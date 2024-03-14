<?php

defined('ABSPATH') or die();


class wl_companion_testimonial_enigma {

    public static function wl_companion_testimonial_enigma_html() {
        $theme_name = wl_companion_helper::wl_get_theme_name();
?>
<!-- testimonial section -->
        <div class="enigma_service <?php if ($theme_name == 'Oculis') { ?>service2<?php } ?>">
            <?php
            $home_testimonial_heading = get_theme_mod('home_testimonial_heading', 'Our Testimonial');
            if (!empty($home_testimonial_heading)) { ?>
                <div class="container">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="enigma_heading_title">
                                <h3><?php echo get_theme_mod('home_testimonial_heading', 'Our Testimonial'); ?></h3>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
			<?php
            $name_arr = unserialize(get_theme_mod('enigma_testimonial_data'));
			//var_dump($name_arr);
			?>
        <section class="testimonial-box-area section-bg">
        <div class="container">
            <div class="row">
                <!-- testimonial-item -->
				
				<?php
				if (!empty($name_arr)) { ?>
				
                <?php foreach ($name_arr as $key => $value) { ?>
                <div class="col-lg-4 col-md-6 mb-30">
                    <div class="testimonial-item wow fadeInUp" data-wow-delay=".4s">
                        <div class="author-image">
                            <img src="<?php echo esc_url($value['testimonial_image']); ?>" alt="<?php if (!empty($value['testimonial_name'])) { esc_attr_e($value['testimonial_name'], WL_COMPANION_DOMAIN); } ?>">
                        </div>
                        <div class="ratting">
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                        </div>
						<?php
                        if (!empty($value['testimonial_desc'])) { ?>
                        <p> <?php esc_html_e($value['testimonial_desc'], WL_COMPANION_DOMAIN); ?>
                        </p>
						<?php } ?>
                        <?php
                        if (!empty($value['testimonial_name'])) { ?><h5 class="name"><?php esc_html_e($value['testimonial_name'], WL_COMPANION_DOMAIN); ?></h5> <?php } ?>
						<?php
                        if (!empty($value['testimonial_desig'])) { ?>
                        <span class="designation"><?php esc_html_e($value['testimonial_desig'], WL_COMPANION_DOMAIN); ?></span>
						<?php } ?>
                    </div>
                </div>
                
                <?php } } else {
				$array_data = array(
					array(
						'testimonial_name' => 'Mike Hardson',
						'testimonial_designation' => 'Client, USA',
						'testimonial_desc'    => 'Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat quid.',
						),
						array(
						'testimonial_name' => 'David Cooper',
						'testimonial_designation' => 'Client, USA',
						'testimonial_desc'    => 'Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat quid.',
						),
						array(
						'testimonial_name' => 'Kevin Martin',
						'testimonial_designation' => 'Client, USA',
						'testimonial_desc'    => 'Quis quorum aliqua sint quem legam fore sunt eram irure aliqua veniam tempor noster veniam enim culpa labore duis sunt culpa nulla illum cillum fugiat quid.',
						),
					); ?>
				<?php foreach ($array_data as $key => $value) { ?>
				<div class="col-lg-4 col-md-6 mb-30">
                    <div class="testimonial-item wow fadeInUp" data-wow-delay=".4s">
                        <div class="author-image">
                            <img src="<?php echo get_template_directory_uri(); ?>/images/team1.jpg" alt="team1">
                        </div>
                        <div class="ratting">
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                            <span><i class="fas fa-star"></i></span>
                        </div>
						<?php
                        if (!empty($value['testimonial_desc'])) { ?>
                        <p> <?php esc_html_e($value['testimonial_desc'], WL_COMPANION_DOMAIN); ?>
                        </p>
						<?php } ?>
                        <?php
                        if (!empty($value['testimonial_name'])) { ?><h5 class="name"><?php esc_html_e($value['testimonial_name'], WL_COMPANION_DOMAIN); ?></h5> <?php } ?>
						<?php
                        if (!empty($value['testimonial_desig'])) { ?>
                        <span class="designation"><?php esc_html_e($value['testimonial_desig'], WL_COMPANION_DOMAIN); ?></span>
						<?php } ?>
                    </div>
                </div>
					
				<?php 
				} } ?>
            </div>
        </div>
    </section>
<?php
} }
?>