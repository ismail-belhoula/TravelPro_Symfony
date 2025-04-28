<?php

namespace App\Service;

class ProfanityFilterService
{
    private string $apiKey;
    private string $apiEndpoint = 'https://api.api-ninjas.com/v1/profanityfilter';

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function filterText(string $text): string
    {
        $curl = curl_init();
        
        curl_setopt_array($curl, [
            CURLOPT_URL => $this->apiEndpoint . '?text=' . urlencode($text),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'X-Api-Key: ' . $this->apiKey,
                'Content-Type: application/json'
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        
        curl_close($curl);

        if ($err) {
            return $text;
        }

        $result = json_decode($response, true);
        return $result['censored'] ?? $text;
    }
}