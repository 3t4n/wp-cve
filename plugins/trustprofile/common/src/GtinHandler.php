<?php

namespace Valued\WordPress;

class GtinHandler {
    const ATTRIBUTE_PREFIX = 'custom_attribute';

    const META_PREFIX = 'meta_key';

    private $product;

    private $gtin_meta_key;

    public function setProduct(\WC_Product $product) {
        $this->product = $product;
    }

    public function setGtinMetaKey(string $gtin_meta_key) {
        $this->gtin_meta_key = $gtin_meta_key;
    }

    public function getActivePlugin() {
        foreach ($this->getSupportedPlugins() as $plugin_name => $callback) {
            if (is_plugin_active($plugin_name)) {
                return $plugin_name;
            }
        }
        return null;
    }

    private function getSupportedPlugins(): array {
        return [
            'woosa-vandermeer/woosa-vandermeer.php' =>
                $this->getFromMeta('vdm_ean'),
            'woocommerce-product-feeds/woocommerce-gpf.php' =>
                function () {
                    if (function_exists('woocommerce_gpf_show_element')) {
                        return (string) woocommerce_gpf_show_element('gtin', $this->product->post) ?: null;
                    }
                },
            'customer-reviews-woocommerce/ivole.php' =>
                $this->getFromMeta('_cr_gtin'),
            'product-gtin-ean-upc-isbn-for-woocommerce/product-gtin-ean-upc-isbn-for-woocommerce.php' =>
                $this->getFromMeta('_wpm_gtin_code', '_wpm_ean_code'),
            'woo-product-feed-pro/woocommerce-sea.php' =>
                $this->getFromMeta('_woosea_gtin', '_woosea_ean'),
            'wpseo-woocommerce/wpseo-woocommerce.php' =>
                function () {
                    $meta_values = get_post_meta($this->product->get_id(), 'wpseo_global_identifier_values')[0] ?? null;
                    foreach ([
                        'gtin13',
                        'gtin8',
                        'gtin12',
                        'gtin14',
                        'isbn',
                    ] as $gtin_key) {
                        if (!empty($meta_values[$gtin_key])) {
                            return (string) $meta_values[$gtin_key];
                        }
                    }
                    return null;
                },
        ];
    }

    public function getGtin(string $custom_gtin_key = null) {
        if (!empty($custom_gtin_key)) {
            return $this->getGtinFromKey($custom_gtin_key);
        }
        foreach ($this->getSupportedPlugins() as $plugin_name => $callback) {
            if (is_plugin_active($plugin_name)) {
                return $callback();
            }
        }
        return $this->getGtinFromMeta($this->gtin_meta_key);
    }

    private function getFromMeta(...$keys) {
        return function () use ($keys) {
            foreach ($keys as $key) {
                if ($result = $this->getGtinFromMeta($key)) {
                    return $result;
                }
            }
            return null;
        };
    }

    private function getGtinFromMeta(string $key) {
        return (string) get_post_meta($this->product->get_id(), $key, true) ?: null;
    }

    private function getGtinFromKey(string $custom_gtin_key) {
        if (strpos($custom_gtin_key, self::ATTRIBUTE_PREFIX) === 0) {
            return $this->product->get_attribute(
                substr($custom_gtin_key, strlen(self::ATTRIBUTE_PREFIX))
            );
        }
        if (strpos($custom_gtin_key, self::META_PREFIX) === 0) {
            return $this->getGtinFromMeta(
                substr($custom_gtin_key, strlen(self::META_PREFIX))
            );
        }
        return null;
    }
}
