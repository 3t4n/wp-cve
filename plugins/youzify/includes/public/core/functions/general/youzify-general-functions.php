<?php

/**
 * Set Default ProfileTab.
 */
function youzify_set_profile_default_tab() {

    // Get Default Tab.
    $default_tab = youzify_option( 'youzify_profile_default_tab', 'overview' );

    if ( ! empty( $default_tab ) )  {

        buddypress()->active_components[ $default_tab ] = 1;

        // Set Default Tab
        if ( ! defined( 'BP_DEFAULT_COMPONENT' ) ) {
            define( 'BP_DEFAULT_COMPONENT', $default_tab );
        }

    }

}

add_action( 'bp_init', 'youzify_set_profile_default_tab', 99 );

/**
 * Youzify Options
 */
function youzify_options( $option_id ) {

    // Get Option Value.
    $option_value = ! is_multisite() ? get_option( $option_id ) : get_blog_option( null, $option_id );

    if ( empty( $option_value ) ) {

        // Get Default Options.
        $default_options = youzify_default_options();

        // Check if option exists.
        if ( isset( $default_options[ $option_id ] ) ) {
            $option_value = $default_options[ $option_id ];
        }

    }

    return $option_value;
}

/**
 * Get Option
 */
function youzify_option( $option, $default = null ) {

    if ( ! is_multisite() ) {
        $option_value = get_option( $option, $default );
    } else {
        if ( apply_filters( 'youzify_activate_blog_mode', true, $option ) ) {
            $option_value = get_blog_option( null, $option, $default );
        } else {
            $option_value = get_site_option( $option, $default );
        }
    }

    return $option_value;
}

/**
 * Update Option
 */
function youzify_update_option( $option, $value = null, $autoload = false ) {

    if ( ! is_multisite() ) {
        $option_value = update_option( $option, $value, $autoload );
    } else {
        if ( apply_filters( 'youzify_activate_blog_mode', true, $option ) ) {
            $option_value = update_blog_option( null, $option, $value );
        } else {
            $option_value = update_site_option( $option, $value );
        }
    }

    return $option_value;
}

/**
 * Delete Option
 */
function youzify_delete_option( $option ) {

     if ( ! is_multisite() ) {
        $option_value = delete_option( $option );
    } else {

        if ( apply_filters( 'youzify_activate_blog_mode', true, $option ) ) {
            $option_value = delete_blog_option( null, $option );
        } else {
            $option_value = delete_site_option( $option );
        }

    }

    return $option_value;
}

/**
 * Get Image
 */
function youzify_get_image_attributes( $attachment_id, $size, $element, $item_id = null ) {

    // Get Attachment URL.
    $url = youzify_get_attachment_image_url( $attachment_id, $size, $element );

    return apply_filters( 'youzify_get_image_attributes', "src='$url'", $url, $size, $element, $item_id );

}

/**
 * Get Image
 */
function youzify_get_image_attributes_by_link( $url) {
    return apply_filters( 'youzify_get_image_attributes', "src='$url'", $url );
}

/**
 * Get Image Src
 */
function youzify_get_attachment_image_url( $attachment_id, $size, $element ) {

    // Filter Size.
    $size = apply_filters( 'youzify_get_attachment_image_size', $size, $element );

    return wp_get_attachment_image_url( $attachment_id, $size );
}

/**
 * Get Option Array Values
 */
function youzify_get_select_options( $option_id ) {

    // Set Up Variables
    $array_values  = array();
    $option_value  = youzify_option( $option_id );

    // Get Default Value
    if ( ! $option_value ) {
        $Youzify_default_options = youzify_default_options();
        $option_value = $Youzify_default_options[ $option_id ];
    }

    foreach ( $option_value as $key => $value ) {
        $array_values[ $value ] = $value;
    }

    return $array_values;
}

/**
 * Youzify Default Options .
 */
function youzify_default_options() {

    $default_options = array(

        // Author Box
        'youzify_display_author_networks'    => 'on',
        'youzify_enable_author_pattern'      => 'on',
        'youzify_enable_author_overlay'      => 'on',
        'youzify_author_photo_border_style'  => 'circle',
        'youzify_author_sn_bg_type'          => 'silver',
        'youzify_author_sn_bg_style'         => 'radius',
        'youzify_author_meta_type'           => 'full_location',
        'youzify_author_meta_icon'           => 'fas fa-map-marker',
        'youzify_author_layout'              => 'youzify-author-v1',
        'youzify_display_author_first_statistic' => 'on',
        'youzify_display_author_third_statistic' => 'on',
        'youzify_display_author_second_statistic'=> 'on',
        'youzify_author_first_statistic' => 'posts',
        'youzify_author_third_statistic' => 'views',
        'youzify_author_second_statistic'=> 'comments',

        // Lighting Mode
        'youzify_allow_lighting_edition' => 'on',
        'youzify_default_lighting_mode' => 'light',

        // Author Statistics.
        'youzify_author_use_statistics_bg' => 'on',
        'youzify_display_widget_networks' => 'on',
        'youzify_author_use_statistics_borders' => 'on',

        // User Profile Header
        'youzify_profile_photo_effect'           => 'on',
        'youzify_display_header_site'            => 'on',
        'youzify_display_header_networks'        => 'on',
        'youzify_display_header_location'        => 'on',
        'youzify_enable_header_pattern'          => 'on',
        'youzify_enable_header_overlay'          => 'on',
        'youzify_header_enable_user_status'      => 'on',
        'youzify_header_photo_border_style'      => 'circle',
        'youzify_header_sn_bg_type'              => 'colorful',
        'youzify_header_sn_bg_style'             => 'radius',
        'youzify_header_layout'                  => 'hdr-v1',
        'youzify_header_meta_type'               => 'full_location',
        'youzify_hheader_meta_type_1'            => 'full_location',
        'youzify_hheader_meta_type_2'            => 'user_url',
        'youzify_header_meta_icon'               => 'fas fa-map-marker-alt',
        'youzify_hheader_meta_icon_1'            => 'fas fa-map-marker-alt',
        'youzify_hheader_meta_icon_2'       	    => 'fas fa-link',
        'youzify_header_use_statistics_bg'       => 'on',
        'youzify_header_use_statistics_borders'  => 'off',
        'youzify_display_header_first_statistic' => 'on',
        'youzify_display_header_third_statistic' => 'on',
        'youzify_display_header_second_statistic'=> 'on',
        'youzify_header_first_statistic'         => 'posts',
        'youzify_header_third_statistic'         => 'views',
        'youzify_header_second_statistic'        => 'comments',

        // Group Header
        'youzify_group_photo_effect'                 => 'on',
        'youzify_display_group_header_privacy'       => 'on',
        'youzify_display_group_header_posts'         => 'on',
        'youzify_display_group_header_members'       => 'on',
        'youzify_display_group_header_networks'      => 'on',
        'youzify_display_group_header_activity'      => 'on',
        'youzify_enable_group_header_pattern'        => 'on',
        'youzify_enable_group_header_overlay'        => 'on',
        'youzify_enable_group_header_avatar_border'  => 'on',
        'youzify_group_header_use_avatar_as_cover'   => 'on',
        'youzify_group_header_sn_bg_type'            => 'silver',
        'youzify_group_header_sn_bg_style'           => 'circle',
        'youzify_group_header_layout'                => 'hdr-v1',
        'youzify_group_header_avatar_border_style'   => 'circle',
        'youzify_group_header_use_statistics_bg'     => 'on',
        'youzify_group_header_use_statistics_borders'=> 'off',

        // WP Navbar
        'youzify_disable_wp_menu_avatar_icon' => 'on',

        // Navbar
        'youzify_display_navbar_icons' => 'on',
        'youzify_profile_navbar_menus_limit' => 5,
        'youzify_navbar_icons_style' => 'navbar-inline-icons',
        'youzify_vertical_layout_navbar_type' => 'wild-navbar',

        // Courses Tab
        'youzify_profile_courses_per_page'  => 5,
        'youzify_make_courses_tab_public'  => 'off',
        'youzify_display_course_enrolment_status'    => 'on',
        'youzify_display_course_author'    => 'on',
        'youzify_display_course_date'    => 'on',
        'youzify_display_course_excerpt'    => 'on',
        'youzify_display_course_completion_bar'    => 'on',
        'youzify_display_course_completion_percent'    => 'on',
        'youzify_display_course_completed_steps'    => 'on',

        // Posts Tab
        'youzify_profile_posts_per_page'  => 5,
        'youzify_display_post_meta'       => 'on',
        'youzify_display_post_excerpt'    => 'on',
        'youzify_display_post_date'       => 'on',
        'youzify_display_post_cats'       => 'on',
        'youzify_display_post_comments'   => 'on',
        'youzify_display_post_readmore'   => 'on',
        'youzify_display_post_meta_icons' => 'on',

        // Comments Tab
        'youzify_profile_comments_nbr'     => 5,
        'youzify_display_comment_date'     => 'on',
        'youzify_display_view_comment'     => 'on',
        'youzify_display_comment_username' => 'on',
        'youzify_display_comment_title'    => 'on',

        // Media Tab
        'youzify_user-media_tab_icon'        => 'fas fa-photo-video',

        // Widgets Settings
        'youzify_display_wg_title_icon' => 'on',
        'youzify_use_wg_title_icon_bg'  => 'on',
        'youzify_wgs_border_style'      => 'radius',
        'youzify_activity_stream_layout' => 'youzify-wall-right-sidebar',
        'youzify_profile_layout'        => 'youzify-right-sidebar',
        'youzify_profile_main_sidebar'  => 'youzify-right-sidebar',
        'youzify_profile_vertical_header_position'  => 'left',

        // Display Widget Titles
        'youzify_wg_link_display_title'      => 'off',
        'youzify_wg_quote_display_title'     => 'off',
        'youzify_wg_slideshow_display_title' => 'off',
        'youzify_wg_user_tags_display_title' => 'off',
        'youzify_wg_media_display_title'     => 'on',
        'youzify_wg_video_display_title'     => 'on',
        'youzify_wg_rposts_display_title'    => 'on',
        'youzify_wg_skills_display_title'    => 'on',
        'youzify_wg_flickr_display_title'    => 'on',
        'youzify_wg_about_me_display_title'  => 'on',
        'youzify_wg_services_display_title'  => 'on',
        'youzify_wg_portfolio_display_title' => 'on',
        'youzify_wg_friends_display_title'   => 'on',
        'youzify_wg_reviews_display_title'   => 'on',
        'youzify_wg_groups_display_title'    => 'on',
        'youzify_wg_instagram_display_title' => 'on',
        'youzify_wg_user_badges_display_title' => 'on',
        'youzify_wg_gamipress_user_badges_display_title' => 'on',
        'youzify_wg_gamipress_user_balance_display_title' => 'off',
        'youzify_wg_user_balance_display_title' => 'off',
        'youzify_wg_social_networks_display_title' => 'on',

        // Widget Titles
        'youzify_wg_post_title'                   => __( 'Post', 'youzify' ),
        'youzify_wg_project_title'                => __( 'Project', 'youzify' ),
        'youzify_wg_link_title'                   => __( 'Link', 'youzify' ),
        'youzify_wg_video_title'                  => __( 'Video', 'youzify' ),
        'youzify_wg_media_title'                  => __( 'Media', 'youzify' ),
        'youzify_wg_quote_title'                  => __( 'Quote', 'youzify' ),
        'youzify_wg_skills_title'                 => __( 'Skills', 'youzify' ),
        'youzify_wg_flickr_title'                 => __( 'Flickr', 'youzify' ),
        'youzify_wg_reviews_title'                => __( 'Reviews', 'youzify' ),
        'youzify_wg_friends_title'                => __( 'Friends', 'youzify' ),
        'youzify_wg_groups_title'                 => __( 'Groups', 'youzify' ),
        'youzify_wg_aboutme_title'                => __( 'About Me', 'youzify' ),
        'youzify_wg_services_title'               => __( 'Services', 'youzify' ),
        'youzify_wg_portfolio_title'              => __( 'Portfolio', 'youzify' ),
        'youzify_wg_instagram_title'              => __( 'Instagram', 'youzify' ),
        'youzify_wg_user_tags_title'              => __( 'User Tags', 'youzify' ),
        'youzify_wg_slideshow_title'              => __( 'Slideshow', 'youzify' ),
        'youzify_wg_rposts_title'                 => __( 'Recent Posts', 'youzify' ),
        'youzify_wg_sn_title'                     => __( 'Keep In Touch', 'youzify' ),
        'youzify_wg_user_badges_title'            => __( 'User Badges', 'youzify' ),
        'youzify_gamipress_wg_user_badges_title'  => __( 'Gamipress User Badges', 'youzify' ),
        'youzify_wg_user_balance_title'           => __( 'User Balance', 'youzify' ),
        'youzify_wg_gamipress_user_balance_title' => __( 'Gamipress User Balance', 'youzify' ),

        // Social Networks
        'youzify_wg_sn_bg_style'   => 'radius',
        'youzify_wg_sn_bg_type'    => 'colorful',
        'youzify_wg_sn_icons_size' => 'full-width',

        // Badges.
        'youzify_wg_max_user_badges_items' => 12,
        // Badges.
        'youzify_gamipress_wg_max_user_badges_items' => 12,

        // Skills
        'youzify_wg_max_skills' => 5,

        // Media
        'youzify_enable_groups_media' => 'on',
        'youzify_profile_media_tab_layout' => '4columns',
        'youzify_profile_media_subtab_layout' => '3columns',
        'youzify_profile_media_tab_per_page' => 8,
        'youzify_profile_media_subtab_per_page' => 24,
        'youzify_show_profile_media_tab_photos' => 'on',
        'youzify_show_profile_media_tab_videos' => 'on',
        'youzify_show_profile_media_tab_audios' => 'on',
        'youzify_show_profile_media_tab_files' => 'on',
        'youzify_group_media_tab_layout' => '4columns',
        'youzify_group_media_subtab_layout' => '3columns',
        'youzify_group_media_tab_per_page' => 8,
        'youzify_group_media_subtab_per_page' => 24,
        'youzify_show_group_media_tab_photos' => 'on',
        'youzify_show_group_media_tab_videos' => 'on',
        'youzify_show_group_media_tab_audios' => 'on',
        'youzify_show_group_media_tab_files' => 'on',
        'youzify_wg_max_media_photos' => 9,
        'youzify_wg_max_media_videos' => 9,
        'youzify_wg_max_media_audios' => 6,
        'youzify_wg_max_media_files'  => 6,
        'youzify_wg_media_filters'    => 'photos,videos,audios,files',

        // About Me
        'youzify_wg_aboutme_img_format' => 'circle',

        // Live Notifications
        'youzify_enable_live_notifications' => 'on',
        'youzify_live_notifications_interval' => 30,

        // Project
        'youzify_display_prjct_meta' => 'on',
        'youzify_display_prjct_tags' => 'on',
        'youzify_display_prjct_meta_icons' => 'on',
        'youzify_wg_project_types' => array(
            __( 'Featured Project', 'youzify' ),
            __( 'Recent Project', 'youzify' )
        ),

        // Post
        'youzify_display_wg_post_meta'       => 'on',
        'youzify_display_wg_post_readmore'   => 'on',
        'youzify_display_wg_post_tags'       => 'on',
        'youzify_display_wg_post_excerpt'    => 'on',
        'youzify_display_wg_post_date'       => 'on',
        'youzify_display_wg_post_comments'   => 'on',
        'youzify_display_wg_post_meta_icons' => 'on',
        'youzify_wg_post_types'              => array(
            __( 'Featured Post', 'youzify' ),
            __( 'Recent Post', 'youzify' )
        ),

        // Login Page Settings.
        'youzify_login_page_type' => 'url',
        'youzify_enable_ajax_login' => 'off',
        'youzify_enable_login_popup' => 'off',

        // Services
        'youzify_wg_max_services' => 4,
        'youzify_display_service_icon' => 'on',
        'youzify_display_service_text' => 'on',
        'youzify_display_service_title' => 'on',
        // 'youzify_wg_service_icon_bg_format' => 'circle',
        'youzify_wg_services_layout' => 'vertical-services-layout',

        // Slideshow
        'youzify_wg_max_slideshow_items' => 3,
        'youzify_slideshow_height_type' => 'fixed',

        // Portfolio
        'youzify_wg_max_portfolio_items' => 9,

        // Flickr
        'youzify_wg_max_flickr_items' => 6,

        // Friends
        'youzify_wg_max_friends_items' => 5,
        'youzify_wg_friends_layout' => 'list',

        // Groups
        'youzify_wg_max_groups_items' => 3,

        // Instagram
        'youzify_wg_max_instagram_items' => 9,

        // Recent Posts
        'youzify_wg_max_rposts' => 3,

        // Use Profile Effects
        'youzify_use_effects' => 'off',

        // Profile Main Content Available Widgets
        'youzify_profile_main_widgets' => array(
            'slideshow'  => 'visible',
            'project'    => 'visible',
            'skills'     => 'visible',
            'portfolio'  => 'visible',
            'quote'      => 'visible',
            'instagram'  => 'visible',
            'services'   => 'visible',
            'post'       => 'visible',
            'link'       => 'visible',
            'video'      => 'visible',
            'reviews'    => 'visible',
        ),

        // Profile Sidebar Available Widgets
        'youzify_profile_sidebar_widgets' => array (
            'login'           => 'visible',
            'user_balance'    => 'visible',
            'user_badges'     => 'visible',
            'about_me'        => 'visible',
            'wall_media'      => 'visible',
            'social_networks' => 'visible',
            'friends'         => 'visible',
            'flickr'          => 'visible',
            'groups'          => 'visible',
            'recent_posts'    => 'visible',
            'user_tags'       => 'visible',
            'email'           => 'visible',
            'address'         => 'visible',
            'website'         => 'visible',
            'phone'           => 'visible',
        ),

        // Profile 404
        'youzify_profile_404_button' => __( 'Go Back Home', 'youzify' ),
        'youzify_profile_404_desc'   => __( "We're sorry, the profile you're looking for cannot be found.", 'youzify' ),

        // Profil Scheme.
        'youzify_profile_scheme' => 'youzify-blue-scheme',
        'youzify_enable_profile_custom_scheme' => 'off',

        // Panel Options.
        'youzify_enable_panel_fixed_save_btn' => 'on',
        'youzify_panel_scheme' => 'youzify-yellow-scheme',
        'youzify_tabs_list_icons_style' => 'youzify-tabs-list-gradient',

        // Panel Messages.
        'youzify_msgbox_mailchimp' => 'on',
        'youzify_msgbox_membership_captcha' => 'on',
        'youzify_msgbox_membership_login' => 'on',
        'youzify_msgbox_mail_tags' => 'off',
        'youzify_msgbox_mail_content' => 'on',
        'youzify_msgbox_ads_placement' => 'on',
        'youzify_msgbox_profile_schemes' => 'on',
        'youzify_msgbox_profile_structure' => 'on',
        'youzify_msgbox_instagram_wg_app_setup_steps' => 'on',
        'youzify_msgbox_custom_widgets_placement' => 'on',
        'youzify_msgbox_user_badges_widget_notice' => 'on',
        'youzify_msgbox_user_balance_widget_notice' => 'on',

        // Account Settings
        'youzify_files_max_size' => 3,

        // Wall Settings
        'youzify_activity_privacy' => 'on',
        'youzify_activity_checkin' => 'on',
        'youzify_activity_mood' => 'on',
        'youzify_activity_tag_friends' => 'on',
        'youzify_enable_wall_url_preview' => 'on',
        'youzify_enable_wall_activity_loader' => 'on',
        'youzify_enable_wall_activity_effects' => 'on',
        'youzify_enable_wall_posts_shares' => 'on',
        'youzify_enable_wall_posts_reply' => 'on',
        'youzify_enable_wall_posts_likes' => 'on',
        'youzify_enable_wall_posts_comments' => 'on',
        'youzify_enable_wall_posts_deletion' => 'on',
        'youzify_wall_comments_gif' => 'on',
        'youzify_enable_activity_directory_filter_bar' => 'on',
        'youzify_attachments_max_size' => 10,
        'youzify_attachments_max_nbr'  => 200,
        'youzify_atts_allowed_images_exts' => array( 'png', 'jpg', 'jpeg', 'gif' ),
        'youzify_atts_allowed_videos_exts' => array( 'mp4', 'ogg', 'ogv', 'webm' ),
        'youzify_atts_allowed_audios_exts' => array( 'mp3', 'ogg', 'wav' ),
        'youzify_atts_allowed_files_exts'  => array( 'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar', 'zip', 'mp4', 'mp3', 'ogg', 'pfi' ),

        // Comments Attachments.
        'youzify_wall_comments_attachments' => 'on',
        'youzify_wall_comments_attachments_max_size' => 10,
        'youzify_wall_comments_attachments_extensions' => array(
            'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar', 'zip', 'mp4', 'mp3', 'wav', 'ogg', 'pfi'
        ),

        // Messages Attachments.
        'youzify_messages_attachments' => 'on',
        'youzify_messages_attachments_max_size' => 10,
        'youzify_messages_attachments_extensions' => array(
            'png', 'jpg', 'jpeg', 'gif', 'doc', 'docx', 'pdf', 'rar',
            'zip', 'mp4', 'mp3', 'wav', 'ogg', 'pfi'
        ),

        // Reviews Settings
        'youzify_enable_reviews' => 'off',
        'youzify_user_reviews_privacy' => 'public',
        'youzify_enable_author_box_ratings' => 'on',
        'youzify_allow_users_reviews_edition' => 'off',
        'youzify_profile_reviews_per_page' => 25,
        'youzify_wg_max_reviews_items' => 3,


        // Bookmarking Posts.
        'youzify_enable_bookmarks' => 'on',
        'youzify_enable_bookmarks_privacy' => 'private',

        // Sticky Posts.
        'youzify_enable_groups_sticky_posts' => 'on',
        'youzify_enable_activity_sticky_posts' => 'on',

        // Share Posts
        'youzify_share_activity_posts' => 'on',

        // Scroll to top.
        'youzify_lazy_load' => 'on',
        'youzify_images_compression_quality' => 90,
        'youzify_compress_images' => 'on',
        'youzify_display_scrolltotop' => 'on',

        // Wall Posts Per Page
        'youzify_activity_wall_posts_per_page' => 5,
        'youzify_profile_wall_posts_per_page' => 5,
        'youzify_groups_wall_posts_per_page' => 5,

        // Wall Settings.
        'youzify_enable_wall_file' => 'on',
        'youzify_enable_wall_link' => 'on',
        'youzify_enable_wall_photo' => 'on',
        'youzify_enable_wall_audio' => 'on',
        'youzify_enable_wall_video' => 'on',
        'youzify_enable_wall_quote' => 'on',
        'youzify_enable_wall_status' => 'on',
        'youzify_enable_wall_giphy' => 'on',
        'youzify_enable_wall_comments' => 'off',
        'youzify_enable_wall_new_cover' => 'on',
        'youzify_enable_wall_new_member' => 'on',
        'youzify_enable_wall_slideshow' => 'on',
        'youzify_enable_wall_filter_bar' => 'on',
        'youzify_enable_wall_new_avatar' => 'on',
        'youzify_enable_wall_joined_group' => 'on',
        'youzify_enable_wall_posts_embeds' => 'on',
        'youzify_enable_wall_new_blog_post' => 'on',
        'youzify_enable_wall_created_group' => 'on',
        'youzify_enable_wall_comments_embeds' => 'on',
        'youzify_enable_wall_updated_profile' => 'off',
        'youzify_enable_wall_new_blog_comment' => 'off',
        'youzify_enable_wall_friendship_created' => 'on',
        'youzify_enable_wall_friendship_accepted' => 'on',

        // Profile Settings
        'youzify_allow_private_profiles' => 'off',

        // Members Directory
        'youzify_md_users_per_page' => 18,
        'youzify_md_card_meta_icon' => 'fas fa-at',
        'youzify_enable_md_cards_cover' => 'on',
        'youzify_enable_md_cards_status' => 'on',
        'youzify_show_md_cards_online_only' => 'on',
        'youzify_enable_md_users_statistics' => 'on',
        'youzify_md_card_meta_field' => 'user_login',
        'youzify_enable_md_custom_card_meta' => 'off',
        'youzify_enable_md_cards_avatar_border' => 'off',
        'youzify_enable_md_user_followers_statistics' => 'on',
        'youzify_enable_md_user_following_statistics' => 'on',
        'youzify_enable_md_user_points_statistics' => 'on',
        'youzify_enable_md_user_views_statistics' => 'on',
        'youzify_enable_md_cards_actions_buttons' => 'on',
        'youzify_enable_md_user_posts_statistics' => 'on',
        'youzify_enable_md_user_friends_statistics' => 'on',
        'youzify_enable_md_user_comments_statistics' => 'on',

        // Groups Directory
        'youzify_gd_groups_per_page' => 18,
        'youzify_enable_gd_cards_cover' => 'on',
        'youzify_enable_gd_groups_statistics' => 'on',
        'youzify_enable_gd_cards_avatar_border' => 'on',
        'youzify_enable_gd_cards_actions_buttons' => 'on',
        'youzify_enable_gd_group_posts_statistics' => 'on',
        'youzify_enable_gd_group_members_statistics' => 'on',
        'youzify_enable_gd_group_activity_statistics' => 'on',

        // Groups Directory - Styling
        'youzify_gd_cards_avatar_border_style' => 'circle',
        'youzify_gd_cards_buttons_layout' => 'block',

        // Members Directory - Styling
        'youzify_md_cards_buttons_layout' => 'block',
        'youzify_md_cards_avatar_border_style' => 'circle',

        // Custom Styling.
        'youzify_enable_global_custom_styling'   => 'off',
        'youzify_enable_profile_custom_styling'  => 'off',
        'youzify_enable_account_custom_styling'  => 'off',
        'youzify_enable_activity_custom_styling' => 'off',
        'youzify_enable_groups_custom_styling'   => 'off',
        'youzify_enable_groups_directory_custom_styling'  => 'off',
        'youzify_enable_members_directory_custom_styling' => 'off',

        // Emoji Settings.
        'youzify_enable_posts_emoji' => 'on',
        'youzify_enable_comments_emoji' => 'on',
        'youzify_enable_messages_emoji' => 'on',
        'youzify_enable_messages_attachments' => 'on',

        // General.
        'youzify_buttons_border_style' => 'oval',
        'youzify_activate_membership_system' => 'on',

        // Account Verification
        'youzify_enable_account_verification' => 'on',

        // Login Form
        'youzify_login_form_enable_header'     => 'on',
        'youzify_user_after_login_redirect'    => 'home',
        'youzify_after_logout_redirect'        => 'login',
        'youzify_admin_after_login_redirect'   => 'dashboard',
        'youzify_login_form_layout'            => 'form-field-v1',
        'youzify_login_icons_position'         => 'form-icons-left',
        'youzify_login_actions_layout'         => 'form-actions-v1',
        'youzify_login_btn_icons_position'     => 'form-icons-left',
        'youzify_login_btn_format'             => 'form-border-radius',
        'youzify_login_fields_format'          => 'form-border-flat',
        'youzify_login_form_title'             => __( 'Login', 'youzify' ),
        'youzify_login_signin_btn_title'       => __( 'Log In', 'youzify' ),
        'youzify_login_register_btn_title'     => __( 'Create New Account', 'youzify' ),
        'youzify_login_lostpswd_title'         => __( 'Lost password?', 'youzify' ),
        'youzify_login_form_subtitle'          => __( 'Sign in to your account', 'youzify' ),

        // Social Login
        'youzify_social_btns_icons_position'   => 'form-icons-left',
        'youzify_social_btns_format'           => 'form-border-radius',
        'youzify_social_btns_type'             => 'form-only-icons',
        'youzify_enable_social_login'          => 'on',
        'youzify_enable_social_login_email_confirmation' => 'on',

        // Lost Password Form
        'youzify_lostpswd_form_enable_header'  => 'on',
        'youzify_lostpswd_form_title'          => __( 'Forgot your password?', 'youzify' ),
        'youzify_lostpswd_submit_btn_title'    => __( 'Reset Password', 'youzify' ),
        'youzify_lostpswd_form_subtitle'       => __( 'Reset your account password', 'youzify' ),

        // Register Form
        'youzify_membership_show_terms_privacy_note'  => 'on',
        'youzify_signup_form_enable_header'    => 'on',
        'youzify_signup_actions_layout'        => 'form-regactions-v1',
        'youzify_signup_btn_icons_position'    => 'form-icons-left',
        'youzify_signup_btn_format'            => 'form-border-radius',
        'youzify_signup_signin_btn_title'      => __( 'Log In', 'youzify' ),
        'youzify_signup_form_title'            => __( 'Sign Up', 'youzify' ),
        'youzify_signup_register_btn_title'    => __( 'Sign Up', 'youzify' ),
        'youzify_signup_form_subtitle'         => __( 'Create New Account', 'youzify' ),

        // Polls Settings
        'yzap_poll_limit_voters'         => '3',
        'yzap_poll_list_voters'          => 'on',
        'yzap_poll_options_image_enable' => 'off',
        'yzap_poll_poll_revote'         => 'on',
        'yzap_poll_options_result'      => 'on',
        'yzap_poll_options_redirection' => 'result',
        'yzap_poll_options_limit'        => '5',
        'yzap_poll_multi_options'        => 'on',
        'yzap_poll_options_selection'    => 'single',
        'yzap_poll_options_image'        => 'off',
        'yzap_poll_options_image_enable' => 'on',

        // Limit Login Settings
        'youzify_enable_limit_login' => 'on',
        'youzify_membership_long_lockout_duration'  => 86400,
        'youzify_membership_short_lockout_duration' => 43200,
        'youzify_membership_retries_duration'       => 1200,
        'youzify_membership_allowed_retries'        => 4,
        'youzify_membership_allowed_lockouts'       => 2,

        // User Tags Settings
        'youzify_enable_user_tags' => 'on',
        'youzify_enable_user_tags_icon' => 'on',
        'youzify_enable_user_tags_description' => 'on',
        'youzify_wg_user_tags_border_style' => 'radius',

        // Mail Settings
        'youzify_enable_woocommerce' => 'off',
        'youzify_enable_mailster' => 'off',
        'youzify_enable_mailchimp' => 'off',

        // Admin Toolbar & Dashboard
        'youzify_hide_subscribers_dash' => 'off',

        // Captcha.
        'youzify_enable_signup_recaptcha' => 'on',
        'youzify_signup_recaptcha_version' => 'v2',

        // Panel Messages.
        'youzify_msgbox_membership_captcha' => 'on',
        'youzify_active_styles' => array(),

    );

    if ( youzify_is_mycred_installed() ) {

        // Options.
        $mycred_options = array(
            'youzify_enable_mycred' => 'on',
            'youzify_badges_tab_icon' => 'fas fa-trophy',
            'youzify_enable_cards_mycred_badges' => 'on',
            'youzify_wg_max_card_user_badges_items' => 4,
            'youzify_mycred-history_tab_icon' => 'fas fa-history',
            'youzify_author_box_max_user_badges_items' => 3,
            'youzify_enable_author_box_mycred_badges' => 'on',
            'youzify_mycred_badges_tab_title' => __( 'Badges', 'youzify' ),
            'youzify_ctabs_mycred-history_thismonth_icon' => 'far fa-calendar-alt',
            'youzify_ctabs_leaderboard_month_icon' => 'far fa-calendar-alt',
            'youzify_ctabs_mycred-history_today_icon' => 'fas fa-calendar-check',
            'youzify_ctabs_leaderboard_today_icon' => 'fas fa-calendar-check',
            'youzify_ctabs_mycred-history_mycred-history_icon' => 'fas fa-calendar',
            'youzify_ctabs_mycred-history_thisweek_icon' => 'fas fa-calendar-times',
            'youzify_ctabs_leaderboard_week_icon' => 'fas fa-calendar-plus',
            'youzify_ctabs_mycred-history_yesterday_icon' => 'fas fa-calendar-minus',
            'youzify_ctabs_achievements_all_icon' => 'fas fa-award',
            'youzify_ctabs_achievements_earned_icon' => 'fas fa-user-check',
            'youzify_ctabs_achievements_unearned_icon' => 'fas fa-user-times',
        );

        $default_options = youzify_array_merge( $default_options, $mycred_options );
    }

    if ( youzify_is_gamipress_installed() ) {

        // Options.
        $gamipress_options = array(
            'youzify_enable_gamipress' => 'on',
            'youzify_badges_tab_icon' => 'fas fa-trophy',
            'youzify_enable_cards_gamipress_badges' => 'on',
            'youzify_wg_max_card_user_badges_items' => 4,
            'youzify_gamipress-history_tab_icon' => 'fas fa-history',
            'youzify_author_box_max_user_badges_items' => 3,
            'youzify_enable_author_box_gamipress_badges' => 'on',
            'youzify_gamipress_badges_tab_title' => __( 'Badges', 'youzify' ),
            'youzify_ctabs_gamipress-history_thismonth_icon' => 'far fa-calendar-alt',
            'youzify_ctabs_leaderboard_month_icon' => 'far fa-calendar-alt',
            'youzify_ctabs_gamipress-history_today_icon' => 'fas fa-calendar-check',
            'youzify_ctabs_leaderboard_today_icon' => 'fas fa-calendar-check',
            'youzify_ctabs_gamipress-history_gamipress-history_icon' => 'fas fa-calendar',
            'youzify_ctabs_gamipress-history_thisweek_icon' => 'fas fa-calendar-times',
            'youzify_ctabs_leaderboard_week_icon' => 'fas fa-calendar-plus',
            'youzify_ctabs_gamipress-history_yesterday_icon' => 'fas fa-calendar-minus',
            'youzify_ctabs_achievements_all_icon' => 'fas fa-award',
            'youzify_ctabs_achievements_earned_icon' => 'fas fa-user-check',
            'youzify_ctabs_achievements_unearned_icon' => 'fas fa-user-times',
        );

        $default_options = youzify_array_merge( $default_options, $gamipress_options );
    }

    return apply_filters( 'youzify_default_options', $default_options );
}

/**
 * Is Youzify Membership system is active.
 */
function youzify_is_membership_system_active() {
    $active = youzify_option( 'youzify_activate_membership_system', 'on' ) == 'off' ? false : true;
    return apply_filters( 'youzify_is_membership_system_active', $active );
}

/**
 * Get Current Page Url
 */
function youzify_get_current_page_url() {

    // Build the redirect URL.
    $redirect_url = is_ssl() ? 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']: 'http://'. $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

    return $redirect_url;
}

/**
 * Class Generator.
 */
function youzify_generate_class( $classes ) {
    // Convert Array to String.
    return implode( ' ' , array_filter( (array) $classes ) );
}

/**
 * Get Profile Photo.
 */
function youzify_get_image_url( $img_url = null ) {
    return ! empty( $img_url ) ? $img_url : YOUZIFY_ASSETS . 'images/default-img.png';
}

/**
 * Get Wordpress Pages
 */
function youzify_get_pages() {

    // Set Up Variables
    $pages    = array();
    $wp_pages = get_pages();

    // Add Default Page.
    $pages[] = __( '-- Select --', 'youzify' );

    // Add Wordpress Pages
    foreach ( $wp_pages as $page ) {
        $pages[ $page->ID ] = sprintf( __( '%1s ( ID : %2d )', 'youzify' ), $page->post_title, $page->ID );
    }

    return $pages;
}

/**
 * Popup Dialog Message
 */
function youzify_popup_dialog( $type = null ) {

    // Init Alert Types.
    $alert_types = array( 'reset_tab', 'reset_all' );

    // Get Dialog Class.
    $form_class = ( ! empty( $type ) && in_array( $type, $alert_types ) ) ? 'alert' : 'error';

    // Get Dialog Name.
    $form_type  = ( ! empty( $type ) && in_array( $type, $alert_types ) ) ? $type : 'error';

    ?>

    <div id="uk_popup_<?php echo $form_type; ?>" class="uk-popup uk-<?php echo $form_class; ?>-popup" style="display: none">
        <div class="uk-popup-container">
            <div class="uk-popup-msg"><?php

                if ( 'reset_all' == $form_type ) : ?>

                <span class="dashicons dashicons-warning"></span>
                <h3><?php _e( 'Are you sure you want to reset all the settings?', 'youzify' ); ?></h3>
                <p><?php _e( 'Be careful! this will reset all the Youzify plugin settings.', 'youzify' ); ?></p>

                <?php elseif ( 'reset_tab' == $form_type ) : ?>

                <span class="dashicons dashicons-warning"></span>
                <h3><?php _e( 'Are you sure you want to do this?', 'youzify' ); ?></h3>
                <p><?php _e( 'Be careful! This will reset all the current tab settings.', 'youzify' ); ?></p>

                <?php elseif ( 'error' == $form_type ) : ?>

                <i class="fas fa-exclamation-triangle"></i>
                <h3><?php _e( 'Oops!', 'youzify' ); ?></h3>
                <div class="uk-msg-content"></div>

            <?php endif; ?>
            </div>

            <ul class="uk-buttons"><?php

                // Get Cancel Button title.
                $confirm = __( 'Confirm', 'youzify' );
                $cancel  = ( 'error' == $form_type ) ? __( 'Got it!', 'youzify' ) : __( 'Cancel', 'youzify' );

                if ( 'reset_all' == $form_type ) : ?>
                    <li>
                        <a class="uk-confirm-popup youzify-confirm-reset" data-reset="all"><?php echo $confirm; ?></a>
                    </li>
                <?php elseif ( 'reset_tab' == $form_type ) : ?>
                    <li>
                        <a class="uk-confirm-popup youzify-confirm-reset" data-reset="tab"><?php echo $confirm; ?></a>
                    </li>
                <?php endif; ?>

                <li><a class="uk-close-popup"><?php echo $cancel; ?></a></li>

                <?php

             ?></ul>
            <i class="fas fa-times uk-popup-close"></i>
        </div>
    </div>

    <?php
}

/**
 * Form Messages.
 */
add_action( 'youzify_admin_after_form', 'youzify_form_messages' );
add_action( 'youzify_account_footer', 'youzify_form_messages' );

function youzify_form_messages() { ?>

    <div class="youzify-form-msg">
        <div id="youzify-action-message"></div>
        <div id="youzify-wait-message">
            <div class="youzify_msg wait_msg">
                <div class="youzify-msg-icon"><i class="fas fa-spinner fa-spin"></i></div>
                <span><?php _e( 'Please wait...', 'youzify' ); ?></span>
            </div>
        </div>
    </div>

    <?php

}

/**
 * Get User Data
 */
function youzify_get_user_meta( $key, $user_id = null ) {

    do_action( 'youzify_before_get_data', $key, $user_id );

    // Get User ID.
    $user_id = empty( $user_id ) ? bp_displayed_user_id() : $user_id;

    // Get user informations.
    $user_data = get_the_author_meta( $key, $user_id );

    return apply_filters( 'youzify_get_user_data', $user_data, $user_id, $key );

}

/**
 * Check if tab is a Custom Tab.
 */
function youzify_is_custom_tab( $tab_name = '' ) {
    if ( ! empty( $tab_name ) ) {
        if ( false !== strpos( $tab_name, 'youzify_custom_tab_' ) ) {
            return true;
        }
    }
    return false;
}

/**
 * Get Youzify Page Template.
 */
function youzify_template( $old_template ) {

    if ( is_admin() || youzify_is_ajax_call() || isset( $_GET['elementor-preview'] ) ) {
        return $old_template;
    }

    // New Template.
    $new_template = $old_template;

    // Check if its Youzify plugin page
    if ( apply_filters( 'youzify_enable_youzify_page', bp_current_component() ) ) {

        // Get Data.
        $path = youzify_get_theme_template_path() . '/youzify/youzify-template.php';

        if ( file_exists( $path ) ) {
            $new_template = $path;
        } else {
            $new_template = YOUZIFY_TEMPLATE . 'youzify-template.php';
        }

    }

    return apply_filters( 'youzify_template', $new_template, $old_template );

}

add_filter( 'template_include', 'youzify_template', 999 );

/**
 * Get Template Path.
 */
function youzify_get_theme_template_path() {
    // Get Path.
    $path = is_child_theme() ? get_theme_file_path() : get_template_directory();
    return apply_filters( 'youzify_get_theme_template_path', $path );
}


/**
 * Write Log.
 **/
function youzify_log( $log )  {
    if ( is_array( $log ) || is_object( $log ) ) {
        error_log( print_r( $log, true ) );
    } else {
        error_log( $log );
    }
}

/**
 * Get File URL By Name.
 */
function youzify_get_file_url( $file ) {

    if ( empty( $file ) ) {
        return false;
    }

    global $Youzify_upload_url;

    // Init Vars.
    $file_name = null;

    $compression_enabled = apply_filters( 'youzify_enable_attachments_compression', true );

    // Prepare Url.
    if ( $compression_enabled ) {
        if ( isset( $file['thumbnail'] ) && $file['thumbnail'] != 'false' ) {
            $file_name = $file['thumbnail'];
        } else {
            $file_name = youzify_save_image_thumbnail( $file );
        }
    }

    if ( empty( $file_name ) ) {

        // Get Backup File.
        $backup_file = isset( $file['file_name'] ) ? $file['file_name'] : $file;

        // Get File Name.
        $file_name = isset( $file['original'] ) ? $file['original'] : $backup_file;

    }

    // Return File Url.
    return apply_filters( 'youzify_get_file_url', $Youzify_upload_url . $file_name, $file_name, $file );

}

/**
 * Get File URL By Name.
 */
function youzify_get_media_url( $file, $show_original = false ) {

    if ( empty( $file ) ) {
        return false;
    }

    global $Youzify_upload_url;

    $file_name = '';

    // Get Compressed Image.
    if ( ! $show_original && apply_filters( 'youzify_enable_attachments_compression', true ) ) {
        $file_name = isset( $file['thumbnail'] ) ? $file['thumbnail'] : '';
    }

    if ( empty( $file_name ) ) {
        $file_name = isset( $file['original'] ) ? $file['original'] : '';
    }

    // Return File Url.
    return apply_filters( 'youzify_get_media_url', $Youzify_upload_url . $file_name, $file_name );

}

/**
 * Save New Thumbnail
 */
function youzify_save_image_thumbnail( $file, $activity_id = null ) {

    global $Youzify_upload_dir;

    // Get image from file
    $img = false;

    // Get Backup File.
    $backup_file = isset( $file['file_name'] ) ? $file['file_name'] : $file;

    // Get Filename.
    $filename = isset( $file['original'] ) ? $file['original'] : $backup_file;

    // Get File Type.
    $file_type = wp_check_filetype( $filename );

    // Get File Name.
    $file_name = pathinfo( $filename, PATHINFO_FILENAME );

    // Get File Path.
    $file_path = $Youzify_upload_dir . $filename;

    switch ( $file_type['type'] ) {

        case 'image/jpeg': {
            $img = imagecreatefromjpeg( $file_path );
            break;
        }

        case 'image/png': {
            $img = imagecreatefrompng( $file_path );
            break;
        }

    }

    if ( empty( $img ) ) {
        return false;
    }

    // Get Compression Quality.
    $quality = apply_filters( 'youzify_attachments_compression_quality', 80 );

    // Get New Image Path.
    $thumb_filename = wp_unique_filename( $Youzify_upload_dir, $file_name . '-thumb.jpg' );

    if ( imagejpeg( $img, $Youzify_upload_dir . $thumb_filename , $quality ) ) {

        imagedestroy( $img );

        return $thumb_filename;

    }

    return false;

}

/**
 * Get Notification Icon.
 */
function youzify_get_notification_icon( $args ) {

    switch ( $args->component_action ) {

        case 'new_at_mention':
            $icon = '<i class="fas fa-at"></i>';
            break;

        case 'membership_request_accepted':
            $icon = '<i class="fas fa-thumbs-up"></i>';
            break;

        case 'membership_request_rejected':
            $icon = '<i class="fas fa-thumbs-down"></i>';
            break;

        case 'member_promoted_to_admin':
            $icon = '<i class="fas fa-user-secret"></i>';
            break;

        case 'member_promoted_to_mod':
            $icon = '<i class="fas fa-shield-alt"></i>';
            break;

        case 'bbp_new_reply':
            $icon = '<i class="fas fa-comments"></i>';
            break;

        case 'update_reply':
            $icon = '<i class="far fa-comment"></i>';
            break;

        case 'comment_reply':
            $icon = '<i class="fas fa-reply-all"></i>';
            break;

        case 'new_message':
            $icon = '<i class="far fa-envelope"></i>';
            break;

        case 'friendship_request':
            $icon = '<i class="fas fa-handshake"></i>';
            break;

        case 'friendship_accepted':
            $icon = '<i class="fas fa-hand-peace"></i>';
            break;

        case 'new_membership_request':
            $icon = '<i class="fas fa-sign-in-alt"></i>';
            break;

        case 'group_invite':
            $icon = '<i class="fas fa-user-plus"></i>';
            break;

        case 'new_follow':
            $icon = '<i class="fas fa-share-alt"></i>';
            break;

        case 'youzify_new_like':
            $icon = '<i class="far fa-heart"></i>';
            break;

        case 'youzify_new_share':
            $icon = '<i class="far fa-share-square"></i>';
            break;

        default:
            $icon = '<i class="fas fa-bell"></i>';
            break;
    }

    return apply_filters( 'youzify_get_notification_icon', $icon, $args );

}

/**
 * Get Posts Excerpt.
 */
function youzify_get_excerpt( $content, $limit = 12 ) {

    $limit = apply_filters( 'youzify_excerpt_limit', $limit );

    // Strip Shortcodes
    $excerpt = strip_shortcodes( $content );

    // Strip Remaining shortcodes.
    $excerpt = preg_replace("~(?:\[/?)[^/\]]+/?\]~s", '', $excerpt );

    // Strip Tag.
    $excerpt = wp_strip_all_tags( $excerpt );

    $excerpt = explode( ' ', $excerpt, $limit );

    if ( count( $excerpt ) >= $limit ) {
        array_pop( $excerpt );
        $excerpt = implode( " ", $excerpt ) . '...';
    } else {
        $excerpt = implode( " ", $excerpt );
    }

    $excerpt = preg_replace( '`\[[^\]]*\]`', '', $excerpt );

    return apply_filters( 'youzify_get_excerpt', $excerpt, $content, $limit );
}

/**
 * Get Post Format Icon.
 */
function youzify_get_format_icon( $format = 'standard' ) {

    switch ( $format ) {

        case 'video':
            return 'fas fa-video';

        case 'image':
            return 'fas fa-image';

        case 'status':
            return 'fas fa-pencil-alt';

        case 'quote':
            return 'fas fa-quote-right';

        case 'link':
            return 'fas fa-link';

        case 'gallery':
            return 'fas fa-images';

        case 'standard':
            return 'fas fa-file-alt';

        case 'audio':
            return "fas fa-volume-up";

        default:
            return 'fas fa-pencil-alt';
    }
}

/**
 * Get Product Images
 */
function youzify_get_product_image( $args = null ) {

    if ( $args ) {
        echo "<a data-youzify-lightbox='youzify-product-{$args['id']}' href='{$args['original']}' class='youzify-product-thumbnail' style='background-image: url({$args['thumbnail']});'></a>";
    } else {
        echo '<div class="youzify-no-thumbnail">';
        echo '<div class="thumbnail-icon"><i class="fas fa-image"></i></div>';
        echo '</div>';
    }

}

/**
 * Check is Mycred is Installed & Active.
 */
function youzify_is_mycred_installed() {

    if ( ! defined( 'myCRED_VERSION' ) )  {
        return false;
    }

    return true;

}

/**
 * Check is bbpress is Installed & Active.
 */
function youzify_is_bbpress_active() {
    return youzify_option( 'youzify_enable_bbpress', 'on' ) == 'on' ? true : false;
}

/**
 * Register New Sidebars
 */
function youzify_new_sidebars() {

    register_sidebar(
        array (
            'name' => __( 'Activity Stream Right Sidebar', 'youzify' ),
            'id' => 'youzify-wall-sidebar',
            'description' => __( 'Activity right sidebar', 'youzify' ),
            'before_widget' => '<div id="%1$s" class="widget-content %2$s">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );

    register_sidebar(
        array (
            'name' => __( 'Activity Stream Left Sidebar', 'youzify' ),
            'id' => 'youzify-wall-left-sidebar',
            'description' => __( 'Activity left sidebar', 'youzify' ),
            'before_widget' => '<div id="%1$s" class="widget-content %2$s">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );

    register_sidebar(
        array (
            'name' => __( 'Groups Sidebar', 'youzify' ),
            'id' => 'youzify-groups-sidebar',
            'description' => __( 'Groups sidebar', 'youzify' ),
            'before_widget' => '<div id="%1$s" class="widget-content %2$s">',
            'after_widget' => "</div>",
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        )
    );

    if ( youzify_is_bbpress_active() ) {

        register_sidebar(
            array (
                'name' => __( 'Forum Sidebar', 'youzify' ),
                'id' => 'youzify-forum-sidebar',
                'description' => __( 'Forums pages sidebar', 'youzify' ),
                'before_widget' => '<div id="%1$s" class="widget-content %2$s">',
                'after_widget' => "</div>",
                'before_title' => '<h3 class="widget-title">',
                'after_title' => '</h3>',
            )
        );

    }
}

add_action( 'widgets_init', 'youzify_new_sidebars' );

/**
 * Get Post ID .
 */
function youzify_get_post_id( $post_type, $key_meta , $meta_value ) {

    // Get Posts
    $posts = get_posts(
        array(
            'post_type'  => $post_type,
            'meta_key'   => $key_meta,
            'meta_value' => $meta_value )
        );

    if ( isset( $posts[0] ) && ! empty( $posts ) ) {
        return $posts[0]->ID;
    }

    return false;
}

/**
 * Get Multi-Checkboxes.
 */
function youzify_get_multicheckbox_options( $option_id, $type = 'on' ) {

    // Init Array.
    $new_values = array();

    // Get Option Values.
    $options = youzify_options( $option_id );

    if ( ! empty( $options ) ) {
        // Get Values
        foreach ( $options as $option => $value ) {
            if ( $value == $type ) {
                $new_values[] = $option;
            }
        }
    } else {
        $new_values = $options;
    }

    return apply_filters( 'youzify_get_multicheckbox_options', $new_values );
}

/**
 * Get Site Roles
 */
function youzify_get_site_roles() {

    $checkbox_roles = array();

    foreach ( get_editable_roles() as $id => $role ) {
        $checkbox_roles[ $id ] = $role['name'];
    }

    return apply_filters( 'youzify_get_site_roles', $checkbox_roles );

}

/**
 * Is RT-Media Ajax Call.
 */
function youzify_is_ajax_call() {

    $is_ajax = false;

    $rt_ajax_request = youzify_get_server_var( 'HTTP_X_REQUESTED_WITH', 'FILTER_SANITIZE_STRING' );

    if ( 'xmlhttprequest' === strtolower( $rt_ajax_request ) ) {
        $is_ajax = true;
    }

    return apply_filters( 'youzify_is_ajax_call', $is_ajax );

}

/**
 * Get server variable
 */
function youzify_get_server_var( $server_key, $filter_type = 'FILTER_SANITIZE_STRING' ) {

    $server_val = '';

    if ( function_exists( 'filter_input' ) && filter_has_var( INPUT_SERVER, $server_key ) ) {
        $server_val = filter_input( INPUT_SERVER, $server_key, constant( $filter_type ) );
    } elseif ( isset( $_SERVER[ $server_key ] ) ) {
        $server_val = $_SERVER[ $server_key ];
    }

    return $server_val;

}

/**
 * Check Is Buddypress Followers installed !
 */
function youzify_is_bpfollowers_active() {
    return apply_filters( 'youzify_is_follows_active', defined( 'BP_FOLLOW_DIR' ) ? true : false );
}

/**
 * Upload Image By Url.
 **/
function youzify_upload_image_by_url( $link = false ) {

    if ( empty( $link ) ) {
        return false;
    }

    // Decode Image.
    $url_image = youzify_file_get_contents( $link );

    if ( empty( $url_image ) ) {
        return false;
    }

    global $Youzify_upload_dir, $Youzify_upload_url;

    // Get Uploaded File extension
    $ext = strtolower( pathinfo( $link, PATHINFO_EXTENSION ) );

    if ( empty( $ext ) ) {
        $ext = 'jpg';
    }

    // Get Unique File Name.
    $filename = uniqid( 'file_' ) . '.' . $ext;

    // Get File Link.
    $file_link = $Youzify_upload_dir . $filename;

    // Get Unique File Name for the file.
    while ( file_exists( $file_link ) ) {
        $filename = uniqid( 'file_' ) . '.' . $ext;
    }

    // Get File Link.
    $file_link = $Youzify_upload_dir . $filename;

    // Upload Image.
    $image_uploaded = file_put_contents( $file_link, $url_image );

    if ( $image_uploaded ) {
        return $Youzify_upload_url . $filename;
    }

    return false;

}

// Buddypress ID.
function youzify_buddypress_id() {
    return 'buddypress';
}

/*
 * Get File Format Size.
 **/
function youzify_file_format_size( $size ) {

    // Get Sizes.
    $sizes = array(
        __( 'Bytes', 'youzify' ),
        __( 'KB', 'youzify' ),
        __( 'MB', 'youzify' )
    );

    if ( 0 == $size ) {
        return( 'n/a' );
    } else {
        return ( round( $size/pow( 1024, ( $i = floor( log( $size, 1024 ) ) ) ), 2 ) . ' ' . $sizes[ $i ] );
    }

}

/**
 * Array Merge.
 */
function youzify_array_merge( $array, $array2 ) {
    foreach( $array2 as $k => $i ) {
        $array[ $k ] = $i;
    }
    return $array;
}


/**
 * Get Files Name Excerpt.
 */
function youzify_get_filename_excerpt( $name, $length = 25 ) {

    // Filter Lenght
    $length = apply_filters( 'youzify_get_filename_excerpt_length', $length );

    // Get Name Lenght.
    $text_length = strlen( $name );

    // If Name is not too long keep it.
    if ( $text_length < $length ) {
        return $name;
    }

    // Get Excerpt.
    $excerpt = substr_replace( $name, '...', $length / 2, $text_length - $length );

    // Return The New Name.
    return apply_filters( 'youzify_get_filename_excerpt', $excerpt, $name, $length );
}

/**
 * Disable Gravatars
 */
add_filter( 'bp_core_fetch_avatar_no_grav', '__return_true' );


/**
 * Get Image Size.
 */
function youzify_get_image_size( $url, $referer = null ) {

    // Get URL Data.
    $response = wp_remote_get( $url, array( 'headers' => array( 'user-agent' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2)' ) ) );

    if ( is_wp_error( $response ) ) {
        return array( 0, 0 );
    }

    // Get Data.
    $data = wp_remote_retrieve_body( $response );

    // Get Image.
    $image = imagecreatefromstring( $data );

    // Get Image Dimensions.
    $dims = array( imagesx( $image ), imagesy( $image ) );

    // Destroy Image
    imagedestroy( $image );

    // Return Dimensions.
    return $dims;

}

/**
 * Check if Review Option is Enabled.
 */
function youzify_is_reviews_active() {
    $activate = youzify_option( 'youzify_enable_reviews', 'off' ) == 'on' ? true : false;
    return apply_filters( 'youzify_is_reviews_active', $activate );
}

/**
 * Init Reviews
 */
function youzify_init_reviews() {

    if ( youzify_is_reviews_active() ) {
        global $Youzify;
        require YOUZIFY_CORE . 'class-youzify-reviews.php';
        require YOUZIFY_CORE . 'functions/youzify-reviews-functions.php';
        require YOUZIFY_CORE . 'reviews/class-youzify-reviews-query.php';
        $Youzify->reviews = new Youzify_Reviews();
    }

}

add_action( 'plugins_loaded', 'youzify_init_reviews', 999 );


/**
 * Check is Mycred is Installed & Active.
 */
function youzify_is_mycred_active() {

    if ( ! youzify_is_mycred_installed() ) {
        return false;
    }

    return apply_filters( 'youzify_is_mycred_active', 'on' == youzify_option( 'youzify_enable_mycred', 'on' ) ? true : false );

}

/**
 * Check is GamiPress is Installed & Active.
 */
function youzify_is_gamipress_active() {

    if ( ! youzify_is_gamipress_installed() ) {
        return false;
    }

    return apply_filters( 'youzify_is_gamipress_active', 'on' == youzify_option( 'youzify_enable_gamipress', 'on' ) ? true : false );

}

/**
 * Check is GamiPress is Installed & Active.
 */
function youzify_is_gamipress_installed() {

    if ( ! defined( 'GAMIPRESS_VER' ) )  {
        return false;
    }

    return true;

}

/**
 * Get Tag Attributes.
 */
function youzify_get_tag_attributes( $args = null ) {
    if ( empty( $args ) ) {
        return;
    }

    $atts = '';

    foreach ( $args as $key => $value ) {
        if ( $key == 'icon' ) {
            continue;
        }
        $atts .= "data-$key='$value'";
    }

    return apply_filters( '', $atts, $args );
}

/**
 * Get Group Cover.
 */
function youzify_get_group_cover( $group_id = null ) {

    $group_id = ! empty( $group_id ) ? $group_id : bp_get_group_id();

    // Get Cover Photo Path.
    $cover_path = bp_attachments_get_attachment( 'url', array( 'item_id' => $group_id, 'object_dir' => 'groups') );

    // Get Default Cover.
    if ( empty( $cover_path ) ) {
        $cover_path = youzify_option( 'youzify_default_groups_cover' );
    }

    // If Cover not exist use .
    if ( empty( $cover_path ) ) {
        return "<div style='background-image:url(" . YOUZIFY_ASSETS . "images/geopattern.png);' class='youzify-cover-pattern' loading='lazy'></div>";
    }

    return apply_filters( 'youzify_group_profile_cover', '<img loading="lazy" ' . youzify_get_image_attributes_by_link( $cover_path ) . ' alt="">', $group_id );

}

/**
 * Get user display name.
 */
function youzify_get_user_display_name( $user_id ) {
    // Get Username.
    $username = bp_core_get_user_displayname( $user_id );
    return apply_filters( 'youzify_user_profile_username', $username );
}

/**
 * Ajax - Exclude Youzify Media from Wordpress Media Library.
 */
add_filter( 'ajax_query_attachments_args', 'youzify_ajax_exclude_youzify_media_from_media_library', 10, 1 );

function youzify_ajax_exclude_youzify_media_from_media_library( $query = array() ) {
    $term = get_term_by( 'slug', 'youzify_media', 'category' );
    $query['category__not_in'] = array( $term->term_id );
   return $query;
}

/**
 * Convert Incomplete Class Into Object.
 */
function youzify_convert_incomplete_class_to_object( $class ) {

    $new_array = array();

    // Unserialize.
    $class = maybe_unserialize( $class );

    if ( empty( $class ) ) {
        return array();
    }

    foreach ( $class as $key => $value ) {
        if ( $key == '__PHP_Incomplete_Class_Name' || empty( $value ) ) {
            continue;
        }

        $new_array[ $key ] = $value;
    }

    return $new_array;
}

/**
 * Sanitize Fields
 */
function youzify_sanitize_fields( $items, $types ) {

    foreach ( $items as $key => $item ) {

        foreach ( $item as $field_key => $field_value ) {

            switch ( $types[ $field_key ] ) {

                case 'textarea':
                    $items[ $key ][ $field_key ] = sanitize_textarea_field( $field_value );
                    break;

                case 'url':
                    $items[ $key ][ $field_key ] = esc_url( $field_value );
                    break;

                case 'html':
                    $items[ $key ][ $field_key ] = wp_filter_post_kses( $field_value );
                    break;

                case 'color':
                    $items[ $key ][ $field_key ] = sanitize_hex_color( $field_value );
                    break;

                default:
                    $items[ $key ][ $field_key ] = sanitize_text_field( $field_value );
                    break;

            }
        }
    }

    return $items;
}

/**
 * Fix Str Length.
 */
function youzify_fix_str_length($matches) {
    $string = $matches[2];
    $right_length = strlen($string); // yes, strlen even for UTF-8 characters, PHP wants the mem size, not the char count
    return 's:' . $right_length . ':"' . $string . '";';
}

/**
 * Check if Woocommerce Integration Active.
 */
function youzify_is_woocommerce_active() {

    $active = true;

    if ( ! class_exists( 'WooCommerce' ) || 'off' == youzify_option( 'youzify_enable_woocommerce', 'off' ) ) {
        $active = false;
    }

    return apply_filters( 'youzify_is_woocommerce_active', $active );
}

/**
 * Get Social Login Session Name
 */
function youzify_get_social_login_session_name() {

    // Set Default Session Name
    $session_name = 'Youzify_Social_Login_Session';

    /**
     * WP Engine hosting needs custom cookie name to prevent caching.
     *
     * @see https://wpengine.com/support/wpengine-ecommerce/
     */
    if ( class_exists( 'WpePlugin_common', false ) ) {
        $session_name = 'wordpress_youzify_social_login';
    }

    if ( defined( 'YOUZIFY_SOCIAL_LOGIN_SESSION_NAME' ) ) {
        $session_name = YOUZIFY_SOCIAL_LOGIN_SESSION_NAME;
    }

    return apply_filters( 'youzify_social_login_session_name', $session_name );

}

/**
 * Hide Youzify Media Category from WordPress
 */
add_filter( 'widget_categories_args', 'youzify_hide_media_category_from_wordpress', 10 );

function youzify_hide_media_category_from_wordpress( $args ) {

    // Get Media Category.
    $media_category = get_category_by_slug( 'youzify_media' );

    if ( empty( $media_category ) ) {
        return $args;
    }

    // Get Excluded Categories
    $exclude_arr = array( $media_category->term_id );

    if( isset( $args['exclude'] ) && !empty( $args['exclude'] ) ) {

        // Merge Excluded Categories.
        $exclude_arr = array_unique( array_merge( explode( ',', $args['exclude'] ), $exclude_arr ) );
    }

    // Exclude Category.
    $args['exclude'] = implode( ',', $exclude_arr );

    return $args;
}

/**
 * Get Panel Profile Fields.
 */
function youzify_get_panel_profile_fields() {

    // Init Panel Fields.
    $panel_fields = array();

    // Get All Fields.
    $all_fields = youzify_get_all_profile_fields();

    foreach ( $all_fields as $field ) {

        // Get ID.
        $field_id = $field['id'];

        // Add Data.
        $panel_fields[ $field_id ] = $field['name'];

    }

    // Add User Login Option Data.
    $panel_fields['user_login'] = __( 'Username', 'youzify' );

    return $panel_fields;
}

/**
 * Hide Shortcode Menu for non logged-in users.
 */
add_filter( 'wp_nav_menu_objects', 'youzify_hide_shortcode_menu', 10 );

function youzify_hide_shortcode_menu( $items ) {

    if ( is_user_logged_in() ) {
        return $items;
    }

    foreach ( $items as $key => $item ) {

        if ( $item->title == '[youzify_account_avatar]' ) {
            unset( $items[ $key ] );
        }

    }

    return $items;
}

/**
 * Get File Type.
 */
function youzify_get_file_type( $path ) {

    // Get File Extension.
    $ext = pathinfo( $path, PATHINFO_EXTENSION );

    if ( in_array( $ext, array( 'png', 'jpg', 'jpeg', 'gif' ) ) ) {
        return 'image';
    } elseif ( in_array( $ext, array( 'mp4', 'ogg', 'ogv', 'webm', 'flv', 'wmv', 'avi', 'mov' ) ) ) {
        return 'video';
    } elseif ( in_array( $ext, array( 'mp3', 'ogg', 'wav' ) ) ) {
        return 'audio';
    } else {
        return 'file';
    }

}

// Escape Args
function youzify_escape_shortcode_args( $shortcode_args = array() ) {

    $args = array();

    if ( ! empty( $shortcode_args ) ) {
        foreach( $shortcode_args as $key => $value ) {
            $args[ $key ] = esc_attr( $value );
        }
    }
    return $args;
}

/**
 * Group Media Slug
 **/
function youzify_group_media_slug() {
    $slug = function_exists( 'is_rtmedia_page' ) ? 'group-media' : 'media';
    return apply_filters( 'youzify_group_media_slug', $slug );
}