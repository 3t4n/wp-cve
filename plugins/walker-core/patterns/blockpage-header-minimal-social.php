<?php

/**
 * Title: Blockpage PRO: Header Minimal with Social Icon
 * Slug: blockpage/blockpage-header-minimal-social
 * Categories: blockpage-header, header
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)"><!-- wp:group {"style":{"spacing":{"padding":{"top":"25px","right":"var:preset|spacing|50","bottom":"25px","left":"var:preset|spacing|50"}},"border":{"bottom":{"width":"0px","style":"none"}}},"className":"blockpage-sticky-header","layout":{"type":"constrained","contentSize":"1280px"}} -->
    <div class="wp-block-group blockpage-sticky-header" style="border-bottom-style:none;border-bottom-width:0px;padding-top:25px;padding-right:var(--wp--preset--spacing--50);padding-bottom:25px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
        <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group"><!-- wp:site-logo {"width":40,"shouldSyncIcon":false} /-->

                <!-- wp:site-title {"style":{"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"uppercase","letterSpacing":"3px","fontSize":"24px"},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"}}}}} /-->
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group"><!-- wp:navigation {"textColor":"heading-color","overlayBackgroundColor":"secondary","overlayTextColor":"heading-alt","layout":{"type":"flex","justifyContent":"center"},"fontSize":"normal"} -->
                <!-- wp:page-list /-->
                <!-- /wp:navigation -->

                <!-- wp:social-links {"iconColor":"heading-color","iconColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}},"className":"is-style-logos-only blockpage-socials"} -->
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
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->