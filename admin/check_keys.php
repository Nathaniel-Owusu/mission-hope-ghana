<?php
include 'adverse_sms_client.php';

$keys = [
    'adv_9c0d08bdc5c3927f593b25b4aff7b2cb5e86d99477deaa34bade62321b252dd8',
    'adv_717737f8c1e621d07ba254c8c1cf0ab1e7d4a4da798ce85abfa4faa60831e10b'
];

foreach ($keys as $k) {
    echo "Testing key: " . substr($k, 0, 10) . "... ";
    try {
        $client = new AdverseSmsClient($k);
        $bal = $client->getBalance();
        echo "SUCCESS! Balance: " . $bal['data']['credits_balance'] . "\n";
    } catch (Exception $e) {
        echo "FAILED: " . $e->getMessage() . "\n";
    }
}
