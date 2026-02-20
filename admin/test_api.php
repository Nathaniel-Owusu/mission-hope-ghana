<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'adverse_sms_client.php';

$apiKey = 'adv_9c0d08bdc5c3927f593b25b4aff7b2cb5e86d99477deaa34bade62321b252dd8';
$client = new AdverseSmsClient($apiKey);

echo "<h2>Adverse API Connection Test</h2>";
echo "<p>Testing API Key: " . substr($apiKey, 0, 10) . "...</p>";

try {
    echo "<h3>1. Testing Balance Endpoint...</h3>";
    $balance = $client->getBalance();
    echo "<pre style='background:#f0f0f0; padding:10px;'>";
    print_r($balance);
    echo "</pre>";
    echo "<p style='color:green'>✅ Balance Check Successful</p>";

    echo "<h3>2. Testing Sender IDs Endpoint...</h3>";
    $senders = $client->listSenderIds();
    echo "<pre style='background:#f0f0f0; padding:10px;'>";
    print_r($senders);
    echo "</pre>";
    echo "<p style='color:green'>✅ Sender IDs Check Successful</p>";
} catch (Exception $e) {
    echo "<p style='color:red'>❌ API Error: " . $e->getMessage() . "</p>";
}
