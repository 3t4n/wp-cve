<?php
/**
 * Settings Page Controller Class
 *
 * @author 		MojofyWP
 * @package 	builder/settings-page
 * 
 */

if ( !class_exists('WRSL_Builder_Settings') ) :

class WRSL_Builder_Settings {

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
			self::$_instance = new WRSL_Builder_Settings();

		return self::$_instance;
	}

	/**
	 * Get Model
	 *
	 * @access public
	 * @return WRSL_Builder_Settings_Model
	 */
	public function get_model() {
		return $this->_model;
	}
	
	/**
	 * Class Constructor
	 * @param array
	 * @access private
	 */
    function __construct( $args = array() ) {

		$this->_hook_prefix = wrsl()->plugin_hook() . 'builder_settings/';
    	$this->_model 		= new WRSL_Builder_Settings_Model();

    	add_action( 'admin_menu', array(&$this, 'add_settings_page') , 15 );

    	// add new wizard
    	add_action( 'wp_ajax_wrslb-add-new', array(&$this, 'add_new') );
    	add_action( 'wp_ajax_wrslb-create-new', array(&$this, 'create_new') );

    	// remove carousel
    	add_action( 'wp_ajax_wrslb-delete-carousel', array(&$this, 'delete_carousel') );

		// update settings ajax listener
		add_action( 'wp_ajax_wrslb-update-settings', array(&$this, 'update_settings') );
    }

   	/**
	 * Add Settings Page
	 *
	 * @access public
	 */
	public function add_settings_page() {

		add_menu_page( 		        	
			__( 'WoorouSell' , WRSL_SLUG ) , // page_title
			__( 'WoorouSell' , WRSL_SLUG ) , // menu_title
			'manage_options' , // capability
			'wrsl-builder', // menu_slug
			array(&$this, 'render_settings_page'), // callback function
			'dashicons-slides' // icon
		);
	}

	/**
	 * Render Settings Page
	 *
	 * @access public
	 * @return string
	 */
	public function render_settings_page() {

		if ( ! current_user_can( 'manage_options' ) )
			wp_die( __( 'Cheatin&#8217; uh?' ) );

		$view = new WRSL_Builder_Settings_View();

		$page = ( isset( $_GET['view'] ) ? esc_attr( $_GET['view'] ) : 'overview' );

		switch ( $page ) {
			case 'overview':
			default:
				$subtitle = __( 'Overview' , WRSL_SLUG );
				break;
		
			case 'edit':
				$subtitle = __( 'Edit WoorouSell' , WRSL_SLUG );
				break;
		}

		ob_start();
		?>
		<div id="wrsl-builder">
			<div class="wrslb-logo-wrapper">
				<img src="<?php echo wrsl()->plugin_url('assets/img/logo.png'); ?>" class="wrslb-logo" />
				<span><?php echo 'v' . WRSL_VERSION; ?></span>
			</div>
			<div class="wrslb-main-wrapper">
				<?php echo $view->render_header( $subtitle ); ?>
				<div class="wrslb-main-container">
				<?php
					switch ( $page ) {
						case 'overview':
						default:
							$carousels = $this->_model->get_all_carousels();
							echo $view->render_overview( array(
									'carousels' => $carousels
								) );
							break;
					
						case 'edit':
							$c_id = ( isset( $_GET['id'] ) ? intval( $_GET['id'] ) : 0 );
							echo $view->render_edit( array(
									'id' => $c_id,
									'values' => $this->_model->get_values( $c_id ),
								) );
							break;
					}
				?>
				</div><!-- .wrslb-main-container -->
			</div><!-- .wrslb-main-wrapper -->
		</div><!-- #wrsl-builder -->
		<?php echo $view->modal_container(); ?>
		<?php
		$html = ob_get_clean();

		echo apply_filters( $this->_hook_prefix . 'render_settings_page' , ( !empty( $html ) ? $html : '' ) , $this );
	}

	/**
	 * Add New Wizard
	 *
	 * @access public
	 * @return string
	 */
	public function add_new() {

		$view = new WRSL_Builder_Settings_View();
		$output = $view->add_new_wizard();

      	if (defined('DOING_AJAX') && DOING_AJAX) {
        	echo apply_filters( $this->_hook_prefix . 'add_new' , $output , $this );
			exit;
      	} // end - DOING_AJAX

	}

	/**
	 * ACreate new
	 *
	 * @access public
	 * @return string
	 */
	public function create_new() {

		$status = false;
		$id = 0;
		$values = ( isset( $_POST['wrslb_new_carousel'] ) ? $_POST['wrslb_new_carousel'] : array() );

		if ( isset( $_POST['_create_carousel_nonce'] ) && wp_verify_nonce( $_POST['_create_carousel_nonce'] , 'wrslb_create_carousel' ) && !empty( $values ) ) {
			$id = $this->_model->create_new( $values );	
			if ( !empty( $id ) )
				$status = true;
		}
		
		if ( $status ) {
			$output = 'success_|_';
			$output .= apply_filters( $this->_hook_prefix . 'create_new/success_msg' , '
				<div class="wrslb-addnew-container">
					<h2 class="wrslb-modal-headline">'.sprintf( __( '"%s" has been successfully created!' , WRSL_SLUG ) , get_the_title( $id ) ).'<span class="subheadline">'. __( 'Click the "Edit" button below to edit your carousel' , WRSL_SLUG ).'</span></h2>
					<div class="wrslb-modal-actions">
						<a href="'. wrslb_options_page_url( array( 'view' => 'overview' ) ) . '" class="wrslb-modal-cancel-btn wrslb-loading-btn">'.__( 'Close' , WRSL_SLUG ).'</a>
						<a href="' . wrslb_options_page_url( array( 'view' => 'edit' , 'id' => $id ) ) . '" class="wrslb-modal-action-success">'.__( 'Edit' , WRSL_SLUG ).'</a>
					</div>
				</div>
				' , $id );
		} else {
			$output = 'error_|_';
			if ( empty( $_POST['wrslb_new_carousel']['title'] ) )
				$output .= apply_filters( $this->_hook_prefix . 'create_new/error_msg' , __( 'Please insert a name.' , WRSL_SLUG ) );
			else
				$output .= apply_filters( $this->_hook_prefix . 'create_new/error_msg' , __( 'Something just went wrong! Please try again.' , WRSL_SLUG ) );

		}

      	if (defined('DOING_AJAX') && DOING_AJAX) {
        	echo apply_filters( $this->_hook_prefix . 'create_new' , $output , $id , $this );
			exit;
      	} // end - DOING_AJAX

	}

	/**
	 * Update settings via ajax
	 *
	 * @access public
	 * @return string
	 */
	public function update_settings() {

		$status = false;
		$output = '';
		$id = ( isset( $_POST['wrslb_carousel_id'] ) ? intval( $_POST['wrslb_carousel_id'] ) : 0 );
		$values = ( isset( $_POST['wrslb_carousel'] ) ? $_POST['wrslb_carousel'] : array() );

		if ( isset( $_POST['_update_settings_nonce'] ) && wp_verify_nonce( $_POST['_update_settings_nonce'] , 'wrslb_update_settings' ) && !empty( $values ) && !empty( $id ) ) {
			$status = $this->_model->update_settings( $id , $values );	
		}	

		if ( $status ) {
			$output .= 'success_|_';
			$output .= apply_filters( $this->_hook_prefix . 'update_settings/success_msg' , '
					<i class="fa fa-check-square-o"></i><span class="wrslb-modal-msg">'.__( 'Settings Saved!' , WRSL_SLUG ).'</span>' , WRSL_SLUG , $id );
		} else {
			$output .= 'error_|_';
			$output .= apply_filters( $this->_hook_prefix . 'update_settings/error_msg' , __( 'Something just went wrong! Please try again.' , WRSL_SLUG ) , $id );
		}

      	if (defined('DOING_AJAX') && DOING_AJAX) {
        	echo apply_filters( $this->_hook_prefix . 'update_settings' , $output , $this );
			exit;
      	} // end - DOING_AJAX
	}

	/**
	 * Remove carousel
	 *
	 * @access public
	 * @return string
	 */
	public function delete_carousel() {

		$status = false;
		
		if ( isset( $_POST['id'] ) ) {
			$c_id = intval( $_POST['id'] );
			$post = get_post( $c_id );

			if ( $post->post_type == 'wrsl' ) {
				$status = wp_delete_post( $c_id, true );
			}

		} // end - $_POST['id']

      	if (defined('DOING_AJAX') && DOING_AJAX) {
        	echo apply_filters( $this->_hook_prefix . 'delete_carousel' , ( $status ? 'success' : 'error' ) , $this );
			exit;
      	} // end - DOING_AJAX

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

} // end - class WRSL_Builder_Settings

WRSL_Builder_Settings::get_instance();

endif; // end - !class_exists('WRSL_Builder_Settings')