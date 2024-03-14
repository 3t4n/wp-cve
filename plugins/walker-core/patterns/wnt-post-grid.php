<?php

/**
 * Title: WNT Pro - Grid Layout
 * Slug: walker-core/wnt-post-grid
 * Categories: wnt-patterns
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1440px"}} -->
<div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
    <div class="wp-block-group"><!-- wp:heading {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"xx-large"} -->
        <h2 class="wp-block-heading has-xx-large-font-size" style="font-style:normal;font-weight:600">Trending News</h2>
        <!-- /wp:heading -->

        <!-- wp:read-more {"content":"See all"} /-->
    </div>
    <!-- /wp:group -->

    <!-- wp:group {"layout":{"type":"constrained","contentSize":"100%"}} -->
    <div class="wp-block-group"><!-- wp:query {"queryId":44,"query":{"perPage":"4","pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
        <div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columnCount":2}} -->
            <!-- wp:post-featured-image {"isLink":true,"height":"358px","align":"wide","style":{"border":{"radius":"10px"}}} /-->

            <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
            <div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                <div class="wp-block-group"><!-- wp:avatar {"size":48,"style":{"border":{"radius":"50px"}}} /-->

                    <!-- wp:post-author-name {"style":{"typography":{"textTransform":"capitalize"}},"textColor":"heading-color","fontSize":"normal"} /-->
                </div>
                <!-- /wp:group -->

                <!-- wp:post-date {"fontSize":"normal"} /-->
            </div>
            <!-- /wp:group -->

            <!-- wp:post-title {"isLink":true,"style":{"elements":{"link":{"color":{"text":"var:preset|color|heading-color"}}},"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":1.4}},"className":"is-style-title-hover-primary-color","fontSize":"x-large"} /-->

            <!-- wp:post-excerpt {"textColor":"foreground","fontSize":"normal"} /-->
            <!-- /wp:post-template -->
        </div>
        <!-- /wp:query -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->