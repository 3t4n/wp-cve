<?php

/**
 * Class Gutenberg
 */
#[AllowDynamicProperties]

  class WFOP_Gutenberg {
	/**
	 * @var string $ins | Instance.
	 */
	private static $ins = null;

	/**
	 * @var array $modules_instance | Instance Array.
	 */
	public $modules_instance = [];

	/**
	 * @var object $post | Post Object.
	 */
	private $post = null;

	/**
	 * @var array $widgets_json | Widgets Json.
	 */
	protected $widgets_json = [];


	private $url = '';

	/**
	 * Class constructor
	 */
	private function __construct() {

		$this->register();
	}


	/**
	 * Get Class Instance
	 */
	public static function get_instance() {
		if ( is_null( self::$ins ) ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/**
	 * Register
	 */
	private function register() {
		$this->url = plugin_dir_url( __FILE__ );



		add_action( 'plugins_loaded', array( $this, 'load_require_files' ), 21 );
		add_action( 'init', array( $this, 'init_extension' ), 21 );
		if ( version_compare( get_bloginfo( 'version' ), '5.8', '>=' ) ) {
			add_filter( 'block_categories_all', array( $this, 'add_category' ), 11, 2 );
		} else {
			add_filter( 'block_categories', array( $this, 'add_category' ), 11, 2 );
		}

		add_filter( 'admin_body_class', [ $this, 'bwf_blocks_admin_body_class' ] );
		/* show a section in +Add */
		add_action( 'wffn_step_duplicated', [ $this, 'assign_empty_template' ] );
		add_action( 'wfop_page_design_updated', [ $this, 'assign_empty_template' ] );
	}

	/**
	 * Add custom category
	 *
	 * @param array $categories category list.
	 * @param object WP_Post $post post object.
	 *
	 */
	public function add_category( $categories ) {
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

	public function assign_empty_template( $wfop_id ) {
		if ( $wfop_id < 1 ) {
			return;
		}

	}

	/**
	 * Load Regquired Files
	 */
	public function load_require_files() {

		//load necessary files.
		if ( ! is_admin() ) {
			require_once __DIR__ . '/includes/functions.php';
			require_once __DIR__ . '/includes/class-bwf-blocks-css.php';
			require_once __DIR__ . '/includes/class-bwf-blocks-frontend-css.php';
			require_once __DIR__ . '/includes/class-render-blocks.php';
		}
	}

	/**
	 * Register Templates
	 */
	public function add_default_templates() {

		$template = array(
			'slug'        => 'gutenberg',
			'title'       => __( 'Block Editor', 'funnel-builder' ),
			'button_text' => __( 'Edit', 'funnel-builder' ),
			'edit_url'    => add_query_arg( array(
				'post'   => WFOPP_Core()->optin_pages->get_edit_id(),
				'action' => 'edit',
			), admin_url( 'post.php' ) ),
		);
		WFOPP_Core()->optin_pages->register_template_type( $template );
		$templates = WooFunnels_Dashboard::get_all_templates();
		$designs   = isset( $templates['optin'] ) ? $templates['optin'] : [];
		if ( isset( $designs['gutenberg'] ) && is_array( $designs['gutenberg'] ) ) {
			foreach ( $designs['gutenberg'] as $d_key => $templates ) {
				if ( isset( $templates['pro'] ) && 'yes' === $templates['pro'] ) {
					$templates['license_exist'] = WFFN_Core()->admin->get_license_status();
				}
				WFOPP_Core()->optin_pages->register_template( $d_key, $templates, 'gutenberg' );
			}

		} else {

			$empty_template = array(
				'name'               => __( 'Start from scratch', 'funnel-builder' ),
				'show_import_popup'  => 'no',
				'slug'               => 'gutenberg_1',
				'build_from_scratch' => true,
				'group'              => array( 'inline', 'popup' ),
			);
			WFOPP_Core()->optin_pages->register_template( 'gutenberg_1', $empty_template, 'gutenberg' );
		}
	}

	/**
	 * Load assets for wp-admin when editor is active.
	 */
	public function admin_script_style() {

		global $pagenow, $post;

		if ( WFOPP_Core()->optin_pages->get_post_type_slug() === $post->post_type && 'post.php' === $pagenow && isset( $_GET['post'] ) && intval( $_GET['post'] ) > 0 ) { //phpcs:ignore

			defined( 'BWF_I18N' ) || define( 'BWF_I18N', 'funnel-builder' );
			$app_name = 'optin-form-block';

			$frontend_dir = defined( 'BWFOP_FORM_REACT_ENVIRONMENT' ) ? BWFOP_FORM_REACT_ENVIRONMENT : $this->url . 'dist';

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
			$version = $assets['version'];

			$script_deps = array_filter( $deps, function ( $dep ) {
				return false === strpos( $dep, 'css' );
			} );

			wp_enqueue_script( 'wfoptin-script', $frontend_dir . $js_path, $script_deps, $version, true );


			wp_enqueue_style( 'wfoptin-default', $frontend_dir . $style_path, array(), $version );


			$system_font_path = __DIR__ . '/font/standard-fonts.php';
			wp_enqueue_script( 'wfoptin-font', 'https://ajax.googleapis.com/ajax/libs/webfont/1.6.26/webfont.js', array(), true );

			wp_enqueue_script( 'wfoptin-font-awesome-kit', 'https://kit.fontawesome.com/f4306c3ab0.js', // Our free kit https://fontawesome.com/kits/f4306c3ab0/settings
				null, null, true );


			wp_enqueue_style( 'wfoptin-fonts', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css' );
			$get_fields = WFOPP_Core()->optin_pages->form_builder->get_form_fields( $post->ID );
			wp_localize_script( 'wfoptin-script', 'bwf_funnels_widgets', $this->widgets_json );
			wp_localize_script( 'wfoptin-script', 'bwfop_funnels_data', [
				'post_id'          => $post->ID,
				'i18n'             => BWF_I18N,
				'get_fields'       => $get_fields,
				'first_name'       => esc_html__( 'Your First Name', BWF_I18N ),
				'email'            => esc_html__( 'Your Email', BWF_I18N ),
				'bwf_g_fonts'      => bwf_get_fonts_list( 'all' ),
				'bwf_g_font_names' => bwf_get_fonts_list( 'name_only' ),
				'system_font_path' => file_exists( $system_font_path ) ? include $system_font_path : array(),
				'wp_version'       => $GLOBALS['wp_version'],
			] );


			if ( defined( 'WFOPP_PRO_PLUGIN_FILE' ) ) {
				wp_enqueue_script( 'phone_flag_intl', plugin_dir_url( WFOPP_PRO_PLUGIN_FILE ) . 'assets/phone/js/intltelinput.min.js', array(), WFFN_VERSION_DEV );
				wp_enqueue_style( 'flag_style', plugin_dir_url( WFOPP_PRO_PLUGIN_FILE ) . 'assets/phone/css/phone-flag.css', array(), WFFN_VERSION_DEV );
			}

			if ( function_exists( 'wp_set_script_translations' ) ) {
				wp_set_script_translations( 'wfoptin-script', 'funnel-builder' );
			}

		}

	}


	/**
	 * Init Extension
	 */
	public function init_extension() {

		$post_id = 0;
		if ( isset( $_REQUEST['post'] ) && $_REQUEST['post'] > 0 ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_id = absint( $_REQUEST['post'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		} else if ( isset( $_REQUEST['edit'] ) && $_REQUEST['edit'] > 0 ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_id = absint( $_REQUEST['edit'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}

		$post = get_post( $post_id );
		if ( ! is_null( $post ) && $post->post_type === WFOPP_Core()->optin_pages->get_post_type_slug() ) {

			$this->post = $post;
			$this->prepare_module();

			return;
		}

		add_action( 'wp', [ $this, 'prepare_frontend_module' ], - 5 );


	}

	/**
	 * Prepare Frontend Module
	 */
	public function prepare_frontend_module() {
		global $post;
		if ( is_null( $post ) ) {
			return;
		}
		$this->post = $post;

		if ( $post->post_type === WFOPP_Core()->optin_pages->get_post_type_slug() ) {

			if ( current_action() === 'wp' && ! is_admin() ) {
				$this->register_scripts();
			}

		}

		$this->prepare_module();
	}

	/**
	 * Prepare Module
	 */
	public function prepare_module() {
		if ( is_null( $this->post ) ) {
			return;
		}

		$id   = $this->post->ID;
		$data = get_post_meta( $id, '_wfop_selected_design', true );

		$design = apply_filters( 'get_offer', $data, $id );

		if ( empty( $design ) || empty( $design['selected_type'] ) ) {
			return;
		}

		if ( 'wp_editor' === $design['selected_type'] || 'gutenberg' === $design['selected_type'] ) {
			add_action( 'enqueue_block_editor_assets', [ $this, 'admin_script_style' ] );
		}

		register_post_meta( '', 'bwfblock_default_font', array(
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'string',
		) );

	}

	/**
	 * Register Scripts
	 */
	private function register_scripts() {

		if ( is_null( $this->post ) ) {
			return;
		}

		$id   = $this->post->ID;
		$data = get_post_meta( $id, '_wfop_selected_design', true );

		$design = apply_filters( 'get_offer', $data, $id );

		if ( empty( $design ) || empty( $design['selected_type'] ) ) {
			return;
		}

		if ( 'wp_editor' === $design['selected_type'] || 'gutenberg' === $design['selected_type'] ) {

			defined( 'BWF_I18N' ) || define( 'BWF_I18N', 'funnel-builder' );
			$app_name = 'optin-form-public';

			$frontend_dir = defined( 'BWFOP_FORM_REACT_ENVIRONMENT' ) ? BWFOP_FORM_REACT_ENVIRONMENT : $this->url . 'dist';
			$style_path   = "/$app_name.css";
			$version      = time();
			wp_enqueue_style( 'bwf-optin-block-style', $frontend_dir . $style_path, array(), $version );

			// load block font family
			require_once __DIR__ . '/font/fonts.php';

		}

	}

	public function bwf_blocks_admin_body_class( $classes ) {
		$screen = get_current_screen();
		if ( 'post' === $screen->base && WFOPP_Core()->optin_pages->get_post_type_slug() === $screen->post_type ) {
			global $post;
			$template_file = get_post_meta( $post->ID, '_wp_page_template', true );
			if ( 'wfop-canvas.php' === $template_file ) {
				$classes .= ' bwf-editor-width-canvas';
			}
			if ( 'wfop-boxed.php' === $template_file ) {
				$classes .= ' bwf-editor-width-boxed';
			}

		}

		return $classes;

	}

	public function bwf_render_default_font() {
		global $post;
		$default_font = get_post_meta( $post->ID, 'bwfblock_default_font', true );

		if ( ! empty( $default_font ) ) {
			echo "<style id='bwfblock-default-font'>#editor .editor-styles-wrapper { font-family:$default_font; }</style>"; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	public function allow_theme_css( $is, $post_id ) {

		$design = WFOPP_Core()->optin_pages->get_page_design( $post_id );
		if ( is_array( $design ) && isset( $design['selected_type'] ) && 'gutenberg' === $design['selected_type'] ) {
			return true;
		}

		return $is;
	}

}

WFOP_Gutenberg::get_instance();
