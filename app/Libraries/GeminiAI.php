<?php

namespace App\Libraries;

class GeminiAI
{
  protected $apiKey;
  protected $baseUrl;
  protected $client;

  public function __construct()
  {
    $this->apiKey = getenv('GEMINI_API_KEY');
    $this->baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.5-flash-lite:generateContent';

    $this->client = \Config\Services::curlrequest();
  }

  public function generateText($prompt)
  {
    try {
      $url = $this->baseUrl . '?key=' . $this->apiKey;

      $body = [
        'contents' => [
          [
            'parts' => [
              ['text' => $prompt]
            ]
          ]
        ]
      ];

      $response = $this->client->request('POST', $url, [
        'headers' => [
          'Content-Type' => 'application/json'
        ],
        'json' => $body,
        'http_errors' => false
      ]);

      $result = json_decode($response->getBody(), true);

      if (isset($result['error'])) {
        return "Error: " . $result['error']['message'];
      }

      return $result['candidates'][0]['content']['parts'][0]['text'] ?? 'Tidak ada respon.';
    } catch (\Exception $e) {
      return "Terjadi kesalahan sistem: " . $e->getMessage();
    }
  }
}
