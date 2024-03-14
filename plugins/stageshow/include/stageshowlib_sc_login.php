<?php

if (!class_exists('StageShowLibLoginShortcodeClass')) 
{
	class StageShowLibLoginShortcodeClass // Define class
	{	
		function __construct($pluginObj)
		{	
			$this->pluginObj = $pluginObj;
			$this->myDBaseObj = $pluginObj->myDBaseObj;
			
			$this->cssDomain = 'stageshow';
		}
		
		function Output($args)
		{
	  		// FUNCTIONALITY: Runtime - Output Shop Front
			$myDBaseObj = $this->myDBaseObj;
						
			if ( is_user_logged_in() )
			{
				$msg = __('You are logged in!', 'stageshow');
				return "<p>$msg</p>";
			}

			/* Set up some defaults. */
			$defaults = array(
				'label_username' => 'Username',
				'label_password' => 'Password'
			);

			/* Merge the user input arguments with the defaults. */
			$args = shortcode_atts( $defaults, $args );

			/* Set 'echo' to 'false' because we want it to always return instead of print for shortcodes. */
			$args['echo'] = false;

			$lostmsg = __('Lost Your Password?', 'stageshow');
			$clickmsg = __('Click Here!', 'stageshow');
			$lostPasswordURL = get_option('siteurl').'/wp-login.php?action=lostpassword';
			
			$content = wp_login_form($args);
			$content .= "$lostmsg <a href=\"$lostPasswordURL\">$clickmsg</a>";
			
			return $content;
		}
		
	}
}
		
