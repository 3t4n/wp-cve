<?php

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/*
* Plugin Name: Revi.io - Customer and Product Reviews
* Plugin URI: https://revi.io
* Description: Product ratings and Customer Reviews for WooCommerce ecommerce
* Version: 5.5.7
* Author: <a href="https://revi.io">Revi</a>
* Text Domain: revi-io-customer-and-product-reviews
* License: GPLv2 or later
*/

require_once(plugin_dir_path(__FILE__) . 'constants.php');
require_once(REVI_DIR . 'classes/revimodel.php');
require_once(REVI_DIR . 'classes/reviwidgets.php');
require_once(REVI_DIR . 'functions.php');
require_once(REVI_DIR . 'shortcodes.php');


$revi_lang = 'en';
if (REVI_LANGUAGE_PLUGIN == 'wpml' && defined('ICL_LANGUAGE_CODE')) {
    if (ICL_LANGUAGE_CODE && strlen(ICL_LANGUAGE_CODE) >= 2 && ICL_LANGUAGE_CODE != 'ICL_LANGUAGE_CODE') {
        $revi_lang = ICL_LANGUAGE_CODE;
    }
} else if (REVI_LANGUAGE_PLUGIN == 'polylang') {
    $revi_lang = pll_current_language();
} else {

    if (!empty(get_option('REVI_SELECTED_LANGUAGE'))) {
        $revi_lang = get_option('REVI_SELECTED_LANGUAGE');
    } else if (!empty(get_option('REVI_LANG'))) {
        $revi_lang = get_option('REVI_LANG');
    }
}
$revimodel = new revimodel();
$revi_lang = $revimodel->parseLang($revi_lang);
define('REVI_DEFAULT_LANGUAGE', $revi_lang);

$id_store = 0;
if (!empty(get_option('REVI_SELECTED_STORE'))) {
    $id_store = get_option('REVI_SELECTED_STORE');
}
define('REVI_ID_STORE', $id_store);



add_action('wp_print_styles', 'revi_styles');
add_action('admin_enqueue_scripts', 'revi_admin_styles');

register_activation_hook(__FILE__, 'revi_install');
register_deactivation_hook(__FILE__, 'revi_uninstall');

add_action('admin_notices', 'my_theme_notice');

function my_theme_notice()
{
    $REVI_NOTIFICATIONS = get_option('REVI_NOTIFICATIONS');
    if ($REVI_NOTIFICATIONS == false) {
        $REVI_NOTIFICATIONS = array();
    }
    $id_user = get_current_user_id();
    if (isset($_GET['revi_dismiss_notification'])) {
        array_push($REVI_NOTIFICATIONS, $id_user);
        update_option('REVI_NOTIFICATIONS', $REVI_NOTIFICATIONS);
        header('Location: ' . $_SERVER['HTTP_REFERER']);
    }

    if (is_array(($REVI_NOTIFICATIONS))) {
        if (!in_array($id_user, $REVI_NOTIFICATIONS)) {
            include 'templates/admin/rate_revi_notification.php';
        }
    }
}

function revi_load_plugin_textdomain()
{
    load_plugin_textdomain('revi-io-customer-and-product-reviews', false, basename(dirname(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'revi_load_plugin_textdomain');

function revi_install()
{
    revi_createReviDatabase();
    revi_createContent();
}

function revi_uninstall()
{
    revi_deleteReviDatabase();
    delete_option('revi_options');
}

function revi_admin_styles()
{
    wp_enqueue_style('back_css', plugins_url('/assets/css/back.css', __FILE__));
}

//Añade el css
function revi_styles()
{
    wp_enqueue_style('inner_css', plugins_url('/assets/css/style.css', __FILE__));
}





add_action('widgets_init', 'revi_register_widgets');
function revi_register_widgets()
{
    include_once(REVI_DIR . 'widgets/class-revi-widget.php');
    return register_widget('revi_Widget');
}



//////////////////////////////////////////////////////////
/**************** GENERAL CONFIGURATION ****************/
////////////////////////////////////////////////////////

add_action('admin_menu', 'revi_plugin_admin_add_page');
function revi_plugin_admin_add_page()
{
    add_menu_page('Revi', 'Revi', 'manage_options', 'revi', 'revi_plugin_configuration_page', plugin_dir_url(__FILE__) . '/icon.png');
}

//PÁGINA DE CONFIGURACIÓN DEL PLUGIN
function revi_plugin_configuration_page()
{
    wp_enqueue_style('revi_bootstrap_css', plugins_url('/assets/css/bootstrap.min.css', __FILE__));

    global $wpdb;
    $revimodel = new revimodel();
    $result_update = true;
    $message = '';

    //CHECK UPDATE PLUGIN
    $update_sqls = array();
    $module_version_bd = get_option('REVI_MODULE_VERSION');
    if (!isset($module_version_bd)) {
        $module_version_bd = '4.0.0';
    }

    $upgrade_files = array('4.0.13', '4.2.4', '4.2.5', '4.2.6', '4.2.7');

    foreach ($upgrade_files as $upgrade_file) {
        // -1 es mayor, 0 es igual, 1 es menor
        if (version_compare($upgrade_file, $module_version_bd) == 1) {
            $update_sqls[] = $upgrade_file;
        }
    }

    if (count($update_sqls)) {
        foreach ($update_sqls as $update_sql) {
            echo "<br>Upgrading to " . $update_sql . ".sql";
            $sql = file_get_contents(plugin_dir_path(__FILE__) . "/upgrade/upgrade-" . $update_sql . ".sql");
            $wpdb->query($sql);
        }
    }

    //ACTUALIZAMOS EN OPTIONS LA NUEVA VERSIÓN DEL PLUGIN
    $plugin_data = get_plugin_data(__FILE__);
    $module_version_actual = $plugin_data['Version'];
    update_option('REVI_MODULE_VERSION', $module_version_actual);


    if (isset($_POST['REVI_API_KEY'])) {
        $message = revi_save_content();
    }


    // ESTÁ LOGGEADO
    if (get_option('REVI_API_KEY') && get_option('REVI_ID_SHOP')) {
        $selectedStatuses = get_option('REVI_ORDER_STATUSES');
        if (empty($selectedStatuses)) {
            $selectedStatuses = array();
        }

        if (WOOCOMMERCE_ACTIVE) {
            $allStatuses = wc_get_order_statuses();
        } else {
            $allStatuses = array();
        }
        $status_selected = array();

        if (is_array(($selectedStatuses))) {
            foreach ($allStatuses as $key => $value) {

                if (in_array($key, $selectedStatuses)) {
                    $status_selected[$key] = true;
                } else {
                    $status_selected[$key] = false;
                }
            }
        }

        $stores = json_decode(get_option('REVI_STORES'));
        $selected_store = get_option('REVI_SELECTED_STORE');

        $active_languages = json_decode(get_option('REVI_ACTIVE_LANGUAGES'));
        $selected_language = get_option('REVI_SELECTED_LANGUAGE');


        if (WOOCOMMERCE_ACTIVE) {
            $order_status = wc_get_order_statuses();
        } else {
            $order_status = array();
        }

        $tab_reviews = get_option('REVI_TAB_REVIEWS');
        $tab_product_stars = get_option('REVI_TAB_PRODUCT_STARS');
        $display_widget_floating = get_option('REVI_DISPLAY_WIDGET_FLOATING');

        $woocommerce_reviews = get_option('REVI_WOOCOMMERCE_REVIEWS');
        $REVI_CATEGORY_JSON_META = get_option('REVI_CATEGORY_JSON_META');

        switch (get_option('REVI_SUBSCRIPTION')) {
            case 2:
                $subscription = 'Revi Pro';
                break;
            case 3:
                $subscription = 'Revi Premium';
                break;
            default:
                $subscription = 'Revi Free';
                break;
        }

        $plugin_data = get_plugin_data(__FILE__);
        $revimodel->sendModuleVersion($plugin_data['Version']);
        include 'templates/admin/settings.php';
    } else {
        include 'templates/admin/login.php';
    }
}

function revi_save_content()
{
    $revimodel = new revimodel();

    update_option('REVI_API_KEY', sanitize_text_field($_POST['REVI_API_KEY']));

    // Actualizamos la configuration
    $result_update = $revimodel->updateConfiguration();
    if (!$result_update) {
        update_option('REVI_API_KEY', '');
        return 'Wrong API KEY, not logged in, try again or contact us at revi.io';
    }

    if (isset($_POST['status'])) {
        $status = array_map('sanitize_text_field', $_POST['status']);
        update_option('REVI_ORDER_STATUSES', $status);
    }

    if (isset($_POST['stores'])) {
        $stores = sanitize_text_field($_POST['stores']);
        update_option('REVI_SELECTED_STORE', $stores);
    }

    if (isset($_POST['languages'])) {
        $selected_language = sanitize_text_field($_POST['languages']);
        update_option('REVI_SELECTED_LANGUAGE', $selected_language);
    }

    if (isset($_POST['tab_reviews'])) {
        $tab_reviews = sanitize_text_field($_POST['tab_reviews']);
        update_option('REVI_TAB_REVIEWS', $tab_reviews);
    }

    if (isset($_POST['tab_product_stars'])) {
        $tab_product_stars = sanitize_text_field($_POST['tab_product_stars']);
        update_option('REVI_TAB_PRODUCT_STARS', $tab_product_stars);
    }

    if (isset($_POST['display_widget_floating'])) {
        $display_widget_floating = sanitize_text_field($_POST['display_widget_floating']);
        update_option('REVI_DISPLAY_WIDGET_FLOATING', $display_widget_floating);
    }

    if (isset($_POST['woocommerce_reviews'])) {
        $woocommerce_reviews = sanitize_text_field($_POST['woocommerce_reviews']);
        update_option('REVI_WOOCOMMERCE_REVIEWS', $woocommerce_reviews);
    }

    if (isset($_POST['REVI_CATEGORY_JSON_META'])) {
        $REVI_CATEGORY_JSON_META = sanitize_text_field($_POST['REVI_CATEGORY_JSON_META']);
        update_option('REVI_CATEGORY_JSON_META', $REVI_CATEGORY_JSON_META);
    }

    return 'Congratulations! You are now logged in successfully';
}

/* Query Vars */
add_filter('query_vars', 'revi_register_query_var');
function revi_register_query_var($vars)
{
    $vars[] = 'revi_page';
    return $vars;
}

/* LOAD CONTROLLERS */
add_filter('template_include', 'revi_template_include');
function revi_template_include($template)
{
    if (isset($_GET['revi_page'])) {
        if (isset($_GET['revi_page']) && $_GET['revi_page'] == "orders") {
            require_once(REVI_DIR . 'controllers/orders.php');
            new revi_orders();
        }
        if (isset($_GET['revi_page']) && $_GET['revi_page'] == "sync") {
            require_once(REVI_DIR . 'controllers/sync.php');
            new revi_sync();
        }
        if (isset($_GET['revi_page']) && $_GET['revi_page'] == "products") {
            require_once(REVI_DIR . 'controllers/products.php');
            new revi_products();
        }
        if (isset($_GET['revi_page']) && $_GET['revi_page'] == "send") {
            require_once(REVI_DIR . 'controllers/send.php');
            new revi_send($_GET['encoded_string']);
        }
        if (isset($_GET['revi_page']) && $_GET['revi_page'] == "connection") {
            require_once(REVI_DIR . 'controllers/connection.php');
            new revi_connection();
        }
    }

    return $template;
}

///////////////////////// DATABASE ///////////////////////////

function revi_createReviDatabase()
{
    global $wpdb;

    $structure0 = 'CREATE TABLE IF NOT EXISTS `revi_orders` (
    `id_order` BIGINT(20) NOT null,
    `status` INT(1) NOT null,
    `date_sent` DATETIME NOT null,
    PRIMARY KEY (`id_order`)
    ) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
    $wpdb->query($structure0);

    $structure1 = 'CREATE TABLE IF NOT EXISTS `revi_comments` (
	`id_comment` BIGINT(20) NOT null,
	`id_order` VARCHAR(11) NOT null,
	`id_shop` BIGINT(20) NOT null,
	`id_product` BIGINT(20) NOT null,
	`customer_name` VARCHAR(30) NOT null,
	`customer_lastname` VARCHAR(50) NOT null,
	`email` VARCHAR(100) NOT null,
	`IP` VARCHAR(45) NOT null,
	`date` DATETIME NOT null,
	`comment` VARCHAR(5000) NOT null,
	`rating` FLOAT(5) NOT null,
	`status` INT(1) NOT null,
	`lang` VARCHAR(3) NOT null,
	`external` VARCHAR(20) NOT null,
	`anonymous` TINYINT(1) NOT null,
	PRIMARY KEY (`id_product`, `id_comment`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
    $wpdb->query($structure1);

    $structure2 = 'CREATE TABLE IF NOT EXISTS `revi_products` (
	`id_product` VARCHAR(36) NOT null,
	`num_ratings` INT(10) NOT null,
	`avg_rating` FLOAT(5) NOT null,
	`date_sent` DATETIME NOT null,
	PRIMARY KEY (`id_product`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
    $wpdb->query($structure2);

    $structure3 = 'CREATE TABLE IF NOT EXISTS `revi_categories` (
	`id_category` BIGINT(20) NOT null,
	`num_ratings` INT(10) NOT null,
	`avg_rating` FLOAT(5) NOT null,
	PRIMARY KEY (`id_category`)
	) ENGINE=MyISAM DEFAULT CHARSET=utf8;';
    $wpdb->query($structure3);
}

function revi_deleteReviDatabase()
{
    global $wpdb;

    $structure0 = "DROP TABLE IF EXISTS `revi_orders`";
    $structure1 = "DROP TABLE IF EXISTS `revi_comments`";
    $structure2 = "DROP TABLE IF EXISTS `revi_products`";
    $structure3 = "DROP TABLE IF EXISTS `revi_categories`";

    $wpdb->query($structure0);
    $wpdb->query($structure1);
    $wpdb->query($structure2);
    $wpdb->query($structure3);
}

function revi_createContent()
{
    $plugin_data = get_plugin_data(__FILE__);
    $module_version_actual = $plugin_data['Version'];
    if (
        !update_option('REVI_MODULE_VERSION', $module_version_actual)
        || !update_option('REVI_ID_SHOP', '')
        || !update_option('REVI_URL', '')
        || !update_option('REVI_SELECTED_STORE', 0)
        || !update_option('REVI_LANG', 'en')
        || !update_option('REVI_SELECTED_LANGUAGE', 'en')
        || !update_option('REVI_SECURITY_KEY', '')
        || !update_option('REVI_API_KEY', '')
        || !update_option('REVI_SUBSCRIPTION', '0')
        || !update_option('REVI_ORDER_STATUSES', array('wc-completed'))
        || !update_option('REVI_SELECTED_SHOPS', array(1))
        || !update_option('REVI_RATING_TYPE', '10')
        || !update_option('REVI_TAB_REVIEWS', 0)
        || !update_option('REVI_TAB_PRODUCT_STARS', 0)
        || !update_option('REVI_DISPLAY_WIDGET_FLOATING', 1)
        || !update_option('REVI_NOTIFICATIONS', array())
    ) {
        return false;
    }
    return true;
}
