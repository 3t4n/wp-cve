<?php

/**
 * Title: Blockpage PRO: Service Layout 3
 * Slug: walker-core/blockpage-service-3
 * Categories: blockpage-service
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/folio1.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/folio2.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/folio3.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/folio4.jpg',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"30px","bottom":"120px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="padding-top:30px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"660px"}} -->
    <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":5,"textColor":"primary"} -->
        <h5 class="wp-block-heading has-text-align-center has-primary-color has-text-color"><?php echo esc_html_e('Solutions', 'walker-core') ?></h5>
        <!-- /wp:heading -->

        <!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"blockpage-heading","fontSize":"xxx-large"} -->
        <h1 class="wp-block-heading has-text-align-center blockpage-heading has-xxx-large-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Our Services', 'walker-core') ?></h1>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"40px"}},"typography":{"lineHeight":"1.5"}}} -->
        <p class="has-text-align-center" style="margin-top:40px;line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"style":{"spacing":{"margin":{"top":"60px"},"blockGap":{"left":"32px"}}}} -->
    <div class="wp-block-columns" style="margin-top:60px"><!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"border":{"radius":"5px"}},"backgroundColor":"background-alt","className":"blockpage-services-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-services-box has-background-alt-background-color has-background" style="border-radius:5px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:image {"align":"full","id":268,"sizeSlug":"medium","linkDestination":"none","style":{"border":{"radius":{"topLeft":"5px","topRight":"5px","bottomLeft":"0px","bottomRight":"0px"}}}} -->
                <figure class="wp-block-image alignfull size-medium has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-268" style="border-top-left-radius:5px;border-top-right-radius:5px;border-bottom-left-radius:0px;border-bottom-right-radius:0px" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"30px","left":"30px","right":"30px"}}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-group" style="padding-top:0;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"large"} -->
                    <h4 class="wp-block-heading has-large-font-size" style="margin-top:24px"><?php echo esc_html_e('Digital Marketing', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"fontSize":"normal"} -->
                    <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor aliqua.', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:buttons {"className":"blockpage-button-arrow"} -->
                    <div class="wp-block-buttons blockpage-button-arrow"><!-- wp:button {"textColor":"primary","style":{"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}},"color":{"background":"#ffffff00"}},"className":"is-style-button-hover-primary-color","fontSize":"normal"} -->
                        <div class="wp-block-button has-custom-font-size is-style-button-hover-primary-color has-normal-font-size"><a class="wp-block-button__link has-primary-color has-text-color has-background wp-element-button" style="background-color:#ffffff00;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><?php echo esc_html_e('Read More', 'walker-core') ?> <img class="wp-image-155" style="width: 26px;" src="http://localhost:8888/walkerwp/wp-content/uploads/2023/10/button-arrow-2.png" alt=""></a></div>
                        <!-- /wp:button -->
                    </div>
                    <!-- /wp:buttons -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"border":{"radius":"5px"}},"backgroundColor":"background-alt","className":"blockpage-services-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-services-box has-background-alt-background-color has-background" style="border-radius:5px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:image {"align":"full","id":269,"sizeSlug":"medium","linkDestination":"none","style":{"border":{"radius":{"topLeft":"5px","topRight":"5px","bottomLeft":"0px","bottomRight":"0px"}}}} -->
                <figure class="wp-block-image alignfull size-medium has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-269" style="border-top-left-radius:5px;border-top-right-radius:5px;border-bottom-left-radius:0px;border-bottom-right-radius:0px" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"30px","left":"30px","right":"30px"}}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-group" style="padding-top:0;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"large"} -->
                    <h4 class="wp-block-heading has-large-font-size" style="margin-top:24px"><?php echo esc_html_e('Digital Marketing', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"fontSize":"normal"} -->
                    <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor aliqua.', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:buttons {"className":"blockpage-button-arrow"} -->
                    <div class="wp-block-buttons blockpage-button-arrow"><!-- wp:button {"textColor":"primary","style":{"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}},"color":{"background":"#ffffff00"}},"className":"is-style-button-hover-primary-color","fontSize":"normal"} -->
                        <div class="wp-block-button has-custom-font-size is-style-button-hover-primary-color has-normal-font-size"><a class="wp-block-button__link has-primary-color has-text-color has-background wp-element-button" style="background-color:#ffffff00;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><?php echo esc_html_e('Read More', 'walker-core') ?> <img class="wp-image-155" style="width: 26px;" src="http://localhost:8888/walkerwp/wp-content/uploads/2023/10/button-arrow-2.png" alt=""></a></div>
                        <!-- /wp:button -->
                    </div>
                    <!-- /wp:buttons -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"border":{"radius":"5px"}},"backgroundColor":"background-alt","className":"blockpage-services-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-services-box has-background-alt-background-color has-background" style="border-radius:5px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:image {"align":"full","id":267,"sizeSlug":"medium","linkDestination":"none","style":{"border":{"radius":{"topLeft":"5px","topRight":"5px","bottomLeft":"0px","bottomRight":"0px"}}}} -->
                <figure class="wp-block-image alignfull size-medium has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[2]) ?>" alt="" class="wp-image-267" style="border-top-left-radius:5px;border-top-right-radius:5px;border-bottom-left-radius:0px;border-bottom-right-radius:0px" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"30px","left":"30px","right":"30px"}}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-group" style="padding-top:0;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"large"} -->
                    <h4 class="wp-block-heading has-large-font-size" style="margin-top:24px"><?php echo esc_html_e('Digital Marketing', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"fontSize":"normal"} -->
                    <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor aliqua.', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:buttons {"className":"blockpage-button-arrow"} -->
                    <div class="wp-block-buttons blockpage-button-arrow"><!-- wp:button {"textColor":"primary","style":{"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}},"color":{"background":"#ffffff00"}},"className":"is-style-button-hover-primary-color","fontSize":"normal"} -->
                        <div class="wp-block-button has-custom-font-size is-style-button-hover-primary-color has-normal-font-size"><a class="wp-block-button__link has-primary-color has-text-color has-background wp-element-button" style="background-color:#ffffff00;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><?php echo esc_html_e('Read More', 'walker-core') ?> <img class="wp-image-155" style="width: 26px;" src="http://localhost:8888/walkerwp/wp-content/uploads/2023/10/button-arrow-2.png" alt=""></a></div>
                        <!-- /wp:button -->
                    </div>
                    <!-- /wp:buttons -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"border":{"radius":"5px"}},"backgroundColor":"background-alt","className":"blockpage-services-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-services-box has-background-alt-background-color has-background" style="border-radius:5px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:image {"align":"full","id":269,"sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":{"topLeft":"5px","topRight":"5px","bottomLeft":"0px","bottomRight":"0px"}}}} -->
                <figure class="wp-block-image alignfull size-full has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[3]) ?>" alt="" class="wp-image-269" style="border-top-left-radius:5px;border-top-right-radius:5px;border-bottom-left-radius:0px;border-bottom-right-radius:0px" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"30px","left":"30px","right":"30px"}}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-group" style="padding-top:0;padding-right:30px;padding-bottom:30px;padding-left:30px"><!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"large"} -->
                    <h4 class="wp-block-heading has-large-font-size" style="margin-top:24px"><?php echo esc_html_e('Digital Marketing', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"fontSize":"normal"} -->
                    <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor aliqua.', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:buttons {"className":"blockpage-button-arrow"} -->
                    <div class="wp-block-buttons blockpage-button-arrow"><!-- wp:button {"textColor":"primary","style":{"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}},"color":{"background":"#ffffff00"}},"className":"is-style-button-hover-primary-color","fontSize":"normal"} -->
                        <div class="wp-block-button has-custom-font-size is-style-button-hover-primary-color has-normal-font-size"><a class="wp-block-button__link has-primary-color has-text-color has-background wp-element-button" style="background-color:#ffffff00;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><?php echo esc_html_e('Read More', 'walker-core') ?> <img class="wp-image-155" style="width: 26px;" src="http://localhost:8888/walkerwp/wp-content/uploads/2023/10/button-arrow-2.png" alt=""></a></div>
                        <!-- /wp:button -->
                    </div>
                    <!-- /wp:buttons -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->