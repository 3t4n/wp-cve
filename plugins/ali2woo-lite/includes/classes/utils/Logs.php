<?php

/**
 * Description of Logs
 *
 * @author Ali2Woo Team
 */

namespace AliNext_Lite;;

class Logs {

    private static $_instance = null;

    private $a2wl_logs_file = '/ali2woo/a2wl_debug.log';

    protected function __construct() {
        if(get_setting('write_info_log')){
            $upload_dir = wp_upload_dir();
            $log_file_parts = pathinfo($this->a2wl_logs_file);

            $a2wl_logs_dir = $upload_dir['basedir'].$log_file_parts['dirname'];
            $a2wl_logs_file = $a2wl_logs_dir.'/'.$log_file_parts['basename'];
        
            if (!file_exists($a2wl_logs_dir)) {
                mkdir($a2wl_logs_dir, 0755, true);
            }

            if (!file_exists($a2wl_logs_file)) {
                $fp = fopen($a2wl_logs_file, 'w');
                fclose($fp);
                chmod($a2wl_logs_file, 0644);
            }
        }
    }

    protected function __clone() {
        
    }

    static public function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }


    public function write($message){
        if(get_setting('write_info_log')){
            $ft = false;
            try {
                $fp = fopen($this->log_path(), 'a');
                fwrite($fp, $message."\r\n");
            } catch (Throwable $e) {
                error_log($e->getTraceAsString());
            } catch (Exception $e) {
                error_log($e->getTraceAsString());
            } finally {
                if($fp){
                    fclose($fp);
                }
            }
        }
    }

    public function delete(){
        unlink($this->log_path());
    }

    public function log_path(){
        $upload_dir = wp_upload_dir();
        return $upload_dir['basedir'].$this->a2wl_logs_file;
    }

    public function log_url(){
        $upload_dir = wp_upload_dir();
        return $upload_dir['baseurl'].$this->a2wl_logs_file;
    }
}
