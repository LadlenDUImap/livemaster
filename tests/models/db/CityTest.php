<?php

use PHPUnit\Framework\TestCase;
use app\models\db\City;
use app\core\Application;

/**
 * @requires PHP >= 7.1
 * 
 */
class CityTest extends TestCase
{
    /** @var  City */
    private static $city;


    public static function setUpBeforeClass(): void
    {
        new Application(LM_GLOBAL_CONFIG);
        self::$city = new City();
    }

    public static function tearDownAfterClass(): void
    {
        self::$city = null;
    }

    /**
     * @dataProvider correctPropertyDataProvider
     */
    public function testCorrectProperty($name, $value, $expected): void
    {
        $result = self::$city->correctProperty($name, $value);
        $this->assertSame($expected, $result);
    }

    public function testValidateProperty()
    {
        $result = self::$city->validateProperty('name', 'Правильное Название');
        $this->assertSame(false, $result);
    }

    public function correctPropertyDataProvider()
    {
        return [
            ['name', '   123     45 ', '123 45'],
            ['dummy', '   123     4 ', '123     4'],
            ['name', 'Не Подлежит Коррекции', false],
        ];
    }

    /*public function validatePropertyDataProvider()
    {
        return [
            ['name', 'Правильное Название', false],
            ['name', 'значение больше 30 символов 12345', is_string()],
        ];
    }*/
}
