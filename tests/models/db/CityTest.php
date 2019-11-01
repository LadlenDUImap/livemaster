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

    /**
     * @dataProvider correctPropertyDataProvider
     */
    public function testCorrectProperty($name, $value, $expected): void
    {
        $result = $this->city->correctProperty($name, $value);
        $this->assertEquals($expected, $result);
    }

    public function correctPropertyDataProvider()
    {
        return [
            ['name', '   123     78 ', '123 78'],
            ['dummy', '   123     78 ', '123     78'],
            ['name', 'true_value', false],
        ];
    }
}
