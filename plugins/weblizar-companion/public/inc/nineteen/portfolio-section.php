<?php
defined('ABSPATH') or die();
class wl_companion_portfolios
{
    public static function wl_companion_portfolios_html()
    {
        ?>
        <!--our-Portfolios-->
        <section class="our_portfolio wl_companion">
            <div class="container">
                <div class="section-heading text-center white animate clearfix" data-anim-type="zoomIn" data-anim-delay="600">
                    <?php if (!empty(get_theme_mod('nineteen_portfolio_title'))) { ?>
                        <h2 class="section-title "> <span><?php echo get_theme_mod('nineteen_portfolio_title', 'Our Portfolio'); ?></span></h2>
                    <?php }
        if (!empty(get_theme_mod('nineteen_portfolio_desc'))) { ?>
                        <span class="section-description "><?php echo get_theme_mod('nineteen_portfolio_desc'); ?></span>
                    <?php } ?>
                </div>
                <div class="margin-60"> </div>

                <div class="swiper-container portfolio-slider animate" data-anim-type="fadeInDownLarge" data-anim-delay="800">
                    <div class="swiper-wrapper">
                        <?php
                                if (!empty(get_theme_mod('nineteen_portfolio_data'))) {
                                    $name_arr = unserialize(get_theme_mod('nineteen_portfolio_data'));
                                    foreach ($name_arr as $key => $value) {
                                        ?>
                        <div class="swiper-slide gallery_slider">
                            <?php if (!empty($value['portfolio_image'])) { ?>
                               <figure>
                                <img src="<?php echo esc_url($value['portfolio_image']); ?>" alt="<?php if (!empty($value['portfolio_name'])) {
                                            echo trim(esc_attr($value['portfolio_name']));
                                        } ?>" class="img-fluid">
                                    <figcaption>
                                            <h4><?php if (!empty($value['portfolio_name'])) {
                                         esc_html_e($value['portfolio_name'],WL_COMPANION_DOMAIN);
                                        } ?></h4>
                                            <?php if (!empty($value['port_btn_link'])) {  ?>
                                                <a href="<?php echo esc_url($value['port_btn_link']); ?>" class="btn main-btn"><?php if (!empty($value['portfolio_btn_txt'])) {
                                             esc_html_e($value['portfolio_btn_txt'],WL_COMPANION_DOMAIN);
                                        } ?></a>
                                            <?php } ?>
                                    </figcaption>
                                </figure>
                            <?php } ?>
                        </div>
                        <?php
                                    }
                                } ?>
                    </div>
                    <div class="swiper-button-prev swiper-button-white"></div>
                    <div class="swiper-button-next swiper-button-white"></div>
                </div>
            </div>
        </section>
        <!--//our-Portfolios-->
<?php
    }
}