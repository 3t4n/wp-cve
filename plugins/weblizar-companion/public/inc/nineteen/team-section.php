<?php

defined( 'ABSPATH' ) or die();

class wl_companion_teams
{
    
    public static function wl_companion_teams_html() {
    ?>
        <!--our-Team-->
        <section class="our-clients our-team clearfix wl_companion">
            <div class="container">
                <div class="section-heading text-center animate white" data-anim-type="zoomIn" data-anim-delay="600">
                    <?php if ( ! empty ( get_theme_mod( 'nineteen_team_title' ) ) ) { ?>
                        <h2 class="section-title "> <span><?php echo get_theme_mod( 'nineteen_team_title', 'Meet Our Team' ); ?></span></h2>
                    <?php } if ( ! empty ( get_theme_mod( 'nineteen_team_desc' ) ) ) { ?>
                        <span class="section-description "><?php echo get_theme_mod( 'nineteen_team_desc' ); ?></span>
                    <?php } ?>
                </div>
                <div class="margin-60 clearfix"> </div>

                <?php if ( ! empty ( get_theme_mod('nineteen_team_data' ) ) ) { ?>
                <div class="swiper-container swiper-our-team  animate" data-anim-type="fadeInDownLarge" data-anim-delay="800">
                    <div class="swiper-wrapper">
                        <?php  $name_arr = unserialize(get_theme_mod( 'nineteen_team_data'));
                                foreach ( $name_arr as $key => $value ) {
                        ?>
                        <div class="swiper-slide">
                            <div class="swiper-slide-inner">
                                <?php if ( ! empty ( $value['team_image'] ) ) { ?>
                                    <img src="<?php echo esc_url( $value['team_image'] ); ?>" alt="<?php if ( ! empty ( $value['team_name'] ) ) { esc_attr_e( $value['team_name'],WL_COMPANION_DOMAIN ); } ?>" class="img-fluid">
                                <?php } ?>
                                <div class="team_mmbr_overlay">
                                    <div class="team_mmbr_overlay-inner">
                                        <h2><?php if ( ! empty ( $value['team_name'] ) ) { esc_html_e( $value['team_name'],WL_COMPANION_DOMAIN ); } ?></h2>
                                        <h5><?php if ( ! empty ( $value['team_designation'] ) ) { esc_html_e( $value['team_designation'] ,WL_COMPANION_DOMAIN); } ?></h5>
                                        <p class="py-5"><?php if ( ! empty ( $value['team_desc'] ) ) { esc_html_e( $value['team_desc'] ,WL_COMPANION_DOMAIN); } ?></p>
                                        <?php if ( ! empty ( $value['team_link'] ) ) { ?>
                                        <a class="btn main-btn" href="<?php echo esc_url($value['team_link']); ?>">
                                            <?php if ( ! empty ( $value['team_text'] ) ) { esc_html_e($value['team_text'],WL_COMPANION_DOMAIN); } ?>  
                                        </a>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <!-- Add Pagination -->
                    <!-- If we need navigation buttons -->
                    <div class="swiper-button-prev swiper-button-white"></div>
                    <div class="swiper-button-next swiper-button-white"></div>
                </div>
                <?php } ?>
            </div>
        </section>
        <!--//our-Team-->
    <?php 
    }
}

?>