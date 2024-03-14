<?php

/**
 * Glossary
 *
 * @package   Glossary
 * @author    Codeat <support@codeat.co>
 * @copyright 2020
 * @license   GPL 3.0+
 * @link      https://codeat.co
 */

namespace Glossary\Integrations\CMB_Fields;

use Glossary\Engine;

/**
 * CMB2 Number text field
 */
class CMB2_Number extends Engine\Base {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		parent::initialize();

		\add_action( 'cmb2_render_text_number', array( $this, 'render' ), 10, 5 );
		\add_filter( 'cmb2_sanitize_text_number', array( $this, 'sanitize' ), 10, 2 );
	}

	public function sanitize( $null, $new ) {
		$new = \preg_replace( '/[^0-9]/', '', $new );

		return $new;
	}

	public function render( $field, $escaped_value, $object_id, $object_type, $field_type_object ) {
		echo $field_type_object->input(// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		array(
			'class' => 'cmb2-text-small',
			'type'  => 'number',
		)
		);
	}

}
