<?php
/**
 * Column component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Column extends ElementSpecs implements ColumnInterface {

	public
		$name,
		$slug,
		$type,
		$parent,
		$uuid,
		$icon,
		$position,
		$template,
		$builder,
		$auth,
		$visibilityDesktop,
		$visibilityTablet,
		$visibilityMobile,
		$customDesktop,
		$customTablet,
		$customMobile,
		$editor,
		$editor_header,
		$editor_section,
		$desktop,
		$tablet,
		$mobile,
		$generalCSS,
		$desktopCSS,
		$tabletCSS,
		$mobileCSS,
		$layers;

	/**
	 * Column constructor.
	 */
	public function __construct() {
		$this->name              = 'Column';
		$this->slug              = 'column';
		$this->type              = 'column';
		$this->parent            = '';
		$this->uuid              = '';
		$this->icon              = (object) [
			'size'  => 'mdi-24px',
			'type'  => 'mdi-tab-unselected',
			'color' => 'sq-columnItem'
		];
		$this->position          = '';
		$this->template          = '';
		$this->template_header   = $this->getTemplate( $this->slug . '-header' );
		$this->template_section  = $this->getTemplate( $this->slug . '-section' );
		$this->builder           = '';
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
		$this->editor_header     = [];
		$this->editor_section    = [];
		$this->desktop           = new \stdClass();
		$this->tablet            = new \stdClass();
		$this->mobile            = new \stdClass();
		$this->generalCSS        = '';
		$this->desktopCSS        = '';
		$this->tabletCSS         = '';
		$this->mobileCSS         = '';
		$this->layers            = false;

		$this->editorHeader();
		$this->editorSection();
		$this->advancedEditor();
	}

	/**
	 * @param EditorSection $section
	 */
	protected function addSectionSettings( $name, EditorSection $section ) {
		if ( ! $name ) {
			$this->editor_section[] = $section;
		} else {
			$this->editor_section[ $name ] = $section;
		}

	}

	/**
	 * @param EditorSection $section
	 */
	protected function addHeaderSettings( $name, EditorSection $section ) {
		if ( ! $name ) {
			$this->editor_header[] = $section;
		} else {
			$this->editor_header[ $name ] = $section;
		}
	}

	/**
	 *
	 */
	private function editorSection() {
		$section = new EditorSection( [
			'title' => 'Layout',
			'name'  => 'default-options'
		] );

		$fields = [];

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Width',
				'name'     => 'width_field',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '',
				'units'    => [
					[
						'type'   => '%',
						'active' => true
					]
				],
				'selector' => [
					'{{SELECTOR}}' => 'max-width: {{VALUE}}{{UNIT}}; flex: 0 0 {{VALUE}}{{UNIT}}',
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label' => 'Content position',
				'name'  => 'content_position_field',
				'type'  => self::FIELD_DROPDOWN,
				'value' => [
					[
						'label'    => 'Inherit',
						'value'    => '',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Top',
						'value'    => 'column-content-top',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Middle',
						'value'    => 'column-content-middle',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => 'Bottom',
						'value'    => 'column-content-bottom',
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

		$this->addSectionSettings( null, $section );
	}

	/**
	 *
	 */
	private function editorHeader() {
		$section = new EditorSection( [
			'title' => 'Layout',
			'name'  => 'default-options'
		] );

		$fields = [];

		$fields[] = new EditorSectionField(
			[
				'label' => 'Shrink Width',
				'name'  => 'shrink_field',
				'type'  => self::FIELD_DROPDOWN,
				'value' => [
					[
						'label'    => 'No',
						'value'    => '',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => [
								'h_align_field'
							]
						]
					],
					[
						'label'    => 'Yes',
						'value'    => 'sq-col-auto',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label' => 'Vertical Align',
				'name'  => 'v_align_field',
				'type'  => self::FIELD_BUTTON_GROUP,
				'value' => [
					[
						'label'    => '<span class="mdi mdi-format-vertical-align-top mdi-18px"></span>',
						'value'    => 'align-items-start',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '<span class="mdi mdi-format-vertical-align-center mdi-18px"></span>',
						'value'    => 'align-items-center',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '<span class="mdi mdi-format-vertical-align-bottom mdi-18px"></span>',
						'value'    => 'align-items-end',
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
				'label' => 'Horizontal Align',
				'name'  => 'h_align_field',
				'type'  => self::FIELD_BUTTON_GROUP,
				'value' => [
					[
						'label'    => '<span class="mdi mdi-format-horizontal-align-left mdi-18px"></span>',
						'value'    => 'justify-content-start',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '<span class="mdi mdi-format-horizontal-align-center mdi-18px"></span>',
						'value'    => 'justify-content-center',
						'selected' => true,
						'trigger'  => [
							'section' => [],
							'field'   => []
						]
					],
					[
						'label'    => '<span class="mdi mdi-format-horizontal-align-right mdi-18px"></span>',
						'value'    => 'justify-content-end',
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

		$this->addHeaderSettings( null, $section );
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
					'{{SELECTOR}}' => 'border-style: {{VALUE}}'
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
						'value'    => '0'
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
						'border-top-width: {{VALUE_1}}{{UNIT}}',
						'border-right-width: {{VALUE_2}}{{UNIT}}',
						'border-bottom-width: {{VALUE_3}}{{UNIT}}',
						'border-left-width: {{VALUE_4}}{{UNIT}}'
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
					'{{SELECTOR}}' => 'border-color: {{VALUE}}'
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

		$this->addHeaderSettings( 'tab_advanced_layout', $section_1 );
		$this->addHeaderSettings( 'tab_advanced_normal_style', $section_2 );
		$this->addHeaderSettings( 'tab_advanced_customization', $section_4 );

		$this->addSectionSettings( 'tab_advanced_layout', $section_1 );
		$this->addSectionSettings( 'tab_advanced_normal_style', $section_2 );
		$this->addSectionSettings( 'tab_advanced_customization', $section_4 );
	}
}
