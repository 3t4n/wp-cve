<?php

/**
 * Title: Blockpage PRO: Header with multiple header
 * Slug: blockpage/blockpage-header-with-multiple-buttons
 * Categories: blockpage-header, header
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/icon_map.png',
    WALKER_CORE_URL . 'admin/images/blockpage/icon_call.png',
    WALKER_CORE_URL . 'admin/images/blockpage/icon_msg.png',
);
?>
<!-- wp:group {"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"padding":{"top":"13px","bottom":"13px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"backgroundColor":"background-alt","layout":{"type":"constrained","contentSize":"1280px"}} -->
    <div class="wp-block-group has-background-alt-background-color has-background" style="padding-top:13px;padding-right:var(--wp--preset--spacing--50);padding-bottom:13px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
        <div class="wp-block-group"><!-- wp:group {"className":"blockpage-topbar-links","layout":{"type":"flex","flexWrap":"wrap"}} -->
            <div class="wp-block-group blockpage-topbar-links"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group"><!-- wp:image {"id":43,"width":"22px","height":"16px","scale":"contain","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-43" style="object-fit:contain;width:22px;height:16px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:list {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"className":"is-style-list-style-no-bullet"} -->
                    <ul class="is-style-list-style-no-bullet" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:list-item {"fontSize":"x-small"} -->
                        <li class="has-x-small-font-size"><a href="#"><?php echo esc_html_e('Metrotech Center, Brooklyn, NY 11201, USA', 'walker-core') ?></a></li>
                        <!-- /wp:list-item -->
                    </ul>
                    <!-- /wp:list -->
                </div>
                <!-- /wp:group -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group"><!-- wp:image {"id":46,"width":"22px","height":"16px","scale":"contain","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-46" style="object-fit:contain;width:22px;height:16px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:list {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"className":"is-style-list-style-no-bullet"} -->
                    <ul class="is-style-list-style-no-bullet" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:list-item {"fontSize":"x-small"} -->
                        <li class="has-x-small-font-size"><a href="#"><?php echo esc_html_e('+1 (012) 345-6789', 'walker-core') ?></a></li>
                        <!-- /wp:list-item -->
                    </ul>
                    <!-- /wp:list -->
                </div>
                <!-- /wp:group -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group"><!-- wp:image {"id":49,"width":"18px","height":"20px","scale":"contain","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[2]) ?>" alt="" class="wp-image-49" style="object-fit:contain;width:18px;height:20px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:list {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"className":"is-style-list-style-no-bullet"} -->
                    <ul class="is-style-list-style-no-bullet" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:list-item {"fontSize":"small"} -->
                        <li class="has-small-font-size"><a href="#"><?php echo esc_html_e('email@example.com', 'walker-core') ?></a></li>
                        <!-- /wp:list-item -->
                    </ul>
                    <!-- /wp:list -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->

            <!-- wp:social-links {"iconColor":"foreground","iconColorValue":"#c3c2c2","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}},"className":"is-style-logos-only blockpage-socials"} -->
            <ul class="wp-block-social-links has-icon-color is-style-logos-only blockpage-socials"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

                <!-- wp:social-link {"url":"#","service":"instagram"} /-->

                <!-- wp:social-link {"url":"#","service":"twitter"} /-->

                <!-- wp:social-link {"url":"#","service":"dribbble"} /-->
            </ul>
            <!-- /wp:social-links -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->

    <!-- wp:group {"style":{"spacing":{"padding":{"top":"20px","right":"var:preset|spacing|50","bottom":"20px","left":"var:preset|spacing|50"}},"border":{"bottom":{"width":"0px","style":"none"}}},"className":"blockpage-sticky-header","layout":{"type":"constrained","contentSize":"1280px"}} -->
    <div class="wp-block-group blockpage-sticky-header" style="border-bottom-style:none;border-bottom-width:0px;padding-top:20px;padding-right:var(--wp--preset--spacing--50);padding-bottom:20px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
        <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group"><!-- wp:site-logo {"width":40,"shouldSyncIcon":false} /-->

                <!-- wp:site-title {"style":{"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"uppercase","letterSpacing":"3px","fontSize":"24px"},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"}}}}} /-->
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"right","flexWrap":"wrap"}} -->
                <div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"heading-color","textColor":"background","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|60","bottom":"var:preset|spacing|40","left":"var:preset|spacing|60"}},"border":{"radius":"64px"},"typography":{"fontSize":"18px"}},"className":"is-style-button-hover-primary-color"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-primary-color" style="font-size:18px"><a class="wp-block-button__link has-background-color has-heading-color-background-color has-text-color has-background wp-element-button" style="border-radius:64px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--60)"><?php echo esc_html_e('Schedule an Appointment', 'walker-core') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->

                <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"right","flexWrap":"wrap"}} -->
                <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"spacing":{"padding":{"top":"var:preset|spacing|40","right":"var:preset|spacing|60","bottom":"var:preset|spacing|40","left":"var:preset|spacing|60"}},"border":{"radius":"64px"},"typography":{"fontSize":"18px"}},"className":"is-style-button-hover-white-bgcolor"} -->
                    <div class="wp-block-button has-custom-font-size is-style-button-hover-white-bgcolor" style="font-size:18px"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:64px;padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--60)"><?php echo esc_html_e('Request a Quote', 'walker-core') ?></a></div>
                    <!-- /wp:button -->
                </div>
                <!-- /wp:buttons -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->

    <!-- wp:group {"style":{"spacing":{"padding":{"top":"15px","right":"var:preset|spacing|50","bottom":"15px","left":"var:preset|spacing|50"}},"border":{"bottom":{"width":"0px","style":"none"}}},"backgroundColor":"background-alt","className":"blockpage-sticky-header","layout":{"type":"constrained","contentSize":"1280px"}} -->
    <div class="wp-block-group blockpage-sticky-header has-background-alt-background-color has-background" style="border-bottom-style:none;border-bottom-width:0px;padding-top:15px;padding-right:var(--wp--preset--spacing--50);padding-bottom:15px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left"}} -->
        <div class="wp-block-group">
            <!-- wp:navigation {"textColor":"heading-color","overlayBackgroundColor":"secondary","overlayTextColor":"heading-alt","layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"blockGap":"30px"}},"fontSize":"normal"} -->
            <!-- wp:page-list /-->
            <!-- /wp:navigation -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->