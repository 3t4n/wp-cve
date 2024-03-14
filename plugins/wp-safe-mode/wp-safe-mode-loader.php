<?php
/*
 * This class is the main loader class for WP Safe Mode, and handles all the enabling/disabling of safe mode.
 * It can be used automatically with the counterpart WP Safe Mode plugin, or independently for inaccessible sites.
 * To use it independently, move it to the mu-plugins folder (preferably an empty mu-plugins folder) or refer to our installation instructions.
 */
class WP_Safe_Mode {
	
	// START Editable Options: The following options can be edited here if you're having to recover your site by uploading this loader directly.
	
	// These two properties are the 'master switch' and can help you access an inaccessible site in safe mode.
	
	/**
	 * If turned on, safe mode will be enabled for all visitors.
	 * @var boolean
	 */
	public static $safe_mode_on = false;
	
	/**
	 * If set to true, settings from the database (via the WP Safe Mode Plugin admin area) will be totally ignored.
	 * Useful if you want to load Safe Mode independently from admin but also ignored previously stored settings that may be breaking the site.
	 * @var array
	 */
	public static $hardcoded_override = false;
	
	// The following properties are for enabling specific safe mode types.
	
	/**
	 * If turned on, safe mode will be enabled for the whole site.
	 * This setting is equivalent to WP_Safe_Mode::$safe_mode_on and is mainly useful in MultiSite environments whilst loding via init()
	 * @var boolean
	 */
	public static $site_safe_mode_on = false;
	
	/**
	 * If turned on, safe mode will be enabled for the whole network when in MultiSite.
	 * This setting is equivalent to WP_Safe_Mode::$safe_mode_on and is mainly useful in MultiSite environments whilst loding via init()
	 * @var boolean
	 */
	public static $network_safe_mode_on = false;
	
	/**
	 * Array of IPs where the plugins will be temporarily disabled. Change the value or add your own to the array below to activate safe mode when you visit the site.
	 * This is useful if you're completely blocked out of your site and you upload this file directly to mu-plugins.
	 * Only applicable if WP_Safe_Mode::$site_safe_mode_on is set to true.
	 * @var array
	 */
	public static $site_ip_array = array();
	
	/**
	 * Same as $site_ip_array, but this array of IPs will apply in Network Safe Mode.
	 * Only applicable if WP_Safe_Mode::$network_safe_mode_on is set to true.
	 * @var array
	 */
	public static $network_ip_array = array();
	
	// The following options will allow you to disable/enable plugins and switch themes whilst the safe modes above have been enabled. Edit accordingly.
	
	/**
	 * Whether to disable the theme and use the default WP theme.
	 * @var boolean
	 */
	public static $disable_themes = true;
	
	/**
	 * Array of themes that'll be allowed as a default theme.
	 * @var array
	 */
	public static $default_themes = array('twentyseventeen', 'twentysixteen', 'twentyfifteen', 'twentyfourteen', 'twentythirteen', 'twentytwelve');
	
	/**
	 * Whether to disable plugins and only enabled allowed plugins in WP_Safe_Mode::$plugins_to_keep
	 * @var boolean
	 */
	public static $disable_plugins = true;
	
	/**
	 * Array of plugins to keep in format of 'plugin-folder/plugin-file' per array item.
	 * Edit this to your liking and it'll be added to any settings currently stored in db.
	 * @var array
	 */
	public static $plugins_to_keep = array();

	/**
	 * Array of plugins to force-enable, so if they're disabled when safe mode is off, then they'll be enabled in safe mode
	 * @var array
	 */
	public static $plugins_to_enable = array( 'wp-safe-mode/wp-safe-mode.php' );
	
	/**
	 * In a MultiSite environment, when in Site Safe Mode, any plugins in this array will be disabled if network enabled.
	 * @var array
	 */
	public static $network_plugins_to_disable = array();
	
	/**
	 * Whether or not to attempt to load MU plugins from the WP_Safe_Mode::$mu_plugins_dir (mu-plugins) directory, if this file is not located in the mu-plugins folder already.
	 * @var array
	 */
	public static $load_mu_plugins = false;
	
	// END Editable Options: These next options are set by the plugin during loading time and editing will have no beneficial effect.
	
	/**
	 * If turned on and user is logged in, safe mode will be enabled.
	 * @var boolean
	 */
	public static $user_safe_mode_on = false;
	
	/**
	 * Same as $user_safe_mode_on but used for multisite environments where safe mode is enabled for a specific user on a specific site.
	 * In non-multisite environments this will be set to the same value as $user_safe_mode_on
	 * @var boolean
	 */
	public static $user_site_safe_mode_on = false;
	
	/**
	 * Salt used to verify user login and whether safe mode is turned on for the viewing user.
	 * @var string
	 */
	public static $user_safe_mode_salt;
	
	/**
	 * Will be set to true during the init process if the current user is not being put into network safe mode due to IP restrictions
	 * @var boolean
	 */
	private static $network_ip_restricted = false;
	
	/**
	 * Will be set to true during the init process if the current user is not being put into site safe mode due to IP restrictions
	 * @var boolean
	 */
	private static $site_ip_restricted = false;
	
	/**
	 * Name of the original mu-plugins foloder on this site. In 99.9% of cases, this should remain null and will become /path/to/wp-content/mu-plugins in init().
	 * However, if a site is already using a custom mu-plugins folder by defining WPMU_PLUGINS_DIR then this should be modified to match that directory name.
	 * @var string
	 */
	public static $mu_plugins_dir;
	
	/**
	 * If enabled in MultiSite, network admins can enable Site Safe Mode specifically.
	 * This enables user site safe mode (i.e. user safe mode for a speific site only), and also allows for
	 * the settings page in the dashboard if the WP Safe Mode plugin is also active/installed.
	 * @var boolean
	 */
	public static $multisite_single_site = false;
	
	/**
	 * Same as $multisite_single_site, but also allows site admins to enable safe mode on their own site.
	 * @var boolean
	 */
	public static $multisite_single_site_admins = false;
	
	/**
	 * Decide whether to run this script and add actions to initiate safe mode. 
	 */
	public static function init(){
		//check if user is logged in and turned safe mode on specifically for them, since this may affect settings loading
		self::$user_safe_mode_salt = sha1( $_SERVER['REMOTE_ADDR'] . NONCE_SALT );
		self::$user_safe_mode_on = !empty($_COOKIE['safe_mode']) && self::$user_safe_mode_salt == $_COOKIE['safe_mode'];
		//check if safe mode is being requested to be turned on/off
		if( !empty($_REQUEST['safe_mode_toggle_user']) && $_REQUEST['safe_mode_toggle_user'] == self::$user_safe_mode_salt ){
			if( self::$user_safe_mode_on ){
				self::$user_safe_mode_on = false;
				setcookie('safe_mode', 0, time() - DAY_IN_SECONDS, '/', null, is_ssl());
			}else{
				self::$user_safe_mode_on = true;
				setcookie('safe_mode', self::$user_safe_mode_salt, time() + DAY_IN_SECONDS, '/', null, is_ssl());
			}
			WP_Safe_Mode::redirect();
		}
		//check if safe mode is being requested to be turned on/off in MS mode
		if( is_multisite() && !is_network_admin() ){
			$blog_id = get_current_blog_id();
			$blog_salt = sha1(self::$user_safe_mode_salt . $blog_id);
			self::$user_site_safe_mode_on = !empty($_COOKIE['safe_mode_'.$blog_id]) && $blog_salt == $_COOKIE['safe_mode_'.$blog_id];
			if( !empty($_REQUEST['safe_mode_toggle_user_site']) && $_REQUEST['safe_mode_toggle_user_site'] == $blog_salt ){
				if( self::$user_site_safe_mode_on ){
					self::$user_site_safe_mode_on = false;
					setcookie('safe_mode_'.$blog_id, 0, time() - DAY_IN_SECONDS, '/', null, is_ssl());
				}else{
					self::$user_site_safe_mode_on = true;
					setcookie('safe_mode_'.$blog_id, $blog_salt, time() + DAY_IN_SECONDS, '/', null, is_ssl());
				}
				WP_Safe_Mode::redirect();
			}
		}elseif( !is_network_admin() ){
			self::$user_site_safe_mode_on = self::$user_safe_mode_on;
		}
		//next we load other settings into the object
		if( !self::$hardcoded_override ){
			if( is_multisite() && is_network_admin() ){
				$settings = get_site_option('wp_safe_mode_settings', array());
			}else{
				$settings = self::get_settings();
			}
			foreach( $settings as $setting_key => $setting_val ){
				if( property_exists('WP_Safe_Mode', $setting_key) ){
					if( is_array($setting_val) && is_array(self::${$setting_key}) ){
						//any hard-coded options here get tacked to the end of the resulting array
						if( is_array($setting_val) ) self::${$setting_key} = array_unique(array_merge($setting_val, self::${$setting_key}));
					}else{
						self::${$setting_key} = $setting_val;
					}
				}
			}
		}
		if( !self::$multisite_single_site) self::$multisite_single_site_admins = false; //so we can assume this argument can only be true if single_site is also true
		//check whether Safe Mode is active in one way or another
		if( !self::$safe_mode_on ){
			//check network safe mode
			if( is_multisite() ){
				if( self::$network_safe_mode_on ){
					//safe mode is on unless IP restrictions don't match
					self::$safe_mode_on = true;
					if( !empty(self::$network_ip_array) ){
						if( !in_array($_SERVER['REMOTE_ADDR'], self::$network_ip_array) ){
							self::$safe_mode_on = false;
							self::$network_ip_restricted = true;
						}
					}
				}
			}
			//check site safe mode which an override network safe mode
			if( !is_multisite() || self::$multisite_single_site ){
				if( self::$site_safe_mode_on ){
					//check safe mode with IP Restriction
					self::$safe_mode_on = true;
					if( !empty(self::$site_ip_array) ){
						if( !in_array($_SERVER['REMOTE_ADDR'], self::$site_ip_array) ){
							self::$safe_mode_on = false;
							self::$site_ip_restricted = true;
						}
					}
				}
			}
			if( self::$user_safe_mode_on || self::$user_site_safe_mode_on ) self::$safe_mode_on = true;
			//safe mode is now on or off - whether we proceed with disabling plugins and themes remains to be seen further down
		}
		//load other mu-plugins if required, or if safe mode is off and this file is not in the mu-plugins folder (i.e. a custom mu-plugins folder)
		if( empty($mu_plugins_dir) ) self::$mu_plugins_dir = WP_CONTENT_DIR . '/mu-plugins';
		if( !self::$safe_mode_on ) self::$load_mu_plugins = true; //we always load mu plugins if we are not in safe mode but with a custom mu-plugins folder
		if( self::$load_mu_plugins ){
			//the following is a mix of the mu-plugins folder loading in wp-settings.php and the wp_get_mu_plugins() function in wp-settings.php
			foreach ( self::wp_get_mu_plugins() as $mu_plugin ) {
				include_once( $mu_plugin );
			}
		}
		//check activation toggles for IP, network-wide and sitewide safe mode
		if( !empty($_REQUEST['safe_mode_toggle_site']) || !empty($_REQUEST['safe_mode_toggle_network']) ){
			add_action('init', 'WP_Safe_Mode::safe_mode_toggle');
		}
		//admin bar stuff
		add_action( 'admin_bar_menu', 'WP_Safe_Mode::admin_bar_menu', 999, 1 );
		add_action( 'admin_head', 'WP_Safe_Mode::css' );
		add_action( 'wp_head', 'WP_Safe_Mode::css' );
		//skip plugins and themes page to avoid deactivations
		if( (is_admin() || is_network_admin()) && preg_match('/\/(plugins|themes)\.php\??/', $_SERVER['REQUEST_URI']) ){
			add_action('admin_notices', 'WP_Safe_Mode::admin_notices');
			add_action('network_admin_notices', 'WP_Safe_Mode::admin_notices');
			add_action('plugins_loaded', 'WP_Safe_Mode::disable_plugin_safe_mode');
			add_action('after_setup_theme', 'WP_Safe_Mode::disable_theme_safe_mode');
		}
		//check if we're in safe mode and return if not
		if ( !self::$safe_mode_on ) return false;
		//plugins
		if( self::$disable_plugins ){
			add_filter( 'site_option_active_sitewide_plugins', 'WP_Safe_Mode::disable_multisite_plugins' );
			add_filter( 'option_active_plugins', 'WP_Safe_Mode::disable_plugins' );
		}
		//themes
		if( self::$disable_themes ){
			add_filter( 'site_option_allowedthemes', 'WP_Safe_Mode::disable_theme_multisite' );
			add_filter( 'stylesheet', 'WP_Safe_Mode::disable_theme');
			add_filter( 'template', 'WP_Safe_Mode::disable_theme');
			add_filter( 'option_stylesheet', 'WP_Safe_Mode::disable_theme' );
			add_filter( 'option_template', 'WP_Safe_Mode::disable_theme' );
		}
	}
	
	/**
	 * Add safe mode menu item to admin bar.
	 * @param WP_Admin_Bar $wp_admin_bar
	 */
	public static function admin_bar_menu( $wp_admin_bar ) {
		//users without ability to manage mode will just see a safe mode notice if enabled and admin bars show
		if( !self::can_user_enable() ){
			if( self::$safe_mode_on ){
				$wp_admin_bar->add_node( array(
					'id'    => 'wp-safe-mode',
					'title' => esc_html(sprintf(__('Safe Mode (%s)', 'wp-safe-mode'), __('Enabled', 'wp-safe-mode'))),
					'meta'  => array( 'class' => 'safe-mode safe-mode-on' )
				) );
			}
			return;
		}
		//Build extended top-level menu descriptor for admin bar, depending on safe mode status
		$ip_restricted = self::$site_ip_restricted || (self::$network_ip_restricted && current_user_can('install_plugins')); //safe mode is enabled, but restricted by IP and not enabled for current user
		if( $ip_restricted || self::$safe_mode_on ){
			// regular safe mode, it's an on-off thing
			$status = esc_html__('Enabled', 'wp-safe-mode');
			//now multisite considerations
			if( is_multisite() ){
				$status_meta = array();
				// multisite needs to be a little more informative since we have cascading safe modes
				if( self::$site_safe_mode_on || self::$user_site_safe_mode_on ){
					$site_text = __('Site', 'wp-safe-mode');
					//add site to desc along with user only asterisk if applicable
					if( !self::$site_safe_mode_on || self::$site_ip_restricted ) $site_text .= '*';
					$status_type_meta[] =  $site_text;
				}
				if( is_multisite() && (self::$network_safe_mode_on || self::$user_safe_mode_on) ){
					//if in multisite, single-site admins will only see this if network safe mode is being imposed on them
					if( !self::$network_ip_restricted || current_user_can('install_plugins') ){
						$network_text = __('Network', 'wp-safe-mode');
						//add network desc along with user only asterisk if applicable
						if( !self::$network_safe_mode_on || self::$network_ip_restricted ) $network_text .= '*';
						$status_type_meta[] = $network_text;
					}
				}
				if( !empty($status_type_meta) ){
					switch( count($status_type_meta) ){
						case 2 : $status_type_ph = _x('%s & %s', 'enabled safe mode types', 'wp-safe-mode'); break;
						case 1 : $status_type_ph = _x('%s', 'enabled safe mode types', 'wp-safe-mode'); break;
					}
					$status_meta[] = vsprintf( $status_type_ph, $status_type_meta);
				}
			}else{
				if( self::$user_site_safe_mode_on && (!self::$site_safe_mode_on || self::$site_ip_restricted) ){
					$status .= ' *';
				}elseif( self::$site_safe_mode_on && !$ip_restricted && !empty(self::$site_ip_array) ){
					$ip_restricted = true;
				}
			}
			//IP Restriction Warning
			if( $ip_restricted ){
				$status_meta[] = esc_html__('IP Restricted', 'wp-safe-mode');
			}
		}else{
			$status = esc_html__('Disabled', 'wp-safe-mode');
		}
		$safe_mode_status_text = sprintf(esc_html__('Safe Mode %s', 'wp-safe-mode'), $status);
		if( !empty($status_meta) ) $safe_mode_status_text .= ' - ' . implode(' - ', $status_meta);
		//Main Menu Item
		$args = array(
				'id'    => 'wp-safe-mode',
				'title' => $safe_mode_status_text,
				'meta'  => array( 'class' => 'safe-mode ' )
		);
		//restrict links
		if( !is_multisite() || (self::$multisite_single_site_admins || current_user_can('install_plugins')) ){
			$safe_mode_user_url = ( !is_multisite() || is_network_admin() ) ? self::safe_mode_toggle_user_url() : self::safe_mode_toggle_user_site_url();
			$args['href']  = $safe_mode_user_url['href'];
		}
		if( !self::$safe_mode_on && $ip_restricted ){
			$args['meta']['class'] .= 'safe-mode-on-others';
		}else{
			$args['meta']['class'] .= self::$safe_mode_on ? 'safe-mode-on':'safe-mode-off';
		}
		$wp_admin_bar->add_node( $args );
		//Submenu Action Items
		if( is_multisite() ){
			//we assume users at this point have the right caps, it's now whether settings allow them to toggle certain modes
			$can_view_site_toggle = self::$multisite_single_site && (self::$multisite_single_site_admins || current_user_can('install_plugins'));
			if( !is_network_admin() && $can_view_site_toggle )  $wp_admin_bar->add_node( self::safe_mode_toggle_user_site_url() );
			if( current_user_can('install_plugins') ) $wp_admin_bar->add_node( self::safe_mode_toggle_user_url() );
			if( !is_network_admin() && $can_view_site_toggle ) $wp_admin_bar->add_node( self::safe_mode_toggle_site_url() );
			if( current_user_can('install_plugins') ) $wp_admin_bar->add_node( self::safe_mode_toggle_network_url() );
		}else{
			$wp_admin_bar->add_node( $safe_mode_user_url );
			$wp_admin_bar->add_node( self::safe_mode_toggle_site_url() );
		}
	}
	
	/**
	 * Handles requests to toggle site and network safe modes, returns false on failure or redirects on success.
	 * @return bool
	 */
	public static function safe_mode_toggle(){
		if( !self::can_user_enable() ) return false;
		if( !empty($_REQUEST['safe_mode_toggle_site']) && wp_verify_nonce($_REQUEST['safe_mode_toggle_site'], 'safe_mode_toggle_site') ){
			$settings = get_option('wp_safe_mode_settings', array());
			$settings['site_safe_mode_on'] = self::$safe_mode_on = empty($settings['site_safe_mode_on']);
			update_option('wp_safe_mode_settings', $settings);
			self::redirect( is_network_admin() );
		}elseif( !empty($_REQUEST['safe_mode_toggle_network']) && wp_verify_nonce($_REQUEST['safe_mode_toggle_network'], 'safe_mode_toggle_network') ){
			$settings = get_site_option('wp_safe_mode_settings', array());
			$settings['network_safe_mode_on'] = self::$safe_mode_on = empty($settings['network_safe_mode_on']);
			update_site_option('wp_safe_mode_settings', $settings);
			self::redirect( true );
		}
	}
	
	/**
	 * Provides array of meta for generating link to toggle user safe mode with network context if in MultiSite
	 * @return array
	 */
	public static function safe_mode_toggle_user_url(){
		$status = !self::$user_safe_mode_on ? __('Enable', 'wp-safe-mode') : __('Disable', 'wp-safe-mode');
		$status_class = self::$user_safe_mode_on ? 'on':'off';
		$title = is_multisite() ? __('%s Safe Mode (Only Me - Network)', 'wp-safe-mode') : __('%s Safe Mode (Only Me)', 'wp-safe-mode');
		return array(
			'id'    => 'wp-safe-mode-toggle-user',
			'parent' => 'wp-safe-mode',
			'title' => esc_html( sprintf($title, $status) ),
			'href'  => esc_url( add_query_arg('safe_mode_toggle_user', self::$user_safe_mode_salt) ),
			'meta' => array(
				'class' => 'user-safe-mode safe-mode-type-'.$status_class,
				'title' => esc_html__('You can enable Safe Mode only for yourself, all other visitors will see the site with Safe Mode disabled.', 'wp-safe-mode')
			),
			'status' => self::$user_safe_mode_on
		);
	}
	
	/**
	 * Provides array of meta for generating link to toggle user site safe mode with network context if in MultiSite
	 * @return array
	 */
	public static function safe_mode_toggle_user_site_url(){
		$status = !self::$user_site_safe_mode_on ? __('Enable', 'wp-safe-mode') : __('Disable', 'wp-safe-mode');
		$status_class = self::$user_site_safe_mode_on ? 'on':'off';
		$blog_salt = sha1( self::$user_safe_mode_salt . get_current_blog_id() );
		return array(
			'id'    => 'wp-safe-mode-toggle-user-site',
			'parent' => 'wp-safe-mode',
			'title' => esc_html( sprintf(__('%s Safe Mode (Only Me - Site)', 'wp-safe-mode'), $status) ),
			'href'  => esc_url( add_query_arg('safe_mode_toggle_user_site', $blog_salt) ),
			'meta' => array(
				'class' => 'user-site-safe-mode safe-mode-type-'.$status_class,
				'title' => esc_html__('You can enable Safe Mode only for yourself, all other visitors will see the site with Safe Mode disabled.', 'wp-safe-mode')
			),
			'status' => self::$user_site_safe_mode_on
		);
	}
	
	/**
	 * Provides array of meta for generating link to toggle network safe mode if in MultiSite
	 * @return array
	 */
	public static function safe_mode_toggle_site_url(){
		$status = !self::$site_safe_mode_on ? __('Enable', 'wp-safe-mode') : __('Disable', 'wp-safe-mode');
		$status_class = self::$site_safe_mode_on ? 'on':'off';
		if( self::$site_safe_mode_on && self::$site_ip_restricted ) $status_class = 'on-others';
		return array(
			'id'    => 'wp-safe-mode-toggle',
			'parent' => 'wp-safe-mode',
			'title' => esc_html( sprintf(__('%s Safe Mode (Site)', 'wp-safe-mode'), $status) ),
			'href'  => esc_url(add_query_arg('safe_mode_toggle_site', wp_create_nonce('safe_mode_toggle_site'))),
			'meta' => array(
				'class' => 'safe-mode safe-mode-type-'.$status_class,
				'title' => esc_html__('Enable Safe Mode for this site.', 'wp-safe-mode')
			),
			'status' => self::$site_safe_mode_on
		);
	}
	
	/**
	 * Provides array of meta for generating link to toggle network safe mode in MultiSite
	 * @return array
	 */
	public static function safe_mode_toggle_network_url(){
		$status = !self::$network_safe_mode_on ? __('Enable', 'wp-safe-mode') : __('Disable', 'wp-safe-mode');
		$status_class = self::$network_safe_mode_on ? 'on':'off';
		if( self::$network_safe_mode_on && self::$network_ip_restricted ) $status_class = 'on-others';
		return array(
			'id'    => 'wp-safe-mode-toggle-network',
			'parent' => 'wp-safe-mode',
			'title' => esc_html( sprintf(__('%s Safe Mode (Network)', 'wp-safe-mode'), $status) ),
			'href'  => esc_url(add_query_arg('safe_mode_toggle_network', wp_create_nonce('safe_mode_toggle_network'))),
			'meta' => array(
				'class' => 'total-safe-mode safe-mode-type-'.$status_class,
				'title' => esc_html__('Safe Mode will be enabled on all sites accross the entire network.', 'wp-safe-mode')
			),
			'status' => self::$network_safe_mode_on
		);
	}
	
	/**
	 * Generates admin notices in the theme and plugin pages of the admin dashboard.
	 */
	public static function admin_notices(){
		if( preg_match('/\/(themes)\.php\??/', $_SERVER['REQUEST_URI']) ){
			//we're in a theme page
			if( self::$safe_mode_on ){
				$notices = array();
				if( self::$disable_themes ){
					$default_theme = self::get_safe_theme();
					if( $default_theme ){
						$WP_Theme = wp_get_theme($default_theme);
						if( is_multisite() && is_network_admin() ){
							$notices[] = sprintf( esc_html__('Safe Mode is currently enabled, which will load the %s theme on sites in this network.', 'wp-safe-mode'), '<code>'.$WP_Theme->get('Name').'</code>' );
						}else{
							$notices[] = sprintf( esc_html__('Safe Mode is currently enabled, which will load the %s theme on any other page. You can switch themes here which will be used when Safe Mode is deactivated.', 'wp-safe-mode'), '<code>'.$WP_Theme->get('Name').'</code>' );
						}
						echo '<div class="notice notice-info"><p>'. implode('</p><p>', $notices) .'</p></div>';
					}else{
						$notice = esc_html__('You do not have a default theme to revert to wihlst in Safe Mode, so the current theme will remain active. To enable a \'Safe Mode\' theme please select a default theme from the %s page or install any of the default Twenty-Something WordPress themes.', 'wp-safe-mode');
						$notices[] = sprintf( $notice, '<a href="'.admin_url('admin.php?page=wp-safe-mode').'">'.esc_html__('WP Safe Mode Settings', 'wp-safe-mode').'</a>' );
						echo '<div class="notice notice-warning"><p>'. implode('</p><p>', $notices) .'</p></div>';
					}
				}else{
					$notices[] = esc_html__('Safe mode is currently enabled, but themes are not set to be disabled.', 'wp-safe-mode');
					if( !is_multisite() || is_network_admin() || current_user_can('install_plugins') || self::$multisite_single_site_admins ){
						if( class_exists('WP_Safe_Mode_Admin') ){
							$notice = esc_html__('You can modify this behaviour via the %s page.', 'wp-safe-mode');
							$admin_url = is_multisite() ? network_admin_url('admin.php?page=wp-safe-mode') : admin_url('admin.php?page=wp-safe-mode');
							$notices[] = sprintf($notice, '<a href="'.$admin_url.'">'.esc_html__('WP Safe Mode Settings', 'wp-safe-mode').'</a>');
						}else{
							//counterpart plugin not installed, so we can also hard-code this text since it wouldn't get translated anyway.
							$notice = 'You can modify this behaviour by editing the wp-safe-mode.php file in %1$s or installing/activating the %2$s plugin and visiting the settings page.';
							$notices[] = sprintf($notice, '<code>'.__FILE__.'</code>', 'WP Safe Mode');
						}
					}
					echo '<div class="notice notice-warning"><p>'. implode('</p><p>', $notices) .'</p></div>';
				}
			}
		}else{
			$notices = array( 'warning' => array(), 'info' => array() );
			//we're in a plugin page
			if( self::$safe_mode_on ){
				$notices['info'][] = esc_html__('You are currently in Safe Mode, you can deactivate or activate the plugins below which will take effect when Safe Mode is disabled.', 'wp-safe-mode');
			}
			//check for mu-plugins folder installation warnings
			if( WPMU_PLUGIN_DIR == self::_DIR_() ){
				//check if we're in the custom mu-plugins folder
				if( WPMU_PLUGIN_DIR == WP_CONTENT_DIR . '/wp-safe-mode' ){
					//if so, is there another mu-plugins folder and are there any plugins there
					$mu_plugins = self::wp_get_mu_plugins();
					if( !empty($mu_plugins) ){
						//we warn them about whether or not these mu-plugins are being loaded
						$notice = esc_html__('You are currently loading a custom must-use directory which contains the WP Safe Mode loader file. Your default mu-plugins directory is %1$s which contains %2$d must-use plugins.', 'wp-safe-mode');
						$notices['info'][] = sprintf( str_replace('%2$d', '%2$s', $notice), '<code>'.self::$mu_plugins_dir.'</code>', '<code>'.count($mu_plugins).'</code>' ); //we replace digit placeholder with string to allow html code
						if( self::$load_mu_plugins ){
							//warn user that mu plugins are still being loaded, with varying text depending on whether safe mode is on or off
							if( self::$safe_mode_on ){
								$notices['warning'][] = esc_html__('Must-Use plugins are currently being loaded even though Safe Mode is enabled.', 'wp-safe-mode');
								//modify warning based on whether user only uploaded mu-plugin loader file or installed the entire WP Safe Mode plugin
								if( !is_multisite() || is_network_admin() || self::can_user_enable() ){ //show this only to admins with permission if in multisite
									if( class_exists('WP_Safe_Mode_Admin') ){
										$notice = esc_html__('You can modify this behaviour via the %s page.', 'wp-safe-mode');
										$admin_url = is_multisite() ? network_admin_url('admin.php?page=wp-safe-mode') : admin_url('admin.php?page=wp-safe-mode');
										$notices['warning'][] = sprintf($notice, '<a href="'.$admin_url.'">'.esc_html__('WP Safe Mode Settings', 'wp-safe-mode').'</a>');
									}else{
										//counterpart plugin not installed, so we can also hard-code this text since it wouldn't get translated anyway.
										$notice = 'You can modify this behaviour by editing the wp-safe-mode.php file in %1$s or installing/activating the %2$s plugin and visiting the settings page.';
										$notices['warning'][] = sprintf($notice, '<code>'.__FILE__.'</code>', 'WP Safe Mode');
									}
								}
							}else{
								$notices['info'][] = esc_html__('Must-Use plugins are currently being loaded even if Safe Mode is disabled.', 'wp-safe-mode');
							}
						}
						if( !empty($_REQUEST['plugin_status']) && $_REQUEST['plugin_status'] == 'mustuse' ){
							$notices['info'][] = esc_html__('Currently loaded mu-plugins are :', 'wp-safe-mode');
							ob_start();
							echo '<ul>';
							foreach( $mu_plugins as $mu_plugin ){
								$plugin_data = get_plugin_data( $mu_plugin, false, false );
								$plugin_name = !empty($plugin_data['Name']) ? $plugin_data['Name'] : basename($mu_plugin);
								echo '<li> - <em>'. $plugin_name . '</em></li>';
							}
							echo '</ul>';
							$notices['info'][] = ob_get_clean();
						}else{
							$notices['info'][] = esc_html__('You can still view a list of loaded plugins in the "Must Use" plugins list below.', 'wp-safe-mode');
						}
					}
				}else{
					//not in a custom mu-plugins folder
					$mu_plugins = wp_get_mu_plugins();
					if( count($mu_plugins) > 1 ){
						//there's at least one more mu-plugin file being loaded, so we will issue a warning about that
						$notices['warning'][] = esc_html__('You have installed the WP Safe Mode loader file in the default mu-plugins folder, which contains more plugins that will get loaded even in Safe Mode.', 'wp-safe-mode');//modify warning based on whether user only uploaded mu-plugin loader file or installed the entire WP Safe Mode plugin
						if( class_exists('WP_Safe_Mode_Admin') ){
							$notice = esc_html__('To prevent Must-Use plugins from being loaded in Safe Mode, you need to install the loader via the %s page.', 'wp-safe-mode');
							$notices['warning'][] = sprintf($notice, '<a href="'.admin_url('admin.php?page=wp-safe-mode').'">'.esc_html__('WP Safe Mode Settings', 'wp-safe-mode').'</a>');
						}else{
							//counterpart plugin not installed, so we can also hard-code this text since it wouldn't get translated anyway.
							$notices['warning'][] = 'To prevent Must-Use plugins from being loaded in Safe Mode, please refer to our <a href="https://wordpress.org/plugins/wp-safe-mode/#installation">installation instructions</a>, since you do not have the main \'WP Safe Mode\' plugin active.';
						}
					}
				}
			}
			$notices[] = '';
			foreach( $notices as $notice_type => $type_notices){
				if( !empty($type_notices) ){
					echo '<div class="notice notice-'.$notice_type.'"><p>'. implode('</p><p>', $type_notices) .'</p></div>';
				}
			}
		}
	}
	
	/**
	 * Output CSS for admin bar 
	 */
	public static function css(){
		if( !is_user_logged_in() ) return;
		?>
		<style type="text/css">
			#wpadminbar #wp-admin-bar-wp-safe-mode.safe-mode:hover > .ab-item { color:white; }
			#wpadminbar #wp-admin-bar-wp-safe-mode.safe-mode-on, #wpadminbar #wp-admin-bar-wp-safe-mode.safe-mode-on:hover > .ab-item { background-color:green; }
			#wpadminbar #wp-admin-bar-wp-safe-mode.safe-mode-on-others, #wpadminbar #wp-admin-bar-wp-safe-mode.safe-mode-on-others:hover > .ab-item { background-color:orange; }
			#wpadminbar #wp-admin-bar-wp-safe-mode.safe-mode-off, #wpadminbar #wp-admin-bar-wp-safe-mode.safe-mode-off:hover > .ab-item { background-color: rgba(255, 255, 255, 0.3); }
			#wpadminbar #wp-admin-bar-wp-safe-mode .safe-mode-type-on a { color:green !important; }
			#wpadminbar #wp-admin-bar-wp-safe-mode .safe-mode-type-on-others a { color:orange !important; }
		</style>
		<?php
	}
	
	/**
	 * Use to redirect user after toggling safe mode on/off. Redirects users to home page or admin page when turning safe mode on, 
	 * given they may be on a plugin-specific page that got deactivated.
	 * @param $network_admin
	 */
	public static function redirect( $network_admin = "" ){
		if( self::$safe_mode_on && is_admin() ){
			$url = defined('WP_SAFE_MODE_VERSION') ? 'admin.php?page=wp-safe-mode':'';
			if( $network_admin === "" ) $network_admin = is_network_admin();
			$url = $network_admin ? network_admin_url($url) : admin_url($url);
		}else{
			$url = esc_url_raw( add_query_arg( array(
					'safe_mode_toggle_user' => false,
					'safe_mode_toggle_user_site' => false,
					'safe_mode_toggle_site' => false,
					'safe_mode_toggle_network' => false,
					'settings_updated' => false,
					'errors' => false,
				)
			));
		}
		header("Location: $url", true);
		exit();
	}
	
	/**
	 * Takes an array of plugins and filters out only the plugins we want to keep
	 * @param array $plugins
	 * @return array
	 */
	public static function disable_plugins( $plugins ) {
		$network_plugins = array();
		if( is_multisite() ){
			//prevent network-active plugins from getting removed or kept, that's handled in a separate filter
			remove_filter( 'site_option_active_sitewide_plugins', 'WP_Safe_Mode::disable_multisite_plugins' ); //deactivate
			//deactivated filter to allow us to get original list of network-active plugins
			$network_plugins = (array) get_site_option( 'active_sitewide_plugins', array() );
			add_filter( 'site_option_active_sitewide_plugins', 'WP_Safe_Mode::disable_multisite_plugins' ); //reactivate
		}
		//first remove all plugins we haven't decided to keep
		foreach( $plugins as $key => $plugin ){
			if( !in_array($plugin, self::$plugins_to_keep) && !array_key_exists($plugin, $network_plugins)){
				unset( $plugins[$key] );
			}
		}
		//now add plugins we want to enable, active or not
        foreach( self::$plugins_to_enable as $plugin ){
            if( !in_array($plugin, $plugins) && !array_key_exists($plugin, $network_plugins) ){
                $plugins[] = $plugin;
            }
        }
		return $plugins;
	}
	
	/**
	 * Same as disable_multisite_plugins but checks the key of array, as supplied in MultiSite network active plugins
	 * @param array $plugins
	 * @return array
	 */
	public static function disable_multisite_plugins( $plugins ){
		//disable/enable network plugins if in network safe mode
		if( !is_array($plugins) ) return $plugins;
		if( self::$network_safe_mode_on || self::$user_safe_mode_on ){
			foreach( $plugins as $key => $val ){
				//multisite stores network activated sites in key, normal sites in value
				if( !in_array($key, self::$plugins_to_keep) ){
					unset( $plugins[$key] );
				}
			}
	        foreach( self::$plugins_to_enable as $plugin ){
		        if( empty($plugins[$plugin]) ){
	                $plugins[$plugin] = 1;
	            }
	        }
		}
		//disable network-activated plugins in site safe mode only
		if( self::$site_safe_mode_on || self::$user_site_safe_mode_on ){
			foreach( self::$network_plugins_to_disable as $plugin ){
				if( !empty($plugins[$plugin]) ) unset($plugins[$plugin]);
			}
		}
		return $plugins;
	}
	
	/**
	 * Disables filters for disabling/enabling plugins in safe mode.
	 */
	public static function disable_plugin_safe_mode(){
		remove_filter( 'site_option_active_sitewide_plugins', 'WP_Safe_Mode::disable_multisite_plugins' );
		remove_filter( 'option_active_plugins', 'WP_Safe_Mode::disable_plugins' );
	}
	
	/**
	 * Takes a list of themes and returns the list of allowed themes.
	 * @param array $themes
	 * @return array
	 */
	public static function disable_theme_multisite( $themes ){
		foreach( $themes as $theme => $active){
			if( !preg_match('/^twenty[a-z]+$/', $theme) ){
				unset($themes[$theme]);
			}
		}
		return $themes;
	}
	
	/**
	 * Disables the current theme if not a chosen 'safe' theme and approves the current one  
	 * @param string $theme
	 * @return string
	 */
	public static function disable_theme( $theme ){
		$safe_theme = self::get_safe_theme();
		if( !empty($safe_theme) ){
			$theme = $safe_theme;
		}
		return $theme;
	}
	
	/**
	 * Disables filters for disabling/enabling themes in safe mode.
	 */
	public static function disable_theme_safe_mode(){
		remove_filter( 'site_option_allowedthemes', 'WP_Safe_Mode::disable_theme_multisite' );
		remove_filter( 'stylesheet', 'WP_Safe_Mode::disable_theme');
		remove_filter( 'template', 'WP_Safe_Mode::disable_theme');
		remove_filter( 'option_stylesheet', 'WP_Safe_Mode::disable_theme' );
		remove_filter( 'option_template', 'WP_Safe_Mode::disable_theme' );
	}
	
	/**
	 * Obtains the 'safe' theme to be loaded during safe mode, if none is availble then false is returned.
	 * @return string|false
	 */
	public static function get_safe_theme(){
		foreach( self::$default_themes as $default_theme ){
			$theme_root = get_theme_root($default_theme);
			if( file_exists("$theme_root/$default_theme/style.css") ){
				return $default_theme;
			}
		}
		return false;
	}
	
	/**
	 * Whether or not the user can manage and enable safe mode.
	 * @param $user_id - ID of the user, defaults to the current user.
	 * @return boolean
	 */
	public static function can_user_enable( $user_id = 0 ){
		if( empty($user_id) ) $user_id = get_current_user_id();
		//unless we're in multisite or admin, user can't enable safe mode
		if( is_multisite() ){
			if( user_can( $user_id, 'install_plugins' ) ) return true;
			//check if user can enable/disable
			if( self::$multisite_single_site_admins ){
				return user_can( $user_id, 'switch_themes' );
			}
		}else{
			return user_can( $user_id, 'install_plugins' );
		}
		return false;
	}
	
	/**
	 * Returns the required capability required for an admin to manage safe mode a specific site.
	 * @return bool|string
	 */
	public static function get_admin_capability(){
		$capability = 'install_plugins';
		if( is_multisite() && !is_network_admin() ){
			// we ensure that single-site admin menu is enabled and single-site admins have permission to see it
			if( !empty(self::$multisite_single_site) ){
				//display safe mode settings to super admins, and single-site admins if settings allow it
				if( !empty(self::$multisite_single_site_admins) ) $capability = 'switch_themes';
			}else{
				return false; //don't load a Safe Mode admin menu on single-site
			}
		}
		return $capability;
	}
	
	/**
	 * Gets the list from the site's original mu-plugins folder, provided this plugin isn't already in that folder.
	 * This function replaces the default wp_get_mu_plugins() function in WordPress so that we can check a different folder
	 * than the WPMU_PLUGINS_DIR which is hard-coded in that function.
	 * @return array
	 */
	public static function wp_get_mu_plugins(){
		//check if this file is located within the mu-plugins directory, if not then load mu-plugin files
		if ( self::$mu_plugins_dir != self::_DIR_() && WPMU_PLUGIN_DIR == self::_DIR_() ){
			//this is an adaptation of wp_get_mu_plugins() function in wp-settings.php
			if( is_dir( self::$mu_plugins_dir ) && $dh = opendir( self::$mu_plugins_dir ) ){
				$mu_plugins = array();
				while ( ( $plugin = readdir( $dh ) ) !== false ) {
					if ( substr( $plugin, -4 ) == '.php' )
						$mu_plugins[] = self::$mu_plugins_dir . '/' . $plugin;
				}
				closedir( $dh );
				sort( $mu_plugins );
				return $mu_plugins;
			}
		}
		return array();
	}
	
	/**
	 * Returns the array of settings for the site.
	 * @param boolean $network
	 * @return array
	 */
	public static function get_settings( $network = null ){
		//load settings and override these settings
		if( $network && is_multisite() ){
			$settings = get_site_option('wp_safe_mode_settings', array());
		}elseif( $network === false ){
			$settings = get_option('wp_safe_mode_settings', array());
		}else{
			//load site settings, or for MultiSite mix in network settings with single-site settings accordingly
			if( is_multisite() ){
				$network_settings = get_site_option('wp_safe_mode_settings', array()); // by default, network options are used
				$settings = !empty($network_settings['network_safe_mode_on']) || self::$user_safe_mode_on ? $network_settings : array();
				$settings['multisite_single_site'] = !empty($network_settings['multisite_single_site']);
				$settings['multisite_single_site_admins'] = !empty($network_settings['multisite_single_site_admins']);
				if( !empty($settings['multisite_single_site']) ){
					// single-site overrides allowed, so we merge array but also merge in arrays within the array
					$site_settings = get_option('wp_safe_mode_settings', array()); // so we can check if we're in site safe mode to override options
					if( self::$user_site_safe_mode_on || !empty($site_settings['site_safe_mode_on']) ){
						// if plugins or themes aren't to be disabled on site, don't override that setting on the network-level
						if( empty($site_settings['disable_plugins']) ){
							unset($site_settings['plugins_to_keep'], $site_settings['plugins_to_enable'], $site_settings['network_plugins_to_disable']);
							if( isset($settings['disable_plugins']) ){
								unset($site_settings['disable_plugins']);
							}
						}
						if( empty($site_settings['disable_themes']) ){
							unset($site_settings['default_themes']);
							if( isset($settings['disable_themes']) ){
								unset($site_settings['disable_themes']);
							}
						}
						// merge the rest in, overriding network settings
						foreach( $site_settings as $setting_key => $setting ){
							if( isset($settings[$setting_key]) && is_array($settings[$setting_key]) ){
								//merge in array and make it unique
								$settings[$setting_key] = array_unique( array_merge($settings[$setting_key], $setting) );
								if( $setting_key == 'default_themes' ) $settings['default_themes']  = array_reverse($settings['default_themes']); //flip themes since currently network setting takes precedence
							}else{
								$settings[$setting_key] = $setting;
							}
						}
					}
				}
			}else{
				$settings = get_option('wp_safe_mode_settings');
			}
		}
		if( !is_array($settings) ) $settings = array();
		return $settings;
	}
	
	/**
	 * Returns an array containing directory and filname of this loader file.
	 * @return array
	 */
	public static function where(){
		if( function_exists('wp_safe_mode_loader_location') ){
			return wp_safe_mode_loader_location();
		}
		return array( '__DIR__' => dirname(__FILE__), '__FILE__' => __FILE__ );
	}
	
	/**
	 * Shortcut for getting directory name via where() function.
	 * @return string
	 * @uses WP_Safe_Mode::where()
	 */
	private static function _DIR_(){
		$where = self::where();
		return $where['__DIR__'];
	}
	
	/**
	 * Shortcut for getting file name via where() function.
	 * @return string
	 * @uses WP_Safe_Mode::where()
	 */
	private static function _FILE_(){
		$where = self::where();
		return $where['__FILE__'];
	}
}
WP_Safe_Mode::init();