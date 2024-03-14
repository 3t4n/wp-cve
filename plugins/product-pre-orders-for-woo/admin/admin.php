<?php

/*
Class Name: WOO_PRE_ORDER_SETTING_ADMIN
Author: villatheme
Author URI: http://villatheme.com
Copyright 2020-2021 villatheme.com. All rights reserved.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


class WPRO_WOO_PRE_ORDER_Admin_Admin {

	public function __construct() {
		add_filter( 'plugin_action_links_product-pre-orders-for-woo/product-pre-orders-for-woo.php', array(
			$this,
			'settings_link'
		) );
		add_action( 'admin_enqueue_scripts', array( $this, 'script_and_css_backend' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'init', array( $this, 'init' ) );
	}

	public function script_and_css_backend() {
		$current_screen = get_current_screen()->id;
		$page_now       = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : '';
		if ( $page_now == 'product-pre-orders-for-woo' || $current_screen == 'product' ) {
			wp_enqueue_style( 'product-pre-orders-for-woo-admin', WPRO_WOO_PRE_ORDER_CSS . 'product-pre-orders-for-woo-setting-admin.css' );
			wp_enqueue_style( 'product-pre-orders-for-woo-segment', WPRO_WOO_PRE_ORDER_CSS . 'segment.min.css' );
			wp_enqueue_style( 'product-pre-orders-for-woo-menu', WPRO_WOO_PRE_ORDER_CSS . 'menu.min.css' );
			wp_enqueue_style( 'product-pre-orders-for-woo-checkbox', WPRO_WOO_PRE_ORDER_CSS . 'checkbox.min.css' );
			wp_enqueue_style( 'product-pre-orders-for-woo-input', WPRO_WOO_PRE_ORDER_CSS . 'input.min.css' );
			wp_enqueue_style( 'product-pre-orders-for-woo-icon', WPRO_WOO_PRE_ORDER_CSS . 'icon.min.css' );
			wp_enqueue_style( 'product-pre-orders-for-woo-button', WPRO_WOO_PRE_ORDER_CSS . 'button.min.css' );
			wp_enqueue_style( 'product-pre-orders-for-woo-form', WPRO_WOO_PRE_ORDER_CSS . 'form.min.css' );
			wp_enqueue_style( 'product-pre-orders-for-woo-tab', WPRO_WOO_PRE_ORDER_CSS . 'tab.css' );
			wp_enqueue_style( 'product-pre-orders-for-woo-table', WPRO_WOO_PRE_ORDER_CSS . 'table.min.css' );
			wp_enqueue_style( 'product-pre-orders-for-woo-select2', WPRO_WOO_PRE_ORDER_CSS . 'select2.min.css' );
			wp_enqueue_style( 'product-pre-orders-for-woo-dropdown', WPRO_WOO_PRE_ORDER_CSS . 'dropdown.min.css' );
			wp_enqueue_style( 'product-pre-orders-for-woo-transition', WPRO_WOO_PRE_ORDER_CSS . 'transition.min.css' );
			wp_enqueue_script( 'product-pre-orders-for-woo-form-js', WPRO_WOO_PRE_ORDER_JS . 'form.js' );
			wp_enqueue_script( 'product-pre-orders-for-woo-checkbox-js', WPRO_WOO_PRE_ORDER_JS . 'checkbox.js' );
			wp_enqueue_script( 'product-pre-orders-for-woo-tab-js', WPRO_WOO_PRE_ORDER_JS . 'tab.js' );
			wp_enqueue_script( 'product-pre-orders-for-woo-select2-js', WPRO_WOO_PRE_ORDER_JS . 'select2.js' );
			wp_enqueue_script( 'product-pre-orders-for-woo-dropdown-js', WPRO_WOO_PRE_ORDER_JS . 'dropdown.min.js' );
			wp_enqueue_script( 'product-pre-orders-for-woo-transition-js', WPRO_WOO_PRE_ORDER_JS . 'transition.min.js' );
			wp_enqueue_script( 'product-pre-orders-for-woo-iris-js', WPRO_WOO_PRE_ORDER_JS . 'iris.min.js', array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			), false, 1 );

			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wp-color-picker' );
			wp_enqueue_script( 'product-pre-orders-for-woo-setting-js', WPRO_WOO_PRE_ORDER_JS . 'product-pre-orders-for-woo-setting.js', array( 'jquery' ), WPRO_WOO_PRE_ORDER_VERSION );
			wp_localize_script( 'product-pre-orders-for-woo-setting-js', 'price',
				array(
					'status' => esc_html__( 'You have not entered a price for the product Pre-Order', 'product-pre-orders-for-woo' ),
				)
			);
		}
		if ( $current_screen == 'edit-product' ) {
			wp_enqueue_style( 'product-pre-orders-for-woo-all-product', WPRO_WOO_PRE_ORDER_CSS . 'product-pre-orders-for-woo-all-product-page.css' );
		}
	}

	public function admin_menu() {
		add_menu_page(
			__( 'Pre-Orders', 'product-pre-orders-for-woo' ),
			__( 'Pre-Orders', 'product-pre-orders-for-woo' ),
			'manage_woocommerce',
			'product-pre-orders-for-woo',
			array( $this, 'pre_order_page_setting' ),
			'dashicons-cart',
			4
		);
	}

	public function save_option() {
		if ( isset( $_POST['wpro_pre_order_field'] ) && wp_verify_nonce( $_POST['wpro_pre_order_field'], 'woo_pre_order_setting_page_save' ) ) {
			$args = array(
				'enabled'              => isset( $_POST['woo_pre_enabled'] ) && sanitize_text_field( $_POST['woo_pre_enabled'] ) ? 'yes' : 'no',
				'price_calculation'    => isset( $_POST['woo_pre_price_calculation'] ) && sanitize_text_field( $_POST['woo_pre_price_calculation'] ) ? 'yes' : 'no',
				'default_label_simple' => isset( $_POST['woo_pre_default_label_simple'] ) ? sanitize_text_field( $_POST['woo_pre_default_label_simple'] ) : '',
				'no_date_text'         => isset( $_POST['woo_pre_no_date_text'] ) ? sanitize_text_field( $_POST['woo_pre_no_date_text'] ) : '',
				'date_text'            => isset( $_POST['woo_pre_date_text'] ) ? sanitize_text_field( $_POST['woo_pre_date_text'] ) : '',
				'label_variable'       => isset( $_POST['woo_pre_label_variable'] ) ? sanitize_text_field( $_POST['woo_pre_label_variable'] ) : '',
				'color_shop_page'      => isset( $_POST['woo_pre_color_shop_page'] ) ? sanitize_text_field( $_POST['woo_pre_color_shop_page'] ) : '',
				'color_stock_status'   => isset( $_POST['woo_pre_color_stock_status'] ) ? sanitize_text_field( $_POST['woo_pre_color_stock_status'] ) : '',
				'color_date_single'    => isset( $_POST['woo_pre_color_date_single'] ) ? sanitize_text_field( $_POST['woo_pre_color_date_single'] ) : '',
				'color_date_cart'      => isset( $_POST['woo_pre_color_date_cart'] ) ? sanitize_text_field( $_POST['woo_pre_color_date_cart'] ) : '',
				'color_date_shop_page' => isset( $_POST['woo_pre_color_date_shop_page'] ) ? sanitize_text_field( $_POST['woo_pre_color_date_shop_page'] ) : '',
			);
			update_option( 'pre_order_setting_default', $args );
		}

	}

	public function pre_order_page_setting() {
		$this->save_option();
		$get_option = get_option( 'pre_order_setting_default' );
		?>
        <div class="wrap woo-pre-order">
            <h1><?php esc_attr_e( 'Product Pre-Orders for WooCommerce Settings', 'product-pre-orders-for-woo' ) ?></h1>
            <form method="POST" action="" class="vi-ui form">
				<?php wp_nonce_field( 'woo_pre_order_setting_page_save', 'wpro_pre_order_field' ); ?>
                <div class="vi-ui top attached tabular menu">
                    <div class="item active" data-tab="general">
                        <label><?php esc_html_e( 'General', 'product-pre-orders-for-woo' ) ?></label>
                    </div>
                    <div class="item" data-tab="design">
                        <label><?php esc_html_e( 'Design', 'product-pre-orders-for-woo' ) ?></label>
                    </div>
                </div>
                <div class="vi-ui bottom attached tab segment active" data-tab="general">
                    <table class="optiontable form-table">
                        <tbody>
                        <tr>
                            <th>
                                <label>
									<?php esc_html_e( 'Enable Pre-Order on frontend', 'product-pre-orders-for-woo' ) ?>
                                </label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input type="checkbox"
                                           name="woo_pre_enabled" <?php if ( $get_option['enabled'] == 'yes' ) {
										echo esc_attr( 'checked' );
									} ?> value="yes">
                                    <label></label>
                                </div>
                                <p class="description"><?php esc_html_e( 'Uncheck this option to disable Pre-Order features on Frontend', 'product-pre-orders-for-woo' ) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label>
									<?php esc_html_e( 'Price calculation', 'product-pre-orders-for-woo' ) ?>
                                </label>
                            </th>
                            <td>
                                <div class="vi-ui toggle checkbox">
                                    <input type="checkbox"
                                           name="woo_pre_price_calculation" <?php if ( $get_option['price_calculation'] == 'yes' ) {
										echo esc_attr( 'checked' );
									} ?> value="yes"/>
                                    <label></label>
                                </div>
                                <p class="description"><?php esc_html_e( 'Enable this option, Pre-Order price will be calculated based on the sale price.', 'product-pre-orders-for-woo' ) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Default Add to Cart text', 'product-pre-orders-for-woo' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui input">
                                    <input type="text" name="woo_pre_default_label_simple"
                                           value="<?php echo esc_attr( $get_option['default_label_simple'] ); ?>"
                                           size="50"/>
                                    <label></label>
                                </div>
                                <p class="description"><?php esc_html_e( 'This text will be replaced on \'Add to Cart\' button. By leaving it blank, it will be \'Pre-Order Now\'.', 'product-pre-orders-for-woo' ) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'No date message', 'product-pre-orders-for-woo' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui input">
                                    <input type="text" name="woo_pre_no_date_text"
                                           value="<?php echo esc_attr( $get_option['no_date_text'] ); ?>"
                                           size="50"/>
                                    <label></label>
                                </div>
                                <p class="description"><?php esc_html_e( 'Text is displayed when the Pre-Order date is empty', 'product-pre-orders-for-woo' ) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Default availability date text', 'product-pre-orders-for-woo' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui input">
                                    <input type="text" name="woo_pre_date_text"
                                           value="<?php echo esc_attr( $get_option['date_text'] ); ?>" size="50"/>
                                    <label></label>
                                </div>
                                <p class="description"><?php esc_html_e( 'Text message when Pre-Order date is availble. By default: Available on:{availability_date} at {availability_time} , Available shortcodes: {availability_date},{availability_time}', 'product-pre-orders-for-woo' ) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Variable product label', 'product-pre-orders-for-woo' ) ?></label>
                            </th>
                            <td>
                                <div class="vi-ui input">
                                    <input type="text" name="woo_pre_label_variable"
                                           value="<?php echo esc_attr( $get_option['label_variable'] ); ?>"
                                           size="50"/>
                                    <label></label>
                                </div>
                                <p class="description"><?php esc_html_e( 'The label which will be showed when all variations of a variable product are Pre-Order.', 'product-pre-orders-for-woo' ) ?></p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="vi-ui bottom attached tab segment" data-tab="design">
                    <table class="optiontable form-table">
                        <tbody>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Color on single product page ', 'product-pre-orders-for-woo' ) ?></label>
                            </th>
                            <td class="color-single-product">
                                <input type="text" class="color-picker" name="woo_pre_color_date_single"
                                       value=" <?php if ( ! empty( $get_option['color_date_single'] ) ) {
									       echo esc_attr( $get_option['color_date_single'] );
								       } else {
									       echo esc_attr( '#2185d0' );
								       }
								       ?>"
                                       style="background-color: <?php if ( ! empty( $get_option['color_date_single'] ) ) {
									       echo esc_attr( $get_option['color_date_single'] );
								       } else {
									       echo esc_attr( '#2185d0' );
								       } ?>"
                                />
                                <p class="description"><?php esc_html_e( 'Change the color of the \'availability date\' and \'no date\' messages on the cart page.', 'product-pre-orders-for-woo' ) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Color on shop page ', 'product-pre-orders-for-woo' ) ?></label>
                            </th>
                            <td>
                                <input type="text" class="color-picker" name="woo_pre_color_date_shop_page"
                                       value=" <?php if ( ! empty( $get_option['color_date_shop_page'] ) ) {
									       echo esc_attr( $get_option['color_date_shop_page'] );
								       } else {
									       echo esc_attr( '#2185d0' );
								       }
								       ?>"
                                       style="background-color: <?php if ( ! empty( $get_option['color_date_shop_page'] ) ) {
									       echo esc_attr( $get_option['color_date_shop_page'] );
								       } else {
									       echo esc_attr( '#2185d0' );
								       } ?>"
                                />
                                <label></label>

                                <p class="description"><?php esc_html_e( 'Change the color of the \'availability date\' and \'no date\' messages on the shop page.', 'product-pre-orders-for-woo' ) ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th>
                                <label><?php esc_html_e( 'Color on cart page ', 'product-pre-orders-for-woo' ) ?></label>
                            </th>
                            <td>
                                <input type="text" class="color-picker" name="woo_pre_color_date_cart"
                                       value=" <?php if ( ! empty( $get_option['color_date_cart'] ) ) {
									       echo esc_attr( $get_option['color_date_cart'] );
								       } else {
									       echo esc_attr( '#2185d0' );
								       }
								       ?>"
                                       style="background-color: <?php if ( ! empty( $get_option['color_date_cart'] ) ) {
									       echo esc_attr( $get_option['color_date_cart'] );
								       } else {
									       echo esc_attr( '#2185d0' );
								       } ?>"
                                />
                                <p class="description"><?php esc_html_e( 'Change the color of the \'availability date\' and \'no date\' messages on the cart page.', 'product-pre-orders-for-woo' ) ?></p>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <p>
                    <button name="saveSetting" class="vi-ui button labeled icon primary wmc-submit">
                        <i class="send icon"></i> <?php esc_html_e( 'Save', 'product-pre-orders-for-woo' ) ?>
                    </button>
                </p>
            </form>
			<?php
			do_action( 'villatheme_support_product-pre-orders-for-woo' );
			?>
        </div>
		<?php
	}

	/**
	 * Link to Settings
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	public function settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=product-pre-orders-for-woo" title="' . esc_html__( 'Settings', 'product-pre-orders-for-woo' ) . '">' . esc_html__( 'Settings', 'product-pre-orders-for-woo' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * load Language translate
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'product-pre-orders-for-woo' );
		load_textdomain( 'product-pre-orders-for-woo', WPRO_WOO_PRE_ORDER_LANGUAGES . "product-pre-orders-for-woo-$locale.mo" );
		load_plugin_textdomain( 'product-pre-orders-for-woo', false, WPRO_WOO_PRE_ORDER_LANGUAGES );
	}

	public function init() {
		load_plugin_textdomain( 'product-pre-orders-for-woo' );
		$this->load_plugin_textdomain();
		if ( class_exists( 'VillaTheme_Support' ) ) {
			new VillaTheme_Support(
				array(
					'support'    => 'https://wordpress.org/support/plugin/pre-orders-for-woo/',
					'docs'       => 'https://docs.villatheme.com/woocommerce-product-pre-orders/',
					'review'     => 'https://wordpress.org/support/plugin/pre-orders-for-woo/reviews/?rate=5#rate-response',
					'pro_url'    => '',
					'css'        => WPRO_WOO_PRE_ORDER_CSS,
					'image'      => WPRO_WOO_PRE_ORDER_IMAGES,
					'slug'       => 'product-pre-orders-for-woo',
					'menu_slug'  => 'product-pre-orders-for-woo',
					'version'    => WPRO_WOO_PRE_ORDER_VERSION,
					'survey_url' => 'https://script.google.com/macros/s/AKfycbwuQNmdhWCAw0mpA8MV36HEbuqNP_JtBmCLcLBJVma6mSxMSO201Gci7-SaMQVJ0G3p/exec'
				)
			);
		}
	}
}
