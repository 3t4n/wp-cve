<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff

use Elementor\Plugin;

defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_Optin_Pages_Elementor
 */
if ( ! class_exists( 'WFFN_Optin_Pages_Elementor' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Optin_Pages_Elementor {

		private static $ins = null;
		protected $template_type = [];
		protected $design_template_data = [];
		protected $templates = [];
		private $edit_id = 0;
		private $url = '';

		/**
		 * WFFN_Optin_Pages_Elementor constructor.
		 */
		public function __construct() {
			$this->url = plugin_dir_url( WFOPP_PLUGIN_FILE );
			$this->process_url();
			add_filter( 'bwf_page_template', array( $this, 'get_page_template' ) );

			/**  Register widget category */
			add_action( 'elementor/elements/categories_registered', array( $this, 'add_wffn_elementor_category' ) );

			if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
				add_action( 'elementor/widgets/register', array( $this, 'register_widgets' ) );
			} else {
				add_action( 'elementor/widgets/widgets_registered', array( $this, 'register_widgets' ) );
			}

			add_action( 'wp_enqueue_scripts', [ $this, 'register_scripts' ] );
			add_action( 'init', [ $this, 'setup' ] );
		}

		private function process_url() {

			if ( isset( $_REQUEST['action'] ) && 'elementor' === $_REQUEST['action'] && isset( $_REQUEST['post'] ) && $_REQUEST['post'] > 0 ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->edit_id = absint( $_REQUEST['post'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			if ( isset( $_REQUEST['action'] ) && 'elementor_ajax' === $_REQUEST['action'] && isset( $_REQUEST['editor_post_id'] ) && $_REQUEST['editor_post_id'] > 0 ) {  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$this->edit_id = absint( $_REQUEST['editor_post_id'] ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}

		}

		public function add_default_templates() {
			$templates = WooFunnels_Dashboard::get_all_templates();
			$designs   = isset( $templates['optin'] ) ? $templates['optin'] : [];
			$template  = [
				'slug'        => 'elementor',
				'title'       => __( 'Elementor', 'funnel-builder' ),
				'button_text' => __( 'Edit', 'funnel-builder' ),
				'edit_url'    => add_query_arg( [
					'post'   => $this->edit_id,
					'action' => 'elementor',
				], admin_url( 'post.php' ) ),
			];
			WFOPP_Core()->optin_pages->register_template_type( $template );


			if(isset($designs['elementor']) && is_array($designs['elementor'])) {
				foreach ( $designs['elementor'] as $d_key => $templates ) {

					if ( isset( $templates['pro'] ) && 'yes' === $templates['pro'] ) {
						$templates['license_exist'] = WFFN_Core()->admin->get_license_status();
					}
					WFOPP_Core()->optin_pages->register_template( $d_key, $templates, 'elementor' );

				}
			}else{

				$empty_template = [
					"type"               => "view",
					"import"             => "no",
					"show_import_popup"  => "no",
					"slug"               => "elementor_1",
					"build_from_scratch" => true,
					"group"              => [
						"inline",
						"popup"
					],
				];
				WFOPP_Core()->optin_pages->register_template( 'elementor_1', $empty_template, 'elementor' );
			}

			return [];
		}

		/**
		 * @return WFFN_Optin_Pages_Elementor|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}


		/**
		 * Get page template fiter callback for elementor preview mode
		 *
		 * @param string $template page template.
		 *
		 * @return string
		 */
		public function get_page_template( $template ) {
			$response = WFFN_Common::check_builder_status( 'elementor' );
			if ( is_singular() && ( true === $response['found'] ) ) {
				if ( version_compare( $response['version'], '3.2.0', '<=' ) ) {
					$el_build = Plugin::$instance->db->is_built_with_elementor( get_the_ID() );
				} else {
					$el_build = Plugin::$instance->documents->get( get_the_ID() )->is_built_with_elementor();
				}
				if ( true === $el_build ) {
					$document = Plugin::$instance->documents->get_doc_for_frontend( get_the_ID() );
					if ( $document ) {
						$template = $document->get_meta( '_wp_page_template' );
					}
				}
			}

			return $template;
		}

		public function get_module_path() {
			return plugin_dir_path( WFFN_PLUGIN_FILE ) . 'modules/optin-pages/templates/template-default-boxed.php';
		}

		/**
		 * Adding a new widget category 'Flex Funnels'
		 */
		public function add_wffn_elementor_category() {
			$design = WFOPP_Core()->optin_pages->get_page_design( WFOPP_Core()->optin_pages->edit_id );
			if ( 'elementor' === $design['selected_type'] && class_exists( '\Elementor\Plugin' ) ) {
				\Elementor\Plugin::instance()->elements_manager->add_category( 'wffn-flex', array(
					'title' => __( 'FunnelKit', 'funnel-builder' ),
					'icon'  => 'fa fa-plug',
				) );
			}
		}

		public function register_widgets() {
			$optinPageId = $this->edit_id;

			if ( $optinPageId < 1 && function_exists( 'WFOPP_Core' ) ) {
				$optinPageId = WFOPP_Core()->optin_pages->get_optin_id();
			}
			if ( $optinPageId < 1 && function_exists( 'get_the_ID' ) ) {
				$optinPageId = get_the_ID();
			}

			if ( WFOPP_Core()->optin_pages->get_post_type_slug() === get_post_type( $optinPageId ) ) {

				require_once( __DIR__ . '/widgets/class-elementor-wffn-optin-form-widget.php' );

				if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '3.5.0', '>=' ) ) {
					\Elementor\Plugin::instance()->widgets_manager->register( new \Elementor_WFFN_Optin_Form_Widget() );
				}else{
					\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_WFFN_Optin_Form_Widget() );
				}

				do_action( 'wffn_optin_elementor_lite_loaded' );
			}
		}

		/**
		 * register optin script in elementor editor mode
		 */
		public function register_scripts() {
			if ( class_exists( '\Elementor\Plugin' ) && \Elementor\Plugin::$instance->preview instanceof \Elementor\Preview && \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
				$post_id = get_the_ID();
				if ( apply_filters( 'wfop_show_flag_in_phone_field', true ) && WFOPP_Core()->optin_pages->get_post_type_slug() === get_post_type( $post_id ) && WFFN_Common::wffn_is_funnel_pro_active() ) {
					wp_enqueue_script( 'phone_flag_intl', plugin_dir_url( WFOPP_PRO_PLUGIN_FILE ) . 'assets/phone/js/intltelinput.min.js', array(), WFFN_VERSION_DEV );
					wp_enqueue_style( 'flag_style', plugin_dir_url( WFOPP_PRO_PLUGIN_FILE ) . 'assets/phone/css/phone-flag.css', array(), WFFN_VERSION_DEV );

				}

			}
		}

		public function setup() {
			if ( did_action( 'elementor/loaded' ) ) {
				add_action( 'elementor/theme/register_conditions', [ $this, 'register_conditions' ] );
			}

		}

		public function register_conditions( $conditions_manager ) {
			require plugin_dir_path( WFFN_PLUGIN_FILE ) . 'modules/optins/modules/optin-pages/compatibilities/page-builders/elementor/conditions/class-wffn-op-pages.php';
			$new_condition = new ElementorPro\Modules\ThemeBuilder\Conditions\WFFN_OP_Pages( [
				'post_type' => WFOPP_Core()->optin_pages->get_post_type_slug(),
			] );
			$conditions_manager->get_condition( 'singular' )->register_sub_condition( $new_condition );
		}


	}

	WFFN_Optin_Pages_Elementor::get_instance();
}
