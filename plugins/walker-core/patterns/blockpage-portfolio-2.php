<?php

/**
 * Title: Blockpage PRO: Portfolio Layout 2
 * Slug: walker-core/blockpage-portfolio-2
 * Categories: blockpage-portfolio
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/folio1.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/folio2.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/folio3.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/folio4.jpg',

);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"60px","bottom":"60px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:60px;padding-right:var(--wp--preset--spacing--50);padding-bottom:60px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"660px"}} -->
    <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":5,"textColor":"primary"} -->
        <h5 class="wp-block-heading has-text-align-center has-primary-color has-text-color"><?php echo esc_html_e('Portfolios', 'walker-core') ?></h5>
        <!-- /wp:heading -->

        <!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"blockpage-heading","fontSize":"xxx-large"} -->
        <h1 class="wp-block-heading has-text-align-center blockpage-heading has-xxx-large-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Latest Projects', 'walker-core') ?></h1>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"40px"}},"typography":{"lineHeight":"1.5"}}} -->
        <p class="has-text-align-center" style="margin-top:40px;line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"style":{"spacing":{"margin":{"top":"60px"},"blockGap":{"left":"30px"}}}} -->
    <div class="wp-block-columns" style="margin-top:60px"><!-- wp:column {"width":"50%"} -->
        <div class="wp-block-column" style="flex-basis:50%"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"border":{"radius":"0px"}},"className":"blockpage-portfolio-box","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-portfolio-box" style="border-radius:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:image {"align":"full","id":539,"width":"undefinedpx","height":"560px","sizeSlug":"large","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
                <figure class="wp-block-image alignfull size-large is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-539" style="border-radius:0px;width:undefinedpx;height:560px" /></figure>
                <!-- /wp:image -->

                <!-- wp:group {"className":"blockpage-portfolio-info","layout":{"type":"constrained"}} -->
                <div class="wp-block-group blockpage-portfolio-info"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                    <div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"heading-alt","textColor":"border-color","style":{"border":{"radius":"50px"}},"className":"is-style-button-hover-primary-bgcolor"} -->
                        <div class="wp-block-button is-style-button-hover-primary-bgcolor"><a class="wp-block-button__link has-border-color-color has-heading-alt-background-color has-text-color has-background wp-element-button" style="border-radius:50px"><?php echo esc_html_e('More', 'walker-core') ?></a></div>
                        <!-- /wp:button -->
                    </div>
                    <!-- /wp:buttons -->

                    <!-- wp:heading {"textAlign":"center","level":3,"style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"large"} -->
                    <h3 class="wp-block-heading has-text-align-center has-large-font-size" style="margin-top:24px"><?php echo esc_html_e('Architect &amp; Construction', 'walker-core') ?></h3>
                    <!-- /wp:heading -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"width":"50%","style":{"spacing":{"padding":{"right":"0","left":"0"}}}} -->
        <div class="wp-block-column" style="padding-right:0;padding-left:0;flex-basis:50%"><!-- wp:columns {"style":{"spacing":{"margin":{"top":"0px"},"blockGap":{"left":"20px"}}}} -->
            <div class="wp-block-columns" style="margin-top:0px"><!-- wp:column -->
                <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"border":{"radius":"0px"}},"className":"blockpage-portfolio-box","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group blockpage-portfolio-box" style="border-radius:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:image {"align":"full","id":540,"width":"undefinedpx","height":"260px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
                        <figure class="wp-block-image alignfull size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-540" style="border-radius:0px;width:undefinedpx;height:260px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:group {"className":"blockpage-portfolio-info","layout":{"type":"constrained"}} -->
                        <div class="wp-block-group blockpage-portfolio-info"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                            <div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"heading-alt","textColor":"border-color","style":{"border":{"radius":"50px"}},"className":"is-style-button-hover-primary-bgcolor"} -->
                                <div class="wp-block-button is-style-button-hover-primary-bgcolor"><a class="wp-block-button__link has-border-color-color has-heading-alt-background-color has-text-color has-background wp-element-button" style="border-radius:50px"><?php echo esc_html_e('More', 'walker-core') ?></a></div>
                                <!-- /wp:button -->
                            </div>
                            <!-- /wp:buttons -->

                            <!-- wp:heading {"textAlign":"center","level":3,"style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"large"} -->
                            <h3 class="wp-block-heading has-text-align-center has-large-font-size" style="margin-top:24px"><?php echo esc_html_e('Architect &amp; Construction', 'walker-core') ?></h3>
                            <!-- /wp:heading -->
                        </div>
                        <!-- /wp:group -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:column -->
            </div>
            <!-- /wp:columns -->

            <!-- wp:columns {"style":{"spacing":{"blockGap":{"left":"30px"},"margin":{"top":"30px"}}}} -->
            <div class="wp-block-columns" style="margin-top:30px"><!-- wp:column -->
                <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"border":{"radius":"0px"}},"className":"blockpage-portfolio-box","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group blockpage-portfolio-box" style="border-radius:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:image {"align":"full","id":267,"width":"undefinedpx","height":"260px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
                        <figure class="wp-block-image alignfull size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[2]) ?>" alt="" class="wp-image-267" style="border-radius:0px;width:undefinedpx;height:260px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:group {"className":"blockpage-portfolio-info","layout":{"type":"constrained"}} -->
                        <div class="wp-block-group blockpage-portfolio-info"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                            <div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"heading-alt","textColor":"border-color","style":{"border":{"radius":"50px"}},"className":"is-style-button-hover-primary-bgcolor"} -->
                                <div class="wp-block-button is-style-button-hover-primary-bgcolor"><a class="wp-block-button__link has-border-color-color has-heading-alt-background-color has-text-color has-background wp-element-button" style="border-radius:50px"><?php echo esc_html_e('More', 'walker-core') ?></a></div>
                                <!-- /wp:button -->
                            </div>
                            <!-- /wp:buttons -->

                            <!-- wp:heading {"textAlign":"center","level":3,"style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"large"} -->
                            <h3 class="wp-block-heading has-text-align-center has-large-font-size" style="margin-top:24px"><?php echo esc_html_e('Architect &amp; Construction', 'walker-core') ?></h3>
                            <!-- /wp:heading -->
                        </div>
                        <!-- /wp:group -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:column -->

                <!-- wp:column -->
                <div class="wp-block-column"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}},"border":{"radius":"0px"}},"className":"blockpage-portfolio-box","layout":{"type":"constrained"}} -->
                    <div class="wp-block-group blockpage-portfolio-box" style="border-radius:0px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:image {"align":"full","id":268,"width":"undefinedpx","height":"260px","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"0px"}}} -->
                        <figure class="wp-block-image alignfull size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[3]) ?>" alt="" class="wp-image-268" style="border-radius:0px;width:undefinedpx;height:260px" /></figure>
                        <!-- /wp:image -->

                        <!-- wp:group {"className":"blockpage-portfolio-info","layout":{"type":"constrained"}} -->
                        <div class="wp-block-group blockpage-portfolio-info"><!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"}} -->
                            <div class="wp-block-buttons"><!-- wp:button {"backgroundColor":"heading-alt","textColor":"border-color","style":{"border":{"radius":"50px"}},"className":"is-style-button-hover-primary-bgcolor"} -->
                                <div class="wp-block-button is-style-button-hover-primary-bgcolor"><a class="wp-block-button__link has-border-color-color has-heading-alt-background-color has-text-color has-background wp-element-button" style="border-radius:50px"><?php echo esc_html_e('More', 'walker-core') ?></a></div>
                                <!-- /wp:button -->
                            </div>
                            <!-- /wp:buttons -->

                            <!-- wp:heading {"textAlign":"center","level":3,"style":{"spacing":{"margin":{"top":"24px"}}},"fontSize":"large"} -->
                            <h3 class="wp-block-heading has-text-align-center has-large-font-size" style="margin-top:24px"><?php echo esc_html_e('Nature and Animals', 'walker-core') ?></h3>
                            <!-- /wp:heading -->
                        </div>
                        <!-- /wp:group -->
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