<?php

class Youzify_Profile_User_Balance_Widget {

    /**
     * Widget Content.
     */
    function widget() {

        youzify_styling()->gradient_styling(
            array(
                'pattern'       => 'geometric',
                'selector'      => '.youzify-user-balance-box',
                'left_color'    => 'youzify_user_balance_gradient_left_color',
                'right_color'   => 'youzify_user_balance_gradient_right_color'
            )
        );

        do_action( 'youzify_user_balance_widget_content' );
    }

}