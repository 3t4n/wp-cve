<?php

defined('ABSPATH') or die();

class wl_companion_sliders_digicrew {

    public static function wl_companion_sliders_digicrew_html() {
?>

        <!-- Main-start -->
        <main class="hero-area" style="background-image:url(<?php echo esc_url(get_theme_mod('banner_background')); ?>);">
            <div class="slide-table">
                <div class="slide-table-cell">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-8 col-sm-12">
                                <div class="slide-content">

                                    <h1><?php esc_html_e(get_theme_mod('banner_heading', ''), WL_COMPANION_DOMAIN); ?></h1>

                                    <p>
                                        <?php esc_html_e(get_theme_mod('banner_desc', ''), WL_COMPANION_DOMAIN); ?>
                                    </p>
                                    <?php
                                    $btn_txt = get_theme_mod('btn_txt', '');
                                    if (!empty($btn_txt)) { ?>
                                        <a href="<?php echo esc_url(get_theme_mod('btn_url', '')); ?>" class="btn btn-theme btn-active"><?php esc_html_e(get_theme_mod('btn_txt', ''), WL_COMPANION_DOMAIN); ?></a>
                                    <?php } ?>
                                </div>
                            </div>

                        </div>
                        <?php $banner_sidebackground = get_theme_mod('banner_sidebackground');
                        if (!empty($banner_sidebackground)) { ?>
                            <div class="right-img-box">
                                <img src="<?php echo esc_url(get_theme_mod('banner_sidebackground')); ?>" alt="slide-img">
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
            <!-- Shape -->
            <div class="shapes">
                <svg class="abstract-svg-1" viewBox="0 0 102 102">
                    <circle cx="50" cy="50" r="50"></circle>
                </svg>
                <svg class="abstract-svg-2" viewBox="0 0 102 102">
                    <circle cx="50" cy="50" r="50"></circle>
                </svg>
                <svg class="abstract-svg-3" viewBox="0 0 102 102">
                    <circle cx="50" cy="50" r="50"></circle>
                </svg>

                <svg class="abstract-svg-4" viewBox="0 0 102 102">
                    <circle cx="50" cy="50" r="50"></circle>
                </svg>
                <svg class="abstract-svg-5" viewBox="0 0 102 102">
                    <circle cx="50" cy="50" r="50"></circle>
                </svg>
                <svg class="abstract-svg-6" viewBox="0 0 102 102">
                    <circle cx="50" cy="50" r="50"></circle>
                </svg>
            </div>
        </main>
        <!-- Main-end -->
<?php  }
} ?>