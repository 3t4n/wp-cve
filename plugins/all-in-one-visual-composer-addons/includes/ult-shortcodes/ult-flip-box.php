<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_flip_box extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract(shortcode_atts( array(
				    "wdo_flip_icon"	=> '',
				    "wdo_front_title"	=> '',
				    "wdo_front_desc"	=> '',
				    "wdo_back_title"	=> '',
				    "wdo_back_desc"	=> '',
				    "wdo_flip_button_text"	=> '',
				    "wdo_flip_button_link"	=> '',
				), $atts));

				wp_enqueue_style( 'wdo-bootstrap4-css', ULT_URL.'assets/css/bootstrap4.min.css');
				wp_enqueue_style( 'wdo-custom-css', ULT_URL.'assets/css/custom.css');
				wp_enqueue_style( 'wdo-font-awesome-css', ULT_URL.'assets/css/font-awesome.min.css');
				wp_enqueue_script( 'wdo-bootstrap-js',  ULT_URL.'assets/js/bootstrap4.min.js',array('jquery'));

				ob_start(); ?>
					<!-- https://bootsnipp.com/snippets/92xNm -->
					<div class="wdo-team-flip-container">
						<div class="image-flip" ontouchstart="this.classList.toggle('hover');">
		                    <div class="mainflip">
		                        <div class="frontside">
		                            <div class="card">
		                                <div class="card-body text-center">
		                                    <p>
		                                    	<i class="fa <?php echo $wdo_flip_icon; ?> fa-3x"></i>
		                                    </p>
		                                    <h4 class="card-title"><?php echo $wdo_front_title; ?></h4>
		                                    <p class="card-text"><?php echo $wdo_front_desc; ?></p>
		                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="backside">
		                            <div class="card">
		                                <div class="card-body text-center mt-4">
		                                    <h4 class="card-title"><?php echo $wdo_back_title; ?></h4>
		                                    <p class="card-text"><?php echo $wdo_back_desc; ?></p>
		                                    <?php if ( $wdo_flip_button_text != '' ): ?>
												<a href="<?php echo ( $wdo_flip_button_link !='' ) ? $wdo_flip_button_link : 'javascript:void(0)'; ?>" class="btn btn-light btn-sm text-dark"><?php echo $wdo_flip_button_text; ?></a>
											<?php endif; ?>
		                                </div>
		                            </div>
		                        </div>
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
			'name'		=> 'Flip Box',
			"description" => __("Add content in flip box.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_flip_box',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/flip-box-icon.png',
			'params' => array(

					array(
		                'type' => 'iconpicker',
		                'heading' => __( 'Select Icon', 'wdo-ultimate-addons' ),
		                'param_name' => 'wdo_flip_icon',
		                'settings' => array(
		                   'emptyIcon' => false,
		                   'type' => 'fontawesome',
		                   'iconsPerPage' => 500, 
		                ),
		                "group" 	=> 'Front',
		            ),

					array(
						"type" => "textfield",
						"heading" => "Front Title",
						"description" => "This would be displayed at front.",
						"param_name" => "wdo_front_title",
						"group" 	=> 'Front',
					),

					array(
						"type" => "textarea",
						"heading" => "Front Description",
						"param_name" => "wdo_front_desc",
						"description" => "Shown at front.Keep it short and simple.",
						"group" 	=> 'Front',
					),

					array(
						"type" => "textfield",
						"heading" => "Back Title",
						"description" => "This would be displayed at back.",
						"param_name" => "wdo_back_title",
						"group" 	=> 'Back',
					),

					array(
						"type" => "textarea",
						"heading" => "Back Description",
						"param_name" => "wdo_back_desc",
						"description" => "Shown at back",
						"group" 	=> 'Back',
					),
					array(
						"type" => "textfield",
						"heading" => "Button Text",
						"param_name" => "wdo_flip_button_text",
						"description" => "Give text to show on button.",
						"group" 	=> 'Back',
					),

					array(
						"type" => "textfield",
						"heading" => "URL",
						"param_name" => "wdo_flip_button_link",
						"description" => "Give link that would open when you click over button.",
						"group" 	=> 'Back',
					),
			)
		) );
	}
 ?>