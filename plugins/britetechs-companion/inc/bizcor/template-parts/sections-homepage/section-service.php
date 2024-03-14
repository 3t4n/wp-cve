<?php
global $bizcor_options;
$service_disable = get_theme_mod('bizcor_service_disable',$bizcor_options['bizcor_service_disable']);
$service_subtitle = get_theme_mod('bizcor_service_subtitle',$bizcor_options['bizcor_service_subtitle']);
$service_title = get_theme_mod('bizcor_service_title',$bizcor_options['bizcor_service_title']);
$service_desc = get_theme_mod('bizcor_service_desc',$bizcor_options['bizcor_service_desc']);
$services = bizcor_homepage_service_data();
$service_column = get_theme_mod('bizcor_service_column',$bizcor_options['bizcor_service_column']);

if($service_disable==false){
?>
<section id="service-section" class="service-section service-home st-py-default">
    <div class="container">
        <div class="row">
            <div class="col-lg-5 col-12 mx-lg-auto mb-5 text-center">
                <div class="heading-default wow fadeInUp">
                    <?php if($service_subtitle!=''){ ?>
                    <span class="badge"><?php echo esc_html($service_subtitle); ?></span>
                    <?php } ?>

                    <?php if($service_title!=''){ ?>
                    <h2 class="mb-0"><?php echo wp_kses_post($service_title); ?></h2>
                    <?php } ?>

                    <?php if($service_desc!=''){ ?>
                    <p class="mb-0"><?php echo wp_kses_post($service_desc); ?></p>
                    <?php } ?>                     
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12 col-12 mx-lg-auto">
                <div class="row row-cols-1 row-cols-lg-<?php echo esc_attr($service_column); ?> row-cols-md-2 g-4 wow fadeInUp">
                    <?php 
                    if(!empty($services)) { 
                        foreach ($services as $service) {
                            $image = bizcor_get_media_url( $service['image'] );
                            $icon = isset( $service['icon'] ) ?  $service['icon'] : '';
                            $title = isset( $service['title'] ) ?  $service['title'] : '';
                            $desc = isset( $service['desc'] ) ?  $service['desc'] : '';
                            $link = '';

                            $class = 'st-load-item';
                            if( is_page_template('templates/template-service.php') ){
                                $class = '';
                            }

                    ?>
                    <div class="col <?php echo esc_attr($class); ?>">
                        <div class="theme-item">
                            <div class="theme-item-overlay">
                                <img src="<?php echo esc_url($image); ?>" alt="<?php echo esc_attr($title); ?>">
                                <?php if($link!=''){ ?>
                                <a href="<?php echo esc_url($link); ?>" class="readmore"><i class="fas fa-arrow-right"></i></a>
                                <?php } ?>
                            </div>
                            <div class="theme-flexbox">
                                <?php if($icon!=''){ ?>
                                <div class="theme-icon">
                                    <i class="<?php echo esc_attr($icon); ?>"></i>
                                </div>
                                <?php } ?>
                                
                                <div class="theme-content">
                                    <?php if($title!=''){ ?>
                                    <h5 class="theme-title">
                                        <?php if($link!=''){ ?>
                                        <a href="<?php echo esc_url($link); ?>">
                                        <?php } ?>
                                            <?php echo wp_kses_post($title); ?>
                                        <?php if($link!=''){ ?>
                                        </a>
                                        <?php } ?>
                                    </h5>
                                    <?php } ?>

                                    <?php if($desc!=''){ ?>
                                    <p><?php echo wp_kses_post($desc); ?></p>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } } ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php } ?>