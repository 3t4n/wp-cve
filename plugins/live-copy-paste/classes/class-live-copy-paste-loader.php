<?php

use Elementor\Core\Kits\Documents\Kit;
use Elementor\Plugin;
use Elementor\Controls_Manager;

if (!class_exists('LiveCopyPasteLoader')) {
    class LiveCopyPasteLoader {

        private static $_instance = null;

        // const VERSION = VERSION;

        const MINIMUM_PHP_VERSION = '7.0';

        const MINIMUM_ELEMENTOR_VERSION = '3.0.0';

        public function __construct() {
            add_action('plugins_loaded', [$this, 'bdt_lcp_plugins_loaded']);
            add_action('elementor/init', [$this, 'live_copy_paste_tab_settings_init']);
            add_action("admin_init", [$this, 'bdt_duplicator_settings_init']);

            $this->define_constants();
            $this->load_files();
            $this->load_section();
        }

        public function RegisterSectionControls($element, $sectionId) {
            $only_specific_section =   get_option('lcp_enable_magic_copy_btn_specific_section');
            $element->start_controls_section(
                '_section_live_copy_paste',
                [
                    'label'         => __('Live Copy Paste'),
                    'tab'           => Controls_Manager::TAB_ADVANCED
                ]
            );



            if (isset($only_specific_section) && ($only_specific_section == 1)) {
                $element->add_control(
                    'live_copy_paste_magic_btn_switcher',
                    [
                        'label'         => __('Enable Magic Copy'),
                        'type'          => Controls_Manager::SWITCHER,
                        'return_value'  => 'yes',
                        'prefix_class'  => 'magic-button-enabled-',
                        'render_type'   => 'template'
                    ]
                );
            }
            if (isset($only_specific_section) && ($only_specific_section != 1)) {
                $element->add_control(
                    'live_copy_paste_magic_btn_disable',
                    [
                        'label'         => __('Disable Magic Copy'),
                        'type'          => Controls_Manager::SWITCHER,
                        'return_value'  => 'yes',
                        'prefix_class'  => 'magic-button-disabled-',
                        'render_type'   => 'template',
                        'default' => 'no'
                    ]
                );
            }
            $element->end_controls_section();
        }


        // Initialize
        public function bdt_lcp_plugins_loaded() {

            // Check if Elementor installed and activated
            if (
                !did_action('elementor/loaded')
            ) {
                add_action('admin_notices', array($this, 'bdt_lcp_notice_missing_main_plugin'));
                return;
            }

            // Check for required Elementor version
            if (
                !version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')
            ) {
                add_action('admin_notices', array($this, 'bdt_lcp_notice_minimum_elementor_version'));
                return;
            }

            // Check for required PHP version
            if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
                add_action('admin_notices', array($this, 'bdt_lcp_notice_minimum_php_version'));
                return;
            }

            $this->bdt_lcp_load_textdomain();
        }

        public function is_elementor_activated($plugin_path = 'elementor/elementor.php') {
            $installed_plugins_list = get_plugins();
            return isset($installed_plugins_list[$plugin_path]);
        }

        public function bdt_lcp_notice_missing_main_plugin() {
            $plugin = 'elementor/elementor.php';

            if ($this->is_elementor_activated()) {
                if (!current_user_can('activate_plugins')) {
                    return;
                }
                $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);
                $message = sprintf(esc_html__('Live Copy Paste requires %1$s"Elementor"%2$s plugin to be active. Please activate Elementor to continue.', 'live-copy-paste'), '<strong>', '</strong>');
                $button_text = esc_html__('Activate Elementor', 'live-copy-paste');
            } else {
                if (!current_user_can('install_plugins')) {
                    return;
                }

                $activation_url = wp_nonce_url(
                    self_admin_url('update.php?action=install-plugin&plugin=elementor'),
                    'install-plugin_elementor'
                );
                $message = sprintf(esc_html__('Live Copy Paste requires %1$s"Elementor"%2$s plugin to be installed and activated. Please install Elementor to continue.', 'live-copy-paste'), '<strong>', '</strong>');
                $button_text = esc_html__('Install Elementor', 'live-copy-paste');
            }




            $button = '<p><a href="' . esc_url_raw($activation_url) . '" class="button-primary">' . esc_html($button_text) . '</a></p>';

            printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p>%2$s</div>', $message, $button);
        }

        public function bdt_lcp_notice_minimum_elementor_version() {
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }

            $message = sprintf(
                /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
                esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'live-copy-paste'),
                '<strong>' . esc_html__(
                    'Live Copy Paste',
                    'live-copy-paste'
                ) . '</strong>',
                '<strong>' . esc_html__('Elementor', 'live-copy-paste') . '</strong>',
                self::MINIMUM_ELEMENTOR_VERSION
            );

            printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
        }

        public function bdt_lcp_notice_minimum_php_version() {
            if (isset($_GET['activate'])) {
                unset($_GET['activate']);
            }

            $message = sprintf(
                /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
                esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'live-copy-paste'),
                '<strong>' . esc_html__(
                    'Live Copy Paste',
                    'live-copy-paste'
                ) . '</strong>',
                '<strong>' . esc_html__('PHP', 'live-copy-paste') . '</strong>',
                self::MINIMUM_PHP_VERSION
            );

            printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
        }


        static private function define_constants() {
            if (!defined('ABSPATH')) exit; // Exit if accessed directly
            define('BDT_LCP_VER', VERSION);
            define('BDT_LCP_FILE', trailingslashit(dirname(dirname(__FILE__))) . 'live-copy-paste.php');
            define('BDT_LCP_DIR', plugin_dir_path(BDT_LCP_FILE));
            define('BDT_LCP_DIR_URL', plugin_dir_url(__DIR__));
        }

        public function load_files() {
            if (did_action('elementor/loaded')) {
                require_once BDT_LCP_DIR . 'includes/settings/options.php';
            }
            $enable_copy_paste = get_option('lcp_enable_copy_paste_btn');
            $enable_magic_copy = get_option('lcp_enable_magic_copy_btn');
            $enable_magic_copy_specific_section = get_option('lcp_enable_magic_copy_btn_specific_section');


            $enable_duplicator = get_option('bdt_enable_duplicator');
            $ep_other_settings =  get_option('element_pack_other_settings');

            if (isset($ep_other_settings['duplicator'])) {
                $ep_other_settings['duplicator'] = 'off';
            }


            if (isset($ep_other_settings)) :
                if (isset($enable_duplicator) != 1) {
                    update_option('element_pack_other_settings', $ep_other_settings);
                }
            endif;


            if (isset($enable_copy_paste) && ($enable_copy_paste == 1)) {
                require_once BDT_LCP_DIR . 'classes/class-live-copy-paste-btn.php';
            }


            if (isset($enable_magic_copy) && ($enable_magic_copy == 1) || isset($enable_magic_copy_specific_section) && ($enable_magic_copy_specific_section == 1)) {
                require_once BDT_LCP_DIR . 'classes/class-live-copy-paste-magic-btn.php';
            }
            if (isset($enable_duplicator) == 1) {
                require_once BDT_LCP_DIR . 'includes/duplicator/class-duplicator.php';
            }
        }

        public function bdt_lcp_load_textdomain() {
            load_plugin_textdomain('live-copy-paste', false, plugin_dir_path(__FILE__) . '/languages');
        }


        public function live_copy_paste_tab_settings_init() {
            require_once BDT_LCP_DIR . 'includes/settings/controls.php';
            add_action('elementor/kit/register_tabs', function (Kit $kit) {
                $kit->register_tab('live-copy-paste', LiveCopyPasteControls::class);
            }, 1, 40);
        }

        public function load_section() {
            add_action('elementor/element/section/section_advanced/after_section_end', array($this, 'RegisterSectionControls'), 10, 2);
            add_action('elementor/element/container/section_layout/after_section_end', [$this, 'RegisterSectionControls'], 10, 2);
        }

        public static function get_instance() {
            if (
                is_null(self::$_instance)
            ) {
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        public function bdt_duplicator_settings_init() {

            add_settings_section('bdt_duplicator_section', __('Live Copy Paste', 'live-copy-paste'), [$this, 'bdt_duplicator_section_callback'], 'general');
            add_settings_field('bdt_enable_duplicator', __('Enable Duplicator', 'live-copy-paste'), [$this, 'bdt_duplicator_settings_content'], 'general', 'bdt_duplicator_section');
            register_setting('general', 'bdt_enable_duplicator');
        }


        function bdt_duplicator_section_callback() {
            echo "<p>" . __('Settings for Live Copy Paste Duplicator Features', 'live-copy-paste') . "</p>";
        }
        function bdt_duplicator_settings_content() {
            $options = get_option('bdt_enable_duplicator', 1);

            $html = '<input type="checkbox" id="bdt_enable_duplicator" name="bdt_enable_duplicator" value="1"' . checked(1, $options, false) . '/>';
            $html .= '<label for="bdt_enable_duplicator">To enable Live Copy Paste Duplicator Feature make sure this input fields is checked</label>';

            echo $html;
        }
    }
}


/**
 * Returns Instanse of the Live Copy Paste
 */

if (!function_exists('bdt_lcp_init')) {
    function bdt_lcp_init() {
        return  LiveCopyPasteLoader::get_instance();
    }
}

bdt_lcp_init();
