<?php
if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return;
}

class PL_Customizer_Control_Tab extends WP_Customize_Control {

	/**
	 * The type of control being rendered
	 */
	public $type = 'pluglab-tab-control';

	public $controls_general;

	public $controls_design;

	
	/**
	 * Constructor
	 */
	public function __construct( $manager, $id, $args = array(), $options = array() ) {
		parent::__construct( $manager, $id, $args );
	}

	/**
	 * Render the control in the customizer
	 */
	public function render_content() {
	?>

	<div class="control-tabs">
		<div class="control-tab control-tab-general active" data-connected="<?php echo esc_attr( $this->controls_general ); ?>"><?php echo esc_html__( 'General', 'pluglab' ); ?></div>
		<div class="control-tab control-tab-design" data-connected="<?php echo esc_attr( $this->controls_design ); ?>"><?php echo esc_html__( 'Style', 'pluglab' ); ?></div>
	</div>
	<?php
	}

}
