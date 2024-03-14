<?php

/**
 * Title: Blockpage PRO: Product Layout with Left Featured section 
 * Slug: walker-core/blockpage-product-block-left-featured
 * Categories: blockpage-woo
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/shop_2.jpg',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"60px","bottom":"120px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:60px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30","margin":{"bottom":"40px"},"padding":{"bottom":"20px"}},"border":{"bottom":{"color":"var:preset|color|border-color","width":"1px"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
    <div class="wp-block-group" style="border-bottom-color:var(--wp--preset--color--border-color);border-bottom-width:1px;margin-bottom:40px;padding-bottom:20px"><!-- wp:columns {"verticalAlignment":"bottom"} -->
        <div class="wp-block-columns are-vertically-aligned-bottom"><!-- wp:column {"verticalAlignment":"bottom","width":"66.66%","style":{"spacing":{"blockGap":"var:preset|spacing|30"}}} -->
            <div class="wp-block-column is-vertically-aligned-bottom" style="flex-basis:66.66%"><!-- wp:heading {"textAlign":"left","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"blockpage-heading","fontSize":"xxx-large"} -->
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

    <!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"32px"}}}} -->
    <div class="wp-block-columns"><!-- wp:column {"width":"40%"} -->
        <div class="wp-block-column" style="flex-basis:40%"><!-- wp:cover {"url":"<?php echo esc_url($walkercore_patterns_images[0]) ?>","id":806,"dimRatio":30,"minHeight":740,"gradient":"primary-gradient","layout":{"type":"constrained"}} -->
            <div class="wp-block-cover" style="min-height:740px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-30 has-background-dim wp-block-cover__gradient-background has-background-gradient has-primary-gradient-gradient-background"></span><img class="wp-block-cover__image-background wp-image-806" alt="" src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" data-object-fit="cover" />
                <div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","style":{"typography":{"textTransform":"uppercase","fontStyle":"normal","fontWeight":"500"}},"textColor":"heading-color","fontSize":"xx-large"} -->
                    <h2 class="wp-block-heading has-text-align-center has-heading-color-color has-text-color has-xx-large-font-size" style="font-style:normal;font-weight:500;text-transform:uppercase"><?php echo esc_html_e('50% OFF ON Summer SAle', 'walker-core') ?></h2>
                    <!-- /wp:heading -->

                    <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                    <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-color","style":{"color":{"background":"#ffffff00"},"border":{"radius":"5px","width":"1px"},"typography":{"fontStyle":"normal","fontWeight":"500","textTransform":"uppercase"}},"borderColor":"heading-color","className":"is-style-button-hover-white-bgcolor","fontSize":"small"} -->
                        <div class="wp-block-button has-custom-font-size is-style-button-hover-white-bgcolor has-small-font-size" style="font-style:normal;font-weight:500;text-transform:uppercase"><a class="wp-block-button__link has-heading-color-color has-text-color has-background has-border-color has-heading-color-border-color wp-element-button" style="border-width:1px;border-radius:5px;background-color:#ffffff00"><?php echo esc_html_e('Grab Deal', 'walker-core') ?></a></div>
                        <!-- /wp:button -->
                    </div>
                    <!-- /wp:buttons -->
                </div>
            </div>
            <!-- /wp:cover -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"60%","style":{"spacing":{"padding":{"right":"0","left":"0","top":"0","bottom":"0"}}}} -->
        <div class="wp-block-column" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0;flex-basis:60%"><!-- wp:query {"queryId":12,"query":{"perPage":"6","pages":0,"offset":0,"postType":"product","order":"asc","orderBy":"title","author":"","search":"","exclude":[],"sticky":"","inherit":false,"__woocommerceAttributes":[],"__woocommerceStockStatus":["instock","outofstock","onbackorder"]},"namespace":"woocommerce/product-query"} -->
            <div class="wp-block-query"><!-- wp:post-template {"style":{"spacing":{"blockGap":"30px"}},"className":"products-block-post-template blockpage-products-list","layout":{"type":"grid","columnCount":3},"__woocommerceNamespace":"woocommerce/product-query/product-template"} -->
                <!-- wp:group {"style":{"border":{"width":"1px"},"spacing":{"padding":{"top":"0","bottom":"24px","left":"0","right":"0"}}},"borderColor":"border-color","layout":{"type":"constrained"}} -->
                <div class="wp-block-group has-border-color has-border-color-border-color" style="border-width:1px;padding-top:0;padding-right:0;padding-bottom:24px;padding-left:0"><!-- wp:group {"style":{"border":{"width":"0px","style":"none"}},"className":"blockpage-product-image","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group blockpage-product-image" style="border-style:none;border-width:0px"><!-- wp:woocommerce/product-image {"imageSizing":"thumbnail","isDescendentOfQueryLoop":true,"height":"236px"} /-->

                        <!-- wp:woocommerce/product-button {"textAlign":"center","width":100,"isDescendentOfQueryLoop":true,"fontSize":"small","style":{"elements":{"link":{"color":{"text":"var:preset|color|heading-color"},":hover":{"color":{"text":"var:preset|color|heading-color"}}}}}} /-->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:post-title {"textAlign":"center","level":3,"isLink":true,"style":{"spacing":{"margin":{"bottom":"0rem","top":"24px"}},"typography":{"fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"},":hover":{"color":{"text":"var:preset|color|primary"}}}}},"className":"is-style-default","fontSize":"medium","__woocommerceNamespace":"woocommerce/product-query/product-title"} /-->

                    <!-- wp:woocommerce/product-price {"isDescendentOfQueryLoop":true,"textAlign":"center","style":{"spacing":{"margin":{"top":"var:preset|spacing|20"}}}} /-->
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