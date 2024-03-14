<?php

/**
 * Cornerstone Editor by Themeco
 * https://theme.co/cornerstone
 *
 * Class Wpil_Editor_Cornerstone
 */
class Wpil_Editor_Cornerstone
{
    public static $link_processed;
    public static $keyword_links_count;
    public static $link_confirmed;
    public static $force_insert_link;

    /**
     * Obtains the post's text content data from the meta.
     **/
    public static function getContent($post_id = 0){
        $cornerstone = get_post_meta($post_id, '_cornerstone_data', true);
        $editor_not_overridden = empty(get_post_meta($post_id, '_cornerstone_override', true));
        $content = '';

        if(!empty($cornerstone) && $editor_not_overridden){

            if(is_string($cornerstone)){ // backwards compatibility. The data has been JSON as of 1.3.0, but we have to be able to process the legacy data...
                $cornerstone = json_decode($cornerstone);
            }
           
            foreach($cornerstone as $section){
                self::processContent($content, $section);
            }
        }

        return $content;
    }

    /**
     * Processes the Cornerstone editor content to provide us with content for making suggestions with.
     * 
     * @param $content The string of post content that we'll be progressively updating as we go.
     * @param $data The Cornerstone data that we'll be looking through to extract content from
     **/
    public static function processContent(&$content, $data)
    {

        foreach (['accordion_item_content', 'alert_content', 'content', 'modal_content', 'text_subheadline_content', 'quote_content', 'controls_std_content', 'testimonial_content', 'text_content', ] as $key) {
            if (!empty($data->$key) && !('headline' === $data->_type && $key === 'text_content')) {
                $content .= "\n" . $data->$key;
            }
        }

        if (!empty($data->_modules)) {
            foreach ($data->_modules as $module) {
                self::processContent($content, $module);
            }
        }
    }
}