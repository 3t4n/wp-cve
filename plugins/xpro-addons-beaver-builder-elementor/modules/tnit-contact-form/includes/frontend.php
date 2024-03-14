<?php
/**
 * This file should be used to render each module instance.
 * You have access to two variables in this file:
 *
 * $module An instance of your module class.
 * $settings The module's settings.
 *
 * @package Contact Form Module
 * @since 1.1.3
 */

global $wp_embed;

if ( 'stacked' === $settings->tnit_form_layout ) {
	$form_class       = 'tnit-form-box tnit-form-box_v2 tnit-form-style-1';
	$form_inner_class = 'tnit-form';
	$fullwidth_class  = 'tnit-fullwidth-style';
} elseif ( 'inline' === $settings->tnit_form_layout ) {
	$form_class       = 'tnit-form-box tnit-form-box_v2 tnit-form-box-inline tnit-form-style-2';
	$form_inner_class = 'tnit-form tnit-form-inline';
	$fullwidth_class  = 'tnit-fullwidth-style';
} elseif ( 'stacked-inline' === $settings->tnit_form_layout ) {
	$form_class       = 'tnit-form-box tnit-form-box_v2 tnit-form-box-stacked-inline tnit-form-style-3';
	$form_inner_class = 'tnit-form tnit-form-inline tnit-form-stacked-inline tnit-masonry-box';
	$fullwidth_class  = 'tnit-fullwidth-style';
}
?>


<div class="tnit-form-outer">
	<div class="<?php echo esc_attr( $form_class ); ?>">

		<?php if ( '' !== $settings->tnit_form_title ) { ?>
		<div class="tnit-contact-title-holder">
			<h3 class="tnit-title-contact"><?php echo esc_attr( $settings->tnit_form_title ); ?></h3>
		</div>
		<?php } ?>

		<?php if ( '' !== $settings->form_description ) { ?>
		<div class="tnit-contact-desc-holder">
			<p class="tnit-desc-contactv1"><?php echo esc_attr( $settings->form_description ); ?></p>
		</div>
		<?php } ?>

		<form class="tnit-contact-form"
		<?php
		if ( isset( $module->template_id ) ) {
			echo 'data-template-id="' . $module->template_id . '" data-template-node-id="' . $module->template_node_id . '"';}
		?>
		>

			<?php global $post; ?>
			<input type="hidden" name="tnit-layout-id" value="<?php echo esc_attr( $post->ID ); ?>" />

			<div class="<?php echo esc_attr( $form_inner_class ); ?>">

				<?php if ( 'stacked-inline' === $settings->tnit_form_layout ) { ?>
				<div class="tnit-nested-flex">
				<?php } ?>

					<?php if ( 'show' === $settings->tnit_name_toggle ) { ?>
					<div class="inner-holder">
						<span class="tnit-contact-error"><?php esc_html_e( 'Please enter your name.', 'xpro-bb-addons' ); ?></span>
						<input placeholder="<?php echo esc_attr( $settings->name_placeholder ); ?>" class="tnit-contact-name" name="name" required="" pattern="[a-zA-Z ]+" type="text">
					</div>
					<?php } ?>

					<div class="inner-holder">
						<span class="tnit-contact-error"><?php esc_html_e( 'Please provide a real email.', 'xpro-bb-addons' ); ?></span>
						<input placeholder="<?php echo esc_attr( $settings->tnit_email_placeholder ); ?>" class="tnit-contact-email" name="email" type="email" required>
					</div>

				<?php if ( 'stacked-inline' === $settings->tnit_form_layout ) { ?>
				</div>
				<?php } ?>

				<?php if ( 'stacked-inline' === $settings->tnit_form_layout ) { ?>
				<div class="tnit-nested-flex">
				<?php } ?>
				<?php if ( 'show' === $settings->tnit_phone_toggle ) { ?>
					<div class="inner-holder">
						<span class="tnit-contact-error"><?php esc_html_e( 'Please enter valid phone number.', 'xpro-bb-addons' ); ?></span>
						<input placeholder="<?php echo esc_attr( $settings->tnit_phone_placeholder ); ?>" type="text" class="tnit-contact-phone" name="phone" required>
					</div>
				<?php } ?>

				<?php if ( 'show' === $settings->subject_toggle ) { ?>
				<div class="inner-holder
					<?php
					if ( 'inline' === $settings->tnit_form_layout ) {
						echo esc_attr( $fullwidth_class ); }
					?>
				">
					<span class="tnit-contact-error"><?php esc_html_e( 'Please enter your subject.', 'xpro-bb-addons' ); ?></span>
					<input placeholder="<?php echo esc_attr( $settings->tnit_subject_placeholder ); ?>" class="tnit-contact-subject" name="subject" required pattern="[a-zA-Z ]+" type="text">
				</div>
				<?php } ?>

				<?php if ( 'stacked-inline' === $settings->tnit_form_layout ) { ?>
				</div>
				<?php } ?>

				<?php if ( 'show' === $settings->tnit_message_toggle ) { ?>
				<div class="inner-holder <?php echo esc_attr( $fullwidth_class ); ?>">
					<span class="tnit-contact-error"><?php esc_html_e( 'Please enter your message.', 'xpro-bb-addons' ); ?></span>
					<textarea placeholder="<?php echo esc_attr( $settings->tnit_message_placeholder ); ?>" class="tnit-contact-message" name="message" required></textarea>
				</div>
				<?php } ?>

				<?php
				if ( 'show' === $settings->recaptcha_toggle && ( isset( $module->user_data['recaptcha']['site_key'] ) ) ) {
					?>
				<div class="fl-recaptcha inner-holder tnit-captcha <?php echo esc_attr( $fullwidth_class ); ?>">
					<span class="tnit-contact-error"><?php esc_html_e( 'Please check the captcha to verify you are not a robot.', 'xpro-bb-addons' ); ?></span>
					<div id="<?php echo esc_attr( $id ); ?>-fl-grecaptcha" class="fl-grecaptcha"<?php $module->recaptcha_data_attributes(); ?>></div>
				</div>
				<?php } ?>

				<div class="inner-holder tnit-btn-holder <?php echo esc_attr( $fullwidth_class ); ?>">
					<a href="#" target="_self" class="tnit-button tnit-btn-style1 btn-submit" role="button"><?php echo esc_attr( $settings->tnit_button_text ); ?></a>
				</div>

				<?php if ( 'redirect' === $settings->success_action ) : ?>
					<input type="text" value="<?php echo esc_url( $settings->success_url ); ?>" style="display: none;" class="tnit-success-url">
				<?php elseif ( 'none' === $settings->success_action ) : ?>
					<span class="tnit-success-none" style="display:none;"><?php esc_html_e( 'Message Sent!', 'xpro-bb-addons' ); ?></span>
				<?php endif; ?>

				<span class="tnit-send-error" style="display:none;"><?php esc_html_e( 'Message failed. Please try again.', 'xpro-bb-addons' ); ?></span>

			</div>
		</form>

		<?php if ( 'show_message' === $settings->success_action ) : ?>
			<div class="tnit-success-msg" style="display:none;"><?php echo wpautop( $wp_embed->autoembed( $settings->success_message ) ); ?></div>
		<?php endif; ?>

	</div>
</div>
