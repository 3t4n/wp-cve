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

//put the js code under this line 

if ($(".wpsection_banner-carousel-one").length) {
    $(".wpsection_banner-carousel-one").owlCarousel({

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


<div id="<?php echo esc_attr( $unique_id ); ?>" class="wps_slider_style_one slider_path wps_slider_path  slider_path_style-<?php echo esc_attr( $style ); ?> <?php echo esc_attr( $unique_id ); ?>">       
<div class="defult_slider_1 wps_hero_slider_block">

				<div class="wpsection_banner-carousel-one owl-theme owl-carousel owl_dots_one " >
	
							<?php foreach ( $repeat as $item ) : ?>   

								
									<?php  if ( 'template' === $item['slider_type'] ) : ?>
									<div class="slider_path_elemntor">
										<?php 
											$post_id = slider_path_elemntor_content($item['slider_path_elemntor_template']);
											echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($post_id); ?>
									</div>
									<?php endif;?>
								
									<?php  if ( 'content' === $item['slider_type'] ) :  ?>

									 <?php 
					$slider_path_image = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image', '', $item ) );
					
					$slider_path_image_one = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_one', '', $item ) );
					$slider_path_image_two = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_two', '', $item ) );
					$slider_path_image_three = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_three', '', $item ) );
					$slider_path_image_four = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_four', '', $item ) );
					$slider_path_image_five = wpsection()->get_settings_atts( 'url', '', wpsection()->get_settings_atts( 'slider_path_image_five', '', $item ) );
		
					
					
									 ?>
										<div class="slider_path_slide "  style=" 
										background-image:url(<?php echo esc_url( $slider_path_image ); ?>);  
										background-size: <?php echo esc_attr($slider_path_background_size);?>;  
										background-position: <?php echo esc_attr($slider_path_background_position);?>; 
										
											">	
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
															<h5 class="slider_path_subtitle"><?php echo wp_kses($item['slider_path_subtitle'], $allowed_tags); ?></h5>
															<?php endif; ?>

															<?php if ( ! empty($item['slider_path_title']) ) : ?>
															<h2 class="slider_path_title wow slideIn"> <?php echo wp_kses($item['slider_path_title'], $allowed_tags); ?></h2>
															<?php endif; ?>


															<?php if ( ! empty($item['slider_path_text']) ) : ?>
															<p class="slider_path_text"><?php echo wp_kses($item['slider_path_text'], $allowed_tags); ?></p>
															<?php endif; ?>

														<div class=" slider_path_button_container">
															<?php if ( ! empty($item['slider_path_button']) ) : ?>
															<div class=" slider_path_button_box" style="max-width: 200px;">
																
																<a href=" <?php echo esc_url($item['slider_path_link']['url']);?>" class="slider_path_button"> <?php echo wp_kses($item['slider_path_button'], $allowed_tags); ?></a>
															</div>
															<?php endif; ?>
															<?php if ( ! empty($item['slider_path_button_2']) ) : ?>
															<div class=" slider_path_button_box_2" style="max-width: 200px;">
																<a href=" <?php echo esc_url($item['slider_path_link_2']['url']);?>" class="slider_path_button_2"> <?php echo wp_kses($item['slider_path_button_2'], $allowed_tags); ?></a>
															</div>
															<?php endif; ?>
														</div> 
													
													
													
												
 													
													
												
											
													
												</div> 
										
										 </div> 
											</div>
										  
               
                			<?php endif;?> 
				
   					<?php endforeach?>
				
         		</div>    

		

</div>   
	

</div>