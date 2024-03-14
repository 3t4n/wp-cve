<?php


// Dynamic Admin CSS Styling
function ucd_dynamic_css() { ?>
    <style type="text/css">

    <?php // Start Admin Menu Styling
    $ucd_dashboard_accent = get_option('ucd_dashboard_accent');
    if ( !empty($ucd_dashboard_accent) ) { ?>
        #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu, #adminmenu li a.wp-has-current-submenu .update-plugins, #adminmenu li.current a .awaiting-mod, #wpadminbar {
            background: <?php echo get_option('ucd_dashboard_accent') ?> !important;
        }
        #adminmenu .wp-submenu a:focus, #adminmenu .wp-submenu a:hover, #adminmenu a:hover, #adminmenu li.menu-top>a:focus,#adminmenu li.menu-top:hover, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus, #adminmenu li a:focus div.wp-menu-image:before, #adminmenu li.opensub div.wp-menu-image:before, #adminmenu .wp-submenu li.current a, #adminmenu .wp-submenu a:hover,
        #adminmenu li div.wp-menu-image:before, #adminmenu .wp-menu-image img, #collapse-menu span,#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, #wpadminbar #wp-admin-bar-user-info .display-name, #wpadminbar .menupop:hover .display-name {
            color: <?php echo get_option('ucd_dashboard_accent') ?> !important;
        }
        @media screen and (max-width: 782px) {
        .wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before, #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before  {
            background: <?php echo get_option('ucd_dashboard_accent') ?> !important;
        }
        }
    <?php } ?>
    #adminmenu li a:focus div.wp-menu-image:before, #adminmenu li.opensub div.wp-menu-image:before {
        color: #eee;
    }
    #adminmenu li div.wp-menu-image:before, #adminmenu .wp-menu-image img {
      color: #eee !important;
    }
    <?php $ucd_dashboard_text_color = get_option('ucd_dashboard_text_color');
    if ( !empty($ucd_dashboard_text_color) ) { ?>
        #adminmenu li div.wp-menu-image:before, #adminmenu .wp-menu-image img, #collapse-menu span,#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, #wpadminbar #wp-admin-bar-user-info .display-name, #wpadminbar .menupop:hover .display-name,
        #wpadminbar .quicklinks .menupop ul li .ab-item, #wpadminbar .ab-topmenu>li.hover>.ab-item, #wpadminbar .ab-topmenu>li:hover>.ab-item, #wpadminbar .ab-topmenu>li.hover>.ab-item:focus, #wpadminbar ul#wp-admin-bar-root-default>li:hover span.ab-label {
            color: <?php echo get_option('ucd_dashboard_text_color') ?> !important;
        }
        #adminmenuwrap a,
        #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu:hover,
        .current.menu-top.menu-top-first.menu-top-last a :hover {
            color: #eee;
            color: <?php echo get_option('ucd_dashboard_text_color') ?> !important;
        }
    <?php }
    $ucd_dashboard_submenu_text_color = get_option('ucd_dashboard_submenu_text_color');
    if ( !empty($ucd_dashboard_submenu_text_color) ) { ?>
        #adminmenu .wp-submenu a {
            color: <?php echo get_option('ucd_dashboard_submenu_text_color') ?> !important;
        }
    <?php }
    $ucd_dashboard_background_dark = get_option('ucd_dashboard_background_dark');
    if ( !empty($ucd_dashboard_background_dark) ) { ?>
        #adminmenu, #adminmenuback, #adminmenuwrap,
        #wpadminbar .menupop .ab-sub-wrapper,
        #wpadminbar .quicklinks .menupop ul.ab-sub-secondary, #wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu, #adminmenu .toplevel_page_revslider {
            background: <?php echo get_option('ucd_dashboard_background_dark') ?> !important;
        }
        @media screen and (max-width: 782px) {
            .wp-has-submenu.wp-not-current-submenu.menu-top {
                background: <?php echo get_option('ucd_dashboard_background_dark') ?> !important;
            }
        }
    <?php }
    $ucd_dashboard_background_light = get_option('ucd_dashboard_background_light');
    if ( !empty($ucd_dashboard_background_light) ) { ?>
        #adminmenu .wp-submenu {
            background: <?php echo get_option('ucd_dashboard_background_light') ?> !important;
        }
        #adminmenu .wp-has-current-submenu .wp-submenu, #adminmenu .wp-has-current-submenu .wp-submenu.sub-open, #adminmenu .wp-has-current-submenu.opensub .wp-submenu, #adminmenu a.wp-has-current-submenu:focus+.wp-submenu, .no-js li.wp-has-current-submenu:hover .wp-submenu, #adminmenu div.wp-menu-name:active, #adminmenu li.opensub>a.menu-top,#adminmenu .wp-submenu li:hover, #wpadminbar .shortlink-input,#wpadminbar a.ab-item:hover, #adminmenu .toplevel_page_revslider:hover  {
            background: <?php echo get_option('ucd_dashboard_background_light') ?> !important;
        }
        #adminmenu li.menu-top:hover, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus {
            background: <?php echo get_option('ucd_dashboard_background_light') ?>;
        }
    <?php }
    $ucd_dashboard_link_text_color = get_option('ucd_dashboard_link_text_color');
    if ( !empty($ucd_dashboard_link_text_color) ) { ?>
    .wp-core-ui .button-primary {
        background: <?php echo get_option('ucd_dashboard_link_text_color') ?> !important;
        border-color: <?php echo get_option('ucd_dashboard_link_text_color') ?> !important;
        box-shadow: 0 1px 0 <?php echo get_option('ucd_dashboard_link_text_color') ?> !important;
        text-shadow: none!important;
    }
    a, .button-link.editinline {
        color: <?php echo get_option('ucd_dashboard_link_text_color') ?>;
    }
    <?php } ?>
    <?php // Start Admin Bar Styling
    $ucd_dashboard_admin_bar_text_color = get_option('ucd_dashboard_admin_bar_text_color');
    if ( !empty($ucd_dashboard_admin_bar_text_color) ) { ?>
        #wpadminbar span, #wpadminbar a.auto-fold #adminmenu div.wp-menu-image,
        #adminmenu li.current a.menu-top,
        #adminmenu li.wp-has-current-submenu div.wp-menu-image:before, #adminmenu li.current a.menu-top div.wp-menu-image:before,
        #wpadminbar .ab-icon, #wpadminbar .ab-item:before, #wpadminbar>#wp-toolbar>#wp-admin-bar-root-default .ab-icon,#wpadminbar #adminbarsearch:before, #wpadminbar .ab-icon:before, #wpadminbar .ab-item:before,
        #wpadminbar a.ab-item,
        #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .wp-menu-name:focus,
        #wpadminbar .ab-empty-item,
        #adminmenu li.wp-has-current-submenu:hover div.wp-menu-image:before {
            color: <?php echo get_option('ucd_dashboard_admin_bar_text_color') ?> !important;
        }
        @media screen and (max-width: 782px) {
        #wpadminbar .ab-icon, #wpadminbar .ab-item:before, #wpadminbar>#wp-toolbar>#wp-admin-bar-root-default .ab-icon,
        .wp-responsive-open #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before, #wpadminbar #wp-admin-bar-menu-toggle .ab-icon:before {
            color: <?php echo get_option('ucd_dashboard_admin_bar_text_color') ?> !important;
        }
        }
    <?php }

    $ucd_dashboard_accent = get_option('ucd_dashboard_accent');
    if ( !empty($ucd_dashboard_accent) ) { ?>
        #wpadminbar .ab-top-menu>#wp-admin-bar-my-account.hover>.ab-item span, #adminmenu li.current a.menu-top:hover div.wp-menu-image:before, #adminmenu li.current a.menu-top:hover,
        #wpadminbar span:hover, #wpadminbar a:hover,
        #wpadminbar .quicklinks .ab-sub-wrapper .menupop.hover>a, #wpadminbar .quicklinks .menupop ul li a:focus, #wpadminbar .quicklinks .menupop ul li a:focus strong, #wpadminbar .quicklinks .menupop ul li a:hover, #wpadminbar .quicklinks .menupop ul li a:hover strong, #wpadminbar .quicklinks .menupop.hover ul li a:focus, #wpadminbar .quicklinks .menupop.hover ul li a:hover, #wpadminbar .quicklinks .menupop.hover ul li div[tabindex]:focus, #wpadminbar .quicklinks .menupop.hover ul li div[tabindex]:hover, #wpadminbar li #adminbarsearch.adminbar-focused:before, #wpadminbar li .ab-item:focus .ab-icon:before, #wpadminbar li .ab-item:focus:before, #wpadminbar li a:focus .ab-icon:before, #wpadminbar li.hover .ab-icon:before, #wpadminbar li.hover .ab-item:before, #wpadminbar li:hover #adminbarsearch:before, #wpadminbar li:hover .ab-icon:before, #wpadminbar li:hover .ab-item:before, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:focus, #wpadminbar.nojs .quicklinks .menupop:hover ul li a:hover, #wpadminbar.mobile .quicklinks .hover .ab-icon:before, #wpadminbar.mobile .quicklinks .hover .ab-item:before,
        #wpadminbar #wp-admin-bar-user-info .display-name:hover,
        #wpadminbar .ab-top-menu>li.hover>.ab-item, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus, #wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item, #wpadminbar:not(.mobile) .ab-top-menu>li>.ab-item:focus,
        #wpadminbar .menupop .ab-sub-wrapper:hover,
        #wpadminbar .ab-top-menu>#wp-admin-bar-new-content.hover>.ab-item span,
        #wpadminbar .quicklinks .menupop ul li .ab-item span,
        #wpadminbar .quicklinks .menupop.hover .ab-item span {
            color: <?php echo get_option('ucd_dashboard_accent') ?> !important;
        }
    <?php }

    $ucd_dashboard_background_light = get_option('ucd_dashboard_background_light');
    if ( !empty($ucd_dashboard_background_light) ) { ?>
        #adminmenu li.current a.menu-top:hover,#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu:hover {
            background: <?php echo get_option('ucd_dashboard_background_light') ?> !important;
        }
    <?php }

    $ucd_dashboard_background_dark = get_option('ucd_dashboard_background_dark');
    if ( !empty($ucd_dashboard_background_dark) ) { ?>
        #wpadminbar .ab-top-menu>li.hover>.ab-item, #wpadminbar.nojq .quicklinks .ab-top-menu>li>.ab-item:focus, #wpadminbar:not(.mobile) .ab-top-menu>li:hover>.ab-item, #wpadminbar:not(.mobile) .ab-top-menu>li>.ab-item:focus,
        #wpadminbar .menupop .ab-sub-wrapper:hover {
            background: <?php echo get_option('ucd_dashboard_background_dark') ?> !important;
        }
    <?php } ?>

    <?php // Set widget row width
    $ucd_widget_rows = get_option('ucd_widget_rows');
    if (!empty($ucd_widget_rows)) { ?>
        @media (min-width: 720px) {
        #dashboard-widgets .postbox-container, #wpbody-content #dashboard-widgets.columns-4 .postbox-container {
            width: <?php echo get_option('ucd_widget_rows') ?>  !important;
        }
    }
    <?php } ?>
    #wp-admin-bar-ucd-landing-page-notice a.ab-item {
        color: #fff!important;
    }
    <?php // UCD widget 1 styling
    $ucd_widget_enabled = get_option('ucd_custom_widget');
    if ( !empty($ucd_widget_enabled) ) { ?>
        .ucd-widget-shortcode-one {
            margin-top: 23px;
            display: block;
            width: 100%;
        }
    <?php } ?>
    <?php // UCD widget 2 styling
    $ucd_widget_two_enabled = get_option('ucd_custom_widget_two');
    if ( !empty($ucd_widget_two_enabled) ) { ?>
        .ucd-widget-shortcode-two {
            margin-top: 23px;
            display: block;
            width: 100%;
        }
    <?php } ?>
    <?php // UCD widget 3 styling
    $ucd_widget_three_enabled = get_option('ucd_custom_widget_three');
    if ( !empty($ucd_widget_three_enabled) ) { ?>
        .ucd-widget-shortcode-three {
            margin-top: 23px;
            display: block;
            width: 100%;
        }
    <?php } ?>
    <?php // UCD widget 4 styling
    $ucd_widget_four_enabled = get_option('ucd_custom_widget_four');
    if ( !empty($ucd_widget_four_enabled) ) { ?>
        .ucd-widget-shortcode-four {
            margin-top: 23px;
            display: block;
            width: 100%;
        }
    <?php } ?>
    <?php // Modern Theme Styling
    $dashboard_modern_theme = get_option('ucd_dashboard_modern_theme');
    if (!empty($dashboard_modern_theme)) { ?>
    #adminmenu li.menu-top {
        <?php $ucd_border_bottom_status_secondary = get_option('ucd_dashboard_background_light');
        $ucd_border_bottom_status = get_option('ucd_dashboard_border_color');
        if (!empty($ucd_border_bottom_status_secondary) && $ucd_border_bottom_status == "" ) { ?>
            border-bottom: 1px solid <?php echo get_option('ucd_dashboard_background_light') ?>!important;
        <?php } else { ?>
            border-bottom:1px solid #333!important;
        <?php } ?>
        <?php $ucd_border_bottom_status = get_option('ucd_dashboard_border_color');
        if (!empty($ucd_border_bottom_status)) { ?>
            border-bottom:1px solid <?php echo get_option('ucd_dashboard_border_color') ?>!important;
        <?php } ?>
    }
    <?php } else {
        /* Display default admin styling */
    } ?>

    <?php // Enable Custom Welcome Message
    $ucd_message_disable = get_option('ucd_message_disable');
    if ( !empty($ucd_message_disable) ) { ?>
    .ucd-client-welcome {
        display: block!important;
    }
    <?php } ?>

    <?php
    $ucd_dashboard_admin_bar_screen_options = get_option('ucd_dashboard_admin_bar_screen_options');
    if ( !empty($ucd_dashboard_admin_bar_screen_options) ) {
        if (current_user_can('client')) { ?>
            #screen-meta-links .screen-meta-toggle {
                display: none!important;
            }
        <?php }
    } ?>

</style>
<?php }
add_action( 'admin_head', 'ucd_dynamic_css' );


// Pro Version Unlock Message
function ucd_pro_version_settings_message() {
if (!is_plugin_active('ultimate-client-dash-pro/ultimate-client-dash-pro.php') ) { ?>
<style type="text/css">
    .ucd-pro-version {
         background-color: #292c4b;
         color: #fff!important;
         pointer-events: none!important;
         padding-bottom: 6px!important;
    }
    .ucd-pro-version th, .ucd-pro-version p {
         color: #fff!important;
    }
    .ucd-pro-version:before {
         content: "Buy Pro version to unlock this feature";
         background-color: #303457;
         color: #fff!important;
         display: block!important;
         width: auto;
         overflow: hidden!important;
         margin-left: -35px;
         margin-right: -35px!important;
         text-align: center;
         padding: 11px 0px;
         margin-top: -7px;
         font-size: 13.5px!important;
         margin-bottom: 7px!important;
    }
    .ucd-shortcode-tr.ucd-pro-version {
         padding-bottom: 23px!important;
    }
    .ucd-shortcode-tr.ucd-pro-version:before {
         margin-bottom: 0px!important;
    }
    .ucd-dynamic-item.ucd-dynamic-pro {
         background-color: #292c4b!important;
         color: #fff!important;
         pointer-events: none!important;
         padding-bottom: 13px!important;
    }
    .ucd-dynamic-item.ucd-dynamic-pro:before {
         content: "Buy Pro version to unlock this feature";
         background-color: #303457;
         color: #fff!important;
         display: block!important;
         width: auto;
         overflow: hidden!important;
         margin-left: -34px;
         margin-right: -30px!important;
         text-align: center;
         padding: 11px 0px;
         margin-top: -14px;
         font-size: 13.5px!important;
         margin-bottom: 13px!important;
    }
    .ucd-inner-wrapper .ucd-dynamic-item.ucd-dynamic-pro .ucd-item-toggle {
        padding-top: 15.5px;
        padding-bottom: 12px;
        border-color: rgba(255, 255, 255, 0.15)!important;
    }
    .ucd-inner-wrapper .ucd-dynamic-item.ucd-dynamic-pro .ucd-item-toggle:before {
        top: 10px!important;
    }
    .ucd-inner-wrapper .ucd-dynamic-item.ucd-dynamic-pro.submenu:before {
        margin-left: -30px!important;
    }
    .ucd-inner-wrapper .ucd-dynamic-item.ucd-dynamic-pro .ucd-item-capabilities {
        top: 51px!important;
    }
</style>
<?php }
}
add_action( 'admin_head', 'ucd_pro_version_settings_message' );
