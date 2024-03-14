<?php

/**
 * Title: Blockpage PRO: Content with Multiple Blocks style 2
 * Slug: walker-core/blockpage-content-multiple-col
 * Categories: blockpage-about
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/about_img.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/about_img_2.jpg',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"120px","bottom":"120px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="padding-top:120px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"constrained","contentSize":"760px"}} -->
    <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":5,"textColor":"primary","fontSize":"small"} -->
        <h5 class="wp-block-heading has-text-align-center has-primary-color has-text-color has-small-font-size"><?php echo esc_html_e('Our Story', 'walker-core') ?></h5>
        <!-- /wp:heading -->

        <!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","fontSize":"60px"}}} -->
        <h1 class="wp-block-heading has-text-align-center" style="font-size:60px;font-style:normal;font-weight:500"><?php echo esc_html_e('Aldus Corporation, which later merged Systems', 'walker-core') ?></h1>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"align":"center","fontSize":"normal"} -->
        <p class="has-text-align-center has-normal-font-size"><?php echo esc_html_e('The French lettering company&nbsp;Letraset&nbsp;manufactured a set of dry-transfer sheets which included the&nbsp;lorem<em> </em>ipsum&nbsp;filler text in a variety of fonts', 'walker-core') ?></p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"blockGap":{"top":"0","left":"100px"},"margin":{"top":"100px"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-top" style="margin-top:100px"><!-- wp:column {"verticalAlignment":"top","width":"50%","style":{"spacing":{"blockGap":"var:preset|spacing|60"}}} -->
        <div class="wp-block-column is-vertically-aligned-top" style="flex-basis:50%"><!-- wp:image {"id":1301,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"12px"}}} -->
            <figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-1301" style="border-radius:12px" /></figure>
            <!-- /wp:image -->

            <!-- wp:group {"style":{"spacing":{"margin":{"top":"60px"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
            <div class="wp-block-group" style="margin-top:60px"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","lineHeight":"1.3"}},"fontSize":"xxx-large"} -->
                <h1 class="wp-block-heading has-text-align-center has-xxx-large-font-size" style="font-style:normal;font-weight:500;line-height:1.3"><?php echo esc_html_e('Refining technology as business driven.', 'walker-core') ?></h1>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"align":"center","fontSize":"normal"} -->
                <p class="has-text-align-center has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"24px"}}}} -->
                <div class="wp-block-buttons" style="margin-top:24px"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"},"spacing":{"padding":{"left":"35px","right":"35px","top":"18px","bottom":"18px"}}},"className":"is-style-button-hover-white-bgcolor","fontSize":"normal"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-white-bgcolor has-normal-font-size"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:18px;padding-right:35px;padding-bottom:18px;padding-left:35px"><?php echo esc_html_e('Read More', 'walker-core') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"top","width":"50%","style":{"spacing":{"blockGap":"var:preset|spacing|60"}}} -->
        <div class="wp-block-column is-vertically-aligned-top" style="flex-basis:50%"><!-- wp:group {"style":{"spacing":{"margin":{"bottom":"70px"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
            <div class="wp-block-group" style="margin-bottom:70px"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","lineHeight":"1.3"}},"fontSize":"xxx-large"} -->
                <h1 class="wp-block-heading has-text-align-center has-xxx-large-font-size" style="font-style:normal;font-weight:500;line-height:1.3"><?php echo esc_html_e('Growth is the only facts that matters.', 'walker-core') ?></h1>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"align":"center","fontSize":"normal"} -->
                <p class="has-text-align-center has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"24px"}}}} -->
                <div class="wp-block-buttons" style="margin-top:24px"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"},"spacing":{"padding":{"left":"35px","right":"35px","top":"18px","bottom":"18px"}}},"className":"is-style-button-hover-white-bgcolor","fontSize":"normal"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-white-bgcolor has-normal-font-size"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:18px;padding-right:35px;padding-bottom:18px;padding-left:35px"><?php echo esc_html_e('Read More', 'walker-core') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->

            <!-- wp:image {"id":498,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"12px"}}} -->
            <figure class="wp-block-image size-full has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-498" style="border-radius:12px" /></figure>
            <!-- /wp:image -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->