<?php
require 'admin/adverse_sms_client.php';
$c = new AdverseSmsClient('adv_a8b415dd84dd1b6a78f75036e2ddafc87ad6b49fc6c11d24a04abc3b3112c580');
try {
    var_dump($c->getBalance());
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
