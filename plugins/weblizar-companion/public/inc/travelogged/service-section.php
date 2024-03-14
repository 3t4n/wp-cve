<?php

defined( 'ABSPATH' ) or die();

class wl_companion_services_travel {
    
    public static function wl_companion_services_travel_html() {
    ?>
        <!--Services Section-->
        <section class="services container space">
            <!--section-heading-->
            <div class="section-heading text-center">
            <?php if ( ! empty ( get_theme_mod( 'travelogged_service_title' ) ) ) { ?>
                <h2 class="section-title"><span><?php echo get_theme_mod( 'travelogged_service_title' ,'Our Service' ); ?></span></h2>
            <?php } if ( ! empty ( get_theme_mod( 'travelogged_service_desc' ) ) ) { ?>
                <p><?php echo get_theme_mod( 'travelogged_service_desc' ); ?></p>
            <?php } ?>
            </div>
            <div class="row">
            <?php  if ( ! empty ( get_theme_mod('travelogged_service_data' ) ) ) {
                    $name_arr = unserialize(get_theme_mod( 'travelogged_service_data'));
                    foreach ( $name_arr as $key => $value ) {
            ?>
                <!--column-->
                <div class="our_services-col col-md-6 col-sm-12 col-lg-4">
                    <div class="service-content">
                    <?php if ( ! empty ( $value['service_icon'] ) ) { ?>
                        <span class="icon-circle"> 
                            <i class="<?php esc_attr_e($value['service_icon'],WL_COMPANION_DOMAIN); ?>">  </i>
                        </span>
                    <?php } ?>
                        <h4 class="service-title"><span><?php if ( ! empty ( $value['service_name'] ) ) { esc_html_e( $value['service_name'],WL_COMPANION_DOMAIN ); } ?></span></h4>
                        <?php if ( ! empty ( $value['service_desc'] ) ) { ?>
                        <p><?php esc_html_e( $value['service_desc'],WL_COMPANION_DOMAIN); ?></p>
                        <?php } ?>
                    </div>
                </div>
                <!--column-->
            <?php } } ?>
            </div>
        </section>
    <?php
    }
}

?>