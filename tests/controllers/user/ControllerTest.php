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

    public function setUp(): void
    {
        $webMock = $this->getMockBuilder(app\core\Web::class)
            ->setMethods(['callExit', 'sendHeader'])
            ->getMock();
        /*$webMock->expects($this->any())
            ->method('callExit');*/
        Lm::$app->web = $webMock;
    }

    protected function fillUserPostInfo($cityIdParam = null)
    {
        static $cityId;

        if ($cityIdParam) {
            $cityId = $cityIdParam;
        }

        $userPost['User']['name'] = 'Новый Юзер';
        $userPost['User']['age'] = '12';
        $userPost['User']['city_id'] = $cityId;
        $userPost['ajax'] = 'true';

        return $userPost;
    }

    public function testActionCreate()
    {
        $city = new City;
        $city->name = 'Тестовый Город';
        $city->save();

        $_POST = $this->fillUserPostInfo($city->getId());

        self::$controller->actionCreate();

        $outputJson = $this->getActualOutputForAssertion();
        $this->assertJsonStringEqualsJsonString('{"data":{"corrected-attributes":[]},"state":"success"}', $outputJson);
    }

    public function testActionCreateSame()
    {
        self::$controller->actionCreate();

        $outputJson = $this->getActualOutputForAssertion();
        $this->assertJson($outputJson);

        $outputArray = json_decode($outputJson, true);

        $this->assertArrayHasKey('state', $outputArray);
        $this->assertSame($outputArray['state'], 'error');

        $this->assertArrayHasKey('data', $outputArray);
        $this->assertArrayHasKey('error-messages', $outputArray['data']);
        $this->assertNotEmpty($outputArray['data']['error-messages']);
    }

    /*public function testActionUpdate()
    {

    }*/
}
