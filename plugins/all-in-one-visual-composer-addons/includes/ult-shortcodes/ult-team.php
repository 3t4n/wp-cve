<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_team extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract( shortcode_atts( array(
					'team_member_image'				=> "",
				    "team_member_style"				=> 'style1',
				    "team_member_border_style"		=> '',
				    "team_custom_class"				=> '',
				    "team_member_name"				=> '',
				    "team_member_designation"		=> '',
				    "member_profile_link"			=> '',
				    "member_link_target"			=> '',
				    "member_text_alignment"			=> '',
				    "member_social_links"			=> '',
				), $atts ) );

				wp_enqueue_style( 'wdo-ult-team-css',  ULT_URL.'assets/css/teamshowcase.css');
				$content = wpb_js_remove_wpautop($content, true);
				if ($team_member_image != '') {
					$team_image_url = wp_get_attachment_url( $team_member_image );		
				}
				ob_start(); ?>
				<div class="team-showcase-container">
					<?php include 'team-templates/'.$team_member_style.'.php'; ?>
				</div>
				<?php
				 
				return ob_get_clean();
			}
		}
	}

	$team_styles = array(
		'style 1'	=>	'style1', 
		'style 2'	=>	'style2',
		'style 3'	=>	'style3',
		'style 4'	=>	'style4',
		'style 5'	=>	'style5',
		'style 6'	=>	'style6',
		'style 7'	=>	'style7',
		'style 8'	=>	'style8',
		'style 9'	=>	'style9',
		'style 10'	=>	'style10',
	);
	if ( function_exists( "vc_map" ) ) {
		vc_map( array(
			'name'		=> 'Team',
			"description" => __("Display team members.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_team',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/team-icon.png',
			'allowed_container_element' => 'vc_row',
			'params' => array(
							array(
								"type" 			=> 	"attach_image",
								"heading" 		=> 	__("Member Image"), 
								"param_name" 	=> 	"team_member_image",
								'description'	=> 'Try to use image of equal dimension for better results.',
								"group" 		=> 'Image',
							),

							array(
								"type" 			=> "dropdown",
								"heading" 		=> __("Style"),
								"param_name" 	=> "team_member_style",
								"group" 		=> 'Image',
								'save_always'   => true,
								"value" 		=> $team_styles,
							),

							array(
								"type" => "dropdown",
								"heading" => "Image Border Style",
								"param_name" => "team_member_border_style",
								"group" 		=> 'Image',
								"value" => array(
									"None" 	=> "none",
									"Dashed" => "dashed",
									"Dotted" => "dotted",
									"Double" => "double",
									"Inset" => "inset",
									"Outset" => "outset",
								),
							),

							array(
								"type" 			=> "textfield", 
								"heading" 		=> __("Custom Class"),
								"param_name" 	=> "team_custom_class",
								"group" 		=> 'Image',
							),

							array(
								"type" 			=> "textfield", 
								"heading" 		=> __("Member Name"),
								"param_name" 	=> "team_member_name",
								"group" 		=> 'Text',
							),

							array(
								"type" 			=> "textfield", 
								"heading" 		=> __("Member Designation"),
								"param_name" 	=> "team_member_designation",
								'value'			=> 'Project Manager',
								"group" 		=> 'Text',
							),

							array(
								"type" 			=> "textarea_html",
								"heading" 		=> __("Member Description"),
								"param_name" 	=> "content",
								"group" 		=> 'Text',
							),

							array(
								"type" 			=> "textfield",
								"heading" 		=> __("Member Profile Link"),
								"param_name" 	=> "member_profile_link",
								"description" 	=> __("Leave blank to disable link"),
								"group" 		=> 'Text',
							),
							array(
								"type" 			=> "textfield",
								"heading" 		=> __("Profile Link Target"),
								"param_name" 	=> "member_link_target",
								"description" 	=> __("Write _blank for opening link in new window and _self for same window."),
								"group" 		=> 'Text',
							),
							array(
								'type' => 'param_group',
								'heading' => __( 'Add Social Links', 'wdo-ultimate-addons' ),
								'param_name' => 'member_social_links',
								"show_settings_on_create" => true,
								'group'  => __( 'Social Links', 'wdo-ultimate-addons' ),
								"description" => "Click on arrow icon to see settings if not visible.",
								'value' => urlencode( json_encode ( array(
																		array(
																			"selected_team_icon" => "fa fa-facebook-square",
																			"social_icon_url" => "#",
																		)
								) ) ),
								'params' => array(
									array(
										'type' => 'textfield',
										'heading' => __( 'Link', 'wdo-ultimate-addons' ),
										'param_name' => 'social_icon_url',
										'description' => "",
									),

									array(
						                'type' => 'iconpicker',
						                'heading' => __( 'Select Icon', 'wdo-ultimate-addons' ),
						                'param_name' => 'selected_team_icon',
						                'settings' => array(
						                   'emptyIcon' => false,
						                   'type' => 'fontawesome',
						                   'iconsPerPage' => 500, 
						                ),
						            ),
								),
								
							),

							array(
								"type" => "html",
								"group" => "Demo",
								"heading" => "<h3 style='padding: 10px;background: #2b4b80;text-align:center;'><a style='color: #fff;text-decoration:none;' target='_blank' href='https://demo.webdevocean.com/team-vc/' >Click to See Demo</a>",
								"param_name" => "demo",
							),
					)
		));
	}
 ?>