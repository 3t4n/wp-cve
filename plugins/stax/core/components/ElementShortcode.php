<?php
/**
 * Shortcode component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class ElementShortcode extends Element implements ElementInterface {
	/**
	 * ElementShortcode constructor.
	 */
	public function __construct() {
		parent::__construct();
		$this->name       = 'Shortcode';
		$this->slug       = 'shortcode';
		$this->icon->type = 'mdi-code-braces';
		$this->template   = $this->getTemplate( $this->slug );

		$fields = [];

		$fields[] = new EditorSectionField(
			[
				'label' => 'Shortcode',
				'name'  => 'shortcode_field',
				'type'  => self::FIELD_TEXT_AREA,
				'value' => '[stax-demo-shortcode]'
			]
		);

		$this->addSection( new EditorSection( [
			'title' => $this->name,
			'name'  => 'option-section'
		] ), $fields );

		$this->setBoxDefaults( [
			self::ADVANCED_MARGIN => [
				'value' => [ 0, 2, 0, 2 ]
			]
		] );
	}
}
