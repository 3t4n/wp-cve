<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'VI_WOO_ORDERS_TRACKING_ADMIN_CRON_UPDATE_TRACKING' ) ) {
	class VI_WOO_ORDERS_TRACKING_ADMIN_CRON_UPDATE_TRACKING {
		protected $settings;
		protected $carriers;
		protected $next_schedule;

		public function __construct() {
			$this->settings      = VI_WOO_ORDERS_TRACKING_DATA::get_instance();
			$this->next_schedule = wp_next_scheduled( 'woo_orders_tracking_cron_update_tracking' );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
			add_action( 'admin_menu', array( $this, 'admin_menu' ), 16 );
		}

		private static function set( $name, $set_name = false ) {
			return VI_WOO_ORDERS_TRACKING_DATA::set( $name, $set_name );
		}

		public function admin_menu() {
			add_submenu_page( 'woo-orders-tracking', esc_html__( 'Schedule Update', 'woo-orders-tracking' ), esc_html__( 'Schedule Update', 'woo-orders-tracking' ), 'manage_woocommerce', 'woo-orders-tracking-cron-update-tracking', array(
				$this,
				'page_callback'
			) );
		}

		public function page_callback() {
			$service_carrier_type = $this->settings->get_params( 'service_carrier_type' );
			?>
            <div class="wrap">
                <h2><?php esc_html_e( 'Schedule Update for tracking number', 'woo-orders-tracking' ) ?></h2>
				<?php
				if ( $service_carrier_type !== 'cainiao' ) {
					?>
                    <form class="vi-ui form" method="post">
						<?php
						wp_nonce_field( 'wot_cron_update_tracking', 'wot_cron_update_tracking_nonce' );
						?>
                        <div class="vi-ui segment">
							<?php
							if ( $this->next_schedule ) {
								$gmt_offset = intval( get_option( 'gmt_offset' ) );
								?>
                                <div class="vi-ui positive message"><?php printf( __( 'Next schedule: <strong>%s</strong>', 'woo-orders-tracking' ), date_i18n( 'F j, Y g:i:s A', ( $this->next_schedule + HOUR_IN_SECONDS * $gmt_offset ) ) ); ?></div>
								<?php
							} else {
								?>
                                <div class="vi-ui negative message"><?php esc_html_e( 'Schedule Update is currently DISABLED', 'woo-orders-tracking' );; ?></div>
								<?php
							}
							?>
                            <table class="form-table">
                                <tbody>
                                <tr>
                                    <th>
                                        <label for="<?php echo esc_attr( self::set( 'cron_update_tracking' ) ) ?>"><?php esc_html_e( 'Enable cron', 'woo-orders-tracking' ) ?></label>
                                    </th>
                                    <td>
                                        <a class="vi-ui button" target="_blank"
                                           href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="<?php echo esc_attr( self::set( 'cron_update_tracking_interval' ) ) ?>"><?php esc_html_e( 'Run update every', 'woo-orders-tracking' ) ?></label>
                                    </th>
                                    <td>
                                        <div class="vi-ui right labeled input">
                                            <input type="number" min="1"
                                                   name="<?php echo esc_attr( self::set( 'cron_update_tracking_interval', true ) ) ?>"
                                                   id="<?php echo esc_attr( self::set( 'cron_update_tracking_interval' ) ) ?>"
                                                   value="<?php echo esc_attr( $this->settings->get_params( 'cron_update_tracking_interval' ) ) ?>">
                                            <label for="<?php echo esc_attr( self::set( 'cron_update_tracking_interval' ) ) ?>"
                                                   class="vi-ui label"><?php esc_html_e( 'Day(s)', 'woo-orders-tracking' ) ?></label>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="<?php echo esc_attr( self::set( 'cron_update_tracking_hour' ) ) ?>"><?php esc_html_e( 'Run update at', 'woo-orders-tracking' ) ?></label>
                                    </th>
                                    <td>
                                        <div class="equal width fields">
                                            <div class="field">
                                                <div class="vi-ui left labeled input">
                                                    <label for="<?php echo esc_attr( self::set( 'cron_update_tracking_hour' ) ) ?>"
                                                           class="vi-ui label"><?php esc_html_e( 'Hour', 'woo-orders-tracking' ) ?></label>
                                                    <input type="number" min="0" max="23"
                                                           name="<?php echo esc_attr( self::set( 'cron_update_tracking_hour', true ) ) ?>"
                                                           id="<?php echo esc_attr( self::set( 'cron_update_tracking_hour' ) ) ?>"
                                                           value="<?php echo esc_attr( $this->settings->get_params( 'cron_update_tracking_hour' ) ) ?>">
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="vi-ui left labeled input">
                                                    <label for="<?php echo esc_attr( self::set( 'cron_update_tracking_minute' ) ) ?>"
                                                           class="vi-ui label"><?php esc_html_e( 'Minute', 'woo-orders-tracking' ) ?></label>
                                                    <input type="number" min="0" max="59"
                                                           name="<?php echo esc_attr( self::set( 'cron_update_tracking_minute', true ) ) ?>"
                                                           id="<?php echo esc_attr( self::set( 'cron_update_tracking_minute' ) ) ?>"
                                                           value="<?php echo esc_attr( $this->settings->get_params( 'cron_update_tracking_minute' ) ) ?>">
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="vi-ui left labeled input">
                                                    <label for="<?php echo esc_attr( self::set( 'cron_update_tracking_second' ) ) ?>"
                                                           class="vi-ui label"><?php esc_html_e( 'Second', 'woo-orders-tracking' ) ?></label>
                                                    <input type="number" min="0" max="59"
                                                           name="<?php echo esc_attr( self::set( 'cron_update_tracking_second', true ) ) ?>"
                                                           id="<?php echo esc_attr( self::set( 'cron_update_tracking_second' ) ) ?>"
                                                           value="<?php echo esc_attr( $this->settings->get_params( 'cron_update_tracking_second' ) ) ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="<?php echo esc_attr( self::set( 'cron_update_tracking_range' ) ) ?>"><?php esc_html_e( 'Only query tracking created in the last x day(s):', 'woo-orders-tracking' ) ?></label>
                                    </th>
                                    <td>
                                        <div class="vi-ui right labeled input">
                                            <input type="number" min="1" max=""
                                                   name="<?php echo esc_attr( self::set( 'cron_update_tracking_range', true ) ) ?>"
                                                   id="<?php echo esc_attr( self::set( 'cron_update_tracking_range' ) ) ?>"
                                                   value="<?php echo esc_attr( $this->settings->get_params( 'cron_update_tracking_range' ) ) ?>">
                                            <label for="<?php echo esc_attr( self::set( 'cron_update_tracking_range' ) ) ?>"
                                                   class="vi-ui label"><?php esc_html_e( 'Day(s)', 'woo-orders-tracking' ) ?></label>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
					<?php
				} else {
					?>
                    <div class="vi-ui negative message">
                        <div class="header"><?php esc_html_e( 'Schedule update is not available with your currently selected tracking service', 'woo-orders-tracking' ); ?></div>
                    </div>
					<?php
				}
				?>
            </div>
			<?php
		}

		public function admin_enqueue_scripts() {
			global $pagenow;
			$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
			if ( $pagenow === 'admin.php' && $page === 'woo-orders-tracking-cron-update-tracking' ) {
				VI_WOO_ORDERS_TRACKING_ADMIN_SETTINGS::admin_enqueue_semantic();
			}
		}
	}
}