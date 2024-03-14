<?php

/**
 * Title: Blockpage PRO: Banner Layout with large Image
 * Slug: walker-core/blockpage-banner-large-image
 * Categories: blockpage-banner, banner
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/banner_img.jpg',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"120px"}}},"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group" style="padding-top:120px"><!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"},"margin":{"bottom":"60px"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
    <div class="wp-block-group" style="margin-bottom:60px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"100px"}}}} -->
        <div class="wp-block-columns"><!-- wp:column {"width":"50%","style":{"spacing":{"blockGap":"var:preset|spacing|50"}}} -->
            <div class="wp-block-column" style="flex-basis:50%"><!-- wp:heading {"level":5,"textColor":"primary","className":"blockpage-subheader","fontSize":"small"} -->
                <h5 class="wp-block-heading blockpage-subheader has-primary-color has-text-color has-small-font-size"><?php echo esc_html_e('Welcome to Blockpage', 'walker-core') ?></h5>
                <!-- /wp:heading -->

                <!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"xxx-large"} -->
                <h1 class="wp-block-heading has-xxx-large-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Delivering Cutting-edge Technology Since Decades.', 'walker-core') ?></h1>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"width":"50%"} -->
            <div class="wp-block-column" style="flex-basis:50%"><!-- wp:paragraph {"fontSize":"normal"} -->
                <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"42px"}}}} -->
                <div class="wp-block-buttons" style="margin-top:42px"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"},"spacing":{"padding":{"left":"35px","right":"35px","top":"18px","bottom":"18px"}}},"className":"is-style-button-hover-white-bgcolor","fontSize":"normal"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-white-bgcolor has-normal-font-size"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:18px;padding-right:35px;padding-bottom:18px;padding-left:35px"><?php echo esc_html_e('Read More', 'walker-core') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->

    <!-- wp:image {"align":"full","id":1230,"sizeSlug":"large","linkDestination":"none"} -->
    <figure class="wp-block-image alignfull size-large"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-1230" /></figure>
    <!-- /wp:image -->
</div>
<!-- /wp:group -->