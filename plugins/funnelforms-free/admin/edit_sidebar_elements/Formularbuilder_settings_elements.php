<?php

function fnsf_get_formularbuilder_settings_elements($filesarray) {
    $editArray = array();

    $uploadedFontfiles = $filesarray['files'];

    $allFontFiles = array(
        array('value' => 'Montserrat', 'label' => __('Default (Montserrat)', 'funnelforms-free')),
        array('value' => 'inherit', 'label' => __('Theme-based', 'funnelforms-free')),
        array('value' => 'serif', 'label' => __('Serif', 'funnelforms-free')),
        array('value' => 'sans-serif', 'label' => __('Sans-Serif', 'funnelforms-free')),
        array('value' => 'cursive', 'label' => __('Cursive', 'funnelforms-free')),
        array('value' => 'fantasy', 'label' => __('Fantasy', 'funnelforms-free')),
        array('value' => 'monospace', 'label' => __('Monospace', 'funnelforms-free')),
    );

    $uploadedFontfiles = array_map(function($value) {
        return array('value' => $value, 'label' => explode('.', $value)[0]);
    }, $uploadedFontfiles);

    $finalFonts = array_merge($allFontFiles, $uploadedFontfiles);

    array_push($editArray,
        array(
            'editContentId' => 'general_settings',
            'fields' => array(
                array(
                    'type' => 'textarea_',
                    'icon' => 'fas fa-tag',
                    'label' => __('Form title (backend)', 'funnelforms-free'),
                    'placeholder' => __('Enter form title...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'saveObjectId' => 'name'
                    )
                ),
                array(
                    'type' => 'textarea_',
                    'icon' => 'fas fa-tag',
                    'label' => __('Form title (frontend)', 'funnelforms-free'),
                    'placeholder' => __('Enter form title...', 'funnelforms-free'),
                    'required' => false,
                    'details' => array(
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'fe_title'
                    ),
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Adapt form layout to the width of the column', 'funnelforms-free'),
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'adjust_containersize',
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Loading animation', 'funnelforms-free'),
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'showLoading',
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('FontAwesome Icons', 'funnelforms-free'),
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'showFontAwesome',
                    )
                ),
                array(
                    'type' => 'select',
                    'icon' => 'fas fa-language',
                    'label' => __('Form language', 'funnelforms-free'),
                    'select_values' => array(
                        array('value' => 'default', 'label' => __('WordPress language', 'funnelforms-free')),
                        array('value' => 'de_DE', 'label' => __('German', 'funnelforms-free')),
                        array('value' => 'es_ES', 'label' => __('Spanish', 'funnelforms-free')),
                        array('value' => 'en_US', 'label' => __('English', 'funnelforms-free')),
                        array('value' => 'fr_FR', 'label' => __('French', 'funnelforms-free')),
                        array('value' => 'it_IT', 'label' => __('Italian', 'funnelforms-free')),
                    ),
                    'default_value' => 'default',
                    'details' => array(
                        'saveObjectId' => 'fe_locale',
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Right-to-left Language', 'funnelforms-free'),
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'rtl_layout',
                    )
                ),
                /*
                array(
                    'type' => 'checkbox',
                    'label' => __('E-mail notification in case of error', 'funnelforms-free'),
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'send_error_mail',
                    )
                ),*/
                array(
                    'type' => 'checkbox',
                    'label' => __('Automatic scroll to form title (mobile)', 'funnelforms-free'),
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'activateScrollToAnchor',
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Show form sent success message', 'funnelforms-free'),
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'showSuccessScreen',
                    )
                ),
                array(
                    'type' => 'textarea_',
                    'icon' => 'fas fa-tag',
                    'label' => __('Form sent - success message', 'funnelforms-free'),
                    'placeholder' => __('Enter success message...', 'funnelforms-free'),
                    'required' => false,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'success_text'
                    )
                ),
                array(AF2F_PLUGIN,
                    'type' => 'icon_image',
                    'icon' => 'fas fa-image',
                    'label' => __('Form sent - image', 'funnelforms-free'),
                    'label_buttons' => array('image' => __('Choose image', 'funnelforms-free'), 'reset' => __('Reset', 'funnelforms-free')),
                    'enable_icon' => false,
                    'enable_media' => true,
                    'enable_remove' => false,
                    'enable_reset' => true,
                    'reset_value' => plugins_url('/res/images/success_standard.png', AF2F_PLUGIN),
                    'show_preview' => true, 
                    'required' => false,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'success_image',
                    )
                ),
            )
        ),
        array(
            'editContentId' => 'desgin_settings',
            'fields' => array(
                array(
                    'type' => 'color_picker',
                    'label' => __('Main accent color', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'global_main_color',
                        'saveObjectIdSpreadFields' => array(
                            'form_answer_card_icon_color',
                            'form_border_color',
                            'form_box_shadow_color',
                            'form_button_background_color',
                            'form_contact_form_button_background_color',
                            'form_datepicker_background_color',
                            'form_heading_color',
                            'form_loader_color',
                            'form_progress_bar_color',
                            'form_slider_frage_bullet_color',
                            'form_slider_frage_thumb_background_color',
                        )
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Main background color', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'global_main_background_color',
                        'saveObjectIdSpreadFields' => array(
                            'form_background_color'
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-tag',
                    'label' => __('Button text previous step', 'funnelforms-free'),
                    'placeholder' => __('Enter button text...', 'funnelforms-free'),
                    'required' => false,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'global_prev_text',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-tag',
                    'label' => __('Button text next step', 'funnelforms-free'),
                    'placeholder' => __('Enter button text...', 'funnelforms-free'),
                    'required' => false,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'global_next_text',
                    )
                ),
                array(
                    'type' => 'select',
                    'icon' => 'fas fa-pen',
                    'label' => __('Font family', 'funnelforms-free'),
                    'select_values' => $finalFonts,
                    'default_value' => 'Montserrat',
                    'details' => array(
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'global_font',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Icon desktop size (Grid)', 'af2_multilanguage'),
                    'placeholder' => __('Enter value...', 'af2_multilanguage'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'icon_size_desktop_grid',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Icon desktop size (List 1 column)', 'af2_multilanguage'),
                    'placeholder' => __('Enter value...', 'af2_multilanguage'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'icon_size_desktop_list_1',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Icon desktop size (List 2 column)', 'af2_multilanguage'),
                    'placeholder' => __('Enter value...', 'af2_multilanguage'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'icon_size_desktop_list_2',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Icon mobile size (Grid)', 'af2_multilanguage'),
                    'placeholder' => __('Enter value...', 'af2_multilanguage'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'icon_size_mobile_grid',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Icon mobile size (List)', 'af2_multilanguage'),
                    'placeholder' => __('Enter value...', 'af2_multilanguage'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'icon_size_mobile_list',
                    )
                ),
            ),
        ),
        array(
            'editContentId' => 'individual_colors',
            'fields' => array(
                array(
                    'type' => 'color_picker',
                    'label' => __('Form heading', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_heading_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Question heading', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_question_heading_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Question description', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_question_description_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Answer text', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_answer_card_text_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Answer icon', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_answer_card_icon_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Loading animation', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_loader_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Form background', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_background_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Answer background', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_answer_card_background_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Button active', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_button_background_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Button inactive', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_button_disabled_background_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Shadow text-fields', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_box_shadow_color_unfocus',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Shadow text-fields (active)', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_box_shadow_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Shadow selection-fields', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_box_shadow_color_answer_card',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Border', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_border_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Progressbar filled', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_progress_bar_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Progressbar unfilled', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_progress_bar_unfilled_background_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Slider numbers', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_slider_frage_bullet_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Slider selection', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_slider_frage_thumb_background_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Slider background', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_slider_frage_background_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Text row background', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_input_background_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Date background', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_datepicker_background_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Date', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_datepicker_color',
                    )
                ),
            ),
        ),
        array(
            'editContentId' => 'font_sizes',
            'fields' => array(
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Headline desktop font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_heading_size_desktop',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Headline mobile font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_heading_size_mobile',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Headline font weight', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_heading_weight',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Headline desktop line height', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_heading_line_height_desktop',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Headline mobile line height', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_heading_line_height_mobile',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Question title desktop font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_question_heading_size_desktop',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Question title mobile font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_question_heading_size_mobile',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Question title font weight', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_question_heading_weight',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Question title desktop line height', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_question_heading_line_height_desktop',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Question title mobile line height', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_question_heading_line_height_mobile',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Answer text desktop font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_answer_card_text_size_desktop',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Answer text mobile font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_answer_card_text_size_mobile',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Answer text font weight', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_answer_card_text_weight',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Answer text desktop line height', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_answer_card_text_line_height_desktop',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Answer text mobile line height', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_answer_card_text_line_height_mobile',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Text row desktop font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_text_input_size_desktop',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Text row mobile font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_text_input_size_mobile',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Text row font weight', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_text_input_text_weight',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Text row desktop line height', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_text_input_line_height_desktop',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Text row mobile line height', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_text_input_line_height_mobile',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Question description desktop font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_question_description_size_desktop',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Question description mobile font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_question_description_size_mobile',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Question description font weight', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_question_description_weight',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Question description desktop line height', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_question_description_line_height_desktop',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Question description mobile line height', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_question_description_line_height_mobile',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Button text desktop font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_button_label_size_desktop',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Button text mobile font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_button_label_size_mobile',
                    )
                ),
            ),
        ),
        array(
            'editContentId' => 'border_radius',
            'fields' => array(
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Grid border radius', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_answer_card_border_radius',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Input field border radius', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_text_input_border_radius',
                    )
                ),
            ),
        ),
        array(
            'editContentId' => 'contact_form',
            'fields' => array(
                array(
                    'type' => 'color_picker',
                    'label' => __('Button color', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_button_background_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Button text color', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_button_color',
                    )
                ),
                array(
                    'type' => 'color_picker',
                    'label' => __('Font color', 'funnelforms-free'),
                    'icon' => 'fas fa-palette',
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_font_color',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Button text font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_button_size',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Button text font weight', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_button_weight',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Button padding top and bottom', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_button_padding_top_bottom',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Button padding left and right', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_button_padding_left_right',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Button border radius', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_button_border_radius',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Label font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_label_size',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Label font weight', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_label_weight',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Text row font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_input_size',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Text row font weight', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_input_weight',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Checkbox text font size', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_cb_size',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Checkbox text font weight', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_cb_weight',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Text row height', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_input_height',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Text row border radius', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_input_border_radius',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-text-height',
                    'label' => __('Input field padding left and right', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'styling',
                        'saveObjectIdField' => 'form_contact_form_input_padding_left_right',
                    )
                ),
            ),
        ),
    );


    return $editArray;
}
