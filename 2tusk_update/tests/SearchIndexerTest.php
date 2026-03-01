<?php
// tests/SearchIndexerTest.php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/SearchIndexer.php';

class SearchIndexerTest extends TestCase
{
    private function makeData(): array
    {
        $data = [];
        for ($i=0;$i<120;$i++) {
            $data["c{$i}"] = ['price' => $i % 10, 'name' => 'name' . ($i % 5)];
        }
        $data['dupA'] = ['price'=>5,'name'=>'name2'];
        $data['dupB'] = ['price'=>5,'name'=>'name2'];
        return $data;
    }

    public function testByPrice()
    {
        $idx = new SearchIndexer($this->makeData());
        $res = $idx->search(5, null);
        $this->assertNotEmpty($res);
        foreach ($res as $item) $this->assertEquals(5, $item['price']);
    }

    public function testByName()
    {
        $idx = new SearchIndexer($this->makeData());
        $res = $idx->search(null, 'name3');
        $this->assertNotEmpty($res);
        foreach ($res as $it) $this->assertEquals('name3', $it['name']);
    }

    public function testUnique()
    {
        $idx = new SearchIndexer($this->makeData());
        $res = $idx->search(5, 'name2');
        $serialized = array_map('serialize', $res);
        $this->assertCount(count(array_unique($serialized)), $res);
    }

    public function testEmpty()
    {
        $idx = new SearchIndexer([]);
        $this->assertEmpty($idx->search(1, 'x'));
    }
}