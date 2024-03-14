<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_icon_seperator extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract(shortcode_atts( array(
				    "wdo_seperator_icon"	=> '',
				    "wdo_seperator_color"	=> '',
				    "wdo_seperator_opacity"	=> '',
				), $atts));
				wp_enqueue_style( 'wdo-seperator-css', ULT_URL.'assets/css/ult-icon-seperator.css');
				ob_start();
				?>
				<div class="wdo-seperator-container" style="opacity:<?php echo $wdo_seperator_opacity; ?>">
				  <div class="hr-line" style="border-bottom:1px solid <?php echo $wdo_seperator_color; ?>;"></div>
				  <div class="hr-icon" style="color:<?php echo $wdo_seperator_color; ?>;"><i class="fa <?php echo $wdo_seperator_icon; ?>"></i></div>
				  <div class="hr-line" style="border-bottom:1px solid <?php echo $wdo_seperator_color; ?>;"></div>
				</div>
		<?php
			return ob_get_clean();
			}
		}
	}

	if ( function_exists( "vc_map" ) ) {
		vc_map( array(
			'name'		=> 'Seperator with Icon',
			"description" => __("Add sepertor with icon in center.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_icon_seperator',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/seperator-icon.png',
			'allowed_container_element' => 'vc_row',
			'params' => array(
					array(
                    'type' => 'iconpicker',
                    'heading' => __( 'Icon', 'wdo-ultimate-addons' ),
                    'param_name' => 'wdo_seperator_icon',
                    'settings' => array(
                       'emptyIcon' => false,
                       'type' => 'fontawesome',
                       'iconsPerPage' => 500, 
	                    ),
	                ),

	                array(
						"type"       => "colorpicker",
						"heading"    => __( "Seperator Color", "wdo-ultimate-addons" ),
						"param_name" => "wdo_seperator_color",
					),

					array(
						"type" => "textfield",
						"heading" => "Opacity",
						"param_name" => "wdo_seperator_opacity",
						"description" => __("Set opacity from 0.1 to 1.", 'wdo-ultimate-addons'),
					),

			)
		) );
	}
 ?>