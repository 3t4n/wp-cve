<?php
/**
 * The metabox-specific functionality of the plugin.
 *
 * @link       https://thebrandiD.com
 * @since      2.0.0
 *
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/admin
 */

/**
 * The metabox-specific functionality of the plugin.
 *
 * @package    Social_Proof_Slider
 * @subpackage Social_Proof_Slider/admin
 */
class Social_Proof_Slider_Admin_Metaboxes {

	/**
	 * The post meta data
	 *
	 * @since 		2.0.0
	 * @access 		private
	 * @var 		string 			$meta    			The post meta data.
	 */
	private $meta;

	/**
	 * The ID of this plugin.
	 *
	 * @since 		2.0.0
	 * @access 		private
	 * @var 		string 			$plugin_name 		The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since 		2.0.0
	 * @access 		private
	 * @var 		string 			$version 			The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 		2.0.0
	 * @param 		string 			$Now_Hiring 		The name of this plugin.
	 * @param 		string 			$version 			The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		$this->set_meta();

	}

	/**
	 * Registers metaboxes with WordPress
	 *
	 * @since 	2.0.0
	 * @access 	public
	 */
	public function add_metaboxes() {

		// add_meta_box( $id, $title, $callback, $screen, $context, $priority, $callback_args );

		add_meta_box(
			'socialproofslider_details_box',
			apply_filters(
				$this->plugin_name . '-metabox-title-additional-info',
				esc_html__( 'Testimonial Details',
				'social-proof-slider',
				)
			),
			array(
				$this,
				'metabox_callback' ,
			),
			'socialproofslider',
			'normal',
			'default',
			array(
				'file' => 'testimonial-details'
			)
		);

	} // add_metaboxes()

	/**
	 * Check each nonce. If any don't verify, $nonce_check is increased.
	 * If all nonces verify, returns 0.
	 *
	 * @since 		2.0.0
	 * @access 		public
	 * @return 		int 		The value of $nonce_check
	 */
	private function check_nonces( $posted ) {

		$nonces 		= array();
		$nonce_check 	= 0;

		$nonces[] 		= 'testimonial_details';

		foreach ( $nonces as $nonce ) {

			if ( ! isset( $posted[$nonce] ) ) { $nonce_check++; }
			if ( isset( $posted[$nonce] ) && ! wp_verify_nonce( $posted[$nonce], $this->plugin_name ) ) { $nonce_check++; }

		}

		return $nonce_check;

	} // check_nonces()

	/**
	 * Returns an array of the all the metabox fields and their respective types
	 *
	 * @since 		2.0.0
	 * @access 		public
	 * @return 		array 		Metabox fields and types
	 */
	private function get_metabox_fields() {

		$fields = array();

		$fields[] = array( 'socialproofslider_testimonial_author_name', 'textarea' );
		$fields[] = array( 'socialproofslider_testimonial_author_title', 'textarea' );
		$fields[] = array( 'socialproofslider_testimonial_text', 'textarea' );

		return $fields;

	} // get_metabox_fields()

	/**
	 * Calls a metabox file specified in the add_meta_box args.
	 *
	 * @since 	2.0.0
	 * @access 	public
	 * @return 	void
	 */
	public function metabox_callback( $post, $params ) {

		if ( ! is_admin() ) { return; }
		if ( 'socialproofslider' !== $post->post_type ) { return; }

		if ( ! empty( $params['args']['classes'] ) ) {

			$classes = 'repeater ' . $params['args']['classes'];

		}

		include( plugin_dir_path( __FILE__ ) . 'partials/social-proof-slider-admin-metabox-' . $params['args']['file'] . '.php' );

	} // metabox_callback()

	private function sanitizer( $type, $data ) {

		if ( empty( $type ) ) { return; }
		if ( empty( $data ) ) { return; }

		$return 	= '';
		$sanitizer 	= new Social_Proof_Slider_Sanitize();

		$sanitizer->set_data( $data );
		$sanitizer->set_type( $type );

		$return = $sanitizer->clean();

		unset( $sanitizer );

		return $return;

	} // sanitizer()

	/**
	 * Sets the class variable $options
	 */
	public function set_meta() {

		global $post;

		if ( empty( $post ) ) { return; }
		if ( 'socialproofslider' != $post->post_type ) { return; }

		// wp_die( '<pre>' . print_r( $post->ID ) . '</pre>' );

		$this->meta = get_post_custom( $post->ID );

	} // set_meta()

	/**
	 * Shows metabox data
	 *
	 * @since 	2.0.0
	 * @access 	public
	 * @param 	int 		$post_id 		The post ID
	 * @param 	object 		$object 		The post object
	 * @return 	void
	 */
	public function show_meta( $post_id, $object ) {

		// if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return $post_id; }
		// if ( ! current_user_can( 'edit_post', $post_id ) ) { return $post_id; }
		// if ( 'socialproofslider' !== $object->post_type ) { return $post_id; }

		// $nonce_check = $this->check_nonces( $_POST );

		// if ( 0 < $nonce_check ) { return $post_id; }

		// $metas = $this->get_metabox_fields();

		// foreach ( $metas as $meta ) {

		// 	$name = $meta[0];
		// 	$type = $meta[1];

		// 	//$new_value = $this->sanitizer( $type, $_POST[$name] );

		// 	// update_post_meta( $post_id, $name, $new_value );
		// 	update_post_meta( $post_id, $name, $_POST[$name] );

		// } // foreach

	} // show_meta()

	/**
	 * Saves metabox data
	 *
	 * Repeater section works like this:
	 *  	Loops through meta fields
	 *  		Loops through submitted data
	 *  		Sanitizes each field into $clean array
	 *   	Gets max of $clean to use in FOR loop
	 *   	FOR loops through $clean, adding each value to $new_value as an array
	 *
	 * @since 	2.0.0
	 * @access 	public
	 * @param 	int 		$post_id 		The post ID
	 * @param 	object 		$object 		The post object
	 * @return 	void
	 */
	public function validate_meta( $post_id, $object ) {

		// wp_die( '<pre>' . print_r( $_POST ) . '</pre>' );

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) { return $post_id; }
		if ( ! current_user_can( 'edit_post', $post_id ) ) { return $post_id; }
		if ( 'socialproofslider' !== $object->post_type ) { return $post_id; }

		$nonce_check = $this->check_nonces( $_POST );

		if ( 0 < $nonce_check ) { return $post_id; }

		$metas = $this->get_metabox_fields();

		foreach ( $metas as $meta ) {

			$name = $meta[0];
			$type = $meta[1];

			//$new_value = $this->sanitizer( $type, $_POST[$name] );

			// update_post_meta( $post_id, $name, $new_value );
			update_post_meta( $post_id, $name, wp_filter_post_kses( $_POST[$name] ) );

		} // foreach

	} // validate_meta()

} // class
