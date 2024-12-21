<?php

namespace gamalkh\GptContentReviewer;

use gamalkh\GptContentReviewer\Jobs\ModerationJob;
use gamalkh\GptContentReviewer\Models\GptReviewer;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class GptContentReviewer
{
    protected Client $client;

    protected mixed $apiKey;

    public function __construct()
    {
        $this->client = new Client;
        $this->apiKey = config('gpt-content-reviewer.api_key');
    }

    /**
     * Create a review entry and dispatch the moderation job.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model  The model to review
     * @return void
     */
    public function createReview($model): void
    {
        $review = GptReviewer::create([
            'reviewable_type' => get_class($model),
            'reviewable_id' => $model->id,
            'status' => 'pending',

        ]);

        ModerationJob::dispatch($review)->onQueue(config('gpt-content-reviewer.queue'));

    }

    /**
     * Moderate the content using OpenAI GPT-3 API.
     *
     * @param  string  $input
     * @return array
     */
    public function ModerateContent($input): array
    {
        $modelToUse = 'omni-moderation-latest';

        try {
            if ($this->isImage($input)) {
                $imageData = $this->getImageBase64($input);

                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$this->apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.openai.com/v1/moderations', [
                    'model' => $modelToUse,
                    'input' => [
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $imageData,
                            ],
                        ],
                    ],
                ]);

            } else {
                //           Process Text Input
                $response = Http::withHeaders([
                    'Authorization' => 'Bearer '.$this->apiKey,
                    'Content-Type' => 'application/json',
                ])->post('https://api.openai.com/v1/moderations', [
                    'model' => $modelToUse,
                    'input' => $input,

                ]);
            }

            return $response['results'];

        } catch (\Exception $e) {
            return [
                'harmful' => true,
                'reason' => 'Unable to parse response from GPT because of: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Detect if input is an image (path or URL)
     *
     * @param  string  $input
     * @return bool
     */
    protected function isImage($input)
    {
        return filter_var($input, FILTER_VALIDATE_URL) || file_exists($input);
    }

    /**
     * Convert Image Path or URL to Base64 with MIME type prefix.
     */
    protected function getImageBase64(string $input): string
    {
        if (filter_var($input, FILTER_VALIDATE_URL)) {
            $imageData = file_get_contents($input);
            $mimeType = $this->getMimeTypeFromUrl($input);
        } else {
            $imageData = file_get_contents($input);
            $mimeType = mime_content_type($input);
        }

        return 'data:'.$mimeType.';base64,'.base64_encode($imageData);
    }

    /**
     * Get the MIME type from a URL.
     */
    protected function getMimeTypeFromUrl(string $url): string
    {
        $headers = get_headers($url, 1);

        return $headers['Content-Type'] ?? 'application/octet-stream';
    }
}
