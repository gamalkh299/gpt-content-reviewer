<?php

namespace gamalkh\GptContentReviewer;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;

class GptContentReviewer {

    protected Client $client;
    protected mixed $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('gpt-content-reviewer.api_key');
    }


    /**
     * @param $input
     * @return array
     */
    public function ModerateContent($input): array
    {
            $modelToUse = 'omni-moderation-latest';

        try {

            if ($this->isImage($input)) {
                $imageData = $this->getImageBase64($input);


                $response = Http::withHeaders([
                    'Authorization'=>'Bearer '.$this->apiKey,
                    'Content-Type'=>'application/json'
                ])->post('https://api.openai.com/v1/moderations', [
                    'model' => $modelToUse,
                    'input'=>[
                        [
                            'type'=>'text',
                            'text'=>$input
                        ],
                        [
                            'type'=>'image_url',
                            'image_url'=>[
                                'url'=>$imageData
                            ]
                        ]
                    ]
                ]);


            } else {
//           Process Text Input
                $response = Http::withHeaders([
                    'Authorization'=>'Bearer '.$this->apiKey,
                    'Content-Type'=>'application/json'
                ])->post('https://api.openai.com/v1/moderations', [
                    'model' => $modelToUse,
                    'input'=>$input,

                ]);
            }

            return $response['results'];



        }catch (\Exception $e) {
            return [
                'harmful' => true,
                'reason' => 'Unable to parse response from GPT because of: '.$e->getMessage()
            ];
        }
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





}
