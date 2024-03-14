<?php

namespace OXI_FLIP_BOX_PLUGINS\Classes;

if (!defined('ABSPATH'))
    exit;

/**
 * Description of Bootstrap
 *
 * @author biplo
 */
class Bootstrap
{

    use \OXI_FLIP_BOX_PLUGINS\Inc_Helper\Public_Helper;
    use \OXI_FLIP_BOX_PLUGINS\Inc_Helper\Admin_helper;

    // instance container
    private static $instance = null;

    /**
     * Define $wpdb
     *
     * @since 3.1.0
     */
    public $wpdb;

    /**
     * Database Parent Table
     *
     * @since 3.1.0
     */
    public $parent_table;

    /**
     * Database Import Table
     *
     * @since 3.1.0
     */
    public $import_table;

    /**
     * Database Import Table
     *
     * @since 3.1.0
     */
    public $child_table;

    const ADMINMENU = 'get_oxilab_addons_menu';
    /**
     * Shortcode loader
     *
     * @since 3.1.0
     * @access public
     */
    protected function Shortcode_loader()
    {
        add_shortcode('oxilab_flip_box', [$this, 'wp_shortcode']);
        new \OXI_FLIP_BOX_PLUGINS\Modules\Visual_Composer();
        $Flipbox_Widget = new \OXI_FLIP_BOX_PLUGINS\Modules\Widget();
        add_filter('widget_text', 'do_shortcode');
        add_action('widgets_init', array($Flipbox_Widget, 'flip_register_flipwidget'));
    }

    /**
     * Execute Shortcode
     *
     * @since 3.1.0
     * @access public
     */
    public function wp_shortcode($atts)
    {
        extract(shortcode_atts(array('id' => ' ',), $atts));
        $styleid = $atts['id'];
        ob_start();
        $this->shortcode_render($styleid, 'user');
        return ob_get_clean();
    }

    public function __construct()
    {
        do_action('oxi-flip-box-plugin/before_init');
        // Load translation
        add_action('init', array($this, 'i18n'));
        $this->Shortcode_loader();
        $this->Public_loader();
        if (is_admin()) {
            $this->Admin_Filters();
            $this->User_Admin();
            $this->User_Reviews();
        }
    }

    /**
     * Load Textdomain
     *
     * @since 3.1.0
     * @access public
     */
    public function i18n()
    {
        load_plugin_textdomain('oxi-flip-box-plugin');
    }
    public function Public_loader()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->parent_table = $this->wpdb->prefix . 'oxi_div_style';
        $this->child_table = $this->wpdb->prefix . 'oxi_div_list';
        $this->import_table = $this->wpdb->prefix . 'oxi_div_import';
    }

    public function Admin_Filters()
    {
        add_filter($this->fixed_data('6f78692d666c69702d626f782d737570706f72742d616e642d636f6d6d656e7473'), array($this, $this->fixed_data('537570706f7274416e64436f6d6d656e7473')));
        add_filter($this->fixed_data('6f78692d666c69702d626f782d706c7567696e2f70726f5f76657273696f6e'), array($this, $this->fixed_data('636865636b5f63757272656e745f74616273')));
        add_filter($this->fixed_data('6f78692d666c69702d626f782d706c7567696e2f61646d696e5f6d656e75'), array($this, $this->fixed_data('6f78696c61625f61646d696e5f6d656e75')));
    }

    public function User_Admin()
    {

        add_action('admin_menu', [$this, 'Admin_Menu']);
        add_action('admin_head', [$this, 'Admin_Icon']);
        add_action('wp_ajax_oxi_flip_box_data', array($this, 'data_process'));
        add_action('admin_init', array($this, 'redirect_on_activation'));
        add_action('admin_head', [$this, 'welcome_remove_menus']);
    }

    public static function instance()
    {
        if (self::$instance == null) {
            self::$instance = new self;
        }

        return self::$instance;
    }
}
