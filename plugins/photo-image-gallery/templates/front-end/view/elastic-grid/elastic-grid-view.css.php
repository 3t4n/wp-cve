<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<style>

#uxgallery_container_<?php echo $galleryID; ?> .paginate {
    font-size: <?php echo get_option('uxgallery_ht_view10_paginator_fontsize'); ?>px !important;
    color: <?php echo '#'.get_option('uxgallery_ht_view10_paginator_color'); ?> !important;
    text-align: <?php echo get_option('uxgallery_ht_view10_paginator_position'); ?>;
    margin-top: 15px;
}

#uxgallery_container_<?php echo $galleryID; ?> .paginate5  {
    border-bottom: none !important;
}

#uxgallery_container_<?php echo $galleryID; ?> .icon-style {
    font-size: <?php echo get_option('uxgallery_ht_view10_paginator_icon_size'); ?>px !important;
    color: <?php echo '#'.get_option('uxgallery_ht_view10_paginator_icon_color'); ?> !important;
}

#uxgallery_container_<?php echo $galleryID; ?> {
    position: relative;
    display: inline-block;
    width: 100%;
}

#uxgallery_content_<?php echo $galleryID; ?> .wagwep-container {
 display: none;
}

#uxgallery_content_<?php echo $galleryID; ?> ul.og-grid {
    padding: 0;
<?php if( $uxgallery_get_option['uxgallery_ht_view10_show_center'] == 'on' ): ?> text-align: center;
<?php endif; ?>
}

#uxgallery_content_<?php echo $galleryID; ?> .og-grid > li {
    width: <?php echo $uxgallery_get_option['uxgallery_ht_view10_element_width'] + 2*$uxgallery_get_option['uxgallery_ht_view10_element_border_width']; ?>px;
    height: <?php echo $uxgallery_get_option['uxgallery_ht_view10_element_height'] + 2*$uxgallery_get_option['uxgallery_ht_view10_element_border_width']; ?>px;
<?php $margin = $uxgallery_get_option['uxgallery_ht_view10_element_margin']; ?> margin: <?php echo $margin; ?>px <?php echo $margin/2; ?>px 0 <?php echo $margin/2; ?>px;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-grid > li > a {
    width: 100%;
    height: 100%;
    overflow: hidden;
    box-shadow: none;
    max-height: <?php echo $uxgallery_get_option['uxgallery_ht_view10_element_height'] + 2*$uxgallery_get_option['uxgallery_ht_view10_element_border_width']; ?>px;
    border: solid #<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_border_color']; ?> <?php echo $uxgallery_get_option['uxgallery_ht_view10_element_border_width']; ?>px;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-grid li > a > img {
<?php if( $uxgallery_get_option['uxgallery_ht_view10_image_behaviour'] == 'resize'): ?>
	width: 100%;
    height: 100%;
<?php endif;?>
	max-width: none;
    max-height: none;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-grid > li > a > figure > span {
    padding: <?php echo $uxgallery_get_option['uxgallery_ht_view10_element_title_padding_top_bottom']; ?>px 0;
    margin: <?php echo $uxgallery_get_option['uxgallery_ht_view10_element_title_margin_top']; ?>px 20px 20px 20px;
    color: #<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_title_font_color']; ?>;
    border-bottom: solid #<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_title_border_color']; ?> <?php echo $uxgallery_get_option['uxgallery_ht_view10_element_title_border_width']; ?>px;
    font-size: <?php echo $uxgallery_get_option['uxgallery_ht_view10_element_title_font_size']; ?>px;
    text-align: <?php echo $uxgallery_get_option['uxgallery_ht_view10_element_title_align']; ?>;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-grid > li .og-pointer {
    border-bottom-color: #<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_background_color']; ?>;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-grid > li > a > figure {
    background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split($uxgallery_get_option['uxgallery_ht_view10_element_overlay_background_color_'],2));
				$overlay_opacity=$uxgallery_get_option["uxgallery_ht_view10_element_overlay_opacity"]/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$overlay_opacity.') ';
	?>;
    margin: 0;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander {
    width: <?php echo $uxgallery_get_option[ 'uxgallery_ht_view10_expand_width' ]; ?>%;
    background: <?php
			list($r,$g,$b) = array_map('hexdec',str_split($uxgallery_get_option['uxgallery_ht_view10_expand_block_background_color'],2));
				$expand_opacity=$uxgallery_get_option["uxgallery_ht_view10_expand_block_opacity"]/100;
				echo 'rgba('.$r.','.$g.','.$b.','.$expand_opacity.') ';
	?>;
<?php if( $uxgallery_get_option['uxgallery_ht_view10_show_center'] == 'on' ): ?> left: 50%;
    transform: translateX(-50%);
<?php else: ?> left: 0;
<?php endif; ?>
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander .elastislide-wrapper {
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander ul.elastislide-list {
    padding: 0;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander .infosep{
    display: none;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander ul.elastislide-list li > a > img.selected {
    border: 2px solid #999999;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander ul.elastislide-list li > a {
    width: 100%;
    height: 100%;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander .og-details a.link-button {
    font-size: <?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_button_font_size']; ?>px;
    color: #<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_button_text_color']; ?>;
    background-color: #<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_button_background_color']; ?>;
    box-shadow: none;
    text-decoration: none;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander .og-details a.link-button:hover {
    color: #<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_description_font_hover_color']; ?>;
    background: -webkit-gradient(
        linear, left top, left bottom,
        from(#<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_button_background_hover_color']; ?>),
        to(#<?php echo uxgallery_adjust_brightness($uxgallery_get_option['uxgallery_ht_view10_expand_block_button_background_hover_color'],-50);?>));
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander .og-expander-inner {
    display: inline-block;
    width: 100%;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander .og-details p {
    font-size: <?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_description_font_size']; ?>px;
    color: #<?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_description_font_color']; ?>;
    text-align: <?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_description_text_align']; ?>;
    margin: 0;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander .og-details p ul {
    margin-left: 20px;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander .og-details p li {
    display: list-item;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander .og-close {
    z-index: 999;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander .og-details {
    float: right;
    height: auto;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-expander .og-details > h3 {
    color: #<?php echo $uxgallery_get_option['uxgallery_ht_view10_element_title_font_color']; ?>;
    font-size: <?php echo $uxgallery_get_option['uxgallery_ht_view10_expand_block_title_font_size']; ?>px;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-fullimg {
    height: 100%;
    float: left;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-fullimg img {
    margin-bottom: 25px;
    width: 100%;
}

#uxgallery_content_<?php echo $galleryID; ?> .og-fullimg iframe {
    width: 100%;
    border: none;
}

@media (max-width: 767px){
    #uxgallery_content_<?php echo $galleryID; ?> .og-fullimg {
        height: auto;
    }
}

</style >