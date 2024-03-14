<?php

/**
 * Title: Latest Post Block Dark
 * Slug: walker-core/latest-post-block-dark
 * Categories: walkercore-patterns
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|80","bottom":"var:preset|spacing|80","right":"var:preset|spacing|50","left":"var:preset|spacing|50"}}},"backgroundColor":"background-alt","layout":{"type":"constrained","contentSize":"1200px"}} -->
<div class="wp-block-group has-background-alt-background-color has-background" style="padding-top:var(--wp--preset--spacing--80);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--80);padding-left:var(--wp--preset--spacing--50)"><!-- wp:columns {"style":{"spacing":{"padding":{"right":"var:preset|spacing|30","left":"var:preset|spacing|30"}}}} -->
    <div class="wp-block-columns" style="padding-right:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30)"><!-- wp:column {"width":"100%"} -->
        <div class="wp-block-column" style="flex-basis:100%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
            <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"foreground-alt"} -->
                <h2 class="wp-block-heading has-text-align-center has-foreground-alt-color has-text-color" style="font-style:normal;font-weight:500">Blog and Articles</h2>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"align":"center","textColor":"light-shade"} -->
                <p class="has-text-align-center has-light-shade-color has-text-color">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"top":"var:preset|spacing|60"}}},"layout":{"type":"constrained","contentSize":"1200px"}} -->
    <div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--60)"><!-- wp:query {"queryId":21,"query":{"perPage":3,"pages":0,"offset":0,"postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"displayLayout":{"type":"flex","columns":3}} -->
        <div class="wp-block-query"><!-- wp:post-template -->
            <!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","bottom":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"var:preset|spacing|50"}},"border":{"radius":"0px","color":"#282a2f","width":"1px"}},"layout":{"type":"constrained"}} -->
            <div class="wp-block-group has-border-color" style="border-color:#282a2f;border-width:1px;border-radius:0px;padding-top:var(--wp--preset--spacing--50);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50);padding-left:var(--wp--preset--spacing--50)"><!-- wp:post-featured-image {"isLink":true,"height":"280px","align":"wide","style":{"border":{"radius":"0px"}}} /-->

                <!-- wp:post-terms {"term":"category","textAlign":"center","style":{"spacing":{"margin":{"top":"var:preset|spacing|60","right":"0","bottom":"0","left":"0"}},"elements":{"link":{"color":{"text":"var:preset|color|background"}}}},"className":"is-style-categories-background-with-round"} /-->

                <!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"var:preset|color|background"}}},"spacing":{"margin":{"top":"var:preset|spacing|20","right":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"var:preset|spacing|20"}}},"className":"is-style-title-hover-secondary-color","fontSize":"x-large"} /-->

                <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
                <div class="wp-block-group"><!-- wp:post-author-name {"style":{"typography":{"textTransform":"capitalize"},"color":{"text":"#d3d0d0"}}} /-->

                    <!-- wp:paragraph {"textColor":"light-gray"} -->
                    <p class="has-light-gray-color has-text-color">.</p>
                    <!-- /wp:paragraph -->

                    <!-- wp:post-date {"style":{"color":{"text":"#d3d0d0"}}} /-->
                </div>
                <!-- /wp:group -->

                <!-- wp:post-excerpt {"textAlign":"center","moreText":"","style":{"elements":{"link":{"color":{"text":"var:preset|color|light-shade"}}},"color":{"text":"#cdcccc"}}} /-->

                <!-- wp:read-more {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","right":"var:preset|spacing|50","left":"var:preset|spacing|40"}}},"backgroundColor":"background","textColor":"foreground","className":"is-style-readmore-hover-secondary-fill"} /-->
            </div>
            <!-- /wp:group -->
            <!-- /wp:post-template -->
        </div>
        <!-- /wp:query -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->