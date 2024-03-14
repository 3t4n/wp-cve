<?php
/*
Class Name: VI_WOO_THANK_YOU_PAGE_Admin_Settings
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2018 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_THANK_YOU_PAGE_Admin_Settings {
	protected $settings;
	protected $prefix;

	public function __construct() {

		$this->settings = new VI_WOO_THANK_YOU_PAGE_DATA();
		$this->prefix   = 'woocommerce-thank-you-page-';
		add_filter(
			'plugin_action_links_woo-thank-you-page-customizer/woo-thank-you-page-customizer.php', array(
				$this,
				'settings_link'
			)
		);
		add_action( 'admin_menu', array( $this, 'create_options_page' ), 998 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_script' ) );
		add_action( 'admin_init', array( $this, 'save_data' ), 99 );
		/*ajax search*/
		add_action( 'wp_ajax_wtyp_search_coupon', array( $this, 'search_coupon' ) );
		add_action( 'wp_ajax_wtyp_search_product', array( $this, 'search_product' ) );
		add_action( 'wp_ajax_wtyp_search_product_parent', array( $this, 'search_product_parent' ) );
		add_action( 'wp_ajax_wtyp_search_cate', array( $this, 'search_cate' ) );

		/*preview email*/
		add_action( 'media_buttons', array( $this, 'preview_emails_button' ) );
		add_action( 'wp_ajax_wtypc_preview_emails', array( $this, 'preview_emails_ajax' ) );
		add_action( 'admin_footer', array( $this, 'preview_emails_html' ) );
	}

	function preview_emails_html() {
		global $pagenow;
		if ( $pagenow == 'admin.php' && isset( $_REQUEST['page'] ) && wp_unslash( sanitize_text_field( $_REQUEST['page'] ) ) === 'woo_thank_you_page_customizer' ) {
			?>
            <div class="preview-emails-html-container preview-html-hidden">
                <div class="preview-emails-html-overlay"></div>
                <div class="preview-emails-html"></div>
            </div>
			<?php
		}
	}

	public function preview_emails_button( $editor_id ) {
		global $pagenow;
		if ( $pagenow == 'admin.php' && isset( $_REQUEST['page'] ) && wp_unslash( sanitize_text_field( $_REQUEST['page'] ) ) == 'woo_thank_you_page_customizer' ) {
			$editor_ids = array( 'coupon_email_content' );
//			if ( count( $this->languages ) ) {
//				foreach ( $this->languages as $key => $value ) {
//					$editor_ids[] = 'wcb_email_content_' . $value;
//				}
//			}
			if ( in_array( $editor_id, $editor_ids ) ) {
				?>
                <span class="<?php echo esc_attr( $this->set( 'available-shortcodes-shortcut' ) ) ?>"><?php esc_html_e( 'Shortcodes', 'woo-thank-you-page-customizer' ) ?></span>

                <span class="<?php echo esc_attr( $this->set( 'preview-emails-button' ) ) ?> button"
                      data-wtypc_language="<?php echo str_replace( 'coupon_email_content', '', $editor_id ) ?>"><?php esc_html_e( 'Preview emails', 'woo-thank-you-page-customizer' ) ?></span>
				<?php
			}
		}
	}

	public function preview_emails_ajax() {
		$shortcodes          = array(
			'order_number'   => 2019,
			'order_status'   => 'processing',
			'order_date'     => date_i18n( 'F d, Y', strtotime( 'today' ) ),
			'order_total'    => 999,
			'order_subtotal' => 990,
			'items_count'    => 3,
			'payment_method' => 'Cash on delivery',

			'shipping_method'            => 'Free shipping',
			'shipping_address'           => 'Thainguyen City',
			'formatted_shipping_address' => 'Thainguyen City, Vietnam',

			'billing_address'           => 'Thainguyen City',
			'formatted_billing_address' => 'Thainguyen City, Vietnam',
			'billing_country'           => 'VN',
			'billing_city'              => 'Thainguyen',

			'billing_first_name'          => 'John',
			'billing_last_name'           => 'Doe',
			'formatted_billing_full_name' => 'John Doe',
			'billing_email'               => 'support@villatheme.com',

			'shop_title' => get_bloginfo(),
			'home_url'   => home_url(),
			'shop_url'   => get_option( 'woocommerce_shop_page_id', '' ) ? get_page_link( get_option( 'woocommerce_shop_page_id' ) ) : '',

		);
		$content             = isset( $_GET['content'] ) ? wp_kses_post( stripslashes( $_GET['content'] ) ) : '';
		$heading             = isset( $_GET['heading'] ) ? sanitize_text_field( stripslashes( $_GET['heading'] ) ) : '';
		$coupon_amount       = '10%';
		$coupon_code         = 'HAPPY';
		$coupon_date_expires = date_i18n( 'F d, Y', strtotime( '+30 days' ) );
		$last_valid_date     = date_i18n( 'F d, Y', strtotime( '+31 days' ) );
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
		$heading             = str_replace( array(
			'{coupon_code}',
			'{coupon_date_expires}',
			'{last_valid_date}',
			'{coupon_amount}'
		), array( $coupon_code, $coupon_date_expires, $last_valid_date, $coupon_amount ), $heading );
		if ( is_array( $shortcodes ) && count( $shortcodes ) ) {
			foreach ( $shortcodes as $key => $value ) {
				$content = str_replace( '{' . $key . '}', $value, $content );
				$heading = str_replace( '{' . $key . '}', $value, $heading );
			}
		}

		// load the mailer class
		$mailer = WC()->mailer();

		// create a new email
		$email = new WC_Email();

		// wrap the content with the email template and then add styles
		$message = apply_filters( 'woocommerce_mail_content', $email->style_inline( $mailer->wrap_message( $heading, $content ) ) );

		// print the preview email
		$css = '.woo-thank-you-page-customizer-coupon-input{line-height:46px;display:block;text-align: center;font-size: 24px;width: 100%;height: 46px;vertical-align: middle;margin: 0;color:' . $this->settings->get_params( 'coupon_code_color' ) . ';background-color:' . $this->settings->get_params( 'coupon_code_bg_color' ) . ';border-width:' . $this->settings->get_params( 'coupon_code_border_width' ) . 'px;border-style:' . $this->settings->get_params( 'coupon_code_border_style' ) . ';border-color:' . $this->settings->get_params( 'coupon_code_border_color' ) . ';}';
		wp_send_json(
			array(
				'html' => $message,
				'css'  => $css
			)
		);
	}


	function settings_link( $links ) {
		$settings_link = '<a href="' . admin_url( 'admin.php' ) . '?page=woo_thank_you_page_customizer" title="' . esc_html__( 'Settings', 'woo-thank-you-page-customizer' ) . '">' . esc_html__( 'Settings', 'woo-thank-you-page-customizer' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
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
					'key'     => 'wtypc_unique_coupon',
					'compare' => 'NOT EXISTS'
				),
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

				if ( $coupon->get_date_expires() && current_time( 'timestamp', true ) > $coupon->get_date_expires()->getTimestamp() ) {
					continue;
				}
				$existing_coupon_discount_type = $coupon->get_discount_type();
				$existing_coupon_amount        = $coupon->get_amount();
				$product                       = array(
					'id'          => get_the_ID(),
					'text'        => get_the_title(),
					'coupon_data' => array(
						'coupon_amount'        => $existing_coupon_amount,
						'coupon_discount_type' => $existing_coupon_discount_type,
					)
				);
				$found_products[]              = $product;
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
		if ( ! $keyword ) {
			$keyword = filter_input( INPUT_POST, 'keyword', FILTER_SANITIZE_STRING );
		}
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
		$items      = array();
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
		$arg            = array(
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
				$product_id    = get_the_ID();
				$product_title = get_the_title();
				$the_product   = wc_get_product( $product_id );
				if ( $the_product->get_sku() ) {
					$product_title .= ' (' . $the_product->get_sku() . ')';
				}
				$product          = array( 'id' => $product_id, 'text' => $product_title );
				$found_products[] = $product;


				if ( $the_product->has_child() && $the_product->is_type( 'variable' ) ) {
					$product_children = $the_product->get_children();
					if ( count( $product_children ) ) {
						foreach ( $product_children as $product_child ) {

							$child_wc = wc_get_product( $product_child );
							if ( woocommerce_version_check() ) {
								$product_title_child = get_the_title( $product_child );
								if ( $child_wc->get_sku() ) {
									$product_title_child .= '(' . $child_wc->get_sku() . ')';
								}
								$product = array(
									'id'   => $product_child,
									'text' => $product_title_child
								);

							} else {
								$get_atts            = $child_wc->get_variation_attributes();
								$attr_name           = array_values( $get_atts )[0];
								$product_title_child = get_the_title() . ' - ' . $attr_name;
								if ( $child_wc->get_sku() ) {
									$product_title_child .= '(' . $child_wc->get_sku() . ')';
								}
								$product = array(
									'id'   => $product_child,
									'text' => $product_title_child
								);
							}
							$found_products[] = $product;
						}

					}
				}


			}
		}
		wp_reset_postdata();
		wp_send_json( $found_products );
		die;
	}

	public function search_product_parent( $x = '', $post_types = array( 'product' ) ) {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		ob_start();

		$keyword = filter_input( INPUT_GET, 'keyword', FILTER_SANITIZE_STRING );
		if ( ! $keyword ) {
			$keyword = filter_input( INPUT_POST, 'keyword', FILTER_SANITIZE_STRING );
		}
		if ( empty( $keyword ) ) {
			die();
		}
		$arg            = array(
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
				$product_id    = get_the_ID();
				$product_title = get_the_title() . ' (' . $product_id . ')';
				$the_product   = wc_get_product( $product_id );
				if ( $the_product->is_type( 'variation' ) ) {
					continue;
				}

				$product          = array( 'id' => $product_id, 'text' => $product_title );
				$found_products[] = $product;
			}
		}
		wp_reset_postdata();
		wp_send_json( $found_products );
		die;
	}


	public function create_options_page() {
		add_menu_page( 'Thank You Page Customizer for WooCommerce', 'Thank You Page', 'manage_options', 'woo_thank_you_page_customizer', array(
			$this,
			'settings_callback'
		), VI_WOO_THANK_YOU_PAGE_IMAGES . 'thank-you.png', 2 );
	}

	public function settings_callback() {
		$this->settings = new VI_WOO_THANK_YOU_PAGE_DATA();
		?>
        <div class="wrap">
            <h2><?php echo esc_html__( 'Thank You Page Customizer for WooCommerce', 'woo-thank-you-page-customizer' ); ?></h2>

            <div class="vi-ui raised">
                <form class="vi-ui form" method="post" action="">
					<?php
					wp_nonce_field( 'woo_thank_you_page_action_nonce', '_woo_thank_you_page_nonce' );
					settings_fields( 'woo-thank-you-page-customizer' );
					do_settings_sections( 'woo-thank-you-page-customizer' );
					?>
                    <div class="vi-ui vi-ui-main top attached tabular menu">
                        <a class="item active"
                           data-tab="general"><?php esc_html_e( 'General', 'woo-thank-you-page-customizer' ) ?></a>
                        <a class="item"
                           data-tab="coupon"><?php esc_html_e( 'Coupon', 'woo-thank-you-page-customizer' ) ?></a>
                        <a class="item"
                           data-tab="email"><?php esc_html_e( 'Email', 'woo-thank-you-page-customizer' ) ?></a>
                    </div>
                    <div class="vi-ui bottom attached tab segment active" data-tab="general">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">
                                    <label for="enable"><?php esc_html_e( 'Enable', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="enable"
                                               id="enable" <?php checked( $this->settings->get_params( 'enable' ), 1 ); ?>
                                               value="1">
                                        <label for="enable"><?php esc_html_e( 'Enable', 'woo-thank-you-page-customizer' ) ?></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="my_account_coupon_enable"><?php esc_html_e( 'Show coupon gift', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="my_account_coupon_enable"
                                               id="my_account_coupon_enable" <?php checked( $this->settings->get_params( 'my_account_coupon_enable' ), 1 ); ?>
                                               value="1">
                                        <label for="my_account_coupon_enable"></label>
                                    </div>
                                    <p class="description">
										<?php esc_html_e( 'Show coupon gift on the My Account > Orders page', 'woo-thank-you-page-customizer' ) ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="order_status"><?php esc_html_e( 'Order status', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <select name="order_status[]" id="order_status"
                                            class="vi-ui fluid dropdown selection" multiple="">
										<?php
										$order_status = $this->settings->get_params( 'order_status' );
										$statuses     = wc_get_order_statuses();
										foreach ( $statuses as $k => $status ) {
											$selected = '';
											if ( in_array( $k, $order_status ) ) {
												$selected = 'selected="selected"';
											}
											?>
                                            <option <?php echo esc_attr( $selected ); ?>
                                                    value="<?php echo esc_attr( $k ) ?>"><?php echo esc_html( $status ) ?></option>
											<?php
										}
										?>
                                    </select>
                                    <p class="description"><?php esc_html_e( 'Usually, order status will be set to "Processing" after checking out and customers will be lead to a thank you page but it could be different for some payments that you are using.', 'woo-thank-you-page-customizer' ) ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="google-map-api">
										<?php esc_html_e( 'Google map API key', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="google_map_api" id="google-map-api"
                                           value="<?php echo htmlentities( $this->settings->get_params( 'google_map_api' ) ); ?>">
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label><?php esc_html_e( 'Design', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
									<?php
									$url = admin_url( 'customize.php' ) . '?autofocus[section]=woo_thank_you_page_design_general';
									if ( $this->settings->get_params( 'select_order' ) ) {
										$order = wc_get_order( $this->settings->get_params( 'select_order' ) );
										if ( $order ) {
											$url = admin_url( 'customize.php' ) . '?url=' . urlencode( $order->get_checkout_order_received_url() ) . '&autofocus[section]=woo_thank_you_page_design_general';
										}
									}
									?>
                                    <a target="_blank"
                                       href="<?php echo esc_url( $url ) ?>"><?php esc_html_e( 'Go to design', 'woo-thank-you-page-customizer' ) ?></a>
                                </td>
                            </tr>
                        </table>

                    </div>

                    <div class="vi-ui bottom attached tab segment" data-tab="coupon">
                        <table class="form-table wtyp-coupon-table">
                            <tbody>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="coupon_type"><?php esc_html_e( 'Select coupon', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <select class="vi-ui fluid dropdown coupon-select" name="coupon_type"
                                            id="coupon_type">
                                        <option value="unique" <?php selected( $this->settings->get_params( 'coupon_type' )[0], 'unique' ) ?>><?php esc_html_e( 'Unique coupon', 'woo-thank-you-page-customizer' ) ?></option>
                                        <option value="existing" <?php selected( $this->settings->get_params( 'coupon_type' )[0], 'existing' ) ?>><?php esc_html_e( 'Existing coupon', 'woo-thank-you-page-customizer' ) ?></option>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top" class="coupon-email-restriction">
                                <th scope="row">
                                    <label for="coupon_unique_email_restrictions"><?php esc_html_e( 'Email restriction', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="coupon_unique_email_restrictions"
                                               id="coupon_unique_email_restrictions" <?php checked( $this->settings->get_params( 'coupon_unique_email_restrictions' )[0], 1 ); ?>
                                               value="1">
                                        <label for="coupon_unique_email_restrictions"><span
                                                    class="description"><?php esc_html_e( 'Enable to make coupon usable for received email only', 'woo-thank-you-page-customizer' ) ?></span></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top" class="coupon-existing">
                                <th scope="row">
                                    <label for="existing_coupon"><?php esc_html_e( 'Existing coupon', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <select class="search-coupon" name="existing_coupon" id="existing_coupon">
										<?php
										if ( $this->settings->get_params( 'existing_coupon' )[0] ) {
											$coupon = new WC_Coupon( $this->settings->get_params( 'existing_coupon' )[0] );
											?>
                                            <option value="<?php echo esc_attr( $this->settings->get_params( 'existing_coupon' )[0] ) ?>"
                                                    selected><?php echo esc_html( $coupon->get_code() ); ?></option>
											<?php
										}
										?>
                                    </select>
                                </td>
                            </tr>

                            <!--                            <tr valign="top" class="coupon-custom">-->
                            <!--                                <th scope="row">-->
                            <!--                                    <label for="coupon_custom">-->
							<?php //esc_html_e( 'Custom', 'woo-thank-you-page-customizer' ) ?><!--</label>-->
                            <!--                                </th>-->
                            <!--                                <td>-->
                            <!--                                    <input type="text" name="coupon_custom" id="coupon_custom"-->
                            <!--                                           value="-->
							<?php //echo htmlentities( $this->settings->get_params( 'coupon_custom' )[0] ); ?><!--">-->
                            <!--                                </td>-->
                            <!--                            </tr>-->

                            <tr valign="top" class="coupon-unique">
                                <th scope="row">
                                    <label for="coupon_unique_discount_type"><?php esc_html_e( 'Discount type', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <select class="vi-ui fluid dropdown" name="coupon_unique_discount_type">
                                        <option value="percent" <?php selected( $this->settings->get_params( 'coupon_unique_discount_type' )[0], 'percent' ) ?>><?php esc_html_e( 'Percentage discount', 'woo-thank-you-page-customizer' ) ?></option>
                                        <option value="fixed_cart" <?php selected( $this->settings->get_params( 'coupon_unique_discount_type' )[0], 'fixed_cart' ) ?>><?php esc_html_e( 'Fixed cart discount', 'woo-thank-you-page-customizer' ) ?></option>
                                        <option value="fixed_product" <?php selected( $this->settings->get_params( 'coupon_unique_discount_type' )[0], 'fixed_product' ) ?>><?php esc_html_e( 'Fixed product discount', 'woo-thank-you-page-customizer' ) ?></option>
                                    </select>
                                </td>
                            </tr>

                            <tr valign="top" class="coupon-unique">
                                <th scope="row">
                                    <label for="coupon_unique_prefix"><?php esc_html_e( 'Coupon code prefix', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="coupon_unique_prefix" id="coupon_unique_prefix"
                                           value="<?php echo htmlentities( $this->settings->get_params( 'coupon_unique_prefix' )[0] ); ?>">
                                </td>
                            </tr>
                            <tr valign="top" class="coupon-unique">
                                <th scope="row">
                                    <label for="coupon_unique_amount"><?php esc_html_e( 'Coupon amount', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="coupon_unique_amount" id="coupon_unique_amount"
                                           min="0" step="0.01"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'coupon_unique_amount' )[0] ) ?>">
                                </td>
                            </tr>
                            <tr valign="top" class="coupon-unique">
                                <th scope="row">
                                    <label for="coupon_unique_free_shipping"><?php esc_html_e( 'Allow free shipping', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="coupon_unique_free_shipping"
                                               id="coupon_unique_free_shipping" <?php checked( $this->settings->get_params( 'coupon_unique_free_shipping' )[0], 1 ); ?>
                                               value="1">
                                        <label for="coupon_unique_free_shipping"><span
                                                    class="description"><?php printf( esc_html__( 'Enable if the coupon grants free shipping. A free shipping method must be enabled in your shipping zone and be set to require "a valid free shipping coupon" (see the "%s" setting).', 'woo-thank-you-page-customizer' ), '<a href="https://docs.woocommerce.com/document/free-shipping/"
											   target="_blank">' . esc_html__( 'free shipping method', 'woo-thank-you-page-customizer' ) . '</a>' ) ?></span></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top" class="coupon-unique">
                                <th scope="row">
                                    <label for="coupon_unique_date_expires"><?php esc_html_e( 'Expires after(days)', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="coupon_unique_date_expires"
                                           id="coupon_unique_date_expires"
                                           min="0"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'coupon_unique_date_expires' )[0] ) ?>">
                                </td>
                            </tr>

                            <tr valign="top" class="coupon-unique">
                                <th scope="row">
                                    <label for="coupon_unique_minimum_amount"><?php esc_html_e( 'Minimum spend', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="coupon_unique_minimum_amount"
                                           id="coupon_unique_minimum_amount"
                                           min="0"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'coupon_unique_minimum_amount' )[0] ) ?>">
                                </td>
                            </tr>
                            <tr valign="top" class="coupon-unique">
                                <th scope="row">
                                    <label for="coupon_unique_maximum_amount"><?php esc_html_e( 'Maximum spend', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="coupon_unique_maximum_amount"
                                           id="coupon_unique_maximum_amount"
                                           min="0"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'coupon_unique_maximum_amount' )[0] ) ?>">
                                </td>
                            </tr>


                            <tr valign="top" class="coupon-unique">
                                <th scope="row">
                                    <label for="coupon_unique_individual_use"><?php esc_html_e( 'Individual use only', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="coupon_unique_individual_use"
                                               id="coupon_unique_individual_use" <?php checked( $this->settings->get_params( 'coupon_unique_individual_use' )[0], 1 ); ?>
                                               value="1">
                                        <label for="coupon_unique_individual_use"><span
                                                    class="description"><?php esc_html_e( 'Enable if the coupon cannot be used in conjunction with other coupons.', 'woo-thank-you-page-customizer' ) ?></span></label>
                                    </div>
                                </td>
                            </tr>
                            <tr valign="top" class="coupon-unique">
                                <th scope="row">
                                    <label for="coupon_unique_exclude_sale_items"><?php esc_html_e( 'Exclude sale items', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox checked">
                                        <input type="checkbox" name="coupon_unique_exclude_sale_items"
                                               id="coupon_unique_exclude_sale_items" <?php checked( $this->settings->get_params( 'coupon_unique_exclude_sale_items' )[0], 1 ); ?>
                                               value="1">
                                        <label for="coupon_unique_exclude_sale_items"><span
                                                    class="description"><?php esc_html_e( 'Enable if the coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are items in the cart that are not on sale.', 'woo-thank-you-page-customizer' ) ?></span></label>
                                    </div>
                                </td>
                            </tr>


                            <tr valign="top" class="coupon-unique">
                                <th>
                                    <label for="coupon_unique_product_ids"><?php esc_html_e( 'Products', 'woo-thank-you-page-customizer' ); ?></label>
                                </th>
                                <td>
                                    <select name="coupon_unique_product_ids[]" id="coupon_unique_product_ids"
                                            class="search-product" multiple="multiple">
										<?php
										if ( is_array( $this->settings->get_params( 'coupon_unique_product_ids' )[0] ) && count( $this->settings->get_params( 'coupon_unique_product_ids' )[0] ) ) {
											foreach ( $this->settings->get_params( 'coupon_unique_product_ids' )[0] as $product_id ) {
												?>
                                                <option value="<?php echo esc_attr( $product_id ) ?>"
                                                        selected><?php echo get_the_title( $product_id ); ?></option>
												<?php
											}
										}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top" class="coupon-unique">
                                <th>
                                    <label for="coupon_unique_excluded_product_ids"><?php esc_html_e( 'Exclude products', 'woo-thank-you-page-customizer' ); ?></label>
                                </th>
                                <td>
                                    <select name="coupon_unique_excluded_product_ids[]"
                                            id="coupon_unique_excluded_product_ids" class="search-product"
                                            multiple="multiple">
										<?php
										if ( is_array( $this->settings->get_params( 'coupon_unique_excluded_product_ids' )[0] ) && count( $this->settings->get_params( 'coupon_unique_excluded_product_ids' )[0] ) ) {
											foreach ( $this->settings->get_params( 'coupon_unique_excluded_product_ids' )[0] as $product_id ) {
												?>
                                                <option value="<?php echo esc_attr( $product_id ) ?>"
                                                        selected><?php echo get_the_title( $product_id ); ?></option>
												<?php
											}
										}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top" class="coupon-unique">
                                <th>
                                    <label for="coupon_unique_product_categories"><?php esc_html_e( 'Categories', 'woo-thank-you-page-customizer' ); ?></label>
                                </th>
                                <td>
                                    <select name="coupon_unique_product_categories[]"
                                            id="coupon_unique_product_categories" class="search-category"
                                            multiple="multiple">
										<?php

										if ( is_array( $this->settings->get_params( 'coupon_unique_product_categories' )[0] ) && count( $this->settings->get_params( 'coupon_unique_product_categories' )[0] ) ) {
											foreach ( $this->settings->get_params( 'coupon_unique_product_categories' )[0] as $category_id ) {
												$category = get_term( $category_id );
												?>
                                                <option value="<?php echo esc_attr( $category_id ) ?>"
                                                        selected><?php echo esc_html( $category->name ); ?></option>
												<?php
											}
										}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top" class="coupon-unique">
                                <th>
                                    <label for="coupon_unique_excluded_product_categories"><?php esc_html_e( 'Exclude categories', 'woo-thank-you-page-customizer' ); ?></label>
                                </th>
                                <td>
                                    <select name="coupon_unique_excluded_product_categories[]"
                                            id="coupon_unique_excluded_product_categories" class="search-category"
                                            multiple="multiple">
										<?php

										if ( is_array( $this->settings->get_params( 'coupon_unique_excluded_product_categories' )[0] ) && count( $this->settings->get_params( 'coupon_unique_excluded_product_categories' )[0] ) ) {
											foreach ( $this->settings->get_params( 'coupon_unique_excluded_product_categories' )[0] as $category_id ) {
												$category = get_term( $category_id );
												?>
                                                <option value="<?php echo esc_attr( $category_id ) ?>"
                                                        selected><?php echo esc_html( $category->name ); ?></option>
												<?php
											}
										}
										?>
                                    </select>
                                </td>
                            </tr>
                            <tr valign="top" class="coupon-unique">
                                <th scope="row">
                                    <label for="coupon_unique_usage_limit"><?php esc_html_e( 'Usage limit per coupon', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="coupon_unique_usage_limit"
                                           id="coupon_unique_usage_limit"
                                           min="0"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'coupon_unique_usage_limit' )[0] ) ?>">
                                </td>
                            </tr>
                            <tr valign="top" class="coupon-unique">
                                <th scope="row">
                                    <label for="coupon_unique_limit_usage_to_x_items"><?php esc_html_e( 'Limit usage to X items', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="coupon_unique_limit_usage_to_x_items"
                                           id="coupon_unique_limit_usage_to_x_items"
                                           min="0"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'coupon_unique_limit_usage_to_x_items' )[0] ) ?>">
                                </td>
                            </tr>
                            <tr valign="top" class="coupon-unique">
                                <th scope="row">
                                    <label for="coupon_unique_usage_limit_per_user"><?php esc_html_e( 'Usage limit per user', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input type="number" name="coupon_unique_usage_limit_per_user"
                                           id="coupon_unique_usage_limit_per_user"
                                           min="0"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'coupon_unique_usage_limit_per_user' )[0] ) ?>">
                                </td>
                            </tr>
                            </tbody>
                        </table>


                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="email">
                        <table class="form-table">
                            <tr valign="top">
                                <th scope="row">
                                    <label for="email-send"><?php esc_html_e( 'Send coupon email', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="checkbox" name="coupon_email_send" id="email-send"
                                               value="1" <?php checked( $this->settings->get_params( 'coupon_email_send' ), '1' ) ?>>
                                    </div>
                                    <p class="description"><?php echo esc_html__( 'Send coupon email if coupon is given on thank you page', 'woo-thank-you-page-customizer' ) ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="coupon-email-subject"><?php esc_html_e( 'Email subject', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="coupon_email_subject" id="coupon-email-subject"
                                           value="<?php echo htmlentities( $this->settings->get_params( 'coupon_email_subject' ) ) ?>">
                                    <p class="description"><?php echo esc_html__( '', 'woo-thank-you-page-customizer' ) ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="coupon-email-heading"><?php esc_html_e( 'Email heading', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
                                    <input type="text" name="coupon_email_heading" id="coupon-email-heading"
                                           value="<?php echo htmlentities( $this->settings->get_params( 'coupon_email_heading' ) ) ?>">
                                    <p class="description"><?php echo esc_html__( '', 'woo-thank-you-page-customizer' ) ?></p>
                                </td>
                            </tr>
                            <tr valign="top">
                                <th scope="row">
                                    <label for="coupon_email_content"><?php esc_html_e( 'Email content', 'woo-thank-you-page-customizer' ) ?></label>
                                </th>
                                <td>
									<?php wp_editor( stripslashes( $this->settings->get_params( 'coupon_email_content' ) ), 'coupon_email_content', array( 'editor_height' => 300 ) ) ?>
                                    <p class="description"><?php echo esc_html__( '', 'woo-thank-you-page-customizer' ) ?></p>
                                </td>
                            </tr>

                        </table>
                    </div>
                    <p>
                        <input type="submit" name="wtyp_save_data" value="Save" class="vi-ui primary button">
                    </p>
                </form>
            </div>

        </div>
		<?php
		$shortcodes = array(
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
					foreach ( $shortcodes as $key => $value ) {
						?>
                        <div class="<?php echo esc_attr( $this->set( 'available-shortcodes-item' ) ) ?>">
                            <div class="<?php echo esc_attr( $this->set( 'available-shortcodes-item-name' ) ) ?>"><?php echo esc_html( $value ) ?></div>
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

		do_action( 'villatheme_support_woo-thank-you-page-customizer' );
	}

	private function set( $name ) {
		if ( is_array( $name ) ) {
			return implode( ' ', array_map( array( $this, 'set' ), $name ) );

		} else {
			return esc_attr__( $this->prefix . $name );

		}
	}

	public function admin_enqueue_script() {
		$page = isset( $_REQUEST['page'] ) ? wp_unslash( sanitize_text_field( $_REQUEST['page'] ) ) : '';
		if ( $page == 'woo_thank_you_page_customizer' ) {
			global $wp_scripts;
			$scripts = $wp_scripts->registered;
			foreach ( $scripts as $k => $script ) {
				preg_match( '/^\/wp-/i', $script->src, $result );
				if ( count( array_filter( $result ) ) < 1 ) {
					wp_dequeue_script( $script->handle );
				}
			}
			// style
			wp_enqueue_style( 'woocommerce-thank-you-page-form', VI_WOO_THANK_YOU_PAGE_CSS . 'form.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-button', VI_WOO_THANK_YOU_PAGE_CSS . 'button.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-icon', VI_WOO_THANK_YOU_PAGE_CSS . 'icon.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-dropdown', VI_WOO_THANK_YOU_PAGE_CSS . 'dropdown.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-checkbox', VI_WOO_THANK_YOU_PAGE_CSS . 'checkbox.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-transition', VI_WOO_THANK_YOU_PAGE_CSS . 'transition.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-tab', VI_WOO_THANK_YOU_PAGE_CSS . 'tab.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-segment', VI_WOO_THANK_YOU_PAGE_CSS . 'segment.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-menu', VI_WOO_THANK_YOU_PAGE_CSS . 'menu.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-select2', VI_WOO_THANK_YOU_PAGE_CSS . 'select2.min.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-icons', VI_WOO_THANK_YOU_PAGE_CSS . 'woocommerce-thank-you-page-icons.css' );
			wp_enqueue_style( 'woocommerce-thank-you-page-admin', VI_WOO_THANK_YOU_PAGE_CSS . 'admin-style.css' );
			wp_enqueue_style( 'woocommerce-coupon-villatheme-support', VI_WOO_THANK_YOU_PAGE_CSS . 'villatheme-support.css' );
			$css = '.woo-thank-you-page-customizer-coupon-input{line-height:46px;display:block;text-align: center;font-size: 24px;width: 100%;height: 46px;vertical-align: middle;margin: 0;color:' . $this->settings->get_params( 'coupon_code_color' ) . ';background-color:' . $this->settings->get_params( 'coupon_code_bg_color' ) . ';border-width:' . $this->settings->get_params( 'coupon_code_border_width' ) . 'px;border-style:' . $this->settings->get_params( 'coupon_code_border_style' ) . ';border-color:' . $this->settings->get_params( 'coupon_code_border_color' ) . ';}';
			wp_add_inline_style( 'woocommerce-thank-you-page-admin', $css );
			//script
			/*Color picker*/
			wp_enqueue_script(
				'iris', admin_url( 'js/iris.min.js' ), array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			), false, 1
			);
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'woocommerce-thank-you-page-form', VI_WOO_THANK_YOU_PAGE_JS . 'form.min.js', array( 'jquery' ), VI_WOO_THANK_YOU_PAGE_VERSION );
			wp_enqueue_script( 'woocommerce-thank-you-page-checkbox', VI_WOO_THANK_YOU_PAGE_JS . 'checkbox.min.js', array( 'jquery' ), VI_WOO_THANK_YOU_PAGE_VERSION );
			wp_enqueue_script( 'woocommerce-thank-you-page-dropdown', VI_WOO_THANK_YOU_PAGE_JS . 'dropdown.min.js', array( 'jquery' ), VI_WOO_THANK_YOU_PAGE_VERSION );
			wp_enqueue_script( 'woocommerce-thank-you-page-transition', VI_WOO_THANK_YOU_PAGE_JS . 'transition.min.js', array( 'jquery' ), VI_WOO_THANK_YOU_PAGE_VERSION );
			wp_enqueue_script( 'woocommerce-thank-you-page-tab', VI_WOO_THANK_YOU_PAGE_JS . 'tab.js', array( 'jquery' ), VI_WOO_THANK_YOU_PAGE_VERSION );
			wp_enqueue_script( 'woocommerce-thank-you-page-address', VI_WOO_THANK_YOU_PAGE_JS . 'jquery.address-1.6.min.js', array( 'jquery' ), VI_WOO_THANK_YOU_PAGE_VERSION );
			wp_enqueue_script( 'woocommerce-thank-you-page-select2', VI_WOO_THANK_YOU_PAGE_JS . 'select2.js', array( 'jquery' ), VI_WOO_THANK_YOU_PAGE_VERSION );
			wp_enqueue_script( 'woocommerce-thank-you-page-admin', VI_WOO_THANK_YOU_PAGE_JS . 'admin-script.js', array( 'jquery' ), VI_WOO_THANK_YOU_PAGE_VERSION );
			wp_localize_script( 'woocommerce-thank-you-page-admin', 'wtypc_params_admin', array( 'url' => admin_url( 'admin-ajax.php' ) ) );
		}
	}

	public function save_data() {
		global $woo_thank_you_page_settings;
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['_woo_thank_you_page_nonce'] ) || ! wp_verify_nonce( $_POST['_woo_thank_you_page_nonce'], 'woo_thank_you_page_action_nonce' ) ) {
			return;
		}

		$args = array(
			'coupon_type'                               => array( 'unique' ),
			'existing_coupon'                           => array( '' ),
			'coupon_unique_discount_type'               => array( 'percent' ),
			'coupon_unique_amount'                      => array( '10' ),
			'coupon_unique_date_expires'                => array( 30 ),
			'coupon_unique_individual_use'              => array( false ),
			'coupon_unique_product_ids'                 => array( array() ),
			'coupon_unique_excluded_product_ids'        => array( array() ),
			'coupon_unique_usage_limit'                 => array( 0 ),
			'coupon_unique_usage_limit_per_user'        => array( 0 ),
			'coupon_unique_limit_usage_to_x_items'      => array( null ),
			'coupon_unique_free_shipping'               => array( false ),
			'coupon_unique_product_categories'          => array( array() ),
			'coupon_unique_excluded_product_categories' => array( array() ),
			'coupon_unique_exclude_sale_items'          => array( false ),
			'coupon_unique_minimum_amount'              => array( '50' ),
			'coupon_unique_maximum_amount'              => array( '100' ),
			'coupon_unique_email_restrictions'          => array( true ),
			'coupon_unique_prefix'                      => array( '' ),
			'coupon_rule_product_ids'                   => array( array() ),
			'coupon_rule_excluded_product_ids'          => array( array() ),
			'coupon_rule_product_categories'            => array( array() ),
			'coupon_rule_excluded_product_categories'   => array( array() ),
			'coupon_rule_min_total'                     => array( 0 ),
			'coupon_rule_max_total'                     => array( 100 ),
		);
		foreach ( $args as $key => $value ) {
			$args[ $key ] = $this->settings->get_params( $key );
			if ( in_array( $key, array(
				'coupon_unique_product_categories',
				'coupon_unique_excluded_product_categories',
				'coupon_unique_product_ids',
				'coupon_unique_excluded_product_ids'
			) ) ) {
				$args[ $key ][0] = isset( $_POST[ $key ] ) ? array_map( 'sanitize_text_field', $_POST[ $key ] ) : '';

			} else {
				$args[ $key ][0] = isset( $_POST[ $key ] ) ? sanitize_text_field( stripslashes( $_POST[ $key ] ) ) : '';
			}
		}
		$args['enable']                   = isset( $_POST['enable'] ) ? sanitize_text_field( $_POST['enable'] ) : '';
		$args['my_account_coupon_enable'] = isset( $_POST['my_account_coupon_enable'] ) ? sanitize_text_field( $_POST['my_account_coupon_enable'] ) : '';
		$args['google_map_api']           = isset( $_POST['google_map_api'] ) ? sanitize_text_field( $_POST['google_map_api'] ) : '';
		$args['order_status']             = isset( $_POST['order_status'] ) ? wc_clean( $_POST['order_status'] ) : array();
		$args['coupon_email_send']        = isset( $_POST['coupon_email_send'] ) ? sanitize_text_field( $_POST['coupon_email_send'] ) : '';
		$args['coupon_email_subject']     = isset( $_POST['coupon_email_subject'] ) ? sanitize_text_field( stripslashes( $_POST['coupon_email_subject'] ) ) : '';
		$args['coupon_email_heading']     = isset( $_POST['coupon_email_heading'] ) ? sanitize_text_field( stripslashes( $_POST['coupon_email_heading'] ) ) : '';
		$args['coupon_email_content']     = isset( $_POST['coupon_email_content'] ) ? wp_kses_post( stripslashes( $_POST['coupon_email_content'] ) ) : '';
		$args['products']                 = json_encode( array() );
		$args                             = wp_parse_args( $args, get_option( 'woo_thank_you_page_params', $woo_thank_you_page_settings ) );
		update_option( 'woo_thank_you_page_params', $args );
		$woo_thank_you_page_settings = $args;
	}
}