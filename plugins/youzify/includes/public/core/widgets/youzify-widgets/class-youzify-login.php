<?php

class Youzify_Profile_Login_Button_Widget {

    /**
     * Profile Content.
     */
    function widget() {

    	if ( is_user_logged_in() ) {
    		return;
    	}

    	?><a href="<?php echo youzify_get_login_page_url(); ?>" data-show-youzify-login="true" class="youzify-profile-login"><i class="fas fa-user-circle"></i><?php _e( 'Sign in to your account', 'youzify' ); ?></a><?php
    }

}