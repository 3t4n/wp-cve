<?php
class pc_robotstxt_admin {

	function __construct() {
		// stuff to do when the plugin is loaded
		add_action( 'admin_menu', array( &$this, 'admin_menu' ) );
	}

	function admin_menu() {
		add_options_page( 'Virtual Robots.txt Settings', 'Virtual Robots.txt', 'manage_options', __FILE__, array( &$this, 'settings_page' ) );
	}// end function

	function settings_page() {
		
		global $pc_robotstxt;
		$options = $pc_robotstxt->get_options();

		$protocol = ( ( !empty($_SERVER['HTTPS'] ) && $_SERVER['HTTPS'] != 'off' ) || $_SERVER['SERVER_PORT'] == 443 ) ? "https://" : "http://";
		$host = $_SERVER['HTTP_HOST'];

		if ( isset($_POST['update']) ) {
			
			// check user is authorised
			if ( function_exists( 'current_user_can' ) && !current_user_can( 'manage_options' ) ) {
				die( 'Sorry, not allowed...' );
			}
			check_admin_referer( 'pc_robotstxt_settings' );

			$options['user_agents'] = trim( $_POST['user_agents'] );

			isset( $_POST['remove_settings'] ) ? $options['remove_settings'] = true : $options['remove_settings'] = false;

			update_option( 'pc_robotstxt', $options );

			echo '<div id="message" class="updated fade"><p><strong>Settings saved.</strong></p></div>';
		
		}// end if

		echo '<div class="wrap">'
			.'<h2>Virtual Robots.txt Settings</h2>'
			.'<form method="post">';
		if ( function_exists( 'wp_nonce_field' ) ) wp_nonce_field( 'pc_robotstxt_settings' );
		echo '<h3>User Agents and Directives for this site</h3>'
			.'<p>The default rules that are set when the plugin is first activated are appropriate for WordPress.</p>'
			.'<p>You can <a href="' . $protocol . $host . '/robots.txt" target="_blank" onclick="window.open(\'' . $protocol . $host . '/robots.txt\', \'popupwindow\', \'resizable=1,scrollbars=1,width=760,height=500\');return false;">preview your robots.txt file here</a> (opens a new window). If your robots.txt file doesn\'t match what is shown below, you may have a physical file that is being displayed instead.</p>'
			.'<table class="form-table">'
			.'<tr>'
				.'<td colspan="2"><textarea name="user_agents" rows="6" id="user_agents" style="width:99%;height:300px;">' .strip_tags( stripslashes( $options['user_agents'] ) ) . '</textarea></td>'
			.'</tr>'
			.'<tr>'
				.'<th scope="row">Delete settings when deactivating this plugin:</th>'
				.'<td><input type="checkbox" id="remove_settings" name="remove_settings"';
					if ( $options['remove_settings'] ) echo 'checked="checked"';
					echo ' /> <span class="setting-description">When you tick this box all saved settings will be deleted when you deactivate this plugin.</span></td>'
			.'</tr>'
			.'</table>'
			.'<p class="submit"><input type="submit" name="update" class="button-primary" value="Save Changes" /></p>'
			.'</form>'
			.'</div>';
		
	}// end function

}// end class
$pc_robotstxt_admin = new pc_robotstxt_admin;
