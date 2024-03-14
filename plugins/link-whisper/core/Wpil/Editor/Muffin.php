<?php

/**
 * Muffin editor
 *
 * Class Wpil_Editor_Muffin
 */
class Wpil_Editor_Muffin
{
    /**
     * Gets the post content for the Muffin builder
     * 
     * @param $post_id
     */
    public static function getContent($post_id){
        // muffin stores it's data in a vast array under a single index
        $muffin = get_post_meta($post_id, 'mfn-page-items', true);
        // get if the wp editor content is being hidden from view
        $hiding_post_content = get_post_meta($post_id, 'mfn-post-hide-content', true);

        $content = '';

        if(!empty($muffin)){
            if(Wpil_Link::checkIfBase64ed($muffin)){
                $muffin = maybe_unserialize(base64_decode($muffin));
            }

            // if the builder isn't set to hide the wp editor's content
            if(empty($hiding_post_content)){
                // get the post content
                $post = get_post($post_id);
                $content .= $post->post_content;
            }

            foreach($muffin as $item){
                if(isset($item['wraps'])){
                    foreach($item['wraps'] as $wrap){
                        if(isset($wrap['items']) && !empty($wrap['items']) && is_array($wrap['items'])){
                            foreach($wrap['items'] as $item){
                                if(isset($item['fields']) && isset($item['fields']['content'])){
                                    $content .= "\n" . $item['fields']['content'];
                                }elseif(isset($item['attr']) && isset($item['attr']['content'])){
                                    $content .= "\n" . $item['attr']['content'];
                                }elseif(isset($item['type']) && 'content' === $item['type']){
                                    // if the current item is a "WP Editor" content item, pull the post content
                                    $content .= "\n" . get_post($post_id)->post_content;
                                }
                            }
                        }
                    }
                }
            }
        }

        return $content;
    }
}