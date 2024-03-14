<?php

defined( 'ABSPATH' ) or die();

class wl_companion_services_guardian
{
    
    public static function wl_companion_services_guardian_html() {
    ?>

    <div class="our-services-area">
    <div class="container">
        <div class="text-center">
            <h2><?php echo get_theme_mod( 'guardian_service_title' ,'Our Service' ); ?>
            </h2>
		</div>
		<div class="row justify-content-center">
            <!-- <div class="margin_top3"></div> -->
            <?php if ( ! empty ( get_theme_mod('guardian_service_data' ) ) ) { ?>
                <?php  
                $name_arr = unserialize(get_theme_mod( 'guardian_service_data'));
                foreach ( $name_arr as $key => $value ) {
                ?>
                    <div class="col-lg-3 col-md-3 pb-30">
						<div class="serviceBox">
							<div class="service-content">
								<div class="service-icon">
								<?php  if ( ! empty ( $value['service_icon'] ) ) { ?>
									<span><i class="<?php esc_attr_e($value['service_icon'],WL_COMPANION_DOMAIN); ?>"></i></span>
								<?php } ?>
								</div>
								<?php  if ( ! empty ( $value['service_name'] ) ) { ?>
								<h3 class="title"><?php esc_html_e($value['service_name'],WL_COMPANION_DOMAIN); ?></h3>
								<?php } ?>
								<?php 
                        if ( ! empty ( $value['service_desc'] ) ) { ?>
                        <p class="description"><?php echo wp_kses_post($value['service_desc']); ?></p>
						<?php }  ?>
                        <a href="<?php echo esc_url($value['service_link']); ?>" class="lfour pb-30"><?php esc_html_e('Read More','guardian'); ?></a>
					</div>
                </div>
            </div>
			<?php } } else { 
                $service_data = serialize( array(
            /*Repeater's first item*/
            array(
				'service_name' => 'Lorem ipsum',
				'service_link'      => '#',
				'service_icon'       => 'fa fa-users' ,
				'service_desc'    => 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.',
				),
            /*Repeater's second item*/
            array(
				'service_name' => 'Lorem ipsum',
				'service_link'      => '#',
				'service_icon'       => 'fa fa-users' ,
				'service_desc'    => 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.',
				),
            /*Repeater's third item*/
            array(
				'service_name' => 'Lorem ipsum',
				'service_link'      => '#',
				'service_icon'       => 'fa fa-users' ,
				'service_desc'    => 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.',
				),
			array(
				'service_name' => 'Lorem ipsum',
				'service_link'      => '#',
				'service_icon'       => 'fa fa-users' ,
				'service_desc'    => 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.',
				),
            ) );
			$name_arr = unserialize($service_data);
                foreach ( $name_arr as $key => $value ) {
                ?>
                    <div class="col-lg-3 col-md-3 pb-30">
						<div class="serviceBox">
							<div class="service-content">
								<div class="service-icon">
								<?php  if ( ! empty ( $value['service_icon'] ) ) { ?>
									<span><i class="<?php esc_attr_e($value['service_icon'],WL_COMPANION_DOMAIN); ?>"></i></span>
								<?php } ?>
								</div>
								<?php  if ( ! empty ( $value['service_name'] ) ) { ?>
								<h3 class="title"><?php esc_html_e($value['service_name'],WL_COMPANION_DOMAIN); ?></h3>
								<?php } ?>
								<?php 
                        if ( ! empty ( $value['service_desc'] ) ) { ?>
                        <p class="description"><?php echo wp_kses_post($value['service_desc']); ?></p>
						<?php }  ?>
                        <a href="<?php echo esc_url($value['service_link']); ?>" class="lfour pb-30"><?php esc_html_e('Read More','guardian'); ?></a>
					</div>
                </div>
            </div>  
                <?php } ?>
			<?php } ?>
        </div>
    </div><!-- end of service section1 -->
	</div>
    <div class="clearfix"></div>
        
    <?php 
    }
}
?>