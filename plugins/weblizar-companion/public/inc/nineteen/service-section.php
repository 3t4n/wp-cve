<?php defined('ABSPATH') or die();

class wl_companion_services
{
    public static function wl_companion_services_html()
    {
        ?>
        <!--our-Services-->
        <div class="margin-100"> </div>
        <section class="container wl_companion">
            <div class="our-services">
                <div class="section-heading text-center animate " data-anim-type="zoomIn" data-anim-delay="600">
                    <?php if (! empty(get_theme_mod('nineteen_service_title'))) { ?>
                        <h2 class="section-title "> <span><?php echo get_theme_mod('nineteen_service_title', 'Our Service'); ?></span></h2>
                    <?php }
        if (! empty(get_theme_mod('nineteen_service_desc'))) { ?>
                        <span class="section-description "><?php echo get_theme_mod('nineteen_service_desc'); ?></span>
                    <?php } ?>
                </div>
                <div class="margin-60 "> </div>
                <div class="row">
                    <!--section-heading-->
                    <?php  if (! empty(get_theme_mod('nineteen_service_data'))) {
            $name_arr = unserialize(get_theme_mod('nineteen_service_data'));
            foreach ($name_arr as $key => $value) {
                ?>
                    <div class="our_services-col col-md-4 col-sm-6  animate" data-anim-type="fadeInRight" data-anim-delay="800">
                        <div class="service-content">
                            <h4 class="service-title">
                                <?php if (! empty($value['service_icon'])) { ?>
                                <i class="<?php esc_attr_e($value['service_icon'],WL_COMPANION_DOMAIN); ?> icon"></i>
                                <?php } ?>
                                <span><?php if (! empty($value['service_name'])) {
                     esc_html_e($value['service_name'],WL_COMPANION_DOMAIN);
                } ?></span>
                            </h4>
                            <?php if (! empty($value['service_desc'])) { ?>
                            <p> <?php esc_html_e($value['service_desc'],WL_COMPANION_DOMAIN); ?> </p>
                            <?php } ?>
                        </div>
                    </div>
                    <?php
            }
        } ?>
                </div>
            </div>
        </section>
        <!--//our-Services-->
    <?php self::assets_css();
    }

    public static function assets_css()
    {
        ?> 
            <style type="text/css">
                .wl_companion .section-description {
                    font-style: inherit;
                    font-weight: inherit;
                    font-family: OpenSansLight;
                    font-size: inherit;
                }
            </style>
        <?php
    }
}

?>