<?php
/**
 * Plugin Name: «Подсказки» от DaData.ru
 * Plugin URI: https://shafeev.net/plugins/podskazki-ot-dadata-ru
 * Description: Быстрый ввод адресов, компаний, банков, ФИО, email и ещё много чего. Маска ввода телефона для российских и других номеров.
 * Text Domain: dadata-ru
 * Domain Path: /languages
 * Version: 1.0.6
 * Author: Shamil Shafeev
 * Author URI: https://shafeev.net/
 * License:      GPL2
 * 
 * «Подсказки» от DaData.ru is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * «Подсказки» от DaData.ru is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with «Подсказки» от DaData.ru. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
 */

if(!defined("ABSPATH")){
    die;
}

define("SHDADATA_PATH", plugin_dir_path(__FILE__));
define("SHDADATA_VERSION", '1.0.6');


class ShDadata {

    public function __construct() {
        add_action('init', [$this, 'Shdadata_setup']);
        add_action('plugins_loaded', [$this, 'sh_plugins_loaded']);
    }

    public function Shdadata_setup(){
        add_action('wp_footer', [$this, 'Shdadata_enqueue_scripts']);
        add_action( 'admin_init', [$this, 'dadata_settings'] );
        add_filter( 'plugin_action_links', [$this, 'shdadata_plugin_action_links'], 10, 2 );
    }

    public function sh_plugins_loaded(){
        load_plugin_textdomain( 'dadata-ru', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
    }

    public function shdadata_plugin_action_links($actions, $plugin_file){
        if( false === strpos( $plugin_file, basename(__FILE__) ) )
            return $actions;

        $settings_link = '<a href="options-general.php#dadata_sec_id">'.__( 'Settings', 'dadata-ru' ).'</a>';
        array_unshift( $actions, $settings_link );
        return $actions;
    }

    public function Shdadata_enqueue_scripts() {
        wp_enqueue_script('shdadata-jquery-suggestions-min',  plugins_url( '/assets/jquery.suggestions.min.js', __FILE__), array('jquery'),SHDADATA_VERSION );
        wp_enqueue_style('shdadata-main-css',  plugins_url( '/assets/main.css', __FILE__ ), array(), SHDADATA_VERSION );

        if(get_option('dadata_api_key')) {
            wp_enqueue_script('shdadata-main-js',  plugins_url( '/assets/main.js', __FILE__ ), array(), SHDADATA_VERSION );
            $data = array(
                'dadata_api_key' => get_option('dadata_api_key'),
                'dadata_woo_off' => get_option('dadata_woo_off'),
                'dadata_use_mask' => get_option('dadata_use_mask'),
                'dadata_locations' => get_option('dadata_locations'),
                'dadata_count_r' => get_option('dadata_count_r'),
                'dadata_hint' => get_option('dadata_hint'),
                'dadata_minchars' => get_option('dadata_minchars')
            );
            wp_localize_script( 'shdadata-main-js', 'sh_data', $data );
        }
    }


    function dadata_settings() {
        $args = array(
            'sanitize_callback' => 'sanitize_text_field',
            'default'      => '',
            'type'         => 'string',
            'show_in_rest' => true,
        );
        register_setting( 'general', 'dadata_api_key', $args);
        register_setting( 'general', 'dadata_woo_off', $args);
        register_setting( 'general', 'dadata_count_r', $args);
        register_setting( 'general', 'dadata_hint', $args);
        register_setting( 'general', 'dadata_minchars', $args);
        register_setting( 'general', 'dadata_use_mask', $args);
        register_setting( 'general', 'dadata_locations', $args);

        
        add_settings_section(
            'dadata_api_sec', 
            __( 'Setting DaData API', 'dadata-ru' ),
            '',
            'general',
            array(
                'before_section' => '<div class="%s" id="dadata_sec_id" style="border-left: solid 4px #ef4741; padding: 20px;"><img src="'.plugins_url('assets/img/dadata-logo.svg',__FILE__).'" style="width:129px;height:38px;">',
                'after_section' => '</div>',
                'section_class' => 'dadata_sec_class',
            )
        );

        add_settings_field(
            'dadata_api_key',
            'API-ключ (<a href="https://dadata.ru/?ref=143338" target="_blank"><span class="dashicons dashicons-external" style="text-decoration:none;"></span>'.__("get a token","dadata-ru").'</a>)',
            [$this,'dadata_api_field'], 
            'general',
            'dadata_api_sec', 
            array(
                'name' => 'dadata_api_key',
            )
        );

        add_settings_field(
            'dadata_count_r',
            __("The maximum number of hints in the drop-down list. Can't be more than 20" , "dadata-ru"),
            [$this,'dadata_api_field'],
            'general',
            'dadata_api_sec',
            array(
                'name' => 'dadata_count_r',
            )
        );

        add_settings_field(
            'dadata_hint',
            __("Explanatory text that is shown in the drop-down list above the tooltips","dadata-ru"),
            [$this,'dadata_api_field'],
            'general',
            'dadata_api_sec',
            array(
                'name' => 'dadata_hint',
            )
        );

        add_settings_field(
            'dadata_minchars',
            __("The minimum length of the text after which the hints are included","dadata-ru"),
            [$this,'dadata_api_field'],
            'general',
            'dadata_api_sec',
            array(
                'name' => 'dadata_minchars',
            )
        );

        add_settings_field(
            'dadata_use_mask',
            __("Disable phone number entry mask","dadata-ru"),
            [$this,'dadata_api_field_'],
            'general',
            'dadata_api_sec',
            array(
                'name' => 'dadata_use_mask',
            )
        );

        add_settings_field(
            'dadata_woo_off',
            __("Turn off tooltips for WooCommerce","dadata-ru"),
            [$this,'dadata_api_field_'],
            'general', 
            'dadata_api_sec', 
            array(
                'name' => 'dadata_woo_off',
            )
        );

        add_settings_field(
            'dadata_locations',
            __("Countries for address prompts. Enter the values, separated by commas. Example: RU, BY, KZ","dadata-ru"),
            [$this,'dadata_api_field'],
            'general',
            'dadata_api_sec',
            array(
                'name' => 'dadata_locations',
            )
        );
    }
    
    function dadata_api_field($args){
        $value = get_option( $args[ 'name' ] );
        echo '<input type="text"  id="'.esc_attr( $args[ 'name' ] ).'" name="'.esc_attr( $args[ 'name' ] ).'" value="'.$value .'" />';
    }

    function dadata_api_field_($args) { 
        echo '<input name="'.esc_attr( $args[ 'name' ] ).'" type="checkbox"  ' . checked( 1, get_option( $args[ 'name' ] ) , false ) . 'value="1" class="'.esc_attr( $args[ 'name' ] ).'" />';
    }


}

if(class_exists('ShDadata')){
    $shdadata = new ShDadata;
}




