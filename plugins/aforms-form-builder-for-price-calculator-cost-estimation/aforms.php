<?php
/*
  Plugin Name: AForms -- Form Builder for Price Calculator & Cost Estimation
  Plugin URI:
  Description: Form builder for Cost estimation and custom order. If you have any problems or feature requests for this plugin, please feel free to <a href="https://a-forms.com/en/contact/" target="_blank">contact us</a>.
  Version: 2.2.6
  Author: Vivid Colors, inc.
  Author URI: https://a-forms.com/en/
  License: MIT
  Text Domain: aforms
  Domain Path: /src/template/
 */

require __DIR__.'/vendor/autoload.php';

AFormsWrap::start();

class AFormsWrap 
{
    const VERSION = '2.2.6';
    protected static $singleton;
    
    protected $plugin;

    public function __construct($plugin) 
    {
        $this->plugin = $plugin;
    }

    public static function start() 
    {
        $obj = new self(\AForms\Shell\Dispatcher::newInstance(array('AForms\Config')));
        self::$singleton = $obj;

        add_action('init', array($obj, 'init'));
        add_action('wp_enqueue_scripts', array($obj, 'prepareScripts'));
        add_action('admin_enqueue_scripts', array($obj, 'prepareAdminScripts'));
        register_uninstall_hook(__FILE__, array('AFormsWrap', 'uninstall'));
        load_plugin_textdomain('aforms', false, dirname(plugin_basename(__FILE__)) . '/src/template');
    }

    public static function getService($name) 
    {
        return self::$singleton->plugin->getService($name);
    }

    public function init() 
    {
        $this->addEntryPoints();

        if (is_admin() && is_user_logged_in()) {
            add_action('admin_menu', array($this, 'registerAdminPages'), 2);
            $this->registerAdminAjaxes();
        }
        $this->registerShortcodes();
        $this->registerAjaxes();
        add_action('add_meta_boxes', array($this, 'registerMetaBoxes'));
        add_action('save_post', array($this->plugin, 'hook3'), 5, 3);
    }

    public static function install($networkwide) 
    {
        // fail if on a network activation.
        $errorMarker = get_option('aforms-activation-error', false);
        if ($networkwide || $errorMarker) {
            if ($networkwide && ! $errorMarker) {
                // Called first time. Registers the marker.
                update_option('aforms-activation-error', true);
            } else if (! $networkwide && $errorMarker) {
                // Called again with $networkwide === false. Deletes the marker.
                delete_option('aforms-activation-error');
            } else {  // $networkwide && $errorMarker
                // This should not be the case. As a backup, restore the state to the initial.
                delete_option('aforms-activation-error');
            }
            die('AForms cannot be network activated. Instead, please activate it on a site-by-site basis.');
        }

        self::$singleton->plugin->install();
    }

    public function prepareScripts() 
    {
        // required for supporting multiple WP versions.
        if (! wp_script_is('aforms-front-js', 'registered')) {
            $script = plugins_url('', __FILE__).'/asset/front.js';
            wp_register_script('aforms-front-js', $script);
        }
    }

    public function prepareAdminScripts() 
    {
        // this is required for preview forms.
        $this->prepareScripts();
    }

    public function addEntryPoints() 
    {
        $p = $this->plugin;
        $p->addAdmin('wq-form','formList',null,'AForms\App\Admin\FormList');
        $p->addAdmin('wq-form','form',array('edit','int'),'AForms\App\Admin\FormRef');
        $p->addAdmin('wq-form','form',array('new','int'),'AForms\App\Admin\FormRef');
        $p->addAjax( 'wq-form-set',array('edit','int'),'AForms\App\Admin\FormSet');
        $p->addAjax( 'wq-form-del',array('del','int'),'AForms\App\Admin\FormDel');
        $p->addAjax( 'wq-form-dup',array('dup','int'),'AForms\App\Admin\FormDup');
        $p->addAdmin('wq-form','preview',array('preview','int'), 'AForms\App\Admin\Preview');
        $p->addAdmin('wq-settings','settings',null,'AForms\App\Admin\SettingsRef');
        $p->addAjax( 'wq-settings-set',null,'AForms\App\Admin\SettingsSet');
        $p->addAdmin('wq-order','orderList',null,'AForms\App\Admin\OrderList');
        $p->addAjax( 'wq-order',array('int'),'AForms\App\Admin\OrderListPage');
        $p->addAjax( 'wq-order-del', array('del','int'),'AForms\App\Admin\OrderDel');
        $p->addShort('aforms-form','form',null,'AForms\App\Front\FormRef');
        $p->addAjax( 'wq-custom',array('int'),'AForms\App\Front\Custom');
        $p->addAjax( 'wq-order-new',null,'AForms\App\Front\OrderNew');
        $p->addShort('aforms-result','result',null,'AForms\App\Front\ResultRef');
        $p->addShort('aforms-orderid','orderid',null,'AForms\App\Front\ResultRef');
        $p->addHook( 'aforms_restricted','hook/metabox','AForms\App\Admin\RestrictionRef');
        $p->addHook( 'save_post',null,'AForms\App\Admin\RestrictionSet');
        
        add_action('template_redirect', array($p, 'restrictAccess'));
    }

    public function registerMetaBoxes() 
    {
        add_meta_box('aforms_restricted', __('Access Control', 'aforms'), array($this->plugin, 'hook2'), null, 'side', 'low');
    }

    public function registerAdminPages() 
    {
        add_menu_page(__('Forms', 'aforms'), __('Forms', 'aforms'), 'read', 'wq-form', array($this->plugin, 'adminPage'), 'dashicons-yes', 59.00001);
        add_submenu_page('wq-form', __('Order List', 'aforms'), __('Orders', 'aforms'), 'read', 'wq-order', array($this->plugin, 'adminPage'));
        add_submenu_page('wq-form', __('Form Settings', 'aforms'), __('Form Settings', 'aforms'), 'read', 'wq-settings', array($this->plugin, 'adminPage'));
    }
    
    public function registerShortcodes() 
    {
        add_shortcode('aforms-form', array($this->plugin, 'shortcode'));
        add_shortcode('aforms-result', array($this->plugin, 'shortcode'));
        add_shortcode('aforms-orderid', array($this->plugin, 'shortcode'));
    }
    
    public function registerAjaxes() 
    {
        add_action('wp_ajax_nopriv_wq-custom', array($this->plugin, 'ajax'));
        add_action('wp_ajax_nopriv_wq-order-new', array($this->plugin, 'ajax'));
    }

    public function registerAdminAjaxes() 
    {
        add_action('wp_ajax_wq-settings-set', array($this->plugin, 'ajax'));
        add_action('wp_ajax_wq-form-set', array($this->plugin, 'ajax'));
        add_action('wp_ajax_wq-form-del', array($this->plugin, 'ajax'));
        add_action('wp_ajax_wq-form-dup', array($this->plugin, 'ajax'));
        add_action('wp_ajax_wq-order', array($this->plugin, 'ajax'));
        add_action('wp_ajax_wq-order-del', array($this->plugin, 'ajax'));
        add_action('wp_ajax_wq-custom', array($this->plugin, 'ajax'));
        add_action('wp_ajax_wq-order-new', array($this->plugin, 'ajax'));
    }

    public static function uninstall() 
    {
        global $wpdb;

        // drop form table
        $table = $wpdb->prefix . 'wqforms';
        $sql = "DROP TABLE $table";
        $wpdb->query($sql);
        
        // drop order table
        $table = $wpdb->prefix . 'wqorders';
        $sql = "DROP TABLE $table";
        $wpdb->query($sql);

        // TODO: uninstall .mo, .po
    }
}

register_activation_hook(__FILE__, array('AFormsWrap', 'install'));