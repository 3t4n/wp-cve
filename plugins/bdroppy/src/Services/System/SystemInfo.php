<?php

namespace BDroppy\Services\System;

use BDroppy\Init\Core;

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Allows log files to be written to for debugging purposes
 *
 * @class \BrandsSync\Logger
 * @author WooThemes
 */
class SystemInfo {

    const PHP_VERSION = '7.2';
    const WP_VERSION = '5.5';
    const WC_VERSION = "4.3.0";
    const WPML_VERSION = "4.3.11";

    private $config;
    public function __construct(Core $core)
    {
        $this->config = $core->getConfig();
    }

    public static function getPhpVersion($check = false)
    {
        if($check){
            return  version_compare( PHP_VERSION, self::PHP_VERSION, '>=' ) ;
        }else{
            return PHP_VERSION;
        }
    }

    public static function getWpVersion($check = false)
    {
        if($check){
            return version_compare($GLOBALS['wp_version'],self::WP_VERSION,'>=');
        }else{
            return $GLOBALS['wp_version'];
        }
    }

    public static function getWcVersion($check = false)
    {
        global $woocommerce;

        if ( ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) && is_null($woocommerce) ) {
            return false;
        }

        if($check){
            return version_compare( $woocommerce->version, self::WC_VERSION, ">=" );
        }else{
            return $woocommerce->version;
        }
    }

    public static function getOS($check = false)
    {
        if($check){
            return 1;
        }else{
            return PHP_OS;
        }
    }

    public function getMemoryLimit($check = false)
    {
        if($check){
            return ini_get('memory_limit');
        }else{
            return ini_get('memory_limit');
        }
    }

    public function getCPULoadAvarage($check = false)
    {
        if(function_exists('sys_getloadavg')){
            if($check)
            {
                return (sys_getloadavg()[0] <= $this->config->setting->get('cpu-load-average-limitation',0)) || ($this->config->setting->get('cpu-load-average-limitation',0) == 0);
            }else{
                return sys_getloadavg()[0];
            }
        }else{
            return null;
        }

    }


    public function getMaxExecutionTime($check = false)
    {
        if($check){
            return ini_get('max_execution_time');
        }else{
            return ini_get('max_execution_time');
        }
    }

    public function getUploadMaxFileSize($check = false)
    {
        if($check){
            return ini_get('upload_max_filesize');
        }else{
            return ini_get('upload_max_filesize');
        }
    }


    public function getMemorySize()
    {
        $fh = @fopen('/proc/meminfo','r');
        if(is_null($fh)){
            return 'undefined';
        }else{
            $mem = 0;
            while ($line = @fgets($fh)) {
                $pieces = array();
                if (preg_match('/^MemTotal:\s+(\d+)\skB$/', $line, $pieces)) {
                    $mem = $pieces[1];
                    break;
                }
            }
            @fclose($fh);

            return "$mem kB";
        }
    }

    public function getCpu()
    {
        $load = getrusage();
        return $load;

        $free = shell_exec('free');
        $free = (string)trim($free);
        $free_arr = explode("\n", $free);
        $mem = explode(" ", $free_arr[1]);
        $mem = array_filter($mem);
        $mem = array_merge($mem);
        $memory_usage = $mem[2]/$mem[1]*100;



        return $memory_usage;
    }
}
