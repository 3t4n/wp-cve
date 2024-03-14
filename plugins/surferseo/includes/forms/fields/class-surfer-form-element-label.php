<?php
/**
 *  Class to handle labels (text without field)
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Forms\Fields;

/**
 * Class to handle labels (text without field)
 */
class Surfer_Form_Element_Label extends Surfer_Form_Element {

	/**
	 * Default construct for text fields.
	 *
	 * @param string $name - name of the field.
	 */
	public function __construct( $name ) {
		parent::__construct( $name );

		$this->type = 'label';
	}

	/**
	 * Executed field default renderer.
	 *
	 * @return void
	 */
	protected function default_renderer() {
		ob_start();
			echo esc_html( $this->label );
		$content = ob_get_clean();

		echo wp_kses( $content, parent::return_allowed_html_for_forms_elements() );
	}

}
