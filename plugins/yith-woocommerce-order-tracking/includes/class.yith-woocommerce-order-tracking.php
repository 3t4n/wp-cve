<?php // phpcs:ignore WordPress.Files.FileName
/**
 * YITH_WooCommerce_Order_Tracking class
 *
 * @package YITH\OrderTracking\Classes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

if ( ! class_exists( 'YITH_WooCommerce_Order_Tracking' ) ) {

	/**
	 * Implements features of YITH WooCommerce Order Tracking
	 *
	 * @class  YITH_WooCommerce_Order_Tracking
	 * @since  1.0.0
	 * @author YITH <plugins@yithemes.com>
	 */
	class YITH_WooCommerce_Order_Tracking {

		/**
		 * Panel Object
		 *
		 * @var $_panel
		 */
		protected $panel;

		/**
		 * Premium version landing link
		 *
		 * @var string
		 */
		protected $premium_landing = 'http://yithemes.com/themes/plugins/yith-woocommerce-order-tracking/';

		/**
		 * Plugin official documentation
		 *
		 * @var string
		 */
		protected $official_documentation = 'http://yithemes.com/docs-plugins/yith-woocommerce-order-tracking/';

		/**
		 * Panel page
		 *
		 * @var string
		 */
		protected $panel_page = 'yith_woocommerce_order_tracking_panel';

		/**
		 * Default carrier name
		 *
		 * @var mixed|void
		 */
		protected $default_carrier;

		/**
		 * Position of text related to order details page
		 *
		 * @var string
		 */
		protected $order_text_position;

		/**
		 * Constructor
		 *
		 * Initialize plugin and registers actions and filters to be used
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function __construct() {
			add_action( 'plugins_loaded', array( $this, 'plugin_fw_loader' ), 15 );

			add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );
			add_filter( 'plugin_action_links_' . plugin_basename( YITH_YWOT_DIR . '/' . basename( YITH_YWOT_FILE ) ), array( $this, 'action_links' ) );
			add_filter( 'yith_show_plugin_row_meta', array( $this, 'plugin_row_meta' ), 10, 3 );

			$this->initialize_settings();

			/**
			 * Enqueue scripts and styles
			 */
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

			/**
			 * Add metabox on order, to let vendor add order tracking code and carrier
			 */
			add_action( 'add_meta_boxes', array( $this, 'add_order_tracking_metabox' ), 10, 2 );

			/**
			 * Set default carrier name on new orders
			 */
			add_action( 'woocommerce_checkout_order_processed', array( $this, 'set_default_carrier' ) );

			/**
			 * Show icon on order list for picked up orders
			 */
			add_action( 'manage_shop_order_posts_custom_column', array( $this, 'prepare_picked_up_icon' ), 10, 2 );
			add_action( 'manage_woocommerce_page_wc-orders_custom_column', array( $this, 'prepare_picked_up_icon' ), 10, 2 );

			/**
			 * Save Order Meta Boxes
			 * */
			add_action( 'woocommerce_process_shop_order_meta', array( $this, 'save_order_tracking_metabox' ), 10 );

			/**
			 * Register action to show tracking information on customer order page
			 */
			$this->register_order_tracking_actions();

			/**
			 * Show shipped icon on my orders page
			 */
			add_action( 'woocommerce_my_account_my_orders_actions', array( $this, 'show_picked_up_icon_on_orders' ), 99, 2 );

			add_action( 'woocommerce_admin_field_carriers_list', array( $this, 'show_carriers_settings' ) );

			add_action( 'before_woocommerce_init', array( $this, 'declare_wc_features_support' ) );

		}

		/**
		 * Load Plugin Framework.
		 */
		public function plugin_fw_loader() {
			if ( ! defined( 'YIT_CORE_PLUGIN' ) ) {
				global $plugin_fw_data;
				if ( ! empty( $plugin_fw_data ) ) {
					$plugin_fw_file = array_shift( $plugin_fw_data );
					require_once $plugin_fw_file;
				}
			}
		}

		/**
		 * Register panel.
		 */
		public function register_panel() {
			if ( ! empty( $this->panel ) ) {
				return;
			}

			$admin_tabs = array(
				'general'  => __( 'General options', 'yith-woocommerce-order-tracking' ),
				'carriers' => __( 'Carriers', 'yith-woocommerce-order-tracking' ),
			);

			$args = array(
				'create_menu_page' => true,
				'parent_slug'      => '',
				'plugin_slug'      => YITH_YWOT_SLUG,
				'page_title'       => 'YITH WooCommerce Order & Shipment Tracking',
				'menu_title'       => 'Order & Shipment Tracking',
				'capability'       => 'manage_options',
				'parent'           => '',
				'parent_page'      => 'yit_plugin_panel',
				'page'             => $this->panel_page,
				'admin-tabs'       => $admin_tabs,
				'options-path'     => YITH_YWOT_DIR . '/plugin-options',
				'class'            => yith_set_wrapper_class(),
				'is_free'          => defined( 'YITH_YWOT_FREE_INIT' ),
				'is_premium'       => defined( 'YITH_YWOT_PREMIUM' ),
				'premium_tab'      => array(
					'landing_page_url'          => $this->get_premium_landing_uri(),
					'premium_features'          => array(
						__( 'Choose your carriers from a list of <b>480+ carriers</b> supported to <b>automatically get the tracking URL.</b>', 'yith-woocommerce-order-tracking' ),
						__( 'Enter also the <b>Estimated Delivery Date</b> and <b>share the tracking info via email</b> with customers when the order is completed.', 'yith-woocommerce-order-tracking' ),
						__( 'Save time by <b>importing multiple tracking info into your orders from a CSV file.</b>', 'yith-woocommerce-order-tracking' ),
						__( 'Automatically change the order status to "Completed" after the tracking data insertion.', 'yith-woocommerce-order-tracking' ),
						__( 'Use the built-in shortcode to <b>create a custom order tracking page</b> on your shop.', 'yith-woocommerce-order-tracking' ),
						'<b>' . __( 'Regular updates, translations, and premium support.', 'yith-woocommerce-order-tracking' ) . '</b>',
					),
					'main_image_url'            => YITH_YWOT_URL . 'assets/images/get-premium-order-tracking.jpg',
					'show_free_vs_premium_link' => true,
				),
			);

			if ( ! class_exists( 'YIT_Plugin_Panel_WooCommerce' ) ) {
				require_once 'plugin-fw/lib/yit-plugin-panel-wc.php';
			}

			$this->panel = new YIT_Plugin_Panel_WooCommerce( $args );
		}

		/**
		 * Action Links.
		 *
		 * @param array $links Plugin links.
		 *
		 * @return array
		 */
		public function action_links( $links ) {
			$links = yith_add_action_links( $links, $this->panel_page, false );

			return $links;
		}

		/**
		 * Adds action links to plugin admin page
		 *
		 * @param array    $row_meta_args Row meta args.
		 * @param string[] $plugin_meta   An array of the plugin's metadata, including the version, author, author URI, and plugin URI.
		 * @param string   $plugin_file   Path to the plugin file relative to the plugins directory.
		 *
		 * @return array
		 */
		public function plugin_row_meta( $row_meta_args, $plugin_meta, $plugin_file ) {
			if ( YITH_YWOT_FREE_INIT === $plugin_file ) {
				$row_meta_args['slug'] = YITH_YWOT_SLUG;
			}

			return $row_meta_args;
		}

		/**
		 * Get the premium landing uri
		 *
		 * @since   1.0.0
		 * @return  string The premium landing link
		 */
		public function get_premium_landing_uri() {
			return $this->premium_landing;
		}

		/**
		 * Set values from plugin settings page
		 */
		public function initialize_settings() {
			$this->default_carrier     = get_option( 'ywot_carrier_default_name' );
			$this->order_text_position = get_option( 'ywot_order_tracking_text_position', '1' );
		}

		/**
		 * Add scripts
		 *
		 * @since  1.0
		 */
		public function enqueue_scripts() {
			global $post, $pagenow;


			wp_register_style( 'tooltipster', YITH_YWOT_URL . 'assets/css/tooltipster.bundle.min.css', array(), '4.2.8' );
			wp_register_style( 'tooltipster-borderless', YITH_YWOT_URL . 'assets/css/tooltipster-sidetip-borderless.css', array(), '4.2.8' );
			wp_register_style( 'ywot_style', YITH_YWOT_URL . 'assets/css/ywot_style.css', array(), YITH_YWOT_VERSION );

			wp_register_script( 'tooltipster', YITH_YWOT_URL . 'assets/js/tooltipster.bundle.min.js', array( 'jquery' ), '4.2.8', true );
			wp_register_script( 'ywot_script', YITH_YWOT_URL . 'assets/js/ywot.js', array(), YITH_YWOT_VERSION, true );

			$can_be_enqueue = false;
			$if_shop_order  = false;

			if ( function_exists( 'get_current_screen' ) ) {
				$current_screen_id = get_current_screen() ? get_current_screen()->id : '';
			
				$if_shop_order = function_exists( 'wc_get_page_screen_id' ) ? wc_get_page_screen_id( 'shop-order' ) === $current_screen_id : 'shop-order' === $current_screen_id;
			}

			if ( ( is_admin() && ( 'admin.php' === $pagenow && isset( $_GET['page'] ) && 'yith_woocommerce_order_tracking_panel' === $_GET['page'] ) || ( 'edit.php' === $pagenow && isset( $_GET['post_type'] ) && 'shop_order' === $_GET['post_type'] ) || ( 'post.php' === $pagenow && isset( $_GET['post'] ) ) || ( 'post-new.php' === $pagenow && isset( $_GET['post_type'] ) && 'shop_order' === $_GET['post_type'] ) ) || is_account_page() || $if_shop_order ) { // phpcs:ignore WordPress.Security.NonceVerification
				$can_be_enqueue = true;
			}

			if ( $can_be_enqueue ) {
				wp_enqueue_style( 'tooltipster' );
				wp_enqueue_style( 'tooltipster-borderless' );
				wp_enqueue_style( 'ywot_style' );

				wp_enqueue_script( 'tooltipster' );

				wp_localize_script(
					'ywot_script',
					'ywot',
					array(
						'is_account_page' => is_account_page(),
					)
				);

				wp_enqueue_script( 'ywot_script' );
			}

			if ( $if_shop_order && class_exists( 'YIT_Assets' ) ) {
				// Make sure pluing-fw scripts and styles are registered.
				if ( ! wp_script_is( 'yith-plugin-fw-fields', 'registered' ) || ! wp_style_is( 'yith-plugin-fw-fields', 'registered' ) ) {
					YIT_Assets::instance()->register_styles_and_scripts();
				}

				if ( ! defined( 'YITH_YWRAQ_PREMIUM' ) ) {
					wp_enqueue_script( 'yith-plugin-fw-fields' );
					wp_enqueue_style( 'yith-plugin-fw-fields' );
				}
			}
		}

		/**
		 *  Add a metabox on backend order page, to be filled with order tracking information
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function add_order_tracking_metabox( $post_type ) {
			if ( in_array( $post_type, array( wc_get_page_screen_id( 'shop-order' ), 'shop_order' ), true ) ) {
				add_meta_box( 
					'yith-order-tracking-information', 
					__( 'Order tracking', 'yith-woocommerce-order-tracking' ), 
					array( $this, 'show_order_tracking_metabox' ), 
					$post_type, 
					'side', 
					'high' );
			}
		}

		/**
		 * Show metabox content for tracking information on backend order page
		 *
		 * @param WP_Post $post the order object that is currently shown.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function show_order_tracking_metabox( $post ) {
			$data                = get_post_custom( $post->ID );
			$order_tracking_code = isset( $data['ywot_tracking_code'][0] ) ? $data['ywot_tracking_code'][0] : '';
			$order_carrier_name  = isset( $data['ywot_carrier_name'][0] ) ? $data['ywot_carrier_name'][0] : '';
			$order_pick_up_date  = isset( $data['ywot_pick_up_date'][0] ) ? $data['ywot_pick_up_date'][0] : '';
			$order_carrier_url   = isset( $data['ywot_carrier_url'][0] ) ? $data['ywot_carrier_url'][0] : '';
			$order_picked_up     = isset( $data['ywot_picked_up'][0] ) && ( '' !== $data['ywot_picked_up'][0] ) ? 'yes' : 'no';

			$picked_up_field = array(
				'id'    => 'ywot_picked_up',
				'name'  => 'ywot_picked_up',
				'title' => __( 'Order picked up by Carrier', 'yith-woocommerce-order-tracking' ),
				'type'  => 'onoff',
				'value' => $order_picked_up,
			);

			$date_picker_field = array(
				'id'                => 'ywot_pick_up_date',
				'name'              => 'ywot_pick_up_date',
				'title'             => __( 'Pickup date:', 'yith-woocommerce-order-tracking' ),
				'type'              => 'datepicker',
				'value'             => $order_pick_up_date,
				'data'              => array(
					'date-format' => 'yy-mm-dd',
				),
				'custom_attributes' => array(
					'placeholder' => __( 'Enter pickup date', 'yith-woocommerce-order-tracking' ),
				),
			);

			?>
			<div class="yith-ywot-track-information yith-plugin-ui">
				<div class="yith-ywot-order-picked-up-container">
					<label class="yith-ywot-order-picked-up-label" for="ywot_picked_up"><?php echo esc_html( $picked_up_field['title'] ); ?></label>
					<?php
						yith_plugin_fw_get_field( $picked_up_field, true, false );
					?>
				</div>
				<p class="yith-ywot-tracking-code">
					<label for="ywot_tracking_code"><?php esc_html_e( 'Tracking code:', 'yith-woocommerce-order-tracking' ); ?></label>
					<input type="text" name="ywot_tracking_code" id="ywot_tracking_code" placeholder="<?php esc_attr_e( 'Enter tracking code', 'yith-woocommerce-order-tracking' ); ?>" value="<?php echo esc_attr( $order_tracking_code ); ?>"/>
				</p>
				<p class="yith-ywot-tracking-carrier-name">
					<label for="ywot_carrier_name"><?php esc_html_e( 'Carrier name:', 'yith-woocommerce-order-tracking' ); ?></label>
					<input type="text" id="ywot_carrier_name" name="ywot_carrier_name" placeholder="<?php esc_attr_e( 'Enter carrier name', 'yith-woocommerce-order-tracking' ); ?>" value="<?php echo esc_attr( $order_carrier_name ); ?>"/>
				</p>
				<div class="yith-ywot-tracking-pickup-date">
					<label class="yith-ywot-order-pickup-date-label" for="ywot_pick_up_date"><?php echo esc_html( $date_picker_field['title'] ); ?></label>
					<?php
						yith_plugin_fw_get_field( $date_picker_field, true, false );
					?>
				</div>
				<p class="yith-ywot-tracking-carrier-url">
					<label for="ywot_carrier_url"><?php esc_html_e( 'Carrier website link:', 'yith-woocommerce-order-tracking' ); ?></label>
					<input type="text" id="ywot_carrier_url" name="ywot_carrier_url" placeholder="<?php esc_attr_e( 'Enter carrier website link', 'yith-woocommerce-order-tracking' ); ?>" value="<?php echo esc_attr( $order_carrier_url ); ?>"/>
				</p>
			</div>
			<?php
		}

		/**
		 * Set default carrier name when an order is created (if related option is set).
		 *
		 * @param int $post_id post id being created.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function set_default_carrier( $post_id ) {
			if ( isset( $this->default_carrier ) && ( strlen( $this->default_carrier ) > 0 ) ) {
				$order = wc_get_order( $post_id );

				if ( $order ) {
					if ( defined( 'YITH_YWOT_PREMIUM' ) ) {
						yit_save_prop( $order, array( 'ywot_carrier_id' => $this->default_carrier ) );
					} else {
						yit_save_prop( $order, array( 'ywot_carrier_name' => $this->default_carrier ) );
					}
				}
			}
		}

		/**
		 * Check if an order is flagged as picked up
		 *
		 * @param array $data post meta for current order.
		 *
		 * @since  1.0
		 *
		 * @return bool
		 */
		public function is_order_picked_up( $data ) {
			$order_picked_up = isset( $data['ywot_picked_up'][0] ) && ( '' !== $data['ywot_picked_up'][0] );

			return $order_picked_up;
		}

		/**
		 * Build a text which indicates order tracking information
		 *
		 * @param array  $data     post meta for current order.
		 * @param string $pattern  text pattern to be used.
		 *
		 * @since  1.0
		 */
		public function get_picked_up_message( $data, $pattern = '' ) {
			if ( ! isset( $pattern ) || ( 0 === strlen( $pattern ) ) ) {
				$pattern = get_option( 'ywot_order_tracking_text', wp_kses_post( __( 'Your order has been picked up by <b>[carrier_name]</b> on <b>[pickup_date]</b>. Your tracking code is <b>[track_code]</b>. Live tracking on [carrier_link]', 'yith-woocommerce-order-tracking' ) ) );
			}

			$pattern = is_admin() ? __( 'Picked up by <b>[carrier_name]</b> on <b>[pickup_date]</b>. Tracking code: <b>[track_code]</b>. Live tracking on [carrier_link]', 'yith-woocommerce-order-tracking' ) : $pattern;

			// Retrieve additional information to be shown.
			$order_tracking_code = isset( $data['ywot_tracking_code'][0] ) ? $data['ywot_tracking_code'][0] : '';
			$order_carrier_name  = isset( $data['ywot_carrier_name'][0] ) ? $data['ywot_carrier_name'][0] : '';
			$order_pick_up_date  = isset( $data['ywot_pick_up_date'][0] ) ? $data['ywot_pick_up_date'][0] : '';
			$order_carrier_link  = isset( $data['ywot_carrier_url'][0] ) ? $data['ywot_carrier_url'][0] : '';
			$carrier_link        = ! empty( $order_carrier_link ) ? '<a href="' . esc_url( $order_carrier_link ) . '" target="_blank">' . wp_kses_post( $order_carrier_name ) . '</a>' : '<span>' . wp_kses_post( $order_carrier_name ) . '</span>';

			$message = str_replace(
				array( '[carrier_name]', '[pickup_date]', '[track_code]', '[carrier_link]' ),
				array(
					$order_carrier_name,
					date_i18n( get_option( 'date_format' ), strtotime( $order_pick_up_date ) ),
					$order_tracking_code,
					$carrier_link,
				),
				$pattern
			);

			return $message;
		}

		/**
		 * Show a image stating the order has been picked up
		 *
		 * @param array  $data Post meta related to current order.
		 * @param string $css_class CSS classes.
		 *
		 * @since  1.0
		 */
		public function show_picked_up_icon( $data, $css_class = '' ) {
			if ( ! $this->is_order_picked_up( $data ) ) {
				return;
			}

			$message   = $this->get_picked_up_message( $data );
			$track_url = isset( $data['ywot_carrier_url'][0] ) ? $data['ywot_carrier_url'][0] : '';

			$href = ! empty( $track_url ) ? 'href="' . $track_url . '" target="_blank"' : '';

			?>
				<a class="button track-button <?php echo esc_attr( $css_class ); ?>" <?php echo wp_kses_post( $href ); ?> data-title="<?php echo esc_attr( $message ); ?>">
					<span class="ywot-icon-delivery track-icon"></span>

					<?php
					if ( ! is_admin() ) {
						esc_html_e( 'Track', 'yith-woocommerce-order-tracking' );
					}
					?>
				</a>
			<?php
		}

		/**
		 * Show a picked up icon on backend orders table
		 *
		 * @param string $column the column of backend order table being elaborated.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function prepare_picked_up_icon( $column, $post_id ) {
			// If column is not of type order_status, skip it.
			if ( 'order_status' !== $column ) {
				return;
			}

			$order = $post_id instanceof WC_Order ? $post_id : wc_get_order( $post_id );

			$data = get_post_custom( yit_get_prop( $order, 'id' ) );

			// if current order is not flagged as picked up, skip.
			if ( ! $this->is_order_picked_up( $data ) ) {
				return;
			}

			$this->show_picked_up_icon( $data );
		}

		/**
		 * Save additional data to the order its going to be saved. We add tracking code, carrier name and data of picking.
		 *
		 * @param int $post_id  the post id whom order tracking information should be saved.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function save_order_tracking_metabox( $post_id ) {
			$order = wc_get_order( $post_id );

			if ( $order ) {
				//phpcs:disable WordPress.Security.NonceVerification
				$parameters = array(
					'ywot_tracking_code' => isset( $_POST['ywot_tracking_code'] ) ? sanitize_text_field( wp_unslash( $_POST['ywot_tracking_code'] ) ) : '',
					'ywot_pick_up_date'  => isset( $_POST['ywot_pick_up_date'] ) ? sanitize_text_field( wp_unslash( $_POST['ywot_pick_up_date'] ) ) : '',
				);

				$parameters['ywot_picked_up'] = isset( $_POST['ywot_picked_up'] );

				if ( isset( $_POST['ywot_carrier_name'] ) ) {
					$parameters['ywot_carrier_name'] = sanitize_text_field( wp_unslash( $_POST['ywot_carrier_name'] ) );
				}

				if ( isset( $_POST['ywot_carrier_url'] ) ) {
					$parameters['ywot_carrier_url'] = sanitize_text_field( wp_unslash( $_POST['ywot_carrier_url'] ) );
				}
				//phpcs:enable WordPress.Security.NonceVerification

				yit_save_prop( $order, $parameters );
			}
		}

		/**
		 * Show message about the order tracking details.
		 *
		 * @param WC_Order $order   the order whose tracking information have to be shown.
		 * @param string   $pattern custom text to be shown.
		 * @param string   $prefix  Prefix to be shown before custom text.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function show_tracking_information( $order, $pattern, $prefix = '' ) {
			/**
			 * Show information about order shipping
			 */
			$data            = get_post_custom( yit_get_prop( $order, 'id' ) );
			$order_picked_up = isset( $data['ywot_picked_up'][0] ) && ( '' !== $data['ywot_picked_up'][0] ) ? 'checked = "checked"' : '';

			// if current order is not flagged as picked, don't show shipping information.
			if ( ! $order_picked_up ) {
				return;
			}

			$message = $this->get_picked_up_message( $data, $pattern );

			return $prefix . $message;
		}

		/**
		 * Show order tracking information on user order page when the order is set to "completed"
		 *
		 * @param WC_Order $order the order whose tracking information have to be shown.
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function add_order_shipping_details( $order ) {
			$data = get_post_custom( yit_get_prop( $order, 'id' ) );

			if ( ! $this->is_order_picked_up( $data ) ) {
				return;
			}

			$container_class = 'ywot_order_details';

			// add top or bottom class, depending on the value of related option.
			if ( 1 === intval( $this->order_text_position ) ) {
				$container_class .= ' top';
			} else {
				$container_class .= ' bottom';
			}

			echo '<div class="yith-ywot-tracking-info-container"><p class="yith-ywot-tracking-info-header">' . esc_html__( 'Tracking info', 'yith-woocommerce-order-tracking' ) . '</p><div class="' . esc_attr( $container_class ) . '">' . wp_kses_post( $this->show_tracking_information( $order, get_option( 'ywot_order_tracking_text', wp_kses_post( __( 'Your order has been picked up by <b>[carrier_name]</b> on <b>[pickup_date]</b>. Your tracking code is <b>[track_code]</b>. Live tracking on [carrier_link]', 'yith-woocommerce-order-tracking' ) ) ), '' ) ) . '</div></div>';
		}

		/**
		 * Add callback to show shipping details on order page, in the position choosen from plugin settings
		 *
		 * @since  1.0
		 * @access public
		 * @return void
		 */
		public function register_order_tracking_actions() {
			if ( ! isset( $this->order_text_position ) || ( 1 === intval( $this->order_text_position ) ) ) {
				if ( version_compare( WC()->version, '3.0.0', '<' ) ) {
					add_action( 'woocommerce_order_items_table', array( $this, 'add_order_shipping_details' ) );
				} else {
					add_action( 'woocommerce_order_details_after_order_table_items', array( $this, 'add_order_shipping_details' ) );
				}
			} else {
				add_action( 'woocommerce_order_details_after_order_table', array( $this, 'add_order_shipping_details' ) );
			}
		}

		/**
		 * Show on my orders page, a link image stating the order has been picked
		 *
		 * @param array    $actions others actions registered to the same hook.
		 * @param WC_Order $order   the order being shown.
		 *
		 * @return mixed    action passed as arguments
		 */
		public function show_picked_up_icon_on_orders( $actions, $order ) {
			$data = get_post_custom( yit_get_prop( $order, 'id' ) );

			if ( $this->is_order_picked_up( $data ) ) {
				$this->show_picked_up_icon( $data, 'button' );
			}

			return $actions;
		}

		/**
		 * Carriers Tab Template
		 *
		 * Load the carriers tab template on admin page
		 *
		 * @since  1.0
		 * @return void
		 */
		public function show_carriers_settings() {
			yith_ywot_get_view( 'carriers.php' );
		}

		/** 
		 *  Declare support for WooCommerce features. 
		 */ 
		public function declare_wc_features_support() {
			if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
				\Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', YITH_YWOT_FREE_INIT, true);
			}
		}
	}
}
