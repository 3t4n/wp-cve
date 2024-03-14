<?php

/**
 * Show Coupon box
 * Class VI_WOO_COUPON_BOX_Frontend_Coupon
 *
 */
class VI_WOO_COUPON_BOX_Frontend_Shortcode {
	protected $settings;
	protected $characters_array;

	public function __construct() {
		$this->settings = new VI_WOO_COUPON_BOX_DATA();
		if ( ! $this->settings->get_params( 'wcb_active' ) ) {
			return;
		}
		add_action( 'init', array( $this, 'shortcode_init' ) );
		/*Ajax add email*/
		add_action( 'wp_ajax_nopriv_wcb_widget_subscribe', array( $this, 'subscribe' ) );
		add_action( 'wp_ajax_wcb_widget_subscribe', array( $this, 'subscribe' ) );
	}

	public function shortcode_init() {
		add_shortcode( 'wcb_widget', array( $this, 'register_shortcode' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'shortcode_enqueue_script' ) );
	}

	public function shortcode_enqueue_script() {
		if ( ! wp_script_is( 'wcbwidget-shortcode-style', 'registered' ) ) {
			wp_register_style( 'wcbwidget-shortcode-style', VI_WOO_COUPON_BOX_CSS . 'shortcode-style.css', array(), VI_WOO_COUPON_BOX_VERSION );
		}
		if ( ! wp_script_is( 'wcbwidget-shortcode-script', 'registered' ) ) {
			wp_register_script( 'wcbwidget-shortcode-script', VI_WOO_COUPON_BOX_JS . 'shortcode-script.js', array( 'jquery' ), VI_WOO_COUPON_BOX_VERSION );
		}
	}

	public function register_shortcode( $atts ) {
		extract( shortcode_atts( array(
			'type'                => '1',
			'always_visible'      => '',
			'bt_color'            => '',
			'bt_bg_color'         => '',
			'bt_border_radius'    => '',
			'input_border_radius' => '',
			'show_coupon_code'    => '',
		), $atts ) );
		if ( ! $always_visible ) {
			if ( $this->settings->get_params( 'wcb_disable_login' ) && is_user_logged_in() ) {
				return '';
			}
			if ( isset( $_COOKIE['woo_coupon_box'] ) ) {
				$cookies = explode( ':', sanitize_text_field( wp_unslash( $_COOKIE['woo_coupon_box'] ) ) );
				if ( isset( $cookies[0] ) && in_array( $cookies[0], array( 'subscribed', 'closed' ) ) ) {
					return '';
				}
			}
		}

		if ( ! wp_script_is( 'wcbwidget-shortcode-script' ) ) {
			wp_enqueue_script( 'wcbwidget-shortcode-script' );
			$data = array(
				'ajaxurl'               => admin_url( 'admin-ajax.php' ),
				'wcb_current_time'      => time(),
				'wcb_show_coupon'       => $this->settings->get_params( 'wcb_show_coupon' ),
				'wcb_expire_subscribed' => $this->settings->get_params( 'wcb_expire_subscribed' ) * 86400,
				'wcb_gdpr_checkbox'     => $this->settings->get_params( 'wcb_gdpr_checkbox' ),
			);
			wp_localize_script( 'wcbwidget-shortcode-script', 'wcb_widget_params', $data );
			wp_enqueue_style( 'wcbwidget-shortcode-style' );
			/*button subscribe*/
			$bt_color            = $this->settings->get_params( 'wcb_button_text_color' );
			$bt_bg_color         = $this->settings->get_params( 'wcb_button_bg_color' );
			$bt_border_radius    = $this->settings->get_params( 'wcb_button_border_radius' );
			$input_border_radius = $this->settings->get_params( 'wcb_email_input_border_radius' );
			$css                 = '.woo-coupon-box-widget .wcbwidget-newsletter span.wcbwidget-button{';
			$css                 .= 'color:' . $bt_color . ';';
			$css                 .= 'background-color:' . $bt_bg_color . ';';
			$css                 .= 'border-radius:' . $bt_border_radius . 'px;';
			$css                 .= '}';
			$css                 .= '.woo-coupon-box-widget .wcbwidget-newsletter input.wcbwidget-email{border-radius:' . $input_border_radius . 'px;}';
			wp_add_inline_style( 'wcbwidget-shortcode-style', $css );
		}
		ob_start();
		?>
        <div class="woo-coupon-box-widget woo-coupon-box-widget-type-<?php echo esc_attr( $type ); ?>">
            <div class="wcbwidget-coupon-box-newsletter">

                <div class="wcbwidget-newsletter">
                    <div class="wcbwidget-warning-message"></div>
                    <div class="wcbwidget-newsletter-form">
                        <div class="wcbwidget-input-group">
                            <input type="email"
                                   placeholder="<?php esc_html_e( 'Enter your email address', 'woo-coupon-box' ) ?>"
                                   class="wcbwidget-form-control wcbwidget-email"
                                   name="wcb_email">

                            <div class="wcbwidget-input-group-btn">
                                <span class="wcbwidget-btn wcbwidget-btn-primary wcbwidget-button" data-show_coupon="<?php echo esc_attr( $show_coupon_code ) ?>">
                                    <?php echo esc_html( $this->settings->get_params( 'wcb_button_text' ) ) ?>
                                </span>
                            </div>
                        </div>
                    </div>
					<?php
					if ( $this->settings->get_params( 'wcb_gdpr_checkbox' ) ) {
						?>
                        <div class="wcbwidget-gdpr-field">
                            <input type="checkbox" name="wcb_gdpr_checkbox" class="wcbwidget-gdpr-checkbox">
                            <span class="wcbwidget-gdpr-message"><?php echo wp_kses_post( $this->settings->get_params( 'wcb_gdpr_message' ) ); ?></span>
                        </div>
						<?php
					}
					?>
                </div>

            </div>
        </div>
		<?php
		$return = ob_get_clean();
		$return = str_replace( "\n", '', $return );
		$return = str_replace( "\r", '', $return );
		$return = str_replace( "\t", '', $return );
		$return = str_replace( "\l", '', $return );
		$return = str_replace( "\0", '', $return );

		return $return;
	}

	/**
	 * Process ajax add email
	 */
	public function subscribe() {
		$wcb_enable_mailchimp = $this->settings->get_params( 'wcb_enable_mailchimp' );
		$wcb_email_campaign   = $this->settings->get_params( 'wcb_email_campaign' );
		$email                = filter_input( INPUT_POST, 'email', FILTER_SANITIZE_EMAIL );
		$show_coupon_code     = isset( $_POST['show_coupon'] ) ? sanitize_text_field( wp_unslash( $_POST['show_coupon'] ) ) : '';

		$msg  = array(
			'status'   => '',
			'message'  => '',
			'warning'  => '',
			'code'     => '',
			'thankyou' => '',
		);
		$meta = array(
			'coupon'    => '',
			'campaign'  => '',
			'mailchimp' => '',
		);
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
				$msg['warning'] = esc_html__( '*This email already subscribed. Please enter another email and subscribe!', 'woo-coupon-box' );
				wp_send_json( $msg );
				die;
			}
			wp_reset_postdata();
			// Create post object
			if ( $wcb_enable_mailchimp && class_exists( 'VI_WOO_COUPON_BOX_Admin_Mailchimp' ) ) {
				/*Add mailchimp*/
				$mailchimp      = new VI_WOO_COUPON_BOX_Admin_Mailchimp();
				$mailchimp_list = $this->settings->get_params( 'wcb_mlists' );
				if ( $mailchimp_list != 'non' ) {
					$mailchimp->add_email( $email );
					$meta['mailchimp'] = $mailchimp_list;
				}
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
				}

			} else {
				/*Send a custom coupon code*/
				$this->send_email( $email, '', $code );
			}
			$msg['status'] = 'subscribed';

			if ( $show_coupon_code ) {
				ob_start();
				?>
                <div class="wcb-coupon-treasure-container">
                    <span class="wcb-coupon-scissors"><input type="text" readonly="readonly" value="<?php echo esc_attr( $code ); ?>" class="wcb-coupon-treasure"/></span>
                    <div class="wcb-guide">
						<?php esc_html_e( 'Enter this promo code at checkout page.', 'woocommerce-coupon-box' ) ?>
                    </div>
                </div>

				<?php
				$msg['code'] = ob_get_clean();
			}

			wp_send_json( $msg );
			die;
		} else {
			$msg['status']  = 'invalid';
			$msg['warning'] = esc_html__( '*Invalid email! Please enter a valid email and subscribe.', 'woo-coupon-box' );
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
				$date_expires = empty( $this->settings->get_params( 'wcb_coupon_unique_date_expires' ) ) ? '' : ( ( $this->settings->get_params( 'wcb_coupon_unique_date_expires' ) + 1 ) * 86400 + $today );
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

		$unformatted_price = $price;
		$negative          = $price < 0;
		$price             = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * - 1 : $price ) );
		$price             = apply_filters( 'formatted_woocommerce_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

		if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $decimals > 0 ) {
			$price = wc_trim_zeros( $price );
		}

		$formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, $currency, $price );

		return $formatted_price;
	}

}