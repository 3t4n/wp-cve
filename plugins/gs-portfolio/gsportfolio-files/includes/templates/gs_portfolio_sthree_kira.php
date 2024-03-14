<?php 

$portfolio_id = rand( 10,1000 );
$gs_p_button_txt = gs_p_get_option( 'gs_p_button_txt', 'gs_p_general', 'Portfolio Details' );
$gs_p_link_tar = gs_p_get_option( 'gs_p_link_tar', 'gs_p_general', '_blank' );
$gsp_popup_link = gs_p_get_option( 'gsp_popup_link', 'gs_p_advance', 'on' );
$gsp_singlep_link = gs_p_get_option( 'gsp_singlep_link', 'gs_p_advance', 'on' );
$gsp_extp_link = gs_p_get_option( 'gsp_extp_link', 'gs_p_advance', 'on' );

$output .= '<div class="container">';
$output .= '<div class="row clearfix gs_p_portfolio" id="gs_p_portfolio_'.$portfolio_id.'">';
	if ( $gs_p_loop->have_posts() ) {
		
		while ( $gs_p_loop->have_posts() ) {
			$gs_p_loop->the_post();				
			
			$gs_portfolio_id = get_post_thumbnail_id();
			$gs_portfolio_img_url = wp_get_attachment_image_src($gs_portfolio_id, 'gs-grid-thumb', true);
    		$gs_portfolio_thumb = $gs_portfolio_img_url[0];
    		$gs_portfolio_full_img = wp_get_attachment_url($gs_portfolio_id, 'full' );				
			$gs_portfolio_alt = get_post_meta($gs_portfolio_id,'_wp_attachment_image_alt',true);
		
			$gs_p_title = get_the_title();
			$gs_p_content = get_the_content();
			$gs_p_content = (strlen($gs_p_content) > 50) ? substr($gs_p_content,0,80).'...' : $gs_p_content;

			$gs_p_meta = get_post_meta( get_the_id() );	

			$output .= '<div class="grid single-gsp col-md-'. $port_cols_val .' col-sm-6 col-xs-6 mix">';
				$output .= '<figure class="'.$hover_effect.'">';
				$output .= '<img src="'.$gs_portfolio_thumb.'" alt="'. $gs_portfolio_alt .'"/>';
					$output .= '<figcaption>';
						$output .= '<h2>'. $gs_p_title .'</h2>';
						$output .= '<p>';
							if ( 'on' ==  $gsp_popup_link ) :
								$output .= '<a class="gs_p_pop open-popup-link" href="#gs_p_popup_'.get_the_id().'" data-effect="'.$popup_effect.'"><i class="fa fa-eye"></i></a>';
							endif;

							if ( 'on' ==  $gsp_singlep_link ) :
								$output .= '<a class="gs_p_link" href="'.get_permalink().'"><i class="fa fa-link"></i></a>';
							endif;

							if ( 'on' ==  $gsp_extp_link ) :
								if ($gs_p_meta['client_url'][0]) :
									$output .= '<a class="gs_p_link" href="'. $gs_p_meta['client_url'][0] .'" target="'.$gs_p_link_tar.'"><i class="fa fa-paper-plane-o"></i></a>';
								endif;
							endif;
						$output .= '</p>';	
					$output .= '</figcaption>';
				$output .= '</figure>';
			$output .= '</div>';

			// Popup
			$output .= '<div id="gs_p_popup_'.get_the_id().'" class="white-popup mfp-hide mfp-with-anim gs_p_popup">';
			$output .= '<div class="container">';
				$output .= '<div class="row">';
					$output .= '<div class="gs_p_popup_img col-md-6 col-sm-12">';
						$output .= '<img src="'.$gs_portfolio_full_img.'" alt="'. $gs_portfolio_alt .'">';
					$output .= '</div>';

					$output .= '<div class="gs_p_popup_content col-md-6 col-sm-12">';
						$output .= '<h2>'. get_the_title() .'</h2>';
						$output .= wpautop(get_the_content());
						
						if ($gs_p_meta['client_url'][0]) :
							$output .= '<a class="gsp_btn" href="'. $gs_p_meta['client_url'][0] .'" target="'.$gs_p_link_tar.'">'. $gs_p_button_txt .'</a>';
						endif;
					$output .= '</div>';

				$output .= '</div>'; // row
			$output .= '</div>'; // end of container
			$output .= '</div>'; 
			// Popup end

		} // end while loop

	} else {
		$output .= "No Portfolio Added!";
	}

	wp_reset_postdata();
$output .= '</div>'; // end row
$output .= '</div>'; // end container

return $output;