<?php

namespace OXIIMAEADDONS\Classes;

if (!defined('ABSPATH'))
    exit;

final class Bootstrap {

    use \OXIIMAEADDONS\Helper\Helper;
    use \OXIIMAEADDONS\Helper\Categories;

    /**
     * Addon Version
     *
     * @since 1.0.0
     * @var string The addon version.
     */
    const VERSION = '1.0.0';

    /**
     * Minimum Elementor Version
     *
     * @since 3.0.0
     * @var string Minimum Elementor version required to run the addon.
     */
    const MINIMUM_ELEMENTOR_VERSION = '3.5.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     * @var string Minimum PHP version required to run the addon.
     */
    const MINIMUM_PHP_VERSION = '7.3';

    /**
     * Instance
     *
     * @since 3.0.0
     * @access private
     * @static
     * @var \Elementor_Test_Addon\Plugin The single instance of the class.
     */
    private static $_instance = null;
    public $transient_elements;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @return \Elementor_Test_Addon\Plugin An instance of the class.
     * @since 1.0.0
     * @access public
     * @static
     */
    public static function instance() {

        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        do_action('oxi-hover-effects-addons/before_init');
        // Load translation
        add_action('init', [$this, 'i18n']);

        $this->init();
    }

    /**
     * Load Textdomain
     *
     * @since 3.0.0
     * @access public
     */
    public function i18n() {
        load_plugin_textdomain('oxi-hover-effects-addons');
    }

    public function init() {
        add_filter('oxi-hover-effects-addons-version', [$this, 'addons_version']);
        add_shortcode('ihewc_oxi', [$this, 'ihewc_oxi_shortcode']);
        if ($this->is_compatible()) {
            $this->register_hooks();
        }

        if (is_admin()) {
            $this->oxi_admin();
        }
    }

    public function register_hooks() {
        add_action('elementor/elements/categories_registered', [$this, 'register_widget_categories']);
        add_action('elementor/widgets/register', [$this, 'register_elements']);
        add_action('elementor/editor/before_enqueue_scripts', function () {
            wp_register_style('oxi-image-hover-editor-css', OXIIMAEADDONS_URL . 'Modules/admin.css');
            wp_enqueue_style('oxi-image-hover-editor-css');
        });
    }

}
