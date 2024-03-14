<?php

/**
 * Title: Blockpage PRO: Testimonial Block Style 2
 * Slug: walker-core/blockpage-testimonial-block-2
 * Categories: blockpage-testimonial
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/testimonial_1.png',
    WALKER_CORE_URL . 'admin/images/blockpage/testimonial_2.png',
    WALKER_CORE_URL . 'admin/images/blockpage/testimonial_3.png',
    WALKER_CORE_URL . 'admin/images/blockpage/star_rating.png',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"120px","bottom":"120px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:120px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:columns {"verticalAlignment":"bottom"} -->
    <div class="wp-block-columns are-vertically-aligned-bottom"><!-- wp:column {"verticalAlignment":"bottom","width":"66.66%"} -->
        <div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:66.66%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":""}} -->
            <div class="wp-block-group"><!-- wp:heading {"textAlign":"left","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"blockpage-heading","fontSize":"xxx-large"} -->
                <h1 class="wp-block-heading has-text-align-left blockpage-heading has-xxx-large-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('What Our Client Says', 'walker-core') ?></h1>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"align":"left","style":{"spacing":{"margin":{"top":"32px"}},"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
                <p class="has-text-align-left has-normal-font-size" style="margin-top:32px;line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"bottom","width":"33.33%"} -->
        <div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:33.33%"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
            <div class="wp-block-group"><!-- wp:buttons -->
                <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"spacing":{"padding":{"left":"35px","right":"35px","top":"18px","bottom":"18px"}},"border":{"radius":"60px"}},"className":"is-style-button-hover-white-bgcolor"} -->
                    <div class="wp-block-button is-style-button-hover-white-bgcolor"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:18px;padding-right:35px;padding-bottom:18px;padding-left:35px"><?php echo esc_html_e('All Reviews', 'walker-core') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:columns {"style":{"spacing":{"margin":{"top":"74px"},"blockGap":{"left":"40px"}}}} -->
    <div class="wp-block-columns" style="margin-top:74px"><!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}},"border":{"radius":"5px"}},"backgroundColor":"background-alt","layout":{"type":"constrained"}} -->
            <div class="wp-block-group has-background-alt-background-color has-background" style="border-radius:5px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px"><!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"32px"}},"typography":{"lineHeight":"1.5"}},"textColor":"foreground","fontSize":"normal"} -->
                <p class="has-foreground-color has-text-color has-normal-font-size" style="margin-bottom:32px;line-height:1.5"><?php echo esc_html_e('Just woW! Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:image {"id":218,"width":"80px","height":"16px","scale":"contain","sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[3]) ?>" alt="" class="wp-image-218" style="object-fit:contain;width:80px;height:16px" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group"><!-- wp:image {"id":222,"width":"60px","height":"undefinedpx","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"60px"}}} -->
                    <figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-222" style="border-radius:60px;width:60px;height:undefinedpx" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                    <div class="wp-block-group"><!-- wp:heading {"level":5,"fontSize":"large"} -->
                        <h5 class="wp-block-heading has-large-font-size"><?php echo esc_html_e('George Pento', 'walker-core') ?></h5>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph -->
                        <p><?php echo esc_html_e('Writer', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}},"border":{"radius":"5px"}},"backgroundColor":"background-alt","layout":{"type":"constrained"}} -->
            <div class="wp-block-group has-background-alt-background-color has-background" style="border-radius:5px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px"><!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"32px"}},"typography":{"lineHeight":"1.5"}},"textColor":"foreground","fontSize":"normal"} -->
                <p class="has-foreground-color has-text-color has-normal-font-size" style="margin-bottom:32px;line-height:1.5"><?php echo esc_html_e('Just woW! Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:image {"id":218,"width":"80px","height":"16px","scale":"contain","sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[3]) ?>" alt="" class="wp-image-218" style="object-fit:contain;width:80px;height:16px" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group"><!-- wp:image {"id":222,"width":"60px","height":"undefinedpx","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"60px"}}} -->
                    <figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-222" style="border-radius:60px;width:60px;height:undefinedpx" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                    <div class="wp-block-group"><!-- wp:heading {"level":5,"fontSize":"large"} -->
                        <h5 class="wp-block-heading has-large-font-size"><?php echo esc_html_e('George Pento', 'walker-core') ?></h5>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph -->
                        <p><?php echo esc_html_e('Writer', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}},"border":{"radius":"5px"}},"backgroundColor":"background-alt","layout":{"type":"constrained"}} -->
            <div class="wp-block-group has-background-alt-background-color has-background" style="border-radius:5px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px"><!-- wp:paragraph {"style":{"spacing":{"margin":{"bottom":"32px"}},"typography":{"lineHeight":"1.5"}},"textColor":"foreground","fontSize":"normal"} -->
                <p class="has-foreground-color has-text-color has-normal-font-size" style="margin-bottom:32px;line-height:1.5"><?php echo esc_html_e('Just woW! Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:image {"id":218,"width":"80px","height":"16px","scale":"contain","sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[3]) ?>" alt="" class="wp-image-218" style="object-fit:contain;width:80px;height:16px" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group"><!-- wp:image {"id":222,"width":"60px","height":"undefinedpx","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"60px"}}} -->
                    <figure class="wp-block-image size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[2]) ?>" alt="" class="wp-image-222" style="border-radius:60px;width:60px;height:undefinedpx" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical"}} -->
                    <div class="wp-block-group"><!-- wp:heading {"level":5,"fontSize":"large"} -->
                        <h5 class="wp-block-heading has-large-font-size"><?php echo esc_html_e('George Pento', 'walker-core') ?></h5>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph -->
                        <p><?php echo esc_html_e('Writer', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->
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