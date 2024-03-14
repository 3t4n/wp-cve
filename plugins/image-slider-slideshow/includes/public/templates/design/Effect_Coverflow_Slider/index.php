<?php if ( ! defined( 'ABSPATH' ) ) exit; 
wp_enqueue_style( "img_slider_coverflow", IMG_SLIDER_ASSETS. "css/layout-design.css",'');

$auto_play = $data->settings['auto_play'];
$slide_duration = $data->settings['slide_duration'];
$numOfImages = $data->settings['numOfImages'];
?>

<div class="swiper-container img-slider-swiper-coverflow">
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
							'hide_navigation'  => boolval( $data->settings['hide_navigation'] ) ? true : false,
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
						$url = wp_get_attachment_image_src($id, 'rpg_image_thumbnail', true);
						/*--------------------------*/

						$data->loader->set_template_data( $item_data ); ?>	

    					<div class="swiper-slide" style="background-image:url(<?php echo esc_url($url['0']); ?>)"></div>

						
		<?php endforeach; ?>
		</div>
	<?php if ( ! $data->settings['hide_navigation'] ): ?>
		<!-- Add Pagination -->
    	<div class="swiper-pagination"></div>
    <?php endif ?>
  </div>

  <script type="text/javascript">
    
    var auto_play = '<?php echo $auto_play; ?>';
    var slide_duration = '<?php echo $slide_duration; ?>';
    var numOfImages = '<?php echo $numOfImages; ?>';

    auto_play = (auto_play==1) ? { delay: slide_duration, disableOnInteraction: false,} : false;
    //console.log(auto_play);
    
    var swiper = new Swiper('.img-slider-swiper-coverflow', {
      effect: 'coverflow',
      grabCursor: true,
      centeredSlides: false,
      slidesPerView: numOfImages,
      coverflowEffect: {
        rotate: 50,
        stretch: 0,
        depth: 100,
        modifier: 1,
        slideShadows : true,
      },
      autoplay: auto_play,
      pagination: {
        el: '.swiper-pagination',
      },
    });

  </script>

	
