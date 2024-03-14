<?php

defined('ABSPATH') or die();


class wl_companion_services_digicrew {

    public static function wl_companion_services_digicrew_html() {
?>

        <section class="ws-section-spacing service-one">
            <div class="container">
                <?php
                $home_service_heading = get_theme_mod('home_service_heading', 'Our Service');
                if (!empty($home_service_heading)) { ?>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="main-title-two ">
                                <h2><?php echo get_theme_mod('home_service_heading', 'Our Service'); ?></h2>
                            </div>
                        </div>
                    </div>
                <?php } ?>
                <?php
                $name_arr = unserialize(get_theme_mod('digicrew_service_data'));
                if (!empty($name_arr)) { ?>
                    <div class="row">
                        <?php foreach ($name_arr as $key => $value) { ?>
                            <div class="col-lg-4 col-md-6 col-sm-12">

                                <div class="service-wt-bor">
                                    <?php if (!empty($value['service_icon'])) { ?>
                                        <span class="icon-two blue"><i class="<?php esc_attr_e($value['service_icon'], WL_COMPANION_DOMAIN); ?>"></i></span>
                                    <?php } ?>
                                    <?php if (!empty($value['service_name'])) { ?>
                                        <a href="<?php echo esc_url($value['service_link']); ?>">
                                            <h3><?php esc_html_e($value['service_name'], WL_COMPANION_DOMAIN); ?> </h3>
                                        </a>
                                    <?php } ?>
                                    <?php if (!empty($value['service_desc'])) { ?>
                                        <p><?php echo wp_kses_post($value['service_desc']); ?></p>
                                    <?php } ?>
                                </div>

                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>
<?php
    }
}
?>