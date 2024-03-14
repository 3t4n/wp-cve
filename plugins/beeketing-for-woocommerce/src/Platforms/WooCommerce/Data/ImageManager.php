<?php
/**
 * User: Quan Truong
 * Email: quan@beeketing.com
 * Date: 8/15/18
 */

namespace BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Data;


use BeeketingConnect_beeketing_woocommerce\Common\Data\Manager\ImageManagerAbstract;
use BeeketingConnect_beeketing_woocommerce\Common\Data\Model\Image;
use BeeketingConnect_beeketing_woocommerce\Platforms\WooCommerce\Helper;

class ImageManager extends ImageManagerAbstract
{
    /**
     * Get product images by WC product
     *
     * @param \WC_Product $product
     * @return array
     */
    public function getProductImagesByWCProduct($product)
    {
        $images = $attachmentIds = array();

        // Check wc version
        if (Helper::isWc3()) {
            $productId = $product->get_id();
            $galleryImageIds = $product->get_gallery_image_ids();
        } else {
            $productId = $product->id;
            $galleryImageIds = $product->get_gallery_attachment_ids();
        }

        // Add featured image
        if (has_post_thumbnail($productId)) {
            $attachmentIds[] = get_post_thumbnail_id($productId);
        }

        // Add gallery images
        $attachmentIds = array_merge($attachmentIds, $galleryImageIds);

        // Build image data
        foreach ($attachmentIds as $position => $attachmentId) {
            $attachmentPost = get_post($attachmentId);
            if (is_null($attachmentPost)) {
                continue;
            }

            $attachment = wp_get_attachment_image_src($attachmentId, 'medium');
            if (!is_array($attachment)) {
                continue;
            }

            // Update image src if use cdn image
            $imageSrc = current($attachment);
            if (preg_match_all('/http(s)?:\/\//', $imageSrc) > 1) {
                $imageSrc = preg_replace('/(http[s]?:\/\/.*)(http[s]?:\/\/)/', '$2', $imageSrc);
            }

            $image = new Image();
            $image->id = (int)$attachmentId;
            $image->src = $imageSrc;

            $images[] = $image;
        }

        // Set a placeholder image if the product has no images set
        if (empty($images)) {

            $image = new Image();
            $image->id = 0;
            $image->src = wc_placeholder_img_src();

            $images[] = $image;
        }

        return $images;
    }

}