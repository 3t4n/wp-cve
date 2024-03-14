<?php //phpcs:ignore WordPress.WP.TimezoneChange.DeprecatedSniff

defined( 'ABSPATH' ) || exit; //Exit if accessed directly

/**
 * Class WFFN_Landing_Pages_Oxygen
 */
if ( ! class_exists( 'WFFN_Landing_Pages_Oxygen' ) ) {
	#[AllowDynamicProperties]

  class WFFN_Landing_Pages_Oxygen {
		private static $ins = null;
		private $edit_id = 0;

		/**
		 * WFFN_Landing_Pages_Oxygen constructor.
		 */
		private function __construct() {
			$this->register();
			add_action( 'wp', [ $this, 'disable_signature_checking' ] );
			add_action( 'init', [ $this, 'init_extension' ], 21 );

			add_action( 'wffn_wflp_before_register_templates', array( $this, 'add_default_templates' ),18);
		}

		public function disable_signature_checking() {
			global $post;

			if ( ! is_null( $post ) && $post->post_type === WFFN_Core()->landing_pages->get_post_type_slug() ) {
				WFFN_OXYGEN::disable_signature_checking();
			}
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
			add_action( 'admin_bar_menu', [ $this, 'add_admin_bar_link' ], 1003 );

		}

		public function assign_empty_template( $post_id ) {
			if ( $post_id < 1 ) {
				return;
			}
		}


		public function init_extension() {
			if ( WFFN_OXYGEN::is_template_editor() ) {
				add_action( 'wp', [ $this, 'remove_register_template' ] );

				return;
			}
			$post_id = 0;
			if ( isset( $_REQUEST['post_id'] ) && $_REQUEST['post_id'] > 0 ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = absint( $_REQUEST['post_id'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			} elseif ( isset( $_REQUEST['oxy_wffn_lp_id'] ) ) {//phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$post_id = absint( $_REQUEST['oxy_wffn_lp_id'] );//phpcs:ignore WordPress.Security.NonceVerification.Recommended
			}
			$post = get_post( $post_id );
			if ( ! is_null( $post ) && $post->post_type === WFFN_Core()->landing_pages->get_post_type_slug() ) {
				$this->remove_register_template();
				WFFN_OXYGEN::disable_signature_checking();
			}

			add_action( 'wp', [ $this, 'remove_register_template' ] );

		}

		public function add_default_templates() {

			$template = [
				'slug'        => 'oxy',
				'title'       => __( 'Oxygen', 'funnel-builder' ),
				'button_text' => __( 'Edit', 'funnel-builder' ),
				'description' => __( 'Use Oxygen Builder modules to create your own designs. Or pick from professionally-designed templates.', 'funnel-builder' ),
				'edit_url'    => add_query_arg( [
					'ct_builder'     => true,
					'oxy_wffn_lp_id' => WFFN_Core()->landing_pages->get_edit_id(),
				], get_permalink( WFFN_Core()->landing_pages->get_edit_id() ) ),
			];

			WFFN_Core()->landing_pages->admin->register_template_type( $template );
			$templates = WooFunnels_Dashboard::get_all_templates();
			$designs   = isset( $templates['landing'] ) ? $templates['landing'] : [];

			if ( isset( $designs['oxy'] ) && is_array( $designs['oxy'] ) ) {
				foreach ( $designs['oxy'] as $d_key => $templates ) {

					if ( isset( $templates['pro'] ) && 'yes' === $templates['pro'] ) {
						$templates['license_exist'] = WFFN_Core()->admin->get_license_status();
					}
					WFFN_Core()->landing_pages->admin->register_template( $d_key, $templates, 'oxy' );

				}
			} else {

				$empty_template = [
					"type"               => "view",
					"import"             => "no",
					"show_import_popup"  => "no",
					"slug"               => "oxy_1",
					"build_from_scratch" => true,

				];
				WFFN_Core()->landing_pages->admin->register_template( 'oxy_1', $empty_template, 'oxy' );
			}

			return [];
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
					if ( ! is_null( $post ) && $post->post_type === WFFN_Core()->landing_pages->get_post_type_slug() ) {
						$wfacp_id     = $post->ID;
						$href         = $node['href'];
						$node['href'] = add_query_arg( [ 'ct_builder' => 'true', 'oxy_wffn_lp_id' => $wfacp_id ], $href );
						$wp_admin_bar->add_node( $node );
					}
				}
			}
		}

		public function remove_register_template() {
			$id   = WFFN_Core()->landing_pages->get_edit_id();
			$post = get_post( $id );
			if ( is_null( $post ) ) {
				global $post; //phpcs:ignore VariableAnalysis.CodeAnalysis.VariableAnalysis
			}

			$ct_builder = ( isset( $_GET['ct_builder'] ) && $_GET['ct_builder'] === 'true' ) ? true : false;//phpcs:ignore WordPress.Security.NonceVerification.Recommended

			if ( ! is_null( $post ) && $post->post_type === WFFN_Core()->landing_pages->get_post_type_slug() ) {
				$design = WFFN_Core()->landing_pages->get_page_design( $post->ID );
				if ( ( $ct_builder === true ) || ( is_array( $design ) && isset( $design['selected_type'] ) && 'oxy' === $design['selected_type'] ) ) {
					remove_action( 'template_include', [ WFFN_Landing_Pages::get_instance(), 'may_be_change_template' ], 99 );
				}
			}

		}

	}

	WFFN_Landing_Pages_Oxygen::get_instance();
}
