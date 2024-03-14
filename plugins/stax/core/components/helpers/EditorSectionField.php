<?php
/**
 * EditorSectionField.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class EditorSectionField extends ElementSpecs {

	public
		$id,
		$name,
		$visibility,
		$label,
		$only,
		$type,
		$selector,
		$value,
		$cachedValue,
		$units,
		$tooltip,
		$editorClass;

	/**
	 * EditorSectionField constructor.
	 *
	 * @param array $property
	 */
	public function __construct( array $property ) {
		$this->id          = substr( number_format( time() * rand(), 0, '', '' ), 0, 6 );
		$this->label       = $property['label'];
		$this->only        = isset( $property['only'] ) ? $property['only'] : '';
		$this->name        = $property['name'];
		$this->visibility  = isset( $property['visibility'] ) ? $property['visibility'] : true;
		$this->type        = $property['type'];
		$this->value       = $property['value'];
		$this->cachedValue = '';
		$this->selector    = isset( $property['selector'] ) ? $property['selector'] : [];
		$this->units       = isset( $property['units'] ) ? $property['units'] : [];
		$this->tooltip     = isset( $property['tooltip'] ) ? $property['tooltip'] : '';
		$this->editorClass = isset( $property['editorClass'] ) ? $property['editorClass'] : [];
	}
}
