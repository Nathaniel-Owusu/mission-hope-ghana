<?php
// Adverse SMS Webhook Endpoint
// URL to register on Adverse: https://YOUR-DOMAIN.com/admin/webhook_sms.php

include_once 'db.php';

// Your webhook secret (you get this when creating the webhook via API or Adverse dashboard)
$webhook_secret = ''; // Paste your webhook secret here after creating the webhook

// Read incoming payload
$body = file_get_contents('php://input');
$signature = $_SERVER['HTTP_X_ADVERSE_SIGNATURE'] ?? '';

// Verify signature if secret is set
if (!empty($webhook_secret)) {
    $hash = hash_hmac('sha256', $body, $webhook_secret);
    $expected = "sha256={$hash}";

    if (!hash_equals($expected, $signature)) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid signature']);
        exit;
    }
}

// Parse the event
$event = json_decode($body, true);

if (!$event) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payload']);
    exit;
}

// Ensure webhook_logs table exists
$conn->query("CREATE TABLE IF NOT EXISTS sms_webhook_logs (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    event_type VARCHAR(50) NOT NULL,
    campaign_id INT(11),
    payload TEXT,
    received_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
)");

// Log the webhook event
$event_type = $event['event'] ?? 'unknown';
$campaign_id = $event['data']['campaign_id'] ?? null;
$payload_json = $body;

$stmt = $conn->prepare("INSERT INTO sms_webhook_logs (event_type, campaign_id, payload) VALUES (?, ?, ?)");
$stmt->bind_param("sis", $event_type, $campaign_id, $payload_json);
$stmt->execute();

// Handle specific events
switch ($event_type) {
    case 'sms.delivered':
        // SMS was delivered successfully
        if ($campaign_id) {
            $conn->query("UPDATE sms_history SET status='Delivered' WHERE id={$campaign_id}");
        }
        break;

    case 'sms.failed':
        // SMS delivery failed
        if ($campaign_id) {
            $conn->query("UPDATE sms_history SET status='Failed' WHERE id={$campaign_id}");
        }
        break;

    case 'campaign.completed':
        // Entire campaign completed
        break;

    case 'campaign.cancelled':
        // Campaign was cancelled
        break;

    case 'sms.sent':
        // SMS was sent to gateway
        break;
}

// Always respond 200 to acknowledge receipt
http_response_code(200);
echo json_encode(['received' => true]);
