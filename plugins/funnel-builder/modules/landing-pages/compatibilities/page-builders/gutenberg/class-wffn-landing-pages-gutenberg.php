<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff

defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_Landing_Pages_Oxygen
 */
if ( ! class_exists( 'WFFN_Landing_Pages_Gutenberg' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Landing_Pages_Gutenberg {
		private static $ins = null;
		private $edit_id = 0;

		/**
		 * WFFN_Landing_Pages_Oxygen constructor.
		 */
		private function __construct() {

			$this->register();
			add_action( 'init', [ $this, 'init_extension' ], 21 );
			add_action( 'wffn_wflp_before_register_templates', array( $this, 'add_default_templates' ),15 );
		}


		public static function get_instance() {
			if ( is_null( self::$ins ) ) {
				self::$ins = new self();
			}

			return self::$ins;

		}

		private function register() {
			add_action( 'wffn_step_duplicated', [ $this, 'assign_empty_template' ] );
			add_action( 'wflp_page_design_updated', [ $this, 'assign_empty_template' ] );
		}

		public function assign_empty_template( $post_id ) {
			if ( $post_id < 1 ) {
				return;
			}
		}


		public function init_extension() {

			$post_id = 0;
			if ( isset( $_REQUEST['post_id'] ) && $_REQUEST['post_id'] > 0 ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = absint( $_REQUEST['post_id'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			} elseif ( isset( $_REQUEST['oxy_wffn_lp_id'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = absint( $_REQUEST['oxy_wffn_lp_id'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			$post = get_post( $post_id );
			if ( ! is_null( $post ) && $post->post_type === WFFN_Core()->landing_pages->get_post_type_slug() ) {

			}

		}

		public function add_default_templates() {

			$template = [
				'slug'        => 'gutenberg',
				'title'       => __( 'Block Editor', 'funnel-builder' ),
				'button_text' => __( 'Edit', 'funnel-builder' ),
				'description' => __( 'Use Gutenberg Builder modules to create your own designs. Or pick from professionally-designed templates.', 'funnel-builder' ),
				'edit_url'    => add_query_arg( [
					'action' => 'edit',
					'post'   => WFFN_Core()->landing_pages->admin->get_edit_id()
				], admin_url( 'post.php' ) ),
			];

			WFFN_Core()->landing_pages->admin->register_template_type( $template );
			$templates = WooFunnels_Dashboard::get_all_templates();
			$designs   = isset( $templates['landing'] ) ? $templates['landing'] : [];
			if ( isset( $designs['gutenberg'] ) && is_array( $designs['gutenberg'] ) ) {
				foreach ( $designs['gutenberg'] as $d_key => $templates ) {

					if ( isset( $templates['pro'] ) && 'yes' === $templates['pro'] ) {
						$templates['license_exist'] = WFFN_Core()->admin->get_license_status();
					}
					WFFN_Core()->landing_pages->admin->register_template( $d_key, $templates, 'gutenberg' );

				}
			} else {

				$empty_template = [
					"type"               => "view",
					"import"             => "no",
					"show_import_popup"  => "no",
					"slug"               => "gutenberg_1",
					"build_from_scratch" => true,

				];
				WFFN_Core()->landing_pages->admin->register_template( 'gutenberg_1', $empty_template, 'gutenberg' );
			}

			return [];
		}

		public function allow_theme_css( $is, $post_id ){

			$design = WFFN_Core()->landing_pages->get_page_design( $post_id );
			if ( is_array( $design ) && isset( $design['selected_type'] ) && 'gutenberg' === $design['selected_type'] ) {
				return true;
			}

			return $is;
		}

	}

	WFFN_Landing_Pages_Gutenberg::get_instance();
}
