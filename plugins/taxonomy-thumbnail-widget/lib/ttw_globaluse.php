<?php
defined('ABSPATH') or die('No script kiddies please!');
/**
 * Global function for get taxonomy thumbnail URL
 **/
if (!function_exists('ttw_thumbnail_url')):
    function ttw_thumbnail_url($ttwID = null, $size = 'full')
    {
        if (!$ttwID) {
            if (is_category())
                $ttwID = get_query_var('cat');
            elseif (is_tag())
                $ttwID = get_query_var('tag_id');
            elseif (is_tax()) {
                $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                $ttwID = $current_term->term_id;
            }
        }
        $taxonomyName = get_query_var('taxonomy');

        if (@$taxonomyName == 'product_cat') {
            $imageID = get_woocommerce_term_meta($ttwID, 'thumbnail_id', true);
        } else {
            $imageID = get_term_meta($ttwID, 'taxonomy_thumb_id', true);
        }

        $thumbnailSrc = wp_get_attachment_image_src($imageID, $size);
        $thumbnailSrc = ($thumbnailSrc == '') ? TTWTHUMB_URL : $thumbnailSrc[0];

        return $thumbnailSrc;
    }
endif;

/**
 * Global function for get taxonomy thumbnail image
 **/
if (!function_exists('ttw_thumbnail_image')):
    function ttw_thumbnail_image($ttwID = null, $size = 'full')
    {
        if (!$ttwID) {
            if (is_category())
                $ttwID = get_query_var('cat');
            elseif (is_tag())
                $ttwID = get_query_var('tag_id');
            elseif (is_tax()) {
                $current_term = get_term_by('slug', get_query_var('term'), get_query_var('taxonomy'));
                $ttwID = $current_term->term_id;
            }
        }
        $taxonomyName = get_query_var('taxonomy');
        if (@$taxonomyName == 'product_cat') {
            $imageID = get_woocommerce_term_meta($ttwID, 'thumbnail_id', true);
        } else {
            $imageID = get_term_meta($ttwID, 'taxonomy_thumb_id', true);
        }
        $thumbnailSrc = wp_get_attachment_image($imageID, $size);
        return $thumbnailSrc;
    }
endif;