
<?php defined('ABSPATH') or die();

class wl_companion_clients
{
    public static function wl_companion_clients_html()
    {
        ?>
        <!--our-clients-->
        <section class="our-clients our-clientsss clearfix wl_companion">
            <div class="container">
                <div class="section-heading text-center animate white" data-anim-type="zoomIn" data-anim-delay="600">
                    <?php if (!empty(get_theme_mod('nineteen_client_title'))) {?>
                        <h2 class="section-title "> <span><?php echo get_theme_mod('nineteen_client_title'); ?></span></h2>
                    <?php }
        if (!empty(get_theme_mod('nineteen_client_desc'))) {?>
                        <span class="section-description "><?php echo get_theme_mod('nineteen_client_desc'); ?></span>
                    <?php } ?>
                </div>
                <div class="margin-60 clearfix"> </div>

                <div class="swiper-container logo-slider animate" data-anim-type="fadeInDownLarge" data-anim-delay="800">
                    <div class="swiper-wrapper">
                        <?php
if (!empty(get_theme_mod('nineteen_client_data'))) {
            $name_arr = unserialize(get_theme_mod('nineteen_client_data'));
            foreach ($name_arr as $key => $value) {
                ?>
                                <div class="swiper-slide">
                                    <?php if (!empty($value['client_image'])) {?>
                                        <img src="<?php echo esc_url($value['client_image']); ?>" alt="<?php if (!empty($value['client_name'])) {
                    echo trim($value['client_name']);
                }?>" class="img-fluid">
                                    <?php } ?>
                                </div>
                                <?php
            }
        } ?>
                    </div>
                </div>
            </div>
        </section>
        <!--//our-clients-->
        <?php
    }
}

?>
