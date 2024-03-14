<?php
/*
Class Name: VI_WOO_LUCKY_WHEEL_Admin_Admin
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2015 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VI_WOO_LUCKY_WHEEL_Admin_Admin {
	protected $settings;

	function __construct() {
		$this->settings = VI_WOO_LUCKY_WHEEL_DATA::get_instance();
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_init', array( $this, 'save_settings' ), 99 );
		add_action( 'wp_ajax_wlwl_search_active_campaign_list', array( $this, 'search_active_campaign_list' ) );
		add_action( 'wp_ajax_wlwl_search_coupon', array( $this, 'search_coupon' ) );
		add_action( 'wp_ajax_wlwl_search_cate', array( $this, 'search_cate' ) );
		add_action( 'wp_ajax_wlwl_search_product', array( $this, 'search_product' ) );
		add_action( 'wp_ajax_wlwl_preview_emails', array( $this, 'preview_emails_ajax' ) );
		add_action( 'wp_ajax_wlwl_preview_wheel', array( $this, 'preview_wheel_ajax' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue' ) );
		add_action( 'media_buttons', array( $this, 'preview_emails_button' ) );
		add_action( 'admin_footer', array( $this, 'preview_emails_html' ) );

	}

	function preview_emails_html() {
		global $pagenow;
		if ( $pagenow == 'admin.php' && isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'woo-lucky-wheel' ) {
			?>
            <div class="preview-emails-html-container preview-html-hidden">
                <div class="preview-emails-html-overlay"></div>
                <div class="preview-emails-html"></div>
            </div>
			<?php
		}
	}

	function add_menu() {
		add_menu_page(
			esc_html__( 'Lucky Wheel for WooCommerce', 'woo-lucky-wheel' ), esc_html__( 'WC Lucky Wheel', 'woo-lucky-wheel' ), 'manage_options', 'woo-lucky-wheel', array(
			$this,
			'settings_page'
		), 'dashicons-wheel', 2
		);
		add_submenu_page( 'woo-lucky-wheel', esc_html__( 'Emails', 'woo-lucky-wheel' ), esc_html__( 'Emails', 'woo-lucky-wheel' ), 'manage_options', 'edit.php?post_type=wlwl_email' );
		add_submenu_page(
			'woo-lucky-wheel', esc_html__( 'Report', 'woo-lucky-wheel' ), esc_html__( 'Report', 'woo-lucky-wheel' ), 'manage_options', 'wlwl-report', array(
				$this,
				'report_callback'
			)
		);
		add_submenu_page(
			'woo-lucky-wheel', esc_html__( 'System Status', 'woo-lucky-wheel' ), esc_html__( 'System Status', 'woo-lucky-wheel' ), 'manage_options', 'wlwl-system-status', array(
				$this,
				'system_status'
			)
		);
	}


	public function preview_emails_button( $editor_id ) {
		global $pagenow;
		if ( $pagenow == 'admin.php' && isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'woo-lucky-wheel' && $editor_id == 'content' ) {
			ob_start();
			?>
            <span class="button wlwl-preview-emails-button"><?php esc_html_e( 'Preview emails', 'woo-lucky-wheel' ) ?></span>
			<?php
			echo ob_get_clean();
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

		$unformatted_price = $price;
		$negative          = $price < 0;
		$price             = apply_filters( 'raw_woocommerce_price', floatval( $negative ? $price * - 1 : $price ) );
		$price             = apply_filters( 'formatted_woocommerce_price', number_format( $price, $decimals, $decimal_separator, $thousand_separator ), $price, $decimals, $decimal_separator, $thousand_separator );

		if ( apply_filters( 'woocommerce_price_trim_zeros', false ) && $decimals > 0 ) {
			$price = wc_trim_zeros( $price );
		}
		$formatted_price = ( $negative ? '-' : '' ) . sprintf( $price_format, wlwl_get_currency_symbol( $currency ), $price );

		return $formatted_price;
	}

	public function preview_wheel_ajax() {
		$label         = isset( $_GET['label'] ) ? wc_clean( $_GET['label'] ) : array();
		$coupon_type   = isset( $_GET['coupon_type'] ) ? wc_clean( $_GET['coupon_type'] ) : array();
		$coupon_amount = isset( $_GET['coupon_amount'] ) ? wc_clean( $_GET['coupon_amount'] ) : array();
		$labels        = array();
		if ( is_array( $label ) && count( $label ) ) {
			for ( $i = 0; $i < count( $label ); $i ++ ) {
				$wheel_label = $label[ $i ];
				switch ( $coupon_type[ $i ] ) {
					case 'percent':
						$wheel_label = str_replace( '{coupon_amount}', $coupon_amount[ $i ] . '%', $wheel_label );
						break;
					case 'fixed_cart':
					case 'fixed_product':
						$wheel_label = str_replace( '{coupon_amount}', $this->wc_price( $coupon_amount[ $i ] ), $wheel_label );
						$wheel_label = str_replace( '&nbsp;', ' ', $wheel_label );
						break;
				}

				$labels[] = $wheel_label;
			}

		}
		wp_send_json( array( 'labels' => $labels ) );
	}

	public function preview_emails_ajax() {
		$content              = isset( $_GET['content'] ) ? wp_kses_post( stripslashes( $_GET['content'] ) ) : '';
		$email_heading        = isset( $_GET['heading'] ) ? wc_clean( stripslashes( $_GET['heading'] ) ) : '';
		$button_shop_url      = isset( $_GET['button_shop_url'] ) ? wc_clean( stripslashes( $_GET['button_shop_url'] ) ) : '';
		$button_shop_size     = 16;
		$button_shop_color    = '#ffffff';
		$button_shop_bg_color = '#000';
		$button_shop_title    = esc_html__( 'Shop now', 'woo-lucky-wheel' );

		$button_shop_now = '<a href="' . $button_shop_url . '" target="_blank" style="text-decoration:none;display:inline-block;padding:10px 30px;margin:10px 0;font-size:' . $button_shop_size . 'px;color:' . $button_shop_color . ';background:' . $button_shop_bg_color . ';">' . $button_shop_title . '</a>';
		$coupon_label    = '10% OFF';
		$coupon_code     = 'LUCKY_WHEEL';
		$date_expires    = strtotime( '+30 days' );
		$customer_name   = 'John';
		$content         = str_replace( '{coupon_label}', $coupon_label, $content );
		$content         = str_replace( '{customer_name}', $customer_name, $content );
		$content         = str_replace( '{coupon_code}', '<span style="font-size: x-large;">' . strtoupper( $coupon_code ) . '</span>', $content );
		$content         = str_replace( '{date_expires}', empty( $date_expires ) ? esc_html__( 'never expires', 'woo-lucky-wheel' ) : date_i18n( 'F d, Y', ( $date_expires ) ), $content );
		$content         = str_replace( '{shop_now}', $button_shop_now, $content );
		$email_heading   = str_replace( '{coupon_label}', $coupon_label, $email_heading );

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
	}

	public function search_product() {

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
			'post_type'      => 'product',
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
				}
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
		wp_send_json( $found_products );
	}

	public function search_coupon() {
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
			'post_type'      => 'shop_coupon',
			'posts_per_page' => 50,
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'     => 'wlwl_unique_coupon',
					'compare' => 'NOT EXISTS'
				),
				array(
					'key'     => 'kt_unique_coupon',
					'compare' => 'NOT EXISTS'
				)
			),
			's'              => $keyword
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
				if ( $coupon->get_date_expires() && time() > $coupon->get_date_expires()->getTimestamp() ) {
					continue;
				}
				$product          = array( 'id' => get_the_ID(), 'text' => get_the_title() );
				$found_products[] = $product;
			}
		}
		wp_send_json( $found_products );
	}

	public function search_suggested_product() {

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
			'post_type'      => 'product',
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
				$the_product   = new WC_Product( $product_id );
				if ( ! $the_product->is_in_stock() ) {
					$product_title .= ' (out-of-stock)';
				}
				$product          = array( 'id' => $product_id, 'text' => $product_title );
				$found_products[] = $product;
			}
		}
		wp_send_json( $found_products );
	}

	public function admin_enqueue() {

		if ( isset( $_REQUEST['page'] ) && $_REQUEST['page'] == 'woo-lucky-wheel' ) {
			global $wp_scripts;
			$scripts = $wp_scripts->registered;
			foreach ( $scripts as $k => $script ) {
				preg_match( '/select2/i', $k, $result );
				if ( count( array_filter( $result ) ) ) {
					unset( $wp_scripts->registered[ $k ] );
					wp_dequeue_script( $script->handle );
				}
				preg_match( '/bootstrap/i', $k, $result );
				if ( count( array_filter( $result ) ) ) {
					unset( $wp_scripts->registered[ $k ] );
					wp_dequeue_script( $script->handle );
				}
			}
			wp_enqueue_script( 'woocommerce-lucky-wheel-fontselect-js', VI_WOO_LUCKY_WHEEL_JS . 'jquery.fontselect.min.js', array( 'jquery' ) );
			wp_enqueue_style( 'woocommerce-lucky-wheel-fontselect-css', VI_WOO_LUCKY_WHEEL_CSS . 'fontselect-default.css' );

			wp_enqueue_script( 'woocommerce-lucky-wheel-semantic-js-form', VI_WOO_LUCKY_WHEEL_JS . 'form.js', array( 'jquery' ) );
			wp_enqueue_style( 'woocommerce-lucky-wheel-semantic-css-form', VI_WOO_LUCKY_WHEEL_CSS . 'form.min.css' );
			wp_enqueue_script( 'woocommerce-lucky-wheel-semantic-js-transition', VI_WOO_LUCKY_WHEEL_JS . 'transition.min.js', array( 'jquery' ) );
			wp_enqueue_style( 'woocommerce-lucky-wheel-semantic-css-transition', VI_WOO_LUCKY_WHEEL_CSS . 'transition.min.css' );
			wp_enqueue_script( 'woocommerce-lucky-wheel-semantic-js-dropdown', VI_WOO_LUCKY_WHEEL_JS . 'dropdown.min.js', array( 'jquery' ) );
			wp_enqueue_style( 'woocommerce-lucky-wheel-semantic-css-dropdown', VI_WOO_LUCKY_WHEEL_CSS . 'dropdown.min.css' );
			wp_enqueue_script( 'woocommerce-lucky-wheel-semantic-js-checkbox', VI_WOO_LUCKY_WHEEL_JS . 'checkbox.js', array( 'jquery' ) );
			wp_enqueue_style( 'woocommerce-lucky-wheel-semantic-css-checkbox', VI_WOO_LUCKY_WHEEL_CSS . 'checkbox.min.css' );
			wp_enqueue_script( 'woocommerce-lucky-wheel-select2-js', VI_WOO_LUCKY_WHEEL_JS . 'select2.js', array( 'jquery' ) );
			wp_enqueue_style( 'woocommerce-lucky-wheel-select2-css', VI_WOO_LUCKY_WHEEL_CSS . 'select2.min.css' );
			wp_enqueue_script( 'woocommerce-lucky-wheel-semantic-js-tab', VI_WOO_LUCKY_WHEEL_JS . 'tab.js', array( 'jquery' ) );
			wp_enqueue_style( 'woocommerce-lucky-wheel-semantic-css-tab', VI_WOO_LUCKY_WHEEL_CSS . 'tab.css' );
			wp_enqueue_style( 'woocommerce-lucky-wheel-semantic-css-input', VI_WOO_LUCKY_WHEEL_CSS . 'button.min.css' );
			wp_enqueue_style( 'woocommerce-lucky-wheel-semantic-css-table', VI_WOO_LUCKY_WHEEL_CSS . 'table.min.css' );
			wp_enqueue_style( 'woocommerce-lucky-wheel-semantic-css-segment', VI_WOO_LUCKY_WHEEL_CSS . 'segment.min.css' );
			wp_enqueue_style( 'woocommerce-lucky-wheel-semantic-css-label', VI_WOO_LUCKY_WHEEL_CSS . 'label.min.css' );
			wp_enqueue_style( 'woocommerce-lucky-wheel-semantic-css-menu', VI_WOO_LUCKY_WHEEL_CSS . 'menu.min.css' );
			wp_enqueue_style( 'woocommerce-lucky-wheel-semantic-css-icon', VI_WOO_LUCKY_WHEEL_CSS . 'icon.min.css' );
			wp_enqueue_script( 'jquery-ui-sortable' );
			/*Color picker*/
			wp_enqueue_script(
				'iris', admin_url( 'js/iris.min.js' ), array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			), false, 1
			);

			wp_enqueue_script( 'media-upload' );
			if ( ! did_action( 'wp_enqueue_media' ) ) {
				wp_enqueue_media();
			}

			wp_enqueue_script( 'woocommerce-lucky-wheel-jquery-address-javascript', VI_WOO_LUCKY_WHEEL_JS . 'jquery.address-1.6.min.js', array( 'jquery' ), VI_WOO_LUCKY_WHEEL_VERSION );
			wp_enqueue_script( 'woocommerce-lucky-wheel-admin-javascript', VI_WOO_LUCKY_WHEEL_JS . 'admin-javascript.js', array( 'jquery' ), VI_WOO_LUCKY_WHEEL_VERSION );
			wp_localize_script( 'woocommerce-lucky-wheel-admin-javascript', 'woo_lucky_wheel_params_admin', array(
				'url' => admin_url( 'admin-ajax.php' )
			) );
			wp_enqueue_style( 'woocommerce-lucky-wheel-admin-style', VI_WOO_LUCKY_WHEEL_CSS . 'admin-style.css', array(), VI_WOO_LUCKY_WHEEL_VERSION );
		}
		wp_enqueue_style( 'woocommerce-lucky-wheel-admin-icon-style', VI_WOO_LUCKY_WHEEL_CSS . 'admin-icon-style.css' );
	}

	public function settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
        <div class="wrap">
            <h2><?php esc_html_e( 'Lucky Wheel for WooCommerce Settings', 'woo-lucky-wheel' ); ?></h2>
            <form action="" method="POST" class="vi-ui form">
				<?php wp_nonce_field( 'wlwl_settings_page_save', 'wlwl_nonce_field' ); ?>
                <div class="vi-ui top attached tabular menu">
                    <div class="item active"
                         data-tab="general"><?php esc_html_e( 'General', 'woo-lucky-wheel' ); ?></div>
                    <div class="item"
                         data-tab="popup"><?php esc_html_e( 'Pop-up', 'woo-lucky-wheel' ); ?></div>
                    <div class="item"
                         data-tab="wheel-wrap"><?php esc_html_e( 'Wheel Background', 'woo-lucky-wheel' ); ?></div>
                    <div class="item"
                         data-tab="custom-fields"><?php esc_html_e( 'Custom Fields', 'woo-lucky-wheel' ); ?></div>
                    <div class="item"
                         data-tab="wheel"><?php esc_html_e( 'Wheel Settings', 'woo-lucky-wheel' ); ?></div>
                    <div class="item"
                         data-tab="result"><?php esc_html_e( 'Inform Result', 'woo-lucky-wheel' ); ?></div>
                    <div class="item"
                         data-tab="coupon"><?php esc_html_e( 'Unique Coupon', 'woo-lucky-wheel' ); ?></div>
                    <div class="item"
                         data-tab="email_api"><?php esc_html_e( 'Email API', 'woo-lucky-wheel' ); ?></div>
                </div>
                <div class="vi-ui bottom attached active tab segment" data-tab="general">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th>
                                <label for="wlwl_enable"><?php esc_html_e( 'Enable', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td colspan="2">
                                <div class="vi-ui toggle checkbox">
                                    <input type="checkbox" name="wlwl_enable"
                                           id="wlwl_enable" <?php checked( $this->settings->get_params( 'general', 'enable' ), 'on' ) ?>>
                                    <label></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wlwl_enable_mobile"><?php esc_html_e( 'Enable mobile', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td colspan="2">
                                <div class="vi-ui toggle checkbox">
                                    <input type="checkbox" name="wlwl_enable_mobile"
                                           id="wlwl_enable_mobile" <?php checked( $this->settings->get_params( 'general', 'mobile' ), 'on' ) ?>>
                                    <label></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="ajax_endpoint"><?php esc_html_e( 'Ajax endpoint', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td colspan="2">
                                <div class="vi-ui toggle checkbox">
                                    <input type="radio" name="ajax_endpoint"
                                           id="ajax_endpoint_ajax"
                                           value="ajax" <?php checked( $this->settings->get_params( 'ajax_endpoint' ), 'ajax' ) ?>>
                                    <label for="ajax_endpoint_ajax"><?php esc_html_e( 'Ajax', 'woo-lucky-wheel' ); ?></label>
                                </div>
                                <p>
                                <div class="vi-ui toggle checkbox">
                                    <input type="radio" name="ajax_endpoint"
                                           id="ajax_endpoint_rest_api"
                                           value="rest_api" <?php checked( $this->settings->get_params( 'ajax_endpoint' ), 'rest_api' ) ?>>
                                    <label for="ajax_endpoint_rest_api"><?php esc_html_e( 'REST API', 'woo-lucky-wheel' ); ?></label>
                                </div>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wlwl_spin_num"><?php esc_html_e( 'Times spinning per email', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td colspan="2">
                                <input type="number" id="wlwl_spin_num" name="wlwl_spin_num" min="1"
                                       value="<?php echo esc_attr( $this->settings->get_params( 'general', 'spin_num' ) ); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wlwl_delay"><?php esc_html_e( 'Delay between each spin of an email', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <input type="number" id="wlwl_delay" name="wlwl_delay"
                                       min="0"
                                       value="<?php echo esc_attr( $this->settings->get_params( 'general', 'delay' ) ); ?>">
                            </td>
                            <td>
                                <select name="wlwl_delay_unit" class="vi-ui fluid dropdown">
                                    <option value="s" <?php selected( $this->settings->get_params( 'general', 'delay_unit' ), 's' ) ?>>
										<?php esc_html_e( 'Seconds', 'woo-lucky-wheel' ); ?>
                                    </option>
                                    <option value="m" <?php selected( $this->settings->get_params( 'general', 'delay_unit' ), 'm' ) ?>><?php esc_html_e( 'Minutes', 'woo-lucky-wheel' ); ?></option>
                                    <option value="h" <?php selected( $this->settings->get_params( 'general', 'delay_unit' ), 'h' ) ?>><?php esc_html_e( 'Hours', 'woo-lucky-wheel' ); ?></option>
                                    <option value="d" <?php selected( $this->settings->get_params( 'general', 'delay_unit' ), 'd' ) ?>><?php esc_html_e( 'Days', 'woo-lucky-wheel' ); ?></option>
                                </select>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="vi-ui bottom attached tab segment" data-tab="popup">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Custom popup icon', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td colspan="2">
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wheel_popup_icon_color"><?php esc_html_e( 'Custom popup icon color', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td colspan="2">
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wheel_popup_icon_bg_color"><?php esc_html_e( 'Custom popup icon background color', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td colspan="2">
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="notify_position"><?php esc_html_e( 'Popup icon position', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td colspan="2">
                                <select name="notify_position" id="notify_position" class="vi-ui fluid dropdown">
                                    <option value="top-left" <?php selected( $this->settings->get_params( 'notify', 'position' ), 'top-left' ) ?>><?php esc_html_e( 'Top Left', 'woo-lucky-wheel' ); ?></option>
                                    <option value="top-right" <?php selected( $this->settings->get_params( 'notify', 'position' ), 'top-right' ) ?>><?php esc_html_e( 'Top Right', 'woo-lucky-wheel' ); ?></option>
                                    <option value="middle-left" <?php selected( $this->settings->get_params( 'notify', 'position' ), 'middle-left' ) ?>><?php esc_html_e( 'Middle Left', 'woo-lucky-wheel' ); ?></option>
                                    <option value="middle-right" <?php selected( $this->settings->get_params( 'notify', 'position' ), 'middle-right' ) ?>><?php esc_html_e( 'Middle Right', 'woo-lucky-wheel' ); ?></option>
                                    <option value="bottom-left" <?php selected( $this->settings->get_params( 'notify', 'position' ), 'bottom-left' ) ?>><?php esc_html_e( 'Bottom Left', 'woo-lucky-wheel' ); ?></option>
                                    <option value="bottom-right" <?php selected( $this->settings->get_params( 'notify', 'position' ), 'bottom-right' ) ?>><?php esc_html_e( 'Bottom Right', 'woo-lucky-wheel' ); ?></option>
                                </select>
                                <p><?php esc_html_e( 'Position of the popup on screen', 'woo-lucky-wheel' ); ?></p>
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <label for="notify_intent"><?php esc_html_e( 'Select intent', 'woo-lucky-wheel' ); ?>
                                </label>
                            </th>
                            <td colspan="2">
                                <select name="notify_intent" class="vi-ui fluid dropdown">
                                    <option value="popup_icon" <?php selected( $this->settings->get_params( 'notify', 'intent' ), 'popup_icon' ) ?>><?php esc_html_e( 'Popup icon', 'woo-lucky-wheel' ); ?></option>
                                    <option value="show_wheel" <?php selected( $this->settings->get_params( 'notify', 'intent' ), 'show_wheel' ) ?>><?php esc_html_e( 'Automatically show wheel after initial time', 'woo-lucky-wheel' ); ?></option>
                                    <option value="on_scroll"
                                            disabled><?php esc_html_e( 'Show wheel after users scroll down a specific value - Premium version only', 'woo-lucky-wheel' ); ?></option>
                                    <option value="on_exit"
                                            disabled><?php esc_html_e( 'Show wheel when users move mouse over the top to close browser - Premium version only', 'woo-lucky-wheel' ); ?></option>
                                    <option value="random"
                                            disabled><?php esc_html_e( 'Random one of these above - Premium version only', 'woo-lucky-wheel' ); ?></option>
                                </select>
                            </td>

                        </tr>
                        <tr>
                            <th>
                                <label for="show_wheel"><?php esc_html_e( 'Initial time', 'woo-lucky-wheel' ); ?>
                                </label>
                            </th>
                            <td colspan="2">
                                <input type="text" id="show_wheel" name="show_wheel"
                                       value="<?php echo esc_attr( $this->settings->get_params( 'notify', 'show_wheel' ) ); ?>"><?php esc_html_e( 'Enter min,max to set random between min and max (seconds).', 'woo-lucky-wheel' ); ?>
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <label for="notify_hide_popup"><?php esc_html_e( 'Hide popup icon', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td colspan="2">
                                <div class="vi-ui toggle checkbox">
                                    <input type="checkbox" name="notify_hide_popup"
                                           id="notify_hide_popup" <?php checked( $this->settings->get_params( 'notify', 'hide_popup' ), 'on' ) ?>>
                                    <label></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="notify_time_on_close"><?php esc_html_e( 'If customers close and not spin, show popup again after', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <input type="number" id="notify_time_on_close" name="notify_time_on_close"
                                       min="0"
                                       value="<?php echo esc_attr( $this->settings->get_params( 'notify', 'time_on_close' ) ); ?>">
                            </td>
                            <td>
                                <select name="notify_time_on_close_unit" class="vi-ui fluid dropdown">
                                    <option value="m" <?php selected( $this->settings->get_params( 'notify', 'time_on_close_unit' ), 'm' ) ?>><?php esc_html_e( 'Minutes', 'woo-lucky-wheel' ); ?></option>
                                    <option value="h" <?php selected( $this->settings->get_params( 'notify', 'time_on_close_unit' ), 'h' ) ?>><?php esc_html_e( 'Hours', 'woo-lucky-wheel' ); ?></option>
                                    <option value="d" <?php selected( $this->settings->get_params( 'notify', 'time_on_close_unit' ), 'd' ) ?>><?php esc_html_e( 'Days', 'woo-lucky-wheel' ); ?></option>
                                </select>
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <label for="notify_show_again"><?php esc_html_e( 'When finishing a spin, show popup again after', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <input type="number" id="notify_show_again" name="notify_show_again"
                                       min="0"
                                       value="<?php echo esc_attr( $this->settings->get_params( 'notify', 'show_again' ) ); ?>">
                            </td>
                            <td>
                                <select name="notify_show_again_unit" class="vi-ui fluid dropdown">
                                    <option value="s" <?php selected( $this->settings->get_params( 'notify', 'show_again_unit' ), 's' ) ?>><?php esc_html_e( 'Seconds', 'woo-lucky-wheel' ); ?></option>
                                    <option value="m" <?php selected( $this->settings->get_params( 'notify', 'show_again_unit' ), 'm' ) ?>><?php esc_html_e( 'Minutes', 'woo-lucky-wheel' ); ?></option>
                                    <option value="h" <?php selected( $this->settings->get_params( 'notify', 'show_again_unit' ), 'h' ) ?>><?php esc_html_e( 'Hours', 'woo-lucky-wheel' ); ?></option>
                                    <option value="d" <?php selected( $this->settings->get_params( 'notify', 'show_again_unit' ), 'd' ) ?>><?php esc_html_e( 'Days', 'woo-lucky-wheel' ); ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="notify_frontpage_only"><?php esc_html_e( 'Show only on Homepage', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td colspan="2">
                                <div class="vi-ui toggle checkbox">
                                    <input type="checkbox" name="notify_frontpage_only"
                                           id="notify_frontpage_only" <?php checked( $this->settings->get_params( 'notify', 'show_only_front' ), 'on' ) ?>>
                                    <label></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="notify_blogpage_only"><?php esc_html_e( 'Show only on Blog page', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td colspan="2">
                                <div class="vi-ui toggle checkbox">
                                    <input type="checkbox" name="notify_blogpage_only"
                                           id="notify_blogpage_only" <?php checked( $this->settings->get_params( 'notify', 'show_only_blog' ), 'on' ) ?>>
                                    <label></label>
                                </div>
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <label for="notify_shop_only"><?php esc_html_e( 'Show only on Shop page', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td colspan="2">
                                <div class="vi-ui toggle checkbox">
                                    <input type="checkbox" name="notify_shop_only"
                                           id="notify_shop_only" <?php checked( $this->settings->get_params( 'notify', 'show_only_shop' ), 'on' ) ?>>
                                    <label></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="notify_conditional_tags"><?php esc_html_e( 'Conditional tags', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td colspan="2">
                                <input type="text" name="notify_conditional_tags"
                                       placeholder="<?php esc_html_e( 'Ex: !is_page(array(123,41,20))', 'woo-lucky-wheel' ) ?>"
                                       id="notify_conditional_tags"
                                       value="<?php if ( $this->settings->get_params( 'notify', 'conditional_tags' ) ) {
									       echo esc_attr(htmlentities( $this->settings->get_params( 'notify', 'conditional_tags' ) ));
								       } ?>">
                                <p class="description"><?php esc_html_e( 'Let you control on which pages Lucky Wheel for Woocommerce appears using ', 'woo-lucky-wheel' ) ?>
                                    <a href="http://codex.wordpress.org/Conditional_Tags"><?php esc_html_e( 'WP\'s conditional tags', 'woo-lucky-wheel' ) ?></a>
                                </p>
                                <p class="description">
                                    <strong>*</strong><?php esc_html_e( '"Home page", "Blog page" and "Shop page" options above must be disabled to run these conditional tags.', 'woo-lucky-wheel' ) ?>
                                </p>
                                <p class="description"><?php esc_html_e( 'Use ', 'woo-lucky-wheel' ); ?>
                                    <strong>is_cart()</strong><?php esc_html_e( ' to show only on cart page', 'woo-lucky-wheel' ) ?>
                                </p>
                                <p class="description"><?php esc_html_e( 'Use ', 'woo-lucky-wheel' ); ?>
                                    <strong>is_checkout()</strong><?php esc_html_e( ' to show only on checkout page', 'woo-lucky-wheel' ) ?>
                                </p>
                                <p class="description"><?php esc_html_e( 'Use ', 'woo-lucky-wheel' ); ?>
                                    <strong>is_product_category()</strong><?php esc_html_e( 'to show only on WooCommerce category page', 'woo-lucky-wheel' ) ?>
                                </p>
                                <p class="description"><?php esc_html_e( 'Use ', 'woo-lucky-wheel' ); ?>
                                    <strong>is_shop()</strong><?php esc_html_e( ' to show only on WooCommerce shop page', 'woo-lucky-wheel' ) ?>
                                </p>
                                <p class="description"><?php esc_html_e( 'Use ', 'woo-lucky-wheel' ); ?>
                                    <strong>is_product()</strong><?php esc_html_e( ' to show only on WooCommerce single product page', 'woo-lucky-wheel' ) ?>
                                </p>
                                <p class="description">
                                    <strong>**</strong><?php esc_html_e( 'Combining 2 or more conditionals using || to show wheel if 1 of the conditionals matched. e.g use ', 'woo-lucky-wheel' ); ?>
                                    <strong>is_cart() ||
                                        is_checkout()</strong><?php esc_html_e( ' to show only on cart page and checkout page', 'woo-lucky-wheel' ) ?>
                                </p>
                                <p class="description">
                                    <strong>***</strong><?php esc_html_e( 'Use exclamation mark(!) before a conditional to hide wheel if the conditional matched. e.g use ', 'woo-lucky-wheel' ); ?>
                                    <strong>!is_home()</strong><?php esc_html_e( ' to hide wheel on homepage', 'woo-lucky-wheel' ) ?>
                                </p>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="vi-ui bottom attached tab segment" data-tab="wheel-wrap">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th>
                                <label for="wheel_wrap_bg_image"><?php esc_html_e( 'Background image', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td id="wlwl-bg-image">
								<?php
								if ( $this->settings->get_params( 'wheel_wrap', 'bg_image' ) ) {
									$bg_image_url = wc_is_valid_url( $this->settings->get_params( 'wheel_wrap', 'bg_image' ) ) ? $this->settings->get_params( 'wheel_wrap', 'bg_image' ) : wp_get_attachment_url( $this->settings->get_params( 'wheel_wrap', 'bg_image' ) );
									?>
                                    <div class="wlwl-image-container">
                                        <img style="border: 1px solid;width: 300px;" class="review-images"
                                             src="<?php echo esc_attr( $bg_image_url ); ?>"/>
                                        <input class="wheel_wrap_bg_image" name="wheel_wrap_bg_image"
                                               type="hidden"
                                               value="<?php echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'bg_image' ) ); ?>"/>
                                        <span class="wlwl-remove-image negative vi-ui button"><?php esc_html_e( 'Remove', 'woo-lucky-wheel' ); ?></span>
                                    </div>
                                    <div id="wlwl-new-image" style="float: left;">
                                    </div>
                                    <span style="display: none;"
                                          class="positive vi-ui button wlwl-upload-custom-img"><?php esc_html_e( 'Add Image', 'woo-lucky-wheel' ); ?></span>
									<?php

								} else {
									?>
                                    <div id="wlwl-new-image" style="float: left;">
                                    </div>
                                    <span class="positive vi-ui button wlwl-upload-custom-img"><?php esc_html_e( 'Add Image', 'woo-lucky-wheel' ); ?></span>
									<?php
								}
								?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wheel_wrap_bg_color"><?php esc_html_e( 'Background color', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <input name="wheel_wrap_bg_color" id="wheel_wrap_bg_color" type="text"
                                       class="color-picker"
                                       value="<?php if ( $this->settings->get_params( 'wheel_wrap', 'bg_color' ) ) {
									       echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'bg_color' ) );
								       } ?>"
                                       style="background: <?php if ( $this->settings->get_params( 'wheel_wrap', 'bg_color' ) ) {
									       echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'bg_color' ) );
								       } ?>;"/>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="background_effect"><?php esc_html_e( 'Background effect', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wheel_wrap_text_color"><?php esc_html_e( 'Text color', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <input name="wheel_wrap_text_color" id="wheel_wrap_text_color" type="text"
                                       class="color-picker"
                                       value="<?php if ( $this->settings->get_params( 'wheel_wrap', 'text_color' ) ) {
									       echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'text_color' ) );
								       } ?>"
                                       style="background: <?php if ( $this->settings->get_params( 'wheel_wrap', 'text_color' ) ) {
									       echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'text_color' ) );
								       } ?>;"/>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wheel_wrap_description"><?php esc_html_e( 'Wheel description', 'woo-lucky-wheel' ); ?>
                                </label>
                            </th>
                            <td>
								<?php $desc_option = array( 'editor_height' => 200, 'media_buttons' => true );
								wp_editor( stripslashes( $this->settings->get_params( 'wheel_wrap', 'description' ) ), 'wheel_wrap_description', $desc_option ); ?>

                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wheel_wrap_spin_button"><?php esc_html_e( 'Button spin', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <input type="text" name="wheel_wrap_spin_button" id="wheel_wrap_spin_button"
                                       value="<?php if ( $this->settings->get_params( 'wheel_wrap', 'spin_button' ) ) {
									       echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'spin_button' ) );
								       } ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wheel_wrap_spin_button_color"><?php esc_html_e( 'Button spin color', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <input type="text" class="color-picker" name="wheel_wrap_spin_button_color"
                                       id="wheel_wrap_spin_button_color"
                                       value="<?php if ( $this->settings->get_params( 'wheel_wrap', 'spin_button_color' ) ) {
									       echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'spin_button_color' ) );
								       } ?>"
                                       style="background-color:<?php if ( $this->settings->get_params( 'wheel_wrap', 'spin_button_color' ) ) {
									       echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'spin_button_color' ) );
								       } ?>;">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wheel_wrap_spin_button_bg_color"><?php esc_html_e( 'Button spin background color', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <input type="text" class="color-picker" name="wheel_wrap_spin_button_bg_color"
                                       id="wheel_wrap_spin_button_bg_color"
                                       value="<?php if ( $this->settings->get_params( 'wheel_wrap', 'spin_button_bg_color' ) ) {
									       echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'spin_button_bg_color' ) );
								       } ?>"
                                       style="background-color:<?php if ( $this->settings->get_params( 'wheel_wrap', 'spin_button_bg_color' ) ) {
									       echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'spin_button_bg_color' ) );
								       } ?>;">
                            </td>
                        </tr>


                        <tr>
                            <th>
                                <label for="wheel_wrap_close_option"><?php esc_html_e( 'Show text option to not display wheel again', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input type="checkbox" name="wheel_wrap_close_option"
                                           id="wheel_wrap_close_option" <?php checked( $this->settings->get_params( 'wheel_wrap', 'close_option' ), 'on' ) ?>>
                                    <label></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wlwl-google-font-select"><?php esc_html_e( 'Select font', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>

                                <input type="text" name="wlwl_google_font_select"
                                       id="wlwl-google-font-select"
                                       value="<?php echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'font' ) ) ?>"><span
                                        class="wlwl-google-font-select-remove wlwl-cancel"
                                        style="<?php if ( ! $this->settings->get_params( 'wheel_wrap', 'font' ) ) {
											echo esc_attr( 'display:none' );
										} ?>"></span>

                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="gdpr_policy"><?php esc_html_e( 'GDPR checkbox', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input class="gdpr_policy" type="checkbox" id="gdpr_policy"
                                           name="gdpr_policy"
                                           value="on" <?php checked( $this->settings->get_params( 'wheel_wrap', 'gdpr' ), 'on' ) ?>>
                                    <label></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="gdpr_message"><?php esc_html_e( 'GDPR message', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
								<?php
								$option = array( 'editor_height' => 300, 'media_buttons' => false );
								wp_editor( stripslashes( $this->settings->get_params( 'wheel_wrap', 'gdpr_message' ) ), 'gdpr_message', $option );
								?>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="custom_css"><?php esc_html_e( 'Custom css', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <textarea
                                        name="custom_css"><?php echo wp_kses_post( $this->settings->get_params( 'wheel_wrap', 'custom_css' ) ) ?></textarea>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="vi-ui bottom attached tab segment" data-tab="custom-fields">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th>
                                <label for="custom_field_name_enable"><?php esc_html_e( 'Enable field name', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input class="custom_field_name_enable" type="checkbox"
                                           id="custom_field_name_enable"
                                           name="custom_field_name_enable"
                                           value="on" <?php checked( $this->settings->get_params( 'custom_field_name_enable' ), 'on' ) ?>>
                                    <label></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="custom_field_name_enable_mobile"><?php esc_html_e( 'Enable field name on mobile', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input class="custom_field_name_enable_mobile" type="checkbox"
                                           id="custom_field_name_enable_mobile"
                                           name="custom_field_name_enable_mobile"
                                           value="on" <?php checked( $this->settings->get_params( 'custom_field_name_enable_mobile' ), 'on' ) ?>>
                                    <label></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="custom_field_name_required"><?php esc_html_e( 'Field name is required', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input class="custom_field_name_required" type="checkbox"
                                           id="custom_field_name_required"
                                           name="custom_field_name_required"
                                           value="on" <?php checked( $this->settings->get_params( 'custom_field_name_required' ), 'on' ) ?>>
                                    <label></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                        </tr>
                        <tr>
                            <th>
                                <label for="custom_field_mobile_enable"><?php esc_html_e( 'Enable field mobile', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="custom_field_mobile_enable_mobile"><?php esc_html_e( 'Enable field mobile on mobile', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="custom_field_mobile_required"><?php esc_html_e( 'Field mobile is required', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="vi-ui bottom attached tab segment" data-tab="wheel">
                    <span class="vi-ui positive button preview-lucky-wheel"><?php esc_html_e( 'Preview Wheel', 'woo-lucky-wheel' ); ?></span>
                    <table class="form-table wheel-settings">
                        <tbody class="content">
                        <tr>
                            <th>
                                <label for="pointer_position"><?php esc_html_e( 'Pointer position', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>

                                <select name="pointer_position" id="pointer_position" class="vi-ui fluid dropdown">
                                    <option value="center" <?php selected( $this->settings->get_params( 'wheel_wrap', 'pointer_position' ), 'center' ) ?>><?php esc_html_e( 'Center', 'woo-lucky-wheel' ); ?></option>
                                    <option value="top"
                                            disabled><?php esc_html_e( 'Top - Premium version only', 'woo-lucky-wheel' ); ?></option>
                                    <option value="right"
                                            disabled><?php esc_html_e( 'Right - Premium version only', 'woo-lucky-wheel' ); ?></option>
                                    <option value="bottom"
                                            disabled><?php esc_html_e( 'Bottom - Premium version only', 'woo-lucky-wheel' ); ?></option>
                                    <option value="random"
                                            disabled><?php esc_html_e( 'Random - Premium version only', 'woo-lucky-wheel' ); ?></option>
                                </select>

                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="pointer_color"><?php esc_html_e( 'Wheel pointer color', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <input name="pointer_color" id="pointer_color" type="text"
                                       class="color-picker"
                                       value="<?php if ( $this->settings->get_params( 'wheel_wrap', 'pointer_color' ) ) {
									       echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'pointer_color' ) );
								       } ?>"
                                       style="background-color: <?php if ( $this->settings->get_params( 'wheel_wrap', 'pointer_color' ) ) {
									       echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'pointer_color' ) );
								       } ?>;"/>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wlwl-center-image1"><?php esc_html_e( 'Wheel center background image', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td id="wlwl-bg-image1">
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>


                        <tr>
                            <th>
                                <label for="wheel_center_color"><?php esc_html_e( 'Wheel center color', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <input name="wheel_center_color" id="wheel_center_color" type="text"
                                       class="color-picker"
                                       value="<?php if ( $this->settings->get_params( 'wheel_wrap', 'wheel_center_color' ) ) {
									       echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'wheel_center_color' ) );
								       } ?>"
                                       style="background-color: <?php if ( $this->settings->get_params( 'wheel_wrap', 'wheel_center_color' ) ) {
									       echo esc_attr( $this->settings->get_params( 'wheel_wrap', 'wheel_center_color' ) );
								       } ?>;"/>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wheel_border_color"><?php esc_html_e( 'Wheel border color', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wheel_dot_color"><?php esc_html_e( 'Wheel border dot color', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <label for="wlwl-currency"><?php esc_html_e( 'Displayed Currency', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wheel_spinning_time">
									<?php esc_html_e( 'Wheel spinning duration', 'woo-lucky-wheel' ); ?>
                                </label>
                            </th>
                            <td colspan="4">
                                <div class="inline fields">
                                    <a class="vi-ui button" target="_blank"
                                       href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                                    <input type="hidden" name="wheel_label_coupon" id="wheel_label_coupon"
                                           class="wheel_label_coupon"
                                           value="<?php if ( $this->settings->get_params( 'wheel', 'label_coupon' ) ) {
										       echo esc_attr( $this->settings->get_params( 'wheel', 'label_coupon' ) );
									       } ?>">
                                </div>
                                <p><?php esc_html_e( 'From 3s to 15s.', 'woo-lucky-wheel' ); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wheel_speed"><?php esc_html_e( 'Wheel speed', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <select id="wheel_speed" class="vi-ui fluid dropdown">
									<?php
									for ( $i = 1; $i <= 10; $i ++ ) {
										if ( $i == 5 ) {
											?>
                                            <option value="<?php esc_attr_e( $i ) ?>"
                                                    selected><?php esc_html_e( $i ); ?></option>
											<?php
										} else {
											?>
                                            <option value="<?php esc_attr_e( $i ) ?>"
                                                    disabled><?php printf( esc_html__( '%s - Premium version only', 'woo-lucky-wheel' ), $i ); ?></option>
											<?php
										}
									}
									?>
                                </select>

                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="show_full_wheel"><?php esc_html_e( 'Show full wheel', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input class="show_full_wheel" type="checkbox" id="show_full_wheel"
                                           name="show_full_wheel"
                                           value="on" <?php checked( $this->settings->get_params( 'wheel', 'show_full_wheel' ), 'on' ) ?>>
                                    <label><?php esc_html_e( 'Make all wheel slices visible on desktop', 'woo-lucky-wheel' ) ?></label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="font_size"><?php esc_html_e( 'Adjust font size of text on the wheel by(%)', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wheel_size"><?php esc_html_e( 'Adjust wheel size by(%)', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="slice_text_color"><?php esc_html_e( 'Slices text color', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <input name="slice_text_color" id="slice_text_color" type="text"
                                       class="color-picker"
                                       value="<?php echo esc_attr( $this->settings->get_params( 'wheel', 'slice_text_color' ) ); ?>"
                                       style="background-color: <?php echo esc_attr( $this->settings->get_params( 'wheel', 'slice_text_color' ) ); ?>;"/>
                            </td>
                        </tr>
                        </tbody>

                    </table>
                    <table class="form-table wheel-settings" style="margin-top: 0;">
                        <tbody>
                        <tr class="wheel-slices" style="background-color: #000000;">
                            <td width="40"><?php esc_attr_e( 'Index', 'woo-lucky-wheel' ) ?></td>
                            <td><?php esc_attr_e( 'Coupon Type', 'woo-lucky-wheel' ) ?></td>
                            <td><?php esc_attr_e( 'Label', 'woo-lucky-wheel' ) ?></td>
                            <td><?php esc_attr_e( 'Value', 'woo-lucky-wheel' ) ?></td>
                            <td><?php esc_attr_e( 'Probability(%)', 'woo-lucky-wheel' ) ?></td>
                            <td><?php esc_attr_e( 'Color', 'woo-lucky-wheel' ) ?></td>
                        </tr>
                        <tr>
                            <td colspan="6">
                                <p><?php esc_html_e( 'Use {coupon_amount} for WooCommerce coupon type to refer to the amount of that coupon. e.g: Coupon type is percentage discount, coupon value is 10 then {coupon_amount} will become 10% when printing out on the wheel.', 'woo-lucky-wheel' ); ?></p>
                            </td>
                        </tr>
                        </tbody>
                        <tbody class="ui-sortable">
						<?php
						for ( $count = 0; $count < count( $this->settings->get_params( 'wheel', 'coupon_type' ) ); $count ++ ) {
							?>
                            <tr class="wheel_col">
                                <td class="wheel_col_index" width="40"><?php echo( $count + 1 ); ?></td>
                                <td class="wheel_col_coupons">
                                    <select name="coupon_type[]" class="coupons_select vi-ui fluid dropdown">
                                        <option value="non" <?php selected( $this->settings->get_params( 'wheel', 'coupon_type' )[ $count ], 'non' ); ?>><?php esc_attr_e( 'Non', 'woo-lucky-wheel' ) ?></option>
                                        <option value="existing_coupon" <?php selected( $this->settings->get_params( 'wheel', 'coupon_type' )[ $count ], 'existing_coupon' ); ?>><?php esc_attr_e( 'Existing coupon', 'woo-lucky-wheel' ) ?></option>
                                        <option value="percent" <?php selected( $this->settings->get_params( 'wheel', 'coupon_type' )[ $count ], 'percent' ); ?>><?php esc_attr_e( 'Percentage discount', 'woo-lucky-wheel' ) ?></option>
                                        <option value="fixed_product" <?php selected( $this->settings->get_params( 'wheel', 'coupon_type' )[ $count ], 'fixed_product' ); ?>><?php esc_attr_e( 'Fixed product discount', 'woo-lucky-wheel' ) ?></option>
                                        <option value="fixed_cart" <?php selected( $this->settings->get_params( 'wheel', 'coupon_type' )[ $count ], 'fixed_cart' ); ?>><?php esc_attr_e( 'Fixed cart discount', 'woo-lucky-wheel' ) ?></option>
                                        <option value="custom" <?php selected( $this->settings->get_params( 'wheel', 'coupon_type' )[ $count ], 'custom' ); ?>><?php esc_attr_e( 'Custom', 'woo-lucky-wheel' ) ?></option>
                                    </select>
                                </td>
                                <td class="wheel_col_coupons_value">
                                    <input type="text" name="custom_type_label[]"
										<?php
										echo ' class="custom_type_label" value="' . ( ( isset( $this->settings->get_params( 'wheel', 'custom_label' )[ $count ] ) && $this->settings->get_params( 'wheel', 'custom_label' )[ $count ] ) ? esc_attr( $this->settings->get_params( 'wheel', 'custom_label' )[ $count ] ) : esc_attr( $this->settings->get_params( 'wheel', 'label_coupon' ) ) ) . '"';
										?> placeholder="Label"/>
                                </td>
                                <td class="wheel_col_coupons_value">
                                    <input type="number" name="coupon_amount[]" min="0"
                                           class="coupon_amount <?php echo ( $this->settings->get_params( 'wheel', 'coupon_type' )[ $count ] == 'non' ) ? 'coupon-amount-readonly' : ''; ?>"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'wheel', 'coupon_amount' )[ $count ] ); ?>"
                                           placeholder="Coupon Amount"
                                           style="<?php if ( ! in_array( $this->settings->get_params( 'wheel', 'coupon_type' )[ $count ], array(
										       'percent',
										       'fixed_product',
										       'fixed_cart',
										       'non'
									       ) ) ) {
										       echo esc_attr( 'display:none;' );
									       } ?>" <?php if ( isset( $this->settings->get_params( 'wheel', 'coupon_type' )[ $count ] ) && $this->settings->get_params( 'wheel', 'coupon_type' )[ $count ] == 'non' ) {
										echo esc_attr( 'readonly' );
									} ?>/>
                                    <input type="text" name="custom_type_value[]" class="custom_type_value"
                                           value="<?php echo isset( $this->settings->get_params( 'wheel', 'custom_value' )[ $count ] ) ? esc_attr($this->settings->get_params( 'wheel', 'custom_value' )[ $count ]) : ''; ?>"
                                           placeholder="Value/Code"
                                           style="<?php if ( in_array( $this->settings->get_params( 'wheel', 'coupon_type' )[ $count ], array(
										       'existing_coupon',
										       'percent',
										       'fixed_product',
										       'fixed_cart',
										       'non'
									       ) ) ) {
										       echo esc_attr( 'display:none;' );
									       } ?>"/>
                                    <div class="wlwl_existing_coupon"
                                         style="<?php if ( $this->settings->get_params( 'wheel', 'coupon_type' )[ $count ] != 'existing_coupon' ) {
										     echo esc_attr( 'display:none;' );
									     } ?>">
                                        <select name="wlwl_existing_coupon[]"
                                                class="coupon-search wlwl_existing_coupon select2-selection--single"
                                                data-placeholder="<?php esc_html_e( 'Enter Code', 'woo-lucky-wheel' ) ?>">
											<?php
											if ( isset( $this->settings->get_params( 'wheel', 'existing_coupon' )[ $count ] ) && '' !== $this->settings->get_params( 'wheel', 'existing_coupon' )[ $count ] ) {
												echo '<option value="' . esc_attr( $this->settings->get_params( 'wheel', 'existing_coupon' )[ $count ]) . '" selected>' . ( isset( get_post( $this->settings->get_params( 'wheel', 'existing_coupon' )[ $count ] )->post_title ) ? esc_html(get_post( $this->settings->get_params( 'wheel', 'existing_coupon' )[ $count ] )->post_title) : "" ) . '</option>';
											} else {
												echo '<option value=""></option>';
											}
											?>
                                        </select>
                                    </div>

                                </td>
                                <td class="wheel_col_probability">
                                    <input type="number" name="probability[]"
                                           class="probability probability_<?php echo esc_attr( $count ); ?>" min="0"
                                           max="100" placeholder="Probability"
                                           value="<?php echo absint( $this->settings->get_params( 'wheel', 'probability' )[ $count ] ) ?>"/>
                                </td>
                                <td class="remove_field_wrap">
                                    <input type="text" id="bg_color" name="bg_color[]" class="color-picker"
                                           value=" <?php echo esc_attr(trim( $this->settings->get_params( 'wheel', 'bg_color' )[ $count ] )); ?>"
                                           style="background: <?php echo esc_attr(trim( $this->settings->get_params( 'wheel', 'bg_color' )[ $count ] )); ?>"/>
                                    <span class="remove_field negative vi-ui button"><?php esc_attr_e( 'Remove', 'woocommerce-lucky-wheel' ); ?></span>
                                    <span class="clone_piece positive vi-ui button"><?php esc_attr_e( 'Clone', 'woocommerce-lucky-wheel' ); ?></span>
                                </td>
                            </tr>
							<?php
						}
						?>
                        <tbody>
                        <tr>
                            <td class="col_add_new" colspan="3">
                                <i><?php esc_attr_e( 'Slices positions are sortable by drag and drop.', 'woo-lucky-wheel' ); ?></i>
                            </td>

                            <td class="col_add_new col_total_probability">
                                <i><?php esc_attr_e( '*The total Probability: ', 'woo-lucky-wheel' ); ?>
                                    <strong class="total_probability" data-total_probability=""> 100 </strong> (
                                    % )</i>
                            </td>
                            <td></td>
                            <td class="col_add_new">
								<?php
								self::auto_color();
								?>
                                <p>
                                    <span class="auto_color positive vi-ui button"><?php esc_attr_e( 'Auto Color', 'woo-lucky-wheel' ) ?></span>
                                </p>
                            </td>
                        </tr>

                        </tbody>
                    </table>
                </div>
                <div class="vi-ui bottom attached tab segment" data-tab="result">
                    <table class="form-table">
                        <tbody>
                        <tr>
                            <th>
                                <label for="result-auto_close"><?php esc_html_e( 'Automatically hide wheel after finishing spinning', 'woo-lucky-wheel' ); ?>
                                </label>
                            </th>
                            <td>
                                <div class="inline fields">
                                    <input type="number" name="result-auto_close" min="0"
                                           id="result-auto_close"
                                           value="<?php echo esc_attr(intval( $this->settings->get_params( 'result', 'auto_close' ) )) ?>">
									<?php esc_html_e( 'seconds', 'woo-lucky-wheel' ); ?>
                                </div>
                                <p><?php esc_html_e( 'Left 0 to disable this feature', 'woo-lucky-wheel' ); ?></p>

                            </td>

                        </tr>
                        <tr>
                            <th>
                                <label for="subject"><?php esc_html_e( 'Email subject', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <input id="subject" type="text" name="subject"
                                       value="<?php echo esc_attr(htmlentities( $this->settings->get_params( 'result', 'email' )['subject'] )); ?>">
								<?php esc_html_e( 'The subject of emails sending to customers which include discount coupon code.', 'woo-lucky-wheel' ) ?>
                                <p>{coupon_label}
                                    - <?php esc_html_e( 'Coupon label/custom label that customers win', 'woo-lucky-wheel' ) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="heading"><?php esc_html_e( 'Email heading', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <input id="heading" type="text" name="heading"
                                       value="<?php echo esc_attr(htmlentities( $this->settings->get_params( 'result', 'email' )['heading'] )); ?>">
								<?php esc_html_e( 'The heading of emails sending to customers which include discount coupon code.', 'woo-lucky-wheel' ) ?>
                                <p>{coupon_label}
                                    - <?php esc_html_e( 'Coupon label/custom label that customers win', 'woo-lucky-wheel' ) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="content"><?php esc_html_e( 'Email content', 'woo-lucky-wheel' ) ?></label>
                                <p><?php esc_html_e( 'The content of email sending to customers to inform them the coupon code they receive', 'woo-lucky-wheel' ) ?></p>
                            </th>
                            <td><?php $option = array( 'editor_height' => 300, 'media_buttons' => true );

								wp_editor( stripslashes( $this->settings->get_params( 'result', 'email' )['content'] ), 'content', $option ); ?></td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <ul>
                                    <li>{customer_name}
                                        - <?php esc_html_e( 'Customer\'s name.', 'woo-lucky-wheel' ) ?></li>
                                    <li>{coupon_code}
                                        - <?php esc_html_e( 'Coupon code/custom value will be sent to customer.', 'woo-lucky-wheel' ) ?></li>
                                    <li>{coupon_label}
                                        - <?php esc_html_e( 'Coupon label/custom label that customers win', 'woo-lucky-wheel' ) ?></li>
                                    <li>{date_expires}
                                        - <?php esc_html_e( 'Expiry date of the coupon.', 'woo-lucky-wheel' ) ?></li>
                                    <li>{shop_now}
                                        - <?php echo '<a class="wlwl-button-shop-now" href="' . esc_url( $this->settings->get_params( 'button_shop_url' ) ) . '" target="_blank" style="text-decoration:none;display:inline-block;padding:10px 30px;margin:10px 0;font-size: 16px;color:#ffffff;background:#000;">' . esc_html__( 'Shop now', 'woo-lucky-wheel' ) . '</a>'; ?></li>
                                </ul>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="wlwl_button_shop_url"><?php esc_html_e( 'Button "Shop now" URL', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <input type="text" name="wlwl_button_shop_url" id="wlwl_button_shop_url"
                                       value="<?php echo esc_attr(htmlentities( $this->settings->get_params( 'button_shop_url' ) )) ?>">
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="wlwl_suggested_products"><?php esc_html_e( 'Suggested products', 'woo-lucky-wheel' ) ?></label>

                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                                <p><?php esc_html_e( 'These products will be added at the end of email content with product image thumbnail, product title, product price and a button linked to product page which is design the same as button {shop_now}', 'woo-lucky-wheel' ) ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="wlwl_button_shop_title"><?php esc_html_e( 'Button "Shop now" title', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>


                        <tr valign="top">
                            <th scope="row">
                                <label for="wlwl_button_shop_color"><?php esc_html_e( 'Button "Shop now" color', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="wlwl_button_shop_bg_color"><?php esc_html_e( 'Button "Shop now" background color', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="wlwl_button_shop_size"><?php esc_html_e( 'Button "Shop now" font size(px)', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <label for="result_win"><?php esc_html_e( 'Frontend Message if win', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
								<?php $win_option = array( 'editor_height' => 200, 'media_buttons' => true );
								wp_editor( stripslashes( $this->settings->get_params( 'result', 'notification' )['win'] ), 'result_win', $win_option ); ?>
                            </td>
                        </tr>
                        <tr>
                            <td></td>
                            <td>
                                <ul>
                                    <li>{coupon_label}
                                        - <?php esc_html_e( 'Label of coupon that customers win', 'woo-lucky-wheel' ) ?></li>
                                    <li>{checkout}
                                        - <?php esc_html_e( '"Checkout" with link to checkout page', 'woo-lucky-wheel' ) ?></li>
                                    <li>{customer_name}
                                        - <?php esc_html_e( 'Customers\'name if they enter', 'woo-lucky-wheel' ) ?></li>
                                    <li>{customer_email}
                                        - <?php esc_html_e( 'Email that customers enter to spin', 'woo-lucky-wheel' ) ?></li>
                                    <li>{coupon_code}
                                        - <?php esc_html_e( 'Coupon code/custom value will be sent to customer.', 'woo-lucky-wheel' ) ?></li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label for="congratulations_effect"><?php esc_html_e( 'Winning effect', 'woo-lucky-wheel' ); ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>

                        <tr>
                            <th>
                                <label for="result_lost"><?php esc_html_e( 'Frontend message if lost', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
								<?php $lost_option = array( 'editor_height' => 200, 'media_buttons' => true );
								wp_editor( stripslashes( $this->settings->get_params( 'result', 'notification' )['lost'] ), 'result_lost', $lost_option ); ?>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="vi-ui bottom attached tab segment" data-tab="coupon">
                    <table class="form-table">
                        <tbody>

                        <tr class="wlwl-custom-coupon">
                            <th><?php esc_html_e( 'Allow free shipping', 'woo-lucky-wheel' ) ?></th>
                            <td>
                                <input type="checkbox"
                                       class="checkbox" <?php checked( $this->settings->get_params( 'coupon', 'allow_free_shipping' ), 'yes' ) ?>
                                       name="wlwl_free_shipping" id="wlwl_free_shipping" value="yes">
                                <label for="wlwl_free_shipping"><?php esc_html_e( 'Check this box if the coupon grants free shipping. A ', 'woo-lucky-wheel' ) ?>
                                    <a href="https://docs.woocommerce.com/document/free-shipping/"
                                       target="_blank"><?php esc_html_e( 'free shipping method', 'woo-lucky-wheel' ); ?></a><?php esc_html_e( ' must be enabled in your shipping zone and be set to require "a valid free shipping coupon" (see the "Free Shipping Requires" setting).', 'woo-lucky-wheel' ); ?>
                                </label>
                            </td>
                        </tr>
                        <tr class="wlwl-custom-coupon">
                            <th>
                                <label for="wlwl_expiry_date"><?php esc_html_e( 'Time to live', 'woo-lucky-wheel' ) ?></label>

                            </th>
                            <td>
                                <div class="inline fields">
                                    <input type="number" min="1" name="wlwl_expiry_date" id="wlwl_expiry_date"
                                           value="<?php echo esc_attr( $this->settings->get_params( 'coupon', 'expiry_date' ) ) ?>"><?php esc_html_e( 'Days', 'woo-lucky-wheel' ) ?>
                                </div>
                                <p><?php esc_html_e( 'Coupon will expire after x(days) since it\'s generated and sent', 'woo-lucky-wheel' ) ?></p>
                            </td>
                        </tr>
                        <tr class="wlwl-custom-coupon">
                            <th>
                                <label for="wlwl_min_spend"><?php esc_html_e( 'Minimum spend', 'woo-lucky-wheel' ) ?></label>

                            </th>
                            <td>
                                <input type="text" class="short wc_input_price" name="wlwl_min_spend"
                                       id="wlwl_min_spend"
                                       value="<?php echo esc_attr( $this->settings->get_params( 'coupon', 'min_spend' ) ); ?>"
                                       placeholder="<?php esc_html_e( 'No minimum', 'woo-lucky-wheel' ) ?>">
                                <p><?php esc_html_e( 'The minimum spend to use the coupon.', 'woo-lucky-wheel' ) ?></p>
                            </td>

                        </tr>
                        <tr class="wlwl-custom-coupon">
                            <th>
                                <label for="wlwl_max_spend"><?php esc_html_e( 'Maximum spend', 'woo-lucky-wheel' ) ?></label>

                            </th>
                            <td>
                                <input type="text" class="short wc_input_price" name="wlwl_max_spend"
                                       id="wlwl_max_spend"
                                       value="<?php echo esc_attr( $this->settings->get_params( 'coupon', 'max_spend' ) ); ?>"
                                       placeholder="<?php esc_html_e( 'No maximum', 'woo-lucky-wheel' ) ?>">
                                <p><?php esc_html_e( 'The maximum spend to use the coupon.', 'woo-lucky-wheel' ) ?></p>
                            </td>
                        </tr>
                        <tr class="wlwl-custom-coupon">
                            <th><?php esc_html_e( 'Individual use only', 'woo-lucky-wheel' ) ?></th>
                            <td>
                                <input type="checkbox" <?php checked( $this->settings->get_params( 'coupon', 'individual_use' ), 'yes' ) ?>
                                       class="checkbox" name="wlwl_individual_use" id="wlwl_individual_use"
                                       value="yes"><label
                                        for="wlwl_individual_use"><?php esc_html_e( 'Check this box if the coupon cannot be used in conjunction with other coupons.', 'woo-lucky-wheel' ) ?></label>
                            </td>
                        </tr>
                        <tr class="wlwl-custom-coupon">
                            <th><?php esc_html_e( 'Exclude sale items', 'woo-lucky-wheel' ) ?></th>
                            <td>
                                <input type="checkbox" <?php checked( $this->settings->get_params( 'coupon', 'exclude_sale_items' ), 'yes' ) ?>
                                       class="checkbox" name="wlwl_exclude_sale_items"
                                       id="wlwl_exclude_sale_items"
                                       value="yes"><label
                                        for="wlwl_exclude_sale_items"><?php esc_html_e( 'Check this box if the coupon should not apply to items on sale. Per-item coupons will only work if the item is not on sale. Per-cart coupons will only work if there are items in the cart that are not on sale.', 'woo-lucky-wheel' ) ?></label>
                            </td>
                        </tr>
                        <tr class="wlwl-custom-coupon">
                            <th>
                                <label for="wlwl_product_ids"><?php esc_html_e( 'Include Products', 'woo-lucky-wheel' ) ?></label>

                            </th>
                            <td>
                                <select id="wlwl_product_ids" name="wlwl_product_ids[]" multiple="multiple"
                                        class="product-search"
                                        data-placeholder="<?php esc_html_e( 'Please Fill In Your Product Title', 'woo-lucky-wheel' ) ?>">
									<?php
									$product_ids = $this->settings->get_params( 'coupon', 'product_ids' );
									if ( count( $product_ids ) ) {
										foreach ( $product_ids as $ps ) {
											$product = wc_get_product( $ps );
											if ( $product ) {
												?>
                                                <option selected
                                                        value="<?php echo esc_attr( $ps ) ?>"><?php echo esc_html( $product->get_title() ) ?></option>
												<?php
											}
										}
									}
									?>
                                </select>
                                <p><?php esc_html_e( 'Products that the coupon will be applied to, or that need to be in the cart in order for the "Fixed cart discount" to be applied', 'woo-lucky-wheel' ) ?></p>
                            </td>
                        </tr>
                        <tr class="wlwl-custom-coupon">
                            <th>
                                <label for="wlwl_exclude_product_ids"><?php esc_html_e( 'Exclude Products', 'woo-lucky-wheel' ) ?></label>

                            </th>
                            <td>
                                <select id="wlwl_exclude_product_ids" name="wlwl_exclude_product_ids[]"
                                        multiple="multiple"
                                        class="product-search"
                                        data-placeholder="<?php esc_html_e( 'Please Fill In Your Product Title', 'woo-lucky-wheel' ) ?>">
									<?php
									$exclude_product_ids = $this->settings->get_params( 'coupon', 'exclude_product_ids' );
									if ( count( $exclude_product_ids ) ) {
										foreach ( $exclude_product_ids as $ps ) {
											$product = wc_get_product( $ps );
											if ( $product ) {
												?>
                                                <option selected
                                                        value="<?php echo esc_attr( $ps ) ?>"><?php echo esc_html( $product->get_title() ) ?></option>
												<?php
											}
										}
									}
									?>
                                </select>
                                <p><?php esc_html_e( 'Products that the coupon will not be applied to, or that cannot be in the cart in order for the "Fixed cart discount" to be applied.', 'woo-lucky-wheel' ) ?></p>
                            </td>
                        </tr>
                        <tr class="wlwl-custom-coupon">
                            <th>
                                <label for="wlwl_product_categories"><?php esc_html_e( 'Include categories', 'woo-lucky-wheel' ) ?></label>

                            </th>
                            <td>
                                <select id="wlwl_product_categories" name="wlwl_product_categories[]"
                                        multiple="multiple"
                                        class="category-search"
                                        data-placeholder="<?php esc_html_e( 'Please enter categories name', 'woo-lucky-wheel' ) ?>">
									<?php
									$product_categories = $this->settings->get_params( 'coupon', 'product_categories' );
									if ( count( $product_categories ) ) {
										foreach ( $product_categories as $category_id ) {
											$category = get_term( $category_id );
											if ( $category ) {
												?>
                                                <option value="<?php echo esc_attr( $category_id ) ?>"
                                                        selected><?php echo esc_html( $category->name ); ?></option>
												<?php
											}
										}
									}
									?>
                                </select>
                                <p><?php esc_html_e( 'Product categories that the coupon will be applied to, or that need to be in the cart in order for the "Fixed cart discount" to be applied.', 'woo-lucky-wheel' ) ?></p>
                            </td>
                        </tr>
                        <tr class="wlwl-custom-coupon">
                            <th>
                                <label for="wlwl_exclude_product_categories"><?php esc_html_e( 'Exclude categories', 'woo-lucky-wheel' ) ?></label>

                            </th>
                            <td>
                                <select id="wlwl_exclude_product_categories" name="wlwl_exclude_product_categories[]"
                                        multiple="multiple"
                                        class="category-search"
                                        data-placeholder="<?php esc_html_e( 'Please enter categories name', 'woo-lucky-wheel' ) ?>">
									<?php
									$exclude_product_categories = $this->settings->get_params( 'coupon', 'exclude_product_categories' );
									if ( count( $exclude_product_categories ) ) {
										foreach ( $exclude_product_categories as $category_id ) {
											$category = get_term( $category_id );
											if ( $category ) {
												?>
                                                <option value="<?php echo esc_attr( $category_id ) ?>"
                                                        selected><?php echo esc_html( $category->name ); ?></option>
												<?php
											}
										}
									}
									?>
                                </select>
                                <p><?php esc_html_e( 'Product categories that the coupon will not be applied to, or that cannot be in the cart in order for the "Fixed cart discount" to be applied.', 'woo-lucky-wheel' ) ?></p>
                            </td>
                        </tr>

                        <tr class="wlwl-custom-coupon">
                            <th>
                                <label for="wlwl_limit_per_coupon"><?php esc_html_e( 'Usage limit per coupon', 'woo-lucky-wheel' ) ?></label>

                            </th>
                            <td>
                                <input type="number" class="short" name="wlwl_limit_per_coupon"
                                       id="wlwl_limit_per_coupon"
                                       value="<?php echo esc_attr( $this->settings->get_params( 'coupon', 'limit_per_coupon' ) ) ?>"
                                       placeholder="Unlimited usage" step="1" min="0">
                                <p><?php esc_html_e( 'How many times this coupon can be used before it is void.', 'woo-lucky-wheel' ) ?></p>
                            </td>
                        </tr>
                        <tr class="wlwl-custom-coupon">
                            <th>
                                <label for="wlwl_limit_to_x_items"><?php esc_html_e( 'Limit usage to X items', 'woo-lucky-wheel' ) ?></label>

                            </th>
                            <td>
                                <input type="number" class="short" name="wlwl_limit_to_x_items"
                                       id="wlwl_limit_to_x_items"
                                       value="<?php echo esc_attr( $this->settings->get_params( 'coupon', 'limit_to_x_items' ) ) ?>"
                                       placeholder="<?php esc_html_e( 'Apply To All Qualifying Items In Cart', 'woo-lucky-wheel' ) ?>"
                                       step="1" min="0">
                                <p><?php esc_html_e( 'The maximum number of individual items this coupon can apply to when using product discount.', 'woo-lucky-wheel' ) ?></p>
                            </td>
                        </tr>
                        <tr class="wlwl-custom-coupon">
                            <th>
                                <label for="wlwl_limit_per_user"><?php esc_html_e( 'Usage limit per user', 'woo-lucky-wheel' ) ?></label>

                            </th>
                            <td>
                                <input type="number" class="short" name="wlwl_limit_per_user"
                                       id="wlwl_limit_per_user"
                                       value="<?php echo esc_attr( $this->settings->get_params( 'coupon', 'limit_per_user' ) ) ?>"
                                       placeholder="<?php esc_html_e( 'Unlimited Usage', 'woo-lucky-wheel' ) ?>"
                                       step="1" min="0">
                                <p><?php esc_html_e( 'How many times this coupon can be used by an individual user.', 'woo-lucky-wheel' ) ?></p>
                            </td>
                        </tr>
                        <tr class="wlwl-custom-coupon">
                            <th>
                                <label for="wlwl_coupon_code_prefix"><?php esc_html_e( 'Coupon code prefix', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <input id="wlwl_coupon_code_prefix" type="text" name="wlwl_coupon_code_prefix"
                                       value="<?php echo esc_attr( $this->settings->get_params( 'coupon', 'coupon_code_prefix' ) ) ?>">
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="vi-ui bottom attached tab segment" data-tab="email_api">
                    <table class="form-table">
                        <tbody>

                        <tr valign="top">
                            <th scope="row">
                                <label for="mailchimp_enable"><?php esc_html_e( 'Enable Mailchimp', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox checked">
                                    <input type="checkbox" name="mailchimp_enable"
                                           id="mailchimp_enable" <?php checked( $this->settings->get_params( 'mailchimp', 'enable' ), 'on' ) ?>>
                                    <label for="mailchimp_enable"><?php esc_html_e( 'Enable', 'woo-lucky-wheel' ) ?></label>
                                </div>
                                <p class="description"><?php esc_html_e( 'Turn on to use MailChimp system', 'woo-lucky-wheel' ) ?></p>

                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="mailchimp_api"></label><?php esc_html_e( 'API key', 'woo-lucky-wheel' ) ?>
                            </th>
                            <td>
                                <input type="text" id="mailchimp_api" name="mailchimp_api"
                                       value="<?php echo esc_attr( $this->settings->get_params( 'mailchimp', 'api_key' ) ) ?>">

                                <p class="description"><?php esc_html_e( ' The API key for connecting with your MailChimp account. Get your API key ', 'woo-lucky-wheel' ) ?>
                                    <a href="https://admin.mailchimp.com/account/api"><?php esc_html_e( 'here', 'woo-lucky-wheel' ) ?></a>.
                                </p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="mailchimp_lists"><?php esc_html_e( 'Mailchimp lists', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
								<?php
								$mailchimp      = new VI_WOO_LUCKY_WHEEL_Admin_Mailchimp();
								$mail_lists     = $mailchimp->get_lists();
								$mailchimp_list = $mail_lists->lists ?? array();
								?>
                                <select class="select-who vi-ui fluid dropdown" name="mailchimp_lists"
                                        id="mailchimp_lists">
									<?php
									if ( is_array( $mailchimp_list ) && ! empty( $mailchimp_list ) ) {
										foreach ( $mailchimp_list as $mail_list ) {
											?>
                                            <option value='<?php echo esc_attr( $mail_list->id ); ?>' <?php selected( $this->settings->get_params( 'mailchimp', 'lists' ), $mail_list->id ); ?> ><?php echo esc_html( $mail_list->name ); ?></option>
											<?php
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
                                <label for="wlwl_enable_active_campaign"><?php esc_html_e( 'Active Campaign', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="wlwl_active_campaign_key"></label><?php esc_html_e( 'Active Campaign API Key', 'woo-lucky-wheel' ) ?>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="wlwl_active_campaign_url"></label><?php esc_html_e( 'Active Campaign API URL', 'woo-lucky-wheel' ) ?>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="wlwl_active_campaign_list"><?php esc_html_e( 'Active Campaign list', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>

                        <tr>
                            <td></td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="wlwl_sendgrid_enable"><?php esc_html_e( 'SendGrid', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="wlwl_sendgrid_key"></label><?php esc_html_e( 'SendGrid API Key', 'woo-lucky-wheel' ) ?>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row">
                                <label for="sendgrid_lists"><?php esc_html_e( 'Sendgrid lists', 'woo-lucky-wheel' ) ?></label>
                            </th>
                            <td>
                                <a class="vi-ui button" target="_blank"
                                   href="https://1.envato.market/qXBNY"><?php esc_html_e( 'Upgrade This Feature', 'woo-lucky-wheel' ) ?></a>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <p>
                    <input id="submit" type="submit" class="vi-ui primary button" name="submit"
                           value="<?php esc_html_e( 'Save', 'woo-lucky-wheel' ); ?>">
                </p>

            </form>
        </div>
        <div class="woocommerce-lucky-wheel-preview preview-html-hidden">
            <div class="woocommerce-lucky-wheel-preview-overlay"></div>
            <div class="woocommerce-lucky-wheel-preview-html">
                <canvas id="wlwl_canvas"></canvas>
                <canvas id="wlwl_canvas1"></canvas>
                <canvas id="wlwl_canvas2"></canvas>
            </div>
        </div>
		<?php
		do_action( 'villatheme_support_woo-lucky-wheel' );
	}

	public function save_settings() {

		global $woo_lucky_wheel_settings;
		if ( empty( $_POST['wlwl_nonce_field'] ) || ! wp_verify_nonce( $_POST['wlwl_nonce_field'], 'wlwl_settings_page_save' ) ) {
			return;
		}

		$args = array(
			'general'    => array(
				'enable'     => isset( $_POST['wlwl_enable'] ) ? sanitize_text_field( $_POST['wlwl_enable'] ) : 'off',
				'mobile'     => isset( $_POST['wlwl_enable_mobile'] ) ? sanitize_text_field( $_POST['wlwl_enable_mobile'] ) : 'off',
				'spin_num'   => isset( $_POST['wlwl_spin_num'] ) ? sanitize_text_field( $_POST['wlwl_spin_num'] ) : 0,
				'delay'      => isset( $_POST['wlwl_delay'] ) ? sanitize_text_field( $_POST['wlwl_delay'] ) : 0,
				'delay_unit' => isset( $_POST['wlwl_delay_unit'] ) ? sanitize_text_field( $_POST['wlwl_delay_unit'] ) : 's',
			),
			'notify'     => array(
				'position'           => isset( $_POST['notify_position'] ) ? sanitize_text_field( $_POST['notify_position'] ) : '',
				'size'               => isset( $_POST['notify_size'] ) ? sanitize_text_field( $_POST['notify_size'] ) : 0,
				'color'              => isset( $_POST['notify_color'] ) ? sanitize_text_field( $_POST['notify_color'] ) : '',
				'intent'             => isset( $_POST['notify_intent'] ) ? sanitize_text_field( $_POST['notify_intent'] ) : '',
				'show_again'         => isset( $_POST['notify_show_again'] ) ? sanitize_text_field( $_POST['notify_show_again'] ) : 0,
				'hide_popup'         => isset( $_POST['notify_hide_popup'] ) ? sanitize_text_field( $_POST['notify_hide_popup'] ) : 'off',
				'show_wheel'         => isset( $_POST['show_wheel'] ) ? sanitize_text_field( $_POST['show_wheel'] ) : '',
				'show_again_unit'    => isset( $_POST['notify_show_again_unit'] ) ? sanitize_text_field( $_POST['notify_show_again_unit'] ) : 0,
				'show_only_front'    => isset( $_POST['notify_frontpage_only'] ) ? sanitize_text_field( $_POST['notify_frontpage_only'] ) : 'off',
				'show_only_blog'     => isset( $_POST['notify_blogpage_only'] ) ? sanitize_text_field( $_POST['notify_blogpage_only'] ) : 'off',
				'show_only_shop'     => isset( $_POST['notify_shop_only'] ) ? sanitize_text_field( $_POST['notify_shop_only'] ) : 'off',
				'conditional_tags'   => isset( $_POST['notify_conditional_tags'] ) ? stripslashes( sanitize_text_field( $_POST['notify_conditional_tags'] ) ) : '',
				'time_on_close'      => isset( $_POST['notify_time_on_close'] ) ? stripslashes( sanitize_text_field( $_POST['notify_time_on_close'] ) ) : '',
				'time_on_close_unit' => isset( $_POST['notify_time_on_close_unit'] ) ? stripslashes( sanitize_text_field( $_POST['notify_time_on_close_unit'] ) ) : '',
			),
			'wheel_wrap' => array(
				'description'          => isset( $_POST['wheel_wrap_description'] ) ? wp_kses_post( stripslashes( $_POST['wheel_wrap_description'] ) ) : '',
				'bg_image'             => isset( $_POST['wheel_wrap_bg_image'] ) ? sanitize_text_field( $_POST['wheel_wrap_bg_image'] ) : '',
				'bg_color'             => isset( $_POST['wheel_wrap_bg_color'] ) ? sanitize_text_field( $_POST['wheel_wrap_bg_color'] ) : '',
				'text_color'           => isset( $_POST['wheel_wrap_text_color'] ) ? sanitize_text_field( $_POST['wheel_wrap_text_color'] ) : '',
				'spin_button'          => isset( $_POST['wheel_wrap_spin_button'] ) ? sanitize_text_field( stripslashes( $_POST['wheel_wrap_spin_button'] ) ) : 'Try Your Lucky',
				'spin_button_color'    => isset( $_POST['wheel_wrap_spin_button_color'] ) ? sanitize_text_field( $_POST['wheel_wrap_spin_button_color'] ) : '',
				'spin_button_bg_color' => isset( $_POST['wheel_wrap_spin_button_bg_color'] ) ? sanitize_text_field( $_POST['wheel_wrap_spin_button_bg_color'] ) : '',
				'pointer_position'     => 'center',
				'pointer_color'        => isset( $_POST['pointer_color'] ) ? sanitize_text_field( $_POST['pointer_color'] ) : '',
				'wheel_center_image'   => '',
				'wheel_center_color'   => isset( $_POST['wheel_center_color'] ) ? sanitize_text_field( $_POST['wheel_center_color'] ) : '',
				'wheel_border_color'   => '#ffffff',
				'wheel_dot_color'      => '#000000',
				'close_option'         => isset( $_POST['wheel_wrap_close_option'] ) ? sanitize_text_field( $_POST['wheel_wrap_close_option'] ) : '',
				'font'                 => isset( $_POST['wlwl_google_font_select'] ) ? sanitize_text_field( $_POST['wlwl_google_font_select'] ) : '',
				'gdpr'                 => isset( $_POST['gdpr_policy'] ) ? sanitize_textarea_field( $_POST['gdpr_policy'] ) : "off",
				'gdpr_message'         => isset( $_POST['gdpr_message'] ) ? wp_kses_post( stripslashes( $_POST['gdpr_message'] ) ) : "",
				'custom_css'           => isset( $_POST['custom_css'] ) ? wp_kses_post( stripslashes( $_POST['custom_css'] ) ) : "",
			),
			'wheel'      => array(
				'label_coupon'     => isset( $_POST['wheel_label_coupon'] ) ? sanitize_text_field( $_POST['wheel_label_coupon'] ) : '{coupon_amount} OFF',
				'spinning_time'    => 8,
				'coupon_type'      => isset( $_POST['coupon_type'] ) ? stripslashes_deep( array_map( 'sanitize_text_field', $_POST['coupon_type'] ) ) : array(),
				'coupon_amount'    => isset( $_POST['coupon_amount'] ) ? array_map( 'sanitize_text_field', $_POST['coupon_amount'] ) : array(),
				'custom_value'     => isset( $_POST['custom_type_value'] ) ? array_map( 'wlwl_sanitize_text_field', $_POST['custom_type_value'] ) : array(),
				'custom_label'     => isset( $_POST['custom_type_label'] ) ? array_map( 'wlwl_sanitize_text_field', $_POST['custom_type_label'] ) : array(),
				'existing_coupon'  => isset( $_POST['wlwl_existing_coupon'] ) ? array_map( 'sanitize_text_field', $_POST['wlwl_existing_coupon'] ) : array(),
				'probability'      => isset( $_POST['probability'] ) ? array_map( 'sanitize_text_field', $_POST['probability'] ) : array(),
				'bg_color'         => isset( $_POST['bg_color'] ) ? array_map( 'sanitize_text_field', $_POST['bg_color'] ) : array(),
				'slice_text_color' => isset( $_POST['slice_text_color'] ) ? wp_kses_post( stripslashes( $_POST['slice_text_color'] ) ) : "",
				'show_full_wheel'  => isset( $_POST['show_full_wheel'] ) ? sanitize_text_field( $_POST['show_full_wheel'] ) : "",
			),

			'result' => array(
				'auto_close'   => isset( $_POST['result-auto_close'] ) ? sanitize_text_field( $_POST['result-auto_close'] ) : 0,
				'email'        => array(
					'subject' => isset( $_POST['subject'] ) ? stripslashes( sanitize_text_field( $_POST['subject'] ) ) : "",
					'heading' => isset( $_POST['heading'] ) ? stripslashes( sanitize_text_field( $_POST['heading'] ) ) : "",
					'content' => isset( $_POST['content'] ) ? wp_kses_post( $_POST['content'] ) : "",
				),
				'notification' => array(
					'win'  => isset( $_POST['result_win'] ) ? wp_kses_post( stripslashes( $_POST['result_win'] ) ) : "",
					'lost' => isset( $_POST['result_lost'] ) ? wp_kses_post( stripslashes( $_POST['result_lost'] ) ) : "",
				)
			),

			'coupon' => array(
				'allow_free_shipping'        => isset( $_POST['wlwl_free_shipping'] ) ? sanitize_text_field( $_POST['wlwl_free_shipping'] ) : 'no',
				'expiry_date'                => isset( $_POST['wlwl_expiry_date'] ) ? sanitize_text_field( $_POST['wlwl_expiry_date'] ) : '',
				'min_spend'                  => isset( $_POST['wlwl_min_spend'] ) ? wc_format_decimal( $_POST['wlwl_min_spend'] ) : "",
				'max_spend'                  => isset( $_POST['wlwl_max_spend'] ) ? wc_format_decimal( $_POST['wlwl_max_spend'] ) : "",
				'individual_use'             => isset( $_POST['wlwl_individual_use'] ) ? sanitize_text_field( $_POST['wlwl_individual_use'] ) : "no",
				'exclude_sale_items'         => isset( $_POST['wlwl_exclude_sale_items'] ) ? sanitize_text_field( $_POST['wlwl_exclude_sale_items'] ) : "no",
				'limit_per_coupon'           => isset( $_POST['wlwl_limit_per_coupon'] ) ? absint( $_POST['wlwl_limit_per_coupon'] ) : "",
				'limit_to_x_items'           => isset( $_POST['wlwl_limit_to_x_items'] ) ? absint( $_POST['wlwl_limit_to_x_items'] ) : "",
				'limit_per_user'             => isset( $_POST['wlwl_limit_per_user'] ) ? absint( $_POST['wlwl_limit_per_user'] ) : "",
				'product_ids'                => isset( $_POST['wlwl_product_ids'] ) ? stripslashes_deep( $_POST['wlwl_product_ids'] ) : array(),
				'exclude_product_ids'        => isset( $_POST['wlwl_exclude_product_ids'] ) ? stripslashes_deep( $_POST['wlwl_exclude_product_ids'] ) : array(),
				'product_categories'         => isset( $_POST['wlwl_product_categories'] ) ? stripslashes_deep( $_POST['wlwl_product_categories'] ) : array(),
				'exclude_product_categories' => isset( $_POST['wlwl_exclude_product_categories'] ) ? stripslashes_deep( $_POST['wlwl_exclude_product_categories'] ) : array(),
				'coupon_code_prefix'         => isset( $_POST['wlwl_coupon_code_prefix'] ) ? sanitize_text_field( $_POST['wlwl_coupon_code_prefix'] ) : ""
			),

			'mailchimp' => array(
				'enable'  => isset( $_POST['mailchimp_enable'] ) ? sanitize_text_field( $_POST['mailchimp_enable'] ) : 'off',
				'api_key' => isset( $_POST['mailchimp_api'] ) ? sanitize_text_field( $_POST['mailchimp_api'] ) : '',
				'lists'   => isset( $_POST['mailchimp_lists'] ) ? sanitize_text_field( $_POST['mailchimp_lists'] ) : '',
			),

			'button_shop_title'               => esc_html__( 'Shop now', 'woo-lucky-wheel' ),
			'button_shop_url'                 => isset( $_POST['wlwl_button_shop_url'] ) ? sanitize_text_field( $_POST['wlwl_button_shop_url'] ) : '',
			'button_shop_color'               => '#ffffff',
			'button_shop_bg_color'            => '#000',
			'button_shop_size'                => 16,
			'ajax_endpoint'                   => isset( $_POST['ajax_endpoint'] ) ? sanitize_text_field( $_POST['ajax_endpoint'] ) : 'ajax',
			'custom_field_name_enable'        => isset( $_POST['custom_field_name_enable'] ) ? sanitize_text_field( $_POST['custom_field_name_enable'] ) : '',
			'custom_field_name_enable_mobile' => isset( $_POST['custom_field_name_enable_mobile'] ) ? sanitize_text_field( $_POST['custom_field_name_enable_mobile'] ) : '',
			'custom_field_name_required'      => isset( $_POST['custom_field_name_required'] ) ? sanitize_text_field( $_POST['custom_field_name_required'] ) : '',
		);

		if ( isset( $_POST['submit'] ) || isset( $_POST['wlwl_check_key'] ) ) {
			$validate = true;
			if ( count( $args['wheel']['probability'] ) > 6 || count( $args['wheel']['probability'] ) < 3 ) {
				add_action( 'admin_notices', function () {
					?>
                    <div class="error">
                        <p><?php esc_html_e( 'Free version only includes from 3 to 6 slices. Upgrade to Premium version to add up to 20 slices.', 'woocommerce-photo-reviews' ) ?></p>
                    </div>
					<?php
				} );
				$validate = false;
			}

			if ( array_sum( $args['wheel']['probability'] ) != 100 ) {
				add_action( 'admin_notices', function () {
					?>
                    <div class="error">
                        <p><?php esc_html_e( 'The total probability must be equal to 100%!', 'woocommerce-photo-reviews' ) ?></p>
                    </div>
					<?php
				} );
				$validate = false;
			}
			for ( $i = 0; $i < sizeof( $args['wheel']['coupon_type'] ); $i ++ ) {
				if ( $args['wheel']['coupon_type'][ $i ] == 'non' ) {
					if ( $args['wheel']['coupon_amount'][ $i ] > 0 ) {
						add_action( 'admin_notices', function () {
							?>
                            <div class="error">
                                <p><?php esc_html_e( 'The amount of Non-coupon must be left blank or zero!', 'woocommerce-photo-reviews' ) ?></p>
                            </div>
							<?php
						} );
						$validate = false;
						break;
					}
				} else if ( ! in_array( $args['wheel']['coupon_type'][ $i ], array(
					'custom',
					'existing_coupon'
				) ) ) {
					if ( $args['wheel']['coupon_amount'][ $i ] < 0 || $args['wheel']['coupon_amount'][ $i ] == '') {
						add_action( 'admin_notices', function () {
							?>
                            <div class="error">
                                <p><?php esc_html_e( 'The amount of Valued-coupon must be greater than or equal zero!', 'woocommerce-photo-reviews' ) ?></p>
                            </div>
							<?php
						} );
						$validate = false;
						break;
					}
				}
			}

			if ( is_array( $args['wheel']['custom_label'] ) && count( $args['wheel']['custom_label'] ) ) {
				foreach ( $args['wheel']['custom_label'] as $key => $val ) {
					if ( $args['wheel']['custom_label'][ $key ] === '' ) {
						add_action( 'admin_notices', function () {
							?>
                            <div class="error">
                                <p><?php esc_html_e( 'Label cannot be empty.', 'woocommerce-photo-reviews' ) ?></p>
                            </div>
							<?php
						} );
						$validate = false;
						break;
					}
					if ( $args['wheel']['coupon_type'][ $key ] == 'existing_coupon' && $args['wheel']['existing_coupon'][ $key ] == '' ) {
						add_action( 'admin_notices', function () {
							?>
                            <div class="error">
                                <p><?php esc_html_e( 'Please enter value for existing coupon.', 'woocommerce-photo-reviews' ) ?></p>
                            </div>
							<?php
						} );
						$validate = false;
						break;
					}
					if ( $args['wheel']['coupon_type'][ $key ] == 'custom' && $args['wheel']['custom_value'][ $key ] == '' ) {
						add_action( 'admin_notices', function () {
							?>
                            <div class="error">
                                <p><?php esc_html_e( 'Please enter value for custom type.', 'woocommerce-photo-reviews' ) ?></p>
                            </div>
							<?php
						} );
						$validate = false;
						break;
					}
				}
			}
			if ( ! $validate ) {
				return;
			}
			$args = wp_parse_args( $args, get_option( '_wlwl_settings', $woo_lucky_wheel_settings ) );

			update_option( '_wlwl_settings', $args );
			$woo_lucky_wheel_settings = $args;
			$this->settings           = VI_WOO_LUCKY_WHEEL_DATA::get_instance( true );
			add_action( 'admin_notices', function () {
				?>
                <div class="updated">
                    <p><?php esc_html_e( 'Your settings have been saved!', 'woo-lucky-wheel' ) ?></p>
                </div>
				<?php
			} );
		}
	}

	public function report_callback() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$total_spin = $email_subscribe = $coupon_given = 0;

		if ( isset( $_POST['submit'] ) ) {
			$start    = sanitize_text_field( $_POST['wlwl_export_start'] );
			$end      = sanitize_text_field( $_POST['wlwl_export_end'] );
			$filename = "lucky_wheel_email";
			if ( ! $start && ! $end ) {
				$args1    = array(
					'post_type'      => 'wlwl_email',
					'posts_per_page' => - 1,
					'post_status'    => 'publish',
				);
				$filename .= date( 'Y-m-d_h-i-s', time() ) . ".csv";
			} elseif ( ! $start ) {
				$args1    = array(
					'post_type'      => 'wlwl_email',
					'posts_per_page' => - 1,
					'post_status'    => 'publish',
					'date_query'     => array(
						array(
							'before'    => $end,
							'inclusive' => true

						)
					),
				);
				$filename .= 'before_' . $end . ".csv";
			} elseif ( ! $end ) {
				$args1    = array(
					'post_type'      => 'wlwl_email',
					'posts_per_page' => - 1,
					'post_status'    => 'publish',
					'date_query'     => array(
						array(
							'after'     => $start,
							'inclusive' => true
						)
					),

				);
				$filename .= 'from' . $start . 'to' . date( 'Y-m-d' ) . ".csv";
			} else {
				if ( strtotime( $start ) > strtotime( $end ) ) {
					wp_die( 'Incorrect input date' );
				}
				$args1    = array(
					'post_type'      => 'wlwl_email',
					'posts_per_page' => - 1,
					'post_status'    => 'publish',
					'date_query'     => array(
						array(
							'before'    => $end,
							'after'     => $start,
							'inclusive' => true

						)
					),
				);
				$filename .= 'from' . $start . 'to' . $end . ".csv";
			}
			$the_query        = new WP_Query( $args1 );
			$csv_source_array = array();
			$names            = array();
			if ( $the_query->have_posts() ) {
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$csv_source_array[] = get_the_title();
					$names[]            = get_the_content();

				}
				wp_reset_postdata();
				$data_rows  = array();
				$header_row = array(
					'order',
					'email',
					'name',
				);
				$i          = 1;
				foreach ( $csv_source_array as $key => $result ) {
					$row         = array( $i, $result, $names[ $key ] );
					$data_rows[] = $row;
					$i ++;
				}
				ob_end_clean();
				$fh = @fopen( 'php://output', 'w' );
				fprintf( $fh, chr( 0xEF ) . chr( 0xBB ) . chr( 0xBF ) );
				header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
				header( 'Content-Description: File Transfer' );
				header( 'Content-type: text/csv' );
				header( 'Content-Disposition: attachment; filename=' . $filename );
				header( 'Expires: 0' );
				header( 'Pragma: public' );
				fputcsv( $fh, $header_row );
				foreach ( $data_rows as $data_row ) {
					fputcsv( $fh, $data_row );
				}
				$csvFile = stream_get_contents( $fh );
				fclose( $fh );
				die;
			}
		} else {
			$args      = array(
				'post_type'      => 'wlwl_email',
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
			);
			$the_query = new WP_Query( $args );
			if ( $the_query->have_posts() ) {
				$email_subscribe = $the_query->post_count;
				while ( $the_query->have_posts() ) {
					$the_query->the_post();
					$id = get_the_ID();
					if ( get_post_meta( $id, 'wlwl_spin_times', true ) ) {
						$total_spin += get_post_meta( $id, 'wlwl_spin_times', true )['spin_num'];
					}
					if ( get_post_meta( $id, 'wlwl_email_coupons', true ) ) {
						$coupon       = get_post_meta( $id, 'wlwl_email_coupons', true );
						$coupon_given += sizeof( $coupon );
					}
				}
				wp_reset_postdata();
			}
		}
		?>
        <div class="wrap">
            <form action="" method="post">
                <h2><?php esc_html_e( 'Lucky Wheel Report', 'woo-lucky-wheel' ) ?></h2>

                <table cellspacing="0" id="status" class="widefat">
                    <tbody>
                    <tr>
                        <th><?php esc_html_e( 'Total Spins', 'woo-lucky-wheel' ) ?></th>
                        <th><?php esc_html_e( 'Emails Subcribed', 'woo-lucky-wheel' ) ?></th>
                        <th><?php esc_html_e( 'Coupon Given', 'woo-lucky-wheel' ) ?></th>
                    </tr>
                    <tr>
                        <td><?php echo esc_html( $total_spin ); ?></td>
                        <td><?php echo esc_html( $email_subscribe ); ?></td>
                        <td><?php echo esc_html( $coupon_given ); ?></td>
                    </tr>
                    </tbody>

                </table>
                <label for="wlwl_export_start"><?php esc_html_e( 'From', 'woo-lucky-wheel' ); ?></label><input
                        type="date" name="wlwl_export_start" id="wlwl_export_start" class="wlwl_export_date">
                <label for="wlwl_export_end"><?php esc_html_e( 'To', 'woo-lucky-wheel' ); ?></label><input
                        type="date" name="wlwl_export_end" id="wlwl_export_end" class="wlwl_export_date">

                <input id="submit"
                       type="submit"
                       class="button-primary"
                       name="submit"
                       value="<?php esc_html_e( 'Export Emails', 'woo-lucky-wheel' ); ?>"/>
            </form>
        </div>
		<?php
	}

	function system_status() {
		?>
        <div class="wrap">
            <h2><?php esc_html_e( 'System Status', 'woo-lucky-wheel' ) ?></h2>
            <table cellspacing="0" id="status" class="widefat">
                <tbody>
                <tr>
                    <td data-export-label="file_get_contents"><?php esc_html_e( 'file_get_contents', 'woo-lucky-wheel' ) ?></td>
                    <td>
						<?php
						if ( function_exists( 'file_get_contents' ) ) {
							echo '<span class="wlwl-status-ok">&#10004;</span> ';
						} else {
							echo '<span class="wlwl-status-error">&#10005; </span>';
						}
						?>
                    </td>
                </tr>
                <tr>
                    <td data-export-label="<?php esc_html_e( 'Allow URL Open', 'woo-lucky-wheel' ) ?>"><?php esc_html_e( 'Allow URL Open', 'woo-lucky-wheel' ) ?></td>
                    <td>
						<?php
						if ( ini_get( 'allow_url_fopen' ) == 'On' ) {
							echo '<span class="wlwl-status-ok">&#10004;</span> ';
						} else {
							echo '<span class="wlwl-status-error">&#10005;</span>';
						}
						?>
                </tr>
                </tbody>
            </table>
        </div>
		<?php
	}

	public static function auto_color() {
		$palette     = '{
          "red": {
            "100": "#ffcdd2",
            "900": "#b71c1c",
            "300": "#e57373",
            "600": "#e53935"
          },
          "purple": {
            "100": "#e1bee7",
            "900": "#4a148c",
            "300": "#ba68c8",
            "600": "#8e24aa"
          },
          "deeppurple": {
            "100": "#d1c4e9",
            "900": "#311b92",
             "300": "#9575cd",
            "600": "#5e35b1"
          },
          "indigo": {
            "100": "#c5cae9",
            "900": "#1a237e",
            "300": "#7986cb",
            "600": "#3949ab"
          },
          "blue": {
            "100": "#bbdefb",
             "300": "#64b5f6",
            "600": "#1e88e5",
            "900": "#0d47a1"
          },
          "teal": {
            "100": "#b2dfdb",
            "900": "#004d40",
            "300": "#4db6ac",
            "600": "#00897b"
          },
          "green": {
            "100": "#c8e6c9",
            "900": "#1b5e20",
            "300": "#81c784",
            "600": "#43a047"
          },
          "lime": {
            "100": "#f0f4c3",
            "900": "#827717",
             "300": "#dce775",
            "600": "#c0ca33"
          },
          "yellow": {
            "100": "#fff9c4",
            "900": "#f57f17",
            "300": "#fff176",
            "600": "#fdd835"
          },
          "orange": {
            "100": "#ffe0b2",
            "900": "#e65100",
            "300": "#ffb74d",
            "600": "#fb8c00"
          },
          "brown": {
            "100": "#d7ccc8",
            "900": "#3e2723",
             "300": "#a1887f",
            "600": "#6d4c41"
          },
          "bluegrey": {
            "100": "#cfd8dc",
            "900": "#263238",
            "300": "#90a4ae",
            "600": "#546e7a"
          }
        }';
		$palette     = json_decode( $palette );
		$color_array = array();
		foreach ( $palette as $colors ) {
			$color_row = array();
			foreach ( $colors as $color ) {
				$color_row[] = $color;
			}
			$color_array[] = $color_row;
		}
		$color_array[] = array(
			'#e6194b',
			'#3cb44b',
			'#ffe119',
			'#0082c8',
			'#f58231',
			'#911eb4',
			'#46f0f0',
			'#f032e6',
			'#d2f53c',
			'#fabebe',
			'#008080',
			'#e6beff',
			'#aa6e28',
			'#fffac8',
			'#800000',
			'#aaffc3',
			'#808000',
			'#ffd8b1',
			'#000080',
			'#808080',
			'#FFFFFF',
			'#000000'
		);
		?>
        <div class="color_palette" style="display: none;">
			<?php
			foreach ( $color_array as $colors ) {
				?>
                <div>
					<?php
					$i = 0;
					foreach ( $colors as $color ) {
						?>
                        <div class="wlwl_color_palette" data-color_code="<?php echo esc_attr( $color ); ?>"
                             style="width: 20px;height: 20px;float:left;border:1px solid #ffffff;background-color: <?php echo esc_attr( $color ); ?>;
						     <?php
						     if ( $i == ( sizeof( $colors ) - 1 ) ) {
							     echo esc_attr( 'display:block;' );
						     } else {
							     echo esc_attr( 'display:none;' );
						     }
						     ?>
                                     "></div>
						<?php
						$i ++;
					}
					?>
                </div>
				<?php
			}
			?>
        </div>

        <div class="auto_color_ok_cancel">
            <div class="vi-ui buttons">
                <span class="auto_color_ok positive vi-ui button"><?php esc_html_e( 'OK', 'woo-lucky-wheel' ); ?></span>
                <div class="or"></div>
                <span class="auto_color_cancel vi-ui button"><?php esc_html_e( 'Cancel', 'woo-lucky-wheel' ); ?></span>
            </div>
        </div>
		<?php
	}


}
