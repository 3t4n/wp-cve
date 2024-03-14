<?php

if ( ! class_exists( 'WFFN_Template_Importer' ) ) {
	class WFFN_Template_Importer {

		private static $instance = null;
		private static $importer = [];

		public function __construct() {
			require __DIR__ . '/remote/class-wffn-remote-template-importer.php';

			if ( class_exists( 'WFFN_Remote_Template_Importer' ) ) {
				WFFN_Core()->remote_importer = WFFN_Remote_Template_Importer::get_instance();
			}
			add_action( 'wffn_step_duplicated', array( $this, 'maybe_clear_cache' ) );
		}

		public static function get_instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		public static function register( $builder, $importer ) {
			if ( ! isset( self::$importer[ $builder ] ) && $importer instanceof WFFN_Import_Export ) {
				self::$importer[ $builder ] = $importer;
			}
		}

		public function is_empty_template( $builder, $slug, $type ) {

			if ( 'wp_editor' === $builder ) {
				return true;
			}

			$templates     = WooFunnels_Dashboard::get_all_templates();
			$template_data = $templates[ $type ][ $builder ][ $slug ];

			if ( ! isset( $template_data['build_from_scratch'] ) ) {
				return false;
			}

			return wffn_string_to_bool( $template_data['build_from_scratch'] );
		}

		/**
		 * @param $module_id
		 * @param $builder
		 * @param $slug
		 * @param $step
		 *
		 * @return array
		 */
		public function import_remote( $module_id, $builder, $slug, $step ) {
			$result = [ 'success' => false, 'error' => __( 'We are having trouble importing this template, Please contact support.', 'funnel-builder' ) ];

			do_action( 'wffn_template_import_remote', $module_id, $builder, $slug, $step );


			$template_file_path = $builder . '/' . $step . '/' . $slug;
			if ( ! file_exists( WFFN_TEMPLATE_UPLOAD_DIR . $template_file_path . '.json' ) ) {
				//Pull Template from cloud
				$content = WFFN_Core()->remote_importer->get_remote_template( $step, $slug, $builder );

			} else {
				$content = file_get_contents( WFFN_TEMPLATE_UPLOAD_DIR . $template_file_path . '.json' );
				unlink( WFFN_TEMPLATE_UPLOAD_DIR . $template_file_path . '.json' ); //phpcs:ignore WordPressVIPMinimum.Functions.RestrictedFunctions.file_ops_unlink
			}

			if ( empty( $content ) ) {
				return $result;
			}

			if ( is_array( $content ) && isset( $content['error'] ) ) {
				$result['error'] = $content['error'];

				return $result;
			}
			$content = apply_filters( 'wffn_imported_template_content', $content, $module_id );
			$status  = $this->import( $module_id, $builder, $slug, $content );

			if ( is_array( $status ) ) {
				$result['success'] = ( isset( $status['success'] ) ) ? $status['success'] : $result['success'];
				$result['error']   = ( isset( $status['error'] ) ) ? $status['error'] : $result['error'];
			} else {
				$result['success'] = $status;
			}

			do_action( 'wffn_import_completed', $module_id, $step, $builder, $slug );

			return $result;
		}

		/**
		 * @param $module_id
		 * @param $builder
		 * @param $slug
		 *
		 * @return bool
		 */
		public function import( $module_id, $builder, $slug, $content = '' ) {

			if ( $builder === 'elementor' ) {
				if ( ( ! version_compare( get_bloginfo( 'version' ), '5.0', '>=' ) && ( version_compare( ELEMENTOR_VERSION, '2.8.0', '>=' ) ) ) ) {
					$message = sprintf( esc_html__( 'Elementor requires WordPress version %s+. please update the wordpress version to import the template.', 'funnel-builder' ), '5.0' );

					return [ 'error' => $message ];
				}
			}
			if ( $builder === 'divi' ) {
				$response = WFFN_Common::check_builder_status( 'divi' );
				if ( ! empty( $response['error'] ) ) {
					return [ 'error' => $response['error'] ];
				}

			}

			if ( isset( self::$importer[ $builder ] ) && self::$importer[ $builder ] instanceof WFFN_Import_Export && ! empty( $content ) ) {

				$importer = self::$importer[ $builder ];
				BWF_Logger::get_instance()->log( "Importing the " . $module_id, 'wffn_template_import' );
				BWF_Logger::get_instance()->log( "Content length the " . strlen( $content ), 'wffn_template_import' );
				$status = $importer->import( $module_id, $content );
				delete_post_meta( $module_id, '_tobe_import_template_type' );
				delete_post_meta( $module_id, '_tobe_import_template' );

				return $status;
			} else {
				BWF_Logger::get_instance()->log( "failed importing for " . $module_id . "-- builder" . $builder, 'wffn_template_import' );
			}

			return false;
		}

		/**
		 * @param $module_id
		 * @param $builder
		 * @param $slug
		 *
		 * @return array||null
		 */
		public function export( $module_id, $builder, $slug ) {
			if ( isset( self::$importer[ $builder ] ) && self::$importer[ $builder ] instanceof WFFN_Import_Export ) {
				$importer    = self::$importer[ $builder ];
				$export_data = $importer->export( $module_id, $builder, $slug );

				return $export_data;
			}

			return null;
		}

		public function maybe_clear_cache() {
			if ( class_exists( '\Elementor\Plugin' ) ) {
				$this->generate_kit();
				Elementor\Plugin::$instance->files_manager->clear_cache();
			}
		}

		public function generate_kit() {
			if ( is_null( Elementor\Plugin::$instance ) || ! Elementor\Plugin::$instance->kits_manager instanceof Elementor\Core\Kits\Manager ) {
				return;
			}
			$kit = Elementor\Plugin::$instance->kits_manager->get_active_kit();
			if ( $kit->get_id() ) {
				return;
			}
			$created_default_kit = Elementor\Plugin::$instance->kits_manager->create_default();
			if ( ! $created_default_kit ) {
				return;
			}
			update_option( Elementor\Core\Kits\Manager::OPTION_ACTIVE, $created_default_kit );
		}

	}

	WFFN_Core::register( 'importer', 'WFFN_Template_Importer' );
}
