<?php

add_action('admin_menu', 'conwr_settings_add_menu_item');

function conwr_settings_add_menu_item() {
    if (!conwr_menu_settings_get_item('sc-settings')) {
        add_menu_page('Content Writer - Settings', 'Content Writer', 'manage_options', 'sc-settings', 'conwr_render_options_page', CONWR_BASE_URL . 'assets/images/sc-logo20.png', 1);
    }

    $page = add_submenu_page('sc-settings', 'Content Writer - Settings', 'Content Writer', 'manage_options', 'sc-settings', 'conwr_render_options_page');

    if (!function_exists('remove_submenu_page')) {
        unset($GLOBALS['submenu']['sc-settings'][0]);
    } else {
        remove_submenu_page('sc-settings', 'sc-settings');
    }
}

//Checks if Content Writer admin menu or submenu exists
function conwr_menu_settings_get_item($handle, $sub = false) {
    if (!is_admin() || (defined('DOING_AJAX') && DOING_AJAX))
        return false;
    global $menu, $submenu;
    $check_menu = $sub ? $submenu : $menu;
    if (empty($check_menu))
        return false;
    foreach ($check_menu as $k => $item) {
        if ($sub) {
            foreach ($item as $sm) {
                if ($handle == $sm[2])
                    return true;
            }
        } else {
            if ($handle == $item[2])
                return true;
        }
    }
    return false;
}

?>