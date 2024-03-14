<?php
	
	class Mo_lla_Spam
	{
		function __construct()
		{
			if(get_option('mo_lla_enable_comment_spam_blocking') || get_option('mo_lla_activate_recaptcha_for_comments'))
			{
				add_filter( 'preprocess_comment'		, array($this, 'comment_spam_check'			) );
				add_action( 'comment_form_after_fields' , array($this, 'comment_spam_custom_field'	) );
			}
		}

		
		function comment_spam_check( $comment_data ) 
		{
			if(!is_user_logged_in()){
			global $mollaUtility;
			if( isset($_POST['mocomment']) && !empty($_POST['mocomment']))
				wp_die( __( 'You are not authorised to perform this action.'));
			else if(get_option('mo_lla_activate_recaptcha_for_comments'))
			{
				if(is_wp_error($mollaUtility->verify_recaptcha(sanitize_text_field($_POST['g-recaptcha-response']))))
					wp_die( __( 'Invalid captcha. Please verify captcha again.'));
			}
			return $comment_data;
		}
		else{
			return $comment_data;	
		}
		}

		function comment_spam_custom_field()
		{
			echo '<input type="hidden" name="mocomment" />';
			if(get_option('mo_lla_activate_recaptcha_for_comments'))
			{
					wp_register_script( 'wpns_catpcha_js',esc_url(Mo_lla_MoWpnsConstants::RECAPTCHA_URL));
					wp_enqueue_script( 'wpns_catpcha_js' );
				echo '<div class="g-recaptcha" data-sitekey="'.esc_html(get_option('mo_lla_recaptcha_site_key')).'"></div>';
			}
		}
	}
	new Mo_lla_Spam;