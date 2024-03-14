<?php

class Youzify_Info_Tab {


    /**
     * Tab.
     */
    function tab() {

        // Get User Profile Widgets
        $this->get_user_infos();

        do_action( 'youzify_after_infos_widgets' );
    }

    /**
     * Get Custom Widgets functions.
     */
    function get_user_infos() {

        if ( ! bp_is_active( 'xprofile' ) ) {
            return false;
        }

        require_once YOUZIFY_CORE . 'widgets/youzify-widgets/class-youzify-custom-infos.php';

        $custom_infos = new Youzify_Profile_Custom_Infos_Widget();

        do_action( 'bp_before_profile_loop_content' );

        if ( bp_has_profile() ) : while ( bp_profile_groups() ) : bp_the_profile_group();

            if ( bp_profile_group_has_fields() ) :

                $group_id = bp_get_the_profile_group_id();

                youzify_widgets()->youzify_widget_core( 'custom_infos', $custom_infos, array(
                    'icon'   => youzify_get_xprofile_group_icon( $group_id ),
                    'name'  => bp_get_the_profile_group_name(),
                    'id'   => 'custom_infos',
                    'load_effect'   => 'fadeIn'
                ) );

        endif; endwhile;

        endif;

        do_action( 'bp_after_profile_loop_content' );

    }

}