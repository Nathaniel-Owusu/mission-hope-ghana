<?php
// AdverseSmsClient.php

class AdverseSmsClient
{
    private string $apiKey;
    private string $baseUrl = 'https://services.getadverse.com/api/v1';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    private function request(string $method, string $path, ?array $body = null, array $params = []): array
    {
        $url = $this->baseUrl . $path;
        if ($params) {
            $params = array_filter($params, fn($v) => $v !== null);
            if ($params) $url .= '?' . http_build_query($params);
        }

        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $this->apiKey,
                'Content-Type: application/json',
            ],
            CURLOPT_CUSTOMREQUEST => $method,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_SSL_VERIFYPEER => false // Added for local dev environments potentially lacking certs
        ]);

        if ($body !== null) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new Exception("cURL Error: " . $curlError);
        }

        $data = json_decode($response, true);

        // Handle non-2xx responses or API-level errors
        if ($httpCode >= 400 || (isset($data['success']) && $data['success'] === false)) {
            $code = $data['error']['code'] ?? $httpCode;
            $message = $data['error']['message'] ?? $data['message'] ?? 'Unknown error';
            throw new Exception("[{$code}] {$message}");
        }

        return $data;
    }

    // Sending
    public function send($to, string $message, int $senderId, string $title = 'API SMS'): array
    {
        return $this->request('POST', '/v1/sms/send', [
            'to' => $to,
            'message' => $message,
            'sender_id' => $senderId,
            'title' => $title,
        ]);
    }

    public function sendPersonalized(
        string $message,
        int $senderId,
        array $recipients,
        string $title = 'API Personalized SMS'
    ): array {
        return $this->request('POST', '/v1/sms/send/personalized', [
            'message' => $message,
            'sender_id' => $senderId,
            'recipients' => $recipients,
            'title' => $title,
        ]);
    }

    public function schedule(
        string $scheduleAt,
        $to,
        string $message,
        int $senderId,
        string $title = 'API Scheduled SMS'
    ): array {
        return $this->request('POST', '/v1/sms/schedule', [
            'schedule_at' => $scheduleAt,
            'to' => $to,
            'message' => $message,
            'sender_id' => $senderId,
            'title' => $title,
        ]);
    }

    // Account
    public function getBalance(): array
    {
        return $this->request('GET', '/v1/sms/balance');
    }
    public function getStats(): array
    {
        return $this->request('GET', '/v1/sms/stats');
    }

    // Campaigns
    public function listCampaigns(int $page = 1, int $limit = 20): array
    {
        return $this->request('GET', '/v1/sms/campaigns', null, ['page' => $page, 'limit' => $limit]);
    }
    public function getCampaign(int $id): array
    {
        return $this->request('GET', "/v1/sms/campaigns/{$id}");
    }
    public function cancelCampaign(int $id): array
    {
        return $this->request('POST', "/v1/sms/campaigns/{$id}/cancel");
    }

    // Contacts
    public function listContactLists(): array
    {
        return $this->request('GET', '/v1/sms/contacts/lists');
    }
    public function createContactList(string $name): array
    {
        return $this->request('POST', '/v1/sms/contacts/lists', ['name' => $name]);
    }
    public function getContactList(int $id, int $page = 1, int $limit = 50): array
    {
        return $this->request('GET', "/v1/sms/contacts/lists/{$id}", null, ['page' => $page, 'limit' => $limit]);
    }
    public function addContacts(int $listId, array $contacts): array
    {
        return $this->request('POST', "/v1/sms/contacts/lists/{$listId}/contacts", ['contacts' => $contacts]);
    }
    public function deleteContactList(int $id): array
    {
        return $this->request('DELETE', "/v1/sms/contacts/lists/{$id}");
    }

    // Sender IDs
    public function listSenderIds(): array
    {
        return $this->request('GET', '/v1/sms/sender-ids');
    }
    public function listApprovedSenderIds(): array
    {
        return $this->request('GET', '/v1/sms/sender-ids/approved');
    }
    public function registerSenderId(string $name, string $purpose): array
    {
        return $this->request('POST', '/v1/sms/sender-ids', ['sender_name' => $name, 'purpose' => $purpose]);
    }

    // Templates
    public function listTemplates(?string $category = null, ?string $search = null): array
    {
        return $this->request('GET', '/v1/sms/templates', null, ['category' => $category, 'search' => $search]);
    }
    public function createTemplate(string $name, string $message, string $category = 'Custom'): array
    {
        return $this->request('POST', '/v1/sms/templates', compact('name', 'message', 'category'));
    }

    // Webhooks
    public function listWebhooks(): array
    {
        return $this->request('GET', '/v1/sms/webhooks');
    }
    public function createWebhook(string $url, array $events): array
    {
        return $this->request('POST', '/v1/sms/webhooks', ['url' => $url, 'events' => $events]);
    }
    public function deleteWebhook(int $id): array
    {
        return $this->request('DELETE', "/v1/sms/webhooks/{$id}");
    }
    public function getWebhookLogs(int $id, int $limit = 20): array
    {
        return $this->request('GET', "/v1/sms/webhooks/{$id}/logs", null, ['limit' => $limit]);
    }
}
