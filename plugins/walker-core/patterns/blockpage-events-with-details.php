<?php

/**
 * Title: Blockpage PRO: Events List with Details
 * Slug: walker-core/blockpage-events-with-details
 * Categories: blockpage-events
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/about_half_1.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/about_half_2.png',
    WALKER_CORE_URL . 'admin/images/blockpage/about_half_3.png',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"120px","bottom":"120px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="padding-top:120px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"80px"},"margin":{"top":"0","bottom":"0"},"padding":{"top":"50px","bottom":"50px"}}}} -->
    <div class="wp-block-columns" style="margin-top:0;margin-bottom:0;padding-top:50px;padding-bottom:50px"><!-- wp:column {"width":"10%"} -->
        <div class="wp-block-column" style="flex-basis:10%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
            <div class="wp-block-group"><!-- wp:heading {"level":5,"style":{"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"300"}},"textColor":"foreground"} -->
                <h5 class="wp-block-heading has-foreground-color has-text-color" style="font-style:normal;font-weight:300;text-transform:uppercase"><?php echo esc_html_e('FRI', 'walker-core') ?></h5>
                <!-- /wp:heading -->

                <!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}}} -->
                <h1 class="wp-block-heading" style="font-style:normal;font-weight:500"><?php echo esc_html_e('30', 'walker-core') ?></h1>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"50%"} -->
        <div class="wp-block-column" style="flex-basis:50%"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase"}},"textColor":"primary","fontSize":"small"} -->
            <p class="has-primary-color has-text-color has-small-font-size" style="text-transform:uppercase"><?php echo esc_html_e('30 January, 2024 - 08:00 AM - 2:00 PM', 'walker-core') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"xx-large"} -->
            <h3 class="wp-block-heading has-xx-large-font-size" style="margin-top:24px"><?php echo esc_html_e('Day of Open Source Contributors', 'walker-core') ?></h3>
            <!-- /wp:heading -->

            <!-- wp:heading {"level":5,"style":{"spacing":{"margin":{"top":"24px"}}},"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color" style="margin-top:24px"><?php echo esc_html_e('104 Street, New York, USA', 'walker-core') ?></h5>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
            <p class="has-normal-font-size" style="line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'walker-core') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:group {"style":{"spacing":{"margin":{"top":"32px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group" style="margin-top:32px"><!-- wp:heading {"level":5,"style":{"spacing":{"margin":{"top":"0px"}}}} -->
                <h5 class="wp-block-heading" style="margin-top:0px"><?php echo esc_html_e('$250-$600', 'walker-core') ?></h5>
                <!-- /wp:heading -->

                <!-- wp:buttons -->
                <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"}},"className":"is-style-button-hover-white-bgcolor"} -->
                    <div class="wp-block-button is-style-button-hover-white-bgcolor"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px"><?php echo esc_html_e('Book Ticket', 'walker-core') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"35%"} -->
        <div class="wp-block-column" style="flex-basis:35%"><!-- wp:image {"id":1212,"sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image size-large"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-1212" /></figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"80px"},"margin":{"top":"0","bottom":"0"},"padding":{"top":"50px","bottom":"50px"}}}} -->
    <div class="wp-block-columns" style="margin-top:0;margin-bottom:0;padding-top:50px;padding-bottom:50px"><!-- wp:column {"width":"10%"} -->
        <div class="wp-block-column" style="flex-basis:10%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
            <div class="wp-block-group"><!-- wp:heading {"level":5,"style":{"typography":{"fontStyle":"normal","fontWeight":"300","textTransform":"uppercase"}},"textColor":"foreground"} -->
                <h5 class="wp-block-heading has-foreground-color has-text-color" style="font-style:normal;font-weight:300;text-transform:uppercase"><?php echo esc_html_e('MON', 'walker-core') ?></h5>
                <!-- /wp:heading -->

                <!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}}} -->
                <h1 class="wp-block-heading" style="font-style:normal;font-weight:500"><?php echo esc_html_e('03', 'walker-core') ?></h1>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"50%"} -->
        <div class="wp-block-column" style="flex-basis:50%"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase"}},"textColor":"primary","fontSize":"small"} -->
            <p class="has-primary-color has-text-color has-small-font-size" style="text-transform:uppercase"><?php echo esc_html_e('03 February, 2024 - 08:00 AM - 2:00 PM', 'walker-core') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"xx-large"} -->
            <h3 class="wp-block-heading has-xx-large-font-size" style="margin-top:24px"><?php echo esc_html_e('International Labor Day', 'walker-core') ?></h3>
            <!-- /wp:heading -->

            <!-- wp:heading {"level":5,"style":{"spacing":{"margin":{"top":"24px"}}},"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color" style="margin-top:24px"><?php echo esc_html_e('104 Street, New York, USA', 'walker-core') ?></h5>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
            <p class="has-normal-font-size" style="line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'walker-core') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:group {"style":{"spacing":{"margin":{"top":"32px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group" style="margin-top:32px"><!-- wp:heading {"level":5,"style":{"spacing":{"margin":{"top":"0px"}}}} -->
                <h5 class="wp-block-heading" style="margin-top:0px"><?php echo esc_html_e('$250-$600', 'walker-core') ?></h5>
                <!-- /wp:heading -->

                <!-- wp:buttons -->
                <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"}},"className":"is-style-button-hover-white-bgcolor"} -->
                    <div class="wp-block-button is-style-button-hover-white-bgcolor"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px"><?php echo esc_html_e('Book Ticket', 'walker-core') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"35%"} -->
        <div class="wp-block-column" style="flex-basis:35%"><!-- wp:image {"id":916,"sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image size-large"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-916" /></figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"80px"},"margin":{"top":"0","bottom":"0"},"padding":{"top":"50px","bottom":"50px"}}}} -->
    <div class="wp-block-columns" style="margin-top:0;margin-bottom:0;padding-top:50px;padding-bottom:50px"><!-- wp:column {"width":"10%"} -->
        <div class="wp-block-column" style="flex-basis:10%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
            <div class="wp-block-group"><!-- wp:heading {"level":5,"style":{"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"300"}},"textColor":"foreground"} -->
                <h5 class="wp-block-heading has-foreground-color has-text-color" style="font-style:normal;font-weight:300;text-transform:uppercase"><?php echo esc_html_e('Wed', 'walker-core') ?></h5>
                <!-- /wp:heading -->

                <!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}}} -->
                <h1 class="wp-block-heading" style="font-style:normal;font-weight:500"><?php echo esc_html_e('05', 'walker-core') ?></h1>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"50%"} -->
        <div class="wp-block-column" style="flex-basis:50%"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase"}},"textColor":"primary","fontSize":"small"} -->
            <p class="has-primary-color has-text-color has-small-font-size" style="text-transform:uppercase"><?php echo esc_html_e('05 February, 2024 - 08:00 AM - 2:00 PM', 'walker-core') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"xx-large"} -->
            <h3 class="wp-block-heading has-xx-large-font-size" style="margin-top:24px"><?php echo esc_html_e('International Celebration Day', 'walker-core') ?></h3>
            <!-- /wp:heading -->

            <!-- wp:heading {"level":5,"style":{"spacing":{"margin":{"top":"24px"}}},"textColor":"foreground"} -->
            <h5 class="wp-block-heading has-foreground-color has-text-color" style="margin-top:24px"><?php echo esc_html_e('104 Street, New York, USA', 'walker-core') ?></h5>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
            <p class="has-normal-font-size" style="line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'walker-core') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:group {"style":{"spacing":{"margin":{"top":"32px"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group" style="margin-top:32px"><!-- wp:heading {"level":5,"style":{"spacing":{"margin":{"top":"0px"}}}} -->
                <h5 class="wp-block-heading" style="margin-top:0px"><?php echo esc_html_e('$250-$600', 'walker-core') ?></h5>
                <!-- /wp:heading -->

                <!-- wp:buttons -->
                <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"}},"className":"is-style-button-hover-white-bgcolor"} -->
                    <div class="wp-block-button is-style-button-hover-white-bgcolor"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px"><?php echo esc_html_e('Book Ticket', 'walker-core') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"35%"} -->
        <div class="wp-block-column" style="flex-basis:35%"><!-- wp:image {"id":1215,"sizeSlug":"large","linkDestination":"none"} -->
            <figure class="wp-block-image size-large"><img src="<?php echo esc_url($walkercore_patterns_images[2]) ?>" alt="" class="wp-image-1215" /></figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->