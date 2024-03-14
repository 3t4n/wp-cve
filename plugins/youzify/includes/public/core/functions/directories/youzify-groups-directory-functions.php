<?php

/**
 * Add Groups Directory Header
 */
add_action( 'youzify_before_directory_groups_main_content', 'youzify_add_groups_directory_header' );

function youzify_add_groups_directory_header() {

    // Get Directory Header
    youzify_get_directory_header(
        'groups_directory',
        array(
            'cover_background' => youzify_option( 'youzify_gd_header_background' ),
            'search_placeholder' => __( 'Search Groups...', 'youzify' ),
            'title' => youzify_option( 'youzify_gd_header_title', __( 'Groups Directory', 'youzify' ) ),
            'subtitle' => youzify_option( 'youzify_gd_header_subtitle', __( 'Groups Directory', 'youzify' ) ),
            'cover' => youzify_option( 'youzify_gd_header_cover' )
        )
    );

}
/**
 * Get Groups Directory Class
 */
function youzify_groups_directory_class() {

    // New Array
    $directory_class = array( 'youzify-directory youzify-page youzify-groups-directory-page' );

    // Add Scheme Class
    $directory_class[] = youzify_option( 'youzify_profile_scheme', 'youzify-blue-scheme' );

    // Add Lists Icons Styles Class
    $directory_class[] = youzify_option( 'youzify_tabs_list_icons_style', 'youzify-tabs-list-gradient' );

    return youzify_generate_class( $directory_class );
}

/**
 * Get Groups Directory Group Cover.
 */
function youzify_groups_directory_group_cover( $group_id ) {

    if ( 'off' == youzify_option( 'youzify_enable_gd_cards_cover', 'on' ) ) {
        return false;
    }

    echo '<div class="youzify-cover">' . youzify_get_group_cover( $group_id ) . '</div>';

}

/**
 * Groups Directory - Edit Groups Class.
 */
function youzify_edit_group_directory_class( $classes ) {

    if ( bp_is_groups_directory() && 'on' == youzify_option( 'youzify_enable_gd_cards_cover', 'on' ) ) {
        $classes[] = 'youzify-show-cover';
    }

    return $classes;
}

add_filter( 'bp_get_group_class', 'youzify_edit_group_directory_class' );

/**
 * Groups Directory - Get Member Data Statitics.
 */
function youzify_get_group_statistics_data( $group_id ) {

    if ( 'off' == youzify_option( 'youzify_enable_gd_groups_statistics', 'on' ) ) {
        return false;
    }

    // Get Data

    ?>

    <div class="youzify-group-user-statistics">

        <?php if ( 'on' == youzify_option( 'youzify_enable_gd_group_posts_statistics', 'on' ) ) : ?>
            <?php $posts_nbr = youzify_get_group_total_posts_count( $group_id ); ?>
        <div class="youzify-data-item youzify-data-posts" data-youzify-tooltip="<?php echo sprintf( _n( '%s Post', '%s Posts', $posts_nbr, 'youzify' ), $posts_nbr ); ?>">
            <span class="dashicons dashicons-edit"></span>
        </div>
        <?php endif; ?>

        <?php if ( 'on' == youzify_option( 'youzify_enable_gd_group_activity_statistics', 'on' ) ) : ?>
        <div class="youzify-data-item youzify-data-activity" data-youzify-tooltip="<?php printf( __( 'Active %s', 'youzify' ), bp_get_group_last_active() ); ?>">
            <span class="dashicons dashicons-clock"></span>
        </div>
        <?php endif; ?>

        <?php if ( 'on' == youzify_option( 'youzify_enable_gd_group_members_statistics', 'on' ) ) : ?>
        <?php $members_count = groups_get_total_member_count( $group_id ); ?>
        <div class="youzify-data-item youzify-data-members" data-youzify-tooltip="<?php echo sprintf( _n( '%s Member', '%s Members', $members_count, 'youzify' ), bp_core_number_format( $members_count ) ); ?>">
            <span class="dashicons dashicons-groups"></span>
        </div>
        <?php endif; ?>


    </div>

    <?php
}

/**
 * Groups Directory - Get Group Buttons.
 */
function youzify_get_gd_manage_group_buttons() {

    if ( ! is_user_logged_in() || ! bp_is_groups_directory() ) {
        return false;
    }

    // Check if Current User is admin.
    if ( false == groups_is_user_admin( get_current_user_id(), bp_get_group_id() ) ) {
        return false;
    }

    ?>

    <a href="<?php echo bp_get_group_manage_url(); ?>" class="youzify-manage-group"><i class="fas fa-cogs"></i><?php _e( 'Manage Group', 'youzify' ); ?></a>

    <?php

}

add_action( 'bp_directory_groups_actions', 'youzify_get_gd_manage_group_buttons', 999 );

/**
 * Groups Directory - Max Groups Number per Page.
 */
function youzify_groups_directory_groups_per_page( $loop ) {

    if ( bp_is_groups_directory() ) {
        $loop['per_page'] = youzify_option( 'youzify_gd_groups_per_page', 18 );
    } else {

        // If isset per page value use it.
        if ( isset( $_POST['custom_args'] ) ) {

            // Get Args.
            $custom_args = json_decode( stripcslashes( $_POST['custom_args'] ), true );

            // Sanitize Args.
            $custom_args = array_map( 'sanitize_text_field', $custom_args );

            foreach ( $custom_args as $key => $value) {

                if ( ! empty( $value ) ) {
                    $loop[ $key ] = $value;
                }
            }

        }

    }

    return $loop;
}

add_filter( 'bp_after_has_groups_parse_args', 'youzify_groups_directory_groups_per_page' );

/**
 * Groups - Members List Args.
 */
add_filter( 'bp_after_group_has_members_parse_args', 'youzify_groups_members_list_args' );

function youzify_groups_members_list_args( $loop ) {
    $loop['per_page'] = 20;
    return $loop;
}

/**
 * Groups Directory - Cards Class.
 */
function youzify_groups_list_class() {

    // Init Array().
    $classes = array( 'item-list' );

    if ( ! bp_is_groups_directory() ) {
        return youzify_generate_class( $classes );
    }

    // Show Avatar Border.
    if ( 'on' == youzify_option( 'youzify_enable_gd_cards_avatar_border', 'on' ) ) {
        $classes[] = 'youzify-card-show-avatar-border';
    }

    // Add Avatar Border Style.
    $classes[] = 'youzify-card-avatar-border-' . youzify_option( 'youzify_gd_cards_avatar_border_style', 'circle' );

    // Add Buttons Layout.
    $classes[] = 'youzify-card-action-buttons-' . youzify_option( 'youzify_gd_cards_buttons_layout', 'block' );

    // Get Page Buttons Style
    $classes[] = 'youzify-page-btns-border-' . youzify_option( 'youzify_buttons_border_style', 'oval' );
;

    return youzify_generate_class( $classes );

}

/**
 * Groups Directory - Shortcode Attributes.
 */
function youzify_set_groups_directory_shortcode_atts( $loop ) {

    global $youzify_gd_shortcode_atts;

    $loop = shortcode_atts( $loop, $youzify_gd_shortcode_atts, 'youzify_groups_atts' );

    return $loop;

}

/**
 * Enable Groups Directory Component For Shortcode.
 */
function youzify_enable_groups_directory_shortcode( $active, $component ) {

    if ( $component == 'groups' ) {
        return true;
    }

    return $active;

}