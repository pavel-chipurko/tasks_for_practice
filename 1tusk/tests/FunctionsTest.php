<?php
// tests/FunctionsTest.php

use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../src/functions.php';

class FunctionsTest extends TestCase
{
    public function testLowQuantity()
    {
        $this->assertEquals(2.5, low_quantity(5));
        $this->assertEquals(1.5, low_quantity(3));
    }

    public function testHighQuantity()
    {
        $this->assertEquals(25, high_quantity(50));
        $this->assertEquals(30, high_quantity(60));
    }

    public function testMediumQuantity()
    {
        $this->assertEquals(0, medium_quantity(10));
    }

    public function testProcessDataLow()
    {
        $this->assertEquals(3, process_data(5)); // 2.5 -> round -> 3
    }

    public function testProcessDataHigh()
    {
        $this->assertEquals(25, process_data(50));
    }

    public function testProcessDataMedium()
    {
        $this->assertEquals(0, process_data(10));
    }

    public function testProcessDataNormal()
    {
        $this->assertEquals(20, process_data(20));
    }

    public function testUniqueRange1()
    {
        $this->assertEquals(12, count_unique_results(1, 15));
    }

    public function testUniqueRange2()
    {
        $this->assertEquals(36, count_unique_results(3, 55));
    }

    public function testUniqueRange3()
    {
        $this->assertEquals(32, count_unique_results(9, 43));
    }
}