<?php

function fnsf_get_kontaktformularbuilder_elements() {
    $editArray = array();

    array_push($editArray, 
        array(
            'editContentId' => 'heading',
            'fields' => array(
                array(
                    'type' => 'textarea_',
                    'icon' => 'fas fa-tag',
                    'label' => __('Contact form title (frontend)', 'funnelforms-free'),
                    'placeholder' => __('Enter title...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_contact_form_heading',
                        'empty_value' => __('Contact form title (frontend)', 'funnelforms-free'),
                        'saveObjectId' => 'cftitle'
                    )
                ),
            )
        ),
        array(
            'editContentId' => 'description',
            'fields' => array(
                array(
                    'type' => 'textarea_',
                    'icon' => 'fas fa-tag',
                    'label' => __('Description (optional)', 'funnelforms-free'),
                    'placeholder' => __('Enter description...', 'funnelforms-free'),
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_contact_form_description',
                        'empty_value' => __('Description (optional)', 'funnelforms-free'),
                        'saveObjectId' => 'description'
                    )
                ),
            ),
        ),
        array(
            'editContentId' => 'question',
            'editContentArray' => true,
            'fields' => array(
                array(
                    'type' => 'label',
                    'icon' => 'fas fa-check',
                    'label' => __('Type:', 'funnelforms-free').' '.__('Name', 'funnelforms-free'),
                    'conditioned' => true,
                    'depending_field' => 'typ',
                    'depending_values' => array('text_type_name'),
                    'details' => array(
                        'saveObjectId' => 'questions',
                    ),
                ),
                array(
                    'type' => 'label',
                    'icon' => 'fas fa-check',
                    'label' => __('Type:', 'funnelforms-free').' '.__('E-mail', 'funnelforms-free'),
                    'conditioned' => true,
                    'depending_field' => 'typ',
                    'depending_values' => array('text_type_mail'),
                    'details' => array(
                        'saveObjectId' => 'questions',
                    ),
                ),
                array(
                    'type' => 'label',
                    'icon' => 'fas fa-check',
                    'label' => __('Type:', 'funnelforms-free').' '. __('Phone', 'funnelforms-free'),
                    'conditioned' => true,
                    'depending_field' => 'typ',
                    'depending_values' => array('text_type_phone'),
                    'details' => array(
                        'saveObjectId' => 'questions',
                    ),
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-tag',
                    'label' => __('ID', 'funnelforms-free'),
                    'placeholder' => __('Enter ID...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'questions',
                        'saveObjectIdField' => 'id'
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-tag',
                    'label' => __('Label', 'funnelforms-free'),
                    'placeholder' => __('Enter label...', 'funnelforms-free'),
                    'conditioned' => true,
                    'depending_field' => 'typ',
                    'depending_values' => array('salutation_type', 'text_type_name', 'text_type_mail', 'text_type_phone', 'text_type_phone_verification',  'text_type_plain'),
                    'required' => false,
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_question_label',
                        'throwEvent' => 'set_required',
                        'empty_value' => '',
                        'saveObjectId' => 'questions',
                        'saveObjectIdField' => 'label'
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-tag',
                    'label' => __('Placeholder', 'funnelforms-free'),
                    'placeholder' => __('Enter placeholder...', 'funnelforms-free'),
                    'required' => false,
                    'conditioned' => true,
                    'depending_field' => 'typ',
                    'depending_values' => array('text_type_name', 'text_type_mail', 'text_type_plain'),
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_question_input_field',
                        'htmlAttr' => 'placeholder',
                        'throwEvent' => 'set_required',
                        'empty_value' => '',
                        'saveObjectId' => 'questions',
                        'saveObjectIdField' => 'placeholder'
                    )
                ),
                array(
                    'type' => 'textarea_',
                    'icon' => 'fas fa-tag',
                    'label' => __('Text - (do not use "target" for links!)', 'funnelforms-free'),
                    'placeholder' => __('Enter checkbox text...', 'funnelforms-free'),
                    'required' => false,
                    'conditioned' => true,
                    'depending_field' => 'typ',
                    'depending_values' => array('checkbox_type'),
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_question_checkbox_text_field',
                        'throwEvent' => 'set_required',
                        'empty_value' => __('Enter checkbox text...', 'funnelforms-free'),
                        'saveObjectId' => 'questions',
                        'saveObjectIdField' => 'text'
                    )
                ),
                array(
                    'type' => 'icon_image',
                    'icon' => 'fas fa-image',
                    'label' => __('Icon', 'funnelforms-free'),
                    'label_buttons' => array('icon' => __('Select icon', 'funnelforms-free'), 'remove' => __('Remove icon', 'funnelforms-free')),
                    'enable_icon' => true,
                    'enable_media' => false,
                    'enable_remove' => true, 
                    'required' => false,
                    'conditioned' => true,
                    'depending_field' => 'typ',
                    'depending_values' => array('text_type_name', 'text_type_mail', 'text_type_phone', 'text_type_phone_verification',  'text_type_plain'),
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_question_input_icon',
                        'throwEvent' => 'set_icon',
                        'saveObjectId' => 'questions',
                        'saveObjectIdField' => 'icon'
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('B2B e-mail validation', 'funnelforms-free'),
                    'conditioned' => true,
                    'depending_field' => 'typ',
                    'depending_values' => array('text_type_mail'),
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'questions',
                        'saveObjectIdField' => 'b2bMailValidation'
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Mr.', 'funnelforms-free'),
                    'conditioned' => true,
                    'depending_field' => 'typ',
                    'depending_values' => array('salutation_type'),
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_salutation_male',
                        'throwEvent' => 'set_salutation',
                        'saveObjectId' => 'questions',
                        'saveObjectIdField' => 'allowSalutationMale'
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Mrs.', 'funnelforms-free'),
                    'conditioned' => true,
                    'depending_field' => 'typ',
                    'depending_values' => array('salutation_type'),
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_salutation_female',
                        'throwEvent' => 'set_salutation',
                        'saveObjectId' => 'questions',
                        'saveObjectIdField' => 'allowSalutationFemale'
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Diverse', 'funnelforms-free'),
                    'conditioned' => true,
                    'depending_field' => 'typ',
                    'depending_values' => array('salutation_type'),
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_salutation_divers',
                        'throwEvent' => 'set_salutation',
                        'saveObjectId' => 'questions',
                        'saveObjectIdField' => 'allowSalutationDivers'
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Company', 'funnelforms-free'),
                    'conditioned' => true,
                    'depending_field' => 'typ',
                    'depending_values' => array('salutation_type'),
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_salutation_company',
                        'throwEvent' => 'set_salutation',
                        'saveObjectId' => 'questions',
                        'saveObjectIdField' => 'allowSalutationCompany'
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Required field', 'funnelforms-free'),
                    'conditioned' => true,
                    'depending_field' => 'typ',
                    'depending_values' => array('salutation_type', 'text_type_name', 'text_type_mail', 'text_type_phone',  'text_type_plain', 'checkbox_type'),
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_question_wrapper',
                        'htmlClass' => true,
                        'throwEvent' => 'set_required',
                        'saveObjectId' => 'questions',
                        'saveObjectIdField' => 'required'
                    )
                ),
            ),
        ),
        array(
            'editContentId' => 'send_button',
            'fields' => array(
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-tag',
                    'label' => __('Button text', 'funnelforms-free'),
                    'placeholder' => __('Enter button text...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_contact_form_send_button',
                        'empty_value' => __('Button text', 'funnelforms-free'),
                        'saveObjectId' => 'send_button'
                    )
                ),
            ),
        ),
    );


    return $editArray;
}
