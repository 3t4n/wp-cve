<?php
/**
 * Google Maps component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ElementGoogleMaps extends Element implements ElementInterface {
	/**
	 * ElementGoogleMaps constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->name       = 'Google Map';
		$this->slug       = 'maps';
		$this->icon->type = 'mdi-google-maps';
		$this->template   = $this->getTemplate( $this->slug );

		$fields = [];

		$fields[] = new EditorSectionField(
			[
				'label' => 'Address',
				'name'  => 'address_field',
				'type'  => self::FIELD_INPUT_TEXT,
				'value' => 'Romania',
			]
		);

		$fields[] = new EditorSectionField(
			[
				'label' => 'Zoom level',
				'name'  => 'zoom_field',
				'type'  => self::FIELD_INPUT_NUMBER,
				'value' => '10'
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

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'option-section'
		] ), $fields );

	}
}
