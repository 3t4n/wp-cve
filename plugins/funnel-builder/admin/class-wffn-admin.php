<?php
defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class to initiate admin functionalists
 * Class WFFN_Admin
 */
if ( ! class_exists( 'WFFN_Admin' ) ) {
	#[AllowDynamicProperties]
	class WFFN_Admin {

		private static $ins = null;
		private $funnel = null;
		private $step_against_fid = array();
		private $step_count_against_fid = array();

		/**
		 * @var WFFN_Background_Importer $updater
		 */
		public $wffn_updater;

		/**
		 * WFFN_Admin constructor.
		 */
		public function __construct() {


			/** Admin enqueue scripts*/
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_assets' ), 99 );
			add_action( 'admin_enqueue_scripts', array( $this, 'js_variables' ), 0 );
			add_action( 'admin_enqueue_scripts', array( $this, 'maybe_register_breadcrumb_nodes' ), 5 );

			/**
			 * DB updates and table installation
			 */
			add_action( 'admin_init', array( $this, 'check_db_version' ), 990 );
			add_action( 'admin_init', array( $this, 'maybe_update_database_update' ), 995 );


			add_action( 'admin_init', array( $this, 'reset_wizard' ) );
			add_action( 'admin_init', array( $this, 'maybe_force_redirect_to_wizard' ) );
			add_action( 'admin_head', array( $this, 'hide_from_menu' ) );


			add_filter( 'get_pages', array( $this, 'add_landing_in_home_pages' ), 10, 2 );
			add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 1 );

			add_action( 'admin_notices', array( $this, 'maybe_show_notices' ) );
			add_action( 'admin_notices', array( $this, 'remove_all' ), - 1 );
			add_filter( 'plugin_action_links_' . WFFN_PLUGIN_BASENAME, array( $this, 'plugin_actions' ) );

			/** Initiate Background updater if action scheduler is not available for template importing */
			add_action( 'init', array( $this, 'wffn_maybe_init_background_updater' ), 110 );
			add_filter( 'bwf_general_settings_link', function () {
				return admin_url( 'admin.php?page=bwf&path=/funnels' );
			}, 100000 );
			add_filter( 'woofunnels_show_reset_tracking', '__return_true', 999 );
			add_action( 'admin_head', array( $this, 'menu_highlight' ), 99999 );
			add_action( 'pre_get_posts', [ $this, 'load_page_to_home_page' ], 9999 );
			add_filter( 'bwf_settings_config_general', array( $this, 'settings_config' ) );

			add_filter( 'bwf_settings_config_general', array( $this, 'maybe_add_oxygen_in_global_settings' ) );
			add_filter( 'bwf_experiment_ref_link', array( $this, 'maybe_modify_link' ), 10, 2 );

			add_action( 'before_delete_post', array( $this, 'delete_funnel_step_permanently' ), 10, 2 );
			add_filter( 'wffn_rest_get_funnel_steps', array( $this, 'maybe_delete_funnel_step' ), 10, 2 );

			add_action( 'admin_bar_menu', array( $this, 'add_menu_in_admin_bar' ), 99 );

			add_action( 'updated_postmeta', [ $this, 'update_last_edit_time' ], 10, 2 );
			add_action( 'wffn_funnel_update', [ $this, 'update_last_update_time' ] );

			add_action( 'wffn_rest_plugin_activate_response', array( $this, 'maybe_add_auth_link_stripe' ), 10, 2 );

			add_filter( 'woofunnels_global_settings', [ $this, 'add_conversion_tracking_menu' ], 5 );

			add_filter( 'woofunnels_global_settings_fields', array( $this, 'add_settings_fields_array' ), 110 );

			add_action( 'wp_ajax_wffn_blocks_incompatible_switch_to_classic', array( $this, 'blocks_incompatible_switch_to_classic_cart_checkout' ) );
			add_action( 'wp_ajax_wffn_dismiss_notice', array( $this, 'ajax_dismiss_admin_notice' ) );
			add_filter( 'bwf_general_settings_default_config', [ $this, 'google_map_key_migrate' ], 10, 2 );

			if ( isset( $_GET['wfacp_id'] ) && isset( $_GET['new_ui'] ) && 'wffn' === $_GET['new_ui'] ) {
				add_action( 'init', array( $this, 'redirect_checkout_edit_link_on_new_ui' ) );
			}
			if ( defined( 'FKCART_PLUGIN_FILE' ) ) {
				add_filter( 'fkcart_app_header_menu', function ( $menu ) {
					if ( isset( $menu['analytics'] ) ) {
						return $menu;
					}

					$keys   = array_keys( $menu );
					$values = array_values( $menu );

					$indexToInsert = array_search( 'templates', $keys, true );

					array_splice( $keys, $indexToInsert, 0, 'analytics' );
					array_splice( $values, $indexToInsert, 0, [
						'analytics' => [
							'name' => 'Analytics',
							'link' => admin_url( 'admin.php?page=bwf&path=/analytics' ),
						]
					] );


					$resultArray = array_combine( $keys, $values );
					if ( isset( $menu['settings'] ) ) {
						unset( $resultArray['settings'] );
					}

					return $resultArray;
				} );
			}


		}


		/**
		 * @return WFFN_Admin|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self();
			}

			return self::$ins;
		}

		public function google_map_key_migrate( $settings ) {
			$global_settings                      = get_option( '_wfacp_global_settings', [] );
			$settings['funnelkit_google_map_key'] = isset( $global_settings['wfacp_google_address_key'] ) ? $global_settings['wfacp_google_address_key'] : '';

			return $settings;
		}

		public function add_conversion_tracking_menu( $menu ) {
			$f_tracking = array(
				'title'    => __( 'First Party Tracking', 'woofunnels' ),
				'slug'     => 'funnelkit_first_party_tracking',
				'link'     => apply_filters( 'bwf_general_settings_link', 'javascript:void(0)' ),
				'priority' => 6,
				'pro_tab'  => true
			);

			array_push( $menu, $f_tracking );
			array_push( $menu, array(
				'title'    => __( 'Pixel Tracking', 'woofunnels' ),
				'slug'     => 'funnelkit_pixel_tracking',
				'link'     => apply_filters( 'bwf_general_settings_link', 'javascript:void(0)' ),
				'priority' => 7,
			) );

			return $menu;

		}

		public function add_settings_fields_array( $settings ) {

			$temp_settings        = $settings['woofunnels_general_settings'];
			$pixel_tracking       = [];
			$first_party_tracking = [];
			$filter_setting       = array_filter( $temp_settings, function ( $v, $k ) use ( &$pixel_tracking, &$first_party_tracking ) {
				if ( in_array( $k, [ 'general', 'permalinks', 'fk_stripe_gateway', 'funnelkit_google_maps' ], true ) ) {
					return true;
				}
				if ( 'utm_parameter' === $k ) {
					$first_party_tracking[ $k ] = $v;
				} else {
					$pixel_tracking[ $k ] = $v;
				}

				return false;
			}, ARRAY_FILTER_USE_BOTH );

			if ( defined( 'WFFN_PRO_VERSION' ) ) {
				$settings['funnelkit_first_party_tracking'] = [
					[
						'tabs' => $first_party_tracking
					],
				];
			}

			$settings['funnelkit_pixel_tracking'] = [
				[
					'tabs' => $pixel_tracking
				],
			];

			$settings['woofunnels_general_settings'] = [
				[
					'heading' => __( 'License', 'funnel-builder' ),
					'tabs'    => [ 'general' => $temp_settings['general'] ],
				],
				[
					'heading' => __( 'Permalinks', 'funnel-builder' ),
					'tabs'    => [ 'permalinks' => $filter_setting['permalinks'] ]
				],
				[
					'heading' => __( 'Stripe Gateway <span class="bwf--tag is-primary">Recommended</span>', 'funnel-builder' ),
					'tabs'    => [ 'fk_stripe_gateway' => $filter_setting['fk_stripe_gateway'] ]
				],
				[
					'heading' => __( 'Google Maps', 'funnel-builder' ),
					'tabs'    => [ 'funnelkit_google_maps' => $temp_settings['funnelkit_google_maps'] ]
				],
			];

			return $settings;
		}

		public function add_automations_menu() {
			$user = WFFN_Core()->role->user_access( 'menu', 'read' );
			if ( $user ) {
				add_submenu_page( 'woofunnels', __( 'Automations', 'funnel-builder' ), __( 'Automations', 'funnel-builder' ) . '<span style="padding-left: 2px;color: #f18200; vertical-align: super; font-size: 9px;"> NEW!</span>', $user, 'bwf&path=/automations', array(
					$this,
					'bwf_funnel_pages',
				) );
			}
		}

		public function register_admin_menu() {
			$steps = WFFN_Core()->steps->get_supported_steps();
			if ( count( $steps ) < 1 ) {
				return;
			}

			$user = WFFN_Core()->role->user_access( 'menu', 'read' );
			if ( $user ) {


				add_submenu_page( 'woofunnels', __( 'Dashboard', 'funnel-builder' ), __( 'Dashboard', 'funnel-builder' ), $user, 'bwf', array(
					$this,
					'bwf_funnel_pages',
				) );

				add_submenu_page( 'woofunnels', __( 'Funnels', 'funnel-builder' ), __( 'Funnels', 'funnel-builder' ), $user, 'bwf&path=/funnels', array(
					$this,
					'bwf_funnel_pages',
				) );

				add_submenu_page( 'woofunnels', __( 'Templates', 'funnel-builder' ), __( 'Templates', 'funnel-builder' ), $user, 'bwf&path=/templates', array(
					$this,
					'bwf_funnel_pages',
				) );
				add_submenu_page( 'woofunnels', __( 'Analytics', 'funnel-builder' ), __( 'Analytics', 'funnel-builder' ), $user, 'bwf&path=/analytics', array(
					$this,
					'bwf_funnel_pages',
				) );

				add_submenu_page( 'woofunnels', __( 'Store Checkout', 'funnel-builder' ), __( 'Store Checkout', 'funnel-builder' ), $user, 'bwf&path=/store-checkout', array(
					$this,
					'bwf_funnel_pages',
				) );
			}

		}

		public function is_basic_exists() {
			return defined( 'WFFN_BASIC_FILE' );

		}

		public function bwf_funnel_pages() {

			?>
            <div id="wffn-contacts" class="wffn-page">
            </div>
			<?php

			wp_enqueue_style( 'wffn-flex-admin', $this->get_admin_url() . '/assets/css/admin.css', array(), WFFN_VERSION_DEV );


		}


		public function admin_enqueue_assets( $hook_suffix ) {
			wp_enqueue_style( 'bwf-admin-font', $this->get_admin_url() . '/assets/css/bwf-admin-font.css', array(), WFFN_VERSION_DEV );


			if ( strpos( $hook_suffix, 'woofunnels_page' ) > - 1 || strpos( $hook_suffix, 'page_woofunnels' ) > - 1 ) {
				wp_enqueue_style( 'bwf-admin-header', $this->get_admin_url() . '/assets/css/admin-global-header.css', array(), WFFN_VERSION_DEV );
			}

			if ( $this->is_wffn_flex_page( 'all' ) ) {

				wp_enqueue_style( 'wffn-flex-admin', $this->get_admin_url() . '/assets/css/admin.css', array(), WFFN_VERSION_DEV );


				if ( WFFN_Core()->admin->is_wffn_flex_page() ) {
					$this->load_react_app( 'main' );
					if ( isset( $_GET['page'] ) && $_GET['page'] === 'bwf' && method_exists( 'BWF_Admin_General_Settings', 'get_localized_bwf_data' ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
						wp_localize_script( 'wffn-contact-admin', 'bwfAdminGen', BWF_Admin_General_Settings::get_instance()->get_localized_bwf_data() );

					} else {
						wp_localize_script( 'wffn-contact-admin', 'bwfAdminGen', BWF_Admin_General_Settings::get_instance()->get_localized_data() );

					}

					add_filter( 'wffn_noconflict_scripts', function ( $scripts = array() ) {
						return array_merge( $scripts, array( 'wffn-contact-admin' ) );
					} );
				}


				do_action( 'wffn_admin_assets', $this );
			}
		}

		public function get_local_app_path() {
			return '/admin/views/contact/dist/';
		}

		public function load_react_app( $app_name = 'main' ) {
			$min              = 60 * get_option( 'gmt_offset' );
			$sign             = $min < 0 ? "-" : "+";
			$absmin           = abs( $min );
			$tz               = sprintf( "%s%02d:%02d", $sign, $absmin / 60, $absmin % 60 );
			$status_responses = ( array ) WFFN_REST_Setup::get_instance()->get_status_responses();


			$contact_page_data = array(
				'is_wc_active'        => false,
				'date_format'         => get_option( 'date_format', 'F j, Y' ),
				'time_format'         => get_option( 'time_format', 'g:i a' ),
				'lev'                 => $this->get_license_config(),
				'app_path'            => WFFN_Core()->get_plugin_url() . '/admin/views/contact/dist/',
				'timezone'            => $tz,
				'flag_img'            => WFFN_Core()->get_plugin_url() . '/admin/assets/img/phone/flags.png',
				'updated_pro_version' => defined( 'WFFN_PRO_VERSION' ) && version_compare( WFFN_PRO_VERSION, '3.0.0 beta', '>=' ),
				'get_pro_link'        => WFFN_Core()->admin->get_pro_link(),
				'wc_add_product_url'  => admin_url( 'post-new.php?post_type=product' ),
				'setup_data'          => ! empty( $status_responses['data']['statuses'] ) ? $status_responses['data']['statuses'] : []
			);
			if ( class_exists( 'WooCommerce' ) ) {
				$currency                          = get_woocommerce_currency();
				$contact_page_data['currency']     = [
					'code'              => $currency,
					'precision'         => wc_get_price_decimals(),
					'symbol'            => html_entity_decode( get_woocommerce_currency_symbol( $currency ) ),
					'symbolPosition'    => get_option( 'woocommerce_currency_pos' ),
					'decimalSeparator'  => wc_get_price_decimal_separator(),
					'thousandSeparator' => wc_get_price_thousand_separator(),
					'priceFormat'       => html_entity_decode( get_woocommerce_price_format() ),
				];
				$contact_page_data['is_wc_active'] = true;
				$contact_page_data['admin_url']    = esc_url( $this->get_admin_url() );
			}

			$frontend_dir = ( 0 === WFFN_REACT_ENVIRONMENT ) ? WFFN_REACT_DEV_URL : WFFN_Core()->get_plugin_url() . $this->get_local_app_path();
			if ( class_exists( 'WooCommerce' ) ) {
				wp_dequeue_style( 'woocommerce_admin_styles' );
				wp_dequeue_style( 'wc-components' );
			}


			$assets_path = 1 === WFFN_REACT_ENVIRONMENT ? WFFN_PLUGIN_DIR . $this->get_local_app_path() . "$app_name.asset.php" : $frontend_dir . "/$app_name.asset.php";
			$assets      = file_exists( $assets_path ) ? include $assets_path : array(
				'dependencies' => array(
					'lodash',
					'moment',
					'react',
					'react-dom',
					'wp-api-fetch',
					'wp-components',
					'wp-compose',
					'wp-date',
					'wp-deprecated',
					'wp-block-editor',
					'wp-block-library',
					'wp-dom',
					'wp-element',
					'wp-hooks',
					'wp-html-entities',
					'wp-i18n',
					'wp-keycodes',
					'wp-polyfill',
					'wp-primitives',
					'wp-url',
					'wp-viewport',
					'wp-color-picker',
					'wp-i18n',
				),
				'version'      => time(),
			);
			$deps        = ( isset( $assets['dependencies'] ) ? array_merge( $assets['dependencies'], array( 'jquery' ) ) : array( 'jquery' ) );
			$version     = $assets['version'];

			$script_deps = array_filter( $deps, function ( $dep ) {
				return false === strpos( $dep, 'css' );
			} );
			if ( 'settings' === $app_name ) {
				$script_deps = array_merge( $script_deps, array( 'wp-color-picker' ) );
			}

			if ( class_exists( 'WFFN_Header' ) ) {
				$header_ins                       = new WFFN_Header();
				$contact_page_data['header_data'] = $header_ins->get_render_data();
			}

			$contact_page_data['localize_texts'] = apply_filters( 'wffn_localized_text_admin', array() );
			wp_enqueue_style( 'wp-components' );
			wp_enqueue_style( 'wffn_material_icons', 'https://fonts.googleapis.com/icon?family=Material+Icons+Outlined' );
			wp_enqueue_style( 'wffn-contact-admin', $frontend_dir . "$app_name.css", array(), $version );
			wp_register_script( 'wffn-contact-admin', $frontend_dir . "$app_name.js", $script_deps, $version, true );
			wp_localize_script( 'wffn-contact-admin', 'wffn_contacts_data', $contact_page_data );
			wp_enqueue_script( 'wffn-contact-admin' );
			wp_set_script_translations( 'wffn-contact-admin', 'funnel-builder' );

			$this->setup_js_for_localization( $app_name, $frontend_dir, $script_deps, $version );
			wp_enqueue_editor();
			wp_tinymce_inline_scripts();
			wp_enqueue_media();
		}


		public function get_admin_url() {
			return WFFN_Core()->get_plugin_url() . '/admin';
		}

		public function get_admin_path() {
			return WFFN_PLUGIN_DIR . '/admin';
		}

		/**
		 * @param string $page
		 *
		 * @return bool
		 */
		public function is_wffn_flex_page( $page = 'bwf' ) {

			if ( isset( $_GET['page'] ) && $_GET['page'] === $page ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return true;
			}


			if ( isset( $_GET['page'] ) && 'bwf' === $_GET['page'] && 'all' === $page ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return true;
			}

			return false;
		}


		public function js_variables() {
			if ( $this->is_wffn_flex_page( 'all' ) ) {
				$steps_data               = WFFN_Common::get_steps_data();
				$substeps_data            = WFFN_Common::get_substeps_data();
				$substeps_data['substep'] = true;

				$funnel    = $this->get_funnel();
				$funnel_id = $funnel->get_id();

				if ( $funnel_id > 0 ) {
					BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );
				}


				$upsell_exist = function_exists( 'WFOCU_Core' );


				$data = array(
					'funnel_id'  => $funnel_id,
					'steps_data' => $steps_data,
					'substeps'   => $substeps_data,
					'icons'      => array(
						'error_cross'   => '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2" class="wffn_loader wffn_loader_error">
                        <circle fill="#e6283f" stroke="#e6283f" stroke-width="6" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1" class="path circle"></circle>
                        <line fill="none" stroke="#ffffff" stroke-width="8" stroke-linecap="round" stroke-miterlimit="10" x1="34.4" y1="37.9" x2="95.8" y2="92.3" class="path line"></line>
                        <line fill="none" stroke="#ffffff" stroke-width="8" stroke-linecap="round" stroke-miterlimit="10" x1="95.8" y1="38" x2="34.4" y2="92.2" class="path line"></line>
                    </svg>',
						'success_check' => '<svg class="wffn_loader wffn_loader_ok" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 130.2 130.2">
                                <circle class="path circle" fill="#13c37b" stroke="#13c37b" stroke-width="5" stroke-miterlimit="10" cx="65.1" cy="65.1" r="62.1"></circle>
                                <polyline class="path check" fill="none" stroke="#ffffff" stroke-width="9" stroke-linecap="round" stroke-miterlimit="10" points="100.2,40.2 51.5,88.8 29.8,67.5 "></polyline>
                            </svg>',
						'delete_alert'  => '<div class="swal2-header wf_funnel-icon-without-swal"><div class="swal2-icon swal2-warning swal2-animate-warning-icon" style="display: flex;"><span class="swal2-icon-text">!</span></div></div>',
					),


					'update_funnel'    => array(
						'submit_btn'  => __( 'Update', 'funnel-builder' ),
						'label_texts' => array(
							'title' => array(
								'label'       => __( 'Name', 'funnel-builder' ),
								'placeholder' => __( 'Enter Name', 'funnel-builder' ),
								'value'       => $funnel->get_title(),
							),
							'desc'  => array(
								'label'       => __( 'Description (optional)', 'funnel-builder' ),
								'placeholder' => __( 'Enter Description (optional)', 'funnel-builder' ),
								'value'       => $funnel->get_desc(),
							),
						),
					),
					'funnel_home_link' => admin_url( 'admin.php?page=bwf&path=/funnels' ),

				);


				$data['filters']   = $this->get_template_filter();
				$data['view_link'] = $funnel->get_view_link();

				$data['settings_texts'] = apply_filters( 'wffn_funnel_settings', [] );


				$data['i18n'] = [
					'plugin_activate' => __( 'Activating plugin...', 'funnel-builder' ),
					'plugin_install'  => __( 'Installing plugin...', 'funnel-builder' ),
					'preparingsteps'  => __( 'Preparing steps...', 'funnel-builder' ),
					'redirecting'     => __( 'Redirecting...', 'funnel-builder' ),
					'importing'       => __( 'Importing...', 'funnel-builder' ),
					'custom_import'   => __( 'Setting up your funnel...', 'funnel-builder' ),
					'ribbons'         => array(
						'lite' => __( 'Lite', 'funnel-builder' ),
						'pro'  => __( 'PRO', 'funnel-builder' )
					),
					'test'            => __( 'Test', 'funnel-builder' ),
				];
				if ( wffn_is_wc_active() && false === $upsell_exist ) {
					$data['wc_upsells'] = [
						'type'      => 'wc_upsells',
						'group'     => WFFN_Steps::STEP_GROUP_WC,
						'title'     => __( 'One Click Upsells', 'funnel-builder' ),
						'desc'      => __( 'Deploy post purchase one click upsells to increase average order value', 'funnel-builder' ),
						'dashicons' => 'dashicons-tag',
						'icon'      => 'tags',
						'pro'       => true,
					];
				}
				if ( $this->is_wffn_flex_page( 'all' ) ) {
					if ( ( isset( $_GET['page'] ) && $_GET['page'] === 'bwf' ) || ( isset( $_GET['section'] ) && $_GET['section'] === 'design' ) ) { // phpcs:ignore WordPress.Security.NonceVerification
						$data['pageBuildersTexts']   = WFFN_Core()->page_builders->localize_page_builder_texts();
						$data['pageBuildersOptions'] = WFFN_Core()->page_builders->get_plugins_groupby_page_builders();
					}
				}


				$data['welcome_note_dismiss'] = get_user_meta( get_current_user_id(), '_wffn_welcome_note_dismissed', true );//phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
				$data['is_bump_dismissed']    = get_user_meta( get_current_user_id(), '_wffn_bump_promotion_hide', true );//phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
				$data['is_upsell_dismissed']  = get_user_meta( get_current_user_id(), '_wffn_upsell_promotion_hide', true );//phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta

				$data['user_display_name']   = get_user_by( 'id', get_current_user_id() )->display_name;
				$data['current_logged_user'] = get_current_user_id();
				$data['is_rtl']              = is_rtl();

				$default_builder = BWF_Admin_General_Settings::get_instance()->get_option( 'default_selected_builder' );

				$data['default_builder']          = ( ! empty( $default_builder ) ) ? $default_builder : 'elementor';
				$data['is_ab_experiment']         = class_exists( 'BWFABT_Core' ) ? 1 : 0;
				$data['is_ab_experiment_support'] = ( class_exists( 'BWFABT_Core' ) && version_compare( BWFABT_VERSION, '1.3.5', '>' ) ) ? 1 : 0;
				$data['admin_url']                = admin_url();
				$data['wizard_status']            = get_option( '_wffn_onboarding_completed', false );

				$data['automation_plugin_status']      = WFFN_Common::get_plugin_status( 'wp-marketing-automations/wp-marketing-automations.php' );
				$data['fkcart_img_url']                = esc_url( plugin_dir_url( WFFN_PLUGIN_FILE ) . 'admin/assets/img/fkcart-img.png' );
				$data['fkcart_plugin_status']          = WFFN_Common::get_plugin_status( 'cart-for-woocommerce/plugin.php' );
				$data['automation_count']              = class_exists( 'BWFAN_Model_Automations' ) ? BWFAN_Model_Automations::count_rows() : 0;
				$data['ob_arrow_blink_img_url']        = esc_url( plugin_dir_url( WFFN_PLUGIN_FILE ) . 'admin/assets/img/arrow-blink.gif' );
				$data['pro_modal_img_path']            = esc_url( plugin_dir_url( WFFN_PLUGIN_FILE ) . 'admin/assets/img/pro_modal/' );
				$data['admin_img_path']                = esc_url( plugin_dir_url( WFFN_PLUGIN_FILE ) . 'admin/assets/img/' );
				$bwf_notifications                     = get_user_meta( get_current_user_id(), '_bwf_notifications_close', true ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_get_user_meta
				$bwf_notifications                     = is_array( $bwf_notifications ) ? array_values( $bwf_notifications ) : $bwf_notifications;
				$data['user_preferences']              = array( 'notices_close' => $bwf_notifications );
				$data['user_has_notifications']        = WFFN_Core()->admin_notifications->user_has_notifications( get_current_user_id() );
				$data['pro_link']                      = $this->get_pro_link();
				$data['upgrade_button_text']           = __( 'Upgrade to PRO Now', 'funnel-builder' );
				$data['site_options']                  = get_option( 'fb_site_options', [] );
				$data['nonce_contact_export_download'] = wp_create_nonce( 'bwf_contact_export_download' );
				?>
                <script>window.wffn = <?php echo wp_json_encode( apply_filters( 'wffn_localize_admin', $data ) ); ?>;</script>
				<?php
			}
		}

		/**
		 * Get the already setup funnel object
		 * @return WFFN_Funnel
		 */
		public function get_funnel( $funnel_id = 0 ) {
			if ( $funnel_id > 0 ) {
				if ( $this->funnel instanceof WFFN_Funnel && $funnel_id === $this->funnel->get_id() ) {
					return $this->funnel;
				}
				$this->initiate_funnel( $funnel_id );
			}
			if ( $this->funnel instanceof WFFN_Funnel ) {
				return $this->funnel;
			}
			$this->funnel = new WFFN_Funnel( $funnel_id );

			return $this->funnel;
		}

		/**
		 * @param $funnel_id
		 */
		public function initiate_funnel( $funnel_id ) {
			if ( ! empty( $funnel_id ) ) {
				$this->funnel = new WFFN_Funnel( $funnel_id );

			}
		}

		public static function get_template_filter() {

			$options = [
				'all'   => __( 'All', 'funnel-builder' ),
				'sales' => __( 'Sales', 'funnel-builder' ),
				'optin' => __( 'Optin', 'funnel-builder' ),
			];

			return $options;
		}


		public function get_license_status() {
			$license_key = WFFN_Core()->remote_importer->get_license_key( true );


			if ( empty( $license_key ) ) {
				return false;
			} elseif ( isset( $license_key['manually_deactivated'] ) && 1 === $license_key['manually_deactivated'] ) {
				return 'deactiavted';
			} elseif ( isset( $license_key['expired'] ) && 1 === $license_key['expired'] ) {
				return 'expired';
			} elseif ( isset( $license_key['activated'] ) && 0 === $license_key['activated'] ) {
				return 'not-active';
			}

			return true;
		}

		public function is_license_active() {
			return true === $this->get_license_status();
		}


		/**
		 * @hooked over `admin_enqueue_scripts`
		 * Check the environment and register appropiate node for the breadcrumb to process
		 * @since 1.0.0
		 */
		public function maybe_register_breadcrumb_nodes() {
			$single_link = '';
			$funnel      = null;
			/**
			 * IF its experiment builder UI
			 */
			if ( $this->is_wffn_flex_page() ) {

				$funnel = $this->get_funnel();

			} else {

				/**
				 * its its a page where experiment page is a referrer
				 */
				$get_ref = filter_input( INPUT_GET, 'funnel_id', FILTER_UNSAFE_RAW ); //phpcs:ignore WordPressVIPMinimum.Security.PHPFilterFunctions.RestrictedFilter
				$get_ref = apply_filters( 'maybe_setup_funnel_for_breadcrumb', $get_ref );
				if ( ! empty( $get_ref ) ) {
					$funnel = $this->get_funnel( $get_ref );
					if ( absint( $funnel->get_id() ) === WFFN_Common::get_store_checkout_id() ) {
						$single_link = WFFN_Common::get_store_checkout_edit_link();
					} else {
						$single_link = WFFN_Common::get_funnel_edit_link( $funnel->get_id() );
					}
				}

			}

			/**
			 * Register nodes
			 */
			if ( ! empty( $funnel ) && null === filter_input( INPUT_GET, 'bwf_exp_ref', FILTER_UNSAFE_RAW ) ) { //phpcs:ignore WordPressVIPMinimum.Security.PHPFilterFunctions.RestrictedFilter

				BWF_Admin_Breadcrumbs::register_node( array(
					'text' => WFFN_Core()->admin->maybe_empty_title( $funnel->get_title() ),
					'link' => $single_link,
				) );
				BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel->get_id() );

			}


		}


		public function get_date_format() {
			return get_option( 'date_format', '' ) . ' ' . get_option( 'time_format', '' );
		}

		/**
		 * @return array
		 */
		public function get_funnels( $args = array() ) {
			$is_total_query_required = true;
			if ( isset( $args['offset'] ) && ! empty( $args['offset'] ) ) {
				$is_total_query_required = false;
			}
			if ( isset( $args['s'] ) ) {
				$search_str = wffn_clean( $args['s'] );
			} else {
				$search_str = isset( $_REQUEST['s'] ) ? wffn_clean( $_REQUEST['s'] ) : '';  // phpcs:ignore WordPress.Security.NonceVerification
			}
			$need_draft_count = $args['need_draft_count'] ?? false;
			if ( isset( $args['status'] ) ) {
				$status = wffn_clean( $args['status'] );
			} else {
				$status = isset( $_REQUEST['status'] ) ? wffn_clean( $_REQUEST['status'] ) : '';  // phpcs:ignore WordPress.Security.NonceVerification
			}
			$args['meta'] = isset( $args['meta'] ) ? $args['meta'] : [];
			$limit        = isset( $args['limit'] ) ? $args['limit'] : $this->posts_per_page();

			$sql_query = ' FROM {table_name}';

			$args = apply_filters( 'wffn_funnels_args_query', $args );

			if ( isset( $args['meta'] ) && is_array( $args['meta'] ) && ! empty( $args['meta'] ) && ! isset( $args['meta']['compare'] ) ) {
				$args['meta']['compare'] = '=';
			}

			/*
			 * Trying to add join in query base on meta
			 */
			if ( ! empty( $args['meta'] ) ) {
				if ( $args['meta']['compare'] === 'NOT_EXISTS' ) {
					$sql_query .= ' LEFT JOIN ';
				} else {
					$sql_query .= ' INNER JOIN ';
				}
				$sql_query .= '{table_name_meta} ON ( {table_name}.id = {table_name_meta}.bwf_funnel_id ';
				if ( $args['meta']['compare'] === 'NOT_EXISTS' ) {
					$sql_query .= 'AND {table_name_meta}.meta_key = \'' . $args['meta']['key'] . '\'';
				}
				$sql_query .= ')';

			}

			/*
			 * where clause start here in query
			 */
			$sql_query .= ' WHERE 1=1';


			if ( ! empty( $status ) && 'all' !== $status ) {
				$status    = ( 'live' === $status ) ? 1 : 0;
				$sql_query .= ' AND `status` = ' . "'$status'";
			}

			if ( ! empty( $search_str ) ) {
				global $wpdb;
				$sql_query .= $wpdb->prepare( " AND ( `title` LIKE %s OR `desc` LIKE %s )", "%" . $search_str . "%", "%" . $search_str . "%" );
			}
			if ( ! empty( $args['meta'] ) ) {
				if ( $args['meta']['compare'] === 'NOT_EXISTS' ) {

					$sql_query .= ' AND ({table_name_meta}.bwf_funnel_id IS NULL) ';
					if ( false === $is_total_query_required ) {
						$sql_query .= ' GROUP BY {table_name}.id';

					}
				} else {
					$sql_query .= ' AND ( {table_name_meta}.meta_key = \'' . $args['meta']['key'] . '\' AND {table_name_meta}.meta_value = \'' . $args['meta']['value'] . '\' )';
				}
			}
			$sql_query .= " ORDER BY {table_name}.id DESC";


			if ( false === $is_total_query_required ) {
				$sql_query .= ' LIMIT ' . $args['offset'] . ', ' . $limit;
			} else {
				$found_funnels = WFFN_Core()->get_dB()->get_results( 'SELECT count({table_name}.id) as count ' . $sql_query );
				$sql_query     .= ' LIMIT ' . 0 . ', ' . $limit;
			}
			$funnel_ids = WFFN_Core()->get_dB()->get_results( 'SELECT {table_name}.id as funnel_id ' . $sql_query );
			$items      = array();

			if ( isset( $args['search_filter'] ) ) {
				foreach ( $funnel_ids as $funnel_id ) {
					$funnel  = new WFFN_Funnel( $funnel_id['funnel_id'] );
					$item    = array(
						'id'   => $funnel->get_id(),
						'name' => $funnel->get_title(),
					);
					$items[] = $item;
				}

				return $items;

			} else {
				foreach ( $funnel_ids as $funnel_id ) {
					$funnel = new WFFN_Funnel( $funnel_id['funnel_id'] );
					$steps  = $funnel->get_steps();
					$view   = ( is_array( $steps ) && count( $steps ) > 0 ) ? get_permalink( $steps[0]['id'] ) : "";
					if ( false !== $need_draft_count || isset( $args['need_steps_data'] ) ) {
						$this->parse_funnels_step_ids( $funnel );
					}
					$item = array(
						'id'          => $funnel->get_id(),
						'title'       => $funnel->get_title(),
						'desc'        => $funnel->get_desc(),
						'date_added'  => $funnel->get_date_added(),
						'last_update' => $funnel->get_last_update_date(),
						'steps'       => ( is_array( $this->step_count_against_fid ) && count( $this->step_count_against_fid ) > 0 ) ? count( array_keys( $this->step_count_against_fid, absint( $funnel->get_id() ), true ) ) : 0,
						//phpcs:ignore WordPress.PHP.StrictInArray.MissingTrueStrict
						'view_link'   => $view,

					);
					if ( isset( $args['need_steps_data'] ) ) {
						$item['steps_data'] = $steps;
					}
					if ( ! isset ( $args['context'] ) || 'listing' !== $args['context'] ) {
						$item['__funnel'] = $funnel;
					}
					$items[] = $item;
				}
				if ( true === $is_total_query_required ) {
					$found_posts = array( 'found_posts' => (int) $found_funnels[0]['count'] );

				} else {
					$found_posts = array();

				}
				if ( false !== $need_draft_count ) {
					$draft_counts = $this->get_draft_steps();
					$items        = array_map( function ( $item ) use ( $draft_counts ) {
						$item['draft_count'] = $draft_counts[ $item['id'] ] ?? 0;

						return $item;
					}, $items );
				}
				$found_posts['items'] = $items;


				return apply_filters( 'wffn_funnels_lists', $found_posts );
			}
		}

		/**
		 * @param WFFN_Funnel $funnel
		 *
		 * @return void
		 */
		private function parse_funnels_step_ids( $funnel ) {
			$steps     = $funnel->get_steps();
			$funnel_id = $funnel->get_id();
			foreach ( $steps as $step ) {


				$step_id                                            = $step['id'];
				$this->step_against_fid[ $step_id ]                 = $funnel_id;
				$this->step_count_against_fid[ absint( $step_id ) ] = absint( $funnel_id );

				/**
				 * Handle case of upsells separately
				 */
				if ( $step['type'] === 'wc_upsells' && WFFN_Core()->steps->get_integration_object( 'wc_upsells' ) instanceof WFFN_Step ) {
					$funnel_offers = WFOCU_Core()->funnels->get_funnel_steps( $step['id'] );
					if ( ! empty( $funnel_offers ) && count( $funnel_offers ) > 1 ) {
						$offer_ids = wp_list_pluck( $funnel_offers, 'id' );

						$count = 0;
						foreach ( $offer_ids as $offer_id ) {
							$this->step_against_fid[ absint( $offer_id ) ] = $funnel_id;
							/**
							 * skip first offer in step listing
							 */
							if ( 0 !== $count ) {
								$this->step_count_against_fid[ absint( $offer_id ) ] = absint( $funnel_id );
							}
							$count ++;
						}
					}
				}
			}
		}

		private function get_draft_steps() {
			if ( empty( $this->step_against_fid ) ) {
				return [];
			}

			$step_ids = array_keys( $this->step_against_fid );

			global $wpdb;
			$results     = $wpdb->get_results( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE 1=1 AND post_status != %s and id IN (" . implode( ',', $step_ids ) . ")", 'publish' ), ARRAY_A ); //phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$draft_count = [];
			foreach ( $results as $result ) {
				$step_id   = $result['ID'];
				$funnel_id = $this->step_against_fid[ $step_id ];
				if ( ! isset( $draft_count[ $funnel_id ] ) ) {
					$draft_count[ $funnel_id ] = 1;
				} else {
					$draft_count[ $funnel_id ] ++;
				}
			}

			return $draft_count;
		}

		public function posts_per_page() {
			return 20;
		}


		public function hide_from_menu() {
			global $submenu;
			foreach ( $submenu as $key => $men ) {
				if ( 'woofunnels' !== $key ) {
					continue;
				}
				foreach ( $men as $k => $d ) {
					if ( 'woofunnels-settings' === $d[2] ) {
						unset( $submenu[ $key ][ $k ] );
					}
				}
			}
		}


		/**
		 * Adding landing pages in homepage display settings
		 *
		 * @param $pages
		 * @param $args
		 *
		 * @return array
		 */
		public function add_landing_in_home_pages( $pages, $args ) {
			if ( is_array( $args ) && isset( $args['name'] ) && 'page_on_front' !== $args['name'] && '_customize-dropdown-pages-page_on_front' !== $args['name'] ) {
				return $pages;
			}

			if ( is_array( $args ) && isset( $args['name'] ) && ( 'page_on_front' === $args['name'] || '_customize-dropdown-pages-page_on_front' === $args['name'] ) ) {
				$landing_pages = get_posts( array( //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_posts
					'post_type'   => WFFN_Core()->landing_pages->get_post_type_slug(),
					'numberposts' => 100,
					'post_status' => 'publish'
				) );


				$optin_pages = get_posts( array( //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.get_posts_get_posts
					'post_type'   => WFOPP_Core()->optin_pages->get_post_type_slug(),
					'numberposts' => 100,
					'post_status' => 'publish'
				) );


				$pages = array_merge( $pages, $landing_pages, $optin_pages );
			}

			return $pages;
		}


		public function admin_footer_text( $footer_text ) {
			if ( false === WFFN_Core()->role->user_access( 'funnel', 'read' ) ) {
				return $footer_text;
			}

			$current_screen = get_current_screen();
			$wffn_pages     = array( 'woofunnels_page_bwf', 'woofunnels_page_wffn-settings' );

			// Check to make sure we're on a WooFunnels admin page.
			if ( isset( $current_screen->id ) && apply_filters( 'bwf_funnels_funnels_display_admin_footer_text', in_array( $current_screen->id, $wffn_pages, true ), $current_screen->id ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
				// Change the footer text.
				$footer_text = __( 'Over 648+ 5 star reviews show that FunnelKit users trust our top-rated support for their online business. Do you need help? <a href="https://funnelkit.com/support/?utm_source=WordPress&utm_medium=Support+Footer&utm_campaign=Lite+Plugin" target="_blank"><b>Contact FunnelKit Support</b></a>', 'funnel-builder' );

			}

			return $footer_text;
		}

		public function maybe_show_notices() {


			global $wffn_notices;
			if ( ! is_array( $wffn_notices ) || empty( $wffn_notices ) ) {
				return;
			}

			foreach ( $wffn_notices as $notice ) {
				echo wp_kses_post( $notice );
			}
		}

		public function remove_all() {
			if ( $this->is_wffn_flex_page( 'all' ) ) {

				remove_all_actions( 'admin_notices' );
				remove_all_actions( 'all_admin_notices' );
			}
		}

		/**
		 * Hooked over 'plugin_action_links_{PLUGIN_BASENAME}' WordPress hook to add deactivate popup support & add PRO link
		 *
		 * @param array $links array of existing links
		 *
		 * @return array modified array
		 */
		public function plugin_actions( $links ) {
			if ( isset( $links['deactivate'] ) ) {
				$links['deactivate'] .= '<i class="woofunnels-slug" data-slug="' . WFFN_PLUGIN_BASENAME . '"></i>';
			}
			if ( ! defined( 'WFFN_PRO_VERSION' ) ) {
				$link  = add_query_arg( [
					'utm_source'   => 'WordPress',
					'utm_medium'   => 'All+Plugins',
					'utm_campaign' => 'Lite+Plugin',
					'utm_content'  => WFFN_VERSION
				], $this->get_pro_link() );
				$links = array_merge( [
					'pro_upgrade' => '<a href="' . $link . '" target="_blank" style="color: #1da867 !important;font-weight:600">' . __( 'Upgrade to Pro', 'funnel-builder' ) . '</a>'
				], $links );
			}


			return $links;
		}

		/**
		 * Initiate WFFN_Background_Importer class if ActionScheduler class doesn't exist
		 * @see woofunnels_maybe_update_customer_database()
		 */
		public function wffn_maybe_init_background_updater() {
			if ( class_exists( 'WFFN_Background_Importer' ) ) {
				$this->wffn_updater = new WFFN_Background_Importer();
			}


		}

		/**
		 * @hooked over `admin_init`
		 * This method takes care of template importing
		 * Checks whether there is a need to import
		 * Iterates over define callbacks and passes it to background updater class
		 * Updates templates for all steps of the funnels
		 */
		public function wffn_maybe_run_templates_importer() {
			if ( is_null( $this->wffn_updater ) ) {
				return;
			}
			$funnel_id = get_option( '_wffn_scheduled_funnel_id', 0 );

			if ( $funnel_id > 0 ) { // WPCS: input var ok, CSRF ok.

				$task = 'wffn_maybe_import_funnel_in_background';  //Scanning order table and updating customer tables
				$this->wffn_updater->push_to_queue( $task );
				BWF_Logger::get_instance()->log( '**************START Importing************', 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
				$this->wffn_updater->save()->dispatch();
				BWF_Logger::get_instance()->log( 'First Dispatch completed', 'wffn_template_import' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_print_r
			}
		}

		/**
		 * Delete wffn-wizard and redirect install
		 */
		public function reset_wizard() {
			if ( current_user_can( 'manage_options' ) && isset( $_GET['wffn_show_wizard_force'] ) && 'yes' === $_GET['wffn_show_wizard_force'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended

				delete_option( '_wffn_onboarding_completed' );
				delete_user_meta( get_current_user_id(), '_bwf_notifications_close' );
				wp_redirect( $this->wizard_url() );
				exit;

			}
		}

		/**
		 * @return array
		 */
		public function get_all_active_page_builders() {
			$page_builders = [ 'gutenberg', 'elementor', 'divi', 'oxy' ];

			return $page_builders;
		}

		/**
		 * Keep the menu open when editing the flows.
		 * Highlights the wanted admin (sub-) menu items for the CPT.
		 *
		 * @since 1.0.0
		 */
		public function menu_highlight() {
			global $submenu_file;
			$get_ref = filter_input( INPUT_GET, 'funnel_id' );
			if ( ! empty( $get_ref ) && absint( $get_ref ) === WFFN_Common::get_store_checkout_id() ) {
				$submenu_file = 'admin.php?page=bwf&path=/store-checkout'; //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			} else if ( $get_ref ) {
				$submenu_file = 'admin.php?page=bwf&path=/funnels'; //phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
			}
		}

		/**
		 * @param $query WP_Query
		 */
		public function load_page_to_home_page( $query ) {
			if ( $query->is_main_query() && ! ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ) {

				$post_type = $query->get( 'post_type' );

				$page_id = $query->get( 'page_id' );

				if ( empty( $post_type ) && ! empty( $page_id ) ) {
					$t_post = get_post( $page_id );
					if ( in_array( $t_post->post_type, [ WFFN_Core()->landing_pages->get_post_type_slug(), WFOPP_Core()->optin_pages->get_post_type_slug() ], true ) ) {
						$query->set( 'post_type', get_post_type( $page_id ) );
					}
				}
			}
		}

		public function check_db_version() {

			$get_db_version = get_option( '_wffn_db_version', '0.0.0' );

			if ( version_compare( WFFN_DB_VERSION, $get_db_version, '>' ) ) {


				include_once plugin_dir_path( WFFN_PLUGIN_FILE ) . 'admin/db/class-wffn-db-tables.php';
				$tables = WFFN_DB_Tables::get_instance();
				$tables->define_tables();
				$tables->add_if_needed();

			}

		}

		/**
		 * @hooked over `admin_init`
		 * This method takes care of database updating process.
		 * Checks whether there is a need to update the database
		 * Iterates over define callbacks and passes it to background updater class
		 */
		public function maybe_update_database_update() {


			$task_list          = array(
				'3.3.1' => array( 'wffn_handle_store_checkout_config' ),
				'3.3.3' => array( 'wffn_alter_conversion_table' ),
				'3.3.4' => array( 'wffn_add_utm_columns_in_conversion_table' ),
			);
			$current_db_version = get_option( '_wffn_db_version', '0.0.0' );


			/**
			 * 1. Fresh customer with no DB data. -
			 * - no task should run
			 * - direct update
			 * 2. Existing customer with db version less than current with task.
			 * - remaining tasks should run
			 * - update
			 * 3. Existing customer with db version less than current but no task.
			 * - no tasks should run
			 * - update
			 * 4. db version is update with current version.
			 * - return
			 * 5. db version is more than the current version.
			 * - return
			 */

			/**
			 * if the current db version is greater than or equal to the current version then no need to update the database
			 * case 4 and 5
			 */
			if ( version_compare( $current_db_version, WFFN_DB_VERSION, '>=' ) ) {
				return;
			}

			/**
			 * if the current db version is 0.0.0
			 * case 1
			 */
			if ( $current_db_version === '0.0.0' ) {
				update_option( '_wffn_db_version', WFFN_DB_VERSION, true );

				return;
			}


			if ( ! empty( $task_list ) ) {
				foreach ( $task_list as $version => $tasks ) {
					if ( version_compare( $current_db_version, $version, '<' ) ) {
						foreach ( $tasks as $update_callback ) {

							call_user_func( $update_callback );
							update_option( '_wffn_db_version', $version, true );
							$current_db_version = $version;
						}
					}
				}

				/**
				 * If we do not have any task for the specific DB version then directly update option
				 */
				if ( version_compare( $current_db_version, WFFN_DB_VERSION, '<' ) ) {
					update_option( '_wffn_db_version', WFFN_DB_VERSION, true );
				}

			}

		}

		public function settings_config( $config ) {
			$License    = WooFunnels_licenses::get_instance();
			$fields     = [];
			$has_fb_pro = false;
			if ( is_object( $License ) && is_array( $License->plugins_list ) && count( $License->plugins_list ) ) {
				foreach ( $License->plugins_list as $license ) {
					/**
					 * Excluding data for automation and connector addon
					 */
					if ( in_array( $license['product_file_path'], array( '7b31c172ac2ca8d6f19d16c4bcd56d31026b1bd8', '913d39864d876b7c6a17126d895d15322e4fd2e8' ), true ) ) {
						continue;
					}

					$license_data = [];
					if ( isset( $license['_data'] ) && isset( $license['_data']['data_extra'] ) ) {
						$license_data = $license['_data']['data_extra'];
						if ( isset( $license_data['api_key'] ) ) {
							$license_data['api_key'] = 'xxxxxxxxxxxxxxxxxxxxxxxxxx' . substr( $license_data['api_key'], - 6 );
							$license_data['licence'] = 'xxxxxxxxxxxxxxxxxxxxxxxxxx' . substr( $license_data['api_key'], - 6 );
						}
					}

					$data = array(
						'id'                      => $license['product_file_path'],
						'label'                   => $license['plugin'],
						'type'                    => 'license',
						'key'                     => $license['product_file_path'],
						'license'                 => ! empty( $license_data ) ? $license_data : false,
						'is_manually_deactivated' => ( isset( $license['_data']['manually_deactivated'] ) && true === wffn_string_to_bool( $license['_data']['manually_deactivated'] ) ) ? 1 : 0,
						'activated'               => ( isset( $license['_data']['activated'] ) && true === wffn_string_to_bool( $license['_data']['activated'] ) ) ? 1 : 0,
						'expired'                 => ( isset( $license['_data']['expired'] ) && true === wffn_string_to_bool( $license['_data']['expired'] ) ) ? 1 : 0
					);
					if ( $license['plugin'] === 'FunnelKit Funnel Builder Pro' || $license['plugin'] === 'FunnelKit Funnel Builder Basic' ) {
						$has_fb_pro = true;
						array_unshift( $fields, $data );
					} else {
						$fields[] = $data;
					}
				}
			}

			if ( empty( $has_fb_pro ) ) {
				$field_no_license = array(
					'type'         => 'label',
					'key'          => 'label_no_license',
					'label'        => __( 'FunnelKit Funnel Builder Pro', 'woofunnels' ),
					'styleClasses' => [ 'wfacp_setting_track_and_events_start', 'bwf_wrap_custom_html_tracking_general' ],
				);
				array_unshift( $fields, $field_no_license );
				$field_no_license = array(
					'key'          => 'no_license',
					'type'         => 'upgrade_pro',
					'label'        => __( 'You are currently using FunnelKit Lite version, which does not require a license. To access more features, consider upgrading to FunnelKit PRO now. Already got license? Download the premium version from your account and activate license keys. Login to FunnelKit Account', 'funnel-builder' ),
					'styleClasses' => [ 'wfacp_checkbox_wrap', 'wfacp_setting_track_and_events_end' ],
					'hint'         => '',
				);
				array_unshift( $fields, $field_no_license );

			}

			return array_merge( $fields, $config );
		}

		public function maybe_add_oxygen_in_global_settings( $config ) {
			$get_index = false;
			foreach ( $config as &$v ) {
				if ( $v['key'] === 'default_selected_builder' ) {
					$get_all_builders = wp_list_pluck( $v['values'], 'id' );
					if ( in_array( 'oxy', $get_all_builders, true ) ) {
						break;
					}
					foreach ( $v['values'] as $index => $vv ) {
						if ( $vv['id'] === 'divi' ) {
							$get_index = $index;
							break;
						}
					}
					if ( false !== $get_index ) {

						array_splice( $v['values'], $get_index + 1, 0, [ [ 'id' => 'gutenberg', 'name' => __( 'Block Editor', 'woofunnels' ) ] ] );

						array_splice( $v['values'], $get_index + 2, 0, [ [ 'id' => 'oxy', 'name' => __( 'Oxygen', 'woofunnels' ) ] ] );
					}


				}
			}


			return $config;
		}

		/**
		 * @param $link
		 * @param BWFABT_Experiment $experiment
		 *
		 * @return string
		 */
		function maybe_modify_link( $link, $experiment ) {


			$get_control_id = $experiment->get_control();

			$get_funnel_id = get_post_meta( $get_control_id, '_bwf_in_funnel', true );

			if ( ! empty( $get_funnel_id ) ) {

				return WFFN_Common::get_experiment_edit_link( $get_funnel_id, $get_control_id );
			}

			return $link;
		}

		/*
		 * @param $post_id
		 * @param $all_meta
		 *
		 * Return selected builder based on post meta when import page
		 * @return string[]
		 */
		public function get_selected_template( $post_id, $all_meta ) {
			$meta = '';
			if ( ! empty( $all_meta ) ) {
				$meta = wp_list_pluck( $all_meta, 'meta_key' );
			}

			$template = [
				'selected'        => 'wp_editor_1',
				'selected_type'   => 'wp_editor',
				'template_active' => 'yes'
			];


			$selected_template = apply_filters( 'wffn_set_selected_template_on_duplicate', array(), $post_id, $meta );

			if ( is_array( $selected_template ) && count( $selected_template ) > 0 ) {
				return $selected_template;
			}

			if ( is_array( $meta ) ) {
				if ( in_array( '_elementor_data', $meta, true ) ) {
					$template['selected']      = 'elementor_1';
					$template['selected_type'] = 'elementor';

					return $template;
				}
				if ( in_array( '_et_builder_version', $meta, true ) ) {
					$template['selected']      = 'divi_1';
					$template['selected_type'] = 'divi';

					return $template;
				}
				if ( in_array( 'ct_builder_shortcodes', $meta, true ) ) {
					$template['selected']      = 'oxy_1';
					$template['selected_type'] = 'oxy';

					return $template;
				}
			}

			if ( false !== strpos( get_post_field( 'post_content', $post_id ), '<!-- wp:' ) ) {
				$template['selected']      = 'gutenberg_1';
				$template['selected_type'] = 'gutenberg';

				return $template;
			}

			return $template;
		}

		public function get_pro_link() {
			return esc_url( 'https://funnelkit.com/funnel-builder-lite-upgrade/' );
		}

		public function setup_js_for_localization( $app_name, $frontend_dir, $script_deps, $version ) {
			/** enqueue other js file from the dist folder */
			$path = WFFN_PLUGIN_DIR . $this->get_local_app_path();
			foreach ( glob( $path . "*.js" ) as $dist_file ) {
				$file_info = pathinfo( $dist_file );

				if ( $app_name === $file_info['filename'] ) {
					continue;
				}
				wp_register_script( "wffn_admin_" . $file_info['filename'], $frontend_dir . "" . $file_info['basename'], $script_deps, $version, true );
				wp_set_script_translations( "wffn_admin_" . $file_info['filename'], 'funnel-builder' );
			}
			add_action( 'admin_print_footer_scripts', function () {

				if ( 0 === WFFN_REACT_ENVIRONMENT ) {
					return;
				}
				$path = WFFN_PLUGIN_DIR . $this->get_local_app_path();
				global $wp_scripts;
				foreach ( glob( $path . "*.js" ) as $dist_file ) {

					$file_info = pathinfo( $dist_file );

					$translations = $wp_scripts->print_translations( "wffn_admin_" . $file_info['filename'], false );
					if ( $translations ) {
						$translations = sprintf( "<script%s id='%s-js-translations'>\n%s\n</script>\n", '', esc_attr( "wffn_admin_" . $file_info['filename'] ), $translations );
					}
					echo $translations; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				}

			}, 99999 );
		}

		/**
		 * @param $post_id
		 * @param $post
		 *
		 * hooked over `before_delete_post`
		 * Checks if funnel step delete, then update associated funnel step meta
		 *
		 * @return void
		 */
		public function delete_funnel_step_permanently( $post_id, $post ) {

			if ( is_null( $post ) ) {
				return;
			}

			if ( ! in_array( $post->post_type, array(
				'wfacp_checkout',
				'wffn_landing',
				'wffn_ty',
				'wffn_optin',
				'wffn_oty',
			), true ) ) {
				return;
			}

			$get_funnel_id = get_post_meta( $post_id, '_bwf_in_funnel', true );

			if ( empty( $get_funnel_id ) ) {
				return;
			}

			$funnel = new WFFN_Funnel( $get_funnel_id );

			if ( $funnel instanceof WFFN_Funnel ) {
				$funnel->delete_step( $get_funnel_id, $post_id );
			}

		}

		/**
		 * @param $steps
		 * @param $funnel
		 *
		 * Removed step if not exists on funnel steps listing
		 *
		 * @return mixed
		 */
		public function maybe_delete_funnel_step( $steps, $funnel ) {

			if ( ! $funnel instanceof WFFN_Funnel ) {
				return $steps;
			}
			if ( is_array( $steps ) && count( $steps ) > 0 ) {
				foreach ( $steps as $key => &$step ) {

					/**
					 * Skip if store funnel have native checkout
					 */
					if ( absint( $funnel->get_id() ) === WFFN_Common::get_store_checkout_id() && WFFN_Common::store_native_checkout_slug() === $step['type'] ) {
						continue;
					}

					/**
					 * IF current step post not exist, then remove this step from funnel meta
					 */
					if ( 0 <= $step['id'] && ! get_post( $step['id'] ) instanceof WP_Post ) {
						unset( $steps[ $key ] );
						$funnel->delete_step( $funnel->get_id(), $step['id'] );
					}
				}

			}

			return $steps;

		}

		/**
		 * @param WP_Admin_Bar $wp_admin_bar
		 * Add funnel and step direct edit link in top wp admin bar
		 *
		 * @return void
		 */
		public function add_menu_in_admin_bar( \WP_Admin_Bar $wp_admin_bar ) {

			global $post;

			if ( is_null( $post ) ) {
				return;
			}

			if ( ! class_exists( 'BWF_Admin_Breadcrumbs' ) ) {
				return;
			}

			$wffn_steps = array(
				'wffn_landing'   => array( 'slug' => 'funnel-landing', 'title' => __( 'Edit Sales', 'funnel-builder' ) ),
				'wfacp_checkout' => array( 'slug' => 'funnel-checkout', 'title' => __( 'Edit Checkout', 'funnel-builder' ) ),
				'wfocu_offer'    => array( 'slug' => 'funnel-offer', 'title' => __( 'Edit Offer', 'funnel-builder' ) ),
				'wffn_ty'        => array( 'slug' => 'funnel-thankyou', 'title' => __( 'Edit Thank You', 'funnel-builder' ) ),
				'wffn_optin'     => array( 'slug' => 'funnel-optin', 'title' => __( 'Edit Optin', 'funnel-builder' ) ),
				'wffn_oty'       => array( 'slug' => 'funnel-optin-confirmation', 'title' => __( 'Edit Optin Confirmation', 'funnel-builder' ) )
			);

			if ( empty( $post->post_type ) || ! isset( $wffn_steps[ $post->post_type ] ) ) {
				return;
			}

			$step = $wffn_steps[ $post->post_type ];

			if ( 'wfocu_offer' === $post->post_type ) {
				$upsell_id = get_post_meta( $post->ID, '_funnel_id', true );
				$funnel_id = get_post_meta( $upsell_id, '_bwf_in_funnel', true );
			} else {
				$funnel_id = get_post_meta( $post->ID, '_bwf_in_funnel', true );
			}

			if ( empty( $funnel_id ) || abs( $funnel_id ) === 0 ) {
				return;
			}

			$funnel_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
				'page' => 'bwf',
				'path' => "/funnels/" . $funnel_id . "/steps",
			], admin_url( 'admin.php' ) ) );

			$step_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
				'page'      => 'bwf',
				'path'      => "/" . $step['slug'] . "/" . $post->ID . "/design",
				'funnel_id' => $funnel_id,
			], admin_url( 'admin.php' ) ) );

			$wp_admin_bar->add_node( [
				'id'    => 'wffn_funnel',
				'title' => 'FunnelKit'
			] );

			$wp_admin_bar->add_menu( [
				'id'     => 'wffn_edit_funnel',
				'parent' => 'wffn_funnel',
				'title'  => __( 'Edit Funnel', 'funnel-builder' ),
				'href'   => $funnel_link,
			] );

			$wp_admin_bar->add_menu( [
				'id'     => 'wffn_edit_step',
				'parent' => 'wffn_funnel',
				'title'  => $step['title'],
				'href'   => $step_link,
			] );

		}

		/**
		 * @param $title
		 * set default post title if post tile empty
		 *
		 * @return mixed|string|null
		 */
		public function maybe_empty_title( $title ) {
			if ( empty( $title ) ) {
				return __( '(no title)', 'funnel-builder' );
			}

			return $title;
		}


		public function wizard_url() {
			return admin_url( 'admin.php?page=bwf&path=/user-setup' );
		}

		/**
		 * Update Last Updated on Meta Data
		 *
		 * @param $meta_id
		 * @param $object_id
		 *
		 * @return void
		 */
		public function update_last_edit_time( $meta_id, $object_id ) {
			$bwf_id = get_post_meta( $object_id, '_bwf_in_funnel', true );
			if ( ! empty( $bwf_id ) ) {
				$this->update_last_update_time( $bwf_id );
				remove_action( 'updated_postmeta', [ $this, 'updated_post_meta' ], 10, 2 );
			}
		}

		public function update_last_update_time( $bwf_id ) {
			WFFN_Core()->get_dB()->update_meta( $bwf_id, '_last_updated_on', current_time( 'mysql' ) );
		}


		/**
		 * Filter CB to alter response data to pass connect link to the REST cll
		 *
		 * @param array $response
		 * @param string $basename
		 *
		 * @return mixed
		 */
		public function maybe_add_auth_link_stripe( $response, $basename ) {
			if ( 'funnelkit-stripe-woo-payment-gateway/funnelkit-stripe-woo-payment-gateway.php' === $basename ) {
				$response['next_action'] = 'funnelkit-app/stripe-connect-link';
			}

			return $response;
		}


		public function license_fb_pro_data() {

			$License = WooFunnels_licenses::get_instance();

			$data = [];
			if ( is_object( $License ) && is_array( $License->plugins_list ) && count( $License->plugins_list ) ) {
				foreach ( $License->plugins_list as $license ) {
					/**
					 * Excluding data for automation and connector addon
					 */
					if ( in_array( $license['product_file_path'], array( '7b31c172ac2ca8d6f19d16c4bcd56d31026b1bd8', '913d39864d876b7c6a17126d895d15322e4fd2e8' ), true ) ) {
						continue;
					}

					$license_data = [];
					if ( isset( $license['_data'] ) && isset( $license['_data']['data_extra'] ) ) {
						$license_data = $license['_data']['data_extra'];
						if ( isset( $license_data['api_key'] ) ) {
							$license_data['api_key'] = 'xxxxxxxxxxxxxxxxxxxxxxxxxx' . substr( $license_data['api_key'], - 6 );
							$license_data['licence'] = 'xxxxxxxxxxxxxxxxxxxxxxxxxx' . substr( $license_data['api_key'], - 6 );
						}
					}
					if ( $license['plugin'] === 'FunnelKit Funnel Builder Pro' || $license['plugin'] === 'FunnelKit Funnel Builder Basic' ) {
						$data = array(
							'id'                      => $license['product_file_path'],
							'label'                   => $license['plugin'],
							'type'                    => 'license',
							'key'                     => $license['product_file_path'],
							'license'                 => ! empty( $license_data ) ? $license_data : false,
							'is_manually_deactivated' => ( isset( $license['_data']['manually_deactivated'] ) && true === bwf_string_to_bool( $license['_data']['manually_deactivated'] ) ) ? 1 : 0,
							'activated'               => ( isset( $license['_data']['activated'] ) && true === bwf_string_to_bool( $license['_data']['activated'] ) ) ? 1 : 0,
							'expired'                 => ( isset( $license['_data']['expired'] ) && true === bwf_string_to_bool( $license['_data']['expired'] ) ) ? 1 : 0
						);


					}
				}
			}

			return $data;
		}

		public function get_license_expiry() {

			$licenses = $this->license_fb_pro_data();

			if ( empty( $licenses ) || empty( $licenses['license'] ) ) {
				return '';
			}

			$expiry = $licenses['license']['expires'];
			if ( '' === $expiry ) {
				return gmdate( 'Y-m-d H:i:s', strtotime( '+1 year' ) );
			}


			return $expiry;

		}


		public function license_data( $hash ) {

			$License = WooFunnels_licenses::get_instance();
			if ( is_object( $License ) && is_array( $License->plugins_list ) && count( $License->plugins_list ) ) {
				foreach ( $License->plugins_list as $license ) {
					if ( $license['product_file_path'] !== $hash ) {
						continue;
					}
					if ( isset( $license['_data'] ) && isset( $license['_data']['data_extra'] ) ) {
						$license_data = $license['_data']['data_extra'];

						return array(
							'id'                      => $license['product_file_path'],
							'label'                   => $license['plugin'],
							'type'                    => 'license',
							'key'                     => $license['product_file_path'],
							'license'                 => ! empty( $license_data ) ? $license_data : false,
							'is_manually_deactivated' => ( isset( $license['_data']['manually_deactivated'] ) && true === bwf_string_to_bool( $license['_data']['manually_deactivated'] ) ) ? 1 : 0,
							'activated'               => ( isset( $license['_data']['activated'] ) && true === bwf_string_to_bool( $license['_data']['activated'] ) ) ? 1 : 0,
							'expired'                 => ( isset( $license['_data']['expired'] ) && true === bwf_string_to_bool( $license['_data']['expired'] ) ) ? 1 : 0
						);
					}


				}


			}

			return [];

		}

		public function is_license_active_for_checkout() {
			$hashes = $this->get_license_hashes();


			if ( $this->is_basic_exists() ) {
				$license_basic = $this->license_data( $hashes['basic'] );

				if ( empty( $license_basic ) ) {
					return false;
				} elseif ( isset( $license_basic['is_manually_deactivated'] ) && 1 === $license_basic['is_manually_deactivated'] ) {
					return 'deactiavted';
				} elseif ( isset( $license_basic['expired'] ) && 1 === $license_basic['expired'] ) {
					return 'expired';
				} elseif ( isset( $license_key['activated'] ) && 0 === $license_key['activated'] ) {
					return 'not-active';
				}

				return true;
			}

			if ( defined( 'WFFN_PRO_VERSION' ) & ! $this->is_basic_exists() ) {
				$license_pro = $this->license_data( $hashes['pro'] );
				if ( empty( $license_pro ) ) {
					return false;
				} elseif ( isset( $license_pro['is_manually_deactivated'] ) && 1 === $license_pro['is_manually_deactivated'] ) {
					return 'deactiavted';
				} elseif ( isset( $license_pro['expired'] ) && 1 === $license_pro['expired'] ) {
					return 'expired';
				} elseif ( isset( $license_key['activated'] ) && 0 === $license_key['activated'] ) {
					return 'not-active';
				}

				return true;
			}

			if ( class_exists( 'WFACP_Core' ) ) {
				$license_checkout = $this->license_data( $hashes['checkout'] );

				if ( empty( $license_checkout ) ) {
					return false;
				} elseif ( isset( $license_checkout['is_manually_deactivated'] ) && 1 === $license_checkout['is_manually_deactivated'] ) {
					return 'deactiavted';
				} elseif ( isset( $license_checkout['expired'] ) && 1 === $license_checkout['expired'] ) {
					return 'expired';
				}
                elseif ( isset( $license_key['activated'] ) && 0 === $license_key['activated'] ) {
					return 'not-active';
				}

				return true;
			}


			return false;
		}

		public function get_license_expiry_for_checkout() {
			$hashes = $this->get_license_hashes();


			if ( $this->is_basic_exists() ) {
				$licenses = $this->license_data( $hashes['basic'] );
				if ( empty( $licenses ) || empty( $licenses['license'] ) ) {
					return '';
				}

				if ( '' === $licenses['license']['expires'] ) {
					return gmdate( 'Y-m-d H:i:s', strtotime( '+1 year' ) );
				}

				return $licenses['license']['expires'];
			}

			if ( defined( 'WFFN_PRO_VERSION' ) & ! $this->is_basic_exists() ) {
				$licenses = $this->license_data( $hashes['pro'] );
				if ( empty( $licenses ) || empty( $licenses['license'] ) ) {
					return '';
				}

				if ( '' === $licenses['license']['expires'] ) {
					return gmdate( 'Y-m-d H:i:s', strtotime( '+1 year' ) );
				}

				return $licenses['license']['expires'];
			}

			if ( class_exists( 'WFACP_Core' ) ) {
				$licenses = $this->license_data( $hashes['checkout'] );
				if ( empty( $licenses ) || empty( $licenses['license'] ) ) {
					return '';
				}

				if ( '' === $licenses['license']['expires'] ) {
					return gmdate( 'Y-m-d H:i:s', strtotime( '+1 year' ) );
				}

				return $licenses['license']['expires'];
			}


			return false;
		}

		public function is_license_active_for_upsell() {
			$hashes = $this->get_license_hashes();

			if ( defined( 'WFFN_PRO_VERSION' ) & ! $this->is_basic_exists() ) {
				$license_pro = $this->license_data( $hashes['pro'] );

				if ( empty( $license_pro ) ) {
					return false;
				} elseif ( isset( $license_pro['is_manually_deactivated'] ) && 1 === $license_pro['is_manually_deactivated'] ) {
					return 'deactiavted';
				} elseif ( isset( $license_pro['expired'] ) && 1 === $license_pro['expired'] ) {
					return 'expired';
				} elseif ( isset( $license_key['activated'] ) && 0 === $license_key['activated'] ) {
					return 'not-active';
				}

				return true;
			}

			if ( class_exists( 'WFOCU_Core' ) ) {
				$license_upsells = $this->license_data( $hashes['upsell'] );

				if ( empty( $license_upsells ) ) {
					return false;
				} elseif ( isset( $license_upsells['is_manually_deactivated'] ) && 1 === $license_upsells['is_manually_deactivated'] ) {
					return 'deactiavted';
				} elseif ( isset( $license_upsells['expired'] ) && 1 === $license_upsells['expired'] ) {
					return 'expired';
				}
                elseif ( isset( $license_key['activated'] ) && 0 === $license_key['activated'] ) {
					return 'not-active';
				}

				return true;
			}


			return false;
		}

		public function get_license_expiry_for_upsell() {
			$hashes = $this->get_license_hashes();
			if ( defined( 'WFFN_PRO_VERSION' ) & ! $this->is_basic_exists() ) {
				$licenses = $this->license_data( $hashes['pro'] );
				if ( empty( $licenses ) || empty( $licenses['license'] ) ) {
					return '';
				}

				if ( '' === $licenses['license']['expires'] ) {
					return gmdate( 'Y-m-d H:i:s', strtotime( '+1 year' ) );
				}

				return $licenses['license']['expires'];
			}

			if ( class_exists( 'WFOCU_Core' ) ) {
				$licenses = $this->license_data( $hashes['upsell'] );
				if ( empty( $licenses ) || empty( $licenses['license'] ) ) {
					return '';
				}

				if ( '' === $licenses['license']['expires'] ) {
					return gmdate( 'Y-m-d H:i:s', strtotime( '+1 year' ) );
				}

				return $licenses['license']['expires'];
			}


			return '';
		}

		public function get_license_hashes() {
			return array(
				'checkout' => '742fc61c1b455e2b1efa4154a92da8fb7f9866d3',
				'upsell'   => 'e837ebc716ca979006da34eecdce9f650ced6bef',
				'pro'      => 'ffec4bb68f0841db41213ce12305aaef7e0237f3',
				'basic'    => 'e234ca9ec3e4856bb05ea9f8ec90e7f3831b05c5',

			);
		}


		public function blocks_incompatible_switch_to_classic_cart_checkout( $is_rest = false ) {

			if ( ! class_exists( '\Automattic\WooCommerce\Blocks\BlockTypes\ClassicShortcode' )  // Make sure WC version is atleast 8.3. This class is added at version 8.3.
			) {
				return;
			}


			if ( empty( $is_rest ) && false === check_ajax_referer( 'wffn_blocks_incompatible_switch_to_classic', 'nonce', false ) ) {
				return;
			}
			$wc_cart_page     = get_post( wc_get_page_id( 'cart' ) );
			$wc_checkout_page = get_post( wc_get_page_id( 'checkout' ) );

			if ( has_block( 'woocommerce/checkout', $wc_checkout_page ) ) {
				wp_update_post( array(
					'ID'           => $wc_checkout_page->ID,
					'post_content' => '<!-- wp:woocommerce/classic-shortcode {"shortcode":"checkout"} /-->',
				) );
			}

			if ( has_block( 'woocommerce/cart', $wc_cart_page ) ) {
				wp_update_post( array(
					'ID'           => $wc_cart_page->ID,
					'post_content' => '<!-- wp:woocommerce/classic-shortcode {"shortcode":"cart"} /-->',
				) );
			}

			$userdata   = get_user_meta( get_current_user_id(), '_bwf_notifications_close', true );
			$userdata   = empty( $userdata ) && ! is_array( $userdata ) ? [] : $userdata;
			$userdata[] = 'wc_block_incompat';
			update_user_meta( get_current_user_id(), '_bwf_notifications_close', array_values( array_unique( $userdata ) ) ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_update_user_meta

			if ( ! empty( $is_rest ) ) {
				return rest_ensure_response( array( 'success' => true ) );

			}
			$redirect     = isset( $_REQUEST['redirect'] ) ? esc_url_raw( wp_unslash( $_REQUEST['redirect'] ) ) : null;
			$redirect_url = $redirect && strpos( $redirect, '.php' ) ? admin_url( $redirect ) : null;

			wp_safe_redirect( $redirect_url ?? admin_url( 'admin.php?page=bwf&path=/funnels' ) );
			exit;
		}

		/**
		 * AJAX dismiss admin notice.
		 *
		 * @since 1.1
		 * @since 4.5.1 Add nonce verification when dismissing notices.
		 * @access public
		 */
		public function ajax_dismiss_admin_notice() {
			$notice_key = isset( $_REQUEST['nkey'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['nkey'] ) ) : '';

			if ( defined( 'DOING_AJAX' ) && DOING_AJAX && current_user_can( 'manage_options' ) && $notice_key && isset( $_REQUEST['nonce'] ) && false !== check_ajax_referer( 'wp_wffn_dismiss_notice', 'nonce', false )

			) {

				$userdata   = get_user_meta( get_current_user_id(), '_bwf_notifications_close', true );
				$userdata   = empty( $userdata ) && ! is_array( $userdata ) ? [] : $userdata;
				$userdata[] = $notice_key;

				update_user_meta( get_current_user_id(), '_bwf_notifications_close', array_values( array_unique( $userdata ) ) ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.user_meta_update_user_meta

			}
			$redirect = isset( $_REQUEST['redirect'] ) ? esc_url_raw( wp_unslash( $_REQUEST['redirect'] ) ) : null;

			$redirect_url = $redirect && strpos( $redirect, '.php' ) ? admin_url( $redirect ) : null;

			wp_safe_redirect( $redirect_url ?? admin_url( 'admin.php?page=bwf&path=/funnels' ) );
			exit;

		}

		public function get_pro_activation_date() {
			if ( ! defined( 'WFFN_PRO_VERSION' ) ) {
				return '';
			}
			$pro_activation_date = get_option( 'fk_fb_active_date', [] );
			if ( empty( $pro_activation_date ) || ! isset( $pro_activation_date['pro'] ) ) {
				$date = new DateTime( 'now' );
				$date->modify( '-10 days' );
				$pro_activation_date['pro'] = $date->getTimestamp();
				update_option( 'fk_fb_active_date', $pro_activation_date, false );

				return $date->format( 'Y-m-d H:i:s' );
			}

			return gmdate( 'Y-m-d H:i:s', $pro_activation_date['pro'] );
		}

		public function get_lite_activation_date() {

			$pro_activation_date = get_option( 'fk_fb_active_date', [] );
			if ( empty( $pro_activation_date ) || ! isset( $pro_activation_date['lite'] ) ) {
				$date                        = new DateTime( 'now' );
				$pro_activation_date['lite'] = $date->getTimestamp();
				update_option( 'fk_fb_active_date', $pro_activation_date, false );

				return $date->format( 'Y-m-d H:i:s' );
			}

			return gmdate( 'Y-m-d H:i:s', $pro_activation_date['lite'] );
		}


		public function get_license_config() {
			return [
				'f'  => array(
					'e'   => defined( 'WFFN_PRO_VERSION' ),
					'la'  => $this->is_license_active(),  //false on not exist
					//true when activated
					//false when manually deactivated
					// on expiry it could be both true and false, not recommended checking this value
					'ad'  => $this->get_pro_activation_date(),
					'ed'  => $this->get_license_expiry(),
					'ib'  => $this->is_basic_exists(),
					'adl' => $this->get_lite_activation_date(),
				),
				'ck' => array(
					'e'  => wfacp_pro_dependency(), //should cover aero, basic and pro addon
					'la' => $this->is_license_active_for_checkout(),
					'ad' => $this->get_pro_activation_date(),
					'ed' => $this->get_license_expiry_for_checkout()

				),
				'ul' => array(
					'e'  => function_exists( 'WFOCU_Core' ), //should cover upstroke & pro addon
					'la' => $this->is_license_active_for_upsell(),
					'ad' => $this->get_pro_activation_date(),
					'ed' => $this->get_license_expiry_for_upsell()
				),
				'gp' => [ 7, 3 ]
			];
		}

		/**
		 * redirect checkout edit link on react screen when click on edit link from wc order screen
		 * @return void
		 */
		public function redirect_checkout_edit_link_on_new_ui() {
			$funnel_id = get_post_meta( $_GET['wfacp_id'], '_bwf_in_funnel', true );
			if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {

				$edit_link = add_query_arg( [
					'page' => 'bwf',
					'path' => "/funnel-checkout/" . $_GET['wfacp_id'] . "/design",
				], admin_url( 'admin.php' ) );
				wp_redirect( $edit_link );
				exit;
			}
		}

		public function maybe_force_redirect_to_wizard() {


			if ( ! $this->is_wffn_flex_page() ) {
				return;
			}
			if ( isset( $_GET['path'] ) && '/user-setup' === $_GET['path'] ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				return;
			}

			$first_version = get_option( 'wffn_first_v', '0.0.0' );
			if ( false === version_compare( $first_version, WFFN_VERSION, '=' ) ) {

				/**
				 * bail out if old users versions
				 */
				return;
			}
			$status = get_option( '_wffn_onboarding_completed', false );
			if ( false !== $status ) {
				/**
				 * bail out if wizard started/skipped/completed
				 */
				return;
			}


			if ( WFFN_Core()->admin_notifications->is_user_dismissed( get_current_user_id(), 'wizard_open' ) ) {
				/**
				 * This flag tells us that the wizard first step is already opened
				 * We are using this to make sure we never redirect multiple times.
				 */
				return;
			}
			wp_redirect( WFFN_Core()->admin->wizard_url() );
			exit;


		}


	}

	if ( class_exists( 'WFFN_Core' ) ) {
		WFFN_Core::register( 'admin', 'WFFN_Admin' );
	}
}




