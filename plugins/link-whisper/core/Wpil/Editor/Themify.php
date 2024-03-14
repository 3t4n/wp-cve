<?php

/**
 * Themify editor
 *
 * Class Wpil_Editor_Themify
 */
class Wpil_Editor_Themify
{
    public static $keyword_links_count;
    public static $meta_key = '_themify_builder_settings_json';
    public static $post_content;
    public static $force_insert_link;

    /**
     * Get Themify post content
     *
     * @param $post_id
     * @return string
     */
    public static function getContent($post_id)
    {
        self::$post_content = '';

        if(!class_exists('ThemifyBuilder_Data_Manager')){
            return self::$post_content;
        }

        $content = $post_id;
        self::manageLink($content, [
            'action' => 'get',
        ]);

        return self::$post_content;
    }

    /**
     * Delete link
     *
     * @param $post_id
     * @param $url
     * @param $anchor
     */
    public static function deleteLink($post_id, $url, $anchor)
    {
        if (empty($post_id) || !class_exists('ThemifyBuilder_Data_Manager')) {
            return;
        }

        global $ThemifyBuilder_Data_Manager;

        $data = $post_id;
        self::manageLink($data, [
            'action' => 'remove',
            'url' => $url,
            'anchor' => $anchor
        ]);

        if(empty($data)){
            return;
        }

        $data = json_decode(json_encode($data), true);

        // if there were no problems with converting the datatype
        if(!empty($data)){
            // save using Themify's data manager to avoid data issues
            // check if there's an instatiated object to use
            if(!empty($ThemifyBuilder_Data_Manager) && method_exists($ThemifyBuilder_Data_Manager, 'save_data')){
                // if there is, use it to save the data
                $ThemifyBuilder_Data_Manager->save_data($data, $post_id);
            }else{
                ThemifyBuilder_Data_Manager::save_data($data, $post_id);
            }
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
        if (empty($post_id) || !class_exists('ThemifyBuilder_Data_Manager')) {
            return;
        }

        global $ThemifyBuilder_Data_Manager;

        self::$keyword_links_count = 0;
        $data = $post_id;
        self::manageLink($data, [
            'action' => 'remove_keyword',
            'keyword' => $keyword,
            'left_one' => $left_one
        ]);

        if(empty($data)){
            return;
        }

        $data = json_decode(json_encode($data), true);

        // if there were no problems with converting the datatype
        if(!empty($data)){
            // save using Themify's data manager to avoid data issues

            // check if there's an instatiated object to use
            if(!empty($ThemifyBuilder_Data_Manager) && method_exists($ThemifyBuilder_Data_Manager, 'save_data')){
                // if there is, use it to save the data
                $ThemifyBuilder_Data_Manager->save_data($data, $post_id);
            }else{
                ThemifyBuilder_Data_Manager::save_data($data, $post_id);
            }
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
        if (empty($post_id) || !class_exists('ThemifyBuilder_Data_Manager')) {
            return;
        }

        global $ThemifyBuilder_Data_Manager;
        $data = $post->id;

        self::manageLink($data, [
            'action' => 'replace_urls',
            'url' => $url,
            'post' => $post,
        ]);

        if(empty($data)){
            return;
        }

        $data = json_decode(json_encode($data), true);

        // if there were no problems with converting the datatype
        if(!empty($data)){
            // save using Themify's data manager to avoid data issues

            // check if there's an instatiated object to use
            if(!empty($ThemifyBuilder_Data_Manager) && method_exists($ThemifyBuilder_Data_Manager, 'save_data')){
                // if there is, use it to save the data
                $ThemifyBuilder_Data_Manager->save_data($data, $post->id);
            }else{
                ThemifyBuilder_Data_Manager::save_data($data, $post->id);
            }
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
        if (empty($post_id) || !class_exists('ThemifyBuilder_Data_Manager')) {
            return;
        }

        global $ThemifyBuilder_Data_Manager;

        $data = $post->id;
        self::manageLink($data, [
            'action' => 'revert_urls',
            'url' => $url,
        ]);

        if(empty($data)){
            return;
        }

        $data = json_decode(json_encode($data), true);

        // if there were no problems with converting the datatype
        if(!empty($data)){
            // save using Themify's data manager to avoid data issues

            // check if there's an instatiated object to use
            if(!empty($ThemifyBuilder_Data_Manager) && method_exists($ThemifyBuilder_Data_Manager, 'save_data')){
                // if there is, use it to save the data
                $ThemifyBuilder_Data_Manager->save_data($data, $post->id);
            }else{
                ThemifyBuilder_Data_Manager::save_data($data, $post->id);
            }
        }
    }

    /**
     * Updates the urls of existing links on a link-by-link basis.
     * For use with the Ajax URL updating functionality
     *
     * @param Wpil_Model_Post $post
     * @param string $old_link
     * @param string $new_link
     * @param string $anchor
     */
    public static function updateExistingLink($post, $old_link, $new_link, $anchor)
    {
        // exit if this is a term or there's no post data
        if(empty($post) || $post->type !== 'post' || !class_exists('ThemifyBuilder_Data_Manager')){
            return;
        }

        global $ThemifyBuilder_Data_Manager;

        $data = $post->id;
        self::manageLink($data, [
            'action' => 'update_existing_link',
            'old_link' => $old_link,
            'new_link' => $new_link,
            'anchor' => $anchor,
        ]);

        if(empty($data)){
            return;
        }
        
        // make sure we send Themify an array and not an object
        $data = json_decode(json_encode($data), true);

        // if there were no problems with converting the datatype
        if(!empty($data)){
            // save using Themify's data manager to avoid data issues

            // check if there's an instatiated object to use
            if(!empty($ThemifyBuilder_Data_Manager) && method_exists($ThemifyBuilder_Data_Manager, 'save_data')){
                // if there is, use it to save the data
                $ThemifyBuilder_Data_Manager->save_data($data, $post->id);
            }else{
                ThemifyBuilder_Data_Manager::save_data($data, $post->id);
            }
        }
    }

    /**
     * Find all text elements
     *
     * @param $data
     * @param $params
     */
    public static function manageLink(&$data, $params)
    {
        global $ThemifyBuilder_Data_Manager;


        if (is_numeric($data)) {
            // check if there's an instatiated object to use
            if(!empty($ThemifyBuilder_Data_Manager) && method_exists($ThemifyBuilder_Data_Manager, 'get_data')){
                // if there is, use it to save the data
                $content = $ThemifyBuilder_Data_Manager->get_data($data, true);
            }else{
                $content = ThemifyBuilder_Data_Manager::get_data($data, true);
            }

            if (empty($content)) {
                return;
            }

            $data = json_decode($content);
        }

        if (is_countable($data)) {
            foreach ($data as $item) {
                self::checkItem($item, $params);
            }
        }
    }

    /**
     * Check certain text element
     *
     * @param $item
     * @param $params
     */
    public static function checkItem(&$item, $params)
    {
        if (!empty($item->mod_settings)) {
            foreach (['content_text', 'text_alert', 'content_box', 'text_callout', 'content_feature', 'plain_text'] as $key) {
                if (!empty($item->mod_settings->$key)) {
                    self::manageBlock($item->mod_settings->$key, $params);
                }
            }

            if (!empty($item->mod_settings->content_accordion)) {
                foreach ($item->mod_settings->content_accordion as &$value) {
                    if (!empty($value->text_accordion)) {
                        self::manageBlock($value->text_accordion, $params);
                    }
                }
            }

            if (!empty($item->mod_settings->tab_content_testimonial)) {
                foreach ($item->mod_settings->tab_content_testimonial as &$value) {
                    if (!empty($value) && isset($value->content_testimonial) && !empty($value->content_testimonial)) {
                        self::manageBlock($value->content_testimonial, $params);
                    }
                }
            }

            if (!empty($item->mod_settings->tab_content_tab)) {
                foreach ($item->mod_settings->tab_content_tab as &$value) {
                    if (!empty($value) && isset($value->text_tab) && !empty($value->text_tab)) {
                        self::manageBlock($value->text_tab, $params);
                    }
                }
            }
        }

        if (!empty($item->cols)) {
            foreach ($item->cols as &$value) {
                self::checkItem($value, $params);
            }
        }

        if (!empty($item->modules)) {
            foreach ($item->modules as &$value) {
                self::checkItem($value, $params);
            }
        }
    }

    /**
     * Route current action
     *
     * @param $block
     * @param $params
     */
    public static function manageBlock(&$block, $params)
    {
        if ($params['action'] == 'get') {
            self::$post_content .= $block . "\n"; /* mb_ereg_replace_callback('<[^<>]*?(title=["\'][^\'"]*?["\']|alt=["\'][^\'"]*?["\'])+[^<>]*?>', function($matches){ 
                return str_replace("'", '"', $matches[0]);
            }, $block) . "\n";*/ // todo remove if I can't find a use for this after version 2.3.0
        } elseif ($params['action'] == 'remove') {
            self::removeLinkFromBlock($block, $params['url'], $params['anchor']);
        } elseif ($params['action'] == 'remove_keyword') {
            self::removeKeywordFromBlock($block, $params['keyword'], $params['left_one']);
        } elseif ($params['action'] == 'replace_urls') {
            self::replaceURLInBlock($block, $params['url'], $params['post']);
        } elseif ($params['action'] == 'revert_urls') {
            self::revertURLInBlock($block, $params['url']);
        } elseif ($params['action'] == 'update_existing_link') {
            self::updateURLInBlock($block, $params['old_link'], $params['new_link'], $params['anchor']);
        }
    }

    /**
     * Remove link from block
     *
     * @param $block
     * @param $url
     * @param $anchor
     */
    public static function removeLinkFromBlock(&$block, $url, $anchor)
    {
        // decode the url if it's base64 encoded
        if(base64_encode(base64_decode($url, true)) === $url){
            $url = base64_decode($url);
        }

        preg_match('`<a .+?' . preg_quote($url, '`') . '.+?>' . preg_quote($anchor, '`') . '</a>`i', $block,  $matches);
        if (!empty($matches[0])) {
            $block = preg_replace('|<a [^>]+' . preg_quote($url, '`') . '[^>]+>' . preg_quote($anchor, '`') . '</a>|i', $anchor,  $block);
        }
    }

    /**
     * Remove keyword links
     *
     * @param $block
     * @param $keyword
     * @param $left_one
     */
    public static function removeKeywordFromBlock(&$block, $keyword, $left_one)
    {
        $matches = Wpil_Keyword::findKeywordLinks($keyword, $block);
        if (!empty($matches[0])) {
            if (!$left_one || self::$keyword_links_count) {
                Wpil_Keyword::removeAllLinks($keyword, $block);
            }
            if($left_one && self::$keyword_links_count == 0 and count($matches[0]) > 1) {
                Wpil_Keyword::removeNonFirstLinks($keyword, $block);
            }
            self::$keyword_links_count += count($matches[0]);
        }
    }


    /**
     * Replace URL in block
     *
     * @param $block
     * @param $url
     */
    public static function replaceURLInBlock(&$block, $url, $post)
    {
        if (Wpil_URLChanger::hasUrl($block, $url)) {
            Wpil_URLChanger::replaceLink($block, $url, true, $post);
            $block = str_replace('"', "'", $block);
        }
    }

    /**
     * Revert URL in block
     *
     * @param $block
     * @param $url
     */
    public static function revertURLInBlock(&$block, $url)
    {
        preg_match('`data-wpil=\'url\' (?:data-wpil-url-old=[\'\"]([a-zA-Z0-9+=]*?)[\'\"] )*(href|url)=[\'\"]' . preg_quote($url->new, '`') . '\/*[\'\"]`i', $block, $matches);
        if (!empty($matches)) {
            $block = preg_replace('`data-wpil=\'url\' (?:data-wpil-url-old=[\'\"]([a-zA-Z0-9+=]*?)[\'\"] )*(href|url)=([\'\"])' . $url->new . '\/*([\'\"])`i', '$1=$2' . $url->old . '$3', $block);
        }
    }

    public static function updateURLInBlock(&$block, $old_link, $new_link, $anchor){
        preg_match('`(href|url)=[\'\"]' . preg_quote($old_link, '`') . '\/*[\'\"]`i', $block, $matches);
        if (!empty($matches)) {
            Wpil_Link::updateLinkUrl($block, $old_link, $new_link, $anchor);
        }
    }

    /**
     * Makes sure all double qoutes and their slashes are excaped once in the supplied text.
     * @param string $text The text that needs to have it's quotes escaped
     * @return string $text The updated text with the double qoutes and their slashes escaped
     **/
    public static function normalize_slashes($text){
        // add slashes to the double qoutes
        $text = mb_eregi_replace('(?<!\\\\)"', '\\\"', $text);
        // and return the text
        return $text;
    }
}