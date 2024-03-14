<?php

defined( 'ABSPATH' ) or die();

class wl_companion_sliders_scoreline
{
    
    public static function wl_companion_sliders_scoreline_html() {
    ?>
        <?php
        if ( get_theme_mod( 'slider_choise', '1' ) == '1' ) {   ?>      
        <?php if ( ! empty ( get_theme_mod('scoreline_slider_data' ) ) ) { ?>
            <div class="slider-wrapper">
                <div class="swiper-container home_slider">
                    <div class="swiper-wrapper">
                        <?php 
                        $name_arr = unserialize(get_theme_mod( 'scoreline_slider_data'));
                        foreach ( $name_arr as $key => $value ) {    
                        ?>    
                            <div class="swiper-slide">
                                <img src="<?php echo esc_url($value['slider_image']); ?>" class="img-responsive" alt="<?php  esc_attr_e($value['slider_name'],WL_COMPANION_DOMAIN); ?>"> 
                                <div class="carousel-caption">
                                <h1 class="animation animated-item-1"><?php esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN); ?></h1>            
                                <h2 class="animation animated-item-2"><?php esc_html_e($value['slider_desc'],WL_COMPANION_DOMAIN); ?></h2>
                                <a class="btn hvr-grow-shadow animation animated-item-3" href="<?php echo esc_url($value['slider_link']);  ?>" role="button"><?php esc_html_e($value['slider_text'],WL_COMPANION_DOMAIN);  ?></a>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class="swiper-button-prev swiper-button-white swiper-button-prev1"></div>
                    <div class="swiper-button-next swiper-button-white swiper-button-next1"></div>
                </div>
            </div>
        <?php } } elseif ( get_theme_mod( 'slider_choise', '1' ) == '2' ) { ?>
            <?php if ( ! empty ( get_theme_mod('scoreline_slider_data' ) ) ) { ?>
                <div class="slider-wrapper">
                    <div class="swiper-container home_slider2">
                        <div class="swiper-wrapper">
                            <?php 
                            $name_arr = unserialize(get_theme_mod( 'scoreline_slider_data'));
                            foreach ( $name_arr as $key => $value ) {    
                            ?>    
                                <div class="swiper-slide">
                                    <img src="<?php echo esc_url($value['slider_image']); ?>" class="img-responsive" alt="<?php  esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN); ?>"> 
                                    <div class="carousel-caption">
                                    <h1 class="animation animated-item-1"><?php esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN); ?></h1>            
                                    <h2 class="animation animated-item-2"><?php esc_html_e($value['slider_desc'],WL_COMPANION_DOMAIN); ?></h2>
                                    <a class="btn hvr-grow-shadow animation animated-item-3" href="<?php echo esc_url($value['slider_link']);  ?>" role="button"><?php esc_html_e($value['slider_text'],WL_COMPANION_DOMAIN);  ?></a>
                                    </div>
                                </div>
                            <?php  } ?>
                        </div>
                        <div class="swiper-button-prev swiper-button-white swiper-button-prev1"></div>
                        <div class="swiper-button-next swiper-button-white swiper-button-next1"></div>
                    </div>
                </div>
            <?php } 
        }
    } 
} ?>