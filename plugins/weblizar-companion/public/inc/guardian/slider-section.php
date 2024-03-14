<?php

defined( 'ABSPATH' ) or die();

class wl_companion_sliders_guardian
{

    public static function wl_companion_sliders_guardian_html() {
    ?>
        <?php $slider_select = absint(get_theme_mod( 'slider_choise', '1' ));
		if ( $slider_select == '1' ) {   ?>
			<?php if ( ! empty ( get_theme_mod('guardian_slider_data' ) ) ) { 
			?>
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <?php
                        $j = 1;
                        $name_arr = unserialize(get_theme_mod( 'guardian_slider_data'));
                        foreach ( $name_arr as $key => $value ) {
                            if ( ! empty ( $value['slider_image']) ) { ?>
                                <div class="item <?php if ($j == 1) echo esc_attr("active"); ?>">
                                    <img src="<?php echo esc_url( $value['slider_image'] ); ?>" class="img-responsive" alt="<?php if ( ! empty ( $value['slider_name'] ) ) { esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN); } ?>">
                                    <div class="container">
                                        <div class="carousel-caption">
                                            <?php if ( ! empty ( $value['slider_name'] ) ) { ?>
                                                <h2 class="guardian_slide_title"><strong>
                                                    <?php esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN); ?></strong>
                                                </h2>
                                            <?php } ?>
                                            <?php if ( ! empty ( $value['slider_desc'] ) ) { ?>
                                                <p class="guardian_slide_desc">
                                                    <?php echo wp_kses_post($value['slider_desc']); ?>
                                                </p>
                                            <?php } ?>
                                            <?php if ( ! empty ( $value['slider_link'] ) ) { ?>
                                                <a class="btn btn-lg btn-primary" target="_blank" href="<?php echo esc_url($value['slider_link']);  ?>" role="button"><?php if ( ! empty ( $value['slider_text'] ) ) {  esc_html_e($value['slider_text'],WL_COMPANION_DOMAIN); } ?>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php $j++;
                            }
                        } ?>

                    </div>
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev"><i class="fa fa-angle-left"></i></a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next"><i class="fa fa-angle-right"></i></a>
                </div><!-- /.carousel -->
            <?php } else { ?> 
			
			<div id="myCarousel" class="carousel slide" data-ride="carousel">
                    <!-- Indicators -->
                    <ol class="carousel-indicators">
                        <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                        <li data-target="#myCarousel" data-slide-to="1"></li>
                        <li data-target="#myCarousel" data-slide-to="2"></li>
                    </ol>
                    <div class="carousel-inner">
                        <?php
                        $j = 1;
						$slider_data = serialize( array(
            /*Repeater's first item*/
            array(
				'slider_name' => 'Welcome to Guardian Theme',
				'slider_desc'      => 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.',
				'slider_image'       => get_template_directory_uri().'/images/slider.jpg' ,
				'slider_text'    => 'View More',
				'slider_link' => '#',
				),
			array(
				'slider_name' => 'Welcome to Guardian Theme',
				'slider_desc'      => 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.',
				'slider_image'       => get_template_directory_uri().'/images/slider.jpg' ,
				'slider_text'    => 'View More',
				'slider_link' => '#',
				),
			));
			
                        $name_arr = unserialize($slider_data);
                        foreach ( $name_arr as $key => $value ) {
                            if ( ! empty ( $value['slider_image']) ) { ?>
                                <div class="item <?php if ($j == 1) echo esc_attr("active"); ?>">
                                    <img src="<?php echo esc_url( $value['slider_image'] ); ?>" class="img-responsive" alt="<?php if ( ! empty ( $value['slider_name'] ) ) { esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN); } ?>">
                                    <div class="container">
                                        <div class="carousel-caption">
                                            <?php if ( ! empty ( $value['slider_name'] ) ) { ?>
                                                <h2 class="guardian_slide_title"><strong>
                                                    <?php esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN); ?></strong>
                                                </h2>
                                            <?php } ?>
                                            <?php if ( ! empty ( $value['slider_desc'] ) ) { ?>
                                                <p class="guardian_slide_desc">
                                                    <?php echo wp_kses_post($value['slider_desc']); ?>
                                                </p>
                                            <?php } ?>
                                            <?php if ( ! empty ( $value['slider_link'] ) ) { ?>
                                                <a class="btn btn-lg btn-primary" target="_blank" href="<?php echo esc_url($value['slider_link']);  ?>" role="button"><?php if ( ! empty ( $value['slider_text'] ) ) {  esc_html_e($value['slider_text'],WL_COMPANION_DOMAIN); } ?>
                                                </a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            <?php $j++;
                            }
                        } ?>

                    </div>
                    <a class="left carousel-control" href="#myCarousel" data-slide="prev"><i class="fa fa-angle-left"></i></a>
                    <a class="right carousel-control" href="#myCarousel" data-slide="next"><i class="fa fa-angle-right"></i></a>
                </div><!-- /.carousel -->
			
			<?php }
        } elseif ( get_theme_mod( 'slider_choise', '1' ) == '2' ) { ?>
            <?php if ( ! empty ( get_theme_mod('guardian_slider_data' ) ) ) { ?>
                <div class="guardian_options_slider">
                    <div class="swiper-container guardian_slider">
                        <div class="swiper-wrapper ">
                            <?php
                            $name_arr = unserialize(get_theme_mod( 'guardian_slider_data'));
                            foreach ( $name_arr as $key => $value ) {
                                if ( ! empty ( $value['slider_image']) ) { ?>
                                    <div class="swiper-slide">
                                        <img src="<?php echo esc_url( $value['slider_image'] ); ?>" alt="<?php  the_title(); ?>" class="home_slider img-responsive" />
                                        <div class="overlay"></div>
                                        <div class="carousel-caption">
                                            <?php if ( ! empty ( $value['slider_name'] ) ) {  ?>
                                                <h2 class="guardian_slide_title animation animated-item-1"><strong><?php esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN); ?></strong></h2>
                                            <?php } ?>
                                            <?php if ( ! empty ( $value['slider_desc'] ) ) {  ?>
                                                <p class="guardian_slide_desc animation animated-item-2"><?php echo wp_kses_post($value['slider_desc']); ?></p>
                                            <?php } if ( ! empty ( $value['slider_link'] ) )  { ?>
                                                <a class="btn btn-lg btn-primary animation animated-item-3" target="_blank" href="<?php echo esc_url($value['slider_link']);  ?>" role="button"><?php if ( ! empty ( $value['slider_text'] ) ) {  esc_html_e($value['slider_text']); } ?></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php }
                            } ?>

                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-prev swiper-button-prev5"><i class="fa fa-arrow-circle-o-left"></i></div>
                        <div class="swiper-button-next swiper-button-next5"><i class="fa fa-arrow-circle-o-right"></i></div>
                    </div>
                </div>
            <?php  } else { ?>
			<?php $slider_data = serialize( array(
            /*Repeater's first item*/
            array(
				'slider_name' => 'Welcome to Guardian Theme',
				'slider_desc'      => 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.',
				'slider_image'       => get_template_directory_uri().'/images/slider.jpg' ,
				'slider_text'    => 'View More',
				'slider_link' => '#',
				),
			array(
				'slider_name' => 'Welcome to Guardian Theme',
				'slider_desc'      => 'Lorem ipsum dolor sit amet consectetur adipiscing elit sed do eiusmod tempor incididunt ut labore et dolore.',
				'slider_image'       => get_template_directory_uri().'/images/slider.jpg' ,
				'slider_text'    => 'View More',
				'slider_link' => '#',
				),
			));
			
                        $name_arr = unserialize($slider_data);
			
			?>
			
			<div class="guardian_options_slider">
                    <div class="swiper-container guardian_slider">
                        <div class="swiper-wrapper ">
                            <?php
                            foreach ( $name_arr as $key => $value ) {
                                if ( ! empty ( $value['slider_image']) ) { ?>
                                    <div class="swiper-slide">
                                        <img src="<?php echo esc_url( $value['slider_image'] ); ?>" alt="<?php  the_title(); ?>" class="home_slider img-responsive" />
                                        <div class="overlay"></div>
                                        <div class="carousel-caption">
                                            <?php if ( ! empty ( $value['slider_name'] ) ) {  ?>
                                                <h2 class="guardian_slide_title animation animated-item-1"><strong><?php esc_html_e($value['slider_name'],WL_COMPANION_DOMAIN); ?></strong></h2>
                                            <?php } ?>
                                            <?php if ( ! empty ( $value['slider_desc'] ) ) {  ?>
                                                <p class="guardian_slide_desc animation animated-item-2"><?php echo wp_kses_post($value['slider_desc']); ?></p>
                                            <?php } if ( ! empty ( $value['slider_link'] ) )  { ?>
                                                <a class="btn btn-lg btn-primary animation animated-item-3" target="_blank" href="<?php echo esc_url($value['slider_link']);  ?>" role="button"><?php if ( ! empty ( $value['slider_text'] ) ) {  esc_html_e($value['slider_text']); } ?></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php }
                            } ?>

                        </div>
                        <!-- Add Arrows -->
                        <div class="swiper-button-prev swiper-button-prev5"><i class="fa fa-arrow-circle-o-left"></i></div>
                        <div class="swiper-button-next swiper-button-next5"><i class="fa fa-arrow-circle-o-right"></i></div>
                    </div>
                </div>
			
			<?php }
        }
    }
} ?>