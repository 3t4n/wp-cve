<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class WRGRGM_Settings {

    private static $instance;

    public static function initialize() {

        if ( empty( self::$instance ) ) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    private function __construct() {

        add_action( 'admin_menu', array($this, 'register_settings') );
        add_action( 'admin_post_nopriv_settings_callback', array($this, 'handle_settings') );
        add_action( 'admin_post_settings_callback', array($this, 'handle_settings') );
        add_action( 'admin_notices', array($this, 'admin_notices') );
    }

    public function admin_notices() { 

        $transition_msg = get_transient('_rgm_settings_status');
        delete_transient('_rgm_settings_status');

        if ( ! empty($transition_msg) && $transition_msg == 'success' ):
        ?>
            <div class="notice notice-success is-dismissible">
                <p><?php esc_html_e( 'Google Map API key updated successfully.', 'wrg_rgm' ); ?></p>
            </div>
        <?php
        endif;

        if ( ! empty($transition_msg) && $transition_msg == 'error' ):
        ?>
            <div class="notice notice-error is-dismissible">
                <p><?php esc_html_e( 'Please enter the Google Map API key.', 'wrg_rgm' ); ?></p>
            </div>
        <?php
        endif;
    }

    public function handle_settings() {

        $nonce   = $_POST['_wrg_rgm_settings'];

        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, '_wrg_rgm_settings_action' ) ) {
            return;
        }

        $gmap_key = sanitize_text_field( $_POST['gmap_key'] );
        $post_url = sanitize_text_field( $_POST['_wp_http_referer'] );

        $post_url = str_replace("/wp-admin/", "", $post_url);

        if ( empty( $gmap_key ) ) {
            set_transient( '_rgm_settings_status', 'error', 60 );
            wp_redirect( admin_url($post_url) );
        }
        else {
            RGM_Settings::set_key( $gmap_key );
            set_transient( '_rgm_settings_status', 'success', 60 );
            wp_redirect( admin_url( $post_url ) );
        }
    }

    public function register_settings() {
        add_submenu_page( 
            'edit.php?post_type=wrg_rgm', 
            'Settings', 
            'Settings', 
            'manage_options', 
            'wrg_rgm_settings', 
            array( $this, 'settings_callback' )
        );
    }

    public function settings_callback() { 
        
        $gmap_key = RGM_Settings::get_key();
        ?>
        <div class="wrap">
            <h1>RGM Maps Settings</h1>
            <form method="POST" action="<?php echo admin_url('admin-post.php') ?>">
                <?php wp_nonce_field( '_wrg_rgm_settings_action', '_wrg_rgm_settings' ); ?>
                <input type="hidden" name="action" value="settings_callback">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="gmap_key">Google Map API Key</label></th>
                            <td>
                                <input name="gmap_key" type="text" id="gmap_key" value="<?php echo $gmap_key; ?>" class="regular-text">
                                <p class="description" id="gmap_key-description">Enter the Google Map API key, <a href="https://developers.google.com/maps/documentation/javascript/get-api-key/" target="_blank">Click here to know how to get Google Map API key</a>.</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
                </p>
            </form>
        </div>
        <?php
    }
}

WRGRGM_Settings::initialize();