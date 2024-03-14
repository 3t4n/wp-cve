<?php $settings->icon_img_border_radius_njba = (array)$settings->icon_img_border_radius_njba; ?>
<?php $settings->img_icon_padding = (array)$settings->img_icon_padding; ?>
<?php $settings->img_icon_margin = (array)$settings->img_icon_margin; ?>
<?php $settings->icon_size = (array)$settings->icon_size; ?>
.fl-node-<?php echo $id; ?> .njba-icon-img {
<?php if($settings->overall_alignment_img_icon === 'center') { ?> float: none;
<?php } ?><?php if($settings->overall_alignment_img_icon === 'left') { ?> float: left;
<?php } ?><?php if($settings->overall_alignment_img_icon === 'right') { ?> float: right;
<?php } ?><?php if($settings->overall_alignment_img_icon ){ ?> text-align: center;
<?php } ?>
}

<?php if($settings->img_icon_show_border === 'yes'){ ?>
.fl-node-<?php echo $id ?> .njba-icon-img {
<?php if($settings->img_icon_border_width !== '' ){ echo 'border: '.$settings->img_icon_border_width.'px;'; } else { echo 'border: 1px;'; }?><?php if( $settings->icon_img_border_radius_njba['topleft'] ) { ?> border-top-left-radius: <?php echo $settings->icon_img_border_radius_njba['topleft'].'px'; ?>;
<?php } else { echo 'border-top-left-radius:0px;'; } ?><?php if( $settings->icon_img_border_radius_njba['topright'] ) { ?> border-top-right-radius: <?php echo $settings->icon_img_border_radius_njba['topright'].'px'; ?>;
<?php } else { echo 'border-top-right-radius:0px;'; } ?><?php if( $settings->icon_img_border_radius_njba['bottomleft'] ) { ?> border-bottom-left-radius: <?php echo $settings->icon_img_border_radius_njba['bottomleft'].'px'; ?>;
<?php } else { echo 'border-bottom-left-radius:0px;'; } ?><?php if( $settings->icon_img_border_radius_njba['bottomright'] ) { ?> border-bottom-right-radius: <?php echo $settings->icon_img_border_radius_njba['bottomright'].'px'; ?>;
<?php } else { echo 'border-bottom-right-radius:0px;'; } ?><?php if($settings->img_icon_border_style !== ''){ echo 'border-style: '.$settings->img_icon_border_style.';'; } else { echo 'border-style: none;'; } ?><?php if($settings->img_icon_border_color !== ''){ echo 'border-color: #'.$settings->img_icon_border_color.';'; } else { echo 'border-color: #ffffff;'; }?>
}

.fl-node-<?php echo $id ?> .njba-icon-img:hover {
<?php if($settings->icon_transition !== '' ) { ?> transition: all <?php echo $settings->icon_transition; ?>s ease;
<?php } ?><?php if($settings->img_icon_border_hover_color !== ''){ echo 'border-color: #'.$settings->img_icon_border_hover_color.';'; } else { echo 'border: #ffffff;'; }?>
}

<?php } ?>
.fl-node-<?php echo $id ?> .njba-icon-img {
<?php $settings->img_icon_bg_color_opc = ( $settings->img_icon_bg_color_opc !== '' ) ? $settings->img_icon_bg_color_opc : '100'; ?> <?php if( $settings->img_icon_bg_color ) { ?> background: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->img_icon_bg_color )) ?>, <?php echo $settings->img_icon_bg_color_opc/100; ?>);
<?php } ?>
}

.fl-node-<?php echo $id ?> .njba-icon-img:hover {
<?php $settings->img_icon_bg_hover_color_opc = ( $settings->img_icon_bg_hover_color_opc !== '' ) ? $settings->img_icon_bg_hover_color_opc : '100'; ?> <?php if( $settings->img_icon_bg_hover_color ) { ?> background: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->img_icon_bg_hover_color )) ?>, <?php echo $settings->img_icon_bg_hover_color_opc/100; ?>);
<?php } ?>
}

<?php if( $settings->image_type === 'photo' ) { ?>
.fl-node-<?php echo $id; ?> .njba-icon-img-main {
<?php if($settings->overall_alignment_img_icon === 'center') { ?>
    width: 100%;
    text-align: <?php echo $settings->overall_alignment_img_icon; ?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-icon-img {
<?php if( $settings->img_size !== '' ) { ?> max-width: <?php echo $settings->img_size.'px'; ?>;
<?php } else { echo 'max-width:100%;'; } ?><?php if($settings->img_size !== '') { echo 'width: '.$settings->img_size.'px;'; } else { echo 'width: auto;'; }?> display: inline-block;
}

.fl-node-<?php echo $id; ?> .njba-icon-img img {
<?php if( $settings->img_icon_padding['top'] ) { ?> padding-top: <?php echo $settings->img_icon_padding['top'].'px'; ?>;
<?php } else { echo 'padding-top:0px;'; } ?><?php if( $settings->img_icon_padding['right'] ) { ?> padding-right: <?php echo $settings->img_icon_padding['right'].'px'; ?>;
<?php } else { echo 'padding-right:0px;'; } ?><?php if( $settings->img_icon_padding['bottom'] ) { ?> padding-bottom: <?php echo $settings->img_icon_padding['bottom'].'px'; ?>;
<?php } else { echo 'padding-bottom:0px;'; } ?><?php if( $settings->img_icon_padding['left'] ) { ?> padding-left: <?php echo $settings->img_icon_padding['left'].'px'; ?>;
<?php } else { echo 'padding-left:0px;'; } ?><?php if( $settings->img_icon_margin['top'] ) { ?> margin-top: <?php echo $settings->img_icon_margin['top'].'px'; ?>;
<?php } else { echo 'margin-top:0px;'; } ?><?php if( $settings->img_icon_margin['right'] ) { ?> margin-right: <?php echo $settings->img_icon_margin['right'].'px'; ?>;
<?php } else { echo 'margin-right:0px;'; } ?><?php if( $settings->img_icon_margin['bottom'] ) { ?> margin-bottom: <?php echo $settings->img_icon_margin['bottom'].'px'; ?>;
<?php } else { echo 'margin-bottom:0px;'; } ?><?php if( $settings->img_icon_margin['left'] ) { ?> margin-left: <?php echo $settings->img_icon_margin['left'].'px'; ?>;
<?php } else { echo 'margin-left:0px;'; } ?>
}

<?php } ?>
<?php if($settings->image_type === 'icon') {?>
.fl-node-<?php echo $id; ?> .njba-icon-img-main.njba-infobox-icon-set {
    width: 100%;
}

.fl-node-<?php echo $id; ?> .njba-icon-img-main .njba-icon-img {
<?php if($settings->icon_size['desktop']) { ?> font-size: <?php echo $settings->icon_size['desktop'].'px'; ?>;
<?php } ?><?php if($settings->icon_line_height['desktop'] ){ ?> line-height: <?php echo $settings->icon_line_height['desktop'].'px'; ?>;
<?php } else { echo 'line-height:35px;'; } ?><?php if($settings->icon_line_height['desktop'] ){ ?> height: <?php echo $settings->icon_line_height['desktop'].'px'; ?>;
<?php } else { echo 'height:35px;'; } ?><?php if($settings->icon_line_height['desktop'] ){ ?> width: <?php echo $settings->icon_line_height['desktop'].'px'; ?>;
<?php } else { echo 'width:35px;'; } ?> text-align: center;
<?php if($settings->overall_alignment_img_icon === 'center'){ ?> margin: 0 auto;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-icon-img i {
<?php if($settings->icon_color){?> color: <?php echo '#'.$settings->icon_color; ?>;
<?php } ?> <?php if($settings->icon_transition !== '' ) { ?> transition: all <?php echo $settings->icon_transition; ?>s ease;
<?php } ?><?php if( $settings->img_icon_padding['top'] ) { ?> padding-top: <?php echo $settings->img_icon_padding['top'].'px'; ?>;
<?php } else { echo 'padding-top:0px;'; } ?><?php if( $settings->img_icon_padding['right'] ) { ?> padding-right: <?php echo $settings->img_icon_padding['right'].'px'; ?>;
<?php } else { echo 'padding-right:0px;'; } ?><?php if( $settings->img_icon_padding['bottom'] ) { ?> padding-bottom: <?php echo $settings->img_icon_padding['bottom'].'px'; ?>;
<?php } else { echo 'padding-bottom:0px;'; } ?><?php if( $settings->img_icon_padding['left'] ) { ?> padding-left: <?php echo $settings->img_icon_padding['left'].'px'; ?>;
<?php } else { echo 'padding-left:0px;'; } ?><?php if( $settings->img_icon_margin['top'] ) { ?> margin-top: <?php echo $settings->img_icon_margin['top'].'px'; ?>;
<?php } else { echo 'margin-top:0px;'; } ?><?php if( $settings->img_icon_margin['right'] ) { ?> margin-right: <?php echo $settings->img_icon_margin['right'].'px'; ?>;
<?php } else { echo 'margin-right:0px;'; } ?><?php if( $settings->img_icon_margin['bottom'] ) { ?> margin-bottom: <?php echo $settings->img_icon_margin['bottom'].'px'; ?>;
<?php } else { echo 'margin-bottom:0px;'; } ?><?php if( $settings->img_icon_margin['left'] ) { ?> margin-left: <?php echo $settings->img_icon_margin['left'].'px'; ?>;
<?php } else { echo 'margin-left:0px;'; } ?>
}

.fl-node-<?php echo $id; ?> .njba-icon-img:hover i {
<?php if($settings->icon_hover_color){?> color: <?php echo '#'.$settings->icon_hover_color; ?>;
<?php } ?> <?php if($settings->icon_transition !== '' ) { ?> transition: all <?php echo $settings->icon_transition; ?>s ease;
<?php } ?>
}

<?php } ?>
<?php if( $global_settings->responsive_enabled ) { // Global Setting If started 
?>
@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-icon-img-main .njba-icon-img {
    <?php if($settings->icon_size['medium']) { ?> font-size: <?php echo $settings->icon_size['medium'].'px'; ?>;
    <?php } ?>
    <?php if($settings->icon_line_height['medium'] ){ ?> line-height: <?php echo $settings->icon_line_height['medium'].'px'; ?>;
    <?php } else { echo 'line-height:35px;'; } ?><?php if($settings->icon_line_height['medium'] ){ ?> height: <?php echo $settings->icon_line_height['medium'].'px'; ?>;
    <?php } else { echo 'height:35px;'; } ?><?php if($settings->icon_line_height['medium'] ){ ?> width: <?php echo $settings->icon_line_height['medium'].'px'; ?>;
    <?php } else { echo 'width:35px;'; } ?>
    }
}

@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-icon-img-main .njba-icon-img {
    <?php if($settings->icon_size['small']) { ?> font-size: <?php echo $settings->icon_size['small'].'px'; ?>;
    <?php } ?>
    <?php if($settings->icon_line_height['small'] ){ ?> line-height: <?php echo $settings->icon_line_height['small'].'px'; ?>;
    <?php } else { echo 'line-height:35px;'; } ?><?php if($settings->icon_line_height['small'] ){ ?> height: <?php echo $settings->icon_line_height['small'].'px'; ?>;
    <?php } else { echo 'height:35px;'; } ?><?php if($settings->icon_line_height['small'] ){ ?> width: <?php echo $settings->icon_line_height['small'].'px'; ?>;
    <?php } else { echo 'width:35px;'; } ?>
    }
}

<?php
}
