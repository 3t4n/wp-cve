<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_cards extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract(shortcode_atts( array(
				    "wdo_card_image"	=> '',
				    "wdo_card_title"	=> '',
				    "wdo_card_content"	=> '',
				    "wdo_button_text"	=> '',
				    "wdo_button_link"	=> '',
				), $atts));
				wp_enqueue_style( 'wdo-bootstrap4-css', ULT_URL.'assets/css/bootstrap4.min.css');
				if ($wdo_card_image != '') {
					$card_image_url = wp_get_attachment_url( $wdo_card_image );		
				}
				ob_start(); ?>
					<!-- https://bootsnipp.com/snippets/mMBqZ -->
					<div class="wdo-card-container">
						<div class="card mb-4">
							<?php if ( $wdo_card_image != '' ): ?>
				            	<img class="card-img-top" src="<?php echo $card_image_url; ?>" alt="Card image cap">
				            <?php endif; ?>
				            <div class="card-body">
				               <h5 class="card-title"><?php echo $wdo_card_title; ?></h5>
				               <p class="card-text"><?php echo $wdo_card_content; ?></p>
				               	<?php if ( $wdo_button_text != '' ): ?>
									<a href="<?php echo ( $wdo_button_link !='' ) ? $wdo_button_link : 'javascript:void(0)'; ?>" class="btn btn-outline-dark btn-sm"><?php echo $wdo_button_text; ?></a>
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
			'name'		=> 'Content Cards',
			"description" => __("Add content in card style.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_cards',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/content-card-icon.png',
			'params' => array(

					array(
						"type" 			=> 	"attach_image",
						"heading" 		=> 	__("Card Image"), 
						"param_name" 	=> 	"wdo_card_image",
						"group" 		=> 'Image',
					),

					array(
						"type" => "textfield",
						"heading" => "Card Title",
						"param_name" => "wdo_card_title",
						"description" => "",
						"group" 		=> 'Card Content',
					),

					array(
						"type" => "textarea",
						"heading" => "Card Content",
						"param_name" => "wdo_card_content",
						"description" => "",
						"group" 		=> 'Card Content',
					),

					array(
						"type" => "textfield",
						"heading" => "Button Text",
						"param_name" => "wdo_button_text",
						"description" => "Give text to show on button.",
						"group" 		=> 'Button',
					),

					array(
						"type" => "textfield",
						"heading" => "Link URL",
						"param_name" => "wdo_button_link",
						"description" => "Give link that would open when you click over button.",
						"group" 		=> 'Button',
					),
					

			)
		) );
	}
 ?>