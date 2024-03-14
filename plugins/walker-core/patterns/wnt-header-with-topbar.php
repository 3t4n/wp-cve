<?php

/**
 * Title: WNT Pro - Header with Topbar
 * Slug: walker-core/wnt-header-with-topbar
 * Categories: wnt-patterns
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/patterns-media/header_banner_image.png',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","right":"0","bottom":"0","left":"0"},"blockGap":"0"}},"className":"wnt-magazine-header","layout":{"type":"constrained","contentSize":"100%","justifyContent":"left"}} -->
<div class="wp-block-group wnt-magazine-header" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"padding":{"right":"2rem","left":"2rem","top":"0.5rem","bottom":"0.5rem"}},"border":{"bottom":{"color":"var:preset|color|background-alt","width":"1px"}}},"layout":{"type":"constrained","contentSize":"1440px"}} -->
    <div class="wp-block-group" style="border-bottom-color:var(--wp--preset--color--background-alt);border-bottom-width:1px;padding-top:0.5rem;padding-right:2rem;padding-bottom:0.5rem;padding-left:2rem"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
        <div class="wp-block-group"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap"}} -->
            <div class="wp-block-group"><!-- wp:list {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"textColor":"heading-color","className":"is-style-list-style-no-bullet"} -->
                <ul class="is-style-list-style-no-bullet has-heading-color-color has-text-color" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:list-item -->
                    <li>+1 (012) 345-6789</li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->

                <!-- wp:list {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"textColor":"heading-color","className":"is-style-list-style-no-bullet"} -->
                <ul class="is-style-list-style-no-bullet has-heading-color-color has-text-color" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:list-item -->
                    <li>email@yoursite.com</li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->
            </div>
            <!-- /wp:group -->

            <!-- wp:social-links {"iconColor":"heading-color","iconColorValue":"#1F1F1F","iconBackgroundColor":"background","iconBackgroundColorValue":"#ffffff","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|20"}}}} -->
            <ul class="wp-block-social-links has-icon-color has-icon-background-color"><!-- wp:social-link {"url":"#","service":"instagram"} /-->

                <!-- wp:social-link {"url":"#","service":"behance"} /-->

                <!-- wp:social-link {"url":"#","service":"pinterest"} /-->

                <!-- wp:social-link {"url":"#","service":"spotify"} /-->
            </ul>
            <!-- /wp:social-links -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->

    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","right":"var:preset|spacing|60","left":"var:preset|spacing|60"}}},"layout":{"type":"constrained","contentSize":"1440px"}} -->
    <div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--60)"><!-- wp:columns {"verticalAlignment":"center"} -->
        <div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
            <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|20"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
                <div class="wp-block-group"><!-- wp:site-title {"textAlign":"left","style":{"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"700","letterSpacing":"2px","fontSize":"36px"},"elements":{"link":{"color":{"text":"var:preset|color|primary"}}}}} /-->

                    <!-- wp:site-tagline {"textAlign":"left","fontSize":"normal"} /-->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"verticalAlignment":"center","width":"66.66%"} -->
            <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:66.66%"><!-- wp:image {"id":447,"sizeSlug":"full","linkDestination":"none"} -->
                <figure class="wp-block-image size-full"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-447" /></figure>
                <!-- /wp:image -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->

    <!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","right":"var:preset|spacing|60","left":"var:preset|spacing|60"}},"border":{"top":{"color":"var:preset|color|background-alt","width":"1px"},"bottom":{"color":"var:preset|color|background-alt","width":"1px"}}},"layout":{"type":"constrained","contentSize":"1440px"}} -->
    <div class="wp-block-group" style="border-top-color:var(--wp--preset--color--background-alt);border-top-width:1px;border-bottom-color:var(--wp--preset--color--background-alt);border-bottom-width:1px;padding-top:var(--wp--preset--spacing--20);padding-right:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--20);padding-left:var(--wp--preset--spacing--60)"><!-- wp:columns {"verticalAlignment":"center"} -->
        <div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"80%"} -->
            <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:80%"><!-- wp:navigation {"textColor":"heading-color"} -->
                <!-- wp:page-list /-->
                <!-- /wp:navigation -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"verticalAlignment":"center","width":"20%"} -->
            <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:20%"><!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search on site...","buttonText":"Search","buttonUseIcon":true,"style":{"border":{"width":"0px","style":"none"}},"backgroundColor":"background","textColor":"heading-color","className":"wnt-header-withads-search"} /--></div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->