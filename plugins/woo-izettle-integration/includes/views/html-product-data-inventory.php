<?php
/**
 * Adds iZettle specific fields.
 *
 * @package WooCommerce\Admin
 */

if (!defined('ABSPATH')) {
    exit;
}
echo '<div id="izettle_product_data" class="panel woocommerce_options_panel">';

woocommerce_wp_checkbox(
    array(
        'id' => "_izettle_nosync",
        'value' => $product_object->get_meta('_izettle_nosync', true),
        'label' => '<abbr title="' . esc_attr__('Exclude from Zettle', 'woo-izettle-integration') . '">' . esc_html__('Exclude from Zettle', 'woo-izettle-integration') . '</abbr>',
        'desc_tip' => true,
        'description' => __('Check if you do not want this product to be synced to Zettle', 'woo-izettle-integration'),
    )
);

woocommerce_wp_text_input(
    array(
        'id' => '_izettle_product_name',
        'value' => $product_object->get_meta('_izettle_product_name', true),
        'label' => '<abbr title="' . esc_attr__('Product name', 'woo-izettle-integration') . '">' . esc_html__('Product name', 'woo-izettle-integration') . '</abbr>',
        'desc_tip' => true,
        'description' => __('Product name to be shown in Zettle', 'woo-izettle-integration'),
    )
);

woocommerce_wp_select(
    array(
        'id' => '_zettle_product_cat_id',
        'value' => $product_object->get_meta('_zettle_product_cat_id', true),
        'label' => '<abbr title="' . esc_attr__('Category', 'woo-izettle-integration') . '">' . esc_html__('Category', 'woo-izettle-integration') . '</abbr>',
        'options' => WC_Zettle_Helper::get_categories($product_object),
        'desc_tip' => true,
        'description' => __('Category to set on the Zettle product', 'woo-izettle-integration'),
    )
);

if ($product_object->is_type('simple')) {
    if ('ean13_manual' == get_option('izettle_product_barcode_generate')) {
        WC_Zettle_Helper::izettle_wp_button_input(
            array(
                'id' => '_izettle_barcode',
                'value' => $product_object->get_meta('_izettle_barcode', true),
                'label' => '<abbr title="' . esc_attr__('Barcode', 'woo-izettle-integration') . '">' . esc_html__('Barcode', 'woo-izettle-integration') . '</abbr>',
                'desc_tip' => true,
                'button_text' => esc_html__('Generate', 'woo-izettle-integration'),
                'button_class' => 'izettle_generate_barcode',
                'description' => __('Barcode used by Zettle.', 'woo-izettle-integration'),
                'button_name' => $product_object->get_id(),
            )
        );
    } else {
        woocommerce_wp_text_input(
            array(
                'id' => '_izettle_barcode',
                'value' => $product_object->get_meta('_izettle_barcode', true),
                'label' => '<abbr title="' . esc_attr__('Barcode', 'woo-izettle-integration') . '">' . esc_html__('Barcode', 'woo-izettle-integration') . '</abbr>',
                'desc_tip' => true,
                'description' => __('Barcode used by Zettle.', 'woo-izettle-integration'),
            )
        );
    }

    woocommerce_wp_text_input(
        array(
            'id' => '_izettle_special_price',
            'value' => $product_object->get_meta('_izettle_special_price', true),
            'label' => '<abbr title="' . esc_attr__('Price', 'woo-izettle-integration') . '">' . esc_html__('Price', 'woo-izettle-integration') . '</abbr>',
            'desc_tip' => true,
            'data_type' => 'price',
            'description' => __('Price for the product.', 'woo-izettle-integration'),
        )
    );

    woocommerce_wp_text_input(
        array(
            'id' => '_izettle_cost_price',
            'value' => $product_object->get_meta('_izettle_cost_price', true),
            'label' => '<abbr title="' . esc_attr__('Cost price', 'woo-izettle-integration') . '">' . esc_html__('Cost price', 'woo-izettle-integration') . '</abbr>',
            'desc_tip' => true,
            'data_type' => 'price',
            'description' => __('Cost price for the product.', 'woo-izettle-integration'),
        )
    );
}

if ('yes' == get_option('zettle_enable_uuid_edit')) {
    woocommerce_wp_text_input(
        array(
            'id' => '_zettle_product_uuid',
            'value' => $product_object->get_meta('woocommerce_izettle_product_uuid', true),
            'label' => '<abbr title="' . esc_attr__('Product UUID', 'woo-izettle-integration') . '">' . esc_html__('Product UUID', 'woo-izettle-integration') . '</abbr>',
            'desc_tip' => true,
            'description' => __('Zettle Product UUID', 'woo-izettle-integration'),
        )
    );
    if ($product_object->is_type('simple')) {
        woocommerce_wp_text_input(
            array(
                'id' => '_zettle_variant_uuid',
                'value' => $product_object->get_meta('woocommerce_izettle_variant_uuid', true),
                'label' => '<abbr title="' . esc_attr__('Variant UUID', 'woo-izettle-integration') . '">' . esc_html__('Variant UUID', 'woo-izettle-integration') . '</abbr>',
                'desc_tip' => true,
                'description' => __('Zettle Variant UUID', 'woo-izettle-integration'),
            )
        );
    }
}

echo '</div>';
