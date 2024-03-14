<?php

/**
 * Title: Blockpage PRO: Banner Layout with large Image and Text Centered
 * Slug: walker-core/blockpage-banner-large-text-centered
 * Categories: blockpage-banner, banner
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/banner_img.jpg',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"120px"}}},"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group" style="padding-top:120px"><!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"},"margin":{"bottom":"60px"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
    <div class="wp-block-group" style="margin-bottom:60px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"style":{"spacing":{"padding":{"bottom":"64px"}}},"layout":{"type":"constrained","contentSize":"860px"}} -->
        <div class="wp-block-group" style="padding-bottom:64px"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","lineHeight":"1.5","fontSize":"60px"}}} -->
            <h1 class="wp-block-heading has-text-align-center" style="font-size:60px;font-style:normal;font-weight:500;line-height:1.5"><?php echo esc_html_e('Delivering Cutting-edge Technology', 'walekr-core') ?></h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"normal"} -->
            <p class="has-text-align-center has-normal-font-size" style="margin-top:24px"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor.', 'walekr-core') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"52px"}}}} -->
            <div class="wp-block-buttons" style="margin-top:52px"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"},"spacing":{"padding":{"left":"35px","right":"35px","top":"20px","bottom":"20px"}}},"className":"is-style-button-hover-white-bgcolor","fontSize":"normal"} -->
                <div class="wp-block-button has-custom-font-size is-style-button-hover-white-bgcolor has-normal-font-size"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:20px;padding-right:35px;padding-bottom:20px;padding-left:35px"><?php echo esc_html_e('Schedule Quick Call', 'walekr-core') ?></a></div>
                <!-- /wp:button -->

                <!-- wp:button {"backgroundColor":"heading-color","textColor":"background","style":{"border":{"radius":"60px"},"spacing":{"padding":{"left":"35px","right":"35px","top":"20px","bottom":"20px"}}},"className":"is-style-button-hover-primary-color","fontSize":"normal"} -->
                <div class="wp-block-button has-custom-font-size is-style-button-hover-primary-color has-normal-font-size"><a class="wp-block-button__link has-background-color has-heading-color-background-color has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:20px;padding-right:35px;padding-bottom:20px;padding-left:35px"><?php echo esc_html_e('Schedule Quick Call', 'walekr-core') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:group -->

        <!-- wp:image {"align":"wide","id":1230,"sizeSlug":"large","linkDestination":"none"} -->
        <figure class="wp-block-image alignwide size-large"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-1230" /></figure>
        <!-- /wp:image -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->