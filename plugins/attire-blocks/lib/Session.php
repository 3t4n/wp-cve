<?php

namespace Attire\Blocks;
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}
class Session
{
    static $data;
    static $deviceID;
    static $store = 'file';

    function __construct()
    {

        $agent = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
        $deviceID = md5(self::clientIP() . $agent);
        self::$deviceID = $deviceID;

        if (self::$store === 'file') {
            if (file_exists(ATTIRE_BLOCKS_DIR_PATH . "/cache/session-{$deviceID}.txt")) {
                $data = file_get_contents(ATTIRE_BLOCKS_DIR_PATH . "/cache/session-{$deviceID}.txt");
                $data = Crypt::decrypt($data, true);
                if (!is_array($data)) $data = array();
            } else {
                $data = array();
            }

            self::$data = $data;

            register_shutdown_function(array($this, 'saveSession'));
        }
    }

    static function clientIP()
    {
        if (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        else
            $ipaddress = 'UNKNOWN';

        return $ipaddress;
    }

    static function deviceID($deviceID)
    {
        self::$deviceID = $deviceID;
    }

    static function set($name, $value, $expire = 1800)
    {
        global $wpdb;
        if (self::$store === 'file') self::$data[$name] = array('value' => $value, 'expire' => time() + $expire);
        if (self::$store === 'db') $wpdb->insert("{$wpdb->prefix}atbs_sessions", array('deviceID' => self::$deviceID, 'name' => $name, 'value' => maybe_serialize($value), 'expire' => time() + $expire));
    }

    static function get($name)
    {
        if (self::$store === 'file') {
            if (!isset(self::$data[$name])) return null;
            $_value = self::$data[$name];
            if (count($_value) == 0) return null;
            extract($_value);
            if (isset($expire) && $expire < time()) {
                unset(self::$data[$name]);
                $value = null;
            }
        }
        if (self::$store === 'db') {
            global $wpdb;
            $deviceID = self::$deviceID;
            $value = $wpdb->get_var("select `value` from {$wpdb->prefix}atbs_sessions where deviceID = '{$deviceID}' and `name` = '{$name}'");
        }
        return maybe_unserialize($value);

    }

    static function clear($name = '')
    {
        global $wpdb;
        if ($name == '') {
            if (self::$store === 'file') self::$data = array();
            if (self::$store === 'db') $wpdb->delete("{$wpdb->prefix}atbs_sessions", array('deviceID' => self::$deviceID));
        } else {
            if (self::$store === 'file' && isset(self::$data[$name])) unset(self::$data[$name]);
            if (self::$store === 'db') $wpdb->delete("{$wpdb->prefix}atbs_sessions", array('deviceID' => self::$deviceID, 'name' => $name));
        }
    }

    static function show()
    {
        echo "<pre>";
        print_r(self::$data);
        echo "</pre>";
    }

    static function saveSession()
    {
        if (self::$store === 'file' && is_array(self::$data)) {
            $data = Crypt::encrypt(self::$data);
            if (!file_exists(ATTIRE_BLOCKS_DIR_PATH . '/cache')) {
                mkdir(ATTIRE_BLOCKS_DIR_PATH . '/cache', 0755);
            }
            file_put_contents(ATTIRE_BLOCKS_DIR_PATH . '/cache/session-' . self::$deviceID . '.txt', $data);
        }
    }
}

new Session();