<?php

/**
 * Social Networks Settings.
 */
function youzify_social_networks_widget_settings() {

    global $Youzify_Settings;

    // Get Social Networks
    $social_networks = youzify_option( 'youzify_social_networks' );

    // Unserialize data
    if ( is_serialized( $social_networks ) ) {
        $social_networks = unserialize( $social_networks );
    }

    // Get Args
    $args = youzify_get_profile_widget_args( 'social_networks' );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Social Networks', 'youzify' ),
            'id'    => $args['id'],
            'icon'  => $args['icon'],
            'type'  => 'open'
        )
    );

    if ( ! empty( $social_networks )  ) {

        foreach ( $social_networks as $network => $data ) {
            $Youzify_Settings->get_field(
                array(
                    'title' => sanitize_text_field( $data['name'] ),
                    'id'    => $network,
                    'type'  => 'text'
                ), true
            );
        }

    }

    $Youzify_Settings->get_field( array( 'type' => 'close' ) );
}