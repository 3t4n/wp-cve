<?php
require_once(plugin_dir_path(__FILE__) . '../utils/Utils.php');
require_once(plugin_dir_path(__FILE__) . 'SubstitutionHandler.php');

class LPageryMetaDataHandler {
    public static function lpagery_copy_post_meta_info($new_id, $post, $meta_excludelist, $params) {
        $post_meta_keys = \get_post_custom_keys($post->ID);
        if (empty($post_meta_keys)) {
            return;
        }
        if (!\is_array($meta_excludelist)) {
            $meta_excludelist = [];
        }
        $meta_excludelist = \array_merge($meta_excludelist, LPageryUtils::lpagery_get_default_filtered_meta_names());


        $meta_excludelist_string = '(' . \implode(')|(', $meta_excludelist) . ')';
        if (strpos($meta_excludelist_string, '*') !== false) {
            $meta_excludelist_string = \str_replace(['*'], ['[a-zA-Z0-9_]*'], $meta_excludelist_string);

            $meta_keys = [];
            foreach ($post_meta_keys as $meta_key) {
                if (!\preg_match('#^' . $meta_excludelist_string . '$#', $meta_key)) {
                    $meta_keys[] = $meta_key;
                }
            }
        } else {
            $meta_keys = \array_diff($post_meta_keys, $meta_excludelist);
        }

        foreach ($meta_keys as $meta_key) {

            $meta_values = get_post_custom_values($meta_key, $post->ID);

            delete_post_meta($new_id, $meta_key);

            foreach ($meta_values as $meta_value) {
                $meta_value = maybe_unserialize($meta_value);

                $replacedValue = (LPagerySubstitutionHandler::lpagery_substitute($params, $meta_value));

                add_post_meta($new_id, $meta_key, LPageryUtils::lpagery_recursively_slash_strings($replacedValue));
            }
        }
        delete_post_meta($new_id, "_lpagery_page_source");
        delete_post_meta($new_id, "_lpagery_process");

        add_post_meta($new_id, "_lpagery_page_source", $post->ID);
        add_post_meta($new_id, "_lpagery_process", $params["process_id"]);

    }


}