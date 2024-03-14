<?php 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	} // Exit if accessed directly
?>
<div class="moove-gdpr-button-holder">
	<?php 
	if ( isset( $content->buttons_order ) && is_array( $content->buttons_order ) ) : 
		foreach ( $content->buttons_order as $button_type ) :
			if ( 'enable' === $button_type && isset( $content->allow_v ) && $content->allow_v ) : 
				?>
		  		<button class="mgbutton moove-gdpr-modal-allow-all button-visible" aria-label="<?php echo esc_attr( $content->allow_label ); ?>"><?php echo esc_attr( $content->allow_label ); ?></button>
		  	<?php
			elseif ( 'reject' === $button_type && isset( $content->reject_v ) && $content->reject_v ) :
				?>
				<button class="mgbutton moove-gdpr-modal-reject-all button-visible" aria-label="<?php echo esc_attr( $content->reject_label ); ?>"><?php echo esc_attr( $content->reject_label ); ?></button>
				<?php
			elseif ( 'save' === $button_type && isset( $content->settings_v ) && $content->settings_v ) :
				?>
				<button class="mgbutton moove-gdpr-modal-save-settings button-visible" aria-label="<?php echo esc_attr( $content->settings_label ); ?>"><?php echo esc_attr( $content->settings_label ); ?></button>
				<?php
			endif;
		endforeach;
	else : ?>
		<?php if ( isset( $content->allow_v ) && $content->allow_v ) : ?>
		  <button class="mgbutton moove-gdpr-modal-allow-all button-visible" aria-label="<?php echo esc_attr( $content->allow_label ); ?>"><?php echo esc_attr( $content->allow_label ); ?></button>
		<?php endif; ?>

		<?php if ( isset( $content->reject_v ) && $content->reject_v ) : ?>
		  <button class="mgbutton moove-gdpr-modal-reject-all button-visible" aria-label="<?php echo esc_attr( $content->reject_label ); ?>"><?php echo esc_attr( $content->reject_label ); ?></button>
		<?php endif; ?>

		<?php if ( isset( $content->settings_v ) && $content->settings_v ) : ?>
	  	<button class="mgbutton moove-gdpr-modal-save-settings button-visible" aria-label="<?php echo esc_attr( $content->settings_label ); ?>"><?php echo esc_attr( $content->settings_label ); ?></button>
	  <?php endif; ?>
	<?php endif; ?>
</div>
<!--  .moove-gdpr-button-holder -->