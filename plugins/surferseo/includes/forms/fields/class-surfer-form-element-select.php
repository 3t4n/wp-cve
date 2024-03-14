<?php
/**
 *  Class to handle <select>
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Forms\Fields;

/**
 * Class to handle <select>
 */
class Surfer_Form_Element_Select extends Surfer_Form_Element_Multichoice {

	/**
	 * Default construct for text fields.
	 *
	 * @param string $name - name of the field.
	 */
	public function __construct( $name ) {
		parent::__construct( $name );

		$this->type = 'select';
	}

	/**
	 * Executed field default renderer.
	 *
	 * @return void
	 */
	protected function default_renderer() {
		ob_start();
		?>
			<select name="<?php echo esc_html( $this->name ); ?>" id="<?php echo esc_html( $this->name ); ?>" class="<?php echo esc_html( $this->get_classes() ); ?>">
				<?php foreach ( $this->options as $option ) : ?>
					<option value="<?php echo esc_html( $option['value'] ); ?>" <?php echo ( $option['value'] === $this->value ) ? 'selected="selected"' : ''; ?>><?php echo esc_html( $option['label'] ); ?></option>
				<?php endforeach; ?>
			</select>
		<?php
		$content = ob_get_clean();

		echo wp_kses( $content, parent::return_allowed_html_for_forms_elements() );
	}

}
