<?php
/**
 * Menu component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class ElementMenu extends Element implements ElementInterface {
    /**
     * ElementMenu constructor.
     */
    public function __construct() {
        parent::__construct();
        $this->name       = 'Menu';
        $this->slug       = 'menu';
        $this->icon->type = 'mdi-menu';
        $this->template   = $this->getTemplate( $this->slug );

        $fields_1 = [];

        $fields_1[] = new EditorSectionField(
            [
                'label' => 'Menu',
                'name'  => 'menu_field',
                'type'  => self::FIELD_DROPDOWN_MENU,
                'value' => ''
            ]
        );

        $fields_1[] = new EditorSectionField(
            [
                'label' => 'Type',
                'name'  => 'menu_type_field',
                'type'  => self::FIELD_DROPDOWN,
                'value' => [
                    [
                        'label'    => 'Default menu',
                        'value'    => '',
                        'selected' => true,
                        'trigger'  => [
                            'section' => [
                                'menu-section',
                                'style-menu',
                                'style-submenu'
                            ],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Flex menu',
                        'value'    => 'flexMenu',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [
                                'menu-section',
                                'style-menu',
                                'style-submenu'
                            ],
                            'field'   => [ 'flex_icon_field' ]
                        ]
                    ],
                    [
                        'label'    => 'Burger menu',
                        'value'    => 'modburger',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [
                                'style-modal',
                                'style-menu-2',
                                'style-submenu-2'
                            ],
                            'field'   => [
                                'burger_type'
                            ]
                        ]
                    ]
                ],
            ]
        );

        $fields_1[] = new EditorSectionField(
            [
                'label'      => 'Menu alignment',
                'name'       => 'burger_type',
                'type'       => self::FIELD_DROPDOWN,
                'visibility' => false,
                'value'      => [
                    [
                        'label'    => 'Left',
                        'value'    => 'left',
                        'selected' => true,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Center',
                        'value'    => 'center',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ]
                ],
            ]
        );

        $fields_1[] = new EditorSectionField(
            [
                'label'      => 'Flex icon',
                'name'       => 'flex_icon_field',
                'visibility' => false,
                'type'       => self::FIELD_ICON,
                'value'      => 'mdi-dots-vertical',
                'selector'   => [
                    '{{SELECTOR}} .menu-default > ul > li.flexMenu-viewMore.has-submenu > a:after' => 'content: "{{VALUE}}"'
                ]
            ]
        );

        $fields_2 = [];

        $fields_2[] = new EditorSectionField(
            [
                'label' => 'Style',
                'name'  => 'menu_style_type_field',
                'type'  => self::FIELD_DROPDOWN,
                'value' => [
                    [
                        'label'    => 'Default',
                        'value'    => '',
                        'selected' => true,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Style 1',
                        'value'    => 'menu-style-1',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                'menu_style_1_border_field',
                                'menu_style_1_border_color_field'
                            ]
                        ]
                    ],
                    [
                        'label'    => 'Style 2',
                        'value'    => 'menu-style-2',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                'menu_style_2_item_bg_field',
                                'menu_style_2_item_border_radius_field'
                            ]
                        ]
                    ],
                    [
                        'label'    => 'Style 3',
                        'value'    => 'menu-style-3',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                'menu_style_3_border_field',
                                'menu_style_3_border_color_field',
                                'menu_style_3_item_border_radius_field'
                            ]
                        ]
                    ],
                    [
                        'label'    => 'Style 4',
                        'value'    => 'menu-style-4',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                'menu-style-4-item-bg-field'
                            ]
                        ]
                    ],
                    [
                        'label'    => 'Style 5',
                        'value'    => 'menu-style-5',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                'menu_style_5_item_border_bg_field'
                            ]
                        ]
                    ],
                    [
                        'label'    => 'Style 6',
                        'value'    => 'menu-style-6',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                'menu_style_6_item_border_bg_field'
                            ]
                        ]
                    ],
                    [
                        'label'    => 'Style 7',
                        'value'    => 'menu-style-7',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => [
                                'menu_style_7_item_border_bg_field'
                            ]
                        ]
                    ]
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'       => 'Normal style',
                'name'        => 'menu_normal_style_separator',
                'type'        => self::FIELD_SEPARATOR,
                'value'       => '',
                'editorClass' => [
                    'padding-m'
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'    => 'Color',
                'name'     => 'menu_color_field',
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => 'rgb(0,0,0)',
                'selector' => [
                    '{{SELECTOR}} .menu-default > ul > li > a'                         => 'color: {{VALUE}}',
                    '{{SELECTOR}} .menu-default > ul > li.flexMenu-viewMore > a:after' => 'color: {{VALUE}}'
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'    => 'Background',
                'name'     => 'menu_bg_field',
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => '',
                'selector' => [
                    '{{SELECTOR}} .menu-default > ul > li > a' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'       => 'Hover style',
                'name'        => 'menu_hover_style_separator',
                'type'        => self::FIELD_SEPARATOR,
                'value'       => '',
                'editorClass' => [
                    'padding-m'
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'    => 'Hover Color',
                'name'     => 'menu_hover_color_field',
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => 'rgb(255,51,102)',
                'selector' => [
                    '{{SELECTOR}}.sq-menu .menu-default > ul > li:hover > a'                       => 'color: {{VALUE}}',
                    '{{SELECTOR}}.sq-menu .menu-default > ul > li > a:hover'                       => 'color: {{VALUE}}',
                    '{{SELECTOR}}.sq-menu .menu-default > ul > li > a.item:not(input):focus'         => 'color: {{VALUE}}',
                    '{{SELECTOR}}.sq-menu .menu-default > ul > li.menu-item.current-menu-item > a' => 'color: {{VALUE}}',
                    '{{SELECTOR}} .menu-default > ul > li.flexMenu-viewMore:hover > a:after'       => 'color: {{VALUE}}'
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'      => 'Item border width',
                'name'       => 'menu_style_1_border_field',
                'visibility' => false,
                'type'       => self::FIELD_INPUT_NUMBER,
                'value'      => '3',
                'units'      => [
                    [
                        'type'   => 'px',
                        'active' => true,
                    ],
                ],
                'selector'   => [
                    '{{SELECTOR}} .menu-style-1 > ul > li.menu-item > a::before' => 'height: {{VALUE}}{{UNIT}}',
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'      => 'Item border color',
                'name'       => 'menu_style_1_border_color_field',
                'visibility' => false,
                'type'       => self::FIELD_COLOR_PICKER,
                'value'      => 'rgb(255,51,102)',
                'units'      => [],
                'selector'   => [
                    '{{SELECTOR}}  .menu-style-1 > ul > li.menu-item > a::before' => 'background: {{VALUE}}'
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'      => 'Item background',
                'name'       => 'menu_style_2_item_bg_field',
                'visibility' => false,
                'type'       => self::FIELD_COLOR_PICKER,
                'value'      => '',
                'selector'   => [
                    '{{SELECTOR}} .menu-style-2 > ul > li.menu-item.current-menu-item > a' => 'background: {{VALUE}}',
                    '{{SELECTOR}} .menu-style-2 > ul > li.menu-item:hover > a'             => 'background: {{VALUE}}'
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'      => 'Item border radius',
                'name'       => 'menu_style_2_item_border_radius_field',
                'visibility' => false,
                'type'       => self::FIELD_BORDER_RADIUS,
                'value'      => [
                    [
                        'value'    => '3',
                        'position' => 'Top left'
                    ],
                    [
                        'value'    => '3',
                        'position' => 'Top right'
                    ],
                    [
                        'value'    => '3',
                        'position' => 'Bottom right'
                    ],
                    [
                        'value'    => '3',
                        'position' => 'Bottom left'
                    ]
                ],
                'units'      => [
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
                'selector'   => [
                    '{{SELECTOR}} .menu-style-2 > ul > li.menu-item.current-menu-item > a' => [
                        'border-top-left-radius: {{VALUE_1}}{{UNIT}}',
                        'border-top-right-radius: {{VALUE_2}}{{UNIT}}',
                        'border-bottom-right-radius: {{VALUE_3}}{{UNIT}}',
                        'border-bottom-left-radius: {{VALUE_4}}{{UNIT}}'
                    ],
                    '{{SELECTOR}} .menu-style-2 > ul > li.menu-item:hover > a'             => [
                        'border-top-left-radius: {{VALUE_1}}{{UNIT}}',
                        'border-top-right-radius: {{VALUE_2}}{{UNIT}}',
                        'border-bottom-right-radius: {{VALUE_3}}{{UNIT}}',
                        'border-bottom-left-radius: {{VALUE_4}}{{UNIT}}'
                    ],
                    '{{SELECTOR}} .menu-style-2 > ul > li.menu-item.has-submenu .submenu'  => [
                        'border-top-right-radius: {{VALUE_2}}{{UNIT}}',
                        'border-bottom-right-radius: {{VALUE_3}}{{UNIT}}',
                        'border-bottom-left-radius: {{VALUE_4}}{{UNIT}}'
                    ]
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'      => 'Item border width',
                'name'       => 'menu_style_3_border_field',
                'visibility' => false,
                'type'       => self::FIELD_INPUT_NUMBER,
                'value'      => '3',
                'units'      => [
                    [
                        'type'   => 'px',
                        'active' => true,
                    ],
                ],
                'selector'   => [
                    '{{SELECTOR}} .menu-style-3 > ul > li.menu-item > a' => 'border-width: {{VALUE}}{{UNIT}}',
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'      => 'Item border color',
                'name'       => 'menu_style_3_border_color_field',
                'visibility' => false,
                'type'       => self::FIELD_COLOR_PICKER,
                'value'      => 'rgb(255,51,102)',
                'selector'   => [
                    '{{SELECTOR}} .menu-style-3 > ul > li.menu-item:hover > a'             => 'border-color: {{VALUE}}',
                    '{{SELECTOR}} .menu-style-3 > ul > li.menu-item.current-menu-item > a' => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'      => 'Item border radius',
                'name'       => 'menu_style_3_item_border_radius_field',
                'visibility' => false,
                'type'       => self::FIELD_BORDER_RADIUS,
                'value'      => [
                    [
                        'value'    => '3',
                        'position' => 'Top left'
                    ],
                    [
                        'value'    => '3',
                        'position' => 'Top right'
                    ],
                    [
                        'value'    => '3',
                        'position' => 'Bottom right'
                    ],
                    [
                        'value'    => '3',
                        'position' => 'Bottom left'
                    ]
                ],
                'units'      => [
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
                'selector'   => [
                    '{{SELECTOR}} .menu-style-3 > ul > li.menu-item > a' => [
                        'border-top-left-radius: {{VALUE_1}}{{UNIT}}',
                        'border-top-right-radius: {{VALUE_2}}{{UNIT}}',
                        'border-bottom-right-radius: {{VALUE_3}}{{UNIT}}',
                        'border-bottom-left-radius: {{VALUE_4}}{{UNIT}}'
                    ]
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'      => 'Item background',
                'name'       => 'menu_style_4_item_bg_field',
                'visibility' => false,
                'type'       => self::FIELD_COLOR_PICKER,
                'value'      => '',
                'units'      => [],
                'selector'   => [
                    '{{SELECTOR}} .menu-style-4 > ul > li.menu-item > a:after' => 'background: {{VALUE}}'
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'      => 'Item border color',
                'name'       => 'menu_style_5_item_border_bg_field',
                'visibility' => false,
                'type'       => self::FIELD_COLOR_PICKER,
                'value'      => 'rgb(255,51,102)',
                'selector'   => [
                    '{{SELECTOR}} .menu-style-5 > ul > li.menu-item > a::before' => 'background: {{VALUE}}',
                    '{{SELECTOR}} .menu-style-5 > ul > li.menu-item > a::after'  => 'background: {{VALUE}}'
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'      => 'Item border color',
                'name'       => 'menu_style_6_item_border_bg_field',
                'visibility' => false,
                'type'       => self::FIELD_COLOR_PICKER,
                'value'      => 'rgb(255,51,102)',
                'selector'   => [
                    '{{SELECTOR}} .menu-style-6 > ul > li.menu-item > a::before' => 'background: {{VALUE}}'
                ]
            ]
        );

        $fields_2[] = new EditorSectionField(
            [
                'label'      => 'Item border color',
                'name'       => 'menu_style_7_item_border_bg_field',
                'visibility' => false,
                'type'       => self::FIELD_COLOR_PICKER,
                'value'      => 'rgb(255,51,102)',
                'selector'   => [
                    '{{SELECTOR}} .menu-style-7 > ul > li.menu-item > a::before' => 'border-color: {{VALUE}}',
                    '{{SELECTOR}} .menu-style-7 > ul > li.menu-item > a::after'  => 'border-color: {{VALUE}}',
                    '{{SELECTOR}} .menu-style-7 > ul > li.menu-item::before'     => 'border-color: {{VALUE}}',
                    '{{SELECTOR}} .menu-style-7 > ul > li.menu-item::after'      => 'border-color: {{VALUE}}'
                ]
            ]
        );

        $fields_3 = [];

        $fields_3[] = new EditorSectionField(
            [
                'label'    => 'Container padding',
                'name'     => 'submenu_box_padding_field',
                'type'     => self::FIELD_SPACING,
                'value'    => [
                    [
                        'position' => 'Top',
                        'value'    => '8',
                    ],
                    [
                        'position' => 'Right',
                        'value'    => '8',
                    ],
                    [
                        'position' => 'Bottom',
                        'value'    => '8',
                    ],
                    [
                        'position' => 'Left',
                        'value'    => '8',
                    ],
                ],
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true,
                    ],
                    [
                        'type'   => 'em',
                        'active' => false,
                    ],
                    [
                        'type'   => '%',
                        'active' => false,
                    ],
                ],
                'selector' => [
                    '{{SELECTOR}} .menu-default > ul > li ul' => [
                        'padding-top: {{VALUE_1}}{{UNIT}}',
                        'padding-right: {{VALUE_2}}{{UNIT}}',
                        'padding-bottom: {{VALUE_3}}{{UNIT}}',
                        'padding-left: {{VALUE_4}}{{UNIT}}',
                    ],
                ]
            ]
        );


        $fields_3[] = new EditorSectionField(
            [
                'label'    => 'Items padding',
                'name'     => 'submenu_padding_field',
                'type'     => self::FIELD_SPACING,
                'value'    => [
                    [
                        'position' => 'Top',
                        'value'    => '5',
                    ],
                    [
                        'position' => 'Right',
                        'value'    => '5',
                    ],
                    [
                        'position' => 'Bottom',
                        'value'    => '5',
                    ],
                    [
                        'position' => 'Left',
                        'value'    => '5',
                    ],
                ],
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true,
                    ],
                    [
                        'type'   => 'em',
                        'active' => false,
                    ],
                    [
                        'type'   => '%',
                        'active' => false,
                    ],
                ],
                'selector' => [
                    '{{SELECTOR}} .menu-default > ul > li ul > li > a.item' => [
                        'padding-top: {{VALUE_1}}{{UNIT}}',
                        'padding-right: {{VALUE_2}}{{UNIT}}',
                        'padding-bottom: {{VALUE_3}}{{UNIT}}',
                        'padding-left: {{VALUE_4}}{{UNIT}}',
                    ],
                ]
            ]
        );

        $fields_3[] = new EditorSectionField(
            [
                'label'       => 'Style',
                'name'        => 'submenu_style_normal_separator',
                'type'        => self::FIELD_SEPARATOR,
                'value'       => '',
                'editorClass' => [
                    'padding-m'
                ]
            ]
        );

        $fields_3[] = new EditorSectionField(
            [
                'label'    => 'Color',
                'name'     => 'submenu_color_n_field',
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => 'rgb(0,0,0)',
                'selector' => [
                    '{{SELECTOR}} .menu-default ul li > ul.submenu a' => 'color: {{VALUE}}'
                ]
            ]
        );

        $fields_3[] = new EditorSectionField(
            [
                'label'    => 'Hover color',
                'name'     => 'submenu_color_h_field',
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => '',
                'selector' => [
                    '{{SELECTOR}} .menu-default ul li > ul.submenu li:hover a' => 'color: {{VALUE}}'
                ]
            ]
        );


        $fields_3[] = new EditorSectionField(
            [
                'label'    => 'Background',
                'name'     => 'submenu_bg_color_n_field',
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => 'rgb(255, 255, 255)',
                'selector' => [
                    '{{SELECTOR}} .menu-default ul li > ul.submenu' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $fields_4 = [];

        $fields_4[] = new EditorSectionField(
            [
                'label'    => 'Alignment',
                'name'     => 'align_field',
                'only'     => 'section',
                'type'     => self::FIELD_BUTTON_GROUP,
                'value'    => [
                    [
                        'label'    => '<span class="mdi mdi-format-align-left mdi-18px"></span>',
                        'value'    => 'flex-start',
                        'selected' => true,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '<span class="mdi mdi-format-align-center mdi-18px"></span>',
                        'value'    => 'center',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '<span class="mdi mdi-format-align-right mdi-18px"></span>',
                        'value'    => 'flex-end',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} .main-menu' => 'justify-content: {{VALUE}}'
                ],
            ]
        );

        $fields_4[] = new EditorSectionField(
            [
                'label'       => 'Typography',
                'name'        => 'typo_separator',
                'type'        => self::FIELD_SEPARATOR,
                'value'       => '',
                'editorClass' => [
                    'padding-m'
                ]
            ]
        );

        $fields_4[] = new EditorSectionField(
            [
                'label'    => 'Font family',
                'name'     => 'typo_font_family_field',
                'type'     => self::FIELD_FONT_FAMILY,
                'value'    => '',
                'selector' => [
                    '{{SELECTOR}} a' => 'font-family: {{VALUE}}'
                ]
            ]
        );

        $fields_4[] = new EditorSectionField(
            [
                'label'    => 'Font size',
                'name'     => 'typo_font_size_field',
                'type'     => self::FIELD_INPUT_NUMBER,
                'value'    => '14',
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true
                    ],
                    [
                        'type'   => 'em',
                        'active' => false
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} a' => 'font-size: {{VALUE}}{{UNIT}}'
                ]
            ]
        );

        $fields_4[] = new EditorSectionField(
            [
                'label'    => 'Font weight',
                'name'     => 'typo_font_weight_field',
                'type'     => self::FIELD_DROPDOWN,
                'value'    => [
                    [
                        'label'    => '100',
                        'value'    => '100',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '200',
                        'value'    => '200',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '300',
                        'value'    => '300',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '400',
                        'value'    => '400',
                        'selected' => true,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '500',
                        'value'    => '500',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '600',
                        'value'    => '600',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '700',
                        'value'    => '700',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '800',
                        'value'    => '800',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '900',
                        'value'    => '900',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} a' => 'font-weight: {{VALUE}}'
                ]
            ]
        );

        $fields_4[] = new EditorSectionField(
            [
                'label'    => 'Text transform',
                'name'     => 'typo_font_transform_field',
                'type'     => self::FIELD_DROPDOWN,
                'value'    => [
                    [
                        'label'    => 'Initial',
                        'value'    => 'initial',
                        'selected' => true,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Uppercase',
                        'value'    => 'uppercase',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Lowercase',
                        'value'    => 'lowercase',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Capitalize',
                        'value'    => 'capitalize',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} a' => 'text-transform: {{VALUE}}'
                ]
            ]
        );

        $fields_4[] = new EditorSectionField(
            [
                'label'    => 'Text style',
                'name'     => 'typo_font_type_field',
                'type'     => self::FIELD_DROPDOWN,
                'value'    => [
                    [
                        'label'    => 'Normal',
                        'value'    => 'normal',
                        'selected' => true,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Italic',
                        'value'    => 'italic',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Oblique',
                        'value'    => 'oblique',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} a' => 'font-style: {{VALUE}}'
                ]
            ]
        );

        $fields_4[] = new EditorSectionField(
            [
                'label'    => 'Letter spacing',
                'name'     => 'typo_letter_spacing_field',
                'type'     => self::FIELD_INPUT_NUMBER,
                'value'    => '2',
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true
                    ],
                    [
                        'type'   => 'em',
                        'active' => false
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} .item-child' => 'letter-spacing: {{VALUE}}{{UNIT}}'
                ]
            ]
        );

        $fields_4[] = new EditorSectionField(
            [
                'label'       => 'Layout',
                'name'        => 'menu_layout_separator',
                'type'        => self::FIELD_SEPARATOR,
                'value'       => '',
                'editorClass' => [
                    'padding-m'
                ]
            ]
        );

        $fields_4[] = new EditorSectionField(
            [
                'label'    => 'Padding',
                'name'     => 'padding_field_menu',
                'type'     => self::FIELD_SPACING,
                'value'    => [
                    [
                        'position' => 'Top',
                        'value'    => '10',
                    ],
                    [
                        'position' => 'Right',
                        'value'    => '10',
                    ],
                    [
                        'position' => 'Bottom',
                        'value'    => '10',
                    ],
                    [
                        'position' => 'Left',
                        'value'    => '10',
                    ],
                ],
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true,
                    ],
                    [
                        'type'   => 'em',
                        'active' => false,
                    ],
                    [
                        'type'   => '%',
                        'active' => false,
                    ],
                ],
                'selector' => [
                    '{{SELECTOR}} .menu-default > ul > li > a.item' => [
                        'padding-top: {{VALUE_1}}{{UNIT}}',
                        'padding-right: {{VALUE_2}}{{UNIT}}',
                        'padding-bottom: {{VALUE_3}}{{UNIT}}',
                        'padding-left: {{VALUE_4}}{{UNIT}}',
                    ],
                ]
            ]
        );

        $fields_4[] = new EditorSectionField(
            [
                'label'    => 'Margin',
                'name'     => 'margin_field_menu',
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
                    '{{SELECTOR}} .menu-default > ul > li' => [
                        'margin-top: {{VALUE_1}}{{UNIT}}',
                        'margin-right: {{VALUE_2}}{{UNIT}}',
                        'margin-bottom: {{VALUE_3}}{{UNIT}}',
                        'margin-left: {{VALUE_4}}{{UNIT}}'
                    ]
                ]
            ]
        );

        $fields_4[] = new EditorSectionField(
            [
                'label'    => 'Border radius',
                'name'     => 'menu_border_radius_field',
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
                    '{{SELECTOR}} .menu-default > ul > li > a' => [
                        'border-top-left-radius: {{VALUE_1}}{{UNIT}}',
                        'border-top-right-radius: {{VALUE_2}}{{UNIT}}',
                        'border-bottom-right-radius: {{VALUE_3}}{{UNIT}}',
                        'border-bottom-left-radius: {{VALUE_4}}{{UNIT}}'
                    ]
                ]
            ]
        );

        $fields_5 = [];

        $fields_5[] = new EditorSectionField(
            [
                'label'       => 'Typography',
                'name'        => 'menu_2_typo_separator',
                'type'        => self::FIELD_SEPARATOR,
                'value'       => '',
                'editorClass' => [
                    'padding-m'
                ]
            ]
        );

        $fields_5[] = new EditorSectionField(
            [
                'label'    => 'Font family',
                'name'     => 'menu_2_typo_font_family_field',
                'type'     => self::FIELD_FONT_FAMILY,
                'value'    => '',
                'selector' => [
                    '{{SELECTOR}} a' => 'font-family: {{VALUE}}'
                ]
            ]
        );

        $fields_5[] = new EditorSectionField(
            [
                'label'    => 'Font size',
                'name'     => 'menu_2_typo_font_size_field',
                'type'     => self::FIELD_INPUT_NUMBER,
                'value'    => '14',
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true
                    ],
                    [
                        'type'   => 'em',
                        'active' => false
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} a' => 'font-size: {{VALUE}}{{UNIT}}'
                ]
            ]
        );

        $fields_5[] = new EditorSectionField(
            [
                'label'    => 'Font weight',
                'name'     => 'menu_2_typo_font_weight_field',
                'type'     => self::FIELD_DROPDOWN,
                'value'    => [
                    [
                        'label'    => '100',
                        'value'    => '100',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '200',
                        'value'    => '200',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '300',
                        'value'    => '300',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '400',
                        'value'    => '400',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '500',
                        'value'    => '500',
                        'selected' => true,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '600',
                        'value'    => '600',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '700',
                        'value'    => '700',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '800',
                        'value'    => '800',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => '900',
                        'value'    => '900',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} a' => 'font-weight: {{VALUE}}'
                ]
            ]
        );

        $fields_5[] = new EditorSectionField(
            [
                'label'    => 'Text transform',
                'name'     => 'menu_2_typo_font_transform_field',
                'type'     => self::FIELD_DROPDOWN,
                'value'    => [
                    [
                        'label'    => 'Initial',
                        'value'    => 'initial',
                        'selected' => true,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Uppercase',
                        'value'    => 'uppercase',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Lowercase',
                        'value'    => 'lowercase',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Capitalize',
                        'value'    => 'capitalize',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} a' => 'text-transform: {{VALUE}}'
                ]
            ]
        );

        $fields_5[] = new EditorSectionField(
            [
                'label'    => 'Text style',
                'name'     => 'menu_2_typo_font_type_field',
                'type'     => self::FIELD_DROPDOWN,
                'value'    => [
                    [
                        'label'    => 'Normal',
                        'value'    => 'normal',
                        'selected' => true,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Italic',
                        'value'    => 'italic',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ],
                    [
                        'label'    => 'Oblique',
                        'value'    => 'oblique',
                        'selected' => false,
                        'trigger'  => [
                            'section' => [],
                            'field'   => []
                        ]
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} a' => 'font-style: {{VALUE}}'
                ]
            ]
        );

        $fields_5[] = new EditorSectionField(
            [
                'label'    => 'Letter spacing',
                'name'     => 'menu_2_typo_letter_spacing_field',
                'type'     => self::FIELD_INPUT_NUMBER,
                'value'    => '2',
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true
                    ],
                    [
                        'type'   => 'em',
                        'active' => false
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} .menu' => 'letter-spacing: {{VALUE}}{{UNIT}}'
                ]
            ]
        );

        $fields_5[] = new EditorSectionField(
            [
                'label'    => 'Line height',
                'name'     => 'menu_2_typo_line_height_field',
                'type'     => self::FIELD_INPUT_NUMBER,
                'value'    => '',
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true
                    ],
                    [
                        'type'   => 'em',
                        'active' => false
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} .menu' => 'line-height: {{VALUE}}{{UNIT}}'
                ]
            ]
        );

        $fields_5[] = new EditorSectionField(
            [
                'label'       => 'Layout',
                'name'        => 'menu_2_layout_separator',
                'type'        => self::FIELD_SEPARATOR,
                'value'       => '',
                'editorClass' => [
                    'padding-m'
                ]
            ]
        );

        $fields_5[] = new EditorSectionField(
            [
                'label'    => 'Padding',
                'name'     => 'padding_field_menu_2',
                'type'     => self::FIELD_SPACING,
                'value'    => [
                    [
                        'position' => 'Top',
                        'value'    => '',
                    ],
                    [
                        'position' => 'Right',
                        'value'    => '',
                    ],
                    [
                        'position' => 'Bottom',
                        'value'    => '',
                    ],
                    [
                        'position' => 'Left',
                        'value'    => '',
                    ],
                ],
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true,
                    ],
                    [
                        'type'   => 'em',
                        'active' => false,
                    ],
                    [
                        'type'   => '%',
                        'active' => false,
                    ],
                ],
                'selector' => [
                    '{{SELECTOR}} .menu > ul' => [
                        'padding-top: {{VALUE_1}}{{UNIT}}',
                        'padding-right: {{VALUE_2}}{{UNIT}}',
                        'padding-bottom: {{VALUE_3}}{{UNIT}}',
                        'padding-left: {{VALUE_4}}{{UNIT}}',
                    ],
                ]
            ]
        );

        $fields_5[] = new EditorSectionField(
            [
                'label'       => 'Style',
                'name'        => 'menu_2_style_separator',
                'type'        => self::FIELD_SEPARATOR,
                'value'       => '',
                'editorClass' => [
                    'padding-m'
                ]
            ]
        );

        $fields_5[] = new EditorSectionField(
            [
                'label'    => 'Color',
                'name'     => 'menu_2_color_field',
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => '',
                'selector' => [
                    '{{SELECTOR}}.sq-menu-modburger .menu-burger > ul li a' => 'color: {{VALUE}}',
                ]
            ]
        );

        $fields_5[] = new EditorSectionField(
            [
                'label'    => 'Hover color',
                'name'     => 'menu_2_color_h_field',
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => '',
                'selector' => [
                    '{{SELECTOR}} .menu > ul li:hover a' => 'color: {{VALUE}}'
                ]
            ]
        );

        $fields_6 = [];

        $fields_6[] = new EditorSectionField(
            [
                'label'       => 'Typography',
                'name'        => 'submenu_2_typo_separator',
                'type'        => self::FIELD_SEPARATOR,
                'value'       => '',
                'editorClass' => [
                    'padding-m'
                ]
            ]
        );

        $fields_6[] = new EditorSectionField(
            [
                'label'    => 'Font size',
                'name'     => 'submenu_2_typo_font_size_field',
                'type'     => self::FIELD_INPUT_NUMBER,
                'value'    => '14',
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true
                    ],
                    [
                        'type'   => 'em',
                        'active' => false
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} .submenu a' => 'font-size: {{VALUE}}{{UNIT}}'
                ]
            ]
        );

        $fields_6[] = new EditorSectionField(
            [
                'label'    => 'Letter spacing',
                'name'     => 'submenu_2_typo_letter_spacing_field',
                'type'     => self::FIELD_INPUT_NUMBER,
                'value'    => '2',
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true
                    ],
                    [
                        'type'   => 'em',
                        'active' => false
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} .submenu' => 'letter-spacing: {{VALUE}}{{UNIT}}'
                ]
            ]
        );

        $fields_6[] = new EditorSectionField(
            [
                'label'    => 'Line height',
                'name'     => 'submenu_2_typo_line_height_field',
                'type'     => self::FIELD_INPUT_NUMBER,
                'value'    => '',
                'units'    => [
                    [
                        'type'   => 'px',
                        'active' => true
                    ],
                    [
                        'type'   => 'em',
                        'active' => false
                    ]
                ],
                'selector' => [
                    '{{SELECTOR}} .submenu' => 'line-height: {{VALUE}}{{UNIT}}'
                ]
            ]
        );

        $fields_6[] = new EditorSectionField(
            [
                'label'       => 'Style',
                'name'        => 'submenu_2_style_separator',
                'type'        => self::FIELD_SEPARATOR,
                'value'       => '',
                'editorClass' => [
                    'padding-m'
                ]
            ]
        );

        $fields_6[] = new EditorSectionField(
            [
                'label'    => 'Color',
                'name'     => 'submenu_2_color_field',
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => '',
                'selector' => [
                    '{{SELECTOR}} .submenu li a' => 'color: {{VALUE}}'
                ]
            ]
        );

        $fields_6[] = new EditorSectionField(
            [
                'label'    => 'Hover color',
                'name'     => 'submenu_2_color_h_field',
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => '',
                'selector' => [
                    '{{SELECTOR}} .submenu li:hover a' => 'color: {{VALUE}}'
                ]
            ]
        );

        $fields_7 = [];

        $fields_7[] = new EditorSectionField(
            [
                'label'    => 'Modal background',
                'name'     => 'modal_color_bg_field',
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => '',
                'selector' => [
                    '{{SELECTOR}} .menu' => 'background-color: {{VALUE}}'
                ]
            ]
        );

        $fields_7[] = new EditorSectionField(
            [
                'label'    => 'Close modal color',
                'name'     => 'modal_close_color_field',
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => '',
                'selector' => [
                    '{{SELECTOR}} .sq-menu-modburger-close' => 'color: {{VALUE}}'
                ]
            ]
        );

        $fields_7[] = new EditorSectionField(
            [
                'label'    => 'Toggle Menu Color',
                'name'     => 'modal_toggle_color',
                'type'     => self::FIELD_COLOR_PICKER,
                'value'    => '',
                'selector' => [
                    '{{SELECTOR}}.sq-menu-modburger .sq-menu-modburger-toggle a' => 'color: {{VALUE}}',
                ]
            ]
        );

        $this->addSection( new EditorSection( [
            'title' => $this->name,
            'name'  => 'option-section'
        ] ), $fields_1 );

        $this->addSection( new EditorSection( [
            'title' => 'Menu presets',
            'name'  => 'menu-section',
            'state' => false
        ] ), $fields_2 );

        $this->addSection( new EditorSection( [
            'title' => 'Menu',
            'name'  => 'style-menu',
        ] ), $fields_4, 'tab_style_menu' );

        $this->addSection( new EditorSection( [
            'title' => 'Submenu',
            'name'  => 'style-submenu',
        ] ), $fields_3, 'tab_style_submenu' );

        $this->addSection( new EditorSection( [
            'title'      => 'Modal',
            'name'       => 'style-modal',
            'visibility' => false,
        ] ), $fields_7, 'tab_style_modal' );

        $this->addSection( new EditorSection( [
            'title'      => 'Menu Burger',
            'name'       => 'style-menu-2',
            'visibility' => false,
        ] ), $fields_5, 'tab_style_menu_2' );

        $this->addSection( new EditorSection( [
            'title'      => 'Modal Submenu',
            'name'       => 'style-submenu-2',
            'visibility' => false,
        ] ), $fields_6, 'tab_style_submenu_2' );

        $this->setBoxDefaults( [
            self::ADVANCED_FULL_HEIGHT => [
                'value' => 'is-full-height'
            ]
        ] );

        $this->setBoxDefaults( [
            self::ADVANCED_MARGIN => [
                'value' => [ 0, 8, 0, 8 ]
            ]
        ] );
    }
}
