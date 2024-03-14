<?php

function bravepop_hide_meta_boxes( $post_type, $post ) {
   $post_types = get_post_types( array('public' => true) );
   add_meta_box( 'bravepop_hide_metabox', __( 'Hide Popups', 'bravepop' ), 'bravepop_render_meta_boxes', $post_types, 'side', 'bravepop' );
}
add_action( 'add_meta_boxes', 'bravepop_hide_meta_boxes', 10, 2 );


function bravepop_render_meta_boxes($post){
   // make sure the form request comes from WordPress
   wp_nonce_field( basename( __FILE__ ), 'bravepop_hide_metabox_nonce' );
   $brave_hidden_popups = get_post_meta( $post->ID, 'brave_hidden_popups', true ) ? get_post_meta( $post->ID, 'brave_hidden_popups', true ) : array() ;
   $post_type_obj = get_post_type_object( $post->post_type );
   $post_type_name = $post_type_obj->labels->singular_name;
	?>
	<div class='inside'>
		<h5><?php esc_html_e('Select Popups to Hide them from this', 'bravepop' ); ?> <?php print_r(esc_html($post_type_name)); ?></h5>
      <p>
         <select id="brave_selected_popups" data-count="<?php print_r(count($brave_hidden_popups));?>" name="selectedPopups[]" multiple="multiple" style="width: 100%;border: 1px solid #ddd; max-height: 150px;"> 
            <option value="" <?php print_r(count($brave_hidden_popups) > 0 ? '' : 'selected="selected"');?> style="padding: 5px;"><?php esc_html_e( 'Do not Hide, Show All Popups', 'bravepop' ); ?></option>
            <?php
               $posts_query = new WP_Query();
               $query_args = array('posts_per_page' => 100, 'post_type' => 'popup', 'post_status' => array( 'publish' ));
               $query_result = $posts_query->query( $query_args );
               $posts = array();
               foreach ( $query_result as $popup ) { ?>
                  <option value="<?php print_r(absint($popup->ID));?>" <?php selected(  ( in_array( $popup->ID, $brave_hidden_popups ) ) ? $popup->ID : '', $popup->ID );?>  style="padding: 5px;"><?php print_r(esc_html($popup->post_title));?></option>';
               <?php  } ?>
         </select>
      </p>
	</div>
	<?php
}


function bravepop_save_meta_boxes( $post_id ){
	// verify taxonomies meta box nonce
	if ( !isset( $_POST['bravepop_hide_metabox_nonce'] ) || !wp_verify_nonce( $_POST['bravepop_hide_metabox_nonce'], basename( __FILE__ ) ) ){
		return;
	}
	// return if autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ){
		return;
	}
	// Check the user's permissions.
	if ( ! current_user_can( 'edit_post', $post_id ) ){
		return;
	}

	// store custom fields values
	if( isset( $_POST['selectedPopups'] ) ){
		$selectedPopups = (array) $_POST['selectedPopups'];
		// sinitize array
		$selectedPopups = array_map( 'sanitize_text_field', $selectedPopups );
		// save data
		update_post_meta( $post_id, 'brave_hidden_popups', $selectedPopups );
	}else{
		// delete data
		delete_post_meta( $post_id, 'brave_hidden_popups' );
	}
}
add_action( 'save_post', 'bravepop_save_meta_boxes' );