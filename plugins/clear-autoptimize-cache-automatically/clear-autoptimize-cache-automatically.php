<?php
/**
 * Plugin Name: Clear Autoptimize Cache Automatically
 * Plugin URI: https://villatheme.com/extensions/clear-autoptimize-cache-automatically/
 * Description: Automatically clear Autoptimize cache at a specific time of selected days
 * Version: 1.0.1
 * Author: VillaTheme(villatheme.com)
 * Author URI: https://villatheme.com
 * Text Domain: clear-autoptimize-cache-automatically
 * Copyright 2022-2023 VillaTheme.com. All rights reserved.
 * Tested up to: 6.3
 * Requires PHP: 7.0
 **/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'VICACA_VERSION', '1.0.1' );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( ! is_plugin_active( 'autoptimize/autoptimize.php' ) ) {
	return;
}
define( 'VICACA_DIR', plugin_dir_path( __FILE__ ) );
define( 'VICACA_INCLUDES', VICACA_DIR . "includes" . DIRECTORY_SEPARATOR );
require_once VICACA_INCLUDES . "define.php";

/**
 * Class VICACA_CLEAR_AUTOPTIMIZE_CACHE_AUTOMATICALLY
 */
if ( ! class_exists( 'VICACA_CLEAR_AUTOPTIMIZE_CACHE_AUTOMATICALLY' ) ) {
	class VICACA_CLEAR_AUTOPTIMIZE_CACHE_AUTOMATICALLY {
		private static $settings;

		public function __construct() {
			self::$settings = VICACA_DATA::get_instance();
			add_action( 'init', array( $this, 'init' ) );
			add_action( 'init', array( $this, 'clear_cache' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 11 );
			add_action( 'admin_init', array( $this, 'save_settings' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_filter( 'plugin_action_links_clear-autoptimize-cache-automatically/clear-autoptimize-cache-automatically.php', array( $this, 'settings_link' ) );
			add_action( 'rest_api_init', array( $this, 'register_api' ) );
		}

		public function register_api() {
			if ( self::$settings->get_params( 'execution_link' ) ) {
				register_rest_route(
					'vicaca', '/clear', array(
						'methods'             => WP_REST_Server::READABLE,
						'callback'            => array( $this, 'clear_cache_by_link' ),
						'permission_callback' => '__return_true',
					)
				);
			}
		}

		/**
		 * @param $request WP_REST_Request
		 *
		 * @return WP_Error
		 */
		public function clear_cache_by_link( $request ) {
			$secret = sanitize_text_field( $request->get_param( 'secret' ) );
			if ( ! $secret ) {
				return new WP_Error( 'vicaca_required_secret_key', esc_html__( 'Secret key is required', 'clear-autoptimize-cache-automatically' ), array( 'status' => 404 ) );
			}
			if ( $secret !== self::$settings->get_params( 'execution_link_secret' ) ) {
				return new WP_Error( 'vicaca_invalid_secret_key', esc_html__( 'Secret key is not correct', 'clear-autoptimize-cache-automatically' ), array( 'status' => 404 ) );
			}

			return rest_ensure_response( array( 'clear' => autoptimizeCache::clearall() ) );
		}

		/**
		 * Clear Autoptimize cache based on settings
		 */
		public function clear_cache() {
			/*Do not run when it's a POST request so that it does not affect the original action*/
			if ( $_POST ) {
				return;
			}
			/*Clear by cache size*/
			if ( self::$settings->get_params( 'clear_by_cache_size' ) ) {
				$cache_stats = autoptimizeCache::stats();
				$cache_size  = self::$settings->get_params( 'cache_size' );
				if ( $cache_size ) {
					$cache_size_unit    = self::$settings->get_params( 'cache_size_unit' );
					$current_cache_size = $cache_stats[1];//Cache size in bytes
					if ( 'percent' === $cache_size_unit ) {
						$max_size   = apply_filters( 'autoptimize_filter_cachecheck_maxsize', 512 * 1024 * 1024 );//The hook and default value here are copied from Autoptimize
						$percentage = ceil( 100 * $current_cache_size / $max_size );
						if ( $percentage >= $cache_size ) {
							self::clearall();
						}
					} else {
						if ( 'gb' === $cache_size_unit ) {
							$cache_size *= 1024;
						}
						$cache_size = $cache_size * 1024 * 1024;//Convert to bytes before comparing
						if ( $current_cache_size >= $cache_size ) {
							self::clearall();
						}
					}
				}
			}
			/*Clear after a specific interval*/
			if ( self::$settings->get_params( 'clear_by_time_interval' ) ) {
				$cache_interval = self::$settings->get_params( 'cache_interval' );
				if ( $cache_interval ) {
					if ( ! get_transient( 'vicaca_clear_autoptimize_cache' ) ) {
						$cache_interval_unit = self::$settings->get_params( 'cache_interval_unit' );
						switch ( $cache_interval_unit ) {
							case 'minute':
								$cache_interval *= MINUTE_IN_SECONDS;
								break;
							case 'hour':
								$cache_interval *= HOUR_IN_SECONDS;
								break;
							case 'day':
							default:
								$cache_interval *= DAY_IN_SECONDS;
						}
						set_transient( 'vicaca_clear_autoptimize_cache', time(), $cache_interval );
						self::clearall();
					}
				}
			}
		}

		/**
		 * Clear Autoptimize cache then reload the page
		 */
		private static function clearall() {
			autoptimizeCache::clearall();
			wp_safe_redirect( esc_url_raw( add_query_arg( array() ) ) );
			exit();
		}

		/**
		 * Save the plugin settings
		 */
		public function save_settings() {
			global $vicaca_settings;
			if ( ! current_user_can( 'manage_options' ) ) {
				return;
			}
			if ( isset( $_POST['vicaca-save-settings'] ) && isset( $_POST['_vicaca_save_settings_nonce'] ) && wp_verify_nonce( sanitize_text_field( $_POST['_vicaca_save_settings_nonce'] ), 'vicaca_save_settings' ) ) {
				$args = self::$settings->get_params();
				foreach ( $args as $key => $arg ) {
					if ( isset( $_POST[ 'vicaca_' . $key ] ) ) {
						if ( is_array( $_POST[ 'vicaca_' . $key ] ) ) {
							$args[ $key ] = isset( $_POST[ 'vicaca_' . $key ] ) ? array_map( 'sanitize_text_field', $_POST[ 'vicaca_' . $key ] ) : '';
						} else {
							$args[ $key ] = sanitize_text_field( $_POST[ 'vicaca_' . $key ] );
						}
					} else {
						if ( is_array( $arg ) ) {
							$args[ $key ] = array();
						} else {
							$args[ $key ] = '';
						}
					}
				}
				if ( $args['cache_interval'] !== self::$settings->get_params( 'cache_interval' ) || $args['cache_interval_unit'] !== self::$settings->get_params( 'cache_interval_unit' ) ) {
					delete_transient( 'vicaca_clear_autoptimize_cache' );
				}
				if ( $args['cache_size_unit'] === 'percent' && $args['cache_size'] > 100 ) {
					$args['cache_size'] = 100;
				}
				$vicaca_settings = $args;
				update_option( 'vicaca_params', $args );

				self::$settings = VICACA_DATA::get_instance( true );
			}
		}

		/**
		 * Settings page
		 */
		public function page_callback() {
			?>
			<div class="wrap">
				<h2><?php esc_html_e( 'Clear Autoptimize Cache Automatically', 'clear-autoptimize-cache-automatically' ) ?></h2>
				<form method="post" action="" class="vi-ui form">
					<?php wp_nonce_field( 'vicaca_save_settings', '_vicaca_save_settings_nonce' ); ?>
					<div class="vi-ui segment">
						<table class="form-table">
							<tbody>
							<tr>
								<th>
									<label for="<?php self::set_params( 'clear_by_cache_size', true ) ?>">
										<?php esc_html_e( 'Clear by cache size', 'clear-autoptimize-cache-automatically' ) ?>
									</label>
								</th>
								<td>
									<div class="vi-ui toggle checkbox">
										<input id="<?php self::set_params( 'clear_by_cache_size', true ) ?>"
										       type="checkbox" <?php checked( self::$settings->get_params( 'clear_by_cache_size' ), 1 ) ?>
										       value="1"
										       name="<?php self::set_params( 'clear_by_cache_size' ) ?>"/>
										<label><?php esc_html_e( 'Yes', 'clear-autoptimize-cache-automatically' ) ?></label>
									</div>
									<p class="description"><?php esc_html_e( 'Clear cache whenever the cache size reaches the one set below', 'clear-autoptimize-cache-automatically' ) ?></p>
								</td>
							</tr>
							<tr>
								<th>
									<label for="<?php self::set_params( 'weekday', true ) ?>">
										<?php esc_html_e( 'Cache size', 'clear-autoptimize-cache-automatically' ) ?>
									</label>
								</th>
								<td>
									<div class="vi-ui right labeled input">
										<input type="number" min="0"
										       name="<?php self::set_params( 'cache_size' ) ?>"
										       id="<?php self::set_params( 'cache_size', true ) ?>"
										       value="<?php echo esc_attr( self::$settings->get_params( 'cache_size' ) ) ?>">
										<label for="<?php self::set_params( 'cache_size', true ) ?>"
										       class="vi-ui label">
											<select name="<?php self::set_params( 'cache_size_unit' ) ?>"
											        id="<?php self::set_params( 'cache_size_unit', true ) ?>"
											        class="<?php self::set_params( 'cache_size_unit', true ) ?> vi-ui dropdown">
												<?php
												foreach (
													array(
														'mb'      => esc_html__( 'MB', 'clear-autoptimize-cache-automatically' ),
														'gb'      => esc_html__( 'GB', 'clear-autoptimize-cache-automatically' ),
														'percent' => esc_html__( 'Percent', 'clear-autoptimize-cache-automatically' ),
													) as $key => $value
												) {
													?>
													<option value="<?php echo esc_attr( $key ); ?>" <?php selected( self::$settings->get_params( 'cache_size_unit' ), $key ) ?>><?php echo esc_html( $value ) ?></option>
													<?php
												}
												?>
											</select>
										</label>
									</div>
								</td>
							</tr>
							<tr>
								<th>
									<label for="<?php self::set_params( 'clear_by_time_interval', true ) ?>">
										<?php esc_html_e( 'Clear after a specific interval', 'clear-autoptimize-cache-automatically' ) ?>
									</label>
								</th>
								<td>
									<div class="vi-ui toggle checkbox">
										<input id="<?php self::set_params( 'clear_by_time_interval', true ) ?>"
										       type="checkbox" <?php checked( self::$settings->get_params( 'clear_by_time_interval' ), 1 ) ?>
										       value="1"
										       name="<?php self::set_params( 'clear_by_time_interval' ) ?>"/>
										<label><?php esc_html_e( 'Yes', 'clear-autoptimize-cache-automatically' ) ?></label>
									</div>
									<p class="description"><?php esc_html_e( 'Enable this if you need the cache to be cleared every hour, every day... regardless of cache size.', 'clear-autoptimize-cache-automatically' ) ?></p>
								</td>
							</tr>
							<tr>
								<th>
									<label for="<?php self::set_params( 'weekday', true ) ?>">
										<?php esc_html_e( 'Clear cache every', 'clear-autoptimize-cache-automatically' ) ?>
									</label>
								</th>
								<td>
									<div class="vi-ui right labeled input">
										<input type="number" min="1"
										       name="<?php self::set_params( 'cache_interval' ) ?>"
										       id="<?php self::set_params( 'cache_interval', true ) ?>"
										       value="<?php echo esc_attr( self::$settings->get_params( 'cache_interval' ) ) ?>">
										<label for="<?php self::set_params( 'cache_interval', true ) ?>"
										       class="vi-ui label">
											<select name="<?php self::set_params( 'cache_interval_unit' ) ?>"
											        id="<?php self::set_params( 'cache_interval_unit', true ) ?>"
											        class="<?php self::set_params( 'cache_interval_unit', true ) ?> vi-ui dropdown">
												<?php
												foreach (
													array(
														'day'    => esc_html__( 'Day', 'clear-autoptimize-cache-automatically' ),
														'hour'   => esc_html__( 'Hour', 'clear-autoptimize-cache-automatically' ),
														'minute' => esc_html__( 'Minute', 'clear-autoptimize-cache-automatically' ),
													) as $key => $value
												) {
													?>
													<option value="<?php echo esc_attr( $key ); ?>" <?php selected( self::$settings->get_params( 'cache_interval_unit' ), $key ) ?>><?php echo esc_html( $value ) ?></option>
													<?php
												}
												?>
											</select>
										</label>
									</div>
								</td>
							</tr>

							<tr>
								<th>
									<label for="<?php self::set_params( 'execution_link', true ) ?>">
										<?php esc_html_e( 'Enable execution link', 'clear-autoptimize-cache-automatically' ) ?>
									</label>
								</th>
								<td>
									<div class="vi-ui toggle checkbox">
										<input id="<?php self::set_params( 'execution_link', true ) ?>"
										       type="checkbox" <?php checked( self::$settings->get_params( 'execution_link' ), 1 ) ?>
										       value="1"
										       name="<?php self::set_params( 'execution_link' ) ?>"/>
										<label><?php esc_html_e( 'Yes', 'clear-autoptimize-cache-automatically' ) ?></label>
									</div>
									<p class="description"><?php esc_html_e( 'Autoptimize cache will be cleared whenever a request is made to the Execution link below', 'clear-autoptimize-cache-automatically' ) ?></p>
								</td>
							</tr>
							<?php
							$execution_link_secret = self::$settings->get_params( 'execution_link_secret' );
							?>
							<tr>
								<th>
									<label for="<?php self::set_params( 'execution_link_url', true ) ?>">
										<?php esc_html_e( 'Execution link', 'clear-autoptimize-cache-automatically' ) ?>
									</label>
								</th>
								<td>
									<div class="vi-ui left labeled fluid input">
										<label for="<?php self::set_params( 'execution_link_url', true ) ?>"
										       class="vi-ui label">
                                            <span class="<?php self::set_params( 'execution_link_url_buttons', true ) ?>">
                                                <span class="<?php self::set_params( 'execution_link_url_copy', true ) ?>"
                                                      title="<?php esc_attr_e( 'Copy execution link', 'clear-autoptimize-cache-automatically' ) ?>"><i
			                                                class="icon copy"></i></span>
                                                <span class="<?php self::set_params( 'execution_link_secret_refresh', true ) ?>"
                                                      title="<?php esc_attr_e( 'Generate a new secret for execution link', 'clear-autoptimize-cache-automatically' ) ?>"><i
			                                                class="icon refresh"></i></span>
                                            </span></label>
										<input id="<?php self::set_params( 'execution_link_url', true ) ?>"
										       type="text" readonly
										       value="<?php echo esc_url_raw( add_query_arg( array( 'secret' => urlencode( $execution_link_secret ) ), get_rest_url( null, 'vicaca/clear' ) ) ) ?>"/>
										<input id="<?php self::set_params( 'execution_link_secret', true ) ?>"
										       type="hidden"
										       value="<?php echo esc_attr( $execution_link_secret ) ?>"
										       name="<?php self::set_params( 'execution_link_secret' ) ?>"/>
									</div>
								</td>
							</tr>
							</tbody>
						</table>
					</div>
					<p class="<?php self::set_params( 'save-settings-container', true ) ?>">
						<button type="submit"
						        class="vi-ui button labeled icon primary <?php self::set_params( 'save-settings', true ) ?>"
						        name="<?php self::set_params( 'save-settings', true ) ?>"><i
									class="save icon"></i><?php esc_html_e( 'Save Settings', 'clear-autoptimize-cache-automatically' ) ?>
						</button>
					</p>
				</form>
				<?php do_action( 'villatheme_support_clear-autoptimize-cache-automatically' ) ?>
			</div>
			<?php
		}

		/**
		 * @param string $name
		 * @param bool $class
		 * @param bool $multiple
		 */
		public static function set_params( $name = '', $class = false, $multiple = false ) {
			if ( $name ) {
				if ( $class ) {
					echo esc_attr( 'vicaca-' . str_replace( '_', '-', $name ) );
				} else {
					if ( $multiple ) {
						echo esc_attr( 'vicaca_' . $name . '[]' );
					} else {
						echo esc_attr( 'vicaca_' . $name );
					}
				}
			}
		}

		/**
		 * Add the plugin menu page to WordPress Settings
		 */
		public function admin_menu() {
			add_options_page(
				esc_html__( 'Clear Autoptimize Cache Automatically', 'clear-autoptimize-cache-automatically' ),
				esc_html__( 'Clear Autoptimize Cache Automatically', 'clear-autoptimize-cache-automatically' ),
				'manage_options',
				'vicaca',
				array( $this, 'page_callback' )
			);
		}

		/**
		 * @param $links
		 *
		 * @return mixed
		 */
		public function settings_link( $links ) {
			$links[] = sprintf( wp_kses_post( __( '<a href="%s">Settings</a>', 'clear-autoptimize-cache-automatically' ) ), esc_url( add_query_arg( array( 'page' => 'vicaca' ), admin_url( 'options-general.php' ) ) ) );

			return $links;
		}


		/**
		 * Enqueue needed scripts
		 */
		public function admin_enqueue_scripts() {
			global $pagenow;
			$page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
			if ( $pagenow === 'options-general.php' && $page === 'vicaca' ) {
				wp_dequeue_style( 'eopa-admin-css' );

				wp_enqueue_style( 'vicaca-label', VICACA_CSS . 'label.min.css' );
				wp_enqueue_style( 'vicaca-input', VICACA_CSS . 'input.min.css' );
				wp_enqueue_style( 'vicaca-image', VICACA_CSS . 'image.min.css' );
				wp_enqueue_style( 'vicaca-transition', VICACA_CSS . 'transition.min.css' );
				wp_enqueue_style( 'vicaca-form', VICACA_CSS . 'form.min.css' );
				wp_enqueue_style( 'vicaca-icon', VICACA_CSS . 'icon.min.css' );
				wp_enqueue_style( 'vicaca-dropdown', VICACA_CSS . 'dropdown.min.css' );
				wp_enqueue_style( 'vicaca-checkbox', VICACA_CSS . 'checkbox.min.css' );
				wp_enqueue_style( 'vicaca-segment', VICACA_CSS . 'segment.min.css' );
				wp_enqueue_style( 'vicaca-table', VICACA_CSS . 'table.min.css' );
				wp_enqueue_style( 'vicaca-button', VICACA_CSS . 'button.min.css' );

				wp_enqueue_script( 'vicaca-transition', VICACA_JS . 'transition.min.js', array( 'jquery' ) );
				wp_enqueue_script( 'vicaca-dropdown', VICACA_JS . 'dropdown.min.js', array( 'jquery' ) );
				wp_enqueue_script( 'vicaca-checkbox', VICACA_JS . 'checkbox.js', array( 'jquery' ) );
				wp_enqueue_script( 'vicaca-address', VICACA_JS . 'jquery.address-1.6.min.js', array( 'jquery' ) );

				wp_enqueue_style( 'vicaca-settings', VICACA_CSS . 'settings.css' );
				wp_enqueue_script( 'vicaca-settings', VICACA_JS . 'settings.js', array( 'jquery' ), VICACA_VERSION );
				wp_localize_script( 'vicaca-settings', 'vicaca_settings_params', array(
					'execution_link_url' => esc_url_raw( add_query_arg( array( 'secret' => self::$settings->get_params( 'execution_link_secret' ) ), get_rest_url( null, 'vicaca/clear' ) ) ),
					'i18n_url_copied'    => esc_html__( 'Execution link is copied to clipboard!', 'clear-autoptimize-cache-automatically' )
				) );
			}
		}

		/**
		 * Load text domain and initialize VillaTheme support class
		 */
		public function init() {
			load_plugin_textdomain( 'clear-autoptimize-cache-automatically' );
			$this->load_plugin_textdomain();
			if ( class_exists( 'VillaTheme_Support' ) ) {
				new VillaTheme_Support(
					array(
						'support'    => 'https://wordpress.org/support/plugin/clear-autoptimize-cache-automatically/',
						'docs'       => 'https://docs.villatheme.com/clear-autoptimize-cache-automatically',
						'review'     => 'https://wordpress.org/support/plugin/clear-autoptimize-cache-automatically/reviews/?rate=5#rate-response',
						'pro_url'    => '',
						'css'        => VICACA_CSS,
						'image'      => VICACA_IMAGES,
						'slug'       => 'clear-autoptimize-cache-automatically',
						'menu_slug'  => 'options-general.php?page=vicaca',
						'version'    => VICACA_VERSION,
						'survey_url' => 'https://script.google.com/macros/s/AKfycbxMCb9efYAjWvEy2w7Xn46WMAoXxgo5oP7UJCBVROADc5nrWyuGfiuTapX4xgBkVZDT/exec'
					)
				);
			}
		}

		/**
		 * Load text domain
		 */
		public function load_plugin_textdomain() {
			$locale = apply_filters( 'plugin_locale', get_locale(), 'clear-autoptimize-cache-automatically' );
			load_textdomain( 'clear-autoptimize-cache-automatically', VICACA_LANGUAGES . "clear-autoptimize-cache-automatically-$locale.mo" );
			load_plugin_textdomain( 'clear-autoptimize-cache-automatically', false, VICACA_LANGUAGES );
		}
	}
}

new VICACA_CLEAR_AUTOPTIMIZE_CACHE_AUTOMATICALLY();