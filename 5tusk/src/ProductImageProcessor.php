<?php

function processApiResponse(string $json, string $imageDir = __DIR__ . '/../image_folder'): array
{
    $decoded = json_decode($json, true);

    if (!isset($decoded['call'])) {
        return [];
    }

    $result = [];

    $callData = $decoded['call'];

    // Проверяем наличие обязательных данных
    if (
        !isset($callData['image_name'], $callData['image']['link'], $callData['image']['base64'])
    ) {
        return [];
    }

    foreach ($callData as $key => $product) {

        if (!is_array($product) || !isset($product['tradeble'])) {
            continue;
        }

        if ($product['tradeble'] !== "true") {
            continue;
        }

        $imageName = $callData['image_name'];
        $link = $callData['image']['link'];
        $base64 = $callData['image']['base64'];
        $name = $product['name'] ?? null;

        
        $base64 = preg_replace('#^data:image/\w+;base64,#i', '', $base64);
        $imageData = base64_decode($base64);

        if ($imageData === false) {
            continue;
        }

        if (!is_dir($imageDir)) {
            mkdir($imageDir, 0777, true);
        }

        $filePath = $imageDir . '/' . $imageName . '.jpeg';

        file_put_contents($filePath, $imageData);

        $result[] = [
            'image_name' => $imageName,
            'link' => $link,
            'file_path' => $filePath,
            'name' => $name
        ];
    }

    return $result;
}