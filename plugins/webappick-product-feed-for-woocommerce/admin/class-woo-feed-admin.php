<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://webappick.com/
 * @since      1.0.0
 *
 * @package    Woo_Feed
 * @subpackage Woo_Feed/admin
 * @author     Ohidul Islam <wahid@webappick.com>
 */
class Woo_Feed_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $woo_feed The ID of this plugin.
	 */
	private $woo_feed;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string $version The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string $woo_feed The name of this plugin.
	 * @param string $version The version of this plugin.
	 *
	 * @since    1.0.0
	 *
	 */
	public function __construct( $woo_feed, $version ) {
		$this->woo_feed = $woo_feed;
		$this->version  = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @param string $hook
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles( $hook ) {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in woo_feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The woo_feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$mainDeps = array();
		$ext      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.css' : '.min.css';
		if (
			false !== strpos( $hook, 'webappick' ) &&
			false !== strpos( $hook, 'feed' )
		) {
			wp_enqueue_style( 'thickbox' );
			wp_register_style(
				'selectize',
				plugin_dir_url( __FILE__ ) . 'css/selectize' . $ext,
				array(),
				$this->version
			);
			wp_enqueue_style(
				'fancy-select',
				plugin_dir_url( __FILE__ ) . 'css/fancy-select' . $ext,
				array(),
				$this->version
			);
			wp_register_style(
				'slick',
				plugin_dir_url( __FILE__ ) . 'css/slick' . $ext,
				array(),
				$this->version
			);
			wp_register_style(
				'slick-theme',
				plugin_dir_url( __FILE__ ) . 'css/slick-theme' . $ext,
				array(),
				$this->version
			);
			$mainDeps = array( 'selectize', 'fancy-select', 'list-tables', 'edit' );
			if ( 'woo-feed_page_webappick-feed-pro-vs-free' === $hook ) {
				$mainDeps = array_merge( $mainDeps, array( 'slick', 'slick-theme' ) );
			}
		}
		wp_register_style(
			$this->woo_feed,
			plugin_dir_url( __FILE__ ) . 'css/woo-feed-admin' . $ext,
			$mainDeps,
			$this->version,
			'all'
		);
		wp_register_style(
			$this->woo_feed . '-pro',
			plugin_dir_url( __FILE__ ) . 'css/woo-feed-admin-pro' . $ext,
			array( $this->woo_feed ),
			$this->version,
			'all'
		);
		wp_enqueue_style( $this->woo_feed );
		wp_enqueue_style( $this->woo_feed . '-pro' );
        wp_register_style(
            "codemirror",
            plugin_dir_url( __FILE__ )
            . 'css/codemirror.css', //. $ext,
            [],
            $this->version,
            'all'
        );

        if ( isset( $_GET['page'] ) &&  'webappick-new-feed' === $_GET['page']  ) {
            wp_enqueue_style( 'codemirror' );
        }
		// Enqueue for react UI
		wp_register_style(
			'woo-feed-react',
			WOO_FEED_FREE_ADMIN_URL . 'css/V5CSS/index.css',
			array( $this->woo_feed ),
			$this->version,
			'all'
		);
		if (
			isset( $_GET['page'] ) &&
			( 'webappick-manage-wp-options' === $_GET['page'] || 'webappick-manage-settings' === $_GET['page'] ||
			  'webappick-manage-dynamic-attribute' === $_GET['page'] ||
			  'webappick-manage-attributes-mapping' === $_GET['page'] ||
			  'webappick-manage-feeds' === $_GET['page'] ||
			  'webappick-manage-category-mapping' === $_GET['page'] ||
			  'webappick-wp-status' === $_GET['page'] ||
			  'webappick-feed-docs' === $_GET['page'] ||
			  'webappick-new-feed' === $_GET['page'] )
		) {
			wp_enqueue_style( 'woo-feed-react' );
		}

		wp_register_style( 'woo-feed-react-selectize', WOO_FEED_FREE_ADMIN_URL . 'css/react-selectize.css', array( $this->woo_feed ), $this->version, 'all' );
		if ( isset( $_GET['page'] ) && ( 'webappick-manage-attributes-mapping' === $_GET['page'] || 'webappick-manage-category-mapping' === $_GET['page'] || 'webappick-new-feed' === $_GET['page'] ) ) {
			wp_enqueue_style( 'woo-feed-react-selectize' );
		}


	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @param string $hook
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts( $hook ) {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Feed_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The woo_feed_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		//dequeue unnecessary scripts from loading
		$js_dequeue_handles = woo_feed_get_js_dequeue_handles_list();

		if ( isset( $_GET['page'] ) ) {
			$page                  = apply_filters(
				'CTXFEED_filter_securing_input',
				'GET',
				@$_GET['page'],
				'text'
			);
			$woo_feed_plugin_pages = woo_feed_get_plugin_pages_slugs();

			if (
				isset( $js_dequeue_handles ) &&
				! empty( $js_dequeue_handles ) &&
				in_array( $page, $woo_feed_plugin_pages, true )
			) {
				foreach ( $js_dequeue_handles as $script_handle ) {
					wp_dequeue_script( $script_handle );
				}
			}
		}

		$ext = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '.js' : '.min.js';
		if (
			false !== strpos( $hook, 'webappick' ) &&
			false !== strpos( $hook, 'feed' )
		) {
			wp_enqueue_script( 'thickbox' );
			if ( is_network_admin() ) {
				add_action( 'admin_head', '_thickbox_path_admin_subfolder' );
			}
			wp_register_script(
				'jquery-selectize',
				plugin_dir_url( __FILE__ ) . 'js/selectize.min.js',
				array( 'jquery' ),
				$this->version,
				false
			);
			wp_register_script(
				'fancy-select',
				plugin_dir_url( __FILE__ ) . 'js/fancy-select' . $ext,
				array( 'jquery' ),
				$this->version,
				false
			);
			wp_register_script(
				'jquery-validate',
				plugin_dir_url( __FILE__ ) . 'js/jquery.validate.min.js',
				array( 'jquery' ),
				$this->version,
				false
			);
			wp_register_script(
				'jquery-validate-additional-methods',
				plugin_dir_url( __FILE__ ) . 'js/additional-methods.min.js',
				array( 'jquery', 'jquery-validate' ),
				$this->version,
				false
			);
			wp_register_script(
				'jquery-sortable',
				plugin_dir_url( __FILE__ ) . 'js/jquery-sortable' . $ext,
				array( 'jquery' ),
				$this->version,
				false
			);

			$feedScriptDependency = array(
				'jquery',
				'clipboard',
				'jquery-selectize',
				'jquery-sortable',
				'jquery-validate',
				'jquery-validate-additional-methods',
				'wp-util',
				'utils',
				'wp-lists',
				'postbox',
				'tags-box',
				// 'underscore', 'word-count', 'jquery-ui-autocomplete',
				'jquery-touch-punch',
				'fancy-select',
			);

			wp_register_script(
				$this->woo_feed,
				plugin_dir_url( __FILE__ ) . 'js/woo-feed-admin' . $ext,
				$feedScriptDependency,
				$this->version,
				false
			);

			//get feed options with which feed is previously generated
			$feed_rules = '';

			if ( isset( $_GET['feed'] ) ) {
				$feed         = apply_filters(
					'CTXFEED_filter_securing_input',
					'GET',
					@$_GET['feed'],
					'text'
				);
				$filename     = str_replace( 'wf_feed_', '', wp_unslash( $feed ) );
				$feed_options = maybe_unserialize(
					get_option( 'wf_feed_' . $filename )
				);
				if ( isset( $feed_options['feedrules'] ) ) {
					$feed_rules = $feed_options['feedrules'];
				}
			}

			$page   = isset( $_GET['page'] )
				? apply_filters(
					'CTXFEED_filter_securing_input',
					'GET',
					@$_GET['page'],
					'text'
				)
				: false;
			$action =
				isset( $_GET['action'] ) &&
				apply_filters(
					'CTXFEED_filter_securing_input',
					'GET',
					@$_GET['action'],
					'text'
				);
			global $plugin_page;
			$js_opts = array(
				'wpf_ajax_url'            => admin_url( 'admin-ajax.php' ),
				'json_url'                => esc_url_raw( rest_url() ),
				'rest_nonce'              => wp_create_nonce( 'wp_rest' ),
				'admin_url'               => admin_url( 'admin.php' ),
				'api_namespace'           => WOO_FEED_API_NAMESPACE,
				'free_url'                => WOO_FEED_FREE_ADMIN_URL,
				'v5_images'               => WOO_FEED_FREE_ADMIN_URL . 'images/v5_images/',
				'api_version'             => WOO_FEED_API_VERSION,
				'v5_url'                  => WOO_FEED_V5_URL,
				'is_free'                 => WOO_FEED_IS_FREE,
				'plugin_version'          => WOO_FEED_FREE_VERSION,
				'constants'               => [
					'categoryMapping'   => 'wf_cmapping_',
					'dynamicAttributes' => 'wf_dattribute_',
					'wpOptions'         => 'wf_option_',
					'attributesMapping' => 'wp_attr_mapping_',
				],
				'manage_feeds_nonces'     => [
					'edit-feed'            => wp_create_nonce( 'wf_edit_feed' ),
					'delete-feed'          => wp_create_nonce( 'wf_delete_feed' ),
					'duplicate-feed'       => wp_create_nonce( 'wf_duplicate_feed' ),
					'wf_export_feed'       => wp_create_nonce( 'wpf-export' ),
					'wf_download_feed'     => wp_create_nonce( 'wpf-download-feed' ),
					'wf_download_feed_log' => wp_create_nonce( 'wpf-log-download' ),
				],
				'manage_feed_action_urls' => [
					'edit-feed'            => admin_url( '?page=' . $plugin_page . '&action=edit-feed&feed=' ),
					'duplicate-feed'       => admin_url( '?page=' . $plugin_page . '&action=duplicate-feed&feed=' ),
					'delete-feed'          => admin_url( '?page=' . $plugin_page . '&action=delete-feed&feed=' ),
					'wf_export_feed'       => admin_url( 'admin-post.php?action=wf_export_feed&feed=' ),
					'wf_download_feed'     => admin_url( 'admin-post.php?action=wf_download_feed&feed=' ),
					'wf_download_feed_log' => admin_url( 'admin-post.php?action=wf_download_feed_log&feed=' ),
				],
				'site_url'                => get_option( 'siteurl' ),
				'wpf_debug'               => woo_feed_is_debugging_enabled(),
				'feed_rules'              => $feed_rules,
				'pages'                   => array(
					'list' => array(
						'feed' => esc_url(
							admin_url( 'admin.php?page=webappick-manage-feeds' )
						),
					),
				),
				'nonce'                   => wp_create_nonce( 'wpf_feed_nonce' ),
				'is_feed_edit'            => $action && 'webappick-manage-feeds' === $page && 'edit-feed' === $page,
				$action && 'webappick-manage-feeds' === $page && 'edit-feed' === $page,

				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				'is_feed_add'             => 'webappick-new-feed' === $page,
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended

				'na'          => esc_html__( 'N/A', 'woo-feed' ),
				'regenerate'  => esc_html__( 'Generating...', 'woo-feed' ),
				'learn_more'  => esc_html__( 'Learn More..', 'woo-feed' ),
				'form'        => array(
					'select_category'     => esc_attr__(
						'Select A Category',
						'woo-feed'
					),
					'loading_tmpl'        => esc_html__(
						'Loading Template...',
						'woo-feed'
					),
					'generate'            => esc_html__(
						'Delivering Configuration...',
						'woo-feed'
					),
					'save'                => esc_html__( 'Saving Configuration...', 'woo-feed' ),
					'sftp_checking'       => esc_html__(
						'Wait! Checking Extensions ...',
						'woo-feed'
					),
					'sftp_warning'        => esc_html__(
						'Warning! Enable PHP ssh2 extension to use SFTP. Contact your server administrator.',
						'woo-feed'
					),
					'sftp_available'      => esc_html__(
						'SFTP Available!',
						'woo-feed'
					),
					'one_item_required'   => esc_html__(
						'Please add one or more items to continue.',
						'woo-feed'
					),
					'google_category'     => woo_feed_merchant_require_google_category(),
					'del_confirm'         => esc_html__(
						'Are you sure you want to delete this item?',
						'woo-feed'
					),
					'del_confirm_multi'   => esc_html__(
						'Are you sure you want to delete selected items?',
						'woo-feed'
					),
					'item_wrapper_hidden' => woo_feed_get_item_wrapper_hidden_merchant(),
				),
				'generator'   => array(
					'limit'      => woo_feed_get_options( 'per_batch' ),
					'feed'       => '',
					'regenerate' => false,
				),
				'ajax'        => array(
					'url'   => admin_url( 'admin-ajax.php' ),
					'nonce' => wp_create_nonce( 'wpf_feed_nonce' ),
					'error' => esc_html__( 'There was an error processing ajax request.', 'woo-feed' ),
				),
				'woocommerce' => array(
					'currency'  => get_option( 'woocommerce_currency' ),
					'weight'    => get_option( 'woocommerce_weight_unit' ),
					'dimension' => get_option( 'woocommerce_dimension_unit' ),
					'country'	=> WC()->countries->get_base_country(),
				),
			);

			$feed_created    = isset( $_GET['feed_created'] ) ? apply_filters(
				'CTXFEED_filter_securing_input',
				'GET',
				@$_GET['feed_created'],
				'text'
			) : "";
			$feed_updated    = isset( $_GET['feed_updated'] ) ? apply_filters(
				'CTXFEED_filter_securing_input',
				'GET',
				@$_GET['feed_updated'],
				'text'
			) : "";
			$feed_imported   = isset( $_GET['feed_imported'] ) ? apply_filters(
				'CTXFEED_filter_securing_input',
				'GET',
				@$_GET['feed_imported'],
				'text'
			) : "";
			$feed_regenerate = isset( $_GET['feed_regenerate'] ) ? apply_filters(
				'CTXFEED_filter_securing_input',
				'GET',
				@$_GET['feed_regenerate'],
				'text'
			) : "";
			$feed_name       = isset( $_GET['feed_name'] ) ? apply_filters(
				'CTXFEED_filter_securing_input',
				'GET',
				@$_GET['feed_name'],
				'file_name'
			) : "";

			// phpcs:ignore WordPress.Security.NonceVerification.Recommended
			if (
				( $feed_created || $feed_updated || $feed_imported ) &&
				$feed_regenerate &&
				1 === (int) $feed_regenerate
			) {
				// phpcs:ignore WordPress.Security.NonceVerification.Recommended
				$fileName = ! empty( $feed_name ) ? wp_unslash( $feed_name ) : ''; // trigger feed regenerate...
				if ( ! empty( $fileName ) ) {
					// filename must be wf_config+XXX format for js to work.
					$js_opts['generator']['feed']       =
						'wf_config' .
						woo_feed_extract_feed_option_name( $fileName );
					$js_opts['generator']['regenerate'] = true;
				}
			}
			wp_localize_script( $this->woo_feed, 'wpf_ajax_obj', $js_opts );
			if (
				isset( $_GET['page'] ) &&
				( 'webappick-manage-wp-options' !== $_GET['page'] || 'webappick-manage-settings' !== $_GET['page'] ||
				  'webappick-manage-dynamic-attribute' !== $_GET['page'] ||
				  'webappick-manage-attributes-mapping' !== $_GET['page'] ||
				  'webappick-manage-category-mapping' !== $_GET['page'] ||
				  'webappick-wp-status' === $_GET['page'] ||
				  'webappick-feed-docs' === $_GET['page'] ||
				  'webappick-new-feed' === $_GET['page'] )
			) {
				wp_enqueue_script( $this->woo_feed );
			}
			if ( 'woo-feed_page_webappick-feed-pro-vs-free' === $hook ) {
				wp_register_script(
					'jquery-slick',
					plugin_dir_url( __FILE__ ) . 'js/slick' . $ext,
					array( 'jquery' ),
					$this->version,
					false
				);
				wp_register_script(
					$this->woo_feed . '-pro',
					plugin_dir_url( __FILE__ ) . 'js/woo-feed-admin-pro' . $ext,
					array( $this->woo_feed, 'jquery-slick' ),
					$this->version,
					false
				);
				wp_enqueue_script( $this->woo_feed . '-pro' );
			}

            wp_register_script(
                'codemirror',
                plugin_dir_url( __FILE__ )
                . '/js/codemirror' . $ext,
                $feedScriptDependency+ array( $this->woo_feed ),
                $this->version,
                true
            );

            wp_register_script(
                'codemirror-mode-xml',
                plugin_dir_url( __FILE__ )
                . '/js/codemirror-customs-mode-xml' . $ext,
                array( 'codemirror' ),
                $this->version,
                true
            );

            if ( isset( $_GET['page'] ) &&  'webappick-new-feed' === $_GET['page']  ) {
                wp_enqueue_script( 'codemirror' );
                wp_enqueue_script( 'codemirror-mode-xml' );
            }

			// Enqueue for react U
			wp_register_script(
				'woo-feed-react',
				WOO_FEED_FREE_ADMIN_URL . 'js/V5JS/index.js',
				array(),
				$this->version,
				true
			);

			if (
				isset( $_GET['page'] ) &&
				( 'webappick-manage-wp-options' === $_GET['page'] || 'webappick-manage-settings' === $_GET['page'] ||
				  'webappick-manage-dynamic-attribute' === $_GET['page'] ||
				  'webappick-manage-attributes-mapping' === $_GET['page'] ||
				  'webappick-manage-feeds' === $_GET['page'] ||
				  'webappick-manage-category-mapping' === $_GET['page'] ||
				  'webappick-wp-status' === $_GET['page'] ||
				  'webappick-feed-docs' === $_GET['page'] ||
				  'webappick-new-feed' === $_GET['page'] )
			) {
				wp_enqueue_script( 'woo-feed-react' );
				wp_localize_script( 'woo-feed-react', 'WFV5', $js_opts );
			}


		}
	}

	/**
	 * Add Go to Pro and Documentation link
	 *
	 * @param array $links
	 *
	 * @return array
	 */
	public function woo_feed_plugin_action_links( $links ) {
		$links[] =
			'<a style="color: #389e38; font-weight: bold;" href="https://webappick.com/plugin/woocommerce-product-feed-pro/?utm_source=freePlugin&utm_medium=go_premium&utm_campaign=free_to_pro&utm_term=wooFeed" target="_blank">' .
			__( 'Get Pro', 'woo-feed' ) .
			'</a>';
		/** @noinspection HtmlUnknownTarget */
		$links[] = sprintf(
			'<a style="color:#ce7304; font-weight: bold;" href="%s">%s</a>',
			esc_url( admin_url( 'admin.php?page=webappick-feed-docs' ) ),
			__( 'Docs', 'woo-feed' )
		);
		/** @noinspection HtmlUnknownTarget */
		$links[] = sprintf(
			'<a href="%s">%s</a>',
			esc_url( admin_url( 'admin.php?page=webappick-manage-settings' ) ),
			__( 'Settings', 'woo-feed' )
		);

		return $links;
	}

	/**
	 * Register the Plugin's Admin Pages for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function load_admin_pages() {
		/**
		 * This function is provided for making admin pages into admin area.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in WOO_FEED_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The WOO_FEED_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		if ( function_exists( 'add_options_page' ) ) {
			add_menu_page(
				__( 'CTX Feed', 'woo-feed' ),
				__( 'CTX Feed', 'woo-feed' ),
				'manage_woocommerce',
				'webappick-manage-feeds',
				'woo_feed_manage_feed',
				'dashicons-rss'
			);
			add_submenu_page(
				'webappick-manage-feeds',
				__( 'Manage Feeds', 'woo-feed' ),
				__( 'Manage Feeds', 'woo-feed' ),
				'manage_woocommerce',
				'webappick-manage-feeds',
				array( $this, 'woo_feed_manage_feed_react' )
			);
			add_submenu_page(
				'webappick-manage-feeds',
				__( 'Make Feed', 'woo-feed' ),
				__( 'Make Feed', 'woo-feed' ),
				'manage_woocommerce',
				'webappick-new-feed',
				array( $this, 'woo_feed_manage_feed_react' )
			);
			add_submenu_page(
				'webappick-manage-feeds',
				__( 'Attribute Mapping', 'woo-feed' ),
				__( 'Attribute Mapping', 'woo-feed' ),
				'manage_woocommerce',
				'webappick-manage-attributes-mapping',
				array( $this, 'woo_feed_manage_feed_react' )
			);

			add_submenu_page(
				'webappick-manage-feeds',
				__( 'Dynamic Attributes', 'woo-feed' ),
				__( 'Dynamic Attributes', 'woo-feed' ),
				'manage_woocommerce',
				'webappick-manage-dynamic-attribute',
				array( $this, 'woo_feed_manage_feed_react' )
			);
			add_submenu_page(
				'webappick-manage-feeds',
				__( 'Category Mapping', 'woo-feed' ),
				__( 'Category Mapping', 'woo-feed' ),
				'manage_woocommerce',
				'webappick-manage-category-mapping',
				array( $this, 'woo_feed_manage_feed_react' )
			);

			add_submenu_page(
				'webappick-manage-feeds',
				__( 'WP Options', 'woo-feed' ),
				__( 'WP Options', 'woo-feed' ),
				'manage_woocommerce',
				'webappick-manage-wp-options',
				array( $this, 'woo_feed_manage_feed_react' )
			);
			add_submenu_page(
				'webappick-manage-feeds',
				__( 'Settings', 'woo-feed' ),
				__( 'Settings', 'woo-feed' ),
				'manage_woocommerce',
				'webappick-manage-settings',
				array( $this, 'woo_feed_manage_feed_react' )
			);
//			add_submenu_page(
//				'webappick-manage-feeds',
//				__( 'Status', 'woo-feed' ),
//				__( 'Status', 'woo-feed' ),
//				'manage_woocommerce',
//				'webappick-wp-status',
//				'woo_feed_system_status'
//			);
			add_submenu_page(
				'webappick-manage-feeds',
				__( 'Status', 'woo-feed' ),
				__( 'Status', 'woo-feed' ),
				'manage_woocommerce',
				'webappick-wp-status',
				array( $this, 'woo_feed_manage_feed_react' )
			);
//			add_submenu_page(
//				'webappick-manage-feeds',
//				__( 'Documentation', 'woo-feed' ),
//				'<span class="woo-feed-docs">' .
//				__( 'Docs', 'woo-feed' ) .
//				'</span>',
//				'manage_woocommerce',
//				'webappick-feed-docs',
//				array( WooFeedDocs::getInstance(), 'woo_feed_docs' )
//			);
			add_submenu_page(
				'webappick-manage-feeds',
				__( 'Documentation', 'woo-feed' ),
				'<span class="woo-feed-docs">' .
				__( 'Docs', 'woo-feed' ) .
				'</span>',
				'manage_woocommerce',
				'webappick-feed-docs',
				array( $this, 'woo_feed_manage_feed_react' )
			);
		}
	}

	public function woo_feed_manage_feed_react() {

		if ( $_GET['page'] === 'webappick-manage-feeds' ) {
			?>
            <div class="wrap wapk-admin">
                <div class="wapk-section">
					<?php
					WPFFWMessage()->displayMessages();
					woo_feed_progress_bar();
					?>
                </div>
            </div>
            <div id="wpf_importer" style="display: none;">
                <form action="<?php echo esc_url( admin_url( 'admin-post.php?action=wpf_import' ) ); ?>" method="post"
                      enctype="multipart/form-data">
					<?php wp_nonce_field( 'wpf_import' ); ?>
                    <!-- <input type="file" name="wpf_import_file" id="wpf_import_file" accept=".wpf" onchange="this.form.submit()">-->
                    <table class="fixed widefat">
                        <tr>
                            <td colspan="2">
                                <label for="wpf_import_file"
                                       class="screen-reader-text"><?php esc_html_e( 'Import Feed File', 'woo-feed' ); ?></label>
                                <input type="file" name="wpf_import_file" id="wpf_import_file" accept=".wpf" required>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <label for="wpf_import_feed_name"
                                       class="screen-reader-text"><?php esc_html_e( 'Feed File Name', 'woo-feed' ); ?></label>
                                <input type="text" class="regular-text" name="wpf_import_feed_name"
                                       id="wpf_import_feed_name"
                                       placeholder="<?php esc_attr_e( 'Feed File Name', 'woo-feed' ); ?>" required>
                            </td>
                        </tr>
                        <tr class="text-center">
                            <td colspan="2">
                                <input type="submit" class="button button-primary" id="wpf_import_submit"
                                       value="<?php esc_attr_e( 'Import Now', 'woo-feed' ); ?>">
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
			<?php
		}

		echo '<div class="wrap"><div id="woo-feed"></div></div>';
	}

	/**
	 * Redirect user to with new menu slug (if user browser any bookmarked url)
	 *
	 * @return void
	 * @since 3.1.7
	 */
	public function handle_old_menu_slugs() {
		global $pagenow;
		// redirect user to new old slug => new slug
		$redirect_to = array(
			'webappick-product-feed-for-woocommerce/admin/class-woo-feed-admin.php' => 'webappick-new-feed',
			'woo_feed_manage_feed'                                                  => 'webappick-manage-feeds',
			'woo_feed_config_feed'                                                  => 'webappick-feed-settings',
			'woo_feed_pro_vs_free'                                                  => 'webappick-feed-pro-vs-free',
			'woo_feed_wp_options'                                                   => 'webappick-wp-options',
		);
		if (
			'admin.php' === $pagenow &&
			isset( $plugin_page ) &&
			! empty( $plugin_page )
		) {
			foreach ( $redirect_to as $from => $to ) {
				if ( $plugin_page !== $from ) {
					continue;
				}
				wp_safe_redirect( admin_url( 'admin.php?page=' . $to ), 301 );
				die();
			}
		}
	}
}
