<?php

defined( 'ABSPATH' ) or die();

class wl_companion_sliders_green_lantern
{
    
    public static function wl_companion_sliders_green_lantern_html() {
    ?>
        <?php if ( get_theme_mod( 'slider_choise', '1' ) == '1' ) {   ?>      
        <?php if ( ! empty ( get_theme_mod('green_lantern_slider_data' ) ) ) { ?>
            <div class="rev-slider-full">
                <div class="rev-slider-banner-full  rev-slider-full">       
                    <ul>    
                        <?php 
                        $name_arr = unserialize(get_theme_mod( 'green_lantern_slider_data'));
                       
                        foreach ( $name_arr as $key => $value ) {    
                        ?>          
                            <li data-transition="fade" data-slotamount="7" data-masterspeed="300" >
                                <img src="<?php echo esc_url(get_theme_mod('slider_background')); ?>"  alt="rev-full1" data-fullwidthcentering="on">
                                <?php if ( ! empty ( $value['slider_image'] ) ) { ?>
                                    <div class="tp-caption lfb stb stl"  data-x="0" data-y="120" data-speed="1500" data-start="200" data-easing="easeOutExpo" data-end="6000" data-endspeed="500">
                                        
                                     <img src="<?php echo esc_url($value['slider_image']); ?>" class="img-responsive img-slide" alt="First slide">
                                    </div>
                                <?php } ?>
                                <div class="tp-caption very_large_text2 sft str"
                                     data-x="672"
                                     data-y="100"
                                     data-speed="300"
                                     data-start="1800"
                                     data-easing="easeOutCubic" data-end="5800" data-endspeed="500">
                                </div>
                                <?php if ( ! empty ( $value['slider_name'] ) ) { ?>
                                    <div class="tp-caption main_color_text sfl str "
                                         data-x="672"
                                         data-y="145"
                                         data-speed="200"
                                         data-start="2000"
                                         data-easing="easeOutCubic" data-end="5600" data-endspeed="500">
                                         <h1 class="weblizar_slide_title"><?php esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN); ?></h1>
                                    </div>
                                <?php }
                                if ( ! empty ( $value['slider_desc'] ) ) { ?>
                                    <div class="tp-caption default_text sfl str "
                                         data-x="672"
                                         data-y="200"
                                         data-speed="200"
                                         data-start="2200"
                                         data-easing="" data-end="5400" data-endspeed="500"  > 
                                        <span class="slide_desc"><?php esc_html_e($value['slider_desc'],WL_COMPANION_DOMAIN); ?></span>
                                    </div>
                                <?php } if ( ! empty ( $value['slider_text'] ) ) { ?>             
                                    <div class="tp-caption sfl str weblizar_slide_btn_text"
                                         data-x="672"
                                         data-y="290"
                                         data-speed="200"
                                         data-start="2400"
                                         data-easing="" data-end="5000" data-endspeed="500" >                   
                                        <a href="<?php echo esc_url($value['slider_link']);  ?>" class="btn btn-primary btn-lg">
                                            <?php esc_html_e($value['slider_text'],WL_COMPANION_DOMAIN);  ?></a>
                                    </div>  
                                <?php } ?>            
                            </li>
                        <?php } ?>
                    </ul>       
                    <div class="tp-bannertimer tp-bottom"></div>
                </div>
            </div>
                        
        <?php } } elseif ( get_theme_mod( 'slider_choise', '1' ) == '2' ) { ?>
            <?php if ( ! empty ( get_theme_mod('green_lantern_slider_data' ) ) ) { ?>
                <div class="swiper-container swiper-container-slider2">
                <div class="swiper-wrapper">
                    <?php 
                    $name_arr = unserialize(get_theme_mod( 'green_lantern_slider_data'));
                    foreach ( $name_arr as $key => $value ) {    
                    ?>    
                        <div class="swiper-slide">
                            <img src="<?php echo esc_url($value['slider_image']); ?>" class="img-responsive" alt="<?php esc_attr_e($value['slider_name'],WL_COMPANION_DOMAIN); ?>"> 
                            <div class="container">
                                <div class="carousel-caption">
                                    
                                    <div class="carousel-text">
                                        <?php if ( ! empty ( $value['slider_name'] ) ) { ?>
                                            <h1 class="animation animated-item-2 head_1 title"><?php esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN); ?></h1>  
                                        <?php } ?>
                                        <?php if ( ! empty ( $value['slider_desc'] ) ) { ?> 
                                        <ul class="list-unstyled carousel-list">
                                          <li class="animation animated-item-3 desc_1 desc"><?php esc_html_e($value['slider_desc'],WL_COMPANION_DOMAIN); ?></li>
                                        </ul>
                                        <?php 
                                        } if ( ! empty ( $value['slider_text'] ) ) { ?>
                                            <a class="enigma_blog_read_btn  animation animated-item-3 rdm_1 btn" href="<?php echo esc_url($value['slider_link']);  ?>" role="button"><?php esc_html_e($value['slider_text'],WL_COMPANION_DOMAIN);  ?></a>
                                        <?php } ?>
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
                <!-- Add Pagination -->
                <div class="swiper-pagination swiper'"></div>
                <div class="swiper-button-next swiper' swiper-button-next-cont swiper-button-white"></div>
                <div class="swiper-button-prev swiper' swiper-button-prev-cont swiper-button-white"></div>
            </div>
            <?php } 
        }
    } 
} ?>