<?php

class Youzify_Gamipress_Profile_User_Balance_Widget {

    /**
     * Widget Content.
     */
    function widget() {

        youzify_styling()->gradient_styling(
            array(
                'pattern'       => 'geometric',
                'selector'      => '.youzify-gamipress-user-balance-box',
                'left_color'    => 'youzify_gamipress_user_balance_gradient_left_color',
                'right_color'   => 'youzify_gamipress_user_balance_gradient_right_color'
            )
        );

        do_action( 'youzify_gamipress_user_balance_widget_content' );
    }

}