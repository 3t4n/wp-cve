<?php
/**
 * Single feature post
 * $linkImg  Post Image Link;
 * $title  Title,
 * $is_new = true;
 * $is_pro = true;
 * $doc_config_link Documentation link
 * $is_latest Latest version check

 * $post_description Post Description; 
 */
$is_pro_user = apply_filters( 'wisdm_ld_reports_pro_version', false );
// $img_array   = count( $img_array ) < 1 ? array( ) : $img_array;
?>

<div class="wrld-feature-post">
	<div class="wrld-feature-post-header">
		<h2><?php echo $title ?? '' ?></h2>
	   <div class="wrld-new-pro">
	   <?php echo $is_new ? '<div class="wrld-feature-post-new-tag">' . __( 'NEW', 'learndash-reports-by-wisdmlabs' ) . '</div>' : ''; ?>
		<?php echo $is_pro ? '<div class="wrld-feature-post-pro-tag">' . __( 'PRO', 'learndash-reports-by-wisdmlabs' ) . '</div>' : ''; ?>
	   </div>
	</div>
	<div class="wrld-feature-post-content">
		<div class="wrld-post-info">
			<p class="wrld-post-desc"><?php echo $post_description ?? '';   ?></p>
			<p class="wrld-post-info-bottom"><p class="wrld-post-notice" style="margin:0;padding:0;">
			<?php
			if ( isset( $version_data ) && count($version_data) > 0 ) { // Latest post
				if ( $is_pro_user ) { // Pro user.
					if( ! $is_old_version ){
					esc_html_e( 'You are on older version please update the plugin to get the best experience.', 'learndash-reports-by-wisdmlabs' );
					?>
					<div class="wrld-feature-post-footer">
						<div class="wrld-feature-post-footer-button">
							<?php echo '<a href="https://wisdmlabs.com/reports-for-learndash/?utm_source=wrld&utm_medium=wrld-whatsnewtab&utm_campaign=upgradetopro#pricing" target="_blank">' . __( 'Update', 'learndash-reports-by-wisdmlabs' ) . '</a>'; ?>
						</div>
					</div>
					<?php
					}
					else{
						esc_html_e( 'We hope you are having a great experience and would appreciate your', 'learndash-reports-by-wisdmlabs' );
						echo ' <a href="https://form.typeform.com/to/QCJcHc2E" target="__blank">' . __( 'feedback.', 'learndash-reports-by-wisdmlabs' ) . '</a>';
					}
				} else { // Free user.
					if ( $is_pro ) {
						esc_html_e( 'You are missing out on an important feature. Upgrade to Pro to access such advanced reports.', 'learndash-reports-by-wisdmlabs' );
						?>
						<div class="wrld-feature-post-footer">
							<div class="wrld-feature-post-footer-button">
								<?php echo '<a href="https://wisdmlabs.com/reports-for-learndash/?utm_source=wrld&utm_medium=wrld-whatsnewtab&utm_campaign=upgradetopro#pricing" target="_blank" style="width:144px;">' . __( 'Upgrade to PRO', 'learndash-reports-by-wisdmlabs' ) . '</a>'; ?>
							</div>
						</div>
						<?php
					} else {
						if($is_old_version){
						
								esc_html_e( 'We hope you are having a great experience and would appreciate your', 'learndash-reports-by-wisdmlabs' );
								echo ' <a href="https://form.typeform.com/to/Fqw2CZoC" target="__blank">' . __( 'feedback.', 'learndash-reports-by-wisdmlabs' ) . '</a>';

						}else{
						esc_html_e( 'You are on an older version. Please update the plugin to get the best experience.', 'learndash-reports-by-wisdmlabs' );
						?>
							<div class="wrld-feature-post-footer">
								<div class="wrld-feature-post-footer-button">
									<?php echo '<a href="https://wisdmlabs.com/reports-for-learndash/?utm_source=wrld&utm_medium=wrld-whatsnewtab&utm_campaign=upgradetopro#pricing" target="_blank">' . __( 'Update', 'learndash-reports-by-wisdmlabs' ) . '</a>'; ?>
								</div>
							</div>
						<?php
						}
					}
				}
			} else { // Old post
				if ( $is_pro_user ) { // Pro user.
					if(count($version_data) > 0){
						esc_html_e( 'We hope you are having a great experience and would appreciate your', 'learndash-reports-by-wisdmlabs' );
						echo ' <a href="https://form.typeform.com/to/QCJcHc2E" target="__blank">' . __( 'feedback.', 'learndash-reports-by-wisdmlabs' ) . '</a>';
						}else{
							echo __( 'You are already on the latest version and we do not have further updates for you. We hope you are having a great experience and would appreciate your', 'learndash-reports-by-wisdmlabs' );
						echo ' <a href="https://form.typeform.com/to/QCJcHc2E" target="__blank">' . __( 'feedback.', 'learndash-reports-by-wisdmlabs' ) . '</a>';
						}
				} else { // Free user.
					if ( $is_pro ) {
						esc_html_e( 'You are missing out on an important feature. Upgrade to Pro to access such advanced reports.', 'learndash-reports-by-wisdmlabs' );
						?>
						<div class="wrld-feature-post-footer">
							<div class="wrld-feature-post-footer-button">
								<?php echo '<a href="https://wisdmlabs.com/reports-for-learndash/?utm_source=wrld&utm_medium=wrld-whatsnewtab&utm_campaign=upgradetopro#pricing" target="_blank" style="width:144px;">' . __( 'Upgrade to PRO', 'learndash-reports-by-wisdmlabs' ) . '</a>'; ?>
							</div>
						</div>
						<?php
					} else {
						if(count($version_data) > 0){
						echo __( 'We hope you are having a great experience and would appreciate your', 'learndash-reports-by-wisdmlabs' );
						echo ' <a href="https://form.typeform.com/to/QCJcHc2E
						" target="__blank">' . __( 'feedback.', 'learndash-reports-by-wisdmlabs' ) . '</a>';
						}else{
							echo __( 'You are already on the latest version and we do not have further updates for you. We hope you are having a great experience and would appreciate your', 'learndash-reports-by-wisdmlabs' );
						echo ' <a href="https://form.typeform.com/to/QCJcHc2E" target="__blank">' . __( 'feedback.', 'learndash-reports-by-wisdmlabs' ) . '</a>';
						}
					}
				}
			}
			?>
			</p>
			</p>
		</div>
		<?php if ( ! empty( $img_array ) ) { ?>
		<div class="wrld-post-images wrld-slider-container">
			<?php foreach ( $img_array as $img ) { ?>
			<img class="mySlides" onclick="wrldShowmodal(this,this.parentElement);" src="<?php echo $img; ?>" alt="" style="width:100%">
			<?php } ?>
			<button class="wrld-slider-btn-color wrld-slider-btn-left <?php echo count( $img_array ) == 1 ? 'wrld-single-hide' : ''; ?>" data-slideindex="1" onclick="plusDivs(this,-1)">&#10094;</button>
			<button class="wrld-slider-btn-color wrld-slider-btn-right wrld-slider-btn-nxt <?php echo count( $img_array ) == 1 ? 'wrld-single-hide' : ''; ?>" data-slideindex="1" onclick="plusDivs(this,1)">&#10095;</button>
		</div>
		<?php } ?>
	</div>
	<?php if( '' !== $doc_config_link  ) { ?>
	<div class="wrld-feature-post-footer">
		<div class="wrld-feature-post-footer-text">
			<a class="wrld-config-doc-link <?php echo $doc_config_link == '' ? 'wrld-single-hide' : ''; ?>" href="<?php echo $doc_config_link; ?>"
				target="_blank"><?php esc_html_e( 'Configuration settings DOC', 'learndash-reports-by-wisdmlabs' ); ?>
				<span class="dashicons dashicons-external"></span></a>
		</div>
	</div>
	<?php } ?>
</div>
