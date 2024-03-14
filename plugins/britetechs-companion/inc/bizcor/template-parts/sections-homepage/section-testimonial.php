<?php
global $bizcor_options;
$testimonial_disable = get_theme_mod('bizcor_testimonial_disable',$bizcor_options['bizcor_testimonial_disable']);
$testimonial_subtitle = get_theme_mod('bizcor_testimonial_subtitle',$bizcor_options['bizcor_testimonial_subtitle']);
$testimonial_title = get_theme_mod('bizcor_testimonial_title',$bizcor_options['bizcor_testimonial_title']);
$testimonial_desc = get_theme_mod('bizcor_testimonial_desc',$bizcor_options['bizcor_testimonial_desc']);
$testimonials = bizcor_homepage_testimonial_data();

if($testimonial_disable==false){
?>
<section id="testimonials-section" class="testimonials-section bg-primary-light3 st-py-default">
    <div class="container">
        <div class="row row-cols-lg-2 row-cols-1 g-lg-0 g-4 mt-1">
            <div class="col wow fadeInLeft">
                <div class="row">
                    <div class="col-lg-8 col-12 mb-5">
                        <div class="heading-default text-white">
                            <?php if($testimonial_subtitle!=''){ ?>
                            <span class="badge"><?php echo esc_html($testimonial_subtitle); ?></span>
                            <?php } ?>

                            <?php if($testimonial_title!=''){ ?>
                            <h2 class="mb-0"><?php echo wp_kses_post($testimonial_title); ?></h2>
                            <?php } ?>

                            <?php if($testimonial_desc!=''){ ?>
                            <p class="mb-0"><?php echo wp_kses_post($testimonial_desc); ?></p>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12 testimonials-slider owl-carousel owl-theme">
                        <?php 
                        $main_image = '';
                        $main_title = '';
                        if(!empty($testimonials)) { 
                            foreach ($testimonials as $key => $testimonial) {
                                $image = bizcor_get_media_url( $testimonial['image'] );
                                $title = isset( $testimonial['title'] ) ?  $testimonial['title'] : '';
                                $designation = isset( $testimonial['designation'] ) ?  $testimonial['designation'] : '';
                                $desc = isset( $testimonial['desc'] ) ?  $testimonial['desc'] : '';
                                $rating = isset( $testimonial['rating'] ) ?  $testimonial['rating'] : '';

                                if($key==0){
                                    $main_image = $image;
                                    $main_title = $title;
                                }
                        ?>
                        <div class="testimonials-item">
                            <div class="testimonials-content">
                                <div class="testimonials-client">
                                    <div class="img-fluid">
                                        <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
                                    </div>
                                </div>
                                <div class="testimonials-title">
                                    <div class="title">
                                        <?php if($title!=''){ ?>
                                        <h4><?php echo esc_html($title); ?></h4>
                                        <?php } ?>
                                        <?php if($designation!=''){ ?>
                                        <span><?php echo esc_html($designation); ?></span>
                                        <?php } ?>
                                    </div>
                                    <div class="rating-star">
                                        <?php for ($i=1; $i <= $rating; $i++) { ?>
                                        <i class="fas fa-star"></i>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                            <?php if($desc!=''){ ?>
                            <p><?php echo esc_html($desc); ?></p>
                            <?php } ?>
                        </div>
                        <?php } } ?>
                    </div>
                </div>
            </div>
            <div class="col wow fadeInRight m-auto">
                <div class="testimonial-right">
                    <div class="border-shap">
                        <div class="testimonial-bg">
                            <div class="testimonial-img">
                                <img src="<?php echo esc_url($main_image); ?>" alt="<?php echo esc_attr($main_title); ?>">
                            </div>
                            <div class="icon">
                                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/img/icon-shap.png">
                            </div>
                        </div>                      
                        <div class="border-circle"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>