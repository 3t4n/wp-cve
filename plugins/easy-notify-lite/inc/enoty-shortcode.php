<?php

function easynotify_shortcode( $attsn ) {
	
	if ( enoty_get_option( 'easynotify_disen_plug' ) != '1' ) {
		return false;
		}
	
	ob_start();
	
	wp_enqueue_script( 'enoty-enotybox-js' );
	wp_enqueue_script( 'enoty-cookie-front' );
	wp_enqueue_style( 'enoty-enotybox-style' );
	wp_enqueue_style( 'enoty-frontend-style' );
	
	extract( shortcode_atts( array(
		'id' => '',
		), $attsn ) );	

	$fnlid = explode(",", $id);
	
	$args = array(
		'post_type' => 'easynotify',
		'post__in'		=>  $fnlid
		);

	$noty_query = new WP_Query( $args );

	if ( $noty_query->have_posts() ):

		echo '<div style="display: none !important;" id="inline-container-'.$id.'">';	

			while ( $noty_query->have_posts() ) : $noty_query->the_post();

				echo'<a style="display: none !important;" href="#noty-'.get_the_id().'" id="launcher-'.get_the_id().'"></a>';
				echo'<div style="display: none !important;"><div class="enoty-inline" id="noty-'.get_the_id().'"></div></div>';
				
				easynotify_ajax_script( get_the_id(), $val = '' ); // Let's generate the Notify Script

				endwhile;
		else:
			echo '<div>No Notify!</div>';	
			$contnt = ob_get_clean();
			return $contnt;  

	endif;
	
	wp_reset_postdata();

	echo '</div>';

	// Apply Individual Layout
	$lyot = get_post_meta( $id, 'enoty_cp_layoutmode', true );
	$layout = preg_replace( '/\\.[^.\\s]{3,4}$/', '', $lyot );	
	
	add_action( 'enoty_wp_print_layout', 'easynotify_apply_layout_style' );
	add_action( 'wp_print_styles', 'easynotify_dynamic_styles' );
	do_action( 'enoty_wp_print_layout', str_replace('_', '-', $layout ) );
	do_action( 'wp_print_styles', $id );
	easynotify_render_custom_css();
	
	$content = ob_get_clean();
	return $content;

}

add_shortcode( 'easy-notify', 'easynotify_shortcode' );