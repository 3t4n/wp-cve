<?php
/**
 * Accordion component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ElementAccordion extends Element implements ElementInterface {
	/**
	 * ElementAccordion constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->name       = 'Accordion';
		$this->slug       = 'accordion';
		$this->icon->type = 'mdi-cards-variant';
		$this->template   = $this->getTemplate( $this->slug );

		$fields = [];

		$fields[] = new EditorSectionField(
			[
				'label' => 'Accordion items',
				'name'  => 'tabs_field',
				'type'  => self::FIELD_VERTICAL_TABS,
				'value' => [
					[
						'name'   => 'Accordion 1',
						'value'  => 'Sample text',
						'active' => true
					],
					[
						'name'   => 'Accordion 2',
						'value'  => 'Sample text',
						'active' => false
					]
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'       => 'Icons',
				'name'        => 'icons_layout_separator',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label' => 'Icon minimized',
				'name'  => 'icon_minimized_field',
				'type'  => self::FIELD_ICON,
				'value' => 'mdi-minus'
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label' => 'Icon maximized',
				'name'  => 'icon_maximized_field',
				'type'  => self::FIELD_ICON,
				'value' => 'mdi-plus'
			]
		);

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
				'label'    => 'Border width',
				'name'     => 'border_width_n_field',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '1',
				'units'    => [
					[
						'type'   => 'px',
						'active' => true
					]
				],
				'selector' => [
					'{{SELECTOR}}'                                => 'border-width: {{VALUE}}{{UNIT}}',
					'{{SELECTOR}} .sq-toggle-acc'                 => 'border-bottom-width: {{VALUE}}{{UNIT}}',
					'{{SELECTOR}} .sq-inner-acc:not(:last-child)' => 'border-bottom-width: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'    => 'Border color',
				'name'     => 'border_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => 'rgb(229, 229, 229)',
				'selector' => [
					'{{SELECTOR}}'                                => 'border-color: {{VALUE}}',
					'{{SELECTOR}} .sq-toggle-acc'                 => 'border-color: {{VALUE}}',
					'{{SELECTOR}} .sq-inner-acc:not(:last-child)' => 'border-color: {{VALUE}}'
				]
			]
		);

		$fields_3 = [];

		$fields_3[] = new EditorSectionField(
			[
				'label'    => 'Background',
				'name'     => 'title_bg_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} .sq-toggle-acc' => 'background-color: {{VALUE}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'    => 'Color',
				'name'     => 'title_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} .sq-toggle-acc' => 'color: {{VALUE}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'    => 'Active color',
				'name'     => 'title_color_a_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} .sq-toggle-acc.active' => 'color: {{VALUE}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label' => 'Typography',
				'name'  => 'title_typography_switch_field',
				'type'  => self::FIELD_SWITCH,
				'value' => [
					[
						'label'   => '',
						'value'   => 'show',
						'checked' => false,
						'trigger' => [
							'section' => [],
							'field'   => [
								'title_font_family_field',
								'title_font_size_field',
								'title_font_weight_field',
								'title_font_transform_field',
								'title_font_type_field',
								'title_letter_spacing_field'
							]
						]
					]
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'      => 'Font family',
				'name'       => 'title_font_family_field',
				'visibility' => false,
				'type'       => self::FIELD_FONT_FAMILY,
				'value'      => '',
				'selector'   => [
					'{{SELECTOR}} .sq-toggle-acc' => 'font-family: {{VALUE}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'      => 'Font size',
				'name'       => 'title_font_size_field',
				'visibility' => false,
				'type'       => self::FIELD_INPUT_NUMBER,
				'value'      => '14',
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
					'{{SELECTOR}} .sq-toggle-acc' => 'font-size: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'      => 'Font weight',
				'name'       => 'title_font_weight_field',
				'visibility' => false,
				'type'       => self::FIELD_DROPDOWN,
				'value'      => [
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
				'selector'   => [
					'{{SELECTOR}} .sq-toggle-acc' => 'font-weight: {{VALUE}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'      => 'Text transform',
				'name'       => 'title_font_transform_field',
				'visibility' => false,
				'type'       => self::FIELD_DROPDOWN,
				'value'      => [
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
				'selector'   => [
					'{{SELECTOR}} .sq-toggle-acc' => 'text-transform: {{VALUE}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'      => 'Text style',
				'name'       => 'title_font_type_field',
				'visibility' => false,
				'type'       => self::FIELD_DROPDOWN,
				'value'      => [
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
				'selector'   => [
					'{{SELECTOR}} .sq-toggle-acc' => 'font-style: {{VALUE}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'      => 'Letter spacing',
				'name'       => 'title_letter_spacing_field',
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
					'{{SELECTOR}} .sq-toggle-acc' => 'letter-spacing: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_3[] = new EditorSectionField(
			[
				'label'    => 'Padding',
				'name'     => 'title_padding_field',
				'type'     => self::FIELD_SPACING,
				'value'    => [
					[
						'position' => 'Top',
						'value'    => '10'
					],
					[
						'position' => 'Right',
						'value'    => '10'
					],
					[
						'position' => 'Bottom',
						'value'    => '10'
					],
					[
						'position' => 'Left',
						'value'    => '10',
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
					'{{SELECTOR}} .sq-toggle-acc' => [
						'padding-top: {{VALUE_1}}{{UNIT}}',
						'padding-right: {{VALUE_2}}{{UNIT}}',
						'padding-bottom: {{VALUE_3}}{{UNIT}}',
						'padding-left: {{VALUE_4}}{{UNIT}}'
					]
				]
			]
		);

		$fields_4 = [];

		$fields_4[] = new EditorSectionField(
			[
				'label'    => 'Color',
				'name'     => 'icon_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} .sq-toggle-acc .mdi' => 'color: {{VALUE}}',
				]
			]
		);

		$fields_4[] = new EditorSectionField(
			[
				'label'    => 'Active color',
				'name'     => 'icon_color_a_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} .sq-toggle-acc.active .mdi' => 'color: {{VALUE}}',
				]
			]
		);

		$fields_5 = [];

		$fields_5[] = new EditorSectionField(
			[
				'label'    => 'Background',
				'name'     => 'content_bg_colo_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} .sq-inner-acc' => 'background-color: {{VALUE}}',
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'    => 'Color',
				'name'     => 'content_color_n_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} .sq-inner-acc' => 'color: {{VALUE}}',
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label' => 'Typography',
				'name'  => 'content_typography_switch_field',
				'type'  => self::FIELD_SWITCH,
				'value' => [
					[
						'label'   => '',
						'value'   => 'show',
						'checked' => false,
						'trigger' => [
							'section' => [],
							'field'   => [
								'content_font_family_field',
								'content_font_size_field',
								'content_font_weight_field',
								'content_font_transform_field',
								'content_font_type_field',
								'content_letter_spacing_field'
							]
						]
					]
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'      => 'Font family',
				'name'       => 'content_font_family_field',
				'visibility' => false,
				'type'       => self::FIELD_FONT_FAMILY,
				'value'      => '',
				'selector'   => [
					'{{SELECTOR}} .sq-inner-acc' => 'font-family: {{VALUE}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'      => 'Font size',
				'name'       => 'content_font_size_field',
				'visibility' => false,
				'type'       => self::FIELD_INPUT_NUMBER,
				'value'      => '14',
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
					'{{SELECTOR}} .sq-inner-acc' => 'font-size: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'      => 'Font weight',
				'name'       => 'content_font_weight_field',
				'visibility' => false,
				'type'       => self::FIELD_DROPDOWN,
				'value'      => [
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
				'selector'   => [
					'{{SELECTOR}} .sq-inner-acc' => 'font-weight: {{VALUE}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'      => 'Text transform',
				'name'       => 'content_font_transform_field',
				'visibility' => false,
				'type'       => self::FIELD_DROPDOWN,
				'value'      => [
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
				'selector'   => [
					'{{SELECTOR}} .sq-inner-acc' => 'text-transform: {{VALUE}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'      => 'Text style',
				'name'       => 'content_font_type_field',
				'visibility' => false,
				'type'       => self::FIELD_DROPDOWN,
				'value'      => [
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
				'selector'   => [
					'{{SELECTOR}} .sq-inner-acc' => 'font-style: {{VALUE}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'      => 'Letter spacing',
				'name'       => 'content_letter_spacing_field',
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
					'{{SELECTOR}} .sq-inner-acc' => 'letter-spacing: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_5[] = new EditorSectionField(
			[
				'label'    => 'Padding',
				'name'     => 'content_padding_field',
				'type'     => self::FIELD_SPACING,
				'value'    => [
					[
						'position' => 'Top',
						'value'    => '10'
					],
					[
						'position' => 'Right',
						'value'    => '10'
					],
					[
						'position' => 'Bottom',
						'value'    => '10'
					],
					[
						'position' => 'Left',
						'value'    => '10',
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
					'{{SELECTOR}} .sq-inner-acc' => [
						'padding-top: {{VALUE_1}}{{UNIT}}',
						'padding-right: {{VALUE_2}}{{UNIT}}',
						'padding-bottom: {{VALUE_3}}{{UNIT}}',
						'padding-left: {{VALUE_4}}{{UNIT}}'
					]
				]
			]
		);

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'option-section'
		] ), $fields );

		$this->addSection( new EditorSection( [
			'title' => 'Accordion',
			'name'  => 'style-accordion'
		] ), $fields_2, 'tab_style_accordion' );

		$this->addSection( new EditorSection( [
			'title' => 'Title bar',
			'name'  => 'style-title-bar'
		] ), $fields_3, 'tab_style_title_bar' );

		$this->addSection( new EditorSection( [
			'title' => 'Icon',
			'name'  => 'style-icon'
		] ), $fields_4, 'tab_style_icon' );

		$this->addSection( new EditorSection( [
			'title' => 'Content',
			'name'  => 'style-content'
		] ), $fields_5, 'tab_style_content' );

	}
}
