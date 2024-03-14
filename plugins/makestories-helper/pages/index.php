<?php
function mscpt_admin_menu() {
    $addPageFor = ms_get_allowed_roles();
    $alreadyAdded = false;
    foreach ($addPageFor as $role){
        if(current_user_can($role) && !$alreadyAdded){
            $alreadyAdded = true;
            add_dashboard_page(
                __( 'MakeStories', MS_TEXT_DOMAIN ),
                __( 'MakeStories', MS_TEXT_DOMAIN ),
                $role,
                MS_ROUTING['EDITOR']['slug'],
                'ms_editor_contents',
                'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIGZpbGw9IiNhMGE1YWEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgZmlsbD0iI2EwYTVhYSIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMTguMjEwOSAzLjE2ODNDMTkuMTAwMSA0LjI3MTg2IDE5LjcwNDcgNS41NTM0MiAxOS45MTgxIDcuMDEyOThDMjAuNDg3MiAxMC45NjQ1IDE3Ljk5NzUgMTQuNTU5OSAxNS4yOTQzIDE2LjI2ODdDMTQuNDQwNyAxNi44MzgzIDEwLjMxNDggMTkuMTg3OCA3Ljg5NjEzIDE5LjE4NzhDNy41MDQ4OSAxOS4xODc4IDcuMTQ5MjEgMTkuMTE2NiA2Ljg2NDY2IDE4Ljk3NDJMNi45MTYyNyAxOC45MTU3TDYuODI5MjcgMTkuMDA5NUM2LjcyMjU3IDE4Ljk3MzkgNi42NTE0MyAxOC45MzgzIDYuNTgwMyAxOC44NjcxQzYuMzMxMzIgMTguNjg5MSA1LjgzMzM3IDE4LjA4NCA2LjI5NTc1IDE3LjAxNkw2LjM2Njg5IDE2Ljg3MzZMOC44OTIyMiAxNC4wOTY5SDExLjA5NzRDMTEuMDI2MyAxNC4zNDYxIDEwLjg4NCAxNC41MjQxIDEwLjcwNjIgMTQuNzAyMUw4LjM5NDI3IDE3LjMwMDhMOC4yODIzMyAxNy40MzUyQzkuNTU3MDggMTcuMjYzOSAxMi4yNzE4IDE2LjIwOSAxNC4zMzQgMTQuODQ0N0MxNi41MzkyIDEzLjM4NTIgMTguNTMxIDEwLjUzNzMgMTguMjEwOSA3LjQ0MDE3QzE4LjE3NTMgNy4zNjg5NyAxOC4xNzUzIDcuMjk3NzcgMTguMTc1MyA3LjIyNjU3QzE3Ljc0ODUgNC4yMzYyNyAxNS4xNTIgMS45NTc5NCAxMi4wNTc2IDEuNzQ0MzRDMTEuOTE1MyAxLjcwODc1IDExLjc3MzEgMS43MDg3NSAxMS42MzA4IDEuNzA4NzVINS43NjIwNUM1LjI5OTY3IDEuNzA4NzUgNC45MDg0MiAxLjM1Mjc2IDQuOTA4NDIgMC44NTQzNzNDNC45MDg0MiAwLjM5MTU4OCA1LjI2NDEgMCA1Ljc2MjA1IDBIMTEuNjMwOEMxMy41NTE1IDAgMTUuMzY1NCAwLjY3NjM3OSAxNi43ODgyIDEuNzc5OTRDMTcuMzIxNyAyLjE3MTUzIDE3Ljc4NDEgMi42Njk5MiAxOC4yMTA5IDMuMTY4M1pNMTEuMTMyNSAxMy4yNzg1QzEwLjk1NDYgMTIuNzA4OSAxMC40NTY3IDEyLjM1MjkgOS44NTIwMyAxMi4zNTI5SDMuNDQ5NzhDMi45NTE4MyAxMi4zNTI5IDIuNTk2MTUgMTIuNzQ0NSAyLjU5NjE1IDEzLjIwNzNDMi41OTYxNSAxMy42NzAxIDIuOTg3MzkgMTQuMDYxNyAzLjQ0OTc4IDE0LjA2MTdIOC45MjcyNkgxMS4xMzI1QzExLjIwMzYgMTMuODQ4MSAxMS4yMzkyIDEzLjU2MzMgMTEuMTMyNSAxMy4yNzg1Wk0xMi4xOTk3IDUuMDE5NzVDMTIuMTk5NyA0LjUyMTM3IDExLjgwODQgNC4xNjUzOCAxMS4zNDYgNC4xNjUzOEgyLjU5NjNDMi4wOTgzNSA0LjE2NTM4IDEuNzQyNjcgNC41NTY5NyAxLjc0MjY3IDUuMDE5NzVDMS43NDI2NyA1LjQ4MjU0IDIuMTMzOTIgNS44NzQxMyAyLjU5NjMgNS44NzQxM0gxMS4zNDZDMTEuODA4NCA1Ljg3NDEzIDEyLjE5OTcgNS40ODI1NCAxMi4xOTk3IDUuMDE5NzVaTTEwLjIwOCA4LjI1OTE2QzEwLjY3MDQgOC4yNTkxNiAxMS4wNjE3IDguNjE1MTUgMTEuMDYxNyA5LjExMzUzQzExLjA2MTcgOS42MTE5MSAxMC42NzA0IDEwLjAwMzUgMTAuMjA4IDkuOTY3OUgwLjg1MzYzNEMwLjM5MTI0OSA5Ljk2NzkgMCA5LjYxMTkxIDAgOS4xMTM1M0MwIDguNjUwNzQgMC4zNTU2ODEgOC4yNTkxNiAwLjg1MzYzNCA4LjI1OTE2SDEwLjIwOFoiLz4KPC9zdmc+Cg=='
            );
            add_menu_page(
                __( 'MakeStories', MS_TEXT_DOMAIN ),
                __( 'MakeStories', MS_TEXT_DOMAIN ),
                $role,
                MS_ROUTING['DASHBOARD']['slug'],
                'ms_editor_contents',
                'data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIGZpbGw9IiNhMGE1YWEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgZmlsbD0iI2EwYTVhYSIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMTguMjEwOSAzLjE2ODNDMTkuMTAwMSA0LjI3MTg2IDE5LjcwNDcgNS41NTM0MiAxOS45MTgxIDcuMDEyOThDMjAuNDg3MiAxMC45NjQ1IDE3Ljk5NzUgMTQuNTU5OSAxNS4yOTQzIDE2LjI2ODdDMTQuNDQwNyAxNi44MzgzIDEwLjMxNDggMTkuMTg3OCA3Ljg5NjEzIDE5LjE4NzhDNy41MDQ4OSAxOS4xODc4IDcuMTQ5MjEgMTkuMTE2NiA2Ljg2NDY2IDE4Ljk3NDJMNi45MTYyNyAxOC45MTU3TDYuODI5MjcgMTkuMDA5NUM2LjcyMjU3IDE4Ljk3MzkgNi42NTE0MyAxOC45MzgzIDYuNTgwMyAxOC44NjcxQzYuMzMxMzIgMTguNjg5MSA1LjgzMzM3IDE4LjA4NCA2LjI5NTc1IDE3LjAxNkw2LjM2Njg5IDE2Ljg3MzZMOC44OTIyMiAxNC4wOTY5SDExLjA5NzRDMTEuMDI2MyAxNC4zNDYxIDEwLjg4NCAxNC41MjQxIDEwLjcwNjIgMTQuNzAyMUw4LjM5NDI3IDE3LjMwMDhMOC4yODIzMyAxNy40MzUyQzkuNTU3MDggMTcuMjYzOSAxMi4yNzE4IDE2LjIwOSAxNC4zMzQgMTQuODQ0N0MxNi41MzkyIDEzLjM4NTIgMTguNTMxIDEwLjUzNzMgMTguMjEwOSA3LjQ0MDE3QzE4LjE3NTMgNy4zNjg5NyAxOC4xNzUzIDcuMjk3NzcgMTguMTc1MyA3LjIyNjU3QzE3Ljc0ODUgNC4yMzYyNyAxNS4xNTIgMS45NTc5NCAxMi4wNTc2IDEuNzQ0MzRDMTEuOTE1MyAxLjcwODc1IDExLjc3MzEgMS43MDg3NSAxMS42MzA4IDEuNzA4NzVINS43NjIwNUM1LjI5OTY3IDEuNzA4NzUgNC45MDg0MiAxLjM1Mjc2IDQuOTA4NDIgMC44NTQzNzNDNC45MDg0MiAwLjM5MTU4OCA1LjI2NDEgMCA1Ljc2MjA1IDBIMTEuNjMwOEMxMy41NTE1IDAgMTUuMzY1NCAwLjY3NjM3OSAxNi43ODgyIDEuNzc5OTRDMTcuMzIxNyAyLjE3MTUzIDE3Ljc4NDEgMi42Njk5MiAxOC4yMTA5IDMuMTY4M1pNMTEuMTMyNSAxMy4yNzg1QzEwLjk1NDYgMTIuNzA4OSAxMC40NTY3IDEyLjM1MjkgOS44NTIwMyAxMi4zNTI5SDMuNDQ5NzhDMi45NTE4MyAxMi4zNTI5IDIuNTk2MTUgMTIuNzQ0NSAyLjU5NjE1IDEzLjIwNzNDMi41OTYxNSAxMy42NzAxIDIuOTg3MzkgMTQuMDYxNyAzLjQ0OTc4IDE0LjA2MTdIOC45MjcyNkgxMS4xMzI1QzExLjIwMzYgMTMuODQ4MSAxMS4yMzkyIDEzLjU2MzMgMTEuMTMyNSAxMy4yNzg1Wk0xMi4xOTk3IDUuMDE5NzVDMTIuMTk5NyA0LjUyMTM3IDExLjgwODQgNC4xNjUzOCAxMS4zNDYgNC4xNjUzOEgyLjU5NjNDMi4wOTgzNSA0LjE2NTM4IDEuNzQyNjcgNC41NTY5NyAxLjc0MjY3IDUuMDE5NzVDMS43NDI2NyA1LjQ4MjU0IDIuMTMzOTIgNS44NzQxMyAyLjU5NjMgNS44NzQxM0gxMS4zNDZDMTEuODA4NCA1Ljg3NDEzIDEyLjE5OTcgNS40ODI1NCAxMi4xOTk3IDUuMDE5NzVaTTEwLjIwOCA4LjI1OTE2QzEwLjY3MDQgOC4yNTkxNiAxMS4wNjE3IDguNjE1MTUgMTEuMDYxNyA5LjExMzUzQzExLjA2MTcgOS42MTE5MSAxMC42NzA0IDEwLjAwMzUgMTAuMjA4IDkuOTY3OUgwLjg1MzYzNEMwLjM5MTI0OSA5Ljk2NzkgMCA5LjYxMTkxIDAgOS4xMTM1M0MwIDguNjUwNzQgMC4zNTU2ODEgOC4yNTkxNiAwLjg1MzYzNCA4LjI1OTE2SDEwLjIwOFoiLz4KPC9zdmc+Cg=='
            );
        }
    }
}

add_action( 'admin_menu', 'mscpt_admin_menu' );

/**
 * Function for adding submenu
 */

include 'category-structure.php';

function mscpt_sub_menu()
{
    $addPageFor = ms_get_allowed_roles();
    global $submenu;
    $alreadyAdded = false;
    foreach ($addPageFor as $role) {
        if(!$alreadyAdded && current_user_can($role)) {
            $alreadyAdded = true;
            add_submenu_page(
                MS_ROUTING['DASHBOARD']['slug'], //Parent Slug
                'Settings', //Page Title
                'Settings', //Menu Title
                $role, //Capability
                'ms_settings', //menu_slug
                'ms_option_page' //callback
            );
            if(ms_is_categories_enabled()){
                $permalink = admin_url("edit-tags.php?taxonomy=ms_story_category");
                $submenu[MS_ROUTING['DASHBOARD']['slug']][] = array( 'Categories', $role, $permalink );
            }
            $permalink = admin_url("edit.php?post_type=makestories_story");
            $submenu[MS_ROUTING['DASHBOARD']['slug']][] = array( 'Published Stories', $role, $permalink );
        }
    }
}
add_action('admin_menu', 'mscpt_sub_menu');


add_action( 'admin_init', 'mscpt_amp_story_load_editor' );

function mscpt_amp_story_load_editor(){
    if(is_admin() && isset($_GET['page'])){
        $pagenow = sanitize_text_field($_GET['page']);
        if($pagenow === MS_ROUTING['EDITOR']['slug']){
            $subpage = isset($_GET['mspage']) ? sanitize_text_field($_GET['mspage']) : "homepage";
            if($subpage === "preview" && isset($_GET['story'])){
                $r = ms_get_story_HTML(sanitize_text_field($_GET['story']));
                $parsed = json_decode($r, true);
                if(is_array($parsed) && isset($parsed['html'])){
                    echo esc_html($parsed['html']);
                    die();
                }
            }
        }
    }
}

function ms_editor_contents() {
    $subpage = isset($_GET['mspage']) ? sanitize_text_field($_GET['mspage']) : "homepage";
    require_once(MS_PLUGIN_BASE_PATH."/templates/editor.php");
//    die();
}


add_filter("admin_body_class", "ms_folded_menu", 10, 1);

function ms_folded_menu($classes){
    if(is_admin() && isset($_GET['page'])){
        $pagenow = sanitize_text_field($_GET['page']);
        if($pagenow === MS_ROUTING['EDITOR']['slug'] || $pagenow === MS_ROUTING['DASHBOARD']['slug']){
            return $classes." folded";
        }
    }
    return $classes;
}
add_action("admin_head", "ms_editor_head");

function isMSEditorPage(){
    if(is_admin() && isset($_GET['page'])){
        $pagenow = sanitize_text_field($_GET['page']);
        return $pagenow === MS_ROUTING['EDITOR']['slug'] || $pagenow === MS_ROUTING['DASHBOARD']['slug'];
    }
    return false;
}
function isMSDashboardPage(){
    if(is_admin() && isset($_GET['page'])){
        $pagenow = sanitize_text_field($_GET['page']);
        return $pagenow === MS_ROUTING['DASHBOARD']['slug'];
    }
    return false;
}

function ms_editor_head(){
    if(isMSEditorPage()){
        require_once(MS_PLUGIN_BASE_PATH."/templates/editor-head.php");
    }
}

add_action("admin_footer", "ms_editor_footer");

function ms_editor_footer(){
    if(isMSDashboardPage()){
        require_once(MS_PLUGIN_BASE_PATH."/templates/editor-footer.php");
        wp_enqueue_script("ms_main_script_url", MS_DASHBOARD_MAIN_SCRIPT_URL, [], false, true);
    } else if(isMSEditorPage()){
        require_once(MS_PLUGIN_BASE_PATH."/templates/editor-footer.php");
        wp_enqueue_script("ms_vendor_script_url", MS_VENDOR_SCRIPT_URL, [], false, true);
        wp_enqueue_script("ms_main_script_url", MS_MAIN_SCRIPT_URL, [], false, true);
        wp_enqueue_style("ms_font_style_url", "https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700& display=swap");
        wp_enqueue_style("ms_main_style_url", MS_MAIN_STYLE_URL);
        wp_enqueue_style("ms_vendor_style_url", MS_VENDOR_STYLE_URL);
    }
}

//add_filter("admin_body_class", "my_folded_menu", 10, 1);
//
//function my_folded_menu($classes){
//    return $classes." folded";
//}

add_action("admin_notices", "ms_hide_admin_notices_for_editor", 1);
add_action("user_admin_notices", "ms_hide_admin_notices_for_editor", 1);
add_action("network_admin_notices", "ms_hide_admin_notices_for_editor", 1);
add_action("all_admin_notices", "ms_hide_admin_notices_for_editor_end", 1000);

function ms_hide_admin_notices_for_editor(){
    if(isMSEditorPage()){
        ob_start();
    }
}

function ms_hide_admin_notices_for_editor_end(){
    if(isMSEditorPage()){
        ob_end_clean();
    }
}

add_action( 'admin_enqueue_scripts', 'ms_enqueue_media' );

function ms_enqueue_media(){
    if(isMSEditorPage()){
        wp_enqueue_media();
    }
}

add_filter("get_edit_post_link", 'mscpt_wordpress_post_edit_link', 10, 2);
function mscpt_wordpress_post_edit_link($link, $post_id){
    $storyId = get_post_meta($post_id, "story_id", true);
    $post_type = get_post_type($post_id);
    if($post_type === MS_POST_TYPE && $storyId){
        return admin_url("admin.php?page=".MS_ROUTING['EDITOR']['slug']."&mspage=edit-story&storyId=".$storyId);
    }
    return $link;
}