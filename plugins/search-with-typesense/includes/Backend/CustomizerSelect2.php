<?php

namespace Codemanas\Typesense\Backend;

/**
 * Dropdown Select2 Custom Control
 *
 * @author  Anthony Hortin <http://maddisondesigns.com>
 * @license http://www.gnu.org/licenses/gpl-2.0.html
 * @link    https://github.com/maddisondesigns
 */
class CustomizerSelect2 extends \WP_Customize_Control {
	/**
	 * The type of control being rendered
	 */
	public $type = 'cm-select2';

	/**
	 * The Placeholder value to display. Select2 requires a Placeholder value to be set when using the clearall option. Default = 'Please select...'
	 */
	private $placeholder = 'Please select...';

	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array(), $options = array() ) {
		parent::__construct( $manager, $id, $args );
		// Check if a placeholder string has been specified
		if ( isset( $this->input_attrs['placeholder'] ) && $this->input_attrs['placeholder'] ) {
			$this->placeholder = $this->input_attrs['placeholder'];
		}
	}

	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_script( 'skyrocket-select2-js', CODEMANAS_TYPESENSE_ROOT_URI_PATH . 'assets/vendor/js/select2.full.min.js', [
			'jquery',
			'jquery-ui-sortable'
		], '4.0.13', true );
		wp_enqueue_style( 'skyrocket-select2-css', CODEMANAS_TYPESENSE_ROOT_URI_PATH . 'assets/vendor/css/select2.min.css', [], '4.0.13', 'all' );
	}

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		$defaultValue = is_array( $this->value() ) ? $this->value() : explode( ',', $this->value() );
		?>
        <div class="dropdown_select2_control">
			<?php if ( ! empty( $this->label ) ) { ?>
                <label for="<?php echo esc_attr( $this->id ); ?>" class="customize-control-title">
					<?php echo esc_html( $this->label ); ?>
                </label>
			<?php } ?>
			<?php if ( ! empty( $this->description ) ) { ?>
                <span class="customize-control-description"><?php echo esc_html( $this->description ); ?></span>
			<?php } ?>
            <input type="hidden"
                   id="<?php echo esc_attr( $this->id ); ?>"
                   class="customize-control-dropdown-select2"
                   value="<?php echo esc_attr( is_array( $this->value() ) ? implode( ',', $this->value() ) : $this->value() ); ?>"
                   name="<?php echo esc_attr( $this->id ); ?>"
				<?php $this->link(); ?>
            />
            <select name="select2-list-multi[]" class="customize-control-select2"
                    data-placeholder="<?php echo $this->placeholder; ?>" multiple="multiple">
				<?php
				foreach ( $this->choices as $key => $value ) {
					echo '<option value="' . esc_attr( $key ) . '" ' . ( in_array( esc_attr( $key ), $defaultValue ) ? 'selected="selected"' : '' ) . '>' . esc_attr( $value ) . '</option>';
				}
				?>
            </select>
        </div>
		<?php
	}
}