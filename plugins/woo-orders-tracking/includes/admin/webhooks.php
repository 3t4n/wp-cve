<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'VI_WOO_ORDERS_TRACKING_ADMIN_WEBHOOKS' ) ) {
	class VI_WOO_ORDERS_TRACKING_ADMIN_WEBHOOKS {
		protected static $settings;

		public function __construct() {
			self::$settings = VI_WOO_ORDERS_TRACKING_DATA::get_instance();
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 16 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		}

		public function admin_enqueue_scripts() {
			global $pagenow;
			$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
			if ( $pagenow === 'admin.php' && $page === 'woo-orders-tracking-webhooks' ) {
				VI_WOO_ORDERS_TRACKING_ADMIN_SETTINGS::admin_enqueue_semantic();
				if ( ! wp_script_is( 'transition' ) ) {
					wp_enqueue_style( 'transition', VI_WOO_ORDERS_TRACKING_CSS . 'transition.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
					wp_enqueue_script( 'transition', VI_WOO_ORDERS_TRACKING_JS . 'transition.min.js', array( 'jquery' ), VI_WOO_ORDERS_TRACKING_VERSION );
				}
				wp_enqueue_style( 'woo-orders-tracking-webhooks', VI_WOO_ORDERS_TRACKING_CSS . 'webhooks.css' );
				wp_enqueue_script( 'woo-orders-tracking-webhooks', VI_WOO_ORDERS_TRACKING_JS . 'webhooks.js', array( 'jquery' ), VI_WOO_ORDERS_TRACKING_VERSION );
			}
		}

		public function admin_menu() {
			add_submenu_page( 'woo-orders-tracking', esc_html__( 'Webhooks', 'woo-orders-tracking' ), esc_html__( 'Webhooks', 'woo-orders-tracking' ), 'manage_woocommerce', 'woo-orders-tracking-webhooks', array(
				$this,
				'page_callback'
			) );
		}

		public function page_callback() {
			$service_carrier_type = self::$settings->get_params( 'service_carrier_type' );
			?>
            <div class="wrap">
                <h2><?php esc_html_e( 'Webhooks Settings', 'woo-orders-tracking' ) ?></h2>
                <div class="vi-ui segment">
                    <div class="vi-ui positive message">
                        <div class="header"><?php esc_html_e( 'How to setup your webhook?', 'woo-orders-tracking' ); ?></div>
                        <ul class="list">
							<?php
							$statuses = VI_WOO_ORDERS_TRACKING_TRACKINGMORE::status_text();
							?>
                            <li><?php _e( 'Go to <a href="https://my.trackingmore.com/webhook_setting.php">https://my.trackingmore.com/webhook_setting.php</a>', 'woo-orders-tracking' ); ?></li>
                            <li><?php esc_html_e( 'Copy Webhook url below and paste it to Webhook URL field of your Webhook Notification Settings', 'woo-orders-tracking' ); ?></li>
                            <li><?php esc_html_e( 'Select statuses that you want to be notified', 'woo-orders-tracking' ); ?></li>
                            <li><?php esc_html_e( 'Save', 'woo-orders-tracking' ); ?></li>
                        </ul>
                    </div>
                    <form class="vi-ui form" method="post">
		                <?php wp_nonce_field( 'wot_webhooks_action', '_wot_webhooks_nonce' ); ?>
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th>
                                    <label for="<?php echo esc_attr( self::set( 'webhooks_enable' ) ) ?>"><?php esc_html_e( 'Enable webhook', 'woo-orders-tracking' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button" target="_blank"
                                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="<?php echo esc_attr( self::set( 'webhooks_debug' ) ) ?>"><?php esc_html_e( 'Enable Debug', 'woo-orders-tracking' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox"
                                               name="<?php echo esc_attr( self::set( 'webhooks_debug', true ) ) ?>"
                                               id="<?php echo esc_attr( self::set( 'webhooks_debug' ) ) ?>"
                                               value="1" <?php checked( self::$settings->get_params( 'webhooks_debug' ), '1' ) ?>>
                                    </div>
                                    <div class="description"><?php esc_html_e( 'If enabled, webhook data will be logged for debugging purpose', 'woo-orders-tracking' ) ?></div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="<?php echo esc_attr( self::set( 'webhooks_user_email' ) ) ?>"><?php esc_html_e( 'TrackingMore Email', 'woo-orders-tracking' ) ?></label>
                                </th>
                                <td>
                                    <input type="email"
                                           name="<?php echo esc_attr( self::set( 'webhooks_user_email', true ) ) ?>"
                                           id="<?php echo esc_attr( self::set( 'webhooks_user_email' ) ) ?>"
                                           value="<?php echo esc_attr( self::$settings->get_params( 'webhooks_user_email' ) ) ?>">
                                    <div class="description"><?php esc_html_e( 'Email is required to verify webhook from TrackingMore', 'woo-orders-tracking' ) ?></div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="<?php echo esc_attr( self::set( 'webhooks_send_email_' . $service_carrier_type ) ) ?>"><?php esc_html_e( 'Send email', 'woo-orders-tracking' ) ?></label>
                                </th>
                                <td>
                                    <select id="<?php echo esc_attr( self::set( 'webhooks_send_email_' . $service_carrier_type ) ) ?>"
                                            class="vi-ui fluid dropdown"
                                            name="<?php echo esc_attr( self::set( 'webhooks_send_email_' . $service_carrier_type, true ) ) ?>[]"
                                            multiple="multiple">
						                <?php
						                foreach ( $statuses as $status_k => $status_v ) {
							                ?>
                                            <option value="<?php echo esc_attr( $status_k ) ?>"><?php echo esc_html( $status_v ) ?></option>
							                <?php
						                }
						                ?>
                                    </select>
                                    <div class="description"><?php _e( 'Send email to customers if Shipment status changes to one of these values. View <a href="admin.php?page=woo-orders-tracking#email" target="_blank">Email settings</a>.', 'woo-orders-tracking' ) ?></div>
                                    <div class="description"><?php _e( '<strong>*Note: </strong>Statuses you select here must be selected in your Webhook settings', 'woo-orders-tracking' ) ?></div>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </form>
                </div>
            </div>
			<?php
		}

		public static function set( $name, $set_name = false ) {
			return VI_WOO_ORDERS_TRACKING_DATA::set( $name, $set_name );
		}
	}
}