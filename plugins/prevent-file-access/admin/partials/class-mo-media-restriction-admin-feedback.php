<?php
/**
 * Feedback form file.
 *
 * @package    Media_Restriction
 * @subpackage Media_Restriction/admin/partials
 */

/**
 * Class for admin feedback form.
 */
class Mo_Media_Restriction_Admin_Feedback {
	/**
	 * Display feedback form.
	 *
	 * @return mixed
	 */
	static function mo_media_restriction_display_feedback_form() { //phpcs:ignore Squiz.Scope.MethodScope.Missing -- It only visible on deactivation.
		if ( ! empty( $_SERVER['PHP_SELF'] ) && 'plugins.php' !== basename( sanitize_text_field( wp_unslash( $_SERVER['PHP_SELF'] ) ) ) ) {
			return;
		}

		$deactivate_reasons = array( "Does not have the features I'm looking for", 'Do not want to upgrade to Premium version', 'Confusing Interface', 'Bugs in the plugin', 'Unable to register', 'Other Reasons' );
		wp_enqueue_style( 'wp-pointer' );
		wp_enqueue_script( 'wp-pointer' );
		wp_enqueue_script( 'utils' );

		?>
		<style>
			.mo_media_restriction_modal {
				display: none;
				position: fixed;
				z-index: 1;
				padding-top: 100px;
				left: 100px;
				top: 0;
				margin-left:220px;
				width: 50%;
				height: 100%;

			}

			.mo_media_restriction_modal-demo {
				background-color: #fefefe;
				margin: auto;
				padding: 20px;
				border: 1px solid #888;
				width: auto;
			}

			.mo_media_restriction_modal-content {
				background-color: #fefefe;
				margin: auto;
				padding: 20px;
				border: 1px solid #888;
				width: 55%;
			}

			.mo_media_restriction_modal-footer {
				padding: 15px;
				text-align: right;
				border-top: 1px solid #e5e5e5;
			}
			.mo_media_restriction_modal-footer .btn + .btn {
				margin-left: 5px;
				margin-bottom: 0;
			}
			.mo_media_restriction_modal-footer .btn-group .btn + .btn {
				margin-left: -1px;
			}
			.mo_media_restriction_modal-footer .btn-block + .btn-block {
				margin-left: 0;
			}
			.mo_media_restriction_modal-footer::after {
				content: "";
				clear: both;
				display: table;
			}
			.mo_media_restriction_feedback_close {
				float: right;
				font-size: 21px;
				font-weight: bold;
				line-height: 1;
				color: #000000;
				text-shadow: 0 1px 0 #212121;
				opacity: 0.5;
				filter: alpha(opacity=50);
			}
			.mo_media_restriction_feedback_close:hover,
			.mo_media_restriction_feedback_close:focus {
				color: #000000;
				text-decoration: none;
				cursor: pointer;
				opacity: 0.8;
				filter: alpha(opacity=80);
			}
		</style>
		</head>
		<body>
		<div id="mo_media_restriction_feedback_modal" class="mo_media_restriction_modal">
			<div class="mo_media_restriction_modal-content">
				<span class="mo_media_restriction_feedback_close">&times;</span>
				<h3>Tell us what happened? </h3>
				<form name="f" method="post" action="" id="mo_media_restriction_feedback">
					<input type="hidden" name="option" value="mo_media_restriction_feedback"/>
					<?php wp_nonce_field( 'mo_media_restriction_feedback_form', 'mo_media_restriction_feedback_fields' ); ?>
					<div>
					<?php
					foreach ( $deactivate_reasons as $deactivate_reason ) {
						?>
						<div class="radio" style="padding:1px;margin-left:2%">
							<label style="font-weight:normal;font-size:14.6px" for="<?php echo esc_attr( $deactivate_reason ); ?>">
								<input type="radio" name="mo_media_restriction_deactivate_reason_radio" value="<?php echo esc_attr( $deactivate_reason ); ?>"
								required>
								<?php echo esc_attr( $deactivate_reason ); ?></label>
						</div>
						<?php } ?>
						<br>
						<textarea id="query_feedback" name="mo_media_restriction_query_feedback" rows="4" style="margin-left:2%;width: 330px"
						placeholder="Write your query here"></textarea>
						<br><br>
						<div class="mo_media_restriction_modal-footer">
							<input type="submit" name="miniorange_feedback_submit"
								class="button button-primary button-large" style="float: left;" value="Submit"/>
							<input id="mo_skip" type="submit" name="miniorange_feedback_skip"
								class="button button-primary button-large" style="float: right;" value="Skip"/>
						</div>
					</div>
				</form>
				<form name="f" method="post" action="" id="mo_feedback_form_close">
					<?php wp_nonce_field( 'mo_media_restriction_skip_feedback_form', 'mo_media_restriction_skip_feedback_form_fields' ); ?>
					<input type="hidden" name="option" value="mo_media_restriction_skip_feedback"/>
				</form>
			</div>
		</div>
		<script>
			jQuery('a[aria-label="Deactivate Prevent Files / Folders Access"]').click(function () {
				var mo_media_restriction_modal = document.getElementById('mo_media_restriction_feedback_modal');
				var mo_skip = document.getElementById('mo_skip');
				var span = document.getElementsByClassName("mo_media_restriction_feedback_close")[0];
				mo_media_restriction_modal.style.display = "block";
				jQuery('input:radio[name="mo_media_restriction_deactivate_reason_radio"]').click(function () {
					var reason = jQuery(this).val();
					var query_feedback = jQuery('#query_feedback');
					query_feedback.removeAttr('required')

					if (reason === "Does not have the features I'm looking for") {
						query_feedback.attr("placeholder", "Let us know what feature are you looking for");	
					} else if (reason === "Other Reasons") {
						query_feedback.attr("placeholder", "Can you let us know the reason for deactivation");
						query_feedback.prop('required', true);

					} else if (reason === "Bugs in the plugin") {
						query_feedback.attr("placeholder", "Can you please let us know about the bug in detail?");

					} else if (reason === "Confusing Interface") {
						query_feedback.attr("placeholder", "Finding it confusing? let us know so that we can improve the interface");

					} else if (reason === "Unable to register") {
						query_feedback.attr("placeholder", "Error while creating a new account? Can you please let us know the exact error?");

					}
				});


				span.onclick = function () {
					mo_media_restriction_modal.style.display = "none";
					jQuery('#mo_feedback_form_close').submit();
				}

				window.onclick = function (event) {
					if (event.target == mo_media_restriction_modal) {
						mo_media_restriction_modal.style.display = "none";
					}
				}

				mo_skip.onclick = function() {
					mo_media_restriction_modal.style.display = "none";
					jQuery('#mo_feedback_form_close').submit();
				}
				return false;

			});
		</script>
		<?php
	}
}
