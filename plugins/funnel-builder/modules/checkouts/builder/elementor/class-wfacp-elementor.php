<?php

class WFACP_Elementor {
	private static $ins = null;

	private $is_elementor = false;
	private static $front_locals = [];
	private $template_file = '';
	private $widget_dir = '';
	private $wfacp_id = 0;

	private function __construct() {

		$this->widget_dir    = WFACP_Core()->dir( 'builder/elementor/widgets' );
		$this->template_file = WFACP_Core()->dir( 'builder/elementor/template/template.php' );
		$this->register();
		add_action( 'wfacp_template_removed', [ $this, 'delete_elementor_data' ] );
		add_action( 'wfacp_duplicate_pages', [ $this, 'duplicate_template' ], 10, 3 );
		add_action( 'wfacp_update_page_design', [ $this, 'update_page_design' ], 10, 2 );
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_widget_categories' ] );
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

	private function widgets() {
		if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
			add_action( 'elementor/widgets/register', [ $this, 'initialize_widgets' ] );
		} else {
			add_action( 'elementor/widgets/widgets_registered', [ $this, 'initialize_widgets' ] );
		}
	}

	public function initialize_widgets() {

		include_once __DIR__ . '/class-abstract-wfacp-fields.php';
		include_once __DIR__ . '/class-wfacp-html-block-elementor.php';
		foreach ( glob( $this->widget_dir . '/class-elementor-*.php' ) as $_field_filename ) {
			require_once( $_field_filename );
		}
	}

	public function add_widget_categories( $elements_manager ) {
		$design = WFACP_Common::get_page_design( WFACP_Common::get_id() );
		if ( 'elementor' === $design['selected_type'] && class_exists( '\Elementor\Plugin' ) ) {
			$elements_manager->add_category( 'woofunnels-aero-checkout', [
				'title' => __( 'FunnelKit', 'woofunnels-aero-checkout' ),
				'icon'  => 'fa fa-plug',
			] );
		}
	}

	private function register() {
		add_filter( 'wfacp_is_theme_builder', [ $this, 'is_elementor_page' ] );
		add_filter( 'wfacp_post', [ $this, 'check_current_page_is_aero_page' ] );
		add_action( 'wfacp_checkout_page_found', [ $this, 'initialize_elementor_widgets' ] );
		add_action( 'wfacp_register_template_types', [ $this, 'register_template_type' ], 11 );
		add_filter( 'wfacp_register_templates', [ $this, 'register_templates' ] );
		add_action( 'wfacp_template_load', [ $this, 'load_elementor_abs_class' ], 10, 2 );
		add_filter( 'wfacp_template_edit_link', [ $this, 'add_template_edit_link' ], 10, 2 );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 101 );
	}

	public function is_elementor_page( $status ) {
		if ( isset( $_REQUEST['elementor-preview'] ) || ( isset( $_REQUEST['action'] ) && ( 'elementor' === $_REQUEST['action'] || 'elementor_ajax' === $_REQUEST['action'] ) ) ) {
			$this->is_elementor = true;
			$status             = true;
		}
		if ( isset( $_REQUEST['preview_id'] ) && isset( $_REQUEST['preview_nonce'] ) ) {
			$this->is_elementor = true;
			$status             = true;
		}

		if ( $this->is_elementor ) {
			if ( isset( $_REQUEST['post'] ) ) {
				$temp_id = absint( $_REQUEST['post'] );
			} elseif ( isset( $_REQUEST['editor_post_id'] ) ) {
				$temp_id = absint( $_REQUEST['editor_post_id'] );
			} else {
				$temp_id = 0;
			}
			WFACP_Common::set_id( $temp_id );
		}

		return $status;
	}

	public function check_current_page_is_aero_page( $post ) {
		if ( WFACP_Common::is_theme_builder() && true == $this->is_elementor ) {

			if ( isset( $_REQUEST['post'] ) ) {
				$temp_id = absint( $_REQUEST['post'] );
			} elseif ( isset( $_REQUEST['editor_post_id'] ) ) {
				$temp_id = absint( $_REQUEST['editor_post_id'] );
			} else {
				$temp_id = 0;
			}
			$post = get_post( $temp_id );
		}

		return $post;
	}

	public function initialize_elementor_widgets( $post_id ) {
		$design = WFACP_Common::get_page_design( $post_id );
		if ( 'elementor' == $design['selected_type'] && class_exists( '\Elementor\Plugin' ) ) {
			$this->wfacp_id = $post_id;
			global $post;
			$post = get_post( $this->wfacp_id );
			$this->widgets();
			add_filter( 'the_content', [ $this, 'change_global_post_var_to_our_page_post' ], 5 );
			add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'custom_admin_style' ] );
			add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'register_custom_font' ] );
		}
	}

	public function change_global_post_var_to_our_page_post( $content ) {
		global $post;
		$post = get_post( $this->wfacp_id );

		return $content;

	}


	public function enqueue_scripts() {

		if ( isset( $_REQUEST['elementor-preview'] ) ) {
			wp_enqueue_script( 'wfacp_elementor_edit', WFACP_Core()->url( '/builder/elementor/js/elementor-preview-iframe.js' ), [ 'wfacp_checkout_js' ], WFACP_VERSION_DEV, true );
		}
	}


	/**
	 * @param $loader WFACP_Template_loader
	 */
	public function register_template_type( $loader ) {
		$template = [
			'slug'    => 'elementor',
			'title'   => __( 'Elementor', 'woofunnels-aero-checkout' ),
			'filters' => WFACP_Common::get_template_filter()
		];

		$loader->register_template_type( $template );
	}

	public function register_templates( $designs ) {
		$templates = WooFunnels_Dashboard::get_all_templates();

		$designs['elementor'] = ( isset( $templates['wc_checkout'] ) && isset( $templates['wc_checkout']['elementor'] ) ) ? $templates['wc_checkout']['elementor'] : [];

		if ( is_array( $designs['elementor'] ) && count( $designs['elementor'] ) > 0 ) {
			foreach ( $designs['elementor'] as $key => $val ) {
				$val['path']                  = WFACP_BUILDER_DIR . '/elementor/template/template.php';
				$designs['elementor'][ $key ] = $val;
			}
		}

		return $designs;
	}

	public function load_elementor_abs_class( $wfacp_id, $template = [] ) {
		if ( empty( $template ) ) {
			return;
		}
		if ( 'elementor' === $template['selected_type'] ) {
			$this->check_css_file_issue( $wfacp_id );
			include_once WFACP_Core()->dir( 'builder/elementor/class-wfacp-elementor-template.php' );
		}
	}

	public function add_template_edit_link( $links, $admin ) {
		$url                = add_query_arg( [ 'post' => $admin->wfacp_id, 'action' => 'elementor' ], admin_url( 'post.php' ) );
		$links['elementor'] = [ 'url' => $url, 'button_text' => __( 'Edit', 'elementor' ) ];

		return $links;
	}

	public function custom_admin_style() {
		echo '<style>';
		include __DIR__ . '/css/custom_admin_style.css';
		echo '</style>';
	}

	public function register_custom_font() {
		wp_enqueue_style( 'wfacp-icons', WFACP_PLUGIN_URL . '/admin/assets/css/wfacp-font.css', null, WFACP_VERSION );
	}

	/**
	 * Delete Elementor saved data from postmeta of aerocheckout ID
	 *
	 * @param $post_id
	 */
	public function delete_elementor_data( $post_id ) {
		wp_update_post( [ 'ID' => $post_id, 'post_content' => '' ] );
		delete_post_meta( $post_id, '_elementor_version' );
		delete_post_meta( $post_id, '_elementor_template_type' );
		delete_post_meta( $post_id, '_elementor_edit_mode' );
		delete_post_meta( $post_id, '_elementor_data' );
		delete_post_meta( $post_id, '_elementor_controls_usage' );
		delete_post_meta( $post_id, '_elementor_css' );
	}

	public function update_page_design( $page_id, $data ) {
		if ( ! isset( $page_id ) ) {
			return;
		}

		if ( ! is_array( $data ) || count( $data ) == 0 ) {
			return;
		}
		if ( ! isset( $data['selected_type'] ) || $data['selected_type'] !== 'elementor' ) {
			return;
		}
	}

	public function duplicate_template( $new_post_id, $post_id, $data ) {
		if ( 'elementor' === $data['_wfacp_selected_design']['selected_type'] ) {
			if ( class_exists( 'SitePress' ) ) {
				WFACP_Common::copy_meta( $post_id, $new_post_id );

				return;
			}
			$contents = get_post_meta( $post_id, '_elementor_data', true );
			$data     = [
				'_elementor_version'       => get_post_meta( $post_id, '_elementor_version', true ),
				'_elementor_template_type' => get_post_meta( $post_id, '_elementor_template_type', true ),
				'_elementor_edit_mode'     => get_post_meta( $post_id, '_elementor_edit_mode', true ),
			];

			foreach ( $data as $meta_key => $meta_value ) {
				update_post_meta( $new_post_id, $meta_key, $meta_value );
			}

			/**
			 * @var $instance WFACP_Elementor_Importer
			 */
			$instance = new WFACP_Elementor_Importer();
			if ( ! is_null( $instance ) ) {
				if ( is_array( $contents ) ) {
					$contents = json_encode( $contents );

				}
				$instance->delete_page_meta = false;
				$instance->import_aero_template( $new_post_id, $contents );
			}
			update_post_meta( $new_post_id, '_wp_page_template', get_post_meta( $post_id, '_wp_page_template', true ) );
		}
	}


	public function check_css_file_issue( $wfacp_id ) {
		if ( 'internal' == get_option( 'elementor_css_print_method' ) ) {
			return;
		}
		$elementor_css_present = get_post_meta( $wfacp_id, '_elementor_css', true );
		if ( ! empty( $elementor_css_present ) ) {
			delete_post_meta( $wfacp_id, '_elementor_css' );
		}
	}
}

WFACP_Elementor::get_instance();