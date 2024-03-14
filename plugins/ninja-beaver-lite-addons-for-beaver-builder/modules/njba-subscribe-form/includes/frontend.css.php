
.fl-node-<?php echo $id; ?>.njba-subscribe-box .njba-subscribe-inner {
    position: relative;
    float: left;
    height: 100%;
    width: 100%;
}

.fl-node-<?php echo $id; ?>.njba-subscribe-box .njba-subscribe-body {
    display: block;
    height: 100%;
    width: 100%;
    overflow: hidden;
}

<?php if ($settings->form_custom_title_desc === 'yes') : ?>
<?php 
    $heading_css_array = array(
        'heading_title_alignment'       => $settings->title_alignment,
        'heading_sub_title_alignment'   => $settings->description_alignment,
        'heading_title_font_size'       => $settings->title_font_size,
        'heading_title_font'            => $settings->title_font_family,
        'heading_sub_title_font'        => $settings->description_font_family,
        'heading_sub_title_font_size'   => $settings->description_font_size,
        'heading_title_line_height'     => $settings->title_line_height,
        'heading_sub_title_line_height' => $settings->description_line_height,
        'heading_title_color'           => $settings->title_color,
        'heading_margin'                => $settings->title_margin,
        'heading_sub_title_color'       => $settings->description_color,
        'heading_subtitle_margin'       => $settings->description_margin,
    );
    FLBuilder::render_module_css('njba-heading' , $id, $heading_css_array);
?>
<?php endif; ?>
.fl-node-<?php echo $id; ?>.njba-subscribe-box .njba-subscribe-content,
.fl-node-<?php echo $id; ?>.njba-subscribe-box .njba-subscribe-form {
    float: left;
    width: 100%;
}

.fl-node-<?php echo $id; ?>.njba-subscribe-box .njba-form-field {
    position: relative;
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form {
<?php if( $settings->form_bg_type === 'color' && $settings->form_bg_color !=='' ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->form_bg_color)) ?>, <?php echo $settings->form_background_opacity/100; ?>);
<?php } ?> <?php if( $settings->form_bg_image && $settings->form_bg_type === 'image' ) { ?> background-image: url('<?php echo $settings->form_bg_image_src; ?>');
<?php } ?> <?php if( $settings->form_bg_size ) { ?> background-size: <?php echo $settings->form_bg_size; ?>;
<?php } ?> <?php if( $settings->form_bg_repeat ) { ?> background-repeat: <?php echo $settings->form_bg_repeat; ?>;
<?php } ?> <?php if( $settings->form_border_width >= 0 ) { ?> border-width: <?php echo $settings->form_border_width.'px'; ?>;
<?php } ?> <?php if( $settings->form_border_color ) { ?> border-color: <?php echo '#'.$settings->form_border_color; ?>;
<?php } ?> <?php if( $settings->form_border_style ) { ?> border-style: <?php echo $settings->form_border_style; ?>;
<?php } ?> <?php if( $settings->form_border_radius >= 0 ) { ?> border-radius: <?php echo $settings->form_border_radius.'px'; ?>;
<?php } ?> <?php if ( 'yes' === $settings->form_shadow_display ) { ?> -webkit-box-shadow: <?php if($settings->form_shadow['horizontal'] !==''){ echo $settings->form_shadow['horizontal'].'px '; } if($settings->form_shadow['vertical'] !==''){ echo $settings->form_shadow['vertical'].'px '; } if($settings->form_shadow['blur'] !==''){ echo $settings->form_shadow['blur'].'px '; } if($settings->form_shadow['spread'] !==''){ echo $settings->form_shadow['spread'].'px '; } echo '#'.$settings->form_shadow_color; ?>;
    -moz-box-shadow: <?php if($settings->form_shadow['horizontal'] !==''){ echo $settings->form_shadow['horizontal'].'px '; } if($settings->form_shadow['vertical'] !==''){ echo $settings->form_shadow['vertical'].'px '; } if($settings->form_shadow['blur'] !==''){ echo $settings->form_shadow['blur'].'px '; } if($settings->form_shadow['spread'] !==''){ echo $settings->form_shadow['spread'].'px '; } echo '#'.$settings->form_shadow_color; ?>;
    -o-box-shadow: <?php if($settings->form_shadow['horizontal'] !==''){ echo $settings->form_shadow['horizontal'].'px '; } if($settings->form_shadow['vertical'] !==''){ echo $settings->form_shadow['vertical'].'px '; } if($settings->form_shadow['blur'] !==''){ echo $settings->form_shadow['blur'].'px '; } if($settings->form_shadow['spread'] !==''){ echo $settings->form_shadow['spread'].'px '; } echo '#'.$settings->form_shadow_color; ?>;
    box-shadow: <?php if($settings->form_shadow['horizontal'] !==''){ echo $settings->form_shadow['horizontal'].'px '; } if($settings->form_shadow['vertical'] !==''){ echo $settings->form_shadow['vertical'].'px '; } if($settings->form_shadow['blur'] !==''){ echo $settings->form_shadow['blur'].'px '; } if($settings->form_shadow['spread'] !==''){ echo $settings->form_shadow['spread'].'px '; } echo '#'.$settings->form_shadow_color; ?>;
<?php } ?><?php if( $settings->form_padding['top'] >= 0 ) { ?> padding-top: <?php echo $settings->form_padding['top'].'px'; ?>;
<?php } ?> <?php if( $settings->form_padding['right'] >= 0 ) { ?> padding-right: <?php echo $settings->form_padding['right'].'px'; ?>;
<?php } ?> <?php if( $settings->form_padding['bottom'] >= 0 ) { ?> padding-bottom: <?php echo $settings->form_padding['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->form_padding['left'] >= 0 ) { ?> padding-left: <?php echo $settings->form_padding['left'].'px'; ?>;
<?php } ?>

}

<?php if( $settings->input_custom_width === 'custom' ) { ?>
.fl-node-<?php echo $id; ?> .njba-subscribe-form .njba-form-field.njba-fname-field {
    width: <?php echo $settings->input_fname_width; ?>%;
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form .njba-form-field.njba-lname-field {
    width: <?php echo $settings->input_lname_width; ?>%;
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form .njba-form-field.njba-email-field {
    width: <?php echo $settings->input_email_width; ?>%;
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-subscribe-form .njba-form-field {
<?php if( $settings->layout === 'inline' || $settings->layout === 'compact' ) { ?> padding-right: <?php echo $settings->inputs_space; ?>%;
<?php } ?> <?php if( $settings->layout === 'stacked' ) { ?> margin-bottom: <?php echo $settings->inputs_space; ?>%;
<?php } ?>
}

<?php if( $settings->layout === 'compact' ) { ?>
.fl-node-<?php echo $id; ?> .njba-subscribe-form-compact .njba-form-field.njba-fname-field {
    padding-right: <?php echo $settings->inputs_space; ?>%;
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form-compact .njba-form-field.njba-lname-field {
    padding-right: <?php echo $settings->inputs_space; ?>%;
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=text],
.fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=email] {
<?php if( $settings->input_field_text_color ) { ?> color: <?php echo '#'.$settings->input_field_text_color; ?>;
<?php } ?> <?php if(  $settings->input_field_bg_color !=='' ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->input_field_bg_color)) ?>, <?php echo $settings->input_field_background_opacity/100; ?>);
<?php } ?> border-width: 0;
    border-color: <?php echo $settings->input_field_border_color ? '#'.$settings->input_field_border_color : 'transparent'; ?>;
<?php if( $settings->input_field_border_radius >= 0 ) { ?> border-radius: <?php echo $settings->input_field_border_radius.'px'; ?>;
    -moz-border-radius: <?php echo $settings->input_field_border_radius.'px'; ?>;
    -webkit-border-radius: <?php echo $settings->input_field_border_radius.'px'; ?>;
    -ms-border-radius: <?php echo $settings->input_field_border_radius.'px'; ?>;
    -o-border-radius: <?php echo $settings->input_field_border_radius.'px'; ?>;
<?php } ?><?php if( $settings->input_border_width['top'] >= 0 ) { ?> border-top-width: <?php echo $settings->input_border_width['top'].'px'; ?>;
<?php } ?> <?php if( $settings->input_border_width['bottom'] >= 0 ) { ?> border-bottom-width: <?php echo $settings->input_border_width['bottom'].'px'; ?>;
<?php } ?> <?php if( $settings->input_border_width['left'] >= 0 ) { ?> border-left-width: <?php echo $settings->input_border_width['left'].'px'; ?>;
<?php } ?> <?php if( $settings->input_border_width['right'] >= 0 ) { ?> border-right-width: <?php echo $settings->input_border_width['right'].'px'; ?>;
<?php } ?><?php if( $settings->input_field_box_shadow === 'yes' ) { ?> box-shadow: <?php echo ($settings->input_shadow_direction === 'inset') ? $settings->input_shadow_direction : ''; ?> 0 0 10px<?php echo ' #'.$settings->input_shadow_color; ?>;
    -moz-box-shadow: <?php echo ($settings->input_shadow_direction === 'inset') ? $settings->input_shadow_direction : ''; ?> 0 0 10px<?php echo ' #'.$settings->input_shadow_color; ?>;
    -webkit-box-shadow: <?php echo ($settings->input_shadow_direction === 'inset') ? $settings->input_shadow_direction : ''; ?> 0 0 10px<?php echo ' #'.$settings->input_shadow_color; ?>;
    -ms-box-shadow: <?php echo ($settings->input_shadow_direction === 'inset') ? $settings->input_shadow_direction : ''; ?> 0 0 10px<?php echo ' #'.$settings->input_shadow_color; ?>;
    -o-box-shadow: <?php echo ($settings->input_shadow_direction === 'inset') ? $settings->input_shadow_direction : ''; ?> 0 0 10px<?php echo ' #'.$settings->input_shadow_color; ?>;
<?php } ?><?php if( $settings->input_field_padding['top'] >= 0 ) { ?> padding-top: <?php echo $settings->input_field_padding['top'].'px'; ?>;
<?php } ?><?php if( $settings->input_field_padding['bottom'] >= 0 ) { ?> padding-bottom: <?php echo $settings->input_field_padding['bottom'].'px'; ?>;
<?php } ?><?php if( $settings->input_field_padding['left'] >= 0 ) { ?> padding-left: <?php echo $settings->input_field_padding['left'].'px'; ?>;
<?php } ?><?php if( $settings->input_field_padding['right'] >= 0 ) { ?> padding-right: <?php echo $settings->input_field_padding['right'].'px'; ?>;
<?php } ?><?php if( $settings->input_field_text_alignment ) { ?> text-align: <?php echo $settings->input_field_text_alignment; ?>;
<?php } ?><?php if( $settings->input_font_family['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->input_font_family ); ?><?php } ?><?php if( isset($settings->input_font_size['desktop']) && $settings->input_size === 'custom' ) { ?> font-size: <?php echo $settings->input_font_size['desktop'].'px'; ?>;
<?php } ?> text-transform: <?php echo $settings->input_text_transform; ?>;
    height: <?php echo $settings->input_height.'px'; ?>;
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=text]:focus,
.fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=email]:focus {
    border-color: <?php echo $settings->input_field_focus_color ? '#' . $settings->input_field_focus_color : 'transparent'; ?>;
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=text]::-webkit-input-placeholder {
<?php if( $settings->input_placeholder_color && $settings->input_placeholder_display === 'block' ) { ?> color: <?php echo '#'.$settings->input_placeholder_color; ?>;
<?php } else { ?> color: transparent;
    opacity: 0;
<?php } ?> <?php if( isset($settings->placeholder_font_size['desktop']) && $settings->placeholder_size === 'custom' ) { ?> font-size: <?php echo $settings->placeholder_font_size['desktop'].'px'; ?>;
<?php } ?> text-transform: <?php echo $settings->placeholder_text_transform; ?>;
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=text]:-moz-placeholder {
<?php if( $settings->input_placeholder_color && $settings->input_placeholder_display === 'block' ) { ?> color: <?php echo '#'.$settings->input_placeholder_color; ?>;
<?php } else { ?> color: transparent;
    opacity: 0;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=text]::-moz-placeholder {
<?php if( $settings->input_placeholder_color && $settings->input_placeholder_display === 'block' ) { ?> color: <?php echo '#'.$settings->input_placeholder_color; ?>;
<?php } else { ?> color: transparent;
    opacity: 0;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=text]:-ms-input-placeholder {
<?php if( $settings->input_placeholder_color && $settings->input_placeholder_display === 'block' ) { ?> color: <?php echo '#'.$settings->input_placeholder_color; ?>;
<?php } else { ?> color: transparent;
    opacity: 0;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=email]::-webkit-input-placeholder {
<?php if( $settings->input_placeholder_color && $settings->input_placeholder_display === 'block' ) { ?> color: <?php echo '#'.$settings->input_placeholder_color; ?>;
<?php } else { ?> color: transparent;
    opacity: 0;
<?php } ?> <?php if( isset($settings->placeholder_font_size['desktop']) && $settings->placeholder_size === 'custom' ) { ?> font-size: <?php echo $settings->placeholder_font_size['desktop'].'px'; ?>;
<?php } ?> text-transform: <?php echo $settings->placeholder_text_transform; ?>;
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=email]:-moz-placeholder {
<?php if( $settings->input_placeholder_color ) { ?> color: <?php echo '#'.$settings->input_placeholder_color; ?>;
<?php } else { ?> color: transparent;
    opacity: 0;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=email]::-moz-placeholder {
<?php if( $settings->input_placeholder_color && $settings->input_placeholder_display === 'block' ) { ?> color: <?php echo '#'.$settings->input_placeholder_color; ?>;
<?php } else { ?> color: transparent;
    opacity: 0;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=email]:-ms-input-placeholder {
<?php if( $settings->input_placeholder_color && $settings->input_placeholder_display === 'block' ) { ?> color: <?php echo '#'.$settings->input_placeholder_color; ?>;
<?php } else { ?> color: transparent;
    opacity: 0;
<?php } ?>
}

<?php
        $button_css_array = array(
            'button_text'                   => $settings->button_text,
            'buttton_icon_select'           => $settings->buttton_icon_select,
            'button_font_icon'              => $settings->button_font_icon,
            'button_custom_icon'            => $settings->button_custom_icon,
            'button_icon_aligment'          => $settings->button_icon_aligment,
            'button_background_color'       => $settings->button_background_color,
            'button_background_hover_color' => $settings->button_background_hover_color,
            'button_text_color'             => $settings->button_text_color,
            'button_text_hover_color'       => $settings->button_text_hover_color,
            'icon_color'                    => $settings->icon_color,
            'icon_hover_color'              => $settings->icon_hover_color,
            'button_border_color'           => $settings->button_border_color,
            'button_border_hover_color'     => $settings->button_border_hover_color,
            'button_style'                  => $settings->button_style,
            'transition'                    => $settings->transition,
            'button_border_width'           => $settings->button_border_width,
            'button_border_radius'          => $settings->button_border_radius,
            'button_border_style'           => $settings->button_border_style,
            'button_box_shadow'             => $settings->button_box_shadow,
            'button_box_shadow_color'       => $settings->button_box_shadow_color,
            'button_padding'                => $settings->button_padding,
            'button_margin'                 => $settings->button_margin,
            'alignment'                     => $settings->alignment,
            // 'custom_width' => $settings->custom_width,
            // 'custom_height' => $settings->custom_height,
            'button_font_family'            => $settings->button_font_family,
            'button_font_size'              => $settings->button_font_size,
            'icon_font_size'                => $settings->icon_font_size,
            'icon_padding'                  => $settings->icon_padding,
            'icon_margin'                   => $settings->icon_margin,
        );
        FLBuilder::render_module_css('njba-button' , $id, $button_css_array);
?>
.fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn {
<?php if($settings->width === 'custom'){ ?><?php if($settings->custom_width){?> width: <?php echo $settings->custom_width.'px;';?> <?php }?> <?php if($settings->custom_height){?> min-height:<?php echo $settings->custom_height.'px;';?><?php }?><?php } ?><?php if($settings->width === 'full_width'){?> display: block;
<?php }?>
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form input.njba-form-error {
    border-color: #<?php echo $settings->validation_message_border_color; ?>;
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form .njba-form-error-message {
<?php if( $settings->validation_message_color ) { ?> color: <?php echo '#'.$settings->validation_message_color; ?>;
<?php } ?> <?php if( isset($settings->validation_error_font_size['desktop'] ) && $settings->success_message_size === 'custom' ) { ?> font-size: <?php echo $settings->validation_error_font_size['desktop'].'px'; ?>;
<?php } ?> text-transform: <?php echo $settings->error_text_transform; ?>;
    position: relative;
    margin-top: 5px;
    padding-left: 20px;
    text-align: left;
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form .njba-form-error-msg {
<?php if( $settings->validation_message_color ) { ?> color: <?php echo '#'.$settings->validation_message_color; ?>;
<?php } ?><?php if( isset($settings->validation_error_font_size['desktop'] ) && $settings->success_message_size === 'custom' ) { ?> font-size: <?php echo $settings->validation_error_font_size['desktop'].'px'; ?>;
<?php } ?> text-transform: <?php echo $settings->error_text_transform; ?>;
    position: relative;
    margin-top: 5px;
    padding-left: 20px;
    text-align: left;
}

.fl-node-<?php echo $id; ?> .njba-subscribe-form .njba-form-success-message {
<?php if( isset($settings->success_message_font_size['desktop']) && $settings->success_message_size === 'custom' ) { ?> font-size: <?php echo $settings->success_message_font_size['desktop'].'px'; ?>;
<?php } ?> <?php if( $settings->success_message_color ) { ?> color: <?php echo '#'.$settings->success_message_color; ?>;
<?php } ?> text-transform: <?php echo $settings->success_message_text_transform; ?>;
}

@media only screen and (max-width: 768px) {
    .fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=text],
    .fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=email] {
    <?php if( isset($settings->input_font_size['tablet'])  && $settings->input_size === 'custom' ) { ?> font-size: <?php echo $settings->input_font_size['tablet'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-subscribe-form .njba-sub-form-error-message {
    <?php if( isset($settings->validation_error_font_size['tablet']) && $settings->success_message_size === 'custom' ) { ?> font-size: <?php echo $settings->validation_error_font_size['tablet'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-subscribe-form .njba-form-success-message {
    <?php if( isset($settings->success_message_font_size['tablet']) && $settings->success_message_size === 'custom' ) { ?> font-size: <?php echo $settings->success_message_font_size['tablet'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=text]::-webkit-input-placeholder,
    .fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=email]::-webkit-input-placeholder {
    <?php if( isset($settings->placeholder_font_size['tablet']) && $settings->placeholder_size === 'custom' ) { ?> font-size: <?php echo $settings->placeholder_font_size['tablet'].'px'; ?>;
    <?php } ?>
    }

}

@media only screen and (max-width: 480px) {
    .fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=text],
    .fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=email] {
    <?php if( isset($settings->input_font_size['mobile'] ) && $settings->input_size === 'custom' ) { ?> font-size: <?php echo $settings->input_font_size['mobile'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-subscribe-form .njba-sub-form-error-message {
    <?php if( isset($settings->validation_error_font_size['mobile']) && $settings->success_message_size === 'custom' ) { ?> font-size: <?php echo $settings->validation_error_font_size['mobile'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-subscribe-form .njba-form-success-message {
    <?php if( isset($settings->success_message_font_size['mobile']) && $settings->success_message_size === 'custom' ) { ?> font-size: <?php echo $settings->success_message_font_size['mobile'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=text]::-webkit-input-placeholder,
    .fl-node-<?php echo $id; ?> .njba-subscribe-form input[type=email]::-webkit-input-placeholder {
    <?php if( isset($settings->placeholder_font_size['mobile']) && $settings->placeholder_size === 'custom' ) { ?> font-size: <?php echo $settings->placeholder_font_size['mobile'].'px'; ?>;
    <?php } ?>
    }
}
