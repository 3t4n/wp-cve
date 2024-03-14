<?php

if ( !defined( 'ABSPATH' ) ) {
    exit;
}

if ( intval( $cab_profile_banner ) > 0 ) {
    $hmcabwImage = wp_get_attachment_image_src( $cab_profile_banner, 'fulll', false );
    $hmcabwPhotograph2 = $hmcabwImage[0];
} else {
    $hmcabwPhotograph2 = HMCABW_ASSETS . 'img/bg-img.jpg';
}

?>
<style type="text/css">
    <?php 
?>
    .hmcabw-main-wrapper .hmcabw-parent-container .hmcabw-info-container p.hmcabw-bio-info,
    .hmcabw-main-wrapper-widget .hmcabw-info-container p.hmcabw-bio-info {
        font-size: <?php 
esc_attr_e( $cab_post_desc_font_size );
?>px;
    }
    .hmcabw-main-wrapper-widget .hmcabw-image-container {
        background: url(<?php 
echo  ( !$cab_hide_banner ? $hmcabwPhotograph2 : 'none' ) ;
?>);
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center center;
    }
    .hmcabw-main-wrapper-widget .hmcabw-image-container img {
        width: <?php 
esc_attr_e( $hmcabw_photo_width );
?>px;
        height: <?php 
esc_attr_e( $hmcabw_photo_width );
?>px;
        bottom: -<?php 
esc_attr_e( $hmcabw_photo_width / 2 + $cab_img_border_width / 2 - 10 );
?>px;
    }
    .hmcabw-main-wrapper-widget .hmcabw-info-container {
        margin-top: <?php 
esc_attr_e( $hmcabw_photo_width / 2 - $cab_img_border_width + 90 );
?>px;
    }
</style>