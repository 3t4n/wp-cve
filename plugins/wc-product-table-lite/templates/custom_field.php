<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// variable switch
if(
	$field_name &&
	'variable' == $product->get_type() &&
	! empty( $variable_switch ) &&
	empty( $attribute_name )
){

	$table_data = wcpt_get_table_data();
	$table_id = $table_data['id'];

	$arr_key = 'wcpt_' . $table_id . '_variable_switch_cf';

	if( empty( $GLOBALS[$arr_key] ) ){
		$GLOBALS[$arr_key] = array();
	}

	if( empty( $GLOBALS[$arr_key][$element['id']] ) ){
		$GLOBALS[$arr_key][$element['id']] = array();
	}

	$vals =& $GLOBALS[$arr_key][$element['id']];

	$empty_val = '';
	if( ! empty( $empty_relabel ) ){
		$empty_val = wcpt_parse_2( $empty_relabel );
	}

	$product_id = $product->get_id();
	$product_cf_val = get_post_meta( $product_id, $field_name, true );
	$vals[ $product_id ] = $product_cf_val ? $product_cf_val : $empty_val;

	// get variations
	foreach( $product->get_children() as $child_id ){
		$meta_val = get_post_meta( $child_id, $field_name, true );
		$vals[$child_id] = $meta_val ? $meta_val : $empty_val;
	}

	$html_class .= " wcpt-variable-switch";
	
	echo "<div class='wcpt-custom-field wcpt-variable-switch $html_class' data-wcpt-element-id='{$element['id']}'>". $product_cf_val ."</div>";

	return;
}

if( empty( $manager ) ){
	$field_value = get_post_meta( $product->get_id(), $field_name, true );

	// if variation doesn't have custom field value, check parent
	if( 
		! $field_value &&
		$product->get_type() === 'variation'
	) {
		$field_value = get_post_meta( $product->get_parent_id(), $field_name, true );		
	}

	// Iconic variation custom fields
	if( 
		empty( $attribute_name ) && // not coming from attribute template
		'variation' == $product->get_type() &&
		class_exists( 'Iconic_CFFV_Fields' ) &&
		$custom_field_data = Iconic_CFFV_Fields::get_product_fields_data( $product->get_id() ) 
	){
		$field_value = ! empty( $custom_field_data[ strtolower( $field_name ) ] ) ? esc_html( $custom_field_data[ strtolower( $field_name ) ]['value'] ) : '';
	}	

}else if( 
	$manager === 'acf' && 
	class_exists( 'ACF' ) &&
	get_field_object( $field_name )
){
	$field_value = get_field( $field_name, $product->get_id(), true );
	$field_object = get_field_object( $field_name );

	if( 
		! $field_value &&
		$product->get_type() === 'variation'
	) {
		$field_value = get_field( $field_name, $product->get_parent_id(), true );
		$field_object = get_field_object( $field_name, $product->get_parent_id() );
	}

	// link
	if(
		$field_object['type'] == 'link' &&
		$field_object['return_format'] === 'array' &&
		! empty( $field_value['url'] ) &&
		! empty( $field_value['title'] )
	){
		$field_value = '<a class="wcpt-acf-link" href="'. $field_value['url'] .'" target="'. $field_value['target'] .'">'. $field_value['title'] .'</a>';

	// file
	}else if(
		$field_object['type'] == 'file' &&
		$field_object['return_format'] === 'array'
	){
		$field_value = '<a class="wcpt-acf-file" href="'. $field_value['url'] .'" download="'. esc_attr( $field_value['filename'] ) .'">'. $field_value['filename'] .'</a>';

	// image
	}else if(
		$field_object['type'] == 'image' &&
		$field_object['return_format'] === 'array' &&
		! empty( $field_value['url'] )
	){
		$field_value = '<img class="wcpt-acf-image" src="'. $field_value['url'] .'" />';
	
	// checkbox / select / radio
	}	else {

		if( is_array( $field_value ) ){ // needs to be converted to string

			if(
				! empty( $field_object['return_format'] ) &&
				$field_object['return_format'] == 'array' 
			){
				switch ( $field_object['type'] ) {
					case 'checkbox':
						$field_value = implode( ', ', array_column( $field_value, 'label' ) );
						break;

					default: // radio / select
						$field_value = $field_value['label'];
						break;
				}

			}else{
				$field_value = implode( ', ', $field_value );

			}
		}

	}

}else{
	return;
}

// iterate over custom relabel rules first
if( empty( $relabel_rules ) ){
	$relabel_rules = array();
}

foreach( $relabel_rules as $rule ){

	$use = false;

	if( $rule['compare'] == '=' && $rule['value'] != $field_value ){
		continue;

	}else if( $rule['compare'] == 'BETWEEN' ){
		if(
			( ! empty( $rule['min_value'] ) && (int) $rule['min_value'] > $field_value ) ||
			( ! empty( $rule['max_value'] ) && (int) $rule['max_value'] < $field_value )
		){
				continue;
		}

	}

	echo '<div class="wcpt-custom-field '. $html_class .'" ">' . wcpt_parse_2($rule['label']) . '</div>';

	return;
}

// empty value
if( empty( $empty_relabel ) ){
	$empty_relabel = '';
}

if( in_array( $field_value, array( '', null ), true ) ){
		if( $empty_relabel = wcpt_parse_2( $empty_relabel ) ){
			echo '<div class="wcpt-custom-field-empty">' . $empty_relabel . '</div>';
		}

		return;
}

// by default show value as text
if( empty( $display_as ) ){
	$display_as = 'text';
}

// for ACF show value as HTML
if( ! empty( $manager ) ){
	$display_as = 'html';
}

if( 
	empty( $manager ) && // not ACF
	gettype( $field_value ) !== 'string'
){
	$field_value = ''; // cannot handle anything but string
}

switch ($display_as) {
	case 'text':
		if( gettype( $field_value ) == 'string' ){
			// $field_value = htmlentities( $field_value );
			$field_value = esc_html( $field_value );
		}
		break;

	case 'html':
		// do shortcodes as well
		global $wp_embed;
		if( gettype( $field_value ) == 'string' ){
			$field_value = do_shortcode( $wp_embed->run_shortcode($field_value) );
		}
		break;

	case 'link':
		$label = rtrim( preg_replace("(^https?://)", "", $field_value ), '/' );
		if( empty( $link_target ) ){
			$link_target = '_self';
		}
		$field_value = '<a class="wcpt-cf-link" href="'. $field_value .'" target="'. $link_target .'">'. $label .'</a>';
		break;

	case 'phone_link':
		$field_value = '<a class="wcpt-cf-phone-link" href="tel:'. $field_value .'">'. $field_value .'</a>';
		break;

	case 'email_link':
		$field_value = '<a class="wcpt-cf-phone-link" href="mailto:'. $field_value .'">'. $field_value .'</a>';
		break;

	case 'pdf_link':
		if( empty( $pdf_val_type ) || $pdf_val_type == 'url' ){
			$url = $field_value;
		}else{
			$url = wp_get_attachment_url( $field_value );
		}

		if( ! $url ){
			if( $empty_relabel = wcpt_parse_2( $empty_relabel ) ){
				echo '<div class="wcpt-custom-field-empty">' . $empty_relabel . '</div>';
			}
			return;

		}else{
			$label = wcpt_parse_2( $pdf_link_label );
			$label = substr( substr( $label, 0, -6 ), 4 );
			$label = '<span' . $label . '</span>';

			$field_value = '<a class="wcpt-cf-pdf-link" href="'. esc_attr($url) .'" download="'. esc_attr( basename($url) ) .'">'. $label .'</a>';

			break;

		}

	case 'image':
		if( empty( $img_val_type ) || $img_val_type == 'url' ){
			$field_value = '<img class="wcpt-cf-image" src="'. $field_value .'" />';
		}else{
			if( empty( $media_img_size ) ){
				$media_img_size = 'thumbnail';
			}
			$img = wp_get_attachment_image( $field_value, $media_img_size );

			// no media img for id
			if( ! $img ){
				if( $empty_relabel = wcpt_parse_2( $empty_relabel ) ){
					echo '<div class="wcpt-custom-field-empty">' . $empty_relabel . '</div>';
				}
				return;
			}else{
				$style= "";
				if( ! empty( $img_max_width ) ){
					$style = 'style="max-width: '. $img_max_width .'px"';
				}

				$field_value = '<span class="wcpt-cf-image" '. $style .'>'. $img .'</span>';
			}
		}
		break;
}

echo wcpt_parse_2('<div class="wcpt-custom-field '. $html_class .'">' . $field_value . '</div>');
