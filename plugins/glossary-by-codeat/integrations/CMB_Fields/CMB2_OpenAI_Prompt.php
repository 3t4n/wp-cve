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
class CMB2_OpenAI_Prompt extends Engine\Base {

	/**
	 * Initialize the class.
	 *
	 * @return void
	 */
	public function initialize() {
		parent::initialize();

		\add_action( 'cmb2_render_openai_prompt', array( $this, 'render' ), 10, 5 );
	}

	/**
	 * Render
	 *
	 * @param object $field Unused.
	 * @param string $escaped_value Unused.
	 * @param int    $object_id Unused.
	 * @param object $object_type Unused.
	 * @param object $field_type_object Unused.
	 * @return void
	 */
	public function render( $field, $escaped_value, $object_id, $object_type, $field_type_object ) { //phpcs:ignore
		echo '<div style="display: flex;flex-wrap: wrap;flex-direction: column;">';
		echo $field_type_object->textarea(// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'save_field'   => false,
				'autocomplete' => 'off',
				'rows'         => 4,
				'style'        => 'width:100%',
			)
		);

		echo $field_type_object->input(// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			array(
				'type'  => 'button',
				'value' => \__( 'Generate Term\'s content', GT_TEXTDOMAIN ), //phpcs:ignore
				'class' => 'button button-secondary button-large',
				'style' => 'margin-top:10px',
			)
		);
		echo '</div>';
	}

}
