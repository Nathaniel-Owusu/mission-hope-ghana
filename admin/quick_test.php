<?php
$apiKey = 'adv_dbd7f13966940cd36d41a05f4bed7ad0c888e71b63d12a7c94520e15fb528c5d';

$urls = [
    'services.getadverse.com' => 'https://services.getadverse.com/api/v1/sms/balance',
    'app.adverseforms.com' => 'https://app.adverseforms.com/api/v1/sms/balance',
];

foreach ($urls as $label => $url) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Authorization: Bearer ' . $apiKey],
        CURLOPT_TIMEOUT => 10,
        CURLOPT_SSL_VERIFYPEER => false
    ]);
    $resp = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $err = curl_error($ch);
    curl_close($ch);
    echo "$label => HTTP $code";
    if ($err) echo " | ERROR: $err";
    if ($resp) echo " | $resp";
    echo "\n";
}
