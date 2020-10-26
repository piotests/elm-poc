<?php

require_once __DIR__.'/../../vendor/autoload.php';

use App\Helpers\Helper;
use App\Services\DataServices;

//require_once('../Helpers/Helper.php');
//require_once('./DataServices.php');

header("Content-type: application/json");

session_start();

/**
 * Validate request and get data from service by request params ( $_POST["sec"] decided witch service to load )
 *
 * @param string $_SERVER['HTTP_X_REQUESTED_WITH'] server request
 * @param array $_POST post request
 * @param array $_SESSION
 *
 * @return array
 */
if ( !empty( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) === 'xmlhttprequest' ) {
    $response = [
        "status" => false,
        "msg" => "The request not valid ajax"
    ];

    echo @json_encode( $response );
    exit;

} elseif ( strtolower( $_SERVER['REQUEST_METHOD'] ) !== 'post' ) {
    $response = [
        "status" => false,
        "msg" => "The request not valid method",
    ];

    echo @json_encode( $response );
    exit;

}  elseif ( isset( $_POST['sec'] ) ) {

    /**
     * Check if param 'debug' exist in request, case: 'debug' exist - ignore token validation
     */
    $tokenExist = false;
    if ( !isset( $_POST['debug'] ) || isset( $_POST['token'] ) && isset( $_SESSION['token'] ) && $_POST['token'] == $_SESSION['token'] ) {
        $tokenExist =true;
    }

    /**
     * Default response, it can be override by each switch case.
     */
    $response = [
        "status" => false,
        "msg" => "Invalid token"
    ];

    $user = new DataServices;

    switch ( $_POST['sec'] ) {
        case "login" :
            $helper = new Helper;
            $getBrowserName = $helper->getBrowserName();
            $getCurrentDateTime = $helper->getCurrentDateTime();
            $getReqIp = $helper->getIpAddrFromReq();
            $response = $user->login( "login", null, $_POST["user"], $_POST["pass"], [ "user_agent" => $getBrowserName, "time" => $getCurrentDateTime, "ip" => $getReqIp ] );
            break;
        case "logout" :
            if ( isset( $_POST['debug'] ) || $tokenExist ) {
                $id = $_POST['id'] ?? null;
                $response = $user->logout( "logout", $id );

                /// For Testing case - session_destroy here
                if ( $response["status"] && strpos( "Goodbye", $response["msg"] ) !== false ) {
                    session_destroy();
                }
            }
            break;
        case "user_details" :
            if ( isset( $_POST['debug'] ) || $tokenExist ) {
                $response = $user->fetchOne($_POST['id']);
            }
            break;
        case "user_list" :
            if ( isset( $_POST['debug'] ) || $tokenExist ) {
                $response = $user->fetchAll();
            }
            break;
        default:
            $response = [
                "status" => false,
                "msg" => "Invalid section"
            ];
            break;
    }

    echo @json_encode( $response );
    exit;

} else {

    $response = [
        "status" => false,
        "msg" => "The request missing parameter"
    ];

    echo @json_encode( $response );
    exit;
}
