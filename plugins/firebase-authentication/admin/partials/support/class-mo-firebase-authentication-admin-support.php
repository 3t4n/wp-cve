<?php
/**
 * Firebase Authentication Support
 *
 * @package firebase-authentication
 */

/**
 * Renders support form
 */
class Mo_Firebase_Authentication_Admin_Support {

	/**
	 * Display contact us form
	 *
	 * @return void
	 */
	public static function mo_firebase_authentication_support() {
		?>
		<div class="col-md-12" style="padding: 5px;">
			<div class="mo_firebase_auth_card" style="width:90%" >
				<h3 style="margin: 10px 0;">Contact us</h3>
				<p>Need any help?<br>Just send us a query so we can help you.</p>
				<form action="" method="POST">
					<table class="mo_settings_table">
					<?php wp_nonce_field( 'mo_firebase_auth_contact_us_form', 'mo_firebase_auth_contact_us_field' ); ?>
					<input type="hidden" name="option" value="mo_firebase_auth_contact_us">
					<tr>
							<td><input style="width:95%;" type="email" placeholder="Enter email here"  name="mo_firebase_auth_contact_us_email" id="mo_firebase_auth_contact_us_email" required></td>
						</tr><tr><td></td></tr>
						<tr>
							<td><input style="width:95%;" type="tel" id="mo_firebase_auth_contact_us_phone" pattern="[\+]\d{11,14}|[\+]\d{1,4}[\s]\d{9,10}" placeholder="Enter phone here" name="mo_firebase_auth_contact_us_phone"></td>
						</tr><tr><td></td></tr>
						<tr>
							<td><textarea style="width:95%;" name="mo_firebase_auth_contact_us_query" placeholder="Enter query here" rows="5" id="mo_firebase_auth_contact_us_query" required></textarea></td>
						</tr><tr><td>
					<input type="submit" class="button button-primary button-large" style="width:100px; margin: 15px 0;" value="Submit"></td></tr>
					</table>
				</form>
				<p style="padding-right: 8%;">If you want custom features in the plugin, just drop an email at <a href="mailto:info@xecurify.com">info@xecurify.com</a></p>
			</div>
		</div>
		<div class="firestore_adv">
			<div class="firestore_adv_row">
				<div class="col-md-2 text-center">
				<img src="<?php echo esc_url( MO_FIREBASE_AUTHENTICATION_URL . 'public/images/mini.png' ); ?>" width="50px">
				</div>
				<div class="col-md-10 text-dark">
					<h5><a href="https://plugins.miniorange.com/woocommerce-cloud-firestore-integration" target="_blank" style="text-decoration: none;">WordPress Firestore Integrator </a></h5>
					<h6>
						<span class="h5 ">By</span><span><a rel="nofollow" href="https://miniorange.com/" target="_blank" class="firestore_adv_url">&nbsp;miniOrange</a></span>
					</h6>
					<hr class="firestore_adv_hr"/>
				</div>
			</div>
			<p class="mt-2 pl-2 firestore_adv_content">
				WordPress Firestore integrator plugin allows you to sync your WordPress data into Firebase Cloud Firestore. The cloud firestore data can also be displayed on your WordPress site.
			</p>
			<p style="margin-left: 4%;"> Please reach out to us at <a href="mailto:info@xecurify.com">info@xecurify.com</a> to discuss the integration you want between your WordPress site and Firestore.</p>
		</div>

		<script>
			jQuery("#mo_firebase_auth_contact_us_phone").intlTelInput();

		</script>
		<?php
	}
}
