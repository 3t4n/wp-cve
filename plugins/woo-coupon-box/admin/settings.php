<?php
/*
Class Name: VI_WOO_COUPON_BOX_Admin_Admin
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_COUPON_BOX_Admin_Settings {
	protected $settings;

	public function __construct() {

		$this->settings = new VI_WOO_COUPON_BOX_DATA();
		add_action( 'admin_menu', array( $this, 'create_options_page' ), 998 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ) );
		add_action( 'admin_init', array( $this, 'save_data_coupon_box' ), 99 );

		/*ajax search*/
		add_action( 'wp_ajax_wcb_search_coupon', array( $this, 'search_coupon' ) );
		add_action( 'wp_ajax_wcb_search_product', array( $this, 'search_product' ) );
		add_action( 'wp_ajax_wcb_search_cate', array( $this, 'search_cate' ) );
		/*preview email*/
		add_action( 'media_buttons', array( $this, 'preview_emails_button' ) );
		add_action( 'wp_ajax_wcb_preview_emails', array( $this, 'preview_emails_ajax' ) );
		add_action( 'admin_footer', array( $this, 'preview_emails_html' ) );
	}

	function preview_emails_html() {
		if ( get_current_screen()->id !== 'wcb_page_woo_coupon_box' ) {
			return;
		}

		?>
        <div class="preview-emails-html-container preview-html-hidden">
            <div class="preview-emails-html-overlay"></div>
            <div class="preview-emails-html"></div>
        </div>
		<?php
	}

	public function preview_emails_button( $editor_id ) {
		if ( function_exists( 'get_current_screen' ) ) {
			if ( get_current_screen()->id === 'wcb_page_woo_coupon_box' ) {
				$editor_ids = array( 'wcb_email_content' );
				if ( in_array( $editor_id, $editor_ids ) ) {
					?>
                    <span class="button wcb-preview-emails-button"
                          data-wcb_language="<?php echo esc_html( str_replace( 'wcb_email_content', '', $editor_id ) ) ?>">
                    <?php esc_html_e( 'Preview emails', 'woo-coupon-box' ) ?>
                </span>
					<?php
				}
			}
		}
	}

	public function preview_emails_ajax() {
		$content              = isset( $_GET['content'] ) ? wp_kses_post( wp_unslash( $_GET['content'] ) ) : '';
		$email_heading        = isset( $_GET['heading'] ) ? sanitize_text_field( wp_unslash( $_GET['heading'] ) ) : '';
		$button_shop_url      = isset( $_GET['button_shop_url'] ) ? sanitize_text_field( wp_unslash( $_GET['button_shop_url'] ) ) : '';
		$button_shop_size     = isset( $_GET['button_shop_size'] ) ? sanitize_text_field( wp_unslash( $_GET['button_shop_size'] ) ) : '';
		$button_shop_color    = isset( $_GET['button_shop_color'] ) ? sanitize_text_field( wp_unslash( $_GET['button_shop_color'] ) ) : '';
		$button_shop_bg_color = isset( $_GET['button_shop_bg_color'] ) ? sanitize_text_field( wp_unslash( $_GET['button_shop_bg_color'] ) ) : '';
		$button_shop_title    = isset( $_GET['button_shop_title'] ) ? sanitize_text_field( wp_unslash( $_GET['button_shop_title'] ) ) : '';

		$button_shop_now = '<a href="' . $button_shop_url . '" target="_blank" style="text-decoration:none;display:inline-block;padding:10px 30px;margin:10px 0;font-size:' . $button_shop_size . 'px;color:' . $button_shop_color . ';background:' . $button_shop_bg_color . ';">' . $button_shop_title . '</a>';
		$coupon_value    = '10%';
		$coupon_code     = 'HAPPY';
		$date_expires    = strtotime( '+30 days' );
		$customer_name   = 'John';
		$content         = str_replace( '{coupon_value}', $coupon_value, $content );
		$content         = str_replace( '{customer_name}', $customer_name, $content );
		$content         = str_replace( '{coupon_code}', '<span style="font-size: x-large;">' . strtoupper( $coupon_code ) . '</span>', $content );
		$content         = str_replace( '{date_expires}', empty( $date_expires ) ? esc_html__( 'never expires', 'woo-coupon-box' ) : date_i18n( 'F d, Y', ( $date_expires ) ), $content );
		$content         = str_replace( '{last_valid_date}', empty( $date_expires ) ? '' : date_i18n( 'F d, Y', ( $date_expires - 86400 ) ), $content );
		$content         = str_replace( '{shop_now}', $button_shop_now, $content );
		$email_heading   = str_replace( '{coupon_value}', $coupon_value, $email_heading );

		// load the mailer class
		$mailer = WC()->mailer();

		// create a new email
		$email = new WC_Email();

		// wrap the content with the email template and then add styles
		$message = apply_filters( 'woocommerce_mail_content', $email->style_inline( $mailer->wrap_message( $email_heading, $content ) ) );

		// print the preview email
		wp_send_json(
			array(
				'html' => $message,
			)
		);
	}

	public static function search_coupon( $x = '', $post_types = 'shop_coupon' ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		ob_start();
		$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );
		if ( empty( $keyword ) ) {
			die();
		}
		$arg            = array(
			'post_status'    => 'publish',
			'post_type'      => $post_types,
			'posts_per_page' => 50,
			's'              => $keyword,
			'meta_query'     => array(
				'ralation' => 'AND',
				array(
					'key'     => 'wlwl_unique_coupon',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key'     => 'kt_unique_coupon',
					'compare' => 'NOT EXISTS'
				),
			)
		);
		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$coupon = new WC_Coupon( get_the_ID() );

				if ( $coupon->get_usage_limit() > 0 && $coupon->get_usage_count() >= $coupon->get_usage_limit() ) {
					continue;
				}

				if ( $coupon->get_amount() < 1 ) {
					continue;
				}

				if ( $coupon->get_date_expires() && time() > $coupon->get_date_expires()->getTimestamp() ) {
					continue;
				}

				$product          = array( 'id' => get_the_ID(), 'text' => get_the_title() );
				$found_products[] = $product;
			}
		}
		wp_reset_postdata();
		wp_send_json( $found_products );
		die;
	}

	public function search_cate() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		ob_start();

		$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );

		if ( empty( $keyword ) ) {
			die();
		}

		$categories = get_terms(
			array(
				'taxonomy' => 'product_cat',
				'orderby'  => 'name',
				'order'    => 'ASC',
				'search'   => $keyword,
				'number'   => 100
			)
		);

		$items = array();
		if ( count( $categories ) ) {
			foreach ( $categories as $category ) {
				$item    = array(
					'id'   => $category->term_id,
					'text' => $category->name
				);
				$items[] = $item;
			}
		}

		wp_send_json( $items );
		die;
	}

	public function search_product( $x = '', $post_types = array( 'product' ) ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		ob_start();

		$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );

		if ( empty( $keyword ) ) {
			die();
		}

		$arg = array(
			'post_status'    => 'publish',
			'post_type'      => $post_types,
			'posts_per_page' => 50,
			's'              => $keyword
		);

		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$prd = wc_get_product( get_the_ID() );

				if ( $prd->has_child() && $prd->is_type( 'variable' ) ) {
					$product_children = $prd->get_children();
					if ( count( $product_children ) ) {
						foreach ( $product_children as $product_child ) {
							if ( woocommerce_version_check() ) {
								$product = array(
									'id'   => $product_child,
									'text' => get_the_title( $product_child )
								);

							} else {
								$child_wc  = wc_get_product( $product_child );
								$get_atts  = $child_wc->get_variation_attributes();
								$attr_name = array_values( $get_atts )[0];
								$product   = array(
									'id'   => $product_child,
									'text' => get_the_title() . ' - ' . $attr_name
								);

							}
							$found_products[] = $product;
						}

					}
				} else {
					$product_id    = get_the_ID();
					$product_title = get_the_title();
					$the_product   = new WC_Product( $product_id );
					if ( ! $the_product->is_in_stock() ) {
						$product_title .= ' (out-of-stock)';
					}
					$product          = array( 'id' => $product_id, 'text' => $product_title );
					$found_products[] = $product;
				}
			}
		}
		wp_send_json( $found_products );
		die;
	}


	public function create_options_page() {
		add_submenu_page(
			'edit.php?post_type=wcb',
			esc_html__( 'Settings', 'woo-coupon-box' ),
			esc_html__( 'Settings', 'woo-coupon-box' ),
			'manage_options',
			'woo_coupon_box',
			array( $this, 'setting_page_woo_coupon_box' )
		);
	}

	public function setting_page_woo_coupon_box() {
		$this->settings = new VI_WOO_COUPON_BOX_DATA();
		?>
        <div class="wrap">
            <h2><?php echo esc_html__( 'Coupon Box for WooCommerce', 'woo-coupon-box' ); ?></h2>
            <p class="">
				<?php printf( "%s <a target='_blank' href='%s'>%s</a> %s",
					esc_html__( 'Please go to', 'woo-coupon-box' ),
					esc_url( admin_url( 'admin.php?page=wc-settings&tab=email#woocommerce_email_base_color' ) ),
					esc_html__( 'WooCommerce Emails settings', 'woo-coupon-box' ),
					esc_html__( 'to edit Email design', 'woo-coupon-box' )
				); ?>
            </p>
            <div class="vi-ui raised">
                <form class="vi-ui form" method="post" action="">
					<?php
					wp_nonce_field( 'woocouponbox_action_nonce', '_woocouponbox_nonce' );
					settings_fields( 'woo-coupon-box' );
					do_settings_sections( 'woo-coupon-box' );
					?>
                    <div class="vi-ui top attached tabular menu">
                        <a class="item active"
                           data-tab="wcb-general"><?php esc_html_e( 'General', 'woo-coupon-box' ) ?></a>

                        <a class="item"
                           data-tab="wcb-coupon"><?php esc_html_e( 'Coupon', 'woo-coupon-box' ) ?></a>
                        <a class="item"
                           data-tab="wcb-email"><?php esc_html_e( 'Email', 'woo-coupon-box' ) ?></a>
                        <a class="item"
                           data-tab="wcb-email-api"><?php esc_html_e( 'Email API', 'woo-coupon-box' ) ?></a>
                        <a class="item"
                           data-tab="wcb-grecaptcha"><?php esc_html_e( 'Google reCAPTCHA', 'woo-coupon-box' ) ?></a>
                        <a class="item"
                           data-tab="wcb-assignpage"><?php esc_html_e( 'Assign', 'woo-coupon-box' ) ?></a>
                        <a class="item"
                           data-tab="wcb-design"><?php esc_html_e( 'Design', 'woo-coupon-box' ) ?></a>

                    </div>
                    <div class="vi-ui bottom attached tab segment wcb-container active" data-tab="wcb-general">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_active"><?php esc_html_e( 'Enable', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="wcb_active"
                                               id="wcb_active" <?php checked( $this->settings->get_params( 'wcb_active' ), 1 ); ?>
                                               value="1">
                                        <label for="wcb_active"><?php esc_html_e( 'Enable', 'woo-coupon-box' ) ?></label>
                                    </div>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_disable_login"><?php esc_html_e( 'Disable for logged-in users', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="wcb_disable_login"
                                               id="wcb_disable_login" <?php checked( $this->settings->get_params( 'wcb_disable_login' ), 1 ); ?>
                                               value="1">
                                        <label for="wcb_disable_login"><span
                                                    class="description"><?php esc_html_e( 'Enable to hide coupon box for all logged-in users', 'woo-coupon-box' ) ?></span></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_multi_language"><?php esc_html_e( 'Enable multi language', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button" target="_blank"
                                       href="https://1.envato.market/DzJ12"><?php esc_html_e( 'Upgrade This Feature', 'woo-coupon-box' ) ?></a>
                                    <label for="wcb_multi_language">
                                        <span class="description"><?php esc_html_e( 'You can use multi language if you are using WPML or Polylang. Make sure you had translated Coupon Box for WooCommerce in all your languages before using this feature.', 'woo-coupon-box' ) ?></span>
                                    </label>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_select_popup"><?php esc_html_e( 'Popup trigger', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <select class="vi-ui fluid dropdown wcb-select-popup" name="wcb_select_popup"
                                            id="wcb_select_popup">
                                        <option value="time"
                                                selected><?php esc_html_e( 'After initial time', 'woo-coupon-box' ) ?></option>
                                        <option value="scroll"
                                                disabled><?php esc_html_e( 'When users scroll-Pro version only', 'woo-coupon-box' ) ?></option>
                                        <option value="exit"
                                                disabled><?php esc_html_e( 'When users are about to exit-Pro version only', 'woo-coupon-box' ) ?></option>
                                        <option value="random"
                                                disabled><?php esc_html_e( 'Random one of these above-Pro version only', 'woo-coupon-box' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-popup-time">
                                <th scope="row">
                                    <label for="wcb_popup_time"><?php esc_html_e( 'Initial time', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="wcb_popup_time" id="wcb_popup_time"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_popup_time' ) ); ?>">

                                    <p class="description"><?php esc_html_e( 'Enter min,max to set initial time random between min and max(seconds).', 'woo-coupon-box' ); ?></i></p>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_on_close"><?php esc_html_e( 'When visitors close coupon box without subscribing', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <select class="vi-ui fluid dropdown" name="wcb_on_close"
                                            id="wcb_on_close">
                                        <option value="hide"
                                                selected><?php esc_html_e( 'Hide coupon box', 'woo-coupon-box' ) ?></option>
                                        <option value="top"
                                                disabled><?php esc_html_e( 'Minimize to top bar-Pro version only', 'woo-coupon-box' ) ?></option>
                                        <option value="bottom"
                                                disabled><?php esc_html_e( 'Minimize to bottom bar-Pro version only', 'woo-coupon-box' ) ?></option>
                                    </select>
                                    <p>
                                        <span class="description"><?php esc_html_e( 'If a visitor subscribes when the coupon box is minimized, only field email is visible and required, all other fields are invisible even if you set them required fields.', 'woo-coupon-box' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_never_reminder_enable"><?php esc_html_e( 'Never reminder if click \'No, thanks\' button', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="wcb_never_reminder_enable"
                                               id="wcb_never_reminder_enable" <?php checked( $this->settings->get_params( 'wcb_never_reminder_enable' ), 1 ); ?>
                                               value="1">
                                        <label for="wcb_never_reminder_enable"><span
                                                    class="description"><?php esc_html_e( 'Enable', 'woo-coupon-box' ) ?></span></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_expire"><?php esc_html_e( 'Subscription reminder if not subscribe', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <div class="inline field">
                                        <input type="number" name="wcb_expire" id="wcb_expire" min="1"
                                               value="<?php echo esc_attr( $this->settings->get_params( 'wcb_expire' ) ); ?>">
                                        <select name="wcb_expire_unit" class="vi-ui dropdown wcb-expire-unit">
                                            <option value="second" <?php selected( $this->settings->get_params( 'wcb_expire_unit' ), 'second' ) ?>><?php esc_html_e( 'Second', 'woo-coupon-box' ) ?></option>
                                            <option value="minute" <?php selected( $this->settings->get_params( 'wcb_expire_unit' ), 'minute' ) ?>><?php esc_html_e( 'Minute', 'woo-coupon-box' ) ?></option>
                                            <option value="hour" <?php selected( $this->settings->get_params( 'wcb_expire_unit' ), 'hour' ) ?>><?php esc_html_e( 'Hour', 'woo-coupon-box' ) ?></option>
                                            <option value="day" <?php selected( $this->settings->get_params( 'wcb_expire_unit' ), 'day' ) ?>><?php esc_html_e( 'Day', 'woo-coupon-box' ) ?></option>
                                        </select>
                                    </div>
                                    <label for="wcb_expire">
                                        <span class="description"><?php esc_html_e( 'Time to show subscription again if visitor does not subscribe', 'woo-coupon-box' ); ?>
                                    </label>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_expire_subscribed"><?php esc_html_e( 'Subscription reminder if subscribe', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <div class="inline field">
                                        <input type="number" name="wcb_expire_subscribed" id="wcb_expire_subscribed"
                                               min="1"
                                               value="<?php echo esc_attr( $this->settings->get_params( 'wcb_expire_subscribed' ) ); ?>">
										<?php esc_html_e( 'Days', 'woo-coupon-box' ); ?>
                                    </div>
                                    <label for="wcb_expire_subscribed">
                                        <span class="description">
                                            <?php esc_html_e( 'Show subscription form again after ', 'woo-coupon-box' ); ?>
                                            <span class="wcb_expire_subscribed_value">
                                                <?php echo esc_html( $this->settings->get_params( 'wcb_expire_subscribed' ) ); ?></span>
	                                        <i><?php esc_html_e( ' days if the visitor subscribes', 'woo-coupon-box' ); ?></i>
                                        </span>
                                    </label>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_email_campaign"><?php esc_html_e( 'Email campaign', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <select class="vi-ui fluid dropdown select-email-campaign" name="wcb_email_campaign"
                                            id="wcb_email_campaign">
										<?php
										$terms_wcb = get_terms( [
											'taxonomy'   => 'wcb_email_campaign',
											'hide_empty' => false,
										] );

										if ( count( $terms_wcb ) ) {
											foreach ( $terms_wcb as $item ) {
												echo "<option value='" . esc_attr( $item->term_id ) . "' " . selected( $this->settings->get_params( 'wcb_email_campaign' ), $item->term_id ) . ">" . esc_html( $item->name ) . "</option>";
											}
										}

										?>
                                    </select>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div class="vi-ui bottom attached tab segment wcb-container" data-tab="wcb-coupon">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_coupon_select"><?php esc_html_e( 'Select coupon', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <select class="vi-ui fluid dropdown wcb-coupon-select" name="wcb_coupon_select"
                                            id="wcb_coupon_select">
                                        <option value="unique" <?php selected( $this->settings->get_params( 'wcb_coupon_select' ), 'unique' ) ?>><?php esc_html_e( 'Unique coupon', 'woo-coupon-box' ) ?></option>
                                        <option value="existing" <?php selected( $this->settings->get_params( 'wcb_coupon_select' ), 'existing' ) ?>><?php esc_html_e( 'Existing coupon', 'woo-coupon-box' ) ?></option>
                                        <option value="custom" <?php selected( $this->settings->get_params( 'wcb_coupon_select' ), 'custom' ) ?>><?php esc_html_e( 'Custom', 'woo-coupon-box' ) ?></option>
                                        <option value="non" <?php selected( $this->settings->get_params( 'wcb_coupon_select' ), 'non' ) ?>><?php esc_html_e( 'Do not use coupon', 'woo-coupon-box' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-coupon-email-restriction">
                                <th scope="row">
                                    <label for="wcb_coupon_unique_email_restrictions"><?php esc_html_e( 'Email restriction', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="wcb_coupon_unique_email_restrictions"
                                               id="wcb_coupon_unique_email_restrictions" <?php checked( $this->settings->get_params( 'wcb_coupon_unique_email_restrictions' ), 1 ); ?>
                                               value="1">
                                        <label for="wcb_coupon_unique_email_restrictions"><span
                                                    class="description"><?php esc_html_e( 'Enable to make coupon usable for received email only', 'woo-coupon-box' ) ?></span></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-coupon-existing">
                                <th scope="row">
                                    <label for="wcb_coupon"><?php esc_html_e( 'Existing coupon', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <select class="search-coupon" name="wcb_coupon" id="wcb_coupon">
										<?php
										if ( $this->settings->get_params( 'wcb_coupon' ) ) {
											$coupon = new WC_Coupon( $this->settings->get_params( 'wcb_coupon' ) );
											?>
                                            <option value="<?php echo esc_attr( $this->settings->get_params( 'wcb_coupon' ) ) ?>"
                                                    selected>
												<?php echo esc_html( $coupon->get_code() ); ?>
                                            </option>
											<?php
										}
										?>
                                    </select>
                                </td>
                            </tr>

                            <tr valign="top" class="wcb-coupon-custom">
                                <th scope="row">
                                    <label for="wcb_coupon_custom"><?php esc_html_e( 'Custom', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="wcb_coupon_custom" id="wcb_coupon_custom"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_coupon_custom' ) ); ?>">
                                </td>
                            </tr>

                            <tr valign="top" class="wcb-coupon-unique">
                                <th scope="row">
                                    <label for="wcb_coupon_unique_description"><?php esc_html_e( 'Description', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <textarea name="wcb_coupon_unique_description"
                                              id="wcb_coupon_unique_description"><?php echo wp_kses_post( $this->settings->get_params( 'wcb_coupon_unique_description' ) ) ?></textarea>
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-coupon-unique">
                                <th scope="row">
                                    <label for="wcb_coupon_unique_discount_type"><?php esc_html_e( 'Discount type', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <select class="vi-ui fluid dropdown" name="wcb_coupon_unique_discount_type">
                                        <option value="percent" <?php selected( $this->settings->get_params( 'wcb_coupon_unique_discount_type' ), 'percent' ) ?>><?php esc_html_e( 'Percentage discount', 'woo-coupon-box' ) ?></option>
                                        <option value="fixed_cart" <?php selected( $this->settings->get_params( 'wcb_coupon_unique_discount_type' ), 'fixed_cart' ) ?>><?php esc_html_e( 'Fixed cart discount', 'woo-coupon-box' ) ?></option>
                                        <option value="fixed_product" <?php selected( $this->settings->get_params( 'wcb_coupon_unique_discount_type' ), 'fixed_product' ) ?>><?php esc_html_e( 'Fixed product discount', 'woo-coupon-box' ) ?></option>
                                    </select>
                                </td>
                            </tr>

                            <tr valign="top" class="wcb-coupon-unique">
                                <th scope="row">
                                    <label for="wcb_coupon_unique_prefix"><?php esc_html_e( 'Coupon code prefix', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="wcb_coupon_unique_prefix" id="wcb_coupon_unique_prefix"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_coupon_unique_prefix' ) ); ?>">
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-coupon-unique">
                                <th scope="row">
                                    <label for="wcb_coupon_unique_amount"><?php esc_html_e( 'Coupon amount', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="wcb_coupon_unique_amount" id="wcb_coupon_unique_amount"
                                           min="0" step="0.01"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_coupon_unique_amount' ) ) ?>">
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-coupon-unique">
                                <th scope="row">
                                    <label for="wcb_coupon_unique_free_shipping"><?php esc_html_e( 'Allow free shipping', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="wcb_coupon_unique_free_shipping"
                                               id="wcb_coupon_unique_free_shipping" <?php checked( $this->settings->get_params( 'wcb_coupon_unique_free_shipping' ), 1 ); ?>
                                               value="1">
                                        <label for="wcb_coupon_unique_free_shipping">
                                            <span class="description">
                                                <?php
                                                printf( '%s <a href="https://docs.woocommerce.com/document/free-shipping/" target="_blank">%s</a> %s',
	                                                esc_html__( 'Enable if the coupon grants free shipping. A free shipping method must be enabled in your shipping zone and be set to require "a valid free shipping coupon" (see the', 'woo-coupon-box' ),
	                                                esc_html__( 'free shipping method', 'woo-coupon-box' ),
	                                                esc_html__( 'setting).', 'woo-coupon-box' ) );
                                                ?>
                                            </span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-coupon-unique">
                                <th scope="row">
                                    <label for="wcb_coupon_unique_date_expires"><?php esc_html_e( 'Expires after(days)', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="wcb_coupon_unique_date_expires"
                                           id="wcb_coupon_unique_date_expires" min="0"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_coupon_unique_date_expires' ) ) ?>">
                                </td>
                            </tr>

                            <tr valign="top" class="wcb-coupon-unique">
                                <th scope="row">
                                    <label for="wcb_coupon_unique_minimum_amount"><?php esc_html_e( 'Minimum spend', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="wcb_coupon_unique_minimum_amount"
                                           id="wcb_coupon_unique_minimum_amount" min="0"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_coupon_unique_minimum_amount' ) ) ?>">
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-coupon-unique">
                                <th scope="row">
                                    <label for="wcb_coupon_unique_maximum_amount"><?php esc_html_e( 'Maximum spend', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="wcb_coupon_unique_maximum_amount"
                                           id="wcb_coupon_unique_maximum_amount" min="0"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_coupon_unique_maximum_amount' ) ) ?>">
                                </td>
                            </tr>


                            <tr valign="top" class="wcb-coupon-unique">
                                <th scope="row">
                                    <label for="wcb_coupon_unique_individual_use"><?php esc_html_e( 'Individual use only', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="wcb_coupon_unique_individual_use"
                                               id="wcb_coupon_unique_individual_use" <?php checked( $this->settings->get_params( 'wcb_coupon_unique_individual_use' ), 1 ); ?>
                                               value="1">
                                        <label for="wcb_coupon_unique_individual_use"><span
                                                    class="description"><?php esc_html_e( 'Enable if the coupon cannot be used in conjunction with other coupons.', 'woo-coupon-box' ) ?></span></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-coupon-unique">
                                <th scope="row">
                                    <label for="wcb_coupon_unique_exclude_sale_items"><?php esc_html_e( 'Exclude sale items', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="wcb_coupon_unique_exclude_sale_items"
                                               id="wcb_coupon_unique_exclude_sale_items" <?php checked( $this->settings->get_params( 'wcb_coupon_unique_exclude_sale_items' ), 1 ); ?>
                                               value="1">
                                        <label for="wcb_coupon_unique_exclude_sale_items"><span
                                                    class="description"><?php esc_html_e( 'Enable if the coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are items in the cart that are not on sale.', 'woo-coupon-box' ) ?></span></label>
                                    </div>
                                </td>
                            </tr>


                            <tr valign="top" class="wcb-coupon-unique">
                                <th>
                                    <label for="wcb_coupon_unique_product_ids"><?php esc_html_e( 'Products', 'woo-coupon-box' ); ?></label>
                                </th>
                                <td>
                                    <select name="wcb_coupon_unique_product_ids[]" id="wcb_coupon_unique_product_ids"
                                            class="search-product" multiple="multiple">
										<?php
										if ( is_array( $this->settings->get_params( 'wcb_coupon_unique_product_ids' ) ) && count( $this->settings->get_params( 'wcb_coupon_unique_product_ids' ) ) ) {
											foreach ( $this->settings->get_params( 'wcb_coupon_unique_product_ids' ) as $product_id ) {
												$product = wc_get_product( $product_id );
												?>
                                                <option value="<?php echo esc_attr( $product_id ) ?>" selected>
													<?php
													if ( $product ) {
														echo esc_html( $product->get_title() );
													} else {
														printf( "%s (ID=%s)", esc_html__( 'Note*: Not found product', 'woo-coupon-box' ), esc_html( $product_id ) );
													}
													?>
                                                </option>
												<?php
											}
										}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-coupon-unique">
                                <th>
                                    <label for="wcb_coupon_unique_excluded_product_ids"><?php esc_html_e( 'Exclude products', 'woo-coupon-box' ); ?></label>
                                </th>
                                <td>
                                    <select name="wcb_coupon_unique_excluded_product_ids[]"
                                            id="wcb_coupon_unique_excluded_product_ids" class="search-product"
                                            multiple="multiple">
										<?php
										if ( is_array( $this->settings->get_params( 'wcb_coupon_unique_excluded_product_ids' ) ) && count( $this->settings->get_params( 'wcb_coupon_unique_excluded_product_ids' ) ) ) {
											foreach ( $this->settings->get_params( 'wcb_coupon_unique_excluded_product_ids' ) as $product_id ) {
												$product = wc_get_product( $product_id );
												?>
                                                <option value="<?php echo esc_attr( $product_id ) ?>" selected>
													<?php
													if ( $product ) {
														echo esc_html( $product->get_title() );
													} else {
														printf( "%s (ID = %s)", esc_html__( 'Note*: Not found product', 'woo-coupon-box' ), esc_html( $product_id ) );
													}
													?>
                                                </option>
												<?php
											}
										}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-coupon-unique">
                                <th>
                                    <label for="wcb_coupon_unique_product_categories"><?php esc_html_e( 'Categories', 'woo-coupon-box' ); ?></label>
                                </th>
                                <td>
                                    <select name="wcb_coupon_unique_product_categories[]"
                                            id="wcb_coupon_unique_product_categories" class="search-category"
                                            multiple="multiple">
										<?php

										if ( is_array( $this->settings->get_params( 'wcb_coupon_unique_product_categories' ) ) && count( $this->settings->get_params( 'wcb_coupon_unique_product_categories' ) ) ) {
											foreach ( $this->settings->get_params( 'wcb_coupon_unique_product_categories' ) as $category_id ) {
												$category = get_term( $category_id );
												?>
                                                <option value="<?php echo esc_attr( $category_id ) ?>" selected>
													<?php
													if ( $category ) {
														echo esc_html( $category->name );
													} else {
														printf( "%s (ID = %s)", esc_html__( 'Note*: Not found category', 'woo-coupon-box' ), esc_html( $category_id ) );
													}
													?>
                                                </option>
												<?php
											}
										}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-coupon-unique">
                                <th>
                                    <label for="wcb_coupon_unique_excluded_product_categories"><?php esc_html_e( 'Exclude categories', 'woo-coupon-box' ); ?></label>
                                </th>
                                <td>
                                    <select name="wcb_coupon_unique_excluded_product_categories[]"
                                            id="wcb_coupon_unique_excluded_product_categories" class="search-category"
                                            multiple="multiple">
										<?php

										if ( is_array( $this->settings->get_params( 'wcb_coupon_unique_excluded_product_categories' ) ) && count( $this->settings->get_params( 'wcb_coupon_unique_excluded_product_categories' ) ) ) {
											foreach ( $this->settings->get_params( 'wcb_coupon_unique_excluded_product_categories' ) as $category_id ) {
												$category = get_term( $category_id );
												?>
                                                <option value="<?php echo esc_attr( $category_id ) ?>" selected>
													<?php
													if ( $category ) {
														echo esc_html( $category->name );
													} else {
														printf( "%s (ID = %s)", esc_html__( 'Note*: Not found category', 'woo-coupon-box' ), esc_html( $category_id ) );
													}
													?>
                                                </option>
												<?php
											}
										}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-coupon-unique">
                                <th scope="row">
                                    <label for="wcb_coupon_unique_usage_limit"><?php esc_html_e( 'Usage limit per coupon', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="wcb_coupon_unique_usage_limit"
                                           id="wcb_coupon_unique_usage_limit" min="0"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_coupon_unique_usage_limit' ) ) ?>">
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-coupon-unique">
                                <th scope="row">
                                    <label for="wcb_coupon_unique_limit_usage_to_x_items"><?php esc_html_e( 'Limit usage to X items', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="wcb_coupon_unique_limit_usage_to_x_items"
                                           id="wcb_coupon_unique_limit_usage_to_x_items"
                                           min="0"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_coupon_unique_limit_usage_to_x_items' ) ) ?>">
                                </td>
                            </tr>
                            <tr valign="top" class="wcb-coupon-unique">
                                <th scope="row">
                                    <label for="wcb_coupon_unique_usage_limit_per_user"><?php esc_html_e( 'Usage limit per user', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="wcb_coupon_unique_usage_limit_per_user"
                                           id="wcb_coupon_unique_usage_limit_per_user" min="0"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_coupon_unique_usage_limit_per_user' ) ) ?>">
                                </td>
                            </tr>

                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment wcb-container" data-tab="wcb-email">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_email_subject"><?php esc_html_e( 'Email subject', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="wcb_email_subject" id="wcb_email_subject"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_email_subject' ) ) ?>">
                                    <p>{coupon_value}
                                        - <?php esc_html_e( 'The value of coupon, can be percentage or currency amount depending on coupon type' ) ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_email_heading"><?php esc_html_e( 'Email heading', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="wcb_email_heading" id="wcb_email_heading"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_email_heading' ) ) ?>">
                                    <p>{coupon_value}
                                        - <?php esc_html_e( 'The value of coupon, can be percentage or currency amount depending on coupon type' ) ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_email_content"><?php esc_html_e( 'Email content', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
									<?php
									wp_editor( wp_unslash( $this->settings->get_params( 'wcb_email_content' ) ), 'wcb_email_content', array( 'editor_height' => 300 ) );
									?>
                                </td>
                            </tr>
                            <tr>
                                <th></th>
                                <td>
                                    <p>{coupon_value}
                                        - <?php esc_html_e( 'The value of coupon, can be percentage or currency amount depending on coupon type' ) ?></p>
                                    <p>{coupon_code}
                                        - <?php esc_html_e( 'The code of coupon that will be sent to your subscribers' ) ?></p>
                                    <p>{date_expires}
                                        - <?php esc_html_e( 'From the date that given coupon will no longer be available' ) ?></p>
                                    <p>{last_valid_date}
                                        - <?php esc_html_e( 'That last day that coupon is valid' ) ?></p>
                                    <p>{site_title}
                                        - <?php esc_html_e( 'The title of your website' ) ?></p>
                                    <p>{shop_now}
                                        - <?php esc_html_e( 'Button ' );

										$url = $this->settings->get_params( 'wcb_button_shop_now_url' ) ? $this->settings->get_params( 'wcb_button_shop_now_url' ) : get_bloginfo( 'url' );
										printf( '<a class="wcb-button-shop-now" target="_blank" href="%s"  target="_blank" style="text-decoration:none;display:inline-block;padding:10px 30px;margin:10px 0;font-size:%spx;color:%s;background:%s;">%s</a>',
											esc_url( $url ),
											esc_attr( $this->settings->get_params( 'wcb_button_shop_now_size' ) ),
											esc_attr( $this->settings->get_params( 'wcb_button_shop_now_color' ) ),
											esc_attr( $this->settings->get_params( 'wcb_button_shop_now_bg_color' ) ),
											esc_attr( $this->settings->get_params( 'wcb_button_shop_now_title' ) )
										);
										?>
                                    </p>

                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_button_shop_now_title"><?php esc_html_e( 'Button "Shop now" title', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="wcb_button_shop_now_title" id="wcb_button_shop_now_title"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_button_shop_now_title' ) ) ?>">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_button_shop_now_url"><?php esc_html_e( 'Button "Shop now" URL', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="wcb_button_shop_now_url" id="wcb_button_shop_now_url"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_button_shop_now_url' ) ) ?>">
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_button_shop_now_color"><?php esc_html_e( 'Button "Shop now" color', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="wcb_button_shop_now_color" id="wcb_button_shop_now_color"
                                           class="color-picker"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_button_shop_now_color' ) ) ?>"
                                           style="background-color: <?php echo esc_attr( $this->settings->get_params( 'wcb_button_shop_now_color' ) ) ?>;">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_button_shop_now_bg_color"><?php esc_html_e( 'Button "Shop now" background color', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="wcb_button_shop_now_bg_color"
                                           id="wcb_button_shop_now_bg_color" class="color-picker"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_button_shop_now_bg_color' ) ) ?>"
                                           style="background-color: <?php echo esc_attr( $this->settings->get_params( 'wcb_button_shop_now_bg_color' ) ) ?>;">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_button_shop_now_size"><?php esc_html_e( 'Button "Shop now" font size(px)', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="wcb_button_shop_now_size" id="wcb_button_shop_now_size"
                                           min="1"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_button_shop_now_size' ) ) ?>">
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment wcb-container" data-tab="wcb-email-api">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_enable_mailchimp"><?php esc_html_e( 'Mailchimp', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="wcb_enable_mailchimp"
                                               id="wcb_enable_mailchimp" <?php checked( $this->settings->get_params( 'wcb_enable_mailchimp' ), 1 ); ?>
                                               value="1">
                                        <label for="wcb_enable_mailchimp"><?php esc_html_e( 'Enable', 'woo-coupon-box' ) ?></label>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'Turn on to use MailChimp system', 'woo-coupon-box' ) ?></p>
									<?php
									if ( ! class_exists( 'VI_WOO_COUPON_BOX_Admin_Mailchimp' ) ) {
										echo "<p class='description'>" . esc_html__( 'Please install ', 'woo-coupon-box' ) . "<a href='https://wordpress.org/plugins/mailchimp-for-wp/'>" . esc_html__( 'Mailchimp for WordPress', 'woo-coupon-box' ) . "</a>" . esc_html__( ' to using this feature.', 'woo-coupon-box' ) . "</p>";
									}
									?>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_api"></label><?php esc_html_e( 'Mailchimp API Key', 'woo-coupon-box' ) ?>
                                </th>
                                <td>
                                    <input type="text" id="wcb_api" name="wcb_api"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_api' ) ); ?>">

                                    <p class="description"><?php esc_html_e( ' The API key for connecting with your MailChimp account. Get your API key ', 'woo-coupon-box' ) ?>
                                        <a href="https://admin.mailchimp.com/account/api"><?php esc_html_e( 'here', 'woo-coupon-box' ) ?></a>.
                                    </p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_mlists"><?php esc_html_e( 'Mailchimp lists', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <select class="vi-ui fluid dropdown select-who" name="wcb_mlists" id="wcb_mlists">
										<?php
										if ( class_exists( 'VI_WOO_COUPON_BOX_Admin_Mailchimp' ) ) {
											$mailchimp  = new VI_WOO_COUPON_BOX_Admin_Mailchimp();
											$mail_lists = $mailchimp->get_lists();

											if ( ! $mail_lists ) {
												$mail_lists = array();
											}

											if ( count( $mail_lists ) ) {
												foreach ( $mail_lists as $mail_list_id => $mail_list ) {
//													$id = $mail_list->id;
													printf( '<option value="%s" %s>%s</option>', esc_attr( $mail_list_id ), selected( $this->settings->get_params( 'wcb_mlists' ), $mail_list_id ), esc_html( $mail_list ) );
												}
											}
										}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td></td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">
                                    <label><?php esc_html_e( 'Active Campaign', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button" target="_blank"
                                       href="https://1.envato.market/DzJ12"><?php esc_html_e( 'Upgrade This Feature', 'woo-coupon-box' ) ?></a>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">
                                    <label><?php esc_html_e( 'SendGrid', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button" target="_blank"
                                       href="https://1.envato.market/DzJ12"><?php esc_html_e( 'Upgrade This Feature', 'woo-coupon-box' ) ?></a>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">
                                    <label><?php esc_html_e( 'Hubspot', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button" target="_blank"
                                       href="https://1.envato.market/DzJ12"><?php esc_html_e( 'Upgrade This Feature', 'woo-coupon-box' ) ?></a>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">
                                    <label><?php esc_html_e( 'Klaviyo', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button" target="_blank"
                                       href="https://1.envato.market/DzJ12"><?php esc_html_e( 'Upgrade This Feature', 'woo-coupon-box' ) ?></a>
                                </td>
                            </tr>

                            <tr valign="top">
                                <th scope="row">
                                    <label><?php esc_html_e( 'Sendinblue', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button" target="_blank"
                                       href="https://1.envato.market/DzJ12"><?php esc_html_e( 'Upgrade This Feature', 'woo-coupon-box' ) ?></a>
                                </td>
                            </tr>
                        </table>
                    </div>

                    <div class="vi-ui bottom attached tab segment wcb-container" data-tab="wcb-grecaptcha">
                        <table class="form-table">
                            <tr align="top">
                                <th>
                                    <label for="wcb_recaptcha"><?php esc_html_e( 'Enable', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="wcb_recaptcha" id="wcb_recaptcha" value="1"
                                               tabindex="0" <?php checked( $this->settings->get_params( 'wcb_recaptcha' ), '1' ); ?>>
                                    </div>
                                </td>
                            </tr>
                            <tr align="top">
                                <th>
                                    <label for="wcb_recaptcha_version"><?php esc_html_e( 'Version', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <select name="wcb_recaptcha_version" id="wcb_recaptcha_version"
                                            class="vi-ui fluid dropdown wcb_recaptcha_version">
                                        <option value="2" <?php selected( $this->settings->get_params( 'wcb_recaptcha_version' ), '2' ) ?>><?php esc_html_e( 'reCAPTCHA v2', 'woo-coupon-box' ) ?></option>
                                        <option value="3" <?php selected( $this->settings->get_params( 'wcb_recaptcha_version' ), '3' ) ?>><?php esc_html_e( 'reCAPTCHA v3', 'woo-coupon-box' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr align="top">
                                <th>
                                    <label for="wcb_recaptcha_site_key"><?php esc_html_e( 'Site key', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="wcb_recaptcha_site_key"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_recaptcha_site_key' ) ) ?>">
                                </td>
                            </tr>
                            <tr align="top">
                                <th>
                                    <label for="wcb_recaptcha_secret_key"><?php esc_html_e( 'Secret key', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="wcb_recaptcha_secret_key"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_recaptcha_secret_key' ) ) ?>">
                                </td>
                            </tr>
                            <tr align="top" class="wcb-recaptcha-v2-wrap"
                                style="<?php echo $this->settings->get_params( 'wcb_recaptcha_version' ) == 2 ? '' : 'display:none;'; ?>">
                                <th>
                                    <label for="wcb_recaptcha_secret_theme"><?php esc_html_e( 'Theme', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <select name="wcb_recaptcha_secret_theme" id="wcb_recaptcha_secret_theme"
                                            class="vi-ui fluid dropdown wcb_recaptcha_secret_theme">
                                        <option value="dark" <?php selected( $this->settings->get_params( 'wcb_recaptcha_secret_theme' ), 'dark' ) ?>><?php esc_html_e( 'Dark', 'woo-coupon-box' ) ?></option>
                                        <option value="light" <?php selected( $this->settings->get_params( 'wcb_recaptcha_secret_theme' ), 'light' ) ?>><?php esc_html_e( 'Light', 'woo-coupon-box' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th>
                                    <label for=""><?php esc_html_e( 'Guide', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <div>
                                        <strong class="wcb-recaptcha-v2-wrap"
                                                style="<?php echo $this->settings->get_params( 'wcb_recaptcha_version' ) == 2 ? '' : 'display:none;'; ?>">
											<?php esc_html_e( 'Get Google reCAPTCHA V2 Site and Secret key', 'woo-coupon-box' ) ?>
                                        </strong>
                                        <strong class="wcb-recaptcha-v3-wrap"
                                                style="<?php echo $this->settings->get_params( 'wcb_recaptcha_version' ) == 3 ? '' : 'display:none;'; ?>">
											<?php esc_html_e( 'Get Google reCAPTCHA V3 Site and Secret key', 'woo-coupon-box' ) ?>
                                        </strong>
                                        <ul>
                                            <li>
												<?php printf( '%s <a target="_blank" href="http://www.google.com/recaptcha/admin">%s</a> %s',
													esc_html__( '1, Visit', 'woo-coupon-box' ),
													esc_html__( 'page', 'woo-coupon-box' ),
													esc_html__( 'to sign up for an API key pair with your Gmail account', 'woo-coupon-box' )
												) ?>
                                            </li>

                                            <li class="wcb-recaptcha-v2-wrap"
                                                style="<?php echo $this->settings->get_params( 'wcb_recaptcha_version' ) == 2 ? '' : 'display:none;'; ?>">
												<?php esc_html_e( '2, Choose reCAPTCHA v2 checkbox ', 'woo-coupon-box' ) ?>
                                            </li>
                                            <li class="wcb-recaptcha-v3-wrap"
                                                style="<?php echo $this->settings->get_params( 'wcb_recaptcha_version' ) == 3 ? '' : 'display:none;'; ?>">
												<?php esc_html_e( '2, Choose reCAPTCHA v3', 'woo-coupon-box' ) ?>
                                            </li>
                                            <li><?php esc_html_e( '3, Fill in authorized domains', 'woo-coupon-box' ) ?></li>
                                            <li><?php esc_html_e( '4, Accept terms of service and click Register button', 'woo-coupon-box' ) ?></li>
                                            <li><?php esc_html_e( '5, Copy and paste the site and secret key into the above field', 'woo-coupon-box' ) ?></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment wcb-container" data-tab="wcb-assignpage">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_assign_home"><?php esc_html_e( 'Home Page', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="wcb_assign_home"
                                               id="wcb_assign_home" <?php checked( $this->settings->get_params( 'wcb_assign_home' ), 1 ); ?>
                                               value="1">
                                        <label for="wcb_assign_home"><?php esc_html_e( 'Enable', 'woo-coupon-box' ) ?></label>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'Turn on to show coupon box only on Home page', 'woo-coupon-box' ) ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="wcb_assign"><?php esc_html_e( 'Assign Page', 'woo-coupon-box' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" id="wcb_assign" name="wcb_assign"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wcb_assign' ) ) ?>"
                                           placeholder="<?php esc_html_e( 'Ex: !is_page(array(123,41,20))', 'woo-coupon-box' ) ?>">

                                    <p class="description"><?php esc_html_e( 'Let you control on which pages coupon box will appear using ', 'woo-coupon-box' ) ?>
                                        <a href="http://codex.wordpress.org/Conditional_Tags"><?php esc_html_e( 'WP\'s conditional tags', 'woo-coupon-box' ) ?></a>
                                    </p>
                                    <p class="description">
                                        <strong>*</strong><?php esc_html_e( '"Home page" option above must be disabled to run these conditional tags.', 'woo-coupon-box' ) ?>
                                    </p>
                                    <p class="description"><?php esc_html_e( 'Use ', 'woo-coupon-box' ); ?><strong>is_cart()</strong><?php esc_html_e( ' to show only on cart page', 'woo-coupon-box' ) ?>
                                    </p>
                                    <p class="description"><?php esc_html_e( 'Use ', 'woo-coupon-box' ); ?><strong>is_checkout()</strong><?php esc_html_e( ' to show only on checkout page', 'woo-coupon-box' ) ?>
                                    </p>
                                    <p class="description"><?php esc_html_e( 'Use ', 'woo-coupon-box' ); ?><strong>is_product_category()</strong><?php esc_html_e( 'to show only on WooCommerce category page', 'woo-coupon-box' ) ?>
                                    </p>
                                    <p class="description"><?php esc_html_e( 'Use ', 'woo-coupon-box' ); ?><strong>is_shop()</strong><?php esc_html_e( ' to show only on WooCommerce shop page', 'woo-coupon-box' ) ?>
                                    </p>
                                    <p class="description"><?php esc_html_e( 'Use ', 'woo-coupon-box' ); ?><strong>is_product()</strong><?php esc_html_e( ' to show only on WooCommerce single product page', 'woo-coupon-box' ) ?>
                                    </p>
                                    <p class="description">
                                        <strong>**</strong><?php esc_html_e( 'Combining 2 or more conditionals using || to show coupon box if 1 of the conditionals matched. e.g use ', 'woo-coupon-box' ); ?>
                                        <strong>is_cart() ||
                                            is_checkout()</strong><?php esc_html_e( ' to show only on cart page and checkout page', 'woo-coupon-box' ) ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment wcb-container" data-tab="wcb-design">
                        <div><a href="customize.php?autofocus[panel]=wcb_coupon_box_design"
                                target="_blank"><?php esc_html_e( 'Go to design now', 'woo-coupon-box' ) ?></a>
                        </div>
                    </div>

                    <p>
                        <input type="submit" name="wcb_save_data"
                               value="<?php esc_html_e( 'Save', 'woo-coupon-box' ) ?>" class="vi-ui primary button">
                    </p>
                </form>
            </div>

        </div>
		<?php
		do_action( 'villatheme_support_woo-coupon-box' );
	}

	public function admin_enqueue_script() {
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
		if ( $page == 'woo_coupon_box' ) {
			global $wp_scripts;
			$scripts = $wp_scripts->registered;
			//			print_r($scripts);
			foreach ( $scripts as $k => $script ) {
				preg_match( '/^\/wp-/i', $script->src, $result );
				if ( count( array_filter( $result ) ) < 1 ) {
					wp_dequeue_script( $script->handle );
				}
			}
			// style
			wp_enqueue_style( 'woo-coupon-box-form', VI_WOO_COUPON_BOX_CSS . 'form.min.css', '', VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_style( 'woo-coupon-box-button', VI_WOO_COUPON_BOX_CSS . 'button.min.css', '', VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_style( 'woo-coupon-box-dropdown', VI_WOO_COUPON_BOX_CSS . 'dropdown.min.css', '', VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_style( 'woo-coupon-box-checkbox', VI_WOO_COUPON_BOX_CSS . 'checkbox.min.css', '', VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_style( 'woo-coupon-box-icon', VI_WOO_COUPON_BOX_CSS . 'icon.min.css', '', VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_style( 'woo-coupon-box-transition', VI_WOO_COUPON_BOX_CSS . 'transition.min.css', '', VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_style( 'woo-coupon-box-tab', VI_WOO_COUPON_BOX_CSS . 'tab.css', '', VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_style( 'woo-coupon-box-segment', VI_WOO_COUPON_BOX_CSS . 'segment.min.css', '', VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_style( 'woo-coupon-box-menu', VI_WOO_COUPON_BOX_CSS . 'menu.min.css', '', VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_style( 'woo-coupon-box-select2', VI_WOO_COUPON_BOX_CSS . 'select2.min.css', '', VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_style( 'woo-coupon-box-admin', VI_WOO_COUPON_BOX_CSS . 'wcb-admin.css', '', VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_style( 'woocommerce-coupon-villatheme-support', VI_WOO_COUPON_BOX_CSS . 'villatheme-support.css', '', VI_WOO_COUPON_BOX_VERSION );

			//script
			/*Color picker*/
			wp_enqueue_script( 'iris', admin_url( 'js/iris.min.js' ), [
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			], VI_WOO_COUPON_BOX_VERSION, 1 );
			wp_enqueue_script( 'woo-coupon-box-form', VI_WOO_COUPON_BOX_JS . 'form.min.js', [ 'jquery' ], VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_script( 'woo-coupon-box-checkbox', VI_WOO_COUPON_BOX_JS . 'checkbox.min.js', [ 'jquery' ], VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_script( 'woo-coupon-box-dropdown', VI_WOO_COUPON_BOX_JS . 'dropdown.min.js', [ 'jquery' ], VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_script( 'woo-coupon-box-transition', VI_WOO_COUPON_BOX_JS . 'transition.min.js', [ 'jquery' ], VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_script( 'woo-coupon-box-tab', VI_WOO_COUPON_BOX_JS . 'tab.js', [ 'jquery' ], VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_script( 'woo-coupon-box-address', VI_WOO_COUPON_BOX_JS . 'jquery.address-1.6.min.js', [ 'jquery' ], VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_script( 'woo-coupon-box-select2', VI_WOO_COUPON_BOX_JS . 'select2.js', [ 'jquery' ], VI_WOO_COUPON_BOX_VERSION );
			wp_enqueue_script( 'woo-coupon-box-admin-javascript', VI_WOO_COUPON_BOX_JS . 'wcb-admin.js', [ 'jquery' ], VI_WOO_COUPON_BOX_VERSION );
			wp_localize_script( 'woo-coupon-box-admin-javascript', 'woo_coupon_box_params_admin', [ 'url' => admin_url( 'admin-ajax.php' ) ] );
		}
	}

	public function save_data_coupon_box() {

		global $coupon_box_settings;
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['_woocouponbox_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['_woocouponbox_nonce'] ) ), 'woocouponbox_action_nonce' ) ) {
			return;
		}
		$args = array(
			/*old option*/
			'wcb_active'         => '',
			'wcb_coupon'         => '',
			'wcb_email_campaign' => '',

			'wcb_enable_mailchimp'                          => '',
			'wcb_api'                                       => '',
			'wcb_mlists'                                    => '',
			'wcb_assign_home'                               => '',
			'wcb_assign'                                    => '',
			/*new options*/
			'wcb_coupon_select'                             => '',
			'wcb_coupon_custom'                             => '',
			'wcb_coupon_unique_amount'                      => 0,
			'wcb_coupon_unique_date_expires'                => null,
			'wcb_coupon_unique_discount_type'               => 'fixed_cart',
			'wcb_coupon_unique_description'                 => '',
			'wcb_coupon_unique_individual_use'              => false,
			'wcb_coupon_unique_product_ids'                 => array(),
			'wcb_coupon_unique_excluded_product_ids'        => array(),
			'wcb_coupon_unique_usage_limit'                 => 0,
			'wcb_coupon_unique_usage_limit_per_user'        => 0,
			'wcb_coupon_unique_limit_usage_to_x_items'      => null,
			'wcb_coupon_unique_free_shipping'               => false,
			'wcb_coupon_unique_product_categories'          => array(),
			'wcb_coupon_unique_excluded_product_categories' => array(),
			'wcb_coupon_unique_exclude_sale_items'          => false,
			'wcb_coupon_unique_minimum_amount'              => '',
			'wcb_coupon_unique_maximum_amount'              => '',
			'wcb_coupon_unique_email_restrictions'          => '',
			'wcb_coupon_unique_prefix'                      => '',

			'wcb_email_subject'                 => '',
			'wcb_email_heading'                 => '',
			'wcb_email_content'                 => '',
			'wcb_button_shop_now_title'         => '',
			'wcb_button_shop_now_url'           => '',
			'wcb_button_shop_now_size'          => '',
			'wcb_button_shop_now_color'         => '',
			'wcb_button_shop_now_bg_color'      => '',
			'wcb_button_shop_now_border_radius' => '',

			'wcb_disable_login'          => '',
			'wcb_select_popup'           => '',
			'wcb_popup_time'             => '',
			'wcb_popup_scroll'           => '',
			'wcb_popup_exit'             => '',
			'wcb_on_close'               => '',
			'wcb_expire'                 => '',
			'wcb_expire_unit'            => '',
			'wcb_expire_subscribed'      => '',
			'wcb_purchased_code'         => '',
			'wcb_enable_active_campaign' => '',
			'wcb_active_campaign_api'    => '',
			'wcb_active_campaign_url'    => '',
			'wcb_active_campaign_list'   => '',

			'wcb_never_reminder_enable'  => 0,
			'wcb_recaptcha'              => 0,
			'wcb_recaptcha_version'      => 2,
			'wcb_recaptcha_site_key'     => '',
			'wcb_recaptcha_secret_key'   => '',
			'wcb_recaptcha_secret_theme' => 'light',
		);
		if ( get_option( 'wcb_active' ) !== false ) {
			foreach ( $args as $key => $arg ) {
				$args[ $key ] = get_option( $key, '' );
				delete_option( $key );
			}
			$remove_option = array(
				'wcb_facebook',
				'wcb_twitter',
				'wcb_pinterest',
				'wcb_instagram',
				'wcb_dribbble',
				'wcb_tumblr',
				'wcb_gplus',
				'wcb_vkontakte',
				'wcb_linkedin',
				'wcb_only_reg',
				'wcb_countdown_timer',
				'wcb_toggle_coupon_box',
				'wcb_initial_time',
			);
			foreach ( $remove_option as $k => $v ) {
				delete_option( $v );
			}
			update_option( 'woo_coupon_box_params', $args );
		} else {
			foreach ( $args as $key => $arg ) {
				if ( in_array( $key, array(
					'wcb_coupon_unique_product_categories',
					'wcb_coupon_unique_excluded_product_categories',
					'wcb_coupon_unique_product_ids',
					'wcb_coupon_unique_excluded_product_ids'
				) ) ) {
					$args[ $key ] = isset( $_POST[ $key ] ) ? array_map( 'sanitize_text_field', wp_unslash( $_POST[ $key ] ) ) : '';

				} elseif ( in_array( $key, array( 'wcb_email_content', 'wcb_coupon_unique_description' ) ) ) {
					$args[ $key ] = isset( $_POST[ $key ] ) ? wp_kses_post( wp_unslash( $_POST[ $key ] ) ) : '';

				} else {
					$args[ $key ] = isset( $_POST[ $key ] ) ? sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) : '';
				}
			}
			$args = wp_parse_args( $args, get_option( 'woo_coupon_box_params', $coupon_box_settings ) );
			update_option( 'woo_coupon_box_params', $args );
			$coupon_box_settings = $args;
		}
	}
}