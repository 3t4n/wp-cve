<?php
if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

/************************* ADMIN PAGE **********************************
 ***********************************************************************/

add_action('admin_menu', 'rt_display_free_register_admin_page');

function rt_display_free_register_admin_page()
{
    add_submenu_page('woocommerce', __('Settings', 'rt-display-free'), __('Display Price Free', 'rt-display-free'), 'manage_woocommerce', 'rt_display_settings', 'rt_display_free_submenu_settings_callback');
    add_action('admin_init', 'rt_display_free_register_settings');
}

function rt_display_free_register_settings()
{
    register_setting('rt_display_free_settings_group', 'rt_display_free_custom');
}

function rt_display_free_submenu_settings_callback()
{
    if (current_user_can('manage_options')) {
        ?>
        <div class="wrap woocommerce">
            <div style="background-color:#87b43e;">
            </div>
            <h1><?php _e("Display Price Free for Woocommerce", 'rt-display-free') ?></h1>
            <hr>
            <h2 class="nav-tab-wrapper">
                <a href="?page=rt_display_settings" class="nav-tab <?php
                if ((!isset($_REQUEST['tab']))) {
                    print " nav-tab-active";
                } ?>"><?php _e('Settings', 'rt-display-free') ?></a>
            </h2>
            <?php
            if ((!isset($_REQUEST['tab']))) {
                rt_display_free_submenu_settings();
            } ?>
        </div>
        <?php
    }
}

function rt_display_free_submenu_settings()
{
    ?>
    <h2><?php _e('General setting', 'rt-display-free') ?></h2>
    <?php settings_errors(); ?>
    <form method="post" action="options.php">
        <?php settings_fields('rt_display_free_settings_group'); ?>
        <?php do_settings_sections('rt_display_free_settings_group'); ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label><?php _e('Text Custom', 'rt-display-free') ?></label>
                </th>
                <td>
                    <input name="rt_display_free_custom" type="text" id="rt_display_free_custom"
                           value="<?php echo esc_html(get_option('rt_display_free_custom')) ?>"
                           class="regular-text ltr">
                </td>
            </tr>
            </tbody>
        </table>
        <?php wp_nonce_field('nonce_guardar_display', 'field_nonce_guardar_display'); ?>
        <p class="submit">
            <?php
            $attributes = array('id' => 'btn_guardar_display');
            submit_button(__('Save changes', 'rt-display-free'), 'button button-primary', 'btn_guardar_display', true, $attributes); ?>
        </p>
    </form>
    <?php
}

add_filter('woocommerce_get_price_html', 'rt_display_free_zero_empty', 9999, 2);

function rt_display_free_zero_empty($price, $product)
{
    $text_custom = esc_html(get_option('rt_display_free_custom'));
    if ('' === $product->get_price() || 0 == $product->get_price()) {
        $price = '<span class="woocommerce-Price-amount amount">' . $text_custom . '</span>';
    }
    return $price;
}

function rt_display_free_wc_check()
{
    if (class_exists('woocommerce')) {
        global $rt_display_wc_active;
        $rt_display_wc_active = 'yes';
    } else {
        global $rt_display_wc_active;
        $rt_display_wc_active = 'no';
    }

}

add_action('admin_init', 'rt_display_free_wc_check');

function rt_display_free_wc_admin_notice()
{
    global $rt_display_wc_active;
    if ($rt_display_wc_active == 'no') {
        ?>
        <div class="notice notice-error is-dismissible">
            <p><?php _e("Display Price Free: The module needs to have WooCommerce installed to operate correctly.", 'rt-display-free'); ?></p>
        </div>
        <?php
    }
}

add_action('admin_notices', 'rt_display_free_wc_admin_notice');

// Cart and Checkout
add_filter( 'woocommerce_cart_item_subtotal', 'rt_display_free_checkout_item_subtotal_html', 10, 3 );
function rt_display_free_checkout_item_subtotal_html( $subtotal_html, $cart_item, $cart_item_key )
{
    if( $cart_item['data']->get_price() == 0 ) {
        $text_custom = esc_html(get_option('rt_display_free_custom'));
        return '<span class="woocommerce-Price-amount amount">'. $text_custom .'</span>';
    }
    return $subtotal_html;
}