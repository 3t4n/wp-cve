<?php
/**
 * Admin
 *
 * @package     GamiPress\Notifications\By_Type\Admin
 * @since       1.0.0
 */
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

/**
 * Register plugin meta boxes
 *
 * @since  1.0.0
 */
function gamipress_notifications_by_type_meta_boxes() {

    $prefix = '_gamipress_notifications_by_type_';

    // Setup achievement fields
    $achievement_fields = array();

    $original_achievement_fields = GamiPress()->shortcodes['gamipress_achievement']->fields;

    unset( $original_achievement_fields['id'] );

    foreach( $original_achievement_fields as $achievement_field_id => $achievement_field ) {

        if( $achievement_field['type'] === 'checkbox' && isset( $achievement_field['default'] ) ) {
            unset( $achievement_field['default'] );
        }

        $achievement_fields[$prefix . $achievement_field_id] = $achievement_field;
    }

    // Audio settings
    $audio_query_args = array(
        'type' => array(
            'audio/midi',
            'audio/mpeg',
            'audio/x-aiff',
            'audio/x-pn-realaudio',
            'audio/x-pn-realaudio-plugin',
            'audio/x-realaudio',
            'audio/x-wav',
        ),
    );

    // -------------------------------
	// Achievement Type
    // -------------------------------

	gamipress_add_meta_box(
        'achievement-type-notifications-by-type',
        __( 'Notifications', 'gamipress-notifications-by-type' ),
        'achievement-type',
        array_merge( array(

            // Sound settings

            $prefix . 'show_sound' => array(
                'name'    => __( 'Show notification sound effect', 'gamipress-notifications-by-type' ),
                'desc'    => __( 'Upload, choose or paste the URL of the notification sound to play when a notification of this achievement type gets displayed (leave blank to keep sound effect configured from settings).', 'gamipress-notifications-by-type' ),
                'type'    => 'file',
                'text'    => array(
                    'add_upload_file_text' => __( 'Add or Upload Audio', 'gamipress-notifications-by-type' ),
                ),
                'query_args' => $audio_query_args,
            ),
            $prefix . 'hide_sound' => array(
                'name'    => __( 'Hide notification sound effect', 'gamipress-notifications-by-type' ),
                'desc'    => __( 'Upload, choose or paste the URL of the notification sound to play when a notification of this achievement type gets hidden (leave blank to keep sound effect configured from settings).', 'gamipress-notifications-by-type' ),
                'type'    => 'file',
                'text'    => array(
                    'add_upload_file_text' => __( 'Add or Upload Audio', 'gamipress-notifications-by-type' ),
                ),
                'query_args' => $audio_query_args,
            ),

            // Color settings

            $prefix . 'background_color' => array(
                'name' => __( 'Background Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the notification background color of this achievement type (leave blank to keep background color configured from settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'title_color' => array(
                'name' => __( 'Title Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification title of this achievement type (leave blank to keep title color configured from settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'text_color' => array(
                'name' => __( 'Text Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification content of this achievement type (leave blank to keep text color configured from settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'link_color' => array(
                'name' => __( 'Link Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification link of this achievement type (leave blank to keep link color configured from settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),

            // Achievement notification settings

            $prefix . 'disable_achievements' => array(
                'name' => __( 'Disable achievements completion notifications', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Check this option to do not notify to users about new achievements of this achievement type.', 'gamipress-notifications-by-type' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch',
            ),
            $prefix . 'achievement_title_pattern' => array(
                'name' => __( 'Achievement Title Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New achievement notification title pattern for this achievement type (leave blank to keep pattern configured from settings). For a list available tags, check next field description.', 'gamipress-notifications-by-type' ),
                'type' => 'text',
            ),
            $prefix . 'achievement_content_pattern' => array(
                'name' => __( 'Achievement Content Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New achievement notification content pattern for this achievement type to be shown after the achievement (leave blank to keep pattern configured from settings). Available tags:', 'gamipress-notifications-by-type' )
                    . gamipress_notifications_get_achievement_pattern_tags_html(),
                'type' => 'wysiwyg',
            ),
            $prefix . 'achievement_template_args_title' => array(
                'name' => __( 'Achievement Output Configuration', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Configure how the achievement automatic output will be displayed', 'gamipress-notifications-by-type' ),
                'type' => 'title',
            ),
            $prefix . 'override_achievement_template_args' => array(
                'name' => __( 'Override Achievement Output', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Check this option to override the achievement automatic output for achievements of this achievement type.', 'gamipress-notifications-by-type' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch',
            ),

            // Step notification settings

            $prefix . 'disable_steps' => array(
                'name' => __( 'Disable steps completion notifications', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Check this option to do not notify to users about new completed steps of this achievement type.', 'gamipress-notifications-by-type' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch',
            ),
            $prefix . 'step_title_pattern' => array(
                'name' => __( 'Step Title Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New step completed notification title pattern for this achievement type (leave blank to keep pattern configured from settings). For a list available tags, check next field description.', 'gamipress-notifications-by-type' ),
                'type' => 'text',
            ),
            $prefix . 'step_content_pattern' => array(
                'name' => __( 'Step Content Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New step completed notification content pattern for this achievement type (leave blank to keep pattern configured from settings). Available tags:', 'gamipress-notifications-by-type' )
                    . gamipress_notifications_get_step_pattern_tags_html(),
                'type' => 'wysiwyg',
            ),

        ), $achievement_fields ),
        array(
            'tabs' => array(
                'notification' => array(
                    'icon' => 'dashicons-admin-comments',
                    'title' => __( 'Notification', 'gamipress-notifications-by-type' ),
                    'fields' => array(
                        $prefix . 'show_sound',
                        $prefix . 'hide_sound',
                        $prefix . 'background_color',
                        $prefix . 'title_color',
                        $prefix . 'text_color',
                        $prefix . 'link_color',
                    ),
                ),
                'achievement' => array(
                    'icon' => 'dashicons-awards',
                    'title' => __( 'Achievements', 'gamipress-notifications-by-type' ),
                    'fields' => array_merge( array(
                        $prefix . 'disable_achievements',
                        $prefix . 'achievement_title_pattern',
                        $prefix . 'achievement_content_pattern',
                        $prefix . 'achievement_template_args_title',
                        $prefix . 'override_achievement_template_args',
                    ), array_keys( $achievement_fields ) ),
                ),
                'steps' => array(
                    'icon' => 'dashicons-editor-ol',
                    'title' => __( 'Steps', 'gamipress-notifications-by-type' ),
                    'fields' => array(
                        $prefix . 'disable_steps',
                        $prefix . 'step_title_pattern',
                        $prefix . 'step_content_pattern',
                    ),
                ),
            ),
            'vertical_tabs' => true
        )
    );

    // -------------------------------
    // Achievement
    // -------------------------------

    gamipress_add_meta_box(
        'achievement-notifications-by-type',
        __( 'Notifications', 'gamipress-notifications-by-type' ),
        gamipress_get_achievement_types_slugs(),
        array_merge( array(

            // Sound settings

            $prefix . 'show_sound' => array(
                'name'    => __( 'Show notification sound effect', 'gamipress-notifications-by-type' ),
                'desc'    => __( 'Upload, choose or paste the URL of the notification sound to play when a notification of this achievement gets displayed (leave blank to keep sound effect configured from achievement type or settings).', 'gamipress-notifications-by-type' ),
                'type'    => 'file',
                'text'    => array(
                    'add_upload_file_text' => __( 'Add or Upload Audio', 'gamipress-notifications-by-type' ),
                ),
                'query_args' => $audio_query_args,
            ),
            $prefix . 'hide_sound' => array(
                'name'    => __( 'Hide notification sound effect', 'gamipress-notifications-by-type' ),
                'desc'    => __( 'Upload, choose or paste the URL of the notification sound to play when a notification of this achievement gets hidden (leave blank to keep sound effect configured from achievement type or settings).', 'gamipress-notifications-by-type' ),
                'type'    => 'file',
                'text'    => array(
                    'add_upload_file_text' => __( 'Add or Upload Audio', 'gamipress-notifications-by-type' ),
                ),
                'query_args' => $audio_query_args,
            ),

            // Color settings

            $prefix . 'background_color' => array(
                'name' => __( 'Background Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the notification background color of this achievement (leave blank to keep background color configured from achievement type or settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'title_color' => array(
                'name' => __( 'Title Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification title of this achievement (leave blank to keep title color configured from achievement type or settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'text_color' => array(
                'name' => __( 'Text Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification content of this achievement (leave blank to keep text color configured from achievement type or settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'link_color' => array(
                'name' => __( 'Link Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification link of this achievement (leave blank to keep link color configured from achievement type or settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),

            // Achievement notification settings

            $prefix . 'disable_achievements' => array(
                'name' => __( 'Disable achievements completion notifications', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Check this option to do not notify to users about new achievements of this achievement.', 'gamipress-notifications-by-type' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch',
            ),
            $prefix . 'achievement_title_pattern' => array(
                'name' => __( 'Achievement Title Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New achievement notification title pattern for this achievement (leave blank to keep pattern configured from achievement type or settings). For a list available tags, check next field description.', 'gamipress-notifications-by-type' ),
                'type' => 'text',
            ),
            $prefix . 'achievement_content_pattern' => array(
                'name' => __( 'Achievement Content Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New achievement notification content pattern for this achievement to be shown after the achievement (leave blank to keep pattern configured from achievement type or settings). Available tags:', 'gamipress-notifications-by-type' )
                    . gamipress_notifications_get_achievement_pattern_tags_html(),
                'type' => 'wysiwyg',
            ),
            $prefix . 'achievement_template_args_title' => array(
                'name' => __( 'Achievement Output Configuration', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Configure how the achievement automatic output will be displayed', 'gamipress-notifications-by-type' ),
                'type' => 'title',
            ),
            $prefix . 'override_achievement_template_args' => array(
                'name' => __( 'Override Achievement Output', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Check this option to override the achievement automatic output.', 'gamipress-notifications-by-type' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch',
            ),

            // Step notification settings

            $prefix . 'disable_steps' => array(
                'name' => __( 'Disable steps completion notifications', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Check this option to do not notify to users about new completed steps of this achievement.', 'gamipress-notifications-by-type' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch',
            ),
            $prefix . 'step_title_pattern' => array(
                'name' => __( 'Step Title Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New step completed notification title pattern for this achievement (leave blank to keep pattern configured from achievement type or settings). For a list available tags, check next field description.', 'gamipress-notifications-by-type' ),
                'type' => 'text',
            ),
            $prefix . 'step_content_pattern' => array(
                'name' => __( 'Step Content Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New step completed notification content pattern for this achievement (leave blank to keep pattern configured from achievement type or settings). Available tags:', 'gamipress-notifications-by-type' )
                    . gamipress_notifications_get_step_pattern_tags_html(),
                'type' => 'wysiwyg',
            ),

        ), $achievement_fields ),
        array(
            'tabs' => array(
                'notification' => array(
                    'icon' => 'dashicons-admin-comments',
                    'title' => __( 'Notification', 'gamipress-notifications-by-type' ),
                    'fields' => array(
                        $prefix . 'show_sound',
                        $prefix . 'hide_sound',
                        $prefix . 'background_color',
                        $prefix . 'title_color',
                        $prefix . 'text_color',
                        $prefix . 'link_color',
                    ),
                ),
                'achievement' => array(
                    'icon' => 'dashicons-awards',
                    'title' => __( 'Achievements', 'gamipress-notifications-by-type' ),
                    'fields' => array_merge( array(
                        $prefix . 'disable_achievements',
                        $prefix . 'achievement_title_pattern',
                        $prefix . 'achievement_content_pattern',
                        $prefix . 'achievement_template_args_title',
                        $prefix . 'override_achievement_template_args',
                    ), array_keys( $achievement_fields ) ),
                ),
                'steps' => array(
                    'icon' => 'dashicons-editor-ol',
                    'title' => __( 'Steps', 'gamipress-notifications-by-type' ),
                    'fields' => array(
                        $prefix . 'disable_steps',
                        $prefix . 'step_title_pattern',
                        $prefix . 'step_content_pattern',
                    ),
                ),
            ),
            'vertical_tabs' => true
        )
    );

    // -------------------------------
    // Points Type
    // -------------------------------

    gamipress_add_meta_box(
        'points-type-notifications-by-type',
        __( 'Notifications', 'gamipress-notifications-by-type' ),
        'points-type',
        array(

            // Sound settings

            $prefix . 'show_sound' => array(
                'name'    => __( 'Show notification sound effect', 'gamipress-notifications-by-type' ),
                'desc'    => __( 'Upload, choose or paste the URL of the notification sound to play when a notification of this points type gets displayed (leave blank to keep sound effect configured from settings).', 'gamipress-notifications-by-type' ),
                'type'    => 'file',
                'text'    => array(
                    'add_upload_file_text' => __( 'Add or Upload Audio', 'gamipress-notifications-by-type' ),
                ),
                'query_args' => $audio_query_args,
            ),
            $prefix . 'hide_sound' => array(
                'name'    => __( 'Hide notification sound effect', 'gamipress-notifications-by-type' ),
                'desc'    => __( 'Upload, choose or paste the URL of the notification sound to play when a notification of this points type gets hidden (leave blank to keep sound effect configured from settings).', 'gamipress-notifications-by-type' ),
                'type'    => 'file',
                'text'    => array(
                    'add_upload_file_text' => __( 'Add or Upload Audio', 'gamipress-notifications-by-type' ),
                ),
                'query_args' => $audio_query_args,
            ),

            // Color settings

            $prefix . 'background_color' => array(
                'name' => __( 'Background Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the notification background color of this points type (leave blank to keep background color configured from settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'title_color' => array(
                'name' => __( 'Title Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification title of this points type (leave blank to keep title color configured from settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'text_color' => array(
                'name' => __( 'Text Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification content of this points type (leave blank to keep text color configured from settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'link_color' => array(
                'name' => __( 'Link Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification link of this points type (leave blank to keep link color configured from settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),

            // Points Award notification settings

            $prefix . 'disable_points_awards' => array(
                'name' => __( 'Disable points awards notifications', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Check this option to do not notify to users about new points awards of this points type.', 'gamipress-notifications-by-type' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch',
            ),
            $prefix . 'points_award_title_pattern' => array(
                'name' => __( 'Points Awards Title Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New points award notification title pattern (leave blank to keep pattern configured from settings). For a list available tags, check next field description.', 'gamipress-notifications-by-type' ),
                'type' => 'text',
            ),
            $prefix . 'points_award_content_pattern' => array(
                'name' => __( 'Points Awards Content Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New points award notification content pattern for this points type (leave blank to keep pattern configured from settings). Available tags:', 'gamipress-notifications-by-type' )
                    . gamipress_notifications_get_points_award_pattern_tags_html(),
                'type' => 'wysiwyg',
            ),

            // Points Deduct notification settings

            $prefix . 'disable_points_deducts' => array(
                'name' => __( 'Disable points deducts notifications', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Check this option to do not notify to users about new points deductions of this points type.', 'gamipress-notifications-by-type' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch',
            ),
            $prefix . 'points_deduct_title_pattern' => array(
                'name' => __( 'Points Deducts Title Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New points deduction notification title pattern (leave blank to keep pattern configured from settings). For a list available tags, check next field description.', 'gamipress-notifications-by-type' ),
                'type' => 'text',
            ),
            $prefix . 'points_deduct_content_pattern' => array(
                'name' => __( 'Points Deducts Content Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New points deduction notification content pattern for this points type (leave blank to keep pattern configured from settings). Available tags:', 'gamipress-notifications-by-type' )
                    . gamipress_notifications_get_points_deduct_pattern_tags_html(),
                'type' => 'wysiwyg',
            ),

        ),
        array(
            'tabs' => array(
                'notification' => array(
                    'icon' => 'dashicons-admin-comments',
                    'title' => __( 'Notification', 'gamipress-notifications-by-type' ),
                    'fields' => array(
                        $prefix . 'show_sound',
                        $prefix . 'hide_sound',
                        $prefix . 'background_color',
                        $prefix . 'title_color',
                        $prefix . 'text_color',
                        $prefix . 'link_color',
                    ),
                ),
                'points_awards' => array(
                    'icon' => 'dashicons-star-filled',
                    'title' => __( 'Points Awards', 'gamipress-notifications-by-type' ),
                    'fields' => array(
                        $prefix . 'disable_points_awards',
                        $prefix . 'points_award_title_pattern',
                        $prefix . 'points_award_content_pattern',
                    ),
                ),
                'points_deducts' => array(
                    'icon' => 'dashicons-star-empty',
                    'title' => __( 'Points Deducts', 'gamipress-notifications-by-type' ),
                    'fields' => array(
                        $prefix . 'disable_points_deducts',
                        $prefix . 'points_deduct_title_pattern',
                        $prefix . 'points_deduct_content_pattern',
                    ),
                ),
            ),
            'vertical_tabs' => true
        )
    );

    // Setup rank fields
    $rank_fields = array();

    $original_rank_fields = GamiPress()->shortcodes['gamipress_rank']->fields;

    unset( $original_rank_fields['id'] );

    foreach( $original_rank_fields as $rank_field_id => $rank_field ) {

        if( $rank_field['type'] === 'checkbox' && isset( $rank_field['default'] ) ) {
            unset( $rank_field['default'] );
        }

        $rank_fields[$prefix . $rank_field_id] = $rank_field;
    }

    // -------------------------------
    // Rank Type
    // -------------------------------

    gamipress_add_meta_box(
        'rank-type-notifications-by-type',
        __( 'Notifications', 'gamipress-notifications-by-type' ),
        'rank-type',
        array_merge( array(

            // Sound settings

            $prefix . 'show_sound' => array(
                'name'    => __( 'Show notification sound effect', 'gamipress-notifications-by-type' ),
                'desc'    => __( 'Upload, choose or paste the URL of the notification sound to play when a notification of this rank type gets displayed (leave blank to keep sound effect configured from settings).', 'gamipress-notifications-by-type' ),
                'type'    => 'file',
                'text'    => array(
                    'add_upload_file_text' => __( 'Add or Upload Audio', 'gamipress-notifications-by-type' ),
                ),
                'query_args' => $audio_query_args,
            ),
            $prefix . 'hide_sound' => array(
                'name'    => __( 'Hide notification sound effect', 'gamipress-notifications-by-type' ),
                'desc'    => __( 'Upload, choose or paste the URL of the notification sound to play when a notification of this rank type gets hidden (leave blank to keep sound effect configured from settings).', 'gamipress-notifications-by-type' ),
                'type'    => 'file',
                'text'    => array(
                    'add_upload_file_text' => __( 'Add or Upload Audio', 'gamipress-notifications-by-type' ),
                ),
                'query_args' => $audio_query_args,
            ),

            // Color settings

            $prefix . 'background_color' => array(
                'name' => __( 'Background Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the notification background color of this rank type (leave blank to keep background color configured from settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'title_color' => array(
                'name' => __( 'Title Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification title of this rank type (leave blank to keep title color configured from settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'text_color' => array(
                'name' => __( 'Text Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification content of this rank type (leave blank to keep text color configured from settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'link_color' => array(
                'name' => __( 'Link Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification link of this rank type (leave blank to keep link color configured from settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),

            // Rank notification settings

            $prefix . 'disable_ranks' => array(
                'name' => __( 'Disable ranks completion notifications', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Check this option to do not notify to users about new rank reached of this rank type.', 'gamipress-notifications-by-type' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch',
            ),
            $prefix . 'rank_title_pattern' => array(
                'name' => __( 'Rank Title Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New rank notification title pattern for this rank type (leave blank to keep pattern configured from settings). For a list available tags, check next field description.', 'gamipress-notifications-by-type' ),
                'type' => 'text',
            ),
            $prefix . 'rank_content_pattern' => array(
                'name' => __( 'Rank Content Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New rank notification content pattern for this rank type to be shown after the rank (leave blank to keep pattern configured from settings). Available tags:', 'gamipress-notifications-by-type' )
                    . gamipress_notifications_get_rank_pattern_tags_html(),
                'type' => 'wysiwyg',
            ),
            $prefix . 'rank_template_args_title' => array(
                'name' => __( 'Rank Output Configuration', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Configure how the rank automatic output will be displayed', 'gamipress-notifications-by-type' ),
                'type' => 'title',
            ),
            $prefix . 'override_rank_template_args' => array(
                'name' => __( 'Override Rank Output', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Check this option to override the rank automatic output for ranks of this rank type.', 'gamipress-notifications-by-type' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch',
            ),

            // Rank requirement notification settings

            $prefix . 'disable_rank_requirements' => array(
                'name' => __( 'Disable rank requirements completion notifications', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Check this option to do not notify to users about new completed rank requirements of this rank type.', 'gamipress-notifications-by-type' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch',
            ),
            $prefix . 'rank_requirement_title_pattern' => array(
                'name' => __( 'Rank Requirement Title Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New rank requirement completed notification title pattern for this rank type (leave blank to keep pattern configured from settings). For a list available tags, check next field description.', 'gamipress-notifications-by-type' ),
                'type' => 'text',
            ),
            $prefix . 'rank_requirement_content_pattern' => array(
                'name' => __( 'Rank Requirement Content Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New rank requirement completed notification content pattern for this rank type (leave blank to keep pattern configured from settings). Available tags:', 'gamipress-notifications-by-type' )
                    . gamipress_notifications_get_rank_requirement_pattern_tags_html(),
                'type' => 'wysiwyg',
            ),

        ), $rank_fields ),
        array(
            'tabs' => array(
                'notification' => array(
                    'icon' => 'dashicons-admin-comments',
                    'title' => __( 'Notification', 'gamipress-notifications-by-type' ),
                    'fields' => array(
                        $prefix . 'show_sound',
                        $prefix . 'hide_sound',
                        $prefix . 'background_color',
                        $prefix . 'title_color',
                        $prefix . 'text_color',
                        $prefix . 'link_color',
                    ),
                ),
                'rank' => array(
                    'icon' => 'dashicons-rank',
                    'title' => __( 'Ranks', 'gamipress-notifications-by-type' ),
                    'fields' => array_merge( array(
                        $prefix . 'disable_ranks',
                        $prefix . 'rank_title_pattern',
                        $prefix . 'rank_content_pattern',
                        $prefix . 'rank_template_args_title',
                        $prefix . 'override_rank_template_args',
                    ), array_keys( $rank_fields ) ),
                ),
                'rank_requirements' => array(
                    'icon' => 'dashicons-editor-ol',
                    'title' => __( 'Rank Requirements', 'gamipress-notifications-by-type' ),
                    'fields' => array(
                        $prefix . 'disable_rank_requirements',
                        $prefix . 'rank_requirement_title_pattern',
                        $prefix . 'rank_requirement_content_pattern',
                    ),
                ),
            ),
            'vertical_tabs' => true
        )
    );

    // -------------------------------
    // Rank
    // -------------------------------

    gamipress_add_meta_box(
        'rank-notifications-by-type',
        __( 'Notifications', 'gamipress-notifications-by-type' ),
        gamipress_get_rank_types_slugs(),
        array_merge( array(

            // Sound settings

            $prefix . 'show_sound' => array(
                'name'    => __( 'Show notification sound effect', 'gamipress-notifications-by-type' ),
                'desc'    => __( 'Upload, choose or paste the URL of the notification sound to play when a notification of this rank gets displayed (leave blank to keep sound effect configured from rank type or settings).', 'gamipress-notifications-by-type' ),
                'type'    => 'file',
                'text'    => array(
                    'add_upload_file_text' => __( 'Add or Upload Audio', 'gamipress-notifications-by-type' ),
                ),
                'query_args' => $audio_query_args,
            ),
            $prefix . 'hide_sound' => array(
                'name'    => __( 'Hide notification sound effect', 'gamipress-notifications-by-type' ),
                'desc'    => __( 'Upload, choose or paste the URL of the notification sound to play when a notification of this rank gets hidden (leave blank to keep sound effect configured from rank type or settings).', 'gamipress-notifications-by-type' ),
                'type'    => 'file',
                'text'    => array(
                    'add_upload_file_text' => __( 'Add or Upload Audio', 'gamipress-notifications-by-type' ),
                ),
                'query_args' => $audio_query_args,
            ),

            // Color settings

            $prefix . 'background_color' => array(
                'name' => __( 'Background Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the notification background color of this rank (leave blank to keep background color configured from rank type or settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'title_color' => array(
                'name' => __( 'Title Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification title of this rank (leave blank to keep title color configured from rank type or settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'text_color' => array(
                'name' => __( 'Text Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification content of this rank (leave blank to keep text color configured from rank type or settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),
            $prefix . 'link_color' => array(
                'name' => __( 'Link Color', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Set the text color of the notification link of this rank (leave blank to keep link color configured from rank type or settings).', 'gamipress-notifications-by-type' ),
                'type' => 'colorpicker',
                'options' => array( 'alpha' => true ),
            ),

            // Rank notification settings

            $prefix . 'disable_ranks' => array(
                'name' => __( 'Disable ranks completion notifications', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Check this option to do not notify to users about new rank reached of this rank.', 'gamipress-notifications-by-type' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch',
            ),
            $prefix . 'rank_title_pattern' => array(
                'name' => __( 'Rank Title Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New rank notification title pattern for this rank (leave blank to keep pattern configured from rank type or settings). For a list available tags, check next field description.', 'gamipress-notifications-by-type' ),
                'type' => 'text',
            ),
            $prefix . 'rank_content_pattern' => array(
                'name' => __( 'Rank Content Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New rank notification content pattern for this rank to be shown after the rank (leave blank to keep pattern configured from rank type or settings). Available tags:', 'gamipress-notifications-by-type' )
                    . gamipress_notifications_get_rank_pattern_tags_html(),
                'type' => 'wysiwyg',
            ),
            $prefix . 'rank_template_args_title' => array(
                'name' => __( 'Rank Output Configuration', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Configure how the rank automatic output will be displayed', 'gamipress-notifications-by-type' ),
                'type' => 'title',
            ),
            $prefix . 'override_rank_template_args' => array(
                'name' => __( 'Override Rank Output', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Check this option to override the rank automatic output.', 'gamipress-notifications-by-type' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch',
            ),

            // Rank requirement notification settings

            $prefix . 'disable_rank_requirements' => array(
                'name' => __( 'Disable rank requirements completion notifications', 'gamipress-notifications-by-type' ),
                'desc' => __( 'Check this option to do not notify to users about new completed rank requirements of this rank.', 'gamipress-notifications-by-type' ),
                'type' => 'checkbox',
                'classes' => 'gamipress-switch',
            ),
            $prefix . 'rank_requirement_title_pattern' => array(
                'name' => __( 'Rank Requirement Title Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New rank requirement completed notification title pattern for this rank (leave blank to keep pattern configured from rank type or settings). For a list available tags, check next field description.', 'gamipress-notifications-by-type' ),
                'type' => 'text',
            ),
            $prefix . 'rank_requirement_content_pattern' => array(
                'name' => __( 'Rank Requirement Content Pattern', 'gamipress-notifications-by-type' ),
                'desc' => __( 'New rank requirement completed notification content pattern for this rank (leave blank to keep pattern configured from rank type or settings). Available tags:', 'gamipress-notifications-by-type' )
                    . gamipress_notifications_get_rank_requirement_pattern_tags_html(),
                'type' => 'wysiwyg',
            ),

        ), $rank_fields ),
        array(
            'tabs' => array(
                'notification' => array(
                    'icon' => 'dashicons-admin-comments',
                    'title' => __( 'Notification', 'gamipress-notifications-by-type' ),
                    'fields' => array(
                        $prefix . 'show_sound',
                        $prefix . 'hide_sound',
                        $prefix . 'background_color',
                        $prefix . 'title_color',
                        $prefix . 'text_color',
                        $prefix . 'link_color',
                    ),
                ),
                'rank' => array(
                    'icon' => 'dashicons-rank',
                    'title' => __( 'Ranks', 'gamipress-notifications-by-type' ),
                    'fields' => array_merge( array(
                        $prefix . 'disable_ranks',
                        $prefix . 'rank_title_pattern',
                        $prefix . 'rank_content_pattern',
                        $prefix . 'rank_template_args_title',
                        $prefix . 'override_rank_template_args',
                    ), array_keys( $rank_fields ) ),
                ),
                'rank_requirements' => array(
                    'icon' => 'dashicons-editor-ol',
                    'title' => __( 'Rank Requirements', 'gamipress-notifications-by-type' ),
                    'fields' => array(
                        $prefix . 'disable_rank_requirements',
                        $prefix . 'rank_requirement_title_pattern',
                        $prefix . 'rank_requirement_content_pattern',
                    ),
                ),
            ),
            'vertical_tabs' => true
        )
    );

}
add_action( 'cmb2_admin_init', 'gamipress_notifications_by_type_meta_boxes' );

/**
 * GamiPress Notifications By Type automatic updates
 *
 * @since  1.0.0
 *
 * @param array $automatic_updates_plugins
 *
 * @return array
 */
function gamipress_notifications_by_type_automatic_updates( $automatic_updates_plugins ) {

    $automatic_updates_plugins['gamipress-notifications-by-type'] = __( 'Notifications By Type', 'gamipress-notifications-by-type' );

    return $automatic_updates_plugins;
}
add_filter( 'gamipress_automatic_updates_plugins', 'gamipress_notifications_by_type_automatic_updates' );