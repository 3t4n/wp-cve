<?php

/**
 * User Balance Settings.
 */
function youzify_user_balance_widget_settings() {

    global $Youzify_Settings;

    if ( ! defined( 'myCRED_VERSION' ) && ! defined( 'GAMIPRESS_VER' ) ) {

        $Youzify_Settings->get_field(
            array(
                'msg_type'  => 'info',
                'type'      => 'msgBox',
                'id'        => 'youzify_msgbox_user_balance_widget_notice',
                'title'     => __( 'How to activate user balance widget?', 'youzify' ),
                'msg'       => sprintf( __( 'Please install the <a href="%1s"> MyCRED Plugin</a> or <a href="%2s"> GamiPress Plugin</a> to activate the user balance widget.' , 'youzify' ), 'https://wordpress.org/plugins/mycred/', 'https://wordpress.org/plugins/gamipress/' )
            )
        );

    } else {

        if( defined( 'myCRED_VERSION' ) ) {

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'MyCred General Settings', 'youzify' ),
                    'type'  => 'openBox'
                )
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Display Title', 'youzify' ),
                    'id'    => 'youzify_wg_user_balance_display_title',
                    'desc'  => __( 'Show widget title', 'youzify' ),
                    'type'  => 'checkbox'
                )
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Widget Title', 'youzify' ),
                    'id'    => 'youzify_wg_user_balance_title',
                    'desc'  => __( 'Add widget title', 'youzify' ),
                    'type'  => 'text'
                )
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Loading Effect', 'youzify' ),
                    'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
                    'desc'  => __( 'How you want the widget to be loaded?', 'youzify' ),
                    'id'    => 'youzify_user_balance_load_effect',
                    'type'  => 'select'
                )
            );


            $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Box Gradient Settings', 'youzify' ),
                    'class' => 'ukai-box-2cols',
                    'type'  => 'openBox'
                )
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Left Color', 'youzify' ),
                    'id'    => 'youzify_user_balance_gradient_left_color',
                    'desc'  => __( 'Gradient left color', 'youzify' ),
                    'type'  => 'color'
                )
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Right Color', 'youzify' ),
                    'id'    => 'youzify_user_balance_gradient_right_color',
                    'desc'  => __( 'Gradient right color', 'youzify' ),
                    'type'  => 'color'
                )
            );

            $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

        }

        if ( defined( 'GAMIPRESS_VER' ) ) {

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'GamiPress General Settings', 'youzify' ),
                    'type'  => 'openBox'
                )
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Display Title', 'youzify' ),
                    'id'    => 'youzify_wg_gamipress_user_balance_display_title',
                    'desc'  => __( 'Show widget title', 'youzify' ),
                    'type'  => 'checkbox'
                )
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Widget Title', 'youzify' ),
                    'id'    => 'youzify_wg_gamipress_user_balance_title',
                    'desc'  => __( 'Add widget title', 'youzify' ),
                    'type'  => 'text'
                )
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Loading Effect', 'youzify' ),
                    'opts'  => $Youzify_Settings->get_field_options( 'loading_effects' ),
                    'desc'  => __( 'How you want the widget to be loaded?', 'youzify' ),
                    'id'    => 'youzify_gamipress_user_balance_load_effect',
                    'type'  => 'select'
                )
            );


            $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Box Gradient Settings', 'youzify' ),
                    'class' => 'ukai-box-2cols',
                    'type'  => 'openBox'
                )
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Left Color', 'youzify' ),
                    'id'    => 'youzify_gamipress_user_balance_gradient_left_color',
                    'desc'  => __( 'Gradient left color', 'youzify' ),
                    'type'  => 'color'
                )
            );

            $Youzify_Settings->get_field(
                array(
                    'title' => __( 'Right Color', 'youzify' ),
                    'id'    => 'youzify_gamipress_user_balance_gradient_right_color',
                    'desc'  => __( 'Gradient right color', 'youzify' ),
                    'type'  => 'color'
                )
            );

            $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );

        }
    }

    do_action( 'youzify_user_balance_widget_settings' );

}