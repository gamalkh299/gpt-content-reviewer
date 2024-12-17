<?php

namespace gamalkh\GptContentReviewer;

use GuzzleHttp\Client;

class GptContentReviewer {

    protected $client;
    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('gpt-content-review.api_key');
        $this->defaultModel = config('gpt-content-review.default_model');
    }


    public function reviewContent($input, $model = null)
    {
        $modelToUse = $model ?? $this->defaultModel;

        if ($this->isImage($input)) {
            // Process Image Input
            $imageData = $this->getImageBase64($input);

            $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => ['Authorization' => "Bearer {$this->apiKey}"],
                'json' => [
                    'model' => $modelToUse,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a content moderation assistant. Return JSON {"harmful": true/false, "reason": "reason for classification"}.'],
                        ['role' => 'user', 'content' => 'Review this image for nudity or harmful content.'],
                        ['role' => 'user', 'content' => $imageData]
                    ]
                ]
            ]);
        } else {
            // Process Text Input
            $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
                'headers' => ['Authorization' => "Bearer {$this->apiKey}"],
                'json' => [
                    'model' => $modelToUse,
                    'messages' => [
                        ['role' => 'system', 'content' => 'You are a content moderation assistant. Return JSON {"harmful": true/false, "reason": "reason for classification"}.'],
                        ['role' => 'user', 'content' => $input]
                    ]
                ]
            ]);
        }

        return $this->parseJsonResponse($response);
    }


    /**
     * Detect if input is an image (path or URL)
     *
     * @param string $input
     * @return bool
     */
    protected function isImage($input)
    {
        return filter_var($input, FILTER_VALIDATE_URL) || file_exists($input);
    }

    /**
     * Convert Image Path or URL to Base64
     *
     * @param string $input
     * @return string
     */
    protected function getImageBase64($input)
    {
        if (filter_var($input, FILTER_VALIDATE_URL)) {
            $imageData = file_get_contents($input);
        } else {
            $imageData = file_get_contents($input);
        }

        return base64_encode($imageData);
    }

    /**
     * Parse JSON Response
     *
     * @param \Psr\Http\Message\ResponseInterface $response
     * @return array
     */
    protected function parseJsonResponse($response)
    {
        $content = json_decode($response->getBody()->getContents(), true);

        if (isset($content['choices'][0]['message']['content'])) {
            $result = json_decode($content['choices'][0]['message']['content'], true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $result;
            }
        }

        return [
            'harmful' => true,
            'reason' => 'Unable to parse response from GPT.'
        ];
    }




}
