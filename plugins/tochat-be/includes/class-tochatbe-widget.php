<?php
defined( 'ABSPATH' ) || exit;

class TOCHATBE_Widget {

    public function __construct() {
        add_action( 'wp_footer', array( $this, 'display_widget' ), 20 );
    }

    public function display_widget() {
        if ( true === $this->is_displayable() ) {
            if ( 'yes' === tochatbe_just_whatsapp_icon_option( 'status' ) ) {
                require_once TOCHATBE_PLUGIN_PATH . 'views/html-just-whatsapp-icon.php';
            } else {
                require_once TOCHATBE_PLUGIN_PATH . 'views/html-widget.php';
            }
        }
    }

    public function is_displayable() {
        if ( ! $this->is_display_on_page() ) {
            return false;
        }
        if ( ! $this->is_scheduled() ) {
            return false;
        }
        if ( wp_is_mobile() && 'yes' === tochatbe_basic_option( 'on_mobile' ) ) {
            return true;
        }
        if ( ! wp_is_mobile() && 'yes' === tochatbe_basic_option( 'on_desktop' ) ) {
            return true;
        }
    }

    public function is_scheduled() {
        $current_day  = strtolower( current_time( 'l' ) );
        $current_time = current_time( 'H:i:s' );
        $start_time   = tochatbe_get_schedule_option( $current_day, 'start' );
        $end_time     = tochatbe_get_schedule_option( $current_day, 'end' );

        if ( 'yes' !== tochatbe_get_schedule_option( $current_day, 'status' ) ) {
            return false;
        }
        if ( $current_time >= $start_time && $current_time <= $end_time ) {
            return true;
        }

        return false;
    }

    public function is_display_on_page() {
        $page_id       = get_the_ID();
        $on_all_pages  = tochatbe_get_filter_by_pages_option( 'on_all_pages' );
        $on_front_page = tochatbe_get_filter_by_pages_option( 'on_front_page' );
        $include_pages = tochatbe_get_filter_by_pages_option( 'include_pages' );
        $exclude_pages = tochatbe_get_filter_by_pages_option( 'exclude_pages' );

        // exclude pages
        if ( $exclude_pages && in_array( $page_id, $exclude_pages ) ) {
            return false;
        }

        // on all pages
        if ( 'yes' === $on_all_pages ) {
            return true;
        }

        // front page
        if ( 'yes' === $on_front_page && is_front_page() ) {
            return true;
        }

        // include pages
        if ( $include_pages && in_array( $page_id, $include_pages ) ) {
            return true;
        }

    }

}

new TOCHATBE_Widget;