<?php

class WFACP_GutenBerg {
	private static $ins = null;
	private static $front_locals = [];
	private $template_file = '';
	private $wfacp_id = 0;
	public $modules_instance = [];
	private $post = null;
	public static $mini_cart_data = [];
	public static $checkout_form_data = [];
	public static $html_fields = [];
	public static $section_fields = [];

	private function __construct() {
		$this->template_file = __DIR__ . '/template/template.php';
		add_action( 'wfacp_checkout_page_found', [ $this, 'setup_global_checkout' ] );
		add_action( 'wfacp_template_removed', [ $this, 'delete_gutenberg_data' ] );
		add_action( 'wfacp_duplicate_pages', [ $this, 'duplicate_template' ], 10, 3 );
		add_action( 'wfacp_after_checkout_page_found', [ $this, 'only_add_to_cart_product' ], 1000 );
		$this->block_registration();
		$this->register();
	}

	public static function get_instance() {
		if ( is_null( self::$ins ) ) {
			self::$ins = new self();
		}

		return self::$ins;

	}

	public static function set_locals( $name, $id ) {
		self::$front_locals[ $name ] = $id;
	}

	public static function get_locals() {
		return self::$front_locals;
	}

	private function register() {
		add_action( 'init', [ $this, 'init_extension' ], 21 );
		add_action( 'wfacp_register_template_types', [ $this, 'register_template_type' ], 15 );
		add_filter( 'wfacp_register_templates', [ $this, 'register_templates' ] );
		add_action( 'wfacp_template_load', [ $this, 'load_abs_class' ], 10, 2 );
		add_filter( 'wfacp_template_edit_link', [ $this, 'add_template_edit_link' ], 10, 2 );
		add_action( 'wp_ajax_get_gutenberg_checkout_from_data', [ $this, 'get_form_html' ] );
		add_filter( 'admin_body_class', [ $this, 'bwf_blocks_admin_body_class' ] );
		add_filter( 'wfacp_is_theme_builder', [ $this, 'is_edit_page' ] );
		add_filter( 'wfacp_block_editor_compatibility', '__return_true' );
		$this->load_require_files();
	}

	public function is_edit_page( $status ) {
		if ( is_admin() && isset( $_GET['action'] ) && isset( $_GET['post'] ) && 'edit' === $_GET['action'] && $_GET['post'] > 0 ) {
			$post = get_post( $_GET['post'] );
			if ( ! is_null( $post ) && $post->post_type == WFACP_Common::get_post_type_slug() ) {
				$status = true;
			}
		}

		return $status;
	}

	public function get_form_html() {

		add_filter( 'wfacp_is_theme_builder', '__return_true' );
		add_filter( 'woocommerce_payment_gateways', [ 'WFACP_Common', 'unset_gateways' ], 1000 );
		if ( isset( $_REQUEST['wfacp_id'] ) ) {
			$post_id = $_REQUEST['wfacp_id'];
			$post    = get_post( $post_id );
			if ( ! is_null( $post ) && $post->post_type == WFACP_Common::get_post_type_slug() ) {
				WFACP_Common::wc_ajax_get_refreshed_fragments();
			} else {
				return;
			}
		}

		$json = file_get_contents( 'php://input' );
		if ( '' !== $json ) {
			$json = json_decode( $json, true );
		} else {
			$json = [];
		}

		$template = wfacp_template();
		$id       = 'wfacp_gutenberg_checkout_form';
		WFACP_Common::set_session( $id, $json );
		$template->set_form_data( $json );

		do_action( 'wfacp_get_gutenberg_form_data', $post, $json );
		/**
		 * @var $template WFACP_GutenBerg_template;
		 */
		$template->set_active_step_on_cookie();

		include $template->wfacp_get_form();

		exit( 0 );
	}


	protected function block_registration() {
		if ( version_compare( $GLOBALS['wp_version'], '5.8-alpha-1', '<' ) ) {
			add_filter( 'block_categories', [ $this, 'add_block_categories' ], 11, 2 );

			return;
		}
		add_filter( 'block_categories_all', [ $this, 'add_block_categories' ], 11, 2 );
	}

	/**
	 * @param $loader WFACP_Template_loader
	 */
	public function register_template_type( $loader ) {
		$template = [
			'slug'    => 'gutenberg',
			'title'   => __( 'Block Editor', 'funnel-builder' ),
			'filters' => WFACP_Common::get_template_filter()
		];

		$loader->register_template_type( $template );
	}

	public function register_templates( $designs ) {
		$templates            = WooFunnels_Dashboard::get_all_templates();
		$designs['gutenberg'] = ( isset( $templates['wc_checkout'] ) && isset( $templates['wc_checkout']['gutenberg'] ) ) ? $templates['wc_checkout']['gutenberg'] : [
			"gutenberg_1" => [
				"name"               => "Build from Scratch",
				"show_import_popup"  => "no",
				"build_from_scratch" => "yes",
				"slug"               => "gutenberg_1",
				"group"              => "gutenberg",
				"builder"            => "gutenberg",
				"no_steps"           => 1,
			]
		];

		if ( is_array( $designs['gutenberg'] ) && count( $designs['gutenberg'] ) > 0 ) {
			foreach ( $designs['gutenberg'] as $key => $val ) {
				$val['path']                  = $this->template_file;
				$designs['gutenberg'][ $key ] = $val;
			}
		}

		return $designs;
	}

	public function load_abs_class( $wfacp_id, $template = [] ) {
		if ( empty( $template ) ) {
			return;
		}
		if ( 'gutenberg' === $template['selected_type'] ) {
			include_once __DIR__ . ( '/class-wfacp-gutenberg-template.php' );
		}
	}

	public function add_template_edit_link( $links, $admin ) {
		$url = add_query_arg( [
			'action' => 'edit',
			'post'   => $admin->wfacp_id,
		], admin_url( 'post.php' ) );

		$links['gutenberg'] = [ 'url' => $url, 'button_text' => __( 'Edit', 'funnel-builder' ) ];

		return $links;
	}


	public function init_extension() {
		$post_id = 0;
		if ( isset( $_REQUEST['post_id'] ) && $_REQUEST['post_id'] > 0 ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_id = absint( $_REQUEST['post_id'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		} elseif ( isset( $_REQUEST['post'] ) && $_REQUEST['post'] > 0 && isset( $_REQUEST['action'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_id = absint( $_REQUEST['post'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}
		$post_type = WFACP_Common::get_post_type_slug();

		if ( $post_id > 0 ) {
			$post = get_post( $post_id );
			if ( ! is_null( $post ) && $post->post_type === $post_type ) {
				WFACP_Common::set_id( $post_id );
				WFACP_Core()->template_loader->load_template( $post_id );
				add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ) );

				return;
			}
		}
		add_action( "rest_insert_{$post_type}", [ $this, 'register_post_meta_at_rest_level' ] );
		add_action( 'wfacp_after_template_found', [ $this, 'prepare_module' ] );


	}

	public function register_post_meta_at_rest_level() {
		// Register Gutenberg Block Meta for default font
		register_post_meta( WFACP_Common::get_post_type_slug(), 'bwfblock_default_font', array(
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'string',
		) );
	}

	public function prepare_module() {
		$id     = WFACP_Common::get_id();
		$design = WFACP_Common::get_page_design( $id );

		if ( 'gutenberg' !== $design['selected_type'] ) {
			return;
		}
		$this->register_post_meta_at_rest_level();

		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_block_front_assets' ) );
	}


	/**
	 * Add custom category
	 *
	 * @param array $categories category list.
	 * @param WP_Post $post post object.
	 */
	public function add_block_categories( $categories ) {

		if ( false !== array_search( 'woofunnels', array_column( $categories, 'slug' ) ) ) {
			return $categories;
		} else {
			return array_merge( array(
				array(
					'slug'  => 'woofunnels',
					'title' => esc_html__( 'FunnelKit', 'funnel-builder' ),
				),
			), $categories );
		}

	}


	public function load_require_files() {
		if ( WFACP_Common::is_disabled() ) {
			return;
		}
		//load necessary files
		require_once __DIR__ . '/includes/functions.php';
		require_once __DIR__ . '/includes/class-bwf-blocks-css.php';
		require_once __DIR__ . '/includes/class-bwf-blocks-frontend-css.php';
		// require_once __DIR__ . '/includes/class-render-blocks.php';
	}


	/**
	 * Load assets for wp-admin when editor is active.
	 */
	public function enqueue_block_editor_assets() {
		global $pagenow, $post;

		if ( ( $post instanceof WP_Post ) && WFACP_Common::get_post_type_slug() === $post->post_type && 'post.php' === $pagenow && isset( $_GET['post'] ) && intval( $_GET['post'] ) > 0 ) { //phpcs:ignore

			$app_name     = 'wfacp-block-editor';
			$frontend_dir = defined( 'BWF_AERO_REACT_ENVIRONMENT' ) ? BWF_AERO_REACT_ENVIRONMENT : WFACP_PLUGIN_URL . '/builder/gutenberg/dist';
			$assets_path  = $frontend_dir . "/$app_name.asset.php";
			$assets       = file_exists( $assets_path ) ? include $assets_path : array(
				'dependencies' => array(
					'wp-plugins',
					'wp-element',
					'wp-edit-post',
					'wp-i18n',
					'wp-api-request',
					'wp-data',
					'wp-hooks',
					'wp-plugins',
					'wp-components',
					'wp-blocks',
					'wp-editor',
					'wp-compose',
				),
				'version'      => time(),
			);
			$js_path      = "/$app_name.js";
			$style_path   = "/$app_name.css";

			$deps    = ( isset( $assets['dependencies'] ) ? array_merge( $assets['dependencies'], array( 'jquery' ) ) : array( 'jquery' ) );
			$deps    = array_merge( $deps, array( 'bwf-font-awesome-kit' ) );
			$version = $assets['version'];

			$script_deps = array_filter( $deps, function ( $dep ) {
				return false === strpos( $dep, 'css' );
			} );
			wp_enqueue_style( 'wfacp-gutenberg-style', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/css/wfacp_combined.min.css', false, WFACP_VERSION_DEV );
			wp_enqueue_style( 'gotenberg-style', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/css/wfacp-form.min.css', array(), WFACP_VERSION, false );
			wp_enqueue_script( 'jquery' );

			if ( defined( 'BWF_DEV' ) ) {
				wp_enqueue_script( 'wfacp_checkout_js', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/js/public.js', [ 'jquery' ], WFACP_VERSION_DEV, true );
			} else {
				wp_enqueue_script( 'wfacp_checkout_js', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/js/public.min.js', [ 'jquery' ], WFACP_VERSION_DEV, true );
			}


			$page_settings = WFACP_Common::get_page_settings( WFACP_Common::get_id() );

			if ( isset( $page_settings['enable_phone_flag'] ) && wc_string_to_bool( $page_settings['enable_phone_flag'] ) ) {
				wp_enqueue_style( 'wfacp-intl-css', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/css/intlTelInput.css', false, WFACP_VERSION_DEV );
				wp_enqueue_script( 'wfacp-intlTelInput-js', plugin_dir_url( WFACP_PLUGIN_FILE ) . 'assets/js/intlTelInput.min.js', [], WFACP_VERSION_DEV );
			}

			$template = wfacp_template();
			$template->localize_locals();

			// Our free kit https://fontawesome.com/kits/f4306c3ab0/settings
			wp_register_script( 'bwf-font-awesome-kit', 'https://kit.fontawesome.com/f4306c3ab0.js', null, null, true );
			wp_enqueue_script( 'wfacp-block-editor', $frontend_dir . $js_path, $script_deps, $version, true );
			wp_enqueue_script( 'web-font', 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js', [], true );


			$section_data    = self::register_section_fields();
			$section_classes = self::class_section();
			$form_section    = isset( $section_data['section'] ) ? $section_data['section'] : [];
			$form_attr       = isset( $section_data['attributes'] ) ? $section_data['attributes'] : [];
			$field_classes   = isset( $section_classes['section'] ) ? $section_classes['section'] : [];
			$classes_attr    = isset( $section_classes['attributes'] ) ? $section_classes['attributes'] : [];
			$form_attr       = array_merge( self::form_attributes(), $form_attr, $classes_attr );


			wp_localize_script( 'wfacp-block-editor', 'wfacp_blocks', array(
				'i18n'                   => 'funnel-builder',
				'bwf_g_fonts'            => bwf_get_fonts_list( 'all' ),
				'bwf_g_font_names'       => bwf_get_fonts_list( 'name_only' ),
				'bwf_standard_fonts'     => file_exists( __DIR__ . '/font/standard-fonts.php' ) ? include __DIR__ . '/font/standard-fonts.php' : array(),
				'ajax_url'               => admin_url( 'admin-ajax.php' ),
				'step_count'             => $template->get_step_count(),
				'fieldsets'              => $form_section,
				'section_classes'        => $field_classes,
				'cart_attributes'        => self::mini_cart_default_attrs(),
				'form_attributes'        => $form_attr,
				'get_fieldsets'          => $template->get_fieldsets(),
				'is_best_value'          => '',
				'is_what_included'       => '',
				'html_fields'            => self::$html_fields,
				'is_lite'                => true,
				'wp_version'             => $GLOBALS['wp_version'],
				'enable_checkout_terms'  => ! ! wc_terms_and_conditions_page_id(),
				'enable_checkout_policy' => ! ! wc_privacy_policy_page_id(),
				'icon_list'              => $this->checkout_botton_icon_list(),
			) );


			// Enqueue Block Editor Stylesheet
			wp_enqueue_style( 'wfacp-block-editor', $frontend_dir . $style_path, [], $version );

			if ( function_exists( 'wp_set_script_translations' ) ) {
				wp_set_script_translations( 'wfacp-block-editor', 'funnel-builder' );
			}

		}
	}

	public static function mini_cart_default_attrs() {
		return [
			'mini_cart_heading'            => __( 'Order Summary', 'funnel-builder' ),
			'enable_product_image'         => true,
			'enable_quantity_box'          => true,
			'enable_delete_item'           => false,
			'enable_coupon'                => true,
			'enable_coupon_collapsible'    => true,
			'mini_cart_coupon_button_text' => 'Apply'

		];
	}

	public function checkout_botton_icon_list() {
		$icon_list = [
			'\e902' => __( 'Arrow 1', 'funnel-builder' ),
			'\e906' => __( 'Arrow 2', 'funnel-builder' ),
			'\e907' => __( 'Arrow 3', 'funnel-builder' ),
			'\e908' => __( 'Checkmark', 'funnel-builder' ),
			'\e905' => __( 'Cart 1', 'funnel-builder' ),
			'\e901' => __( 'Lock 1', 'funnel-builder' ),
			'\e900' => __( 'Lock 2', 'funnel-builder' ),
		];

		return apply_filters( 'bwf_checkout_button_icon_list', $icon_list );
	}

	public static function form_attributes() {
		$template   = wfacp_template();
		$step_count = $template->get_step_count();
		$attributes = [];
		$labels     = [
			[
				'heading'     => __( 'SHIPPING', 'funnel-builder' ),
				'sub-heading' => __( 'Where to ship it?', 'funnel-builder' ),
			],
			[
				'heading'     => __( 'PRODUCTS', 'funnel-builder' ),
				'sub-heading' => __( 'Select your product', 'funnel-builder' ),
			],
			[
				'heading'     => __( 'PAYMENT', 'funnel-builder' ),
				'sub-heading' => __( 'Confirm your order', 'funnel-builder' ),
			],

		];
		$counter    = 1;
		for ( $i = 0; $i < $step_count; $i ++ ) {

			//Steps Attributes
			$attributes["step_{$i}_bredcrumb"]    = "Step $counter";
			$attributes["step_{$i}_progress_bar"] = "Step $counter";
			$attributes["step_{$i}_heading"]      = $labels[ $i ]['heading'];
			$attributes["step_{$i}_subheading"]   = $labels[ $i ]['sub-heading'];
			$counter ++;
		}

		//Payment Method Button Attributes
		for ( $i = 1; $i <= $step_count; $i ++ ) {
			$button_default_text = __( 'NEXT STEP →', 'funnel-builder' );
			$button_key          = 'wfacp_payment_button_' . $i . '_text';
			$button_icon_status  = 'enable_icon_with_place_order_' . $i;
			$button_icon_key     = 'icons_with_place_order_list_' . $i;
			if ( $i == $step_count ) {
				$button_key          = 'wfacp_payment_place_order_text';
				$button_default_text = __( 'PLACE ORDER NOW', 'funnel-builder' );
			}

			$button_subtext_key                = 'step_' . $i . '_text_after_place_order';
			$attributes[ $button_key ]         = esc_js( $button_default_text );
			$attributes[ $button_subtext_key ] = '';
			$attributes[ $button_icon_status ] = false;
			$attributes[ $button_icon_key ]    = '\e901';

			if ( $i > 1 ) {
				$backCount                                           = $i - 1;
				$attributes[ 'payment_button_back_' . $i . '_text' ] = sprintf( '« Return to Step %s ', $i - 1 );
				$attributes['return_to_cart_text']                   = __( '« Return to Cart', 'funnel-builder' );
			}
		}
		$attributes['text_below_placeorder_btn'] = sprintf( 'We Respect Your privacy & Information ', 'woofunnel-aero-checkout' );


		// Form Step/Heading Attributes
		$attributes['enable_progress_bar']         = false;
		$attributes['enable_progress_bar_tablet']  = false;
		$attributes['enable_progress_bar_mobile']  = false;
		$attributes['select_type']                 = 'tab';
		$attributes['step_cart_link_enable']       = 'yes';
		$attributes['step_cart_progress_bar_link'] = 'Cart';
		$attributes['step_cart_bredcrumb_link']    = 'Cart';

		//Payment Gateways Attributes
		$attributes['wfacp_payment_method_heading_text'] = esc_attr__( 'Payment Information', 'funnel-builder' );
		$attributes['wfacp_payment_method_subheading']   = esc_attr__( 'All transactions are secure and encrypted. Credit card information is never stored on our servers.', 'funnel-builder' );

		//Collapsible Order Summary Attributes
		$attributes['enable_callapse_order_summary']                = false;
		$attributes['enable_callapse_order_summary_tablet']         = true;
		$attributes['enable_callapse_order_summary_mobile']         = true;
		$attributes['cart_collapse_title']                          = __( 'Show Order Summary', 'funnel-builder' );
		$attributes['cart_expanded_title']                          = __( 'Hide Order Summary', 'funnel-builder' );
		$attributes['order_summary_enable_product_image_collapsed'] = true;
		$attributes['collapse_enable_coupon']                       = true;
		$attributes['collapse_enable_coupon_collapsible']           = false;
		$attributes['collapse_order_quantity_switcher']             = true;
		$attributes['collapse_order_delete_item']                   = true;
		$attributes['collapse_coupon_button_text']                  = 'Apply';
		$attributes['form_coupon_button_text']                      = 'Apply';


		$attributes['order_summary_enable_product_image'] = true;

		$attributes['wfacp_label_position'] = 'wfacp-inside';


		return $attributes;
	}

	public static function register_section_fields() {
		$data       = [
			'section'    => [],
			'attributes' => []
		];
		$attributes = [];

		$template = wfacp_template();
		if ( null == $template ) {
			return $data;
		}

		$steps = $template->get_fieldsets();

		$do_not_show_fields = WFACP_Common::get_html_excluded_field();
		$exclude_fields     = [];
		foreach ( $steps as $step_key => $fieldsets ) {
			foreach ( $fieldsets as $section_key => $section_data ) {
				if ( empty( $section_data['fields'] ) ) {
					continue;
				}
				$count            = count( $section_data['fields'] );
				$html_field_count = 0;


				if ( ! empty( $section_data['html_fields'] ) ) {
					foreach ( $do_not_show_fields as $h_key ) {
						if ( isset( $section_data['html_fields'][ $h_key ] ) ) {
							$html_field_count ++;
							self::$html_fields[ $h_key ] = true;

						}
					}
				}

				if ( $html_field_count == $count ) {
					continue;
				}

				if ( is_array( $section_data['fields'] ) && count( $section_data['fields'] ) > 0 ) {
					foreach ( $section_data['fields'] as $fkey => $fval ) {
						if ( isset( $fval['id'] ) && in_array( $fval['id'], $do_not_show_fields ) ) {
							$exclude_fields[]                 = $fval['id'];
							self::$html_fields[ $fval['id'] ] = true;
							continue;
						}
					}
				}

				if ( count( $exclude_fields ) == count( $section_data['fields'] ) ) {
					continue;
				}

				$panel             = $section_data['name'];
				$panel_data        = self::register_fields( $section_data['fields'] );
				$panel_fields      = isset( $panel_data['fields'] ) ? $panel_data['fields'] : [];
				$panel_attr        = isset( $panel_data['attributes'] ) ? $panel_data['attributes'] : [];
				$attributes        = array_merge( $attributes, $panel_attr );
				$data['section'][] = [
					'panel'  => $panel,
					'fields' => $panel_fields,
				];

			}
		}

		$data['attributes'] = $attributes;

		return $data;
	}

	public static function register_fields( $temp_fields ) {

		$field_data = [
			'fields'     => [],
			'attributes' => []
		];

		$template      = wfacp_template();
		$template_slug = $template->get_template_slug();
		$template_cls  = $template->get_template_fields_class();

		$default_cls        = $template->default_css_class();
		$do_not_show_fields = WFACP_Common::get_html_excluded_field();


		//$this->add_heading( __( 'Field Width', 'woofunnel-aero-checkout' ) );


		self::$section_fields[] = $temp_fields;
		foreach ( $temp_fields as $loop_key => $field ) {

			if ( in_array( $loop_key, [ 'wfacp_start_divider_billing', 'wfacp_start_divider_shipping' ], true ) ) {
				$address_key_group      = ( $loop_key == 'wfacp_start_divider_billing' ) ? __( 'Billing Address', 'funnel-builder' ) : __( 'Shipping Address', 'funnel-builder' );
				$field_data['fields'][] = [ 'heading' => $address_key_group ];
			}

			if ( ! isset( $field['id'] ) || ! isset( $field['label'] ) ) {
				continue;
			}

			$field_key         = $field['id'];
			$field_default_cls = '';

			if ( isset( $template_cls[ $field_key ] ) ) {
				$field_default_cls = $template_cls[ $field_key ]['class'];
			} else {
				$field_default_cls = $default_cls['class'];
			}

			if ( in_array( $field_key, $do_not_show_fields ) ) {
				self::$html_fields[ $field_key ] = true;
				continue;
			}


			$skipKey = [ 'billing_same_as_shipping', 'shipping_same_as_billing' ];
			if ( in_array( $field_key, $skipKey ) ) {
				continue;
			}

			$options = self::get_class_options();
			if ( isset( $field['type'] ) && 'wfacp_html' === $field['type'] ) {
				$options           = [
					[ 'label' => __( 'Full' ), 'value' => 'wfacp-col-full' ]
				];
				$field_default_cls = 'wfacp-col-full';
			}
			$options = apply_filters( 'wfacp_widget_fields_classes', $options, $field, self::get_class_options() );

			$field_data['attributes'][ 'wfacp_' . $template_slug . '_' . $field_key . '_field' ] = $field_default_cls;
			$field_data['fields'][]                                                              = [
				'id'      => 'wfacp_' . $template_slug . '_' . $field_key . '_field',
				'label'   => $field['label'],
				'options' => $options

			];
		}

		return $field_data;

	}

	public static function get_class_options() {
		return [
			[ 'label' => __( 'Full' ), 'value' => 'wfacp-col-full' ],
			[ 'label' => __( 'One Half' ), 'value' => 'wfacp-col-left-half' ],
			[ 'label' => __( 'One Third' ), 'value' => 'wfacp-col-left-third' ],
			[ 'label' => __( 'Two Third' ), 'value' => 'wfacp-col-two-third' ],
		];
	}

	public static function class_section() {
		$template           = wfacp_template();
		$template_slug      = $template->get_template_slug();
		$do_not_show_fields = WFACP_Common::get_html_excluded_field();

		$section_data = [
			'section'    => [
				[
					'panel'  => __( 'Field Classes', 'funnel-builder' ),
					'fields' => []
				]
			],
			'attributes' => []
		];

		$sections = self::$section_fields;
		foreach ( $sections as $keys => $val ) {
			foreach ( $val as $loop_key => $field ) {
				if ( in_array( $loop_key, [ 'wfacp_start_divider_billing', 'wfacp_start_divider_shipping' ], true ) ) {
					$address_key_group                      = ( $loop_key == 'wfacp_start_divider_billing' ) ? __( 'Billing Address', 'funnel-builder' ) : __( 'Shipping Address', 'funnel-builder' );
					$section_data['section'][0]['fields'][] = [ 'heading' => $address_key_group ];
				}

				if ( ! isset( $field['id'] ) || ! isset( $field['label'] ) ) {
					continue;
				}

				$field_key = $field['id'];

				if ( in_array( $field_key, $do_not_show_fields ) ) {
					self::$html_fields[ $field_key ] = true;
					continue;
				}


				$skipKey = [ 'billing_same_as_shipping', 'shipping_same_as_billing' ];
				if ( in_array( $field_key, $skipKey ) ) {
					continue;
				}
				$section_data['attributes'][ 'wfacp_' . $template_slug . '_' . $field_key . '_field_class' ] = '';
				$section_data['section'][0]['fields'][]                                                      = [
					'id'          => 'wfacp_' . $template_slug . '_' . $field_key . '_field_class',
					'label'       => $field['label'],
					'placeholder' => __( 'Custom Class', 'funnel-builder' )

				];
			}
		}

		return $section_data;

	}


	/**
	 * Enqueue Front Style.
	 */
	public function enqueue_block_front_assets() {
		global $post;

		if ( ! ( $post instanceof WP_Post ) || WFACP_Common::get_post_type_slug() !== $post->post_type ) {
			return false;
		}

		// Enable Gutenberg and WooCommerce block styling
		add_filter( 'wfacp_css_js_removal_paths', [ $this, 'remove_js_css_from_editor' ] );

		// Enqueue our plugin Css.
		$wfacp_assets_dir = defined( 'BWF_AERO_REACT_ENVIRONMENT' ) ? BWF_AERO_REACT_ENVIRONMENT : plugin_dir_url( __FILE__ ) . '/dist';

		$stylesheet_file = '/wfacp-block-front.css';

		wp_enqueue_style( 'wfacp-block-front', $wfacp_assets_dir . $stylesheet_file, [ 'wp-block-library', 'wp-block-library-theme' ], time() );

		//Load block font 
		require_once( __DIR__ . '/font/fonts.php' );

	}


	public function setup_global_checkout( $post_id ) {
		$design = WFACP_Common::get_page_design( $post_id );

		if ( 'gutenberg' === $design['selected_type'] ) {

			$this->wfacp_id = $post_id;
			global $post;
			$post       = get_post( $this->wfacp_id );
			$this->post = $post;
			add_filter( 'the_content', [ $this, 'change_global_post_var_to_our_page_post' ], 5 );
		}
	}

	public function change_global_post_var_to_our_page_post( $content ) {
		if ( 0 === did_action( 'wfacp_after_template_found' ) ) {
			return $content;
		}
		global $post;
		if ( ! is_null( $this->post ) ) {
			$post    = $this->post;
			$content = $post->post_content;
		} else {
			$post    = get_post( $this->wfacp_id );
			$content = $post->post_content;
		}

		return $content;
	}


	/**
	 * Delete oxy saved data from postmeta of aerocheckout ID
	 */
	public function delete_gutenberg_data( $wfacp_id ) {
		$design = WFACP_Common::get_page_design( $wfacp_id );
		if ( 'gutenberg' === $design['selected_type'] ) {
			$post               = get_post( $wfacp_id );
			$post->post_content = '';
			wp_update_post( $post );
		}
	}

	public function bwf_blocks_admin_body_class( $classes ) {
		$screen = get_current_screen();
		if ( 'post' == $screen->base && WFACP_Common::get_post_type_slug() === $screen->post_type ) {
			global $post;
			$template_file = get_post_meta( $post->ID, '_wp_page_template', true );
			if ( 'wfacp-canvas.php' === $template_file ) {
				$classes .= ' bwf-editor-width-canvas';
			}
			if ( 'wfacp-full-width.php' === $template_file ) {
				$classes .= ' bwf-editor-width-boxed';
			}
			$classes .= ' wfacp_editor_active';

		}

		return $classes;

	}

	public function duplicate_template( $new_post_id, $post_id, $data ) {
		if ( 'gutenberg' === $data['_wfacp_selected_design']['selected_type'] ) {
			$post     = get_post( $post_id );
			$post->ID = $new_post_id;
			wp_update_post( $post );

			$data = [
				'_wp_page_template' => get_post_meta( $post_id, '_wp_page_template', true ),
			];

			foreach ( $data as $meta_key => $meta_value ) {
				update_post_meta( $new_post_id, $meta_key, $meta_value );
			}

		}
	}

	public function only_add_to_cart_product() {
		if ( isset( $_GET['gutenberg_iframe_preview'] ) ) {
			exit;
		}
	}

	public function remove_js_css_from_editor( $paths ) {
		if ( false !== array_search( "/block-library/", $paths ) ) {
			unset( $paths[ array_search( "/block-library/", $paths ) ] );
		}
		if ( false !== array_search( "/woocommerce-blocks/", $paths ) ) {
			unset( $paths[ array_search( "/woocommerce-blocks/", $paths ) ] );
		}
		if ( false !== array_search( "/woo-gutenberg-products-block/", $paths ) ) {
			unset( $paths[ array_search( "/woo-gutenberg-products-block/", $paths ) ] );
		}

		return $paths;
	}

	public function builder_actions( $post, $json ) {
		add_filter( 'wfacp_forms_field', function ( $field, $key ) use ( $json ) {

			return $this->modern_label( $field, $key, $json );
		}, 20, 2 );
	}

	public function modern_label( $field, $key, $data ) {
		if ( empty( $field ) ) {
			return $field;
		}

		if ( 'wfacp-modern-label' != $data['wfacp_label_position'] || ! isset( $field['placeholder'] ) ) {
			return $field;
		}

		return WFACP_Common::live_change_modern_label( $field );
	}

	public function migrate_label( $post_ID, $post, $update ) {
		if ( false == $update ) {
			return;
		}

		if ( ! is_null( $post ) ) {
			if ( false !== strpos( $post->post_content, 'wfacp-modern-label' ) ) {
				$field_label = 'wfacp-modern-label';
				WFACP_Common_Helper::modern_label_migrate( $post_ID );
			} else if ( false !== strpos( $post->post_content, 'wfacp-top' ) ) {
				$field_label = 'wfacp-top';
			} else {
				$field_label = 'wfacp-inside';
			}
			update_post_meta( $post_ID, '_wfacp_field_label_position', $field_label );
		}


	}

}

WFACP_GutenBerg::get_instance();