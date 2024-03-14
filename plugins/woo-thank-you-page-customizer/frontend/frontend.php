<?php

/**
 * Class VI_WOO_THANK_YOU_PAGE_Frontend_Frontend
 *
 */

class VI_WOO_THANK_YOU_PAGE_Frontend_Frontend {
	protected $settings;
	protected $order_id;
	protected $key;
	protected $prefix;
	protected $text_editor;
	protected $text_editor_id;
	protected $billing_first_name;
	protected $billing_last_name;
	protected $billing_full_name;
	protected $billing_address;
	protected $shipping_address;
	protected $coupon_select;
	protected $coupon_code;
	protected $coupon_amount;
	protected $coupon_date_expires;
	protected $last_valid_date;
	protected $is_customize_preview;
	protected $characters_array;
	protected $enable;
	protected $google_map_address;
	protected $order_items_products;
	protected $order_items_products_categories;
	protected $active_components;
	protected $active_product_options;
	protected $shortcodes;
	protected $include_google_api;
	protected $payment_method_html;

	public function __construct() {
		$this->settings               = new VI_WOO_THANK_YOU_PAGE_DATA();
		$this->prefix                 = 'woocommerce-thank-you-page-';
		$this->text_editor_id         = 0;
		$this->active_components      = array();
		$this->active_product_options = array();
		$this->shortcodes             = array(
			'order_number'   => '',
			'order_status'   => '',
			'order_date'     => '',
			'order_total'    => '',
			'order_subtotal' => '',
			'items_count'    => '',
			'payment_method' => '',

			'shipping_method'            => '',
			'shipping_address'           => '',
			'formatted_shipping_address' => '',

			'billing_address'           => '',
			'formatted_billing_address' => '',
			'billing_country'           => '',
			'billing_city'              => '',

			'billing_first_name'          => '',
			'billing_last_name'           => '',
			'formatted_billing_full_name' => '',
			'billing_email'               => '',

			'shop_title' => '',
			'home_url'   => '',
			'shop_url'   => '',
		);
		$this->payment_method_html    = '';
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'wp_print_scripts', array( $this, 'wp_print_scripts' ) );
		add_filter( 'the_content', array( $this, 'the_content' ) );
		add_action( 'wp_ajax_woo_thank_you_page_layout', array( $this, 'apply_layout' ) );
		add_action( 'wp_ajax_woo_thank_you_page_select_order', array( $this, 'select_order' ) );
		add_action( 'wp_ajax_woo_thank_you_page_get_text_editor_content', array( $this, 'get_text_editor_content' ) );
		add_action( 'media_buttons', array( $this, 'shortcut_to_shortcodes' ) );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );
		add_action( 'wp_footer', array( $this, 'payment_method_html_hold' ) );
		add_action( 'wp_ajax_woocommerce_thank_you_page_customizer_send_email', array( $this, 'send_email_action' ) );
		add_action( 'wp_ajax_nopriv_woocommerce_thank_you_page_customizer_send_email', array(
			$this,
			'send_email_action'
		) );

		add_filter( 'page_template_hierarchy', array( $this, 'page_template_hierarchy' ), PHP_INT_MAX, 1 );
		add_filter( 'wc_get_template', array( $this, 'wc_get_template' ), 99, 5 );
		add_filter( 'woocommerce_valid_order_statuses_for_order_again', array(
			'WTYPC_F_FUNCTIONS',
			'woocommerce_valid_order_statuses_for_order_again'
		) );
	}

	public function page_template_hierarchy( $templates ) {
		if ( is_wc_endpoint_url( 'order-received' ) && in_array( 'order-confirmation', $templates ) ) {
			unset( $templates[ array_search( 'order-confirmation', $templates ) ] );
		}

		return $templates;
	}

	public function wc_get_template( $located, $template_name, $args, $template_path, $default_path ) {
		if ( ( $this->enable || ( ! empty( $args['order'] ) && wc_get_order( $args['order'] ) ) ) && $template_name === 'checkout/thankyou.php' ) {
			$located = VI_WOO_THANK_YOU_PAGE_TEMPLATES . 'thankyou.php';
		}

		return $located;
	}

	public function get_text_editor_content() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		check_ajax_referer( 'viwtp_send_email_ajax_nonce', 'security' );
		$shortcodes = isset( $_POST['shortcodes'] ) ? array_map( 'sanitize_text_field', $_POST['shortcodes'] ) : array();
		$shortcodes = ! empty( $shortcodes ) ? array_map( 'stripslashes', $shortcodes ) : array();
		$content    = isset( $_POST['content'] ) ? wp_kses_post( stripslashes( $_POST['content'] ) ) : array();
		if ( is_array( $shortcodes ) && count( $shortcodes ) ) {
			foreach ( $shortcodes as $key => $value ) {
				$content = str_replace( "{{$key}}", $value, $content );
			}
		}
		wp_send_json( array( 'html' => do_shortcode( $content ) ) );
		die;
	}

	public function send_email_action() {
		$shortcodes   = isset( $_POST['shortcodes'] ) ? array_map( 'sanitize_text_field', $_POST['shortcodes'] ) : '';
		$coupon_code  = isset( $_POST['coupon_code'] ) ? sanitize_text_field( $_POST['coupon_code'] ) : '';
		$order_id     = isset( $shortcodes['order_number'] ) ? $shortcodes['order_number'] : '';
		$email        = isset( $shortcodes['billing_email'] ) ? sanitize_email( $shortcodes['billing_email'] ) : '';
		$message_fail = esc_html__( 'There was problem sending email but you can always view your coupon gift by going to Account settings/Orders', 'woo-thank-you-page-customizer' );
		if ( $order_id && $email && $coupon_code ) {
			if ( get_transient( 'woocommerce_thank_you_page_customizer_send_email_' . $order_id ) ) {
				wp_send_json( array(
					'message' => esc_html__( 'Coupon code was sent to your billing email. If you did not receive any email, please go to Account settings/Orders to view your coupon gift anytime.', 'woo-thank-you-page-customizer' ),
				) );
				die;
			} else {
				$coupon = new WC_Coupon( $coupon_code );
				if ( $coupon ) {
					if ( $coupon->get_discount_type() == 'percent' ) {
						$coupon_amount = $coupon->get_amount() . '%';
					} else {
						$coupon_amount = $this->wc_price( $coupon->get_amount() );
					}
					$coupon_date_expires = $coupon->get_date_expires();
					$last_valid_date     = empty( $coupon_date_expires ) ? '' : date_i18n( 'F d, Y', strtotime( $coupon_date_expires ) - 86400 );
					$coupon_date_expires = empty( $coupon_date_expires ) ? esc_html__( 'never expires', 'woo-thank-you-page-customizer' ) : date_i18n( 'F d, Y', strtotime( $coupon_date_expires ) );
					$send                = $this->send_email( $email, $coupon_code, $coupon_date_expires, $last_valid_date, $coupon_amount, $shortcodes, true );
					if ( $send ) {
						set_transient( 'woocommerce_thank_you_page_customizer_send_email_' . $order_id, time(), 86400 );
						wp_send_json( array(
							'message' => esc_html__( 'Coupon code was sent to your billing email.', 'woo-thank-you-page-customizer' ),
						) );
						die;
					} else {
						wp_send_json( array(
							'message' => $message_fail,
						) );
						die;
					}

				}
			}

		}
		wp_send_json( array(
			'message' => $message_fail,
		) );
		die;
	}

	public function email_style( $css ) {
		$css .= '.woo-thank-you-page-customizer-coupon-input{line-height:46px;display:block;text-align: center;font-size: 24px;width: 100%;height: 46px;vertical-align: middle;margin: 0;color:' . $this->get_params( 'coupon_code_color' ) . ';background-color:' . $this->get_params( 'coupon_code_bg_color' ) . ';border-width:' . $this->get_params( 'coupon_code_border_width' ) . 'px;border-style:' . $this->get_params( 'coupon_code_border_style' ) . ';border-color:' . $this->get_params( 'coupon_code_border_color' ) . ';}';

		return $css;
	}

	public function send_email( $user_email, $coupon_code, $coupon_date_expires = '', $last_valid_date = '', $coupon_amount = '', $shortcodes = array(), $return = false ) {
		check_ajax_referer( 'viwtp_send_email_ajax_nonce', 'security' );

		$headers             = "Content-Type: text/html\r\n";
		$content             = stripslashes( $this->get_params( 'coupon_email_content' ) );
		$subject             = stripslashes( $this->get_params( 'coupon_email_subject' ) );
		$heading             = stripslashes( $this->get_params( 'coupon_email_heading' ) );
		$coupon_code_style_1 = '<div class="woo-thank-you-page-customizer-coupon-input">' . $coupon_code . '</div>';
		$content             = str_replace( '{coupon_code_style_1}', $coupon_code_style_1, $content );
		$content             = str_replace( array(
			'{coupon_code}',
			'{coupon_date_expires}',
			'{last_valid_date}',
			'{coupon_amount}',
		), array(
			$coupon_code,
			$coupon_date_expires,
			$last_valid_date,
			$coupon_amount,
		), $content );
		$subject             = str_replace( array(
			'{coupon_code}',
			'{coupon_date_expires}',
			'{last_valid_date}',
			'{coupon_amount}'
		), array( $coupon_code, $coupon_date_expires, $last_valid_date, $coupon_amount ), $subject );
		$heading             = str_replace( array(
			'{coupon_code}',
			'{coupon_date_expires}',
			'{last_valid_date}',
			'{coupon_amount}'
		), array( $coupon_code, $coupon_date_expires, $last_valid_date, $coupon_amount ), $heading );
		if ( is_array( $shortcodes ) && count( $shortcodes ) ) {
			foreach ( $shortcodes as $key => $value ) {
				$content = str_replace( '{' . $key . '}', $value, $content );
				$subject = str_replace( '{' . $key . '}', $value, $subject );
				$heading = str_replace( '{' . $key . '}', $value, $heading );
			}
		}
		add_filter( 'woocommerce_email_styles', array( $this, 'email_style' ) );
		$mailer  = WC()->mailer();
		$email   = new WC_Email();
		$content = $email->style_inline( $mailer->wrap_message( $heading, $content ) );
		$send    = $email->send( $user_email, $subject, $content, $headers, array() );
		remove_filter( 'woocommerce_email_styles', array( $this, 'email_style' ) );
		if ( $return ) {
			return $send;
		}
	}

	public function shortcut_to_shortcodes( $editor_id ) {
		if ( $editor_id == 'woocommerce-thank-you-page-wp-editor' ) {
			ob_start();
			?>
            <span class="<?php echo esc_attr( $this->set( 'available-shortcodes-shortcut' ) ) ?>"><?php esc_html_e( 'Shortcodes', 'woo-thank-you-page-customizer' ) ?></span>
			<?php
			echo ob_get_clean();
		}
	}

	public function select_order() {
		$order_id = isset( $_POST['order_id'] ) ? sanitize_text_field( $_POST['order_id'] ) : '';
		if ( $order_id ) {
			$order = wc_get_order( $order_id );
			if ( $order ) {
				wp_send_json( array(
					'url' => admin_url( 'customize.php' ) . '?url=' . urlencode( $order->get_checkout_order_received_url() ) . '&autofocus[section]=woo_thank_you_page_design_general',
				) );
			}
		}
		die;
	}

	public function apply_layout() {
		$this->is_customize_preview = true;
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		$order_id                   = isset( $_POST['order_id'] ) ? (int) sanitize_text_field( $_POST['order_id'] ) : '';
		$change_url                 = isset( $_POST['change_url'] ) ? sanitize_text_field( $_POST['change_url'] ) : '';
		$this->payment_method_html  = isset( $_POST['payment_method_html'] ) ? wp_kses_post( base64_decode( $_POST['payment_method_html'] ) ) : '';
		$this->google_map_address   = isset( $_POST['google_map_address'] ) ? wp_kses_post( base64_decode( $_POST['google_map_address'] ) ) : '';
		if ( $change_url && $order_id ) {
			$order_received_url = '';
			$order              = wc_get_order( $order_id );
			if ( $order ) {
				$data                   = new VI_WOO_THANK_YOU_PAGE_DATA();
				$option                 = $data->get_params();
				$option['select_order'] = $order->get_id();
				update_option( 'woo_thank_you_page_params', $option );
				$order_received_url = admin_url( 'customize.php' ) . '?url=' . urlencode( $order->get_checkout_order_received_url() ) . '&autofocus[section]=woo_thank_you_page_design_general';
			}
			wp_send_json( array(
				'url' => $order_received_url,
			) );
		} else {
			$order = wc_get_order( $order_id );
			if ( $order ) {
				$shortcodes                     = array(
					'order_number'   => $order_id,
					'order_status'   => $order->get_status(),
					'order_date'     => $order->get_date_created() ? $order->get_date_created()->date_i18n( 'F d, Y' ) : '',
					'order_total'    => $order->get_formatted_order_total(),
					'order_subtotal' => $order->get_subtotal_to_display(),
					'items_count'    => $order->get_item_count(),
					'payment_method' => $order->get_payment_method_title(),

					'shipping_method'            => $order->get_shipping_method(),
					'formatted_shipping_address' => $order->get_formatted_shipping_address(),

					'formatted_billing_address' => $order->get_formatted_billing_address(),
					'billing_country'           => $order->get_billing_country(),
					'billing_city'              => $order->get_billing_city(),

					'billing_first_name'          => ucwords( $order->get_billing_first_name() ),
					'billing_last_name'           => ucwords( $order->get_billing_last_name() ),
					'formatted_billing_full_name' => ucwords( $order->get_formatted_billing_full_name() ),
					'billing_email'               => $order->get_billing_email(),

					'shop_title' => get_bloginfo(),
					'home_url'   => home_url(),
					'shop_url'   => get_option( 'woocommerce_shop_page_id', '' ) ? get_page_link( get_option( 'woocommerce_shop_page_id' ) ) : '',
				);
				$billing_address                = WC()->countries->get_formatted_address( array(
					'address_1' => $order->get_billing_address_1(),
					'city'      => $order->get_billing_city(),
					'state'     => $order->get_billing_state(),
					'country'   => $order->get_billing_country(),
				), ', ' );
				$shortcodes['billing_address']  = ucwords( $billing_address );
				$shipping_address               = WC()->countries->get_formatted_address( array(
					'address_1' => $order->get_shipping_address_1(),
					'city'      => $order->get_billing_city(),
					'state'     => $order->get_billing_state(),
					'country'   => $order->get_billing_country(),
				), ', ' );
				$shortcodes['shipping_address'] = ucwords( $shipping_address );
				$country                        = new WC_Countries();
				$store_address                  = $country->get_base_address() ? $country->get_base_address() : $country->get_base_address_2();
				$store_address                  = WC()->countries->get_formatted_address( array(
					'address_1' => $store_address,
					'city'      => $country->get_base_city(),
					'state'     => $country->get_base_state(),
					'country'   => $country->get_base_country(),
				), ', ' );
				$shortcodes['store_address']    = ucwords( $store_address );
				$blocks                         = isset( $_POST['block'] ) ? json_decode( sanitize_text_field( stripslashes( $_POST['block'] ) ) ) : array();
				$text_editor                    = isset( $_POST['text_editor'] ) ? json_decode( sanitize_text_field( stripslashes( $_POST['text_editor'] ) ), true ) : array();
				$meta                           = array(
					'order_confirmation_header'               => isset( $_POST['order_confirmation_header'] ) ? sanitize_text_field( $_POST['order_confirmation_header'] ) : '',
					'order_details_header'                    => isset( $_POST['order_details_header'] ) ? sanitize_text_field( $_POST['order_details_header'] ) : '',
					'order_details_product_image'             => isset( $_POST['order_details_product_image'] ) ? sanitize_text_field( $_POST['order_details_product_image'] ) : false,
					'order_details_product_quantity_in_image' => isset( $_POST['order_details_product_quantity_in_image'] ) ? sanitize_text_field( $_POST['order_details_product_quantity_in_image'] ) : true,
					'customer_information_header'             => isset( $_POST['customer_information_header'] ) ? sanitize_text_field( $_POST['customer_information_header'] ) : '',
					'thank_you_message_header'                => isset( $_POST['thank_you_message_header'] ) ? sanitize_text_field( $_POST['thank_you_message_header'] ) : '',
					'thank_you_message_message'               => isset( $_POST['thank_you_message_message'] ) ? sanitize_text_field( $_POST['thank_you_message_message'] ) : '',
					'social_icons'                            => isset( $_POST['social_icons'] ) ? array_map( 'sanitize_text_field', array_map( 'stripslashes', $_POST['social_icons'] ) ) : array(),
				);
				wp_send_json( array(
					'blocks'     => $this->get_content( $blocks, $order, $text_editor, $meta, $shortcodes ),
					'shortcodes' => $shortcodes
				) );
			}
		}
		die;
	}

	public function wp_print_scripts() {
		if ( ! $this->is_customize_preview && ! $this->enable ) {
			return;
		}
		$google_map_api = $this->get_params( 'google_map_api' );
		if ( $google_map_api && $this->include_google_api === null ) {
			$this->include_google_api = 1;
			if ( $this->is_customize_preview ) {
				?>
                <script async defer
                        src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_map_api ?>">
                </script>
				<?php
			} else if ( in_array( 'google_map', $this->active_components ) ) {
				?>
                <script async src="https://maps.googleapis.com/maps/api/js?key=<?php echo $google_map_api ?>">
                </script>
				<?php
			}

		}
	}

	public function get_active_components( $value, $key ) {
		if ( ! in_array( $value, $this->active_components ) ) {
			$this->active_components[] = $value;
		}
	}

	public function enqueue_scripts() {
		global $post, $wp;

		if ( is_checkout() && ! empty( $wp->query_vars['order-received'] ) ) {
			$this->order_id = absint( $wp->query_vars['order-received'] );
			$this->key      = wc_clean( $_GET['key'] );
		} else {
			return;
		}
		$blocks = json_decode( $this->get_params( 'blocks' ), true );
		array_walk_recursive( $blocks, array( $this, 'get_active_components' ) );

		$order = wc_get_order( $this->order_id );
		if ( $order ) {
			$this->shortcodes['order_number']   = $this->order_id;
			$this->shortcodes['order_status']   = $order->get_status();
			$this->shortcodes['order_date']     = $order->get_date_created() ? $order->get_date_created()->date_i18n( 'F d, Y' ) : '';
			$this->shortcodes['order_total']    = $order->get_formatted_order_total();
			$this->shortcodes['order_subtotal'] = $order->get_subtotal_to_display();
			$this->shortcodes['items_count']    = $order->get_item_count();
			$this->shortcodes['payment_method'] = $order->get_payment_method_title();

			$this->shortcodes['shipping_method']            = $order->get_shipping_method();
			$this->shortcodes['shipping_address']           = $order->get_shipping_address_1();
			$this->shortcodes['formatted_shipping_address'] = $order->get_formatted_shipping_address();

			$this->shortcodes['billing_address']           = $order->get_billing_address_1();
			$this->shortcodes['formatted_billing_address'] = $order->get_formatted_billing_address();
			$this->shortcodes['billing_country']           = $order->get_billing_country();
			$this->shortcodes['billing_city']              = $order->get_billing_city();

			$this->shortcodes['billing_first_name']          = ucwords( $order->get_billing_first_name() );
			$this->shortcodes['billing_last_name']           = ucwords( $order->get_billing_last_name() );
			$this->shortcodes['formatted_billing_full_name'] = ucwords( $order->get_formatted_billing_full_name() );
			$this->shortcodes['billing_email']               = $order->get_billing_email();

			$this->shortcodes['shop_title'] = get_bloginfo();
			$this->shortcodes['home_url']   = home_url();
			$this->shortcodes['shop_url']   = get_option( 'woocommerce_shop_page_id', '' ) ? get_page_link( get_option( 'woocommerce_shop_page_id' ) ) : '';
		}
		if ( is_customize_preview() && ! empty( $_REQUEST['customize_messenger_channel'] ) ) {
			$this->is_customize_preview = true;
			wp_enqueue_style( 'woocommerce-thank-you-page-style', VI_WOO_THANK_YOU_PAGE_CSS . 'woocommerce-thank-you-page.css', array(), VI_WOO_THANK_YOU_PAGE_VERSION );
			wp_enqueue_media();
			wp_enqueue_style( 'woocommerce-thank-you-page-social-icons', VI_WOO_THANK_YOU_PAGE_CSS . 'social_icons.css', array(), VI_WOO_THANK_YOU_PAGE_VERSION );
			wp_enqueue_style( 'woocommerce-thank-you-page-icons', VI_WOO_THANK_YOU_PAGE_CSS . 'woocommerce-thank-you-page-icons.css', array(), VI_WOO_THANK_YOU_PAGE_VERSION );
			$google_map_address = $this->get_params( 'google_map_address' );
			if ( $order ) {
				$billing_address = $order->get_billing_address_1();
				if ( $order->get_billing_city() ) {
					$billing_address .= ', ' . $order->get_billing_city();
				}
				if ( $order->get_billing_state() ) {
					$billing_address .= ', ' . $order->get_billing_state();
				}
				if ( $order->get_billing_country() ) {
					$billing_address .= ', ' . $order->get_billing_country();
				}
				$shipping_address = $order->get_shipping_address_1();
				if ( $order->get_shipping_city() ) {
					$shipping_address .= ', ' . $order->get_shipping_city();
				}
				if ( $order->get_shipping_state() ) {
					$shipping_address .= ', ' . $order->get_shipping_state();
				}
				if ( $order->get_shipping_country() ) {
					$shipping_address .= ', ' . $order->get_shipping_country();
				}
				$country       = new WC_Countries();
				$store_address = $country->get_base_address() ? $country->get_base_address() : $country->get_base_address_2();
				if ( $country->get_base_city() ) {
					$store_address .= ', ' . $country->get_base_city();
				}
				if ( $country->get_base_state() ) {
					$store_address .= ', ' . $country->get_base_state();
				}
				if ( $country->get_base_country() ) {
					$store_address .= ', ' . $country->get_base_country();
				}
				$google_map_address       = str_replace( '{billing_address}', $billing_address, $google_map_address );
				$google_map_address       = str_replace( '{shipping_address}', $shipping_address, $google_map_address );
				$google_map_address       = str_replace( '{store_address}', $store_address, $google_map_address );
				$this->google_map_address = $google_map_address;
			}
		} elseif ( $this->get_params( 'enable' ) ) {
			$order_status = $this->get_params( 'order_status' );
			if ( $order && is_array( $order_status ) && count( $order_status ) && in_array( 'wc-' . $order->get_status(), $order_status ) ) {
				$this->enable = true;
				if ( $this->get_params( 'google_map_api' ) ) {
					if ( is_array( $this->active_components ) && in_array( 'google_map', $this->active_components ) ) {
						$google_map_address = $this->get_params( 'google_map_address' );
						$billing_address    = WC()->countries->get_formatted_address( array(
							'address_1' => $order->get_billing_address_1(),
							'city'      => $order->get_billing_city(),
							'state'     => $order->get_billing_state(),
							'country'   => $order->get_billing_country(),
						), ', ' );
						$billing_address    = ucwords( $billing_address );
						$shipping_address   = WC()->countries->get_formatted_address( array(
							'address_1' => $order->get_shipping_address_1(),
							'city'      => $order->get_shipping_city(),
							'state'     => $order->get_shipping_state(),
							'country'   => $order->get_shipping_country(),
						), ', ' );
						$shipping_address   = ucwords( $shipping_address );
						$country            = new WC_Countries();
						$store_address      = $country->get_base_address() ? $country->get_base_address() : $country->get_base_address_2();
						$store_address      = WC()->countries->get_formatted_address( array(
							'address_1' => $store_address,
							'city'      => $country->get_base_city(),
							'state'     => $country->get_base_state(),
							'country'   => $country->get_base_country(),
						), ', ' );
						$store_address      = ucwords( $store_address );
						$google_map_address = str_replace( '{billing_address}', $billing_address, $google_map_address );
						$google_map_address = str_replace( '{billing_address}', $billing_address, $google_map_address );
						$google_map_address = str_replace( '{shipping_address}', $shipping_address, $google_map_address );
						$google_map_address = str_replace( '{store_address}', $store_address, $google_map_address );
						wp_enqueue_script( 'woocommerce-thank-you-page-google-map-script', VI_WOO_THANK_YOU_PAGE_JS . 'woocommerce-thank-you-page-google-map.js', array( 'jquery' ), VI_WOO_THANK_YOU_PAGE_VERSION, true );
						wp_localize_script( 'woocommerce-thank-you-page-google-map-script', 'woo_thank_you_page_front_end_params', array(
							'google_map_zoom_level' => $this->get_params( 'google_map_zoom_level' ),
							'google_map_label'      => str_replace( array(
								'{address}',
								'{store_address}',
								'{shipping_address}',
								'{billing_address}'
							), array(
								$google_map_address,
								$store_address,
								$shipping_address,
								$billing_address
							), nl2br( $this->get_params( 'google_map_label' ) ) ),
							'google_map_address'    => $google_map_address,
							'google_map_marker'     => VI_WOO_THANK_YOU_PAGE_MARKERS . $this->get_params( 'google_map_marker' ) . '.png'
						) );
						$this->google_map_address = $google_map_address;
					}

				}
				$css = '';
				if ( is_array( $this->active_components ) ) {

					if ( in_array( 'order_confirmation', $this->active_components ) ) {
						/*order confirmation*/
						$css .= $this->add_inline_style( array(
							'order_confirmation_bg',
							'order_confirmation_padding',
							'order_confirmation_border_radius',
							'order_confirmation_border_width',
							'order_confirmation_border_style',
							'order_confirmation_border_color'
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container', array(
							'background-color',
							'padding',
							'border-radius',
							'border-width',
							'border-style',
							'border-color'
						), array(
							'',
							'px',
							'px',
							'px',
							'',
							''
						) );
						$css .= $this->add_inline_style( array(
							'order_confirmation_vertical_width',
							'order_confirmation_vertical_style',
							'order_confirmation_vertical_color',
							'order_confirmation_title_color',
							'order_confirmation_title_bg_color',
							'order_confirmation_title_font_size',
							'order_confirmation_title_text_align',
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-title', array(
							'border-right-width',
							'border-right-style',
							'border-right-color',
							'color',
							'background-color',
							'font-size',
							'text-align',
						), array(
							'px',
							'',
							'',
							'',
							'',
							'px',
							'',
						) );
						$css .= $this->add_inline_style( array(
							'order_confirmation_horizontal_width',
							'order_confirmation_horizontal_style',
							'order_confirmation_horizontal_color',
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-title div,.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail:not(:last-child) .woocommerce-thank-you-page-order_confirmation-value div', array(
							'border-bottom-width',
							'border-bottom-style',
							'border-bottom-color',
						), array(
							'px',
							'',
							'',
						) );
						$css .= $this->add_inline_style( array(
							'order_confirmation_header_color',
							'order_confirmation_header_bg_color',
							'order_confirmation_header_font_size',
							'order_confirmation_header_text_align',
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-header', array(
							'color',
							'background-color',
							'font-size',
							'text-align',
						), array(
							'',
							'',
							'px',
							'',
						) );

						$css .= $this->add_inline_style( array(
							'order_confirmation_value_color',
							'order_confirmation_value_bg_color',
							'order_confirmation_value_font_size',
							'order_confirmation_value_text_align',
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_confirmation__container .woocommerce-thank-you-page-order_confirmation__detail .woocommerce-thank-you-page-order_confirmation-value', array(
							'color',
							'background-color',
							'font-size',
							'text-align',
						), array(
							'',
							'',
							'px',
							'',
						) );
					}
					if ( in_array( 'order_details', $this->active_components ) ) {
						/*order details*/
						$css .= $this->add_inline_style( array(
							'order_details_color',
							'order_details_bg',
							'order_details_padding',
							'order_details_border_radius',
							'order_details_border_width',
							'order_details_border_style',
							'order_details_border_color'
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-order_details__container', array(
							'color',
							'background-color',
							'padding',
							'border-radius',
							'border-width',
							'border-style',
							'border-color'
						), array(
							'',
							'',
							'px',
							'px',
							'px',
							'',
							''
						) );
						$css .= $this->add_inline_style( array(
							'order_details_horizontal_width',
							'order_details_horizontal_style',
							'order_details_horizontal_color',
						), '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total .woocommerce-thank-you-page-order_details__detail:last-child,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_item_total,.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__order_items', array(
							'border-top-width',
							'border-top-style',
							'border-top-color',
						), array(
							'px',
							'',
							'',
						) );

						$css .= $this->add_inline_style( array(
							'order_details_header_color',
							'order_details_header_bg_color',
							'order_details_header_font_size',
							'order_details_header_text_align',
						), '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-header', array(
							'color',
							'background-color',
							'font-size',
							'text-align',
						), array(
							'',
							'',
							'px',
							'',
						) );
						$css .= $this->add_inline_style( array(
							'order_details_product_image_width',
						), '.woocommerce-thank-you-page-order_details__container .woocommerce-thank-you-page-order_details__detail .woocommerce-thank-you-page-order_details-title a.woocommerce-thank-you-page-order-item-image-wrap', array(
							'width',
						), array(
							'px',
						) );
					}
					if ( in_array( 'customer_information', $this->active_components ) ) {
						/*customer information*/
						$css .= $this->add_inline_style( array(
							'customer_information_color',
							'customer_information_bg',
							'customer_information_padding',
							'customer_information_border_radius',
							'customer_information_border_width',
							'customer_information_border_style',
							'customer_information_border_color'
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-customer_information__container', array(
							'color',
							'background-color',
							'padding',
							'border-radius',
							'border-width',
							'border-style',
							'border-color'
						), array(
							'',
							'',
							'px',
							'px',
							'px',
							'',
							''
						) );
						$css .= $this->add_inline_style( array(
							'customer_information_vertical_width',
							'customer_information_vertical_style',
							'customer_information_vertical_color',
						), '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address .woocommerce-thank-you-page-customer_information__shipping_address', array(
							'border-left-width',
							'border-left-style',
							'border-left-color',
						), array(
							'px',
							'',
							'',
						) );
						$css .= $this->add_inline_style( array(
							'customer_information_header_color',
							'customer_information_header_bg_color',
							'customer_information_header_font_size',
							'customer_information_header_text_align',
						), '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__detail .woocommerce-thank-you-page-customer_information-header', array(
							'color',
							'background-color',
							'font-size',
							'text-align',
						), array(
							'',
							'',
							'px',
							'',
						) );
						$css .= $this->add_inline_style( array(
							'customer_information_address_color',
							'customer_information_address_bg_color',
							'customer_information_address_font_size',
							'customer_information_address_text_align',
						), '.woocommerce-thank-you-page-customer_information__container .woocommerce-thank-you-page-customer_information__address', array(
							'color',
							'background-color',
							'font-size',
							'text-align',
						), array(
							'',
							'',
							'px',
							'',
						) );
					}
					if ( in_array( 'social_icons', $this->active_components ) ) {
						/*social icons*/
						$css .= $this->add_inline_style( array(
							'social_icons_header_color',
							'social_icons_header_font_size',
						), '.woocommerce-thank-you-page-social_icons__container .woocommerce-thank-you-page-social_icons__header', array(
							'color',
							'font-size',
						), array(
							'',
							'px',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_align',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials', array(
							'text-align',
						), array(
							'',
						) );

						$css .= $this->add_inline_style( array(
							'social_icons_space',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials li:not(:last-child)', array(
							'margin-right',
						), array(
							'px',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_size',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials li .wtyp-social-button span', array(
							'font-size',
						), array(
							'px',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_facebook_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-facebook-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_twitter_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-twitter-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_pinterest_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-pinterest-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_instagram_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-instagram-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_dribbble_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-dribbble-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_tumblr_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-tumblr-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_google_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-google-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_vkontakte_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-vkontakte-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_linkedin_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-linkedin-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
						$css .= $this->add_inline_style( array(
							'social_icons_youtube_color',
						), '.woocommerce-thank-you-page-social_icons__container .wtyp-list-socials .wtyp-youtube-follow .wtyp-social-button span:before', array(
							'color',
						), array(
							'',
						) );
					}
					if ( in_array( 'thank_you_message', $this->active_components ) ) {
						/*thank you message*/
						$css .= $this->add_inline_style( array(
							'thank_you_message_color',
						), '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail', array(
							'color',
						), array(
							'',
						) );

						$css .= $this->add_inline_style( array(
							'thank_you_message_padding',
							'thank_you_message_text_align',
						), '.woocommerce-thank-you-page-thank_you_message__container', array(
							'padding',
							'text-align',
						), array(
							'px',
							'',
						) );
						$css .= $this->add_inline_style( array(
							'thank_you_message_header_font_size',
						), '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail .woocommerce-thank-you-page-thank_you_message-header', array(
							'font-size',
						), array(
							'px',
						) );
						$css .= $this->add_inline_style( array(
							'thank_you_message_message_font_size',
						), '.woocommerce-thank-you-page-thank_you_message__container .woocommerce-thank-you-page-thank_you_message__detail .woocommerce-thank-you-page-thank_you_message-message', array(
							'font-size',
						), array(
							'px',
						) );
					}
					if ( in_array( 'coupon', $this->active_components ) ) {
						/*coupon*/
						$css .= $this->add_inline_style( array(
							'coupon_padding',
							'coupon_text_align',
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container', array(
							'padding',
							'text-align',
						), array(
							'px',
							'',
						) );
						$css .= $this->add_inline_style( array(
							'coupon_message_color',
							'coupon_message_font_size',
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__message', array(
							'color',
							'font-size',
						), array(
							'',
							'px',
						) );
						$css .= $this->add_inline_style( array(
							'coupon_code_color',
							'coupon_code_bg_color',
							'coupon_code_border_width',
							'coupon_code_border_style',
							'coupon_code_border_color',
						), '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-code', array(
							'color',
							'background-color',
							'border-width',
							'border-style',
							'border-color',
						), array(
							'',
							'',
							'px',
							'',
							'',
						) );
						$css .= '.woocommerce-thank-you-page-container .woocommerce-thank-you-page-coupon__container .woocommerce-thank-you-page-coupon__code .woocommerce-thank-you-page-coupon__code-wrap:before{color:' . $this->get_params( 'coupon_scissors_color' ) . ';}';
					}
					if ( in_array( 'google_map', $this->active_components ) ) {
						/*google map*/
						if ( $this->get_params( 'google_map_width' ) ) {
							$css .= $this->add_inline_style( array(
								'google_map_height',
								'google_map_width',
							), '#woocommerce-thank-you-page-google-map', array(
								'height',
								'width',
							), array(
								'px',
								'px',
							) );
						} else {
							$css .= '#woocommerce-thank-you-page-google-map{width:100%;height:' . $this->get_params( 'google_map_height' ) . 'px;}';
						}
					}
				}
				/*custom css*/
				$css .= $this->get_params( 'custom_css' );
				wp_enqueue_style( 'woocommerce-thank-you-page-style', VI_WOO_THANK_YOU_PAGE_CSS . 'woocommerce-thank-you-page.css', array(), VI_WOO_THANK_YOU_PAGE_VERSION );
				wp_add_inline_style( 'woocommerce-thank-you-page-style', $css );
				wp_enqueue_style( 'woocommerce-thank-you-page-social-icons', VI_WOO_THANK_YOU_PAGE_CSS . 'social_icons.css', array(), VI_WOO_THANK_YOU_PAGE_VERSION );
				wp_enqueue_style( 'woocommerce-thank-you-page-icons', VI_WOO_THANK_YOU_PAGE_CSS . 'woocommerce-thank-you-page-icons.css', array(), VI_WOO_THANK_YOU_PAGE_VERSION );
				wp_enqueue_script( 'woocommerce-thank-you-page-script', VI_WOO_THANK_YOU_PAGE_JS . 'woocommerce-thank-you-page.js', array( 'jquery' ), VI_WOO_THANK_YOU_PAGE_VERSION, true );
				wp_localize_script( 'woocommerce-thank-you-page-script', 'woocommerce_thank_you_page_customizer_params', array(
					'url'            => admin_url( 'admin-ajax.php' ),
					'action'         => 'woocommerce_thank_you_page_customizer_send_email',
					'shortcodes'     => $this->shortcodes,
					'copied_message' => esc_html__( 'Coupon code is copied to clipboard.', 'woo-thank-you-page-customizer' ),
					'nonce'          => wp_create_nonce( 'viwtp_send_email_ajax_nonce' ),
					'ajax_nonce'     => wp_create_nonce( 'viwtp_ajax_nonce' ),
				) );

			}
		}
	}

	private function add_inline_style( $name, $element, $style, $suffix = '', $echo = false ) {
		$return = $element . '{';
		if ( is_array( $name ) && count( $name ) ) {
			foreach ( $name as $key => $value ) {
				$return .= $style[ $key ] . ':' . $this->get_params( $name[ $key ] ) . $suffix[ $key ] . ';';
			}
		}
		$return .= '}';
		if ( $echo ) {
			echo wp_kses_post( $return );
		}

		return $return;
	}

	public function payment_method_html_hold() {
		?>
        <div id="<?php echo esc_attr( $this->set( 'payment-method-html-hold' ) ) ?>" style="display: none">
			<?php echo wp_kses_post( $this->payment_method_html ) ?>
        </div>
		<?php
	}

	public function wp_footer() {

		if ( ! $this->is_customize_preview ) {
			return;
		}
		$shortcode_titles = array(
			'coupon_code'         => esc_html__( 'Coupon code', 'woo-thank-you-page-customizer' ),
			'coupon_code_style_1' => esc_html__( 'Coupon code style 1', 'woo-thank-you-page-customizer' ),
			'coupon_date_expires' => esc_html__( 'Coupon\'s date expires', 'woo-thank-you-page-customizer' ),
			'last_valid_date'     => esc_html__( 'Coupon\'s last valid date', 'woo-thank-you-page-customizer' ),
			'coupon_amount'       => esc_html__( 'Coupon amount', 'woo-thank-you-page-customizer' ),
			'shop_title'          => esc_html__( 'Shop title', 'woo-thank-you-page-customizer' ),
			'home_url'            => esc_html__( 'Home url', 'woo-thank-you-page-customizer' ),
			'shop_url'            => esc_html__( 'Shop url', 'woo-thank-you-page-customizer' ),
			'order_number'        => esc_html__( 'Order number', 'woo-thank-you-page-customizer' ),
			'order_status'        => esc_html__( 'Order status', 'woo-thank-you-page-customizer' ),
			'order_date'          => esc_html__( 'Order date', 'woo-thank-you-page-customizer' ),
			'order_total'         => esc_html__( 'Order total', 'woo-thank-you-page-customizer' ),
			'order_subtotal'      => esc_html__( 'Order subtotal', 'woo-thank-you-page-customizer' ),
			'items_count'         => esc_html__( 'Items count', 'woo-thank-you-page-customizer' ),
			'payment_method'      => esc_html__( 'Payment method', 'woo-thank-you-page-customizer' ),

			'shipping_method'            => esc_html__( 'Shipping method', 'woo-thank-you-page-customizer' ),
			'shipping_address'           => esc_html__( 'Shipping address', 'woo-thank-you-page-customizer' ),
			'formatted_shipping_address' => esc_html__( 'Formatted shipping address', 'woo-thank-you-page-customizer' ),

			'billing_address'           => esc_html__( 'Billing address', 'woo-thank-you-page-customizer' ),
			'formatted_billing_address' => esc_html__( 'Formatted billing address', 'woo-thank-you-page-customizer' ),
			'billing_country'           => esc_html__( 'Billing country', 'woo-thank-you-page-customizer' ),
			'billing_city'              => esc_html__( 'Billing city', 'woo-thank-you-page-customizer' ),

			'billing_first_name'          => esc_html__( 'Billing first name', 'woo-thank-you-page-customizer' ),
			'billing_last_name'           => esc_html__( 'Billing last name', 'woo-thank-you-page-customizer' ),
			'formatted_billing_full_name' => esc_html__( 'Formatted billing full name', 'woo-thank-you-page-customizer' ),
			'billing_email'               => esc_html__( 'Billing email', 'woo-thank-you-page-customizer' ),
		);
		if ( is_array( $this->shortcodes ) && count( $this->shortcodes ) ) {
			?>
            <div class="<?php echo esc_attr( $this->set( array( 'available-shortcodes-container', 'hidden' ) ) ) ?>">
                <div class="<?php echo esc_attr( $this->set( 'available-shortcodes-overlay' ) ) ?>">
                </div>
                <div class="<?php echo esc_attr( $this->set( 'available-shortcodes-items' ) ) ?>">
                    <div class="<?php echo esc_attr( $this->set( 'available-shortcodes-items-header' ) ) ?>">
						<?php esc_html_e( 'Available shortcode', 'woo-thank-you-page-customizer' ) ?>
                        <span class="<?php echo esc_attr( $this->set( 'available-shortcodes-items-close' ) ) ?> wtyp_icons-cancel"></span>
                    </div>
                    <div class="<?php echo esc_attr( $this->set( 'available-shortcodes-items-content' ) ) ?>">
						<?php
						foreach ( $this->shortcodes as $key => $value ) {
							?>
                            <div class="<?php echo esc_attr( $this->set( 'available-shortcodes-item' ) ) ?>">
                                <div class="<?php echo esc_attr( $this->set( 'available-shortcodes-item-name' ) ) ?>"><?php echo isset( $shortcode_titles[ $key ] ) ? $shortcode_titles[ $key ] : esc_html__( ucwords( str_replace( '_', ' ', $key ) ), 'woo-thank-you-page-customizer' ) ?></div>
                                <div class="<?php echo esc_attr( $this->set( 'available-shortcodes-item-syntax' ) ) ?>">
                                    <input readonly value="<?php echo "{{$key}}" ?>">
                                    <span class="wtyp_icons-copy <?php echo esc_attr( $this->set( 'available-shortcodes-item-copy' ) ) ?>"></span>
                                </div>
                            </div>
							<?php
						}
						?>
                    </div>
                </div>
            </div>
			<?php
		}
		?>
        <div class="<?php echo esc_attr( $this->set( 'wp-editor-overlay' ) ) ?>"></div>
        <div class="<?php echo esc_attr( $this->set( 'wp-editor-container' ) ) ?>">
			<?php wp_editor( '', $this->set( 'wp-editor' ), array(
				'editor_height' => 300,
				'media_buttons' => true
			) ) ?>
            <div class="<?php echo esc_attr( $this->set( 'wp-editor-handle' ) ) ?>">
                <span class="<?php echo esc_attr( $this->set( 'wp-editor-save' ) ) ?>"><?php esc_html_e( 'OK', 'woo-thank-you-page-customizer' ) ?></span>
                <span class="<?php echo esc_attr( $this->set( 'wp-editor-cancel' ) ) ?>"><?php esc_html_e( 'Cancel', 'woo-thank-you-page-customizer' ) ?></span>
            </div>
        </div>
        <input type="hidden" class="<?php echo esc_attr( $this->set( 'google-map-address' ) ) ?>"
               value="<?php echo esc_attr( $this->google_map_address ) ?>">
        <input type="hidden" class="<?php echo esc_attr( $this->set( 'coupon-code' ) ) ?>"
               value="<?php echo esc_attr( $this->coupon_code ) ?>">
        <input type="hidden" class="<?php echo esc_attr( $this->set( 'coupon-amount' ) ) ?>"
               value="<?php echo esc_attr( $this->coupon_amount ) ?>">
        <input type="hidden" class="<?php echo esc_attr( $this->set( 'coupon-date-expires' ) ) ?>"
               value="<?php echo esc_attr( $this->coupon_date_expires ) ?>">
        <input type="hidden" class="<?php echo esc_attr( $this->set( 'last-valid-date' ) ) ?>"
               value="<?php echo esc_attr( $this->last_valid_date ) ?>">
        <div class="<?php echo esc_attr( $this->set( 'preview-processing-overlay' ) ) ?>"></div>
		<?php
	}

	private function get_params( $name = '' ) {
		return $this->settings->get_params( $name );
	}

	private function set( $name ) {
		if ( is_array( $name ) ) {
			return implode( ' ', array_map( array( $this, 'set' ), $name ) );

		} else {
			return esc_attr__( $this->prefix . $name );

		}
	}

	public function wc_price( $price, $args = array() ) {
		extract(
			apply_filters(
				'wc_price_args', wp_parse_args(
					$args, array(
						'ex_tax_label'       => false,
						'currency'           => get_option( 'woocommerce_currency' ),
						'decimal_separator'  => get_option( 'woocommerce_price_decimal_sep' ),
						'thousand_separator' => get_option( 'woocommerce_price_thousand_sep' ),
						'decimals'           => get_option( 'woocommerce_price_num_decimals', 2 ),
						'price_format'       => get_woocommerce_price_format(),
					)
				)
			)
		);
		$currency_pos = get_option( 'woocommerce_currency_pos' );
		$price_format = '%1$s%2$s';

		switch ( $currency_pos ) {
			case 'left' :
				$price_format = '%1$s%2$s';
				break;
			case 'right' :
				$price_format = '%2$s%1$s';
				break;
			case 'left_space' :
				$price_format = '%1$s&nbsp;%2$s';
				break;
			case 'right_space' :
				$price_format = '%2$s&nbsp;%1$s';
				break;
		}

		$negative = $price < 0;
		$price    = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * - 1 : $price ) );
		$price    = apply_filters( 'formatted_woocommerce_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

		if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $decimals > 0 ) {
			$price = wc_trim_zeros( $price );
		}

		$formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, $currency, $price );

		return $formatted_price;
	}

	public function get_content( $blocks, $order, $text_editor, $meta, $shortcodes ) {
		$order_confirmation_header               = isset( $meta['order_confirmation_header'] ) ? $meta['order_confirmation_header'] : '';
		$order_details_header                    = isset( $meta['order_details_header'] ) ? $meta['order_details_header'] : '';
		$order_details_product_image             = isset( $meta['order_details_product_image'] ) ? $meta['order_details_product_image'] : false;
		$order_details_product_quantity_in_image = isset( $meta['order_details_product_quantity_in_image'] ) ? $meta['order_details_product_quantity_in_image'] : false;
		$customer_information_header             = isset( $meta['customer_information_header'] ) ? $meta['customer_information_header'] : '';
		$thank_you_message_header                = isset( $meta['thank_you_message_header'] ) ? $meta['thank_you_message_header'] : '';
		$thank_you_message_message               = isset( $meta['thank_you_message_message'] ) ? $meta['thank_you_message_message'] : '';
		$social_icons                            = isset( $meta['social_icons'] ) ? $meta['social_icons'] : '';

		$this->text_editor = $text_editor;
		ob_start();
		if ( $order ) {
			?>
            <input type="hidden" value="<?php echo esc_attr( $order->get_id() ); ?>" class="wtyp-order-id">
			<?php

			if ( is_array( $blocks ) && count( $blocks ) ) {
				foreach ( $blocks as $row_key => $row_value ) {
					if ( is_array( $row_value ) ) {
						?>
                        <div class="<?php echo esc_attr( $this->set( array(
							'container__row',
							'container__row_' . $row_key,
							count( $row_value ) . '-column',
						) ) ) ?>">
							<?php
							if ( count( $row_value ) ) {
								foreach ( $row_value as $block_key => $block_value ) {
									?>
                                    <div class="<?php echo esc_attr( $this->set( array(
										'container__block',
										'container__block_' . $block_key,
									) ) ) ?>">
										<?php
										if ( is_array( $block_value ) && count( $block_value ) ) {

											foreach ( $block_value as $block_value_k => $block_value_v ) {
												switch ( $block_value_v ) {
													case 'order_confirmation':
														echo( $this->order_confirmation_html( $order, $order_confirmation_header ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													case 'order_details':
														echo( $this->order_details_html( $order, $order_details_header, $order_details_product_image, $order_details_product_quantity_in_image ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													case 'customer_information':
														echo( $this->customer_information_html( $order, $customer_information_header ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													case 'social_icons':
														echo( $this->social_icons_html( $social_icons ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													case 'text_editor':
														echo( $this->text_editor_html( $order ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													case 'google_map':
														echo( $this->google_map_html( $order, $this->google_map_address ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													case 'thank_you_message':
														echo( $this->thank_you_message_html( $order, $thank_you_message_header, $thank_you_message_message ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													case 'coupon':
														echo( $this->coupon_html( $order ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													case 'payment_method':
														echo( $this->payment_method_html );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													case 'order_again':
														echo( $this->order_again( $order ) );// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
														break;
													default:

												}

											}

										}
										?>
                                    </div>
									<?php
								}
							}
							?>
                        </div>
						<?php
					}
				}
			}
		}

		$content = ob_get_clean();
		if ( is_array( $shortcodes ) && count( $shortcodes ) ) {
			foreach ( $shortcodes as $shortcodes_key => $shortcodes_value ) {
				$content = str_replace( '{' . $shortcodes_key . '}', '<span class="wtypc_shortcodes_' . $shortcodes_key . '">' . $shortcodes_value . '</span>', $content );
			}
		}

		return $content;
	}


	protected function rand() {
		if ( $this->characters_array === null ) {
			$this->characters_array = array_merge( range( 0, 9 ), range( 'a', 'z' ) );
		}
		$rand = rand( 0, count( $this->characters_array ) - 1 );

		return $this->characters_array[ $rand ];
	}

	protected function create_code() {

		$code = $this->get_params( 'coupon_unique_prefix' )[0];
		for ( $i = 0; $i < 6; $i ++ ) {
			$code .= $this->rand();
		}

		return $code;

	}

	public function create_coupon( $order ) {
		$code = '';
		if ( $order ) {
			$email = $order->get_billing_email();
			switch ( $this->get_params( 'coupon_type' )[0] ) {
				case 'existing':
					$code   = $this->get_params( 'existing_coupon' )[0];
					$coupon = new WC_Coupon( $code );
					if ( $this->get_params( 'coupon_unique_email_restrictions' )[0] ) {
						$er = $coupon->get_email_restrictions();
						if ( ! in_array( $email, $er ) ) {
							$er[] = $email;
							$coupon->set_email_restrictions( $er );
							$coupon->save();
						}
					}
					$code = $coupon->get_code();
					break;
				case 'unique':
					$code = $this->create_code();
				default:
			}
		}

		return $code;
	}

	public function the_content( $content ) {
		global $post, $wp_query, $wp;
		if ( is_null( $this->is_customize_preview ) ) {
			$this->is_customize_preview = is_customize_preview();
		}
		if ( is_null( $this->order_id ) && ! empty( $wp->query_vars['order-received'] ) ) {
			$this->order_id = absint( $wp->query_vars['order-received'] );
		}
		if ( is_null( $this->key ) && isset( $_REQUEST['key'] ) ) {
			$this->key = wc_clean( $_REQUEST['key'] );
		}
		if ( ! $this->is_customize_preview ) {
			return $content;
		}
		if ( ! $this->order_id || ! $this->key ) {
			return $content;
		}
		if ( did_action( 'wp_footer' ) ) {
			return $content;
		}
		$blocks      = json_decode( $this->get_params( 'blocks' ) );
		$text_editor = json_decode( $this->get_params( 'text_editor' ), true );
		$meta        = array(
			'order_confirmation_header'               => $this->get_params( 'order_confirmation_header' ),
			'order_details_header'                    => $this->get_params( 'order_details_header' ),
			'order_details_product_image'             => $this->get_params( 'order_details_product_image' ),
			'order_details_product_quantity_in_image' => $this->get_params( 'order_details_product_quantity_in_image' ),
			'customer_information_header'             => $this->get_params( 'customer_information_header' ),
			'thank_you_message_header'                => $this->get_params( 'thank_you_message_header' ),
			'thank_you_message_message'               => $this->get_params( 'thank_you_message_message' ),
			'coupon_message'                          => $this->get_params( 'coupon_message' ),
			'social_icons'                            => array(
				'social_icons_header'           => $this->get_params( 'social_icons_header' ),
				'social_icons_target'           => $this->get_params( 'social_icons_target' ),
				'social_icons_align'            => $this->get_params( 'social_icons_align' ),
				'social_icons_facebook_url'     => $this->get_params( 'social_icons_facebook_url' ),
				'social_icons_facebook_select'  => $this->get_params( 'social_icons_facebook_select' ),
				'social_icons_twitter_url'      => $this->get_params( 'social_icons_twitter_url' ),
				'social_icons_twitter_select'   => $this->get_params( 'social_icons_twitter_select' ),
				'social_icons_pinterest_url'    => $this->get_params( 'social_icons_pinterest_url' ),
				'social_icons_pinterest_select' => $this->get_params( 'social_icons_pinterest_select' ),
				'social_icons_instagram_url'    => $this->get_params( 'social_icons_instagram_url' ),
				'social_icons_instagram_select' => $this->get_params( 'social_icons_instagram_select' ),
				'social_icons_dribbble_url'     => $this->get_params( 'social_icons_dribbble_url' ),
				'social_icons_dribbble_select'  => $this->get_params( 'social_icons_dribbble_select' ),
				'social_icons_tumblr_url'       => $this->get_params( 'social_icons_tumblr_url' ),
				'social_icons_tumblr_select'    => $this->get_params( 'social_icons_tumblr_select' ),
				'social_icons_google_url'       => $this->get_params( 'social_icons_google_url' ),
				'social_icons_google_select'    => $this->get_params( 'social_icons_google_select' ),
				'social_icons_vkontakte_url'    => $this->get_params( 'social_icons_vkontakte_url' ),
				'social_icons_vkontakte_select' => $this->get_params( 'social_icons_vkontakte_select' ),
				'social_icons_linkedin_url'     => $this->get_params( 'social_icons_linkedin_url' ),
				'social_icons_linkedin_select'  => $this->get_params( 'social_icons_linkedin_select' ),
				'social_icons_youtube_url'      => $this->get_params( 'social_icons_youtube_url' ),
				'social_icons_youtube_select'   => $this->get_params( 'social_icons_youtube_select' ),
			),
		);
		$order       = wc_get_order( $this->order_id );
		if ( $order ) {
			if ( $order->has_status( 'failed' ) ) {
				ob_start();
				?>
                <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>

                <p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
                    <a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>"
                       class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ) ?></a>
					<?php if ( is_user_logged_in() ) : ?>
                        <a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"
                           class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
					<?php endif; ?>
                </p>
				<?php
				$content = ob_get_clean();
			} else {
				ob_start();
				do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() );
				$this->payment_method_html = ob_get_clean();
				$content                   = '<div class="' . $this->set( array(
						'container',
						'customize-preview'
					) ) . '">' . $this->get_content( $blocks, $order, $text_editor, $meta, $this->shortcodes ) . '</div>';
			}
		} else {
			ob_start();
			?>
            <p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( 'Thank you. Your order has been received.', 'woocommerce' ), null ); ?></p>
			<?php
			$content = ob_get_clean();
		}

		return $content;
	}

	private function social_icons_html( $icons_text ) {
		$social_icons_target = isset( $icons_text['social_icons_target'] ) ? $icons_text['social_icons_target'] : '';
		$social_icons_header = isset( $icons_text['social_icons_header'] ) ? $icons_text['social_icons_header'] : '';
		$facebook_url        = isset( $icons_text['social_icons_facebook_url'] ) ? $icons_text['social_icons_facebook_url'] : '';
		$twitter_url         = isset( $icons_text['social_icons_twitter_url'] ) ? $icons_text['social_icons_twitter_url'] : '';
		$pinterest_url       = isset( $icons_text['social_icons_pinterest_url'] ) ? $icons_text['social_icons_pinterest_url'] : '';
		$instagram_url       = isset( $icons_text['social_icons_instagram_url'] ) ? $icons_text['social_icons_instagram_url'] : '';
		$dribbble_url        = isset( $icons_text['social_icons_dribbble_url'] ) ? $icons_text['social_icons_dribbble_url'] : '';
		$google_url          = isset( $icons_text['social_icons_google_url'] ) ? $icons_text['social_icons_google_url'] : '';
		$tumblr_url          = isset( $icons_text['social_icons_tumblr_url'] ) ? $icons_text['social_icons_tumblr_url'] : '';
		$vkontakte_url       = isset( $icons_text['social_icons_vkontakte_url'] ) ? $icons_text['social_icons_vkontakte_url'] : '';
		$linkedin_url        = isset( $icons_text['social_icons_linkedin_url'] ) ? $icons_text['social_icons_linkedin_url'] : '';
		$youtube_url         = isset( $icons_text['social_icons_youtube_url'] ) ? $icons_text['social_icons_youtube_url'] : '';

		$facebook_select  = isset( $icons_text['social_icons_facebook_select'] ) ? $icons_text['social_icons_facebook_select'] : '';
		$twitter_select   = isset( $icons_text['social_icons_twitter_select'] ) ? $icons_text['social_icons_twitter_select'] : '';
		$pinterest_select = isset( $icons_text['social_icons_pinterest_select'] ) ? $icons_text['social_icons_pinterest_select'] : '';
		$instagram_select = isset( $icons_text['social_icons_instagram_select'] ) ? $icons_text['social_icons_instagram_select'] : '';
		$dribbble_select  = isset( $icons_text['social_icons_dribbble_select'] ) ? $icons_text['social_icons_dribbble_select'] : '';
		$google_select    = isset( $icons_text['social_icons_google_select'] ) ? $icons_text['social_icons_google_select'] : '';
		$tumblr_select    = isset( $icons_text['social_icons_tumblr_select'] ) ? $icons_text['social_icons_tumblr_select'] : '';
		$vkontakte_select = isset( $icons_text['social_icons_vkontakte_select'] ) ? $icons_text['social_icons_vkontakte_select'] : '';
		$linkedin_select  = isset( $icons_text['social_icons_linkedin_select'] ) ? $icons_text['social_icons_linkedin_select'] : '';
		$youtube_select   = isset( $icons_text['social_icons_youtube_select'] ) ? $icons_text['social_icons_youtube_select'] : '';
		$html             = '<div class="' . $this->set( array(
				'social_icons__container',
				'item__container'
			) ) . '" id="' . $this->set( 'social_icons__container' ) . '">';
		$html             .= '<div class="' . $this->set( array(
				'social_icons__header',
			) ) . '"><div class="' . $this->set( 'social_icons-header' ) . '"><div>' . wp_kses_post( nl2br( $social_icons_header ) ) . '</div></div></div>';
		$html             .= '<span class="' . $this->set( 'edit-item-shortcut' ) . ' wtyp_icons-edit" data-edit_section="social_icons">' . esc_html__( 'Edit', 'woo-thank-you-page-customizer' ) . '</span><ul class="wtyp-list-socials wtyp-list-unstyled" id="wtyp-sharing-accounts">';
		ob_start();
		?>
        <a target="<?php echo esc_attr( $social_icons_target ); ?>" href="<?php echo esc_url( $facebook_url ) ?>"
           class="wtyp-social-button wtyp-facebook">
            <span class="wtyp-social-icon <?php esc_attr_e( $facebook_select ) ?>"></span></a>
		<?php $facebook_html = ob_get_clean();

		$html .= '<li style="' . ( ! $facebook_url ? 'display:none' : '' ) . '" class="wtyp-facebook-follow">' . $facebook_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo esc_attr( $social_icons_target ); ?>" href="<?php echo esc_url( $twitter_url ) ?>"
           class="wtyp-social-button wtyp-twitter">
            <span class="wtyp-social-icon <?php esc_attr_e( $twitter_select ) ?>"></span>
        </a>
		<?php
		$twitter_html = ob_get_clean();
		$html         .= '<li style="' . ( ! $twitter_url ? 'display:none' : '' ) . '" class="wtyp-twitter-follow">' . $twitter_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo esc_attr( $social_icons_target ); ?>" href="<?php echo esc_url( $pinterest_url ) ?>"
           class="wtyp-social-button wtyp-pinterest"
           data-pin-do="buttonFollow">
            <span class="wtyp-social-icon <?php esc_attr_e( $pinterest_select ) ?>"></span>
        </a>
		<?php
		$pinterest_html = ob_get_clean();
		$html           .= '<li style="' . ( ! $pinterest_url ? 'display:none' : '' ) . '" class="wtyp-pinterest-follow">' . $pinterest_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo esc_attr( $social_icons_target ); ?>" href="<?php echo esc_url( $instagram_url ) ?>"
           class="wtyp-social-button wtyp-instagram">
            <span class="wtyp-social-icon <?php esc_attr_e( $instagram_select ) ?>"></span>
        </a>
		<?php
		$instagram_html = ob_get_clean();
		$html           .= '<li style="' . ( ! $instagram_url ? 'display:none' : '' ) . '" class="wtyp-instagram-follow">' . $instagram_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo esc_attr( $social_icons_target ); ?>" href="<?php echo esc_url( $dribbble_url ) ?>"
           class="wtyp-social-button wtyp-dribbble">
            <span class="wtyp-social-icon <?php esc_attr_e( $dribbble_select ) ?>"></span>
        </a>
		<?php
		$dribbble_html = ob_get_clean();
		$html          .= '<li style="' . ( ! $dribbble_url ? 'display:none' : '' ) . '" class="wtyp-dribbble-follow">' . $dribbble_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo esc_attr( $social_icons_target ); ?>" href="<?php echo esc_url( $tumblr_url ) ?>"
           class="wtyp-social-button wtyp-tumblr">
            <span class="wtyp-social-icon <?php esc_attr_e( $tumblr_select ) ?>"></span>
        </a>
		<?php
		$tumblr_html = ob_get_clean();
		$html        .= '<li style="' . ( ! $tumblr_url ? 'display:none' : '' ) . '" class="wtyp-tumblr-follow">' . $tumblr_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo esc_attr( $social_icons_target ); ?>" href="<?php echo esc_url( $google_url ) ?>"
           class="wtyp-social-button wtyp-google-plus">
            <span class="wtyp-social-icon <?php esc_attr_e( $google_select ) ?>"></span>
        </a>
		<?php
		$google_html = ob_get_clean();
		$html        .= '<li style="' . ( ! $google_url ? 'display:none' : '' ) . '" class="wtyp-google-follow">' . $google_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo esc_attr( $social_icons_target ); ?>" href="<?php echo esc_url( $vkontakte_url ) ?>"
           class="wtyp-social-button wtyp-vk">
            <span class="wtyp-social-icon <?php esc_attr_e( $vkontakte_select ) ?>"></span>
        </a>
		<?php
		$vkontakte_html = ob_get_clean();
		$html           .= '<li style="' . ( ! $vkontakte_url ? 'display:none' : '' ) . '" class="wtyp-vkontakte-follow">' . $vkontakte_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo esc_attr( $social_icons_target ); ?>" href="<?php echo esc_url( $linkedin_url ) ?>"
           class="wtyp-social-button wtyp-linkedin">
            <span class="wtyp-social-icon <?php esc_attr_e( $linkedin_select ) ?>"></span>
        </a>
		<?php
		$linkedin_html = ob_get_clean();
		$html          .= '<li style="' . ( ! $linkedin_url ? 'display:none' : '' ) . '" class="wtyp-linkedin-follow">' . $linkedin_html . '</li>';

		ob_start(); ?>
        <a target="<?php echo esc_attr( $social_icons_target ); ?>" href="<?php echo esc_url( $youtube_url ) ?>"
           class="wtyp-social-button wtyp-youtube">
            <span class="wtyp-social-icon <?php esc_attr_e( $youtube_select ) ?>"></span>
        </a>
		<?php
		$youtube_html = ob_get_clean();
		$html         .= '<li style="' . ( ! $youtube_url ? 'display:none' : '' ) . '" class="wtyp-youtube-follow">' . $youtube_html . '</li>';

		$html = apply_filters( 'wtyp_after_socials_html', $html );
		$html .= '</ul></div>';

		return $html;
	}

	private function thank_you_message_html( $order, $thank_you_message_header, $thank_you_message_message ) {
		ob_start();
		?>
        <div class="<?php echo esc_attr( $this->set( array( 'thank_you_message__container', 'item__container' ) ) ) ?>"
             id="<?php echo esc_attr( $this->set( 'thank_you_message__container' ) ) ?>">

                <span class="<?php echo esc_attr( $this->set( 'edit-item-shortcut' ) ) ?> wtyp_icons-edit"
                      data-edit_section="thank_you_message"><?php echo esc_html__( 'Edit', 'woo-thank-you-page-customizer' ) ?></span>

            <div class="<?php echo esc_attr( $this->set( 'check' ) ) ?> wtyp_icons-accept">
                <div class="<?php echo esc_attr( $this->set( array(
					'thank_you_message__header',
					'thank_you_message__detail',
				) ) ) ?>">
                    <div class="<?php echo esc_attr( $this->set( 'thank_you_message-header' ) ) ?>">
                        <div><?php echo wp_kses_post( nl2br( $thank_you_message_header ) ); ?></div>
                    </div>
                </div>
                <div class="<?php echo esc_attr( $this->set( array(
					'thank_you_message__message',
					'thank_you_message__detail',
				) ) ) ?>">
                    <div class="<?php echo esc_attr( $this->set( 'thank_you_message-message' ) ) ?>">
                        <div><?php echo wp_kses_post( nl2br( $thank_you_message_message ) ); ?></div>
                    </div>
                </div>
            </div>

        </div>
		<?php
		return ob_get_clean();
	}

	private function order_again( $order ) {
		ob_start();
		if ( function_exists( 'woocommerce_order_again_button' ) ) {
			woocommerce_order_again_button( $order );
		}

		return ob_get_clean();
	}

	private function coupon_html( $order ) {
		$coupon_message    = $this->get_params( 'coupon_message' );
		$give_coupon       = true;
		$coupon_code       = $this->create_coupon( $order );
		$coupon_code       = strtoupper( $coupon_code );
		$this->coupon_code = $coupon_code;
		if ( $coupon_code ) {
			if ( $this->get_params( 'coupon_type' )[0] == 'existing' ) {
				$coupon = new WC_Coupon( $coupon_code );
				if ( $coupon ) {
					if ( $coupon->get_discount_type() == 'percent' ) {
						$this->coupon_amount = $coupon->get_amount() . '%';
					} else {
						$this->coupon_amount = $this->wc_price( $coupon->get_amount() );
					}
					$coupon_date_expires       = $coupon->get_date_expires();
					$this->last_valid_date     = empty( $coupon_date_expires ) ? '' : date_i18n( 'F d, Y', strtotime( $coupon_date_expires ) - 86400 );
					$this->coupon_date_expires = empty( $coupon_date_expires ) ? esc_html__( 'never expires', 'woo-thank-you-page-customizer' ) : date_i18n( 'F d, Y', strtotime( $coupon_date_expires ) );
					$coupon_message            = str_replace( '{coupon_code}', $this->coupon_code, $coupon_message );
					$coupon_message            = str_replace( '{coupon_amount}', $this->coupon_amount, $coupon_message );
					$coupon_message            = str_replace( '{last_valid_date}', $this->last_valid_date, $coupon_message );
					$coupon_message            = str_replace( '{coupon_date_expires}', $this->coupon_date_expires, $coupon_message );
				}
			} else {
				if ( $this->get_params( 'coupon_unique_discount_type' )[0] == 'percent' ) {
					$this->coupon_amount = $this->get_params( 'coupon_unique_amount' )[0] . '%';
				} else {
					$this->coupon_amount = $this->wc_price( $this->get_params( 'coupon_unique_amount' )[0] );
				}
				$coupon_date_expires       = $this->get_params( 'coupon_unique_date_expires' )[0];
				$this->last_valid_date     = empty( $coupon_date_expires ) ? '' : date_i18n( 'F d, Y', strtotime( date_i18n( 'F d, Y' ) ) + $coupon_date_expires * 86400 );
				$this->coupon_date_expires = empty( $coupon_date_expires ) ? esc_html__( 'never expires', 'woo-thank-you-page-customizer' ) : date_i18n( 'F d, Y', strtotime( date_i18n( 'F d, Y' ) ) + ( $coupon_date_expires + 1 ) * 86400 );
				$coupon_message            = str_replace( '{coupon_code}', $this->coupon_code, $coupon_message );
				$coupon_message            = str_replace( '{coupon_amount}', $this->coupon_amount, $coupon_message );
				$coupon_message            = str_replace( '{last_valid_date}', $this->last_valid_date, $coupon_message );
				$coupon_message            = str_replace( '{coupon_date_expires}', $this->coupon_date_expires, $coupon_message );
			}
		}


		if ( ! $give_coupon ) {
			return '';
		}
		ob_start();
		?>
        <div class="<?php echo esc_attr( $this->set( array( 'coupon__container', 'item__container' ) ) ) ?>"
             id="<?php echo esc_attr( $this->set( 'coupon__container' ) ) ?>">

                <span class="<?php echo esc_attr( $this->set( 'edit-item-shortcut' ) ) ?> wtyp_icons-edit"
                      data-edit_section="coupon"><?php echo esc_html__( 'Edit', 'woo-thank-you-page-customizer' ) ?></span>
            <div class="<?php echo esc_attr( $this->set( array(
				'coupon__message',
				'coupon__detail',
			) ) ) ?>">
                <div class="<?php echo esc_attr( $this->set( 'coupon-message' ) ) ?>">
                    <div><?php echo( nl2br( $coupon_message ) ); ?></div>
                </div>
            </div>
            <div class="<?php echo esc_attr( $this->set( array(
				'coupon__code',
				'coupon__detail',
			) ) ) ?>">
                <div class="<?php echo esc_attr( $this->set( 'coupon-code' ) ) ?>">
                    <span class="<?php echo esc_attr( $this->set( 'coupon__code-wrap' ) ); ?> wtyp_icons-scissors">
                        <input type="text" readonly class="<?php echo esc_attr( $this->set( 'coupon__code-code' ) ); ?>"
                               value="<?php echo esc_attr( $coupon_code ); ?>">
                            <span class="<?php echo esc_attr( $this->set( 'coupon__code-email' ) ); ?> <?php echo $this->get_params( 'coupon_email_enable' ) ? '' : 'woocommerce-thank-you-page-hidden' ?>">
                                <span class="<?php echo esc_attr( $this->set( 'coupon__code-mail-me' ) ); ?> wtyp_icons-opened-email-envelope"
                                      title="<?php echo esc_html__( 'Email me', 'woo-thank-you-page-customizer' ) ?>"></span>
                                <span class="<?php echo esc_attr( $this->set( 'coupon__code-copy-code' ) ); ?> wtyp_icons-copy"
                                      title="<?php echo esc_html__( 'Copy code', 'woo-thank-you-page-customizer' ) ?>"></span>
                            </span>

                    </span>
                </div>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}

	private function google_map_html( $order, $google_map_address ) {
		ob_start();
		?>
        <div class="<?php echo esc_attr( $this->set( array( 'google_map__container', 'item__container' ) ) ) ?>"
             id="<?php echo esc_attr( $this->set( 'google_map__container' ) ) ?>">
                    <span class="<?php echo esc_attr( $this->set( 'edit-item-shortcut' ) ) ?> wtyp_icons-edit"
                          data-edit_section="google_map"><?php echo esc_html__( 'Edit', 'woo-thank-you-page-customizer' ) ?></span>
            <div id="<?php echo esc_attr( $this->set( array( 'google-map' ) ) ) ?>"></div>
            <input type="hidden" id="<?php echo esc_attr( $this->set( array( 'google-map-address' ) ) ) ?>"
                   value="<?php echo esc_attr( $google_map_address ) ?>">
        </div>
		<?php


		return ob_get_clean();
	}

	private function text_editor_html( $order ) {
		ob_start();
		if ( is_array( $this->text_editor ) && count( $this->text_editor ) ) {
			$text = array_splice( $this->text_editor, 0, 1 )[0];
			if ( is_string( $text ) ) {
				$text = base64_decode( $text );
			} else {
				$text = base64_decode( $text[0] ?? '' );
			}
			?>
            <div class="<?php echo esc_attr( $this->set( array( 'text-editor', 'item__container' ) ) ) ?>"
                 id="<?php echo esc_attr( $this->set( 'text-editor-' . $this->text_editor_id ) ) ?>">
                <div class="<?php echo esc_attr( $this->set( 'text-editor-content' ) ) ?>">
					<?php
					echo do_shortcode( $text );
					?>
                </div>

                <span class="<?php echo esc_attr( $this->set( 'text-editor-edit' ) ); ?> wtyp_icons-edit"><?php esc_html_e( 'Edit', 'woo-thank-you-page-customizer' ) ?></span>

            </div>
			<?php
			$this->text_editor_id ++;
		}

		return ob_get_clean();
	}

	private function customer_information_html( $order, $customer_information_header ) {
		ob_start();
		$show_shipping = ! wc_ship_to_billing_address_only() && $order->needs_shipping_address();
		?>
        <div class="<?php echo esc_attr( $this->set( array(
			'customer_information__container',
			'item__container'
		) ) ) ?>"
             id="<?php echo esc_attr( $this->set( 'customer_information__container' ) ) ?>">

                <span class="<?php echo esc_attr( $this->set( 'edit-item-shortcut' ) ) ?> wtyp_icons-edit"
                      data-edit_section="customer_information"><?php echo esc_html__( 'Edit', 'woo-thank-you-page-customizer' ) ?></span>

            <div class="<?php echo esc_attr( $this->set( array(
				'customer_information__header',
				'customer_information__detail',
			) ) ) ?>">
                <div class="<?php echo esc_attr( $this->set( 'customer_information-header' ) ) ?>">
                    <div><?php echo trim( strtolower( $customer_information_header ) ) === 'customer information' ? esc_html__( 'Customer information', 'woo-thank-you-page-customizer' ) : wp_kses_post( nl2br( $customer_information_header ) ); ?></div>
                </div>
            </div>
            <div class="<?php echo esc_attr( $this->set( array(
				'customer_information__address',
				'customer_information__detail',
			) ) ) ?>">
                <div class="<?php echo esc_attr( $this->set( array(
					'customer_information__billing_address',
				) ) ) ?>">
                    <div class="<?php echo esc_attr( $this->set( array(
						'customer_information__billing_address-header',
					) ) ) ?>">
						<?php esc_html_e( 'Billing address', 'woocommerce' ); ?>
                    </div>
                    <div class="<?php echo esc_attr( $this->set( array(
						'customer_information__billing_address-address',
					) ) ) ?>">
						<?php echo wp_kses_post( $order->get_formatted_billing_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>

						<?php if ( $order->get_billing_phone() ) : ?>
                            <div><?php echo esc_html( $order->get_billing_phone() ); ?></div>
						<?php endif; ?>

						<?php if ( $order->get_billing_email() ) : ?>
                            <div><?php echo esc_html( $order->get_billing_email() ); ?></div>
						<?php endif; ?>
                    </div>
                </div>
				<?php
				if ( $show_shipping ) {
					?>
                    <div class="<?php echo esc_attr( $this->set( array(
						'customer_information__shipping_address',
					) ) ) ?>">
                        <div class="<?php echo esc_attr( $this->set( array(
							'customer_information__shipping_address-header',
						) ) ) ?>">
							<?php esc_html_e( 'Shipping address', 'woocommerce' ); ?>
                        </div>
                        <div class="<?php echo esc_attr( $this->set( array(
							'customer_information__shipping_address-address',
						) ) ) ?>">
							<?php echo wp_kses_post( $order->get_formatted_shipping_address( esc_html__( 'N/A', 'woocommerce' ) ) ); ?>
                        </div>
                    </div>
					<?php
				}
				?>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}

	private function order_details_html( $order, $order_details_header, $order_details_product_image, $order_details_product_quantity_in_image ) {
		ob_start();

		$order_items        = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
		$show_purchase_note = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array(
			'completed',
			'processing'
		) ) );
		$downloads          = $order->get_downloadable_items();
		$show_downloads     = $order->has_downloadable_item() && $order->is_download_permitted();

		if ( $show_downloads ) {
			wc_get_template( 'order/order-downloads.php', array( 'downloads' => $downloads, 'show_title' => true ) );
		}
		?>
        <div class="<?php echo esc_attr( $this->set( array( 'order_details__container', 'item__container' ) ) ) ?>"
             id="<?php echo esc_attr( $this->set( 'order_details__container' ) ) ?>">

                <span class="<?php echo esc_attr( $this->set( 'edit-item-shortcut' ) ) ?> wtyp_icons-edit"
                      data-edit_section="order_details"><?php echo esc_html__( 'Edit', 'woo-thank-you-page-customizer' ) ?></span>

            <div class="<?php echo esc_attr( $this->set( array(
				'order_details__header',
				'order_details__detail'
			) ) ) ?>">
                <div class="<?php echo esc_attr( $this->set( array(
					'order_details-header'
				) ) ) ?>">
                    <div><?php echo trim( strtolower( $order_details_header ) ) === 'order details' ? esc_html__( 'Order details', 'woo-thank-you-page-customizer' ) : wp_kses_post( nl2br( $order_details_header ) ); ?></div>
                </div>
            </div>

            <div class="<?php echo esc_html( $this->set( array(
				'order_details__header',
				'order_details__detail'
			) ) ) ?>">
                <div class="<?php echo esc_html( $this->set( array(
					'order_details__header-title',
					'order_details-title'
				) ) ) ?>">
                    <div><?php esc_html_e( 'Product', 'woo-thank-you-page-customizer' ); ?></div>
                </div>
                <div class="<?php echo esc_html( $this->set( array(
					'order_details__header-value',
					'order_details-value'
				) ) ) ?>">
                    <div><?php esc_html_e( 'Total', 'woo-thank-you-page-customizer' ); ?></div>
                </div>
            </div>
            <div class="<?php echo esc_html( $this->set( array(
				'order_details__order_items'
			) ) ) ?>">
				<?php
				foreach ( $order_items as $item_id => $item ) {
					$product = $item->get_product();
					if ( ! $product ) {
						continue;
					}
					$purchase_note = $product->get_purchase_note();
					?>
                    <div class="<?php echo esc_html( $this->set( array(
						'order_details__product',
						'order_details__detail'
					) ) ) ?>">
                        <div class="<?php echo esc_html( $this->set( array(
							'order_details__product-title',
							'order_details-title'
						) ) ) ?>">
							<?php
							$is_visible                    = $product && $product->is_visible();
							$product_permalink             = apply_filters( 'woocommerce_order_item_permalink', $is_visible ? $product->get_permalink( $item ) : '', $item, $order );
							$product_image_src             = wc_placeholder_img_src();
							$alt                           = '';
							$product_quantity_html_default = ' <strong class="product-quantity ' . $this->set( 'order-item-product-quantity-default' ) . '">' . sprintf( '&times; %s', $item->get_quantity() ) . '</strong>';
							//							if ( $order_details_product_quantity_in_image ) {
							//								$product_quantity_html = '<span class="' . $this->set( 'order-item-product-quantity' ) . '">' . $item->get_quantity() . '</span>';
							//							}
							if ( $product->get_image_id() ) {
								$product_image_src = wp_get_attachment_thumb_url( $product->get_image_id() );
								$alt               = get_post_meta( $product->get_id(), '_wp_attachment_image_alt', true );
							}
							echo apply_filters( 'woo_thank_you_page_order_item_image', $product_permalink ? sprintf( '<div class="%s"><a href="%s" class="%s"><img class="%s" src="%s" alt="%s"></a></div>', ( $order_details_product_image ? $this->set( array(
								'order-item-image-container',
								'active'
							) ) : $this->set( 'order-item-image-container' ) ), $product_permalink, $this->set( 'order-item-image-wrap' ), $this->set( 'order-item-image' ), $product_image_src, $alt ? $alt : $item->get_name() ) : $item->get_name(), $item, $is_visible );

							?>
                            <div>
								<?php
								echo apply_filters( 'woocommerce_order_item_name', $product_permalink ? sprintf( '<a href="%s">%s</a>', $product_permalink, $item->get_name() ) : $item->get_name(), $item, $is_visible );
								echo apply_filters( 'woocommerce_order_item_quantity_html', $product_quantity_html_default, $item );
								?>
                            </div>
							<?php
							do_action( 'woocommerce_order_item_meta_start', $item_id, $item, $order, false );

							wc_display_item_meta( $item );

							do_action( 'woocommerce_order_item_meta_end', $item_id, $item, $order, false );
							?>
                        </div>
                        <div class="<?php echo esc_attr( $this->set( array(
							'order_details__product-value',
							'order_details-value'
						) ) ) ?>">
							<?php echo( $order->get_formatted_line_subtotal( $item ) ); ?>
                        </div>
                    </div>
					<?php
					if ( $show_purchase_note && $purchase_note ) {
						?>
                        <div class="<?php echo esc_attr( $this->set( array(
							'order_details__purchase_note',
							'order_details__detail'
						) ) ) ?>">
							<?php echo wpautop( do_shortcode( wp_kses_post( $purchase_note ) ) ); ?>
                        </div>
						<?php
					}
				}
				?>
            </div>
            <div class="<?php echo esc_attr( $this->set( array(
				'order_details__order_item_total'
			) ) ) ?>">
				<?php
				foreach ( $order->get_order_item_totals() as $key => $total ) {
					?>
                    <div class="<?php echo esc_attr( $this->set( array(
						'order_details__' . $key,
						'order_details__detail',
					) ) ) ?>">
                        <div class="<?php echo esc_attr( $this->set( array(
							'order_details-title'
						) ) ) ?>">
                            <div><?php echo esc_html( str_replace( ':', '', $total['label'] ) ); ?></div>
                        </div>
                        <div class="<?php echo esc_attr( $this->set( array(
							'order_details-value'
						) ) ) ?>">
							<?php
							if ( $key == 'order_total' ) {
								?>
                                <div><?php echo get_woocommerce_currency(); ?></div>
								<?php
							}
							?>
                            <div><?php echo wp_kses_post( $total['value'] ); ?></div>
                        </div>
                    </div>
					<?php
				}
				?>
            </div>
			<?php
			if ( $order->get_customer_note() ) {
				?>
                <div class="<?php echo esc_attr( $this->set( array(
					'order_details__detail'
				) ) ) ?>">
                    <div class="<?php echo esc_attr( $this->set( array(
						'order_details-value'
					) ) ) ?>">
                        <div><?php esc_html_e( 'Note', 'woocommerce' ); ?></div>
                    </div>
                    <div class="<?php echo esc_attr( $this->set( array(
						'order_details-value'
					) ) ) ?>">
                        <div><?php echo wptexturize( $order->get_customer_note() ); ?></div>
                    </div>
                </div>
				<?php
			}
			?>
        </div>
		<?php
		do_action( 'woocommerce_after_order_details', $order );

		return ob_get_clean();
	}

	private function order_confirmation_html( $order, $order_confirmation_header ) {
		ob_start();
		?>
        <div class="<?php echo esc_attr( $this->set( array( 'order_confirmation__container', 'item__container' ) ) ) ?>"
             id="<?php echo esc_attr( $this->set( 'order_confirmation__container' ) ) ?>">

                <span class="<?php echo esc_attr( $this->set( 'edit-item-shortcut' ) ) ?> wtyp_icons-edit"
                      data-edit_section="order_confirmation"><?php echo esc_html__( 'Edit', 'woo-thank-you-page-customizer' ) ?></span>

            <div class="<?php echo esc_attr( $this->set( array(
				'order_confirmation__header',
				'order_confirmation__detail'
			) ) ) ?>">
                <div class="<?php echo esc_attr( $this->set( array(
					'order_confirmation-header'
				) ) ) ?>">
                    <div><?php echo trim( strtolower( $order_confirmation_header ) ) === 'order confirmation' ? esc_html__( 'Order confirmation', 'woo-thank-you-page-customizer' ) : wp_kses_post( nl2br( $order_confirmation_header ) ); ?></div>
                </div>
            </div>
            <div class="<?php echo esc_attr( $this->set( array(
				'order_confirmation__order_number',
				'order_confirmation__detail'
			) ) ) ?>">
                <div class="<?php echo esc_attr( $this->set( array(
					'order_confirmation__order_number-title',
					'order_confirmation-title'
				) ) ) ?>">
                    <div><?php esc_html_e( 'Order number', 'woo-thank-you-page-customizer' ); ?></div>
                </div>
                <div class="<?php echo esc_attr( $this->set( array(
					'order_confirmation__order_number-value',
					'order_confirmation-value'
				) ) ) ?>">
                    <div>       <?php echo esc_html( $order->get_order_number() ); ?></div>
                </div>
            </div>

            <div class="<?php echo esc_attr( $this->set( array(
				'order_confirmation__order_date',
				'order_confirmation__detail'
			) ) ) ?>">
                <div class="<?php echo esc_attr( $this->set( array(
					'order_confirmation__order_date-title',
					'order_confirmation-title'
				) ) ) ?>">
                    <div><?php esc_html_e( 'Date', 'woo-thank-you-page-customizer' ); ?></div>
                </div>
                <div class="<?php echo esc_attr( $this->set( array(
					'order_confirmation__order_date-value',
					'order_confirmation-value'
				) ) ) ?>">
                    <div>       <?php echo wc_format_datetime( $order->get_date_created() ); ?></div>
                </div>
            </div>
            <div class="<?php echo esc_attr( $this->set( array(
				'order_confirmation__order_total',
				'order_confirmation__detail'
			) ) ) ?>">
                <div class="<?php echo esc_attr( $this->set( array(
					'order_confirmation__order_total-title',
					'order_confirmation-title'
				) ) ) ?>">
                    <div><?php esc_html_e( 'Total', 'woo-thank-you-page-customizer' ); ?></div>
                </div>
                <div class="<?php echo esc_attr( $this->set( array(
					'order_confirmation__order_total-value',
					'order_confirmation-value'
				) ) ) ?>">
                    <div>       <?php echo wp_kses_post( $order->get_formatted_order_total() ); ?></div>
                </div>
            </div>
			<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) { ?>
                <div class="<?php echo esc_attr( $this->set( array(
					'order_confirmation__order_email',
					'order_confirmation__detail'
				) ) ) ?>">
                    <div class="<?php echo esc_attr( $this->set( array(
						'order_confirmation__order_email-title',
						'order_confirmation-title'
					) ) ) ?>">
                        <div><?php esc_html_e( 'Email', 'woo-thank-you-page-customizer' ); ?></div>
                    </div>
                    <div class="<?php echo esc_attr( $this->set( array(
						'order_confirmation__order_email-value',
						'order_confirmation-value'
					) ) ) ?>">
                        <div title="<?php esc_attr_e( $order->get_billing_email() ); ?>"><?php echo esc_html( $order->get_billing_email() ); ?></div>
                    </div>
                </div>
			<?php } ?>
			<?php if ( $order->get_payment_method_title() ) { ?>
                <div class="<?php echo esc_attr( $this->set( array(
					'order_confirmation__order_payment',
					'order_confirmation__detail'
				) ) ) ?>">
                    <div class="<?php echo esc_attr( $this->set( array(
						'order_confirmation__order_payment-title',
						'order_confirmation-title'
					) ) ) ?>">
                        <div><?php esc_html_e( 'Payment method', 'woo-thank-you-page-customizer' ); ?></div>
                    </div>
                    <div class="<?php echo esc_attr( $this->set( array(
						'order_confirmation__order_payment-value',
						'order_confirmation-value'
					) ) ) ?>">
                        <div><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></div>
                    </div>
                </div>
			<?php } ?>
        </div>
		<?php
		return ob_get_clean();
	}

}