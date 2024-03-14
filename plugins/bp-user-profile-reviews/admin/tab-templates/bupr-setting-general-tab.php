<?php
/**
 * BuddyPress Member Review general tab.
 *
 * @package BuddyPress_Member_Reviews
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/* admin setting on dashboard */
global $bupr;
$message = 'Hello [user-name], <br><br>
We are pleased to inform you that [reviewer-name] has recently reviewed your profile.<br><br>
To view the review, simply click on the link below:<br>
[review-link]<br><br>
Best regards,<br>
The [site-name] Team';
// get all user for exclude for review.
$user_roles = array_reverse( get_editable_roles() );
$review_email_subject = ( isset( $bupr['bupr_review_email_subject'] ) ) ? $bupr['bupr_review_email_subject'] : 'New Review on Your Profile at [site-name]';
$review_email_message = ( isset( $bupr['bupr_review_email_message'] ) ) ? $bupr['bupr_review_email_message'] : $message;

?>
<div class="wbcom-tab-content">
	<div class="wbcom-wrapper-admin">
		<div class="wbcom-admin-title-section">
			<h3><?php esc_html_e( 'General Settings', 'bp-member-reviews' ); ?></h3>
		</div>
		<div class="wbcom-admin-option-wrap wbcom-admin-option-wrap-view">
			<form method="post" action="options.php">
				<?php
				settings_fields( 'bupr_admin_general_options' );
				do_settings_sections( 'bupr_admin_general_options' );
				?>
				<div class="form-table">
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label for="bupr-multi-review">
									<?php esc_html_e( 'Multiple Reviews', 'bp-member-reviews' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Enable this option, if you want to add functionality for a user to send multiple reviews to same user.', 'bp-member-reviews' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<label class="bupr-switch">
									<input name="bupr_admin_general_options[bupr_multi_reviews]" type="checkbox" id="bupr-multi-review" <?php checked( esc_attr( $bupr['multi_reviews'] ), 'yes' ); ?> value="yes">
									<div class="bupr-slider bupr-round"></div>
								</label>
							</div>
						</div>
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label for="bupr-hide-review-button">
									<?php esc_html_e( 'Show Review Button', 'bp-member-reviews' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Enable this option if you want to show the Add Review button on member profile header.', 'bp-member-reviews' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<label class="bupr-switch">
									<input name="bupr_admin_general_options[bupr_hide_review_button]" type="checkbox" id="bupr-hide-review-button" <?php checked( esc_attr( $bupr['hide_review_button'] ), 'yes' ); ?> value="yes">
									<div class="bupr-slider bupr-round"></div>
								</label>
							</div>
						</div>
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label for="bupr_review_auto_approval">
									<?php esc_html_e( 'Auto approve reviews ', 'bp-member-reviews' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Enable this option, if you want to have the reviews automatically approved, else manual approval will be required.', 'bp-member-reviews' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<label class="bupr-switch">
									<input type="checkbox" id="bupr_review_auto_approval" name="bupr_admin_general_options[bupr_auto_approve_reviews]" <?php checked( esc_attr( $bupr['auto_approve_reviews'] ), 'yes' ); ?> value="yes">
									<div class="bupr-slider bupr-round"></div>
								</label>
							</div>
						</div>
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label for="bupr_member_dir_reviews">
									<?php esc_html_e( 'Show ratings in member directory', 'bp-member-reviews' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Enable this option, if you want to show ratings at member directory page.', 'bp-member-reviews' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<label class="bupr-switch">
									<input type="checkbox" id="bupr_member_dir_reviews" name="bupr_admin_general_options[bupr_member_dir_reviews]" <?php checked( esc_attr( $bupr['dir_view_ratings'] ), 'yes' ); ?> value="yes">
									<div class="bupr-slider bupr-round"></div>
								</label>
							</div>
						</div>
						<!-- <div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label for="bupr_member_dir_add_reviews">
									<?php // esc_html_e( 'Add view review link at member directory', 'bp-member-reviews' ); ?>
								</label>
								<p class="description"><?php // esc_html_e( 'Enable this option for Add Review link at member directory.', 'bp-member-reviews' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<label class="bupr-switch">
									<input type="checkbox" id="bupr_member_dir_add_reviews" name="bupr_admin_general_options[bupr_member_dir_add_reviews]" <?php // checked( esc_attr( $bupr['dir_view_review_btn'] ), 'yes' ); ?> value="yes">
									<div class="bupr-slider bupr-round"></div>
								</label>
							</div>
						</div> -->
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label for="bupr_allow_email">
									<?php esc_html_e( 'Emails ', 'bp-member-reviews' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Enable this option, if you want the member to receive an email when someone adds review in their profile.', 'bp-member-reviews' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<label class="bupr-switch">
									<input type="checkbox" id="bupr_allow_email" name="bupr_admin_general_options[bupr_allow_email]" <?php checked( esc_attr( $bupr['allow_email'] ), 'yes' ); ?> value="yes">
									<div class="bupr-slider bupr-round"></div>
								</label>
							</div>
						</div>
						<div class="bgr-row wbcom-settings-section-wrap review-deny-email-section">
							<div class="wbcom-settings-section-options-heading">
								<label>
									<?php esc_html_e( 'Email Subject', 'bp-group-reviews' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Please add review email subject.', 'bp-group-reviews' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<input id="review_deny_email_subject" class="large-text" name="bupr_admin_general_options[review_email_subject]" value="<?php echo esc_attr( $review_email_subject ); ?>" type="text" placeholder="Please enter review email subject.">
							</div>
						</div>
						<div class="bgr-row wbcom-settings-section-wrap review-deny-email-section">
					<div class="wbcom-settings-section-options-heading">
						<label>
							<?php esc_html_e( 'Email Message', 'bp-group-reviews' ); ?>
						</label>
						<p class="description"><?php esc_html_e( 'Please add review email message.', 'bp-group-reviews' ); ?></p>
					</div>
					<div class="wbcom-settings-section-options">
						<?php
						wp_editor(
							$review_email_message,
							'review-email-message',
							array(
								'media_buttons' => false,
								'textarea_name' => 'bupr_admin_general_options[review_email_message]',
							)
						);
						?>
					</div>
				</div>
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label for="bupr_review_notification">
									<?php esc_html_e( 'BuddyPress Notifications', 'bp-member-reviews' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Enable this option, if you want the member to receive a BuddyPress Notification when someone adds a review in their profile.', 'bp-member-reviews' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<label class="bupr-switch">
									<input type="checkbox" id="bupr_review_notification" name="bupr_admin_general_options[bupr_allow_notification]" <?php checked( esc_attr( $bupr['allow_notification'] ), 'yes' ); ?> value="yes">
									<div class="bupr-slider bupr-round"></div>
								</label>
							</div>
						</div>
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label for="bupr_review_update">
									<?php esc_html_e( 'Update Review', 'bp-member-reviews' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Enable this option, if you want the members update/modify their reviews.', 'bp-member-reviews' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<label class="bupr-switch">
									<input type="checkbox" id="bupr_review_update" name="bupr_admin_general_options[bupr_allow_update]" <?php checked( esc_attr( $bupr['allow_update'] ), 'yes' ); ?> value="yes">
									<div class="bupr-slider bupr-round"></div>
								</label>
							</div>
						</div>
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label for="profile_reviews_per_page">
									<?php esc_html_e( 'Reviews pages show at most', 'bp-member-reviews' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'This option lets you limit number of reviews in Member Reviews page.', 'bp-member-reviews' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<input id="profile_reviews_per_page" class="small-text" name="bupr_admin_general_options[profile_reviews_per_page]" step="1" min="1" value="<?php echo esc_attr( $bupr['reviews_per_page'] ); ?>" type="number">
								<?php esc_html_e( 'Reviews', 'bp-member-reviews' ); ?>
							</div>
						</div>
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label for="bupr_exc_member">
									<?php esc_html_e( 'Select member roles to write reviews', 'bp-member-reviews' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Select user roles which have only right to give review.', 'bp-member-reviews' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<select name="bupr_admin_general_options[bupr_exc_member][]" id="bupr_exc_member" class="bupr_excluding_member" multiple>
									<?php
									foreach ( $user_roles as $role => $details ) {
										$name = translate_user_role( $details['name'] );
										if ( ! empty( $bupr['exclude_given_members'] ) ) {
											if ( in_array( $role, $bupr['exclude_given_members'] ) ) {
												?>
													<option value="<?php echo esc_attr( $role ); ?>" <?php echo 'selected = "selected"'; ?>><?php echo esc_html( $name ); ?></option>
												<?php
											} else {
												?>
													<option value='<?php echo esc_attr( $role ); ?>'><?php echo esc_html( $name ); ?></option>
												<?php
											}
										} else {
											?>
												<option value="<?php echo esc_attr( $role ); ?>"><?php echo esc_html( $name ); ?></option>
											<?php
										}
									}
									?>
								</select>
							</div>
						</div>
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label for="bupr_exc_member">
									<?php esc_html_e( 'User roles to accept reviews', 'bp-member-reviews' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Select user roles which have you want to give review.', 'bp-member-reviews' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<select name="bupr_admin_general_options[bupr_add_member][]" id="bupr_add_member" class="bupr_adding_member" multiple>
									<?php
									foreach ( $user_roles as $role => $details ) {
										$name = translate_user_role( $details['name'] );
										if ( ! empty( $bupr['add_taken_members'] ) ) {
											if ( in_array( $role, $bupr['add_taken_members'] ) ) {
												?>
													<option value="<?php echo esc_attr( $role ); ?>" <?php echo 'selected = "selected"'; ?>><?php echo esc_html( $name ); ?></option>
												<?php
											} else {
												?>
													<option value='<?php echo esc_attr( $role ); ?>'><?php echo esc_html( $name ); ?></option>
												<?php
											}
										} else {
											?>
												<option value="<?php echo esc_attr( $role ); ?>"><?php echo esc_html( $name ); ?></option>
											<?php
										}
									}


									?>
								</select>
							</div>
						</div>
						<div class="wbcom-settings-section-wrap">
							<div class="wbcom-settings-section-options-heading">
								<label for="bupr_enable_anonymous_reviews">
									<?php esc_html_e( 'Enable anonymous reviews', 'bp-member-reviews' ); ?>
								</label>
								<p class="description"><?php esc_html_e( 'Enable this option if you want users to review members anonymously.', 'bp-member-reviews' ); ?></p>
							</div>
							<div class="wbcom-settings-section-options">
								<label class="bupr-switch">
									<input type="checkbox" id="bupr_enable_anonymous_reviews" value="yes" name="bupr_admin_general_options[bupr_enable_anonymous_reviews]" <?php checked( esc_attr( $bupr['anonymous_reviews'] ), 'yes' ); ?>>
									<div class="bupr-slider bupr-round"></div>
								</label>
							</div>
						</div>
				</div>
				<?php submit_button(); ?>
			</form>
		</div>
	</div>
</div>
