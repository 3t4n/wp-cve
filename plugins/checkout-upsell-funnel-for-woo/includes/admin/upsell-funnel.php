<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class VICUFFW_CHECKOUT_UPSELL_FUNNEL_Admin_Upsell_Funnel {
	protected $settings, $error;
	
	public function __construct() {
		$this->settings         = new VICUFFW_CHECKOUT_UPSELL_FUNNEL_Data();
		add_action( 'admin_menu', array( $this, 'admin_menu' ), 10 );
		add_action( 'admin_init', array( $this, 'save_settings' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), PHP_INT_MAX );
	}
	
	public function admin_menu() {
		add_menu_page(
			esc_html__( 'Checkout Funnel', 'checkout-upsell-funnel-for-woo' ),
			esc_html__( 'Checkout Funnel', 'checkout-upsell-funnel-for-woo' ),
			'manage_options',
			'checkout-upsell-funnel-for-woo',
			array( $this, 'settings_callback' ),
			'dashicons-filter',
			2 );
		add_submenu_page(
			'checkout-upsell-funnel-for-woo',
			esc_html__( 'Upsell Funnel', 'checkout-upsell-funnel-for-woo' ),
			esc_html__( 'Upsell Funnel', 'checkout-upsell-funnel-for-woo' ),
			'manage_options',
			'checkout-upsell-funnel-for-woo',
			array( $this, 'settings_callback' )
		);
	}
	
	public function save_settings() {
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		if ( $page !== 'checkout-upsell-funnel-for-woo' ) {
			return;
		}
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		if ( ! isset( $_POST['_viwcuf_settings_us'] ) || ! wp_verify_nonce( wc_clean($_POST['_viwcuf_settings_us']), '_viwcuf_settings_us_action' ) ) {
			return;
		}
		global $viwcuf_params;
		if ( isset( $_POST['vi-wcuf-save'] ) || isset( $_POST['vi-wcuf-check_key'] ) ) {
			$map_args_1 = array(
				'us_enable',
				'us_mobile_enable',
				'us_pd_redirect',
				'us_vicaio_enable',
				'us_desktop_style',
				'us_mobile_style',
				'us_desktop_position',
				'us_mobile_position',
				'us_redirect_page_endpoint',
				'us_border_color',
				'us_border_style',
				'us_border_width',
				'us_border_radius',
				'us_header_bg_color',
				'us_header_padding',
				'us_container_bg_color',
				'us_container_padding',
				'us_footer_bg_color',
				'us_footer_padding',
				'us_title',
				'us_title_color',
				'us_title_font_size',
				'us_bt_continue_title',
				'us_bt_continue_bg_color',
				'us_bt_continue_color',
				'us_bt_continue_border_color',
				'us_bt_continue_border_width',
				'us_bt_continue_border_radius',
				'us_bt_continue_font_size',
				'us_skip_icon',
				'us_skip_icon_color',
				'us_skip_icon_font_size',
				'us_bt_alltc_title',
				'us_bt_alltc_bg_color',
				'us_bt_alltc_color',
				'us_bt_alltc_border_color',
				'us_bt_alltc_border_width',
				'us_bt_alltc_border_radius',
				'us_bt_alltc_font_size',
				'us_alltc_icon',
				'us_alltc_icon_color',
				'us_alltc_icon_font_size',
				'us_time_checkout',
				'us_time',
				'us_time_reset',
				'us_countdown_message',
				'us_countdown_color',
				'us_countdown_font_size',
				'us_progress_bar_bt_pause',
				'us_progress_bar_border_width',
				'us_progress_bar_diameter',
				'us_progress_bar_bg_color',
				'us_progress_bar_border_color1',
				'us_progress_bar_border_color2',
				'us_bt_pause_title',
				'us_bt_pause_bg_color',
				'us_bt_pause_color',
				'us_bt_pause_border_color',
				'us_bt_pause_border_width',
				'us_bt_pause_border_radius',
				'us_bt_pause_font_size',
				'us_pause_icon',
				'us_pause_icon_color',
				'us_pause_icon_font_size',
				'us_desktop_display_type',
				'us_mobile_display_type',
				'us_desktop_item_per_row',
				'us_mobile_item_per_row',
				'us_desktop_scroll_limit_rows',
				'us_mobile_scroll_limit_rows',
				'us_pd_template',
				'us_pd_bg_color',
				'us_pd_box_shadow_color',
				'us_pd_border_color',
				'us_pd_border_radius',
				'us_pd_img_padding',
				'us_pd_img_border_color',
				'us_pd_img_border_width',
				'us_pd_img_border_radius',
				'us_pd_details_padding',
				'us_pd_details_font_size',
				'us_pd_details_color',
				'us_pd_details_text_align',
				'us_pd_qty_bg_color',
				'us_pd_qty_color',
				'us_pd_qty_border_color',
				'us_pd_qty_border_radius',
				'us_pd_atc_title',
				'us_pd_atc_bg_color',
				'us_pd_atc_color',
				'us_pd_atc_border_color',
				'us_pd_atc_border_width',
				'us_pd_atc_border_radius',
				'us_pd_atc_font_size',
				'us_pd_atc_icon',
				'us_pd_atc_icon_color',
				'us_pd_atc_icon_font_size',
			);
			$map_args_2 = array(
				'us_content',
				'us_header_content',
				'us_container_content',
				'us_footer_content',
			);
			$map_args_3 = array(
				'us_discount_amount',
				'us_discount_type',
				'us_days_show',
				'us_product_type',
				'us_product_limit',
				'us_product_order_by',
				'us_product_order',
				'us_product_rule_type',
				'us_product_show_variation',
				'us_product_visibility',
				'us_product_include',
				'us_product_exclude',
				'us_cats_include',
				'us_cats_exclude',
				'us_product_price',
				'us_cart_rule_type',
				'us_cart_item_include',
				'us_cart_item_exclude',
				'us_cart_cats_include',
				'us_cart_cats_exclude',
				'us_cart_coupon_include',
				'us_cart_coupon_exclude',
				'us_billing_countries_include',
				'us_billing_countries_exclude',
				'us_shipping_countries_include',
				'us_shipping_countries_exclude',
				'us_user_rule_type',
				'us_user_logged',
				'us_user_include',
				'us_user_exclude',
				'us_user_role_include',
				'us_user_role_exclude',
			);
			$old_args=  get_option( 'viwcuf_woo_checkout_upsell_funnel', $viwcuf_params ) ;
			$args       = array();
			foreach ( $map_args_1 as $item ) {
				$args[ $item ] = isset( $_POST[ $item ] ) ? sanitize_text_field( wp_unslash( $_POST[ $item ] ) ) : '';
			}
			foreach ( $map_args_2 as $item ) {
				$args[ $item ] = isset( $_POST[ $item ] ) ? wp_kses_post( wp_unslash( $_POST[ $item ] ) ) : '';
			}
			foreach ( $map_args_3 as $item ) {
				$args[ $item ] = isset( $_POST[ $item ] ) ? viwcuf_sanitize_fields( $_POST[ $item ] ) : array();
			}
			if ( ! empty( $args['us_product_type'] ) ) {
				$key                          = array_search( '4', $args['us_product_type'] );
				$args['recent_viewed_cookie'] = $key === false ? '' : 1;
			}
			$args          = wp_parse_args( $args, $old_args );
			$viwcuf_params = $args;
			if ( ( $args['us_desktop_style'] == '3' ) || ( ! empty( $args['us_mobile_enable'] ) && $args['us_mobile_style'] == '3' ) ) {
				if ( ! empty( $args['us_redirect_page_endpoint'] ) ) {
					update_option( 'woocommerce_queue_flush_rewrite_rules', 'yes' );
				} else {
					$this->error = esc_html__( 'Suggest page cannot be empty!', 'checkout-upsell-funnel-for-woo' );
					
					return;
				}
			}
			$update_prefix = false;
			if (!get_option( 'viwcuf_upsell_funnel_prefix','') || $args['us_desktop_style'] != $old_args['us_desktop_style'] || ( ! empty( $args['us_mobile_enable'] ) && $args['us_mobile_style'] != $old_args['us_mobile_style'] )){
			    $update_prefix = true;
            }
			if (!$update_prefix){
				foreach ( $map_args_3 as $item ) {
					if ($args[$item] !== $old_args[$item]){
					    $update_prefix = true;
					    break;
                    }
				}
            }
			if ($update_prefix){
				update_option( 'viwcuf_upsell_funnel_prefix', substr( md5( date( "YmdHis" ) ), 0, 10 ) );
            }
			update_option( 'viwcuf_woo_checkout_upsell_funnel', $args );
		}
	}
	
	public function settings_callback() {
		$this->settings = new  VICUFFW_CHECKOUT_UPSELL_FUNNEL_Data();
		?>
        <div class="wrap">
            <h2 class=""><?php esc_html_e( 'Checkout Upsell Funnel For WooCommerce', 'checkout-upsell-funnel-for-woo' ) ?></h2>
            <div id="vi-wcuf-message-error" class="error <?php echo esc_attr( $this->error ? '' : 'hidden' ); ?>">
                <p><?php echo esc_html( $this->error ); ?></p>
            </div>
            <div class="vi-ui raised">
                <form class="vi-ui form" method="post">
					<?php wp_nonce_field( '_viwcuf_settings_us_action', '_viwcuf_settings_us' ); ?>
                    <div class="vi-ui vi-ui-main tabular attached menu">
                        <a class="item active" data-tab="general"><?php esc_html_e( 'General Settings', 'checkout-upsell-funnel-for-woo' ); ?></a>
                        <a class="item" data-tab="rule"><?php esc_html_e( 'Rules & Products', 'checkout-upsell-funnel-for-woo' ); ?></a>
                        <a class="item" data-tab="design"><?php esc_html_e( 'Design', 'checkout-upsell-funnel-for-woo' ); ?></a>
                    </div>
                    <div class="vi-ui bottom attached tab segment active" data-tab="general">
						<?php
						$us_enable                 = $this->settings->get_params( 'us_enable' );
						$us_mobile_enable          = $this->settings->get_params( 'us_mobile_enable' );
						$us_pd_redirect            = $this->settings->get_params( 'us_pd_redirect' );
						$us_desktop_style          = $this->settings->get_params( 'us_desktop_style' );
						$us_vicaio_enable          = $this->settings->get_params( 'us_vicaio_enable' );
						$us_mobile_style           = $this->settings->get_params( 'us_mobile_style' );
						$us_desktop_position       = $this->settings->get_params( 'us_desktop_position' );
						$us_mobile_position        = $this->settings->get_params( 'us_mobile_position' );
						$us_redirect_page_endpoint = $this->settings->get_params( 'us_redirect_page_endpoint' ) ?: 'upsell-funnel';
						$checkout_pos              = array(
							'1' => esc_html__( 'Before checkout form', 'checkout-upsell-funnel-for-woo' ),
							'2' => esc_html__( 'Before billing details', 'checkout-upsell-funnel-for-woo' ),
							'3' => esc_html__( 'After billing details', 'checkout-upsell-funnel-for-woo' ),
							'4' => esc_html__( 'Before order details', 'checkout-upsell-funnel-for-woo' ),
							'5' => esc_html__( 'Before payment gateways', 'checkout-upsell-funnel-for-woo' ),
							'6' => esc_html__( 'After payment gateways', 'checkout-upsell-funnel-for-woo' ),
							'7' => esc_html__( 'After checkout form', 'checkout-upsell-funnel-for-woo' ),
						);
						?>
                        <table class="form-table">
                            <tr>
                                <th>
                                    <label for="vi-wcuf-us_enable-checkbox"><?php esc_html_e( 'Enable', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="hidden" id="vi-wcuf-us_enable" name="us_enable" value="<?php echo esc_attr( $us_enable ); ?>">
                                        <input type="checkbox" id="vi-wcuf-us_enable-checkbox" <?php checked( $us_enable, '1' ) ?>><label></label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label><?php esc_html_e( 'Apply coupon', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button" href="https://1.envato.market/oeemke"
                                       target="_blank"><?php esc_html_e( 'Unlock This Feature', 'checkout-upsell-funnel-for-woo' ); ?> </a>
                                    <p class="description">
										<?php esc_html_e( 'Apply coupon to recommended products', 'checkout-upsell-funnel-for-woo' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcuf-us_pd_redirect-checkbox"><?php esc_html_e( 'Redirect to single product page', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="hidden" id="vi-wcuf-us_pd_redirect" name="us_pd_redirect" value="<?php echo esc_attr( $us_pd_redirect ); ?>">
                                        <input type="checkbox" id="vi-wcuf-us_pd_redirect-checkbox" <?php checked( $us_pd_redirect, '1' ) ?>><label></label>
                                    </div>
                                    <p class="description">
										<?php esc_html_e( 'Redirect to single product page when click to product image or product title', 'checkout-upsell-funnel-for-woo' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcuf-us_pd_hide_after_atc-checkbox"><?php esc_html_e( 'Hide added products', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button" href="https://1.envato.market/oeemke"
                                       target="_blank"><?php esc_html_e( 'Unlock This Feature', 'checkout-upsell-funnel-for-woo' ); ?> </a>
                                    <p class="description">
										<?php esc_html_e( 'Allow to hide the product which added to cart', 'checkout-upsell-funnel-for-woo' ); ?>
                                    </p>
                                </td>
                            </tr>
	                        <?php
	                        if ( class_exists( 'VIWCAIO_CART_ALL_IN_ONE' ) ) {
		                        ?>
                                <tr>
                                    <th>
                                        <label for="vi-wcuf-us_vicaio_enable-checkbox"><?php esc_html_e( 'Enable on Sidebar cart','checkout-upsell-funnel-for-woo' ); ?></label>
                                    </th>
                                    <td>
                                        <div class="vi-ui toggle checkbox">
                                            <input type="hidden" id="vi-wcuf-us_vicaio_enable" name="us_vicaio_enable" value="<?php echo esc_attr( $us_vicaio_enable ); ?>">
                                            <input type="checkbox" id="vi-wcuf-us_vicaio_enable-checkbox" <?php checked( $us_vicaio_enable, '1' ) ?>><label></label>
                                        </div>
                                        <p class="description">
					                        <?php esc_html_e( 'Display Upsell Funnel for Checkout form on the Sidebar Cart', 'checkout-upsell-funnel-for-woo' ); ?>
                                        </p>
                                    </td>
                                </tr>
		                        <?php
	                        }
	                        ?>
                            <tr>
                                <th>
                                    <label for="vi-wcuf-us_desktop_style"><?php esc_html_e( 'Style to display recommended products', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </th>
                                <td>
									<?php
									$us_style = array(
										'1' => esc_html__( 'On Checkout page', 'checkout-upsell-funnel-for-woo' ),
										'2' => esc_html__( 'On popup after clicking \'Place Order\' button', 'checkout-upsell-funnel-for-woo' ),
										'3' => esc_html__( 'Redirect to another page after clicking \'Place Order\' button', 'checkout-upsell-funnel-for-woo' ),
									);
									if ( class_exists( 'WC_Gateway_Twocheckout_Inline' ) ) {
										unset( $us_style['2'] );
										$us_desktop_style = $us_desktop_style == 2 ? 1 : $us_desktop_style;
										$us_mobile_style  = $us_mobile_style == 2 ? 1 : $us_mobile_style;
									}
									?>
                                    <select name="us_desktop_style" class="vi-ui fluid dropdown vi-wcuf-us_desktop_style" id="vi-wcuf-us_desktop_style">
										<?php
										foreach ( $us_style as $k => $v ) {
											echo sprintf( '<option value="%s" %s>%s</option>', esc_attr( $k ), selected( $us_desktop_style, $k ), $v );
										}
										?>
                                    </select>
                                    <p class="description">
										<?php
										esc_html_e( 'Set the style to display recommended products', 'checkout-upsell-funnel-for-woo' );
										?>
                                    </p>
                                </td>
                            </tr>
                            <tr class="vi-wcuf-us_desktop_position-wrap <?php echo esc_attr( $us_desktop_style == 1 ? '' : 'vi-wcuf-hidden' ); ?>">
                                <th>
                                    <label for="vi-wcuf-us_desktop_position"><?php esc_html_e( 'Products position on checkout page', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </th>
                                <td>
                                    <select name="us_desktop_position" id="vi-wcuf-us_desktop_position" class="vi-ui fluid dropdown vi-wcuf-us_desktop_position">
										<?php
										foreach ( $checkout_pos as $k => $v ) {
											echo sprintf( '<option value="%s" %s>%s</option>', $k, selected( $us_desktop_position, $k ), $v );
										}
										?>
                                    </select>
                                    <p class="description">
										<?php
										esc_html_e( 'Choose the position for recommended products on checkout page', 'checkout-upsell-funnel-for-woo' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr class="vi-wcuf-us_redirect_page_endpoint-wrap <?php echo esc_attr($us_desktop_style == 3 || ( $us_mobile_enable && $us_mobile_style == 3 ) ? '' :  'vi-wcuf-hidden' ); ?>">
                                <th>
                                    <label for="vi-wcuf-us_redirect_page_endpoint"><?php esc_html_e( 'URL of endpoint page', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </th>
                                <td>
                                    <input type="text" id="vi-wcuf-us_redirect_page_endpoint" name="us_redirect_page_endpoint"
                                           class="vi-wcuf-us_redirect_page_endpoint" value="<?php echo esc_attr( $us_redirect_page_endpoint ); ?>">
                                    <p class="description">
										<?php esc_html_e( 'Endpoints are appended to your checkout page to display recommended products', 'checkout-upsell-funnel-for-woo' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="vi-wcuf-us_mobile_enable-checkbox"><?php esc_html_e( 'Mobile enable', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </th>
                                <td>
                                    <div class="vi-ui toggle checkbox">
                                        <input type="hidden" id="vi-wcuf-us_mobile_enable" name="us_mobile_enable" value="<?php echo esc_attr( $us_mobile_enable ); ?>">
                                        <input type="checkbox" id="vi-wcuf-us_mobile_enable-checkbox" class="vi-wcuf-us_mobile_enable-checkbox" <?php checked( $us_mobile_enable, '1' ) ?>><label></label>
                                    </div>
                                    <p class="description">
										<?php
										esc_html_e( 'Enable to display recommended products on mobile', 'checkout-upsell-funnel-for-woo' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr class="vi-wcuf-us_mobile_enable-enable <?php echo esc_attr($us_mobile_enable ? '' :  'vi-wcuf-hidden' ); ?>">
                                <th>
                                    <label for="vi-wcuf-us_mobile_style"><?php esc_html_e( 'Style to display recommended products on mobile', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </th>
                                <td>
                                    <select name="us_mobile_style" class="vi-ui fluid dropdown vi-wcuf-us_mobile_style" id="vi-wcuf-us_mobile_style">
										<?php
										foreach ( $us_style as $k => $v ) {
											echo sprintf( '<option value="%s" %s>%s</option>', esc_attr( $k ), selected( $us_mobile_style, $k ), $v );
										}
										?>
                                    </select>
                                    <p class="description">
										<?php esc_html_e( 'Set the style to display recommended products on mobile', 'checkout-upsell-funnel-for-woo' ); ?>
                                    </p>
                                </td>
                            </tr>
                            <tr class="vi-wcuf-us_mobile_enable-enable vi-wcuf-us_mobile_position-wrap <?php echo esc_attr( $us_mobile_enable && $us_mobile_style === '1' ? '' : 'vi-wcuf-hidden' ); ?>">
                                <th>
                                    <label for="vi-wcuf-us_mobile_position"><?php esc_html_e( 'Products position on checkout page on mobile', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                </th>
                                <td>
                                    <select name="us_mobile_position" id="vi-wcuf-us_mobile_position" class="vi-ui fluid dropdown vi-wcuf-us_mobile_position">
										<?php
										foreach ( $checkout_pos as $k => $v ) {
											echo sprintf( '<option value="%s" %s>%s</option>', $k, selected( $us_mobile_position, $k ), $v );
										}
										?>
                                    </select>
                                    <p class="description">
										<?php esc_html_e( 'Choose the position for recommended products on checkout page on mobile', 'checkout-upsell-funnel-for-woo' ); ?>
                                    </p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment vi-wcuf-tab-rule" data-tab="rule">
                        <div class="vi-wcuf-rules-wrap">
							<?php
							$woo_currency_symbol = get_woocommerce_currency_symbol();
							$woo_countries       = new WC_Countries();
							$woo_countries       = $woo_countries->__get( 'countries' );
							$woo_users_role      = wp_roles()->roles;
							$i                   = 0;
							$id                  = 'default';
							$us_discount_amount  = $this->settings->get_current_setting( 'us_discount_amount', $i ) ?: 0;
							$us_discount_type    = $this->settings->get_current_setting( 'us_discount_type', $i );
							$us_days_show        = $this->settings->get_current_setting( 'us_days_show', $id, array() );
							?>
                            <div class="vi-ui fluid styled accordion active vi-wcuf-accordion-rule-wrap  vi-wcuf-accordion-wrap vi-wcuf-accordion-<?php echo esc_attr( $id ); ?>"
                                 data-rule_id="<?php echo esc_attr( $id ); ?>">
                                <div class="title active">
                                    <i class="dropdown icon"></i>
									<?php esc_html_e( 'General settings', 'checkout-upsell-funnel-for-woo' ); ?>
                                </div>
                                <div class="content active">
                                    <div class="field vi-wcuf-accordion-general-wrap">
                                        <div class="equal width fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Days', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                <select name="us_days_show[<?php echo esc_attr( $id ) ?>][]" class="vi-ui fluid dropdown vi-wcuf-us_days_show" multiple>
                                                    <option value="0" <?php selected( in_array( '0', $us_days_show ), true ) ?>>
														<?php esc_html_e( 'Sunday', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option value="1" <?php selected( in_array( '1', $us_days_show ), true ) ?>>
														<?php esc_html_e( 'Monday', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option value="2" <?php selected( in_array( '2', $us_days_show ), true ) ?>>
														<?php esc_html_e( 'Tuesday', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option value="3" <?php selected( in_array( '3', $us_days_show ), true ) ?>>
														<?php esc_html_e( 'Wednesday', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option value="4" <?php selected( in_array( '4', $us_days_show ), true ) ?>>
														<?php esc_html_e( 'Thursday', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option value="5" <?php selected( in_array( '5', $us_days_show ), true ) ?>>
														<?php esc_html_e( 'Friday', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option value="6" <?php selected( in_array( '6', $us_days_show ), true ) ?>>
														<?php esc_html_e( 'Saturday', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="title">
                                    <i class="dropdown icon"></i>
									<?php esc_html_e( 'Recommended products', 'checkout-upsell-funnel-for-woo' ); ?>
                                </div>
                                <div class="content">
									<?php
									$us_product_type     = $this->settings->get_current_setting( 'us_product_type', $i );
									$us_product_limit    = $this->settings->get_current_setting( 'us_product_limit', $i, 4 );
									$us_product_order_by = $this->settings->get_current_setting( 'us_product_order_by', $i, 'date' );
									$us_product_order    = $this->settings->get_current_setting( 'us_product_order', $i, 'desc' );
									?>
                                    <div class="field">
                                        <div class="equal width fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Type', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                <select name="us_product_type[]" class="vi-ui fluid dropdown vi-wcuf-us_product_type">
                                                    <option value="0" <?php selected( $us_product_type, '0' ); ?>>
														<?php esc_html_e( 'Featured products', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option value="1" <?php selected( $us_product_type, '1' ); ?>>
														<?php esc_html_e( 'Best selling products', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option value="2" <?php selected( $us_product_type, '2' ); ?>>
														<?php esc_html_e( 'On sale', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option value="3" <?php selected( $us_product_type, '3' ); ?>>
														<?php esc_html_e( 'Recently published products', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option value="4" <?php selected( $us_product_type, '4' ); ?>>
														<?php esc_html_e( 'Recently viewed products', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option value="9" <?php selected( $us_product_type, '9' ); ?>>
														<?php esc_html_e( 'Products from Billing', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option value="13" <?php selected( $us_product_type, '13' ); ?>>
														<?php esc_html_e( 'Selected products', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option disabled>
														<?php esc_html_e( 'Related products of products in the cart', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option disabled>
														<?php esc_html_e( 'Up-sells of products in the cart', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option disabled>
														<?php esc_html_e( 'Cross-sells of products in the cart', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option disabled>
														<?php esc_html_e( 'Products in the same categories of products in the cart', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option disabled>
														<?php esc_html_e( 'Most purchased products from Billing', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option disabled>
														<?php esc_html_e( 'Most expensive products from Billing', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                    <option disabled>
														<?php esc_html_e( 'Recently purchased products from Billing', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </option>
                                                </select>
                                                <p class="description">
													<?php esc_html_e( 'The type of products will appear', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </p>
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Discount amount', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                <div class="vi-ui right action labeled input">
                                                    <input type="number" min="0" step="1" max="<?php echo esc_attr( in_array( $us_discount_type, [ '1', '3' ] ) ? '100' : '' ); ?>"
                                                           class="vi-wcuf-us_discount_amount<?php echo esc_attr( $us_discount_type ? '' : ' vi-wcuf-hidden' ); ?>"
                                                           name="us_discount_amount[]" value="<?php echo esc_attr( $us_discount_amount ); ?>">
                                                    <select name="us_discount_type[]" id="vi-wcuf-us_discount_type" class="vi-ui fluid dropdown vi-wcuf-us_discount_type">
                                                        <option value="0" <?php selected( $us_discount_type, '0' ) ?>>
															<?php esc_html_e( 'None', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                        <option value="1" <?php selected( $us_discount_type, '1' ) ?>>
															<?php esc_html_e( 'Percentage(%) regular price', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                        <option value="2" <?php selected( $us_discount_type, '2' ) ?>>
															<?php echo sprintf( esc_html__( 'Fixed(%s) regular price', 'checkout-upsell-funnel-for-woo' ), $woo_currency_symbol ); ?>
                                                        </option>
                                                        <option value="3" <?php selected( $us_discount_type, '3' ) ?>>
															<?php esc_html_e( 'Percentage(%) price', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                        <option value="4" <?php selected( $us_discount_type, '4' ) ?>>
															<?php echo sprintf( esc_html__( 'Fixed(%s) price', 'checkout-upsell-funnel-for-woo' ), $woo_currency_symbol ); ?>
                                                        </option>
                                                    </select>
                                                </div>
                                                <p class="description">
													<?php
													esc_html_e( 'The amount discounted on recommended products', 'checkout-upsell-funnel-for-woo' );
													?>
                                                </p>
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Products limit', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                <input type="number" min="0" max="1000" step="1" name="us_product_limit[]" value="<?php echo esc_attr( $us_product_limit ); ?>">
                                                <p class="description">
													<?php esc_html_e( 'The maximum number of recommended products', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </p>
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Products quantity limit', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                <a class="vi-ui button" href="https://1.envato.market/oeemke"
                                                   target="_blank"><?php esc_html_e( 'Unlock This Feature', 'checkout-upsell-funnel-for-woo' ); ?> </a>
                                                <p class="description">
													<?php esc_html_e( 'The maximum number of products quantity. Leave blank to not limit this.', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </p>
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Order products by', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                <div class="vi-ui left action input">
                                                    <select name="us_product_order_by[]" class="vi-ui fluid dropdown vi-wcuf-us_product_order_by left">
                                                        <option value="date" <?php selected( $us_product_order_by, 'date' ) ?>>
															<?php esc_html_e( 'Date', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                        <option value="id" <?php selected( $us_product_order_by, 'id' ) ?>>
															<?php esc_html_e( 'ID', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                        <option value="menu_order" <?php selected( $us_product_order_by, 'menu_order' ) ?>>
															<?php esc_html_e( 'Menu order', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                        <option value="popularity" <?php selected( $us_product_order_by, 'popularity' ) ?>>
															<?php esc_html_e( 'Popularity', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                        <option value="price" <?php selected( $us_product_order_by, 'price' ) ?>>
															<?php esc_html_e( 'Price', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                        <option value="rand" <?php selected( $us_product_order_by, 'rand' ) ?>>
															<?php esc_html_e( 'Random', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                        <option value="rating" <?php selected( $us_product_order_by, 'rating' ) ?>>
															<?php esc_html_e( 'Rating', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                        <option value="title" <?php selected( $us_product_order_by, 'title' ) ?>>
															<?php esc_html_e( 'Title', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                    </select>
                                                    <select name="us_product_order[]" class="vi-ui fluid dropdown vi-wcuf-us_product_order">
                                                        <option value="asc" <?php selected( $us_product_order, 'asc' ) ?>>
															<?php esc_html_e( 'ASC', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                        <option value="desc" <?php selected( $us_product_order, 'desc' ) ?>>
															<?php esc_html_e( 'DESC', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <h5 class="vi-ui header dividing vi-wcuf-pd_rule-title">
											<?php esc_html_e( 'Conditions of Product', 'checkout-upsell-funnel-for-woo' ); ?>
                                            <span class="vi-wcuf-discount-amount-notice vi-wcuf-discount-amount-notice-1<?php echo esc_attr( $us_discount_type == '1' ? '' : ' vi-wcuf-hidden' ); ?>">
                                                    <?php esc_html_e( ' - The price displayed on recommended products will be  the difference between the regular price and the discount (based on percentage)', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </span>
                                            <span class="vi-wcuf-discount-amount-notice vi-wcuf-discount-amount-notice-2<?php echo esc_attr( $us_discount_type == '2' ? '' : ' vi-wcuf-hidden' ); ?>">
                                                    <?php esc_html_e( ' - The price displayed on recommended products will be  the difference between the regular price and the discount (based on fixed amount)', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </span>
                                            <span class="vi-wcuf-discount-amount-notice vi-wcuf-discount-amount-notice-3<?php echo esc_attr( $us_discount_type == '3' ? '' : ' vi-wcuf-hidden' ); ?>">
                                                    <?php esc_html_e( ' - The price displayed on recommended products will be the difference between the sale price and the discount (based on percentage). If your product is not on sale, it will take the regular price.', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </span>
                                            <span class="vi-wcuf-discount-amount-notice vi-wcuf-discount-amount-notice-4<?php echo esc_attr( $us_discount_type == '4' ? '' : ' vi-wcuf-hidden' ); ?>">
                                                    <?php esc_html_e( ' - The price displayed on recommended products will be the difference between the sale price and the discount (based on fixed amount). If your product is not on sale, it will take the regular price.', 'checkout-upsell-funnel-for-woo' ); ?>
                                            </span>
                                        </h5>
                                        <div class="field vi-wcuf-rule-wrap-wrap vi-wcuf-pd_rule-wrap-wrap">
                                            <div class="field vi-wcuf-rule-wrap vi-wcuf-pd-rule-wrap vi-wcuf-pd_rule-condition-wrap">
												<?php
												$us_product_rule_type = $this->settings->get_current_setting( 'us_product_rule_type', $id, array() );
												if ( is_array( $us_product_rule_type ) && count( $us_product_rule_type ) ) {
													foreach ( $us_product_rule_type as $item_type ) {
														wc_get_template( 'admin-product-rule.php',
															array(
																'index'               => $id,
																'woo_currency_symbol' => $woo_currency_symbol,
																'prefix'              => 'us_',
																'type'                => $item_type,
																$item_type            => $this->settings->get_current_setting( 'us_' . $item_type, $id, $item_type === 'product_price' ? array() : '' ),
															),
															'',
															VICUFFW_CHECKOUT_UPSELL_FUNNEL_TEMPLATES );
													}
												}
												?>
                                            </div>
                                            <span class="vi-ui positive mini button vi-wcuf-add-condition-btn vi-wcuf-pd_rule-add-condition" data-rule_type="pd" data-rule_prefix="us_">
                                                <?php esc_html_e( 'Add Conditions(AND)', 'checkout-upsell-funnel-for-woo' ); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="title"
                                     data-tooltip="<?php esc_attr_e( 'Choose the conditions Carts which will display recommended products', 'checkout-upsell-funnel-for-woo' ); ?>">
                                    <i class="dropdown icon"></i>
									<?php esc_html_e( 'Cart Conditions', 'checkout-upsell-funnel-for-woo' ); ?>
                                </div>
                                <div class="content">
                                    <div class="field vi-wcuf-rule-wrap-wrap vi-wcuf-cart_rule-wrap-wrap">
                                        <div class="field vi-wcuf-rule-wrap  vi-wcuf-cart-rule-wrap vi-wcuf-cart_rule-wrap">
											<?php
											$us_cart_rule_type = $this->settings->get_current_setting( 'us_cart_rule_type', $id, array() );
											if ( is_array( $us_cart_rule_type ) && count( $us_cart_rule_type ) ) {
												foreach ( $us_cart_rule_type as $item_type ) {
													wc_get_template( 'admin-cart-rule.php',
														array(
															'index'               => $id,
															'woo_currency_symbol' => $woo_currency_symbol,
															'woo_countries'       => $woo_countries,
															'prefix'              => 'us_',
															'type'                => $item_type,
															$item_type            => $this->settings->get_current_setting( 'us_' . $item_type, $id, array() ),
														),
														'',
														VICUFFW_CHECKOUT_UPSELL_FUNNEL_TEMPLATES );
												}
											}
											?>
                                        </div>
                                        <span class="vi-ui positive mini button vi-wcuf-add-condition-btn vi-wcuf-cart_rule-add-condition"
                                              data-rule_type="cart" data-rule_prefix="us_">
                                            <?php esc_html_e( 'Add Conditions(AND)', 'checkout-upsell-funnel-for-woo' ); ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="title" data-tooltip="<?php esc_attr_e( 'Choose the customers who can see the recommended products', 'checkout-upsell-funnel-for-woo' ); ?>">
                                    <i class="dropdown icon"></i>
									<?php esc_html_e( 'Customer Conditions', 'checkout-upsell-funnel-for-woo' ); ?>
                                </div>
                                <div class="content">
                                    <div class="field vi-wcuf-rule-wrap-wrap vi-wcuf-user_rule-wrap-wrap">
                                        <div class="field vi-wcuf-rule-wrap vi-wcuf-user-rule-wrap vi-wcuf-user_rule-wrap">
											<?php
											$us_user_rule_type = $this->settings->get_current_setting( 'us_user_rule_type', $id, array() );
											if ( is_array( $us_user_rule_type ) && count( $us_user_rule_type ) ) {
												foreach ( $us_user_rule_type as $item_type ) {
													wc_get_template( 'admin-user-rule.php',
														array(
															'index'               => $id,
															'woo_currency_symbol' => $woo_currency_symbol,
															'woo_users_role'      => $woo_users_role,
															'prefix'              => 'us_',
															'type'                => $item_type,
															$item_type            => $this->settings->get_current_setting( 'us_' . $item_type, $id, in_array( $item_type, [
																'limit_per_day',
																'user_logged'
															] ) ? '' : array() ),
														),
														'',
														VICUFFW_CHECKOUT_UPSELL_FUNNEL_TEMPLATES );
												}
											}
											?>
                                        </div>
                                        <span class="vi-ui positive mini button vi-wcuf-add-condition-btn vi-wcuf-user_rule-add-condition"
                                              data-rule_type="user" data-rule_prefix="us_">
                                                <?php esc_html_e( 'Add Conditions(AND)', 'checkout-upsell-funnel-for-woo' ); ?>
                                            </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="vi-ui blue yellow">
							<?php
							esc_html_e( 'You can add multiple rules in premium version. The plugin scans rules from top to bottom, if a rule is qualified it will be applied.', 'checkout-upsell-funnel-for-woo' );
							?>
                            <a class="vi-ui button" href="https://1.envato.market/oeemke"
                               target="_blank"><?php esc_html_e( 'Unlock This Feature', 'checkout-upsell-funnel-for-woo' ); ?> </a>
                        </div>
                        <div class="field vi-wcuf-rule-new-wrap vi-wcuf-pricing-rule-new-wrap vi-wcuf-hidden">
                            <div class="vi-wcuf-pd-condition-new-wrap">
								<?php
								wc_get_template( 'admin-product-rule.php',
									array(
										'woo_currency_symbol' => $woo_currency_symbol,
									),
									'',
									VICUFFW_CHECKOUT_UPSELL_FUNNEL_TEMPLATES );
								?>
                            </div>
                            <div class="vi-wcuf-cart-condition-new-wrap">
								<?php
								wc_get_template( 'admin-cart-rule.php',
									array(
										'woo_currency_symbol' => $woo_currency_symbol,
										'woo_countries'       => $woo_countries,
									),
									'',
									VICUFFW_CHECKOUT_UPSELL_FUNNEL_TEMPLATES );
								?>
                            </div>
                            <div class="vi-wcuf-user-condition-new-wrap">
								<?php
								wc_get_template( 'admin-user-rule.php',
									array(
										'woo_currency_symbol' => $woo_currency_symbol,
										'woo_users_role'      => $woo_users_role,
									),
									'',
									VICUFFW_CHECKOUT_UPSELL_FUNNEL_TEMPLATES );
								?>
                            </div>
                        </div>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="design">
                        <div class="vi-ui fluid styled accordion active vi-wcuf-accordion-wrap">
                            <div class="title active">
                                <i class="dropdown icon"></i>
								<?php esc_html_e( 'Layout', 'checkout-upsell-funnel-for-woo' ); ?>
                            </div>
                            <div class="content active">
								<?php
								$us_content       = $this->settings->get_params( 'us_content' );
								$us_border_color  = $this->settings->get_params( 'us_border_color' );
								$us_border_style  = $this->settings->get_params( 'us_border_style' );
								$us_border_width  = $this->settings->get_params( 'us_border_width' ) ?: 0;
								$us_border_radius = $this->settings->get_params( 'us_border_radius' ) ?: 0;
								?>
                                <div class="field">
                                    <div class="field">
                                        <label><?php esc_html_e( 'Content', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                        <input type="text" class="vi-wcuf-us_content" name="us_content" value="<?php echo esc_attr( $us_content ); ?>"
                                               placeholder="<?php echo esc_attr( '{countdown_timer}{content}' ); ?>">
                                        <p class="description">
											<?php echo sprintf( '{content} - %s', esc_html__( 'Go to content tab to customize it', 'checkout-upsell-funnel-for-woo' ) ); ?>
                                        </p>
                                        <p class="description">
											<?php echo sprintf( '{countdown_timer} - %s', esc_html__( 'Go to Countdown timer tab to customize it', 'checkout-upsell-funnel-for-woo' ) ); ?>
                                        </p>
                                        <p class="description vi-wcuf-warning-message <?php echo esc_attr(strpos( $us_content, '{content}' ) === false ? '' :  'vi-wcuf-hidden' ); ?>">
											<?php esc_html_e( 'The recommended products will not show if content does not include {content}', 'checkout-upsell-funnel-for-woo' ); ?>
                                        </p>
                                    </div>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border Color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text"
                                                   class="vi-wcuf-color vi-wcuf-us_border_color"
                                                   name="us_border_color"
                                                   value="<?php echo esc_attr( $us_border_color ) ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border style', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <select name="us_border_style" class="vi-ui fluid dropdown vi-wcuf-us_border_style">
                                                <option value="none" <?php selected( $us_border_style, 'none' ) ?>>
													<?php esc_html_e( 'None', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </option>
                                                <option value="dashed" <?php selected( $us_border_style, 'dashed' ) ?>>
													<?php esc_html_e( 'Dashed', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </option>
                                                <option value="double" <?php selected( $us_border_style, 'double' ) ?>>
													<?php esc_html_e( 'Double', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </option>
                                                <option value="dotted" <?php selected( $us_border_style, 'dotted' ) ?>>
													<?php esc_html_e( 'Dotted', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </option>
                                                <option value="solid" <?php selected( $us_border_style, 'solid' ) ?>>
													<?php esc_html_e( 'Solid', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border width', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number"
                                                       class="vi-wcuf-us_border_width"
                                                       name="us_border_width"
                                                       step="1"
                                                       min="0"
                                                       value="<?php echo esc_attr( $us_border_width ) ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border radius', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number"
                                                       class="vi-wcuf-us_border_radius"
                                                       name="us_border_radius"
                                                       step="1"
                                                       min="0"
                                                       value="<?php echo esc_attr( $us_border_radius ) ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="title">
                                <i class="dropdown icon"></i>
								<?php esc_html_e( 'Content in pop up', 'checkout-upsell-funnel-for-woo' ); ?>
                            </div>
                            <div class="content">
								<?php
								$us_pd_template        = $this->settings->get_params( 'us_pd_template' );
								$us_header_content     = $this->settings->get_params( 'us_header_content' );
								$us_header_bg_color    = $this->settings->get_params( 'us_header_bg_color' );
								$us_header_padding     = $this->settings->get_params( 'us_header_padding' );
								$us_container_content  = $this->settings->get_params( 'us_container_content' );
								$us_container_bg_color = $this->settings->get_params( 'us_container_bg_color' );
								$us_container_padding  = $this->settings->get_params( 'us_container_padding' );
								$us_footer_content     = $this->settings->get_params( 'us_footer_content' );
								$us_footer_bg_color    = $this->settings->get_params( 'us_footer_bg_color' );
								$us_footer_padding     = $this->settings->get_params( 'us_footer_padding' );
								?>
                                <div class="field">
                                    <h5 class="vi-ui header dividing"><?php esc_html_e( 'Header', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label>
												<?php esc_html_e( 'Content', 'checkout-upsell-funnel-for-woo' ); ?>
                                            </label>
                                            <input type="text" name="us_header_content" value="<?php echo esc_attr( $us_header_content ); ?>"
                                                   placeholder="<?php echo esc_attr( '{title}{continue_button}' ); ?>">
                                        </div>
                                        <div class="field">
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Background', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text" class="vi-wcuf-color vi-wcuf-us_header_bg_color"
                                                           name="us_header_bg_color" value="<?php echo esc_attr( $us_header_bg_color ) ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Padding', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text" class="vi-wcuf-us_header_padding"
                                                           name="us_header_padding" value="<?php echo esc_attr( $us_header_padding ); ?>"
                                                           placeholder="<?php echo esc_attr( '10px 15px' ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="vi-ui header dividing"><?php esc_html_e( 'Container', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Content', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_container_content" value="<?php echo esc_attr( $us_container_content ); ?>"
                                                   placeholder="<?php echo esc_attr( '{product_list}' ); ?>">
                                        </div>
                                        <div class="field">
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Background', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text"
                                                           class="vi-wcuf-color vi-wcuf-us_container_bg_color"
                                                           name="us_container_bg_color"
                                                           value="<?php echo esc_attr( $us_container_bg_color ); ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Padding', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text" class="vi-wcuf-us_container_padding"
                                                           name="us_container_padding" value="<?php echo esc_attr( $us_container_padding ); ?>"
                                                           placeholder="<?php echo esc_attr( '10px 15px' ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="vi-ui header dividing"><?php esc_html_e( 'Footer', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Content', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_footer_content" value="<?php echo esc_attr( $us_footer_content ); ?>"
                                                   placeholder="<?php echo esc_attr( '{add_all_to_cart}' ); ?>">
                                        </div>
                                        <div class="field">
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Background', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text"
                                                           class="vi-wcuf-color vi-wcuf-us_footer_bg_color"
                                                           name="us_footer_bg_color"
                                                           value="<?php echo esc_attr( $us_footer_bg_color ); ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Padding', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text" class="vi-wcuf-us_footer_padding"
                                                           name="us_footer_padding" value="<?php echo esc_attr( $us_footer_padding ); ?>"
                                                           placeholder="<?php echo esc_attr( '10px 15px' ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="vi-ui header dividing"><?php esc_html_e( 'Shortcode', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <div class="field">
                                        <p class="description">
											<?php echo sprintf( '{title} - %s', esc_html__( 'The display of title popup, it is customized in the title tab', 'checkout-upsell-funnel-for-woo' ) ) ?>
                                        </p>
                                        <p class="description">
											<?php echo sprintf( '{product_list} - %s',
												esc_html__( 'Display the list of recommended products, this shortcode  is only used in container of pop up and designed in Product list tab and customized in product list tab', 'checkout-upsell-funnel-for-woo' ) ) ?>
                                        </p>
                                        <p class="description">
											<?php echo sprintf( '{continue_button} - %s', esc_html__( 'Display the button for continuing checkout and it was customized in Continue button tab', 'checkout-upsell-funnel-for-woo' ) ) ?>
                                        </p>
                                        <p class="description">
											<?php echo sprintf( '{countdown_timer} - %s',
												esc_html__( 'Display Countdown timer for watching and buying recommend products by customers, and it was customized in Countdown timer tab', 'checkout-upsell-funnel-for-woo' ) ) ?>
                                        </p>
                                        <p class="description">
											<?php echo sprintf( '{add_all_to_cart} - %s',
												esc_html__( 'Display the button which can add all recommended products to Cart. If the recommended products are appeared after clicking \'place order\' and the customers click on \'add all to Cart\' button, the customers can checkout all in one time.It is not working if Product Template of Product list tab is \'Add to cart with checkbox. This shortcode was customized in \'Add all to Cart button\' tab', 'checkout-upsell-funnel-for-woo' ) ) ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="title">
                                <i class="dropdown icon"></i>
								<?php esc_html_e( 'Title', 'checkout-upsell-funnel-for-woo' ); ?>
                            </div>
                            <div class="content">
								<?php
								$us_title           = $this->settings->get_params( 'us_title' );
								$us_title_color     = $this->settings->get_params( 'us_title_color' );
								$us_title_font_size = $this->settings->get_params( 'us_title_font_size' ) ?: 0;
								?>
                                <div class="field">
                                    <div class="field">
                                        <label><?php esc_html_e( 'Message', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                        <textarea name="us_title" id="vi-wpvs-us_title" class="vi-wpvs-us_title"
                                                  rows="5"><?php echo esc_textarea( $us_title ) ?></textarea>
                                        <p class="description">
											<?php echo sprintf( '{discount_amount} - %s ', esc_html__( 'The discount amount for one product', 'checkout-upsell-funnel-for-woo' ) ) ?>
                                        </p>
                                        <p class="description">
											<?php echo sprintf( '{discount_type} - %s', esc_html__( 'The discount amount in regular or current price', 'checkout-upsell-funnel-for-woo' ) ) ?>
                                        </p>
                                    </div>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" class="vi-wcuf-color vi-wcuf-us_title_color"
                                                   name="us_title_color" value="<?php echo esc_attr( $us_title_color ) ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Font size', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" name="us_title_font_size" class="vi-wcuf-us_title_font_size"
                                                       min="0" step="1" value="<?php echo esc_attr( $us_title_font_size ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="title">
                                <i class="dropdown icon"></i>
								<?php esc_html_e( 'Continue button', 'checkout-upsell-funnel-for-woo' ); ?>
                            </div>
                            <div class="content">
								<?php
								$us_bt_continue_title         = $this->settings->get_params( 'us_bt_continue_title' );
								$us_bt_continue_bg_color      = $this->settings->get_params( 'us_bt_continue_bg_color' );
								$us_bt_continue_color         = $this->settings->get_params( 'us_bt_continue_color' );
								$us_bt_continue_border_color  = $this->settings->get_params( 'us_bt_continue_border_color' );
								$us_bt_continue_border_width  = $this->settings->get_params( 'us_bt_continue_border_width' ) ?: 0;
								$us_bt_continue_border_radius = $this->settings->get_params( 'us_bt_continue_border_radius' ) ?: 0;
								$us_bt_continue_font_size     = $this->settings->get_params( 'us_bt_continue_font_size' ) ?: 0;
								
								$skip_icons             = $this->settings->get_class_icons( 'skip_icons' );
								$us_skip_icon           = $this->settings->get_params( 'us_skip_icon' ) ?: '7';
								$us_skip_icon_color     = $this->settings->get_params( 'us_skip_icon_color' );
								$us_skip_icon_font_size = $this->settings->get_params( 'us_skip_icon_font_size' ) ?: 0;
								?>
                                <div class="field">
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Title', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_bt_continue_title" class="vi-wcuf-us_bt_continue_title"
                                                   value="<?php echo esc_attr( $us_bt_continue_title ); ?>">
                                            <p class="description">
												<?php echo sprintf( '{skip_icon} - %s', esc_html__( 'The skip icon', 'checkout-upsell-funnel-for-woo' ) ); ?>
                                            </p>
                                        </div>
                                        <div class="field">
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Background', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text" class="vi-wcuf-color vi-wcuf-us_bt_continue_bg_color"
                                                           name="us_bt_continue_bg_color" value="<?php echo esc_attr( $us_bt_continue_bg_color ); ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text" class="vi-wcuf-color vi-wcuf-us_bt_continue_color"
                                                           name="us_bt_continue_color" value="<?php echo esc_attr( $us_bt_continue_color ); ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Border color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text" class="vi-wcuf-color vi-wcuf-us_bt_continue_border_color"
                                                           name="us_bt_continue_border_color" value="<?php echo esc_attr( $us_bt_continue_border_color ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border width', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" class="vi-wcuf-us_bt_continue_border_width"
                                                       name="us_bt_continue_border_width" min="0" step="1" value="<?php echo esc_attr( $us_bt_continue_border_width ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border radius', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" class="vi-wcuf-us_bt_continue_border_radius"
                                                       name="us_bt_continue_border_radius" min="0" step="1" value="<?php echo esc_attr( $us_bt_continue_border_radius ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Font size', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" class="vi-wcuf-us_bt_continue_font_size"
                                                       name="us_bt_continue_font_size" min="0" step="1" value="<?php echo esc_attr( $us_bt_continue_font_size ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="vi-ui header dividing"><?php esc_html_e( 'Skip icon', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <div class="field">
                                        <label><?php esc_html_e( 'Icon', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                        <div class="fields vi-wcuf-fields-icons">
											<?php
											foreach ( $skip_icons as $k => $icon ) {
												?>
                                                <div class="field ">
                                                    <div class="vi-ui center aligned segment radio checked checkbox">
                                                        <input type="radio" name="us_skip_icon" class="vi-wcuf-us_skip_icon"
                                                               value="<?php echo esc_attr( $k ); ?>" <?php checked( $k, $us_skip_icon ) ?>>
                                                        <label><i class="viwcuf-icon <?php echo esc_attr( $icon ); ?>"></i></label>
                                                    </div>
                                                </div>
												<?php
											}
											?>
                                        </div>
                                        <div class="equal width fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                <input type="text" class="vi-wcuf-color vi-wcuf-us_skip_icon_color"
                                                       name="us_skip_icon_color" value="<?php echo esc_attr( $us_skip_icon_color ); ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Font size', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                <div class="vi-ui right labeled input">
                                                    <input type="number" class="vi-wcuf-us_skip_icon_font_size" min="0" step="1"
                                                           name="us_skip_icon_font_size" value="<?php echo esc_attr( $us_skip_icon_font_size ); ?>">
                                                    <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="title vi-wcuf-us_pd_atc<?php echo esc_attr( in_array( $us_pd_template, [ '2' ] ) ? ' vi-wcuf-hidden'  : ''); ?>">
                                <i class="dropdown icon"></i>
								<?php esc_html_e( 'Add all to cart button', 'checkout-upsell-funnel-for-woo' ); ?>
                            </div>
                            <div class="content vi-wcuf-us_pd_atc<?php echo esc_attr(in_array( $us_pd_template, [ '2' ] ) ?  ' vi-wcuf-hidden'  : ''); ?>"
                                 data-tooltip="<?php esc_attr_e( 'The add all to cart button will not work if Product Template of Product list tab is \'Add to cart with checkbox\'', 'checkout-upsell-funnel-for-woo' ); ?>">
								<?php
								$us_bt_alltc_title         = $this->settings->get_params( 'us_bt_alltc_title' );
								$us_bt_alltc_bg_color      = $this->settings->get_params( 'us_bt_alltc_bg_color' );
								$us_bt_alltc_color         = $this->settings->get_params( 'us_bt_alltc_color' );
								$us_bt_alltc_border_color  = $this->settings->get_params( 'us_bt_alltc_border_color' );
								$us_bt_alltc_border_width  = $this->settings->get_params( 'us_bt_alltc_border_width' ) ?: 0;
								$us_bt_alltc_border_radius = $this->settings->get_params( 'us_bt_alltc_border_radius' ) ?: 0;
								$us_bt_alltc_font_size     = $this->settings->get_params( 'us_bt_alltc_font_size' ) ?: 0;
								
								$cart_icons              = $this->settings->get_class_icons( 'cart_icons' );
								$us_alltc_icon           = $this->settings->get_params( 'us_alltc_icon' );
								$us_alltc_icon_color     = $this->settings->get_params( 'us_alltc_icon_color' );
								$us_alltc_icon_font_size = $this->settings->get_params( 'us_alltc_icon_font_size' ) ?: 0;
								?>
                                <div class="field">
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Title', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_bt_alltc_title" class="vi-wcuf-us_bt_alltc_title"
                                                   value="<?php echo esc_attr( $us_bt_alltc_title ); ?>">
                                            <p class="description">
												<?php echo sprintf( '{cart_icon} - %s', esc_html__( 'The cart icon', 'checkout-upsell-funnel-for-woo' ) ); ?>
                                            </p>
                                        </div>
                                        <div class="field">
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Background', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text" class="vi-wcuf-color vi-wcuf-us_bt_alltc_bg_color"
                                                           name="us_bt_alltc_bg_color" value="<?php echo esc_attr( $us_bt_alltc_bg_color ); ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text" class="vi-wcuf-color vi-wcuf-us_bt_alltc_color"
                                                           name="us_bt_alltc_color" value="<?php echo esc_attr( $us_bt_alltc_color ); ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Border color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text" class="vi-wcuf-color vi-wcuf-us_bt_alltc_border_color"
                                                           name="us_bt_alltc_border_color" value="<?php echo esc_attr( $us_bt_alltc_border_color ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border width', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" class="vi-wcuf-us_bt_alltc_border_width"
                                                       name="us_bt_alltc_border_width" min="0" step="1" value="<?php echo esc_attr( $us_bt_alltc_border_width ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border radius', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" class="vi-wcuf-us_bt_alltc_border_radius"
                                                       name="us_bt_alltc_border_radius" min="0" step="1" value="<?php echo esc_attr( $us_bt_alltc_border_radius ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Font size', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" class="vi-wcuf-us_bt_alltc_font_size"
                                                       name="us_bt_alltc_font_size" min="0" step="1" value="<?php echo esc_attr( $us_bt_alltc_font_size ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="vi-ui header dividing">
										<?php esc_html_e( 'Cart icon', 'checkout-upsell-funnel-for-woo' ); ?>
                                    </h5>
                                    <div class="field">
                                        <label><?php esc_html_e( 'Icon', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                        <div class="fields vi-wcuf-fields-icons">
											<?php
											foreach ( $cart_icons as $k => $icon ) {
												?>
                                                <div class="field">
                                                    <div class="vi-ui radio checkbox center aligned segment">
                                                        <input type="radio" name="us_alltc_icon" class="vi-wcuf-us_alltc_icon"
                                                               value="<?php echo esc_attr( $k ); ?>" <?php checked( $k, $us_alltc_icon ) ?>>
                                                        <label><i class="viwcuf-icon <?php echo esc_attr( $icon ); ?>"></i></label>
                                                    </div>
                                                </div>
												<?php
											}
											?>
                                        </div>
                                    </div>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" class="vi-wcuf-color vi-wcuf-us_alltc_icon_color"
                                                   name="us_alltc_icon_color" value="<?php echo esc_attr( $us_alltc_icon_color ); ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Font size', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" class="vi-wcuf-us_alltc_icon_font_size" min="0" step="1"
                                                       name="us_alltc_icon_font_size" value="<?php echo esc_attr( $us_alltc_icon_font_size ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="title">
                                <i class="dropdown icon"></i>
								<?php esc_html_e( 'Countdown timer', 'checkout-upsell-funnel-for-woo' ); ?>
                            </div>
                            <div class="content">
								<?php
								$us_time_checkout       = $this->settings->get_params( 'us_time_checkout' );
								$us_time                = $this->settings->get_params( 'us_time' );
								$us_time_reset          = $this->settings->get_params( 'us_time_reset' );
								$us_countdown_message   = $this->settings->get_params( 'us_countdown_message' );
								$us_countdown_color     = $this->settings->get_params( 'us_countdown_color' );
								$us_countdown_font_size = $this->settings->get_params( 'us_countdown_font_size' );
								
								$us_progress_bar_bt_pause      = $this->settings->get_params( 'us_progress_bar_bt_pause' );
								$us_progress_bar_border_width  = $this->settings->get_params( 'us_progress_bar_border_width' );
								$us_progress_bar_diameter      = $this->settings->get_params( 'us_progress_bar_diameter' );
								$us_progress_bar_bg_color      = $this->settings->get_params( 'us_progress_bar_bg_color' );
								$us_progress_bar_border_color1 = $this->settings->get_params( 'us_progress_bar_border_color1' );
								$us_progress_bar_border_color2 = $this->settings->get_params( 'us_progress_bar_border_color2' );
								
								$us_bt_pause_title         = $this->settings->get_params( 'us_bt_pause_title' );
								$us_bt_pause_bg_color      = $this->settings->get_params( 'us_bt_pause_bg_color' );
								$us_bt_pause_color         = $this->settings->get_params( 'us_bt_pause_color' );
								$us_bt_pause_border_color  = $this->settings->get_params( 'us_bt_pause_border_color' );
								$us_bt_pause_border_width  = $this->settings->get_params( 'us_bt_pause_border_width' ) ?: 0;
								$us_bt_pause_border_radius = $this->settings->get_params( 'us_bt_pause_border_radius' ) ?: 0;
								$us_bt_pause_font_size     = $this->settings->get_params( 'us_bt_pause_font_size' ) ?: 0;
								
								$pause_icons             = $this->settings->get_class_icons( 'pause_icons' );
								$us_pause_icon           = $this->settings->get_params( 'us_pause_icon' );
								$us_pause_icon_color     = $this->settings->get_params( 'us_pause_icon_color' );
								$us_pause_icon_font_size = $this->settings->get_params( 'us_pause_icon_font_size' ) ?: 0;
								?>
                                <div class="field">
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Checkout page enable', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui toggle checkbox">
                                                <input type="hidden" id="vi-wcuf-us_time_checkout" name="us_time_checkout" value="<?php echo esc_attr( $us_time_checkout ); ?>">
                                                <input type="checkbox" id="vi-wcuf-us_time_checkout-checkbox" class="vi-wcuf-us_time_checkout-checkbox"
													<?php checked( $us_time_checkout, '1' ) ?>><label for="vi-wcuf-us_time_checkout-checkbox"></label>
                                            </div>
                                            <p class="description">
												<?php esc_html_e( 'Enable it to display countdown timer for buying recommended products on checkout page', 'checkout-upsell-funnel-for-woo' ); ?>
                                            </p>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Display time', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" min="5" id="vi-wcuf-us_time" max="300" name="us_time" value="<?php echo esc_attr( $us_time ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php esc_html_e( 'Seconds', 'checkout-upsell-funnel-for-woo' ); ?></div>
                                            </div>
                                            <p class="description">
												<?php esc_html_e( 'The time for countdown', 'checkout-upsell-funnel-for-woo' ); ?>
                                            </p>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Reset time', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" min="1" step="1" name="us_time_reset" id="vi-wcuf-us_time_reset" value="<?php echo esc_attr( $us_time_reset ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php esc_html_e( 'Days', 'checkout-upsell-funnel-for-woo' ); ?></div>
                                            </div>
                                            <p class="description">
												<?php esc_html_e( 'Set time for reappear recommend popup when the Cart is not checkout and popup is timed out', 'checkout-upsell-funnel-for-woo' ); ?>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Message', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_countdown_message" class="vi-wcuf-us_countdown_message"
                                                   value="<?php echo esc_attr( $us_countdown_message ); ?>">
                                            <p class="description">
												<?php echo sprintf( '{time} - %s', esc_html__( 'The time to continue checkout', 'checkout-upsell-funnel-for-woo' ) ); ?>
                                            </p>
                                            <p class="description">
												<?php echo sprintf( '{progress_bar} - %s',
													esc_html__( 'The bar used to visualization the remaining time of recommended popup', 'checkout-upsell-funnel-for-woo' ) ); ?>
                                            </p>
                                            <p class="description">
												<?php echo sprintf( '{pause_button} - %s',
													esc_html__( 'The button use to stop countdown and is only go with continue button', 'checkout-upsell-funnel-for-woo' ) ); ?>
                                            </p>
                                        </div>
                                        <div class="field">
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text" class="vi-wcuf-color vi-wcuf-us_countdown_color"
                                                           name="us_countdown_color" value="<?php echo esc_attr( $us_countdown_color ); ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Font size', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <div class="vi-ui right labeled input">
                                                        <input type="number" class="vi-wcuf-us_countdown_font_size" min="0" step="1"
                                                               name="us_countdown_font_size" value="<?php echo esc_attr( $us_countdown_font_size ); ?>">
                                                        <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="vi-ui header dividing"><?php esc_html_e( 'Progress bar', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Enable pause button', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui toggle checkbox">
                                                <input type="hidden" class="vi-wcuf-us_progress_bar_bt_pause"
                                                       name="us_progress_bar_bt_pause" value="<?php echo esc_attr( $us_progress_bar_bt_pause ); ?>">
                                                <input type="checkbox" class="vi-wcuf-us_progress_bar_bt_pause-checkbox"
													<?php checked( $us_progress_bar_bt_pause, 1 ); ?>>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border width', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" class="vi-wcuf-us_progress_bar_border_width"
                                                       name="us_progress_bar_border_width" min="0" step="1" value="<?php echo esc_attr( $us_progress_bar_border_width ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Diameter`', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" class="vi-wcuf-us_progress_bar_diameter"
                                                       name="us_progress_bar_diameter" min="0" step="1" value="<?php echo esc_attr( $us_progress_bar_diameter ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Background', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" class="vi-wcuf-color vi-wcuf-us_progress_bar_bg_color"
                                                   name="us_progress_bar_bg_color" value="<?php echo esc_attr( $us_progress_bar_bg_color ); ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border color 1', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" class="vi-wcuf-color vi-wcuf-us_progress_bar_border_color1"
                                                   name="us_progress_bar_border_color1" value="<?php echo esc_attr( $us_progress_bar_border_color1 ); ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border color 2', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" class="vi-wcuf-color vi-wcuf-us_progress_bar_border_color2"
                                                   name="us_progress_bar_border_color2" value="<?php echo esc_attr( $us_progress_bar_border_color2 ); ?>">
                                        </div>
                                    </div>
                                    <h5 class="vi-ui header dividing"><?php esc_html_e( 'Pause button', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Title', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_bt_pause_title" class="vi-wcuf-us_bt_pause_title"
                                                   value="<?php echo esc_attr( $us_bt_pause_title ); ?>">
                                            <p class="description">
												<?php echo sprintf( '{pause_icon} - %s', esc_html__( 'The cart icon', 'checkout-upsell-funnel-for-woo' ) ); ?>
                                            </p>
                                        </div>
                                        <div class="field">
                                            <div class="equal width fields">
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Background', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text" class="vi-wcuf-color vi-wcuf-us_bt_pause_bg_color"
                                                           name="us_bt_pause_bg_color" value="<?php echo esc_attr( $us_bt_pause_bg_color ); ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text" class="vi-wcuf-color vi-wcuf-us_bt_pause_color"
                                                           name="us_bt_pause_color" value="<?php echo esc_attr( $us_bt_pause_color ); ?>">
                                                </div>
                                                <div class="field">
                                                    <label><?php esc_html_e( 'Border color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                    <input type="text" class="vi-wcuf-color vi-wcuf-us_bt_pause_border_color"
                                                           name="us_bt_pause_border_color" value="<?php echo esc_attr( $us_bt_pause_border_color ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border width', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" class="vi-wcuf-us_bt_pause_border_width"
                                                       name="us_bt_pause_border_width" min="0" step="1" value="<?php echo esc_attr( $us_bt_pause_border_width ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border radius', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" class="vi-wcuf-us_bt_pause_border_radius"
                                                       name="us_bt_pause_border_radius" min="0" step="1" value="<?php echo esc_attr( $us_bt_pause_border_radius ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Font size', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" class="vi-wcuf-us_bt_pause_font_size"
                                                       name="us_bt_pause_font_size" min="0" step="1" value="<?php echo esc_attr( $us_bt_pause_font_size ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="vi-ui header dividing"><?php esc_html_e( 'Pause icon', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <div class="field">
                                        <label><?php esc_html_e( 'Icon', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                        <div class="fields vi-wcuf-fields-icons">
											<?php
											foreach ( $pause_icons as $k => $icon ) {
												?>
                                                <div class="field">
                                                    <div class="vi-ui radio checkbox center aligned segment">
                                                        <input type="radio" name="us_pause_icon" class="vi-wcuf-us_pause_icon"
                                                               value="<?php echo esc_attr( $k ); ?>" <?php checked( $k, $us_pause_icon ) ?>>
                                                        <label><i class="viwcuf-icon <?php echo esc_attr( $icon ); ?>"></i></label>
                                                    </div>
                                                </div>
												<?php
											}
											?>
                                        </div>
                                    </div>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" class="vi-wcuf-color vi-wcuf-us_pause_icon_color"
                                                   name="us_pause_icon_color" value="<?php echo esc_attr( $us_pause_icon_color ); ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Font size', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" class="vi-wcuf-us_pause_icon_font_size" min="0" step="1"
                                                       name="us_pause_icon_font_size" value="<?php echo esc_attr( $us_pause_icon_font_size ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="title">
                                <i class="dropdown icon"></i>
								<?php esc_html_e( 'Product list', 'checkout-upsell-funnel-for-woo' ); ?>
                            </div>
                            <div class="content">
								<?php
								$us_desktop_display_type      = $this->settings->get_params( 'us_desktop_display_type' );
								$us_mobile_display_type       = $this->settings->get_params( 'us_mobile_display_type' );
								$us_desktop_item_per_row      = $this->settings->get_params( 'us_desktop_item_per_row' ) ?: 4;
								$us_mobile_item_per_row       = $this->settings->get_params( 'us_mobile_item_per_row' ) ?: 1;
								$us_desktop_scroll_limit_rows = $this->settings->get_params( 'us_desktop_scroll_limit_rows' );
								$us_mobile_scroll_limit_rows  = $this->settings->get_params( 'us_mobile_scroll_limit_rows' );
								$us_pd_bg_color               = $this->settings->get_params( 'us_pd_bg_color' );
								$us_pd_box_shadow_color       = $this->settings->get_params( 'us_pd_box_shadow_color' );
								$us_pd_border_color           = $this->settings->get_params( 'us_pd_border_color' );
								$us_pd_border_radius          = $this->settings->get_params( 'us_pd_border_radius' ) ?: 0;
								$us_pd_img_padding            = $this->settings->get_params( 'us_pd_img_padding' );
								$us_pd_img_border_color       = $this->settings->get_params( 'us_pd_img_border_color' );
								$us_pd_img_border_width       = $this->settings->get_params( 'us_pd_img_border_width' ) ?: 0;
								$us_pd_img_border_radius      = $this->settings->get_params( 'us_pd_img_border_radius' ) ?: 0;
								$us_pd_details_padding        = $this->settings->get_params( 'us_pd_details_padding' );
								$us_pd_details_font_size      = $this->settings->get_params( 'us_pd_details_font_size' ) ?: 0;
								$us_pd_details_color          = $this->settings->get_params( 'us_pd_details_color' );
								$us_pd_details_text_align     = $this->settings->get_params( 'us_pd_details_text_align' );
								$us_pd_qty_bg_color           = $this->settings->get_params( 'us_pd_qty_bg_color' );
								$us_pd_qty_color              = $this->settings->get_params( 'us_pd_qty_color' );
								$us_pd_qty_border_color       = $this->settings->get_params( 'us_pd_qty_border_color' );
								$us_pd_qty_border_radius      = $this->settings->get_params( 'us_pd_qty_border_radius' ) ?: 0;
								$us_pd_atc_title              = $this->settings->get_params( 'us_pd_atc_title' );
								$us_pd_atc_bg_color           = $this->settings->get_params( 'us_pd_atc_bg_color' );
								$us_pd_atc_color              = $this->settings->get_params( 'us_pd_atc_color' );
								$us_pd_atc_border_color       = $this->settings->get_params( 'us_pd_atc_border_color' );
								$us_pd_atc_border_width       = $this->settings->get_params( 'us_pd_atc_border_width' ) ?: 0;
								$us_pd_atc_border_radius      = $this->settings->get_params( 'us_pd_atc_border_radius' ) ?: 0;
								$us_pd_atc_font_size          = $this->settings->get_params( 'us_pd_atc_font_size' ) ?: 0;
								$us_pd_atc_icon               = $this->settings->get_params( 'us_pd_atc_icon' );
								$us_pd_atc_icon_color         = $this->settings->get_params( 'us_pd_atc_icon_color' );
								$us_pd_atc_icon_font_size     = $this->settings->get_params( 'us_pd_atc_icon_font_size' ) ?: 0;
								?>
                                <div class="field">
                                    <div class="field">
                                        <label><?php esc_html_e( 'Desktop', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                        <div class="equal width fields">
                                            <div class="field">
                                                <div class="vi-ui right action labeled input">
                                                    <div class="vi-ui label vi-wcuf-basic-label">
														<?php esc_html_e( 'Display type', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </div>
                                                    <select name="us_desktop_display_type" id="vi-wcuf-us_desktop_display_type"
                                                            class="vi-ui fluid dropdown vi-wcuf-us_desktop_display_type">
                                                        <option value="slider" <?php selected( $us_desktop_display_type, 'slider' ); ?> >
															<?php esc_html_e( 'Slider', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                        <option value="scroll" <?php selected( $us_desktop_display_type, 'scroll' ); ?> >
															<?php esc_html_e( 'Scroll', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="vi-ui left labeled input">
                                                    <div class="vi-ui label vi-wcuf-basic-label">
														<?php esc_html_e( 'Item per row', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </div>
                                                    <input type="number" min="1" step="1" max="6" name="us_desktop_item_per_row"
                                                           class="vi-wcuf-us_desktop_item_per_row" value="<?php echo esc_attr( $us_desktop_item_per_row ); ?>">
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="vi-ui left labeled input">
                                                    <div class="vi-ui label vi-wcuf-basic-label">
														<?php esc_html_e( 'Row on Scroll', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </div>
                                                    <input type="number" step="1" min="1" name="us_desktop_scroll_limit_rows"
                                                           data-wcuf_allow_empty="1"
                                                           placeholder="<?php esc_attr_e( 'Leave blank to not limit this', 'checkout-upsell-funnel-for-woo' ); ?>"
                                                           class="vi-wcuf-us_desktop_scroll_limit_rows" value="<?php echo esc_attr( $us_desktop_scroll_limit_rows?:'' ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <label><?php esc_html_e( 'Mobile', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                        <div class="equal width fields">
                                            <div class="field">
                                                <div class="vi-ui right action labeled input">
                                                    <div class="vi-ui label vi-wcuf-basic-label">
														<?php esc_html_e( 'Display type', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </div>
                                                    <select name="us_mobile_display_type" id="vi-wcuf-us_mobile_display_type"
                                                            class="vi-ui fluid dropdown vi-wcuf-us_mobile_display_type">
                                                        <option value="slider" <?php selected( $us_mobile_display_type, 'slider' ); ?> >
															<?php esc_html_e( 'Slider', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                        <option value="scroll" <?php selected( $us_mobile_display_type, 'scroll' ); ?> >
															<?php esc_html_e( 'Scroll', 'checkout-upsell-funnel-for-woo' ); ?>
                                                        </option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="vi-ui left labeled input">
                                                    <div class="vi-ui label vi-wcuf-basic-label">
														<?php esc_html_e( 'Item per row', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </div>
                                                    <input type="number" min="1" step="1" max="6" name="us_mobile_item_per_row"
                                                           class="vi-wcuf-us_mobile_item_per_row" value="<?php echo esc_attr( $us_mobile_item_per_row ); ?>">
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="vi-ui left labeled input">
                                                    <div class="vi-ui label vi-wcuf-basic-label">
														<?php esc_html_e( 'Row on Scroll', 'checkout-upsell-funnel-for-woo' ); ?>
                                                    </div>
                                                    <input type="number" step="1" min="1" name="us_mobile_scroll_limit_rows"
                                                           data-wcuf_allow_empty="1"
                                                           placeholder="<?php esc_attr_e( 'Leave blank to not limit this', 'checkout-upsell-funnel-for-woo' ); ?>"
                                                           class="vi-wcuf-us_mobile_scroll_limit_rows" value="<?php echo esc_attr( $us_mobile_scroll_limit_rows ?: '' ); ?>">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="vi-ui header dividing"><?php esc_html_e( 'Product', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Template', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <select name="us_pd_template" class="vi-ui fluid dropdown vi-wcuf-us_pd_template">
                                                <option value="1" <?php selected( $us_pd_template, 1 ); ?>>
													<?php esc_html_e( 'Basic template', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </option>
                                                <option value="2" <?php selected( $us_pd_template, 2 ); ?>>
													<?php esc_html_e( 'Add to cart with checkbox', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Background', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_pd_bg_color" class="vi-wcuf-color vi-wcuf-us_pd_bg_color"
                                                   value="<?php echo esc_attr( $us_pd_bg_color ); ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Box shadow color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_pd_box_shadow_color" class="vi-wcuf-color vi-wcuf-us_pd_box_shadow_color"
                                                   value="<?php echo esc_attr( $us_pd_box_shadow_color ); ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_pd_border_color" class="vi-wcuf-color vi-wcuf-us_pd_border_color"
                                                   value="<?php echo esc_attr( $us_pd_border_color ); ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border radius', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" name="us_pd_border_radius" min="0" step="1" class="vi-wcuf-us_pd_border_radius"
                                                       value="<?php echo esc_attr( $us_pd_border_radius ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="vi-ui header dividing"><?php esc_html_e( 'Product image', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Padding', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" class="vi-wcuf-us_pd_img_padding"
                                                   name="us_pd_img_padding" value="<?php echo esc_attr( $us_pd_img_padding ); ?>"
                                                   placeholder="<?php echo esc_attr( '10px 15px' ); ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Image border color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_pd_img_border_color" class="vi-wcuf-color vi-wcuf-us_pd_img_border_color"
                                                   value="<?php echo esc_attr( $us_pd_img_border_color ); ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Image border width', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" min="0" step="1" name="us_pd_img_border_width" class="vi-wcuf-us_pd_img_border_width"
                                                       value="<?php echo esc_attr( $us_pd_img_border_width ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Image border radius', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" min="0" step="1" name="us_pd_img_border_radius" class="vi-wcuf-us_pd_img_border_radius"
                                                       value="<?php echo esc_attr( $us_pd_img_border_radius ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="vi-ui header dividing"><?php esc_html_e( 'Product details', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Padding', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" class="vi-wcuf-us_pd_details_padding"
                                                   name="us_pd_details_padding" value="<?php echo esc_attr( $us_pd_details_padding ); ?>"
                                                   placeholder="<?php echo esc_attr( '10px 15px' ); ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Font size', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" min="0" step="1" name="us_pd_details_font_size" class="vi-wcuf-us_pd_details_font_size"
                                                       value="<?php echo esc_attr( $us_pd_details_font_size ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_pd_details_color" class="vi-wcuf-color vi-wcuf-us_pd_details_color"
                                                   value="<?php echo esc_attr( $us_pd_details_color ); ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Text align', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <select name="us_pd_details_text_align" id="vi-wcuf-us_pd_details_text_align"
                                                    class="vi-ui fluid dropdown vi-wcuf-us_pd_details_text_align">
                                                <option value="center" <?php selected( $us_pd_details_text_align, 'center' ) ?>>
													<?php esc_html_e( 'Center', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </option>
                                                <option value="left" <?php selected( $us_pd_details_text_align, 'left' ) ?>>
													<?php esc_html_e( 'Left', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </option>
                                                <option value="right" <?php selected( $us_pd_details_text_align, 'right' ) ?>>
													<?php esc_html_e( 'Right', 'checkout-upsell-funnel-for-woo' ); ?>
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                    <h5 class="vi-ui header dividing"><?php esc_html_e( 'Product quantity', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Background', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_pd_qty_bg_color" class="vi-wcuf-color vi-wcuf-us_pd_qty_bg_color"
                                                   value="<?php echo esc_attr( $us_pd_qty_bg_color ); ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_pd_qty_color" class="vi-wcuf-color vi-wcuf-us_pd_qty_color"
                                                   value="<?php echo esc_attr( $us_pd_qty_color ); ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_pd_qty_border_color" class="vi-wcuf-color vi-wcuf-us_pd_qty_border_color"
                                                   value="<?php echo esc_attr( $us_pd_qty_border_color ); ?>">
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border radius', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" min="0" step="1" name="us_pd_qty_border_radius" class="vi-wcuf-us_pd_qty_border_radius"
                                                       value="<?php echo esc_attr( $us_pd_qty_border_radius ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="vi-ui header dividing vi-wcuf-us_pd_atc<?php echo  esc_attr(in_array( $us_pd_template, [ '2' ] ) ? ' vi-wcuf-hidden' : ''); ?>"><?php esc_html_e( 'Product \'Add To Cart\' button', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <h5 class="vi-ui header dividing vi-wcuf-us_pd_atc_checkbox<?php echo esc_attr( in_array( $us_pd_template, [ '2' ] ) ? '' : ' vi-wcuf-hidden' ); ?>"><?php esc_html_e( 'Product checkbox button', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <div class="equal width fields">
                                        <div class="field vi-wcuf-us_pd_atc<?php echo esc_attr(in_array( $us_pd_template, [ '2' ] ) ?  ' vi-wcuf-hidden'  : ''); ?>">
                                            <label><?php esc_html_e( 'Title', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <input type="text" name="us_pd_atc_title" class="vi-wcuf-us_pd_atc_title"
                                                   value="<?php echo esc_attr( $us_pd_atc_title ); ?>">
                                            <p class="description">
												<?php echo sprintf( '{cart_icon} - %s', esc_html__( 'The cart icon', 'checkout-upsell-funnel-for-woo' ) ); ?>
                                            </p>
                                        </div>
                                        <div class="equal width fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Background', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                <input type="text" name="us_pd_atc_bg_color" class="vi-wcuf-color vi-wcuf-us_pd_atc_bg_color"
                                                       value="<?php echo esc_attr( $us_pd_atc_bg_color ); ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                <input type="text" name="us_pd_atc_color" class="vi-wcuf-color vi-wcuf-us_pd_atc_color"
                                                       value="<?php echo esc_attr( $us_pd_atc_color ); ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Border color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                <input type="text" name="us_pd_atc_border_color" class="vi-wcuf-color vi-wcuf-us_pd_atc_border_color"
                                                       value="<?php echo esc_attr( $us_pd_atc_border_color ); ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border width', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" min="0" step="1" name="us_pd_atc_border_width" class="vi-wcuf-us_pd_atc_border_width"
                                                       value="<?php echo esc_attr( $us_pd_atc_border_width ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Border radius', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" min="0" step="1" name="us_pd_atc_border_radius" class="vi-wcuf-us_pd_atc_border_radius"
                                                       value="<?php echo esc_attr( $us_pd_atc_border_radius ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Font size', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                            <div class="vi-ui right labeled input">
                                                <input type="number" min="0" step="1" name="us_pd_atc_font_size" class="vi-wcuf-us_pd_atc_font_size"
                                                       value="<?php echo esc_attr( $us_pd_atc_font_size ); ?>">
                                                <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                    <h5 class="vi-ui header dividing vi-wcuf-us_pd_atc<?php echo esc_attr(in_array( $us_pd_template, [ '2' ] ) ?  ' vi-wcuf-hidden'  : ''); ?>"><?php esc_html_e( 'Cart icon', 'checkout-upsell-funnel-for-woo' ); ?></h5>
                                    <div class="field vi-wcuf-us_pd_atc<?php echo esc_attr( in_array( $us_pd_template, [ '2' ] ) ? ' vi-wcuf-hidden'  : ''); ?>">
                                        <label><?php esc_html_e( 'Icon', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                        <div class="fields vi-wcuf-fields-icons">
											<?php
											foreach ( $cart_icons as $k => $icon ) {
												?>
                                                <div class="field">
                                                    <div class="vi-ui radio checkbox center aligned segment">
                                                        <input type="radio" name="us_pd_atc_icon" class="vi-wcuf-us_pd_atc_icon"
                                                               value="<?php echo esc_attr( $k ); ?>" <?php checked( $k, $us_pd_atc_icon ) ?>>
                                                        <label><i class="viwcuf-icon <?php echo esc_attr( $icon ); ?>"></i></label>
                                                    </div>
                                                </div>
												<?php
											}
											?>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <div class="equal width fields vi-wcuf-us_pd_atc<?php echo esc_attr( in_array( $us_pd_template, [ '2' ] ) ? ' vi-wcuf-hidden'  : ''); ?>">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Color', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                <input type="text" class="vi-wcuf-color vi-wcuf-us_pd_atc_icon_color"
                                                       name="us_pd_atc_icon_color" value="<?php echo esc_attr( $us_pd_atc_icon_color ); ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Font size', 'checkout-upsell-funnel-for-woo' ); ?></label>
                                                <div class="vi-ui right labeled input">
                                                    <input type="number" class="vi-wcuf-us_pd_atc_icon_font_size" min="0" step="1"
                                                           name="us_pd_atc_icon_font_size" value="<?php echo esc_attr( $us_pd_atc_icon_font_size ); ?>">
                                                    <div class="vi-ui label vi-wcuf-basic-label"><?php echo esc_html( 'Px' ); ?></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="vi-wcuf-save-wrap">
                        <button type="button" class="vi-wcuf-save vi-ui primary button" name="vi-wcuf-save">
							<?php esc_html_e( 'Save', 'checkout-upsell-funnel-for-woo' ); ?>
                        </button>
                    </p>
                </form>
				<?php do_action( 'villatheme_support_checkout-upsell-funnel-for-woo' ); ?>
            </div>
        </div>
		<?php
	}
	
	public function admin_enqueue_scripts() {
		$page  = isset( $_REQUEST['page'] ) ? sanitize_text_field( wp_unslash( $_REQUEST['page'] ) ) : '';
		$admin = 'VICUFFW_CHECKOUT_UPSELL_FUNNEL_Admin_Settings';
		if ( $page === 'checkout-upsell-funnel-for-woo' ) {
			$admin::remove_other_script();
			$admin::enqueue_style(
				array( 'semantic-ui-accordion', 'semantic-ui-button', 'semantic-ui-checkbox', 'semantic-ui-dropdown', 'semantic-ui-form', 'semantic-ui-header', 'semantic-ui-icon' ),
				array( 'accordion.min.css', 'button.min.css', 'checkbox.min.css', 'dropdown.min.css', 'form.min.css', 'header.min.css', 'icon.min.css' )
			);
			$admin::enqueue_style(
				array( 'semantic-ui-input', 'semantic-ui-label', 'semantic-ui-menu', 'semantic-ui-message', 'semantic-ui-popup', 'semantic-ui-segment', 'semantic-ui-tab' ),
				array( 'input.min.css', 'label.min.css', 'menu.min.css', 'message.min.css', 'popup.min.css', 'segment.min.css', 'tab.css' )
			);
			$admin::enqueue_style(
				array( 'vi-wcuf-admin-settings', 'vi-wcuf-cart_icons', 'vi-wcuf-skip_icons', 'vi-wcuf-pause_icons', 'select2', 'transition', 'minicolors' ),
				array( 'admin-settings.css', 'cart-icons.min.css', 'skip-icons.min.css', 'pause-icons.min.css', 'select2.min.css', 'transition.min.css', 'minicolors.css' )
			);
			$admin::enqueue_script(
				array( 'semantic-ui-accordion', 'semantic-ui-address', 'semantic-ui-checkbox', 'semantic-ui-dropdown', 'semantic-ui-form', 'semantic-ui-tab', 'transition' ),
				array( 'accordion.min.js', 'address.min.js', 'checkbox.min.js', 'dropdown.min.js', 'form.min.js', 'tab.js', 'transition.min.js' )
			);
			$admin::enqueue_script(
				array( 'vi-wcuf-admin-settings', 'vi-wcuf-admin-upsell', 'minicolors', 'select2' ),
				array( 'admin-settings.js', 'admin-upsell.js', 'minicolors.min.js', 'select2.js', ),
				array( array( 'jquery' ), array( 'jquery', 'jquery-ui-sortable' ) )
			);
		}
	}
	
}
