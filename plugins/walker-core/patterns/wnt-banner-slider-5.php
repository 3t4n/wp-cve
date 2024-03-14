<?php

/**
 * Title: WNT Pro - Featured Banner with Slider
 * Slug: walker-core/wnt-banner-slider-5
 * Categories: wnt-patterns
 */
?>
<!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1440px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:columns -->
    <div class="wp-block-columns"><!-- wp:column {"width":"50%","layout":{"type":"default"}} -->
        <div class="wp-block-column" style="flex-basis:50%"><!-- wp:query {"queryId":20,"query":{"perPage":"6","pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"wnt-featured-slider-five"} -->
            <div class="wp-block-query wnt-featured-slider-five"><!-- wp:html -->
                <div class="wnt-slider-nav">
                    <div class="wnt-slide-prev"> </div>
                    <div class="wnt-slide-next"> </div>
                </div>
                <!-- /wp:html -->

                <!-- wp:post-template {"className":"swiper-wrapper wnt-swiper-holder ","layout":{"type":"default"}} -->
                <!-- wp:cover {"useFeaturedImage":true,"minHeight":564,"gradient":"primary-gradient","contentPosition":"bottom center","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"className":"is-style-wnt-cover-round-style"} -->
                <div class="wp-block-cover has-custom-content-position is-position-bottom-center is-style-wnt-cover-round-style" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50);min-height:564px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient has-primary-gradient-gradient-background"></span>
                    <div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"border":{"radius":"10px"},"spacing":{"padding":{"top":"0rem","bottom":"0rem","right":"0rem","left":"0rem"}}},"className":"wnt-slide-content","layout":{"type":"default"}} -->
                        <div class="wp-block-group wnt-slide-content" style="border-radius:10px;padding-top:0rem;padding-right:0rem;padding-bottom:0rem;padding-left:0rem"><!-- wp:post-title {"textAlign":"left","isLink":true,"style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground-alt"},":hover":{"color":{"text":"var:preset|color|primary"}}}},"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1.4","fontSize":"32px","textTransform":"capitalize"},"spacing":{"padding":{"top":"var:preset|spacing|40"}}},"className":"is-style-title-hover-primary-color"} /-->

                            <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"textColor":"heading-color","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"},"fontSize":"medium"} -->
                            <div class="wp-block-group has-heading-color-color has-text-color has-medium-font-size"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                                <div class="wp-block-group"><!-- wp:avatar {"size":32,"style":{"border":{"radius":"50px"}}} /-->

                                    <!-- wp:post-author-name {"style":{"typography":{"textTransform":"capitalize"}},"textColor":"foreground","fontSize":"small"} /-->
                                </div>
                                <!-- /wp:group -->

                                <!-- wp:post-date {"textColor":"foreground","fontSize":"small"} /-->
                            </div>
                            <!-- /wp:group -->
                        </div>
                        <!-- /wp:group -->
                    </div>
                </div>
                <!-- /wp:cover -->
                <!-- /wp:post-template -->
            </div>
            <!-- /wp:query -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"50%"} -->
        <div class="wp-block-column" style="flex-basis:50%"><!-- wp:query {"queryId":44,"query":{"perPage":"4","pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
            <div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columnCount":2}} -->
                <!-- wp:cover {"useFeaturedImage":true,"dimRatio":90,"minHeight":270,"gradient":"primary-gradient","contentPosition":"bottom center","className":"is-style-wnt-cover-round-style"} -->
                <div class="wp-block-cover has-custom-content-position is-position-bottom-center is-style-wnt-cover-round-style" style="min-height:270px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-90 has-background-dim has-background-gradient has-primary-gradient-gradient-background"></span>
                    <div class="wp-block-cover__inner-container"><!-- wp:post-terms {"term":"category","className":"is-style-categories-primary-background-color","fontSize":"normal"} /-->

                        <!-- wp:post-title {"isLink":true,"style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground-alt"},":hover":{"color":{"text":"var:preset|color|primary"}}}},"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":1.4},"spacing":{"margin":{"top":"0","bottom":"var:preset|spacing|40"}}},"className":"is-style-title-hover-primary-color","fontSize":"medium"} /-->

                        <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                        <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                            <div class="wp-block-group"><!-- wp:avatar {"size":32,"style":{"border":{"radius":"40px"}}} /-->

                                <!-- wp:post-author-name {"style":{"typography":{"textTransform":"capitalize"}},"textColor":"foreground"} /-->
                            </div>
                            <!-- /wp:group -->

                            <!-- wp:post-date {"textColor":"foreground","fontSize":"normal"} /-->
                        </div>
                        <!-- /wp:group -->
                    </div>
                </div>
                <!-- /wp:cover -->
                <!-- /wp:post-template -->
            </div>
            <!-- /wp:query -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->