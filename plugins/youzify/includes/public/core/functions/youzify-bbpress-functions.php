<?php

/**
 * Get Youzify Page Template.
 */
function youzify_bbp_youzify_template( $template ) {

    if ( ! is_page() && is_bbpress() && ! bp_current_component() ) {

        // Check if its Youzify plugin page
        if ( apply_filters( 'youzify_enable_youzify_bbpress_page', true ) ) {

            // Get Data.
            $path = youzify_get_theme_template_path() . '/youzify/bbpress-template.php';

            if ( file_exists( $path ) ) {
                $new_template = $path;
            } else {
                $new_template = YOUZIFY_TEMPLATE . 'bbpress-template.php';
            }

        }

        return $new_template;
    }

    return $template;

}

add_filter( 'youzify_template', 'youzify_bbp_youzify_template' );

/**
 * Register Youzify BBpress Templates Folder Location
 */
function youzify_bbp_register_template_location() {
    return YOUZIFY_TEMPLATE . '/bbpress';
}

/**
 * Over Load BBpress Templates.
 */
function youzify_bbp_overload_templates() {

    // Get New Templates Location
    if ( function_exists( 'bbp_register_template_stack' ) ) {
        bbp_register_template_stack( 'youzify_bbp_register_theme_template_location', 0 );
        bbp_register_template_stack( 'youzify_bbp_register_template_location', 1 );
    }

}

add_action( 'bp_init', 'youzify_bbp_overload_templates' );

/**
 * Theme Template location.
 */
function youzify_bbp_register_theme_template_location() {
    return youzify_get_theme_template_path() . '/youzify/bbpress';
}

/**
 * Forum Topic Head Meta
 */
function youzify_bbp_forum_topic_head( $args = array() ) {

    // Parse arguments against default values
    $r = bbp_parse_args( $args, array(
        'topic_id'  => 0,
        'before'    => '<div class="bbp-template-notice info"><p class="bbp-topic-description">',
        'after'     => '</p></div>',
        'size'      => 20
    ), 'get_single_topic_description' );

    // Validate topic_id
    $topic_id = bbp_get_topic_id( $r['topic_id'] );

    // Unhook the 'view all' query var adder
    remove_filter( 'bbp_get_topic_permalink', 'bbp_add_view_all' );

    // Build the topic description
    $vc_int      = bbp_get_topic_voice_count   ( $topic_id, true  );
    $voice_count = bbp_get_topic_voice_count   ( $topic_id, false );
    $reply_count = bbp_get_topic_replies_link  ( $topic_id        );
    $time_since  = bbp_get_topic_freshness_link( $topic_id        );

    // Singular/Plural
    $voice_count = sprintf( _n( '%s voice', '%s voices', $vc_int, 'youzify' ), $voice_count );

    // Topic has replies
    $last_reply = bbp_get_topic_last_reply_id( $topic_id );
    if ( !empty( $last_reply ) ) {
        $last_updated_by = bbp_get_author_link( array( 'post_id' => $last_reply, 'size' => $r['size'] ) );
        $retstr          = sprintf( esc_html__( 'last updated by %1$s %2$s', 'youzify' ), $last_updated_by, $time_since );

    // Topic has no replies
    } elseif ( ! empty( $voice_count ) && ! empty( $reply_count ) ) {
        $retstr = sprintf( esc_html__( 'This topic contains %1$s and has %2$s.', 'youzify' ), $voice_count, $reply_count );

    // Topic has no replies and no voices
    } elseif ( empty( $voice_count ) && empty( $reply_count ) ) {
        $retstr = sprintf( esc_html__( 'This topic has no replies.', 'youzify' ), $voice_count, $reply_count );
    }

    // Add the 'view all' filter back
    add_filter( 'bbp_get_topic_permalink', 'bbp_add_view_all' );

    ?>

    <div class="youzify-bbp-topic-head-meta">
        <div class="youzify-bbp-topic-head-meta-item youzify-bbp-head-meta-last-updated"><?php echo $retstr; ?></div>
        <div class="youzify-bbp-topic-head-meta-item">
            <i class="fas fa-microphone-alt"></i><?php echo $voice_count; ?>
        </div>
        <div class="youzify-bbp-topic-head-meta-item">
            <i class="far fa-comments"></i><?php echo $reply_count; ?>
        </div>
    </div>

    <?php

}

/**
 * Get Topic Icon
 */
function youzify_bbp_get_topic_icon( $topic_id = null ) {

    $icon = 'fas fa-file-alt';

    if ( bbp_is_topic_sticky( $topic_id ) ) {
        $icon = 'fas fa-thumbtack';
    } elseif( bbp_get_topic_status( $topic_id ) == 'closed' ) {
        $icon = 'fas fa-lock';
    } elseif( bbp_get_topic_status( $topic_id ) == 'trash' ) {
        $icon = 'fas fa-trash-alt';
    } elseif( bbp_get_topic_status( $topic_id ) == 'pending' ) {
        $icon = 'fas fa-ellipsis-h';
    } elseif ( bbp_get_topic_status( $topic_id ) == 'spam' ) {
        $icon = 'fas fa-ban';
    } elseif ( bbp_is_topic_anonymous( $topic_id ) ) {
        $icon = 'fas fa-user-secret';
    }

    return '<i class="' . $icon . '"></i>';

}

/**
 * Get Forum Icon
 */
function youzify_bbp_get_forum_icon( $forum_id = null ) {

    $icon = 'fas fa-comment-dots';

    if ( bbp_is_topic_sticky( $forum_id ) ) {
        $icon = 'fas fa-thumb-tack';
    } elseif( bbp_get_forum_visibility( $forum_id ) == 'hidden' ) {
        $icon = 'fas fa-eye-slash';
    } elseif( bbp_get_forum_visibility( $forum_id ) == 'private' ) {
        $icon = 'fas fa-user-secret';
    } elseif ( bbp_get_forum_status( $forum_id ) == 'closed' ) {
        $icon = 'fas fa-lock';
    } elseif ( bbp_is_topic_anonymous( $forum_id ) ) {
        $icon = 'fas fa-user-secret';
    }

    return '<i class="' . $icon . '"></i>';

}

/**
 * Get Single Forum Meta
 */
function youzify_bbp_single_forum_head_meta(  $args = '' ) {

    // Parse arguments against default values
    $r = bbp_parse_args( $args, array(
        'forum_id'  => 0,
        'before'    => '<div class="bbp-template-notice info"><p class="bbp-forum-description">',
        'after'     => '</p></div>',
        'size'      => 14,
        'feed'      => true
    ), 'get_single_forum_description' );

    // Validate forum_id
    $forum_id = bbp_get_forum_id( $r['forum_id'] );

    // Unhook the 'view all' query var adder
    remove_filter( 'bbp_get_forum_permalink', 'bbp_add_view_all' );

    // Get some forum data
    $tc_int      = bbp_get_forum_topic_count( $forum_id, false );
    $rc_int      = bbp_get_forum_reply_count( $forum_id, false );
    $topic_count = bbp_get_forum_topic_count( $forum_id );
    $reply_count = bbp_get_forum_reply_count( $forum_id );
    $last_active = bbp_get_forum_last_active_id( $forum_id );

    // Has replies
    if ( ! empty( $reply_count ) ) {
        $reply_text = sprintf( _n( '%s reply', '%s replies', $rc_int, 'youzify' ), $reply_count );
    }

    // Forum has active data
    if ( !empty( $last_active ) ) {
        $topic_text      = bbp_get_forum_topics_link( $forum_id );
        $time_since      = bbp_get_forum_freshness_link( $forum_id );
        $last_updated_by = bbp_get_author_link( array( 'post_id' => $last_active, 'size' => $r['size'] ) );

    // Forum has no last active data
    } else {
        $topic_text      = sprintf( _n( '%s topic', '%s topics', $tc_int, 'youzify' ), $topic_count );
    }

    // Forum has active data
    if ( !empty( $last_active ) ) {

        if ( !empty( $reply_count ) ) {

            if ( bbp_is_forum_category( $forum_id ) ) {
                $retstr = sprintf( esc_html__( 'last updated by %1$s %2$s.', 'youzify' ),$last_updated_by, $time_since );
            } else {
                $retstr = sprintf( esc_html__( 'last updated by %1$s %2$s.', 'youzify' ), $last_updated_by, $time_since );
            }

        } else {

            if ( bbp_is_forum_category( $forum_id ) ) {
                $retstr = sprintf( esc_html__( 'last updated by %1$s %2$s.', 'youzify' ), $last_updated_by, $time_since );
            } else {
                $retstr = sprintf( esc_html__( 'last updated by %1$s %2$s.', 'youzify' ), $last_updated_by, $time_since );
            }
        }

    }

    // Add the 'view all' filter back
    add_filter( 'bbp_get_forum_permalink', 'bbp_add_view_all' );

    ?>

    <div class="youzify-bbp-topic-head-meta">
        <?php if ( isset( $retstr ) ) : ?>
        <div class="youzify-bbp-topic-head-meta-item youzify-bbp-head-meta-last-updated"><?php echo $retstr; ?></div>
        <?php endif; ?>

        <?php if ( isset( $topic_text ) ) : ?>
        <div class="youzify-bbp-topic-head-meta-item">
            <i class="fas fa-pencil-alt"></i><?php echo $topic_text; ?>
        </div>
        <?php endif; ?>
        <?php if ( isset( $reply_text ) ) : ?>
        <div class="youzify-bbp-topic-head-meta-item">
            <i class="far fa-comments"></i><?php echo $reply_text; ?>
        </div>
        <?php endif; ?>
    </div>
<?php
}

/**
 * BBpress Enqueue scripts.
 */
function youzify_bbpress_scripts( $hook_suffix ) {

    // Disable BBpress styling.
    wp_dequeue_style( 'bbp-default' );
    wp_dequeue_style( 'bbp-default-rtl' );
    wp_enqueue_style( 'youzify-bbpress', YOUZIFY_ASSETS . 'css/youzify-bbpress.min.css', array(), YOUZIFY_VERSION );

}

add_action( 'wp_enqueue_scripts', 'youzify_bbpress_scripts' );

/**
 * Add create forum step class.
 */
function youzify_group_create_forum_step_class( $class ) {

    if ( ! bp_is_group_creation_step( 'forum' ) ) {
        return $class;
    }

    $class[] = 'youzify-group-create-forum-step';

    return $class;
}

add_filter( 'youzify_group_class', 'youzify_group_create_forum_step_class' );

/**
 * Call Forum Sidebar
 */
function youzify_get_forum_sidebar() {
    // Display Widgets.
    if ( is_active_sidebar( 'youzify-forum-sidebar' ) ) {
        dynamic_sidebar( 'youzify-forum-sidebar' );
    }
}

add_action( 'youzify_forum_sidebar', 'youzify_get_forum_sidebar' );

/**
 * Display Forum Sidebar.
 */
function youzify_show_forum_sidebar() {
    return apply_filters( 'youzify_show_forum_sidebar', true );
}

/**
 * Get Like Button
 */
function youzify_bbp_topic_favorite_link() {

    bbp_topic_favorite_link(
        array(
            'before' => '',
            'favorite' => '<span class="youzify-toggle-btn">' . __( 'Like', 'youzify' ) . '</span>',
            'favorited' => '<span class="youzify-toggle-btn">' . __( 'Unlike', 'youzify' ) . '</span>',
        )
    );

}

/**
 * Get Group Page Class.
 */
function youzify_forums_page_class() {

    // New Array
    $class = array();

    // Get Group Page
    $class[] = 'youzify-page youzify-forum';

    // Get Group Width Type
    $class[] = 'youzify-wild-content';

    // Get Tabs List Icons Style
    $class[] = youzify_option( 'youzify_tabs_list_icons_style', 'youzify-tabs-list-gradient' );

    // Get Page Buttons Style
    $class[] = 'youzify-page-btns-border-' . youzify_option( 'youzify_buttons_border_style', 'oval' );

    // Get Elements Border Style.
    $class[] = 'youzify-wg-border-' . youzify_option( 'youzify_wgs_border_style', 'radius' );

    $class = apply_filters( 'youzify_forum_class', $class );

    return youzify_generate_class( $class );
}

/**
 * Enable Groups breadcrumb.
 */
add_filter( 'bbp_no_breadcrumb', '__return_false', 20 );

/**
 * Default Options
 */
function youzify_bbpress_default_options( $options ) {
    return youzify_array_merge( $options, array(
        'youzify_enable_bbpress' => 'on',
        'youzify_forums_tab_icon' => 'far fa-comments',
        'youzify_ctabs_forums_subscriptions_icon' => 'fas fa-bell',
        'youzify_ctabs_forums_topics_icon' => 'fas fa-file-alt',
        'youzify_ctabs_forums_replies_icon' => 'far fa-comments',
        'youzify_ctabs_forums_favorites_icon' => 'fas fa-thumbs-up',
        'youzify_ctabs_forums_engagements_icon' => 'fas fa-handshake',
    ) );
}

add_filter( 'youzify_default_options', 'youzify_bbpress_default_options' );

/**
 * Hide Bbpress Duplicated Filters
 **/
// add_filter( 'bp_get_activity_show_filters_options', 'youzify_hide_duplicated_bbpress_activity_filters' );
function youzify_hide_duplicated_bbpress_activity_filters( $filters ) {
    unset( $filters['bbp_topic_create'], $filters['bbp_reply_create'] );
    return $filters;
}