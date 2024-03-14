<?php

    if( !defined( 'ABSPATH' ) ){
        exit;
    }
	$html = '';
    $html .='<div class="content_area-'.$postid.'">';
    $carpro_slider_items   				= get_post_meta($postid, 'carpro_slider_items', true);
    $carpro_slider_itemsdesktop     	= get_post_meta($postid, 'carpro_slider_itemsdesktop', true);
    $carpro_slider_itemsdesktopsmall	= get_post_meta($postid,'carpro_slider_itemsdesktopsmall', true);
    $carpro_slider_itemsmobile      	= get_post_meta($postid, 'carpro_slider_itemsmobile', true); 
    $carpro_slider_loop    				= get_post_meta($postid, 'carpro_slider_loop', true);
    $carpro_slider_margin   			= get_post_meta($postid, 'carpro_slider_margin', true);
    $carpro_slider_autoplay         	= get_post_meta($postid, 'carpro_slider_autoplay', true);
    $carpro_slider_autoplay_speed  	 	= get_post_meta($postid, 'carpro_slider_autoplay_speed', true);
    $carpro_slider_autoplaytimeout  	= get_post_meta($postid, 'carpro_slider_autoplaytimeout', true);
    $carpro_slider_navigation         	= get_post_meta($postid, 'carpro_slider_navigation', true);
    $carpro_slider_navigation_position  = get_post_meta($postid, 'carpro_slider_navigation_position', true);
    $carpro_slider_pagination           = get_post_meta($postid, 'carpro_slider_pagination', true);
    $carpro_slider_paginationposition   = get_post_meta($postid, 'carpro_slider_paginationposition', true);
    $carpro_slider_stophover            = get_post_meta($postid, 'carpro_slider_stophover', true);
    $carpro_slider_navtext_color        = get_post_meta($postid, 'carpro_slider_navtext_color', true);
    $carpro_slider_navtext_hovercolor   = get_post_meta($postid, 'carpro_slider_navtext_hovercolor', true);
    $carpro_slider_navbg_color        	= get_post_meta($postid, 'carpro_slider_navbg_color', true);
    $carpro_slider_navbg_hovercolor     = get_post_meta($postid, 'carpro_slider_navbg_hovercolor', true);
    $carpro_slider_pagination_color     = get_post_meta($postid, 'carpro_slider_pagination_color', true);
    $carpro_slider_pagination_bgcolor   = get_post_meta($postid, 'carpro_slider_pagination_bgcolor', true);
    $carpro_slider_pagination_style    	= get_post_meta($postid, 'carpro_slider_pagination_style', true);
	
    $carpro_slider_hide_img         	= get_post_meta($postid, 'carpro_slider_hide_img', true);
    $carpro_slider_img_height         	= get_post_meta($postid, 'carpro_slider_img_height', true);
    $carpro_slider_hide_img_captions 	= get_post_meta($postid, 'carpro_slider_hide_img_captions', true);
    $carpro_slider_capbg_color 			= get_post_meta($postid, 'carpro_slider_capbg_color', true);
    $carpro_slider_hide_link 			= get_post_meta($postid, 'carpro_slider_hide_link', true);
    $carpro_slider_overlay_color 		= get_post_meta($postid, 'carpro_slider_overlay_color', true);
    $carpro_slider_overlayicons_color 	= get_post_meta($postid, 'carpro_slider_overlayicons_color', true);
    $carpro_slider_overlaycolor_icons 	= get_post_meta($postid, 'carpro_slider_overlaycolor_icons', true);
	
	# Excerpt color 
    $carpro_slider_excerpt_color 		= get_post_meta($postid, 'carpro_slider_excerpt_color', true);
    $carpro_excerptbg_color 			= get_post_meta($postid, 'carpro_excerptbg_color', true);
	
	# Content
	$carpro_contentclr 					= get_post_meta($postid, 'carpro_contentclr', true);
	$carpro_con_align 					= get_post_meta($postid, 'carpro_con_align', true);
	$carpro_context_size 				= get_post_meta($postid, 'carpro_context_size', true);
	
	# bg color 
	$carprobg_color 					= get_post_meta($postid, 'carprobg_color', true);

	# Date & Time
	$carpro_datetime_hide 				= get_post_meta($postid, 'carpro_datetime_hide', true);
	$carpro_date_align 					= get_post_meta($postid, 'carpro_date_align', true);
	$carpro_dattext_size 				= get_post_meta($postid, 'carpro_dattext_size', true);
	$carpro_datetclr 					= get_post_meta($postid, 'carpro_datetclr', true);

	# Caption color settings
	$carpro_slider_captext_color 		= get_post_meta($postid, 'carpro_slider_captext_color', true);
    $carpro_slider_captext_size 		= get_post_meta($postid, 'carpro_slider_captext_size', true);
    $carpro_slider_captext_align 		= get_post_meta($postid, 'carpro_slider_captext_align', true);

    
	$html .='<script>
            jQuery(document).ready(function($) {
              $("#tpcarouselpro-'.$postid.'").owlCarousel({
                autoplay: '.$carpro_slider_autoplay.',
                autoplaySpeed: 700,
                autoplayHoverPause: '.$carpro_slider_stophover.',
                margin: '.$carpro_slider_margin.',
                autoplayTimeout: '.$carpro_slider_autoplaytimeout.',
                nav : '.$carpro_slider_navigation.',
                navText:["<",">"],
                dots: '.$carpro_slider_pagination.',
                smartSpeed: 450,
                clone:true,
                loop: '.$carpro_slider_loop.',
                responsive:{
                    0:{
                      items:'.$carpro_slider_itemsmobile.',
                    },
                    678:{
                      items:'.$carpro_slider_itemsdesktopsmall.',
                    },
                    980:{
                      items:'.$carpro_slider_itemsdesktop.',
                    },
                    1199:{
                      items:'.$carpro_slider_items.',
                    }
                }
              });
            });
          </script>';
      
    $html .='<style>';

	$html .='
		#tpcarouselpro-'.$postid.' #tpcarouselpro-theme7 a img{
		  height:'.$carpro_slider_img_height.'px;
		  box-shadow:none;
		}';
	
	# navigation & pagination style
	if($carpro_slider_navigation_position == 1){
		$html .='
		#tpcarouselpro-'.$postid.' .owl-controls .owl-nav{
			margin-right: 0;
			margin-top: 0;
			position: absolute;
			right: 0;
			top: -50px;
		}';
	}
	$html .='
		#tpcarouselpro-'.$postid.' .owl-nav .owl-prev{
			background: #'.$carpro_slider_navbg_color.';
			border-radius: 0;
			color: #'.$carpro_slider_navtext_color.';
			cursor: pointer;
			display: inline-block;
			font-size: 14px;
			margin: 0 4px 0 0;
			padding: 5px;
			width: 25px;
		}';
	$html .='
		#tpcarouselpro-'.$postid.' .owl-nav .owl-next{
			background: #'.$carpro_slider_navbg_color.';
			border-radius: 0;
			color: #'.$carpro_slider_navtext_color.';
			cursor: pointer;
			display: inline-block;
			font-size: 14px;
			margin: 0;
			padding: 5px;
			width: 25px;
		}';
	$html .='	
		#tpcarouselpro-'.$postid.' .owl-nav .owl-next:hover, #tpcarouselpro-'.$postid.' .owl-nav .owl-prev:hover {
		  background: #'.$carpro_slider_navbg_hovercolor.';
		  color: #'.$carpro_slider_navtext_hovercolor.';
		}';
	$html .='	
		#tpcarouselpro-'.$postid.'.owl-theme .owl-dots {
		  text-align: '.$carpro_slider_paginationposition.';
		  margin-top: 10px;
		}';
	if($carpro_slider_pagination_style == 1){
		$html .='
		#tpcarouselpro-'.$postid.'.owl-theme .owl-dots .owl-dot span {
		  backface-visibility: visible;
		  background: #'.$carpro_slider_pagination_bgcolor.';
		  border-radius: 30px;
		  display: block;
		  height: 10px;
		  margin: 5px 7px;
		  transition: opacity 200ms ease 0s;
		  width: 10px;
		}';
	}
	$html .='	
		#tpcarouselpro-'.$postid.'.owl-theme .owl-dots .owl-dot.active span, .owl-theme .owl-dots .owl-dot:hover span {
		  background: #'.$carpro_slider_pagination_color.';
		}';

	$html .='#tpcarouselpro-'.$postid.' #tpcarouselpro-theme7 {
				background: '.$carprobg_color.';
			}';
	$html .='#tpcarouselpro-'.$postid.' #tpcarouselpro-theme7 .tpcarouselpro-theme7-title h3{
				color: '.$carpro_slider_captext_color.';
				font-size: '.$carpro_slider_captext_size.'px;
				text-align: '.$carpro_slider_captext_align.';
			}'; 
	$html .='#tpcarouselpro-'.$postid.' #tpcarouselpro-theme7 .excerpt_area a{
				color: #'.$carpro_slider_excerpt_color.';
				background: #'.$carpro_excerptbg_color.';
			}'; 
	$html .='#tpcarouselpro-'.$postid.' #tpcarouselpro-theme7 .content_area, .excerpt_area{
				color: #'.$carpro_contentclr.';
				text-align: '.$carpro_con_align.';
				font-size: '.$carpro_context_size.'px;
			}';
	$html .='#tpcarouselpro-'.$postid.' #tpcarouselpro-theme7 .tpcarouselpro-theme7-post .tpcarouselpro-theme7-date{
				color: #'.$carpro_datetclr.';
				text-align: '.$carpro_date_align.';
				font-size: '.$carpro_dattext_size.'px;
			}'; 
		
		
	$html .='</style>';
    require_once('excerpt.php'); 

    $html .= '<div id="tpcarouselpro-'.$postid.'" class="owl-carousel owl-theme">';
    while ($query->have_posts()) : $query->the_post();

	$web_url 		= get_post_meta( $post->ID, 'any_web_links', true );
	$web_url_target = get_post_meta( $post->ID, 'carpro_slider_ulr_target', true );
	$post_thumb 	= wp_get_attachment_url( get_post_thumbnail_id($post->ID) );

 	$html .='
		<div id="tpcarouselpro-theme7">';
				if($carpro_slider_hide_img == 1){
					if($carpro_slider_hide_link == 1){
						$html.='<a href="'.esc_url(get_the_permalink()).'">
							<img src="'.$post_thumb.'" alt="'.esc_attr(get_the_title()).'" />
						</a>';
					}
					else{
						$html.='<img src="'.$post_thumb.'" alt="'.esc_attr(get_the_title()).'" />';
					}
				}
			$html.='
			<div class="tpcarouselpro-theme7-post">';
					if($carpro_datetime_hide == 1){
						$html.='<div class="tpcarouselpro-theme7-date">'.get_the_date().'</div>';
					}
					if($carpro_slider_hide_img_captions == 1){
						$html.='<div class="tpcarouselpro-theme7-title">
								<h3>'.get_the_title().'</h3>
						</div>';
					}
				$html.='
			</div>
			<div class="tpcarouselpro-theme7-content">
				<div class="content_area">'.caropro_get_excerpt($excerpt_lenght).'</div>
				<div class="excerpt_area">
					<a href="'.esc_url(get_the_permalink()).'">'.$btn_readmore.'</a>
				</div>
				
			</div>
			
		</div>
	';

    endwhile;
    $html .='</div>';
    $html .='</div>';