<?php

/**
 * Title: Blockpage PRO: Team Grid Layout 3
 * Slug: walker-core/blockpage-team-grid-3
 * Categories: blockpage-team
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/team_1.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/team_2.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/team_3.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/team_4.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/team_5.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/team_6.jpg',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"120px","bottom":"120px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"margin":{"top":"0","bottom":"0"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="margin-top:0;margin-bottom:0;padding-top:120px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"660px"}} -->
    <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":5,"style":{"spacing":{"margin":{"bottom":"24px"}}},"textColor":"primary"} -->
        <h5 class="wp-block-heading has-text-align-center has-primary-color has-text-color" style="margin-bottom:24px"><?php echo esc_html_e('Team', 'walker-core') ?></h5>
        <!-- /wp:heading -->

        <!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"blockpage-heading","fontSize":"xxx-large"} -->
        <h1 class="wp-block-heading has-text-align-center blockpage-heading has-xxx-large-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Meet the People Behind the Success', 'walker-core') ?></h1>
        <!-- /wp:heading -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"margin":{"top":"84px"},"blockGap":{"left":"50px"},"padding":{"right":"0","left":"0"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-top" style="margin-top:84px;padding-right:0;padding-left:0"><!-- wp:column {"verticalAlignment":"top"} -->
        <div class="wp-block-column is-vertically-aligned-top"><!-- wp:group {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}}},"layout":{"type":"constrained"}} -->
            <div class="wp-block-group" style="border-radius:12px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:group {"layout":{"type":"constrained"}} -->
                <div class="wp-block-group"><!-- wp:image {"align":"center","id":1032,"width":"150px","height":"150px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
                    <figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-1032" style="border-radius:50%;object-fit:cover;width:150px;height:150px" /></figure>
                    <!-- /wp:image -->
                </div>
                <!-- /wp:group -->

                <!-- wp:group {"style":{"spacing":{"margin":{"top":"30px"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                <div class="wp-block-group" style="margin-top:30px"><!-- wp:heading {"textAlign":"center","level":4} -->
                    <h4 class="wp-block-heading has-text-align-center"><?php echo esc_html_e('Matt Alexendar', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                    <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CEO, Hamm Industry', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->

                <!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
                <p class="has-text-align-center has-normal-font-size" style="line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:social-links {"iconColor":"heading-color","iconColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"32px"}}},"className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"}} -->
                <ul class="wp-block-social-links has-icon-color is-style-logos-only" style="margin-top:32px"><!-- wp:social-link {"url":"#","service":"spotify"} /-->

                    <!-- wp:social-link {"url":"#","service":"twitter"} /-->

                    <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                </ul>
                <!-- /wp:social-links -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"top"} -->
        <div class="wp-block-column is-vertically-aligned-top"><!-- wp:group {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}}},"layout":{"type":"constrained"}} -->
            <div class="wp-block-group" style="border-radius:12px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:group {"layout":{"type":"constrained"}} -->
                <div class="wp-block-group"><!-- wp:image {"align":"center","id":1030,"width":"150px","height":"150px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
                    <figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-1030" style="border-radius:50%;object-fit:cover;width:150px;height:150px" /></figure>
                    <!-- /wp:image -->
                </div>
                <!-- /wp:group -->

                <!-- wp:group {"style":{"spacing":{"margin":{"top":"30px"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                <div class="wp-block-group" style="margin-top:30px"><!-- wp:heading {"textAlign":"center","level":4} -->
                    <h4 class="wp-block-heading has-text-align-center"><?php echo esc_html_e('Medina Lott', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                    <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CEO, Hamm Industry', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->

                <!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
                <p class="has-text-align-center has-normal-font-size" style="line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:social-links {"iconColor":"heading-color","iconColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"32px"}}},"className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"}} -->
                <ul class="wp-block-social-links has-icon-color is-style-logos-only" style="margin-top:32px"><!-- wp:social-link {"url":"#","service":"spotify"} /-->

                    <!-- wp:social-link {"url":"#","service":"twitter"} /-->

                    <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                </ul>
                <!-- /wp:social-links -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"top"} -->
        <div class="wp-block-column is-vertically-aligned-top"><!-- wp:group {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}}},"layout":{"type":"constrained"}} -->
            <div class="wp-block-group" style="border-radius:12px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:group {"layout":{"type":"constrained"}} -->
                <div class="wp-block-group"><!-- wp:image {"align":"center","id":1031,"width":"150px","height":"150px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
                    <figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[2]) ?>" alt="" class="wp-image-1031" style="border-radius:50%;object-fit:cover;width:150px;height:150px" /></figure>
                    <!-- /wp:image -->
                </div>
                <!-- /wp:group -->

                <!-- wp:group {"style":{"spacing":{"margin":{"top":"30px"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                <div class="wp-block-group" style="margin-top:30px"><!-- wp:heading {"textAlign":"center","level":4} -->
                    <h4 class="wp-block-heading has-text-align-center"><?php echo esc_html_e('Mathew H', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                    <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CEO, Hamm Industry', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->

                <!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
                <p class="has-text-align-center has-normal-font-size" style="line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:social-links {"iconColor":"heading-color","iconColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"32px"}}},"className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"}} -->
                <ul class="wp-block-social-links has-icon-color is-style-logos-only" style="margin-top:32px"><!-- wp:social-link {"url":"#","service":"spotify"} /-->

                    <!-- wp:social-link {"url":"#","service":"twitter"} /-->

                    <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                </ul>
                <!-- /wp:social-links -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"top"} -->
        <div class="wp-block-column is-vertically-aligned-top"><!-- wp:group {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}}},"layout":{"type":"constrained"}} -->
            <div class="wp-block-group" style="border-radius:12px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:group {"layout":{"type":"constrained"}} -->
                <div class="wp-block-group"><!-- wp:image {"align":"center","id":1509,"width":"150px","height":"150px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
                    <figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[3]) ?>" alt="" class="wp-image-1509" style="border-radius:50%;object-fit:cover;width:150px;height:150px" /></figure>
                    <!-- /wp:image -->
                </div>
                <!-- /wp:group -->

                <!-- wp:group {"style":{"spacing":{"margin":{"top":"30px"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                <div class="wp-block-group" style="margin-top:30px"><!-- wp:heading {"textAlign":"center","level":4} -->
                    <h4 class="wp-block-heading has-text-align-center"><?php echo esc_html_e('Noyal Kor', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                    <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CEO, Hamm Industry', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->

                <!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
                <p class="has-text-align-center has-normal-font-size" style="line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', 'walker-core') ?></p>
                <!-- /wp:paragraph -->

                <!-- wp:social-links {"iconColor":"heading-color","iconColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"32px"}}},"className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"}} -->
                <ul class="wp-block-social-links has-icon-color is-style-logos-only" style="margin-top:32px"><!-- wp:social-link {"url":"#","service":"spotify"} /-->

                    <!-- wp:social-link {"url":"#","service":"twitter"} /-->

                    <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                </ul>
                <!-- /wp:social-links -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->

    <!-- wp:group {"style":{"spacing":{"margin":{"top":"100px"}}},"layout":{"type":"constrained","contentSize":"1060px"}} -->
    <div class="wp-block-group" style="margin-top:100px"><!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"margin":{"top":"84px"},"blockGap":{"left":"100px"},"padding":{"right":"0","left":"0"}}}} -->
        <div class="wp-block-columns are-vertically-aligned-top" style="margin-top:84px;padding-right:0;padding-left:0"><!-- wp:column {"verticalAlignment":"top"} -->
            <div class="wp-block-column is-vertically-aligned-top"><!-- wp:group {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-group" style="border-radius:12px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:group {"layout":{"type":"constrained"}} -->
                    <div class="wp-block-group"><!-- wp:image {"align":"center","id":1030,"width":"150px","height":"150px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
                        <figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[4]) ?>" alt="" class="wp-image-1030" style="border-radius:50%;object-fit:cover;width:150px;height:150px" /></figure>
                        <!-- /wp:image -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"30px"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                    <div class="wp-block-group" style="margin-top:30px"><!-- wp:heading {"textAlign":"center","level":4} -->
                        <h4 class="wp-block-heading has-text-align-center"><?php echo esc_html_e('Medina Lott', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                        <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CEO, Hamm Industry', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
                    <p class="has-text-align-center has-normal-font-size" style="line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:social-links {"iconColor":"heading-color","iconColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"32px"}}},"className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"}} -->
                    <ul class="wp-block-social-links has-icon-color is-style-logos-only" style="margin-top:32px"><!-- wp:social-link {"url":"#","service":"spotify"} /-->

                        <!-- wp:social-link {"url":"#","service":"twitter"} /-->

                        <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                    </ul>
                    <!-- /wp:social-links -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"verticalAlignment":"top"} -->
            <div class="wp-block-column is-vertically-aligned-top"><!-- wp:group {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-group" style="border-radius:12px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:group {"layout":{"type":"constrained"}} -->
                    <div class="wp-block-group"><!-- wp:image {"align":"center","id":1031,"width":"150px","height":"150px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
                        <figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[5]) ?>" alt="" class="wp-image-1031" style="border-radius:50%;object-fit:cover;width:150px;height:150px" /></figure>
                        <!-- /wp:image -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"30px"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                    <div class="wp-block-group" style="margin-top:30px"><!-- wp:heading {"textAlign":"center","level":4} -->
                        <h4 class="wp-block-heading has-text-align-center"><?php echo esc_html_e('Mathew H', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                        <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CEO, Hamm Industry', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
                    <p class="has-text-align-center has-normal-font-size" style="line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:social-links {"iconColor":"heading-color","iconColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"32px"}}},"className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"}} -->
                    <ul class="wp-block-social-links has-icon-color is-style-logos-only" style="margin-top:32px"><!-- wp:social-link {"url":"#","service":"spotify"} /-->

                        <!-- wp:social-link {"url":"#","service":"twitter"} /-->

                        <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                    </ul>
                    <!-- /wp:social-links -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->

            <!-- wp:column {"verticalAlignment":"top"} -->
            <div class="wp-block-column is-vertically-aligned-top"><!-- wp:group {"style":{"border":{"radius":"12px"},"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}}},"layout":{"type":"constrained"}} -->
                <div class="wp-block-group" style="border-radius:12px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:group {"layout":{"type":"constrained"}} -->
                    <div class="wp-block-group"><!-- wp:image {"align":"center","id":1509,"width":"150px","height":"150px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
                        <figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-1509" style="border-radius:50%;object-fit:cover;width:150px;height:150px" /></figure>
                        <!-- /wp:image -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"style":{"spacing":{"margin":{"top":"30px"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                    <div class="wp-block-group" style="margin-top:30px"><!-- wp:heading {"textAlign":"center","level":4} -->
                        <h4 class="wp-block-heading has-text-align-center"><?php echo esc_html_e('Noyal Kor', 'walker-core') ?></h4>
                        <!-- /wp:heading -->

                        <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                        <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CEO, Hamm Industry', 'walker-core') ?></p>
                        <!-- /wp:paragraph -->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
                    <p class="has-text-align-center has-normal-font-size" style="line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->

                    <!-- wp:social-links {"iconColor":"heading-color","iconColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"32px"}}},"className":"is-style-logos-only","layout":{"type":"flex","justifyContent":"center"}} -->
                    <ul class="wp-block-social-links has-icon-color is-style-logos-only" style="margin-top:32px"><!-- wp:social-link {"url":"#","service":"spotify"} /-->

                        <!-- wp:social-link {"url":"#","service":"twitter"} /-->

                        <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
                    </ul>
                    <!-- /wp:social-links -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:column -->
        </div>
        <!-- /wp:columns -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->