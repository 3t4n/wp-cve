<?php
/**
 * Newsletter custom template
 *
 * @author YITH <plugins@yithemes.com>
 * @package YITH WooCommerce Popup
 * @version 1.0.0
 */

if ( ! defined( 'YITH_YPOP_INIT' ) ) {
	exit;
} // Exit if accessed directly

$type_label   = YITH_Popup()->get_meta( $theme . '_label_position', $popup_id );
$email_label  = YITH_Popup()->get_meta( '_mailchimp-email-label', $popup_id );
$submit_label = YITH_Popup()->get_meta( '_mailchimp-submit-label', $popup_id );

$add_privacy         = YITH_Popup()->get_meta( '_mailchimp-add-privacy-checkbox', $popup_id );
$privacy_label       = YITH_Popup()->get_meta( '_mailchimp-privacy-label', $popup_id );
$privacy_description = YITH_Popup()->get_meta( '_mailchimp-privacy-description', $popup_id );

$placeholder_email = ( 'placeholder' === $type_label ) ? 'placeholder="' . esc_html( $email_label ) . '"' : '';


$submit_icon = '';


?>
<div class="ypop-form-newsletter-wrapper">
<div class="message-box"></div>
<form method="post" action="#" id="ypop-mailchimp">
	<fieldset>
		<ul class="group">
			<li>
				<?php
				if ( 'label' === $type_label ) {
					echo '<label for="yit_mailchimp_newsletter_form_email">' . esc_html( $email_label ) . '</label>'; }
				?>
				<div class="newsletter_form_email">
                    <input type="text" <?php echo $placeholder_email; //phpcs:ignore ?> name="yit_mailchimp_newsletter_form_email" id="yit_mailchimp_newsletter_form_email" class="email-field text-field autoclear" />
				</div>
			</li>
			<?php if ( 'yes' === $add_privacy ) : ?>
				<li>
					<div class="ypop-privacy-wrapper">
						<p class="form-row"
                           id="ypop_privacy_description_row"><?php echo ypop_replace_policy_page_link_placeholders( $privacy_description ); //phpcs:ignore ?></p>
						<p class="form-row" id="ypop_privacy_row">
                            <input type="checkbox" <?php echo $placeholder_email; //phpcs:ignore ?> name="ypop-privacy" id="ypop-privacy" required>
							<label for="ypop-privacy"
                                   class=""><?php echo ypop_replace_policy_page_link_placeholders( $privacy_label ); //phpcs:ignore ?>
								<abbr class="required" title="required">*</abbr></label>

						</p>
					</div>
				</li>
			<?php endif ?>
			<li class="ypop-submit">
				<input type="hidden" name="yit_mailchimp_newsletter_form_id" value="<?php echo esc_attr( $popup_id ); ?>"/>
				<input type="hidden" name="action" value="ypop_subscribe_mailchimp_user"/>
				<?php wp_nonce_field( 'yit_mailchimp_newsletter_form_nonce', 'yit_mailchimp_newsletter_form_nonce' ); ?>
				<button type="submit" class="btn submit-field mailchimp-subscription-ajax-submit"><?php echo  $submit_icon . $submit_label ; ?></button>
			</li>
		</ul>
	</fieldset>
</form>
</div>
<?php
yit_enqueue_script( 'yit-mailchimp-ajax-send-form', YITH_YPOP_ASSETS_URL . '/js/mailchimp-ajax-subscribe.js', array( 'jquery' ), '', true );
wp_localize_script(
	'yit-mailchimp-ajax-send-form',
	'mailchimp_localization',
	array(
		'url'           => admin_url( 'admin-ajax.php' ),
		'error_message' => 'Ops! Something went wrong',
	)
);
