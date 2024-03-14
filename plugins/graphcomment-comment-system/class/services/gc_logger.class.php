<?php

/**
 * Class GcLogger
 * Usage :
 *   - GcLogger::getLogger()->debug('it works !');
 *   - GcLogger::getLogger()->log('my super info');
 *   - GcLogger::getLogger()->error('my super error');
 */

if (!class_exists('GcLogger')) {
  class GcLogger {

    private static $_instance = null;

    private $log_file_path;
    private $prefix;

    private static function bootstrapLogDir() {
      $uploads = wp_upload_dir();
      $my_upload_dir = $uploads['basedir'] . '/graphcomment';

      if (!is_dir($my_upload_dir)) {
        // dir doesn't exist, create it
        mkdir($my_upload_dir);
      }

      return $my_upload_dir;
    }

    private function __construct() {

      $logDir = self::bootstrapLogDir();

      $this->log_file_path = $logDir . '/wp_graphcomment.log';
      $this->prefix = '['.date("y-m-d H:i:s").'] - ';
    }

    public static function getLogger() {

      if(is_null(self::$_instance)) {
        self::$_instance = new GcLogger();
      }

      return self::$_instance;
    }

    /**
     * Get end of logs
     */
    public function debugLogs() {
      $data = file($this->log_file_path);
      return join('', array_reverse(array_slice($data, -500)));
    }

    public function debug($string) {
      $this->write('[debug]'.$this->prefix.$string, false);
    }

    public function log($string) {
      $this->write('[info]'.$this->prefix.$string, false);
    }

    public function error($string) {
      $this->write('[error]'.$this->prefix.$string, true);
    }

    private function write($string, $force) {
      if (GcParamsService::getInstance()->graphcommentDebugIsActivated() || $force) {
        file_put_contents($this->log_file_path, $string."\n", FILE_APPEND | LOCK_EX);
      }
    }
  }
}
