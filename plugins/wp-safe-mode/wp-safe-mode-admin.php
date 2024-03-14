<?php
/**
 * Hanndles all the UI elements for modifying WP Safe Mode behaviour as well as installing the loader.
 * Class WP_Safe_Mode_Admin
 */
class WP_Safe_Mode_Admin {
	
	public static $mu_plugins_custom_dir;
	public static $mu_wp_config;
	
	public static function init(){
		self::$mu_plugins_custom_dir = WP_CONTENT_DIR . '/wp-safe-mode';
		$mu_plugins_custom_dir = "'".self::$mu_plugins_custom_dir."'";
		if( ABSPATH . 'wp-content/wp-safe-mode' == self::$mu_plugins_custom_dir ){
			$mu_plugins_custom_dir = "dirname(__FILE__).'/wp-content/wp-safe-mode'";
		}elseif( ABSPATH . basename(WP_CONTENT_DIR) . '/wp-safe-mode' == self::$mu_plugins_custom_dir  ){
			$mu_plugins_custom_dir = "dirname(__FILE__).'/".basename(WP_CONTENT_DIR)."/wp-safe-mode'";
		}
		self::$mu_wp_config = "if( !defined('WPMU_PLUGIN_DIR') ) define( 'WPMU_PLUGIN_DIR', $mu_plugins_custom_dir ); //WP Safe Mode";
		add_action('admin_menu', 'WP_Safe_Mode_Admin::admin_menu', 9999);
		add_action('network_admin_menu', 'WP_Safe_Mode_Admin::admin_menu', 9999);
		add_action('admin_init', 'WP_Safe_Mode_Admin::settings_page_actions');
		add_action('admin_enqueue_scripts', 'WP_Safe_Mode_Admin::settings_page_scripts');
		//admin notices for safe mode
		add_action('network_admin_notices', 'WP_Safe_Mode_Admin::activation_warning_notice', 1);
		add_action('admin_notices', 'WP_Safe_Mode_Admin::activation_warning_notice', 1);
		add_action('wp_ajax_wpsf_dismiss_activation_warning_notice', 'WP_Safe_Mode_Admin::dismiss_activation_warning_notice');
	}
	
	public static function admin_menu(){
		$capability = self::get_settings_page_capability();
		if( is_multisite() && !is_network_admin() && !self::is_wp_safe_mode_installed() ) return;
		if( $capability ){
			add_menu_page( __('WP Safe Mode Settings', 'wp-safe-mode'), __('WP Safe Mode', 'wp-safe-mode'), $capability, 'wp-safe-mode', 'WP_Safe_Mode_Admin::settings_page', 'dashicons-sos' );
		}
	}
	
	public static function settings_page_scripts($hook) {
		if ( 'toplevel_page_wp-safe-mode' != $hook ) return false;
		wp_enqueue_style( 'wp-safe-mode-admin', plugin_dir_url(__FILE__) . '/wp-safe-mode-admin.css', array(), WP_SAFE_MODE_VERSION );
		wp_enqueue_style( 'select2', plugin_dir_url(__FILE__) . '/select2/css/select2.min.css', array(), '4.0.6-rc.1' );
		wp_enqueue_script( 'select2', plugin_dir_url(__FILE__) . '/select2/js/select2.min.js', array(), '4.0.6-rc.1' );
	}
	
	public static function settings_page_actions(){
		if( empty($_REQUEST['page']) || $_REQUEST['page'] !== 'wp-safe-mode' ) return false; //stop if not in settings page
		//remove all admin notices so they don't get mixed with Safe Mode notices
		remove_all_actions('admin_notices'); remove_all_actions('network_admin_notices');
		add_action('admin_notices', 'WP_Safe_Mode_Admin::settings_page_admin_notices', 1);
		add_action('network_admin_notices', 'WP_Safe_Mode_Admin::settings_page_admin_notices', 1);
		//remove network filter for plugins, it messes with the select options and filtering network-specific plugins if in safe mode
		remove_filter( 'site_option_active_sitewide_plugins', 'WP_Safe_Mode::disable_multisite_plugins' );
		remove_filter( 'option_active_plugins', 'WP_Safe_Mode::disable_plugins' );
		//install default settings
		WP_Safe_Mode_Admin::install_default_settings();
		//if safe mode loader installation was toggled, either enable/disable the installer
		if( !empty($_REQUEST['wp_safe_mode_toggle']) && wp_verify_nonce($_REQUEST['wp_safe_mode_toggle'], 'wp-safe-mode-toggle') ){
			if( !self::can_user_install_loader() ) return false;
			//determine whether we're enabling/disabling
			$safe_mode_action = !empty($_REQUEST['safe_mode_action']) && $_REQUEST['safe_mode_action'] == 'enable';
			$confirmation_type = $safe_mode_action ? 'install':'remove';
			//proceed with action
			$install_result = WP_Safe_Mode_Admin::toggle_loader_install( $safe_mode_action );
			//redirect with confirmation/error based on action result
			$url = is_multisite() ? network_admin_url('admin.php?page=wp-safe-mode') : admin_url('admin.php?page=wp-safe-mode');
			if( $install_result ) $url = add_query_arg('error', $confirmation_type, $url);
			wp_redirect( $url );
			exit();
		}
		if( !empty($_REQUEST['wp_safe_mode_settings']) && wp_verify_nonce($_REQUEST['wp_safe_mode_settings'], 'wp-safe-mode-settings') ){
			//check permissions to do this and load up current settings
			$capability = self::get_settings_page_capability();
			if( !$capability || !current_user_can($capability) ) return false;
			//get either network settings or single-site setting for saving purposes
			if( is_multisite() && is_network_admin() ){
				$settings = get_site_option('wp_safe_mode_settings', array());
			}else{
				$settings = get_option('wp_safe_mode_settings', array());
			}
			//themes can be switched by any site admin, multisite or not
			$settings['disable_themes'] = !empty($_REQUEST['disable_themes']);
			$settings['default_themes'] = !empty($_REQUEST['default_themes']) ? array($_REQUEST['default_themes']) : array();
			//allow plugin management only to single-site admins or multisite admins with specific permissions
			if( current_user_can('activate_plugins') ){
				$settings['disable_plugins'] = !empty($_REQUEST['disable_plugins']);
				$settings['plugins_to_keep'] = !empty($_REQUEST['plugins_to_keep']) && is_array($_REQUEST['plugins_to_keep']) ? $_REQUEST['plugins_to_keep'] : array();
				$settings['plugins_to_enable'] = !empty($_REQUEST['plugins_to_enable']) && is_array($_REQUEST['plugins_to_enable']) ? $_REQUEST['plugins_to_enable'] : array();
				if( is_multisite() & !is_network_admin() ){
					//sanitize plugins to keep and enable so only non-network-activated plugins are included
					foreach( array('plugins_to_keep', 'plugins_to_enable') as $type_k ){
						foreach( $settings[$type_k] as $plugin_key => $plugin ){
							if( is_plugin_active_for_network($plugin) ) unset($settings[$type_k][$plugin_key]);
						}
					}
					//save network plugins to disable for this specific site, only for network admins
					if( current_user_can('install_plugins') ){
						$settings['network_plugins_to_disable'] = !empty($_REQUEST['network_plugins_to_disable']) && is_array($_REQUEST['network_plugins_to_disable']) ? $_REQUEST['network_plugins_to_disable'] : array();
						foreach( $settings['network_plugins_to_disable'] as $plugin_key => $plugin ){
							if( !is_plugin_active_for_network($plugin) ) unset($settings[$type_k][$plugin_key]);
						}
					}
				}
			}
			//mu plugins management only available to super admins of multisite or admin of single installs
			if( current_user_can('install_plugins') ){
				$settings['load_mu_plugins'] = !empty($_REQUEST['load_mu_plugins']);
			}
			if( is_multisite() && is_network_admin() ){
				//multisite network admin specific settings
				$settings['network_ip_array'] = !empty($_REQUEST['network_ip_array']) ? explode("\r\n", str_replace(' ', '', $_REQUEST['network_ip_array'])) : array();
				$settings['multisite_single_site'] = !empty($_REQUEST['multisite_single_site']);
				$settings['multisite_single_site_admins'] = !empty($_REQUEST['multisite_single_site_admins']);
				// save to network settings and redirect
				update_site_option('wp_safe_mode_settings', $settings);
				wp_redirect( network_admin_url('admin.php?page=wp-safe-mode&settings_updated=1') );
			}else{
				//site-specific settings (multisite or not)
				$settings['site_ip_array'] = !empty($_REQUEST['site_ip_array']) ? explode("\r\n", str_replace(' ', '', $_REQUEST['site_ip_array'])) : array();
				//save site settings and redirect
				update_option('wp_safe_mode_settings', $settings);
				wp_redirect( admin_url('admin.php?page=wp-safe-mode&settings_updated=1') );
			}
		}
	}
	
	public static function settings_page_admin_notices(){
		remove_all_actions('admin_notices');
		$notice = '';
		if( !empty($_GET['settings_updated']) ){
			?>
			<div class="notice notice-success">
				<p><?php esc_html_e('Your settings have been updated.', 'wp-safe-mode'); ?></p>
			</div>
			<?php
		}
		if( is_multisite() && !is_network_admin() ) return;
		//information message about current state of WP Safe Mode installation
		$notices = array( 'error' => array(), 'warning' => array(), 'info' => array() );
		if( class_exists('WP_Safe_Mode') ){
			//Safe Mode is installed, let's confirm where...
			if( !self::is_file_installed() ){
				if( self::is_file_installed_in_mustuse() ){
					$notices['warning'][] = esc_html__('You have installed the WP Safe Mode loader file in the default mu-plugins folder, which contains more plugins that will get loaded even in Safe Mode.', 'wp-safe-mode');
				}else{
					$notice = esc_html__('You have installed the WP Safe Mode loader file outside of a Must-Use plugin folder. We recommend you delete this file and install the loader to a custom Must-Use plugins folder via our installer below.', 'wp-safe-mode');
					$notices['warning'][] = sprintf($notice, '<a href="https://wordpress.org/plugins/wp-safe-mode/#installation">'.esc_html__('installation instructions', 'wp-safe-mode').'</a>');
				}
			}
			//Check Drop-Ins
			foreach( _get_dropins() as $dropin => $dropin_meta ){
				if( file_exists(WP_CONTENT_DIR.'/'.$dropin) && ($dropin_meta[1] === true || (defined($dropin_meta[1]) && constant($dropin_meta[1]))) ){
					$dropins[] = $dropin . ' - ' . $dropin_meta[0];
				}
			}
			if( !empty($dropins) ){
				$notices['warning'][] = esc_html__('Your site is currently loading the following Drop-Ins, which are files that are loaded automatically in your wp-contents folder. These files are loaded before our own loader and therefore cannot be disabled unless you delete or rename the following files:', 'wp-safe-mode');
				$notices['warning'][] = '<p>'.implode('<br>', $dropins).'</p>';
			}
		}else{
			$notices['warning'][] = esc_html__('The WP Safe Mode loader file has not been installed. This file is required in order to enable safe mode, use our installation wizard below to get started!', 'wp-safe-mode');
			$settings = get_site_option('wp_safe_mode_settings', array());
			if( !empty($settings['installation_error']) && $settings['installation_error'] !== true ){
				$install_error = esc_html__('WP Safe Mode loader file installatiaon was cancelled. The following errors were produced during the last attempt to install the WP Safe Mode loader file, which would prevent your site from loading : %s', 'wp-safe-mode');
				$notices['error'][] = sprintf($install_error, '<blockquote>'.$settings['installation_error'].'</blockquote>');
			}
		}
		foreach( $notices as $notice_type => $type_notices){
			if( !empty($type_notices) ){
				echo '<div class="notice notice-'.$notice_type.'"><p>'. implode('</p><p>', $type_notices) .'</p></div>';
			}
		}
	}
	
	public static function settings_page(){
		?>
		<div class="wrap" id="safe-mode-admin">
			<h1><?php is_network_admin() ? esc_html_e('WP Safe Mode Network Settings', 'wp-safe-mode') : esc_html_e('WP Safe Mode Settings', 'wp-safe-mode'); ?></h1>
			<?php if( self::is_wp_safe_mode_installed() ): ?>
			
				<h2><?php esc_html_e('Enable Safe Mode', 'wp-safe-mode'); ?></h2>
				<?php
				//output safe mode buttons/toggles, depending on what page we're on
				$safe_modes = array();
				if( !is_multisite() || is_network_admin() ) $safe_modes[] = WP_Safe_Mode::safe_mode_toggle_user_url();
				if( is_multisite() && !is_network_admin() && (current_user_can('install_plugins') || (WP_Safe_Mode::$multisite_single_site_admins && current_user_can('switch_themes'))) ) $safe_modes[] = WP_Safe_Mode::safe_mode_toggle_user_site_url();
				if( !is_multisite() || !is_network_admin() ) $safe_modes[] = WP_Safe_Mode::safe_mode_toggle_site_url();
				if( is_multisite() && is_network_admin() ) $safe_modes[] = WP_Safe_Mode::safe_mode_toggle_network_url();
				?>
				<table class="safe-mode-dash">
					<?php foreach( $safe_modes as $safe_mode ): ?>
					<tr>
						<td>
							<a href="<?php echo $safe_mode['href']; ?>" class="button-<?php echo empty($safe_mode['status']) ? 'primary':'secondary'; ?> <?php if( $safe_mode['href'] == '#' ) echo 'button-primary-disabled'; ?>">
								<?php echo $safe_mode['title']; ?>
							</a>
						</td>
						<td><?php echo $safe_mode['meta']['title']; ?></td>
					</tr>
					<?php endforeach; ?>
				</table>
			
				<h2><?php esc_html_e('Safe Mode Settings', 'wp-safe-mode'); ?></h2>
				<?php
				//establish some defaults to prevent PHP warnings
				$settings_defaults = array (
					'disable_themes' => false,
					'default_themes' => array( 'twentyseventeen', 'twentysixteen', 'twentyfifteen', 'twentyfourteen', 'twentythirteen', 'twentytwelve' ),
					'disable_plugins' => false,
					'plugins_to_enable' => array('wp-safe-mode/wp-safe-mode.php'),
					'plugins_to_keep' => array(),
					'load_mu_plugins' => false,
					'site_ip_array' => array(),
					'multisite_single_site' => false,
					'multisite_single_site_admins' => false,
					'network_ip_array' => array(),
					'network_plugins_to_disable' => array(),
				);
				//we merge in array into settings, but not recursively for joining arrays since the above serves only to prevent undefined setting keys throwing PHP warnings
				if( is_multisite() && is_network_admin() ){
					$settings = get_site_option('wp_safe_mode_settings', array());
				}else{
					if( is_multisite() ){
						//copy default network option for mu plugins so network admins don't accidentally save a different value in a site
						$network_settings = get_site_option('wp_safe_mode_settings', array());
						$settings_defaults['load_mu_plugins'] = !empty($network_settings['load_mu_plugins']);
					}
					$settings = get_option('wp_safe_mode_settings', array());
				}
				$settings = array_merge( $settings_defaults, $settings );
				//output settings
				?>
				<form action="" method="post" id="safe-mode-settings">
					<?php wp_nonce_field( 'wp-safe-mode-settings', 'wp_safe_mode_settings', false ); ?>
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row">
									<label for="safe-mode-default_theme"><?php esc_html_e('Themes','wp-safe-mode'); ?></label>
								</th>
								<td>
									<fieldset>
										<legend class="screen-reader-text"><span><?php esc_html_e('Switch Themes', 'safe-mode'); ?></span></legend>
										<label>
											<input type="checkbox" name="disable_themes" value="1" <?php if( $settings['disable_themes'] ) echo 'checked="checked"'; ?> class="data-toggle" data-toggle=".safe-mode-theme-options">
											<?php esc_html_e('Switch themes in Safe Mode','wp-safe-mode'); ?>
										</label>
									</fieldset>
									<fieldset class="safe-mode-theme-options">
										<br>
										<label for="safe-mode-default_themes" class="bold"><?php esc_html_e('Safe Mode Theme','wp-safe-mode'); ?></label>
										<legend class="screen-reader-text"><span><?php esc_html_e('Safe Mode Theme', 'safe-mode'); ?></span></legend>
										<?php
											$current_theme = !empty($settings['default_themes']) ? $settings['default_themes'][0]:''; //get the default theme to use in current settings
											$themes = wp_get_themes(); ksort($themes);
										?>
										<select class="widefat" id="safe-mode-default_themes" name="default_themes">
											<?php foreach( $themes as $theme => $WP_Theme ): /* @var WP_Theme $WP_Theme */ ?>
												<option value="<?php echo esc_attr($theme); ?>" <?php if( $theme == $current_theme ) echo 'selected'; ?>>
													<?php echo esc_html($WP_Theme->get('Name')); get_stylesheet_directory(); ?>
												</option>
											<?php endforeach; ?>
										</select>
										<p class="description"><?php esc_html_e('The selected theme will be used in Safe Mode. By default, any installed default Twenty-Something WordPress themes will be used, with the newest theme taking precedence.', 'wp-safe-mode'); ?></p>
									</fieldset>
								</td>
							</tr>
							<?php if( current_user_can('activate_plugins') ) : ?>
							<tr>
								<th scope="row"><?php esc_html_e('Plugins', 'safe-mode'); ?></th>
								<td>
									<fieldset>
										<legend class="screen-reader-text"><span><?php esc_html_e('Disable Plugins', 'safe-mode'); ?></span></legend>
										<label>
											<input type="checkbox" name="disable_plugins" value="1" <?php if( $settings['disable_plugins'] ) echo 'checked="checked"'; ?> class="data-toggle" data-toggle=".safe-mode-plugin-options">
											<?php esc_html_e('Disable and/or enable specific plugins whilst in Safe Mode','wp-safe-mode'); ?>
										</label>
									</fieldset>
									<?php $include_network_plugins = !is_multisite() || is_network_admin(); ?>
									<fieldset class="safe-mode-plugin-options">
										<br>
										<legend class="screen-reader-text"><span><?php esc_html_e('Plugins to Enable','wp-safe-mode'); ?></span></legend>
										<label for="safe-mode-plugins_to_enable" class="bold">
											<?php esc_html_e('Plugins to Enable','wp-safe-mode'); ?>
											<a href="#" class="safe-mode-select-all button-secondary"><?php esc_html_e('Select All', 'wp-safe-mode'); ?></a>
											<a href="#" class="safe-mode-select-none button-secondary"><?php esc_html_e('Select None', 'wp-safe-mode'); ?></a>
										</label>
										<select class="wp-safe-mode-multiselect widefat" id="safe-mode-plugins_to_enable" name="plugins_to_enable[]" multiple>
											<?php foreach( get_plugins() as $plugin => $plugin_data ): ?>
												<?php if( $include_network_plugins || !is_plugin_active_for_network( $plugin ) ) : ?>
													<?php $selected = in_array($plugin, $settings['plugins_to_enable']) ? 'selected':''; ?>
													<option value="<?php echo esc_attr($plugin); ?>" <?php echo $selected; ?>><?php echo esc_html($plugin_data['Name']); ?></option>
												<?php endif; ?>
											<?php endforeach; ?>
										</select>
										<p class="description"><?php esc_html_e('The selected plugins will be activated when in Safe Mode, even if currently deactivated.', 'wp-safe-mode'); ?></p>
									</fieldset>
									<fieldset class="safe-mode-plugin-options">
										<br>
										<legend class="screen-reader-text"><span><?php esc_html_e('Plugins to Keep Active','wp-safe-mode'); ?></span></legend>
										<label for="safe-mode-plugins_to_keep" class="bold">
											<?php esc_html_e('Plugins to Keep Active','wp-safe-mode'); ?>
											<a href="#" class="safe-mode-select-all button-secondary"><?php esc_html_e('Select All', 'wp-safe-mode'); ?></a>
											<a href="#" class="safe-mode-select-none button-secondary"><?php esc_html_e('Select None', 'wp-safe-mode'); ?></a>
										</label>
										<select class="wp-safe-mode-multiselect widefat" id="safe-mode-plugins_to_keep" name="plugins_to_keep[]" multiple>
											<?php foreach( get_plugins() as $plugin => $plugin_data ): ?>
												<?php if( $include_network_plugins || !is_plugin_active_for_network( $plugin ) ) : ?>
													<?php $selected = in_array($plugin, $settings['plugins_to_keep']) ? 'selected':''; ?>
													<option value="<?php echo esc_attr($plugin); ?>" <?php echo $selected; ?>><?php echo esc_html($plugin_data['Name']); ?></option>
												<?php endif; ?>
											<?php endforeach; ?>
										</select>
										<p class="description"><?php esc_html_e('The selected plugins will remain active when in Safe Mode.', 'wp-safe-mode'); ?></p>
									</fieldset>
									<?php if( is_multisite() && !is_network_admin() && current_user_can('install_plugins') ): ?>
									<fieldset class="safe-mode-plugin-options">
										<br>
										<legend class="screen-reader-text"><span><?php esc_html_e('Disable Network Plugins','wp-safe-mode'); ?></span></legend>
										<label for="safe-mode-plugins_to_keep" class="bold">
											<?php esc_html_e('Disable Network Plugins','wp-safe-mode'); ?>
											<a href="#" class="safe-mode-select-all button-secondary"><?php esc_html_e('Select All', 'wp-safe-mode'); ?></a>
											<a href="#" class="safe-mode-select-none button-secondary"><?php esc_html_e('Select None', 'wp-safe-mode'); ?></a>
										</label>
										<select class="wp-safe-mode-multiselect widefat" id="safe-mode-network_plugins_to_disable" name="network_plugins_to_disable[]" multiple>
											<?php foreach( get_plugins() as $plugin => $plugin_data ): ?>
												<?php if( is_plugin_active_for_network( $plugin ) ) : ?>
													<?php $selected = in_array($plugin, $settings['network_plugins_to_disable']) ? 'selected':''; ?>
													<option value="<?php echo esc_attr($plugin); ?>" <?php echo $selected; ?>><?php echo esc_html($plugin_data['Name']); ?></option>
												<?php endif; ?>
											<?php endforeach; ?>
										</select>
										<p class="description"><?php esc_html_e('The selected plugins will be disabled on this site when in Safe Mode, even if network-enabled or enabled via Network Safe Mode.', 'wp-safe-mode'); ?></p>
									</fieldset>
									<?php endif; ?>
									<?php if( current_user_can('install_plugins') ) : ?>
									<br>
									<fieldset>
										<legend class="screen-reader-text"><span><?php esc_html_e('Must-Use Plugins Loading', 'safe-mode'); ?></span></legend>
										<label>
											<input type="checkbox" name="load_mu_plugins" value="1" <?php if( $settings['load_mu_plugins'] ) echo 'checked="checked"'; ?>>
											<?php esc_html_e('Load Must-Use Plugins whilst in Safe Mode','wp-safe-mode'); ?>
										</label>
										<?php if( self::is_file_installed() ): ?>
											<p class="description">
											<?php
												$notice = esc_html__('You are currently loading a custom must-use directory which contains the WP Safe Mode loader file. Your default mu-plugins directory is %1$s which contains %2$d must-use plugins.', 'wp-safe-mode');
												echo sprintf( str_replace('%2$d', '%2$s', $notice), '<code>'.self::$mu_plugins_custom_dir.'</code>', '<code>'.count(WP_Safe_Mode::wp_get_mu_plugins()).'</code>' ); //we replace digit placeholder with string to allow html code
											?>
											</p>
										<?php else: ?>
											<p class="description" style="color:#cc3300">
												<?php esc_html_e('The plugin loader is not installed in a custom Must-Use folder, therefore this setting will not have any impact on safe mode.','wp-safe-mode'); ?>
											</p>
										<?php endif; ?>
									</fieldset>
									<?php endif; ?>
								</td>
							</tr>
							<?php endif; ?>
							<?php if( is_multisite() && is_network_admin() ): ?>
							<tr>
								<th scope="row"><?php esc_html_e('MultiSite Options', 'safe-mode'); ?></th>
								<td>
									<fieldset>
										<legend class="screen-reader-text"><span><?php esc_html_e('Single Site Settings', 'safe-mode'); ?></span></legend>
										<label>
											<input type="checkbox" name="multisite_single_site" value="1" <?php if( $settings['multisite_single_site'] ) echo 'checked="checked"'; ?>>
											<?php esc_html_e('Enable site-specific safe mode settings.','wp-safe-mode'); ?>
										</label>
										<p class="description">
											<?php esc_html_e('When enabled, this settings page will be available on each site dashboard which can override these network-wide settings for that specific site.','wp-safe-mode'); ?>
										</p>
									</fieldset>
									<br>
									<fieldset>
										<legend class="screen-reader-text"><span><?php esc_html_e('Single Site Settings for Admins', 'safe-mode'); ?></span></legend>
										<label>
											<input type="checkbox" name="multisite_single_site_admins" value="1" <?php if( $settings['multisite_single_site_admins'] ) echo 'checked="checked"'; ?>>
											<?php esc_html_e('Allow admins to enable and manage Safe Mode on their own sites.','wp-safe-mode'); ?>
										</label>
										<p class="description">
											<?php esc_html_e('When enabled, single-site admins can enable/disable Safe Mode for their specific site, and manage their settings.','wp-safe-mode'); ?>
											<?php esc_html_e('If admins don\'t have permission to manage plugins on their site, they will not be able to do so in Safe Mode either.','wp-safe-mode'); ?>
										</p>
									</fieldset>
								</td>
							</tr>
							<?php endif; ?>
							<tr>
								<?php
								$safe_mode = !is_multisite() || is_network_admin() ? esc_html__('Network', 'wp-safe-mode') : esc_html__('Site', 'wp-safe-mode');
								$ip_var = is_multisite() && is_network_admin() ? 'network_ip_array' : 'site_ip_array';
								$placeholder = esc_html__('Enter one IP Address per line, your current IP is %s', 'wp-safe-mode');
								?>
								<th scope="row">
									<label for="safe-mode-ip_array"><?php echo esc_html( sprintf(__('%s Safe Mode IP Filter','wp-safe-mode'), $safe_mode) ); ?></label>
								</th>
								<td>
									<textarea id="safe-mode-ip_array" class="widefat" name="<?php echo esc_attr($ip_var); ?>" rows="<?php echo count($settings[$ip_var]) > 3 ? count($settings[$ip_var]) : 5; ?>"
									          placeholder="<?php echo sprintf($placeholder, $_SERVER['REMOTE_ADDR']); ?>"><?php echo implode("\r\n", $settings[$ip_var]); ?></textarea>
									<p class="description">
										<?php echo sprintf(esc_html__('When Safe Mode is active on this %1$s, the following IP addresses will view your site in Safe Mode. If no IP addresses are defined, then all visitors will see the site in Safe Mode.', 'wp-safe-mode'), $safe_mode); ?>
									</p>
									<?php if( !empty($settings[$ip_var]) ): ?>
									<p class="description">
										<?php echo sprintf($placeholder, '<code>'. $_SERVER['REMOTE_ADDR'] .'</code>'); ?>
									</p>
									<?php endif; ?>
								</td>
							</tr>
							<tr>
								<th>&nbsp;</th>
								<td>
									<button type="submit" class="button-primary"><?php esc_html_e('Save Settings', 'wp-safe-mode'); ?></button>
								</td>
							</tr>
						</tbody>
					</table>
					<script type="text/javascript">
						jQuery(document).ready(function($){
							$('.wp-safe-mode-multiselect').select2();
							$(".safe-mode-select-all").click(function( e ){
								e.preventDefault();
								var select = $(this).closest('fieldset').find('.wp-safe-mode-multiselect');
								select.find("option").prop("selected","selected");// Select All Options
								select.trigger("change");
							});
							$(".safe-mode-select-none").click(function( e ){
								e.preventDefault();
								var select = $(this).closest('fieldset').find('.wp-safe-mode-multiselect');
								select.find("option").prop("selected",false);// Select All Options
								select.trigger("change");
							});
							$('#safe-mode-settings .data-toggle').change( function(){
								var el = $(this);
								var toggle_class = el.data('toggle');
								el.prop('checked') ? $('#safe-mode-settings ' + toggle_class).show() : $('#safe-mode-settings ' + toggle_class).hide();
							}).trigger('change');
						});
					</script>
				</form>
			<?php endif; ?>
			
			<?php if( !is_multisite() || is_network_admin() ): ?>
			<h2><?php esc_html_e('Loader Installation Wizard', 'wp-safe-mode'); ?></h2>
			<?php if( self::is_wp_safe_mode_installed() ): ?>
			<div class="safe-mode-notice info">
				<p>
				<?php
				$loader_location = WP_Safe_Mode::where();
				$notice = esc_html__('The WP Safe Mode loader file has been installed at : %s', 'wp-safe-mode');
				echo sprintf($notice, '<br><code>'. $loader_location['__FILE__'] . '</code>');
				?>
				</p>
			</div>
			<?php endif; ?>
			<div id="loader-wizard">
				<p><?php esc_html_e('WP Safe Mode requires a loader file to be installed which allows us to control the loading of WordPress plugins and themes.', 'wp-safe-mode'); ?></p>
				<p><?php esc_html_e('This wizard can attempt to install it for you, or provide you with instructions to install it manually.', 'wp-safe-mode') ?></p>
				<?php
				// Install instructions default text
				if( !self::is_wp_safe_mode_installed() ){
					$mu_folder_installation_instructions = array(
						sprintf(esc_html__('Create a new folder called %1$s in %2$s.', 'wp-safe-mode'), '<code>wp-safe-mode</code>', '<code>'.WP_CONTENT_DIR.'</code>'),
						sprintf(esc_html__('Copy the loader file %s (from this plugin folder) into the newly created folder.', 'wp-safe-mode'), '<code>'.dirname(__FILE__).'/bootstrap/wp-safe-mode-bootstrap.php</code>', '<code>wp-safe-mode-bootstrap.php</code>')
					);
					$wp_config_installation_instructions = array(
						sprintf(esc_html__('Add this line of code below the first line (%1$s) of your wp-config.php file : %2$s', 'wp-safe-mode'), '<code>&lt;?php</code>', '<br><code>'. self::$mu_wp_config .'</code>')
					);
				}else{
					$where = WP_Safe_Mode::where();
					if( self::is_file_installed() ){
						$mu_folder_installation_instructions = array(
							sprintf(esc_html__('Delete the directory %1$s and its contents.', 'wp-safe-mode'), '<code>'. $where['__DIR__'] .'</code>')
						);
					}else{
						$mu_folder_installation_instructions = array(
							sprintf(esc_html__('Delete the file %1$s located in %2$s', 'wp-safe-mode'), '<code>'. basename($where['__FILE__']) .'</code>', '<code>'. $where['__DIR__'] .'</code>')
						);
					}
					$wp_config_installation_instructions = array(
						sprintf(esc_html__('Remove this line of code from your wp-config.php file : %s', 'wp-safe-mode'), '<br><code>'. self::$mu_wp_config .'</code>')
					);
				}
				// Do a check to see if we have the permissions to install it automatically:
				$install_issues = array();
				if( !is_writeable(ABSPATH . '/wp-config.php') ){
					$install_issue = esc_html__('Your wp-config.php file is not writeable. You must make this file writeable or manually configure it : %s', 'wp-safe-mode');
					$install_issues[] = sprintf( $install_issue, '<p><ol><li>'.implode('</li><li>', $wp_config_installation_instructions).'</li></ol></p>');
				}
				if( !is_writeable(WP_CONTENT_DIR) ){
					$install_issue = esc_html__('Your wp-content directory is not writable, you must make this folder writable or manually install the loader file : %s', 'wp-safe-mode');
					$install_issues[] = sprintf( $install_issue, '<p><ul><li>'.implode('</li><li>', $mu_folder_installation_instructions).'</li></ul></p>');
				}
				if( !empty($install_issues) ){
					?>
					<p style="color:#cc0000">
						<?php esc_html_e('The WP Safe Mode loader file cannot be installed automatically:', 'wp-safe-mode'); ?>
					</p>
					<p>
					<ul style="list-style: disc; padding-left:10px; list-style-position: inside;">
						<li><?php echo implode('</li><li>', $install_issues); ?></li>
					</ul>
					</p>
					<?php
				}else{
					?>
					<form action="" method="post">
						<?php
						wp_nonce_field( 'wp-safe-mode-toggle', 'wp_safe_mode_toggle', false );
						if( self::is_wp_safe_mode_installed() ){
							$button_text = __('Uninstall Loader', 'wp-safe-mode');
							$toggle_action = 'disable';
						}else{
							$button_text = __('Install Loader', 'wp-safe-mode');
							$toggle_action = 'enable';
						}
						?>
						<input type="hidden" name="safe_mode_action" value="<?php echo esc_attr($toggle_action); ?>">
						<button type="submit" class="button-<?php echo !self::is_wp_safe_mode_installed() ? 'primary':'secondary'; ?>"><?php echo esc_html($button_text); ?></button>
					</form>
					<?php
				}
				// Show manual install/uninstall options
				if( !self::is_wp_safe_mode_installed() ){
					$manual_install_text = esc_html_x('%s manual installation instructions', 'view or hide', 'wp-safe-mode');
				}else{
					$manual_install_text = esc_html_x('%s manual removal instructions', 'view or hide', 'wp-safe-mode');
				}
				?>
				<p><em><a href="#" id="manual-instructions-toggle" data-toggle="<?php echo sprintf( $manual_install_text, esc_html__('hide', 'wp-safe-mode') ); ?>"><?php echo sprintf( $manual_install_text, esc_html__('view', 'wp-safe-mode') ) ?></a></em></p>
				<div id="manual-instructions" style="display:none;">
					<?php if( !self::is_wp_safe_mode_installed() ): ?>
						<h3><?php esc_html_e('Manual Installation Instructions', 'wp-safe-mode'); ?></h3>
						<p><?php esc_html_e('If for whatever reason you cannot install the loader automatically (or you prefer to install it yourself), here is how you can do it manually :', 'wp-safe-mode'); ?></p>
						<p>
						<ol>
							<li><?php echo implode('</li><li>', $mu_folder_installation_instructions); ?></li>
							<li><?php echo implode('</li><li>', $wp_config_installation_instructions); ?></li>
							<li><?php esc_html_e('Revisit this settings page, for confirmation of installation status.', 'wp-safe-mode'); ?></li>
						</ol>
						<em><?php echo sprintf(esc_html__("You can also alternatively copy the loader file directly to the default mu-plugins folder in %s if you don't have any Must-Use plugins installed.", 'wp-safe-mode'), '<code>'.WP_CONTENT_DIR.'</code>'); ?></em>
						</p>
					<?php else: ?>
						<h3><?php esc_html_e('Manual Removal Instructions', 'wp-safe-mode'); ?></h3>
						<p><?php esc_html_e('If the installation wizard cannot remove the loader file, here is how you can do it manually :', 'wp-safe-mode'); ?></p>
						<ol>
							<li><?php echo implode('</li><li>', $mu_folder_installation_instructions); ?></li>
							<li><?php echo implode('</li><li>', $wp_config_installation_instructions); ?></li>
							<li><?php esc_html_e('Revisit this settings page, for confirmation of installation status.', 'wp-safe-mode'); ?></li>
						</ol>
					<?php endif; ?>
				</div>
				<script type="text/javascript">
					jQuery(document).ready( function($){
						$('#manual-instructions-toggle').click( function( e ){
							e.stopPropagation();
							$('#manual-instructions').toggle();
							var el = $(this);
							var orig_toggle_text = el.text();
							el.text( el.data('toggle') );
							el.data('toggle', orig_toggle_text);
							return false;
						});
					});
				</script>
			</div>
			<?php endif; ?>
		</div>
		<?php
	}
	
	public static function toggle_loader_install( $enable = null, $reinstall = false ){
		$file = WP_Safe_Mode_Admin::toggle_file( $enable, $reinstall );
		$wp_config = WP_Safe_Mode_Admin::toggle_wp_config( $enable );
		$settings = get_site_option('wp_safe_mode_settings', array());
		if( $enable && (!$wp_config || !$file || is_wp_error($wp_config)) ){
			$settings['installation_error'] = is_wp_error($wp_config) ? $wp_config->get_error_message() : true;
		}else{
			unset($settings['installation_error']);
		}
		update_site_option('wp_safe_mode_settings', $settings);
		return $file === true && $wp_config === true;
	}
	
	public static function toggle_file( $enable = null, $reinstall = false ){
		if( $enable === null ){
			$enable = !self::is_wp_config_configured();
		}
		if( $reinstall ){
			$enable = true;
			if( file_exists(self::$mu_plugins_custom_dir.'/wp-safe-mode-bootstrap.php') ) unlink(self::$mu_plugins_custom_dir.'/wp-safe-mode-bootstrap.php');
		}
		if( $enable ){
			if( !file_exists(self::$mu_plugins_custom_dir) ) mkdir(self::$mu_plugins_custom_dir, ( fileperms( ABSPATH ) & 0777 | 0755 ), true);
			return copy( dirname(__FILE__).'/bootstrap/wp-safe-mode-bootstrap.php', self::$mu_plugins_custom_dir.'/wp-safe-mode-bootstrap.php' );
		}else{
			if( file_exists(self::$mu_plugins_custom_dir.'/wp-safe-mode-bootstrap.php') ){
				unlink( self::$mu_plugins_custom_dir.'/wp-safe-mode-bootstrap.php' );
				return @rmdir( self::$mu_plugins_custom_dir );
			}elseif( class_exists('WP_Safe_Mode') ){
				//delete file from e.g. mu-plugins folder, just not within the plugin file itself
				$where = WP_Safe_Mode::where();
				if( $where['__DIR__'] != __DIR__ ){
					return unlink( $where['__FILE__'] );
				}
			}
			return false;
		}
	}
	
	/**
	 * @param null $enable
	 * @return array|bool|int|WP_Error
	 */
	public static function toggle_wp_config($enable = null ){
		$file_path= ABSPATH . '/wp-config.php';
		if( !file_exists($file_path) ){
			//try one folder up
			$file_path = trailingslashit(dirname(ABSPATH)).'wp-config.php';
			if( !file_exists($file_path) ){
				//no check wp-contents folder
				$file_path = trailingslashit(WP_CONTENT_DIR) . 'wp-config.php';
				if( !file_exists($file_path) ){
					//we can't find a wp-config.php file, so let's just give up
					return false;
				}
			}
		}
		$wp_config = file_get_contents($file_path);
		if( strlen($wp_config) == 0 ) return false; //if we have an empty file (symlinks or something like that), give up
		if( $enable === null ){
			$enable = !self::is_wp_config_configured();
		}
		if( $enable ){
			if( !self::is_wp_config_configured() ){
				//before we do anything, we run a test to see if enabling Safe Mode will not produce fatal errors.
				$test_response = wp_remote_get(plugin_dir_url(__FILE__) . 'wp-safe-mode-test.php');
				if( is_wp_error($test_response) ){
					return $test_response;
				}elseif( !preg_match('/WP Safe Mode OK/', $test_response['body']) ){
					return new WP_Error('safe-mode-verification', $test_response['body']);
				}
				$wp_config = str_replace('<?php', '<?php'."\r\n".self::$mu_wp_config, $wp_config);
			}else{
				//no need to install twice
				return true;
			}
		}else{
			if( self::is_wp_config_configured() ){
				$wp_config = str_replace('<?php'."\r\n".self::$mu_wp_config, '<?php', $wp_config);
			}else{
				//no need to remove anything
				return true;
			}
		}
		// Write the contents back to the file
		return file_put_contents($file_path, $wp_config);
	}
	
	/**
	 * Installs default settings to single-site or network if no options exist.
	 */
	public static function install_default_settings(){
		//save default site options
		$settings = get_site_option('wp_safe_mode_settings', array());
		if( empty($settings) ){
			$settings = array (
				'load_mu_plugins' => false,
				'disable_themes' => true,
				'default_themes' => array('twentyseventeen', 'twentysixteen', 'twentyfifteen', 'twentyfourteen', 'twentythirteen', 'twentytwelve'),
				'disable_plugins' => true,
				'plugins_to_keep' => array(
					'events-manager/events-manager.php',
					'events-manager-pro/events-manager-pro.php',
					'events-manager-io/events-manager-io.php',
					'wp-fullcalendar/wp-fullcalendar.php',
					'login-with-ajax/login-with-ajax.php',
					'meta-tag-manager/meta-tag-manager.php',
					'heartbeat-control/heartbeat-control.php',
					'query-monitor/query-monitor.php',
				),
				'plugins_to_enable' => array('wp-safe-mode/wp-safe-mode.php'),
			);
			update_site_option('wp_safe_mode_settings', $settings);
		}
	}
	
	public static function is_wp_safe_mode_installed(){
		return class_exists('WP_Safe_Mode');
	}
	
	public static function is_wp_config_configured(){
		return defined('WPMU_PLUGIN_DIR') && WPMU_PLUGIN_DIR == self::$mu_plugins_custom_dir;
	}
	
	public static function is_file_installed(){
		return file_exists(self::$mu_plugins_custom_dir.'/wp-safe-mode-bootstrap.php') && class_exists('WP_Safe_Mode');
	}
	
	public static function is_file_installed_in_mustuse(){
		if( !self::is_file_installed() && function_exists('wp_safe_mode_loader_location') && class_exists('WP_Safe_Mode') ){
			$where = WP_Safe_Mode::where();
			if( WPMU_PLUGIN_DIR == basename($where['__DIR__']) || WP_CONTENT_DIR . '/mu-plugins' == $where['__DIR__'] ){
				return true;
			}
		}
		return false;
	}
	
	public static function is_file_installed_elsewhere(){
		return !self::is_file_installed() && !self::is_file_installed_in_mustuse() && class_exists('WP_Safe_Mode');
	}
	
	public static function can_user_install_loader(){
		return is_multisite() && current_user_can('install_plugins') || current_user_can('activate_plugins');
	}
	
	public static function get_settings_page_capability(){
		$capability = 'install_plugins';
		if( is_multisite() && !is_network_admin() && self::is_wp_safe_mode_installed() ){
			// we ensure that single-site admin menu is enabled and single-site admins have permission to see it
			if( WP_Safe_Mode::$multisite_single_site ){
				//display safe mode settings to super admins, and single-site admins if settings allow it
				$capability = WP_Safe_Mode::$multisite_single_site_admins ? 'switch_themes' : 'install_plugins';
			}else{
				return false; //don't load a Safe Mode admin menu on single-site
			}
		}
		return $capability;
	}
	
	public static function activation_warning_notice(){
		$settings = get_site_option('wp_safe_mode_settings', array());
		if( is_multisite() ){
			if( !empty($settings['installation_error']) && current_user_can('install_plugins') ){
				$error_url = '<a href="'. network_admin_url('admin.php?page=wp-safe-mode') .'">'. esc_html__('WP Safe Mode Network Settings', 'wp-safe-mode'). '</a>';
			}
		}elseif( !empty($settings['installation_error']) && current_user_can('install_plugins') ){
			$error_url = '<a href="'. admin_url('admin.php?page=wp-safe-mode') .'">'. esc_html__('WP Safe Mode Settings', 'wp-safe-mode'). '</a>';
		}
		if( !empty($error_url) ){
			$error = esc_html__('WP Safe Mode could not automatically install the required loader file. Please visit the %s page for further instructions.', 'wp-safe-mode');
			?>
			<div class="wpsm-notice notice notice-error is-dismissible"><p><?php echo sprintf($error, $error_url) ?></p></div>
			<script type="text/javascript">
				jQuery(document).ready( function($){
					$('.wpsm-notice').on('click', 'button.notice-dismiss', function(e){
						$.get('<?php echo admin_url('admin-ajax.php'); ?>', {'action' : 'wpsf_dismiss_activation_warning_notice' });
					});
				});
			</script>
			<?php
		}
	}
	
	public static function dismiss_activation_warning_notice(){
		$settings = get_site_option('wp_safe_mode_settings', array());
		unset($settings['installation_error']);
		update_site_option('wp_safe_mode_settings', $settings);
		echo 'Warning Dismissed!';
		exit();
	}
}
WP_Safe_Mode_Admin::init();