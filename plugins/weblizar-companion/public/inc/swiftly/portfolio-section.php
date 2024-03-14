<?php

defined('ABSPATH') or die();

class wl_companion_portfolios_swiftly {

    public static function wl_companion_portfolios_swiftly_html() {
        $theme_name = wl_companion_helper::wl_get_theme_name(); ?>

        <section class="ftco-section ftco-project" id="projects-section">
            <div class="container-fluid px-md-4">
                <div class="row justify-content-center pb-5">
                    <div class="col-md-12 heading-section text-center ftco-animate">
                        <h2 class="mb-4"><?php echo get_theme_mod('port_heading', 'Our Projects'); ?></h2>
                    </div>
                </div>
                <div class="row">
                    <?php
                    $name_arr = unserialize( get_theme_mod('enigma_portfolio_data') );
                    if (!empty($name_arr)) { ?>
                        <?php
                        $name_arr = unserialize( get_theme_mod('enigma_portfolio_data') );
                        foreach ($name_arr as $key => $value) { ?>
                            <div class="col-md-3">
                                <div class="project img shadow ftco-animate d-flex justify-content-center align-items-center" style="background-image: url( <?php echo esc_url($value['portfolio_image']); ?> );">
                                    <div class="overlay"></div>
                                        <div class="text text-center p-4">
                                            <h3>
                                                <a href="<?php echo esc_url($value['portfolio_link']); ?>"><?php esc_html_e($value['portfolio_name'], WL_COMPANION_DOMAIN);  ?></a>
                                            </h3>
                                        </div>
                                </div>
                            </div>       
                        <?php
                        }          
                    } ?>
                </div>
            </div>
        </section><?php

    }
}
?>