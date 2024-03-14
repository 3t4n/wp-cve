<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_image_caption_hover extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract( shortcode_atts( array(
					'ihe_heading'					=> "",
				    "caption_url"					=> '',
				    "caption_url_target"			=> '',
				    "ihe_image"						=> '',
				    "caption_style"					=> 'circle',
				    "hover_effect"					=> 'effect1',
				    "caption_direction"				=> 'left_to_right',
				), $atts ) );

				wp_enqueue_style( 'wdo-image-caption-css', ULT_URL.'assets/css/image-hover.css');

				$content = wpb_js_remove_wpautop($content, true);
				if ($ihe_image != '') {
					$image_url = wp_get_attachment_url( $ihe_image );		
				}
				
				ob_start();
				?>
				<div class="ih-item <?php if(($caption_style=='square' && $hover_effect=='effect8')|| ($caption_style=='circle' && $hover_effect=='effect6') ){ echo 'scale_up';} ?> <?php echo $caption_style; ?> <?php echo $hover_effect; ?> <?php echo $caption_direction; ?>" >
					<a class="taphover"  href="<?php echo ( $caption_url != '') ? $caption_url  : 'javascript:void(0)' ;  ?>" target="<?php echo $caption_url_target; ?>"> 
					<?php if($caption_style=='circle' && $hover_effect=='effect1' ){ echo '<div class="spinner"></div>';} ?>
					  
					  <div class="img"><img class="responsiveimage" src="<?php echo $image_url; ?>" alt="img"></div>
					  <?php if($caption_style=='square' && $hover_effect=='effect4' ){ echo '<div class="mask1"></div><div class="mask2"></div>';} ?>

					  <?php if($caption_style=='circle' && $hover_effect=='effect8' ){?>
					  <div class="info-container" >
					    <div class="info">
					      <h3><?php echo $ihe_heading; ?></h3>
					      <?php echo $content; ?>
					    </div>
					  </div>
					  <?php }else{ ?>
						<div class="info">
					    <div class="info-back" >
					      <h3><?php echo $ihe_heading; ?></h3>
					      <?php echo $content; ?>
					    </div>
					  </div>
					  <?php } ?>
					</a>
				</div>

				<?php
				return ob_get_clean();
			}
		}
	}

	$hoverEffects = array(
		'Effect 1'	=>	'effect1',
		'Effect 2'	=>	'effect2',
		'Effect 3'	=>	'effect3',
		'Effect 4'	=>	'effect4',
		'Effect 5'	=>	'effect5',
		'Effect 6'	=>	'effect6',
		'Effect 7'	=>	'effect7',
		'Effect 8'	=>	'effect8',
		'Effect 9'	=>	'effect9',
		'Effect 10'	=>	'effect10',
		'Effect 11'	=>	'effect11',
		'Effect 12'	=>	'effect12',
		'Effect 13'	=>	'effect13',
		'Effect 14'	=>	'effect14',
		'Effect 15'	=>	'effect15',
		'Effect 16'	=>	'effect16',
		'Effect 17'	=>	'effect17',
		'Effect 18'	=>	'effect18',
		'Effect 19'	=>	'effect19',
		'Effect 20'	=>	'effect20',
	);
	if ( function_exists( "vc_map" ) ) {
		vc_map( array(
			'name'		=> 'Image Hover Effects',
			"description" => __("Add images with captions.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_image_caption_hover',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/image-hover-effects-icon.png',
			'allowed_container_element' => 'vc_row',
			'params' => array(
					array(
						"type" 			=> 	"attach_image",
						"heading" 		=> 	__("Image"),
						"param_name" 	=> 	"ihe_image",
						"description" 	=> 	__("Select the image"),
						"group" 		=> 'Image',
					),

					array(
						"type" 			=> "textfield", 
						"heading" 		=> __("Caption Heading"),
						"param_name" 	=> "ihe_heading",
						"description" 	=> __("Give heading for caption"),
						"group" 		=> 'Caption',
					),

					array(
						"type" 			=> "textarea_html",
						"heading" 		=> __("Caption Description"),
						"param_name" 	=> "content",
						"description" 	=> __("Caption description for Image.You can also use html."),
						"group" 		=> 'Caption',
					),

					array(
						"type" 			=> "textfield",
						"heading" 		=> __("URL"),
						"param_name" 	=> "caption_url",
						"description" 	=> __("Leave blank to disable link"),
						"group" 		=> 'Links',
					),
					array(
						"type" 			=> "textfield",
						"heading" 		=> __("Link Target"),
						"param_name" 	=> "caption_url_target",
						"description" 	=> __("Write _blank for opening link in new window and _self for same window."),
						"group" 		=> 'Links',
					),

					// Hover Effects Settings


					array(
						"type" => "dropdown",
						"heading" => "Hover Style",
						"param_name" => "caption_style",
						"value" => array(
							"Circle" => "circle",
							"Square" => "square",
						),
						"description" => "",
						"group" 		=> 'Hover Effects',
					),
					array(
						"type" 			=> "dropdown",
						"heading" 		=> __("Hover Effect"),
						"param_name" 	=> "hover_effect",
						"description" 	=> __("Select the hover effect"),
						"value" 		=> $hoverEffects,
						"group" 		=> 'Hover Effects',
					),


					array(
						"type" => "dropdown",
						"heading" => "Animation Direction",
						"param_name" => "caption_direction",
						"group" 		=> 'Hover Effects',
						"value" => array(
							"Left To Right" => "left_to_right",
							"Right To Left" => "right_to_left",
							"Top To Bottom" => "top_to_bottom",
							"Bottom To Top" => "bottom_to_top",
						),
						"description" => "Select direction of Caption on hover",
					),
					

			)
		) );
	}
 ?>