<?php

defined( 'ABSPATH' ) or die();

class wl_companion_slider_travel {
    
    public static function wl_companion_slider_travel_html() {
    ?>
        <!--our-Slider-->
        <div class="main-sliders">
            <div class="owl-carousel owl-carousel_home owl-theme fxSoftScale">
                <?php 
                if ( ! empty ( get_theme_mod('travelogged_slider_data' ) ) ) {
                    $name_arr = unserialize(get_theme_mod( 'travelogged_slider_data'));
                        foreach ( $name_arr as $key => $value ) {
                ?>
                <!--slider item-->
                <div class="item">
                <?php if ( ! empty ( $value['slider_image'] ) ) { ?>
                    <img src="<?php echo esc_url($value['slider_image']); ?>" class="img-fluid" alt="<?php if ( ! empty ( $value['slider_name'] ) ) {  esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN); } ?>"/>
                <?php } ?>
                    <div class="container slider-caption content-center justify-content-center">
                        <div class=" slider-caption-inner ">
                            <h2> Welcome to </h2>
                            <?php if ( ! empty ( $value['slider_name'] ) ) { ?>
                                <h1> <?php  esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN); ?> </h1>
                            <?php } if ( ! empty ( $value['slider_desc'] ) ) { ?>
                                <p> <?php  esc_html_e($value['slider_desc'],WL_COMPANION_DOMAIN); ?> </p>
                            <?php } if ( ! empty ( $value['slider_link'] ) ) { ?>
                                <button type="button" class="btn btn-primary"> <a href="<?php echo esc_url($value['slider_link']);  ?>">
                                    <?php if ( ! empty ( $value['slider_text'] ) ) {  esc_html_e($value['slider_text'],WL_COMPANION_DOMAIN); } ?></a>
                                </button>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!--slider item-->
                <?php } } ?>
            </div>
        </div>
        <!--//our-Slider-->
    <?php 
    }
}

?>