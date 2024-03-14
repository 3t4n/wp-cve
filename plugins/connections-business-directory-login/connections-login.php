<?php
/**
 * An extension for the Connections plugin which adds login content box and a widget for a single entry page.
 *
 * @package   Connections Business Directory Extension - Login
 * @category  Extension
 * @author    Steven A. Zahm
 * @license   GPL-2.0+
 * @link      https://connections-pro.com
 * @copyright 2023 Steven A. Zahm
 *
 * @wordpress-plugin
 * Plugin Name:       Connections Business Directory Extension - Login
 * Plugin URI:        https://connections-pro.com
 * Description:       An extension for the Connections plugin which adds login content box and a login widget to Connections.
 * Version:           3.4
 * Requires at least: 6.0
 * Requires PHP:      7.4
 * Author:            Steven A. Zahm
 * Author URI:        https://connections-pro.com
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       connections_login
 * Domain Path:       /languages
 */

if ( ! class_exists( 'Connections_Login' ) ) {

	final class Connections_Login {

		// Define version.
		const VERSION = '3.4';

		/**
		 * @var string The absolute path this file.
		 *
		 * @since 2.3
		 */
		private $file = '';

		/**
		 * @var string The URL to the plugin's folder.
		 *
		 * @since 2.3
		 */
		private $url = '';

		/**
		 * @var string The absolute path to this plugin's folder.
		 *
		 * @since 2.3
		 */
		private $path = '';

		/**
		 * @var string The basename of the plugin.
		 *
		 * @since 2.3
		 */
		private $basename = '';

		public function __construct() {

			$this->file     = __FILE__;
			$this->url      = plugin_dir_url( $this->file );
			$this->path     = plugin_dir_path( $this->file );
			$this->basename = plugin_basename( $this->file );

			self::defineConstants();
			self::loadDependencies();

			/**
			 * This should run on the `plugins_loaded` action hook. Since the extension loads on the
			 * `plugins_loaded` action hook, load immediately.
			 */
			cnText_Domain::register(
				'connections_login',
				$this->basename,
				'load'
			);

			// Add the business hours option to the admin settings page.
			// This is also required, so it'll be rendered by $entry->getContentBlock( 'login_form' ).
			add_filter( 'cn_content_blocks', array( __CLASS__, 'settingsOption' ) );

			// Add the action that'll be run when calling $entry->getContentBlock( 'login_form' ) from within a template.
			add_action( 'cn_entry_output_content-login_form', array( __CLASS__, 'block' ), 10, 3 );

			// Enqueue the scripts.
			add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueueScripts' ) );

			// Register the widget.
			add_action( 'widgets_init', array( 'CN_Login_Form_Widget', 'register' ) );

			// Register the shortcode.
			add_action( 'init', array( \Connections_Directory\Shortcode\Login_Form::class, 'add' ) );
			// add_action( 'init', array( \Connections_Directory\Shortcode\Request_Reset_Password::class, 'add' ) );
			// add_action( 'init', array( \Connections_Directory\Shortcode\Reset_Password::class, 'add' ) );
			// add_action( 'init', array( \Connections_Directory\Shortcode\User_Register::class, 'add' ) );
			add_action( 'init', array( \Connections_Directory\Shortcode\User_Link::class, 'add' ) );
			add_action( 'init', array( \Connections_Directory\Shortcode\User_Property::class, 'add' ) );
		}

		/**
		 * Define the constants.
		 *
		 * @access  private
		 * @static
		 * @since  1.0
		 *
		 * @return void
		 */
		private static function defineConstants() {

			define( 'CNL_DIR_NAME', plugin_basename( dirname( __FILE__ ) ) );
			define( 'CNL_BASE_NAME', plugin_basename( __FILE__ ) );
			define( 'CNL_PATH', plugin_dir_path( __FILE__ ) );
			define( 'CNL_URL', plugin_dir_url( __FILE__ ) );
		}

		/**
		 * The widget.
		 *
		 * @access private
		 * @since  1.0
		 * @static
		 *
		 * @return void
		 */
		private static function loadDependencies() {

			require_once CNL_PATH . 'includes/class.widgets.php';
			require_once CNL_PATH . 'includes/Shortcode/Login_Form.php';
			// require_once CNL_PATH . 'includes/Shortcode/Request_Reset_Password.php';
			// require_once CNL_PATH . 'includes/Shortcode/Reset_Password.php';
			// require_once CNL_PATH . 'includes/Shortcode/User_Register.php';
			require_once CNL_PATH . 'includes/Shortcode/User_Link.php';
			require_once CNL_PATH . 'includes/Shortcode/User_Property.php';
		}

		/**
		 * Add the custom meta as an option in the content block settings in the admin.
		 * This is required for the output to be rendered by $entry->getContentBlock().
		 *
		 * @access private
		 * @since  1.0
		 * @static
		 * @param array $blocks An associative array containing the registered content block settings options.
		 *
		 * @return array
		 */
		public static function settingsOption( $blocks ) {

			$blocks['login_form'] = __( 'Login Form', 'connections_login' );

			return $blocks;
		}

		/**
		 * Register and enqueue CSS and javascript files for frontend.
		 *
		 * @access private
		 * @since  2.0
		 * @static
		 */
		public static function enqueueScripts() {

			// If SCRIPT_DEBUG is set and TRUE load the non-minified JS files, otherwise, load the minified files.
			$min = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

			wp_enqueue_style( 'cn-login-public', CNL_URL . "assets/css/cn-login-user$min.css", array(), self::VERSION );
		}

		/**
		 * The list of supported tokens that can be replaced.
		 *
		 * @access private
		 * @since  2.0
		 * @static
		 *
		 * @return array
		 */
		public static function supportedTokens() {

			$tokens = array(
				'%username%',
				'%userid%',
				'%firstname%',
				'%lastname%',
				'%nickname%',
				'%display_name%',
				'%name%',
				'%avatar%',
				'%admin_url%',
				'%login_url%',
				'%logout_url%',
				'%profile_url%',
			);

			if ( function_exists( 'bp_loggedin_user_domain' ) ) $tokens[] = '%buddypress_profile_url%';
			if ( function_exists( 'bbp_get_user_profile_url' ) ) $tokens[] = '%bbpress_profile_url%';
			if ( function_exists( 'bbp_user_topics_created_url' ) ) $tokens[] = '%bbpress_topics_created_url%';
			if ( function_exists( 'bbp_get_user_replies_created_url' ) ) $tokens[] = '%bbpress_replies_created_url%';
			if ( function_exists( 'bbp_get_favorites_permalink' ) ) $tokens[] = '%bbpress_favorites_url%';
			if ( function_exists( 'bbp_get_subscriptions_permalink' ) ) $tokens[] = '%bbpress_subscriptions_url%';

			/**
			 * Filter the add or remove the supported token.
			 *
			 * @since 2.0
			 *
			 * @param array $tokens
			 */
			return apply_filters( 'cn_login_supported_tokens', $tokens );
		}

		/**
		 * Replace tokens with the corresponding string.
		 *
		 * @access private
		 * @since  2.0
		 * @static
		 *
		 * @param string       $string
		 * @param array|string $context The context in which the tokens should be replaced.
		 *                              Default: array( 'string', 'url' )
		 *                              Valid: string | url | array( 'string', 'url' )
		 * @param array        $atts {
		 *     Optional. An array of arguments.
		 *
		 *     @type string $login_url  The WordPress log in URL.
		 *                              Default: URL returned from `wp_login_url()`
		 *     @type string $logout_url The WordPress logout URL.
		 *                              Default: URL returned from `wp_logout_url()`
		 * }
		 *
		 * @return string
		 */
		public static function replaceTokens( $string, $context = array( 'string', 'url' ), $atts = array() ) {

			$defaults = array(
				'login_url'  => wp_login_url(),
				'logout_url' => wp_logout_url(),
			);

			$atts = wp_parse_args( $atts, $defaults );

			$user = wp_get_current_user();

			if ( ! is_array( $context ) ) {

				$context = array( $context );
			}

			if ( $user ) {

				if ( in_array( 'string', $context ) ) {

					$string = str_replace(
						array(
							'%username%',
							'%userid%',
							'%firstname%',
							'%lastname%',
							'%nickname%',
							'%display_name%',
							'%name%',
							'%avatar%',
						),
						array(
							$user->user_login,
							$user->ID,
							$user->first_name,
							$user->last_name,
							$user->nickname,
							$user->display_name,
							trim( $user->first_name . ' ' . $user->last_name ),
							get_avatar( $user->ID, apply_filters( 'cn_login_avatar_size', 38 ) ),
						),
						$string
					);
				}

				if ( in_array( 'url', $context ) ) {

					$string = str_replace(
						array(
							'%profile_url%',
						),
						array(
							get_edit_profile_url( $user->ID ),
						),
						$string
					);

					// BuddyPress.
					if ( function_exists( 'bp_loggedin_user_domain' ) ) {

						$string = str_replace(
							array( '%buddypress_profile_url%' ),
							array( bp_loggedin_user_domain() ),
							$string
						);
					}

					// bbPress.
					if ( function_exists( 'bbp_get_user_profile_url' ) ) {

						$string = str_replace(
							array( '%bbpress_profile_url%' ),
							array( bbp_get_user_profile_url( $user->ID ) ),
							$string
						);
					}

					if ( function_exists( 'bbp_user_topics_created_url' ) ) {

						$string = str_replace(
							array( '%bbpress_topics_created_url%' ),
							array( bbp_get_user_topics_created_url( $user->ID ) ),
							$string
						);
					}

					if ( function_exists( 'bbp_get_user_replies_created_url' ) ) {

						$string = str_replace(
							array( '%bbpress_replies_created_url%' ),
							array( bbp_get_user_replies_created_url( $user->ID ) ),
							$string
						);
					}

					if ( function_exists( 'bbp_get_favorites_permalink' ) ) {

						$string = str_replace(
							array( '%bbpress_favorites_url%' ),
							array( bbp_get_favorites_permalink( $user->ID ) ),
							$string
						);
					}

					if ( function_exists( 'bbp_get_subscriptions_permalink' ) ) {

						$string = str_replace(
							array( '%bbpress_subscriptions_url%' ),
							array( bbp_get_subscriptions_permalink( $user->ID ) ),
							$string
						);
					}
				}
			}

			if ( in_array( 'url', $context ) ) {

				//$logout_redirect = wp_logout_url( empty( $this->instance['logout_redirect_url'] ) ? $this->current_url( 'nologout' ) : $this->instance['logout_redirect_url'] );

				$string = str_replace(
					array( '%admin_url%', '%logout_url%', '%login_url%' ),
					array(
						untrailingslashit( admin_url() ),
						apply_filters( 'cn_login_logout_url', $atts['logout_url'] ),
						apply_filters( 'cn_login_login_url', $atts['login_url'] ),
					),
					$string
				);
			}

			$string = do_shortcode( $string );

			/**
			 * Filter the string or URL.
			 *
			 * @since 2.0
			 *
			 * @param string       $string
			 * @param array|string $context The context in which the tokens should be replaced.
			 *                              Default: array( 'string', 'url' )
			 *                              Valid: string | url | array( 'string', 'url' )
			 * @param array        $atts {
			 *     Optional. An array of arguments.
			 *
			 *     @type string $login_url  The WordPress log in URL.
			 *                              Default: URL returned from `wp_login_url()`
			 *     @type string $logout_url The WordPress logout URL.
			 *                              Default: URL returned from `wp_logout_url()`
			 * }
			 */
			return apply_filters( 'cn_login_replace_tokens', $string, $context, $atts );
		}

		/**
		 * Renders the login form content block.
		 *
		 * Called by the cn_meta_output_field-login_form action in cnOutput->getMetaBlock().
		 *
		 * @internal
		 * @since 1.0
		 *
		 * @param cnEntry    $entry    An instance of the cnEntry object.
		 * @param array      $atts     The shortcode $atts array.
		 * @param cnTemplate $template An instance of the cnTemplate object.
		 */
		public static function block( $entry, $atts = array(), $template = false ) {

			if ( is_user_logged_in() ) return;

			$form = new \Connections_Directory\Shortcode\Login_Form( $atts );
			$form->render();
		}
	}

	/**
	 * Start up the extension.
	 *
	 * @access public
	 * @since 1.0
	 *
	 * @return Connections_Login
	 */
	function Connections_Login() {

		return new Connections_Login();
	}

	add_action( 'Connections_Directory/Loaded', 'Connections_Login' );
}
