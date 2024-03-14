<?php
/**
 * Builder Controller Class
 *
 * @author 		MojofyWP
 * @package 	builder/builder
 * 
 */

if ( !class_exists('WRSL_Builder') ) :

class WRSL_Builder {

	/**
	 * Class instance
	 *
	 * @access private
	 * @var object
	 */
	private static $_instance = null;

	/**
	 * model
	 *
	 * @access private
	 * @var object
	 */
	private $_model = null;

	/**
	 * hook prefix
	 *
	 * @access private
	 * @var string
	 */
	private $_hook_prefix = null;

	/**
	 * Get class instance
	 *
	 * @access public
	 * @return object
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new WRSL_Builder();

		return self::$_instance;
	}

	/**
	 * Get Model
	 *
	 * @access public
	 * @return WRSL_Builder_Model
	 */
	public function get_model() {
		return $this->_model;
	}
	
	/**
	 * Class Constructor
	 * @access private
	 */
    function __construct() {

		$this->_hook_prefix = wrsl()->plugin_hook() . 'builder/';

    	// setup variables
    	$this->_model = new WRSL_Builder_Model();

		// register woorousell as custom post type
		add_action( 'init', array( &$this->_model, 'register_woorousell' ), 1 );

		// add woorousell shortcode
		add_shortcode( 'woorousell' , array(&$this, 'woorousell_sc') );
    }

	/**
	 * Render Woorousell Shortcode
	 *
	 * @access public
	 */
	public function woorousell_sc( $atts ) {

		extract( shortcode_atts( array(
			'id' => 0
		), $atts , 'woorousell' ) );

		$values = $this->_model->get_values( $id );
		$view = new WRSL_Builder_View();

		// enqueue scripts
		$this->_model->enqueue_scripts();

		// Apply Styling
		$this->_model->apply_inline_styling( $id , $values );

		$component = $this->_model->get_component( $id , $values );

		ob_start();
		?>
		<div id="woorousell-<?php echo $id; ?>" class="woorousell_sc">
			<?php $component->render(); ?>
		</div>
		<?php
		$html = ob_get_clean();

		return apply_filters( $this->_hook_prefix . 'woorousell_sc' , ( !empty( $html ) ? $html : '' ) , $atts , $this );
		
	}

	/**
	 * sample function
	 *
	 * @access public
	 * @return string
	 */
	public function sample_func() {

		$output = '';

		return apply_filters( $this->_hook_prefix . 'sample_func' , $output , $this );
	}

	/* END
	------------------------------------------------------------------- */

} // end - class WRSL_Builder

WRSL_Builder::get_instance();

endif; // end - !class_exists('WRSL_Builder')