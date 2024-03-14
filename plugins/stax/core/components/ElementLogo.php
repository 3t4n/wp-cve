<?php

/**
 * Logo component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */
namespace Stax;

if ( !defined( 'ABSPATH' ) ) {
    exit;
}
class ElementLogo extends Element implements  ElementInterface 
{
    /**
     * ElementLogo constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->name = 'Logo';
        $this->slug = 'logo';
        $this->icon->type = 'mdi-sticker-emoji';
        $this->template = $this->getTemplate( $this->slug );
        $fields_1 = [];
        $fields_1[] = new EditorSectionField( [
            'label' => 'Logo type',
            'name'  => 'logo_type_field',
            'type'  => self::FIELD_RADIO,
            'value' => [ [
            'label'   => 'Img',
            'value'   => 'img',
            'checked' => true,
            'trigger' => [
            'section' => [ 'image-section', 'resized-logo-section', 'transparent-logo-section' ],
            'field'   => [],
        ],
        ], [
            'label'   => 'Text',
            'value'   => 'text',
            'checked' => false,
            'trigger' => [
            'section' => [ 'text-section', 'icon-section' ],
            'field'   => [],
        ],
        ] ],
        ] );
        $fields_1[] = new EditorSectionField( [
            'label' => 'Link',
            'name'  => 'url_field',
            'type'  => self::FIELD_INPUT_URL,
            'value' => get_home_url(),
        ] );
        $fields_1[] = new EditorSectionField( [
            'label'    => 'Alignment',
            'name'     => 'align_field',
            'only'     => 'section',
            'type'     => self::FIELD_BUTTON_GROUP,
            'value'    => [ [
            'label'    => '<span class="mdi mdi-format-align-left mdi-18px"></span>',
            'value'    => 'left',
            'selected' => true,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ], [
            'label'    => '<span class="mdi mdi-format-align-center mdi-18px"></span>',
            'value'    => 'center',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ], [
            'label'    => '<span class="mdi mdi-format-align-right mdi-18px"></span>',
            'value'    => 'right',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ] ],
            'selector' => [
            '{{SELECTOR}}' => 'text-align: {{VALUE}}',
        ],
        ] );
        $fields_2 = [];
        $fields_2[] = new EditorSectionField( [
            'label' => '',
            'name'  => 'img_source_field',
            'type'  => self::FIELD_IMAGE,
            'value' => STAX_ASSETS_URL . 'images/choose_your_image.png',
        ] );
        $fields_2[] = new EditorSectionField( [
            'label' => 'Caption',
            'name'  => 'img_alt_field',
            'type'  => self::FIELD_INPUT_TEXT,
            'value' => '',
        ] );
        $fields_2[] = new EditorSectionField( [
            'label'    => 'Border radius',
            'name'     => 'img_border_radius_field',
            'type'     => self::FIELD_BORDER_RADIUS,
            'value'    => [
            [
            'value'    => '',
            'position' => 'Top left',
        ],
            [
            'value'    => '',
            'position' => 'Top right',
        ],
            [
            'value'    => '',
            'position' => 'Bottom right',
        ],
            [
            'value'    => '',
            'position' => 'Bottom left',
        ]
        ],
            'units'    => [ [
            'type'   => 'px',
            'active' => true,
        ], [
            'type'   => 'em',
            'active' => false,
        ], [
            'type'   => '%',
            'active' => false,
        ] ],
            'selector' => [
            '{{SELECTOR}} img' => [
            'border-top-left-radius: {{VALUE_1}}{{UNIT}}',
            'border-top-right-radius: {{VALUE_2}}{{UNIT}}',
            'border-bottom-right-radius: {{VALUE_3}}{{UNIT}}',
            'border-bottom-left-radius: {{VALUE_4}}{{UNIT}}'
        ],
        ],
        ] );
        $fields_2[] = new EditorSectionField( [
            'label'       => 'Logo on default header',
            'name'        => 'logo_normal_separator',
            'only'        => 'header',
            'type'        => self::FIELD_SEPARATOR,
            'value'       => '',
            'editorClass' => [ 'padding-m' ],
        ] );
        $fields_2[] = new EditorSectionField( [
            'label'    => 'Image height',
            'name'     => 'logo_normal_height_field',
            'only'     => 'header',
            'type'     => self::FIELD_INPUT_NUMBER,
            'value'    => '',
            'units'    => [ [
            'type'   => 'px',
            'active' => true,
        ] ],
            'selector' => [
            '.header-section {{SELECTOR}} img, .hb-section {{SELECTOR}} img' => [ 'height: {{VALUE}}{{UNIT}}', 'max-height: initial !important' ],
        ],
        ] );
        $fields_3 = [];
        $fields_3[] = new EditorSectionField( [
            'label' => 'Text',
            'name'  => 'logo_text_field',
            'type'  => self::FIELD_INPUT_TEXT,
            'value' => get_bloginfo( 'name' ),
        ] );
        $fields_3[] = new EditorSectionField( [
            'label'       => 'Typography',
            'name'        => 'text_typo_separator',
            'type'        => self::FIELD_SEPARATOR,
            'value'       => '',
            'editorClass' => [ 'padding-m' ],
        ] );
        $fields_3[] = new EditorSectionField( [
            'label'    => 'Font family',
            'name'     => 'typo_font-family_field',
            'type'     => self::FIELD_FONT_FAMILY,
            'value'    => '',
            'selector' => [
            '{{SELECTOR}} .logo-text' => 'font-family: {{VALUE}}',
        ],
        ] );
        $fields_3[] = new EditorSectionField( [
            'label'    => 'Font size',
            'name'     => 'typo_font_size_field',
            'type'     => self::FIELD_INPUT_NUMBER,
            'value'    => '14',
            'units'    => [ [
            'type'   => 'px',
            'active' => true,
        ], [
            'type'   => 'em',
            'active' => false,
        ] ],
            'selector' => [
            '{{SELECTOR}} .logo-text' => 'font-size: {{VALUE}}{{UNIT}}',
        ],
        ] );
        $fields_3[] = new EditorSectionField( [
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
            'field'   => [],
        ],
        ],
            [
            'label'    => '200',
            'value'    => '200',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => '300',
            'value'    => '300',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => '400',
            'value'    => '400',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => '500',
            'value'    => '500',
            'selected' => true,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => '600',
            'value'    => '600',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => '700',
            'value'    => '700',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => '800',
            'value'    => '800',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => '900',
            'value'    => '900',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ]
        ],
            'selector' => [
            '{{SELECTOR}} .logo-text' => 'font-weight: {{VALUE}}',
        ],
        ] );
        $fields_3[] = new EditorSectionField( [
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
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Uppercase',
            'value'    => 'uppercase',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Lowercase',
            'value'    => 'lowercase',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Capitalize',
            'value'    => 'capitalize',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ]
        ],
            'selector' => [
            '{{SELECTOR}} .logo-text' => 'text-transform: {{VALUE}}',
        ],
        ] );
        $fields_3[] = new EditorSectionField( [
            'label'    => 'Text style',
            'name'     => 'typo_font_style_field',
            'type'     => self::FIELD_DROPDOWN,
            'value'    => [ [
            'label'    => 'Normal',
            'value'    => 'normal',
            'selected' => true,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ], [
            'label'    => 'Italic',
            'value'    => 'italic',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ], [
            'label'    => 'Oblique',
            'value'    => 'oblique',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ] ],
            'selector' => [
            '{{SELECTOR}} .logo-text' => 'font-style: {{VALUE}}',
        ],
        ] );
        $fields_3[] = new EditorSectionField( [
            'label'    => 'Letter spacing',
            'name'     => 'typo_letter_spacing_field',
            'type'     => self::FIELD_INPUT_NUMBER,
            'value'    => '1',
            'units'    => [ [
            'type'   => 'px',
            'active' => true,
        ], [
            'type'   => 'em',
            'active' => false,
        ] ],
            'selector' => [
            '{{SELECTOR}} .item-child' => 'letter-spacing: {{VALUE}}{{UNIT}}',
        ],
        ] );
        $fields_3[] = new EditorSectionField( [
            'label'       => 'Layout',
            'name'        => 'general_style_separator',
            'type'        => self::FIELD_SEPARATOR,
            'value'       => '',
            'editorClass' => [ 'padding-m' ],
        ] );
        $fields_3[] = new EditorSectionField( [
            'label'    => 'Padding',
            'name'     => 'a_padding_field',
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
        ]
        ],
            'units'    => [ [
            'type'   => 'px',
            'active' => true,
        ], [
            'type'   => 'em',
            'active' => false,
        ], [
            'type'   => '%',
            'active' => false,
        ] ],
            'selector' => [
            '{{SELECTOR}} a' => [
            'padding-top: {{VALUE_1}}{{UNIT}}',
            'padding-right: {{VALUE_2}}{{UNIT}}',
            'padding-bottom: {{VALUE_3}}{{UNIT}}',
            'padding-left: {{VALUE_4}}{{UNIT}}'
        ],
        ],
        ] );
        $fields_3[] = new EditorSectionField( [
            'label'    => 'Border radius',
            'name'     => 'border_radius_field',
            'type'     => self::FIELD_BORDER_RADIUS,
            'value'    => [
            [
            'position' => 'Top left',
            'value'    => '',
        ],
            [
            'position' => 'Top right',
            'value'    => '',
        ],
            [
            'position' => 'Bottom right',
            'value'    => '',
        ],
            [
            'position' => 'Bottom left',
            'value'    => '',
        ]
        ],
            'units'    => [ [
            'type'   => 'px',
            'active' => true,
        ], [
            'type'   => 'em',
            'active' => false,
        ], [
            'type'   => '%',
            'active' => false,
        ] ],
            'selector' => [
            '{{SELECTOR}} a' => 'border-radius: {{VALUE_1}}{{UNIT}} {{VALUE_2}}{{UNIT}} {{VALUE_3}}{{UNIT}} {{VALUE_4}}{{UNIT}}',
        ],
        ] );
        $fields_3[] = new EditorSectionField( [
            'label'       => 'Style',
            'name'        => 'text_style_separator',
            'type'        => self::FIELD_SEPARATOR,
            'value'       => '',
            'editorClass' => [ 'padding-m' ],
        ] );
        $fields_3[] = new EditorSectionField( [
            'label'    => 'Color',
            'name'     => 'color_n_field',
            'type'     => self::FIELD_COLOR_PICKER,
            'value'    => 'rgba(87, 44, 136, 1)',
            'selector' => [
            '{{SELECTOR}} > a' => 'color: {{VALUE}}',
        ],
        ] );
        $fields_3[] = new EditorSectionField( [
            'label'    => 'Background',
            'name'     => 'bg_color_n_field',
            'type'     => self::FIELD_COLOR_PICKER,
            'value'    => 'rgb(253,251,255)',
            'selector' => [
            '{{SELECTOR}} a' => 'background-color: {{VALUE}}',
        ],
        ] );
        $fields_3[] = new EditorSectionField( [
            'label'       => 'Hover style',
            'name'        => 'text_style_hover_separator',
            'type'        => self::FIELD_SEPARATOR,
            'value'       => '',
            'editorClass' => [ 'padding-m' ],
        ] );
        $fields_3[] = new EditorSectionField( [
            'label'    => 'Color',
            'name'     => 'color_h_field',
            'type'     => self::FIELD_COLOR_PICKER,
            'value'    => 'rgba(87, 44, 136, 1)',
            'selector' => [
            '{{SELECTOR}}:hover > a' => 'color: {{VALUE}}',
        ],
        ] );
        $fields_3[] = new EditorSectionField( [
            'label'    => 'Background',
            'name'     => 'bg_color_h_field',
            'type'     => self::FIELD_COLOR_PICKER,
            'value'    => 'rgb(253,251,255)',
            'selector' => [
            '{{SELECTOR}}:hover a' => 'background-color: {{VALUE}}',
        ],
        ] );
        $fields_4 = [];
        $fields_4[] = new EditorSectionField( [
            'label' => 'Show',
            'name'  => 'icon_show_field',
            'type'  => self::FIELD_SWITCH,
            'value' => [ [
            'label'   => '',
            'value'   => 'show',
            'checked' => false,
            'trigger' => [
            'section' => [],
            'field'   => [
            'icon_field',
            'icon_size_field',
            'icon_style_separator',
            'icon_padding_field',
            'icon_border_radius_field',
            'icon_normal_separator',
            'icon_color_n_field',
            'icon_bg_color_n_field',
            'icon_border_type_n_field',
            'icon_hover_separator',
            'icon_color_h_field',
            'icon_bg_color_h_field',
            'icon_border_type_h_field'
        ],
        ],
        ] ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'      => 'Type',
            'name'       => 'icon_field',
            'visibility' => false,
            'type'       => self::FIELD_ICON,
            'value'      => '',
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'      => 'Size',
            'name'       => 'icon_size_field',
            'visibility' => false,
            'type'       => self::FIELD_DROPDOWN,
            'value'      => [
            [
            'label'    => '18 px',
            'value'    => 'mdi-18px',
            'selected' => true,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => '24 px',
            'value'    => 'mdi-24px',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => '36 px',
            'value'    => 'mdi-36px',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => '48 px',
            'value'    => 'mdi-48px',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ]
        ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'       => 'Layout',
            'name'        => 'icon_style_separator',
            'visibility'  => false,
            'type'        => self::FIELD_SEPARATOR,
            'value'       => '',
            'editorClass' => [ 'padding-m' ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'      => 'Padding',
            'name'       => 'icon_padding_field',
            'visibility' => false,
            'type'       => self::FIELD_SPACING,
            'value'      => [
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
        ]
        ],
            'units'      => [ [
            'type'   => 'px',
            'active' => true,
        ], [
            'type'   => 'em',
            'active' => false,
        ], [
            'type'   => '%',
            'active' => false,
        ] ],
            'selector'   => [
            '{{SELECTOR}} a span.mdi' => [
            'padding-top: {{VALUE_1}}{{UNIT}}',
            'padding-right: {{VALUE_2}}{{UNIT}}',
            'padding-bottom: {{VALUE_3}}{{UNIT}}',
            'padding-left: {{VALUE_4}}{{UNIT}}'
        ],
        ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'      => 'Border radius',
            'name'       => 'icon_border_radius_field',
            'visibility' => false,
            'type'       => self::FIELD_BORDER_RADIUS,
            'value'      => [
            [
            'position' => 'Top left',
            'value'    => '',
        ],
            [
            'position' => 'Top right',
            'value'    => '',
        ],
            [
            'position' => 'Bottom right',
            'value'    => '',
        ],
            [
            'position' => 'Bottom left',
            'value'    => '',
        ]
        ],
            'units'      => [ [
            'type'   => 'px',
            'active' => true,
        ], [
            'type'   => 'em',
            'active' => false,
        ], [
            'type'   => '%',
            'active' => false,
        ] ],
            'selector'   => [
            '{{SELECTOR}} a span.mdi' => 'border-radius: {{VALUE_1}}{{UNIT}} {{VALUE_2}}{{UNIT}} {{VALUE_3}}{{UNIT}} {{VALUE_4}}{{UNIT}}',
        ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'       => 'Style',
            'name'        => 'icon_normal_separator',
            'visibility'  => false,
            'type'        => self::FIELD_SEPARATOR,
            'value'       => '',
            'editorClass' => [ 'padding-m' ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'      => 'Color',
            'name'       => 'icon_color_n_field',
            'visibility' => false,
            'type'       => self::FIELD_COLOR_PICKER,
            'value'      => '',
            'selector'   => [
            '{{SELECTOR}} > a span.mdi' => 'color: {{VALUE}}',
        ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'      => 'Background',
            'name'       => 'icon_bg_color_n_field',
            'visibility' => false,
            'type'       => self::FIELD_COLOR_PICKER,
            'value'      => '',
            'selector'   => [
            '{{SELECTOR}} a span.mdi' => 'background-color: {{VALUE}}',
        ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'      => 'Border type',
            'name'       => 'icon_border_type_n_field',
            'visibility' => false,
            'type'       => self::FIELD_DROPDOWN,
            'value'      => [
            [
            'label'    => 'None',
            'value'    => '',
            'selected' => true,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Solid',
            'value'    => 'solid',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ 'icon_border_width_n_field', 'icon_border_color_n_field' ],
        ],
        ],
            [
            'label'    => 'Double',
            'value'    => 'double',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ 'icon_border_width_n_field', 'icon_border_color_n_field' ],
        ],
        ],
            [
            'label'    => 'Dotted',
            'value'    => 'dotted',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ 'icon_border_width_n_field', 'icon_border_color_n_field' ],
        ],
        ],
            [
            'label'    => 'Dashed',
            'value'    => 'dashed',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ 'icon_border_width_n_field', 'icon_border_color_n_field' ],
        ],
        ]
        ],
            'selector'   => [
            '{{SELECTOR}} a span.mdi' => 'border-style: {{VALUE}}',
        ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'      => 'Border width',
            'name'       => 'icon_border_width_n_field',
            'visibility' => false,
            'type'       => self::FIELD_INPUT_NUMBER,
            'value'      => '1',
            'units'      => [ [
            'type'   => 'px',
            'active' => true,
        ] ],
            'selector'   => [
            '{{SELECTOR}} a span.mdi' => 'border-width: {{VALUE}}{{UNIT}}',
        ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'      => 'Border color',
            'name'       => 'icon_border_color_n_field',
            'visibility' => false,
            'type'       => self::FIELD_COLOR_PICKER,
            'value'      => '',
            'selector'   => [
            '{{SELECTOR}} a span.mdi' => 'border-color: {{VALUE}}',
        ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'       => 'Hover style',
            'name'        => 'icon_hover_separator',
            'visibility'  => false,
            'type'        => self::FIELD_SEPARATOR,
            'value'       => '',
            'editorClass' => [ 'padding-m' ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'      => 'Color',
            'name'       => 'icon_color_h_field',
            'visibility' => false,
            'type'       => self::FIELD_COLOR_PICKER,
            'value'      => '',
            'selector'   => [
            '{{SELECTOR}}:hover > a span.mdi' => 'color: {{VALUE}}',
        ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'      => 'Background',
            'name'       => 'icon_bg_color_h_field',
            'visibility' => false,
            'type'       => self::FIELD_COLOR_PICKER,
            'value'      => '',
            'selector'   => [
            '{{SELECTOR}}:hover a span.mdi' => 'background-color: {{VALUE}}',
        ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'      => 'Border type',
            'name'       => 'icon_border_type_h_field',
            'visibility' => false,
            'type'       => self::FIELD_DROPDOWN,
            'value'      => [
            [
            'label'    => 'None',
            'value'    => '',
            'selected' => true,
            'trigger'  => [
            'section' => [],
            'field'   => [],
        ],
        ],
            [
            'label'    => 'Solid',
            'value'    => 'solid',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ 'icon_border_width_h_field', 'icon_border_color_h_field' ],
        ],
        ],
            [
            'label'    => 'Double',
            'value'    => 'double',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ 'icon_border_width_h_field', 'icon_border_color_h_field' ],
        ],
        ],
            [
            'label'    => 'Dotted',
            'value'    => 'dotted',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ 'icon_border_width_h_field', 'icon_border_color_h_field' ],
        ],
        ],
            [
            'label'    => 'Dashed',
            'value'    => 'dashed',
            'selected' => false,
            'trigger'  => [
            'section' => [],
            'field'   => [ 'icon_border_width_h_field', 'icon_border_color_h_field' ],
        ],
        ]
        ],
            'selector'   => [
            '{{SELECTOR}}:hover a span.mdi' => 'border-style: {{VALUE}}',
        ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'      => 'Border width',
            'name'       => 'icon_border_width_h_field',
            'visibility' => false,
            'type'       => self::FIELD_INPUT_NUMBER,
            'value'      => '1',
            'units'      => [ [
            'type'   => 'px',
            'active' => true,
        ] ],
            'selector'   => [
            '{{SELECTOR}}:hover a span.mdi' => 'border-width: {{VALUE}}{{UNIT}}',
        ],
        ] );
        $fields_4[] = new EditorSectionField( [
            'label'      => 'Border color',
            'name'       => 'icon_border_color_h_field',
            'visibility' => false,
            'type'       => self::FIELD_COLOR_PICKER,
            'value'      => '',
            'selector'   => [
            '{{SELECTOR}}:hover a span.mdi' => 'border-color: {{VALUE}}',
        ],
        ] );
        $fields_5 = [];
        $fields_6 = [];
        $this->addSection( new EditorSection( [
            'title' => $this->name,
            'name'  => 'option-section',
        ] ), $fields_1 );
        $this->addSection( new EditorSection( [
            'title' => 'Image',
            'name'  => 'image-section',
        ] ), $fields_2 );
        $this->addSection( new EditorSection( [
            'title'      => 'Text',
            'name'       => 'text-section',
            'visibility' => false,
        ] ), $fields_3 );
        $this->addSection( new EditorSection( [
            'title'      => 'Icon',
            'name'       => 'icon-section',
            'visibility' => false,
        ] ), $fields_4 );
        $free = false;
        $this->addSection( new EditorSection( [
            'title' => 'Logo on header resize',
            'name'  => 'resized-logo-section',
            'only'  => 'header',
            'state' => true,
            'free'  => $free,
        ] ), $fields_5 );
        $this->addSection( new EditorSection( [
            'title' => 'Logo on header transparent',
            'name'  => 'transparent-logo-section',
            'only'  => 'header',
            'state' => true,
            'free'  => $free,
        ] ), $fields_6 );
        $this->setBoxDefaults( [
            self::ADVANCED_FULL_HEIGHT => [
            'value' => 'is-full-height',
        ],
        ] );
    }

}