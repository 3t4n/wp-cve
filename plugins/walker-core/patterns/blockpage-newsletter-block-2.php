<?php

/**
 * Title: Blockpage PRO: Newsletter Block 2
 * Slug: walker-core/blockpage-newsletter-block-2
 * Categories: blockpage-newsletters
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"120px","bottom":"120px"}}},"layout":{"type":"constrained","contentSize":"1180px"}} -->
<div class="wp-block-group" style="padding-top:120px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:columns {"verticalAlignment":"center","style":{"spacing":{"blockGap":{"left":"120px"}}}} -->
    <div class="wp-block-columns are-vertically-aligned-center"><!-- wp:column {"verticalAlignment":"center","width":"45%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:45%"><!-- wp:heading {"textAlign":"left","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"blockpage-heading"} -->
            <h1 class="wp-block-heading has-text-align-left blockpage-heading" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Signup Newsletter', 'walker-core') ?></h1>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"align":"left","style":{"spacing":{"margin":{"bottom":"0px","top":"32px"}},"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
            <p class="has-text-align-left has-normal-font-size" style="margin-top:32px;margin-bottom:0px;line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', 'walker-core') ?></p>
            <!-- /wp:paragraph -->
        </div>
        <!-- /wp:column -->

        <!-- wp:column {"verticalAlignment":"center","width":"55%"} -->
        <div class="wp-block-column is-vertically-aligned-center" style="flex-basis:55%"><!-- wp:group {"style":{"border":{"radius":"7px"},"spacing":{"padding":{"top":"40px","bottom":"20px","left":"60px","right":"60px"}}},"backgroundColor":"background-alt","layout":{"type":"constrained"}} -->
            <div class="wp-block-group has-background-alt-background-color has-background" style="border-radius:7px;padding-top:40px;padding-right:60px;padding-bottom:20px;padding-left:60px"><!-- wp:contact-form-7/contact-form-selector {"id":1760,"hash":"0708ec1","title":"Newsletter","className":"blockpage-newsletter-form-2"} -->
                <div class="wp-block-contact-form-7-contact-form-selector blockpage-newsletter-form-2">[contact-form-7 id="0708ec1" title="Newsletter"]</div>
                <!-- /wp:contact-form-7/contact-form-selector -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:column -->
    </div>
    <!-- /wp:columns -->
</div>
<!-- /wp:group -->