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

$type_label = YITH_Popup()->get_meta( $theme . '_label_position', $popup_id );

$show_name     = YITH_Popup()->get_meta( '_newsletter-show-name', $popup_id );
$method        = YITH_Popup()->get_meta( '_newsletter-method', $popup_id );
$action_popup  = YITH_Popup()->get_meta( '_newsletter-action', $popup_id );
$name_label    = YITH_Popup()->get_meta( '_newsletter-name-label', $popup_id );
$name_name     = YITH_Popup()->get_meta( '_newsletter-name-name', $popup_id );
$email_label   = YITH_Popup()->get_meta( '_newsletter-email-label', $popup_id );
$email_name    = YITH_Popup()->get_meta( '_newsletter-email-name', $popup_id );
$hidden_fields = YITH_Popup()->get_meta( '_newsletter-hidden-fields', $popup_id );
$submit_label  = YITH_Popup()->get_meta( '_newsletter-submit-label', $popup_id );

$add_privacy         = YITH_Popup()->get_meta( '_newsletter-add-privacy-checkbox', $popup_id );
$privacy_name        = YITH_Popup()->get_meta( '_newsletter-privacy-name', $popup_id );
$privacy_label       = YITH_Popup()->get_meta( '_newsletter-privacy-label', $popup_id );
$privacy_description = YITH_Popup()->get_meta( '_newsletter-privacy-description', $popup_id );

$placeholder_name  = ( 'placeholder' === $type_label ) ? 'placeholder="' . esc_attr( $name_label ) . '"' : '';
$placeholder_email = ( 'placeholder' === $type_label ) ? 'placeholder="' . esc_attr( $email_label ) . '"' : '';

$icon        = YITH_Popup()->get_meta( '_submit_button_icon', $popup_id );
$submit_icon = '';
if ( ! empty( $icon ) ) {
	switch ( $icon['select'] ) {
		case 'icon':
			$submit_icon = '<span class="icon" ' . ypop_get_icon_data( $icon['icon'] ) . ' style="padding-right:10px; "></span>';
			break;
		case 'custom':
			$submit_icon = '<span class="custom_icon"><img src="' . $icon['custom'] . '" style="max-width :27px;max-height: 25px;"/></span>';
			break;
	}
}

?>
<div class="ypop-form-newsletter-wrapper">
	<form method="<?php echo esc_attr( $method ); ?>" action="<?php echo esc_attr( $action_popup ); ?>">
		<fieldset>
			<ul class="group">
				<?php if ( 'yes' === $show_name ) : ?>
				<li>
					<?php
					if ( 'label' === $type_label ) {
						echo '<label for="' . esc_attr( $name_name ) . '">' . esc_html( $name_label ) . '</label>'; }
					?>
					<div class="newsletter_form_name">
						<input type="text" <?php echo $placeholder_name;; //phpcs:ignore ?> name="<?php echo esc_attr( $name_name ); ?>" id="<?php echo esc_attr( $name_name ); ?>" class="name-field text-field autoclear" />
					</div>
				</li>
				<?php endif ?>
				<li>
					<?php
					if ( 'label' === $type_label ) {
						echo '<label for="' . esc_attr( $email_name ) . '">' . esc_attr( $email_label ) . '</label>'; }
					?>
					<div class="newsletter_form_email">
						<input type="text" <?php echo $placeholder_email;//phpcs:ignore ?> name="<?php echo esc_attr( $email_name ); ?>" id="<?php echo esc_attr( $email_name ); ?>" class="email-field text-field autoclear" />
					</div>
				</li>
				<?php if ( 'yes' === $add_privacy ) : ?>
				<li>
					<div class="ypop-privacy-wrapper">
						<p class="form-row"
						   id="ypop_privacy_description_row"><?php echo ypop_replace_policy_page_link_placeholders( $privacy_description ); //phpcs:ignore ?></p>
						<p class="form-row" id="ypop_privacy_row">
							<input type="checkbox" <?php echo $placeholder_email;//phpcs:ignore ?> name="<?php echo esc_attr( $privacy_name ); ?>" id="<?php echo esc_attr( $privacy_name ); ?>" required>
							<label for="<?php echo esc_attr( $privacy_name ); ?>"
								   class=""><?php echo ypop_replace_policy_page_link_placeholders( $privacy_label ); //phpcs:ignore ?>
								<abbr class="required" title="required">*</abbr></label>
						</p>
					</div>
				</li>
				<?php endif ?>

				<li class="ypop-submit">
					<?php
					if ( ! empty( $hidden_fields ) ) {
						$hidden_fields = explode( '&', $hidden_fields );
						foreach ( $hidden_fields as $field ) {
							list( $id_field, $value_field ) = explode( '=', $field );
							echo '<input type="hidden" name="' . esc_attr( $id_field ) . '" value="' . esc_attr( $value_field ) . '" />';
						}
					}
					?>

					<button type="submit" class="btn submit-field"><?php echo wp_kses_post( $submit_icon . $submit_label ); ?></button>
				</li>
			</ul>
		</fieldset>
	</form>
</div>
