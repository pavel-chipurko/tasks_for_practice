<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/CategoryStats.php';

class CategoryStatsTest extends TestCase
{
    private function getData(): array
    {
        return [
            'category' => [
                'one' => [
                    'priority' => '3',
                    'views' => [
                        'user_count' => 345,
                        'bot_count' => 9392
                    ]
                ],
                'two' => [
                    'priority' => '1',
                    'views' => [
                        'user_count' => 123222,
                        'bot_count' => 99
                    ]
                ],
                'three' => [
                    'priority' => '2',
                    'views' => [
                        'user_count' => 23,
                        'bot_count' => 1
                    ]
                ],
            ]
        ];
    }

    public function testMaxBotCount()
    {
        $result = processCategoryData($this->getData());
        $this->assertEquals(9392, $result['max_bot_count']);
    }

    public function testMinBotCount()
    {
        $result = processCategoryData($this->getData());
        $this->assertEquals(1, $result['min_bot_count']);
    }

    public function testSortingByPriority()
    {
        $result = processCategoryData($this->getData());
        $sorted = $result['sorted_views'];

        $this->assertEquals(1, $sorted[0]['priority']);
        $this->assertEquals(2, $sorted[1]['priority']);
        $this->assertEquals(3, $sorted[2]['priority']);
    }

    public function testWorksWithExtendedData()
    {
        $data = $this->getData();

        $data['category']['four'] = [
            'priority' => '4',
            'views' => [
                'user_count' => 555,
                'bot_count' => 777
            ]
        ];

        $result = processCategoryData($data);

        $this->assertEquals(9392, $result['max_bot_count']);
        $this->assertEquals(1, $result['min_bot_count']);
        $this->assertCount(4, $result['sorted_views']);
    }

    public function testEmptyInput()
    {
        $result = processCategoryData([]);
        $this->assertNull($result['max_bot_count']);
        $this->assertNull($result['min_bot_count']);
        $this->assertEmpty($result['sorted_views']);
    }
}