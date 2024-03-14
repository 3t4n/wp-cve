<?php
function cmicpt_add_menu_classes($items)
{
    $parent = false;
    $dataSettingName = cmicpt_get_settings_name();
    $cmicptData = json_decode(get_site_option($dataSettingName));
    $cmicptClass = cmicpt_get_classes();

    foreach ($items as $i => $menu_item) {
        if ($cmicptData) {
            foreach ($cmicptData as $postType => $postID) {
                if ($menu_item->object_id == $postID && get_post_type() == $postType) {
                    array_push($menu_item->classes, $cmicptClass['item']);
                    $parent = $menu_item->menu_item_parent;
                    $items[$i]->current = true;
                }
            }
        }

        if (get_post_type() != 'post' && $menu_item->object_id == get_site_option('page_for_posts')) {
            if (($key = array_search('current_page_parent', $menu_item->classes)) !== false) {
                unset($menu_item->classes[$key]);
            }
        }
    }
    while ($parent != 0) {
        foreach ($items as $i => $menu_item) {
            if ($menu_item->ID == $parent) {
                array_push($menu_item->classes, $cmicptClass['parent']);
                $parent = $menu_item->menu_item_parent;
                $items[$i]->current_item_parent = true;
            }
        }
    }

    return $items;
}
add_filter('wp_nav_menu_objects', 'cmicpt_add_menu_classes', 10, 1);

function cmicpt_get_classes()
{
    $cmicptClass = json_decode(get_site_option('cmicpt-class'), true);
    if (!isset($cmicptClass['item']) || empty($cmicptClass['item'])) {
        $cmicptClass['item'] = "current-menu-item";
    }

    if (!isset($cmicptClass['parent']) || empty($cmicptClass['parent'])) {
        $cmicptClass['parent'] = "current-menu-ancestor";
    }

    return $cmicptClass;
}
