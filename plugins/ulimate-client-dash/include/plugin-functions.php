<?php


// Function file that manages pro addons that use to be free.
require( plugin_dir_path( __FILE__ ) . '/addons/pro-addons.php');


// Upload image function
function ucd_load_media_files() {
    wp_enqueue_media();
}
add_action( 'admin_enqueue_scripts', 'ucd_load_media_files' );

add_action( 'admin_init', 'ucd_main_register_options' );
function ucd_main_register_options() {
    do_action( 'ucd_create_options' );
}


// Create custom welcome message template
function ucd_general_admin_notice(){
global $pagenow;
    if ( $pagenow == 'index.php' ) {
         echo '<div class="notice notice-info is-dismissible ucd-client-welcome">
         <h1 class="title-background">' . get_option('ucd_message_title') . '</h1>
         <div class="ucd-welcome-content">' . get_option( 'ucd_message_body' ) . '</div>
         </div>';
    }
}
add_action('admin_notices', 'ucd_general_admin_notice');


// Customize login logo styling
function ucd_login_logo() {
$ucd_login_logo = get_option('ucd_login_logo');
    if (!empty($ucd_login_logo)) { ?>
    <style type="text/css">
        body.login div#login h1 a {
            background-image: url(<?php echo get_option('ucd_login_logo') ?>);
            background-size: contain!important;
            background-repeat: no-repeat;
            padding-bottom: 5px;
            width: <?php echo get_option('ucd_logo_width') ?>!important;
            height: <?php echo get_option('ucd_logo_height') ?>!important;
            max-width: 320px!important;
        }
        .login h1 a {
            background-size: contain!important;
            background-repeat: no-repeat;
            width: <?php echo get_option('ucd_logo_width') ?>!important;
            height: <?php echo get_option('ucd_logo_height') ?>!important;
            max-width: 320px!important;
        }
    </style>
    <?php } else {}
}
add_action( 'login_enqueue_scripts', 'ucd_login_logo' );


// Customize login background styling
function ucd_login_footer() { ?>
    <style type="text/css" media="screen">
        <?php
        $ucd_login_background_color = get_option('ucd_login_background_color');
        $ucd_login_background_image = get_option('ucd_login_background_image');
        if (!empty($ucd_login_background_color) || !empty($ucd_login_background_image)) { ?>
            body {
                <?php if (!empty($ucd_login_background_color)) { ?> background: <?php echo get_option('ucd_login_background_color') ?>!important; <?php } ?>
                <?php if (!empty($ucd_login_background_image)) { ?> background-image: url(<?php echo get_option('ucd_login_background_image') ?>)!important; <?php } ?>
                <?php if (!empty($ucd_login_background_image)) { ?> background-size: cover!important; <?php } ?>
                <?php if (!empty($ucd_login_background_image)) { ?> background-repeat: repeat; <?php } ?>
            }
        <?php }
        $ucd_dashboard_accent = get_option('ucd_dashboard_accent');
        if ( !empty($ucd_dashboard_accent) ) { ?>
        input[type=text]:focus,
        input[type=search]:focus,
        input[type=radio]:focus,
        input[type=tel]:focus,
        input[type=time]:focus,
        input[type=url]:focus,
        input[type=week]:focus,
        input[type=password]:focus,
        input[type=checkbox]:focus,
        input[type=color]:focus,
        input[type=date]:focus,
        input[type=datetime]:focus,
        input[type=datetime-local]:focus,
        input[type=email]:focus,
        input[type=month]:focus,
        input[type=number]:focus,
        select:focus,
        textarea:focus {
            border-color: <?php echo get_option('ucd_dashboard_accent') ?> !important;
            box-shadow: 0 0 2px <?php echo get_option('ucd_dashboard_accent') ?> !important;
        }
        .wp-core-ui .button-group.button-large .button, .wp-core-ui .button.button-large {
            background: <?php echo get_option('ucd_dashboard_accent') ?> !important;
            border-color: <?php echo get_option('ucd_dashboard_accent') ?> !important;
            box-shadow: 0 1px 0 <?php echo get_option('ucd_dashboard_accent') ?> !important;
            text-shadow: none!important;
        }
        <?php }
        $ucd_login_hide_password = get_option('ucd_login_hide_password_link');
        if ( !empty($ucd_login_hide_password) ) { ?>
        .login #nav {
            display: none!important;
        }
        <?php }
        $ucd_login_hide_main_link = get_option('ucd_login_hide_main_site_link');
        if ( !empty($ucd_login_hide_main_link) ) { ?>
        .login #backtoblog a {
            display: none!important;
        }
        <?php }
        $ucd_login_body_text_color = get_option('ucd_login_body_text_color');
        if ( !empty($ucd_login_body_text_color) ) { ?>
        #nav a, #backtoblog a, .privacy-policy-link {
            color: <?php echo get_option('ucd_login_body_text_color') ?>!important;
        }
        .ucd-login-custom-content {
            color: <?php echo get_option('ucd_login_body_text_color') ?>!important;
            padding-top: 40px!important;
            width: 320px!important;
            margin: 0 auto!important;
            display: block!important;
        }
        .ucd-login-custom-content a {
            color: <?php echo get_option('ucd_login_body_text_color') ?>!important;
        }
        <?php }
        $ucd_login_overlay_color = get_option('ucd_login_overlay_color');
        if (!empty($ucd_login_overlay_color)) { ?>
        body:before {
            content: " ";
            width: 100%;
            height: 100%;
            position: fixed;
            z-index: -1;
            top: 0;
            left: 0;
            background: <?php echo get_option('ucd_login_overlay_color') ?>;
            opacity: <?php echo get_option('ucd_login_overlay_opacity') ?>;
        }
        <?php }
        $ucd_logo_padding_bottom = get_option('ucd_logo_padding_bottom');
        if ( !empty($ucd_logo_padding_bottom) ) { ?>
        body.login div#login h1 a {
            padding-bottom: <?php echo get_option('ucd_logo_padding_bottom') ?> !important;
        }
        <?php } ?>
  	</style>
<?php
}
add_action('login_footer', 'ucd_login_footer');


// Customize login logo url
function ucd_login_logo_url() {
$ucd_login_logo_url = get_option('ucd_login_logo_url');
    if (!empty($ucd_login_logo_url)) {
        return "$ucd_login_logo_url";
    } else {
        return home_url();
    }
}
add_filter( 'login_headerurl', 'ucd_login_logo_url' );


// Customize login logo title
function ucd_login_logo_url_title() {
$ucd_login_logo_title = get_option('ucd_login_logo_title');
    if (!empty($ucd_login_logo_title)) {
        return "$ucd_login_logo_title";
    } else {
        return 'powered by Ultimate Client Dash';
    }
}
add_filter( 'login_headertext', 'ucd_login_logo_url_title' );


// Change howdy text on admin bar
function ucd_howdy_message($text) {
$ucd_howdy_text_change = get_option('ucd_howdy_text');
    if (!empty($ucd_howdy_text_change)) {
        $new_message = str_replace('Howdy', "$ucd_howdy_text_change", $text);
    } else {
        $new_message = str_replace('Howdy', "Howdy", $text);
    }
return $new_message;
}
add_filter('gettext', 'ucd_howdy_message', 10, 3);


// Creates Custom Widget 1
add_action('wp_dashboard_setup', 'ucd_create_custom_widget');
function ucd_create_custom_widget() {
global $wp_meta_boxes;
$ucd_widget_title_text = get_option('ucd_widget_title');
$ucd_widget_enabled = get_option('ucd_custom_widget');
    if (!empty($ucd_widget_enabled)) {
        wp_add_dashboard_widget('ucd_widget', " $ucd_widget_title_text  ", 'ucd_custom_widget');
    }
}
function ucd_custom_widget() {
    $ucd_widget_one_shortcode = get_option('ucd_custom_widget_shortcode');
        echo '<div class="ucd-custom-widget-content">' . get_option( 'ucd_widget_body' ) . '</div>
        <div class="ucd-widget-shortcode-one">' . do_shortcode("$ucd_widget_one_shortcode") . '</div>';
}


// Creates Custom Widget 2
add_action('wp_dashboard_setup', 'ucd_create_custom_widget_two');
function ucd_create_custom_widget_two() {
global $wp_meta_boxes;
$ucd_widget_two_title_text = get_option('ucd_widget_two_title');
$ucd_widget_two_enabled = get_option('ucd_custom_widget_two');
$ucd_widget_count = get_option('ucd_custom_widget_count');
    if ($ucd_widget_count !== "one") {
        if ($ucd_widget_count == "two" || "three" || "four") {
            if (!empty($ucd_widget_two_enabled)) {
                wp_add_dashboard_widget('ucd_widget_two', " $ucd_widget_two_title_text  ", 'ucd_custom_widget_two');
            }
        }
    }
}
function ucd_custom_widget_two() {
    $ucd_widget_two_shortcode = get_option('ucd_custom_widget_two_shortcode');
    echo '<div class="ucd-custom-widget-content">' . get_option( 'ucd_widget_two_body' ) . '</div>
    <div class="ucd-widget-shortcode-two">' . do_shortcode("$ucd_widget_two_shortcode") . '</div>';
}


// Create Custom Widget 3
add_action('wp_dashboard_setup', 'ucd_create_custom_widget_three');
function ucd_create_custom_widget_three() {
global $wp_meta_boxes;
$ucd_widget_three_title_text = get_option('ucd_widget_three_title');
$ucd_widget_three_enabled = get_option('ucd_custom_widget_three');
$ucd_widget_count = get_option('ucd_custom_widget_count');
    if ($ucd_widget_count !== "one") {
        if ($ucd_widget_count !== "two") {
            if ($ucd_widget_count == "three" || "four") {
                if (!empty($ucd_widget_three_enabled)) {
                    wp_add_dashboard_widget('ucd_widget_three', " $ucd_widget_three_title_text  ", 'ucd_custom_widget_three');
                }
            }
        }
    }
}
function ucd_custom_widget_three() {
    $ucd_widget_three_shortcode = get_option('ucd_custom_widget_three_shortcode');
    echo '<div class="ucd-custom-widget-content">' . get_option( 'ucd_widget_three_body' ) . '</div>
    <div class="ucd-widget-shortcode-three">' . do_shortcode("$ucd_widget_three_shortcode") . '</div>';
}


// Create Custom Widget 4
add_action('wp_dashboard_setup', 'ucd_create_custom_widget_four');
function ucd_create_custom_widget_four() {
global $wp_meta_boxes;
$ucd_widget_four_title_text = get_option('ucd_widget_four_title');
$ucd_widget_four_enabled = get_option('ucd_custom_widget_four');
$ucd_widget_count = get_option('ucd_custom_widget_count');
    if ($ucd_widget_count == "four") {
        if (!empty($ucd_widget_four_enabled)) {
            wp_add_dashboard_widget('ucd_widget_four', " $ucd_widget_four_title_text  ", 'ucd_custom_widget_four');
        }
    }
}

function ucd_custom_widget_four() {
    $ucd_widget_four_shortcode = get_option('ucd_custom_widget_four_shortcode');
    echo '<div class="ucd-custom-widget-content">' . get_option( 'ucd_widget_four_body' ) . '</div>
    <div class="ucd-widget-shortcode-four">' . do_shortcode("$ucd_widget_four_shortcode") . '</div>';
}


// Adds Custom CSS code to website frontend
function ucd_add_custom_css(){
$ucd_custom_css = get_option('ucd_tracking_custom_css'); // GA Property ID
    if (!empty($ucd_custom_css)) { ?>
        <!-- Ultimate Client Dashboard Custom CSS -->
        <style type="text/css">
            <?php echo $ucd_custom_css; ?>
        </style>
    <?php }
}
add_action('wp_head', 'ucd_add_custom_css');


// Add Custom JS code to header
function ucd_add_custom_js(){
$ucd_custom_js = get_option('ucd_tracking_custom_js'); // GA Property ID
    if (!empty($ucd_custom_js)) { ?>
        <!-- Ultimate Client Dashboard Custom JS -->
        <script type="text/javascript">
            <?php echo $ucd_custom_js; ?>
        </script>
<?php }
}
add_action('wp_head', 'ucd_add_custom_js');


// Add Custom Script to header
function ucd_add_custom_script(){
$ucd_custom_script = get_option('ucd_tracking_custom_script');
    if (!empty($ucd_custom_script)) { ?>
        <!-- Ultimate Client Dashboard Custom Script -->
        <?php echo $ucd_custom_script; ?>
    <?php }
}
add_action('wp_head', 'ucd_add_custom_script');


// Activate WordPress landing Page mode
function ucd_landing_page_mode(){
global $pagenow;
$ucd_enable_constructions = get_option('ucd_under_construction_disable');
    if (!empty($ucd_enable_constructions)) {
        if ( $pagenow !== 'wp-login.php' && !is_user_logged_in() && !is_admin() ) {
        		header( $_SERVER["SERVER_PROTOCOL"] . ' 503 Service Temporarily Unavailable', true, 503 );
        		header( 'Content-Type: text/html; charset=utf-8' );
        		if ( file_exists( plugin_dir_path( __FILE__ ) . 'templates/ucd-landing-page.php' ) ) {
        			require_once( plugin_dir_path( __FILE__ ) . 'templates/ucd-landing-page.php' );
        		}
        		die();
      	}
    }
    else {
      // Don't display landing page
    }
}
add_action('wp_loaded', 'ucd_landing_page_mode');


// Preview landing Page function
add_filter( 'template_include', 'ucd_landing_page_template', 99 );
function ucd_landing_page_template( $template ) {
    if(isset($_GET['ucd-preview-landing-page']) && current_user_can('read')) {
        return( plugin_dir_path( __FILE__ ) . 'templates/ucd-landing-page.php' );
    }
    return $template;
}


// Preview Login Page function
add_filter( 'template_include', 'ucd_login_page_template', 99 );
function ucd_login_page_template( $template ) {
    if(isset($_GET['ucd-preview-login-page']) && current_user_can('read')) {
        return( 'wp-login.php' );
    }
    return $template;
}


// Add custom text to login page footer
function ucd_custom_login_content_footer() {
$ucd_login_footer_content_enabled = get_option('ucd_login_custom_content');
    if (!empty($ucd_login_footer_content_enabled)) { ?>
        <div class="ucd-login-custom-content">
        <?php echo get_option('ucd_login_custom_content') ?>
        </div>
    <?php }
}
add_action('login_footer','ucd_custom_login_content_footer');


// Change Admin Footer Text
function ucd_admin_footer_text () {
$ucd_admin_footer_text = get_option( 'ucd_admin_footer_text' );
    if (!empty($ucd_admin_footer_text)) {
        echo "$ucd_admin_footer_text";
    } else {
        $ucd_white_label_wordpress = get_option( 'ucd_dashboard_hide_wp_logo' );
        if (empty($ucd_white_label_wordpress)) {
            echo '<span id="footer-thankyou">Thank you for creating with <a href="https://wordpress.org/">WordPress</a>.</span>';
        }
    }
}
add_filter('admin_footer_text', 'ucd_admin_footer_text');


// Remove default WordPress widgets from dashboard
function ucd_remove_default_dashboard_widgets() {
    // Welcome Panel Widget
    $welcome_panel = get_option( 'ucd_widget_welcome' );
    if (!empty($welcome_panel)) { remove_action( 'welcome_panel','wp_welcome_panel' ); } // Welcome Panel
    // Try Gluentberg Widget
    $try_gutenberg_panel = get_option( 'ucd_widget_glutenberg' );
    if (!empty($try_gutenberg_panel)) { remove_action( 'try_gutenberg_panel', 'wp_try_gutenberg_panel'); } // Try Gutenberg
    // Wordpress Events & News Widget
    $dashboard_primary = get_option( 'ucd_widget_primary' );
    if (!empty($dashboard_primary)) { remove_meta_box( 'dashboard_primary','dashboard','side' ); } // Wordpress Events & News
    // Other WordPress News Widget
    $dashboard_secondary = get_option( 'ucd_widget_secondary' );
    if (!empty($dashboard_secondary)) { remove_meta_box('dashboard_secondary','dashboard','side'); } // Other WordPress News
    // Activity Widget
    $dashboard_activity = get_option( 'ucd_widget_activity' );
    if (!empty($dashboard_activity)) { remove_meta_box('dashboard_activity','dashboard', 'normal'); } // Activity
    // Right Now Widget
    $dashboard_right_now = get_option( 'ucd_widget_glance' );
    if (!empty($dashboard_right_now)) { remove_meta_box('dashboard_right_now','dashboard', 'normal' ); } // Right Now
    // Quick Draft Widget
    $dashboard_quick_press = get_option( 'ucd_widget_draft' );
    if (!empty($dashboard_quick_press)) { remove_meta_box('dashboard_quick_press','dashboard','side'); } // Quick Draft
    // Recent Drafts Widget
    $dashboard_recent_drafts = get_option( 'ucd_widget_recent_drafts' );
    if (!empty($dashboard_recent_drafts)) { remove_meta_box('dashboard_recent_drafts','dashboard','side'); } // Recent Drafts
    // Incoming Links Widget
    $dashboard_incoming_links = get_option( 'ucd_widget_incoming_links' );
    if (!empty($dashboard_incoming_links)) { remove_meta_box('dashboard_incoming_links','dashboard','normal'); } // Incoming Links
    // Recent Comments Widget
    $dashboard_recent_comments = get_option( 'ucd_widget_recent_comments' );
    if (!empty($dashboard_recent_comments)) { remove_meta_box('dashboard_recent_comments','dashboard','normal'); } // Recent Comments
    // WooCommerce Widgets
    $ucd_widget_woocommerce = get_option( 'ucd_widget_woocommerce' );
    if (!empty($ucd_widget_woocommerce)) { remove_meta_box( 'woocommerce_dashboard_status', 'dashboard', 'normal'); remove_meta_box( 'woocommerce_dashboard_recent_reviews', 'dashboard', 'normal'); } // WooCommerce
    // Gravity Forms Widgets
    $rg_forms_dashboard = get_option( 'ucd_widget_gravity_forms' );
    if (!empty($rg_forms_dashboard)) { remove_meta_box('rg_forms_dashboard','dashboard','normal'); } // Gravity Forms
    // Yoast SEO Widget
    $yoast_db_widget = get_option( 'ucd_widget_yoast' );
    if (!empty($yoast_db_widget)) { remove_meta_box('yoast_db_widget','dashboard','normal'); } // Yoast SEO
    // Elementor Widget
    $e_dashboard_overview = get_option( 'ucd_widget_elementor' );
    if (!empty($e_dashboard_overview)) { remove_meta_box( 'e-dashboard-overview', 'dashboard', 'normal'); } // Elementor Overview
    // Site Health Widget
    $ucd_widget_site_health = get_option( 'ucd_widget_site_health' );
    if (!empty($ucd_widget_site_health)) { remove_meta_box( 'dashboard_site_health', 'dashboard', 'normal'); } // Site Health
    // PHP Update Required Widget
    $ucd_widget_php_update = get_option( 'ucd_widget_php_update' );
    if (!empty($ucd_widget_php_update)) { remove_meta_box( 'dashboard_php_nag', 'dashboard', 'normal'); } // Site Health

}
add_action('wp_dashboard_setup', 'ucd_remove_default_dashboard_widgets', 20);
add_action('wp_user_dashboard_setup', 'ucd_remove_default_dashboard_widgets', 20);


// Function to remove help tab from the WordPress dashboard
function ucd_remove_help_tab(){
    $ucd_dashboard_admin_bar_help = get_option('ucd_dashboard_admin_bar_help');
    if (!empty($ucd_dashboard_admin_bar_help)) {
        if (current_user_can('client')) {
            $screen = get_current_screen();
            $screen->remove_help_tabs();
        }
    }
}
add_action('admin_head', 'ucd_remove_help_tab');

// Remove WordPress Footer Version
function ucd_remove_wordpress_footer_version() {
    $ucd_admin_footer_version = get_option('ucd_admin_footer_version');
    if (!empty($ucd_admin_footer_version)) {
        remove_filter( 'update_footer', 'core_update_footer' );
    }
}
add_action( 'admin_menu', 'ucd_remove_wordpress_footer_version' );


// Remove WordPress admin bar links
function ucd_remove_admin_bar_content() {
    global $wp_admin_bar;
    $ucd_dashboard_hide_wp_logo = get_option('ucd_dashboard_hide_wp_logo');
    if (!empty($ucd_dashboard_hide_wp_logo)) {
        $wp_admin_bar->remove_node( 'wp-logo' ); // Remove the WordPress logo
    }
    $ucd_dashboard_admin_bar_updates_link = get_option('ucd_dashboard_admin_bar_updates_link');
    if (!empty($ucd_dashboard_admin_bar_updates_link)) {
        if ( current_user_can('client') ) {
            $wp_admin_bar->remove_menu('updates'); // Remove the updates link
        }
    }
    $ucd_dashboard_admin_bar_comments_link = get_option('ucd_dashboard_admin_bar_comments_link');
    if (!empty($ucd_dashboard_admin_bar_comments_link)) {
        if ( current_user_can('client') ) {
            $wp_admin_bar->remove_menu('comments'); // Remove the comments link
        }
    }
    $ucd_dashboard_admin_bar_add_new_Menu = get_option('ucd_dashboard_admin_bar_add_new_Menu');
    if (!empty($ucd_dashboard_admin_bar_add_new_Menu)) {
        if ( current_user_can('client') ) {
            $wp_admin_bar->remove_menu('new-content'); // Remove the content link
        }
    }
}
add_action( 'admin_bar_menu', 'ucd_remove_admin_bar_content', 999 );


// Apply branding to frontend admin bar
function ucd_admin_bar_frontend_styling(){
// If admin bar frontend styling is enabled
$ucd_misc_admin_bar_frontend_styling = get_option('ucd_misc_admin_bar_frontend_styling');
if ( empty($ucd_misc_admin_bar_frontend_styling) ) {
    // Only add styling when user is logged in to help with site optimization
    if ( is_user_logged_in() ) { ?>
        <style>
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
                #adminmenu .wp-has-current-submenu .wp-submenu .wp-submenu-head, #adminmenu .wp-menu-arrow, #adminmenu .wp-menu-arrow div, #adminmenu li.current a.menu-top, #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, .folded #adminmenu li.current.menu-top, .folded #adminmenu li.wp-has-current-submenu, #adminmenu li a.wp-has-current-submenu .update-plugins, #adminmenu li.current a .awaiting-mod, #wpadminbar {
                    background: <?php echo get_option('ucd_dashboard_accent') ?> !important;
                }
            <?php }
            $ucd_dashboard_background_light = get_option('ucd_dashboard_background_light');
            if ( !empty($ucd_dashboard_background_light) ) { ?>
                #adminmenu .wp-submenu {
                    background: <?php echo get_option('ucd_dashboard_background_light') ?> !important;
                }
                #adminmenu .wp-has-current-submenu .wp-submenu, #adminmenu .wp-has-current-submenu .wp-submenu.sub-open, #adminmenu .wp-has-current-submenu.opensub .wp-submenu, #adminmenu a.wp-has-current-submenu:focus+.wp-submenu, .no-js li.wp-has-current-submenu:hover .wp-submenu, #adminmenu div.wp-menu-name:active, #adminmenu li.opensub>a.menu-top,#adminmenu .wp-submenu li:hover, #wpadminbar .shortlink-input,#wpadminbar a.ab-item:hover  {
                    background: <?php echo get_option('ucd_dashboard_background_light') ?> !important;
                }
                #adminmenu li.menu-top:hover, #adminmenu li.opensub>a.menu-top, #adminmenu li>a.menu-top:focus {
                    background: <?php echo get_option('ucd_dashboard_background_light') ?>;
                }
            <?php }
            $ucd_dashboard_background_dark = get_option('ucd_dashboard_background_dark');
            if ( !empty($ucd_dashboard_background_dark) ) { ?>
                #adminmenu, #adminmenuback, #adminmenuwrap,
                #wpadminbar .menupop .ab-sub-wrapper,
                #wpadminbar .quicklinks .menupop ul.ab-sub-secondary, #wpadminbar .quicklinks .menupop ul.ab-sub-secondary .ab-submenu {
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
            <?php $ucd_dashboard_text_color = get_option('ucd_dashboard_text_color');
            if ( !empty($ucd_dashboard_text_color) ) { ?>
                #adminmenu li div.wp-menu-image:before, #adminmenu .wp-menu-image img, #collapse-menu span,#adminmenu li.wp-has-current-submenu a.wp-has-current-submenu, #wpadminbar #wp-admin-bar-user-info .display-name, #wpadminbar .menupop:hover .display-name,
                #wpadminbar .quicklinks .menupop ul li .ab-item, #wpadminbar .ab-topmenu>li.hover>.ab-item, #wpadminbar .ab-topmenu>li:hover>.ab-item, #wpadminbar .ab-topmenu>li.hover>.ab-item:focus, #wpadminbar ul#wp-admin-bar-root-default>li:hover span.ab-label {
                    color: <?php echo get_option('ucd_dashboard_text_color') ?> !important;
                }
                #adminmenuwrap a,
                #adminmenu li.wp-has-current-submenu a.wp-has-current-submenu:hover,
                .current.menu-top.menu-top-first.menu-top-last a :hover {
                    color: <?php echo get_option('ucd_dashboard_text_color') ?> !important;
                }
            <?php } ?>
            #wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar>a img {
                float:left;
                margin:5px 15px;
                width:20px;
                height:20px;
                border:1px solid #FFF;
                border-radius:50%
            }
            @media screen and (max-width: 782px) {
                #wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar>a {
                    width: 70px;
                }
                #wpadminbar .quicklinks li#wp-admin-bar-my-account.with-avatar>a img {
                    top: 7px;
                }
            }
            #wp-admin-bar-ucd-landing-page-notice a.ab-item {
                color: #fff!important;
            }
        </style>
<?php   }
    }
}
add_action('wp_head', 'ucd_admin_bar_frontend_styling');
