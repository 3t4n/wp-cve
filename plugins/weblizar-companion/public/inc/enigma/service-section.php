<?php

defined('ABSPATH') or die();


class wl_companion_services_enigma {

    public static function wl_companion_services_enigma_html() {
        $theme_name = wl_companion_helper::wl_get_theme_name();
?>
        <!-- service section -->
        <div class="enigma_service <?php if ($theme_name == 'Oculis') { ?>service2<?php } ?>">
            <?php
            $home_service_heading = get_theme_mod('home_service_heading', 'Our Service');
            if (!empty($home_service_heading)) { ?>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="enigma_heading_title">
                                <h3><?php echo get_theme_mod('home_service_heading', 'Our Service'); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
            <?php
            $name_arr = unserialize(get_theme_mod('enigma_service_data'));
			//var_dump($name_arr);
			?>
			
			<?php
            if (!empty($name_arr)) { ?>
                <div class="container">
                    <div class="row isotope" id="isotope-service-container">
                        <?php foreach ($name_arr as $key => $value) { ?>
                            <div class=" col-md-4 service">
                                <div class="enigma_service_area appear-animation bounceIn appear-animation-visible">
                                    <?php

                                    if (!empty($value['service_icon'])) { ?>
                                        <a href="<?php echo esc_url($value['service_link']); ?>">
                                            <div class="enigma_service_iocn pull-left">
                                                <i class="<?php esc_attr_e($value['service_icon'], WL_COMPANION_DOMAIN); ?>"></i>
                                            </div>
                                        </a>
                                    <?php } ?>
                                    <div class="enigma_service_detail media-body">
                                        <?php
                                        if (!empty($value['service_name'])) { ?>
                                            <h3 class="head">
                                                <a href="<?php echo esc_url($value['service_link']); ?>">
                                                    <?php esc_html_e($value['service_name'], WL_COMPANION_DOMAIN); ?>
                                                </a>
                                            </h3>
                                        <?php }

                                        if (!empty($value['service_desc'])) { ?>
                                            <p><?php echo wp_kses_post($value['service_desc']); ?></p>
                                        <?php }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php } else { ?>
			<?php 
			$array_data = array(
			array(
				'service_name' => 'Lorem ipsum',
				'service_link'      => '#',
				'service_icon'       => 'fa fa-headphones',
				'service_desc'    => 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.',
				),
				array(
				'service_name' => 'Lorem ipsum',
				'service_link'      => '#',
				'service_icon'       => 'fa fa-mobile' ,
				'service_desc'    => 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.',
				),
				array(
				'service_name' => 'Lorem ipsum',
				'service_link'      => '#',
				'service_icon'       => 'fa fa-users' ,
				'service_desc'    => 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.',
				),
			);
			
			$serializeArray = serialize($array_data);
			$name_arr = unserialize($serializeArray);
		 ?>
			
			<div class="container">
                    <div class="row isotope" id="isotope-service-container">
                        <?php foreach ($name_arr as $key => $value) { ?>
                            <div class=" col-md-4 service">
                                <div class="enigma_service_area appear-animation bounceIn appear-animation-visible">
                                    <?php

                                    if (!empty($value['service_icon'])) { ?>
                                        <a href="<?php echo esc_url($value['service_link']); ?>">
                                            <div class="enigma_service_iocn pull-left">
                                                <i class="<?php esc_attr_e($value['service_icon'], WL_COMPANION_DOMAIN); ?>"></i>
                                            </div>
                                        </a>
                                    <?php } ?>
                                    <div class="enigma_service_detail media-body">
                                        <?php
                                        if (!empty($value['service_name'])) { ?>
                                            <h3 class="head">
                                                <a href="<?php echo esc_url($value['service_link']); ?>">
                                                    <?php esc_html_e($value['service_name'], WL_COMPANION_DOMAIN); ?>
                                                </a>
                                            </h3>
                                        <?php }

                                        if (!empty($value['service_desc'])) { ?>
                                            <p><?php echo wp_kses_post($value['service_desc']); ?></p>
                                        <?php }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            <?php  } ?>
        </div>
        <!-- /Service section -->
<?php
    }
}
?>