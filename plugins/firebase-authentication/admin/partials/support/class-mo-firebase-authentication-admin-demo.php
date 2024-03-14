<?php
/**
 * Firebase Authentication Demo Request
 *
 * @package firebase-authentication
 */

/**
 * Class to implement demo request
 */
class Mo_Firebase_Authentication_Admin_Demo {

	/**
	 * Caller to demo request form
	 *
	 * @return void
	 */
	public static function mo_firebase_authentication_handle_demo() {
		self::demo_request();
	}

	/**
	 * Display demo request form
	 *
	 * @return void
	 */
	public static function demo_request() {

		?>
		<div class="row">
		<div class="col-md-12">
		<div class="mo_firebase_auth_card" style="width:100%">
			<h3><?php esc_html_e( 'Request for Demo', 'firebase-authentication' ); ?></h3><hr>
			<p>
		<?php esc_html_e( "Interested in testing out the paid features before purchasing? Let us know the plan you're considering, and we'll set up a demo for you.", 'firebase-authentication' ); ?></p>
			<form method="post" action="">
				<input type="hidden" name="option" value="mo_fb_demo_request_form" />
			<?php wp_nonce_field( 'mo_fb_demo_request_form', 'mo_fb_demo_request_field' ); ?>				
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-3">
						<p style="margin-bottom: 3px"><strong><p style="display:inline;color:red;">*</p>Email ID: </strong></p>
					</div>
					<div class="col-md-6">
					<input required type="email" name="mo_auto_create_demosite_email" placeholder="We will use this email to setup the demo for you"style="width:80%; font-size: 14px;" value="">
					</div>
				</div>
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-3">
						<p style="margin-bottom: 3px"><strong><p style="display:inline;color:red;">*</p>Request a demo for: </strong></p>
					</div>
					<div class="col-md-6">
					<select required name="mo_auto_create_demosite_demo_plan"id="mo_fb_demo_plan" style="width:80%; font-size: 14px;" >
						<option disabled selected>------------------ Select ------------------</option>
						<option value="miniorange-firebase-authentication-enterprise@22.0.3">WP Firebase Authentication Enterprise Plan</option>
						<option value="miniorange-firebase-authentication-premium@12.0.3">WP Firebase Authentication Premium Plan</option>
						<option value="Not Sure">Not Sure</option>
					</select>
					</div>
				</div>
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-3">
						<p style="margin-bottom: 3px"><strong><p style="display:inline;color:red;">*</p>Usecase: </strong></p>
					</div>
					<div class="col-md-6">
					<textarea type="text" minlength="15" name="mo_auto_create_demosite_usecase" rows="4"placeholder="Example: WooCommerce Integration, WP Login with Firebase credentials for my Mobile App users, Firebase social login for my WordPress, etc" style="width:80%; font-size: 14px;" required value=""></textarea>
					</div>
				</div><br>
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-8" style="background-color: #e5f4ff;border-radius: 10px;padding: 13px 18px;"><br>
						<input value="true" name="mo_auto_create_demosite_firestore_integrator_check" type="checkbox" id="mo_auto_create_demosite_firestore_integrator_check" checked>
						<div style="display:inline">
							<div style="display:inline-block;padding:0px 10px 10px 0px">
								<strong>Enable Firestore Integrator Addon on Demo Site</strong>
							</div>
							<p>
								Our new launched solution helps you sync WordPress data to Cloud Firestore Collections.
								<br><br><strong>Note:</strong> You have to select a plan in the above 'Request a demo for' field as this addon works along with the Firebase Authentication plugin.
							</p>				
						</div>
					</div>
				</div>
				<br>
				<div class="row">
					<div class="col-md-1"></div>
					<div class="col-md-3"></div>
					<div class="col-md-6">
						<input type="submit" style="text-align:center; font-size: 14px; font-weight: 400;" class="button button-primary button-large" name="submit" value="Submit Demo Request" ><br>
					</div>
				</div>
				<br>
			</form>
		</div>
		</div>
		</div>
		<?php
	}
}


