<?php
/**
 *  Menus functionality.
 *
 * @package white-label
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Hide sidebar menus.
 *
 * @return void
 */
function white_label_hidden_sidebar_menus()
{
    // Exit early if WL admin.
    if (white_label_is_wl_admin()) {
        return;
    }

    $hidden_sidebar_menus = white_label_get_option('hidden_sidebar_menus', 'white_label_menus', false);

    if (empty($hidden_sidebar_menus)) {
        return;
    }

    global $menu;

    if ($menu && is_array($menu)) {
        // Hidden Sidebar Menus - Parents
        if (isset($hidden_sidebar_menus['parents']) && !empty($hidden_sidebar_menus['parents'])) {
            foreach ($hidden_sidebar_menus['parents'] as $item) {
                // Support for removing VC Bakery parent menu.
                if ($item === 'vc-welcome' || $item === 'vc-general') {
                    remove_menu_page('vc-general');
                    remove_menu_page('vc-welcome');
                    continue;
                }

                // Yoast adds a new menu for non-admin users, gross.
                if ($item == 'wpseo_dashboard') {
                    remove_menu_page('wpseo_workouts');
                }

                remove_menu_page($item);
            }
        }

        // Hidden Sidebar Menus - Children
        if (isset($hidden_sidebar_menus['children']) && !empty($hidden_sidebar_menus['children'])) {
            foreach ($hidden_sidebar_menus['children'] as $parent_key => $childen_array) {
                foreach ($childen_array as $child) {
                    $submenu_list = explode('_whitelabel_', $child);
                    $main_menu = $submenu_list[0];
                    $main_submenu = $submenu_list[1];

                    white_label_remove_submenu_page($main_menu, $main_submenu);
                }
            }
        }
    }
}

add_action('admin_menu', 'white_label_hidden_sidebar_menus', 9999999999);

/**
 * Remove an admin submenu.
 *
 * @global array $submenu
 *
 * @param string $parent_slug    The slug for the parent menu.
 * @param string $submenu_slug The slug of the submenu.
 * @return array|bool The removed submenu on success, false if not found.
 */
function white_label_remove_submenu_page($parent_slug, $submenu_slug)
{
    global $submenu;

    if (!isset($submenu[$parent_slug]) || !is_array($submenu[$parent_slug])) {
        return false;
    }

    $removed_submenus = [];

    foreach ($submenu[$parent_slug] as $i => $item) {
        $submenu_item = remove_query_arg('return', $item[2]);
        $submenu_item = sanitize_title($submenu_item);

        $removed = false;

        if ($submenu_slug === $submenu_item) {
            $removed = true;
        } elseif ($submenu_slug === $item[2]) {
            // Fallback to none sanitized name.
            $removed = true;
        }

        if ($removed == true) {
            $removed_submenus[] = $submenu_item;
            unset($submenu[$parent_slug][$i]);
        }
    }

    if ($parent_slug == 'woocommerce') {
        // Some WooCommerce submenu items will not work without the top option being available. This is a hack solution to move the top
        // option to the bottom of the submenu list and hide it from view.
        if (in_array('wc-admin', $removed_submenus)) {
            $submenu[$parent_slug][] = ['<style>.toplevel_page_woocommerce li:last-of-type { display: none !important; }</style>Home', 'read', 'wc-admin', ''];
        }
    }

    return false;
}

/**
 * Apply sidebar menu width.
 *
 * @return void
 */
function white_label_sidebar_menu_width()
{
    $white_label_sidebar_menu_width = white_label_get_option('sidebar_menu_width', 'white_label_menus', '');

    if (is_numeric($white_label_sidebar_menu_width) && $white_label_sidebar_menu_width >= 160) : ?>
    <style type="text/css">
    /* Sidebar Menu Width */ #wpcontent, #wpfooter { margin-left: <?php echo esc_attr($white_label_sidebar_menu_width); ?>px; } body.rtl #wpcontent, body.rtl #wpfooter { margin-left: 0; margin-right: <?php echo $white_label_sidebar_menu_width; ?>px; } #adminmenuback, #adminmenuwrap, #adminmenu, #adminmenu .wp-submenu { width: <?php echo $white_label_sidebar_menu_width; ?>px; } #adminmenu .wp-submenu { left: <?php echo $white_label_sidebar_menu_width; ?>px; } #adminmenu .wp-not-current-submenu .wp-submenu, .folded #adminmenu .wp-has-current-submenu .wp-submenu { min-width: <?php echo $white_label_sidebar_menu_width; ?>px; }
    </style>
    <?php endif;
}

add_action('admin_head', 'white_label_sidebar_menu_width');