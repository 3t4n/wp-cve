<?php

defined( 'ABSPATH' ) or die();

class wl_companion_destination_travel {
    
    public static function wl_companion_destination_travel_html() {
    ?>
    <!--Booking Section -->
    <section class="property_booking space">
        <div class="container">
            <!--section-heading-->
            <div class="section-heading  text-center">
            <?php if ( ! empty ( get_theme_mod( 'travelogged_destination_title' ) ) ) { ?>
                <h2 class="section-title"><span> <?php echo get_theme_mod( 'travelogged_destination_title' ,'Proper Destination' ); ?></span></h2>
            <?php } if ( ! empty ( get_theme_mod( 'travelogged_destination_desc' ) ) ) { ?>
                <p class="text-white"><?php echo get_theme_mod( 'travelogged_destination_desc' ); ?></p>
            <?php } ?>
            </div>
            <!--owl carousel slider-->
            <div class="owl-carousel owl-carousel_3 owl-theme owl-nav_mainclr owl_btn2 wow animated fadeInDown" data-wow-duration="1s" data-wow-offset="150">
            <?php  if ( ! empty ( get_theme_mod('travelogged_destination_data' ) ) ) {
                    $name_arr = unserialize(get_theme_mod( 'travelogged_destination_data'));
                    foreach ( $name_arr as $key => $value ) {
            ?>
                <!--item slider-->
                <div class="item">
                    <figure>
                        <div class="property_img">
                        <?php if ( ! empty ( $value['desti_image'] ) ) { ?>
                            <img src="<?php esc_attr_e( esc_url( $value['desti_image'] ),WL_COMPANION_DOMAIN ); ?>" class="img-fluid" alt="<?php  esc_attr_e( $value['desti_name'],WL_COMPANION_DOMAIN ); ?>"/>
                        <?php } if ( ! empty ( $value['desti_duration'] ) ) { ?>
                            <h6 class="spof_days"><i class="far fa-clock icon"></i><?php esc_html_e( $value['desti_duration'],WL_COMPANION_DOMAIN ); ?> </h6>
                        <?php } ?>
                        </div>
                        <figcaption class="property_cont">
                        <?php if ( ! empty ( $value['desti_name'] ) ) { ?>
                            <h4><span> <?php esc_html_e( $value['desti_name'],WL_COMPANION_DOMAIN ); ?> </span></h4>
                        <?php } if ( ! empty ( $value['desti_rating'] ) ) { ?>
                            <span class="spof_ratting">
                            <?php if ( $value['desti_rating'] == 1 ) { ?>
                            <i class="fas fa-star icon"></i>
                            <?php } elseif ($value['desti_rating'] == 2 ) { ?>
                            <i class="fas fa-star icon"></i>
                            <i class="fas fa-star icon"></i>
                            <?php } elseif ($value['desti_rating'] == 3 ) { ?>
                            <i class="fas fa-star icon"></i>
                            <i class="fas fa-star icon"></i>
                            <i class="fas fa-star icon"></i>
                            <?php } elseif ($value['desti_rating'] == 4 ) { ?>
                            <i class="fas fa-star icon"></i>
                            <i class="fas fa-star icon"></i>
                            <i class="fas fa-star icon"></i>
                            <i class="fas fa-star icon"></i>
                            <?php } elseif ($value['desti_rating'] == 5 ) { ?>
                            <i class="fas fa-star icon"></i>
                            <i class="fas fa-star icon"></i>
                            <i class="fas fa-star icon"></i>
                            <i class="fas fa-star icon"></i>
                            <i class="fas fa-star icon"></i>
                            <?php } ?>
                            </span>
                        <?php } if ( ! empty ( $value['desti_desc'] ) ) { ?>
                            <p><?php esc_html_e( $value['desti_desc'],WL_COMPANION_DOMAIN ); ?></p>
                            <?php } ?>
                            <?php if ( ! empty ( $value['desti_link'] ) ) { ?>
                            <a href="<?php echo esc_url( $value['desti_link'] ); ?>" class="btn mb-2"> <?php esc_html_e( $value['desti_text'],WL_COMPANION_DOMAIN ); ?></a>
                            <?php } ?>
                        </figcaption>
                    </figure>
                </div>
                <!--item slider-->
            <?php } } ?>
            </div>
        </div>
    </section>
    <?php
    }
}
?>