<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_social_cards extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract(shortcode_atts( array(
				    "wdo_social_icon"	=> '',
				    "wdo_social_card_title"	=> '',
				    "wdo_social_card_content"	=> '',
				    "wdo_link_text"	=> '',
				    "wdo_social_link"	=> '',
				), $atts));
				wp_enqueue_style( 'wdo-bootstrap4-css', ULT_URL.'assets/css/bootstrap4.min.css');
				wp_enqueue_style( 'wdo-font-awesome-css', ULT_URL.'assets/css/font-awesome.min.css');
				wp_enqueue_style( 'wdo-custom-css', ULT_URL.'assets/css/custom.css');
				ob_start(); ?>
						<!-- https://bootsnipp.com/snippets/or33d -->

					<div class="wdo-social-container">
						<div class="box-part text-center">
                        
	                        <i class="fa <?php echo $wdo_social_icon; ?> fa-3x" aria-hidden="true"></i>
	                        
							<div class="wdo-title text-center">
								<h4><?php echo $wdo_social_card_title; ?></h4>
							</div>
	                        
							<div class="wdo-text" style="margin:20px 0px;">
								<span><?php echo $wdo_social_card_content; ?></span>
							</div>
	                        
							<?php if ( $wdo_link_text != '' ): ?>
								<a href="<?php echo ( $wdo_social_link !='' ) ? $wdo_social_link : 'javascript:void(0)'; ?>"><?php echo $wdo_link_text; ?></a>
							<?php endif; ?>
                        
					 	</div>
					</div>
			<?php
			return ob_get_clean();
			}
		}
	}


	if ( function_exists( "vc_map" ) ) {
		vc_map( array(
			'name'		=> 'Social Cards',
			"description" => __("Display your social profiles.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_social_cards',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/social-card-icon.png',
			'params' => array(

					array(
                    'type' => 'iconpicker',
                    'heading' => __( 'Social Icon', 'wdo-ultimate-addons' ),
                    'param_name' => 'wdo_social_icon',
                    'settings' => array(
                       'emptyIcon' => false,
                       'type' => 'fontawesome',
                       'iconsPerPage' => 500, 
	                    ),
	                ),

					array(
						"type" => "textfield",
						"heading" => "Social Network Name",
						"param_name" => "wdo_social_card_title",
						"description" => "",
						"group" 		=> 'Content',
					),

					array(
						"type" => "textarea",
						"heading" => "Description Text",
						"param_name" => "wdo_social_card_content",
						"description" => "",
						"group" 		=> 'Content',
					),

					array(
						"type" => "textfield",
						"heading" => "Link Text",
						"param_name" => "wdo_link_text",
						"description" => "Give text to show on link.",
						"group" 		=> 'Links',
					),

					array(
						"type" => "textfield",
						"heading" => "Socail Profile URL",
						"param_name" => "wdo_social_link",
						"description" => "Give complete URL of profile.",
						"group" 		=> 'Links',
					),
					

			)
		) );
	}
 ?>