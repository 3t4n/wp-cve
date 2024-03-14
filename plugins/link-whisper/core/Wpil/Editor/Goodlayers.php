<?php

/**
 * Goodlayers editor
 *
 * Class Wpil_Editor_Goodlayers
 */
class Wpil_Editor_Goodlayers
{
    static $item_types = array('text-box', 'accordion', 'blockquote', 'toggle-box');
    public static $force_insert_link;

    /**
     * Gets the post content for the Goodlayers builder
     *
     * @param $post_id
     */
    public static function getContent($post_id){
        // goodlayer stores it's data in a vast array under a single index
        $goodlayer = get_post_meta($post_id, 'gdlr-core-page-builder', true);

        $content = '';

        if(!empty($goodlayer)){
            foreach($goodlayer as $item){
                // if this item is a type that we can get content out of
                if(isset($item['type']) && in_array($item['type'], self::$item_types, true) &&
                    isset($item['value']) && !empty($item['value'])) // and if there's a value
                {
                    // check if it's tabbed
                    if(isset($item['value']['tabs'])){
                        // if it is, retrieve the content from the tabs
                        foreach($item['value']['tabs'] as $tab){
                            if(isset($tab['content']) && !empty($tab['content'])){
                                $content .= "\n" . $tab['content'];
                            }
                        }
                    }elseif(isset($item['value']['content']) && !empty($item['value']['content'])){
                        $content .= "\n" . $item['value']['content'];
                    }
                }elseif(isset($item['type']) && $item['type'] === 'wordpress-editor-content'){
                    // if there's a WP editor content in the array, pull the post content
                    $content .= "\n" . get_post($post_id)->post_content;
                }
            }
        }

        return $content;
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
        $goodlayer = get_post_meta($post_id, 'gdlr-core-page-builder', true);

        if (!empty($goodlayer)) {

            // check if the current URL is for an image
            $is_image = false;
            if(preg_match('/\.jpg|\.jpeg|\.svg|\.png|\.gif|\.ico|\.webp/i', $url) && empty($anchor)){
                // if it is, check to see if there's an image tag with this URL in the post // Since the link is already for an image, we'll check if there's an image tag on the assumption that the user is deleting an image.
                if(preg_match('`<img [^><]+(\'|\")' . preg_quote($url, '`') . '(\'|\")[^><]*>|&lt;img [^&>]+(\'|\")' . preg_quote($url, '`') . '(\'|\")[^&>]*&gt;`', $content)){
                    $is_image = true;
                }
            }

            // create a dummy post object to avoid infinite loops
            $post = (object) array('type' => null);

            foreach($goodlayer as &$item){
                // if this item is a type that we can get content out of
                if(isset($item['type']) && in_array($item['type'], self::$item_types, true) &&
                    isset($item['value']) && !empty($item['value'])) // and if there's a value
                {
                    // check if it's tabbed
                    if(isset($item['value']['tabs'])){
                        // if it is, retrieve the content from the tabs
                        foreach($item['value']['tabs'] as &$tab){
                            if(isset($tab['content']) && !empty($tab['content'])){
                                if($is_image){
                                    $tab['content'] = Wpil_Link::deleteImage($post, $url, $tab['content']);
                                }else{
                                    $tab['content'] = Wpil_Link::deleteLink($post, $url, $anchor, $tab['content']);
                                }
                            }
                        }
                    }elseif(isset($item['value']['content']) && !empty($item['value']['content'])){
                        if($is_image){
                            $item['value']['content'] = Wpil_Link::deleteImage($post, $url, $item['value']['content']);
                        }else{
                            $item['value']['content'] = Wpil_Link::deleteLink($post, $url, $anchor, $item['value']['content']);
                        }
                    }
                }
            }

            update_post_meta($post_id, 'gdlr-core-page-builder', $goodlayer);
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
        $goodlayer = get_post_meta($post_id, 'gdlr-core-page-builder', true);

        if (!empty($goodlayer)) {
            $changed = false;

            $slashed_keyword = $keyword;
            $slashed_keyword->link = addslashes($keyword->link);
            $slashed_keyword->keyword = addslashes($keyword->keyword);

            foreach($goodlayer as &$item){
                // if this item is a type that we can get content out of
                if(isset($item['type']) && in_array($item['type'], self::$item_types, true) &&
                    isset($item['value']) && !empty($item['value'])) // and if there's a value
                {
                    // check if it's tabbed
                    if(isset($item['value']['tabs'])){
                        // if it is, retrieve the content from the tabs
                        foreach($item['value']['tabs'] as &$tab){
                            if(isset($tab['content']) && !empty($tab['content'])){
                                $matches = Wpil_Keyword::findKeywordLinks($keyword, $tab['content']);
                                if(empty($matches[0])){
                                    if($left_one && !$changed){
                                        $matches2 = Wpil_Keyword::findKeywordLinks($slashed_keyword, $tab['content']);
                                        if(!empty($matches2[0])){
                                            Wpil_Keyword::removeNonFirstLinks($slashed_keyword, $tab['content']);
                                            $changed = true;
                                        }
                                    }else{
                                        Wpil_Keyword::removeAllLinks($slashed_keyword, $tab['content']);
                                    }
                                }else{
                                    if($left_one && !$changed){
                                        Wpil_Keyword::removeNonFirstLinks($keyword, $tab['content']);
                                        $changed = true;
                                    }else{
                                        Wpil_Keyword::removeAllLinks($keyword, $tab['content']);
                                    }
                                }
                            }
                        }
                    }elseif(isset($item['value']['content']) && !empty($item['value']['content'])){
                        $matches = Wpil_Keyword::findKeywordLinks($keyword, $item['value']['content']);
                        if(empty($matches[0])){
                            if($left_one && !$changed){
                                $matches2 = Wpil_Keyword::findKeywordLinks($slashed_keyword, $item['value']['content']);
                                if(!empty($matches2[0])){
                                    Wpil_Keyword::removeNonFirstLinks($slashed_keyword, $item['value']['content']);
                                    $changed = true;
                                }
                            }else{
                                Wpil_Keyword::removeAllLinks($slashed_keyword, $item['value']['content']);
                            }
                        }else{
                            if($left_one && !$changed){
                                Wpil_Keyword::removeNonFirstLinks($keyword, $item['value']['content']);
                                $changed = true;
                            }else{
                                Wpil_Keyword::removeAllLinks($keyword, $item['value']['content']);
                            }
                        }
                    }
                }
            }

            update_post_meta($post_id, 'gdlr-core-page-builder', $goodlayer);
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
        $goodlayer = get_post_meta($post->id, 'gdlr-core-page-builder', true);

        if (!empty($goodlayer)) {
            foreach($goodlayer as &$item){
                // if this item is a type that we can get content out of
                if(isset($item['type']) && in_array($item['type'], self::$item_types, true) &&
                    isset($item['value']) && !empty($item['value'])) // and if there's a value
                {
                    // check if it's tabbed
                    if(isset($item['value']['tabs'])){
                        // if it is, retrieve the content from the tabs
                        foreach($item['value']['tabs'] as &$tab){
                            if(isset($tab['content']) && !empty($tab['content'])){
                                Wpil_URLChanger::replaceLink($tab['content'], $url, true, $post);
                            }
                        }
                    }elseif(isset($item['value']['content']) && !empty($item['value']['content'])){
                        Wpil_URLChanger::replaceLink($item['value']['content'], $url, true, $post);
                    }
                }
            }

            update_post_meta($post->id, 'gdlr-core-page-builder', $goodlayer);
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
        $goodlayer = get_post_meta($post->id, 'gdlr-core-page-builder', true);

        if (!empty($goodlayer)) {
            foreach($goodlayer as &$item){
                // if this item is a type that we can get content out of
                if(isset($item['type']) && in_array($item['type'], self::$item_types, true) &&
                    isset($item['value']) && !empty($item['value'])) // and if there's a value
                {
                    // check if it's tabbed
                    if(isset($item['value']['tabs'])){
                        // if it is, retrieve the content from the tabs
                        foreach($item['value']['tabs'] as &$tab){
                            if(isset($tab['content']) && !empty($tab['content'])){
                                Wpil_URLChanger::revertURL($tab['content'], $url);
                            }
                        }
                    }elseif(isset($item['value']['content']) && !empty($item['value']['content'])){
                        Wpil_URLChanger::revertURL($item['value']['content'], $url);
                    }
                }
            }

            update_post_meta($post->id, 'gdlr-core-page-builder', $goodlayer);
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

        $goodlayer = get_post_meta($post->id, 'gdlr-core-page-builder', true);

        if (!empty($goodlayer)) {
            foreach($goodlayer as &$item){
                // if this item is a type that we can get content out of
                if(isset($item['type']) && in_array($item['type'], self::$item_types, true) &&
                    isset($item['value']) && !empty($item['value'])) // and if there's a value
                {
                    // check if it's tabbed
                    if(isset($item['value']['tabs'])){
                        // if it is, retrieve the content from the tabs
                        foreach($item['value']['tabs'] as &$tab){
                            if(isset($tab['content']) && !empty($tab['content'])){
                                Wpil_Link::updateLinkUrl($tab['content'], $old_link, $new_link, $anchor);
                            }
                        }
                    }elseif(isset($item['value']['content']) && !empty($item['value']['content'])){
                        Wpil_Link::updateLinkUrl($item['value']['content'], $old_link, $new_link, $anchor);
                    }
                }
            }

            update_post_meta($post->id, 'gdlr-core-page-builder', $goodlayer);
        }
    }
}