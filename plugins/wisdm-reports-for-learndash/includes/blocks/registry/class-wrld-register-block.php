<?php
/**
 * This file contains a Common class which can be used to register all the blocks.
 *
 * @package learndash-reports-by-wisdmlabs
 */

namespace WisdmReportsLearndashBlockRegistry;

if ( ! class_exists( '\WisdmReportsLearndashBlockRegistry\WRLD_Register_Block' ) ) {
	/**
	 * Usage new WRLD_Register_Block('block-slug', 'Block Title', 'Block Description','wether block has server side render function');
	 */
	abstract class WRLD_Register_Block {
		/**
		 * Block namespace.
		 *
		 * @var string
		 */
		protected $namespace = 'wisdm-learndash-reports';

		/**
		 * Block Category: Section in which the block will be displayed in the editor
		 *
		 * @var string
		 */
		protected $block_category = 'wisdm-learndash-reports';

		/**
		 * Block name within this namespace.
		 *
		 * @var string
		 */
		protected $block_name = '';

		/**
		 * Block Title.
		 *
		 * @var string
		 */
		protected $block_title = '';

		/**
		 * Description for the block
		 *
		 * @var string
		 */
		protected $description = '';

		/**
		 * Handle of the registered block editor script.
		 *
		 * @var string
		 */
		protected $editor_script_handle = '';

		/**
		 * Handle of the registered block editor style.
		 *
		 * @var string
		 */
		protected $editor_style_handle = '';


		/**
		 * Handle of the registered block script for front-end.
		 *
		 * @var string
		 */
		protected $front_end_script_handle = '';

		/**
		 * Handle of the registered block style for front-end rendering.
		 *
		 * @var string
		 */
		protected $front_end_style_handle = '';

		/**
		 * Block name within this namespace.
		 *
		 * @var string
		 */
		protected $api_version = 2;

		/**
		 * Tracks if assets have been enqueued.
		 *
		 * @var boolean
		 */
		protected $enqueued_assets = false;

		/**
		 * Server side rendering callback function name if required
		 *
		 * @var string
		 */
		protected $server_side_callback = '';

		/**
		 * Registers block assets based on the block name from the build path
		 * stores the scrit & style handles in the object properties for later
		 * use.
		 */
		protected function wrld_register_block_assets() {
			$editor_script_handle = $this->get_editor_script_handle();
			$editor_style_handle  = $this->get_editor_style_handle();

			$editor_script_path = $this->get_editor_script_url();
			$editor_style_path  = $this->get_editor_style_sheet_url();
			$editor_script_deps = $this->get_editor_script_dependancies();

			$front_end_script_handle = $this->get_front_end_script_handle();
			$front_end_style_handle  = $this->get_front_end_style_handle();

			$frontend_script_path = $this->get_front_end_script_url();
			$frontend_style_path  = $this->get_front_end_style_sheet_url();
			$frontend_script_deps = $this->get_front_end_script_dependancies();

			// Register & localize editor Script.
			wp_register_script( $editor_script_handle, $editor_script_path, $editor_script_deps['dependencies'], $editor_script_deps['version'], true );
			$script_variable_name = str_replace( '-', '_', $editor_script_handle );
			$script_data          = $this->get_script_localization_data( $script_variable_name );
			wp_localize_script( $editor_script_handle, $script_variable_name, $script_data );
			wp_register_style( $editor_style_handle, $editor_style_path, null, $editor_script_deps['version'] );

			$frontend_script_deps['dependencies'][] = 'wp-api-fetch';
			$frontend_script_deps['dependencies'][] = 'wrld-common-script';
			wp_register_script( $front_end_script_handle, $frontend_script_path, $frontend_script_deps['dependencies'], $frontend_script_deps['version'], true );
			$script_variable_name = str_replace( '-', '_', $front_end_script_handle );
			$script_data          = $this->get_script_localization_data( $script_variable_name );
			wp_localize_script( $front_end_script_handle, $script_variable_name, $script_data );
			wp_register_style( $front_end_style_handle, $frontend_style_path, array( 'dashicons' ), $frontend_script_deps['version'] );

			// Wp register script.
			$this->editor_script_handle    = $editor_script_handle;
			$this->editor_style_handle     = $editor_style_handle;
			$this->front_end_script_handle = $front_end_script_handle;
			$this->front_end_style_handle  = $front_end_style_handle;

			$language_files_path = apply_filters( 'wrld_language_files_path', WRLD_REPORTS_PATH . '/languages' );

			// Set / Load Translation Files.
			wp_set_script_translations( $editor_script_handle, 'learndash-reports-by-wisdmlabs', $language_files_path );
			wp_set_script_translations( $front_end_script_handle, 'learndash-reports-by-wisdmlabs', $language_files_path );
		}

		/**
		 * Returns the name to be used for the editor script_handle.
		 * Eg for the block reports-header the function will return
		 *
		 * 'wisdm-learndash-reports-editor-script-reports-header'
		 */
		protected function get_editor_script_handle() {
			return $this->namespace . '-editor-script-' . $this->block_name;
		}

		/**
		 * Returns the name to be used for the editor style handle.
		 * Eg for the block reports-header the function will return
		 *
		 * 'wisdm-learndash-reports-editor-style-reports-header'
		 */
		protected function get_editor_style_handle() {
			return $this->namespace . '-editor-style-' . $this->block_name;
		}


		/**
		 * Returns the name to be used for the index/front-end script_handle.
		 * Eg for the block reports-header the function will return
		 *
		 * 'wisdm-learndash-reports-front-end-script-reports-header'
		 */
		protected function get_front_end_script_handle() {
			return $this->namespace . '-front-end-script-' . $this->block_name;
		}

		/**
		 * Returns the name to be used for the index/front-end script_handle.
		 * Eg for the block reports-header the function will return
		 *
		 * 'wisdm-learndash-reports-front-end-script-reports-header'
		 */
		protected function get_front_end_style_handle() {
			return $this->namespace . '-front-end-style-' . $this->block_name;
		}

		/**
		 * This function returns the block asset path, i.e the path of the build folder where all the
		 * gutenber block assets of the plugin are stored.
		 */
		protected function get_block_assets_path() {
			$asset_path = WRLD_REPORTS_PATH . '/includes/blocks/builds/';
			/**
			 * This filter will allow to change the path of the default assets folder
			 */
			$asset_path = apply_filters( 'wrld_reports_asset_path', $asset_path, $this->namespace, $this->block_name );

			return $asset_path;
		}

		/**
		 * This function returns the block asset path, i.e the path of the build folder where all the
		 * gutenber block assets of the plugin are stored.
		 */
		protected function get_block_assets_url() {
			$asset_url = WRLD_REPORTS_SITE_URL . '/includes/blocks/builds/';
			/**
			 * This filter will allow to change the url of the default assets folder
			 */
			$asset_url = apply_filters( 'wrld_reports_asset_url', $asset_url, $this->namespace, $this->block_name );

			return $asset_url;
		}

		/**
		 * Prepares & returns tha editor script path on
		 */
		protected function get_editor_script_url() {
			$script_name = 'editor-' . $this->block_name . '.js';
			/**
			 * This filter will allow to make changes in the editor script name for the block.
			 */
			$script_name = apply_filters( 'wrld_reports_editor_script_name', $script_name, $this->namespace, $this->block_name );

			return $this->get_block_assets_url() . $script_name;
		}

		/**
		 * Generates & returns the url of the stylesheet to be used as a editor styleseet for the block.
		 *
		 * @return string
		 */
		protected function get_editor_style_sheet_url() {
			$style_name = 'editor-' . $this->block_name . '.css';
			if ( is_rtl() ) {
				$style_name = 'editor-' . $this->block_name . '.rtl.css';
			}
			/**
			 * This filter will allow to make changes in the editor script name for the block.
			 */
			$script_name = apply_filters( 'wrld_reports_editor_style_name', $style_name, $this->namespace, $this->block_name );

			return $this->get_block_assets_url() . $style_name;
		}

		/**
		 * Returns the dependancies autogenerated in the file.
		 *
		 * @return string
		 */
		protected function get_editor_script_dependancies() {
			$path_to_assets = $this->get_block_assets_path() . 'editor-' . $this->block_name . '.asset.php';
			$asset_file     = include $path_to_assets;
			return $asset_file;
		}


		/**
		 * Returns the url of the front-end script
		 *
		 * @return string
		 */
		protected function get_front_end_script_url() {
			$script_name = 'index-' . $this->block_name . '.js';
			/**
			 * This filter will allow to make changes in the editor script name for the block.
			 */
			$script_name = apply_filters( 'wrld_reports_editor_script_name', $script_name, $this->namespace, $this->block_name );

			return $this->get_block_assets_url() . $script_name;
		}

		/**
		 * Returns teh url of the front-end stylesheet
		 *
		 * @return string
		 */
		protected function get_front_end_style_sheet_url() {
			$style_name = 'index-' . $this->block_name . '.css';
			if ( is_rtl() ) {
				$style_name = 'index-' . $this->block_name . '.rtl.css';
			}
			/**
			 * This filter will allow to make changes in the editor script name for the block.
			 */
			$script_name = apply_filters( 'wdm_ld_reports_editor_style_name', $style_name, $this->namespace, $this->block_name );

			return $this->get_block_assets_url() . $style_name;
		}


		/**
		 * Returns the dependancies autogenerated in the file
		 *
		 * @return string
		 */
		protected function get_front_end_script_dependancies() {
			$path_to_assets = $this->get_block_assets_path() . 'index-' . $this->block_name . '.asset.php';
			$asset_file     = include $path_to_assets;
			return $asset_file;
		}



		/**
		 * Registers the block with the enqueued assets
		 */
		protected function wrld_register_block_type() {
			register_block_type(
				$this->get_block_name_with_name_space(),
				array(
					'api_version'     => $this->api_version,
					'render_callback' => array( $this, $this->server_side_callback ),
					'title'           => $this->block_title,
					'category'        => $this->block_category,
					'description'     => $this->description,
					'text-domain'     => 'learndash-reports-by-wisdmlabs',
					'keywords'        => array( 'learndash', 'reports', 'header', 'graphs' ),
					'script'          => $this->front_end_script_handle, // front-end-script-handle.
					'editor_script'   => $this->editor_script_handle,
					'editor_style'    => $this->editor_style_handle,
					'style'           => $this->front_end_style_handle,
				)
			);
		}

		/**
		 * Concatinated the block namespcae with the block name & returns
		 * the block name to be used for registration
		 */
		protected function get_block_name_with_name_space() {
			return $this->namespace . '/' . $this->block_name;
		}


		/**
		 * Gets localization data for the script getting registerd & localized that data in the script variable.
		 *
		 * @param string $script_variable_name name of the script variable.
		 */
		protected function get_script_localization_data( $script_variable_name ) {
			$report_type = 'default-ld-reports';
			if ( isset( $_GET['ld_report_type'] ) && 'quiz-reports' == $_GET['ld_report_type'] ) {
				$report_type = 'quiz-reports';
			}
			if ( ( isset( $_GET['screen'] ) && 'quiz' == $_GET['screen'] ) || isset( $_GET['pageno'] ) ) {
				$report_type = 'quiz-reports';
			}
			$currency = function_exists( 'learndash_get_currency_symbol' ) ? learndash_get_currency_symbol() : '';
			$currency = empty( $currency ) && function_exists( 'learndash_30_get_currency_symbol' ) ? @learndash_30_get_currency_symbol() : $currency;
			$currency = empty( $currency ) ? '$' : $currency;
			$data     = array(
				'plugin_asset_url'          => WRLD_REPORTS_SITE_URL . '/assets',
				'is_pro_version_active'     => apply_filters( 'wisdm_ld_reports_pro_version', false ),
				'upgrade_link'              => 'https://wisdmlabs.com/reports-for-learndash/?utm_source=google&utm_term=FreeToPro',
				'is_admin_user'             => current_user_can( 'manage_options' ),
				'currency_in_use'           => apply_filters( 'wrld_currency_in_use', $currency ),
				'user_roles'                => $this->get_roles_of_current_user(),
				'report_type'               => $report_type,
				'ajaxurl'                   => admin_url( 'admin-ajax.php' ),
				'report_nonce'              => wp_create_nonce( 'wisdm_ld_reports_page' ),
				'is_idle_tracking_enabled'  => get_option( 'wrld_time_tracking_status', false ),
				'idle_tracking_active_from' => get_option( 'wrld_time_tracking_last_update', false ) == '' ? 'Not Configured' : date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), get_option( 'wrld_time_tracking_last_update', false ) ),
				'time_tacking_setting_url'  => admin_url( 'admin.php?page=wrld-settings#wlrd-dashboard-time-settings' ),
				'user_roles'                => $this->get_roles_of_current_user(),
			);

			return apply_filters( $script_variable_name, $data );
		}

		/**
		 * Returns the user roles of the currently logged in user
		 *
		 * @return array $userRoles Array of user role slugs
		 */
		protected function get_roles_of_current_user() {
			$user_roles = array();
			$user       = wp_get_current_user();
			if ( ! empty( $user ) ) {
				$user_roles = (array) $user->roles;
			}
			return $user_roles;
		}
	}
}
