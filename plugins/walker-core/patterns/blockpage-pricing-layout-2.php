<?php

/**
 * Title: Blockpage PRO: Pricing Tables Layout 2
 * Slug: walker-core/blockpage-pricing-layout-2
 * Categories: blockpage-pricing
 */

$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/icon_check.png',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"120px","bottom":"60px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:120px;padding-right:var(--wp--preset--spacing--50);padding-bottom:60px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"24px"},"margin":{"top":"80px"}}}} -->
    <div class="wp-block-columns" style="margin-top:80px"><!-- wp:column {"style":{"spacing":{"blockGap":"0"}}} -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"700px"}} -->
            <div class="wp-block-group"><!-- wp:heading {"textAlign":"left","level":5,"textColor":"primary"} -->
                <h5 class="wp-block-heading has-text-align-left has-primary-color has-text-color"><?php echo esc_html_e('Pricing &amp; Plans', 'walker-core') ?></h5>
                <!-- /wp:heading -->

                <!-- wp:heading {"textAlign":"left","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500","lineHeight":"1.4"},"spacing":{"margin":{"top":"24px"}}},"className":"blockpage-heading","fontSize":"xxx-large"} -->
                <h1 class="wp-block-heading has-text-align-left blockpage-heading has-xxx-large-font-size" style="margin-top:24px;font-style:normal;font-weight:500;line-height:1.4"><?php echo esc_html_e('Choose the appropriate pricing for your business.', 'walker-core') ?></h1>
                <!-- /wp:heading -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"style":{"spacing":{"blockGap":"0"}}} -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"50px","bottom":"50px","left":"50px","right":"50px"},"blockGap":"var:preset|spacing|40"},"border":{"radius":"5px","width":"0px","style":"none"}},"backgroundColor":"background-alt","className":"blockpage-pricing-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-pricing-box has-background-alt-background-color has-background" style="border-style:none;border-width:0px;border-radius:5px;padding-top:50px;padding-right:50px;padding-bottom:50px;padding-left:50px"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"bottom"}} -->
                <div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"fontSize":"48px","lineHeight":"0.8","fontStyle":"normal","fontWeight":"500"}}} -->
                    <h1 class="wp-block-heading" style="font-size:48px;font-style:normal;font-weight:500;line-height:0.8"><?php echo esc_html_e('$49', 'walker-core') ?></h1>
                    <!-- /wp:heading -->

                    <!-- wp:heading {"level":6} -->
                    <h6 class="wp-block-heading"><?php echo esc_html_e('/per year', 'walker-core') ?></h6>
                    <!-- /wp:heading -->
                </div>
                <!-- /wp:group -->

                <!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"52px"}}},"fontSize":"large"} -->
                <h3 class="wp-block-heading has-large-font-size" style="margin-top:52px"><?php echo esc_html_e('Starter Package', 'walker-core') ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"fontSize":"small"} -->
                <p class="has-small-font-size"><?php echo esc_html_e('Check out our new font generator and level up your pricing and plans as requirement.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:group {"style":{"spacing":{"margin":{"top":"40px"},"padding":{"top":"32px"}},"border":{"top":{"color":"var:preset|color|border-color","width":"1px"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
                <div class="wp-block-group" style="border-top-color:var(--wp--preset--color--border-color);border-top-width:1px;margin-top:40px;padding-top:32px"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group"><!-- wp:image {"id":333,"aspectRatio":"1","scale":"contain","sizeSlug":"full","linkDestination":"none","style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
                        <figure class="wp-block-image size-full"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-333" style="aspect-ratio:1;object-fit:contain" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":6} -->
                        <h6 class="wp-block-heading"><?php echo esc_html_e('24/7 Supports', 'walker-core') ?></h6>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group"><!-- wp:image {"id":333,"aspectRatio":"1","scale":"contain","sizeSlug":"full","linkDestination":"none","style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
                        <figure class="wp-block-image size-full"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-333" style="aspect-ratio:1;object-fit:contain" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":6} -->
                        <h6 class="wp-block-heading"><?php echo esc_html_e('Social Media Management', 'walker-core') ?></h6>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group"><!-- wp:image {"id":333,"aspectRatio":"1","scale":"contain","sizeSlug":"full","linkDestination":"none","style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
                        <figure class="wp-block-image size-full"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-333" style="aspect-ratio:1;object-fit:contain" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":6} -->
                        <h6 class="wp-block-heading"><?php echo esc_html_e('Content and Seo Strategy', 'walker-core') ?></h6>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group"><!-- wp:image {"id":333,"aspectRatio":"1","scale":"contain","sizeSlug":"full","linkDestination":"none","style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
                        <figure class="wp-block-image size-full"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-333" style="aspect-ratio:1;object-fit:contain" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":6} -->
                        <h6 class="wp-block-heading"><?php echo esc_html_e('Branding Books', 'walker-core') ?></h6>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group"><!-- wp:image {"id":333,"aspectRatio":"1","scale":"contain","sizeSlug":"full","linkDestination":"none","style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
                        <figure class="wp-block-image size-full"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-333" style="aspect-ratio:1;object-fit:contain" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":6} -->
                        <h6 class="wp-block-heading"><?php echo esc_html_e('Visual Identity', 'walker-core') ?></h6>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->

                <!-- wp:buttons {"align":"full","layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"42px","bottom":"0px"}}}} -->
                <div class="wp-block-buttons alignfull" style="margin-top:42px;margin-bottom:0px"><!-- wp:button {"textColor":"heading-color","width":100,"style":{"spacing":{"padding":{"left":"var:preset|spacing|60","right":"var:preset|spacing|60","top":"19px","bottom":"19px"}},"border":{"width":"1px","color":"#FFFFFF","radius":"50px"},"color":{"background":"#ffffff00"}},"className":"is-style-button-hover-white-bgcolor blockpage-pricing-buttons","fontSize":"medium"} -->
                    <div class="wp-block-button has-custom-width wp-block-button__width-100 has-custom-font-size is-style-button-hover-white-bgcolor blockpage-pricing-buttons has-medium-font-size"><a class="wp-block-button__link has-heading-color-color has-text-color has-background has-border-color wp-element-button" style="border-color:#FFFFFF;border-width:1px;border-radius:50px;background-color:#ffffff00;padding-top:19px;padding-right:var(--wp--preset--spacing--60);padding-bottom:19px;padding-left:var(--wp--preset--spacing--60)">Get Started', 'walker-core') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"style":{"spacing":{"blockGap":"0"}}} -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"50px","bottom":"50px","left":"50px","right":"50px"},"blockGap":"var:preset|spacing|40"},"border":{"radius":"5px","width":"0px","style":"none"}},"backgroundColor":"background-alt","className":"blockpage-pricing-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-pricing-box has-background-alt-background-color has-background" style="border-style:none;border-width:0px;border-radius:5px;padding-top:50px;padding-right:50px;padding-bottom:50px;padding-left:50px"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"bottom"}} -->
                <div class="wp-block-group"><!-- wp:heading {"level":1,"style":{"typography":{"fontSize":"48px","lineHeight":"0.8","fontStyle":"normal","fontWeight":"500"}}} -->
                    <h1 class="wp-block-heading" style="font-size:48px;font-style:normal;font-weight:500;line-height:0.8"><?php echo esc_html_e('$149', 'walker-core') ?></h1>
                    <!-- /wp:heading -->

                    <!-- wp:heading {"level":6} -->
                    <h6 class="wp-block-heading"><?php echo esc_html_e('/per year', 'walker-core') ?></h6>
                    <!-- /wp:heading -->
                </div>
                <!-- /wp:group -->

                <!-- wp:heading {"level":3,"style":{"spacing":{"margin":{"top":"52px"}}},"fontSize":"large"} -->
                <h3 class="wp-block-heading has-large-font-size" style="margin-top:52px"><?php echo esc_html_e('Business Package', 'walker-core') ?></h3>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"fontSize":"small"} -->
                <p class="has-small-font-size"><?php echo esc_html_e('Check out our new font generator and level up your pricing and plans as your requirement.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:group {"style":{"spacing":{"margin":{"top":"40px"},"padding":{"top":"32px"}},"border":{"top":{"color":"var:preset|color|border-color","width":"1px"}}},"layout":{"type":"flex","orientation":"vertical"}} -->
                <div class="wp-block-group" style="border-top-color:var(--wp--preset--color--border-color);border-top-width:1px;margin-top:40px;padding-top:32px"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group"><!-- wp:image {"id":333,"aspectRatio":"1","scale":"contain","sizeSlug":"full","linkDestination":"none","style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
                        <figure class="wp-block-image size-full"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-333" style="aspect-ratio:1;object-fit:contain" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":6} -->
                        <h6 class="wp-block-heading"> <?php echo esc_html_e('24/7 Supports', 'walker-core') ?></h6>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group"><!-- wp:image {"id":333,"aspectRatio":"1","scale":"contain","sizeSlug":"full","linkDestination":"none","style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
                        <figure class="wp-block-image size-full"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-333" style="aspect-ratio:1;object-fit:contain" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":6} -->
                        <h6 class="wp-block-heading"><?php echo esc_html_e('Social Media Management', 'walker-core') ?></h6>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group"><!-- wp:image {"id":333,"aspectRatio":"1","scale":"contain","sizeSlug":"full","linkDestination":"none","style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
                        <figure class="wp-block-image size-full"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-333" style="aspect-ratio:1;object-fit:contain" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":6} -->
                        <h6 class="wp-block-heading"><?php echo esc_html_e('Content and Seo Strategy', 'walker-core') ?></h6>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group"><!-- wp:image {"id":333,"aspectRatio":"1","scale":"contain","sizeSlug":"full","linkDestination":"none","style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
                        <figure class="wp-block-image size-full"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-333" style="aspect-ratio:1;object-fit:contain" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":6} -->
                        <h6 class="wp-block-heading"><?php echo esc_html_e('Branding Books', 'walker-core') ?></h6>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group"><!-- wp:image {"id":333,"aspectRatio":"1","scale":"contain","sizeSlug":"full","linkDestination":"none","style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
                        <figure class="wp-block-image size-full"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-333" style="aspect-ratio:1;object-fit:contain" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":6} -->
                        <h6 class="wp-block-heading"><?php echo esc_html_e('Visual Identity', 'walker-core') ?></h6>
                        <!-- /wp:heading -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->

                <!-- wp:buttons {"align":"full","layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"42px","bottom":"0px"}}}} -->
                <div class="wp-block-buttons alignfull" style="margin-top:42px;margin-bottom:0px"><!-- wp:button {"textColor":"heading-color","width":100,"style":{"spacing":{"padding":{"left":"var:preset|spacing|60","right":"var:preset|spacing|60","top":"19px","bottom":"19px"}},"border":{"width":"1px","color":"#FFFFFF","radius":"50px"},"color":{"background":"#ffffff00"}},"className":"is-style-button-hover-white-bgcolor blockpage-pricing-buttons","fontSize":"medium"} -->
                    <div class="wp-block-button has-custom-width wp-block-button__width-100 has-custom-font-size is-style-button-hover-white-bgcolor blockpage-pricing-buttons has-medium-font-size"><a class="wp-block-button__link has-heading-color-color has-text-color has-background has-border-color wp-element-button" style="border-color:#FFFFFF;border-width:1px;border-radius:50px;background-color:#ffffff00;padding-top:19px;padding-right:var(--wp--preset--spacing--60);padding-bottom:19px;padding-left:var(--wp--preset--spacing--60)">Get Started', 'walker-core') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->