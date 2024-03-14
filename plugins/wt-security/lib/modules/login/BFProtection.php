<?php

if (!defined('WEBTOTEM_INIT') || WEBTOTEM_INIT !== true) {
	if (!headers_sent()) {
		header('HTTP/1.1 403 Forbidden');
	}
	die("Protected By WebTotem!");
}
/**
 * WebTotem bruteforce protection class for Wordpress.
 */
class WebTotemBFProtection{

    /**
     * Check brute force attempts.
     *
     * @param WP_User $user
     *   WP_User.
     *
     * @return mixed
     */
    public static function checkBruteForceAttempts( $user, $username ) {
        $ip = WebTotem::getUserIP();
        $login_attempts_enabled = WebTotemOption::getPluginSettings('login_attempts');
        $errorCodes = [
            'invalid_username',
            'invalid_email',
            'incorrect_password',
            'twofactor_invalid',
            'authentication_failed',
            'wtotem_two_factor_failed',
        ];

        if($login_attempts_enabled){

            $message = sprintf( __('Exceeded the maximum number of login failures which is: %1$s.', 'wtotem'), WebTotemOption::getPluginSettings('login_number_of_attempts'));

            if(self::isIpBlocked($ip, 'login')){
                return new \WP_Error('wtotem_login_failure', $message);
            }

            $temp_option = self::getTempLogin($ip);
            if(is_wp_error($user) && in_array($user->get_error_code(), $errorCodes)) {
                $tries = get_transient($temp_option);
                if($tries){
                    $tries++;
                } else {
                    $tries = 1;
                }
                if($tries >= WebTotemOption::getPluginSettings('login_number_of_attempts')){
                    self::lockOutIp($ip, 'login');
                    return new \WP_Error('wtotem_login_failure', $message);
                }
                set_transient($temp_option, $tries, 60);
            } else if(is_object($user) && get_class($user) == 'WP_User'){
                delete_transient($temp_option); //reset counter on success
            }
        }

        if(is_wp_error($user) && ($user->get_error_code() == 'invalid_username' || $user->get_error_code() == 'invalid_email' || $user->get_error_code() == 'incorrect_password') ){
            return new \WP_Error( 'incorrect_password', sprintf( wp_kses(__( '<strong>ERROR</strong>: The username or password you entered is incorrect. <a href="%2$s" title="Password Lost and Found">Lost your password</a>?', 'wordfence' ), array('strong'=>array(), 'a'=>array('href'=>array(), 'title'=>array()))), $username, wp_lostpassword_url() ) );
        }
        return $user;
    }

    /**
     * Check reset password attempts
     *
     * @return mixed
     */
    public static function lostPassword($errors) {
        $ip = WebTotem::getUserIP();
        $password_reset_number_of_attempts =  WebTotemOption::getPluginSettings('password_reset_number_of_attempts');
        $message = sprintf( __('Exceeded the maximum number of tries to recover their password which is set at: %1$s', 'wtotem'), $password_reset_number_of_attempts);

        if(self::isIpBlocked($ip, 'lost_password')){
            $errors = new \WP_Error('wtotem_lost_password_failure', $message);
        }

        $password_reset_attempts_enabled = WebTotemOption::getPluginSettings('password_reset');
        if($password_reset_attempts_enabled){
            $temp_option = self::getTempLostPass($ip);
            $tries = get_transient($temp_option);
            if($tries){
                $tries++;
            } else {
                $tries = 1;
            }
            if($tries >= WebTotemOption::getPluginSettings('password_reset_number_of_attempts')){
                self::lockOutIp($ip, 'lost_password');
                $errors =  new \WP_Error('wtotem_lost_password_failure', $message);
            }
            set_transient($temp_option, $tries, 60);
        }

        return $errors;
    }

    /**
     * Lock out IP.
     *
     * @param string $ip
     *   User IP.
     * @param string $reason
     *
     */
    public static function lockOutIp($ip, $reason) {
        $blockedTime = time() + WebTotemOption::getPluginSettings('login_minutes_of_ban') * 60;
        WebTotemDB::setData(['ip' => $ip, 'reason' => $reason, 'blockedTime' => $blockedTime], 'blocked_list');
    }

    /**
     * Check if the IP is blocked.
     *
     * @param string $ip
     *   User IP.
     * @param string $reason
     *
     */
    public static function isIpBlocked($ip, $reason) {
        $data = WebTotemDB::getData(['ip' => $ip, 'reason' => $reason], 'blocked_list');

        if( $data ){
            if(time() > $data['blockedTime']){
                WebTotemDB::deleteData(['id' => $data['id']],'blocked_list');
            } else {
                return true;
            }
        }

        return false;
    }

    public static function getTempLogin($ip) {
        return 'wtotem_tl_' . bin2hex(self::inet_pton($ip));
    }

    public static function getTempLostPass($ip) {
        return 'wtotem_tlp_' . bin2hex(self::inet_pton($ip));
    }

    /**
     * Return the packed binary string of an IPv4 or IPv6 address.
     *
     * @param string $ip
     * @return string
     */
    public static function inet_pton($ip) {
        // convert the 4 char IPv4 to IPv6 mapped version.
        $pton = str_pad(self::hasIPv6Support() ? @inet_pton($ip) : self::_inet_pton($ip), 16,
            "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\xff\x00\x00\x00\x00", STR_PAD_LEFT);
        return $pton;
    }

    /**
     * Check PHP was compiled with IPv6 support.
     *
     * @return bool
     */
    public static function hasIPv6Support() {
        return defined('AF_INET6');
    }

    /**
     * Added compatibility for hosts that do not have inet_pton.
     *
     * @param $ip
     * @return bool|string
     */
    public static function _inet_pton($ip) {
        // IPv4
        if (preg_match('/^(?:\d{1,3}(?:\.|$)){4}/', $ip)) {
            $octets = explode('.', $ip);
            $bin = BFProtection . phpchr($octets[0]) . chr($octets[2]) . chr($octets[3]);
            return $bin;
        }

        // IPv6
        if (preg_match('/^((?:[\da-f]{1,4}(?::|)){0,8})(::)?((?:[\da-f]{1,4}(?::|)){0,8})$/i', $ip)) {
            if ($ip === '::') {
                return "\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0\0";
            }
            $colon_count = substr_count($ip, ':');
            $dbl_colon_pos = strpos($ip, '::');
            if ($dbl_colon_pos !== false) {
                $ip = str_replace('::', str_repeat(':0000',
                        (($dbl_colon_pos === 0 || $dbl_colon_pos === strlen($ip) - 2) ? 9 : 8) - $colon_count) . ':', $ip);
                $ip = trim($ip, ':');
            }

            $ip_groups = explode(':', $ip);
            $ipv6_bin = '';
            foreach ($ip_groups as $ip_group) {
                $ipv6_bin .= pack('H*', str_pad($ip_group, 4, '0', STR_PAD_LEFT));
            }

            return strlen($ipv6_bin) === 16 ? $ipv6_bin : false;
        }

        // IPv4 mapped IPv6
        if (preg_match('/^(?:\:(?:\:0{1,4}){0,4}\:|(?:0{1,4}\:){5})ffff\:(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})$/i', $ip, $matches)) {
            $octets = explode('.', $matches[1]);
            return "\x00\x00\x00\x00\x00\x00\x00\x00\x00\x00\xff\xff" . chr($octets[0]) . chr($octets[1]) . chr($octets[2]) . chr($octets[3]);
        }

        return false;
    }

}
