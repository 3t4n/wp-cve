<?php
/*
Plugin Name: Order auto complete for WooCommerce
Plugin URI : webtoptemplate.com
Description:  WooCommerce Order will automatically complete
Version:1.2.1
Author: kardi
Author URI : https://github.com/ikardi420
License : GPL v or later
Text Domain: wtt-woo-auto-complete
Domain Path : /languages/
WC requires at least: 4.2.0
WC tested up to: 8.2.0
*/

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

if (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {


    /**
     * Auto Complete all WooCommerce orders.
     */
    add_action('woocommerce_thankyou', 'custom_woocommerce_auto_complete_order');
    function custom_woocommerce_auto_complete_order($order_id)
    {
        if (!$order_id) {
            return;
        }

        $order = wc_get_order($order_id);
        $order->update_status('completed');
    }
}

function wttwoodecor_settings_init()
{
    // Register a new setting for "woodecor" page.
    register_setting('woodecor', 'woodecor_options1');
    register_setting('woodecor', 'woodecor_options2');


    // Register a new section in the "woodecor" page.
    add_settings_section(
        'woodecor_section_developers',
        __('Here set your settings', 'wtt-woo-auto-complete'),
        'woodecor_section_developers_callback',
        'woodecor'
    );

    // Register a new field in the "woodecor_section_developers" section, inside the "woodecor" page.
    add_settings_field(
        'woodecor_field_cart', // As of WP 4.6 this value is used only internally.
        // Use $args' label_for to populate the id inside the callback.
        __('Add to Cart Button Text', 'wtt-woo-auto-complete'),
        'woodecor_field_cart_cb',
        'woodecor',
        'woodecor_section_developers'
    );
    add_settings_field(
        'woodecor_field_readmore',
        __('Out of Stock Button Text', 'wtt-woo-auto-complete'),
        'woodecor_field_readmore_cb',
        'woodecor',
        'woodecor_section_developers'
    );
}

/**
 * Register our woodecor_settings_init to the admin_init action hook.
 */
add_action('admin_init', 'wttwoodecor_settings_init');


/**
 * Custom option and settings:
 *  - callback functions
 */


/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */

function woodecor_section_developers_callback($args)
{
    if (!is_plugin_active('woocommerce/woocommerce.php')) { ?>
        <div id="message" class="error">
            <p>Woo Decor Add To Cart requires <a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a> to be activated in order to work. Please install and activate <a href="<?php echo admin_url('/plugin-install.php?tab=search&amp;type=term&amp;s=WooCommerce'); ?>" target="">WooCommerce</a> first.</p>
        </div>
    <?php deactivate_plugins('/woo-decor/index.php');
    }
}

/**
 * Pill field callbakc function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function woodecor_field_cart_cb($args)
{
    // Get the value of the setting we've registered with register_setting()
    $options = get_option('woodecor_options1');
    ?>


    <input id='woodecor_field_cart' placeholder="Add To Cart" name='woodecor_options1' type='text' value="<?php echo esc_attr(sanitize_text_field($options)); ?>" />
    <p class="description">
    <div class="tooltip"><?php esc_html_e('here set your text.', 'wtt-woo-auto-complete'); ?>
        <span class="tooltiptext">Set Add to cart Button Text</span>
    </div>

    </p>

<?php
}
function woodecor_field_readmore_cb($args)
{
    // Get the value of the setting we've registered with register_setting()
    $options = get_option('woodecor_options2');
?>


    <input id='woodecor_field_readmore' placeholder="Read More" name='woodecor_options2' type='text' value="<?php echo esc_attr(sanitize_text_field($options)); ?>" />
    <p class="description">
    <div class="tooltip"><?php esc_html_e('here set your text.', 'wtt-woo-auto-complete'); ?>
        <span class="tooltiptext">Set Out of Stock Button Text</span>
    </div>

    </p>




<?php
}

/**
 * Add the top level menu page.
 */


function woodecor_options_page()
{
    add_menu_page(
        'Order auto complete Option Page',
        'Auto Complete',
        'manage_options',
        'woodecor',
        'woodecor_options_page_html'
    );
}


/**
 * Register our woodecor_options_page to the admin_menu action hook.
 */
add_action('admin_menu', 'woodecor_options_page');


/**
 * Top level menu callback function
 */
function woodecor_options_page_html()
{
    // check user capabilities
    if (!current_user_can('manage_options')) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if (isset($_GET['settings-updated'])) {
        // add settings saved message with the class of "updated"
        add_settings_error('woodecor_messages', 'woodecor_message', __('Settings Saved', 'wtt-woo-auto-complete'), 'updated');
    }

    // show error/update messages
    settings_errors('woodecor_messages');
?>
    <div class="wrap">
        <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "woodecor"
            settings_fields('woodecor');
            // output setting sections and their fields
            // (sections are registered for "woodecor", each field is registered to a specific section)
            do_settings_sections('woodecor');
            // output save settings button
            submit_button('Save Settings');
            ?>
        </form>
    </div>
<?php
}

function woodecor_enqueue_scripts()
{

    wp_register_style('woodecor-stylesheet',  plugin_dir_url(__FILE__) . 'assets/css/wtt-style.css');
    wp_enqueue_style('woodecor-stylesheet');
}
add_action('admin_enqueue_scripts', 'woodecor_enqueue_scripts');
function woodecor_enqueue_front_scripts()
{

    wp_register_style('woodecor-front-stylesheet',  plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_style('woodecor-front-stylesheet');
}
add_action('wp_enqueue_scripts', 'woodecor_enqueue_front_scripts');

require_once('function.php');
