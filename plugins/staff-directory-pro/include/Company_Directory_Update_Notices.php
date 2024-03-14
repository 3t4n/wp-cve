<?php
include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); // so we have access to is_plugin_active()

class Company_Directory_Update_Notices
{
	function __construct()
	{
		$this->add_hooks();
	}
	
	function add_hooks()
	{
		add_action( 'admin_enqueue_scripts', array($this, 'enqueue_inline_script_for_notices') );
		add_action( 'admin_notices', array($this, 'pro_plugin_upgrade_notice') );
		add_action( 'wp_ajax_company_directory_dismiss_pro_plugin_notice', array($this, 'dismiss_pro_plugin_upgrade_notice') );
	}
	
	/**
	 * If the user has an active key but doesn't have the Pro plugin, show them
	 * a notice to this effect.
	 */
	function pro_plugin_upgrade_notice()
	{		
		// Only show notices to pro users without the Pro plugin
		// who also have an email set (suggesting a old user)
		$pro_plugin_path = "company-directory-pro/company-directory-pro.php";
		$registered_email = $this->get_registered_email();
		
		if ( empty($registered_email)
			 || !$this->has_valid_api_key()
			 || is_plugin_active($pro_plugin_path) 
		   ) {
			return;
		}
		
		// Quit if the user has already dismissed the notice, unless this is an 
		// Company Directory settings page, in which case we always show the notice
		$company_directory_hide_pro_plugin_notice = get_option('company_directory_hide_pro_plugin_notice');		
		if ( !$this->is_company_directory_page() && !empty( $company_directory_hide_pro_plugin_notice ) ) {
			return;
		}
		
		// don't show the notice on the Install Pro Plugin page
		$hide_on_pages =  array(
			'company-directory-install-plugins',
			'company_directory_pro_error_page',
			'company_directory_pro_privacy_notice',
		);
		$is_plugin_install_page = !empty( $_GET['page'] ) && in_array($_GET['page'], $hide_on_pages);
		if ( $is_plugin_install_page ) {
			return;
		}
		
		// render the message
		$div_style = "border: 4px solid #46b450; padding: 20px 38px 10px 20px;";
		$heading_style = "color: green; font-size: 20px; font-family: -apple-system,BlinkMacSystemFont,&quot;Segoe UI&quot;,Roboto,Oxygen-Sans,Ubuntu,Cantarell,&quot;Helvetica Neue&quot;,sans-serif; font-weight: 600";
		$p_style = "font-size: 16px; font-weight: normal; margin-bottom: 1em;";
		$button_style = "font-size: 16px; height: 52px; line-height: 50px;";
		$package_url = get_option('_company_directory_upgrade_package_url', '');
		$next_url = !empty($package_url)
					? admin_url('admin.php?page=company-directory-install-plugins')
					: admin_url('admin.php?page=company_directory_pro_privacy_notice');
		
		$message = sprintf( '<h3 style="%s">%s</h3>', 
							$heading_style,
							'Company Directory Pro - ' . __('Update Required')							
						  );
		$message .= sprintf( '<p style="%s">%s</p>', $p_style, __('In order to keep using all the great features of Company Directory Pro, you\'ll need to install the Company Directory Pro plugin. Without this update, Pro features such as the Staff Table, Staff Grid, Import&nbsp;&amp;&nbsp;Export wizard, and Advanced Search will temporarily stop working.') );
		$message .= sprintf( '<p style="%s">%s</p>', $p_style, __('Installing Company Directory Pro only takes a moment. None of your data or settings will be affected.') );
		$message .= sprintf( '<p style="%s">%s</p>', $p_style,  __('Click the button below to begin.') );
		$message .= sprintf( '<p style="%s"><a class="button button-primary button-hero" style="%s" href="%s">%s</a></p>',
							 $p_style,
							 $button_style,
							 $next_url,
							 __('Install Company Directory Pro')
						   );		
		$div_id = 'company_directory_pro_plugin_notice';
		printf ( '<div id="%s" style="%s" class="notice notice-%s is-dismissible company_directory_install_pro_plugin_notice">%s</div>',
				 $div_id,
				 $div_style,
				'success',
				 $message );
	}
	
	/**
	 * Adds an inline script to watch for clicks on the "Pro plugin required" 
	 * notice's dismiss button
	 */
	function enqueue_inline_script_for_notices($hook = '')
	{
		$js = '		
		jQuery(function () {
			jQuery("#company_directory_pro_plugin_notice").on("click", ".notice-dismiss", function () {
				jQuery.post(
					ajaxurl, 
					{
						action: "company_directory_dismiss_pro_plugin_notice"
					}
				);
			});
		});		
		';
		if ( !wp_script_is( 'jquery', 'done' ) ) {
			wp_enqueue_script( 'jquery' );
		}
		// note: attach to jquery-core, not jquery, or it won't fire
		wp_add_inline_script('jquery-core', $js);		
	}
	
	/**
	 * AJAX hook - records dismissal of the "Pro plugin required" notice.
	 */
	function dismiss_pro_plugin_upgrade_notice()
	{
		update_option('company_directory_hide_pro_plugin_notice', 1);
		wp_die('OK');
	}
	
	// check the reg key, and set $this->isPro to true/false reflecting whether the Pro version has been registered
	function has_valid_api_key()
	{
		$options = get_option( 'sd_options' );	
		if ( isset($options['api_key']) && 
			 isset($options['registration_email']) 
		   ) {	
				// check the key
				$keychecker = new S_D_KeyChecker();
				$correct_key = $keychecker->computeKeyEJ($options['registration_email']);
				if (strcmp($options['api_key'], $correct_key) == 0) {
					return true;
				} else if(isset($options['registration_url']) && isset($options['registration_email'])) {//only check if its an old key if the relevant fields are set
					//maybe its an old style of key
					$correct_key = $keychecker->computeKey($options['registration_url'], $options['registration_email']);
					if (strcmp($options['api_key'], $correct_key) == 0) {
						return true;
					} else {
						return false;
					}
				}
		}
		return false;
	}
	
	function get_registered_email()
	{
		$options = get_option( 'sd_options' );	
		return !empty($options) && !empty($options['registration_email']) 
			   ? $options['registration_email']
			   : '';
	}
	
	function get_api_key()
	{
		$options = get_option( 'sd_options' );	
		return !empty($options) && !empty($options['api_key']) 
			   ? $options['api_key']
			   : '';
	}
	
	function is_company_directory_page()
	{
		if ( empty($_GET['page']) ) {
			return false;
		}
		
		return (strpos($_GET['page'], 'company-directory') !== false) 
			   || (strpos($_GET['page'], 'staff_dir') !== false);
	}
}