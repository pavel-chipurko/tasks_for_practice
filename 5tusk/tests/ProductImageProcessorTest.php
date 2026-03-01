<?php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/ProductImageProcessor.php';

class ProductImageProcessorTest extends TestCase
{
    private string $testDir;

    protected function setUp(): void
    {
        $this->testDir = __DIR__ . '/test_images';

        if (!is_dir($this->testDir)) {
            mkdir($this->testDir, 0777, true);
        }
    }

    protected function tearDown(): void
    {
        foreach (glob($this->testDir . '/*') as $file) {
            unlink($file);
        }

        if (is_dir($this->testDir)) {
            rmdir($this->testDir);
        }
    }

    public function testSuccessfulProcessing()
    {
        $fakeImage = base64_encode("fake_image_content");

        $json = json_encode([
            "call" => [
                "product1" => [
                    "tradeble" => "true",
                    "name" => "main_window"
                ],
                "image_name" => "sun1",
                "image" => [
                    "link" => "https://product_web",
                    "base64" => "data:image/jpeg;base64," . $fakeImage
                ]
            ]
        ]);

        $result = processApiResponse($json, $this->testDir);

        $this->assertCount(1, $result);
        $this->assertEquals("sun1", $result[0]['image_name']);
        $this->assertEquals("https://product_web", $result[0]['link']);
        $this->assertEquals("main_window", $result[0]['name']);
        $this->assertFileExists($result[0]['file_path']);
    }

    public function testTradebleFalse()
    {
        $json = json_encode([
            "call" => [
                "product1" => [
                    "tradeble" => "false",
                    "name" => "main_window"
                ],
                "image_name" => "sun1",
                "image" => [
                    "link" => "https://product_web",
                    "base64" => "data:image/jpeg;base64," . base64_encode("data")
                ]
            ]
        ]);

        $result = processApiResponse($json, $this->testDir);

        $this->assertEmpty($result);
    }

    public function testInvalidJsonStructure()
    {
        $result = processApiResponse('{}', $this->testDir);
        $this->assertEmpty($result);
    }
}