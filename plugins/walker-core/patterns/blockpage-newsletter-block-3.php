<?php

/**
 * Title: Blockpage PRO: Newsletter Block 3
 * Slug: walker-core/blockpage-newsletter-block-3
 * Categories: blockpage-newsletters
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"right":"var:preset|spacing|50","left":"var:preset|spacing|50","top":"120px","bottom":"120px"}}},"layout":{"type":"constrained","contentSize":"760px"}} -->
<div class="wp-block-group" style="padding-top:120px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:heading {"textAlign":"center","level":1,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"blockpage-heading"} -->
    <h1 class="wp-block-heading has-text-align-center blockpage-heading" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Signup Newsletter', 'walker-core') ?></h1>
    <!-- /wp:heading -->

    <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"bottom":"0px","top":"50px"}},"typography":{"lineHeight":"1.5"}},"fontSize":"normal"} -->
    <p class="has-text-align-center has-normal-font-size" style="margin-top:50px;margin-bottom:0px;line-height:1.5"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', 'walker-core') ?></p>
    <!-- /wp:paragraph -->

    <!-- wp:group {"style":{"border":{"radius":"7px"},"spacing":{"padding":{"top":"0px","bottom":"0px","left":"0px","right":"0px"}}},"layout":{"type":"constrained"}} -->
    <div class="wp-block-group" style="border-radius:7px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:contact-form-7/contact-form-selector {"id":1760,"hash":"0708ec1","title":"Newsletter","className":"blockpage-newsletter-form-2"} -->
        <div class="wp-block-contact-form-7-contact-form-selector blockpage-newsletter-form-2">[contact-form-7 id="0708ec1" title="Newsletter"]</div>
        <!-- /wp:contact-form-7/contact-form-selector -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->