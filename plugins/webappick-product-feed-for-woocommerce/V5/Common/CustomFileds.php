<?php

namespace CTXFeed\V5\Common;

use CTXFeed\V5\Helper\CustomFieldHelper;
use CTXFeed\V5\Utility\Settings;

class CustomFileds
{
    public function init()
    {
        add_action('woocommerce_product_options_inventory_product_data', [$this, 'set_custom_field'], 10, 3);
        add_action('woocommerce_product_after_variable_attributes', [
            $this,
            'set_custom_field_for_variation'
        ], 10, 3);
        add_action('save_post_product', [$this, 'save_custom_field_value'], 10, 2);
        add_action('woocommerce_save_product_variation', [$this, 'save_variation_custom_field_value'], 10, 2);
    }

    /**
     * Set Custom Fields for Product.
     *
     * @return void
     */
    public function set_custom_field()
    {

        $custom_field_settings = Settings::get('woo_feed_identifier');
        $custom_fields = CustomFieldHelper::get_fields();

        if (!empty($custom_field_settings) && in_array('enable', $custom_field_settings, true)) {
            echo '<div class="options_group">';
            echo sprintf('<h4 class="%s" style="padding-left: 10px;color: black;">%s</h4>', esc_attr('woo-feed-option-title'), esc_html__('CUSTOM FIELDS by CTX Feed', 'woo-feed'));
            foreach ($custom_fields as $field_Key => $custom_field) {
                if (isset($custom_field_settings[$field_Key]) && 'enable' === $custom_field_settings[$field_Key] && in_array($custom_field[2], ['text', 'date'])) {

                    //identifier meta value for old and new version users
                    $custom_field_key_previous = sprintf('woo_feed_identifier_%s', strtolower($field_Key));
                    $custom_field_value_previous = get_post_meta(get_the_ID(), $custom_field_key_previous, true);

                    $custom_field_key = sprintf('woo_feed_%s', strtolower($field_Key));
                    $custom_field_value = get_post_meta(get_the_ID(), $custom_field_key, true);

                    if (empty($custom_field_value) && !empty($custom_field_value_previous)) {
                        $custom_field_key = $custom_field_key_previous;
                        $custom_field_value = $custom_field_value_previous;
                    }

                    $custom_field_id = esc_attr(wp_unslash("woo_feed_$field_Key"));
                    $custom_field_label = esc_attr(wp_unslash($custom_field[0]));
                    $custom_field_description = __('Set product ', 'woo-feed') . esc_html($custom_field_label) . __(' here.', 'woo-feed');

                    if (strpos($custom_field_id, 'availability_date')) {
                        woocommerce_wp_text_input(
                            array(
                                'id' => "woo_feed_availability_date",
                                'name' => "woo_feed_availability_date",
                                'placeholder' => '',
                                'label' => __('Availability Date', 'woo-feed'),
                                'type' => 'date',
                                'value' => $custom_field_value,
                                'desc_tip' => false,
                                'description' => __('Set availability date for backorder products.', 'woo-feed'),
                            )
                        );
                    } else {
                        woocommerce_wp_text_input(
                            array(
                                'id' => $custom_field_id,
                                'label' => $custom_field_label,
                                'placeholder' => $custom_field_label,
                                'type' => $custom_field[2],
                                'value' => esc_attr(wp_unslash($custom_field_value)),
                                'desc_tip' => true,
                                'description' => $custom_field_description,
                            )
                        );
                    }

                }
            }
            echo '</div>';
        }
    }

    /**
     * Set Custom Fields for Product Variations.
     *
     * @param $loop
     * @param $variation_data
     * @param $variation
     *
     * @return void
     */
    public function set_custom_field_for_variation($loop, $variation_data, $variation)
    {

        $custom_field_settings = Settings::get('woo_feed_identifier');
        $custom_fields = CustomFieldHelper::get_fields();

        if (!empty($custom_field_settings) && in_array('enable', $custom_field_settings, true)) {
            echo '<div class="woo-feed-variation-options">';

            if (!empty($custom_fields)) {
                echo '<div class="woo-feed-variation-options">';
                echo "<hr>";
                echo sprintf('<h4 class="%s">%s</h4>', esc_attr('woo-feed-variation-option-title'), esc_html__('CUSTOM FIELDS by CTX Feed', 'woo-feed'));
                echo "<hr>";
                echo '<div class="woo-feed-variation-items">';

                foreach ($custom_fields as $field_Key => $custom_field) {
                    if (isset($custom_field_settings[$field_Key]) && 'enable' === $custom_field_settings[$field_Key] && in_array($custom_field[2], ['text', 'date'])) {
                        $custom_field_id = sprintf('woo_feed_%s_var[%d]', strtolower($field_Key), $variation->ID);
                        $custom_field_label = isset($custom_field[0]) ? $custom_field[0] : '';
                        $custom_field_description = sprintf('Set Variation %s here.', $custom_field_label);

                        //identifier meta value for old and new version users
                        if (metadata_exists('post', $variation->ID, 'woo_feed_' . strtolower($field_Key) . '_var')) {
                            $custom_field_key = sprintf('woo_feed_%s_var', strtolower($field_Key));
                        } else {
                            $custom_field_key = sprintf('woo_feed_identifier_%s_var', strtolower($field_Key));
                        }

                        $custom_field_value = esc_attr(get_post_meta($variation->ID, $custom_field_key, true));

                        if (strpos($custom_field_id, 'availability_date')) {
                            woocommerce_wp_text_input(
                                array(
                                    'id' => "woo_feed_availability_date_var{$loop}",
                                    'name' => "woo_feed_availability_date_var[{$loop}]",
                                    'placeholder' => '',
                                    'label' => __('Availability Date', 'woo-feed'),
                                    'type' => 'date',
                                    'desc_tip' => true,
                                    'description' => __('Set availability date for backorder products.', 'woo-feed'),
                                    'value' => esc_attr($custom_field_value),
                                    'wrapper_class' => 'form-row form-row-full',
                                )
                            );
                        } else {
                            woocommerce_wp_text_input(
                                array(
                                    'id' => $custom_field_id,
                                    'value' => $custom_field_value,
                                    'placeholder' => esc_html($custom_field_label),
                                    'label' => esc_html($custom_field_label),
                                    'desc_tip' => true,
                                    'description' => esc_html($custom_field_description),
                                    'wrapper_class' => 'form-row form-row-full',
                                )
                            );
                        }
                    }
                }
                echo '</div></div>';
            }
            echo "<hr>";
            echo '</div>';
        }
    }

    /**
     * Save Product Custom Field Value.
     *
     * @param int $post_id Product id.
     *
     * @return void
     */
    public function save_custom_field_value($post_id)
    {

        $custom_fields = CustomFieldHelper::get_fields();
        $set_meta_val = '';

        if (!empty($custom_fields)) {
            foreach ($custom_fields as $key => $custom_field) {
                $product_meta_key = "woo_feed_$key";

                $new_meta_key = "woo_feed_identifier_$key";
                $new_meta_val = get_post_meta($post_id, $new_meta_key, true);
                $old_meta_val = get_post_meta($post_id, $product_meta_key, true);

                if (!empty($old_meta_val)) {
                    $set_meta_val = $old_meta_val;
                } else {
                    $set_meta_val = $new_meta_val;
                }

                if (isset($_POST[$product_meta_key])) {
                    $product_meta_value = sanitize_text_field($_POST[$product_meta_key]);
                } elseif (isset($_POST[$new_meta_key])) {
                    $product_meta_value = sanitize_text_field($_POST[$new_meta_key]);
                } else {
                    $product_meta_value = $set_meta_val;
                }

                if (isset($product_meta_value) && !empty($product_meta_value)) {
                    update_post_meta($post_id, $product_meta_key, $product_meta_value);
                } else {
                    delete_post_meta($post_id, $product_meta_key);
                }
            }
        }
    }

    /**
     * Save Product Variation Custom Field Value.
     *
     * @param int $post_id Variation id.
     *
     * @return void
     */
    public function save_variation_custom_field_value($post_id, $loop)
    {

        $custom_fields = woo_feed_product_custom_fields();

        if (!empty($custom_fields)) {
            foreach ($custom_fields as $key => $value) {

                $product_meta_key = "woo_feed_{$key}_var";

                $new_meta_key = "woo_feed_identifier_{$key}_var";
                $new_meta_val = get_post_meta($post_id, $new_meta_key, true);
                $old_meta_val = get_post_meta($post_id, $product_meta_key, true);

                if (!empty($old_meta_val)) {
                    $set_meta_val = $old_meta_val;
                } else {
                    $set_meta_val = $new_meta_val;
                }

                if (isset($_POST[$product_meta_key][$post_id])) {
                    $product_meta_value = sanitize_text_field($_POST[$product_meta_key][$post_id]);
                } elseif (isset($_POST[$product_meta_key][$loop])) {
                    $product_meta_value = sanitize_text_field($_POST[$product_meta_key][$loop]);
                } elseif (isset($_POST[$new_meta_key] [$post_id])) {
                    $product_meta_value = sanitize_text_field($_POST[$new_meta_key][$post_id]);
                } elseif (isset($_POST[$new_meta_key] [$loop])) {
                    $product_meta_value = sanitize_text_field($_POST[$new_meta_key][$loop]);
                } else {
                    $product_meta_value = $set_meta_val;
                }


                if (isset($product_meta_value) && !empty($product_meta_value)) {
                    update_post_meta($post_id, $product_meta_key, $product_meta_value);
                }
            }
        }
    }
}
