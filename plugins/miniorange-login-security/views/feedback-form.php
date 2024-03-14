<?php
/**
 * This File contains Feedback form UI.
 *
 * @package miniorange-login-security/views
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
if ( 'plugins.php' !== basename( isset( $_SERVER['PHP_SELF'] ) ? sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) : '' ) ) {
	return;
}
global $momlsdb_queries;
$mo2f_configured_2_f_a_method = $momlsdb_queries->momls_get_user_detail( 'mo2f_configured_2FA_method', get_current_user_id() );
$deactivate_reasons           = array(
	'Temporary deactivation - Testing',
	'Upgrading to Premium',
	'Conflicts with other plugins',
	'Redirecting back to login page after Authentication',
);
if ( strlen( $mo2f_configured_2_f_a_method ) ) {
	array_push( $deactivate_reasons, "Couldn't understand how to make it work" );
} elseif ( strpos( $mo2f_configured_2_f_a_method, 'Google Authenticator' ) !== false ) {
	array_push( $deactivate_reasons, 'Unable to verify Google Authenticator' );
}

array_push( $deactivate_reasons, 'Other Reasons:' );
?>

	</head>
	<body>


<!-- The Modal -->
<div id="momls_wpns_feedback_modal" class="mo_modal">

	<!-- Modal content -->
	<div class="momls_wpns_modal-content">
		<h3>
			<b>Your feedback</b>
			<span class="momls_wpns_close">&times;</span>
		</h3>
		<hr>
		<form name="f" method="post" action="" id="momls_wpns_feedback">
		<input type="hidden" id="momls_wpns_feedback_nonce" name="momls_wpns_feedback_nonce" value="<?php echo esc_attr( wp_create_nonce( 'mo-wpns-feedback-nonce' ) ); ?>"/>
			<input type="hidden" name="option" value="momls_wpns_feedback"/>
			<div>
				<h4>Please help us to improve our plugin by giving your
					opinion.<br></h4>

			</div>
			<div class="mo2f_feedback_text">
				<span id="mo2f_link_id"></span>
				<?php

				foreach ( $deactivate_reasons as $deactivate_reason ) {
					?>

					<div>
						<label
								for="<?php echo esc_attr( $deactivate_reason ); ?>">
							<input type="radio" name="momls_wpns_deactivate_plugin"
								value="<?php echo esc_attr( $deactivate_reason ); ?>"
								required>
							<?php echo esc_html( $deactivate_reason ); ?>
							<?php if ( 'Conflicts with other plugins' === $deactivate_reason ) { ?>
								<div id="momls_wpns_other_plugins_installed">
									<?php
									MOmls_Utility::momls_get_all_plugins_installed();
									?>
								</div>
							<?php } ?>

						</label>
					</div>


				<?php } ?>
				<br>
				<textarea id="wpns_query_feedback" name="wpns_query_feedback" rows="4" cols="50"
						placeholder="Write your query here"></textarea>
				<div class="mo2f_modal-footer">
					<div>
						<input type="checkbox" name="mo2f_get_configuration" value="reply" checked>
						<label for="mo2f_get_configuration">Send Plugin Configuration</label>
						</input>
					</div>
					<br/>
					<input type="submit" name="miniorange_feedback_submit"
						class="button button-primary button-large" style="float:left" value="Submit"/>
					<input type="button" name="miniorange_feedback_skip"
						class="button button-primary button-large" style="float:right" value="Skip & Deactivate"
						onclick="document.getElementById('momls_wpns_feedback_form_close').submit();"/>
				</div>
				<br><br><br>
			</div>
		</form>
		<form name="f" method="post" action="" id="momls_wpns_feedback_form_close">
		<input type="hidden" id="momls_wpns_feedback_nonce" name="momls_wpns_feedback_nonce" value="<?php echo esc_attr( wp_create_nonce( 'mo-wpns-feedback-nonce' ) ); ?>"/>
			<input type="hidden" name="option" value="momls_wpns_skip_feedback"/>
		</form>

	</div>

</div>
<script>
	var label = document.getElementById('deactivate-miniorange-login-security').getAttribute("aria-label");
	var message =      {'NOT_CREATE_ACCOUNT':'Did not want to create an account',
						'UPGRADING_PREMIUM':'Upgrading to Premium',
						'CONFLICT_WITH_PLUGINS':'Conflicts with other plugins',
						'OTHER_REASONS':'Other Reasons:'  };

	plugin_active_label = 'a[aria-label="' + label + '"]';
	jQuery('#momls_wpns_other_plugins_installed').hide();
	jQuery(plugin_active_label).click(function () {
		var mo_modal = document.getElementById('momls_wpns_feedback_modal');
		var span = document.getElementsByClassName("momls_wpns_close")[0];
		// When the user clicks the button, open the mo2f_modal
		mo_modal.style.display = "block";
		jQuery('input:radio[name="momls_wpns_deactivate_plugin"]').click(function () {
			var reason = jQuery(this).val();
			jQuery('#wpns_query_feedback').removeAttr('required');
			if (reason === message['NOT_CREATE_ACCOUNT']  ) {
				jQuery('#momls_wpns_other_plugins_installed').hide();
				jQuery('#wpns_query_feedback').attr("placeholder", "Write your query here.");
				jQuery('#mo2f_link_id').html('<p>We suggest you to create an account for only those methods which require miniOrange cloud for working purpose.</p>');
				jQuery('#mo2f_link_id').show();
			} else if (reason === message['UPGRADING_PREMIUM']  ) {
				jQuery('#momls_wpns_other_plugins_installed').hide();
				jQuery('#wpns_query_feedback').attr("placeholder", "Write your query here.");
				jQuery('#mo2f_link_id').html('<p>Thanks for upgrading. For setup instructions, please follow this guide' +
					', <a href="<?php echo esc_attr( Momls_Wpns_Constants::SETUPGUIDE ); ?>" target="_blank"><b>VIDEO GUIDE.</b></a></p>');
				jQuery('#mo2f_link_id').show();
			} else if (reason === message['CONFLICT_WITH_PLUGINS']  ) {
				jQuery('#wpns_query_feedback').attr("placeholder", "Can you please mention the plugin name, and the issue?");
				jQuery('#momls_wpns_other_plugins_installed').show();
				jQuery('#mo2f_link_id').hide();
			} else if (reason === message['OTHER_REASONS']  ) {
				jQuery('#momls_wpns_other_plugins_installed').hide();
				jQuery('#wpns_query_feedback').attr("placeholder", "Can you let us know the reason for deactivation");
				jQuery('#wpns_query_feedback').prop('required', true);
				jQuery('#mo2f_link_id').hide();
			} else {
				jQuery('#momls_wpns_other_plugins_installed').hide();
				jQuery('#wpns_query_feedback').attr("placeholder", "Write your query here.");
				jQuery('#mo2f_link_id').hide();
			}
		});

		span.onclick = function () {
			mo_modal.style.display = "none";
		}

		// When the user clicks anywhere outside of the mo2f_modal, mo2f_close it
		window.onclick = function (event) {
			if (event.target === mo_modal) {
				mo_modal.style.display = "none";
			}
		}
		return false;

	});
</script>
