<?php

/**
 * Title: Blockpage PRO: Footer Minimal with CTA
 * Slug: walker-core/blockpage-footer-minimal-cta
 * Categories: blockpage-footer, footer
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"0","bottom":"0","left":"0","right":"0"}}},"backgroundColor":"background-alt","layout":{"type":"constrained","contentSize":"100%"}} -->
<div class="wp-block-group has-background-alt-background-color has-background" style="padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><!-- wp:group {"style":{"spacing":{"padding":{"top":"120px","right":"var:preset|spacing|50","left":"var:preset|spacing|50","bottom":"120px"}}},"backgroundColor":"nutral","layout":{"type":"constrained","contentSize":"740px"}} -->
    <div class="wp-block-group has-nutral-background-color has-background" style="padding-top:120px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"flex","orientation":"vertical","justifyContent":"center"}} -->
        <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontSize":"60px","fontStyle":"normal","fontWeight":"500"}}} -->
            <h1 class="wp-block-heading has-text-align-center" style="font-size:60px;font-style:normal;font-weight:500"><?php echo esc_html_e('Let\'s Collaborate &amp; Grow Together', 'walker-core') ?></h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.6"},"spacing":{"margin":{"top":"18px"}}},"textColor":"foreground","fontSize":"normal"} -->
            <p class="has-text-align-center has-foreground-color has-text-color has-normal-font-size" style="margin-top:18px;line-height:1.6"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor.', 'walker-core') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons {"style":{"spacing":{"margin":{"top":"32px"}}}} -->
            <div class="wp-block-buttons" style="margin-top:32px"><!-- wp:button {"textColor":"heading-alt","gradient":"primary-gradient","style":{"border":{"radius":"60px"},"spacing":{"padding":{"left":"35px","right":"35px","top":"20px","bottom":"20px"}}},"className":"is-style-button-hover-white-bgcolor","fontSize":"normal"} -->
                <div class="wp-block-button has-custom-font-size is-style-button-hover-white-bgcolor has-normal-font-size"><a class="wp-block-button__link has-heading-alt-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:60px;padding-top:20px;padding-right:35px;padding-bottom:20px;padding-left:35px"><?php echo esc_html_e('Schedule and Appointment', 'walker-core') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->

    <!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"30px","bottom":"0rem"}},"border":{"top":{"color":"var:preset|color|border-color","width":"1px"}}},"backgroundColor":"nutral","layout":{"type":"constrained","contentSize":"1280px"}} -->
    <div class="wp-block-group has-nutral-background-color has-background" style="border-top-color:var(--wp--preset--color--border-color);border-top-width:1px;padding-top:30px;padding-right:var(--wp--preset--spacing--50);padding-bottom:0rem;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"style":{"spacing":{"padding":{"top":"0"}},"border":{"top":{"style":"none","width":"0px"}}},"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"space-between"}} -->
        <div class="wp-block-group" style="border-top-style:none;border-top-width:0px;padding-top:0"><!-- wp:paragraph {"align":"center","textColor":"foreground"} -->
            <p class="has-text-align-center has-foreground-color has-text-color"><?php echo esc_html_e('Proudly powered by WordPress&nbsp;|&nbsp;Theme: Blockpage by&nbsp;WalkerWP.', 'walker-core') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:social-links {"iconColor":"foreground","iconColorValue":"#c3c2c2","style":{"spacing":{"blockGap":{"top":"var:preset|spacing|20","left":"var:preset|spacing|40"}}},"className":"is-style-logos-only blockpage-socials "} -->
            <ul class="wp-block-social-links has-icon-color is-style-logos-only blockpage-socials"><!-- wp:social-link {"url":"#","service":"instagram"} /-->

                <!-- wp:social-link {"url":"#","service":"facebook"} /-->

                <!-- wp:social-link {"url":"#","service":"twitter"} /-->

                <!-- wp:social-link {"url":"#","service":"linkedin"} /-->
            </ul>
            <!-- /wp:social-links -->
        </div>
        <!-- /wp:group -->
    </div>
    <!-- /wp:group -->

    <!-- wp:group {"layout":{"type":"constrained"}} -->
    <div id="blockpage-cursor" class="wp-block-group"></div>
    <!-- /wp:group -->

    <!-- wp:group {"layout":{"type":"constrained"}} -->
    <div id="blockpage-scrolltop" class="wp-block-group"><!-- wp:buttons -->
        <div class="wp-block-buttons"><!-- wp:button {"textColor":"heading-color","gradient":"primary-gradient","style":{"border":{"radius":"50%"}},"className":"blockpage-scrolltop-button is-style-button-hover-white-bgcolor"} -->
            <div class="wp-block-button blockpage-scrolltop-button is-style-button-hover-white-bgcolor"><a class="wp-block-button__link has-heading-color-color has-primary-gradient-gradient-background has-text-color has-background wp-element-button" style="border-radius:50%" rel="#"><?php echo esc_html_e('Scroll to to Top', 'walker-core') ?></a></div>
            <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->