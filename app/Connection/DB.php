<?php

namespace App\Connection;

//require_once '../../vendor/autoload.php';

use App\Helpers\Helper;

/**
 * Class DB
 * @package App\Connection
 */
class DB
{
    /** @var self $instance Instance of DataBase class */
    protected static ?DB $instance = null;

    /**
     * @var string
     */
    private string $file_path = __DIR__."/../DataBase/";
    private string $file_name = "data.txt";

    private function __construct() {}
    private function __clone() {}

    /**
     * @return static
     */
    public static function getConnection(): self
    {
        if ( empty( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * Write & Read data from json file
     *
     * @param string $action
     * @param array|null $addData
     * @param int|null $position
     *
     * @return \stdClass
     */
    public function fileData( string $action = "read", ?array $addData = [], ?int $position = null ) : \stdClass
    {
        $data = new \stdClass();
        $file = $this->file_path.$this->file_name;

        if ( file_exists( $file ) && filesize( $file ) > 0 )
        {
            $json = file_get_contents( $file );
            $data = json_decode( $json );

            if ( $action == "read" && !empty( $position ) && $data->users[$position]->id == $addData['id'] ) {
                $data = $data->users[$position];
            }


            if ( $action === "write" && !empty( $addData ) )
            {
                $getCurrentDateTime = "";
                if ( isset( $addData['is_online'] ) ) {
                    $helper = new Helper;
                    $getCurrentDateTime = $helper->getCurrentDateTime();
                }

                for ( $i = 0; $i < count( $data->users ); $i++ ) {
                    $user = $data->users[$i];
                    if ( $user->id == $addData['id'] ) {
                        if ( isset( $addData['login_details'] ) ) {
                            $user->login_details[] = $addData['login_details'];
                        }
                        if ( isset( $addData['logout_details'] ) ) {
                            $user->logout_details[] = $addData['logout_details'];
                        }
                        if ( isset( $addData['is_online'] ) ) {
                            $user->is_online = $addData['is_online'];
                            $user->updated_at = $getCurrentDateTime;
                        }
                        $newJsonString = json_encode( $data );
                        file_put_contents( $file, $newJsonString );
                        return $this->fileData( "read", [ "id" => $user->id ], $i );
                    }
                    continue;
                }
            }
        }

        return $data;

    }

}