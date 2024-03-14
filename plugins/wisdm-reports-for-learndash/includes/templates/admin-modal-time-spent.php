<?php
/**
 * This file contains the onboarding modal html structure.
 *
 * @package learndash-reports-by-wisdmlabs
 */

?>
<div id="wrld-ts-custom-modal" class="wrld-ts-custom-popup-modal" wp_nonce=<?php echo esc_html( $wp_nonce ); ?>>
	<div class="wrld-ts-modal-content">
		<div class="wrld-ts-modal-content-container">
		<a class="wrld-dismiss-link" href="<?php echo esc_html( $dismiss_link ); ?>"><span class="dashicons dashicons-no-alt wrld-close-modal-btn"></span></a>
			<div class="wrld-ts-modal-head">
				<span>
					<p class="wrld-ts-modal-head-text"><?php echo esc_html( $modal_head ); ?></p>
				</span>

			</div>
			<div class="wrld-ts-modal-text">
				<?php echo esc_html__( 'We have enhanced the Time Spent on Course reports with improved visualizations, enabling a quick and intuitive understanding of the time spent by learners to quickly get the', 'learndash-reports-by-wisdmlabs' ); ?>
			</div>
			<div class="wrld-ts-modal-second-head">
				<ol>
					<li>
							<?php esc_html_e( ' The ', 'learndash-reports-by-wisdmlabs' ); ?>  
						<b class="add-ts-weight"><?php esc_html_e( 'average time spent ', 'learndash-reports-by-wisdmlabs' ); ?></b>
						<?php esc_html_e( ' in a course for course completion or in progress learner. ', 'learndash-reports-by-wisdmlabs' ); ?> 
					</li>
					<li>
							<?php esc_html_e( ' Identify learners who are ', 'learndash-reports-by-wisdmlabs' ); ?>  
						<b class="add-ts-weight"><?php esc_html_e( 'spending significantly less or more time ', 'learndash-reports-by-wisdmlabs' ); ?></b>
						<?php esc_html_e( ' than the average. ', 'learndash-reports-by-wisdmlabs' ); ?> 
					</li>
					<li>
							<?php esc_html_e( ' For each learner, ', 'learndash-reports-by-wisdmlabs' ); ?>  
						<b class="add-ts-weight"><?php esc_html_e( 'compare their actual time spent ', 'learndash-reports-by-wisdmlabs' ); ?></b>
						<?php esc_html_e( ' in their enrolled course with the overall average time spent in a course. ', 'learndash-reports-by-wisdmlabs' ); ?> 
					</li>
				</ol>
			</div>
			<div class="wrld-ts-modal-actions">
				<div class="wrld-ts-modal-center">
					<?php if ( isset( $banner_message_addon ) && ! empty( $banner_message_addon ) ) : ?>
					<div class="wrld-ts-additional-text">
						<?php echo esc_html( $banner_message_addon ); ?>
					</div>
					<?php endif; ?>

					<div class="wrld-ts-btn-container">
						<div class="right-box">
						   <div class="first-r-row heading">
								<b
									class="add-weight"><?php esc_html_e( 'Mandatory Data Upgrade required', 'learndash-reports-by-wisdmlabs' ); ?></b>
							</div>
						   <div class="first-r-row description">
								
								<?php esc_html_e( "We've also enhanced the performance the above report by optimizing how we store time spent data in our course time entries table. To see the ", 'learndash-reports-by-wisdmlabs' ); ?>
								<b
									class="add-weight"><?php esc_html_e( "learner's earlier time spent information on this enhanced report,", 'learndash-reports-by-wisdmlabs' ); ?></b>

									<?php esc_html_e( " click below to go to data upgrade settings.", 'learndash-reports-by-wisdmlabs' ); ?>
							</div>
							<a href=<?php echo esc_url($page_link) ?> > <button class="wrld-ts-auto-button">
									<div class="wrld-btn-txt">
										<?php esc_html_e( ' Upgrade Data now', 'learndash-reports-by-wisdmlabs' ); ?>
									</div>
									<span class="right_arrow_icon">></span>
								</button></a>

								<div class="first-r-row bottom-note">
								<b
									class="add-weight"><?php esc_html_e( ' Note:', 'learndash-reports-by-wisdmlabs' ); ?></b>
								<?php esc_html_e( 'You can also perform this action at a later time by navigating to Wisdm Reports > Settings > Time Tracking settings.', 'learndash-reports-by-wisdmlabs' ); ?>
								
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
