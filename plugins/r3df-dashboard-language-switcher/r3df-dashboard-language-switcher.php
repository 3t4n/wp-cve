<?php
/*
Plugin Name: 	R3DF - Dashboard Language Switcher
Description:    Set the admin language based on user choice
Plugin URI:		http://r3df.com/
Version: 		1.0.2
Text Domain:	r3df-dls
Domain Path: 	/lang/
Author:         R3DF
Author URI:     http://r3df.com
Author email:   plugin-support@r3df.com
Copyright: 		R-Cubed Design Forge
*/

/*  This work is a fork & complete refactor of "WP Native Dashboard" by: Heiko Rabe */
/*  Thank-you Heiko for creating and supporting the original plugin since 2009 */


/*  Copyright 2015 R-Cubed Design Forge

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


// TODO
// add check and admin message if no languages...
// remove embedded styles from legacy code
// is there a WP way to do: is_rtl_language()


//avoid direct calls to this file where wp core files not present
if ( ! function_exists( 'add_action' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	exit();
}

$r3df_dashboard_language_switcher = new R3DF_Dashboard_Language_Switcher();


/**
 * Class R3DF_Dashboard_Language
 *
 */
class R3DF_Dashboard_Language_Switcher {
	private $_locale = false;
	// options defaults
	private $_defaults = array(
		'version' => '1.0',
		'login_language_switcher' => false,
		'admin_toolbar_language_switcher' => true,
		'user_profile_language_switcher' => true,
		'translate_site_toobar' => true,
		'enable_locale_abbreviations' => false,
		'cleanup_on_uninstall' => true,
		'hide_language' => array(),
	);
	private $_options = array();

	/**
	 * Class constructor
	 *
	 */
	function __construct() {

		// get plugin options
		$this->_options = get_option( 'r3df_dashboard_language_switcher', $this->_defaults );

		// register plugin activation/deactivation hooks
		register_activation_hook( plugin_basename( __FILE__ ), array( &$this, 'activate_plugin' ) );

		// Add plugin text domain hook
		add_action( 'plugins_loaded', array( &$this, '_text_domain' ) );

		// remove admin locale for msls
		// **************************************************
		// msls asserts the admin locale over the locale setting in general settings
		add_action( 'plugins_loaded', array( &$this, 'remove_msls_locale' ) );

		// admin locale override setup
		add_filter( 'locale', array( &$this, 'filter_locale' ), PHP_INT_MAX - 1 );
		// locale fix, saves it in filter_locale (the global $locale) and restores after it is overwritten in wp-settings.php line 308
		add_action( 'after_setup_theme', array( &$this, 'restore_locale' ) );

		// Add plugins settings page
		add_action( 'admin_menu', array( $this, 'register_r3df_dls_settings_page' ) );
		add_action( 'admin_init', array( $this, 'r3df_dls_settings' ) );

		// Setup workaround to get translated front end toolbar
		if ( $this->_options['translate_site_toobar'] && ! is_admin() ) {
			add_action( 'init', array( &$this, 'setup_capture' ) );
		}
		$this->user_agent_is_r3df_dls = ( isset( $_SERVER['HTTP_USER_AGENT'] ) && 'R3DF_DASHBOARD_LANGUAGE' == $_SERVER['HTTP_USER_AGENT'] ) ? true : false;
		if ( $this->user_agent_is_r3df_dls ) {
			ob_start(); // begin buffering page to discard, we only want the frontend toolbar rendering which we capture separately
		}

		// Setup new method to get translated front end toolbar
		// Not fully working, post, media and page names are not translated (new menu and edit item)
		//add_action( 'admin_bar_menu', array( $this, 'switch_to_admin_locale' ), -1 );

		// LANGUAGE SWITCHER SETUP
		// *************************************************
		// Save locale submitted in language switcher
		add_action( 'wp_loaded', array( &$this, 'save_switcher_locale' ) );

		// load admin css and javascript
		add_action( 'admin_enqueue_scripts', array( $this, '_load_admin_scripts_and_styles' ) );

		// Toolbar selector setup

		// Could use this to add to start of admin as in original plugin
		//add_action( 'admin_bar_menu', array( $this, 'add_admin_bar_node' ) );
		// add switcher to end of admin toolbar (don't add to site toolbar)
		if ( is_admin() ) {
			add_action( 'wp_before_admin_bar_render', array( $this, 'add_admin_bar_node' ) );
			add_action( 'bp_adminbar_menus', array( &$this, 'bp_adminbar_switcher_menu' ), 1 );
		}

		// Login page selector setup
		if ( $this->_options['login_language_switcher'] ) {
			add_action( 'login_form', array( &$this, 'login_form_selector' ) );
			add_action( 'wp_login', array( &$this, 'process_wp_login' ), 10, 2 );
		}

		// User profile selector setup
		if ( $this->_options['user_profile_language_switcher'] ) {
			add_action( 'profile_personal_options', array( &$this, 'user_profile' ) );
			add_action( 'personal_options_update', array( &$this, 'user_profile_update' ) );
		}

		// Add action to embed login form language selector
		// To add the select box to a custom login implementation, add the following code into widget or theme code where required.
		/* <?php do_action( 'r3df_dls_login_selector' ); ?> */
		add_action( 'r3df_dls_login_selector', array( $this, 'login_form_selector' ) );
	}


	/* ****************************************************
	 * Locale functions
	 * ****************************************************/

	/**
	 * Restore the local right after it is overwritten in wp-settings.php line 308
	 * (set in locale filter function)
	 * action after_setup_theme
	 *
	 */
	function restore_locale(){
		global $locale;
		$locale = $this->_locale;
	}

	/**
	 * Remove the locale setting for msls
	 *
	 */
	function remove_msls_locale() {
		remove_filter( 'locale', array( 'MslsPlugin', 'set_admin_language' ) );
		add_action( 'msls_admin_language_section', array( $this, 'add_msls_note' ) );
	}

	/**
	 * Adds admin note to msls options about admin language
	 *
	 */
	function add_msls_note() { echo '<p class="r3df-alert">'.__( 'Admin language setting is being overridden by the plugin: ', 'r3df-dls' ) .'R3DF - Dashboard Language Switcher' . '</p>'; }


	/**
	 * Save user locale settings
	 *
	 * @param $locale
	 * @param $context
	 * @param $user_id
	 *
	 * @return array
	 *
	 */
	function set_user_locale( $locale, $context = 'switcher', $user_id = 0 ) {
		if ( empty( $user_id ) ) {
			$user_id = get_current_user_id();
		}

		$user_locale_settings = get_user_meta( $user_id, 'r3df_dashboard_language', true );

		switch ( $context ) {
			case 'profile':
				$user_locale_settings['0'] = $locale;
				break;
			case 'switcher':
			case 'login':
			default:
				$user_locale_settings[ get_current_blog_id() ] = $locale;
				break;
		}

		update_user_meta( $user_id, 'r3df_dashboard_language', $user_locale_settings );

		return $user_locale_settings;
	}

	/**
	 * Get user locale settings (with default of current site locale)
	 *
	 * @param $user_id
	 * @param $context
	 *
	 * @return string
	 *
	 */
	function get_user_locale( $user_id = false, $context = 'filter' ) {
		if ( ! $user_id ) {
			$user_id = get_current_user_id();
		}

		$user_local_settings = get_user_meta( $user_id, 'r3df_dashboard_language', true );

		switch ( $context ) {
			case 'switcher':
			case 'login':  // BLOOT is there a case for this being used?
				if ( isset( $user_local_settings[ get_current_blog_id() ] ) ) {
					return ( $user_local_settings[ get_current_blog_id() ] );
				} elseif ( isset( $user_local_settings['0'] ) ) {
					return ( $user_local_settings['0'] );
				} else {
					return ( get_locale() );
				}
				break;
			case 'profile':
				if ( isset( $user_local_settings['0'] ) ) {
					return ( $user_local_settings['0'] );
				} else {
					return ( get_locale() );
				}
				break;
			case 'filter':
			default:
				if ( isset( $user_local_settings[ get_current_blog_id() ] ) ) {
					return ( $user_local_settings[ get_current_blog_id() ] );
				} elseif ( isset( $user_local_settings['0'] ) ) {
					return ( $user_local_settings['0'] );
				} else {
					// can't use get_locale() for filter case -> will loop!
					return false;
				}
				break;
		}
	}

	/**
	 * Set the user's language choice
	 *
	 * @param $current_locale
	 *
	 * @return mixed|string
	 */
	function filter_locale( $current_locale ) {
		// Save locale from global $local so it can be restored after it is overwritten in wp-settings.php line 308
		// if filters are applied to locale, the filtered version of locale, not the base version gets set in line 308
		// Also used by add_lang_node()
		if ( false === $this->_locale ) {
			global $locale;
			$this->_locale = $locale;
		}

		$options_page = false;
		// need to check if we are on general settings page (need to disable admin locale if we are or locale setting gets messed up)
		if ( function_exists( 'get_current_screen' ) ) {
			$screen = get_current_screen();
			if ( ! empty( $screen ) ) {
				$options_page = 'options-general' == $screen->id;
			}
		}

		// see if we can get a valid user
		$user_id = 0;
		if ( function_exists( 'wp_get_current_user' ) ) {
			$user = wp_get_current_user();
			if ( is_object( $user ) ) {
				$user_id = $user->ID;
			}
		}

		if ( // if we are on options page  OR
			apply_filters( 'r3df_override_options_page_skip', $options_page ) ||
			// if none of the selectors are set (user can't control language)  OR
		    ! $this->_options['login_language_switcher'] && ! $this->_options['user_profile_language_switcher'] && ! $this->_options['admin_toolbar_language_switcher'] ||
		    // we don't have a user (can't get user settings to control language)  OR
			! $user_id ||
			// we're not in admin and it's not a request for the site toolbar
			! is_admin() && ! ( $this->_options['translate_site_toobar'] && $this->user_agent_is_r3df_dls ) ) {

			// bail...
			return $current_locale;
		}

		$user_locale = $this->get_user_locale( $user_id, 'filter' );

		if ( ! $user_locale ) {
			// No user local set, bail...
			return $current_locale;
		}

		// abort if .mo does not exist
		// BLOOT is this needed?? (it should revert to en_US anyways)
		if ( ( 'en_US' != $user_locale ) && ! @file_exists( WP_LANG_DIR . '/' . $user_locale . '.mo' ) ) {
			return $current_locale;
		}

		return $user_locale;
	}


	/**
	 * Saves admin locale choice, chosen in switcher
	 *
	 */
	function save_switcher_locale() {
		// BLOOT nonce check?
		if ( is_admin() && is_user_logged_in() && ! empty( $_GET['r3df-dashboard-language'] ) && in_array( $_GET['r3df-dashboard-language'], $this->get_installed_languages() ) ) {
			$user_id = get_current_user_id();
			if ( $user_id ) {
				$this->set_user_locale( $_GET['r3df-dashboard-language'], 'switcher', $user_id );
				// need to redirect to fully reset page, not everything gets properly translated otherwise
				$start = stripos( $_SERVER['REQUEST_URI'], 'r3df-dashboard-language=' );
				wp_redirect( $this->get_home_url( substr_replace( $_SERVER['REQUEST_URI'], '', $start - 1, 30 ) ) );
				exit;
			}
		}
	}

	/* ****************************************************
	 * Frontend Toolbar Capture Functions
	 * ****************************************************/
	// Original technique (does a http request to get a page, and captures toolbar)

	/**
	 *
	 */
	function setup_capture() {

		//front end admin bar handling
		if ( $this->_options['translate_site_toobar'] && ! is_admin() && is_user_logged_in() && ! $this->user_agent_is_r3df_dls ) {
			add_action( 'wp_before_admin_bar_render', array( &$this, 'start_capture_toolbar' ), 0 );
			add_action( 'wp_after_admin_bar_render', array( &$this, 'end_capture_toolbar_get_translated' ), 9999 );
		}
		if ( $this->_options['translate_site_toobar'] && ! is_admin() && is_user_logged_in() && $this->user_agent_is_r3df_dls ) {
			add_action( 'admin_bar_menu', array( &$this, 'on_admin_bar_page_lang' ), 999 );
			add_action( 'wp_before_admin_bar_render', array( &$this, 'start_capture_toolbar' ), 0 );
			add_action( 'wp_after_admin_bar_render', array( &$this, 'end_capture_toolbar_save' ), 9999 );
		}
	}

	/**
	 * Start capture of toolbar
	 *
	 */
	function start_capture_toolbar() {
		ob_start();
	}

	/**
	 * End capture of toolbar, display (return) and exit
	 *
	 */
	function end_capture_toolbar_save() {
		$frontend_toolbar = ob_get_clean(); // end and get buffering of toolbar
		ob_end_clean(); // end buffing of page, and discard
		echo $frontend_toolbar;  // return only toolbar (nested ob_start)
		exit;
	}

	/**
	 * End capture of toolbar, get translated version using http request
	 *
	 */
	function end_capture_toolbar_get_translated() {
		ob_end_clean();
		// BLOOT add an nonce to verify request?
		$cookies = array();
		foreach ( $_COOKIE as $key => $val ) {
			$cookie          = new WP_Http_Cookie( $key );
			$cookie->name    = $key;
			$cookie->value   = $val;
			$cookie->expires = mktime( 0, 0, 0, date( 'm' ), date( 'd' ) + 7, date( 'Y' ) ); // expires in 7 days
			$cookie->path    = '/';
			//$cookie->domain = get_site_url();
			$cookies[] = $cookie;
		}

		$requested_url = is_ssl() ? 'https://' : 'http://';
		$requested_url .= $_SERVER['HTTP_HOST'];
		$requested_url .= $_SERVER['REQUEST_URI'];

		$response = wp_remote_get(
			$requested_url,
			array(
				'sslverify'  => false,
				'cookies'    => $cookies,
				'user-agent' => 'R3DF_DASHBOARD_LANGUAGE',
			)
		);
		if ( ! is_object( $response ) && isset( $response['body'] ) ) {
			echo $response['body'];
		}
	}


	/**
	 * New technique, changes locale before and after rendering toolbar
	 * Not yet working...  (misses translation of post, media and page names)
	 *  - These are created much before the toolbar is rendered
	 *
	 * Changes locale to admin locale before toolbar is displayed
	 *
	 */
	function switch_to_admin_locale() {
		if ( ! is_admin() && function_exists( 'is_admin_bar_showing' ) && is_admin_bar_showing() && is_user_logged_in() ) {
			$user = wp_get_current_user();
			load_default_textdomain( $user->r3df_dashboard_language );
			add_action( 'wp_after_admin_bar_render', array( &$this, 'switch_to_site_locale' ), 9999 );
		}
	}

	/**
	 * Changes locale back to site locale after toolbar is displayed
	 *
	 */
	function switch_to_site_locale() {
		load_default_textdomain( $this->_locale );
	}


	/* ****************************************************
     * Toolbar Language Switcher Functions
     * ****************************************************/

	/**
	 * Renders html for admin toolbar language selector
	 *
	 */
	function add_admin_bar_node() {

		global $wp_admin_bar;

		if ( function_exists( 'is_admin_bar_showing' ) && is_admin_bar_showing() && $this->_options['admin_toolbar_language_switcher'] ) {

			$wp_admin_bar->add_node( array(
				'id'    => 'r3df-dls-selector',
				'title' => '<span>' . __( 'Dashboard Langauge', 'r3df-dls' ) . '</span>',
				//'href'  => add_query_arg( array( 'r3df-dashboard-language' => $loc ), $this->get_home_url( $_SERVER['REQUEST_URI'] ) ),
				'meta'  => array( 'class' => 'r3df-dls-selector' )
			) );

			$langs = $this->get_installed_languages();

			// need to add filters to get locale setting to set switcher properly since filter is normally blocked on the options page.
			add_filter( 'r3df_override_options_page_skip', array( $this, 'allow_locale_on_options_page' ) );
			$loc = get_locale();
			remove_filter( 'r3df_override_options_page_skip', array( $this, 'allow_locale_on_options_page' ) );

			if ( count( $langs ) > 1 ) {
				foreach ( $langs as $lang ) {
					if ( $lang != $loc && ! $this->_options['hide_language'][ $lang ] ) {
						$wp_admin_bar->add_node( array(
							'parent' => 'r3df-dls-selector',
							'id'     => 'r3df-dls-lang-' . $lang,
							'title'  => '<span class="flag-' . $lang . '" hreflang="' . $lang . '">' . $this->get_language_name( $lang ) . '</span>',
							'href'   => add_query_arg( array( 'r3df-dashboard-language' => $lang ), $this->get_home_url( $_SERVER['REQUEST_URI'] ) ),
							'meta'   => array( 'class' => 'r3df-dls-lang r3df-dls-lang-option' )
						) );
					}
				}
			}
		}
	}

	/**
	 * Function for filter override of locale skip on general options page
	 * - Needed to allow setting of current language on language selector in toolbar
	 *
	 * @param $skip_options_page
	 *
	 * @return bool
	 *
	 */
	function allow_locale_on_options_page( $skip_options_page ) {
		return false;
	}

	/**
	 *
	 */
	function bp_adminbar_switcher_menu() {
		$langs = $this->get_installed_languages();
		$loc   = get_locale();
		?>
		<li id="r3df-dls-lang-<?php echo $loc; ?>" class="r3df-dls-lang-option r3df-dls-lang-cur"><a href="#"><span><span class="flag-<?php echo $loc; ?>"><?php echo $this->get_language_name( $loc ); ?></span></span></a>
		<?php
		if ( count( $langs ) > 1 ) {
			echo '<ul>';
			foreach ( $langs as $lang ) {
				if ( $lang == $loc ) {
					continue;
				}
				?>
				<li id="r3df-dls-lang-<?php echo $lang; ?>" class="r3df-dls-lang-option"><a
						href="#"><span class="flag-<?php echo $lang; ?>"
				                       hreflang="<?php echo $lang; ?>"><?php echo $this->get_language_name( $lang ); ?></span></a>
				</li>
			<?php
			}
			echo '</ul>';
		} ?>
		</li>
		<?php
	}


	/* ****************************************************
     * Login language switcher functions
     * ****************************************************/

	/**
	 * Displays language chooser on user login page
	 *
	 */
	function login_form_selector() {
		?>
		<label for="r3df_dashboard_language"><?php _e( 'Language', 'r3df-dls' ); ?></label><br/>
		<select id="r3df_dashboard_language" name="r3df_dashboard_language" tabindex="30">
			<?php
			foreach ( $this->get_installed_languages() as $lang ) {
				if ( ! $this->_options['hide_language'][ $lang ] ) {
					echo '<option value="' . $lang . '"' . selected( get_locale(), $lang ) . '>' . $this->get_language_name( $lang ) . '</option>';
				}
			}
			?>
		</select>
		<br/><br/>
	<?php
	}

	/**
	 * Sets user language on login
	 *
	 * @param $user_name
	 * @param $user
	 *
	 */
	function process_wp_login( $user_name, $user ) {
		if ( ! empty( $_POST['r3df_dashboard_language'] ) && in_array( $_POST['r3df_dashboard_language'], $this->get_installed_languages() ) ) {
			$this->set_user_locale( $_POST['r3df_dashboard_language'], 'login', $user->ID );
		}
	}



	/* ****************************************************
     * User profile language switcher functions
     * ****************************************************/

	/**
	 * Displays language chooser on user profile page
	 *
	 * @param $user
	 *
	 */
	function user_profile( $user ) {
		?>
		<table class="form-table">
			<tbody>
			<tr valign="top">
				<th scope="row"><?php _e( 'Language', 'r3df-dls' ); ?></th>
				<td>
					<label>
						<select name="r3df_dashboard_language">
							<?php
							$user_locale = $this->get_user_locale( $user->ID, 'profile' );
							foreach ( $this->get_installed_languages() as $lang ) {
								if ( ! $this->_options['hide_language'][ $lang ] ) {
									echo '<option value="'.$lang.'"'. selected( $user_locale, $lang ) . '>' . $this->get_language_name( $lang ) . '</option>';
								}
							}
							?>
						</select>
						<br><small><?php _e( 'Select your preferred language for the dashboard.<br>This setting is overwritten by the toolbar switcher if enabled,<br>and the login selector if enabled.', 'r3df-dls' ); ?></small>
					</label>
				</td>
			</tr>
			</tbody>
		</table>
		<?php
	}


	/**
	 * Sets user language on user profile update
	 *
	 * @param $user_id
	 *
	 */
	function user_profile_update( $user_id ) {
		if ( ! empty( $_POST['r3df_dashboard_language'] ) && in_array( $_POST['r3df_dashboard_language'], $this->get_installed_languages() ) ) {
			$this->set_user_locale( $_POST['r3df_dashboard_language'], 'profile', $user_id );
		}
	}


	/*
	 * ****************************************************
	 * Settings Page & Functions
	 * ****************************************************/

	/**
	 * Register plugin settings page
	 *
	 */
	function register_r3df_dls_settings_page() {
		$my_admin_page = add_submenu_page( 'options-general.php', 'R3DF - Dashboard Language Switcher', 'Dashboard Language', 'manage_options', 'r3df-dashboard-language-switcher', array(
			$this,
			'r3df_dls_settings_page',
		) );
		add_action( 'load-'.$my_admin_page, array( $this, 'add_help_tabs' ) );
	}

	/**
	 * Settings page html content
	 *
	 */
	function r3df_dls_settings_page() { ?>
		<div class="wrap">
			<div id="icon-tools" class="icon32"></div>
			<h2><?php echo 'R3DF - Dashboard Language Switcher'; ?></h2>

			<form action="options.php" method="post">
				<?php settings_fields( 'r3df_dashboard_language_switcher' ); ?>
				<?php do_settings_sections( 'r3df_dls' ); ?>
				<input class="button button-primary" name="Submit" type="submit"
				       value="<?php esc_attr_e( 'Save Changes', 'r3df-dls' ); ?>"/>
			</form>
		</div>
	<?php }

	/**
	 * Add the settings
	 *
	 */
	function r3df_dls_settings() {
		// Option name in db
		register_setting( 'r3df_dashboard_language_switcher', 'r3df_dashboard_language_switcher', array( $this, 'r3df_dls_options_validate' ) );

		// Language selector settings
		add_settings_section( 'r3df_dls_selectors', __( 'Enable language selectors:', 'r3df-dls' ), array( $this, 'r3df_dls_selectors_form_section' ), 'r3df_dls' );
		add_settings_field( 'login_language_switcher', __( 'On WordPress logon screen:', 'r3df-dls' ), array( $this, 'login_language_switcher_form_item' ), 'r3df_dls', 'r3df_dls_selectors', array( 'label_for' => 'login_language_switcher' ) );
		add_settings_field( 'admin_toolbar_language_switcher', __( 'On dashboard toolbar:<br>(admin/back-end)', 'r3df-dls' ), array( $this, 'admin_toolbar_language_switcher_form_item' ), 'r3df_dls', 'r3df_dls_selectors', array( 'label_for' => 'admin_toolbar_language_switcher' ) );
		add_settings_field( 'user_profile_language_switcher', __( 'On <a href="profile.php" target="_blank">User Profile</a> pages:', 'r3df-dls' ), array( $this, 'user_profile_language_switcher_form_item' ), 'r3df_dls', 'r3df_dls_selectors', array( 'label_for' => 'user_profile_language_switcher' ) );

		add_settings_section( 'r3df_dls_options', __( 'General options:', 'r3df-dls' ), null, 'r3df_dls' );
		add_settings_field( 'translate_site_toobar', __( 'Translate frontend toolbar using selected backend language', 'r3df-dls' ), array( $this, 'translate_site_toobar_form_item' ), 'r3df_dls', 'r3df_dls_options', array( 'label_for' => 'translate_site_toobar' ) );
		add_settings_field( 'enable_locale_abbreviations', __( 'Add locale abbreviations after the language name', 'r3df-dls' ), array( $this, 'enable_locale_abbreviations_form_item' ), 'r3df_dls', 'r3df_dls_options', array( 'label_for' => 'enable_locale_abbreviations' ) );
		add_settings_field( 'cleanup_on_uninstall', __( 'Cleanup all settings at plugin uninstall', 'r3df-dls' ), array( $this, 'cleanup_on_uninstall_form_item' ), 'r3df_dls', 'r3df_dls_options', array( 'label_for' => 'cleanup_on_uninstall' ) );

		add_settings_section( 'r3df_dls_languages', __( 'Installed languages:', 'r3df-dls' ), null, 'r3df_dls' );
		$installed = $this->get_installed_languages();
		foreach ( $installed as $lang ) {
			add_settings_field( $lang, $this->get_language_name( $lang ), array(
				$this,
				'languages_form_item',
			), 'r3df_dls', 'r3df_dls_languages', array( 'label_for' => $lang, 'lang' => $lang ) );
		}
	}

	/**
	 * Validate the settings
	 *
	 * @param $input
	 *
	 * @return mixed
	 */
	function r3df_dls_options_validate( $input ) {
		$newinput['login_language_switcher'] = ( $input['login_language_switcher'] == 'true' ) ? true : false;
		$newinput['admin_toolbar_language_switcher'] = ( $input['admin_toolbar_language_switcher'] == 'true' ) ? true : false;
		$newinput['user_profile_language_switcher'] = ( $input['user_profile_language_switcher'] == 'true' ) ? true : false;

		$newinput['translate_site_toobar'] = ( $input['translate_site_toobar'] == 'true' ) ? true : false;
		$newinput['enable_locale_abbreviations'] = ( $input['enable_locale_abbreviations'] == 'true' ) ? true : false;
		$newinput['cleanup_on_uninstall'] = ( $input['cleanup_on_uninstall'] == 'true' ) ? true : false;

		$newinput['hide_language'] = array();
		if ( ! empty( $input['hide_language'] ) ) {
			foreach ( $input['hide_language'] as $language => $hide ) {
				$newinput['hide_language'][ $language ] = ( 'true' == $hide ) ? true : false;
			}
		}
		return $newinput;
	}

	/**
	 * Settings page html content - login_language_switcher
	 *
	 * @param $args
	 *
	 */
	function r3df_dls_selectors_form_section( $args ) {
		echo __( 'Choose the locations to display a language selector.', 'r3df-dls' );
	}


	/**
	 * Settings page html content - r3df_dls_selectors section
	 *
	 * @param $args
	 *
	 */
	function login_language_switcher_form_item( $args ) {
		echo '<input type="checkbox" id="login_language_switcher" name="r3df_dashboard_language_switcher[login_language_switcher]"'. checked( $this->_options['login_language_switcher'], true, false ) . 'value="true" >' . __( 'Yes', 'r3df-dls' );
	}

	/**
	 * Settings page html content - admin_toolbar_language_switcher
	 *
	 * @param $args
	 *
	 */
	function admin_toolbar_language_switcher_form_item( $args ) {
		echo '<input type="checkbox" id="admin_toolbar_language_switcher" name="r3df_dashboard_language_switcher[admin_toolbar_language_switcher]"'. checked( $this->_options['admin_toolbar_language_switcher'], true, false ) . 'value="true" >' . __( 'Yes', 'r3df-dls' );
	}

	/**
	 * Settings page html content - user_profile_language_switcher
	 *
	 * @param $args
	 *
	 */
	function user_profile_language_switcher_form_item( $args ) {
		echo '<input type="checkbox" id="user_profile_language_switcher" name="r3df_dashboard_language_switcher[user_profile_language_switcher]"'. checked( $this->_options['user_profile_language_switcher'], true, false ) . 'value="true" >' . __( 'Yes', 'r3df-dls' );
	}


	/**
	 * Settings page html content - translate_site_toobar
	 *
	 * @param $args
	 *
	 */
	function translate_site_toobar_form_item( $args ) {
		echo '<input type="checkbox" id="translate_site_toobar" name="r3df_dashboard_language_switcher[translate_site_toobar]"'. checked( $this->_options['translate_site_toobar'], true, false ) . 'value="true" >' . __( 'Yes', 'r3df-dls' );
	}

	/**
	 * Settings page html content - enable_locale_abbreviations
	 *
	 * @param $args
	 *
	 */
	function enable_locale_abbreviations_form_item( $args ) {
		echo '<input type="checkbox" id="enable_locale_abbreviations" name="r3df_dashboard_language_switcher[enable_locale_abbreviations]"'. checked( $this->_options['enable_locale_abbreviations'], true, false ) . 'value="true" >' . __( 'Yes', 'r3df-dls' );
	}

	/**
	 * Settings page html content - cleanup_on_uninstall
	 *
	 * @param $args
	 *
	 */
	function cleanup_on_uninstall_form_item( $args ) {
		echo '<input type="checkbox" id="cleanup_on_uninstall" name="r3df_dashboard_language_switcher[cleanup_on_uninstall]"'. checked( $this->_options['cleanup_on_uninstall'], true, false ) . 'value="true" >' . __( 'Yes', 'r3df-dls' );
	}


	/**
	 * Settings page html content - languages
	 *
	 * @param $args
	 *
	 */
	function languages_form_item( $args ) {
		echo '<input type="checkbox" id="r3df_dashboard_language_switcher[hide_language][' . $args['lang'] . ']"
				name="r3df_dashboard_language_switcher[hide_language][' . $args['lang'] . ']"' . checked( $this->_options['hide_language'][ $args['lang'] ], true, false ) . 'value="true" >' . __( 'Hide on selectors', 'r3df-dls' );
		echo $this->is_rtl_language( $args['lang'] ) ? '<br>' . __( 'RTL language', 'r3df-dls' ) : '';
	}


	/* ****************************************************
	 * Help tab functions
	 * ****************************************************/

	/**
	 * Add help tabs
	 *
	 */
	function add_help_tabs() {
		$screen = get_current_screen();
		$screen->add_help_tab(array(
			'title' => __( 'Options', 'r3df-dls' ),
			'id' => 'options',
			'content' => '',
			'callback' => array( $this, 'help_options' )
		));
		$screen->add_help_tab( array(
			'title' => __( 'Custom login forms', 'r3df-dls' ),
			'id' => 'custom',
			'content' => '',
			'callback' => array( $this, 'help_custom' )
		));
	}

	/**
	 *
	 */
	function help_options() {
		?>
		<h2><?php echo 'R3DF - Dashboard Language Switcher'; ?></h2>
		<h3><?php echo __( 'Options', 'r3df-dls' ); ?></h3>
		<p><?php echo __( 'TBD', 'r3df-dls' ); ?></p>
		<p style="margin-top: 50px;padding-top:10px; border-top: solid 1px #ccc;">
			<a href="http://wordpress.org/extend/plugins/r3df-dashboard-language-switcher/" target="_blank"><?php echo __( 'Plugin Directory', 'r3df-dls' ) ?></a> |
			<a href="http://wordpress.org/extend/plugins/r3df-dashboard-language-switcher/changelog/" target="_blank"><?php echo __( 'Change Logs', 'r3df-dls' ) ?></a>
			<span class="alignright">&copy; 2015 <?php echo __( 'by', 'r3df-dls' ) ?> <a href="http://r3df.com/" target="_blank">R3DF</a></span>
		</p>
		<?php
	}

	/**
	 *
	 */
	function help_custom() {
		?>
		<h2><?php echo 'R3DF - Dashboard Language Switcher'; ?></h2>
		<h3><?php echo __( 'Action call for plugins or themes', 'r3df-dls' ); ?></h3>
		<p>
			<?php echo __( 'If you would like to add the select box to your own login implementation, add the following code into your widget or theme code where required.', 'r3df-dls' ); ?>
		</p>
		<code>
		&lt;?php
			do_action( 'r3df_dls_login_selector' );
		?&gt;
		</code>
		<?php
	}


	/* ****************************************************
	 * Utility functions
     * ****************************************************/

	/**
	 * Return home URL as appropriate for network or single site...
	 *
	 * @param $url
	 *
	 * @return string
	 *
	 */
	function get_home_url( $url ) {
		if ( is_multisite() ) {
			return network_home_url( $url );
		} else {
			return home_url( $url );
		}
	}

	/**
	 * Return installed languages
	 *
	 * @return array
	 *
	 */
	function get_installed_languages() {
		$languages = get_available_languages();
		$languages[] = 'en_US';
		sort( $languages );

		return $languages;
	}

	/**
	 * Return "human readable" name for a language locale
	 *
	 * @param $locale
	 *
	 * @return string
	 *
	 */
	function get_language_name( $locale ) {

		// Get names using concepts from wp_dropdown_languages in I10n.php
		require_once( ABSPATH . 'wp-admin/includes/translation-install.php' );
		$translations = wp_get_available_translations();
		$translations['en_US'] = array( 'language' => 'en_US', 'native_name' => 'English (United States)' );
		foreach ( $translations as $translation ) {
			$language_names[ $translation['language'] ] = $translation['native_name'];
		}

		$name = isset( $language_names[ $locale ] ) ? $language_names[ $locale ] : __( '-n.a.-', 'r3df-dls' );
		$abbr = ( $this->_options['enable_locale_abbreviations'] ? "&nbsp;<i>($locale)</i>" : '' );

		return "<b>$name</b>$abbr";
	}

	/**
	 * Display the language on the toolbar
	 *
	 */
	function add_lang_node() {
		global $wp_admin_bar;
		$wp_admin_bar->add_menu( array(
			'id'     => 'csl-current-locale',
			'parent' => 'top-secondary',
			'title'  => '<i style="font-size: 10px;">' . __( 'Language', 'r3df-dls' ) . ': </i>' . $this->get_language_name( $this->_locale ),
			'meta'   => array( 'class' => '' ),
		) );
	}

	/**
	 * Test for rtl language
	 *
	 * @param $locale
	 *
	 * @return bool
	 *
	 */
	function is_rtl_language( $locale ) {
		$rtl = array( 'ar', 'ckb', 'fa', 'he', 'ur', 'ug' );
		return in_array( array_shift( explode( '_', $locale ) ), $rtl );
	}

	/**
	 * Plugin language file loader
	 *
	 */
	function _text_domain() {
		// Load language files - files must be r3df-dls-xx_XX.mo
		load_plugin_textdomain( 'r3df-dls', false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
	}

	/**
	 * Admin scripts and styles loader
	 *
	 * @param $hook
	 *
	 */
	function _load_admin_scripts_and_styles( $hook ) {
		//if ( 'edit.php' != $hook ) {
		//	return;
		//}
		global $text_direction;

		// Get the plugin version (added to js file loaded to clear browser caches on change)
		$plugin = get_file_data( __FILE__, array( 'Version' => 'Version' ) );

		// Register and enqueue the css files
		if ( 'rtl' == $text_direction ) {
			wp_register_style( 'r3df_dls_admin_style_rtl', plugins_url( '/css/admin_style-rtl.css', __FILE__ ), false, $plugin['Version'] );
			wp_enqueue_style( 'r3df_dls_admin_style_rtl' );
		} else {
			wp_register_style( 'r3df_dls_admin_style', plugins_url( '/css/admin_style.css', __FILE__ ), false, $plugin['Version'] );
			wp_enqueue_style( 'r3df_dls_admin_style' );
		}
	}


	/* ****************************************************
	 * Activate and deactivate functions
	 * ****************************************************/

	/**
	 * Initialize options and abort with error on insufficient requirements
	 *
	 */
	function activate_plugin() {
		global $wp_version;
		$version_error = array();
		if ( ! version_compare( $wp_version, '4.1', '>=' ) ) {
			$version_error['WordPress Version'] = array( 'required' => '4.1', 'found' => $wp_version );
		}
		//if ( ! version_compare( phpversion(), '4.4.3', '>=' ) ) {
		//	$error['PHP Version'] = array( 'required' => '4.4.3', 'found' => phpversion() );
		//}
		if ( 0 != count( $version_error ) ) {
			$current = get_option( 'active_plugins' );
			array_splice( $current, array_search( plugin_basename( __FILE__ ), $current ), 1 );
			update_option( 'active_plugins', $current );
			if ( 0 != count( $version_error ) ) {
				echo '<table>';
				echo '<tr class="r3df-header"><td><strong>'.__( 'Plugin can not be activated.', 'r3df-mli' ) . '</strong></td><td> | '.__( 'required', 'r3df-mli' ) . '</td><td> | '.__( 'actual', 'r3df-mli' ) . '</td></tr>';
				foreach ( $version_error as $key => $value ) {
					echo '<tr><td>'.$key.'</td><td align=\"center\"> &gt;= <strong>' . $value['required'] . '</strong></td><td align="center"><span class="r3df-alert">' . $value['found'] . '</span></td></tr>';
				}
				echo '</table>';
			}
			exit();
		}
	}
}

