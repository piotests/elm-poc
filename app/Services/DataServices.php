<?php

namespace App\Services;

//require_once '../../vendor/autoload.php';

use App\Connection\DB;

/**
 * Class DataServices
 * @package App\Services
 */
class DataServices
{
    /**
     * @var DB|null
     */
    private ?DB $instance = null;

    /**
     * DataServices constructor.
     */
    public function __construct()
    {
        $this->instance = DB::getConnection();
    }

    /**
     * Get all or one users details
     *
     * @param bool $custom - case: this param equal to true display one user details
     * @param int|null $user_id - case: this param not equal to null display one user details
     *
     * @return array
     */
    public function fetchAll( ?bool $custom = false, ?int $user_id = null ) : array
    {
        
        $users = [];
    
        /**
         * Get all users data from db
         *
         * @return array
         */
        $fetchData = $this->instance->fileData();

        if ( is_object( $fetchData ) && property_exists( $fetchData, 'users' ) )
        {

            $inc = 0;
            for ( $i = 0; $i < count( $fetchData->users ); $i++ ) {
                /**
                 * Check if user isn't online or userId from request not equal to userId from db
                 * If one of them true continue to next iterate
                 */
                if ( $fetchData->users[$i]->is_online === 0 || !empty( $user_id ) && $user_id != $fetchData->users[$i]->id ) {
                    continue;
                }

                if ( $custom ) {
                    $users["created_at"] = $fetchData->users[$i]->updated_at;
                    $users["logins_count"] = count( $fetchData->users[$i]->login_details );
                } else {
                    $users[$inc]["id"] = $fetchData->users[$i]->id;
                    $users[$inc]["name"] = $fetchData->users[$i]->name;
                    $users[$inc]["updated_at"] = $fetchData->users[$i]->updated_at;
                }

                /**
                 * Check if user has some data of an array "login_details",
                 * Case not empty return the last index of an array
                 */
                $loginDetails = $fetchData->users[$i]->login_details;
                if ( !empty( $loginDetails ) ) {
                    if ( !function_exists( 'array_key_last' ) ) {
                        $loginDetails = $loginDetails[ count( $loginDetails ) - 1 ];
                    } else {
                        $loginDetails = $loginDetails[ array_key_last( $loginDetails ) ];
                    }
                    if ( $custom ) {
                        $users["user_agent"] = $loginDetails->user_agent;
                    } else {
                        $users[$inc]["time"] = $loginDetails->time;
                        $users[$inc]["ip"] = $loginDetails->ip;
                    }
                }

                /**
                 * Check if argument $user_id is not null and if it's equal to userId from db
                 * This case break the loop and return one user details
                 */
                if ( !empty( $user_id ) && $user_id == $fetchData->users[$i]->id ) {
                    break;
                }
                $inc++;
            }
        }
        
        if ( empty( $users ) ) {
            $users = [ "status" => false, "msg" => "Can not find online user(s)" ];
        }
        
        return $users;
    }
    
    /**
     * Get one user details
     *
     * @param int|null $user_id
     *
     * @return array
     */
    public function fetchOne( ?int $user_id = null ) : array
    {
        $users = [];
        if ( !empty( $user_id ) ) {
            $users = $this->fetchAll( true, $user_id );
        }
        
        if ( empty( $users ) ) {
            $users = [ "status" => false, "msg" => "Can not find online user(s)" ];
        }
        
        return $users;
    }
    
    /**
     * User login process
     *
     * @param string $action
     * @param int|null $id
     * @param string $email
     * @param string $pass
     * @param array $logDetails
     *
     * @return array
     */
    public function login( string $action = "login", int $id = null, string $email = "", string $pass = "", array $logDetails = [] ) : array
    {
        if ( $action == "login" ) {
            if ( !empty( $email ) ) {
                $email = filter_var( $email, FILTER_SANITIZE_EMAIL );
                if ( !filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
                    $error = "$email is not a valid email address";
                    return [ "status" => false, "msg" => $error ];
                }
            } else {
                $error = "Please enter username.";
                return [ "status" => false, "msg" => $error ];
            }
            
            if ( empty( $pass ) ) {
                $error = "Please enter password.";
                return [ "status" => false, "msg" => $error ];
                
            }
        }
        
        if ( $action == "change_status" && empty( $id ) ) {
            return [ "status" => false, "msg" => "Can't changed status, please press logout and try to login again" ];
            
        }
        
        $user = [];
        $fetchData = $this->instance->fileData();

        for ( $i = 0; $i < count( $fetchData->users ); $i++ ) {
            $userDetails = [];
            if ( $action == "login" && $fetchData->users[$i]->email == $email && $fetchData->users[$i]->password == $pass ) {
                if ( $fetchData->users[$i]->is_online !== 0 ) {
                    return [ "status" => false, "msg" => "This user already logged in." ];
                }
                $userDetails = [ "id" => $fetchData->users[$i]->id, "is_online" => 1, "login_details" => $logDetails ];

            } elseif ( $action == "change_status" && $fetchData->users[$i]->id == $id ) {
                $userDetails = [ "id" => $fetchData->users[$i]->id, "is_online" => 1 ];

            }
            
            if ( !empty( $userDetails ) ) {
                $fetchData = $this->instance->fileData( "write", $userDetails );
                if ( $fetchData->is_online === 1 ) {
                    $user = $fetchData;
                    break;
                }
            }
            
        }

        if ( !empty( $user ) ) {
            if ( $action == "login" ) {

                if ( empty( $_SESSION['token'] ) ) {
                    $_SESSION['token'] = bin2hex(random_bytes(32));
                }

                return [ "status" => true, "msg" => "Welcome " . $user->name, "uid" => $user->id, "name" => $user->name, "token" => $_SESSION['token'] ];
                
            } elseif ( $action == "change_status" ) {
                return [ "status" => true, "msg" => "Welcome back " . $user->name, "uid" => $user->id, "name" => $user->name ];
                
            } else {
                return [ "status" => false, "msg" => "Can't changed status, please press logout and try to login again" ];
                
            }
        }

        if ( $action == "change_status" && empty( $user ) ) {
            return [ "status" => false, "msg" => "Can't changed status" ];
        }
        
        return [ "status" => false, "msg" => "Incorrect username or password." ];
        
    }
    
    /**
     * User logout process
     *
     * @param string $action
     * @param int|null $id
     *
     * @return array
     */
    public function logout( string $action = "logout", int $id = null ) : array
    {
        $user = [];
        
        if ( !empty( $id ) ) {
            $fetchData = $this->instance->fileData();
            for ( $i = 0; $i < count( $fetchData->users ); $i++ ) {
                if ( $fetchData->users[$i]->id == $id ) {
                    $fetchData = $this->instance->fileData( "write", [ "id" => $fetchData->users[$i]->id, "is_online" => 0 ] );
                    if ( $fetchData->is_online === 0 ) {
                        $user = $fetchData;
                        break;
                    }
                }
            }
        }
        if ( !empty( $user ) ) {
            if ( $action == "logout" ) {
                $_SESSION = [];
                session_unset();
                return [ "status" => true, "msg" => "Goodbye " . $user->name ];
            } elseif ( $action == "change_status" ) {
                return [ "status" => true, "msg" => "Your status changed to offline" ];
            }
        }
        /**
         * Return fake msg for who try to fake userID
         */
        return [ "status" => false, "msg" => "You still on online mode." ];
    }
}

