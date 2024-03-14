<?php
/* Create default settings on activation */
function paw_ffl_default_options() {

	if( !get_option( 'flexible-frontend-login' ) ) { // only add these options if not already existing
		$paw_ffl_options = array(
			'popup_link_text' 		=> __('Click to login'),
			'arrange_logged_in_links' => array(
													'1' 	=> 'Admin Link',
													'2'	=> 'Username', 
													'3' 	=> 'Logout Link'		
													),	
													
			'show_logout_link' 		=> 1,
			'logout_link_text' 		=> __('Logout'),
			'show_username' 			=> 0,
			
			'show_admin_link' 		=> 1,
			'admin_link_text' 		=> __('Admin'),
			'role_for_admin_link'	=> 'Administrator',
															
			'delete_options_on_deactivation'	=> 0,
			'delete_options_on_plugin_delete' 	=> 1,

			'vertical_position' 		=> 'bottom',
			'horizontal_position' 	=> 'left',
										
			'css_usage'	=> 'css_from_plugin',
			
			'custom_css'=> '
/* This is a copy of the plugin\'s CSS file for your convenience.
/* Copy and paste the content of this editor into your own ffl-style.css
/* Create a subfolder called "flexible-frontend-login" in your theme folder
/* Edit the CSS to match your theme
/* Upload the ffl-style.css into the new folder
/* Finally choose "Use CSS file from your theme" in the dropdown menu above 

/* Z-index of #ffl-mask must lower than #ffl-boxes .ffl-window 
/* Some themes work with z-index especially in the header
/* In rare cases you might need to adjust the following z-index values
/* if the popup form is not accessible.
**/

#ffl-mask {
  position:absolute;
  float:left;
  z-index:9000;
  background-color:#000;
  display:none;
}

#ffl-boxes #ffl-dialog {
  position:fixed;
  display:none;
  z-index:9999 !important;

}
 
 
/* Customize your modal window here, you can add background image too */
#ffl-boxes #ffl-dialog {	
background-color: #FFF;
	padding:1em 2em;
	border:solid 1px #000;
	box-shadow:0 0 6px 2px #000;
}

/** FUNCTIONAL VALUES **/
/* You should not delete or change these values. */
.flexible-frontend-login {
	display:inline;
	position:relative;
	}

.ffl-popup-content {
	z-index:500;
	}

/* positioning of the popup */
.flexible-frontend-login .ffl-top {
	position: absolute; 
	bottom:0; 
	}

.flexible-frontend-login .ffl-bottom {
	position: absolute; 
	top:0;
	}

.flexible-frontend-login .ffl-left {
	position: absolute; 
	right: 0; 
	}

.flexible-frontend-login .ffl-right {
	position: absolute; 
	left:0;
	}

/** END FUNCTIONAL VALUES **/



/* DIV container for the complete plugin output */
.flexible-frontend-login {
	}

/* Link to access the popup */
.flexible-frontend-popup-link {
	}

/* DIV that contains the popup */
.ffl-popup-content {
	background-color: #FFF !important;
	padding:1em 2em;
	border:solid 1px #CCC;
	box-shadow:0 0 4px 1px #224;
	}


/* Link inside popup to close it again*/	
.ffl-close-popup-link {
	display:block;
	text-align:right;
	}

/* IDs to access all form elements individually */

#ffl-label-username {
	padding-right:.5em;
	}
#ffl-input-username {
	padding:.1em .2em;
	}
#ffl-label-password {	
	padding-right:.5em;
	}
#ffl-input-password {
	margin-top:.3em;
	padding:.1em .2em;
	}
#ffl-submit {
	margin-top:.3em;
	width:100%;
	}
#ffl-lostpassword {
	}

#ffl-userlinks {
	list-style:none;
	margin:0;
}	
#ffl-userlinks li {
	display:inline;
}
#ffl-userlinks a {
	padding:0 .25em;
}
#ffl-logged-in-user {
	padding-left:.6em;
	}
#ffl-logout-link {
	padding-left:.6em;
	}',

		);
		update_option( 'flexible_frontend_login', $paw_ffl_options ); 
	}
} 

?>
