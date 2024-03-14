<?php

/**
 * Title: WNT Pro - Magazine Layout Grid Overlay
 * Slug: walker-core/wnt-magazine-grid-overlay
 * Categories: wnt-patterns
 */
?>
<!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1440px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:query {"queryId":44,"query":{"perPage":"5","pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
    <div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"5px"}},"layout":{"type":"grid","columnCount":5}} -->
        <!-- wp:cover {"useFeaturedImage":true,"dimRatio":90,"minHeight":450,"gradient":"primary-gradient","contentPosition":"bottom center","style":{"border":{"radius":"0px"},"spacing":{"blockGap":"var:preset|spacing|40"}},"className":"is-style-default"} -->
        <div class="wp-block-cover has-custom-content-position is-position-bottom-center is-style-default" style="border-radius:0px;min-height:450px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-90 has-background-dim has-background-gradient has-primary-gradient-gradient-background"></span>
            <div class="wp-block-cover__inner-container"><!-- wp:post-terms {"term":"category","className":"is-style-categories-primary-background-color","fontSize":"normal"} /-->

                <!-- wp:post-title {"level":3,"isLink":true,"style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground-alt"},":hover":{"color":{"text":"var:preset|color|primary"}}}},"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":1.4,"fontSize":"18px"},"spacing":{"margin":{"top":"0","bottom":"0"}}},"className":"is-style-title-hover-primary-color"} /-->

                <!-- wp:post-date {"textColor":"neutral-color","fontSize":"normal"} /-->
            </div>
        </div>
        <!-- /wp:cover -->
        <!-- /wp:post-template -->
    </div>
    <!-- /wp:query -->
</div>
<!-- /wp:group -->