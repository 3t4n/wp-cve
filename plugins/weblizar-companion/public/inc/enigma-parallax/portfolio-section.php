<?php

defined( 'ABSPATH' ) or die();

class wl_companion_portfolios_enigma_parallax
{

    public static function wl_companion_portfolios_enigma_parallax_html() {
    ?>
        <!-- portfolio section -->
        <div class="clearfix"></div>
        <div  id="portfolio" class="portfolio__section"></div>
        <div class="enigma_project_section" <?php if ( ! empty ( get_theme_mod( 'upload__portfolio_image' ) ) ) { ?> style="background-image:url('<?php echo esc_url( get_theme_mod( 'upload__portfolio_image' ) ); ?>');"<?php } ?> >
            <?php if ( ! empty ( get_theme_mod( 'port_heading' ) ) )  { ?>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="enigma_heading_title">
                                <h3><?php echo get_theme_mod( 'port_heading', 'Recent Works '); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php if ( ! empty ( get_theme_mod('enigma_portfolio_data' ) ) ) {?>
                <div class="container">
                    <div class="row" >
                        <div id="enigma_portfolio_section" class="enima_photo_gallery">
                            <?php
                            $name_arr = unserialize(get_theme_mod( 'enigma_portfolio_data'));
                            foreach ( $name_arr as $key => $value ) {
                            ?>
                            <?php if ( ! empty ( $value['portfolio_image'] ) ) { ?>
                            <div class="col-lg-3 col-md-3 col-sm-6 pull-left scrollimation fade-right d1">
                                <div class="img-wrapper">
                                    <div class="enigma_home_portfolio_showcase">
                                        <img class="enigma_img_responsive" src="<?php echo esc_url($value['portfolio_image']); ?>">
                                        <div class="enigma_home_portfolio_showcase_overlay">
                                            <div class="enigma_home_portfolio_showcase_overlay_inner ">
                                                <div class="enigma_home_portfolio_showcase_icons">
                                                    <a title="<?php if ( ! empty ( $value['portfolio_name'] ) ) { echo trim( $value['portfolio_name'] ); } ?>" href="<?php  echo esc_url( $value['portfolio_link'] );  ?>">
                                                        <i class="fa fa-link"></i>
                                                    </a>
                                                    <a class="photobox_a" href="<?php echo esc_url($value['portfolio_image']); ?>">
                                                        <i class="fa fa-search-plus"></i>
                                                        <img src="<?php echo esc_url($value['portfolio_image']); ?>" alt="<?php if ( ! empty ( $value['portfolio_name'] ) ) { echo trim( $value['portfolio_name'] ); } ?>" style="display:none !important;visibility:hidden">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ( ! empty ( $value['portfolio_name'] ) ) { ?>
                                        <div class="enigma_home_portfolio_caption">
                                            <h3 class="port_<?php esc_attr_e( $i ,WL_COMPANION_DOMAIN) ?>">
                                                <a href="<?php  echo esc_url( $value['portfolio_link'] );  ?>"><?php esc_html_e( $value['portfolio_name'],WL_COMPANION_DOMAIN );  ?></a>
                                            </h3>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="enigma_portfolio_shadow"></div>
                            </div>
                            <?php  } } ?>
                        </div>
                    </div>
                </div>
            <?php  } else { ?>

                <div class="container">
                    <div class="row" >
                        <div id="enigma_portfolio_section" class="enima_photo_gallery">
                            <?php for ( $i=1 ; $i<=4; $i++ ) { ?>
                            <?php if ( ! empty ( get_theme_mod( 'port_'.$i.'_img') ) ) { ?>
                            <div class="col-lg-3 col-md-3 col-sm-6 pull-left scrollimation fade-right d1">
                                <div class="img-wrapper">
                                    <div class="enigma_home_portfolio_showcase">
                                        <img class="enigma_img_responsive" src="<?php echo esc_url( get_theme_mod( 'port_'.$i.'_img') ); ?>">
                                        <div class="enigma_home_portfolio_showcase_overlay">
                                            <div class="enigma_home_portfolio_showcase_overlay_inner ">
                                                <div class="enigma_home_portfolio_showcase_icons">
                                                    <a title="<?php esc_attr_e( get_theme_mod( 'port_'.$i.'_title', __( 'Bonorum', 'enigma-parallax' ) ),WL_COMPANION_DOMAIN ); ?>" href="<?php echo esc_url( get_theme_mod( 'port_'.$i.'_link', '#' ) ); ?>">
                                                        <i class="fa fa-link"></i>
                                                    </a>
                                                    <a class="photobox_a" href="<?php echo esc_url( get_theme_mod( 'port_'.$i.'_img' ) ); ?>">
                                                        <i class="fa fa-search-plus"></i>
                                                        <img src="<?php echo esc_url( get_theme_mod( 'port_'.$i.'_img' ) ); ?>" alt="<?php esc_attr_e( get_theme_mod( 'port_'.$i.'_title', __( 'Bonorum', 'enigma-parallax' ) ) ,WL_COMPANION_DOMAIN); ?>" style="display:none !important;visibility:hidden">
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php if ( ! empty ( get_theme_mod( 'port_'.$i.'_title', __( 'Bonorum', 'enigma-parallax' ) ) ) ) { ?>
                                        <div class="enigma_home_portfolio_caption">
                                            <h3 class="port_<?php esc_attr_e( $i,WL_COMPANION_DOMAIN) ?>">
                                                <a href="<?php echo esc_url( get_theme_mod( 'port_'.$i.'_link', '#' ) ); ?>"><?php  esc_html_e( get_theme_mod( 'port_'.$i.'_title', 'Bonorum' ),WL_COMPANION_DOMAIN ); ?></a>
                                            </h3>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div class="enigma_portfolio_shadow"></div>
                            </div><?php  } } ?>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
        <div class="clearfix"></div>
        <!-- /portfolio section -->

    <?php
    }
}
?>