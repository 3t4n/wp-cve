<?php

/**
 * Title: Blockpage PRO: Header witn Info CTA Buttons
 * Slug: blockpage/blockpage-header-with-info-cta
 * Categories: blockpage-header, header
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/icon_location.png',
    WALKER_CORE_URL . 'admin/images/blockpage/icon_phone.png',
);
?>
<!-- wp:group {"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","right":"var:preset|spacing|50","bottom":"40px","left":"var:preset|spacing|50"}},"border":{"bottom":{"width":"0px","style":"none"}}},"className":"blockpage-sticky-header","layout":{"type":"constrained","contentSize":"1280px"}} -->
    <div class="wp-block-group blockpage-sticky-header" style="border-bottom-style:none;border-bottom-width:0px;padding-top:40px;padding-right:var(--wp--preset--spacing--50);padding-bottom:40px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
        <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group"><!-- wp:site-logo {"width":40,"shouldSyncIcon":false} /-->

                <!-- wp:site-title {"style":{"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"uppercase","letterSpacing":"3px","fontSize":"24px"},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"}}}}} /-->
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"15px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group"><!-- wp:image {"id":1712,"width":"34px","height":"34px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-1712" style="object-fit:cover;width:34px;height:34px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:list {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"textColor":"heading-color","className":"is-style-hide-bullet-list-link-hover-style-primary"} -->
                    <ul class="is-style-hide-bullet-list-link-hover-style-primary has-heading-color-color has-text-color" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:list-item {"style":{"typography":{"lineHeight":"1.5"}}} -->
                        <li style="line-height:1.5"><?php echo esc_html_e('Metrotech Center, Brooklyn, <br>NY 11201, USA', 'walker-core') ?></li>
                        <!-- /wp:list-item -->
                    </ul>
                    <!-- /wp:list -->
                </div>
                <!-- /wp:group -->

                <!-- wp:group {"style":{"spacing":{"blockGap":"15px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group"><!-- wp:image {"id":1714,"width":"34px","height":"34px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-1714" style="object-fit:cover;width:34px;height:34px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:list {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"textColor":"heading-color","className":"is-style-hide-bullet-list-link-hover-style-primary"} -->
                    <ul class="is-style-hide-bullet-list-link-hover-style-primary has-heading-color-color has-text-color" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:list-item {"fontSize":"medium"} -->
                        <li class="has-medium-font-size"><?php echo esc_html_e('+1 (012) 345-6789', 'walker-core') ?></li>
                        <!-- /wp:list-item -->
                    </ul>
                    <!-- /wp:list -->
                </div>
                <!-- /wp:group -->

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

    <!-- wp:group {"style":{"spacing":{"padding":{"top":"15px","right":"var:preset|spacing|50","bottom":"15px","left":"var:preset|spacing|50"},"margin":{"top":"0","bottom":"0"}},"border":{"bottom":{"width":"0px","style":"none"}}},"backgroundColor":"background-alt","className":"blockpage-sticky-header","layout":{"type":"constrained","contentSize":"1280px"}} -->
    <div class="wp-block-group blockpage-sticky-header has-background-alt-background-color has-background" style="border-bottom-style:none;border-bottom-width:0px;margin-top:0;margin-bottom:0;padding-top:15px;padding-right:var(--wp--preset--spacing--50);padding-bottom:15px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"left"}} -->
        <div class="wp-block-group"><!-- wp:navigation {"textColor":"heading-color","overlayBackgroundColor":"secondary","overlayTextColor":"heading-alt","layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"blockGap":"30px"}},"fontSize":"normal"} -->
            <!-- wp:page-list /-->
            <!-- /wp:navigation -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->