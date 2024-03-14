<?php
/**
 * Element component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Element extends ElementSpecs {

    public
        $name,
        $slug,
        $container,
        $type,
        $uuid,
        $icon,
        $template,
        $builder,
        $frontend,
        $css,
        $js,
        $auth,
        $visibilityDesktop,
        $visibilityTablet,
        $visibilityMobile,
        $customDesktop,
        $customTablet,
        $customMobile,
        $editor,
        $desktop,
        $tablet,
        $mobile,
        $generalCSS,
        $desktopCSS,
        $tabletCSS,
        $mobileCSS;

    /**
     * Element constructor.
     */
    public function __construct() {
        $this->name              = '';
        $this->slug              = '';
        $this->container         = false;
        $this->type              = 'element';
        $this->uuid              = '';
        $this->icon              = (object) [
            'size'  => 'mdi-24px',
            'type'  => '',
            'color' => 'sq-textItem'
        ];
        $this->template          = '';
        $this->builder           = '';
        $this->frontend          = '';
        $this->css               = '';
        $this->js                = '';
        $this->auth              = [
            'logged_in'  => false,
            'logged_out' => false,
            'all'        => true
        ];
        $this->visibilityDesktop = true;
        $this->visibilityTablet  = true;
        $this->visibilityMobile  = true;
        $this->customDesktop     = false;
        $this->customTablet      = true;
        $this->customMobile      = true;
        $this->editor            = [];
        $this->desktop           = new \stdClass();
        $this->tablet            = new \stdClass();
        $this->mobile            = new \stdClass();
        $this->generalCSS        = '';
        $this->desktopCSS        = '';
        $this->tabletCSS         = '';
        $this->mobileCSS         = '';

        $this->advancedEditor();
    }

    /**
     * @param EditorSection $section
     * @param array $fields
     */
    protected function addSection( EditorSection $section, array $fields, $arrayName = null ) {

        $section = apply_filters( 'stax_section_before_add', $section, $fields );
        $fields  = apply_filters( 'stax_section_fields_before_add', $fields, $section );

        foreach ( $fields as $field ) {
            $section->addField( $field );
        }

        do_action( 'stax_section_after_add', $section, $fields );

        if ( $arrayName ) {
            $this->editor[ $arrayName ] = $section;
        } else {
            $this->editor[] = $section;
        }
    }

    /**
     * @param array $fields
     */
    protected function setBoxDefaults( array $fields ) {
        foreach ( $this->editor as $x => $group ) {
            if ( is_numeric( $x ) ) {
                continue;
            }

            foreach ( $this->editor[ $x ]->fields as $key => $field ) {
                foreach ( $fields as $name => $props ) {
                    if ( $name === $field->name ) {

                        if ( isset( $props['visibility'] ) ) {
                            $this->editor[ $x ]->fields[ $key ]->visibility = $props['visibility'];
                        }

                        if ( is_array( $field->value ) && is_array( $props['value'] ) &&
                             count( $field->value ) === count( $props['value'] ) ) {
                            for ( $i = 0; $i < count( $field->value ); $i ++ ) {
                                $this->editor[ $x ]->fields[ $key ]->value[ $i ]['value'] = $props['value'][ $i ];
                            }
                        } else if ( is_array( $field->value ) && ( is_string( $props['value'] ) || is_numeric( $props['value'] ) ) ) {
                            foreach ( $field->value as $i => $value ) {
                                if ( $value['value'] == $props['value'] ) {
                                    if ( isset( $this->editor[ $x ]->fields[ $key ]->value[ $i ]['selected'] ) ) {
                                        $this->editor[ $x ]->fields[ $key ]->value[ $i ]['selected'] = true;
                                    } else {
                                        $this->editor[ $x ]->fields[ $key ]->value[ $i ]['checked'] = true;
                                    }
                                } else {
                                    if ( isset( $this->editor[ $x ]->fields[ $key ]->value[ $i ]['selected'] ) ) {
                                        $this->editor[ $x ]->fields[ $key ]->value[ $i ]['selected'] = false;
                                    } else {
                                        $this->editor[ $x ]->fields[ $key ]->value[ $i ]['checked'] = false;
                                    }
                                }
                            }
                        } else if ( ( is_numeric( $field->value ) || is_string( $field->value ) ) && ( is_string( $props['value'] ) || is_numeric( $props['value'] ) ) ) {
                            $this->editor[ $x ]->fields[ $key ]->value = $props['value'];
                        }
                    }
                }
            }
        }
    }

    /**
     *
     */
    private function advancedEditor() {
        $section_1 = new EditorSection( [
            'title' => 'Layout',
            'name'  => 'layout-advanced'
        ] );

        $fields_1 = [];

        $fields_1[] = new EditorSectionField(
            [
                'label' => 'Height',
                'name'  => self::ADVANCED_FULL_HEIGHT,
                'only'  => 'header',
                'type'  => self::FIELD_RADIO,
                'value' => [
                    [
                        'label'   => 'Auto',
                        'value'   => '',
                        'checked' => true,
                        'trigger' => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'   => 'Full',
                        'value'   => 'is-full-height',
                        'checked' => false,
                        'trigger' => [
                            'section' => [],
                            'field'   => []
                        ]
                    ]
                ]
            ]
        );

        $fields_1[] = new EditorSectionField(
            [
                'label' => 'Width',
                'name'  => self::ADVANCED_FULL_WIDTH,
                'only'  => 'header',
                'type'  => self::FIELD_RADIO,
                'value' => [
                    [
                        'label'   => 'Auto',
                        'value'   => '',
                        'checked' => true,
                        'trigger' => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'   => 'Full',
                        'value'   => 'is-full-width',
                        'checked' => false,
                        'trigger' => [
                            'section' => [],
                            'field'   => []
                        ]
                    ]
                ]
            ]
        );

        $fields_1[] = new EditorSectionField(
            [
                'label'    => 'Padding',
                'name'     => self::ADVANCED_PADDING,
                'type'     => self::FIELD_SPACING,
                'value'    => [
                    [
                        'position' => 'Top',
                        'value'    => ''
                    ],
                    [
                        'position' => 'Right',
                        'value'    => ''
                    ],
                    [
                        'position' => 'Bottom',
                        'value'    => ''
                    ],
                    [
                        'position' => 'Left',
                        'value'    => '',
                    ]
                ],
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true
                    ],
                    [
                        'type'   => 'em',
                        'active' => false
                    ],
                    [
                        'type'   => '%',
                        'active' => false
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}}' => [
                        'padding-top: {{VALUE_1}}{{UNIT}}',
                        'padding-right: {{VALUE_2}}{{UNIT}}',
                        'padding-bottom: {{VALUE_3}}{{UNIT}}',
                        'padding-left: {{VALUE_4}}{{UNIT}}'
                    ]
                ]
            ]
        );

        $fields_1[] = new EditorSectionField(
            [
                'label'    => 'Margin',
                'name'     => self::ADVANCED_MARGIN,
                'type'     => self::FIELD_SPACING,
                'value'    => [
                    [
                        'position' => 'Top',
                        'value'    => ''
                    ],
                    [
                        'position' => 'Right',
                        'value'    => ''
                    ],
                    [
                        'position' => 'Bottom',
                        'value'    => ''
                    ],
                    [
                        'position' => 'Left',
                        'value'    => ''
                    ]
                ],
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true
                    ],
                    [
                        'type'   => 'em',
                        'active' => false
                    ],
                    [
                        'type'   => '%',
                        'active' => false
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}}' => [
                        'margin-top: {{VALUE_1}}{{UNIT}}',
                        'margin-right: {{VALUE_2}}{{UNIT}}',
                        'margin-bottom: {{VALUE_3}}{{UNIT}}',
                        'margin-left: {{VALUE_4}}{{UNIT}}'
                    ]
                ]
            ]
        );

        $fields_1[] = new EditorSectionField(
            [
                'label'    => 'Border radius',
                'name'     => self::ADVANCED_BORDER_RADIUS,
                'type'     => self::FIELD_BORDER_RADIUS,
                'value'    => [
                    [
                        'value'    => '',
                        'position' => 'Top left'
                    ],
                    [
                        'value'    => '',
                        'position' => 'Top right'
                    ],
                    [
                        'value'    => '',
                        'position' => 'Bottom right'
                    ],
                    [
                        'value'    => '',
                        'position' => 'Bottom left'
                    ]
                ],
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true
                    ],
                    [
                        'type'   => 'em',
                        'active' => false
                    ],
                    [
                        'type'   => '%',
                        'active' => false
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}}' => [
                        'border-top-left-radius: {{VALUE_1}}{{UNIT}}',
                        'border-top-right-radius: {{VALUE_2}}{{UNIT}}',
                        'border-bottom-right-radius: {{VALUE_3}}{{UNIT}}',
                        'border-bottom-left-radius: {{VALUE_4}}{{UNIT}}'
                    ]
                ]
            ]
        );

        $section_2 = new EditorSection( [
            'title' => 'Normal style',
            'name'  => 'normal-style-advanced'
        ] );

        $fields_2 = [];

        $fields_2[] = new EditorSectionField(
            [
                'label'    => 'Shadow',
                'name'     => self::ADVANCED_SHADOW,
                'type'     => self::FIELD_SHADOW,
                'value'    => [
                    [
                        'position' => 'Color',
                        'value'    => ''
                    ],
                    [
                        'position' => 'X',
                        'value'    => ''
                    ],
                    [
                        'position' => 'Y',
                        'value'    => ''
                    ],
                    [
                        'position' => 'Blur',
                        'value'    => '',
                    ],
                    [
                        'position' => 'Spread',
                        'value'    => '',
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}}' => [
                        '-webkit-box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}',
                        '-moz-box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}',
                        'box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}'
                    ]
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'    => 'Background',
                'name'     => self::ADVANCED_BG_NORMAL,
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => '',
                'selector' => [
                    '{{SELECTOR}}' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'    => 'Border type',
                'name'     => self::ADVANCED_BORDER_TYPE_NORMAL,
                'type'     => self::FIELD_DROPDOWN,
                'value'    => [
                    [
                        'label'    => 'None',
                        'value'    => '',
                        'selected' => true,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Solid',
                        'value'    => 'solid',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                self::ADVANCED_BORDER_WIDTH_NORMAL,
                                self::ADVANCED_BORDER_COLOR_NORMAL
                            ]
                        ]
                    ],
                    [
                        'label'    => 'Double',
                        'value'    => 'double',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                self::ADVANCED_BORDER_WIDTH_NORMAL,
                                self::ADVANCED_BORDER_COLOR_NORMAL
                            ]
                        ]
                    ],
                    [
                        'label'    => 'Dotted',
                        'value'    => 'dotted',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                self::ADVANCED_BORDER_WIDTH_NORMAL,
                                self::ADVANCED_BORDER_COLOR_NORMAL
                            ]
                        ]
                    ],
                    [
                        'label'    => 'Dashed',
                        'value'    => 'dashed',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                self::ADVANCED_BORDER_WIDTH_NORMAL,
                                self::ADVANCED_BORDER_COLOR_NORMAL
                            ]
                        ]
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}}' => 'border-style: {{VALUE}}'
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'      => 'Border width',
                'name'       => self::ADVANCED_BORDER_WIDTH_NORMAL,
                'visibility' => false,
                'type'       => self::FIELD_INPUT_NUMBER,
                'value'      => '1',
                'units'      => [
                    [
                        'type'   => 'px',
                        'active' => true
                    ]
                ],
                'selector'   => [
                    '{{SELECTOR}}' => 'border-width: {{VALUE}}{{UNIT}}'
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'      => 'Border color',
                'name'       => self::ADVANCED_BORDER_COLOR_NORMAL,
                'visibility' => false,
                'type'       => self::FIELD_COLOR_PICKER,
                'value'      => '',
                'selector'   => [
                    '{{SELECTOR}}' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $section_3 = new EditorSection( [
            'title' => 'Hover style',
            'name'  => 'hover-style-advanced'
        ] );

        $fields_3 = [];

        $fields_3[] = new EditorSectionField(
            [
                'label'    => 'Shadow',
                'name'     => self::ADVANCED_SHADOW,
                'type'     => self::FIELD_SHADOW,
                'value'    => [
                    [
                        'position' => 'Color',
                        'value'    => ''
                    ],
                    [
                        'position' => 'X',
                        'value'    => ''
                    ],
                    [
                        'position' => 'Y',
                        'value'    => ''
                    ],
                    [
                        'position' => 'Blur',
                        'value'    => '',
                    ],
                    [
                        'position' => 'Spread',
                        'value'    => '',
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}}:hover' => [
                        '-webkit-box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}',
                        '-moz-box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}',
                        'box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}'
                    ]
                ]
            ]
        );

        $fields_3[] = new EditorSectionField(
            [
                'label'    => 'Background',
                'name'     => self::ADVANCED_BG_HOVER,
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => '',
                'selector' => [
                    '{{SELECTOR}}:hover' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $fields_3[] = new EditorSectionField(
            [
                'label'    => 'Border type',
                'name'     => self::ADVANCED_BORDER_TYPE_HOVER,
                'type'     => self::FIELD_DROPDOWN,
                'value'    => [
                    [
                        'label'    => 'None',
                        'value'    => '',
                        'selected' => true,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Solid',
                        'value'    => 'solid',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                self::ADVANCED_BORDER_WIDTH_HOVER,
                                self::ADVANCED_BORDER_COLOR_HOVER
                            ]
                        ]
                    ],
                    [
                        'label'    => 'Double',
                        'value'    => 'double',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                self::ADVANCED_BORDER_WIDTH_HOVER,
                                self::ADVANCED_BORDER_COLOR_HOVER
                            ]
                        ]
                    ],
                    [
                        'label'    => 'Dotted',
                        'value'    => 'dotted',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                self::ADVANCED_BORDER_WIDTH_HOVER,
                                self::ADVANCED_BORDER_COLOR_HOVER
                            ]
                        ]
                    ],
                    [
                        'label'    => 'Dashed',
                        'value'    => 'dashed',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                self::ADVANCED_BORDER_WIDTH_HOVER,
                                self::ADVANCED_BORDER_COLOR_HOVER
                            ]
                        ]
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}}:hover' => 'border-style: {{VALUE}}'
                ]
            ]
        );

        $fields_3[] = new EditorSectionField(
            [
                'label'      => 'Border width',
                'name'       => self::ADVANCED_BORDER_WIDTH_HOVER,
                'visibility' => false,
                'type'       => self::FIELD_INPUT_NUMBER,
                'value'      => '1',
                'units'      => [
                    [
                        'type'   => 'px',
                        'active' => true
                    ]
                ],
                'selector'   => [
                    '{{SELECTOR}}:hover' => 'border-width: {{VALUE}}{{UNIT}}'
                ]
            ]
        );

        $fields_3[] = new EditorSectionField(
            [
                'label'      => 'Border color',
                'name'       => self::ADVANCED_BORDER_COLOR_HOVER,
                'visibility' => false,
                'type'       => self::FIELD_COLOR_PICKER,
                'value'      => '',
                'selector'   => [
                    '{{SELECTOR}}:hover' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $section_4 = new EditorSection( [
            'title' => 'Customization',
            'name'  => 'customization-advanced'
        ] );

        $fields_4 = [];

        $fields_4[] = new EditorSectionField(
            [
                'label' => 'ID',
                'name'  => self::ADVANCED_CSS_ID,
                'type'  => self::FIELD_INPUT_TEXT,
                'value' => ''
            ]
        );

        $fields_4[] = new EditorSectionField(
            [
                'label' => 'Class',
                'name'  => self::ADVANCED_CSS_CLASS,
                'type'  => self::FIELD_INPUT_TEXT,
                'value' => ''
            ]
        );

        foreach ( $fields_1 as $field ) {
            $section_1->addField( $field );
        }

        foreach ( $fields_2 as $field ) {
            $section_2->addField( $field );
        }

        foreach ( $fields_3 as $field ) {
            $section_3->addField( $field );
        }
        foreach ( $fields_4 as $field ) {
            $section_4->addField( $field );
        }

        $this->editor['tab_advanced_layout']        = $section_1;
        $this->editor['tab_advanced_normal_style']  = $section_2;
        $this->editor['tab_advanced_hover_style']   = $section_3;
        $this->editor['tab_advanced_customization'] = $section_4;
    }
}
