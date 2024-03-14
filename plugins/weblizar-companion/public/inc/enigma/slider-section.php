<?php

defined('ABSPATH') or die();

class wl_companion_sliders_enigma {

    public static function wl_companion_sliders_enigma_html() {
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

            <div id="myCarousel" class="carousel  <?php esc_attr_e($slider_class, WL_COMPANION_DOMAIN); ?>" data-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $j = 1;
                    foreach ($name_arr as $key => $value) {
                    ?>
                        <div class="carousel-item <?php if ($j == 1) echo esc_attr("active"); ?>">
                            <img src="<?php echo esc_url($value['slider_image']); ?>" class="img-responsive" alt="<?php if (!empty($value['slider_name'])) { esc_attr_e($value['slider_name'], WL_COMPANION_DOMAIN); } ?>">
                            <div class="container">
                                <div class="carousel-caption">
                                    <div class="carousel-text">
                                        <?php
                                        $animate_type_title = get_theme_mod('animate_type_title');
                                        if (!empty($value['slider_name'])) { ?>
                                            <h1 class="animated <?php if (!empty($animate_type_title)) { esc_attr_e(get_theme_mod('animate_type_title'), WL_COMPANION_DOMAIN); } else  esc_attr_e('bounceInRight', WL_COMPANION_DOMAIN); ?>"><?php esc_html_e($value['slider_name'], WL_COMPANION_DOMAIN); ?>
                                            </h1>
                                        <?php }
                                        $animate_type_desc = get_theme_mod('animate_type_desc');
                                        if (!empty($value['slider_desc'])) { ?>
                                            <ul class="list-unstyled carousel-list">
                                                <li class="animated <?php if (!empty($animate_type_desc)) {
                                                                        esc_attr_e(get_theme_mod('animate_type_desc'), WL_COMPANION_DOMAIN);
                                                                    } else esc_attr('bounceInLeft'); ?>">
                                                    <?php esc_html_e($value['slider_desc'], WL_COMPANION_DOMAIN); ?>
                                                </li>
                                            </ul>
                                        <?php }
                                        if (!empty($value['slider_link'])) { ?>
                                            <a class="enigma_blog_read_btn animated bounceInUp" href="<?php echo esc_url($value['slider_link']);  ?>" role="button">
                                                <?php if (!empty($value['slider_text'])) {
                                                    esc_html_e($value['slider_text'], WL_COMPANION_DOMAIN);
                                                } ?> </a>
                                        <?php } ?>
                                    </div>

                                </div>
                            </div>
                        </div>
                    <?php $j++;
                    } ?>
                </div>
                <ol class="carousel-indicators">
                    <?php for (
                        $i = 0;
                        $i < $j - 1;
                        $i++
                    ) { ?>
                        <li data-target="#myCarousel" data-slide-to="<?php esc_attr_e($i, WL_COMPANION_DOMAIN); ?>" <?php if ($i == 0) { echo esc_attr('class="active"'); } ?>></li>
                    <?php } ?>
                </ol>
                <a class="carousel-control-prev" href="#myCarousel" data-slide="prev"><span class="carousel-control-prev-icon"></span></a>
                <a class="carousel-control-next" href="#myCarousel" data-slide="next"><span class="carousel-control-next-icon"></span></a>
                <div class="enigma_slider_shadow"></div>
            </div>
            <!-- /.carousel -->
        <?php } else { ?>

            <div id="myCarousel" class="carousel  <?php esc_attr_e($slider_class, WL_COMPANION_DOMAIN); ?>" data-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    $j = 1;
                    for ($i = 1; $i <= 3; $i++) {
                        $slide_image = get_theme_mod('slide_image_' . $i);
                        if (!empty($slide_image)) {
                    ?>
                            <div class="carousel-item <?php if ($j == 1) echo esc_attr("active"); ?>">
                                <img src="<?php echo esc_url(get_theme_mod('slide_image_' . $i)); ?>" class="img-responsive" alt="<?php esc_attr_e(get_theme_mod('slide_title_' . $i), WL_COMPANION_DOMAIN); ?>">
                                <div class="container">
                                    <div class="carousel-caption">
                                        <div class="carousel-text">
                                            <?php
                                            $slide_title = get_theme_mod('slide_title_' . $i);
                                            $animate_type_title = get_theme_mod('animate_type_title');
                                            if (!empty($slide_title)) { ?>
                                                <h1 class="animated <?php if (!empty($animate_type_title)) {
                                                                        esc_attr_e(get_theme_mod('animate_type_title'), WL_COMPANION_DOMAIN);
                                                                    } else  esc_attr_e('bounceInRight', WL_COMPANION_DOMAIN); ?> head_<?php esc_attr_e($i, WL_COMPANION_DOMAIN) ?>"><?php esc_html_e(get_theme_mod('slide_title_' . $i), WL_COMPANION_DOMAIN); ?>
                                                </h1>
                                            <?php }
                                            $slide_desc = get_theme_mod('slide_desc_' . $i);
                                            $animate_type_desc = get_theme_mod('animate_type_desc');
                                            if (!empty($slide_desc)) { ?>
                                                <ul class="list-unstyled carousel-list">
                                                    <li class="animated <?php if (!empty($animate_type_desc)) {
                                                                            esc_attr_e(get_theme_mod('animate_type_desc'), WL_COMPANION_DOMAIN);
                                                                        } else esc_attr('bounceInLeft'); ?> desc_<?php esc_attr_e($i, WL_COMPANION_DOMAIN) ?>"><?php echo wp_kses_post(get_theme_mod('slide_desc_' . $i)); ?>
                                                    </li>
                                                </ul>
                                            <?php }
                                            $slide_btn_text = get_theme_mod('slide_btn_text_' . $i);
                                            $slide_btn_link = get_theme_mod('slide_btn_link_' . $i);
                                            if (!empty($slide_btn_text)) { ?>
                                                <a class="enigma_blog_read_btn animated bounceInUp rdm_<?php esc_attr_e($i, WL_COMPANION_DOMAIN) ?>" href="<?php if (!empty($slide_btn_link)) {
                                                                                                                                                                echo esc_url(get_theme_mod('slide_btn_link_' . $i));
                                                                                                                                                            } ?>" role="button"><?php esc_html_e(get_theme_mod('slide_btn_text_' . $i), WL_COMPANION_DOMAIN); ?></a>
                                            <?php } ?>
                                        </div>
                                    <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php $j++;
                    } ?>
                </div>
                <ol class="carousel-indicators">
                    <?php for ($i = 0; $i < $j - 1; $i++) { ?>
                        <li data-target="#myCarousel" data-slide-to="<?php esc_attr_e($i, WL_COMPANION_DOMAIN); ?>" <?php if ($i == 0) {
                                                                                                                        echo esc_attr('class="active"');
                                                                                                                    } ?>></li>
                    <?php } ?>
                </ol>
                <a class="carousel-control-prev" href="#myCarousel" data-slide="prev"><span class="carousel-control-prev-icon"></span></a>
                <a class="carousel-control-next" href="#myCarousel" data-slide="next"><span class="carousel-control-next-icon"></span></a>
                <div class="enigma_slider_shadow"></div>
            </div>
<?php  }
    }
} ?>