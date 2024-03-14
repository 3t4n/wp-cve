<?php

use PhpOffice\PhpSpreadsheet\Writer\Ods\Content;

/**
 * Beaver editor
 *
 * Class Wpil_Editor_Oxygen
 */
class Wpil_Editor_Oxygen
{
    public static $content_types = [
        'ct_text_block',
        'oxy_rich_text',
        'oxy_tabs_content'
    ];

    public static $args_types = [
        'oxy_testimonial' => [
            'testimonial_text',
            'testimonial_author',
            'testimonial_author_info'
        ],
        'oxy_icon_box' => [
            'icon_box_text'
        ],
        'oxy_pricing_box' => [
            'pricing_box_package_title',
            'pricing_box_package_subtitle',
            'pricing_box_content'
        ]
    ];

    public static $keyword_links_count;
    public static $force_insert_link;
    public static $json_data = false;
    public static $post_saving = null; // are we doing stuff during the "save_post" action?

    /**
     * Check if editor is active
     *
     * @return bool
     */
    public static function active()
    {
        self::$json_data = defined('CT_VERSION') && version_compare(CT_VERSION, '4.0', '>=');

        $activated_plugins = get_option('active_plugins');
        foreach ($activated_plugins as $plugin){
            if (strpos($plugin, 'oxygen/') === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get Oxygen post content
     *
     * @param $post_id
     * @return string
     */
    public static function getContent($post_id, $remove_unprocessable = true)
    {
        $data = self::getData($post_id);
        if (!self::active() || empty($data)) {
            return '';
        }

        // if we're not removing the items we can't process
        if(!$remove_unprocessable){
            // try getting the shortcode data
            $dat = get_post_meta($post_id, 'ct_builder_shortcodes', true);
            if(!empty($dat)){
                if(Wpil_Settings::getContentFormattingLevel() > 0){
                    return (function_exists('do_oxygen_elements')) ? do_oxygen_elements($dat): do_shortcode($dat);
                }else{
                    return $dat;
                }
            }
        }

        $content = '';
        if(self::$json_data){
            self::getJsonReadOnlyContent($data, $content);
        }else{
            foreach ($data as $item) {
                self::getItemContent($item, $content);
            }
        }

        return $content;
    }

    /**
     * Get content from certain shortcode
     *
     * @param $item
     * @param $content
     */
    public static function getItemContent($item, &$content)
    {
        foreach (self::$args_types as $type => $types) {
            if ($item->type == $type) {
                $args = json_decode($item->args_value);
                foreach ($types as $key) {
                    if (!empty($args->original->$key)) {
                        $content .= base64_decode($args->original->$key) . "\n";
                    }
                }
            }
        }

        if (!empty($item->content) && in_array($item->type, self::$content_types)) {
            $content .= $item->content . "\n";
        }

        if (!empty($item->children)) {
            foreach ($item->children as $child)
            self::getItemContent($child, $content);
        }
    }

    /**
     * Gets the content that is processable by Link Whisper, for display & reading purposes only.
     * The content is taken out of it's native object construction and stringified for searching and phrase making purposes
     **/
    public static function getJsonReadOnlyContent($data, &$content){
        // if this is the base element in the Oxygen data object
        if(isset($data['name']) && $data['name'] === 'root'){
            $data = array($data); // wrapp the data in an array so we can loop over it
        }

        foreach($data as $dat){
            if( isset($dat['name']) && 
                in_array($dat['name'], self::$content_types, true) &&
                isset($dat['options']) && isset($dat['options']['ct_content']) && !empty($dat['options']['ct_content']))
            {
                $content .= "\n" . $dat['options']['ct_content'];
            }

            if(isset($dat['children']) && !empty($dat['children'])){
                self::getJsonReadOnlyContent($dat['children'], $content);
            }
        }
    }

    /**
     * Remove link from content
     *
     * @param $post_id
     * @param $url
     * @param $anchor
     */
    public static function deleteLink($post_id, $url, $anchor)
    {
        $data = self::getData($post_id);
        if (!self::active() || empty($data)) {
            return;
        }

        self::manageLink($data, [
            'action' => 'remove',
            'url' => $url,
            'anchor' => $anchor
        ]);

        self::saveData($post_id, $data);
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
        $data = self::getData($post_id);
        if (!self::active() || empty($data)) {
            return;
        }

        self::$keyword_links_count = 0;
        self::manageLink($data, [
            'action' => 'remove_keyword',
            'keyword' => $keyword,
            'left_one' => $left_one
        ]);

        self::saveData($post_id, $data);
    }

    /**
     * Replace URLs
     *
     * @param $post
     * @param $url
     */
    public static function replaceURLs($post, $url)
    {
        $data = self::getData($post->id);
        if (!self::active() || empty($data)) {
            return;
        }

        self::manageLink($data, [
            'action' => 'replace_urls',
            'url' => $url,
            'post' => $post,
        ]);

        self::saveData($post->id, $data);
    }

    /**
     * Revert URLs
     *
     * @param $post
     * @param $url
     */
    public static function revertURLs($post, $url)
    {
        $data = self::getData($post->id);
        if (!self::active() || empty($data)) {
            return;
        }

        self::manageLink($data, [
            'action' => 'revert_urls',
            'url' => $url,
        ]);

        self::saveData($post->id, $data);
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
        if(empty($post) || $post->type !== 'post'){
            return;
        }

        $data = self::getData($post->id);
        if (!self::active() || empty($data)) {
            return;
        }

        self::manageLink($data, [
            'action' => 'update_existing_link',
            'old_link' => $old_link,
            'new_link' => $new_link,
            'anchor' => $anchor,
        ]);

        self::saveData($post->id, $data);
    }

    /**
     * Get all content items
     *
     * @param $data
     * @param $params
     */
    public static function manageLink(&$data, $params)
    {
        if (is_countable($data)) {
            if(self::$json_data){
                self::checkJsonItem($data, $params);
            }else{
                foreach ($data as $item) {
                    self::checkItem($item, $params);
                }
            }
        }
    }

    /**
     * 
     **/
    public static function checkJsonItem(&$item, $params){
        // if this is the base element in the Oxygen data object
        if(isset($item['name']) && $item['name'] === 'root'){
            $item = array($item); // wrapp the data in an array so we can loop over it
        }

        foreach($item as &$dat){
            if( isset($dat['name']) && 
                in_array($dat['name'], self::$content_types, true) &&
                isset($dat['options']) && isset($dat['options']['ct_content']) && !empty($dat['options']['ct_content']))
            {
                self::manageBlock($dat['options']['ct_content'], $params);
            }

            if(isset($dat['children']) && !empty($dat['children'])){
                self::checkJsonItem($dat['children'], $params);
            }
        }
    }

    /**
     * Get content from certain item
     *
     * @param $item
     * @param $params
     */
    public static function checkItem(&$item, $params)
    {
        foreach (self::$args_types as $type => $types) {
            if ($item->type == $type) {
                $args = json_decode($item->args_value);
                foreach ($types as $key) {
                    if (!empty($args->original->$key)) {
                        $block = base64_decode($args->original->$key);
                        self::manageBlock($block, $params);
                        $args->original->$key = base64_encode($block);
                    }
                }

                $args = json_encode($args);
                if ($item->args_value != $args) {
                    $item->args_value = $args;
                }
            }
        }

        if (!empty($item->content) && in_array($item->type, self::$content_types)) {
            self::manageBlock($item->content, $params);
        }

        if (!empty($item->children)) {
            foreach ($item->children as $child) {
                self::checkItem($child, $params);
            }
        }
    }

    /**
     * Route certain item
     *
     * @param $block
     * @param $params
     */
    public static function manageBlock(&$block, $params)
    {
        if ($params['action'] == 'remove') {
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
        preg_match('`data-wpil="url" (?:data-wpil-url-old=[\'\"]([a-zA-Z0-9+=]*?)[\'\"] )*(href|url)=[\'\"]' . preg_quote($url->new, '`') . '\/*[\'\"]`i', $block, $matches);
        if (!empty($matches)) {
            $block = preg_replace('`data-wpil="url" (?:data-wpil-url-old=[\'\"]([a-zA-Z0-9+=]*?)[\'\"] )*(href|url)=([\'\"])' . $url->new . '\/*([\'\"])`i', '$1=$2' . $url->old . '$3', $block);
        }
    }

    public static function updateURLInBlock(&$block, $old_link, $new_link, $anchor){
        preg_match('`(href|url)=[\'\"]' . preg_quote($old_link, '`') . '\/*[\'\"]`i', $block, $matches);
        if (!empty($matches)) {
            Wpil_Link::updateLinkUrl($block, $old_link, $new_link, $anchor);
        }
    }

    /**
     * Parse Oxygen post content
     *
     * @param $post_id
     * @return array
     */
    public static function getData($post_id)
    {
        if(!defined('CT_VERSION') || !self::active()){
            return array();
        }

        $data = self::get_meta($post_id);

        if(self::$json_data){
            return $data;
        }else{
            $data = self::getItem($data);
        }

        return $data;
    }

    /**
     * Parse certain shortcode
     *
     * @param $data
     * @return array
     */
    public static function getItem($data)
    {
        $blocks = [];
        $begin = self::closestShortcode($data);

        $i = 0;
        while ($begin !== false) {
            $i++;
            $end = strpos($data, ' ', $begin);
            $type = substr($data, $begin + 1, $end - $begin - 1);
            $end = strpos($data, '[/' . $type . ']', $begin);
            $text = substr($data, $begin, $end - $begin + strlen($type) + 3);

            //get content
            $content_begin = strpos($text, ']');
            $sub_content_begin = strpos($text, ']"');
            // check if there's a shortcode inside the shortcode we're trying to examine
            if(!empty($sub_content_begin) && $content_begin === $sub_content_begin){
                // if there is, update the parent shortcode ending so it's actually the end and not the sub content shortcode ending...
                $content_begin = strpos($text, ']', ($sub_content_begin + 1));
            }
            $content_end = strrpos($text, '[');
            $content = substr($text, $content_begin + 1, $content_end - $content_begin - 1);

            //get sign type
            $params_end = strpos($text, ']');
            $params = substr($text, 0, $params_end);
            $params = explode(' ', $params);

            if(!isset($params[0]) || !isset($params[1])){
                if(false === $end){
                    $end = strpos($data, ']', $begin);
                }

                if(false === $end){
                    break;
                }else{
                    $begin = self::closestShortcode($data, $end + 1);
                    continue;
                }
            }

            $sig = explode('=', $params[1]);
            $sig_value = substr($sig[1], 1, -1);

            //get args
            $params = array_slice($params, 2);
            $params = implode('', $params);
            $args = preg_split('/([a-zA-Z0-9])(?:=)([\'])/', $params);
            array_shift($args);
            $args = implode('', $args);
            $args_value = trim($args, '\'');

            $blocks[] = (object)[
                'type' => $type,
                'text' => $text,
                'sig_key' => $sig[0],
                'sig_value' => $sig_value,
                'args_value' => $args_value,
                'content' => $content,
                'children' => self::getItem($content)
            ];

            $begin = self::closestShortcode($data, $end + 1);
        }

        return $blocks;
    }

    public static function closestShortcode($string = '', $offset = 0){
        if(empty($string) || $offset > strlen($string)){
            return false;
        }
        $tags = array('[ct', '[oxy');

        $positions = array();
        foreach($tags as $tag) {
            $position = strpos($string, $tag, $offset);
            $subtag = ('"' . $tag);
            if ($position !== false && // if we've found a tag
                $position !== (strpos($string, $subtag, $offset) + 1)) // and the tag we've found doesn't belong to a sub-field shortcode
            {
                $positions[$tag] = $position; // set the position
            }
        }

        return (!empty($positions)) ? min($positions): false;
    }

    /**
     * Obtains the Oxygen content from the post meta.
     * Gets the shortcode data for versions < 4.0 and gets JSON for versions =< 4.0
     * 
     * @param int $post_id
     * @return array
     **/
    public static function get_meta($post_id){
        if(empty($post_id) || !defined('CT_VERSION')){
            return array();
        }
        // if the version is 4.0 or above
        if(self::$json_data){
            // obtain the json data

            // is there $_POST json?
            self::$post_saving = (isset($_POST['ct_builder_json']) && !empty($_POST['ct_builder_json'])) ? true: false;

            if(self::$post_saving){
                $data = trim(wp_unslash($_POST['ct_builder_json']));
            }else{
                $data = get_post_meta($post_id, 'ct_builder_json', true);
            }

        }else{
            // otherwise, go for shortcodes
            $data = get_post_meta($post_id, 'ct_builder_shortcodes', true);
        }

        // if there's no data, return an empty array
        if(empty($data)){
            return array();
        }

        // if this is json data
        if(self::$json_data){
            // decode it before returning it
            $data = json_decode($data, true);
        }

        return $data;
    }

    /**
     * Save Oxygen content
     *
     * @param $post_id
     * @param $data
     */
    public static function saveData($post_id, $data)
    {
        // if this is json data
        if(self::$json_data){
            // todo create some
            if(isset($data[0])){
                $data = $data[0];
            }

            $data = json_encode($data);

            // if there were no mistakes in encoding the JSON
            if(!empty($data)){
                // decode the json again and process it into shortcodes
                $components_tree_json_for_shortcodes = json_decode($data, true);
                // base64 encode js,css code and (wrapping_shortcode in case of nestable_shortcode component) in the IDs
                if(isset($components_tree_json_for_shortcodes['children'])) {
                    $components_tree_json_for_shortcodes['children'] = ct_base64_encode_decode_tree($components_tree_json_for_shortcodes['children']);
                }
                $components_tree_json_for_shortcodes = json_encode($components_tree_json_for_shortcodes);
                // Generate the shortcodes from JSON data
                $shortcodes = components_json_to_shortcodes( $components_tree_json_for_shortcodes );

                // if we're in the middle of saving the post
                if(self::$post_saving){
                    // update the post data
                    $_POST['ct_builder_json'] = wp_slash($data);
                    $_POST['ct_builder_shortcodes'] = wp_slash($shortcodes);
                }else{
                    // if we're not saving the post, update the stored meta data
                    update_post_meta($post_id, 'ct_builder_json', addslashes($data));
                    update_post_meta($post_id, 'ct_builder_shortcodes', $shortcodes);
                }
            }

        }else{
            $text = '';
            foreach ($data as $item) {
                $item = self::updateItem($item);
                $text .= $item->text;
            }

            update_post_meta($post_id, 'ct_builder_shortcodes', $text);
        }
    }

    /**
     * Update certain shortcode in the parsed data
     * For Oxygen 3.9 and below
     *
     * @param $item
     * @return mixed
     */
    public static function updateItem($item) {
        if (!empty($item->children)) {
            $item->content = '';
            foreach ($item->children as $child) {
                $updated_item = self::updateItem($child);
                $item->content .= !empty($updated_item->text) ? $updated_item->text : '';
            }
        }

        $sig_class = new OXYGEN_VSB_Signature();
        $item->sig_value = $sig_class->generate_signature( $item->type, ['ct_options' => $item->args_value], $item->content);
        $item->text = "[{$item->type} {$item->sig_key}='{$item->sig_value}' ct_options='{$item->args_value}']{$item->content}[/{$item->type}]";

        return $item;
    }
}