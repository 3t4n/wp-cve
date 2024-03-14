<?php

/**
 * Title: Blockpage PRO: Service Layout 2
 * Slug: walker-core/blockpage-service-2
 * Categories: blockpage-service
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/icon_1.png',
    WALKER_CORE_URL . 'admin/images/blockpage/icon_2.png',
    WALKER_CORE_URL . 'admin/images/blockpage/icon_3.png',
    WALKER_CORE_URL . 'admin/images/blockpage/icon_4.png',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"30px","bottom":"120px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="padding-top:30px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"90px"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":""}} -->
            <div class="wp-block-group"><!-- wp:heading {"textAlign":"left","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"blockpage-heading","fontSize":"xxx-large"} -->
                <h1 class="wp-block-heading has-text-align-left blockpage-heading has-xxx-large-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Our Services', 'walker-core') ?></h1>
                <!-- /wp:heading -->

                <!-- wp:paragraph {"align":"left","style":{"spacing":{"margin":{"top":"24px"}},"typography":{"lineHeight":"1.5"}}} -->
                <p class="has-text-align-left" style="margin-top:24px;line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->
            </div>
            <!-- /wp:group -->

            <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"left"},"style":{"spacing":{"margin":{"top":"42px"}}}} -->
            <div class="wp-block-buttons" style="margin-top:42px"><!-- wp:button {"textColor":"heading-color","gradient":"primary-gradient","style":{"spacing":{"padding":{"left":"40px","right":"40px","top":"18px","bottom":"18px"}},"border":{"radius":"60px"}},"className":"is-style-button-hover-white-bgcolor","fontSize":"normal"} -->
                <div class="wp-block-button has-custom-font-size is-style-button-hover-white-bgcolor has-normal-font-size"><a class="wp-block-button__link has-heading-color-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:18px;padding-right:40px;padding-bottom:18px;padding-left:40px"><?php echo esc_html_e('All Services', 'walker-core') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"60%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:60%"><!-- wp:columns {"style":{"spacing":{"margin":{"top":"0px"},"blockGap":{"left":"40px"}}}} -->
            <div class="wp-block-columns" style="margin-top:0px"><!-- wp:column -->
                <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}},"border":{"radius":"5px"}},"backgroundColor":"background-alt","className":"blockpage-services-box","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group blockpage-services-box has-background-alt-background-color has-background" style="border-radius:5px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px"><!-- wp:image {"id":139,"width":"34px","height":"32px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-139" style="object-fit:cover;width:34px;height:32px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"top":"32px"}}},"fontSize":"large"} -->
                        <h4 class="wp-block-heading has-large-font-size" style="margin-top:32px"><?php echo esc_html_e('Digital Marketing', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"fontSize":"normal"} -->
                        <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->

                        <!-- wp:buttons {"className":"blockpage-button-arrow"} -->
                        <div class="wp-block-buttons blockpage-button-arrow"><!-- wp:button {"textColor":"primary","style":{"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}},"color":{"background":"#ffffff00"}},"className":"is-style-button-hover-primary-color","fontSize":"normal"} -->
                            <div class="wp-block-button has-custom-font-size is-style-button-hover-primary-color has-normal-font-size"><a class="wp-block-button__link has-primary-color has-text-color has-background wp-element-button" style="background-color:#ffffff00;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">Read More', 'walker-core') ?> <img class="wp-image-155" style="width: 26px;" src="http://localhost:8888/walkerwp/wp-content/uploads/2023/10/button-arrow-2.png" alt=""></a></div>
                            <!-- /wp:button -->
                        </div>
                        <!-- /wp:buttons -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:column -->

                <!-- wp:column -->
                <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}},"border":{"radius":"5px"}},"backgroundColor":"background-alt","className":"blockpage-services-box","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group blockpage-services-box has-background-alt-background-color has-background" style="border-radius:5px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px"><!-- wp:image {"id":139,"width":"34px","height":"32px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-139" style="object-fit:cover;width:34px;height:32px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"top":"32px"}}},"fontSize":"large"} -->
                        <h4 class="wp-block-heading has-large-font-size" style="margin-top:32px"><?php echo esc_html_e('Market Research', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"fontSize":"normal"} -->
                        <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->

                        <!-- wp:buttons {"className":"blockpage-button-arrow"} -->
                        <div class="wp-block-buttons blockpage-button-arrow"><!-- wp:button {"textColor":"primary","style":{"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}},"color":{"background":"#ffffff00"}},"className":"is-style-button-hover-primary-color","fontSize":"normal"} -->
                            <div class="wp-block-button has-custom-font-size is-style-button-hover-primary-color has-normal-font-size"><a class="wp-block-button__link has-primary-color has-text-color has-background wp-element-button" style="background-color:#ffffff00;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">Read More', 'walker-core') ?> <img class="wp-image-155" style="width: 26px;" src="http://localhost:8888/walkerwp/wp-content/uploads/2023/10/button-arrow-2.png" alt=""></a></div>
                            <!-- /wp:button -->
                        </div>
                        <!-- /wp:buttons -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:column -->
            </div>
            <!-- /wp:columns -->

            <!-- wp:columns {"style":{"spacing":{"margin":{"top":"40px"},"blockGap":{"left":"40px"}}}} -->
            <div class="wp-block-columns" style="margin-top:40px"><!-- wp:column -->
                <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}},"border":{"radius":"5px"}},"backgroundColor":"background-alt","className":"blockpage-services-box","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group blockpage-services-box has-background-alt-background-color has-background" style="border-radius:5px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px"><!-- wp:image {"id":139,"width":"34px","height":"32px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[2]) ?>" alt="" class="wp-image-139" style="object-fit:cover;width:34px;height:32px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"top":"32px"}}},"fontSize":"large"} -->
                        <h4 class="wp-block-heading has-large-font-size" style="margin-top:32px"><?php echo esc_html_e('Digital Marketing', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"fontSize":"normal"} -->
                        <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->

                        <!-- wp:buttons {"className":"blockpage-button-arrow"} -->
                        <div class="wp-block-buttons blockpage-button-arrow"><!-- wp:button {"textColor":"primary","style":{"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}},"color":{"background":"#ffffff00"}},"className":"is-style-button-hover-primary-color","fontSize":"normal"} -->
                            <div class="wp-block-button has-custom-font-size is-style-button-hover-primary-color has-normal-font-size"><a class="wp-block-button__link has-primary-color has-text-color has-background wp-element-button" style="background-color:#ffffff00;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">Read More', 'walker-core') ?> <img class="wp-image-155" style="width: 26px;" src="http://localhost:8888/walkerwp/wp-content/uploads/2023/10/button-arrow-2.png" alt=""></a></div>
                            <!-- /wp:button -->
                        </div>
                        <!-- /wp:buttons -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:column -->

                <!-- wp:column -->
                <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"40px","bottom":"40px","left":"40px","right":"40px"}},"border":{"radius":"5px"}},"backgroundColor":"background-alt","className":"blockpage-services-box","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group blockpage-services-box has-background-alt-background-color has-background" style="border-radius:5px;padding-top:40px;padding-right:40px;padding-bottom:40px;padding-left:40px"><!-- wp:image {"id":139,"width":"34px","height":"32px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
                        <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[3]) ?>" alt="" class="wp-image-139" style="object-fit:cover;width:34px;height:32px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:heading {"level":4,"style":{"spacing":{"margin":{"top":"32px"}}},"fontSize":"large"} -->
                        <h4 class="wp-block-heading has-large-font-size" style="margin-top:32px"><?php echo esc_html_e('Market Research', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"fontSize":"normal"} -->
                        <p class="has-normal-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->

                        <!-- wp:buttons {"className":"blockpage-button-arrow"} -->
                        <div class="wp-block-buttons blockpage-button-arrow"><!-- wp:button {"textColor":"primary","style":{"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}},"color":{"background":"#ffffff00"}},"className":"is-style-button-hover-primary-color","fontSize":"normal"} -->
                            <div class="wp-block-button has-custom-font-size is-style-button-hover-primary-color has-normal-font-size"><a class="wp-block-button__link has-primary-color has-text-color has-background wp-element-button" style="background-color:#ffffff00;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0">Read More', 'walker-core') ?> <img class="wp-image-155" style="width: 26px;" src="http://localhost:8888/walkerwp/wp-content/uploads/2023/10/button-arrow-2.png" alt=""></a></div>
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
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->