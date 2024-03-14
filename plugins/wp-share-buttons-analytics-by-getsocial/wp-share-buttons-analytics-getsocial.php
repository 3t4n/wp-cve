<?php
/**
 * Plugin Name: Social Share Buttons & Analytics by GetSocial.io
 * Plugin URI: https://getsocial.io
 * Description: Social Share Buttons & Analytics is a free WordPress plugin that enables you to add beautiful share buttons to your page in various formats. Upgrade to get analytics and track all social shares happening on your page. See how much traffic, conversions, and shares each post generated.
 * Version: 4.4
 * Author: Getsocial, S.A.
 * Author URI: https://getsocial.io
 * License: GPL2
 */

include('lib/gs.php');
/* MENU */

add_action('admin_menu', 'gs_getsocial_menu');

function gs_getsocial_menu() {
    $GS = get_gs();

    add_menu_page( 'GetSocial', 'GetSocial', 'manage_options', slug_path('init.php'), '', 'data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0idXRmLTgiPz4KPCEtLSBHZW5lcmF0b3I6IEFkb2JlIElsbHVzdHJhdG9yIDIyLjEuMCwgU1ZHIEV4cG9ydCBQbHVnLUluIC4gU1ZHIFZlcnNpb246IDYuMDAgQnVpbGQgMCkgIC0tPgo8c3ZnIHZlcnNpb249IjEuMSIgaWQ9IkxheWVyXzEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiIHg9IjBweCIgeT0iMHB4IgoJIHZpZXdCb3g9IjAgMCAyNy43IDE3LjEiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDI3LjcgMTcuMTsiIHhtbDpzcGFjZT0icHJlc2VydmUiPgo8c3R5bGUgdHlwZT0idGV4dC9jc3MiPgoJLnN0MHtmaWxsOiNGRkZGRkY7fQo8L3N0eWxlPgo8dGl0bGU+R0VUU09DSUFMPC90aXRsZT4KPGRlc2M+Q3JlYXRlZCB3aXRoIFNrZXRjaC48L2Rlc2M+CjxnIGlkPSJQYWdlLTEiPgoJPGcgaWQ9IkFnZW5jeSIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoLTYwLjAwMDAwMCwgLTQ2LjAwMDAwMCkiPgoJCTxnIGlkPSJHcm91cC01NiIgdHJhbnNmb3JtPSJ0cmFuc2xhdGUoNjAuMDAwMDAwLCAyNS4wMDAwMDApIj4KCQkJPGcgaWQ9ImxvZ28iPgoJCQkJPHBhdGggaWQ9IkdFVFNPQ0lBTCIgY2xhc3M9InN0MCIgZD0iTTE1LjIsMzYuN2MtMC44LDAuNC0xLjcsMC44LTIuOCwxYy0xLjEsMC4zLTIuMiwwLjQtMy41LDAuNGMtMS4zLDAtMi41LTAuMi0zLjYtMC42CgkJCQkJYy0xLjEtMC40LTItMS0yLjgtMS43Yy0wLjgtMC44LTEuNC0xLjctMS45LTIuN1MwLDMwLjgsMCwyOS42YzAtMS4zLDAuMi0yLjUsMC43LTMuNXMxLjEtMiwxLjktMi43YzAuOC0wLjcsMS43LTEuMywyLjgtMS43CgkJCQkJQzYuNCwyMS4yLDcuNiwyMSw4LjgsMjFjMS4zLDAsMi41LDAuMiwzLjYsMC42czIsMC45LDIuNywxLjZMMTIuNiwyNmMtMC40LTAuNC0wLjktMC44LTEuNS0xLjFjLTAuNi0wLjMtMS4zLTAuNC0yLjEtMC40CgkJCQkJYy0wLjcsMC0xLjMsMC4xLTEuOSwwLjRjLTAuNiwwLjMtMS4xLDAuNi0xLjUsMS4xYy0wLjQsMC41LTAuOCwxLTEsMS42Yy0wLjIsMC42LTAuNCwxLjMtMC40LDJjMCwwLjgsMC4xLDEuNCwwLjMsMi4xCgkJCQkJYzAuMiwwLjYsMC41LDEuMiwxLDEuNkM1LjksMzMuNyw2LjQsMzQsNywzNC4zYzAuNiwwLjMsMS4zLDAuNCwyLjEsMC40YzAuNSwwLDAuOSwwLDEuMy0wLjFzMC44LTAuMiwxLjEtMC4zdi0zSDguNXYtMy4yaDYuNwoJCQkJCVYzNi43eiBNMjUuMSwyNS42Yy0wLjMtMC40LTAuOC0wLjgtMS4zLTFjLTAuNS0wLjMtMS4xLTAuNC0xLjYtMC40Yy0wLjMsMC0wLjUsMC0wLjgsMC4xYy0wLjMsMC0wLjUsMC4xLTAuNywwLjMKCQkJCQlzLTAuNCwwLjMtMC41LDAuNUMyMCwyNS4zLDIwLDI1LjYsMjAsMjUuOWMwLDAuMywwLjEsMC41LDAuMiwwLjdjMC4xLDAuMiwwLjMsMC4zLDAuNSwwLjVjMC4yLDAuMSwwLjUsMC4zLDAuOCwwLjQKCQkJCQljMC4zLDAuMSwwLjcsMC4yLDEsMC40YzAuNiwwLjIsMS4xLDAuNCwxLjcsMC42YzAuNiwwLjIsMS4xLDAuNSwxLjYsMC45YzAuNSwwLjQsMC45LDAuOCwxLjIsMS40YzAuMywwLjUsMC41LDEuMiwwLjUsMgoJCQkJCWMwLDAuOS0wLjIsMS43LTAuNSwyLjRjLTAuMywwLjctMC44LDEuMi0xLjQsMS43Yy0wLjYsMC40LTEuMiwwLjgtMiwxYy0wLjgsMC4yLTEuNSwwLjMtMi4zLDAuM2MtMS4yLDAtMi4zLTAuMi0zLjQtMC42CgkJCQkJYy0xLjEtMC40LTItMS0yLjctMS43bDIuNi0yLjZjMC40LDAuNSwwLjksMC45LDEuNiwxLjJzMS4zLDAuNSwxLjksMC41YzAuMywwLDAuNiwwLDAuOS0wLjFjMC4zLTAuMSwwLjUtMC4yLDAuNy0wLjMKCQkJCQljMC4yLTAuMSwwLjQtMC4zLDAuNS0wLjZzMC4yLTAuNSwwLjItMC44YzAtMC4zLTAuMS0wLjYtMC4yLTAuOGMtMC4yLTAuMi0wLjQtMC40LTAuNy0wLjZjLTAuMy0wLjItMC42LTAuMy0xLjEtMC41CgkJCQkJYy0wLjQtMC4xLTAuOS0wLjMtMS40LTAuNWMtMC41LTAuMi0xLTAuNC0xLjUtMC42cy0wLjktMC41LTEuMy0wLjlzLTAuNy0wLjgtMC45LTEuM2MtMC4yLTAuNS0wLjQtMS4xLTAuNC0xLjkKCQkJCQljMC0wLjksMC4yLTEuNywwLjYtMi4zYzAuNC0wLjYsMC45LTEuMiwxLjQtMS42czEuMy0wLjcsMi0wLjljMC44LTAuMiwxLjUtMC4zLDIuMy0wLjNjMC45LDAsMS45LDAuMiwyLjgsMC41CgkJCQkJYzEsMC4zLDEuOCwwLjgsMi41LDEuNUwyNS4xLDI1LjZ6Ii8+CgkJCTwvZz4KCQk8L2c+Cgk8L2c+CjwvZz4KPC9zdmc+Cg==' );

    // If it's an update from a previous version, don't show the popup
    if (get_option('gs-api-key')) {
        update_option("gs-popup-showed", "showed");
    }


    add_action( 'admin_init', 'register_gs_settings' );
}

function slug_path($s) {
    $main_slug = 'wp-share-buttons-analytics-by-getsocial/';

    return ($main_slug.$s);
}

add_action('wp_ajax_gs_update', 'update_getsocial');
add_action('wp_ajax_gs_update_with_values', 'update_getsocial_with_values');

function update_getsocial() {
    global $wpdb; // this is how you get access to the database

    $GS = get_gs();
    $GS->refreshSite();

    wp_die(); // this is required to terminate immediately and return a proper response
}

function update_getsocial_with_values() {
    global $wpdb; // this is how you get access to the database

    $GS = get_gs();

    $GS->refreshSite($_POST['response']);

    wp_die(); // this is required to terminate immediately and return a proper response
}

function register_gs_settings() {
    register_setting('getsocial-gs-settings' , 'gs-api-key');
    register_setting('getsocial-gs-settings' , 'gs-place');
    register_setting('getsocial-gs-settings' , 'gs-place-follow');
    register_setting('getsocial-gs-settings' , 'gs-lang');
    register_setting('getsocial-gs-settings' , 'gs-posts-page');
    register_setting('getsocial-gs-settings' , 'gs-user-email');

    foreach(array('group', 'floating') as $app):
        register_setting('getsocial-gs-' . $app, 'gs-' . $app . '-active');
        register_setting('getsocial-gs-' . $app, 'gs-' . $app . '-network-fb');
        register_setting('getsocial-gs-' . $app, 'gs-' . $app . '-network-tw');
        register_setting('getsocial-gs-' . $app, 'gs-' . $app . '-network-pn');
        register_setting('getsocial-gs-' . $app, 'gs-' . $app . '-network-gp');
        register_setting('getsocial-gs-' . $app, 'gs-' . $app . '-template');
        register_setting('getsocial-gs-' . $app, 'gs-' . $app . '-size');
        register_setting('getsocial-gs-' . $app, 'gs-' . $app . '-counter');
        register_setting('getsocial-gs-' . $app, 'gs-' . $app . '-position');
    endforeach;

    register_setting('getsocial-gs-custom-expressions', 'gs-custom-expression-active');
    register_setting('getsocial-gs-custom-expressions', 'gs-custom-expression-position');
}

function get_gs() {
    return new GS(get_option('gs-api-key'),
                    get_option('gs-identifier'),
                    get_option('gs-lang'));
}

// Add GS lib only if the plugin is activated and registered
if (get_option('gs-api-key') != '') {
    add_action('wp_head','add_gs_lib');
}

function add_gs_lib() {
    $GS = get_gs();
    echo $GS->getLib();
}

// check if page builder plugin is installed and change the order of the GS div
$installed_plugins = get_option('active_plugins');

if (false !== array_search('siteorigin-panels/siteorigin-panels.php', $installed_plugins)) {
    add_filter('the_content', 'on_post_content', 10);
} else {
    add_filter('the_content', 'on_post_content', 0);
}

add_filter('the_excerpt','change_excerpt');

// Add GS code to the post excerpts
function change_excerpt($content) {
    global $wp_query;
    $post = $wp_query->post;
    $GS = get_gs();

    $groups_active = $GS->is_active('sharing_bar');
    $big_counter_bar_active = $GS->is_active('social_bar_big_counter');
    $after_content = "";

    // If we are in the posts page, show a share bar at the end of the post only
    if ((is_home() || is_search() || is_category()) && get_option('gs-posts-page') == 'active'):
        if($groups_active):
            $groups = $GS->getCode('sharing_bar', get_permalink(), get_the_title(), wp_get_attachment_url( get_post_thumbnail_id($post->ID)), null, null, true);

            $position = $GS->prop('sharing_bar', 'position');

            if($position == 'top' || $position == 'bottom' || $position == 'both'):
                $after_content = $after_content . $groups;
            endif;
        endif;

        if ($big_counter_bar_active):
            $big_counter = $GS->getCode('social_bar_big_counter', get_permalink(), get_the_title(), wp_get_attachment_url( get_post_thumbnail_id($post->ID)), null, null, true);

            $position = $GS->prop('sharing_bar', 'position');

            if( $position == 'top' || $position == 'bottom' || $position == 'both'):
                $after_content = $after_content . $big_counter;
            endif;
        endif;
    endif;

    $content = $content . $after_content;

    return $content;
}

function on_product_after_content($content) {
    echo add_buttons_to_content($content);
}

function on_post_content($content) {
    return add_buttons_to_content($content);
}

function add_buttons_to_content($content) {
    global $post;

    $getsocial_meta = get_post_custom();

    if (isset($getsocial_meta['_my_meta_getsocialio_hide'])) {
        $hide_bars = $getsocial_meta['_my_meta_getsocialio_hide'][0];

        if ($hide_bars == 1) {
            return $content;
        }
    }

    if (is_singular('page') && $post->post_type != 'page') {
        return $content;
    }

    $places = get_option('gs-place');

    $condition = true;

    if ($places == null || $places == 'place-all'):

        if (function_exists('is_shop')) {
            $shop_page = is_shop();
        } else {
            $shop_page = false;
        }

        $condition = (is_single() || is_page() || $shop_page);
    elseif ($places == 'place-posts'):
        $condition = is_single();
    elseif ($places == 'place-pages'):
        $condition = is_page();
    elseif ($places == 'only-shortcodes'):
        $condition = false;
    endif;

    $places_follow = get_option('gs-place-follow');

    $condition_follow = true;

    if($places_follow == null || $places_follow == 'place-all'):
        $condition_follow = (is_single() || is_page());
    elseif ($places_follow == 'place-posts'):
        $condition_follow = is_single();
    elseif ($places_follow == 'place-pages'):
        $condition_follow = is_page();
    elseif ($places_follow == 'only-shortcodes'):
        $condition_follow = false;
    endif;

    $GS = get_gs();

    $groups_active = $GS->is_active('sharing_bar');
    $native_active = $GS->is_active('native_bar');
    $big_counter_bar_active = $GS->is_active('social_bar_big_counter');
    $follow_bar_active = $GS->is_active('follow_bar');
    $reaction_buttons_active = $GS->is_active('reaction_buttons');
    $before_content = "";
    $after_content = "";

    $custom_content = $content;

    if (!is_feed() && !is_home()):

        if ($groups_active && $condition):
            $groups = $GS->getCode('sharing_bar', get_permalink(), get_the_title(), wp_get_attachment_url( get_post_thumbnail_id($post->ID)));

            $position = $GS->prop('sharing_bar', 'position');

            if($position == 'bottom' || $position == 'both'):
                $after_content = $groups;
            endif;

            if ( $position == 'top' || $position == 'both' ):
                $before_content = $groups.'<br/>';
            endif;
        endif;

        if ($reaction_buttons_active && $condition):
            $groups = $GS->getCode('reaction_buttons', get_permalink(), get_the_title(), wp_get_attachment_url( get_post_thumbnail_id($post->ID)));

            $position = $GS->prop('reaction_buttons', 'position');

            if($position == 'bottom' || $position == 'both'):
                $after_content = $groups;
            endif;

            if ( $position == 'top' || $position == 'both' ):
                $before_content = $groups.'<br/>';
            endif;
        endif;

        if ($big_counter_bar_active && $condition):
            $big_counter = $GS->getCode('social_bar_big_counter', get_permalink(), get_the_title(), wp_get_attachment_url( get_post_thumbnail_id($post->ID)));

            $position = $GS->prop('social_bar_big_counter', 'position');

            if ($position == 'bottom' || $position == 'both'):
                $after_content = $after_content.$big_counter;
            endif;

            if ($position == 'top' || $position == 'both'):
                $before_content = $before_content.$big_counter.'<br/>';
            endif;
        endif;

        if ($follow_bar_active && $condition_follow):
            $follow_bar = $GS->getCode('follow_bar', get_permalink(), get_the_title(), wp_get_attachment_url( get_post_thumbnail_id($post->ID)));

            $position = $GS->prop('follow_bar', 'position');

            if ($position == 'bottom' || $position == 'both'):
                $after_content = $after_content.$follow_bar;
            endif;

            if ($position == 'top' || $position == 'both'):
                $before_content = $before_content.$follow_bar.'<br/>';
            endif;
        endif;

        if ($native_active && $condition):
            $native = $GS->getCode('native_bar', get_permalink(), get_the_title(), wp_get_attachment_url( get_post_thumbnail_id($post->ID)));

            $position = $GS->prop('native_bar', 'position');

            if ($position == 'bottom' || $position == 'both'):
                $after_content = $after_content.$native;
            endif;

            if ($position == 'top' || $position == 'both'):
                $before_content = $before_content.$native.'<br/>';
            endif;
        endif;
    endif;
    // if is the posts page, add follow bars at the end of the post
    if (is_home() && get_option('gs-posts-page') == 'active'):
        if ($groups_active):
            $groups = $GS->getCode('sharing_bar', get_permalink(), get_the_title(), wp_get_attachment_url( get_post_thumbnail_id($post->ID)), null, null, true);

            $position = $GS->prop('sharing_bar', 'position');

            if ($position == 'top' || $position == 'bottom' || $position == 'both'):
                $after_content = $after_content . $groups;
            endif;
        endif;

        if ($reaction_buttons_active):
            $groups = $GS->getCode('reaction_buttons', get_permalink(), get_the_title(), wp_get_attachment_url( get_post_thumbnail_id($post->ID)), null, null, true);

            $position = $GS->prop('reaction_buttons', 'position');

            if ($position == 'top' || $position == 'bottom' || $position == 'both'):
                $after_content = $after_content . $groups;
            endif;
        endif;

        if ($big_counter_bar_active):
            $big_counter = $GS->getCode('social_bar_big_counter', get_permalink(), get_the_title(), wp_get_attachment_url( get_post_thumbnail_id($post->ID)), null, null, true);

            $position = $GS->prop('social_bar_big_counter', 'position');

            if ($position == 'top' || $position == 'bottom' || $position == 'both'):
                $after_content = $after_content . $big_counter;
            endif;
        endif;
    endif;

    $custom_content = $before_content . $custom_content . $after_content;

    return $custom_content;
}

add_shortcode('getsocial', 'gs_bars_shortcode');

function gs_bars_shortcode($atts) {
    global $wp_query;
    $post = $wp_query->post;
    $GS = get_gs();

    if (function_exists('is_shop')) {
        $shop_page = is_shop();
    } else {
        $shop_page = false;
    }

    // if no type defined
    if ($atts['app'] == 'follow_bar' || (array_key_exists('app',$atts) && (is_single() || is_page() || $shop_page))) {
        return $GS->getCode($atts['app'], get_permalink(), get_the_title(), wp_get_attachment_url( get_post_thumbnail_id($post->ID)));
    } else {
        return "";
    }
}

/**
 * Adds a box to the main column on the Post and Page edit screens.
 */
function getsocialio_add_meta_box_settings() {

    $screens = array( 'post', 'page' );

    foreach ( $screens as $screen ) {

        add_meta_box(
            'getsocialio_settings',
            __( 'GetSocial', 'getsocialio_textdomain' ),
            'getsocialio_meta_box_callback',
            $screen,
            'side'
        );
    }
}

add_action('add_meta_boxes', 'getsocialio_add_meta_box_settings');

/**
 * Prints the box content.
 *
 * @param WP_Post $post The object for the current post/page.
 */
function getsocialio_meta_box_callback($post) {

    // Add an nonce field so we can check for it later.
    wp_nonce_field( 'getsocialio_meta_box', 'getsocialio_meta_box_nonce' );

    /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
    $value = get_post_meta( $post->ID, '_my_meta_getsocialio_hide', true );
    $checked = (esc_attr( $value ) == "1") ? 'checked' : '';

    echo '<input type="checkbox" id="getsocialio_hide" name="getsocialio_hide" value="1"' . $checked . ' />';
    echo '<label for="">';
    _e( ' Hide social bars?', 'getsocialio_textdomain' );
    echo '</label>';
    echo '<br/><br/><p class="howto"><i>Limited to Horizontal Bars</i></p>';
}

/**
 * When the post is saved, saves our custom data.
 *
 * @param int $post_id The ID of the post being saved.
 */
function getsocialio_save_meta_box_data($post_id) {

    /*
     * We need to verify this came from our screen and with proper authorization,
     * because the save_post action can be triggered at other times.
     */

    // Check if our nonce is set.
    if (!isset($_POST['getsocialio_meta_box_nonce'])) {
        return;
    }

    // Verify that the nonce is valid.
    if (!wp_verify_nonce($_POST['getsocialio_meta_box_nonce'], 'getsocialio_meta_box')) {
        return;
    }

    // If this is an autosave, our form has not been submitted, so we don't want to do anything.
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check the user's permissions.
    if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
        if (!current_user_can('edit_page', $post_id)) {
            return;
        }
    } else {
        if (!current_user_can('edit_post', $post_id)) {
            return;
        }
    }

    /* OK, it's safe for us to save the data now. */

    // Make sure that it is set.
    if (!isset($_POST['getsocialio_hide'])) {
        $my_data = 0;
    } else {
        // Sanitize user input.
        $my_data = sanitize_text_field($_POST['getsocialio_hide']);
    }

    // Update the meta field in the database.
    update_post_meta( $post_id, '_my_meta_getsocialio_hide', $my_data );
}

add_action( 'save_post', 'getsocialio_save_meta_box_data' );

/* Welcome popover */

function add_popup_scripts_method() {

    if (get_option('gs-popup-showed') == "showed") {
        return;
    } else {

        try {
            $url = plugins_url( '/lib/onboarding_popup.php' , __FILE__ );

            wp_enqueue_script( 'jquery-form');
            wp_enqueue_script('gs-popover', plugins_url( '/js/create_popover.js' , __FILE__ ), array('jquery'));
            wp_localize_script( 'gs-popover', 'GETSOCIAL_ONBOARDING_PATH', $url );
            wp_localize_script( 'gs-popover', 'popup_showed', get_option('gs-popup-showed') );
        }
        // If there's some problem creating the popup, just ignore it
        catch(Exception $e) {
            update_option("gs-popup-showed", "showed");
        }
    }
}

add_action( 'admin_enqueue_scripts', 'add_popup_scripts_method' );

add_action( 'wp_ajax_save_popup_visit', 'save_popup_visit' );

function save_popup_visit() {

    global $wpdb; // this is how you get access to the database

    update_option("gs-popup-showed", "showed");

    wp_die(); // this is required to terminate immediately and return a proper response
}
