<?php

namespace wobel\classes\bootstrap;

use wobel\classes\controllers\WOBEL_Ajax;
use wobel\classes\controllers\WOBEL_Post;
use wobel\classes\repositories\Option;
use wobel\classes\repositories\Setting;

class WOBEL
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

        WOBEL_Ajax::register_callback();
        WOBEL_Post::register_callback();
        (new WOBEL_Meta_Fields())->init();
        (new WOBEL_Custom_Queries())->init();

        // update all options
        (new Option())->update_options('wobel');

        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public static function wobel_woocommerce_required()
    {
        include WOBEL_VIEWS_DIR . 'alerts/wobel_woocommerce_required.php';
    }

    public static function wobel_wp_init()
    {
        $version = get_option('wobel-version');
        if (empty($version) || $version != WOBEL_VERSION) {
            update_option('wobel-version', WOBEL_VERSION);
        }

        // load textdomain
        load_plugin_textdomain('ithemeland-woocommerce-bulk-orders-editing-lite', false, WOBEL_LANGUAGES_DIR);
    }

    public function add_menu()
    {
        if (defined('WBEBL_NAME')) {
            add_submenu_page('wbebl', esc_html__('Woo Orders', 'ithemeland-woocommerce-bulk-orders-editing-lite'), esc_html__('Woo Orders', 'ithemeland-woocommerce-bulk-orders-editing-lite'), 'manage_woocommerce', 'wobel', ['wobel\classes\controllers\Woo_Order_Controller', 'init'], 1);
        } else {
            add_menu_page(esc_html__('iT Woo Orders', 'ithemeland-woocommerce-bulk-orders-editing-lite'), sprintf('%s', '<span style="color: #627ddd;font-weight: 900;">iT</span> Woo Orders'), 'manage_woocommerce', 'wobel', ['wobel\classes\controllers\Woo_Order_Controller', 'init'], WOBEL_IMAGES_URL . 'wobel_icon.svg', 2);
        }
    }

    public function enqueue_scripts($page)
    {
        if (!empty($_GET['page']) && $_GET['page'] == 'wobel') {
            if (WOBEL_Verification::is_active() || defined('WBEBL_NAME')) {
                $this->main_enqueue_scripts();
            } else {
                $this->activation_enqueue_scripts();
            }
        }
    }

    private function main_enqueue_scripts()
    {
        $setting_repository = new Setting();
        // Styles
        wp_enqueue_style('wobel-reset', WOBEL_CSS_URL . 'reset.css', [], WOBEL_VERSION);
        wp_enqueue_style('wobel-icomoon', WOBEL_CSS_URL . 'icomoon.css', [], WOBEL_VERSION);
        wp_enqueue_style('wobel-datepicker', WOBEL_CSS_URL . 'bootstrap-material-datetimepicker.css', [], WOBEL_VERSION);
        wp_enqueue_style('wobel-select2', WOBEL_CSS_URL . 'select2.min.css', [], WOBEL_VERSION);
        wp_enqueue_style('wobel-sweetalert', WOBEL_CSS_URL . 'sweetalert.css', [], WOBEL_VERSION);
        wp_enqueue_style('wobel-jquery-ui', WOBEL_CSS_URL . 'jquery-ui.min.css', [], WOBEL_VERSION);
        wp_enqueue_style('wobel-tipsy', WOBEL_CSS_URL . 'jquery.tipsy.css', [], WOBEL_VERSION);
        wp_enqueue_style('wobel-datetimepicker', WOBEL_CSS_URL . 'jquery.datetimepicker.css', [], WOBEL_VERSION);
        wp_enqueue_style('wobel-main', WOBEL_CSS_URL . 'style.css', [], WOBEL_VERSION);
        wp_enqueue_style('wobel-main', WOBEL_CSS_URL . 'style.css', [], WOBEL_VERSION);
        wp_enqueue_style('wp-color-picker');

        // Scripts
        wp_enqueue_script('wobel-datetimepicker', WOBEL_JS_URL . 'jquery.datetimepicker.js', ['jquery'], WOBEL_VERSION);
        wp_enqueue_script('wobel-functions', WOBEL_JS_URL . 'functions.js', ['jquery'], WOBEL_VERSION);
        wp_enqueue_script('wobel-functions', WOBEL_JS_URL . 'functions.js', ['jquery'], WOBEL_VERSION);
        wp_enqueue_script('wobel-select2', WOBEL_JS_URL . 'select2.min.js', ['jquery'], WOBEL_VERSION);
        wp_enqueue_script('wobel-moment', WOBEL_JS_URL . 'moment-with-locales.min.js', ['jquery'], WOBEL_VERSION);
        wp_enqueue_script('wobel-tipsy', WOBEL_JS_URL . 'jquery.tipsy.js', ['jquery'], WOBEL_VERSION);
        wp_enqueue_script('wobel-bootstrap_datepicker', WOBEL_JS_URL . 'bootstrap-material-datetimepicker.js', ['jquery'], WOBEL_VERSION);
        wp_enqueue_script('wobel-sweetalert', WOBEL_JS_URL . 'sweetalert.min.js', ['jquery'], WOBEL_VERSION);
        wp_enqueue_script('wobel-main', WOBEL_JS_URL . 'main.js', ['jquery'], WOBEL_VERSION);
        wp_enqueue_script('wobel-main', WOBEL_JS_URL . 'main.js', ['jquery'], WOBEL_VERSION);
        wp_localize_script('wobel-main', 'WOBEL_DATA', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'wp_nonce' => wp_create_nonce(),
            'strings' => [
                'please_select_one_item' => __('Please select one order', 'ithemeland-woocommerce-bulk-orders-editing-lite')
            ],
            'wobel_settings' => $setting_repository->get_settings(),
        ]);
        wp_enqueue_media();
        wp_enqueue_editor();
        wp_enqueue_script('jquery-ui-sortable');
        wp_enqueue_script('jquery-ui-datepicker');
        wp_enqueue_script('wp-color-picker');
    }

    private function activation_enqueue_scripts()
    {
        wp_enqueue_style('wobel-reset', WOBEL_CSS_URL . 'reset.css', [], WOBEL_VERSION);
        wp_enqueue_style('wobel-sweetalert', WOBEL_CSS_URL . 'sweetalert.css', [], WOBEL_VERSION);
        wp_enqueue_style('wobel-main', WOBEL_CSS_URL . 'style.css', [], WOBEL_VERSION);
        wp_enqueue_style('wobel-activation', WOBEL_CSS_URL . 'activation.css', [], WOBEL_VERSION);

        wp_enqueue_script('wobel-sweetalert', WOBEL_JS_URL . 'sweetalert.min.js', ['jquery'], WOBEL_VERSION);
        wp_enqueue_script('wobel-activation', WOBEL_JS_URL . 'activation.js', ['jquery'], WOBEL_VERSION);
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
        if (!defined('WBEBL_NAME')) {
            update_option('wobel-version', WOBEL_VERSION);

            self::create_tables();
        }
    }

    public static function deactivate()
    {
        $option_repository = new Option();
        $option_repository->delete_options_with_like_name('wobel');
    }
}
