<?php
/**
 * Addons Dashboard Management
 *
 * Manage widgets, tools and staffs.
 *
 * @package ABSP
 * @since 1.0.0
 * @version 1.0.0
 */

namespace AbsoluteAddons\Settings;

use AbsoluteAddons\Controls\ABSP_Admin_Field_Base;
use AbsoluteAddons\MailChimp;
use AbsoluteAddons\Plugin;
use InvalidArgumentException;

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

class Dashboard {

	/**
	 * Admin main page slug
	 * @var string
	 */
	const PAGE_SLUG = 'absolute_addons';

	/**
	 * Admin menu capabilities
	 */
	const MENU_CAP = 'manage_options';

	/**
	 * Main Menu Page Hook.
	 *
	 * @var string
	 */
	protected static $page_hook;

	/**
	 * Tab Option Cache.
	 * @var array
	 */
	protected static $tab_options = array();

	/**
	 * Singleton instance.
	 *
	 * @var self
	 */
	protected static $instance;

	/**
	 * Create & Return Singleton instance.
	 *
	 * @return Dashboard
	 */
	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Dashboard constructor.
	 */
	private function __construct() {

		add_action( 'admin_menu', [ __CLASS__, 'register_menu' ], ABSOLUTE_ADDONS_INT_MIN );
		add_action( 'admin_menu', [ __CLASS__, 'update_menu_items' ], ABSOLUTE_ADDONS_INT_MAX );
		add_action( 'admin_enqueue_scripts', [ __CLASS__, 'enqueue' ] );

		add_action( 'wp_ajax_absp_save_widgets', [ __CLASS__, 'save_widgets' ] );
		add_action( 'wp_ajax_absp_save_integrations', [ __CLASS__, 'save_integrations' ] );
	}

	public static function save_integrations() {
		check_ajax_referer( 'absp_dashboard' );
		if ( ! isset( $_POST['absp_admin_page'] ) || isset( $_POST['absp_admin_page'] ) && 'integrations' !== $_POST['absp_admin_page'] ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid Request', 'absolute-addons' ) ] );
			die();
		}

		$settings = [];

		unset(
			$_POST['absp_admin_page'],
			$_POST['_wpnonce']
		);

		$options       = wp_unslash( $_POST );
		$errors        = [];
		$saved_options = static::get_tab_options( 'integrations' );

		foreach ( static::get_option_fields( 'integrations' ) as $section => $fields ) {
			foreach ( $fields as $field ) {
				if ( ! empty( $field['id'] ) ) {
					$field_id    = $field['id'];
					$field_value = isset( $options[ $section ][ $field_id ] ) ? $options[ $section ][ $field_id ] : '';
					// Sanitize "post" request of field.
					if ( ! isset( $field['sanitize'] ) ) {
						if ( is_array( $field_value ) ) {
							$settings[ $section ][ $field_id ] = wp_kses_post_deep( $field_value );
						} else {
							$settings[ $section ][ $field_id ] = wp_kses_post( $field_value );
						}
					} elseif ( isset( $field['sanitize'] ) && is_callable( $field['sanitize'] ) ) {
						$settings[ $section ][ $field_id ] = call_user_func( $field['sanitize'], $field_value );
					} else {
						$settings[ $section ][ $field_id ] = $field_value;
					}
					// Validate "post" request of field.
					if ( isset( $field['validate'] ) && is_callable( $field['validate'] ) ) {
						$has_validated = call_user_func( $field['validate'], $field_value );
						if ( ! empty( $has_validated ) ) {
							$settings[ $section ][ $field_id ]    = ( isset( $saved_options[ $field_id ] ) ) ? $saved_options[ $field_id ] : '';
							$errors[ $section . '-' . $field_id ] = $has_validated;
						}
					}
				}
			}
		}

		$updated = static::save_tab_options( 'integrations', $settings );

		if ( $updated ) {
			$message = esc_html__( 'Settings Successfully Updated.', 'absolute-addons' );
			if ( ! empty( $errors ) ) {
				$message = esc_html__( 'Some error occurred processing submitted data.', 'absolute-addons' );
			}
			wp_send_json_success( [
				'message' => $message,
				'errors'  => $errors,
			] );
		} else {
			$message = esc_html__( 'Unable to save settings. Please try after sometime.', 'absolute-addons' );
			if ( ! empty( $errors ) ) {
				$message = esc_html__( 'Some error occurred processing submitted data.', 'absolute-addons' );
			}
			wp_send_json_error( [
				'message' => $message,
				'errors'  => $errors,
			] );
		}

		if ( ! wp_doing_ajax() ) {
			wp_safe_redirect( wp_get_referer() . '#integrations' ); // phpcs:ignore WordPressVIPMinimum.Security.ExitAfterRedirect.NoExit
		}

		die();
	}

	public static function save_widgets() {
		check_ajax_referer( 'absp_dashboard' );

		if ( ! isset( $_POST['widgets'] ) || isset( $_POST['widgets'] ) && empty( $_POST['widgets'] ) ) {
			wp_send_json_error( [ 'message' => esc_html__( 'Invalid Request', 'absolute-addons' ) ] );
			die();
		}

		$settings = [ 'widgets' => [] ];

		foreach ( $_POST['widgets'] as $widget => $status ) { // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
			$widget = sanitize_title( $widget );
			$status = 'on' === $status ? 'on' : 'off';
			// update widget status
			$settings['widgets'][ $widget ] = $status;
		}

		$settings['updated'] = current_time( 'mysql' );

		$updated = update_option( 'absolute_addons_settings', $settings, false );

		if ( $updated ) {
			wp_send_json_success( [ 'message' => esc_html__( 'Settings Successfully Updated.', 'absolute-addons' ) ] );
		} else {
			wp_send_json_error( [ 'message' => esc_html__( 'Unable to save settings. Please try after sometime.', 'absolute-addons' ) ] );
		}

		if ( ! wp_doing_ajax() ) {
			wp_safe_redirect( wp_get_referer() . '#widgets' ); // phpcs:ignore WordPressVIPMinimum.Security.ExitAfterRedirect.NoExit
		}
		die();
	}

	public static function is_page() {
		return ( isset( $_GET['page'] ) && ( self::PAGE_SLUG === $_GET['page'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
	}

	/**
	 * Registers Admin Menus.
	 * hooked to admin_menu
	 *
	 * @return void
	 */
	public static function register_menu() {
		global $menu;

		// phpcs:disable WordPress.WP.GlobalVariablesOverride.Prohibited

		self::$page_hook = add_menu_page(
			__( 'Absolute Addons Dashboard', 'absolute-addons' ),
			__( 'Absolute Addons', 'absolute-addons' ),
			self::MENU_CAP,
			self::PAGE_SLUG,
			[ __CLASS__, 'render_dashboard' ],
			absp_plugin_url( 'assets/images/absp.png', false ),
			'58.6'
		);

		$tabs = self::get_registered_tabs();

		if ( is_array( $tabs ) ) {
			$defaults = [
				'menu_title' => '',
				'page_title' => '',
				'renderer'   => '',
			];

			foreach ( $tabs as $key => $data ) {
				$data = wp_parse_args( $data, $defaults );
				if ( empty( $data['renderer'] ) || ! is_callable( $data['renderer'] ) ) {
					continue;
				}

				if ( ! empty( $page_title ) ) {
					$page_title = $data['page_title'];
				} elseif ( ! empty( $data['menu_title'] ) ) {
					$page_title = $data['menu_title'];
				} else {
					continue;
				}

				// Add submenu.
				add_submenu_page(
					self::PAGE_SLUG,
					sprintf(
					/* translators: 1. Page Title */
						__( '%s - Absolute Addons', 'absolute-addons' ),
						$page_title
					),
					$data['menu_title'],
					'manage_options',
					self::PAGE_SLUG . '#' . $key,
					[ __CLASS__, 'render_tab_content' ] // keep a callable function so wp render proper page links..
				);
			}
		}

		// A separator!
		$menu['58.555'] = [
			'',
			'read',
			'separator-absp',
			'',
			'wp-menu-separator absp',
		];

		// phpcs:enable
	}

	public static function update_menu_items() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		global $submenu;
		if ( isset( $submenu[ self::PAGE_SLUG ] ) ) {
			$menu = $submenu[ self::PAGE_SLUG ];
			array_shift( $menu );
			$submenu[ self::PAGE_SLUG ] = $menu; // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
		}
	}

	public static function enqueue() {

		if ( apply_filters( 'absp/dashboard/load_google_font', true ) ) {
			wp_enqueue_style( 'roboto', 'https://fonts.googleapis.com/css2?family=Roboto:wght@100;300;400;500;700&display=swap', [], ABSOLUTE_ADDONS_VERSION );
		}

		// wp_enqueue_script( 'wp-hooks' );
		// wp_enqueue_script( 'wp-api-fetch' );
		wp_enqueue_script( 'wp-polyfill' );
		wp_enqueue_script( 'wp-util' );

		$css = [
			'jquery.fancybox' => 'assets/dist/css/libraries/jquery.fancybox',

			'absp_admin'      => 'assets/dist/css/admin',
		];
		$js  = [
			'sweetalert'      => 'assets/dist/js/libraries/sweetalert2.all',
			'jquery.fancybox' => [ 'assets/dist/js/libraries/jquery.fancybox', 'jquery' ],
			'beefup'          => [ 'assets/dist/js/libraries/jquery.beefup', 'jquery' ],
			'serializeJSON'   => [ 'assets/dist/js/libraries/jquery.serializejson', 'jquery' ],
			'absp_admin'      => [ 'assets/dist/js/admin', [ 'jquery', 'sweetalert' ] ],
		];

		foreach ( $css as $key => $style ) {
			$style = $style . '.css';
			wp_enqueue_style( $key, Plugin::plugin_url( $style ), [], Plugin::asset_version( $style ) );
		}

		foreach ( $js as $key => $script ) {
			$deps = [];
			if ( is_array( $script ) ) {
				list( $script, $deps ) = $script;
				if ( is_string( $deps ) ) {
					$deps = [ $deps ];
				}
			}
			$script = $script . '.js';
			wp_enqueue_script( $key, Plugin::plugin_url( $script ), $deps, Plugin::asset_version( $script ), true );
		}

		wp_localize_script( 'absp_admin', 'ABSP_ADMIN_DASHBOARD', [
			'nonce' => wp_create_nonce( 'absp_dashboard' ),
			'i18n'  => [
				'okay'                => esc_html__( 'Okay', 'absolute-addons' ),
				'cancel'              => esc_html__( 'Cancel', 'absolute-addons' ),
				'submit'              => esc_html__( 'Submit', 'absolute-addons' ),
				'success'             => esc_html__( 'Success', 'absolute-addons' ),
				'warning'             => esc_html__( 'Warning', 'absolute-addons' ),
				'error'               => esc_html__( 'Error', 'absolute-addons' ),
				'something_wrong'     => esc_html__( 'Something went wrong! Please try again after sometime.', 'absolute-addons' ),
				'e404'                => esc_html__( 'Requested Resource Not Found!', 'absolute-addons' ),
				'are_you_sure'        => esc_html__( 'Are You Sure?', 'absolute-addons' ),
				'confirm_disable_all' => esc_html__( 'Are You Sure You Want To Disable All Widgets?', 'absolute-addons' ),
				'confirm_enable_all'  => esc_html__( 'Are You Sure You Want To Enable All Widgets?', 'absolute-addons' ),
			],
		] );
	}

	protected static function get_registered_tabs() {
		$tabs = [
			'dashboard'    => [
				'menu_title' => esc_html__( 'Dashboard', 'absolute-addons' ),
				'renderer'   => [ __CLASS__, 'render_tab_content' ],
				'position'   => - 1,
			],
			'widgets'      => [
				'menu_title' => esc_html__( 'Widgets', 'absolute-addons' ),
				'renderer'   => [ __CLASS__, 'render_tab_content' ],
				'position'   => 10,
			],

			'integrations' => [
				'menu_title' => esc_html__( 'Integrations', 'absolute-addons' ),
				'renderer'   => [ __CLASS__, 'render_tab_content' ],
				'position'   => 50,
			],
			'support'      => [
				'menu_title' => esc_html__( 'Support', 'absolute-addons' ),
				'renderer'   => [ __CLASS__, 'render_tab_content' ],
				'position'   => 70,
			],
			/*'tools'        => [
				'menu_title' => esc_html__( 'Tools', 'absolute-addons' ),
				'renderer'   => [ __CLASS__, 'render_tab_content' ],
				'position'   => 50,
			],*/
		];

		if ( ! absp_has_pro() ) {
			$tabs['pro-features'] = [
				'menu_title' => '<span class="dashicons dashicons-star-filled" style="font-size: 17px"></span> ' . esc_html__( 'Go Pro', 'absolute-addons' ),
				'page_title' => esc_html__( 'Go Pro', 'absolute-addons' ),
				'renderer'   => [ __CLASS__, 'render_tab_content' ],
				'position'   => 100,
			];
		}

		uasort( $tabs, 'absp_usort_position' );

		return apply_filters( 'absp/dashboard/tabs', $tabs );
	}

	protected static function get_option_section( $tab ) {
		$sections = [
			'integrations' => [
				[
					'title' => __( 'Mailchimp', 'absolute-addons' ),
					'id'    => 'mailchimp',
				],
			],
		];

		return isset( $sections[ $tab ] ) ? $sections[ $tab ] : [];
	}

	protected static function get_option_fields( $tab, $section = '' ) {

		/** @noinspection HtmlUnknownTarget */
		$fields = [
			'integrations' => [
				'mailchimp' => [
					[
						'id'         => 'api_key',
						'title'      => __( 'API Key', 'absolute-addons' ),
						'type'       => 'text',
						'desc'       => sprintf(
						/* translators: 1. Mailchimp Admin API Creation Page URL. */
							__( 'Get Your Mailchimp API Key <a href="%s">Here</a>', 'absolute-addons' ),
							esc_url( 'https://admin.mailchimp.com/account/api/' )
						),
						'attributes' => [ 'type' => 'password' ],
						'validate'   => [ __CLASS__, 'validate_mailchimp_api' ],
					],
					[
						'id'          => 'audience_list',
						'title'       => __( 'Default Audience List', 'absolute-addons' ),
						'type'        => 'select',
						'placeholder' => __( 'Select Default Audience List', 'absolute-addons' ),
						'options'     => get_option( 'absp_mc_audience_list', [
							'' => __( 'No List Available', 'absolute-addons' ),
						] ),
					],
				],
			],
		];

		$tab_fields = isset( $fields[ $tab ] ) ? $fields[ $tab ] : [];
		if ( ! $section ) {
			return $tab_fields;
		}

		return isset( $tab_fields[ $section ] ) ? $tab_fields[ $section ] : [];
	}

	protected static function save_tab_options( $tab, $options = [] ) {
		$options['version']        = ABSOLUTE_ADDONS_VERSION;
		$options['updated']        = current_time( 'mysql' );
		self::$tab_options[ $tab ] = $options;

		return update_option( 'absp-' . $tab . '-options', absp_encrypt( $options ) );
	}

	/**
	 * @param string $tab
	 *
	 * @return false|mixed
	 */
	public static function get_tab_options( $tab ) {
		if ( ! isset( self::$tab_options[ $tab ] ) ) {
			self::$tab_options[ $tab ] = absp_decrypt( get_option( 'absp-' . $tab . '-options', '' ) );
		}

		return isset( self::$tab_options[ $tab ] ) ? self::$tab_options[ $tab ] : [];
	}

	/**
	 * @param string $tab
	 * @param string $key
	 *
	 * @return false|mixed
	 */
	public static function get_tab_section_option( $tab, $key ) {
		self::get_tab_options( $tab );

		return isset( self::$tab_options[ $tab ][ $key ] ) ? self::$tab_options[ $tab ][ $key ] : [];
	}

	protected static function render_option_fields( $tab ) {
		$sections = self::get_option_section( $tab );
		?>
		<div class="absp-admin-options--sections absp-admin-options--sections__<?php echo esc_attr( $tab ); ?>">
			<input type="hidden" name="absp_admin_page" value="<?php echo esc_attr( $tab ); ?>">
			<?php
			if ( is_array( $sections ) && ! empty( $sections ) ) {
				foreach ( $sections as $section ) {
					if ( empty( $section['id'] ) ) {
						continue;
					}

					$sec_id       = $section['id'];
					$fields       = isset( $section['fields'] ) && is_array( $section['fields'] ) ? $section['fields'] : [];
					$fields       = array_merge( $fields, self::get_option_fields( $tab, $sec_id ) );
					$section_slug = ( ! empty( $section['title'] ) ) ? sanitize_title( $section['title'] ) : '';
					$class        = 'absp-admin-options--section';
					$class        .= isset( $section['class'] ) && $section['class'] ? ' ' . $section['class'] : '';

					echo '<div class="' . esc_attr( $class ) . '" data-section-id="' . esc_attr( $section_slug ) . '">';
					if ( ! empty( $section['title'] ) ) {
						?>
						<div class="absp-admin-options--section-title">
							<h3>
								<?php if ( isset( $section['icon'] ) && $section['icon'] ) { ?>
									<i class="absp-admin-icon <?php echo esc_attr( $section['icon'] ); ?>" aria-hidden="true"></i>
								<?php } ?>
								<?php echo esc_html( $section['title'] ); ?>
							</h3>
						</div>
						<?php
					}

					if ( isset( $section['description'] ) && $section['description'] ) {
						?>
						<div class="absp-admin-options--field absp-admin-options--section-description">
							<?php wp_kses_post_e( $section['description'] ); ?>
						</div>
						<?php
					}

					$values = self::get_tab_section_option( $tab, $sec_id );
					if ( ! empty( $fields ) ) {
						foreach ( $fields as $field ) {

							$field = wp_parse_args( $field, [
								'id'       => '',
								'title'    => '',
								'icon'     => '',
								'subtitle' => '',
								'type'     => '',
								'default'  => false,
								'value'    => '',
								'section'  => $sec_id,
							] );

							if ( empty( $field['id'] ) ) {
								continue;
							}

							if ( ! empty( $field['type'] ) ) {
								// Field Data.
								$field['value'] = isset( $values[ $field['id'] ] ) ? $values[ $field['id'] ] : $field['default'];
								$field_type     = ! empty( $field['type'] ) ? $field['type'] : '';

								/**
								 * Subclass of ABSP_Admin_Field_Base
								 * @see ABSP_Admin_Field_Base
								 */
								$class_name = '\AbsoluteAddons\Controls\Fields\ABSP_Admin_Field_' . ucfirst( $field_type );

								// Field CSS Class.
								$class = 'absp-admin-options--field';
								$class .= ( ! empty( $field['class'] ) ) ? ' ' . esc_attr( $field['class'] ) : '';
								$class .= ( ! empty( $field['pseudo'] ) ) ? ' absp-admin-options--pseudo-field' : '';
								$class .= $field_type ? ' absp-admin-options--field-' . $field_type : '';

								?>
								<div class="<?php echo esc_attr( $class ); ?>">
									<?php if ( $field['title'] ) { ?>
										<div class="absp-admin-options--title">
											<h4><?php echo esc_html( $field['title'] ); ?></h4>
											<?php if ( isset( $field['subtitle'] ) && $field['subtitle'] ) { ?>
												<div
													class="absp-admin-options--subtitle-text"><?php wp_kses_post_e( $field['subtitle'] ); ?></div>
											<?php } ?>
										</div>
										<?php
									}
									if ( $field['title'] ) { ?>
									<div class="absp-admin-options--fieldset">
										<?php
										}

										if ( class_exists( $class_name ) ) {
											$instance = new $class_name( $field, $field['value'], $sec_id );
											$instance->render();
										} else {
											?>
											<div class="absp-admin-options--no-fields">
												<p><?php esc_html_e( 'Field not found!', 'absolute-addons' ); ?></p>
											</div>
											<?php
										}

										if ( $field['title'] ) { ?>
									</div>
								<?php } ?>
									<div class="clear"></div>
								</div>
								<?php
							} else {
								?>
								<div class="absp-admin-options--no-fields">
									<p><?php esc_html_e( 'Field not found!', 'absolute-addons' ); ?></p>
								</div>
								<?php
							}
						}
					} else {
						?>
						<div class="absp-admin-options--no-fields">
							<p><?php esc_html_e( 'No data available.', 'absolute-addons' ); ?></p>
						</div>
						<?php
					}
					echo '</div>';
				}
			}
			?>
		</div>
		<?php
	}

	protected static function load_template( $template, $data = [] ) {
		$file = ABSOLUTE_ADDONS_PATH . 'templates/admin/' . $template . '.php';
		if ( is_readable( $file ) ) {
			if ( is_array( $data ) && ! empty( $data ) ) {
				extract( $data );
			}

			include( $file );
		}
	}

	protected static function load_tab( $tab, $data = [] ) {
		self::load_template( 'dashboard-tab-' . $tab, $data );
	}

	public static function render_dashboard() {
		self::load_template( 'dashboard' );
	}

	public static function render_tab_content( $tab = '', $data = [] ) {
		if ( $tab ) {
			self::load_tab( $tab, $data );
		}
	}

	public static function get_changelogs() {
		/**
		 * schema
		 * @var array $changelogs {
		 * @type array $changelog {
		 * @type string $version Eg. '1.0.0'.
		 * @type string|int $date release date.
		 * @type string $url download url for the version.
		 * @type array $logs {
		 *       log data.
		 *       'badge', 'message'
		 *     }
		 *   }
		 * }
		 */
		return [];
	}

	public static function get_social_links() {
		return [
			[
				'icon'  => 'dashicons dashicons-facebook-alt',
				'url'   => '#',
				'label' => __( 'Facebook', 'absolute-addons' ),
			],
			[
				'icon'  => 'dashicons dashicons-twitter',
				'url'   => '#',
				'label' => __( 'Twitter', 'absolute-addons' ),
			],
			[
				'icon'  => 'dashicons dashicons-instagram',
				'url'   => '#',
				'label' => __( 'Instagram', 'absolute-addons' ),
			],
			[
				'icon'  => 'dashicons dashicons-youtube',
				'url'   => '#',
				'label' => __( 'YouTube', 'absolute-addons' ),
			],
		];
	}

	public static function get_badges_translations() {
		return [
			'map'  => [
				'improve'     => 'improvement',
				'improvement' => 'improvement',
				'fixed'       => 'fix',
				'fix'         => 'fix',
				'bug'         => 'fix',
				'bug-fix'     => 'fix',
				'new'         => 'new',
				'feature'     => 'new',
				'added'       => 'new',
			],
			'i18n' => [
				'improvement' => __( 'Improvement', 'absolute-addons' ),
				'fix'         => __( 'Fixed', 'absolute-addons' ),
				'new'         => __( 'Feature', 'absolute-addons' ),
			],
		];
	}

	public static function render_notices() {
		if ( self::is_page() ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
	}

	public static function validate_mailchimp_api( $value ) {
		try {
			$mc = new MailChimp( $value );
			$mc->load();
			$resp = $mc->ping();
			if ( is_wp_error( $resp ) ) {
				return $resp->get_error_message();
			} else {
				$data = $mc->fetch_audience_list();
				if ( ! is_wp_error( $data ) ) {
					$list = [];
					foreach ( $data as $item ) {
						$list[ $item->id ] = $item->name;
					}
					update_option( 'absp_mc_audience_list', $list );
				}
			}

			return ''; // return empty if ok. or error.
		} catch ( InvalidArgumentException $e ) {
			return $e->getMessage();
		}
	}
}

// End of file dashboard.php.
