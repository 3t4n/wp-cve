<?php
/*
 * Copyright (c) 2015-2017 Bastian Germann
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 * 
 * Plugin Name: Really Simple CAPTCHA for cformsII
 * Plugin URI: https://wordpress.org/plugins/cforms2-really-simple-captcha/
 * Description: This enables the Really Simple CAPTCHA for the cformsII form plugin.
 * Author: Bastian Germann
 * Version: 1.3
 * Text Domain: cforms2-really-simple-captcha
 */
define( 'CFORMS2_RSC_VERSION', '1.3' );

function cforms2_rsc() {

    if (!class_exists('cforms2_captcha') || !class_exists('ReallySimpleCaptcha') ) {
        return;
    }

    load_plugin_textdomain('cforms2-really-simple-captcha');

    /**
     * Provides the pluggable captcha for cformsII with its settings.
     */
    class cforms2_really_simple_captcha extends cforms2_captcha {

        private $rsc;
        private $url;

        private function __construct() {
            $this->rsc = new ReallySimpleCaptcha();
            $this->url = plugins_url(plugin_basename($this->rsc->tmp_dir));

            $options = get_option('cforms2_rsc_settings');

            $char_length = self::int_range_rand($options['min_len'], $options['max_len']);
            if ($char_length > 0)
                $this->rsc->char_length = intval($char_length);

            $font_size = self::int_range_rand($options['min_pt'], $options['max_pt']);
            if ($font_size > 0)
                $this->rsc->font_size = $font_size;

            $allowed_chars = $options['allowed'];
            if (!empty($allowed_chars))
                $this->rsc->chars = $allowed_chars;

            $width = intval($options['width']);
            $height = intval($options['height']);
            if ($width > 0 && $height > 0)
                $this->rsc->img_size = array($width, $height);

            $color = self::convert_hex_rgb_to_dec($options['bgcolor']);
            $this->rsc->bg = $color;

            $color = self::convert_hex_rgb_to_dec($options['fgcolor']);
            $this->rsc->fg = $color;

            wp_register_script(
                'cforms2_rsc',
                plugin_dir_url(__FILE__) . 'cforms2_really_simple_captcha.js',
                array('jquery'), CFORMS2_RSC_VERSION
            );
            wp_localize_script( 'cforms2_rsc', 'cforms2_rsc_ajax', array(
                'url'   => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('cforms2_rsc_reset_captcha')
            ) );

            add_action( 'wp_ajax_cforms2_rsc_reset_captcha',        array($this, 'reset_captcha') );
            add_action( 'wp_ajax_nopriv_cforms2_rsc_reset_captcha', array($this, 'reset_captcha') );

        }

        /**
         * Converts the two parameters to integers and returns a random number
         * between them.
         * 
         * @param string $min
         * @param string $max
         * @return float between $min and $max
         */
        private static function int_range_rand($min, $max) {
            $min = intval($min);
            $max = intval($max);
            return mt_rand($min, $max);
        }

        /**
         * Converts an HTML hex color string to an int array.
         * 
         * @param string $hex HTML RGB color in the format #RRGGBB.
         * @return array with three int elements: (R, G, B)
         */
        private static function convert_hex_rgb_to_dec($hex) {
            $dec = array();
            $dec[] = hexdec(substr($hex,1,2));
            $dec[] = hexdec(substr($hex,3,2));
            $dec[] = hexdec(substr($hex,5,2));
            return $dec;
        }

        public function get_id() {
            return 'captcha';
        }

        public function get_name() {
            return __('Really Simple CAPTCHA', 'cforms2-really-simple-captcha');
        }

        public function check_authn_users() {
            $options = get_option('cforms2_rsc_settings');
            return isset($options['force']);
        }

        public function check_response($post) {
            $hint = $post[$this->get_id() . '/hint'];
            $answer = $post[$this->get_id()];
            $check = $this->rsc->check($hint, $answer);
            $this->rsc->remove($hint);
            return $check;
        }

        public function get_request($input_id, $input_classes, $input_title) {
            $info = $this->generate_captcha();

            $req = '<label for="'.$input_id.'" class="secq"><span>CAPTCHA</span></label>'
                 . '<input type="text" name="'.$this->get_id().'" id="'.$input_id.'" '
                 . 'class="'.$input_classes.'" title="'.$input_title.'"/>'
                 . '<img class="cforms2_really_simple_captcha_img" alt="CAPTCHA" src="'.$info['url'].'" />'
                 . '<span class="dashicons dashicons-update captcha-reset"></span>'
                 . '<input type="hidden" name="'.$this->get_id().'/hint" value="' . $info['hint'] . '"/>';

            wp_enqueue_script('cforms2_rsc');
            wp_enqueue_style('dashicons');

            return $req;
        }

        private function generate_captcha() {
            $word = $this->rsc->generate_random_word();
            $hint = mt_rand();
            $url = $this->url. '/' .$this->rsc->generate_image( $hint, $word );

            return array(
                'hint' => $hint,
                'url'  => $url
            );
        }

        public function reset_captcha() {
            check_admin_referer( 'cforms2_rsc_reset_captcha' );
            header ('Content-Type: application/json');
            echo json_encode($this->generate_captcha());
            die();
        }

        /**
         * Renders the settings page based on the WordPress Settings API.
         */
        public function settings_html() {
            // manage_cforms capability is defined in cformsII.
            if (!current_user_can('manage_options') || !current_user_can('manage_cforms'))
                return;

            echo '<div class="wrap"><h1>' . esc_html( get_admin_page_title() ) . '</h1>';
            echo '<form action="options.php" method="post">';
            settings_fields('cforms2_rsc');
            do_settings_sections('cforms2_rsc');
            submit_button(__('Save Changes', 'cforms2-really-simple-captcha'));
            echo '</form></div>';
        }

        /**
         * Additional output of a section in front of fields.
         */
        public function section_html() {
        }

        function field_input_html($args) {
            $options = get_option('cforms2_rsc_settings');
            $id = $args['label_for'];
            $type = $args['type'];

            echo '<input name="cforms2_rsc_settings[' . esc_attr($id) . ']" ';
            $value = isset($options[$id]) ? $options[$id] : '';
            if ($type === 'checkbox' && isset($options[$id]))
                echo 'checked="checked" ';
            if (isset($args['input_class']))
                echo 'class="' . $args['input_class'] . '"';
            echo 'value="' . $value . '" type="' . $type . '" ';
            echo 'id="' . esc_attr($id) . '" />';

            if (isset($args['add_text']))
                echo $args['add_text'];
        }

        public function register_javascript() {
            if (is_admin()) {
                wp_enqueue_style('wp-color-picker');
                wp_register_script(
                    'cforms2_rsc_admin',
                    plugin_dir_url(__FILE__) . 'cforms2_really_simple_captcha_admin.js',
                    array('jquery', 'wp-color-picker'), CFORMS2_RSC_VERSION
                );
                wp_enqueue_script('cforms2_rsc_admin');
            }
        }

        public function register_menu() {
            $title = __('Really Simple CAPTCHA for cformsII', 'cforms2-really-simple-captcha');
            add_options_page($title, $title, 'manage_cforms', 'cforms2_rsc', array($this, 'settings_html'));
        }

        /**
         * Registers all CAPTCHA settings based on WordPress Settings API.
         */
        public function register_settings() {
            register_setting('cforms2_rsc', 'cforms2_rsc_settings');
            $cb = array($this, 'field_input_html');


            $section_id = 'cforms2_rsc_general';
            $title = __('General Appearance', 'cforms2-really-simple-captcha');
            add_settings_section($section_id, $title, array($this, 'section_html'), 'cforms2_rsc');

            $title = __('Force display', 'cforms2-really-simple-captcha');
            $args = array(
                'label_for' => 'force',
                'type' => 'checkbox',
                'add_text' => __('Force CAPTCHA display for logged in users', 'cforms2-really-simple-captcha')
            );
            add_settings_field('cforms2_rsc_force', $title, $cb, 'cforms2_rsc', $section_id, $args);

            $title = __('Width', 'cforms2-really-simple-captcha');
            $args = array('label_for' => 'width', 'type' => 'number');
            add_settings_field('cforms2_rsc_width', $title, $cb, 'cforms2_rsc', $section_id, $args);

            $title = __('Height', 'cforms2-really-simple-captcha');
            $args = array('label_for' => 'height', 'type' => 'number');
            add_settings_field('cforms2_rsc_height', $title, $cb, 'cforms2_rsc', $section_id, $args);

            $title = __('Background color', 'cforms2-really-simple-captcha');
            $args = array('label_for' => 'bgcolor', 'type' => 'text', 'input_class' => 'colorpicker');
            add_settings_field('cforms2_rsc_bgcolor', $title, $cb, 'cforms2_rsc', $section_id, $args);


            $section_id = 'cforms2_rsc_font';
            $title = __('Font Appearance', 'cforms2-really-simple-captcha');
            add_settings_section($section_id, $title, array($this, 'section_html'), 'cforms2_rsc');

            $title = __('Minimum size', 'cforms2-really-simple-captcha');
            $args = array('label_for' => 'min_pt', 'type' => 'number');
            add_settings_field('cforms2_rsc_min_pt', $title, $cb, 'cforms2_rsc', $section_id, $args);

            $title = __('Maximum size', 'cforms2-really-simple-captcha');
            $args = array('label_for' => 'max_pt', 'type' => 'number');
            add_settings_field('cforms2_rsc_max_pt', $title, $cb, 'cforms2_rsc', $section_id, $args);

            $title = __('Color', 'cforms2-really-simple-captcha');
            $args = array('label_for' => 'fgcolor', 'type' => 'text', 'input_class' => 'colorpicker');
            add_settings_field('cforms2_rsc_fgcolor', $title, $cb, 'cforms2_rsc', $section_id, $args);


            $section_id = 'cforms2_rsc_char';
            $title = __('Character Set', 'cforms2-really-simple-captcha');
            add_settings_section($section_id, $title, array($this, 'section_html'), 'cforms2_rsc');

            $title = __('Minimum length', 'cforms2-really-simple-captcha');
            $args = array('label_for' => 'min_len', 'type' => 'number');
            add_settings_field('cforms2_rsc_min_len', $title, $cb, 'cforms2_rsc', $section_id, $args);

            $title = __('Maximum length', 'cforms2-really-simple-captcha');
            $args = array('label_for' => 'max_len', 'type' => 'number');
            add_settings_field('cforms2_rsc_max_len', $title, $cb, 'cforms2_rsc', $section_id, $args);

            $title = __('Allowed characters', 'cforms2-really-simple-captcha');
            $args = array('label_for' => 'allowed', 'type' => 'text');
            add_settings_field('cforms2_rsc_allowed', $title, $cb, 'cforms2_rsc', $section_id, $args);
        }

        public static function register() {
            $t = new cforms2_really_simple_captcha();
            $t->register_at_filter();
            add_action('admin_menu', array($t, 'register_menu'));
            add_action('admin_init', array($t, 'register_settings'));
            add_action('admin_enqueue_scripts', array($t, 'register_javascript'));
        }

    }

    cforms2_really_simple_captcha::register();

}

add_action( 'init', 'cforms2_rsc' , 11);


require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';
add_action( 'tgmpa_register', 'cforms2_rsc_register_required_plugins' );

/**
 * Registers the required plugins for this plugin.
 */
function cforms2_rsc_register_required_plugins() {

    $plugins = array(

        array(
            'name'               => 'cformsII',
            'slug'               => 'cforms2',
            'required'           => true,
            'version'            => '14.12.2'
        ),

        array(
            'name'               => 'Really Simple CAPTCHA',
            'slug'               => 'really-simple-captcha',
            'required'           => true
        )

    );

    tgmpa( $plugins );

}
