<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff

defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_ThankYou_WC_Pages_Oxygen
 */
if ( ! class_exists( 'WFFN_ThankYou_WC_Pages_Oxygen' ) ) {

	#[AllowDynamicProperties]

  class WFFN_ThankYou_WC_Pages_Oxygen {
		private static $ins = null;
		public $modules_instance = [];
		private $edit_id = 0;

		/**
		 * WFFN_ThankYou_WC_Pages_Oxygen constructor.
		 */
		private function __construct() {
			$this->register();

			add_filter( 'wffn_thankyou_page_id', [ $this, 'change_dynamic_thankyou_page_id' ] );
			add_filter( 'wfty_load_frontend_style', [ $this, 'prevent_load_frontend_style' ], 10, 2 );
			add_action( 'oxygen_enqueue_frontend_scripts', [ $this, 'enable_self_page_css' ] );
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

		}

		public function add_default_templates() {

			$template = [
				'slug'        => 'oxy',
				'title'       => __( 'Oxygen', 'funnel-builder' ),
				'button_text' => __( 'Edit', 'funnel-builder' ),
				'description' => __( 'Use Oxygen Builder modules to create your own designs. Or pick from professionally-designed templates.', 'funnel-builder' ),
				'edit_url'    => add_query_arg( [
					'ct_builder'           => true,
					'oxy_wffn_thankyou_id' => $this->edit_id
				], get_permalink( $this->edit_id ) ),
			];

			WFFN_Core()->thank_you_pages->register_template_type( $template );
			$templates = WooFunnels_Dashboard::get_all_templates();
			$designs   = isset( $templates['wc_thankyou'] ) ? $templates['wc_thankyou'] : [];

			if ( isset( $designs['oxy'] ) && is_array( $designs['oxy'] ) ) {
				foreach ( $designs['oxy'] as $d_key => $templates ) {

					if ( isset( $templates['pro'] ) && 'yes' === $templates['pro'] ) {
						$templates['license_exist'] = WFFN_Core()->admin->get_license_status();
					}
					WFFN_Core()->thank_you_pages->register_template( $d_key, $templates, 'oxy' );

				}
			} else {

				$empty_template = [
					"type"               => "view",
					"import"             => "no",
					"show_import_popup"  => "no",
					"slug"               => "oxy_1",
					"build_from_scratch" => true,

				];
				WFFN_Core()->thank_you_pages->register_template( 'oxy_1', $empty_template, 'oxy' );
			}

			return [];

		}

		public function init_extension() {
			if ( WFFN_OXYGEN::is_template_editor() ) {
				add_action( 'wp', [ $this, 'remove_register_template' ] );
				add_action( 'wp', [ $this, 'prepare_frontend_module' ] );

				return;
			}
			$post_id = 0;
			if ( isset( $_REQUEST['post_id'] ) && $_REQUEST['post_id'] > 0 ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = absint( $_REQUEST['post_id'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			} elseif ( isset( $_REQUEST['oxy_wffn_thankyou_id'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = absint( $_REQUEST['oxy_wffn_thankyou_id'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			} elseif ( isset( $_REQUEST['post'] ) && $_REQUEST['post'] > 0 && isset( $_REQUEST['action'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = absint( $_REQUEST['post'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}

			$post = get_post( $post_id );

			if ( ! is_null( $post ) && $post->post_type === WFFN_Core()->thank_you_pages->get_post_type_slug() ) {

				$this->edit_id = $post_id;
				add_action( 'admin_head', function () {
					add_filter( 'post_type_link', [ $this, 'change_edit_with_oxygen_link' ], 10, 2 );
				} );
				WFFN_OXYGEN::disable_signature_checking();
				$this->remove_register_template();
				$this->prepare_module();

				return;
			}
			add_action( 'wp', [ $this, 'remove_register_template' ] );
			add_action( 'wp', [ $this, 'prepare_frontend_module' ] );


		}

		public function prepare_frontend_module() {
			global $post;
			if ( is_null( $post ) ) {
				return;
			}
			if ( $post->post_type === WFFN_Core()->thank_you_pages->get_post_type_slug() ) {
				$this->edit_id = $post->ID;
				WFFN_OXYGEN::disable_signature_checking();
				$this->prepare_module();
			}

		}

		public function prepare_module() {
			$id     = WFFN_Core()->thank_you_pages->get_id();
			$design = WFFN_Core()->thank_you_pages->get_page_design( $id );

			if ( 'oxy' !== $design['selected_type'] || ! class_exists( 'OxyEl' ) ) {
				return;
			}
			$modules = $this->get_modules();
			if ( ! empty( $modules ) ) {
				include __DIR__ . '/class-wffn-thankyou-wc-html-block-oxy.php';
				foreach ( $modules as $key => $module ) {
					if ( ! file_exists( $module['path'] ) ) {
						continue;
					}
					$this->modules_instance[ $key ] = include $module['path'];
				}
			}
		}


		private function get_modules() {
			$modules = [
				'order_details'    => [
					'name' => __( 'Order Details', 'funnel-builder' ),
					'path' => __DIR__ . '/modules/class-oxygen-wfty-order-details-widget.php',
				],
				'customer_details' => [
					'name' => __( 'Customer Details', 'funnel-builder' ),
					'path' => __DIR__ . '/modules/class-oxygen-wfty-customer-details-widget.php',
				],
			];

			return apply_filters( 'wffn_thankyou_oxy_modules', $modules, $this );
		}

		public function change_edit_with_oxygen_link( $link, $post ) {
			$link = add_query_arg( [ 'oxy_wffn_thankyou_id' => $post->ID ], $link );

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
					if ( ! is_null( $post ) && $post->post_type === WFFN_Core()->thank_you_pages->get_post_type_slug() ) {
						$wfacp_id     = $post->ID;
						$href         = $node['href'];
						$node['href'] = add_query_arg( [ 'ct_builder' => 'true', 'oxy_wffn_thankyou_id' => $wfacp_id ], $href );
						$wp_admin_bar->add_node( $node );
					}
				}
			}
		}

		public function change_dynamic_thankyou_page_id( $id ) {
			if ( $this->edit_id > 0 ) {
				$id = $this->edit_id;
			}

			return $id;

		}


		public function prevent_load_frontend_style( $bool, $id ) {

			$design = WFFN_Core()->thank_you_pages->get_page_design( $id );

			if ( 'oxy' !== $design['selected_type'] ) {
				return $bool;
			}

			return false;
		}

		public function enable_self_page_css() {
			if ( apply_filters( 'bwf_enable_oxygen_universal_css', true, $this ) ) {
				return;
			}
			add_filter( 'pre_option_oxygen_vsb_universal_css_cache', [ $this, 'disable_universal_css' ] );
		}

		public function disable_universal_css( $status ) {
			global $post;
			if ( ! is_null( $post ) && $post->post_type === WFFN_Core()->thank_you_pages->get_post_type_slug() ) {
				$status = 'false';
			}

			return $status;
		}

		public function remove_register_template() {

			$id   = WFFN_Core()->thank_you_pages->get_id();
			$post = get_post( $id );
			if ( is_null( $post ) ) {
				global $post; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis
			}

			$ct_builder = ( isset( $_GET['ct_builder'] ) && $_GET['ct_builder'] === 'true' ) ? true : false;//phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( ! is_null( $post ) && $post->post_type === WFFN_Core()->thank_you_pages->get_post_type_slug() ) {
				$design = WFFN_Core()->thank_you_pages->get_page_design( $post->ID );
				if ( ( $ct_builder === true ) || ( is_array( $design ) && isset( $design['selected_type'] ) && 'oxy' === $design['selected_type'] ) ) {
					remove_action( 'template_include', [ WFFN_Thank_You_WC_Pages::get_instance(), 'may_be_change_template' ], 99 );
				}
			}

		}


	}

	WFFN_ThankYou_WC_Pages_Oxygen::get_instance();
}
