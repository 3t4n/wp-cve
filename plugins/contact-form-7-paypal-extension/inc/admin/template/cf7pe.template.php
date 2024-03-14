<?php

$post_id = ( isset( $_REQUEST[ 'post' ] ) ? sanitize_text_field( $_REQUEST[ 'post' ] ) : '' );

if ( empty( $post_id ) ) {
	$wpcf7 = WPCF7_ContactForm::get_current();
	$post_id = $wpcf7->id();
}

wp_enqueue_script( 'wp-pointer' );
 wp_enqueue_style( 'wp-pointer' );

wp_enqueue_style( CF7PE_PREFIX . '_admin_css' );

$use_paypal             = get_post_meta( $post_id, CF7PE_META_PREFIX . 'use_paypal', true );
$mode_sandbox           = get_post_meta( $post_id, CF7PE_META_PREFIX . 'mode_sandbox', true );
$sandbox_client_id      = get_post_meta( $post_id, CF7PE_META_PREFIX . 'sandbox_client_id', true );
$sandbox_client_secret  = get_post_meta( $post_id, CF7PE_META_PREFIX . 'sandbox_client_secret', true );
$live_client_id         = get_post_meta( $post_id, CF7PE_META_PREFIX . 'live_client_id', true );
$live_client_secret     = get_post_meta( $post_id, CF7PE_META_PREFIX . 'live_client_secret', true );
$amount                 = get_post_meta( $post_id, CF7PE_META_PREFIX . 'amount', true );
$description            = get_post_meta( $post_id, CF7PE_META_PREFIX . 'description', true );
$quantity               = get_post_meta( $post_id, CF7PE_META_PREFIX . 'quantity', true );
$mailsend               = get_post_meta( $post_id, CF7PE_META_PREFIX . 'mailsend', true );

$success_returnURL      = get_post_meta( $post_id, CF7PE_META_PREFIX . 'success_returnurl', true );
$cancle_returnURL       = get_post_meta( $post_id, CF7PE_META_PREFIX . 'cancel_returnurl', true );
$message                = get_post_meta( $post_id, CF7PE_META_PREFIX . 'message', true );

$currency               = get_post_meta( $post_id, CF7PE_META_PREFIX . 'currency', true );

$currency_code = array(
	'AUD' => 'Australian Dollar',
	'BRL' => 'Brazilian Real',
	'CAD' => 'Canadian Dollar',
	'CZK' => 'Czech Koruna',
	'DKK' => 'Danish Krone',
	'EUR' => 'Euro',
	'HKD' => 'Hong Kong Dollar',
	'HUF' => 'Hungarian Forint',
	'INR' => 'Indian Rupee',
	'ILS' => 'Israeli New Shekel',
	'JPY' => 'Japanese Yen',
	'MYR' => 'Malaysian Ringgit',
	'MXN' => 'Mexican Peso',
	'TWD' => 'New Taiwan Dollar',
	'NZD' => 'New Zealand Dollar',
	'NOK' => 'Norwegian Krone',
	'PHP' => 'Philippine Peso',
	'PLN' => 'Polish Zloty',
	'GBP' => 'Pound Sterling',
	'RUB' => 'Russian Ruble',
	'SGD' => 'Singapore Dollar',
	'SEK' => 'Swedish Krona',
	'CHF' => 'Swiss Franc',
	'THB' => 'Thai Baht',
	'USD' => 'United States Dollar',
);

$mailsendoption = array(
	'successonly'=>'Success Only',
	'both'=>'Both'
);

$selected = '';

echo '<div class="cf7pe-settings">' .
	'<div class="left-box postbox">' .
		'<table class="form-table">' .
			'<tbody>' .
				'<tr class="form-field">' .
					'<th scope="row">' .
						'<label for="' . CF7PE_META_PREFIX . 'use_paypal">' .
							__( 'Use PayPal Payment Form', 'accept-paypal-payments-using-contact-form-7' ) .
						'</label>' .
					'</th>' .
					'<td>' .
						'<input id="' . CF7PE_META_PREFIX . 'use_paypal" name="' . CF7PE_META_PREFIX . 'use_paypal" type="checkbox" class="enable_required" value="1" ' . checked( $use_paypal, 1, false ) . '/>' .
					'</td>' .
				'</tr>' .
				'<tr class="form-field">' .
					'<th>' .
						'<label for="' . CF7PE_META_PREFIX . 'mode_sandbox">' .
							__( 'Enable Test API Mode', 'accept-paypal-payments-using-contact-form-7' ) .
						'</label>' .
					'</th>' .
					'<td>' .
						'<input id="' . CF7PE_META_PREFIX . 'mode_sandbox" name="' . CF7PE_META_PREFIX . 'mode_sandbox" type="checkbox" value="1" ' . checked( $mode_sandbox, 1, false ) . ' />' .
					'</td>' .
				'</tr>' .
				'<tr class="form-field">' .
					'<th>' .
						'<label for="' . CF7PE_META_PREFIX . 'sandbox_client_id">' .
							__( 'Sandbox PayPal Client ID (required)', 'accept-paypal-payments-using-contact-form-7' ) .
						'</label>' .
						'<span class="cf7pe-tooltip hide-if-no-js " id="cf7pe-sanbox-client-id"></span>' .
					'</th>' .
					'<td>' .
						'<input id="' . CF7PE_META_PREFIX . 'sandbox_client_id" name="' . CF7PE_META_PREFIX . 'sandbox_client_id" type="text" class="large-text" value="' . esc_attr( $sandbox_client_id ) . '" />' .
					'</td>' .
				'</tr>' .
				'<tr class="form-field">' .
					'<th>' .
						'<label for="' . CF7PE_META_PREFIX . 'sandbox_client_secret">' .
							__( 'Sandbox PayPal Client Secret (required)', 'accept-paypal-payments-using-contact-form-7' ) .
						'</label>' .
					'</th>' .
					'<td>' .
						'<input id="' . CF7PE_META_PREFIX . 'sandbox_client_secret" name="' . CF7PE_META_PREFIX . 'sandbox_client_secret" type="text" class="large-text" value="' . esc_attr( $sandbox_client_secret ) . '" />' .
					'</td>' .
				'</tr>' .
				'<tr class="form-field">' .
					'<th>' .
						'<label for="' . CF7PE_META_PREFIX . 'live_client_id">' .
							__( 'Live PayPal Client ID (required)', 'accept-paypal-payments-using-contact-form-7' ) .
						'</label>' .
						'<span class="cf7pe-tooltip hide-if-no-js" id="cf7pe-paypal-client-id"></span>' .
					'</th>' .
					'<td>' .
						'<input id="' . CF7PE_META_PREFIX . 'live_client_id" name="' . CF7PE_META_PREFIX . 'live_client_id" type="text" class="large-text" value="' . esc_attr( $live_client_id ) . '" ' . ( empty( $mode_sandbox ) && !empty( $use_paypal ) ? 'required' : '' ) . '/>' .
					'</td>' .
				'</tr>' .
				'<tr class="form-field">' .
					'<th>' .
						'<label for="' . CF7PE_META_PREFIX . 'live_client_secret">' .
							__( 'Live PayPal Client Secret (required)', 'accept-paypal-payments-using-contact-form-7' ) .
						'</label>' .
					'</th>' .
					'<td>' .
						'<input id="' . CF7PE_META_PREFIX . 'live_client_secret" name="' . CF7PE_META_PREFIX . 'live_client_secret" type="text" class="large-text" value="' . esc_attr( $live_client_secret ) . '" ' . ( empty( $mode_sandbox ) && !empty( $use_paypal ) ? 'required' : '' ) . '/>' .
					'</td>' .
				'</tr>' .
				'<tr class="form-field">' .
					'<th>' .
						'<label for="' . CF7PE_META_PREFIX . 'amount">' .
							__( 'Amount Field Name (required)', 'accept-paypal-payments-using-contact-form-7' ) .
						'</label>' .
						'<span class="cf7pe-tooltip hide-if-no-js" id="cf7pe-amount-field"></span>' .
					'</th>' .
					'<td>' .
						'<input id="' . CF7PE_META_PREFIX . 'amount" class="form-required-fields" name="' . CF7PE_META_PREFIX . 'amount" type="text" value="' . esc_attr( $amount ) . '" ' . ( !empty( $use_paypal ) ? 'required' : '' ) . '/>' .
					'</td>' .
				'</tr>' .
				'<tr class="form-field">' .
					'<th>' .
						'<label for="' . CF7PE_META_PREFIX . 'quantity">' .
							__( 'Quantity Field Name (Optional)', 'accept-paypal-payments-using-contact-form-7' ) .
						'</label>' .
					'</th>' .
					'<td>' .
						'<input id="' . CF7PE_META_PREFIX . 'quantity" name="' . CF7PE_META_PREFIX . 'quantity" type="text" value="' . esc_attr( $quantity ) . '" />' .
					'</td>' .
				'</tr>' .
				'<tr class="form-field">' .
					'<th>' .
						'<label for="' . CF7PE_META_PREFIX . 'description">' .
							__( 'Description Field Name (Optional)', 'accept-paypal-payments-using-contact-form-7' ) .
						'</label>' .
					'</th>' .
					'<td>' .
						'<input id="' . CF7PE_META_PREFIX . 'description" name="' . CF7PE_META_PREFIX . 'description" type="text" value="' . esc_attr( $description ) . '" />' .
					'</td>' .
				'</tr>' .
	 			'<tr class="form-field">' .
					'<th>' .
						'<label for="' . CF7PE_META_PREFIX . 'currency">' .
							__( 'Select Currency', 'accept-paypal-payments-using-contact-form-7' ) .
						'</label>' .
						'<span class="cf7pe-tooltip hide-if-no-js" id="cf7pe-currency-select"></span>' .
					'</th>' .
					'<td>' .
						'<select id="' . CF7PE_META_PREFIX . 'currency" name="' . CF7PE_META_PREFIX . 'currency">';

							if ( !empty( $currency_code ) ) {
								foreach ( $currency_code as $key => $value ) {
									echo '<option value="' . esc_attr( $key ) . '" ' . selected( $currency, $key, false ) . '>' . esc_attr( $value ) . '</option>';
								}
							}

						echo '</select>' .
					'</td>' .
				'</tr/>' .
				'<tr class="form-field">' .
					'<th>' .
						'<label for="' . CF7PE_META_PREFIX . 'success_returnurl">' .
							__( 'Success Return URL (Optional)', 'accept-paypal-payments-using-contact-form-7' ) .
						'</label>' .
                        '<span class="cf7pe-tooltip hide-if-no-js" id="cf7pe-success-url"></span>'.
					'</th>' .
					'<td>' .
						'<input id="' . CF7PE_META_PREFIX . 'success_returnurl" name="' . CF7PE_META_PREFIX . 'success_returnurl"  type="text" class="regular-text" value="' . esc_attr( $success_returnURL ) . '" />' .
					'</td>' .
				'</tr>' .
				'<tr class="form-field">' .
					'<th>' .
						'<label for="' . CF7PE_META_PREFIX . 'cancel_returnurl">' .
							__( 'Cancel Return URL (Optional)', 'accept-paypal-payments-using-contact-form-7' ) .
						'</label>' .
					'</th>' .
					'<td>' .
						'<input id="' . CF7PE_META_PREFIX . 'cancel_returnurl" name="' . CF7PE_META_PREFIX . 'cancel_returnurl" type="text" class="regular-text" value="' . esc_attr( $cancle_returnURL ) . '" />' .
					'</td>' .
				'</tr>' .
				'<input type="hidden" name="post" value="' . esc_attr( $post_id ) . '">' .
			'</tbody>' .
		'</table>' .
	'</div>' .
	'<div class="right-box">' .
		'<div id="configuration-help" class="postbox">' .
			apply_filters(
				CF7PE_PREFIX . '/postbox',
				'<h3>' . __( 'Do you need help for configuration?', CF7PE_PREFIX ) . '</h3>' .
				'<p></p>' .
				'<ol>' .
					'<li><a href="http://zealousweb.com/wordpress-plugins/docs/accept-paypal-payments-using-contact-form-7.html" target="_blank">Refer the document.</a></li>' .
					'<li><a href="https://www.zealousweb.com/contact/" target="_blank">Contact Us</a></li>' .
					'<li><a href="mailto:opensource@zealousweb.com">Email us</a></li>' .
				'</ol>'
			) .
		'</div>' .
	'</div>' .
'</div>';


add_action('admin_print_footer_scripts', function() {
	ob_start();
	?>
	<script type="text/javascript">
		//<![CDATA[
		jQuery(document).ready( function($) {

			//jQuery selector to point to
			jQuery( '#cf7pe-sanbox-client-id' ).on( 'hover click', function() {
				jQuery( 'body .wp-pointer-buttons .close' ).trigger( 'click' );
				jQuery( '#cf7pe-sanbox-client-id' ).pointer({
					pointerClass: 'wp-pointer cf7pe-pointer',
					content: '<?php
					_e( '<h3>Get your API test credentials</h3>' .
                        '<p>The PayPal Developer site also assigns each sandbox Business account a set of test API credentials. Log in to the PayPal Developer site and navigate to the <a href="https://developer.paypal.com/developer/accounts/" target="_blank">Sandbox Accounts</a> page or <strong>Dashboard > Sandbox > Accounts</strong>. View your test API credentials by clicking the expand icon next to the Business account that you want to use in your request.</br>' .
                        'Then, navigate to the <strong>Profile > API credentials</strong> tab of the sandbox account.</p>' .
						'<p><a href="https://developer.paypal.com/docs/api/sandbox/sb-credentials/" target="_blank">More Info</a></p>',
						'accept-paypal-payments-using-contact-form-7'
					); ?>',
					position: 'left center',
				} ).pointer('open');
			} );

			jQuery( '#cf7pe-paypal-client-id' ).on( 'hover click', function() {
				jQuery( 'body .wp-pointer-buttons .close' ).trigger( 'click' );
				jQuery( '#cf7pe-paypal-client-id' ).pointer({
					pointerClass: 'wp-pointer cf7pe-pointer',
					content: '<?php
					_e( '<h3>Get your REST API credentials</h3>' .
                        '<p>You can view and manage the REST API sandbox and live credentials on the PayPal Developer site <a href="https://developer.paypal.com/developer/applications/" target="_blank"><strong>My Apps & Credentials</strong></a> page. Within the setting for each of your apps, use <strong>Live</strong> toggle in the top right corner of the app settings page to view the API credentials and default PayPal account for each of these environments. If you have not created an app, navigate to the <a href="https://developer.paypal.com/developer/applications/" target="_blank"><strong>My Apps & Credentials</strong></a> page.</p>' .
						'<p><a href="https://developer.paypal.com/docs/api/overview/#get-credentials" target="_blank">More Info</a></p>',
						'accept-paypal-payments-using-contact-form-7'
					); ?>',
					position: 'left center',
				} ).pointer('open');
			} );

			jQuery( '#cf7pe-amount-field' ).on( 'hover click', function() {
				jQuery( 'body .wp-pointer-buttons .close' ).trigger( 'click' );
				jQuery( '#cf7pe-amount-field' ).pointer({
					pointerClass: 'wp-pointer cf7pe-pointer',
					content: '<?php
					_e( '<h3>Amount Field name</h3>' .
                        '<p>Enter the name of the field from where amount value needs to be retrieved. <a href="https://zealousweb.com/wp-content/docs-assets/cf7pap/edit-contact-form-wordpress.png" target="_blank">Screenshot</a></p>',
						'accept-paypal-payments-using-contact-form-7'
					); ?>',
					position: 'left center',
				} ).pointer('open');
			} );

			jQuery( '#cf7pe-currency-select' ).on( 'hover click', function() {
				jQuery( 'body .wp-pointer-buttons .close' ).trigger( 'click' );
				jQuery( '#cf7pe-currency-select' ).pointer({
					pointerClass: 'wp-pointer cf7pe-pointer',
					content: '<?php
					_e( '<h3>Payouts Country and Currency Codes</h3>' .
                        '<p>This currency is supported as a payment currency and a currency balance for in-country PayPal accounts only.</p>' .
						'<p><a href="https://developer.paypal.com/docs/api/reference/currency-codes/" target="_blank">More Info</a></p>',
						'accept-paypal-payments-using-contact-form-7'
					); ?>',
					position: 'left center',
				} ).pointer('open');
			} );

            jQuery( '#cf7pe-success-url' ).on( 'hover click', function() {
                jQuery( 'body .wp-pointer-buttons .close' ).trigger( 'click' );
                jQuery( '#cf7pe-success-url' ).pointer({
                    pointerClass: 'wp-pointer cf7pe-pointer',
                    content: '<?php
                        _e( '<h3>Auto redirect on success payment</h3>' .
                            '<p>1) Go to the PayPal website and log in to your account.</p>'.
                            '<p>2) Click "Profile" at the top of the page.</p>'.
                            '<p>3) Click "Website Payments" at the sidebar of the page.</p>'.
                            '<p>4) Click "Website Preferences".</p>'.
                            '<p>5) Click the Auto Return "On" button.</p>'.
                            '<p>6) Review the Return URL Requirements.</p>'.
                            '<p>7) Enter the Return URL.</p>'.
                            '<p>8) Click the Payment data transfer "On".</p>'.
                            '<p>9) Click "Save".</p>'.
                            '<p><a href="https://zealousweb.com/wp-content/docs-assets/cf7pap/paypal-auto-redirect-settings.png" target="_blank">Screenshots</a></p>',
                            'accept-paypal-payments-using-contact-form-7'
                        ); ?>',
                    position: 'left center',
                } ).pointer('open');
            } );

		} );
		//]]>
	</script>
	<?php
	echo ob_get_clean();
} );
