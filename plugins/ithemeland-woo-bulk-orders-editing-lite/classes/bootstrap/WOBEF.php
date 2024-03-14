<?php

namespace wobef\classes\bootstrap;

use wobef\classes\controllers\WOBEF_Ajax;
use wobef\classes\controllers\WOBEF_Post;
use wobef\classes\controllers\Woo_Order_Controller;
use wobef\classes\repositories\Option;

class WOBEF
{
    private static $instance;

    public static function init()
    {
        if (is_null(self::$instance)) {
            self::$instance = new self();
        }
    }

    private function __construct()
    {
        if (!current_user_can('manage_woocommerce')) {
            return;
        }

        WOBEF_Ajax::register_callback();
        WOBEF_Post::register_callback();
        (new WOBEF_Meta_Fields())->init();
        (new WOBEF_Custom_Queries())->init();

        // update all options
        (new Option())->update_options('wobef');

        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'load_assets']);
    }

    public static function wobef_woocommerce_required()
    {
        include WOBEF_VIEWS_DIR . 'alerts/wobef_woocommerce_required.php';
    }

    public static function wobef_wp_init()
    {
        $version = get_option('wobef_version');
        if (empty($version) || $version != WOBEF_VERSION) {
            update_option('wobef_version', WOBEF_VERSION);
        }

        // load textdomain
        load_plugin_textdomain('ithemeland-woocommerce-bulk-order-editing-lite', false, WOBEF_LANGUAGES_DIR);
    }

    public function add_menu()
    {
        add_menu_page(esc_html__('iT Woo Orders', 'ithemeland-woocommerce-bulk-orders-editing-lite'), sprintf('%s', '<span style="color: #627ddd;font-weight: 900;">iT</span> Woo Orders'), 'manage_woocommerce', 'wobef', [new Woo_Order_Controller(), 'index'], WOBEF_IMAGES_URL . 'wobef_icon.svg', 2);
    }

    public function load_assets($page)
    {
        if ($page == "toplevel_page_wobef") {
            // Styles
            wp_enqueue_style('wobef-reset', WOBEF_CSS_URL . 'reset.css');
            wp_enqueue_style('wobef-LineIcons', WOBEF_CSS_URL . 'LineIcons.min.css');
            wp_enqueue_style('wobef-select2', WOBEF_CSS_URL . 'select2.min.css');
            wp_enqueue_style('wobef-sweetalert', WOBEF_CSS_URL . 'sweetalert.css');
            wp_enqueue_style('wobef-jquery-ui', WOBEF_CSS_URL . 'jquery-ui.min.css');
            wp_enqueue_style('wobef-tipsy', WOBEF_CSS_URL . 'jquery.tipsy.css');
            wp_enqueue_style('wobef-datetimepicker', WOBEF_CSS_URL . 'jquery.datetimepicker.css');
            wp_enqueue_style('wobef-scrollbar', WOBEF_CSS_URL . 'jquery.scrollbar.css');
            wp_enqueue_style('wobef-main', WOBEF_CSS_URL . 'style.css', [], '2.1.0');
            wp_enqueue_style('wp-color-picker');

            // Scripts
            wp_enqueue_script('wobef-datetimepicker', WOBEF_JS_URL . 'jquery.datetimepicker.js', ['jquery']);
            wp_enqueue_script('wobef-functions', WOBEF_JS_URL . 'functions.js', ['jquery'], '6.7');
            wp_enqueue_script('wobef-select2', WOBEF_JS_URL . 'select2.min.js', ['jquery']);
            wp_enqueue_script('wobef-moment', WOBEF_JS_URL . 'moment-with-locales.min.js', ['jquery']);
            wp_enqueue_script('wobef-tipsy', WOBEF_JS_URL . 'jquery.tipsy.js', ['jquery']);
            wp_enqueue_script('wobef-scrollbar', WOBEF_JS_URL . 'jquery.scrollbar.min.js', ['jquery']);
            wp_enqueue_script('wobef-sweetalert', WOBEF_JS_URL . 'sweetalert.min.js', ['jquery']);
            wp_enqueue_script('wobef-main', WOBEF_JS_URL . 'main.js', ['jquery'], '6.7');
            wp_localize_script('wobef-main', 'WOBEF_DATA', [
                'ajax_url' => admin_url('admin-ajax.php'),
                'wp_nonce' => wp_create_nonce(),
            ]);
            wp_enqueue_media();
            wp_enqueue_editor();
            wp_enqueue_script('jquery-ui-sortable');
            wp_enqueue_script('jquery-ui-datepicker');
            wp_enqueue_script('wp-color-picker');
        }
    }

    private static function create_tables()
    {
        global $wpdb;
        $history_table_name = esc_sql($wpdb->prefix . 'itbbc_history');
        $history_items_table_name = esc_sql($wpdb->prefix . 'itbbc_history_items');
        $query = '';
        $history_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_table_name));
        if (!$wpdb->get_var($history_table) == $history_table_name) {
            $query .= "CREATE TABLE {$history_table_name} (
                  id int(11) NOT NULL AUTO_INCREMENT,
                  user_id int(11) NOT NULL,
                  fields text NOT NULL,
                  operation_type varchar(32) NOT NULL,
                  operation_date timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                  reverted tinyint(1) NOT NULL DEFAULT '0',
                  sub_system varchar(64) NOT NULL,
                  PRIMARY KEY (id),
                  INDEX (user_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
        }

        $history_items_table = $wpdb->prepare('SHOW TABLES LIKE %s', $wpdb->esc_like($history_items_table_name));
        if (!$wpdb->get_var($history_items_table) == $history_items_table_name) {
            $query .= "CREATE TABLE {$history_items_table_name} (
                      id int(11) NOT NULL AUTO_INCREMENT,
                      history_id int(11) NOT NULL,
                      historiable_id int(11) NOT NULL,
                      field longtext,
                      prev_value longtext,
                      new_value longtext,
                      PRIMARY KEY (id),
                      INDEX (history_id),
                      INDEX (historiable_id)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";

            $query .= "ALTER TABLE {$history_items_table_name} ADD CONSTRAINT itbbc_history_items_history_id_relation FOREIGN KEY (history_id) REFERENCES {$history_table_name} (id) ON DELETE CASCADE ON UPDATE CASCADE;";
        } else {
            $result = $wpdb->get_results("SELECT DATA_TYPE as itbbc_field_type FROM information_schema.columns WHERE table_name = '{$history_items_table_name}' AND column_name = 'field'");
            if (!empty($result[0]->itbbc_field_type) && $result[0]->itbbc_field_type != 'longtext') {
                $wpdb->query("ALTER TABLE {$history_items_table_name} MODIFY field longtext");
            }
        }

        if (!empty($query)) {
            require_once(ABSPATH . '/wp-admin/includes/upgrade.php');
            dbDelta($query);
        }
    }

    public static function activate()
    {
        update_option('wobef_version', WOBEF_VERSION);

        // create tables
        self::create_tables();
    }

    public static function deactivate()
    {
        // clear options
        $option_repository = new Option();
        $option_repository->delete_options_with_like_name('wobef');
    }
}
