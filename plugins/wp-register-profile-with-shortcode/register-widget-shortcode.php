<?php

function register_widget_shortcode( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
	      'title' => '',
     ), $atts ) );
     
	ob_start();
	if($title){
		echo '<h2>'.$title.'</h2>';
	}
	$rf = new Register_Form;
	$rf->registration_form();
	$ret = ob_get_contents();	
	ob_end_clean();
	return $ret;
}

function user_profile_edit_shortcode( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
	      'title' => '',
     ), $atts ) );
     
	ob_start();
	$pea = new Register_Profile_Edit;
	if($title){
		echo '<h2>'.$title.'</h2>';
	}
	$pea->profile_edit();
	$ret = ob_get_contents();	
	ob_end_clean();
	return $ret;
}

function update_password_shortcode( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
	      'title' => '',
     ), $atts ) );
     
	ob_start();
	$up = new Register_Update_Password;
	if($title){
		echo '<h2>'.$title.'</h2>';
	}
	$up->update_password_form();
	$ret = ob_get_contents();	
	ob_end_clean();
	return $ret;
}

function wprp_get_user_data( $atts ) {
     global $post;
	 extract( shortcode_atts( array(
	      'field' => '',
		  'user_id' => '',
     ), $atts ) );
     
	 $error = false;
	 if( empty($atts['user_id']) and is_user_logged_in()){
	 	$user_id = get_current_user_id();
	 } elseif( !empty($atts['user_id'])){
	 	$user_id = $atts['user_id'];
	 } else if( empty($atts['user_id']) and !is_user_logged_in() ){
	 	$error = true;
	 }
	 if(!$error){
	 	$ret = get_the_author_meta( $atts['field'], $user_id );
	 } else {
	 	$ret = __('Sorry. no user was found!','wp-register-profile-with-shortcode');
	 }
		
	 return $ret;
}

function rp_user_data_func($field='',$user_id=''){
	echo do_shortcode('[rp_user_data field="'.$field.'" user_id="'.$user_id.'"]');
}