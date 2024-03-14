<?php

defined('ABSPATH') or die();

class wl_companion_services_wl {

    public static function wl_companion_services_wl_html() {
?>
        <!-- service section -->
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-sm-12">
                    <div class="center-title">
                        <?php if (!empty(get_theme_mod('weblizar_service_title'))) { ?>
                            <div class="heading-title">
                                <h2 class="h2-section-title weblizar_site_intro_title"><?php echo get_theme_mod('weblizar_service_title', 'Our Services'); ?></h2>
                            </div>
                        <?php  } ?>
                        <?php
                        $site_intro_text = get_theme_mod('site_intro_text', 'Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin professor at Hampden-Sydney College in Virginia, looked up one of the more obscure Latin words, consectetur.');
                        if (!empty($site_intro_text)) { ?>
                            <p class="weblizar_site_intro_text"><?php esc_html_e($site_intro_text, WL_COMPANION_DOMAIN); ?></p>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
        <?php if (!empty(get_theme_mod('weblizar_service_data'))) { ?>
            <div class="space-sep60"></div>
            <div class="container">
                <div class="row">
                    <?php
                    $name_arr = unserialize(get_theme_mod('weblizar_service_data'));
                    foreach ($name_arr as $key => $value) {
                    ?>
                        <div class="col-md-3 col-sm-6">
                            <div class="content-box content-style2 anim-opacity animated fadeIn animatedVisi" data-animtype="fadeIn" data-animrepeat="0" data-animspeed="1s" data-animdelay="0.2s" style="-webkit-animation: 1s 0.2s;">

                                <h4 class="h4-body-title weblizar_service_1_title">
                                    <?php if (!empty($value['service_icon'])) { ?>
                                        <i class="<?php esc_attr_e($value['service_icon'], WL_COMPANION_DOMAIN); ?> weblizar_service_1_icons"></i>
                                    <?php } ?>
                                    <?php esc_html_e($value['service_name'], WL_COMPANION_DOMAIN); ?>
                                </h4>

                                <div class="content-box-text weblizar_service_1_text">
                                    <?php
                                    if (!empty($value['service_desc'])) { ?>
                                        <?php esc_html_e($value['service_desc'], WL_COMPANION_DOMAIN); ?>
                                    <?php }
                                    if (!empty($value['service_link'])) { ?>
                                        <div>
                                            <a href="<?php echo esc_url($value['service_link']); ?>" class="read-more "><span><?php esc_html_e('read more', WL_COMPANION_DOMAIN); ?></span></a>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    <?php } ?>
                </div>
            </div>
            <div class="space-sep60"></div>
        <?php } ?>
<?php
    }
}
?>