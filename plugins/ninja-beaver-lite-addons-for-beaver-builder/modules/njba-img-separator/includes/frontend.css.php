<?php $settings->img_bg_hover_color_opc = ( $settings->img_bg_hover_color_opc !== '' ) ? $settings->img_bg_hover_color_opc : '100'; ?>
<?php $settings->img_bg_color_opc = ( $settings->img_bg_color_opc !== '' ) ? $settings->img_bg_color_opc : '100'; ?>
<?php 
    $settings->gutter =  ( $settings->gutter !== '' ) ? $settings->gutter : '50';
    $settings->gutter_lr =  ( $settings->gutter_lr !== '' ) ? $settings->gutter_lr : '50';
?>
.fl-node-<?php echo $id; ?> {
    max-width: 100%;
<?php if( !empty( $settings->img_size ) ) :
	$margin_left = $settings->margin_left !== '' ? $settings->margin_left : '20';
	$margin_right = $settings->margin_right !== '' ? $settings->margin_right : '20'; ?> width: <?php echo $settings->img_size + $margin_left + $margin_right.'px'; ?>;
<?php endif; ?>
}

<?php if ( $settings->image_position === 'bottom' ) { ?>
.fl-node-<?php echo $id; ?> {
    bottom: 0;
    top: auto;
<?php if ( $settings->image_position_lr === 'left' ) { ?> left: auto;
    right: <?php echo $settings->gutter_lr; ?>%;
    -webkit-transform: translate(0%, <?php echo $settings->gutter; ?>%);
    -moz-transform: translate(0%, <?php echo $settings->gutter; ?>%);
    transform: translate(0%, <?php echo $settings->gutter; ?>%);
<?php } elseif ( $settings->image_position_lr === 'right' ) { ?> left: <?php echo $settings->gutter_lr; ?>%;
    right: auto;
    -webkit-transform: translate(0%, <?php echo $settings->gutter; ?>%);
    -moz-transform: translate(0%, <?php echo $settings->gutter; ?>%);
    transform: translate(0%, <?php echo $settings->gutter; ?>%);
<?php } else { ?> -webkit-transform: translate(-50%, <?php echo $settings->gutter; ?>%);
    -moz-transform: translate(-50%, <?php echo $settings->gutter; ?>%);
    transform: translate(-50%, <?php echo $settings->gutter; ?>%);
<?php } ?>
}

<?php } ?>
<?php if ( $settings->image_position === 'top' ) { ?>
.fl-node-<?php echo $id; ?> {
    top: 0;
    bottom: auto;
<?php if ( $settings->image_position_lr === 'left' ) { ?> left: auto;
    right: <?php echo $settings->gutter_lr; ?>%;
    -webkit-transform: translate(0%, -<?php echo $settings->gutter; ?>%);
    -moz-transform: translate(0%, -<?php echo $settings->gutter; ?>%);
    transform: translate(0%, -<?php echo $settings->gutter; ?>%);
<?php } elseif ( $settings->image_position_lr === 'right' ) { ?> left: <?php echo $settings->gutter_lr; ?>%;
    right: auto;
    -webkit-transform: translate(0%, -<?php echo $settings->gutter; ?>%);
    -moz-transform: translate(0%, -<?php echo $settings->gutter; ?>%);
    transform: translate(0%, -<?php echo $settings->gutter; ?>%);
<?php } else { ?> -webkit-transform: translate(-50%, -<?php echo $settings->gutter; ?>%);
    -moz-transform: translate(-50%, -<?php echo $settings->gutter; ?>%);
    transform: translate(-50%, -<?php echo $settings->gutter; ?>%);
<?php } ?>
}

<?php } ?>
/*.fl-node-




<?php echo $id; ?>     ,*/
.fl-node-<?php echo $id; ?> .njba-image .njba-sep-image {

<?php if($settings->image_style === 'custom') : ?> <?php if( $settings->image_bg_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->image_bg_color )) ?>, <?php echo $settings->img_bg_color_opc/100; ?>);
<?php } ?><?php if( $settings->img_border_style !== 'none' ) : ?> border-style: <?php echo $settings->img_border_style;?>;
<?php endif; ?> border-width: <?php echo ( $settings->img_border_width !== '' ) ? $settings->img_border_width.'px' : '1'.'px'; ?>;
<?php if( !empty( $settings->img_border_color ) ): ?> border-color: <?php echo '#'.$settings->img_border_color; ?>;
<?php endif; ?> border-radius: <?php echo ( empty( $settings->img_bg_border_radius ) ) ? '0' : $settings->img_bg_border_radius.'px'; ?>;
    -webkit-box-shadow: <?php echo $settings->box_shadow['horizontal'].'px '; echo $settings->box_shadow['vertical'].'px '; echo $settings->box_shadow['blur'].'px '; echo $settings->box_shadow['spread'].'px '; ?> rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_shadow_color )) ?>, <?php echo $settings->box_shadow_opacity/100; ?>);
    -moz-box-shadow: <?php echo $settings->box_shadow['horizontal'].'px '; echo $settings->box_shadow['vertical'].'px '; echo $settings->box_shadow['blur'].'px '; echo $settings->box_shadow['spread'].'px '; ?> rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_shadow_color )) ?>, <?php echo $settings->box_shadow_opacity/100; ?>);
    -o-box-shadow: <?php echo $settings->box_shadow['horizontal'].'px '; echo $settings->box_shadow['vertical'].'px '; echo $settings->box_shadow['blur'].'px '; echo $settings->box_shadow['spread'].'px '; ?> rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_shadow_color )) ?>, <?php echo $settings->box_shadow_opacity/100; ?>);
    box-shadow: <?php echo $settings->box_shadow['horizontal'].'px '; echo $settings->box_shadow['vertical'].'px '; echo $settings->box_shadow['blur'].'px '; echo $settings->box_shadow['spread'].'px '; ?> rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_shadow_color )) ?>, 
        <?php echo ( $settings->box_shadow_opacity !== '' ) ? $settings->box_shadow_opacity/100 : '50'.'%'; ?>);
<?php endif; ?>
}

.fl-node-<?php echo $id; ?> .njba-image .njba-sep-image {
<?php if( !empty( $settings->img_size ) ) : ?> width: <?php echo $settings->img_size.'px'; ?>;
<?php endif; ?><?php /* Border Style */?><?php if(($settings->image_style === 'custom') && !empty( $settings->img_bg_size )) : ?> padding: <?php echo $settings->img_bg_size.'px'; ?>;
<?php endif; ?>
}

/* Responsive Photo Size */
<?php if( $global_settings->responsive_enabled ) { // Global Setting If started ?>
@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
<?php if (  $settings->medium_img_size !== '' ) { ?>
    .fl-node-<?php echo $id; ?> {
    <?php   $margin_left = $settings->margin_left !== '' ? $settings->margin_left : '20';
			$margin_right = $settings->margin_right !== '' ? $settings->margin_right : '20'; ?> width: <?php echo $settings->medium_img_size + $margin_left + $margin_right.'px'; ?>;
    }

    .fl-node-<?php echo $id; ?> .njba-image .njba-sep-image {
        width: <?php echo $settings->medium_img_size.'px'; ?>;
    }

<?php } ?>
<?php
if( ( $settings->image_position_lr === 'left' || $settings->image_position_lr === 'right' ) && $settings->responsive_center === 'both' ) {
	if( $settings->image_position === 'bottom' ) {
	?>
    .fl-node-<?php echo $id; ?> {
        /*bottom: 0;
		top: auto;*/
        right: auto;
        left: 50%;
        -webkit-transform: translate(-50%, 50%);
        -moz-transform: translate(-50%, 50%);
        transform: translate(-50%, 50%);
    }

<?php
	}elseif ( $settings->image_position === 'top' ) { ?>
    .fl-node-<?php echo $id; ?> {
        /*bottom: 0;
		top: auto;*/
        right: auto;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        -moz-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

<?php }
}
?>
}

@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {
<?php if (  $settings->small_img_size !== '' ) { ?>
    .fl-node-<?php echo $id; ?> {
    <?php   $margin_left = $settings->margin_left !== '' ? $settings->margin_left : '20';
			$margin_right = $settings->margin_right !== '' ? $settings->margin_right : '20'; ?> width: <?php echo $settings->small_img_size + $margin_left + $margin_right.'px'; ?>;
    }

    .fl-node-<?php echo $id; ?> .njba-image .njba-sep-image {
        width: <?php echo $settings->small_img_size.'px'; ?>;
    }

<?php } ?>
<?php
if( ( $settings->image_position_lr === 'left' || $settings->image_position_lr === 'right' ) && $settings->responsive_center === 'small' ) {
	if( $settings->image_position === 'bottom' ) {
	?>
    .fl-node-<?php echo $id; ?> {
        /*bottom: 0;
		top: auto;*/
        right: auto;
        left: 50%;
        -webkit-transform: translate(-50%, 50%);
        -moz-transform: translate(-50%, 50%);
        transform: translate(-50%, 50%);
    }

<?php
}elseif ( $settings->image_position === 'top' ) { ?>
    .fl-node-<?php echo $id; ?> {
        /*bottom: 0;
		top: auto;*/
        right: auto;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        -moz-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
    }

<?php }
}
?>
}

<?php } ?>
/* Animation CSS */
<?php if ( $settings->img_animation_repeat !== '' && $settings->img_animation_repeat != '0' && $settings->img_animation_repeat != '1'  ) { ?>
.fl-node-<?php echo $id; ?> .animated {
    -webkit-animation-iteration-count: <?php echo $settings->img_animation_repeat; ?>;
    animation-iteration-count: <?php echo $settings->img_animation_repeat; ?>;
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-image .njba-sep-image:hover {
<?php if($settings->image_style === 'custom') : ?> <?php if( $settings->img_bg_hover_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->img_bg_hover_color )) ?>, <?php echo $settings->img_bg_hover_color_opc/100; ?>);
<?php } ?><?php if( !empty( $settings->img_border_hover_color ) ): ?> border-color: #<?php echo $settings->img_border_hover_color; ?>;
<?php endif; ?><?php endif; ?>

}
