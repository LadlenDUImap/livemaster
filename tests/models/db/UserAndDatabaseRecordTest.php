<?php

use PHPUnit\Framework\TestCase;
use app\core\Application;
use app\core\Lm;
use app\models\db\User;
use app\models\db\City;


/**
 * @requires PHP >= 7.1
 *
 */
class UserAndDatabaseRecordTest extends TestCase
{
    /** @var  User */
    private static $user;


    public static function setUpBeforeClass(): void
    {
        new Application(LM_GLOBAL_CONFIG);

        self::$user = new User();
    }

    public static function tearDownAfterClass(): void
    {
        self::$user = null;
    }

    /**
     * @dataProvider correctPropertyDataProvider
     */
    public function testCorrectProperty($name, $value, $expected): void
    {
        $result = self::$user->correctProperty($name, $value);
        $this->assertSame($expected, $result);
    }

    public function testCRUD()
    {
        Lm::$app->db->delete(User::tableName(), []);
        Lm::$app->db->delete(City::tableName(), []);

        $result =  Lm::$app->db->select(User::tableName());
        $this->assertSame([], $result);

        $user = new User;
        $this->assertTrue($user->isNew());

        //$user->name = ;


    }

    public function testValidateProperty()
    {
        Lm::$app->db->delete(User::tableName(), []);
        Lm::$app->db->delete(City::tableName(), []);

        // Имя

        $result = self::$user->validateProperty('name', 'Правильное Название');
        $this->assertSame(false, $result);

        $result = self::$user->validateProperty('name', '');
        $this->assertTrue(is_string($result), 'Тест пустого имени. Получен тип "' . gettype($result) . '" вместо строки ошибки.');
        echo "\nТест пустого имени выдал '$result'\n";

        $result = self::$user->validateProperty('name', 'значение больше 30 символов 12345');
        $this->assertTrue(is_string($result), 'Тест слишком длинного имени. Получен тип "' . gettype($result) . '" вместо строки ошибки.');
        echo "\nТест слишком длинного имени выдал '$result'\n";


        // Возраст

        $result = self::$user->validateProperty('age', '140');
        $this->assertFalse($result, 'Тест нормального возраста. Получен тип "' . gettype($result) . '"');

        $result = self::$user->validateProperty('age', '');
        $this->assertTrue(is_string($result), 'Тест пустого возраста. Получен тип "' . gettype($result) . '" вместо строки ошибки.');
        echo "\nТест пустого возраста выдал '$result'\n";

        $result = self::$user->validateProperty('age', '14text');
        $this->assertTrue(is_string($result), 'Тест возраста с не-числовыми символами. Получен тип "' . gettype($result) . '" вместо строки ошибки.');
        echo "\nТест возраста с не-числовыми символами выдал '$result'\n";

        $result = self::$user->validateProperty('age', '1400');
        $this->assertTrue(is_string($result), 'Тест слишком большого возраста. Получен тип "' . gettype($result) . '" вместо строки ошибки.');
        echo "\nТест слишком большого возраста выдал '$result'\n";


        // Город

        $result = self::$user->validateProperty('city_id', '1');
        $this->assertTrue(is_string($result), 'Тест отстутствия города. Получен тип "' . gettype($result) . '" вместо строки ошибки.');
        echo "\nТест отстутствия города выдал '$result'\n";

        $city = new City();
        $city->name = 'test';
        $city->save();
        $cityId = $city->getId();

        $result = self::$user->validateProperty('city_id', $cityId);
        $this->assertFalse($result, 'Тест наличия города. Получен тип "' . gettype($result) . '"');


        // Аналогичный пользователь

        $user = new User();
        $user->name = 'пользователь_1';
        $user->age = 20;
        $user->city_id = $cityId;
        $user->save();

        $result = self::$user->validatePropertyBulk([
            'name' => $user->name,
            'age' => $user->age,
            'city_id' => $user->city_id,
        ]);
        $this->assertIsArray($result);
        $this->assertNotEmpty($result, 'Тест аналогичного пользователя. Получен пустой тип вместо массива с ошибкой.');
        echo "\nТест аналогичного пользователя выдал " . print_r($result) . "\n";
    }

    public function correctPropertyDataProvider()
    {
        return [
            ['name', '   123     45 ', '123 45'],
            ['dummy', '   123     4 ', '123     4'],
            ['name', 'Не Подлежит Коррекции', false],
            ['age', '   в  оз    ра ст     ', 'в  оз    ра ст'],
        ];
    }
}
