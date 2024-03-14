<?php

namespace  platy\etsy\logs;
use platy\etsy\EtsySyncerException;

class PlatyLogger{
    const GENERAL = -1;
    const ERROR = 0;
    const SUCCESS = 1;
    private static $sInstance = null;
    private function __construct(){

    }

    public static function get_instance(){
        if(PlatyLogger::$sInstance == null){
            PlatyLogger::$sInstance = new PlatyLogger();
        }
        return PlatyLogger::$sInstance;
    }

    public function log_general($message, $type) {
        $this->log([
            'message' => $message,
            'status' => self::GENERAL,
            'type' => $type
        ]);
    }

    public function log_success($message, $type, $shop_id = null, $post_id = -1, $etsy_id = null) {
        $this->log([
            'message' => $message,
            'status' => self::SUCCESS,
            'type' => $type,
            'shop_id' => $shop_id,
            'post_id' => $post_id,
            'etsy_id' => $etsy_id
        ]);
    }

    public function log_error($message, $type, $shop_id = null, $post_id = -1, $etsy_id = null) {
        $this->log([
            'message' => $message,
            'status' => self::ERROR,
            'type' => $type,
            'shop_id' => $shop_id,
            'post_id' => $post_id,
            'etsy_id' => $etsy_id
        ]);
    }

    private function log($row){
        global $wpdb;

        $log_tbl = \Platy_Syncer_Etsy::LOG_TABLE_NAME;

        $wpdb->insert("{$wpdb->prefix}$log_tbl", $row);
    }

    public function get_logs($max_entries, $type = -1) {
        global $wpdb;

        $log_tbl = $wpdb->prefix . \Platy_Syncer_Etsy::LOG_TABLE_NAME;
        return $wpdb->get_results( "SELECT * FROM $log_tbl WHERE (type LIKE '$type') ORDER BY `id` DESC LIMIT $max_entries", ARRAY_A);
    }

    public function get_log($where) {
        global $wpdb;

        $log_tbl = $wpdb->prefix . \Platy_Syncer_Etsy::LOG_TABLE_NAME;
        return $wpdb->get_results( "SELECT * FROM $log_tbl WHERE $where", ARRAY_A);
    }

    public static function clean_logs() {
        global $wpdb;

        $log_tbl = $wpdb->prefix . \Platy_Syncer_Etsy::LOG_TABLE_NAME;
        $wpdb->query( "DELETE FROM $log_tbl WHERE `date` < NOW() - INTERVAL 1 WEEK" );

    }
    
}