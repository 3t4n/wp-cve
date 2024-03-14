<?php

defined('ABSPATH') or die();

class wl_companion_sliders_wl {

    public static function wl_companion_sliders_wl_html() {
?>

        <?php
        if (get_theme_mod('slider_choise', '1') == '1') {   ?>
            <?php if (!empty(get_theme_mod('weblizar_slider_data'))) { ?>
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <?php

                        $j = 1;
                        $name_arr = unserialize(get_theme_mod('weblizar_slider_data'));
                        foreach ($name_arr as $key => $value) { ?>
                            <div class="carousel-item <?php if ($j == 1) {
                                                            echo esc_attr("active");
                                                        } ?>">
                                <?php
                                if (!empty($value['slider_image'])) {   ?>
                                    <img src="<?php echo esc_url($value['slider_image']); ?>" class="img-responsive" alt="First slide">
                                <?php }  ?>

                                <div class="container">
                                    <div class="carousel-caption">
                                        <?php

                                        if (!empty($value['slider_name'])) {  ?>
                                            <h1 class="weblizar_slide_title"><?php esc_html_e($value['slider_name'], WL_COMPANION_DOMAIN); ?></h1>
                                        <?php }

                                        if (!empty($value['slider_desc'])) {  ?>
                                            <p class="weblizar_slide_desc"><?php esc_html_e($value['slider_desc'], WL_COMPANION_DOMAIN); ?></p>
                                        <?php }

                                        if (!empty($value['slider_link'])) { ?>
                                            <p class="weblizar_slide_btn_text"><a class="btn btn-lg btn-primary" href="<?php echo esc_url($value['slider_link']);  ?>" role="button"><?php if (!empty($value['slider_text'])) {
                                                                                                                                                                                            esc_html_e($value['slider_text'], WL_COMPANION_DOMAIN);
                                                                                                                                                                                        } ?> </a></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                        <?php $j++;
                        } ?>

                    </div>
                    <a class="carousel-control-prev" href="#myCarousel" data-slide="prev"><span class="carousel-control-prev-icon"></span></a>
                    <a class="carousel-control-next" href="#myCarousel" data-slide="next"><span class="carousel-control-next-icon"></span></a>
                </div><!-- /.carousel -->

            <?php }
        } elseif (get_theme_mod('slider_choise', '1') == '2') { ?>
            <div class="guardian_options_slider">
                <div class="swiper-container top_slider">
                    <?php if (!empty(get_theme_mod('weblizar_slider_data'))) { ?>
                        <div class="swiper-wrapper ">
                            <?php
                            $name_arr = unserialize(get_theme_mod('weblizar_slider_data'));
                            foreach ($name_arr as $key => $value) { ?>
                                <div class="swiper-slide">
                                    <img src="<?php echo esc_url($value['slider_image']); ?>" class="img-responsive" alt="First slide">
                                    <div class="overlay"></div>
                                    <div class="carousel-caption">
                                        <?php

                                        if (!empty($value['slider_name'])) {  ?>
                                            <p class="guardian_slide_title animation animated-item-1"><strong><?php esc_html_e($value['slider_name'], WL_COMPANION_DOMAIN); ?></strong></p>
                                        <?php }

                                        if (!empty($value['slider_desc'])) { ?>
                                            <p class="guardian_slide_desc animation animated-item-2"><?php esc_html_e($value['slider_desc'], WL_COMPANION_DOMAIN); ?></p>
                                        <?php }

                                        if (!empty($value['slider_link'])) { ?>
                                            <p class="slider-btn"><a class="btn btn-lg btn-primary animation animated-item-3" target="_blank" href="<?php echo esc_url($value['slider_link']);  ?>" role="button"><?php if (!empty($value['slider_text'])) {
                                                                                                                                                                                                                        esc_html_e($value['slider_text'], WL_COMPANION_DOMAIN);
                                                                                                                                                                                                                    } ?></a></p>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } ?>

                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-prev swiper-button-prev5 swiper-button-white"></div>
                        <div class="swiper-button-next swiper-button-next5 swiper-button-white"></div>
                    <?php } ?>
                </div>
            </div>
<?php }
    }
} ?>