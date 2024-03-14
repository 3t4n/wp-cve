<?php
if (class_exists('WPBakeryShortCode')) {
	class WPBakeryShortCode_wdo_ult_image_slider extends WPBakeryShortCode {

		protected function content( $atts, $content = null ) {
 
			extract( shortcode_atts( array(
				'wdo_slider_images'			=> "",
				'wdo_slides'				=> "3",
				'wdo_slide_margin'			=> "0",
			), $atts ) );

			$unique_id = rand(5, 500);
			$image_ids=explode(',',$wdo_slider_images);

			wp_enqueue_style( 'wdo-owl-css', ULT_URL.'assets/css/owl.carousel.min.css');
			wp_enqueue_script( 'wdo-owl-js',  ULT_URL.'assets/js/owl.carousel.min.js',array('jquery'));
			ob_start();
			?>
			<style>
				.wdo-image-slider-container .owl-nav{
					display: none;
				}
				.owl-carousel:hover .owl-nav{
					display: block;
				}
				.wdo-image-slider-container .owl-nav .owl-prev,.wdo-image-slider-container .owl-nav .owl-next {
				  background: transparent !important;
				  color: #869791 !important;
				  font-size: 40px !important;
				  line-height: 300px !important;
				  margin: 0 !important;
				  padding: 0 60px !important;
				  position: absolute !important;
				  top: 0;
				}
				.wdo-image-slider-container .owl-nav .owl-prev {
				  left: 20px;
				  padding-left: 20px;
				}
				.wdo-image-slider-container .owl-nav .owl-next {
				  right: 20px;
				  padding-right: 20px;
				}
				.wdo-image-slider-container .owl-nav button:focus {
				  outline: none !important;
				}
			</style>
			<div class="wdo-image-slider-container unique-image-slider-<?php echo $unique_id; ?> owl-carousel owl-theme">
				<?php foreach ($image_ids as $image_id): ?>
					<?php $image_url = wp_get_attachment_url( $image_id ); ?>
					<div class="item">
						<img src="<?php echo $image_url; ?>" alt="Owl Image">
					</div>
				<?php endforeach ?>
				
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function ($) {
					$('.unique-image-slider-<?php echo $unique_id; ?>').owlCarousel({
					    autoplay: false,
      					items : <?php echo $wdo_slides; ?>,
      					margin : <?php echo $wdo_slide_margin; ?>,
      					nav : true,
      					navText:["<div class='nav-btn prev-slide'></div>","<div class='nav-btn next-slide'></div>"],
					});
				});
			</script>
	<?php 
		return ob_get_clean();
		}
	}
}



if ( function_exists( "vc_map" ) ) {
	vc_map( array(
		"base" 			=> "wdo_ult_image_slider", 
		"name" 			=> __( 'Image Slider', 'wdo-ultimate-addons' ),
		"description" => __("Add images in slider.", 'wdo-ultimate-addons'),
		'category'		=> 'All in One Addons',
		"icon" => ULT_URL.'icons/image-slider-icon.png',
		'params' => array(
			array(
				"type" 			=> 	"attach_images",
				"heading" 		=> 	__("Add Images"),
				"param_name" 	=> 	"wdo_slider_images",
				"description" 	=> 	__("Select images to show in slider."),
			),

			array(
				"type" 			=> "textfield",
				"heading" 		=> __("Images To Show"),
				"param_name" 	=> "wdo_slides",
				"description" 	=> __("Give number of images want to show in slider in front."),
			),
			array(
				"type" 			=> "textfield", 
				"heading" 		=> __("Margin"),
				"param_name" 	=> "wdo_slide_margin",
				"description" 	=> __("Give margin between each image.Just give number e.g: 1."),
			),
		)
	) );
}
?>