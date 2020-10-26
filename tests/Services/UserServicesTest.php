<?php

namespace Tests\Services;

use PHPUnit\Framework\TestCase;
use GuzzleHttp\Client;

/**
 * Class UserServicesTest
 * @package Tests\Services
 */
class UserServicesTest extends TestCase
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
        'getBrowserName' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36',
        'getCurrentDateTime' => '2020-10-20T08:55:20+0000',
        'getReqIp' => '192.168.31.22',
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
        $this->http = new Client(['base_uri' => 'https://elmtest.test/app/Services/UserServices.php']);
    }

    public function tearDown() : void
    {
        $this->http = null;
    }

    public function testPostRequestNotAjax() :void
    {
        $response = $this->http->post('', [
            'headers' => [
                'Cache-Control'=>'no-cache',
                'User-Agent'   => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.81 Safari/537.36',
                'Content-Type' => 'application/x-www-form-urlencoded; charset=UTF-8',
                'X-Requested-With' => 'XMLHttpRequest' /**@todo check why XMLHttpRequest not equal in UserServices - for now it's work */
            ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );
        $this->assertEquals(200, $response->getStatusCode(), "testPostRequestNotAjax");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType, "testPostRequestNotAjax" );
        $this->assertIsArray( $jsonToArray, "testPostRequestNotAjax" );
        $this->assertArrayHasKey( "status", $jsonToArray, "testPostRequestNotAjax" );
        $this->assertArrayHasKey( "msg", $jsonToArray, "testPostRequestNotAjax" );
        $this->assertEquals( [ "status" => false, "msg" => "The request not valid ajax" ], $jsonToArray, "testPostRequestNotAjax" );

    }

    public function testPostRequestInvalidMethod() :void
    {
        $response = $this->http->get('', [
            'headers' => $this->headers
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );
        $this->assertEquals(200, $response->getStatusCode(), "testPostRequestInvalidMethod");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType, "testPostRequestInvalidMethod" );
        $this->assertIsArray( $jsonToArray, "testGetRequest" );
        $this->assertArrayHasKey( "status", $jsonToArray, "testPostRequestInvalidMethod" );
        $this->assertArrayHasKey( "msg", $jsonToArray, "testPostRequestInvalidMethod" );
        $this->assertEquals( [ "status" => false, "msg" => "The request not valid method" ], $jsonToArray, "testPostRequestInvalidMethod" );
    }

    public function testPostRequestEmptySection() :void
    {
        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'sec' => '' ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );
        $this->assertEquals(200, $response->getStatusCode(), "testPostRequestEmptySection");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType, "testPostRequestEmptySection" );
        $this->assertIsArray( $jsonToArray, "testPostRequestEmptySection" );
        $this->assertArrayHasKey( "status", $jsonToArray, "testPostRequestEmptySection" );
        $this->assertArrayHasKey( "msg", $jsonToArray, "testPostRequestEmptySection" );
        $this->assertEquals( [ "status" => false, "msg" => "Invalid section" ], $jsonToArray, "testPostRequestEmptySection" );
    }

    public function testPostRequestMissingParam() :void
    {

        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'user' => $this->userA["user"], 'pass' => $this->userA["pass"] ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );
        $this->assertEquals(200, $response->getStatusCode(), "testPostRequestMissingParam");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType, "testPostRequestMissingParam" );
        $this->assertIsArray( $jsonToArray, "testPostRequestMissingParam" );
        $this->assertArrayHasKey( "status", $jsonToArray, "testPostRequestMissingParam" );
        $this->assertArrayHasKey( "msg", $jsonToArray, "testPostRequestMissingParam" );
        $this->assertEquals( [ "status" => false, "msg" => "The request missing parameter" ], $jsonToArray, "testPostRequestMissingParam" );
    }

    public function testLoginRequest()
    {
        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'sec' => 'login', 'user' => $this->userA["user"], 'pass' => $this->userA["pass"] ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );
        $this->assertEquals(200, $response->getStatusCode(), "testLoginRequest");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType, "testLoginRequest" );
        $this->assertIsArray( $jsonToArray, 'testLoginRequest' );
        $this->assertArrayHasKey('status', $jsonToArray, 'testLoginRequest' );
        $this->assertArrayHasKey('msg', $jsonToArray, 'testLoginRequest' );
        $this->assertArrayHasKey('uid', $jsonToArray, 'testLoginRequest' );
        $this->assertArrayHasKey('name', $jsonToArray, 'testLoginRequest' );
        $this->assertArrayHasKey('token', $jsonToArray, 'testLoginRequest' );
        $this->assertCount(5, $jsonToArray, 'testLoginRequest' );
        $this->assertEquals( [ "status" => true, "msg" => "Welcome ".$this->userA["name"], "uid" => "112387623", "name" => "test5", "token" => $jsonToArray["token"] ], $jsonToArray, 'testLoginRequest' );

        return $jsonToArray["token"];
    }

    public function testLoginRequestAllReadyLoggedIn() : void
    {
        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'sec' => 'login', 'user' => $this->userA["user"], 'pass' => $this->userA["pass"] ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );
        $this->assertEquals(200, $response->getStatusCode(), "testLoginRequestAllReadyLoggedIn");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType, "testLoginRequestAllReadyLoggedIn" );
        $this->assertIsArray( $jsonToArray, 'testLoginRequest' );
        $this->assertArrayHasKey('status', $jsonToArray, 'testLoginRequestAllReadyLoggedIn' );
        $this->assertArrayHasKey('msg', $jsonToArray, 'testLoginRequestAllReadyLoggedIn' );
        $this->assertEquals( [ "status" => false, "msg" => "This user already logged in." ], $jsonToArray, 'testLoginRequest' );
    }

    public function testUserDetails() : void
    {
        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'debug' => true, 'sec' => 'user_details', 'id' => $this->userA["id"] ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );

        $this->assertEquals(200, $response->getStatusCode(), "testUserDetails");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType, "testUserDetails" );
        $this->assertIsArray( $jsonToArray, "testUserDetails" );
        $this->assertArrayHasKey( "created_at", $jsonToArray, "testUserDetails" );
        $this->assertArrayHasKey( "logins_count", $jsonToArray, "testUserDetails" );
        $this->assertArrayHasKey( "user_agent", $jsonToArray, "testUserDetails" );
        $this->assertCount(3, $jsonToArray, 'testUserDetails' );
    }

    public function testUserDetailsFakeUser() : void
    {
        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'debug' => true, 'sec' => 'user_details', 'id' => 24254534345 ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );

        $this->assertEquals(200, $response->getStatusCode(), "testUserDetailsFakeUser");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType, "testUserDetailsFakeUser" );
        $this->assertIsArray( $jsonToArray, "testUserDetailsFakeUser" );
        $this->assertArrayHasKey( "status", $jsonToArray, "testUserDetailsFakeUser" );
        $this->assertArrayHasKey( "msg", $jsonToArray, "testUserDetailsFakeUser" );
        $this->assertCount(2, $jsonToArray, 'testUserDetailsFakeUser' );
        $this->assertEquals( [ "status" => false, "msg" => "Can not find online user(s)" ], $jsonToArray, 'testLogoutRequestFakeUser' );
    }

    public function testUsersList() : void
    {
        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'debug' => true, 'sec' => 'user_list' ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );

        $this->assertEquals(200, $response->getStatusCode(), "testUsersList");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType, "testUsersList" );
        $this->assertIsArray( $jsonToArray, "testUsersList" );
        $this->assertArrayHasKey('id', $jsonToArray[0], 'testUsersList' );
        $this->assertArrayHasKey('name', $jsonToArray[0], 'testUsersList' );
        $this->assertArrayHasKey('updated_at', $jsonToArray[0], 'testUsersList' );
        $this->assertArrayHasKey('time', $jsonToArray[0], 'testUsersList' );
        $this->assertArrayHasKey('ip', $jsonToArray[0], 'testUsersList' );
        $this->assertCount(5, $jsonToArray[0], 'testUsersList' );
        $this->assertEquals( "112387623", $jsonToArray[0]["id"], 'testUsersList' );
        $this->assertEquals( "test5", $jsonToArray[0]["name"], 'testUsersList' );
    }

    public function testLogoutRequestFakeUser() : void
    {
        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'debug' => true, 'sec' => 'logout', 'id' => 34234234234234 ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );
        $this->assertEquals(200, $response->getStatusCode(), "testLogoutRequestFakeUser");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType, "testLogoutRequestFakeUser" );
        $this->assertIsArray( $jsonToArray, "testLogoutRequestFakeUser" );
        $this->assertArrayHasKey( "status", $jsonToArray, "testLogoutRequestFakeUser" );
        $this->assertArrayHasKey( "msg", $jsonToArray, "testLogoutRequestFakeUser" );
        $this->assertEquals( [ "status" => false, "msg" => "You still on online mode." ], $jsonToArray, 'testLogoutRequestFakeUser' );
    }

    public function testLogoutRequest() : void
    {
        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'debug' => true, 'sec' => 'logout', 'id' => $this->userA["id"] ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );
        $this->assertEquals(200, $response->getStatusCode(), "testLogoutRequest");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType, "testLogoutRequest" );
        $this->assertIsArray( $jsonToArray, "testLogoutRequest" );
        $this->assertArrayHasKey( "status", $jsonToArray, "testLogoutRequest" );
        $this->assertArrayHasKey( "msg", $jsonToArray, "testLogoutRequest" );
        $this->assertEquals( [ "status" => true, "msg" => "Goodbye test5" ], $jsonToArray, 'testLoginRequest' );

    }

    public function testUserDetailsOffline() : void
    {
        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'debug' => true, 'sec' => 'user_details', 'id' => $this->userA["id"] ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );

        $this->assertEquals(200, $response->getStatusCode(), "testUserDetailsOffline");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType, "testUserDetailsOffline" );
        $this->assertIsArray( $jsonToArray, "testUserDetailsOffline" );
        $this->assertArrayHasKey( "status", $jsonToArray, "testUserDetailsOffline" );
        $this->assertArrayHasKey( "msg", $jsonToArray, "testUserDetailsOffline" );
        $this->assertEquals( [ "status" => false, "msg" => "Can not find online user(s)" ], $jsonToArray, 'testUserDetailsOffline' );
    }

    public function testUsersListOffline() : void
    {
        $response = $this->http->post('', [
            'headers' => $this->headers,
            'form_params' => [ 'debug' => true, 'sec' => 'user_list' ]
        ]);

        $jsonToArray = json_decode( $response->getBody()->getContents(), true );
        $this->assertEquals(200, $response->getStatusCode(), "testUsersListOffline");
        $contentType = $response->getHeaders()["Content-Type"][0];
        $this->assertEquals("application/json", $contentType, "testUsersListOffline" );
        $this->assertIsArray( $jsonToArray, "testUsersListOffline" );
        $this->assertArrayHasKey( "status", $jsonToArray, "testUsersListOffline" );
        $this->assertArrayHasKey( "msg", $jsonToArray, "testUsersListOffline" );
        $this->assertCount(2, $jsonToArray, 'testUsersListOffline' );
        $this->assertEquals( [ "status" => false, "msg" => "Can not find online user(s)" ], $jsonToArray, 'testUsersListOffline' );
    }

}
