<?php 
	if (class_exists('WPBakeryShortCode')) {
		class WPBakeryShortCode_wdo_ult_text_marquee extends WPBakeryShortCode {

			protected function content( $atts, $content = null ) {

				extract(shortcode_atts( array(
				    "wdo_marquee_text"				=> '', 
				), $atts));
				wp_enqueue_style( 'wdo-banners-css', ULT_URL.'assets/css/text-marquee.css');
				$marquee_text = preg_replace('#^<\/p>|<p>$#', '', $wdo_marquee_text);
				ob_start();
				?>
				<div class="wdo-marquee-wrap">
					<div class="wdo-marquee">
						<?php echo $marquee_text; ?>
					</div>
				</div>
		<?php
			return ob_get_clean();
			}
		}
	}

	if ( function_exists( "vc_map" ) ) {
		vc_map( array(
			'name'		=> 'Text Marquee',
			"description" => __("Add auto sliding text.", 'wdo-ultimate-addons'),
			'base'		=> 'wdo_ult_text_marquee',
			'category'	=> 'All in One Addons',
			"icon" 		=> ULT_URL.'icons/marquee-icon.png',
			'allowed_container_element' => 'vc_row',
			'params' => array(
					array(
						"type" => "textarea",
						"heading" => "Marquee text",
						"param_name" => "wdo_marquee_text",
					),
					

			)
		) );
	}
 ?>