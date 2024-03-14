<?php
/**
 * Plugin Name: Xl Tab
 * Plugin URI: http://webangon.com
 * Description: Awesome tab accordion for Elementor Page Builder
 * Author: Ashraf
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Author URI: http://webangon.com
 * Version: 1.3
 */

namespace XLTab;

if (!defined('ABSPATH'))
    exit;

if (!class_exists('xltab_init')) :

    final class xltab_init {

        private static $instance;

        public static function instance() {

            if (!isset(self::$instance) && !(self::$instance instanceof xltab_init)) {

                self::$instance = new xltab_init;

                self::$instance->xltab_constant();

                self::$instance->xltab_hooks();

                self::$instance->xltab_includes();

            }
            return self::$instance;
        }

        public function __clone() {
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'xltab'), '1.6');
        }

        public function __wakeup() {
            _doing_it_wrong(__FUNCTION__, __('Cheatin&#8217; huh?', 'xltab'), '1.6');
        }

        private function xltab_constant() {

            // Plugin Folder Path
            if (!defined('XLTAB_DIR')) {
                define('XLTAB_DIR', plugin_dir_path(__FILE__));
            }

            // Plugin Folder URL
            if (!defined('XLTAB_URL')) {
                define('XLTAB_URL', plugin_dir_url(__FILE__));
            }
            define( 'XLTAB_ROOT_FILE__', __FILE__ );

        }

        public function xltab_includes() {

            require_once XLTAB_DIR . 'inc/helper.php';
            require_once XLTAB_DIR . 'inc/optin.php';
            require_once XLTAB_DIR . 'inc/template-lib.php';
            include_once XLTAB_DIR . 'admin/framework/codestar-framework.php';
            include_once XLTAB_DIR . 'admin/configstar/option-panel.php';
            include_once XLTAB_DIR . 'admin/lib/index.php';

        }

        private function xltab_hooks() {
            add_action('elementor/frontend/after_register_scripts', array($this, 'xltab_frontend_scripts'));
            add_action('elementor/widgets/widgets_registered', array(self::$instance, 'xltab_include_widgets'));
            add_action('template_redirect', array(self::$instance, 'template_preview'),9);
            add_action('elementor/init', array($this, 'add_elementor_category'));
        }

        public function add_elementor_category() {
            \Elementor\Plugin::instance()->elements_manager->add_category(
                'xltab',
                array(
                    'title' =>   esc_html__('XL Tab', 'xltab'),
                    'icon' => 'fa fa-plug',
                ),
                1);
        }

        public function template_preview(){

            $instance = \Elementor\Plugin::$instance->templates_manager->get_source( 'local' );
            remove_action( 'template_redirect', [ $instance, 'block_template_frontend' ] );

        }

        public function xltab_frontend_scripts() {

            wp_enqueue_script('xltablib', XLTAB_URL . 'assets/js/xltab-lib.js', array('jquery'), '', true);
            wp_enqueue_script('xltab', XLTAB_URL . 'assets/js/xltab.js', array('jquery'), '', true);

        }

        public function xltab_include_widgets($widgets_manager) {

            $options = get_option( 'xltab' );
            $widgets = [];
            $widgets[] = $options['tab-switch'] ? 'tab-switch' : '';
            $widgets[] = $options['tab1'] ? 'tab1' : '';
            $widgets[] = $options['tab-vertical'] ? 'tab-vertical' : '';
            $widgets[] = $options['accordion'] ? 'accordion' : '';

            if (is_array($widgets)){
                $widgets = array_filter($widgets);
                foreach ($widgets as $key => $value){
                    if (!empty($value)) {
                        require XLTAB_DIR . 'widgets/'.$value.'/index.php';
                    }

                }

            }

        }

    }

endif;

function xltab_run() {
    return xltab_init::instance();
}

xltab_run();
