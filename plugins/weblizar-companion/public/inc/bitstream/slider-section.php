<?php

defined('ABSPATH') or die();

class wl_companion_sliders_bitstream
{
    
    public static function wl_companion_sliders_bitstream_html()
    {
        ?>
        <!-- Main-start -->
        <main>
            <div class="main_slider">
                <?php if (! empty(get_theme_mod('bitstream_slider_data')) ) { ?>
                    <div class="owl-carousel home-slider slider_1">
                        <?php 
                        $name_arr = unserialize(get_theme_mod('bitstream_slider_data'));
                        foreach ( $name_arr as $key => $value ) {
                            ?>
                            <div class="item slider_item">
                                <div class="container slider_cnt">
                                    <div class="row content-center">
                                        <div class="col-lg-7 col-sm-12 slide_col">
                                            <div class="slide-content">
                                                <?php if (!empty($value['slider_name']) ) { ?>
                                                    <h2><?php  esc_attr_e($value['slider_name'],WL_COMPANION_DOMAIN); ?></h2>
                                                <?php } if (!empty($value['slider_desc']) ) { ?>
                                                    <p>
                                                        <?php esc_attr_e($value['slider_desc'],WL_COMPANION_DOMAIN); ?>
                                                    </p>
                                                <?php } if (! empty($value['slider_link']) ) { ?>
                                                    <div class="slider_btn">
                                                        <a href="<?php echo esc_url($value['slider_link']);  ?>" class="btn btn-theme btn-active"> <?php if (! empty($value['slider_text']) ) { 
                                                             esc_attr_e($value['slider_text'],WL_COMPANION_DOMAIN); 
                                                                 } ?> <i class="flaticon-double-angle-pointing-to-right"> </i></a>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <?php if (! empty($value['slider_image']) ) { ?>
                                            <div class="col-lg-5 col-sm-12 slide_img">
                                                <div class="right-img-box">
                                                    <img src="<?php echo esc_url($value['slider_image']); ?>" class="img-fluid" alt="<?php if (! empty($value['slider_name']) ) { esc_attr_e($value['slider_name'],WL_COMPANION_DOMAIN); 
                                                              } ?>">
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </main>
        <!-- Main-end -->
    <?php } 
} ?>
