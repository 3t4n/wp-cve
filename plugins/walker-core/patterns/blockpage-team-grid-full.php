<?php

/**
 * Title: Blockpage PRO: Team Grid Full Layout
 * Slug: walker-core/blockpage-team-grid-full
 * Categories: blockpage-team
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/team_1.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/team_2.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/team_3.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/team_4.jpg',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"120px","bottom":"120px","left":"0","right":"0"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"background-alt","layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group has-background-alt-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:120px;padding-right:0;padding-bottom:120px;padding-left:0"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"660px"}} -->
    <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":5,"style":{"spacing":{"margin":{"bottom":"24px"}}},"textColor":"primary"} -->
        <h5 class="wp-block-heading has-text-align-center has-primary-color has-text-color" style="margin-bottom:24px"><?php echo esc_html_e('Team', 'walker-core') ?></h5>
        <!-- /wp:heading -->

        <!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"blockpage-heading","fontSize":"xxx-large"} -->
        <h1 class="wp-block-heading has-text-align-center blockpage-heading has-xxx-large-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Meet the People Behind the Success', 'walker-core') ?></h1>
        <!-- /wp:heading -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"style":{"spacing":{"margin":{"top":"74px"},"blockGap":{"left":"0px"}}}} -->
    <div class="wp-block-columns" style="margin-top:74px"><!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"className":"blockpage-team-box-3","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-team-box-3"><!-- wp:group {"className":"blockpage-team-overlay","layout":{"type":"constrained"}} -->
                <div class="wp-block-group blockpage-team-overlay"><!-- wp:image {"id":1032,"width":"400px","height":"460px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-1032" style="object-fit:cover;width:400px;height:460px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0px"},"blockGap":"var:preset|spacing|40"}},"className":"team-overlay-content","layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                    <div class="wp-block-group team-overlay-content" style="margin-top:0px"><!-- wp:heading {"textAlign":"left","level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"large"} -->
                        <h4 class="wp-block-heading has-text-align-left has-large-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Matt Alexendar', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                        <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CEO, Hamm Industry', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->

                        <!-- wp:social-links {"iconColor":"heading-color","iconColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"10px"}}},"className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"}} -->
                        <ul class="wp-block-social-links has-icon-color is-style-logos-only" style="margin-top:10px"><!-- wp:social-link {"url":"#","service":"spotify"} /-->

                            <!-- wp:social-link {"url":"#","service":"twitter"} /-->

                            <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                        </ul>
                        <!-- /wp:social-links -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"className":"blockpage-team-box-3","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-team-box-3"><!-- wp:group {"className":"blockpage-team-overlay","layout":{"type":"constrained"}} -->
                <div class="wp-block-group blockpage-team-overlay"><!-- wp:image {"id":1030,"width":"400px","height":"460px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-1030" style="object-fit:cover;width:400px;height:460px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0px"},"blockGap":"var:preset|spacing|40"}},"className":"team-overlay-content","layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                    <div class="wp-block-group team-overlay-content" style="margin-top:0px"><!-- wp:heading {"textAlign":"center","level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"large"} -->
                        <h4 class="wp-block-heading has-text-align-center has-large-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Medisa Kally', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                        <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CFO, Hamm Industry', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->

                        <!-- wp:social-links {"iconColor":"heading-color","iconColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"10px"}}},"className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"}} -->
                        <ul class="wp-block-social-links has-icon-color is-style-logos-only" style="margin-top:10px"><!-- wp:social-link {"url":"#","service":"spotify"} /-->

                            <!-- wp:social-link {"url":"#","service":"twitter"} /-->

                            <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                        </ul>
                        <!-- /wp:social-links -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"className":"blockpage-team-box-3","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-team-box-3"><!-- wp:group {"className":"blockpage-team-overlay","layout":{"type":"constrained"}} -->
                <div class="wp-block-group blockpage-team-overlay"><!-- wp:image {"id":1509,"width":"400px","height":"460px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[2]) ?>" alt="" class="wp-image-1509" style="object-fit:cover;width:400px;height:460px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0px"},"blockGap":"var:preset|spacing|40"}},"className":"team-overlay-content","layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                    <div class="wp-block-group team-overlay-content" style="margin-top:0px"><!-- wp:heading {"textAlign":"center","level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"large"} -->
                        <h4 class="wp-block-heading has-text-align-center has-large-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Medisa Kally', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                        <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CFO, Hamm Industry', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->

                        <!-- wp:social-links {"iconColor":"heading-color","iconColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"10px"}}},"className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"}} -->
                        <ul class="wp-block-social-links has-icon-color is-style-logos-only" style="margin-top:10px"><!-- wp:social-link {"url":"#","service":"spotify"} /-->

                            <!-- wp:social-link {"url":"#","service":"twitter"} /-->

                            <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                        </ul>
                        <!-- /wp:social-links -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"className":"blockpage-team-box-3","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-team-box-3"><!-- wp:group {"className":"blockpage-team-overlay","layout":{"type":"constrained"}} -->
                <div class="wp-block-group blockpage-team-overlay"><!-- wp:image {"id":1031,"width":"400px","height":"460px","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
                    <figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url($walkercore_patterns_images[3]) ?>" alt="" class="wp-image-1031" style="object-fit:cover;width:400px;height:460px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"0px"},"blockGap":"var:preset|spacing|40"}},"className":"team-overlay-content","layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                    <div class="wp-block-group team-overlay-content" style="margin-top:0px"><!-- wp:heading {"textAlign":"left","level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"fontSize":"large"} -->
                        <h4 class="wp-block-heading has-text-align-left has-large-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Brown Kytzer', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                        <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CTO, Hamm Industry', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->

                        <!-- wp:social-links {"iconColor":"heading-color","iconColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"10px"}}},"className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"}} -->
                        <ul class="wp-block-social-links has-icon-color is-style-logos-only" style="margin-top:10px"><!-- wp:social-link {"url":"#","service":"spotify"} /-->

                            <!-- wp:social-link {"url":"#","service":"twitter"} /-->

                            <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                        </ul>
                        <!-- /wp:social-links -->
                    </div>
                    <!-- /wp:group -->
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