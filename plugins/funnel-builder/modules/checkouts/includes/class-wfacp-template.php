<?php
defined( 'ABSPATH' ) || exit;


/**
 * abstract class for all the Template Loading
 * Class WFACP_Template_Common
 */
#[AllowDynamicProperties]
abstract class WFACP_Template_Common {


	protected $selected_register_template = [];
	public $default_badges = [];
	public $web_google_fonts = [
		'Open Sans' => 'Open Sans',
	];
	public $wfacp_templates_slug = [
		'pre_built'  => [
			'layout_1' => 15,
			'layout_2' => 15,
			'layout_4' => 15,
			'layout_9' => 7,
		],
		'elementor'  => [
			'elementor_1' => 7,
			'elementor_2' => 7,
			'elementor_3' => 7,
			'elementor_4' => 7,
		],
		'embed_form' => [
			'embed_forms_2' => 7,
		],
	];

	public $device_type = 'not-mobile';
	public $enabled_product_switching = 'no';
	public $have_billing_address = false;
	public $have_shipping_address = false;
	public $have_billing_address_index = 2;
	public $have_shipping_address_index = 1;
	public $setting_new_version = false;

	protected $available_fields = [
		'layout',
		'header',
		'product',
		'guarantee',
		'listing',
		'testimonial',
		'widget',
		'customer-care',
		'promises',
		'footer',
		'style',
		'gbadge',
		'product_switcher',
		'html_widget_1',
		'html_widget_2',
		'html_widget_3',
	];
	protected $data = null;
	protected $fields = [];
	protected $template_dir = __DIR__;
	protected $template_type = 'pre_built';
	protected $template_slug = 'layout_4';
	protected $template_name = 'layout_4';
	protected $steps = [];
	protected $fieldsets = [];
	protected $checkout_fields = [];
	protected $css_classes = [];
	protected $current_step = 'single_step';
	protected $current_open_step = 'single_step';
	protected $wfacp_id = 0;
	protected $url = '';
	protected $have_coupon_field = false;
	protected $have_shipping_method = true;
	protected $form_data = [];
	protected $smart_buttons = [];
	protected $base_country = [ 'billing_country' => '', 'shipping_country' => '' ];
	private $footer_js_printed = false;
	private $address_keys = [];
	protected $place_order_btn_text = '';
	public $optional_collapsible_fields = [];

	protected function __construct() {
		$this->img_path        = WFACP_PLUGIN_URL . '/admin/assets/img/';
		$this->img_public_path = WFACP_PLUGIN_URL . '/assets/img/';
		$this->url             = WFACP_PLUGIN_URL . '/public/templates/' . $this->get_template_slug() . '/views/';

		$this->setup_data_hooks();
		$this->css_js_hooks();
		$this->checkout_fragments();
		$this->woocommerce_field_hooks();
		$this->remove_actions();
		$this->setup_smart_buttons();
		$this->address_i18_handling();
		$this->address_keys = [
			'billing_first_name'  => 'shipping_first_name',
			'billing_last_name'   => 'shipping_last_name',
			'billing_address_1'   => 'shipping_address_1',
			'billing_address_2'   => 'shipping_address_2',
			'billing_city'        => 'shipping_city',
			'billing_postcode'    => 'shipping_postcode',
			'billing_country'     => 'shipping_country',
			'billing_state'       => 'shipping_state',
			'billing_company'     => 'shipping_company',
			'billing_phone'       => 'shipping_phone',
			'shipping_phone'      => 'billing_phone',
			'shipping_first_name' => 'billing_first_name',
			'shipping_last_name'  => 'billing_last_name',
			'shipping_address_1'  => 'billing_address_1',
			'shipping_address_2'  => 'billing_address_2',
			'shipping_city'       => 'billing_city',
			'shipping_postcode'   => 'billing_postcode',
			'shipping_country'    => 'billing_country',
			'shipping_state'      => 'billing_state',
			'shipping_company'    => 'billing_company',
		];

	}

	public function get_template_slug() {
		return $this->template_slug;
	}

	private function setup_data_hooks() {

		add_action( 'wfacp_after_checkout_page_found', [ $this, 'remove_action_at_page_found' ], 100 );


		add_filter( 'wfacp_default_values', [ $this, 'pre_populate_from_get_parameter' ], 10, 3 );

		add_filter( 'wfacp_native_checkout_cart', '__return_false' );

		/** Adding the_content default filters on 'wfacp_the_content' handle */
		add_filter( 'wfacp_the_content', 'wptexturize' );
		add_filter( 'wfacp_the_content', 'convert_smilies', 20 );
		add_filter( 'wfacp_the_content', 'wpautop' );
		add_filter( 'wfacp_the_content', 'shortcode_unautop' );
		add_filter( 'wfacp_the_content', 'prepend_attachment' );
		add_filter( 'wfacp_the_content', 'do_shortcode', 11 );

		add_filter( 'wfacp_the_content', [ $GLOBALS['wp_embed'], 'run_shortcode' ], 8 );
		add_filter( 'wfacp_the_content', [ $GLOBALS['wp_embed'], 'autoembed' ], 8 );
		add_filter( 'wc_get_template', [ $this, 'remove_form_billing_and_shipping_html' ] );
		add_filter( 'wc_get_template', [ $this, 'replace_recurring_total_shipping' ], 999, 2 );
		add_action( 'wfacp_after_billing_email_field', [ $this, 'show_account_fields' ], 10, 3 );
		add_filter( 'show_admin_bar', [ $this, 'remove_admin_bar' ], 99 );
		add_action( 'wfacp_footer_before_print_scripts', [ $this, 'remove_admin_bar_print_hook' ] );
		add_filter( 'woocommerce_country_locale_field_selectors', [ $this, 'remove_add1_add2_local_field_selector' ] );


		add_filter( 'woocommerce_available_payment_gateways', [ $this, 'remove_extra_payment_gateways_in_customizer' ], 99 );

		add_filter( 'wfacp_forms_field', [ $this, 'merge_builder_data' ], 10, 2 );
		add_filter( 'wfacp_forms_field', [ $this, 'add_styling_class_to_country_field' ], 12, 2 );
		add_filter( 'wfacp_forms_field', [ $this, 'modern_label' ], 20 );

		add_action( 'wfacp_after_form', [ $this, 'remove_unused_js' ] );

		add_action( 'wfacp_before_mini_cart_html', [ $this, 'display_mini_cart_undo_message' ] );
		add_action( 'wfacp_before_order_summary', [ $this, 'display_order_summary_undo_message' ] );
		add_action( 'wfacp_before_sidebar_content', array( $this, 'collapsible_order_summary' ), 11 );
		add_action( 'wfacp_internal_css', [ $this, 'trigger_js_event_editor' ], 9 );
		add_filter( 'wpseo_frontend_presenters', [ $this, 'unset_open_graph_description_Presenter' ] );

		/*---------------------------Add field wrapper on the Advanced field--------------------*/
		add_filter( 'wfacp_form_section', [ $this, 'add_field_wrapper' ] );
		/*--------------------------add Div Wrapper for customizer-------------------------------*/
		add_action( 'wfacp_before_form', [ $this, 'element_start_before_the_form' ], 9 );
		add_action( 'wfacp_after_form', [ $this, 'element_end_after_the_form' ], 9 );

		/*-------------------------add Collapsible Optional Field-----------------------------------*/
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'collapsible_option_field_actions' ], 100 );

		/* Override WC Notices Templates */
		add_filter( 'wc_get_template', [ $this, 'override_notices_templates' ], 9999, 2 );
	}


	private function css_js_hooks() {
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_script' ], 100 );
		add_action( 'wfacp_header_print_in_head', [ $this, 'global_css' ] );
		add_action( 'wp_head', [ $this, 'add_viewport_meta' ], - 1 );
		add_action( 'wp_head', [ $this, 'no_follow_no_index' ], - 1 );
		add_action( 'wp_head', [ $this, 'add_header_script' ], 99 );
		add_action( 'wp_print_styles', [ $this, 'remove_woocommerce_js_css' ], 99 );
		add_action( 'wp_print_styles', [ $this, 'remove_theme_css_and_scripts' ], 100 );
		add_action( 'wp_footer', [ $this, 'add_footer_script' ] );
		add_action( 'wp_footer', [ $this, 'localize_locals' ] );
		add_filter( 'body_class', [ $this, 'add_body_class' ] );
		add_action( 'wfacp_outside_header', [ $this, 'handle_copy_billing_shipping_code' ] );

	}

	private function woocommerce_field_hooks() {
		add_action( 'woocommerce_before_checkout_form', [ $this, 'checkout_form_login' ] );
		add_action( 'woocommerce_before_checkout_form', [ $this, 'checkout_form_coupon' ] );

		add_filter( 'wfacp_default_field', [ $this, 'wfacp_default_field' ], 10, 2 );
		/* change text of next step*/

		add_filter( 'woocommerce_locate_template', [ $this, 'change_template_location_for_cart_shipping' ], 99998, 3 );
		add_filter( 'woocommerce_locate_template', [ $this, 'change_template_location_for_payment' ], 99999, 3 );
		add_filter( 'woocommerce_checkout_fields', [ $this, 'woocommerce_checkout_fields' ], 0 );


		add_filter( 'wfacp_checkout_fields', [ $this, 'set_priority_of_form_fields' ], 0, 2 );
		add_filter( 'wfacp_checkout_fields', [ $this, 'handling_checkout_post_data' ], 1 );
		add_filter( 'wfacp_checkout_fields', [ $this, 'correct_country_state_locals' ], 2 );

		add_filter( 'woocommerce_countries_shipping_countries', [ $this, 'woocommerce_countries_shipping_countries' ] );
		add_filter( 'woocommerce_countries_allowed_countries', [ $this, 'woocommerce_countries_allowed_countries' ] );
		// updating shipping and billing address vice-versa

		add_action( 'woocommerce_before_checkout_form', [ $this, 'reattach_necessary_hooks' ] );
		add_action( 'woocommerce_review_order_before_submit', [ $this, 'display_hide_payment_box_heading' ] );
		add_filter( 'woocommerce_available_payment_gateways', [ $this, 'change_payment_gateway_text' ] );
		add_filter( 'woocommerce_get_cart_page_permalink', [ $this, 'change_cancel_url' ], 999 );
		add_action( 'wfacp_before_breadcrumb', [ $this, 'call_before_cart_link' ] );
		add_filter( 'woocommerce_order_button_text', [ $this, 'change_place_order_button_text' ], 11 );
		add_filter( 'woocommerce_order_button_html', [ $this, 'add_class_change_place_order' ], 11 );

		add_action( 'wfacp_outside_header', [ $this, 'update_base_country' ] );

		add_filter( 'woocommerce_checkout_posted_data', [ $this, 'set_checkout_posted_data' ], - 1 );
		add_action( 'woocommerce_checkout_create_order', [ $this, 'set_address_data' ], 10, 2 );
		add_action( 'woocommerce_checkout_order_processed', [ $this, 'update_custom_fields' ], 10, 3 );
		add_action( 'woocommerce_checkout_process', [ $this, 'process_phone_field' ] );
		add_action( 'woocommerce_before_checkout_form_cart_notices', [ $this, 'display_top_notices' ], 8 );
		add_action( 'woocommerce_checkout_order_processed', [ $this, 'handle_paypal_processed' ] );
	}

	protected function address_i18_handling() {
		add_filter( 'woocommerce_get_country_locale', [ $this, 'address_i18_country' ], 99 );
		add_filter( 'woocommerce_get_country_locale_base', [ $this, 'address_i18_same_label_placeholder' ], 99 );
	}

	protected function checkout_fragments() {
		//for normal update_checkout hook
		add_filter( 'woocommerce_update_order_review_fragments', [ $this, 'add_checkout_fragments' ], 99, 2 );
	}

	public function add_checkout_fragments( $fragments ) {
		$fragments = $this->check_cart_coupons( $fragments );
		$fragments = $this->remove_order_summary_table_add_extra_data( $fragments );
		$fragments = $this->add_fragment_order_summary( $fragments );
		$fragments = $this->add_fragment_shipping_calculator( $fragments );
		$fragments = $this->add_fragment_coupon( $fragments );
		$fragments = $this->add_place_order_btn_text( $fragments );

		return $fragments;
	}

	public function update_base_country() {
		$default_customer_address               = get_option( 'woocommerce_default_customer_address' );
		$default_store_country                  = wc_format_country_state_string( get_option( 'woocommerce_default_country', '' ) )['country'];
		$this->base_country['billing_country']  = WFACP_Common::get_base_country( 'billing_country', $default_customer_address );
		$this->base_country['shipping_country'] = WFACP_Common::get_base_country( 'shipping_country', $default_customer_address );
		$this->base_country['store_country']    = $default_store_country;

	}

	final public function get_base_country() {
		return $this->base_country;
	}


	private function remove_actions() {
		remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review', 10 );
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
		remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );
	}


	public function remove_action_at_page_found() {
		remove_all_actions( 'woocommerce_review_order_after_submit' );
		remove_all_actions( 'woocommerce_review_order_before_submit' );
	}

	private function setup_smart_buttons() {
		$page_settings = WFACP_Common::get_page_settings( WFACP_Common::get_id() );
		if ( ! wc_string_to_bool( $page_settings['enable_smart_buttons'] ) ) {
			return;
		}
		$this->smart_buttons = apply_filters( 'wfacp_smart_buttons', [] );
		$position            = $page_settings['smart_button_position'];

		if ( isset( $position['id'] ) && is_string( $position['id'] ) && ! empty( $position['id'] ) ) {
			$position_id = apply_filters( 'wfacp_smart_container_display_hook', $position['id'], $this );
			add_action( $position_id, [ $this, 'display_smart_buttons' ] );
		}
	}

	public function get_template_type() {
		return $this->template_type;
	}


	public function get_step_count() {
		return 1;
	}

	public function get_current_step() {
		return $this->current_step;
	}

	public function get_template_fields_class() {
		return $this->css_classes;
	}

	public function default_css_class() {
		return [
			'input_class' => 'wfacp-form-control',
			'class'       => 'wfacp-col-full',
		];
	}


	public function remove_order_summary_table_add_extra_data( $fragments ) {
		unset( $fragments['.woocommerce-checkout-review-order-table'] );
		$fragments['.cart_total'] = WC()->cart->get_total( 'edit' );
		$extra_data               = WFACP_Common::ajax_extra_frontend_data();
		$fragments                = array_merge( $extra_data, $fragments );

		return $fragments;
	}


	public function no_follow_no_index() {
		if ( WFACP_Common::is_front_page() ) {
			return;
		}
		echo "\n <meta name='robots' content='noindex,nofollow' /> \n";
	}


	public function enqueue_script() {

		$tempType      = $this->get_template_type();
		$page_settings = WFACP_Common::get_page_settings( WFACP_Common::get_id() );

		if ( 'pre_built' === $tempType ) {
			wp_enqueue_style( 'wfacp-' . $tempType . '-style', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/css/wfacp_prebuilt_combined.min.css', false, WFACP_VERSION_DEV );
		} else {
			wp_enqueue_style( 'wfacp-' . $tempType . '-style', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/css/wfacp_combined.min.css', false, WFACP_VERSION_DEV );
		}

		wp_enqueue_script( 'jquery' );


		wp_enqueue_script( 'wc-add-to-cart-variation' );
		$js_extension = defined( 'BWF_DEV' ) ? '.js' : '.min.js';

		if ( $this->maybe_tiktok_enabled() ) {
			wp_enqueue_script( 'wfacp_checkout_hooks_js', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/js/hooks.js', [ 'jquery' ], WFACP_VERSION_DEV, false );
			wp_enqueue_script( 'wfacp_checkout_tracks_js', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/js/tracks.js', [], WFACP_VERSION_DEV, false );
			wp_localize_script( 'wfacp_checkout_hooks_js', 'wfacp_frontend', $this->get_localize_data() );
			wp_localize_script( 'wfacp_checkout_hooks_js', 'wfacp_analytics_data', $this->get_analytics_data() );
			wp_localize_script( 'wfacp_checkout_hooks_js', 'wfacp_head_track_load', array(
				'load_track_script_head' => 'yes',
				'load_hook_script_head'  => 'yes',
			) );
		}

		wp_enqueue_script( 'wfacp_checkout_js', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/js/public' . $js_extension, [ 'jquery' ], WFACP_VERSION_DEV, true );
		if ( wc_string_to_bool( $page_settings['enable_smart_buttons'] ) ) {
			wp_enqueue_script( 'wfacp-smart-buttons', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/js/smart-buttons' . $js_extension, [ 'jquery' ], WFACP_VERSION_DEV );
		}
		if ( apply_filters( 'wfacp_remove_woocommerce_style_dependency', true ) ) {
			wp_deregister_style( 'woocommerce-layout' );
			wp_deregister_style( 'woocommerce-smallscreen' );
			wp_deregister_style( 'woocommerce-general' );
		}


		if ( isset( $page_settings['enable_phone_flag'] ) && wc_string_to_bool( $page_settings['enable_phone_flag'] ) ) {

			if ( $this->get_template_type() == 'divi' ) {
				wp_enqueue_style( 'wfacp-intl-css', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/css/intlTelInput-divi.css', false, WFACP_VERSION_DEV );
			} else {
				wp_enqueue_style( 'wfacp-intl-css', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/css/intlTelInput.css', false, WFACP_VERSION_DEV );
			}

			wp_enqueue_style( 'wfacp-intl-css', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/css/intlTelInput.css', false, WFACP_VERSION_DEV );
			wp_enqueue_script( 'wfacp-intlTelInput-js', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/js/intlTelInput.min.js', [], WFACP_VERSION_DEV );

		}
	}

	public function localize_locals() {

		wp_localize_script( 'wfacp_checkout_js', 'wfacp_frontend', $this->get_localize_data() );
		wp_localize_script( 'wfacp_checkout_js', 'wfacp_analytics_data', $this->get_analytics_data() );
	}

	protected function get_localize_data() {

		$global_settings = WFACP_Common::global_settings( true );
		unset( $global_settings['wfacp_checkout_global_css'] );
		$wc_validation_fields = $this->get_wc_addr2_company_value();
		$page_settings        = WFACP_Common::get_page_settings( WFACP_Common::get_id() );

		$autopopulate_fields = 'no';
		if ( wc_string_to_bool( $page_settings['enable_autopopulate_fields'] ) && ! is_user_logged_in() ) {
			$autopopulate_fields = 'yes';
		}

		$track_facebook = 'yes';
		if ( WFACP_Common::is_theme_builder() ) {
			$track_facebook = 'no';
			if ( isset( $global_settings['wfacp_global_external_script'] ) ) {
				unset( $global_settings['wfacp_global_external_script'] );
			}
		}
		unset( $page_settings['header_script'], $page_settings['footer_script'] );

		$data = [
			'id'                              => WFACP_Common::get_id(),
			'title'                           => get_the_title( WFACP_Common::get_id() ),
			'admin_ajax'                      => admin_url( 'admin-ajax.php' ),
			'wc_endpoints'                    => WFACP_AJAX_Controller::get_public_endpoints(),
			'wfacp_nonce'                     => wp_create_nonce( 'wfacp_secure_key' ),
			'cart_total'                      => ! is_null( WC()->cart ) ? WC()->cart->get_total( 'edit' ) : 0,
			'settings'                        => $global_settings,
			'products_in_cart'                => WFACP_Core()->public->products_in_cart,
			'autopopulate'                    => apply_filters( 'wfacp_autopopulate_fields', $autopopulate_fields ),
			'is_global'                       => WFACP_Core()->public->is_checkout_override(),
			'wc_customizer_validation_status' => $wc_validation_fields,
			'cart_is_virtual'                 => WFACP_Common::is_cart_is_virtual(),
			'track_facebook'                  => $track_facebook,
			'wfacp_is_checkout_override'      => ( WFACP_Core()->public->is_checkout_override() ) ? 'yes' : 'no',
			'cancel_page_url'                 => $this->get_cancel_page_link(),
			'base_country'                    => $this->base_country,
			'applied_coupons'                 => new stdClass(),
			'enable_phone_flag'               => wc_string_to_bool( $page_settings['enable_phone_flag'] ) ? 'yes' : 'no',
			'enable_phone_validation'         => wc_string_to_bool( $page_settings['enable_phone_validation'] ) ? 'yes' : 'no',
			'intl_util_scripts'               => plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/js/utils.js',
			'process_order_loader_text'       => __( "Processing order", 'woocommerce' ),
			'is_rtl'                          => is_rtl(),
			'phone_helping_text'              => isset( $page_settings['phone_helping_text'] ) ? trim( $page_settings['phone_helping_text'] ) : '',
			'wfacp_enable_live_validation'    => isset( $page_settings['enable_live_validation'] ) ? trim( $page_settings['enable_live_validation'] ) : "false",

		];

		return apply_filters( 'wfacp_template_localize_data', $data, $this );
	}

	public function get_cancel_page_link() {
		$current_page_url = get_the_permalink();

		$params = [
			'wfacp_is_checkout_override' => ( WFACP_Core()->public->is_checkout_override() ) ? 'yes' : 'no',
			'wfacp_id'                   => WFACP_Common::get_id(),
			'wfacp_canceled'             => 'yes'
		];

		$params = apply_filters( 'wfacp_cancel_url_arguments', $params, $this );
		$url    = add_query_arg( $params, $current_page_url );

		return apply_filters( 'cancel_page_url', $url, $params, $this );
	}


	protected function get_analytics_data() {
		$final    = [];
		$services = WFACP_Analytics::get_available_service();
		foreach ( $services as $service => $analytic ) {
			/**
			 * @var $analytic WFACP_Analytics;
			 */
			$final[ $service ] = $analytic->get_prepare_data();
		}
		$do_tracking = true;
		if ( is_checkout_pay_page() ) {
			$do_tracking = false;
		}
		$final['shouldRender'] = apply_filters( 'wfacp_do_tracking', $do_tracking );

		$final['conversion_api'] = 'false';
		$admin_general           = BWF_Admin_General_Settings::get_instance();
		$is_conversion_api       = $admin_general->get_option( 'is_fb_purchase_conversion_api' );
		if ( is_array( $is_conversion_api ) && count( $is_conversion_api ) > 0 && 'yes' === $is_conversion_api[0] && ! empty( $admin_general->get_option( 'conversion_api_access_token' ) ) ) {
			$final['conversion_api'] = 'true';
		}

		$final['fb_advanced']     = WFACP_Common::pixel_advanced_matching_data();
		$final['tiktok_advanced'] = WFACP_Common::tiktok_advanced_matching_data();

		return $final;
	}

	public function remove_woocommerce_js_css() {
		if ( WFACP_Common::is_theme_builder() ) {
			global $wp_scripts;

			$registered_script = $wp_scripts->registered;
			if ( ! empty( $registered_script ) ) {
				foreach ( $registered_script as $handle => $data ) {
					if ( false !== strpos( $data->src, '/plugins/woocommerce/' ) ) {
						unset( $wp_scripts->registered[ $handle ] );
						wp_dequeue_script( $handle );
					}
				}
			}
		}
	}

	public function remove_theme_css_and_scripts() {

		if ( false == apply_filters( 'wfacp_remove_theme_js_css_files', true, $this ) ) {
			return;
		}
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
		if ( empty( $paths ) || empty($url)) {
			return false;
		}
		foreach ( $paths as $path ) {
			if ( false !== strpos( $url, $path ) && true == apply_filters( 'wfacp_css_js_deque', true, $path, $url, $this ) ) {
				return true;

			}
		}

		return false;

	}

	public function get_theme_css_path() {
		$paths = [
			'/themes/',
			'/cache/',
			'cart-fragments.min.js',
			'cart-fragments.js',
			'carthopper',
			'/woo-advance-search/',
			'/block-library/',
			'/woo-gutenberg-products-block/',
			'/woocommerce-blocks/',
			'checkout-persistence-form-data' //Astra addon theme need to remove because of this js Make Our Field empty
		];

		$template_type = $this->get_template_type();

		if ( 'pre_built' == $template_type ) {
			$plugins = [
				'revslider',
				'testimonial-slider-and-showcase',
				'woocommerce-product-addons',
				'contact-form-7',
				'wp-upg',
				'bonanza-',
				'affiliate-wp',
				'woofunnels-autobot',
				'woocommerce-quick-buy',
				'wp-admin/js/password-strength-meter.min.js',
				'woocommerce-product-bundles',
				'/fusion-styles/',
				'cart-fragments.min.js',
				'cart-fragments.js',
				'/uploads/oceanwp/main-style.css',
				'/uploads/dynamic_avia/',
				'/uploads/porto_styles/',
				'um-styles.css',
				'/fifu-premium/',
				'/uploads/bb-theme/',
				'/uploads/wp-less/pillar/style/css/',
				'/td-composer/legacy/common/wp_booster/js_dev'
			];
			$paths   = array_merge( $paths, $plugins );
		}

		return apply_filters( 'wfacp_css_js_removal_paths', $paths, $this );
	}

	public function add_header_script() {

		$settings = WFACP_Common::get_page_settings( WFACP_Common::get_id() );

		if ( isset( $settings['header_script'] ) && '' != $settings['header_script'] ) {
			echo sprintf( "\n \n %s \n \n", $settings['header_script'] );
		}
	}

	public function add_footer_script() {

		$settings = WFACP_Common::get_page_settings( WFACP_Common::get_id() );

		if ( false == $this->footer_js_printed && isset( $settings['footer_script'] ) && '' != $settings['footer_script'] ) {
			$this->footer_js_printed = true;
			echo sprintf( "\n \n %s \n\n", $settings['footer_script'] );
		}

		$_wfacp_global_settings = get_option( '_wfacp_global_settings' );

		if ( isset( $_wfacp_global_settings['wfacp_global_external_script'] ) && $_wfacp_global_settings['wfacp_global_external_script'] != '' ) {
			$global_script = $_wfacp_global_settings['wfacp_global_external_script'];
			echo $global_script;
		}

	}

	public function checkout_form_login() {
		if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
			return;
		}
		include WFACP_TEMPLATE_COMMON . '/checkout/form-login.php';
	}

	public function checkout_form_coupon() {
		$settings          = WFACP_Common::get_page_settings( WFACP_Common::get_id() );
		$is_disable_coupon = ( isset( $settings['disable_coupon'] ) && 'true' == $settings['disable_coupon'] );

		if ( ! $is_disable_coupon ) {
			include WFACP_TEMPLATE_COMMON . '/checkout/form-coupon.php';
		}
	}

	public function wfacp_default_field( $default, $index ) {
		if ( isset( $this->css_classes[ $index ] ) ) {
			return $this->css_classes[ $index ]['class'];
		} else {

			return 'wfacp-col-full';
		}
	}


	public function add_fragment_order_summary( $fragments ) {

		ob_start();
		include WFACP_TEMPLATE_COMMON . '/order-summary.php';
		$order_summary                     = ob_get_clean();
		$fragments['.wfacp_order_summary'] = $order_summary;

		return $fragments;
	}

	public function add_fragment_collapsible_order_summary( $fragments ) {
		$path = WFACP_PLUGIN_DIR . '/public/global/collapsible-order-summary/';
		ob_start();
		include $path . '/order-review.php';
		$fragments['.wfacp_mb_mini_cart_sec_accordion_content .wfacp_template_9_cart_item_details'] = ob_get_clean();

		ob_start();
		include $path . '/order-total.php';
		$fragments['.wfacp_mb_mini_cart_sec_accordion_content .wfacp_template_9_cart_total_details'] = ob_get_clean();

		ob_start();
		wc_cart_totals_order_total_html();
		$fragments['.wfacp_cart_mb_fragment_price'] = ob_get_clean();

		$order_summary_cart_price            = apply_filters( 'wfacp_collapsible_order_summary_cart_price', wc_price( WC()->cart->total ) );
		$fragments['.wfacp_show_price_wrap'] = '<div class="wfacp_show_price_wrap">' . do_action( "wfacp_before_mini_price" ) . '<strong>' . $order_summary_cart_price . '</strong>' . do_action( 'wfacp_after_mini_price' ) . '</div>';


		return $fragments;
	}

	public function add_fragment_shipping_calculator( $fragments ) {
		if ( isset( $this->checkout_fields['advanced']['shipping_calculator'] ) ) {
			ob_start();
			include WFACP_TEMPLATE_COMMON . '/shipping-options.php';
			$order_shipping_calc                  = ob_get_clean();
			$fragments['.wfacp_shipping_options'] = $order_shipping_calc;
		}

		return $fragments;
	}


	public function change_template_location_for_cart_shipping( $template, $template_name, $template_path ) {
		if ( 'cart/cart-shipping.php' === $template_name ) {
			$template = WFACP_TEMPLATE_COMMON . '/shipping-options-form.php';
		}

		return $template;
	}


	public function change_template_location_for_payment( $template, $template_name, $template_path ) {
		if ( 'checkout/payment.php' === $template_name ) {
			if ( apply_filters( 'wfacp_replace_payment_box_template', true, $template, $template_name, $template_path ) ) {
				$template = WFACP_TEMPLATE_COMMON . '/checkout/payment.php';
			}
		}

		return $template;
	}

	/**
	 * @param $fields
	 *
	 * @return array
	 * @since 1.0
	 */
	public function woocommerce_checkout_fields( $fields ) {

		$template_fields = $this->get_checkout_fields();
		if ( isset( $fields['account'] ) ) {
			$template_fields['account'] = $fields['account'];
		}

		$template_fields = apply_filters( 'wfacp_checkout_fields', $template_fields, $fields );
		$is_billing_only = wc_ship_to_billing_address_only();
		if ( true == $is_billing_only && ! isset( $template_fields['shipping'] ) ) {
			$template_fields['shipping'] = $fields['shipping'];

		}
		//PHP8.0 when Shipping Address Needed in woocommerce & customer removed shipping address field then Fatal error Occured
		if ( empty( $template_fields['shipping'] ) ) {
			$template_fields['shipping'] = [];
		}
		if ( empty( $template_fields['order'] ) ) {
			$template_fields['order'] = [];
		}
		if ( empty( $template_fields['account'] ) ) {
			$template_fields['account'] = [];
		}


		return $template_fields;
	}

	public function get_checkout_fields() {
		return apply_filters( 'wfacp_get_checkout_fields', $this->checkout_fields );
	}


	public function set_priority_of_form_fields( $template_fields, $fields ) {

		foreach ( $template_fields as $type => $sections ) {
			if ( empty( $sections ) ) {
				continue;
			}
			foreach ( $sections as $key => $field ) {
				$template_fields[ $type ][ $key ]['priority'] = 0;
				if ( isset( $field['type'] ) && ( 'wfacp_wysiwyg' == $field['type'] || 'hidden' == $field['type'] ) && isset( $field['required'] ) ) {
					unset( $template_fields[ $type ][ $key ]['required'] );
				}
			}
		}

		return $template_fields;
	}

	/**
	 * Handle first and last name of shipping and billing field
	 *
	 * @param $template_fields
	 *
	 * @return array
	 *
	 * @since 1.6.0
	 */
	public function handling_checkout_post_data( $template_fields ) {
		if ( isset( $_POST['ship_to_different_address'] ) ) {
			add_filter( 'woocommerce_cart_needs_shipping_address', [ $this, 'enable_need_shipping' ] );
		}

		if ( isset( $_POST['ship_to_different_address'] ) && isset( $_POST['wfacp_billing_same_as_shipping'] ) && $_POST['wfacp_billing_same_as_shipping'] == 0 ) {
			$address_fields = [ 'first_name', 'last_name', 'company', 'address_1', 'address_2', 'city', 'postcode', 'country', 'state' ];
			foreach ( $address_fields as $key ) {
				$b_key = 'billing_' . $key;
				if ( isset( $template_fields['billing'][ $b_key ] ) && in_array( $b_key, [
						'billing_first_name',
						'billing_last_name',
					] ) && ! isset( $template_fields['billing'][ $b_key ]['address_group'] ) ) {

					continue;
				}

				if ( 'billing' == $this->get_shipping_billing_index() && isset( $template_fields['billing'][ $b_key ]['required'] ) ) {
					unset( $template_fields['billing'][ $b_key ]['required'] );
				}
				if ( $key == 'postcode' ) {
					unset( $template_fields['billing'][ $b_key ]['validate'] );
				}
			}
		}

		/**
		 * When billing address not present in form then we assign shipping field values to billing fields values
		 */
		if ( isset( $_POST['_wfacp_post_id'] ) && ! wc_string_to_bool( $this->have_billing_address ) && wc_string_to_bool( $this->have_shipping_address ) ) {

			$available_fields   = [ 'company', 'address_2', 'country', 'city', 'state', 'postcode', 'address_1', 'phone' ];
			$billing_first_name = false;
			$billing_last_name  = false;
			if ( ! isset( $_POST['billing_first_name'] ) ) {
				$available_fields[] = 'first_name';

			} else {
				$billing_first_name = true;
			}

			if ( ! isset( $_POST['billing_last_name'] ) ) {
				$available_fields[] = 'last_name';

			} else {
				$billing_last_name = true;
			}

			foreach ( $available_fields as $key ) {
				$b_key = 'billing_' . $key;
				$s_key = 'shipping_' . $key;
				if ( isset( $template_fields['shipping'][ $s_key ] ) ) {
					$template_fields['billing'][ $b_key ]       = $template_fields['shipping'][ $s_key ];
					$template_fields['billing'][ $b_key ]['id'] = $b_key;
					if ( isset( $template_fields['billing'][ $b_key ]['required'] ) ) {
						unset( $template_fields['billing'][ $b_key ]['required'] );
					}

					$s_key_data         = filter_input( INPUT_POST, $s_key, FILTER_UNSAFE_RAW );
					$s_key_data         = wc_clean( $s_key_data );
					$_POST[ $b_key ]    = $s_key_data;
					$_REQUEST[ $b_key ] = $s_key_data;
				}
			}

			if ( ! isset( $template_fields['shipping']['shipping_first_name'] ) && true == $billing_first_name ) {
				$template_fields['shipping']['shipping_first_name']       = $template_fields['billing']['billing_first_name'];
				$template_fields['shipping']['shipping_first_name']['id'] = 'shipping_first_name';
				if ( isset( $template_fields['shipping']['shipping_first_name']['required'] ) ) {
					unset( $template_fields['shipping']['shipping_first_name']['required'] );
				}
				$_POST['shipping_first_name']    = wc_clean( $_POST['billing_first_name'] );
				$_REQUEST['shipping_first_name'] = wc_clean( $_POST['billing_first_name'] );
			}

			if ( ! isset( $template_fields['shipping']['shipping_last_name'] ) && true == $billing_last_name ) {
				$template_fields['shipping']['shipping_last_name'] = $template_fields['billing']['billing_last_name'];
				if ( isset( $template_fields['shipping']['shipping_last_name']['required'] ) ) {
					unset( $template_fields['shipping']['shipping_last_name']['required'] );
				}
				$template_fields['shipping']['shipping_last_name']['id'] = 'shipping_last_name';
				$_POST['shipping_last_name']                             = wc_clean( $_POST['billing_last_name'] );
				$_REQUEST['shipping_last_name']                          = wc_clean( $_POST['billing_last_name'] );
			}
		}

		return $template_fields;
	}

	public function enable_need_shipping() {
		return true;
	}

	public function display_top_notices() {

		if ( ! apply_filters( 'wfacp_display_top_notices', true ) ) {
			return;
		}
		$all_notices  = WC()->session->get( 'wc_notices', array() );
		$notice_types = apply_filters( 'woocommerce_notice_types', array( 'error', 'success', 'notice' ) );
		if ( empty( $notice_types ) || empty( $all_notices ) ) {
			return;
		}
		$notices = array();
		foreach ( $notice_types as $notice_type ) {
			if ( wc_notice_count( $notice_type ) > 0 ) {
				$notices[ $notice_type ] = $all_notices[ $notice_type ];
			}
		}
		$type_class_mapping = array(
			'error'   => 'wfacp-notice-error',
			'notice'  => 'wfacp-notice-info',
			'success' => 'wfacp-notice-success',
		);

		if ( empty( $notices ) ) {
			return;
		}
		wc_clear_notices();
		?>
        <div class="wfacp-notices-wrapper">
			<?php
			foreach ( $notices as $type => $messages ) :
				foreach ( $messages as $message ) :
					// In WooCommerce 3.9+, messages can be an array with two properties:
					// - notice
					// - data
					$message = isset( $message['notice'] ) ? $message['notice'] : $message;
					if ( empty( $message ) || false !== strpos( $message, 'wc-forward' ) ) {
						continue;
					}

					$class = '';
					if ( false !== strpos( $message, 'Coupon code applied' ) ) {
						$class = 'wfacp_coupon_applied';
					}
					?>
                    <div class="<?php echo $class; ?> wfacp-notice-wrap <?php echo $type_class_mapping[ $type ]; ?>">
                        <div class="wfacp-message wfacp-<?php echo $type ?>"><?php echo $message; ?></div>
                    </div>
				<?php
				endforeach;
			endforeach;
			?>
        </div>
		<?php

	}

	public function add_fragment_coupon( $fragments ) {
		if ( isset( $this->checkout_fields['advanced']['order_coupon'] ) ) {
			$messages        = '';
			$success_message = $this->checkout_fields['advanced']['order_coupon']['coupon_success_message_heading'];
			ob_start();
			foreach ( WC()->cart->get_coupons() as $code => $coupon ) {
				$parse_message = WFACP_Product_Switcher_Merge_Tags::parse_coupon_merge_tag( $success_message, $coupon );
				$remove_link   = sprintf( "<a href='javascript:void(0)' class='wfacp_remove_coupon' data-coupon='%s'>%s</a>", $code, __( 'Remove', 'funnel-builder' ) );
				$messages      .= sprintf( '<div class="wfacp_single_coupon_msg">%s %s</div>', $parse_message, $remove_link );
			}

			$fragments['.wfacp_coupon_field_msg'] = '<div class="wfacp_coupon_field_msg">' . $messages . '</div>';

		}

		return $fragments;
	}

	/**
	 * Update Address Field Vice versa
	 *
	 * @param $posted_data
	 *
	 * @return mixed
	 */

	public function set_checkout_posted_data( $posted_data ) {

		if ( isset( $_REQUEST['wfacp_source'] ) ) {
			$wfacp_source = wc_clean( $_REQUEST['wfacp_source'] );
			if ( filter_var( $wfacp_source, FILTER_VALIDATE_URL ) ) {
				$posted_data['wfacp_source'] = $wfacp_source;
			}
		}
		if ( isset( $_REQUEST['_wfacp_post_id'] ) ) {
			$posted_data['wfacp_post_id'] = wc_clean( $_REQUEST['_wfacp_post_id'] );
		}


		$this->address_keys = apply_filters( 'wfacp_update_posted_data_vice_versa_keys', $this->address_keys, $posted_data, $this );
		if ( ! empty( $this->address_keys ) ) {
			$index          = $this->get_shipping_billing_index();
			$address_fields = [ 'company', 'address_1', 'address_2', 'city', 'postcode', 'country', 'state' ];


			// copy all data from billing to shipping
			if ( $this->have_billing_address() && $this->have_shipping_address() && $index == 'shipping' && ! isset( $_POST['ship_to_different_address'] ) ) {
				foreach ( $address_fields as $field ) {
					$_REQUEST[ 'shipping_' . $field ] = '';
				}
			}

			if ( isset( $_REQUEST['billing_country'] ) && 'default' == trim( $_REQUEST['billing_country'] ) ) {
				$_REQUEST['billing_country'] = '';
			}
			if ( isset( $_REQUEST['shipping_country'] ) && 'default' == trim( $_REQUEST['shipping_country'] ) ) {
				$_REQUEST['shipping_country'] = '';
			}


			// Street Address 2 Condition check #2046
			if ( $this->have_billing_address() && $this->have_shipping_address() ) {
				// Billing is optional address and Client click on Different Billing Address then all billing data from array
				$fields           = $this->get_checkout_fields();
				$same_as_billing  = filter_input( INPUT_POST, 'shipping_same_as_billing', FILTER_UNSAFE_RAW );
				$same_as_shipping = filter_input( INPUT_POST, 'billing_same_as_shipping', FILTER_UNSAFE_RAW );

				if ( ! is_null( $same_as_billing ) || ! is_null( $same_as_shipping ) || ( is_null( $same_as_billing ) && is_null( $same_as_shipping ) ) ) {
					//Both Address Present in page but not using as a optional field then we make array empty
					unset( $this->address_keys['billing_company'] );
					unset( $this->address_keys['billing_address_1'] );
					unset( $this->address_keys['billing_address_2'] );
					unset( $this->address_keys['billing_city'] );
					unset( $this->address_keys['billing_postcode'] );
					unset( $this->address_keys['billing_country'] );
					unset( $this->address_keys['billing_state'] );
					unset( $this->address_keys['shipping_address_1'] );
					unset( $this->address_keys['shipping_address_2'] );
					unset( $this->address_keys['shipping_city'] );
					unset( $this->address_keys['shipping_postcode'] );
					unset( $this->address_keys['shipping_country'] );
					unset( $this->address_keys['shipping_state'] );
					unset( $this->address_keys['shipping_company'] );


					if ( isset( $fields['billing'] ) && isset( $fields['billing']['billing_first_name'] ) ) {
						unset( $this->address_keys['billing_first_name'] );
					}
					if ( isset( $fields['billing'] ) && isset( $fields['billing']['billing_last_name'] ) ) {
						unset( $this->address_keys['billing_last_name'] );
					}
					if ( isset( $fields['shipping'] ) && isset( $fields['shipping']['shipping_first_name'] ) ) {
						unset( $this->address_keys['shipping_first_name'] );
					}
					if ( isset( $fields['shipping'] ) && isset( $fields['shipping']['shipping_last_name'] ) ) {
						unset( $this->address_keys['shipping_last_name'] );
					}
					if ( isset( $fields['billing'] ) && isset( $fields['billing']['billing_phone'] ) ) {
						unset( $this->address_keys['billing_phone'] );
					}
					if ( isset( $fields['shipping'] ) && isset( $fields['shipping']['shipping_phone'] ) ) {
						unset( $this->address_keys['shipping_phone'] );
					}
				}
			}

			if ( empty( $this->address_keys ) ) {
				return $posted_data;
			}

			foreach ( $this->address_keys as $first_key => $second_key ) {
				$input = '';
				if ( ( ! isset( $_REQUEST[ $first_key ] ) || empty( $_REQUEST[ $first_key ] ) ) && ( isset( $_REQUEST[ $second_key ] ) && ! empty( $_REQUEST[ $second_key ] ) ) ) {
					//Do not sanitize array field
					if ( is_array( $_REQUEST[ $second_key ] ) ) {
						$input = $_REQUEST[ $second_key ];
					} else {
						$input = wc_clean( wp_unslash( $_REQUEST[ $second_key ] ) );
					}
				} elseif ( isset( $_REQUEST[ $first_key ] ) && empty( $_REQUEST[ $first_key ] ) && ( isset( $_REQUEST[ $second_key ] ) && ! empty( $_REQUEST[ $second_key ] ) ) ) {
					//Do not sanitize array field
					if ( is_array( $_REQUEST[ $second_key ] ) ) {
						$input = $_REQUEST[ $second_key ];
					} else {
						$input = wc_clean( wp_unslash( $_REQUEST[ $second_key ] ) );
					}
				} elseif ( isset( $_REQUEST[ $first_key ] ) && ! empty( $_REQUEST[ $first_key ] ) ) {
					//Do not sanitize array field
					if ( is_array( $_REQUEST[ $first_key ] ) ) {
						$input = $_REQUEST[ $first_key ];
					} else {
						$input = wc_clean( wp_unslash( $_REQUEST[ $first_key ] ) );
					}
				}
				if ( ! empty( $input ) ) {
					$posted_data[ $first_key ] = $input;
				}
			}
		}
		if ( ! wc_shipping_enabled() || WFACP_Common::is_cart_is_virtual() ) {


			$shipping_keys = [
				'shipping_first_name',
				'shipping_last_name',
				'shipping_address_1',
				'shipping_address_2',
				'shipping_city',
				'shipping_postcode',
				'shipping_country',
				'shipping_state'
			];

			/**
			 * state and country not save blank if upsell exists
			 */
			if ( class_exists( 'WFOCU_Core' ) ) {
				unset( $shipping_keys[6] );
				unset( $shipping_keys[7] );
			}

			$shipping_keys = apply_filters( 'wfacp_unset_vice_versa_keys_shipping_keys', $shipping_keys, $this );
			foreach ( $shipping_keys as $shipping_key ) {
				if ( isset( $posted_data[ $shipping_key ] ) ) {
					unset( $posted_data[ $shipping_key ] );
					unset( $this->address_keys[ $shipping_key ] );
				}
			}
		}

		return $posted_data;
	}


	public function set_address_data( $order, $posted_data ) {
		if ( ! $order instanceof WC_Order ) {
			return;
		}
		$fields_prefix   = array(
			'shipping' => true,
			'billing'  => true,
		);
		$shipping_fields = array(
			'shipping_method' => true,
			'shipping_total'  => true,
			'shipping_tax'    => true,
		);


		foreach ( $this->address_keys as $key => $value ) {
			if ( ! isset( $posted_data[ $key ] ) ) {
				continue;
			}
			$value = $posted_data[ $key ];
			if ( is_callable( array( $order, "set_{$key}" ) ) ) {
				$order->{"set_{$key}"}( $value );
				// Store custom fields prefixed with wither shipping_ or billing_. This is for backwards compatibility with 2.6.x.
			} elseif ( isset( $fields_prefix[ current( explode( '_', $key ) ) ] ) ) {
				if ( ! isset( $shipping_fields[ $key ] ) ) {
					$order->update_meta_data( '_' . $key, $value );
				}
			}

		}
	}

	public function process_phone_field() {
		if ( ! isset( $_POST['wfacp_input_phone_field'] ) ) {
			return;
		}
		$page_settings = WFACP_Common::get_page_settings( WFACP_Common::get_id() );
		if ( ! wc_string_to_bool( $page_settings['enable_phone_flag'] ) ) {
			return;
		}

		$data       = $_POST['wfacp_input_phone_field'];
		$data       = stripslashes_deep( $data );
		$phone_data = json_decode( $data, true );
		if ( ! is_array( $phone_data ) ) {
			return;
		}
		if ( wc_string_to_bool( $page_settings['enable_phone_validation'] ) ) {
			if ( isset( $_POST['billing_phone'] ) && ! empty( $_POST['billing_phone'] ) && isset( $phone_data['billing']['number'] ) && empty( $phone_data['billing']['number'] ) && 'no' == $phone_data['billing']['hidden'] ) {
				wc_add_notice( sprintf( __( '%s is not a valid phone number.', 'woocommerce' ), $this->checkout_fields['billing']['billing_phone']['label'] ), 'error' );
			}
			if ( isset( $_POST['shipping_phone'] ) && ! empty( $_POST['shipping_phone'] ) && isset( $phone_data['shipping']['number'] ) && empty( $phone_data['shipping']['number'] ) && 'no' == $phone_data['shipping']['hidden'] ) {
				wc_add_notice( sprintf( __( '%s is not a valid phone number.', 'woocommerce' ), $this->checkout_fields['shipping']['shipping_phone']['label'] ), 'error' );
			}
		}


		$with_country_code = apply_filters( 'wfacp_intl_phone_code', wc_string_to_bool( $page_settings['save_phone_number_type'] ), $_POST );
		if ( false === $with_country_code ) {
			return;
		}


		/*--------------------------------Intl Code Handling--------------------------------------*/
		if ( isset( $_POST['billing_phone'] ) && ! empty( $_POST['billing_phone'] ) && ! empty( $phone_data['billing']['number'] ) ) {
			$code                      = '+' . $phone_data['billing']['code'];
			$_POST['billing_phone']    = $code . $phone_data['billing']['number'];
			$_REQUEST['billing_phone'] = $code . $phone_data['billing']['number'];
		}
		if ( isset( $_POST['shipping_phone'] ) && ! empty( $_POST['shipping_phone'] ) && ! empty( $phone_data['shipping']['number'] ) ) {
			$code                       = '+' . $phone_data['shipping']['code'];
			$_POST['shipping_phone']    = $code . $phone_data['shipping']['number'];
			$_REQUEST['shipping_phone'] = $code . $phone_data['shipping']['number'];
		}
	}

	public function update_custom_fields( $order_id, $posted_data, $order ) {
		WFACP_Common::update_aero_custom_fields( $order, $posted_data, true );

	}

	/**
	 * Return shipping or billing
	 * get which address field is hidden in form Shipping or billing
	 * @return string
	 */
	public function get_shipping_billing_index() {

		if ( $this->have_shipping_address && $this->have_billing_address ) {
			$have_billing_address_index  = absint( $this->have_billing_address_index );
			$have_shipping_address_index = absint( $this->have_shipping_address_index );
			if ( $have_billing_address_index < $have_shipping_address_index ) {
				return 'shipping';
			} else {
				return 'billing';
			}
		}

		return '';
	}

	/**
	 * @param $template_fields
	 *
	 * @return mixed
	 * @since 1.6.0
	 */
	public function correct_country_state_locals( $template_fields ) {
		$checkout = WC()->checkout();
		if ( ! $checkout instanceof WC_Checkout ) {
			return $template_fields;
		}
		// check for billing country locale values
		if ( '' !== $checkout->get_value( 'billing_country' ) ) {
			$locale  = WC()->countries->get_country_locale();
			$country = $checkout->get_value( 'billing_country' );
			if ( isset( $locale[ $country ] ) && isset( $template_fields['billing'] ) ) {

				$array_without_key = [];
				foreach ( $template_fields['billing'] as $key => $value ) {
					$array_without_key[ str_replace( 'billing_', '', $key ) ] = $value;
				}
				$get_filtered_array = wc_array_overlay( $array_without_key, $locale[ $country ] );
				foreach ( $template_fields['billing'] as $key => $value ) {
					$truncated_key = str_replace( 'billing_', '', $key );
					if ( isset( $get_filtered_array[ $truncated_key ] ) ) {
						$template_fields['billing'][ $key ] = $get_filtered_array[ $truncated_key ];
					}
				}
			}
		}

		// check for shipping country locale values
		if ( '' !== $checkout->get_value( 'shipping_country' ) ) {
			$locale  = WC()->countries->get_country_locale();
			$country = $checkout->get_value( 'shipping_country' );

			if ( isset( $locale[ $country ] ) && isset( $template_fields['shipping'] ) ) {

				$array_without_key = [];
				foreach ( $template_fields['shipping'] as $key => $value ) {
					$array_without_key[ str_replace( 'shipping_', '', $key ) ] = $value;
				}
				$get_filtered_array = wc_array_overlay( $array_without_key, $locale[ $country ] );
				foreach ( $template_fields['shipping'] as $key => $value ) {
					$truncated_key = str_replace( 'shipping_', '', $key );
					if ( isset( $get_filtered_array[ $truncated_key ] ) ) {
						$template_fields['shipping'][ $key ] = $get_filtered_array[ $truncated_key ];
					}
				}
			}
		}

		return $template_fields;
	}

	public function get_google_webfonts() {
		$url    = 'https://www.googleapis.com/webfonts/v1/webfonts?key=key_here&&sort=alpha';
		$raw    = file_get_contents( $url, 0, null, null );
		$result = json_decode( $raw );

		$font_list = array();
		foreach ( $result->items as $font ) {
			$font_list[] .= $font->family;
		}

	}


	public function get_view( $template ) {
		extract( array( 'data' => $this->get_data() ) );
		do_action( 'wfacp_before_template_load' );
		include $this->get_template_url( $template );
		do_action( 'wfacp_after_template_load' );
		exit;
	}

	public function get_template_url( $template = '' ) {
		return $this->template_dir . '/views/view.php';
	}


	public function get_slug() {
		return $this->template_slug;
	}

	public function get_url() {
		return $this->url;
	}

	public function get_wfacp_id() {
		return $this->wfacp_id;
	}

	public function set_wfacp_id( $wfacp_id = false ) {
		if ( false !== $wfacp_id ) {
			$this->wfacp_id = $wfacp_id;
		}
	}

	public function get_fieldsets() {
		return apply_filters( 'wfacp_get_fieldsets', $this->fieldsets );
	}

	public function get_fields() {
		return $this->fields;
	}

	final public function set_data( $data = false ) {
		$data = WFACP_Common::get_fieldset_data( WFACP_Common::get_id() );

		foreach ( $data as $key => $val ) {
			$this->{$key} = $val;
		}
		$this->have_billing_address  = wc_string_to_bool( $data['have_billing_address'] );
		$this->have_shipping_address = wc_string_to_bool( $data['have_shipping_address'] );
		$this->have_coupon_field     = isset( $data['have_coupon_field'] ) ? wc_string_to_bool( $data['have_coupon_field'] ) : $this->have_coupon_field;
		$this->have_shipping_method  = isset( $data['have_shipping_method'] ) ? wc_string_to_bool( $data['have_shipping_method'] ) : $this->have_shipping_method;
		$this->checkout_fields       = WFACP_Common::get_checkout_fields( WFACP_Common::get_id() );

	}


	public function wfacp_get_header() {
		return $this->template_dir . '/views/template-parts/header.php';
	}

	public function wfacp_get_footer() {
		return $this->template_dir . '/views/template-parts/footer.php';
	}


	public function have_shipping_address() {

		return $this->have_shipping_address;
	}

	public function have_billing_address() {

		return $this->have_billing_address;
	}


	final public function wfacp_get_form() {

		$template = WFACP_TEMPLATE_COMMON . '/form.php';
		$temp     = apply_filters( 'wfacp_form_template', $template );

		if ( ! empty( $temp ) ) {
			$template = $temp;
		}

		return $template;
	}


	final public function get_payment_box() {
		do_action( 'wfacp_before_payment_section' );
		include WFACP_TEMPLATE_COMMON . '/payment.php';
		do_action( 'wfacp_after_payment_section' );
	}


	/**
	 * Prepopulate field data From URL
	 * if data not present in URL then we check default data and populate the data
	 *
	 * @param $value
	 * @param $key
	 * @param $field
	 *
	 * @return mixed|string
	 */
	public function pre_populate_from_get_parameter( $value, $key, $field ) {

		if ( '' == $key ) {
			return $value;
		}


		if ( isset( $_REQUEST[ $key ] ) ) {
			$key_data  = filter_input( INPUT_GET, $key, FILTER_UNSAFE_RAW );
			$new_value = urldecode( $key_data );
		} else if ( isset( $field['default'] ) && '' !== $field['default'] ) {
			$new_value = $field['default'];
		} elseif ( isset( $field['type'] ) && 'select' == $field['type'] && ! empty( $field['options'] ) && ! isset( $field['org_type'] ) ) {
			$options   = array_keys( $field['options'] );
			$new_value = $options[0];
		} else {
			$new_value = $value;
		}

		$value = apply_filters( 'wfacp_populate_default_value', $new_value, $value, $field, $this );

		return $value;
	}


	public function remove_form_billing_and_shipping_html( $template ) {

		if ( in_array( $template, [ 'checkout/form-billing.php', 'checkout/form-billing.php', 'cart/shipping-calculator.php' ] ) ) {
			return WFACP_TEMPLATE_DIR . '/empty.php';
		}

		return $template;

	}

	public function replace_recurring_total_shipping( $template, $template_name ) {

		if ( function_exists( 'wcs_cart_totals_subtotal_html' ) && in_array( $template_name, [ 'checkout/recurring-totals.php' ] ) ) {
			return WFACP_TEMPLATE_COMMON . '/checkout/recurring-totals.php';
		}

		return $template;
	}

	public function remove_admin_bar( $status ) {
		if ( WFACP_Common::is_customizer() ) {
			return false;
		}

		return $status;
	}


	public function show_account_fields( $key, $field, $dvalue ) {
		include WFACP_TEMPLATE_COMMON . '/account.php';
	}

	public function woocommerce_countries_shipping_countries( $countries ) {
		if ( is_array( $countries ) && count( $countries ) == 0 ) {
			$countries = WC()->countries->get_countries();
		}

		return $countries;
	}

	public function woocommerce_countries_allowed_countries( $countries ) {
		if ( is_array( $countries ) && count( $countries ) == 0 ) {
			$countries = WC()->countries->get_countries();
		}

		return $countries;
	}

	public function remove_add1_add2_local_field_selector( $locale_fields ) {
		if ( isset( $locale_fields['address_1'] ) ) {
			unset( $locale_fields['address_1'] );
		}
		if ( isset( $locale_fields['address_2'] ) ) {
			unset( $locale_fields['address_2'] );
		}

		return $locale_fields;
	}

	public function add_viewport_meta() {
		include WFACP_TEMPLATE_COMMON . '/meta.php';
	}

	public function reattach_necessary_hooks() {
		if ( ! has_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment' ) ) {
			add_action( 'woocommerce_checkout_order_review', 'woocommerce_checkout_payment' );
		}

		if ( has_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review' ) ) {
			remove_action( 'woocommerce_checkout_order_review', 'woocommerce_order_review' );
		}
	}


	public function display_hide_payment_box_heading() {

		if ( ! WC()->cart->needs_payment() ) {
			?>
            <style>
                #wfacp_checkout_form .wfacp_payment .wfacp-comm-title {
                    display: none;
                }
            </style>
			<?Php
		}

	}


	/**
	 * @param $total
	 *
	 * @return false|string
	 *
	 * check shipping total if its less then or zero and check shipping name
	 */
	public function wc_check_matched_rate( $total ) {

		$amt = (int) WC()->cart->get_shipping_total();

		if ( $amt == 0 ) {
			$label = $this->check_shipping_name();
			if ( $label != '' ) {
				return $label;
			} else {
				return $total;
			}
		}

		return $total;
	}

	/**
	 * @return false|string
	 *
	 * Return Shipping Name when Local Pickup Activate in shipping
	 */

	public function check_shipping_name() {
		$packages       = WC()->shipping->get_packages();
		$resultHtml     = '';
		$chooseShipping = wc_get_chosen_shipping_method_ids();

		foreach ( $packages as $i => $package ) {

			$available_methods = $package['rates'];

			if ( is_array( $available_methods ) && count( $available_methods ) > 0 ) {
				foreach ( $available_methods as $method ) {

					if ( strpos( $method->id, 'local_pickup' ) !== false && ( is_array( $chooseShipping ) && strpos( $chooseShipping[0], 'local_pickup' ) !== false ) ) {
						ob_start();
						printf( '<label style="font-weight: normal;" for="shipping_method_%1$s_%2$s">%3$s</label>', $i, esc_attr( sanitize_title( $method->id ) ), __( 'Free', 'woocommerce' ) );
						$resultHtml = ob_get_clean();
					}
				}
			}

			return $resultHtml;

		}
	}

	public function display_mini_cart_undo_message() {
		if ( ! wp_doing_ajax() ) {
			return;
		}
		WFACP_Common::get_cart_undo_message();
	}

	public function display_order_summary_undo_message( $field ) {
		if ( ! wp_doing_ajax() ) {
			return;
		}
		$allow_delete = isset( $field['allow_delete'] ) ? wc_string_to_bool( $field['allow_delete'] ) : false;

		if ( false == $allow_delete ) {
			return;
		}
		WFACP_Common::get_cart_undo_message();
	}

	public function payment_button_text() {
		return '';
	}

	/**
	 * Forcefully change order button text for authorize and paypal express gateway
	 *
	 * @param $gateways
	 *
	 * @return mixed
	 */
	public function change_payment_gateway_text( $gateways ) {

		$orderText = $this->payment_button_text();
		if ( isset( $orderText ) && $orderText != '' ) {
			foreach ( $gateways as $gateway_id => $gateway ) {
				if ( in_array( $gateway_id, apply_filters( 'wfacp_allowed_gateway_order_button_text_change', [
					'authorize_net_cim_credit_card',
					'ppec_paypal',
					'square_credit_card',
					'braintree_credit_card',
					'braintree_cc',
					'nmi_gateway_woocommerce_credit_card'
				], $this ) ) ) {
					$gateways[ $gateway_id ]->order_button_text = $orderText;
				}
			}
		}

		return $gateways;
	}


	/**
	 * Change cancel url for dedicated only
	 *
	 * @param $url
	 *
	 * @return false|string
	 */
	public function change_cancel_url( $url ) {
		if ( WFACP_Core()->public->is_checkout_override() ) {
			return $url;
		}
		if ( ! WFACP_Core()->public->is_checkout_override() ) {
			$url = get_the_permalink( WFACP_Common::get_id() );
		}

		return $url;
	}

	public function have_coupon_field() {
		return $this->have_coupon_field;
	}

	public function have_shipping_method() {
		return $this->have_shipping_method;
	}

	public function get_wc_addr2_company_value() {

		$woocommerce_checkout_address_2_field = get_option( 'woocommerce_checkout_address_2_field', 'optional' );
		$woocommerce_checkout_company_field   = get_option( 'woocommerce_checkout_company_field', 'optional' );

		$get_wc_addr2_company = [
			'shipping_address_2_field' => 'wfacp_required_optional',
			'billing_address_2_field'  => 'wfacp_required_optional',
			'shipping_company_field'   => 'wfacp_required_optional',
			'billing_company_field'    => 'wfacp_required_optional',
		];

		if ( 'required' === $woocommerce_checkout_address_2_field ) {
			$get_wc_addr2_company['shipping_address_2_field'] = 'wfacp_required_active';
			$get_wc_addr2_company['billing_address_2_field']  = 'wfacp_required_active';
		}

		if ( 'required' === $woocommerce_checkout_company_field ) {
			$get_wc_addr2_company['shipping_company_field'] = 'wfacp_required_active';
			$get_wc_addr2_company['billing_company_field']  = 'wfacp_required_active';
		}

		return $get_wc_addr2_company;

	}

	public function check_cart_coupons( $fragments ) {
		if ( ! is_null( WC()->cart ) ) {
			WC()->cart->check_cart_coupons();
		}

		return $fragments;
	}

	public function call_before_cart_link( $breadcrumb ) {

	}

	public function get_wfacp_version() {
		$pageID         = WFACP_Common::get_id();
		$_wfacp_version = WFACP_Common::get_post_meta_data( $pageID, '_wfacp_version' );

		if ( $_wfacp_version == WFACP_VERSION ) {
			$this->setting_new_version = true;

			return true;
		}

		return false;

	}

	public function add_styling_class_to_country_field( $field, $key ) {
		if ( in_array( $key, [ 'billing_country', 'shipping_country' ] ) ) {
			$billing_allowed_countries  = WC()->countries->get_allowed_countries();
			$shipping_allowed_countries = WC()->countries->get_shipping_countries();
			if ( count( $billing_allowed_countries ) == 1 || count( $shipping_allowed_countries ) == 1 ) {
				$field['class'][] = 'wfacp_allowed_countries';
				$field['class'][] = 'wfacp-anim-wrap';
			}
		}

		return $field;
	}

	public function wc_cart_totals_coupon_label( $coupon, $echo = true ) {
		if ( is_string( $coupon ) ) {
			$coupon = new WC_Coupon( $coupon );
		}

		$svg = '<svg id="668a2151-f22c-4f0f-8525-beec391fcabb" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 415.33">
<title>Untitled-2</title>
<path d="M222.67,0H270L47,223,213.67,389.67l-25,25L0,226Z" transform="translate(0 0)" style="fill:#999"/>
<path d="M318,0S94,222,95.33,222L288.67,415.33,512,192V0Zm97.67,133.33a41,41,0,1,1,41-41A41,41,0,0,1,415.67,133.33Z" transform="translate(0 0)" style="fill:#999"/>
</svg>';

		$label = apply_filters( 'woocommerce_cart_totals_coupon_label', sprintf( esc_html__( 'Coupon %1$s %2$s', 'woocommerce' ), $svg, "<span class='wfacp_coupon_code'>" . $coupon->get_code() . '</span>' ), $coupon );
		if ( $echo ) {
			echo $label;
		} else {
			return $label;
		}
	}

	public function get_class_from_body() {

		$wfacp_body_class = [
			'wfacp_main_wrapper',
			'wfacp-' . $this->device_type,
			'wfacp_cls_' . $this->template_slug,
			'single_step',
			'woocommerce-checkout',
			//'wfacp_anim_active'
		];

		if ( isset( $this->customizer_fields_data['wfacp_form']['form_data']['btn_details']['make_button_sticky_on_mobile'] ) ) {
			$wfacp_body_class[] = $this->customizer_fields_data['wfacp_form']['form_data']['btn_details']['make_button_sticky_on_mobile'];
		}

		$wfacp_body_class = apply_filters( 'wfacp_body_class', $wfacp_body_class );
		$body_cls_str     = '';
		if ( ! empty( $wfacp_body_class ) ) {

			$wfacp_body_class = array_unique( $wfacp_body_class );
			$body_cls_str     = implode( ' ', $wfacp_body_class );
		}

		return $body_cls_str;

	}

	public function remove_extra_payment_gateways_in_customizer( $gateways ) {

		if ( WFACP_Common::is_theme_builder() ) {
			$gateways     = [];
			$payments     = WC_Payment_Gateways::instance();
			$all_gateways = $payments->payment_gateways();
			if ( isset( $all_gateways['cod'] ) ) {
				$gateways['cod']              = $all_gateways['cod'];
				$gateways['cod']->title       = __( 'Payment Gateway', 'woofunnels-aero-checkout' );
				$gateways['cod']->description = __( 'Enabled payment methods will display on the frontend.', 'woofunnels-aero-checkout' );

			}
		}

		return $gateways;
	}

	public function set_selected_template( $data ) {
		$this->selected_register_template                  = $data;
		$this->selected_register_template['template_type'] = $this->template_type;
	}

	public function get_selected_register_template() {
		return $this->selected_register_template;
	}

	protected function get_field_css_ready( $template_slug, $field_index ) {
		return '';
	}

	public function merge_builder_data( $field, $field_index ) {

		$template_slug = $this->get_template_slug();
		$template_slug = sanitize_title( $template_slug );
		$css_ready     = $this->get_field_css_ready( $template_slug, $field_index );
		if ( '' !== $css_ready ) {
			$field['cssready'] = explode( ',', $css_ready );
		}

		$css_classes = $this->default_css_class();

		if ( isset( $this->css_classes[ $field_index ] ) ) {
			$css_classes = $this->css_classes[ $field_index ];
		}
		$wrapper_class = 'wfacp-form-control-wrapper ';

		if ( isset( $field['cssready'] ) && is_array( $field['cssready'] ) && count( $field['cssready'] ) > 0 ) {
			$wrapper_class .= implode( ' ', $field['cssready'] );
		} else {
			$wrapper_class .= ' ' . $css_classes['class'];
		}
		$input_class = 'wfacp-form-control';
		$label_class = 'wfacp-form-control-label';
		if ( isset( $field['input_class'] ) && ! is_array( $field['input_class'] ) ) {
			$field['input_class'] = [];
		}
		if ( isset( $field['input_class'] ) && ( ! isset( $field['label_class'] ) || ! is_array( $field['label_class'] ) ) ) {
			$field['label_class'] = [];
		}
		$field['class'][]       = $wrapper_class;
		$field['input_class'][] = $input_class;
		$field['label_class'][] = $label_class;
		$field['class']         = array_unique( $field['class'] );
		$field['input_class']   = array_unique( $field['input_class'] );
		$field['label_class']   = array_unique( $field['label_class'] );
		if ( isset( $field['required'] ) ) {
			$field['class'][] = 'wfacp_field_required';
		}
		if ( $field_index == 'billing_address_2' || $field_index == 'street_address_2' || $field_index == 'shipping_address_2' ) {
			$search_index = array_search( 'screen-reader-text', $field['label_class'] );
			if ( false !== $search_index ) {
				unset( $field['label_class'][ $search_index ] );
			}
		}

		if ( isset( $field['type'] ) ) {


			if ( $field['type'] == 'multiselect' ) {

				$field['class'][]                       = 'wfacp_custom_field_multiselect';
				$field['type']                          = 'select';
				$field['name']                          = $field['id'] . '[]';
				$field['custom_attributes']['multiple'] = 'multiple';
				if ( isset( $field['multiselect_maximum'] ) ) {
					$field['custom_attributes']['data-max-selection'] = $field['multiselect_maximum'];
				}
				if ( isset( $field['multiselect_maximum_error'] ) ) {
					$field['custom_attributes']['data-max-error'] = $field['multiselect_maximum_error'];
				}

			} elseif ( 'email' == $field['type'] ) {
				$field['validate'][] = 'email';
			} elseif ( 'checkbox' == $field['type'] ) {
				$field['class'][] = 'wfacp_checkbox_field';
				unset( $field['label_class'][0] );
				if ( isset( $field['field_type'] ) && $field['field_type'] != 'advanced' ) {
					unset( $field['input_class'][0] );
				}
			} elseif ( 'select2' == $field['type'] ) {
				$field['class'][]       = 'wfacp_custom_field_select2';
				$field['org_type']      = $field['type'];
				$field['type']          = 'select';
				$field['input_class'][] = 'wfacp_select2_custom_field';
				$options                = $field['options'];
				$field['options']       = array_merge( [ '' => $field['placeholder'] ], $options );
			} elseif ( 'textarea' == $field['type'] ) {
				$field['class'][] = 'wfacp_textarea_fields';
			}

			if ( in_array( $field['type'], [ 'date' ] ) ) {
				$default = $field['default'];
				if ( '' !== $default ) {
					$default          = str_replace( '/', '-', $default );
					$field['default'] = date( 'Y-m-d', strtotime( $default ) );
				}
				unset( $default );
			}
		}

		if ( in_array( $field_index, [ 'billing_postcode', 'shipping_postcode', 'billing_city', 'shipping_city' ] ) ) {
			$field['class'][] = 'update_totals_on_change';
		}

		if ( in_array( $field_index, [ 'billing_country', 'shipping_country' ] ) ) {
			if ( ! empty( $this->base_country[ $field_index ] ) ) {
				$field['default'] = $this->base_country[ $field_index ];
			}
		}
		if ( in_array( $field_index, [ 'billing_state', 'shipping_state' ] ) ) {
			$field['class'][] = 'wfacp_state_wrap';

		}

		return $field;
	}

	public function set_current_open_step( $step = 'single_step' ) {
		$this->current_open_step = $step;
	}

	public function get_current_open_step() {
		return $this->current_open_step;
	}

	public function set_form_data( $settings ) {
		$this->form_data = $settings;
	}

	public function get_form_data() {

		return [];
	}

	public function get_heading_title_class() {

		return '';
	}

	public function get_heading_class() {

		return '';
	}

	public function get_sub_heading_class() {

		return '';
	}


	public function get_payment_desc() {
		return '';
	}

	public function payment_heading() {

		return '';
	}

	public function payment_sub_heading() {

		return esc_attr__( 'All transactions are secure and encrypted. Credit card information is never stored on our servers.', 'woofunnels-aero-checkout' );
	}


	public function change_place_order_button_text( $text ) {

		return $text;
	}

	public function payment_button_alignment() {

		return 'center';
	}


	public function add_class_change_place_order( $btn_text ) {

		return $btn_text;
	}


	public function get_template_type_px() {

		$template_type          = $this->get_template_type();
		$wfacp_templates_slug   = $this->wfacp_templates_slug;
		$selected_template_slug = $this->get_template_slug();
		if ( $template_type != '' ) {
			if ( is_array( $wfacp_templates_slug ) && isset( $wfacp_templates_slug[ $template_type ] ) ) {
				$templateDetails = $wfacp_templates_slug[ $template_type ];

				if ( $selected_template_slug != '' && is_array( $templateDetails ) && isset( $templateDetails[ $selected_template_slug ] ) ) {
					return $templateDetails[ $selected_template_slug ];
				}
			}
		}

		return 15;
	}


	public function get_product_switcher_mobile_style() {
		return '';
	}

	public function get_mobile_mini_cart( $input_data = [] ) {
		if ( WFACP_Core()->pay->is_order_pay() ) {
			return;
		}
		include WFACP_TEMPLATE_COMMON . '/template-parts/mobile-collapsible-mini-cart.php';
	}

	public function get_data() {
		return $this->data;
	}

	public function get_smart_buttons() {
		return $this->smart_buttons;
	}

	final public function display_smart_buttons() {
		if ( is_admin() || wp_doing_ajax() ) {
			return;
		}

		include WFACP_TEMPLATE_COMMON . '/smart_buttons.php';

	}


	/**
	 *backward compatibility for header footer
	 */
	public function get_customizer_data() {

	}

	final public function get_container() {
		include $this->template_dir . '/views/container.php';
	}

	public function get_theme_header() {
		get_header();
		do_action( 'wfacp_header_print_in_head' );
	}

	public function get_theme_footer() {
		do_action( 'wfacp_footer_before_print_scripts' );
		get_footer();
		do_action( 'wfacp_footer_after_print_scripts' );
	}


	public function global_css() {
		$_wfacp_global_settings = get_option( '_wfacp_global_settings' );
		$page_settings          = WFACP_Common::get_page_settings( WFACP_Common::get_id() );

		if ( isset( $_wfacp_global_settings['wfacp_checkout_global_css'] ) && $_wfacp_global_settings['wfacp_checkout_global_css'] != '' ) {
			$global_custom_css = '<style>' . $_wfacp_global_settings['wfacp_checkout_global_css'] . '</style>';
			echo $global_custom_css;
		}
		if ( isset( $page_settings['header_css'] ) && $page_settings['header_css'] != '' ) {
			$header_css = '<style id="header_css">' . $page_settings['header_css'] . '</style>';
			echo $header_css;
		}
	}

	/**
	 * Override this when new template using theme template or aero checkout boxed template
	 * @return bool
	 */
	public function use_own_template() {
		return true;
	}

	public function remove_admin_bar_print_hook() {
		remove_action( 'wp_footer', 'wp_admin_bar_render', 1000 );
		remove_action( 'in_admin_header', 'wp_admin_bar_render', 0 );
		add_action( 'wfacp_footer_after_print_scripts', 'wp_admin_bar_render' );
	}

	final public function remove_unused_js() {
		if ( WFACP_Common::is_theme_builder() ) {
			return;
		}
		// this password strength js enqueue wordpress but not use by Woocommerce. Woocommerce enqueue password strength by own library
		wp_dequeue_script( 'password-strength-meter' );
		if ( ! is_product() ) {
			//this is extra js enqueue by woocommerce on every page if ajax add to cart enabled by woocommerce Settings at our checkout page no add to cart button present
			wp_dequeue_script( 'wc-add-to-cart' );
		}
	}

	public function modern_label( $field ) {
		if ( empty( $field ) ) {
			return $field;
		}
		$position = $this->get_field_label_position();
		if ( 'wfacp-modern-label' != $position ) {
			return $field;
		}

		// For Fresh new import
		if ( isset( $field['placeholder'] ) ) {
			// Handled  blank space  issue with floating label;
			$placeholder = trim( $field['placeholder'] );
			if ( empty( $placeholder ) ) {
				$field['placeholder'] = $field['label'];
			}
		}


		if ( isset( $field['required'] ) && wc_string_to_bool( $field['required'] ) ) {
			if ( ! empty( $field['placeholder'] ) ) {
				$field['placeholder'] .= ' *';
			}
		}

		return $field;
	}


	public function get_field_label_position() {
		if ( isset( $this->form_data['wfacp_label_position'] ) && ! empty( $this->form_data['wfacp_label_position'] ) ) {

			return $this->form_data['wfacp_label_position'];
		}
		$field_label = get_post_meta( WFACP_Common::get_id(), '_wfacp_field_label_position', true );

		if ( ! empty( $field_label ) ) {
			return $field_label;
		}

		return 'wfacp-inside';
	}

	/**
	 * print tracking script if tiktok enabled
	 */
	public function maybe_tiktok_enabled() {
		if ( ! class_exists( 'WFACP_Analytics_TikTok' ) ) {
			return false;
		}
		$obj = WFACP_Analytics_TikTok::get_instance();

		if ( ! empty( $obj->get_key() ) ) {
			return true;
		}

		return false;
	}

	/***
	 * Handle Standard Paypal Gateway
	 * @return void
	 */
	public function handle_paypal_processed() {
		$public = WFACP_Core()->public;
		if ( ! $public instanceof WFACP_Public ) {
			return;
		}
		$payment_method = filter_input( INPUT_POST, 'payment_method', FILTER_UNSAFE_RAW );
		if ( 'paypal' !== $payment_method ) {
			return;
		}
		remove_filter( 'woocommerce_checkout_no_payment_needed_redirect', [ $public, 'reset_session_when_order_processed' ] );
		remove_filter( 'woocommerce_payment_successful_result', [ $public, 'reset_session_when_order_processed' ] );

	}

	public function handle_copy_billing_shipping_code() {
		if ( ! WFACP_Core()->pay->is_order_pay() ) {
			add_action( 'woocommerce_checkout_after_customer_details', [ $this, 'print_billing_fields' ] );
		}
	}


	public function print_billing_fields() {

		$temp_field = [
			'first_name',
			'last_name',
			'address_1',
			'address_2',
			'city',
			'postcode',
			'state',
			'house_number',
			'street_name',
			'house_number_suffix',
		];
		$add_fields = [
			'address_1',
			'address_2',
			'city',
			'postcode',
			'state',
		];


		$instance = wfacp_template();
		$fields   = $instance->get_checkout_fields();
		if ( false == $instance->have_billing_address() && $instance->have_shipping_address() ) {
			$address_prefix = 'billing_';
			if ( isset( $fields['billing']['billing_first_name'] ) ) {
				unset( $temp_field[0] );
			}
			if ( isset( $fields['billing']['billing_last_name'] ) ) {
				unset( $temp_field[1] );
			}
			foreach ( $temp_field as $item ) {
				echo $this->replace_names_ids( $address_prefix . $item, $address_prefix . $item );
			}
		} elseif ( $instance->have_shipping_address() && $instance->have_billing_address() ) {
			if ( isset( $fields['shipping']['shipping_first_name'] ) && ! isset( $fields['billing']['billing_first_name'] ) ) {
				echo $this->replace_names_ids( 'billing_first_name', 'billing_first_name' );
			}
			if ( isset( $fields['shipping']['shipping_last_name'] ) && ! isset( $fields['billing']['billing_last_name'] ) ) {
				echo $this->replace_names_ids( 'billing_last_name', 'billing_last_name' );
			}
			if ( isset( $fields['billing']['billing_first_name'] ) && ! isset( $fields['shipping']['shipping_first_name'] ) && ! isset( $fields['advanced']['shipping_first_name'] ) ) {
				echo $this->replace_names_ids( 'shipping_first_name', 'shipping_first_name' );
			}
			if ( isset( $fields['billing']['billing_last_name'] ) && ! isset( $fields['shipping']['shipping_last_name'] ) && ! isset( $fields['advanced']['shipping_last_name'] ) ) {
				echo $this->replace_names_ids( 'shipping_last_name', 'shipping_last_name' );
			}

			foreach ( $add_fields as $key ) {
				$b_key = 'billing_' . $key;
				$s_key = 'shipping_' . $key;
				if ( ! isset( $fields['billing'][ $b_key ] ) ) {
					echo $this->replace_names_ids( $b_key, $b_key );
				}
				if ( ! isset( $fields['shipping'][ $s_key ] ) ) {
					echo $this->replace_names_ids( $s_key, $s_key );
				}
			}
		}
	}

	public function replace_names_ids( $name, $id ) {
		return sprintf( '<input type="hidden" name="%s" id="%s" form="wfacp_checkout_form"  class="wfacp_hidden_fields">', $name, $id ) . "\n";
	}

	/*---------------------------------Add Field Wrapper On the advanced field type----------------------- */
	public function add_field_wrapper( $sections ) {
		$advanced_fields          = [];
		$not_include_in_addvanced = [
			'order_summary',
			'order_coupon',
			'order_comments',
			'order_total',
			'shipping_calculator',
		];
		foreach ( $sections['fields'] as $index => $field ) {

			if ( is_array( $field ) && count( $field ) > 0 && isset( $field['field_type'] ) && ( $field['field_type'] == 'advanced' ) && ! in_array( $field['id'], $not_include_in_addvanced ) ) {

				if ( ! isset( $field['unique_id'] ) ) {
					continue;
				}
				$advanced_fields[] = $index;

			}

		}

		if ( is_array( $advanced_fields ) && count( $advanced_fields ) > 0 ) {
			$first_index = $advanced_fields[0];
			$last_index  = end( $advanced_fields );


			$sections['fields'][ $first_index ]['start_advanced_wrapper'] = "yes";
			$sections['fields'][ $last_index ]['close_advanced_wrapper']  = "yes";


		}


		return $sections;
	}

	public function field_open( $key, $field, $field_value ) {
		if ( is_array( $field ) && count( $field ) > 0 && isset( $field['start_advanced_wrapper'] ) && ( $field['start_advanced_wrapper'] == 'yes' ) ) {
			echo "<div class='wfacp_advanced_field_wrap'>";

			return true;
		}

		return false;

	}

	public function field_close( $is_field_open, $key, $field, $field_value ) {
		if ( is_array( $field ) && count( $field ) > 0 && isset( $field['close_advanced_wrapper'] ) && ( $field['close_advanced_wrapper'] == 'yes' ) ) {
			echo "</div>";

		}

	}

	public function element_start_before_the_form() {

		echo "<div id=wfacp-sec-wrapper>";
	}

	public function element_end_after_the_form() {
		echo "</div>";
	}

	/* -------------------------------------------Checkout Collapsible order field------------------------------- */
	public function collapsible_option_field_actions() {

		$this->create_optional_fields();
		add_filter( 'woocommerce_form_field_args', [ $this, 'add_default_wfacp_styling' ], 10, 3 );
		add_action( 'wfacp_internal_css', [ $this, 'add_internal_css' ], 10, 2 );

	}

	public function create_optional_fields() {
		$collapsible_optional_fields = $this->get_collapsible_optional_fields();


		if ( ! is_array( $collapsible_optional_fields ) || count( $collapsible_optional_fields ) == 0 ) {
			return '';
		}
		$this->optional_collapsible_fields = $collapsible_optional_fields;
		foreach ( $collapsible_optional_fields as $key => $field ) {

			if ( false === wc_string_to_bool( $field ) ) {

				continue;
			}


			add_action( 'wfacp_before_' . $key . '_field', function ( $field_key, $field, $field_value ) {
				$label = '';

				
				if ( ! empty( $field_value ) ) {
					return;
				}

				if ( isset( $field['required'] ) && true == wc_string_to_bool( $field['required'] ) ) {
					return;
				}


				if ( isset( $this->optional_collapsible_fields['collapsible_optional_link_text'] ) ) {
					$label .= $this->optional_collapsible_fields['collapsible_optional_link_text'] . " ";
				}
				if ( isset( $field['label'] ) ) {
					$label .= $field['label'];
				}


				$default_class = [];


				if ( isset( $field['class'] ) ) {
					$default_class = $field['class'];

				}

				if ( is_array( $default_class ) && ! in_array( 'form-row', $default_class ) ) {
					$default_class[] = 'form-row';
					$default_class[] = 'wfacp_collapsible_field_wrap';
				}
				$id = $field_key . "_collapse_label";


				$default_class = isset( $field['class'] ) ? implode( ' ', $default_class ) : 'form-row wfacp-form-control-wrapper wfacp-col-full';


				$svg_icon = '<svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 10 10" fill="none">
<path d="M9.16665 4.16665H5.83335V0.833345C5.83335 0.373344 5.46084 -1.90735e-06 5 -1.90735e-06C4.53916 -1.90735e-06 4.16665 0.373344 4.16665 0.833345V4.16665H0.833348C0.372506 4.16665 0 4.54 0 5C0 5.46 0.372506 5.83335 0.833348 5.83335H4.16665V9.16665C4.16665 9.62665 4.53916 10 5 10C5.46084 10 5.83335 9.62665 5.83335 9.16665V5.83335H9.16665C9.6275 5.83335 10 5.46 10 5C10 4.54 9.6275 4.16665 9.16665 4.16665Z" fill="currentColor"/>
</svg>';
				echo "<p class='$default_class' id='$id'><a href='#' class='wfacp_collapsible' data-field='$field_key' >$svg_icon $label </a></p>";
			}, 10, 3 );
		}


	}

	public function get_collapsible_optional_fields() {
		$page_settings = get_post_meta( $this->wfacp_id, '_wfacp_page_settings', true );


		$collapsible_optional_fields = [];


		if ( ! isset( $page_settings['collapsible_optional_fields'] ) || ! is_array( $page_settings['collapsible_optional_fields'] ) ) {
			return $collapsible_optional_fields;
		}

		$collapsible_optional_fields = $page_settings['collapsible_optional_fields'];


		if ( isset( $page_settings['collapsible_optional_link_text'] ) && ! empty( $page_settings['collapsible_optional_link_text'] ) ) {
			$collapsible_optional_fields['collapsible_optional_link_text'] = $page_settings['collapsible_optional_link_text'];
		}


		return $collapsible_optional_fields;


	}

	public function add_default_wfacp_styling( $args, $key, $field_value ) {

		if ( ! empty( $field_value ) ) {
			return $args;
		}

		$collapsible_optional_fields = $this->optional_collapsible_fields;

		if ( ! isset( $args['id'] ) || ! is_array( $collapsible_optional_fields ) || count( $collapsible_optional_fields ) == 0 ) {
			return $args;
		}
		if ( ! array_key_exists( $args['id'], $collapsible_optional_fields ) ) {
			return $args;
		}

		if ( isset( $args['required'] ) && true === wc_string_to_bool( $args['required'] ) ) {
			return $args;
		}


		if ( $args['type'] == 'select' && isset( $args['custom_attributes']['multiple'] ) && 'multiple' === $args['custom_attributes']['multiple'] ) {
			$key = $args['id'];
		}

		if ( isset( $collapsible_optional_fields[ $key ] ) && wc_string_to_bool( $collapsible_optional_fields[ $key ] ) ) {
			$args['class'] = array_merge( [ 'wfacp_collapsible_enable', 'wfacp_hidden_class' ], $args['class'] );
		}


		return $args;
	}

	public function add_internal_css() {
		$instance = wfacp_template();
		if ( ! $instance instanceof WFACP_Template_Common ) {
			return;
		}

		$bodyClass = "body #wfacp-sec-wrapper ";


		$cssHtml = "<style>";
		$cssHtml .= $bodyClass . " p.form-row.wfacp_collapsible_enable.wfacp_hidden_class {display: none;}";
		$cssHtml .= $bodyClass . " p.form-row.wfacp_collapsible_field_wrap.wfacp_hidden_class {display: none;}";
		$cssHtml .= "</style>";
		echo $cssHtml;

	}

	public function override_notices_templates( $template, $template_name ) {

		$notices_templates = apply_filters( 'wfacp_override_notices_templates', [
			'notices/error.php',
			'notices/notice.php',
			'notices/success.php',
		], $template_name, $this );

		if ( ! is_array( $notices_templates ) || count( $notices_templates ) == 0 ) {
			return $template;
		}

		if ( function_exists( 'wc_print_notice' ) && in_array( $template_name, $notices_templates ) ) {
			return WFACP_TEMPLATE_COMMON . "/checkout/{$template_name}";
		}

		return $template;
	}

	/**
	 * to avoid unserialize of the current class
	 */
	public function __wakeup() {
		throw new ErrorException( 'WFACP_Core classes can`t converted to string' );
	}

	/**
	 * to avoid serialize of the current class
	 */
	public function __sleep() {
		throw new ErrorException( 'WFACP_Core classes can`t converted to string' );
	}

	/**
	 * To avoid cloning of current template class
	 */
	protected function __clone() {
	}
	public function get_order_pay_summary( $order ) {
		include WFACP_TEMPLATE_COMMON . '/order-pay-summary.php';
	}
	public function get_order_pay_summary_heading() {
		return apply_filters( 'wfacp_order_pay_summary_heading', __( 'Review Order Summary', 'woofunnels-aero-checkout' ) );
	}

	public function add_body_class( $class ) {
		if ( ! WFACP_Common::is_theme_builder() && ! WFACP_Common::is_customizer() ) {
			//$class[] = 'wfacp_anim_active';
		} else {
			$class[] = 'wfacp_editor_active';
		}


		return $class;
	}

	public function collapsible_order_summary() {

		include WFACP_PLUGIN_DIR . '/public/global/collapsible-order-summary/order-summary.php';
	}

	public function get_mobile_mini_cart_collapsible_title() {
		return '';

	}

	public function enable_collapsed_coupon_field() {
		return '';
	}


	public function collapse_enable_coupon_collapsible() {
		return 'false';
	}

	public function enable_coupon_right_side_coupon() {
		return 'true';
	}

	public function get_mobile_mini_cart_expand_title() {
		return '';
	}

	public function get_coupon_button_text() {
		return __( 'Apply', 'woocommerce' );
	}

	/**
	 * Prevent Placeholder & label Mismatch issue when country changed
	 * this code not impact on translation of label & placeholder
	 *
	 * i.e Now Label & placeholder of Postcode field In Ireland is  same`EIRCODE`
	 *
	 * @param $countries
	 *
	 * @return mixed
	 */
	public function address_i18_country( $countries ) {
		if ( wp_doing_ajax() ) {
			return $countries;
		}
		$label_position = $this->get_field_label_position();

		if ( 'wfacp-modern-label' != $label_position ) {
			return $countries;
		}


		$aero_locals = include __DIR__ . '/address-i18-locals.php';
		remove_filter( 'woocommerce_get_country_locale', [ $this, 'address_i18_country' ] );

		return array_merge( $countries, $aero_locals );
	}

	/**
	 * WooCommerce Translate below 3 field city,postcode,State field
	 *
	 * @param $local
	 *
	 * @return array
	 */
	public function address_i18_same_label_placeholder( $local ) {
		if ( wp_doing_ajax() ) {
			return $local;
		}
		$label_position = $this->get_field_label_position();

		if ( 'wfacp-modern-label' != $label_position ) {
			return $local;
		}
		if ( isset( $local['postcode'] ) && isset( $local['postcode']['label'] ) ) {
			$local['postcode']['placeholder'] = $local['postcode']['label'];
		}
		if ( isset( $local['city'] ) && isset( $local['city']['label'] ) ) {
			$local['city']['placeholder'] = $local['city']['label'];
		}
		if ( isset( $local['state'] ) && isset( $local['state']['label'] ) ) {
			$local['state']['placeholder'] = $local['state']['label'];
		}
		remove_filter( 'woocommerce_get_country_locale_base', [ $this, 'address_i18_same_label_placeholder' ] );

		return $local;
	}

	public function trigger_js_event_editor() {
		if ( ! WFACP_Common::is_theme_builder() ) {
			return;
		}

		?>
        <script>
            (function ($) {
                $(document.body).on('wfacp_editor_init', function (e, v) {
                    $(document.body).removeClass('wfacp-inside');
                    $(document.body).removeClass('wfacp-top');
                    $(document.body).removeClass('wfacp-modern-label');
                    $(document.body).addClass(v.position_label);

                    $(document.body).trigger('wfacp_intl_setup');
                })
                $(document.body).trigger('wfacp_editor_init', {'position_label': '<?php echo $this->get_field_label_position()?>'});
            })(jQuery);
        </script>
		<?php

	}

	/**
	 * Unset Yoast Meta Description(Meta Tag og:desc,twitter:desc) tag for our checkout.
	 *
	 * @param $descriptions
	 *
	 * @return mixed
	 */
	public function unset_open_graph_description_Presenter( $descriptions ) {
		if ( empty( $descriptions ) ) {
			return $descriptions;
		}
		foreach ( $descriptions as $key => $description ) {
			$class_name = get_class( $description );
			if ( false !== strpos( $class_name, 'Description_Presenter' ) ) {
				unset( $descriptions[ $key ] );
			}
		}

		return $descriptions;
	}

	public function add_place_order_btn_text( $fragments ) {
		$fragments['place_order_text'] = $this->place_order_btn_text;

		return $fragments;
	}


}
