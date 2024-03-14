<?php
/**
 * Button component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ElementButton extends Element implements ElementInterface {
	/**
	 * ElementButton constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->name       = 'Button';
		$this->slug       = 'button';
		$this->icon->type = 'mdi-crop-square';
		$this->template   = $this->getTemplate( $this->slug );

		$fields = [];

		$fields[] = new EditorSectionField(
			[
				'label' => 'Link',
				'name'  => 'url_field',
				'type'  => self::FIELD_INPUT_URL,
				'value' => '#'
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label' => 'Link target',
				'name'  => 'url_target_field',
				'type'  => self::FIELD_DROPDOWN,
				'value' => [
					[
						'label'    => 'Self',
						'value'    => '_self',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Blank',
						'value'    => '_blank',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					]
				]
			]
		);

		$fields_1 = [];

		$fields_1[] = new EditorSectionField(
			[
				'label' => 'Text',
				'name'  => 'icon_text_field',
				'type'  => self::FIELD_INPUT_TEXT,
				'value' => 'Your text here'
			]
		);

		$fields_2 = [];

		$fields_2[] = new EditorSectionField(
			[
				'label' => 'Show',
				'name'  => 'icon_show_field',
				'type'  => self::FIELD_SWITCH,
				'value' => [
					[
						'label'   => '',
						'value'   => 'show',
						'checked' => false,
						'trigger' => [
							'section' => [ 'style-icon' ],
							'field'   => [
								'icon_type_field',
								'icon_size_field',
								'icon_style_separator',
								'icon_border_radius_field',
								'icon_bg_color_n_field',
								'icon_bg_color_h_field',
								'icon_color_n_field',
								'icon_color_h_field',
								'icon_padding_field',
								'icon_margin_field',
								'icon_border_type_n_field',
								'icon_border_type_h_field',
								'icon_normal_separator',
								'icon_hover_separator'
							]
						]
					]
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'      => 'Type',
				'name'       => 'icon_type_field',
				'type'       => self::FIELD_ICON,
				'visibility' => false,
				'value'      => 'mdi-arrow-right-bold-circle'
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'      => 'Size',
				'name'       => 'icon_size_field',
				'type'       => self::FIELD_DROPDOWN,
				'visibility' => false,
				'value'      => [
					[
						'label'    => '18 px',
						'value'    => 'mdi-18px',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '24 px',
						'value'    => 'mdi-24px',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '36 px',
						'value'    => 'mdi-36px',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '48 px',
						'value'    => 'mdi-48px',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					]
				]
			]
		);

		$fields_3 = [];

		$fields_3[] = new EditorSectionField(
			[
				'label'    => 'Alignment',
				'name'     => 'align_field',
				'only'     => 'section',
				'type'     => self::FIELD_BUTTON_GROUP,
				'value'    => [
					[
						'label'    => '<span class="mdi mdi-format-align-left mdi-18px"></span>',
						'value'    => 'left',
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
						'value'    => 'right',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					]
				],
				'selector' => [
					'{{SELECTOR}}' => 'text-align: {{VALUE}}'
				],
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'    => 'Padding',
				'name'     => 'padding_field',
				'type'     => self::FIELD_SPACING,
				'value'    => [
					[
						'position' => 'Top',
						'value'    => '7'
					],
					[
						'position' => 'Right',
						'value'    => '20'
					],
					[
						'position' => 'Bottom',
						'value'    => '7'
					],
					[
						'position' => 'Left',
						'value'    => '20',
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
					'{{SELECTOR}} a' => [
						'padding-top: {{VALUE_1}}{{UNIT}}',
						'padding-right: {{VALUE_2}}{{UNIT}}',
						'padding-bottom: {{VALUE_3}}{{UNIT}}',
						'padding-left: {{VALUE_4}}{{UNIT}}'
					]
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'    => 'Border radius',
				'name'     => 'border_radius_field',
				'type'     => self::FIELD_BORDER_RADIUS,
				'value'    => [
					[
						'position' => 'Top left',
						'value'    => '4'
					],
					[
						'position' => 'Top right',
						'value'    => '4'
					],
					[
						'position' => 'Bottom right',
						'value'    => '4'
					],
					[
						'position' => 'Bottom left',
						'value'    => '4'
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
					'{{SELECTOR}} a' => 'border-radius: {{VALUE_1}}{{UNIT}} {{VALUE_2}}{{UNIT}} {{VALUE_3}}{{UNIT}} {{VALUE_4}}{{UNIT}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'       => 'Style',
				'name'        => 'btn_normal_separator',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'    => 'Background',
				'name'     => 'bg_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(33, 150, 243)',
				'selector' => [
					'{{SELECTOR}} a' => 'background-color: {{VALUE}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'    => 'Border type',
				'name'     => 'border_type_n_field',
				'type'     => self::FIELD_DROPDOWN,
				'value'    => [
					[
						'label'    => 'None',
						'value'    => '',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Solid',
						'value'    => 'solid',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => [
								'border_width_n_field',
								'border_color_n_field'
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
								'border_width_n_field',
								'border_color_n_field'
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
								'border_width_n_field',
								'border_color_n_field'
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
								'border_width_n_field',
								'border_color_n_field'
							]
						]
					]
				],
				'selector' => [
					'{{SELECTOR}} a' => 'border-style: {{VALUE}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'    => 'Border width',
				'name'     => 'border_width_n_field',
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
					'{{SELECTOR}} a' => 'border-width: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'    => 'Border color',
				'name'     => 'border_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(33, 150, 243)',
				'selector' => [
					'{{SELECTOR}} a' => 'border-color: {{VALUE}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'       => 'Hover style',
				'name'        => 'btn_hover_separator',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'    => 'Background',
				'name'     => 'bg_color_h_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(19, 134, 226)',
				'selector' => [
					'{{SELECTOR}} a:hover' => 'background-color: {{VALUE}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'    => 'Border type',
				'name'     => 'border_type_h_field',
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
								'border_width_h_field',
								'border_color_h_field'
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
								'border_width_h_field',
								'border_color_h_field'
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
								'border_width_h_field',
								'border_color_h_field'
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
								'border_width_h_field',
								'border_color_h_field'
							]
						]
					]
				],
				'selector' => [
					'{{SELECTOR}} a:hover' => 'border-style: {{VALUE}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'      => 'Border width',
				'name'       => 'border_width_h_field',
				'visibility' => false,
				'type'       => self::FIELD_INPUT_NUMBER,
				'value'      => '1',
				'units'      => [
					[
						'type'   => 'px',
						'active' => true
					],
					[
						'type'   => 'em',
						'active' => false
					]
				],
				'selector'   => [
					'{{SELECTOR}} a:hover' => 'border-width: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'      => 'Border color',
				'name'       => 'border_color_h_field',
				'visibility' => false,
				'type'       => self::FIELD_COLOR_PICKER,
				'value'      => '',
				'selector'   => [
					'{{SELECTOR}} a:hover' => 'border-color: {{VALUE}}'
				]
			]
		);

		$fields_4 = [];

		$fields_4[] = new EditorSectionField(
			[
				'label'       => 'Typography',
				'name'        => 'text_typo_separator',
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
					'{{SELECTOR}} a span.item-text' => 'font-family: {{VALUE}}'
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
					'{{SELECTOR}} a span.item-text' => 'font-size: {{VALUE}}{{UNIT}}'
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
					'{{SELECTOR}} a span.item-text' => 'font-weight: {{VALUE}}'
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
					'{{SELECTOR}} a span.item-text' => 'text-transform: {{VALUE}}'
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
					'{{SELECTOR}} a span.item-text' => 'font-style: {{VALUE}}'
				]
			]
		);

		$fields_4[] = new EditorSectionField(
			[
				'label'    => 'Letter spacing',
				'name'     => 'typo_letter_spacing_field',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '1',
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
				'label'       => 'Style',
				'name'        => 'text_normal_separator',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields_4[] = new EditorSectionField(
			[
				'label'    => 'Color',
				'name'     => 'text_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(255, 255, 255)',
				'selector' => [
					'{{SELECTOR}} > a span.item-text' => 'color: {{VALUE}}'
				]
			]
		);

		$fields_4[] = new EditorSectionField(
			[
				'label'    => 'Hover color',
				'name'     => 'text_color_h_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}}:hover > a span.item-text' => 'color: {{VALUE}}'
				]
			]
		);

		$fields_5 = [];

		$fields_5[] = new EditorSectionField(
			[
				'label'    => 'Padding',
				'name'     => 'icon_padding_field',
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
					'{{SELECTOR}} a span.mdi' => [
						'padding-top: {{VALUE_1}}{{UNIT}}',
						'padding-right: {{VALUE_2}}{{UNIT}}',
						'padding-bottom: {{VALUE_3}}{{UNIT}}',
						'padding-left: {{VALUE_4}}{{UNIT}}'
					]
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'    => 'Margin',
				'name'     => 'icon_margin_field',
				'type'     => self::FIELD_SPACING,
				'value'    => [
					[
						'position' => 'Top',
						'value'    => ''
					],
					[
						'position' => 'Right',
						'value'    => '7'
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
					'{{SELECTOR}} a span.mdi' => [
						'margin-top: {{VALUE_1}}{{UNIT}}',
						'margin-right: {{VALUE_2}}{{UNIT}}',
						'margin-bottom: {{VALUE_3}}{{UNIT}}',
						'margin-left: {{VALUE_4}}{{UNIT}}'
					]
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'    => 'Border radius',
				'name'     => 'icon_border_radius_field',
				'type'     => self::FIELD_BORDER_RADIUS,
				'value'    => [
					[
						'position' => 'Top left',
						'value'    => ''
					],
					[
						'position' => 'Top right',
						'value'    => ''
					],
					[
						'position' => 'Bottom right',
						'value'    => ''
					],
					[
						'position' => 'Bottom left',
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
					'{{SELECTOR}} a span.mdi' => 'border-radius: {{VALUE_1}}{{UNIT}} {{VALUE_2}}{{UNIT}} {{VALUE_3}}{{UNIT}} {{VALUE_4}}{{UNIT}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'       => 'Style',
				'name'        => 'icon_normal_separator',
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
				'name'     => 'icon_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(255, 255, 255)',
				'selector' => [
					'{{SELECTOR}} > a span.mdi' => 'color: {{VALUE}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'    => 'Background',
				'name'     => 'icon_bg_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} a span.mdi' => 'background-color: {{VALUE}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'    => 'Border type',
				'name'     => 'icon_border_type_n_field',
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
								'icon_border_width_n_field',
								'icon_border_color_n_field'
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
								'icon_border_width_n_field',
								'icon_border_color_n_field'
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
								'icon_border_width_n_field',
								'icon_border_color_n_field'
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
								'icon_border_width_n_field',
								'icon_border_color_n_field'
							]
						]
					]
				],
				'selector' => [
					'{{SELECTOR}} a span.mdi' => 'border-style: {{VALUE}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'      => 'Border width',
				'name'       => 'icon_border_width_n_field',
				'visibility' => false,
				'type'       => self::FIELD_INPUT_NUMBER,
				'value'      => '1',
				'units'      => [
					[
						'type'   => 'px',
						'active' => true
					],
					[
						'type'   => 'em',
						'active' => false
					]
				],
				'selector'   => [
					'{{SELECTOR}} a span.mdi' => 'border-width: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'      => 'Border color',
				'name'       => 'icon_border_color_n_field',
				'visibility' => false,
				'type'       => self::FIELD_COLOR_PICKER,
				'value'      => '',
				'selector'   => [
					'{{SELECTOR}} a span.mdi' => 'border-color: {{VALUE}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'       => 'Hover style',
				'name'        => 'icon_hover_separator',
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
				'name'     => 'icon_color_h_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}}:hover > a span.mdi' => 'color: {{VALUE}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'    => 'Background',
				'name'     => 'icon_bg_color_h_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}}:hover a span.mdi' => 'background-color: {{VALUE}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'    => 'Border type',
				'name'     => 'icon_border_type_h_field',
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
								'icon_border_width_h_field',
								'icon_border_color_h_field'
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
								'icon_border_width_h_field',
								'icon_border_color_h_field'
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
								'icon_border_width_h_field',
								'icon_border_color_h_field'
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
								'icon_border_width_h_field',
								'icon_border_color_h_field'
							]
						]
					]
				],
				'selector' => [
					'{{SELECTOR}}:hover a span.mdi' => 'border-style: {{VALUE}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'      => 'Border width',
				'name'       => 'icon_border_width_h_field',
				'visibility' => false,
				'type'       => self::FIELD_INPUT_NUMBER,
				'value'      => '1',
				'units'      => [
					[
						'type'   => 'px',
						'active' => true
					],
					[
						'type'   => 'em',
						'active' => false
					]
				],
				'selector'   => [
					'{{SELECTOR}}:hover a span.mdi' => 'border-width: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'      => 'Border color',
				'name'       => 'icon_border_color_h_field',
				'visibility' => false,
				'type'       => self::FIELD_COLOR_PICKER,
				'value'      => '',
				'selector'   => [
					'{{SELECTOR}}:hover a span.mdi' => 'border-color: {{VALUE}}'
				]
			]
		);

		$this->addSection( new EditorSection( [
			'title' => 'Text',
			'name'  => 'text-section'
		] ), $fields_1 );

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'option-section'
		] ), $fields );

		$this->addSection( new EditorSection( [
			'title' => 'Icon',
			'name'  => 'icon-section'
		] ), $fields_2 );

		$this->addSection( new EditorSection( [
			'title' => 'Text',
			'name'  => 'style-text'
		] ), $fields_4, 'tab_style_text' );

		$this->addSection( new EditorSection( [
			'title' => 'Button',
			'name'  => 'style-button'
		] ), $fields_3, 'tab_style_button' );

		$this->addSection( new EditorSection( [
			'title' => 'Icon',
			'name'  => 'style-icon'
		] ), $fields_5, 'tab_style_icon' );

	}
}
