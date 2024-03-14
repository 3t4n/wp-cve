<?php 
//--------- Getting values from setting panel ---------------- //

function gs_p_get_option( $option, $section, $default = '' ) {

    $options = get_option( $section );
 
    if ( isset( $options[$option] ) ) {
        return $options[$option];
    }
 
    return $default;
}

// ---------- Function for GS Portfolo Category -------------

 function gs_portfolio_cat(){
    global $post;
    $terms = get_the_terms( $post->ID, 'portfolio-category' );
                                                   
    if ( $terms && ! is_wp_error( $terms ) ) :
            $gsp_cats_link = array();
     
            foreach ( $terms as $term ) {
                $gsp_cats_link[] = $term->name;
            }
             
            $gsp_cats_link = str_replace(' ', '-', $gsp_cats_link);
            $gsp_cats = join( " ", $gsp_cats_link );
            $gsp_cats = strtolower($gsp_cats);      
    endif;
    return $gsp_cats;      
} 

// ---------- Shortcode [gs_portfolio] -------------

add_shortcode( 'gs_portfolio', 'gs_portfolio_shortcode' );

function gs_portfolio_shortcode( $atts ) {
	$gs_p_hover = gs_p_get_option( 'gs_p_hover', 'gs_p_general', 'effect-sadie' );
	$gs_p_popup = gs_p_get_option( 'gs_p_popup', 'gs_p_general', 'mfp-move-horizontal' );
	$gs_p_cols = gs_p_get_option( 'gs_p_cols', 'gs_p_general', 4 );
	
	extract(shortcode_atts( 
			array(
			'posts' 		=> -1,
			'order'			=> 'DESC',
			'orderby'   	=> 'date',
			'hover_effect' 	=> $gs_p_hover,
			'popup_effect' 	=> $gs_p_popup,
			'port_cols_val' => $gs_p_cols,
			'cats_name'		=> ''
			), $atts 
		));

	$gs_p_loop = new WP_Query(
		array(
			'post_type'			=> 'gs-portfolio',
			'order'				=> $order,
			'orderby'			=> $orderby,
			'posts_per_page'	=> $posts,
			)
		);
	
	$output = '';
		$output = '<div class="wrap gs_portfolio_area '. esc_attr($hover_effect) .'">';

			if ( $hover_effect == 'effect-sadie') {
				include GSPORTFOLIO_FILES_DIR . '/includes/templates/gs_portfolio_structure_one.php';
			}
			if ( $hover_effect == 'effect-julia') {
				include GSPORTFOLIO_FILES_DIR . '/includes/templates/gs_portfolio_stwo_julia.php';
			}
			if ( $hover_effect == 'effect-kira' || $hover_effect == 'effect-winston' ) {
				include GSPORTFOLIO_FILES_DIR . '/includes/templates/gs_portfolio_sthree_kira.php';
			}
			if ( $hover_effect == 'effect-zoe' ) {
				include GSPORTFOLIO_FILES_DIR . '/includes/templates/gs_portfolio_sfour_zoe.php';
			} 
		
		$output .= '</div>'; // end wrap
	return $output;
}


// ---------- Mixitup -------------

function gs_p_mixitup_trigger(){
?>
	<script type="text/javascript">
		jQuery(function(){
			jQuery('.gs_p_portfolio').mixItUp({
				animation: {
					duration: 1000,
					// effects: 'fade stagger(34ms) translateY(10%) scale(0.01)',
					// easing: 'cubic-bezier(0.6, -0.28, 0.735, 0.045)'
				}
			});

		});
	</script>

<?php
}
add_action( 'wp_footer','gs_p_mixitup_trigger' );