<?php

class core_all_in_one_intranet {
	
	protected function __construct() {
		$this->add_actions();
	}
	
	// PRIVATE SITE
	
	public function aioi_template_redirect() {
		$options = $this->get_option_aioi();
		if (!$options['aioi_privatesite']) {
			return;
		}

	    $allow_access = false;
		if (substr($_SERVER['REQUEST_URI'], 0, 16) == '/wp-activate.php' || substr($_SERVER['REQUEST_URI'], 0, 11) == '/robots.txt') {
		    $allow_access = true;
		}

		$allow_access = apply_filters('aioi_allow_public_access', $allow_access);

		if ($allow_access) {
		    return;
        }

		// We do want a private site
		if (!is_user_logged_in()) {
			auth_redirect();
		}
		else {
			if (is_multisite()) {
				$this->handle_private_loggedin_multisite($options);
			}
			else {
				// Bar access to users with no role
				$user = wp_get_current_user();
				if (!$user || !is_array($user->roles) || count($user->roles) == 0) {
					wp_logout();
					$output = '<p>'.esc_html__('You attempted to login to the site, but you do not have any permissions. If you believe you should have access, please contact your administrator.', 'all-in-one-intranet').'</p>';
					wp_die($output);
				}
			}
		}
	}
	
	// Override to decide what to do for Multisite
	protected function handle_private_loggedin_multisite($options) {
	}
	
	// Handler for robots.txt - just disallow if private
	public function aioi_robots_txt($output, $public) {
		$options = $this->get_option_aioi();
		if ($options['aioi_privatesite']) {	
			return "Disallow: /\n";
		}
		return $output;
	}
	
	// Don't allow ping backs if private
	public function aioi_option_ping_sites($sites) {
		$options = $this->get_option_aioi();
		if ($options['aioi_privatesite']) {
			return '';
		}
		return $sites;
	}

	// Disable REST API
    public function aioi_rest_pre_dispatch() {
	    $options = $this->get_option_aioi();
	    $allow_access = !$options['aioi_privatesite'] || is_user_logged_in();
	    $allow_access = apply_filters('aioi_allow_public_access', $allow_access);

	    if (!$allow_access) {
		    return new WP_Error( 'not-logged-in', 'REST API Requests must be authenticated because All-In-One Intranet is active', array( 'status' => 401 ) );
	    }
    }
	
	// LOGIN REDIRECT
	
	public function aioi_login_redirect($redirect_to, $requested_redirect_to='', $user=null) {
		if (!is_null($user) && isset($user->user_login)) {
			$options = $this->get_option_aioi();
			if ($options['aioi_loginredirect'] != '' && admin_url() == $redirect_to) {
				return $options['aioi_loginredirect']; 
			}
		}
		return $redirect_to;
	}
	
	// AUTO-LOGOUT
	
	// Reset timer on login
	public function aioi_wp_login($username, $user) {
		try {
			if ($user->ID) {
				update_user_meta($user->ID, 'aioi_last_activity_time', time());
			}
		} catch (Exception $ex) {
		}
	}
	
	// Check whether user should be auto-logged out this time
	public function aioi_check_activity() {
		if (is_user_logged_in()) {
			$user_id = get_current_user_id();
			$last_activity_time = (int)get_user_meta($user_id, 'aioi_last_activity_time', true);
			$logout_time_in_sec = $this->get_autologout_time_in_seconds();
			if ($logout_time_in_sec > 0 && $last_activity_time + $logout_time_in_sec < time()) {
				$current_url = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . "{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
				wp_logout();
				wp_redirect($current_url); // Should hit the Login wall if site is private
				exit;
			} else {
				update_user_meta($user_id, 'aioi_last_activity_time', time());
			}
		}
	}
	
	protected function get_autologout_time_in_seconds() {
		$options = $this->get_option_aioi();
		if ($options['aioi_autologout_time'] == 0) {
			return 0;
		}
		
		switch ($options['aioi_autologout_units']) {
			case 'days':
				return $options['aioi_autologout_time'] * 60 * 60 * 24;
				break;

			case 'hours':
				return $options['aioi_autologout_time'] * 60 * 60;
				break;
			
			case 'minutes':
			default:
				return $options['aioi_autologout_time'] * 60;
				break;
		}
	}
	
	
	// PUT SETTINGS MENU ON PLUGINS PAGE
	
	public function aioi_plugin_action_links( $links, $file ) {
		if ($file == $this->my_plugin_basename()) {
			$settings_link = '<a href="'.$this->get_settings_url().'">'.__('Settings', 'all-in-one-intranet').'</a>';
			array_unshift( $links, $settings_link );
		}
	
		return $links;
	}
	
	// ADMIN OPTIONS
	// *************
	
	protected function get_options_menuname() {
		return 'aioi_list_options';
	}
	
	protected function get_options_pagename() {
		return 'aioi_options';
	}
	
	protected function get_settings_url() {
		return is_multisite()
		? network_admin_url( 'settings.php?page='.$this->get_options_menuname() )
		: admin_url( 'options-general.php?page='.$this->get_options_menuname() );
	}
	
	// Add All-In-One Intranet to the Settings menu in admin panel
	public function aioi_admin_menu() {
		if (is_multisite()) {
			add_submenu_page( 'settings.php', __('All-In-One Intranet settings', 'all-in-one-intranet'),
                __('All-In-One Intranet', 'all-in-one-intranet'),
			'manage_network_options', $this->get_options_menuname(),
			array($this, 'aioi_options_do_page'));
		}
		else {
			add_options_page( __('All-In-One Intranet settings', 'all-in-one-intranet'),
            __('All-In-One Intranet', 'all-in-one-intranet'),
			'manage_options', $this->get_options_menuname(),
			array($this, 'aioi_options_do_page'));
		}
	}
	
	// Entry point of admin settings page
	public function aioi_options_do_page() {
		
		wp_enqueue_script( 'aioi_admin_js', $this->my_plugin_url().'js/aioi-admin.js', array('jquery') );
		wp_enqueue_style( 'style', $this->my_plugin_url().'css/style.css' );
	
		$submit_page = is_multisite() ? 'edit.php?action='.$this->get_options_menuname() : 'options.php';
	
		if (is_multisite()) {
			$this->aioi_options_do_network_errors();
		}
		?>
			
		<h2><?php esc_html_e('All-In-One Intranet setup', 'all-in-one-intranet'); ?></h2>
		<div id="gal-tablewrapper">
		
		<div id="gal-tableleft" class="gal-tablecell">
		<hr />
		<br />
		
		<form action="<?php echo $submit_page; ?>" method="post">
		
		<?php 
		settings_fields($this->get_options_pagename());
		$this->aioi_privacysection_text();
		$this->aioi_memberssection_text();
		$this->aioi_loginredirectsection_text();
		$this->aioi_autologoutsection_text();
		$this->aioi_licensesection_text();
		?>
		<p class="submit">
			<input type="submit" value="<?php esc_attr_e('Save Changes', 'all-in-one-intranet') ?>" class="button button-primary" id="submit" name="submit">
		</p>
		
		</form>
	</div>
	<?php $this->ga_options_do_sidebar(); ?>
</div>

		<?php
	}
	
	protected function aioi_privacysection_text() {
		$options = $this->get_option_aioi();
				
		echo "<h3>".esc_html__('Privacy','all-in-one-intranet')."</h3>";
		
		echo "<input id='input_aioi_privatesite' name='".$this->get_options_name()."[aioi_privatesite]' type='checkbox' ".($options['aioi_privatesite'] ? 'checked' : '')." class='checkbox' />";
		echo '<label for="input_aioi_privatesite" class="checkbox plain">';
		esc_html_e('Force site to be entirely private', 'all-in-one-intranet');
		echo '</label>';
		
		echo "<br />";
		
		if (is_multisite()) {
			echo "<input id='input_aioi_ms_requiremember' name='".$this->get_options_name()."[aioi_ms_requiremember]' type='checkbox' ".($options['aioi_ms_requiremember'] ? 'checked' : '')." class='checkbox' />";
			echo '<label for="input_aioi_ms_requiremember" class="checkbox plain">';
			esc_html_e('Require logged-in users to be members of a sub-site to view it', 'all-in-one-intranet' );
			echo '</label>';
			
			echo "<br />";
		}
		
		echo "<p>".esc_html__('Note that your media uploads (e.g. photos) will still be accessible to anyone who knows their direct URLs.', 'all-in-one-intranet')."</p>";
		
		$this->display_registration_warning();
		echo "<br />";
	}
	protected function output_drip_form() {
      $userdata = wp_get_current_user();
      if (!$userdata) {
          return;
      }
	$signedup = get_user_meta($userdata->ID, 'gal_user_signedup_to_drip', true);

	if (!$signedup) {

          $useremail = $userdata->user_email;

		?>
		<div>
			<form action="https://www.getdrip.com/forms/9468024/submissions" method="post" target="_blank" data-drip-embedded-form="9468024" id="gal-drip-signup-form">
				<h3 data-drip-attribute="headline">Get the most out of Google Apps and WordPress</h3>
				<p data-drip-attribute="description">
                      Register your email address to receive information on building a WordPress site
                      that truly integrates G Suite and WordPress.
                  </p>
				<div>
					<label for="fields[email]">Email Address</label>
                      <br />
					<input type="email" name="fields[email]" value="<?php echo esc_js($useremail); ?>" />
                      <br />
					<input type="submit" name="submit" value="Sign Up" data-drip-attribute="sign-up-button" class="gal-drip-signup-button" />
				</div>
                  <p class="gal-drip-unsubscribe">
                      You can unsubscribe at any time, and we will never share your email address.
                  </p>
			</form>
		</div>
		<?php
	}
}
	protected function display_registration_warning() {
		if (get_option('users_can_register')) {
			echo '<p>'
                 . '<b>'.esc_html__('Warning:', 'all-in-one-intranet').'</b> '
                 . esc_html__('Your site is set so that &quot;Anyone can register&quot; themselves. ', 'all-in-one-intranet');
			echo '<a href="'
					.admin_url( 'options-general.php' )
					.'">'.esc_html__('Turn off here', 'all-in-one-intranet').'</a>';
			echo '</p>';
		}
	}
	
	// Override to deal with members of sub-sites in a multisite
	protected function aioi_memberssection_text() {
	}
	
	protected function aioi_loginredirectsection_text() {
		$options = $this->get_option_aioi();
	
		echo "<h3>".esc_html__('Login Redirect', 'all-in-one-intranet')."</h3>";
	
		echo '<label for="input_aioi_loginredirect" class="textbox plain">';
		esc_html_e( 'Redirect after login to URL: ', 'all-in-one-intranet');
		echo '</label>';
	
		echo "<input id='input_aioi_loginredirect' name='".$this->get_options_name()."[aioi_loginredirect]' type='input' value='".esc_attr($options['aioi_loginredirect'])."' size='60' />";
		
		echo "<br />";
		
		echo "<p>".esc_html__('Effective when users login via /wp-login.php directly. Otherwise, they will be taken to the page they were trying to access before being required to login.', 'all-in-one-intranet')."</p>";
		
		echo "<br />";
		echo "<br />";
	}
	
	protected function aioi_autologoutsection_text() {
		$options = $this->get_option_aioi();
		
		echo "<h3>".esc_html('Auto Logout', 'all-in-one-intranet')."</h3>";
		
		echo '<label for="input_aioi_autologout_time" class="textbox plain">';
		esc_html_e('Auto logout inactive users after ', 'all-in-one-intranet');
		echo '</label>';
		
		echo "<input id='input_aioi_autologout_time' name='".$this->get_options_name()."[aioi_autologout_time]' type='input' value='".esc_attr($options['aioi_autologout_time'] == 0 ? '' : $options['aioi_autologout_time'])."' size='10' />";
		
		echo "<select name='".$this->get_options_name()."[aioi_autologout_units]'>";
		echo $this->list_options(Array('minutes', 'hours', 'days'), $options['aioi_autologout_units']);
		echo "</select> ".esc_html__("(leave blank to turn off auto-logout)", 'all-in-one-intranet');

		echo "<br />";
		echo "<br />";
	}

	// Override in Premium
	protected function aioi_licensesection_text() {
    }

  protected function ga_options_do_sidebar() {
  	$drivelink = "https://wp-glogin.com/drive/?utm_source=Admin%20Sidebar&utm_medium=freemium&utm_campaign=Drive";
  	$upgradelink = "https://wp-glogin.com/glogin/?utm_source=Admin%20Sidebar&utm_medium=freemium&utm_campaign=Freemium";
  	$avatarslink = "https://wp-glogin.com/avatars/?utm_source=Admin%20Sidebar&utm_medium=freemium&utm_campaign=Avatars";
  	$aioilink = "https://wp-glogin.com/intranet/?utm_source=Admin%20Sidebar&utm_medium=freemium&utm_campaign=AIOI";
  	
  	$adverts = Array();
  	
  	$adverts[] = '<div>'
  	.'<a href="'.$upgradelink.'" target="_blank">'
  	.'<img alt="Login upgrade" src="'.$this->my_plugin_url().'img/basic_loginupgrade.png" />'
  	.'</a>'
  	.'<span>Buy our <a href="'.$upgradelink.'" target="_blank">premium Login plugin</a> to revolutionize user management</span>'
  	.'</div>';
  	
  	$adverts[] = '<div>'
  	.'<a href="'.$drivelink.'" target="_blank">'
  	.'<img alt="Google Drive Embedder Plugin" src="'.$this->my_plugin_url().'img/basic_driveplugin.png" />'
  	.'</a>'
  	.'<span>Try our <a href="'.$drivelink.'" target="_blank">Google Drive Embedder</a> plugin</span>'
  	.'</div>';

  	$adverts[] = '<div>'
  	.'<a href="'.$avatarslink.'" target="_blank">'
  	.'<img alt="Google Profile Avatars Plugin" src="'.$this->my_plugin_url().'img/basic_avatars.png" />'
  	.'</a>'
  	.'<span>Bring your site to life with <a href="'.$avatarslink.'" target="_blank">Google Profile Avatars</a></span>'
  	.'</div>';

  	$adverts[] = '<div>'
  	.'<a href="'.$aioilink.'" target="_blank">'
  	.'<img alt="All-In-One Intranet Plugin" src="'.$this->my_plugin_url().'img/basic_aioi.png" />'
  	.'</a>'
  	.'<span>Instantly turn WordPress into a corporate intranet with <a href="'.$aioilink.'" target="_blank">All-In-One Intranet</a></span>'
  	.'</div>';
  	
  	$startnum = (int)date('j');
  	
  	echo '<div id="gal-tableright" class="gal-tablecell">';

  	$this->output_drip_form();
  	
  	for ($i=0 ; $i<2 ; $i++) {
  		echo $adverts[($startnum+$i) % 4];
  	}
  	
  	echo '</div>';
  	
  }

	protected function list_options($list, $current) {
		$output = '';
		$trans_map = Array(
			'minutes' => esc_html__('Minutes', 'all-in-one-intranet'),
			'hours' => esc_html__('Hours', 'all-in-one-intranet'),
            'days' => esc_html__('Days', 'all-in-one-intranet')
        );
		foreach ($list as $opt) {
			$output .= '<option value="'.esc_attr($opt).'" '.($current == $opt ? 'selected="selected"' : '').'>'.$trans_map[$opt].'</option>';
		}
		return $output;
	}
	public function aioi_options_validate($input) {
		$newinput = Array();
		$newinput['aioi_version'] = $this->PLUGIN_VERSION;
		$newinput['aioi_privatesite'] = isset($input['aioi_privatesite']) ? (boolean)$input['aioi_privatesite'] : false;
		$newinput['aioi_ms_requiremember'] = isset($input['aioi_ms_requiremember']) ? (boolean)$input['aioi_ms_requiremember'] : false;
		
		$newinput['aioi_autologout_time'] = isset($input['aioi_autologout_time']) ? trim($input['aioi_autologout_time']) : '';
		if(!preg_match('/^[0-9]*$/i', $newinput['aioi_autologout_time'])) {
			add_settings_error(
			'aioi_autologout_time',
			'nan_texterror',
			self::get_error_string('aioi_autologout_time|nan_texterror'),
			'error'
			);
			$newinput['aioi_autologout_time'] = 0;
		}
		else {
			$newinput['aioi_autologout_time'] = intval($newinput['aioi_autologout_time']);
		}
		
		$newinput['aioi_autologout_units'] = isset($input['aioi_autologout_units']) ? $input['aioi_autologout_units'] : '';
		if (!in_array($newinput['aioi_autologout_units'], Array('minutes', 'hours', 'days'))) {
			$newinput['aioi_autologout_units'] = 'minutes';
		}
		
		$newinput['aioi_loginredirect'] = isset($input['aioi_loginredirect']) ? $input['aioi_loginredirect'] : '';
		
		return $newinput;
	}
	
	protected function get_error_string($fielderror) {
		$local_error_strings = Array(
				'aioi_autologout_time|nan_texterror' => __('Auto logout time should be blank or a whole number', 'all-in-one-intranet')
		);
		if (isset($local_error_strings[$fielderror])) {
			return $local_error_strings[$fielderror];
		}
		return __('Unspecified error', 'all-in-one-intranet');
	}
	
	public function aioi_save_network_options() {
		check_admin_referer( $this->get_options_pagename().'-options' );
	
		if (isset($_POST[$this->get_options_name()]) && is_array($_POST[$this->get_options_name()])) {
			$inoptions = $_POST[$this->get_options_name()];
			$outoptions = $this->aioi_options_validate($inoptions);
				
			$error_code = Array();
			$error_setting = Array();
			foreach (get_settings_errors() as $e) {
				if (is_array($e) && isset($e['code']) && isset($e['setting'])) {
					$error_code[] = $e['code'];
					$error_setting[] = $e['setting'];
				}
			}
	
			update_site_option($this->get_options_name(), $outoptions);
				
			// redirect to settings page in network
			wp_redirect(
			add_query_arg(
			array( 'page' => $this->get_options_menuname(),
			'updated' => true,
			'error_setting' => $error_setting,
			'error_code' => $error_code ),
			network_admin_url( 'admin.php' )
			)
			);
			exit;
		}
	}
	
	protected function aioi_options_do_network_errors() {
		if (isset($_REQUEST['updated']) && $_REQUEST['updated']) {
			?>
					<div id="setting-error-settings_updated" class="updated settings-error">
					<p>
					<strong><?php esc_html_e('Settings saved', 'all-in-one-intranet'); ?></strong>
					</p>
					</div>
				<?php
			}
	
			if (isset($_REQUEST['error_setting']) && is_array($_REQUEST['error_setting'])
				&& isset($_REQUEST['error_code']) && is_array($_REQUEST['error_code'])) {
				$error_code = $_REQUEST['error_code'];
				$error_setting = $_REQUEST['error_setting'];
				if (count($error_code) > 0 && count($error_code) == count($error_setting)) {
					for ($i=0; $i<count($error_code) ; ++$i) {
						?>
					<div id="setting-error-settings_<?php echo $i; ?>" class="error settings-error">
					<p>
					<strong><?php echo htmlentities2($this->get_error_string($error_setting[$i].'|'.$error_code[$i])); ?></strong>
					</p>
					</div>
						<?php
				}
			}
		}
	}
	
	// OPTIONS
	
	protected function get_default_options() {
		return Array('aioi_version' => $this->PLUGIN_VERSION,
					 'aioi_privatesite' => true,
					 'aioi_ms_requiremember' => true,
					 'aioi_autologout_time' => 0,
					 'aioi_autologout_units' => 'minutes',
					 'aioi_loginredirect' => '');
	}
	
	protected $aioi_options = null;
	protected function get_option_aioi() {
		if ($this->aioi_options != null) {
			return $this->aioi_options;
		}
	
		$option = get_site_option($this->get_options_name(), Array());
	
		$default_options = $this->get_default_options();
		foreach ($default_options as $k => $v) {
			if (!isset($option[$k])) {
				$option[$k] = $v;
			}
		}
	
		$this->aioi_options = $option;
		return $this->aioi_options;
	}
	
	// ADMIN
	
	public function aioi_admin_init() {
		register_setting( $this->get_options_pagename(), $this->get_options_name(), Array($this, 'aioi_options_validate') );

		global $pagenow;	
	}
	
	protected function add_actions() {

		add_action('plugins_loaded', array($this, 'aioi_plugins_loaded'));
		
		if (is_admin()) {
			add_action( 'admin_init', array($this, 'aioi_admin_init'), 5, 0 );
			
			add_action(is_multisite() ? 'network_admin_menu' : 'admin_menu', array($this, 'aioi_admin_menu'));
			
			if (is_multisite()) {
				add_action('network_admin_edit_'.$this->get_options_menuname(), array($this, 'aioi_save_network_options'));
				add_filter('network_admin_plugin_action_links', array($this, 'aioi_plugin_action_links'), 10, 2 );
			}
			else {
				add_filter( 'plugin_action_links', array($this, 'aioi_plugin_action_links'), 10, 2 );
			}
		}

		add_action( 'template_redirect', array($this, 'aioi_template_redirect') );
		add_filter( 'robots_txt', array($this, 'aioi_robots_txt'), 0, 2);
		add_filter( 'option_ping_sites', array($this, 'aioi_option_ping_sites'), 0, 1);
		add_filter( 'rest_pre_dispatch', array($this, 'aioi_rest_pre_dispatch'), 0, 1);
		
		add_filter( 'login_redirect', array($this, 'aioi_login_redirect'), 10, 3);
		
		add_action( 'wp_login', array($this, 'aioi_wp_login'), 10, 2);
		add_action( 'init', array($this, 'aioi_check_activity'), 1);
	}

	public function aioi_plugins_loaded() {
		load_plugin_textdomain( 'all-in-one-intranet', false, dirname($this->my_plugin_basename()).'/lang/' );
	}


}

?>