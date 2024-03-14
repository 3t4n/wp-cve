<?php

/**
 * Title: Blockpage PRO: Banner Layout with Video Background
 * Slug: walker-core/blockpage-banner-with-videobackground
 * Categories: blockpage-banner, banner
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/media_video.mp4',
);
?>
<!-- wp:cover {"url":"<?php echo esc_url($walkercore_patterns_images[0]) ?>","id":1364,"dimRatio":60,"customOverlayColor":"#021337","backgroundType":"video","minHeight":900,"contentPosition":"center center","style":{"spacing":{"padding":{"right":"var:preset|spacing|60","left":"var:preset|spacing|60"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-cover" style="padding-right:var(--wp--preset--spacing--60); padding-left:var(--wp--preset--spacing--60);min-height:900px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-60 has-background-dim" style="background-color:#021337"></span><video class="wp-block-cover__video-background intrinsic-ignore" autoplay muted loop playsinline src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" data-object-fit="cover"></video>
    <div class="wp-block-cover__inner-container"><!-- wp:group {"layout":{"type":"constrained","contentSize":"1080px"}} -->
        <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","style":{"typography":{"fontSize":"84px","fontStyle":"normal","fontWeight":"600","textTransform":"uppercase"},"spacing":{"margin":{"bottom":"var:preset|spacing|50","top":"20px"}}},"textColor":"heading-alt"} -->
            <h2 class="wp-block-heading has-text-align-center has-heading-alt-color has-text-color" style="margin-top:20px;margin-bottom:var(--wp--preset--spacing--50);font-size:84px;font-style:normal;font-weight:600;text-transform:uppercase"><?php echo esc_html_e('Building Digital Worlds', 'walker-core') ?></h2>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
            <p class="has-text-align-center has-medium-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', 'walker-core') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"54px"}}}} -->
            <div class="wp-block-buttons" style="margin-top:54px"><!-- wp:button {"textColor":"heading-alt","style":{"spacing":{"padding":{"left":"35px","right":"35px","top":"20px","bottom":"20px"}},"border":{"radius":"60px","width":"2px"},"color":{"background":"#ffffff00"}},"className":"is-style-button-hover-primary-color","fontSize":"normal"} -->
                <div class="wp-block-button has-custom-font-size is-style-button-hover-primary-color has-normal-font-size"><a class="wp-block-button__link has-heading-alt-color has-text-color has-background wp-element-button" style="border-width:2px;border-radius:60px;background-color:#ffffff00;padding-top:20px;padding-right:35px;padding-bottom:20px;padding-left:35px"><?php echo esc_html_e('Request a Demo', 'walker-core') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:group -->
    </div>
</div>
<!-- /wp:cover -->