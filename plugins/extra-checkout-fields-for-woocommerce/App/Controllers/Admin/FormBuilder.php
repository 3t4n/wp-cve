<?php

namespace ECFFW\App\Controllers\Admin;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class FormBuilder
{
    /**
     * Form Builder Configurations.
     */
    public static function config()
    {
        $data = Settings::data();

        return apply_filters('ecffw_form_builder_config', [
            'fields' => self::fields($data),
            'options' => self::options($data)
        ]);
    }

    /**
     * Form Builder Fields.
     */
    public static function fields($data)
    {
        if ($data['tab'] == 'settings') return [];

        $fields = [
            [
                'label' => __("Email", 'extra-checkout-fields-for-woocommerce'),
                'type' => 'text',
                'subtype' => 'email',
                'icon' => '<span class="dashicons dashicons-email"></span>'
            ],
            [
                'label' => __("Telephone", 'extra-checkout-fields-for-woocommerce'),
                'type' => 'text',
                'subtype' => 'tel',
                'icon' => '<span class="dashicons dashicons-phone"></span>'
            ]
        ];
        
        if (in_array($data['tab'], ['billing', 'shipping'])) {
            $fields[] = [
                'label' => __("State", 'extra-checkout-fields-for-woocommerce'),
                'type' => 'text',
                'subtype' => 'state',
                'name' => $data['tab'] . '_state',
                'icon' => '<span class="dashicons dashicons-location"></span>'
            ];
            $fields[] = [
                'label' => __("Country", 'extra-checkout-fields-for-woocommerce'),
                'type' => 'text',
                'subtype' => 'country',
                'name' => $data['tab'] . '_country',
                'icon' => '<span class="dashicons dashicons-flag"></span>'
            ];
        }

        return apply_filters('ecffw_form_builder_fields', array_reverse($fields));
    }

    /**
     * Form Builder Options.
     */
    public static function options($data)
    {
        if ($data['tab'] == 'settings') return [];
        
        $options = [
            'replaceFields' => [
                [
                    'type' => 'text',
                    'label' => __("Text", 'extra-checkout-fields-for-woocommerce'),
                    'icon' => '<span class="dashicons dashicons-editor-textcolor"></span>'
                ],
                [
                    'type' => 'number',
                    'label' => __("Number", 'extra-checkout-fields-for-woocommerce')
                ],
                [
                    'type' => 'textarea',
                    'label' => __("TextArea", 'extra-checkout-fields-for-woocommerce'),
                    'icon' => '<span class="dashicons dashicons-admin-comments"></span>'
                ],
                [
                    'type' => 'select',
                    'label' => __("Select", 'extra-checkout-fields-for-woocommerce'),
                    'icon' => '<span class="dashicons dashicons-editor-justify"></span>'
                ],
                [
                    'type' => 'paragraph',
                    'label' => __("Paragraph", 'extra-checkout-fields-for-woocommerce'),
                    'icon' => '<span class="dashicons dashicons-editor-paragraph"></span>'
                ],
                [
                    'type' => 'header',
                    'label' => __("Header", 'extra-checkout-fields-for-woocommerce'),
                    'icon' => '<span class="dashicons dashicons-heading"></span>'
                ]
            ],
            'showActionButtons' => false,
            'controlOrder' => [
                'text',
                'date',
                'number',
                'textarea',
                'select',
                'checkbox-group',
                'radio-group'
            ],
            'disabledAttrs' => [
                'access',
                'style',
                'toggle',
                'other',
                'inline',
                'description',
                'multiple'
            ]
        ];

        if (in_array($data['tab'], ['billing', 'shipping', 'order', 'custom'])) {
            $row_type_attr = [
                'rowType' => [
                    'label' => 'Row Type',
                    'options' => [
                      'form-row-wide' => 'Normal (Wide)',
                      'form-row-first' => 'Left (Half)',
                      'form-row-last' => 'Right (Half)'
                    ]
                ]
            ];
            $options = array_merge($options, [
                'typeUserAttrs' => [
                    'text' => [
                        'rowType' => [
                            'label' => 'Row Type',
                            'options' => [
                              'form-row-wide' => 'Normal (Wide)',
                              'form-row-first' => 'Left (Half)',
                              'form-row-last' => 'Right (Half)',
                              'form-row-wide address-field' => 'Address'
                            ]
                        ]
                    ],
                    'textarea' => [
                        'rowType' => [
                            'label' => 'Row Type',
                            'options' => [
                                'form-row-wide notes' => 'Notes'
                            ]
                        ]
                    ],
                    'number' => $row_type_attr,
                    'select' => $row_type_attr,
                    'radio-group' => $row_type_attr,
                    'checkbox-group' => $row_type_attr
                ]
            ]);
        }

        if (in_array($data['tab'], ['billing', 'shipping'])) {
            $options = array_merge($options, [
                'subtypes' => [
                    'text' => ['country', 'state']
                ]
            ]);
        }

        $options = array_merge($options, [
            'disableFields' => [
                'autocomplete',
                'button',
                'hidden',
                'date',
                'file',
                'radio-group',
                'checkbox-group'
            ],
            'disabledSubtypes' => [
                'text' => ['color', 'password']
            ],
            'stickyControls' => [
                'enable' => true,
                'offset' => [
                    'top' => 42,
                    'right' => 20,
                    'left' => 'auto'
                ]
            ]
        ]);

        if ($data['settings']['form_builder_editonadd'] == true)
            $options['editOnAdd'] = true;

        if ($data['settings']['form_builder_warning'] == true)
            $options['fieldRemoveWarn'] = true;

        if ($data['settings']['form_builder_control'] == 'left')
            $options['controlPosition'] = 'left';

        return apply_filters('ecffw_form_builder_options', $options);
    }
}
