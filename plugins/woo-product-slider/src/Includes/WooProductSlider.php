<?php
/**
 * The plugin main class.
 *
 * @link       https://shapedplugin.com/
 *
 * @package    woo-product-slider
 * @subpackage woo-product-slider/Includes
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WooProductSlider\Includes;

use ShapedPlugin\WooProductSlider\Includes\Import_Export;
use ShapedPlugin\WooProductSlider\Frontend\Frontend;
use ShapedPlugin\WooProductSlider\Admin\Admin;
use ShapedPlugin\WooProductSlider\Admin\Gutenberg_Block;
use ShapedPlugin\WooProductSlider\Admin\Elementor_Addons;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Plugin main class name.
 */
class WooProductSlider {
	/**
	 * Plugin version
	 *
	 * @var string
	 */
	public $version = SP_WPS_VERSION;

	/**
	 * Plugin short code.
	 *
	 * @var SP_WPS_ShortCodes $shortcode
	 */
	public $shortcode;

	/**
	 * Plugin router.
	 *
	 * @var SP_WPS_Router $router
	 */
	public $router;

	/**
	 * Instance var.
	 *
	 * @var null
	 * @since 2.0
	 */
	protected static $_instance = null;

	/**
	 * Plugin instance function.
	 *
	 * @return WooProductSlider
	 * @since 2.0
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new WooProductSlider();
		}
		return self::$_instance;
	}

	/**
	 * Class Constructor.
	 */
	public function __construct() {
		// Run Frontend class.
		new Frontend();
		// Run Admin class.
		new Admin();
		// Initialize the filter hooks.
		$this->init_filters();
		// Initialize the action hooks.
		$this->init_actions();
	}

	/**
	 * Initialize WordPress filter hooks
	 *
	 * @return void
	 */
	public function init_filters() {
		add_filter( 'plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 2 );
		add_filter( 'manage_sp_wps_shortcodes_posts_columns', array( $this, 'add_shortcode_column' ) );
		add_filter( 'plugin_row_meta', array( $this, 'after_woo_product_slider_row_meta' ), 10, 4 );
		add_filter( 'post_updated_messages', array( $this, 'sp_wps_update' ), 10, 1 );
	}

	/**
	 * Initialize WordPress action hooks
	 *
	 * @return void
	 */
	public function init_actions() {
		add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );
		add_action( 'manage_sp_wps_shortcodes_posts_custom_column', array( $this, 'add_shortcode_form' ), 10, 2 );
		add_action( 'activated_plugin', array( $this, 'redirect_help_page' ) );
		if ( ! is_plugin_active_for_network( 'woocommerce/woocommerce.php' ) && ! in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) ) {
			add_action( 'admin_notices', array( $this, 'error_admin_notice' ) );
		}
		add_action( 'admin_action_sp_wps_duplicate_shortcode', array( $this, 'sp_wps_duplicate_shortcode' ) );
		add_filter( 'post_row_actions', array( $this, 'sp_wps_duplicate_shortcode_link' ), 10, 2 );
		add_action( 'admin_notices', array( $this, 'woo_gallery_slider_admin_notice' ) );
		add_action( 'admin_notices', array( $this, 'wqv_install_admin_notice' ) );
		add_action( 'wp_ajax_dismiss_wqv_notice', array( $this, 'dismiss_wqv_notice' ) );
		add_action( 'wp_ajax_dismiss_woo_gallery_slider_notice', array( $this, 'dismiss_woo_gallery_slider_notice' ) );

		// Export Import Ajax call.
		$import_export = new Import_Export( SP_WPS_NAME, SP_WPS_VERSION );

		add_action( 'wp_ajax_wpsp_export_shortcodes', array( $import_export, 'export_shortcodes' ) );
		add_action( 'wp_ajax_wpsp_import_shortcodes', array( $import_export, 'import_shortcodes' ) );

		// Gutenberg Block.
		if ( version_compare( $GLOBALS['wp_version'], '5.3', '>=' ) ) {
			new Gutenberg_Block();
		}

		// Elementor shortcode addons.
		if ( ( is_plugin_active( 'elementor/elementor.php' ) || is_plugin_active_for_network( 'elementor/elementor.php' ) ) ) {
			new Elementor_Addons();
		}
	}


	/**
	 * Load TextDomain for plugin.
	 *
	 * @since 2.0
	 */
	public function load_text_domain() {
		load_textdomain( 'woo-product-slider', WP_LANG_DIR . '/woo-product-slider/languages/woo-product-slider-' . apply_filters( 'plugin_locale', get_locale(), 'woo-product-slider' ) . '.mo' );
		load_plugin_textdomain( 'woo-product-slider', false, dirname( SP_WPS_BASENAME ) . '/languages/' );
	}

	/**
	 * Add plugin action menu
	 *
	 * @param array  $links menu action links.
	 * @param string $file file basename.
	 *
	 * @return array
	 */
	public function add_plugin_action_links( $links, $file ) {

		if ( SP_WPS_BASENAME === $file ) {
			$new_links = sprintf( '<a href="%s">%s</a>', admin_url( 'post-new.php?post_type=sp_wps_shortcodes' ), __( 'Add New', 'woo-product-slider' ) );

			array_unshift( $links, $new_links );

			$links['go_pro'] = sprintf( '<a target="_blank" href="%1$s" style="color: #35b747; font-weight: 700;">Go Pro!</a>', 'https://wooproductslider.io/pricing/?ref=1' );
		}

		return $links;
	}

	/**
	 * Add plugin row meta link.
	 *
	 * @since 2.0
	 *
	 * @param array  $plugin_meta .
	 * @param string $file .
	 *
	 * @return array
	 */
	public function after_woo_product_slider_row_meta( $plugin_meta, $file ) {
		if ( SP_WPS_BASENAME === $file ) {
			$plugin_meta[] = '<a href="https://wooproductslider.io/lite-version-demo/" target="_blank">' . __( 'Live Demo', 'woo-product-slider' ) . '</a>';
		}

		return $plugin_meta;
	}

	/**
	 *  Sp_wps_shortcodes post type Save and update alert in Admin Dashboard created by Woo Product Slider.
	 *
	 * @param array $messages alert messages.
	 */
	public function sp_wps_update( $messages ) {
		global $post, $post_ID;
		$messages['sp_wps_shortcodes'][1] = __( 'Shortcode Updated', 'woo-product-slider' );
		$messages['sp_wps_shortcodes'][6] = __( 'Shortcode Published', 'woo-product-slider' );
		return $messages;
	}

	/**
	 * ShortCode Column.
	 *
	 * @return array $new_columns.
	 */
	public function add_shortcode_column() {
		$new_columns['cb']         = '<input type="checkbox" />';
		$new_columns['title']      = __( 'Slider Title', 'woo-product-slider' );
		$new_columns['wps_layout'] = __( 'Layout', 'woo-product-slider' );
		$new_columns['shortcode']  = __( 'Shortcode', 'woo-product-slider' );
		$new_columns['']           = '';
		$new_columns['date']       = __( 'Date', 'woo-product-slider' );

		return $new_columns;
	}

	/**
	 * Add shortcode form.
	 *
	 * @param string $column shortcode form column.
	 * @param int    $post_id post_id.
	 */
	public function add_shortcode_form( $column, $post_id ) {
		$wps_layouts   = get_post_meta( $post_id, 'sp_wps_shortcode_options', true );
		$layout_preset = isset( $wps_layouts['layout_preset'] ) ? $wps_layouts['layout_preset'] : 'slider';
		switch ( $column ) {
			case 'shortcode':
				$input_tag          = wp_kses_allowed_html( 'post' );
				$input_tag['input'] = array(
					'style'    => array(),
					'type'     => array(),
					'readonly' => array(),
					'value'    => array(),
				);
				$column_field       = '<div class="wpspro-after-copy-text"><i class="fa fa-check-circle"></i>  Shortcode  Copied to Clipboard! </div><input style="width: 270px;padding: 6px;cursor:pointer;" type="text" readonly="readonly" value="[woo_product_slider id=&quot;' . $post_id . '&quot;]"/>';
				echo wp_kses( $column_field, $input_tag );
				break;
			case 'wps_layout':
				$layout = ucwords( str_replace( '_layout', ' ', $layout_preset ) );
				esc_html_e( $layout, 'woo-product-slider' );
				break;
			default:
				break;

		} // end switch

	}

	/**
	 * Function creates product slider duplicate as a draft.
	 */
	public function sp_wps_duplicate_shortcode() {
		global $wpdb;
		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'sp_wps_duplicate_shortcode' === $_REQUEST['action'] ) ) ) {
			wp_die( esc_html__( 'No shortcode to duplicate has been supplied!', 'woo-product-slider' ) );
		}

		/**
		 * Nonce verification
		 */
		if ( ! isset( $_GET['sp_wps_duplicate_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['sp_wps_duplicate_nonce'] ) ), basename( __FILE__ ) ) ) {
			return;
		}

		/**
		* Get the original shortcode id
		*/
		$post_id = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : absint( $_POST['post'] );

		$capability = apply_filters( 'sp_wps_shortcodes_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;

		if ( ! $show_ui && get_post_type( $post_id ) !== 'sp_wps_shortcodes' ) {
			wp_die( esc_html__( 'No shortcode to duplicate has been supplied!', 'woo-product-slider' ) );
		}

		/**
		 * And all the original shortcode data then
		 */
		$post = get_post( $post_id );

		$current_user    = wp_get_current_user();
		$new_post_author = $current_user->ID;

		/**
		 * If shortcode data exists, create the shortcode duplicate
		 */
		if ( isset( $post ) && null !== $post ) {
			/**
			 * New shortcode data array
			 */
			$args = array(
				'comment_status' => $post->comment_status,
				'ping_status'    => $post->ping_status,
				'post_author'    => $new_post_author,
				'post_content'   => $post->post_content,
				'post_excerpt'   => $post->post_excerpt,
				'post_name'      => $post->post_name,
				'post_parent'    => $post->post_parent,
				'post_password'  => $post->post_password,
				'post_status'    => 'draft',
				'post_title'     => $post->post_title,
				'post_type'      => $post->post_type,
				'to_ping'        => $post->to_ping,
				'menu_order'     => $post->menu_order,
			);

			/**
			 * Insert the shortcode by wp_insert_post() function
			 */
			$new_post_id = wp_insert_post( $args );

			/**
			 * Get all current post terms ad set them to the new post draft
			 */
			$taxonomies = get_object_taxonomies( $post->post_type ); // Returns array of taxonomy names for post type, ex array("category", "post_tag").
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}

			$post_meta_infos = get_post_custom( $post_id );
			// Duplicate all post meta.
			foreach ( $post_meta_infos as $key => $values ) {
				foreach ( $values as $value ) {
					$value = wp_slash( maybe_unserialize( $value ) ); // Unserialize data to avoid conflicts.
					add_post_meta( $new_post_id, $key, $value );
				}
			}
			// Finally, redirect to the edit post screen for the new draft.
			wp_safe_redirect( esc_url( admin_url( 'edit.php?post_type=' . $post->post_type ) ) );
			exit;
		} else {
			wp_die( esc_html__( 'Shortcode creation failed, could not find original post: ', 'woo-product-slider' ) . esc_html( $post_id ) );
		}
	}

	/**
	 * Add the duplicate link to action list for post_row_actions.
	 *
	 * @param array  $actions duplicate link action.
	 * @param object $post post.
	 * @return array $actions
	 */
	public function sp_wps_duplicate_shortcode_link( $actions, $post ) {
		$capability = apply_filters( 'sp_wps_shortcodes_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		if ( $show_ui && 'sp_wps_shortcodes' === $post->post_type ) {
			$actions['duplicate'] = '<a href="' . wp_nonce_url( 'admin.php?action=sp_wps_duplicate_shortcode&post=' . $post->ID, basename( __FILE__ ), 'sp_wps_duplicate_nonce' ) . '" rel="permalink">' . __( 'Duplicate', 'woo-product-slider' ) . '</a>';
		}
		return $actions;
	}

	/**
	 * Redirect after active
	 *
	 * @param string $plugin Plugin basename.
	 */
	public function redirect_help_page( $plugin ) {
		if ( SP_WPS_BASENAME === $plugin ) {
			wp_safe_redirect( admin_url( 'edit.php?post_type=sp_wps_shortcodes&page=wps_help' ) );
			exit;
		}
	}

	/**
	 * WooCommerce not installed error message
	 */
	public function error_admin_notice() {
		$link    = esc_url(
			add_query_arg(
				array(
					'tab'       => 'plugin-information',
					'plugin'    => 'woocommerce',
					'TB_iframe' => 'true',
					'width'     => '772',
					'height'    => '446',
				),
				admin_url( 'plugin-install.php' )
			)
		);
		$outline = '<div class="error"><p>You must install and activate <a class="thickbox open-plugin-details-modal" href="' . $link . '"><strong>WooCommerce</strong></a> plugin to make the <strong>Product Slider for WooCommerce</strong> work.</p></div>';
		echo wp_kses_post( $outline );
	}

	/**
	 * Gallery Slider for WooCommerce admin notice.
	 *
	 * @since 2.2.11
	 */
	public function woo_gallery_slider_admin_notice() {

		if ( is_plugin_active( 'gallery-slider-for-woocommerce/woo-gallery-slider.php' ) ) {
			return;
		}
		if ( get_option( 'sp-woogs-notice-dismissed' ) ) {
			return;
		}

		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;

		if ( current_user_can( 'install_plugins' ) && 'sp_wps_shortcodes' === $the_current_post_type ) {

			$plugins     = array_keys( get_plugins() );
			$slug        = 'gallery-slider-for-woocommerce';
			$icon        = SP_WPS_URL . 'Admin/assets/images/woogs-logo-notice.svg';
			$text        = esc_html__( 'Install', 'woo-product-slider' );
			$button_text = esc_html__( 'Install Now', 'woo-product-slider' );
			$install_url = esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $slug ), 'install-plugin_' . $slug ) );
			$arrow       = '<svg width="14" height="10" viewBox="0 0 14 10" fill="#2171B1" xmlns="http://www.w3.org/2000/svg">
			<path d="M13.8425 4.5226L10.465 0.290439C10.3403 0.138808 10.164 0.0428426 9.97274 0.0225711C9.7815 0.00229966 9.59007 0.0592883 9.43833 0.181617C9.29698 0.313072 9.20835 0.494686 9.18999 0.6906C9.17163 0.886513 9.22487 1.08246 9.33917 1.23966L11.7425 4.26263H0.723328C0.531488 4.26263 0.347494 4.3416 0.211843 4.4822C0.0761915 4.62279 0 4.81349 0 5.01232C0 5.21116 0.0761915 5.40182 0.211843 5.54241C0.347494 5.68301 0.531488 5.76202 0.723328 5.76202H11.7425L9.33917 8.78499C9.22616 8.94269 9.17373 9.13831 9.19206 9.33383C9.21038 9.52935 9.29815 9.71082 9.43833 9.84303C9.58951 9.96682 9.78128 10.0247 9.97296 10.0044C10.1646 9.98405 10.3411 9.88716 10.465 9.73421L13.8425 5.50204C13.9447 5.36535 14.0001 5.19731 14.0001 5.02439C14.0001 4.85147 13.9447 4.68347 13.8425 4.54677V4.5226Z"></path>
		</svg>';
			if ( in_array( 'gallery-slider-for-woocommerce/woo-gallery-slider.php', $plugins, true ) ) {
				$text        = esc_html__( 'Activate', 'woo-product-slider' );
				$button_text = esc_html__( 'Activate', 'woo-product-slider' );
				$install_url = esc_url( self_admin_url( 'plugins.php?action=activate&plugin=' . urlencode( 'gallery-slider-for-woocommerce/woo-gallery-slider.php' ) . '&plugin_status=all&paged=1&s&_wpnonce=' . urlencode( wp_create_nonce( 'activate-plugin_gallery-slider-for-woocommerce/woo-gallery-slider.php' ) ) ) );
			}

			$popup_url = esc_url(
				add_query_arg(
					array(
						'tab'       => 'plugin-information',
						'plugin'    => $slug,
						'TB_iframe' => 'true',
						'width'     => '772',
						'height'    => '446',
					),
					admin_url( 'plugin-install.php' )
				)
			);
			$nonce =  wp_create_nonce( 'woogs-notice' );

			echo sprintf( '<div class="woogs-notice notice is-dismissible" data-nonce="%7$s"><img src="%1$s"/><div class="woogs-notice-text">To Enable the <strong> Single Product Image Gallery Slider</strong>, %4$s the <a href="%2$s" class="thickbox open-plugin-details-modal"><strong>Gallery Slider for WooCommerce</strong></a> and <strong>Boost Sales!</strong><a href="%3$s" rel="noopener" class="woogs-activate-btn">%5$s</a><a href="https://demo.shapedplugin.com/woo-gallery-slider/product/t-shirt/" target="_blank" class="woogs-demo-button">See How It Works<span>%6$s</span></a></div></div>', esc_url( $icon ), esc_url( $popup_url ), esc_url( $install_url ), esc_html( $text ), esc_html( $button_text ), $arrow, $nonce ); // phpcs:ignore
		}

	}
	/**
	 * Quick View for WooCommerce install admin notice.
	 *
	 * @since 2.2.11
	 */
	public function wqv_install_admin_notice() {

		if ( is_plugin_active( 'woo-quickview/woo-quick-view.php' ) ) {
			return;
		}
		if ( get_option( 'sp-wqv-notice-dismissed' ) ) {
			return;
		}

		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;

		if ( current_user_can( 'install_plugins' ) && 'sp_wps_shortcodes' === $the_current_post_type ) {

			$plugins     = array_keys( get_plugins() );
			$slug        = 'woo-quickview';
			$icon        = SP_WPS_URL . 'Admin/assets/images/woo-quick-view-notice.svg';
			$text        = esc_html__( 'Install', 'woo-product-slider' );
			$button_text = esc_html__( 'Install Now', 'woo-product-slider' );
			$install_url = esc_url( wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $slug ), 'install-plugin_' . $slug ) );
			$arrow       = '<svg width="14" height="10" viewBox="0 0 14 10" fill="#2171B1" xmlns="http://www.w3.org/2000/svg">
			<path d="M13.8425 4.5226L10.465 0.290439C10.3403 0.138808 10.164 0.0428426 9.97274 0.0225711C9.7815 0.00229966 9.59007 0.0592883 9.43833 0.181617C9.29698 0.313072 9.20835 0.494686 9.18999 0.6906C9.17163 0.886513 9.22487 1.08246 9.33917 1.23966L11.7425 4.26263H0.723328C0.531488 4.26263 0.347494 4.3416 0.211843 4.4822C0.0761915 4.62279 0 4.81349 0 5.01232C0 5.21116 0.0761915 5.40182 0.211843 5.54241C0.347494 5.68301 0.531488 5.76202 0.723328 5.76202H11.7425L9.33917 8.78499C9.22616 8.94269 9.17373 9.13831 9.19206 9.33383C9.21038 9.52935 9.29815 9.71082 9.43833 9.84303C9.58951 9.96682 9.78128 10.0247 9.97296 10.0044C10.1646 9.98405 10.3411 9.88716 10.465 9.73421L13.8425 5.50204C13.9447 5.36535 14.0001 5.19731 14.0001 5.02439C14.0001 4.85147 13.9447 4.68347 13.8425 4.54677V4.5226Z"></path>
		</svg>';
			if ( in_array( 'woo-quickview/woo-quick-view.php', $plugins, true ) ) {
				$text        = esc_html__( 'Activate', 'woo-product-slider' );
				$button_text = esc_html__( 'Activate', 'woo-product-slider' );
				$install_url = esc_url( self_admin_url( 'plugins.php?action=activate&plugin=' . urlencode( 'woo-quickview/woo-quick-view.php' ) . '&plugin_status=all&paged=1&s&_wpnonce=' . urlencode( wp_create_nonce( 'activate-plugin_woo-quickview/woo-quick-view.php' ) ) ) );
			}

			$popup_url = esc_url(
				add_query_arg(
					array(
						'tab'       => 'plugin-information',
						'plugin'    => $slug,
						'TB_iframe' => 'true',
						'width'     => '772',
						'height'    => '446',
					),
					admin_url( 'plugin-install.php' )
				)
			);

			$nonce =  wp_create_nonce( 'wqv-notice' );
			echo sprintf( '<div class="wqv-notice notice is-dismissible" data-nonce="%7$s"><img src="%1$s"/><div class="wqv-notice-text">To Allow the Customers to <strong>Have a Quick View of Products</strong>, %4$s the <a href="%2$s" class="thickbox open-plugin-details-modal"><strong>Quick View for WooCommerce</strong></a> and <strong>Boost Sales!</strong> <a href="%3$s" rel="noopener" class="wqv-activate-btn">%5$s</a><a href="https://demo.shapedplugin.com/woocommerce-quick-view/" target="_blank" class="wqv-demo-button">See How It Works<span>%6$s</span></a></div></div>', esc_url( $icon ), esc_url( $popup_url ), esc_url( $install_url ), esc_html( $text ), esc_html( $button_text ), $arrow, $nonce ); // phpcs:ignore
		}

	}

	/**
	 * Dismiss WQV install notice message
	 *
	 * @since 2.1.11
	 *
	 * @return void
	 */
	public function dismiss_wqv_notice() {
		$nonce = isset( $_GET['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['ajax_nonce'] ) ) : '';
		// Check the update permission and nonce verification.
		if ( ! current_user_can( 'install_plugins' ) || ! wp_verify_nonce( $nonce, 'wqv-notice' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Authorization failed!', 'woo-product-slider' ) ), 401 );
		}
		update_option( 'sp-wqv-notice-dismissed', 1 );
		die;
	}

	/**
	 * Dismiss Gallery Slider notice message
	 *
	 * @since 2.2.11
	 *
	 * @return void
	 */
	public function dismiss_woo_gallery_slider_notice() {
		$nonce = isset( $_GET['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_GET['ajax_nonce'] ) ) : '';
		// Check the update permission and nonce verification.
		if ( ! current_user_can( 'install_plugins' ) || ! wp_verify_nonce( $nonce, 'woogs-notice' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Authorization failed!', 'woo-product-slider' ) ), 401 );
		}
		update_option( 'sp-woogs-notice-dismissed', 1 );
		die;
	}
}
