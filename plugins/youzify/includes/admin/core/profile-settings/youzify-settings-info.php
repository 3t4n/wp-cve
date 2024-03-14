<?php

/**
 * Infos settings
 */
function youzify_profile_info_tab_settings() {

    global $Youzify_Settings;

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Info Styling Settings', 'youzify' ),
            'class' => 'ukai-box-2cols',
            'type'  => 'openBox'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Info Title', 'youzify' ),
            'desc'  => __( 'Info titles color', 'youzify' ),
            'id'    => 'youzify_infos_wg_title_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field(
        array(
            'title' => __( 'Info Value', 'youzify' ),
            'desc'  => __( 'Info values color', 'youzify' ),
            'id'    => 'youzify_infos_wg_value_color',
            'type'  => 'color'
        )
    );

    $Youzify_Settings->get_field( array( 'type' => 'closeBox' ) );
}