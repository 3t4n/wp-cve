<?php

/**
 * Title: Blockpage PRO: Team Grid Layout 4
 * Slug: walker-core/blockpage-team-grid-4
 * Categories: blockpage-team
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/team_1.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/team_2.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/team_3.jpg',
    WALKER_CORE_URL . 'admin/images/blockpage/team_4.jpg',
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"120px","bottom":"120px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"margin":{"top":"0","bottom":"0"}}},"backgroundColor":"background-alt","layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group has-background-alt-background-color has-background" style="margin-top:0;margin-bottom:0;padding-top:120px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"660px"}} -->
    <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":5,"style":{"spacing":{"margin":{"bottom":"24px"}}},"textColor":"primary"} -->
        <h5 class="wp-block-heading has-text-align-center has-primary-color has-text-color" style="margin-bottom:24px"><?php echo esc_html_e('Team', 'walker-core') ?></h5>
        <!-- /wp:heading -->

        <!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"blockpage-heading","fontSize":"xxx-large"} -->
        <h1 class="wp-block-heading has-text-align-center blockpage-heading has-xxx-large-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Meet the People Behind the Success', 'walker-core') ?></h1>
        <!-- /wp:heading -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"style":{"spacing":{"margin":{"top":"100px"},"blockGap":{"left":"42px"}}}} -->
    <div class="wp-block-columns" style="margin-top:100px"><!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"className":"blockpage-team-box-2","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-team-box-2"><!-- wp:group {"className":"blockpage-team-icon-overlap","layout":{"type":"constrained"}} -->
                <div class="wp-block-group blockpage-team-icon-overlap"><!-- wp:image {"align":"center","id":1032,"width":"200px","height":"200px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
                    <figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-1032" style="border-radius:50%;object-fit:cover;width:200px;height:200px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:social-links {"iconColor":"background","iconColorValue":"#0b0b12","iconBackgroundColor":"heading-color","iconBackgroundColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"24px"}}},"className":"is-style-default","layout":{"type":"flex","justifyContent":"center"}} -->
                    <ul class="wp-block-social-links has-icon-color has-icon-background-color is-style-default" style="margin-top:24px"><!-- wp:social-link {"url":"#","service":"spotify"} /--></ul>
                    <!-- /wp:social-links -->
                </div>
                <!-- /wp:group -->

                <!-- wp:group {"style":{"spacing":{"margin":{"top":"40px"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                <div class="wp-block-group" style="margin-top:40px"><!-- wp:heading {"textAlign":"center","level":4} -->
                    <h4 class="wp-block-heading has-text-align-center"><?php echo esc_html_e('Matt Alexendar', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                    <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CEO, Hamm Industry', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"className":"blockpage-team-box-2","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-team-box-2"><!-- wp:group {"className":"blockpage-team-icon-overlap","layout":{"type":"constrained"}} -->
                <div class="wp-block-group blockpage-team-icon-overlap"><!-- wp:image {"align":"center","id":1030,"width":"200px","height":"200px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
                    <figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-1030" style="border-radius:50%;object-fit:cover;width:200px;height:200px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:social-links {"iconColor":"background","iconColorValue":"#0b0b12","iconBackgroundColor":"heading-alt","iconBackgroundColorValue":"#FFFFFE","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"24px"}}},"className":"is-style-default","layout":{"type":"flex","justifyContent":"center"}} -->
                    <ul class="wp-block-social-links has-icon-color has-icon-background-color is-style-default" style="margin-top:24px"><!-- wp:social-link {"url":"#","service":"twitter"} /--></ul>
                    <!-- /wp:social-links -->
                </div>
                <!-- /wp:group -->

                <!-- wp:group {"style":{"spacing":{"margin":{"top":"40px"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                <div class="wp-block-group" style="margin-top:40px"><!-- wp:heading {"textAlign":"center","level":4} -->
                    <h4 class="wp-block-heading has-text-align-center"><?php echo esc_html_e('Medisa Kally', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                    <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CFO, Hamm Industry', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"className":"blockpage-team-box-2","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-team-box-2"><!-- wp:group {"className":"blockpage-team-icon-overlap","layout":{"type":"constrained"}} -->
                <div class="wp-block-group blockpage-team-icon-overlap"><!-- wp:image {"align":"center","id":1031,"width":"200px","height":"200px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
                    <figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[2]) ?>" alt="" class="wp-image-1031" style="border-radius:50%;object-fit:cover;width:200px;height:200px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:social-links {"iconColor":"background","iconColorValue":"#0b0b12","iconBackgroundColor":"heading-alt","iconBackgroundColorValue":"#FFFFFE","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"24px"}}},"className":"is-style-default","layout":{"type":"flex","justifyContent":"center"}} -->
                    <ul class="wp-block-social-links has-icon-color has-icon-background-color is-style-default" style="margin-top:24px"><!-- wp:social-link {"url":"#","service":"linkedin"} /--></ul>
                    <!-- /wp:social-links -->
                </div>
                <!-- /wp:group -->

                <!-- wp:group {"style":{"spacing":{"margin":{"top":"40px"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                <div class="wp-block-group" style="margin-top:40px"><!-- wp:heading {"textAlign":"left","level":4} -->
                    <h4 class="wp-block-heading has-text-align-left"><?php echo esc_html_e('Brown Kytzer', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                    <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CTO, Hamm Industry', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column -->
        <div class="wp-block-column"><!-- wp:group {"className":"blockpage-team-box-2","layout":{"type":"constrained"}} -->
            <div class="wp-block-group blockpage-team-box-2"><!-- wp:group {"className":"blockpage-team-icon-overlap","layout":{"type":"constrained"}} -->
                <div class="wp-block-group blockpage-team-icon-overlap"><!-- wp:image {"align":"center","id":1030,"width":"200px","height":"200px","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"border":{"radius":"50%"}}} -->
                    <figure class="wp-block-image aligncenter size-full is-resized has-custom-border"><img src="<?php echo esc_url($walkercore_patterns_images[3]) ?>" alt="" class="wp-image-1030" style="border-radius:50%;object-fit:cover;width:200px;height:200px" /></figure>
                    <!-- /wp:image -->

                    <!-- wp:social-links {"iconColor":"background","iconColorValue":"#0b0b12","iconBackgroundColor":"heading-alt","iconBackgroundColorValue":"#FFFFFE","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|40"},"margin":{"top":"24px"}}},"className":"is-style-default","layout":{"type":"flex","justifyContent":"center"}} -->
                    <ul class="wp-block-social-links has-icon-color has-icon-background-color is-style-default" style="margin-top:24px"><!-- wp:social-link {"url":"#","service":"instagram"} /--></ul>
                    <!-- /wp:social-links -->
                </div>
                <!-- /wp:group -->

                <!-- wp:group {"style":{"spacing":{"margin":{"top":"40px"},"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
                <div class="wp-block-group" style="margin-top:40px"><!-- wp:heading {"textAlign":"center","level":4} -->
                    <h4 class="wp-block-heading has-text-align-center"><?php echo esc_html_e('Medisa Kally', 'walker-core') ?></h4>
                    <!-- /wp:heading -->

                    <!-- wp:paragraph {"align":"center","fontSize":"x-small"} -->
                    <p class="has-text-align-center has-x-small-font-size"><?php echo esc_html_e('CFO, Hamm Industry', 'walker-core') ?></p>
                    <!-- /wp:paragraph -->
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