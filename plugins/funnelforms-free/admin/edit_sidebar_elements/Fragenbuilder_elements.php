<?php

function fnsf_get_fragenbuilder_elements()
{
    $editArray = array();

    array_push(
        $editArray,
        array(
            'editContentId' => 'heading',
            'fields' => array(
                array(
                    'type' => 'textarea_',
                    'icon' => 'fas fa-tag',
                    'label' => __('Question title', 'funnelforms-free'),
                    'placeholder' => __('Enter question title...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_question_type_heading',
                        'empty_value' => __('Question title', 'funnelforms-free'),
                        'saveObjectId' => 'name'
                    )
                ),
                array(
                    'type' => 'radio',
                    'icon' => 'fas fa-desktop',
                    'label' => __('Layout desktop', 'funnelforms-free'),
                    'conditioned' => true,
                    'enabled' => false,
                    'default_option' => 'grid',
                    'options' => array(
                        array('label' => __('Grid view', 'funnelforms-free'), 'value' => 'grid'),
                        array('label' => __('List view 1-column', 'funnelforms-free'), 'value' => 'list'),
                        array('label' => __('List view 2-columns', 'funnelforms-free'), 'value' => 'list2'),
                    ),
                    'details' => array(
                        'saveObjectId' => 'desktop_layout'
                    )
                ),
                array(
                    'type' => 'radio',
                    'icon' => 'fas fa-mobile-alt',
                    'label' => __('Layout mobile', 'funnelforms-free'),
                    'conditioned' => true,
                    'enabled' => false,
                    'default_option' => 'list',
                    'options' => array(
                        array('label' => __('Grid view', 'funnelforms-free'), 'value' => 'grid'),
                        array('label' => __('List view', 'funnelforms-free'), 'value' => 'list')
                    ),
                    'details' => array(
                        'saveObjectId' => 'mobile_layout'
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Hide graphic / icon', 'funnelforms-free'),
                    'conditioned' => true,
                    'enabled' => false,
                    'default_option' => false,
                    'details' => array(
                        'html' => false,
                        'saveObjectId' => 'hide_icons',
                    )
                ),
                array(
                    'type' => 'textarea_',
                    'icon' => 'fas fa-code',
                    'label' => __('JavaScript code (optional)', 'funnelforms-free'),
                    'placeholder' => __('Enter JavaScript code...', 'funnelforms-free'),
                    'required' => false,
                    'disabled' => true,
                    'details' => array(
                        'saveObjectId' => 'tracking_code'
                    )
                )
            ),
        ),
        array(
            'editContentId' => 'description',
            'fields' => array(
                array(
                    'type' => 'textarea_',
                    'icon' => 'fas fa-tag',
                    'label' => __('Description (optional)', 'funnelforms-free'),
                    'placeholder' => __('Enter description...', 'funnelforms-free'),
                    'required' => false,
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_question_type_description',
                        'empty_value' => __('Description (optional)', 'funnelforms-free'),
                        'saveObjectId' => 'description'
                    )
                ),
            ),
        ),
        array(
            'editContentId' => 'condition',
            'fields' => array(
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-sort-numeric-up-alt',
                    'label' => __('Maximum number', 'funnelforms-free'),
                    'placeholder' => __('Enter number...', 'funnelforms-free'),
                    'required' => false,
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_question_type_condition',
                        'htmlPreset' => __('Maximum number', 'funnelforms-free') . ': ',
                        'empty_value' => __('Maximum number of selectable answers (optional)', 'funnelforms-free'),
                        'saveObjectId' => 'condition'
                    )
                ),
            ),
        ),
        array(
            'editContentId' => 'answer',
            'editContentArray' => true,
            'fields' => array(
                array(
                    'type' => 'textarea_',
                    'icon' => 'fas fa-tag',
                    'label' => __('Answer text', 'funnelforms-free'),
                    'placeholder' => __('Enter answer text...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_answer_text',
                        'empty_value' => '',
                        'saveObjectId' => 'answers',
                        'saveObjectIdField' => 'text'
                    )
                ),
                array(
                    'type' => 'icon_image',
                    'icon' => 'fas fa-image',
                    'label' => __('Select image', 'funnelforms-free'),
                    'label_buttons' => array('image' => __('Select image', 'funnelforms-free'), 'icon' => __('Select icon', 'funnelforms-free')),
                    'enable_icon' => true,
                    'enable_media' => true,
                    'required' => true,
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_answer_img',
                        'saveObjectId' => 'answers',
                        'saveObjectIdField' => 'img'
                    )
                ),
            ),
        ),
        array(
            'editContentId' => 'textrow',
            'fields' => array(
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-tag',
                    'label' => __('Placeholder', 'funnelforms-free'),
                    'placeholder' => __('Enter placeholder...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_textrow',
                        'empty_value' => __('Text row settings', 'funnelforms-free'),
                        'saveObjectId' => 'textfeld',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-sort-numeric-down',
                    'label' => __('Minimum number of characters', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => false,
                    'details' => array(
                        'saveObjectId' => 'min_length',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-sort-numeric-up',
                    'label' => __('Maximum number of characters', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => false,
                    'details' => array(
                        'saveObjectId' => 'max_length',
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Allow text only', 'funnelforms-free'),
                    'radio_group' => 'text_only',
                    'details' => array(
                        'saveObjectId' => 'text_only_text'
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Allow numbers only', 'funnelforms-free'),
                    'radio_group' => 'text_only',
                    'details' => array(
                        'saveObjectId' => 'text_only_numbers'
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Allow date only', 'funnelforms-free'),
                    'radio_group' => 'text_only',
                    'details' => array(
                        'saveObjectId' => 'text_birthday'
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Required field', 'funnelforms-free'),
                    'details' => array(
                        'saveObjectId' => 'textfield_mandatory'
                    )
                ),
            ),
        ),
        array(
            'editContentId' => 'textarea',
            'fields' => array(
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-tag',
                    'label' => __('Placeholder', 'funnelforms-free'),
                    'placeholder' => __('Enter placeholder...', 'funnelforms-free'),
                    'required' => true,
                    'details' => array(
                        'html' => true,
                        'htmlId' => 'af2_textarea',
                        'empty_value' => __('Text area settings', 'funnelforms-free'),
                        'saveObjectId' => 'textarea',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-sort-numeric-down',
                    'label' => __('Minimum number of characters', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => false,
                    'details' => array(
                        'saveObjectId' => 'min_length',
                    )
                ),
                array(
                    'type' => 'text',
                    'icon' => 'fas fa-sort-numeric-up',
                    'label' => __('Maximum number of characters', 'funnelforms-free'),
                    'placeholder' => __('Enter value...', 'funnelforms-free'),
                    'required' => false,
                    'details' => array(
                        'saveObjectId' => 'max_length',
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Allow text only', 'funnelforms-free'),
                    'radio_group' => 'text_only',
                    'details' => array(
                        'saveObjectId' => 'text_only_text'
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Allow numbers only', 'funnelforms-free'),
                    'radio_group' => 'text_only',
                    'details' => array(
                        'saveObjectId' => 'text_only_numbers'
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Allow date only', 'funnelforms-free'),
                    'radio_group' => 'text_only',
                    'details' => array(
                        'saveObjectId' => 'text_birthday'
                    )
                ),
                array(
                    'type' => 'checkbox',
                    'label' => __('Required field', 'funnelforms-free'),
                    'details' => array(
                        'saveObjectId' => 'textarea_mandatory'
                    )
                ),
            ),
        ),
    );


    return $editArray;
}
