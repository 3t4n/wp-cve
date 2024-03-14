<?php

/**
 * Title: Blockpage PRO: Header Minimal with Mini Cart Icon
 * Slug: blockpage/blockpage-header-with-minicart
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

            <!-- wp:navigation {"ref":1641,"textColor":"heading-color","overlayBackgroundColor":"secondary","overlayTextColor":"heading-alt","layout":{"type":"flex","justifyContent":"center"},"fontSize":"normal"} /-->

            <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
            <div class="wp-block-group"><!-- wp:search {"label":"Search","showLabel":false,"buttonText":"Search","buttonPosition":"button-only","buttonUseIcon":true,"style":{"color":{"background":"#ffffff00"},"border":{"radius":"60px","width":"0px","style":"none"}},"textColor":"heading-color","className":"blockpage-search-style-2","fontSize":"normal"} /-->

                <!-- wp:woocommerce/customer-account {"displayStyle":"icon_only","iconStyle":"alt","iconClass":"wc-block-customer-account__account-icon","textColor":"heading-color"} /-->

                <!-- wp:woocommerce/mini-cart {"hasHiddenPrice":true,"priceColor":"heading-color","priceColorValue":"#FFFFFF","iconColor":"heading-color","iconColorValue":"#FFFFFF","productCountColor":"primary","productCountColorValue":"#08B786"} /-->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->