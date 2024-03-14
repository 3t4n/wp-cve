<?php

class LPageryWpmlHandler
{
    public static function lpagery_handle_wpml($sourcePostId, $targetPostId)
    {
        if (!defined('ICL_SITEPRESS_VERSION')) {
            return;
        }
        $template_language_info = wpml_get_language_information(null, $sourcePostId);
        if ($template_language_info) {
            $get_language_args = array('element_id' => $sourcePostId, 'element_type' => get_post_type($sourcePostId) );
            $original_post_language_info = apply_filters( 'wpml_element_language_details', null, $get_language_args );
            self::wpml_switch_post_language($original_post_language_info->language_code, $targetPostId);
        } else {
            echo "Template post language information not available.";
        }

    }

    private static function wpml_switch_post_language($language, $post_id)
    {
        global $sitepress, $wpdb;

        if ($post_id && $language) {
            $post_type = get_post_type($post_id);
            $wpml_post_type = 'post_' . $post_type;
            $trid = $sitepress->get_element_trid($post_id, $wpml_post_type);

            /* Check if a translation in that language already exists with a different post id.
             * If so, then don't perform this action.
             */
            $sitepress->set_element_language_details($post_id, $wpml_post_type, $trid, $language);
            // Synchronize the posts terms languages. Do not create automatic translations though.
            WPML_Terms_Translations::sync_post_terms_language($post_id);
            require_once WPML_PLUGIN_PATH . '/inc/cache.php';
            icl_cache_clear($post_type . 's_per_language', true);


            \WPML\LIB\WP\Cache::clearMemoizedFunction('get_source_language_by_trid', (int)$trid);
        }

    }

}