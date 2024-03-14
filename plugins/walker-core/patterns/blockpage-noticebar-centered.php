<?php

/**
 * Title: Blockpage PRO: Offer Announcement Bar Text Centered
 * Slug: walker-core/blockpage-noticebar-centered
 * Categories: blockpage-notice
 */
?>
<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|40","bottom":"var:preset|spacing|40","left":"var:preset|spacing|50","right":"var:preset|spacing|50"}}},"gradient":"primary-gradient","layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group has-primary-gradient-gradient-background has-background" style="padding-top:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--40);padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","justifyContent":"center"}} -->
    <div class="wp-block-group"><!-- wp:heading {"textAlign":"left","level":5,"textColor":"heading-color","fontSize":"normal"} -->
        <h5 class="wp-block-heading has-text-align-left has-heading-color-color has-text-color has-normal-font-size"><?php echo esc_html_e('Get your business online and grow your business with Blockpage!', 'walker-core') ?> </h5>
        <!-- /wp:heading -->

        <!-- wp:buttons -->
        <div class="wp-block-buttons"><!-- wp:button {"textAlign":"left","textColor":"heading-color","style":{"spacing":{"padding":{"left":"0","right":"0","top":"0","bottom":"0"}},"border":{"radius":"0px","bottom":{"width":"1px"}},"color":{"background":"#ffffff00"}},"className":"is-style-button-hover-primary-color","fontSize":"normal"} -->
            <div class="wp-block-button has-custom-font-size is-style-button-hover-primary-color has-normal-font-size"><a class="wp-block-button__link has-heading-color-color has-text-color has-background has-text-align-left wp-element-button" style="border-radius:0px;border-bottom-width:1px;background-color:#ffffff00;padding-top:0;padding-right:0;padding-bottom:0;padding-left:0"><?php echo esc_html_e('Download Now', 'walker-core') ?></a></div>
            <!-- /wp:button -->
        </div>
        <!-- /wp:buttons -->
    </div>
    <!-- /wp:group -->
</div>
<!-- /wp:group -->