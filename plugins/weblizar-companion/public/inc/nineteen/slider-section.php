<?php

defined( 'ABSPATH' ) or die();

class wl_companion_sliders
{
    
    public static function wl_companion_sliders_html() {
    ?>
        <!--our-Slider-->
        <div class="swiper-container home_slider main-slider">
            <?php if ( ! empty ( get_theme_mod('nineteen_slider_data' ) ) ) { ?>
                <div class="swiper-wrapper">
                    <?php $name_arr = unserialize(get_theme_mod( 'nineteen_slider_data'));
                          foreach ( $name_arr as $key => $value ) {
                    ?>
                    <div class="swiper-slide">
                        <figure class="slide-bgimg">
                            <?php if ( ! empty ( $value['slider_image'] ) ) { ?>
                                <img src="<?php echo esc_url($value['slider_image']); ?>" class="img-fluid " alt="<?php if ( ! empty ( $value['slider_name'] ) ) { esc_attr_e($value['slider_name'],WL_COMPANION_DOMAIN ); } ?>">
                            <?php } ?>
                        </figure>
                        <div class="content text-center">
                            <?php if ( ! empty ( $value['slider_name'] ) ) { ?>
                                <h1 class="title"><?php esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN ); ?></h1>
                            <?php } if ( ! empty ( $value['slider_desc'] ) ) { ?>
                            <p class="wl-caption"> <?php esc_html_e($value['slider_desc'],WL_COMPANION_DOMAIN); ?> </p>
                            <?php } if ( ! empty ( $value['slider_link'] ) ) { ?>
                            <div class=" btn_b2 mt-5">
                                <a href="<?php echo esc_url($value['slider_link']);  ?>" class="btn main-btn">
                                    <?php if ( ! empty ( $value['slider_text'] ) ) { esc_html_e($value['slider_text'],WL_COMPANION_DOMAIN); } ?>
                                </a>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <!-- If we need navigation buttons -->
                <div class="swiper-button-prev swiper-button-white"></div>
                <div class="swiper-button-next swiper-button-white"></div>
            <?php } ?>
        </div>
        <!--//our-Slider-->
    <?php 
    }
}

?>