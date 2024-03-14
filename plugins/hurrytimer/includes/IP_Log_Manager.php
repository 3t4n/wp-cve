<?php
namespace Hurrytimer;

class IP_Log_Manager {

    const CLEANUP_ACTION = 'hurrytimer_evergreen_daily_cleanup';
    private $table;

    public function __construct() {
        global $wpdb;
        $this->table      = "{$wpdb->prefix}hurrytimer_evergreen";

        if ( $this->has_entries() ) {
            add_action( 'wp', array( $this, 'schedule_daily_cleanup' ) );
        }else{
            $this->unschedule_cleanup();
        }
    }

    public function schedule_daily_cleanup() {
        if ( ! wp_next_scheduled( self::CLEANUP_ACTION ) ) {
            wp_schedule_event( time(), 'daily', self::CLEANUP_ACTION );
        }
    }

    public function cleanup_ip_logs() {
        global $wpdb;

        $current_time = current_time( 'mysql' );

        $query = $wpdb->prepare(
            "DELETE FROM {$this->table} WHERE destroy_at < %s",
            $current_time
        );

        // Run the query
        $wpdb->query( $query );
    }

    public function has_entries() {
        global $wpdb;

        $result = $wpdb->get_var( "SELECT COUNT(*) FROM {$this->table}");

        return ( $result > 0 );
    }

    public function unschedule_cleanup() {
        wp_clear_scheduled_hook( self::CLEANUP_ACTION );
    }
    
}

