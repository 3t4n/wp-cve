<?php

defined( 'ABSPATH' ) or die();

class wl_companion_services_bitstream
{
    
    public static function wl_companion_services_bitstream_html() {
    ?>

        <!-- Service-section-start-->
        <section class="services space-top" id="about">
            <div class="container">
                <?php if ( ! empty ( get_theme_mod( 'bitstream_service_title' ) ) || ! empty ( get_theme_mod( 'bitstream_service_desc' ) ) ) { ?>
                    <div class="section-heading">
                        <?php if ( ! empty ( get_theme_mod( 'bitstream_service_title' ) ) ) { ?>
                            <h2>  <?php echo get_theme_mod( 'bitstream_service_title' ,'Our Service' ); ?> <span class="heading_divider"> </span> </h2>
                        <?php } if ( ! empty ( get_theme_mod( 'bitstream_service_desc' ) ) ) { ?>
                            <p> <?php echo get_theme_mod( 'bitstream_service_desc' ); ?> </p>
                        <?php } ?>
                    </div>
                <?php  } ?>
                <?php if ( ! empty ( get_theme_mod('bitstream_service_data' ) ) ) { ?>
                    <div class="row">
                        <?php  
                        $name_arr = unserialize(get_theme_mod( 'bitstream_service_data'));
                        foreach ( $name_arr as $key => $value ) {
                        ?>
                            <div class="our_services-col col-lg-3 col-md-6 col-sm-12">
                                <div class="service-content">
                                    <?php if ( ! empty ( $value['service_image'] ) ) { ?>
                                        <span class="icon">
                                            <img src="<?php echo esc_url( $value['service_image'] ); ?>" alt="<?php if ( ! empty ( $value['service_name'] ) ) {  esc_attr_e($value['service_name'],WL_COMPANION_DOMAIN); } ?>">
                                        </span>
                                    <?php }
                                    if ( ! empty ( $value['service_name'] ) ) { ?>
                                        <h3 class="service-title"><?php esc_attr_e( $value['service_name'] ,WL_COMPANION_DOMAIN); ?> </h3>
                                    <?php } ?>
                                    <?php if (!empty($value['service_desc'])) { ?>
                                        <p><?php esc_attr_e($value['service_desc'],WL_COMPANION_DOMAIN); ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>
        </section>
        <!-- Service-section-end-->
    <?php 
    }
}
?>