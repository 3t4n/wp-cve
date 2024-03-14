<?php
/**
 * Plugin Name: Gmedia Gallery
 * Plugin URI: http://wordpress.org/extend/plugins/grand-media/
 * Description: Gmedia Gallery - powerful media library plugin for creating beautiful galleries and managing files.
 * Version: 1.22.0
 * Author: Rattus
 * Author URI: https://codeasily.com/
 * Requires at least: 5.3.0
 * Tested up to: 6.2
 * Text Domain: grand-media
 * Domain Path: /lang
 */

/*
		Copyright (C) 2011  Rattus  (email : gmediafolder@gmail.com)

		This program is free software; you can redistribute it and/or
		modify it under the terms of the GNU General Public License
		as published by the Free Software Foundation; either version 2
		of the License, or (at your option) any later version.

		This program is distributed in the hope that it will be useful,
		but WITHOUT ANY WARRANTY; without even the implied warranty of
		MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
		GNU General Public License for more details.

		You should have received a copy of the GNU General Public License
		along with this program; if not, write to the Free Software
		Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

// Stop direct call.
defined( 'ABSPATH' ) || die( 'No script kiddies please!' );

if ( ! class_exists( 'Gmedia' ) ) {
	/**
	 * Class Gmedia
	 */
	class Gmedia {

		public $version       = '1.22.0';
		public $dbversion     = '1.8.0';
		public $minium_WP     = '5.3';
		public $options       = '';
		public $do_module     = array();
		public $import_styles = array();
		public $shortcode     = array();

		public function __construct() {

			// Stop the plugin if we missed the requirements.
			if ( ! $this->required_version() ) {
				return;
			}

			// Get some constants first.
			include_once dirname( __FILE__ ) . '/config.php';
			$this->load_options();
			$this->define_constant();
			$this->define_tables();

			// Load global libraries.
			require_once dirname( __FILE__ ) . '/inc/core.php';
			require_once dirname( __FILE__ ) . '/inc/db.connect.php';
			require_once dirname( __FILE__ ) . '/inc/permalinks.php';

			//if ( $this->options['debug_mode'] ) {
			//	@ini_set( 'display_errors', true );
			//	error_reporting( E_ALL );
			//} else {
			//	@ini_set( 'display_errors', true ); //Ensure that Fatal errors are displayed.
			//	error_reporting( E_CORE_ERROR | E_COMPILE_ERROR | E_ERROR | E_USER_ERROR | E_RECOVERABLE_ERROR );
			//}

			$this->plugin_name = plugin_basename( __FILE__ );

			add_filter( 'cron_schedules', array( &$this, 'gmedia_cron_schedules' ) );

			// Init options & tables during activation & deregister init option.
			register_activation_hook( $this->plugin_name, array( &$this, 'activate' ) );
			register_deactivation_hook( $this->plugin_name, array( &$this, 'deactivate' ) );

			// Register an uninstall hook to remove all tables & option automatic.
			//register_uninstall_hook( $this->plugin_name, array(__CLASS__, 'uninstall' ) );

			add_action( 'wp_enqueue_scripts', array( &$this, 'register_scripts_frontend' ), 20 );

			add_action( 'admin_enqueue_scripts', array( &$this, 'register_scripts_backend' ), 8 );

			add_action( 'wpmu_new_blog', array( &$this, 'new_blog' ), 10, 6 );

			// Start this plugin once all other plugins are fully loaded.
			add_action( 'plugins_loaded', array( &$this, 'start_plugin' ) );

			add_action( 'deleted_user', array( &$this, 'reassign_media' ), 10, 2 );

			add_action( 'init', array( &$this, 'gmedia_post_type' ), 0 );
			add_action( 'init', array( &$this, 'compatibility' ), 11 );
			//add_action('init', array(&$this, 'gm_schedule_update_checks'), 0);

			// register widget.
			add_action( 'widgets_init', array( &$this, 'register_gmedia_widget' ) );

			add_action( 'gmedia_app_cronjob', array( &$this, 'gmedia_app_cronjob' ) );
			add_action( 'gmedia_modules_update', array( &$this, 'gmedia_modules_update' ) );

			//Add some message on the plugins page.
			//add_action( 'after_plugin_row', array(&$this, 'check_message_version') );
			//Add some links on the plugins page.
			add_filter( 'plugin_row_meta', array( &$this, 'add_plugin_links' ), 10, 2 );
			add_action( 'admin_footer', array( &$this, 'add_plugin_feedback' ) );

		}

		public function start_plugin() {

			$this->load_dependencies();

			// Load the language file.
			$this->load_textdomain();

			require_once dirname( __FILE__ ) . '/inc/functions.php';

			// Check for upgrade.
			$this->upgrade();

			require_once dirname( __FILE__ ) . '/inc/hashids.php';
			require_once dirname( __FILE__ ) . '/inc/shortcodes.php';

			// Load the admin panel or the frontend functions.
			if ( is_admin() ) {

				// Pass the init check or show a message.
				if ( get_option( 'gmediaActivated' ) ) {
					add_action( 'init', array( &$this, 'gmedia_after_activation' ) );
				}

				// Pass the init check or show a message.
				if ( get_option( 'gmediaInitCheck' ) ) {
					add_action( 'admin_notices', array( &$this, 'admin_notices' ) );
				}

				add_action( 'admin_notices', array( &$this, 'ask_for_help' ) );
				add_action( 'admin_init', function () {
					if ( isset( $_GET['ask_for_help'] ) && 'hide' === $_GET['ask_for_help'] ) {
						update_option( 'gmedia_ask_for_help', 'hide' );
						wp_redirect( remove_query_arg( 'ask_for_help' ) );
						exit();
					}
				} );

				require_once dirname( __FILE__ ) . '/admin/functions.php';

				require_once dirname( __FILE__ ) . '/admin/class.processor.php';

			} else {

				// Add the script and style files.
				//add_action('wp_enqueue_scripts', array(&$this, 'load_scripts'), 4);

				require_once dirname( __FILE__ ) . '/inc/frontend.filters.php';

				// Add a version number to the header.
				add_action( 'wp_head', array( &$this, 'gmedia_head_meta' ) );
				add_action( 'wp_footer', array( &$this, 'load_module_scripts' ) );

			}

			add_action( 'gmedia_head', array( &$this, 'gmedia_head_meta' ) );
			add_action( 'gmedia_head', array( &$this, 'load_scripts' ), 2 );
			add_action( 'gmedia_head', 'wp_print_head_scripts', 9 );
			add_action( 'gmedia_enqueue_scripts', array( &$this, 'load_module_scripts' ) );

			add_action( 'gmedia_head', array( &$this, 'print_import_styles' ) );
			add_action( 'gmedia_footer', array( &$this, 'print_import_styles' ) );

		}

		public function gmedia_head_meta() {
			$lk         = strtolower( $this->options['license_key'] );
			$db_version = get_option( 'gmediaDbVersion' );
			echo "\n" . '<!-- <meta name="GmediaGallery" version="' . esc_attr( $this->version . '/' . $db_version ) . '" license="' . esc_attr( $lk ) . '" /> -->' . "\n";
		}

		public function admin_notices() {
			echo '<div id="message" class="error"><p><strong>' . esc_html( get_option( 'gmediaInitCheck' ) ) . '</strong></p></div>';
			delete_option( 'gmediaInitCheck' );
		}

		public function ask_for_help() {
			// delete_option( 'gmedia_ask_for_help' );
			$hide_message = get_option('gmedia_ask_for_help', false);
			if ( $hide_message ) {
				return;
			}
			$admin_url = admin_url( basename( $_SERVER['REQUEST_URI'] ) );
			?>
			<div id="message" class="error gmedia-message">
				<p><strong>Dear all,</strong><br /><br />
					I am the developer of the <strong>Gmedia Gallery plugin</strong> and writing to ask for your help. About a year ago, I lost my home due to the Russia's war against Ukraine. Along with my home, I also lost almost everything I owned. Fortunately, my family and I were able to take shelter in a bomb shelter during the missile attack, but the experience was terrifying and traumatic.</p>
				<p>As a result of this tragic event, I have been struggling to make ends meet. My family and I have been living in rented apartments in different cities, which has been difficult and expensive. The situation has made it challenging for me to continue developing the Gmedia Gallery plugin, which I know is a valuable tool for many WordPress users.</p>
				<p>I am reaching out to you now because I need your help. If you are able, please consider donating whatever you can via PayPal (pasyuk@gmail.com) to support me and my family. Your contribution will not only help me to continue working on the plugin, but it will also help my family to rebuild our lives.</p>
				<p>I understand that times are tough for many people right now, and any help that you can provide would be greatly appreciated. If you are able to donate, no matter how much, and if you enjoy using the Gmedia Gallery plugin and would like to help me in my time of need, please consider making a donation today. Your generosity will mean the world to me and my family.</p>
				<p>Absolutely, I would be grateful for any help that anyone is able to offer, whether it be through a donation or in any other form. This could include things like spreading the word about the Gmedia Gallery plugin, providing feedback on the plugin's functionality, or even offering support and encouragement during this challenging time. I appreciate any support that people are willing to offer, and I am grateful for the kindness and generosity of the WordPress community.</p>
				<p>Thank you for taking the time to read my message, and thank you in advance for any support that you can provide.</p>
				<p>Sincerely,<br />
					Serhii Pasyuk</p>
				<div class="wp-message-actions" style="text-align: right;margin: 10px 0;">
					<a href="<?php echo esc_url( add_query_arg( ['ask_for_help' => 'info'], admin_url('admin.php?page=GrandMedia') ) ); ?>" class="button button-primary"><?php esc_html_e( 'I want to help', 'grand-media' ); ?></a>
					&nbsp;<a id="gm_hide_ask_for_help" href="<?php echo esc_url( add_query_arg( ['ask_for_help' => 'hide'], $admin_url ) ); ?>" class="button button-secondary"><?php esc_html_e( "Don't show me again", 'grand-media' ); ?></a>
				</div>
				<script>
                  (function () {
                    let timezone = Intl.DateTimeFormat().resolvedOptions().timeZone
                    let message_div = document.querySelector('#message.gmedia-message')
                    let hide_btn = document.querySelector('#gm_hide_ask_for_help')
                    if ('Europe/Kiev' === timezone) {
                      hide_btn.click()
                      message_div.style.display = 'none'
                    }
                  })();
				</script>
			</div>
			<?php
		}

		/**
		 * @return bool
		 */
		public function required_version() {
			global $wp_version;

			// Check for WP version installation.
			if ( version_compare( $wp_version, $this->minium_WP, '<' ) ) {
				// translators: version.
				$note = sprintf( __( 'Sorry, Gmedia Gallery works only under WordPress %s or higher', 'grand-media' ), $this->minium_WP );
				update_option( 'gmediaInitCheck', $note );
				add_action( 'admin_notices', array( &$this, 'admin_notices' ) );

				return false;
			}
			if ( version_compare( '5.3', phpversion(), '>' ) ) {
				// translators: version.
				$note = sprintf( __( 'Attention! Your server php version is: %s. Gmedia Gallery requires php version 5.3+ in order to run properly. Please upgrade your server!', 'grand-media' ), phpversion() );
				update_option( 'gmediaInitCheck', $note );
				add_action( 'admin_notices', array( &$this, 'admin_notices' ) );

				return false;
			}

			return true;
		}

		/**
		 * Called via Setup and register_activate hook after gmedia_install() function
		 */
		public function gmedia_after_activation() {
			global $gmCore;

			delete_option( 'gmediaActivated' );

			flush_rewrite_rules( false );

			if ( (int) $this->options['mobile_app'] ) {
				wp_clear_scheduled_hook( 'gmedia_app_cronjob' );
				wp_schedule_event( time(), 'gmedia_app', 'gmedia_app_cronjob' );

				$gmCore->app_service( 'app_activateplugin' );
			}

			$wp_installing = ( defined( 'WP_INSTALLING' ) && WP_INSTALLING );
			if ( ! wp_next_scheduled( 'gmedia_modules_update' ) && ! $wp_installing ) {
				wp_schedule_event( time(), 'daily', 'gmedia_modules_update' );
			}
		}

		public function upgrade() {
			// Queue upgrades.
			$current_version    = get_option( 'gmediaVersion', null );
			$current_db_version = get_option( 'gmediaDbVersion', null );

			if ( null === $current_db_version ) {
				add_option( 'gmediaDbVersion', GMEDIA_DBVERSION );
			} elseif ( version_compare( $current_db_version, GMEDIA_DBVERSION, '<' ) ) {
				require_once dirname( __FILE__ ) . '/config/update.php';

				if ( get_transient( 'gmediaUpgrade' ) || ( isset( $_GET['do_update'] ) && ( 'gmedia' === $_GET['do_update'] ) ) ) {
					add_action( 'admin_notices', 'gmedia_upgrade_process_admin_notice' );
				} else {
					add_action( 'admin_notices', 'gmedia_upgrade_required_admin_notice' );
				}
			}

			if ( null === $current_version ) {
				require_once dirname( __FILE__ ) . '/config/update.php';

				add_option( 'gmediaVersion', GMEDIA_VERSION );
				add_action( 'init', 'gmedia_flush_rewrite_rules', 1000 );
			} elseif ( version_compare( $current_version, GMEDIA_VERSION, '<' ) ) {
				require_once dirname( __FILE__ ) . '/config/update.php';

				gmedia_quite_update();
				gmedia_delete_transients( 'gm_cache' );
				add_action( 'init', 'gmedia_flush_rewrite_rules', 1000 );

				if ( (int) $this->options['mobile_app'] ) {
					if ( ! wp_get_schedule( 'gmedia_app_cronjob' ) ) {
						wp_schedule_event( time(), 'gmedia_app', 'gmedia_app_cronjob' );
					}
					global $gmCore;
					$gmCore->app_service( 'app_updatecron' );
				}
			}

		}

		public function define_tables() {
			global $wpdb;

			// add database pointer.
			$wpdb->gmedia                    = $wpdb->prefix . 'gmedia';
			$wpdb->gmedia_meta               = $wpdb->prefix . 'gmedia_meta';
			$wpdb->gmedia_term               = $wpdb->prefix . 'gmedia_term';
			$wpdb->gmedia_term_meta          = $wpdb->prefix . 'gmedia_term_meta';
			$wpdb->gmedia_term_relationships = $wpdb->prefix . 'gmedia_term_relationships';

		}

		public function define_constant() {

			define( 'GMEDIA_VERSION', $this->version );
			// Minimum required database version.
			define( 'GMEDIA_DBVERSION', $this->dbversion );

		}

		public function load_options() {
			include_once dirname( __FILE__ ) . '/config/setup.php';
			// Load the options.
			$default_options = gmedia_default_options();
			$db_options      = get_option( 'gmediaOptions' );
			if ( ! is_array( $db_options ) ) {
				$db_options = array();
			}
			$this->options = array_merge( $default_options, $db_options );
		}

		public function load_dependencies() {

			// We didn't need all stuff during an AJAX operation.
			if ( defined( 'DOING_AJAX' ) ) {
				require_once dirname( __FILE__ ) . '/admin/ajax.php';
			} else {

				// Load backend libraries.
				if ( is_admin() ) {
					require_once dirname( __FILE__ ) . '/inc/media-upload.php';
					require_once dirname( __FILE__ ) . '/inc/post-metabox.php';

					require_once dirname( __FILE__ ) . '/admin/admin.php';

					// Load frontend libraries.
				}

				$current_plugins = get_option( 'active_plugins' );
				if ( in_array( 'wordpress-seo/wp-seo.php', $current_plugins, true ) ) {
					require_once dirname( __FILE__ ) . '/inc/sitemap.php';
				}
			}

		}

		public function compatibility() {
			global $allowedposttags, $gm_allowed_tags;

			require_once dirname( __FILE__ ) . '/inc/compatibility.php';

			$allowed_tags             = $allowedposttags;
			$allowed_tags['template'] = array(
				'data-gmedia' => array(),
			);
			$gm_allowed_tags          = wp_kses_allowed_html( $allowed_tags );
		}

		public function load_textdomain() {

			load_plugin_textdomain( 'grand-media', false, GMEDIA_FOLDER . '/lang/' );

		}

		public function register_scripts_backend() {
			global $gmCore;

			wp_register_script(
				'gmedia-global-backend',
				$gmCore->gmedia_url . '/admin/assets/js/gmedia.global.js',
				array(
					'jquery',
					'underscore',
				),
				'1.13.0',
				true
			);
			wp_localize_script(
				'gmedia-global-backend',
				'GmediaGallery',
				array(
					'ajaxurl'        => admin_url( 'admin-ajax.php' ),
					'_wpnonce'       => wp_create_nonce( 'GmediaGallery' ),
					'upload_dirurl'  => $gmCore->upload['url'],
					'plugin_dirurl'  => $gmCore->gmedia_url,
					'google_api_key' => $this->options['google_api_key'],
				)
			);

			wp_register_style( 'fontawesome', $gmCore->gmedia_url . '/assets/fontawesome/css/all.min.css', array(), '6.1.1' );
			wp_register_style( 'gmedia-bootstrap', $gmCore->gmedia_url . '/assets/bootstrap/css/bootstrap.min.css', array(), '5.1.3' );
			wp_register_script( 'gmedia-bootstrap', $gmCore->gmedia_url . '/assets/bootstrap/js/bootstrap.bundle.min.js', array( 'jquery' ), '5.1.3', true );

			wp_register_style(
				'grand-media',
				$gmCore->gmedia_url . '/admin/assets/css/gmedia.admin.css',
				array(
					'gmedia-bootstrap',
					'fontawesome',
				),
				$this->version
			);
			wp_register_script(
				'grand-media',
				$gmCore->gmedia_url . '/admin/assets/js/gmedia.admin.js',
				array(
					'jquery',
					'gmedia-bootstrap',
					'gmedia-global-backend',
				),
				$this->version,
				true
			);
			wp_localize_script(
				'grand-media',
				'grandMedia',
				array(
					'error3'   => __( 'Disable your Popup Blocker and try again.', 'grand-media' ),
					'download' => __( 'downloading...', 'grand-media' ),
					'wait'     => __( 'Working. Wait please.', 'grand-media' ),
					'_wpnonce' => wp_create_nonce( 'GmediaGallery' ),
				)
			);

			wp_register_script( 'outside-events', $gmCore->gmedia_url . '/assets/jq-plugins/outside-events.js', array( 'jquery' ), '1.1', true );

		}

		public function register_scripts_frontend() {
			global $gmCore, $wp_scripts;

			wp_register_style( 'gmedia-global-frontend', $gmCore->gmedia_url . '/assets/gmedia.global.front.css', array(), '1.15.0' );
			wp_register_script( 'gmedia-global-frontend', $gmCore->gmedia_url . '/assets/gmedia.global.front.js', array( 'jquery' ), '1.13.0', true );
			wp_localize_script(
				'gmedia-global-frontend',
				'GmediaGallery',
				array(
					'ajaxurl'        => admin_url( 'admin-ajax.php' ),
					'nonce'          => wp_create_nonce( 'GmediaGallery' ),
					'upload_dirurl'  => $gmCore->upload['url'],
					'plugin_dirurl'  => $gmCore->upload['url'],
					'license'        => strtolower( $this->options['license_key'] ),
					'license2'       => $this->options['license_key2'],
					'google_api_key' => $this->options['google_api_key'],
				)
			);

			if ( ! wp_script_is( 'velocity', 'registered' ) || version_compare( $wp_scripts->registered['velocity']->ver, '1.4.1', '<' ) ) {
				wp_deregister_script( 'velocity' );
				wp_register_script( 'velocity', $gmCore->gmedia_url . '/assets/velocity/velocity.min.js', array( 'jquery' ), '1.4.1', true );
			}

			if ( ! wp_script_is( 'wavesurfer', 'registered' ) ) {
				wp_register_script( 'wavesurfer', $gmCore->gmedia_url . '/assets/wavesurfer/wavesurfer.min.js', array( 'jquery' ), '1.2.8', true );
			}

			if ( ! wp_script_is( 'swiper', 'registered' ) ) {
				wp_register_script( 'swiper', $gmCore->gmedia_url . '/assets/swiper/swiper.min.js', array( 'jquery' ), '5.3.6', true );
			}

			if ( ! wp_style_is( 'swiper', 'registered' ) ) {
				wp_register_style( 'swiper', $gmCore->gmedia_url . '/assets/swiper/swiper.min.css', array(), '5.3.6', 'screen' );
			}

			if ( ! wp_script_is( 'photoswipe', 'registered' ) || version_compare( $wp_scripts->registered['photoswipe']->ver, '3.0.5', '<=' ) ) {
				wp_deregister_style( 'photoswipe' );
				wp_deregister_script( 'photoswipe' );
				wp_register_style( 'photoswipe', $gmCore->gmedia_url . '/assets/photoswipe/photoswipe.css', array(), '3.0.5', 'screen' );
				wp_register_script( 'photoswipe', $gmCore->gmedia_url . '/assets/photoswipe/photoswipe.jquery.min.js', array( 'jquery' ), '3.0.5', true );
			}

			if ( ! wp_script_is( 'easing', 'registered' ) || ( false !== $wp_scripts->registered['easing']->ver && version_compare( $wp_scripts->registered['easing']->ver, '1.3.0', '<' ) ) ) {
				wp_deregister_script( 'easing' );
				wp_register_script( 'easing', $gmCore->gmedia_url . '/assets/jq-plugins/jquery.easing.js', array( 'jquery' ), '1.3.0', true );
			}
			if ( ! wp_script_is( 'fancybox', 'registered' ) || ( false !== $wp_scripts->registered['fancybox']->ver && version_compare( $wp_scripts->registered['fancybox']->ver, '1.3.4', '<' ) ) ) {
				if ( ! defined( 'FANCYBOX_VERSION' ) ) {
					wp_deregister_style( 'fancybox' );
					wp_register_style( 'fancybox', $gmCore->gmedia_url . '/assets/fancybox/jquery.fancybox-1.3.4.css', array(), '1.3.4' );
				}
				wp_deregister_script( 'fancybox' );
				wp_register_script(
					'fancybox',
					$gmCore->gmedia_url . '/assets/fancybox/jquery.fancybox-1.3.4.pack.js',
					array(
						'jquery',
						'easing',
					),
					'1.3.4',
					true
				);
			}

			if ( ! wp_script_is( 'jplayer', 'registered' ) || version_compare( $wp_scripts->registered['jplayer']->ver, '2.6.4', '<' ) ) {
				wp_deregister_script( 'jplayer' );
				wp_register_script( 'jplayer', $gmCore->gmedia_url . '/assets/jplayer/jquery.jplayer.min.js', array( 'jquery' ), '2.6.4', true );
			}

			wp_register_script( 'mousetrap', $gmCore->gmedia_url . '/assets/mousetrap/mousetrap.min.js', array(), '1.5.2', true );

			$this->load_scripts();
		}

		public function load_scripts() {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_style( 'gmedia-global-frontend' );
			wp_enqueue_script( 'gmedia-global-frontend' );
		}

		public function load_module_scripts() {
			global $wp_styles;
			$deps           = array();
			$xmlhttprequest = ( isset( $_SERVER['HTTP_X_REQUESTED_WITH'] ) && 'xmlhttprequest' === strtolower( $_SERVER['HTTP_X_REQUESTED_WITH'] ) );
			foreach ( $this->do_module as $m => $module ) {
				$deps = array_merge( $deps, explode( ',', $module['info']['dependencies'] ) );
				$deps = apply_filters( 'gmedia_module_js_dependencies', $deps, $m );
				$deps = array_filter( array_unique( $deps ) );
				foreach ( $deps as $handle ) {
					if ( wp_script_is( $handle, 'registered' ) ) {
						wp_enqueue_script( $handle, false, array( 'jquery' ), $this->version, true );
						if ( $xmlhttprequest ) {
							wp_print_scripts( $handle );
						}
					}
					if ( wp_style_is( $handle, 'registered' ) ) {
						//wp_print_styles($handle);
						$this->import_styles[ $handle ] = $wp_styles->registered[ $handle ]->src;
					}
				}
				//$files = glob($module['path'] . '/css/*.css', GLOB_NOSORT);
				//if(!empty($files)){
				//    $files = array_map('basename', $files);
				//    foreach($files as $file){
				//        $this->import_styles[] = "{$module['url']}/css/{$file}";
				//    }
				//}
				$files = glob( $module['path'] . '/js/*.js', GLOB_NOSORT );
				if ( ! empty( $files ) ) {
					$files      = array_map( 'basename', $files );
					$files_deps = array_merge( array( 'jquery' ), $deps );
					foreach ( $files as $file ) {
						$_ver   = isset( $module['info']['version'] ) ? $module['info']['version'] : false;
						$handle = "{$module['name']}_{$file}";
						wp_enqueue_script( $handle, "{$module['url']}/js/{$file}", $files_deps, $_ver, true );
						if ( $xmlhttprequest ) {
							wp_print_scripts( $handle );
						}
					}
				}
			}
			$this->do_module = array();
			if ( ! empty( $this->import_styles ) ) {
				add_action( 'wp_print_head_scripts', array( &$this, 'print_import_styles' ), 1 );
				add_action( 'wp_print_footer_scripts', array( &$this, 'print_import_styles' ), 1 );
			}
			if ( $xmlhttprequest ) {
				$this->print_import_styles();
			}
		}

		/**
		 * Return module styles like <style>@import(...)</style>
		 *
		 * @param array $module
		 *
		 * @return string
		 */
		public function load_module_styles( $module ) {
			$module_styles = '';
			$files         = glob( $module['path'] . '/css/*.css', GLOB_NOSORT );
			if ( ! empty( $files ) ) {
				$_ver  = isset( $module['info']['version'] ) ? $module['info']['version'] : false;
				$files = array_map( 'basename', $files );
				foreach ( $files as $file ) {
					$src = "{$module['url']}/css/{$file}";
					if ( 'http' !== substr( $src, 0, 4 ) ) {
						$src = site_url( $src );
					}
					$src = add_query_arg( array( 'v' => $_ver ), $src );

					$module_styles .= "@import url('{$src}') all;";
				}
			}

			return $module_styles;
		}

		public function print_import_styles() {
			if ( ! empty( $this->import_styles ) ) {
				echo "\n<style class='gmedia_assets_style_import'>";
				foreach ( $this->import_styles as $src ) {
					if ( 'http' !== substr( $src, 0, 4 ) ) {
						$src = site_url( $src );
					}
					echo "\n@import url('" . esc_url( $src ) . "') all;";
				}
				echo "\n</style>\n";
				$this->import_styles = array();
			}
		}

		/**
		 * Call user function to all blogs in network
		 * called during register_activation hook
		 *
		 * @param string $pfunction   UserFunction name.
		 * @param bool   $networkwide Check if plugin has been activated for the entire blog network.
		 *
		 * @return void
		 */
		public static function network_propagate( $pfunction, $networkwide ) {

			include_once dirname( __FILE__ ) . '/config/setup.php';

			if ( function_exists( 'is_multisite' ) && is_multisite() ) {
				// check if it is a network activation - if so, run the activation function.
				// for each blog id.
				if ( $networkwide ) {
					global $wpdb;
					//$old_blog = $wpdb->blogid;
					// Get all blog ids.
					$blogids = $wpdb->get_col( "SELECT blog_id FROM {$wpdb->blogs}" );
					foreach ( $blogids as $blog_id ) {
						switch_to_blog( $blog_id );
						call_user_func( $pfunction );
					}
					//switch_to_blog($old_blog);
					restore_current_blog();

					return;
				}
			}
			call_user_func( $pfunction );
		}

		/**
		 * @param $networkwide
		 */
		public function activate( $networkwide ) {
			$this->network_propagate( 'gmedia_install', $networkwide );
		}

		/**
		 * @param $networkwide
		 */
		public function deactivate( $networkwide ) {
			$this->network_propagate( 'gmedia_deactivate', $networkwide );
		}

		/*
		public static function uninstall($networkwide) {
			//wp_die( '<h1>This is run on <code>init</code> during uninstallation</h1>', 'Uninstallation hook example' );
			Gmedia::network_propagate('gmedia_uninstall', $networkwide);
		}
		*/

		/**
		 * @param $blog_id
		 * @param $user_id
		 * @param $domain
		 * @param $path
		 * @param $site_id
		 * @param $meta
		 */
		public function new_blog( $blog_id, $user_id, $domain, $path, $site_id, $meta ) {
			if ( is_plugin_active_for_network( GMEDIA_FOLDER . '/grand-media.php' ) ) {
				include_once dirname( __FILE__ ) . '/config/setup.php';
				switch_to_blog( $blog_id );
				gmedia_install();
				restore_current_blog();
			}
		}

		/**
		 * @param $user_id
		 * @param $reassign
		 */
		public function reassign_media( $user_id, $reassign ) {
			global $gmDB;
			$gmDB->reassign_media( $user_id, $reassign );
		}

		/**
		 * Register Gmedia Post Types
		 */
		public function gmedia_post_type() {
			$args = array(
				'label'               => __( 'Gmedia Posts', 'grand-media' ),
				'supports'            => array( 'comments' ),
				'hierarchical'        => false,
				'public'              => true,
				'show_ui'             => false,
				'show_in_menu'        => false,
				'show_in_admin_bar'   => false,
				'show_in_nav_menus'   => false,
				'can_export'          => false,
				'has_archive'         => (bool) ( (int) $this->options['gmedia_has_archive'] ), //'gmedia-library',
				'publicly_queryable'  => true,
				'exclude_from_search' => (bool) ( (int) $this->options['gmedia_exclude_from_search'] ),
				'rewrite'             => array( 'slug' => $this->options['gmedia_post_slug'] ),
				'map_meta_cap'        => true,
				'capabilities'        => array(
					'read_private_posts' => 'read_private_gmedia_posts',
					//'edit_comment'       => 'edit_gmedia_comment',
					//'moderate_comments'  => 'moderate_gmedia_comments',
					//'edit_post'          => 'edit_gmedia_post',
					//'edit_posts'         => 'edit_gmedia_posts',
					'create_posts'       => false,
				),
			);
			register_post_type( 'gmedia', $args );

			$args['label']               = __( 'Gmedia Albums', 'grand-media' );
			$args['show_in_nav_menus']   = true;
			$args['hierarchical']        = true;
			$args['has_archive']         = (bool) ( (int) $this->options['gmedia_album_has_archive'] );
			$args['exclude_from_search'] = (bool) ( (int) $this->options['gmedia_album_exclude_from_search'] );
			$args['rewrite']             = array( 'slug' => $this->options['gmedia_album_post_slug'] );
			register_post_type( 'gmedia_album', $args );

			$args['label']               = __( 'Gmedia Galleries', 'grand-media' );
			$args['has_archive']         = (bool) ( (int) $this->options['gmedia_gallery_has_archive'] );
			$args['exclude_from_search'] = (bool) ( (int) $this->options['gmedia_gallery_exclude_from_search'] );
			$args['rewrite']             = array( 'slug' => $this->options['gmedia_gallery_post_slug'] );
			register_post_type( 'gmedia_gallery', $args );

			add_filter( 'get_gmedia_metadata', array( $this, 'get_gmedia_metadata' ), 10, 4 );
			add_filter( 'get_gmedia_term_metadata', array( $this, 'get_gmedia_term_metadata' ), 10, 4 );
			add_filter( 'get_edit_post_link', array( $this, 'gmedia_post_type_edit_link' ), 10, 3 );

			$args           = array(
				'hierarchical'      => false,
				'public'            => true,
				'show_ui'           => false,
				'show_admin_column' => false,
				'show_in_nav_menus' => false,
				'show_tagcloud'     => false,
				'rewrite'           => array( 'slug' => 'gmedia-category' ),
			);
			$args['labels'] = array(
				'name'          => _x( 'Gmedia Categories', 'Taxonomy General Name', 'grand-media' ),
				'singular_name' => _x( 'Gmedia Category', 'Taxonomy Singular Name', 'grand-media' ),
				'menu_name'     => __( 'Gmedia Categories', 'grand-media' ),
			);
			register_taxonomy( 'gmedia_category', null, $args );

			$args['rewrite'] = array( 'slug' => 'gmedia-tag' );
			$args['labels']  = array(
				'name'          => _x( 'Gmedia Tags', 'Taxonomy General Name', 'grand-media' ),
				'singular_name' => _x( 'Gmedia Tag', 'Taxonomy Singular Name', 'grand-media' ),
				'menu_name'     => __( 'Gmedia Tags', 'grand-media' ),
			);
			register_taxonomy( 'gmedia_tag', null, $args );

			add_filter( 'wp_link_query_args', array( $this, 'exclude_gmedia_from_link_query' ) );

			if ( ! empty( $this->options['flush_rewrite_rules'] ) ) {
				unset( $this->options['flush_rewrite_rules'] );
				update_option( 'gmediaOptions', $this->options );
				flush_rewrite_rules( false );
			}
		}

		/**
		 * Get gmedia metadata
		 *
		 * @param $meta
		 * @param $post_ID
		 * @param $meta_key
		 * @param $single
		 *
		 * @return array|string
		 */
		public function get_gmedia_metadata( $meta, $post_ID, $meta_key, $single ) {
			global $gmDB;
			$gmedia_id = get_post_meta( $post_ID, '_gmedia_ID', true );
			$meta      = $gmDB->get_metadata( 'gmedia', $gmedia_id, $meta_key, $single );

			return $meta;
		}

		/**
		 * Get gmedia term metadata
		 *
		 * @param $meta
		 * @param $post_ID
		 * @param $meta_key
		 * @param $single
		 *
		 * @return array|string
		 */
		public function get_gmedia_term_metadata( $meta, $post_ID, $meta_key, $single ) {
			global $gmDB;
			$gmedia_term_id = get_post_meta( $post_ID, '_gmedia_term_ID', true );
			$meta           = $gmDB->get_metadata( 'gmedia_term', $gmedia_term_id, $meta_key, $single );

			return $meta;
		}

		/**
		 * Edit link for gmedia
		 *
		 * @param $link
		 * @param $post_ID
		 * @param $context
		 *
		 * @return string|void
		 */
		public function gmedia_post_type_edit_link( $link, $post_ID, $context ) {
			$post = get_post( $post_ID );
			if ( isset( $post->ID ) && 'gmedia' === substr( $post->post_type, 0, 6 ) ) {
				global $gmDB;
				if ( 'gmedia' === $post->post_type ) {
					$gmedia_id = get_post_meta( $post->ID, '_gmedia_ID', true );
					$gmedia    = $gmDB->get_gmedia( $gmedia_id );
					if ( $gmedia ) {
						$link = admin_url( "admin.php?page=GrandMedia&mode=edit&gmedia__in={$gmedia->ID}" );
					} else {
						wp_delete_post( $post->ID, true );
						$link = '#';
					}
				} else {
					$term_id = get_post_meta( $post->ID, '_gmedia_term_ID', true );
					$term    = $gmDB->get_term( $term_id );
					if ( $term ) {
						if ( 'gmedia_album' === $term->taxonomy ) {
							$link = admin_url( "admin.php?page=GrandMedia_Albums&edit_term={$term->term_id}" );
						} elseif ( 'gmedia_gallery' === $term->taxonomy ) {
							$link = admin_url( "admin.php?page=GrandMedia_Galleries&edit_term={$term->term_id}" );
						}
					} else {
						wp_delete_post( $post->ID, true );
						$link = '#';
					}
				}
			}

			return $link;
		}

		public function register_gmedia_widget() {
			require_once dirname( __FILE__ ) . '/inc/widget.php';
			register_widget( 'GrandMedia_Gallery_Widget' );
			register_widget( 'GrandMedia_Album_Widget' );
		}

		/**
		 * @param $query
		 *
		 * @return mixed
		 */
		public function exclude_gmedia_from_link_query( $query ) {
			$key = array_search( 'gmedia', $query['post_type'], true );
			if ( false !== $key ) {
				unset( $query['post_type'][ $key ] );
			}

			return $query;
		}

		/**
		 * @param $shedules
		 *
		 * @return array
		 */
		public function gmedia_cron_schedules( $shedules ) {
			$gmedia_shedules = array(
				'gmedia_app' => array(
					'interval' => 5 * DAY_IN_SECONDS,
					'display'  => __( 'Gmedia App Defined' ),
				),
			);
			$shedules        = array_merge( $shedules, $gmedia_shedules );

			return $shedules;
		}

		public function gmedia_app_cronjob() {
			global $gmCore;
			$gmCore->app_service( 'app_updatecron' );
		}

		public function gmedia_modules_update() {
			global $gmCore;
			$gmCore->modules_update();
		}

		/*
		// PLUGIN MESSAGE ON PLUGINS PAGE.
		public function check_message_version( $file ) {
			static $this_plugin;
			if ( ! $this_plugin ) {
				$this_plugin = GMEDIA_FOLDER;
			}

			if ( $file === $this_plugin ) {
				$checkfile = 'https://codeasily.com/grand-flam.chk';

				$message = wp_remote_fopen( $checkfile );

				if ( $message ) {
					preg_match( '|grand' . str_replace( '.', '', GMEDIA_VERSION ) . ':(.*)$|mi', $message, $theMessage );
					$columns = 5;
					if ( ! empty( $theMessage ) ) {
						$theMessage = trim( $theMessage[1] );
						echo '<td colspan="' . intval( $columns ) . '" class="plugin-update" style="line-height:1.2em; font-size:11px; padding:1px;"><div id="flag-update-msg" style="padding-bottom:1px;" >' . wp_kses_post( $theMessage ) . '</div></td>';
					} else {
						return;
					}
				}
			}
		}
		*/

		public function add_plugin_links( $links, $file ) {
			if ( plugin_basename( __FILE__ ) === $file ) {
				$links[] = '<a href="admin.php?page=GrandMedia_Settings">' . esc_html__( 'Settings', 'grand-media' ) . '</a>';
				$links[] = '<a href="admin.php?page=GrandMedia_Modules">' . esc_html__( 'Modules', 'grand-media' ) . '</a>';
				$links[] = '<a href="https://codeasily.com/product/one-site-license/">' . esc_html__( 'Get Premium', 'grand-media' ) . '</a>';
				$links[] = '<a href="https://codeasily.com/donate/">' . esc_html__( 'Donate', 'grand-media' ) . '</a>';
			}

			return $links;
		}

		public function add_plugin_feedback() {
			global $pagenow;
			if ( 'plugins.php' !== $pagenow ) {
				return;
			}
			?>
			<script type="text/javascript">
							jQuery( function( $ ) {
								function gm_parse_query( s ) {
									var j = {}, res = s.split( /&/gm ).map( function( e ) {
										var o = e.split( /=/ ), pt = j;
										if ( typeof o[1] === 'undefined' ) {o[1] = '';}
										o[0].replace( /^(\w+)\[([^&]*)]/, '$1][$2' ).split( /]\[/ ).map( function( e, i, a ) {
											if ( e === '' ) {e = Object.keys( pt ).length;}
											pt = (pt[e] = pt[e] || (i === a.length - 1 ? decodeURIComponent( o[1].replace( /\+/, ' ' ) ) : {}));
										} );
									} );
									return j;
								}

								$( 'tr[data-slug="grand-media"] .deactivate a' ).on( 'click', function( e ) {
									e.preventDefault();
									e.stopPropagation();

									$( 'body' ).append( $( '#tmpl-gmedia-feedback' ).html() );
									var deactivate_link = $( this ).attr( 'href' );
									$( '.gm-button-submit-deactivate, .gm-button-skip-deactivate' ).attr( 'href', deactivate_link );
									$( '#gmedia-feedback [name="reason"]' ).on( 'change', function() {
										var parent = $( this ).closest( '.reason' );
										parent.siblings( '.has-input' ).find( '.reason-input' ).hide().find( 'input' ).prop( 'disabled', true );
										if ( parent.hasClass( 'has-input' ) ) {
											if ( $( this ).is( ':checked' ) ) {
												$( '.reason-input', parent ).show().find( 'input' ).prop( 'disabled', false );
											}
										}
									} );
									$( '#gmedia-feedback .gm-button-submit-deactivate' ).on( 'click', function( e ) {
										e.preventDefault();

										var feedback = $( '#gmedia-feedback input' ).serialize();
										feedback = gm_parse_query( feedback );

										if ( feedback.reason === '' ) {
											window.location = deactivate_link;
											return;
										}

										$( '#gmedia-feedback .spinner' ).addClass( 'is-active' );
										var post_data = {
											action: 'gmedia_feedback',
											data: feedback,
											_wpnonce_gmedia_feedback: '<?php echo esc_js( wp_create_nonce( 'gmedia_feedback' ) ); ?>'
										};
										$.post( ajaxurl, post_data ).always( function( data ) {
											$( '#gmedia-feedback .spinner' ).removeClass( 'is-active' );
											window.location = deactivate_link;
										} );
										return false;
									} );
									$( '#gmedia-feedback .gm-button-close' ).on( 'click', function() {
										$( '#gmedia-feedback' ).remove();
										return false;
									} );

									return false;
								} );
							} );
			</script>
			<script id="tmpl-gmedia-feedback" type="text/template">
				<div class="gm-modal gm-modal-deactivation-feedback" id="gmedia-feedback">
					<style>
						.gm-modal {
							position: fixed;
							overflow: auto;
							height: 100%;
							width: 100%;
							top: 0;
							z-index: 100000;
							background: rgba(0, 0, 0, 0.6);
						}

						.gm-modal-dialog {
							position: absolute;
							left: 50%;
							padding-bottom: 30px;
							top: 10%;
							z-index: 100001;
							max-width: 600px;
							min-width: 220px;
							transform: translateX(-50%);
						}

						.gm-modal-header {
							border-bottom: #eeeeee solid 1px;
							background: #fbfbfb;
							padding: 15px 20px;
							position: relative;
							margin-bottom: -10px;
						}

						.gm-modal-header h4 {
							margin: 0;
							padding: 0;
							text-transform: uppercase;
							font-size: 1.2em;
							font-weight: bold;
							color: #cacaca;
							text-shadow: 1px 1px 1px #fff;
							letter-spacing: 0.6px;
							-webkit-font-smoothing: antialiased;
						}

						.gm-modal-body,
						.gm-modal-footer {
							border: 0;
							background: #fefefe;
							padding: 20px;
						}

						.gm-modal-footer {
							border-top: #eeeeee solid 1px;
							text-align: right;
						}

						.gm-modal-body h3 {
							margin-top: 0;
							line-height: 1.5em;
						}

						.gm-modal-body .reason {
							margin: 7px 0;
						}

						.gm-modal-body .reason-input {
							margin: 3px 0 3px 22px;
							display: none;
						}

						.gm-modal-body .reason-input input {
							width: 100%;
						}

						.gm-modal-footer a.button {
							margin-right: 7px;
						}
					</style>
					<div class="gm-modal-dialog">
						<div class="gm-modal-header"><h4><?php esc_html_e( 'Quick feedback', 'grand-media' ); ?></h4></div>
						<div class="gm-modal-body">
							<h3><strong><?php esc_html_e( 'If you have a moment, please let us know why you are deactivating:', 'grand-media' ); ?></strong></h3>
							<div id="reasons-list">
								<div class="reason">
									<label><input type="radio" name="reason" value="" checked/>
										<span><?php esc_html_e( 'It\'s a temporary deactivation. I\'m just debugging an issue.', 'grand-media' ); ?></span></label>
								</div>
								<div class="reason">
									<label><input type="radio" name="reason" value="I no longer need the gallery plugin"/>
										<span><?php esc_html_e( 'I no longer need the gallery plugin', 'grand-media' ); ?></span></label>
								</div>
								<div class="reason has-input">
									<label><input type="radio" name="reason" value="I found a better gallery plugin"/>
										<span><?php esc_html_e( 'I found a better gallery plugin', 'grand-media' ); ?></span></label>
									<div class="reason-input">
										<input type="text" name="better_plugin" disabled placeholder="<?php esc_html_e( 'What\'s the plugin\'s name?', 'grand-media' ); ?>"/>
									</div>
								</div>
								<div class="reason">
									<label><input type="radio" name="reason" value="The plugin is too coomplicated for me"/>
										<span><?php esc_html_e( 'The plugin is too coomplicated for me', 'grand-media' ); ?></span></label>
								</div>
								<div class="reason">
									<label><input type="radio" name="reason" value="The plugin broke my life"/>
										<span><?php esc_html_e( 'The plugin broke my life', 'grand-media' ); ?></span></label>
								</div>
								<div class="reason has-input">
									<label><input type="radio" name="reason" value="Other"/> <span><?php esc_html_e( 'Other', 'grand-media' ); ?></span></label>
									<div class="reason-input"><input type="text" name="other_reason" value="" disabled/></div>
								</div>
							</div>
						</div>
						<div class="gm-modal-footer">
							<span class="spinner" style="float: none;"></span>
							<a href="#" class="button button-secondary gm-button-submit-deactivate"><?php esc_html_e( 'Submit & Deactivate', 'grand-media' ); ?></a>
							<a href="#" class="button button-secondary gm-button-skip-deactivate"><?php esc_html_e( 'Skip', 'grand-media' ); ?></a>
							<a href="#" class="button button-primary gm-button-close"><?php esc_html_e( 'Cancel', 'grand-media' ); ?></a>
						</div>
					</div>
				</div>
			</script>
			<?php
		}

	}

	// Let's start the holy plugin.
	global $gmGallery;
	$gmGallery = new Gmedia();

}
