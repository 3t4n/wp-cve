<?php
/**
 * Heading component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ElementHeading extends Element implements ElementInterface {
	/**
	 * ElementHeading constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->name       = 'Heading';
		$this->slug       = 'heading';
		$this->icon->type = 'mdi-format-header-1';
		$this->template   = $this->getTemplate( $this->slug );

		$fields = [];

		$fields[] = new EditorSectionField(
			[
				'label' => 'Text',
				'name'  => 'text_field',
				'type'  => self::FIELD_INPUT_TEXT,
				'value' => 'Your title here'
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label' => 'Link',
				'name'  => 'href_field',
				'type'  => self::FIELD_INPUT_URL,
				'value' => ''
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label' => 'Link target',
				'name'  => 'href_target_field',
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

		$fields[] = new EditorSectionField(
			[
				'label' => 'HTML Tag',
				'name'  => 'tag_field',
				'type'  => self::FIELD_DROPDOWN,
				'value' => [
					[
						'label'    => 'H1',
						'value'    => 'h1',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'H2',
						'value'    => 'h2',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'H3',
						'value'    => 'h3',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'H4',
						'value'    => 'h4',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'H5',
						'value'    => 'h5',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'H6',
						'value'    => 'h6',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					]
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Alignment',
				'name'     => 'align_field',
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
					],
					[
						'label'    => '<span class="mdi mdi-format-align-justify mdi-18px"></span>',
						'value'    => 'justify',
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

		$fields_1 = [];

		$fields_1[] = new EditorSectionField(
			[
				'label'    => 'Color',
				'name'     => 'title_color_field',
				'type'     => self::FIELD_COLOR_PICKER,
				'value'    => '',
				'selector' => [
					'{{SELECTOR}} .heading-title' => 'color: {{VALUE}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
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
								'title_letter_spacing_field',
								'title_line_height_field'
							]
						]
					]
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'      => 'Font family',
				'name'       => 'title_font_family_field',
				'visibility' => false,
				'type'       => self::FIELD_FONT_FAMILY,
				'value'      => '',
				'selector'   => [
					'{{SELECTOR}} .heading-title' => 'font-family: {{VALUE}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'      => 'Font size',
				'name'       => 'title_font_size_field',
				'visibility' => false,
				'type'       => self::FIELD_INPUT_NUMBER,
				'value'      => '24',
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
					'{{SELECTOR}} .heading-title' => 'font-size: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
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
						'selected' => true,
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
					'{{SELECTOR}} .heading-title' => 'font-weight: {{VALUE}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
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
					'{{SELECTOR}} .heading-title' => 'text-transform: {{VALUE}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
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
					'{{SELECTOR}} .heading-title' => 'font-style: {{VALUE}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'      => 'Letter spacing',
				'name'       => 'title_letter_spacing_field',
				'visibility' => false,
				'type'       => self::FIELD_INPUT_NUMBER,
				'value'      => '0',
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
					'{{SELECTOR}} .heading-title' => 'letter-spacing: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields_1[] = new EditorSectionField(
			[
				'label'      => 'Line height',
				'name'       => 'title_line_height_field',
				'visibility' => false,
				'type'       => self::FIELD_INPUT_NUMBER,
				'value'      => '36',
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
					'{{SELECTOR}} .heading-title' => 'line-height: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'option-section'
		] ), $fields );

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'style-title'
		] ), $fields_1, 'tab_style_title' );
	}
}
