<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Card Generator</title>
<style>
body {
font-family: Arial, sans-serif;
margin: 20px;
background-color: #f5f5f5;
color: #333;
}
h1 {
text-align: center;
}
form {
margin: 0 auto;
max-width: 600px;
padding: 20px;
background: #fff;
border-radius: 8px;
box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}
label {
display: block;
margin-bottom: 8px;
font-weight: bold;
}
input, button {
width: 100%;
padding: 10px;
margin-bottom: 15px;
border: 1px solid #ccc;
border-radius: 4px;
}
button {
background-color: #007bff;
color: white;
font-size: 16px;
cursor: pointer;
}
button:hover {
background-color: #0056b3;
}
textarea {
width: 100%;
height: 150px;
padding: 10px;
margin-top: 10px;
border: 1px solid #ccc;
border-radius: 4px;
resize: none;
}
.copy-btn {
display: block;
margin-top: 10px;
background-color: #28a745;
color: white;
}
.copy-btn:hover {
background-color: #218838;
}
</style>
</head>
<body>
<h1>Credit Card Generator</h1>
<form method="POST">
<label for="bin">BIN (e.g., 411111xxxxxxxxxx)</label>
<input type="text" id="bin" name="bin" required>

<label for="mm">Month (MM, optional)</label>
<input type="text" id="mm" name="mm" placeholder="Leave empty for random">

<label for="yy">Year (YYYY, optional)</label>
<input type="text" id="yy" name="yy" placeholder="Leave empty for random">

<label for="cvv">CVV (optional)</label>
<input type="text" id="cvv" name="cvv" placeholder="Leave empty for random">

<label for="amount">Amount (Number of Cards)</label>
<input type="number" id="amount" name="amount" min="1" placeholder="Default is 10">

<button type="submit">Generate</button>
</form>

<?php
class CardGenerator {
private $bin;
private $mm;
private $yy;
private $cvv;
private $amount;

public function __construct($bin, $mm = '', $yy = '', $cvv = '', $amount = 10) {
$this->bin = $bin;
$this->mm = $mm ?: str_pad(mt_rand(1, 12), 2, '0', STR_PAD_LEFT);
$this->yy = $yy ?: mt_rand(date('y'), date('y') + 10);
$this->cvv = $cvv;
$this->amount = max(1, intval($amount));
}

public function generateCards(): array {
$cards = [];
for ($i = 0; $i < $this->amount; $i++) {
$cards[] = $this->generateCard();
}
return $cards;
}

private function generateCard(): string {
$number = $this->generateCardNumber();
$cvv = $this->cvv ?: $this->generateCVV();
return "$number|$this->mm|$this->yy|$cvv";
}

private function generateCardNumber(): string {
$bin = $this->bin;
$length = substr($bin, 0, 2) === '34' || substr($bin, 0, 2) === '37' ? 15 : 16;

while (strlen($bin) < $length - 1) {
$bin .= mt_rand(0, 9);
}

return $bin . $this->calculateLuhn($bin);
}

private function calculateLuhn($number): int {
$sum = 0;
$reverse = strrev($number);
for ($i = 0; $i < strlen($reverse); $i++) {
$digit = intval($reverse[$i]);
if ($i % 2 === 0) {
$digit *= 2;
if ($digit > 9) $digit -= 9;
}
$sum += $digit;
}
return (10 - ($sum % 10)) % 10;
}

private function generateCVV(): string {
$cvvLength = substr($this->bin, 0, 2) === '34' || substr($this->bin, 0, 2) === '37' ? 4 : 3;
return str_pad(mt_rand(0, pow(10, $cvvLength) - 1), $cvvLength, '0', STR_PAD_LEFT);
}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$bin = $_POST['bin'];
$mm = $_POST['mm'] ?? '';
$yy = $_POST['yy'] ?? '';
$cvv = $_POST['cvv'] ?? '';
$amount = $_POST['amount'] ?? 10;

$generator = new CardGenerator($bin, $mm, $yy, $cvv, $amount);
$cards = $generator->generateCards();

echo '<textarea id="cards" readonly>' . implode("\n", $cards) . '</textarea>';
echo '<button class="copy-btn" onclick="copyToClipboard()">Copy to Clipboard</button>';
}
?>

<script>
function copyToClipboard() {
const textarea = document.getElementById('cards');
textarea.select();
document.execCommand('copy');
alert('Cards copied to clipboard!');
}
</script>
</body>
</html>





4154644401329025|06|28|959
5578292442052530|03|2026|770
5396343722977068|03|2027|488
5205245018818248|02|2028|034
5156240253751120|07|26|998
5568628200740208|03|29|933
5220950688168029|05|29|803
5241050147194651|07|26|664
5241050247624698|07|26|772
5429350068616565|05|2025|199
