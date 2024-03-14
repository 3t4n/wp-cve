<?php

defined('ABSPATH') or die();

class wl_companion_testimonials_swiftly {

    public static function wl_companion_testimonial_swiftly_html() { ?>

        <!-- Testimonial section -->   
        <section class="ftco-section testimony-section bg-primary">
            <?php
            $enigma_testimonial_title = get_theme_mod('enigma_testimonial_title', 'What Our Client Say');
            if ( !empty($enigma_testimonial_title )) { ?>
            <div class="container">
                <div class="row justify-content-center pb-5">
                    <div class="col-md-12 heading-section heading-section-white text-center ftco-animate">
                        <span class="subheading"><?php echo get_theme_mod( 'enigma_testimonial_title', 'TESTIMONIES' ); ?></span>
                        <h2 class="mb-4"><?php echo get_theme_mod( 'enigma_testimonial_sub_title', 'What Our Client Say' ); ?></h2>
                    </div>
                </div> <?php   
                } ?>
                <div class="row ftco-animate">
                    <div class="col-md-12">
                        <div class="carousel-testimony owl-carousel">

                            <?php 
                            if ( ! empty ( get_theme_mod('enigma_testimonial_data' ) ) ) {
                                $name_arr = unserialize( get_theme_mod( 'enigma_testimonial_data') );
                                foreach ( $name_arr as $key => $value ) { ?>
                                    
                                    <div class="item">
                                        <div class="testimony-wrap py-4">
                                            <div class="text">
                                                <span class="fa fa-quote-left"></span>
                                                <p class="mb-4 pl-5">
                                                    <?php if ( ! empty ( $value['testimonial_desc'] ) ) { echo esc_textarea($value['testimonial_desc']); } ?>
                                                </p>
                                                <div class="d-flex align-items-center">
                                                    <div class="user-img" style="background-image: url( <?php echo esc_url($value['testimonial_image']); ?> )"></div>
                                                    <div class="pl-3">
                                                        <p class="name"> <?php echo esc_attr_e($value['testimonial_name']); ?> </p>
                                                        <span class="position"> <?php echo esc_attr_e($value['testimonial_designation']); ?> </span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> <?php 

                                } 
                            } ?>

                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- /Testimonial  section --> <?php

    }
} ?>
