<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_text_over_image extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract( shortcode_atts( array(
					'caption_heading'				=> "",
				    "caption_url"					=> '',
				    "caption_url_target"			=> '',
				    "ihe_image"						=> '',
				    "hover_effect"					=> '',
				), $atts ) );

				wp_enqueue_style( 'wdo-text-over-image-css', ULT_URL.'assets/css/image-hover.css');

				if ($ihe_image != '') {
					$image_url = wp_get_attachment_url( $ihe_image );		
				}
				$content = preg_replace('#^<\/p>|<p>$#', '', $content);
				ob_start();
				?>
				<div class="ih-item <?php echo $hover_effect; ?>" >
					<a class="taphover"  href="<?php echo ( $caption_url != '') ? $caption_url  : 'javascript:void(0)' ;  ?>" target="<?php echo $caption_url_target; ?>">
					  
						<div class="img">
							<div class="wdo-overlay"></div>
							<h3 class="title-over-image"><?php echo $caption_heading; ?></h3>
							<img class="responsiveimage" src="<?php echo $image_url; ?>" alt="img">
						</div>
						<div class="info">
						    <p>
						    	<?php echo $content; ?>
						    </p>
						</div>
					</a>
				</div>

				<?php
				return ob_get_clean();
			}
		}
	}

	$hoverEffects = array(
    'square effect1 left and right'      =>      'square effect1 left_and_right',
    'square effect1 top to bottom'      =>      'square effect1 top_to_bottom',
    'square effect1 bottom to top'      =>      'square effect1 bottom_to_top',
    'square effect2'                    =>      'square effect2',
    'square effect3 bottom to top'      =>      'square effect3 bottom_to_top',
    'square effect3 top to bottom'      =>      'square effect3 top_to_bottom',
    'square effect4'                    =>      'square effect11 bottom_to_top',
    'square effect5 left to right'      =>      'square effect5 left_to_right',
    'square effect5 right to left'      =>      'square effect5 right_to_left',
    'square effect6 from left and right'=>      'square effect6 from_left_and_right',
    'square effect6 top to bottom'      =>      'square effect6 top_to_bottom',
    'square effect6 bottom to top'      =>      'square effect6 bottom_to_top',
    'square effect7'                    =>      'square effect7',
    'square effect8 scaleup'            =>      'square effect8 scale_up',
    'square effect8 scaledown'          =>      'square effect8 scale_down',
    'square effect9 left to right'     =>      	'square effect12 left_to_right',
    'square effect9 right to left'     =>      	'square effect12 right_to_left',
    'square effect9 top to bottom'     =>      	'square effect12 top_to_bottom',
    'square effect9 bottom to top'     =>      	'square effect12 bottom_to_top',
    'square effect10 left to right'     =>      'square effect10 left_to_right',
    'square effect10 right to left'     =>      'square effect10 right_to_left',
    'square effect10 top to bottom'     =>      'square effect10 top_to_bottom',
    'square effect10 bottom to top'     =>      'square effect10 bottom_to_top',
        
    );
	if ( function_exists( "vc_map" ) ) {
		vc_map( array(
			'name'		=> 'Text Over Image',
			"description" => __("Show text over image.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_text_over_image',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/text-over-image-icon.png',
			'allowed_container_element' => 'vc_row',
			'params' => array(
				array(
						"type" 			=> 	"attach_image",
						"heading" 		=> 	__("Image"),
						"param_name" 	=> 	"ihe_image",
						"description" 	=> 	__("Select the image"),
						"group" 		=> 	'Image',
					),

					array(
						"type" 			=> "textfield", 
						"heading" 		=> __("Static Title"),
						"param_name" 	=> "caption_heading",
						"description" 	=> __("Give title that would be displayed over image."),
						"group" 		=> 'Caption',
					),

					array(
						"type" 			=> "textarea_html",
						"heading" 		=> __("Description Text"),
						"param_name" 	=> "content",
						"description" 	=> __("Description that would appear when hover over image."),
						"group" 		=> 'Caption',
					),

					/*** LightBox & Linking Block ***/

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
						"type" 			=> "dropdown",
						"heading" 		=> __("Hover Effect"),
						"param_name" 	=> "hover_effect",
						"description" 	=> __("Select the hover effect"),
						"group" 		=> 'Hover Effects',
						'save_always' => true,
						"value" 		=> $hoverEffects,
					),

					/*** Styling Block ***/

					array(
						"type" => "html",
						"group" => "Demo",
						"heading" => "<h3 style='padding: 10px;background: #2b4b80;text-align:center;'><a style='color: #fff;text-decoration:none;' target='_blank' href='https://demo.webdevocean.com/text-over-image/' >Click to See Demo</a>",
						"param_name" => "demo",
					),
					

			)
		) );
	}
 ?>