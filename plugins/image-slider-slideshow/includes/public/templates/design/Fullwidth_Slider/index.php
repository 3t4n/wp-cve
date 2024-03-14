<?php if ( ! defined( 'ABSPATH' ) ) exit; 
wp_enqueue_style( "img_slider_fullwidth", IMG_SLIDER_ASSETS. "css/layout-design.css",'');

$auto_play = $data->settings['auto_play'];
$slide_duration = $data->settings['slide_duration'];
?>

<!-- Demo styles -->
  <style>
    
    .swiper-container {
      width: 100%;
      height: 100%; 
      padding-right: 0px;
      padding-left: 0px;
      margin-right: auto;
      margin-left: auto;
    }
    
    

    body.template-full-width .entry-content > *:not(.alignwide):not(.alignfull):not(.alignleft):not(.alignright):not(.is-style-wide), body.template-full-width [class*="__inner-container"] > *:not(.alignwide):not(.alignfull):not(.alignleft):not(.alignright):not(.is-style-wide)
    {
      max-width: max-content;
    }

    /*.img-slider-swiper-button-next:after, .img-slider-swiper-button-prev:after {
      font-size: 30px;
      color: #000;
      font-weight: bold;
    }

    .img-slider-swiper-button-prev{
      width: 50px;
      background: #fff;
      background-position: -82px -22px;
      height: 50px;
      cursor: pointer;
      top: 50%;
      left: 30px;
      margin-top: -25px;
      position: absolute;
      z-index: 110;
    }

    .img-slider-swiper-button-next{
      right: 30px;
      left: auto;
      background-position: -81px -99px;
      
      width: 50px;
      background: #fff;
      height: 50px;
      cursor: pointer;
      top: 50%;
      margin-top: -25px;
      position: absolute;
      z-index: 110;
    }*/

    .img-slider-swiper-button-prev {
        position: relative;
        width: auto;
        height: auto;
        padding: 5px 20px !important;
        background: <?php echo $data->settings['controlsBgColor'] ?>; 
        border-radius: <?php echo $data->settings['contorlsBgBorderRadius'] ?>%; 
        background-position: -82px -22px;
        cursor: pointer;
        top: 45%;
        left: 30px;
        margin-top: -25px;
        position: absolute;
        z-index: 110;
    }

    .img-slider-swiper-button-next {
        position: relative;
        right: 30px;
        left: auto;
        background-position: -81px -99px;
        padding: 5px 20px !important;
        width: auto;
        height: auto;
        background: <?php echo $data->settings['controlsBgColor'] ?>; 
        border-radius: <?php echo $data->settings['contorlsBgBorderRadius'] ?>%;
        cursor: pointer;
        top: 45%;
        margin-top: -25px;
        position: absolute;
        z-index: 110;
    }

    .img-slider-swiper-button-next:after, .img-slider-swiper-button-prev:after{
        font-size: <?php echo $data->settings['contorlsFontSize'] ?>px; 
        font-weight: bold;
        color: <?php echo $data->settings['controlsColor'] ?>;  
    }

    .img-slider-swiper-button-prev:hover,
    .img-slider-swiper-button-next:hover {
        background: <?php echo $data->settings['controlsBgColorOnHover'] ?>;
    }

    .boxed-prev:hover .img-slider-swiper-button-prev:after,
    .boxed-next:hover .img-slider-swiper-button-next:after{
        color: <?php echo $data->settings['controlsColorOnHover'] ?>;
    }

    .img-slider .swiper-slide img{
      width: 100%;
      height: auto;
      
    }
    
  </style>

<!-- Swiper -->
<div class="swiper-container img-slider-swiper-auto">
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
						$url = wp_get_attachment_image_src($id, 'rpg_image_slider', true);
						/*--------------------------*/

						$data->loader->set_template_data( $item_data ); ?>	

 						
 						<div class="swiper-slide"><img src="<?php echo esc_url($url['0']); ?>">
                <div class="fullwidth-caption d-none d-md-block">
                    <?php if ( ! $data->settings['hide_title'] && !empty($item_data['title']) ): ?>
                        <h5 style="
                        font-size: <?php echo $data->settings['titleFontSize'] ?>px;
                        font-family: <?php echo $data->settings['font_family'] ?>;
                        color: <?php echo $data->settings['titleColor'] ?>;
                        background-color: <?php echo $data->settings['titleBgColor'] ?>;
                        padding: 5px;
                        border-radius: 3px;
                        "><?php echo $item_data['title']; ?></h5>
                    <?php endif ?>

                    <?php if ( ! $data->settings['hide_description'] && !empty($item_data['description']) ): ?>
                        <p style="
                        font-size: <?php echo $data->settings['captionFontSize'] ?>px;
                        font-family: <?php echo $data->settings['font_family'] ?>;
                        color: <?php echo $data->settings['captionColor'] ?>;
                        background-color: <?php echo $data->settings['captionBgColor'] ?>;
                        padding: 5px;
                        border-radius: 3px;
                        "><?php echo $item_data['description']; ?></p>
                      <?php endif ?>
                </div>
            </div>

		<?php endforeach; ?>

		</div>
    <?php if ( ! $data->settings['hide_navigation'] ): ?>
      <!-- Add Pagination -->
      <div class="swiper-pagination"></div>
      
      <!-- Add Arrows -->
      <div class="boxed-next">
        <div class="img-slider-swiper-button-next"></div>
      </div>
      <div class="boxed-prev">
          <div class="img-slider-swiper-button-prev"></div>
      </div>
    <?php endif ?>
   
  </div>

  <script type="text/javascript">

    var auto_play = '<?php echo $auto_play; ?>';
    var slide_duration = '<?php echo $slide_duration; ?>';

    auto_play = (auto_play==1) ? { delay: slide_duration, disableOnInteraction: false,} : false;
    //console.log(auto_play);
    
    var swiper = new Swiper('.img-slider-swiper-auto', {
      spaceBetween: 30,
      //effect: 'flip',
      effect: 'fade',//for fade effect
      // effect: 'cube',
      // cubeEffect: {
      //   shadow: true,
      //   slideShadows: true,
      //   shadowOffset: 20,
      //   shadowScale: 0.94,
      // },

      centeredSlides: true,
      autoplay: auto_play,
      pagination: {
        el: '.swiper-pagination',
        clickable: true,
      },
      navigation: {
        nextEl: '.img-slider-swiper-button-next',
        prevEl: '.img-slider-swiper-button-prev',
      },
      keyboard: {
        enabled: true, //for keyboard control
      },
    });
  </script>