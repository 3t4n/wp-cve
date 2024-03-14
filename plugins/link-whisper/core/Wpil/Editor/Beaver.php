<?php

/**
 * Beaver editor
 *
 * Class Wpil_Editor_Beaver
 */
class Wpil_Editor_Beaver
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
        $beaver = get_post_meta($post_id, '_fl_builder_data', true);

        if (!empty($beaver)) {
            foreach ($beaver as $key => $item) {
                foreach (['text', 'html'] as $element) {
                    if (!empty($item->settings->$element)) {
                        preg_match('|<a .+'.$url.'.+>'.$anchor.'</a>|i', $item->settings->$element,  $matches);
                        if (!empty($matches[0])) {
                            $beaver[$key]->settings->$element = preg_replace('|<a [^>]+'.$url.'[^>]+>'.$anchor.'</a>|i', $anchor,  $beaver[$key]->settings->$element);
                        }
                    }
                }
            }

            update_post_meta($post_id, '_fl_builder_data', $beaver);
            update_post_meta($post_id, '_fl_builder_draft', $beaver);
        }
    }
}
