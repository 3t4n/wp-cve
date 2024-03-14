<?php

class GRWP_WP_Cron
{
    protected static  $instance = null ;
    protected function __construct()
    {
    }
    
    public static function get_instance()
    {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function plugin_setup()
    {
        add_action( 'get_google_reviews', array( $this, 'wp_cron_reviews' ) );
    }
    
    public function wp_cron_reviews()
    {
    }

}