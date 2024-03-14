<?php  $njbaRgbaColors = new NJBA_Rgba_Colors(); ?>
<?php $settings->button_border_radius = (array)$settings->button_border_radius; ?>
<?php $settings->button_box_shadow = (array)$settings->button_box_shadow; ?>
<?php $settings->button_padding = (array)$settings->button_padding; ?>
<?php $settings->button_margin = (array)$settings->button_margin; ?>
<?php $settings->button_font_family = (array)$settings->button_font_family; ?>
<?php $settings->button_font_size = (array)$settings->button_font_size; ?>
<?php $settings->icon_padding = (array)$settings->icon_padding; ?>
<?php $settings->icon_margin = (array)$settings->icon_margin; ?>
<?php $settings->icon_font_size = (array)$settings->icon_font_size; ?>
.fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn {

<?php if($settings->button_background_color) { ?> background-color: <?php echo '#'.$settings->button_background_color; ?>;
<?php } ?> <?php if($settings->button_text_color) { ?> color: <?php echo '#'.$settings->button_text_color;?>;
<?php } ?> <?php if($settings->button_font_family['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->button_font_family ); ?><?php } ?> <?php if($settings->button_font_size['desktop'] !== '' ) { ?> font-size: <?php echo $settings->button_font_size['desktop'].'px';?>;
<?php } ?> <?php if($settings->button_font_size['desktop'] !== '' ) { ?> line-height: <?php echo $settings->button_font_size['desktop'].'px';?>;
<?php } ?> <?php
if(is_array($settings->button_border_width)) { ?> <?php if($settings->button_border_width['top'] !== '') { ?> border-top-width: <?php echo $settings->button_border_width['top'].'px';?>;
<?php } ?> <?php if($settings->button_border_width['right'] !== '') { ?> border-right-width: <?php echo $settings->button_border_width['right'].'px';?>;
<?php } ?> <?php if($settings->button_border_width['bottom'] !== '') { ?> border-bottom-width: <?php echo $settings->button_border_width['bottom'].'px';?>;
<?php } ?> <?php if($settings->button_border_width['left'] !== '') { ?> border-left-width: <?php echo $settings->button_border_width['left'].'px';?>;
<?php } ?> <?php } else { ?> <?php if($settings->button_border_width ) { ?> border-width: <?php echo $settings->button_border_width.'px';?>;
<?php }
} ?> <?php if($settings->button_border_style) { ?> border-style: <?php echo $settings->button_border_style;?>;
<?php } ?> <?php if($settings->button_border_color) {?> border-color: <?php echo '#'.$settings->button_border_color;?>;
<?php } ?> <?php if($settings->button_border_radius['top-left'] !== '' ) { ?> border-top-left-radius: <?php echo $settings->button_border_radius['top-left'].'px';?>;
<?php } ?> <?php if($settings->button_border_radius['top-right'] !== '' ) { ?> border-top-right-radius: <?php echo $settings->button_border_radius['top-right'].'px';?>;
<?php } ?> <?php if($settings->button_border_radius['bottom-left'] !== '' ) { ?> border-bottom-left-radius: <?php echo $settings->button_border_radius['bottom-left'].'px';?>;
<?php } ?> <?php if($settings->button_border_radius['bottom-right'] !== '' ) { ?> border-bottom-right-radius: <?php echo $settings->button_border_radius['bottom-right'].'px';?>;
<?php } ?> <?php if($settings->button_padding['top'] !== '') { ?> padding-top: <?php echo $settings->button_padding['top'].'px';?>;
<?php } ?> <?php if($settings->button_padding['right'] !== '' ) { ?> padding-right: <?php echo $settings->button_padding['right'].'px';?>;
<?php } ?> <?php if($settings->button_padding['bottom'] !== '' ) { ?> padding-bottom: <?php echo $settings->button_padding['bottom'].'px';?>;
<?php } ?> <?php if($settings->button_padding['left'] !== '' ) { ?> padding-left: <?php echo $settings->button_padding['left'].'px';?>;
<?php } ?> <?php if($settings->button_margin['top'] !== '' ) { ?> margin-top: <?php echo $settings->button_margin['top'].'px';?>;
<?php } ?> <?php if($settings->button_margin['right'] !== '' ) { ?> margin-right: <?php echo $settings->button_margin['right'].'px';?>;
<?php } ?> <?php if($settings->button_margin['bottom'] !== '' ) { ?> margin-bottom: <?php echo $settings->button_margin['bottom'].'px';?>;
<?php } ?> <?php if($settings->button_margin['left'] !== '' ) { ?> margin-left: <?php echo $settings->button_margin['left'].'px';?>;
<?php } ?> <?php if($settings->transition) { ?> transition: <?php echo 'all ease '.$settings->transition.'s';?>;
<?php } ?> display: inline-block;
    text-align: center;
<?php if($settings->width === 'full_width'){?> display: block;
<?php }?><?php if($settings->width === 'custom'){?><?php if($settings->custom_width !== ''){?> width: <?php echo $settings->custom_width.'px';?>;
<?php } else { ?> width: 200px;
<?php } ?><?php if($settings->custom_height !== ''){?> min-height: <?php echo $settings->custom_height.'px';?>;
<?php } else { ?> min-height: 45px;
<?php } ?> <?php if($settings->custom_height !== ''){?> line-height: <?php echo $settings->custom_height.'px';?>;
<?php } ?> <?php } ?><?php if($settings->button_box_shadow !== '' && $settings->button_box_shadow_color !== '' ) {?> box-shadow: <?php if($settings->button_box_shadow['left_right'] !== '' ){ echo $settings->button_box_shadow['left_right'].'px '; } if($settings->button_box_shadow['top_bottom'] !== '' ){ echo $settings->button_box_shadow['top_bottom'].'px '; } if($settings->button_box_shadow['blur'] !== '' ){ echo $settings->button_box_shadow['blur'].'px '; } if($settings->button_box_shadow['spread'] !== '' ){ echo $settings->button_box_shadow['spread'].'px '; } if($settings->button_box_shadow_color !== '' ){echo '#'.$settings->button_box_shadow_color; }?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-btn-main {

<?php if($settings->alignment === 'left'){?> text-align: left;
<?php }?> <?php if($settings->alignment === 'center'){?> text-align: center;
<?php }?> <?php if($settings->alignment === 'right'){?> text-align: right;
<?php }?>
}

<?php if ($settings->button_style == 'gradient') {
	$bg_grad_start = '#'.FLBuilderColor::adjust_brightness( $njbaRgbaColors->njba_parse_color_to_hex( $settings->button_background_color ), 80, 'lighten' );
	$bg_hover_grad_start = '#'. FLBuilderColor::adjust_brightness( $njbaRgbaColors->njba_parse_color_to_hex( $settings->button_background_hover_color ), 80, 'lighten' );
?>
.fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn {
    background: linear-gradient(to top,  <?php echo $bg_grad_start; ?> 0%, <?php echo '#'.$settings->button_background_color; ?> 100%); /* FF3.6+ */
    background: -moz-linear-gradient(to top,  <?php echo $bg_grad_start; ?> 0%, <?php echo $settings->button_background_color; ?> 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $bg_grad_start; ?>), color-stop(100%,<?php echo $settings->button_background_color; ?>)); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(to top,  <?php echo $bg_grad_start; ?> 0%,<?php echo $settings->button_background_color; ?> 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(to top,  <?php echo $bg_grad_start; ?> 0%,<?php echo $settings->button_background_color; ?> 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(to top,  <?php echo $bg_grad_start; ?> 0%,<?php echo $settings->button_background_color; ?> 100%); /* IE10+ */
}

.fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn:hover {
    background: linear-gradient(to bottom,  <?php echo $bg_hover_grad_start; ?> 0%, <?php echo '#'.$settings->button_background_hover_color; ?> 100%); /* FF3.6+ */
    background: -moz-linear-gradient(to bottom,  <?php echo $bg_hover_grad_start; ?> 0%, <?php echo '#'.$settings->button_background_hover_color; ?> 100%); /* FF3.6+ */
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%,<?php echo $bg_hover_grad_start; ?>), color-stop(100%,<?php echo $settings->button_background_hover_color; ?>)); /* Chrome,Safari4+ */
    background: -webkit-linear-gradient(to bottom,  <?php echo $bg_hover_grad_start; ?> 0%,<?php echo $settings->button_background_hover_color; ?> 100%); /* Chrome10+,Safari5.1+ */
    background: -o-linear-gradient(to bottom,  <?php echo $bg_hover_grad_start; ?> 0%,<?php echo $settings->button_background_hover_color; ?> 100%); /* Opera 11.10+ */
    background: -ms-linear-gradient(to bottom,  <?php echo $bg_hover_grad_start; ?> 0%,<?php echo $settings->button_background_hover_color; ?> 100%); /* IE10+ */

}

<?php } ?>
<?php if($settings->button_style === 'threed') {  ?>
.fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn {
<?php $shadow_color = FLBuilderColor::adjust_brightness( $njbaRgbaColors->njba_parse_color_to_hex( $settings->button_background_color ), 30, 'darken' ); ?> <?php if(isset($settings->button_border_radius['top'] ) ) { ?> border-top-left-radius: <?php echo $settings->button_border_radius['top'].'px';?>;
<?php } ?> <?php if($settings->button_border_radius['right'] ) { ?> border-top-right-radius: <?php echo $settings->button_border_radius['right'].'px';?>;
<?php } ?> <?php if($settings->button_border_radius['bottom'] ) { ?> border-bottom-left-radius: <?php echo $settings->button_border_radius['bottom'].'px';?>;
<?php } ?> <?php if($settings->button_border_radius['left']) { ?> border-bottom-right-radius: <?php echo $settings->button_border_radius['left'].'px';?>;
<?php } ?> top: 0;
    box-shadow: 0 4px<?php echo '#'.$shadow_color; ?>;
}

.fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn:hover {

<?php $shadow_color_hover = FLBuilderColor::adjust_brightness( $njbaRgbaColors->njba_parse_color_to_hex( $settings->button_background_hover_color ), 30, 'darken' ); ?> box-shadow: 0 4px<?php echo '#'.$shadow_color_hover; ?>;
}

.fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn:active {

    top: 4px;
    box-shadow: none;

}

<?php } ?>
<?php if($settings->button_style === 'transparent') {  ?>
.fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn {
    background-color: transparent;
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn:hover {
<?php if($settings->button_background_hover_color) { ?> background-color: <?php echo '#'.$settings->button_background_hover_color; ?>;
<?php } ?> <?php if($settings->button_text_hover_color) { ?> color: <?php echo '#'.$settings->button_text_hover_color;?>;
<?php } ?> <?php if($settings->button_border_hover_color) { ?> border-color: <?php echo '#'.$settings->button_border_hover_color;?>;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn i {
<?php if($settings->icon_color) { ?> color: <?php echo '#'.$settings->icon_color;?>;
<?php } ?> <?php if($settings->icon_font_size['desktop'] ) { ?> font-size: <?php echo $settings->icon_font_size['desktop'].'px';?>;
<?php } ?> <?php if($settings->icon_padding['top'] ) { ?> padding-top: <?php echo $settings->icon_padding['top'].'px';?>;
<?php } ?> <?php if($settings->icon_padding['right'] ) { ?> padding-right: <?php echo $settings->icon_padding['right'].'px';?>;
<?php } ?> <?php if($settings->icon_padding['bottom'] ) { ?> padding-bottom: <?php echo $settings->icon_padding['bottom'].'px';?>;
<?php } ?> <?php if($settings->icon_padding['left'] ) { ?> padding-left: <?php echo $settings->icon_padding['left'].'px';?>;
<?php } ?> <?php if($settings->icon_margin['top'] ) { ?> margin-top: <?php echo $settings->icon_margin['top'].'px';?>;
<?php } ?> <?php if($settings->icon_margin['right'] ) { ?> margin-right: <?php echo $settings->icon_margin['right'].'px';?>;
<?php } ?> <?php if($settings->icon_margin['bottom'] ) { ?> margin-bottom: <?php echo $settings->icon_margin['bottom'].'px';?>;
<?php } ?> <?php if($settings->icon_margin['left'] ) { ?> margin-left: <?php echo $settings->icon_margin['left'].'px';?>;
<?php } ?> <?php if($settings->transition) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn:hover i {
<?php if($settings->icon_hover_color) { ?> color: <?php echo '#'.$settings->icon_hover_color;?>;
<?php } ?>
}

@media ( max-width: 991px ) {
    .fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn {
    <?php if($settings->button_font_size['medium'] ){ ?> font-size: <?php echo $settings->button_font_size['medium'].'px';?>;
    <?php } ?>

    }

    .fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn i {
    <?php if($settings->icon_font_size['medium']){ ?> font-size: <?php echo $settings->icon_font_size['medium'].'px';?>;
    <?php } ?>
    }
}

@media ( max-width: 767px ) {
    .fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn {
    <?php if($settings->button_font_size['small'] ){ ?> font-size: <?php echo $settings->button_font_size['small'].'px';?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-btn-main a.njba-btn i {
    <?php if($settings->icon_font_size['small'] ){ ?> font-size: <?php echo $settings->icon_font_size['small'].'px';?>;
    <?php } ?>
    }
}
