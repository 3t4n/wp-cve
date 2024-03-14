<?php

namespace rednaoformpdfbuilder\core\Managers;

use rednaoformpdfbuilder\core\Loader;
use rednaoformpdfbuilder\htmlgenerator\generators\FileManager;

class LogManager
{
    /** @var Loader */
    private static $loader;
    /** @var FileManager */
    private static $fileManager;
    private static $ShouldLog=null;
    const TYPE_ERROR=10;
    const TYPE_DEBUG=5;
    /** @var $LogOptions */
    private static $LogOptions=null;

    static function Initialize($loader)
    {
        self::$loader=$loader;
        self::$fileManager=new FileManager($loader);
    }


    /**
     * @param $type "Error"|"Debug"|"Warning"
     * @param $message
     */
    static function Log($type,$message)
    {
        if(!self::ShouldLog($type))
            return;

        $line=get_date_from_gmt(date('c'))." - [".\strtoupper($type)."] --> ".$message."\r\n";

        $path=self::GetLogFilePath();

        \file_put_contents($path,$line,FILE_APPEND);


    }

    static function SetShouldLog($shouldLog)
    {
        LogManager::$ShouldLog=false;
    }

    static function LogError($message)
    {
        self::Log(LogManager::TYPE_ERROR,$message);
    }

    static function LogDebug($message)
    {
        self::Log(LogManager::TYPE_DEBUG,$message);
    }

    private static function ShouldLog($type)
    {
        if(LogManager::$ShouldLog===null)
            LogManager::$ShouldLog=get_option(self::$loader->Prefix.'_enable_log')=="1";
        return LogManager::$ShouldLog;
    }

    static function RemoveLog()
    {
        $path=self::GetLogFilePath();
        if(\file_exists($path))
            \unlink($path);
    }


    public static function GetLogFilePath()
    {
        return self::$fileManager->GetLoggerPath().'/log.txt';
    }


}