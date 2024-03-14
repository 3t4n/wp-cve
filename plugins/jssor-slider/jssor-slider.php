<?php
/**
 * Jssor Slider by jssor.com
 *
 * @link              https://www.jssor.com
 * @link              https://wordpress.org/plugins/jssor-slider/
 * @since             1.0.0
 * @package           Jssor_Slider
 *
 * @wordpress-plugin
 * Plugin Name:       jssor-slider
 * Plugin URI:        https://www.jssor.com
 * Description:       Touch swipe responsive image/content slider/slideshow/carousel/gallery/banner by jssor.com
 * Version:           3.1.24
 * Author:            Jssor
 * Author URI:        https://profiles.wordpress.org/jssor
 * Text Domain:       jssor-slider
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Exit if accessed directly
if( !defined( 'ABSPATH') ) exit();

if(class_exists('Jssor_Slider')) {
    function jssor_slider_duplicate_notice() {
        echo '<div class="error"><p>' .
            'Duplicate jssor slider plugin found.', 'jssor-slider' .
            '</p></div>';
    }
	add_action( 'admin_notices', 'jssor_slider_duplicate_notice' );
}
else {
    class Jssor_Slider {
        private static $JSSOR_SLIDER_CONTROLLER_PATH;

        private static function load_dispatcher() {
            require_once self::$JSSOR_SLIDER_CONTROLLER_PATH;
        }

        private static function get_slider_html_cache($id_or_alias) {
            $hash = (crc32($id_or_alias) & 0x3FFF) % 10000;
            $array = array(floor($hash / 100), $hash % 100);
            $upload = wp_upload_dir();
            $file_path = $upload['basedir'] . '/jssor-slider/html/' . implode('/', $array) . '/' . $id_or_alias . '.htm_';

            $html_code = '';

            if(@file_exists($file_path)) {
                $temp_html_code = @file_get_contents($file_path);
                if(!empty($temp_html_code) && preg_match('/<!--(#endregion )?jssor-slider(-end)?-->[\t\n\r\0\x0B]*$/', $temp_html_code)) {
                    $html_code = $temp_html_code;
                }
            }

            return $html_code;
        }

        public static function get_slider_display_html_code($id_or_alias, $pages_to_show) {
            $html = '';

            if(!empty($id_or_alias)) {
                $id_or_alias = strtolower(strval($id_or_alias));

                #region is to show or not

                $is_to_show = true;
                if(!is_null($pages_to_show)) {
                    $is_to_show = false;

                    $current_page = '';

                    global $post;
                    if(is_front_page()) {
                        $current_page = 'homepage';
                    }
                    else if(isset($post->ID)) {
                        $current_page = strval($post->ID);
                    }

                    $array_pages_to_show = explode(",", $pages_to_show);
                    foreach($array_pages_to_show as $page_to_show) {
                        if(strtolower(trim($page_to_show)) === $current_page) {
                            $is_to_show = true;
                        }
                    }
                }

                #endregion

                if($is_to_show) {
                    //html code should be always cached
                    $html = Jssor_Slider::get_slider_html_cache($id_or_alias);

                    if(empty($html)) {
                        Jssor_Slider::load_dispatcher();
                        $html = Jssor_Slider_Dispatcher::get_slider_display_html_code($id_or_alias);
                    }
                }
            }

            return $html;
        }

        public static function shortcode($attrs) {
            $id_or_alias = null;
            $pages_to_show = null;

            if(isset($attrs['id'])) {
                $id_or_alias = $attrs['id'];
            }
            else if(isset($attrs['alias'])) {
                $id_or_alias = $attrs['alias'];
            }

            if(isset($attrs['show_on_pages'])) {
                $pages_to_show = $attrs['show_on_pages'];
            }

            return Jssor_Slider::get_slider_display_html_code($id_or_alias, $pages_to_show);
        }

        public static function template_redirect() {
            if(isset($_GET['jssorextver'])) {
                self::load_dispatcher();
                Jssor_Slider_Dispatcher::template_redirect();
            }
        }

        public static function admin_menu() {
            self::load_dispatcher();
            Jssor_Slider_Dispatcher::admin_menu();
        }

        public static function display_admin_dashboard() {
            self::load_dispatcher();
            Jssor_Slider_Dispatcher::display_admin_dashboard();
        }

        public static function admin_ajax_action() {
            self::load_dispatcher();
            Jssor_Slider_Dispatcher::admin_ajax_action();
        }

        public static function upgrade_completed($upgrader_object, $options) {
            self::load_dispatcher();
            Jssor_Slider_Dispatcher::upgrade_completed($upgrader_object, $options, plugin_basename( __FILE__ ));
        }

        public static function activate_jssor_slider() {
            self::load_dispatcher();
            Jssor_Slider_Dispatcher::activate_jssor_slider();
        }

        public static function register_hook() {
            self::$JSSOR_SLIDER_CONTROLLER_PATH = dirname(__FILE__) . '/jssor-slider-dispatcher.php';

            add_shortcode('jssor-slider', 'Jssor_Slider::shortcode');
            add_action('admin_menu', 'Jssor_Slider::admin_menu');
            add_action('template_redirect', 'Jssor_Slider::template_redirect');
            add_action('wp_ajax_jssor_slider_action', 'Jssor_Slider::admin_ajax_action' );

            add_action('upgrader_process_complete', 'Jssor_Slider::upgrade_completed', 10, 2 );
            register_activation_hook( __FILE__, 'Jssor_Slider::activate_jssor_slider');
        }
    }

    function putJssorSlider($id_or_alias, $pages_to_show = null) {
        echo Jssor_Slider::get_slider_display_html_code($id_or_alias, $pages_to_show);
    }

    Jssor_Slider::register_hook();
}
