<?php
$count            = 0;
$first_name_class = '';
$last_name_class  = '';
$email_class      = '';
$phone_class      = '';
$subject_class    = '';
$msg_class        = '';
$fnmae            = ( $settings->enable_icon == 'yes' ) ? '<span class="njba-input-icon "><i class= " ' . $settings->first_name_icon . '"></i></span>' : '';
$lname            = ( $settings->enable_icon == 'yes' ) ? '<span class="njba-input-icon "><i class= " ' . $settings->last_name_icon . '"></i></span>' : '';
$email            = ( $settings->enable_icon == 'yes' ) ? '<span class="njba-input-icon "><i class= " ' . $settings->email_icon . '"></i></span>' : '';
$subject          = ( $settings->enable_icon == 'yes' ) ? '<span class="njba-input-icon "><i class= " ' . $settings->subject_icon . '"></i></span>' : '';
$phone            = ( $settings->enable_icon == 'yes' ) ? '<span class="njba-input-icon "><i class= " ' . $settings->phone_icon . '"></i></span>' : '';
$msg              = ( $settings->enable_icon == 'yes' ) ? '<span class="njba-input-icon "><i class= " ' . $settings->msg_icon . '"></i></span>' : '';
/* Render Heading Module  */
$heading_settings = array(
	'main_title' => $settings->custom_title,
	'sub_title'  => $settings->custom_description,

);
/* Render Button Module  */
$button_settings = array(
	'btn_class'            => 'njba-contact-form-submit',
	'button_font_icon'     => $settings->button_font_icon,
	'button_icon_aligment' => $settings->button_icon_aligment,
	'button_text'          => $settings->btn_text,
	'buttton_icon_select'  => $settings->buttton_icon_select,
);
if ( $settings->first_name_toggle == 'show' && $settings->first_name_width == '50' ) {
	$count            = ++ $count;
	$first_name_class = ' njba-name-inline njba-inline-group';
	if ( $count % 2 == 0 ) {
		$first_name_class .= ' njba-io-padding-left';
	} else {
		$first_name_class .= ' njba-io-padding-right';
	}
}
if ( $settings->last_name_toggle == 'show' && $settings->last_name_width == '50' ) {
	$count           = ++ $count;
	$last_name_class = ' njba-name-inline njba-inline-group';
	if ( $count % 2 == 0 ) {
		$last_name_class .= ' njba-io-padding-left';
	} else {
		$last_name_class .= ' njba-io-padding-right';
	}
}
if ( $settings->email_toggle == 'show' && $settings->email_width == '50' ) {
	$count       = ++ $count;
	$email_class = ' njba-email-inline njba-inline-group';
	if ( $count % 2 == 0 ) {
		$email_class .= ' njba-io-padding-left';
	} else {
		$email_class .= ' njba-io-padding-right';
	}
}
if ( $settings->subject_toggle == 'show' && $settings->subject_width == '50' ) {
	$count         = ++ $count;
	$subject_class = ' njba-subject-inline njba-inline-group';
	if ( $count % 2 == 0 ) {
		$subject_class .= ' njba-io-padding-left';
	} else {
		$subject_class .= ' njba-io-padding-right';
	}
}
if ( $settings->phone_toggle == 'show' && $settings->phone_width == '50' ) {
	$count       = ++ $count;
	$phone_class = ' njba-phone-inline njba-inline-group';
	if ( $count % 2 == 0 ) {
		$phone_class .= ' njba-io-padding-left';
	} else {
		$phone_class .= ' njba-io-padding-right';
	}
}
if ( $settings->msg_toggle == 'show' && $settings->msg_width == '50' ) {
	$count     = ++ $count;
	$msg_class = ' njba-message-inline njba-inline-group';
	if ( $count % 2 == 0 ) {
		$msg_class .= ' njba-io-padding-left';
	} else {
		$msg_class .= ' njba-io-padding-right';
	}
}
?>
<form class="njba-module-content njba-contact-form <?php echo 'njba-form-' . $settings->form_style; ?>" <?php if ( isset( $module->template_id ) ) {
	echo 'data-template-id="' . $module->template_id . '" data-template-node-id="' . $module->template_node_id . '"';
} ?>>
    <div class="njba-form-title">
		<?php if ( $settings->form_custom_title_desc == 'yes' ) : ?>
            <!-- Render Heading Module  -->
			<?php
			FLBuilder::render_module_html( 'njba-heading', $heading_settings );
			?>
		<?php endif; ?>
    </div>
    <div class="njba-input-group-wrap">
		<?php if ( $settings->first_name_toggle == 'show' ) : ?>
            <div class="njba-input-group njba-first-name <?php echo $first_name_class; ?>">
				<?php if ( $settings->enable_label == 'yes' ) { ?>
                    <label for="njba-first-name"><?php echo $settings->first_name_label; ?></label>
				<?php } ?>
                <div class="njba-form-outter">
                    <div class="njba-form-validation">
                        <input type="text" name="njba-first-name"
                               value="" <?php if ( $settings->enable_placeholder == 'yes' ) { ?> placeholder="<?php echo $settings->first_name_placeholder; ?>" <?php } ?>/>
                        <div class="njba-form-error-message njba-form-error-message-required"></div>
						<?php if ( $settings->enable_icon == 'yes' ) { ?>
							<?php echo $fnmae; ?>
						<?php } ?>
                    </div>
                    <span class="njba-contact-error">Please enter your first name.</span>
                </div>
            </div>
		<?php endif; ?>
		<?php if ( $settings->last_name_toggle == 'show' ) : ?>
            <div class="njba-input-group njba-last-name <?php echo $last_name_class; ?>">
				<?php if ( $settings->enable_label == 'yes' ) { ?>
                    <label for="njba-last-name"><?php echo $settings->last_name_label; ?></label>
				<?php } ?>
                <div class="njba-form-outter">
                    <div class="njba-form-validation">
                        <input type="text" name="njba-last-name"
                               value="" <?php if ( $settings->enable_placeholder == 'yes' ) { ?> placeholder="<?php echo $settings->last_name_placeholder; ?>" <?php } ?>/>
                        <div class="njba-form-error-message njba-form-error-message-required"></div>
						<?php if ( $settings->enable_icon == 'yes' ) { ?>
							<?php echo $lname; ?>
						<?php } ?>
                    </div>
                    <span class="njba-contact-error">Please enter your last name.</span>
                </div>
            </div>
		<?php endif; ?>
		<?php if ( $settings->email_toggle == 'show' ) : ?>
            <div class="njba-input-group njba-email <?php echo $email_class; ?>">
				<?php if ( $settings->enable_label == 'yes' ) { ?>
                    <label for="njba-email"><?php echo $settings->email_label; ?></label>
				<?php } ?>
                <div class="njba-form-outter">
                    <div class="njba-form-validation">
                        <input type="email" name="njba-email" value=""
						       <?php if ( $settings->enable_placeholder == 'yes' ) { ?>placeholder="<?php echo $settings->email_placeholder; ?>"<?php } ?>/>
                        <div class="njba-form-error-message njba-form-error-message-required"></div>
						<?php if ( $settings->enable_icon == 'yes' ) { ?>
							<?php echo $email; ?>
						<?php } ?>
                    </div>
                    <span class="njba-contact-error">Please enter an email.</span>
                    <span class="njba-error-email-msg">Please enter a valid email address.</span>

                </div>
            </div>
		<?php endif; ?>
		<?php if ( $settings->subject_toggle == 'show' ) : ?>
            <div class="njba-input-group njba-subject <?php echo $subject_class; ?>">
				<?php if ( $settings->enable_label == 'yes' ) { ?>
                    <label for="njba-subject"><?php echo $settings->subject_label; ?></label>
				<?php } ?>
                <div class="njba-form-outter">
                    <div class="njba-form-validation">
                        <input type="text" name="njba-subject" value=""
						       <?php if ( $settings->enable_placeholder == 'yes' ) { ?>placeholder="<?php echo $settings->subject_placeholder; ?>"<?php } ?>/>
                        <div class="njba-form-error-message njba-form-error-message-required"></div>
						<?php if ( $settings->enable_icon == 'yes' ) { ?>
							<?php echo $subject; ?>
						<?php } ?>
                    </div>
                    <span class="njba-contact-error">Please enter a subject.</span>
                </div>
            </div>
		<?php endif; ?>
		<?php if ( $settings->phone_toggle == 'show' ) : ?>
            <div class="njba-input-group njba-phone <?php echo $phone_class; ?>">
				<?php if ( $settings->enable_label == 'yes' ) { ?>
                    <label for="njba-phone"><?php echo $settings->phone_label; ?></label>
				<?php } ?>
                <div class="njba-form-outter">
                    <div class="njba-form-validation">
                        <input type="tel" name="njba-phone" value=""
						       <?php if ( $settings->enable_placeholder == 'yes' ) { ?>placeholder="<?php echo $settings->phone_placeholder; ?>"<?php } ?> />
                        <div class="njba-form-error-message njba-form-error-message-required"></div>
						<?php if ( $settings->enable_icon == 'yes' ) { ?>
							<?php echo $phone; ?>
						<?php } ?>
                    </div>
                    <span class="njba-contact-error">Please enter a number.</span>
                </div>
            </div>
		<?php endif; ?>
		<?php if ( $settings->msg_toggle == 'show' ) : ?>
            <div class="njba-input-group njba-message <?php echo $msg_class; ?>">
				<?php if ( $settings->enable_label == 'yes' ) { ?>
                    <label for="njba-message"><?php echo $settings->msg_label; ?></label>
				<?php } ?>
                <div class="njba-form-outter-textarea">
                    <div class="njba-form-validation">
                        <textarea name="njba-message"
                                  <?php if ( $settings->enable_placeholder == 'yes' ) { ?>placeholder="<?php echo $settings->msg_placeholder; ?>"<?php } ?>></textarea>
                        <div class="njba-form-error-message njba-form-error-message-required"></div>
						<?php if ( $settings->enable_icon == 'yes' ) { ?>
							<?php echo $msg; ?>
						<?php } ?>
                    </div>
                    <span class="njba-contact-error">Please enter a message.</span>
                </div>
            </div>
		<?php endif; ?>
		<?php
		if ( 'show' == $settings->recaptcha_toggle && ( isset( $settings->recaptcha_site_key ) && ! empty( $settings->recaptcha_site_key ) ) ) :
			?>
            <div class="njba-input-group fl-recaptcha">
                <span class="fl-contact-error">Please check the captcha to verify you are not a robot.</span>
                <div id="<?php echo $id; ?>-fl-grecaptcha" class="fl-grecaptcha"
                     data-sitekey="<?php echo $settings->recaptcha_site_key; ?>"<?php if ( isset( $settings->recaptcha_validate_type ) ) {
					echo ' data-validate="' . $settings->recaptcha_validate_type . '"';
				} ?><?php if ( isset( $settings->recaptcha_theme ) ) {
					echo ' data-theme="' . $settings->recaptcha_theme . '"';
				} ?>></div><?php // @codingStandardsIgnoreLine
				?>
            </div>
		<?php endif; ?>
    </div>

    <div class="njba-contact-submit-btn">
        <!-- Render Button Module  -->
		<?php
		FLBuilder::render_module_html( 'njba-button', $button_settings );
		?>
    </div>
	<?php if ( $settings->success_action == 'redirect' ) : ?>
        <input type="text" value="<?php echo $settings->success_url; ?>" style="display: none;" class="njba-success-url">
	<?php elseif ( $settings->success_action == 'none' ) : ?>
        <span class="njba-success-none" style="display:none;"><?php echo $settings->email_success; ?></span>
	<?php endif; ?>
    <span class="njba-send-error" style="display:none;"><?php echo $settings->email_error; ?></span>
</form>
<?php if ( $settings->success_action == 'show_message' ) : ?>
    <span class="njba-success-msg njba-text-editor" style="display:none;"><span
                class="njba-success-icon fas fa-check-double"><?php echo $settings->success_message; ?></span></span>
<?php endif; ?> 
