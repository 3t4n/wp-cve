<?php

defined('ABSPATH') or die();

class wl_companion_sliders_creative {

    public static function wl_companion_sliders_creative_html() {
?>

        <?php if (!empty(get_theme_mod('creative_slider_data'))) { ?>

            <div id="slider" class="sl-slider-wrapper tp-banner-container">
                <div class="sl-slider fullwidthbanner rslider tp-banner">
                    <?php
                    $dot = 0;
                    $j = 1;
                    if ($dot % 2 == 0) {
                        $orientation = 'horizontal';
                    } else {
                        $orientation = 'vertical';
                    }
                    $name_arr = unserialize(get_theme_mod('creative_slider_data'));
                    foreach ($name_arr as $key => $value) {
                    ?>
                        <div class="sl-slide" data-orientation="<?php esc_attr_e($orientation, WL_COMPANION_DOMAIN); ?>" data-slice1-rotation="-25" data-slice2-rotation="-25" data-slice1-scale="2" data-slice2-scale="2">
                            <div class="sl-slide-inner">
                                <img src="<?php echo esc_url($value['slider_image']); ?>" class="img-responsive bg-img" alt="<?php if (!empty($value['slider_name'])) { esc_attr_e($value['slider_name'], WL_COMPANION_DOMAIN); } ?>">
                                <h2><?php esc_html_e($value['slider_name'], WL_COMPANION_DOMAIN); ?></h2>
                                <blockquote>
                                    <p><?php echo wp_kses_post($value['slider_desc']); ?></p>
                                    <footer class="post-footer"><a href="<?php echo esc_url($value['slider_link']);  ?>" class="btn btn-color">
                                            <?php if (!empty($value['slider_text'])) {
                                                esc_html_e($value['slider_text'], WL_COMPANION_DOMAIN);
                                            } ?></a></footer>
                                </blockquote>
                            </div>
                        </div>
                    <?php
                        $j++;
                    } ?>

                </div><!-- /sl-slider -->
                <nav id="nav-dots" class="nav-dots">
                    <?php
                    for ($i = 1; $i <= $j - 1; $i++) { ?>
                        <span <?php echo esc_attr($i == 1 ? 'class="nav-dot-current"' : ""); ?>></span>
                    <?php } ?>
                </nav>
            </div><!-- /slider-wrapper -->
<?php }
    }
} ?>