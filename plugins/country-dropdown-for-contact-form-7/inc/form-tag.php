<?php 
add_action( 'wpcf7_init' , 'csfcf7_add_form_tag_country' , 10, 0 );
function csfcf7_add_form_tag_country() {
	wpcf7_add_form_tag( array( 'country_select', 'country_select*' ), 'csfcf7_country_tag_handler',array('name-attr' => true) );
}


function csfcf7_country_tag_handler($tag){
	if ( empty( $tag->name ) ) {
		return '';
	}

	$validation_error = wpcf7_get_validation_error( $tag->name );

	$class = wpcf7_form_controls_class( $tag->type );

	if ( $validation_error ) {
		$class .= ' wpcf7-not-valid';
	}

	$atts = array();

	$class = $atts['class'] = $tag->get_class_option( $class );
	$id = $atts['id'] = $tag->get_id_option();
	$default_country = $tag->get_option('default_country', '', true);
	

	if ( $tag->has_option( 'readonly' ) ) {
		$atts['readonly'] = 'readonly';
	}

	if ( $tag->is_required() ) {
		$atts['aria-required'] = 'true';
	}

	if ( $validation_error ) {
		$atts['aria-invalid'] = 'true';
		$atts['aria-describedby'] = wpcf7_get_validation_error_reference(
			$tag->name
		);
	} else {
		$atts['aria-invalid'] = 'false';
	}


	$atts['name'] = $tag->name;
	$atts['type'] = 'hidden';

	$atts = wpcf7_format_atts( $atts );
	$html ='<div class="dswcf7_country_sel">
			<input id="'.$id.'" type="text" name="'.$tag->name.'"  class="'.$class.' country_select_class" default_country="'.$default_country.'">
        </div>';

	return $html;
}