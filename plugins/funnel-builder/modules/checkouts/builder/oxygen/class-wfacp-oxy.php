<?php

class WFACP_OXY {
	private static $ins = null;
	private $is_oxy = false;
	private static $front_locals = [];
	private $template_file = '';
	private $wfacp_id = 0;
	private $section_slug = "woofunnels";
	private $tab_slug = "woofunnels";
	public $modules_instance = [];
	private $post = null;

	private function __construct() {
		$this->template_file = __DIR__ . '/template/template.php';
		add_action( 'wp_ajax_wfacp_import_template', [ $this, 'setup_oxygen_widgets' ], 9 );
		add_action( 'wfacp_checkout_page_found', [ $this, 'setup_global_checkout' ] );
		add_action( 'wfacp_template_removed', [ $this, 'delete_oxy_data' ] );
		add_action( 'wfacp_duplicate_pages', [ $this, 'duplicate_template' ], 10, 3 );

		$this->register();
	}

	public static function get_instance() {
		if ( is_null( self::$ins ) ) {
			self::$ins = new self();
		}

		return self::$ins;

	}

	private function register() {
		add_action( 'admin_bar_menu', [ $this, 'add_admin_bar_link' ], 1003 );

		add_action( 'init', [ $this, 'init_extension' ], 21 );
		add_filter( 'wfacp_is_theme_builder', [ $this, 'is_oxy_page' ] );
		add_filter( 'wfacp_post', [ $this, 'check_current_page_is_aero_page' ] );
		add_action( 'wfacp_register_template_types', [ $this, 'register_template_type' ], 19 );
		add_filter( 'wfacp_register_templates', [ $this, 'register_templates' ] );
		add_action( 'wfacp_template_load', [ $this, 'load_oxy_abs_class' ], 10, 2 );
		add_filter( 'wfacp_template_edit_link', [ $this, 'add_template_edit_link' ], 10, 2 );
		add_action( 'oxygen_enqueue_frontend_scripts', [ $this, 'enable_self_page_css' ] );
	}

	private function importer() {
		add_action( 'wp_loaded', [ $this, 'load_oxy_importer' ], 150 );
	}

	public function load_oxy_importer() {
		require __DIR__ . '/class-wfacp-oxy-importer.php';
	}

	public static function set_locals( $name, $id ) {
		self::$front_locals[ $name ] = $id;
	}

	public static function get_locals() {
		return self::$front_locals;

	}

	public function is_oxy_page( $status ) {

		// At load
		if ( isset( $_REQUEST['ct_builder'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$this->is_oxy = true;
			$status       = true;

		}
		// when ajax running for form html
		if ( isset( $_REQUEST['action'] ) && ( 'set_oxygen_edit_post_lock_transient' === $_REQUEST['action'] || false !== strpos( $_REQUEST['action'], 'oxy_render_' ) || false !== strpos( $_REQUEST['action'], 'oxy_load_controls_oxy' ) ) ) {//phpcs:ignore
			$this->is_oxy = true;
			$status       = true;
		}


		if ( true === $status ) {
			add_filter( 'wfacp_wc_photoswipe_enable', '__return_false' );
		}

		return $status;
	}

	public function check_current_page_is_aero_page( $post ) {
		if ( WFACP_Common::is_theme_builder() && true === $this->is_oxy ) {

			if ( isset( $_REQUEST['post'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$temp_id = absint( $_REQUEST['post'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			} else if ( isset( $_REQUEST['post_id'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$temp_id = absint( $_REQUEST['post_id'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			} elseif ( isset( $_REQUEST['editor_post_id'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$temp_id = absint( $_REQUEST['editor_post_id'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			} else {
				$temp_id = 0;
			}
			$post_new = get_post( $temp_id );
			if ( $post_new instanceof WP_Post && WFACP_Common::get_post_type_slug() === $post_new->post_type ) {
				$post = $post_new;
			}
		}

		return $post;
	}


	/**
	 * @param $loader WFACP_Template_loader
	 */
	public function register_template_type( $loader ) {
		$template = [
			'slug'    => 'oxy',
			'title'   => __( 'Oxygen', 'woofunnels-aero-checkout' ),
			'filters' => WFACP_Common::get_template_filter()
		];

		$loader->register_template_type( $template );
	}

	public function register_templates( $designs ) {


		$templates      = WooFunnels_Dashboard::get_all_templates();
		$designs['oxy'] = ( isset( $templates['wc_checkout'] ) && isset( $templates['wc_checkout']['oxy'] ) ) ? $templates['wc_checkout']['oxy'] : [];

		if ( is_array( $designs['oxy'] ) && count( $designs['oxy'] ) > 0 ) {
			foreach ( $designs['oxy'] as $key => $val ) {
				$val['path']            = $this->template_file;
				$designs['oxy'][ $key ] = $val;
			}
		}


		return $designs;


	}


	public function load_oxy_abs_class( $wfacp_id, $template = [] ) {
		if ( empty( $template ) ) {
			return;
		}
		if ( 'oxy' === $template['selected_type'] ) {
			include_once __DIR__ . ( '/class-wfacp-oxy-template.php' );
		}
	}

	public function add_template_edit_link( $links, $admin ) {
		$url          = add_query_arg( [
			'ct_builder'   => 'true',
			'oxy_wfacp_id' => $admin->wfacp_id
		], get_the_permalink( $admin->wfacp_id ) );
		$links['oxy'] = [ 'url' => $url, 'button_text' => __( 'Edit', 'woofunnles-aero-checkout' ) ];

		return $links;
	}

	public static function is_template_editor() {
		return isset( $_REQUEST['action'] ) && ( 'ct_save_components_tree' == $_REQUEST['action'] || 'ct_render_innercontent' == $_REQUEST['action'] );
	}

	public function init_extension() {
		if ( self::is_template_editor() ) {
			// Only Run Template Preview Section Displayed
			add_action( 'wfacp_after_template_found', [ $this, 'prepare_module' ] );

			return;
		}
		if ( ! class_exists( 'CT_Component' ) ) {
			return;
		}

		$post_id = 0;
		if ( isset( $_REQUEST['post_id'] ) && $_REQUEST['post_id'] > 0 ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_id = absint( $_REQUEST['post_id'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		} elseif ( isset( $_REQUEST['oxy_wfacp_id'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_id = absint( $_REQUEST['oxy_wfacp_id'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		} elseif ( isset( $_REQUEST['post'] ) && $_REQUEST['post'] > 0 && isset( $_REQUEST['action'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			$post_id = absint( $_REQUEST['post'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
		}


		if ( wp_doing_ajax() && isset( $_REQUEST['wfacp_id'] ) ) {
			$post_id = $_REQUEST['wfacp_id'];
		}


		if ( $post_id > 0 ) {
			$status = $this->editor_prepare_module( $post_id );
			if ( true == $status ) {
				return true;
			}
		}

		add_action( 'wfacp_after_template_found', [ $this, 'prepare_module' ] );


	}

	/**
	 * Only run when oxygen builder importer running . Widget need to ready before builder_json_prepare
	 * @return void
	 */
	public function setup_oxygen_widgets() {
		if ( isset( $_POST['builder'] ) && 'oxy' == $_POST['builder'] && class_exists( 'OxyEl' ) ) {
			$post_id = absint( $_POST['wfacp_id'] );
			WFACP_Common::set_id( $post_id );
			WFACP_Core()->template_loader->load_template( $post_id );
			$this->run_widgets();
		}
	}

	public function editor_prepare_module( $post_id ) {
		$post = get_post( $post_id );
		if ( ! is_null( $post ) && $post->post_type === WFACP_Common::get_post_type_slug() ) {
			WFACP_Common::set_id( $post_id );
			add_action( 'admin_head', function () {
				add_filter( 'post_type_link', [ $this, 'change_edit_with_oxygen_link' ], 10, 2 );
			} );
			WFACP_Core()->template_loader->load_template( $post_id );
			$this->prepare_module();

			return true;
		}

		return false;
	}


	public function prepare_module() {
		$id     = WFACP_Common::get_id();
		$design = WFACP_Common::get_page_design( $id );
		if ( 'oxy' !== $design['selected_type'] || ! class_exists( 'OxyEl' ) ) {
			return;
		}
		$this->run_widgets();

	}

	public function run_widgets() {
		$modules = $this->get_modules();
		if ( ! empty( $modules ) ) {
			include_once __DIR__ . '/class-abstract-wfacp-fields.php';
			include_once __DIR__ . '/class-wfacp-html-block-oxy.php';
			foreach ( $modules as $key => $module ) {
				if ( ! file_exists( $module['path'] ) ) {
					continue;
				}
				$this->modules_instance[ $key ] = include $module['path'];
			}
		}
	}

	public function setup_global_checkout( $post_id ) {
		$design = WFACP_Common::get_page_design( $post_id );

		if ( 'oxy' === $design['selected_type'] ) {
			$this->wfacp_id = $post_id;
			global $post;
			$post       = get_post( $this->wfacp_id );
			$this->post = $post;
			add_action( 'wp_head', [ $this, 'change_global_post_var_to_our_page_post' ], 999998 );
			add_filter( 'the_content', [ $this, 'change_global_post_var_to_our_page_post' ], 5 );
		}
	}

	public function change_global_post_var_to_our_page_post( $content ) {

		global $post;
		if ( ! is_null( $this->post ) ) {
			$post = $this->post;
		} else {
			$post = get_post( $this->wfacp_id );
		}

		return $content;
	}

	private function get_modules() {
		$modules = [
			'checkout_form' => [
				'name' => __( 'Checkout Form', 'woofunnels-aero-checkout' ),
				'path' => __DIR__ . ( '/modules/class-oxy-form.php' ),
			],
		];

		return apply_filters( 'wfacp_oxy_modules', $modules, $this );
	}

	public function change_edit_with_oxygen_link( $link, $post ) {
		$link = add_query_arg( [ 'oxy_wfacp_id' => $post->ID ], $link );

		return $link;
	}

	public function add_admin_bar_link() {
		/**
		 * @var $wp_admin_bar WP_Admin_Bar;
		 */ global $wp_admin_bar;

		if ( ! is_null( $wp_admin_bar ) ) {

			$node = $wp_admin_bar->get_node( 'edit_post_template' );
			if ( ! is_null( $node ) ) {
				$node = (array) $node;
				global $post;
				if ( ! is_null( $post ) && $post->post_type === WFACP_Common::get_post_type_slug() ) {
					$wfacp_id     = $post->ID;
					$href         = $node['href'];
					$node['href'] = add_query_arg( [ 'ct_builder' => 'true', 'oxy_wfacp_id' => $wfacp_id ], $href );
					$wp_admin_bar->add_node( $node );
				}
			}
		}
	}

	/**
	 * Delete oxy saved data from postmeta of aerocheckout ID
	 */
	public function delete_oxy_data( $post_id ) {
		delete_post_meta( $post_id, 'ct_other_template' );
		delete_post_meta( $post_id, 'ct_builder_shortcodes' );
		delete_post_meta( $post_id, 'ct_page_settings' );
		delete_post_meta( $post_id, 'ct_builder_json' );
	}

	public function duplicate_template( $new_post_id, $post_id, $data ) {
		if ( 'oxy' === $data['_wfacp_selected_design']['selected_type'] ) {
			$content  = get_post_meta( $post_id, 'ct_builder_shortcodes', true );
			$settings = get_post_meta( $post_id, 'ct_page_settings', true );
			$template = get_post_meta( $post_id, 'ct_other_template', true );
			update_post_meta( $new_post_id, 'ct_other_template', $template );
			update_post_meta( $new_post_id, 'ct_page_settings', $settings );
			update_post_meta( $new_post_id, 'ct_builder_shortcodes', $content );
		}
	}



	public function enable_self_page_css() {
		if ( apply_filters( 'bwf_enable_oxygen_universal_css', true, $this ) ) {
			return;
		}
		add_filter( 'pre_option_oxygen_vsb_universal_css_cache', [ $this, 'disable_universal_css' ] );
	}

	public function disable_universal_css( $status ) {
		global $post;
		if ( ! is_null( $post ) && $post->post_type == WFACP_Common::get_post_type_slug() ) {
			$status = 'false';
		}

		return $status;
	}

}

WFACP_OXY::get_instance();
