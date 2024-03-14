<?php

/**
 * Title: Blockpage PRO: Conatct Block with Form Style 2
 * Slug: walker-core/blockpage-contact-block-with-form-2
 * Categories: blockpage-conatctblock
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"120px","bottom":"120px"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="padding-top:120px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"constrained","contentSize":"760px"}} -->
    <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":5,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"textColor":"primary","fontSize":"small"} -->
        <h5 class="wp-block-heading has-text-align-center has-primary-color has-text-color has-small-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Contact Us', 'walker-core') ?></h5>
        <!-- /wp:heading -->

        <!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"blockpage-heading"} -->
        <h1 class="wp-block-heading has-text-align-center blockpage-heading" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Keep in Touch', 'walker-core') ?></h1>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"align":"center","style":{"typography":{"lineHeight":"1.5"},"spacing":{"margin":{"top":"50px"}}},"fontSize":"normal"} -->
        <p class="has-text-align-center has-normal-font-size" style="margin-top:50px;line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->

    <!-- wp:columns {"verticalAlignment":"top","style":{"spacing":{"blockGap":{"left":"140px"},"margin":{"top":"60px"},"padding":{"top":"60px"}},"border":{"top":{"color":"var:preset|color|border-color","width":"1px"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-top" style="border-top-color:var(--wp--preset--color--border-color);border-top-width:1px;margin-top:60px;padding-top:60px"><!-- wp:column {"verticalAlignment":"top","width":"45%","style":{"spacing":{"blockGap":"var:preset|spacing|40"}}} -->
        <div class="wp-block-column is-vertically-aligned-top" style="flex-basis:45%"><!-- wp:group {"style":{"spacing":{"margin":{"top":"42px"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group" style="margin-top:42px"><!-- wp:heading {"level":4,"fontSize":"medium"} -->
                <h4 class="wp-block-heading has-medium-font-size"><?php echo esc_html_e('Business Address:', 'walker-core') ?></h4>
                <!-- /wp:heading -->

                <!-- wp:list {"style":{"typography":{"fontStyle":"normal","fontWeight":"400"},"spacing":{"padding":{"right":"0","left":"0"}}},"textColor":"foreground","className":"is-style-list-style-no-bullet","fontSize":"medium"} -->
                <ul class="is-style-list-style-no-bullet has-foreground-color has-text-color has-medium-font-size" style="padding-right:0;padding-left:0;font-style:normal;font-weight:400"><!-- wp:list-item {"fontSize":"small"} -->
                    <li class="has-small-font-size"><?php echo esc_html_e('Metrotech Center, Brooklyn,&nbsp; NY 11201, USA', 'walker-core') ?></li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"style":{"spacing":{"margin":{"top":"32px"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group" style="margin-top:32px"><!-- wp:heading {"level":4,"fontSize":"medium"} -->
                <h4 class="wp-block-heading has-medium-font-size"><?php echo esc_html_e('Phone:', 'walker-core') ?></h4>
                <!-- /wp:heading -->

                <!-- wp:list {"style":{"typography":{"fontStyle":"normal","fontWeight":"400"},"spacing":{"padding":{"right":"0","left":"0"}}},"textColor":"foreground","className":"is-style-list-style-no-bullet","fontSize":"medium"} -->
                <ul class="is-style-list-style-no-bullet has-foreground-color has-text-color has-medium-font-size" style="padding-right:0;padding-left:0;font-style:normal;font-weight:400"><!-- wp:list-item {"style":{"typography":{"fontStyle":"normal","fontWeight":"400"}},"fontSize":"normal"} -->
                    <li class="has-normal-font-size" style="font-style:normal;font-weight:400"><?php echo esc_html_e('+1 (012) 345-6789', 'walker-core') ?></li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->
            </div>
            <!-- /wp:group -->

            <!-- wp:group {"style":{"spacing":{"margin":{"top":"32px"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","orientation":"vertical"}} -->
            <div class="wp-block-group" style="margin-top:32px"><!-- wp:heading {"level":4,"fontSize":"medium"} -->
                <h4 class="wp-block-heading has-medium-font-size"><?php echo esc_html_e('Email:', 'walker-core') ?></h4>
                <!-- /wp:heading -->

                <!-- wp:list {"style":{"typography":{"fontStyle":"normal","fontWeight":"400"},"spacing":{"padding":{"right":"0","left":"0"}}},"textColor":"foreground","className":"is-style-list-style-no-bullet","fontSize":"medium"} -->
                <ul class="is-style-list-style-no-bullet has-foreground-color has-text-color has-medium-font-size" style="padding-right:0;padding-left:0;font-style:normal;font-weight:400"><!-- wp:list-item {"style":{"typography":{"fontStyle":"normal","fontWeight":"400"}},"fontSize":"normal"} -->
                    <li class="has-normal-font-size" style="font-style:normal;font-weight:400"><?php echo esc_html_e('email@example.com', 'walker-core') ?></li>
                    <!-- /wp:list-item -->
                </ul>
                <!-- /wp:list -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"top","width":"55%","style":{"spacing":{"padding":{"right":"0","left":"0"}}}} -->
        <div class="wp-block-column is-vertically-aligned-top" style="padding-right:0;padding-left:0;flex-basis:55%"><!-- wp:heading {"level":4} -->
            <h4 class="wp-block-heading"><?php echo esc_html_e('Send us an Enquiry', 'walker-core') ?></h4>
            <!-- /wp:heading -->

            <!-- wp:contact-form-7/contact-form-selector {"id":1759,"hash":"72e5d3d","title":"Contact form 1","className":"blockpage-contact-form"} -->
            <div class="wp-block-contact-form-7-contact-form-selector blockpage-contact-form">[contact-form-7 id="72e5d3d" title="Contact form 1"]</div>
            <!-- /wp:contact-form-7/contact-form-selector -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->