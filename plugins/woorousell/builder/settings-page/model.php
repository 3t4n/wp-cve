<?php
/**
 * Settings Page Model Class
 *
 * @author 		MojofyWP
 * @package 	builder/settings-page
 * 
 */

if ( !class_exists('WRSL_Builder_Settings_Model') ) :

class WRSL_Builder_Settings_Model {

	/**
	 * instance
	 *
	 * @access private
	 * @var array
	 */
	private $instance = null;

	/**
	 * Hook prefix
	 *
	 * @access private
	 * @var string
	 */
	private $_hook_prefix = null;

	/**
	 * Get instance
	 *
	 * @access public
	 * @return array
	 */
	public function get_instance() {
		return $this->instance;
	}

	/**
	 * Class Constructor
	 *
	 * @access private
	 */
    function __construct() {
		
		// setup variables
		$this->_hook_prefix = wrsl()->plugin_hook() . 'builder_settings/model/';
		
    }

   	/**
	 * Retrieve all carousels
	 *
	 * @access public
	 * @return array
	 */
	public function get_all_carousels() {

		$carousels = array();

		$query = new WP_Query( array(
				'post_type' => 'wrsl',
				'post_status' => 'publish',
				'paged' => 1,
				'posts_per_page' => 9999,
			) );

		if ($query->have_posts()) : 
			while ($query->have_posts()) : $query->the_post();
				$c_id = get_the_ID();
				$carousels[ $c_id ] = array( 'id' => $c_id );
			endwhile; 
		endif;

		wp_reset_postdata();

		return apply_filters( $this->_hook_prefix . 'get_all_carousels' , $carousels , $this );
	}

	/**
	 * Get Carousel Values
	 *
	 * @access public
	 * @return array
	 */
	public function get_values( $c_id = 0 ) {

		$values = array();

		// Get title
		$values['title'] = get_the_title( $c_id );

		// Get meta values
		$carousel_type = wrslb_get_meta( array( 'id' => $c_id , 'key' => 'carousel_type' , 'default' => 'product' , 'esc' => 'attr' ) );
		$default = wrsl_default_meta( $carousel_type );

		if ( !empty( $default ) && is_array( $default ) ) {
			foreach ( $default as $key => $d_value ) {
				if ( $key == 'columns' ) {
					$values[ $key ] = wrslb_get_meta( array( 'id' => $c_id , 'key' => $key , 'default' => $d_value , 'esc' => null ) );
				} else {
					$values[ $key ] = wrslb_get_meta( array( 'id' => $c_id , 'key' => $key , 'default' => $d_value , 'esc' => 'attr' ) );
				}
			}
		}

		return apply_filters( $this->_hook_prefix . 'get_values' , $values , $c_id , $this );
	}

	/**
	 * Create new
	 *
	 * @access public
	 */
	public function create_new( $values = array() ) {

		$id = null;
		$meta_prefix = wrsl()->plugin_meta_prefix();

		if ( isset( $values['title'] ) ) {

			$id = wp_insert_post( array(
					'post_title' => esc_attr( $values['title'] ),
					'post_type' => 'wrsl',
					'post_status' => 'publish'
				) );

			if ( !empty( $id ) && $id > 0 ) {

				// Get type
				$carousel_type = ( isset( $values['carousel_type'] ) ? esc_attr( $values['carousel_type'] ) : 'product' );

				$default = wrsl_default_meta( $carousel_type );
				$checkboxes = wrslb_checkbox_meta();

				// update meta
				if ( !empty( $default ) && is_array( $default ) ) {
					foreach ( $default as $key => $d_value ) {
						$is_checkbox = ( !empty( $checkboxes ) && in_array( $key , $checkboxes ) ? true : false );

						if ( $is_checkbox ) {
							if ( !empty( $d_value ) && $d_value == 'on' ) {
								update_post_meta( $id , $meta_prefix . $key , 'on' );
							} else {
								update_post_meta( $id , $meta_prefix . $key , 0 );
							}
						} else {
							update_post_meta( $id , $meta_prefix . $key , $d_value );
						} // end - is_checkbox
					} // end - foreach
				} // end -$default

			}



		} // end - $values[title]

		return apply_filters( $this->_hook_prefix . 'update_settings' , $id , $values , $this );
	}

	/**
	 * update settings
	 *
	 * @access public
	 */
	public function update_settings( $id = 0 , $values = array() ) {

		$status = true;
		$meta_prefix = wrsl()->plugin_meta_prefix();

		// Get type
		$carousel_type = wrslb_get_meta( array( 'id' => $id , 'key' => 'carousel_type' , 'default' => 'product' , 'esc' => 'attr' ) );
		$default = wrsl_default_meta( $carousel_type );
		$checkboxes = wrslb_checkbox_meta();

		// update meta
		if ( !empty( $default ) && is_array( $default ) ) {
			foreach ( $default as $key => $d_value ) {
				$is_checkbox = ( !empty( $checkboxes ) && in_array( $key , $checkboxes ) ? true : false );

				if ( $is_checkbox ) {
					if ( !empty( $values[ $key ] ) && $values[ $key ] == 'on' ) {
						update_post_meta( $id , $meta_prefix . $key , 'on' );
					} else {
						update_post_meta( $id , $meta_prefix . $key , 0 );
					}
				} else {
					if ( isset( $values[ $key ] ) ) {
						update_post_meta( $id , $meta_prefix . $key , $values[ $key ] );
					} else {
						update_post_meta( $id , $meta_prefix . $key , '' );
					}
				} // end - is_checkbox
			}
		}

		// update title 
		if ( isset( $values['title'] ) ) {
			$post_title = esc_attr( $values['title'] );
			wp_update_post( array( 'ID' => $id , 'post_title' => $post_title ) );
		}

		return apply_filters( $this->_hook_prefix . 'update_settings' , $status , $id , $values , $this );
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

} // end - class WRSL_Builder_Settings_Model

endif; // end - !class_exists('WRSL_Builder_Settings_Model')