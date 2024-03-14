<?php

/**
 * Title: Blockpage PRO: Brand Showcase Grid 1
 * Slug: walker-core/blockpage-brands-grid-1
 * Categories: blockpage-brandings
 */
$walkercore_patterns_images = array(
    WALKER_CORE_URL . 'admin/images/blockpage/brand_1.png',
    WALKER_CORE_URL . 'admin/images/blockpage/brand_2.png',
    WALKER_CORE_URL . 'admin/images/blockpage/brand_3.png',
    WALKER_CORE_URL . 'admin/images/blockpage/brand_4.png',
    WALKER_CORE_URL . 'admin/images/blockpage/brand_5.png',
    WALKER_CORE_URL . 'admin/images/blockpage/brand_6.png',
    WALKER_CORE_URL . 'admin/images/blockpage/brand_7.png',
    WALKER_CORE_URL . 'admin/images/blockpage/brand_8.png'
);
?>
<!-- wp:group {"style":{"spacing":{"padding":{"bottom":"120px","top":"60px","left":"var:preset|spacing|50","right":"var:preset|spacing|50"},"margin":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained","contentSize":"1280px"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50);padding-top:60px;padding-right:var(--wp--preset--spacing--50);padding-bottom:120px;padding-left:var(--wp--preset--spacing--50)"><!-- wp:group {"layout":{"type":"constrained","contentSize":"660px"}} -->
    <div class="wp-block-group"><!-- wp:heading {"textAlign":"center","level":4,"style":{"typography":{"fontStyle":"normal","fontWeight":"500"}},"className":"blockpage-heading","fontSize":"xxx-large"} -->
        <h4 class="wp-block-heading has-text-align-center blockpage-heading has-xxx-large-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html_e('Featured Brands', 'walker-core') ?></h4>
        <!-- /wp:heading -->

        <!-- wp:paragraph {"align":"center","style":{"spacing":{"margin":{"top":"50px"}}}} -->
        <p class="has-text-align-center" style="margin-top:50px"><?php echo esc_html_e('Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 'walker-core') ?></p>
        <!-- /wp:paragraph -->
    </div>
    <!-- /wp:group -->

    <!-- wp:gallery {"columns":4,"imageCrop":false,"linkTo":"none","sizeSlug":"full","style":{"spacing":{"margin":{"top":"74px"}}},"className":"is-style-enable-grayscale-mode-on-image blockpage-brands"} -->
    <figure class="wp-block-gallery has-nested-images columns-4 is-style-enable-grayscale-mode-on-image blockpage-brands" style="margin-top:74px"><!-- wp:image {"align":"center","id":1453,"sizeSlug":"full","linkDestination":"none"} -->
        <figure class="wp-block-image aligncenter size-full"><img src="<?php echo esc_url($walkercore_patterns_images[0]) ?>" alt="" class="wp-image-1453" /></figure>
        <!-- /wp:image -->

        <!-- wp:image {"align":"center","id":1454,"sizeSlug":"full","linkDestination":"none"} -->
        <figure class="wp-block-image aligncenter size-full"><img src="<?php echo esc_url($walkercore_patterns_images[1]) ?>" alt="" class="wp-image-1454" /></figure>
        <!-- /wp:image -->

        <!-- wp:image {"align":"center","id":1447,"sizeSlug":"full","linkDestination":"none"} -->
        <figure class="wp-block-image aligncenter size-full"><img src="<?php echo esc_url($walkercore_patterns_images[2]) ?>" alt="" class="wp-image-1447" /></figure>
        <!-- /wp:image -->

        <!-- wp:image {"align":"center","id":1448,"sizeSlug":"full","linkDestination":"none"} -->
        <figure class="wp-block-image aligncenter size-full"><img src="<?php echo esc_url($walkercore_patterns_images[3]) ?>" alt="" class="wp-image-1448" /></figure>
        <!-- /wp:image -->

        <!-- wp:image {"align":"center","id":1449,"sizeSlug":"full","linkDestination":"none"} -->
        <figure class="wp-block-image aligncenter size-full"><img src="<?php echo esc_url($walkercore_patterns_images[4]) ?>" alt="" class="wp-image-1449" /></figure>
        <!-- /wp:image -->

        <!-- wp:image {"id":1474,"sizeSlug":"full","linkDestination":"none"} -->
        <figure class="wp-block-image size-full"><img src="<?php echo esc_url($walkercore_patterns_images[5]) ?>" alt="" class="wp-image-1474" /></figure>
        <!-- /wp:image -->

        <!-- wp:image {"id":1445,"sizeSlug":"full","linkDestination":"none"} -->
        <figure class="wp-block-image size-full"><img src="<?php echo esc_url($walkercore_patterns_images[6]) ?>" alt="" class="wp-image-1445" /></figure>
        <!-- /wp:image -->

        <!-- wp:image {"id":1446,"sizeSlug":"full","linkDestination":"none"} -->
        <figure class="wp-block-image size-full"><img src="<?php echo esc_url($walkercore_patterns_images[7]) ?>" alt="" class="wp-image-1446" /></figure>
        <!-- /wp:image -->
    </figure>
    <!-- /wp:gallery -->
</div>
<!-- /wp:group -->