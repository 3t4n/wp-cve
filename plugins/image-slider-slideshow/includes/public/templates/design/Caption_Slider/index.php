<?php if ( ! defined( 'ABSPATH' ) ) exit; 
wp_enqueue_style( "img_slider_caption", IMG_SLIDER_ASSETS. "css/layout-design.css",'');
?> 

<style type="text/css">
	.img-slider .swiper-slide img{
      width: 100%;
      height: auto;
    }

</style>

<?php 
$auto_play = $data->settings['auto_play'];
$slide_duration = $data->settings['slide_duration'];
?>


 
<!-- Swiper -->
<div class="swiper-container img-slider-swiper-caption">
   <div class="swiper-wrapper">
   		<?php 
		
		foreach ( $data->images as $image ): ?>
					<?php 
						
						$image_object = get_post( $image['id'] );
						if ( is_wp_error( $image_object ) || get_post_type( $image_object ) != 'attachment' ) {
							continue;
						}

						// Create array with data in order to send it to image template
						$item_data = array(
							/* Item Elements */
							'title'            => Img_Slider_Helper::get_title( $image, $data->settings['wp_field_title'] ),
							'description'      => Img_Slider_Helper::get_description( $image, $data->settings['wp_field_caption'] ),
							/*'lightbox'         => $data->settings['lightbox'],*/

							/* What to show from elements */
							'hide_title'       => boolval( $data->settings['hide_title'] ) ? true : false,
							'hide_description' => boolval( $data->settings['hide_description'] ) ? true : false,
						

							/* Item container attributes & classes */
							'item_classes'     => array( 'img-slider-item' ),
							'item_attributes'  => array(),

							/* Item link attributes & classes */
							'link_classes'     => array( 'tile-inner' ),
							'link_attributes'  => array(),

							/* Item img attributes & classes */
							'img_classes'      => array( 'pic' ),
							'img_attributes'   => array(
								'data-valign' => esc_attr( $image['valign'] ),
								'data-halign' => esc_attr( $image['halign'] ),
								'alt'         => esc_attr( $image['alt'] ),
							),
						);

						// Create array with data in order to send it to image template
						$image = apply_filters( 'img_slider_shortcode_image_data', $image, $data->settings );

						$item_data = apply_filters( 'img_slider_shortcode_item_data', $item_data, $image, $data->settings, $data->images );
						
						/*--image cropping--*/
						$id=$image['id'];
						$url = wp_get_attachment_image_src($id, 'rpg_image_slider', true);
						/*--------------------------*/

						$data->loader->set_template_data( $item_data ); ?>	

 						
 						<div class="swiper-slide"><img src="<?php echo esc_url($url['0']); ?>">
 							<!-- <php if(!empty($item_data['title']) || !empty($item_data['description']) ){ ?> -->

 							<?php if($item_data['title'] || $item_data['description'] ) { ?>	

 							<?php if(! $data->settings['hide_title'] || ! $data->settings['hide_description']) { ?>
 								
	 							<div class="caption-caption d-md-block" style="overflow: hidden;
	 							background: <?php echo $data->settings['sliderColor'] ?>;
	 							">
	                                <?php if ( ! $data->settings['hide_title'] && !empty($item_data['title']) ): ?>	
	                                    <h5 style="display: inline;
	                                    font-size: <?php echo $data->settings['titleFontSize'] ?>px;
	                                    font-family: <?php echo $data->settings['font_family'] ?>;
	                                    color: <?php echo $data->settings['titleColor'] ?>;
	                                    background-color: <?php echo $data->settings['titleBgColor'] ?>;
	                                    
	                                    "><?php echo $item_data['title']; ?></h5>
	                                <?php endif ?>

	                                <?php if ( ! $data->settings['hide_description'] && !empty($item_data['description']) ): ?>
										<p style="margin-top: 3px;
										font-size: <?php echo $data->settings['captionFontSize'] ?>px;
										font-family: <?php echo $data->settings['font_family'] ?>;
	                                    color: <?php echo $data->settings['captionColor'] ?>;
	                                    background-color: <?php echo $data->settings['captionBgColor'] ?>;
	                                    
										"><?php echo $item_data['description']; ?></p>
									<?php endif ?>
                           		</div>
                           	<?php } } ?>
 						</div>

		<?php endforeach; ?>

		</div>
	   	<!-- Add Arrows -->
	    <div class="img-slider-swiper-button-next" style="background-color: <?php echo $data->settings['sliderColor'] ?>;"></div>
	    <div class="img-slider-swiper-button-prev" style="background-color: <?php echo $data->settings['sliderColor'] ?>;"></div>
	
  </div>

  <script type="text/javascript">
  	
  	var auto_play = '<?php echo $auto_play; ?>';
    var slide_duration = '<?php echo $slide_duration; ?>';

    auto_play = (auto_play==1) ? { delay: slide_duration, disableOnInteraction: false,} : false;
    //console.log(auto_play);

  	var swiper = new Swiper('.img-slider-swiper-caption', {
      spaceBetween: 30,
      

      centeredSlides: true,
      autoplay: auto_play,
      navigation: {
        nextEl: '.img-slider-swiper-button-next',
        prevEl: '.img-slider-swiper-button-prev',
      },
      keyboard: {
        enabled: true, //for keyboard control
      },
    });
  </script>