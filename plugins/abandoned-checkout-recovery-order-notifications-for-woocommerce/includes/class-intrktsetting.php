<?php
/**
 * Setting Page.
 *
 * Handle admin setting page.
 *
 * @package interakt-add-on-woocommerce
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
/**
 * Setting class.
 */
class IntrktSetting {
	/**
	 * Setting option array.
	 *
	 * @var array $intrkt_setting_options.
	 */
	private $intrkt_setting_options;
	/**
	 * Member Variable
	 *
	 * @var instance
	 */
	private static $instance;
	/**
	 * Constructor
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'intrkt_setting_add_plugin_page' ) );
		add_action( 'wp_ajax_intrkt_save_setting', array( $this, 'intrkt_save_setting_callback' ) );
		add_action( 'wp_ajax_intrkt_disconnect_oauth', array( $this, 'intrkt_disconnect_oauth_callback' ) );
		// js.
		add_action( 'admin_enqueue_scripts', array( $this, 'intrkt_register_setting_scripts' ) );
		// css.
		add_action( 'admin_enqueue_scripts', array( $this, 'intrkt_admin_enqueue_style' ) );
		add_filter( 'plugin_action_links_' . INTRKT_BASE, array( $this, 'intrkt_settings_link' ) );

	}

	/**
	 * Register menu page.
	 */
	public function intrkt_setting_add_plugin_page() {
		$capability = current_user_can( 'manage_woocommerce' ) ? 'manage_woocommerce' : 'manage_options';

		add_submenu_page(
			'woocommerce',
			__( 'Interakt', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
			__( 'Interakt', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
			$capability,
			INTRKT_SLUG,
			array( $this, 'intrkt_setting_create_admin_page' )
		);
	}
	/**
	 * Setting page.
	 */
	public function intrkt_setting_create_admin_page() {
		?>
		<div class="wrap intrkt_main_wrapper">
			<h2><?php esc_html_e( 'Set up your Woocommerce synced WhatsApp Store!', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></h2>
			<p class="intrkt_intro_desc"><?php esc_html_e( 'Interakt is an Official WhatsApp Business Service Provider. We enable businesses to better utilize WhatsApp by accessing WhatsApp APIs.', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></p>
			<p class="notice-warning notice" >
				<span>
					<?php
					/* translators: $1: Contact number, $2 Contact email */
					printf( esc_html__( 'If you face any issues, please WhatsApp us at %1$s or email us at %2$s.', 'abandoned-checkout-recovery-order-notifications-woocommerce' ), esc_html( INTRKT_CONTACT_NUMBER ), esc_html( INTRKT_CONTACT_EMAIL ) );
					?>
				</span>
				<span>
					<?php
					/* translators: $1: Interakt Woocommerce doc url, */
					echo '<br>' . sprintf( esc_html__( 'Check our Woocommerce documentation %s.', 'abandoned-checkout-recovery-order-notifications-woocommerce' ), "<a href= '" . esc_url( INTRKT_WOOCOMMERCE_DOC ) . "' target='_blank'>here</a>" );

					?>
				</span>
			</p>
			<ul class="intrkt_setting-list">
				<li>
					<?php
					$this->intrkt_setting_section_one();
					?>
				</li>
				<li>
					<?php
					$this->intrkt_setting_section_two();
					?>
				</li>
				<li>
					<?php
					$this->intrkt_setting_section_three();
					?>
				</li>
				<li>
					<?php
					$this->intrkt_setting_section_four();
					?>
				</li>
			</ul>
		</div>
		<div class="intrkt-setting-footer">
			<?php
			$this->intrkt_setting_section_footer();
			?>
		</div>
		<?php

	}
	/**
	 * OAuth settings.
	 * Creates HTML for section.
	 *
	 * @return void.
	 */
	public function intrkt_setting_section_one() {
		$upgrade                     = 'https://app.interakt.ai/billing/modify-subscription';
		$see_how                     = INTRKT_WOOCOMMERCE_DOC . '#no-2nd-alert';
		$oauth_status_label          = __( 'Connect To Interakt', 'abandoned-checkout-recovery-order-notifications-woocommerce' );
		$oauth_status                = intrkt_load()->utils->intrkt_get_account_connection_status();
		$interakt_oauth_route        = intrkt_load()->utils->intrkt_get_oauth_custom_route();
		$you_can_use_your_own_number = 'https://interakt.shop/resource-center/how-to-apply-for-whatsapp-business-api/';
		?>
		<div class="intrkt_section-one-wrap intrkt_accordion" >
			<div class="intrkt_section-one-heading interakt_accordion_heading_wrp" id="interakt_account_log_wrp">
				<div class="intrkt_num_head_grp">
					<span class="accordion_number">1</span>
					<h3 class="interakt_accordion_heading" data-bs-toggle="collapse"data-bs-target="#intrkt_section-one-body" aria-expanded="true" aria-controls="intrkt_section-one-body" id="interakt_account_log_heading">
						<?php esc_html_e( 'Create or log-in into your Interakt account', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?>
					<?php if ( 'true' === $oauth_status ) : ?>
						<span class="intrkt_connection_success"><?php esc_html_e( 'Connected', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></span>
					<?php endif; ?>
					</h3>
				</div>
			</div>
			<div id="intrkt_section-one-body" class="intrkt_section-one-body collapse show intrkt_accordion_body" aria-labelledby="interakt_account_log_heading" data-bs-parent="#interakt_account_log_wrp">
				<?php if ( 'true' === $oauth_status ) : ?>
					<div class="intrkt-connection-status-wrap">
						<div class="intrkt-connection-status">
							<span><?php esc_html_e( 'Status', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></span>
							<span><?php esc_html_e( 'Connected', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></span>
						</div>
						<div class="intrkt-connection-message">
							<p>
								<?php
								esc_html_e( 'To connect another Interakt account to your WooCommerce store, please contact us at ', 'abandoned-checkout-recovery-order-notifications-woocommerce' );
								?>
								<strong>
									<?php echo esc_html( INTRKT_CONTACT_EMAIL ); ?>
								</strong>
								.
							</p>
						</div>
					</div>
					<a id='intrkt_disconnect' href="">Disconnect</a>
				<?php else : ?>
					<a class = "button button-primary oauth-connect-interakt" href = "<?php echo esc_url( $interakt_oauth_route ); ?>" target= "_blank"> <?php echo esc_html( $oauth_status_label ); ?></a>
					<p class = 'notice-info notice' >
						<span>
							<?php
								echo wp_kses(
									sprintf(
										__( "<strong> If you already have an Interakt account </strong>: Click on 'Connect to Interakt' & log into your account. Then refresh this page to continue with the next steps.", 'abandoned-checkout-recovery-order-notifications-woocommerce' )
									),
									array(
										'strong' => array(),
									)
								);
							?>
						</span>
						<br>
						<span>
							<?php
								echo wp_kses(
									sprintf(
										/* translators: $1s: Interakt FAQ url, $2s: Interakt FAQ url*/
										__( "<strong>If you don't have an Interakt account</strong>: Click on 'Connect to Interakt' & sign up for a new account. Then refresh this page to continue with the next steps. A 14 days free trial (with access to premium features) will start immediately. The WhatsApp number assigned to your account would be our test number. <a href = '%3\$s' target = '_blank' > You can use your own number</a> as well. After the trial, you may continue in a Free Forever Plan or <a href= '%2\$s' target= '_blank' >upgrade</a>.", 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
										esc_html( INTRKT_CONTACT_NUMBER ),
										esc_url( $upgrade ),
										esc_url( $you_can_use_your_own_number )
									),
									array(
										'a'      => array(
											'href'   => true,
											'target' => true,
										),
										'strong' => array(),
									),
								);
							?>
						</span>
					</p>
					<span>
						<?php
						/* translators: $1s: Interakt FAQ url */
						echo wp_kses(
							sprintf(
								__( "If you face issues in connecting, it could be because of your firewall. Disable your firewall & try connecting again (you can enable it again after that), or else, try whitelisting Interakt's IP.  <a href= '%1\$s' target= '_blank' >See how</a>. ", 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
								esc_url( $see_how )
							),
							array(
								'strong' => array(),
								'a'      => array(
									'href'   => true,
									'target' => true,
								),
							)
						);
						?>
					</span>
				<?php endif; ?>

			</div>
		</div>

		<?php
	}
	/**
	 * Order status section.
	 */
	public function intrkt_setting_section_two() {
		$integration_status_class   = '';
		$integration_status         = intrkt_load()->utils->intrkt_get_account_integration_status();
		$oauth_status               = intrkt_load()->utils->intrkt_get_account_connection_status();
		$integration_status_tooltip = '';
		$see_campaigns_live         = INTRKT_WOOCOMMERCE_DOC . '#set-live-ab';
		if ( 'true' === $integration_status || 'false' === $oauth_status ) {
			$integration_status_class = 'intrkt-disable-section';
		}
		if ( 'false' === $oauth_status ) {
			$integration_status_tooltip = esc_html__( 'Connect your Interakt account to enable the steps', 'abandoned-checkout-recovery-order-notifications-woocommerce' );
		}
		$order_status_rows = intrkt_load()->utils->intrkt_get_order_status_data();
		if ( empty( $order_status_rows ) || ! is_array( $order_status_rows ) ) {
			return;
		}
		$wc_order_statuses          = wc_get_order_statuses();
		$order_status_empty_allowed = intrkt_load()->utils->intrkt_get_order_statuses_empty_allowed();
		$shipping_plugin_doc        = INTRKT_WOOCOMMERCE_DOC;
		$intrkt_status_with_note    = array(
			'intrkt_abandon_checkout',
			'intrkt_order_shipped',
			'intrkt_order_delivered',
		);
		?>
		<div class="intrkt_section-two-wrap intrkt_accordion <?php echo esc_html( $integration_status_class ); ?>">
			<div class="intrkt_section-two-heading interakt_accordion_heading_wrp" id="intrkt_section-two-heading">
				<div class="intrkt_num_head_grp">
					<span class="accordion_number">2</span>
					<h3 class="interakt_accordion_heading" data-bs-toggle="collapse"  title="<?php echo esc_html( $integration_status_tooltip ); ?>"  data-bs-target="#intrkt_section-two-body" aria-expanded="true" aria-controls="intrkt_section-two-body" id="interakt_order_status_heading" >
						<?php esc_html_e( 'Set ‘Order Statuses’ for which WhatsApp Notifications are to be sent', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?>
					</h3>
				</div>
			</div>
			<div id="intrkt_section-two-body" class="intrkt_accordion_body intrkt_section-two-body collapse show" aria-labelledby="interakt_order_status_heading" data-bs-parent="#intrkt_section-two-heading">
				<p>
					<?php
					echo wp_kses(
						sprintf(
							/* translators: $1$s: Interakt url */
							__( 'On Interakt,  <a href="%1$s" target= "_blank" >please set campaigns live, </a> to ensure that the following WhatsApp Notifications are sent.', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
							esc_url( $see_campaigns_live )
						),
						array(
							'a' => array(
								'href'   => true,
								'target' => true,
							),
						),
					);
					?>
				</p>
				<table id='intrkt_order_status_table'>
					<thead>
						<tr>
							<th><?php esc_html_e( 'WhatsApp Notification', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></th>
							<th><?php esc_html_e( 'Order Status in WooCommerce', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></th>
							<th><?php esc_html_e( 'Order Payment Method in WooCommerce', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ( $order_status_rows as $order_status_row ) : ?>
							<?php
								$intrkt_order_status_label = intrkt_load()->utils->intrkt_get_intrkt_order_status_label( $order_status_row['intrkt_status'] );
								$wc_order_status_label     = wc_get_order_status_name( $order_status_row['order_status'] );
								$wc_order_payments         = WC()->payment_gateways()->payment_gateways();
							?>
							<tr>
								<td data-intrktOrderStatus = '<?php echo esc_html( $order_status_row['intrkt_status'] ); ?>'>
									<span><?php echo esc_html( $intrkt_order_status_label ); ?></span>
									<span>
										<?php if ( 'intrkt_abandon_checkout' === $order_status_row['intrkt_status'] ) : ?>
											*
										<?php elseif ( 'intrkt_order_shipped' === $order_status_row['intrkt_status'] || 'intrkt_order_delivered' === $order_status_row['intrkt_status'] ) : ?>
											**
										<?php endif; ?>
									</span>
								</td>
								<td>
									<span class ='intrkt-mobile-heading intrkt-heading-order-status'><?php esc_html_e( 'Order Status', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></span>
									<?php
									if ( 'any' === $order_status_row['order_status'] ) :
										?>
										<span class = 'intrkt-no-value'><?php esc_html_e( 'Any', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></span>
										<?php

									else :
										?>
										<?php $order_status_row_array = explode( ',', $order_status_row['order_status'] ); ?>
											<?php if ( 'intrkt_abandon_checkout' === $order_status_row['intrkt_status'] ) : ?>
												<select name="intrkt_order_status[]" class="intrkt_order_status intrkt_abandon_checkout" multiple="multiple">
													<?php if ( in_array( $order_status_row['intrkt_status'], $order_status_empty_allowed ) ) : ?>
														<option value=""><?php esc_html_e( 'Please Select', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></option>
													<?php endif; ?>
													<?php
													foreach ( $wc_order_statuses as $key => $wc_order_status ) :
														$selected = ( in_array( $key, $order_status_row_array ) ) ? 'SELECTED' : '';
														?>
														<option value="<?php echo esc_attr( $key ); ?>"
														<?php echo esc_attr( $selected ); ?>>
														<?php echo esc_html( $wc_order_status ); ?></option>
													<?php endforeach; ?>
												</select>
											<?php else : ?>
												<select name="intrkt_order_status" class="intrkt_order_status">
													<?php if ( in_array( $order_status_row['intrkt_status'], $order_status_empty_allowed ) ) : ?>
														<option value=""><?php esc_html_e( 'Please Select', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></option>
													<?php endif; ?>
													<?php foreach ( $wc_order_statuses as $key => $wc_order_status ) : ?>
														<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $order_status_row['order_status'] ); ?>><?php echo esc_html( $wc_order_status ); ?></option>
													<?php endforeach; ?>
												</select>
											<?php endif; ?>
										<?php
									endif;
									?>
								</td>
								<?php
								$intrkt_has_status = ( in_array( $order_status_row['intrkt_status'], $intrkt_status_with_note ) ) ? 'intrkt_status_note' : '';
								?>
								<td class = "<?php echo esc_attr( $intrkt_has_status ); ?>" >
									<span class ='intrkt-mobile-heading intrkt-heading-payment-method'><?php esc_html_e( 'Payment Method', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></span>
									<?php
									if ( 'any' === $order_status_row['payment_mode'] ) :
										?>
											<span class = 'intrkt-no-value'><?php esc_html_e( 'Any', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></span>
										<?php
									else :
										?>
											<select name="intrkt_payment_mode" class="intrkt_payment_mode">
												<option value="not-cod"><?php esc_html_e( 'Not Cash on delivery', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></option>
												<?php foreach ( $wc_order_payments as $key => $wc_order_payment ) : ?>
													<option value="<?php echo esc_attr( $key ); ?>" <?php selected( $key, $order_status_row['payment_mode'] ); ?>><?php echo esc_html( $wc_order_payment->title ); ?></option>
												<?php endforeach; ?>
											</select>
										<?php
									endif;
									if ( 'intrkt_abandon_checkout' === $order_status_row['intrkt_status'] ) :
										?>
										<span class ='intrkt-mobile-note intrkt-note-abandon-cart'><?php esc_html_e( "*These will be sent if, after 15 minutes of the customer filling the phone number, the order doesn't appear in your Woocommerce orders panel, or, it does appear but, with the order statuses selected here for Abandoned Checkout. ", 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></span>
										<?php
									elseif ( 'intrkt_order_shipped' === $order_status_row['intrkt_status'] || 'intrkt_order_delivered' === $order_status_row['intrkt_status'] ) :
										?>
										<span class ='intrkt-mobile-note intrkt-note-shipping'>
											<?php
											echo wp_kses(
												sprintf(
													/* translators: $1s: Interakt Shipping plugin url*/
													__( '**Tracking info (like tracking url, tracking number, tracking provider) can be sent in the notification only if you are using a specific shipping plugin - check our <a href="%1$s" target= "_blank" >documentation</a> to know more.', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
													esc_url( $shipping_plugin_doc )
												),
												array(
													'a' => array(
														'href'   => true,
														'target' => true,
													),
												),
											);
											?>
										</span>
										<?php
									endif;
									?>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="3">
								<p>
									<?php esc_html_e( "*These will be sent if, after 15 minutes of the customer filling the phone number, the order doesn't appear in your Woocommerce orders panel, or, it does appear but, with the order statuses selected here for Abandoned Checkout.", 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?>
								</p>
								<p>
									<?php
									echo wp_kses(
										sprintf(
											/* translators: $1s: Interakt Shipping plugin doc*/
											__( '**Tracking info (like tracking url, tracking number, tracking provider) can be sent in the notification only if you are using a specific shipping plugin - check our <a href="%1$s" target= "_blank" >documentation</a>. to know more.', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
											esc_url( $shipping_plugin_doc )
										),
										array(
											'a' => array(
												'href'   => true,
												'target' => true,
											),
										)
									);
									?>
								</p>
							</td>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>

		<?php
	}
	/**
	 * Country code section.
	 * Creates HTML for section.
	 *
	 * @return void.
	 */
	public function intrkt_setting_section_three() {
		$integration_status_class = '';
		$integration_status       = intrkt_load()->utils->intrkt_get_account_integration_status();
		$oauth_status             = intrkt_load()->utils->intrkt_get_account_connection_status();
		if ( 'true' === $integration_status || 'false' === $oauth_status ) {
			$integration_status_class = 'intrkt-disable-section';
		}
		$country_code_selection = intrkt_load()->utils->intrkt_intrkt_get_country_code_selection();
		if ( empty( $country_code_selection ) ) {
			$country_code_selection = INTRKT_WITHOUT_COUNTRY_CODE;
		}
		$is_required = ( INTRKT_WITHOUT_COUNTRY_CODE === $country_code_selection ) ? 'required' : '';

		$country_code = intrkt_load()->utils->intrkt_get_country_code();
		?>
		<div class="intrkt_section-three-wrap intrkt_accordion <?php echo esc_html( $integration_status_class ); ?>" >
			<div class="intrkt_section-three-heading interakt_accordion_heading_wrp" id="interakt_customer_phone_wrp">
				<div class="intrkt_num_head_grp">
					<span class="accordion_number">3</span>
					<h3 class="interakt_accordion_heading" data-bs-toggle="collapse"data-bs-target="#intrkt_section-three-body" aria-expanded="true" aria-controls="intrkt_section-three-body" id="interakt_customer_phone_heading">
						<?php esc_html_e( 'Select the phone number format provided by customers in the Phone field, in your Website Checkout Form', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?>
					</h3>
				</div>
			</div>
			<div id="intrkt_section-three-body" class="intrkt_section-three-body collapse show intrkt_accordion_body" aria-labelledby="interakt_customer_phone_heading" data-bs-parent="#interakt_customer_phone_wrp">
				<div class="customer_phone_section">
					<div class="intrkt_radio_wrp">
						<input type="radio" id= '<?php echo esc_attr( INTRKT_WITHOUT_COUNTRY_CODE ); ?>' name="country_code_status" class="intrkt_radio_btn intrkt_country_selection" value= "<?php echo esc_attr( INTRKT_WITHOUT_COUNTRY_CODE ); ?>" <?php checked( INTRKT_WITHOUT_COUNTRY_CODE, $country_code_selection ); ?> >
						<label for= '<?php echo esc_attr( INTRKT_WITHOUT_COUNTRY_CODE ); ?>'><?php esc_html_e( 'My customers provide phone number without the country code. (commonly seen amongst customers)', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></label>
						<div class="intrkt_radio_check"></div>
					</div>
					<input type="text" class="intrkt_text_input" name="intrkt_country_code" id="intrkt_country_code" placeholder="<?php esc_html_e( 'Enter common Country Code for all customers (with +). For example: +91', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?>" value='<?php echo esc_html( $country_code ); ?>' <?php echo esc_attr( $is_required ); ?> >
					<span class = 'intrkt_text_input_error_msg'><?php esc_html_e( 'Please fill the country code', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></span>
					<p class="notice-info notice">
						<?php esc_html_e( 'Recommended for merchants shipping to one country', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?>
					</p>
				</div>
				<div class="customer_phone_section">
					<div class="intrkt_radio_wrp">
						<input type="radio" id= '<?php echo esc_attr( INTRKT_WITH_COUNTRY_CODE ); ?>' name="country_code_status" class="intrkt_radio_btn intrkt_country_selection" value= '<?php echo esc_attr( INTRKT_WITH_COUNTRY_CODE ); ?>' <?php checked( INTRKT_WITH_COUNTRY_CODE, $country_code_selection ); ?> >
						<label for= '<?php echo esc_attr( INTRKT_WITH_COUNTRY_CODE ); ?>'><?php esc_html_e( 'My customers provide phone number along with the country code', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></label>
						<div class="intrkt_radio_check"></div>
					</div>
					<p class="notice-info notice">
						<?php esc_html_e( "To ensure this, you can include a note below the ‘Phone’ field (in your website checkout form), asking customers to enter the country code along with the phone number. Note example: 'Provide Phone as +917003705584, instead of 7003705584'", 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?>
					</p>
				</div>
			</div>
		</div>
		<?php
	}
	/**
	 * Set live settings.
	 * Creates HTML for section.
	 *
	 * @return void.
	 */
	public function intrkt_setting_section_four() {
		$see_how                  = INTRKT_WOOCOMMERCE_DOC . '#set-live-ab';
		$integration_status       = intrkt_load()->utils->intrkt_get_account_integration_status();
		$integration_status_label = ( 'false' === $integration_status ) ? __( 'Set Live', 'abandoned-checkout-recovery-order-notifications-woocommerce' ) : __( 'Pause', 'abandoned-checkout-recovery-order-notifications-woocommerce' );
		$oauth_status             = intrkt_load()->utils->intrkt_get_account_connection_status();
		$integration_status_class = ( 'true' === $integration_status ) ? 'intrkt_is_connected' : 'intrkt_is_not_connected';
		if ( 'false' === $oauth_status ) {
			$integration_status_class = 'intrkt-disable-section';
		}
		?>
		<div class="intrkt_section-four-wrap intrkt_accordion <?php echo esc_html( $integration_status_class ); ?>">
			<div class="intrkt_section-four-heading interakt_accordion_heading_wrp" id="interakt_set_integration_wrp">
				<div class="intrkt_num_head_grp">
					<span class="accordion_number">4</span>
					<h3 class="interakt_accordion_heading" data-bs-toggle="collapse" data-bs-target="#intrkt_section-four-body" aria-expanded="true" aria-controls="intrkt_section-four-body" id="interakt_set_integration_heading">
						<?php esc_html_e( 'Set integration live!', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?>
						<?php if ( 'true' === $integration_status ) : ?>
							<span class="intrkt_connection_success"><?php esc_html_e( 'Live', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?></span>
						<?php endif; ?>
					</h3>
				</div>
			</div>
			<div id="intrkt_section-four-body" class="intrkt_section-four-body collapse show intrkt_accordion_body" aria-labelledby="interakt_set_integration_heading" data-bs-parent="#interakt_set_integration_wrp">
				<?php if ( 'true' === $integration_status ) : ?>
					<div class="intrkt-integration-message">
						<p>
							<?php
								echo wp_kses(
									sprintf(
										/* translators: $1s: Interakt FAQ url */
										__( 'Your WhatsApp integration is live! If you want to make any changes to the above settings, first pause the integration. <br><strong>Make sure you set live Campaigns on Interakt for the notifications to be sent. <a href="%1$s" target= "_blank" >See how.<span class="dashicons dashicons-external"> </span></a></strong>', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
										esc_url( $see_how )
									),
									array(
										'a'      => array(
											'href'   => true,
											'target' => true,
										),
										'strong' => array(),
										'br'     => array(),
										'span'   => array(),
									),
								);
							?>
						</p>
					</div>
				<?php endif; ?>
				<div class="intrkt_tooltip">
					<input type="submit" class='button button-primary <?php echo esc_html( $integration_status_class ); ?>' id='intrkt-set-live' value="<?php echo esc_html( $integration_status_label ); ?>">
					<?php if ( 'true' === $integration_status ) : ?>
						<p class="intrkt_tooltiptext">
							<?php
								echo wp_kses(
									sprintf(
										__( 'Pausing the Integration will ensure that your <br> Woocommerce data is not sent to Interakt.', 'abandoned-checkout-recovery-order-notifications-woocommerce' )
									),
									array(
										'br' => array(),
									)
								);
							?>
						</p>
					<?php endif ?>
				</div>
			</div>
		</div>
		<?php
	}
	/**
	 * Extra content settings.
	 * Creates HTML for section.
	 *
	 * @return void.
	 */
	public function intrkt_setting_section_footer() {
		$visit_interakt = 'http://app.interakt.ai/';
		?>
		<div class="intrkt_section-footer-wrap">
			<p>
				<?php
					echo wp_kses(
						sprintf(
							/* translators: $1s: Interakt terms url, $2s: Interakt condition url */
							__( 'Have a look at our <a href="%1$s" target= "_blank" >Terms & conditions</a> and <a href="%2$s" target= "_blank" >Privacy Policy</a>.', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
							esc_url( INTRKT_TERMS_CONDITION ),
							esc_url( INTRKT_PRIVACY_POLICY )
						),
						array(
							'a' => array(
								'href'   => true,
								'target' => true,
							),
						),
					);
				?>
			</p>
			<a class = intrkt-visit-intrkt href="<?php echo esc_url( $visit_interakt ); ?>" target= "_blank" ><?php esc_html_e( 'Visit Interakt', 'abandoned-checkout-recovery-order-notifications-woocommerce' ); ?> <span class="dashicons dashicons-external"> </span></a>
		</div>
		<?php
	}
	/**
	 * Enqueue css
	 */
	public function intrkt_admin_enqueue_style() {
		$is_intrkt_setting_page = intrkt_load()->utils->is_intrkt_setting_page();
		if ( true == $is_intrkt_setting_page ) {
			wp_enqueue_style(
				'intrkt_admin_css',
				INTRKT_URL . 'admin/css/intrkt-setting.css',
				array(),
				INTRKT_VER,
				'all'
			);
			wp_enqueue_style(
				'intrkt_select2_min_css',
				INTRKT_URL . 'admin/css/select2.min.css',
				array(),
				INTRKT_VER,
				'all'
			);
		}
		wp_enqueue_style(
			'intrkt_style_css',
			INTRKT_URL . 'admin/css/intrkt-style.css',
			array(),
			INTRKT_VER,
			'all'
		);
	}
	/**
	 * Enqueue required scripts.
	 */
	public function intrkt_register_setting_scripts() {
		$is_intrkt_setting_page = intrkt_load()->utils->is_intrkt_setting_page();
		if ( true == $is_intrkt_setting_page ) {
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script(
				'intrkt-popper-min',
				INTRKT_URL . 'admin/js/popper.min.js',
				array( 'jquery' ),
				INTRKT_VER,
				true
			);
			wp_enqueue_script(
				'intrkt-bootstrap-min',
				INTRKT_URL . 'admin/js/bootstrap.bundle.min.js',
				array( 'jquery' ),
				INTRKT_VER,
				true
			);
		}
		wp_enqueue_script(
			'intrkt-select2-min',
			INTRKT_URL . 'admin/js/select2.min.js',
			array( 'jquery' ),
			INTRKT_VER,
			true
		);
		wp_enqueue_script(
			'intrkt-settings',
			INTRKT_URL . 'admin/js/intrkt-setting.js',
			array( 'jquery' ),
			INTRKT_VER,
			true
		);
		$integration_status = intrkt_load()->utils->intrkt_get_account_integration_status();
		$vars               = array(
			'ajaxurl'                  => admin_url( 'admin-ajax.php' ),
			'_nonce'                   => wp_create_nonce( 'intrkt_settings_data' ),
			'intrktDefaultCountryCode' => INTRKT_DEFAULT_COUNTRY_CODE,
			'intrktWithoutCountryCode' => INTRKT_WITHOUT_COUNTRY_CODE,
			'intrktintegrationStatus'  => $integration_status,
			'disconnectConfirm'        => __( 'Do you want to disconnect the Interakt account', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
			'completeDisconnect'       => __( 'To complete the process please contact the interakt support team', 'abandoned-checkout-recovery-order-notifications-woocommerce' ),
		);

		wp_localize_script( 'intrkt-settings', 'intrktSettingVars', $vars );
	}
	/**
	 * Ajax call to save settings.
	 */
	public function intrkt_save_setting_callback() {
		check_ajax_referer( 'intrkt_settings_data', 'security' );
		$post_data = $this->intrkt_sanitize_setting_data();
		$this->intrkt_save_integration_status( $post_data );
		$this->intrkt_save_order_status_data( $post_data );
		$this->intrkt_save_country_code_data( $post_data );
		wp_send_json( $post_data );
	}
	/**
	 * Ajax call to disconnect oAuth.
	 */
	public function intrkt_disconnect_oauth_callback() {
		check_ajax_referer( 'intrkt_settings_data', 'security' );
		intrkt_load()->utils->intrkt_disconnect_oauth();
		wp_send_json( true );
	}
	/**
	 * Sanitize post array.
	 *
	 * @return array
	 */
	public function intrkt_sanitize_setting_data() {
		$input_post_values      = array(
			'intrkt_status'  => array(
				'default' => '',
			),
			'order_status'   => array(
				'default' => '',
			),
			'payment_method' => array(
				'default' => '',
			),
		);
		$sanitized_table_values = array();
		$new_integration_status = 'true';
		if ( isset( $_POST['tableValues'] ) && ! empty( $_POST['tableValues'] ) && is_array( $_POST['tableValues'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$table_values = $_POST['tableValues'];//phpcs:ignore
			foreach ( $table_values as $table_value ) {
				$sanitized_post = array();
				foreach ( $input_post_values as $key => $input_post_value ) {
					if ( isset( $table_value[ $key ] ) || empty( $table_value[ $key ] ) ) {
						if ( is_array( $table_value[ $key ] ) ) {
							$sanitized_post[ $key ] = implode( ',', $table_value[ $key ] );
						} else {
							$sanitized_post[ $key ] = sanitize_text_field( $table_value[ $key ] );
						}
					} else {
						$sanitized_post[ $key ] = $input_post_value['default'];
					}
				}
				$sanitized_table_values[] = $sanitized_post;
			}
		}
		if ( isset( $_POST['countryCodeSelection'] ) && ! empty( $_POST['countryCodeSelection'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$country_code_selection = sanitize_text_field( wp_unslash( $_POST['countryCodeSelection'] ) );//phpcs:ignore WordPress.Security.NonceVerification.Missing
		} else {
			$country_code_selection = INTRKT_WITHOUT_COUNTRY_CODE;
		}
		if ( isset( $_POST['countryCode'] ) && ! empty( $_POST['countryCode'] ) ) { //phpcs:ignore WordPress.Security.NonceVerification.Missing
			$country_code = sanitize_text_field( wp_unslash( $_POST['countryCode'] ) );//phpcs:ignore WordPress.Security.NonceVerification.Missing
		}
		return array(
			'sanitized_table_values' => $sanitized_table_values,
			'country_code_selection' => $country_code_selection,
			'country_code'           => $country_code,
		);

	}
	/**
	 * Save integration status.
	 *
	 * @param array $data Sanitized post data.
	 * @return void
	 */
	public function intrkt_save_integration_status( $data ) {
		$current_integration_status = get_option( 'intrkt_integration_status', false );
		$new_integration_status     = 'true';
		if ( 'true' === $current_integration_status ) {
			$new_integration_status = 'false';
		}
		update_option( 'intrkt_integration_status', $new_integration_status );
	}
	/**
	 * Save Order status.
	 *
	 * @param array $data Sanitized post data.
	 * @return boolean
	 */
	public function intrkt_save_order_status_data( $data ) {
		if ( empty( $data['sanitized_table_values'] ) ) {
			return false;
		}
		global $wpdb;
		$intrkt_order_status = $wpdb->prefix . INTRKT_ORDER_STATUS_TABLE;
		$is_table_exist = $wpdb->get_var( "SHOW TABLES LIKE '$intrkt_order_status'" ); //phpcs:ignore
		if ( empty( $is_table_exist ) ) {
			return false;
		}
		foreach ( $data['sanitized_table_values'] as $sanitized_table_value ) {
			$wpdb->update( // phpcs:ignore WordPress.DB.DirectDatabaseQuery
				$intrkt_order_status,
				array(
					'order_status' => $sanitized_table_value['order_status'],
					'payment_mode' => $sanitized_table_value['payment_method'],
				),
				array(
					'intrkt_status' => $sanitized_table_value['intrkt_status'],
				)
			);
		}
		return true;
	}
	/**
	 * Save country code data.
	 *
	 * @param array $data Sanitized post data.
	 * @return void
	 */
	public function intrkt_save_country_code_data( $data ) {
		$country_code_selection = $data['country_code_selection'];
		$country_code           = $data['country_code'];
		update_option( 'intrkt_country_code_selection', $country_code_selection );
		update_option( 'intrkt_country_code', $country_code );
	}
	/**
	 * Add settings link in plugin page.
	 *
	 * @param array $links links.
	 */
	public function intrkt_settings_link( $links ) {
		if ( ! is_array( $links ) ) {
			return $links;
		}
		$action_links = array(
			'settings' => '<a href="' . admin_url( 'admin.php?page=' . INTRKT_SLUG ) . '" aria-label="' . esc_attr__( 'View Interakt settings', 'abandoned-checkout-recovery-order-notifications-woocommerce' ) . '">' . esc_html__( 'Settings', 'abandoned-checkout-recovery-order-notifications-woocommerce' ) . '</a>',
		);
		return array_merge( $action_links, $links );
	}

	/**
	 *  Initiator
	 */
	public static function get_instance() {
		if ( ! isset( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}
IntrktSetting::get_instance();

