<?php

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

class PL_Customizer_Control_Dropdown_Select2_Custom extends WP_Customize_Control {

	/**
	 * The type of control being rendered
	 */
	public $type = 'dropdown_select2';

	/**
	 * The type of Select2 Dropwdown to display. Can be either a single select dropdown or a multi-select dropdown. Either false for true. Default = false
	 */
	private $multiselect = false;


	/**
	 * The Placeholder value to display. Select2 requires a Placeholder value to be set when using the clearall option. Default = 'Please select...'
	 */
	private $placeholder = 'Please select category...';

	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array(), $options = array() ) {
		parent::__construct( $manager, $id, $args );
		// Check if this is a multi-select field
		if ( isset( $this->input_attrs['multiselect'] ) && $this->input_attrs['multiselect'] ) {
			$this->multiselect = true;
		}
		// Check if a placeholder string has been specified
		if ( isset( $this->input_attrs['placeholder'] ) && $this->input_attrs['placeholder'] ) {
			$this->placeholder = $this->input_attrs['placeholder'];
		}
	}

	/**
	 * Enqueue our scripts and styles
	 */
	public function enqueue() {
		wp_enqueue_script( 'pluglab-select2-js', PL_PLUGIN_INC_URL . 'customizer/js/select2.full.min.js', array( 'jquery' ), '4.0.13', true );
		wp_enqueue_script( 'pluglab-custom-controls-js', PL_PLUGIN_INC_URL . 'customizer/js/customizer.js', array( 'pluglab-select2-js' ), '1.0', true );
		wp_enqueue_style( 'pluglab-custom-controls-css', PL_PLUGIN_INC_URL . 'customizer/css/customizer.css', array(), '1.1', 'all' );
		wp_enqueue_style( 'pluglab-select2-css', PL_PLUGIN_INC_URL . 'customizer/css/select2.min.css', array(), '4.0.13', 'all' );
	}

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
		$this->choices = array();
		$getCategories = get_categories();

		$postCats = array();
		foreach ( $getCategories as $categoryV ) {
			$this->choices[ $categoryV->term_id ] = $categoryV->name;
		}
		$defaultValue = $this->value();
		if ( $this->multiselect ) {
			$defaultValue = explode( ',', $this->value() );
		}
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
			<input type="hidden" id="<?php echo esc_attr( $this->id ); ?>" class="customize-control-dropdown-select2" value="<?php echo esc_attr( $this->value() ); ?>" name="<?php echo esc_attr( $this->id ); ?>" <?php $this->link(); ?> />
			<select name="select2-list-<?php echo ( $this->multiselect ? 'multi[]' : 'single' ); ?>" class="customize-control-select2" data-placeholder="<?php echo $this->placeholder; ?>" <?php echo ( $this->multiselect ? 'multiple="multiple" ' : '' ); ?>>
				<?php
				foreach ( $this->choices as $key => $value ) {

					if ( $this->multiselect ) {
						echo '<option value="' . esc_attr( $key ) . '" ' . ( in_array( esc_attr( $key ), $defaultValue ) ? 'selected="selected"' : '' ) . '>' . esc_attr( $value ) . '</option>';
					} else {
						echo '<option value="' . esc_attr( $key ) . '" ' . selected( esc_attr( $key ), $defaultValue, false ) . '>' . esc_attr( $value ) . '</option>';
					}
				}
				?>
			</select>
		</div>
		<?php
	}

}
