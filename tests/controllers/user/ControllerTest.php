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
class ControllerTest extends TestCase
{
    /** @var  app\controllers\user\Controller */
    protected static $controller;


    public static function setUpBeforeClass(): void
    {
        new Application(LM_GLOBAL_CONFIG);

        Lm::$app->db->delete(User::tableName(), []);
        Lm::$app->db->delete(City::tableName(), []);

        self::$controller = new app\controllers\user\Controller;
    }

    public static function tearDownAfterClass(): void
    {
        self::$controller = null;
    }

    /**
     * @runInSeparateProcess
     */
    public function testActionCreate()
    {
        $webMock = $this->getMockBuilder(app\core\Web::class)
            ->setMethods(['callExit'])
            ->getMock();
        $webMock->expects($this->atLeastOnce())
            ->method('callExit');
        Lm::$app->web = $webMock;

        $city = new City;
        $city->name = 'Тестовый Город';
        $city->save();

        $_POST['User']['name'] = 'Новый Юзер';
        $_POST['User']['age'] = '12';
        $_POST['User']['city_id'] = $city->getId();
        $_POST['ajax'] = 'true';

        //$this->expectOutputString('{"state":"success","data":{"corrected-attributes":[]}}');
        self::$controller->actionCreate();

        $outputJson = $this->getActualOutput();
        $this->assertJsonStringEqualsJsonString('{"state":"success","data":{"corrected-attributes":[]}}', $outputJson);
        //$this->

        //echo "\n>>>$output<<<\n";
    }

    /*public function testActionUpdate()
    {

    }*/
}
