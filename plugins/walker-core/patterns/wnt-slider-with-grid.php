<?php

/**
 * Title: WNT Pro - Full Sldier with Grid 
 * Slug: walker-core/wnt-sldier-with-grid
 * Categories: wnt-patterns
 */
?>
<!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1440px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
    <div class="wp-block-group"><!-- wp:heading {"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}},"fontSize":"xx-large"} -->
        <h2 class="wp-block-heading has-xx-large-font-size" style="font-style:normal;font-weight:600">Life Style and Fashion</h2>
        <!-- /wp:heading -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns -->
    <div class="wp-block-columns"><!-- wp:column {"width":"100%"} -->
        <div class="wp-block-column" style="flex-basis:100%"><!-- wp:query {"queryId":20,"query":{"perPage":"3","pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"wnt-slider-with-grid"} -->
            <div class="wp-block-query wnt-slider-with-grid"><!-- wp:html -->
                <div class="wnt-slider-nav">
                    <div class="wnt-slide-prev"> </div>
                    <div class="wnt-slide-next"> </div>
                </div>
                <!-- /wp:html -->

                <!-- wp:post-template {"className":"swiper-wrapper wnt-swiper-holder ","layout":{"type":"default"}} -->
                <!-- wp:cover {"useFeaturedImage":true,"minHeight":616,"gradient":"primary-gradient","contentPosition":"bottom center","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|50","bottom":"var:preset|spacing|70","left":"var:preset|spacing|50"}}},"className":"is-style-wnt-cover-round-style"} -->
                <div class="wp-block-cover has-custom-content-position is-position-bottom-center is-style-wnt-cover-round-style" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--70);padding-left:var(--wp--preset--spacing--50);min-height:616px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient has-primary-gradient-gradient-background"></span>
                    <div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"border":{"radius":"10px"},"spacing":{"padding":{"top":"0rem","bottom":"0rem","right":"0rem","left":"0rem"}}},"className":"wnt-slide-content","layout":{"type":"default"}} -->
                        <div class="wp-block-group wnt-slide-content" style="border-radius:10px;padding-top:0rem;padding-right:0rem;padding-bottom:0rem;padding-left:0rem"><!-- wp:post-title {"textAlign":"center","isLink":true,"style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground-alt"},":hover":{"color":{"text":"var:preset|color|primary"}}}},"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1.3","fontSize":"48px","textTransform":"capitalize"},"spacing":{"padding":{"top":"var:preset|spacing|40"}}},"className":"is-style-title-hover-primary-color"} /-->

                            <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"textColor":"heading-color","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"},"fontSize":"medium"} -->
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
    </div>
    <!-- /wp:columns -->

    <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"}}},"layout":{"type":"constrained","contentSize":"1440px"}} -->
    <div class="wp-block-group" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:query {"queryId":33,"query":{"perPage":"6","pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false}} -->
        <div class="wp-block-query"><!-- wp:post-template {"layout":{"type":"grid","columnCount":3}} -->
            <!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|30","left":"var:preset|spacing|30","top":"0.5rem","bottom":"0.5rem"}},"border":{"radius":"5px"}},"backgroundColor":"neutral-color","layout":{"type":"constrained"}} -->
            <div class="wp-block-group has-neutral-color-background-color has-background" style="border-radius:5px;padding-top:0.5rem;padding-right:var(--wp--preset--spacing--30);padding-bottom:0.5rem;padding-left:var(--wp--preset--spacing--30)"><!-- wp:columns {"verticalAlignment":"center"} -->
                <div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"30%"} -->
                    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:30%"><!-- wp:post-featured-image {"isLink":true,"height":"100px","align":"wide","style":{"border":{"radius":"2px"}}} /--></div>
                    <!-- /wp:column -->

                    <!-- wp:column {"verticalAlignment":"center","width":"70%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
                    <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:70%"><!-- wp:post-title {"isLink":true,"style":{"typography":{"fontSize":"18px","fontStyle":"normal","fontWeight":"600","lineHeight":1.6},"elements":{"link":{"color":{"text":"var:preset|color|dark-heading-color"}}}},"className":"is-style-title-hover-primary-color"} /-->

                        <!-- wp:group {"style":{"spacing":{"blockGap":"0.7rem"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                        <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                            <div class="wp-block-group"><!-- wp:avatar {"size":32,"style":{"border":{"radius":"50px"}}} /-->

                                <!-- wp:post-author-name {"style":{"typography":{"textTransform":"capitalize"}},"textColor":"dark-heading-color","fontSize":"medium"} /-->
                            </div>
                            <!-- /wp:group -->

                            <!-- wp:post-date {"fontSize":"normal"} /-->
                        </div>
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
</div>
<!-- /wp:group -->