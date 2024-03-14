<?php
class Log
{
    public static function warning($message = '')
    {
        $log = self::getInstance();
        $log->warning(json_encode($message));
    }

    public static function error($message)
    {
        $log = self::getInstance();        
        $log->error(json_encode($message));
    }

    public static function critical($message)
    {
        $log = self::getInstance();
        $log->critical(json_encode($message));
    }

    public static function getInstance()
    {
        try {
            $log = new Katzgrau\KLogger\Logger(__DIR__ . '/../../logs', Psr\Log\LogLevel::WARNING, array(
                'extension' => 'log',
            ));
        } catch (Exception $e) {
            var_dump("exception: ", $e);
        }
        return $log;
    }
}
