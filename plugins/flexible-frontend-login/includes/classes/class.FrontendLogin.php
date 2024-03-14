<?php
/**
* Class for Frontend Login instances
* since Version 0.95
* added 2012-09-24 10:01:00
**/




class FrontendLogin
{
	public $popup_link_text = 'Login';
	public $vertical_position = 'bottom';
	public $horizontal_position = 'left';
	public $modal = true;
	public $html_block = '
										<table class="ffl-form-table">
										   <tbody>
											  <tr class="ffl-form-table-row">
												 <td class="ffl-form-table-cell-left">%label_for_username%</td>
												 <td class="ffl-form-table-cell-right">%input_username%</td>
											  </tr>
											  <tr class="ffl-form-table-row">
												 <td class="ffl-form-table-cell-left">%label_for_password%</td>
												 <td class="ffl-form-table-cell-right">%input_password%</td>
											  </tr>
											  <tr class="ffl-form-table-row">
												 <td colspan="2" class="ffl-table-cell-double">%send_button%</td>
											  </tr>
											 <tr class="ffl-form-table-row">
												 <td colspan="2" class="ffl-table-cell-double">%lost_password%</td>
											  </tr>
										   </tbody>
										</table>';
	public $show_username = true;
	public $username_display_type = 'display_name';
	public $show_logout_link = true;
	public $logout_link_text = 'Log Out';
	public $show_admin_link = false;
	public $admin_link_text =  'Admin';
	
	public function ffl_wrap_form_before()
	{
		$unique = rand(100,999);
		$html = "<div class='flexible-frontend-login'>";
		$html .= "<a name='ffl-popup' href='#ffl-popup-content-$unique' class='ffl-popup-link'>";
		$html .= $this->popup_link_text;
		$html .= "</a>";
		$html .= "<div id='ffl-popup-content-$unique' class='ffl-popup-content ffl-{$this->horizontal_position} ffl-{$this->vertical_position}'>";
		$html .= "<a class='ffl-close-popup-link'>";
		$html .= __( 'Close', 'flexible-frontend-login'  );
		$html .= "</a>";
		
		return $html;
	}
	
	public function ffl_wrap_form_after()
	{
		$html = "</div><!-- / .flexible-frontend-login-popup -->";
		$html .= "</div><!-- / .flexible-frontend-login -->";
		
		return $html;
	}
	
	public function ffl_wrap_form_modal_before()
	{
		$html = "<div id='ffl-container-modal' class='flexible-frontend-login'>";
		$html .= "<a href='#ffl-dialog' class='ffl-modal'>";
		$html .= $this->popup_link_text;
		$html .= "</a>";
		$html .= "<div id='ffl-boxes'>";
		$html .= "<div id='ffl-dialog' class='ffl-window'>";
		$html .= "<a class='ffl-close'>";
		$html .= __( 'Close', 'flexible-frontend-login' );
		$html .= "</a>";
		
		return $html;
	}
	
	public function ffl_wrap_form_modal_after()
	{
		$html = "</div><!-- / #ffl-dialog -->";
		$html .= "</div><!-- / #ffl-boxes -->";
		$html .= "</div><!-- / .flexible-frontend-login -->";
			
		return $html;
	}


	/* Use template */
	public function ffl_create_html_form() 
	{
		
		$html = "<form class='ffl-form' action='";
		$html .= site_url();
		$html .= "/wp-login.php' method='post' autocomplete='on'>";
		$html .= "<input type='hidden' name='rememberme' id='rememberme' value='forever' />";
		$html .= "<input type='hidden' name='redirect_to' value='";
		$html .= $_SERVER['REQUEST_URI'];
		$html .= "'>";

		// check if there is a custom template file
		if ( file_exists( get_stylesheet_directory() . '/flexible-frontend-login/ffl-template.tpl' ) ) 
		{
				$templatepath = get_stylesheet_directory() . '/flexible-frontend-login/ffl-template.tpl';
		} 
		elseif ( file_exists( get_template_directory() . '/flexible-frontend-login/ffl-template.tpl' ) ) 
		{
				$templatepath = get_template_directory() . '/flexible-frontend-login/ffl-template.tpl';
		} 
		else 
		{
				$templatepath = FFL_PATH . '/customization/ffl-template.tpl'; 
		}
		$template = file_get_contents( $templatepath );
	 
		$replacementPairs = 
			array(
				"label_for_username"	=>"<label for='ffl-input-username' id='ffl-label-username'>" . __( 'Username', 'flexible-frontend-login' ) . "</label>",
				"input_username"		=>"<input type='text' name='log' id='ffl-input-username' size='20' placeholder='" . __( 'Username', 'flexible-frontend-login' ) . "' pattern='^[@_öäüöÄÜß\w\s\.]{3,30}$'>",
				"label_for_password"	=>"<label for='ffl-input-password'  id='ffl-label-password'>" . __( 'Password', 'flexible-frontend-login' ) . "</label>",
				"input_password"		=>"<input type='password' name='pwd' size='20' id='ffl-input-password' placeholder='" . __( 'Password', 'flexible-frontend-login' ) . "' autocomplete='off'>",
				"send_button"			=>"<button type='submit' name='submit' id='ffl-submit'>" . __( 'Login', 'flexible-frontend-login' ) . "</button>",
				"lost_password"			=>"<a href='" . site_url() . "/wp-login.php?action=lostpassword' class='lostpassword' id='ffl-lostpassword'>" . __( 'Lost Password', 'flexible-frontend-login' ) . "</a>"
		   );
		//Perform replace/matching
		$replacements = array();
		$matches = array();
	 
		foreach($replacementPairs as $smatches=>$sreplacements){
			$replacements[] = $sreplacements;
			$matches[] = '{$'.$smatches.'}';
		}
		$html .= str_replace($matches, $replacements, $template);

		$html .= "</form>";	
		
		return $html;
	}

	
	/* Display something for logged in users */
	public function ffl_show_user_info() 
	{
		$html = '';
		$html .= "<div class='flexible-frontend-login' id='ffl-user-info'>";
		if ( $this->show_admin_link )
		{
			$html .= "<a id='ffl-admin-link' href='";
			$html .= get_bloginfo( 'url' );
			$html .= "/wp-admin'>";
			$html .= $this->admin_link_text;
			$html .= "</a>";
		}
		if ( $this->show_username )
		{
		$html .= "<span id='ffl-logged-in-user'>";
			global $current_user; 
			get_currentuserinfo(); 
			$displaytype = $this->username_display_type;
			switch ($displaytype) {
			case 'loginname':
				$html .= $current_user->user_login;
				break;			
			case 'firstname':
				$html .= $current_user->user_firstname;
				break;			
			case 'displayname':
				$html .= $current_user->display_name;
				break;
			default:							
				$html .= $current_user->display_name;
			}
		$html .= "</span>";
		}
		
		if ( $this->show_logout_link )
		{
			$redirect = $_SERVER[ 'REQUEST_URI' ];
			$logout_url = wp_logout_url( $redirect );
			$html .= "<a id='ffl-logout-link' href='$logout_url'>";
			$html .= $this->logout_link_text;
			$html .= "</a>";
		}
		$html .= "</div>";
		return $html;
	}

}

?>
