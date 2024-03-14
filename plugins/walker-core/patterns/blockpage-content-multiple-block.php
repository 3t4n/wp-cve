<?php

/**
 * Title: Blockpage PRO: Content with Multiple Blocks
 * Slug: walker-core/blockpage-content-multiple-block
 * Categories: blockpage-about
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/about_half_1.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/about_half_2.png',
    WALKER_CORE_URL . 'admin/images/blockpage/about_half_3.png'
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"120px","bottom":"120px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="padding-top:120px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"140px"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"60%","style":{"spacing":{"blockGap":"var:preset|spacing|50"}}} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%"><!-- wp:heading {"level":5,"textColor":"primary","className":"blockpage-subheader","fontSize":"small"} -->
            <h5 class="wp-block-heading blockpage-subheader has-primary-color has-text-color has-small-font-size"><?php echo esc_html_e('Our Story', 'walker-core') ?></h5>
            <!-- /wp:heading -->

            <!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","lineHeight":"1.3"}},"fontSize":"xxx-large"} -->
            <h1 class="wp-block-heading has-xxx-large-font-size" style="font-style:normal;font-weight:500;line-height:1.3"><?php echo esc_html_e('Refining technology as business driven.', 'walker-core') ?></h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"fontSize":"normal"} -->
            <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.', 'walker-core') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"42px"}}}} -->
            <div class="wp-block-buttons" style="margin-top:42px"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"},"spacing":{"padding":{"left":"35px","right":"35px","top":"18px","bottom":"18px"}}},"className":"is-style-button-hover-white-bgcolor","fontSize":"normal"} -->
                <div class="wp-block-button has-custom-font-size is-style-button-hover-white-bgcolor has-normal-font-size"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:18px;padding-right:35px;padding-bottom:18px;padding-left:35px"><?php echo esc_html_e('Read More', 'walker-core') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%"><!-- wp:image {"id":1202,"sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"12px"}}} -->
            <figure class="wp-block-image size-large has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-1202" style="border-radius:12px" /></figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"140px"},"margin":{"top":"120px"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-center" style="margin-top:120px"><!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%"><!-- wp:image {"id":1212,"sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"12px"}}} -->
            <figure class="wp-block-image size-large has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-1212" style="border-radius:12px" /></figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"60%","style":{"spacing":{"blockGap":"var:preset|spacing|50"}}} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%"><!-- wp:heading {"level":5,"textColor":"primary","className":"blockpage-subheader","fontSize":"small"} -->
            <h5 class="wp-block-heading blockpage-subheader has-primary-color has-text-color has-small-font-size"><?php echo esc_html_e('Mission and Goal', 'walker-core') ?></h5>
            <!-- /wp:heading -->

            <!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","lineHeight":"1.3"}},"fontSize":"xxx-large"} -->
            <h1 class="wp-block-heading has-xxx-large-font-size" style="font-style:normal;font-weight:500;line-height:1.3"><?php echo esc_html_e('Growth is the only facts that matters.', 'walker-core') ?></h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"fontSize":"normal"} -->
            <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.', 'walker-core') ?></p>
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

    <!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"140px"},"margin":{"top":"120px"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-center" style="margin-top:120px"><!-- wp:column {"verticalAlignment":"center","width":"60%","style":{"spacing":{"blockGap":"var:preset|spacing|50"}}} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%"><!-- wp:heading {"level":5,"textColor":"primary","className":"blockpage-subheader","fontSize":"small"} -->
            <h5 class="wp-block-heading blockpage-subheader has-primary-color has-text-color has-small-font-size"><?php echo esc_html_e('The Future', 'walker-core') ?></h5>
            <!-- /wp:heading -->

            <!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","lineHeight":"1.3"}},"fontSize":"xxx-large"} -->
            <h1 class="wp-block-heading has-xxx-large-font-size" style="font-style:normal;font-weight:500;line-height:1.3"><?php echo esc_html_e('Refining technology as business driven.', 'walker-core') ?></h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"fontSize":"normal"} -->
            <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.', 'walker-core') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"42px"}}}} -->
            <div class="wp-block-buttons" style="margin-top:42px"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"},"spacing":{"padding":{"left":"35px","right":"35px","top":"18px","bottom":"18px"}}},"className":"is-style-button-hover-white-bgcolor","fontSize":"normal"} -->
                <div class="wp-block-button has-custom-font-size is-style-button-hover-white-bgcolor has-normal-font-size"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:18px;padding-right:35px;padding-bottom:18px;padding-left:35px"><?php echo esc_html_e('Read More', 'walker-core') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%"><!-- wp:image {"id":1297,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"12px"}}} -->
            <figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[2]) ?>" alt="" class="wp-image-1297" style="border-radius:12px" /></figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->