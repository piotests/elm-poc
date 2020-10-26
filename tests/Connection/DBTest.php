<?php

namespace Tests\Connection;

use App\Connection\DB;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

/**
 * Class DBTest
 * @package Tests
 */
class DBTest extends TestCase
{

    /**
     * Test connection to db and instance (singleton)
     *
     * Create fresh (object) instance for testing
     *
     * @return App\Connection\DB
     */
    public function testGetConnection()
    {
        $single_comp = DB::getConnection();
        $singleton = DB::getConnection(); // no idea what's inside

        $this->assertTrue( $singleton === $single_comp, 'testGetConnection' );

        try {
            $reflection = new ReflectionClass($singleton);
            $instance = $reflection->getProperty('instance');
            $instance->setAccessible(true); // now we can modify that
            $instance->setValue(null, null); // instance is gone
            $instance->setAccessible(false); // clean up
        } catch (\ReflectionException $e) {
            self::throwException($e);
        }

        $singleton = DB::getConnection(); // now recreate a fresh object
        $this->assertFalse( $singleton === $single_comp, 'testGetConnection' );

        return $singleton;
    }

    /**
     * Test get db data
     *
     * Need to get all users from db
     *
     * @return \stdClass
     */
    public function testFileDataReadAll()
    {
        $instance = $this->testGetConnection();
        $fetchData = $instance->fileData();

        $this->assertIsObject( $fetchData, 'testFileDataReadAll' );
        $this->assertObjectHasAttribute('users', $fetchData, 'testFileDataReadAll');
        $this->assertIsArray( $fetchData->users, 'testFileDataReadAll' );
        $this->assertCount(6, $fetchData->users, 'testFileDataReadAll' );
    }

    /**
     * Test get db date
     *
     * Need to get only one user from db
     *
     * @return \stdClass
     */
    public function testFileDataReadOneUser()
    {
        $instance = $this->testGetConnection();
        $fetchData = $instance->fileData("read", ["id" => 289809870],1 );

        $this->assertIsObject( $fetchData, 'testFileDataReadOneUser' );
        $this->assertObjectHasAttribute('id', $fetchData, 'testFileDataReadOneUser' );
        $this->assertObjectHasAttribute('name', $fetchData, 'testFileDataReadOneUser' );
        $this->assertObjectHasAttribute('email', $fetchData, 'testFileDataReadOneUser' );
        $this->assertObjectHasAttribute('password', $fetchData, 'testFileDataReadOneUser' );
        $this->assertObjectHasAttribute('updated_at', $fetchData, 'testFileDataReadOneUser' );
        $this->assertObjectHasAttribute('is_online', $fetchData, 'testFileDataReadOneUser' );
        $this->assertObjectHasAttribute('login_details', $fetchData, 'testFileDataReadOneUser' );
        $this->assertIsArray( $fetchData->login_details, 'testFileDataReadOneUser' );
        $this->assertIsObject( $fetchData->login_details[0], 'testFileDataReadOneUser' );
        $this->assertObjectHasAttribute( 'user_agent', $fetchData->login_details[0], 'testFileDataReadOneUser' );
        $this->assertObjectHasAttribute( 'time', $fetchData->login_details[0], 'testFileDataReadOneUser' );
        $this->assertObjectHasAttribute( 'ip', $fetchData->login_details[0], 'testFileDataReadOneUser' );
        $this->assertCount(0, $fetchData->logout_details, 'testFileDataReadOneUser' );
    }

    /**
     * Test write and get db date
     *
     * Need to write data to user with id and get only one user from db
     *
     * @return \stdClass
     */
    public function testFileDataWriteOneUser()
    {
        $id = '322387490';
        $getBrowserName = 'Chrome';
        $getCurrentDateTime = '2020-10-20T08:55:20+0000';
        $getReqIp = '127.0.0.1';

        $arr = [ "id" => $id, "is_online" => 1, "login_details" => [ "user_agent" => $getBrowserName, "time" => $getCurrentDateTime, "ip" => $getReqIp ] ];

        $instance = $this->testGetConnection();
        $fetchData = $instance->fileData( "write", $arr );

        $this->assertIsObject( $fetchData, 'testFileDataWriteOneUser' );
        $this->assertObjectHasAttribute('id', $fetchData, 'testFileDataWriteOneUser' );
        $this->assertObjectHasAttribute('name', $fetchData, 'testFileDataWriteOneUser' );
        $this->assertObjectHasAttribute('email', $fetchData, 'testFileDataWriteOneUser' );
        $this->assertObjectHasAttribute('password', $fetchData, 'testFileDataWriteOneUser' );
        $this->assertObjectHasAttribute('updated_at', $fetchData, 'testFileDataWriteOneUser' );
        $this->assertObjectHasAttribute('is_online', $fetchData, 'testFileDataWriteOneUser' );
        $this->assertObjectHasAttribute('login_details', $fetchData, 'testFileDataWriteOneUser' );
        $this->assertIsArray( $fetchData->login_details, 'testFileDataWriteOneUser' );
        $this->assertIsObject( $fetchData->login_details[0], 'testFileDataWriteOneUser' );
        $this->assertObjectHasAttribute( 'user_agent', $fetchData->login_details[0], 'testFileDataWriteOneUser' );
        $this->assertObjectHasAttribute( 'time', $fetchData->login_details[0], 'testFileDataWriteOneUser' );
        $this->assertObjectHasAttribute( 'ip', $fetchData->login_details[0], 'testFileDataWriteOneUser' );
        $this->assertCount(0, $fetchData->logout_details, 'testFileDataWriteOneUser' );
        $this->assertEquals( $id, $fetchData->id, 'testFileDataWriteOneUser' );
        $this->assertEquals( 1, $fetchData->is_online, 'testFileDataWriteOneUser' );

        $loginDetails = $fetchData->login_details;
        if ( !empty( $loginDetails ) ) {
            if ( !function_exists( 'array_key_last' ) ) {
                $loginDetails = $loginDetails[ count( $loginDetails ) - 1];
            } else {
                $loginDetails = $loginDetails[ array_key_last( $loginDetails ) ];
            }
        }
        $this->assertEquals( [ "user_agent" => $getBrowserName,"time" => $getCurrentDateTime,"ip" => $getReqIp], (array)$loginDetails, 'testFileDataWriteOneUser' );

        /**
         * Test update field 'is_online' and compare with the last state of same user
         */
        $arrB = [ "id" => $id, "is_online" => 0 ];
        $instanceB = $this->testGetConnection();
        $fetchDataB = $instanceB->fileData( "write", $arrB );

        $this->assertFalse( $fetchDataB->is_online === $fetchData->is_online, 'testFileDataWriteOneUser' );
        $this->assertEquals( 0, $fetchDataB->is_online, 'testFileDataWriteOneUser' );

        /**
         * Test update field 'is_online' with fake userId
         */
        $arrC = [ "id" => '1231212', "is_online" => 0 ];
        $instanceC = $this->testGetConnection();
        $fetchDataC = $instanceC->fileData( "write", $arrC );

        $this->assertIsObject( $fetchDataC, 'testFileDataWriteOneUser' );
        $this->assertObjectHasAttribute( 'users', $fetchDataC, 'testFileDataWriteOneUser' );
        $this->assertIsArray( $fetchDataC->users, 'testFileDataWriteOneUser' );
        $this->assertCount(6, $fetchDataC->users, 'testFileDataWriteOneUser' );

    }
}
