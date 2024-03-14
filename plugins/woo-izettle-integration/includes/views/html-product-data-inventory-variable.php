<?php
/**
 * Adds a barcode-field.
 *
 * @package WooCommerce\Admin
 */

if (!defined('ABSPATH')) {
    exit;
}

echo '<div>';
echo '<p>';
woocommerce_wp_checkbox(
    array(
        'id' => "_izettle_nosync_{$loop}",
        'value' => get_post_meta($variation->ID, '_izettle_nosync', true),
        'label' => '<abbr title="' . esc_attr__('Exclude from Zettle', 'woo-izettle-integration') . '">' . esc_html__('Exclude from Zettle', 'woo-izettle-integration') . '</abbr>',
        'desc_tip' => true,
        'description' => __('Check if you do not want this product to be synced to Zettle', 'woo-izettle-integration'),
    )
);

if ('ean13_manual' == get_option('izettle_product_barcode_generate')) {
    WC_Zettle_Helper::izettle_wp_button_input(
        array(
            'id' => "_izettle_barcode_{$loop}",
            'value' => get_post_meta($variation->ID, '_izettle_barcode', true),
            'label' => '<abbr title="' . esc_attr__('Barcode', 'woo-izettle-integration') . '">' . esc_html__('Barcode', 'woo-izettle-integration') . '</abbr>',
            'desc_tip' => true,
            'button_text' => esc_html__('Generate', 'woo-izettle-integration'),
            'button_class' => 'izettle_generate_barcode',
            'description' => __('Barcode used by Zettle.', 'woo-izettle-integration'),
            'button_name' => $variation->ID,
        )
    );
} else {
    woocommerce_wp_text_input(
        array(
            'id' => "_izettle_barcode_{$loop}",
            'value' => get_post_meta($variation->ID, '_izettle_barcode', true),
            'label' => '<abbr title="' . esc_attr__('Barcode', 'woo-izettle-integration') . '">' . esc_html__('Barcode', 'woo-izettle-integration') . '</abbr>',
            'desc_tip' => true,
            'description' => __('Barcode used by Zettle.', 'woo-izettle-integration'),
        )
    );
}

woocommerce_wp_text_input(
    array(
        'id' => "_izettle_price_{$loop}",
        'value' => get_post_meta($variation->ID, '_izettle_special_price', true),
        'label' => '<abbr title="' . esc_attr__('Price', 'woo-izettle-integration') . '">' . esc_html__('Price', 'woo-izettle-integration') . '</abbr>',
        'desc_tip' => true,
        'data_type' => 'price',
        'description' => __('Price for the product.', 'woo-izettle-integration'),
    )
);
woocommerce_wp_text_input(
    array(
        'id' => "_izettle_cost_price_{$loop}",
        'value' => get_post_meta($variation->ID, '_izettle_cost_price', true),
        'label' => '<abbr title="' . esc_attr__('Cost price', 'woo-izettle-integration') . '">' . esc_html__('Cost price', 'woo-izettle-integration') . '</abbr>',
        'desc_tip' => true,
        'data_type' => 'price',
        'description' => __('Cost price for the product.', 'woo-izettle-integration'),
    )
);

if ('yes' == get_option('zettle_enable_uuid_edit')) {
    woocommerce_wp_text_input(
        array(
            'id' => "_zettle_product_uuid_{$loop}",
            'value' => get_post_meta($variation->ID, 'woocommerce_izettle_product_uuid', true),
            'label' => '<abbr title="' . esc_attr__('Product UUID', 'woo-izettle-integration') . '">' . esc_html__('Product UUID', 'woo-izettle-integration') . '</abbr>',
            'desc_tip' => true,
            'description' => __('Product UUID used by Zettle.', 'woo-izettle-integration'),
        )
    );
    woocommerce_wp_text_input(
        array(
            'id' => "_zettle_variant_uuid_{$loop}",
            'value' => get_post_meta($variation->ID, 'woocommerce_izettle_variant_uuid', true),
            'label' => '<abbr title="' . esc_attr__('Variant UUID', 'woo-izettle-integration') . '">' . esc_html__('Variant UUID', 'woo-izettle-integration') . '</abbr>',
            'desc_tip' => true,
            'description' => __('Variant UUID used by Zettle.', 'woo-izettle-integration'),
        )
    );
}

echo '</p>';
echo '</div>';
