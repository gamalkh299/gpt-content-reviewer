<?php

namespace gamalkh\GptContentReviewer\Traits;

trait ReasonHelper
{
    /**
     * Extract the reason for flagging from the response.
     *
     * @return array
     */
    public function getFlaggingReasons(array $response): string
    {
        $categories = $response[0]['categories'] ?? [];
        $scores = $response[0]['category_scores'] ?? [];
        $reasons = [];

        foreach ($categories as $category => $isFlagged) {
            if ($isFlagged) {
                $reasons[] = [
                    'reason' => $category,
                    'score' => $scores[$category] ?? 0,
                ];
            }
        }

        // Sort reasons by score descending (optional, for prioritization)
        usort($reasons, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        $result = $this->getFlaggingSummary($reasons);

        return $result;
    }

    public function getFlaggingSummary(array $response): string
    {
        $reasons = [];
        foreach ($response as $reason) {
            $reasons[] = $reason['reason'];
        }

        return implode(', ', $reasons);
    }
}
