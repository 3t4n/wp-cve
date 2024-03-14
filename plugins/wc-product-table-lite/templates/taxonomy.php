<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// false attribute
if( empty( $taxonomy ) ){
	return;
}

$terms = wp_get_post_terms( $product->get_id(), $taxonomy );

if(
	empty( $terms ) ||
	is_wp_error( $terms )
){
	$terms = false;

}

// excludes array
$excludes_arr = array();
if( ! empty( $exclude_terms ) ){
	$excludes_arr = preg_split( '/\r\n|\r|\n/', $exclude_terms );
}

if( $terms && count( $terms ) ){
// associated terms exist

	if( empty ( $separator ) ){
 		$separator = '';
 	}else{
		$separator = wcpt_parse_2( $separator );
	}

	$output = '';

	if( empty( $relabels ) ){
		$relabels = array();
	}

	// sort terms prioritizing current filter
	global $wcpt_table_data;
	$table_id = $wcpt_table_data['id'];
	$filter_key = $table_id . '_tax_' . $taxonomy;
	if( ! empty( $_GET[ $filter_key ] ) && ! empty( $terms ) ){
		$_terms = array();
		foreach( $terms as $term ){
			$_terms[$term->term_id] = $term;
		}
		$terms = array_replace( array_intersect_key( array_flip( $_GET[ $filter_key ] ), $_terms ), $_terms );
	}

	$terms = array_values($terms);

	// relabel each term
	foreach( $terms as $index => $term ){

		// exclude
		if( 
			in_array( $term->name, $excludes_arr ) ||
			in_array( $term->slug, $excludes_arr )
		){
			continue;
		}

		// filtering
		if( 
			! empty( $_GET[ $filter_key ] ) &&
			is_array( $_GET[ $filter_key ] ) &&
			in_array( $term->term_taxonomy_id, $_GET[ $filter_key ] )
		){
			$filtering = 'true';
		}else{
			$filtering = 'false';
		}


		// is link
		$archive_url = get_term_link($term->term_id, $taxonomy);
		if( is_wp_error( $archive_url ) ){
			$archive_url = '';
		}

		if(
			! empty( $click_action ) &&
			$click_action == 'archive_redirect' &&
			$archive_url
		){
			$is_link = true;
		}else{
			$is_link = false;
		}

		// data attrs
		$common_data_attrs = 'data-wcpt-slug="'. $term->slug .'" data-wcpt-filtering="'. $filtering .'" data-wcpt-archive-url="'. $archive_url .'"';
		if( $is_link ){
			$common_data_attrs .= ' href="'. $archive_url .'" ';
		}

		// look for a matching rule
    $match = false;

    foreach( $relabels as $rule ){
			// skip default
			if( wcpt_is_default_relabel($rule) ){
				continue;
			}

      if( 
				! empty( $rule['term'] ) && wp_specialchars_decode( $term->name ) == $rule['term'] ||
				(
					function_exists('icl_object_id') &&
					! empty( $rule['ttid'] ) &&
					$term->term_taxonomy_id == icl_object_id( $rule['ttid'], $taxonomy, false )
				)
			){
        $match = true;

				// style
				wcpt_parse_style_2( $rule, '!important' );
				$term_html_class = 'wcpt-' . $rule['id'];

				// append
				$label = str_replace( '[term]', $term->name, wcpt_parse_2( $rule['label'] ) );

				// wrap in a / div tag
				if( $is_link ){
					$output .= '<a class="wcpt-taxonomy-term ' . $term_html_class . '" '. $common_data_attrs .'>' . $label . '</a>';
				}else{
					$output .= '<div class="wcpt-taxonomy-term ' . $term_html_class . '" '. $common_data_attrs .'>' . $label . '</div>';
				}

				break;
      }
		}

		if( ! $match ){
			$term_name = apply_filters( 'wcpt_term_name_in_column', $term->name, $term );

			if( $is_link ){
				$output .= '<a class="wcpt-taxonomy-term" '. $common_data_attrs .'>' . $term_name . '</a>';
			}else{
				$output .= '<div class="wcpt-taxonomy-term" '. $common_data_attrs .'>' . $term_name . '</div>';
			}
		}

		if( $index < count( $terms ) - 1 ){
			$output .= '<div class="wcpt-taxonomy-term-separator wcpt-term-separator">'. $separator .'</div>';
		}

  }

}else{
// no associated terms

	if( empty( $empty_relabel ) ){
		$empty_relabel = '';
	}

	$output = wcpt_parse_2($empty_relabel);

}

if( empty( $click_action ) ){
	$click_action = false;
}

if( 
	$click_action == 'trigger_filter' &&
	! wcpt_check_if_nav_has_filter( null, 'taxonomy_filter', $taxonomy ) 
){
	$click_action = false;
}

if( $click_action ){
	$html_class .= ' wcpt-'. $click_action .' ';
}

if( ! empty( $output ) ){
	?>
		<div
			class="wcpt-taxonomy <?php echo $html_class; ?>"
			data-wcpt-taxonomy="<?php echo $taxonomy; ?>"
		>
			<?php echo $output; ?>
		</div>
	<?php
}
