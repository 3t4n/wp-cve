<?php

/**
 * Title: WNT Pro - Full Sldier
 * Slug: walker-core/wnt-post-sldier
 * Categories: wnt-patterns
 */
?>
<!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1440px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:columns -->
    <div class="wp-block-columns"><!-- wp:column {"width":"100%"} -->
        <div class="wp-block-column" style="flex-basis:100%"><!-- wp:query {"queryId":20,"query":{"perPage":"3","pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"wnt-featured-slider-one"} -->
            <div class="wp-block-query wnt-featured-slider-one"><!-- wp:html -->
                <div class="wnt-slider-nav">
                    <div class="wnt-slide-prev"> </div>
                    <div class="wnt-slide-next"> </div>
                </div>
                <!-- /wp:html -->

                <!-- wp:post-template {"className":"swiper-wrapper wnt-swiper-holder ","layout":{"type":"default"}} -->
                <!-- wp:cover {"useFeaturedImage":true,"minHeight":616,"gradient":"primary-gradient","contentPosition":"bottom center","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","right":"var:preset|spacing|50","bottom":"var:preset|spacing|60","left":"var:preset|spacing|50"}}},"className":"is-style-wnt-cover-round-style"} -->
                <div class="wp-block-cover has-custom-content-position is-position-bottom-center is-style-wnt-cover-round-style" style="padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--50);min-height:616px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-100 has-background-dim has-background-gradient has-primary-gradient-gradient-background"></span>
                    <div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"border":{"radius":"10px"},"spacing":{"padding":{"top":"0rem","bottom":"0rem","right":"0rem","left":"0rem"}}},"className":"wnt-slide-content","layout":{"type":"default"}} -->
                        <div class="wp-block-group wnt-slide-content" style="border-radius:10px;padding-top:0rem;padding-right:0rem;padding-bottom:0rem;padding-left:0rem"><!-- wp:columns -->
                            <div class="wp-block-columns"><!-- wp:column {"width":"75%","style":{"spacing":{"blockGap":"var:preset|spacing|50"}}} -->
                                <div class="wp-block-column" style="flex-basis:75%"><!-- wp:post-title {"isLink":true,"style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground-alt"},":hover":{"color":{"text":"var:preset|color|primary"}}}},"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1.3","fontSize":"48px","textTransform":"capitalize"},"spacing":{"padding":{"top":"var:preset|spacing|40"}}},"className":"is-style-title-hover-primary-color"} /-->

                                    <!-- wp:post-excerpt {"style":{"typography":{"lineHeight":"1.4","fontSize":"18px"}},"textColor":"background-alt"} /-->

                                    <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"textColor":"heading-color","layout":{"type":"flex","flexWrap":"nowrap"},"fontSize":"medium"} -->
                                    <div class="wp-block-group has-heading-color-color has-text-color has-medium-font-size"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                                        <div class="wp-block-group"><!-- wp:avatar {"size":32,"style":{"border":{"radius":"50px"}}} /-->

                                            <!-- wp:post-author-name {"style":{"typography":{"textTransform":"capitalize"}},"textColor":"background-alt","fontSize":"small"} /-->
                                        </div>
                                        <!-- /wp:group -->

                                        <!-- wp:post-date {"textColor":"background-alt","fontSize":"small"} /-->
                                    </div>
                                    <!-- /wp:group -->
                                </div>
                                <!-- /wp:column -->

                                <!-- wp:column {"width":"25%"} -->
                                <div class="wp-block-column" style="flex-basis:25%"></div>
                                <!-- /wp:column -->
                            </div>
                            <!-- /wp:columns -->
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