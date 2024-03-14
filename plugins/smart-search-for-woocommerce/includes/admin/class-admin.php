<?php
/**
 * Searchanise Admin settings
 *
 * @package Searchanise/Admin
 */

namespace Searchanise\SmartWoocommerceSearch;

defined( 'ABSPATH' ) || exit;

/**
 * Administrator class
 */
class Admin {

	/**
	 * Lang code
	 *
	 * @var string
	 */
	private $lang_code = '';

	/**
	 * Admin constructor
	 *
	 * @param string $lang_code Lang code.
	 */
	public function __construct( $lang_code = null ) {
		$this->lang_code = $lang_code ? $lang_code : Api::get_instance()->get_locale();

		add_action( 'admin_init', array( $this, 'init' ) );
		add_action( 'wp_loaded', array( $this, 'register' ) );

		add_action(
			'before_woocommerce_init',
			function () {
				if ( class_exists( '\Automattic\WooCommerce\Utilities\FeaturesUtil' ) ) {
					\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', 'woocommerce-searchanise.php', true );
				}
			}
		);
	}

	/**
	 * Admin init. Performs basic check
	 */
	public function init() {
		if ( ! is_admin() ) {
			return;
		}

		if ( isset( $_GET['page'] ) ) {
			$current_page = sanitize_text_field( wp_unslash( $_GET['page'] ) );

			if ( 'searchanise' === $current_page && is_plugin_active( 'woocommerce/woocommerce.php' ) && ! Api::get_instance()->get_wc_status() ) {
				$woo_subscriptions_url = get_admin_url() . 'admin.php?page=wc-addons&section=helper';

				add_action(
					'admin_notices',
					function () use ( $woo_subscriptions_url ) {
						echo '<div class="notice-error notice"><p>'
							. '<b>' . esc_html( SE_PRODUCT_NAME ) . '</b></p><p>'
							. wp_kses_data(
								sprintf(
								/* translators: %s is a placeholder for the subscriptions URL */
									__( 'The Searchanise app is not working now because your subscription is not active. Please <a href=" %s">upgrade your subscription</a> to continue enjoying Searchanise in full.', 'woocommerce-searchanise' ),
									$woo_subscriptions_url
								)
							) . '</p></div>';
					}
				);
			}
		}

		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) && ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) ) {
			add_action(
				'admin_notices',
				function () {
					echo '<div class="notice-error notice"><p>'
						. '<b>' . esc_html( SE_PRODUCT_NAME ) . '</b></p><p>'
						. wp_kses_data( __( '<a href="https://wordpress.org/plugins/woocommerce">WooCommerce</a> plugin should be enabled to work correctly.', 'woocommerce-searchanise' ) ) . '</p></div>';
				}
			);

			if ( current_user_can( 'activate_plugins' ) ) {
				deactivate_plugins( SE_ABSPATH . DIRECTORY_SEPARATOR . 'woocommerce-searchanise.php' );

				add_action(
					'admin_notices',
					function () {
						echo '<div class="notice-error notice"><p>'
							. '<b>' . esc_html( SE_PRODUCT_NAME ) . '</b></p><p>'
							. wp_kses_data( __( 'Plugin was deactivated.', 'woocommerce-searchanise' ) ) . '</p></div>';
					}
				);

				if ( isset( $_GET['activate'] ) ) {
					unset( $_GET['activate'] );
				}
			}
		}
	}

	/**
	 * Register backend scripts
	 */
	public function register() {
		if ( ! is_admin() ) {
			return;
		}

		// Network activation, try to install pluging.
		if ( is_multisite() && Api::get_instance()->get_module_status() != 'Y' ) {
			// Network activation, try to install pluging.
			Cron::unregister();

			if ( Installer::install() ) {
				// Register searchanise info page.
				add_rewrite_rule( '^searchanise/info', 'index.php?is_searchanise_page=1&post_type=page', 'top' );
				flush_rewrite_rules();
			} else {
				add_action(
					'admin_notices',
					function () {
						/* translators: %s: support email */
						echo '<div class="notice-warning notice"><p>'
							. '<b>' . esc_html( SE_PRODUCT_NAME ) . '</b></p><p>' . esc_html(
								sprintf(
									'Unable to register plugin. Please, contact Searchanise <a href="mailto:%s">%s</a> technical support',
									SE_SUPPORT_EMAIL,
									SE_SUPPORT_EMAIL
								)
							) . '</p></div>';
					}
				);
			}
		}

		if ( Api::get_instance()->get_module_status() != 'Y' ) {
			return;
		}
		if ( ! Upgrade::is_updated() ) {
			if ( Upgrade::process_upgrade() ) {
				$text_notification = sprintf(
					/* translators: %s: admin panel */
					__( 'Plugin was successfully updated. Catalog indexation in process. <a href="%s">Admin Panel</a>.', 'woocommerce-searchanise' ),
					Api::get_instance()->get_admin_url()
				);

				if ( SE_PLUGIN_VERSION == '1.0.12' ) {
					Api::get_instance()->add_admin_notitice(
						sprintf(
							/* translators: %s: admin panel */
							__( 'In the new version 1.0.12 of the plugin, the settings moved from <b>Settings → Searchanise</b> to the <b><a href="%1$s">WooCommerce → Settings → Searchanise</a></b> and <br />admin panel moved from <b>Products → Searchanise</b> to <b><a href="%2$s"> Woocommerce → Searchanise</a></b>.', 'woocommerce-searchanise' ),
							get_admin_url( null, 'admin.php?page=wc-settings&tab=searchanise_settings' ),
							admin_url( 'admin.php?page=searchanise' )
						),
						'info'
					);
				}
			}
		} elseif ( Api::get_instance()->check_auto_install() ) {
			$text_notification = sprintf(
				/* translators: %s: admin panel */
				__( 'Plugin was successfully installed. Catalog indexation in process. <a href="%s">Admin Panel</a>.', 'woocommerce-searchanise' ),
				Api::get_instance()->get_admin_url()
			);
		} elseif ( Api::get_instance()->get_is_need_reindexation() ) {
			// Full reindexation, usually used after addon activating.
			$text_notification = sprintf(
				/* translators: %s: admin panel */
				__( 'Plugin was successfully activated. Catalog indexation in process. <a href="%s">Admin Panel</a>.', 'woocommerce-searchanise' ),
				Api::get_instance()->get_admin_url()
			);
			Api::get_instance()->set_is_need_reindexation( false );
		}

		if ( ! empty( $text_notification ) ) {
			if ( Api::get_instance()->signup( null, false ) == true ) {
				Api::get_instance()->queue_import( null, false );
				Api::get_instance()->add_admin_notitice( $text_notification, 'success' );

			} else {
				Api::get_instance()->add_admin_notitice(
					sprintf(
						/* translators: %s: support email */
						__( 'Something is wrong in plugin registration. Please contact Searchanise <a href="mailto:%1$s">%2$s</a> technical support', 'woocommerce-searchanise' ),
						SE_SUPPORT_EMAIL,
						SE_SUPPORT_EMAIL
					),
					'error'
				);
			}
		} else {
			Api::get_instance()->show_notification_async_completed();
		}

		$this->searchanise_settings();

		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_filter( 'admin_footer_text', array( $this, 'admin_footer_text' ), 999999 );
		add_filter( 'plugin_action_links_' . SE_PLUGIN_BASENAME, array( $this, 'admin_settings_link' ) );
		add_action( 'admin_notices', array( $this, 'display_admin_notices' ) );
	}

	/**
	 * Adds plugin links.
	 *
	 * @param array $links Links.
	 *
	 * @return array $links with additional links
	 */
	public function admin_settings_link( $links ) {
		$links[] = '<a href="' . get_admin_url( null, '/admin.php?page=searchanise' ) . '">' . __( 'Admin Panel', 'woocommerce-searchanise' ) . '</a>';
		$links[] = '<a href="' . admin_url( 'admin.php?page=wc-settings&tab=searchanise_settings' ) . '">' . __( 'Settings', 'woocommerce-searchanise' ) . '</a>';

		return $links;
	}


	/**
	 * Add the Searchanise Admin Panel menu items.
	 */
	public function admin_menu() {
		$admin_page = add_submenu_page(
			'woocommerce',
			Api::get_instance()->get_woocommerce_plugin_version() ? SE_PRODUCT_NAME : __( 'Searchanise', 'woocommerce-searchanise' ),
			Api::get_instance()->get_woocommerce_plugin_version() ? SE_PRODUCT_NAME : __( 'Searchanise', 'woocommerce-searchanise' ),
			'manage_product_terms',
			'searchanise',
			array( $this, 'searchanise_manage' )
		);

		add_action( 'load-' . $admin_page, array( $this, 'load_dashboard' ) );
	}

	/**
	 * Display stored admin notice
	 */
	public function display_admin_notices() {
		$admin_notices = Api::get_instance()->get_admin_notices();
		$allowed_tags = array(
			'div'    => array(
				'class' => array(),
			),
			'strong' => array(),
			'em'     => array(),
			'p'      => array(),
			'b'      => array(),
			'i'      => array(),
			'a'      => array(
				'href' => array(),
			),
		);

		if ( ! empty( $admin_notices ) ) {
			foreach ( $admin_notices as $notice ) {
				$class = ! empty( $notice['type'] ) ? 'notice-' . $notice['type'] : '';
				$message = $notice['message'];
				echo wp_kses( "<div class=\"notice {$class} is-dismissible\"><p><b>" . SE_PRODUCT_NAME . "</b></p><p>{$message}</p></div>", $allowed_tags );
			}
		}

		return $this;
	}

	/**
	 * Adds rating request to footer
	 *
	 * @param string $footer_text Original footer text.
	 *
	 * @return string modified footer text
	 */
	public function admin_footer_text( $footer_text ) {
		$current_screen = get_current_screen();

		if ( isset( $current_screen->id ) && ! Api::get_instance()->get_woocommerce_plugin_version() && in_array( $current_screen->id, array( 'woocommerce_page_searchanise', 'product_page_searchanise' ) ) ) {
			if ( ! Api::get_instance()->get_is_rated() ) {
				$footer_text = sprintf(
					/* translators: %s: review link */
					__( 'If you like %1$s please leave us a %2$s rating. A huge thanks in advance!', 'woocommerce-searchanise' ),
					sprintf( '<strong>%s</strong>', SE_PRODUCT_NAME ),
					'<a href="https://wordpress.org/support/plugin/smart-search-for-woocommerce/reviews?rate=5#new-post" target="_blank" class="se-rating-link" data-rated="' . esc_attr__( 'Thanks :)', 'woocommerce-searchanise' ) . '">&#9733;&#9733;&#9733;&#9733;&#9733;</a>'
				);
				wc_enqueue_js(
					"jQuery('a.se-rating-link').click( function() {
						jQuery.get('" . admin_url( 'admin-ajax.php' ) . "', {action: 'searchanise_rated'});
						jQuery(this).parent().text(jQuery(this).data('rated'));
					});"
				);

			} else {
				$footer_text = sprintf(
					/* translators: %s: product name */
					__( 'Thank you for using <strong>%s</strong>.', 'woocommerce-searchanise' ),
					SE_PRODUCT_NAME
				);
			}
		}

		return $footer_text;
	}

	/**
	 * Load assets
	 */
	public function load_settings() {
		// Adds page to allow loads woocommerce scripts / css on them.
		add_filter(
			'woocommerce_screen_ids',
			function ( $screen_ids ) {
				return array_merge(
					$screen_ids,
					array(
						'settings_page_searchanise_settings',
					)
				);
			}
		);
		add_filter(
			'woocommerce_display_admin_footer_text',
			function ( $result ) {
				$current_screen = get_current_screen();

				if ( 'settings_page_searchanise_settings' == $current_screen->id ) {
					return false;
				}

				return $result;
			}
		);

		return $this;
	}

	/**
	 * Load Searchanise Admin Widget
	 */
	public function load_dashboard() {
		global $wp_version;

		Api::get_instance()->check_enviroments();

		$addon_options = Api::get_instance()->get_addon_options();
		$last_request = Api::get_instance()->get_last_request( $this->lang_code );
		$last_resync = Api::get_instance()->get_last_resync( $this->lang_code );
		$service_url = is_ssl() ? str_replace( 'http://', 'https://', SE_SERVICE_URL ) : SE_SERVICE_URL;

		$se_admin_widgets_file_path = SE_BASE_DIR . '/assets/js/se-admin-widgets.js';
		$se_options = array(
			'version'               => SE_PLUGIN_VERSION,
			'status'                => 'enabled',
			'platform'              => SE_PLATFORM,
			'platform_edition'      => ! empty( $addon_options['woocommerce'] ) ? $addon_options['woocommerce']['Version'] : '',
			'platform_version'      => $wp_version,
			'host'                  => $service_url,
			'private_key'           => Api::get_instance()->get_private_key( $this->lang_code ),
			'parent_private_key'    => Api::get_instance()->get_parent_private_key(),
			'connect_link'          => Api::get_instance()->get_admin_url( 'signup' ),
			're_sync_link'          => Api::get_instance()->get_admin_url( 'reindex' ),
			'last_request'          => Api::get_instance()->format_date( $last_request ),
			'last_resync'           => Api::get_instance()->format_date( $last_resync ),
			'lang_code'             => $this->lang_code,
			'name'                  => Api::get_instance()->get_store_name( $this->lang_code ),
			'symbol'                => get_woocommerce_currency_symbol(),
			'decimals'              => wc_get_price_decimals(),
			'decimals_separator'    => wc_get_price_decimal_separator(),
			'thousands_separator'   => wc_get_price_thousand_separator(),
			'api_key'               => Api::get_instance()->get_api_key( $this->lang_code ),
			'export_status'         => Api::get_instance()->get_export_status( $this->lang_code ),
			's_engines'             => array_values( Api::get_instance()->get_engines() ),
		);

		/**
		 * Gets admin widgets file path
		 *
		 * @since 1.0.0
		 *
		 * @param string $se_admin_widgets_file_path
		 */
		$se_admin_widgets_file_path = apply_filters( 'se_admin_widgets_file_path', $se_admin_widgets_file_path );

		/**
		 * Gets admin widgets options
		 *
		 * @since 1.0.0
		 *
		 * @param string $se_admin_widgets_file_path
		 */
		$se_options = apply_filters( 'se_load_admin_widgets', $se_options );

		wp_register_script( 'se_admin_widget', plugins_url( $se_admin_widgets_file_path ), array( 'jquery' ), SE_PLUGIN_VERSION, true );
		wp_localize_script( 'se_admin_widget', 'SeOptions', $se_options );
		wp_register_script( 'se_link', $service_url . '/js/init.js', array( 'se_admin_widget' ), SE_PLUGIN_VERSION, true );
		wp_enqueue_style( 'se_admin_css', plugins_url( SE_BASE_DIR . '/assets/css/se-admin.css' ), array(), SE_PLUGIN_VERSION, false );

		return $this;
	}

	/**
	 * Searchanise manage controller
	 */
	public function searchanise_manage() {
		if ( ! current_user_can( 'manage_product_terms' ) ) {
			wp_die( esc_html__( 'Access denied.', 'woocommerce-searchanise' ) );
		}

		$mode = isset( $_GET['mode'] ) ? htmlspecialchars( sanitize_key( $_GET['mode'] ), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML401 ) : '';

		if ( ! empty( $mode ) ) {
			$action = 'action_' . mb_strtolower( $mode );

			if ( method_exists( $this, $action ) ) {
				call_user_func_array( array( $this, $action ), array() );
				wp_redirect( Api::get_instance()->get_admin_url() );
			}
		}

		wp_enqueue_script( 'se_admin_widget' );
		wp_enqueue_script( 'se_link' );

		echo '<div class="wrap"><h1>'
			. esc_html( SE_PRODUCT_NAME )
			. '</h1><div class="snize" id="snize_container"></div></div>';

		return $this;
	}

	/**
	 * Signup controller action
	 */
	private function action_signup() {
		if ( Api::get_instance()->get_module_status() == 'Y' && Api::get_instance()->signup() ) {
			Api::get_instance()->queue_import();
		}

		return $this;
	}

	/**
	 * Reindex controller action
	 */
	private function action_reindex() {
		if ( Api::get_instance()->get_module_status() == 'Y' && Api::get_instance()->signup() ) {
			Api::get_instance()->queue_import();
		}

		return $this;
	}

	/**
	 * Returns settings list for reindex
	 *
	 * @param string $name Setting name.
	 *
	 * @return boolean
	 */
	public function need_setting_reindexation( $name ) {
		return in_array(
			$name,
			array(
				'se_use_direct_image_links',
				'se_import_block_posts',
				'se_excluded_tags',
				'se_excluded_pages',
				'se_excluded_categories',
				'se_custom_attribute',
				'se_custom_product_fields',
				'se_custom_taxonomies',
			)
		);
	}

	/**
	 * Settings controller
	 */
	public function searchanise_settings() {
		global $se_need_reindexation;

		$admin_setting = new Admin_Setting();
		$admin_setting->init();

		if ( isset( $_SERVER['REQUEST_METHOD'] ) && 'POST' == $_SERVER['REQUEST_METHOD'] ) {
			$post = filter_input_array( INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS );
			$se_settings = isset( $post['se_search_input_selector'] ) ? $post : array();

			if ( ! empty( $se_settings ) ) {
				$need_reindexation = false;

				foreach ( $post as $name => $val ) {
					if ( $this->need_setting_reindexation( $name ) ) {
						$old_value = Api::get_instance()->get_system_setting( $name );
						$need_reindexation |= $old_value != $val;
					}

					if ( in_array( $name, array( 'color_attribute', 'size_attribute' ) ) ) {
						$old_value = Api::get_instance()->get_system_setting( $name );

						if ( $old_value != $val ) {
							// Need attribute reindexation.
							Queue::get_instance()->add_action_update_attributes();
						}
					}

					if ( 'search_result_page' == $name ) {
						Installer::create_search_results_page( array( 'post_name' => $val ), true );
					}

					Api::get_instance()->set_system_setting( $name, $val );
				}

				$se_need_reindexation = $need_reindexation;
			}

			flush_rewrite_rules();
		}

		return $this;
	}

	/**
	 * Returns all pages for system settings
	 *
	 * @param string $lang_code Lanuage code.
	 *
	 * @return array
	 */
	public function get_all_pages( $lang_code ) {
		$pages = array();

		$posts = get_posts(
			array(
				'post_type'   => Async::get_post_types(),
				'numberposts' => -1,
			)
		);

		foreach ( $posts as $post ) {
			$pages[ $post->post_name ] = $post->post_title;
		}

		/**
		 * Returns all pages for system settings
		 *
		 * @since 1.0.0
		 *
		 * @param array $pages
		 * @param string $lang_code
		 */
		return (array) apply_filters( 'se_get_all_pages', $pages, $lang_code );
	}

	/**
	 * Returns all categories for system settings
	 *
	 * @param string $lang_code Lanuage code.
	 *
	 * @return array
	 */
	public function get_all_categories( $lang_code ) {
		$categories = array();

		$terms = get_terms( 'product_cat' );

		foreach ( $terms as $term ) {
			$categories[ $term->slug ] = $term->name;
		}

		/**
		 * Returns all categories for system settings
		 *
		 * @since 1.0.0
		 *
		 * @param array $categories
		 * @param string $lang_code
		 */
		return (array) apply_filters( 'se_get_all_categories', $categories, $lang_code );
	}
}
