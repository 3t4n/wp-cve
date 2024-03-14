<?php
namespace OCM;
class Error_Handler
{
    /**
     *
     * @var bool
     */
    private static $inizialized = false;

    /**
     * This function only initializes the error handler the first time it is called
     */
    public static function init_error_handler()
    {
        if (!self::$inizialized) {
            @set_error_handler(array(__CLASS__, 'error'));
            @register_shutdown_function(array(__CLASS__, 'shutdown'));
            self::$inizialized = true;
        }
    }

    /**
     * Error handler
     *
     * @param  integer $errno   Error level
     * @param  string  $errstr  Error message
     * @param  string  $errfile Error file
     * @param  integer $errline Error line
     * @return void
     */
    public static function error($errno, $errstr, $errfile, $errline)
    {
      $file = OCM_DEBUG_LOG_FILE;
      $file_array = [];
      if(file_exists($file)){
        $file_array = file($file);
      }

      $log_message = self::getMessage($errno, $errstr, $errfile, $errline);
      if(!self::substr_in_array($errstr, $file_array)){
        switch ($errno) {
            case E_ERROR :
                One_Click_Migration::write_to_log(sprintf(' SYSLOG: "%s"', $log_message));
                One_Click_Migration::write_to_log($errstr);
                die();
                break;
            case E_NOTICE :
            case E_WARNING :
            default :
                if (WP_DEBUG === true) One_Click_Migration::write_to_log(sprintf(' SYSLOG: "%s"', $log_message));
                break;
        }

      }

    }

    private static function substr_in_array($needle, array $haystack)
    {
      $filtered = array_filter($haystack, function ($item) use ($needle) {
          return false !== strpos($item, $needle);
      });

      return !empty($filtered);
    }

    private static function getMessage($errno, $errstr, $errfile, $errline)
    {
        $result = '[PHP ERR]';
        switch ($errno) {
            case E_ERROR :
                $result .= '[FATAL]';
                break;
            case E_WARNING :
                $result .= '[WARN]';
                break;
            case E_NOTICE :
                $result .= '[NOTICE]';
                break;
            default :
                $result .= '[ISSUE]';
                break;
        }
        $result .= ' MSG:';
        $result .= $errstr;
        $result .= ' [CODE:'.$errno.'|FILE:'.$errfile.'|LINE:'.$errline.']';
        return $result;
    }

    /**
     * Shutdown handler
     *
     * @return void
     */
    public static function shutdown()
    {
        if (($error = error_get_last())) {
          self::error($error['type'], $error['message'], $error['file'], $error['line']);
        }
    }
}
