<?php

namespace gamalkh\GptContentReviewer\Traits;

trait ReasonHelper
{
    /**
     * Extract the reason for flagging from the response.
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

        if (empty($reasons)) {
            return 'No reasons found';
        }

        // Sort reasons by score descending (optional, for prioritization)
        usort($reasons, function ($a, $b) {
            return $b['score'] <=> $a['score'];
        });

        return $this->getFlaggingSummary($reasons);
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
