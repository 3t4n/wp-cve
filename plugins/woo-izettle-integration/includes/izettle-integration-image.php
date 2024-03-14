<?php

/**
 * This class handles how the image is populated in iZettle
 *
 * @package   WooCommerce_iZettle_Integration
 * @author    BjornTech <info@bjorntech.com>
 * @license   GPL-3.0
 * @link      http://bjorntech.com
 * @copyright 2017-2019 BjornTech - BjornTech AB
 */

defined('ABSPATH') || exit;

if (!class_exists('WC_iZettle_Integration_Image', false)) {

    class WC_iZettle_Integration_Image
    {

        public $image_size;

        public function __construct()
        {
            add_filter('izettle_get_image_keys', array($this, 'get_image_keys'), 10, 3);

            if (get_option('izettle_variation_images') == 'yes') {
                add_filter('izettle_get_image', array($this, 'get_image'), 10, 3);
            }

            $this->image_size = get_option('izettle_image_size');
            if (!$this->image_size) {
                $this->image_size = array(2000, 2000);
            }
        }

        public function get_thumbnail_id($product)
        {
            $thumbnail_id = $product->get_image_id();

            if (!$thumbnail_id && ($parent_id = $product->get_parent_id()) && ($parent = wc_get_product($parent_id))) {
                WC_IZ()->logger->add(sprintf('get_image_keys (%s): Thumbnail missing, using image from %s (parent)', $product->get_id(), $parent_id));
                $thumbnail_id = $parent->get_image_id();
            }

            return $thumbnail_id;
        }

        /**
         * Get the image selected for iZettle and upload it, use the saved one if there is one
         * If there is no image on the product, try with the parent
         */
        public function get_image_keys($image_lookup_keys, $product, $sync_all)
        {

            $product_id = $product->get_id();

            $izettle_image_key = reset($image_lookup_keys);

            if (!$izettle_image_key || !WC_Zettle_Helper::is_image_in_meta($product, $izettle_image_key) || ($sync_all && 'yes' == get_option('izettle_always_upload_image'))) {

                $thumbnail_id = $this->get_thumbnail_id($product);

                $imageURL = wp_get_attachment_image_src($thumbnail_id, $this->image_size);

                if ($imageURL !== false) {

                    $imageformat = 'JPEG';

                    if ($imageMime = get_post_mime_type($thumbnail_id)) {

                        $imageformat_array = explode("/", $imageMime);
                        if (sizeof($imageformat_array) > 1) {
                            $imageformat = strtoupper($imageformat_array[1]);
                        }

                    }

                    $is_webp = false;

                    if ($imageformat == 'WEBP') {
                        $is_webp = true;
                    } 

                    WC_IZ()->logger->add(sprintf('get_image_keys (%s): Image name %s with image format %s', $product_id, $imageURL[0], $imageformat));
                    $product_image = array();

                    try {

                        $product_image = izettle_api()->create_image($imageURL[0], null, $imageformat);
                        $image_lookup_keys = array($product_image->imageLookupKey);

                        update_post_meta($product_id, '_izettle_image_lookup_key', $product_image->imageLookupKey);

                        WC_IZ()->logger->add(sprintf('get_image_keys (%s): Image %s sent as link to Zettle successfully', $product_id, $imageURL[0]));

                    } catch (IZ_Integration_API_Exception $e) {

                        WC_IZ()->logger->add(sprintf('get_image_keys (%s): Setting image as link failed %s', $product_id, $e->getMessage()), true);

                        try {

                            $uploads = wp_upload_dir();

                            $image_path = $imageURL[0];

                            if (strpos($imageURL[0], '?') !== false) {
                                $image_path = substr($image_path, 0, strrpos($image_path, '?'));
                            }

                            WC_IZ()->logger->add(sprintf('get_image_keys (%s): Image path %s', $product_id, $image_path));
                            
                            $image_path = str_replace($uploads['baseurl'], $uploads['basedir'], $image_path);
                            $image_path = str_replace('\\', '/', $image_path);
                            $file_data = file_get_contents($image_path);

                            if ($file_data !== false) {

                                $imageformat = getimagesize($image_path)[2];

                                WC_IZ()->logger->add(sprintf('get_image_keys (%s): Image format %s', $product_id, $imageformat));

                                if ($is_webp && $imageformat === IMAGETYPE_WEBP) {
                                    WC_IZ()->logger->add(sprintf('get_image_keys (%s): Converting WEBP image %s to PNG', $product_id, $image_path));
                                    $file_data = $this->image_data($image_path);
                                    $imageformat = 'PNG';
                                } else {
                                    WC_IZ()->logger->add(sprintf('get_image_keys (%s): Image %s is not a WEBP image', $product_id, $image_path));
                                }

                                $contents = base64_encode($file_data);
                                $product_image = izettle_api()->create_image(null, $contents, $imageformat);

                                $image_lookup_keys = array($product_image->imageLookupKey);

                                update_post_meta($product_id, '_izettle_image_lookup_key', $product_image->imageLookupKey);
                                WC_IZ()->logger->add(sprintf('get_image_keys (%s): Image lookup key %s created by byte_array in Zettle successfully', $product_id, $product_image->imageLookupKey));

                            }

                        } catch (IZ_Integration_API_Exception $e) {

                            WC_IZ()->logger->add(sprintf('get_image_keys (%s): Setting image as byte array failed %s', $product_id, $e->getMessage()), true);

                            WC_IZ()->logger->add(sprintf('get_image_keys (%s): Image %s could not be uploaded to Zettle', $product_id, $imageURL[0]), true);

                        }

                    }

                } else {

                    WC_IZ()->logger->add(sprintf('get_image_keys (%s): No image present in WooCommerce', $product_id));
                    $image_lookup_keys = array();
                }

            } else {

                WC_IZ()->logger->add(sprintf('get_image_keys (%s): Image %s already in Zettle', $product_id, $izettle_image_key));

            }

            return $image_lookup_keys;

        }

        function image_data($image_path){
            $im = imagecreatefromwebp($image_path);

            $im = imagescale($im, 2000, 2000);

            ob_start();
            imagepng($im);
            return(ob_get_clean());
        }

        public function update_product($product_id, $thumbnail_id, $image_data)
        {
            $image_data->{"id"} = $thumbnail_id;
            update_post_meta($product_id, '_izettle_image', $image_data);
        }

        public function return_image_url($image_data, $type)
        {
            if ($image_data) {
                if ('k' == $type) {
                    return $image_data->imageLookupKey;
                } else {
                    WC_IZ()->logger->add(sprintf('Using %s as image', $image_data->imageUrls[0]));
                    return $image_data->imageUrls[0];
                }
            }
            return false;
        }

        public function get_image($image_data, $product_id, $type = '')
        {

            WC_IZ()->logger->add(sprintf('Starting processing of image for WooCommerce product %s', $product_id));

            $image_data = get_post_meta($product_id, '_izettle_image', true);

            $product = wc_get_product($product_id);

            $thumbnail_id = $product->get_image_id();

            if (!$image_data || ($thumbnail_id != $image_data->id)) {

                $imageURL = wp_get_attachment_image_src($thumbnail_id, $this->image_size);

                if ($imageURL !== false) {
                    $imageformat = 'JPEG';
                    $imageMime = get_post_mime_type($thumbnail_id);
                    if ($imageMime) {
                        $imageformat_array = explode("/", $imageMime);
                        if (sizeof($imageformat_array) > 1) {
                            $imageformat = strtoupper($imageformat_array[1]);
                        }
                    }
                    WC_IZ()->logger->add(sprintf('WooCommerce image %s is a %s-imsge', $imageURL[0], $imageformat));

                    try {
                        $image_data = izettle_api()->create_image($imageURL[0], null, $imageformat);
                        $this->update_product($product_id, $thumbnail_id, $image_data);
                        WC_IZ()->logger->add(sprintf('Image %s sent as link to Zettle successfully', $imageURL[0]));
                    } catch (IZ_Integration_API_Exception $e) {
                        WC_IZ()->logger->add(sprintf('Sending image as link failed %s with message: ', $e->getMessage()));
                        try {
                            $uploads = wp_upload_dir();
                            $image_path = str_replace($uploads['baseurl'], $uploads['basedir'], $imageURL[0]);
                            $image_path = str_replace('\\', '/', $image_path);
                            $file_data = file_get_contents($image_path);

                            if ($file_data !== false) {
                                $contents = base64_encode($file_data);
                                $image_data = izettle_api()->create_image(null, $contents, $imageformat);
                                $this->update_product($product_id, $thumbnail_id, $image_data);
                                WC_IZ()->logger->add(sprintf('Image %s created via byte_array to Zettle successfully', $imageURL[0]));
                            } else {
                                WC_IZ()->logger->add(sprintf('Failed to read data from WooCommerce image %s', $imageURL[0]));
                            }
                        } catch (IZ_Integration_API_Exception $e) {
                            WC_IZ()->logger->add(sprintf('Setting image as byte array for product %s failed %s', $product_id, $e->getMessage()));
                        }
                    }
                } else {
                    WC_IZ()->logger->add(sprintf('No image present for WooCommerce product %s', $product_id));

                }
            } else {
                WC_IZ()->logger->add(sprintf('The image for product %s has not changed since last upload', $product_id));
            }

            return $this->return_image_url($image_data, $type);

        }
    }

    new WC_iZettle_Integration_Image();
}
