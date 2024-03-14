<?php

namespace ECFFW\App\Views\Admin;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use ECFFW\App\Helpers\ProPlugin;

class SettingsPage
{
    /**
     * Settings Page construct.
     */
    public function __construct($data)
    {
        $page = $data['page'];
        $tabs = $data['tabs'];
        $current_tab = $data['tab'];
        $settings = $data['settings'];

        $docs_url = ECFFW_HELP_DOCS_URL;

        if (!ProPlugin::isActive()) {
            ProPlugin::displaySettingsFeatures();
        }
        ?>
            <div class="wrap woocommerce">
                <h1><?php _e("Checkout Field Editor", 'extra-checkout-fields-for-woocommerce'); ?></h1>
                <h2 class="nav-tab-wrapper">
                    <?php
                        foreach ($tabs as $tab => $title) {
                            $active = ($tab == $current_tab) ? ' nav-tab-active' : '';
                            $title = esc_html($title);
                            $url = esc_url($page . '&tab=' . $tab);
                            echo "<a class='nav-tab$active' href='$url'>$title</a>";
                        }
                    ?>
                    <a class="nav-tab" style="float: right; background-color: limegreen; color: white;" target="_blank"
                        href="<?php echo esc_url($docs_url); ?>"><?php _e("Documentation", 'extra-checkout-fields-for-woocommerce'); ?>
                    </a>
                </h2>
                <div id="ecffw-page" style="width: 80%; float: left;">
                    <?php if ($current_tab != 'settings'): ?>
                        <div style="margin-top: 20px;">
                            <h1 style="display: inline; font-size: 20px;">
                                <?php
                                    switch ($current_tab) {
                                        case 'billing':
                                            _e("Billing details", 'extra-checkout-fields-for-woocommerce');
                                            break;
                                        case 'shipping':
                                            _e("Shipping details", 'extra-checkout-fields-for-woocommerce');
                                            break;
                                        case 'order':
                                            _e("Additional information", 'extra-checkout-fields-for-woocommerce');
                                            break;
                                        case 'custom':
                                            esc_html_e($settings['custom_fields_heading']);
                                            break;
                                    }
                                ?>
                            </h1>
                            <form method="post" action="" enctype="multipart/form-data" style="display: inline; float: right;">
                                <?php wp_nonce_field(ECFFW_SETTINGS_KEY, 'ecffw_settings_nonce');?>
                                <input id="ecffw-save" name="save" class="button-primary" type="submit" value="<?php _e('Save Changes', 'extra-checkout-fields-for-woocommerce');?>"/>
                                <textarea id="ecffw-form-builder-json" name="ecffw-form-builder-json" style="display: none;">
                                    <?php
                                        switch ($current_tab) {
                                            case 'billing':
                                                $fields_json = $settings['billing_fields_json']; 
                                                break;
                                            case 'shipping':
                                                $fields_json = $settings['shipping_fields_json']; 
                                                break;
                                            case 'order':
                                                $fields_json = $settings['order_fields_json'];
                                                break;
                                            case 'custom':
                                                $fields_json = $settings['custom_fields_json'];
                                                break;
                                            default:
                                                $fields_json = '[]';
                                        }
                                        echo esc_js($fields_json);
                                    ?>
                                </textarea>
                            </form>
                            <form method="post" action="" enctype="multipart/form-data" style="display: inline; float: right; margin: 0 20px 20px 0;">
                                <?php wp_nonce_field(ECFFW_SETTINGS_KEY, 'ecffw_settings_nonce'); ?>
                                <input class="button-secondary" type="submit" name="reset" value="<?php _e("Restore default fields", 'extra-checkout-fields-for-woocommerce')?>">
                            </form>
                        </div>
                        <div id="ecffw-editor"></div>
                    <?php else: ?>
                        <div id="ecffw-settings">
                            <form method="post" action="" enctype="multipart/form-data">
                                <table class="form-table">
                                    <tbody>
                                        <?php do_action('ecffw_settings_page_before_rows', $settings); ?>
                                        <tr valign="top">
                                            <th scope="row">
                                                <h2 style="margin: 0; margin-bottom: -10px;"><?php _e("Custom Section", 'extra-checkout-fields-for-woocommerce'); ?></h2>
                                            </th>
                                            <td></td>
                                        </tr>
                                        <?php $heading = $settings['custom_fields_heading']; ?>
                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="ecffw-custom-fields-heading"><?php _e("Heading", 'extra-checkout-fields-for-woocommerce'); ?></label>
                                            </th>
                                            <td>
                                                <input type="text" id="ecffw-custom-fields-heading" name="ecffw-custom-fields-heading" value="<?php echo esc_html($heading); ?>"/>
                                            </td>
                                        </tr>
                                        <?php $position = $settings['custom_fields_position']; ?>
                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="ecffw-custom-fields-position"><?php _e("Position", 'extra-checkout-fields-for-woocommerce'); ?></label>
                                            </th>
                                            <td>
                                                <select name="ecffw-custom-fields-position" id="ecffw-custom-fields-position" class="ecffw-custom-fields-position">
                                                    <option value="checkout_before_customer_details" <?php if($position == 'checkout_before_customer_details') echo 'selected="selected"'; ?>><?php _e("Before Customer Details", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                    <option value="checkout_after_customer_details" <?php if($position == 'checkout_after_customer_details') echo 'selected="selected"'; ?>><?php _e("After Customer Details", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                    <option value="before_checkout_billing_form" <?php if($position == 'before_checkout_billing_form') echo 'selected="selected"'; ?>><?php _e("Before Billing Details", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                    <option value="after_checkout_billing_form" <?php if($position == 'after_checkout_billing_form') echo 'selected="selected"'; ?>><?php _e("After Billing Details", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                    <option value="before_checkout_registration_form" <?php if($position == 'before_checkout_registration_form') echo 'selected="selected"'; ?>><?php _e("Before Registration Form", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                    <option value="after_checkout_registration_form" <?php if($position == 'after_checkout_registration_form') echo 'selected="selected"'; ?>><?php _e("After Registration Form", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                    <option value="before_checkout_shipping_form" <?php if($position == 'before_checkout_shipping_form') echo 'selected="selected"'; ?>><?php _e("Before Shipping Details", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                    <option value="after_checkout_shipping_form" <?php if($position == 'after_checkout_shipping_form') echo 'selected="selected"'; ?>><?php _e("After Shipping Details", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                    <option value="before_order_notes" <?php if($position == 'before_order_notes') echo 'selected="selected"'; ?>><?php _e("Before Additional information (Order notes)", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                    <option value="after_order_notes" <?php if($position == 'after_order_notes') echo 'selected="selected"'; ?>><?php _e("After Additional information (Order notes)", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                    <option value="checkout_before_order_review_heading" <?php if($position == 'checkout_before_order_review_heading') echo 'selected="selected"'; ?>><?php _e("Before Order Review (Your order) Heading", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                    <option value="checkout_before_order_review" <?php if($position == 'checkout_before_order_review') echo 'selected="selected"'; ?>><?php _e("Before Order Review (Your order)", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                    <option value="checkout_after_order_review" <?php if($position == 'checkout_after_order_review') echo 'selected="selected"'; ?>><?php _e("After Order Review (Your order)", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <tr valign="top">
                                            <th scope="row">
                                                <h2 style="margin: 0; margin-bottom: -10px;"><?php _e("Form Builder", 'extra-checkout-fields-for-woocommerce'); ?></h2>
                                            </th>
                                            <td></td>
                                        </tr>
                                        <?php $editonadd = $settings['form_builder_editonadd']; ?>
                                        <tr valign="top">
                                            <th scope="row">
                                                <label><?php _e("Edit on Add", 'extra-checkout-fields-for-woocommerce'); ?></label>
                                            </th>
                                            <td>
                                                <input type="checkbox" id="ecffw-form-builder-editonadd" name="ecffw-form-builder-editonadd" <?php if($editonadd == true) echo 'checked="checked"'; ?>/>
                                                <label for="ecffw-form-builder-editonadd"><?php _e("Show Field Edit Panel when Add New Field", 'extra-checkout-fields-for-woocommerce'); ?></label>
                                            </td>
                                        </tr>
                                        <?php $warning = $settings['form_builder_warning']; ?>
                                        <tr valign="top">
                                            <th scope="row">
                                                <label><?php _e("Remove Warning", 'extra-checkout-fields-for-woocommerce'); ?></label>
                                            </th>
                                            <td>
                                                <input type="checkbox" id="ecffw-form-builder-warning" name="ecffw-form-builder-warning" <?php if($warning == true) echo 'checked="checked"'; ?>/>
                                                <label for="ecffw-form-builder-warning"><?php _e("Show Field Remove Warning Message when Remove Field", 'extra-checkout-fields-for-woocommerce'); ?></label>
                                            </td>
                                        </tr>
                                        <?php $control = $settings['form_builder_control']; ?>
                                        <tr valign="top">
                                            <th scope="row">
                                                <label for="ecffw-form-builder-control"><?php _e("Control Position", 'extra-checkout-fields-for-woocommerce'); ?></label>
                                            </th>
                                            <td>
                                                <select name="ecffw-form-builder-control" id="ecffw-form-builder-control" class="ecffw-form-builder-control">
                                                    <option value="left" <?php if($control == 'left') echo 'selected="selected"'; ?>><?php _e("Left", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                    <option value="right" <?php if($control == 'right') echo 'selected="selected"'; ?>><?php _e("Right", 'extra-checkout-fields-for-woocommerce'); ?></option>
                                                </select>
                                            </td>
                                        </tr>
                                        <?php do_action('ecffw_settings_page_after_rows', $settings); ?>
                                    </tbody>
                                </table>
                                <?php wp_nonce_field(ECFFW_SETTINGS_KEY, 'ecffw_settings_nonce');?>
                                <input name="save" class="button-primary" type="submit" value="<?php _e('Save Changes', 'extra-checkout-fields-for-woocommerce');?>" style="margin-top: 20px;" />
                            </form>
                            <form method="post" id="mainform" action="" enctype="multipart/form-data" style="float: left; margin-top: -30px; margin-left: 150px;">
                                <?php wp_nonce_field(ECFFW_SETTINGS_KEY, 'ecffw_settings_nonce'); ?>
                                <input class="button-secondary" type="submit" name="reset" value="<?php _e("Reset", 'extra-checkout-fields-for-woocommerce')?>">
                            </form>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if (!ProPlugin::isActive()): ?>
                    <div id="ecffw-pro-features" style="width: 20%; float: left;">
                        <div style="padding: 20px;">
                            <h3 style="margin-top: 0;"><?php _e("Pro Features", 'extra-checkout-fields-for-woocommerce'); ?></h3>
                            <?php echo ProPlugin::displayListFeatures(); ?>
                        </div>
                    </div>
                <?php endif;?>
            </div>
        <?php
    }
}
