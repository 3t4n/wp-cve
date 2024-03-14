<?php $settings->form_bg_color_opc = ( $settings->form_bg_color_opc !== '' ) ? $settings->form_bg_color_opc : '100'; ?>
<?php $settings->input_background_color_opc = ( $settings->input_background_color_opc !== '' ) ? $settings->input_background_color_opc : '100'; ?>
<?php $settings->btn_border_width = ( $settings->btn_border_width !== '' ) ? $settings->btn_border_width : '0'; ?>
<?php if ($settings->form_custom_title_desc === 'yes') : ?>
<?php
        $heading_css_array = array(
        'heading_title_alignment'      => $settings->title_alignment,
        'heading_sub_title_alignment'       => $settings->description_alignment,
        'heading_title_font_size' => $settings->title_font_size,
        'heading_title_font' => $settings->title_font_family,
        'heading_sub_title_font' =>$settings->description_font_family,
        'heading_sub_title_font_size' =>$settings->description_font_size,
        'heading_title_line_height' => $settings->title_line_height,
        'heading_sub_title_line_height' => $settings->description_line_height,
        'heading_title_color' => $settings->title_color,
        'heading_sub_title_color' => $settings->description_color,
        'heading_margin' => $settings->title_margin,
        'heading_subtitle_margin' => $settings->description_margin,
     
        );
        FLBuilder::render_module_css('njba-heading' , $id, $heading_css_array);
?>
<?php endif; ?>
<?php
        $button_css_array = array(
        'icon_color' =>$settings->icon_color,
		'button_background_color' => $settings->btn_background_color,
		'button_background_hover_color' => $settings->btn_background_hover_color,
		'button_text_color' => $settings->btn_text_color,
		'button_text_hover_color' => $settings->btn_text_hover_color,
		'button_box_shadow' => $settings->btn_box_shadow,
		'button_box_shadow_color' => $settings->btn_shadow_color,
		'button_padding' => $settings->btn_padding,
		'button_margin' => $settings->btn_margin,
		'alignment' => $settings->btn_align,
		'icon_margin' => $settings->icon_margin,
		'button_font_family' => $settings->button_font_family,
		'button_font_size' => $settings->button_font_size,
		'button_border_width' => $settings->btn_border_width,		
		'button_border_color' => $settings->btn_border_color,
		'button_border_hover_color' => $settings->btn_hover_border_color,
		'button_border_radius' => $settings->btn_radius,
		'button_border_style' => $settings->btn_border_style,
    
        );
        FLBuilder::render_module_css('njba-button' , $id, $button_css_array);
?>
.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group label {

<?php if( $settings->form_label_color !== '' ) { ?> color: <?php echo '#'.$settings->form_label_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-input-group .njba-input-icon i {

<?php if( $settings->input_icon_color !== '' ) { ?> color: <?php echo '#'.$settings->input_icon_color; ?>;
<?php } ?><?php echo ( $settings->input_icon_size !== '' ) ? 'font-size: ' . $settings->input_icon_size.'px;' : ''; ?>
}

.fl-node-<?php echo $id; ?> .njba-input-group .njba-input-icon {

<?php echo ( $settings->position_top !== '' ) ? 'top: ' . $settings->position_top . 'px;' : ''; ?> <?php echo ( $settings->position_left !== '' ) ? 'left: ' . $settings->position_left . 'px;' : ''; ?>
}

<?php if( $settings->enable_icon === 'yes' ) { ?>
.fl-node-<?php echo $id; ?> .njba-contact-form textarea,
.fl-node-<?php echo $id; ?> .njba-contact-form input[type="text"],
.fl-node-<?php echo $id; ?> .njba-contact-form input[type="tel"],
.fl-node-<?php echo $id; ?> .njba-contact-form input[type="email"] {
<?php if( $settings->input_padding !== '' ) { ?> padding-left: <?php echo $settings->input_padding['left']+12 .'px !important'; ?>;
<?php } ?>
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-contact-form label {
<?php if( $settings->label_font_family['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->label_font_family ); ?><?php } ?> <?php if( $settings->label_font_size['desktop'] ) { ?> font-size: <?php echo $settings->label_font_size['desktop'].'px'; ?>;
<?php } ?> text-transform: <?php echo $settings->label_text_transform; ?>;
}

.fl-node-<?php echo $id; ?> .njba-contact-form input,
.fl-node-<?php echo $id; ?> .njba-contact-form textarea {
<?php if( $settings->input_font_family['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->input_font_family ); ?><?php } ?> <?php if( $settings->input_font_size['desktop'] ) { ?> font-size: <?php echo $settings->input_font_size['desktop'].'px'; ?>;
<?php } ?> text-transform: <?php echo $settings->input_text_transform; ?>;
}

.fl-node-<?php echo $id; ?> .njba-contact-form textarea,
.fl-node-<?php echo $id; ?> .njba-contact-form input[type="text"],
.fl-node-<?php echo $id; ?> .njba-contact-form input[type="tel"],
.fl-node-<?php echo $id; ?> .njba-contact-form input[type="email"] {
<?php if( $settings->input_padding['top'] !== '' ) { ?> padding-top: <?php echo $settings->input_padding['top'].'px'; ?>;
<?php } ?> <?php if( $settings->input_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->input_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->input_padding['left'] !== '' ) { ?> padding-left: <?php echo $settings->input_padding['left'].'px'; ?>;
<?php } ?> <?php if( $settings->input_padding['right'] !== '' ) { ?> padding-right: <?php echo $settings->input_padding['right'].'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group-wrap input,
.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group-wrap input:focus,
.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group-wrap textarea {
<?php if( $settings->input_background_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->input_background_color )) ?>, <?php echo $settings->input_background_color_opc/100; ?>);
<?php } ?> text-align: <?php echo $settings->input_text_align; ?>;
<?php if( $settings->input_border_color ) { ?> border-color: <?php echo '#'.$settings->input_border_color; ?>;
<?php } ?> <?php if( $settings->input_border_radius ) { ?> border-radius: <?php echo $settings->input_border_radius.'px'; ?>;
<?php } ?> <?php if( $settings->input_border_width ) { ?> border-width: <?php echo $settings->input_border_width.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-input-group-wrap .njba-input-group input[type="text"]::-moz-placeholder,
.fl-node-<?php echo $id; ?> .njba-input-group-wrap .njba-input-group input[type="email"]::-moz-placeholder,
.fl-node-<?php echo $id; ?> .njba-input-group-wrap .njba-input-group input[type="tel"]::-moz-placeholder,
.fl-node-<?php echo $id; ?> .njba-input-group-wrap .njba-input-group input[type="number"]::-moz-placeholder,
.fl-node-<?php echo $id; ?> .njba-input-group-wrap .njba-input-group textarea::-moz-placeholder,
.fl-node-<?php echo $id; ?> .njba-input-group-wrap .njba-input-group textarea,
.fl-node-<?php echo $id; ?> .njba-input-group-wrap .njba-input-group input {
<?php if( $settings->input_text_color !== '' ) { ?> color: <?php echo '#'.$settings->input_text_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-input-group-wrap .njba-input-group input[type="text"]::-webkit-input-placeholder,
.fl-node-<?php echo $id; ?> .njba-input-group-wrap .njba-input-group input[type="email"]::-webkit-input-placeholder,
.fl-node-<?php echo $id; ?> .njba-input-group-wrap .njba-input-group input[type="tel"]::-webkit-input-placeholder,
.fl-node-<?php echo $id; ?> .njba-input-group-wrap .njba-input-group input[type="number"]::-webkit-input-placeholder,
.fl-node-<?php echo $id; ?> .njba-input-group-wrap .njba-input-group textarea::-webkit-input-placeholder {

<?php if( $settings->input_text_color !== '' ) { ?> color: <?php echo '#'.$settings->input_text_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-input-group-wrap .njba-input-group textarea,
.fl-node-<?php echo $id; ?> .njba-input-group-wrap .njba-input-group input {
<?php if( $settings->input_text_color !== '' ) { ?> color: <?php echo '#'.$settings->input_text_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-contact-form.njba-form-style2 .njba-input-group-wrap input,
.fl-node-<?php echo $id; ?> .njba-contact-form.njba-form-style2 .njba-input-group-wrap textarea {
<?php if( $settings->input_border['top'] !== '' ) { ?> border-top: <?php echo $settings->input_border['top'].'px '; echo $settings->border_style; echo ' #'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->input_border['bottom'] !== '' ) { ?> border-bottom: <?php echo $settings->input_border['bottom'].'px '; echo $settings->border_style; echo ' #'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->input_border['left'] !== '' ) { ?> border-left: <?php echo $settings->input_border['left'].'px '; echo $settings->border_style; echo ' #'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->input_border['right'] !== '' ) { ?> border-right: <?php echo $settings->input_border['right'].'px '; echo $settings->border_style; echo ' #'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->border_radius !== '' ) { ?> border-radius: <?php echo $settings->border_radius.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-contact-form.njba-form-style3 .njba-input-group-wrap input {
<?php if( $settings->input_border['top'] !== '' ) { ?> border-top: <?php echo $settings->input_border['top'].'px ' ; echo $settings->border_style; echo ' #'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->input_border['bottom'] !== '' ) { ?> border-bottom: <?php echo $settings->input_border['bottom'].'px '; echo $settings->border_style; echo ' #'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->input_border['left'] !== '' ) { ?> border-left: <?php echo $settings->input_border['left'].'px '; echo $settings->border_style; echo ' #'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->input_border['right'] !== '' ) { ?> border-right: <?php echo $settings->input_border['right'].'px '; echo $settings->border_style;  echo ' #'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->border_radius !== '' ) { ?> border-radius: <?php echo $settings->border_radius.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-contact-form.njba-form-style3 .njba-input-group-wrap textarea {

<?php if( $settings->textarea_border['top'] !== '' ) { ?> border-top: <?php echo $settings->textarea_border['top'].'px '; echo $settings->border_style; echo ' #'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->textarea_border['bottom'] !== '' ) { ?> border-bottom: <?php echo $settings->textarea_border['bottom'].'px '; echo $settings->border_style; echo ' #'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->textarea_border['left'] !== '' ) { ?> border-left: <?php echo $settings->textarea_border['left'].'px '; echo $settings->border_style; echo ' #'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->textarea_border['right'] !== '' ) { ?> border-right: <?php echo $settings->textarea_border['right'].'px '; echo $settings->border_style; echo ' #'.$settings->border_color; ?>;
<?php } ?> <?php if( $settings->border_radius !== '' ) { ?> border-radius: <?php echo $settings->border_radius.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-contact-form textarea {
<?php if( $settings->msg_height !== '' ) { ?> min-height: <?php echo $settings->msg_height.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-form-error-message-required {
<?php if( $settings->invalid_msg_color !== '' ) { ?> background: <?php echo '#'.$settings->invalid_msg_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-form-error-message span {
<?php if( $settings->invalid_msg_color !== '' ) { ?> color: <?php echo '#'.$settings->invalid_msg_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-success,
.fl-node-<?php echo $id; ?> .njba-success-none,
.fl-node-<?php echo $id; ?> .njba-success-msg {
<?php if( $settings->success_msg_color !== '' ) { ?> color: <?php echo '#'.$settings->success_msg_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-send-error {
<?php if( $settings->error_msg_color !== '' ) { ?> color: <?php echo '#'.$settings->error_msg_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group-wrap .njba-error textarea,
.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group-wrap .njba-error input[type=text],
.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group-wrap .njba-error input[type=tel],
.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group-wrap .njba-error input[type=email] {
<?php if( $settings->invalid_border_color !== '' ) { ?> border-color: <?php echo '#'.$settings->invalid_border_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group-wrap .njba-error-msg input[type=email] {
<?php if( $settings->invalid_border_color !== '' ) { ?> border-color: <?php echo '#'.$settings->invalid_border_color; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-contact-form {

<?php if( $settings->form_padding['top'] !== '' ) { ?> padding-top: <?php echo $settings->form_padding['top'].'px'; ?>;
<?php } ?> <?php if( $settings->form_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->form_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->form_padding['left'] !== '' ) { ?> padding-left: <?php echo $settings->form_padding['left'].'px'; ?>;
<?php } ?> <?php if( $settings->form_padding['right'] !== '' ) { ?> padding-right: <?php echo $settings->form_padding['right'].'px'; ?>;
<?php } ?> <?php if( $settings->form_bg_color !== '' ) { ?> background: <?php echo '#'.$settings->form_bg_color; ?>;
<?php } ?><?php if( $settings->form_border_width['top'] !== '' ) { ?> border-top: <?php echo $settings->form_border_width['top'].'px '; echo $settings->form_border_style; ?> <?php echo ' #'.$settings->form_border_color; ?>;
<?php } ?> <?php if( $settings->form_border_width['bottom'] !== '' ) { ?> border-bottom: <?php echo $settings->form_border_width['bottom'].'px '; echo $settings->form_border_style; ?> <?php echo ' #'.$settings->form_border_color; ?>;
<?php } ?> <?php if( $settings->form_border_width['left'] !== '' ) { ?> border-left: <?php echo $settings->form_border_width['left'].'px '; echo $settings->form_border_style; ?> <?php echo ' #'.$settings->form_border_color; ?>;
<?php } ?> <?php if( $settings->form_border_width['right'] !== '' ) { ?> border-right: <?php echo $settings->form_border_width['right'].'px '; echo $settings->form_border_style; ?> <?php echo ' #'.$settings->form_border_color; ?>;
<?php } ?> <?php if( $settings->form_radius !== '' ) { ?> border-radius: <?php echo $settings->form_radius.'px'; ?>;
<?php } ?> <?php if( $settings->form_box_shadow !== '' ) { ?> -webkit-box-shadow: <?php if( isset($settings->form_box_shadow['horizontal'] ) ) { echo $settings->form_box_shadow['horizontal'].'px '; } if( isset($settings->form_box_shadow['vertical'] ) ) {  echo $settings->form_box_shadow['vertical'].'px '; } if( isset($settings->form_box_shadow['blur'] ) ) { echo $settings->form_box_shadow['blur'].'px '; } if( isset($settings->form_box_shadow['spread'] ) ) { echo $settings->form_box_shadow['spread'].'px '; } ?> rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_shadow_color )) ?>, <?php echo $settings->box_shadow_opacity/100; ?>);
    -moz-box-shadow: <?php if( isset($settings->form_box_shadow['horizontal'] ) ) { echo $settings->form_box_shadow['horizontal'].'px '; } if( isset($settings->form_box_shadow['vertical'] ) ) {echo $settings->form_box_shadow['vertical'].'px '; } if( isset($settings->form_box_shadow['blur'] ) ) { echo $settings->form_box_shadow['blur'].'px '; } if( isset($settings->form_box_shadow['spread'] ) ) {echo $settings->form_box_shadow['spread'].'px '; } ?> rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_shadow_color )) ?>, <?php echo $settings->box_shadow_opacity/100; ?>);
    -o-box-shadow: <?php if( isset($settings->form_box_shadow['horizontal'] ) ) { echo $settings->form_box_shadow['horizontal'].'px '; } if( isset($settings->form_box_shadow['vertical'] ) ) { echo $settings->form_box_shadow['vertical'].'px '; } if( isset($settings->form_box_shadow['blur'] ) ) { echo $settings->form_box_shadow['blur'].'px '; } if( isset($settings->form_box_shadow['spread'] ) ) { echo $settings->form_box_shadow['spread'].'px '; } ?> rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_shadow_color )) ?>, <?php echo $settings->box_shadow_opacity/100; ?>);
    box-shadow: <?php if( isset($settings->form_box_shadow['horizontal'] ) ) { echo $settings->form_box_shadow['horizontal'].'px '; } if( isset($settings->form_box_shadow['vertical'] ) ) { echo $settings->form_box_shadow['vertical'].'px '; } if( isset($settings->form_box_shadow['blur'] ) ) { echo $settings->form_box_shadow['blur'].'px '; } if( isset($settings->form_box_shadow['spread'] ) ) { echo $settings->form_box_shadow['spread'].'px '; }?> rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_shadow_color )) ?>, <?php echo $settings->box_shadow_opacity/100; ?>);

}

<?php } ?>

/* Form Style */
.fl-node-<?php echo $id; ?> .njba-contact-form {

<?php if ( $settings->form_bg_type === 'color' ) { ?> <?php if( $settings->form_bg_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->form_bg_color )) ?>, <?php echo $settings->form_bg_color_opc/100; ?>);
<?php } ?> <?php } elseif ( $settings->form_bg_type === 'image' ) { ?> background-image: url(<?php echo FLBuilderPhoto::get_attachment_data($settings->form_bg_img)->url; ?>);
    background-position: <?php echo $settings->form_bg_img_pos; ?>;
    background-size: <?php echo $settings->form_bg_img_size; ?>;
    background-repeat: <?php echo $settings->form_bg_img_repeat; ?>;

<?php } ?>
}

<?php if( $settings->input_custom_width === 'custom' ) { ?>
.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group.njba-first-name,
.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group.njba-last-name,
.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group.njba-subject,
.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group.njba-phone {
    width: <?php echo $settings->input_name_width; ?>%;
}

.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group.njba-message {
<?php if( $settings->input_textarea_width ) { ?> width: <?php echo $settings->input_textarea_width; ?>%;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group.njba-email {
<?php if( $settings->input_email_width ) { ?> width: <?php echo $settings->input_email_width; ?>%;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-contact-form .njba-contact-form-submit {
<?php if( $settings->input_button_width ) { ?> width: <?php echo $settings->input_button_width; ?>%;
<?php } ?>
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-contact-form .njba-input-group {
<?php if( $settings->inputs_space ) { ?> margin-bottom: <?php echo $settings->inputs_space; ?>%;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-contact-form .njba-inline-group:nth-child(2n) {
<?php if( $settings->input_spacing ) { ?> padding-left: <?php echo $settings->input_spacing.'px'; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-contact-form .njba-inline-group:nth-child(2n+1) {
<?php if( $settings->input_spacing ) { ?> padding-right: <?php echo $settings->input_spacing.'px'; ?>;
<?php } ?>
}

@media ( max-width: 991px ) {

    .fl-node-<?php echo $id; ?> .njba-contact-form label {
    <?php if( $settings->label_font_size['medium'] ) { ?> font-size: <?php echo $settings->label_font_size['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-contact-form-submit {
    <?php if( $settings->label_font_size['medium'] ) { ?> font-size: <?php echo $settings->label_font_size['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-contact-form input,
    .fl-node-<?php echo $id; ?> .njba-contact-form textarea {
    <?php if( $settings->input_font_size['medium'] ) { ?> font-size: <?php echo $settings->input_font_size['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media ( max-width: 767px ) {

    .fl-node-<?php echo $id; ?> .njba-contact-form label {
    <?php if( $settings->label_font_size['small'] ) { ?> font-size: <?php echo $settings->label_font_size['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-contact-form input,
    .fl-node-<?php echo $id; ?> .njba-contact-form textarea {
    <?php if( $settings->input_font_size['small'] ) { ?> font-size: <?php echo $settings->input_font_size['small'].'px'; ?>;
    <?php } ?>
    }

}
