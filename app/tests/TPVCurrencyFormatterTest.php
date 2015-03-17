<?php

class TPVCurrencyFormatterTest extends TestCase
{
    public function testFormatter()
    {
        $this->assertEquals(1295, TPVCurrencyFormatter::format('12.95'));
        $this->assertEquals(1200, TPVCurrencyFormatter::format('12.00'));
        $this->assertEquals(1290, TPVCurrencyFormatter::format('12.9'));
        $this->assertEquals(1200, TPVCurrencyFormatter::format('12.0'));
        $this->assertEquals(1200, TPVCurrencyFormatter::format('12'));
        $this->assertEquals(1295, TPVCurrencyFormatter::format('12,95'));
        $this->assertEquals(1200, TPVCurrencyFormatter::format('12,00'));
        $this->assertEquals(1290, TPVCurrencyFormatter::format('12,9'));
        $this->assertEquals(1200, TPVCurrencyFormatter::format('12,0'));
        $this->assertEquals(1200, TPVCurrencyFormatter::format('12'));
    }
}