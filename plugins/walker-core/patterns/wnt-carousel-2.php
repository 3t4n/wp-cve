<?php

/**
 * Title: WNT Pro : 3 Column Carousel
 * Slug: walker-core/wnt-carousel-2
 * Categories: wnt-patterns
 */
?>
<!-- wp:group {"style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1440px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-right:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:columns -->
    <div class="wp-block-columns"><!-- wp:column {"width":"100%"} -->
        <div class="wp-block-column" style="flex-basis:100%"><!-- wp:query {"queryId":20,"query":{"perPage":"6","pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"className":"wnt-carousel-two"} -->
            <div class="wp-block-query wnt-carousel-two"><!-- wp:group {"style":{"spacing":{"margin":{"bottom":"var:preset|spacing|50"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
                <div class="wp-block-group" style="margin-bottom:var(--wp--preset--spacing--50)"><!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600"}}} -->
                    <h3 class="wp-block-heading" style="font-style:normal;font-weight:600">Post Carousel</h3>
                    <!-- /wp:heading -->

                    <!-- wp:html -->
                    <div class="wnt-slider-nav">
                        <div class="wnt-slide-prev"> </div>
                        <div class="wnt-slide-next"> </div>
                    </div>
                    <!-- /wp:html -->
                </div>
                <!-- /wp:group -->

                <!-- wp:post-template {"className":"swiper-wrapper wnt-swiper-holder ","layout":{"type":"default"}} -->
                <!-- wp:post-featured-image {"height":"400px","style":{"border":{"radius":"5px"}}} /-->

                <!-- wp:post-title {"textAlign":"left","isLink":true,"style":{"elements":{"link":{"color":{"text":"var:preset|color|heading-color"},":hover":{"color":{"text":"var:preset|color|primary"}}}},"typography":{"fontStyle":"normal","fontWeight":"600","lineHeight":"1.4","textTransform":"capitalize"},"spacing":{"padding":{"top":"var:preset|spacing|20"}}},"className":"is-style-title-hover-primary-color","fontSize":"x-large"} /-->

                <!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40","margin":{"top":"var:preset|spacing|40"}}},"textColor":"heading-color","layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"left"},"fontSize":"medium"} -->
                <div class="wp-block-group has-heading-color-color has-text-color has-medium-font-size" style="margin-top:var(--wp--preset--spacing--40)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group"><!-- wp:avatar {"size":32,"style":{"border":{"radius":"50px"}}} /-->

                        <!-- wp:post-author-name {"style":{"typography":{"textTransform":"capitalize"}},"textColor":"foreground","fontSize":"small"} /-->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:post-date {"textColor":"foreground","fontSize":"small"} /-->
                </div>
                <!-- /wp:group -->
                <!-- /wp:post-template -->
            </div>
            <!-- /wp:query -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->