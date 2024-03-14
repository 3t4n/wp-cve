<?php

defined('ABSPATH') or die();

class wl_companion_services_creative {

    public static function wl_companion_services_creative_html() {
?>
        <?php if (!empty(get_theme_mod('creative_service_title'))) { ?>
            <div class="row">
                <div class="col-lg-12 col-md-12 text-center service">
                    <h2 class="wow bounceIn creative_ser_title"><?php echo get_theme_mod('creative_service_title', 'Our Service'); ?></h2>
                </div>
            </div>
        <?php } ?>
        <!-- /Services -->
        <?php if (!empty(get_theme_mod('creative_service_data'))) { ?>
            <!-- Main Content -->
            <div class="main-content">
                <div class="container">
                    <div class="row">
                        <?php
                        $name_arr = unserialize(get_theme_mod('creative_service_data'));
                        foreach ($name_arr as $key => $value) :
                        ?>
                            <div class="col-lg-4 col-md-4 col-sm-4 wow swing">
                                <div class="content-box big ch-item bottom-pad-small">
                                    <?php if (!empty($value['service_icon'])) : ?>
                                        <div class="ch-info-wrap">
                                            <div class="ch-info">
                                                <div class="ch-info-front ch-img-1"><i class="<?php esc_attr_e($value['service_icon'], WL_COMPANION_DOMAIN); ?>"></i></div>
                                                <div class="ch-info-back">
                                                    <i class="<?php esc_attr_e($value['service_icon'], WL_COMPANION_DOMAIN); ?>"></i>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="content-box-info">

                                        <h3><?php esc_attr_e($value['service_name'], WL_COMPANION_DOMAIN); ?></h3>

                                        <p>
                                            <?php esc_attr_e($value['service_desc'], WL_COMPANION_DOMAIN); ?>
                                        </p>
                                        <a href="<?php esc_attr_e($value['service_link'], WL_COMPANION_DOMAIN); ?>"><?php _e('Read More', WL_COMPANION_DOMAIN); ?> <i class="fa fa-angle-right"></i><i class="fa fa-angle-right"></i></a>
                                    </div>
                                    <div class="border-bottom margin-top30">
                                    </div>
                                    <div class="border-bottom">
                                    </div>
                                </div>
                            </div><?php
                                endforeach; ?>
                    </div>
                </div>
            </div>
<?php }
    }
}
?>