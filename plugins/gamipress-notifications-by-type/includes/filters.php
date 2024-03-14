<?php
/**
 * Content Filters
 *
 * @package     GamiPress\Notifications\By_Type\Content_Filters
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/* ------------------------
 * DYNAMIC CSS
   ------------------------ */

/**
 * Override the notifications dynamic CSS
 *
 * @since 1.0.1
 *
 * @param string $css
 *
 * @return string
 */
function gamipress_notifications_by_type_dynamic_css( $css ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Achievement types

    $achievement_types = gamipress_get_achievement_types();

    foreach( $achievement_types as $achievement_type => $data ) {

        $background_color   = gamipress_get_post_meta( $data['ID'], $prefix . 'background_color' );
        $title_color        = gamipress_get_post_meta( $data['ID'], $prefix . 'title_color' );
        $text_color         = gamipress_get_post_meta( $data['ID'], $prefix . 'text_color' );
        $link_color         = gamipress_get_post_meta( $data['ID'], $prefix . 'link_color' );
        $selector           = ".gamipress-notification.gamipress-notification-achievement-type-{$achievement_type}";

        if( ! empty( $background_color ) )
            $css .= "$selector { background-color: $background_color; }";

        if( ! empty( $text_color ) )
            $css .= "$selector { color: $text_color; }";

        if( ! empty( $title_color ) )
            $css .= "$selector .gamipress-notification-title { color: $title_color; }";

        if( ! empty( $link_color ) )
            $css .= "$selector a { color: $link_color; }";

        // Achievements

        $achievements = get_posts( array(
            'post_type'         =>	$achievement_type,
            'posts_per_page'    =>	-1,
            'suppress_filters'  => false,
        ) );

        foreach( $achievements as $achievement ) {

            $background_color   = gamipress_get_post_meta( $achievement->ID, $prefix . 'background_color' );
            $title_color        = gamipress_get_post_meta( $achievement->ID, $prefix . 'title_color' );
            $text_color         = gamipress_get_post_meta( $achievement->ID, $prefix . 'text_color' );
            $link_color         = gamipress_get_post_meta( $achievement->ID, $prefix . 'link_color' );
            $selector           = ".gamipress-notification#gamipress-achievement-{$achievement->ID}";

            if( ! empty( $background_color ) )
                $css .= "$selector { background-color: $background_color !important; }";

            if( ! empty( $text_color ) )
                $css .= "$selector { color: $text_color !important; }";

            if( ! empty( $title_color ) )
                $css .= "$selector .gamipress-notification-title { color: $title_color !important; }";

            if( ! empty( $link_color ) )
                $css .= "$selector a { color: $link_color !important; }";
        }

    }

    // Points types

    $points_types = gamipress_get_points_types();

    foreach( $points_types as $points_type => $data ) {

        $background_color   = gamipress_get_post_meta( $data['ID'], $prefix . 'background_color' );
        $title_color        = gamipress_get_post_meta( $data['ID'], $prefix . 'title_color' );
        $text_color         = gamipress_get_post_meta( $data['ID'], $prefix . 'text_color' );
        $link_color         = gamipress_get_post_meta( $data['ID'], $prefix . 'link_color' );
        $selector           = ".gamipress-notification.gamipress-notification-points-type-{$points_type}";

        if( ! empty( $background_color ) )
            $css .= "$selector { background-color: $background_color; }";

        if( ! empty( $text_color ) )
            $css .= "$selector { color: $text_color; }";

        if( ! empty( $title_color ) )
            $css .= "$selector .gamipress-notification-title { color: $title_color; }";

        if( ! empty( $link_color ) )
            $css .= "$selector a { color: $link_color; }";

    }

    // Rank types

    $rank_types = gamipress_get_rank_types();

    foreach( $rank_types as $rank_type => $data ) {

        $background_color   = gamipress_get_post_meta( $data['ID'], $prefix . 'background_color' );
        $title_color        = gamipress_get_post_meta( $data['ID'], $prefix . 'title_color' );
        $text_color         = gamipress_get_post_meta( $data['ID'], $prefix . 'text_color' );
        $link_color         = gamipress_get_post_meta( $data['ID'], $prefix . 'link_color' );
        $selector           = ".gamipress-notification.gamipress-notification-rank-type-{$rank_type}";

        if( ! empty( $background_color ) )
            $css .= "$selector { background-color: $background_color; }";

        if( ! empty( $text_color ) )
            $css .= "$selector { color: $text_color; }";

        if( ! empty( $title_color ) )
            $css .= "$selector .gamipress-notification-title { color: $title_color; }";

        if( ! empty( $link_color ) )
            $css .= "$selector a { color: $link_color; }";

        // Ranks

        $ranks = get_posts( array(
            'post_type'         =>	$rank_type,
            'posts_per_page'    =>	-1,
            'suppress_filters'  => false,
        ) );

        foreach( $ranks as $rank ) {

            $background_color   = gamipress_get_post_meta( $rank->ID, $prefix . 'background_color' );
            $title_color        = gamipress_get_post_meta( $rank->ID, $prefix . 'title_color' );
            $text_color         = gamipress_get_post_meta( $rank->ID, $prefix . 'text_color' );
            $link_color         = gamipress_get_post_meta( $rank->ID, $prefix . 'link_color' );
            $selector           = ".gamipress-notification#gamipress-rank-{$rank->ID}";

            if( ! empty( $background_color ) )
                $css .= "$selector { background-color: $background_color !important; }";

            if( ! empty( $text_color ) )
                $css .= "$selector { color: $text_color !important; }";

            if( ! empty( $title_color ) )
                $css .= "$selector .gamipress-notification-title { color: $title_color !important; }";

            if( ! empty( $link_color ) )
                $css .= "$selector a { color: $link_color !important; }";
        }

    }

    return $css;

}
add_filter( 'gamipress_notifications_dynamic_css', 'gamipress_notifications_by_type_dynamic_css' );

/* ------------------------
 * SOUND EFFECTS
   ------------------------ */

/**
 * Override show/hide notification sound effect
 *
 * @since 1.0.4
 *
 * @param string $sound     Audio file URL to set as sound effect
 * @param object $earning
 * @param WP_Post $post
 *
 * @return string
 */
function gamipress_notifications_by_type_notification_sound( $sound, $earning, $post ) {

    $prefix         = '_gamipress_notifications_by_type_';
    $sound_type     = ( current_filter() === 'gamipress_notification_show_notification_sound' ? 'show_sound' : 'hide_sound' );
    $meta_key       = $prefix . $sound_type;
    $custom_sound   = '';

    if( in_array( $earning->post_type, gamipress_get_achievement_types_slugs() ) ) {

        // Achievement
        $custom_sound = gamipress_get_post_meta( $post->ID, $meta_key );

        // Achievement Type
        if( empty( $custom_sound ) ) {
            $achievement_type = gamipress_get_achievement_type( $earning->post_type );

            if( $achievement_type ) {
                $custom_sound = gamipress_get_post_meta( $achievement_type['ID'], $meta_key );
            }
        }

    } else if( $earning->post_type === 'step' ) {

        // Step

        // Get the step achievement
        $achievement = gamipress_get_parent_of_achievement( $post->ID );

        if( $achievement ) {

            // Achievement
            $custom_sound = gamipress_get_post_meta( $achievement->ID, $meta_key );

            // Achievement Type
            if( empty( $custom_sound ) ) {
                $achievement_type = gamipress_get_achievement_type( $achievement->post_type );

                if( $achievement_type ) {
                    $custom_sound = gamipress_get_post_meta( $achievement_type['ID'], $meta_key );
                }
            }
        }

    } else if( $earning->post_type === 'points-award' ) {

        // Points Award
        $points_type = gamipress_get_points_award_points_type( $post->ID );

        if( $points_type ) {
            $custom_sound = gamipress_get_post_meta( $points_type->ID, $meta_key );
        }

    } else if( $earning->post_type === 'points-deduct' ) {

        // Points Deduct
        $points_type = gamipress_get_points_deduct_points_type( $post->ID );

        if( $points_type ) {
            $custom_sound = gamipress_get_post_meta( $points_type->ID, $meta_key );
        }

    } else if( in_array( $earning->post_type, gamipress_get_rank_types_slugs() ) ) {

        // Rank
        $custom_sound = gamipress_get_post_meta( $post->ID, $meta_key );

        // Rank Type
        if( empty( $custom_sound ) ) {
            $rank_type = gamipress_get_rank_type( $earning->post_type );

            if( $rank_type ) {
                $custom_sound = gamipress_get_post_meta( $rank_type['ID'], $meta_key );
            }
        }

    } else if( $earning->post_type === 'rank-requirement' ) {

        // Rank Requirement

        // Get the rank requirement rank
        $rank = gamipress_get_rank_requirement_rank( $post->ID );

        if( $rank ) {

            // Rank
            $custom_sound = gamipress_get_post_meta( $rank->ID, $meta_key );

            // Rank Type
            if( empty( $custom_sound ) ) {
                $rank_type = gamipress_get_rank_type( $rank->post_type );

                if( $rank_type ) {
                    $custom_sound = gamipress_get_post_meta( $rank_type['ID'], $meta_key );
                }
            }
        }

    }

    // If sound effect gets overwritten, then return the new one
    if( ! empty( $custom_sound ) ) {
        return $custom_sound;
    }

    return $sound;

}
add_filter( 'gamipress_notification_show_notification_sound', 'gamipress_notifications_by_type_notification_sound', 10, 3 );
add_filter( 'gamipress_notification_hide_notification_sound', 'gamipress_notifications_by_type_notification_sound', 10, 3 );

/* ------------------------
 * ACHIEVEMENT
   ------------------------ */

/**
 * Override the achievement notification disable status
 *
 * @since 1.0.0
 *
 * @param bool $return
 * @param object $earning
 * @param WP_Post $achievement
 *
 * @return bool True to disable
 */
function gamipress_notifications_by_type_disable_achievements( $return, $earning, $achievement ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Achievement

    // Bail if achievements notifications are disabled for this type
    if( (bool) gamipress_get_post_meta( $achievement->ID, $prefix . 'disable_achievements' ) )
        return true;

    // Achievement Type

    // Get the achievement type ID (where is stored our custom config)
    $achievement_type = gamipress_get_achievement_type( $earning->post_type );

    // Bail if achievements notifications are disabled for this type
    if( (bool) gamipress_get_post_meta( $achievement_type['ID'], $prefix . 'disable_achievements' ) )
        return true;

    return $return;

}
add_filter( 'gamipress_notifications_disable_achievements', 'gamipress_notifications_by_type_disable_achievements', 10, 3 );

/**
 * Override the achievement notification title pattern
 *
 * @since 1.0.0
 *
 * @param string $return
 * @param object $earning
 * @param WP_Post $achievement
 *
 * @return string
 */
function gamipress_notifications_by_type_achievement_title_pattern( $return, $earning, $achievement ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Achievement

    // Get achievement custom pattern
    $title_pattern = gamipress_get_post_meta( $achievement->ID, $prefix . 'achievement_title_pattern' );

    // If not empty, override
    if( ! empty( $title_pattern ) )
        return $title_pattern;

    // Achievement Type

    // Get the achievement type ID (where is stored our custom config)
    $achievement_type = gamipress_get_achievement_type( $earning->post_type );

    // Get achievement type custom pattern
    $title_pattern = gamipress_get_post_meta( $achievement_type['ID'], $prefix . 'achievement_title_pattern' );

    // If not empty, override
    if( ! empty( $title_pattern ) )
        return $title_pattern;

    return $return;

}
add_filter( 'gamipress_notifications_achievement_title_pattern', 'gamipress_notifications_by_type_achievement_title_pattern', 10, 3 );

/**
 * Override the achievement notification content pattern
 *
 * @since 1.0.0
 *
 * @param string $return
 * @param object $earning
 * @param WP_Post $achievement
 *
 * @return string
 */
function gamipress_notifications_by_type_achievement_content_pattern( $return, $earning, $achievement ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Achievement

    // Get achievement custom pattern
    $content_pattern = gamipress_get_post_meta( $achievement->ID, $prefix . 'achievement_content_pattern' );

    // If not empty, override
    if( ! empty( $content_pattern ) )
        return $content_pattern;

    // Achievement Type

    // Get the achievement type ID (where is stored our custom config)
    $achievement_type = gamipress_get_achievement_type( $earning->post_type );

    // Get achievement type custom pattern
    $content_pattern = gamipress_get_post_meta( $achievement_type['ID'], $prefix . 'achievement_content_pattern' );

    // If not empty, override
    if( ! empty( $content_pattern ) )
        return $content_pattern;

    return $return;

}
add_filter( 'gamipress_notifications_achievement_content_pattern', 'gamipress_notifications_by_type_achievement_content_pattern', 10, 3 );

/**
 * Override the achievement notification achievement template args
 *
 * @since 1.0.0
 *
 * @param array $template_args
 * @param object $earning
 * @param WP_Post $achievement
 *
 * @return array
 */
function gamipress_notifications_by_type_achievement_template_args( $template_args, $earning, $achievement ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Get the achievement type ID (where is stored our custom config)
    $achievement_type = gamipress_get_achievement_type( $earning->post_type );

    $override = (bool) gamipress_get_post_meta( $achievement->ID, $prefix . 'override_achievement_template_args' );
    $override_from_type = (bool) gamipress_get_post_meta( $achievement_type['ID'], $prefix . 'override_achievement_template_args' );

    // Bail if override from single and from type are not checked
    if( ! $override && ! $override_from_type )
        return $template_args;

    $original_achievement_fields = GamiPress()->shortcodes['gamipress_achievement']->fields;

    // Remove achievement id field
    unset( $original_achievement_fields['id'] );

    foreach( $original_achievement_fields as $field_id => $field_args ) {

        if( $override )
            $meta_value = gamipress_get_post_meta( $achievement->ID, $prefix . $field_id ); // Achievement
        else if( $override_from_type )
            $meta_value = gamipress_get_post_meta( $achievement_type['ID'], $prefix . $field_id ); // Achievement Type

        if( $field_args['type'] === 'checkbox' ) {
            $template_args[$field_id] = ( (bool) $meta_value ) ? 'yes' : 'no';
        } else {
            $template_args[$field_id] = $meta_value;

            // Fallback to default arg
            if( empty( $template_args[$field_id] ) && isset( $field_args['default'] ) ) {
                $template_args[$field_id] = $field_args['default'];
            }
        }

    }

    return $template_args;

}
add_filter( 'gamipress_notifications_achievement_template_args', 'gamipress_notifications_by_type_achievement_template_args', 10, 3 );

/* ------------------------
 * STEP
   ------------------------ */

/**
 * Override the step notification disable status
 *
 * @since 1.0.0
 *
 * @param bool $return
 * @param object $earning
 * @param WP_Post $step
 * @param WP_Post $achievement
 *
 * @return bool True to disable
 */
function gamipress_notifications_by_type_disable_steps( $return, $earning, $step, $achievement ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Achievement

    // Bail if steps notifications are disabled for this achievement
    if( (bool) gamipress_get_post_meta( $achievement->ID, $prefix . 'disable_steps' ) )
        return true;

    // Achievement Type

    // Get the achievement type ID (where is stored our custom config)
    $achievement_type = gamipress_get_achievement_type( $achievement->post_type );

    // Bail if steps notifications are disabled for this type
    if( (bool) gamipress_get_post_meta( $achievement_type['ID'], $prefix . 'disable_steps' ) )
        return true;

    return $return;

}
add_filter( 'gamipress_notifications_disable_steps', 'gamipress_notifications_by_type_disable_steps', 10, 4 );

/**
 * Override the step notification title pattern
 *
 * @since 1.0.0
 *
 * @param string $return
 * @param object $earning
 * @param WP_Post $step
 * @param WP_Post $achievement
 *
 * @return string
 */
function gamipress_notifications_by_type_step_title_pattern( $return, $earning, $step, $achievement ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Achievement

    // Get achievement custom pattern
    $title_pattern = gamipress_get_post_meta( $achievement->ID, $prefix . 'step_title_pattern' );

    // If not empty, override
    if( ! empty( $title_pattern ) )
        return $title_pattern;

    // Achievement Type

    // Get the achievement type ID (where is stored our custom config)
    $achievement_type = gamipress_get_achievement_type( $achievement->post_type );

    // Get achievement type custom pattern
    $title_pattern = gamipress_get_post_meta( $achievement_type['ID'], $prefix . 'step_title_pattern' );

    // If not empty, override
    if( ! empty( $title_pattern ) )
        return $title_pattern;

    return $return;

}
add_filter( 'gamipress_notifications_step_title_pattern', 'gamipress_notifications_by_type_step_title_pattern', 10, 4 );

/**
 * Override the step notification content pattern
 *
 * @since 1.0.0
 *
 * @param string $return
 * @param object $earning
 * @param WP_Post $step
 * @param WP_Post $achievement
 *
 * @return string
 */
function gamipress_notifications_by_type_step_content_pattern( $return, $earning, $step, $achievement ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Achievement

    // Get achievement custom pattern
    $content_pattern = gamipress_get_post_meta( $achievement->ID, $prefix . 'step_content_pattern' );

    // If not empty, override
    if( ! empty( $content_pattern ) )
        return $content_pattern;

    // Achievement Type

    // Get the achievement type ID (where is stored our custom config)
    $achievement_type = gamipress_get_achievement_type( $achievement->post_type );

    // Get achievement type custom pattern
    $content_pattern = gamipress_get_post_meta( $achievement_type['ID'], $prefix . 'step_content_pattern' );

    // If not empty, override
    if( ! empty( $content_pattern ) )
        return $content_pattern;

    return $return;

}
add_filter( 'gamipress_notifications_step_content_pattern', 'gamipress_notifications_by_type_step_content_pattern', 10, 4 );

/* ------------------------
 * POINTS AWARD
   ------------------------ */

/**
 * Override the points award notification disable status
 *
 * @since 1.0.0
 *
 * @param bool $return
 * @param object $earning
 * @param WP_Post $points_award
 * @param WP_Post $points_type
 *
 * @return bool True to disable
 */
function gamipress_notifications_by_type_disable_points_awards( $return, $earning, $points_award, $points_type ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Bail if points awards notifications are disabled for this type
    if( (bool) gamipress_get_post_meta( $points_type->ID, $prefix . 'disable_points_awards' ) )
        return true;

    return $return;

}
add_filter( 'gamipress_notifications_disable_points_awards', 'gamipress_notifications_by_type_disable_points_awards', 10, 4 );

/**
 * Override the points award notification title pattern
 *
 * @since 1.0.0
 *
 * @param string $return
 * @param object $earning
 * @param WP_Post $points_award
 * @param WP_Post $points_type
 *
 * @return string
 */
function gamipress_notifications_by_type_points_award_title_pattern( $return, $earning, $points_award, $points_type ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Get the custom pattern
    $title_pattern = gamipress_get_post_meta( $points_type->ID, $prefix . 'points_award_title_pattern' );

    // If not empty, override
    if( ! empty( $title_pattern ) )
        $return = $title_pattern;

    return $return;

}
add_filter( 'gamipress_notifications_points_award_title_pattern', 'gamipress_notifications_by_type_points_award_title_pattern', 10, 4 );

/**
 * Override the points award notification content pattern
 *
 * @since 1.0.0
 *
 * @param string $return
 * @param object $earning
 * @param WP_Post $points_award
 * @param WP_Post $points_type
 *
 * @return string
 */
function gamipress_notifications_by_type_points_award_content_pattern( $return, $earning, $points_award, $points_type ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Get the custom pattern
    $content_pattern = gamipress_get_post_meta( $points_type->ID, $prefix . 'points_award_content_pattern' );

    // If not empty, override
    if( ! empty( $content_pattern ) )
        $return = $content_pattern;

    return $return;

}
add_filter( 'gamipress_notifications_points_award_content_pattern', 'gamipress_notifications_by_type_points_award_content_pattern', 10, 4 );

/* ------------------------
 * POINTS DEDUCT
   ------------------------ */

/**
 * Override the points deduct notification disable status
 *
 * @since 1.0.0
 *
 * @param bool $return
 * @param object $earning
 * @param WP_Post $points_deduct
 * @param WP_Post $points_type
 *
 * @return bool True to disable
 */
function gamipress_notifications_by_type_disable_points_deducts( $return, $earning, $points_deduct, $points_type ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Bail if points deducts notifications are disabled for this type
    if( (bool) gamipress_get_post_meta( $points_type->ID, $prefix . 'disable_points_deducts' ) )
        return true;

    return $return;

}
add_filter( 'gamipress_notifications_disable_points_deducts', 'gamipress_notifications_by_type_disable_points_deducts', 10, 4 );

/**
 * Override the points deduct notification title pattern
 *
 * @since 1.0.0
 *
 * @param string $return
 * @param object $earning
 * @param WP_Post $points_deduct
 * @param WP_Post $points_type
 *
 * @return string
 */
function gamipress_notifications_by_type_points_deduct_title_pattern( $return, $earning, $points_deduct, $points_type ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Get the custom pattern
    $title_pattern = gamipress_get_post_meta( $points_type->ID, $prefix . 'points_deduct_title_pattern' );

    // If not empty, override
    if( ! empty( $title_pattern ) )
        $return = $title_pattern;

    return $return;

}
add_filter( 'gamipress_notifications_points_deduct_title_pattern', 'gamipress_notifications_by_type_points_deduct_title_pattern', 10, 4 );

/**
 * Override the points deduct notification content pattern
 *
 * @since 1.0.0
 *
 * @param string $return
 * @param object $earning
 * @param WP_Post $points_deduct
 * @param WP_Post $points_type
 *
 * @return string
 */
function gamipress_notifications_by_type_points_deduct_content_pattern( $return, $earning, $points_deduct, $points_type ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Get the custom pattern
    $content_pattern = gamipress_get_post_meta( $points_type->ID, $prefix . 'points_deduct_content_pattern' );

    // If not empty, override
    if( ! empty( $content_pattern ) )
        $return = $content_pattern;

    return $return;

}
add_filter( 'gamipress_notifications_points_deduct_content_pattern', 'gamipress_notifications_by_type_points_deduct_content_pattern', 10, 4 );

/* ------------------------
 * RANK
   ------------------------ */

/**
 * Override the rank notification disable status
 *
 * @since 1.0.0
 *
 * @param bool $return
 * @param object $earning
 * @param WP_Post $rank
 *
 * @return bool True to disable
 */
function gamipress_notifications_by_type_disable_ranks( $return, $earning, $rank ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Rank

    // Bail if ranks notifications are disabled for this rank
    if( (bool) gamipress_get_post_meta( $rank->ID, $prefix . 'disable_ranks' ) )
        return true;

    // Rank Type

    // Get the rank type ID (where is stored our custom config)
    $rank_type = gamipress_get_rank_type( $earning->post_type );

    // Bail if ranks notifications are disabled for this type
    if( (bool) gamipress_get_post_meta( $rank_type['ID'], $prefix . 'disable_ranks' ) )
        return true;

    return $return;

}
add_filter( 'gamipress_notifications_disable_ranks', 'gamipress_notifications_by_type_disable_ranks', 10, 3 );

/**
 * Override the rank notification title pattern
 *
 * @since 1.0.0
 *
 * @param string $return
 * @param object $earning
 * @param WP_Post $rank
 *
 * @return string
 */
function gamipress_notifications_by_type_rank_title_pattern( $return, $earning, $rank ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Rank

    // Get rank custom pattern
    $title_pattern = gamipress_get_post_meta( $rank->ID, $prefix . 'rank_title_pattern' );

    // If not empty, override
    if( ! empty( $title_pattern ) )
        return $title_pattern;

    // Rank Type

    // Get the rank type ID (where is stored our custom config)
    $rank_type = gamipress_get_rank_type( $earning->post_type );

    // Get rank type custom pattern
    $title_pattern = gamipress_get_post_meta( $rank_type['ID'], $prefix . 'rank_title_pattern' );

    // If not empty, override
    if( ! empty( $title_pattern ) )
        return $title_pattern;

    return $return;

}
add_filter( 'gamipress_notifications_rank_title_pattern', 'gamipress_notifications_by_type_rank_title_pattern', 10, 3 );

/**
 * Override the rank notification content pattern
 *
 * @since 1.0.0
 *
 * @param string $return
 * @param object $earning
 * @param WP_Post $rank
 *
 * @return string
 */
function gamipress_notifications_by_type_rank_content_pattern( $return, $earning, $rank ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Rank

    // Get rank custom pattern
    $content_pattern = gamipress_get_post_meta( $rank->ID, $prefix . 'rank_content_pattern' );

    // If not empty, override
    if( ! empty( $content_pattern ) )
        return $content_pattern;

    // Rank Type

    // Get the rank type ID (where is stored our custom config)
    $rank_type = gamipress_get_rank_type( $earning->post_type );

    // Get rank type custom pattern
    $content_pattern = gamipress_get_post_meta( $rank_type['ID'], $prefix . 'rank_content_pattern' );

    // If not empty, override
    if( ! empty( $content_pattern ) )
        return $content_pattern;

    return $return;

}
add_filter( 'gamipress_notifications_rank_content_pattern', 'gamipress_notifications_by_type_rank_content_pattern', 10, 3 );

/**
 * Override the rank notification rank template args
 *
 * @since 1.0.0
 *
 * @param array $template_args
 * @param object $earning
 * @param WP_Post $rank
 *
 * @return array
 */
function gamipress_notifications_by_type_rank_template_args( $template_args, $earning, $rank ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Get the rank type ID (where is stored our custom config)
    $rank_type = gamipress_get_rank_type( $earning->post_type );

    $override = (bool) gamipress_get_post_meta( $rank->ID, $prefix . 'override_rank_template_args' );
    $override_from_type = (bool) gamipress_get_post_meta( $rank_type['ID'], $prefix . 'override_rank_template_args' );

    // Bail if override from single and from type are not checked
    if( ! $override && ! $override_from_type )
        return $template_args;

    $original_rank_fields = GamiPress()->shortcodes['gamipress_rank']->fields;

    // Remove rank id field
    unset( $original_rank_fields['id'] );

    foreach( $original_rank_fields as $field_id => $field_args ) {

        if( $override )
            $meta_value = gamipress_get_post_meta( $rank->ID, $prefix . $field_id ); // Rank
        else if( $override_from_type )
            $meta_value = gamipress_get_post_meta( $rank_type['ID'], $prefix . $field_id ); // Rank Type

        if( $field_args['type'] === 'checkbox' ) {
            $template_args[$field_id] = ( (bool) $meta_value ) ? 'yes' : 'no';
        } else {
            $template_args[$field_id] = $meta_value;

            // Fallback to default arg
            if( empty( $template_args[$field_id] ) && isset( $field_args['default'] ) ) {
                $template_args[$field_id] = $field_args['default'];
            }
        }

    }

    return $template_args;

}
add_filter( 'gamipress_notifications_rank_template_args', 'gamipress_notifications_by_type_rank_template_args', 10, 3 );

/* ------------------------
 * RANK REQUIREMENT
   ------------------------ */

/**
 * Override the rank requirement notification disable status
 *
 * @since 1.0.0
 *
 * @param bool $return
 * @param object $earning
 * @param WP_Post $rank_requirement
 * @param WP_Post $rank
 *
 * @return bool True to disable
 */
function gamipress_notifications_by_type_disable_rank_requirements( $return, $earning, $rank_requirement, $rank ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Rank

    // Bail if rank requirements notifications are disabled for this rank
    if( (bool) gamipress_get_post_meta( $rank->ID, $prefix . 'disable_rank_requirements' ) )
        return true;

    // Rank Type

    // Get the rank type ID (where is stored our custom config)
    $rank_type = gamipress_get_rank_type( $rank->post_type );

    // Bail if rank requirements notifications are disabled for this type
    if( (bool) gamipress_get_post_meta( $rank_type['ID'], $prefix . 'disable_rank_requirements' ) )
        return true;

    return $return;

}
add_filter( 'gamipress_notifications_disable_rank_requirements', 'gamipress_notifications_by_type_disable_rank_requirements', 10, 4 );

/**
 * Override the rank requirement notification title pattern
 *
 * @since 1.0.0
 *
 * @param string $return
 * @param object $earning
 * @param WP_Post $rank_requirement
 * @param WP_Post $rank
 *
 * @return string
 */
function gamipress_notifications_by_type_rank_requirement_title_pattern( $return, $earning, $rank_requirement, $rank ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Rank

    // Get rank custom pattern
    $title_pattern = gamipress_get_post_meta( $rank->ID, $prefix . 'rank_requirement_title_pattern' );

    // If not empty, override
    if( ! empty( $title_pattern ) )
        return $title_pattern;

    // Rank Type

    // Get the rank type ID (where is stored our custom config)
    $rank_type = gamipress_get_rank_type( $rank->post_type );

    // Get rank type custom pattern
    $title_pattern = gamipress_get_post_meta( $rank_type['ID'], $prefix . 'rank_requirement_title_pattern' );

    // If not empty, override
    if( ! empty( $title_pattern ) )
        return $title_pattern;

    return $return;

}
add_filter( 'gamipress_notifications_rank_requirement_title_pattern', 'gamipress_notifications_by_type_rank_requirement_title_pattern', 10, 4 );

/**
 * Override the rank requirement notification content pattern
 *
 * @since 1.0.0
 *
 * @param string $return
 * @param object $earning
 * @param WP_Post $rank_requirement
 * @param WP_Post $rank
 *
 * @return string
 */
function gamipress_notifications_by_type_rank_requirement_content_pattern( $return, $earning, $rank_requirement, $rank ) {

    $prefix = '_gamipress_notifications_by_type_';

    // Rank

    // Get rank custom pattern
    $content_pattern = gamipress_get_post_meta( $rank->ID, $prefix . 'rank_requirement_content_pattern' );

    // If not empty, override
    if( ! empty( $content_pattern ) )
        return $content_pattern;

    // Rank Type

    // Get the rank type ID (where is stored our custom config)
    $rank_type = gamipress_get_rank_type( $rank->post_type );

    // Get rank type custom pattern
    $content_pattern = gamipress_get_post_meta( $rank_type['ID'], $prefix . 'rank_requirement_content_pattern' );

    // If not empty, override
    if( ! empty( $content_pattern ) )
        return $content_pattern;

    return $return;

}
add_filter( 'gamipress_notifications_rank_requirement_content_pattern', 'gamipress_notifications_by_type_rank_requirement_content_pattern', 10, 4 );