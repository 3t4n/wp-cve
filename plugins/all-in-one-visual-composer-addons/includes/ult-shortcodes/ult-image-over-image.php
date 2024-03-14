<?php
if (class_exists('WPBakeryShortCode')) {
	class WPBakeryShortCode_wdo_ult_image_over_image extends WPBakeryShortCode {

		protected function content( $atts, $content = null ) {
 
			extract( shortcode_atts( array(
				'wdo_front_image'					=> "",
			    "wdo_back_image"					=> '',
			    "wdo_caption_url"					=> '',
			    "wdo_url_target"					=> '',
			    "wdo_image_effect"					=> '',
			), $atts ) );

			if (isset($wdo_front_image) &&  $wdo_front_image != '') {
				$front_image_url = wp_get_attachment_url( $wdo_front_image );		
			}
			if (isset($wdo_back_image) &&  $wdo_back_image != '') {
				$back_image_url = wp_get_attachment_url( $wdo_back_image );		
			}
			wp_enqueue_style( 'wdo-ioi-css', ULT_URL.'assets/css/ioi.css');
			$cap_link = ( isset($wdo_caption_url) && $wdo_caption_url != '' ) ? $wdo_caption_url : 'javascript:void(0)' ;
			ob_start();
			?>
			<div class="item"> 
				<div class="ioi-container">
					<div class="ioi-<?php echo $wdo_image_effect; ?>">
					    <a class="he-box" href="<?php echo $cap_link; ?>" target="<?php echo $wdo_url_target; ?>">
					        <div class="box-img">
					        	<?php if ($wdo_image_effect == 'style7'): ?>
					        		<span class="he-over-layer">
	                                    <img src="<?php echo $back_image_url; ?> " alt="">
	                                </span>
					        	<?php endif ?>
					        	<?php if ( $wdo_image_effect == 'style8' ): ?>
					        		<span class="he-over-layer"></span>
					        	<?php endif ?>

					            <img src="<?php echo $front_image_url; ?> " alt="">

					        	<?php if ( $wdo_image_effect == 'style9' ): ?>
					        		<span class="he-over-layer"></span>
					        	<?php endif ?>
					        </div>
					        <div class="he-content">
					            <img src="<?php echo $back_image_url; ?> " alt="">
					        </div>
					    </a>
					</div>
				</div>
			</div>
	<?php 
		return ob_get_clean();
		}
	}
}

$image_effects = array(
	'Select Effect'=>	'style1',
	'Style 1'	=>	'style1',
	'Style 2'	=>	'style2',
	'Style 3'	=>	'style3',
	'Style 4'	=>	'style4',
	'Style 5'	=>	'style5',
	'Style 6'	=>	'style6',
	'Style 7'	=>	'style7',
	'Style 8'	=>	'style8',
	'Style 9'	=>	'style9',
	'Style 10'	=>	'style10',
);

if ( function_exists( "vc_map" ) ) {
	vc_map( array(
		"base" 			=> "wdo_ult_image_over_image", 
		"name" 			=> __( 'Image Over Image', 'wdo-carousel' ),
		'category'		=> 'All in One Addons',
		"description" 	=> __('On hover image changes.', 'wdo-carousel'),
		"icon" => ULT_URL.'icons/admin-icon-ioi.png',
		'params' => array(
			array(
				"type" 			=> 	"attach_image",
				"heading" 		=> 	__("Front Image"),
				"param_name" 	=> 	"wdo_front_image",
				"description" 	=> 	__("Select front image"),
			),

			array(
				"type" 			=> 	"attach_image",
				"heading" 		=> 	__("Image after Hover"),
				"param_name" 	=> 	"wdo_back_image",
				"description" 	=> 	__("Select image to show on hover"),
			),

			array(
				"type" 			=> "textfield",
				"heading" 		=> __("URL"),
				"param_name" 	=> "wdo_caption_url",
				"description" 	=> __("Leave blank to disable link"),
			),
			array(
				"type" 			=> "textfield", 
				"heading" 		=> __("Link Target"),
				"param_name" 	=> "wdo_url_target",
				"description" 	=> __("Write _blank for opening link in new window and _self for same window."),
			),

			

			// Hover Effects Settings

			array(
				"type" 			=> "dropdown",
				"heading" 		=> __("Image Change Effect"),
				"param_name" 	=> "wdo_image_effect",
				'save_always'   => true,
				"description" 	=> __("Select effect when image changes."),
				"group"         => "Styles",
				"value" 		=> $image_effects,
			),

			array(
				"type" => "html",
				"group" => "Demo",
				"heading" => "<h3 style='padding: 10px;background: #2b4b80;text-align:center;'><a style='color: #fff;text-decoration:none;' target='_blank' href='https://demo.webdevocean.com/image-over-image-vc/' >Click to See Demo</a>",
				"param_name" => "demo",
			),
		)
	) );
}
?>