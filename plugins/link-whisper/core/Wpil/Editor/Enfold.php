<?php

/**
 * Enfold (Avia) editor
 *
 * Class Wpil_Editor_Enfold
 */
class Wpil_Editor_Enfold
{
    /**
     * Delete link
     *
     * @param $post_id
     * @param $url
     * @param $anchor
     */
    public static function deleteLink($post_id, $url, $anchor)
    {
        if (defined('AV_FRAMEWORK_VERSION') && 'active' === get_post_meta($post_id, '_aviaLayoutBuilder_active', true)) {
            $enfold_content = get_post_meta($post_id, '_aviaLayoutBuilderCleanData', true);

            preg_match('|<a .+'.$url.'.+>'.$anchor.'</a>|i', $enfold_content,  $matches);
            if (!empty($matches[0])) {
                $url = addslashes($url);
                $anchor = addslashes($anchor);
            }

            $enfold_content = preg_replace('|<a [^>]+'.$url.'[^>]+>'.$anchor.'</a>|i', $anchor,  $enfold_content);

            update_post_meta($post_id, '_aviaLayoutBuilderCleanData', $enfold_content);
        }
    }

    /**
     * Remove keyword links
     *
     * @param $keyword
     * @param $post_id
     * @param bool $left_one
     */
    public static function removeKeywordLinks($keyword, $post_id, $left_one = false)
    {
        if (defined('AV_FRAMEWORK_VERSION') && 'active' === get_post_meta($post_id, '_aviaLayoutBuilder_active', true)) {
            $enfold_content = get_post_meta($post_id, '_aviaLayoutBuilderCleanData', true);

            $matches = Wpil_Keyword::findKeywordLinks($keyword, $enfold_content);
            if (!empty($matches[0])) {
                $keyword->link = addslashes($keyword->link);
                $keyword->keyword = addslashes($keyword->keyword);
            }

            if ($left_one) {
                Wpil_Keyword::removeNonFirstLinks($keyword, $enfold_content);
            } else {
                Wpil_Keyword::removeAllLinks($keyword, $enfold_content);
            }

            update_post_meta($post_id, '_aviaLayoutBuilderCleanData', $enfold_content);
        }
    }

    /**
     * Replace URLs
     *
     * @param $post
     * @param $url
     */
    public static function replaceURLs($post, $url)
    {
        if (defined('AV_FRAMEWORK_VERSION') && 'active' === get_post_meta($post->id, '_aviaLayoutBuilder_active', true)) {
            $enfold_content = get_post_meta($post->id, '_aviaLayoutBuilderCleanData', true);

            Wpil_URLChanger::replaceLink($enfold_content, $url);
            update_post_meta($post->id, '_aviaLayoutBuilderCleanData', $enfold_content);
        }
    }

    /**
     * Revert URLs
     *
     * @param $post
     * @param $url
     */
    public static function revertURLs($post, $url)
    {
        if (defined('AV_FRAMEWORK_VERSION') && 'active' === get_post_meta($post->id, '_aviaLayoutBuilder_active', true)) {
            $enfold_content = get_post_meta($post->id, '_aviaLayoutBuilderCleanData', true);
            Wpil_URLChanger::revertURL($enfold_content, $url);

            update_post_meta($post->id, '_aviaLayoutBuilderCleanData', $enfold_content);
        }
    }

    /**
     * Updates the urls of existing links on a link-by-link basis.
     * For use with the Ajax URL updating functionality
     *
     * @param Wpil_Model_Post $post
     * @param $old_link
     * @param $new_link
     * @param $anchor
     */
    public static function updateExistingLink($post, $old_link, $new_link, $anchor)
    {
        // exit if this is a term or there's no post data
        if(empty($post) || $post->type !== 'post'){
            return;
        }

        if (defined('AV_FRAMEWORK_VERSION') && 'active' === get_post_meta($post->id, '_aviaLayoutBuilder_active', true)) {
            $enfold_content = get_post_meta($post->id, '_aviaLayoutBuilderCleanData', true);
            Wpil_Link::updateLinkUrl($enfold_content, $old_link, $new_link, $anchor);
            update_post_meta($post->id, '_aviaLayoutBuilderCleanData', $enfold_content);
        }
    }
}