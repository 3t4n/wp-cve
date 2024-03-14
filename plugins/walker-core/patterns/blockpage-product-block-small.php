<?php

/**
 * Title: Blockpage PRO: Product Layout with Left Featured section style 2
 * Slug: walker-core/blockpage-product-block-small
 * Categories: blockpage-woo
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/shop_1.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/shop_2.jpg',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"60px","bottom":"120px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:60px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30","margin":{"bottom":"40px"},"padding":{"bottom":"20px"}},"border":{"bottom":{"color":"var:preset|color|border-color","width":"1px"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
    <div class="wp-block-group" style="border-bottom-color:var(--wp--preset--color--border-color);border-bottom-width:1px;margin-bottom:40px;padding-bottom:20px"><!-- wp:columns {"verticalAlignment":"bottom"} -->
        <div class="wp-block-columns are-vertically-aligned-bottom"><!-- wp:column {"verticalAlignment":"bottom","width":"66.66%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
            <div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:66.66%"><!-- wp:heading {"textAlign":"left","level":5,"textColor":"primary"} -->
                <h5 class="wp-block-heading has-text-align-left has-primary-color has-text-color"><?php echo esc_html_e('Shop', 'walker-core') ?></h5>
                <!-- /wp:heading -->

                <!-- wp:heading {"textAlign":"left","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"blockpage-heading","fontSize":"xxx-large"} -->
                <h1 class="wp-block-heading has-text-align-left blockpage-heading has-xxx-large-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Featured Products', 'walker-core') ?></h1>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"align":"left","style":{"spacing":{"margin":{"top":"20px"}},"typography":{"lineHeight":"1.5"}}} -->
                <p class="has-text-align-left" style="margin-top:20px;line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"verticalAlignment":"bottom","width":"33.33%"} -->
            <div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:33.33%"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"right"}} -->
                <div class="wp-block-group"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                    <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"spacing":{"padding":{"left":"33px","right":"33px","top":"16px","bottom":"16px"}},"border":{"radius":"60px"},"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"is-style-button-hover-white-bgcolor","fontSize":"normal"} -->
                        <div class="wp-block-button has-custom-font-size is-style-button-hover-white-bgcolor has-normal-font-size" style="font-style:normal;font-weight:500"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:16px;padding-right:33px;padding-bottom:16px;padding-left:33px"><?php echo esc_html_e('Visit Store', 'walker-core') ?></a></div>
                        <!-- /wp:button -->
                    </div>
                    <!-- /wp:buttons -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"15px"}}}} -->
    <div class="wp-block-columns"><!-- wp:column {"width":"25%"} -->
        <div class="wp-block-column" style="flex-basis:25%"><!-- wp:cover {"url":"<?php echo esc_url($walkercore_patterns_images[0]) ?>","id":806,"dimRatio":30,"minHeight":498,"gradient":"primary-gradient","layout":{"type":"constrained"}} -->
            <div class="wp-block-cover" style="min-height:498px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-30 has-background-dim wp-block-cover__gradient-background has-background-gradient has-primary-gradient-gradient-background"></span><img class="wp-block-cover__image-background wp-image-806" alt="" src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" data-object-fit="cover" />
                <div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","style":{"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"500"}},"textColor":"heading-color","fontSize":"xx-large"} -->
                    <h2 class="wp-block-heading has-text-align-center has-heading-color-color has-text-color has-xx-large-font-size" style="font-style:normal;font-weight:500;text-transform:uppercase"><?php echo esc_html_e('Women\'s Collections', 'walker-core') ?></h2>
                    <!-- /wp:heading -->
                </div>
            </div>
            <!-- /wp:cover -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"25%"} -->
        <div class="wp-block-column" style="flex-basis:25%"><!-- wp:cover {"url":"<?php echo esc_url($walkercore_patterns_images[1]) ?>","id":592,"dimRatio":30,"minHeight":498,"gradient":"secondary-gradient","contentPosition":"bottom center","layout":{"type":"constrained"}} -->
            <div class="wp-block-cover has-custom-content-position is-position-bottom-center" style="min-height:498px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-30 has-background-dim wp-block-cover__gradient-background has-background-gradient has-secondary-gradient-gradient-background"></span><img class="wp-block-cover__image-background wp-image-592" alt="" src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" data-object-fit="cover" />
                <div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","textColor":"heading-color","fontSize":"xx-large"} -->
                    <h2 class="wp-block-heading has-text-align-center has-heading-color-color has-text-color has-xx-large-font-size"><?php echo esc_html_e('Instant Buy from Phone', 'walker-core') ?></h2>
                    <!-- /wp:heading -->
                </div>
            </div>
            <!-- /wp:cover -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"25%"} -->
        <div class="wp-block-column" style="flex-basis:25%"><!-- wp:query {"queryId":12,"query":{"perPage":5,"pages":0,"offset":0,"postType":"product","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":false,"__woocommerceAttributes":[],"__woocommerceStockStatus":["instock","outofstock","onbackorder"]},"namespace":"woocommerce/product-query"} -->
            <div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"10px"}},"className":"products-block-post-template blockpage-products-list","layout":{"type":"default","columnCount":3},"__woocommerceNamespace":"woocommerce/product-query/product-template"} -->
                <!-- wp:group {"style":{"border":{"width":"1px"},"spacing":{"padding":{"top":"5px","bottom":"5px","left":"5px","right":"5px"}}},"borderColor":"border-color","layout":{"type":"constrained"}} -->
                <div class="wp-block-group has-border-color has-border-color-border-color" style="border-width:1px;padding-top:5px;padding-right:5px;padding-bottom:5px;padding-left:5px"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}}} -->
                    <div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"33.33%"} -->
                        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:33.33%"><!-- wp:woocommerce/product-image {"showSaleBadge":false,"imageSizing":"thumbnail","isDescendentOfQueryLoop":true,"height":"80px"} /--></div>
                        <!-- /wp:column -->

                        <!-- wp:column {"verticalAlignment":"center","width":"66.66%","style":{"spacing":{"blockGap":"10px"}}} -->
                        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:66.66%"><!-- wp:post-title {"textAlign":"left","level":3,"isLink":true,"style":{"spacing":{"margin":{"bottom":"0rem","top":"0px"}},"typography":{"fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"},":hover":{"color":{"text":"var:preset|color|primary"}}}}},"className":"is-style-default","fontSize":"medium","__woocommerceNamespace":"woocommerce/product-query/product-title"} /-->

                            <!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"textAlign":"left","style":{"spacing":{"margin":{"top":"0"}}}} /-->
                        </div>
                        <!-- /wp:column -->
                    </div>
                    <!-- /wp:columns -->
                </div>
                <!-- /wp:group -->
                <!-- /wp:post-template -->

                <!-- wp:query-no-results -->
                <!-- wp:paragraph {"placeholder":"Add text or blocks that will display when a query returns no results."} -->
                <p></p>
                <!-- /wp:paragraph -->
                <!-- /wp:query-no-results -->
            </div>
            <!-- /wp:query -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"25%"} -->
        <div class="wp-block-column" style="flex-basis:25%"><!-- wp:query {"queryId":12,"query":{"perPage":5,"pages":0,"offset":0,"postType":"product","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":false,"__woocommerceAttributes":[],"__woocommerceStockStatus":["instock","outofstock","onbackorder"]},"namespace":"woocommerce/product-query"} -->
            <div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"10px"}},"className":"products-block-post-template blockpage-products-list","layout":{"type":"default","columnCount":3},"__woocommerceNamespace":"woocommerce/product-query/product-template"} -->
                <!-- wp:group {"style":{"border":{"width":"1px"},"spacing":{"padding":{"top":"5px","bottom":"5px","left":"5px","right":"5px"}}},"borderColor":"border-color","layout":{"type":"constrained"}} -->
                <div class="wp-block-group has-border-color has-border-color-border-color" style="border-width:1px;padding-top:5px;padding-right:5px;padding-bottom:5px;padding-left:5px"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}}} -->
                    <div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"33.33%"} -->
                        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:33.33%"><!-- wp:woocommerce/product-image {"showSaleBadge":false,"imageSizing":"thumbnail","isDescendentOfQueryLoop":true,"height":"80px"} /--></div>
                        <!-- /wp:column -->

                        <!-- wp:column {"verticalAlignment":"center","width":"66.66%","style":{"spacing":{"blockGap":"10px"}}} -->
                        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:66.66%"><!-- wp:post-title {"textAlign":"left","level":3,"isLink":true,"style":{"spacing":{"margin":{"bottom":"0rem","top":"0px"}},"typography":{"fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"},":hover":{"color":{"text":"var:preset|color|primary"}}}}},"className":"is-style-default","fontSize":"medium","__woocommerceNamespace":"woocommerce/product-query/product-title"} /-->

                            <!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"textAlign":"left","style":{"spacing":{"margin":{"top":"0"}}}} /-->
                        </div>
                        <!-- /wp:column -->
                    </div>
                    <!-- /wp:columns -->
                </div>
                <!-- /wp:group -->
                <!-- /wp:post-template -->

                <!-- wp:query-no-results -->
                <!-- wp:paragraph {"placeholder":"Add text or blocks that will display when a query returns no results."} -->
                <p></p>
                <!-- /wp:paragraph -->
                <!-- /wp:query-no-results -->
            </div>
            <!-- /wp:query -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->