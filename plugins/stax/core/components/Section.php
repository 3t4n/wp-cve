<?php
/**
 * Section component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0.2
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Section extends ElementSpecs implements ContainerInterface {

	public $name,
		$slug,
		$type,
		$uuid,
		$icon,
		$template,
		$builder,
		$frontend,
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
	 * Header constructor.
	 */
	public function __construct() {
		$this->name              = 'Section';
		$this->slug              = 'section';
		$this->type              = 'section';
		$this->uuid              = '';
		$this->icon              = (object) [
			'size'  => 'mdi-24px',
			'type'  => 'mdi-page-layout-body',
			'color' => 'sq-containerItem'
		];
		$this->template          = $this->getTemplate( $this->slug );
		$this->builder           = '';
		$this->frontend          = (object)
		[
			'desktop' => (object) [
				'auth'     => '',
				'not_auth' => '',
			],
			'tablet'  => (object) [
				'auth'     => '',
				'not_auth' => '',
			],
			'mobile'  => (object) [
				'auth'     => '',
				'not_auth' => '',
			]
		];
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

		$this->editor();
		$this->advancedEditor();
	}

	/**
	 * @param EditorSection $section
	 */
	protected function addSection( EditorSection $section ) {
		$this->editor[] = $section;
	}

	/**
	 *
	 */
	private function editor() {
		$section = new EditorSection( [
			'title' => 'Options',
			'name'  => 'default-options'
		] );

		$fields = [];

		$fields[] = new EditorSectionField(
			[
				'label'   => 'Content width',
				'name'    => 'content_width_field',
				'type'    => self::FIELD_DROPDOWN,
				'value'   => [
					[
						'label'    => 'Full width',
						'value'    => 'section-full-width',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Boxed',
						'value'    => 'section-boxed',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => [
								'width_field'
							]
						]
					]
				],
				'tooltip' => 'Choose from different container widths.',
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'      => 'Width',
				'name'       => 'width_field',
				'visibility' => false,
				'type'       => self::FIELD_INPUT_NUMBER,
				'value'      => '1200',
				'units'      => [
					[
						'type'   => 'px',
						'active' => true
					]
				],
				'selector'   => [
					'{{SELECTOR}} .section-content' => 'max-width: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Columns gap',
				'name'     => 'columns_gap_field',
				'type'     => self::FIELD_DROPDOWN,
				'value'    => [
					[
						'label'    => 'Default',
						'value'    => '10',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'No gap',
						'value'    => '0',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Narrow',
						'value'    => '5',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Extended',
						'value'    => '15',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Wide',
						'value'    => '20',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Wider',
						'value'    => '30',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Custom',
						'value'    => '',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => [
								'custom_gap_field'
							]
						]
					]
				],
				'units'    => [
					[
						'type'   => 'px',
						'active' => true
					]
				],
				'selector' => [
					'{{SELECTOR}} .section-item' => 'padding: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'      => 'Custom gap',
				'name'       => 'custom_gap_field',
				'visibility' => false,
				'type'       => self::FIELD_INPUT_NUMBER,
				'value'      => '10',
				'units'      => [
					[
						'type'   => 'px',
						'active' => true
					]
				],
				'selector'   => [
					'{{SELECTOR}} .section-item' => 'padding: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label' => 'Height',
				'name'  => 'height_field',
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
						'label'    => 'Min height',
						'value'    => '',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => [
								'min_height_field',
								'column_position_field'
							]
						]
					]
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'      => 'Min height',
				'name'       => 'min_height_field',
				'visibility' => false,
				'type'       => self::FIELD_INPUT_NUMBER,
				'value'      => '140',
				'units'      => [
					[
						'type'   => 'px',
						'active' => true
					]
				],
				'selector'   => [
					'{{SELECTOR}} .section-content .sq-row' => 'min-height: {{VALUE}}{{UNIT}}'
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'      => 'Column position',
				'name'       => 'column_position_field',
				'visibility' => false,
				'type'       => self::FIELD_DROPDOWN,
				'value'      => [
					[
						'label'    => 'Full height',
						'value'    => '',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => [
								'column_content_field'
							]
						]
					],
					[
						'label'    => 'Top',
						'value'    => 'section-columns-top',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Middle',
						'value'    => 'section-columns-middle',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Bottom',
						'value'    => 'section-columns-bottom',
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
				'label'      => 'Content position',
				'name'       => 'column_content_field',
				'visibility' => false,
				'type'       => self::FIELD_DROPDOWN,
				'value'      => [
					[
						'label'    => 'Top',
						'value'    => 'section-columns-content-top',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Middle',
						'value'    => 'section-columns-content-middle',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Bottom',
						'value'    => 'section-columns-content-bottom',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					]
				]
			]
		);

		foreach ( $fields as $field ) {
			$section->addField( $field );
		}

		$this->addSection( $section );
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
						'padding-top: {{VALUE_1}}{{UNIT}} !important',
						'padding-right: {{VALUE_2}}{{UNIT}} !important',
						'padding-bottom: {{VALUE_3}}{{UNIT}} !important',
						'padding-left: {{VALUE_4}}{{UNIT}} !important'
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
						'margin-top: {{VALUE_1}}{{UNIT}} !important',
						'margin-right: {{VALUE_2}}{{UNIT}} !important',
						'margin-bottom: {{VALUE_3}}{{UNIT}} !important',
						'margin-left: {{VALUE_4}}{{UNIT}} !important'
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
			'title' => 'Style',
			'name'  => 'normal-style-advanced'
		] );

		$fields_2 = [];

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
					'{{SELECTOR}}' => 'border-style: {{VALUE}} !important'
				]
			]
		);

		$fields_2[] = new EditorSectionField(
			[
				'label'      => 'Border width',
				'name'       => self::ADVANCED_BORDER_WIDTH_NORMAL,
				'visibility' => false,
				'type'       => self::FIELD_SPACING,
				'value'      => [
					[
						'position' => 'Top',
						'value'    => '0'
					],
					[
						'position' => 'Right',
						'value'    => '0'
					],
					[
						'position' => 'Bottom',
						'value'    => '1'
					],
					[
						'position' => 'Left',
						'value'    => '0'
					]
				],
				'units'      => [
					[
						'type'   => 'px',
						'active' => true
					]
				],
				'selector'   => [
					'{{SELECTOR}}' => [
						'border-top-width: {{VALUE_1}}{{UNIT}} !important',
						'border-right-width: {{VALUE_2}}{{UNIT}} !important',
						'border-bottom-width: {{VALUE_3}}{{UNIT}} !important',
						'border-left-width: {{VALUE_4}}{{UNIT}} !important'
					]
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
					'{{SELECTOR}}' => 'border-color: {{VALUE}} !important'
				]
			]
		);

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

		foreach ( $fields_4 as $field ) {
			$section_4->addField( $field );
		}

		$this->editor['tab_advanced_layout']        = $section_1;
		$this->editor['tab_advanced_normal_style']  = $section_2;
		$this->editor['tab_advanced_customization'] = $section_4;
	}
}
