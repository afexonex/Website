<?php
require 'config.php';
// Binance API credentials
$api_key = 'YOUR_BINANCE_API_KEY';
$api_secret = 'YOUR_BINANCE_API_SECRET';

// Function to generate a deposit address
function generateCryptoAddress($asset) {
    global $api_key, $api_secret;
    
    $timestamp = time() * 1000; // Binance expects timestamp in milliseconds
    $query = "asset=$asset&timestamp=$timestamp";
    $signature = hash_hmac('sha256', $query, $api_secret);

    $url = "https://api.binance.com/sapi/v1/capital/deposit/address?$query&signature=$signature";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "X-MBX-APIKEY: $api_key"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);
    return $data['address'] ?? null; // Return the generated address or null if failed
}

// Function to check if payment has been received
function checkWalletPayment($asset, $walletAddress, $amount) {
    global $api_key, $api_secret;

    $timestamp = time() * 1000;
    $query = "asset=$asset&timestamp=$timestamp";
    $signature = hash_hmac('sha256', $query, $api_secret);

    $url = "https://api.binance.com/sapi/v1/capital/deposit/hisrec?$query&signature=$signature";

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "X-MBX-APIKEY: $api_key"
    ]);
    $response = curl_exec($ch);
    curl_close($ch);

    $deposits = json_decode($response, true);

    // Check if any deposit matches the wallet address and amount
    foreach ($deposits as $deposit) {
        if ($deposit['address'] === $walletAddress && $deposit['amount'] >= $amount) {
            return true; // Payment has been received
        }
    }
    return false;
}

// Process payment request from user
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = 1; // Replace with actual user ID
    $amount = $_POST['amount'];
    $cryptoType = $_POST['crypto_type'];

    // Generate a new wallet address
    $walletAddress = generateCryptoAddress($cryptoType);

    if ($walletAddress) {
        // Save transaction to the database
        $stmt = $conn->prepare("INSERT INTO transactions (user_id, crypto_type, amount, wallet_address, status) VALUES (?, ?, ?, ?, 'pending')");
        $stmt->bind_param("isds", $userId, $cryptoType, $amount, $walletAddress);
        $stmt->execute();

        echo "<h3>Payment Details</h3>";
        echo "Send $amount $cryptoType to the following address:<br><strong>$walletAddress</strong><br>";
        echo "Your payment status is pending. This page will check for updates periodically.";
    } else {
        echo "Failed to generate a wallet address. Please try again later.";
    }
}

// Check pending payments and update balance if payment is received
function checkPendingPayments() {
    global $conn;

    $result = $conn->query("SELECT * FROM transactions WHERE status = 'pending'");
    while ($transaction = $result->fetch_assoc()) {
        if (checkWalletPayment($transaction['crypto_type'], $transaction['wallet_address'], $transaction['amount'])) {
            // Update transaction status to completed
            $update = $conn->prepare("UPDATE transactions SET status = 'completed' WHERE id = ?");
            $update->bind_param("i", $transaction['id']);
            $update->execute();

            // Update user's balance
            $updateUserBalance = $conn->prepare("UPDATE users SET balance = balance + ? WHERE id = ?");
            $updateUserBalance->bind_param("di", $transaction['amount'], $transaction['user_id']);
            $updateUserBalance->execute();

            echo "Payment received for transaction ID {$transaction['id']}. Balance updated.<br>";
        }
    }
}

// Call the check function to simulate periodic payment confirmation
checkPendingPayments();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crypto Payment</title>
</head>
<body>
    <h1>Crypto Payment</h1>
    <form method="POST">
        <label for="amount">Amount:</label>
        <input type="number" step="0.0001" id="amount" name="amount" required>

        <label for="crypto_type">Cryptocurrency:</label>
        <select id="crypto_type" name="crypto_type" required>
            <option value="BTC">Bitcoin (BTC)</option>
            <option value="ETH">Ethereum (ETH)</option>
            <!-- Add more options as needed -->
        </select>

        <button type="submit">Generate Payment Address</button>
    </form>
</body>
</html>