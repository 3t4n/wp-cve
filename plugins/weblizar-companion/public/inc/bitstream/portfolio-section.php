<?php

defined('ABSPATH') or die();

class wl_companion_portfolios_bitstream {

    public static function wl_companion_portfolios_bitstream_html() {
?>

        <!--Our-Projects-start-->
        <section class="our-project-section space bg-gray" id="projects">
            <div class="container">
                <?php if (!empty(get_theme_mod('bitstream_portfolio_title')) || !empty(get_theme_mod('bitstream_portfolio_desc'))) { ?>
                    <div class="section-heading">
                        <?php if (!empty(get_theme_mod('bitstream_portfolio_title'))) { ?>
                            <h2><?php echo get_theme_mod('bitstream_portfolio_title', 'Our Projects'); ?> <span class="heading_divider"> </span> </h2>
                        <?php }
                        if (!empty(get_theme_mod('bitstream_portfolio_desc'))) { ?>
                            <p> <?php echo get_theme_mod('bitstream_portfolio_desc', 'Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor. Aenean massa. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridi culus mus.'); ?></p>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
            <?php if (!empty(get_theme_mod('bitstream_portfolio_data'))) { ?>
                <div class="owl-carousel project-slider btn-center">
                    <?php
                    $name_arr = unserialize(get_theme_mod('bitstream_portfolio_data'));
                    foreach ($name_arr as $key => $value) {
                    ?>
                        <div class="item">
                            <div class="project_item">
                                <?php if (!empty($value['portfolio_image'])) { ?>
                                    <div class="project_img">
                                        <img src="<?php echo esc_url($value['portfolio_image']); ?>" class="img-fluid" alt="<?php if (!empty($value['portfolio_name'])) {
                                                                                                                                    esc_attr_e(trim($value['portfolio_name']), WL_COMPANION_DOMAIN);
                                                                                                                                } ?>">
                                    </div>
                                <?php } ?>
                                <div class="project_cont">
                                    <?php if (!empty($value['portfolio_name'])) { ?>
                                        <h3><?php esc_attr_e($value['portfolio_name'], WL_COMPANION_DOMAIN);  ?> </h3>
                                    <?php } ?>
                                    <?php if (!empty($value['portfolio_date'])) { ?>
                                        <p> <?php esc_attr_e($value['portfolio_date'], WL_COMPANION_DOMAIN);  ?> </p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            <?php  } ?>
        </section>
        <!--Our-Projects-end-->
<?php
    }
}
?>