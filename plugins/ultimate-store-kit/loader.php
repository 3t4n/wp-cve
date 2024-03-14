<?php

namespace UltimateStoreKit;

use Elementor\Plugin;
use Elementor\Core\Kits\Documents\Kit;
use UltimateStoreKit\Manager;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

/**
 * Main class for element pack
 */
class Ultimate_Store_Kit_Loader {
    /**
     * @var Ultimate_Store_Kit_Loader
     */
    private static $_instance;

    /**
     * @var Manager
     */
    private $_modules_manager;

    private $classes_aliases = [
        'UltimateStoreKit\Modules\PanelPostsControl\Module'                       => 'UltimateStoreKit\Modules\QueryControl\Module',
        'UltimateStoreKit\Modules\PanelPostsControl\Controls\Group_Control_Posts' => 'UltimateStoreKit\Modules\QueryControl\Controls\Group_Control_Posts',
        'UltimateStoreKit\Modules\PanelPostsControl\Controls\Query'               => 'UltimateStoreKit\Modules\QueryControl\Controls\Query',
    ];

    public $elements_data = [
        'sections' => [],
        'columns'  => [],
        'widgets'  => [],
    ];

    /**
     * @return string
     * @deprecated
     *
     */
    public function get_version() {
        return BDTUSK_VER;
    }

    /**
     * return active theme
     */
    public function get_theme() {
        return wp_get_theme();
    }

    /**
     * Throw error on object clone
     *
     * The whole idea of the singleton design pattern is that there is a single
     * object therefore, we don't want the object to be cloned.
     *
     * @return void
     * @since 1.0.0
     */
    public function __clone() {
        // Cloning instances of the class is forbidden
        _doing_it_wrong(__FUNCTION__, esc_html__('Cheatin&#8217; huh?', 'ultimate-store-kit'), '1.6.0');
    }

    /**
     * Disable unserializing of the class
     *
     * @return void
     * @since 1.0.0
     */
    public function __wakeup() {
        // Unserializing instances of the class is forbidden
        _doing_it_wrong(__FUNCTION__, esc_html__('Cheatin&#8217; huh?', 'ultimate-store-kit'), '1.6.0');
    }

    /**
     * @return Plugin
     */

    public static function elementor() {
        return Plugin::$instance;
    }

    /**
     * @return Ultimate_Store_Kit_Loader
     */
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }

        do_action('bdthemes_ultimate_store_kit/init');
        return self::$_instance;
    }


    /**
     * we loaded module manager + admin php from here
     * @return [type] [description]
     */
    private function _includes() {

        require_once BDTUSK_ADMIN_PATH . 'module-settings.php';
        // ========================
        // Helper for global usec
        //==========================
        require(BDTUSK_PATH . 'base/support/helpers.php');
        //===========================
        //          WISHLIST
        //===========================
        require(BDTUSK_INC_PATH . 'wishlist-compare.php');
        //===========================
        //          WISHLIST
        //===========================


        // all widgets control from here
        // require BDTUSK_INC_PATH . 'global-controls.php';
        require BDTUSK_INC_PATH . 'modules-manager.php';
        // Dynamic Select control
        // require BDTUSK_INC_PATH . 'controls/group-query/group-control-query.php';
        require BDTUSK_INC_PATH . 'controls/select-input/dynamic-select-input-module.php';
        require BDTUSK_INC_PATH . 'controls/select-input/dynamic-select.php';


        require_once 'includes/modal/modal-settings.php';


        //GLOBAL CONTROLS
        require_once BDTUSK_PATH . 'traits/global-widget-controls.php';
        require_once BDTUSK_PATH . 'traits/global-widget-template.php';


        if (class_exists('woocommerce')) {
            require_once BDTUSK_PATH . 'includes/builder/loading-builder.php';
        }

        if (is_admin()) {
            if (!defined('BDTUSK_CH')) {

                // Admin settings controller
                require(BDTUSK_ADM_PATH . 'class-settings-api.php');

                require(BDTUSK_ADM_PATH . 'admin.php');
                // element pack admin settings here
                require(BDTUSK_ADM_PATH . 'admin-settings.php');

                // Load admin class for admin related content process
                new Admin();
            }
        }
    }

    /**
     * Autoloader function for all classes files
     * @param  [type] string
     * @return [type]        [description]
     */
    public function autoload($class) {
        if (0 !== strpos($class, __NAMESPACE__)) {
            return;
        }

        $has_class_alias = isset($this->classes_aliases[$class]);

        // Backward Compatibility: Save old class name for set an alias after the new class is loaded
        if ($has_class_alias) {
            $class_alias_name = $this->classes_aliases[$class];
            $class_to_load = $class_alias_name;
        } else {
            $class_to_load = $class;
        }

        if (!class_exists($class_to_load)) {
            $filename = strtolower(
                preg_replace(
                    ['/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/'],
                    ['', '$1-$2', '-', DIRECTORY_SEPARATOR],
                    $class_to_load
                )
            );
            $filename = BDTUSK_PATH . $filename . '.php';

            if (is_readable($filename)) {
                include($filename);
            }
        }

        if ($has_class_alias) {
            class_alias($class_alias_name, $class);
        }
    }

    /**
     * Register all script that need for any specific widget on call basis.
     * @return [type] [description]
     */
    public function register_site_scripts() {

        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        // wp_register_script( 'micromodal', BDTUSK_ASSETS_URL . 'vendor/js/micromodal' . $suffix . '.js', [], '1.0.0', true );
        //wp_register_script( 'micromodal', BDTUSK_ASSETS_URL . 'vendor/js/micromodal' . '.js', [], '1.0.0', true );
        wp_register_script('datatables', BDTUSK_ASSETS_URL . 'vendor/js/datatables' . '.js', [], '1.0.0', true);
        wp_register_script('micromodal', BDTUSK_ASSETS_URL . 'vendor/js/micromodal' . '.js', [], '1.0.0', true);
        wp_register_script('usk-accordion', BDTUSK_ASSETS_URL . 'vendor/js/usk-accordion' . '.js', [], '1.0.0', true);
    }

    public function register_site_styles() {
        $direction_suffix = is_rtl() ? '.rtl' : '';
        wp_register_style('usk-all-styles', BDTUSK_URL . 'assets/css/usk-all-styles' . $direction_suffix . '.css', [], BDTUSK_VER);
        // wp_register_style('datatables', BDTUSK_URL . 'assets/vendor/css/datatables' . $direction_suffix . '.css', [], BDTUSK_VER);
        wp_register_style('ultimate-store-kit-font', BDTUSK_URL . 'assets/css/ultimate-store-kit-font' . $direction_suffix . '.css', [], BDTUSK_VER);
    }

    /**
     * Loading site related style from here.
     * @return [type] [description]
     */
    public function enqueue_site_styles() {

        $direction_suffix = is_rtl() ? '.rtl' : '';

        wp_enqueue_style('ultimate-store-kit-site', BDTUSK_ASSETS_URL . 'css/ultimate-store-kit-site' . $direction_suffix . '.css', [], BDTUSK_VER);
        wp_enqueue_style('slick-modal', BDTUSK_ASSETS_URL . 'vendor/css/slickmodal.css', [], BDTUSK_VER);
        wp_enqueue_style('toolslide-css', BDTUSK_ASSETS_URL . 'vendor/css/toolslide.css', [], BDTUSK_VER);
    }


    /**
     * Loading site related script that needs all time such as uikit.
     * @return [type] [description]
     */
    public function enqueue_site_scripts() {

        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        wp_enqueue_script('ultimate-store-kit-core', BDTUSK_ASSETS_URL . 'js/ultimate-store-kit-core' . $suffix . '.js', ['jquery', 'elementor-frontend'], BDTUSK_VER, true); // tooltip file should be separate
        wp_enqueue_script('ultimate-store-kit-site', BDTUSK_ASSETS_URL . 'js/ultimate-store-kit-site' . $suffix . '.js', ['jquery', 'elementor-frontend'], BDTUSK_VER, true); // tooltip file should be separate
        wp_enqueue_script('slick-modal', BDTUSK_ASSETS_URL . 'vendor/js/jquery.slickmodal' . $suffix . '.js', ['jquery', 'elementor-frontend'], BDTUSK_VER, true); // tooltip file should be separate
        wp_enqueue_script('toolslide-js', BDTUSK_ASSETS_URL . 'vendor/js/toolslide.js', ['elementor-frontend'], BDTUSK_VER, true); // tooltip file should be separate



        wp_localize_script('ultimate-store-kit-site', 'ultimate_store_kit_ajax_config', array(
            'ajaxurl'        => admin_url('admin-ajax.php'),
        ));
    }

    public function enqueue_editor_scripts() {

        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        wp_enqueue_script('usk-editor', BDTUSK_ASSETS_URL . 'js/ultimate-store-kit-editor' . $suffix . '.js', ['backbone-marionette', 'elementor-common-modules', 'elementor-editor-modules',], BDTUSK_VER, true);

        $_is_usk_pro_activated = false;
        if (function_exists('usk_license_validation') && true === usk_license_validation()) {
            $_is_usk_pro_activated = true;
        }

        $localize_data = [
            'pro_installed'         => _is_usk_pro_activated(),
            'pro_license_activated' => $_is_usk_pro_activated,
            'promotional_widgets'   => [],
        ];

        if (!$_is_usk_pro_activated) {
            $pro_widget_map = new \UltimateStoreKit\Includes\Pro_Widget_Map();
            $localize_data['promotional_widgets'] = $pro_widget_map->get_pro_widget_map();
        }

        wp_localize_script('usk-editor', 'UltimateStoreKitConfigEditor', $localize_data);
    }

    public function enqueue_admin_scripts() {

        $suffix = defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ? '' : '.min';

        wp_enqueue_script('ultimate-store-kit-admin', BDTUSK_ASSETS_URL . 'js/ultimate-store-kit-admin' . $suffix . '.js', ['jquery'], BDTUSK_VER, true);
    }

    /**
     * Load editor editor related style from here
     * @return [type] [description]
     */
    public function enqueue_preview_styles() {
        $direction_suffix = is_rtl() ? '.rtl' : '';

        wp_enqueue_style('ultimate-store-kit-preview', BDTUSK_ASSETS_URL . 'css/ultimate-store-kit-preview' . $direction_suffix . '.css', '', BDTUSK_VER);
    }


    public function enqueue_editor_styles() {
        $direction_suffix = is_rtl() ? '.rtl' : '';

        wp_enqueue_style('ultimate-store-kit-editor', BDTUSK_ASSETS_URL . 'css/ultimate-store-kit-editor' . $direction_suffix . '.css', '', BDTUSK_VER);
        wp_enqueue_style('ultimate-store-kit-font', BDTUSK_ASSETS_URL . 'css/ultimate-store-kit-font' . $direction_suffix . '.css', [], BDTUSK_VER);
    }



    public function ultimate_store_kit_init() {
        $this->_modules_manager = new Manager();
        $this->ultimate_store_kit_modal_settings_init();
        do_action('bdthemes_ultimate_store_kit/init');
    }

    /**
     * initialize the category
     * @return [type] [description]
     */
    public function ultimate_store_kit_category_register() {

        $elementor = Plugin::$instance;

        // Add element category in panel
        $elementor->elements_manager->add_category('ultimate-store-kit-single', ['title' => 'Ultimate Store Kit (Single)', 'icon' => 'font']);
        $elementor->elements_manager->add_category(BDTUSK_SLUG, ['title' => BDTUSK_TITLE, 'icon' => 'font']);
    }
    // /**
    //  * initialize the category
    //  * @return [type] [description]
    //  */
    // public function ultimate_store_kit_init() {
    //     $this->_modules_manager = new Manager();
    //     $this->ultimate_store_kit_modal_settings_init();
    //     do_action('bdthemes_ultimate_store_kit/init');
    // }

    // /**
    //  * initialize the category
    //  * @return [type] [description]
    //  */
    // public function ultimate_store_kit_category_register() {

    //     $elementor = Plugin::$instance;

    //     // Add element category in panel
    //     $elementor->elements_manager->add_category(BDTUSK_SLUG, ['title' => BDTUSK_TITLE, 'icon' => 'font']);
    // }
    private function setup_hooks() {
        add_action('elementor/elements/categories_registered', [$this, 'ultimate_store_kit_category_register']);
        add_action('elementor/init', [$this, 'ultimate_store_kit_init']);
        add_action('elementor/editor/after_enqueue_styles', [$this, 'enqueue_editor_styles']);
        add_action('elementor/frontend/before_register_styles', [$this, 'register_site_styles']);
        add_action('elementor/frontend/before_register_scripts', [$this, 'register_site_scripts']);
        add_action('elementor/preview/enqueue_styles', [$this, 'enqueue_preview_styles']);
        add_action('elementor/editor/after_enqueue_scripts', [$this, 'enqueue_editor_scripts']);
        add_action('elementor/frontend/after_register_styles', [$this, 'enqueue_site_styles']);
        add_action('elementor/frontend/before_enqueue_scripts', [$this, 'enqueue_site_scripts']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }

    public function ultimate_store_kit_modal_settings_init() {
        require 'includes/modal/modal-controls.php';
        add_action('elementor/kit/register_tabs', function (Kit $kit) {
            $kit->register_tab('ultimate-store-kit-modal', Includes\Settings\Settings_Modal::class);
        }, 1, 40);
    }



    /**
     * Ultimate_Store_Kit_Loader constructor.
     */
    private function __construct() {
        // Register class automatically
        spl_autoload_register([$this, 'autoload']);
        // Include some backend files
        $this->_includes();

        // Finally hooked up all things here
        $this->setup_hooks();
    }
}

if (!defined('BDTUSK_TESTS')) {
    // In tests we run the instance manually.
    Ultimate_Store_Kit_Loader::instance();
}

// handy fundtion for push data
function ultimate_store_kit_config() {
    return Ultimate_Store_Kit_Loader::instance();
}
