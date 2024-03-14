<?php

/**
 * Get Youzify Plugin Pages
 */
function youzify_pages( $request_type = null, $id = null ) {

    // Get youzify pages.
    $youzify_pages = youzify_option( 'youzify_pages' );

    // Switch Key <=> Values
    if ( 'ids' == $request_type ) {
        $youzify_pages_ids = array_flip( $youzify_pages );
        return $youzify_pages_ids;
    }

    return $youzify_pages;
}

/**
 * Get Page URL.
 */
function youzify_page_url( $page_name, $user_id = null ) {

	// Get Page Data
    $page_id  = youzify_page_id( $page_name );
    $page_url = youzify_fix_path( get_permalink( $page_id ) );

	// Get Page with Current User if page = profile or account .
	if ( 'profile' == $page_name && ! empty( $user_id ) ) {
        $page_url = $page_url . get_the_author_meta( 'user_login', $user_id );
    } elseif ( 'profile' == $page_name && empty( $user_id ) ) {
        $page_url = $page_url . esc_html(  youzify_get_user_meta( 'user_login' ) );
    }

	// Return Page Url.
    return $page_url;

}

/**
 * Get Page ID.
 */
function youzify_page_id( $page ) {
    $youzify_pages = youzify_option( 'youzify_pages' );
    return $youzify_pages[ $page ];
}

/**
 * Sort list by numeric order.
 */
function youzify_sortByMenuOrder( $a, $b ) {

    if ( ! isset( $a['menu_order'] ) || ! isset( $b['menu_order'] ) ) {
        return 0;
    }

    $a = $a['menu_order'];
    $b = $b['menu_order'];

    if ( $a == $b ) {
        return 0;
    }

    return ( $a < $b ) ? -1 : 1;
}

/**
 * Get All Widgets.
 */
function youzify_get_profile_hidden_widgets() {
    return apply_filters( 'youzify_get_profile_hidden_widgets', (array) youzify_option( 'youzify_profile_hidden_widgets' ) );
}

/**
 * Check widget visibility
 */
function youzify_is_widget_visible( $widget_name ) {

    $visibility = false;

    $overview_widgets = youzify_options( 'youzify_profile_main_widgets' );
    $sidebar_widgets  = youzify_options( 'youzify_profile_sidebar_widgets' );
    $all_widgets      = array_merge( $overview_widgets, $sidebar_widgets );

    foreach ( $all_widgets as $widget_name => $visibility  ) {
        if ( 'visible' == $visibility ) {
            $visibility = true;
        }
    }

    // If its a Custom wiget Return True.
    if ( false !== strpos( $widget_name, 'youzify_cwg' ) ) {
        $visibility = true;
    }

    return apply_filters( 'youzify_is_widget_visible', $visibility, $widget_name );
}

/**
 * Get Array Key Index.
 */
function youzify_get_key_index( $value, $array ) {
    $key = array_search( $value, $array );
    if ( false !== $key ) {
        return $key;
    }
}

/**
 * Fix Url Path.
 */
function youzify_fix_path( $url ) {
    $url = str_replace( '\\', '/', trim( $url ) );
    return ( substr( $url,-1 ) != '/' ) ? $url .= '/' : $url;
}

/**
 * Get Login Page Url.
 */
function youzify_get_login_page_url() {

    // Init Vars.
    $login_url = wp_login_url();

    // Get Login Type.
    $login_type = youzify_option( 'youzify_login_page_type', 'url' );

    // Get Login Url.
    if ( 'url' == $login_type ) {
        $url = wp_login_url();
        $login_url = ! empty( $url ) ? $url : $login_url;
    } elseif ( 'page' == $login_type ) {
        $page_id = youzify_option( 'youzify_login_page' );
        $login_url = ! empty( $page_id ) ? get_the_permalink( $page_id ) : $login_url;
    }

    return apply_filters( 'youzify_get_login_page_url', $login_url );

}

/**
 * Get Arguments consedering default values.
 */
function youzify_get_args( $pairs, $atts, $prefix = null ) {

    // Set Up Arrays
    $out  = array();
    $atts = (array) $atts;

    // Get Prefix Value.
    $prefix = $prefix ? $prefix . '_' : null;

    // Get Values.
    foreach ( $pairs as $name => $default ) {
        if ( array_key_exists(  $prefix . $name, $atts ) ) {
            $out[ $name ] = $atts[ $prefix . $name ];
        } else {
            $out[ $name ] = $default;
        }
    }

    return $out;
}

/**
 * Add Groups & Wall Sidebar Widgets
 */
function youzify_add_sidebar_widgets( $sidebar_id, $widgets_list ) {

    // Get Sidebar Widgets
    $sidebars_widgets = youzify_option( 'sidebars_widgets' );

    // Check if Sidebar is empty.
    if ( ! empty( $sidebars_widgets[ $sidebar_id ] ) ) {
        return false;
    }

    // Add Widgets To sidebar.
    foreach ( $widgets_list as $widget ) {

        // Get Widgets Data.
        $widget_data = youzify_option( 'widget_' . $widget );

        // Get Last Widget Id
        $last_id = (int) ! empty( $widget_data ) ? max( array_keys( $widget_data ) ) : 0;

        // Get Next ID.
        $counter = $last_id + 1;

        // Add Widget Default Settings.
        $widget_data[] = youzify_get_widget_defaults_settings( $widget );

        // Get Widgets Data.
        update_option( 'widget_' . $widget, $widget_data );

        // Add Widget To sidebar
        $sidebars_widgets[ $sidebar_id ][] = strtolower( $widget ) . '-' . $counter;
    }

    // Update Sidebar
    update_option( 'sidebars_widgets', $sidebars_widgets );

}

/**
 * Create New Plugin Page.
 */
function youzify_add_new_plugin_page( $args ) {

    // Get Page Slug
    $slug = $args['slug'];

    // Check that the page doesn't exist already
    $is_page_exists = youzify_get_post_id( 'page', $args['meta'], $slug );

    if ( $is_page_exists ) {

        if ( ! isset( $pages[ $slug ] ) ) {

            // init Array.
            $pages = get_option( $args['pages'] );

            // Get Page ID
            $page_id = youzify_get_post_id( 'page', $args['meta'], $slug );

            // Add New Page Data.
            $pages[ $slug ] = $page_id;

            update_option( $args['pages'], $pages );
        }

        return false;
    }

    $user_page = array(
        'post_title'     => $args['title'],
        'post_name'      => $slug,
        'post_type'      => 'post',
        'post_status'    => 'publish',
        'post_author'    =>  1,
        'comment_status' => 'closed'
    );

    $post_id = wp_insert_post( $user_page );

    wp_update_post( array('ID' => $post_id, 'post_type' => 'page' ) );

    update_post_meta( $post_id, $args['meta'], $slug );

    // init Array.
    $pages = get_option( $args['pages'] );

    // Add New Page Data.
    $pages[ $slug ] = $post_id;

    if ( isset( $pages ) ) {
        update_option( $args['pages'], $pages );
    }

}

/**
 * Display Notice Function
 */
function youzify_display_admin_notice() {

    // Remove Default Function.
    global $BP_Legacy;
    remove_action( 'wp_footer', array( $BP_Legacy, 'sitewide_notices' ), 1 );

}

add_action( 'wp_head', 'youzify_display_admin_notice' );

/**
 * Check is user exist by id
 */
function youzify_is_user_exist( $user_id = null ) {

    if ( $user_id instanceof WP_User ) {
        $user_id = $user_id->ID;
    }
    return (bool) get_user_by( 'id', $user_id );
}

/**
 * Template Messages
 */
function youzify_template_messages() {

    ?>

    <div id="template-notices" role="alert" aria-atomic="true">
        <?php

        /**
         * Fires towards the top of template pages for notice display.
         *
         * @since 1.0.0
         */
        do_action( 'template_notices' ); ?>

    </div>

    <?php
}

add_action( 'youzify_group_main_content', 'youzify_template_messages' );
add_action( 'youzify_profile_main_content', 'youzify_template_messages' );

/**
 * Get Attachments Allowed Extentions
 */
function youzify_get_allowed_extensions( $type = null, $format = null ) {

    // Extentions
    $extensions = null;

    switch ( $type ) {

        case 'image':
            // Get Images Extensions.
            $extensions = youzify_option( 'youzify_atts_allowed_images_exts', array( 'png', 'jpg', 'jpeg', 'gif' ) );
            break;

        case 'video':
            // Get Videos Extensions.
            $extensions = youzify_option( 'youzify_atts_allowed_videos_exts', array( 'mp4', 'ogg', 'ogv', 'webm' ) );
            break;

        case 'audio':
            // Get Audios Extensions.
            $extensions = youzify_option( 'youzify_atts_allowed_audios_exts', array( 'mp3', 'ogg', 'wav' ) );
            break;

        case 'file':
            // Get Files Extensions.
            $extensions = youzify_option( 'youzify_atts_allowed_files_exts', array( 'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar', 'zip', 'mp4', 'mp3', 'ogg', 'pfi' ) );
            break;

        default:
            // Get Default Extensions.
            $extensions = array(
                'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar',
                'zip', 'mp4', 'mp3', 'ogg', 'pfi'
            );
            break;
    }

    // Convert Extentions To Lower Case.
    $extensions = array_map( 'strtolower', $extensions );

    // Return Extentions as Text Format
    $extensions = ( $format == 'text' ) ? implode( ', ', $extensions ) : $extensions;

    return $extensions;
}

/**
 * Insert After Array.
 */
function youzify_array_insert_after( array $array, $key, array $new ) {
    $keys = array_keys( $array );
    $index = array_search( $key, $keys );
    $pos = false === $index ? count( $array ) : $index + 1;
    return array_merge( array_slice( $array, 0, $pos ), $new, array_slice( $array, $pos ) );
}

/*
 * Set Body Scheme Class
 */
function youzify_body_add_youzify_scheme( $classes ) {

    // Get Profile Scheme
    $classes[] = youzify_option( 'youzify_profile_scheme', 'youzify-blue-scheme' );
    $classes[] = is_user_logged_in() ? 'logged-in' : 'not-logged-in';

    // Check if lighting is editable
    if ( youzify_option( 'youzify_allow_lighting_edition', 'on' ) == 'on' ) {

        // Get User ID.
        $user_id = bp_loggedin_user_id();

        $mode = get_user_meta( $user_id, 'youzify_lighting_mode', true );

        if ( empty( $mode ) ) {
            $mode = youzify_option( 'youzify_default_lighting_mode', 'light' );
        }

    } else {
        $mode = youzify_option( 'youzify_default_lighting_mode', 'light' );
    }

    // Lighting Mode
    $classes[] = 'youzify-' . $mode . '-lighting-mode';

    return $classes;

}

add_filter( 'body_class', 'youzify_body_add_youzify_scheme' );

/**
 *  Font-edn Modal
 */
function youzify_modal( $args, $modal_function, $options = null ) {

    $title        = $args['title'];
    $button_id    = $args['button_id'];
    $title_icon = isset( $args['title_icon'] ) ? $args['title_icon'] : '';
    $default_submit_icon = isset( $args['operation'] ) && $args['operation'] == 'add' ? 'fas fa-edit' : 'fas fa-sync-alt';
    $submit_btn_icon = isset( $args['submit_button_icon'] ) ? $args['submit_button_icon'] : $default_submit_icon;
    $button_title = isset( $args['button_title'] ) ? $args['button_title'] : __( 'Save', 'youzify' );
    $show_close = isset( $args['show_close'] ) ? $args['show_close'] : true;
    $show_delete_btn = isset( $args['show_delete_button'] ) ? $args['show_delete_button'] : false;
    $delete_btn_title = isset( $args['delete_button_title'] ) ? $args['delete_button_title'] : __( 'Delete', 'youzify' );
    $delete_btn_id = isset( $args['delete_button_id'] ) ? $args['delete_button_id'] : null;
    $delete_btn_item_id = isset( $args['delete_button_item_id'] ) ? $args['delete_button_item_id'] : null;

    $clases = array( 'youzify-modal' );

    if ( ! empty( $title_icon ) ) {
        $clases[] = 'youzify-big-close-icon';
    }

    $clases = implode( ' ', $clases );

    ?>

    <div id="youzify-modal" class="youzify-page-btns-border-<?php echo youzify_option( 'youzify_buttons_border_style', 'oval' ); ?>">

    <?php if ( isset( $args['modal_type'] ) && $args['modal_type'] == 'div' ) : ?>
        <div class="<?php echo $clases; ?>" id="<?php echo $args['id'] ;?>">
    <?php else : ?>
        <form class="<?php echo $clases; ?>" id="<?php echo $args['id'] ;?>" method="post" >
    <?php endif; ?>
        <div class="youzify-modal-title" data-title="<?php echo $title; ?>">
            <?php if ( ! empty( $title_icon ) ) : ?><i class="<?php echo $title_icon; ?>"></i><?php endif;?>
            <span class="youzify-modal-title-text"><?php echo $title; ?></span>
            <i class="fas fa-times youzify-modal-close-icon"></i>
        </div>

        <div class="youzify-modal-content">
            <?php
                if ( is_array( $modal_function ) ) {
                    call_user_func(array( $modal_function[0], $modal_function[1] ), $options );
                } else {
                    $modal_function( $options );
                }
            ?>
        </div>

        <?php if ( ! isset( $args['hide-action'] ) ) : ?>
        <div class="youzify-modal-actions">

            <?php if ( isset( $args['operation'] ) ) : ?>
            <button id="<?php echo $button_id; ?>" data-action="<?php echo $args['operation']; ?>" class="youzify-modal-button youzify-modal-save">
                <i class="<?php echo $submit_btn_icon; ?>"></i><?php echo $button_title ?>
            </button>
            <?php endif; ?>

            <?php if ( $show_delete_btn ) : ?>
            <button id="<?php echo $delete_btn_id; ?>" class="youzify-md-button youzify-modal-delete" data-item-id="<?php echo $delete_btn_item_id ?>">
                <i class="far fa-trash-alt"></i><?php echo $delete_btn_title; ?>
            </button>
            <?php endif; ?>

            <?php if ( $show_close ) : ?>
            <button class="youzify-modal-button youzify-modal-close">
                <i class="fas fa-times"></i><?php _e( 'Close', 'youzify' ); ?>
            </button>
            <?php endif; ?>
        </div>
        <?php endif; ?>

    <?php if ( isset( $args['modal_type'] ) && $args['modal_type'] == 'div' ) : ?>
        </div>
    <?php else : ?>
        </form>
    <?php endif; ?>
    </div>
    <?php
}

function youzify_fix_networks_icons_css( $icon ) {
    if ( strpos( $icon, ' ' ) === false) {
        $icon = 'fab fa-' . $icon;
    }

    return $icon;

}

add_filter( 'youzify_panel_networks_icon', 'youzify_fix_networks_icons_css' );
add_filter( 'youzify_user_social_networks_icon', 'youzify_fix_networks_icons_css' );

/**
 * Youzify Scrips Vars.
 */
function youzify_scripts_vars() {

    $vars = array(
        'unknown_error' => __( 'An unknown error occurred. Please try again later.', 'youzify' ),
        'slideshow_auto' => apply_filters( 'youzify_profile_slideshow_auto_loop' , true ),
        'slides_height_type' => youzify_option( 'youzify_slideshow_height_type', 'fixed' ),
        'activity_autoloader' => youzify_enable_wall_activity_loader(),
        'authenticating' => __( 'Authenticating...', 'youzify' ),
        'security_nonce' => wp_create_nonce( 'youzify-nonce' ),
        'displayed_user_id' => bp_displayed_user_id(),
        'ajax_url' => admin_url( 'admin-ajax.php' ),
        'save_changes' => __( 'Save Changes', 'youzify' ),
        'thanks'   => __( 'OK! Thanks', 'youzify' ),
        'confirm' => __( 'Confirm', 'youzify' ),
        'cancel' => __( 'Cancel', 'youzify' ),
        'menu_title' => __( 'Menu', 'youzify' ),
        'gotit' => __( 'Got it!', 'youzify' ),
        'done' => __( 'Done!', 'youzify' ),
        'ops' => __( 'Oops!', 'youzify' ),
        'poll_option' => __( 'Option %d', 'youzify' ),
        'poll_option_empty' => __( 'Sorry, you need to choose at least one option.', 'youzify' ),
        'slideshow_speed' => 5,
        'assets' => YOUZIFY_ASSETS,
        'youzify_url' => YOUZIFY_URL,
    );

    return apply_filters( 'youzify_scripts_vars', $vars );
}

/**
 * Enable Activity Loader
 */
function youzify_enable_wall_activity_loader() {

    $can = youzify_option( 'youzify_enable_wall_activity_loader', 'on' );

    if ( wp_is_mobile() ) {
        $can = 'off';
    }

    return apply_filters( 'youzify_enable_wall_activity_loader', $can );

}

/**
 * Get Suggestions List.
 */
function youzify_get_users_list( $users, $args = null ) {

    if ( empty( $users ) ) {
        return;
    }

    // Get Widget Class.
    $main_class = isset( $args['main_class'] ) ? $args['main_class'] : null;

    ?>

    <div class="youzify-items-list-widget youzify-list-avatar-circle <?php echo youzify_generate_class( $main_class ); ?>">

        <?php foreach ( $users as $user_id ) : ?>

        <?php $profile_url = bp_members_get_user_url( $user_id ); ?>

        <div class="youzify-list-item">
            <a href="<?php echo $profile_url; ?>" class="youzify-item-avatar"><?php echo bp_core_fetch_avatar( array( 'item_id' => $user_id, 'type' => 'thumb' ) ); ?></a>
            <div class="youzify-item-data">
                <a href="<?php echo $profile_url; ?>" class="youzify-item-name"><?php echo bp_core_get_user_displayname( $user_id ); ?><?php youzify_the_user_verification_icon( $user_id ); ?></a>
                <div class="youzify-item-meta">
                    <div class="youzify-meta-item">@<?php echo bp_members_get_user_slug( $user_id ); ?></div>
                </div>
            </div>
        </div>

        <?php endforeach; ?>

    </div>

    <?php

}

/**
 * Die Message
 */
function youzify_die( $message ) {
    $response['error'] = $message;
    die( json_encode( $response ) );
}

/**
 * Get User ID By Email.
 */
function youzify_get_user_id_by_email( $email_address = null ) {

    // Get User Data.
    $user = get_user_by( 'email', $email_address );

    return $user->ID;
}

/**
 * Get Image Tag By Url
 */
function youzify_get_avatar_img_by_url( $url ) {
    return '<img src="' . $url . '" alt="' . __( 'User Avatar', 'youzify' ) . '">';
}

/**
 * Convert Tags
 */
function youzify_convert_content_tags( $content ) {

    if ( empty( $content ) ) {
        return $content;
    }

    // Get Displayed User ID
    $displayed_user_id = bp_displayed_user_id();

    // Replace Tags.
    $content = str_replace( '{displayed_username}', bp_members_get_user_slug( $displayed_user_id ), $content );
    $content = str_replace( '{displayed_user_id}', $displayed_user_id, $content );

    $content = str_replace( '{logged_in_user}', bp_members_get_user_slug( bp_loggedin_user_id() ), $content );

    return apply_filters( 'youzify_convert_content_tags', $content );

}

/**
 * Pagination.
 */
function youzify_pagination( $data_args ) {

    // Get Base.
    $base = isset( $_POST['base'] ) ? sanitize_text_field( $_POST['base'] ) : get_pagenum_link( 1 );

    // Get Items Per Page Number
    $per_page = $data_args['limit'] ? absint( $data_args['limit'] ) : 1;

    // Get total Pages Number
    $max_page = ceil( $data_args['total'] / $per_page );

    // Get Current Page Number
    $cpage = ! empty( $_POST['page'] ) ? absint( $_POST['page'] ) : 1;

    // Get Offset
    $offset = ( ( $per_page * ( $cpage - 1 ) ) );

    if ( $cpage != 1 ) {
        $offset = $offset + $per_page;
    }

    // Get Next and Previous Pages Number
    if ( ! empty( $cpage ) ) {
        $next_page = $cpage + 1;
        $prev_page = $cpage - 1;
    }

    // Pagination Settings
    $pagination_args = array(
        'base'        => $base . '%_%',
        'format'      => 'page/%#%',
        'total'       => $max_page,
        'current'     => $cpage,
        'show_all'    => false,
        'end_size'    => 1,
        'mid_size'    => 2,
        'prev_next'   => True,
        'prev_text'   => '<div class="youzify-page-symbole">&laquo;</div><span class="youzify-next-nbr">'. $prev_page .'</span>',
        'next_text'   => '<div class="youzify-page-symbole">&raquo;</div><span class="youzify-next-nbr">'. $next_page .'</span>',
        'type'         => 'plain',
        'add_args'     => false,
        'add_fragment' => '',
        'before_page_number' => '<span class="youzify-page-nbr">',
        'after_page_number'  => '</span>',
    );

    // Call Pagination Function
    $paginate_comments = paginate_links( $pagination_args );

    // Get Data Args.
    $pargs = '';

    if ( ! empty( $data_args ) ) {
        foreach ( $data_args as $key => $value ) {
            $pargs .=' data-' . $key .'="' . $value . '"';
        }
    }

    // Print Pagination
    if ( $paginate_comments ) {
        echo sprintf( "<nav class='youzify-pagination' data-base='%1s' data-page='%3d' $pargs>" , $base, $offset, $cpage );
        echo '<span class="youzify-pagination-pages">';
        printf( __( 'Page %1$d of %2$d' , 'youzify' ), $cpage, $max_page );
        echo "</span><div class='comments-nav-links youzify-nav-links'>$paginate_comments</div></nav>";
    }
}

/**
 * Get File Contents.
 */
function youzify_file_get_contents( $url ) {

    $args =apply_filters( 'youzify_file_get_contents_args',
        array(
            'headers' => array(
                'user-agent' => 'Mozilla/5.0 (Windows NT 6.2) AppleWebKit/536.6 (KHTML, like Gecko) Chrome/20.0.1090.0 Safari/536.6'
            )
        )
    );

    // Get URL Data.
    $response = wp_remote_get( $url, $args );

    // Return Result.
    return wp_remote_retrieve_body( $response );

}

/**
 * Redirect Buddypress No access page to login page.
 */
function youzify_redirect_bp_no_access_to_login_page( $data ) {
    if ( $data['mode'] == 2 ) {
        $data['mode'] = 1;
        $data['root'] = youzify_get_login_page_url();
    }
    return $data;
}

add_filter( 'bp_core_no_access', 'youzify_redirect_bp_no_access_to_login_page' );

/**
 * Get Bookmarked Post.
 */
function youzify_get_bookmark_id( $user_id, $item_id, $item_type ) {

    global $wpdb, $Youzify_bookmark_table;

    // Prepare Sql
    $sql = $wpdb->prepare(
        "SELECT id FROM $Youzify_bookmark_table WHERE user_id = %d AND item_id = %d AND item_type = %s",
        $user_id, $item_id, $item_type
    );

    // Get Result
    $result = $wpdb->get_var( $sql );

    return $result;

}

/**
 * Get Attributes
 */
function youzify_get_item_attributes( $attributes = null ) {

    if ( ! empty( $attributes ) ) {
        foreach ( $attributes as $attribute => $value ) {
            echo 'data-' . $attribute . '="' . $value . '"';
        }
    }

}

/**
 * Is Activity Component
 */
function youzify_is_activity_component() {
    $active = bp_is_activity_component() ? true : false;
    return apply_filters( 'youzify_is_activity_component', $active );
}

/**
 * Authenticate User.
 */

add_action( 'parse_request', 'youzify_instagram_widget_process_authentication' );

function youzify_instagram_widget_process_authentication( $query ) {

    if ( ! is_user_logged_in() || ! isset( $query->query_vars['youzify-authentication'] ) ) {
        return;
    }

    if ( $query->query_vars['youzify-authentication'] != 'feed' ) {
        return;
    }

    // Get Provider.
    $provider = $query->query_vars['youzify-provider'];

    if ( empty( $provider ) || $provider != 'Instagram' ) {
        return;
    }

    // Inculue Files.
    if ( ! class_exists( 'Hybridauth', false ) ) {
        require_once YOUZIFY_CORE . 'hybridauth/autoload.php';
        require_once YOUZIFY_CORE . 'hybridauth/Hybridauth.php';
    }

    try {

        // Config Data.
        $config = array(
            'callback'   => home_url( '/youzify-auth/feed/Instagram' ),
            "debug_file" => 'debug-instagram.txt',
            "debug_mode" => false,
            'keys' => array(
                'id'  => youzify_option( 'youzify_wg_instagram_app_id' ),
                'secret' => youzify_option( 'youzify_wg_instagram_app_secret' ),
            )
        );

        // Create an Instance with The Config Data.
        $hybridauth = new Hybridauth\Provider\Instagram( $config );

        // Get User ID.
        $user_id = get_current_user_id();

        // Start the Authentication Process.
        $adapter = $hybridauth->authenticate();

        // Get Access Token.
        $accessToken = $hybridauth->getAccessToken();

        if ( isset( $accessToken['access_token'] ) && ! empty( $accessToken['access_token'] ) ) {

            $response = wp_remote_get( 'https://graph.instagram.com/access_token?grant_type=ig_exchange_token&client_secret=' . $config['keys']['secret'] . '&access_token=' . $accessToken['access_token'] , array( 'timeout' => 60, 'sslverify' => false ) );

            if ( ! is_wp_error( $response ) ) {

                // certain ways of representing the html for double quotes causes errors so replaced here.
                $response = json_decode( str_replace( '%22', '&rdquo;', $response['body'] ), true );

                if ( ! empty( $response['access_token'] ) ) {

                    // Get Current Time.
                    $date = new DateTime();

                    // Set Expiration Date After 30 Days.
                    $date->modify( '+30 days' );

                    update_user_meta( $user_id, 'youzify_wg_instagram_account_token', array( 'token' => $response['access_token'], 'expires' => $date->format( 'Y/m/d' ) ) );

                }
            }

        }

        // Get User Data.
        $user_data = $hybridauth->getUserProfile();

        if ( ! empty( $user_data ) ) {

            $user_data = youzify_convert_incomplete_class_to_object( $user_data );

            // Remove empty data.
            $user_data = array_filter( $user_data );

            update_user_meta( $user_id, 'youzify_wg_instagram_account_user_data', $user_data );

            do_action( 'youzify_after_linking_instagram_account', $user_id, $response['access_token']  );

        } else {
            delete_user_meta( $user_id, 'youzify_wg_instagram_account_user_data' );
        }

    } catch( Exception $e ) {
        youzify_log( $e );
        youzify_auth_redirect( $e );
    }

    wp_redirect( youzify_get_widgets_settings_url( 'instagram', $user_id ) );
    exit;

}

/**
 * Scroll to top
 */
add_action( 'youzify_after_youzify_template_content', 'youzify_add_scroll_to_top' );

function youzify_add_scroll_to_top() {
    if ( 'on' == youzify_option( 'youzify_display_scrolltotop', 'on' ) ) {
        wp_enqueue_script( 'youzify-scrolltotop', YOUZIFY_ASSETS . 'js/youzify-scrolltotop.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );
        echo '<a class="youzify-scrolltotop"><i class="fas fa-chevron-up"></i></a>';
    }
}

/**
 * Edit Invitations Resend Button.
 */

add_filter( 'bp_get_the_members_invitation_resend_link' , 'youzify_edit_invitations_resend_button' );

function youzify_edit_invitations_resend_button( $retval, $user_id = 0 ) {
    // New Delete Link.
    return sprintf(
        '<a href="%1$s" data-youzify-tooltip="' . __( 'Resend', 'youzify' ) . '" class="resend secondary confirm">%2$s</a>',
        esc_url( bp_get_the_members_invitations_resend_url( $user_id ) ),
        '<span class="dashicons dashicons-update-alt"></span>'
    );
}

/**
 * Edit Invitations Cancel Button.
 */
add_filter( 'bp_get_the_members_invitation_delete_link' , 'youzify_edit_invitations_delete_button' );

function youzify_edit_invitations_delete_button( $retval, $user_id = 0 ) {

    // Modify the message for accepted/not accepted invitatons.
    if ( bp_get_the_members_invitation_property( 'accepted' ) ) {
        $message = __( 'Delete', 'youzify' );
        $icon = '<span class="dashicons dashicons-trash"></span>';
    } else {
        $message = __( 'Cancel', 'youzify' );
        $icon = '<span class="dashicons dashicons-no-alt"></span>';
    }

    // New Delete Link.
    return sprintf(
        '<a href="%1$s" data-youzify-tooltip="' . $message . '" class="delete secondary confirm">%2$s</a>',
        esc_url( bp_get_the_members_invitations_delete_url( $user_id ) ),
        $icon
    );
}


/**
 *  Get Directory Header
 */
function youzify_get_directory_header( $directory, $args = null ) {

    if ( ! apply_filters( 'youzify_show_directories_header', true ) ) {
        return;
    }

    // Filter Args.
    $args = apply_filters ( 'youzify_' . $directory . '_args' , $args  );

    ?>

    <div class="youzify-search-header">

        <div class="youzify-search-landing-image-container" <?php if ( ! empty( $args['cover_background'] ) ) : ?> style="background-color:<?php echo $args['cover_background']['color']; ?>" <?php endif; ?>>
          <img class="youzify-search-landing-image" src="<?php echo $args['cover']; ?>" alt="">
          <div class="youzify-search-landing-image-text">
            <div class="youzify-search-landing-heading"><?php echo $args['title']; ?></div>
              <p class="youzify-search-landing-heading-text"><?php echo $args['subtitle']; ?></p>
          </div>
        </div>

      <div class="youzify-search-results-details">
        <div class="youzify-search-input-container">

    <div class="youzify-search-input-with-dropdown">
      <div class="youzify-left-side-wrapper">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" role="img" class="icon fill-current youzify-search-icon">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M10.6002 12.0498C9.49758 12.8568 8.13777 13.3333 6.66667 13.3333C2.98477 13.3333 0 10.3486 0 6.66667C0 2.98477 2.98477 0 6.66667 0C10.3486 0 13.3333 2.98477 13.3333 6.66667C13.3333 8.15637 12.8447 9.53194 12.019 10.6419C12.0265 10.6489 12.0338 10.656 12.0411 10.6633L15.2935 13.9157C15.6841 14.3063 15.6841 14.9394 15.2935 15.33C14.903 15.7205 14.2699 15.7205 13.8793 15.33L10.6269 12.0775C10.6178 12.0684 10.6089 12.0592 10.6002 12.0498ZM11.3333 6.66667C11.3333 9.244 9.244 11.3333 6.66667 11.3333C4.08934 11.3333 2 9.244 2 6.66667C2 4.08934 4.08934 2 6.66667 2C9.244 2 11.3333 4.08934 11.3333 6.66667Z"></path>
        </svg>

        <form action="/search" class="js-search-input-form youzify-search-input-form" method="get">
          <input type="search" name="q" placeholder="<?php echo $args['search_placeholder']; ?>" value="" class="youzify-search-input js-search-input youzify-directory-search-form">
        </form>
      </div>

      <?php do_action( 'youzify_after_' . $directory . '_search_input' ); ?>

    </div>


        </div>

      </div>
    </div>

    <style type="text/css">
        .youzify #youzify-directory-search-box,
        #directory-show-search {
            display: none;
        }
    </style>

    <?php
}

/**
 * Sanitize Array.
 * */
function youzify_sanitize_array( &$array ) {

    if ( empty( $array ) ) {
        return $array;
    }

    foreach ( $array as &$value) {
        if ( ! is_array( $value ) )    {
            $value = sanitize_text_field( $value );
        }  else {
            youzify_sanitize_array( $value );
        }
    }

    return $array;

}