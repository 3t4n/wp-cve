<?php
$heading_settings = array(
	'main_title'     => $settings->custom_title,
	'sub_title'      => $settings->custom_description,
	'main_title_tag' => $settings->main_title_tag,
);
?>
<div class="njba-subscribe-form njba-subscribe-form-<?php echo $settings->layout; ?> njba-subscribe-form-name njba-form njba-clearfix" <?php if ( isset( $module->template_id ) ) {
	echo 'data-template-id="' . $module->template_id . '" data-template-node-id="' . $module->template_node_id . '"';
} ?>>
    <div class="njba-subscribe-form-inner">
		<?php if ( 'yes' === $settings->form_custom_title_desc ) { ?>
            <div class="njba-subscribe-content">
				<?php
				FLBuilder::render_module_html( 'njba-heading', (object) $heading_settings );
				?>
            </div>
		<?php } ?>
		<?php if ( 'show' === $settings->show_fname ) : ?>
            <div class="njba-form-field njba-fname-field">
                <input type="text" name="njba-subscribe-form-fname" placeholder="<?php echo $settings->input_fname_placeholder; ?>"/>
                <div class="njba-form-error-message"><?php esc_html_e( 'Please enter your First Name.', 'bb-njba' ); ?></div>
            </div>
		<?php endif; ?>
		<?php if ( 'show' === $settings->show_lname ) : ?>
            <div class="njba-form-field njba-lname-field">
                <input type="text" name="njba-subscribe-form-lname" placeholder="<?php echo $settings->input_lname_placeholder; ?>"/>
                <div class="njba-form-error-message"><?php esc_html_e( 'Please enter your Last Name.', 'bb-njba' ); ?></div>
            </div>
		<?php endif; ?>
        <div class="njba-form-field njba-email-field">
            <input type="text" name="njba-subscribe-form-email" placeholder="<?php echo $settings->input_email_placeholder; ?>"/>
            <div class="njba-form-error-message"><?php esc_html_e( 'Please enter a valid email address.', 'bb-njba' ); ?></div>
            <div class="njba-form-error-msg"><?php esc_html_e( 'Please enter an email address.', 'bb-njba' ); ?></div>
        </div>
        <div class="njba-form-button" data-wait-text="<?php esc_attr_e( 'Please Wait...', 'bb-njba' ); ?>">
			<?php
			FLBuilder::render_module_html( 'njba-button', (object) array(
				'btn_class'            => 'njba-subscribe-form-submit',
				'button_font_icon'     => $settings->button_font_icon,
				'button_icon_aligment' => $settings->button_icon_aligment,
				'button_text'          => $settings->button_text,
				'buttton_icon_select'  => $settings->buttton_icon_select,
			) );
			?>
        </div>
        <div class="njba-form-error-message"><?php esc_html_e( 'Something went wrong. Please check your entries and try again.', 'bb-njba' ); ?></div>
    </div>
</div>
