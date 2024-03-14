<?php

class WFACP_DIVI {
	private static $ins = null;

	private $is_divi = false;
	private static $front_locals = [];
	private $template_file = '';
	private $wfacp_id = 0;
	private $set_our_page_content = '';

	private function __construct() {
		$this->template_file = WFACP_Core()->dir( 'builder/divi/template/template.php' );
		add_filter( 'wfacp_is_theme_builder', [ $this, 'is_divi_page' ] );
		add_action( 'wfacp_template_removed', [ $this, 'delete_divi_data' ] );
		add_action( 'wfacp_duplicate_pages', [ $this, 'duplicate_template' ], 10, 3 );
		add_action( 'wfacp_get_divi_form_data', [ $this, 'builder_actions' ], 10, 2 );
		add_action( 'et_save_post', [ $this, 'migrate_label' ] );
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

	public function is_divi_page( $status ) {

		// At load
		if ( isset( $_REQUEST['et_fb'] ) ) {
			$this->is_divi = true;
			$status        = true;

		}
		// when ajax running for form html
		if ( isset( $_REQUEST['wc-ajax'] ) && 'wfacp_get_divi_data' == $_REQUEST['wc-ajax'] ) {
			$this->is_divi = true;
			$status        = true;

		}


		if ( function_exists( 'et_fb_is_builder_ajax' ) && et_fb_is_builder_ajax() ) {
			$this->is_divi = true;
			$status        = true;
		}

		if ( true == $status ) {
			add_filter( 'wfacp_wc_photoswipe_enable', '__return_false' );
		}

		return $status;
	}

	private function register() {

		add_action( 'wfacp_checkout_page_found', [ $this, 'initialize_divi_widgets' ] );
		add_action( 'wfacp_register_template_types', [ $this, 'register_template_type' ], 12 );
		add_filter( 'wfacp_register_templates', [ $this, 'register_templates' ] );
		add_action( 'wfacp_template_load', [ $this, 'load_divi_abs_class' ], 10, 2 );
		add_filter( 'wfacp_template_edit_link', [ $this, 'add_template_edit_link' ], 10, 2 );

		add_action( 'divi_extensions_init', [ $this, 'init_extension' ] );
		add_action( 'admin_bar_menu', [ $this, 'add_admin_bar_link' ], 1003 );
	}

	/**
	 * @param $loader WFACP_Template_loader
	 */
	public function register_template_type( $loader ) {
		$template = [
			'slug'    => 'divi',
			'title'   => __( 'Divi', 'woofunnels-aero-checkout' ),
			'filters' => WFACP_Common::get_template_filter()
		];

		$loader->register_template_type( $template );
	}

	public function register_templates( $designs ) {


		$templates       = WooFunnels_Dashboard::get_all_templates();
		$designs['divi'] = ( isset( $templates['wc_checkout'] ) && isset( $templates['wc_checkout']['divi'] ) ) ? $templates['wc_checkout']['divi'] : [];

		if ( is_array( $designs['divi'] ) && count( $designs['divi'] ) > 0 ) {
			foreach ( $designs['divi'] as $key => $val ) {
				$val['path']             = WFACP_BUILDER_DIR . '/divi/template/template.php';
				$designs['divi'][ $key ] = $val;
			}
		}


		return $designs;

	}


	public function initialize_divi_widgets( $post_id ) {
		$design = WFACP_Common::get_page_design( $post_id );
		if ( 'divi' == $design['selected_type'] ) {
			add_filter( 'et_builder_add_outer_content_wrap', '__return_true', 999 );
			if ( ! isset( $_REQUEST['et_fb'] ) ) {
				global $post;
				$post                       = get_post( $post_id );
				$this->set_our_page_content = $post->post_content;
				remove_filter( 'the_content', 'et_builder_add_builder_content_wrapper' );
				add_filter( 'wfacp_assign_default_theme_template', '__return_false' );
				add_filter( 'the_content', [ $this, 'replace_divi_our_page_content' ], 1 );
			}
		}
	}

	public function replace_divi_our_page_content( $content ) {
		if ( '' !== $this->set_our_page_content ) {
			$content = $this->et_builder_add_builder_content_wrapper( $this->set_our_page_content );
		}
		do_action( 'wfacp_divi_page_content_replaced', $this, $content );

		return $content;
	}

	public function et_builder_add_builder_content_wrapper( $content ) {
		$is_bfb_new_page = isset( $_GET['is_new_page'] ) && '1' === $_GET['is_new_page'];

		if ( ! is_singular() && ! $is_bfb_new_page && ! et_theme_builder_is_layout_post_type( get_post_type( get_the_ID() ) ) ) {
			return $content;
		}
		if ( function_exists( 'et_builder_get_layout_opening_wrapper' ) ) {
			$content = et_builder_get_layout_opening_wrapper() . $content . et_builder_get_layout_closing_wrapper();
		}

		/**
		 * Filter whether to add the outer builder content wrapper or not.
		 *
		 * @param bool $wrap
		 *
		 * @since 4.0
		 *
		 */
		if ( function_exists( 'et_builder_get_builder_content_opening_wrapper' ) ) {
			$content = et_builder_get_builder_content_opening_wrapper() . $content . et_builder_get_builder_content_closing_wrapper();
		}

		return $content;
	}

	public function load_divi_abs_class( $wfacp_id, $template = [] ) {
		if ( empty( $template ) ) {
			return;
		}
		if ( 'divi' == $template['selected_type'] ) {

			include_once WFACP_Core()->dir( 'builder/divi/class-wfacp-divi-template.php' );
		}
	}

	public function add_template_edit_link( $links, $admin ) {
		$url           = add_query_arg( [
			'et_fb'       => '1',
			'et_wfacp_id' => $admin->wfacp_id
		], get_the_permalink( $admin->wfacp_id ) );
		$links['divi'] = [ 'url' => $url, 'button_text' => __( 'Edit', 'elementor' ) ];

		return $links;
	}


	public function init_extension() {

		if ( wp_doing_ajax() ) {

			if ( isset( $_REQUEST['action'] ) && "et_fb_get_saved_templates" == $_REQUEST['action'] && isset( $_REQUEST['et_post_type'] ) && WFACP_Common::get_post_type_slug() !== $_REQUEST['et_post_type'] ) {
				return;
			}

			if ( isset( $_REQUEST['action'] ) && "et_fb_update_builder_assets" == $_REQUEST['action'] && isset( $_REQUEST['et_post_type'] ) && WFACP_Common::get_post_type_slug() !== $_REQUEST['et_post_type'] ) {
				return;
			}

			$post_id = 0;
			if ( isset( $_REQUEST['action'] ) && "heartbeat" == $_REQUEST['action'] && isset( $_REQUEST['data'] ) ) {
				if ( isset( $_REQUEST['data']['et'] ) ) {
					$post_id = $_REQUEST['data']['et']['post_id'];

				}
			}

			if ( isset( $_REQUEST['post_id'] ) ) {
				$post_id = absint( $_REQUEST['post_id'] );
			}
			if ( isset( $_REQUEST['et_post_id'] ) ) {
				$post_id = absint( $_REQUEST['et_post_id'] );
			}
			if ( $post_id > 0 ) {
				$post = get_post( $post_id );
				if ( is_null( $post ) || $post->post_type !== WFACP_Common::get_post_type_slug() ) {
					return;
				}
			}
		}

		if ( isset( $_REQUEST['et_fb'] ) && ! isset( $_REQUEST['et_wfacp_id'] ) ) {
			return;
		}

		include __DIR__ . '/class-wfacp-divi-extension.php';


	}

	public function add_admin_bar_link() {
		/**
		 * @var $wp_admin_bar WP_Admin_Bar;
		 */ global $wp_admin_bar;

		if ( ! is_null( $wp_admin_bar ) ) {
			$node = $wp_admin_bar->get_node( 'et-use-visual-builder' );
			if ( ! is_null( $node ) ) {
				$node = (array) $node;
				global $post;
				if ( ! is_null( $post ) && $post->post_type == WFACP_Common::get_post_type_slug() ) {
					$wfacp_id     = $post->ID;
					$href         = $node['href'];
					$node['href'] = add_query_arg( [ 'et_wfacp_id' => $wfacp_id ], $href );
					$wp_admin_bar->add_node( $node );
				}
			}
		}
	}

	/**
	 * Delete Elementor saved data from postmeta of aerocheckout ID
	 */
	public function delete_divi_data( $post_id ) {
		wp_update_post( [ 'ID' => $post_id, 'post_content' => '' ] );
		delete_post_meta( $post_id, 'et_enqueued_post_fonts' );
	}

	public function duplicate_template( $new_post_id, $post_id, $data ) {
		if ( 'divi' == $data['_wfacp_selected_design']['selected_type'] ) {
			$data = [
				'_et_pb_use_builder'     => get_post_meta( $post_id, '_et_pb_use_builder', true ),
				'et_enqueued_post_fonts' => get_post_meta( $post_id, 'et_enqueued_post_fonts', true ),
			];
			foreach ( $data as $meta_key => $meta_value ) {
				update_post_meta( $new_post_id, $meta_key, $meta_value );
			}
		}

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


	public function migrate_label( $post_id ) {
		$post = get_post( $post_id );

		if ( ! is_null( $post ) ) {
			if ( false !== strpos( $post->post_content, 'wfacp-modern-label' ) ) {
				$field_label = 'wfacp-modern-label';
				WFACP_Common_Helper::modern_label_migrate( $post_id );
			} else if ( false !== strpos( $post->post_content, 'wfacp-top' ) ) {
				$field_label = 'wfacp-top';
			} else {
				$field_label = 'wfacp-inside';
			}
			update_post_meta( $post_id, '_wfacp_field_label_position', $field_label );
		}

	}

}

WFACP_DIVI::get_instance();
