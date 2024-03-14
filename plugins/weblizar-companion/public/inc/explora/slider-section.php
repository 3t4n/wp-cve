<?php

defined( 'ABSPATH' ) or die();

class wl_companion_sliders_explora
{
    
    public static function wl_companion_sliders_explora_html() {
    ?>
        <?php
        if ( get_theme_mod( 'slider_choise', '1' ) == '1' ) {   ?>      
        <?php if ( ! empty ( get_theme_mod('explora_slider_data' ) ) ) { ?>
            <div class="explora_options_slider">
                <div class="row w_slider">
                    <div class="swiper-container explora_slider">
                        <div class="swiper-wrapper ">
                            <?php 
                            $name_arr = unserialize(get_theme_mod( 'explora_slider_data'));
                            foreach ( $name_arr as $key => $value ) {    
                            ?>         
                                <div class="swiper-slide">
                                    <img src="<?php echo esc_url($value['slider_image']); ?>" alt="<?php  the_title(); ?>" class="home_slider img-responsive" />
                                    <div class="overlay"></div>
                                    <div class="container">
                                        <div class="carousel-caption">
                                            <h1 class="animation animated-item-1"><span><?php esc_html_e( $value['slider_name'],WL_COMPANION_DOMAIN); ?></span></h1>
                                            <h2 class="animation animated-item-2"><?php esc_html_e($value['slider_desc'],WL_COMPANION_DOMAIN); ?></h2>
                                            <?php if ( ! empty ( $value['slider_text'] ) ) { ?>
                                                <a href="<?php echo esc_url($value['slider_link']);  ?>" class="btn s_link animation animated-item-2" alt=""><?php esc_attr_e($value['slider_text'],WL_COMPANION_DOMAIN);  ?></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div> 
                            <?php  } ?>
                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-prev swiper-button-prev5"></div>
                        <div class="swiper-button-next swiper-button-next5"></div>
                    </div>  
                </div>
            </div>
        <?php } } elseif ( get_theme_mod( 'slider_choise', '1' ) == '2' ) { ?>
            <?php if ( ! empty ( get_theme_mod('explora_slider_data' ) ) ) { ?>
                <section id="demos">
                    <div class="owl-carousel owl-theme">
                        <?php 
                        $name_arr = unserialize(get_theme_mod( 'explora_slider_data'));
                        foreach ( $name_arr as $key => $value ) {    
                        ?>    
                            <div class="item">
                              <img src="<?php echo esc_url($value['slider_image']); ?>" alt="<?php  the_title(); ?>"/>
                              <div class="overlay"></div>
                                <div class="container">
                                    <div class="carousel-caption">
                                        <h1 class="animation animated-item-1"><span><?php esc_html_e( $value['slider_name'],WL_COMPANION_DOMAIN); ?></span></h1>
                                        <h2 class="animation animated-item-2"><?php esc_html_e($value['slider_desc'],WL_COMPANION_DOMAIN); ?></h2>
                                        <?php if ( ! empty ( $value['slider_text'] ) ) { ?>
                                            <a href="<?php echo esc_url($value['slider_link']);  ?>" class="btn s_link animation animated-item-2" alt=""><?php esc_attr_e($value['slider_text'],WL_COMPANION_DOMAIN);  ?></a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php  } ?>
                    </div>
                </section>
            <?php } 
        }
    } 
} ?>