<?php

/**
 * Title: Blockpage PRO: Header Minimal with Logo Centered
 * Slug: blockpage/blockpage-header-logo-centered
 * Categories: blockpage-header, header
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20"}}},"layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--20);padding-bottom:var(--wp--preset--spacing--20)"><!-- wp:group {"style":{"spacing":{"padding":{"top":"25px","right":"var:preset|spacing|50","bottom":"25px","left":"var:preset|spacing|50"}},"border":{"bottom":{"width":"0px","style":"none"}}},"className":"blockpage-sticky-header","layout":{"type":"constrained","contentSize":"1280px"}} -->
    <div class="wp-block-group blockpage-sticky-header" style="border-bottom-style:none;border-bottom-width:0px;padding-top:25px;padding-right:var(--wp--preset--spacing--50);padding-bottom:25px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:columns -->
        <div class="wp-block-columns"><!-- wp:column {"width":"35%"} -->
            <div class="wp-block-column" style="flex-basis:35%"><!-- wp:navigation {"ref":1641,"textColor":"heading-color","overlayBackgroundColor":"secondary","overlayTextColor":"heading-alt","layout":{"type":"flex","justifyContent":"left"},"fontSize":"normal"} /--></div>
            <!-- /wp:column -->

            <!-- wp:column {"width":"30%"} -->
            <div class="wp-block-column" style="flex-basis:30%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
                <div class="wp-block-group"><!-- wp:site-logo {"width":40,"shouldSyncIcon":false} /-->

                    <!-- wp:site-title {"style":{"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"uppercase","letterSpacing":"3px","fontSize":"24px"},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"}}}}} /-->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"width":"35%"} -->
            <div class="wp-block-column" style="flex-basis:35%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
                <div class="wp-block-group"><!-- wp:woocommerce/customer-account {"displayStyle":"icon_only","iconStyle":"alt","iconClass":"wc-block-customer-account__account-icon","textColor":"heading-color"} /-->

                    <!-- wp:woocommerce/mini-cart {"hasHiddenPrice":true,"priceColor":"heading-color","priceColorValue":"#FFFFFF","iconColor":"heading-color","iconColorValue":"#FFFFFF","productCountColor":"primary","productCountColorValue":"#08B786"} /-->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->