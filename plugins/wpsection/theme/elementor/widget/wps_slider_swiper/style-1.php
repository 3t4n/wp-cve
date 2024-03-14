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
            speed: 1400,
            spaceBetween: 0,
            parallax: false,
            pagination: {
                el: ".swiper-pagination",
                clickable: true,
            },
            effect: "' . esc_js($settings['wps_slider_animation']) . '",
            navigation: {
                nextEl: ".banner-slider-button-next",
                prevEl: ".banner-slider-button-prev",
            },
            autoplay: {
                delay: 8000,
                disableOnInteraction: false
            },
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
.banner-section .content-box .swiper_title_anim {
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
.banner-section .swiper-slide-active .content-box .swiper_title_anim {
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


.slider_path_slide{
position:relative;
}



.slider_path_slide:before {
    position: absolute;
    content: " ";
    width: 100%;
    height: 100%;
    top: 0px;
    right: 0px;
    mix-blend-mode: multiply;
    z-index: 1;
}
.slider_path_slide:after {
    position: absolute;
    content:  " ";
    width: 100%;
    height: 100%;
    top: 0px;
    right: 0px;
    opacity: 0.6;
}

.slider_path_container{
position:relative;
z-index: 999;
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






/* slider text animation */

.slider-text-anim {
  display: inline-block;
  overflow: hidden;
  position: relative;
  padding-bottom: 5px;
}

.slider-text-anim:before {
  content: " ";
  width: 101%;
  height: 100%;
  position: absolute;
  top: 0;
  left: 100%;
  background: #fff; 
  -webkit-transition: 1s cubic-bezier(.858, .01, .068, .99);
  -o-transition: 1s cubic-bezier(.858, .01, .068, .99);
  transition: 1s cubic-bezier(.858, .01, .068, .99);
  z-index: 3;
  -webkit-transform: translateX(-100%);
  -ms-transform: translateX(-100%);
  transform: translateX(-100%);
  -webkit-transition-delay: 1s;
  -o-transition-delay: 1s;
  transition-delay: 1s;
}

.swiper-slide-active .slider-text-anim:before {
  -webkit-transform: translateX(1%);
  -ms-transform: translateX(1%);
  transform: translateX(1%);
}

.wps_slider_bg_area{
    overflow: hidden;
}



 /* CSS code End Here */
 
</style>';


?>



<section class="banner-1-section banner-section banner-style-three overflow-hidden  wps_slider_style_one slider_path wps_slider_path ">
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
		
		  
          

		  
<?php if($settings['zoomstyle'] == 'style-2'): ?>
    <div class="slider_path_slide wps_slider_bg_area">	
		
		<div class="wps_slider_bg_zoom" style="
            background-image: url(<?php echo esc_url($slider_path_image); ?>);
            background-size: <?php echo esc_attr($slider_path_background_size); ?>;
            background-position: <?php echo esc_attr($slider_path_background_position); ?>;
            background-repeat: <?php echo esc_attr($slider_path_background_repeat); ?>;
        "></div>

<?php endif; ?>	
	  
<?php if($settings['zoomstyle'] == 'style-1'): ?>			
    <div class="slider_path_slide" style="
        background-image: url(<?php echo esc_url($slider_path_image); ?>);
        background-size: <?php echo esc_attr($slider_path_background_size); ?>;
        background-position: <?php echo esc_attr($slider_path_background_position); ?>;
        background-repeat: <?php echo esc_attr($slider_path_background_repeat); ?>;
    ">
<?php endif; ?>


	
	
	
	
			
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
<?php							
        if($settings['slider_path_animation_title'] == 'anim-1') {
                        $animation_class = ' swiper_title_anim ';
                    }      
         else if($settings['slider_path_animation_title'] == 'anim-2') {
                        $animation_class = ' slider-text-anim ';
                    } 
        else if($settings['slider_path_animation_title'] == 'anim-3') {
                        $animation_class = ' custom_animation_3 ';
                    }   			
?>
<h1 class="slider_path_title <?php echo esc_attr($animation_class); ?>"> <?php echo wp_kses($item['slider_path_title'], $allowed_tags); ?></h1>												
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
	
		
<div class="wps_swiper_nav banner-slider-nav">
    <div class="wps_swiper_button banner-slider-control banner-slider-button-prev"><span><i class="eicon-angle-left"></i></span></div>
    <div class="wps_swiper_button banner-slider-control banner-slider-button-next"><span><i class="eicon-angle-right"></i></span></div>
</div>




			
<div class="wps_slide_sweeper_two">			
	<div class="wps_slider_two_dot swiper-pagination"></div>			
</div>

   </div> 
   </div>   
</section>


