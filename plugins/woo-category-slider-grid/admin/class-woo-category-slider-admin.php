<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @link       https://shapedplugin.com/
 * @since      1.1.0
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

/**
 * Woo_Category_Slider_Admin class
 */
class Woo_Category_Slider_Admin {

	/**
	 * The style and script suffix.
	 *
	 * @since    1.1.0
	 * @access   private
	 * @var      string    $suffix    The style and script suffix of this plugin.
	 */
	private $suffix;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		$this->suffix = defined( 'WP_DEBUG' ) && WP_DEBUG ? '' : '.min';
		spl_autoload_register( array( $this, 'autoload' ) );

		SP_WCS_Settings::settings( 'sp_wcsp_settings' );
		SP_WCS_Tools::tools( 'sp_wcsp_tools' );
		SP_WCS_Metaboxs::preview_metabox( 'sp_wcsp_live_preview' );
		SP_WCS_Metaboxs::metabox_banner( 'sp_wcsp_shortcode_banner_options' );
		SP_WCS_Metaboxs::metabox( 'sp_wcsp_shortcode_options' );

		add_action( 'admin_action_wcs_shortcode_duplicate', array( $this, 'wcs_shortcode_duplicate' ) );
		add_filter( 'post_row_actions', array( $this, 'wcs_shortcode_duplicate_link' ), 10, 2 );
	}

	/**
	 * Autoload class files on demand
	 *
	 * @param string $class requested class name.
	 * @since 1.1.0
	 */
	private function autoload( $class ) {
		$name = explode( '_', $class );
		if ( isset( $name[2] ) ) {
			$class_name   = strtolower( $name[2] );
			$config_paths = array( 'partials/', 'partials/section/settings/', 'partials/section/metabox/' );
			foreach ( $config_paths as $path ) {
				$filename = plugin_dir_path( __FILE__ ) . '/' . $path . 'class-woo-category-slider-' . $class_name . '.php';
				if ( file_exists( $filename ) ) {
					require_once $filename;
				}
			}
		}
	}

	/**
	 * Function creates woo category slider duplicate as a draft.
	 */
	public function wcs_shortcode_duplicate() {
		global $wpdb;
		if ( ! ( isset( $_GET['post'] ) || isset( $_POST['post'] ) || ( isset( $_REQUEST['action'] ) && 'wcs_shortcode_duplicate' === $_REQUEST['action'] ) ) ) {
			wp_die( esc_html__( 'No shortcode to duplicate has been supplied!', 'woo-category-slider-grid' ) );
		}

		/**
		 * Nonce verification
		 */
		if ( ! isset( $_GET['sp_wcs_duplicate_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_GET['sp_wcs_duplicate_nonce'] ) ), basename( __FILE__ ) ) ) {
			return;
		}

		/**
		 * Get the original shortcode id
		 */
		$post_id    = isset( $_GET['post'] ) ? absint( $_GET['post'] ) : absint( $_POST['post'] );
		$capability = apply_filters( 'sp_wcslider_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		if ( ! $show_ui && get_post_type( $post_id ) !== 'sp_wcslider' ) {
			wp_die( esc_html__( 'No shortcode to duplicate has been supplied!', 'woo-category-slider-grid' ) );
		}

		/**
		 * And all the original shortcode data then.
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
			$taxonomies = get_object_taxonomies( $post->post_type );
			foreach ( $taxonomies as $taxonomy ) {
				$post_terms = wp_get_object_terms( $post_id, $taxonomy, array( 'fields' => 'slugs' ) );
				wp_set_object_terms( $new_post_id, $post_terms, $taxonomy, false );
			}

			/**
			 * Duplicate all post meta.
			 */
			$post_meta_infos = get_post_custom( $post_id );

			// Duplicate all post meta.
			foreach ( $post_meta_infos as $key => $values ) {
				foreach ( $values as $value ) {
					$value = wp_slash( maybe_unserialize( $value ) ); // Unserialize data to avoid conflicts.
					add_post_meta( $new_post_id, $key, $value );
				}
			}
			/**
			 * Finally, redirect to the edit post screen for the new draft
			 */
			wp_safe_redirect( admin_url( 'edit.php?post_type=' . $post->post_type ) );
			exit;
		} else {
			wp_die( esc_html__( 'Shortcode creation failed, could not find original post: ', 'woo-category-slider-grid' ) . esc_html( $post_id ) );
		}
	}

	/**
	 * Add the duplicate link to action list for post_row_actions.
	 *
	 * @param array  $actions shortcode duplicate action.
	 * @param object $post Post type.
	 * @return array
	 */
	public function wcs_shortcode_duplicate_link( $actions, $post ) {
		$capability = apply_filters( 'sp_wcslider_ui_permission', 'manage_options' );
		$show_ui    = current_user_can( $capability ) ? true : false;
		if ( $show_ui && 'sp_wcslider' === $post->post_type ) {
			$actions['duplicate'] = '<a href="' . wp_nonce_url( 'admin.php?action=wcs_shortcode_duplicate&post=' . $post->ID, basename( __FILE__ ), 'sp_wcs_duplicate_nonce' ) . '" rel="permalink">' . __( 'Duplicate', 'woo-category-slider-grid' ) . '</a>';
		}
		return $actions;
	}

	/**
	 * Register the stylesheets for the live preview and the Admin area.
	 *
	 * @since    1.3.0
	 */
	public function admin_enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Category_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Category_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		$current_screen        = get_current_screen();
		$the_current_post_type = $current_screen->post_type;
		wp_enqueue_style( 'wcsp-fontello', SP_WCS_URL . 'admin/css/fontello.min.css', array(), SP_WCS_VERSION, 'all' );
		wp_enqueue_style( 'woo-category-slider-grid-admin' );
		if ( 'sp_wcslider' === $the_current_post_type ) {
			wp_enqueue_style( 'sp-wcs-swiper' );
			wp_enqueue_style( 'sp-wcs-font-awesome' );
			wp_enqueue_style( 'woo-category-slider-grid' );

			wp_enqueue_script( 'sp-wcs-swiper-js' );
			wp_enqueue_script( 'woo-category-slider-grid-admin-js' );
		}
	}

	/**
	 * ShortCode Column
	 *
	 * @return array
	 */
	public function add_shortcode_column() {
		$new_columns['cb']        = '<input type="checkbox" />';
		$new_columns['title']     = __( 'Title', 'woo-category-slider-grid' );
		$new_columns['shortcode'] = __( 'Shortcode', 'woo-category-slider-grid' );
		$new_columns['']          = '';
		$new_columns['date']      = __( 'Date', 'woo-category-slider-grid' );

		return $new_columns;
	}

	/**
	 * ShortCode Column Form
	 *
	 * @param string $column sortcode column.
	 * @param int    $post_id post id.
	 */
	public function add_shortcode_form( $column, $post_id ) {
		switch ( $column ) {
			case 'shortcode':
				echo '<div class="wcsp-after-copy-text"><i class="fa fa-check-circle"></i>  Shortcode  Copied to Clipboard! </div><input style="width: 230px;padding: 6pwidth: 230px;padding: 6px;;cursor:pointer;" type="text" onClick="this.select();" readonly="readonly" value="[woocatslider id=&quot;' . esc_attr( $post_id ) . '&quot;]"/>';
				break;
			default:
				break;

		} // end switch
	}

	/**
	 * Add plugin action menu
	 *
	 * @param array  $links plugin menu links.
	 * @param string $file plugin file.
	 *
	 * @return array
	 */
	public function add_plugin_action_links( $links, $file ) {

		if ( $file === SP_WCS_BASENAME ) {

			$ui_links = sprintf( '<a href="%s">%s</a>', admin_url( 'post-new.php?post_type=sp_wcslider' ), __( 'Add New', 'woo-category-slider-grid' ) );

			array_unshift( $links, $ui_links );

			$links['go_pro'] = sprintf( '<a target="_blank" href="%1$s" style="color: #35b747; font-weight: 700;">Go Pro!</a>', 'https://shapedplugin.com/plugin/woocommerce-category-slider-pro/?ref=115' );
		}

		return $links;
	}

	/**
	 * Plugin row meta.
	 *
	 * Adds row meta links to the plugin list table
	 *
	 * Fired by `plugin_row_meta` filter.
	 *
	 * @since  1.1.0
	 * @access public
	 *
	 * @param array  $plugin_meta An array of the plugin's metadata, including
	 *                            the version, author, author URI, and plugin URI.
	 * @param string $plugin_file Path to the plugin file, relative to the plugins
	 *                            directory.
	 *
	 * @return array An array of plugin row meta links.
	 */
	public function plugin_row_meta( $plugin_meta, $plugin_file ) {
		if ( SP_WCS_BASENAME === $plugin_file ) {
			$row_meta = array(
				'live_demo' => '<a href="https://demo.shapedplugin.com/woocommerce-category-slider/" aria-label="' . esc_attr( __( 'Live Demo', 'woo-category-slider-grid' ) ) . '" target="_blank">' . __( 'Live Demo', 'woo-category-slider-grid' ) . '</a>',
			);

			$plugin_meta = array_merge( $plugin_meta, $row_meta );
		}

		return $plugin_meta;
	}

	/**
	 * Post update messages for Shortcode Generator.
	 *
	 * @param array $message post update message.
	 */
	public function post_update_message( $message ) {
		$screen = get_current_screen();
		if ( 'sp_wcslider' === $screen->post_type ) {
			$message['post'][1]  = esc_html__( 'Slider updated.', 'woo-category-slider-grid' );
			$message['post'][4]  = esc_html__( 'Slider updated.', 'woo-category-slider-grid' );
			$message['post'][6]  = esc_html__( 'Slider published.', 'woo-category-slider-grid' );
			$message['post'][8]  = esc_html__( 'Slider submitted.', 'woo-category-slider-grid' );
			$message['post'][10] = esc_html__( 'Slider draft updated.', 'woo-category-slider-grid' );
		}

		return $message;
	}

	/**
	 * Admin footer text.
	 *
	 * @param string $text Footer text.
	 * @return string
	 */
	public function admin_footer( $text ) {
		$screen = get_current_screen();
		if ( 'sp_wcslider' === $screen->post_type ) {
			$url  = 'https://wordpress.org/support/plugin/woo-category-slider-grid/reviews/?filter=5#new-post';
			$text = sprintf( __( 'If you like <strong>Category Slider for WooCommerce</strong> please leave us a <a href="%s" target="_blank">&#9733;&#9733;&#9733;&#9733;&#9733;</a> rating. Your Review is very important to us as it helps us to grow more. ', 'woo-category-slider-grid' ), $url );
		}

		return $text;
	}

	/**
	 * Show notice if woocommerce plugin is not installed
	 *
	 * @since 1.10
	 *
	 * @return void
	 */
	public function admin_notice() {
		if ( current_user_can( 'install_plugins' ) ) {

			$action = empty( $_GET['sp-wcsp-woo'] ) ? '' : \sanitize_text_field( wp_unslash( $_GET['sp-wcsp-woo'] ) );
			$plugin = 'woocommerce/woocommerce.php';
			require_once SP_WCS_PATH . 'admin/helper/class-woo-category-slider-woo.php';
			$woo_install = new SP_WCS_WOO();

			if ( 'install' === $action ) {
				$woo_install->install_plugin( 'https://downloads.wordpress.org/plugin/woocommerce.zip' );
			} elseif ( 'activate' === $action ) {
				$woo_install->activate_woo_plugin( $plugin );
			}

			if ( ! class_exists( 'WooCommerce' ) ) {
				if ( \file_exists( WP_PLUGIN_DIR . '/' . $plugin ) ) {
					if ( ! \is_plugin_active( $plugin ) ) {
						$this->woo_notice_message( 'activate' );
					}
				} else {
					$this->woo_notice_message( 'install' );
				}
			}
		}
	}

	/**
	 * WooCommerce notice message
	 *
	 * @since 1.1.0
	 *
	 * @param String $type notice message type.
	 *
	 * @return void
	 */
	public function woo_notice_message( $type ) {
		$actual_link = esc_url( ( isset( $_SERVER['HTTPS'] ) ? 'https' : 'http' ) . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" );
		$sign        = empty( $_GET ) ? '?' : '&';

		echo '<div class="updated notice is-dismissible notice-sp-wcsp-woo" data-nonce="' . wp_create_nonce( 'dismiss-wcsp-woo-notice' ) . '"><p>';
		echo wp_kses_post( 'Please ' . $type . ' <a href="' . esc_url( $actual_link . $sign . 'sp-wcsp-woo=' . $type ) . '">WooCommerce</a> plugin to make the <b>Category Slider for WooCommerce</b> work.', 'woo-category-slider-grid' );
		echo '</p></div>';
	}

	/**
	 * Dismiss WooCommerce notice message
	 *
	 * @since 1.1.0
	 *
	 * @return void
	 */
	public function dismiss_woo_notice() {
		$nonce = isset( $_POST['ajax_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['ajax_nonce'] ) ) : '';
		// Check the update permission and nonce verification.
		if ( ! current_user_can( 'install_plugins' ) || ! wp_verify_nonce( $nonce, 'dismiss-wcsp-woo-notice' ) ) {
			wp_send_json_error( array( 'error' => esc_html__( 'Authorization failed!', 'woo-category-slider-grid' ) ), 401 );
		}

		update_option( 'sp-wcsp-woo-notice-dismissed', 1 );
		die;
	}

	/**
	 * Declare the compatibility of WooCommerce High-Performance Order Storage (HPOS) feature.
	 *
	 * @since 1.4.15
	 *
	 * @return void
	 */
	public function declare_compatibility_with_woo_hpos_feature() {
		if ( class_exists( \Automattic\WooCommerce\Utilities\FeaturesUtil::class ) ) {
			\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility( 'custom_order_tables', 'woo-category-slider-grid/woo-category-slider-grid.php', true );
		}
	}

	/**
	 * Redirect after active.
	 *
	 * @param string $plugin plugin base name.
	 * @return void
	 */
	public function redirect_to( $plugin ) {
		if ( SP_WCS_BASENAME === $plugin ) {
			wp_safe_redirect( admin_url( 'edit.php?post_type=sp_wcslider&page=wcsp_help' ) );
			exit();
		}
	}

}
