<?php

require_once __DIR__.'/../../vendor/autoload.php';

use App\Services\DataServices;

//require_once('DataServices.php');

/**
 * Receive post with php and read stream raw data from the request body
 * Update user field "is_online" by request params ( $_POST["state"] decided witch service to load )
 *
 * @param null|array $post raw data from the request body
 *
 * @return array but we use it like void
 */
$post = null;
parse_str( file_get_contents( 'php://input' ), $post );

$response = "";

$user = new DataServices;
if ( $post['state'] == "offline" ) {
    $response = $user->logout( $post["action"], $post["id"] );
}
if ( $post['state'] == "online" ) {
    $response = $user->login( $post["action"], $post["id"], "", "", [] );
}

echo @json_encode( $response );
exit;