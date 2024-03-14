<?php
global $bizcor_options;
$slider_disable = get_theme_mod('bizcor_slider_disable',$bizcor_options['bizcor_slider_disable']);
$slides = bizcor_homepage_slider_data();
if($slider_disable==false){
?>
<section id="slider-section" class="slider-section home-slider-one">
    <div class="home-slider owl-carousel owl-theme">
        <?php 
        if(!empty($slides)) { 
            foreach ($slides as $slide) {
                $image = bizcor_get_media_url( $slide['image'] );
                $title = isset( $slide['title'] ) ?  $slide['title'] : '';
                $desc = isset( $slide['desc'] ) ?  $slide['desc'] : '';
                $button1_label = isset( $slide['button1_label'] ) ?  $slide['button1_label'] : '';
                $button1_link = isset( $slide['button1_link'] ) ?  $slide['button1_link'] : '';
                $button1_target = isset( $slide['button1_target'] ) ?  $slide['button1_target'] : '';
                $button2_label = isset( $slide['button2_label'] ) ?  $slide['button2_label'] : '';
                $button2_link = isset( $slide['button2_link'] ) ?  $slide['button2_link'] : '';
                $button2_target = isset( $slide['button2_target'] ) ?  $slide['button2_target'] : '';
        ?>
        <div class="item">
            <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_html( $title ); ?>">
            <div class="main-slider">
                <div class="main-table">
                    <div class="main-table-cell">
                        <div class="container">
                            <div class="main-content text-left">
                                <h3 data-animation="fadeInUp" data-delay="200ms"><?php echo wp_kses_post( $title ); ?></h3>
                                <p data-animation="fadeInUp" data-delay="800ms"><?php echo wp_kses_post( $desc ); ?></p>

                                <?php if($button1_label!=''){ ?>
                                <a data-animation="fadeInUp" data-delay="1300ms" href="<?php echo esc_url( $button1_link ); ?>" class="btn btn-primary left-shap" <?php if($button1_target){ echo 'target="_blank"';} ?>><?php echo esc_html( $button1_label ); ?></a>
                                <?php } ?>

                                <?php if($button2_label!=''){ ?>
                                <a data-animation="fadeInUp" data-delay="1300ms" href="<?php echo esc_url( $button2_link ); ?>" class="btn btn-secondary right-shap" <?php if($button2_target){ echo 'target="_blank"';} ?>><?php echo esc_html( $button2_label ); ?></a>
                                <div class="slider-arrow"></div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php } } ?>
    </div>
    <div class="shape-svg">
        <img src="<?php echo esc_url(bc_plugin_url); ?>inc/bizcor/img/shape-1.png">
    </div>
</section>
<?php } ?>