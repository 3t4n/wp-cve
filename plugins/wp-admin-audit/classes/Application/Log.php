<?php
/**
 * @copyright (C) 2021 - 2024 Holger Brandt IT Solutions
 * @license GPL2
*/

if(!defined('ABSPATH')){
    exit;
}

class WADA_Log
{
    const DEBUG = 	'DEBU';
    const INFO = 	'INFO';
    const WARNING = 'WARN';
    const ERROR = 	'ERRO';

    public static function log($msg, $level='INFO', $typeNr=0)
    {
        $isHeartbeatRequest = false;
        $isRestUrlPath = false;
        if(array_key_exists('action', $_POST)){
            $isHeartbeatRequest = ($_POST['action'] === 'heartbeat');
        }
        if(array_key_exists('REQUEST_URI', $_SERVER)) {
            $restUrlPath = trim(parse_url(home_url('/wp-json/'), PHP_URL_PATH), '/');
            $requestUrl = trim($_SERVER['REQUEST_URI'], '/');
            $isRestUrlPath = (strpos($requestUrl, $restUrlPath) === 0);
        }
        if($isHeartbeatRequest && ($level == self::DEBUG)){
            return true; // do not log anything DEBUG during heartbeat requests
        }

        $isInstallEntry = ($typeNr == WADA_Constants::LOGENTRY_INSTALLER);

        if($isInstallEntry){
            $levelSufficient = true; // during install all levels are fine
        }else{
            $levelSufficient = self::loggingLevelSufficient( $level ); // want to avoid calling it during install (when not settings table might be there)
        }

        if( $levelSufficient ) {

            $level = strtoupper($level);

            if(is_null($msg)){
                $msg = '<NULL>';
            }elseif(is_array($msg)){
                $msg = print_r($msg, true);
            }elseif(is_object($msg)){
                $msg = serialize($msg);
            }

            $remoteAddress = array_key_exists('REMOTE_ADDR', $_SERVER) ? $_SERVER['REMOTE_ADDR'] : '';
            $remotePort = array_key_exists('REMOTE_PORT', $_SERVER) ? $_SERVER['REMOTE_PORT'] : '';
            $reqTime = array_key_exists('REQUEST_TIME', $_SERVER) ? $_SERVER['REQUEST_TIME'] : '';
            $requestId = sprintf("%08x", abs(crc32($remoteAddress . $reqTime . $remotePort)));

            $loggingPossible = self::loggingPossible();

            if(!$loggingPossible) {
                try {
                    self::initFile();
                } catch (RuntimeException $e) {
                    return false;
                }
                $loggingPossible = self::loggingPossible();
            }

            if($isInstallEntry){
                $loggingForced = false; // don't risk anything during installation!
            }else{
                $loggingForced = self::isLoggingForced(); // want to avoid calling it during install (when not settings table might be there)
            }

            if( $loggingPossible || $loggingForced ){
                $logFileName = self::getLogFile();
                $logFile = fopen($logFileName, "a");
                if($logFile){
                    $msg = date("Y-m-d H:i:s") . ' ' . $requestId . ' ' . $level . ' ' . $msg;
                    if(fwrite($logFile, $msg . "\n") !== false){ //write to the file
                        return true;
                    }
                }
            }

        }
        return false;
    }

    public static function warning($msg, $typeNr=0)
    {
        return self::log($msg, self::WARNING, $typeNr);
    }
    public static function error($msg, $typeNr=0)
    {
        return self::log($msg, self::ERROR, $typeNr);
    }
    public static function debug($msg, $typeNr=0)
    {
        return self::log($msg, self::DEBUG, $typeNr);
    }
    public static function info($msg, $typeNr=0)
    {
        return self::log($msg, self::INFO, $typeNr);
    }
    public static function debugOrError($isDebug, $msg, $typeNr=0)
    {
        if($isDebug){
            return self::log($msg, self::DEBUG, $typeNr);
        }else{
            return self::log($msg, self::ERROR, $typeNr);
        }
    }
    public static function errorIfFalse($isOkay, $msg, $typeNr=0)
    {
        if(!$isOkay){
            return self::log($msg, self::ERROR, $typeNr);
        }
        return false;
    }

    public static function getLoggingLevel($entryType){
        switch($entryType){
            case self::ERROR:
                return WADA_Constants::LOG_LEVEL_ERROR;
            case self::WARNING:
                return WADA_Constants::LOG_LEVEL_WARNING;
            case self::INFO:
                return WADA_Constants::LOG_LEVEL_INFO;
            case self::DEBUG:
                return WADA_Constants::LOG_LEVEL_DEBUG;
        }
        return 0;
    }

    public static function getLoggingLevelStr($entryTypeNr){
        switch($entryTypeNr){
            case WADA_Constants::LOG_LEVEL_ERROR:
                return __('Error', 'wp-admin-audit' );
            case WADA_Constants::LOG_LEVEL_WARNING:
                return __('Warning', 'wp-admin-audit' );
            case WADA_Constants::LOG_LEVEL_INFO:
                return __('Info', 'wp-admin-audit' );
            case WADA_Constants::LOG_LEVEL_DEBUG:
                return __('Debug', 'wp-admin-audit' );
        }
        return 0;
    }

    public static function getLoggingTypeStr($typeNr){
        switch($typeNr){
            case WADA_Constants::LOGENTRY_INSTALLER:
                return __('Installer', 'wp-admin-audit' );
            case WADA_Constants::LOGENTRY_PLUGIN:
                return __('Plugin', 'wp-admin-audit' );
        }
        return "";
    }

    public static function loggingLevelSufficient($entryType){
        $loggingLevel = self::getCurrentLoggingLevel();
        if($loggingLevel > 0){ // when Logging Level is zero then nothing will be logged
            $entryLevel = self::getLoggingLevel($entryType); // get Level that this entry needs
            if($entryLevel <= $loggingLevel){
                return true; // entry can be logged
            }
        }
        return false; // entry may not be logged
    }

    public static function isLoggingActive(){
        return (self::getCurrentLoggingLevel() > 0);
    }

    public static function getCurrentLoggingLevel(){
        return WADA_Settings::getLoggingLevel(); // get current Logging Level			
    }

    public static function getCurrentLoggingLevelString(){
        $loggingLevel = self::getCurrentLoggingLevel();
        return self::getLoggingLevelStr($loggingLevel);
    }

    public static function getLogFileFolder(){
        $uploadDir = wp_upload_dir();
        $uploadDir = $uploadDir['basedir'];
        return $uploadDir.'/wp-admin-audit/';
    }

    public static function getLogFile($logFileName = 'wada.log.php'){
        $logPath = self::getLogFileFolder();
        return $logPath . $logFileName;
    }

    public static function loggingPossible($logFileName = 'wada.log.php', $addPath=true){
        if($addPath){
            $logPath = self::getLogFileFolder();
            $filePath = $logPath . $logFileName;
            $takeFileNameAsPath = false;
        }else{
            $filePath = $logFileName;
            $logPath = null;
            $takeFileNameAsPath = true;
        }
        if(!file_exists($filePath)){
            error_log('loggingPossible - File does not exist: '.$filePath);
        }

        return self::isFileWritable($logFileName, $logPath, $takeFileNameAsPath);
    }

    public static function tailFromFile($numOfLines=100, $logFileName = 'wada.log.php') {
        $logFile = self::getLogFile($logFileName);
        $handle = fopen($logFile, 'rb');
        if (!$handle) {
            return null;
        }

        // Move to the end of the file
        fseek($handle, 0, SEEK_END);

        $lines = [];
        $line = '';
        $bytes = -1;

        // Read backwards character by character
        while (count($lines) < $numOfLines && fseek($handle, $bytes--, SEEK_END) !== -1) {
            $char = fgetc($handle);
            // Check for the start of a new line
            if ($char === "\n") {
                // Skip empty lines if the line variable is empty
                if (strlen($line) > 0) {
                    array_unshift($lines, $line); // Prepend the line to the lines array
                    $line = ''; // Reset the line variable
                }
            } else {
                $line = $char . $line; // Prepend the character to the current line
            }
        }

        // Add the last line if it's not empty
        if (strlen($line) > 0) {
            array_unshift($lines, $line);
        }

        fclose($handle);
        return $lines;
    }

    public static function isLoggingForced(){
        return WADA_Settings::isLoggingForced();
    }

    public static function isLogFile2Big($fileLimitInByte, $logFileName = 'wada.log.php'){
        if(self::loggingPossible($logFileName)){ // log writable?
            $logFile = self::getLogFile($logFileName);
            if(!is_file($logFile)){ // log file existing?
                return false; // not existing, cannot be too big...
            }
            $lSize = filesize($logFile);
            return ($lSize > $fileLimitInByte);
        }
        return false;
    }

    // same as in FileUtils (duplicate), both for performance and dependency reasons
    protected static function isFileWritable($fileName, $folderPath, $takeFileNameAsPath=false){
        if($takeFileNameAsPath){
            $folderPath = $fileName; // cannot check it
            $filePath = $fileName;
        }else{
            $folderPath = rtrim($folderPath, '/') . '/'; // make sure there is one trailing slash
            $filePath = $folderPath . $fileName;
        }
        if(!file_exists($folderPath)){
            return false;  // Log folder path wrong configured
        }
        if(!is_writable($folderPath)){
            return false;  // Directory not writable
        }
        if(file_exists($filePath) && !is_writable($filePath)){
            return false;  // Log file exists but is not writable
        }
        return true; // Directory writable, file may exist or not
    }

    protected static function generateFileHeader()
    {
        $head = array();
        // Build the log file header.
        // If the no php flag is not set add the php die statement.
        if ( !file_exists(self::getLogFile()) )
        {
            // Blank line to prevent information disclose: https://bugs.php.net/bug.php?id=60677
            $head[] = '#';
            $head[] = '<?php die(\'Forbidden.\'); ?>';
        }

        $head[] = '#Date: ' . date('Y-m-d H:i:s') . ' UTC';
        $head[] = '';

        return implode("\n", $head);
    }

    /**
     * @throws RuntimeException
     */
    public static function initFile(){
        $logsDirectory = rtrim(self::getLogFileFolder(),'/');
        if (!is_dir($logsDirectory)){ // Make sure log directory exists
            mkdir($logsDirectory, 0755, true);
        }

        $logFile = self::getLogFile();
        if(file_exists($logFile)){ // We only need to make sure the file exists
            return;
        }

        // Only when file was not existing before, initialize it
        $head = self::generateFileHeader();  // Build the log file header

        if (!file_exists($logFile)){
            $logFileHandler = fopen($logFile, "w");
            if($logFileHandler){
                if (fwrite($logFileHandler, $head . "\n") === false) {
                    throw new RuntimeException('Cannot write to log file ' . $logFile);
                }
                fclose($logFileHandler);
            }
        }

    }
}
