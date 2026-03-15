<?php
$apiKey = 'adv_717737f8c1e621d07ba254c8c1cf0ab1e7d4a4da798ce85abfa4faa60831e10b';
$url = 'https://services.getadverse.com/api/v1/sms/balance';

function test($headers, $label)
{
    global $url;
    echo "Testing $label... ";
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "Code: $code. Resp: " . substr($resp, 0, 100) . "\n";
}

test(['Authorization: Bearer ' . $apiKey], 'Bearer Auth');
test(['X-API-Key: ' . $apiKey], 'X-API-Key Auth');
