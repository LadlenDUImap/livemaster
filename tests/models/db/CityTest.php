<?php

use PHPUnit\Framework\TestCase;
use app\models\db\City;

/**
 * @requires PHP >= 7.1
 * 
 */
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
        unset($this->city);
    }

    /**
     * @dataProvider correctPropertyDataProvider
     */
    public function testCorrectProperty($name, $value, $expected): void
    {
        $result = $this->city->correctProperty($name, $value);
        $this->assertSame($expected, $result);
    }

    /*public function testValidateProperty()
    {

    }*/

    public function correctPropertyDataProvider()
    {
        return [
            ['name', '   123     78 ', '123 78'],
            ['dummy', '   123     78 ', '123     78'],
            ['name', 'true_value', false],
        ];
    }
}
