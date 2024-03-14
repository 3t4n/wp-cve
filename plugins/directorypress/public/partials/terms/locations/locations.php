<?php
 
	global $DIRECTORYPRESS_ADIMN_SETTINGS,$directorypress_object;
	
	if ($instance->location_style == 'default'){
		directorypress_display_template('partials/terms/locations/parts/location-style-default.php', array('instance' => $instance, 'terms' => $terms));
	}elseif ($instance->location_style == 'custom'){
		directorypress_display_template('partials/terms/locations/parts/location-style-custom.php', array('instance' => $instance, 'terms' => $terms));
	}
	
	// custom styling
	$id = $instance->args['id'];
		$location_bg = $instance->location_bg;
		$location_bg_image = $instance->location_bg_image;
		$gradientbg1 = $instance->gradientbg1;
		$gradientbg2 = $instance->gradientbg2;
		$opacity1 = $instance->opacity1;
		$opacity2 = $instance->opacity2;
		$gradient_angle = $instance->gradient_angle;
		$location_width = (isset($instance->location_width) && !empty($instance->location_width))? ('width:' . esc_attr($instance->location_width) . '%;') : '';
		$location_height = (isset($instance->location_height) && !empty($instance->location_height))? ('height:' . esc_attr($instance->location_height) . 'px;') : '';
		
		
		
		if ($instance->location_padding) {
		   DirectoryPress_Static_Files::addCSS('
				.directorypress-masonry-grid{
					margin:-'.$instance->location_padding .'px;
					box-sizing: border-box;
				}
				.directorypress-masonry-grid:after,.directorypress-masonry-grid:before{
					clear:both;
					content:"";
					display:table;
				}
			', $id);
		}
		if ( $instance->location_style && (!empty($gradient_angle) && !empty($gradientbg1) && !empty($gradientbg2))) {
			$opacitybg1 = '0.'.$opacity1;
			$opacitybg2 = '0.'.$opacity2;
			$gradient_bg_color1 = directorypress_convert_rgba($gradientbg1, $opacitybg1);
			$gradient_bg_color2 = directorypress_convert_rgba($gradientbg2, $opacitybg2);
			
			DirectoryPress_Static_Files::addCSS('
				.directorypress-location-item{background-size:cover !important;}	
				.directorypress-location-item-holder{transition:all 0.5s ease;}
				#loaction-styles'.$id.'.location-style-custom .directorypress-location-item:hover .directorypress-location-item-holder {
					background: -webkit-linear-gradient('.$gradient_angle.'deg, '.$gradient_bg_color1.', '.$gradient_bg_color2.') !important;
					background: -moz-linear-gradient('.$gradient_angle.'deg, '.$gradient_bg_color1.', '.$gradient_bg_color2.') !important;
					background: -o-linear-gradient('.$gradient_angle.'deg, '.$gradient_bg_color1.', '.$gradient_bg_color2.') !important;
					background: -ms-linear-gradient('.$gradient_angle.'deg, '.$gradient_bg_color1.', '.$gradient_bg_color2.') !important;
					background: linear-gradient('.$gradient_angle.'deg, '.$gradient_bg_color1.', '.$gradient_bg_color2.') !important;
					transition:all 2.5s ease;
				}

			', $id);
		}
		
		if(!empty($location_bg_image)){
			$locationbg = 'url('.$location_bg_image.')';
		}else{
			$locationbg = $location_bg;
		}
		$gradient_bg_color3 = 'rgba(0,0,0,0)';
		$gradient_bg_color4 = 'rgba(0,0,0,0)';
		
		DirectoryPress_Static_Files::addCSS('
			#loaction-styles'.$id.'.directorypress-locations-columns.location-style-custom .directorypress-location-item{ 
				background:'.$locationbg.';
				height:100%;
				
			}
			#loaction-styles'.$id.'.location-style-custom.grid-item{
				'.$location_width.'
				'.$location_height.'
				float:left;
			}
			.location-style2.directorypress-locations-columns .directorypress-location-item  .directorypress-parent-location a:before,
			.listings.location-archive .directorypress-locations-columns .directorypress-location-item  .directorypress-parent-location a:before{
				background-image: url("'.DIRECTORYPRESS_RESOURCES_URL .'images/directorypress-location-icon.png");
			}
			.widget #loaction-styles'.$id.'.grid-item{
				width:100% !important;
				height:100% !important;
			}
			@media screen and (max-width:480px) {
				#loaction-styles'.$id.'.location-style-custom.grid-item{
					width:100%;
					'.$location_height.'
					float:left;
				}
			}
			.widget #loaction-styles'.$id.'.location-style2 .directorypress-location-item .directorypress-location-item-holder,
			.widget #loaction-styles'.$id.'.location-style4 .directorypress-location-item .directorypress-location-item-holder,
			.widget #loaction-styles'.$id.' .directorypress-location-item{
				background: -webkit-linear-gradient(0deg, '.$gradient_bg_color3.', '.$gradient_bg_color4.') !important;
				background: -moz-linear-gradient(0deg, '.$gradient_bg_color3.', '.$gradient_bg_color4.') !important;
				background: -o-linear-gradient(0deg, '.$gradient_bg_color3.', '.$gradient_bg_color4.') !important;
				background: -ms-linear-gradient(0deg, '.$gradient_bg_color3.', '.$gradient_bg_color4.') !important;
				background: linear-gradient(0deg, '.$gradient_bg_color3.', '.$gradient_bg_color4.') !important;
				transition:all 2.5s ease;
			}
			.widget #loaction-styles'.$id.'{padding:0 !important;}
			.directorypress-masonry-grid .grid-sizer{
				'.$location_width.'
			}
		', $id);