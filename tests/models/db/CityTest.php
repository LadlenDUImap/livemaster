<?php

use PHPUnit\Framework\TestCase;
use app\models\db\City;

class CityTest extends TestCase
{
    /** @var  City */
    private $city;


    protected function setUp(): void
    {
        $this->city = new City();
    }

    protected function tearDown(): void
    {
        $this->city = null;
    }

    public function testCorrectProperty(): void
    {
        $res = $this->city->correctProperty('name', '   34    123 ');
        $this->assertEquals('34 123', $res);
    }
}
