<?php
defined( 'ABSPATH' ) || exit;

if ( !class_exists( 'Better_Messages_Customize' ) ):

    class Better_Messages_Customize
    {
        public static function instance()
        {
            static $instance = null;

            if ( null === $instance ) {
                $instance = new Better_Messages_Customize();
            }

            return $instance;
        }

        public function __construct(){
            add_action( 'customize_register',     array( $this, 'customize_register') );
            add_action( 'customize_preview_init', array( $this, 'customizer_live_preview') );
            add_filter('body_class',              array( $this, 'body_class' ) );
        }

        public function customization_link( $autofocus = [] ){
            # http://bpbettermessagesreact.com/wp-admin/customize.php?autofocus[section]=better_messages_mini_widgets

            $args = [
                'url' => Better_Messages()->functions->get_link()
            ];

            if( ! empty($autofocus) ){
                $args['autofocus'] = $autofocus;
            }

            return add_query_arg( $args, admin_url( 'customize.php' ) );
        }

        public function customizer_live_preview(){
            wp_enqueue_script(
                'better-messages-customizer',
                Better_Messages()->url . 'assets/admin/customizer.js',
                array( 'jquery', 'customize-preview' ),
                Better_Messages()->version,
                true
            );
        }

        function customize_register( WP_Customize_Manager $wp_customize ) {
            $wp_customize->add_panel(
                'better_messages',
                array(
                    'title'       => 'Better Messages',
                    'priority'    => 200,
                )
            );

            /**
             * General section
             */
            $wp_customize->add_section(
                'better_messages_general',
                array(
                    'title'    => _x( 'General', 'WP Customizer', 'bp-better-messages' ),
                    'panel'    => 'better_messages',
                    'priority' => 40,
                )
            );

            /**
             * Color mode sections
             */
            $wp_customize->add_section(
                'better_messages_light_mode',
                array(
                    'title'    => _x( 'Light Mode', 'WP Customizer', 'bp-better-messages' ),
                    'panel'    => 'better_messages',
                    'priority' => 40,
                )
            );

            $wp_customize->add_section(
                'better_messages_dark_mode',
                array(
                    'title'    => _x( 'Dark Mode', 'WP Customizer', 'bp-better-messages' ),
                    'panel'    => 'better_messages',
                    'priority' => 40,
                )
            );

            $wp_customize->add_setting(
                'bm-border-radius',
                array(
                    'section'    => 'better_messages_general',
                    'transport'  => 'postMessage',
                    'default'    => 3
                )
            );

            $wp_customize->add_control(
                'bm-border-radius',
                array(
                    'label'   => _x( 'Messages Window Roundness', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_general',
                    'type' => 'range',
                    'input_attrs' => array(
                        'min' => 0,
                        'step' => 1,
                        'max' => 20
                    )
                )
            );

            /**
             * Modern messages border radius
             */
            $wp_customize->add_setting(
                'bm-modern-radius',
                array(
                    'section'    => 'better_messages_general',
                    'transport'  => 'postMessage',
                    'default'    => 2
                )
            );

            $wp_customize->add_control(
                'bm-modern-radius',
                array(
                    'label'   => _x( 'Modern Messages Roundness', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_general',
                    'type' => 'range',
                    'input_attrs' => array(
                        'min' => 0,
                        'step' => 1,
                        'max' => 25
                    )
                )
            );

            /**
             * Modern messages border radius
             */
            $wp_customize->add_setting(
                'bm-avatar-radius',
                array(
                    'section'    => 'better_messages_general',
                    'transport'  => 'postMessage',
                    'default'    => 2
                )
            );

            $wp_customize->add_control(
                'bm-avatar-radius',
                array(
                    'label'   => _x( 'Avatars Roundness', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_general',
                    'type' => 'range',
                    'input_attrs' => array(
                        'min' => 0,
                        'step' => 1,
                        'max' => 25
                    )
                )
            );

            $wp_customize->add_setting(
                'bm-date-enabled',
                array(
                    'section'    => 'better_messages_general',
                    'transport'  => 'postMessage',
                    'default'    => true
                )
            );

            $wp_customize->add_control(
                'bm-date-enabled',
                array(
                    'label'   => _x( 'Show Date Labels', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_general',
                    'type'    => 'checkbox',
                )
            );

            /**
             * Modern messages border radius
             */
            $wp_customize->add_setting(
                'bm-date-radius',
                array(
                    'section'    => 'better_messages_general',
                    'transport'  => 'postMessage',
                    'default'    => 2
                )
            );

            $wp_customize->add_control(
                'bm-date-radius',
                array(
                    'label'   => _x( 'Date Labels Roundness', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_general',
                    'type' => 'range',
                    'input_attrs' => array(
                        'min' => 0,
                        'step' => 1,
                        'max' => 25
                    )
                )
            );

            /**
             * Theme
             */
            $wp_customize->add_setting(
                'bm-theme',
                array(
                    'section'    => 'better_messages_general',
                    'transport'  => 'postMessage',
                    'default'    => 'light'
                )
            );

            $wp_customize->add_control(
                'bm-theme',
                array(
                    'label'   => _x( 'Color scheme', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_general',
                    'type'    => 'radio',
                    'choices' => array(
                        'light' => _x( 'Light', 'WP Customizer', 'bp-better-messages' ),
                        'dark'  => _x( 'Dark', 'WP Customizer', 'bp-better-messages' ),
                    ),
                )
            );

            /**
             * Theme
             */
            $wp_customize->add_setting(
                'bm-show-avatar-group',
                array(
                    'section'    => 'better_messages_general',
                    'transport'  => 'postMessage',
                    'default'    => true
                )
            );

            $wp_customize->add_control(
                'bm-show-avatar-group',
                array(
                    'label'   => _x( 'Show avatars in group conversation header', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_general',
                    'type'    => 'checkbox',
                )
            );

            $wp_customize->add_setting(
                'bm-private-sub-name',
                array(
                    'section'    => 'better_messages_general',
                    'transport'  => 'postMessage',
                    'default'    => 'online'
                )
            );

            $wp_customize->add_control(
                'bm-private-sub-name',
                array(
                    'label'   => _x( 'Subtitle in private conversations', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_general',
                    'type'    => 'radio',
                    'description' => _x( 'What to show under user name in private conversations?', 'WP Customizer', 'bp-better-messages' ),
                    'choices' => array(
                        'online'  => _x( 'Online indicator or last active time (only WebSocket Version)', 'WP Customizer', 'bp-better-messages' ),
                        'subject' => _x( 'Conversation subject', 'WP Customizer', 'bp-better-messages' ),
                        'hide'    => _x( 'Nothing', 'WP Customizer', 'bp-better-messages' )
                    ),
                )
            );

            $wp_customize->add_setting(
                'bm-avatars-list',
                array(
                    'section'    => 'better_messages_general',
                    'transport'  => 'postMessage',
                    'default'    => 'show'
                )
            );

            $wp_customize->add_control(
                'bm-avatars-list',
                array(
                    'label'   => _x( 'Avatars in messages list', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_general',
                    'type'    => 'radio',
                    'description' => _x( 'Hide avatars if you want to allow messages to have more space in messages list', 'WP Customizer', 'bp-better-messages' ),
                    'choices' => array(
                        'show' => _x( 'Show everywhere', 'WP Customizer', 'bp-better-messages' ),
                        'hide_private'  => _x( 'Hide in private conversations', 'WP Customizer', 'bp-better-messages' ),
                        'hide_groups'  => _x( 'Hide in group conversations', 'WP Customizer', 'bp-better-messages' ),
                        'hide'  => _x( 'Hide everywhere', 'WP Customizer', 'bp-better-messages' ),
                    ),
                )
            );

            /**
             * Theme
             */
            $wp_customize->add_setting(
                'bm-date-position',
                array(
                    'section'    => 'better_messages_general',
                    'transport'  => 'postMessage',
                    'default'    => 'message'
                )
            );

            $wp_customize->add_control(
                'bm-date-position',
                array(
                    'label'   => _x( 'Show message sent date', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_general',
                    'type'    => 'radio',
                    'choices' => array(
                        'message' => _x( 'At every single message', 'WP Customizer', 'bp-better-messages' ),
                        'stack'   => _x( 'At the start of messages stack', 'WP Customizer', 'bp-better-messages' ),
                    ),
                )
            );

            $wp_customize->add_setting(
                'bm-time-format',
                array(
                    'section'    => 'better_messages_general',
                    'transport'  => 'postMessage',
                    'default'    => '24'
                )
            );

            $wp_customize->add_control(
                'bm-time-format',
                array(
                    'label'   => _x( 'Time format', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_general',
                    'type'    => 'radio',
                    'choices' => array(
                        '24' => _x( '24-hour time format', 'WP Customizer', 'bp-better-messages' ),
                        '12'   => _x( '12-hour time format', 'WP Customizer', 'bp-better-messages' ),
                    ),
                )
            );

            /**
             * Light mode start
             */
            $wp_customize->add_setting(
                'main-bm-color',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#21759b'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'main-bm-color',
                    array(
                        'label'   => _x( 'Primary Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-primary-bg
            $wp_customize->add_setting(
                'bm-primary-bg',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#fff'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-primary-bg',
                    array(
                        'label'   => _x( 'Primary Background Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-secondary-bg
            $wp_customize->add_setting(
                'bm-secondary-bg',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#fafbfc'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-secondary-bg',
                    array(
                        'label'   => _x( 'Secondary Background Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-hover-bg
            $wp_customize->add_setting(
                'bm-hover-bg',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#fafbfc'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-hover-bg',
                    array(
                        'label'   => _x( 'Hover Background Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-primary-border
            $wp_customize->add_setting(
                'bm-primary-border',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#d7d8db'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-primary-border',
                    array(
                        'label'   => _x( 'Primary Border Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-secondary-border
            $wp_customize->add_setting(
                'bm-secondary-border',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#ebebeb'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-secondary-border',
                    array(
                        'label'   => _x( 'Secondary Border Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-secondary-border
            $wp_customize->add_setting(
                'bm-date-background',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#000000'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-date-background',
                    array(
                        'label'   => _x( 'Date Background', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-text-color
            $wp_customize->add_setting(
                'bm-text-color',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#000'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-text-color',
                    array(
                        'label'   => _x( 'Primary Text Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-modern-left-side-nickname
            $wp_customize->add_setting(
                'bm-modern-left-side-nickname',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#000'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-modern-left-side-nickname',
                    array(
                        'label'   => _x( 'Modern Message: Left Nickname Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-modern-left-side-bg
            $wp_customize->add_setting(
                'bm-modern-left-side-bg',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#e8e8e8'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-modern-left-side-bg',
                    array(
                        'label'   => _x( 'Modern Message: Left Background Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-modern-left-side-color
            $wp_customize->add_setting(
                'bm-modern-left-side-color',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#000'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-modern-left-side-color',
                    array(
                        'label'   => _x( 'Modern Message: Left Content Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-modern-right-side-nickname
            $wp_customize->add_setting(
                'bm-modern-right-side-nickname',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#21759b'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-modern-right-side-nickname',
                    array(
                        'label'   => _x( 'Modern Message: Right Nickname Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-modern-right-side-bg
            $wp_customize->add_setting(
                'bm-modern-right-side-bg',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#21759b'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-modern-right-side-bg',
                    array(
                        'label'   => _x( 'Modern Message: Right Background Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-modern-right-side-color
            $wp_customize->add_setting(
                'bm-modern-right-side-color',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#fff'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-modern-right-side-color',
                    array(
                        'label'   => _x( 'Modern Message: Right Content Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-stiky-date-bg
            $wp_customize->add_setting(
                'bm-sticky-date-bg',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#000'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-sticky-date-bg',
                    array(
                        'label'   => _x( 'Date Label: Background Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-stiky-date-color
            $wp_customize->add_setting(
                'bm-sticky-date-color',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#fff'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-sticky-date-color',
                    array(
                        'label'   => _x( 'Date Label: Text Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            $wp_customize->add_setting(
                'bm-tooltip-bg',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#000'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-tooltip-bg',
                    array(
                        'label'   => _x( 'Tooltips: Background Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );

            // bm-stiky-date-color
            $wp_customize->add_setting(
                'bm-tooltip-color',
                array(
                    'section'    => 'better_messages_light_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#fff'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-tooltip-color',
                    array(
                        'label'   => _x( 'Tooltips: Text Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_light_mode',
                    )
                )
            );
            /**
             * Light mode end
             */

            /**
             * Dark mode start
             */
            $wp_customize->add_setting(
                'main-bm-color-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#fff'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'main-bm-color-dark',
                    array(
                        'label'   => _x( 'Primary Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-primary-bg
            $wp_customize->add_setting(
                'bm-primary-bg-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#181d2c'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-primary-bg-dark',
                    array(
                        'label'   => _x( 'Primary Background Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-secondary-bg
            $wp_customize->add_setting(
                'bm-secondary-bg-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#1d2333'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-secondary-bg-dark',
                    array(
                        'label'   => _x( 'Secondary Background Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-hover-bg
            $wp_customize->add_setting(
                'bm-hover-bg-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#1c2338'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-hover-bg-dark',
                    array(
                        'label'   => _x( 'Hover Background Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-primary-border
            $wp_customize->add_setting(
                'bm-primary-border-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#3f485f'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-primary-border-dark',
                    array(
                        'label'   => _x( 'Primary Border Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-secondary-border
            $wp_customize->add_setting(
                'bm-secondary-border-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#3f485f'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-secondary-border-dark',
                    array(
                        'label'   => _x( 'Secondary Border Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-text-color
            $wp_customize->add_setting(
                'bm-text-color-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#fff'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-text-color-dark',
                    array(
                        'label'   => _x( 'Primary Text Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-modern-left-side-nickname
            $wp_customize->add_setting(
                'bm-modern-left-side-nickname-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#fff'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-modern-left-side-nickname-dark',
                    array(
                        'label'   => _x( 'Modern Message: Left Nickname Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-modern-left-side-bg
            $wp_customize->add_setting(
                'bm-modern-left-side-bg-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#e8e8e8'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-modern-left-side-bg-dark',
                    array(
                        'label'   => _x( 'Modern Message: Left Background Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-modern-left-side-color
            $wp_customize->add_setting(
                'bm-modern-left-side-color-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#000'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-modern-left-side-color-dark',
                    array(
                        'label'   => _x( 'Modern Message: Left Content Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-modern-right-side-nickname
            $wp_customize->add_setting(
                'bm-modern-right-side-nickname-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#fff'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-modern-right-side-nickname-dark',
                    array(
                        'label'   => _x( 'Modern Message: Right Nickname Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-modern-right-side-bg
            $wp_customize->add_setting(
                'bm-modern-right-side-bg-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#404e72'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-modern-right-side-bg-dark',
                    array(
                        'label'   => _x( 'Modern Message: Right Background Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-modern-right-side-color
            $wp_customize->add_setting(
                'bm-modern-right-side-color-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#fff'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-modern-right-side-color-dark',
                    array(
                        'label'   => _x( 'Modern Message: Right Content Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-stiky-date-bg
            $wp_customize->add_setting(
                'bm-sticky-date-bg-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#1d2333'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-sticky-date-bg-dark',
                    array(
                        'label'   => _x( 'Date Label: Background Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-stiky-date-color
            $wp_customize->add_setting(
                'bm-sticky-date-color-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#fff'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-sticky-date-color-dark',
                    array(
                        'label'   => _x( 'Date Label: Text Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            $wp_customize->add_setting(
                'bm-tooltip-bg-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#000'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-tooltip-bg-dark',
                    array(
                        'label'   => _x( 'Tooltips: Background Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );

            // bm-stiky-date-color
            $wp_customize->add_setting(
                'bm-tooltip-color-dark',
                array(
                    'section'    => 'better_messages_dark_mode',
                    'transport'  => 'postMessage',
                    'default'    => '#fff'
                )
            );

            $wp_customize->add_control(
                new WP_Customize_Color_Control(
                    $wp_customize,
                    'bm-tooltip-color-dark',
                    array(
                        'label'   => _x( 'Tooltips: Text Color', 'WP Customizer', 'bp-better-messages' ),
                        'section' => 'better_messages_dark_mode',
                    )
                )
            );
            /** Dark mode end */



            /* Mini Widgets */
            $wp_customize->add_section(
                'better_messages_mini_widgets',
                array(
                    'title'    => _x( 'Mini Widgets & Mini Chats', 'WP Customizer', 'bp-better-messages' ),
                    'panel'    => 'better_messages',
                    'priority' => 40,
                )
            );

            $wp_customize->add_setting(
                'bm-widgets-border-radius',
                array(
                    'section'    => 'better_messages_mini_widgets',
                    'transport'  => 'postMessage',
                    'default'    => 0
                )
            );

            $wp_customize->add_control(
                'bm-widgets-border-radius',
                array(
                    'label'   => _x( 'Mini Widgets Window Roundness', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_mini_widgets',
                    'type' => 'range',
                    'input_attrs' => array(
                        'min' => 0,
                        'step' => 1,
                        'max' => 20
                    )
                )
            );


            $wp_customize->add_setting(
                'bm-widgets-button-radius',
                array(
                    'section'    => 'better_messages_mini_widgets',
                    'transport'  => 'postMessage',
                    'default'    => 3
                )
            );

            $wp_customize->add_control(
                'bm-widgets-button-radius',
                array(
                    'label'   => _x( 'Mini Chats Buttons Roundness', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_mini_widgets',
                    'type' => 'range',
                    'input_attrs' => array(
                        'min' => 0,
                        'step' => 1,
                        'max' => 20
                    )
                )
            );

            $wp_customize->add_setting(
                'bm-widgets-position',
                array(
                    'section'    => 'better_messages_mini_widgets',
                    'transport'  => 'postMessage',
                    'default'    => 'right'
                )
            );

            $wp_customize->add_control(
                'bm-widgets-position',
                array(
                    'label'   => _x( 'Mini widgets position', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_mini_widgets',
                    'type'    => 'radio',
                    'description' => _x( 'Choose where do you want to locate mini widgets and mini chats', 'WP Customizer', 'bp-better-messages' ),
                    'choices' => array(
                        'left' => _x( 'Left side', 'WP Customizer', 'bp-better-messages' ),
                        'right' => _x( 'Right side', 'WP Customizer', 'bp-better-messages' ),
                    ),
                )
            );

            $wp_customize->add_setting(
                'bm-mini-widgets-width',
                array(
                    'section'    => 'better_messages_mini_widgets',
                    'transport'  => 'postMessage',
                    'default'    => 320
                )
            );

            $wp_customize->add_control(
                'bm-mini-widgets-width',
                array(
                    'label'   => _x( 'Width of Mini Widgets', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_mini_widgets',
                    'type' => 'number',
                    'input_attrs' => array(
                        'min' => 300
                    )
                )
            );

            $wp_customize->add_setting(
                'bm-mini-widgets-height',
                array(
                    'section'    => 'better_messages_mini_widgets',
                    'transport'  => 'postMessage',
                    'default'    => 450
                )
            );

            $wp_customize->add_control(
                'bm-mini-widgets-height',
                array(
                    'label'   => _x( 'Height of Mini Widgets', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_mini_widgets',
                    'type' => 'number',
                    'input_attrs' => array(
                        'min' => 300
                    )
                )
            );

            $wp_customize->add_setting(
                'bm-mini-widgets-indent',
                array(
                    'section'    => 'better_messages_mini_widgets',
                    'transport'  => 'postMessage',
                    'default'    => 20
                )
            );

            $wp_customize->add_control(
                'bm-mini-widgets-indent',
                array(
                    'label'   => _x( 'Indent of Mini Widgets', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_mini_widgets',
                    'description' => _x('Indent of mini widgets from the window side',  'WP Customizer', 'bp-better-messages' ),
                    'type' => 'number',
                    'input_attrs' => array(
                        'min' => 0
                    )
                )
            );

            $wp_customize->add_setting(
                'bm-mini-chats-width',
                array(
                    'section'    => 'better_messages_mini_widgets',
                    'transport'  => 'postMessage',
                    'default'    => 300
                )
            );

            $wp_customize->add_control(
                'bm-mini-chats-width',
                array(
                    'label'   => _x( 'Width of Mini Chat', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_mini_widgets',
                    'type' => 'number',
                    'input_attrs' => array(
                        'min' => 300
                    )
                )
            );

            $wp_customize->add_setting(
                'bm-mini-chats-height',
                array(
                    'section'    => 'better_messages_mini_widgets',
                    'transport'  => 'postMessage',
                    'default'    => 450
                )
            );

            $wp_customize->add_control(
                'bm-mini-chats-height',
                array(
                    'label'   => _x( 'Height of Mini Chat', 'WP Customizer', 'bp-better-messages' ),
                    'section' => 'better_messages_mini_widgets',
                    'type' => 'number',
                    'input_attrs' => array(
                        'min' => 450
                    )
                )
            );

        }


        public function header_output() {
            ob_start();
            $mod = get_theme_mod('bm-show-avatar-group', true);
            if( $mod !== true && ! is_customize_preview() ) {
                echo '.bp-messages-wrap .thread-info .avatar-group{display: none !important}.bp-messages-wrap .thread-info .avatar-group+.thread-info-data{max-width: 100% !important}';
            }

            $mod = get_theme_mod('bm-widgets-position', 'right');
            if( $mod === 'left' ){
                echo '.bp-better-messages-list{right: auto;left:var(--bm-mini-widgets-offset)}.bp-better-messages-mini{left:70px;right: auto}.bp-better-messages-list+.bp-better-messages-mini{right:auto;left:var(--bm-mini-chats-offset);}';
            }


            $mod = get_theme_mod('bm-date-enabled', true);

            if( ! $mod ){
                echo '.bp-messages-wrap .bm-messages-list .bm-list .bm-sticky-date{display:none}';
            }

            echo ':root{';

            $mod = get_theme_mod('main-bm-color', '#21759b');
            echo '--main-bm-color:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-border-radius', 3);
            echo '--bm-border-radius:' . $mod . 'px;';

            $mod = get_theme_mod('bm-widgets-border-radius', 0);
            echo '--bm-mini-chats-border-radius:' . $mod . 'px ' . $mod . 'px 0 0;';

            $mod = get_theme_mod('bm-widgets-button-radius', 5);
            echo '--bm-widgets-button-radius:' . $mod . 'px;';

            $mod = get_theme_mod('bm-mini-chats-width', 300 );
            echo '--bm-mini-chats-width:'. $mod . 'px;';

            $mod = get_theme_mod('bm-mini-chats-height', 450 );
            echo '--bm-mini-chats-height:'. $mod . 'px;';

            $mod = get_theme_mod('bm-mini-widgets-width', 320 );
            echo '--bm-mini-widgets-width:'. $mod . 'px;';

            $mod = get_theme_mod('bm-mini-widgets-height', 450 );
            echo '--bm-mini-widgets-height:'. $mod . 'px;';

            $mod = get_theme_mod('bm-mini-widgets-indent', 20 );
            echo '--bm-mini-widgets-offset:'. $mod . 'px;';

            $mod = get_theme_mod('bm-modern-radius', 2 );
            echo '--bm-message-border-radius:'. $mod . 'px;';

            $mod = get_theme_mod('bm-avatar-radius', 2 );
            echo '--bm-avatar-radius:'. $mod . 'px;';

            $mod = get_theme_mod('bm-date-radius', 3 );
            echo '--bm-date-radius:'. $mod . 'px;';

            /* LIGHT MODE START */
            $mod = get_theme_mod('bm-primary-bg', '#fff');
            echo '--bm-bg-color:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-secondary-bg', '#fafbfc');
            echo '--bm-bg-secondary:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-hover-bg', '#fafbfc');
            echo '--bm-hover-bg:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-primary-border', '#d7d8db');
            echo '--bm-border-color:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-secondary-border', '#ebebeb');
            echo '--bm-border-secondary-color:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-text-color', '#000');
            echo '--bm-text-color:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-modern-left-side-nickname', '#000');
            echo '--left-message-nickname-color:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-modern-left-side-bg', '#e8e8e8');
            echo '--left-message-bg-color:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-modern-left-side-color', '#000');
            echo '--left-message-text-color:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-modern-right-side-nickname', '#21759b');
            echo '--right-message-nickname-color:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-modern-right-side-bg', '#21759b');
            echo '--right-message-bg-color:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-modern-right-side-color', '#fff');
            echo '--right-message-text-color:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-sticky-date-bg', '#000');
            echo '--bm-sticky-date-bg:'. $mod . ';';

            $mod = get_theme_mod('bm-sticky-date-color', '#fff');
            echo '--bm-sticky-date-color:'. $mod . ';';

            $mod = get_theme_mod('bm-tooltip-bg', '#000');
            echo '--bm-tooltip-bg:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-tooltip-color', '#fff');
            echo '--bm-tooltip-color:'. $this->hex2rgba($mod) . ';';
            /* LIGHT MODE END */
            echo '}';

            /* DARK MODE START */

            echo 'body.bm-messages-dark{';

            $mod = get_theme_mod('bm-primary-bg-dark', '#181d2c');
            echo '--bm-bg-color-dark:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-secondary-bg-dark', '#1d2333');
            echo '--bm-bg-secondary-dark:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('main-bm-color-dark', '#fff');
            echo '--main-bm-color-dark:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-modern-left-side-nickname-dark', '#fff');
            echo '--left-message-nickname-color-dark:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-modern-left-side-bg-dark', '#e8e8e8');
            echo '--left-message-bg-color-dark:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-modern-left-side-color-dark', '#000');
            echo '--left-message-text-color-dark:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-modern-right-side-nickname-dark', '#fff');
            echo '--right-message-nickname-color-dark:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-hover-bg-dark', '#1c2338');
            echo '--bm-hover-bg-dark:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-modern-right-side-bg-dark', '#404e72');
            echo '--right-message-bg-color-dark:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-modern-right-side-color-dark', '#fff');
            echo '--right-message-text-color-dark:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-sticky-date-bg-dark', '#1d2333');
            echo '--bm-sticky-date-bg-dark:'. $mod . ';';

            $mod = get_theme_mod('bm-sticky-date-color-dark', '#fff');
            echo '--bm-sticky-date-color-dark:'. $mod . ';';

            $mod = get_theme_mod('bm-tooltip-bg-dark', '#000');
            echo '--bm-tooltip-bg-dark:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-tooltip-color-dark', '#fff');
            echo '--bm-tooltip-color-dark:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-primary-border-dark', '#3f485f');
            echo '--bm-border-color-dark:'. $this->hex2rgba($mod) . ';';

            $mod = get_theme_mod('bm-secondary-border-dark', '#3f485f');
            echo '--bm-border-secondary-color-dark:'. $this->hex2rgba($mod) . ';';

            /* DARK MODE END */
            return ob_get_clean();
        }

        public function body_class( $classes = [] ){
            $mod = get_theme_mod('bm-theme', 'light');

            if( $mod === 'dark' ) {
                return array_merge($classes, array('bm-messages-dark'));
            } else {
                return array_merge($classes, array('bm-messages-light'));
            }
        }


        public function hex2rgba($color) {
            $default = '0,0,0';

            //Return default if no color provided
            if(empty($color))
                return $default;

            //Sanitize $color if "#" is provided
            if ($color[0] == '#' ) {
                $color = substr( $color, 1 );
            }

            //Check if color has 6 or 3 characters and get values
            if (strlen($color) == 6) {
                $hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
            } elseif ( strlen( $color ) == 3 ) {
                $hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
            } else {
                return $default;
            }

            //Convert hexadec to rgb
            $rgb =  array_map('hexdec', $hex);

            //Return rgb(a) color string
            return implode(",",$rgb);
        }
    }

endif;

/**
 * @return Better_Messages_Customize instance | null
 */
function Better_Messages_Customize()
{
    return Better_Messages_Customize::instance();
}
