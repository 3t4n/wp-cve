<?php
/**
 *  Class to handle <input type="checkbox">
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Forms\Fields;

/**
 * Class to handle <input type="checkbox">
 */
class Surfer_Form_Element_Checkbox extends Surfer_Form_Element_Multichoice {

	/**
	 * Default construct for text fields.
	 *
	 * @param string $name - name of the field.
	 */
	public function __construct( $name ) {
		parent::__construct( $name );

		$this->type = 'checkbox';
	}

	/**
	 * Executed field default renderer.
	 *
	 * @return void
	 */
	protected function default_renderer() {
		ob_start();
		?>
			<?php foreach ( $this->options as $option ) : ?>
				<label>
					<input type="checkbox"  name="<?php echo esc_html( $this->name ); ?>[]" value="<?php echo esc_html( $option['value'] ); ?>" class="<?php echo esc_html( $this->get_classes() ); ?>" <?php echo ( in_array( $option['value'], (array) $this->value ) ) ? 'checked="checked"' : ''; ?> />
					<?php echo esc_html( $option['label'] ); ?>
				</label>
			<?php endforeach; ?>
		<?php
		$content = ob_get_clean();

		echo wp_kses( $content, parent::return_allowed_html_for_forms_elements() );
	}

}
