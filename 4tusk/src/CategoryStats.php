<?php

function processCategoryData(array $data): array
{
    if (!isset($data['category']) || !is_array($data['category'])) {
        return [
            'max_bot_count' => null,
            'min_bot_count' => null,
            'sorted_views' => []
        ];
    }

    $categories = $data['category'];

    $botCounts = [];
    $sorted = [];

    foreach ($categories as $item) {

        if (!isset($item['views']['bot_count'], $item['views']['user_count'], $item['priority'])) {
            continue;
        }

        $botCounts[] = $item['views']['bot_count'];

        $sorted[] = [
            'priority' => (int)$item['priority'],
            'user_count' => $item['views']['user_count'],
            'bot_count' => $item['views']['bot_count']
        ];
    }

    usort($sorted, function ($a, $b) {
        return $a['priority'] <=> $b['priority'];
    });

    return [
        'max_bot_count' => !empty($botCounts) ? max($botCounts) : null,
        'min_bot_count' => !empty($botCounts) ? min($botCounts) : null,
        'sorted_views' => $sorted
    ];
}