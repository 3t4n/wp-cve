<?php

if ( ! class_exists( 'ContentAd__Includes__Admin__Admin' ) ) {

	class ContentAd__Includes__Admin__Admin {

		function __construct() {
			global $wp_version;
			if( version_compare( $wp_version, '3.1', '<' ) ) {
				ContentAd__Includes__Admin__WP3_Menu_Fix::on_load();
			}
			add_action( 'admin_menu', array( $this, 'admin_menu' ) );
			add_action( 'admin_init', array( $this, 'admin_init' ) );
			add_action( 'admin_notices', array( $this, 'admin_notices' ) );
		}

		function admin_menu() {
			global $contentad_settings_page, $wp_version;
			if( version_compare( $wp_version, '3.1', '>=' ) ) {
				add_menu_page(
					$page_title = __( 'Content.ad', 'contentad' ),
					$menu_title = __( 'Content.ad', 'contentad' ),
					$capability = 'manage_options',
					$menu_slug = CONTENTAD_SLUG ,
					$callback = array( $this, 'menu_page_settings' ),
					$icon_url = plugins_url( 'images/' , CONTENTAD_FILE ) . 'ca_icon.png',
					$position = 56
				);
				$contentad_settings_page = add_submenu_page(
					$parent_slug = CONTENTAD_SLUG,
					$page_title = __( 'Settings', 'contentad' ),
					$menu_title = __( 'Settings', 'contentad' ) ,
					$capability = 'manage_options',
					$menu_slug = CONTENTAD_SLUG . '-settings', array( $this, 'menu_page_settings' )
				);
			} else {
				$contentad_settings_page = add_submenu_page(
					$parent_slug = 'edit.php?post_type=content_ad_widget',
					$page_title = __( 'Settings', 'contentad' ),
					$menu_title = __( 'Settings', 'contentad' ) ,
					$capability = 'manage_options',
					$menu_slug = CONTENTAD_SLUG . '-settings', array( $this, 'menu_page_settings' )
				);
			}
			add_action( 'load-'.$contentad_settings_page, array( $this, 'load' ) );
			add_action( 'load-edit.php', array( $this, 'load' ) );
		}

		function menu_page_settings(){
			ContentAd__Includes__API::validate_installation_key();
			settings_errors( 'contentad_settings' ); ?>
			<div class="wrap settings-container">
				<div class="menu-masthead">
					<div class="icon32 icon32-contentad-settings" id="icon-broadpsring-ca">
						<?php echo '<a href="https://www.content.ad/" target="_blank"><img src="'.plugins_url( 'images/', CONTENTAD_FILE ).'ca_logo.png" /></a>' ?>
					</div>
					<h2 class="menu-header"><?php _e('Settings','contentad'); ?></h2>
				</div>
				<form name="contentad_settings" action="<?php echo admin_url('options.php'); ?>" method="post">
					<?php settings_fields( 'contentad_settings' ); ?>
					<?php do_settings_sections(CONTENTAD_SLUG); ?><br />
					<span class="contentad_instructions_help">Need help? Find more information at <a href="http://help.content.ad/" target="_blank">help.content.ad</a> or get help from our support team on the <a href="https://wordpress.org/support/plugin/contentad/" target="_blank">plugin forum</a>.<br />If you like the plugin, <a href="https://wordpress.org/support/plugin/contentad/reviews/" target="_blank">help us out by leaving a positive review</a>.</span>
				</form>
			</div><?php
		}

		function load() {
			global $contentad_settings_page, $current_screen;
			$screen = $current_screen;
			if ( ( $screen->id == $contentad_settings_page ) || ( 'edit-content_ad_widget' == $screen->id ) ) {
				if( $screen->id == $contentad_settings_page ) {
					wp_enqueue_script( 'contentad.settings.js', plugins_url( 'js/', CONTENTAD_FILE ).'settings.js', array('jquery','thickbox'), CONTENTAD_VERSION );
				}
				if( 'edit-content_ad_widget' == $screen->id ) {
					$add_widget_query = http_build_query( array(
						'installKey' => ContentAd__Includes__API::get_installation_key(),
						'aid' => ContentAd__Includes__API::get_api_key(),
						'new' => 1,
						'TB_iframe' => 'true',
					) );
					$add_widget_url = CONTENTAD_REMOTE_URL . "Publisher/Widgets/Add?{$add_widget_query}";
					$contentad_query = http_build_query( array(
						'installKey' => ContentAd__Includes__API::get_installation_key(),
						'aid' => ContentAd__Includes__API::get_api_key(),
						'TB_iframe' => 'true',
					) );
					$report_url = CONTENTAD_REMOTE_URL . "Publisher/AggregateReport?{$contentad_query}";
					$settings_url = CONTENTAD_REMOTE_URL . "MyAccount?{$contentad_query}";
					$instructions_url = CONTENTAD_REMOTE_URL . "Publisher/Widgets/WordpressInstallation?instructions=true&{$contentad_query}";
					$installation_code_query = http_build_query( array(
						'installKey' => ContentAd__Includes__API::get_installation_key(),
						'aid' => ContentAd__Includes__API::get_api_key(),
					) );
					$installation_code_url = CONTENTAD_REMOTE_URL . "Publisher/Widgets/InstallationCode?{$installation_code_query}";
					wp_enqueue_script( 'contentad.admin.js', plugins_url( 'js/', CONTENTAD_FILE ).'admin.js', array('jquery','thickbox','suggest'), CONTENTAD_VERSION );
					// Register hooks
					wp_localize_script( 'contentad.admin.js', 'ContentAd', array(
						'action' => 'edit_contentad_widget',
						'nonce' => wp_create_nonce( 'edit_contentad_widget' ),
						'pauseLinkTranslation' => __( 'Pause this widget', 'contentad' ),
						'activateLinkTranslation' => __( 'Activate this widget', 'contentad' ),
						'pauseButtonTranslation' => __( 'Paused', 'contentad' ),
						'activateButtonTranslation' => __( 'Active', 'contentad' ),
						'newWidgetCall' => $add_widget_url,
						'reportName' => __('View Statistics', 'contentad' ),
						'reportCall' => $report_url,
						'settingsLinkText' => __('Account Settings', 'contentad' ),
						'settingsCall' => $settings_url,
						'instructionsnCall' => $instructions_url,
						'installationCodeCall' => $installation_code_url,
						'pluginsUrl' => plugins_url( '', CONTENTAD_FILE ),
						'tags' => get_tags('post_tag', array('hide_empty' => false))
					) );
				}
				wp_enqueue_style( 'contentad.admin.css', plugins_url( 'css/', CONTENTAD_FILE ).'admin.css', array('thickbox'), CONTENTAD_VERSION );
			}
			if ( $screen->id == 'edit-content_ad_widget' ) {
				ContentAd__Includes__Init::get_widgets();
			}
		}

		function admin_init(){
			register_setting( 'contentad_settings', 'contentad_api_key', array( $this, 'sanitize_api_key' ) );
			add_settings_section( 'contentad_api', __('', 'contentad'), array( $this, 'settings_section_api' ), CONTENTAD_SLUG );
			add_settings_field( 'contentad_account_creation', '<span class="contentad_step">1</span>', array( $this, 'settings_account_creation' ), CONTENTAD_SLUG , 'contentad_api' );
			add_settings_field( 'contentad_account_connection', '<span class="contentad_step">2</span>', array( $this, 'settings_account_connection' ), CONTENTAD_SLUG , 'contentad_api' );
			add_settings_field( 'contentad_widgets_create', '<span class="contentad_step">3</span>', array( $this, 'settings_widget_create' ), CONTENTAD_SLUG , 'contentad_api' );
		}

		function sanitize_api_key( $dirty ) {
			$clean = preg_replace( '/[^a-z0-9-]/i', '', $dirty );
			return $clean;
		}

		function settings_section_api() {}

		function settings_account_creation() {
			$api_key = ContentAd__Includes__API::get_api_key();
			$is_valid = ContentAd__Includes__API::validate_api_key( $api_key );

			if( !$is_valid ) {

			$register_query = http_build_query( array(
				'email' => get_bloginfo('admin_email'),
				'domain' => home_url(),
				'cb' => CONTENTAD_URL . '/includes/tbclose.php',
				'installKey' => ContentAd__Includes__API::get_installation_key(),
				'TB_iframe' => 'true',
			) );
			$register_url = CONTENTAD_REMOTE_URL . 'Register?' . $register_query;
				echo '<span class="contentad_instructions_h2">Do you have a Content.ad account? If not, <a href="' . $register_url . '" class="thickbox">create one</a> - it\'s <strong>100% free</strong>.</span>';
			} else {
			$myaccount_query = http_build_query( array(
				'installKey' => ContentAd__Includes__API::get_installation_key(),
				'aid' => ContentAd__Includes__API::get_api_key(),
				'TB_iframe' => 'true',
			) );
			$myaccount_url = CONTENTAD_REMOTE_URL . 'MyAccount?' . $myaccount_query;
				echo '<span class="contentad_instructions_h2">You have a <a href="' . $myaccount_url . '" class="thickbox">Content.ad</a> account. </span><span class="cad-success-icon"></span></span>';
			}

		}

		function settings_account_connection() {
			$api_key = ContentAd__Includes__API::get_api_key();
			$is_valid = ContentAd__Includes__API::validate_api_key( $api_key );

			$myaccount_query = http_build_query( array(
				'installKey' => ContentAd__Includes__API::get_installation_key(),
				'aid' => ContentAd__Includes__API::get_api_key(),
				'TB_iframe' => 'true',
			) );
			$myaccount_url = CONTENTAD_REMOTE_URL . 'MyAccount?' . $myaccount_query;

			?>

			<span class="contentad_instructions_h2">
				<?php if( !$is_valid ){
					echo 'Connect your site to Content.ad. <a class="thickbox" href="' . $myaccount_url . '">Find your WordPress Key here</a>';
				} else {
					echo 'Your site is connected to Content.ad. <a class="thickbox" href="' . $myaccount_url . '">See your WordPress Key</a>';
				}?>
            </span>

			<p>
				<input id="contentad_api_key" name="contentad_api_key" size="40" type="text" value="<?php echo esc_attr( $api_key ); ?>" />
				<?php if( $api_key ): ?>
					<span class="<?php echo $is_valid ? 'cad-success-icon': 'cad-error-icon'; ?>"></span>
				<?php endif; ?>
			</p>
            <p><input id="verify_api_key" name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e( 'Remember and Verify Wordpress key', 'contentad' ); ?>" /></p><?php
		}

		function settings_widget_create() {
			if( count(ContentAd__Includes__API::get_ad_units()) >  0 ) { ?>
				<span class="contentad_instructions_h2">Manage your <a href="edit.php?post_type=content_ad_widget">Content.ad widgets</a></span> <span class="cad-success-icon"></span>
			<?php } else {
				$add_widget_query = http_build_query( array(
					'installKey' => ContentAd__Includes__API::get_installation_key(),
					'aid' => ContentAd__Includes__API::get_api_key(),
					'TB_iframe' => 'true',
				) );
				$add_widget_url = CONTENTAD_REMOTE_URL . "Publisher/Widgets/Add?{$add_widget_query}"; ?>
				<span class="contentad_instructions_h2">Create your first <a href="<?php echo $add_widget_url; ?>" class="thickbox">Content.ad widget</a></span>
			<?php }
		}

		function admin_notices() {
			global $contentad_settings_page, $current_screen;
			$api_key = ContentAd__Includes__API::get_api_key();
			if ( ! $api_key ) {
				$screen = $current_screen;
				if ( current_user_can( 'manage_options' ) && ! ( $screen->id == $contentad_settings_page ) ) {
					echo '<div class="error"><p>Your Content.ad plugin is almost ready. Please <a href="admin.php?page=contentad-settings">register it</a> to get started.</p></div>';
				}
			}
		}

	}

}
