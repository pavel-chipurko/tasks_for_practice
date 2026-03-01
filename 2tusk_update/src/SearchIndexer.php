<?php
// src/SearchIndexer.php

class SearchIndexer
{
    private array $data;
    private array $byPrice = [];
    private array $byName = [];

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->buildIndexes();
    }

    private function buildIndexes(): void
    {
        foreach ($this->data as $key => $item) {
            $price = $item['price'] ?? null;
            $name  = $item['name']  ?? null;

            if ($price !== null) {
                $this->byPrice[$price][] = $key;
            }
            if ($name !== null) {
                $this->byName[$name][] = $key;
            }
        }
    }

    /**
     * @param int|null $price
     * @param string|null $name
     * @return array items (unique by content)
     */
    public function search(?int $price, ?string $name): array
    {
        $keys = [];

        if ($price !== null && isset($this->byPrice[$price])) {
            foreach ($this->byPrice[$price] as $k) {
                $keys[$k] = true;
            }
        }

        if ($name !== null && isset($this->byName[$name])) {
            foreach ($this->byName[$name] as $k) {
                $keys[$k] = true;
            }
        }

        // Собираем результаты, но убираем дубликаты по содержимому элемента
        $result = [];
        $seen = []; // набор сериализованных значений

        foreach (array_keys($keys) as $k) {
            $item = $this->data[$k];

            // ключ уникальности — сериализация (можно заменить на md5(serialize(...)) для экономии памяти)
            $sig = serialize($item);

            if (isset($seen[$sig])) {
                continue; // уже есть такой содержимый элемент
            }

            $seen[$sig] = true;
            $result[] = $item;
        }

        return $result;
    }
}