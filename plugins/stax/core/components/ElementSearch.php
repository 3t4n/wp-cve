<?php
/**
 * Search component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ElementSearch extends Element implements ElementInterface {
	/**
	 * ElementSearch constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->name       = 'Search';
		$this->slug       = 'search';
		$this->icon->type = 'mdi-magnify';
		$this->template   = $this->getTemplate( $this->slug );

		$fields_2 = [];

		$fields_2[] = new EditorSectionField(
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

		$fields_2[] = new EditorSectionField(
			[
				'label'       => 'Typography',
				'name'        => 'typo_input_separator',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'    => 'Font family',
				'name'     => 'font_family_field',
				'type'     => self::FIELD_FONT_FAMILY,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} input' => 'font-family: {{VALUE}}'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'    => 'Font size',
				'name'     => 'font_size_field',
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
					'{{SELECTOR}} input' => 'font-size: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'    => 'Font weight',
				'name'     => 'font_weight_field',
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
					'{{SELECTOR}} input' => 'font-weight: {{VALUE}}'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'    => 'Text transform',
				'name'     => 'font_transform_field',
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
					'{{SELECTOR}} input' => 'text-transform: {{VALUE}}'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'    => 'Text style',
				'name'     => 'font_style_field',
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
					'{{SELECTOR}} input' => 'font-style: {{VALUE}}'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'    => 'Letter spacing',
				'name'     => 'font_letter_spacing_field',
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
					'{{SELECTOR}} input' => 'letter-spacing: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'       => 'Layout',
				'name'        => 'input_layout_separator',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'    => 'Min-width',
				'name'     => 'min_width_field',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '300',
				'units'    => [
					[
						'type'   => 'px',
						'active' => true
					],
					[
						'type'   => '%',
						'active' => false
					]
				],
				'selector' => [
					'{{SELECTOR}} input' => 'min-width: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'    => 'Padding',
				'name'     => 'padding_field',
				'type'     => self::FIELD_SPACING,
				'value'    => [
					[
						'position' => 'Top',
						'value'    => '8',
					],
					[
						'position' => 'Right',
						'value'    => '4',
					],
					[
						'position' => 'Bottom',
						'value'    => '8',
					],
					[
						'position' => 'Left',
						'value'    => '4',
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
					'{{SELECTOR}} input' => [
						'padding-top: {{VALUE_1}}{{UNIT}}',
						'padding-right: {{VALUE_2}}{{UNIT}}',
						'padding-bottom: {{VALUE_3}}{{UNIT}}',
						'padding-left: {{VALUE_4}}{{UNIT}}',
					],
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'    => 'Border radius',
				'name'     => 'border_radius_field',
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
					'{{SELECTOR}} input' => 'border-radius: {{VALUE_1}}{{UNIT}} {{VALUE_2}}{{UNIT}} {{VALUE_3}}{{UNIT}} {{VALUE_4}}{{UNIT}}'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'       => 'Style',
				'name'        => 'input_style_separator',
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
				'name'     => 'color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(33, 150, 243)',
				'selector' => [
					'{{SELECTOR}} input' => 'color: {{VALUE}}'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'    => 'Background',
				'name'     => 'bg_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(255, 255, 255)',
				'selector' => [
					'{{SELECTOR}}.sq-search input' => 'background-color: {{VALUE}}'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'    => 'Shadow',
				'name'     => 'shadow_n_field',
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
					'{{SELECTOR}}.sq-search input' => [
						'-webkit-box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}',
						'-moz-box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}',
						'box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}'
					]
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'       => 'Focus style',
				'name'        => 'input_focus_separator',
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
				'name'     => 'color_f_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(33, 150, 243)',
				'selector' => [
					'.header-section {{SELECTOR}} input:focus' => 'color: {{VALUE}}'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'    => 'Background',
				'name'     => 'bg_color_f_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(255, 255, 255)',
				'selector' => [
					'{{SELECTOR}} input:focus' => 'background-color: {{VALUE}}'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'    => 'Shadow',
				'name'     => 'shadow_f_field',
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
					'{{SELECTOR}} input:focus' => [
						'-webkit-box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}',
						'-moz-box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}',
						'box-shadow: {{X}}{{UNIT}} {{Y}}{{UNIT}} {{BLUR}}{{UNIT}} {{SPREAD}}{{UNIT}} {{COLOR}}'
					]
				]
			]
		);

		$fields_4 = [];

		$fields_4[] = new EditorSectionField(
			[
				'label' => 'Text',
				'name'  => 'placeholder_text_field',
				'type'  => self::FIELD_INPUT_TEXT,
				'value' => 'Search for...'
			]
		);

		$fields_5 = [];

		$fields_5[] = new EditorSectionField(
			[
				'label'    => 'Show',
				'name'     => 'label_field',
				'type'     => self::FIELD_SWITCH,
				'value'    => [
					[
						'label'   => '',
						'value'   => 'show',
						'checked' => true,
						'trigger' => [
							'section' => [ 'style-label' ],
							'field'   => []
						]
					]
				],
				'selector' => [
					'{{SELECTOR}} label' => 'display: none !important'
				]
			]
		);

		$fields_6 = [];

		$fields_6[] = new EditorSectionField(
			[
				'label' => 'Size',
				'name'  => 'icon_size_field',
				'type'  => self::FIELD_DROPDOWN,
				'value' => [
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

		$fields_6[] = new EditorSectionField(
			[
				'label'    => 'Padding',
				'name'     => 'icon_padding',
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
					'{{SELECTOR}} .mdi' => [
						'padding-top: {{VALUE_1}}{{UNIT}}',
						'padding-right: {{VALUE_2}}{{UNIT}}',
						'padding-bottom: {{VALUE_3}}{{UNIT}}',
						'padding-left: {{VALUE_4}}{{UNIT}}',
					],
				]
			]
		);
		$fields_6[] = new EditorSectionField(
			[
				'label'    => 'Margin',
				'name'     => 'icon_margin',
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
					'{{SELECTOR}} .mdi' => [
						'margin-top: {{VALUE_1}}{{UNIT}}',
						'margin-right: {{VALUE_2}}{{UNIT}}',
						'margin-bottom: {{VALUE_3}}{{UNIT}}',
						'margin-left: {{VALUE_4}}{{UNIT}}',
					],
				]
			]
		);

		$fields_6[] = new EditorSectionField(
			[
				'label'    => 'Border radius',
				'name'     => 'icon_border_radius',
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
					'{{SELECTOR}} .mdi' => 'border-radius: {{VALUE_1}}{{UNIT}} {{VALUE_2}}{{UNIT}} {{VALUE_3}}{{UNIT}} {{VALUE_4}}{{UNIT}}'
				]
			]
		);


		$fields_6[] = new EditorSectionField(
			[
				'label'    => 'Color',
				'name'     => 'icon_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(33, 150, 243)',
				'selector' => [
					'{{SELECTOR}} span.mdi' => 'color: {{VALUE}}'
				]
			]
		);
		$fields_6[] = new EditorSectionField(
			[
				'label'    => 'Background',
				'name'     => 'icon_bg',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}}.sq-search span.mdi' => 'background-color: {{VALUE}}'
				]
			]
		);

		$fields_6[] = new EditorSectionField(
			[
				'label'       => 'Focus style',
				'name'        => 'icon_focus_style_separator',
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
				'name'     => 'icon_color_f_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(33, 150, 243)',
				'selector' => [
					'{{SELECTOR}} .floating-placeholder.is-focused span.mdi' => 'color: {{VALUE}}'
				]
			]
		);
		$fields_6[] = new EditorSectionField(
			[
				'label'    => 'Background',
				'name'     => 'icon_bg_focus',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} .floating-placeholder.is-focused span.mdi' => 'background-color: {{VALUE}}'
				]
			]
		);

		$fields_7 = [];

		$fields_7[] = new EditorSectionField(
			[
				'label'       => 'Typography',
				'name'        => 'placeholder_typo_separator',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields_7[] = new EditorSectionField(
			[
				'label'    => 'Font family',
				'name'     => 'text_font_family_field',
				'type'     => self::FIELD_FONT_FAMILY,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} input::-webkit-input-placeholder' => 'font-family: {{VALUE}}',
					'{{SELECTOR}} input::-moz-placeholder'          => 'font-family: {{VALUE}}',
					'{{SELECTOR}} input:-ms-input-placeholder'      => 'font-family: {{VALUE}}',
					'{{SELECTOR}} input:-moz-placeholder'           => 'font-family: {{VALUE}}'
				]
			]
		);

		$fields_7[] = new EditorSectionField(
			[
				'label'    => 'Font size',
				'name'     => 'text_font_size_field',
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
					'{{SELECTOR}} input::-webkit-input-placeholder' => 'font-size: {{VALUE}}{{UNIT}}',
					'{{SELECTOR}} input::-moz-placeholder'          => 'font-size: {{VALUE}}{{UNIT}}',
					'{{SELECTOR}} input:-ms-input-placeholder'      => 'font-size: {{VALUE}}{{UNIT}}',
					'{{SELECTOR}} input:-moz-placeholder'           => 'font-size: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_7[] = new EditorSectionField(
			[
				'label'    => 'Font weight',
				'name'     => 'text_font_weight_field',
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
					'{{SELECTOR}} input::-webkit-input-placeholder' => 'font-weight: {{VALUE}}',
					'{{SELECTOR}} input::-moz-placeholder'          => 'font-weight: {{VALUE}}',
					'{{SELECTOR}} input:-ms-input-placeholder'      => 'font-weight: {{VALUE}}',
					'{{SELECTOR}} input:-moz-placeholder'           => 'font-weight: {{VALUE}}'
				]
			]
		);

		$fields_7[] = new EditorSectionField(
			[
				'label'    => 'Text transform',
				'name'     => 'text_font_transform_field',
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
					'{{SELECTOR}} input::-webkit-input-placeholder' => 'text-transform: {{VALUE}}',
					'{{SELECTOR}} input::-moz-placeholder'          => 'text-transform: {{VALUE}}',
					'{{SELECTOR}} input:-ms-input-placeholder'      => 'text-transform: {{VALUE}}',
					'{{SELECTOR}} input:-moz-placeholder'           => 'text-transform: {{VALUE}}'
				]
			]
		);

		$fields_7[] = new EditorSectionField(
			[
				'label'    => 'Text style',
				'name'     => 'text_font_type_field',
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
					'{{SELECTOR}} input::-webkit-input-placeholder' => 'font-style: {{VALUE}}',
					'{{SELECTOR}} input::-moz-placeholder'          => 'font-style: {{VALUE}}',
					'{{SELECTOR}} input:-ms-input-placeholder'      => 'font-style: {{VALUE}}',
					'{{SELECTOR}} input:-moz-placeholder'           => 'font-style: {{VALUE}}'
				]
			]
		);

		$fields_7[] = new EditorSectionField(
			[
				'label'    => 'Letter spacing',
				'name'     => 'text_letter_spacing_field',
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
					'{{SELECTOR}} input::-webkit-input-placeholder' => 'letter-spacing: {{VALUE}}{{UNIT}}',
					'{{SELECTOR}} input::-moz-placeholder'          => 'letter-spacing: {{VALUE}}{{UNIT}}',
					'{{SELECTOR}} input:-ms-input-placeholder'      => 'letter-spacing: {{VALUE}}{{UNIT}}',
					'{{SELECTOR}} input:-moz-placeholder'           => 'letter-spacing: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_7[] = new EditorSectionField(
			[
				'label'       => 'Style',
				'name'        => 'style_placeholder_separator',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields_7[] = new EditorSectionField(
			[
				'label'    => 'Color',
				'name'     => 'placeholder_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} input::-webkit-input-placeholder' => 'color: {{VALUE}}',
					'{{SELECTOR}} input::-moz-placeholder'          => 'color: {{VALUE}}',
					'{{SELECTOR}} input:-ms-input-placeholder'      => 'color: {{VALUE}}',
					'{{SELECTOR}} input:-moz-placeholder'           => 'color: {{VALUE}}'
				]
			]
		);

		$fields_7[] = new EditorSectionField(
			[
				'label'       => 'Focus style',
				'name'        => 'style_focus_placeholder_separator',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields_7[] = new EditorSectionField(
			[
				'label'    => 'Color',
				'name'     => 'placeholder_color_f_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} input:focus::-webkit-input-placeholder' => 'color: {{VALUE}}',
					'{{SELECTOR}} input:focus::-moz-placeholder'          => 'color: {{VALUE}}',
					'{{SELECTOR}} input:focus:-ms-input-placeholder'      => 'color: {{VALUE}}',
					'{{SELECTOR}} input:focus:-moz-placeholder'           => 'color: {{VALUE}}'
				]
			]
		);

		$fields_8 = [];

		$fields_8[] = new EditorSectionField(
			[
				'label'    => 'Color',
				'name'     => 'label_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} label' => 'color: {{VALUE}}'
				]
			]
		);

		$fields_9 = [];

		$fields_9[] = new EditorSectionField(
			[
				'label'      => 'Form action',
				'name'       => 'form_action',
				'type'       => self::FIELD_INPUT_TEXT,
				'value'      => get_home_url(),
			]
		);

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'general-section',
		] ), $fields_9 );

		$this->addSection( new EditorSection( [
			'title' => 'Placeholder',
			'name'  => 'placeholder-section',
			'state' => true
		] ), $fields_4 );


		$this->addSection( new EditorSection( [
			'title' => 'Label',
			'name'  => 'label-section',
			'state' => true
		] ), $fields_5 );

		$this->addSection( new EditorSection( [
			'title' => 'Input',
			'name'  => 'style-input'
		] ), $fields_2, 'tab_style_input' );

		$this->addSection( new EditorSection( [
			'title' => 'Icon',
			'name'  => 'style-icon'
		] ), $fields_6, 'tab_style_icon' );

		$this->addSection( new EditorSection( [
			'title' => 'Placeholder',
			'name'  => 'style-placeholder'
		] ), $fields_7, 'tab_style_placeholder' );


		$this->addSection( new EditorSection( [
			'title' => 'Label',
			'name'  => 'style-label'
		] ), $fields_8, 'tab_style_label' );

		$this->setBoxDefaults( [
			self::ADVANCED_PADDING => [
				'value' => [ 0, 0, 0, 8 ]
			]
		] );

	}
}
