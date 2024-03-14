<?php

namespace UltimateStoreKit\Includes\Builder;

class Builder_Template_Helper {

    public static function isTemplateEditMode() {
        if (get_post_type() == Meta::POST_TYPE) {
            return true;
        }

        if (isset($_REQUEST[Meta::POST_TYPE])) {
            return true;
        }
    }

    public static function separator() {
        return '|';
    }

    public static function templates($single = false) {
        $shopItem = [
            'shop'          => 'Shop Page',
            'archive'       => 'Archive Page',
            'single'        => 'Single Page',
            'cart'          => 'Cart Page',
            'checkout'      => 'Checkout',
            'myaccount'     => 'My Account',
            // 'wishlist'     => 'Wishlist',
        ];

        if ($wcItems = WC()->query->get_query_vars()) {
            array_walk($wcItems, function ($item, $key) use (&$shopItem) {
                $shopItem[$key] = ucwords(str_replace('-', ' ', $key));
            });
        }

        $product = [
            'product' => $shopItem,
        ];

        $templates = apply_filters(
            'ultimate_store_kit_builder_templates',
            $product
        );

        if ($single) {
            $separator = static::separator();
            $return = [];
            if (is_array($templates) && !empty($templates)) {
                foreach ($templates as $keys => $items) {
                    if (is_array($items)) {
                        foreach ($items as $itemKey => $item) {
                            $return["{$keys}{$separator}{$itemKey}"] = $item;
                        }
                    }
                }
            }

            return apply_filters(
                'ultimate_store_kit_builder_all_templates',
                $return
            );
        }

        return $templates;
    }

    public static function templateForSelectDropdown() {
        return static::templates();
    }

    public static function getTemplateByIndex($index) {
        $index     = trim($index);
        $templates = static::templates(true);

        return array_key_exists($index, $templates) ? $templates[$index] : false;
    }

    public static function getTemplatePostTypeByIndex($index) {
        $index     = trim($index);
        if ($item = explode(static::separator(), $index)) {
            return get_post_type_object($item[0]);
        }
    }

    public static function is_elementor_active() {
        return did_action('elementor/loaded');
    }

    public static function getTemplate($slug, $postType = false) {
        if (!$postType) {
            $postType = get_post_type();
        }
        $separator = static::separator();
        $template = strtolower("{$postType}{$separator}{$slug}");
        $enabledTemplate = strtolower(Meta::TEMPLATE_ID . $template);

        return get_option($enabledTemplate);
    }

    public static function getTemplateId($templateType) {
        $metaIndex = strtolower(Meta::TEMPLATE_ID . $templateType);
        return intval(get_option($metaIndex));
    }
}
