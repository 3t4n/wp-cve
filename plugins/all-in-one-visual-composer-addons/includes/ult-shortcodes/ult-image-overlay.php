<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_image_overlay extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract(shortcode_atts( array(
				    "wdo_overlay_image"	=> '',
				    "wdo_overlay_title"	=> '',
				    "wdo_overlay_button_text"	=> '',
				    "wdo_overlay_button_link"	=> '',
				), $atts));

				wp_enqueue_style( 'wdo-bootstrap4-css', ULT_URL.'assets/css/bootstrap4.min.css');

				$content = wpb_js_remove_wpautop($content, true);

				if ($wdo_overlay_image != '') {
					$overlay_image_url = wp_get_attachment_url( $wdo_overlay_image );		
				}
				ob_start(); ?>
					<!-- https://bootsnipp.com/snippets/mMBqZ -->
					<div class="wdo-overlay-container">
						<div class="card mb-4 bg-dark text-white">
							<?php if ( $wdo_overlay_image != '' ): ?>
				            	<img class="card-img" src="<?php echo $overlay_image_url; ?>" alt="Card image">
				            <?php endif; ?>
				            <div class="card-img-overlay text-center">
				               <h5 class="card-title"><?php echo $wdo_overlay_title; ?></h5>
				               <p class="card-text"><?php echo $content; ?></p>
				               <?php if ( $wdo_overlay_button_text != '' ): ?>
									<a href="<?php echo ( $wdo_overlay_button_link !='' ) ? $wdo_overlay_button_link : 'javascript:void(0)'; ?>" class="btn btn-outline-light btn-sm"><?php echo $wdo_overlay_button_text; ?></a>
								<?php endif; ?>
				            </div>
				         </div>
					</div>
			<?php
			return ob_get_clean();
			}
		}
	}


	if ( function_exists( "vc_map" ) ) {
		vc_map( array(
			'name'		=> 'Image Overlay',
			"description" => __("Add content over image.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_image_overlay',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/image-overlay-icon.png',
			'params' => array(

					array(
						"type" 			=> 	"attach_image",
						"heading" 		=> 	__("Overlay Image"), 
						"param_name" 	=> 	"wdo_overlay_image",
						"description" => "This image would be use as a background image for the content.Try to use banner size image for better result.",
						"group" 		=> 'Image',
					),

					array(
						"type" => "textfield",
						"heading" => "Title",
						"param_name" => "wdo_overlay_title",
						"group" 		=> 'Content',
					),

					array(
						"type" => "textarea_html",
						"heading" => "Content",
						"param_name" => "content",
						"description" => "Give content.You can use create your own content using html.",
						"group" 		=> 'Content',
					),

					array(
						"type" => "textfield",
						"heading" => "Button Text",
						"param_name" => "wdo_overlay_button_text",
						"description" => "Give text to show on button.",
						"group" 		=> 'Button',
					),

					array(
						"type" => "textfield",
						"heading" => "Link URL",
						"param_name" => "wdo_overlay_button_link",
						"description" => "Give link that would open when you click over button.",
						"group" 		=> 'Button',
					),
					

			)
		) );
	}
 ?>