<?php

function search(array $data, ?int $price, ?string $name): array
{
    $result = [];

    foreach ($data as $item) {

        $matchPrice = $price !== null && isset($item['price']) && $item['price'] === $price;
        $matchName  = $name !== null && isset($item['name']) && $item['name'] === $name;

        if ($matchPrice || $matchName) {
            $result[] = $item;
        }
    }

    // Удаляем дубликаты
    $unique = [];
    $serialized = [];

    foreach ($result as $item) {
        $key = serialize($item);
        if (!in_array($key, $serialized, true)) {
            $serialized[] = $key;
            $unique[] = $item;
        }
    }

    return $unique;
}