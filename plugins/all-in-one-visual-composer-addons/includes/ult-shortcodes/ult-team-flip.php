<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_team_flip extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract(shortcode_atts( array(
				    "wdo_flip_member_image"	=> '',
				    "wdo_flip_member_name"	=> '',
				    "wdo_flip_member_short_desc"	=> '', 
				    "wdo_flip_member_full_desc"	=> '',
				    "wdo_flip_social_links"	=> '',
				), $atts));

				wp_enqueue_style( 'wdo-bootstrap4-css', ULT_URL.'assets/css/bootstrap4.min.css');
				wp_enqueue_style( 'wdo-font-awesome-css', ULT_URL.'assets/css/font-awesome.min.css');
				wp_enqueue_style( 'wdo-custom-css', ULT_URL.'assets/css/custom.css');
				wp_enqueue_script( 'wdo-bootstrap-js',  ULT_URL.'assets/js/bootstrap4.min.js',array('jquery'));

				if ($wdo_flip_member_image != '') {
					$flip_image_url = wp_get_attachment_url( $wdo_flip_member_image );		
				}

				$team_social_icons = json_decode (urldecode( $wdo_flip_social_links ) );

				ob_start(); ?>
					<!-- https://bootsnipp.com/snippets/92xNm -->
					<div class="wdo-team-flip-container">
						<div class="image-flip" ontouchstart="this.classList.toggle('hover');">
		                    <div class="mainflip">
		                        <div class="frontside">
		                            <div class="card">
		                                <div class="card-body text-center">
		                                    <p>
		                                    	<?php if ( $wdo_flip_member_image != '' ): ?>
									            	<img class=" img-fluid" src="<?php echo $flip_image_url; ?>" alt="card image">
									            <?php endif; ?>
		                                    	
		                                    </p>
		                                    <h4 class="card-title"><?php echo $wdo_flip_member_name; ?></h4>
		                                    <p class="card-text"><?php echo $wdo_flip_member_short_desc; ?></p>
		                                    <a href="#" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i></a>
		                                </div>
		                            </div>
		                        </div>
		                        <div class="backside">
		                            <div class="card">
		                                <div class="card-body text-center mt-4">
		                                    <h4 class="card-title"><?php echo $wdo_flip_member_name; ?></h4>
		                                    <p class="card-text"><?php echo $wdo_flip_member_full_desc; ?></p>
		                                    <ul class="list-inline">
												<?php if ( isset( $team_social_icons) && is_array($team_social_icons) ): ?>
													<?php foreach($team_social_icons as $social_link):  ?>
														<li class="list-inline-item">
															<a class="social-icon text-xs-center" href="<?php echo ( $social_link -> social_icon_url !='' ) ? $social_link -> social_icon_url : 'javascript:void(0)'; ?>">
																<i class="<?php echo $social_link -> selected_team_icon; ?>"></i>
															</a>
														</li>
													<?php endforeach; ?>

												<?php endif; ?>
		                                    </ul>
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
			'name'		=> 'Team Flip Box',
			"description" => __("Add team in filp style.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_team_flip',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/team-flip-icon.png',
			'params' => array(

					array(
						"type" 			=> 	"attach_image",
						"heading" 		=> 	__("Member Image"), 
						"param_name" 	=> 	"wdo_flip_member_image",
						"description" => "Add team member image.",
						"group" 		=> 'Image',
					),

					array(
						"type" => "textfield",
						"heading" => "Member Name",
						"param_name" => "wdo_flip_member_name",
						"group" 		=> 'Member Details',
					),

					array(
						"type" => "textarea",
						"heading" => "Member Short Description",
						"param_name" => "wdo_flip_member_short_desc",
						"description" => "Give short description.This would be shown at front side of flip box.",
						"group" 		=> 'Member Details',
					),

					array(
						"type" => "textarea",
						"heading" => "Member Full Description",
						"param_name" => "wdo_flip_member_full_desc",
						"description" => "Give full description.This would be shown at back side of flip box.",
						"group" 		=> 'Member Details',
					),
					array(
						'type' => 'param_group',
						'heading' => __( 'Social Links', 'wdo-ultimate-addons' ),
						'param_name' => 'wdo_flip_social_links',
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
						"show_settings_on_create" => true,
					),
					
					array(
						"type" => "html",
						"group" => "Demo",
						"heading" => "<h3 style='padding: 10px;background: #2b4b80;text-align:center;'><a style='color: #fff;text-decoration:none;' target='_blank' href='https://demo.webdevocean.com/team-flip/' >Click to See Demo</a>",
						"param_name" => "demo",
					),
			)
		) );
	}
 ?>