<?php

/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 6.1.0
 */

defined('ABSPATH') || exit;

global $product;



$get_variations = count($product->get_children()) <= apply_filters('woocommerce_ajax_variation_threshold', 30, $product);
$available_variations = $get_variations ? $product->get_available_variations() : false;
$attributes = $product->get_variation_attributes();
$selected_attributes = $product->get_default_attributes();
$attribute_keys = array_keys($attributes);
$variations_json = wp_json_encode($available_variations);
$variations_attr = function_exists('wc_esc_json') ? wc_esc_json($variations_json) : _wp_specialchars($variations_json, ENT_QUOTES, 'UTF-8', true);
$_select_fld = isset($settings['add_to_cart_var_attr_select_fld']) ? $settings['add_to_cart_var_attr_select_fld'] : '';
$table_layout = isset($settings['w_ready_table_layout']) ? $settings['w_ready_table_layout'] : '-table';
$qty_label = isset($settings['variable_qty_label']) && $settings['variable_qty_label'] == 'yes' ? $settings['variable_qty_label_text'] : '';

do_action('woocommerce_before_add_to_cart_form'); ?>

<form class="variations_form cart wready-product-variation-wrapper"
    action="<?php echo esc_url(apply_filters('woocommerce_add_to_cart_form_action', $product->get_permalink())); ?>"
    method="post" enctype='multipart/form-data' data-product_id="<?php echo absint($product->get_id()); ?>"
    data-product_variations="
    <?php
    echo wp_kses_post($variations_attr); // WPCS: XSS ok.
    ?>
    ">
    <?php if (empty($available_variations) && false !== $available_variations): ?>
        <p class="stock out-of-stock">
            <?php echo esc_html(apply_filters('woocommerce_out_of_stock_message', __('This product is currently out of stock and unavailable.', 'shopready-elementor-addon'))); ?>
        </p>
    <?php else: ?>
        <?php if ($table_layout == 'table'): ?>
            <table class="variations woo-ready-product-var-table" cellspacing="0">
                <tbody>
                    <?php foreach ($attributes as $attribute_name => $options): ?>
                        <?php

                        $attributes_id_arr = wp_list_pluck(wc_get_attribute_taxonomies(), 'attribute_id', 'attribute_name');
                        $remove_suffix = preg_replace('/^pa_/', '', $attribute_name);
                        $woo_ready_color_id = isset($attributes_id_arr[$remove_suffix]) ? $attributes_id_arr[$remove_suffix] : null;
                        $attribute_wrea = get_option('woo_ready_product_attributes') ? get_option('woo_ready_product_attributes') : array();
                        $woo_ready_display_type = sanitize_text_field(isset($_POST['woo_ready_display_type']) ? sanitize_text_field($_POST['woo_ready_display_type']) : (isset($attribute_wrea[$woo_ready_color_id]) ? $attribute_wrea[$woo_ready_color_id] : ''));

                        ?>
                        <tr class="wready-row <?php echo esc_attr($woo_ready_display_type); ?>">
                            <?php if ($_select_fld != 'none'): ?>
                                <td class="label"><label for="<?php echo esc_attr(sanitize_title($attribute_name)); ?>">
                                        <?php
                                        echo esc_html(wc_attribute_label($attribute_name)); // WPCS: XSS ok.
                                        ?>
                                    </label>
                                </td>
                            <?php endif; ?>
                            <td
                                class="value wready--shop-ready_attr-type <?php echo esc_html($woo_ready_display_type != '' ? $woo_ready_display_type : 'select'); ?>">
                                <?php


                                wc_dropdown_variation_attribute_options(
                                    array(
                                        'options' => $options,
                                        'attribute' => $attribute_name,
                                        'product' => $product,
                                        'wready_select' => $woo_ready_display_type,
                                    )
                                );

                                echo end($attribute_keys) === $attribute_name ? wp_kses_post(apply_filters('woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__('Clear', 'shopready-elementor-addon') . '</a>')) : '';
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="variations woo-ready-product-var-table display:flex flex-direction:column gap:10">
                <?php foreach ($attributes as $attribute_name => $options): ?>
                    <?php

                    $attributes_id_arr = wp_list_pluck(wc_get_attribute_taxonomies(), 'attribute_id', 'attribute_name');
                    $remove_suffix = preg_replace('/^pa_/', '', $attribute_name);
                    $woo_ready_color_id = isset($attributes_id_arr[$remove_suffix]) ? $attributes_id_arr[$remove_suffix] : null;
                    $attribute_wrea = get_option('woo_ready_product_attributes') ? get_option('woo_ready_product_attributes') : array();
                    $woo_ready_display_type = sanitize_text_field(isset($_POST['woo_ready_display_type']) ? sanitize_text_field($_POST['woo_ready_display_type']) : (isset($attribute_wrea[$woo_ready_color_id]) ? $attribute_wrea[$woo_ready_color_id] : ''));

                    ?>
                    <div class="wready-row display:flex flex-direction:row gap:20 <?php echo esc_attr($woo_ready_display_type); ?>">

                        <div class="label">
                            <label for="<?php echo esc_attr(sanitize_title($attribute_name)); ?>">
                                <?php if ($_select_fld != 'none'): ?>
                                    <?php
                                    echo esc_html(wc_attribute_label($attribute_name)); // WPCS: XSS ok.
                                    ?>
                                <?php endif; ?>
                            </label>
                        </div>

                        <div class="value display:flex flex-direction:column <?php echo esc_attr($woo_ready_display_type); ?>">
                            <?php

                            wc_dropdown_variation_attribute_options(
                                array(
                                    'options' => $options,
                                    'attribute' => $attribute_name,
                                    'product' => $product,
                                    'wready_select' => $woo_ready_display_type,
                                )
                            );

                            echo end($attribute_keys) === $attribute_name ? wp_kses_post(apply_filters('woocommerce_reset_variations_link', '<a class="reset_variations" href="#">' . esc_html__('Clear', 'shopready-elementor-addon') . '</a>')) : '';
                            ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif ?>

        <div class="single_variation_wrap">
            <div class="woocommerce-variation single_variation"></div>
            <div class="woocommerce-variation-add-to-cart variations_button">

                <div class="shop-ready-quantity-warapper display:flex gap:15">
                    <?php if ($qty_label != ''): ?>
                        <div class="shop-ready-product-qty-label">
                            <?php echo esc_html($qty_label); ?>
                        </div>
                    <?php endif; ?>
                    <?php

                    wp_kses_post(
                        woocommerce_quantity_input(
                            array(
                                'min_value' => apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product),
                                'max_value' => apply_filters('woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product),
                                'input_value' => sanitize_text_field(isset($_POST['quantity']) ? wc_stock_amount(wp_unslash(sanitize_text_field($_POST['quantity']))) : $product->get_min_purchase_quantity()),
                                // WPCS: CSRF ok, input var ok.
                            ),
                            $product
                        )
                    );


                    ?>

                </div>

                <button type="submit" class="single_add_to_ca
                rt_button button alt">

                    <?php echo esc_html($product->single_add_to_cart_text()); ?>
                </button>

                <input type="hidden" name="add-to-cart" value="<?php echo absint($product->get_id()); ?>" />
                <input type="hidden" name="product_id" value="<?php echo absint($product->get_id()); ?>" />
                <input type="hidden" name="variation_id" class="variation_id" value="0" />
            </div>
        </div>
    <?php endif; ?>


</form>

<?php
do_action('woocommerce_after_add_to_cart_form');