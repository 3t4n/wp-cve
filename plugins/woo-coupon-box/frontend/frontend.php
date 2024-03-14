<?php

/**
 * Show Coupon box
 * Class VI_WOO_COUPON_BOX_Frontend_Coupon
 *
 */
class VI_WOO_COUPON_BOX_Frontend_Frontend {
	protected $settings;
	protected $characters_array;

	public function __construct() {
		$this->settings = new VI_WOO_COUPON_BOX_DATA();
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

		/*Ajax add email*/
		add_action( 'wp_ajax_nopriv_wcb_email', array( $this, 'wcb_email' ) );
		add_action( 'wp_ajax_wcb_email', array( $this, 'wcb_email' ) );
	}

	/**
	 * Process ajax add email
	 */
	public function wcb_email() {
		$g_validate_response = isset( $_POST['g_validate_response'] ) ? sanitize_text_field( wp_unslash( $_POST['g_validate_response'] ) ) : '';

		if ( ! $g_validate_response && $this->settings->get_params( 'wcb_recaptcha' ) ) {
			$msg['status']  = 'invalid';
			$msg['warning'] = esc_html__( '*No g_validate_response', 'woo-coupon-box' );
			wp_send_json( $msg );
			die;
		}

		if ( $g_validate_response && $this->settings->get_params( 'wcb_recaptcha' ) ) {
			$msg = array(
				'status'              => '',
				'message'             => '',
				'warning'             => '',
				'g_validate_response' => '1',
			);

			if ( ! $g_validate_response ) {
				$msg['status']  = 'invalid';
				$msg['warning'] = esc_html__( '*Invalid google reCAPTCHA!', 'woo-coupon-box' );
				wp_send_json( $msg );
				die;
			}

			$wcb_recaptcha_secret_key = $this->settings->get_params( 'wcb_recaptcha_secret_key' );

			if ( ! $wcb_recaptcha_secret_key ) {
				$msg['status']  = 'invalid';
				$msg['warning'] = esc_html__( '*Invalid google reCAPTCHA secret key!', 'woo-coupon-box' );
				wp_send_json( $msg );
				die;
			}

			try {
				$url      = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $wcb_recaptcha_secret_key . '&response=' . $g_validate_response;
				$response = wp_remote_post( $url );
				$body     = wp_remote_retrieve_body( $response );

				$data = json_decode( $body, true );
				if ( $this->settings->get_params( 'wcb_recaptcha_version' ) == 2 ) {
					if ( ! $data['success'] ) {
						$msg['status']  = 'invalid';
						$msg['warning'] = esc_html__( '*reCAPTCHA verification failed', 'woo-coupon-box' );
						$msg['message'] = $data;
						wp_send_json( $msg );
						die();
					}
				} else {
					$g_score = isset( $data['score'] ) ? $data['score'] : 0;
					if ( $g_score < 0.5 ) {
						$msg['status']  = 'invalid';
						$msg['warning'] = sprintf( "%s %s %s", esc_html__( '*reCAPTCHA score', 'woo-coupon-box' ), esc_html( $g_score ), esc_html__( 'lower than threshold 0.5 ', 'woo-coupon-box' ) );
						$msg['message'] = $data;
						wp_send_json( $msg );
						die();
					}
				}
			} catch ( Exception $e ) {
				$msg['status']  = 'invalid';
				$msg['warning'] = $e->getMessage();
				wp_send_json( $msg );
				die;
			}
		}

		$wcb_enable_mailchimp            = $this->settings->get_params( 'wcb_enable_mailchimp' );
		$wcb_email_campaign              = $this->settings->get_params( 'wcb_email_campaign' );
		$wcb_footer_text_after_subscribe = $this->settings->get_params( 'wcb_footer_text_after_subscribe' );
		$email                           = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );

		$msg = array(
			'status'           => '',
			'message'          => '',
			'warning'          => '',
			'code'             => '',
			'thankyou'         => '',
			'wcb_current_time' => time(),
		);

		$meta = array(
			'coupon'              => '',
			'campaign'            => '',
			'mailchimp'           => '',
			'mailchimp_list'      => '',
			'activecampaign'      => '',
			'activecampaign_list' => '',
			'sendgrid'            => '',
			'sendgrid_list'       => '',
			'name'                => isset( $_POST['wcb_input_name'] ) ? sanitize_text_field( wp_unslash( $_POST['wcb_input_name'] ) ) : '',
			'lname'               => isset( $_POST['wcb_input_lname'] ) ? sanitize_text_field( wp_unslash( $_POST['wcb_input_lname'] ) ) : '',
			'mobile'              => isset( $_POST['wcb_input_mobile'] ) ? sanitize_text_field( wp_unslash( $_POST['wcb_input_mobile'] ) ) : '',
			'birthday'            => isset( $_POST['wcb_input_birthday'] ) ? sanitize_text_field( wp_unslash( $_POST['wcb_input_birthday'] ) ) : '',
			'gender'              => isset( $_POST['wcb_input_gender'] ) ? sanitize_text_field( wp_unslash( $_POST['wcb_input_gender'] ) ) : '',
		);

		ob_start();
		?>
        <div class="wcb-footer-text-after-subscribe"><?php echo esc_html( $wcb_footer_text_after_subscribe ) ?></div>
		<?php
		$msg['thankyou'] = ob_get_clean();

		if ( is_email( $email ) ) {
//		    check if email already subscribed
			$emails_args = array(
				'post_type'      => 'wcb',
				'posts_per_page' => - 1,
				'title'          => $email,
				'post_status'    => array( // (string | array) - use post status. Retrieves posts by Post Status, default value i'publish'.
					'publish', // - a published post or page.
					'pending', // - post is pending review.
					'draft',  // - a post in draft status.
					'auto-draft', // - a newly created post, with no content.
					'future', // - a post to publish in the future.
					'private', // - not visible to users who are not logged in.
					'inherit', // - a revision. see get_children.
					'trash', // - post is in trashbin (available with Version 2.9).
				)
			);
			$the_query   = new WP_Query( $emails_args );
			if ( $the_query->have_posts() ) {
				$msg['status']  = 'existed';
				$msg['warning'] = esc_html__( '*This email already subscribed!', 'woo-coupon-box' );
				wp_send_json( $msg );
				die;
			}
			wp_reset_postdata();
			// Create post object
			if ( $wcb_enable_mailchimp && class_exists( 'VI_WOO_COUPON_BOX_Admin_Mailchimp' ) ) {
				/*Add mailchimp*/
				$mailchimp = new VI_WOO_COUPON_BOX_Admin_Mailchimp();
				$mailchimp->add_email( $email );
				$meta['mailchimp'] = $this->settings->get_params( 'wcb_mlists' );
			}

			$my_post = array(
				'post_title'  => $email,
				'post_type'   => 'wcb',
				'post_status' => 'publish',
			);
// Insert the post into the database
			$post_id    = wp_insert_post( $my_post );
			$uncategory = get_term_by( 'slug', 'uncategorized', 'wcb_email_campaign' );

			if ( $wcb_email_campaign ) {
				$my_post['tax_input'] = array(
					'wcb_email_campaign' => $wcb_email_campaign
				);
				$meta['campaign']     = $wcb_email_campaign;
				wp_set_post_terms( $post_id, array( $wcb_email_campaign ), 'wcb_email_campaign' );
			} elseif ( $uncategory ) {
				$term_id              = $uncategory->term_id;
				$my_post['tax_input'] = array(
					'wcb_email_campaign' => $term_id
				);
				$meta['campaign']     = $term_id;
				wp_set_post_terms( $post_id, array( $term_id ), 'wcb_email_campaign' );
			}
			$code           = $this->create_coupon( $email );
			$meta['coupon'] = $code;
			update_post_meta( $post_id, 'woo_coupon_box_meta', $meta );
			$msg['message'] = $this->settings->get_params( 'wcb_message_after_subscribe' );

			$coupon_select = $this->settings->get_params( 'wcb_coupon_select' );
			$show_coupon   = $this->settings->get_params( 'wcb_show_coupon' );
			if ( $coupon_select == 'non' ) {
				/*Only subscribe*/
				$this->send_email( $email, '', '' );
			} elseif ( in_array( $coupon_select, array( 'existing', 'unique' ) ) ) {
				/*Send a WooCommerce coupon*/
				if ( $code ) {
					$coupon = new WC_Coupon( $code );
					if ( $coupon->get_discount_type() == 'percent' ) {
						$coupon_value = $coupon->get_amount() . '%';
					} else {
						$coupon_value = $this->wc_price( $coupon->get_amount() );
					}
					$this->send_email( $email, '', strtoupper( trim( $coupon->get_code() ) ), $coupon->get_date_expires(), $coupon_value );

					if ( $show_coupon ) {
						ob_start();
						?>
                        <div class="wcb-coupon-treasure-container">
                            <input type="text" readonly="readonly" class="wcb-coupon-treasure"
                                   value="<?php echo esc_attr( strtoupper( trim( $coupon->get_code() ) ) ); ?>"/>
                        </div>
                        <span class="wcb-guide">
						<?php esc_html_e( 'Enter this promo code at checkout page.', 'woo-coupon-box' ) ?>
					</span>
						<?php
						$msg['code'] = ob_get_clean();
					}
				}

			} else {
				/*Send a custom coupon code*/
				$this->send_email( $email, '', $code );
				if ( $show_coupon ) {
					ob_start();
					?>
                    <div class="wcb-coupon-treasure-container">
                        <input type="text" readonly="readonly" value="<?php echo esc_html( $code ); ?>" class="wcb-coupon-treasure"/>
                    </div>
                    <span class="wcb-guide">
						<?php esc_html_e( 'Enter this promo code at checkout page.', 'woo-coupon-box' ) ?>
					</span>
					<?php
					$msg['code'] = ob_get_clean();
				}

			}
			$msg['status'] = 'subscribed';
			wp_send_json( $msg );
			die;
		} else {
			$msg['status']  = 'invalid';
			$msg['warning'] = esc_html__( '*Invalid email!', 'woo-coupon-box' );
			wp_send_json( $msg );
			die;
		}
	}

	protected function rand() {
		if ( $this->characters_array === null ) {
			$this->characters_array = array_merge( range( 0, 9 ), range( 'a', 'z' ) );
		}
		$rand = wp_rand( 0, count( $this->characters_array ) - 1 );

		return $this->characters_array[ $rand ];
	}

	protected function create_code() {
		wp_reset_postdata();

		$code = $this->settings->get_params( 'wcb_coupon_unique_prefix' );
		for ( $i = 0; $i < 6; $i ++ ) {
			$code .= $this->rand();
		}
		$args      = array(
			'post_type'      => 'shop_coupon',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
			'title'          => $code
		);
		$the_query = new WP_Query( $args );
		if ( $the_query->have_posts() ) {
			$code = $this->create_code();
		}
		wp_reset_postdata();

		return $code;
	}

	public function create_coupon( $email ) {
		$code = '';
		switch ( $this->settings->get_params( 'wcb_coupon_select' ) ) {
			case 'existing':
				$code = $this->settings->get_params( 'wcb_coupon' );
				if ( $this->settings->get_params( 'wcb_coupon_unique_email_restrictions' ) ) {
					$coupon = new WC_Coupon( $code );
					$er     = $coupon->get_email_restrictions();
					if ( ! in_array( $email, $er ) ) {
						$er[] = $email;
						$coupon->set_email_restrictions( $er );
						$coupon->save();
					}
				}
				break;
			case 'custom':
				$code = $this->settings->get_params( 'wcb_coupon_custom' );
				break;
			case 'unique':
				$code         = $this->create_code();
				$coupon       = new WC_Coupon( $code );
				$today        = strtotime( date_i18n( 'Ymd' ) );
				$date_expires = ( $this->settings->get_params( 'wcb_coupon_unique_date_expires' ) ) ? ( ( $this->settings->get_params( 'wcb_coupon_unique_date_expires' ) + 1 ) * 86400 + $today ) : '';
				$coupon->set_amount( $this->settings->get_params( 'wcb_coupon_unique_amount' ) );
				$coupon->set_date_expires( $date_expires );
				$coupon->set_discount_type( $this->settings->get_params( 'wcb_coupon_unique_discount_type' ) );
				$coupon->set_description( $this->settings->get_params( 'wcb_coupon_unique_description' ) );
				$coupon->set_individual_use( $this->settings->get_params( 'wcb_coupon_unique_individual_use' ) );
				if ( $this->settings->get_params( 'wcb_coupon_unique_product_ids' ) ) {
					$coupon->set_product_ids( $this->settings->get_params( 'wcb_coupon_unique_product_ids' ) );
				}
				if ( $this->settings->get_params( 'wcb_coupon_unique_excluded_product_ids' ) ) {
					$coupon->set_excluded_product_ids( $this->settings->get_params( 'wcb_coupon_unique_excluded_product_ids' ) );
				}
				$coupon->set_usage_limit( $this->settings->get_params( 'wcb_coupon_unique_usage_limit' ) );
				$coupon->set_usage_limit_per_user( $this->settings->get_params( 'wcb_coupon_unique_usage_limit_per_user' ) );
				$coupon->set_limit_usage_to_x_items( $this->settings->get_params( 'wcb_coupon_unique_limit_usage_to_x_items' ) );
				$coupon->set_free_shipping( $this->settings->get_params( 'wcb_coupon_unique_free_shipping' ) );
				$coupon->set_product_categories( $this->settings->get_params( 'wcb_coupon_unique_product_categories' ) );
				$coupon->set_excluded_product_categories( $this->settings->get_params( 'wcb_coupon_unique_excluded_product_categories' ) );
				$coupon->set_exclude_sale_items( $this->settings->get_params( 'wcb_coupon_unique_exclude_sale_items' ) );
				$coupon->set_minimum_amount( $this->settings->get_params( 'wcb_coupon_unique_minimum_amount' ) );
				$coupon->set_maximum_amount( $this->settings->get_params( 'wcb_coupon_unique_maximum_amount' ) );
				if ( $this->settings->get_params( 'wcb_coupon_unique_email_restrictions' ) ) {
					$coupon->set_email_restrictions( array( $email ) );
				}
				$coupon->save();
				$code = $coupon->get_code();
			default:
		}

		return $code;
	}

	public function send_email( $user_email, $customer_name, $coupon_code, $date_expires = '', $coupon_value = '' ) {
		$date_format = wc_date_format();

		$button_shop_now = '<a href="' . ( $this->settings->get_params( 'wcb_button_shop_now_url' ) ? $this->settings->get_params( 'wcb_button_shop_now_url' ) : get_bloginfo( 'url' ) ) . '" target="_blank" style="text-decoration:none;display:inline-block;padding:10px 30px;margin:10px 0;font-size:' . $this->settings->get_params( 'wcb_button_shop_now_size' ) . 'px;color:' . $this->settings->get_params( 'wcb_button_shop_now_color' ) . ';background:' . $this->settings->get_params( 'wcb_button_shop_now_bg_color' ) . ';border-radius:' . $this->settings->get_params( 'wcb_button_shop_now_border_radius' ) . 'px">' . $this->settings->get_params( 'wcb_button_shop_now_title' ) . '</a>';
		$headers         = "Content-Type: text/html\r\n";
		$content         = stripslashes( $this->settings->get_params( 'wcb_email_content' ) );
		$content         = str_replace( '{coupon_value}', $coupon_value, $content );
		$content         = str_replace( '{customer_name}', $customer_name, $content );
		$content         = str_replace( '{coupon_code}', '<span style="font-size: x-large;">' . strtoupper( $coupon_code ) . '</span>', $content );
		$content         = str_replace( '{date_expires}', empty( $date_expires ) ? 'never expires' : date_i18n( $date_format, strtotime( $date_expires ) ), $content );
		$content         = str_replace( '{last_valid_date}', empty( $date_expires ) ? '' : date_i18n( $date_format, strtotime( $date_expires ) - 86400 ), $content );
		$content         = str_replace( '{site_title}', get_bloginfo( 'name' ), $content );
		$content         = str_replace( '{shop_now}', $button_shop_now, $content );
		$subject         = stripslashes( $this->settings->get_params( 'wcb_email_subject' ) );
		$subject         = str_replace( '{coupon_value}', $coupon_value, $subject );
		$heading         = stripslashes( $this->settings->get_params( 'wcb_email_heading' ) );
		$heading         = str_replace( '{coupon_value}', $coupon_value, $heading );
		$mailer          = WC()->mailer();
		$email           = new WC_Email();
		$content         = $email->style_inline( $mailer->wrap_message( $heading, $content ) );
		$email->send( $user_email, $subject, $content, $headers, array() );
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

	/**
	 * Init Style and Script
	 */
	public function enqueue_scripts() {
		if ( is_admin() || is_customize_preview() ) {
			return;
		}
		if ( ! $this->settings->get_params( 'wcb_active' ) ) {
			return;
		}
		if ( $this->settings->get_params( 'wcb_disable_login' ) && is_user_logged_in() ) {
			return;
		}
		if ( isset( $_COOKIE['woo_coupon_box'] ) ) {
			$cookies = explode( ':', sanitize_text_field( wp_unslash( $_COOKIE['woo_coupon_box'] ) ) );
			if ( isset( $cookies[0] ) && in_array( $cookies[0], array( 'subscribed', 'closed' ) ) ) {
				return;
			}
		}
		$wcb_assign_home = $this->settings->get_params( 'wcb_assign_home' );
		$logic_value     = $this->settings->get_params( 'wcb_assign' );

		if ( ! is_front_page() && $wcb_assign_home ) {
			return;
		}
		if ( $logic_value ) {
			if ( stristr( $logic_value, "return" ) === false ) {
				$logic_value = "return (" . $logic_value . ");";
			}
			try {
				if ( ! eval( $logic_value ) ) {
					return;
				}
			} catch ( Error $e ) {
				trigger_error( $e->getMessage(), E_USER_WARNING );

				return;
			} catch ( Exception $e ) {
				trigger_error( $e->getMessage(), E_USER_WARNING );

				return;
			}
		}

		$poup_type = $this->settings->get_params( 'wcb_popup_type' );
		if ( $poup_type ) {
			wp_enqueue_style( 'woo-coupon-box-popup-type-' . $poup_type, VI_WOO_COUPON_BOX_CSS . '/popup-effect/' . $poup_type . '.css', array(), VI_WOO_COUPON_BOX_VERSION );
		}
		// script

		$wcb_popup_time = 0;
		if ( $this->settings->get_params( 'wcb_popup_time' ) ) {
			$wcb_popup_time_val = explode( ',', $this->settings->get_params( 'wcb_popup_time' ) );
			if ( count( $wcb_popup_time_val ) < 2 ) {
				$wcb_popup_time = absint( $wcb_popup_time_val[0] );
			} else {
				$wcb_popup_time = ( absint( $wcb_popup_time_val[0] ) > absint( $wcb_popup_time_val[1] ) ) ? wp_rand( $wcb_popup_time_val[1], $wcb_popup_time_val[0] ) : wp_rand( $wcb_popup_time_val[0], $wcb_popup_time_val[1] );
			}
		}

		$wcb_select_popup = $this->settings->get_params( 'wcb_select_popup' );

		if ( $wcb_select_popup == 'random' ) {
			$ran = wp_rand( 1, 3 );

			if ( $ran == 1 ) {
				$wcb_select_popup = 'time';
			} elseif ( $ran == 2 ) {
				$wcb_select_popup = 'scroll';
			} else {
				$wcb_select_popup = 'exit';
			}
		}
		$wcb_expire = $this->settings->get_params( 'wcb_expire' ) ?? 1;
		$wcb_expire = (int) $wcb_expire;
		switch ( $this->settings->get_params( 'wcb_expire_unit' ) ) {
			case 'day':
				$wcb_expire *= 86400;
				break;
			case 'hour':
				$wcb_expire *= 3600;
				break;
			case 'minute':
				$wcb_expire *= 60;
				break;
			default:
		}
		$data = array(
			'ajaxurl'                     => admin_url( 'admin-ajax.php' ),
			'wcb_select_popup'            => $wcb_select_popup,
			'wcb_popup_time'              => $wcb_popup_time,
			'wcb_popup_scroll'            => $this->settings->get_params( 'wcb_popup_scroll' ),
			'wcb_popup_exit'              => $this->settings->get_params( 'wcb_popup_exit' ),
			'wcb_on_close'                => $this->settings->get_params( 'wcb_on_close' ),
			'wcb_current_time'            => time(),
			'wcb_show_coupon'             => $this->settings->get_params( 'wcb_show_coupon' ),
			'wcb_expire'                  => $wcb_expire,
			'wcb_expire_subscribed'       => ( (int) $this->settings->get_params( 'wcb_expire_subscribed' ) ) * 86400,
			'wcb_gdpr_checkbox'           => $this->settings->get_params( 'wcb_gdpr_checkbox' ),
			'wcb_popup_type'              => $this->settings->get_params( 'wcb_popup_type' ),
			'wcb_empty_email_warning'     => esc_html__( '*Please enter your email and subscribe.', 'woo-coupon-box' ),
			'wcb_invalid_email_warning'   => esc_html__( '*Invalid email!', 'woo-coupon-box' ),
			'wcb_title_after_subscribing' => $this->settings->get_params( 'wcb_title_after_subscribing' ),
			'wcb_popup_position'          => in_array( $this->settings->get_params( 'wcb_popup_icon_position' ), array(
				'top-left',
				'bottom-left'
			) ) ? 'left' : 'right',
			'wcb_recaptcha_site_key'      => $this->settings->get_params( 'wcb_recaptcha_site_key' ),
			'wcb_recaptcha_version'       => $this->settings->get_params( 'wcb_recaptcha_version' ),
			'wcb_recaptcha_secret_theme'  => $this->settings->get_params( 'wcb_recaptcha_secret_theme' ),
			'wcb_recaptcha'               => $this->settings->get_params( 'wcb_recaptcha' ),
			'wcb_never_reminder_enable'   => $this->settings->get_params( 'wcb_never_reminder_enable' ),
		);

		wp_enqueue_style( 'wcb-weather-style', VI_WOO_COUPON_BOX_CSS . 'weather.css', array(), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_script( 'woo-coupon-box-script', VI_WOO_COUPON_BOX_JS . 'wcb.js', array(), VI_WOO_COUPON_BOX_VERSION, true );

		wp_localize_script( 'woo-coupon-box-script', 'wcb_params', $data );
		add_action( 'wp_footer', array( $this, 'wp_footer' ) );


		wp_enqueue_style( 'woo-coupon-box-template-1', VI_WOO_COUPON_BOX_CSS . 'layout-1.css', array(), VI_WOO_COUPON_BOX_VERSION );
		// style
		wp_enqueue_style( 'woo-coupon-box-giftbox-icons', VI_WOO_COUPON_BOX_CSS . 'wcb_giftbox.css', array(), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_style( 'woo-coupon-box-social-icons', VI_WOO_COUPON_BOX_CSS . 'wcb_social_icons.css', array(), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_style( 'woo-coupon-box-close-icons', VI_WOO_COUPON_BOX_CSS . 'wcb_button_close_icons.css', array(), VI_WOO_COUPON_BOX_VERSION );
		wp_enqueue_style( 'woo-coupon-box-basic', VI_WOO_COUPON_BOX_CSS . 'basic.css', array(), VI_WOO_COUPON_BOX_VERSION );

		$css                         = '';
		$wcb_button_close_position_x = $this->settings->get_params( 'wcb_button_close_position_x' ) * ( - 1 );
		$wcb_button_close_position_y = $this->settings->get_params( 'wcb_button_close_position_y' ) * ( - 1 );
		/*button close*/
		$css .= '.wcb-coupon-box span.wcb-md-close{';
		$css .= 'font-size:' . $this->settings->get_params( 'wcb_button_close_size' ) . 'px;';
		$css .= 'width:' . $this->settings->get_params( 'wcb_button_close_width' ) . 'px;';
		$css .= 'line-height:' . $this->settings->get_params( 'wcb_button_close_width' ) . 'px;';
		$css .= 'color:' . $this->settings->get_params( 'wcb_button_close_color' ) . ';';
		$css .= 'background:' . $this->settings->get_params( 'wcb_button_close_bg_color' ) . ';';
		$css .= 'border-radius:' . $this->settings->get_params( 'wcb_button_close_border_radius' ) . 'px;';
		$css .= 'right:' . $wcb_button_close_position_x . 'px;';
		$css .= 'top:' . $wcb_button_close_position_y . 'px;';
		$css .= '}';

		/*coupon box border radius*/
		$css .= '.wcb-coupon-box .wcb-content-wrap .wcb-md-content{border-radius:' . $this->settings->get_params( 'wcb_border_radius' ) . 'px;}';
		/*header*/
		$css .= '.wcb-coupon-box .wcb-md-content .wcb-modal-header{';
		if ( $this->settings->get_params( 'wcb_bg_header' ) ) {
			$css .= 'background-color:' . $this->settings->get_params( 'wcb_bg_header' ) . ';';
		}
		if ( $this->settings->get_params( 'wcb_color_header' ) ) {
			$css .= 'color:' . $this->settings->get_params( 'wcb_color_header' ) . ';';
		}
		$css .= 'font-size:' . $this->settings->get_params( 'wcb_title_size' ) . 'px;';
		$css .= 'line-height:' . $this->settings->get_params( 'wcb_title_size' ) . 'px;';
		$css .= 'padding-top:' . $this->settings->get_params( 'wcb_title_space' ) . 'px;';
		$css .= 'padding-bottom:' . $this->settings->get_params( 'wcb_title_space' ) . 'px;';
		$css .= '}';

		/*body*/
		$css .= '.wcb-coupon-box .wcb-md-content .wcb-modal-body{';
		$css .= 'background-color:' . $this->settings->get_params( 'wcb_body_bg' ) . ';';
		$css .= 'color:' . $this->settings->get_params( 'wcb_body_text_color' ) . ';';
		if ( $this->settings->get_params( 'wcb_body_bg_img' ) ) {
			$css .= 'background-image:url(' . $this->settings->get_params( 'wcb_body_bg_img' ) . ');';
			$css .= 'background-repeat:' . $this->settings->get_params( 'wcb_body_bg_img_repeat' ) . ';';
			$css .= 'background-size:' . $this->settings->get_params( 'wcb_body_bg_img_size' ) . ';';
			$css .= 'background-position:' . $this->settings->get_params( 'wcb_body_bg_img_position' ) . ';';
		}
		$css .= '}';

		$css .= '.wcb-coupon-box .wcb-md-content .wcb-modal-body .wcb-coupon-message{color:' . $this->settings->get_params( 'wcb_color_message' ) . ';font-size:' . $this->settings->get_params( 'wcb_message_size' ) . 'px;text-align:' . $this->settings->get_params( 'wcb_message_align' ) . '}';

		/*text follow us*/
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-md-content .wcb-text-title', 'color', 'wcb_color_follow_us', '', '' );
		/*email input*/
		$css .= '.wcb-coupon-box .wcb-newsletter input.wcb-email{border-radius:' . $this->settings->get_params( 'wcb_email_input_border_radius' ) . 'px;}';
		$css .= '.wcb-coupon-box .wcb-modal-body .wcb-coupon-box-newsletter .wcb-newsletter-form input{margin-right:' . $this->settings->get_params( 'wcb_email_button_space' ) . 'px;}';

		if ( $this->settings->get_params( 'wcb_gdpr_checkbox' ) ) {
			$css .= '.wcb-coupon-box.wcb-collapse-after-close .wcb-coupon-box-newsletter{padding-bottom:0 !important;}';
		}
		/*button subscribe*/
		$css .= '.wcb-coupon-box .wcb-newsletter span.wcb-button{';
		$css .= 'color:' . $this->settings->get_params( 'wcb_button_text_color' ) . ';';
		$css .= 'background-color:' . $this->settings->get_params( 'wcb_button_bg_color' ) . ';';
		$css .= 'border-radius:' . $this->settings->get_params( 'wcb_button_border_radius' ) . 'px;';
		$css .= '}';
		/*overlay*/
		$css .= $this->generate_css( '.wcb-md-overlay', 'background', 'alpha_color_overlay', '', '' );
		/*social*/
		$css .= '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-social-icon{';
		$css .= 'font-size:' . $this->settings->get_params( 'wcb_social_icons_size' ) . 'px;';
		$css .= 'line-height:' . $this->settings->get_params( 'wcb_social_icons_size' ) . 'px;';
		$css .= '}';
		/*social-color*/
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-facebook-follow .wcb-social-icon', 'color', 'wcb_social_icons_facebook_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-twitter-follow .wcb-social-icon', 'color', 'wcb_social_icons_twitter_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-pinterest-follow .wcb-social-icon', 'color', 'wcb_social_icons_pinterest_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-instagram-follow .wcb-social-icon', 'color', 'wcb_social_icons_instagram_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-dribbble-follow .wcb-social-icon', 'color', 'wcb_social_icons_dribbble_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-tumblr-follow .wcb-social-icon', 'color', 'wcb_social_icons_tumblr_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-google-follow .wcb-social-icon', 'color', 'wcb_social_icons_google_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-vkontakte-follow .wcb-social-icon', 'color', 'wcb_social_icons_vkontakte_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-linkedin-follow .wcb-social-icon', 'color', 'wcb_social_icons_linkedin_color', '', '' );
		$css .= $this->generate_css( '.wcb-coupon-box .wcb-sharing-container .wcb-list-socials .wcb-youtube-follow .wcb-social-icon', 'color', 'wcb_social_icons_youtube_color', '', '' );
		$css .= $this->settings->get_params( 'wcb_custom_css' );
		/*popup icon*/
		$css .= '.wcb-coupon-box-small-icon{';
		$css .= 'font-size:' . $this->settings->get_params( 'wcb_popup_icon_size' ) . 'px;';
		$css .= 'line-height:' . $this->settings->get_params( 'wcb_popup_icon_size' ) . 'px;';
		$css .= 'color:' . $this->settings->get_params( 'wcb_popup_icon_color' ) . ';';
		$css .= '}';
		$css .= '.wcb-coupon-box-small-icon-wrap{';
		$css .= 'background-color:' . $this->settings->get_params( 'wcb_popup_icon_bg_color' ) . ';';
		$css .= 'border-radius:' . $this->settings->get_params( 'wcb_popup_icon_border_radius' ) . 'px;';
		$css .= '}';

		/*button no, thanks*/
		$css .= '.wcb-coupon-box .wcb-md-close-never-reminder-field .wcb-md-close-never-reminder{';
		$css .= 'color:' . $this->settings->get_params( 'wcb_no_thank_button_color' ) . ';';
		$css .= 'background-color:' . $this->settings->get_params( 'wcb_no_thank_button_bg_color' ) . ';';
		$css .= 'border-radius:' . $this->settings->get_params( 'wcb_no_thank_button_border_radius' ) . 'px;';
		$css .= '}';
		wp_add_inline_style( 'woo-coupon-box-basic', $css );
		if ( $this->settings->get_params( 'wcb_recaptcha' ) ) {
			if ( $this->settings->get_params( 'wcb_recaptcha_version' ) == 2 ) {
				?>
                <script src='https://www.google.com/recaptcha/api.js?hl=<?php echo esc_attr( get_locale() ) ?>&render=explicit' async
                        defer></script>
				<?php
			} elseif ( $this->settings->get_params( 'wcb_recaptcha_site_key' ) ) {
				?>
                <script src="https://www.google.com/recaptcha/api.js?hl=<?php echo esc_attr( get_locale() ) ?>&render=<?php echo esc_attr( $this->settings->get_params( 'wcb_recaptcha_site_key' ) ); ?>"></script>
				<?php
			}
		}
	}

	public function generate_css( $selector, $style, $mod_name, $prefix = '', $postfix = '', $echo = false ) {
		$return = '';
		$mod    = $this->settings->get_params( $mod_name );
		if ( ! empty( $mod ) ) {
			$return = sprintf( '%s { %s:%s; }',
				$selector,
				$style,
				$prefix . $mod . $postfix
			);
			if ( $echo ) {
				echo esc_html( $return );
			}
		}

		return $return;
	}

	/**
	 * Show HTML
	 */
	public function wp_footer() {
		echo $this->get_template( 'basic' );
		$hide = '';
		if ( ! $this->settings->get_params( 'wcb_popup_icon_mobile' ) ) {
			$hide = ' wcb-coupon-box-small-icon-hidden-mobile';
		}
		if ( $this->settings->get_params( 'wcb_popup_icon_enable' ) ) {
			if ( isset( $_COOKIE['woo_coupon_box'] ) ) {
				?>
                <div class="wcb-coupon-box-small-icon-wrap wcb-coupon-box-small-icon-position-<?php echo esc_attr( $this->settings->get_params( 'wcb_popup_icon_position' ) );
				echo esc_attr( $hide ); ?>">
                    <div class="wcb-coupon-box-small-icon-container">
                         <span class="wcb-coupon-box-small-icon-close wcb_button_close_icons-cancel" title="
						<?php esc_html_e( 'Do not show again', 'woo-coupon-box' ) ?>"> </span>
                        <span class="wcb-coupon-box-small-icon <?php echo esc_attr( $this->settings->get_params( 'wcb_popup_icon' ) ) ?>"> </span>
                    </div>
                </div>
				<?php
			} else {
				if ( in_array( $this->settings->get_params( 'wcb_popup_icon_position' ), array( 'top-left', 'bottom-left' ) ) ) {
					$hide .= ' wcb-coupon-box-small-icon-hide-left';
				} else {
					$hide .= ' wcb-coupon-box-small-icon-hide-right';
				}
				?>
                <div class="wcb-coupon-box-small-icon-wrap wcb-coupon-box-small-icon-position-<?php echo esc_attr( $this->settings->get_params( 'wcb_popup_icon_position' ) );
				echo esc_attr( $hide ); ?>">
                    <div class="wcb-coupon-box-small-icon-container">
                        <span class="wcb-coupon-box-small-icon-close wcb_button_close_icons-cancel"
                              title="<?php esc_html_e( 'Do not show again', 'woo-coupon-box' ) ?>"> </span>
                        <span class="wcb-coupon-box-small-icon <?php echo esc_attr( $this->settings->get_params( 'wcb_popup_icon' ) ) ?>"> </span>
                    </div>
                </div>
				<?php
			}
		}
	}

	/**
	 * Get template data
	 *
	 * @param $name
	 *
	 * @return string
	 */
	protected function get_template( $name ) {
		$title   = $this->settings->get_params( 'wcb_title' );
		$message = $this->settings->get_params( 'wcb_message' );
		$socials = $this->get_socials();

		$parten  = array(
			'/\{title\}/',
			'/\{message\}/',
			'/\{socials\}/'
		);
		$replace = array(
			esc_html( $title ),
			esc_html( $message ),
			ent2ncr( $socials )
		);

		ob_start();
		require_once VI_WOO_COUPON_BOX_TEMPLATES . $name . '.php';
		$html = ob_get_clean();
		$html = preg_replace( $parten, $replace, $html );

		return ent2ncr( $html );
	}

	/**
	 * Get socials
	 */
	protected function get_socials() {
		$link_target = $this->settings->get_params( 'wcb_social_icons_target' );

		$facebook_url  = $this->settings->get_params( 'wcb_social_icons_facebook_url' );
		$twitter_url   = $this->settings->get_params( 'wcb_social_icons_twitter_url' );
		$pinterest_url = $this->settings->get_params( 'wcb_social_icons_pinterest_url' );
		$instagram_url = $this->settings->get_params( 'wcb_social_icons_instagram_url' );
		$dribbble_url  = $this->settings->get_params( 'wcb_social_icons_dribbble_url' );
		$tumblr_url    = $this->settings->get_params( 'wcb_social_icons_tumblr_url' );
		$google_url    = $this->settings->get_params( 'wcb_social_icons_google_url' );
		$vkontakte_url = $this->settings->get_params( 'wcb_social_icons_vkontakte_url' );
		$linkedin_url  = $this->settings->get_params( 'wcb_social_icons_linkedin_url' );
		$youtube_url   = $this->settings->get_params( 'wcb_social_icons_youtube_url' );

		$facebook_select  = $this->settings->get_params( 'wcb_social_icons_facebook_select' );
		$twitter_select   = $this->settings->get_params( 'wcb_social_icons_twitter_select' );
		$pinterest_select = $this->settings->get_params( 'wcb_social_icons_pinterest_select' );
		$instagram_select = $this->settings->get_params( 'wcb_social_icons_instagram_select' );
		$dribbble_select  = $this->settings->get_params( 'wcb_social_icons_dribbble_select' );
		$tumblr_select    = $this->settings->get_params( 'wcb_social_icons_tumblr_select' );
		$google_select    = $this->settings->get_params( 'wcb_social_icons_google_select' );
		$vkontakte_select = $this->settings->get_params( 'wcb_social_icons_vkontakte_select' );
		$linkedin_select  = $this->settings->get_params( 'wcb_social_icons_linkedin_select' );
		$youtube_select   = $this->settings->get_params( 'wcb_social_icons_youtube_select' );

		$html = '<ul class="wcb-list-socials wcb-list-unstyled" id="wcb-sharing-accounts">';

		if ( $facebook_url ) {
			ob_start();
			?>
            <a <?php if ( $link_target == '_blank' )
				echo esc_attr( 'target=_blank' ) ?>
                    href="//www.facebook.com/<?php echo esc_attr( $facebook_url ) ?>"
                    class="wcb-social-button wcb-facebook"
                    title="<?php esc_html_e( 'Follow Facebook', 'woo-coupon-box' ) ?>">
                <span class="wcb-social-icon <?php echo esc_attr( $facebook_select ) ?>"> </span></a>
			<?php $facebook_html = ob_get_clean();

			$html .= '<li class="wcb-facebook-follow">' . $facebook_html . '</li>';
		}
		if ( $twitter_url ) {
			ob_start();
			?>
            <a <?php if ( $link_target == '_blank' )
				echo esc_attr( 'target=_blank' ) ?>
                    href="//twitter.com/<?php echo esc_attr( $twitter_url ) ?>"
                    class="wcb-social-button wcb-twitter"
                    title="<?php esc_html_e( 'Follow Twitter', 'woo-coupon-box' ) ?>">
                <span class="wcb-social-icon <?php echo esc_attr( $twitter_select ) ?>"> </span>
            </a>
			<?php
			$twitter_html = ob_get_clean();
			$html         .= '<li class="wcb-twitter-follow">' . $twitter_html . '</li>';
		}
		if ( $pinterest_url ) {
			ob_start();
			?>
            <a <?php if ( $link_target == '_blank' )
				echo esc_attr( 'target=_blank' ) ?>
                    href="//www.pinterest.com/<?php echo esc_attr( $pinterest_url ) ?>"
                    class="wcb-social-button wcb-pinterest"
                    title="<?php esc_html_e( 'Follow Pinterest', 'woo-coupon-box' ) ?>">
                <span class="wcb-social-icon <?php echo esc_attr( $pinterest_select ) ?>"> </span>
            </a>
			<?php
			$pinterest_html = ob_get_clean();
			$html           .= '<li class="wcb-pinterest-follow">' . $pinterest_html . '</li>';
		}
		if ( $instagram_url ) {
			ob_start();
			?>
            <a <?php if ( $link_target == '_blank' )
				echo esc_attr( 'target=_blank' ) ?>
                    href="//www.instagram.com/<?php echo esc_attr( $instagram_url ) ?>"
                    class="wcb-social-button wcb-instagram"
                    title="<?php esc_html_e( 'Follow Instagram', 'woo-coupon-box' ) ?>">
                <span class="wcb-social-icon <?php echo esc_attr( $instagram_select ) ?>"> </span>
            </a>
			<?php
			$instagram_html = ob_get_clean();
			$html           .= '<li class="wcb-instagram-follow">' . $instagram_html . '</li>';
		}
		if ( $dribbble_url ) {
			ob_start();
			?>
            <a <?php if ( $link_target == '_blank' )
				echo esc_attr( 'target=_blank' ) ?>
                    href="//dribbble.com/<?php echo esc_attr( $dribbble_url ) ?>"
                    class="wcb-social-button wcb-dribbble"
                    title="<?php esc_html_e( 'Follow Dribbble', 'woo-coupon-box' ) ?>">
                <span class="wcb-social-icon <?php echo esc_attr( $dribbble_select ) ?>"> </span>
            </a>
			<?php
			$dribbble_html = ob_get_clean();
			$html          .= '<li class="wcb-dribbble-follow">' . $dribbble_html . '</li>';
		}
		if ( $tumblr_url ) {
			ob_start();
			?>
            <a <?php if ( $link_target == '_blank' )
				echo esc_attr( 'target=_blank' ) ?>
                    href="//www.tumblr.com/follow/<?php echo esc_attr( $tumblr_url ) ?>"
                    class="wcb-social-button wcb-tumblr"
                    title="<?php esc_html_e( 'Follow Tumblr', 'woo-coupon-box' ) ?>">
                <span class="wcb-social-icon <?php echo esc_attr( $tumblr_select ) ?>"> </span>
            </a>
			<?php
			$tumblr_html = ob_get_clean();
			$html        .= '<li class="wcb-tumblr-follow">' . $tumblr_html . '</li>';
		}
		if ( $google_url ) {
			ob_start();
			?>
            <a <?php if ( $link_target == '_blank' )
				echo esc_attr( 'target=_blank' ) ?>
                    href="//plus.google.com/+<?php echo esc_attr( $google_url ) ?>"
                    class="wcb-social-button wcb-google-plus"
                    title="<?php esc_html_e( 'Follow Google Plus', 'woo-coupon-box' ) ?>">
                <span class="wcb-social-icon <?php echo esc_attr( $google_select ) ?>"> </span>
            </a>
			<?php
			$google_html = ob_get_clean();
			$html        .= '<li class="wcb-google-follow">' . $google_html . '</li>';
		}
		if ( $vkontakte_url ) {
			ob_start();
			?>
            <a <?php if ( $link_target == '_blank' )
				echo esc_attr( 'target=_blank' ) ?>
                    href="//vk.com/<?php echo esc_attr( $vkontakte_url ) ?>"
                    class="wcb-social-button wcb-vk"
                    title="<?php esc_html_e( 'Follow VK', 'woo-coupon-box' ) ?>">
                <span class="wcb-social-icon <?php echo esc_attr( $vkontakte_select ) ?>"> </span>
            </a>
			<?php
			$vkontakte_html = ob_get_clean();
			$html           .= '<li class="wcb-vkontakte-follow">' . $vkontakte_html . '</li>';
		}
		if ( $linkedin_url ) {
			ob_start();
			?>
            <a <?php if ( $link_target == '_blank' )
				echo esc_attr( 'target=_blank' ) ?>
                    href="//www.linkedin.com/in/<?php echo esc_attr( $linkedin_url ) ?>"
                    class="wcb-social-button wcb-linkedin"
                    title="<?php esc_html_e( 'Follow Linkedin', 'woo-coupon-box' ) ?>">
                <span class="wcb-social-icon <?php echo esc_attr( $linkedin_select ) ?>"> </span>
            </a>
			<?php
			$linkedin_html = ob_get_clean();
			$html          .= '<li class="wcb-linkedin-follow">' . $linkedin_html . '</li>';
		}

		if ( $youtube_url ) {
			ob_start();
			?>
            <a <?php if ( $link_target == '_blank' )
				echo esc_attr( 'target=_blank' ) ?>
                    href="<?php echo esc_url_raw( $youtube_url ) ?>"
                    class="wcb-social-button wcb-youtube"
                    title="<?php esc_html_e( 'Follow Youtube', 'woo-coupon-box' ) ?>">
                <span class="wcb-social-icon <?php echo esc_attr( $youtube_select ) ?>"> </span>
            </a>
			<?php
			$youtube_html = ob_get_clean();
			$html         .= '<li class="wcb-youtube-follow">' . $youtube_html . '</li>';
		}

		$html = apply_filters( 'wcb_after_socials_html', $html );
		$html .= '</ul>';

		return $html;
	}

}