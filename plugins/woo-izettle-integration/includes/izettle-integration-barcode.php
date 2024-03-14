<?php

/**
 * This class handles how the barcode-field is populated in iZettle
 *
 * @package   WooCommerce_iZettle_Integration
 * @author    BjornTech <info@bjorntech.com>
 * @license   GPL-3.0
 * @link      http://bjorntech.com
 * @copyright 2017-2019 BjornTech - BjornTech AB
 */

defined('ABSPATH') || exit;

if (!class_exists('WC_iZettle_Integration_Barcode', false)) {

    class WC_iZettle_Integration_Barcode
    {

        /**
         * Add filters for barcode handling
         */
        public function __construct()
        {

            $update_barcode = get_option('izettle_product_update_barcode');

            add_filter('izettle_process_barcode', array($this, 'process_barcode' . $update_barcode), 10, 2);
            add_filter('izettle_barcode_ean13_manual', array($this, 'generate_ean13'), 10, 2);

            if(get_option('izettle_import_barcode') == '_custom_barcode'){
                add_filter('izettle_set_barcode_meta', array($this, 'process_zettle_barcode'), 10, 1);
            }

            if ('ean13_automatic' == get_option('izettle_product_barcode_generate')) {
                add_filter('izettle_barcode_ean13_automatic', array($this, 'generate_ean13'), 10, 2);
            }

        }

        /**
         * Calculate the check-digit on the EAN-13 barcode
         */
        public function ean13_check_digit($digits)
        {
            $digits = (string) $digits;
            $even_sum = $digits[1] + $digits[3] + $digits[5] + $digits[7] + $digits[9] + $digits[11];
            $even_sum_three = $even_sum * 3;
            $odd_sum = $digits[0] + $digits[2] + $digits[4] + $digits[6] + $digits[8] + $digits[10];
            $total_sum = $even_sum_three + $odd_sum;
            $next_ten = (ceil($total_sum / 10)) * 10;
            $check_digit = $next_ten - $total_sum;
            return $digits . $check_digit;
        }

        public function process_zettle_barcode($barcode_meta){
            if($custom_barcode_meta = get_option('izettle_import_barcode_meta')){
                $barcode_meta = $custom_barcode_meta;
            }

            return $barcode_meta;
        }

        /**
         * Popolate the iZettle barcode-field with a generated EAN-13 barcode if both barcode fields are empty
         */
        public function generate_ean13($barcode, $product_id, $force = false)
        {

            if (!$barcode || $force) {

                $barcode_identifier = get_option('izettle_product_barcode_identifier');
                if ('sku' == $barcode_identifier) {
                    $product = wc_get_product($product_id);
                    $identifier = $product->get_sku('edit');
                } else {
                    $identifier = $product_id;
                }

                if (is_numeric($identifier)) {
                    $barcode_country = get_option('izettle_product_barcode_country');
                    $barcode_company = get_option('izettle_product_barcode_company');
                    if ($barcode_company != '') {
                        if ((strlen($barcode_country) > 3) && (strlen($barcode_country) > 5) && (strlen($barcode_country) > 4)) {
                            $barcode = $this->ean13_check_digit(sprintf('%03s%05s%04s', $barcode_country, $barcode_company, $identifier));
                            WC_IZ()->logger->add(sprintf('generate_ean13 (%s): New barcode with company %s created', $product_id, $barcode));
                        } else {
                            WC_IZ()->logger->add(sprintf('generate_ean13 (%s): Parameters for creating barcode wrong: country-code=%s company_code=%s identifier=%s', $product_id, $barcode_country, $barcode_company, $identifier));
                        }
                    } else {
                        $barcode = $this->ean13_check_digit(sprintf('%03s%09s', $barcode_country, $identifier));
                        WC_IZ()->logger->add(sprintf('generate_ean13 (%s): New barcode %s created', $product_id, $barcode));
                    }
                } else {
                    WC_IZ()->logger->add(sprintf('generate_ean13 (%s): Barcode identifier %s is not numeric', $product_id, $identifier));
                }

            }

            return (string) $barcode;
        }

        /**
         * Populate the iZettle barcode-field with the barcode from the iZettle-barcode field at the product or variation
         * If the field is empty an EAN13 barcode is generated
         *
         * @since unknown
         *
         * @param string $barcode The current barcode
         * @param string/integer $product_id Product id for the WooCommerce product or product variation
         */
        public function process_barcode_barcode($iz_barcode, $product_id)
        {
            $barcode = get_post_meta($product_id, '_izettle_barcode', true);

            if (!$barcode) {
                $barcode = apply_filters('izettle_barcode_ean13_automatic', $barcode, $product_id);
                if ($barcode) {
                    update_post_meta($product_id, '_izettle_barcode', $barcode);
                }
            }

            if ($barcode != $iz_barcode) {
                WC_IZ()->logger->add(sprintf('process_barcode_barcode (%s): Changing barcode from "%s" to "%s"', $product_id, $iz_barcode, $barcode));
            }
            return $barcode;
        }

        public function process_barcode_yoast($barcode, $product_id)
        {
            // Get the product
            $product = wc_get_product($product_id);

            // Check if the product is a variation
            if ($product->is_type('variation')) {
                // Get the new barcode from the variation meta value
                $identifiers = $product->get_meta('wpseo_variation_global_identifiers_values');
            } else {
                // Get the new barcode from the product meta value
                $identifiers = $product->get_meta('wpseo_global_identifier_values');
            }

            // If the new barcode exists and is not empty, use it
            if (!empty($identifiers) && isset($identifiers['gtin13'])) {
                $new_barcode = $identifiers['gtin13'];
                WC_IZ()->logger->add(sprintf('process_barcode_yoast (%s): Changing barcode from %s to %s', $product_id, $barcode, $new_barcode));
                $barcode = $new_barcode;
            } else {
                WC_IZ()->logger->add(sprintf('process_barcode_yoast (%s): gtin13 value not found', $product_id));
            }

            return $barcode;
        }

        /**
         * Populate the iZettle barcode-field with the data from the WooCommerce sku field
         */
        public function process_barcode_sku($barcode, $product_id, $no_update = false)
        {
            $product = wc_get_product($product_id);
            if ($barcode != ($new_barcode = $product->get_sku('edit'))) {
                WC_IZ()->logger->add(sprintf('process_barcode_sku (%s): Changing barcode from %s to %s', $product_id, $barcode, $new_barcode));
                $barcode = $new_barcode;
            }
            return $barcode;
        }

        public function process_barcode_wpm_gtin_code($barcode, $product_id, $no_update = false)
        {
            if ($barcode != ($new_barcode = get_post_meta($product_id, '_wpm_gtin_code', true))) {
                WC_IZ()->logger->add(sprintf('process_barcode_wpm_gtin_code (%s): Changing barcode from %s to %s', $product_id, $barcode, $new_barcode));
                $barcode = $new_barcode;
            }
            return $barcode;
        }

        public function process_barcode_meta($barcode, $product_id, $no_update = false)
        {
            $barcode_meta = get_option('izettle_product_barcode_meta');
            if ($barcode_meta && $barcode != ($new_barcode = get_post_meta($product_id, $barcode_meta, true))) {
                WC_IZ()->logger->add(sprintf('process_barcode_meta_%s (%s): Changing barcode from %s to %s', $barcode_meta, $product_id, $barcode, $new_barcode));
                $barcode = $new_barcode;
            }
            return $barcode;
        }

        public function process_barcode($barcode, $product_id, $no_update = false)
        {
            return $barcode;
        }

    }

    new WC_iZettle_Integration_Barcode();
}
