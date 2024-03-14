<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_3D_buttons extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract(shortcode_atts( array(
				    "wdo_button_size"				=> '',
				    "wdo_button_style"				=> '', 
				    "wdo_button_text"				=> '',
				    "wdo_icon"						=> '',
				    "wdo_button_link"				=> "",
				    "wdo_target"					=> '_self',
				    'wdo_button_id'					=> "",
				), $atts));
				wp_enqueue_style( 'wdo-bootstrap-css', ULT_URL.'assets/css/bootstrap-min.css');
				wp_enqueue_style( 'wdo-banners-css', ULT_URL.'assets/css/ult-buttons.css');
				wp_enqueue_style( 'wdo-font-awesome-css', ULT_URL.'assets/css/font-awesome.min.css');
				$unique_id = rand(5, 500);
				ob_start();
				?>
				<div class="wdo-ult-container">
					<div class="outer-3d">
						<a id="<?php echo $wdo_button_id; ?>" href="<?php echo ($wdo_button_link!='' ? $wdo_button_link : 'javascript:void(0)' ); ?>" target="<?php echo $wdo_target; ?>" type="button" 
							class="unique-class-<?php echo $unique_id; ?> fancy-btn btn3d btn <?php echo $wdo_button_style; ?> <?php echo ($wdo_button_size!='' ? $wdo_button_size : 'btn-lg') ; ?>">
							<i class="fa <?php echo $wdo_icon; ?>"></i>  <?php echo ($wdo_button_text!='' ? '&nbsp;'.$wdo_button_text : '' ); ?>
						</a>
					</div>
				</div>
		<?php
			return ob_get_clean();
			}
		}
	}

	if ( function_exists( "vc_map" ) ) {
		vc_map( array(
			'name'		=> '3D Buttons',
			"description" => __("Add 3D Styled buttons.", 'wdo-button'),
			'base'		=> 'wdo_ult_3D_buttons',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/3d-button-icon.png',
			'allowed_container_element' => 'vc_row',
			'params' => array(
					array(
						"type" => "dropdown",
						"class" => "",
						"heading" => "Size",
						"param_name" => "wdo_button_size",
						"value" => array(
							"Default" => "",
				            "Extra Small" => "btn-xs",
				            "Small" => "btn-sm",
							"Medium" => "btn-md",	
							"Large" => "btn-lg",
							"Big Large full width" => "btn-block"
						)
					),
					
					array(
						"type" => "dropdown",
						"class" => "",
						"heading" => "Style",
						"param_name" => "wdo_button_style",
						"value" => array(
							"" 	=> "",
							"Default" 	=> "btn-default",
							"White" 	=> "btn-white",
							"Primary" 	=> "btn-primary",
							"Success" 	=> "btn-success",
							"Info" 		=> "btn-info",
							"Warning" 	=> "btn-warning",
							"Danger" 	=> "btn-danger",
							"Magick" 	=> "btn-magick",
							"Link" 		=> "btn-link",
						),
					),

					array(
						"type" => "textfield",
						"class" => "",
						"heading" => "Button Text",
						"param_name" => "wdo_button_text"
					),

					array(
					    'type' => 'iconpicker',
					    'heading' => __( 'Button Icon', 'vca-tabs' ),
					    'param_name' => 'wdo_icon',
					    'settings' => array(
					       'emptyIcon' => true,
					       'type' => 'fontawesome',
					       'iconsPerPage' => 500, 
					    ),
					),

					array(
					    "type" => "textfield",
					    "class" => "",
					    "heading" => "ID",
					    "param_name" => "wdo_button_id",
					    "description" => "Set unique button ID attribute"
				    ),

					array(
						"type" => "textfield",
						"class" => "",
						"heading" => "Link",
						"param_name" => "wdo_button_link",
						"group" 		=> 'Links',
					),
					array(
						"type" => "dropdown",
						"class" => "",
						"heading" => "Link Target",
						"param_name" => "wdo_target",
						"value" => array(
							"Self" => "_self",
							"Blank" => "_blank",	
							"Parent" => "_parent",
							"Top" => "_top"	
						),
						'save_always' => true,
						"group" 		=> 'Links',
					),
					

					/**** Styles Group Start ******/
				 //    array(
				 //        "type" => "colorpicker",
				 //        "class" => "",
				 //        "heading" => "Button Text Color",
				 //        "param_name" => "wdo_text_color",
				 //        "group" 		=> 'Styles',
				 //    ),

					// array(
					// 	"type" => "colorpicker",
					// 	"class" => "",
					// 	"heading" => "Icon Color",
					// 	"param_name" => "wdo_icon_color",
					// 	"dependency" => Array('element' => "wdo_icon", 'not_empty' => true),
					// 	"group" 		=> 'Styles',
					// ),

					
				 //    array(
					// 	"type" => "dropdown",
					// 	"class" => "",
					// 	"heading" => "Font Style",
					// 	"param_name" => "wdo_font_style",
					// 	"value" => array(
					// 		"" => "",
					// 		"Normal" => "normal",	
					// 		"Italic" => "italic"
					// 	),
					// 	"group" 		=> 'Styles',
					// ),
					// array(
					// 	"type" => "dropdown",
					// 	"class" => "",
					// 	"heading" => "Font Weight",
					// 	"param_name" => "wdo_font_weight",
					// 	"value" => array(
					// 		"Default" => "",
					// 		"Thin 100" => "100",
					// 		"Extra-Light 200" => "200",
					// 		"Light 300" => "300",
					// 		"Regular 400" => "400",
					// 		"Medium 500" => "500",
					// 		"Semi-Bold 600" => "600",
					// 		"Bold 700" => "700",
					// 		"Extra-Bold 800" => "800",
					// 		"Ultra-Bold 900" => "900"
					// 	),
					// 	"group" 		=> 'Styles',
					// ),
					// array(
					// 	"type" => "dropdown",
					// 	"class" => "",
					// 	"heading" => "Text Align",
					// 	"param_name" => "wdo_text_align",
					// 	"value" => array(
					// 		"" => "",
					// 		"Left" => "left",	
					// 		"Right" => "right",
					// 		"Center" => "center"
					// 	),
					// 	"group" 		=> 'Styles',
					// ),
					// array(
					// 	"type" => "textfield",
					// 	"class" => "", 
					// 	"heading" => "Margin",
					// 	"param_name" => "wdo_margin",
					// 	"description" => __("Please insert margin in format: 0px 0px 1px 0px", 'wdo-button')
					// ),
					// array(
					// 	"type" => "textfield",
					// 	"class" => "",
					// 	"heading" => "Border radius",
					// 	"param_name" => "wdo_border_radius",
					// 	"description" => __("Please insert border radius(Rounded corners) in px. For example: 4 ", 'wdo-button'),
					// 	"group" 		=> 'Styles',
					// ),

					/**** Styles Group End ***/



					/**** On Hover Styles Start ***/

					// array(
				 //        "type" => "colorpicker",
				 //        "class" => "",
				 //        "heading" => "Hover Text Color",
				 //        "param_name" => "wdo_hover_color",
				 //        "group" 		=> 'On Hover Styles',
				 //    ),

				    /**** On Hover Styles End ***/

			)
		) );
	}
 ?>