<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_ORDERS_TRACKING_ADMIN_SETTINGS {
	private $settings;
	private $schedule_send_emails;
	private $shipping_countries;

	public function __construct() {
		$this->settings = VI_WOO_ORDERS_TRACKING_DATA::get_instance();
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_init', array( $this, 'save_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ) );
		add_action( 'wp_ajax_wotv_admin_add_new_shipping_carrier', array(
			$this,
			'wotv_admin_add_new_shipping_carrier'
		) );
		add_action( 'wp_ajax_wotv_admin_edit_shipping_carrier', array( $this, 'wotv_admin_edit_shipping_carrier' ) );
		add_action( 'wp_ajax_wotv_admin_delete_shipping_carrier', array(
			$this,
			'wotv_admin_delete_shipping_carrier'
		) );
		add_action( 'media_buttons', array( $this, 'preview_emails_button' ) );
		add_action( 'wp_ajax_wot_preview_emails', array( $this, 'wot_preview_emails' ) );
		add_action( 'wp_ajax_wot_test_connection_paypal', array( $this, 'wot_test_connection_paypal' ) );
		add_action( 'wp_ajax_woo_orders_tracking_search_page', array( $this, 'search_page' ) );
	}

	public static function set( $name, $set_name = false ) {
		return VI_WOO_ORDERS_TRACKING_DATA::set( $name, $set_name );
	}

	public function admin_menu() {
		add_menu_page(
			esc_html__( 'Orders Tracking for WooCommerce settings', 'woo-orders-tracking' ),
			esc_html__( 'Orders Tracking', 'woo-orders-tracking' ),
			'manage_options',
			'woo-orders-tracking',
			array( $this, 'settings_callback' ),
			'dashicons-location',
			'2'
		);
	}


	public function save_settings() {
		global $pagenow;
		global $woo_orders_tracking_settings;
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		if ( $pagenow === 'admin.php' && $page === 'woo-orders-tracking' ) {
			if ( isset( $_POST['_vi_wot_setting_nonce'] ) && wp_verify_nonce( $_POST['_vi_wot_setting_nonce'], 'vi_wot_setting_action_nonce' ) ) {
				$args                                      = $woo_orders_tracking_settings;
				$args['active_carriers']                   = isset( $_POST['woo-orders-tracking-settings']['active_carriers'] ) ? self::stripslashes_deep( $_POST['woo-orders-tracking-settings']['active_carriers'] ) : array();
				$args['service_carrier_enable']            = isset( $_POST['woo-orders-tracking-settings']['service_carrier']['service_carrier_enable'] ) ? self::stripslashes( $_POST['woo-orders-tracking-settings']['service_carrier']['service_carrier_enable'] ) : '';
				$args['service_carrier_type']              = isset( $_POST['woo-orders-tracking-settings']['service_carrier']['service_carrier_type'] ) ? self::stripslashes( $_POST['woo-orders-tracking-settings']['service_carrier']['service_carrier_type'] ) : '';
				$args['service_tracking_page']             = isset( $_POST['woo-orders-tracking-settings']['service_carrier']['service_tracking_page'] ) ? self::stripslashes( $_POST['woo-orders-tracking-settings']['service_carrier']['service_tracking_page'] ) : '';
				$args['service_cache_request']             = isset( $_POST['woo-orders-tracking-settings']['service_carrier']['service_cache_request'] ) ? self::stripslashes( $_POST['woo-orders-tracking-settings']['service_carrier']['service_cache_request'] ) : '';
				$args['service_carrier_api_key']           = isset( $_POST['woo-orders-tracking-settings']['service_carrier']['service_carrier_api_key'] ) ? self::stripslashes( $_POST['woo-orders-tracking-settings']['service_carrier']['service_carrier_api_key'] ) : '';
				$args['service_add_tracking_if_not_exist'] = isset( $_POST['woo-orders-tracking-settings']['service_carrier']['service_add_tracking_if_not_exist'] ) ? self::stripslashes( $_POST['woo-orders-tracking-settings']['service_carrier']['service_add_tracking_if_not_exist'] ) : '';
				$args['email_woo_enable']                  = isset( $_POST['woo-orders-tracking-settings']['email_woo']['email_woo_enable'] ) ? self::stripslashes( $_POST['woo-orders-tracking-settings']['email_woo']['email_woo_enable'] ) : '';
				$args['email_woo_status']                  = isset( $_POST['woo-orders-tracking-settings']['email_woo']['email_woo_status'] ) ? self::stripslashes_deep( $_POST['woo-orders-tracking-settings']['email_woo']['email_woo_status'] ) : array();
				$args['email_woo_position']                = isset( $_POST['woo-orders-tracking-settings']['email_woo']['email_woo_position'] ) ? self::stripslashes( $_POST['woo-orders-tracking-settings']['email_woo']['email_woo_position'] ) : 'after_order_table';
				$args['email_send_all_order_items']        = isset( $_POST['woo-orders-tracking-settings']['email']['email_send_all_order_items'] ) ? self::stripslashes( $_POST['woo-orders-tracking-settings']['email']['email_send_all_order_items'] ) : '';
				$args['email_time_send']                   = isset( $_POST['woo-orders-tracking-settings']['email']['email_time_send'] ) ? self::stripslashes( $_POST['woo-orders-tracking-settings']['email']['email_time_send'] ) : '';
				$args['email_time_send_type']              = isset( $_POST['woo-orders-tracking-settings']['email']['email_time_send_type'] ) ? self::stripslashes( $_POST['woo-orders-tracking-settings']['email']['email_time_send_type'] ) : '';
				$args['email_number_send']                 = isset( $_POST['woo-orders-tracking-settings']['email']['email_number_send'] ) ? self::stripslashes( $_POST['woo-orders-tracking-settings']['email']['email_number_send'] ) : '';
				$args['email_subject']                     = isset( $_POST['woo-orders-tracking-settings']['email']['email_subject'] ) ? self::stripslashes( $_POST['woo-orders-tracking-settings']['email']['email_subject'] ) : '';
				$args['email_heading']                     = isset( $_POST['woo-orders-tracking-settings']['email']['email_heading'] ) ? self::stripslashes( $_POST['woo-orders-tracking-settings']['email']['email_heading'] ) : '';
				$args['email_content']                     = isset( $_POST['woo-orders-tracking-settings']['email']['email_content'] ) ? self::stripslashes_deep( $_POST['woo-orders-tracking-settings']['email']['email_content'] ) : '';
				$args['paypal_sandbox_enable']             = isset( $_POST['woo-orders-tracking-settings']['paypal']['paypal_sandbox_enable'] ) ? self::stripslashes_deep( $_POST['woo-orders-tracking-settings']['paypal']['paypal_sandbox_enable'] ) : array();
				$args['paypal_method']                     = isset( $_POST['woo-orders-tracking-settings']['paypal']['paypal_method'] ) ? self::stripslashes_deep( $_POST['woo-orders-tracking-settings']['paypal']['paypal_method'] ) : array();
				$args['paypal_client_id_live']             = isset( $_POST['woo-orders-tracking-settings']['paypal']['paypal_client_id_live'] ) ? self::stripslashes_deep( $_POST['woo-orders-tracking-settings']['paypal']['paypal_client_id_live'] ) : array();
				$args['paypal_client_id_sandbox']          = isset( $_POST['woo-orders-tracking-settings']['paypal']['paypal_client_id_sandbox'] ) ? self::stripslashes_deep( $_POST['woo-orders-tracking-settings']['paypal']['paypal_client_id_sandbox'] ) : array();
				$args['paypal_secret_live']                = isset( $_POST['woo-orders-tracking-settings']['paypal']['paypal_secret_live'] ) ? self::stripslashes_deep( $_POST['woo-orders-tracking-settings']['paypal']['paypal_secret_live'] ) : array();
				$args['paypal_secret_sandbox']             = isset( $_POST['woo-orders-tracking-settings']['paypal']['paypal_secret_sandbox'] ) ? self::stripslashes_deep( $_POST['woo-orders-tracking-settings']['paypal']['paypal_secret_sandbox'] ) : array();
				$args['paypal_debug']                      = isset( $_POST['woo-orders-tracking-settings']['paypal']['paypal_debug'] ) ? self::stripslashes_deep( $_POST['woo-orders-tracking-settings']['paypal']['paypal_debug'] ) : '';
				if ( count( $args['active_carriers'] ) ) {
					$args['shipping_carrier_default'] = $args['active_carriers'][0];
				} elseif ( $args['shipping_carrier_default'] ) {
					$args['active_carriers'] = array( $args['shipping_carrier_default'] );
				}
				update_option( 'woo_orders_tracking_settings', $args );
				$woo_orders_tracking_settings = $args;
				$this->settings               = VI_WOO_ORDERS_TRACKING_DATA::get_instance( true );
			}
		}
	}

	public static function sort_by_time( $array1, $array2 ) {
		return $array1['time'] - $array2['time'];
	}

	private static function stripslashes( $value ) {
		return sanitize_text_field( stripslashes( $value ) );
	}

	private static function stripslashes_deep( $value ) {
		if ( is_array( $value ) ) {
			$value = array_map( 'stripslashes_deep', $value );
		} else {
			$value = wp_kses_post( stripslashes( $value ) );
		}

		return $value;
	}


	public function settings_callback() {
		?>
        <div class="wrap">
            <h2><?php esc_html_e( 'Orders Tracking for WooCommerce settings', 'woo-orders-tracking' ); ?></h2>
            <div class="vi-ui raised">
                <form action="" class="vi-ui form" method="post">
					<?php
					wp_nonce_field( 'vi_wot_setting_action_nonce', '_vi_wot_setting_nonce' );
					?>
                    <div class="vi-ui vi-ui-main top tabular attached menu ">
                        <a class="item active" data-tab="shipping_carriers">
							<?php esc_html_e( 'Shipping Carriers', 'woo-orders-tracking' ) ?>
                        </a>
                        <a class="item " data-tab="email">
							<?php esc_html_e( 'Email', 'woo-orders-tracking' ) ?>
                        </a>
                        <a class="item " data-tab="email_woo">
							<?php esc_html_e( 'WooCommerce Email', 'woo-orders-tracking' ) ?>
                        </a>
                        <a class="item " data-tab="sms">
							<?php esc_html_e( 'SMS', 'woo-orders-tracking' ) ?>
                        </a>
                        <a class="item " data-tab="paypal">
							<?php esc_html_e( 'PayPal', 'woo-orders-tracking' ) ?>
                        </a>
                        <a class="item" data-tab="tracking_service">
							<?php esc_html_e( 'Tracking Service', 'woo-orders-tracking' ) ?>
                        </a>
                    </div>
                    <div class="vi-ui bottom attached tab segment active" data-tab="shipping_carriers">
						<?php
						$this->shipping_carriers_settings();
						?>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="email">
						<?php
						$this->email_settings();
						?>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="email_woo">
						<?php
						$this->email_woo_settings();
						?>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="sms">
						<?php
						$this->sms_settings();
						?>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="paypal">
						<?php
						$this->paypal_settings();
						?>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="tracking_service">
						<?php
						$this->tracking_service_settings();
						?>
                    </div>
                    <p class="<?php echo esc_attr( self::set( 'button-save-settings-container' ) ) ?>">
                        <button type="submit"
                                name="<?php echo esc_attr( self::set( 'settings-save-button' ) ) ?>"
                                class="<?php echo esc_attr( self::set( 'settings-save-button' ) ) ?> vi-ui button primary labeled icon">
                            <i class="icon save"></i>
							<?php esc_html_e( 'Save', 'woo-orders-tracking' ); ?>
                        </button>
                    </p>
                </form>
            </div>
        </div>
		<?php
		do_action( 'villatheme_support_woo-orders-tracking' );
	}

	private function shipping_carriers_settings() {
		$countries = new WC_Countries();
		$countries = $countries->get_countries();
		?>
        <div class="<?php echo esc_attr( self::set( array(
			'setting-shipping-carriers-overlay',
			'hidden'
		) ) ) ?>"></div>
        <div class="vi-ui positive small message"><?php esc_html_e( 'To turn off a carrier that is currently the default carrier(the first one), you first have to set another carrier as the default by hovering over it then click the pointing hand icon.', 'woo-orders-tracking' ) ?></div>
        <div class="<?php echo esc_attr( self::set( array( 'setting-shipping-carriers-header' ) ) ) ?>">
            <div class="<?php echo esc_attr( self::set( array( 'setting-shipping-carriers-filter-wrap' ) ) ) ?>">
                <div class="<?php echo esc_attr( self::set( array( 'setting-shipping-carriers-filter-type-wrap' ) ) ) ?>">
                    <select name=""
                            id="<?php echo esc_attr( self::set( array( 'setting-shipping-carriers-filter-type' ) ) ) ?>"
                            class="vi-ui dropdown fluid <?php echo esc_attr( self::set( array( 'setting-shipping-carriers-filter-type' ) ) ) ?>">
                        <option value="all"><?php esc_html_e( 'All Carriers', 'woo-orders-tracking' ) ?></option>
                        <option value="custom"><?php esc_html_e( 'Custom Carriers ', 'woo-orders-tracking' ) ?></option>
                    </select>
                </div>
                <div class="<?php echo esc_attr( self::set( array( 'setting-shipping-carriers-filter-country-wrap' ) ) ) ?>">
                    <select name=""
                            id="<?php echo esc_attr( self::set( array( 'setting-shipping-carriers-filter-country' ) ) ) ?>"
                            class="<?php echo esc_attr( self::set( array( 'setting-shipping-carriers-filter-country' ) ) ) ?>">
                        <option value="all_country"
                                selected><?php esc_html_e( 'All Countries ', 'woo-orders-tracking' ) ?></option>
                        <option value="Global"><?php esc_html_e( 'Global', 'woo-orders-tracking' ) ?></option>
						<?php
						foreach ( $countries as $country_code => $country_name ) {
							?>
                            <option value="<?php echo esc_attr( $country_code ) ?>"><?php echo esc_html( $country_name ) ?></option>
							<?php
						}
						?>
                    </select>
                </div>
            </div>
            <div class="<?php echo esc_attr( self::set( array( 'setting-shipping-carriers-search-wrap' ) ) ) ?>">
                <div class="vi-ui segment <?php echo esc_attr( self::set( array( 'setting-shipping-carriers-toggle-active-wrap' ) ) ) ?>">
                    <div class="vi-ui fitted toggle checkbox"
                         title="<?php echo esc_attr__( 'Toggle active status of all carrier on/off', 'woo-orders-tracking' ) ?>">
                        <input type="checkbox"
                               class="<?php echo esc_attr( self::set( array( 'setting-shipping-carriers-toggle-active' ) ) ) ?>"><label></label>
                    </div>
                </div>
                <span class="vi-ui button olive <?php echo esc_attr( self::set( array( 'setting-shipping-carriers-add-new-carrier' ) ) ) ?>"><?php esc_html_e( 'Add Carriers', 'woo-orders-tracking' ) ?></span>
            </div>
        </div>
        <div class="<?php echo esc_attr( self::set( array( 'setting-shipping-carriers-list-wrap' ) ) ) ?>">
        </div>
        <div class="<?php echo esc_attr( self::set( array(
			'setting-shipping-carriers-list-search-wrap',
			'hidden'
		) ) ) ?>">
            <p><?php printf( __( 'Unable to find your needed shipping carrier? Please <a class="%s" href="#">click here</a> to add your own carrier.', 'woo-orders-tracking' ), esc_attr( self::set( array( 'setting-shipping-carriers-add-new-carrier-shortcut' ) ) ) ) ?></p>
            <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/8NZwSnSWsIg?start=425"
                    title="YouTube video player" frameborder="0"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen></iframe>
        </div>
		<?php
	}

	private function email_settings() {
		if ( $this->schedule_send_emails ) {
			$orders = get_option( 'vi_wot_send_mails_for_import_csv_function_orders' );
			if ( $orders ) {
				$orders = vi_wot_json_decode( $orders );
				if ( count( $orders ) ) {
					$gmt_offset = intval( get_option( 'gmt_offset' ) );
					?>
                    <div class="vi-ui positive message">
                        <div class="header">
							<?php printf( wp_kses_post( __( 'Next schedule: <strong>%s</strong>', 'woo-orders-tracking' ) ), date_i18n( 'F j, Y g:i:s A', ( $this->schedule_send_emails + HOUR_IN_SECONDS * $gmt_offset ) ) ); ?>
                        </div>
                        <p><?php printf( esc_html__( 'Order(s) to send next: %s', 'woo-orders-tracking' ), implode( ',', array_splice( $orders, 0, $this->settings->get_params( 'email_number_send' ) ) ) ); ?></p>
                    </div>
					<?php
				}
			}
		}
		?>
        <div class="vi-ui positive message">
            <div>
				<?php esc_html_e( 'Settings for sending individual email if you check the send email checkbox(when editing order tracking/importing tracking/Webhooks)', 'woo-orders-tracking' ) ?>
            </div>
        </div>
        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'email_send_all_order_items' ) ) ?>"><?php esc_html_e( 'Send tracking of whole order', 'woo-orders-tracking' ) ?></label>
                </th>
                <td>
                    <div class="vi-ui toggle checkbox">
                        <input type="checkbox"
                               name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[email][email_send_all_order_items]"
                               id="<?php echo esc_attr( self::set( 'email_send_all_order_items' ) ) ?>"
                               value="1" <?php checked( $this->settings->get_params( 'email_send_all_order_items' ), '1' ) ?>><label></label>
                    </div>
                    <p class="description"><?php esc_html_e( '{tracking_table} will include tracking of all items of an order instead of only changed one. Helpful when you add tracking number for single item of an order.', 'woo-orders-tracking' ) ?></p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'email_template' ) ) ?>"><?php esc_html_e( 'Email template', 'woo-orders-tracking' ) ?></label>
                </th>
                <td>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                    <p><?php _e( 'You can use <a href="https://1.envato.market/BZZv1" target="_blank">WooCommerce Email Template Customizer</a> or <a href="http://bit.ly/woo-email-template-customizer" target="_blank">Email Template Customizer for WooCommerce</a> to create and customize your own email template. If no email template is selected, below email will be used.', 'woo-orders-tracking' ) ?></p>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="vi-ui segment">
            <div class="vi-ui message"><?php esc_html_e( 'This email is used when no Email template is selected', 'woo-orders-tracking' ) ?></div>
            <table class="form-table">
                <tbody>
                <tr>
                    <th>
                        <label for="<?php echo esc_attr( self::set( 'setting-email-subject' ) ) ?>">
							<?php esc_html_e( 'Email subject', 'woo-orders-tracking' ) ?>
                        </label>
                    </th>
                    <td>
                        <input type="text"
                               name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[email][email_subject]"
                               id="<?php echo esc_attr( self::set( 'setting-email-subject' ) ) ?>"
                               value="<?php echo esc_attr( htmlentities( $this->settings->get_params( 'email_subject' ) ) ) ?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="<?php echo esc_attr( self::set( 'setting-email-heading' ) ) ?>">
							<?php esc_html_e( 'Email heading', 'woo-orders-tracking' ) ?>
                        </label>
                    </th>
                    <td>
                        <input type="text"

                               name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[email][email_heading]"

                               id="<?php echo esc_attr( self::set( 'setting-email-heading' ) ) ?>"

                               value="<?php echo esc_attr( htmlentities( $this->settings->get_params( 'email_heading' ) ) ) ?>">
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="<?php echo esc_attr( self::set( 'setting-email-content' ) ) ?>">
							<?php esc_html_e( 'Email content', 'woo-orders-tracking' ) ?>
                        </label>
                    </th>
                    <td>
						<?php
						wp_editor( stripslashes( $this->settings->get_params( 'email_content' ) ), 'wot-email-content', array(

							'editor_height' => 300,

							'textarea_name' => 'woo-orders-tracking-settings[email][email_content]'

						) );
						self::table_of_placeholders( array(
								'tracking_table'     => esc_html__( 'Table of order items and their respective tracking info', 'woo-orders-tracking' ),
								'order_id'           => esc_html__( 'ID of current order', 'woo-orders-tracking' ),
								'order_number'       => esc_html__( 'Order number', 'woo-orders-tracking' ),
								'billing_first_name' => esc_html__( 'Billing first name', 'woo-orders-tracking' ),
								'billing_last_name'  => esc_html__( 'Billing last name', 'woo-orders-tracking' ),
								'tracking_number'    => esc_html__( 'First found Tracking number', 'woo-orders-tracking' ),
								'tracking_url'       => esc_html__( 'Tracking url of first found Tracking number', 'woo-orders-tracking' ),
								'carrier_name'       => esc_html__( 'Carrier name of first found Tracking number', 'woo-orders-tracking' ),
								'carrier_url'        => esc_html__( 'Carrier url of first found Tracking number', 'woo-orders-tracking' ),
							)
						);
						?>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="<?php echo esc_attr( self::set( 'email_column_tracking_number' ) ) ?>">
							<?php esc_html_e( 'Tracking number column', 'woo-orders-tracking' ) ?>
                        </label>
                    </th>
                    <td>
                        <a class="vi-ui yellow button" target="_blank"
                           href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="<?php echo esc_attr( self::set( 'email_column_carrier_name' ) ) ?>">
							<?php esc_html_e( 'Carrier name column', 'woo-orders-tracking' ) ?>
                        </label>
                    </th>
                    <td>
                        <a class="vi-ui yellow button" target="_blank"
                           href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                    </td>
                </tr>
                <tr>
                    <th>
                        <label for="<?php echo esc_attr( self::set( 'email_column_tracking_url' ) ) ?>">
							<?php esc_html_e( 'Tracking url column', 'woo-orders-tracking' ) ?>
                        </label>
                    </th>
                    <td>
                        <a class="vi-ui yellow button" target="_blank"
                           href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                        <p><?php esc_html_e( '{tracking_table} contains 4 columns and you can customize 3 of them, the first column is Product title and it\'s mandatory.', 'woo-orders-tracking' ) ?></p>
                        <p><?php esc_html_e( 'You can leave column content blank to remove it from {tracking_table}.', 'woo-orders-tracking' ) ?></p>
                        <p><?php esc_html_e( 'Below placeholders can be used in both 3 columns of {tracking_table}', 'woo-orders-tracking' ) ?></p>
						<?php
						self::table_of_placeholders( array(
								'tracking_number' => esc_html__( 'Tracking number', 'woo-orders-tracking' ),
								'tracking_url'    => esc_html__( 'Tracking url', 'woo-orders-tracking' ),
								'carrier_name'    => esc_html__( 'Carrier name', 'woo-orders-tracking' ),
								'carrier_url'     => esc_html__( 'Carrier url', 'woo-orders-tracking' ),
							)
						);
						?>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <table class="form-table">
            <tbody>
            <tr>
                <td colspan="2">
                    <div class="vi-ui positive message">
                        <div class="header">
							<?php esc_html_e( 'Settings for sending emails when importing tracking numbers', 'woo-orders-tracking' ) ?>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'email_number_send' ) ) ?>">
						<?php esc_html_e( 'Number of emails sent per time', 'woo-orders-tracking' ) ?>
                    </label>
                </th>
                <td>
                    <input type="number" min="1"
                           class="<?php echo esc_attr( self::set( 'email_number_send' ) ) ?>"
                           id="<?php echo esc_attr( self::set( 'email_number_send' ) ) ?>"
                           name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[email][email_number_send]"
                           value="<?php echo esc_attr( $this->settings->get_params( 'email_number_send' ) ) ?>">
                </td>
            </tr>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'email_time_send' ) ) ?>">
						<?php esc_html_e( 'Delay between each time', 'woo-orders-tracking' ) ?>
                    </label>
                </th>
                <td>
                    <div class="vi-ui right labeled input">
                        <input type="number" min="0"
                               class="<?php echo esc_attr( self::set( 'email_time_send' ) ) ?>"
                               id="<?php echo esc_attr( self::set( 'email_time_send' ) ) ?>"
                               name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[email][email_time_send]"
                               value="<?php echo esc_attr( $this->settings->get_params( 'email_time_send' ) ) ?>">
                        <label for="amount"
                               class="vi-ui label">
                            <select name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[email][email_time_send_type]"
                                    id="<?php echo esc_attr( self::set( 'email_time_send_type' ) ) ?>"
                                    class="vi-ui dropdown <?php echo esc_attr( self::set( 'email_time_send_type' ) ) ?>">
								<?php
								$delay_time_type = array(
									'day'    => esc_html__( 'Day', 'woo-orders-tracking' ),
									'hour'   => esc_html__( 'Hour', 'woo-orders-tracking' ),
									'minute' => esc_html__( 'Minute', 'woo-orders-tracking' ),
								);
								foreach ( $delay_time_type as $key => $value ) {
									$selected = '';
									if ( $this->settings->get_params( 'email_time_send_type' ) == $key ) {
										$selected = 'selected="selected"';
									}
									?>
                                    <option value="<?php echo esc_attr( $key ) ?>" <?php echo esc_attr( $selected ) ?>><?php echo esc_html( $value ) ?></option>
									<?php
								}
								?>
                            </select>
                        </label>
                    </div>
                    <p class="description"><?php esc_html_e( 'If you import tracking numbers for 100 orders and all 100 orders have tracking numbers updated, not all 100 emails will be sent at a time.', 'woo-orders-tracking' ) ?></p>
                    <p class="description"><?php echo wp_kses_post( __( 'If you set <strong>"Number of emails sent per time"</strong> to 10 and <strong>"Delay between each time"</strong> to 10 minutes, by the time the import completes, it will send 10 first email and wait 10 minutes to send next 10 emails and continue this until all emails are sent.', 'woo-orders-tracking' ) ) ?></p>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	private function sms_settings() {
		$sms_provider  = $this->settings->get_params( 'sms_provider' );
		$sms_providers = array(
			'twilio' => 'Twilio',
			'nexmo'  => 'Nexmo',
			'plivo'  => 'Plivo'
		)
		?>
        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'sms_text_new' ) ) ?>">
						<?php esc_html_e( 'Message text when new tracking is added', 'woo-orders-tracking' ) ?>
                    </label>
                </th>
                <td>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'sms_text' ) ) ?>">
						<?php esc_html_e( 'Message text when tracking changes', 'woo-orders-tracking' ) ?>
                    </label>
                </th>
                <td>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
					<?php
					self::table_of_placeholders( array(
							'order_id'           => esc_html__( 'ID of current order', 'woo-orders-tracking' ),
							'order_number'       => esc_html__( 'Order number', 'woo-orders-tracking' ),
							'billing_first_name' => esc_html__( 'Billing first name', 'woo-orders-tracking' ),
							'billing_last_name'  => esc_html__( 'Billing last name', 'woo-orders-tracking' ),
							'tracking_number'    => esc_html__( 'The tracking number', 'woo-orders-tracking' ),
							'tracking_url'       => esc_html__( 'The tracking URL', 'woo-orders-tracking' ),
							'carrier_name'       => esc_html__( 'Carrier name', 'woo-orders-tracking' ),
							'carrier_url'        => esc_html__( 'Carrier URL', 'woo-orders-tracking' ),
						)
					);
					?>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	private function email_woo_settings() {
		?>
        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'setting-email-woo-enable' ) ) ?>">
						<?php esc_html_e( 'Include tracking in WooCommerce email', 'woo-orders-tracking' ) ?>
                    </label>
                </th>
                <td>
                    <div class="vi-ui toggle checkbox">
                        <input type="checkbox"
                               name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[email_woo][email_woo_enable]"
                               id="<?php echo esc_attr( self::set( 'setting-email-woo-enable' ) ) ?>"
                               value="1" <?php checked( $this->settings->get_params( 'email_woo_enable' ), '1' ) ?>><label
                                for="<?php echo esc_attr( self::set( 'setting-email-woo-enable' ) ) ?>"><?php esc_html_e( 'Yes', 'woo-orders-tracking' ) ?></label>
                    </div>
                    <p class="description"><?php esc_html_e( 'Tracking information will be included in selected emails below no matter you check the send email checkbox(when editing order tracking/importing tracking) or not', 'woo-orders-tracking' ) ?></p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'setting-email-woo-status' ) ) ?>">
						<?php esc_html_e( 'Order status email', 'woo-orders-tracking' ) ?>
                    </label>
                </th>
                <td>
					<?php
					$email_woo_status   = $this->settings->get_params( 'email_woo_status' );
					$email_woo_statuses = apply_filters( 'woocommerce_orders_tracking_email_woo_statuses', array(
						'cancelled_order'           => esc_html__( 'Cancelled', 'woo-orders-tracking' ),
						'customer_completed_order'  => esc_html__( 'Completed', 'woo-orders-tracking' ),
						'customer_invoice'          => esc_html__( 'Customer Invoice', 'woo-orders-tracking' ),
						'customer_note'             => esc_html__( 'Customer Note', 'woo-orders-tracking' ),
						'failed_order'              => esc_html__( 'Failed', 'woo-orders-tracking' ),
						'customer_on_hold_order'    => esc_html__( 'On Hold', 'woo-orders-tracking' ),
						'customer_processing_order' => esc_html__( 'Processing', 'woo-orders-tracking' ),
						'customer_refunded_order'   => esc_html__( 'Refunded', 'woo-orders-tracking' ),
					) );
					?>
                    <select name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[email_woo][email_woo_status][]"
                            id="<?php echo esc_attr( self::set( 'setting-email-woo-status' ) ) ?>"
                            class="vi-ui fluid dropdown <?php echo esc_attr( self::set( 'setting-email-woo-status' ) ) ?>"
                            multiple>
						<?php
						foreach ( $email_woo_statuses as $email_woo_statuses_k => $email_woo_statuses_v ) {
							?>
                            <option value="<?php echo esc_attr( $email_woo_statuses_k ) ?>" <?php echo esc_attr( in_array( $email_woo_statuses_k, $email_woo_status ) ? 'selected' : "" ); ?>><?php echo esc_html( $email_woo_statuses_v ) ?></option>
							<?php
						}
						?>
                    </select>
                    <p class="description"><?php esc_html_e( 'Select orders status email to include the tracking information.', 'woo-orders-tracking' ) ?></p>
                    <p class="description"><?php _e( '<strong>*Note:</strong> If you use an email customizer plugin to send email, this option will be skipped. Tracking info will be included in all emails that <strong>contain order table</strong>.', 'woo-orders-tracking' ) ?></p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'setting-email-woo-position' ) ) ?>">
						<?php esc_html_e( 'Tracking info position', 'woo-orders-tracking' ) ?>
                    </label>
                </th>
                <td>
					<?php
					$email_woo_position  = $this->settings->get_params( 'email_woo_position' );
					$email_woo_positions = array(
						'before_order_table' => esc_html__( 'Before order table', 'woo-orders-tracking' ),
						'after_order_item'   => esc_html__( 'After each order item - PREMIUM FEATURE', 'woo-orders-tracking' ),
						'after_order_table'  => esc_html__( 'After order table', 'woo-orders-tracking' ),
					);
					?>
                    <select name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[email_woo][email_woo_position]"
                            id="<?php echo esc_attr( self::set( 'setting-email-woo-position' ) ) ?>"
                            class="vi-ui fluid dropdown <?php echo esc_attr( self::set( 'setting-email-woo-position' ) ) ?>">
						<?php
						foreach ( $email_woo_positions as $email_woo_position_k => $email_woo_position_v ) {
							if ( $email_woo_position_k !== 'after_order_item' ) {
								?>
                                <option value="<?php echo esc_attr( $email_woo_position_k ) ?>"
                                        selected><?php echo esc_html( $email_woo_position_v ) ?></option>
								<?php
							} else {
								?>
                                <option value="<?php echo esc_attr( $email_woo_position_k ) ?>"
                                        disabled="disabled"><?php echo esc_html( $email_woo_position_v ) ?></option>
								<?php
							}
						}
						?>
                    </select>
                    <p class="description"><?php esc_html_e( 'Where in the email to place tracking information?', 'woo-orders-tracking' ) ?></p>
                </td>
            </tr>
			<?php
			$not_after_order_item_class = array( 'not_after_order_item' );
			if ( $email_woo_position === 'after_order_item' ) {
				$not_after_order_item_class[] = 'hidden';
			}
			?>
            <tr class="<?php echo esc_attr( self::set( $not_after_order_item_class ) ) ?>">
                <th>
                    <label for="<?php echo esc_attr( self::set( 'email_woo_html' ) ) ?>">
						<?php esc_html_e( 'Tracking content', 'woo-orders-tracking' ) ?>
                    </label>
                </th>
                <td>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                </td>
            </tr>
            <tr class="<?php echo esc_attr( self::set( $not_after_order_item_class ) ) ?>">
                <th>
                    <label for="<?php echo esc_attr( self::set( 'email_woo_tracking_list_html' ) ) ?>">
						<?php esc_html_e( 'Tracking list item', 'woo-orders-tracking' ) ?>
                    </label>
                </th>
                <td>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <div class="vi-ui positive small message">
                        <div>
							<?php _e( 'You can customize tracking number and carrier html which are displayed <strong>after every order item</strong> in email(if selected) or on <strong>My account/Order details</strong> page', 'woo-orders-tracking' ) ?>
                        </div>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'email_woo_tracking_number_html' ) ) ?>">
						<?php esc_html_e( 'Tracking Number', 'woo-orders-tracking' ) ?>
                    </label>
                </th>
                <td>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'email_woo_tracking_carrier_html' ) ) ?>">
						<?php esc_html_e( 'Tracking Carrier', 'woo-orders-tracking' ) ?>
                    </label>
                </th>
                <td>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
					<?php
					self::table_of_placeholders( array(
							'tracking_number' => esc_html__( 'Tracking number', 'woo-orders-tracking' ),
							'tracking_url'    => esc_html__( 'Tracking url', 'woo-orders-tracking' ),
							'carrier_name'    => esc_html__( 'Carrier name', 'woo-orders-tracking' ),
							'carrier_url'     => esc_html__( 'Carrier url', 'woo-orders-tracking' ),
						)
					);
					?>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	private function paypal_settings() {
		$available_gateways        = WC()->payment_gateways()->payment_gateways();
		$available_paypal_methods  = array();
		$supported_paypal_gateways = VI_WOO_ORDERS_TRACKING_ADMIN_PAYPAL::get_supported_paypal_gateways();
		foreach ( $available_gateways as $method_id => $method ) {
			if ( in_array( $method_id, $supported_paypal_gateways ) ) {
				$available_paypal_methods[] = $method;
			}
		}
		if ( is_array( $available_paypal_methods ) && count( $available_paypal_methods ) ) {
			?>
            <div class="vi-ui positive message">
                <div class="header"><?php esc_html_e( 'Please follow these steps to get PayPal API Credentials', 'woo-orders-tracking' ) ?></div>
                <ul class="list">
                    <li><?php printf( wp_kses_post( __( 'Go to %s and login with your PayPal account', 'woo-orders-tracking' ) ), '<strong><a href="https://developer.paypal.com/developer/applications/"
                           target="_blank">PayPal Developer</a></strong>' ); ?></li>
                    <li><?php echo wp_kses_post( __( 'Go to My Apps & Credentials and select the <strong>Live</strong> tab', 'woo-orders-tracking' ) ) ?></li>
                    <li><?php esc_html_e( 'Click on Create App button', 'woo-orders-tracking' ) ?></li>
                    <li><?php esc_html_e( 'Enter the name of your application and click Create App button', 'woo-orders-tracking' ); ?></li>
                    <li><?php esc_html_e( 'Copy your Client ID and Secret and paste them to Client Id and Client Secret fields', 'woo-orders-tracking' ); ?></li>
                </ul>
            </div>
            <table class="vi-ui celled small table wot-paypal-app-table">
                <thead>
                <tr class="wot-paypal-app-table-header">
                    <th><?php esc_html_e( 'Payment Method', 'woo-orders-tracking' ) ?></th>
                    <th><?php esc_html_e( 'Is sandbox', 'woo-orders-tracking' ) ?></th>
                    <th><?php esc_html_e( 'Client ID', 'woo-orders-tracking' ) ?></th>
                    <th><?php esc_html_e( 'Client Secret', 'woo-orders-tracking' ) ?></th>
                    <th><?php esc_html_e( 'Actions', 'woo-orders-tracking' ) ?></th>
                </tr>
                </thead>
                <tbody>
				<?php
				$paypal_method = $this->settings->get_params( 'paypal_method' );
				foreach ( $available_paypal_methods as $item ) {
					$i              = array_search( $item->id, $paypal_method );
					$live_client_id = $live_client_secret = $sandbox_client_id = $sandbox_client_secret = $sandbox_enable = $disabled = '';
					if ( is_numeric( $i ) ) {
						$live_client_id        = $this->settings->get_params( 'paypal_client_id_live' )[ $i ];
						$live_client_secret    = $this->settings->get_params( 'paypal_secret_live' )[ $i ];
						$sandbox_client_id     = $this->settings->get_params( 'paypal_client_id_sandbox' )[ $i ];
						$sandbox_client_secret = $this->settings->get_params( 'paypal_secret_sandbox' )[ $i ];
						$sandbox_enable        = $this->settings->get_params( 'paypal_sandbox_enable' )[ $i ];
					}
					?>
                    <tr class="wot-paypal-app-content">
                        <td>
                            <input type="hidden"
                                   name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[paypal][paypal_method][]"
                                   value="<?php echo esc_attr( $item->id ) ?>">
                            <input type="text" title="<?php echo esc_attr( $item->id ) ?>"
                                   value="<?php echo esc_attr( $item->method_title ) ?>" readonly>
                        </td>
                        <td>
                            <input type="hidden"
                                   name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[paypal][paypal_sandbox_enable][]"
                                   class="<?php echo esc_attr( self::set( 'setting-paypal-sandbox-enable' ) ) ?>"
                                   value="<?php echo esc_attr( $sandbox_enable ); ?>">
                            <div class="vi-ui toggle checkbox">
                                <input type="checkbox"
                                       id="<?php echo esc_attr( self::set( 'setting-paypal-sandbox-enable' ) ) ?>"
                                       value="<?php echo esc_attr( $sandbox_enable ); ?>" <?php echo esc_attr( $disabled ); ?> <?php checked( $sandbox_enable, '1' ) ?> >
                            </div>
                        </td>
                        <td>
                            <div class="field">
                                <div class="field  woo-orders-tracking-setting-paypal-live-wrap">
                                    <div class="vi-ui input"
                                         data-tooltip="<?php echo esc_attr( 'Live Client ID', 'woo-orders-tracking' ) ?>">
                                        <input type="text"
                                               name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[paypal][paypal_client_id_live][]"
                                               class="woo-orders-tracking-setting-paypal-client-id-live"
                                               value="<?php echo esc_attr( $live_client_id ) ?>">
                                    </div>
                                </div>
                                <div class="field woo-orders-tracking-setting-paypal-sandbox-wrap">
                                    <div class="vi-ui input"
                                         data-tooltip="<?php echo esc_attr( 'Sandbox Client ID', 'woo-orders-tracking' ) ?>">
                                        <input type="text"
                                               name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[paypal][paypal_client_id_sandbox][]"
                                               class="woo-orders-tracking-setting-paypal-client-id-sandbox"
                                               value="<?php echo esc_attr( $sandbox_client_id ) ?>">
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="field ">
                                <div class="field  woo-orders-tracking-setting-paypal-live-wrap">
                                    <div class="vi-ui input"
                                         data-tooltip="<?php echo esc_attr( 'Live Client Secret', 'woo-orders-tracking' ) ?>">
                                        <input type="text"
                                               name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[paypal][paypal_secret_live][]"
                                               class="woo-orders-tracking-setting-paypal-secret-live"
                                               value="<?php echo esc_attr( $live_client_secret ) ?>">
                                    </div>
                                </div>
                                <div class="field woo-orders-tracking-setting-paypal-sandbox-wrap">
                                    <div class="vi-ui input"
                                         data-tooltip="<?php echo esc_attr( 'Sandbox Client Secret', 'woo-orders-tracking' ) ?>">
                                        <input type="text"
                                               name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[paypal][paypal_secret_sandbox][]"
                                               class="woo-orders-tracking-setting-paypal-secret-sandbox"
                                               value="<?php echo esc_attr( $sandbox_client_secret ) ?>">
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="field">
                                <div class="field">
                                        <span class="wot-paypal-app-content-action-test-api wot-paypal-app-content-action-btn vi-ui button tiny">
                                    <?php esc_html_e( 'Test Connection', 'woo-orders-tracking' ) ?>
                                </span>
                                </div>
                                <div class="field">
                                    <div class="<?php echo esc_attr( self::set( 'setting-paypal-btn-check-api-text' ) ) ?>">
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
					<?php
				}
				?>
                </tbody>
            </table>
            <table class="form-table">
                <tbody>
                <tr>
                    <th>
                        <label for="<?php echo esc_attr( self::set( 'paypal_debug' ) ) ?>"><?php esc_html_e( 'Debug', 'woo-orders-tracking' ) ?></label>
                    </th>
                    <td>
                        <div class="vi-ui toggle checkbox">
                            <input type="checkbox"
                                   name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[paypal][paypal_debug]"
                                   id="<?php echo esc_attr( self::set( 'paypal_debug' ) ) ?>"
                                   value="1" <?php checked( $this->settings->get_params( 'paypal_debug' ), '1' ) ?>><label></label>
                        </div>
                        <p class="description"><?php esc_html_e( 'If enabled, raw request to PayPal API will be logged whenever there\'s an error', 'woo-orders-tracking' ) ?></p>
                    </td>
                </tr>
                </tbody>
            </table>
			<?php
		} else {
			?>
            <div class="vi-ui negative message">
                <div class="header">
					<?php esc_html_e( 'The free version only supports PayPal standard and PayPal Express checkout of WooCommerce and settings will show when one of these payment gateways is active. If you use other PayPal gateway plugins(e.g WooCommerce PayPal Payments...), please consider upgrading to premium.', 'woo-orders-tracking' ) ?>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                </div>
            </div>
			<?php
		}
	}

	private function tracking_service_settings() {
		$all_order_statuses = wc_get_order_statuses();
		?>
        <div class="vi-ui positive small message">
            <div class="header"><?php esc_html_e( 'Shortcode', 'woo-orders-tracking' ); ?></div>
            <ul class="list">
                <li><?php echo VI_WOO_ORDERS_TRACKING_DATA::wp_kses_post( sprintf( __( 'Branded tracking form shortcode %s. This only works if you use a tracking service. By default, tracking form is appended to the tracking page. If you want to change the tracking form\'s position, especially when the tracking page is built with an UX builder, this shortcode is very helpful.', 'woo-orders-tracking' ), '<span data-tooltip="Click to copy"><input type="text" class="wot-input-shortcode-field" readonly value="[vi_wot_form_track_order]"></span>' ) ) ?></li>
                <li><?php echo VI_WOO_ORDERS_TRACKING_DATA::wp_kses_post( sprintf( __( 'TrackingMore tracking form shortcode %s. You can still use this shortcode even if you do not use tracking service. More details about this at <a target="_blank" href="https://www.trackingmore.com/embed_box_float-en.html">Track Button</a>', 'woo-orders-tracking' ), '<span data-tooltip="Click to copy"><input type="text" class="wot-input-shortcode-field" readonly value="[vi_wot_tracking_more_form]"></span>' ) ) ?></li>
            </ul>
        </div>
        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'setting-service-carrier-enable' ) ) ?>">
						<?php esc_html_e( 'Enable', 'woo-orders-tracking' ); ?>
                    </label>
                </th>
                <td>
                    <div class="vi-ui toggle checkbox">
                        <input type="checkbox"
                               name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[service_carrier][service_carrier_enable]"
                               id="<?php echo esc_attr( self::set( 'setting-service-carrier-enable' ) ) ?>"
                               value="1" <?php checked( $this->settings->get_params( 'service_carrier_enable' ), '1' ) ?>><label
                                for="<?php echo esc_attr( self::set( 'setting-service-carrier-enable' ) ) ?>"><?php esc_html_e( 'Yes', 'woo-orders-tracking' ); ?></label>
                    </div>
                    <p class="description"><?php esc_html_e( 'If disabled, tracking url will be a shipping carrier\'s own tracking page.', 'woo-orders-tracking' ) ?></p>
                    <p class="description"><?php esc_html_e( 'If enabled, tracking url will be your "Tracking page" if set and will be the selected tracking service\'s tracking page otherwise.' , 'woo-orders-tracking' ) ?></p>
                </td>
            </tr>
			<?php
			$api_key_class        = array( 'tracking-service-api' );
			$service_carrier_type = $this->settings->get_params( 'service_carrier_type' );
			if ( $service_carrier_type === 'cainiao' ) {
				$api_key_class[] = 'hidden';
			}
			?>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'setting-service-carrier-type' ) ) ?>"><?php esc_html_e( 'Service', 'woo-orders-tracking' ); ?>
                    </label>
                </th>
                <td>
                    <select name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[service_carrier][service_carrier_type]"
                            id="<?php echo esc_attr( self::set( 'setting-service-carrier-type' ) ) ?>"
                            class="vi-ui dropdown <?php echo esc_attr( self::set( 'setting-service-carrier-type' ) ) ?>">
						<?php
						foreach ( VI_WOO_ORDERS_TRACKING_DATA::service_carriers_list() as $item_slug => $item_name ) {
							$disabled = '';
							if ( $item_slug !== 'trackingmore' ) {
								$item_name .= esc_html__( ' - Premium only', 'woo-orders-tracking' );
								$disabled  = 'disabled';
							}
							?>
                            <option value="<?php echo esc_attr( $item_slug ) ?>" <?php echo esc_attr( $disabled ) ?> <?php selected( $service_carrier_type, $item_slug ) ?>><?php echo esc_html( $item_name ); ?></option>
							<?php
						}
						?>
                    </select>
                </td>
            </tr>

            <tr class="<?php echo esc_attr( self::set( $api_key_class ) ) ?>">
                <th>
                    <label for="<?php echo esc_attr( self::set( 'setting-service-carrier-api-key' ) ) ?>">
						<?php
						esc_html_e( 'API key', 'woo-orders-tracking' );
						?>
                    </label>
                </th>
                <td>
                    <input type="text"
                           name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[service_carrier][service_carrier_api_key]"
                           id="<?php echo esc_attr( self::set( 'setting-service-carrier-api-key' ) ) ?>"
                           value="<?php echo esc_attr( $this->settings->get_params( 'service_carrier_api_key' ) ) ?>">
                    <p class="description <?php echo esc_attr( self::set( array(
						'setting-service-carrier-api-key-trackingmore',
						'setting-service-carrier-api-key',
						'hidden'
					) ) ) ?>">
						<?php
						echo wp_kses_post( __( 'Please enter your TrackingMore api key. If you don\'t have an account, <a href="https://my.trackingmore.com/get_apikey.php" target="_blank"><strong>click here</strong></a> to create one and generate api key', 'woo-orders-tracking' ) );
						?>
                    </p>
                </td>
            </tr>
            <tr class="<?php echo esc_attr( self::set( $api_key_class ) ) ?>">
                <th>
                    <label for="<?php echo esc_attr( self::set( 'setting-service-add-tracking-if-not-exist' ) ) ?>">
						<?php esc_html_e( 'Add tracking if not exist', 'woo-orders-tracking' ); ?>
                    </label>
                </th>
                <td>
                    <div class="vi-ui toggle checkbox">
                        <input type="checkbox"
                               name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[service_carrier][service_add_tracking_if_not_exist]"
                               id="<?php echo esc_attr( self::set( 'setting-service-add-tracking-if-not-exist' ) ) ?>"
                               value="1" <?php checked( $this->settings->get_params( 'service_add_tracking_if_not_exist' ), '1' ) ?>><label
                                for="<?php echo esc_attr( self::set( 'setting-service-add-tracking-if-not-exist' ) ) ?>"><?php esc_html_e( 'Yes', 'woo-orders-tracking' ); ?></label>
                    </div>
                    <p class="description"><?php esc_html_e( 'When customers search for a tracking number that exists in your current orders, add it to your tracking API if it does not exist in your API tracking list', 'woo-orders-tracking' ) ?></p>
                </td>
            </tr>
			<?php
			$service_tracking_page = $this->settings->get_params( 'service_tracking_page' );
			?>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'setting-service-tracking-page' ) ) ?>">
						<?php
						esc_html_e( 'Tracking page', 'woo-orders-tracking' );
						?>
                    </label>
                </th>
                <td>
                    <select name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[service_carrier][service_tracking_page]"
                            id="<?php echo esc_attr( self::set( 'setting-service-tracking-page' ) ) ?>"
                            class="search-page <?php echo esc_attr( self::set( 'setting-service-tracking-page' ) ) ?>">
						<?php
						if ( $service_tracking_page ) {
							?>
                            <option value="<?php echo esc_attr( $service_tracking_page ) ?>"
                                    selected><?php echo esc_html( get_the_title( $service_tracking_page ) ) ?></option>
							<?php
						}
						?>
                    </select>
                    <p class="description"><?php esc_html_e( 'Your branded tracking page, only used when Tracking service is enabled', 'woo-orders-tracking' ) ?></p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="setting-service-carrier-default">
						<?php esc_html_e( 'Customize Tracking page', 'woo-orders-tracking' ) ?>
                    </label>
                </th>
                <td>
                    <div>
						<?php
						if ( $service_tracking_page && $service_tracking_page_url = get_the_permalink( $service_tracking_page ) ) {
							$href = 'customize.php?url=' . urlencode( $service_tracking_page_url ) . '&autofocus[panel]=vi_wot_orders_tracking_design';
							?>
                            <a href="<?php echo esc_url( $href ) ?>"
                               target="_blank">
								<?php esc_html_e( 'Click to customize your tracking page', 'woo-orders-tracking' ) ?>
                            </a>
							<?php
						} else {
							?>
                            <label for="<?php echo esc_attr( self::set( 'setting-service-tracking-page' ) ) ?>"><?php esc_html_e( 'Please select a Tracking page and save settings to use this feature', 'woo-orders-tracking' ); ?></label>
							<?php
						}
						?>
                    </div>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'service_cache_request' ) ) ?>">
						<?php
						esc_html_e( 'Cache request', 'woo-orders-tracking' );
						?>
                    </label>
                </th>
                <td>
                    <div class="vi-ui right labeled input">
                        <input type="number" min="0" step="0.5"
                               class="<?php echo esc_attr( self::set( 'service_cache_request' ) ) ?>"
                               id="<?php echo esc_attr( self::set( 'service_cache_request' ) ) ?>"
                               name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[service_carrier][service_cache_request]"
                               value="<?php echo esc_attr( $this->settings->get_params( 'service_cache_request' ) ) ?>">
                        <label for="<?php echo esc_attr( self::set( 'service_cache_request' ) ) ?>"
                               class="vi-ui label"><?php esc_html_e( 'Hour(s)', 'woo-orders-tracking' ) ?></label>
                    </div>
                    <p class="description"><?php esc_html_e( 'When customers search for a tracking number on tracking page, the result will be saved to use for same searches for this tracking number within this cache time', 'woo-orders-tracking' ) ?></p>
                </td>
            </tr>
            <tr class="<?php echo esc_attr( self::set( $api_key_class ) ) ?>">
                <th>
                    <label for="<?php echo esc_attr( self::set( 'change_order_status' ) ) ?>"><?php esc_html_e( 'Change Order Status', 'woo-orders-tracking' ) ?></label>
                </th>
                <td>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                    <div class="description"><?php esc_html_e( 'Select order status to change to when Shipment status changes to Delivered. Leave it blank if you don\'t want to change order status', 'woo-orders-tracking' ) ?></div>
                </td>
            </tr>
			<?php do_action( 'woo_orders_tracking_settings_after_change_order_status' ); ?>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'translate_timeline' ) ) ?>">
						<?php esc_html_e( 'Translate timeline', 'woo-orders-tracking' ); ?>
                    </label>
                </th>
                <td>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                    <p class="description"><?php printf( __( 'Using <a target="_blank" href="%s">Google Cloud Translation API</a> to translate timeline to a specific language', 'woo-orders-tracking' ), 'https://cloud.google.com/translate/docs/basic/translating-text' ) ?></p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'cloud_translation_api' ) ) ?>">
						<?php esc_html_e( 'Cloud Translation API key', 'woo-orders-tracking' ) ?>
                    </label>
                </th>
                <td>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                    <p class="description"><?php printf( __( 'This functionality uses Cloud Translation - Basic API, please click <a target="_blank" href="%s">here</a> to read more about pricing', 'woo-orders-tracking' ), 'https://cloud.google.com/translate/pricing#cloud-translation---basic' ) ?></p>
                    <p class="description"><?php printf( __( 'To get API key, please read <a target="_blank" href="%s">https://cloud.google.com/docs/authentication/api-keys#creating_an_api_key</a> or watch our guiding video below', 'woo-orders-tracking' ), 'https://cloud.google.com/docs/authentication/api-keys#creating_an_api_key' ) ?></p>
                    <div class="vi-ui accordion segment">
                        <div class="title"><i
                                    class="dropdown icon"></i><?php esc_html_e( 'How to get Google Cloud Translation API key?', 'woo-orders-tracking' ) ?>
                        </div>
                        <div class="content">
                            <iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/fHLIVGcXXNM"
                                    title="YouTube video player" frameborder="0"
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                                    allowfullscreen></iframe>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="vi-ui positive small message">
            <div class="header">
				<?php esc_html_e( 'Default track info', 'woo-orders-tracking' ) ?>
            </div>
            <ul class="list">
                <li><?php esc_html_e( 'This feature is to reduce after-purchase support for new orders when tracking number is not available, or available but without any info', 'woo-orders-tracking' ) ?></li>
                <li><?php esc_html_e( 'Row with empty description will not show on the tracking page', 'woo-orders-tracking' ) ?></li>
                <li><?php esc_html_e( 'Time is relative to an order\'s created time', 'woo-orders-tracking' ) ?></li>
                <li><?php esc_html_e( 'Tracking service must be enabled and tracking page must be set', 'woo-orders-tracking' ) ?></li>
            </ul>
        </div>
        <table class="vi-ui form-table">
            <tbody>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'default_track_info_enable' ) ) ?>">
						<?php esc_html_e( 'Show default track info', 'woo-orders-tracking' ); ?>
                    </label>
                </th>
                <td>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                    <p class="description"><?php esc_html_e( 'Show below track info in tracking timeline if a real tracking number does not have any information from tracking service', 'woo-orders-tracking' ) ?></p>
                </td>
            </tr>
            </tbody>
        </table>
        <table class="vi-ui celled table <?php echo esc_attr( self::set( 'default-track-info-table' ) ) ?>">
            <thead>
            <tr>
                <th style="width: 1%"><?php esc_html_e( 'No.', 'woo-orders-tracking' ) ?></th>
                <th><?php esc_html_e( 'Show if order status', 'woo-orders-tracking' ) ?></th>
                <th><?php esc_html_e( 'Time(since order created)', 'woo-orders-tracking' ) ?></th>
                <th style="width: 1%"><?php esc_html_e( 'Status', 'woo-orders-tracking' ) ?></th>
                <th><?php esc_html_e( 'Description', 'woo-orders-tracking' ) ?></th>
                <th style="width: 1%"></th>
            </tr>
            </thead>
            <tbody>
			<?php
			$ft_messages = $this->settings->get_params( 'default_track_info_message' );
			if ( ! is_array( $ft_messages ) || ! count( $ft_messages ) ) {
				$ft_messages = array(
					array(
						'time'        => 0,
						'description' => '',
						'location'    => '',
						'status'      => 'pending',
					)
				);
			}
			$ft_message_no = 1;
			foreach ( $ft_messages as $ft_message ) {
				?>
                <tr class="<?php echo esc_attr( self::set( 'ft-row' ) ) ?>">
                    <td class="<?php echo esc_attr( self::set( 'ft-message-no-td' ) ) ?>"><span
                                class="<?php echo esc_attr( self::set( 'ft-message-no' ) ) ?>"><?php echo esc_html( $ft_message_no ); ?></span>
                    </td>
                    <td>
                        <select class="vi-ui fluid dropdown <?php echo esc_attr( self::set( 'ft-message-order-statuses' ) ) ?>"
                                multiple>
							<?php
							foreach ( $all_order_statuses as $all_option_k => $all_option_v ) {
								?>
                                <option value="<?php echo esc_attr( $all_option_k ) ?>" <?php if ( in_array( $all_option_k, $ft_message['order_statuses'] ) ) {
									echo esc_attr( 'selected' );
								} ?>><?php echo esc_html( $all_option_v ) ?></option>
								<?php
							}
							?>
                        </select>
                        <input type="hidden"
                               class="<?php echo esc_attr( self::set( 'ft-message-order-statuses-value' ) ) ?>"
                               name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[default_track_info_message][order_statuses][]"
                               value="<?php echo esc_attr( vi_wot_json_encode( $ft_message['order_statuses'] ) ) ?>">
                    </td>
                    <td class="<?php echo esc_attr( self::set( 'ft-message-time-td' ) ) ?>">
						<?php
						$day = $hour = $min = $sec = 0;
						if ( $ft_message['time'] > 0 ) {
							$time = $ft_message['time'];
							$day  = floor( $time / DAY_IN_SECONDS );
							$time = $time - $day * DAY_IN_SECONDS;
							$hour = floor( $time / HOUR_IN_SECONDS );
							$time = $time - $hour * HOUR_IN_SECONDS;
							$min  = floor( $time / MINUTE_IN_SECONDS );
						}
						?>
                        <div class="vi-ui equal width fields">
                            <div class="vi-ui right labeled input fluid field mini">
                                <input type="number" min="0"
                                       class="<?php echo esc_attr( self::set( 'ft-message-time-day' ) ) ?>"
                                       name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[default_track_info_message][day][]"
                                       value="<?php echo esc_attr( $day ) ?>">
                                <label for="amount"
                                       class="vi-ui label"><?php esc_html_e( 'Day', 'woo-orders-tracking' ) ?></label>
                            </div>
                            <div class="vi-ui right labeled input fluid field mini">
                                <input type="number" min="0" max="23"
                                       class="<?php echo esc_attr( self::set( 'ft-message-time-hour' ) ) ?>"
                                       name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[default_track_info_message][hour][]"
                                       value="<?php echo esc_attr( $hour ) ?>">
                                <label for="amount"
                                       class="vi-ui label"><?php esc_html_e( 'Hour', 'woo-orders-tracking' ) ?></label>
                            </div>
                            <div class="vi-ui right labeled input fluid field mini">
                                <input type="number" min="0" max="59"
                                       class="<?php echo esc_attr( self::set( 'ft-message-time-minute' ) ) ?>"
                                       name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[default_track_info_message][minute][]"
                                       value="<?php echo esc_attr( $min ) ?>">
                                <label for="amount"
                                       class="vi-ui label"><?php esc_html_e( 'Minute', 'woo-orders-tracking' ) ?></label>
                            </div>
                        </div>
                    </td>
                    <td>
						<?php
						$statuses = array(
							'pending' => esc_html__( 'Pending', 'woo-orders-tracking' ),
							'transit' => esc_html__( 'In Transit', 'woo-orders-tracking' ),
							'pickup'  => esc_html__( 'Pickup', 'woo-orders-tracking' ),
						);
						?>
                        <select class="vi-ui dropdown"
                                name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[default_track_info_message][status][]">
							<?php
							foreach ( $statuses as $status_k => $status_v ) {
								?>
                                <option value="<?php echo esc_attr( $status_k ) ?>" <?php selected( $status_k, $ft_message['status'] ) ?>><?php echo $status_v ?></option>
								<?php
							}
							?>
                        </select>
                    </td>
                    <td>
                        <input type="text"
                               name="<?php echo esc_attr( self::set( 'settings' ) ) ?>[default_track_info_message][description][]"
                               value="<?php echo esc_attr( $ft_message['description'] ) ?>">
                    </td>
                    <td>
                        <div>
                            <span class="vi-ui button icon negative mini <?php echo esc_attr( self::set( 'ft-button-remove' ) ) ?>"
                                  title="<?php esc_attr_e( 'Remove', 'woo-orders-tracking' ) ?>"><i
                                        class="icon trash"></i></span>
                        </div>
                    </td>
                </tr>
				<?php
				$ft_message_no ++;
			}
			?>
            </tbody>
            <tfoot>
            <tr>
                <th colspan="6">
                    <div>
                        <span class="vi-ui button icon positive mini <?php echo esc_attr( self::set( 'ft-button-add' ) ) ?>"
                              title="<?php esc_attr_e( 'Add', 'woo-orders-tracking' ) ?>"><i
                                    class="icon add"></i></span>
                    </div>
                </th>
            </tr>
            </tfoot>
        </table>
        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'default_track_info_number' ) ) ?>">
						<?php esc_html_e( 'Default tracking number', 'woo-orders-tracking' ); ?>
                    </label>
                </th>
                <td>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                    <p class="description"><?php _e( 'If an order does not have a tracking number, default tracking number will be displayed on Order received page, My account/orders and in emails that you configure in <a href="#email_woo">WooCommerce Email tab</a>.', 'woo-orders-tracking' ) ?></p>
                    <p class="description"><?php esc_html_e( 'When a customer uses a "default tracking number" to track, the default track info will be used.', 'woo-orders-tracking' ) ?></p>
                </td>
            </tr>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'default_track_info_content' ) ) ?>">
						<?php esc_html_e( 'Content', 'woo-orders-tracking' ); ?>
                    </label>
                </th>
                <td>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                    <p class="description"><?php esc_html_e( 'Message including default tracking number which will be displayed on Order received page or WooCommerce email.', 'woo-orders-tracking' ) ?></p>
					<?php
					self::table_of_placeholders(
						array(
							'tracking_number' => esc_html__( 'Default tracking number', 'woo-orders-tracking' ),
							'tracking_url'    => esc_html__( 'Tracking url', 'woo-orders-tracking' ),
						)
					);
					?>
                </td>
            </tr>
            </tbody>
        </table>
        <div class="vi-ui positive small message">
            <div class="header">
				<?php esc_html_e( 'Google reCAPTCHA for tracking form', 'woo-orders-tracking' ) ?>
            </div>
            <ul class="list">
                <li><?php echo wp_kses_post( __( 'Visit <a target="_blank" href="http://www.google.com/recaptcha/admin">Google reCAPTCHA page</a> to sign up for an API key pair with your Gmail account', 'woo-orders-tracking' ) ) ?></li>
                <li><?php esc_html_e( 'Select the reCAPTCHA version that you want to use', 'woo-orders-tracking' ) ?></li>
                <li><?php esc_html_e( 'Fill in authorized domains', 'woo-orders-tracking' ) ?></li>
                <li><?php esc_html_e( 'Accept terms of service and click Register button', 'woo-orders-tracking' ) ?></li>
                <li><?php esc_html_e( 'Copy and paste the site key and secret key into respective fields', 'woo-orders-tracking' ) ?></li>
            </ul>
        </div>
        <table class="form-table">
            <tbody>
            <tr>
                <th>
                    <label for="<?php echo esc_attr( self::set( 'tracking_form_recaptcha_enable' ) ) ?>">
						<?php esc_html_e( 'Enable reCAPTCHA', 'woo-orders-tracking' ); ?>
                    </label>
                </th>
                <td>
                    <a class="vi-ui yellow button" target="_blank"
                       href="https://1.envato.market/6ZPBE"><?php esc_html_e( 'Upgrade This Feature', 'woo-orders-tracking' ) ?></a>
                    <p class="description"><?php esc_html_e( 'Use Google reCAPTCHA for tracking form', 'woo-orders-tracking' ) ?></p>
                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	public function wotv_admin_delete_shipping_carrier() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['action_nonce'] ) || ! wp_verify_nonce( $_POST['action_nonce'], 'vi_wot_setting_action_nonce' ) ) {
			return;
		}
		$carrier_slug = isset( $_POST['carrier_slug'] ) ? sanitize_text_field( $_POST['carrier_slug'] ) : '';
		if ( $carrier_slug ) {
			$args     = $this->settings->get_params();
			$position = '';
			$carriers = VI_WOO_ORDERS_TRACKING_DATA::get_custom_carriers();
			if ( count( $carriers ) ) {
				foreach ( $carriers as $shipping_carrier ) {
					if ( $shipping_carrier["slug"] === $carrier_slug ) {
						$position = array_search( $shipping_carrier, $carriers );
						break;
					} else {
						continue;
					}
				}
				array_splice( $carriers, $position, 1 );
				$args['custom_carriers_list'] = vi_wot_json_encode( $carriers );
				update_option( 'woo_orders_tracking_settings', $args );
				wp_send_json(
					array(
						'status'   => 'success',
						'position' => $position,
					)
				);
			} else {
				wp_send_json(
					array(
						'status'  => 'error',
						'message' => 'can\'t delete carrier',
						'details' => array( 'custom_carriers_list' => $carriers )
					)
				);
			}
		} else {
			wp_send_json(
				array(
					'status'  => 'error',
					'message' => esc_html__( 'Not enough information', 'woo-orders-tracking' ),
					'details' => array( 'slug' => $carrier_slug )
				)
			);
		}
	}

	public function wotv_admin_edit_shipping_carrier() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['action_nonce'] ) || ! wp_verify_nonce( $_POST['action_nonce'], 'vi_wot_setting_action_nonce' ) ) {
			return;
		}
		$carrier_slug     = isset( $_POST['carrier_slug'] ) ? sanitize_text_field( $_POST['carrier_slug'] ) : '';
		$carrier_name     = isset( $_POST['carrier_name'] ) ? sanitize_text_field( $_POST['carrier_name'] ) : '';
		$shipping_country = isset( $_POST['shipping_country'] ) ? sanitize_text_field( $_POST['shipping_country'] ) : '';
		$tracking_url     = isset( $_POST['tracking_url'] ) ? sanitize_text_field( $_POST['tracking_url'] ) : '';
		$digital_delivery = isset( $_POST['digital_delivery'] ) ? sanitize_text_field( $_POST['digital_delivery'] ) : '';
		if ( $carrier_slug && $carrier_name && $shipping_country && $tracking_url ) {
			$args     = array();
			$carriers = VI_WOO_ORDERS_TRACKING_DATA::get_custom_carriers();
			if ( count( $carriers ) ) {
				foreach ( $carriers as $key => $shipping_carrier ) {
					if ( $shipping_carrier['slug'] === $carrier_slug ) {
						$shipping_carrier['name']             = $carrier_name;
						$shipping_carrier['country']          = $shipping_country;
						$shipping_carrier['url']              = $tracking_url;
						$shipping_carrier['digital_delivery'] = $digital_delivery;
						$carriers[ $key ]                     = $shipping_carrier;
						$args['custom_carriers_list']         = vi_wot_json_encode( $carriers );
						$args                                 = wp_parse_args( $args, $this->settings->get_params() );
						update_option( 'woo_orders_tracking_settings', $args );
						wp_send_json(
							array(
								'status'           => 'success',
								'carrier_name'     => $carrier_name,
								'shipping_country' => $shipping_country,
								'tracking_url'     => $tracking_url,
								'digital_delivery' => $digital_delivery,
							)
						);
					}
				}
			}
			$defined_carriers = VI_WOO_ORDERS_TRACKING_DATA::get_defined_carriers();
			foreach ( $defined_carriers as $key => $shipping_carrier ) {
				if ( $shipping_carrier['slug'] === $carrier_slug ) {
					$defined_carriers[ $key ]              = $shipping_carrier;
					$args['shipping_carriers_define_list'] = vi_wot_json_encode( $defined_carriers );
					$args                                  = wp_parse_args( $args, $this->settings->get_params() );
					update_option( 'woo_orders_tracking_settings', $args );
					wp_send_json(
						array(
							'status'           => 'success',
							'carrier_name'     => $carrier_name,
							'shipping_country' => $shipping_country,
							'tracking_url'     => $tracking_url,
							'digital_delivery' => $digital_delivery,
						)
					);
				}
			}
			wp_send_json(
				array(
					'status'  => 'error',
					'message' => 'can\'t edit carrier',
					'details' => array( 'custom_carriers_list' => $carriers )
				)
			);
		} else {
			wp_send_json(
				array(
					'status'  => 'error',
					'message' => esc_html__( 'Not enough information', 'woo-orders-tracking' ),
					'details' => array(
						'name'    => $carrier_name,
						'slug'    => $carrier_slug,
						'country' => $shipping_country,
						'url'     => $tracking_url
					)

				)
			);
		}
	}

	public function wotv_admin_add_new_shipping_carrier() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['action_nonce'] ) || ! wp_verify_nonce( $_POST['action_nonce'], 'vi_wot_setting_action_nonce' ) ) {
			return;
		}
		$carrier_name     = isset( $_POST['carrier_name'] ) ? sanitize_text_field( $_POST['carrier_name'] ) : '';
		$carrier_slug     = isset( $_POST['carrier_slug'] ) ? sanitize_title( $_POST['carrier_slug'] ) : '';
		$tracking_url     = isset( $_POST['tracking_url'] ) ? sanitize_text_field( $_POST['tracking_url'] ) : '';
		$shipping_country = isset( $_POST['shipping_country'] ) ? sanitize_text_field( $_POST['shipping_country'] ) : '';
		$digital_delivery = isset( $_POST['digital_delivery'] ) ? sanitize_text_field( $_POST['digital_delivery'] ) : '';
		if ( $carrier_name && $tracking_url && $shipping_country ) {
			$args                 = $this->settings->get_params();
			$custom_carriers_list = VI_WOO_ORDERS_TRACKING_DATA::get_custom_carriers();
			$carriers             = VI_WOO_ORDERS_TRACKING_DATA::get_carriers();
			if ( $carrier_slug ) {
				$exist = array_search( $carrier_slug, array_column( $carriers, 'slug' ) );
				if ( $exist !== false ) {
					wp_send_json(
						array(
							'status'  => 'error',
							'message' => esc_html__( 'Slug exists, please choose another slug or leave slug field blank', 'woo-orders-tracking' ),
							'carrier' => $carriers[ $exist ]['name'],
						)
					);
				}
			} else {
				$carrier_slug = sanitize_title( $carrier_name );
				$exist        = array_search( $carrier_slug, array_column( $carriers, 'slug' ) );
				if ( $exist !== false ) {
					$carrier_slug = 'custom_' . time();
				}
			}
			$custom_carrier               = array(
				'name'             => $carrier_name,
				'slug'             => $carrier_slug,
				'url'              => $tracking_url,
				'country'          => $shipping_country,
				'type'             => 'custom',
				'digital_delivery' => $digital_delivery,
			);
			$custom_carriers_list[]       = $custom_carrier;
			$args['custom_carriers_list'] = vi_wot_json_encode( $custom_carriers_list );
			$args['active_carriers'][]    = $carrier_slug;
			update_option( 'woo_orders_tracking_settings', $args );
			wp_send_json(
				array(
					'status'  => 'success',
					'carrier' => $custom_carrier,
				)
			);
		} else {
			wp_send_json(
				array(
					'status'  => 'error',
					'message' => esc_html__( 'Please enter all required fields', 'woo-orders-tracking' ),
					'details' => array(
						'carrier_name'     => $carrier_name,
						'tracking_url'     => $tracking_url,
						'shipping_country' => $shipping_country
					)
				)
			);
		}
	}

	public function preview_emails_button( $editor_id ) {
		global $pagenow;
		if ( $pagenow == 'admin.php' && isset( $_REQUEST['page'] ) && sanitize_text_field( $_REQUEST['page'] ) == 'woo-orders-tracking' ) {
			$editor_ids = array( 'wot-email-content' );
			if ( in_array( $editor_id, $editor_ids ) ) {
				?>
                <span class="<?php echo esc_attr( self::set( 'preview-emails-button' ) ) ?> button"
                      data-wot_language="<?php echo esc_attr( str_replace( 'wot-email-content', '', $editor_id ) ) ?>"><?php esc_html_e( 'Preview emails', 'woo-orders-tracking' ) ?></span>
				<?php
			}
		}
	}


	public function wot_preview_emails() {
		$shortcodes = array(
			'order_id'                    => 2020,
			'order_number'                => '2020',
			'order_status'                => 'processing',
			'order_date'                  => date_i18n( 'F d, Y', strtotime( 'today' ) ),
			'order_total'                 => 999,
			'order_subtotal'              => 990,
			'items_count'                 => 1,
			'payment_method'              => 'Cash on delivery',
			'shipping_method'             => 'Free shipping',
			'shipping_address'            => 'Thainguyen City',
			'formatted_shipping_address'  => 'Thainguyen City, Vietnam',
			'billing_address'             => 'Thainguyen City',
			'formatted_billing_address'   => 'Thainguyen City, Vietnam',
			'billing_country'             => 'VN',
			'billing_city'                => 'Thainguyen',
			'billing_first_name'          => 'John',
			'billing_last_name'           => 'Doe',
			'formatted_billing_full_name' => 'John Doe',
			'billing_email'               => 'support@villatheme.com',
			'shop_title'                  => get_bloginfo(),
			'home_url'                    => home_url(),
			'shop_url'                    => get_option( 'woocommerce_shop_page_id', '' ) ? get_page_link( get_option( 'woocommerce_shop_page_id' ) ) : '',
		);

		$content                      = isset( $_GET['content'] ) ? wp_kses_post( stripslashes( $_GET['content'] ) ) : '';
		$email_column_tracking_number = '<a href="{tracking_url}" target="_blank">{tracking_number}</a>';
		$email_column_carrier_name    = '{carrier_name}';
		$email_column_tracking_url    = '<a href="{tracking_url}" target="_blank">' . esc_html__( 'Track', 'woo-orders-tracking' ) . '</a>';
		$heading                      = isset( $_GET['heading'] ) ? ( stripslashes( $_GET['heading'] ) ) : '';
		$heading                      = str_replace( array(
			'{order_id}',
			'{order_number}',
			'{billing_first_name}',
			'{billing_last_name}'
		), array(
			$shortcodes['order_id'],
			$shortcodes['order_number'],
			$shortcodes['billing_first_name'],
			$shortcodes['billing_last_name']
		), $heading );
		$service_tracking_page        = $this->settings->get_params( 'service_tracking_page' );
		if ( ! $this->settings->get_params( 'service_carrier_enable' ) ) {
			$service_tracking_page = '';
		} else {
			$service_tracking_page = get_page_link( $service_tracking_page );
		}
		$imported = array(
			array(
				'order_item_name' => "Legging",
				'tracking_number' => "LTyyyyyyyyyCN",
				'carrier_name'    => "UPS",
				'carrier_url'     => "https://wwwapps.ups.com/WebTracking/track?track=yes&trackNums",
				'tracking_url'    => $service_tracking_page ? add_query_arg( array( 'tracking_id' => "LTyyyyyyyyyCN" ), $service_tracking_page ) : 'https://wwwapps.ups.com/WebTracking/track?track=yes&trackNums=LTyyyyyyyyyCN',
			),
			array(
				'order_item_name' => "T-shirt",
				'tracking_number' => "LTxxxxxxxxxCN",
				'carrier_name'    => "Yun Express",
				'carrier_url'     => "http://www.yuntrack.com/Track/",
				'tracking_url'    => $service_tracking_page ? add_query_arg( array( 'tracking_id' => "LTxxxxxxxxxCN" ), $service_tracking_page ) : 'http://www.yuntrack.com/Track/Detail/LTxxxxxxxxxCN',
			),
		);
		ob_start();
		?>
        <table class="<?php echo esc_attr( self::set( 'preview-email-table' ) ) ?>" cellspacing="0" cellpadding="6"
               border="1">
            <thead>
            <tr>
                <th><?php esc_html_e( 'Product title', 'woo-orders-tracking' ) ?></th>
				<?php
				if ( $email_column_tracking_number ) {
					?>
                    <th><?php esc_html_e( 'Tracking number', 'woo-orders-tracking' ) ?></th>
					<?php
				}
				if ( $email_column_carrier_name ) {
					?>
                    <th><?php esc_html_e( 'Carrier name', 'woo-orders-tracking' ) ?></th>
					<?php
				}
				if ( $email_column_tracking_url ) {
					?>
                    <th><?php esc_html_e( 'Tracking link', 'woo-orders-tracking' ) ?></th>
					<?php
				}
				?>
            </tr>
            </thead>
            <tbody>
			<?php
			foreach ( $imported as $item ) {
				?>
                <tr>
                    <td><?php echo $item['order_item_name']; ?></td>
                    <td><?php echo str_replace( array(
							'{tracking_number}',
							'{carrier_name}',
							'{tracking_url}',
						), array(
							$item['tracking_number'],
							$item['carrier_name'],
							$item['tracking_url'],
						), $email_column_tracking_number ); ?></td>
                    <td><?php echo str_replace( array(
							'{tracking_number}',
							'{carrier_name}',
							'{tracking_url}',
						), array(
							$item['tracking_number'],
							$item['carrier_name'],
							$item['tracking_url'],
						), $email_column_carrier_name ); ?></td>
                    <td><?php echo str_replace( array(
							'{tracking_number}',
							'{carrier_name}',
							'{tracking_url}',
						), array(
							$item['tracking_number'],
							$item['carrier_name'],
							$item['tracking_url'],
						), $email_column_tracking_url ); ?></td>
                </tr>
				<?php
			}
			?>
            </tbody>
        </table>
		<?php
		$tracking_table = ob_get_clean();
		$content        = str_replace( array(
			'{order_id}',
			'{order_number}',
			'{billing_first_name}',
			'{billing_last_name}',
			'{tracking_number}',
			'{carrier_name}',
			'{carrier_url}',
			'{tracking_url}',
			'{tracking_table}',
		), array(
			$shortcodes['order_id'],
			$shortcodes['order_number'],
			$shortcodes['billing_first_name'],
			$shortcodes['billing_last_name'],
			$imported[0]['tracking_number'],
			$imported[0]['carrier_name'],
			$imported[0]['carrier_url'],
			$imported[0]['tracking_url'],
			$tracking_table
		), $content );
		$mailer         = WC()->mailer();
		$email          = new WC_Email();
		$content        = $email->style_inline( $mailer->wrap_message( $heading, $content ) );
		wp_send_json(
			array(
				'html' => $content,
			)
		);
	}

	public function wot_test_connection_paypal() {
		$client_id = isset( $_POST['client_id'] ) ? sanitize_text_field( $_POST['client_id'] ) : '';
		$secret    = isset( $_POST['secret'] ) ? sanitize_text_field( $_POST['secret'] ) : '';
		$sandbox   = isset( $_POST['sandbox'] ) ? sanitize_text_field( $_POST['sandbox'] ) : '';
		if ( $secret && $sandbox && $client_id ) {
			if ( $sandbox === 'no' ) {
				$sandbox = false;
			}
			$check_token = VI_WOO_ORDERS_TRACKING_ADMIN_PAYPAL::get_access_token( $client_id, $secret, $sandbox, true );
			if ( $check_token['status'] === 'success' ) {
				$message = '<p class="' . esc_attr( self::set( 'success' ) ) . '">' . esc_html__( 'Successfully!', 'woo-orders-tracking' ) . '</p>';
			} else {
				$message = '<p class="' . esc_attr( self::set( 'error' ) ) . '">' . $check_token['data'] . '</p>';
			}
			wp_send_json(
				array(
					'message' => $message
				)
			);
		}
	}

	public function orders_tracking_admin_footer() {
		$countries = new WC_Countries();
		$countries = $countries->get_countries();
		?>
        <div class="preview-emails-html-container woo-orders-tracking-footer-container woo-orders-tracking-hidden">
            <div class="preview-emails-html-overlay woo-orders-tracking-overlay"></div>
            <div class="preview-emails-html woo-orders-tracking-footer-content"></div>
        </div>
        <div class="edit-shipping-carrier-html-container woo-orders-tracking-footer-container woo-orders-tracking-hidden">
            <div class="edit-shipping-carrier-html-overlay woo-orders-tracking-overlay"></div>
            <div class="edit-shipping-carrier-html-content woo-orders-tracking-footer-content">
                <div class="edit-shipping-carrier-html-content-header">
                    <h2><?php esc_html_e( 'Edit shipping carrier', 'woo-orders-tracking' ) ?></h2>
                    <i class="close icon edit-shipping-carrier-html-content-close"></i>
                </div>
                <div class="edit-shipping-carrier-html-content-body">
                    <div class="edit-shipping-carrier-html-content-body-row">
                        <div class="edit-shipping-carrier-html-content-body-carrier-name-wrap">
                            <label for="edit-shipping-carrier-html-content-body-carrier-name"><?php esc_html_e( 'Carrier Name', 'woo-orders-tracking' ) ?></label>
                            <input type="text" id="edit-shipping-carrier-html-content-body-carrier-name">
                        </div>
                        <div class="edit-shipping-carrier-html-content-body-country-wrap">
                            <label for="edit-shipping-carrier-html-content-body-country"><?php esc_html_e( 'Shipping Country', 'woo-orders-tracking' ) ?></label>
                            <select name="" id="edit-shipping-carrier-html-content-body-country"
                                    class="edit-shipping-carrier-html-content-body-country">
                                <option value=""></option>
                                <option value="Global"><?php esc_html_e( 'Global', 'woo-orders-tracking' ) ?></option>
								<?php
								foreach ( $countries as $country_code => $country_name ) {
									?>
                                    <option value="<?php echo esc_attr( $country_code ) ?>"><?php echo esc_html( $country_name ) ?></option>
									<?php
								}
								?>
                            </select>
                        </div>
                    </div>
                    <div class="edit-shipping-carrier-html-content-body-row">
                        <div>
                            <input type="checkbox"
                                   id="edit-shipping-carrier-is-digital-delivery"
                                   class="edit-shipping-carrier-is-digital-delivery">
                            <label for="edit-shipping-carrier-is-digital-delivery"><?php esc_html_e( 'Check if this is a Digital Delivery carrier. Tracking number is not required for this kind of carrier', 'woo-orders-tracking' ) ?></label>
                        </div>
                    </div>
                    <div class="edit-shipping-carrier-html-content-body-row">
                        <div class="edit-shipping-carrier-html-content-body-carrier-url-wrap">
                            <label for="edit-shipping-carrier-html-content-body-carrier-url"><?php esc_html_e( 'Carrier URL', 'woo-orders-tracking' ) ?></label>
                            <input type="text" id="edit-shipping-carrier-html-content-body-carrier-url"
                                   placeholder="http://yourcarrier.com/{tracking_number}">
                            <p class="description">
                                <strong>{tracking_number}</strong>: <?php esc_html_e( 'The placeholder for tracking number of an item', 'woo-orders-tracking' ) ?>
                            </p>
                            <p class="description">
                                <strong>{postal_code}</strong>:<?php esc_html_e( 'The placeholder for postal code of an order', 'woo-orders-tracking' ) ?>
                            </p>
                            <p class="description"><?php esc_html_e( 'eg: https://www.dhl.com/en/express/tracking.html?AWB={tracking_number}&brand=DHL', 'woo-orders-tracking' ); ?></p>
                            <p class="description wotv-error-tracking-url"><?php esc_html_e( 'The tracking url will not include tracking number because carrier URL does not include {tracking_number}', 'woo-orders-tracking' ) ?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="edit-shipping-carrier-html-content-footer">
                    <button type="button"
                            class="vi-ui button primary mini edit-shipping-carrier-html-btn-save">
						<?php esc_html_e( 'Save', 'woo-orders-tracking' ) ?>
                    </button>
                    <button type="button"
                            class="vi-ui button mini edit-shipping-carrier-html-btn-cancel">
						<?php esc_html_e( 'Cancel', 'woo-orders-tracking' ) ?>
                    </button>
                </div>
            </div>
        </div>
        <div class="add-new-shipping-carrier-html-container woo-orders-tracking-footer-container woo-orders-tracking-hidden">
            <div class="add-new-shipping-carrier-html-overlay woo-orders-tracking-overlay"></div>
            <div class="add-new-shipping-carrier-html-content woo-orders-tracking-footer-content">
                <div class="add-new-shipping-carrier-html-content-header">
                    <h2><?php esc_html_e( 'Add custom shipping carrier', 'woo-orders-tracking' ) ?></h2>
                    <i class="close icon add-new-shipping-carrier-html-content-close"></i>
                </div>
                <div class="add-new-shipping-carrier-html-content-body">
                    <div class="add-new-shipping-carrier-html-content-body-row">
                        <div class="add-new-shipping-carrier-html-content-body-carrier-name-wrap">
                            <label for="add-new-shipping-carrier-html-content-body-carrier-name"><?php esc_html_e( 'Carrier Name(required)', 'woo-orders-tracking' ) ?></label>
                            <input type="text" required id="add-new-shipping-carrier-html-content-body-carrier-name">
                        </div>
                        <div class="add-new-shipping-carrier-html-content-body-carrier-slug-wrap">
                            <label for="add-new-shipping-carrier-html-content-body-carrier-slug"><?php esc_html_e( 'Carrier slug', 'woo-orders-tracking' ) ?></label>
                            <input type="text" id="add-new-shipping-carrier-html-content-body-carrier-slug">
                        </div>
                        <div class="add-new-shipping-carrier-html-content-body-country-wrap">
                            <label for="add-new-shipping-carrier-html-content-body-country"><?php esc_html_e( 'Shipping Country', 'woo-orders-tracking' ) ?></label>
                            <select name="" id="add-new-shipping-carrier-html-content-body-country"
                                    class="add-new-shipping-carrier-html-content-body-country">
                                <option value="Global"
                                        selected><?php esc_html_e( 'Global', 'woo-orders-tracking' ) ?></option>
								<?php
								foreach ( $countries as $country_code => $country_name ) {
									?>
                                    <option value="<?php echo esc_attr( $country_code ) ?>"><?php echo esc_html( $country_name ) ?></option>
									<?php
								}
								?>
                            </select>
                        </div>
                    </div>
                    <div class="add-new-shipping-carrier-html-content-body-row">
                        <div>
                            <input type="checkbox"
                                   id="add-new-shipping-carrier-is-digital-delivery"
                                   class="add-new-shipping-carrier-is-digital-delivery">
                            <label for="add-new-shipping-carrier-is-digital-delivery"><?php esc_html_e( 'Check if this is a Digital Delivery carrier. Tracking number is not required for this kind of carrier', 'woo-orders-tracking' ) ?></label>
                        </div>
                    </div>
                    <div class="add-new-shipping-carrier-html-content-body-row">
                        <div class="add-new-shipping-carrier-html-content-body-carrier-url-wrap">
                            <label for="add-new-shipping-carrier-html-content-body-carrier-url"><?php esc_html_e( 'Tracking URL', 'woo-orders-tracking' ) ?></label>
                            <input type="text" id="add-new-shipping-carrier-html-content-body-carrier-url"
                                   placeholder="http://yourcarrier.com/{tracking_number}">
                            <p class="description">
                                <strong>{tracking_number}</strong>: <?php esc_html_e( 'The placeholder for tracking number of an item', 'woo-orders-tracking' ) ?>
                            </p>
                            <p class="description">
                                <strong>{postal_code}</strong>:<?php esc_html_e( 'The placeholder for postal code of an order', 'woo-orders-tracking' ) ?>
                            </p>
                            <p class="description"><?php esc_html_e( 'eg: https://www.dhl.com/en/express/tracking.html?AWB={tracking_number}&brand=DHL', 'woo-orders-tracking' ); ?></p>
                            <p class="description wotv-error-tracking-url"><?php esc_html_e( 'The tracking url will not include tracking number if carrier URL does not include {tracking_number}', 'woo-orders-tracking' ) ?></p>
                        </div>
                    </div>
                </div>
                <div class="add-new-shipping-carrier-html-content-footer">
                    <button type="button"
                            class="vi-ui button primary mini add-new-shipping-carrier-html-btn-save">
						<?php esc_html_e( 'Add New', 'woo-orders-tracking' ) ?>
                    </button>
                    <button type="button"
                            class="vi-ui button mini add-new-shipping-carrier-html-btn-cancel">
						<?php esc_html_e( 'Cancel', 'woo-orders-tracking' ) ?>
                    </button>
                </div>
            </div>
        </div>
		<?php
	}

	public static function admin_enqueue_semantic() {
		wp_dequeue_script( 'select-js' );//Causes select2 error, from ThemeHunk MegaMenu Plus plugin
		wp_dequeue_style( 'eopa-admin-css' );
		wp_enqueue_style( 'semantic-ui-message', VI_WOO_ORDERS_TRACKING_CSS . 'message.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_style( 'semantic-ui-input', VI_WOO_ORDERS_TRACKING_CSS . 'input.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_style( 'semantic-ui-label', VI_WOO_ORDERS_TRACKING_CSS . 'label.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_style( 'semantic-ui-accordion', VI_WOO_ORDERS_TRACKING_CSS . 'accordion.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_style( 'semantic-ui-button', VI_WOO_ORDERS_TRACKING_CSS . 'button.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_style( 'semantic-ui-checkbox', VI_WOO_ORDERS_TRACKING_CSS . 'checkbox.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_style( 'semantic-ui-dropdown', VI_WOO_ORDERS_TRACKING_CSS . 'dropdown.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_style( 'semantic-ui-form', VI_WOO_ORDERS_TRACKING_CSS . 'form.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_style( 'semantic-ui-input', VI_WOO_ORDERS_TRACKING_CSS . 'input.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_style( 'semantic-ui-popup', VI_WOO_ORDERS_TRACKING_CSS . 'popup.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_style( 'semantic-ui-icon', VI_WOO_ORDERS_TRACKING_CSS . 'icon.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_style( 'semantic-ui-menu', VI_WOO_ORDERS_TRACKING_CSS . 'menu.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_style( 'semantic-ui-segment', VI_WOO_ORDERS_TRACKING_CSS . 'segment.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_style( 'semantic-ui-tab', VI_WOO_ORDERS_TRACKING_CSS . 'tab.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_style( 'semantic-ui-table', VI_WOO_ORDERS_TRACKING_CSS . 'table.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_script( 'semantic-ui-accordion', VI_WOO_ORDERS_TRACKING_JS . 'accordion.min.js', array( 'jquery' ), VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_script( 'semantic-ui-address', VI_WOO_ORDERS_TRACKING_JS . 'address.min.js', array( 'jquery' ), VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_script( 'semantic-ui-checkbox', VI_WOO_ORDERS_TRACKING_JS . 'checkbox.min.js', array( 'jquery' ), VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_script( 'semantic-ui-dropdown', VI_WOO_ORDERS_TRACKING_JS . 'dropdown.min.js', array( 'jquery' ), VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_script( 'semantic-ui-form', VI_WOO_ORDERS_TRACKING_JS . 'form.min.js', array( 'jquery' ), VI_WOO_ORDERS_TRACKING_VERSION );
		wp_enqueue_script( 'semantic-ui-tab', VI_WOO_ORDERS_TRACKING_JS . 'tab.js', array( 'jquery' ), VI_WOO_ORDERS_TRACKING_VERSION );
	}

	public function admin_enqueue_script() {
		global $pagenow;
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		if ( $pagenow === 'admin.php' && $page === 'woo-orders-tracking' ) {
			self::admin_enqueue_semantic();
			add_action( 'admin_footer', array( $this, 'orders_tracking_admin_footer' ) );
			$this->schedule_send_emails = wp_next_scheduled( 'vi_wot_send_mails_for_import_csv_function' );
			wp_enqueue_style( 'vi-wot-admin-setting-css', VI_WOO_ORDERS_TRACKING_CSS . 'admin-setting.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
			wp_enqueue_style( 'vi-wot-admin-setting-support', VI_WOO_ORDERS_TRACKING_CSS . 'villatheme-support.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
			wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			), false, 1 );

			wp_enqueue_script( 'vi-wot-admin-setting-carrier-functions-js', VI_WOO_ORDERS_TRACKING_JS . '/carrier-functions.js', array( 'jquery' ), VI_WOO_ORDERS_TRACKING_VERSION );
			if ( ! wp_script_is( 'transition' ) ) {
				wp_enqueue_style( 'transition', VI_WOO_ORDERS_TRACKING_CSS . 'transition.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
				wp_enqueue_script( 'transition', VI_WOO_ORDERS_TRACKING_JS . 'transition.min.js', array( 'jquery' ), VI_WOO_ORDERS_TRACKING_VERSION );
			}
			if ( ! wp_script_is( 'select2' ) ) {
				wp_enqueue_style( 'select2', VI_WOO_ORDERS_TRACKING_CSS . 'select2.min.css', '', VI_WOO_ORDERS_TRACKING_VERSION );
				wp_enqueue_script( 'select2', VI_WOO_ORDERS_TRACKING_JS . 'select2.js', array( 'jquery' ), VI_WOO_ORDERS_TRACKING_VERSION );
			}
			wp_enqueue_script( 'vi-wot-admin-setting-js', VI_WOO_ORDERS_TRACKING_JS . 'admin-setting.js', array( 'jquery' ), VI_WOO_ORDERS_TRACKING_VERSION );
			$countries                = new WC_Countries();
			$this->shipping_countries = $countries->get_countries();
			wp_localize_script(
				'vi-wot-admin-setting-js',
				'vi_wot_admin_settings',
				array(
					'ajax_url'                      => admin_url( 'admin-ajax.php' ),
					'shipping_carrier_default'      => $this->settings->get_params( 'shipping_carrier_default' ),
					'carriers'                      => VI_WOO_ORDERS_TRACKING_DATA::get_carriers(),
					'active_carriers'               => $this->settings->get_params( 'active_carriers' ),
					'shipping_countries'            => $this->shipping_countries,
					'service_carriers_list'         => array_keys( VI_WOO_ORDERS_TRACKING_DATA::service_carriers_list() ),
					'select_default_carrier_text'   => esc_html__( 'Set Default', 'woo-orders-tracking' ),
					'add_new_error_empty_field'     => esc_html__( 'Please fill full information for carrier', 'woo-orders-tracking' ),
					'confirm_delete_carrier_custom' => esc_html__( 'Are you sure you want to delete this carrier?', 'woo-orders-tracking' ),
					'confirm_delete_string_replace' => esc_html__( 'Remove this item?', 'woo-orders-tracking' ),
					'i18n_copy_shortcode'           => esc_html__( 'Click to copy', 'woo-orders-tracking' ),
					'i18n_shortcode_copied'         => esc_html__( 'Copied to clipboard!', 'woo-orders-tracking' ),
					'i18n_active_carrier'           => esc_html__( 'Active', 'woo-orders-tracking' ),
					'i18n_search_carrier'           => esc_attr__( 'Search carrier name', 'woo-orders-tracking' ),
				)
			);
		}
	}

	public static function table_of_placeholders( $args ) {
		if ( count( $args ) ) {
			?>
            <table class="vi-ui celled table <?php echo esc_attr( self::set( 'table-of-placeholders' ) ) ?>">
                <thead>
                <tr>
                    <th><?php esc_html_e( 'Placeholder', 'woo-orders-tracking' ) ?></th>
                    <th><?php esc_html_e( 'Explanation', 'woo-orders-tracking' ) ?></th>
                </tr>
                </thead>
                <tbody>
				<?php
				foreach ( $args as $key => $value ) {
					?>
                    <tr>
                        <td class="<?php echo esc_attr( self::set( 'placeholder-value-container' ) ) ?>"><input
                                    class="<?php echo esc_attr( self::set( 'placeholder-value' ) ) ?>" type="text"
                                    readonly value="<?php echo esc_attr( "{{$key}}" ); ?>"><i
                                    class="vi-ui icon copy <?php echo esc_attr( self::set( 'placeholder-value-copy' ) ) ?>"
                                    title="<?php esc_attr_e( 'Copy', 'woo-orders-tracking' ) ?>"></i></td>
                        <td><?php echo esc_html( "{$value}" ); ?></td>
                    </tr>
					<?php
				}
				?>
                </tbody>
            </table>
			<?php
		}
	}

	public function search_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );
		if ( ! $keyword ) {
			$keyword = filter_input( INPUT_POST, 'keyword', FILTER_SANITIZE_STRING );
		}
		if ( empty( $keyword ) ) {
			die();
		}
		$args      = array(
			'post_status'    => 'any',
			'post_type'      => 'page',
			'posts_per_page' => 50,
			's'              => $keyword
		);
		$the_query = new WP_Query( $args );
		$items     = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$items[] = array( 'id' => get_the_ID(), 'text' => get_the_title() );
			}
		}
		wp_reset_postdata();
		wp_send_json( $items );
	}
}