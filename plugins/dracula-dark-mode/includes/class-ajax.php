<?php

defined( 'ABSPATH' ) || exit;
class Dracula_Ajax
{
    private static  $instance = null ;
    public function __construct()
    {
        // Get toggles
        add_action( 'wp_ajax_dracula_get_toggles', array( $this, 'get_toggles' ) );
        // Update toggle
        add_action( 'wp_ajax_dracula_update_toggle', array( $this, 'update_toggle' ) );
        // Delete Toggle
        add_action( 'wp_ajax_dracula_delete_toggle', array( $this, 'delete_toggle' ) );
        // Save Settings
        add_action( 'wp_ajax_dracula_save_settings', array( $this, 'save_settings' ) );
        // Duplicate Toggle
        add_action( 'wp_ajax_dracula_duplicate_toggle', [ $this, 'duplicate_toggle' ] );
        // Handle admin  notice
        add_action( 'wp_ajax_dracula_hide_review_notice', [ $this, 'hide_review_notice' ] );
        add_action( 'wp_ajax_dracula_review_feedback', [ $this, 'handle_review_feedback' ] );
        // Hide Recommended Plugins
        add_action( 'wp_ajax_dracula_hide_recommended_plugins', [ $this, 'hide_recommended_plugins' ] );
    }
    
    public function hide_recommended_plugins()
    {
        $nonce = ( !empty($_REQUEST['nonce']) ? sanitize_text_field( $_REQUEST['nonce'] ) : '' );
        // Verify nonce
        if ( !wp_verify_nonce( $nonce, 'dracula' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        update_option( "dracula_hide_recommended_plugins", true );
        wp_send_json_success();
    }
    
    public function hide_review_notice()
    {
        $nonce = ( !empty($_REQUEST['nonce']) ? sanitize_text_field( $_REQUEST['nonce'] ) : '' );
        // Verify nonce
        if ( !wp_verify_nonce( $nonce, 'dracula' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        update_option( 'dracula_rating_notice', 'off' );
    }
    
    public function handle_review_feedback()
    {
        $nonce = ( !empty($_POST['nonce']) ? sanitize_textarea_field( $_POST['nonce'] ) : '' );
        // Verify nonce
        if ( !wp_verify_nonce( $nonce, 'dracula' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        $feedback = ( !empty($_POST['feedback']) ? sanitize_textarea_field( $_POST['feedback'] ) : '' );
        
        if ( !empty($feedback) ) {
            $feedback = sanitize_textarea_field( $feedback );
            $website_url = get_bloginfo( 'url' );
            /* translators: %s: User feedback */
            $feedback = sprintf( __( 'Feedback: %s', 'dracula-dark-mode' ), $feedback );
            $feedback .= '<br>';
            /* translators: %s: Website URL */
            $feedback .= sprintf( __( 'Website URL: %s', 'dracula-dark-mode' ), $website_url );
            /* translators: %s: Plugin name */
            $subject = sprintf( __( 'Feedback for %s', 'dracula-dark-mode' ), 'Radio Player' );
            $to = 'israilahmed5@gmail.com';
            $headers = [ 'Content-Type: text/html; charset=UTF-8', 'From: ' . get_bloginfo( 'name' ) . ' <' . get_bloginfo( 'admin_email' ) . '>' ];
            wp_mail(
                $to,
                $subject,
                $feedback,
                $headers
            );
            $this->hide_review_notice();
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }
    
    }
    
    public function get_toggles()
    {
        $nonce = ( !empty($_POST['nonce']) ? sanitize_text_field( $_POST['nonce'] ) : '' );
        // Verify nonce
        if ( !wp_verify_nonce( $nonce, 'dracula' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        if ( !current_user_can( 'edit_posts' ) ) {
            wp_send_json_error( 'Invalid user' );
        }
        $order_by = ( !empty($_POST['sort_by']) ? sanitize_key( $_POST['sort_by'] ) : 'created_at' );
        $order = ( !empty($_POST['sort_order']) ? sanitize_key( $_POST['sort_order'] ) : 'desc' );
        $args = [];
        $args['order_by'] = $order_by;
        $args['order'] = $order;
        $toggles = Dracula_Toggle_Builder::instance()->get_toggles( $args );
        $formatted = [];
        if ( !empty($toggles) ) {
            foreach ( $toggles as $toggle ) {
                $toggle->config = unserialize( $toggle->config );
                $formatted[] = $toggle;
            }
        }
        wp_send_json_success( $formatted );
    }
    
    public function update_toggle()
    {
        $nonce = ( !empty($_POST['nonce']) ? sanitize_text_field( $_POST['nonce'] ) : '' );
        // Verify nonce
        if ( !wp_verify_nonce( $nonce, 'dracula' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        if ( !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Invalid user' );
        }
        $toggle_data = ( !empty($_POST['data']) ? json_decode( base64_decode( $_POST['data'] ), 1 ) : [] );
        $id = Dracula_Toggle_Builder::instance()->update_toggle( $toggle_data );
        $data = [
            'id'         => $id,
            'config'     => $toggle_data,
            'title'      => $toggle_data['title'],
            'created_at' => ( !empty($toggle_data['created_at']) ? $toggle_data['created_at'] : date( 'Y-m-d H:i:s', time() ) ),
        ];
        wp_send_json_success( $data );
    }
    
    public function delete_toggle()
    {
        $nonce = ( !empty($_POST['nonce']) ? sanitize_text_field( $_POST['nonce'] ) : '' );
        //verify nonce
        if ( !wp_verify_nonce( $nonce, 'dracula' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        if ( !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Invalid user' );
        }
        $id = ( !empty($_POST['id']) ? intval( $_POST['id'] ) : '' );
        Dracula_Toggle_Builder::instance()->delete_toggle( $id );
        wp_send_json_success( $id );
    }
    
    public function save_settings()
    {
        $nonce = ( !empty($_POST['nonce']) ? sanitize_text_field( $_POST['nonce'] ) : '' );
        //verify nonce
        if ( !wp_verify_nonce( $nonce, 'dracula' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        if ( !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Invalid user' );
        }
        $settings = ( !empty($_POST['data']) ? json_decode( base64_decode( $_POST['data'] ), 1 ) : [] );
        $settings_name = 'dracula_settings';
        update_option( $settings_name, $settings );
        wp_send_json_success( $settings );
    }
    
    public function duplicate_toggle()
    {
        $nonce = ( !empty($_POST['nonce']) ? sanitize_text_field( $_POST['nonce'] ) : '' );
        //verify nonce
        if ( !wp_verify_nonce( $nonce, 'dracula' ) ) {
            wp_send_json_error( 'Invalid nonce' );
        }
        if ( !current_user_can( 'manage_options' ) ) {
            wp_send_json_error( 'Invalid user' );
        }
        $ids = ( !empty($_POST['ids']) ? array_map( 'intval', $_POST['ids'] ) : [] );
        $data = [];
        if ( !empty($ids) ) {
            foreach ( $ids as $id ) {
                $data[] = Dracula_Toggle_Builder::instance()->duplicate_toggle( $id );
            }
        }
        wp_send_json_success( $data );
    }
    
    public static function instance()
    {
        if ( null === self::$instance ) {
            self::$instance = new self();
        }
        return self::$instance;
    }

}
Dracula_Ajax::instance();