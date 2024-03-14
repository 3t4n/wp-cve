<?php

// Form Background Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-box_v2, .fl-node-$id .tnit-form-box.tnit-form-box_v2.tnit-form-box-inline.tnit-form-style-2",
		'props'    => array(
			'background-color' => $settings->form_bg_color,
		),
	)
);

// Outer Border Rule.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'form_border',
		'selector'     => ".fl-node-$id .tnit-form-box",
	)
);

// Outer Padding Rule.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'form_padding',
		'selector'     => ".fl-node-$id .tnit-form-box_v2",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'form_padding_top',
			'padding-right'  => 'form_padding_right',
			'padding-bottom' => 'form_padding_bottom',
			'padding-left'   => 'form_padding_left',
		),
	)
);

// Title Top Bottom Margin.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-contact-title-holder",
		'props'    => array(
			'margin-bottom' => ( '' !== $settings->title_margin_bottom ) ? $settings->title_margin_bottom . 'px' : '',
		),
	)
);

// Descrpition Bottom Margin.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-contact-desc-holder",
		'props'    => array(
			'margin-bottom' => ( '' !== $settings->descrpition_margin_bottom ) ? $settings->descrpition_margin_bottom . 'px' : '',
		),
	)
);

// Input Color and Background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-box .inner-holder .tnit-contact-name, .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-name::placeholder, 
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-email, .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-email::placeholder,
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-phone, .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-phone::placeholder,
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-subject, .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-subject::placeholder,
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-message, .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-message::placeholder",
		'props'    => array(
			'color'            => $settings->input_field_text_color,
			'background-color' => $settings->input_field_bg_color,
		),
	)
);

// Input Color and Background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-box .inner-holder .tnit-contact-name::placeholder, 
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-email::placeholder,
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-phone::placeholder,
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-subject::placeholder,
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-message::placeholder",
		'props'    => array(
			'color' => $settings->input_placeholder_color,
		),
	)
);
// Input Border Rule.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'input_border',
		'selector'     => ".fl-node-$id .tnit-contact-form .inner-holder input[type='text'],
							.fl-node-$id .tnit-contact-form .inner-holder input[type='tel'],
							.fl-node-$id .tnit-contact-form .inner-holder input[type='email'],
							.fl-node-$id .tnit-form-box .tnit-contact-form .inner-holder textarea",
	)
);
// Input Border Hover Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-contact-form .inner-holder input[type='text']:focus,
						.fl-node-$id .tnit-contact-form .inner-holder input[type='tel']:focus,
						.fl-node-$id .tnit-contact-form .inner-holder input[type='email']:focus,
						.fl-node-$id .tnit-form-box .tnit-contact-form .inner-holder textarea:focus",
		'props'    => array(
			'border-color' => $settings->input_border_hover_color,
		),
	)
);
// Description and Title Alignment.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'content_alignment',
		'selector'     => ".fl-node-$id .tnit-contact-title-holder, .fl-node-$id .tnit-contact-desc-holder",
		'prop'         => 'text-align',
	)
);
// Input Padding Rule.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'input_field_padding',
		'selector'     => ".fl-node-$id .tnit-form-box .inner-holder .tnit-contact-name, 
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-email,
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-phone,
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-subject,
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-message",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'input_field_padding_top',
			'padding-right'  => 'input_field_padding_right',
			'padding-bottom' => 'input_field_padding_bottom',
			'padding-left'   => 'input_field_padding_left',
		),
	)
);
// Input Field Margin Bottom.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-box .inner-holder:not(.tnit-btn-holder)",
		'props'    => array(
			'margin-bottom' => ( '' !== $settings->input_field_margin ) ? $settings->input_field_margin . 'px' : '',
		),
	)
);
// Input Height.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-contact-form .inner-holder input[type='text'],
						.fl-node-$id .tnit-contact-form .inner-holder input[type='tel'],
						.fl-node-$id .tnit-contact-form .inner-holder input[type='email']",
		'props'    => array(
			'height' => ( '' !== $settings->input_field_height ) ? $settings->input_field_height . 'px' : '',
		),
	)
);
// Textarea Height.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-box .tnit-contact-form .inner-holder textarea",
		'props'    => array(
			'height' => ( '' !== $settings->input_textarea_height ) ? $settings->input_textarea_height . 'px' : '',
		),
	)
);

// Button color and background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-box .inner-holder .btn-submit",
		'props'    => array(
			'color'            => $settings->btn_text_color,
			'background-color' => $settings->btn_bg_color,
		),
	)
);
// Button Hover color and background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-box .inner-holder .btn-submit:hover",
		'props'    => array(
			'color'            => $settings->btn_text_hover_color,
			'background-color' => $settings->btn_bg_hover_color,
			'border-color'     => $settings->btn_border_hcolor,
		),
	)
);
// Button Border Rule.
FLBuilderCSS::border_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'button_border',
		'selector'     => ".fl-node-$id .tnit-form-box .inner-holder .btn-submit",
	)
);

// Button Structure.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-box .inner-holder .btn-submit",
		'enabled'  => 'auto' === $settings->cta_width,
		'props'    => array(
			'width' => 'auto',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-box .inner-holder .btn-submit",
		'enabled'  => 'full' === $settings->cta_width,
		'props'    => array(
			'width' => '100%',
		),
	)
);

FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-box .inner-holder .btn-submit",
		'enabled'  => 'custom' === $settings->cta_width,
		'props'    => array(
			'width' => ( '' !== $settings->cta_custom_width ) ? $settings->cta_custom_width . 'px' : '',
		),
	)
);
// Button padding.
FLBuilderCSS::dimension_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'btn_padding',
		'selector'     => ".fl-node-$id .tnit-form-box .inner-holder .btn-submit",
		'unit'         => 'px',
		'props'        => array(
			'padding-top'    => 'btn_padding_top',
			'padding-right'  => 'btn_padding_right',
			'padding-bottom' => 'btn_padding_bottom',
			'padding-left'   => 'btn_padding_left',
		),
	)
);

// Button Alignment.
FLBuilderCSS::responsive_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'btn_alignment',
		'selector'     => ".fl-node-$id .tnit-form-box .inner-holder.tnit-btn-holder",
		'prop'         => 'text-align',
	)
);

// Button Margin Top.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-box .inner-holder .btn-submit",
		'props'    => array(
			'margin-top' => ( '' !== $settings->btn_margin_top ) ? $settings->btn_margin_top . 'px' : '',
		),
	)
);

// Title Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'tnit_title_typography',
		'selector'     => ".fl-node-$id .tnit-form-box_v2 .tnit-contact-title-holder h3",
	)
);

// Title Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-contact-title-holder .tnit-title-contact",
		'props'    => array(
			'color' => $settings->title_color,
		),
	)
);

// Descrpition Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'tnit_description_typography',
		'selector'     => ".fl-node-$id .tnit-form-box_v2 .tnit-contact-desc-holder",
	)
);

// Button color and background color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-contact-desc-holder .tnit-desc-contactv1",
		'props'    => array(
			'color' => $settings->decrpition_color,
		),
	)
);

// Input Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'tnit_input_typography',
		'selector'     => ".fl-node-$id .tnit-form-box .inner-holder .tnit-contact-name, .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-name::placeholder, 
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-email, .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-email::placeholder,
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-phone, .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-phone::placeholder,
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-subject, .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-subject::placeholder,
					   .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-message, .fl-node-$id .tnit-form-box .inner-holder .tnit-contact-message::placeholder",
	)
);

// Button Typography.
FLBuilderCSS::typography_field_rule(
	array(
		'settings'     => $settings,
		'setting_name' => 'tnit_button_typography',
		'selector'     => ".fl-node-$id .tnit-form-box .inner-holder .btn-submit",
	)
);

// Error Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-contact-error",
		'props'    => array(
			'color' => $settings->validation_message_color,
		),
	)
);
// Error Border Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-box .inner-holder.tnit-error input[type='text'],
						.fl-node-$id .tnit-form-box .inner-holder.tnit-error input[type='email'],
						.fl-node-$id .tnit-form-box .inner-holder.tnit-error input[type='tel'],
						.fl-node-$id .tnit-form-box .inner-holder.tnit-error textarea",
		'props'    => array(
			'border-color' => $settings->validation_field_border_color,
		),
	)
);
// Success Message Color.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-box .tnit-success-msg",
		'props'    => array(
			'color' => $settings->success_message_color,
		),
	)
);

// reCaptcha Alignment.
FLBuilderCSS::rule(
	array(
		'selector' => ".fl-node-$id .tnit-form-box .tnit-captcha .fl-grecaptcha > div",
		'enabled'  => 'left' === $settings->recaptcha_alignment || 'right' === $settings->recaptcha_alignment,
		'props'    => array(
			"margin-$settings->recaptcha_alignment" => '0',
		),
	)
);
