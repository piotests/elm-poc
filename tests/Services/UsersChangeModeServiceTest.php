<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

/**
 * Class UsersChangeModeServiceTest
 * @package Tests\Services
 */
class UsersChangeModeServiceTest extends TestCase
{
    protected ?Client $http;

    /**
     * @var array
     */
    private array $userA = [
        'id' => 112387623,
        'name' => 'test5',
        'user' => 'test5@gmail.com',
        'pass' => 'test5pass',
        'token' => ''
    ];

    /**
     * @var array
     */
    private array $headers = [
        'Cache-Control'=>'no-cache',
        'User-Agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36',
        'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
        'Accept'     => 'application/json',
    ];

    public function setUp() : void
    {
        $this->http = new Client(['base_uri' => 'https://elmtest.test/app/Services/ChangeModeService.php']);
    }

    public function tearDown() : void
    {
        $this->http = null;
    }

    public function testChangeUserStatusToOnline() :void
    {
        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'action' => 'change_status', 'id' => $this->userA["id"], 'state' => 'online' ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );
        $this->assertEquals(200, $response->getStatusCode(), "testChangeUserStatusToOnline");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("text/html; charset=UTF-8", $contentType, "testChangeUserStatusToOnline" );
        $this->assertIsArray( $jsonToArray, "testChangeUserStatusToOnline" );
        $this->assertArrayHasKey( "status", $jsonToArray, "testChangeUserStatusToOnline" );
        $this->assertArrayHasKey( "msg", $jsonToArray, "testChangeUserStatusToOnline" );
        $this->assertArrayHasKey( "uid", $jsonToArray, "testChangeUserStatusToOnline" );
        $this->assertArrayHasKey( "name", $jsonToArray, "testChangeUserStatusToOnline" );
        $this->assertCount(4, $jsonToArray, 'testChangeUserStatusToOnline' );
        $this->assertEquals( [ "status" => true, "msg" => "Welcome back ".$this->userA["name"], "uid" => $this->userA["id"], "name" => $this->userA["name"] ], $jsonToArray, "testChangeUserStatusToOnline" );
    }

    public function testChangeFakeUserStatusToOnline() :void
    {
        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'action' => 'change_status', 'id' => 523523525, 'state' => 'online' ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );
        $this->assertEquals(200, $response->getStatusCode(), "testChangeFakeUserStatusToOnline");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("text/html; charset=UTF-8", $contentType, "testChangeFakeUserStatusToOnline" );
        $this->assertIsArray( $jsonToArray, "testChangeFakeUserStatusToOnline" );
        $this->assertArrayHasKey( "status", $jsonToArray, "testChangeFakeUserStatusToOnline" );
        $this->assertArrayHasKey( "msg", $jsonToArray, "testChangeFakeUserStatusToOnline" );
        $this->assertEquals( [ "status" => false, "msg" => "Can't changed status" ], $jsonToArray, "testChangeFakeUserStatusToOnline" );
    }

    public function testChangeUserStatusToOffline() :void
    {
        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'action' => 'change_status', 'id' => $this->userA["id"], 'state' => 'offline' ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );
        $this->assertEquals(200, $response->getStatusCode(), "testChangeUserStatusToOffline");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("text/html; charset=UTF-8", $contentType, "testChangeUserStatusToOffline" );
        $this->assertIsArray( $jsonToArray, "testChangeUserStatusToOffline" );
        $this->assertArrayHasKey( "status", $jsonToArray, "testChangeUserStatusToOffline" );
        $this->assertArrayHasKey( "msg", $jsonToArray, "testChangeUserStatusToOffline" );
        $this->assertEquals( [ "status" => true, "msg" => "Your status changed to offline" ], $jsonToArray, "testChangeUserStatusToOffline" );
    }

    public function testChangeFakeUserStatusToOffline() :void
    {
        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'action' => 'change_status', 'id' => 523523525, 'state' => 'offline' ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );
        $this->assertEquals(200, $response->getStatusCode(), "testChangeFakeUserStatusToOffline");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("text/html; charset=UTF-8", $contentType, "testChangeFakeUserStatusToOffline" );
        $this->assertIsArray( $jsonToArray, "testChangeFakeUserStatusToOffline" );
        $this->assertArrayHasKey( "status", $jsonToArray, "testChangeFakeUserStatusToOffline" );
        $this->assertArrayHasKey( "msg", $jsonToArray, "testChangeFakeUserStatusToOffline" );
        $this->assertEquals( [ "status" => false, "msg" => "You still on online mode." ], $jsonToArray, "testChangeFakeUserStatusToOffline" );
    }

}
