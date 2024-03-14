<?php

/**
 * Title: Blockpage PRO: Events Grid
 * Slug: walker-core/blockpage-events-grid
 * Categories: blockpage-events
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/about_half_1.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/about_half_2.png',
    WALKER_CORE_URL . 'admin/images/blockpage/about_half_3.png',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"120px","bottom":"120px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="padding-top:120px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"constrained","contentSize":"760px"}} -->
    <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"blockpage-heading"} -->
        <h1 class="wp-block-heading has-text-align-center blockpage-heading" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Upcoming Events', 'walker-core') ?></h1>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.5"},"spacing":{"margin":{"top":"50px"}}},"fontSize":"normal"} -->
        <p class="has-text-align-center has-normal-font-size" style="margin-top:50px;line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"42px"},"margin":{"top":"60px","bottom":"0"},"padding":{"top":"0px","bottom":"0px"}}}} -->
    <div class="wp-block-columns" style="margin-top:60px;margin-bottom:0;padding-top:0px;padding-bottom:0px"><!-- wp:column {"width":"50%"} -->
        <div class="wp-block-column" style="flex-basis:50%"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"border":{"radius":"0px"}},"backgroundColor":"background-alt","layout":{"type":"constrained"}} -->
            <div class="wp-block-group has-background-alt-background-color has-background" style="border-radius:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:image {"id":1297,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
                <figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-1297" style="border-radius:0px" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"padding":{"top":"10px","bottom":"30px","left":"30px","right":"30px"}}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-group" style="padding-top:10px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase"}},"textColor":"primary","fontSize":"small"} -->
                    <p class="has-primary-color has-text-color has-small-font-size" style="text-transform:uppercase"><?php echo esc_html_e('30 January, 2024 - 08:00 AM - 2:00 PM', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"x-large"} -->
                    <h4 class="wp-block-heading has-x-large-font-size" style="margin-top:24px"><?php echo esc_html_e('Day of Open Source Contributors', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:heading {"level":5,"style":{"spacing":{"margin":{"top":"24px"}}},"textColor":"foreground"} -->
                    <h5 class="wp-block-heading has-foreground-color has-text-color" style="margin-top:24px"><?php echo esc_html_e('104 Street, New York, USA', 'walker-core') ?></h5>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
                    <p class="has-normal-font-size" style="line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
                    <div class="wp-block-group"><!-- wp:heading {"level":5} -->
                        <h5 class="wp-block-heading"><?php echo esc_html_e('$250-$600', 'walker-core') ?></h5>
                        <!-- /wp:heading -->

                        <!-- wp:buttons -->
                        <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-color","gradient":"primary-gradient","style":{"spacing":{"padding":{"left":"var:preset|spacing|40","right":"var:preset|spacing|40","top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}},"border":{"radius":"60px"}},"className":"is-style-button-hover-white-bgcolor"} -->
                            <div class="wp-block-button is-style-button-hover-white-bgcolor"><a class="wp-block-button__link has-heading-color-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40)"><?php echo esc_html_e('Book Ticket', 'walker-core') ?></a></div>
                            <!-- /wp:button -->
                        </div>
                        <!-- /wp:buttons -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"50%"} -->
        <div class="wp-block-column" style="flex-basis:50%"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"border":{"radius":"0px"}},"backgroundColor":"background-alt","layout":{"type":"constrained"}} -->
            <div class="wp-block-group has-background-alt-background-color has-background" style="border-radius:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:image {"id":1296,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
                <figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-1296" style="border-radius:0px" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"padding":{"top":"10px","bottom":"30px","left":"30px","right":"30px"}}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-group" style="padding-top:10px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase"}},"textColor":"primary","fontSize":"small"} -->
                    <p class="has-primary-color has-text-color has-small-font-size" style="text-transform:uppercase"><?php echo esc_html_e('01 May, 2024 - 8:00AM - 4:00PM', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"x-large"} -->
                    <h4 class="wp-block-heading has-x-large-font-size" style="margin-top:24px"><?php echo esc_html_e('International Workers\'s Day', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:heading {"level":5,"style":{"spacing":{"margin":{"top":"24px"}}},"textColor":"foreground"} -->
                    <h5 class="wp-block-heading has-foreground-color has-text-color" style="margin-top:24px"><?php echo esc_html_e('104 Street, New York, USA', 'walker-core') ?></h5>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
                    <p class="has-normal-font-size" style="line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
                    <div class="wp-block-group"><!-- wp:heading {"level":5} -->
                        <h5 class="wp-block-heading"><?php echo esc_html_e('$50-$200', 'walker-core') ?></h5>
                        <!-- /wp:heading -->

                        <!-- wp:buttons -->
                        <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-color","gradient":"primary-gradient","style":{"spacing":{"padding":{"left":"var:preset|spacing|40","right":"var:preset|spacing|40","top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}},"border":{"radius":"60px"}},"className":"is-style-button-hover-white-bgcolor"} -->
                            <div class="wp-block-button is-style-button-hover-white-bgcolor"><a class="wp-block-button__link has-heading-color-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40)"><?php echo esc_html_e('Book Ticket', 'walker-core') ?></a></div>
                            <!-- /wp:button -->
                        </div>
                        <!-- /wp:buttons -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"50%"} -->
        <div class="wp-block-column" style="flex-basis:50%"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"border":{"radius":"0px"}},"backgroundColor":"background-alt","layout":{"type":"constrained"}} -->
            <div class="wp-block-group has-background-alt-background-color has-background" style="border-radius:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:image {"id":1298,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
                <figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[2]) ?>" alt="" class="wp-image-1298" style="border-radius:0px" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"padding":{"top":"10px","bottom":"30px","left":"30px","right":"30px"}}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-group" style="padding-top:10px;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:paragraph {"style":{"typography":{"textTransform":"uppercase"}},"textColor":"primary","fontSize":"small"} -->
                    <p class="has-primary-color has-text-color has-small-font-size" style="text-transform:uppercase"><?php echo esc_html_e('05 February, 2024 - 08:00 AM - 2:00 PM', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"x-large"} -->
                    <h4 class="wp-block-heading has-x-large-font-size" style="margin-top:24px"><?php echo esc_html_e('International Celebration Day', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:heading {"level":5,"style":{"spacing":{"margin":{"top":"24px"}}},"textColor":"foreground"} -->
                    <h5 class="wp-block-heading has-foreground-color has-text-color" style="margin-top:24px"><?php echo esc_html_e('104 Street, New York, USA', 'walker-core') ?></h5>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
                    <p class="has-normal-font-size" style="line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
                    <div class="wp-block-group"><!-- wp:heading {"level":5} -->
                        <h5 class="wp-block-heading"><?php echo esc_html_e('$150-$300', 'walker-core') ?></h5>
                        <!-- /wp:heading -->

                        <!-- wp:buttons -->
                        <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-color","gradient":"primary-gradient","style":{"spacing":{"padding":{"left":"var:preset|spacing|40","right":"var:preset|spacing|40","top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}},"border":{"radius":"60px"}},"className":"is-style-button-hover-white-bgcolor"} -->
                            <div class="wp-block-button is-style-button-hover-white-bgcolor"><a class="wp-block-button__link has-heading-color-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--40);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--40)"><?php echo esc_html_e('Book Ticket', 'walker-core') ?></a></div>
                            <!-- /wp:button -->
                        </div>
                        <!-- /wp:buttons -->
                    </div>
                    <!-- /wp:group -->
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