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
    public function testActionUpdate()
    {
        $webMock = $this->getMockBuilder(app\core\Web::class)
            //->setMethods(['dieWithTheString'])
            ->setMethods(null)
            ->getMock();

        /*$webMock->expects($this->once())
            ->method('dieWithTheString');*/

        Lm::$app->web = $webMock;


        $city = new City;
        $city->name = 'Тестовый Город';
        $city->save();


        $_POST['User']['name'] = 'Новый Юзер';
        $_POST['User']['age'] = '12';
        $_POST['User']['city_id'] = $city->getId();
        $_POST['ajax'] = 'true';

        self::$controller->actionCreate();

        //print_r($result);
        //$this->expectOutputString('YOU SHALL NOT PASS');


        /*$originalClassName = app\controllers\user\Controller::class;

        //$stub = $this->createStub($originalClassName);

//        $stub->method('actionUpdate')
//            ->willReturn('foo');

        //$this->assertSame('foo', $stub->actionUpdate());
        $mock = $this->getMockBuilder($originalClassName)
            ->disableOriginalConstructor()
            //->disableOriginalClone()
            //->disableArgumentCloning()
            ->disallowMockingUnknownTypes()
            ->getMock();

        $this->assertSame('foo', $mock->actionUpdate());*/
    }

}
