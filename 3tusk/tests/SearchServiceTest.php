<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/SearchService.php';

class SearchServiceTest extends TestCase
{
    private function getBigData(): array
    {
        $data = [];

        // создаём 120 элементов
        for ($i = 1; $i <= 120; $i++) {
            $data["category{$i}"] = [
                'price' => $i % 10,
                'name'  => 'name' . ($i % 5),
            ];
        }

        // добавим дубликаты специально
        $data['duplicate1'] = ['price' => 5, 'name' => 'name2'];
        $data['duplicate2'] = ['price' => 5, 'name' => 'name2'];

        return $data;
    }

    public function testSearchByPrice()
    {
        $data = $this->getBigData();
        $result = search($data, 5, null);

        foreach ($result as $item) {
            $this->assertEquals(5, $item['price']);
        }

        $this->assertNotEmpty($result);
    }

    public function testSearchByName()
    {
        $data = $this->getBigData();
        $result = search($data, null, 'name3');

        foreach ($result as $item) {
            $this->assertEquals('name3', $item['name']);
        }

        $this->assertNotEmpty($result);
    }

    public function testSearchByPriceOrName()
    {
        $data = $this->getBigData();
        $result = search($data, 7, 'name1');

        $this->assertNotEmpty($result);
    }

    public function testNoDuplicates()
    {
        $data = $this->getBigData();
        $result = search($data, 5, 'name2');

        $uniqueCount = count(array_unique(array_map('serialize', $result)));

        $this->assertEquals($uniqueCount, count($result));
    }

    public function testEmptyResult()
    {
        $data = $this->getBigData();
        $result = search($data, 999, 'notfound');

        $this->assertEmpty($result);
    }
}