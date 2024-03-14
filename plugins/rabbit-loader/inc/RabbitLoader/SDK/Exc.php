<?php

namespace RabbitLoader\SDK;

class Exc extends \Exception
{
    private static $log;
    private static $debug = false;

    public static function setFile($rootDir, $debug)
    {
        self::$log = new File(rtrim($rootDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . 'error.log');
        self::$debug = $debug;
    }

    public static function catch($e, $data = [], $limit = 8)
    {
        $msg = [];
        try {
            $msg['time'] = date("c");

            if (function_exists('is_wp_error') && is_wp_error($e)) {
                $msg['msg'] = $e->get_error_message();
            } else if ($e instanceof \Exception || $e instanceof \Throwable) {
                $msg['msg'] = $e->getMessage();
                $msg['file'] = $e->getFile();
                $msg['line'] = $e->getLine();
            } else {
                $msg['msg'] = $e;
            }
            if ($limit > 8) {
                $limit = 8;
            }
            $msg['backtrace'] = debug_backtrace(DEBUG_BACKTRACE_PROVIDE_OBJECT, $limit);
            $msg['server'] = $_SERVER;

            $msgStr = json_encode($msg);
            self::$log->fac($msgStr);
            if (self::$debug) {
                echo $msgStr;
                error_log($msgStr);
            }
        } catch (\Throwable $e) {
            if (self::$debug) {
                echo $e->getMessage();
            }
        }
    }

    public static function &getAndClean()
    {
        $data = self::$log->fgc();
        self::$log->unlink();
        return $data;
    }
}
