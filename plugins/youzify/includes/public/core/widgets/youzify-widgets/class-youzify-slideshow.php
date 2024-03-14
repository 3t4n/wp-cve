<?php

class Youzify_Profile_Slideshow_Widget {

    /**
     * Content.
     */
    function widget() {

        // Get Slides.
        $slides = get_the_author_meta( 'youzify_slideshow', bp_displayed_user_id() );

        if ( empty( $slides ) ) {
            return;
        }

        // Load Carousel CSS and JS.
        wp_enqueue_style( 'youzify-carousel-css', YOUZIFY_ASSETS . 'css/youzify-owl-carousel.min.css', array(), YOUZIFY_VERSION );
        wp_enqueue_script( 'youzify-carousel-js', YOUZIFY_ASSETS . 'js/youzify-owl-carousel.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );
        wp_enqueue_script( 'youzify-slider', YOUZIFY_ASSETS . 'js/youzify-slider.min.js', array( 'jquery' ), YOUZIFY_VERSION, true );

        ?>

        <ul class="youzify-slider youzify-slides-<?php echo youzify_option( 'youzify_slideshow_height_type', 'fixed' ); ?>-height">
        <?php foreach ( $slides as $slide ) : ?><li class="youzify-slideshow-item"><img loading="lazy" <?php echo youzify_get_image_attributes( $slide['image'], 'youzify-wide', 'profile-slideshow-widget' ); ?> alt=""></li><?php endforeach; ?>
    	</ul>

    	<?php

    }

}