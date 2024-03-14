<?php

defined('ABSPATH') or die();

class wl_companion_services_swiftly {

    public static function wl_companion_services_swiftly_html() {
        $theme_name = wl_companion_helper::wl_get_theme_name(); ?>   

        <section class="ftco-section" id="services-section">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-12 heading-section text-center ftco-animate mb-5">
                        <h2 class="mb-4"><?php echo get_theme_mod('home_service_heading', 'Our Service'); ?></h2>
                    </div>
                </div>

                <div class="row">

                    <?php
                    $name_arr = unserialize(get_theme_mod('enigma_service_data'));
                    if (!empty($name_arr)) { ?>

                        <?php foreach ($name_arr as $key => $value) { ?>

                            <div class="col-md-6 col-lg-4">
                                <div class="media block-6 services d-block bg-white rounded-lg shadow ftco-animate">
                                    <div class="icon d-flex align-items-center justify-content-center">
                                        <?php
                                        if (!empty($value['service_icon'])) { ?>
                                                    <a href="<?php echo esc_url($value['service_link']); ?>">
                                                        <span class="flaticon-3d-design">
                                                            <i class="<?php esc_attr_e($value['service_icon'], WL_COMPANION_DOMAIN); ?>"></i>
                                                        </span>
                                                    </a>
                                        <?php } ?>
                                        </div>
                                        <div class="media-body">
                                            <h3 class="heading mb-3">
                                                <a class="service_name" href="<?php echo esc_url($value['service_link']); ?>">
                                                    <?php esc_html_e($value['service_name'], WL_COMPANION_DOMAIN); ?>
                                                </a>
                                            </h3>
                                            <?php
                                            if (!empty($value['service_desc'])) { ?>
                                                <p><?php echo wp_kses_post($value['service_desc']); ?></p> <?php 
                                            } ?>
                                        </div>
                                </div> 
                            </div>

                        <?php } ?>
                    <?php } ?>
                </div>
                
            </div>
        </section>

<?php
    }
}
?>