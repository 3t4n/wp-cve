<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Cleaner' ) ):

    class Better_Messages_Cleaner
    {   public static function instance()
        {

            static $instance = null;

            if ( null === $instance ) {
                $instance = new Better_Messages_Cleaner();
            }

            return $instance;
        }

        public function __construct()
        {
            add_action( 'admin_init', array( $this, 'register_event' ) );
        }

        public function register_event(){
            if ( ! wp_next_scheduled( 'better_messages_cleaner_job' ) ) {
                wp_schedule_event( time(), 'better_messages_cleaner_job', 'better_messages_cleaner_job' );
            }
        }
    }

endif;

function Better_Messages_Cleaner()
{
    return Better_Messages_Cleaner::instance();
}
