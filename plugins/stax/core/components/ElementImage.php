<?php
/**
 * Image component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ElementImage extends Element implements ElementInterface {
	/**
	 * ElementImage constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->name       = 'Image';
		$this->slug       = 'image';
		$this->icon->type = 'mdi-image';
		$this->template   = $this->getTemplate( $this->slug );

		$fields = [];

		$fields[] = new EditorSectionField(
			[
				'label' => 'Image',
				'name'  => 'image_source_field',
				'type'  => self::FIELD_IMAGE,
				'value' => STAX_ASSETS_URL . 'images/choose_your_image.png'
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label' => 'Caption',
				'name'  => 'image_alt_field',
				'type'  => self::FIELD_INPUT_TEXT,
				'value' => ''
			]
		);

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
							'field'   => [],
						]
					],
					[
						'label'    => 'Blank',
						'value'    => '_blank',
						'selected' => false,
						'trigger'  => [
							'section' => [],
							'field'   => [],
						]
					]
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'       => 'Image on default header',
				'name'        => 'image_normal_separator',
				'only'        => 'header',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Image height',
				'name'     => 'image_height_n_field',
				'only'     => 'header',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '',
				'units'    => [
					[
						'type'   => 'px',
						'active' => true,
					],
				],
				'selector' => [
					'.header-section {{SELECTOR}} img, .hb-section {{SELECTOR}} img' => [
						'height: {{VALUE}}{{UNIT}}',
						'max-height: initial !important'
					],
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'       => 'Image on resized header',
				'name'        => 'image_resize_separator',
				'only'        => 'header',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Image height',
				'name'     => 'image_resize_height_field',
				'only'     => 'header',
				'type'     => self::FIELD_INPUT_NUMBER,
				'value'    => '',
				'units'    => [
					[
						'type'   => 'px',
						'active' => true,
					],
				],
				'selector' => [
					'.header-section.is-sticky.is-resized {{SELECTOR}} img' => [
						'height: {{VALUE}}{{UNIT}}',
						'max-height: initial !important'
					],
				]
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label'       => 'Style',
				'name'        => 'image_style_separator',
				'type'        => self::FIELD_SEPARATOR,
				'value'       => '',
				'editorClass' => [
					'padding-m'
				]
			]
		);

		$fields[] = new EditorSectionField(
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

		$fields[] = new EditorSectionField(
			[
				'label'    => 'Border radius',
				'name'     => 'image_border_radius',
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
					]
				],
				'selector' => [
					'{{SELECTOR}} .item-image' => 'border-radius: {{VALUE_1}}{{UNIT}} {{VALUE_2}}{{UNIT}} {{VALUE_3}}{{UNIT}} {{VALUE_4}}{{UNIT}}',
				]
			]
		);

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'option-section',
		] ), $fields );

		$this->setBoxDefaults( [
			self::ADVANCED_FULL_HEIGHT => [
				'value' => 'is-full-height'
			]
		] );

	}
}
