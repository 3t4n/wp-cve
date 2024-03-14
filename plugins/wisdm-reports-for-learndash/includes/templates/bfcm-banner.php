<?php
/**
 * Template to display the BFCM Banners
 *
 * @package learndash-reports-by-wisdmlabs
 */

?>
<div class="notice notice-error is-dismissible wrld-main-container" style="padding:0px !important; background-image: url('<?php echo esc_html( $background ); ?>');">

	<div class="wrld-logo">
		<img class="wrld-logo-img" src='<?php echo esc_html( $wisdm_logo ); ?>'>
	</div>
	<div class="wrld-center">
		<div class="wrld-head-text">
			<?php echo esc_html( $banner_head ); ?>
		</div>
		<div class="wrld-sub-text">
			<?php echo esc_html( $banner_message ); ?>
		</div>
		<?php if ( isset( $banner_message_addon ) && ! empty( $banner_message_addon ) ) : ?>
		<div class="wrld-additional-text">
			<?php echo esc_html( $banner_message_addon ); ?>
		</div>
		<?php endif; ?>

		<div class="wrld-btn-container">
			<a href="<?php echo esc_html( $link ); ?>" target="_blank">
				<button class="wrld_btn">
					<span> <?php echo esc_html( $button_text ); ?></span>
				</button>
			</a>
			<div class="wrld_dismiss_btn">
					<a href='<?php echo esc_html( $dismiss_attribute ); ?>'>
						<?php esc_html_e( 'Dismiss Permanently', 'learndash-reports-by-wisdmlabs' ); ?>
					</a>
				</p>
			</div>
		</div>

	</div>
	<div class="wrld-right-image">
	</div>
</div>
