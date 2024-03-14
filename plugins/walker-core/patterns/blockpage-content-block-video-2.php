<?php

/**
 * Title: Blockpage PRO: Content Block with Video style 2
 * Slug: walker-core/blockpage-content-block-video-2
 * Categories: blockpage-about
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/media_video.mp4',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"right":"0","left":"0","top":"120px","bottom":"120px"}}},"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group" style="padding-top:120px;padding-right:0;padding-bottom:120px;padding-left:0"><!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
    <div class="wp-block-group" style="padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"margin":{"bottom":"51px"},"blockGap":{"left":"64px"}}}} -->
        <div class="wp-block-columns are-vertically-aligned-top" style="margin-bottom:51px"><!-- wp:column {"verticalAlignment":"top","width":"35%","style":{"spacing":{"padding":{"right":"0","left":"0"}}}} -->
            <div class="wp-block-column is-vertically-aligned-top" style="padding-right:0;padding-left:0;flex-basis:35%"><!-- wp:heading {"level":5,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"primary","className":"blockpage-subheader","fontSize":"normal"} -->
                <h5 class="wp-block-heading blockpage-subheader has-primary-color has-text-color has-normal-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Our Story', 'walker-core') ?></h5>
                <!-- /wp:heading -->

                <!-- wp:heading {"level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","fontSize":"44px","lineHeight":"1.4"}}} -->
                <h1 class="wp-block-heading" style="font-size:44px;font-style:normal;font-weight:500;line-height:1.4"><?php echo esc_html_e('Legacy does not build in overnight.', 'walker-core') ?></h1>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"verticalAlignment":"top","width":"65%","style":{"spacing":{"blockGap":"var:preset|spacing|70"}}} -->
            <div class="wp-block-column is-vertically-aligned-top" style="flex-basis:65%"><!-- wp:video {"id":1364} -->
                <figure class="wp-block-video"><video controls loop src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" playsinline></video></figure>
                <!-- /wp:video -->

                <!-- wp:paragraph {"style":{"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
                <p class="has-normal-font-size" style="line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"40px"}}}} -->
                <div class="wp-block-buttons" style="margin-top:40px"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"},"spacing":{"padding":{"left":"40px","right":"40px","top":"18px","bottom":"18px"}}},"className":"is-style-button-hover-white-bgcolor","fontSize":"normal"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-white-bgcolor has-normal-font-size"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:18px;padding-right:40px;padding-bottom:18px;padding-left:40px"><?php echo esc_html_e('Read More', 'walker-core') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->