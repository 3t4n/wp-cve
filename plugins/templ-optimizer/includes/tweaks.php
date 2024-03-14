<?php
defined('ABSPATH') or die();

class templOptimizerTweaks extends templOptimizer {

    function __construct() {

        // Include sub-modules
        if( file_exists( ABSPATH . 'wp-config.php' ) ) {
            $this->config = new templOptimizerConfigTransformer( ABSPATH . 'wp-config.php' );
        }

        add_filter( 'heartbeat_settings', array( $this, 'custom_heartbeat_interval' ) );

        if( $this->get_option('wp_rocket_preload_interval') && $this->get_option('wp_rocket_preload_interval') !== 'default' ) {
            add_filter( 'pre_get_rocket_option_sitemap_preload_url_crawl', array( $this, 'wp_rocket_preload_interval' ) );
        }
        
    }

    function custom_heartbeat_interval( $settings ) {

        if( $this->get_option('heartbeat_interval') === 'default' ) {
            $settings['interval'] = 15;
        }

        if( $this->get_option('heartbeat_interval') === 'slow' ) {
            $settings['interval'] = 60;
        }

        return $settings;

    }

    // 2.0
    function set_wp_memory_limit( $value ) {
        if( $value == 'default' ) {
            return $this->config->remove('constant', 'WP_MEMORY_LIMIT');
        }
        return $this->config->update('constant', 'WP_MEMORY_LIMIT', $value);
    }

    function set_heartbeat_interval( $value ) {
        return $this->update_option( 'heartbeat_interval', $value );
    }
    
    function set_wp_rocket_preload_interval( $value ) {
        return $this->update_option( 'wp_rocket_preload_interval', $value );
    }

    function set_wp_post_revisions( $value ) {
        if( $value == 'default' ) {
            return $this->config->remove('constant', 'WP_POST_REVISIONS');
        }
        return $this->config->update('constant', 'WP_POST_REVISIONS', $value);
    }

    function set_disable_wp_cron( $value ) {
        if( $value == 'disabled' ) {
            return $this->config->update('constant', 'DISABLE_WP_CRON', 'true', array('raw' => true));
        }
        if( $value == 'enabled' ) {
            return $this->config->remove('constant', 'DISABLE_WP_CRON');
        }
    }

}
