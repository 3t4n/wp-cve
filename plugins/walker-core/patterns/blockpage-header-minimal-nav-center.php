<?php

/**
 * Title: Blockpage PRO: Header Minimal with Nav Center
 * Slug: blockpage/blockpage-header-minimal-nav-center
 * Categories: blockpage-header, header
 */
?>
<!-- wp:group {"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"padding":{"top":"25px","right":"var:preset|spacing|50","bottom":"25px","left":"var:preset|spacing|50"}},"border":{"bottom":{"width":"0px","style":"none"}}},"className":"blockpage-sticky-header","layout":{"type":"constrained","contentSize":"1280px"}} -->
    <div class="wp-block-group blockpage-sticky-header" style="border-bottom-style:none;border-bottom-width:0px;padding-top:25px;padding-right:var(--wp--preset--spacing--50);padding-bottom:25px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
        <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group"><!-- wp:site-logo {"width":40,"shouldSyncIcon":false} /-->

                <!-- wp:site-title {"style":{"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"uppercase","letterSpacing":"3px","fontSize":"24px"},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"}}}}} /-->
            </div>
            <!-- /wp:group -->

            <!-- wp:navigation {"textColor":"heading-color","overlayBackgroundColor":"secondary","overlayTextColor":"heading-alt","layout":{"type":"flex","justifyContent":"center"},"fontSize":"normal"} -->
            <!-- wp:page-list /-->
            <!-- /wp:navigation -->

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