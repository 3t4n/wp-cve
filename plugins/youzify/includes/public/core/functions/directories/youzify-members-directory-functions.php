<?php

/**
 * Add Members Directory Header
 */
add_action( 'youzify_before_directory_members_main_content', 'youzify_add_members_directory_header' );

function youzify_add_members_directory_header() {

    // Get Directory Header
    youzify_get_directory_header(
        'members_directory',
        array(
            'cover_background' => youzify_option( 'youzify_md_header_background' ),
            'search_placeholder' => __( 'Search Members...', 'youzify' ),
            'title' => youzify_option( 'youzify_md_header_title', __( 'Members Directory', 'youzify' ) ),
            'subtitle' => youzify_option( 'youzify_md_header_subtitle', __( 'Members Directory', 'youzify' ) ),
            'cover' => youzify_option( 'youzify_md_header_cover' )
        )
    );

}

/**
 * Get Members Directory Class
 */
function youzify_members_directory_class() {

    // New Array
    $directory_class = array( 'youzify-directory youzify-page youzify-members-directory-page' );

    // Add Scheme Class
    $directory_class[] = youzify_option( 'youzify_profile_scheme', 'youzify-blue-scheme' );

    // Add Lists Icons Styles Class
    $directory_class[] = youzify_option( 'youzify_tabs_list_icons_style', 'youzify-tabs-list-gradient' );

    return youzify_generate_class( $directory_class );
}

/**
 * Get Members Directory User Cover.
 */
function youzify_members_directory_user_cover( $user_id ) {

    if ( 'off' == youzify_option( 'youzify_enable_md_cards_cover', 'on' ) ) {
        return false;
    }

    ?>

    <div class="youzify-cover"><?php echo youzify_get_user_cover( $user_id ); ?><?php do_action( 'youzify_members_directory_cover_content' ); ?></div>

    <?php

}

/**
 * Filters Members Directory Classes.
 */
function youzify_edit_members_directory_class( $classes ) {

    // Add OffLine Class.
    if ( ! in_array( 'is-online', $classes ) && 'off' == youzify_option( 'youzify_show_md_cards_online_only', 'on' ) ) {
        $classes[] = 'is-offline';
    }

    // Remove User Status Class
    if ( 'off' == youzify_option( 'youzify_enable_md_cards_status', 'on' ) ) {

        // Get Values Keys.
        $is_online = array_search( 'is-online', $classes );
        $is_offline = array_search( 'is-offline', $classes );

        // Remove OnLine Class.
        if ( $is_online !== false ) {
            unset( $classes[ $is_online ] );
        }

        // Remove OffLine Class.
        if ( $is_offline !== false ) {
            unset( $classes[ $is_offline ] );
        }

    }

    if ( 'on' == youzify_option( 'youzify_enable_md_cards_cover', 'on' ) ) {
        $classes[] = 'youzify-show-cover';
    }

    return $classes;
}

add_filter( 'bp_get_member_class', 'youzify_edit_members_directory_class' );

/**
 * Members Directory - Max Members Per Page.
 */
function youzify_members_directory_members_per_page( $loop ) {
    
    if ( bp_is_members_directory() || isset( $_POST['scope'] ) ) {

        // Set Per Page.
        $loop['per_page'] =  youzify_option( 'youzify_md_users_per_page', 18 );

        $scope = isset( $_POST['scope'] ) ? sanitize_text_field( $_POST['scope'] ) : '';

        // Set Member Types.
        if ( ! empty( $scope ) ) {

            // Get Types Singulars
            $member_types = bp_get_member_types( array( 'has_directory' => true ) );

            if (  ! empty( $member_types ) && in_array( $scope, $member_types ) ) {
                $loop['member_type'] = $scope;
            }

        }

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

add_filter( 'bp_after_has_members_parse_args', 'youzify_members_directory_members_per_page' );

/**
 * Members Directory - Cards Class.
 */
function youzify_members_list_class() {

    // Init Array().
    $classes = array( 'item-list' );

    if ( ! bp_is_directory() ) {
        return youzify_generate_class( $classes );
    }

    // Show Avatar Border.
    if ( 'on' == youzify_option( 'youzify_enable_md_cards_avatar_border', 'off' )) {
        $classes[] = 'youzify-card-show-avatar-border';
    }

    // Add Avatar Border Style.
    $classes[] = 'youzify-card-avatar-border-' . youzify_option( 'youzify_md_cards_avatar_border_style', 'circle' );

    // Add Buttons Layout.
    $classes[] = 'youzify-card-action-buttons-' . youzify_option( 'youzify_md_cards_buttons_layout', 'block' );

    // Get Page Buttons Style
    $classes[] = 'youzify-page-btns-border-' . youzify_option( 'youzify_buttons_border_style', 'oval' );

    return youzify_generate_class( $classes );

}

/**
 * Get Members Directory User settings Button
 */
function youzify_get_md_current_user_settings( $user_id = false ) {

    if ( ! is_user_logged_in() || ! bp_is_members_directory() ) {
        return false;
    }

    // Get User Id.
    $user_id = $user_id ? $user_id : youzify_get_context_user_id();

    if ( $user_id != bp_loggedin_user_id() ) {
        return false;
    }

    ?>

    <?php if ( bp_is_active( 'xprofile' ) ) : ?>
    <a href="<?php echo youzify_get_profile_settings_url( false, $user_id ); ?>" class="youzify-profile-settings"><i class="fas fa-user-circle"></i><?php _e( 'Profile Settings', 'youzify' ); ?></a>
    <?php endif; ?>

    <?php if ( bp_is_active( 'friends' ) && bp_is_active( 'messages' ) && 'block' == youzify_option( 'youzify_md_cards_buttons_layout', 'block' ) ) : ?>

        <?php if ( bp_is_active( 'settings' ) ) : ?>
            <a href="<?php echo bp_members_get_user_url( $user_id ) . bp_get_settings_slug(); ?>" class="yzmd-second-btn"><i class="fas fa-cogs"></i><?php _e( 'Account Settings', 'youzify' ); ?></a>
        <?php else : ?>
            <a href="<?php echo youzify_get_widgets_settings_url( false, $user_id ); ?>" class="yzmd-second-btn"><i class="fas fa-sliders-h"></i><?php _e( 'Widgets Settings', 'youzify' ); ?></a>
        <?php endif; ?>

    <?php endif; ?>

    <?php
}

add_action( 'bp_directory_members_actions', 'youzify_get_md_current_user_settings' );

/**
 * Members Directory - Get Member Data Statitics.
 */
function youzify_get_member_statistics_data( $user_id ) {

	if ( 'off' == youzify_option( 'youzify_enable_md_users_statistics', 'on' ) ) {
		return false;
	}

    ?>

    <div class="youzify-user-statistics">

        <?php do_action( 'youzify_before_members_directory_card_statistics', $user_id  ); ?>

        <?php if ( 'on' == youzify_option( 'youzify_enable_md_user_posts_statistics', 'on' ) ) : ?>
            <?php $posts_nbr = youzify_get_user_posts_nbr( $user_id ); ?>
        <a <?php if (  $posts_nbr > 0 ) { ?> href="<?php echo youzify_get_user_profile_page( 'posts', $user_id ); ?>" <?php } ?> class="youzify-data-item youzify-data-posts" data-youzify-tooltip="<?php echo sprintf( _n( '%s Post', '%s Posts', $posts_nbr, 'youzify' ), $posts_nbr ); ?>">
            <span class="dashicons dashicons-edit"></span>
        </a>
        <?php endif; ?>

        <?php if ( 'on' == youzify_option( 'youzify_enable_md_user_comments_statistics', 'on' ) ) : ?>
            <?php $comments_nbr = youzify_get_comments_number( $user_id );  ?>
        <a <?php if (  $comments_nbr > 0 ) { ?>  href="<?php echo youzify_get_user_profile_page( 'comments', $user_id ); ?>" <?php } ?> class="youzify-data-item youzify-data-comments" data-youzify-tooltip="<?php echo sprintf( _n( '%s Comment', '%s Comments', $comments_nbr, 'youzify' ), $comments_nbr ); ?>">
            <span class="dashicons dashicons-format-status"></span>
        </a>
        <?php endif; ?>

        <?php if ( 'on' == youzify_option( 'youzify_enable_md_user_views_statistics', 'on' ) ) : ?>
            <?php $views_nbr = get_user_meta( $user_id, 'youzify_profile_views_count', true ); if ( ! $views_nbr ) $views_nbr = 0; ?>
        <a href="<?php echo bp_member_permalink(); ?>" class="youzify-data-item youzify-data-vues" data-youzify-tooltip="<?php echo sprintf( _n( '%s View', '%s Views', $views_nbr, 'youzify' ), $views_nbr ); ?>">
            <span class="dashicons dashicons-welcome-view-site"></span>
        </a>
        <?php endif; ?>

        <?php if ( 'on' == youzify_option( 'youzify_enable_md_user_friends_statistics', 'on' ) && bp_is_active( 'friends' ) ) :  ?>
	       <?php $friends_nbr = friends_get_total_friend_count( $user_id ); ?>
            <a href="<?php echo youzify_get_user_profile_page( 'friends', $user_id ); ?>" class="youzify-data-item youzify-data-friends" data-youzify-tooltip="<?php echo sprintf( _n( '%s Friend', '%s Friends', $friends_nbr, 'youzify' ), $friends_nbr ); ?>">
                <span class="dashicons dashicons-groups"></span>
            </a>
        <?php endif; ?>

        <?php do_action( 'youzify_after_members_directory_card_statistics', $user_id  ); ?>

    </div>

    <?php
}

/**
 * Get Card Custom Meta.
 */
function youzify_get_md_user_meta( $user_id = null ) {

    // Get Custom Card Meta Availability
    $custom_meta = youzify_option( 'youzify_enable_md_custom_card_meta', 'off' );

    if ( 'off' == $custom_meta || ! bp_is_directory() ) {
        return  apply_filters( 'youzify_members_directory_user_default_meta', '<span class="youzify-meta-item">@' . bp_members_get_user_slug( $user_id ) . '</span>' );

    }

    // Get Custom Meta Data
    $meta_icon  = youzify_option( 'youzify_md_card_meta_icon', 'at' );
    $field_id   = youzify_option( 'youzify_md_card_meta_field', 'user_login' );
    $meta_value = youzify_get_user_field_data( $field_id, $user_id );

    if ( empty( $meta_value ) ) {
        // Set Default Meta.
        $meta_html = '<i class="fas fa-at"></i>' . bp_members_get_user_slug( $user_id );
    } else {
        // Create Custom Meta HTML Code.
        $meta_html = '<i class="' . $meta_icon .'"></i>' . $meta_value;
    }

    // Filter
    $meta_html = apply_filters( 'youzify_members_directory_user_meta', '<span class="youzify-meta-item">' . $meta_html . '</span>', $meta_icon, $meta_value, $field_id, $user_id );

    return $meta_html;
}

/**
 * Get Card User Meta Value.
 */
function youzify_get_user_field_data( $field_id = null, $user_id = null ) {

    // Get Hidden Fields.
    if ( bp_is_active( 'xprofile' ) ) {

        $hidden_fields = bp_xprofile_get_hidden_fields_for_user();

        if ( in_array( $field_id, $hidden_fields ) )  {
            return;
        }

    }

    if ( bp_is_active( 'xprofile' ) && is_numeric( $field_id ) ) {
        // Get Field Data.
        $meta_value = xprofile_get_field_data( $field_id, $user_id, 'comma' );
    } elseif ( $field_id == 'full_location' ) {
        $meta_value = youzify_users()->location( true, $user_id );
    } elseif ( $field_id == 'user_url' ) {
        $meta_value = youzify_get_xprofile_field_value( 'user_url', $user_id );
    } else {
        // Get Field Data.
        $meta_value = get_the_author_meta( $field_id, $user_id );
    }

    return apply_filters( 'youzify_get_user_field_data', $meta_value, $field_id, $user_id );
}

/**
 * Display Members Directory
 */
function youzify_display_md_filter_bar() {
    return apply_filters( 'youzify_display_members_directory_filter', true );
}

/**
 * Members Directory - Shortcode Attributes.
 */
function youzify_set_members_directory_shortcode_atts( $loop ) {
    global $youzify_md_shortcode_atts;
    $loop = shortcode_atts( $loop, $youzify_md_shortcode_atts, 'youzify_members_atts' );
    return $loop;
}

/**
 * Enable Members Directory Component For Shortcode.
 */
function youzify_enable_shortcode_md( $active, $component ) {

    if ( $component == 'members' ) {
        return true;
    }

    return $active;

}


/**
 * Member Directory Filter.
 */
add_action( 'bp_members_directory_member_types', 'youzify_add_members_directory_types_tabs' );

function youzify_add_members_directory_types_tabs() {

    // Get Member Types
    $member_types = bp_get_member_types( array( 'has_directory' => true ) );

    if ( empty( $member_types ) ) {
        return false;
    }

    foreach ( $member_types as $type_id ) {

            // Get Member Type.
            $member_type = bp_get_member_type_object( $type_id );

            // Get Type
            $type_infos = bp_get_term_by( 'slug', $type_id,'bp_member_type' );

            if ( ! isset ( $type_infos->count ) || $type_infos->count < 1 ) {
                continue;
            }

        ?>
        <li id="members-<?php echo $type_id; ?>" class="yzmt-directory-tab"><?php youzify_add_member_types_tab_syling( $type_id, $member_type->db_id ); ?><a href="<?php echo bp_member_type_directory_permalink( $type_id ); ?>"><?php if ( class_exists( 'Youzify' ) ) echo '<i class="' . get_term_meta( $member_type->db_id, 'youzify_type_icon', true ) .'"></i>'; printf( __( '%1s %2s', 'youzify-member-types' ), $member_type->labels['name'], '<span>' . apply_filters( 'youzify_member_types_count', $type_infos->count, $type_id ) . '</span>' ); ?></a></li>

        <?php
    }

}

/**
 * Get Directory Stling
 */
function youzify_add_member_types_tab_syling( $type, $type_id ) {

    // Get Data.
    $left_color = get_term_meta( $type_id, 'youzify_type_bg_left_color', true );
    $right_color = get_term_meta( $type_id, 'youzify_type_bg_right_color', true );

    // if the one of the values are empty go out.
    if ( empty( $left_color ) && empty( $right_color ) ) {
        return;
    }

    // Pattern Path
    $pattern = 'url(' . YOUZIFY_URL . 'includes/public/assets/images/dotted-bg.png)';

    // Add Lists Icons Styles Class
    $icons_style = youzify_option( 'youzify_tabs_list_icons_style', 'youzify-tabs-list-gradient' );

    ?><style type="text/css">
        <?php

        // Get Selector.
        if ( $icons_style == 'youzify-tabs-list-gradient' ) {
            echo "
                .youzify-tabs-list-gradient #members-$type a i {
                    background: $pattern,linear-gradient(to right, $left_color , $right_color ) !important;
                    background: $pattern,-webkit-linear-gradient(left, $left_color , $right_color ) !important;
                }
            ";
        } elseif ( $icons_style == 'youzify-tabs-list-colorful' ) {
            echo "
                .youzify-tabs-list-colorful #members-$type a i {
                    background: $left_color;
                }
            ";
        }

        ?>
    </style>
    <?php
}
