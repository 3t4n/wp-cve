<?php

/**
 * Title: WNT Pro - Header with Ads Banner Top
 * Slug: walker-core/wnt-header-with-top-ads
 * Categories: wnt-patterns
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/patterns-media/header_banner_image.png',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"blockGap":"0"}},"className":"wnt-magazine-header","layout":{"type":"constrained","contentSize":"100%","justifyContent":"left"}} -->
<div class="wp-block-group wnt-magazine-header" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","right":"var:preset|spacing|60","left":"var:preset|spacing|60"}}},"layout":{"type":"constrained","contentSize":"1440px"}} -->
    <div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--60)"><!-- wp:image {"align":"center","id":447,"height":120,"sizeSlug":"full","linkDestination":"none"} -->
        <figure class="wp-block-image aligncenter size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-447" style="height:120px" height="120" /></figure>
        <!-- /wp:image -->
    </div>
    <!-- /wp:group -->

    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"1rem","right":"var:preset|spacing|60","left":"var:preset|spacing|60"}},"border":{"top":{"width":"0px","style":"none"},"bottom":{"color":"var:preset|color|background-alt","width":"1px"}}},"layout":{"type":"constrained","contentSize":"1440px"}} -->
    <div class="wp-block-group" style="border-top-style:none;border-top-width:0px;border-bottom-color:var(--wp--preset--color--background-alt);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--60);padding-bottom:1rem;padding-left:var(--wp--preset--spacing--60)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
        <div class="wp-block-group"><!-- wp:site-title {"textAlign":"left","style":{"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"700","letterSpacing":"2px","fontSize":"36px"},"elements":{"link":{"color":{"text":"var:preset|color|primary"}}}}} /-->

            <!-- wp:navigation {"textColor":"heading-color"} -->
            <!-- wp:page-list /-->
            <!-- /wp:navigation -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->