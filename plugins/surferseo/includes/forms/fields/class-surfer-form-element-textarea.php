<?php
/**
 *  Class to handle <input type="text">
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Forms\Fields;

/**
 * Class to handle <textarea />
 */
class Surfer_Form_Element_Textarea extends Surfer_Form_Element {

	/**
	 * Default construct for text fields.
	 *
	 * @param string $name - name of the field.
	 */
	public function __construct( $name ) {
		parent::__construct( $name );

		$this->type = 'textarea';
	}

	/**
	 * Executed field default renderer.
	 *
	 * @return void
	 */
	protected function default_renderer() {
		ob_start();
		?>
			<textarea name="<?php echo esc_html( $this->name ); ?>" id="<?php echo esc_html( $this->name ); ?>" class="<?php echo esc_html( $this->get_classes() ); ?>" placeholder="<?php echo esc_html( $this->get_placeholder() ); ?>"><?php echo ( isset( $this->value ) ) ? esc_html( $this->value ) : ''; ?></textarea>
		<?php
		$content = ob_get_clean();

		echo wp_kses( $content, parent::return_allowed_html_for_forms_elements() );
	}

}
