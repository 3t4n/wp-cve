<?php
/**
 * This file contains the onboarding modal html structure.
 *
 * @package learndash-reports-by-wisdmlabs
 */

?>
<div id="wrld-la-custom-modal" class="wrld-la-custom-popup-modal" wp_nonce=<?php echo esc_html( $wp_nonce ); ?>>
	<div class="wrld-la-modal-content">
		<div class="wrld-la-modal-content-container">
		<a class="wrld-dismiss-link" href="<?php echo esc_html( $dismiss_link ); ?>"><span class="dashicons dashicons-no-alt wrld-close-modal-btn"></span></a>
			<div class="wrld-la-modal-head">
				<span>
					<p class="wrld-la-modal-head-text"><?php echo esc_html( $modal_head ); ?></p>
				</span>

			</div>
			<div class="wrld-la-modal-text">
				<?php echo esc_html__( 'We have Introduced two ', 'learndash-reports-by-wisdmlabs' ); ?>
				<b class="add-la-weight"><?php echo esc_html__( 'new Gutenberg blocks: Inactive user list and Learner Activity log', 'learndash-reports-by-wisdmlabs' ); ?></b>
				<?php echo esc_html__( 'so that you can understand and take actions to improve learners engagement in the course', 'learndash-reports-by-wisdmlabs' ); ?>
			</div>
			<div class="wrld-la-modal-second-head">
				<?php echo esc_html__( 'Update your Reports dashboard now!', 'learndash-reports-by-wisdmlabs' ); ?>
			</div>
			<div class="wrld-la-modal-actions">
				<div class="wrld-la-modal-center">
					<?php if ( isset( $banner_message_addon ) && ! empty( $banner_message_addon ) ) : ?>
					<div class="wrld-la-additional-text">
						<?php echo esc_html( $banner_message_addon ); ?>
					</div>
					<?php endif; ?>

					<div class="wrld-la-btn-container">
						<div class="left-box">
							<div class="first-row">
								<b class="add-weight">
									<?php esc_html_e( ' Manually Enable', 'learndash-reports-by-wisdmlabs' ); ?></b>
								<?php esc_html_e( 'the reporting blocks', 'learndash-reports-by-wisdmlabs' ); ?>
							</div>
							<div class="second-row">
								<?php esc_html_e( 'Go to the dashboard page --> edit --> search for the Learner activity blocks --> Insert.', 'learndash-reports-by-wisdmlabs' ); ?>
								<a href="https://wisdmlabs.com/docs/article/wisdm-learndash-reports/features/learner-activity-blocks/" target="_blank"><?php esc_html_e( 'Learn more', 'learndash-reports-by-wisdmlabs' ); ?></a>
							</div>
							<a href="
							<?php
							echo esc_attr(
								add_query_arg(
									array(
										'preload_activity' => 1,
										'dla_on'           => true,
									),
									$page_link
								)
							);
							?>
							"> <button class="wrld-la-manual-button">
									<div class="wrld-btn-txt">
										<?php esc_html_e( ' Manually Edit Page', 'learndash-reports-by-wisdmlabs' ); ?>
									</div>
									<span class="right_arrow_icon">></span>
								</button></a>
						</div>
						<div class="center-box">
							<div class="or-container">
								<span><?php esc_html_e( ' OR', 'learndash-reports-by-wisdmlabs' ); ?></span>
							</div>
						</div>
						<div class="right-box">
							<div class="first-r-row">
								<b
									class="add-weight"><?php esc_html_e( ' Note:', 'learndash-reports-by-wisdmlabs' ); ?></b>
								<?php esc_html_e( 'While Auto updating  we will delete the current blocks pattern and replace with the new one including the learner activity block', 'learndash-reports-by-wisdmlabs' ); ?>
								<b
									class="add-weight"><?php esc_html_e( 'If any custom changes were made to the Dashboard page, then they will be lost.', 'learndash-reports-by-wisdmlabs' ); ?></b>
							</div>
							<a href="#"><button href="#" class="wrld-la-auto-button">
									<div class="wrld-btn-txt">
										<?php esc_html_e( ' Auto Update Page ', 'learndash-reports-by-wisdmlabs' ); ?>
									</div>
									<span class="right_arrow_icon">></span>
								</button></a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
