<?php
/**
* Plugin Name: WP Secure Maintainance
* Plugin URI: https://wpexperts.io/products/wp-secure-maintenance/
* Description: Want to lock your site for Maintainance or Development? Then this is the right Plugin.
* Version:           1.6
* Requires at least: 5.2
* Requires PHP:      7.0
* Author: wpexpertsio
* Author URI: https://wpexperts.io/
* License:           GPL v2 or later
* License URI:       https://www.gnu.org/licenses/gpl-2.0.html
* Text Domaub: wpsp
*/

// Exit if accessed directly
// wp_die(plugin_dir_url(__FILE__) . 'inc/wpsp_functions.php');
require_once( plugin_dir_path(__FILE__) . 'inc/wpsp_functions.php' );
if ( !defined( 'ABSPATH' ) ) exit;

class WPSP_Settings {

	public function __construct() {
		add_action( 'admin_menu', array($this, 'wpsp_registerMenu') );
        add_action( 'admin_init', array($this, 'wpsp_settings') );
        add_action( 'admin_enqueue_scripts', array($this, 'wpsp_enqueue_scripts') );
        add_action('admin_enqueue_scripts', function() {
            wp_enqueue_media();
        });
		// wp_die(  );
    }

    public function wpsp_registerMenu() {
        add_menu_page( 
            __( 'WP Secure Maintenance', 'wpsp' ),
            __( 'WP Secure Settings', 'wpsp' ),
            'manage_options',
            'wpsp-settings', 
            array($this, 'settings_menu'),
            plugin_dir_url( __FILE__ ) . 'img/icon.png'
        );

    }

    public function wpsp_enqueue_scripts() {
        wp_enqueue_style( 'wpsp-styles', plugin_dir_url(__FILE__) . "css/style.css" );
        wp_enqueue_script( 'wpsp-scripts', plugin_dir_url(__FILE__) . "js/wpsp-scripts.js", array('jquery', 'media-editor'));
        wp_enqueue_script( 'media-upload' );
    }

	public function settings_menu() {
		require plugin_dir_path(__FILE__) . "inc/wpsp_options.php";
	}

    public function wpsp_settings() {
        register_setting( 'wp-secure-settings_options_group', 'wp-secure-settings_options',  array( $this, 'sanitize' ));
        
        add_settings_section( 'wpsp', 'WP Secure Settings', array($this, 'wpsp_settings_callback'), 'wpsp-settings' );

        add_settings_field( 'wpsp-enable-maintenance-mode', 'Enable', array($this, 'wpsp_enable_maintenance_mode'), 'wpsp-settings', 'wpsp' );
        add_settings_field( 'wpsp-logo', 'Add Logo', array($this, 'wpsp_logo'), 'wpsp-settings', 'wpsp' );
        add_settings_field( 'wpsp-logo-height', 'Logo Height', array($this, 'wpsp_logo_height'), 'wpsp-settings', 'wpsp' );
        add_settings_field( 'wpsp-logo-width', 'Logo Width', array($this, 'wpsp_logo_width'), 'wpsp-settings' , 'wpsp');
        add_settings_field( 'wpsp-password', 'Password <span style="color:red;">*</span>', array($this, 'wpsp_password'), 'wpsp-settings' , 'wpsp' );
        add_settings_field( 'wpsp-label-submit-button', 'Lable For Submit Button (Optional)', array($this, 'wpsp_label_submit_button'), 'wpsp-settings' , 'wpsp' );
        add_settings_field( 'wpsp-placeholder-text', 'Placeholder Text (Optional)', array($this, 'wpsp_placeholder_text'), 'wpsp-settings' , 'wpsp' );
        add_settings_field( 'wpsp-error-message', 'Error Message (Optional)', array($this, 'wpsp_error_message'), 'wpsp-settings' , 'wpsp' );
        add_settings_field( 'wpsp-background-color', 'Background Color', array($this, 'wpsp_background_color'), 'wpsp-settings' , 'wpsp' );
        add_settings_field( 'wpsp-custom-css', 'Custom CSS', array($this, 'wpsp_custom_css'), 'wpsp-settings' , 'wpsp' );
    }

    public function wpsp_settings_callback() {
        //
    }

    public function wpsp_enable_maintenance_mode() {

        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_enable' );

        if(isset($old_option) && $old_option == 'on' && !is_array($options) && !isset($options['wpsp-enable-maintenance-mode'])) {
            $value = 1;
        } else {
            $value = ( isset( $options['wpsp-enable-maintenance-mode'] ) ) ? $options['wpsp-enable-maintenance-mode'] : false;
        }

        echo '<label class="switch"><input type="checkbox" value="1" name="wp-secure-settings_options[wpsp-enable-maintenance-mode]"'.checked( $value, 1, false ).'><span class="slider round"></span></label>';
    }

    public function wpsp_logo() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_logo_image' );
        if(isset($old_option) && !isset($options['wpsp-logo'])) {
            $url = wp_get_attachment_url($old_option);
        } else {
            $url = ( isset( $options['wpsp-logo'] ) ) ? (attachment_url_to_postid($options['wpsp-logo']) ? attachment_url_to_postid($options['wpsp-logo']) : $options['wpsp-logo']) : plugin_dir_url( __FILE__ ) . 'img/icon.png';
        }

        echo '<input type="hidden" name="wp-secure-settings_options[wpsp-logo]" value="'.$url.'">';
        echo '<div class="container"><div class="avatar-upload"><div class="avatar-edit"><input type="file"  id="wpsp-imageUpload"  accept=".png, .jpg, .jpeg" /><label class="onetarek-upload-button" for="imageUpload"><span style="margin: 7px 7px;" class="dashicons dashicons-edit"></span></label></div><div class="avatar-preview"><div id="imagePreview" style="background-image: url('. wp_get_attachment_url($url) .');"></div></div></div></div>';
    }

    public function wpsp_logo_height() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_logo_height' );
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-logo-height' );

        echo '<div class="field-container"><input class="field-input" value="'.$value.'" name="wp-secure-settings_options[wpsp-logo-height]" type="text" placeholder=" "><label class="field-placeholder" for="inputName">Logo Height</label></div>';
    }

    public function wpsp_logo_width() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option('_logo_width');
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-logo-width' );


        echo '<div class="field-container"><input class="field-input" value="'.$value.'" name="wp-secure-settings_options[wpsp-logo-width]" type="text" placeholder=" "><label class="field-placeholder" for="inputName">Logo Width</label></div>';
    }

    public function wpsp_password() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_pin' );
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-password' );

        echo '<div class="field-container"><input class="field-input" value="'.$value.'" name="wp-secure-settings_options[wpsp-password]" type="text" placeholder=" "><label class="field-placeholder" for="inputName">Password</label></div>';
    }

    public function wpsp_label_submit_button() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_submit_label' );
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-label-submit-button' );

        echo '<div class="field-container"><input class="field-input" value="'.$value.'" name="wp-secure-settings_options[wpsp-label-submit-button]" type="text" placeholder=" "><label class="field-placeholder" for="inputName">Submit button label</label></div>';
    }

    public function wpsp_placeholder_text() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_pin_placeholder' );
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-placeholder-text' );
       
        echo '<div class="field-container"><input class="field-input" value="'.$value.'" name="wp-secure-settings_options[wpsp-placeholder-text]" type="text" placeholder=" "><label class="field-placeholder" for="inputName">Password field placeholder</label></div>';
    }

    public function wpsp_error_message() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_try_again_error' );
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-error-message' );

        echo '<div class="field-container"><input class="field-input" value="'.$value.'" name="wp-secure-settings_options[wpsp-error-message]" type="text" placeholder=" "><label class="field-placeholder" for="inputName">Error Message</label></div>';
    }

    public function wpsp_background_color() {
        $options = get_option( 'wp-secure-settings_options' );
        $old_option = get_option( '_crb_background' );
        $value = $this->wpsp_get_option( $options, $old_option, 'wpsp-background-color' );

        echo '<input class="wpsp-color-picker" name="wp-secure-settings_options[wpsp-background-color]" type="color" value="'.$value.'">';
    }

    public function wpsp_custom_css() {
        $options = get_option( 'wp-secure-settings_options' );
        $value = ( isset( $options['wpsp-custom-css'] ) ) ? $options['wpsp-custom-css'] : '';
        
        echo '<textarea id="wpsp_custom_css" name="wp-secure-settings_options[wpsp-custom-css]" rows="10" placeholder="Additional CSS">'.$value.'</textarea>';
    }

    private function wpsp_get_option( $new_value, $old_value, $field_name ) {
        if( isset( $old_value ) && !isset( $new_value[$field_name] ) ) {
            return $old_value;
        } else {
            return $new_value[$field_name];
        }
    }

}
$instance = new WPSP_Settings();

