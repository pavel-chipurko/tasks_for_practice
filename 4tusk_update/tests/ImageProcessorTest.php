<?php
// tests/ImageProcessorTest.php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../src/ImageProcessor.php';

class ImageProcessorTest extends TestCase
{
    private string $tmpDir;

    protected function setUp(): void
    {
        $this->tmpDir = __DIR__ . '/tmp_images';
        if (!is_dir($this->tmpDir)) mkdir($this->tmpDir, 0777, true);
    }

    protected function tearDown(): void
    {
        foreach (glob($this->tmpDir . '/*') as $f) @unlink($f);
        @rmdir($this->tmpDir);
    }

    public function testProcessSingleTradeable()
    {
        $img = base64_encode('this-is-fake-image-bytes');
        $json = json_encode([
            'call'=>[
                'productA' => ['tradeble' => 'true', 'name' => 'main_window'],
                'image_name' => 'sun1',
                'image' => ['link'=>'https://product_web','base64'=>'data:image/jpeg;base64,' . $img]
            ]
        ]);

        $res = ImageProcessor::process($json, $this->tmpDir);
        $this->assertCount(1, $res);
        $this->assertFileExists($res[0]['file_path']);
    }

    public function testNonTradeableSkipped()
    {
        $img = base64_encode('data');
        $json = json_encode([
            'call'=>[
                'productA' => ['tradeble' => 'false', 'name' => 'main_window'],
                'image_name' => 'sun1',
                'image' => ['link'=>'https://product_web','base64'=>'data:image/jpeg;base64,' . $img]
            ]
        ]);

        $res = ImageProcessor::process($json, $this->tmpDir);
        $this->assertEmpty($res);
    }
}