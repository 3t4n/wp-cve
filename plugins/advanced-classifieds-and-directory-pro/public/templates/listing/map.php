<?php

/**
 * Map.
 *
 * @link    https://pluginsware.com
 * @since   3.0.0
 *
 * @package Advanced_Classifieds_And_Directory_Pro
 */
?>

<div class="acadp-map acadp-relative acadp-aspect-video" data-type="single">
    <div class="marker" data-latitude="<?php echo esc_attr( $post_meta['latitude'][0] ); ?>" data-longitude="<?php echo esc_attr( $post_meta['longitude'][0] ); ?>"></div> 
    
    <?php 
    // Cookie consent
    include apply_filters( 'acadp_load_template', ACADP_PLUGIN_DIR . 'public/templates/cookie-consent.php' );
    ?>
</div>
