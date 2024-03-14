<?php
/*
Plugin Name: Music Player for Easy Digital Downloads
Plugin URI: http://wordpress.dwbooster.com/content-tools/music-player-for-easy-digital-downloads
Version: 1.1.5
Text Domain: music-player-for-easy-digital-downloads
Author: CodePeople
Author URI: http://wordpress.dwbooster.com
Description: Music Player for Easy Digital Downloads includes the MediaElement.js music player in the pages of the EDD downloads with audio files associated, and in the store's pages, furthermore, the plugin allows selecting between multiple skins.
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

require_once 'banner.php';
$codepeople_promote_banner_plugins['codepeople-music-player-for-easy-digital-downloads'] = array(
	'plugin_name' => 'Music Player for Easy Digital Downloads',
	'plugin_url'  => 'https://wordpress.org/support/plugin/music-player-for-easy-digital-downloads/reviews/#new-post',
);

// Feedback system
require_once 'feedback/cp-feedback.php';
new EDDMP_FEEDBACK( 'music-player-for-easy-digital-downloads', __FILE__, 'https://wordpress.dwbooster.com/support' );

// CONSTANTS
define( 'EDDMP_PLUGIN_VERSION', '1.1.5' );
define( 'EDDMP_PLUGIN_PATH', __FILE__ );
define( 'EDDMP_PLUGIN_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'EDDMP_WEBSITE_URL', get_home_url( get_current_blog_id(), '', is_ssl() ? 'https' : 'http' ) );
define( 'EDDMP_PLUGIN_URL', plugins_url( '', __FILE__ ) );
define( 'EDDMP_DEFAULT_PLAYER_LAYOUT', 'mejs-classic' );
define( 'EDDMP_DEFAULT_PLAYER_VOLUME', 1 );
define( 'EDDMP_DEFAULT_PLAYER_CONTROLS', 'default' );
define( 'EDDMP_REMOTE_TIMEOUT', 300 );
define( 'EDDMP_DEFAULT_PlAYER_TITLE', 1 );

// Load widgets
require_once 'widgets/playlist_widget.php';

add_filter( 'option_sbp_settings', array( 'EDDMusicPlayer', 'troubleshoot' ) );
if ( ! class_exists( 'EDDMusicPlayer' ) ) {
	class EDDMusicPlayer {

		// ******************** ATTRIBUTES ************************

		private $_downloads_attrs = array();
		private $_global_attrs    = array();
		private $_player_layouts  = array( 'mejs-classic', 'mejs-ted', 'mejs-wmp' );
		private $_player_controls = array( 'button', 'all', 'default' );
		private $_files_directory_path;
		private $_files_directory_url;
		private $_enqueued_resources = false;
		private $_insert_player      = true;
		private $_in_store           = false;

		private $_preload_times = 0; // Multiple preloads with demo generators can affect the server performance

		/**
		 * EDDMP constructor
		 *
		 * @access public
		 * @return void
		 */
		public function __construct() {
			 $this->_createDir();
			register_activation_hook( __FILE__, array( &$this, 'activation' ) );
			register_deactivation_hook( __FILE__, array( &$this, 'deactivation' ) );
			add_action( 'plugins_loaded', array( &$this, 'plugins_loaded' ) );
			add_action( 'init', array( &$this, 'init' ) );
			add_action( 'admin_init', array( &$this, 'admin_init' ), 99 );
			add_action( 'edd_downloads_list_before', array( $this, 'in_store' ) );
		} // End __constructor

		public function activation() {
			$this->_clearDir( $this->_files_directory_path );
			$this->_createDir();
		}

		public function deactivation() {
			$this->_clearDir( $this->_files_directory_path );
		}

		public function plugins_loaded() {
			if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
				return;
			}
			load_plugin_textdomain( 'music-player-for-easy-digital-downloads', false, basename( dirname( __FILE__ ) ) . '/languages/' );

			$this->_load_addons();

			// Integration with the content editors
			require_once dirname( __FILE__ ) . '/pagebuilders/builders.php';
			EDDMP_BUILDERS::run();
		}

		public function in_store( $atts ) {
			 $this->_in_store = true;
		}

		public function get_download_attr( $download_id, $attr, $default = false ) {
			if ( ! isset( $this->_downloads_attrs[ $download_id ] ) ) {
				$this->_downloads_attrs[ $download_id ] = array();
			}
			if ( ! isset( $this->_downloads_attrs[ $download_id ][ $attr ] ) ) {
				if ( metadata_exists( 'post', $download_id, $attr ) ) {
					$this->_downloads_attrs[ $download_id ][ $attr ] = get_post_meta( $download_id, $attr, true );
				} else {
					$this->_downloads_attrs[ $download_id ][ $attr ] = $this->get_global_attr( $attr, $default );
				}
			}
			return $this->_downloads_attrs[ $download_id ][ $attr ];

		} // End get_download_attr

		public function get_global_attr( $attr, $default = false ) {
			if ( empty( $this->_global_attrs ) ) {
				$this->_global_attrs = get_option( 'eddmp_global_settings', array() );
			}
			if ( ! isset( $this->_global_attrs[ $attr ] ) ) {
				$this->_global_attrs[ $attr ] = $default;
			}
			return $this->_global_attrs[ $attr ];

		} // End get_global_attr

		// ******************** WordPress ACTIONS **************************

		public function init() {
			// Check if Easy Digital Downloads is installed or not
			if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
				return;
			}
			$_current_user_id = get_current_user_id();
			if (
				$this->get_global_attr( '_eddmp_registered_only', 0 ) &&
				0 == $_current_user_id
			) {
				$this->_insert_player = false;
			}

			if ( ! is_admin() ) {
				add_filter( 'eddmp_preload', array( $this, 'preload' ), 10, 2 );

				// Define the shortcode for the playlist_widget
				add_shortcode( 'eddmp-playlist', array( &$this, 'replace_playlist_shortcode' ) );
				$this->_preview();
				if ( isset( $_REQUEST['eddmp-action'] ) && 'play' == $_REQUEST['eddmp-action'] ) {
					if ( isset( $_REQUEST['eddmp-download'] ) ) {
						$download_id = @intval( $_REQUEST['eddmp-download'] );
						if ( ! empty( $download_id ) ) {
							$download = edd_get_download( $download_id );

							if ( ! empty( $download ) && isset( $_REQUEST['eddmp-file'] ) ) {
								$files = $this->_get_download_files(
									array(
										'download' => $download,
										'file_id'  => sanitize_key( $_REQUEST['eddmp-file'] ),
									)
								);
								if ( ! empty( $files ) ) {
									$file_url = $files[ sanitize_key( $_REQUEST['eddmp-file'] ) ]['file'];
									$this->_tracking_play_event( $download_id, $file_url );
									$this->_output_file(
										array(
											'download_id' => $download_id,
											'url'         => $file_url,
										)
									);
								}
							}
						}
					}
					exit;
				} else {
					// To allow customize the hooks
					$include_main_player_hook = preg_replace( '/[\t\s]/', '', $this->get_global_attr( '_eddmp_main_player_hook', '' ) );
					$include_all_players_hook = preg_replace( '/[\t\s]/', '', $this->get_global_attr( '_eddmp_all_players_hook', '' ) );

					if ( empty( $include_main_player_hook ) ) {
						$include_main_player_hook = 'edd_download_after_title';
					}
					if ( empty( $include_all_players_hook ) ) {
						$include_all_players_hook = 'edd_before_download_content';
					}

					$include_main_player_hook = explode( ',', $include_main_player_hook );
					foreach ( $include_main_player_hook as $_hook_name ) {
						if ( ! empty( $_hook_name ) ) {
							add_action( $_hook_name, array( &$this, 'include_main_player' ), 11 );
						}
					}

					$include_all_players_hook = explode( ',', $include_all_players_hook );
					foreach ( $include_all_players_hook as $_hook_name ) {
						if ( ! empty( $_hook_name ) ) {
							add_action( $_hook_name, array( &$this, 'include_all_players' ), 11 );
						}
					}

					// Allows to call the players directly by themes
					add_action( 'eddmp_main_player', array( &$this, 'include_main_player' ), 11 );
					add_action( 'eddmp_all_players', array( &$this, 'include_all_players' ), 11 );

					$players_in_cart = $this->get_global_attr( '_eddmp_players_in_cart', false );
					if ( $players_in_cart ) {
						add_action( 'edd_checkout_cart_item_title_after', array( &$this, 'player_in_cart' ), 11, 2 );
					}

					// Add download id to audio tag
					add_filter( 'eddmp_audio_tag', array( &$this, 'add_data_download' ), 99, 3 );
				}
			} else {
				add_action( 'admin_menu', array( &$this, 'menu_links' ), 10 );
			}

		} // End init

		public function admin_init() {
			// Check if Easy Digital Downloads is installed or not
			if ( ! class_exists( 'Easy_Digital_Downloads' ) ) {
				return;
			}

			add_meta_box( 'eddmp_edd_metabox', __( 'Music Player for Easy Digital Downloads', 'music-player-for-easy-digital-downloads' ), array( &$this, 'edd_player_settings' ), $this->_get_post_types(), 'normal' );
			add_action( 'save_post', array( &$this, 'save_post' ), 10, 3 );
			add_action( 'delete_post', array( &$this, 'delete_post' ) );
		} // End admin_init

		public function menu_links() {
			add_options_page( 'Music Player for Easy Digital Downloads', 'Music Player for Easy Digital Downloads', 'manage_options', 'music-player-for-easy-digital-downloads-settings', array( &$this, 'settings_page' ) );
		} // End menu_links

		public function settings_page() {
			if (
				isset( $_POST['eddmp_nonce'] ) &&
				wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['eddmp_nonce'] ) ), 'eddmp_updating_plugin_settings' )
			) {
				$_REQUEST = stripslashes_deep( $_REQUEST );
				// Save the player settings
				$registered_only                = ( isset( $_REQUEST['_eddmp_registered_only'] ) ) ? 1 : 0;
				$troubleshoot_default_extension = ( isset( $_REQUEST['_eddmp_default_extension'] ) ) ? true : false;
				$ios_controls                   = ( isset( $_REQUEST['_eddmp_ios_controls'] ) ) ? true : false;
				$troubleshoot_onload            = ( isset( $_REQUEST['_eddmp_onload'] ) ) ? true : false;
				$include_main_player_hook       = ( isset( $_REQUEST['_eddmp_main_player_hook'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['_eddmp_main_player_hook'] ) ) : '';
				$include_all_players_hook       = ( isset( $_REQUEST['_eddmp_all_players_hook'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['_eddmp_all_players_hook'] ) ) : '';

				$enable_player    = ( isset( $_REQUEST['_eddmp_enable_player'] ) ) ? 1 : 0;
				$show_in          = ( isset( $_REQUEST['_eddmp_show_in'] ) && in_array( $_REQUEST['_eddmp_show_in'], array( 'single', 'multiple' ) ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['_eddmp_show_in'] ) ) : 'all';
				$players_in_cart  = ( isset( $_REQUEST['_eddmp_players_in_cart'] ) ) ? true : false;
				$player_style     = (
						isset( $_REQUEST['_eddmp_player_layout'] ) &&
						in_array( $_REQUEST['_eddmp_player_layout'], $this->_player_layouts )
					) ? sanitize_text_field( wp_unslash( $_REQUEST['_eddmp_player_layout'] ) ) : EDDMP_DEFAULT_PLAYER_LAYOUT;
				 $player_controls = (
						isset( $_REQUEST['_eddmp_player_controls'] ) &&
						in_array( $_REQUEST['_eddmp_player_controls'], $this->_player_controls )
					) ? sanitize_text_field( wp_unslash( $_REQUEST['_eddmp_player_controls'] ) ) : EDDMP_DEFAULT_PLAYER_CONTROLS;

				 $player_title        = ( isset( $_REQUEST['_eddmp_player_title'] ) ) ? 1 : 0;
				 $play_all            = ( isset( $_REQUEST['_eddmp_play_all'] ) ) ? 1 : 0;
				 $loop                = ( isset( $_REQUEST['_eddmp_loop'] ) ) ? 1 : 0;
				 $play_simultaneously = ( isset( $_REQUEST['_eddmp_play_simultaneously'] ) ) ? 1 : 0;
				 $volume              = ( isset( $_REQUEST['_eddmp_player_volume'] ) && is_numeric( $_REQUEST['_eddmp_player_volume'] ) ) ? floatval( $_REQUEST['_eddmp_player_volume'] ) : 1;
				 $preload             = (
						isset( $_REQUEST['_eddmp_preload'] ) &&
						in_array( $_REQUEST['_eddmp_preload'], array( 'none', 'metadata', 'auto' ) )
					) ? sanitize_text_field( wp_unslash( $_REQUEST['_eddmp_preload'] ) ) : 'none';

				 $message         = ( isset( $_REQUEST['_eddmp_message'] ) ) ? wp_kses_post( wp_unslash( $_REQUEST['_eddmp_message'] ) ) : '';
				 $fade_out        = ( isset( $_REQUEST['_eddmp_fade_out'] ) ) ? 1 : 0;

				 $apply_to_all_players = ( isset( $_REQUEST['_eddmp_apply_to_all_players'] ) ) ? 1 : 0;

				 $global_settings = array(
					 '_eddmp_registered_only'       => $registered_only,
					 '_eddmp_fade_out'              => $fade_out,
					 '_eddmp_enable_player'         => $enable_player,
					 '_eddmp_show_in'               => $show_in,
					 '_eddmp_players_in_cart'       => $players_in_cart,
					 '_eddmp_player_layout'         => $player_style,
					 '_eddmp_player_volume'         => $volume,
					 '_eddmp_player_controls'       => $player_controls,
					 '_eddmp_player_title'          => $player_title,
					 '_eddmp_play_all'              => $play_all,
					 '_eddmp_loop'                  => $loop,
					 '_eddmp_play_simultaneously'   => $play_simultaneously,
					 '_eddmp_preload'               => $preload,
					 '_eddmp_message'               => $message,
					 '_eddmp_default_extension'     => $troubleshoot_default_extension,
					 '_eddmp_ios_controls'          => $ios_controls,
					 '_eddmp_onload'                => $troubleshoot_onload,
					 '_eddmp_main_player_hook'      => $include_main_player_hook,
					 '_eddmp_all_players_hook'      => $include_all_players_hook,
					 '_eddmp_analytics_integration' => ( isset( $_REQUEST['_eddmp_analytics_integration'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['_eddmp_analytics_integration'] ) ) : 'ua',
					 '_eddmp_analytics_property'    => ( isset( $_REQUEST['_eddmp_analytics_property'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['_eddmp_analytics_property'] ) ) : '',
					 '_eddmp_analytics_api_secret'  => ( isset( $_REQUEST['_eddmp_analytics_api_secret'] ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['_eddmp_analytics_api_secret'] ) ) : '',
					 '_eddmp_apply_to_all_players' => $apply_to_all_players,
				 );

				 if ( $apply_to_all_players || isset( $_REQUEST['_eddmp_delete_demos'] ) ) {
					 $this->_clearDir( $this->_files_directory_path );
				 }

				 if ( $apply_to_all_players ) {
					 $downloads_ids = array(
						 'post_type'     => $this->_get_post_types(),
						 'numberposts'   => -1,
						 'post_status'   => array( 'publish', 'pending', 'draft', 'future' ),
						 'fields'        => 'ids',
						 'cache_results' => false,
					 );

					 $downloads = get_posts( $downloads_ids );
					 foreach ( $downloads as $download_id ) {
						 update_post_meta( $download_id, '_eddmp_enable_player', $enable_player );
						 update_post_meta( $download_id, '_eddmp_show_in', $show_in );
						 update_post_meta( $download_id, '_eddmp_player_layout', $player_style );
						 update_post_meta( $download_id, '_eddmp_player_controls', $player_controls );
						 update_post_meta( $download_id, '_eddmp_player_volume', $volume );
						 update_post_meta( $download_id, '_eddmp_player_title', $player_title );
						 update_post_meta( $download_id, '_eddmp_play_all', $play_all );
						 update_post_meta( $download_id, '_eddmp_loop', $loop );
						 update_post_meta( $download_id, '_eddmp_preload', $preload );
					 }
				 }

				 update_option( 'eddmp_global_settings', $global_settings );
				 $this->_global_attrs = $global_settings;
				 do_action( 'eddmp_save_setting' );
			} // Save settings

			print '<div class="wrap">'; // Open Wrap
			include_once dirname( __FILE__ ) . '/views/global_options.php';
			print '</div>'; // Close Wrap
		} // End settings_page

		public function settings_page_url() {
			return admin_url( 'options-general.php?page=music-player-for-easy-digital-downloads-settings' );
		} // End settings_page_url

		public function save_post( $post_id, $post, $update ) {
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return;
			}
			$post_types = $this->_get_post_types();
			if (
				! isset( $post ) ||
				! in_array( $post->post_type, $post_types ) ||
				! current_user_can( 'edit_post', $post_id ) ||
				! isset( $_POST['eddmp_nonce'] ) ||
				! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['eddmp_nonce'] ) ), 'eddmp_updating_download' )
			) {
				return;
			}

			$this->delete_post( $post_id );

			// Save the player options
			$enable_player = ( isset( $_REQUEST['_eddmp_enable_player'] ) ) ? 1 : 0;
			$show_in       = ( isset( $_REQUEST['_eddmp_show_in'] ) && in_array( $_REQUEST['_eddmp_show_in'], array( 'single', 'multiple' ) ) ) ? sanitize_text_field( wp_unslash( $_REQUEST['_eddmp_show_in'] ) ) : 'all';
			$player_style  = (
					isset( $_REQUEST['_eddmp_player_layout'] ) &&
					in_array( $_REQUEST['_eddmp_player_layout'], $this->_player_layouts )
				) ? sanitize_text_field( wp_unslash( $_REQUEST['_eddmp_player_layout'] ) ) : EDDMP_DEFAULT_PLAYER_LAYOUT;

			$player_controls = (
					isset( $_REQUEST['_eddmp_player_controls'] ) &&
					in_array( $_REQUEST['_eddmp_player_controls'], $this->_player_controls )
				) ? sanitize_text_field( wp_unslash( $_REQUEST['_eddmp_player_controls'] ) ) : EDDMP_DEFAULT_PLAYER_CONTROLS;

			$player_title = ( isset( $_REQUEST['_eddmp_player_title'] ) ) ? 1 : 0;
			$play_all     = ( isset( $_REQUEST['_eddmp_play_all'] ) ) ? 1 : 0;
			$loop         = ( isset( $_REQUEST['_eddmp_loop'] ) ) ? 1 : 0;
			$volume       = ( isset( $_REQUEST['_eddmp_player_volume'] ) && is_numeric( $_REQUEST['_eddmp_player_volume'] ) ) ? floatval( $_REQUEST['_eddmp_player_volume'] ) : 1;
			$preload      = (
					isset( $_REQUEST['_eddmp_preload'] ) &&
					in_array( $_REQUEST['_eddmp_preload'], array( 'none', 'metadata', 'auto' ) )
				) ? sanitize_text_field( wp_unslash( $_REQUEST['_eddmp_preload'] ) ) : 'none';

			add_post_meta( $post_id, '_eddmp_enable_player', $enable_player, true );
			add_post_meta( $post_id, '_eddmp_show_in', $show_in, true );
			add_post_meta( $post_id, '_eddmp_player_layout', $player_style, true );
			add_post_meta( $post_id, '_eddmp_player_volume', $volume, true );
			add_post_meta( $post_id, '_eddmp_player_controls', $player_controls, true );
			add_post_meta( $post_id, '_eddmp_player_title', $player_title, true );
			add_post_meta( $post_id, '_eddmp_preload', $preload, true );
			add_post_meta( $post_id, '_eddmp_play_all', $play_all, true );
			add_post_meta( $post_id, '_eddmp_loop', $loop, true );
		} // End save_post

		public function delete_post( $post_id ) {
			$post       = get_post( $post_id );
			$post_types = $this->_get_post_types();
			if ( ! isset( $post ) || ! in_array( $post->post_type, $post_types ) || ! current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

			// Delete truncated version of the audio file
			$this->_delete_truncated_files( $post_id );

			delete_post_meta( $post_id, '_eddmp_enable_player' );
			delete_post_meta( $post_id, '_eddmp_show_in' );
			delete_post_meta( $post_id, '_eddmp_player_layout' );
			delete_post_meta( $post_id, '_eddmp_player_volume' );
			delete_post_meta( $post_id, '_eddmp_player_controls' );
			delete_post_meta( $post_id, '_eddmp_player_title' );
			delete_post_meta( $post_id, '_eddmp_preload' );
			delete_post_meta( $post_id, '_eddmp_play_all' );
			delete_post_meta( $post_id, '_eddmp_loop' );

			do_action( 'eddmp_delete_post', $post_id );
		} // End delete_post

		public function enqueue_resources() {
			if ( $this->_enqueued_resources ) {
				return;
			}
			$this->_enqueued_resources = true;

			if ( function_exists( 'wp_add_inline_script' ) ) {
				wp_add_inline_script( 'wp-mediaelement', 'try{if(mejs && mejs.i18n && "undefined" == typeof mejs.i18n.locale) mejs.i18n.locale={};}catch(mejs_err){if(console) console.log(mejs_err);};' );
			}

			// Registering resources
			wp_enqueue_style( 'wp-mediaelement' );

			wp_enqueue_style( 'wp-mediaelement-skins', plugin_dir_url( __FILE__ ) . 'vendors/mejs-skins/mejs-skins.min.css', array(), EDDMP_PLUGIN_VERSION );
			wp_enqueue_style( 'eddmp-style', plugin_dir_url( __FILE__ ) . 'css/style.css', array(), EDDMP_PLUGIN_VERSION );
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'wp-mediaelement' );
			wp_enqueue_script( 'eddmp-script', plugin_dir_url( __FILE__ ) . 'js/public.js', array( 'jquery', 'wp-mediaelement' ), EDDMP_PLUGIN_VERSION );

			$play_all = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_play_all', 0 );

			$play_simultaneously = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_play_simultaneously', 0 );

			if ( is_single() ) {
				global $post;
				$post_types = $this->_get_post_types();
				if ( ! empty( $post ) && in_array( $post->post_type, $post_types ) ) {
					$play_all = $GLOBALS['EDDMusicPlayer']->get_download_attr( $post->ID, '_eddmp_play_all', $play_all );
				}
			}
			wp_localize_script(
				'eddmp-script',
				'eddmp_global_settings',
				array(
					'fade_out'            => $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_fade_out', 1 ),
					'play_all'            => intval( $play_all ),
					'play_simultaneously' => intval( $play_simultaneously ),
					'ios_controls'        => $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_ios_controls', false ),
					'onload'              => $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_onload', false ),
				)
			);
		} // End enqueue_resources

		/**
		 * Replace the shortcode to display a playlist with all songs.
		 */
		public function replace_playlist_shortcode( $atts ) {
			global $post;

			$output = '';
			if ( ! $this->_insert_player ) {
				return $output;
			}

			if ( ! is_array( $atts ) ) {
				$atts = array();
			}
			$post_types = $this->_get_post_types();
			if ( empty( $atts['downloads_ids'] ) && ! empty( $post ) && in_array( $post->post_type, $post_types ) ) {
				try {
					ob_start();
					$this->include_all_players( $post->ID );
					$output = ob_get_contents();
					ob_end_clean();

					$class  = esc_attr( isset( $atts['class'] ) ? $atts['class'] : '' );
					$output = strpos( $output, 'eddmp-player-list' ) !== false ?
						str_replace( 'eddmp-player-list', $class . ' eddmp-player-list', $output ) :
						str_replace( 'eddmp-player-container', $class . ' eddmp-player-container', $output );

					return $output;
				} catch ( Exception $err ) {
					$atts['downloads_ids'] = $post->ID;
				}
			}

			extract( // phpcs:ignore WordPress.PHP.DontExtract
				shortcode_atts(
					array(
						'downloads_ids'              => '*',
						'purchased'                  => 0,
						'highlight_current_download' => 0,
						'continue_playing'           => 0,
						'player_style'               => EDDMP_DEFAULT_PLAYER_LAYOUT,
						'controls'                   => 'track',
						'cover'                      => 0,
						'volume'                     => 1,
						'class'                      => '',
						'loop'                       => 0,
					),
					$atts
				)
			);

			// Typecasting variables.
			$cover  = @intval( $cover );
			$volume = @floatval( $volume );

			// get the downloads ids
			$downloads_ids = preg_replace( '/[^\d\,\*]/', '', $downloads_ids );
			$downloads_ids = preg_replace( '/(\,\,)+/', '', $downloads_ids );
			$downloads_ids = trim( $downloads_ids, ',' );

			if ( strlen( $downloads_ids ) == 0 ) {
				return $output;
			}

			// MAIN CODE GOES HERE
			global $wpdb, $post;

			$current_post_id = ! empty( $post ) ? ( is_int( $post ) ? $post : $post->ID ) : -1;

			$query = 'SELECT posts.ID, posts.post_title FROM ' . $wpdb->posts . ' AS posts, ' . $wpdb->postmeta . ' as postmeta WHERE posts.post_status="publish" AND posts.post_type IN (' . $this->_get_post_types( true ) . ') AND posts.ID = postmeta.post_id AND postmeta.meta_key="_eddmp_enable_player" AND (postmeta.meta_value="yes" OR postmeta.meta_value="1")';

			if ( ! empty( $purchased ) ) {
				// Get the list of downloads purchased by the current user
				$user_id = get_current_user_id();
				if ( empty( $user_id ) ) {
					return $output;
				}

				$downloads = edd_get_users_purchased_products( $user_id );
				if ( empty( $downloads ) ) {
					return $output;
				}
				$comma         = '';
				$downloads_ids = '';
				foreach ( $downloads as $download ) {
					$downloads_ids .= $comma . $download->ID;
					$comma          = ',';}

				$query .= ' AND posts.ID IN (' . $downloads_ids . ')';
				$query .= ' ORDER BY FIELD(posts.ID,' . $downloads_ids . ')';
			} else {
				if ( strpos( '*', $downloads_ids ) === false ) {
					$query .= ' AND posts.ID IN (' . $downloads_ids . ')';
					$query .= ' ORDER BY FIELD(posts.ID,' . $downloads_ids . ')';
				} else {
					$query .= ' ORDER BY posts.post_title ASC';
				}
			}

			$downloads = $wpdb->get_results( $query ); // phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared

			if ( ! empty( $downloads ) ) {
				// Enqueue resources
				$this->enqueue_resources();
				wp_enqueue_style( 'eddmp-playlist-widget-style', plugin_dir_url( __FILE__ ) . 'widgets/playlist_widget/css/style.css', array(), EDDMP_PLUGIN_VERSION );
				wp_enqueue_script( 'eddmp-playlist-widget-script', plugin_dir_url( __FILE__ ) . 'widgets/playlist_widget/js/public.js', array(), EDDMP_PLUGIN_VERSION );
				wp_localize_script(
					'eddmp-playlist-widget-script',
					'eddmp_widget_settings',
					array( 'continue_playing' => $continue_playing )
				);

				$counter = 0;
				$output .= '<div data-loop="' . ( is_numeric( $loop ) && intval( $loop ) ? 1 : 0 ) . '">';
				foreach ( $downloads as $download ) {
					$download_obj = edd_get_download( $download->ID );
					$counter++;
					$preload   = $this->get_download_attr( $download->ID, '_eddmp_preload', '' );
					$class_row = 'eddmp-even-download';
					if ( 1 == $counter % 2 ) {
						$class_row = 'eddmp-odd-download';
					}

					$audio_files = $this->get_download_files( $download->ID );
					if ( ! is_array( $audio_files ) ) {
						continue;
					}

					if ( $cover ) {
						$featured_image = get_the_post_thumbnail_url( $download->ID );
					}

					$price   = edd_price( $download->ID, false );
					$output .= '
						<div class="eddmp-widget-download controls-' . esc_attr( $controls ) . ' ' . esc_attr( $class ) . ' ' . esc_attr( $class_row ) . ' ' . esc_attr( ( $download->ID == $current_post_id && $highlight_current_download ) ? 'eddmp-current-download' : '' ) . '">
							<div class="eddmp-widget-download-header">
								<div class="eddmp-widget-download-title">
									<a href="' . esc_url( get_permalink( $download->ID ) ) . '">' . get_the_title( $download_obj ) . '</a>
								</div><!-- download title -->
					';

					$output .= '<div class="eddmp-widget-download-price">' . $price . '</div><!-- download price -->';

					$output .= '</div>
							<div class="eddmp-widget-download-files">
					';

					if ( ! empty( $featured_image ) ) {
						$output .= '<img src="' . esc_attr( $featured_image ) . '" class="eddmp-widget-feature-image" /><div class="eddmp-widget-download-files-list">';
					}

					foreach ( $audio_files as $index => $file ) {
						$audio_url  = $this->generate_audio_url( $download->ID, $index, $file );
						$duration   = $this->_get_duration_by_url( $file['file'] );
						$audio_tag  = apply_filters(
							'eddmp_widget_audio_tag',
							$this->get_player(
								$audio_url,
								array(
									'player_controls' => $controls,
									'player_style'    => $player_style,
									'media_type'      => $file['media_type'],
									'id'              => $index,
									'duration'        => $duration,
									'preload'         => $preload,
									'volume'          => $volume,
								)
							),
							$download->ID,
							$index
						);
						$file_title = esc_html( apply_filters( 'eddmp_widget_file_name', ( ! empty( $file['name'] ) ? $file['name'] : $download->post_title ), $download->ID, $index ) );
						$output    .= '
							<div class="eddmp-widget-download-file">
								' . $audio_tag . '' . $file_title . '<div style="clear:both;"></div>
							</div><!--download file -->
						';
					}

					if ( ! empty( $featured_image ) ) {
						$output .= '</div>';
					}

					$output .= '
							</div><!-- download-files -->
						</div><!-- download -->
					';
				}
				$output .= '</div>';
				$message = $this->get_global_attr( '_eddmp_message', '' );
				if ( ! empty( $message ) ) {
					$output .= '<div class="eddmp-message">' . __( $message, 'music-player-for-easy-digital-downloads' ) . '</div>'; // phpcs:ignore WordPress.WP.I18n.NonSingularStringLiteralText
				}
			}
			return $output;
		} // replace_playlist_shortcode

		public function preload( $preload, $audio_url ) {
			$result = $preload;
			if ( strpos( $audio_url, 'eddmp-action=play' ) !== false ) {
				if ( $this->_preload_times ) {
					$result = 'none';
				}
				$this->_preload_times++;
			}
			return $result;
		} // End preload

		// ******************** EASY DIGITAL DOWNLOADS ACTIONS ************************

		/**
		 * Load the additional attributes to select the player layout
		 */
		public function edd_player_settings() {
			 include_once 'views/player_options.php';
		} // End edd_player_settings

		public function get_player(
			$audio_url,
			$args = array()
		) {
			$default_args = array(
				'media_type'      => 'mp3',
				'player_style'    => EDDMP_DEFAULT_PLAYER_LAYOUT,
				'player_controls' => EDDMP_DEFAULT_PLAYER_CONTROLS,
				'duration'        => false,
				'volume'          => 1,
			);

			$args = array_merge( $default_args, $args );
			$id   = ( ! empty( $args['id'] ) ) ? 'id="' . esc_attr( $args['id'] ) . '"' : '';

			$preload = ( ! empty( $args['preload'] ) ) ? $args['preload'] : $GLOBALS['EDDMusicPlayer']->get_global_attr(
				'_eddmp_preload',
				$GLOBALS['EDDMusicPlayer']->get_global_attr( 'preload', 'none' )
			);
			$preload = apply_filters( 'eddmp_preload', $preload, $audio_url );

			return '<audio ' . (
					(
						isset( $args['volume'] ) &&
						is_numeric( $args['volume'] ) &&
						0 <= $args['volume'] * 1 &&
						$args['volume'] * 1 <= 1
					) ? 'volume="' . esc_attr( $args['volume'] ) . '"' : ''
				) . ' ' . $id . ' preload="none" data-lazyloading="' . esc_attr( $preload ) . '" class="eddmp-player ' . esc_attr( $args['player_controls'] ) . ' ' . esc_attr( $args['player_style'] ) . '" ' . ( ( ! empty( $args['duration'] ) ) ? 'data-duration="' . esc_attr( $args['duration'] ) . '"' : '' ) . '><source src="' . esc_url( $audio_url ) . '" type="audio/' . esc_attr( $args['media_type'] ) . '" /></audio>';

		} // End get_player

		public function get_download_files( $id ) {
			 $download = edd_get_download( $id );
			if ( ! empty( $download ) ) {
				return $this->_get_download_files(
					array(
						'download' => $download,
						'all'      => 1,
					)
				);
			}
			return array();
		}

		public function generate_audio_url( $download_id, $file_id, $file_data = array() ) {
			return $this->_generate_audio_url( $download_id, $file_id, $file_data );
		}

		public function include_main_player() {
			if ( ! $this->_insert_player ) {
				return;
			}
			$id = get_the_ID();
			if ( false === $id ) {
				return;
			}

			$download = edd_get_download( $id );

			$files = $this->_get_download_files(
				array(
					'download' => $download,
					'first'    => true,
				)
			);

			if ( ! empty( $files ) ) {
				$show_in = $this->get_download_attr( $id, '_eddmp_show_in', 'all' );
				if (
					( 'single' == $show_in && ( ! is_singular() || $this->_in_store ) ) ||
					( 'multiple' == $show_in && is_singular() && ! $this->_in_store )
				) {
					return;
				}

				$preload = $this->get_download_attr( $id, '_eddmp_preload', '' );
				$this->enqueue_resources();

				$player_style    = $this->get_download_attr( $id, '_eddmp_player_layout', EDDMP_DEFAULT_PLAYER_LAYOUT );
				$player_controls = ( $this->get_download_attr( $id, '_eddmp_player_controls', EDDMP_DEFAULT_PLAYER_CONTROLS ) != 'all' ) ? 'track' : '';
				$volume          = @floatval( $this->get_download_attr( $id, '_eddmp_player_volume', EDDMP_DEFAULT_PLAYER_VOLUME ) );

				$file      = reset( $files );
				$index     = key( $files );
				$duration  = $this->_get_duration_by_url( $file['file'] );
				$audio_url = $this->_generate_audio_url( $id, $index, $file );
				$audio_tag = apply_filters(
					'eddmp_audio_tag',
					$this->get_player(
						$audio_url,
						array(
							'player_controls' => $player_controls,
							'player_style'    => $player_style,
							'media_type'      => $file['media_type'],
							'duration'        => $duration,
							'preload'         => $preload,
							'volume'          => $volume,
						)
					),
					$id,
					$index
				);

				do_action( 'eddmp_before_player_shop_page', $id );
				print '<div class="eddmp-player-container download-' . esc_attr( $file['download'] ) . '">' . $audio_tag . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
				do_action( 'eddmp_after_player_shop_page', $id );
			}
		} // End include_main_player

		public function include_all_players( $download ) {
			if ( ! $this->_insert_player ) {
				return;
			}
			if ( ! is_object( $download ) ) {
				$download = edd_get_download( $download );
			}
			$files = $this->_get_download_files(
				array(
					'download' => $download,
					'all'      => true,
				)
			);
			if ( ! empty( $files ) ) {
				$id = $download->ID;

				$show_in = $this->get_download_attr( $id, '_eddmp_show_in', 'all' );
				if (
					( 'single' == $show_in && ! is_singular() ) ||
					( 'multiple' == $show_in && is_singular() )
				) {
					return;
				}

				$preload = $this->get_download_attr( $id, '_eddmp_preload', '' );
				$loop    = $this->get_download_attr( $id, '_eddmp_loop', '' );
				$this->enqueue_resources();
				$player_style    = $this->get_download_attr( $id, '_eddmp_player_layout', EDDMP_DEFAULT_PLAYER_LAYOUT );
				$volume          = @floatval( $this->get_download_attr( $id, '_eddmp_player_volume', EDDMP_DEFAULT_PLAYER_VOLUME ) );
				$player_controls = $this->get_download_attr( $id, '_eddmp_player_controls', EDDMP_DEFAULT_PLAYER_CONTROLS );
				$player_title    = intval( $this->get_download_attr( $id, '_eddmp_player_title', EDDMP_DEFAULT_PlAYER_TITLE ) );

				$counter = count( $files );
				do_action( 'eddmp_before_players_download_page', $id );
				if ( 1 == $counter ) {
					$player_controls = ( 'button' == $player_controls ) ? 'track' : '';
					$file            = reset( $files );
					$index           = key( $files );
					$duration        = $this->_get_duration_by_url( $file['file'] );
					$audio_url       = $this->_generate_audio_url( $id, $index, $file );
					$audio_tag       = apply_filters(
						'eddmp_audio_tag',
						$this->get_player(
							$audio_url,
							array(
								'player_controls' => $player_controls,
								'player_style'    => $player_style,
								'media_type'      => $file['media_type'],
								'duration'        => $duration,
								'preload'         => $preload,
								'volume'          => $volume,
							)
						),
						$id,
						$index
					);
					$title           = esc_html( ( $player_title ) ? apply_filters( 'eddmp_file_name', $file['name'], $id, $index ) : '' );
					print '<div class="eddmp-player-container download-' . esc_attr( $file['download'] ) . '" ' . ( $loop ? 'data-loop="1"' : '' ) . '>' . $audio_tag . '</div><div class="eddmp-player-title">' . esc_html( $title ) . '</div><div style="clear:both;"></div>'; // phpcs:ignore WordPress.Security.EscapeOutput
				} elseif ( $counter > 1 ) {
					$before = '<table class="eddmp-player-list" ' . ( $loop ? 'data-loop="1"' : '' ) . '>';
					$after  = '';
					foreach ( $files as $index => $file ) {
						$evenOdd = ( 1 == $counter % 2 ) ? 'eddmp-odd-row' : 'eddmp-even-row';
						$counter--;
						$audio_url = $this->_generate_audio_url( $id, $index, $file );
						$duration  = $this->_get_duration_by_url( $file['file'] );
						$audio_tag = apply_filters(
							'eddmp_audio_tag',
							$this->get_player(
								$audio_url,
								array(
									'player_style'    => $player_style,
									'player_controls' => ( 'all' != $player_controls ) ? 'track' : '',
									'media_type'      => $file['media_type'],
									'duration'        => $duration,
									'preload'         => $preload,
									'volume'          => $volume,
								)
							),
							$id,
							$index
						);
						$title     = esc_html( ( $player_title ) ? apply_filters( 'eddmp_file_name', $file['name'], $id, $index ) : '' );

						print $before; // phpcs:ignore WordPress.Security.EscapeOutput
						$before = '';
						$after  = '</table>';
						if ( 'all' != $player_controls ) {
							print '<tr class="' . esc_attr( $evenOdd ) . ' download-' . esc_attr( $file['download'] ) . '"><td class="eddmp-player-container eddmp-column-player-' . esc_attr( $player_style ) . '">' . $audio_tag . '</td><td class="eddmp-player-title eddmp-column-player-title">' . esc_html( $title ) . '</td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
						} else {
							print '<tr class="' . esc_attr( $evenOdd ) . ' download-' . esc_attr( $file['download'] ) . '"><td><div class="eddmp-player-container">' . $audio_tag . '</div><div class="eddmp-player-title eddmp-column-player-title">' . esc_html( $title ) . '</div></td></tr>'; // phpcs:ignore WordPress.Security.EscapeOutput
						}
					}
					print $after; // phpcs:ignore WordPress.Security.EscapeOutput
				}
				$message = $this->get_global_attr( '_eddmp_message', '' );
				if ( ! empty( $message ) ) {
					print '<div class="eddmp-message">' . __( $message, 'music-player-for-easy-digital-downloads' ) . '</div>'; // @codingStandardsIgnoreLine
				}
				do_action( 'eddmp_after_players_download_page', $id );
			}
		} // End include_all_players

		public function player_in_cart( $cart_item, $cart_item_key ) {
			$download = edd_get_download( $cart_item['id'] );
			$this->include_all_players( $download );
		} // player_in_cart

		public function add_data_download( $player, $download_id, $index ) {
			$player = preg_replace( '/<audio\b/i', '<audio controlslist="nodownload" data-download="' . esc_attr( $download_id ) . '" ', $player );
			return $player;
		} // End add_data_download

		// ******************** PRIVATE METHODS ************************

		private function _get_post_types( $mysql_in = false ) {
			 $post_types = array( 'download' );
			if ( ! empty( $GLOBALS['eddmp_post_types'] ) && is_array( $GLOBALS['eddmp_post_types'] ) ) {
				$post_types = $GLOBALS['eddmp_post_types'];
			}
			if ( $mysql_in ) {
				return '"' . implode( '","', $post_types ) . '"';
			}
			return $post_types;
		} // End _get_post_types

		private function _load_addons() {
			$path  = __DIR__ . '/addons';
			$eddmp = $this;

			if ( file_exists( $path ) ) {
				$addons = dir( $path );
				while ( false !== ( $entry = $addons->read() ) ) {
					if ( strlen( $entry ) > 3 && strtolower( pathinfo( $entry, PATHINFO_EXTENSION ) ) == 'php' ) {
						include_once $addons->path . '/' . $entry;
					}
				}
			}
		} // End _load_addons

		private function _preview() {
			$user          = wp_get_current_user();
			$allowed_roles = array( 'editor', 'administrator', 'author' );

			if ( array_intersect( $allowed_roles, $user->roles ) ) {
				if ( ! empty( $_REQUEST['eddmp-preview'] ) ) {
					// Sanitizing variable
					$preview = sanitize_text_field( wp_unslash( $_REQUEST['eddmp-preview'] ) );

					// Remove every shortcode that is not in the plugin
					remove_all_shortcodes();
					add_shortcode( 'eddmp-playlist', array( &$this, 'replace_playlist_shortcode' ) );

					if ( has_shortcode( $preview, 'eddmp-playlist' ) ) {
						print '<!DOCTYPE html>';
						$if_empty = __( 'There are no downloads that satisfy the block\'s settings', 'music-player-for-easy-digital-downloads' );
						wp_enqueue_script( 'jquery' );
						$output = do_shortcode( $preview );
						if ( preg_match( '/^\s*$/', $output ) ) {
							$output = '<div>' . $if_empty . '</div>';
						}

						// Deregister all scripts and styles for loading only the plugin styles.
						global  $wp_styles, $wp_scripts;
						if ( ! empty( $wp_scripts ) ) {
							$wp_scripts->reset();
						}
						$this->enqueue_resources();
						if ( ! empty( $wp_styles ) ) {
							$wp_styles->do_items();
						}
						if ( ! empty( $wp_scripts ) ) {
							$wp_scripts->do_items();
						}

						print '<div class="eddmp-preview-container">' . $output . '</div>'; // phpcs:ignore WordPress.Security.EscapeOutput
						print '<script type="text/javascript">jQuery(window).on("load", function(){ var frameEl = window.frameElement; if(frameEl) frameEl.height = jQuery(".eddmp-preview-container").outerHeight(true)+25; });</script>';
						exit;
					}
				}
			}
		} // End _preview

		private function _createDir() {
			 // Generate upload dir
			$_files_directory            = wp_upload_dir();
			$this->_files_directory_path = rtrim( $_files_directory['basedir'], '/' ) . '/eddmp/';
			$this->_files_directory_url  = rtrim( $_files_directory['baseurl'], '/' ) . '/eddmp/';
			$this->_files_directory_url  = preg_replace( '/^http(s)?:\/\//', '//', $this->_files_directory_url );
			if ( ! file_exists( $this->_files_directory_path ) ) {
				@mkdir( $this->_files_directory_path, 0755 );
			}
		} // End _createDir

		private function _clearDir( $dirPath ) {
			try {
				if ( empty( $dirPath ) || ! file_exists( $dirPath ) || ! is_dir( $dirPath ) ) {
					return;
				}
				$dirPath = rtrim( $dirPath, '\\/' ) . '/';
				$files = glob( $dirPath . '*', GLOB_MARK );
				foreach ( $files as $file ) {
					if ( is_dir( $file ) ) {
						$this->_clearDir( $file );
					} else {
						unlink( $file );
					}
				}
			} catch ( Exception $err ) {
				return;
			}
		} // End _clearDir

		private function _get_duration_by_url( $url ) {
			 global $wpdb;
			try {
				$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE guid RLIKE %s;", $url ) );
				if ( empty( $attachment ) ) {
					$uploads_dir = wp_upload_dir();
					$uploads_url = $uploads_dir['baseurl'];
					$parsed_url  = explode( parse_url( $uploads_url, PHP_URL_PATH ), $url );
					$this_host   = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
					$file_host   = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );
					if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
						return false;
					}
					$file       = trim( $parsed_url[1], '/' );
					$attachment = $wpdb->get_col( $wpdb->prepare( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key='_wp_attached_file' AND meta_value RLIKE %s;", $file ) );
				}
				if ( ! empty( $attachment ) && ! empty( $attachment[0] ) ) {
					$metadata = wp_get_attachment_metadata( $attachment[0] );
					if ( false !== $metadata && ! empty( $metadata['length_formatted'] ) ) {
						return $metadata['length_formatted'];
					}
				}
			} catch ( Exception $err ) {
				error_log( $err->getMessage() );
			}
			return false;
		} // End _get_duration_by_url

		private function _generate_audio_url( $download_id, $file_index, $file_data = array() ) {
			if ( ! empty( $file_data['file'] ) ) {
				$file_url = $file_data['file'];
				if ( ! empty( $file_data['play_src'] ) || $this->_is_playlist( $file_url ) ) {
					return $file_url; // Play src audio file, without copying or truncate it.
				}

				// If the playback of music are tracked with Google Analytics, should not be loaded directly the audio files.
				$_eddmp_analytics_property = trim( $this->get_global_attr( '_eddmp_analytics_property', '' ) );
				if ( '' == $_eddmp_analytics_property ) {
					$files = get_post_meta( $download_id, '_eddmp_drive_files', true );
					$key   = md5( $file_url );
					if (
						! empty( $files ) &&
						isset( $files[ $key ] )
					) {
						return $files[ $key ]['url'];
					}

					$file_name   = $this->_demo_file_name( $file_url );
					$o_file_name = 'o_' . $file_name;

					$file_path   = $this->_files_directory_path . $file_name;
					$o_file_path = $this->_files_directory_path . $o_file_name;

					if ( $this->_valid_demo( $file_path ) ) {
						return 'http' . ( ( is_ssl() ) ? 's:' : ':' ) . $this->_files_directory_url . $file_name;
					} elseif ( $this->_valid_demo( $o_file_path ) ) {
						return 'http' . ( ( is_ssl() ) ? 's:' : ':' ) . $this->_files_directory_url . $o_file_name;
					}
				}
			}
			$url  = isset( $_SERVER['REQUEST_URI'] ) ? esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : '';
			$url .= ( ( strpos( $url, '?' ) === false ) ? '?' : '&' ) . 'eddmp-action=play&eddmp-download=' . $download_id . '&eddmp-file=' . $file_index;
			return $url;
		} // End _generate_audio_url

		private function _delete_truncated_files( $download_id ) {
			$files_arr  = array();
			$_files_arr = edd_get_download_files( $download_id );
			if ( ! empty( $_files_arr ) ) {
				foreach ( $_files_arr as $_files_arr_item ) {
					$files_arr[] = $_files_arr_item['file'];
				}
			}

			if ( ! empty( $files_arr ) && is_array( $files_arr ) ) {
				foreach ( $files_arr as $file ) {
					try {
						if ( is_array( $file ) && ! empty( $file['file'] ) ) {
							$file = $file['file'];
						}
						$ext       = pathinfo( $file, PATHINFO_EXTENSION );
						$file_name = md5( $file ) . ( ( ! empty( $ext ) ) ? '.' . $ext : '' );
						if ( file_exists( $this->_files_directory_path . $file_name ) ) {
							@unlink( $this->_files_directory_path . $file_name );
						}
						do_action( 'eddmp_delete_file', $download_id, $file );
					} catch ( Exception $err ) {
						error_log( $err->getMessage() );
					}
				}
			}
		} // End _delete_truncated_files

		/**
		 * Check if the file is an m3u or m3u8 playlist
		 */
		private function _is_playlist( $file_path ) {
			return preg_match( '/\.(m3u|m3u8)$/i', $file_path );
		} // End _is_playlist

		/**
		 * Check if the file is an audio file and return its type or false
		 */
		private function _is_audio( $file_path ) {
			if ( preg_match( '/\.(mp3|ogg|oga|wav|wma|mp4)$/i', $file_path, $match ) ) {
				return $match[1];
			}
			if ( preg_match( '/\.m4a$/i', $file_path ) ) {
				return 'mp4';
			}
			if ( $this->_is_playlist( $file_path ) ) {
				return 'hls';
			}

			// From troubleshoot
			$extension                      = pathinfo( $file_path, PATHINFO_EXTENSION );
			$troubleshoot_default_extension = $GLOBALS['EDDMusicPlayer']->get_global_attr( '_eddmp_default_extension', false );
			if ( ( empty( $extension ) || ! preg_match( '/^[a-z\d]{3,4}$/i', $extension ) ) && $troubleshoot_default_extension ) {
				return 'mp3';
			}

			return false;
		} // End _is_audio

		private function _sort_list( $download_a, $download_b ) {
			$name_a = $download_a->post_name;
			$name_b = $download_b->post_name;
			if ( $name_a == $name_b ) {
				return 0;
			}
			return ( $name_a < $name_b ) ? -1 : 1;
		} // End _sort_list

		private function _edit_files_array( $download_id, $files, $play_src = 0 ) {
			 $p_files = array();
			foreach ( $files as $key => $file ) {
				$p_key             = $key . '_' . $download_id;
				$p_files[ $p_key ] = array(
					'download' => $download_id,
					'name'     => $file['name'],
					'file'     => $file['file'],
					'play_src' => $play_src,
				);
			}
			return $p_files;
		} // end _edit_files_array

		private function _get_recursive_download_files( $download, $files_arr ) {
			$id            = $download->ID;
			$download_type = edd_get_download_type( $id );

			if ( ! $this->get_download_attr( $id, '_eddmp_enable_player', false ) ) {
				return $files_arr;
			}
			switch ( $download_type ) {
				case 'default':
					$_files    = edd_get_download_files( $id );
					$_files    = $this->_edit_files_array( $id, $_files );
					$files_arr = array_merge( $files_arr, $_files );
					break;
				case 'bundle':
					$children = $download->get_bundled_downloads( $id );

					foreach ( $children as $key => $child_id ) {
						$children[ $key ] = edd_get_download( $child_id );
					}

					uasort( $children, array( &$this, '_sort_list' ) ); /* replaced usort with uasort 2018.06.12 */

					foreach ( $children as $child_obj ) {
						$files_arr = $this->_get_recursive_download_files( $child_obj, $files_arr );
					}
					break;
			}
			return $files_arr;
		} // End _get_recursive_download_files

		private function _get_download_files( $args ) {
			if ( empty( $args['download'] ) ) {
				return false;
			}

			$download = $args['download'];
			$files    = $this->_get_recursive_download_files( $download, array() );
			if ( empty( $files ) ) {
				return false;
			}

			$audio_files = array();
			foreach ( $files as $index => $file ) {
				if ( ! empty( $file['file'] ) && ( $media_type = $this->_is_audio( $file['file'] ) ) !== false ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
					$file['media_type'] = $media_type;

					if ( isset( $args['file_id'] ) ) {
						if ( $args['file_id'] == $index ) {
							$audio_files[ $index ] = $file;
							return $audio_files;
						}
					} elseif ( ! empty( $args['first'] ) ) {
						$audio_files[ $index ] = $file;
						return $audio_files;
					} elseif ( ! empty( $args['all'] ) ) {
						$audio_files[ $index ] = $file;
					}
				}
			}
			return $audio_files;
		} // End _get_download_files

		private function _demo_file_name( $url ) {
			$file_extension = pathinfo( $url, PATHINFO_EXTENSION );
			$file_name      = md5( $url ) . ( ( ! empty( $file_extension ) && preg_match( '/^[a-z\d]{3,4}$/i', $file_extension ) ) ? '.' . $file_extension : '' );
			return $file_name;
		} // End _demo_file_name

		private function _valid_demo( $file_path ) {
			if ( ! file_exists( $file_path ) || filesize( $file_path ) == 0 ) {
				return false;
			}
			if ( function_exists( 'finfo_open' ) ) {
				$finfo = finfo_open( FILEINFO_MIME );
				return substr( finfo_file( $finfo, $file_path ), 0, 4 ) !== 'text';
			}
			return true;
		} // End _valid_demo

		/**
		 * Create a temporal file and redirect to the new file
		 */
		private function _output_file( $args ) {
			if ( empty( $args['url'] ) ) {
				return;
			}
			$url = $args['url'];
			$url = do_shortcode( $url );

			if ( file_exists( $url ) ) {
				$url_fixed = $url;
			} elseif ( strpos( $url, '//' ) === 0 ) {
				$url_fixed = 'http' . ( is_ssl() ? 's:' : ':' ) . $url;
			} elseif ( strpos( $url, '/' ) === 0 ) {
				$url_fixed = rtrim( EDDMP_WEBSITE_URL, '/' ) . $url;
			} else {
				$url_fixed = $url;
			}

			do_action( 'eddmp_play_file', $args['download_id'], $url );

			$file_name   = $this->_demo_file_name( $url );
			$o_file_name = 'o_' . $file_name;
			$text        = 'The requested URL was not found on this server';

			$file_path   = $this->_files_directory_path . $file_name;
			$o_file_path = $this->_files_directory_path . $o_file_name;

			if ( $this->_valid_demo( $file_path ) ) {
				header( 'location: http' . ( ( is_ssl() ) ? 's:' : ':' ) . $this->_files_directory_url . $file_name );
				exit;
			} elseif ( $this->_valid_demo( $o_file_path ) ) {
				header( 'location: http' . ( ( is_ssl() ) ? 's:' : ':' ) . $this->_files_directory_url . $o_file_name );
				exit;
			} else {
				try {
					$c = false;
					if ( ( $path = $this->_is_local( $url_fixed ) ) !== false ) { // phpcs:ignore Squiz.PHP.DisallowMultipleAssignments
						$c = copy( $path, $file_path );
					} else {
						$response = wp_remote_get(
							$url_fixed,
							array(
								'timeout'  => EDDMP_REMOTE_TIMEOUT,
								'stream'   => true,
								'filename' => $file_path,
							)
						);
						if ( ! is_wp_error( $response ) && 200 == $response['response']['code'] ) {
							$c = true;
						}
					}

					if ( true === $c ) {
						// header( "HTTP/1.1 301 Moved Permanently" );
						// header( "location: " . $this->_files_directory_url . $file_name );
						header( "Content-Type: audio/mpeg" );
						header( "Content-length: " . filesize( $file_path ) );
						readfile($file_path);
						exit;
					}
				} catch ( Exception $err ) {
					error_log( $err->getMessage() );
				}
				$text = 'It is not possible to generate the file for demo. Possible causes are: - the amount of memory allocated to the php script on the web server is not enough, - the execution time is too short, - or the "uploads/eddmp" directory does not have write permissions.';
			}
			$this->_print_page_not_found( $text );
		} // End _output_file

		/**
		 * Print not found page if file it is not accessible
		 */
		private function _print_page_not_found( $text = 'The requested URL was not found on this server' ) {
			header( 'Status: 404 Not Found' );
			echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">
				  <HTML><HEAD>
				  <TITLE>404 Not Found</TITLE>
				  </HEAD><BODY>
				  <H1>Not Found</H1>
				  <P>' . $text // phpcs:ignore WordPress.Security.EscapeOutput
				  . '</P>
				  </BODY></HTML>
				 ';
		} // End _print_page_not_found

		private function _is_local( $url ) {
			$file_path = false;
			if ( file_exists( $url ) ) {
				$file_path = $url;
			}

			if ( false === $file_path ) {
				$attachment_id = attachment_url_to_postid( $url );
				if ( $attachment_id ) {
					$attachment_path = get_attached_file( $attachment_id );
					if ( $attachment_path && file_exists( $attachment_path ) ) {
						$file_path = $attachment_path;
					}
				}
			}

			if ( false === $file_path && defined( 'ABSPATH' ) ) {
				$path_component = parse_url( $url, PHP_URL_PATH );
				$path = rtrim( ABSPATH, '/' ) . '/' . ltrim( $path_component, '/' );
				if ( file_exists( $path ) ) {
					$file_path = $path;
				}

				if ( false === $file_path ) {
					$site_url = get_site_url( get_current_blog_id() );
					$file_path = str_ireplace( $site_url . '/', ABSPATH, $url );
					if ( ! file_exists( $file_path ) ) {
						$file_path = false;
					}
				}
			}

			return apply_filters( 'eddmp_is_local', $file_path, $url );
		} // End _is_local

		private function _tracking_play_event( $download_id, $file_url ) {
			$_eddmp_analytics_integration = $this->get_global_attr( '_eddmp_analytics_integration', 'ua' );
			$_eddmp_analytics_property    = trim( $this->get_global_attr( '_eddmp_analytics_property', '' ) );
			$_eddmp_analytics_api_secret  = trim( $this->get_global_attr( '_eddmp_analytics_api_secret', '' ) );
			if ( ! empty( $_eddmp_analytics_property ) ) {
				$cid = isset( $_SERVER['REMOTE_ADDR'] ) ? sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ) : '';
				try {
					if ( isset( $_COOKIE['_ga'] ) ) {
						$_ga       = sanitize_text_field( wp_unslash( $_COOKIE['_ga'] ) );
						$cid_parts = explode( '.', $_ga, 3 );
						$cid       = $cid_parts[2];
					}
				} catch ( Exception $err ) {
					error_log( $err->getMessage() );
				}

				if ( 'ua' == $_eddmp_analytics_integration ) {
					$_response = wp_remote_post(
						'http://www.google-analytics.com/collect',
						array(
							'body' => array(
								'v'   => 1,
								'tid' => $_eddmp_analytics_property,
								'cid' => $cid,
								't'   => 'event',
								'ec'  => 'Music Player for Easy Digital Downloads',
								'ea'  => 'play',
								'el'  => $file_url,
								'ev'  => $download_id,
							),
						)
					);
				} else {
					$_response = wp_remote_post(
						'https://www.google-analytics.com/mp/collect?api_secret=' . $_eddmp_analytics_api_secret . '&measurement_id=' . $_eddmp_analytics_property,
						array(
							'sslverify' => true,
							'headers'   => array(
								'Content-Type' => 'application/json',
							),
							'body'      => json_encode(
								array(
									'client_id' => $cid,
									'events'    => array(
										array(
											'name'   => 'play',
											'params' => array(
												'event_category' => 'Music Player for Easy Digital Downloads',
												'event_label' => $file_url,
												'event_value' => $download_id,
											),
										),
									),
								)
							),
						)
					);
				}

				if ( is_wp_error( $_response ) ) {
					error_log( $_response->get_error_message() );
				}
			}
		} // _tracking_play_event

		public static function troubleshoot( $option ) {
			if ( ! is_admin() ) {
				// Solves a conflict caused by the "Speed Booster Pack" plugin
				if ( is_array( $option ) && isset( $option['jquery_to_footer'] ) ) {
					unset( $option['jquery_to_footer'] );
				}
			}
			return $option;
		} // End troubleshoot
	} // End Class EDDMusicPlayer

	$GLOBALS['EDDMusicPlayer'] = new EDDMusicPlayer();
}
