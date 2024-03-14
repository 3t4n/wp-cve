<?php

defined('ABSPATH') or die();

class wl_companion_portfolios_creative {

    public static function wl_companion_portfolios_creative_html() {
?>

        <div class="bottom-pad margin-top100">
            <div class="container">
                <div class="row">
                    <div class="portfolio-content">
                        <?php if (!empty(get_theme_mod('creative_portfolio_title'))) : ?>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                                <div class="portfolio-title text-center">
                                    <h2 class="wow bounceIn creative_home_port_title"><?php echo get_theme_mod('creative_portfolio_title', 'Recent Works '); ?></h2>
                                </div>

                            </div>
                        <?php endif; ?>
                        <?php if (!empty(get_theme_mod('creative_portfolio_data'))) : ?>
                            <div class="col-md-12 col-sm-12 col-xs-12 portfolio-three-column wow bounceIn">
                                <div class="grid">
                                    <!-- Item 1 -->
                                    <?php
                                    $name_arr = unserialize(get_theme_mod('creative_portfolio_data'));
                                    foreach ($name_arr as $key => $value) {
                                    ?>
                                        <figure class="effect-zoe portfolio-border web jquery item">
                                            <a href="<?php echo esc_url($value['portfolio_image']); ?>" class="portfolio-item-link" data-rel="prettyPhoto">
                                                <img src="<?php echo esc_url($value['portfolio_image']); ?>" alt="creative_image" height="278" width="378"></a>
                                            <figcaption>
                                                <?php if (!empty($value['portfolio_name'])) { ?>

                                                    <h2><span><?php esc_attr_e($value['portfolio_name'], WL_COMPANION_DOMAIN);  ?><span><?php _e(' ', 'creative'); ?></span></h2>
                                                <?php } ?>
                                                <span><a href="<?php esc_attr_e($value['portfolio_image'], WL_COMPANION_DOMAIN); ?>" class="portfolio-item-link" data-rel="prettyPhoto"><i class="fa fa-eye"></i></a></span>
                                                <span><a href="<?php esc_attr_e($value['portfolio_link'], WL_COMPANION_DOMAIN);  ?>" class="portfolio-item-link"><i class="fa fa-paperclip"></i></a></span>
                                                <?php if (!empty($value['portfolio_desc'])) { ?>

                                                    <p><?php esc_attr_e($value['portfolio_desc'], WL_COMPANION_DOMAIN);  ?></p>
                                                <?php } ?>
                                            </figcaption>
                                        </figure>
                                    <?php } ?>
                                </div>
                                <!-- /grid -->
                            </div>
                        <?php endif; ?>
                        <div class="clearfix"> </div>
                    </div>
                </div>
            </div>
        </div>
<?php
    }
}
?>