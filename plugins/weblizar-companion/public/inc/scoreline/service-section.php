<?php

defined( 'ABSPATH' ) or die();

class wl_companion_services_scoreline
{
    
    public static function wl_companion_services_scoreline_html() {
    ?>
        <!-- our services -->
        <div class="container-fluid scoreline-services space">
            <div class="container">
                <?php if ( ! empty ( get_theme_mod( 'scoreline_service_title' ) ) ) { ?>
                    <h1 class="scoreline_service_title"><?php echo get_theme_mod( 'scoreline_service_title' ,'Our Service' ); ?></h1>
                <?php } ?>
                <?php if ( ! empty ( get_theme_mod('scoreline_service_data' ) ) ) { ?>        
                    <div class="col-md-12 scoreline-services-post ">
                        <div class="scoreline_home_service">
                            <?php  
                            $name_arr = unserialize(get_theme_mod( 'scoreline_service_data'));
                            foreach ( $name_arr as $key => $value ) {
                            ?>
                                <div class="col-md-4 col-sm-6 scoreline-services-text">
                                    
                                    <div class="ser_img">
                                        <img src="<?php echo esc_url($value['service_image']); ?>" class="img-responsive">
                                    </div>
                                            
                                    <div class="col-md-12 tital">
                                        <h3><a href="<?php echo esc_url($value['service_link']); ?>"><?php esc_html_e($value['service_name'],WL_COMPANION_DOMAIN); ?></a></h3><p><?php esc_html_e($value['service_desc'],WL_COMPANION_DOMAIN); ?></p>
                                        <a href="<?php echo esc_url($value['service_link']); ?>" class="ser_btn"><?php esc_html_e('Continue',WL_COMPANION_DOMAIN); ?></a>
                                    </div>
                                </div>
                            <?php  } ?>
                        </div>
                    </div>
                <?php } ?>  
            </div>
        </div>
        <!-- our service-End-->

    <?php }
} ?>