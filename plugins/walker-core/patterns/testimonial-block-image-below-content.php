<?php

/**
 * Title: Testimonial Block Image below Content
 * Slug: walker-core/testimonial-block-image-below-content
 * Categories: walkercore-patterns
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/patterns-media/pattern_thumb_dark.png',
    WALKER_CORE_URL . 'admin/images/patterns-media/star_icon.png',
);
?><!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"background-alt"} -->
        <h2 class="wp-block-heading has-text-align-center has-background-alt-color has-text-color" style="font-style:normal;font-weight:500">Testimonials</h2>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"align":"center","textColor":"foreground"} -->
        <p class="has-text-align-center has-foreground-color has-text-color">Check out our new&nbsp;font generatorand level up your social bios. Need more? Head over to Glyphy for all the&nbsp;fancy fonts&nbsp;and&nbsp;cool symbols&nbsp;you could ever imagine.</p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"style":{"spacing":{"padding":{"top":"var:preset|spacing|60"},"blockGap":{"top":"var:preset|spacing|70","left":"var:preset|spacing|70"}}}} -->
    <div class="wp-block-columns" style="padding-top:var(--wp--preset--spacing--60)"><!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group"><!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"foreground","fontSize":"large"} -->
                <h3 class="wp-block-heading has-foreground-color has-text-color has-large-font-size" style="font-style:normal;font-weight:500">Best support ever</h3>
                <!-- /wp:heading -->

                <!-- wp:image {"id":378,"width":100,"height":24,"sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-378" width="100" height="24" /></figure>
                <!-- /wp:image -->

                <!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group"><!-- wp:image {"id":330,"width":80,"height":80,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"100px"}}} -->
                <figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-330" style="border-radius:100px" width="80" height="80" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                <div class="wp-block-group"><!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}}} -->
                    <h4 class="wp-block-heading" style="font-style:normal;font-weight:500">Alexa T</h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"textColor":"light-gray","fontSize":"small"} -->
                    <p class="has-light-gray-color has-text-color has-small-font-size">Lead Designer</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group"><!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"foreground","fontSize":"large"} -->
                <h3 class="wp-block-heading has-foreground-color has-text-color has-large-font-size" style="font-style:normal;font-weight:500">Best support ever</h3>
                <!-- /wp:heading -->

                <!-- wp:image {"id":378,"width":100,"height":24,"sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-378" width="100" height="24" /></figure>
                <!-- /wp:image -->

                <!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group"><!-- wp:image {"id":330,"width":80,"height":80,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"100px"}}} -->
                <figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-330" style="border-radius:100px" width="80" height="80" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                <div class="wp-block-group"><!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}}} -->
                    <h4 class="wp-block-heading" style="font-style:normal;font-weight:500">Alexa T</h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"textColor":"light-gray","fontSize":"small"} -->
                    <p class="has-light-gray-color has-text-color has-small-font-size">Lead Designer</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group"><!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"foreground","fontSize":"large"} -->
                <h3 class="wp-block-heading has-foreground-color has-text-color has-large-font-size" style="font-style:normal;font-weight:500">Best support ever</h3>
                <!-- /wp:heading -->

                <!-- wp:image {"id":378,"width":100,"height":24,"sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-378" width="100" height="24" /></figure>
                <!-- /wp:image -->

                <!-- wp:paragraph -->
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group"><!-- wp:image {"id":330,"width":80,"height":80,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"100px"}}} -->
                <figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-330" style="border-radius:100px" width="80" height="80" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                <div class="wp-block-group"><!-- wp:heading {"level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}}} -->
                    <h4 class="wp-block-heading" style="font-style:normal;font-weight:500">Alexa T</h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"textColor":"light-gray","fontSize":"small"} -->
                    <p class="has-light-gray-color has-text-color has-small-font-size">Lead Designer</p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->