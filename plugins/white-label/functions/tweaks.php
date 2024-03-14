<?php
/**
 *  Visual Tweaks.
 *
 * @package white-label
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Apply admin color scheme.
 *
 * @return void
 */
function white_label_admin_color_scheme()
{
    $wl_admin_color_scheme_enable = white_label_get_option('admin_color_scheme_enable', 'white_label_visual_tweaks', 'off');

    if ($wl_admin_color_scheme_enable == 'on') : ?>
    <?php
    $menu_background = esc_html(white_label_get_option('admin_color_scheme_menu_background', 'white_label_visual_tweaks', '#23282d')); // 282d23
    $menu_text = esc_html(white_label_get_option('admin_color_scheme_menu_text', 'white_label_visual_tweaks', '#ffffff')); // 1e4330
    $menu_highlight = esc_html(white_label_get_option('admin_color_scheme_menu_highlight', 'white_label_visual_tweaks', '#0073aa')); // 0c00aa
    $submenu_background = esc_html(white_label_get_option('admin_color_scheme_submenu_background', 'white_label_visual_tweaks', '#2c3338')); // 161913
    $submenu_text = esc_html(white_label_get_option('admin_color_scheme_submenu_text', 'white_label_visual_tweaks', hex_tint($menu_text, -10)));
    $submenu_highlight = esc_html(white_label_get_option('admin_color_scheme_submenu_highlight', 'white_label_visual_tweaks', $menu_highlight));
    $notifications = esc_html(white_label_get_option('admin_color_scheme_notifications', 'white_label_visual_tweaks', '#d54e21')); // d521cb
    $links = esc_html(white_label_get_option('admin_color_scheme_links', 'white_label_visual_tweaks', '#0073aa')); // 0caa00
    $links_focus = hex_tint($links, -10); // 10dd00
    $buttons = esc_html(white_label_get_option('admin_color_scheme_buttons', 'white_label_visual_tweaks', '#04a4cc')); // 1300aa
    $buttons_darken = hex_tint($buttons, -10);
    $form_fields = esc_html(white_label_get_option('admin_color_scheme_form_fields', 'white_label_visual_tweaks', '#2271b1')); // aa006d?>
    <style type="text/css">
    /* Links */a { color: <?php echo $links; ?>; } a:hover, a:active, a:focus { color: <?php echo $links_focus; ?>; } #post-body .misc-pub-post-status:before, #post-body #visibility:before, .curtime #timestamp:before, #post-body .misc-pub-revisions:before, span.wp-media-buttons-icon:before { color: currentColor; }
    /* Forms */ input[type=checkbox]:checked::before { content: url("data:image/svg+xml;utf8,%3Csvg%20xmlns%3D%27http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%27%20viewBox%3D%270%200%2020%2020%27%3E%3Cpath%20d%3D%27M14.83%204.89l1.34.94-5.81%208.38H9.02L5.78%209.67l1.34-1.25%202.57%202.4z%27%20fill%3D%27<?php echo urlencode($form_fields); ?>%27%2F%3E%3C%2Fsvg%3E"); } input[type=radio]:checked::before { background: <?php echo $form_fields; ?>; } .wp-core-ui input[type="reset"]:hover, .wp-core-ui input[type="reset"]:active { color: <?php echo $links_focus; ?>; } input[type="text"]:focus, input[type="password"]:focus, input[type="color"]:focus, input[type="date"]:focus, input[type="datetime"]:focus, input[type="datetime-local"]:focus, input[type="email"]:focus, input[type="month"]:focus, input[type="number"]:focus, input[type="search"]:focus, input[type="tel"]:focus, input[type="text"]:focus, input[type="time"]:focus, input[type="url"]:focus, input[type="week"]:focus, input[type="checkbox"]:focus, input[type="radio"]:focus, select:focus, textarea:focus { border-color: <?php echo $form_fields; ?>; box-shadow: 0 0 0 1px <?php echo $form_fields; ?>; }
    /* Core UI */ .wp-core-ui .button, .wp-core-ui .button-secondary { color: <?php echo $buttons; ?>; border-color: <?php echo $buttons; ?>; } .wp-core-ui .button.hover, .wp-core-ui .button:hover, .wp-core-ui .button-secondary:hover, .wp-core-ui .button.focus, .wp-core-ui .button:focus, .wp-core-ui .button-secondary:focus { border-color: <?php echo $buttons_darken; ?>; color: <?php echo $buttons_darken; ?>; } .wp-core-ui .button.focus, .wp-core-ui .button:focus, .wp-core-ui .button-secondary:focus { border-color: <?php echo $buttons; ?>; color: <?php echo $buttons_darken; ?>; box-shadow: 0 0 0 1px <?php echo $buttons; ?>; } .wp-core-ui .button:active { background: <?php echo $buttons_darken; ?>; border-color: <?php echo $buttons_darken; ?>; } .wp-core-ui .button.active, .wp-core-ui .button.active:focus, .wp-core-ui .button.active:hover { border-color: <?php echo $buttons_darken; ?>; color: <?php echo $buttons_darken; ?>; box-shadow: inset 0 2px 5px -3px <?php echo $buttons_darken; ?>; } .wp-core-ui .button-primary { background: <?php echo $buttons; ?>; border-color: <?php echo $buttons; ?>; color: #fff; } .wp-core-ui .button-primary:hover, .wp-core-ui .button-primary:focus { background: <?php echo $buttons_darken; ?>; border-color: <?php echo $buttons_darken; ?>; color: #fff; } .wp-core-ui .button-primary:focus { box-shadow: 0 0 0 1px #fff, 0 0 0 3px <?php echo $buttons; ?>; } .wp-core-ui .button-primary:active { background: <?php echo $buttons_darken; ?>; border-color: <?php echo $buttons_darken; ?>; color: #fff; } .wp-core-ui .button-primary.active, .wp-core-ui .button-primary.active:focus, .wp-core-ui .button-primary.active:hover { background: <?php echo $buttons; ?>; color: #fff; border-color: <?php echo $buttons_darken; ?>; box-shadow: inset 0 2px 5px -3px black; } .wp-core-ui .button-primary[disabled], .wp-core-ui .button-primary:disabled, .wp-core-ui .button-primary.button-primary-disabled, .wp-core-ui .button-primary.disabled { color: #c8c7d1 !important; background: #0e0081 !important; border-color: #0e0081 !important; text-shadow: none !important; } .wp-core-ui .button-group > .button.active { border-color: <?php echo $buttons; ?>; } .wp-core-ui .wp-ui-primary { color: <?php echo $menu_text; ?>; background-color: <?php echo $menu_background; ?>; } .wp-core-ui .wp-ui-text-primary { color: <?php echo $menu_background; ?>; } .wp-core-ui .wp-ui-highlight { color: <?php echo $menu_text; ?>; background-color: <?php echo $menu_highlight; ?>; } .wp-core-ui .wp-ui-text-highlight { color: <?php echo $menu_highlight; ?>; } .wp-core-ui .wp-ui-notification { color: <?php echo $menu_text; ?>; background-color: <?php echo $notifications; ?> } .wp-core-ui .wp-ui-text-notification { color: <?php echo $notifications; ?>; } .wp-core-ui .wp-ui-text-icon { color: <?php echo $menu_text; ?>; }
    /* List tables */ .wrap .add-new-h2:hover, .wrap .page-title-action:hover { color: <?php echo $menu_text; ?>; background-color: <?php echo $menu_background; ?>; } .view-switch a.current:before { color: <?php echo $menu_background; ?>; } .view-switch a:hover:before { color: <?php echo $notifications; ?>; }
    /* Admin Menu */ #adminmenuback, #adminmenuwrap, #adminmenu { background: <?php echo $menu_background; ?>; } #adminmenu a { color: <?php echo $menu_text; ?>; } #adminmenu div.wp-menu-image:before { color: <?php echo $menu_text; ?>; } #adminmenu a:hover, #adminmenu li.menu-top:hover, #adminmenu li.opensub > a.menu-top, #adminmenu li > a.menu-top:focus { color: <?php echo $menu_text; ?>; background-color: <?php echo $menu_highlight; ?>; } #adminmenu li.menu-top:hover div.wp-menu-image:before, #adminmenu li.opensub > a.menu-top div.wp-menu-image:before { color: <?php echo $menu_text; ?>; }
    /* Admin Menu: submenu */ #adminmenu .wp-submenu, #adminmenu .wp-has-current-submenu .wp-submenu, #adminmenu .wp-has-current-submenu.opensub .wp-submenu, .folded #adminmenu .wp-has-current-submenu .wp-submenu, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu { background: <?php echo $submenu_background; ?>; } #adminmenu li.wp-has-submenu.wp-not-current-submenu.opensub:hover:after { border-right-color: <?php echo $submenu_background; ?>; } #adminmenu .wp-submenu a, #adminmenu .wp-has-current-submenu .wp-submenu a, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu a, #adminmenu .wp-has-current-submenu.opensub .wp-submenu a { color: <?php echo $submenu_text; ?>; } #adminmenu .wp-submenu a:focus, #adminmenu .wp-submenu a:hover, #adminmenu .wp-has-current-submenu .wp-submenu a:focus, #adminmenu .wp-has-current-submenu .wp-submenu a:hover, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu a:focus, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu a:hover, #adminmenu .wp-has-current-submenu.opensub .wp-submenu a:focus, #adminmenu .wp-has-current-submenu.opensub .wp-submenu a:hover { color: <?php echo $submenu_highlight; ?>; }
    /* Admin Menu: current */ #adminmenu .wp-submenu li.current a, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a, #adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a { color: <?php echo $submenu_text; ?>; } #adminmenu .wp-submenu li.current a:hover, #adminmenu .wp-submenu li.current a:focus, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:hover, #adminmenu a.wp-has-current-submenu:focus + .wp-submenu li.current a:focus, #adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:hover, #adminmenu .wp-has-current-submenu.opensub .wp-submenu li.current a:focus { color: <?php echo $submenu_highlight; ?>; } #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, #adminmenu li.wp-has-current-submenu .wp-submenu .wp-submenu-head, .folded #adminmenu li.current.menu-top { color: <?php echo $submenu_text; ?>; background: <?php echo $submenu_highlight; ?>; } #adminmenu li.wp-has-current-submenu div.wp-menu-image:before, #adminmenu a.current:hover div.wp-menu-image:before, #adminmenu li.wp-has-current-submenu a:focus div.wp-menu-image:before, #adminmenu li.wp-has-current-submenu.opensub div.wp-menu-image:before, #adminmenu li:hover div.wp-menu-image:before, #adminmenu li a:focus div.wp-menu-image:before, #adminmenu li.opensub div.wp-menu-image:before, .ie8 #adminmenu li.opensub div.wp-menu-image:before { color: <?php echo $submenu_text; ?>; }
    /* Admin Menu: bubble */ #adminmenu .awaiting-mod, #adminmenu .update-plugins { color: <?php echo $menu_text; ?>; background: <?php echo $notifications; ?>; } #adminmenu li.current a .awaiting-mod, #adminmenu li a.wp-has-current-submenu .update-plugins, #adminmenu li:hover a .awaiting-mod, #adminmenu li.menu-top:hover > a .update-plugins { color: <?php echo $submenu_text; ?>; background: <?php echo $notifications; ?>; }
    /* Admin Menu: collapse button */ #collapse-button { color: <?php echo $menu_text; ?>; } #collapse-button:hover, #collapse-button:focus { color: <?php echo $menu_highlight; ?>; }
    /* Admin Bar */ #wpadminbar { color: <?php echo $menu_text; ?>; background: <?php echo $menu_background; ?>; } #wpadminbar .ab-item, #wpadminbar a.ab-item, #wpadminbar > #wp-toolbar span.ab-label, #wpadminbar > #wp-toolbar span.noticon { color: <?php echo $menu_text; ?>; } #wpadminbar .ab-icon, #wpadminbar .ab-icon:before, #wpadminbar .ab-item:before, #wpadminbar .ab-item:after { color: <?php echo $menu_text; ?>; } #wpadminbar:not(.mobile) .ab-top-menu > li:hover > .ab-item, #wpadminbar:not(.mobile) .ab-top-menu > li > .ab-item:focus, #wpadminbar.nojq .quicklinks .ab-top-menu > li > .ab-item:focus, #wpadminbar.nojs .ab-top-menu > li.menupop:hover > .ab-item, #wpadminbar .ab-top-menu > li.menupop.hover > .ab-item { color: <?php echo $menu_highlight; ?>; background: <?php echo $submenu_background; ?>; } #wpadminbar:not(.mobile) > #wp-toolbar li:hover span.ab-label, #wpadminbar:not(.mobile) > #wp-toolbar li.hover span.ab-label, #wpadminbar:not(.mobile) > #wp-toolbar a:focus span.ab-label { color: <?php echo $menu_highlight; ?>; } #wpadminbar:not(.mobile) li:hover .ab-icon:before, #wpadminbar:not(.mobile) li:hover .ab-item:before, #wpadminbar:not(.mobile) li:hover .ab-item:after, #wpadminbar:not(.mobile) li:hover #adminbarsearch:before { color: <?php echo $menu_text; ?>; }
    /* Admin Bar: submenu */ #wpadminbar .menupop .ab-sub-wrapper { background: <?php echo $submenu_background; ?>; } #wpadminbar .quicklinks .menupop ul.ab-sub-secondary, #wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu { background: #3a3d37; } #wpadminbar .quicklinks .menupop ul li a:hover, #wpadminbar .quicklinks .menupop ul li a:focus, #wpadminbar .quicklinks .menupop ul li a:hover strong, #wpadminbar .quicklinks .menupop ul li a:focus strong, #wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover > a, #wpadminbar .quicklinks .menupop.hover ul li a:hover, #wpadminbar .quicklinks .menupop.hover ul li a:focus, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus, #wpadminbar li:hover .ab-icon:before, #wpadminbar li:hover .ab-item:before, #wpadminbar li a:focus .ab-icon:before, #wpadminbar li .ab-item:focus:before, #wpadminbar li .ab-item:focus .ab-icon:before, #wpadminbar li.hover .ab-icon:before, #wpadminbar li.hover .ab-item:before, #wpadminbar li:hover #adminbarsearch:before, #wpadminbar li #adminbarsearch.adminbar-focused:before { color: <?php echo $submenu_highlight; ?>; } #wpadminbar .quicklinks li a:hover .blavatar, #wpadminbar .quicklinks li a:focus .blavatar, #wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover > a .blavatar, #wpadminbar .menupop .menupop > .ab-item:hover:before, #wpadminbar.mobile .quicklinks .ab-icon:before, #wpadminbar.mobile .quicklinks .ab-item:before { color: <?php echo $menu_highlight; ?>; } #wpadminbar.mobile .quicklinks .hover .ab-icon:before, #wpadminbar.mobile .quicklinks .hover .ab-item:before { color: <?php echo $submenu_text; ?>; }
    /* Admin Bar: search */ #wpadminbar #adminbarsearch:before { color: <?php echo $menu_text; ?>; } #wpadminbar > #wp-toolbar > #wp-admin-bar-top-secondary > #wp-admin-bar-search #adminbarsearch input.adminbar-input:focus { color: <?php echo $menu_text; ?>; background: #3a4133; }
    /* Admin Bar: recovery mode */ #wpadminbar #wp-admin-bar-recovery-mode { color: <?php echo $menu_text; ?>; background-color: <?php echo $notifications; ?>; } #wpadminbar #wp-admin-bar-recovery-mode .ab-item, #wpadminbar #wp-admin-bar-recovery-mode a.ab-item { color: <?php echo $menu_text; ?>; } #wpadminbar .ab-top-menu > #wp-admin-bar-recovery-mode.hover > .ab-item, #wpadminbar.nojq .quicklinks .ab-top-menu > #wp-admin-bar-recovery-mode > .ab-item:focus, #wpadminbar:not(.mobile) .ab-top-menu > #wp-admin-bar-recovery-mode:hover > .ab-item, #wpadminbar:not(.mobile) .ab-top-menu > #wp-admin-bar-recovery-mode > .ab-item:focus { color: <?php echo $menu_text; ?>; background-color: #c01eb7; }
    /* Admin Bar: my account */ #wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar > a img { border-color: #3a4133; background-color: #3a4133; } #wpadminbar #wp-admin-bar-user-info .display-name { color: <?php echo $menu_text; ?>; } #wpadminbar #wp-admin-bar-user-info a:hover .display-name { color: <?php echo $menu_highlight; ?>; }
    /* Pointers */ .wp-pointer .wp-pointer-content h3 { background-color: <?php echo $menu_highlight; ?>; border-color: #0a0091; } .wp-pointer .wp-pointer-content h3:before { color: <?php echo $menu_highlight; ?>; } .wp-pointer.wp-pointer-top .wp-pointer-arrow, .wp-pointer.wp-pointer-top .wp-pointer-arrow-inner, .wp-pointer.wp-pointer-undefined .wp-pointer-arrow, .wp-pointer.wp-pointer-undefined .wp-pointer-arrow-inner { border-bottom-color: <?php echo $menu_highlight; ?>; }
    /* Media */ .media-item .bar, .media-progress-bar div { background-color: <?php echo $menu_highlight; ?>; } .details.attachment { box-shadow: inset 0 0 0 3px #fff, inset 0 0 0 7px <?php echo $menu_highlight; ?>; } .attachment.details .check { background-color: <?php echo $menu_highlight; ?>; box-shadow: 0 0 0 1px #fff, 0 0 0 2px <?php echo $menu_highlight; ?>; } .media-selection .attachment.selection.details .thumbnail { box-shadow: 0 0 0 1px #fff, 0 0 0 3px <?php echo $menu_highlight; ?>; }
    /* Themes */ .theme-browser .theme.active .theme-name, .theme-browser .theme.add-new-theme a:hover:after, .theme-browser .theme.add-new-theme a:focus:after { background: <?php echo $menu_highlight; ?>; } .theme-browser .theme.add-new-theme a:hover span:after, .theme-browser .theme.add-new-theme a:focus span:after { color: <?php echo $menu_highlight; ?>; } .theme-section.current, .theme-filter.current { border-bottom-color: <?php echo $menu_background; ?>; } body.more-filters-opened .more-filters { color: <?php echo $menu_text; ?>; background-color: <?php echo $menu_background; ?>; } body.more-filters-opened .more-filters:before { color: <?php echo $menu_text; ?>; } body.more-filters-opened .more-filters:hover, body.more-filters-opened .more-filters:focus { background-color: <?php echo $menu_highlight; ?>; color: <?php echo $menu_text; ?>; } body.more-filters-opened .more-filters:hover:before, body.more-filters-opened .more-filters:focus:before { color: <?php echo $menu_text; ?>; }
    /* Widgets */ .widgets-chooser li.widgets-chooser-selected { background-color: <?php echo $menu_highlight; ?>; color: <?php echo $menu_text; ?>; } .widgets-chooser li.widgets-chooser-selected:before, .widgets-chooser li.widgets-chooser-selected:focus:before { color: <?php echo $menu_text; ?>; }
    /* Responsive Component */ div#wp-responsive-toggle a:before { color: <?php echo $menu_text; ?>; } .wp-responsive-open div#wp-responsive-toggle a { border-color: transparent; background: <?php echo $menu_highlight; ?>; } .wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle a { background: <?php echo $submenu_background; ?>; } .wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before { color: <?php echo $menu_text; ?>; }
    /* TinyMCE */ .mce-container.mce-menu .mce-menu-item:hover, .mce-container.mce-menu .mce-menu-item.mce-selected, .mce-container.mce-menu .mce-menu-item:focus, .mce-container.mce-menu .mce-menu-item-normal.mce-active, .mce-container.mce-menu .mce-menu-item-preview.mce-active { background: <?php echo $menu_highlight; ?>; }
    </style>
    <?php endif;
}

add_action('admin_head', 'white_label_admin_color_scheme');


/**
 * Remove the WP Logo in Admin Bar.
 *
 * @param mixed $wp_admin_bar admin bar menus.
 *
 * @return void
 */
function white_label_remove_wp_logo($wp_admin_bar)
{
    $admin_remove_wp_logo = white_label_get_option('admin_remove_wp_logo', 'white_label_visual_tweaks', false);
    $wl_admin_logo = white_label_get_option('admin_replace_wp_logo', 'white_label_visual_tweaks', false);

    // Remove WordPress Logo in Admin Bar.
    if ($admin_remove_wp_logo === 'on' || $wl_admin_logo) {
        if (!$wl_admin_logo) {
            $wp_admin_bar->remove_node('wp-logo');
        }

        $wp_admin_bar->remove_menu('about');
        $wp_admin_bar->remove_menu('contribute');
        $wp_admin_bar->remove_menu('wporg');
        $wp_admin_bar->remove_menu('documentation');
        $wp_admin_bar->remove_menu('learn');
        $wp_admin_bar->remove_menu('support-forums');
        $wp_admin_bar->remove_menu('feedback');
    }

    // $hidden_items = white_label_get_option( 'hidden_admin_bar_items', 'white_label_menus', false );
    // if ( empty( $hidden_items ) ) {
    // return;
    // }
    // foreach ( $hidden_items as $value ) {
    // $wp_admin_bar->remove_node( $value );
    // }
}

add_action('admin_bar_menu', 'white_label_remove_wp_logo', 999);

/**
 * Replace Howdy usename with any text.
 *
 * @param mixed $wp_admin_bar admin bar menus.
 *
 * @return void
 */
function white_label_change_howdy($wp_admin_bar)
{
    $white_label_admin_howdy = white_label_get_option('admin_howdy_replacment', 'white_label_visual_tweaks', false);

    if (!empty($white_label_admin_howdy)) {
        $wl_get_howdy = $wp_admin_bar->get_node('my-account');

        $wl_replacement = preg_replace('/^[^,]*,\s*/', $white_label_admin_howdy.' ', $wl_get_howdy->title);
        $wp_admin_bar->add_node(
            [
                'id' => 'my-account',
                'title' => $wl_replacement,
            ]
        );
    }
}
add_filter('admin_bar_menu', 'white_label_change_howdy', 50);

/**
 * Replace admin footer text
 *
 * @param string $default default footer text.
 *
 * @return string
 */
function white_label_admin_footer_credit($default)
{
    $new_footer = white_label_get_option('admin_footer_credit', 'white_label_visual_tweaks', false);

    if (!empty($new_footer)) {
        return $new_footer;
    }
    return $default;
}

add_filter('admin_footer_text', 'white_label_admin_footer_credit', 100);

/**
 * Remove WordPress version and upgrade notice
 *
 * @return void
 */
function white_label_admin_footer_upgrade()
{
    $admin_footer_upgrade = white_label_get_option('admin_footer_upgrade', 'white_label_visual_tweaks', false);

    if ($admin_footer_upgrade === 'on') {
        remove_filter( 'update_footer', 'core_update_footer' ); 
    }
}

add_action('admin_menu', 'white_label_admin_footer_upgrade');

/**
 * Add JS Scripts to the admin area.
 *
 * @return void
 */
function white_label_live_chat()
{
    $white_label_live_chat = white_label_get_option('admin_javascript', 'white_label_visual_tweaks', false);

    if (!empty($white_label_live_chat)) {
        echo $white_label_live_chat;
    }
}

add_action('admin_print_footer_scripts', 'white_label_live_chat');

/**
 * Replace admin bar logo with a custom one.
 *
 * @return void
 */
function white_label_admin_bar_logo()
{
    $wl_admin_logo = white_label_get_option('admin_replace_wp_logo', 'white_label_visual_tweaks', false);

    if ($wl_admin_logo) {
        echo '
<style type="text/css">
#wpadminbar #wp-admin-bar-wp-logo > .ab-item .ab-icon:before {
background-image: url('.esc_url($wl_admin_logo).') !important;
background-position: center;
color:rgba(0, 0, 0, 0);
background-size:cover;
}
#wpadminbar #wp-admin-bar-wp-logo.hover > .ab-item .ab-icon {
background-position: center;
background-size:cover;
}
</style>
';
    }
}
add_action('wp_before_admin_bar_render', 'white_label_admin_bar_logo', 90);

// Hat Tip: https://gist.github.com/GhostToast/9518787
function hex_tint($hex, $percentage = 0)
{
    if (empty($hex) || 0 == $percentage || !is_int($percentage)) {
        return $hex;
    }

    if ('#' == substr($hex, 0, 1)) {
        $temp_hex = trim($hex, '#');
        $trimmed = true;
    } else {
        $temp_hex = $hex;
        $trimmed = false;
    }

    if (3 == strlen($temp_hex)) {
        $temp_rgb = str_split($temp_hex, 1);
        $rgb = [
            $temp_rgb[0],
            $temp_rgb[0],
            $temp_rgb[1],
            $temp_rgb[1],
            $temp_rgb[2],
            $temp_rgb[2],
        ];
        $rgb = str_split(implode($rgb), 2);
    } elseif (6 == strlen($temp_hex)) {
        $rgb = str_split($temp_hex, 2);
    } else {
        return $hex;
    }

    foreach ($rgb as $key => $value) {
        $value = hexdec($value);
        $dechex_value = max(min(((($percentage / 100) * 255) + $value), 255), 0).'<br>';
        $dechex_value =  (int) $dechex_value;
        $value = str_pad(dechex($dechex_value), 2, '0', STR_PAD_LEFT);
        $rgb[$key] = $value;
    }

    $hex = ($trimmed ? '#' : '').implode($rgb);
    return $hex;
}
