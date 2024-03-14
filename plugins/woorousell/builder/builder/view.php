<?php
/**
 * Builder View Class
 *
 * @author 		MojofyWP
 * @package 	builder/builder
 * 
 */

if ( !class_exists('WRSL_Builder_View') ) :

class WRSL_Builder_View {

	/**
	 * Hook prefix
	 *
	 * @access private
	 * @var string
	 */
	private $_hook_prefix = null;

	/**
	 * Class Constructor
	 *
	 * @access private
	 */
    function __construct() {

		// setup variables
		$this->_hook_prefix = wrsl()->plugin_hook() . 'builder/view/';


    }

	/**
	 * sample render
	 *
	 * @access public
	 */
	public function sample_render( $args = array() ) {

		$defaults = array(
			'sample' => true,
		);

		$instance = wp_parse_args( $args, $defaults );
		extract( $instance );	

		ob_start();
		?>

		<?php
		$html = ob_get_clean();

		echo apply_filters( $this->_hook_prefix . 'sample_render' , ( !empty( $html ) ? $html : '' ) , $args , $this );
	}

	/* END
	------------------------------------------------------------------- */

} // end - class WRSL_Builder_View

endif; // end - !class_exists('WRSL_Builder_View')