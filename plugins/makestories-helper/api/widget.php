<?php

add_action("wp_ajax_ms_publish_widget", "ms_publish_widget");

/**
 * Action for publishing the post. Takes the story ID and gets the HTML for that
 */

function ms_publish_widget(){
    ms_protect_ajax_route();
    header("Content-Type: application/json");
    if(
        isset($_REQUEST["widgetId"]) &&
        isset($_REQUEST["widgetName"]) &&
        isset($_REQUEST["container"]) &&
        isset($_REQUEST["script"]) &&
        isset($_REQUEST["type"]) &&
        isset($_REQUEST['divId'])
    ){
        $widgetId = sanitize_text_field($_REQUEST["widgetId"]);
        $title = sanitize_text_field($_REQUEST["widgetName"]);
        $slug = sanitize_text_field($_REQUEST["slug"]);
        $container = sanitize_text_field($_REQUEST["container"]);
        $script = sanitize_text_field($_REQUEST["script"]);
        $jsBlock = sanitize_text_field($_REQUEST["scriptBlock"]);
        $type = sanitize_text_field($_REQUEST["type"]);
        $categories = sanitize_text_field($_REQUEST["tagsSelected"]);
        $design = sanitize_text_field($_REQUEST["designSelected"]);
        $divId = sanitize_text_field($_REQUEST['divId']);

        if(isset($_REQUEST['widgetPostId'])) {
            $widgetPostId = (int)sanitize_text_field($_REQUEST['widgetPostId']);
            $post_id = get_post($widgetPostId);
            if ($post_id && $post_id->post_status != 'trash') {
                $post_id = $post_id->ID;

                wp_update_post([
                    "post_content" => $container,
                    "ID" => $post_id,
                    "post_name" => $slug,
                    "post_title" => $title,
                ]);

                // update post meta
                update_post_meta($post_id, 'widget_id', $widgetId);
                update_post_meta($post_id, 'container', $container);
                update_post_meta($post_id, 'title', $title);
                update_post_meta($post_id, 'js-block', $jsBlock);
                update_post_meta($post_id, 'type', $type);
                update_post_meta($post_id, 'design', $design);
                update_post_meta($post_id, 'categories', $categories);
                update_post_meta($post_id, 'divId', $divId);

            } else {
                die(json_encode(["success" => false, "error" => "Post already deleted!"]));
            }
        } else {
            $slug = sanitize_text_field($_REQUEST["slug"]);
            $post_id = wp_insert_post([
                "post_content" => $container,
                "post_name" => $slug,
                "post_title" => $title,
                "post_status" => "publish",
                "post_type" => MS_POST_WIDGET_TYPE,
            ]);

            // insert post meta
            add_post_meta($post_id, 'widget_id', $widgetId);
            add_post_meta($post_id, 'container', $container);
            add_post_meta($post_id, 'title', $title);
            add_post_meta($post_id, 'js-block', $jsBlock);
            add_post_meta($post_id, 'type', $type);
            add_post_meta($post_id, 'design', $design);
            add_post_meta($post_id, 'categories', $categories);
            add_post_meta($post_id, 'divId', $divId);
        }

            $widgetObject =
            [
                "post_id" => $post_id,
            ];

        print_r(json_encode($widgetObject));
        wp_die();
    }
}