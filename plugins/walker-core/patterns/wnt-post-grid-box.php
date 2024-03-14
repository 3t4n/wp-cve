<?php

/**
 * Title: WNT Pro - Grid Box
 * Slug: walker-core/wnt-post-grid-box
 * Categories: wnt-patterns
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1440px"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"x-large"} -->
    <h3 class="wp-block-heading has-x-large-font-size" style="font-style:normal;font-weight:600">Trending News</h3>
    <!-- /wp:heading -->

    <!-- wp:query {"queryId":33,"query":{"perPage":"9","pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
    <div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
        <!-- wp:group {"layout":{"type":"constrained"}} -->
        <div class="wp-block-group"><!-- wp:columns {"verticalAlignment":"center"} -->
            <div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"27%"} -->
                <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:27%"><!-- wp:post-featured-image {"isLink":true,"height":"100px","align":"wide","style":{"border":{"radius":"10px"}}} /--></div>
                <!-- /wp:column -->

                <!-- wp:column {"verticalAlignment":"center","width":"72%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
                <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:72%"><!-- wp:post-title {"isLink":true,"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"600","lineHeight":1.6},"elements":{"link":{"color":{"text":"var:preset|color|dark-heading-color"}}}},"className":"is-style-title-hover-primary-color"} /-->

                    <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group"><!-- wp:post-date /--></div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:column -->
            </div>
            <!-- /wp:columns -->
        </div>
        <!-- /wp:group -->
        <!-- /wp:post-template -->
    </div>
    <!-- /wp:query -->
</div>
<!-- /wp:group -->