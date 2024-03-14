<?php

#[AllowDynamicProperties]

  class WFTY_Gutenberg {
	private static $ins = null;
	public $modules_instance = [];
	private $post = null;
	protected $widgets_json = [];
	private $url = '';

	private function __construct() {
		$this->url = plugin_dir_url( __FILE__ );

		$this->define_constant();
		$this->register();

	}

	private function define_constant() {
		! defined( 'WFTY_GUTENBURG_DIR' ) && define( 'WFTY_GUTENBURG_DIR', plugin_dir_path( __FILE__ ) );
		! defined( 'WFTY_GUTENBURG_URL' ) && define( 'WFTY_GUTENBURG_URL', plugin_dir_url( __FILE__ ) );
	}

	public static function get_instance() {
		if ( is_null( self::$ins ) ) {
			self::$ins = new self();
		}

		return self::$ins;

	}


	private function register() {
		/* Remove Upstroke theme compatability css */
		if ( version_compare( $GLOBALS['wp_version'], '5.8-alpha-1', '<' ) ) {
			add_filter( 'block_categories', array( $this, 'add_block_categories' ), 11, 1 );
		} else {
			add_filter( 'block_categories_all', array( $this, 'add_block_categories' ), 11, 1 );
		}

		add_filter( 'admin_body_class', [ $this, 'bwf_blocks_admin_body_class' ] );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_block_front_assets' ) );
		add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_block_editor_assets' ), 30 );

		$this->load_require_files();

	}

	public function add_default_templates() {
		$template = [
			'slug'        => 'gutenberg',
			'title'       => __( 'Block Editor', 'funnel-builder' ),
			'button_text' => __( 'Edit', 'funnel-builder' ),
			'description' => __( 'Use block editor modules to create your own designs. Or pick from professionally-designed templates.', 'funnel-builder' ),
			'edit_url'    => add_query_arg( [
				'action' => 'edit',
				'post'   => $this->edit_id
			], admin_url( 'post.php' ) ),
		];

		WFFN_Core()->thank_you_pages->register_template_type( $template );
		$templates = WooFunnels_Dashboard::get_all_templates();
		$designs   = isset( $templates['wc_thankyou'] ) ? $templates['wc_thankyou'] : [];

		if ( isset( $designs['gutenberg'] ) && is_array( $designs['gutenberg'] ) ) {
			foreach ( $designs['gutenberg'] as $d_key => $templates ) {

				if ( isset( $templates['pro'] ) && 'yes' === $templates['pro'] ) {
					$templates['license_exist'] = WFFN_Core()->admin->get_license_status();
				}
				WFFN_Core()->thank_you_pages->register_template( $d_key, $templates, 'gutenberg' );

			}
		} else {

			$empty_template = [
				"type"               => "view",
				"import"             => "no",
				"show_import_popup"  => "no",
				"slug"               => "gutenberg_1",
				"build_from_scratch" => true,

			];
			WFFN_Core()->thank_you_pages->register_template( 'gutenberg_1', $empty_template, 'gutenberg' );
		}

		return [];
	}

	public function init_loaded() {
		// Register Gutenberg Block Meta for Editor Width
		register_post_meta( '', 'bwfblock_default_font', array(
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'string',
		) );
	}

	/**
	 * Add custom category
	 *
	 * @param array $categories category list.
	 * @param WP_Post $post post object.
	 */
	public function add_block_categories( $categories ) {
		if ( false !== array_search( 'woofunnels', array_column( $categories, 'slug' ), true ) ) {
			return $categories;
		} else {
			return array_merge( array(
				array(
					'slug'  => 'woofunnels',
					'title' => esc_html__( 'FunnelKit', 'bwf-gutenberg-block' ),
				),
			), $categories );
		}

	}

	// Add class in editor body
	public function bwf_blocks_admin_body_class( $classes ) {
		$screen = get_current_screen();
		if ( 'post' === $screen->base && WFFN_Thank_You_WC_Pages::get_post_type_slug() === $screen->post_type ) {
			global $post;
			$template_file = get_post_meta( $post->ID, '_wp_page_template', true );
			if ( 'wftp-canvas.php' === $template_file ) {
				$classes .= ' bwf-editor-width-canvas';
			}
			if ( 'wftp-boxed.php' === $template_file ) {
				$classes .= ' bwf-editor-width-boxed';
			}

		}

		return $classes;

	}


	public function load_require_files() {
		//load necessary files
		require_once __DIR__ . '/includes/functions.php';
		require_once __DIR__ . '/includes/class-bwf-blocks-css.php';
		require_once __DIR__ . '/includes/class-bwf-blocks-frontend-css.php';
		require_once __DIR__ . '/includes/class-render-blocks.php';
	}

	/**
	 * Load assets for wp-admin when editor is active.
	 */
	public function enqueue_block_editor_assets() {

		global $pagenow, $post;

		if ( class_exists( 'WFFN_Thank_You_WC_Pages' ) && ! is_null( $post ) && WFFN_Thank_You_WC_Pages::get_post_type_slug() === $post->post_type && 'post.php' === $pagenow && isset( $_GET['post'] ) && intval( $_GET['post'] ) > 0 ) { //phpcs:ignore

			defined( 'BWF_I18N' ) || define( 'BWF_I18N', 'funnel-builder' );
			$app_name     = 'wfty-block-editor';
			$frontend_dir = defined( 'WFTY_REACT_ENVIRONMENT' ) ? WFTY_REACT_ENVIRONMENT : $this->url . 'dist';

			$assets_path = $frontend_dir . "/$app_name.asset.php";

			$assets = file_exists( $assets_path ) ? include $assets_path : array(
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

			$js_path    = "/$app_name.js";
			$style_path = "/$app_name.css";

			$deps    = ( isset( $assets['dependencies'] ) ? array_merge( $assets['dependencies'], array( 'jquery' ) ) : array( 'jquery' ) );
			$deps    = array_merge( $deps, array( 'bwf-font-awesome-kit' ) );
			$version = time();

			$script_deps = array_filter( $deps, function ( $dep ) {
				return false === strpos( $dep, 'css' );
			} );

			wp_register_script( 'bwf-font-awesome-kit', 'https://kit.fontawesome.com/f4306c3ab0.js', // Our free kit https://fontawesome.com/kits/f4306c3ab0/settings
				null, null, true );
			wp_enqueue_script( 'wfty-block-editor', $frontend_dir . $js_path, $script_deps, $version, true );

			wp_enqueue_script( 'web-font', 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js', array(), true );

			$exp_date = gmdate( 'Y-m-d H:i:s', strtotime( '+10 days' ) );
			if ( ! empty( $exp_date ) ) {
				$exp_date = date_i18n( get_option( 'date_format' ), strtotime( $exp_date ) );
			} else {
				$exp_date = __( 'Never', 'funnel-builder' );
			}

			//Localized data for ThankYou Guten Blocks
			$customer_details = array(
				'shipping' => false
			);
			$order_details    = array(
				'price'          => '12.00',
				'total_price'    => '12.00',
				'shipping_price' => '3.00',
				'shipping'       => 'false',
				'currency'       => html_entity_decode( get_woocommerce_currency_symbol() ),
				'img_url'        => WC()->plugin_url() . '/assets/images/placeholder.png',
				'pro_name'       => __( 'Test Product', 'funnel-builder' ),
				'sub_head'       => __( 'Subtotal', 'funnel-builder' ),
				'ship_head'      => __( 'Shipping', 'funnel-builder' ),
				'ship_text'      => '',
				'payment_head'   => __( 'Payment method', 'funnel-builder' ),
				'payment_text'   => __( 'Credit Card', 'funnel-builder' ),
				'total_head'     => __( 'Total', 'funnel-builder' ),
				'subs_head'      => __( 'Related Subscriptions', 'funnel-builder' ),
				'subs_th_title'  => __( 'Subscription', 'funnel-builder' ),
				'subs_th_pay'    => __( 'Next Payment', 'funnel-builder' ),
				'subs_th_tot'    => __( 'Total', 'funnel-builder' ),
				'subs_th_act'    => __( 'Action', 'funnel-builder' ),
				'subs_td_title'  => __( 'Active', 'funnel-builder' ),
				'subs_td_pay'    => __( 'In 24 hours', 'funnel-builder' ),
				'subs_td_tot'    => __( '7.50 /day', 'funnel-builder' ),
				'subs_td_act'    => __( 'View', 'funnel-builder' ),
				'down_th_file'   => __( 'File', 'funnel-builder' ),
				'down_th_down'   => __( 'Downloads remaining', 'funnel-builder' ),
				'down_th_exp'    => __( 'Expires', 'funnel-builder' ),
				'down_td_file'   => __( 'Your_file_name.pdf', 'funnel-builder' ),
				'down_td_exp'    => $exp_date,
			);

			$shipping_option = get_option( 'woocommerce_ship_to_countries' );
			if ( 'disabled' !== $shipping_option ) {
				$order_details['total_price'] += $order_details['shipping_price'];
				$order_details['total_price'] = $order_details['total_price'] . '.00';
				$order_details['shipping']    = 'true';
				$customer_details['shipping'] = true;
			}


			wp_localize_script( 'wfty-block-editor', 'wfty_blocks', array(
				'i18n'             => BWF_I18N,
				'bwf_g_fonts'      => bwf_get_fonts_list( 'all' ),
				'bwf_g_font_names' => bwf_get_fonts_list( 'name_only' ),
				'order_details'    => $order_details,
				'cust_details'     => $customer_details,
				'wp_version'       => $GLOBALS['wp_version'],
			) );
			// Enqueue our plugin Css.
			wp_enqueue_style( 'wfty-block-editor', $frontend_dir . $style_path, array(), $version );

			if ( function_exists( 'wp_set_script_translations' ) ) {
				wp_set_script_translations( 'wfty-block-editor', BWF_I18N );
			}


			if ( defined( 'WFTY_PLUGIN_FILE' ) ) {
				wp_enqueue_style( 'wffn_frontend_tp_css', plugin_dir_url( WFTY_PLUGIN_FILE ) . '/assets/css/style.css', [], time() );
			}

		}
	}


	/**
	 * Enqueue Front Style.
	 */
	public function enqueue_block_front_assets() {
		global $post;

		if ( is_null( $post ) || WFFN_Thank_You_WC_Pages::get_post_type_slug() !== $post->post_type ) {
			return false;
		}
		$design = WFFN_Core()->thank_you_pages->get_page_design( $post->ID );
		if ( ! isset( $design['selected_type'] ) || 'gutenberg' !== $design['selected_type'] ) {
			return;
		}
		// Enqueue our plugin Css.
		$frontend_dir = defined( 'WFTY_REACT_ENVIRONMENT' ) ? WFTY_REACT_ENVIRONMENT : $this->url . 'dist';

		$stylesheet_file = '/wfty-block-front.css';

		wp_enqueue_style( 'wfty-block-front', $frontend_dir . $stylesheet_file, array(), time() );

		// Load block font family
		require_once( __DIR__ . '/font/fonts.php' );

	}

	public function bwf_render_default_font() {
		global $post;
		$default_font = get_post_meta( $post->ID, 'bwfblock_default_font', true );

		if ( ! empty( $default_font ) ) {
			echo "<style id='bwfblock-default-font'>#editor .editor-styles-wrapper { font-family:$default_font; }</style>"; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	public function allow_theme_css( $is, $post_id ) {

		$design = WFFN_Core()->thank_you_pages->get_page_design( $post_id );
		if ( is_array( $design ) && isset( $design['selected_type'] ) && 'gutenberg' === $design['selected_type'] ) {
			return true;
		}

		return $is;
	}

}

WFTY_Gutenberg::get_instance();
