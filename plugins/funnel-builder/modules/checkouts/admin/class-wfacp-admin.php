<?php
defined( 'ABSPATH' ) || exit;

#[AllowDynamicProperties]
final class WFACP_admin {

	private static $ins = null;
	public $wfacp_id = 0;
	public $current_page = 'design';
	public $current_section;
	public $default_checkout_status = false;
	protected $localize_data = [];
	protected $checkout_post_list = [];
	protected $have_variable = false;
	private $address_fields = [
		'billing'  => [],
		'shipping' => [],
	];
	private $wfacp_custom_fields = [];

	protected function __construct() {
		$this->current_section = __DIR__ . '/views/sections/design.php';
		$this->wfacp_id        = WFACP_Common::get_id();
		add_action( 'admin_init', [ $this, 'show_post_not_exist' ], 1 );
		add_action( 'admin_head', array( $this, 'hide_from_menu' ) );
		add_action( 'admin_menu', [ $this, 'register_admin_menu' ], 90 );
		add_action( 'admin_menu', [ $this, 'remove_page_attributes' ], 90 );

		add_filter( 'plugin_action_links_' . WFACP_PLUGIN_BASENAME, array( $this, 'plugin_actions' ) );
		add_filter( 'woofunnels_uninstall_reasons', array( $this, 'plugin_uninstall_reasons' ), 20 );
		add_action( 'admin_head', [ $this, 'open_admin_bar' ], 90 );


		/**
		 * Admin enqueue scripts
		 */
		add_action( 'admin_enqueue_scripts', [ $this, 'admin_enqueue_assets' ], 99 );
		add_action( 'admin_enqueue_scripts', array( $this, 'maybe_register_breadcrumbs' ), 10 );
		/**
		 * Admin customizer enqueue scripts
		 */
		add_action( 'customize_controls_print_styles', [ $this, 'admin_customizer_enqueue_assets' ], 10 );

		add_filter( 'woocommerce_billing_fields', [ $this, 'add_css_ready_classes' ] );
		add_filter( 'woocommerce_shipping_fields', [ $this, 'add_css_ready_classes' ] );
		add_action( 'admin_menu', [ $this, 'set_section' ] );

		add_action( 'woocommerce_admin_order_data_after_order_details', [ $this, 'show_advanced_field_order' ] );


		add_action( 'in_admin_header', [ $this, 'maybe_remove_all_notices_on_page' ] );

		add_action( 'in_admin_header', [ $this, 'restrict_notices_display' ] );

		add_filter( 'wfacp_builder_merge_field_arguments', [ $this, 'wfacp_builder_merge_field_arguments' ], 10, 4 );

		add_action( 'admin_print_styles', [ $this, 'remove_theme_css_and_scripts' ], 100 );

		$post_type = WFACP_Common::get_post_type_slug();
		add_action( 'add_meta_boxes_' . $post_type, [ $this, 'add_meta_boxes_for_shortcodes' ], 10, 2 );
		add_filter( 'wfacp_checkout_post_list', [ $this, 'append_checkout_post_list' ] );


		add_filter( 'wfacp_address_fields_billing', [ $this, 'arrange_billing_fields' ], 9 );
		add_filter( 'wfacp_address_fields_shipping', [ $this, 'arrange_shipping_fields' ], 9 );
		add_action( 'edit_form_after_title', [ $this, 'add_back_button' ] );
		add_filter( 'set-screen-option', [ $this, 'save_screen_option' ], 100, 3 );

		add_action( 'admin_menu', [ $this, 'get_advanced_field' ], 95 );
		add_filter( 'is_protected_meta', [ $this, 'wfacp_protected_meta' ], 10, 3 );
		add_filter( "get_pages", [ $this, 'add_pages_to_front_page_options' ], 15, 2 );
		add_filter( 'bwf_enable_ecommerce_integration_fb_checkout', '__return_true' );
		add_filter( 'bwf_enable_ecommerce_integration_ga_checkout', '__return_true' );
		add_filter( 'bwf_enable_ga4', '__return_true' );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 1 );

		/*** bwf general setting ***/
		add_filter( 'bwf_general_settings_link', function () {
			return admin_url( 'admin.php?page=wfacp&section=bwf_settings' );
		} );


		add_action( 'admin_footer', function () {
			?>
            <script>
                if (typeof window.bwfBuilderCommons !== "undefined") {
                    window.bwfBuilderCommons.addFilter('bwf_common_permalinks_fields', function (e) {
                        e.push(
                            {
                                type: "input",
                                inputType: "text",
                                label: "",
                                model: "checkout_page_base",
                                inputName: 'checkout_page_base',
                            });
                        return e;
                    });
                }

            </script>
			<?php
		} );


	}

	public static function get_instance() {
		if ( is_null( self::$ins ) ) {
			self::$ins = new self;
		}

		return self::$ins;
	}

	/**
	 * Check if its our builder page and registered required nodes to prepare a breadcrumb
	 */
	public function maybe_register_breadcrumbs() {
		if ( WFACP_Common::is_load_admin_assets( 'builder' ) ) {
			/**
			 * Only register primary node if not added yet
			 */
			if ( empty( BWF_Admin_Breadcrumbs::$nodes ) ) {
				BWF_Admin_Breadcrumbs::register_node( array(
					'text' => __( 'Checkouts', 'woofunnels-aero-checkout' ),
					'link' => admin_url( 'admin.php?page=wfacp' )
				) );
			}
			BWF_Admin_Breadcrumbs::register_node( array(
				'text'  => ! empty( WFACP_Common::get_page_name() ) ? WFACP_Common::get_page_name() : __( '(no title)', 'woofunnels-aero-checkout' ),
				'link'  => '',
				'class' => 'wfacp_page_title'
			) );
		}

	}

	public function set_section() {
		if ( WFACP_Common::get_id() > 0 && isset( $_GET['section'] ) ) {

			$this->current_page = filter_input( INPUT_GET, 'section', FILTER_UNSAFE_RAW );
			if ( file_exists( __DIR__ . '/views/sections/' . $this->current_page . '.php' ) ) {
				$this->current_section = __DIR__ . '/views/sections/' . $this->current_page . '.php';
			}
			$this->current_section = apply_filters( 'wfacp_builder_pages_path', $this->current_section, $this->current_page, $this );

		}

	}

	public function register_admin_menu() {
		$user = WFACP_Core()->role->user_access( 'menu', 'read' );
		if ( $user ) {
			add_submenu_page( 'woofunnels', 'Checkouts', 'Checkouts', $user, 'wfacp', [
				$this,
				'admin_page',
			] );
		}
	}

	public function hide_from_menu() {
		global $submenu, $woofunnels_menu_slug;
		foreach ( $submenu as $key => $men ) {
			if ( $woofunnels_menu_slug !== $key ) {
				continue;
			}
			foreach ( $men as $k => $d ) {
				if ( 'admin.php?page=wfacp' === $d[2] ) {

					unset( $submenu[ $key ][ $k ] );
				}
			}
		}


	}


	public function admin_enqueue_assets() {
		wp_enqueue_style( 'wfacp-admin-font', $this->get_admin_url() . '/assets/css/wfacp-admin-font.css', array(), WFACP_VERSION_DEV );
		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'wfacp' ) {

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );

			wp_enqueue_script( 'jquery' );
			wp_enqueue_editor();
			wp_enqueue_style( 'wfacp-izimodal', $this->get_admin_url() . '/includes/iziModal/iziModal.css', array(), WFACP_VERSION_DEV );
			wp_enqueue_style( 'wfacp-vue-multiselect', $this->get_admin_url() . '/includes/vuejs/vue-multiselect.min.css', array(), WFACP_VERSION_DEV );
			wp_enqueue_style( 'wfacp-vfg', $this->get_admin_url() . '/includes/vuejs/vfg.min.css', array(), WFACP_VERSION_DEV );

			wp_enqueue_style( 'wfacp-admin-app', $this->get_admin_url() . '/assets/css/wfacp-admin-app.css', array(), WFACP_VERSION_DEV );
			wp_enqueue_style( 'wfacp-sweetalert2', $this->get_admin_url() . '/assets/css/sweetalert2.css', array(), WFACP_VERSION_DEV );
			wp_enqueue_style( 'wfacp-admin-main', $this->get_admin_url() . '/assets/css/wfacp-admin.css', array(), WFACP_VERSION_DEV );

			wp_enqueue_script( 'wfacp-izimodal', $this->get_admin_url() . '/includes/iziModal/iziModal.js', array(), WFACP_VERSION_DEV );
			wp_enqueue_script( 'wfacp-vuejs', $this->get_admin_url() . '/includes/vuejs/vue.min.js', array(), '2.6.10' );
			wp_enqueue_script( 'wfacp-vue-vfg', $this->get_admin_url() . '/includes/vuejs/vfg.min.js', array(), '2.3.4' );
			wp_enqueue_script( 'wfacp-vue-multiselected', $this->get_admin_url() . '/includes/vuejs/vue-multiselect.min.js', array(), '2.1.0' );
			wp_enqueue_script( 'wfacp-sweetalert2', $this->get_admin_url() . '/assets/js/wfacp-sweetalert.min.js', array(), WFACP_VERSION_DEV );

			if ( function_exists( 'blocksy_get_jed_locale_data' ) ) {
				wp_dequeue_style( 'ct-options-styles' );
			}
			if ( $this->wfacp_id > 0 ) {
				wp_enqueue_script( 'jquery-ui' );
				wp_enqueue_script( 'jquery-ui-sortable' );
			} else {
				wp_enqueue_style( 'woocommerce_admin_styles' );
				wp_enqueue_script( 'wc-backbone-modal' );
			}

			wp_dequeue_script( 'jquery-ui-accordion' );

			/***Add general setting scripts***/
			if ( filter_input( INPUT_GET, 'section', FILTER_UNSAFE_RAW ) === 'bwf_settings' ) {
				BWF_Admin_General_Settings::get_instance()->maybe_add_js();
			}

			wp_enqueue_script( 'wfacp', $this->get_admin_url() . '/assets/js/wfacp_combined.min.js', array(
				'jquery',
				'underscore',
				'backbone',
				'updates'
			), WFACP_VERSION_DEV );
			do_action( 'wfacp_admin_js_enqueued' );
			$this->localize_data();
		}
	}

	public function get_admin_url() {
		return plugin_dir_url( WFACP_PLUGIN_FILE ) . 'admin';
	}

	private function localize_data() {
		wp_localize_script( 'wfacp', 'wfacp_data', $this->get_localize_data() );
		wp_localize_script( 'wfacp', 'wfacp_localization', WFACP_Common::get_builder_localization() );
		wp_localize_script( 'wfacp', 'wfacp_design_settings', $this->design_settings() );
		wp_localize_script( 'wfacp', 'wfacp_secure', [
			'nonce' => wp_create_nonce( 'wfacp_admin_secure_key' ),
		] );

	}

	public function design_settings() {
		$models = WFACP_Common::get_option( '', true );


		$typography_fonts     = bwf_get_fonts_list();
		$default_models       = array(
			'wfacp_form_section_embed_forms_2_step_form_max_width'                          => '450',
			'wfacp_form_section_embed_forms_2_form_border_width'                            => '1',
			'wfacp_form_section_embed_forms_2_disable_steps_bar'                            => 'true',
			'wfacp_form_section_embed_forms_2_form_border_type'                             => 'solid',
			'wfacp_form_section_embed_forms_2_form_border_color'                            => '#bbbbbb',
			'wfacp_form_section_embed_forms_2_form_inner_padding'                           => '15',
			'wfacp_form_section_embed_forms_2_name_0'                                       => __( 'GET YOUR FREE COPY OF AMAZING BOOK', 'woofunnels-aero-checkout' ),
			'wfacp_form_section_embed_forms_2_headline_0'                                   => __( 'Shipped in less than 3 days!', 'woofunnels-aero-checkout' ),
			'wfacp_form_section_embed_forms_2_step_heading_font_size'                       => 19,
			'wfacp_form_section_embed_forms_2_heading_fs'                                   => 18,
			'wfacp_form_section_embed_forms_2_heading_font_weight'                          => 'wfacp-bold',
			'wfacp_form_section_embed_forms_2_heading_talign'                               => 'wfacp-text-left',
			'wfacp_form_section_embed_forms_2_sec_heading_color'                            => '#424141',
			'wfacp_form_section_embed_forms_2_sec_bg_color'                                 => 'transparent',
			'wfacp_form_section_embed_forms_2_rbox_border_type'                             => 'none',
			'wfacp_form_section_embed_forms_2_rbox_border_width'                            => '1',
			'wfacp_form_section_embed_forms_2_rbox_padding'                                 => '0',
			'wfacp_form_section_embed_forms_2_rbox_margin'                                  => '10',
			'wfacp_form_section_embed_forms_2_sub_heading_fs'                               => 13,
			'wfacp_form_section_embed_forms_2_sub_heading_font_weight'                      => 'wfacp-normal',
			'wfacp_form_section_embed_forms_2_sub_heading_talign'                           => 'wfacp-text-left',
			'wfacp_form_section_embed_forms_2_sec_sub_heading_color'                        => '#666666',
			'wfacp_form_section_embed_forms_2_field_style_fs'                               => 13,
			'wfacp_form_section_embed_forms_2_step_sub_heading_font_size'                   => 15,
			'wfacp_form_section_embed_forms_2_step_alignment'                               => 'center',
			'wfacp_form_section_ct_active_inactive_tab'                                     => 'active',
			'wfacp_form_section_embed_forms_2_active_step_bg_color'                         => '#4c4c4c',
			'wfacp_form_section_embed_forms_2_active_step_text_color'                       => '#ffffff',
			'wfacp_form_section_embed_forms_2_active_step_tab_border_color'                 => '#f58e2d',
			'wfacp_form_section_embed_forms_2_field_border_layout'                          => 'solid',
			'wfacp_form_section_embed_forms_2_field_border_width'                           => '1',
			'wfacp_form_section_embed_forms_2_field_style_color'                            => '#888888',
			'wfacp_form_section_embed_forms_2_field_border_color'                           => '#c3c0c0',
			'wfacp_form_section_embed_forms_2_field_focus_color'                            => '#61bdf7',
			'wfacp_form_section_embed_forms_2_field_input_color'                            => '#404040',
			'wfacp_form_section_payment_methods_heading'                                    => __( 'Payment Methods', 'woofunnels-aero-checkout' ),
			'wfacp_form_section_payment_methods_sub_heading'                                => '',
			'wfacp_form_section_embed_forms_2_btn_order-place_btn_text'                     => __( 'PLACE ORDER NOW', 'woofunnels-aero-checkout' ),
			'wfacp_form_section_embed_forms_2_btn_order-place_fs'                           => 25,
			'wfacp_form_section_embed_forms_2_btn_order-place_top_bottom_padding'           => '14',
			'wfacp_form_section_embed_forms_2_btn_order-place_left_right_padding'           => '22',
			'wfacp_form_section_embed_forms_2_btn_order-place_border_radius'                => '10',
			'wfacp_form_section_embed_forms_2_btn_order-place_btn_font_weight'              => 'bold',
			'wfacp_form_section_embed_forms_2_btn_order-place_width'                        => '100%',
			'wfacp_form_section_embed_forms_2_btn_order-place_make_button_sticky_on_mobile' => 'no_sticky',
			'wfacp_form_section_embed_forms_2_color_type'                                   => 'hover',
			'wfacp_form_section_embed_forms_2_btn_order-place_bg_color'                     => '#f58e2d',
			'wfacp_form_section_embed_forms_2_btn_order-place_text_color'                   => '#ffffff',
			'wfacp_form_section_embed_forms_2_additional_text_color'                        => '#000000',
			'wfacp_form_section_embed_forms_2_additional_bg_color'                          => '#f8f8f8',
			'wfacp_form_section_embed_forms_2_validation_color'                             => '#ff0000',
			'wfacp_form_section_embed_forms_2_btn_order-place_bg_hover_color'               => '#d46a06',
			'wfacp_form_section_embed_forms_2_btn_order-place_text_hover_color'             => '#ffffff',
			'wfacp_form_section_text_below_placeorder_btn'                                  => __( '100% Secure & Safe Payments', 'woofunnels-aero-checkout' ),
			'wfacp_form_section_embed_forms_2_form_content_color'                           => '#737373',
			'wfacp_form_section_embed_forms_2_form_content_link_color'                      => '#dd7575',
			'wfacp_form_section_embed_forms_2_section_bg_color'                             => '#ffffff',
			'wfacp_form_section_embed_forms_2_form_content_link_color_type'                 => 'normal',
			'wfacp_form_section_embed_forms_2_form_content_link_hover_color'                => '#965d5d',
			'wfacp_form_form_fields_1_embed_forms_2_billing_email'                          => 'wfacp-col-full',
			'wfacp_form_form_fields_1_embed_forms_2_billing_email_other_classes'            => '',
			'wfacp_form_form_fields_1_embed_forms_2_billing_first_name'                     => 'wfacp-col-left-half',
			'wfacp_form_form_fields_1_embed_forms_2_billing_first_name_other_classes'       => '',
			'wfacp_form_form_fields_1_embed_forms_2_billing_last_name'                      => 'wfacp-col-left-half',
			'wfacp_form_form_fields_1_embed_forms_2_billing_last_name_other_classes'        => '',
			'wfacp_form_form_fields_1_embed_forms_2_billing_phone'                          => 'wfacp-col-full',
			'wfacp_form_form_fields_1_embed_forms_2_billing_phone_other_classes'            => '',
			'wfacp_form_form_fields_1_embed_forms_2_shipping_same_as_billing'               => '',
			'wfacp_form_form_fields_1_embed_forms_2_shipping_same_as_billing_other_classes' => '',
			'wfacp_form_form_fields_1_embed_forms_2_shipping_address_1'                     => 'wfacp-col-full',
			'wfacp_form_form_fields_1_embed_forms_2_shipping_address_1_other_classes'       => '',
			'wfacp_form_form_fields_1_embed_forms_2_shipping_city'                          => 'wfacp-col-left-half',
			'wfacp_form_form_fields_1_embed_forms_2_shipping_city_other_classes'            => '',
			'wfacp_form_form_fields_1_embed_forms_2_shipping_postcode'                      => 'wfacp-col-left-half',
			'wfacp_form_form_fields_1_embed_forms_2_shipping_postcode_other_classes'        => '',
			'wfacp_form_form_fields_1_embed_forms_2_shipping_country'                       => 'wfacp-col-left-half',
			'wfacp_form_form_fields_1_embed_forms_2_shipping_country_other_classes'         => '',
			'wfacp_form_form_fields_1_embed_forms_2_shipping_state'                         => 'wfacp-col-left-half',
			'wfacp_form_form_fields_1_embed_forms_2_shipping_state_other_classes'           => '',
			'wfacp_form_form_fields_1_embed_forms_2_billing_address_1'                      => 'wfacp-col-full',
			'wfacp_form_form_fields_1_embed_forms_2_billing_address_1_other_classes'        => '',
			'wfacp_form_form_fields_1_embed_forms_2_billing_city'                           => 'wfacp-col-left-half',
			'wfacp_form_form_fields_1_embed_forms_2_billing_city_other_classes'             => '',
			'wfacp_form_form_fields_1_embed_forms_2_billing_postcode'                       => 'wfacp-col-left-half',
			'wfacp_form_form_fields_1_embed_forms_2_billing_country'                        => 'wfacp-col-left-half',
			'wfacp_form_form_fields_1_embed_forms_2_billing_state'                          => 'wfacp-col-left-half',
			'wfacp_form_form_fields_1_embed_forms_2_billing_postcode_other_classes'         => '',
			'wfacp_form_form_fields_1_embed_forms_2_billing_country_other_classes'          => '',
			'wfacp_form_form_fields_1_embed_forms_2_billing_state_other_classes'            => '',
			'wfacp_style_typography_embed_forms_2_content_ff'                               => 'default',
		);
		$models               = wp_parse_args( $models, $default_models );
		$checkout_form_fields = [
			/* Form Style  */
			[
				"styleClasses" => "wfacp_design_accordion",
				'attributes'   => [ 'status' => 'close' ],
				"fields"       => [
					[
						'type'         => "label",
						'label'        => __( 'Form Style', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_main_design_heading ',
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Width', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'default'      => '640',
						'model'        => 'wfacp_form_section_embed_forms_2_step_form_max_width'
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Form Padding', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_last_half',
						'default'      => '16',
						'model'        => 'wfacp_form_section_embed_forms_2_form_inner_padding'
					],
					[
						'type'          => "select",
						'label'         => __( 'Border Type', 'woofunnels-aero-checkout' ),
						'styleClasses'  => 'wfacp_design_setting_third_half',
						'default'       => 'solid',
						'selectOptions' => [ 'hideNoneSelectedText' => true ],
						'values'        => [
							[ 'id' => 'none', 'name' => 'None' ],
							[ 'id' => 'solid', 'name' => 'Solid' ],
							[ 'id' => 'double', 'name' => 'Double' ],
							[ 'id' => 'dotted', 'name' => 'Dotted' ],
							[ 'id' => 'dashed', 'name' => 'Dashed' ],
						],
						'model'         => 'wfacp_form_section_embed_forms_2_form_border_type'
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Width', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_third_half',
						'default'      => '640',
						'model'        => 'wfacp_form_section_embed_forms_2_form_border_width'
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Border Color', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_third_half wfacp_last_half wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_form_border_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_form_border_color' ]
					],
					[
						'type'          => "select",
						'label'         => __( 'Typography', 'woofunnels-aero-checkout' ),
						'styleClasses'  => 'wfacp_design_setting_50',
						'default'       => 'abeezee',
						'selectOptions' => [
							'hideNoneSelectedText' => true,
						],
						'values'        => $typography_fonts,
						'model'         => 'wfacp_style_typography_embed_forms_2_content_ff'
					],
				]
			],
			/* Top Bar */
			[
				"styleClasses" => "wfacp_design_accordion",
				'attributes'   => [ 'status' => 'close' ],
				"fields"       => [

					[
						'type'         => "label",
						'label'        => __( 'Top Bar', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_main_design_heading wfacp_section_start',
					],
					[
						'type'         => "switch",
						'label'        => __( 'Disable Top Bar', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_full',
						'default'      => 'Off',
						'textOn'       => 'on',
						'textOff'      => 'Off',
						'model'        => 'wfacp_form_section_embed_forms_2_disable_steps_bar'
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Heading', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'default'      => '',
						'model'        => 'wfacp_form_section_embed_forms_2_name_0'
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Sub Heading', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 ',
						'default'      => '',
						'model'        => 'wfacp_form_section_embed_forms_2_headline_0'
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Step Heading (in px)', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'default'      => '',
						'model'        => 'wfacp_form_section_embed_forms_2_step_heading_font_size'
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Step Sub Heading (in px)', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'default'      => '',
						'model'        => 'wfacp_form_section_embed_forms_2_step_sub_heading_font_size'
					],
					[
						'type'          => "select",
						'label'         => __( 'Text Alignment', 'woofunnels-aero-checkout' ),
						'styleClasses'  => 'wfacp_design_setting_50 wfacp_clear',
						'default'       => 'left',
						'selectOptions' => [ 'hideNoneSelectedText' => true ],
						'values'        => [
							[ 'id' => 'left', 'name' => 'left' ],
							[ 'id' => 'center', 'name' => 'center' ],
							[ 'id' => 'right', 'name' => 'Right' ],
						],
						'model'         => 'wfacp_form_section_embed_forms_2_step_alignment'
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Background', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_third_half wfacp_color_field',
						'default'      => '#e61e1e',
						'model'        => 'wfacp_form_section_embed_forms_2_active_step_bg_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_active_step_bg_color' ]
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Text', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_third_half wfacp_color_field',
						'default'      => '#e61e1e',
						'model'        => 'wfacp_form_section_embed_forms_2_active_step_text_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_active_step_text_color' ]

					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Tab Color', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_third_half    wfacp_color_field',
						'default'      => '#e61e1e',
						'model'        => 'wfacp_form_section_embed_forms_2_active_step_tab_border_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_active_step_tab_border_color' ]

					],
				]
			],
			/* Section  */
			[
				"styleClasses" => "wfacp_design_accordion",
				'attributes'   => [ 'status' => 'close' ],
				"fields"       => [

					[
						'type'         => "label",
						'label'        => __( 'Section', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_main_design_heading',
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Font Size (in px)', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'default'      => '',
						'model'        => 'wfacp_form_section_embed_forms_2_heading_fs'
					],
					[
						'type'          => "select",
						'label'         => __( 'Font Weight', 'woofunnels-aero-checkout' ),
						'styleClasses'  => 'wfacp_design_setting_50 wfacp_last_half',
						'default'       => 'wfacp-normal',
						'selectOptions' => [ 'hideNoneSelectedText' => true ],
						'values'        => [
							[ 'id' => 'wfacp-normal', 'name' => 'Normal' ],
							[ 'id' => 'wfacp-bold', 'name' => 'Bold' ],
						],
						'model'         => 'wfacp_form_section_embed_forms_2_heading_font_weight'
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Margin Bottom', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'model'        => 'wfacp_form_section_embed_forms_2_rbox_margin'
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Padding (Left and Right)', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'model'        => 'wfacp_form_section_embed_forms_2_rbox_padding'
					],
					[
						'type'          => "select",
						'label'         => __( 'Text Alignment', 'woofunnels-aero-checkout' ),
						'styleClasses'  => 'wfacp_design_setting_50 wfacp_clear',
						'default'       => 'wfacp-text-left',
						'selectOptions' => [ 'hideNoneSelectedText' => true ],
						'values'        => [
							[ 'id' => 'wfacp-text-left', 'name' => 'left' ],
							[ 'id' => 'wfacp-text-center', 'name' => 'center' ],
							[ 'id' => 'wfacp-text-right', 'name' => 'Right' ],
						],
						'model'         => 'wfacp_form_section_embed_forms_2_heading_talign'
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Section Heading', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50  wfacp_color_field',
						'default'      => '#e61e1e',
						'model'        => 'wfacp_form_section_embed_forms_2_sec_heading_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_sec_heading_color' ]
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Section Background', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_last_half wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_sec_bg_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_sec_bg_color' ]
					],
					[
						'type'          => "select",
						'label'         => __( 'Border Type', 'woofunnels-aero-checkout' ),
						'styleClasses'  => 'wfacp_design_setting_third_half',
						'default'       => 'solid',
						'selectOptions' => [ 'hideNoneSelectedText' => true ],
						'values'        => [
							[ 'id' => 'none', 'name' => 'None' ],
							[ 'id' => 'solid', 'name' => 'Solid' ],
							[ 'id' => 'double', 'name' => 'Double' ],
							[ 'id' => 'dotted', 'name' => 'Dotted' ],
							[ 'id' => 'dashed', 'name' => 'Dashed' ],
						],
						'model'         => 'wfacp_form_section_embed_forms_2_rbox_border_type'
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Width', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_third_half',
						'default'      => '1',
						'model'        => 'wfacp_form_section_embed_forms_2_rbox_border_width'
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Color', 'woofunnels-aero-checkout ' ),
						'styleClasses' => 'wfacp_design_setting_third_half wfacp_color_field wfacp_last_half',
						'default'      => '#e61e1e',
						'model'        => 'wfacp_form_section_embed_forms_2_rbox_border_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_rbox_border_color' ]
					],
				]
			],
			/* Sub Section  */
			[
				"styleClasses" => "wfacp_design_accordion",
				'attributes'   => [ 'status' => 'close' ],
				"fields"       => [

					[
						'type'         => "label",
						'label'        => __( 'Section Sub heading', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_main_design_heading',
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Font Size (in px)', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'default'      => '',
						'model'        => 'wfacp_form_section_embed_forms_2_sub_heading_fs'
					],
					[
						'type'          => "select",
						'label'         => __( 'Font Weight', 'woofunnels-aero-checkout' ),
						'styleClasses'  => 'wfacp_design_setting_50',
						'selectOptions' => [ 'hideNoneSelectedText' => true ],
						'default'       => 'normal',
						'values'        => [
							[ 'id' => 'wfacp-normal', 'name' => 'Normal' ],
							[ 'id' => 'wfacp-bold', 'name' => 'Bold' ],
						],
						'model'         => 'wfacp_form_section_embed_forms_2_sub_heading_font_weight'

					],
					[
						'type'          => "select",
						'label'         => __( 'Text Alignment', 'woofunnels-aero-checkout' ),
						'styleClasses'  => 'wfacp_design_setting_50 ',
						'default'       => 'wfacp-text-left',
						'selectOptions' => [ 'hideNoneSelectedText' => true ],
						'values'        => [
							[ 'id' => 'wfacp-text-left', 'name' => 'left' ],
							[ 'id' => 'wfacp-text-center', 'name' => 'center' ],
							[ 'id' => 'wfacp-text-right', 'name' => 'Right' ],
						],
						'model'         => 'wfacp_form_section_embed_forms_2_sub_heading_talign'
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Section Subheading', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_sec_sub_heading_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_sec_sub_heading_color' ]
					],
				]
			],
			/* Field Style */
			[
				"styleClasses" => "wfacp_design_accordion",
				'attributes'   => [ 'status' => 'close' ],
				"fields"       => [

					[
						'type'         => "label",
						'label'        => __( 'Field Style', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_main_design_heading',
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Font Size (in px)', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'default'      => '',
						'model'        => 'wfacp_form_section_embed_forms_2_field_style_fs'
					],
					[
						'type'          => "select",
						'label'         => __( 'Field Border Layout', 'woofunnels-aero-checkout' ),
						'styleClasses'  => 'wfacp_design_setting_50',
						'default'       => 'solid',
						'selectOptions' => [ 'hideNoneSelectedText' => true ],
						'values'        => [
							[ 'id' => 'none', 'name' => 'None' ],
							[ 'id' => 'solid', 'name' => 'Solid' ],
							[ 'id' => 'double', 'name' => 'Double' ],
							[ 'id' => 'dotted', 'name' => 'Dotted' ],
							[ 'id' => 'dashed', 'name' => 'Dashed' ],
						],
						'model'         => 'wfacp_form_section_embed_forms_2_field_border_layout'
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Field Border Width', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'model'        => 'wfacp_form_section_embed_forms_2_field_border_width'
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Field Label', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_field_style_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_field_style_color' ]
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Field Border', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_third_half wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_field_border_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_field_border_color' ]
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Field Focus', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_third_half wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_field_focus_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_field_focus_color' ]
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Field Value', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_third_half wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_field_input_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_field_input_color' ]
					],
				]
			],
			/* Buttons */
			[
				"styleClasses" => "wfacp_design_accordion",
				'attributes'   => [ 'status' => 'close' ],
				"fields"       => [

					[
						'type'         => "label",
						'label'        => __( 'Buttons', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_main_design_heading',
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Button Label', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_full',
						'model'        => 'wfacp_form_section_embed_forms_2_btn_order-place_btn_text'
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Font Size', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'model'        => 'wfacp_form_section_embed_forms_2_btn_order-place_fs'
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Padding Top Bottom', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'model'        => 'wfacp_form_section_embed_forms_2_btn_order-place_top_bottom_padding'
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Padding Left Right', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'model'        => 'wfacp_form_section_embed_forms_2_btn_order-place_left_right_padding'
					],
					[
						'type'         => "input",
						'inputType'    => "number",
						'label'        => __( 'Border Radius', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'model'        => 'wfacp_form_section_embed_forms_2_btn_order-place_border_radius'
					],
					[
						'type'          => "select",
						'label'         => __( 'Font Weight', 'woofunnels-aero-checkout' ),
						'styleClasses'  => 'wfacp_design_setting_50',
						'selectOptions' => [ 'hideNoneSelectedText' => false ],
						'default'       => 'wfacp-normal',
						'values'        => [
							[ 'id' => 'normal', 'name' => 'Normal' ],
							[ 'id' => 'bold', 'name' => 'Bold' ],
						],

						'model' => 'wfacp_form_section_embed_forms_2_btn_order-place_btn_font_weight'
					],
					[
						'type'         => "select",
						'label'        => __( 'Width', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50',
						'default'      => 'normal',
						'values'       => [
							[ 'id' => '100', 'name' => 'Full Width' ],
							[ 'id' => 'initial', 'name' => 'Normal' ],
						],
						'model'        => 'wfacp_form_section_embed_forms_2_btn_order-place_width'
					],
					[
						'type'          => "select",
						'label'         => __( 'Alignment', 'woofunnels-aero-checkout' ),
						'styleClasses'  => 'wfacp_design_setting_50',
						'default'       => 'left',
						'selectOptions' => [ 'hideNoneSelectedText' => false ],
						'values'        => [
							[ 'id' => 'left', 'name' => 'left' ],
							[ 'id' => 'center', 'name' => 'center' ],
							[ 'id' => 'right', 'name' => 'Right' ],
						],
						'model'         => 'wfacp_form_section_embed_forms_2_btn_order-place_talign'
					],
					[
						'type'          => "select",
						'label'         => __( 'Sticky on Mobile', 'woofunnels-aero-checkout' ),
						'styleClasses'  => 'wfacp_design_setting_50',
						'selectOptions' => [ 'hideNoneSelectedText' => false ],
						'default'       => 'no_sticky',
						'values'        => [
							[ 'id' => 'yes_sticky', 'name' => 'Yes' ],
							[ 'id' => 'no_sticky', 'name' => 'No' ],

						],
						'model'         => 'wfacp_form_section_embed_forms_2_btn_order-place_make_button_sticky_on_mobile'
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Background', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_btn_order-place_bg_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_btn_order-place_bg_color' ]
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Label', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_btn_order-place_text_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_btn_order-place_text_color' ]
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Background Hover', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_btn_order-place_bg_hover_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_btn_order-place_bg_hover_color' ]
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Label Hover', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_btn_order-place_text_hover_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_btn_order-place_text_hover_color' ]

					],
					[
						'type'         => "textArea",
						'label'        => __( 'Text Below Place Order Buttons', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_full',
						'model'        => 'wfacp_form_section_text_below_placeorder_btn'
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Color', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_additional_text_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_additional_text_color' ]
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Background', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_additional_bg_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_additional_bg_color' ]
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Validation Text', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_validation_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_validation_color' ]
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Form Content', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_form_content_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_form_content_color' ]
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Background', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_section_bg_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_section_bg_color' ]
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Form Links Color', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_form_content_link_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_form_content_link_color' ]
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Form Links Hover Color', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_50 wfacp_color_field',
						'model'        => 'wfacp_form_section_embed_forms_2_form_content_link_hover_color',
						'attributes'   => [ 'id' => 'wfacp_form_section_embed_forms_2_form_content_link_hover_color' ]
					],

				]
			],
			//Payment Gateways
			[
				"styleClasses" => "wfacp_design_accordion",
				'attributes'   => [ 'status' => 'close' ],
				"fields"       => [

					[
						'type'         => "label",
						'label'        => __( 'Payment Methods', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_main_design_heading',
					],
					[
						'type'         => "input",
						'inputType'    => "text",
						'label'        => __( 'Heading', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_full',
						'model'        => 'wfacp_form_section_payment_methods_heading'
					],
					[
						'type'         => "textArea",
						'label'        => __( 'Sub heading', 'woofunnels-aero-checkout' ),
						'styleClasses' => 'wfacp_design_setting_full',
						'model'        => 'wfacp_form_section_payment_methods_sub_heading'
					]
				]
			],
		];


		return [ 'schema' => $checkout_form_fields, 'model' => $models ];
	}

	public function get_localize_data() {

		if ( is_array( $this->localize_data ) && count( $this->localize_data ) > 0 ) {
			return $this->localize_data;
		}

		$checkout_page_slug = 'checkout';
		$checkout_id        = wc_get_page_id( 'checkout' );
		if ( $checkout_id > - 1 ) {
			$checkout_page = get_post( $checkout_id );
			if ( $checkout_page instanceof WP_Post ) {
				$checkout_page_slug = $checkout_page->post_name;
			}
		}

		$this->localize_data['checkout_page_slug']       = $checkout_page_slug;
		$this->localize_data['checkout_page_slug_error'] = "Sorry! You cannot use the slug '" . $checkout_page_slug . "'  Its already reserved by native WooCommerce checkout. Please use another slug.";

		$this->localize_data['id']                = 0;
		$this->localize_data['name']              = '';
		$this->localize_data['post_name']         = '';
		$this->localize_data['post_content']      = '';
		$this->localize_data['post_url']          = '';
		$this->localize_data['base_url']          = WFACP_Common::base_url();
		$this->localize_data['template_edit_url'] = $this->template_edit_url();
		$this->localize_data['currency']          = get_woocommerce_currency_symbol();
		$this->localize_data['global_settings']   = WFACP_Common::global_settings( $this->wfacp_id );
		$this->localize_data['parameters']        = [
			'add_to_checkout' => WFACP_Core()->public->aero_add_to_checkout_parameter(),
			'qty'             => WFACP_Core()->public->aero_add_to_checkout_product_quantity_parameter(),
			'default'         => WFACP_Core()->public->aero_default_value_parameter(),
			'best_value'      => WFACP_Core()->public->aero_best_value_parameter(),
		];

		if ( ! empty( $this->wfacp_id ) ) {
			$post                                = get_post( $this->wfacp_id );
			$this->localize_data['id']           = $this->wfacp_id;
			$this->localize_data['name']         = get_the_title( $this->wfacp_id );
			$this->localize_data['post_name']    = ! is_null( $post ) ? $post->post_name : '';
			$this->localize_data['post_content'] = WFACP_Common::get_post_meta_data( $this->wfacp_id, '_post_description' );
			$this->localize_data['post_url']     = get_the_permalink( $this->wfacp_id );

			$this->localize_data['product_page_url']         = add_query_arg( [
				'page'     => 'wfacp',
				'wfacp_id' => $this->wfacp_id,
				'section'  => 'product',
			], admin_url( 'admin.php' ) );
			$this->localize_data['products']          = $this->get_page_product();
			$this->localize_data['products_settings'] = WFACP_Common::get_page_product_settings( $this->wfacp_id );
			$this->localize_data['design']            = $this->get_page_design();
			$this->localize_data['layout']            = $this->get_page_layout();
			$this->localize_data['address_order']     = WFACP_Common::get_address_field_order( $this->wfacp_id );
			$this->localize_data['optional_checkout_fields'] = WFACP_Common::get_optional_checkout_fields($this->wfacp_id);
			$this->localize_data['settings']          = WFACP_Common::get_page_settings( $this->wfacp_id );

		}

		unset( $this->localize_data['layout']['fieldsets_normalize'] );
		unset( $this->localize_data['layout']['checkout_fields'] );

		$this->localize_data['global_dependency_messages'] = $this->global_dependency_messages();
		$this->localize_data['available_countries']        = $this->get_available_countries();
		$this->localize_data['pageBuildersOptions']        = WFACP_Core()->template_loader->get_plugins_groupby_page_builders();
		$this->localize_data['pageBuildersTexts']          = WFACP_Core()->template_loader->localize_page_builder_texts();
		$this->localize_data['wfacp_i18n']                 = [
			'plugin_activate' => __( 'Activating plugin...', 'woofunnels-aero-checkout' ),
			'plugin_install'  => __( 'Installing plugin...', 'woofunnels-aero-checkout' ),
			'importing'       => __( 'Importing template...', 'woofunnels-aero-checkout' ),
		];

		return apply_filters( 'wfacp_admin_localize_data', $this->localize_data );

	}

	public function template_edit_url() {
		$url        = add_query_arg( [
			'wfacp_customize' => 'loaded',
			'wfacp_id'        => $this->wfacp_id,
		], get_the_permalink( $this->wfacp_id ) );
		$return_url = add_query_arg( [
			'page'     => 'wfacp',
			'section'  => 'design',
			'wfacp_id' => $this->wfacp_id,
		], admin_url( 'admin.php' ) );


		$customize_url = add_query_arg( [
			'url'             => apply_filters( 'wfacp_customize_url', urlencode_deep( $url ), $this ),
			'wfacp_customize' => 'loaded',
			'wfacp_id'        => $this->wfacp_id,
			'return'          => urlencode( $return_url ),
		], admin_url( 'customize.php' ) );

		$urls['pre_built']   = [
			'url'         => $customize_url,
			'button_text' => __( 'Customize', 'woofunnels-aero-checkout' )
		];
		$urls['embed_forms'] = [
			'url'         => $customize_url,
			'button_text' => __( 'Customize Form', 'woofunnels-aero-checkout' )
		];


		$customize_url = add_query_arg( [
			'page_id'  => $this->wfacp_id,
			'et_fb'    => '1',
			'wfacp_id' => $this->wfacp_id,
		], get_the_permalink( $this->wfacp_id ) );

		$urls['divi'] = [
			'url'         => $customize_url,
			'button_text' => __( 'Edit', 'woofunnels-aero-checkout' )
		];


		return apply_filters( 'wfacp_template_edit_link', $urls, $this );
	}

	private function get_page_product() {
		$output   = [];
		$products = WFACP_Common::get_page_product( $this->wfacp_id );

		if ( is_array( $products ) && count( $products ) > 0 ) {
			foreach ( $products as $unique_id => $pdata ) {
				$product = wc_get_product( $pdata['id'] );

				if ( $product instanceof WC_Product ) {
					$image_id     = $product->get_image_id();
					$default      = WFACP_Common::get_default_product_config();
					$default      = array_merge( $default, $pdata );
					$product_type = $product->get_type();
					if ( '' == $default['title'] ) {
						$default['title'] = $product->get_title();
					}

					$product_image_url = '';
					$images            = wp_get_attachment_image_src( $image_id );
					if ( is_array( $images ) && count( $images ) > 0 ) {
						$product_image_url = wp_get_attachment_image_src( $image_id )[0];
					}
					$default['image'] = apply_filters( 'wfacp_product_image', $product_image_url, $product );
					if ( '' == $default['image'] ) {
						$default['image'] = wc_placeholder_img_src();
					}

					$default['type'] = $product_type;
					/**
					 * @var $product WC_Product_Variable;
					 */
					if ( in_array( $product_type, WFACP_COmmon::get_variable_product_type() ) ) {
						$this->have_variable = true;
						$default['variable'] = 'yes';
						$default['price']    = $product->get_price_html();
					} else {
						if ( in_array( $product_type, WFACP_Common::get_variation_product_type() ) ) {
							$default['title'] = $product->get_name();
						}
						$row_data                 = $product->get_data();
						$sale_price               = $row_data['sale_price'];
						$default['price']         = wc_price( $row_data['price'] );
						$default['regular_price'] = wc_price( $row_data['regular_price'] );
						if ( '' != $sale_price ) {
							$default['sale_price'] = wc_price( $sale_price );
						}
					}
					$default['stock']                = $product->is_in_stock();
					$default['is_sold_individually'] = $product->is_sold_individually();
					$resp['product'][ $unique_id ]   = $default;
					$output[ $unique_id ]            = $default;
				};
			}
			if ( count( $output ) > 0 ) {
				return $output;
			}
		} else {
			return new stdClass();
		}
	}

	private function get_page_design() {

		$templates        = WFACP_Core()->template_loader->get_templates();
		$settings         = WFACP_Common::get_page_design( $this->wfacp_id, true );
		$design_type      = WFACP_Core()->template_loader->get_template_type();
		$design_type_data = WFACP_Core()->template_loader->get_template_type_data();
		$out              = array_merge( [
			'designs'          => $templates,
			'design_types'     => $design_type,
			'design_type_data' => $design_type_data,
			'template_active'  => 'yes'
		], $settings );

		return $out;
	}

	private function get_page_layout() {

		/**
		 * remove selected field(step field) from main checkout fields [billing,shipping];
		 */

		$data                  = $this->manage_input_fields();
		$data['default_steps'] = WFACP_Common::get_default_steps_fields();

		return $data;
	}

	/**
	 * Remove Selected field from available checkout fields
	 *
	 * @param $input_fields
	 * @param array $selected_fields
	 *
	 * @return mixed
	 */
	private function manage_input_fields() {
		$page_data        = WFACP_Common::get_page_layout( $this->wfacp_id );
		$input_fields     = $this->get_checkout_field();
		$input_fields     = $this->merge_custom_fields( $input_fields );
		$available_fields = $input_fields;
		$selected_fields  = $page_data['fieldsets'];

		if ( empty( $selected_fields ) || ! is_array( $selected_fields ) ) {
			return $input_fields;
		}
		foreach ( $selected_fields as $step => $step_data ) {
			if ( ! is_array( $step_data ) ) {
				continue;
			}

			foreach ( $step_data as $index => $section ) {
				if ( empty( $section['fields'] ) ) {
					continue;
				}

				$fields = $section['fields'];
				foreach ( $fields as $f_index => $field ) {
					if ( ! isset( $field['id'] ) || ! isset( $field['field_type'] ) ) {
						continue;
					}
					$id   = $field['id'];
					$type = $field['field_type'];
					if ( ! isset( $field['cssready'] ) ) {
						$input_fields[ $type ][ $id ]['cssready'] = [];
					}
					if ( $id == 'address' || $id == 'shipping-address' ) {
						if ( isset( $this->address_fields[ $type ] ) ) {
							$this->address_fields[ $type ][ $id ] = true;
						}
					}

					$temp_page_field = $page_data['fieldsets'][ $step ][ $index ]['fields'][ $f_index ];

					$page_data['fieldsets'][ $step ][ $index ]['fields'][ $f_index ] = apply_filters( 'wfacp_builder_merge_field_arguments', $temp_page_field, $id, $type, $available_fields );


					if ( isset( $input_fields[ $type ][ $id ] ) ) {
						unset( $input_fields[ $type ][ $id ] );
					}
				}
			}
		}

		$input_fields = $this->add_address_field( $input_fields );

		$available_fields = $this->add_address_field( $available_fields, true );
		foreach ( $input_fields as $key => $field_data ) {
			if ( is_array( $field_data ) && count( $field_data ) == 0 ) {
				$input_fields[ $key ] = new stdClass();
			}
		}

		foreach ( $input_fields as $type => $section_fields ) {
			foreach ( $section_fields as $field_id => $field ) {
				if ( ! isset( $field['data_label'] ) ) {
					$input_fields[ $type ][ $field_id ]['data_label'] = $field['label'];
				}
			}

		}
		$input_fields = [
			'input_fields'     => $input_fields,
			'available_fields' => $available_fields,
		];
		$data         = array_merge( $page_data, $input_fields );


		return $data;
	}

	private function get_checkout_field() {
		$billing = WFACP_Common::get_address_fields( 'billing_' );
		$output  = [
			'billing' => $billing,
		];

		$products_fields = WFACP_Common::get_product_field();
		if ( count( $products_fields ) > 0 ) {
			$output['product'] = $products_fields;
		}
		$advanced_fields = WFACP_Common::get_advanced_fields();
		if ( get_option( 'woocommerce_enable_order_comments', 'yes' ) !== 'yes' ) {
			unset( $advanced_fields['order_comments'] );
		}

		$output['advanced'] = $advanced_fields;

		return $output;
	}

	/**
	 * Merge Custom created field with real fields;
	 *
	 * @param $wfacp_id
	 * @param $input_fields
	 *
	 * @return mixed
	 */
	private function merge_custom_fields( $input_fields ) {

		$custom_fields = WFACP_Common::get_page_custom_fields( $this->wfacp_id );
		if ( ! is_array( $custom_fields ) ) {
			return $input_fields;
		}
		foreach ( $custom_fields as $section => $fields ) {
			foreach ( $fields as $key => $field ) {
				$input_fields[ $section ][ $key ] = $field;
			}
		}

		return $input_fields;
	}

	private function add_address_field( $input_fields, $force = false ) {

		foreach ( [ 'billing' ] as $type ) {
			if ( isset( $input_fields[ $type ] ) && ! isset( $this->address_fields[ $type ]['address'] ) || true == $force ) {

				$input_fields[ $type ]['address'] = WFACP_Common::get_single_address_fields( $type );

			}
			if ( isset( $input_fields[ $type ] ) && ! isset( $this->address_fields[ $type ]['shipping-address'] ) || true == $force ) {

				$input_fields[ $type ]['shipping-address'] = WFACP_Common::get_single_address_fields( 'shipping' );
			}
		}

		return $input_fields;
	}

	/**
	 * @return array
	 */
	public function global_dependency_messages() {
		$aero_messages = [];
		if ( wc_shipping_enabled() ) {

			$shipping_location = admin_url( 'admin.php?page=wc-settings' );
			$shipping_methods  = admin_url( 'admin.php?page=wc-settings&tab=shipping' );
			$msg               = __( sprintf( 'Your store has <a href="%s">shipping location</a> enabled. Depending upon shipping method configuration, checkout may need  "Order Summary" field. Please drag "Order Summary" field to place in form. ', $shipping_location, $shipping_methods ), 'funnel-builder' );
			$aero_messages[]   = [
				'message'     => $msg,
				'ids'         => [
					'shipping_calculator',
					'order_summary'
				],
				'show'        => 'yes',
				'dismissible' => true,
				'is_global'   => false,
				'type'        => 'wfacp_warning',
				'call_back'   => '',
				'key'         => 'wfacp_shipping_location_enabled_warning'
			];

			if ( wc_ship_to_billing_address_only() ) {

				$msg = sprintf( __( "<a href='%s'>Shipping destination</a> is set to 'Force shipping to customer billing address'. Please remove Shipping Address field from the checkout form.", 'woofunnels-aero-checkout' ), $shipping_methods );

				$aero_messages[] = [
					'message'       => $msg,
					'id'            => 'shipping_address',
					'show'          => 'yes',
					'dismissible'   => false,
					'reverse_check' => true,
					'is_global'     => false,
					'type'          => 'wfacp_error',
				];
			}

		}

		$aero_messages[] = [
			'message'     => __( 'Billing First Name & Last Name are available in the form. Please disable First Name and Last Name from Billing Address field.', 'woofunnels-aero-checkout' ),
			'show'        => 'yes',
			'dismissible' => false,
			'is_global'   => false,
			'type'        => 'wfacp_error',
			'call_back'   => 'wfacp_duplicate_billing_first_and_last_name',
		];



		$messages = apply_filters( 'wfacp_global_dependency_messages', [] );


		if ( ! empty( $messages ) && is_array( $messages ) ) {
			$aero_messages = array_merge( $aero_messages, $messages );
		}

		$final_messages = [];
		if ( empty( $aero_messages ) ) {
			$final_messages = new stdClass();
		} else {
			foreach ( $aero_messages as $msg ) {

				$mid = md5( $msg['message'] );
				if ( ! isset( $msg['is_global'] ) || false === $msg['is_global'] ) {
					$pageID = WFACP_Common::get_id();
					$mid    = md5( $msg['message'] . $pageID );
				}


				if ( isset( $msg['dismissible'] ) ) {
					$msg['dismissible'] = wc_string_to_bool( $msg['dismissible'] );
				} else {
					$msg['dismissible'] = false;
				}
				$final_messages[ $mid ] = $msg;
			}
		}

		return $this->hide_notification( $final_messages );
	}

	private function hide_notification( $messages ) {
		if ( empty( $messages ) ) {
			return $messages;
		}
		$hide_messages = get_option( 'wfacp_global_notifications', [] );

		$post_message = get_post_meta( WFACP_Common::get_id(), 'notifications', true );
		if ( is_array( $post_message ) ) {
			$hide_messages = array_merge( $hide_messages, $post_message );
		}
		if ( empty( $hide_messages ) ) {
			return $messages;
		}


		foreach ( $messages as $mid => $message ) {
			if ( array_key_exists( $mid, $hide_messages ) ) {
				unset( $messages[ $mid ] );
			}
		}

		return $messages;

	}

	public function get_available_countries() {
		$output    = [];
		$countries = WC()->countries->get_allowed_countries();
		foreach ( $countries as $code => $country ) {
			$country  = html_entity_decode( $country );
			$output[] = [ 'id' => $code, 'name' => $country ];
		}

		return $output;
	}

	public function admin_customizer_enqueue_assets() {
		if ( WFACP_Common::is_customizer() ) {
			$id = WFACP_Common::get_id();


			?>
            <style>
                li#customize-control-wfacp_c_<?php echo $id; ?>-wfacp_form_section_ct_step_form_steps {
                    border-top: none;
                }

                li#customize-control-wfacp_c_<?php echo $id; ?>-wfacp_form_section_step_1,
                li#customize-control-wfacp_c_<?php echo $id; ?>-wfacp_form_section_step_2,
                li#customize-control-wfacp_c_<?php echo $id; ?>-wfacp_form_section_step_3 {
                    border-top: none;
                }
            </style>
			<?php

			wp_enqueue_style( 'wfacp-customizer', $this->get_admin_url() . '/assets/css/wfacp-customizer.css', array(), WFACP_VERSION_DEV );
			wp_enqueue_style( 'wfacp-modal-css', $this->get_admin_url() . '/assets/css/wfacp-modal.css', array(), WFACP_VERSION_DEV );
			wp_enqueue_script( 'wfacp-modal-js', $this->get_admin_url() . '/assets/js/wfacp-modal.js', array(), WFACP_VERSION_DEV );
		}
	}

	public function open_admin_bar() {


		echo "<style>
#order_data #wfacp_admin_advanced_field input[type='radio']{width: auto;float: left;margin: 0 5px 5px 0;}
#order_data #wfacp_admin_advanced_field span.wfacp_radio_options_group{display: block;}
#order_data #wfacp_admin_advanced_field span.wfacp_radio_options_group:before, #order_data #wfacp_admin_advanced_field span.wfacp_radio_options_group:after{content: ''; display: block;}
#order_data #wfacp_admin_advanced_field span.wfacp_radio_options_group:after{    clear: both;} </style>";
	}

	public function admin_page() {

		if ( isset( $_GET['page'] ) && $_GET['page'] === 'wfacp' ) {

			if ( filter_input( INPUT_GET, 'section', FILTER_UNSAFE_RAW ) === 'bwf_settings' ) {
				BWF_Admin_General_Settings::get_instance()->__callback();

				return;
			}

			if ( $this->wfacp_id > 0 ) {
				$post = get_post( $this->wfacp_id );
				if ( ! is_null( $post ) && $post->post_type == WFACP_Common::get_post_type_slug() ) {
					include __DIR__ . '/views/view.php';
				}

			} else {
				/**
				 * No need here to save it in transient as we are not removing the transients at appropriate places
				 */
				$path = __DIR__ . '/views/admin.php';

				$tab = filter_input( INPUT_GET, 'tab', FILTER_UNSAFE_RAW );
				if ( ! is_null( $tab ) ) {
					$tab  = trim( $tab );
					$path = __DIR__ . "/views/{$tab}.php";
				}
				if ( file_exists( $path ) ) {
					include_once $path;
				}
			}
		}
	}

	public function add_css_ready_classes( $address ) {

		if ( is_array( $address ) && count( $address ) > 0 ) {
			foreach ( $address as $key => $field ) {
				$address[ $key ]['cssready'] = [];
			}
		}

		return $address;
	}


	/**
	 * this function use for display advanced field in order backend in General Tab
	 *
	 * @param $order WC_Order
	 */
	public function show_advanced_field_order( $order ) {

		if ( ! $order instanceof WC_Order ) {
			return;
		}

		$wfacp_id = wfacp_get_order_meta( $order, '_wfacp_post_id' );

		if ( empty( $wfacp_id ) ) {
			return;
		}

		$title      = get_the_title( $wfacp_id );
		$title_link = add_query_arg( [
			'page'     => 'wfacp',
			'wfacp_id' => $wfacp_id,
			'section'  => 'product',
			'new_ui'   => 'wffn',
		], admin_url( 'admin.php' ) );

		$permalink = wfacp_get_order_meta( $order, '_wfacp_source' );
		if ( empty( $permalink ) ) {
			$permalink = get_the_permalink( $wfacp_id );
		}
		$display_text = str_replace( home_url(), '', $permalink );
		?>
        <div style="clear: both;">
            <style>
                #wfacp_admin_advanced_field .optional {
                    display: none;
                }
            </style>
        </div>
        <div style="margin-top:15px" class="wfacp_order_backend_field_container">
            <h3 style="display: inline">Checkout</h3>
            <p><b><?php _e( 'Template', 'woofunnel-aero-checkout' ); ?>:</b> <a href="<?php echo $title_link; ?>"
                                                                                target="_blank"><?php echo $title; ?></a>
            </p>
            <p><b><?php _e( 'Source', 'woofunnel-aero-checkout' ); ?>:</b> <a href="<?php echo $permalink; ?>"
                                                                              target="_blank"><?php echo $display_text; ?></a>
            </p>
        </div>
		<?php
		$wfacp_id = absint( $wfacp_id );
		$cfields  = WFACP_Common::get_page_custom_fields( $wfacp_id );
		if ( ! isset( $cfields['advanced'] ) ) {
			return;
		}
		$advancedFields = $cfields['advanced'];
		if ( ! is_array( $advancedFields ) || count( $advancedFields ) == 0 ) {
			return;
		}

		$heading_print = false;

		foreach ( $advancedFields as $field_key => $field ) {
			if ( empty( $field ) || ! isset( $field['is_wfacp_field'] ) || false === wc_string_to_bool( $field['is_wfacp_field'] ) ) {
				continue;
			}


			$has_data  = wfacp_get_order_meta( $order, $field_key );
			$field_key = 'wfacp_' . $field_key;


			if ( false == $heading_print ) {
				printf( '<div style="clear: both;"></div><div style="margin-top:15px" class="wfacp_order_backend_field_container"><h3 style="display: inline">%s</h3> <span class="dashicons dashicons-edit" onclick="wfacp_show_admin_advanced_field(this)" style="cursor: pointer"></span><fieldset id="wfacp_admin_advanced_field" disabled>', __( 'Custom Fields', 'woofunnels-aero-checkout' ) );
				$heading_print = true;
			}
			if ( isset( $field['required'] ) ) {
				unset( $field['required'] );
			}
			if ( $field['type'] == 'hidden' ) {
				$field['type'] = 'text';
			}
			if ( $field['type'] == 'select2' ) {
				$field['type'] = 'select';
			}

			if ( isset( $field['class'] ) ) {
				$field['class'] = [ 'form-field', ' form-field-wide' ];
			}
			if ( isset( $field['placeholder'] ) ) {
				unset( $field['placeholder'] );
			}
			woocommerce_form_field( $field_key, $field, $has_data );

		}
		if ( true == $heading_print ) {
			echo '</fieldset></div>';
		}
	}


	public function maybe_remove_all_notices_on_page() {

		if ( isset( $_GET['page'] ) && 'wfacp' == $_GET['page'] ) {
			global $wp_filter;
			if ( isset( $wp_filter['admin_notices'] ) ) {
				foreach ( $wp_filter['admin_notices']->callbacks as $f_key => $f ) {
					foreach ( $f as $c_name => $clback ) {

						if ( false !== strpos( $c_name, 'XL_' ) ) {
							continue;
						}
						unset( $wp_filter['admin_notices']->callbacks[ $f_key ][ $c_name ] );

					}
				}
			}
		}

		if ( isset( $_GET['page'] ) && 'wfacp' == $_GET['page'] && isset( $_GET['wfacp_id'] ) && $_GET['wfacp_id'] > 0 ) {

			remove_all_actions( 'admin_notices' );
		}
	}


	public function restrict_notices_display() {
		/** Inside AeroCheckout page */
		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'wfacp' ) {
			remove_all_actions( 'admin_notices' );
			remove_all_actions( 'all_admin_notices' );
		}
	}

	public function wfacp_builder_merge_field_arguments( $field, $id, $type, $available_fields ) {

		if ( $id == 'shipping_calculator' && isset( $available_fields[ $type ][ $id ] ) ) {
			$default = $available_fields[ $type ][ $id ];
			$field   = wp_parse_args( $field, $default );
		} elseif ( $id == 'product_switching' ) {

			$default = $available_fields[ $type ][ $id ];
			$field   = wp_parse_args( $field, $default );
		} elseif ( $id == 'vat_number' ) {
			$default = $available_fields[ $type ][ $id ];

			$field = wp_parse_args( $field, $default );
			if ( isset( $default['depend_dency_message'] ) ) {
				$field['depend_dency_message'] = $default['depend_dency_message'];
			}


		} elseif ( $id == 'address' || $id == 'shipping-address' ) {
			$field['fields_options'] = apply_filters( 'wfacp_' . $type . '_address_options', $field['fields_options'] );
		}

		if ( ! isset( $field['data_label'] ) ) {
			$field['data_label'] = $field['label'];
		}

		return $field;
	}

	public function remove_theme_css_and_scripts() {
		$theme_css_path = $this->get_theme_css_path();
		global $wp_scripts, $wp_styles;
		$registered_script = $wp_scripts->registered;
		if ( ! empty( $registered_script ) ) {
			foreach ( $registered_script as $handle => $data ) {
				if ( $this->find_js_css_handle( $data->src, $theme_css_path ) ) {
					unset( $wp_scripts->registered[ $handle ] );
					wp_dequeue_script( $handle );
				}
			}
		}

		$registered_style = $wp_styles->registered;
		if ( ! empty( $registered_style ) ) {
			foreach ( $registered_style as $handle => $data ) {
				if ( $this->find_js_css_handle( $data->src, $theme_css_path ) ) {
					unset( $wp_styles->registered[ $handle ] );
					wp_dequeue_script( $handle );
				}
			}
		}

	}

	/**
	 * Find removal folder path exist in enqueue js and css url
	 *
	 * @param $url
	 *
	 * @return bool
	 */
	private function find_js_css_handle( $url, $paths ) {
		if ( ! WFACP_Common::is_builder() || empty( $paths ) ) {
			return false;
		}
		foreach ( $paths as $path ) {
			if ( false !== strpos( $url, $path ) ) {
				return true;
			}
		}

		return false;

	}

	public function get_theme_css_path() {
		$paths   = [ '/themes/', '/cache/' ];
		$plugins = [
			'revslider',
		];
		if ( isset( $_GET['page'] ) && $_GET['page'] == 'wfacp' ) {
			$plugins[] = '/elementor/';
			$plugins[] = '/divi-builder/core/admin/js/support-center';
		}
		$paths = array_merge( $paths, $plugins );

		return apply_filters( 'wfacp_admin_css_js_removal_paths', $paths, $this );
	}

	public function plugin_actions( $links ) {

		$link = '<i class="woofunnels-slug" data-slug="' . WFACP_PLUGIN_BASENAME . '"></i>';
		if ( isset( $links['deactivate'] ) ) {
			$links['deactivate'] .= $link;
		}


		return $links;
	}

	public function plugin_uninstall_reasons( $uninstall_reasons ) {

		if ( ! isset( $uninstall_reasons['default'] ) ) {
			return $uninstall_reasons;
		}

		$sorted        = [ 0, 1, 2, 6, 3, 4, 5, 7 ];
		$final_reasons = [];

		array_push( $uninstall_reasons['default'], [
			'id'                => 35,
			'text'              => __( 'Doing testing', 'woofunnels-aero-checkout' ),
			'input_type'        => '',
			'input_placeholder' => '',
		] );
		array_push( $uninstall_reasons['default'], [
			'id'                => 42,
			'text'              => __( 'My checkout is not looking good', 'woofunnels-aero-checkout' ),
			'input_type'        => '',
			'input_placeholder' => '',
		] );
		array_push( $uninstall_reasons['default'], [
			'id'                => 41,
			'text'              => __( 'Troubleshooting conflicts with other plugins', 'woofunnels-aero-checkout' ),
			'input_type'        => '',
			'input_placeholder' => '',
		] );

		foreach ( $sorted as $key => $value ) {
			if ( $value === 2 ) {
				$uninstall_reasons['default'][ $value ]['text'] = 'I only need the plugin for shorter period';
			}
			$final_reasons['default'][] = $uninstall_reasons['default'][ $value ];
		}


		return $final_reasons;
	}

	public function add_meta_boxes_for_shortcodes() {

		$id     = WFACP_Common::get_id();
		$design = WFACP_Common::get_page_design( $id );
		if ( $design['selected_type'] !== 'embed_forms' ) {
			return;
		}
		$post_type = WFACP_Common::get_post_type_slug();
		add_meta_box( 'woofunnels-aero-checkout-shortcode', __( 'FunnelKit Checkout', 'woofunnels-aero-checkout' ), [
			$this,
			'render_shortcode_meta_box'
		], $post_type, 'side', 'default' );
	}

	public function render_shortcode_meta_box() {
		$id     = WFACP_Common::get_id();
		$normal = "[wfacp_forms]";

		if ( $id > 0 ) {
			?>
            <style>
                .wfacp_shortcode .wfacp_shortcode_inner {
                    margin: 0 0 20px;
                }

                a.wfacp_copy_text {
                    float: right;
                }
            </style>
            <div class="wfacp_shortcode">
                <div class="wfacp_shortcode_inner">
                    <div class="wfacp_description">
                        <label for='wfacp_shortcode_normal'><?php _e( 'Form Shortcode', 'woofunnels-aero-checkout' ) ?></label>
                        <input type="text" readonly="readonly" id='wfacp_shorcode_normal' style="width: 100%;" value="<?php echo $normal ?>">
                    </div>
                    <a href="javascript:void(0)" class="wfacp_copy_text">
                        <svg fill="#0073aa" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" width="20" height="20">
                            <path d="M 18.5 5 C 15.480226 5 13 7.4802259 13 10.5 L 13 32.5 C 13 35.519774 15.480226 38 18.5 38 L 34.5 38 C 37.519774 38 40 35.519774 40 32.5 L 40 10.5 C 40 7.4802259 37.519774 5 34.5 5 L 18.5 5 z M 18.5 8 L 34.5 8 C 35.898226 8 37 9.1017741 37 10.5 L 37 32.5 C 37 33.898226 35.898226 35 34.5 35 L 18.5 35 C 17.101774 35 16 33.898226 16 32.5 L 16 10.5 C 16 9.1017741 17.101774 8 18.5 8 z M 11 10 L 9.78125 10.8125 C 8.66825 11.5545 8 12.803625 8 14.140625 L 8 33.5 C 8 38.747 12.253 43 17.5 43 L 30.859375 43 C 32.197375 43 33.4465 42.33175 34.1875 41.21875 L 35 40 L 17.5 40 C 13.91 40 11 37.09 11 33.5 L 11 10 z"></path>
                        </svg><?php _e( 'Copy' ); ?></a>
                </div>
            </div>
            <script>
                window.addEventListener('load', function () {
                    (function ($) {
                        $(document).on('click', '.wfacp_copy_text', function () {
                            var sibling = $(this).siblings('.wfacp_description');
                            if (sibling.length > 0) {
                                sibling.find('input').select();
                                document.execCommand("copy");
                                wfacp.show_data_save_model(wfacp_localization.global.shortcode_copy_message);
                            }
                        });
                    })(jQuery);
                });

            </script>
			<?php
		}
	}

	public function append_checkout_post_list( $output ) {

		if ( empty( $this->checkout_post_list ) ) {
			global $wpdb;
			$page_data                = $wpdb->get_results( "SELECT posts.ID as post_id FROM `{$wpdb->prefix}postmeta` as meta INNER JOIN  `{$wpdb->prefix}posts` as posts on meta.post_id=posts.ID WHERE meta.meta_key = '_is_aero_checkout_page' and posts.post_status='publish' ORDER BY posts.post_title ASC", ARRAY_A );
			$this->checkout_post_list = $page_data;
		}
		if ( is_array( $this->checkout_post_list ) && count( $this->checkout_post_list ) > 0 ) {
			foreach ( $this->checkout_post_list as $v ) {
				$post     = get_post( $v['post_id'] );
				$output[] = [
					'id'   => $post->ID,
					'name' => $post->post_title . ' - ' . __( 'Page', 'woofunnels-aero-checkout' ),
					'type' => 'page',
				];
			}
		}

		return $output;
	}

	public function get_address_field_html( $id = '' ) {

		include __DIR__ . '/views/sections/fields/address.php';
	}

	public function arrange_billing_fields( $options ) {

		if ( ! isset( $_POST['address_order'] ) || empty( $_POST['address_order']['address'] ) ) {
			return $options;
		}
		$addressOrder = $_POST['address_order'];

		$options = $this->arrange_order_of_address_fields( $options, $addressOrder );

		return $options;
	}

	public function arrange_shipping_fields( $options ) {
		if ( ! isset( $_POST['address_order'] ) || empty( $_POST['address_order']['shipping-address'] ) ) {
			return $options;
		}

		$addressOrder = $_POST['address_order'];
		$options      = $this->arrange_order_of_address_fields( $options, $addressOrder, 'shipping' );

		return $options;
	}


	public function arrange_order_of_address_fields( $options, $addressOrder, $id = '', $replace_label = true ) {

		$temp_order_key = ( 'shipping' == $id ) ? 'shipping-address' : 'address';

		if ( count( $addressOrder ) > 0 && isset( $addressOrder[ $temp_order_key ] ) && count( $addressOrder[ $temp_order_key ] ) ) {
			$temp_options = [];
			$items        = $addressOrder[ $temp_order_key ];
			$same_data    = [];
			$same_key     = '';
			foreach ( $items as $item ) {
				$i_key = $item['key'];
				if ( ! isset( $options[ $i_key ] ) ) {
					continue;
				}
				if ( $i_key == 'same_as_billing' || $i_key == 'same_as_shipping' ) {
					$same_data[ $i_key ] = $item['status'];
					if ( true == $replace_label ) {
						$same_data[ $i_key . '_label' ] = trim( $item['label'] );
					} else {
						$same_data[ $i_key . '_label' ] = $options[ $i_key ][ $i_key . '_label' ];
					}
					if ( isset( $item['label_2'] ) ) {
						$same_data[ $i_key . '_label_2' ] = trim( $item['label_2'] );
					} else {
						$same_data[ $i_key . '_label_2' ] = $options[ $i_key ][ $i_key . '_label_2' ];
					}
					$same_key = $i_key;

					continue;
				}

				$temp_options[ $i_key ]                = $options[ $i_key ];
				$keys                                  = array_keys( $options[ $i_key ] );
				$status_key                            = $keys[0];
				$label_key                             = $keys[1];
				$temp_options[ $i_key ][ $status_key ] = $item['status'];
				if ( true == $replace_label ) {
					$temp_options[ $i_key ][ $label_key ] = trim( $item['label'] );
				}

				if ( isset( $keys[2] ) ) {
					$placeholder_key                            = $keys[2];
					$temp_options[ $i_key ][ $placeholder_key ] = trim( $item['placeholder'] );
				}

				if ( isset( $item['required'] ) ) {
					$temp_options[ $i_key ]['required'] = $item['required'];
				}
			}
			if ( '' !== $i_key ) {
				$temp_options = array_merge( [ $same_key => $same_data ], $temp_options );

			}

			$options = $temp_options;

		};

		return apply_filters( 'arrange_order_of_address_fields', $options );

	}

	public function add_back_button() {
		global $post;

		$wfacp_id = ( WFACP_Common::get_post_type_slug() === $post->post_type ) ? $post->ID : 0;
		if ( 0 === $wfacp_id ) {
			return;
		}
		$funnel_id = get_post_meta( $wfacp_id, '_bwf_in_funnel', true );
		if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
			BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );
			$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
				'page'      => 'bwf',
				'path'      => "/funnel-checkout/" . $wfacp_id . "/design",
				'funnel_id' => $funnel_id,
			], admin_url( 'admin.php' ) ) );
		} else {
			$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
				'page'     => 'wfacp',
				'wfacp_id' => $wfacp_id,
				'section'  => 'design',
			], admin_url( 'admin.php' ) ) );
		}

		if ( use_block_editor_for_post_type( WFACP_Common::get_post_type_slug() ) ) {
			add_action( 'admin_footer', array( $this, 'render_back_to_aero_script_for_block_editor' ) );
		} else { ?>
            <div id="wfacp-switch-mode">
                <a id="wfacp-back-button" class="button button-default button-large" href="<?php echo esc_url( $edit_link ); ?>">
					<?php esc_html_e( '&#8592; Back to Checkout Page', 'woofunnels-aero-checkout' ); ?>
                </a>
            </div>
            <script>
                window.addEventListener('load', function () {
                    (function (window, wp) {
                        var link = document.querySelector('a.components-button.edit-post-fullscreen-mode-close');
                        if (link) {
                            link.setAttribute('href', "<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>")
                        }

                    })(window, wp)
                });

            </script>
			<?php
		}
	}

	public function render_back_to_aero_script_for_block_editor() {
		global $post;

		$wfacp_type = WFACP_Common::get_post_type_slug();
		$wfacp_id   = ( $wfacp_type === $post->post_type ) ? $post->ID : 0;
		if ( $wfacp_id > 0 ) {
			$funnel_id = get_post_meta( $wfacp_id, '_bwf_in_funnel', true );
			if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
				BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );
			}

			if ( ! empty( $funnel_id ) && abs( $funnel_id ) > 0 ) {
				BWF_Admin_Breadcrumbs::register_ref( 'funnel_id', $funnel_id );
				$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page'      => 'bwf',
					'path'      => "/funnel-checkout/" . $wfacp_id . "/design",
					'funnel_id' => $funnel_id,
				], admin_url( 'admin.php' ) ) );
			} else {
				$edit_link = BWF_Admin_Breadcrumbs::maybe_add_refs( add_query_arg( [
					'page'     => 'wfacp',
					'wfacp_id' => $wfacp_id,
					'section'  => 'design',
				], admin_url( 'admin.php' ) ) );
			}

			?>

            <script id="wfacp-back-button-template" type="text/html">
                <div id="wfacp-switch-mode">
                    <a id="wfacp-back-button" class="button button-default button-large" href="<?php echo esc_url( $edit_link ); ?>">
						<?php echo __( '&#8592; Back to Checkout Page', 'elementor' ); ?>
                    </a>
                </div>

            </script>

            <script>
                window.addEventListener('load', function () {
                    (function (window, wp) {

                        const {Toolbar, ToolbarButton} = wp.components;

                        var link_button = wp.element.createElement(
                            ToolbarButton,
                            {
                                variant: 'secondary',
                                href: "<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>",
                                id: 'wfacp-back-button',
                                className: 'button is-secondary',
                                style: {
                                    display: 'flex',
                                    height: '33px'
                                },
                                text: "<?php esc_html_e( '← Back to Checkout Page', 'woofunnels-aero-checkout' ); ?>",
                                label: "<?php esc_html_e( 'Back to Checkout Page', 'woofunnels-aero-checkout' ); ?>"
                            }
                        );
                        var linkWrapper = '<div id="wfacp-switch-mode"></div>';

                        // check if gutenberg's editor root element is present.
                        var editorEl = document.getElementById('editor');
                        if (!editorEl) { // do nothing if there's no gutenberg root element on page.
                            return;
                        }
                        wp.domReady(function () {
                            var link = document.querySelector('body a.components-button.edit-post-fullscreen-mode-close');
                            if (link) {
                                link.setAttribute('href', "<?php echo htmlspecialchars_decode( esc_url( $edit_link ) );//phpcs:ignore ?>")
                            }
                        })

                        wp.data.subscribe(function () {
                            setTimeout(function () {
                                if (!document.getElementById('wfacp-switch-mode')) {
                                    var toolbalEl = editorEl.querySelector('.edit-post-header__toolbar .edit-post-header-toolbar');
                                    if (toolbalEl instanceof HTMLElement) {
                                        toolbalEl.insertAdjacentHTML('beforeend', linkWrapper);
                                        setTimeout(() => {
                                            wp.element.render(link_button, document.getElementById('wfacp-switch-mode'));
                                        }, 1);
                                    }
                                }
                            }, 1)
                        });
                    })(window, wp)
                });

            </script>
		<?php }
	}

	public function remove_page_attributes() {
		if ( empty( $this->wfacp_id ) || 0 === absint( $this->wfacp_id ) ) {
			return;
		}

		$page_design = WFACP_Common::get_page_design( $this->wfacp_id );
		if ( 'pre_built' !== $page_design['selected_type'] ) {
			return;
		}

		remove_post_type_support( WFACP_Common::get_post_type_slug(), 'editor' );
		add_filter( 'use_block_editor_for_post', [ $this, 'remove_block_editor' ], 10, 2 );
		$meta_box = [ 'pageparentdiv' ];

		$meta_box = apply_filters( 'wfacp_remove_post_meta_boxes', $meta_box );
		if ( is_array( $meta_box ) && count( $meta_box ) > 0 ) {
			foreach ( $meta_box as $box ) {
				remove_meta_box( $box, WFACP_Common::get_post_type_slug(), 'side' );
			}
		}
	}

	public function remove_block_editor( $status, $post_type ) {
		if ( ! is_null( $post_type ) && $post_type->post_type == WFACP_Common::get_post_type_slug() ) {
			$status = false;
		}

		return $status;

	}


	public function save_screen_option( $status, $option, $value ) {
		if ( 'wfacp_per_page' == $option ) {
			return $value;
		}

		return $value;
	}

	public function show_post_not_exist() {
		if ( isset( $_GET['page'] ) && 'wfacp' == $_GET['page'] && isset( $_GET['wfacp_id'] ) && $_GET['wfacp_id'] > 0 ) {


			$wfacp_id = filter_input( INPUT_GET, 'wfacp_id', FILTER_UNSAFE_RAW );
			$post     = get_post( $wfacp_id );
			if ( is_null( $post ) || $post->post_type != WFACP_Common::get_post_type_slug() ) {
				wp_die( __( 'You attempted to edit an item that doesn&#8217;t exist. Perhaps it was deleted?' ) );
			}
		}
	}

	function get_advanced_field() {
		if ( isset( $_REQUEST['post'] ) && $_REQUEST['post'] > 0 ) {
			$wfacp_id = wfacp_get_order_meta( wc_get_order( $_REQUEST['post'] ), '_wfacp_post_id' );
			if ( absint( $wfacp_id ) == 0 ) {
				return;
			}
			$cfields = WFACP_Common::get_page_custom_fields( $wfacp_id );

			if ( empty( $cfields['advanced'] ) ) {
				return;
			}
			$this->wfacp_custom_fields = $cfields['advanced'];
		}
	}

	function wfacp_protected_meta( $protected, $meta_key, $meta_type ) {
		if ( empty( $this->wfacp_custom_fields ) ) {
			return $protected;
		}
		if ( array_key_exists( $meta_key, $this->wfacp_custom_fields ) ) {
			return true;
		}

		return $protected;
	}

	public function add_pages_to_front_page_options( $pages, $args ) {
		global $pagenow;

		if ( 'options-reading.php' === $pagenow || ( 'customize.php' === $pagenow && is_array( $args ) && isset( $args['name'] ) && '_customize-dropdown-pages-page_on_front' === $args['name'] ) ) {
			$wfacp_pages = WFACP_Common::get_saved_pages();
			foreach ( $wfacp_pages as $page ) {
				$pages[] = get_post( $page['ID'] );
			}
		}

		return $pages;
	}

	public function admin_footer_text( $footer_text ) {

		if ( isset( $_GET['page'] ) && $_GET['page'] == 'wfacp' ) {
			$user = WFACP_Core()->role->user_access( 'checkout', 'read' );
			if ( false === $user ) {
				return $footer_text;
			}
			$footer_text = __( 'Over 648+ 5 star reviews show that FunnelKit users trust our top-rated support for their online business. Do you need help? <a href="https://funnelkit.com/support/?utm_source=WordPress&utm_medium=Footer+Checkout&utm_campaign=Lite+Plugin" target="_blank"><b>Contact FunnelKit Support</b></a>', 'funnel-builder' );
		}

		return $footer_text;
	}


	/**
	 * to avoid unserialize of the current class
	 */
	public function __wakeup() {
		throw new ErrorException( 'WFACP_Core can`t converted to string' );
	}

	/**
	 * to avoid serialize of the current class
	 */
	public function __sleep() {

		throw new ErrorException( 'WFACP_Core can`t converted to string' );
	}

	/**
	 * To avoid cloning of current template class
	 */
	protected function __clone() {
	}
}

WFACP_admin::get_instance();
