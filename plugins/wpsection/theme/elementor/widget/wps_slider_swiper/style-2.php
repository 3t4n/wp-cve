<?php
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;



$style    = $settings['style'] ;
$repeat    = $settings['repeat'] ;
$slider_path_background_size    = $settings['slider_path_background_size'] ;
$slider_path_background_position    = $settings['slider_path_background_position'] ;

$unique_id = 'wps_slider_path_' . uniqid();
?>



<?php
echo '
<script>
    jQuery(document).ready(function($)
    {
        // Banner Slider
        var bannerSlider = new Swiper(".banner-slider", {
            preloadImages: false,
            loop: true,
            grabCursor: true,
            centeredSlides: true,
            resistance: true,
            resistanceRatio: 0.6,
            speed: 2400,
            spaceBetween: 0,
            parallax: false,
            effect: "' . $settings['wps_slider_animation'] . '",
            autoplay: {
                delay: 8000,
                disableOnInteraction: false
            },
            navigation: {
                nextEl: ".banner-slider-button-next",
                prevEl: ".banner-slider-button-prev",
            }
        });
    });
</script>';
?>



<?php
echo '
 <style>
 
 .banner-section .content-box {
    position: relative;
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    justify-content: center;
    padding: 201px 0;
}
 
.banner-section .swiper-slide {
	position: relative;
	left: 0;
	top: 0;
	height: 100%;
	width: 100%;
	background-repeat: no-repeat;
	background-position: center;
	background-size: cover;
}

.banner-section .content-box {
	position: relative;
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	justify-content: space-between;
}
.banner-section .content-box .inner {
	position: relative;
	opacity: 0;
	-webkit-transform: translateX(100px);
	-ms-transform: translateX(100px);
	transform: translateX(100px);
}
.banner-section .swiper-slide-active .content-box .inner {
	opacity: 1;
	-webkit-transition: all 500ms ease;
	-o-transition: all 500ms ease;
	transition: all 500ms ease;
	-webkit-transform: translateX(0px);
	-ms-transform: translateX(0px);
	transform: translateX(0px);
}
.banner-slider-2 .content-box .inner {
    transform: translateX(0px);
    transition: .5s;
}
.banner-section .content-box h1 {
	position: relative;
	opacity: 0;
	visibility: hidden;
	-webkit-transition: all 800ms ease;
	-o-transition: all 800ms ease;
	transition: all 800ms ease;
	-webkit-transform: translateY(-20px);
	-ms-transform: translateY(-20px);
	transform: translateY(-20px);
	margin-bottom: 20px;
}
.banner-section .swiper-slide-active .content-box h1 {
	opacity: 1;
	visibility: visible;
	-webkit-transition-delay: 800ms;
	-o-transition-delay: 800ms;
	transition-delay: 800ms;
	-webkit-transform: translateY(0px);
	-ms-transform: translateY(0px);
	transform: translateY(0px);
}
.banner-section h4 {
	position: relative;
	display: block;
	opacity: 0;
	visibility: hidden;
	-webkit-transition: all 800ms ease;
	-o-transition: all 800ms ease;
	transition: all 800ms ease;
	-webkit-transform: translateY(20px);
	-ms-transform: translateY(20px);
	transform: translateY(20px);

}
.banner-section .swiper-slide-active h4 {
	opacity: 1;
	visibility: visible;
	-webkit-transition-delay: 1200ms;
	-o-transition-delay: 1200ms;
	transition-delay: 1200ms;
	-webkit-transform: translateY(0px);
	-ms-transform: translateY(0px);
	transform: translateY(0px);
}
.banner-section .inner p {
	position: relative;
	opacity: 0;
	visibility: hidden;
	-webkit-transition: all 800ms ease;
	-o-transition: all 800ms ease;
	transition: all 800ms ease;
	-webkit-transform: translateX(-50px);
	-ms-transform: translateX(-50px);
	transform: translateX(-50px);
}
.banner-section .swiper-slide-active .inner p {
	opacity: 1;
	visibility: visible;
	-webkit-transition-delay: 1600ms;
	-o-transition-delay: 1600ms;
	transition-delay: 1600ms;
	-webkit-transform: translateX(0px);
	-ms-transform: translateX(0px);
	transform: translateX(0px);
}
.banner-section .link-box {
	-webkit-transform: scaleY(0);
	-ms-transform: scaleY(0);
	transform: scaleY(0);
	-webkit-transition: all 800ms ease;
	-o-transition: all 800ms ease;
	transition: all 800ms ease;
	-webkit-transition-delay: 2000ms;
	-o-transition-delay: 2000ms;
	transition-delay: 2000ms;
	-webkit-transform-origin: bottom;
	-ms-transform-origin: bottom;
	transform-origin: bottom;
	margin: 0 -10px;
}
.banner-section .swiper-slide-active .link-box {
	-webkit-transform: scale(1);
	-ms-transform: scale(1);
	transform: scale(1);
}
.banner-section .link-box a {
	-webkit-transition: .5s ease;
	-o-transition: .5s ease;
	transition: .5s ease;
	margin: 0 10px 10px;
}
.banner-section .link-box a i {
	margin-left: 12px;
}

.banner-section .banner-feature-image {
    transform: translateX(-0) translateY(100px);
    opacity: 0;
    background-size: cover;
    -webkit-transition: all 1500ms ease;
    -o-transition: all 1500ms ease;
    transition: all 1500ms ease;
    transition-delay: 100ms;
}

.banner-section .swiper-slide-active .banner-feature-image {
    opacity: 1;
    transform: translateX(0);
    transition-delay: 1000ms;
}

.banner-section .banner-slider-nav {
	position: absolute;
	bottom: 30px;
	right: 10px;
	display: flex;
	flex-wrap: wrap;
	align-items: center;
	justify-content: space-between;
	width: 68px;
}
.banner-section .banner-slider-button-next {
	position: relative;
	width: 68px;
	height: 68px;
	line-height: 68px;
	text-align: center;
	cursor: pointer;
	z-index: 9;
	font-size: 20px;
	transition: .5s;
	border: 2px solid #fff;
	color: #fff;
}
.banner-section .banner-slider-button-next:hover {
	color: var(--theme-color);
	border-color: #fff;
	background-color: #fff;
}
.banner-section .banner-slider-button-prev {
	position: relative;
	width: 68px;
	height: 68px;
	line-height: 68px;
	text-align: center;
	cursor: pointer;
	z-index: 9;
	font-size: 20px;
	transition: .5s;
	border: 2px solid #fff;
	color: #fff;
	top: 2px;
}
.banner-section .banner-slider-button-prev:hover {
	color: var(--theme-color);
	border-color: #fff;
	background-color: #fff;
}

.wps_floating_img_area img {
    position: absolute;
}








.wps_slider_bg_zoom{
  position:absolute;
  left:0px;
  top:0px;
  right: 0px;
  width:100%;
  height:100%;
  background-repeat: no-repeat;
  background-position: center;
  background-size: cover;
  -webkit-transform:scale(1);
  -ms-transform:scale(1);
  transform:scale(1);
  -webkit-transition: all 6000ms linear;
  -moz-transition: all 6000ms linear;
  -ms-transition: all 6000ms linear;
  -o-transition: all 6000ms linear;
  transition: all 6000ms linear;
}

.wps_slider_path .swiper-slide-active .wps_slider_bg_zoom{
  -webkit-transform:scale(1.15);
  -ms-transform:scale(1.15);
  transform:scale(1.15);
}

 /* CSS code End Here */
 
</style>';


?>



<section class="banner-1-section banner-section banner-style-three overflow-hidden  wps_slider_style_one slider_path wps_slider_path">
<div class="defult_slider_1 wps_hero_slider_block">	
   

    <div class="swiper banner-slider">
        <div class="swiper-wrapper ">
           
<?php foreach ( $repeat as $item ) : ?> 

 <?php 
					$slider_path_image = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image', '', $item ) );
					
					$slider_path_image_one = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_one', '', $item ) );
					$slider_path_image_two = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_two', '', $item ) );
					$slider_path_image_three = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_three', '', $item ) );
					$slider_path_image_four = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_four', '', $item ) );
					$slider_path_image_five = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_five', '', $item ) );
		
					
?>


            <!-- Slide Item -->
            <div class="swiper-slide">
				
      <div class="auto-container ">				
<div class="slider_path_slide wps_slider_bg_area">
	
		 <div class="wps_slider_bg_zoom" style="
    background-image: url(<?php echo esc_url($slider_path_image); ?>);
    background-size: <?php echo esc_attr($slider_path_background_size); ?>;
    background-position: <?php echo esc_attr($slider_path_background_position); ?>;
    background-repeat: <?php echo esc_attr($slider_path_background_repeat); ?>;
"></div>		
				
          
					
			
			
		<div class="slider_path_container slider_path_container_flex" >




<div class="wps_floating_img_area" >													
<?php if (isset($item['show_slider_image_one']) && esc_url($slider_path_image_one)) : ?>
    <div class="banner-feature-image wps_slider_img_one <?php echo esc_attr($item['slider_class_image_one']); ?>" >										
			<img src="<?php echo esc_url($slider_path_image_one); ?>" >										
</div>
<?php endif; ?>
													
<?php if (isset($item['show_slider_image_two']) && esc_url($slider_path_image_two)) : ?>
    <div class="banner-feature-image wps_slider_img_two <?php echo esc_attr($item['slider_class_image_two']); ?>" >
													
			<img src="<?php echo esc_url($slider_path_image_two); ?>" >										
													</div>
<?php endif; ?>
													
<?php if (isset($item['show_slider_image_three']) && esc_url($slider_path_image_three)) : ?>
    <div class="banner-feature-image wps_slider_img_three <?php echo esc_attr($item['slider_class_image_three']); ?>" >
													
			<img src="<?php echo esc_url($slider_path_image_three); ?>" >										
													</div>
<?php endif; ?>	
													
<?php if (isset($item['show_slider_image_four']) && esc_url($slider_path_image_four)) : ?>
    <div class="banner-feature-image wps_slider_img_four <?php echo esc_attr($item['slider_class_image_four']); ?>" >
													
			<img src="<?php echo esc_url($slider_path_image_four); ?>" >										
													</div>
<?php endif; ?>
													
<?php if (isset($item['show_slider_image_five']) && esc_url($slider_path_image_five)) : ?>
    <div class="banner-feature-image wps_slider_img_five <?php echo esc_attr($item['slider_class_image_five']); ?>" >
													
			<img src="<?php echo esc_url($slider_path_image_five); ?>" >										
													</div>
<?php endif; ?>													
</div> 

                    <div class="content-box">
                        <div class="inner wps_slide_test_area">

							
						<?php if ( ! empty($item['slider_path_subtitle']) ) : ?>
															<h4 class="slider_path_subtitle"><?php echo wp_kses($item['slider_path_subtitle'], $allowed_tags); ?></h4>
															<?php endif; ?>	
							
							
							
															<?php if ( ! empty($item['slider_path_title']) ) : ?>
															<h1 class="slider_path_title wow slideIn"> <?php echo wp_kses($item['slider_path_title'], $allowed_tags); ?></h1>
															<?php endif; ?>
							
                         	<?php if ( ! empty($item['slider_path_text']) ) : ?>
															<p class="slider_path_text"><?php echo wp_kses($item['slider_path_text'], $allowed_tags); ?></p>
															<?php endif; ?>
							
							
             
							
							
							<div class=" slider_path_button_container">
															<?php if ( ! empty($item['slider_path_button']) ) : ?>
															<div class=" slider_path_button_box link-box" style="max-width: 200px;">
																
																<a href=" <?php echo esc_url($item['slider_path_link']['url']);?>" class="slider_path_button"> <?php echo wp_kses($item['slider_path_button'], $allowed_tags); ?></a>
															</div>
															<?php endif; ?>
															<?php if ( ! empty($item['slider_path_button_2']) ) : ?>
															<div class="link-box slider_path_button_box_2" style="max-width: 200px;">
																<a href=" <?php echo esc_url($item['slider_path_link_2']['url']);?>" class="slider_path_button_2"> <?php echo wp_kses($item['slider_path_button_2'], $allowed_tags); ?></a>
															</div>
															<?php endif; ?>
														</div> 
							
							
                       
							
							
                        </div>
						
		
						
                    </div>
                </div>
            </div>
        </div>
    </div>


<?php endforeach?>

</div>
</div>



    <div class="banner-slider-nav">
        <div class="banner-slider-control banner-slider-button-prev"><span><i class="eicon-angle-left"></i></span></div>
        <div class="banner-slider-control banner-slider-button-next"><span><i class="eicon-angle-right"></i></span> </div>
    </div>



   </div>   
</section>


