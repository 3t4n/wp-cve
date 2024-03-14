<?php

/**
 * Title: Blockpage PRO: Content content with features list
 * Slug: walker-core/blockpage-content-with-features
 * Categories: blockpage-about
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/about_half_2.png',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"120px","bottom":"120px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="padding-top:120px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"constrained","contentSize":"760px"}} -->
    <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":5,"textColor":"primary","fontSize":"small"} -->
        <h5 class="wp-block-heading has-text-align-center has-primary-color has-text-color has-small-font-size"><?php echo esc_html_e('Our Story', 'walker-core') ?></h5>
        <!-- /wp:heading -->

        <!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","fontSize":"60px"}}} -->
        <h1 class="wp-block-heading has-text-align-center" style="font-size:60px;font-style:normal;font-weight:500"><?php echo esc_html_e('Growth is the only facts that matters.', 'walker-core') ?></h1>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"align":"center","fontSize":"normal"} -->
        <p class="has-text-align-center has-normal-font-size"><?php echo esc_html_e('The French lettering company&nbsp;Letraset&nbsp;manufactured a set of dry-transfer sheets which included the&nbsp;lorem<em> </em>ipsum&nbsp;filler text in a variety of fonts', 'walker-core') ?></p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"blockGap":{"top":"0","left":"100px"},"margin":{"top":"80px"},"padding":{"top":"80px"}},"border":{"top":{"color":"var:preset|color|border-color","width":"1px"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-top" style="border-top-color:var(--wp--preset--color--border-color);border-top-width:1px;margin-top:80px;padding-top:80px"><!-- wp:column {"verticalAlignment":"top","width":"50%","style":{"spacing":{"blockGap":"var:preset|spacing|60"}}} -->
        <div class="wp-block-column is-vertically-aligned-top" style="flex-basis:50%"><!-- wp:heading {"level":3,"textColor":"primary","className":"blockpage-subheader"} -->
            <h3 class="wp-block-heading blockpage-subheader has-primary-color has-text-color"><?php echo esc_html_e('Key Features', 'walker-core') ?></h3>
            <!-- /wp:heading -->

            <!-- wp:columns {"style":{"spacing":{"margin":{"top":"42px"}}}} -->
            <div class="wp-block-columns" style="margin-top:42px"><!-- wp:column {"width":"100px"} -->
                <div class="wp-block-column" style="flex-basis:100px"><!-- wp:heading {"level":3} -->
                    <h3 class="wp-block-heading"><?php echo esc_html_e('01.', 'walker-core') ?></h3>
                    <!-- /wp:heading -->
                </div>
                <!-- /wp:column -->

                <!-- wp:column {"width":"66.66%"} -->
                <div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
                    <div class="wp-block-group"><!-- wp:heading {"level":4} -->
                        <h4 class="wp-block-heading"><?php echo esc_html_e('Drag and Drop', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"fontSize":"normal"} -->
                        <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:column -->
            </div>
            <!-- /wp:columns -->

            <!-- wp:columns -->
            <div class="wp-block-columns"><!-- wp:column {"width":"100px"} -->
                <div class="wp-block-column" style="flex-basis:100px"><!-- wp:heading {"level":3} -->
                    <h3 class="wp-block-heading"><?php echo esc_html_e('02.', 'walker-core') ?></h3>
                    <!-- /wp:heading -->
                </div>
                <!-- /wp:column -->

                <!-- wp:column {"width":"66.66%"} -->
                <div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
                    <div class="wp-block-group"><!-- wp:heading {"level":4} -->
                        <h4 class="wp-block-heading"><?php echo esc_html_e('Fully Customizable Header &amp; Footer', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"fontSize":"normal"} -->
                        <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:column -->
            </div>
            <!-- /wp:columns -->

            <!-- wp:columns -->
            <div class="wp-block-columns"><!-- wp:column {"width":"100px"} -->
                <div class="wp-block-column" style="flex-basis:100px"><!-- wp:heading {"level":3} -->
                    <h3 class="wp-block-heading"><?php echo esc_html_e('03.', 'walker-core') ?></h3>
                    <!-- /wp:heading -->
                </div>
                <!-- /wp:column -->

                <!-- wp:column {"width":"66.66%"} -->
                <div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
                    <div class="wp-block-group"><!-- wp:heading {"level":4} -->
                        <h4 class="wp-block-heading"><?php echo esc_html_e('Wide Range of Patterns Library', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"fontSize":"normal"} -->
                        <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:column -->
            </div>
            <!-- /wp:columns -->

            <!-- wp:columns -->
            <div class="wp-block-columns"><!-- wp:column {"width":"100px"} -->
                <div class="wp-block-column" style="flex-basis:100px"><!-- wp:heading {"level":3} -->
                    <h3 class="wp-block-heading"><?php echo esc_html_e('04.', 'walker-core') ?></h3>
                    <!-- /wp:heading -->
                </div>
                <!-- /wp:column -->

                <!-- wp:column {"width":"66.66%"} -->
                <div class="wp-block-column" style="flex-basis:66.66%"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical"}} -->
                    <div class="wp-block-group"><!-- wp:heading {"level":4} -->
                        <h4 class="wp-block-heading"><?php echo esc_html_e('WooCommerce Ready', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"fontSize":"normal"} -->
                        <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:column -->
            </div>
            <!-- /wp:columns -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"top","width":"50%","style":{"spacing":{"blockGap":"var:preset|spacing|60"}}} -->
        <div class="wp-block-column is-vertically-aligned-top" style="flex-basis:50%"><!-- wp:group {"style":{"spacing":{"margin":{"bottom":"70px"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
            <div class="wp-block-group" style="margin-bottom:70px"><!-- wp:paragraph {"align":"left","fontSize":"normal"} -->
                <p class="has-text-align-left has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"24px"}}}} -->
                <div class="wp-block-buttons" style="margin-top:24px"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"},"spacing":{"padding":{"left":"35px","right":"35px","top":"18px","bottom":"18px"}}},"className":"is-style-button-hover-white-bgcolor","fontSize":"normal"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-white-bgcolor has-normal-font-size"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:18px;padding-right:35px;padding-bottom:18px;padding-left:35px"><?php echo esc_html_e('Read More', 'walker-core') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->

            <!-- wp:image {"id":1302,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
            <figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-1302" style="border-radius:0px" /></figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->