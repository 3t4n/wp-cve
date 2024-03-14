<?php
/**
 * Class to control Settings and its behaviour across the buildwoofunnels
 * @author buildwoofunnels
 */

if ( ! class_exists( 'BWF_Admin_General_Settings' ) ) {

	#[AllowDynamicProperties]
	class BWF_Admin_General_Settings {

		private static $ins = null;
		private $options = array();

		public function __construct() {

			add_filter( 'woofunnels_global_settings', function ( $menu ) {
				array_push( $menu, array(
					'title'    => __( 'General', 'woofunnels' ),
					'slug'     => 'woofunnels_general_settings',
					'link'     => apply_filters( 'bwf_general_settings_link', 'javascript:void(0)' ),
					'priority' => 5,
				) );

				return $menu;
			} );
			add_action( 'wp_ajax_bwf_general_settings_update', [ $this, 'update_general_settings' ] );
			add_action( 'init', array( $this, 'maybe_flush_rewrite_rules' ), 101 );

			add_action( 'admin_head', array( $this, 'hide_from_menu' ) );
			add_filter( 'admin_title', array( $this, 'maybe_change_title' ), 99 );
			add_filter( 'woofunnels_global_settings_fields', array( $this, 'add_settings_fields_array' ), 99 );
			add_action( 'bwf_global_save_settings_woofunnels_general_settings', array( $this, 'update_global_settings_fields' ), 99 );

		}

		public static function get_instance() {

			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function maybe_flush_rewrite_rules() {
			$is_required_rewrite = get_option( 'bwf_needs_rewrite', 'no' );
			if ( 'yes' === $is_required_rewrite ) {
				flush_rewrite_rules(); // phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.flush_rewrite_rules_flush_rewrite_rules
				WooFunnels_Dashboard::get_all_templates();
				update_option( 'bwf_needs_rewrite', 'no', true );
			}
		}

		public function add_settings_fields_array( $fields ) {
			$fields['woofunnels_general_settings'] = $this->all_fields();

			return $fields;
		}

		public function __callback() {
			/** Registering Settings in top bar */
			if ( class_exists( 'BWF_Admin_Breadcrumbs' ) ) {
				BWF_Admin_Breadcrumbs::register_node( [ 'text' => 'Settings' ] );
			}
			BWF_Admin_Breadcrumbs::render_sticky_bar();
			?>
            <div class="wrap bwf-funnel-common">
                <h1 class="wp-heading-inline"><?php esc_html_e( 'Settings', 'woofunnels' ); ?></h1>
				<?php
				$admin_settings = BWF_Admin_Settings::get_instance();
				$admin_settings->render_tab_html( 'woofunnels_general_settings' );
				$i = 0;
				?>
                <div id="bwf_general_settings_vue_wrap" class="bwf-hide" v-bind:class="`1`===is_initialized?'bwf-show':''">
                    <div class="bwf-vue-custom-msg" v-if="'' != errorMsg"><p v-html="errorMsg"></p></div>
                    <div class="bwf-tabs-view-vertical bwf-widget-tabs">
                        <div class="bwf-tabs-wrapper">
                            <div class="bwf-tab-title" data-tab="<?php $i ++;
							echo $i; ?>" role="tab">
								<?php esc_html_e( 'Permalinks', 'woofunnels' ); ?>
                            </div>
                            <div class="bwf-tab-title" data-tab="<?php $i ++;
							echo $i; ?>" role="tab">
								<?php esc_html_e( 'Facebook Pixel', 'woofunnels' ); ?>
                            </div>
                            <div class="bwf-tab-title" data-tab="<?php $i ++;
							echo $i; ?>" role="tab">
								<?php esc_html_e( 'Google Analytics', 'woofunnels' ); ?>
                            </div>


							<?php if ( apply_filters( 'bwf_enable_ecommerce_integration_gad', false ) ) { ?>
                                <div class="bwf-tab-title" data-tab="<?php $i ++;
								echo $i; ?>" role="tab">
									<?php esc_html_e( 'Google Ads', 'woofunnels' ); ?>
                                </div>
							<?php }
							if ( apply_filters( 'bwf_enable_ecommerce_integration_pinterest', false ) ) { ?>
                                <div class="bwf-tab-title" data-tab="<?php $i ++;
								echo $i; ?>" role="tab">
									<?php esc_html_e( 'Pinterest', 'woofunnels' ); ?>
                                </div>
							<?php }

							if ( apply_filters( 'bwf_enable_ecommerce_integration_tiktok', false ) ) { ?>
                                <div class="bwf-tab-title" data-tab="<?php $i ++;
								echo $i; ?>" role="tab">
									<?php esc_html_e( 'TikTok', 'woofunnels' ); ?>
                                </div>
							<?php }
							if ( apply_filters( 'bwf_enable_ecommerce_integration_snapchat', false ) ) { ?>
                                <div class="bwf-tab-title" data-tab="<?php $i ++;
								echo $i; ?>" role="tab">
									<?php esc_html_e( 'Snapchat', 'woofunnels' ); ?>
                                </div>
							<?php } ?>

                        </div>


                        <div class="bwf-tabs-content-wrapper">
                            <div class="bwf_setting_inner">
                                <form class="bwf_forms_wrap">
                                    <fieldset>
                                        <vue-form-generator :schema="schema" :model="model" :options="formOptions"></vue-form-generator>
                                    </fieldset>
                                    <div style="display: none" id="modal-general-settings_success" data-iziModal-icon="icon-home">
                                    </div>
                                </form>
                                <div class="bwf_form_button">
                                    <span class="bwf_loader_global_save spinner" style="float: left;"></span>
                                    <button v-on:click.self="onSubmit" class="bwf_save_btn_style"><?php esc_html_e( 'Save Changes', 'woofunnels' ); ?></button>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

			<?php
		}

		public function default_general_settings() {
			return apply_filters( 'bwf_general_settings_default_config', array(
				'tiktok_pixel'                      => '',
				'is_tiktok_add_to_cart_bump'        => '',
				'tiktok_add_to_cart_event'          => '',
				'tiktok_initiate_checkout_event'    => '',
				'is_tiktok_purchase_event'          => array(),
				'is_tiktok_complete_payment_event'  => array(),
				'pint_key'                          => '',
				'is_pint_lead_op'                   => array(),
				'is_pint_add_to_cart_bump'          => '',
				'is_pint_custom_bump'               => '',
				'is_pint_page_view_lp'              => array(),
				'is_pint_page_view_op'              => array(),
				'is_pint_pageview_event'            => array(),
				'is_pint_page_view_global'          => '',
				'pint_is_page_view'                 => '',
				'pint_add_to_cart_event'            => '',
				'pint_initiate_checkout_event'      => '',
				'is_pint_purchase_event'            => array(),
				'is_pint_custom_events'             => '',
				'pint_variable_as_simple'           => '',
				'pint_content_id_type'              => '0',
				'pint_content_id_prefix'            => '',
				'pint_content_id_suffix'            => '',
				'pint_exclude_from_total'           => array(),
				'gad_key'                           => '',
				'gad_conversion_label'              => '',
				'is_gad_page_view_global'           => '',
				'is_gad_view_item_global'           => '',
				'is_gad_page_view_lp'               => array(),
				'is_gad_page_view_op'               => array(),
				'is_gad_lead_op'                    => array(),
				'is_gad_add_to_cart_bump'           => '',
				'is_gad_custom_bump'                => '',
				'google_ads_is_page_view'           => '',
				'google_ads_add_to_cart_event'      => '',
				'is_gad_pageview_event'             => array(),
				'is_gad_purchase_event'             => array(),
				'is_gad_custom_events'              => '',
				'google_ads_variable_as_simple'     => '',
				'google_ads_content_id_type'        => '0',
				'google_ads_content_id_prefix'      => '',
				'google_ads_content_id_suffix'      => '',
				'gad_exclude_from_total'            => array(),
				'ga_key'                            => '',
				'is_ga4_tracking'                   => array(),
				'is_ga_page_view_global'            => '',
				'is_ga_view_item_global'            => '',
				'is_ga_page_view_lp'                => array(),
				'is_ga_page_view_op'                => array(),
				'is_ga_lead_op'                     => array(),
				'google_ua_is_page_view'            => '',
				'google_ua_add_to_cart_event'       => '',
				'google_ua_initiate_checkout_event' => '',
				'google_ua_add_payment_info_event'  => '',
				'is_ga_purchase_page_view'          => array(),
				'is_ga_purchase_event'              => array(),
				'is_ga_custom_events'               => '',
				'google_ua_variable_as_simple'      => '',
				'google_ua_content_id_type'         => '0',
				'google_ua_content_id_prefix'       => '',
				'google_ua_content_id_suffix'       => '',
				'ga_exclude_from_total'             => array(),
				'fb_pixel_key'                      => '',
				'is_fb_purchase_conversion_api'     => array(),
				'conversion_api_access_token'       => '',
				'is_fb_conv_enable_test'            => array(),
				'conversion_api_test_event_code'    => '',
				'is_fb_conversion_api_log'          => array(),
				'is_fb_page_view_global'            => '',
				'is_fb_page_product_content_global' => '',
				'is_fb_page_view_lp'                => array(),
				'is_fb_page_view_op'                => array(),
				'is_fb_lead_op'                     => array(),
				'is_fb_add_to_cart_bump'            => '',
				'is_fb_custom_bump'                 => '',
				'label_section_head_fb'             => '',
				'pixel_is_page_view'                => '',
				'pixel_initiate_checkout_event'     => '',
				'pixel_add_to_cart_event'           => '',
				'pixel_add_payment_info_event'      => '',
				'is_fb_purchase_page_view'          => array(),
				'is_fb_purchase_event'              => array(),
				'enable_general_event'              => array(),
				'general_event_name'                => 'GeneralEvent',
				'is_fb_custom_events'               => '',
				'is_fb_enable_content'              => [],
				'pixel_variable_as_simple'          => '',
				'pixel_content_id_type'             => '0',
				'pixel_content_id_prefix'           => '',
				'pixel_content_id_suffix'           => '',
				'exclude_from_total'                => array(),
				'is_fb_advanced_event'              => array(),
				'is_tiktok_advanced_event'          => array(),
				'default_selected_builder'          => 'elementor',
				'track_utms'                        => "1",
				'snapchat_pixel'                    => '',
				'is_snapchat_page_view_global'      => '',
				'is_snapchat_page_view_lp'          => array(),
				'is_snapchat_page_view_op'          => array(),
				'is_snapchat_add_to_cart_bump'      => '',
				'label_section_head_snapchat'       => '',
				'snapchat_is_page_view'             => '',
				'snapchat_add_to_cart_event'        => '',
				'snapchat_initiate_checkout_event'  => '',
				'snapchat_add_payment_info_event'   => '',
				'is_snapchat_purchase_event'        => array(),
				'is_fb_add_to_cart_global'          => '',
				'is_ga_add_to_cart_global'          => '',
				'is_gad_add_to_cart_global'         => '',
				'is_snapchat_add_to_cart_global'    => '',
				'is_tiktok_page_view_global'        => '',
				'is_tiktok_page_view_lp'            => array(),
				'is_tiktok_page_view_op'            => array(),
				'is_tiktok_pageview_event'          => array(),
				'tiktok_is_page_view'               => '',
				'is_tiktok_add_to_cart_global'      => '',
				'is_pint_add_to_cart_global'        => '',
				'is_pint_page_visit_global'         => '',
				'track_traffic_source'              => [], //for backcompat, not calling anywhere
				'ga_track_traffic_source'           => [], //for backcompat, not calling anywhere
				'is_ga_add_to_cart_bump'            => '',
				'is_ga_custom_bump'                 => '',
				'custom_aud_opt_conf'               => [],

			) );
		}

		public function get_option( $key = 'all' ) {

			if ( empty( $this->options ) ) {
				$this->setup_options();
			}
			if ( 'all' === $key ) {
				return $this->options;
			}

			return isset( $this->options[ $key ] ) ? $this->options[ $key ] : false;
		}

		public function setup_options() {
			$db_options = get_option( 'bwf_gen_config', [] );

			$db_options    = ( ! empty( $db_options ) && is_array( $db_options ) ) ? array_map( function ( $val ) {
				return is_scalar( $val ) ? html_entity_decode( $val ) : $val;
			}, $db_options ) : array();
			$this->options = wp_parse_args( $db_options, $this->default_general_settings() );

			return $this->options;
		}

		public function maybe_add_js() {
			wp_enqueue_script( 'bwf-general-settings', plugin_dir_url( WooFunnel_Loader::$ultimate_path ) . 'woofunnels/assets/js/bwf-general-settings.js', [], BWF_VERSION );
			wp_enqueue_style( 'bwf-general-settings', plugin_dir_url( WooFunnel_Loader::$ultimate_path ) . 'woofunnels/assets/css/bwf-general-settings.css', array(), BWF_VERSION );


			wp_localize_script( 'bwf-general-settings', 'bwfAdminGen', $this->get_localized_data() );
		}

		public function get_localized_data() {
			$localized_data = [
				'nonce_general_settings' => wp_create_nonce( 'bwf_general_settings_update' ),
				'texts'                  => array(
					'settings_success'    => __( 'Changes saved', 'woofunnels' ),
					'permalink_help_text' => __( 'Leave empty to remove slug completely from url', 'woofunnels' ),
				),
				'globalOptionsFields'    => array(
					'options'       => $this->filter_admin_options( $this->get_option() ),
					'legends_texts' => array(
						'fb'         => __( 'Facebook Pixel', 'woofunnels' ),
						'ga'         => __( 'Google Analytics', 'woofunnels' ),
						'gad'        => __( 'Google Ads', 'woofunnels' ),
						'pint'       => __( 'Pinterest', 'woofunnels' ),
						'permalinks' => __( 'Permalinks', 'woofunnels' ),
						'tiktok'     => __( 'Tiktok', 'woofunnels' ),
						'snapchat'   => __( 'Snapchat', 'woofunnels' ),
					),
					'fields'        => $this->all_fields()
				)
			];


			$localized_data['is_pinterest_enabled']         = ( true === apply_filters( 'bwf_enable_ecommerce_integration_pinterest', false ) ) ? 1 : 0;
			$localized_data['is_tiktok_enabled']            = ( true === apply_filters( 'bwf_enable_ecommerce_integration_tiktok', false ) ) ? 1 : 0;
			$localized_data['is_snapchat_enabled']          = ( true === apply_filters( 'bwf_enable_ecommerce_integration_snapchat', false ) ) ? 1 : 0;
			$localized_data['is_gad_enabled']               = ( true === apply_filters( 'bwf_enable_ecommerce_integration_gad', false ) ) ? 1 : 0;
			$localized_data['is_pixel_enabled']             = ( true === apply_filters( 'bwf_enable_ecommerce_integration_pixel', false ) ) ? 1 : 0;
			$localized_data['is_ga_enabled']                = ( true === apply_filters( 'bwf_enable_ecommerce_integration_ga', false ) ) ? 1 : 0;
			$localized_data['if_fb_checkout_enabled']       = ( true === apply_filters( 'bwf_enable_ecommerce_integration_fb_checkout', false ) ) ? 1 : 0;
			$localized_data['if_fb_purchase_enabled']       = ( true === apply_filters( 'bwf_enable_ecommerce_integration_fb_purchase', false ) ) ? 1 : 0;
			$localized_data['if_ga_checkout_enabled']       = ( true === apply_filters( 'bwf_enable_ecommerce_integration_ga_checkout', false ) ) ? 1 : 0;
			$localized_data['if_ga_purchase_enabled']       = ( true === apply_filters( 'bwf_enable_ecommerce_integration_ga_purchase', false ) ) ? 1 : 0;
			$localized_data['if_gad_checkout_enabled']      = ( true === apply_filters( 'bwf_enable_ecommerce_integration_gad_checkout', false ) ) ? 1 : 0;
			$localized_data['if_gad_purchase_enabled']      = ( true === apply_filters( 'bwf_enable_ecommerce_integration_gad_purchase', false ) ) ? 1 : 0;
			$localized_data['if_pint_checkout_enabled']     = ( true === apply_filters( 'bwf_enable_ecommerce_integration_pint_checkout', false ) ) ? 1 : 0;
			$localized_data['if_pint_purchase_enabled']     = ( true === apply_filters( 'bwf_enable_ecommerce_integration_pint_purchase', false ) ) ? 1 : 0;
			$localized_data['if_tiktok_checkout_enabled']   = ( true === apply_filters( 'bwf_enable_ecommerce_integration_tiktok_checkout', false ) ) ? 1 : 0;
			$localized_data['if_tiktok_purchase_enabled']   = ( true === apply_filters( 'bwf_enable_ecommerce_integration_tiktok_purchase', false ) ) ? 1 : 0;
			$localized_data['if_snapchat_checkout_enabled'] = ( true === apply_filters( 'bwf_enable_ecommerce_integration_snapchat_checkout', false ) ) ? 1 : 0;
			$localized_data['if_snapchat_purchase_enabled'] = ( true === apply_filters( 'bwf_enable_ecommerce_integration_snapchat_purchase', false ) ) ? 1 : 0;
			$localized_data['if_landing_enabled']           = ( true === apply_filters( 'bwf_enable_ecommerce_integration_landing', false ) ) ? 1 : 0;
			$localized_data['if_optin_enabled']             = ( true === apply_filters( 'bwf_enable_ecommerce_integration_optin', false ) ) ? 1 : 0;
			$localized_data['if_ga4_enabled']               = ( true === apply_filters( 'bwf_enable_ga4', false ) ) ? 1 : 0;
			$checkout_page_slug                             = 'checkout';
			$checkout_id                                    = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'checkout' ) : 0;
			if ( $checkout_id > - 1 ) {
				$checkout_page = get_post( $checkout_id );
				if ( $checkout_page instanceof WP_Post ) {
					$checkout_page_slug = $checkout_page->post_name;
				}
			}
			$localized_data['checkout_page_slug']  = $checkout_page_slug;
			$localized_data['permalink_structure'] = get_option( 'permalink_structure' );
			$localized_data['errors']              = array(
				'checkout_slug' => sprintf( __( 'Error: The permalink "%s" is reserved by Native WooCommerce Checkout Page. Try another permalink.', 'woofunnels' ), $checkout_page_slug ),
				'empty_base'    => sprintf( __( 'Error: The current Permalinks settings does not allow blank values. Switch Permalink settings to \'Post name\'. <a href="%s">Click Here To Change</a>', 'woofunnels' ), admin_url( 'options-permalink.php' ) ),
			);

			return $localized_data;
		}

		public function get_localized_bwf_data() {
			$localized_data = [];


			$checkout_page_slug = 'checkout';
			$checkout_id        = function_exists( 'wc_get_page_id' ) ? wc_get_page_id( 'checkout' ) : 0;
			if ( $checkout_id > - 1 ) {
				$checkout_page = get_post( $checkout_id );
				if ( $checkout_page instanceof WP_Post ) {
					$checkout_page_slug = $checkout_page->post_name;
				}
			}
			$localized_data['checkout_page_slug']  = $checkout_page_slug;
			$localized_data['permalink_structure'] = get_option( 'permalink_structure' );
			$localized_data['errors']              = array(
				'checkout_slug' => sprintf( __( 'Error: The permalink "%s" is reserved by Native WooCommerce Checkout Page. Try another permalink.', 'woofunnels' ), $checkout_page_slug ),
				'empty_base'    => sprintf( __( 'Error: The current Permalinks settings does not allow blank values. Switch Permalink settings to \'Post name\'. <a href="%s">Click Here To Change</a>', 'woofunnels' ), admin_url( 'options-permalink.php' ) ),
			);

			$localized_data['pro_status'] = [];

			$License = WooFunnels_licenses::get_instance();

			if ( is_object( $License ) && is_array( $License->plugins_list ) && count( $License->plugins_list ) ) {
				foreach ( $License->plugins_list as $license ) {
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

						$localized_data['pro_status'] = $data;
					}
				}
			}


			return $localized_data;
		}

		public function update_general_settings() {
			check_admin_referer( 'bwf_general_settings_update', '_nonce' );
			$options = isset( $_POST['data'] ) ? bwf_clean( $_POST['data'] ) : 0;
			$resp    = $this->update_global_settings_fields( $options );
			wp_send_json( $resp );
		}

		public function update_global_settings_fields( $options ) {
			$options = ( is_array( $options ) && wp_unslash( bwf_clean( $options ) ) ) ? bwf_clean( $options ) : 0;
			$resp    = [
				'status' => false,
				'msg'    => __( 'Settings Updated', 'woofunnels' ),
				'data'   => '',
			];

			$db_options = get_option( 'bwf_gen_config', [] );
			$options    = array_merge( $this->default_general_settings(), $db_options, $options );

			if ( $options !== 0 ) {
				update_option( 'bwf_gen_config', $options, true );
				update_option( 'bwf_needs_rewrite', 'yes', true );
				$resp['status'] = true;
			}

			return $resp;
		}

		public function get_settings_link() {
			return apply_filters( 'bwf_general_settings_link', 'javascript:void(0)' );
		}

		public function hide_from_menu() {
			global $woofunnels_menu_slug;

			global $parent_file, $plugin_page, $submenu_file; //phpcs:ignore
			if ( filter_input( INPUT_GET, 'tab', FILTER_UNSAFE_RAW ) === 'bwf_settings' ) :
				$parent_file  = $woofunnels_menu_slug;//phpcs:ignore
				$submenu_file = 'admin.php?page=woofunnels_settings'; //phpcs:ignore
			endif;
		}

		/**
		 * Filter options before passing it to the javascript
		 *
		 * @param $config array configuration array
		 *
		 * @return array
		 */
		public function filter_admin_options( $config ) {
			foreach ( $config as $key => &$data ) {

				/**
				 * Check if data is 'false' (string) then make it blank so that checkboxes works accordingly
				 */
				if ( 'false' === $data ) {
					$config[ $key ] = '';
				}
			}

			return $config;
		}

		public function maybe_change_title( $title ) {
			if ( 'bwf_settings' === filter_input( INPUT_GET, 'tab', FILTER_UNSAFE_RAW ) || 'bwf_settings' === filter_input( INPUT_GET, 'section', FILTER_UNSAFE_RAW ) ) {
				$admin_title = get_bloginfo( 'name' );
				$title       = sprintf( __( '%1$s &lsaquo; %2$s &#8212; WordPress' ), 'FunnelKit', $admin_title );
			}

			return $title;
		}

		public function all_fields() {

			$static_config  = include WooFunnel_Loader::$ultimate_path . '/helpers/settings.php';
			$legacy         = apply_filters( 'bwf_general_settings_fields', [] );
			$legacy_altered = [];
			if ( count( $legacy ) > 0 ) {
				$i = 0;
				foreach ( $legacy as $key => $new ) {
					$legacy_altered[ $i ] = array( 'key' => $key );
					$legacy_altered[ $i ] = array_merge( $legacy_altered[ $i ], $new );
					$i ++;
				}
			}
			$static_config['permalinks']['fields'] = $legacy_altered;

			foreach ( $static_config as &$arr ) {
				$values = [];
				foreach ( $arr['fields'] as &$field ) {

					$values[ $field['key'] ] = $this->get_option( $field['key'] );
				}
				$arr['values'] = $values;
			}

			return $static_config;

		}
	}


}
BWF_Admin_General_Settings::get_instance();
