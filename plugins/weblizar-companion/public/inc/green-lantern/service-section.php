<?php

defined( 'ABSPATH' ) or die();


class wl_companion_services_green_lantern
{
    
    public static function wl_companion_services_green_lantern_html() {
    ?>  
        <?php if ( ! empty ( get_theme_mod('green_lantern_service_data' ) ) ) { ?>
        <div class="section-content top-body section-services">    
            <div class="container">
                <div class="row">
                    <?php  
                    $name_arr = unserialize(get_theme_mod( 'green_lantern_service_data'));
                    foreach ( $name_arr as $key => $value ) {
                    ?>
                        <div class="col-md-3 col-sm-3">
                            <div data-animdelay="0.2s" data-animspeed="1s" data-animrepeat="0" data-animtype="fadeIn" class="content-box animated  fadeIn animatedVisi" style="-webkit-animation: 1s 0.2s;">
                                
                                <?php  if ( ! empty ( $value['service_icon'] ) ) { ?>
                                    <i class="<?php esc_attr_e($value['service_icon'],WL_COMPANION_DOMAIN); ?> weblizar_service_1_icons"></i>
                                <?php } ?>
                                <?php if(! empty ( $value['service_name'] ) ) { ?>
                                    <h4 class="h4-body-title weblizar_service_1_title"><?php esc_html_e($value['service_name'],WL_COMPANION_DOMAIN); ?> </h4>
                                <?php } ?>
                                <?php if ( ! empty ( $value['service_desc'] ) ) { ?>
                                    <div class="content-box-text service_1_text">
                                       <?php echo wp_kses_post($value['service_desc']); ?>
                                    </div>
                                <?php } ?>
                            </div>    
                        </div>
                    <?php } ?>
                </div>
            </div> 
        </div>
    <?php } ?>
       
    <?php 
    }
}
?>