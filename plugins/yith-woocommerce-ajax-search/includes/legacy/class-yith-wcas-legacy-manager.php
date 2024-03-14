<?php
/**
 * This class instance the Legacy classes
 *
 * @package YITH WooCommerce Ajax Search
 * @since   1.0.0
 * @author  YITH <plugins@yithemes.com>
 * @deprecated 2.0.0
 */

if ( ! class_exists( 'YITH_WCAS_Legacy_Manager' ) ) {
	/**
	 * Class YITH_WCAS_Legacy_Manager
	 */
	class YITH_WCAS_Legacy_Manager {
		use YITH_WCAS_Trait_Singleton;

		/**
		 * Constructor
		 *
		 * @return void
		 */
		protected function __construct() {
			if ( ! yith_wcas_user_switch_to_block() ) {
				$this->init_legacy_wcas();


				add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ), 20 );
				add_action( 'admin_print_footer_scripts', array( $this, 'show_popup' ), 25 );
				add_action( 'admin_action_ywcas_do_widget_upgrade', array( $this, 'register_admin_action' ) );
				add_filter( 'ywcas_search_fields_type', array( $this, 'remove_unsupported_search_fields' ), 99, 1 );
				add_filter( 'ywcas_disable_search_input_options', array(
					$this,
					'add_disable_search_input_options'
				), 99, 1 );
			}
			YITH_WCAS_Elementor::get_instance();
			add_action( 'init', array( $this, 'legacy_gutenberg_integration' ) );
		}

		/**
		 * Instance the old ajax search engine
		 *
		 * @return void
		 */
		protected function init_legacy_wcas() {
			new YITH_WCAS_Legacy_Frontend();
			new YITH_WCAS_Legacy();
		}

		/**
		 * Gutenberg Integration
		 */
		public function legacy_gutenberg_integration() {
			if ( function_exists( 'yith_plugin_fw_gutenberg_add_blocks' ) ) {
				$blocks = apply_filters(
					'ywcas_gutenberg_blocks',
					array(
						'yith-woocommerce-ajax-search' => array(
							'title'          => _x( 'Ajax Search[Deprecated]', '[gutenberg]: block name', 'yith-woocommerce-ajax-search' ),
							'description'    => _x( 'Show Ajax Search Form', '[gutenberg]: block description', 'yith-woocommerce-ajax-search' ),
							'shortcode_name' => 'yith_woocommerce_ajax_search',
							'do_shortcode'   => false,
							'keywords'       => array(
								_x( 'Ajax Search', '[gutenberg]: keywords', 'yith-woocommerce-ajax-search' ),
								_x( 'Search', '[gutenberg]: keywords', 'yith-woocommerce-ajax-search' ),
							),
							'attributes'     => array(
								'template' => array(
									'type'    => 'select',
									'label'   => _x( 'Template', '[gutenberg]: show or hide the thumbnail', 'yith-woocommerce-ajax-search' ),
									'default' => '',
									'options' => array(
										''     => _x( 'Default', '[gutenberg]: Help to show thumbnail', 'yith-woocommerce-ajax-search' ),
										'wide' => _x( 'Wide', '[gutenberg]: Help to hide thumbnail', 'yith-woocommerce-ajax-search' ),
									),
								),
								'class'    => array(
									'type'    => 'text',
									'label'   => _x( 'Class', '[gutenberg]: class of widget', 'yith-woocommerce-ajax-search' ),
									'default' => '',
								),
							),

						),
					)
				);

				yith_plugin_fw_gutenberg_add_blocks( $blocks );
			}
		}

		/**
		 * Enqueue the script for the modal upgrade
		 *
		 * @return void
		 */
		public function enqueue_scripts() {
			if ( isset( $_GET['page'] ) && 'yith_wcas_panel' === sanitize_text_field( $_GET['page'] ) ) { //phpcs:ignore
				$deps = include_once YITH_WCAS_DIR . 'assets/js/admin/build/upgrade-modal.asset.php';
				wp_enqueue_script( 'ywcas-upgrade-modal', yit_load_js_file( YITH_WCAS_ASSETS_URL . '/js/admin/build/upgrade-modal.js' ), $deps['dependencies'], YITH_WCAS_VERSION, true );
			}
		}

		/**
		 * Include the template
		 *
		 * @return void
		 */
		public function show_popup() {

			if ( isset( $_GET['page'] ) && 'yith_wcas_panel' === sanitize_text_field( $_GET['page'] ) ) { //phpcs:ignore

				require_once YITH_WCAS_DIR . 'includes/admin/views/upgrade-modal/upgrade-modal.php';
			}
		}

		/**
		 * This action store an option about convert the old shortcode with the new block system
		 *
		 * @return void
		 */
		public function register_admin_action() {
			$return_url = add_query_arg(
				array(
					'page' => 'yith_wcas_panel',
				),
				admin_url( 'admin.php' )
			);

			// phpcs:ignore WordPress.Security.ValidatedSanitizedInput
			if ( ! isset( $_REQUEST['_wpnonce'] ) || ( ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'do_widget_upgrade' ) && ! wp_verify_nonce( $_REQUEST['_wpnonce'], 'debug_action' ) ) ) {
				wp_safe_redirect( $return_url );
				die;
			}

			$options = get_option( "widget_yith_woocommerce_ajax_search", array() );
			foreach ( $options as $key => $option ) {
				if ( is_array( $option ) ) {
					unset( $options[ $key ]['template'] );
					unset( $options[ $key ]['filters_above'] );
					$options[ $key ]['preset'] = 'default';
				}
			}
			update_option( "widget_yith_woocommerce_ajax_search", $options );
			update_option( 'ywcas_user_switch_to_block', true );
			wp_safe_redirect( $return_url );
			die;
		}


		/**
		 * Remove the unsupported search field type
		 *
		 * @param array $search_fields The search field type.
		 *
		 * @return array
		 */
		public function remove_unsupported_search_fields( $search_fields ) {

			if ( isset( $search_fields['product_attributes'] ) ) {
				unset( $search_fields['product_attributes'] );
			}

			return $search_fields;
		}

		/**
		 * Add in the array the option unsupported for legacy
		 *
		 * @param array $disabled_options The disabled options.
		 *
		 * @return array
		 */
		public function add_disable_search_input_options( $disabled_options ) {
			$disabled_options[] = 'include';
			$disabled_options[] = 'exclude';

			return $disabled_options;
		}
	}
}
