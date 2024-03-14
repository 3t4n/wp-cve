<?php

defined('ABSPATH') or die();

class wl_companion_services_enigma_parallax {

    public static function wl_companion_services_enigma_parallax_html() {
?>
        <!-- service section -->
        <div class="clearfix"></div>
        <div id="service" class="service__section"></div>
        <div class="enigma_service">
            <?php if (!empty(get_theme_mod('home_service_heading'))) { ?>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="enigma_heading_title">
                                <h3><?php echo get_theme_mod('home_service_heading', 'Our Services'); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php 
            $name_arr = unserialize(get_theme_mod('enigma_service_data'));
            if (!empty($name_arr)) { ?>
                <div class="container">
                    <div class="row isotope" id="isotope-service-container">
                        <?php
                        foreach ($name_arr as $key => $value) {
                        ?>
                            <div class=" col-md-4 service">
                                <div class="enigma_service_area appear-animation bounceIn appear-animation-visible">
                                    <?php if (!empty($value['service_icon'])) { ?>
                                        <a href="<?php echo esc_url($value['service_link']); ?>">
                                            <div class="enigma_service_iocn pull-left">
                                                <i class="<?php esc_attr_e($value['service_icon'], WL_COMPANION_DOMAIN); ?>"></i>
                                            </div>
                                        </a>
                                    <?php } ?>
                                    <div class="enigma_service_detail media-body">
                                        <?php if (!empty($value['service_name'])) { ?>
                                            <h3 class="head_<?php esc_attr_e($i, WL_COMPANION_DOMAIN) ?>">
                                                <a href="<?php echo esc_url($value['service_link']); ?>">
                                                    <?php esc_html_e($value['service_name'], WL_COMPANION_DOMAIN); ?>
                                                </a>
                                            </h3>
                                        <?php }
                                        if (!empty($value['service_desc'])) { ?>
                                            <p>
                                                <?php esc_html_e($value['service_desc'], WL_COMPANION_DOMAIN); ?>
                                            </p>
                                        <?php }
                                        if (!empty($value['service_link'])) { ?>
                                            <a class="ser-link" href="<?php echo esc_url($value['service_link']); ?>">
                                                <?php esc_html_e('Read More', 'enigma-parallax'); ?>
                                            </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } else { ?>

                <div class="container">
                    <div class="row isotope" id="isotope-service-container">
                        <?php for ($i = 1; $i < 4; $i++) { ?>
                            <div class=" col-md-4 service">
                                <div class="enigma_service_area appear-animation bounceIn appear-animation-visible">
                                    <?php if (!empty(get_theme_mod('service_' . $i . '_icons'))) { ?>
                                        <a href="<?php echo esc_url(get_theme_mod('service_' . $i . '_link')); ?>">
                                            <div class="enigma_service_iocn pull-left">
                                                <i class="fa <?php esc_attr_e(get_theme_mod('service_' . $i . '_icons'), WL_COMPANION_DOMAIN); ?>"></i>
                                            </div>
                                        </a>
                                    <?php } ?>
                                    <div class="enigma_service_detail media-body">
                                        <?php if (!empty(get_theme_mod('service_' . $i . '_title'))) { ?>
                                            <h3 class="head_<?php esc_attr_e($i, WL_COMPANION_DOMAIN) ?>">
                                                <a href="<?php echo esc_url(get_theme_mod('service_' . $i . '_link')); ?>">
                                                    <?php esc_html_e(get_theme_mod('service_' . $i . '_title'), WL_COMPANION_DOMAIN); ?>
                                                </a>
                                            </h3>
                                        <?php }
                                        if (!empty(get_theme_mod('service_' . $i . '_text'))) { ?>
                                            <p>
                                                <?php echo wp_kses_post(get_theme_mod('service_' . $i . '_text')); ?><?php } ?>
                                            </p>
                                            <?php if (!empty(get_theme_mod('service_' . $i . '_links'))) { ?>
                                                <a class="ser-link" href="<?php echo esc_url(get_theme_mod('service_' . $i . '_links')); ?>">
                                                    <?php esc_html_e('Read More', 'enigma-parallax'); ?>
                                                </a>
                                            <?php } ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php  } ?>
        </div>
        <div class="clearfix"></div>
        <!-- /Service section -->
<?php
    }
}
?>