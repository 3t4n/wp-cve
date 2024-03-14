<?php
/**
 * The password protected pop-up modal.
 *
 * @version 1.8.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="masteriyo-course-password-protected-modal masteriyo-hidden" id="masteriyoCoursePasswordProtectedModal">
	<div class="masteriyo-overlay">
		<div class="masteriyo--modal masteriyo-course-password-protected-modal-content">
			<h4 class="masteriyo--title"><?php esc_html_e( 'Course Access', 'masteriyo' ); ?></h4>
			<div class="masteriyo--content">
			<p><?php esc_html_e( 'This course is password protected. To access it please enter your password below:', 'masteriyo' ); ?></p>
				<p id="passwordError" class="error-message"></p> 
				<form>
					<p>
						<label for="masteriyoPostPassword"> <?php esc_html_e( 'Password', 'masteriyo' ); ?>
							<input name="post_password" id="masteriyoPostPassword" type="password" size="20" />
						</label>
					</p>
				</form>
			</div>
			<div class="masteriyo-actions">
				<button class="masteriyo-btn masteriyo-btn-outline masteriyo-cancel"><?php esc_html_e( 'Cancel', 'masteriyo' ); ?></button>
				<button class="masteriyo-btn masteriyo-btn-warning masteriyo-submit" data-loading-text="<?php esc_html_e( 'Verifying...', 'masteriyo' ); ?>"><?php esc_html_e( 'Verify', 'masteriyo' ); ?></button>
			</div>
		</div>
	</div>
</div>
