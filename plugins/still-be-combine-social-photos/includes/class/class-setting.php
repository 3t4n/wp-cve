<?php

namespace StillBE\Plugin\CombineSocialPhotos;


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




final class Setting {


	const PREFIX        = SB_CSP_PREFIX;
	const SETTING_GROUP = SB_CSP_PREFIX. 'setting-group';
	const SETTING_NAME  = SB_CSP_PREFIX. 'setting-option';

	const ACTION_SET_AUTH_USER = 'set_auth_user';
	const ACTION_REAUTH_USER   = 'reauth_user';
	const ACTION_REFRESH_TOKEN = 'refresh_token';
	const ACTION_RESET_SETTING = 'reset_setting';
	const ACTION_UNLOCK_DB     = 'unlock_database';

	const GRAPH_API_EXAMINE_TOKEN_ME_URL = 'https://graph.instagram.com/'. STILLBE_CSP_FB_GRAPH_API_VERSION. '/me';
	const GRAPH_API_REFRESH_TOKEN_URL    = 'https://graph.instagram.com/refresh_access_token';

	const DEFAULT_CACHE_LIFETIME = 3600;
	const MIN_CACHE_LIFETIME     = 600;
	const MAX_CACHE_LIFETIME     = 24 * 3600;
	const STEP_CACHE_LIFETIME    = 1;

	const DEFAULT_REFRESH_TOKEN_DAYS = 10;
	const MIN_REFRESH_TOKEN_DAYS     = 1;
	const MAX_REFRESH_TOKEN_DAYS     = 10;
	const STEP_REFRESH_TOKEN_DAYS    = 1;

	public $name        = null;
	public $description = null;

	private static $instance = null;

	private $settings = array();


	public static function init() {

		if( empty( self::$instance ) ) {
			self::$instance = new self;
		}

		return self::$instance;

	}


	// Constructer
	private function __construct() {

		// Initialize
		$this->_initialize();

		// Add Settings
		$this->_add_settings();

		// Plugin Information
		$this->name        = esc_html__( 'Combine Social Photos | Still BE', 'still-be-combine-social-photos' );
		$this->description = esc_html__( 'Embed the Instagram posts into your site. You can get and display posts by yourself and others, and posts that related of hashtag.', 'still-be-combine-social-photos' );

	}


	// Initialization of the Plugin
	private function _initialize() {

		// Add a Submenu Page
		add_action( 'admin_menu', function() {

		//	add_submenu_page(
		//	Wrapper Function adding submenu page to the Settings main menu.
			add_options_page(
			//	'options-general.php',   // Parent Slug
				esc_html__( 'Setting up Instagram embedding', 'still-be-combine-social-photos' ),   // Page Title
				esc_html__( 'Instagram', 'still-be-combine-social-photos' ),    // Menu Title
				'manage_options',   // Capability
				self::PREFIX. 'setting-page',   // Menu Slug
				array( $this, 'render_setting_page' )   // Rederer
			);

		} );

		// Add settings link to plugin actions
		add_filter( 'plugin_action_links', function( $plugin_actions, $plugin_file ) {

			if( basename( STILLBE_CSP_BASE_DIR ). '/still-be-combine-social-photo.php' !== $plugin_file ) {
				return $plugin_actions;
			}

			return array_merge(
				array(
					'sb_csp_settings' => sprintf(
						__( '<a href="%s">Settings</a>', 'still-be-combine-social-photos' ),
						esc_url( admin_url( 'options-general.php?page='. self::PREFIX. 'setting-page' ) )
					),
				),
				$plugin_actions
			);

		}, 10, 2 );

		// Load CSS / Javascript for Admin
		add_action( 'admin_enqueue_scripts', function( $hook_suffix ) {

			if( ( 'settings_page_'. self::PREFIX. 'setting-page' ) !== $hook_suffix ) {
				// @since 0.2.1   Load in all admin screens for Full Site Editing
			//	return;
			}

			// Common
			Main::init()->admin_enqueue_scripts_common();

			// Enqueues all Media JS APIs
			wp_enqueue_media();

		} );

		// Add Settings
		add_action( 'admin_init', function() {

			// Setting API
			//  * Arg 1 : Group Name using Setting API
			//  * Arg 2 : Saving Key Name in WP Options table (in SQL)
			register_setting( self::SETTING_GROUP, self::SETTING_NAME, array( $this, 'sanitize_setting' ) );

		}, 10 );

		// Display Notices
		add_action( 'all_admin_notices', array( $this, 'display_notices' ) );

		// Add WP-Ajax Actions

		// Set an Authorized User to DB & Get Additional Infos of the User
		add_action( 'wp_ajax_'. self::ACTION_SET_AUTH_USER, array( $this, 'ajax_set_auth_user' ) );

		// Update Re-Authorized User to DB
		add_action( 'wp_ajax_'. self::ACTION_REAUTH_USER,   array( $this, 'ajax_reauth_user' ) );

		// Refresh Access Token
		add_action( 'wp_ajax_'. self::ACTION_REFRESH_TOKEN, array( $this, 'ajax_refresh_token' ) );

		// Reset All Settings
		add_action( 'wp_ajax_'. self::ACTION_RESET_SETTING, array( $this, 'ajax_reset_setting' ) );

		// Unlock Database
		add_action( 'wp_ajax_'. self::ACTION_UNLOCK_DB,     array( $this, 'ajax_unlock_database' ) );

	}


	// Render Setting Form Wrapper & Common Style
	public function render_setting_page() {

		// Curernt Settings
		$this->settings = get_option( self::SETTING_NAME, array() );

		// POST Data
		$ig_token = filter_input( INPUT_POST, 'sb-csp-ig-token', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY ) ?: array();
		echo '<script>window.__authToken = '. wp_json_encode( $ig_token ). ';</script>';

		// Wrapper
		echo '<div class="wrap">';

			// Title
			echo '<h1>'. esc_html__( 'Setting up Instagram embedding', 'still-be-combine-social-photos' ). '</h1>';

			// Other Products
			Other_Products::show();

			// Setting Form
			echo '<form name="ab-combine-social-photos-setting-form" method="POST" action="options.php">';

				// Since it will be sanitized twice at the time of initial setting, add a flag to skip the second sanitize
				//   * This is because the core function "update_option" is sanitized in it if option does not exist,
				//     and then "add_option" is executed and sanitized in it too.
				echo '<input type="hidden" name="'. esc_attr( self::SETTING_NAME. '[not-sanitized]' ). '" value="true">';

				// Add the information using Setting API
				// Group Name / Action Name / Nonce / This Page Path
				settings_fields( self::SETTING_GROUP );

				// Output All Sections
				stillbe_do_settings_sections_tab_style( self::PREFIX. 'setting-page' );

				// Submit Button
				submit_button();

			// Closing From
			echo '</form>';

		// Closing Wrapper
		echo '</div>';

	}


	public function sanitize_setting( $input ) {

		// Sanitized data is passed through
		if( empty( $input['not-sanitized'] ) ) {
			return $input;
		}

		// Current Settings
		$current_settings = get_option( self::SETTING_NAME, array() );

		// Current Accounts
		if( empty( $current_settings['accounts'] ) || ! is_array( $current_settings['accounts'] ) ) {
			$current_accounts = array();
		} else {
			$current_accounts = array_combine(
				array_column( $current_settings['accounts'], 'id' ),
				$current_settings['accounts']
			);
		}

		// Save Settings
		$save = array();

		// Accounts
		$accounts = array();
		if( ! empty( $input['accounts'] ) && is_array( $input['accounts'] ) ) {
			foreach( $input['accounts'] as $i_account ) {
				if( empty( $i_account['id'] ) ) {
					continue;
				}
				$_account = $current_accounts[ $i_account['id'] ] ?? array();
				$_account = self::_object_merge_recursive( $_account, $i_account );
				if( isset( $_account->token->token ) ) {
					$accounts[] = $_account;
				}
			}
		}

		// Cache
		$cache = array();
		if( ! empty( $input['cache']['data-lifetime'] ) ) {
			$_num = absint( $input['cache']['data-lifetime'] );
			$_num = max( min( $_num, self::MAX_CACHE_LIFETIME ), self::MIN_CACHE_LIFETIME );
			$_num = floor( $_num / self::STEP_CACHE_LIFETIME ) * self::STEP_CACHE_LIFETIME;
			$cache['data-lifetime'] = $_num;
		}
		if( ! empty( $input['cache']['refresh-token'] ) ) {
			$_num = absint( $input['cache']['refresh-token'] );
			$_num = max( min( $_num, self::MAX_REFRESH_TOKEN_DAYS ), self::MIN_REFRESH_TOKEN_DAYS );
			$_num = floor( $_num / self::STEP_REFRESH_TOKEN_DAYS ) * self::STEP_REFRESH_TOKEN_DAYS;
			$cache['refresh-token'] = $_num;
		}

		$indexed_id = (int) ( $current_settings['indexed_id'] ?? 0 );

		return compact( 'accounts', 'cache', 'indexed_id' );

	}


	private static function _object_merge_recursive( $obj1, $obj2, $to_array = false ) {

		if( is_null( $obj2 ) ) {
			return $obj1;
		}

		if( ! is_array( $obj2 ) && ! is_object( $obj2 ) ) {
			return $obj2;
		}

		$obj1 = (object) $obj1;
		$obj2 = (object) $obj2;

		$new_obj = new \stdClass;

		foreach( $obj1 as $key => $value ) {
			$new_obj->$key = self::_object_merge_recursive( $value, $obj2->$key ?? null, $to_array );
		}

		foreach( $obj2 as $key => $value ) {
			if( isset( $new_obj->$key ) ) {
				continue;
			}
			$new_obj->$key = self::_object_merge_recursive( null, $value, $to_array );
		}

		$is_vector_array = true;
		foreach( $new_obj as $key => $value ) {
			if( (string) (int) $key !== (string) $key ) {
				$is_vector_array = false;
				break;
			}
		}

		if( $is_vector_array ) {
			$_vector_array = (array) $new_obj;
			ksort( $_vector_array );
			$new_obj = array_values($_vector_array );
		}

		if( $to_array ) {
			return (array) $new_obj;
		}

		return $new_obj;

	}


	// Display a notification if an account has been de-authenticated
	public function display_notices() {

		// Settings
		$settings = get_option( self::SETTING_NAME, array() );

		// Accounts
		if( empty( $settings['accounts'] ) || ! is_array( $settings['accounts'] ) ) {
			$accounts = array();
		} else {
			$accounts = $settings['accounts'];
		}

		$group_name = self::PREFIX. 'admin-notices';

		foreach( $accounts as $account ) {
			if( empty( $account->disabled_access_token ) || empty( $account->id ) ) {
				continue;
			}
			add_settings_error(
				$group_name,
				self::PREFIX. 'disabled_access_token_'. $account->id,
				sprintf(
					__( 'Access token authorization for user "%2$s @%3$s" (ID = %1$d) has been revoked. Please re-authenticate from the settings screen.', 'still-be-combine-social-photos' ).
					'<br><a href="'. esc_url( admin_url( 'options-general.php?page='. self::PREFIX. 'setting-page&tab=tab_sb-csp-ss-accounts' ) ). '">'. __( 'Settings Screen', 'still-be-combine-social-photos' ). '</a>',
					$account->id,
					$account->me->name ?? $account->me->username,
					$account->me->username
				),
				'warning'
			);
		}

		// Exclude pages that display automatically
		global $hook_suffix, $parent_file;
		if( 'options-general.php' === $parent_file ||
		      'export-personal-data.php' === $hook_suffix ||
		      'erase-personal-data.php'  === $hook_suffix ) {
			return;
		}

		settings_errors( $group_name );

	}


	// 
	private function _add_settings() {

		// Link Account Section
		add_action( 'admin_init', function() {

			add_settings_section(
				self::PREFIX. 'ss-accounts',   // Section ID (Slug)
				esc_html__( 'Link Account', 'still-be-combine-social-photos' ),   // Section Title
				array( $this, 'render_sd_accounts' ),   // Rederer
				self::PREFIX. 'setting-page'   // Rendering Page
			);

			add_settings_field(
				self::PREFIX. 'sf-linked-accounts',   // Field ID (Slug)
				esc_html__( 'Linked Accounts', 'still-be-combine-social-photos' ),   // Field Label
				array( $this, 'render_linked_accounts' ),   // Rederer
				self::PREFIX. 'setting-page',   // Rendering Page
				self::PREFIX. 'ss-accounts',   // Section
				array()   // Arguments for Renderer Function
			);

		}, 12 );

		// Cache Section
		add_action( 'admin_init', function() {

			add_settings_section(
				self::PREFIX. 'ss-cache',   // Section ID (Slug)
				esc_html__( 'Cache', 'still-be-combine-social-photos' ),   // Section Title
				array( $this, 'render_sd_cache' ),   // Rederer
				self::PREFIX. 'setting-page'   // Rendering Page
			);

			add_settings_field(
				self::PREFIX. 'sf-cache-lifetime',   // Field ID (Slug)
				esc_html__( 'Cache Lifetime', 'still-be-combine-social-photos' ),   // Field Label
				array( $this, 'render_cache_lifetime' ),   // Rederer
				self::PREFIX. 'setting-page',   // Rendering Page
				self::PREFIX. 'ss-cache',   // Section
				array()   // Arguments for Renderer Function
			);

			add_settings_field(
				self::PREFIX. 'sf-refresh-token',   // Field ID (Slug)
				esc_html__( 'Refresh Token', 'still-be-combine-social-photos' ),   // Field Label
				array( $this, 'render_refresh_token' ),   // Rederer
				self::PREFIX. 'setting-page',   // Rendering Page
				self::PREFIX. 'ss-cache',   // Section
				array()   // Arguments for Renderer Function
			);

		}, 14 );

		// Others Section
		add_action( 'admin_init', function() {

			add_settings_section(
				self::PREFIX. 'ss-others',   // Section ID (Slug)
				esc_html__( 'Others', 'still-be-combine-social-photos' ),   // Section Title
				array( $this, 'render_sd_others' ),   // Rederer
				self::PREFIX. 'setting-page'   // Rendering Page
			);

			add_settings_field(
				self::PREFIX. 'sf-reset-settings',   // Field ID (Slug)
				esc_html__( 'Reset Settings', 'still-be-combine-social-photos' ),   // Field Label
				array( $this, 'render_reset_settings' ),   // Rederer
				self::PREFIX. 'setting-page',   // Rendering Page
				self::PREFIX. 'ss-others',   // Section
				array()   // Arguments for Renderer Function
			);

			add_settings_field(
				self::PREFIX. 'sf-unlock-database',   // Field ID (Slug)
				esc_html__( 'Unlock Database', 'still-be-combine-social-photos' ),   // Field Label
				array( $this, 'render_unlock_database' ),   // Rederer
				self::PREFIX. 'setting-page',   // Rendering Page
				self::PREFIX. 'ss-others',   // Section
				array()   // Arguments for Renderer Function
			);

		}, 20 );

	}


	public function render_sd_accounts() {

		echo '<p>'.  esc_html__( 'Link with your account to get Instagram posts or others.', 'still-be-combine-social-photos' ). '</p>';

		echo '<dl class="api-types">';

		echo   '<div>';
		echo     '<dt>Instagram Basic Display API</dt>';
		echo     '<dd>'. esc_html__( 'You can only get the posts from linked accounts.', 'still-be-combine-social-photos' );
		echo     '<br>'. esc_html__( 'Requirement: Instagram Account', 'still-be-combine-social-photos' ). '</dd>';
		echo   '</div>';

		echo   '<div>';
		echo     '<dt>Instagram Graph API</dt>';
		echo     '<dd>'. esc_html__( 'You can get the posts from linked accounts, the posts from other pro accounts (Business, Media-Creator) and posts related of hashtags.', 'still-be-combine-social-photos' );
		echo     '<br>'. esc_html__( 'Requirement: Facebook Account connected Instagram Account', 'still-be-combine-social-photos' ). '</dd>';
		echo   '</div>';

		echo '</dl>';

		echo '<p class="note"><small>'.  esc_html__( '* It is possible to get your own posts in the Instagram Graph API as well.', 'still-be-combine-social-photos' ). '</small>';
		echo '<br><small>'.              esc_html__( '* If you want to automatically get your profile picture or your account name (not username) from Instagram, you must also select the Instagram Graph API.', 'still-be-combine-social-photos' ). '</small></p>';

	}


	public function render_linked_accounts() {

		echo '<p>'. esc_html__( 'List of linked accounts.', 'still-be-combine-social-photos' );
		echo ' '.   esc_html__( 'Check expire date (which are automatically refresh), remove linked accounts, and set profile pictures for this website.', 'still-be-combine-social-photos' ). '</p>';

		$accounts = (array) ( $this->settings['accounts'] ?? array() );

		// for Debug
		echo '<pre style="display: none;">';
		var_dump($accounts);
		echo '</pre>';

		$now = time();
		$available_accounts_count = 0;

		// Linked Accounts Table
		echo '<div class="scroll-table-wrapper"><table class="accounts-table">';

		echo   '<thead>';
		echo     '<th>ID</th>';
		echo     '<th>'. esc_html__( 'API Type', 'still-be-combine-social-photos' ). '</th>';
		echo     '<th>'. esc_html__( 'Profile Picture', 'still-be-combine-social-photos' ). '</th>';
		echo     '<th>'. esc_html__( 'User Name', 'still-be-combine-social-photos' ). '</th>';
		echo     '<th>'. esc_html__( 'Account Name', 'still-be-combine-social-photos' ). '</th>';
		echo     '<th>'. esc_html__( 'Account Type', 'still-be-combine-social-photos' ). '</th>';
		echo     '<th>'. esc_html__( 'Media Count', 'still-be-combine-social-photos' ). '</th>';
		echo     '<th>'. esc_html__( 'Expires In', 'still-be-combine-social-photos' ). '</th>';
		echo     '<th>'. esc_html__( 'Actions', 'still-be-combine-social-photos' ). '</th>';
		echo   '</thead>';

		echo   '<tbody class="account-list">';

		foreach( $accounts as $account ) {

			if( ! isset( $account->id ) ) {
				continue;
			}

			$account_type = empty( $account->me->account_type ) ? __( 'Getting...', 'still-be-combine-social-photos' ) :
			                                                      ( 'PERSONAL' !== $account->me->account_type ? 'Pro' : 'Personal' );

			if( empty( $account->profile_picture_url ) ) {
				$picture = $account->me->profile_picture_url ?? 'data:image/gif;base64,R0lGODlhAQABAGAAACH5BAEKAP8ALAAAAAABAAEAAAgEAP8FBAA7';
			} elseif( 0 === strpos( $account->profile_picture_url, 'data' ) ) {
				$picture = $account->me->profile_picture_url ?? $account->profile_picture_url;
			} else {
				$picture = $account->profile_picture_url;
			}
			$attachment_id = $account->profile_picture_id  ?? 0;
			$is_noimage    = 0 === strpos( $picture, 'data' );

			$attr_class = [ 'account-row' ];
			$attr_error = [];

			if( empty( $account->api_type ) ) {
				$attr_class[] = 'no-api-type';
				$attr_error[] = esc_html__( 'Unknown API type.', 'still-be-combine-social-photos' );
			}

			if( empty( $account->token->token ) ) {
				$attr_class[] = 'no-token';
				$attr_error[] = esc_html__( 'Access Token is not Found.', 'still-be-combine-social-photos' );
			}

			if( empty( $account->token->expire ) || empty( $account->token->type ) ) {
				$attr_class[] = 'no-token-info';
				$attr_error[] = esc_html__( 'Unknown access token expires in or token type.', 'still-be-combine-social-photos' );
			}

			if( ! empty( $attr_error ) ) {
				$attr_class[] = 'has-errors';
			}

			$refresh_token = true;
			if( empty( $account->token->token ) || ( ! empty( $account->token->expire ) && ( empty( $account->token->created ) || 24 * 3600 > $now - $account->token->created ) ) ) {
				$refresh_token = false;
			}
			if( empty( $account->me ) ) {
				$refresh_token = true;
			}

			$has_expiration = true;
			if( isset( $account->token->expire ) ) {
				if( 0 == $account->token->expire ) {
					$has_expiration = false;
					$expire = __( 'No expiration date', 'still-be-combine-social-photos' );
				} else {
					$expire = wp_date( __( 'Y-m-d H:i P (e)', 'still-be-combine-social-photos' ), absint( $account->token->expire ) );
				}
			} else {
				$expire = __( 'Getting...', 'still-be-combine-social-photos' );
			}
			$expire = '<span>'. $expire. '</span>';

			$is_disabled_token = ! empty( $account->disabled_access_token );
			if( $is_disabled_token ) {
				$expire = '<strong style="color: #c00;">'. __( 'Authentication has been deactivated', 'still-be-combine-social-photos' ). '</strong>';
			}

			$allow_tags_expire_string = array(
				'span'   => array(),
				'strong' => array(
					'style' => array(),
					'class' => array(),
				),
			);

			echo '<tr class="'. esc_attr( implode( ' ', $attr_class ) ). '" data-error="'. esc_attr( implode( '&#10;', $attr_error ) ). '" data-row="'. esc_attr( $available_accounts_count ). '">';
			echo   '<td class="account-id">';
			echo     '<span>'. esc_html( $account->id ). '</span>';
			echo     '<input type="hidden" name="'. esc_attr(  self::SETTING_NAME. "[accounts][{$available_accounts_count}][id]" ). '" value="'. esc_attr( $account->id ). '">';
			echo    '</td>';
			echo   '<td class="account-apitype">';
			echo     '<span>'. esc_html( $account->api_type ?? __( 'Unknown', 'still-be-combine-social-photos' ) ). '</span>';
			echo   '</td>';
			echo   '<td class="account-picture">';
			echo     '<figure class="'. esc_attr( $is_noimage ? 'image-selector no-image' : 'image-selector' ). '">';
			echo       '<img src="'. esc_attr( $picture ). '" class="image-thumbnail" loading="lazy">';
			echo       '<div class="button-wrapper">';
			echo         '<button type="button" class="image-selector-button" data-row="'. esc_attr( "account_{$available_accounts_count}" ). '">';
			echo            ( $is_noimage ? esc_html__( 'Select', 'still-be-combine-social-photos' ) : esc_html__( 'Chnage', 'still-be-combine-social-photos' ) );
			echo         '</button>';
			echo         '<button type="button" class="image-remove-button">'. esc_html__( 'Delete', 'still-be-combine-social-photos' ). '</button>';
			echo       '</div>';
			echo       '<input type="hidden" name="'. esc_attr(  self::SETTING_NAME. "[accounts][{$available_accounts_count}][profile_picture_url]" ). '" value="'. esc_attr( $attachment_id ? $picture : '' ). '" class="image-url">';
			echo       '<input type="hidden" name="'. esc_attr(  self::SETTING_NAME. "[accounts][{$available_accounts_count}][profile_picture_id]"  ). '" value="'. esc_attr( $attachment_id                 ). '" class="image-id">';
			echo     '</figure>';
			echo   '</td>';
			echo   '<td class="account-username">';
			echo     '<span>'. esc_html( $account->me->username ?? __( 'Getting...', 'still-be-combine-social-photos' ) ). '</span>';
			echo   '</td>';
			echo   '<td class="account-name">';
			echo     '<input type="text" name="'. esc_attr(  self::SETTING_NAME. "[accounts][{$available_accounts_count}][name]" ). '" value="'. esc_attr( $account->name ?? ( $account->me->name ?? ( $account->me->username ?? '' ) ) ). '">';
			echo   '</td>';
			echo   '<td class="account-type">';
			echo     '<span>'. esc_html( $account_type ). '</span>';
			echo   '</td>';
			echo   '<td class="account-media">';
			echo     '<span>'. esc_html( $account->me->media_count ?? __( 'Getting...', 'still-be-combine-social-photos' ) ). '</span>';
			echo   '</td>';
			echo   '<td class="account-expire">';
			echo     wp_kses( $expire, $allow_tags_expire_string );
			echo   '</td>';
			echo   '<td class="account-actions">';
			echo     '<div class="button-wrapper">';
			if( $is_disabled_token ) {
				echo   '<button type="button" class="action-button" data-action="reauth-token" data-row="'. esc_attr( $available_accounts_count ). '">';
				echo      esc_html__( 'Reauth Token', 'still-be-combine-social-photos' );
				echo   '</button>';
			}
			if( $has_expiration ) {
				echo   '<button type="button" class="action-button" data-action="refresh-token" data-row="'. esc_attr( $available_accounts_count ). '"'. ( $refresh_token ? '' : ' disabled title="Fresh Token"' ). '>';
				echo      esc_html__( 'Refresh Token', 'still-be-combine-social-photos' );
				echo   '</button>';
			}
			echo       '<button type="button" class="action-button" data-action="remove-account" data-row="'. esc_attr( $available_accounts_count ). '">';
			echo          esc_html__( 'Remove', 'still-be-combine-social-photos' );
			echo       '</button>';
			echo     '</div>';
			echo   '</td>';
			echo '</tr>';

			++$available_accounts_count;

		}

		$no_accounts_style = 1 > $available_accounts_count ? '' : 'display: none;';
		echo '<tr id="no_accounts" style="'. esc_attr( $no_accounts_style ). '"><td colspan="9" style="font-size: 0.8em; line-height: 2.4em;">'. esc_html__( '-- No linked accounts --', 'still-be-combine-social-photos' ). '</td></tr>';

		echo   '</tbody>';

		echo '</table></div>';

		// Link Another Account Button
		echo '<button type="button" class="button-add-temp" id="open_auth_button" title="'. esc_attr( __( 'Link another account', 'still-be-combine-social-photos' ) ). '" data-locale="'. esc_attr( get_locale() ). '">';
		echo   '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 512"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path fill="currentColor" d="M488 191.1h-152l.0001 51.86c.0001 37.66-27.08 72-64.55 75.77c-43.09 4.333-79.45-29.42-79.45-71.63V126.4l-24.51 14.73C123.2 167.8 96.04 215.7 96.04 267.5L16.04 313.8c-15.25 8.751-20.63 28.38-11.75 43.63l80 138.6c8.875 15.25 28.5 20.5 43.75 11.75l103.4-59.75h136.6c35.25 0 64-28.75 64-64c26.51 0 48-21.49 48-48V288h8c13.25 0 24-10.75 24-24l.0001-48C512 202.7 501.3 191.1 488 191.1zM635.7 154.5l-79.95-138.6c-8.875-15.25-28.5-20.5-43.75-11.75l-103.4 59.75h-62.57c-37.85 0-74.93 10.61-107.1 30.63C229.7 100.4 224 110.6 224 121.6l-.0004 126.4c0 22.13 17.88 40 40 40c22.13 0 40-17.88 40-40V159.1h184c30.93 0 56 25.07 56 56v28.5l80-46.25C639.3 189.4 644.5 169.8 635.7 154.5z"/></svg>';
		echo   '<span>'. esc_html__( 'Link another account', 'still-be-combine-social-photos' ). '</span>';
		echo   '<span>'. esc_html__( '(Open an authorization window)', 'still-be-combine-social-photos' ). '</span>';
		echo '</button>';

		// Manually Set an Account
		echo '<p class="manyually-set-account-popup-open">';
		echo   '<a href="" id="manyually_set_account_popup_open">'. esc_html__( 'Manually set an account', 'still-be-combine-social-photos' ). '</a>';
		echo '</p>';

		// Notes
		echo '<p class="note"><small>'. esc_html__( '* ID is a serial number in the system.', 'still-be-combine-social-photos' ). '</small>';
		echo '<br><small>'.             esc_html__( '* Media Count may be at odds with the latest information.', 'still-be-combine-social-photos' ). '</small></p>';

		// New Account Row Template
		echo '<template id="temp_account_row">';
		echo   '<tr class="account-row" data-row="{{row}}">';
		echo     '<td class="account-id">';
		echo       '<span>{{id}}</span>';
		echo       '<input type="hidden" name="'. esc_attr(  self::SETTING_NAME. "[accounts][{{row}}][id]" ). '" value="{{id}}">';
		echo      '</td>';
		echo     '<td class="account-apitype">';
		echo       '<span>{{api_type}}</span>';
		echo     '</td>';
		echo     '<td class="account-picture">';
		echo       '<figure class="image-selector">';
		echo         '<img src="{{profile_picture_url}}" class="image-thumbnail" loading="lazy">';
		echo         '<div class="button-wrapper">';
		echo           '<button type="button" class="image-selector-button" data-row="account_{{row}}">'. esc_html__( 'Select', 'still-be-combine-social-photos' ). '</button>';
		echo           '<button type="button" class="image-remove-button">'. esc_html__( 'Delete', 'still-be-combine-social-photos' ). '</button>';
		echo         '</div>';
		echo         '<input type="hidden" name="'. esc_attr(  self::SETTING_NAME. "[accounts][{{row}}][profile_picture_url]" ). '" value="{{profile_picture_url}}" class="image-url">';
		echo         '<input type="hidden" name="'. esc_attr(  self::SETTING_NAME. "[accounts][{{row}}][profile_picture_id]"  ). '" value="0" class="image-id">';
		echo       '</figure>';
		echo     '</td>';
		echo     '<td class="account-username">';
		echo       '<span>{{me.username}}</span>';
		echo     '</td>';
		echo     '<td class="account-name">';
		echo       '<input type="text" name="'. esc_attr(  self::SETTING_NAME. "[accounts][{{row}}][name]" ). '" value="{{me.name}}">';
		echo     '</td>';
		echo     '<td class="account-type">';
		echo       '<span>{{account_type}}</span>';
		echo     '</td>';
		echo     '<td class="account-media">';
		echo       '<span>{{me.media_count}}</span>';
		echo     '</td>';
		echo     '<td class="account-expire">';
		echo       '<span>{{expire_string}}</span>';
		echo     '</td>';
		echo     '<td class="account-actions">';
		echo       '<div class="button-wrapper">';
		echo         '<button type="button" class="action-button" data-action="refresh-token" data-row="{{row}}" disabled title="Fresh Token">';
		echo            esc_html__( 'Refresh Token', 'still-be-combine-social-photos' );
		echo         '</button>';
		echo         '<button type="button" class="action-button" data-action="remove-account" data-row="{{row}}">';
		echo            esc_html__( 'Remove', 'still-be-combine-social-photos' );
		echo         '</button>';
		echo       '</div>';
		echo     '</td>';
		echo   '</tr>';
		echo '</template>';

		// Manually Set Another Account Template
		echo '<template id="temp_popup_manually_account">';
		echo   '<div class="popup-wrapper waiting-screen">';
		echo     '<div class="popup-container">';
		echo       '<h2 class="title">'. esc_html__( 'Manually set an account', 'still-be-combine-social-photos' ). '</h2>';
		echo       '<p>'.  esc_html__( 'If the information is not automatically reflected from the authentication screen, please enter the required information below.', 'still-be-combine-social-photos' );
		echo       '<br>'. esc_html__( 'You can also register your own access token.', 'still-be-combine-social-photos' ). '</p>';
		echo       '<form id="manually_set_account">';
		echo         '<dl class="manually-set-account">';
		echo           '<div>';
		echo             '<dt>'. esc_html__( 'API Type', 'still-be-combine-social-photos' ). '</dt>';
		echo             '<dd>';
		echo               '<label><input type="radio" name="data[api]" value="ig_basic_display" checked><span>Instagram Basic Display API</span></label>';
		echo               '<label><input type="radio" name="data[api]" value="ig_graph"><span>Instagram Graph API</span></label>';
		echo             '</dd>';
		echo           '</div>';
		echo           '<div>';
		echo             '<dt>'. esc_html__( 'Access Token', 'still-be-combine-social-photos' ). '</dt>';
		echo             '<dd>';
		echo               '<textarea name="data[token]" required></textarea>';
		echo               '<p class="note">';
		echo                 '<small>'. esc_html__( '* For Graph API, enter a page token that has not expired..', 'still-be-combine-social-photos' ). '</small><br>';
		echo               '</p>';
		echo             '</dd>';
		echo           '</div>';
		echo           '<div>';
		echo             '<dt>'. esc_html__( 'Authorization type', 'still-be-combine-social-photos' ). '</dt>';
		echo             '<dd>';
		echo               '<label><input type="radio" name="data[type]" value="bearer" checked><span>Bearer</span></label>';
		echo               '<label><input type="radio" name="data[type]" value="basic" disabled><span>Basic</span></label>';
		echo             '</dd>';
		echo           '</div>';
		echo           '<div>';
		echo             '<dt>'. esc_html__( 'Expires in', 'still-be-combine-social-photos' ). '</dt>';
		echo             '<dd>';
		echo               '<input type="number" name="data[expire]">';
		echo               '<p class="note">';
		echo                 '<small>'. esc_html__( '* Enter a timestamp (integer value).', 'still-be-combine-social-photos' ). '</small><br>';
		echo                 '<small>'. esc_html__( '* If unknown, leave empty and refresh the token from the linked account table after registration.', 'still-be-combine-social-photos' ). '</small>';
		echo               '</p>';
		echo             '</dd>';
		echo           '</div>';
		echo         '</dl>';
		echo       '</form>';
		echo       '<div class="submit-button-wrapper">';
		echo         '<button type="button" id="manually_set_account_submit">'. esc_html__( 'Register manually', 'still-be-combine-social-photos' ). '</button>';
		echo       '</div>';
		echo     '</div>';
		echo   '</div>';
		echo '</template>';

	}


	public function render_sd_cache() {

		// Nothing to do

	}


	public function render_cache_lifetime() {

		echo '<p>'.  esc_html__( 'Set a data cache time.', 'still-be-combine-social-photos' );
		echo '<br>'. esc_html__( 'The longer this time is set, the lower the load on your server, but it will take for the latest data to be reflected.', 'still-be-combine-social-photos' ). '</p>';

		echo '<div class="item-row">';
		echo   '<input type="number" name="'. esc_attr(  self::SETTING_NAME. '[cache][data-lifetime]' ). '"';
		echo     ' min="'.   esc_attr(  self::MIN_CACHE_LIFETIME ). '"';
		echo     ' max="'.   esc_attr(  self::MAX_CACHE_LIFETIME ). '"';
		echo     ' step="'.  esc_attr( self::STEP_CACHE_LIFETIME ). '"';
		echo     ' value="'. esc_attr( $this->settings['cache']['data-lifetime'] ?? self::DEFAULT_CACHE_LIFETIME ). '"';
		echo     ' onchange="if(this.value*1<this.min*1){this.value=this.min;}if(this.value*1>this.max*1){this.value=this.max;}this.value=~~(this.value/this.step)*this.step;">';
		echo   '<span class="unit">'. esc_html__( 'sec.', 'still-be-combine-social-photos' ). '</span>';
		echo '</div>';

		echo '<p class="note">';
		echo   '<small>'. sprintf( esc_html__( '* Set between %d sec. (%s) and %d sec. (%s).', 'still-be-combine-social-photos' ), self::MIN_CACHE_LIFETIME, self::_human_readable_time_string( self::MIN_CACHE_LIFETIME ), self::MAX_CACHE_LIFETIME, self::_human_readable_time_string( self::MAX_CACHE_LIFETIME ) ). '</small>';
		echo '</p>';

	}


	public function render_refresh_token() {

		echo '<p>'.  esc_html__( 'Set the number of days remaining on the expiration date at which the token should be refreshed.', 'still-be-combine-social-photos' );
		echo '<br>'. esc_html__( 'The refresh is automatically performed in the background when the site is accessed at being less the remaining days.', 'still-be-combine-social-photos' ). '</p>';

		echo '<div class="item-row">';
		echo   '<input type="number" name="'. esc_attr(  self::SETTING_NAME. '[cache][refresh-token]' ). '"';
		echo     ' min="'.   esc_attr(  self::MIN_REFRESH_TOKEN_DAYS ). '"';
		echo     ' max="'.   esc_attr(  self::MAX_REFRESH_TOKEN_DAYS ). '"';
		echo     ' step="'.  esc_attr( self::STEP_REFRESH_TOKEN_DAYS ). '"';
		echo     ' value="'. esc_attr( $this->settings['cache']['refresh-token'] ?? self::DEFAULT_REFRESH_TOKEN_DAYS ). '"';
		echo     ' onchange="if(this.value*1<this.min*1){this.value=this.min;}if(this.value*1>this.max*1){this.value=this.max;}this.value=~~(this.value/this.step)*this.step;">';
		echo   '<span class="unit">'. esc_html__( 'days', 'still-be-combine-social-photos' ). '</span>';
		echo '</div>';

		echo '<p class="note">';
		echo   '<small>'. sprintf( esc_html__( '* Set between %d days and %d days.', 'still-be-combine-social-photos' ), self::MIN_REFRESH_TOKEN_DAYS, self::MAX_REFRESH_TOKEN_DAYS ). '</small>';
		echo '</p>';

	}


	public function render_sd_others() {

		// Nothing to do

	}


	public function render_reset_settings() {

		echo '<p>'. esc_html__( 'Delete all settings. This action cannot be undone.', 'still-be-combine-social-photos' ). '</p>';

		echo '<div class="item-row">';
		echo   '<button type="button" id="reset_settings_button">'.  esc_html__( 'Reset settings', 'still-be-combine-social-photos' ). '</button>';
		echo '</div>';

	}


	public function render_unlock_database() {

		echo '<p>' . esc_html__( 'Unlock the database.', 'still-be-combine-social-photos' );
		echo '<br>'. esc_html__( 'If the database is still locked after several tries, make sure that no access-token updates, etc. are running to release the lock.', 'still-be-combine-social-photos' ). '</p>';

		echo '<div class="item-row">';
		echo   '<button type="button" id="unlock_database_button">'.  esc_html__( 'Unlock database', 'still-be-combine-social-photos' ). '</button>';
		echo '</div>';

	}


	public function ajax_set_auth_user( $id = 0 ) {

		// Nonce Check
		if( ! wp_verify_nonce( $_POST['_nonce'], 'sb-csp-setting-page' ) ) {
			header( 'Content-Type: application/json' );
			exit( json_encode( array(
				'ok'      => false,
				'code'    => 0,
				'message' => esc_html__( 'The page has expired. Please reload the page.', 'still-be-combine-social-photos' ),
			) ) );
		}

		$token = filter_input( INPUT_POST, 'data', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY );
		$type  = filter_input( INPUT_POST, 'api_type' );

		if( empty( $token ) || empty( $type ) || empty( $token['token'] ) ) {
			header( 'Content-Type: application/json' );
			exit( json_encode( array(
				'ok'      => false,
				'code'    => 0,
				'token'   => $token,
				'message' => esc_html__( 'Data is empty.', 'still-be-combine-social-photos' ),
			) ) );
		}

		$token = (object) $token;

		// Using API
		$selected_api_class = '';
		if( 'Basic Display API' === $type ) {
			$token->api         = 'ig_basic_display';
			$selected_api_class = __NAMESPACE__. '\Basic_Display_API';
		}
		if( 'Graph API' === $type ) {
			$token->api         = 'ig_graph';
			$selected_api_class = __NAMESPACE__. '\Graph_API';
		}

		// Unknown API Type
		if( empty( $selected_api_class ) || ! class_exists( $selected_api_class ) ) {
			header( 'Content-Type: application/json' );
			exit( json_encode( array(
				'ok'      => false,
				'code'    => 2,
				'setting' => $current_settings,
				'token'   => $token,
				'apitype' => $type,
				'message' => esc_html__( 'Unknown API type.', 'still-be-combine-social-photos' ),
			) ) );
		}

		// DB Lock
		$is_db_locked = ! update_option( self::PREFIX. 'db-setting-locked', true, 'no' );

		if( $is_db_locked ) {
			header( 'Content-Type: application/json' );
			exit( json_encode( array(
				'ok'      => false,
				'code'    => 4,
				'setting' => $current_settings,
				'token'   => $token,
				'apitype' => $type,
				'message' => esc_html__( 'Other update processes are in progress. Please try again later. If this message still appears after repeated attempts, please run "Unlock Database" from the "Others" tab of the Instagram Settings screen .', 'still-be-combine-social-photos' ),
			) ) );
		}

		$current_settings = (array) get_option( self::SETTING_NAME, array() );

		if( empty( $current_settings['accounts'] ) || ! is_array( $current_settings['accounts'] ) ) {
			$current_settings['accounts'] = array();
		}

		// Get Account Informations
		$me = $selected_api_class::get_me( $token->token, $token->page_id ?? null );
		if( empty( $me ) || ! ( isset( $me->id ) || isset( $me->username ) || isset( $me->media_count ) || isset( $me->account_type ) ) ) {
			update_option( self::PREFIX. 'db-setting-locked', false, 'no' );
			header( 'Content-Type: application/json' );
			exit( json_encode( array(
				'ok'      => false,
				'code'    => 6,
				'setting' => $current_settings,
				'token'   => $token,
				'message' => esc_html__( 'Access token authentication failed. Please check if the API type and token are correct and try again.', 'still-be-combine-social-photos' ),
			) ) );
		}

		// Current Indexed ID
		$indexed_id = isset( $current_settings['indexed_id'] ) ? absint( $current_settings['indexed_id'] ) : 0;
		if( empty( $id ) ) {
			++$indexed_id;
		}

		if( empty( $id ) ) {
			// Add an Athorized IG User
			array_unshift( $current_settings['accounts'], (object) array(
				'id'       => $indexed_id,
				'token'    => $token,
				'api_type' => $type,
				'me'       => $me,
			) );
		} else {
			foreach( $current_settings['accounts'] as $_index => $account ) {
				if( $id == $account->id ) {
					$current_settings['accounts'][ $_index ] = (object) array(
						'id'       => $id,
						'token'    => $token,
						'api_type' => $type,
						'me'       => $me,
					);
					break;
				}
			}
		}

		// Increase Indexed ID
		$current_settings['indexed_id'] = $indexed_id;

		// Update Settings Option
		$is_updated = update_option( self::SETTING_NAME, $current_settings );

		// Fail to Update
		if( ! $is_updated ) {
			update_option( self::PREFIX. 'db-setting-locked', false, 'no' );
			header( 'Content-Type: application/json' );
			exit( json_encode( array(
				'ok'      => false,
				'code'    => 4,
				'setting' => $current_settings,
				'token'   => $token,
				'message' => esc_html__( 'Failed to update data to database.', 'still-be-combine-social-photos' ),
			) ) );
		}

		// DB Unlock
		update_option( self::PREFIX. 'db-setting-locked', false, 'no' );

		// Expire String
		$expire_string = ! empty( $token->expire ) ?
		                     wp_date( __( 'Y-m-d H:i P (e)', 'still-be-combine-social-photos' ), absint( $token->expire ) ) :
		                     esc_html__( 'Unknown', 'still-be-combine-social-photos' );
		if( 0 === $token->expire || "0" === $token->expire ) {
			$expire_string = esc_html__( 'No expiration date', 'still-be-combine-social-photos' );
		}

		// Completed!!
		header( 'Content-Type: application/json' );
		exit( json_encode( array(
			'ok'      => true,
			'code'    => 99,
			'account' => array(
				'id'            => $indexed_id,
				'api_type'      => $type,
				'me'            => $me ?? null,
				'token'         => $token,
				'expire_string' => $expire_string,
			),
			'message' => esc_html__( 'Success!!', 'still-be-combine-social-photos' ),
		) ) );

	}


	public function ajax_reauth_user() {

		$id = filter_input( INPUT_POST, 'id' ) ?: 0;
		$id = absint( $id );

		if( empty( $id ) ) {
			header( 'Content-Type: application/json' );
			exit( json_encode( array(
				'ok'      => false,
				'code'    => 0,
				'token'   => $token,
				'message' => esc_html__( 'System ID is empty.', 'still-be-combine-social-photos' ),
			) ) );
		}

		$this->ajax_set_auth_user( $id );

	}


	public function ajax_refresh_token() {

		// Nonce Check
		if( ! wp_verify_nonce( $_GET['_nonce'], 'sb-csp-setting-page' ) ) {
			header( 'Content-Type: application/json' );
			exit( json_encode( array(
				'ok'      => false,
				'code'    => 0,
				'message' => esc_html__( 'The page has expired. Please reload the page.', 'still-be-combine-social-photos' ),
			) ) );
		}

		// Account ID
		$id = filter_input( INPUT_GET, 'account_id' );

		// Run
		header( 'Content-Type: application/json' );
		exit( json_encode( self::refresh_token( $id, true ) ) );

	}


	public static function refresh_token( $id, $force_refresh = false ) {

		$current_settings = (array) get_option( self::SETTING_NAME, array() );

		if( empty( $current_settings['accounts'] ) || ! is_array( $current_settings['accounts'] ) || ! is_numeric( $id ) ) {
			return (object) array(
				'ok'      => false,
				'code'    => 2,
				'message' => esc_html__( 'Account ID is not found.', 'still-be-combine-social-photos' ),
			);
		}

		$id = absint( $id );

		$account = null;
		$account_index = null;
		foreach( $current_settings['accounts'] as $_index => $_account ) {
			if( $id == $_account->id ) {
				$account = $_account;
				$account_index = absint( $_index );
				break;
			}
		}

		if( null === $account_index ) {
			return (object) array(
				'ok'      => false,
				'code'    => 3,
				'message' => esc_html__( 'This account is not authorized. (No account exists)', 'still-be-combine-social-photos' ),
			);
		}

		if( empty( $account->token->token ) ) {
			return (object) array(
				'ok'      => false,
				'code'    => 4,
				'message' => esc_html__( 'This account is not authorized. (No access token)', 'still-be-combine-social-photos' ),
			);
		}

		$now = time();

		if( ! empty( $account->token->expire ) && $now - 10 > $account->token->expire ) {
			return (object) array(
				'ok'      => false,
				'code'    => 6,
				'message' => esc_html__( 'This account is expired. You have to authorize again.', 'still-be-combine-social-photos' ),
			);
		}

		if( ! $force_refresh && isset( $account->token->created ) && $now - 24 * 3600 < $account->token->created ) {
			return (object) array(
				'ok'      => false,
				'code'    => 8,
				'message' => esc_html__( 'The access token is fresh.', 'still-be-combine-social-photos' ),
			);
		}

		// DB Lock
		$is_db_locked = ! update_option( self::PREFIX. 'db-setting-locked', true, 'no' );

		if( $is_db_locked ) {
			return (object) array(
				'ok'      => false,
				'code'    => 10,
				'message' => esc_html__( 'Other update processes are in progress. Please try again later.', 'still-be-combine-social-photos' ),
			);
		}

		// Using API
		$selected_api_class = '';
		$api_type           = '';
		if( 'Basic Display API' === $account->api_type ) {
			$selected_api_class = __NAMESPACE__. '\Basic_Display_API';
			$api_type           = 'ig_basic_display';
		}
		if( 'Graph API' === $account->api_type ) {
			$selected_api_class = __NAMESPACE__. '\Graph_API';
			$api_type           = 'ig_graph';
		}

		if( empty( $selected_api_class ) ) {
			update_option( self::PREFIX. 'db-setting-locked', false, 'no' );
			return (object) array(
				'ok'      => false,
				'code'    => 11,
				'apitype' => $account->api_type,
				'message' => esc_html__( 'Failed to refresh access token. Please try again.', 'still-be-combine-social-photos' ),
			);
		}

		$refreshed_token = $selected_api_class::refresh_token( $account );

		if( empty( $refreshed_token ) ) {
			update_option( self::PREFIX. 'db-setting-locked', false, 'no' );
			return (object) array(
				'ok'       => false,
				'code'     => 12,
				'message'  => esc_html__( 'Failed to refresh access token. Please try again.', 'still-be-combine-social-photos' ),
			);
		}

		if( empty( $refreshed_token->access_token ) || empty( $refreshed_token->token_type ) || empty( $refreshed_token->expires_in ) ) {
			update_option( self::PREFIX. 'db-setting-locked', false, 'no' );
			return (object) array(
				'ok'       => false,
				'code'     => 14,
				'response' => $json_refreshed_token,
				'message'  => esc_html__( 'Failed to refresh access token. Please try again.', 'still-be-combine-social-photos' ),
			);
		}

		// Update Token
		$now = time();
		$new_settings = $current_settings;
		$new_settings['accounts'][ $account_index ]->token = (object) array(
			'token'   => $refreshed_token->access_token,
			'type'    => $refreshed_token->token_type,
			'expire'  => $now + $refreshed_token->expires_in,
			'created' => $now,
			'api'     => $api_type,
		);

		// Get Account Informations
		$me = $selected_api_class::get_me( $refreshed_token->access_token );
		if( ! empty( $me ) ) {
			if( isset( $me->id ) || isset( $me->username ) || isset( $me->media_count ) || isset( $me->account_type ) ) {
				$new_settings['accounts'][ $account_index ]->me = $me;
			}
		}

		$result = update_option( self::SETTING_NAME, $new_settings );
		update_option( self::PREFIX. 'db-setting-locked', false, 'no' );

		// Return Result
		return (object) array(
			'ok'       => $result,
			'code'     => 99,
			'old'      => $current_settings,
			'new'      => $new_settings,
			'token'    => $new_settings['accounts'][ $account_index ]->token,
			'me'       => $me ?? null,
			'expire'   => wp_date( __( 'Y-m-d H:i P (e)', 'still-be-combine-social-photos' ), $new_settings['accounts'][ $account_index ]->token->expire ),
			'message'  => $result ?
			                esc_html__( 'Access token is refreshed!!', 'still-be-combine-social-photos' ) :
			                esc_html__( 'Either the access token has not changed or the refresh failed.', 'still-be-combine-social-photos' ),
		);

	}


	//
	public function ajax_reset_setting() {

		// Nonce Check
		if( ! wp_verify_nonce( $_POST['_nonce'], 'sb-csp-setting-page' ) ) {
			header( 'Content-Type: application/json' );
			exit( json_encode( array(
				'ok'      => false,
				'message' => esc_html__( 'The page has expired. Please reload the page.', 'still-be-combine-social-photos' ),
			) ) );
		}

		// Delete Setting Ooption
		$result = delete_option( self::SETTING_NAME );

		// Return
		header( 'Content-Type: application/json' );
		exit( json_encode( array(
			'ok'      => $result,
			'message' => $result ?
			               esc_html__( 'Settings reset!', 'still-be-combine-social-photos' ) :
			               esc_html__( 'Reset failed or setting does not exist.', 'still-be-combine-social-photos' ),
		) ) );

	}


	//
	public function ajax_unlock_database() {

		// Nonce Check
		if( ! wp_verify_nonce( $_POST['_nonce'], 'sb-csp-setting-page' ) ) {
			header( 'Content-Type: application/json' );
			exit( json_encode( array(
				'ok'      => false,
				'message' => esc_html__( 'The page has expired. Please reload the page.', 'still-be-combine-social-photos' ),
			) ) );
		}

		// Delete Setting Ooption
		$result = delete_option( self::PREFIX. 'db-setting-locked' );

		// Return
		header( 'Content-Type: application/json' );
		exit( json_encode( array(
			'ok'      => $result,
			'message' => $result ?
			               esc_html__( 'Unlocked Database!', 'still-be-combine-social-photos' ) :
			               esc_html__( 'Unlocking failed.', 'still-be-combine-social-photos' ),
		) ) );

	}


	private static function _human_readable_time_string( $seconds = 0 ) {

		$threashold = 3;
		$about      = esc_html__( 'about', 'still-be-combine-social-photos' );

		$transform  = array(
			esc_html__( 'sec.',  'still-be-combine-social-photos' ) => 1,
			esc_html__( 'min.',  'still-be-combine-social-photos' ) => 60,
			esc_html__( 'hours', 'still-be-combine-social-photos' ) => 3600,
			esc_html__( 'days',  'still-be-combine-social-photos' ) => 24 * 3600,
		);

		$num  = $seconds;
		$mod  = 0;
		$unit = esc_html__( 'sec.', 'still-be-combine-social-photos' );

		foreach( $transform as $_unit => $_sec ) {
			$_num = round( $seconds / $_sec );
			$_mod = $seconds % $_sec;
			if( $threashold > $_num ) {
				break;
			}
			$num  = $_num;
			$mod  = $_mod;
			$unit = $_unit;
		}

		return ( 0 < $mod ? $about. ' ': '' ). $num. $unit;

	}

}



