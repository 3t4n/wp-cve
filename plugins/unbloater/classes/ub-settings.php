<?php

Namespace Unbloater;

defined( 'ABSPATH' ) || die();

class Unbloater_Settings {
	
	/**
	 * Plugin options
	 */
	public $options;
	
	/**
	 * Class constructor
	 */
	public function __construct() {
		$this->options = Unbloater_Helper::is_ub_active_for_network() ? get_network_option( null, 'unbloater_settings' ) : get_option( 'unbloater_settings' );
		add_action( 'admin_init', array( $this, 'init_settings' ) );	
	}
	
	/**
	 * Render a single settings field checkbox
	 */
	private function render_settings_field_checkbox( $name, $label, $description = '', $overwritten = false ) {
		$disabled = false;
		$checked = ( isset( $this->options[$name] ) && '1' === $this->options[$name] ) ? true : false;
		if( $overwritten && !$checked )
			$disabled = $checked = true;
		if( $disabled )
			echo '<p class="setting-disabled-message"><strong>' . sprintf( __( 'This setting is overwritten, either by another option or by a constant set in the %1$swp-config.php%2$s file.', 'unbloater' ), '<code>', '</code>' ) . '</strong></p>';
		?>
		<input type="hidden" name="unbloater_settings[<?php echo $name; ?>]" value="0">
		<input type="checkbox" name="unbloater_settings[<?php echo $name; ?>]" id="unbloater_settings[<?php echo $name; ?>]" value="1" <?php if( $checked ) echo 'checked="checked"'; ?> <?php if( $disabled ) echo 'disabled="disabled"'; ?>>
		<label for="unbloater_settings[<?php echo $name; ?>]" <?php if( $disabled ) echo 'class="setting-disabled"'; ?>><?php echo $label; ?></label>
		<?php
		if( !empty( $description ) )
			echo '<p class="description' . ( $disabled ? ' setting-disabled' : '' ) . '">' . $description . '</p>';
	}
	
	/**
	 * Initialize the settings sections and fields
	 */
	public function init_settings() {
		
		register_setting( 'unbloater', 'unbloater_settings' );
		
		/******************************************************************
		********* CORE BACKEND SECTION ************************************
		******************************************************************/
		
		add_settings_section(
			'unbloater_section_core_backend',
			__( 'Core (Backend)', 'unbloater' ),
			array( $this, 'cb_settings_section_core_backend' ),
			'unbloater'
		);
		
		add_settings_field(
			'remove_update_available_notice',
			__( 'Update Notice', 'unbloater' ),
			array( $this, 'cb_setting_remove_update_available_notice' ),
			'unbloater',
			'unbloater_section_core_backend'
		);
		
		add_settings_field(
			'disable_auto_updates_core',
			__( 'Auto-Updates', 'unbloater' ),
			array( $this, 'cb_setting_disable_auto_updates_core' ),
			'unbloater',
			'unbloater_section_core_backend'
		);
		
		add_settings_field(
			'disable_auto_updates_plugins',
			__( 'Plugin Auto-Updates', 'unbloater' ),
			array( $this, 'cb_setting_disable_auto_updates_plugins' ),
			'unbloater',
			'unbloater_section_core_backend'
		);

		add_settings_field(
			'disable_auto_updates_themes',
			__( 'Theme Auto-Updates', 'unbloater' ),
			array( $this, 'cb_setting_disable_auto_updates_themes' ),
			'unbloater',
			'unbloater_section_core_backend'
		);

		add_settings_field(
			'disable_core_upgrade_bundled_items',
			__( 'Core Upgrade Bundled Items', 'unbloater' ),
			array( $this, 'cb_setting_disable_core_upgrade_bundled_items' ),
			'unbloater',
			'unbloater_section_core_backend'
		);
		
		add_settings_field(
			'disallow_file_edit',
			__( 'Code Editors', 'unbloater' ),
			array( $this, 'cb_setting_disallow_file_edit' ),
			'unbloater',
			'unbloater_section_core_backend'
		);
		
		add_settings_field(
			'limit_post_revisions',
			__( 'Post Revisions', 'unbloater' ),
			array( $this, 'cb_setting_limit_post_revisions' ),
			'unbloater',
			'unbloater_section_core_backend'
		);
		
		add_settings_field(
			'limit_empty_trash_period',
			__( 'Empty Trash', 'unbloater' ),
			array( $this, 'cb_setting_limit_empty_trash_period' ),
			'unbloater',
			'unbloater_section_core_backend'
		);
		
		if( Unbloater_Helper::is_wp_version_at_least( '5.6' ) || Unbloater_Helper::is_plugin_active( 'application-passwords/application-passwords.php' ) ) {
			
			add_settings_field(
				'limit_application_password_creation',
				__( 'Application Passwords', 'unbloater' ),
				array( $this, 'cb_setting_limit_application_password_creation' ),
				'unbloater',
				'unbloater_section_core_backend'
			);
			
			add_settings_field(
				'disable_application_passwords',
				__( 'Application Passwords', 'unbloater' ),
				array( $this, 'cb_setting_disable_application_passwords' ),
				'unbloater',
				'unbloater_section_core_backend'
			);
			
		}
		
		add_settings_field(
			'disable_admin_email_confirmation',
			__( 'Admin Email Confirmation', 'unbloater' ),
			array( $this, 'cb_setting_disable_admin_email_confirmation' ),
			'unbloater',
			'unbloater_section_core_backend'
		);
		
		add_settings_field(
			'disable_xmlrpc',
			__( 'XML-RPC', 'unbloater' ),
			array( $this, 'cb_setting_disable_xmlrpc' ),
			'unbloater',
			'unbloater_section_core_backend'
		);
		
		add_settings_field(
			'remove_admin_bar_wordpress_item',
			__( 'Admin Bar \'W\' Item', 'unbloater' ),
			array( $this, 'cb_setting_remove_admin_bar_wordpress_item' ),
			'unbloater',
			'unbloater_section_core_backend'
		);
		
		add_settings_field(
			'remove_admin_footer',
			__( 'Admin Footer', 'unbloater' ),
			array( $this, 'cb_setting_remove_admin_footer' ),
			'unbloater',
			'unbloater_section_core_backend'
		);
		
		/******************************************************************
		********* CORE FRONTEND SECTION ***********************************
		******************************************************************/
		
		add_settings_section(
			'unbloater_section_core_frontend',
			__( 'Core (Frontend)', 'unbloater' ),
			array( $this, 'cb_settings_section_core_frontend' ),
			'unbloater'
		);
		
		add_settings_field(
			'remove_generator_tag',
			__( 'Generator Tag', 'unbloater' ),
			array( $this, 'cb_setting_remove_generator_tag' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'remove_script_style_version_parameter',
			__( 'Script/Style Versions', 'unbloater' ),
			array( $this, 'cb_setting_remove_script_style_version_parameter' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'remove_wlw_manifest_link',
			__( 'WLW Manifest', 'unbloater' ),
			array( $this, 'cb_setting_remove_wlw_manifest_link' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'remove_rsd_link',
			__( 'RSD Link', 'unbloater' ),
			array( $this, 'cb_setting_remove_rsd_link' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'remove_shortlink',
			__( 'Shortlink', 'unbloater' ),
			array( $this, 'cb_setting_remove_shortlink' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'remove_feed_generator_tag',
			__( 'Feed Generator', 'unbloater' ),
			array( $this, 'cb_setting_remove_feed_generator_tag' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'remove_feed_links',
			__( 'Feed Links', 'unbloater' ),
			array( $this, 'cb_setting_remove_feed_links' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'disable_feeds',
			__( 'Feeds', 'unbloater' ),
			array( $this, 'cb_setting_disable_feeds' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'remove_wporg_dns_prefetch',
			__( 'DNS Prefetch', 'unbloater' ),
			array( $this, 'cb_setting_remove_wporg_dns_prefetch' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'remove_jquery_migrate',
			__( 'jQuery Migrate', 'unbloater' ),
			array( $this, 'cb_setting_remove_jquery_migrate' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'disable_emojis',
			__( 'Emojis', 'unbloater' ),
			array( $this, 'cb_setting_disable_emojis' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'optimize_comment_js_loading',
			__( 'Comment Script', 'unbloater' ),
			array( $this, 'cb_setting_optimize_comment_js_loading' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'remove_recent_comments_style',
			__( 'Recent Comments Style', 'unbloater' ),
			array( $this, 'cb_setting_remove_recent_comments_style' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'disable_comment_hyperlinks',
			__( 'Comment Hyperlinks', 'unbloater' ),
			array( $this, 'cb_setting_disable_comment_hyperlinks' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'reduce_heartbeat_interval',
			__( 'Heartbeat', 'unbloater' ),
			array( $this, 'cb_setting_reduce_heartbeat_interval' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'normalize_favicon',
			__( 'Favicon', 'unbloater' ),
			array( $this, 'cb_setting_normalize_favicon' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'normalize_login_logo_url',
			__( 'Login Logo URL', 'unbloater' ),
			array( $this, 'cb_setting_normalize_login_logo_url' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		add_settings_field(
			'normalize_login_logo_title',
			__( 'Login Logo Title', 'unbloater' ),
			array( $this, 'cb_setting_normalize_login_logo_title' ),
			'unbloater',
			'unbloater_section_core_frontend'
		);
		
		if( Unbloater_Helper::is_wp_version_at_least( '5.9' ) ) {
			add_settings_field(
				'disable_login_language_dropdown',
				__( 'Login Language', 'unbloater' ),
				array( $this, 'cb_setting_disable_login_language_dropdown' ),
				'unbloater',
				'unbloater_section_core_frontend'
			);
		}
		
		/******************************************************************
		********* BLOCK EDITOR / GUTENBERG SECTION ************************
		******************************************************************/
		
		if( ( Unbloater_Helper::is_wp_version_at_least( '5.0' ) || Unbloater_Helper::is_plugin_active( 'gutenberg/gutenberg.php' ) ) && ! Unbloater_Helper::is_plugin_active( 'classic-editor/classic-editor.php' ) ) {

			add_settings_section(
				'unbloater_section_block_editor',
				__( 'Block Editor', 'unbloater' ),
				array( $this, 'cb_settings_section_block_editor' ),
				'unbloater'
			);
			
			add_settings_field(
				'block_editor_deactivate_block_directory',
				__( 'Block Directory', 'unbloater' ),
				array( $this, 'cb_setting_block_editor_deactivate_block_directory' ),
				'unbloater',
				'unbloater_section_block_editor'
			);
			
			add_settings_field(
				'block_editor_deactivate_core_block_patterns',
				__( 'Core Block Patterns', 'unbloater' ),
				array( $this, 'cb_setting_block_editor_deactivate_core_block_patterns' ),
				'unbloater',
				'unbloater_section_block_editor'
			);
			
			add_settings_field(
				'block_editor_deactivate_template_editor',
				__( 'Template Editor', 'unbloater' ),
				array( $this, 'cb_setting_block_editor_deactivate_template_editor' ),
				'unbloater',
				'unbloater_section_block_editor'
			);
			
			add_settings_field(
				'block_editor_autoclose_welcome_guide',
				__( 'Welcome Guide', 'unbloater' ),
				array( $this, 'cb_setting_block_editor_autoclose_welcome_guide' ),
				'unbloater',
				'unbloater_section_block_editor'
			);
			
			add_settings_field(
				'block_editor_autoexit_fullscreen_mode',
				__( 'Fullscreen Mode', 'unbloater' ),
				array( $this, 'cb_setting_block_editor_autoexit_fullscreen_mode' ),
				'unbloater',
				'unbloater_section_block_editor'
			);
			
		}
		
		/******************************************************************
		********* ADVANCED CUSTOM FIELDS SECTION **************************
		******************************************************************/
		
		if( Unbloater_Helper::is_plugin_active( array( 'advanced-custom-fields/acf.php', 'advanced-custom-fields-pro/acf.php' ) ) ) {
			
			add_settings_section(
				'unbloater_section_acf',
				__( 'Advanced Custom Fields Settings', 'unbloater' ),
				array( $this, 'cb_settings_section_acf' ),
				'unbloater'
			);
			
			add_settings_field(
				'acf_hide_admin',
				__( 'Hide Admin', 'unbloater' ),
				array( $this, 'cb_setting_acf_hide_admin' ),
				'unbloater',
				'unbloater_section_acf'
			);
			
		}
		
		/******************************************************************
		********* AUTOPTIMIZE SECTION *************************************
		******************************************************************/
		
		if( Unbloater_Helper::is_plugin_active( 'autoptimize/autoptimize.php' ) ) {
			
			add_settings_section(
				'unbloater_section_autoptimize',
				__( 'Autoptimize Settings', 'unbloater' ),
				array( $this, 'cb_settings_section_autoptimize' ),
				'unbloater'
			);
			
			add_settings_field(
				'autoptimize_remove_admin_bar_item',
				__( 'Admin Bar', 'unbloater' ),
				array( $this, 'cb_setting_autoptimize_remove_admin_bar_item' ),
				'unbloater',
				'unbloater_section_autoptimize'
			);
			
			add_settings_field(
				'autoptimize_remove_imgopt_nag',
				__( 'Imgopt notice', 'unbloater' ),
				array( $this, 'cb_setting_autoptimize_remove_imgopt_nag' ),
				'unbloater',
				'unbloater_section_autoptimize'
			);
			
		}
		
		/******************************************************************
		********* RANK MATH SECTION ***************************************
		******************************************************************/
		
		if( Unbloater_Helper::is_plugin_active( 'seo-by-rank-math/rank-math.php' ) ) {
			
			add_settings_section(
				'unbloater_section_rankmath',
				__( 'Rank Math', 'unbloater' ),
				array( $this, 'cb_settings_section_rankmath' ),
				'unbloater'
			);
			
			add_settings_field(
				'rankmath_remove_admin_bar_item',
				__( 'Admin Bar', 'unbloater' ),
				array( $this, 'cb_setting_rankmath_remove_admin_bar_item' ),
				'unbloater',
				'unbloater_section_rankmath'
			);
			
			add_settings_field(
				'rankmath_whitelabel',
				__( 'Whitelabel', 'unbloater' ),
				array( $this, 'cb_setting_rankmath_whitelabel' ),
				'unbloater',
				'unbloater_section_rankmath'
			);
			
			add_settings_field(
				'rankmath_remove_sitemap_credit',
				__( 'Sitemap Credit', 'unbloater' ),
				array( $this, 'cb_setting_rankmath_remove_sitemap_credit' ),
				'unbloater',
				'unbloater_section_rankmath'
			);
			
			add_settings_field(
				'rankmath_remove_link_class',
				__( 'Link Class', 'unbloater' ),
				array( $this, 'cb_setting_rankmath_remove_link_class' ),
				'unbloater',
				'unbloater_section_rankmath'
			);
			
		}
		
		/******************************************************************
		********* SEARCHWP SECTION ****************************************
		******************************************************************/
		
		if( Unbloater_Helper::is_plugin_active( 'searchwp/index.php' ) ) {
			
			add_settings_section(
				'unbloater_section_searchwp',
				__( 'SearchWP Settings', 'unbloater' ),
				array( $this, 'cb_settings_section_searchwp' ),
				'unbloater'
			);
			
			add_settings_field(
				'searchwp_disable_stats_widget',
				__( 'Stats Widget', 'unbloater' ),
				array( $this, 'cb_setting_searchwp_disable_stats_widget' ),
				'unbloater',
				'unbloater_section_searchwp'
			);
			
			add_settings_field(
				'searchwp_disable_stats_link',
				__( 'Stats Link', 'unbloater' ),
				array( $this, 'cb_setting_searchwp_disable_stats_link' ),
				'unbloater',
				'unbloater_section_searchwp'
			);
			
			add_settings_field(
				'searchwp_remove_admin_bar_item',
				__( 'Admin Bar', 'unbloater' ),
				array( $this, 'cb_setting_searchwp_remove_admin_bar_item' ),
				'unbloater',
				'unbloater_section_searchwp'
			);
			
			add_settings_field(
				'searchwp_move_menu_item_to_bottom',
				__( 'Menu Item Position', 'unbloater' ),
				array( $this, 'cb_setting_searchwp_move_menu_item_to_bottom' ),
				'unbloater',
				'unbloater_section_searchwp'
			);
			
			add_settings_field(
				'searchwp_remove_menu_item',
				__( 'Menu Item', 'unbloater' ),
				array( $this, 'cb_setting_searchwp_remove_menu_item' ),
				'unbloater',
				'unbloater_section_searchwp'
			);
			
		}
		
		/******************************************************************
		********* THE SEO FRAMEWORK SECTION *******************************
		******************************************************************/
		
		if( Unbloater_Helper::is_plugin_active( 'autodescription/autodescription.php' ) ) {
			
			add_settings_section(
				'unbloater_section_autodescription',
				__( 'The SEO Framework Settings', 'unbloater' ),
				array( $this, 'cb_settings_section_autodescription' ),
				'unbloater'
			);
			
			add_settings_field(
				'autodescription_remove_output_indicator',
				__( 'Plugin Indicator', 'unbloater' ),
				array( $this, 'cb_setting_autodescription_remove_output_indicator' ),
				'unbloater',
				'unbloater_section_autodescription'
			);
			
			add_settings_field(
				'autodescription_metabox_context_side',
				__( 'Metabox Context', 'unbloater' ),
				array( $this, 'cb_setting_autodescription_metabox_context_side' ),
				'unbloater',
				'unbloater_section_autodescription'
			);
			
		}
		
		/******************************************************************
		********* WOOCOMMERCE SECTION *************************************
		******************************************************************/
		
		if( Unbloater_Helper::is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			
			add_settings_section(
				'unbloater_section_woocommerce',
				__( 'WooCommerce Settings', 'unbloater' ),
				array( $this, 'cb_settings_section_woocommerce' ),
				'unbloater'
			);
			
			add_settings_field(
				'wc_helper_remove_connection_nag',
				__( 'Connection Notice', 'unbloater' ),
				array( $this, 'cb_setting_wc_helper_remove_connection_nag' ),
				'unbloater',
				'unbloater_section_woocommerce'
			);
			
			add_settings_field(
				'wc_helper_remove_all_admin_nags',
				__( 'All Admin Notices', 'unbloater' ),
				array( $this, 'cb_setting_wc_helper_remove_all_admin_nags' ),
				'unbloater',
				'unbloater_section_woocommerce'
			);
			
			add_settings_field(
				'wc_remove_cart_fragments',
				__( 'Cart Fragments', 'unbloater' ),
				array( $this, 'cb_setting_wc_remove_cart_fragments' ),
				'unbloater',
				'unbloater_section_woocommerce'
			);
			
			add_settings_field(
				'wc_remove_skyverge_dashboard',
				__( 'SkyVerge Dashboard', 'unbloater' ),
				array( $this, 'cb_setting_wc_remove_skyverge_dashboard' ),
				'unbloater',
				'unbloater_section_woocommerce'
			);
			
		}
		
		/******************************************************************
		********* YOAST SEO SECTION ***************************************
		******************************************************************/
		
		if( Unbloater_Helper::is_plugin_active( 'wordpress-seo/wp-seo.php' ) ) {
			
			add_settings_section(
				'unbloater_section_yoast_seo',
				__( 'Yoast SEO', 'unbloater' ),
				array( $this, 'cb_settings_section_yoast_seo' ),
				'unbloater'
			);
			
			add_settings_field(
				'yoast_seo_remove_html_comments',
				__( 'HTML Comments', 'unbloater' ),
				array( $this, 'cb_setting_yoast_seo_remove_html_comments' ),
				'unbloater',
				'unbloater_section_yoast_seo'
			);
			
			add_settings_field(
				'yoast_seo_remove_admin_bar_item',
				__( 'Admin Bar', 'unbloater' ),
				array( $this, 'cb_setting_yoast_seo_remove_admin_bar_item' ),
				'unbloater',
				'unbloater_section_yoast_seo'
			);
			
		}
		
	}

	/******************************************************************
	********* CORE BACKEND CALLBACKS **********************************
	******************************************************************/

	public function cb_settings_section_core_backend() {
		echo '<p>' . __( 'These settings are related to WordPress Core functions and code that\'s happening in the admin area.', 'unbloater' ) . '</p>';
	}
	
	public function cb_setting_remove_update_available_notice() {
		$this->render_settings_field_checkbox(
			'remove_update_available_notice',
			__( 'Hide the WordPress update notice for non-Administrator users', 'unbloater' ),
			__( 'The update notice will still be shown to Administrator-level users.', 'unbloater' )
		);
	}
	
	public function cb_setting_disable_auto_updates_core() {
		$this->render_settings_field_checkbox(
			'disable_auto_updates_core',
			__( 'Disable the Core auto-update system completely for WordPress, plugin and theme updates', 'unbloater' ),
			__( 'Enabling this option will overwrite the individual Plugin and Theme settings below.', 'unbloater' ),
			defined( 'AUTOMATIC_UPDATER_DISABLED' ) ? true : false
		);
	}
	
	public function cb_setting_disable_auto_updates_plugins() {
		$this->render_settings_field_checkbox(
			'disable_auto_updates_plugins',
			__( 'Disable auto-updates for plugins (including the UI)', 'unbloater' ),
			__( 'The \'Auto-Updates\' setting above might overwrite this setting.' ),
			( defined( 'AUTOMATIC_UPDATER_DISABLED' ) || Unbloater_Helper::is_option_activated( 'disable_auto_updates_core' ) ) ? true : false
		);
	}
	
	public function cb_setting_disable_auto_updates_themes() {
		$this->render_settings_field_checkbox(
			'disable_auto_updates_themes',
			__( 'Disable auto-updates for themes (including the UI)', 'unbloater' ),
			__( 'The \'Auto-Updates\' setting above might overwrite this setting.' ),
			( defined( 'AUTOMATIC_UPDATER_DISABLED' ) || Unbloater_Helper::is_option_activated( 'disable_auto_updates_core' ) ) ? true : false
		);
	}
	
	public function cb_setting_disable_core_upgrade_bundled_items() {
		$this->render_settings_field_checkbox(
			'disable_core_upgrade_bundled_items',
			__( 'Disable the installation of bundled items during Core upgrades', 'unbloater' ),
			sprintf( __( 'This will for example prevent new default themes from being installed during a major WordPress version upgrade.', 'unbloater' ), '<code>', '</code>' ),
			defined( 'CORE_UPGRADE_SKIP_NEW_BUNDLED' ) ? true : false
		);
	}
	
	public function cb_setting_disallow_file_edit() {
		$this->render_settings_field_checkbox(
			'disallow_file_edit',
			__( 'Disable the built-in code editors that allow users to modify plugin and theme code via the admin area', 'unbloater' ),
			sprintf( __( 'Please note that this option provides more security when set via the %1$swp-config.php%2$s file.', 'unbloater' ), '<code>', '</code>' ),
			defined( 'DISALLOW_FILE_EDIT' ) ? true : false
		);
	}
	
	public function cb_setting_limit_post_revisions() {
		$this->render_settings_field_checkbox(
			'limit_post_revisions',
			__( 'Limit the number of post revisions to keep (per post) to a database-friendly maximum of 5', 'unbloater' ),
			null,
			defined( 'WP_POST_REVISIONS' ) && WP_POST_REVISIONS != 5 ? true : false
		);
	}

	public function cb_setting_limit_empty_trash_period() {
		$this->render_settings_field_checkbox(
			'limit_empty_trash_period',
			__( 'Reduce the number of days until posts are deleted from the trash to 7 (WordPress default: 30 days)', 'unbloater' ),
			null,
			defined( 'EMPTY_TRASH_DAYS' ) && EMPTY_TRASH_DAYS != 30 ? true : false
		);
	}
	
	public function cb_setting_limit_application_password_creation() {
		$this->render_settings_field_checkbox(
			'limit_application_password_creation',
			__( 'Limit the creation of Application Passwords to Administrator level users', 'unbloater' ),
			__( 'This will only prevent the creation of Application Passwords on non-administrator users Profile pages.', 'unbloater' ),
			Unbloater_Helper::is_option_activated( 'disable_application_passwords' )
		);
	}

	public function cb_setting_disable_application_passwords() {
		$this->render_settings_field_checkbox(
			'disable_application_passwords',
			__( 'Disable Application Passwords completely', 'unbloater' ),
			__( 'Enabling this option will overwrite the more granular options below.', 'unbloater' )
		);
	}
	
	public function cb_setting_disable_admin_email_confirmation() {
		$this->render_settings_field_checkbox(
			'disable_admin_email_confirmation',
			__( 'Disable the admin email confirmation screen', 'unbloater' ),
			__( 'The admin email confirmation message gets displayed every 6 months by default.', 'unbloater' )
		);
	}
	
	public function cb_setting_disable_xmlrpc() {
		$this->render_settings_field_checkbox(
			'disable_xmlrpc',
			__( 'Disable the XML-RPC API endpoint', 'unbloater' ),
			__( 'This is often used by attackers. Some external and mobile apps rely on it though.', 'unbloater' )
		);
	}
	
	public function cb_setting_remove_admin_bar_wordpress_item() {
		$this->render_settings_field_checkbox(
			'remove_admin_bar_wordpress_item',
			__( 'Remove the admin bar WordPress \'W\' item', 'unbloater' )
		);
	}
	
	public function cb_setting_remove_admin_footer() {
		$this->render_settings_field_checkbox(
			'remove_admin_footer',
			__( 'Remove the admin footer text', 'unbloater' ),
			__( 'Default: \'Thank you for creating with WordPress.\'', 'unbloater' )
		);
	}
	
	/******************************************************************
	********* CORE FRONTEND CALLBACKS *********************************
	******************************************************************/
	
	public function cb_settings_section_core_frontend() {
		echo '<p>' . sprintf( esc_html__( 'These settings are related to WordPress Core functions and code on the visitor-facing side of your website, mainly in the %1$s<head>%2$s section.', 'unbloater' ), '<code>', '</code>' ) . '</p>';
	}
	
	public function cb_setting_remove_generator_tag() {
		$this->render_settings_field_checkbox(
			'remove_generator_tag',
			__( 'Remove the Generator meta tag', 'unbloater' ),
			__( 'Hides your WordPress version from plain sight.', 'unbloater' )
		);
	}
	
	public function cb_setting_remove_script_style_version_parameter() {
		$this->render_settings_field_checkbox(
			'remove_script_style_version_parameter',
			__( 'Remove the version parameter from styles and scripts', 'unbloater' ),
			__( 'Hides your WordPress version some more.', 'unbloater' )
		);
	}
	
	public function cb_setting_remove_wlw_manifest_link() {
		$this->render_settings_field_checkbox(
			'remove_wlw_manifest_link',
			__( 'Remove the WLW Manifest link', 'unbloater' ),
			__( 'The Windows Live Writer (super old, dead software) Manifest is absolutely not needed anymore.', 'unbloater' )
		);
	}
	
	public function cb_setting_remove_rsd_link() {
		$this->render_settings_field_checkbox(
			'remove_rsd_link',
			__( 'Remove the RSD link', 'unbloater' ),
			__( 'The Really Simple Discovery link is used by some external editors and apps.', 'unbloater' )
		);
	}
	
	public function cb_setting_remove_shortlink() {
		$this->render_settings_field_checkbox(
			'remove_shortlink',
			__( 'Remove the post shortlink URL', 'unbloater' )
		);
	}
	
	public function cb_setting_remove_feed_generator_tag() {
		$this->render_settings_field_checkbox(
			'remove_feed_generator_tag',
			__( 'Remove the generator tag from RSS feeds', 'unbloater' ),
			null,
			Unbloater_Helper::is_option_activated( 'disable_feeds' ) ? true : false
		);
	}
	
	public function cb_setting_remove_feed_links() {
		$this->render_settings_field_checkbox(
			'remove_feed_links',
			__( 'Remove RSS (Really Simple Syndication) feed links for posts and comments', 'unbloater' ),
			sprintf( esc_html__( 'This will not disable the feeds itself, but just remove their link from the site\'s %1$s<head>%2$s section.', 'unbloater' ), '<code>', '</code>' ),
			Unbloater_Helper::is_option_activated( 'disable_feeds' ) ? true : false
		);
	}
	
	public function cb_setting_disable_feeds() {
		$this->render_settings_field_checkbox(
			'disable_feeds',
			__( 'Disable RSS (Really Simple Syndication) feeds for posts and comments', 'unbloater' ),
			__( 'This will actually disable the feeds and redirect their URLs to the frontpage.', 'unbloater' )
		);
	}
	
	public function cb_setting_remove_wporg_dns_prefetch() {
		$this->render_settings_field_checkbox(
			'remove_wporg_dns_prefetch',
			__( 'Remove the DNS prefetch to s.w.org', 'unbloater' )
		);
	}
	
	public function cb_setting_remove_jquery_migrate() {
		$this->render_settings_field_checkbox(
			'remove_jquery_migrate',
			__( 'Remove jQuery Migrate script', 'unbloater' ),
			__( 'This removes an additional script that ensures backward compatibility for older scripts. This should be save to use for modern themes and plugins.', 'unbloater' )
		);
	}
	
	public function cb_setting_disable_emojis() {
		$this->render_settings_field_checkbox(
			'disable_emojis',
			__( 'Disable WordPress\' own emoji scripts and styles', 'unbloater' ),
			__( 'This gets rid of a script, an inline script, inline styles and a DNS prefetch.', 'unbloater' )
		);
	}
	
	public function cb_setting_optimize_comment_js_loading() {
		$this->render_settings_field_checkbox(
			'optimize_comment_js_loading',
			__( 'Optimize the comment script by only loading it when needed (when comments are activated and open)', 'unbloater' )
		);
	}
	
	public function cb_setting_remove_recent_comments_style() {
		$this->render_settings_field_checkbox(
			'remove_recent_comments_style',
			sprintf( esc_html__( 'Remove an inline style block from the site\'s %1$s<head>%2$s that is used by old themes', 'unbloater' ), '<code>', '</code>' )
		);
	}

	public function cb_setting_disable_comment_hyperlinks() {
		$this->render_settings_field_checkbox(
			'disable_comment_hyperlinks',
			__( 'Disable automatic clickable hyperlinking of URLs in new comments', 'unbloater' ),
			__( 'Gives a slight security advantage. Proper spam protection should always be in place though.', 'unbloater' )
		);
	}
	
	public function cb_setting_reduce_heartbeat_interval() {
		$this->render_settings_field_checkbox(
			'reduce_heartbeat_interval',
			__( 'Reduce the Heartbeat interval to save on admin-ajax usage', 'unbloater' ),
			__( 'This will reduce the Heartbeat interval from 15 seconds (default) to 60 seconds in order to reduce server load.', 'unbloater' )
		);
	}
	
	public function cb_setting_normalize_favicon() {
		$this->render_settings_field_checkbox(
			'normalize_favicon',
			__( 'Normalize the favicon by removing the default', 'unbloater' ),
			__( 'Only the default WordPresss \'W\' icon will be unset. Custom favicons can still be set via the Customizer.', 'unbloater' )
		);
	}
	
	public function cb_setting_normalize_login_logo_url() {
		$this->render_settings_field_checkbox(
			'normalize_login_logo_url',
			__( 'Normalize the logo link target on the login page', 'unbloater' ),
			__( 'This will link the WordPress logo on the login page to your site.', 'unbloater' )
		);
	}
	
	public function cb_setting_normalize_login_logo_title() {
		$this->render_settings_field_checkbox(
			'normalize_login_logo_title',
			__( 'Normalize the logo title on the login page', 'unbloater' ),
			__( 'This will change the WordPress logo title on the login page to your site\'s name.', 'unbloater' )
		);
	}
	
	public function cb_setting_disable_login_language_dropdown() {
		$this->render_settings_field_checkbox(
			'disable_login_language_dropdown',
			__( 'Disable the language selector on the login page', 'unbloater' )
		);
	}
	
	/******************************************************************
	********* BLOCK EDITOR / GUTENBERG ********************************
	******************************************************************/

	public function cb_settings_section_block_editor() {
		echo '<p>' . __( 'These settings are related to the WordPress Core Block Editor, also known as Gutenberg.', 'unbloater' ) . '</p>';
	}

	public function cb_setting_block_editor_deactivate_block_directory() {
		$this->render_settings_field_checkbox(
			'block_editor_deactivate_block_directory',
			__( 'Deactivate the Block Directory', 'unbloater' ),
			__( 'The Block Directory allows users to install additional blocks from within the Block Editor.', 'unbloater' )
		);
	}
	
	public function cb_setting_block_editor_deactivate_core_block_patterns() {
		$this->render_settings_field_checkbox(
			'block_editor_deactivate_core_block_patterns',
			__( 'Remove Core Block Patterns', 'unbloater' ),
			__( 'This will not remove any custom or theme block patterns.', 'unbloater' )
		);
	}
	
	public function cb_setting_block_editor_deactivate_template_editor() {
		$this->render_settings_field_checkbox(
			'block_editor_deactivate_template_editor',
			__( 'Deactivate the Template Editor', 'unbloater' ),
			__( 'The Template Editor allows users to edit their theme\'s templates from within the Block Editor. Block themes might overwrite this setting.', 'unbloater' )
		);
	}
	
	public function cb_setting_block_editor_autoclose_welcome_guide() {
		$this->render_settings_field_checkbox(
			'block_editor_autoclose_welcome_guide',
			__( 'Auto-close the Welcome Guide on editor load', 'unbloater' ),
			__( 'Users will still be able to open it manually via the context menu.', 'unbloater' )
		);
	}
	
	public function cb_setting_block_editor_autoexit_fullscreen_mode() {
		$this->render_settings_field_checkbox(
			'block_editor_autoexit_fullscreen_mode',
			__( 'Auto-exit the Fullscreen Mode on editor load', 'unbloater' ),
			__( 'Users will still be able to enter it manually via the context menu.', 'unbloater' )
		);
	}
	
	/******************************************************************
	********* ADVANCED CUSTOM FIELDS CALLBACKS ************************
	******************************************************************/

	public function cb_settings_section_acf() {
		echo '<p>' . sprintf( __( 'These settings are related to %s.', 'unbloater' ), '<a href="https://www.advancedcustomfields.com" target="_blank">Advanced Custom Fields</a>' ) . '</p>';
	}

	public function cb_setting_acf_hide_admin() {
		$this->render_settings_field_checkbox(
			'acf_hide_admin',
			__( 'Remove the Advanced Custom Fields admin interface for all users', 'unbloater' ),
			__( 'It\'s suggested to only do this if you manage fields via code, e.g. on client sites.', 'unbloater' )
		);
	}
	
	/******************************************************************
	********* AUTOPTIMIZE CALLBACKS ***********************************
	******************************************************************/
	
	public function cb_settings_section_autoptimize() {
		echo '<p>' . sprintf( __( 'These settings are related to %s.', 'unbloater' ), '<a href="https://wordpress.org/plugins/autoptimize/" target="_blank">Autoptimize</a>' ) . '</p>';
	}
	
	public function cb_setting_autoptimize_remove_admin_bar_item() {
		$this->render_settings_field_checkbox(
			'autoptimize_remove_admin_bar_item',
			__( 'Remove the Autoptimize admin bar item', 'unbloater' )
		);
	}
	
	public function cb_setting_autoptimize_remove_imgopt_nag() {
		$this->render_settings_field_checkbox(
			'autoptimize_remove_imgopt_nag',
			__( 'Remove the Autoptimize Imgopt admin notice', 'unbloater' )
		);
	}
	
	/******************************************************************
	********* RANK MATH CALLBACKS *************************************
	******************************************************************/

	public function cb_settings_section_rankmath() {
		echo '<p>' . sprintf( __( 'These settings are related to %s.', 'unbloater' ), '<a href="https://wordpress.org/plugins/seo-by-rank-math/" target="_blank">Rank Math</a>' ) . '</p>';
	}
	
	public function cb_setting_rankmath_remove_admin_bar_item() {
		$this->render_settings_field_checkbox(
			'rankmath_remove_admin_bar_item',
			__( 'Remove the Rank Math item from the admin bar', 'unbloater' )
		);
	}
	
	public function cb_setting_rankmath_whitelabel() {
		$this->render_settings_field_checkbox(
			'rankmath_whitelabel',
			sprintf( esc_html__( 'Remove the Rank Math credit comments from the site\'s %1$s<head>%2$s and footer credit from Rank Math admin pages', 'unbloater' ), '<code>', '</code>' )
		);
	}
	
	public function cb_setting_rankmath_remove_sitemap_credit() {
		$this->render_settings_field_checkbox(
			'rankmath_remove_sitemap_credit',
			__( 'Remove the Rank Math credit from the sitemap', 'unbloater' )
		);
	}
	
	public function cb_setting_rankmath_remove_link_class() {
		$this->render_settings_field_checkbox(
			'rankmath_remove_link_class',
			__( 'Remove the Rank Math class from frontend links', 'unbloater' )
		);
	}
	
	/******************************************************************
	********* SEARCHWP CALLBACKS **************************************
	******************************************************************/

	public function cb_settings_section_searchwp() {
		echo '<p>' . sprintf( __( 'These settings are related to %s.', 'unbloater' ), '<a href="https://searchwp.com" target="_blank">SearchWP</a>' ) . '</p>';
	}
	
	public function cb_setting_searchwp_disable_stats_widget() {
		$this->render_settings_field_checkbox(
			'searchwp_disable_stats_widget',
			__( 'Remove the SearchWP Stats widget from the Dashboard', 'unbloater' )
		);
	}
	
	public function cb_setting_searchwp_disable_stats_link() {
		$this->render_settings_field_checkbox(
			'searchwp_disable_stats_link',
			__( 'Remove the SearchWP Stats link from the Dashboard menu', 'unbloater' )
		);
	}
	
	public function cb_setting_searchwp_remove_admin_bar_item() {
		$this->render_settings_field_checkbox(
			'searchwp_remove_admin_bar_item',
			__( 'Remove the SearchWP item from the admin bar', 'unbloater' )
		);
	}
	
	public function cb_setting_searchwp_move_menu_item_to_bottom() {
		$this->render_settings_field_checkbox(
			'searchwp_move_menu_item_to_bottom',
			__( 'Move the SearchWP top-level menu item to the bottom', 'unbloater' ),
			null,
			Unbloater_Helper::is_option_activated( 'searchwp_remove_menu_item' ) ? true : false
		);
	}
	
	public function cb_setting_searchwp_remove_menu_item() {
		$this->render_settings_field_checkbox(
			'searchwp_remove_menu_item',
			__( 'Remove the SearchWP top-level menu item', 'unbloater' )
		);
	}
	
	/******************************************************************
	********* THE SEO FRAMEWORK CALLBACKS *****************************
	******************************************************************/
	
	public function cb_settings_section_autodescription() {
		echo '<p>' . sprintf( __( 'These settings are related to %s.', 'unbloater' ), '<a href="https://wordpress.org/plugins/autodescription/" target="_blank">The SEO Framework</a>' ) . '</p>';
	}
	
	public function cb_setting_autodescription_remove_output_indicator() {
		$this->render_settings_field_checkbox(
			'autodescription_remove_output_indicator',
			__( 'Remove the indicator in the plugin\'s HTML output', 'unbloater' ),
			__( 'These are HTML comments that indicate the plugin\'s existence and usage.', 'unbloater' )
		);
	}
	
	public function cb_setting_autodescription_metabox_context_side() {
		$this->render_settings_field_checkbox(
			'autodescription_metabox_context_side',
			__( 'Move the SEO metabox into \'side\' context', 'unbloater' ),
			__( 'Helpful especially when using Gutenberg, since this cleans up the Gutenberg editor area.', 'unbloater' )
		);
	}
	
	/******************************************************************
	********* WOOCOMMERCE CALLBACKS ***********************************
	******************************************************************/

	public function cb_settings_section_woocommerce() {
		echo '<p>' . sprintf( __( 'These settings are related to %s.', 'unbloater' ), '<a href="https://wordpress.org/plugins/woocommerce/" target="_blank">WooCommerce</a>' ) . ' ' . sprintf( __( 'If you\'re interested in further unbloating, check out the %1$sDisable WooCommerce Bloat%2$s plugin.', 'unbloater' ), '<a href="https://wordpress.org/plugins/disable-dashboard-for-woocommerce/" target="_blank">', '</a>' ) . '</p>';
	}
	
	public function cb_setting_wc_helper_remove_connection_nag() {
		$this->render_settings_field_checkbox(
			'wc_helper_remove_connection_nag',
			__( 'Remove WooCommerce \'Connect your store\' notice', 'unbloater' )
		);
	}
	
	public function cb_setting_wc_helper_remove_all_admin_nags() {
		$this->render_settings_field_checkbox(
			'wc_helper_remove_all_admin_nags',
			__( 'Remove all WooCommerce admin notices', 'unbloater' ),
			__( 'Use carefully, as this may hide important notices you might miss when activated.', 'unbloater' )
		);
	}

	public function cb_setting_wc_remove_cart_fragments() {
		$wc_settings_url = admin_url() . 'admin.php?page=wc-settings&tab=products';
		$this->render_settings_field_checkbox(
			'wc_remove_cart_fragments',
			__( 'Remove WooCommerce Cart Fragment scripts (which improves performance but disables live update functionality)', 'unbloater' ),
			sprintf( __( 'If you remove cart fragments, consider setting the cart behaviour to redirect to the cart page after adding a product in the %1$sWooCommerce Settings%2$s.', 'unbloater' ), '<a href="' . $wc_settings_url . '" target="_blank">', '</a>' )
		);
	}
	
	public function cb_setting_wc_remove_skyverge_dashboard() {
		$cart_settings_url = admin_url() . 'admin.php?page=wc-settings&tab=products';
		$this->render_settings_field_checkbox(
			'wc_remove_skyverge_dashboard',
			__( 'Remove the SkyVerge Dashboard', 'unbloater' )
		);
	}

	/******************************************************************
	********* YOAST SEO CALLBACKS *************************************
	******************************************************************/

	public function cb_settings_section_yoast_seo() {
		echo '<p>' . sprintf( __( 'These settings are related to %s.', 'unbloater' ), '<a href="https://wordpress.org/plugins/wordpress-seo/" target="_blank">Yoast SEO</a>' ) . '</p>';
	}
	
	public function cb_setting_yoast_seo_remove_html_comments() {
		$this->render_settings_field_checkbox(
			'yoast_seo_remove_html_comments',
			__( 'Remove the indicator in the plugin\'s HTML output', 'unbloater' ),
			__( 'These are HTML comments that indicate the plugin\'s existence and usage.', 'unbloater' )
		);
	}
	
	public function cb_setting_yoast_seo_remove_admin_bar_item() {
		$this->render_settings_field_checkbox(
			'yoast_seo_remove_admin_bar_item',
			__( 'Remove the Yoast SEO admin bar item', 'unbloater' )
		);
	}
	
}
