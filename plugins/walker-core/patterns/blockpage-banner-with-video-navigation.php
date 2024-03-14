<?php

/**
 * Title: Blockpage PRO: Banner Layout with Video Background and Navigation
 * Slug: walker-core/blockpage-banner-with-video-navigation
 * Categories: blockpage-banner, banner, blockpage-header, header
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/media_video.mp4',
);
?>
<!-- wp:cover {"url":"<?php echo esc_url($walkercore_patterns_images[0]) ?>","id":1364,"dimRatio":60,"customOverlayColor":"#021337","backgroundType":"video","minHeight":900,"contentPosition":"center center","style":{"spacing":{"padding":{"right":"var:preset|spacing|60","left":"var:preset|spacing|60","bottom":"0px"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-cover" style="padding-right:var(--wp--preset--spacing--60);padding-bottom:0px;padding-left:var(--wp--preset--spacing--60);min-height:900px"><span aria-hidden="true" class="wp-block-cover__background has-background-dim-60 has-background-dim" style="background-color:#021337"></span><video class="wp-block-cover__video-background intrinsic-ignore" autoplay muted loop playsinline src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" data-object-fit="cover"></video>
    <div class="wp-block-cover__inner-container"><!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|20","bottom":"var:preset|spacing|20","left":"0","right":"0"}}},"layout":{"type":"constrained","contentSize":"100%"}} -->
        <div class="wp-block-group" style="padding-top:var(--wp--preset--spacing--20);padding-right:0;padding-bottom:var(--wp--preset--spacing--20);padding-left:0"><!-- wp:group {"style":{"spacing":{"padding":{"top":"25px","right":"0","bottom":"25px","left":"0"}},"border":{"bottom":{"width":"0px","style":"none"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
            <div class="wp-block-group" style="border-bottom-style:none;border-bottom-width:0px;padding-top:25px;padding-right:0;padding-bottom:25px;padding-left:0"><!-- wp:group {"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"space-between"}} -->
                <div class="wp-block-group"><!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|40"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group"><!-- wp:site-logo {"width":40,"shouldSyncIcon":false} /-->

                        <!-- wp:site-title {"style":{"typography":{"fontStyle":"normal","fontWeight":"600","textTransform":"uppercase","letterSpacing":"3px","fontSize":"24px"},"elements":{"link":{"color":{"text":"var:preset|color|heading-color"}}}}} /-->
                    </div>
                    <!-- /wp:group -->

                    <!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap"}} -->
                    <div class="wp-block-group"><!-- wp:navigation {"textColor":"heading-color","overlayBackgroundColor":"secondary","overlayTextColor":"heading-alt","layout":{"type":"flex","justifyContent":"center"},"fontSize":"normal"} -->
                        <!-- wp:page-list /-->
                        <!-- /wp:navigation -->

                        <!-- wp:social-links {"iconColor":"heading-color","iconColorValue":"#FFFFFF","style":{"spacing":{"blockGap":{"left":"var:preset|spacing|30"}}},"className":"is-style-logos-only blockpage-socials"} -->
                        <ul class="wp-block-social-links has-icon-color is-style-logos-only blockpage-socials"><!-- wp:social-link {"url":"#","service":"facebook"} /-->

                            <!-- wp:social-link {"url":"#","service":"instagram"} /-->

                            <!-- wp:social-link {"url":"#","service":"twitter"} /-->

                            <!-- wp:social-link {"url":"#","service":"dribbble"} /-->
                        </ul>
                        <!-- /wp:social-links -->
                    </div>
                    <!-- /wp:group -->
                </div>
                <!-- /wp:group -->
            </div>
            <!-- /wp:group -->
        </div>
        <!-- /wp:group -->

        <!-- wp:spacer {"height":"180px"} -->
        <div style="height:180px" aria-hidden="true" class="wp-block-spacer"></div>
        <!-- /wp:spacer -->

        <!-- wp:group {"layout":{"type":"constrained","contentSize":"1080px"}} -->
        <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","style":{"typography":{"fontSize":"84px","fontStyle":"normal","fontWeight":"600","textTransform":"uppercase"},"spacing":{"margin":{"bottom":"var:preset|spacing|50","top":"20px"}}},"textColor":"heading-alt"} -->
            <h2 class="wp-block-heading has-text-align-center has-heading-alt-color has-text-color" style="margin-top:20px;margin-bottom:var(--wp--preset--spacing--50);font-size:84px;font-style:normal;font-weight:600;text-transform:uppercase"><?php echo esc_html_e('Building Digital Worlds', 'blockpage') ?></h2>
            <!-- /wp:heading -->

            <!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
            <p class="has-text-align-center has-medium-font-size"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore.', 'blockpage') ?></p>
            <!-- /wp:paragraph -->

            <!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"54px"}}}} -->
            <div class="wp-block-buttons" style="margin-top:54px"><!-- wp:button {"textColor":"heading-alt","style":{"spacing":{"padding":{"left":"35px","right":"35px","top":"20px","bottom":"20px"}},"border":{"radius":"60px","width":"2px"},"color":{"background":"#ffffff00"}},"className":"is-style-button-hover-primary-color","fontSize":"normal"} -->
                <div class="wp-block-button has-custom-font-size is-style-button-hover-primary-color has-normal-font-size"><a class="wp-block-button__link has-heading-alt-color has-text-color has-background wp-element-button" style="border-width:2px;border-radius:60px;background-color:#ffffff00;padding-top:20px;padding-right:35px;padding-bottom:20px;padding-left:35px"><?php echo esc_html_e('Request a Demo', 'blockpage') ?></a></div>
                <!-- /wp:button -->
            </div>
            <!-- /wp:buttons -->
        </div>
        <!-- /wp:group -->

        <!-- wp:spacer {"height":"180px"} -->
        <div style="height:180px" aria-hidden="true" class="wp-block-spacer"></div>
        <!-- /wp:spacer -->
    </div>
</div>
<!-- /wp:cover -->