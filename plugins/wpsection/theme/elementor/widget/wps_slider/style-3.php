<?php
/**
 * Widget Render: slider
 *
 * @package widgets/slider/views/template-2.php
 * @copyright rashid87
 */

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use Elementor\Utils;



$unique_id = uniqid();
$settings = $this->get_settings_for_display();
$repeat    = $settings['repeat'] ;


?>


 <?php
      echo '
     <script>
 jQuery(document).ready(function($)
 {

//put the js code under this line 

  if ($(".banner-carousel-one").length) {
        $(".banner-carousel-one").owlCarousel({
            
   animateOut: "fadeOut",
            animateIn: "fadeIn",
            loop:true,
            margin:0,
            dots: true,
            nav:true,
            singleItem:true,
            smartSpeed: 500,
        
			
			autoplay: ' . json_encode($settings['hero_slide_auto_loop'] === '1') . ',
			
            autoplayTimeout:6000,
           
	   responsive:{
                    0:{
                        items:1
                    },
                    480:{
                        items:1
                    },
                    600:{
                        items: ' . json_encode($settings['hero_wps_columns_tab']) . ' 
                    },
                    900:{
                        items: ' . json_encode($settings['hero_wps_columns_tab']) . ' 
                    },
                    1024:{
                        items:' . json_encode($settings['hero_wps_columns']) . ' 
                    },
                }
		
			
        });         
    }

//put the code above the line 

  });
</script>';

echo '
 <style>
 
 /* CSS code Will be here */
.wps_slider_style_one .slider_path_slide{
	position:relative;
	z-index:1;
	padding-top:150px;
}

.wps_slider_style_one .slider_path_slide:before{
  position: absolute;
  content: \'\';
  width: 100%;
  height: 100%;
  top: 0px;
  right: 0px;
  mix-blend-mode: multiply;
  z-index: -1;
  background:red;
}

.wps_slider_style_one .slider_path_slide:after{
  position: absolute;
  content: \'\';
  width: 100%;
  height: 100%;
  top: 0px;
  right: 0px;
  opacity: 0.6; 
  background:#222;
  
  z-index: -1;
	
	
}



.wps_slider_img_one img,.wps_slider_img_two img,.wps_slider_img_three img , .wps_slider_img_four img,.wps_slider_img_five img {
        position: absolute;
		top:0px;
		left:0px;
} 
 /* CSS code End Here */
 
 

</style>';

?>

 
<div id="wpsection_id-<?php echo esc_attr( $unique_id ); ?>" class="slider_path  slider_path_style-<?php echo esc_attr( $style ); ?>">
   
    <!-- Slider One -->
        <section class="defult_slider_1">

            <div class="banner-carousel-one owl-theme owl-carousel owl_dots_none ">
        
                <?php foreach ( $repeat as $item ) :

                $slider_path_image = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image', '', $item ) );
                $slider_path_image2 = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image2', '', $item ) );
                $slider_path_image3 = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image3', '', $item ) );
				
				
					$slider_path_image_one = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_one', '', $item ) );
					$slider_path_image_two = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_two', '', $item ) );
					$slider_path_image_three = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_three', '', $item ) );
					$slider_path_image_four = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_four', '', $item ) );
					$slider_path_image_five = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_five', '', $item ) );
                ?>      
                <!-- Slide -->
  

                <div class="slider_path_slide" style="background-image:url(<?php echo esc_url( $slider_path_image ); ?>)">
                    <div class="slider_path_container slider_path_container_flex" >
                 
						
<div class="wps_floating_img_area" >													
<?php if (isset($item['show_slider_image_one']) && esc_url($slider_path_image_one)) : ?>
<div class="wps_slider_img_one <?php echo esc_attr($item['slider_class_image_one']); ?>" >										
			<img src="<?php echo esc_url($slider_path_image_one); ?>" >										
</div>
<?php endif; ?>
													
<?php if (isset($item['show_slider_image_two']) && esc_url($slider_path_image_two)) : ?>
    <div class="wps_slider_img_two <?php echo esc_attr($item['slider_class_image_two']); ?>" >
													
			<img src="<?php echo esc_url($slider_path_image_two); ?>" >										
													</div>
<?php endif; ?>
													
<?php if (isset($item['show_slider_image_three']) && esc_url($slider_path_image_three)) : ?>
    <div class="wps_slider_img_three <?php echo esc_attr($item['slider_class_image_three']); ?>" >
													
			<img src="<?php echo esc_url($slider_path_image_three); ?>" >										
													</div>
<?php endif; ?>	
													
<?php if (isset($item['show_slider_image_four']) && esc_url($slider_path_image_four)) : ?>
    <div class="wps_slider_img_four <?php echo esc_attr($item['slider_class_image_four']); ?>" >
													
			<img src="<?php echo esc_url($slider_path_image_four); ?>" >										
													</div>
<?php endif; ?>
													
<?php if (isset($item['show_slider_image_five']) && esc_url($slider_path_image_five)) : ?>
    <div class="wps_slider_img_five <?php echo esc_attr($item['slider_class_image_five']); ?>" >
													
			<img src="<?php echo esc_url($slider_path_image_five); ?>" >										
													</div>
<?php endif; ?>													
</div> 						
						
						
                        <div class="slider_path_left">  
                                <?php if ( ! empty($item['slider_path_subtitle']) ) : ?>
                                <h5 class="slider_path_subtitle"> <?php echo wp_kses($item['slider_path_subtitle'], $allowed_tags); ?></h5>
                                <?php endif; ?>


                                <?php if ( ! empty($item['slider_path_title']) ) : ?>
                                <h2 class="slider_path_title"><?php echo wp_kses($item['slider_path_title'], $allowed_tags); ?></h2>
                                <?php endif; ?>


                                <?php if ( ! empty($item['slider_path_text']) ) : ?>
                                <p class="slider_path_text"> <?php echo wp_kses($item['slider_path_text'], $allowed_tags); ?></p>
                                <?php endif; ?>

                            <div class=" slider_path_button_container">
                                 <?php if ( ! empty($item['slider_path_button']) ) : ?>
                                <div class=" slider_path_button_box">
                                    <a href=" <?php echo esc_url($item['slider_path_link']['url']);?>" class="slider_path_button"> <?php echo wp_kses($item['slider_path_button'], $allowed_tags); ?></a>
                                </div>
                                <?php endif; ?>

                                <?php if ( ! empty($item['slider_path_button_2']) ) { ?>
                                <div class=" slider_path_button_box_2"><a href=" <?php echo esc_url($item['slider_path_link_2']['url']);?>" class="slider_path_button_2">  <?php echo wp_kses($item['slider_path_button_2'], $allowed_tags); ?></a>
                                </div>
                                <?php } ?>
                            </div> 
                        </div> 

                            <div class="slider_path_right_image">
                                <img src="<?php echo esc_url( $slider_path_image2 ); ?>" alt="">
                            </div>
                    </div>
                </div>              
                <!-- Slide -->
            <?php endforeach; ?>

             
            </div>
        </section>
        <!-- End Slider One -->
</div>