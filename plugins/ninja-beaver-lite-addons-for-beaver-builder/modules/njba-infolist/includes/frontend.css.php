<?php $settings->border_radius = ($settings->border_radius !== '') ? $settings->border_radius : '0'; ?>
<?php $settings->heading_padding['left'] = ($settings->heading_padding['left'] !== '' ) ? $settings->heading_padding['left'] : '20'; ?>
<?php $settings->heading_padding['right'] = ($settings->heading_padding['right'] !== '' ) ? $settings->heading_padding['right'] : '20'; ?>
<?php $settings->heading_padding['top'] = ($settings->heading_padding['top'] !== '' ) ? $settings->heading_padding['top'] : '20'; ?>
<?php $settings->heading_padding['bottom'] = ($settings->heading_padding['bottom'] !== '' ) ? $settings->heading_padding['bottom'] : '10'; ?>
<?php $settings->heading_subhead_padding['left'] = ($settings->heading_subhead_padding['left'] !== '' ) ? $settings->heading_subhead_padding['left'] : '20'; ?>
<?php if($settings->img_icon_position === 'center'){ ?>
<?php $settings->heading_margin['top'] = ($settings->heading_margin['top'] !== '' ) ? $settings->heading_margin['top'] : '10'; ?>
<?php $settings->heading_margin['bottom'] = ($settings->heading_margin['bottom'] !== '' ) ? $settings->heading_margin['bottom'] : '10'; ?>
<?php $settings->connector_width = ($settings->connector_width !== '' ) ? $settings->connector_width : '1'; ?>
<?php } ?>
.fl-node-<?php echo $id; ?> .njba-infolist-img {
    text-align: center;
}

<?php
/* If connector Yes execute this */
if ( $settings->show_connector === 'yes' ) {
?>
.fl-node-<?php echo $id;?> .njba-info-list-connector, .fl-node-<?php echo $id;?> .njba-info-list-connector-top {
<?php if ( $settings->connector_color !== '' ) { ?> color: <?php echo '#'.$settings->connector_color;?>;
<?php } ?><?php if ( $settings->connector_style !== '' ) { ?> border-style: <?php echo $settings->connector_style;?>;
<?php } ?>
}

<?php
		$icon_extra_padding = 0;
		$space_element = 0;
		$space_element_top = 0;
		if( $settings->space_btw_elements != '0' ) {
				$space_element += $settings->space_btw_elements / 2;
		}
?>
.fl-node-<?php echo $id;?> .njba-info-list-connector {
    top: calc(50% + <?php echo ( $settings->icon_image_size / 2 ) + $icon_extra_padding - $space_element .'px'; ?>);
    height: calc(50% - <?php echo ( $settings->icon_image_size / 2 ) + $icon_extra_padding - $space_element .'px'; ?>);
}

.fl-node-<?php echo $id;?> .njba-info-list-connector-top {
    top: 0;
}

.fl-node-<?php echo $id;?> .njba-info-list-connector-top {
    height: calc(50% - <?php echo ( ( $settings->icon_image_size / 2 ) + $icon_extra_padding + $space_element ) .'px'; ?>);
}

.fl-node-<?php echo $id;?> .njba-info-list-connector, .fl-node-<?php echo $id;?> .njba-info-list-connector-top {
    border-width: 0 0 0<?php echo $settings->connector_width.'px'; ?>;
}

<?php
}?>
<?php if ( $settings->space_btw_elements !== '' ) { ?>
.fl-node-<?php echo $id;?> .njba-infolist .njba-infolist-sec {
    padding-bottom: <?php echo ( $settings->space_btw_elements ).'px'; ?>;
}

<?php } ?>
<?php
if( $settings->img_icon_position === 'center' ){ ?>
.fl-node-<?php echo $id;?> .njba-infolist .njba-info-list-connector {
    left: calc(50% + <?php echo $settings->icon_image_size / 2 .'px'; ?>);
    width: calc(100% - <?php echo $settings->icon_image_size .'px'; ?>);
<?php
$extra_padding = 0;
?> top: <?php echo ( $settings->icon_image_size / 2 ) + $extra_padding .'px'; ?>;
    transform: translate(0%, -50%);
}

.fl-node-<?php echo $id; ?> .njba-infolist-content {
    text-align: center;
}

.fl-node-<?php echo $id;?> .njba-info-list-connector, .fl-node-<?php echo $id;?> .njba-info-list-connector-top {
    transform: translateY(50%);
}

.fl-node-<?php echo $id;?> .njba-infolist .njba-info-list-connector, .fl-node-<?php echo $id;?> .njba-infolist .njba-info-list-connector-top {
    border-top-width: <?php echo $settings->connector_width.'px'; ?>;
}

@media all and (min-width: 768px) {
    .fl-node-<?php echo $id;?> .njba-infolist .njba-infolist-sec {
        width: <?php echo round( 100 / count( $settings->info_list_content ), 3 ) ?>%;
        display: inline-block;
    }

<?php if ( $settings->space_btw_elements !== '' ) { ?>
    .fl-node-<?php echo $id;?> .njba-infolist .njba-infolist-sec {
        padding-right: <?php echo ( $settings->space_btw_elements/2 ).'px'; ?>;
        padding-left: <?php echo ( $settings->space_btw_elements/2 ).'px'; ?>;
        padding-bottom: 0;
    }

<?php } ?>
}

<?php
}
if( $settings->img_icon_position === 'left' ){ ?>
.fl-node-<?php echo $id; ?> .njba-infolist-sec {
    float: left;
    width: 100%;
    padding: 0;
}

.fl-node-<?php echo $id; ?> .njba-infolist-sec .position_left {
    float: none;
    display: inline-block;
    vertical-align: middle;
}

.fl-node-<?php echo $id;?> .njba-infolist .njba-infolist-content {
<?php
		$icon_image_size = $settings->icon_image_size;
?> width: calc(100% - <?php echo $icon_image_size + 20 ?>px);
    display: inline-block;
    vertical-align: middle;
}

.fl-node-<?php echo $id;?> .njba-infolist .njba-info-list-connector, .fl-node-<?php echo $id;?> .njba-infolist .njba-info-list-connector-top {
    border-left-width: <?php echo $settings->connector_width.'px'; ?>;
    transform: translateX(50%);
}

.fl-node-<?php echo $id;?> .njba-infolist .njba-info-list-connector, .fl-node-<?php echo $id;?> .njba-infolist .njba-info-list-connector-top {
<?php
		$icon_image_size = $settings->icon_image_size;
?> left: <?php echo $icon_image_size / 2 .'px'; ?>;
}

<?php
}
if( $settings->img_icon_position === 'right' ){ ?>
.fl-node-<?php echo $id; ?> .njba-infolist-sec {
    float: right;
    width: 100%;
    text-align: right;
    direction: rtl;
    padding: 0;
}

.fl-node-<?php echo $id; ?> .njba-infolist-sec .position_right {
    float: none;
    display: inline-block;
    vertical-align: middle;
    direction: ltr;
}

.fl-node-<?php echo $id;?> .njba-infolist .njba-info-list-connector, .fl-node-<?php echo $id;?> .njba-infolist .njba-info-list-connector-top {
    border-left-width: <?php echo $settings->connector_width.'px'; ?>;
    transform: translateX(50%);
}

.fl-node-<?php echo $id;?> .njba-infolist .njba-infolist-content {
<?php
		$icon_image_size = $settings->icon_image_size;
?> width: calc(100% - <?php echo $icon_image_size + 20 .'px';?>);
    display: inline-block;
    vertical-align: middle;
    direction: ltr;
}

.fl-node-<?php echo $id;?> .njba-infolist .njba-info-list-connector, .fl-node-<?php echo $id;?> .njba-infolist .njba-info-list-connector-top {
<?php
		$icon_image_size = $settings->icon_image_size;
?> right: <?php echo $icon_image_size / 2 .'px'; ?>;
}

<?php
}
?>
<?php
        $total_infolist = count($settings->info_list_content);
        for($i=0; $i< $total_infolist; $i++){
        $box_content = $settings->info_list_content[$i];
        $box_content->icon_bg_color_opc = ( $box_content->icon_bg_color_opc !== '' ) ? $box_content->icon_bg_color_opc : '100';
        ?>
.fl-node-<?php echo $id; ?> .njba-infolist-list-<?php echo $i;?> .njba-infolist-img i {
<?php if($box_content->icon_color){?> color: #<?php echo $box_content->icon_color; ?>;
<?php } ?> width: <?php echo $settings->icon_image_size.'px'; ?>;
    height: <?php echo $settings->icon_image_size.'px'; ?>;
    line-height: <?php echo $settings->icon_image_size.'px'; ?>;
<?php if( $box_content->icon_bg_color ) { ?> background: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($box_content->icon_bg_color )) ?>, <?php echo $box_content->icon_bg_color_opc/100; ?>);
<?php } ?><?php if($box_content->icon_bg_color) {?> font-size: <?php echo $settings->icon_image_size / 2 .'px'; ?>;
<?php } else { ?> font-size: <?php echo $settings->icon_image_size.'px'; ?>;
<?php } ?><?php if($settings->border_radius != '0'){?> border-radius: <?php echo $settings->border_radius.'px'; ?>;
<?php } ?> display: inline-block;
<?php if($settings->icon_img_shadow !== '' && $settings->icon_img_shadow_color !== '' ) {?> box-shadow: <?php if($settings->icon_img_shadow['left_right'] !== '' ){ echo $settings->icon_img_shadow['left_right'].'px '; } if($settings->icon_img_shadow['top_bottom'] !== '' ){ echo $settings->icon_img_shadow['top_bottom'].'px '; } if($settings->icon_img_shadow['blur'] !== '' ){ echo $settings->icon_img_shadow['blur'].'px '; } if($settings->icon_img_shadow['spread'] !== '' ){ echo $settings->icon_img_shadow['spread'].'px '; } if($settings->icon_img_shadow_color !== '' ){echo '#'.$settings->icon_img_shadow_color; }?>; <?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-infolist-list-<?php echo $i; ?> .njba-infolist-content <?php echo $settings->title_tag_selection; ?>.heading {
<?php if($settings->title_color){?> color: <?php echo '#'.$settings->title_color; ?>;
<?php } ?><?php if($settings->title_font_size['desktop'] ) { ?> font-size: <?php echo $settings->title_font_size['desktop'].'px'; ?>;
<?php } ?><?php if($settings->title_line_height['desktop'] ) { ?> line-height: <?php echo $settings->title_line_height['desktop'].'px'; ?>;
<?php } ?><?php if( $settings->title_font_family['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->title_font_family ); ?>;
<?php } ?><?php if($settings->heading_padding['top'] ){ ?> padding-top: <?php echo $settings->heading_padding['top'].'px'; ?>;
<?php } ?><?php if($settings->heading_padding['right'] ){ ?> padding-right: <?php echo $settings->heading_padding['right'].'px'; ?>;
<?php } ?><?php if($settings->heading_padding['bottom'] ){ ?> padding-bottom: <?php echo $settings->heading_padding['bottom'].'px'; ?>;
<?php } ?><?php if($settings->heading_padding['left'] ){ ?> padding-left: <?php echo $settings->heading_padding['left'].'px'; ?>;
<?php } ?><?php if($settings->img_icon_position === 'center'){ ?> margin-top: <?php echo $settings->heading_margin['top'].'px'; ?>;
    margin-bottom: <?php echo $settings->heading_margin['bottom'].'px'; ?>;
<?php } else { ?> margin: 0;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-infolist-list-<?php echo $i; ?> .njba-infolist-content p {
<?php if($settings->subhead_color){?> color: <?php echo '#'.$settings->subhead_color; ?>;
<?php } ?><?php if($settings->subhead_font_size['desktop'] ) { ?> font-size: <?php echo $settings->subhead_font_size['desktop'].'px'; ?>;
<?php } ?><?php if($settings->subhead_line_height['desktop'] ) { ?> line-height: <?php echo $settings->subhead_line_height['desktop'].'px'; ?>;
<?php } ?><?php if( $settings->subhead_font_family['family'] !== 'Default' ) { ?><?php FLBuilderFonts::font_css( $settings->subhead_font_family ); ?>;
<?php } ?><?php if($settings->heading_subhead_padding['top'] ){ ?> padding-top: <?php echo $settings->heading_subhead_padding['top'].'px'; ?>;
<?php } ?><?php if($settings->heading_subhead_padding['right'] ){ ?> padding-right: <?php echo $settings->heading_subhead_padding['right'].'px'; ?>;
<?php } ?><?php if($settings->heading_subhead_padding['bottom'] ){ ?> padding-bottom: <?php echo $settings->heading_subhead_padding['bottom'].'px'; ?>;
<?php } ?><?php if($settings->heading_subhead_padding['left'] ){ ?> padding-left: <?php echo $settings->heading_subhead_padding['left'].'px'; ?>;
<?php } ?>
}

<?php if( $global_settings->responsive_enabled ) { // Global Setting If started
		?>

@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-infolist-list-<?php echo $i; ?> .njba-infolist-content <?php echo $settings->title_tag_selection; ?>.heading {
    <?php if($settings->title_font_size['medium'] ) { ?> font-size: <?php echo $settings->title_font_size['medium'].'px'; ?>;
    <?php } ?><?php if($settings->title_line_height['medium'] ) { ?> line-height: <?php echo $settings->title_line_height['medium'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-infolist-list-<?php echo $i; ?> .njba-infolist-content p {
    <?php if($settings->subhead_font_size['medium'] ) { ?> font-size: <?php echo $settings->subhead_font_size['medium'].'px'; ?>;
    <?php } ?><?php if($settings->subhead_line_height['medium'] ) { ?> line-height: <?php echo $settings->subhead_line_height['medium'].'px'; ?>;
    <?php } ?>
    }
}

@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-infolist-list-<?php echo $i; ?> .njba-infolist-content <?php echo $settings->title_tag_selection; ?>.heading {
    <?php if($settings->title_font_size['small'] ) { ?> font-size: <?php echo $settings->title_font_size['small'].'px'; ?>;
    <?php } ?><?php if($settings->title_line_height['small'] ) { ?> line-height: <?php echo $settings->title_line_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .njba-infolist-list-<?php echo $i; ?> .njba-infolist-content p {
    <?php if($settings->subhead_font_size['small'] ) { ?> font-size: <?php echo $settings->subhead_font_size['small'].'px'; ?>;
    <?php } ?><?php if($settings->subhead_line_height['small'] ) { ?> line-height: <?php echo $settings->subhead_line_height['small'].'px'; ?>;
    <?php } ?>
    }

    .fl-node-<?php echo $id; ?> .position_center ~ .njba-info-list-center {
        display: none;
    }
}

<?php
}
?>
<?php
		if( $box_content->image_type === 'photo' ) :
		$img_size = array();
		$img_size = ( isset( FLBuilderPhoto::get_attachment_data($box_content->info_photo)->url ) ) ? getimagesize( FLBuilderPhoto::get_attachment_data($box_content->info_photo)->url ) : '';

		if( isset($img_size[0], $img_size[1]) ) :
				$actual_height = ( $settings->icon_image_size * $img_size[1] ) / $img_size[0];

				if( $actual_height > $settings->icon_image_size ) :
						$need_to_add = $actual_height - $settings->icon_image_size;
				else :
						$need_to_add = $settings->icon_image_size - $actual_height;
				endif;
				$space_element = 0;
				$space_element_top = 0;
				if( $settings->space_btw_elements != '0' ) {
						$space_element += $settings->space_btw_elements / 2;
						$space_element_top += $settings->space_btw_elements / 2;
				}
				if ( $settings->show_connector === 'yes' ) : ?>
<?php if($settings->img_icon_position !== 'center') { ?>
.fl-node-<?php echo $id;?> .njba-infolist-list-<?php echo $i;?> .njba-info-list-connector {
    top: calc(50% + <?php echo ( ( $settings->icon_image_size - $need_to_add ) / 2 ) + $icon_extra_padding - $space_element .'px'; ?>);
    height: calc(50% - <?php echo ( ( $settings->icon_image_size - $need_to_add ) / 2 ) + $icon_extra_padding - $space_element .'px'; ?>);
}

.fl-node-<?php echo $id;?> .njba-infolist-list-<?php echo $i;?> .njba-info-list-connector-top {
    height: calc(50% - <?php echo ( ( ( $settings->icon_image_size - $need_to_add ) / 2 ) + $icon_extra_padding + $space_element .'px' ); ?>);
    top: 0;
}

<?php } ?>
<?php
endif;?>
.fl-node-<?php echo $id;?> .njba-infolist-list-<?php echo $i;?> .njba-infolist-img img {
    width: <?php echo $settings->icon_image_size.'px'; ?>;
    height: <?php echo $settings->icon_image_size.'px'; ?>;
<?php if( $box_content->icon_bg_color ) { ?> background: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($box_content->icon_bg_color )) ?>, <?php echo $box_content->icon_bg_color_opc/100; ?>);
<?php } ?><?php if($settings->border_radius != '0'){ ?> border-radius: <?php echo $settings->border_radius.'px'; ?>;
<?php } ?> display: inline-block;
<?php if($settings->icon_img_shadow !== '' && $settings->icon_img_shadow_color !== '' ) {?> box-shadow: <?php if($settings->icon_img_shadow['left_right'] !== '' ){ echo $settings->icon_img_shadow['left_right'].'px '; } if($settings->icon_img_shadow['top_bottom'] !== '' ){ echo $settings->icon_img_shadow['top_bottom'].'px '; } if($settings->icon_img_shadow['blur'] !== '' ){ echo $settings->icon_img_shadow['blur'].'px '; } if($settings->icon_img_shadow['spread'] !== '' ){ echo $settings->icon_img_shadow['spread'].'px '; } if($settings->icon_img_shadow_color !== '' ){echo '#'.$settings->icon_img_shadow_color; }?>; <?php } ?>
}

<?php
endif;
endif;
?>
<?php
}
?>
