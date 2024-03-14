<?php

defined( 'ABSPATH' ) or die();

class wl_companion_services_explora
{
    
    public static function wl_companion_services_explora_html() {
    ?>
        <div class="container-fluid w_services space">
            <?php if ( ! empty ( get_theme_mod( 'explora_service_title' ) ) ) { ?>
                <div class="row wc_heading">
                    <h1 class="section_heading explora_services_title"><?php echo get_theme_mod( 'explora_service_title' ,'Our Service' ); ?></h1>
                </div>
            <?php } ?>
            <!-- /Services -->
            <?php if ( ! empty ( get_theme_mod('explora_service_data' ) ) ) { ?>
                <div class="container">
                    <div class="explora_service_1">
                        <?php  
                        $name_arr = unserialize(get_theme_mod( 'explora_service_data'));
                        foreach ( $name_arr as $key => $value ) {
                        ?>
                            <div class="col-md-4 col-sm-6 w_right_abt">
                                <div class="col-md-12 w_right_abt_text">
                                    <i class="<?php esc_attr_e($value['service_icon'],WL_COMPANION_DOMAIN); ?>"></i>
                                    <h2><a href="<?php echo esc_url($value['service_link']); ?>"><?php esc_html_e($value['service_name'],WL_COMPANION_DOMAIN); ?></a></h2>
                                    <p><?php echo wp_kses_post($value['service_desc']); ?></p>
                                    <a href="<?php echo esc_url($value['service_link']); ?>" class="btn"><?php esc_html_e('Read More','explora'); ?></a>
                                </div>
                            </div>  
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php
    }
}
?>