<?php
/**
 * Admin class
 *
 * @author  YITH
 * @package YITH/Search
 * @version 2.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'YITH_WCAS_Admin' ) ) {
	/**
	 * Admin class.
	 * The class manage all the admin behaviors.
	 *
	 * @since 2.0.0
	 */
	class YITH_WCAS_Admin {
		/**
		 * Plugin options
		 *
		 * @var array
		 * @access public
		 * @since  1.0.0
		 */
		public $options = array();

		/**
		 * Panel Object
		 *
		 * @var $_panel Panel Object
		 */
		protected $panel; //phpcs:ignore


		/**
		 * Panel Page Name
		 *
		 * @var string Ajax Search panel page
		 */
		protected $panel_page = 'yith_wcas_panel'; //phpcs:ignore


		/**
		 * Constructor
		 *
		 * @access public
		 * @since  1.0.0
		 */
		public function __construct() {
			// Actions.
			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );
			add_filter(
				'plugin_action_links_' . plugin_basename( YITH_WCAS_DIR . '/' . basename( YITH_WCAS_FILE ) ),
				array(
					$this,
					'action_links',
				)
			);
			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 5 );
			add_filter(
				'yith_plugin_fw_get_field_template_path',
				array(
					$this,
					'get_yith_panel_custom_template',
				),
				10,
				2
			);
			add_filter( 'admin_init', array( $this, 'save_search_field_option' ), 10 );
			add_action( 'admin_init', array( $this, 'save_synonymous_field' ), 100 );
			add_action( 'wp_ajax_yith_wcas_start_index', array( $this, 'start_index' ) );
			add_action( 'wp_ajax_yith_wcas_update_index', array( $this, 'update_index' ) );

			// Add the shortcodes tab.
			add_action( 'ywcas_show_shortcode_tab', array( $this, 'show_shortcode_tab' ) );
			add_action( 'wp_ajax_yith_wcas_save_shortcode', array( $this, 'save_shortcode' ) );

			// Add the statistic tab.
			add_action( 'ywcas_show_statistic_tab', array( $this, 'show_statistic_tab' ) );
			add_action( 'wp_ajax_yith_wcas_filter_statistic', array( $this, 'handle_filter_statistic' ) );


		}

		/**
		 * Start index
		 *
		 * @return void
		 */
		public function start_index() {
			check_ajax_referer( 'ywcas-search-index', 'security' );
			if ( isset( $_REQUEST['form'] ) ) { //phpcs:ignore
				parse_str( $_REQUEST['form'], $data ); //phpcs:ignore

				$option_to_check = $data['ywcas-search-fields'];
				$key_values      = array_column( $option_to_check, 'priority' );
				array_multisort( $key_values, SORT_ASC, $option_to_check );

				$old_option = ywcas()->settings->get_search_fields();
				if ( wp_json_encode( $option_to_check ) !== wp_json_encode( $old_option ) ) {
					update_option( 'yith_wcas_search_fields', $option_to_check );
				}
			}

			$process_id = ywcas()->indexer->process_data();

			ob_start();
			yith_plugin_fw_get_field(
				array(
					'name' => _x( 'Index status', 'Admin label option', 'yith-woocommerce-ajax-search' ),
					'desc' => '',
					'id'   => 'yith_wcas_index',
					'type' => 'ywcas-index',
				),
				true
			);

			$content = ob_get_clean();
			wp_send_json_success(
				array(
					'content'    => $content,
					'process_id' => $process_id,
				)
			);
		}

		/**
		 * Update the index status.
		 *
		 * @return void
		 */
		public function update_index() {
			check_ajax_referer( 'ywcas-search-index', 'security' );
			ob_start();
			yith_plugin_fw_get_field(
				array(
					'name' => _x( 'Index status', 'Admin label option', 'yith-woocommerce-ajax-search' ),
					'desc' => '',
					'id'   => 'yith_wcas_index',
					'type' => 'ywcas-index',
				),
				true
			);

			$content = ob_get_clean();
			wp_send_json_success( array( 'content' => $content ) );
		}


		/**
		 * Add a panel under YITH Plugins tab
		 *
		 * @return   void
		 * @since    2.0.0
		 * @use      /Yit_Plugin_Panel class
		 * @see      plugin-fw/lib/yit-plugin-panel.php
		 */
		public function register_panel() {

			if ( ! empty( $this->panel ) ) {
				return;
			}

			$args = apply_filters(
				'ywcas_register_panel_arguments',
				array(
					'create_menu_page' => true,
					'parent_slug'      => '',
					'page_title'       => $this->get_product_name(),
					'menu_title'       => 'Ajax Search',
					'capability'       => apply_filters( 'yith_wcas_panel_capability', 'manage_options' ),
					'parent'           => '',
					'parent_page'      => 'yith_plugin_panel',
					'page'             => $this->panel_page,
					'admin-tabs'       => $this->get_admin_tabs(),
					'class'            => yith_set_wrapper_class(),
					'options-path'     => YITH_WCAS_DIR . '/plugin-options',
					'ui_version'       => 2,
					'is_premium'       => defined( 'YITH_WCAS_PREMIUM' ),
					'plugin_slug'      => YITH_WCAS_SLUG,
					'plugin_icon'      => YITH_WCAS_ASSETS_URL . '/images/plugin-icon.svg',

				)
			);

			if ( file_exists( YITH_WCAS_DIR . '/plugin-options/premium-tab.php' ) && ! defined( 'YITH_WCAS_PREMIUM' ) ) {
				$args['premium_tab'] = include_once YITH_WCAS_DIR . '/plugin-options/premium-tab.php';
			}

			if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
				require_once 'plugin-fw/lib/yit-plugin-panel-wc.php';
			}

			$this->panel = new YIT_Plugin_Panel_WooCommerce( $args );

		}

		/**
		 * Return the admin tabs
		 *
		 * @return array
		 */
		protected function get_admin_tabs() {
			$admin_tabs = array(
				'statistic'     => array(
					'title' => _x( 'Search Stats', 'Admin tab name', 'yith-woocommerce-ajax-search' ),
					'icon'  => '<svg fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
  <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
</svg>',
				),
				'general'       => array(
					'title'       => __( 'General Options', 'yith-woocommerce-ajax-search' ),
					'icon'        => 'settings',
					'description' => __( 'Set the general options for the plugin behavior.', 'yith-woocommerce-ajax-search' )
				),
				'search-fields' => array(
					'title'       => __( 'Search Fields', 'yith-woocommerce-ajax-search' ),
					'icon'        => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 010 3.75H5.625a1.875 1.875 0 010-3.75z"></path>
</svg>',
					'description' => _x(
						'Choose which fields will be used when a search is performed in your shop and set the priority for each enabled field.',
						'description of "Search field" section',
						'yith-woocommerce-ajax-search'
					),
				),
				'shortcodes'    => array(
					'title'       => __( 'Search Shortcodes', 'yith-woocommerce-ajax-search' ),
					'description' => _x(
						'Create and customize your search shortcodes.',
						'description of "Search shortcodes" section',
						'yith-woocommerce-ajax-search'
					),
					'icon'        => '<svg fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
  <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m5.231 13.481L15 17.25m-4.5-15H5.625c-.621 0-1.125.504-1.125 1.125v16.5c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9zm3.75 11.625a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"></path>
</svg>',
				),
			);

			return apply_filters( 'ywcas_admin_tabs', $admin_tabs );
		}

		/**
		 * Return the product name
		 */
		protected function get_product_name() {
			return 'YITH WooCommerce Ajax Search';
		}

		/**
		 * Add custom panel fields.
		 *
		 * @param string $template Template.
		 * @param array  $field Fields.
		 *
		 * @return string
		 * @author YITH
		 * @since  2.0.0
		 */
		public function get_yith_panel_custom_template( $template, $field ) {
			$custom_option_types = apply_filters(
				'ywcas_custom_option_types',
				array(
					'search-fields',
					'ywcas-slider',
					'ywcas-synonymous',
					'ywcas-index',
				)
			);
			$field_type          = $field['type'];
			if ( isset( $field['type'] ) && in_array( $field['type'], $custom_option_types, true ) ) {
				$template = YITH_WCAS_INC . "/admin/views/custom-fields/types/{$field_type}.php";
			}

			return $template;
		}

		/**
		 * Save the search field option
		 *
		 * @return void
		 * @since  1.0.0
		 */
		public function save_search_field_option() {

			if ( ! isset( $_REQUEST['yit_panel_wc_options_nonce'], $_REQUEST['ywcas-search-fields'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['yit_panel_wc_options_nonce'] ) ), 'yit_panel_wc_options_' . $this->panel->settings['page'] ) ) {
				return;
			}

			$fields     = wc_clean( $_REQUEST['ywcas-search-fields'] ); //phpcs:ignore
			$key_values = array_column( $fields, 'priority' );
			array_multisort( $key_values, SORT_ASC, $fields );
			if ( $fields ) {
				$option = array();
				foreach ( $fields as $field ) {
					switch ( $field['type'] ) {
						case 'name':
						case 'description':
						case 'summary':
							$option[] = array(
								'type'     => $field['type'],
								'priority' => $field['priority'],
							);
							break;
					}

					$option = apply_filters( 'yith_wcas_search_fields_saved_option', $option, $field );

				}
			}

			update_option( 'yith_wcas_search_fields', $option ); //phpcs:ignore

		}

		/**
		 * Save Synonymous Field
		 *
		 * @return void
		 */
		public function save_synonymous_field() {
			if ( ! isset( $_REQUEST['yit_panel_wc_options_nonce'], $_REQUEST['yith_wcas_synonymous'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_REQUEST['yit_panel_wc_options_nonce'] ) ), 'yit_panel_wc_options_' . $this->panel->settings['page'] ) ) {
				return;
			}

			$fields = wc_clean( $_REQUEST['yith_wcas_synonymous'] ); //phpcs:ignore
			update_option( 'yith_wcas_synonymous', array_filter( $fields ) );
		}

		/**
		 * Add the action links to plugin admin page
		 *
		 * @param array $links Links plugin array.
		 *
		 * @return mixed
		 * @use plugin_action_links_{$plugin_file_name}
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @since    1.0
		 */
		public function action_links( $links ) {
			$links = yith_add_action_links( $links, $this->panel_page, defined( 'YITH_WCAS_PREMIUM' ), YITH_WCAS_SLUG );

			return $links;
		}

		/**
		 * Add the action links to plugin admin page.
		 *
		 * @param array  $new_row_meta_args Plugin Meta New args.
		 * @param array  $plugin_meta Plugin Meta.
		 * @param string $plugin_file Plugin file.
		 * @param array  $plugin_data Plugin data.
		 * @param string $status Status.
		 * @param string $init_file Init file.
		 *
		 * @return   Array
		 * @since    1.0
		 * @author   Andrea Grillo <andrea.grillo@yithemes.com>
		 * @use plugin_row_meta
		 */
		public function plugin_row_meta( $new_row_meta_args, $plugin_meta, $plugin_file, $plugin_data, $status, $init_file = 'YITH_WCAS_FREE_INIT' ) {
			if ( defined( $init_file ) && constant( $init_file ) === $plugin_file ) {
				$new_row_meta_args['slug']       = YITH_WCAS_SLUG;
				$new_row_meta_args['is_premium'] = false;
			}

			return $new_row_meta_args;
		}

		/**
		 * Show the shortcode tab.
		 *
		 * @return void
		 */
		public function show_shortcode_tab() {
			if ( isset( $_GET['page'], $_GET['tab'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$page = sanitize_text_field( wp_unslash( $_GET['page'] ) ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$tab  = sanitize_text_field( wp_unslash( $_GET['tab'] ) );  //phpcs:ignore WordPress.Security.NonceVerification.Recommended

				if ( 'yith_wcas_panel' === $page && 'shortcodes' === $tab ) {
					$shortcodes = ywcas()->settings->get_shortcodes_list();
					include_once YITH_WCAS_INC . 'admin/views/panel/shortcodes.php';
				}
			}
		}


		/**
		 * Retrieve the documentation URL.
		 *
		 * @return string
		 */
		protected function get_doc_url(): string {
			return 'https://docs.yithemes.com/yith-woocommerce-ajax-search/';
		}

		/**
		 * Save the shortcode preset
		 *
		 * @return void
		 */
		public function save_shortcode() {
			check_ajax_referer( 'ywcas-search-shortcode', 'security' );

			if ( ! isset( $_REQUEST['ywcas_shortcode'], $_REQUEST['slug'] ) ) {
				wp_send_json_error();
			}

			$slug      = sanitize_text_field( wp_unslash( $_REQUEST['slug'] ) );
			$shortcode = wp_unslash( $_REQUEST['ywcas_shortcode'][ $slug ] ); //phpcs:ignore
			$name      = sanitize_text_field( wp_unslash( $shortcode['general']['name'] ) );

			$shortcodes = ywcas()->settings->get_shortcodes_list();

			$uncheck_fields = ywcas()->settings->get_shortcode_fields_to_check();

			foreach ( $uncheck_fields as $tab => $option ) {
				foreach ( $option as $option_key => $value ) {
					if ( ! isset( $shortcode[ $tab ][ $option_key ] ) ) {
						$shortcode[ $tab ][ $option_key ] = $value;
					}
				}
			}


			if ( isset( $shortcodes[ $slug ] ) ) {
				$shortcodes[ $slug ]['name']    = $name;
				$shortcodes[ $slug ]['options'] = $shortcode;
			} else {
				$new_slug = sanitize_title( $name );
				$slugs    = array_keys( $shortcodes );

				if ( in_array( $new_slug, $slugs, true ) ) {
					$i    = 1;
					$suff = $new_slug;
					do {
						$new_slug = $suff . $i ++;
					} while ( in_array( $new_slug, $slugs, true ) );
				}

				$shortcodes[ $new_slug ] = array(
					'name'    => $name,
					'code'    => "[yith_woocommerce_ajax_search preset='{$new_slug}']",
					'options' => $shortcode,
				);
			}

			ywcas()->settings->update_shortcodes_list( $shortcodes );

			ob_start();
			include_once YITH_WCAS_INC . 'admin/views/panel/shortcodes.php';
			$content = ob_get_clean();
			wp_send_json_success( array( 'content' => $content ) );
		}

		/**
		 * Show the statistic tab
		 *
		 * @return void
		 * @since 2.1.0
		 */
		public function show_statistic_tab() {
			if ( isset( $_GET['page'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$page = sanitize_text_field( wp_unslash( $_GET['page'] ) ); //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$tab  = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
				if ( 'yith_wcas_panel' === $page && in_array( $tab, array( '', 'statistic' ) ) ) {
					if ( isset( $_GET['view_all'] ) ) {
						if ( isset( $_GET['from'], $_GET['to'] ) ) {
							$from = sanitize_text_field( wp_unslash( $_GET['from'] ) );
							$to   = sanitize_text_field( wp_unslash( $_GET['to'] ) );
						}

						$type = sanitize_text_field( wp_unslash( $_GET['view_all'] ) );  //phpcs:ignore WordPress.Security.NonceVerification.Recommended
						include_once YITH_WCAS_INC . 'admin/views/panel/statistic-detail.php';
					} else {
						include_once YITH_WCAS_INC . 'admin/views/panel/statistic.php';
					}
				}
			}
		}

		/**
		 * Filter the statistic by date
		 *
		 * @return void
		 * @since 2.1.0
		 */
		public function handle_filter_statistic() {

			check_ajax_referer( 'ywcas-search-statistic', 'security' );

			$from     = sanitize_text_field( wp_unslash( $_POST['from'] ) );
			$to       = sanitize_text_field( wp_unslash( $_POST['to'] ) );
			$isDetail = sanitize_text_field( wp_unslash( $_POST['isDetail'] ) );

			ob_start();
			if ( isset( $_POST['view_all'] ) && $isDetail ) {
				$type = sanitize_text_field( wp_unslash( $_POST['view_all'] ) );
				include_once YITH_WCAS_INC . 'admin/views/panel/statistic-detail.php';
			} else {
				$from .= $from ? ' 00:00:01' : '';
				$to   .= $to ? ' 23:59:59' : '';
				include_once YITH_WCAS_INC . 'admin/views/panel/statistic.php';
			}

			$newstats = ob_get_clean();

			wp_send_json_success( array( 'content' => $newstats ) );

		}
	}
}
