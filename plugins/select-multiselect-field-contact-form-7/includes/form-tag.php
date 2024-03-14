<?php 
/* Form Tag Handler*/

add_action( 'wpcf7_init', 'wpcf7_selct_multiselct_add_formtag' );

function wpcf7_selct_multiselct_add_formtag(){
    wpcf7_add_form_tag(
		array( 'selct-multiselct', 'selct-multiselct*' ),
		'wpcf7_selct_multiselct_formtag_handler', true );
}

function wpcf7_selct_multiselct_formtag_handler( $tag ){
    $tag = new WPCF7_FormTag( $tag );

    if ( empty( $tag->name ) ){
        return '';
    }

    wpcf7_selct_multiselct_load_static_assets();
    
    $validation_error = wpcf7_get_validation_error( $tag->name );
    if ( $validation_error ){
      $class .= ' wpcf7-not-valid';
    }

    $class = wpcf7_form_controls_class( $tag->type, 'wpcf7-selct-multiselct' );

    $atts = array();
    $atts['class'] = $tag->get_class_option( $class );
    $atts['id'] = $tag->get_id_option();
    $atts['tabindex'] = $tag->get_option( 'tabindex', 'signed_int', true );

    if ( $tag->is_required() ) {
		  $atts['aria-required'] = 'true';
    }

    $atts['aria-invalid'] = $validation_error ? 'true' : 'false';

    if ( $tag->has_option( 'placeholder' ) or 
        $tag->has_option( 'watermark' ) ):
      $atts['data-placeholder'] = $tag->get_option('placeholder', '' , true);
      $atts['data-allow-clear'] = 'true';
    else :
      $atts['data-placeholder'] = __('--Please Select--', 'yb-sml');
      $atts['data-allow-clear'] = 'true';
    endif;

    if( $tag->has_option( 'size' ) ) :
      $atts['data-width'] = $tag->get_option('size','',true );
      
    else :
      $atts['data-width'] = '400px';
    endif;

    $multiple = $tag->has_option( 'multiple' );
  
    if ( $data = (array) $tag->get_data_option() ) {
      $tag->values = array_merge( $tag->values, array_values( $data ) );
      $tag->labels = array_merge( $tag->labels, array_values( $data ) );
	  }

	  $values = $tag->values;
    $labels = $tag->labels;
    
    $default_choice = $tag->get_default_option( null, array(
      'multiple' => $multiple
    ) );

    $html = '';
    $hangover = wpcf7_get_hangover( $tag->name );

    foreach ( $values as $key => $value ) {
        if ( $hangover ) {
			$selected = in_array( $value, (array) $hangover, true );
		} else {
			$selected = in_array( $value, (array) $default_choice, true );
    }

        $item_atts = array(
          'value' => $value,
          'selected' => $selected ? 'selected' : '',
        );

        $item_atts = wpcf7_format_atts( $item_atts );

        $label = isset( $labels[$key] ) ? $labels[$key] : $value;

        $html .= sprintf( '<option %1$s>%2$s</option>',
			$item_atts, esc_html( $label ) );
    }

    if ( $multiple ) {
		  $atts['multiple'] = 'multiple';
    }
    
    $atts['name'] = $tag->name . ( $multiple ? '[]' : '' );


    $atts = wpcf7_format_atts( $atts );

    $html = sprintf(
		'<span class="wpcf7-form-control-wrap %1$s"><select %2$s>%3$s</select>%4$s</span>',
		sanitize_html_class( $tag->name ), $atts, $html, $validation_error );

	return $html;

}

function wpcf7_selct_multiselct_load_static_assets(){
    $extension='.min.js';
    $extension1 = '.min.css';
	if( defined('SCRIPT_DEBUG') && SCRIPT_DEBUG ) {
    $extension ='.js';
    $extension1 = '.css';
  }

    wp_register_style( 'wpcf7-selct-multiselct-lib-css', YB_SM_URL. 'assets/css/select2'. $extension1, '', '4.0.11', 'all');
    wp_register_script( 'wpcf7-selct-multiselct-lib-js', YB_SM_URL.'assets/js/select2'. $extension, array( 'jquery' ), '4.0.11', true);
    wp_register_script( 'wpcf7-selct-multiselct-lib-custom-js', YB_SM_URL. 'assets/js/select-multiselect-custom.js', array('jquery','wpcf7-selct-multiselct-lib-js'),'1.0',true );
    wp_enqueue_style( 'wpcf7-selct-multiselct-lib-css' );
    wp_enqueue_script( 'wpcf7-selct-multiselct-lib-js' );
    wp_enqueue_script( 'wpcf7-selct-multiselct-lib-custom-js' );
}
?>