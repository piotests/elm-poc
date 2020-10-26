<?php
namespace App\Helpers;

/**
 * Class Helper
 * @package App\Helpers
 */
class Helper
{
    /**
     * Get real ip from server request
     *
     * @return mixed
     */
    public function getIpAddrFromReq() {
        if( !empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            //ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif( !empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            //ip pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * Get current date and time from server and convert them to format ISO8601
     *
     * @return string
     */
    public function getCurrentDateTime() {
        $datetime = new \DateTime();
        return $datetime->format( DATE_ISO8601 ); // Updated ISO8601
    }

    /**
     * Get browser name from server request
     *
     * @param null $user_agent
     *
     * @return string
     */
    public function getBrowserName( $user_agent = null ) {
        $user_agent = $user_agent ?? $_SERVER['HTTP_USER_AGENT'];
        $t = strtolower( $user_agent );
        $t = " " . $t;
        if     ( strpos( $t, 'opera' ) || strpos( $t, 'opr/' ) ) return 'Opera';
        elseif ( strpos( $t, 'edge' ) ) return 'Edge';
        elseif ( strpos( $t, 'chrome' ) ) return 'Chrome';
        elseif ( strpos( $t, 'safari' ) ) return 'Safari';
        elseif ( strpos( $t, 'firefox' ) ) return 'Firefox';
        elseif ( strpos( $t, 'msie' ) || strpos( $t, 'trident/7' ) ) return 'Internet Explorer';
        return 'Unknown';
    }

}