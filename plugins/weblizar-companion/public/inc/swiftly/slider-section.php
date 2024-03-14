<?php

defined('ABSPATH') or die();

class wl_companion_sliders_swiftly {

    public static function wl_companion_sliders_swiftly_html() {
?>
        <?php
        $slider_anim = get_theme_mod('slider_anim');
        if ($slider_anim == 'fadeIn') {
            $slider_class = 'fadein';
        } else {
            $slider_class = 'slide';
        }
        ?>
        <?php
        $name_arr = unserialize(get_theme_mod('enigma_slider_data'));
        if (!empty($name_arr)) { ?>

            <section id="home-section" class="hero">
                <div class="home-slider owl-carousel">
                    <?php 
                    foreach ($name_arr as $key => $value) { ?>

                    <div class="slider-item">
                        <div class="overlay"></div>
                        <div class="container-fluid px-md-0">
                            <div class="row d-md-flex no-gutters slider-text align-items-end justify-content-end" data-scrollax-parent="true">
                                <div class="one-third order-md-last img" style="background-image:url(<?php echo esc_url($value['slider_image']); ?>);">
                                    <div class="overlay"></div>
                                    <div class="overlay-1"></div>
                                </div>
                                <div class="one-forth d-flex  align-items-center ftco-animate" data-scrollax=" properties: { translateY: '70%' }">
                                    <div class="text">
                                        <span class="subheading">
                                            <?php esc_html_e($value['slider_name'], WL_COMPANION_DOMAIN); ?>
                                        </span>
                                        <h2 class="mb-4 mt-3"><?php esc_html_e($value['slider_desc'], WL_COMPANION_DOMAIN); ?></h1>
                                        <p>
                                            <!--
                                            <a href="#" class="btn btn-primary">Hire me</a>
                                            -->
                                            <a href="<?php echo esc_url($value['slider_link']); ?>" class="btn btn-primary btn-outline-primary">
                                                <?php if (!empty($value['slider_text'])) {
                                                        esc_html_e($value['slider_text'], WL_COMPANION_DOMAIN);
                                                } ?> 
                                            </a>
                                            
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php } ?>
                    
                </div>
            </section>
        
<?php  }
    }
} ?>