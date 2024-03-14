<?php 



/* Fire our meta box setup function on the post editor screen. */
add_action( 'load-post.php', 'wpb_bls_meta_boxes_setup' );
add_action( 'load-post-new.php', 'wpb_bls_meta_boxes_setup' );

/* Meta box setup function. */
function wpb_bls_meta_boxes_setup() {

  /* Add meta boxes on the 'add_meta_boxes' hook. */
  add_action( 'add_meta_boxes', 'wpb_bls_add_logo_meta_boxes' );

  /* Save post meta on the 'save_post' hook. */
  add_action( 'save_post', 'wpb_bls_save_logo_class_meta', 10, 2 );
}

/* Create one or more meta boxes to be displayed on the post editor screen. */
function wpb_bls_add_logo_meta_boxes() {

  add_meta_box(
    'wpb-bls-logo-class',      // Unique ID
    esc_html__( 'Client URL', 'Wpbean' ),    // Title
    'wpb_bls_logo_class_meta_box',   // Callback function
    'logo_slider',         // Admin page (or post type)
    'normal',         // Context
    'default'         // Priority
  );
}


/* Display the logo meta box. */
function wpb_bls_logo_class_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'wpb_bls_logo_class_nonce' ); ?>

  <p>
    <label for="wpb-bls-logo-class"><?php _e( "Put your client url, ", 'Wpbean' ); ?></label>
    <br />
    <input style="max-width:470px;" class="widefat" type="text" name="wpb-bls-logo-class" id="wpb-bls-logo-class" value="<?php echo esc_attr( get_post_meta( $object->ID, 'wpb_bls_logo_class', true ) ); ?>"/>
  </p>
<?php }



/* Save the meta box's post metadata. */
function wpb_bls_save_logo_class_meta( $post_id, $post ) {

  /* Verify the nonce before proceeding. */
  if ( !isset( $_POST['wpb_bls_logo_class_nonce'] ) || !wp_verify_nonce( $_POST['wpb_bls_logo_class_nonce'], basename( __FILE__ ) ) )
    return $post_id;

  /* Get the post type object. */
  $post_type = get_post_type_object( $post->post_type );

  /* Check if the current user has permission to edit the post. */
  if ( !current_user_can( $post_type->cap->edit_post, $post_id ) )
    return $post_id;

  /* Get the posted data and sanitize it for use as an HTML class. */
  $new_meta_value = $_POST['wpb-bls-logo-class'];

  /* Get the meta key. */
  $meta_key = 'wpb_bls_logo_class';

  /* Get the meta value of the custom field key. */
  $meta_value = get_post_meta( $post_id, $meta_key, true );

  /* If a new meta value was added and there was no previous value, add it. */
  if ( $new_meta_value && '' == $meta_value )
    add_post_meta( $post_id, $meta_key, $new_meta_value, true );

  /* If the new meta value does not match the old value, update it. */
  elseif ( $new_meta_value && $new_meta_value != $meta_value )
    update_post_meta( $post_id, $meta_key, $new_meta_value );

  /* If there is no new meta value but an old value exists, delete it. */
  elseif ( '' == $new_meta_value && $meta_value )
    delete_post_meta( $post_id, $meta_key, $meta_value );
}

/*
* Add for pro version
*/
add_action( 'load-post.php', 'wpb_bls_logo_pro_add_meta_box' );
add_action( 'load-post-new.php', 'wpb_bls_logo_pro_add_meta_box' );

/* Add for pro version */
function wpb_bls_logo_pro_add_meta_box() {

  add_meta_box(
    'wpb-bls-logo-add-pro',
    esc_html__( 'Pro version features', 'Wpbean' ),
    'wpb_bls_logo_pro_meta_box',
    'logo_slider',
    'advanced',
    'high'
  );
}

/* Display the post meta box add for pro version. */
function wpb_bls_logo_pro_meta_box( $object, $box ) { ?>

  <?php wp_nonce_field( basename( __FILE__ ), 'wpb_bls_logo_class_nonce' ); ?>

  <p>
	<h3 style="padding-left:0">Logo slider pro gives you</h3>
    <ol class="">
    	<li>Advance setting panel with all necessary settings.</li>
    	<li>Slider auto-play, speed, navigation, pagination, color settings.</li>
    	<li>Responsive settings. Can be control logo item to show on tablet & mobile.</li>
    	<li>Show logo title in a tooltip popup.</li>
    	<li>Image size control.</li>
    	<li>Visual composer support.</li>
    	<li>See all the features of PRO version.</li>
    	<li>And many more.</li>
    </ol>
  </p>
  <p><a class="button button-primary button-large" href="http://wpbean.com/product/best-logo-slider-pro/" target="_blank">Go for pro</a></p>
<?php }