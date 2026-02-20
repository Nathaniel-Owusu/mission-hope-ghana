<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'adverse_sms_client.php';

$apiKey = 'adv_f8dc982f5c52f87f09dfe9141a74c5fb96a8dfecd521b934e5e7d2b8cf5561cb';

echo "<h2>Adverse API Connection Test</h2>";
echo "<p>API Key: " . substr($apiKey, 0, 10) . "...</p>";
echo "<p>Base URL: https://app.adverseforms.com/api</p>";
echo "<hr>";

// Step 0: Test Auth Verify endpoint (recommended by Adverse support)
echo "<h3>0. Testing Auth Verify Endpoint...</h3>";
$ch = curl_init('https://services.getadverse.com/api/v1/auth/verify');
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
    ],
    CURLOPT_TIMEOUT => 15,
    CURLOPT_SSL_VERIFYPEER => false
]);
$resp = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$curlErr = curl_error($ch);
curl_close($ch);

echo "<p>HTTP Code: <b>{$httpCode}</b></p>";
if ($curlErr) echo "<p style='color:red'>cURL Error: {$curlErr}</p>";
echo "<pre style='background:#f0f0f0; padding:10px;'>" . htmlspecialchars($resp) . "</pre>";

if ($httpCode == 200) {
    echo "<p style='color:green'>✅ Auth Verify Successful!</p>";
} else {
    echo "<p style='color:red'>❌ Auth Verify Failed (HTTP {$httpCode})</p>";
}

echo "<hr>";

// Step 1: Test Balance
echo "<h3>1. Testing Balance Endpoint...</h3>";
$client = new AdverseSmsClient($apiKey);
try {
    $balance = $client->getBalance();
    echo "<pre style='background:#f0f0f0; padding:10px;'>";
    print_r($balance);
    echo "</pre>";
    echo "<p style='color:green'>✅ Balance Check Successful</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Balance Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";

// Step 2: Test Sender IDs
echo "<h3>2. Testing Sender IDs Endpoint...</h3>";
try {
    $senders = $client->listSenderIds();
    echo "<pre style='background:#f0f0f0; padding:10px;'>";
    print_r($senders);
    echo "</pre>";
    echo "<p style='color:green'>✅ Sender IDs Check Successful</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Sender IDs Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";

// Step 3: Test Campaigns
echo "<h3>3. Testing Campaigns Endpoint...</h3>";
try {
    $campaigns = $client->listCampaigns(1, 5);
    echo "<pre style='background:#f0f0f0; padding:10px;'>";
    print_r($campaigns);
    echo "</pre>";
    echo "<p style='color:green'>✅ Campaigns Check Successful</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Campaigns Error: " . $e->getMessage() . "</p>";
}

echo "<hr>";

// Step 4: Test Contact Lists
echo "<h3>4. Testing Contact Lists Endpoint...</h3>";
try {
    $lists = $client->listContactLists();
    echo "<pre style='background:#f0f0f0; padding:10px;'>";
    print_r($lists);
    echo "</pre>";
    echo "<p style='color:green'>✅ Contact Lists Check Successful</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>❌ Contact Lists Error: " . $e->getMessage() . "</p>";
}
