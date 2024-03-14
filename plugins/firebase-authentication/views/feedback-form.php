<?php
/**
 * Feedback Form File
 *
 * @package firebase-authentication
 */

/**
 * Control to display feedback form while deactivation
 *
 * @return void
 */
function mo_firebase_auth_display_feedback_form() {

	if ( isset( $_SERVER['PHP_SELF'] ) && 'plugins.php' !== basename( sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) ) ) {
		return;
	}
	$deactivate_reasons = array(
		"Does not have the features I'm looking for",
		'Confusing Interface',
		'Bugs in the plugin',
		'Unable to register',
		'Other Reasons',
	);
	wp_enqueue_style( 'wp-pointer' );
	wp_enqueue_script( 'wp-pointer' );
	wp_enqueue_script( 'utils' );
	wp_enqueue_style( 'mo_firebase_auth_admin_settings_style', MO_FIREBASE_AUTHENTICATION_URL . 'admin/css/style.min.css', array(), MO_FIREBASE_AUTHENTICATION_VERSION, false );

	?>
	</head>
	<body>
	<div id="firebase_auth_feedback_modal" class="mo_fb_modal">
		<div class="mo_fb_modal-content">
			<span class="mo_fb_close">&times;</span>
			<h3>Tell us what happened? </h3>
			<form name="f" method="post" action="" id="mo_firebase_auth_feedback">
				<?php wp_nonce_field( 'mo_firebase_auth_feedback_form', 'mo_firebase_auth_feedback_field' ); ?>
				<input type="hidden" name="option" value="mo_firebase_auth_feedback"/>
				<div>
					<p style="margin-left:2%">
				<?php
				foreach ( $deactivate_reasons as $deactivate_reason ) {
					?>
					<div class="radio" style="padding:1px;margin-left:2%">
						<label style="font-weight:normal;font-size:14.6px" for="<?php echo esc_attr( $deactivate_reason ); ?>">
							<input type="radio" style="opacity:1;" name="deactivate_reason_radio" value="<?php echo esc_attr( $deactivate_reason ); ?>" required>
							<?php echo esc_attr( $deactivate_reason ); ?></label>
					</div>
					<?php } ?>
					<br>
					<textarea id="query_feedback" name="query_feedback" rows="4" style="margin-left:2%;width: 330px" placeholder="Write your query here"></textarea>
					<br><br>
					<div class="mo_fb_modal-footer">
						<input type="submit" name="miniorange_firebase_feedback_submit" class="button button-primary button-large" style="float: left;" value="Submit"/>
						<input id="mo_fb_skip" type="submit" name="miniorange_firebase_feedback_skip" class="button button-primary button-large" style="float: right;" value="Skip"/>
					</div>
				</div>
			</form>
			<form name="f" method="post" action="" id="mo_fb_feedback_form_close">
				<input type="hidden" name="option" value="mo_firebase_auth_skip_feedback"/>
				<?php wp_nonce_field( 'mo_firebase_auth_skip_feedback_form', 'mo_firebase_auth_skip_feedback_form_nonce' ); ?>
			</form>
		</div>
	</div>
	<script>
		jQuery('a[aria-label="Deactivate Firebase Authentication"]').click(function () {
			var mo_fb_modal = document.getElementById('firebase_auth_feedback_modal');
			var mo_fb_skip = document.getElementById('mo_fb_skip');
			var span = document.getElementsByClassName("mo_fb_close")[0];
			mo_fb_modal.style.display = "block";
			jQuery('input:radio[name="deactivate_reason_radio"]').click(function () {
				var reason = jQuery(this).val();
				var query_feedback = jQuery('#query_feedback');
				query_feedback.removeAttr('required')

				if ( reason === "Does not have the features I'm looking for" ) {
					query_feedback.attr( "placeholder", "Let us know what feature are you looking for" );

				} else if ( reason === "Other Reasons:" ) {
					query_feedback.attr( "placeholder", "Can you let us know the reason for deactivation" );
					query_feedback.prop( 'required', true );

				} else if ( reason === "Bugs in the plugin" ) {
					query_feedback.attr( "placeholder", "Can you please let us know about the bug in detail?" );

				} else if ( reason === "Confusing Interface" ) {
					query_feedback.attr( "placeholder", "Finding it confusing? let us know so that we can improve the interface" );

				} else if ( reason === "Unable to register" ) {
					query_feedback.attr( "placeholder", "Error while creating a new account? Can you please let us know the exact error?" );

				}
			});

			span.onclick = function () {
				mo_fb_modal.style.display = "none";
			}
			mo_fb_skip.onclick = function() {
				mo_fb_modal.style.display = "none";
				jQuery('#mo_fb_feedback_form_close').submit();
			}

			window.onclick = function (event) {
				if ( event.target == mo_fb_modal ) {
					mo_fb_modal.style.display = "none";
				}
			}
			return false;

		});
	</script><?php
}
?>
