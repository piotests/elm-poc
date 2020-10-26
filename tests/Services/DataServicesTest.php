<?php

namespace Tests\Services;

use App\Services\DataServices;
use PHPUnit\Framework\TestCase;

/**
 * Class DataServicesTest
 * @package Tests
 */
class DataServicesTest extends TestCase
{

    /**
     * @var App\Services\DataServices
     */
    private static $dataService;

    /**
     * @var array
     */
    private array $userA = [
        'id' => 112387623,
        'name' => 'test5',
        'user' => 'test5@gmail.com',
        'pass' => 'test5pass',
        'getBrowserName' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36',
        'getCurrentDateTime' => '2020-10-20T08:55:20+0000',
        'getReqIp' => '192.168.31.22',
        'token' => ''
    ];

    /**
     * @var array
     */
    private array $userB = [
        'id' => 289809870,
        'name' => 'test2',
        'user' => 'test2@gmail.com',
        'pass' => 'test2pass',
        'getBrowserName' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36',
        'getCurrentDateTime' => '2020-10-20T08:55:20+0000',
        'getReqIp' => '192.168.31.22',
        'token' => ''
    ];

    /**
     * @var array
     */
    private array $userFake = [
        'id' => 43423423423,
        'name' => 'fakeName',
        'user' => 'test5gmail.com',
        'pass' => 'test9pass',
        'getBrowserName' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36',
        'getCurrentDateTime' => '2020-10-20T08:55:20+0000',
        'getReqIp' => '192.168.31.22',
        'token' => ''
    ];

    /**
     * @var array|string[]
     */
    private array $userEmpty = [
        'id' => '',
        'name' => '',
        'user' => '',
        'pass' => '',
        'getBrowserName' => '',
        'getCurrentDateTime' => '',
        'getReqIp' => '',
        'token' => ''
    ];


    /**
     * Create instance to class DataServices and insert it to prop $dataService
     */
    public static function setUpBeforeClass() : void
    {
        self::$dataService = new DataServices;
    }

    /**
     * Test logout
     *
     * @return void
     */
    public function testLogout() : void
    {
        $res = self::$dataService->logout( "logout", $this->userA["id"]);

        $this->assertIsArray( $res, 'testLogout' );
        $this->assertArrayHasKey('status', $res, 'testLogout' );
        $this->assertArrayHasKey('msg', $res, 'testLogout' );
        $this->assertCount(2, $res, 'testLogout' );
        $this->assertEquals( [ "status" => true, "msg" => "Goodbye test5" ], $res, 'testLogout' );

        /**
         * Try to logout with fake userID
         */
        $resB = self::$dataService->logout( "logout", $this->userFake["id"]);
        $this->assertIsArray( $resB, 'testLoginIsOnlineFalse' );
        $this->assertArrayHasKey('status', $resB, 'testLoginIsOnlineFalse' );
        $this->assertArrayHasKey('msg', $resB, 'testLoginIsOnlineFalse' );
        $this->assertCount(2, $resB, 'testLoginIsOnlineFalse' );
        $this->assertEquals( [ "status" => false, "msg" => "You still on online mode." ], $resB, 'testLoginIsOnlineFalse' );
    }

    /**
     * Test login without user details
     *
     * Try to validate user without details
     *
     * @return void
     */
    public function testLoginWithoutUserDetails() :void
    {
        $res = self::$dataService->login("login", null, $this->userEmpty["user"], $this->userEmpty["pass"], [] );

        $this->assertIsArray( $res, 'testLoginWithoutUserDetails' );
        $this->assertArrayHasKey('status', $res, 'testLoginWithoutUserDetails' );
        $this->assertArrayHasKey('msg', $res, 'testLoginWithoutUserDetails' );
        $this->assertCount(2, $res, 'testLoginWithoutUserDetails' );
        $this->assertEquals( [ "status" => false, "msg" => "Please enter username." ], $res, 'testLoginWithoutUserDetails' );
    }

    /**
     * Test login without username
     *
     * Try to validate user without username
     *
     * @return void
     */
    public function testLoginWithoutUserName() : void
    {
        $res = self::$dataService->login("login", null, $this->userEmpty["user"], $this->userFake["pass"], [] );

        $this->assertIsArray( $res, 'testLoginWithoutUserName' );
        $this->assertArrayHasKey('status', $res, 'testLoginWithoutUserName' );
        $this->assertArrayHasKey('msg', $res, 'testLoginWithoutUserName' );
        $this->assertCount(2, $res, 'testLoginWithoutUserName' );
        $this->assertEquals( [ "status" => false, "msg" => "Please enter username." ], $res, 'testLoginWithoutUserName' );
    }

    /**
     * Test login user with invalid username
     *
     * Try to validate user with invalid username
     *
     * @return void
     */
    public function testLoginWithInvalidEmail() : void
    {
        $res = self::$dataService->login( "login", null, $this->userFake["user"], $this->userEmpty["pass"], [] );

        $this->assertIsArray( $res, 'testLoginWithNotValidEmail' );
        $this->assertArrayHasKey('status', $res, 'testLoginWithNotValidEmail' );
        $this->assertArrayHasKey('msg', $res, 'testLoginWithNotValidEmail' );
        $this->assertCount(2, $res, 'testLoginWithNotValidEmail' );
        $this->assertEquals( [ "status" => false, "msg" => $this->userFake["user"]." is not a valid email address" ], $res, 'testLoginWithNotValidEmail' );
    }

    /**
     * Test login user without password
     *
     * Try to validate user without password
     *
     * @return void
     */
    public function testLoginWithoutUserPass() : void
    {
        $res = self::$dataService->login( "login", null, $this->userA["user"], $this->userEmpty["pass"], [] );

        $this->assertIsArray( $res, 'testLoginWithoutUserPass' );
        $this->assertArrayHasKey('status', $res, 'testLoginWithoutUserPass' );
        $this->assertArrayHasKey('msg', $res, 'testLoginWithoutUserPass' );
        $this->assertCount(2, $res, 'testLoginWithoutUserPass' );
        $this->assertEquals( [ "status" => false, "msg" => "Please enter password." ], $res, 'testLoginWithoutUserPass' );
    }

    /**
     * Test login real user
     *
     * Try to validate real user
     *
     * @return void
     */
    public function testLogin() : void
    {
        $res = self::$dataService->login( "login", null, $this->userA["user"], $this->userA["pass"], [ "user_agent" => $this->userA["getBrowserName"], "time" => $this->userA["getCurrentDateTime"], "ip" => $this->userA["getReqIp"] ] );

        $this->assertIsArray( $res, 'testLogin' );
        $this->assertArrayHasKey('status', $res, 'testLogin' );
        $this->assertArrayHasKey('msg', $res, 'testLogin' );
        $this->assertArrayHasKey('uid', $res, 'testLogin' );
        $this->assertArrayHasKey('name', $res, 'testLogin' );
        $this->assertArrayHasKey('token', $res, 'testLogin' );
        $this->assertCount(5, $res, 'testLogin' );
        $this->assertEquals( [ "status" => true, "msg" => "Welcome ".$this->userA["name"], "uid" => "112387623", "name" => "test5", "token" => $res["token"] ], $res, 'testLogin' );
    }

    /**
     * Test login again with same user
     *
     * Try to login again with same real user details and get msg "This user already logged in."
     *
     * @return void
     */
    public function testLoginAgainWithSameUser() : void
    {
        $res = self::$dataService->login( "login", null, $this->userA["user"], $this->userA["pass"], [ "user_agent" => $this->userA["getBrowserName"], "time" => $this->userA["getCurrentDateTime"], "ip" => $this->userA["getReqIp"] ] );

        $this->assertIsArray( $res, 'testLoginAgainWithSameUser' );
        $this->assertArrayHasKey('status', $res, 'testLoginAgainWithSameUser' );
        $this->assertArrayHasKey('msg', $res, 'testLoginAgainWithSameUser' );
        $this->assertCount(2, $res, 'testLoginAgainWithSameUser' );
        $this->assertEquals( [ "status" => false, "msg" => "This user already logged in." ], $res, 'testLoginAgainWithSameUser' );
    }

    /**
     * Test login is online true
     *
     * Try to change field "is_online" to true with the details of logged in user
     *
     * @return void
     */
    public function testLoginIsOnlineTrue() : void
    {
        $res = self::$dataService->login( "change_status", $this->userA["id"], "", "", [] );

        $this->assertIsArray( $res, 'testLoginIsOnlineTrue' );
        $this->assertArrayHasKey('status', $res, 'testLoginIsOnlineTrue' );
        $this->assertArrayHasKey('msg', $res, 'testLoginIsOnlineTrue' );
        $this->assertArrayHasKey('uid', $res, 'testLoginIsOnlineTrue' );
        $this->assertArrayHasKey('name', $res, 'testLoginIsOnlineTrue' );
        $this->assertCount(4, $res, 'testLoginIsOnlineTrue' );
        $this->assertEquals( [ "status" => true, "msg" => "Welcome back test5", "uid" => "112387623", "name" => "test5" ], $res, 'testLoginIsOnlineTrue' );
    }

    /**
     * Test login is online false
     *
     * Try to change field "is_online" to false with the details of logged in user
     *
     * @return void
     */
    public function testLogoutIsOnlineFalse() : void
    {
        $res = self::$dataService->logout( "change_status", $this->userA["id"] );

        $this->assertIsArray( $res, 'testLoginIsOnlineFalse' );
        $this->assertArrayHasKey('status', $res, 'testLoginIsOnlineFalse' );
        $this->assertArrayHasKey('msg', $res, 'testLoginIsOnlineFalse' );
        $this->assertCount(2, $res, 'testLoginIsOnlineFalse' );
        $this->assertEquals( [ "status" => true, "msg" => "Your status changed to offline" ], $res, 'testLoginIsOnlineFalse' );

        /**
         * Try to change_status with fake userID
         */
        $resB = self::$dataService->logout( "change_status", $this->userFake["id"] );

        $this->assertIsArray( $resB, 'testLoginIsOnlineFalse' );
        $this->assertArrayHasKey('status', $resB, 'testLoginIsOnlineFalse' );
        $this->assertArrayHasKey('msg', $resB, 'testLoginIsOnlineFalse' );
        $this->assertCount(2, $resB, 'testLoginIsOnlineFalse' );
        $this->assertEquals( [ "status" => false, "msg" => "You still on online mode." ], $resB, 'testLoginIsOnlineFalse' );
    }

    /**
     * Test fetch all
     *
     * Try to get all online users
     *
     * @return void
     */
    public function testFetchAll() : void
    {
        $res = self::$dataService->fetchAll();

        $this->assertIsArray( $res, 'testFetchAll' );
        $this->assertArrayHasKey('status', $res, 'testFetchAll' );
        $this->assertArrayHasKey('msg', $res, 'testFetchAll' );
        $this->assertCount(2, $res, 'testFetchAll' );
        $this->assertEquals( [ "status" => false, "msg" => "Can not find online user(s)" ], $res, 'testFetchAll' );

        /**
         * Try to login user again
         */
        $this->testLogin();

        $resB = self::$dataService->fetchAll();

        $this->assertIsArray( $resB, 'testLoginIsOnlineTrue' );
        $this->assertArrayHasKey('id', $resB[0], 'testLoginIsOnlineTrue' );
        $this->assertArrayHasKey('name', $resB[0], 'testLoginIsOnlineTrue' );
        $this->assertArrayHasKey('updated_at', $resB[0], 'testLoginIsOnlineTrue' );
        $this->assertArrayHasKey('time', $resB[0], 'testLoginIsOnlineTrue' );
        $this->assertArrayHasKey('ip', $resB[0], 'testLoginIsOnlineTrue' );
        $this->assertCount(5, $resB[0], 'testLoginIsOnlineTrue' );
        $this->assertEquals( "112387623", $resB[0]["id"], 'testLoginIsOnlineTrue' );
        $this->assertEquals( "test5", $resB[0]["name"], 'testLoginIsOnlineTrue' );
    }

    /**
     * Test fetch one
     *
     * Try to get one user details from online users list
     *
     * @return void
     */
    public function testFetchOne() : void
    {
        $res = self::$dataService->fetchOne( $this->userA["id"] );

        $this->assertIsArray( $res, 'testFetchOne' );
        $this->assertArrayHasKey('created_at', $res, 'testFetchOne' );
        $this->assertArrayHasKey('logins_count', $res, 'testFetchOne' );
        $this->assertArrayHasKey('user_agent', $res, 'testFetchOne' );
        $this->assertCount(3, $res, 'testFetchOne' );

        $resB = self::$dataService->fetchOne( $this->userFake["id"] );

        $this->assertIsArray( $resB, 'testFetchOne' );
        $this->assertArrayHasKey('status', $resB, 'testFetchOne' );
        $this->assertArrayHasKey('msg', $resB, 'testFetchOne' );
        $this->assertCount(2, $resB, 'testFetchOne' );
        $this->assertEquals( [ "status" => false, "msg" => "Can not find online user(s)" ], $resB, 'testFetchOne' );

        $this->testLogout();

        $resC = self::$dataService->fetchOne( $this->userA["id"] );

        $this->assertIsArray( $resC, 'testFetchOne' );
        $this->assertArrayHasKey('status', $resC, 'testFetchOne' );
        $this->assertArrayHasKey('msg', $resC, 'testFetchOne' );
        $this->assertCount(2, $resC, 'testFetchOne' );
        $this->assertEquals( [ "status" => false, "msg" => "Can not find online user(s)" ], $resC, 'testFetchOne' );

    }
}
