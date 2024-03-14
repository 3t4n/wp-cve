<?php

/*
Class Name: SALES_COUNTDOWN_TIMER_Admin
Author: Andy Ha (support@villatheme.com)
Author URI: http://villatheme.com
Copyright 2018 villatheme.com. All rights reserved.
*/
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class SALES_COUNTDOWN_TIMER_Admin_Admin {
	protected $settings;

	function __construct() {
		$this->settings = new SALES_COUNTDOWN_TIMER_Data();
		add_action( 'init', array( $this, 'init' ) );
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ), 99 );
		add_action( 'wp_ajax_woo_sctr_save_settings', array( $this, 'save_settings' ) );
		add_filter(
			'plugin_action_links_sales-countdown-timer/sales-countdown-timer.php', array(
				$this,
				'settings_link'
			)
		);
	}

	public function admin_menu() {
		add_menu_page( __( 'Sales Countdown Timer', 'sales-countdown-timer' ), __( 'Countdown Timer', 'sales-countdown-timer' ), 'manage_options', 'sales-countdown-timer', array(
			$this,
			'settings'
		), 'dashicons-clock', 2 );

		if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			add_submenu_page(
				'sales-countdown-timer',
				__( 'Checkout Countdown', 'sales-countdown-timer' ),
				__( 'Checkout Countdown', 'sales-countdown-timer' ),
				'manage_options',
				'sales-countdown-timer-checkout',
				array( $this, 'settings_checkout_countdown' )
			);
		}
	}

	public function settings_checkout_countdown() {
		?>
        <div class="wrap">
            <h2 class=""><?php esc_html_e( 'Checkout Countdown Timer For WooCommerce', 'sales-countdown-timer' ) ?></h2>
            <div class="vi-ui raised">
                <form action="" class="vi-ui form" method="post">
                    <div class="vi-ui vi-ui-main top tabular attached menu">
                        <a class="item active" data-tab="general"><?php esc_html_e( 'General Settings', 'sales-countdown-timer' ) ?></a>
                        <a class="item" data-tab="display_on_page"><?php esc_html_e( 'Display checkout countdown', 'sales-countdown-timer' ) ?></a>
                        <a class="item" data-tab="design_on_cp"><?php esc_html_e( 'Design on checkout page', 'sales-countdown-timer' ) ?></a>
                        <a class="item" data-tab="design_on_op"><?php esc_html_e( 'Design on other page', 'sales-countdown-timer' ) ?></a>
                        <a class="item" data-tab="report"><?php esc_html_e( 'Report', 'sales-countdown-timer' ) ?></a>
                        <a class="item" data-tab="video_preview"><?php esc_html_e( 'Video Preview', 'sales-countdown-timer' ) ?></a>
                    </div>

                    <div class="vi-ui bottom attached tab segment active" data-tab="general">
                        <div class="vi-ui yellow message"><?php esc_html_e( 'Note*: Changes in these settings will not be applied to carts which are already in a checkout countdown.',
								'sales-countdown-timer' ) ?></div>
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-enable"><?php esc_html_e( 'Enable',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-reset"><?php esc_html_e( 'Reset Countdown',
											'sales-countdown-timer' ); ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                    <p class="description"><?php esc_html_e( 'Reset checkout countdown for a cart after this time if customer does not place order during checkout countdown',
											'sales-countdown-timer' ); ?></p>
                                </td>
                            </tr>
                            <tr class="top">
                                <th>
                                    <label for="woo-stcr-checkout-countdown-time-minute"><?php esc_html_e( 'Countdown time',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                    <p class="description"><?php esc_html_e( 'The maximum valid time that this offer bellow are applied. When time hits zero, offer will be gone',
											'sales-countdown-timer' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-start"><?php esc_html_e( 'Starting condition',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <div class="vi-ui labeled right action input">
                                                <div class="vi-ui basic label">
													<?php esc_html_e( 'Action', 'sales-countdown-timer' ) ?>
                                                </div>
                                                <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                                   target="_blank">
													<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                                </a>
                                            </div>
                                            <p class="description"><?php esc_html_e( 'Start checking if a cart is qualified to get offer when:',
													'sales-countdown-timer' ); ?></p>
                                        </div>
                                        <div class="field">
                                            <div class="vi-ui labeled right action input">
                                                <div class="vi-ui basic label">
													<?php printf( __( 'Min cart total(%s)', 'sales-countdown-timer' ),
														get_woocommerce_currency_symbol() ) ?>
                                                </div>
                                                <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                                   target="_blank">
													<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                                </a>
                                            </div>
                                            <p class="description"><?php esc_html_e( 'Minimum cart total to get offer',
													'sales-countdown-timer' ); ?></p>
                                        </div>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-discount"><?php esc_html_e( 'Checkout countdown offer',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <div class="vi-ui labeled right action input">
                                                <div class="vi-ui basic label">
													<?php esc_html_e( 'Free Shipping', 'sales-countdown-timer' ) ?>
                                                </div>
                                                <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                                   target="_blank">
													<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="field">
                                            <div class="vi-ui right action labeled input">
                                                <div class="vi-ui basic label">
													<?php esc_html_e( 'Discount amount', 'sales-countdown-timer' ) ?>
                                                </div>
                                                <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                                   target="_blank">
													<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="description"><?php esc_html_e( 'Customers will get this offer before checkout countdown time hits zero',
											'sales-countdown-timer' ); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <div class="vi-ui blue message">
                                        <div class="header"><?php esc_html_e( 'Below options are used if you want to change offer when the countdown hits specific values',
												'sales-countdown-timer' ) ?></div>
                                        <ul class="list">
                                            <li><?php esc_html_e( 'None: The current discount is applied until the countdown hits zero',
													'sales-countdown-timer' ) ?></li>
                                            <li><?php esc_html_e( 'Auto change: Decrease the current discount by a specific percentage every X minute(s) or second(s)',
													'sales-countdown-timer' ) ?></li>
                                            <li><?php esc_html_e( 'Custom: Create your own levels that you want to change the offer, message can be customized for each level',
													'sales-countdown-timer' ) ?></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-change"><?php esc_html_e( 'Level discount',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="display_on_page">
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-display-on-page"><?php esc_html_e( 'Display countdown timer on',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <div class="field">
                                        <div class="field">
                                            <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                               target="_blank">
												<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                            </a>
                                            <p class="description">
												<?php esc_html_e( 'Select pages to show checkout countdown. Leave blank to show checkout countdown on all pages.',
													'sales-countdown-timer' ) ?>
                                            </p>
                                        </div>

                                    </div>

                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="vi-ui segment">
                            <table class="form-table">
                                <tbody>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-checkout-button-title"><?php esc_html_e( 'Title of Checkout button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
                                        <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                           target="_blank">
											<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-checkout-button-link-target"><?php esc_html_e( 'Open in new tab when clicking Checkout button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
                                        <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                           target="_blank">
											<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-checkout-button-fontsize"><?php esc_html_e( 'Fontsize of Checkout button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
                                        <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                           target="_blank">
											<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-checkout-button-color"><?php esc_html_e( 'Text color of Checkout button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
                                        <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                           target="_blank">
											<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-checkout-button-background"><?php esc_html_e( 'Background color of Checkout button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
                                        <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                           target="_blank">
											<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="vi-ui segment">
                            <table class="form-table">
                                <tbody>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-shop-button-title"><?php esc_html_e( 'Title of Shop button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
                                        <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                           target="_blank">
											<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-shop-button-link-target"><?php esc_html_e( 'Open in new tab when clicking Shop button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
                                        <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                           target="_blank">
											<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-shop-button-fontsize"><?php esc_html_e( 'Fontsize of Shop button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
                                        <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                           target="_blank">
											<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-shop-button-color"><?php esc_html_e( 'Text color of Shop button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
                                        <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                           target="_blank">
											<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <th>
                                        <label for="woo-stcr-shop-button-background"><?php esc_html_e( 'Background color of Shop button',
												'sales-countdown-timer' ) ?></label>
                                    </th>
                                    <td>
                                        <a class="vi-ui button yellow" href="https://1.envato.market/962d3"
                                           target="_blank">
											<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                        </a>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="design_on_cp">
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-message-checkout-page"><?php esc_html_e( 'Checkout countdown message',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-message-checkout-page-missing"><?php esc_html_e( 'Message if missing offer',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                    <p class="description">
										<?php esc_html_e( 'This message only shows during checkout countdown when a cart is updated so that the current cart is smaller than the minimum required value.',
											'sales-countdown-timer' ) ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for=""><?php esc_html_e( 'Shortcode available',
											'sales-countdown-timer' ); ?></label>
                                </th>
                                <td>
                                    <p class="description">{countdown_timer}
                                        - <?php esc_html_e( 'The countdown timer that you set up on \'Sales Countdown Timer\' page',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{checkout_button}
                                        - <?php esc_html_e( 'The checkout button', 'sales-countdown-timer' ) ?></p>
                                    <p class="description">{shop_button}
                                        - <?php esc_html_e( 'The button go to store', 'sales-countdown-timer' ) ?></p>
                                    <p class="description">{discount_percentage}
                                        - <?php esc_html_e( 'The discount in percentage. Not apply to \'Message if missing offer\'',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{discount_fixed}
                                        - <?php esc_html_e( 'The discount amount in currency. Not apply to \'Message if missing offer\'',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{missing_amount}
                                        - <?php esc_html_e( 'The missing amount to get offer. Not apply to \'Checkout countdown message\'',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{minimum_cart_total}
                                        - <?php esc_html_e( 'The minimum cart total required to get offer',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{original_cart_total}
                                        - <?php esc_html_e( 'The current cart total without applying discount ',
											'sales-countdown-timer' ) ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-position-on-checkout-page"><?php esc_html_e( 'Position',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-id-on-checkout-page"><?php esc_html_e( 'Select Countdown timer',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="design_on_op">
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-message-other-page"><?php esc_html_e( 'Checkout countdown message',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-message-other-page-missing"><?php esc_html_e( 'Message if missing offer',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                    <p class="description">
										<?php esc_html_e( 'This message only shows during checkout countdown when a cart is updated so that the current cart is smaller than the minimum required value.',
											'sales-countdown-timer' ) ?>
                                    </p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for=""><?php esc_html_e( 'Shortcode available',
											'sales-countdown-timer' ); ?></label>
                                </th>
                                <td>
                                    <p class="description">{countdown_timer}
                                        - <?php esc_html_e( 'The countdown timer that you set up on \'Sales Countdown Timer\' page',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{checkout_button}
                                        - <?php esc_html_e( 'The checkout button', 'sales-countdown-timer' ) ?></p>
                                    <p class="description">{shop_button}
                                        - <?php esc_html_e( 'The button go to store', 'sales-countdown-timer' ) ?></p>
                                    <p class="description">{discount_percentage}
                                        - <?php esc_html_e( 'The discount in percentage. Not apply to \'Message if missing offer\'',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{discount_fixed}
                                        - <?php esc_html_e( 'The discount amount in currency. Not apply to \'Message if missing offer\'',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{missing_amount}
                                        - <?php esc_html_e( 'The missing amount to get offer. Not apply to \'Checkout countdown message\'',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{minimum_cart_total}
                                        - <?php esc_html_e( 'The minimum cart total required to get offer',
											'sales-countdown-timer' ) ?></p>
                                    <p class="description">{original_cart_total}
                                        - <?php esc_html_e( 'The current cart total without applying discount ',
											'sales-countdown-timer' ) ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-position-on-other-page"><?php esc_html_e( 'Position',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-countdown-id-on-other-page"><?php esc_html_e( 'Select Countdown timer',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="report">
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-save-logs">
										<?php esc_html_e( 'Save Logs', 'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-history-time">
										<?php esc_html_e( 'History time', 'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="vi-ui bottom attached tab segment" data-tab="video_preview">
                        <iframe width="560" height="315" src="https://www.youtube.com/embed/KVomxQCXMbA" frameborder="0"
                                allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture"
                                allowfullscreen></iframe>
                    </div>
                    <div class="vi-ui segment">
                        <table class="form-table">
                            <tbody>
                            <tr>
                                <th>
                                    <label for="woo-stcr-checkout-test-mode-enable"><?php esc_html_e( 'Test mode',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                    <p class="description"><?php esc_html_e( 'The checkout countdown will be applied only to Administrators for testing purpose',
											'sales-countdown-timer' ) ?></p>
                                </td>
                            </tr>
                            <tr>
                                <th>
                                    <label for=""><?php esc_html_e( 'Reset checkout countdown',
											'sales-countdown-timer' ) ?></label>
                                </th>
                                <td>
                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
										<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                    </a>
                                    <p class="description"><?php esc_html_e( 'Reset current checkout discount applied to your account when Test mode is enabled',
											'sales-countdown-timer' ) ?></p>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <p>
                        <button type="button" class="vi-ui button primary woo-ctr-settings-checkout-page-btnsave"
                                name="woo_ctr_settings_checkout_page_btnsave">
							<?php esc_html_e( 'Save', 'sales-countdown-timer' ) ?>
                        </button>
                    </p>
                </form>
            </div>
        </div>
		<?php
	}

	public function settings() {
		$id        = $this->settings->get_id();
		$div_class = is_rtl() ? 'woo-sctr-wrap woo-sctr-wrap-rtl' : 'woo-sctr-wrap';
		?>
        <div class="<?php echo esc_attr( $div_class ); ?>">
            <h2 class=""><?php esc_html_e( 'Sales Countdown Timer', 'sales-countdown-timer' ) ?></h2>
            <form class="vi-ui form" method="post">
				<?php
				wp_nonce_field( 'woo_ctr_settings_page_save', 'woo_ctr_nonce_field' );
				if ( get_transient( '_sales_countdown_timer_demo_product_init' ) ) {
					$sale_products     = get_transient( 'wc_products_onsale' );
					$default_countdown = count( $id ) ? $id[0] : 'salescountdowntimer';
					$now               = current_time( 'timestamp', true );
					$product_url       = '';
					if ( false === $sale_products ) {
						$products_args = array(
							'post_type'      => 'product',
							'status'         => 'publish',
							'posts_per_page' => - 1,
							'meta_query'     => array(
								'relation' => 'AND',
								array(
									'key'     => '_sale_price',
									'value'   => '',
									'compare' => '!=',
								),
								array(
									'key'     => '_sale_price_dates_to',
									'value'   => $now,
									'compare' => '>',
								)
							),
						);
						$the_query     = new WP_Query( $products_args );
						if ( $the_query->have_posts() ) {
							while ( $the_query->have_posts() ) {
								$the_query->the_post();
								$product_id = get_the_ID();
								update_post_meta( $product_id, '_woo_ctr_select_countdown_timer', $default_countdown );
								if ( ! $product_url ) {
									$product_url = get_permalink( $product_id );
								}
							}
						}
						wp_reset_postdata();
					} elseif ( is_array( $sale_products ) && count( $sale_products ) ) {
						foreach ( $sale_products as $product_id ) {
							update_post_meta( $product_id, '_woo_ctr_select_countdown_timer', $default_countdown );
							if ( ! $product_url ) {
								$product_url = get_permalink( $product_id );
							}
						}
					}
					if ( $product_url ) {
						echo esc_html__( 'See your very first sales countdown timer ', 'sales-countdown-timer' ) . '<a href="' . $product_url . '" target="_blank">' . esc_html__( 'here.', 'sales-countdown-timer' ) . '</a>';
						delete_transient( '_sales_countdown_timer_demo_product_init' );
					}
				}
				if ( is_array( $id ) && count( $id ) ) {
					?>
					<?php
					for ( $i = 0; $i < sizeof( $id ); $i ++ ) {
						switch ( $this->settings->get_time_separator()[ $i ] ) {
							case 'dot':
								$time_separator = '.';
								break;
							case 'comma':
								$time_separator = ',';
								break;
							case 'colon':
								$time_separator = ':';
								break;
							default:
								$time_separator = '';
						}
						?>
                        <div class="woo-sctr-accordion-wrap woo-sctr-accordion-wrap-<?php echo esc_attr( $i ); ?> vi-ui segment"
                             data-accordion_id="<?php echo esc_attr( $i ); ?>">
                            <div class="woo-sctr-accordion">
                                <div class="vi-ui toggle checkbox">
                                    <input type="hidden" name="woo_ctr_active[]"
                                           class="woo-sctr-active"
                                           value="<?php echo esc_attr( $this->settings->get_active()[ $i ] ); ?>">
                                    <input type="checkbox"
                                           class="woo-sctr-active" <?php echo $this->settings->get_active()[ $i ] ? 'checked' : ''; ?>><label>
                                </div>
                                <span class="woo-sctr-accordion-name"><?php echo esc_html( $this->settings->get_names()[ $i ] ); ?></span>

                                <span class="woo-sctr-short-description">
                                    <span class="woo-sctr-short-description-from"><?php echo esc_html__( 'From: ', 'sales-countdown-timer' ) ?>
                                        <span class="woo-sctr-short-description-from-date"><?php echo esc_html( $this->settings->get_sale_from_date()[ $i ] ) ?></span>&nbsp;
                                        <span class="woo-sctr-short-description-from-time"><?php echo esc_html( $this->settings->get_sale_from_time()[ $i ] ); ?></span>
                                    </span>
                                    <span class="woo-sctr-short-description-to"><?php echo esc_html__( 'To: ', 'sales-countdown-timer' ) ?>
                                        <span class="woo-sctr-short-description-to-date"><?php echo esc_html( $this->settings->get_sale_to_date()[ $i ] ) ?></span>&nbsp;
                                        <span class="woo-sctr-short-description-to-time"><?php echo esc_html( $this->settings->get_sale_to_time()[ $i ] ); ?></span>
                                    </span>
                                </span>
                                <div class="woo-sctr-shortcode-text">
                                    <span><?php echo esc_html__( 'Shortcode: ', 'sales-countdown-timer' ) ?></span><span><?php echo '[sales_countdown_timer id="' . $id[ $i ] . '"]'; ?></span>
                                </div>
                                <span class="woo-sctr-button-edit">
                                    <span class="woo-sctr-short-description-copy-shortcode vi-ui button"><?php esc_html_e( 'Copy shortcode', 'sales-countdown-timer' ); ?></span>
                                    <span class="woo-sctr-button-edit-duplicate vi-ui positive button"><?php esc_html_e( 'Duplicate', 'sales-countdown-timer' ) ?></span>
                                    <span class="woo-sctr-button-edit-remove vi-ui negative button"><?php esc_html_e( 'Remove', 'sales-countdown-timer' ) ?></span>
                                </span>
                            </div>
                            <div class="woo-sctr-panel vi-ui styled fluid accordion" id="woo-sctr-panel-accordion">
                                <div class="title  <?php if ( $this->settings->get_active()[ $i ] ) {
									echo 'active';
								} ?>">
                                    <i class="dropdown icon"></i>
									<?php esc_html_e( 'General settings', 'sales-countdown-timer' ) ?>
                                </div>
                                <div class="content  <?php if ( $this->settings->get_active()[ $i ] ) {
									echo 'active';
								} ?>">

                                    <div class="field">
                                        <label><?php esc_html_e( 'Name', 'sales-countdown-timer' ) ?></label>
                                        <input type="hidden" name="woo_ctr_id[]" class="woo-sctr-id"
                                               value="<?php echo esc_attr( $id[ $i ] ); ?>">
                                        <input type="text" name="woo_ctr_name[]" class="woo-sctr-name"
                                               value="<?php echo esc_attr( $this->settings->get_names()[ $i ] ); ?>">
                                    </div>

                                    <h4 class="vi-ui dividing header">
                                        <label><?php esc_html_e( 'Schedule time for shortcode usage', 'sales-countdown-timer' ) ?></label>
                                    </h4>
                                    <div class="field"
                                         data-tooltip="<?php esc_html_e( 'These values are used for shortcode only. To schedule sale for product please go to admin product.', 'sales-countdown-timer' ) ?>">
                                        <div class="two fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'From', 'sales-countdown-timer' ) ?></label>
                                                <div class="two fields">
                                                    <div class="field">
                                                        <input type="date"
                                                               name="woo_ctr_sale_from_date[]"
                                                               class="woo-sctr-sale-from-date woo-sctr-sale-date <?php if ( $this->settings->get_time_type()[ $i ] == 'loop' ) {
															       echo 'woo-sctr-hide-date';
														       } ?>"
                                                               value="<?php echo esc_url( $this->settings->get_sale_from_date()[ $i ] ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <input type="time"
                                                               name="woo_ctr_sale_from_time[]"
                                                               class="woo-sctr-sale-from-time"
                                                               value="<?php echo $this->settings->get_sale_from_time()[ $i ] ? esc_attr( $this->settings->get_sale_from_time()[ $i ] ) : '00:00' ?>">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'To', 'sales-countdown-timer' ) ?></label>
                                                <div class="two fields">
                                                    <div class="field">
                                                        <input type="date" name="woo_ctr_sale_to_date[]"
                                                               class="woo-sctr-sale-to-date woo-sctr-sale-date <?php if ( $this->settings->get_time_type()[ $i ] == 'loop' ) {
															       echo 'woo-sctr-hide-date';
														       } ?>"
                                                               value="<?php echo esc_attr( $this->settings->get_sale_to_date()[ $i ] ) ?>">
                                                    </div>
                                                    <div class="field">
                                                        <input type="time" name="woo_ctr_sale_to_time[]"
                                                               class="woo-sctr-sale-to-time"
                                                               value="<?php echo $this->settings->get_sale_to_time()[ $i ] ? esc_attr( $this->settings->get_sale_to_time()[ $i ] ) : '00:00' ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="equal width fields">
                                            <div class="field">
                                                <div class="vi-ui labeled right action input">
                                                    <div class="vi-ui basic label"><?php esc_html_e( 'Countdown evergreen', 'sales-countdown-timer' ); ?></div>
                                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
														<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="vi-ui labeled right action input">
                                                    <div class="vi-ui basic label"><?php esc_html_e( 'Restart countdown after', 'sales-countdown-timer' ); ?></div>
                                                    <a class="vi-ui button yellow" href="https://1.envato.market/962d3" target="_blank">
														<?php esc_html_e( 'Unlock This Feature', 'sales-countdown-timer' ); ?>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="title">
                                    <i class="dropdown icon"></i>
									<?php esc_html_e( 'Design', 'sales-countdown-timer' ) ?>
                                </div>
                                <div class=" content">
									<?php
									$message = $this->settings->get_message()[ $i ];
									$text    = explode( '{countdown_timer}', $message );
									if ( count( $text ) < 2 ) {
										$text_before = $text_after = '';
									} else {
										$text_before = $text[0];
										$text_after  = $text[1];
									}
									?>
                                    <div class="field">
                                        <label><?php esc_html_e( 'Message', 'sales-countdown-timer' ) ?></label>

                                        <input type="text" name="woo_ctr_message[]"
                                               class="woo-sctr-message"
                                               value="<?php echo esc_attr( $this->settings->get_message()[ $i ] ); ?>">
                                    </div>
                                    <div class="field">
                                        <p>{countdown_timer}
                                            - <?php esc_html_e( 'The countdown timer that you set on tab design', 'sales-countdown-timer' ) ?></p>
                                        <p class="woo-sctr-warning-message-countdown-timer <?php if ( count( $text ) >= 2 ) {
											esc_attr_e( 'woo-sctr-hidden-class' );
										} ?>"><?php esc_html_e( 'The countdown timer will not show if message does not include {countdown_timer}', 'sales-countdown-timer' ) ?></p>
                                    </div>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Time separator', 'sales-countdown-timer' ) ?></label>
                                            <select name="woo_ctr_time_separator[]"
                                                    class="woo-sctr-time-separator vi-ui dropdown">
                                                <option value="blank" <?php selected( $this->settings->get_time_separator() [ $i ], 'blank' ); ?>><?php esc_html_e( 'Blank', 'sales-countdown-timer' ) ?></option>
                                                <option value="colon" <?php selected( $this->settings->get_time_separator() [ $i ], 'colon' ); ?>><?php esc_html_e( 'Colon(:)', 'sales-countdown-timer' ) ?></option>
                                                <option value="comma" <?php selected( $this->settings->get_time_separator()[ $i ], 'comma' ); ?>><?php esc_html_e( 'Comma(,)', 'sales-countdown-timer' ) ?></option>
                                                <option value="dot" <?php selected( $this->settings->get_time_separator()[ $i ], 'dot' ); ?>><?php esc_html_e( 'Dot(.)', 'sales-countdown-timer' ) ?></option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Datetime format style', 'sales-countdown-timer' ) ?></label>
                                            <select name="woo_ctr_count_style[]"
                                                    class="woo-sctr-count-style vi-ui dropdown">
                                                <option value="1" <?php selected( $this->settings->get_count_style()[ $i ], 1 ); ?>><?php esc_html_e( '01 days 02 hrs 03 mins 04 secs', 'sales-countdown-timer' ) ?></option>
                                                <option value="2" <?php selected( $this->settings->get_count_style()[ $i ], 2 ); ?>><?php esc_html_e( '01 days 02 hours 03 minutes 04 seconds', 'sales-countdown-timer' ) ?></option>
                                                <option value="3" <?php selected( $this->settings->get_count_style()[ $i ], 3 ); ?>><?php esc_html_e( '01:02:03:04', 'sales-countdown-timer' ) ?></option>
                                                <option value="4" <?php selected( $this->settings->get_count_style()[ $i ], 4 ); ?>><?php esc_html_e( '01d:02h:03m:04s', 'sales-countdown-timer' ) ?></option>
                                            </select>
                                        </div>
                                    </div>
									<?php
									$datetime_unit_position = isset( $this->settings->get_datetime_unit_position() [ $i ] ) ? $this->settings->get_datetime_unit_position() [ $i ] : 'bottom';
									$animation_style        = isset( $this->settings->get_animation_style()[ $i ] ) ? $this->settings->get_animation_style()[ $i ] : 'default';
									?>
                                    <div class="equal width fields">
                                        <div class="field">
                                            <label><?php esc_html_e( 'Datetime unit position', 'sales-countdown-timer' ) ?></label>
                                            <select name="woo_ctr_datetime_unit_position[]"
                                                    class="woo-sctr-datetime-unit-position vi-ui dropdown">
                                                <option value="top" <?php selected( $datetime_unit_position, 'top' ); ?>><?php esc_html_e( 'Top', 'sales-countdown-timer' ) ?></option>
                                                <option value="bottom" <?php selected( $datetime_unit_position, 'bottom' ); ?>><?php esc_html_e( 'Bottom', 'sales-countdown-timer' ) ?></option>
                                            </select>
                                        </div>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Animation style', 'sales-countdown-timer' ) ?></label>
                                            <select name="woo_ctr_animation_style[]"
                                                    class="woo-sctr-animation-style vi-ui dropdown">
                                                <option value="default" <?php selected( $animation_style, 'default' ); ?>><?php esc_html_e( 'Default', 'sales-countdown-timer' ) ?></option>
                                                <option value="slide" <?php selected( $animation_style, 'slide' ); ?>><?php esc_html_e( 'Slide', 'sales-countdown-timer' ) ?></option>
                                            </select>
                                        </div>
                                    </div>
									<?php
									switch ( $this->settings->get_count_style()[ $i ] ) {
										case '1':
											$date   = esc_html__( 'days', 'sales-countdown-timer' );
											$hour   = esc_html__( 'hrs', 'sales-countdown-timer' );
											$minute = esc_html__( 'mins', 'sales-countdown-timer' );
											$second = esc_html__( 'secs', 'sales-countdown-timer' );
											break;
										case '2':
											$date   = esc_html__( 'days', 'sales-countdown-timer' );
											$hour   = esc_html__( 'hours', 'sales-countdown-timer' );
											$minute = esc_html__( 'minutes', 'sales-countdown-timer' );
											$second = esc_html__( 'seconds', 'sales-countdown-timer' );
											break;
										case '3':
											$date   = esc_html__( '', 'sales-countdown-timer' );
											$hour   = esc_html__( '', 'sales-countdown-timer' );
											$minute = esc_html__( '', 'sales-countdown-timer' );
											$second = esc_html__( '', 'sales-countdown-timer' );
											break;
										default:
											$date   = esc_html__( 'd', 'sales-countdown-timer' );
											$hour   = esc_html__( 'h', 'sales-countdown-timer' );
											$minute = esc_html__( 'm', 'sales-countdown-timer' );
											$second = esc_html__( 's', 'sales-countdown-timer' );
									}

									?>
                                    <div class="field">
                                        <h4 class="vi-ui dividing header">
                                            <label><?php esc_html_e( 'Display type', 'sales-countdown-timer' ) ?></label>
                                        </h4>
                                        <input type="hidden"
                                               name="woo_ctr_display_type[]"
                                               class="woo-sctr-display-type"
                                               value="<?php echo esc_attr( $this->settings->get_display_type()[ $i ] ); ?>">

                                        <div class="two fields">

                                            <div class="field">
                                                <div class="vi-ui segment">
                                                    <div class="fields">
                                                        <div class="three wide field">
                                                            <div class="vi-ui toggle checkbox">

                                                                <input type="radio"
                                                                       name="woo_ctr_display_type_<?php echo esc_attr( $i ); ?>"
                                                                       class="woo-sctr-display-type-checkbox"
                                                                       value="1" <?php checked( $this->settings->get_display_type()[ $i ], '1' ) ?>><label></label>
                                                            </div>
                                                        </div>
                                                        <div class="thirteen wide field">
                                                            <div class="woo-sctr-shortcode-wrap-wrap">
                                                                <div class="woo-sctr-shortcode-wrap">

                                                                    <div class="woo-sctr-shortcode-countdown-wrap woo-sctr-shortcode-countdown-style-1">
                                                                        <div class="woo-sctr-shortcode-countdown">
                                                                            <div class="woo-sctr-shortcode-countdown-1">
                                                                                <span class="woo-sctr-shortcode-countdown-text-before"><?php echo esc_html( $text_before ); ?></span>
                                                                                <div class="woo-sctr-shortcode-countdown-2">
                                                                                    <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                                        <span class="woo-sctr-shortcode-countdown-date woo-sctr-shortcode-countdown-unit">
                                                                                            <span class="woo-sctr-shortcode-countdown-date-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-top" <?php echo $datetime_unit_position == 'top' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $date ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-date-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '01', 'sales-countdown-timer' ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-date-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-bottom" <?php echo $datetime_unit_position == 'bottom' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $date ); ?></span>
                                                                                        </span>
                                                                                    </span>
                                                                                    <span class="woo-sctr-shortcode-countdown-time-separator"><?php echo esc_html( $time_separator ); ?></span>
                                                                                    <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                                        <span class="woo-sctr-shortcode-countdown-hour woo-sctr-shortcode-countdown-unit">
                                                                                            <span class="woo-sctr-shortcode-countdown-hour-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-top" <?php echo $datetime_unit_position == 'top' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $hour ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-hour-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '02', 'sales-countdown-timer' ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-hour-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-bottom" <?php echo $datetime_unit_position == 'bottom' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $hour ); ?></span>
                                                                                        </span>
                                                                                    </span>
                                                                                    <span class="woo-sctr-shortcode-countdown-time-separator"><?php echo esc_html( $time_separator ); ?></span>
                                                                                    <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                                        <span class="woo-sctr-shortcode-countdown-minute woo-sctr-shortcode-countdown-unit">
                                                                                            <span class="woo-sctr-shortcode-countdown-minute-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-top" <?php echo $datetime_unit_position == 'top' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $minute ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-minute-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '03', 'sales-countdown-timer' ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-minute-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-bottom" <?php echo $datetime_unit_position == 'bottom' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $minute ); ?></span>
                                                                                        </span>
                                                                                    </span>
                                                                                    <span class="woo-sctr-shortcode-countdown-time-separator"><?php echo esc_html( $time_separator ); ?></span>
                                                                                    <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                                        <span class="woo-sctr-shortcode-countdown-second woo-sctr-shortcode-countdown-unit">
                                                                                            <span class="woo-sctr-shortcode-countdown-second-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-top" <?php echo $datetime_unit_position == 'top' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $second ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-second-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '04', 'sales-countdown-timer' ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-second-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-bottom" <?php echo $datetime_unit_position == 'bottom' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $second ); ?></span>
                                                                                        </span>
                                                                                    </span>
                                                                                </div>
                                                                                <span class="woo-sctr-shortcode-countdown-text-after"><?php echo wp_kses_post( $text_after ); ?></span>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <div class="vi-ui segment">
                                                    <div class="fields">
                                                        <div class="three wide field">
                                                            <div class="vi-ui toggle checkbox">

                                                                <input type="radio"
                                                                       name="woo_ctr_display_type_<?php echo esc_attr( $i ); ?>"
                                                                       class="woo-sctr-display-type-checkbox"
                                                                       value="2" <?php checked( $this->settings->get_display_type()[ $i ], '2' ) ?>><label></label>
                                                            </div>
                                                        </div>
                                                        <div class="thirteen wide field">
                                                            <div class="woo-sctr-shortcode-wrap-wrap">
                                                                <div class="woo-sctr-shortcode-wrap">

                                                                    <div class="woo-sctr-shortcode-countdown-wrap woo-sctr-shortcode-countdown-style-2">
                                                                        <div class="woo-sctr-shortcode-countdown">
                                                                            <div class="woo-sctr-shortcode-countdown-1">
                                                                                <span class="woo-sctr-shortcode-countdown-text-before"><?php echo wp_kses_post( $text_before ); ?></span>
                                                                                <div class="woo-sctr-shortcode-countdown-2">
                                                                                    <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                                        <span class="woo-sctr-shortcode-countdown-date woo-sctr-shortcode-countdown-unit">
                                                                                            <span class="woo-sctr-shortcode-countdown-date-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-top" <?php echo $datetime_unit_position == 'top' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $date ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-date-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '01', 'sales-countdown-timer' ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-date-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-bottom" <?php echo $datetime_unit_position == 'bottom' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $date ); ?></span>
                                                                                        </span>
                                                                                    </span>
                                                                                    <span class="woo-sctr-shortcode-countdown-time-separator"><?php echo esc_attr( $time_separator ); ?></span>
                                                                                    <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                                        <span class="woo-sctr-shortcode-countdown-hour woo-sctr-shortcode-countdown-unit">
                                                                                            <span class="woo-sctr-shortcode-countdown-hour-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-top" <?php echo $datetime_unit_position == 'top' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $hour ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-hour-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '02', 'sales-countdown-timer' ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-hour-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-bottom" <?php echo $datetime_unit_position == 'bottom' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $hour ); ?></span>
                                                                                        </span>
                                                                                    </span>
                                                                                    <span class="woo-sctr-shortcode-countdown-time-separator"><?php echo esc_attr( $time_separator ); ?></span>
                                                                                    <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                                        <span class="woo-sctr-shortcode-countdown-minute woo-sctr-shortcode-countdown-unit">
                                                                                            <span class="woo-sctr-shortcode-countdown-minute-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-top" <?php echo $datetime_unit_position == 'top' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $minute ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-minute-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '03', 'sales-countdown-timer' ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-minute-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-bottom" <?php echo $datetime_unit_position == 'bottom' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $minute ); ?></span>
                                                                                        </span>
                                                                                    </span>
                                                                                    <span class="woo-sctr-shortcode-countdown-time-separator"><?php echo esc_attr( $time_separator ); ?></span>
                                                                                    <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                                        <span class="woo-sctr-shortcode-countdown-second woo-sctr-shortcode-countdown-unit">
                                                                                            <span class="woo-sctr-shortcode-countdown-second-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-top" <?php echo $datetime_unit_position == 'top' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $second ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-second-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '04', 'sales-countdown-timer' ); ?></span>
                                                                                            <span class="woo-sctr-shortcode-countdown-second-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-bottom" <?php echo $datetime_unit_position == 'bottom' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $second ); ?></span>
                                                                                        </span>
                                                                                    </span>
                                                                                </div>
                                                                                <span class="woo-sctr-shortcode-countdown-text-after"><?php echo wp_kses_post( $text_after ); ?></span>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="field">

                                            <div class="vi-ui segment">
                                                <div class="fields">
                                                    <div class="three wide field">
                                                        <div class="vi-ui toggle checkbox">

                                                            <input type="radio"
                                                                   name="woo_ctr_display_type_<?php echo esc_attr( $i ); ?>"
                                                                   class="woo-sctr-display-type-checkbox"
                                                                   value="3" <?php checked( $this->settings->get_display_type()[ $i ], '3' ) ?>><label></label>
                                                        </div>
                                                    </div>
                                                    <div class="ten wide field">
                                                        <div class="woo-sctr-shortcode-wrap-wrap woo-sctr-shortcode-wrap-wrap-inline">

                                                            <span class="woo-sctr-shortcode-countdown-text-before"><?php echo wp_kses_post( $text_before ); ?></span>
                                                            <span class="woo-sctr-shortcode-countdown-1">
                                                                <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                    <span class="woo-sctr-shortcode-countdown-date woo-sctr-shortcode-countdown-unit">
                                                                        <span class="woo-sctr-shortcode-countdown-date-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '01', 'sales-countdown-timer' ); ?></span>
                                                                        <span class="woo-sctr-shortcode-countdown-date-text woo-sctr-shortcode-countdown-text"><?php echo esc_attr( $date ); ?></span>
                                                                    </span>
                                                                </span>
                                                                <span class="woo-sctr-shortcode-countdown-time-separator"><?php echo esc_attr( $time_separator ); ?></span>
                                                                <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                    <span class="woo-sctr-shortcode-countdown-hour woo-sctr-shortcode-countdown-unit">
                                                                        <span class="woo-sctr-shortcode-countdown-hour-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '02', 'sales-countdown-timer' ); ?></span>
                                                                        <span class="woo-sctr-shortcode-countdown-hour-text woo-sctr-shortcode-countdown-text"><?php echo esc_attr( $hour ); ?></span>
                                                                    </span>
                                                                </span>
                                                                <span class="woo-sctr-shortcode-countdown-time-separator"><?php echo esc_attr( $time_separator ); ?></span>
                                                                <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                    <span class="woo-sctr-shortcode-countdown-minute woo-sctr-shortcode-countdown-unit">
                                                                        <span class="woo-sctr-shortcode-countdown-minute-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '03', 'sales-countdown-timer' ); ?></span>
                                                                        <span class="woo-sctr-shortcode-countdown-minute-text woo-sctr-shortcode-countdown-text"><?php echo esc_attr( $minute ); ?></span>
                                                                    </span>
                                                                </span>
                                                                <span class="woo-sctr-shortcode-countdown-time-separator"><?php echo esc_attr( $time_separator ); ?></span>
                                                                <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                    <span class="woo-sctr-shortcode-countdown-second woo-sctr-shortcode-countdown-unit">
                                                                        <span class="woo-sctr-shortcode-countdown-second-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '04', 'sales-countdown-timer' ); ?></span>
                                                                        <span class="woo-sctr-shortcode-countdown-second-text woo-sctr-shortcode-countdown-text"><?php echo esc_attr( $second ); ?></span>
                                                                    </span>
                                                                </span>
                                                            </span>
                                                            <span class="woo-sctr-shortcode-countdown-text-after"><?php echo wp_kses_post( $text_after ); ?></span>
                                                        </div>
                                                    </div>
                                                    <div class="three wide field">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="field">

                                            <div class="vi-ui segment">
                                                <div class="fields">
                                                    <div class="three wide field">
                                                        <div class="vi-ui toggle checkbox">

                                                            <input type="radio"
                                                                   name="woo_ctr_display_type_<?php echo esc_attr( $i ); ?>"
                                                                   class="woo-sctr-display-type-checkbox"
                                                                   value="4" <?php checked( $this->settings->get_display_type()[ $i ], '4' ) ?>><label></label>
                                                        </div>
                                                    </div>
                                                    <div class="ten wide field">
                                                        <div class="woo-sctr-shortcode-wrap-wrap">
                                                            <div class="woo-sctr-shortcode-wrap">

                                                                <div class="woo-sctr-shortcode-countdown-wrap woo-sctr-shortcode-countdown-style-4">
                                                                    <div class="woo-sctr-shortcode-countdown">
                                                                        <div class="woo-sctr-shortcode-countdown-1">
                                                                            <span class="woo-sctr-shortcode-countdown-text-before"><?php echo wp_kses_post( $text_before ); ?></span>
                                                                            <div class="woo-sctr-shortcode-countdown-2">
                                                                                <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                                    <span class="woo-sctr-shortcode-countdown-date woo-sctr-shortcode-countdown-unit">
                                                                                        <span class="woo-sctr-shortcode-countdown-date-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-top" <?php echo $datetime_unit_position == 'top' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $date ); ?></span>
                                                                                        <div class="woo-sctr-progress-circle">
                                                                                            <span class="woo-sctr-shortcode-countdown-date-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '10', 'sales-countdown-timer' ); ?></span>
                                                                                            <div class="woo-sctr-left-half-clipper">
                                                                                                <div class="woo-sctr-first50-bar"></div>
                                                                                                <div class="woo-sctr-value-bar"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <span class="woo-sctr-shortcode-countdown-date-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-bottom" <?php echo $datetime_unit_position == 'bottom' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $date ); ?></span>
                                                                                    </span>
                                                                                </span>
                                                                                <span class="woo-sctr-shortcode-countdown-time-separator"><?php echo esc_attr( $time_separator ); ?></span>
                                                                                <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                                    <span class="woo-sctr-shortcode-countdown-hour woo-sctr-shortcode-countdown-unit">
                                                                                        <span class="woo-sctr-shortcode-countdown-hour-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-top" <?php echo $datetime_unit_position == 'top' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $hour ); ?></span>
                                                                                        <div class="woo-sctr-progress-circle">
                                                                                            <span class="woo-sctr-shortcode-countdown-hour-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '02', 'sales-countdown-timer' ); ?></span>
                                                                                            <div class="woo-sctr-left-half-clipper">
                                                                                                <div class="woo-sctr-first50-bar"></div>
                                                                                                <div class="woo-sctr-value-bar"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <span class="woo-sctr-shortcode-countdown-hour-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-bottom" <?php echo $datetime_unit_position == 'bottom' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $hour ); ?></span>
                                                                                    </span>
                                                                                </span>
                                                                                <span class="woo-sctr-shortcode-countdown-time-separator"><?php echo esc_attr( $time_separator ); ?></span>
                                                                                <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                                    <span class="woo-sctr-shortcode-countdown-minute woo-sctr-shortcode-countdown-unit">
                                                                                        <span class="woo-sctr-shortcode-countdown-minute-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-top" <?php echo $datetime_unit_position == 'top' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $minute ); ?></span>
                                                                                        <div class="woo-sctr-progress-circle">
                                                                                            <span class="woo-sctr-shortcode-countdown-minute-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '30', 'sales-countdown-timer' ); ?></span>
                                                                                            <div class="woo-sctr-left-half-clipper">
                                                                                                <div class="woo-sctr-first50-bar"></div>
                                                                                                <div class="woo-sctr-value-bar"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <span class="woo-sctr-shortcode-countdown-minute-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-bottom" <?php echo $datetime_unit_position == 'bottom' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $minute ); ?></span>
                                                                                    </span>
                                                                                </span>
                                                                                <span class="woo-sctr-shortcode-countdown-time-separator"><?php echo esc_attr( $time_separator ); ?></span>
                                                                                <span class="woo-sctr-shortcode-countdown-unit-wrap">
                                                                                    <span class="woo-sctr-shortcode-countdown-second woo-sctr-shortcode-countdown-unit">
                                                                                        <span class="woo-sctr-shortcode-countdown-second-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-top" <?php echo $datetime_unit_position == 'top' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $second ); ?></span>
                                                                                        <div class="woo-sctr-progress-circle woo-sctr-over50">
                                                                                            <span class="woo-sctr-shortcode-countdown-second-value woo-sctr-shortcode-countdown-value"><?php esc_html_e( '40', 'sales-countdown-timer' ); ?></span>
                                                                                            <div class="woo-sctr-left-half-clipper">
                                                                                                <div class="woo-sctr-first50-bar"></div>
                                                                                                <div class="woo-sctr-value-bar"></div>
                                                                                            </div>
                                                                                        </div>
                                                                                        <span class="woo-sctr-shortcode-countdown-second-text woo-sctr-shortcode-countdown-text woo-sctr-datetime-unit-position-bottom" <?php echo $datetime_unit_position == 'bottom' ? '' : 'style="display:none;"'; ?>><?php echo esc_html( $second ); ?></span>
                                                                                    </span>
                                                                                </span>
                                                                            </div>
                                                                            <span class="woo-sctr-shortcode-countdown-text-after"><?php echo wp_kses_post( $text_after ); ?></span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="three wide field">
                                                    </div>
                                                </div>
                                                <div class="vi-ui toggle checkbox">
													<?php
													$smooth_animation = isset( $this->settings->get_circle_smooth_animation()[ $i ] ) ? $this->settings->get_circle_smooth_animation()[ $i ] : '';
													?>
                                                    <input type="hidden" name="woo_ctr_circle_smooth_animation[]"
                                                           class="woo-sctr-circle-smooth-animation"
                                                           value="<?php echo esc_attr( $smooth_animation ); ?>">
                                                    <input type="checkbox"
                                                           class="woo-sctr-circle-smooth-animation-check"
                                                           value="1" <?php checked( $smooth_animation, '1' ) ?>><label><?php esc_html_e( 'Use smooth animation for circle', 'sales-countdown-timer' ) ?></label>
                                                </div>
                                                <p><?php esc_html_e( '(*)Countdown timer items Border radius, Height and Width are not applied to this type.', 'sales-countdown-timer' ) ?></p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <h4 class="vi-ui dividing header">
                                            <label><?php esc_html_e( 'Countdown timer', 'sales-countdown-timer' ) ?></label>
                                        </h4>
                                        <div class="three fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                <input type="text"
                                                       class="color-picker woo-sctr-countdown-timer-color"
                                                       name="woo_ctr_countdown_timer_color[]"
                                                       value="<?php echo esc_attr( $this->settings->get_countdown_timer_color()[ $i ] ) ?>"
                                                       style="background:<?php echo esc_attr( $this->settings->get_countdown_timer_color()[ $i ] ) ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                <input type="text"
                                                       class="color-picker woo-sctr-countdown-timer-bg-color"
                                                       name="woo_ctr_countdown_timer_bg_color[]"
                                                       value="<?php echo esc_attr( $this->settings->get_countdown_timer_bg_color()[ $i ] ) ?>"
                                                       style="background:<?php echo esc_attr( $this->settings->get_countdown_timer_bg_color()[ $i ] ) ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Border color', 'sales-countdown-timer' ) ?></label>
                                                <input type="text"
                                                       class="color-picker woo-sctr-countdown-timer-border-color"
                                                       name="woo_ctr_countdown_timer_border_color[]"
                                                       value="<?php echo esc_attr( $this->settings->get_countdown_timer_border_color()[ $i ] ) ?>"
                                                       style="background:<?php echo esc_attr( $this->settings->get_countdown_timer_border_color()[ $i ] ) ?>">
                                            </div>

                                            <div class="field">
                                                <label><?php esc_html_e( 'Padding(px)', 'sales-countdown-timer' ) ?></label>
                                                <input type="number"
                                                       class="woo-sctr-countdown-timer-padding"
                                                       name="woo_ctr_countdown_timer_padding[]"
                                                       min="0"
                                                       value="<?php echo esc_attr( $this->settings->get_countdown_timer_padding()[ $i ] ) ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Border radius', 'sales-countdown-timer' ) ?></label>
                                                <input type="number"
                                                       class="woo-sctr-countdown-timer-border-radius"
                                                       name="woo_ctr_countdown_timer_border_radius[]"
                                                       min="0"
                                                       value="<?php echo esc_attr( $this->settings->get_countdown_timer_border_radius()[ $i ] ) ?>">
                                            </div>

                                        </div>
                                    </div>
                                    <div class="field">
                                        <h4 class="vi-ui dividing header">
                                            <label><?php esc_html_e( 'Countdown timer items', 'sales-countdown-timer' ) ?></label>
                                        </h4>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Hide zero items', 'sales-countdown-timer' ) ?></label>
                                            <div class="vi-ui toggle checkbox">
                                                <input type="hidden" name="woo_ctr_countdown_timer_hide_zero[]"
                                                       class="woo-sctr-countdown-timer-hide-zero"
                                                       value="<?php echo esc_attr( $this->settings->get_countdown_timer_hide_zero()[ $i ] ); ?>">
                                                <input type="checkbox"
                                                       class="woo-sctr-countdown-timer-hide-zero-check" <?php echo $this->settings->get_countdown_timer_hide_zero()[ $i ] ? 'checked' : ''; ?>><label></label>
                                            </div>
                                        </div>
                                        <div class="four fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Border color', 'sales-countdown-timer' ) ?></label>
                                                <input type="text"
                                                       class="color-picker woo-sctr-countdown-timer-item-border-color"
                                                       name="woo_ctr_countdown_timer_item_border_color[]"
                                                       value="<?php echo esc_attr( $this->settings->get_countdown_timer_item_border_color()[ $i ] ) ?>"
                                                       style="background:<?php echo esc_attr( $this->settings->get_countdown_timer_item_border_color()[ $i ] ) ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Border radius(px)', 'sales-countdown-timer' ) ?></label>
                                                <input type="number"
                                                       class="woo-sctr-countdown-timer-item-border-radius"
                                                       name="woo_ctr_countdown_timer_item_border_radius[]"
                                                       min="0"
                                                       value="<?php echo esc_attr( $this->settings->get_countdown_timer_item_border_radius()[ $i ] ) ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Height(px)', 'sales-countdown-timer' ) ?></label>
                                                <input type="number"
                                                       class="woo-sctr-countdown-timer-item-height"
                                                       name="woo_ctr_countdown_timer_item_height[]"
                                                       min="0"
                                                       value="<?php echo esc_attr( $this->settings->get_countdown_timer_item_height()[ $i ] ) ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Width(px)', 'sales-countdown-timer' ) ?></label>
                                                <input type="number"
                                                       class="woo-sctr-countdown-timer-item-width"
                                                       name="woo_ctr_countdown_timer_item_width[]"
                                                       min="0"
                                                       value="<?php echo esc_attr( $this->settings->get_countdown_timer_item_width()[ $i ] ) ?>">
                                            </div>


                                        </div>
                                    </div>

                                    <div class="field">
                                        <h4 class="vi-ui dividing header">
                                            <label><?php esc_html_e( 'Datetime value', 'sales-countdown-timer' ) ?></label>
                                        </h4>
                                        <div class="equal width fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                <input type="text"
                                                       class="color-picker woo-sctr-datetime-value-color"
                                                       name="woo_ctr_datetime_value_color[]"
                                                       value="<?php echo esc_attr( $this->settings->get_datetime_value_color()[ $i ] ) ?>"
                                                       style="background:<?php echo esc_attr( $this->settings->get_datetime_value_color()[ $i ] ) ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                <input type="text"
                                                       class="color-picker woo-sctr-datetime-value-bg-color"
                                                       name="woo_ctr_datetime_value_bg_color[]"
                                                       value="<?php echo esc_attr( $this->settings->get_datetime_value_bg_color()[ $i ] ) ?>"
                                                       style="background:<?php echo esc_attr( $this->settings->get_datetime_value_bg_color()[ $i ] ) ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Font size(px)', 'sales-countdown-timer' ) ?></label>
                                                <input type="number"
                                                       class="woo-sctr-datetime-value-font-size"
                                                       name="woo_ctr_datetime_value_font_size[]"
                                                       min="0"
                                                       value="<?php echo esc_attr( $this->settings->get_datetime_value_font_size()[ $i ] ) ?>">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="field">
                                        <h4 class="vi-ui dividing header">
                                            <label><?php esc_html_e( 'Datetime unit', 'sales-countdown-timer' ) ?></label>
                                        </h4>
                                        <div class=" equal width fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                <input type="text"
                                                       class="color-picker woo-sctr-datetime-unit-color"
                                                       name="woo_ctr_datetime_unit_color[]"
                                                       value="<?php echo esc_attr( $this->settings->get_datetime_unit_color()[ $i ] ) ?>"
                                                       style="background:<?php echo esc_attr( $this->settings->get_datetime_unit_color()[ $i ] ) ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                <input type="text"
                                                       class="color-picker woo-sctr-datetime-unit-bg-color"
                                                       name="woo_ctr_datetime_unit_bg_color[]"
                                                       value="<?php echo esc_attr( $this->settings->get_datetime_unit_bg_color()[ $i ] ) ?>"
                                                       style="background:<?php echo esc_attr( $this->settings->get_datetime_unit_bg_color()[ $i ] ) ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Font size(px)', 'sales-countdown-timer' ) ?></label>
                                                <input type="number"
                                                       class="woo-sctr-datetime-unit-font-size"
                                                       name="woo_ctr_datetime_unit_font_size[]"
                                                       min="0"
                                                       value="<?php echo esc_attr( $this->settings->get_datetime_unit_font_size()[ $i ] ) ?>">
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="title">
                                    <i class="dropdown icon"></i>
									<?php esc_html_e( 'WooCommerce Product', 'sales-countdown-timer' ) ?>
                                </div>
                                <div class="content">
                                    <div class="field">

                                        <div class="equal width fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Make countdown timer sticky when scroll', 'sales-countdown-timer' ) ?></label>
                                                <div class="vi-ui toggle checkbox">
                                                    <input type="hidden" name="woo_ctr_stick_to_top[]"
                                                           class="woo-sctr-stick-to-top"
                                                           value="<?php echo isset( $this->settings->get_stick_to_top()[ $i ] ) ? esc_attr( $this->settings->get_stick_to_top()[ $i ] ) : ''; ?>">
                                                    <input type="checkbox"
                                                           class="woo-sctr-stick-to-top-check" <?php echo ( isset( $this->settings->get_stick_to_top()[ $i ] ) && $this->settings->get_stick_to_top()[ $i ] ) ? 'checked' : ''; ?>><label></label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Position on single product page', 'sales-countdown-timer' ) ?></label>
                                                <div class="vi-ui input"
                                                     data-tooltip="<?php esc_attr_e( 'Position of countdown timer of main product on single product page(Can not set position for variations)', 'sales-countdown-timer' ) ?>">
                                                    <select name="woo_ctr_position[]"
                                                            class="woo-sctr-position vi-ui fluid dropdown">
                                                        <option value="before_price" <?php selected( $this->settings->get_position()[ $i ], 'before_price' ); ?>><?php esc_html_e( 'Before price', 'sales-countdown-timer' ) ?></option>
                                                        <option value="after_price" <?php selected( $this->settings->get_position()[ $i ], 'after_price' ); ?>><?php esc_html_e( 'After price', 'sales-countdown-timer' ) ?></option>
                                                        <option value="before_saleflash" <?php selected( $this->settings->get_position()[ $i ], 'before_saleflash' ); ?>><?php esc_html_e( 'Before sale flash', 'sales-countdown-timer' ) ?></option>
                                                        <option value="after_saleflash" <?php selected( $this->settings->get_position()[ $i ], 'after_saleflash' ); ?>><?php esc_html_e( 'After sale flash', 'sales-countdown-timer' ) ?></option>
                                                        <option value="before_cart" <?php selected( $this->settings->get_position()[ $i ], 'before_cart' ); ?>><?php esc_html_e( 'Before cart', 'sales-countdown-timer' ) ?></option>
                                                        <option value="after_cart" <?php selected( $this->settings->get_position()[ $i ], 'after_cart' ); ?>><?php esc_html_e( 'After cart', 'sales-countdown-timer' ) ?></option>
                                                        <option value="product_image" <?php selected( $this->settings->get_position()[ $i ], 'product_image' ); ?>><?php esc_html_e( 'Product image', 'sales-countdown-timer' ) ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Position on archive page', 'sales-countdown-timer' ) ?></label>
                                                <div class="vi-ui input"
                                                     data-tooltip="<?php esc_attr_e( 'Position of countdown timer on shop page, category page and related products', 'sales-countdown-timer' ) ?>">
                                                    <select name="woo_ctr_archive_page_position[]"
                                                            class="woo-sctr-archive-page-position vi-ui fluid dropdown">
                                                        <option value="before_price" <?php selected( $this->settings->get_archive_page_position()[ $i ], 'before_price' ); ?>><?php esc_html_e( 'Before price', 'sales-countdown-timer' ) ?></option>
                                                        <option value="after_price" <?php selected( $this->settings->get_archive_page_position()[ $i ], 'after_price' ); ?>><?php esc_html_e( 'After price', 'sales-countdown-timer' ) ?></option>
                                                        <option value="before_saleflash" <?php selected( $this->settings->get_archive_page_position()[ $i ], 'before_saleflash' ); ?>><?php esc_html_e( 'Before sale flash', 'sales-countdown-timer' ) ?></option>
                                                        <option value="after_saleflash" <?php selected( $this->settings->get_archive_page_position()[ $i ], 'after_saleflash' ); ?>><?php esc_html_e( 'After sale flash', 'sales-countdown-timer' ) ?></option>
                                                        <option value="before_cart" <?php selected( $this->settings->get_archive_page_position()[ $i ], 'before_cart' ); ?>><?php esc_html_e( 'Before cart', 'sales-countdown-timer' ) ?></option>
                                                        <option value="after_cart" <?php selected( $this->settings->get_archive_page_position()[ $i ], 'after_cart' ); ?>><?php esc_html_e( 'After cart', 'sales-countdown-timer' ) ?></option>
                                                        <option value="product_image" <?php selected( $this->settings->get_archive_page_position()[ $i ], 'product_image' ); ?>><?php esc_html_e( 'Product image', 'sales-countdown-timer' ) ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="equal width fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Show on shop page', 'sales-countdown-timer' ) ?></label>
                                                <div class="vi-ui toggle checkbox">
                                                    <input type="hidden" name="woo_ctr_shop_page[]"
                                                           class="woo-sctr-shop-page"
                                                           value="<?php echo esc_attr( $this->settings->get_shop_page()[ $i ] ); ?>">
                                                    <input type="checkbox"
                                                           class="woo-sctr-shop-page-check" <?php echo $this->settings->get_shop_page()[ $i ] ? 'checked' : ''; ?>><label></label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Show on category page', 'sales-countdown-timer' ) ?></label>
                                                <div class="vi-ui toggle checkbox">
                                                    <input type="hidden" name="woo_ctr_category_page[]"
                                                           class="woo-sctr-category-page"
                                                           value="<?php echo esc_attr( $this->settings->get_category_page()[ $i ] ); ?>">
                                                    <input type="checkbox"
                                                           class="woo-sctr-category-page-check" <?php echo $this->settings->get_category_page()[ $i ] ? 'checked' : ''; ?>><label></label>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Reduce size of countdown timer on shop/category page and on mobile(single product) by', 'sales-countdown-timer' ) ?></label>
                                                <div class="inline field">
                                                    <input type="number" name="woo_ctr_size_on_archive_page[]" min="30"
                                                           max="100"
                                                           class="woo-sctr-related-products"
                                                           value="<?php echo isset( $this->settings->get_size_on_archive_page()[ $i ] ) ? esc_attr( $this->settings->get_size_on_archive_page()[ $i ] ) : '75'; ?>">%
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="field">
                                        <h4 class="vi-ui dividing header"><?php esc_html_e( 'Upcoming sale', 'sales-countdown-timer' ) ?></h4>
                                        <div class="fields">
                                            <div class="three wide field">
                                                <label><?php esc_html_e( 'Enable', 'sales-countdown-timer' ) ?></label>
                                                <div class="vi-ui toggle checkbox">
                                                    <input type="hidden" name="woo_ctr_upcoming[]"
                                                           class="woo-sctr-upcoming"
                                                           value="<?php echo esc_attr( $this->settings->get_upcoming()[ $i ] ); ?>">
                                                    <input type="checkbox"
                                                           class="woo-sctr-upcoming-check" <?php echo $this->settings->get_upcoming()[ $i ] ? 'checked' : ''; ?>><label></label>
                                                </div>
                                            </div>
                                            <div class="thirteen wide field">
                                                <label><?php esc_html_e( 'Upcoming sale message', 'sales-countdown-timer' ) ?></label>

                                                <input type="text" name="woo_ctr_upcoming_message[]"
                                                       class="woo-sctr-upcoming-message"
                                                       value="<?php echo esc_attr( $this->settings->get_upcoming_message()[ $i ] ); ?>">
                                                <p>{countdown_timer}
                                                    - <?php esc_html_e( 'The countdown timer that you set on tab design', 'sales-countdown-timer' ) ?></p>
                                            </div>
                                        </div>

                                    </div>
                                    <div class="field">
                                        <h4 class="vi-ui dividing header"><?php esc_html_e( 'Progress bar', 'sales-countdown-timer' ) ?></h4>
                                        <div class="field">
                                            <label><?php esc_html_e( 'Progress bar message', 'sales-countdown-timer' ) ?></label>
                                            <div class="vi-ui input">
                                                <input type="text" name="woo_ctr_progress_bar_message[]"
                                                       class="woo-sctr-progress-bar-message"
                                                       value="<?php echo esc_attr( $this->settings->get_progress_bar_message()[ $i ] ) ?>">
                                            </div>

                                        </div>
                                        <div class="field">
                                            <p>{quantity_left} - <?php esc_html_e( 'Number of products left' ) ?></p>
                                            <p>{quantity_sold} - <?php esc_html_e( 'Number of products sold' ) ?></p>
                                            <p>{percentage_left}
                                                - <?php esc_html_e( 'Percentage of products left' ) ?></p>
                                            <p>{percentage_sold}
                                                - <?php esc_html_e( 'Percentage of products sold' ) ?></p>
                                            <p>{goal}
                                                - <?php esc_html_e( 'The goal that you set on single product' ) ?></p>
                                        </div>
                                        <div class="equal width fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Progress bar type', 'sales-countdown-timer' ) ?></label>
                                                <div class="vi-ui input"
                                                     data-tooltip="<?php esc_attr_e( 'If select increase, the progress bar fill will increase each time the product is bought and vice versa', 'sales-countdown-timer' ) ?>">
                                                    <select name="woo_ctr_progress_bar_type[]"
                                                            class="woo-sctr-progress-bar-type vi-ui fluid dropdown">
                                                        <option value="increase" <?php selected( $this->settings->get_progress_bar_type()[ $i ], 'increase' ); ?>
                                                                data-tooltip="asdasd"><?php esc_html_e( 'Increase', 'sales-countdown-timer' ) ?></option>
                                                        <option value="decrease" <?php selected( $this->settings->get_progress_bar_type()[ $i ], 'decrease' ); ?>><?php esc_html_e( 'Decrease', 'sales-countdown-timer' ) ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Order status', 'sales-countdown-timer' ) ?></label>
                                                <input type="hidden" name="woo_ctr_progress_bar_order_status[]"
                                                       value="<?php $this->settings->get_progress_bar_order_status()[ $i ] ?>"
                                                       class="woo-sctr-progress-bar-order-status-hidden">
                                                <div class="vi-ui input"
                                                     data-tooltip="<?php esc_attr_e( 'When new order created, update the progress bar when order status are(leave blank to apply for all order status):', 'sales-countdown-timer' ) ?>">
                                                    <select multiple
                                                            class="woo-sctr-progress-bar-order-status vi-ui fluid dropdown">
                                                        <option value="wc-completed" <?php if ( in_array( 'wc-completed', explode( ',', $this->settings->get_progress_bar_order_status()[ $i ] ) ) ) {
															echo 'selected';
														} ?>><?php esc_html_e( 'Completed', 'sales-countdown-timer' ) ?></option>
                                                        <option value="wc-on-hold" <?php if ( in_array( 'wc-on-hold', explode( ',', $this->settings->get_progress_bar_order_status()[ $i ] ) ) ) {
															echo 'selected';
														} ?>><?php esc_html_e( 'On-hold', 'sales-countdown-timer' ) ?></option>
                                                        <option value="wc-pending" <?php if ( in_array( 'wc-pending', explode( ',', $this->settings->get_progress_bar_order_status()[ $i ] ) ) ) {
															echo 'selected';
														} ?>><?php esc_html_e( 'Pending', 'sales-countdown-timer' ) ?></option>
                                                        <option value="wc-processing" <?php if ( in_array( 'wc-processing', explode( ',', $this->settings->get_progress_bar_order_status()[ $i ] ) ) ) {
															echo 'selected';
														} ?>><?php esc_html_e( 'Processing', 'sales-countdown-timer' ) ?></option>
                                                        <option value="wc-failed" <?php if ( in_array( 'wc-failed', explode( ',', $this->settings->get_progress_bar_order_status()[ $i ] ) ) ) {
															echo 'selected';
														} ?>><?php esc_html_e( 'Failed', 'sales-countdown-timer' ) ?></option>
                                                        <option value="wc-refunded" <?php if ( in_array( 'wc-refunded', explode( ',', $this->settings->get_progress_bar_order_status()[ $i ] ) ) ) {
															echo 'selected';
														} ?>><?php esc_html_e( 'Refunded', 'sales-countdown-timer' ) ?></option>
                                                        <option value="wc-cancelled" <?php if ( in_array( 'wc-cancelled', explode( ',', $this->settings->get_progress_bar_order_status()[ $i ] ) ) ) {
															echo 'selected';
														} ?>><?php esc_html_e( 'Cancelled', 'sales-countdown-timer' ) ?></option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="equal width fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Position', 'sales-countdown-timer' ) ?></label>
                                                <select name="woo_ctr_progress_bar_position[]"
                                                        class="woo-sctr-progress-bar-position vi-ui dropdown">
                                                    <option value="above_countdown" <?php selected( $this->settings->get_progress_bar_position()[ $i ], 'above_countdown' ); ?>><?php esc_html_e( 'Above Countdown', 'sales-countdown-timer' ) ?></option>
                                                    <option value="below_countdown" <?php selected( $this->settings->get_progress_bar_position()[ $i ], 'below_countdown' ); ?>><?php esc_html_e( 'Below Countdown', 'sales-countdown-timer' ) ?></option>
                                                </select>
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Width(px)', 'sales-countdown-timer' ) ?></label>
                                                <input type="number" min="0"
                                                       name="woo_ctr_progress_bar_width[]"
                                                       class="woo-sctr-progress-bar-width"
                                                       value="<?php echo esc_attr( $this->settings->get_progress_bar_width()[ $i ] ); ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Height(px)', 'sales-countdown-timer' ) ?></label>
                                                <input type="number" min="0"
                                                       name="woo_ctr_progress_bar_height[]"
                                                       class="woo-sctr-progress-bar-height"
                                                       value="<?php echo esc_attr( $this->settings->get_progress_bar_height()[ $i ] ); ?>">
                                            </div>
                                        </div>
                                        <div class="three fields">
                                            <div class="field">
                                                <label><?php esc_html_e( 'Background', 'sales-countdown-timer' ) ?></label>
                                                <input type="text"
                                                       class="color-picker woo-sctr-progress-bar-color"
                                                       name="woo_ctr_progress_bar_bg_color[]"
                                                       value="<?php echo esc_attr( $this->settings->get_progress_bar_bg_color()[ $i ] ) ?>"
                                                       style="background:<?php echo esc_attr( $this->settings->get_progress_bar_bg_color()[ $i ] ) ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Color', 'sales-countdown-timer' ) ?></label>
                                                <input type="text"
                                                       class="color-picker woo-sctr-progress-bar-color"
                                                       name="woo_ctr_progress_bar_color[]"
                                                       value="<?php echo esc_attr( $this->settings->get_progress_bar_color()[ $i ] ) ?>"
                                                       style="background:<?php echo esc_attr( $this->settings->get_progress_bar_color()[ $i ] ) ?>">
                                            </div>
                                            <div class="field">
                                                <label><?php esc_html_e( 'Border radius(px)', 'sales-countdown-timer' ) ?></label>
                                                <input type="number" min="0"
                                                       name="woo_ctr_progress_bar_border_radius[]"
                                                       class="woo-sctr-progress-bar-border-radius"
                                                       value="<?php echo esc_attr( $this->settings->get_progress_bar_border_radius()[ $i ] ); ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

						<?php
					}
					?>
					<?php
				}
				?>
                <!--                <p><input type="submit" name="submit" class="vi-ui primary button"-->
                <!--                          value="-->
				<?php //esc_html_e( 'Save', 'sales-countdown-timer' ); ?><!--"></p>-->

                <p class="woo-sctr-button-save-container">
                    <span class="woo-sctr-save vi-ui primary button"><?php esc_html_e( 'Save', 'sales-countdown-timer' ); ?></span>
                </p>
            </form>
        </div>
        <div class="woo-sctr-save-sucessful-popup">
			<?php esc_html_e( 'Settings saved', 'sales-countdown-timer' ); ?>
        </div>
		<?php
		do_action( 'villatheme_support_sales-countdown-timer' );
	}

	public function save_settings() {
		$response = array(
			'status'  => 'failed',
			'message' => '',
		);
		if ( isset( $_POST['woo_ctr_nonce_field'] ) && wp_verify_nonce( $_POST['woo_ctr_nonce_field'], 'woo_ctr_settings_page_save' ) ) {
			$args = array(
				'id'                                 => isset( $_POST['woo_ctr_id'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_id'] ) : array(),
				'names'                              => isset( $_POST['woo_ctr_name'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_name'] ) : array(),
				'message'                            => isset( $_POST['woo_ctr_message'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_message'] ) : array(),
				'active'                             => isset( $_POST['woo_ctr_active'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_active'] ) : array(),
				'enable_single_product'              => isset( $_POST['woo_ctr_enable_single_product'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_enable_single_product'] ) : array(),
				'time_type'                          => 'fixed',
				'count_style'                        => isset( $_POST['woo_ctr_count_style'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_count_style'] ) : array(),
				'sale_from_date'                     => isset( $_POST['woo_ctr_sale_from_date'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_sale_from_date'] ) : array(),
				'sale_to_date'                       => isset( $_POST['woo_ctr_sale_to_date'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_sale_to_date'] ) : array(),
				'sale_from_time'                     => isset( $_POST['woo_ctr_sale_from_time'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_sale_from_time'] ) : array(),
				'sale_to_time'                       => isset( $_POST['woo_ctr_sale_to_time'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_sale_to_time'] ) : array(),
				'upcoming'                           => isset( $_POST['woo_ctr_upcoming'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_upcoming'] ) : array(),
				'upcoming_message'                   => isset( $_POST['woo_ctr_upcoming_message'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_upcoming_message'] ) : array(),
				'style'                              => isset( $_POST['woo_ctr_style'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_style'] ) : array(),
				'position'                           => isset( $_POST['woo_ctr_position'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_position'] ) : array(),
				'progress_bar'                       => isset( $_POST['woo_ctr_progress_bar'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_progress_bar'] ) : array(),
				'progress_bar_position'              => isset( $_POST['woo_ctr_progress_bar_position'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_progress_bar_position'] ) : array(),
				'progress_bar_message'               => isset( $_POST['woo_ctr_progress_bar_message'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_progress_bar_message'] ) : array(),
				'progress_bar_order_status'          => isset( $_POST['woo_ctr_progress_bar_order_status'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_progress_bar_order_status'] ) : array(),
				'progress_bar_type'                  => isset( $_POST['woo_ctr_progress_bar_type'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_progress_bar_type'] ) : array(),
				'progress_bar_width'                 => isset( $_POST['woo_ctr_progress_bar_width'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_progress_bar_width'] ) : array(),
				'progress_bar_height'                => isset( $_POST['woo_ctr_progress_bar_height'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_progress_bar_height'] ) : array(),
				'progress_bar_bg_color'              => isset( $_POST['woo_ctr_progress_bar_bg_color'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_progress_bar_bg_color'] ) : array(),
				'progress_bar_color'                 => isset( $_POST['woo_ctr_progress_bar_color'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_progress_bar_color'] ) : array(),
				'progress_bar_border_radius'         => isset( $_POST['woo_ctr_progress_bar_border_radius'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_progress_bar_border_radius'] ) : array(),
				'progress_bar_style'                 => isset( $_POST['woo_ctr_progress_bar_style'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_progress_bar_style'] ) : array(),
				'countdown_timer_hide_zero'          => isset( $_POST['woo_ctr_countdown_timer_hide_zero'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_countdown_timer_hide_zero'] ) : array(),
				'countdown_timer_color'              => isset( $_POST['woo_ctr_countdown_timer_color'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_countdown_timer_color'] ) : array(),
				'countdown_timer_bg_color'           => isset( $_POST['woo_ctr_countdown_timer_bg_color'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_countdown_timer_bg_color'] ) : array(),
				'countdown_timer_border_radius'      => isset( $_POST['woo_ctr_countdown_timer_border_radius'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_countdown_timer_border_radius'] ) : array(),
				'countdown_timer_border_color'       => isset( $_POST['woo_ctr_countdown_timer_border_color'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_countdown_timer_border_color'] ) : array(),
				'countdown_timer_item_border_radius' => isset( $_POST['woo_ctr_countdown_timer_item_border_radius'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_countdown_timer_item_border_radius'] ) : array(),
				'countdown_timer_item_border_color'  => isset( $_POST['woo_ctr_countdown_timer_item_border_color'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_countdown_timer_item_border_color'] ) : array(),
				'countdown_timer_item_height'        => isset( $_POST['woo_ctr_countdown_timer_item_height'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_countdown_timer_item_height'] ) : array(),
				'countdown_timer_item_width'         => isset( $_POST['woo_ctr_countdown_timer_item_width'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_countdown_timer_item_width'] ) : array(),
				'countdown_timer_padding'            => isset( $_POST['woo_ctr_countdown_timer_padding'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_countdown_timer_padding'] ) : array(),
				'datetime_unit_color'                => isset( $_POST['woo_ctr_datetime_unit_color'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_datetime_unit_color'] ) : array(),
				'datetime_unit_bg_color'             => isset( $_POST['woo_ctr_datetime_unit_bg_color'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_datetime_unit_bg_color'] ) : array(),
				'datetime_unit_font_size'            => isset( $_POST['woo_ctr_datetime_unit_font_size'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_datetime_unit_font_size'] ) : array(),
				'datetime_value_color'               => isset( $_POST['woo_ctr_datetime_value_color'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_datetime_value_color'] ) : array(),
				'datetime_value_bg_color'            => isset( $_POST['woo_ctr_datetime_value_bg_color'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_datetime_value_bg_color'] ) : array(),
				'datetime_value_font_size'           => isset( $_POST['woo_ctr_datetime_value_font_size'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_datetime_value_font_size'] ) : array(),
				'time_separator'                     => isset( $_POST['woo_ctr_time_separator'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_time_separator'] ) : array(),
				'display_type'                       => isset( $_POST['woo_ctr_display_type'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_display_type'] ) : array(),
				'shop_page'                          => isset( $_POST['woo_ctr_shop_page'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_shop_page'] ) : array(),
				'category_page'                      => isset( $_POST['woo_ctr_category_page'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_category_page'] ) : array(),
				'archive_page_position'              => isset( $_POST['woo_ctr_archive_page_position'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_archive_page_position'] ) : array(),
				'size_on_archive_page'               => isset( $_POST['woo_ctr_size_on_archive_page'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_size_on_archive_page'] ) : array(),
				'datetime_unit_position'             => isset( $_POST['woo_ctr_datetime_unit_position'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_datetime_unit_position'] ) : array(),
				'animation_style'                    => isset( $_POST['woo_ctr_animation_style'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_animation_style'] ) : array(),
				'circle_smooth_animation'            => isset( $_POST['woo_ctr_circle_smooth_animation'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_circle_smooth_animation'] ) : array(),
				'stick_to_top'                       => isset( $_POST['woo_ctr_stick_to_top'] ) ? array_map( 'sanitize_text_field', $_POST['woo_ctr_stick_to_top'] ) : array(),
			);

			if ( ! count( $args['names'] ) ) {
				$response['message'] = __( 'Can not remove all Countdown timer settings.', 'sales-countdown-timer' );
				wp_send_json( $response );
				die;
			} else {
				if ( count( $args['names'] ) != count( array_unique( $args['names'] ) ) ) {
					$response['message'] = __( 'Names are unique.', 'sales-countdown-timer' );
					wp_send_json( $response );
					die;
				}
				foreach ( $args['names'] as $key => $name ) {
					if ( ! $name ) {
						$response['message'] = __( 'Names can not be empty.', 'sales-countdown-timer' );
						wp_send_json( $response );
						die;
					}
				}
			}
			update_option( 'sales_countdown_timer_params', $args );
			$response['status']  = 'successful';
			$response['message'] = __( 'Data saved.', 'sales-countdown-timer' );
			wp_send_json( $response );
			die;
		}
	}

	/**
	 * Init Script in Admin
	 */
	public function admin_enqueue_scripts() {
		$page = isset( $_REQUEST['page'] ) ? sanitize_text_field( $_REQUEST['page'] ) : '';
		if ( $page === 'sales-countdown-timer-checkout' ) {
			global $wp_scripts;
			$scripts = $wp_scripts->registered;
			//			print_r($scripts);
			foreach ( $scripts as $k => $script ) {
				preg_match( '/^\/wp-/i', $script->src, $result );
				if ( count( array_filter( $result ) ) ) {
					preg_match( '/^(\/wp-content\/plugins|\/wp-content\/themes)/i', $script->src, $result1 );
					if ( count( array_filter( $result1 ) ) ) {
						wp_dequeue_script( $script->handle );
					}
				} else {
					if ( $script->handle != 'query-monitor' ) {
						wp_dequeue_script( $script->handle );
					}
				}
			}
			wp_enqueue_style( 'sales-countdown-timer-semantic-button', SALES_COUNTDOWN_TIMER_CSS . 'button.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-form', SALES_COUNTDOWN_TIMER_CSS . 'form.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-menu', SALES_COUNTDOWN_TIMER_CSS . 'menu.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-message', SALES_COUNTDOWN_TIMER_CSS . 'message.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-label', SALES_COUNTDOWN_TIMER_CSS . 'label.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-input', SALES_COUNTDOWN_TIMER_CSS . 'input.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-transition', SALES_COUNTDOWN_TIMER_CSS . 'transition.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-segment', SALES_COUNTDOWN_TIMER_CSS . 'segment.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-popup', SALES_COUNTDOWN_TIMER_CSS . 'popup.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-tab', SALES_COUNTDOWN_TIMER_CSS . 'tab.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-header', SALES_COUNTDOWN_TIMER_CSS . 'header.min.css' );

			wp_enqueue_style( 'sales-countdown-timer-admin-css', SALES_COUNTDOWN_TIMER_CSS . 'admin-checkout.css' );

			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_script( 'sales-countdown-timer-semantic-address', SALES_COUNTDOWN_TIMER_JS . 'address.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'sales-countdown-timer-semantic-form', SALES_COUNTDOWN_TIMER_JS . 'form.js', array( 'jquery' ) );
			wp_enqueue_script( 'sales-countdown-timer-semantic-tab', SALES_COUNTDOWN_TIMER_JS . 'tab.js', array( 'jquery' ) );
			wp_enqueue_script( 'sales-countdown-timer-semantic-transition', SALES_COUNTDOWN_TIMER_JS . 'transition.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'sales-countdown-timer-admin-js', SALES_COUNTDOWN_TIMER_JS . 'admin-checkout.js', array( 'jquery' ), SALES_COUNTDOWN_TIMER_VERSION );
		} elseif ( $page == 'sales-countdown-timer' ) {
			global $wp_scripts;
			if ( isset( $wp_scripts->registered['jquery-ui-accordion'] ) ) {
				unset( $wp_scripts->registered['jquery-ui-accordion'] );
				wp_dequeue_script( 'jquery-ui-accordion' );
			}
			if ( isset( $wp_scripts->registered['accordion'] ) ) {
				unset( $wp_scripts->registered['accordion'] );
				wp_dequeue_script( 'accordion' );
			}
			$scripts = $wp_scripts->registered;
			foreach ( $scripts as $k => $script ) {
				preg_match( '/^\/wp-/i', $script->src, $result );
				if ( count( array_filter( $result ) ) ) {
					preg_match( '/^(\/wp-content\/plugins|\/wp-content\/themes)/i', $script->src, $result1 );
					if ( count( array_filter( $result1 ) ) ) {
						wp_dequeue_script( $script->handle );
					}
				} else {
					if ( $script->handle != 'query-monitor' ) {
						wp_dequeue_script( $script->handle );
					}
				}
			}

			/*Stylesheet*/
			wp_enqueue_style( 'sales-countdown-timer-semantic-button', SALES_COUNTDOWN_TIMER_CSS . 'button.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-checkbox', SALES_COUNTDOWN_TIMER_CSS . 'checkbox.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-dropdown', SALES_COUNTDOWN_TIMER_CSS . 'dropdown.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-form', SALES_COUNTDOWN_TIMER_CSS . 'form.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-icon', SALES_COUNTDOWN_TIMER_CSS . 'icon.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-menu', SALES_COUNTDOWN_TIMER_CSS . 'menu.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-segment', SALES_COUNTDOWN_TIMER_CSS . 'segment.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-label', SALES_COUNTDOWN_TIMER_CSS . 'label.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-transition', SALES_COUNTDOWN_TIMER_CSS . 'transition.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-accordion', SALES_COUNTDOWN_TIMER_CSS . 'accordion.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-input', SALES_COUNTDOWN_TIMER_CSS . 'input.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-header', SALES_COUNTDOWN_TIMER_CSS . 'header.min.css' );
			wp_enqueue_style( 'sales-countdown-timer-semantic-popup', SALES_COUNTDOWN_TIMER_CSS . 'popup.min.css' );

			wp_enqueue_style( 'sales-countdown-timer-admin', SALES_COUNTDOWN_TIMER_CSS . 'sales-countdown-timer-admin.css', array(), SALES_COUNTDOWN_TIMER_VERSION );

			wp_enqueue_script( 'sales-countdown-timer-semantic-checkbox', SALES_COUNTDOWN_TIMER_JS . 'checkbox.js', array( 'jquery' ) );
			wp_enqueue_script( 'sales-countdown-timer-semantic-dropdown', SALES_COUNTDOWN_TIMER_JS . 'dropdown.js', array( 'jquery' ) );
			wp_enqueue_script( 'sales-countdown-timer-semantic-form', SALES_COUNTDOWN_TIMER_JS . 'form.js', array( 'jquery' ) );
			wp_enqueue_script( 'sales-countdown-timer-semantic-tab', SALES_COUNTDOWN_TIMER_JS . 'tab.js', array( 'jquery' ) );
			wp_enqueue_script( 'sales-countdown-timer-semantic-transition', SALES_COUNTDOWN_TIMER_JS . 'transition.min.js', array( 'jquery' ) );
			wp_enqueue_script( 'sales-countdown-timer-semantic-accordion', SALES_COUNTDOWN_TIMER_JS . 'accordion.min.js', array( 'jquery' ) );

			wp_enqueue_script( 'sales-countdown-timer-admin', SALES_COUNTDOWN_TIMER_JS . 'sales-countdown-timer-admin.js', array( 'jquery' ), SALES_COUNTDOWN_TIMER_VERSION );
			/*Color picker*/
			wp_enqueue_script(
				'iris', admin_url( 'js/iris.min.js' ), array(
				'jquery-ui-draggable',
				'jquery-ui-slider',
				'jquery-touch-punch'
			), false, 1
			);
			$id = $this->settings->get_id();
			if ( is_array( $id ) && count( $id ) ) {
				$css = '';

				for ( $i = 0; $i < count( $id ); $i ++ ) {
					if ( $this->settings->get_datetime_value_bg_color()[ $i ] ) {
						$css .= '.woo-sctr-accordion-wrap[data-accordion_id="' . $i . '"] .woo-sctr-shortcode-countdown-style-4 .woo-sctr-shortcode-countdown-1 .woo-sctr-progress-circle:after{' . esc_attr__( 'background:' ) . $this->settings->get_datetime_value_bg_color()[ $i ] . ';}';
					}
					if ( $this->settings->get_countdown_timer_item_border_color()[ $i ] ) {
						$css .= '.woo-sctr-accordion-wrap-' . $i . ' .woo-sctr-shortcode-countdown-style-4 .woo-sctr-shortcode-countdown-1 .woo-sctr-progress-circle .woo-sctr-value-bar{' . esc_attr__( 'border-color: ' ) . $this->settings->get_countdown_timer_item_border_color()[ $i ] . ';}';
						$css .= '.woo-sctr-accordion-wrap-' . $i . ' .woo-sctr-shortcode-countdown-style-4 .woo-sctr-shortcode-countdown-1 .woo-sctr-progress-circle .woo-sctr-first50-bar{' . esc_attr__( 'background-color: ' ) . $this->settings->get_countdown_timer_item_border_color()[ $i ] . ';}';
					}
					if ( $this->settings->get_datetime_value_font_size()[ $i ] ) {
						$css .= '.woo-sctr-accordion-wrap-' . $i . ' .woo-sctr-shortcode-countdown-style-4 .woo-sctr-shortcode-countdown-1 .woo-sctr-progress-circle{' . esc_attr__( 'font-size:' ) . $this->settings->get_datetime_value_font_size()[ $i ] . 'px;}';
					}

					$css .= '.woo-sctr-accordion-wrap-' . $i . ' .woo-sctr-shortcode-wrap-wrap:not(.woo-sctr-shortcode-wrap-wrap-inline) .woo-sctr-shortcode-countdown-1{';
					if ( $this->settings->get_countdown_timer_color()[ $i ] ) {
						$css .= esc_attr__( 'color:' ) . $this->settings->get_countdown_timer_color()[ $i ] . ';';
					}
					if ( $this->settings->get_countdown_timer_bg_color()[ $i ] ) {
						$css .= esc_html__( 'background:' ) . $this->settings->get_countdown_timer_bg_color()[ $i ] . ';';
					}
					if ( $this->settings->get_countdown_timer_padding()[ $i ] ) {
						$css .= esc_html__( 'padding:' ) . $this->settings->get_countdown_timer_padding()[ $i ] . 'px;';
					}
					if ( $this->settings->get_countdown_timer_border_radius()[ $i ] ) {
						$css .= esc_html__( 'border-radius:' ) . $this->settings->get_countdown_timer_border_radius()[ $i ] . 'px;';
					}
					if ( $this->settings->get_countdown_timer_border_color()[ $i ] ) {
						$css .= esc_html__( 'border: 1px solid ' ) . $this->settings->get_countdown_timer_border_color()[ $i ] . ';';
					}
					$css .= '}';

					$css .= '.woo-sctr-accordion-wrap-' . $i . ' .woo-sctr-shortcode-wrap-wrap.woo-sctr-shortcode-wrap-wrap-inline{';
					if ( $this->settings->get_countdown_timer_color()[ $i ] ) {
						$css .= esc_attr__( 'color:' ) . $this->settings->get_countdown_timer_color()[ $i ] . ';';
					}
					if ( $this->settings->get_countdown_timer_bg_color()[ $i ] ) {
						$css .= esc_html__( 'background:' ) . $this->settings->get_countdown_timer_bg_color()[ $i ] . ';';
					}
					if ( $this->settings->get_countdown_timer_padding()[ $i ] ) {
						$css .= esc_html__( 'padding:' ) . $this->settings->get_countdown_timer_padding()[ $i ] . 'px;';
					}
					if ( $this->settings->get_countdown_timer_border_radius()[ $i ] ) {
						$css .= esc_html__( 'border-radius:' ) . $this->settings->get_countdown_timer_border_radius()[ $i ] . 'px;';
					}
					if ( $this->settings->get_countdown_timer_border_color()[ $i ] ) {
						$css .= esc_html__( 'border: 1px solid ' ) . $this->settings->get_countdown_timer_border_color()[ $i ] . ';';
					}
					$css .= '}';

					$css .= '.woo-sctr-accordion-wrap-' . $i . ' .woo-sctr-shortcode-wrap-wrap .woo-sctr-shortcode-countdown-1 .woo-sctr-shortcode-countdown-value{';
					if ( $this->settings->get_datetime_value_color()[ $i ] ) {
						$css .= esc_attr__( 'color:' ) . $this->settings->get_datetime_value_color()[ $i ] . ';';
					}
					if ( $this->settings->get_datetime_value_bg_color()[ $i ] ) {
						$css .= esc_attr__( 'background:' ) . $this->settings->get_datetime_value_bg_color()[ $i ] . ';';
					}
					if ( $this->settings->get_datetime_value_font_size()[ $i ] ) {
						$css .= esc_attr__( 'font-size:' ) . $this->settings->get_datetime_value_font_size()[ $i ] . 'px;';
					}
					$css .= '}';
					$css .= '.woo-sctr-accordion-wrap-' . $i . ' .woo-sctr-shortcode-wrap-wrap .woo-sctr-shortcode-countdown-1 .woo-sctr-shortcode-countdown-text{';
					if ( $this->settings->get_datetime_unit_color()[ $i ] ) {
						$css .= esc_attr__( 'color:' ) . $this->settings->get_datetime_unit_color()[ $i ] . ';';
					}
					if ( $this->settings->get_datetime_unit_bg_color()[ $i ] ) {
						$css .= esc_attr__( 'background:' ) . $this->settings->get_datetime_unit_bg_color()[ $i ] . ';';
					}
					if ( $this->settings->get_datetime_unit_font_size()[ $i ] ) {
						$css .= esc_attr__( 'font-size:' ) . $this->settings->get_datetime_unit_font_size()[ $i ] . 'px;';
					}
					$css .= '}';

					$css1 = '';
					if ( $this->settings->get_countdown_timer_item_height()[ $i ] ) {
						$css1 .= esc_html__( 'height:' ) . $this->settings->get_countdown_timer_item_height()[ $i ] . 'px;';
					}
					if ( $this->settings->get_countdown_timer_item_width()[ $i ] ) {
						$css1 .= esc_html__( 'width:' ) . $this->settings->get_countdown_timer_item_width()[ $i ] . 'px;';
					}
					if ( $this->settings->get_countdown_timer_item_border_radius()[ $i ] ) {
						$css1 .= esc_html__( 'border-radius:' ) . $this->settings->get_countdown_timer_item_border_radius()[ $i ] . 'px;';
					}
					if ( $this->settings->get_countdown_timer_item_border_color()[ $i ] ) {
						$css1 .= esc_html__( 'border:1px solid ' ) . $this->settings->get_countdown_timer_item_border_color()[ $i ] . ';';
					}
					if ( $css1 ) {
						$css .= '.woo-sctr-accordion-wrap-' . $i . ' .woo-sctr-shortcode-countdown-wrap.woo-sctr-shortcode-countdown-style-1 .woo-sctr-shortcode-countdown-unit,.woo-sctr-accordion-wrap-' . $i . ' .woo-sctr-shortcode-countdown-wrap.woo-sctr-shortcode-countdown-style-2 .woo-sctr-shortcode-countdown-value{' . $css1 . '}';
					}

				}


				wp_add_inline_style( 'sales-countdown-timer-admin', $css );
			}
		}
	}

	/**
	 * Link to Settings
	 *
	 * @param $links
	 *
	 * @return mixed
	 */
	public function settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=sales-countdown-timer" title="' . __( 'Settings', 'sales-countdown-timer' ) . '">' . __( 'Settings', 'sales-countdown-timer' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * load Language translate
	 */
	public function load_plugin_textdomain() {
		$locale = apply_filters( 'plugin_locale', get_locale(), 'sales-countdown-timer' );
		// Global + Frontend Locale
		load_textdomain( 'sales-countdown-timer', SALES_COUNTDOWN_TIMER_LANGUAGES . "sales-countdown-timer-$locale.mo" );
		load_plugin_textdomain( 'sales-countdown-timer', false, SALES_COUNTDOWN_TIMER_LANGUAGES );
	}

	public function init() {
		load_plugin_textdomain( 'sales-countdown-timer' );
		$this->load_plugin_textdomain();
		if ( class_exists( 'VillaTheme_Support' ) ) {
			new VillaTheme_Support(
				array(
					'support'   => 'https://wordpress.org/support/plugin/sales-countdown-timer/',
					'docs'      => 'http://docs.villatheme.com/?item=sales-countdown-timer',
					'review'    => 'https://wordpress.org/support/plugin/sales-countdown-timer/reviews/?rate=5#rate-response',
					'pro_url'   => 'https://1.envato.market/962d3',
					'css'       => SALES_COUNTDOWN_TIMER_CSS,
					'image'     => SALES_COUNTDOWN_TIMER_IMAGES,
					'slug'      => 'sales-countdown-timer',
					'menu_slug' => 'sales-countdown-timer',
					'survey_url' => 'https://script.google.com/macros/s/AKfycbzyJ8_bX_gpf3AWbaDg51QcGmOKYqpxIvTnfNq21dxjkbRHjoNH0QpaBAClxeYgeXeeFA/exec',
					'version'   => SALES_COUNTDOWN_TIMER_VERSION
				)
			);
		}
	}
}

?>