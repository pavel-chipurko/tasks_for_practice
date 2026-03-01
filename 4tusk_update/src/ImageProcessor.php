<?php
// src/ImageProcessor.php

class ImageProcessor
{
    /**
     * Преобразует JSON (строку) или iterable данных в результирующий массив.
     * Возвращает массив записанных записей.
     *
     * @param string|iterable $input JSON string или iterable с уже декодированными элементами
     * @param string $imageDir
     * @return array
     */
    public static function process($input, string $imageDir): array
    {
        // Если передана строка — декодируем
        if (is_string($input)) {
            $decoded = json_decode($input, true);
            if ($decoded === null) return [];
            $items = $decoded;
        } elseif (is_iterable($input)) {
            $items = $input;
        } else {
            return [];
        }

        if (!is_dir($imageDir)) mkdir($imageDir, 0777, true);

        $out = [];

        // Предполагаем верхний уровень {"call": {...}}
        $call = $items['call'] ?? $items;

        // image meta (shared)
        $imageName = $call['image_name'] ?? null;
        $imageInfo = $call['image'] ?? null;
        if (!$imageName || !is_array($imageInfo)) return [];

        $link = $imageInfo['link'] ?? null;
        $base64 = $imageInfo['base64'] ?? null;
        if (!$base64) return [];

        // обычно product entries — все ключи кроме image/image_name
        foreach ($call as $k => $product) {
            if (!is_array($product)) continue;
            if (!isset($product['tradeble'])) continue;
            if ($product['tradeble'] !== "true" && $product['tradeble'] !== true) continue;

            $name = $product['name'] ?? null;

            // убираем префикс
            $b = preg_replace('#^data:[^;]+;base64,#i', '', $base64);
            $data = base64_decode($b);
            if ($data === false) continue;

            // уникальное имя: imageName + md5(name+link+time) чтобы не перетёреть
            $unique = $imageName . '_' . substr(sha1(($name ?? '') . '|' . ($link ?? '') . '|' . microtime(true)), 0, 8);
            $path = rtrim($imageDir, '/\\') . DIRECTORY_SEPARATOR . $unique . '.jpeg';

            // запись двоичных данных
            $fp = fopen($path, 'wb');
            if ($fp === false) continue;
            fwrite($fp, $data);
            fclose($fp);

            $out[] = [
                'image_name' => $unique,
                'link' => $link,
                'file_path' => $path,
                'name' => $name
            ];
        }

        return $out;
    }
}