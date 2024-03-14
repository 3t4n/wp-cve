<?php $width = 100 / $settings->show_col['desktop'];?>

.fl-node-<?php echo $id; ?> .njba-gallery-box {
    width: <?php echo $width ?>%;
    padding: <?php echo $settings->photo_spacing/2 .'px'; ?>;
    float: left;
}

<?php if ( $settings->show_col['desktop'] > 1 ) { ?>
.fl-node-<?php echo $id; ?> .njba-gallery-box:nth-child(<?php echo $settings->show_col['desktop']; ?>n+1) {
    clear: left;
}

.fl-node-<?php echo $id; ?> .njba-gallery-box:nth-child(<?php echo $settings->show_col['desktop']; ?>n+0) {
    clear: right;
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-gallery-main .njba-image-box-overlay {
<?php if( $settings->overly_color ) { ?> background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->overly_color)) ?>, <?php echo $settings->overly_color_opacity/100; ?>);
<?php } ?>
<?php if( $settings->transition ) { ?> transition: <?php echo 'all ease '.$settings->transition;?>s;
<?php } ?>
}

.fl-node-<?php echo $id; ?> .njba-gallery-main .njba-image-box-content.njba-image-box-hover i{
<?php if($settings->icon_size['desktop']) { ?> font-size: <?php echo $settings->icon_size['desktop'].'px'; ?>;
<?php } ?>
<?php if($settings->icon_color){?> color: <?php echo '#'.$settings->icon_color; ?>;
<?php } ?>
}

<?php if($settings->box_show_border === 'yes'){ ?>
.fl-node-<?php echo $id ?> .njba-gallery-section {
<?php if($settings->box_border_width !== '' ){ echo 'border: '.$settings->box_border_width.'px;'; } else { echo 'border: 1px;'; }?>
<?php if( $settings->box_border_radius['topleft'] ) { ?> border-top-left-radius: <?php echo $settings->box_border_radius['topleft'].'px'; ?>;
<?php } else { echo 'border-top-left-radius:0px;'; } ?>
<?php if( $settings->box_border_radius['topright'] ) { ?> border-top-right-radius: <?php echo $settings->box_border_radius['topright'].'px'; ?>;
<?php } else { echo 'border-top-right-radius:0px;'; } ?>
<?php if( $settings->box_border_radius['bottomleft'] ) { ?> border-bottom-left-radius: <?php echo $settings->box_border_radius['bottomleft'].'px'; ?>;
<?php } else { echo 'border-bottom-left-radius:0px;'; } ?>
<?php if( $settings->box_border_radius['bottomright'] ) { ?> border-bottom-right-radius: <?php echo $settings->box_border_radius['bottomright'].'px'; ?>;
<?php } else { echo 'border-bottom-right-radius:0px;'; } ?>
<?php if($settings->box_border_style !== ''){ echo 'border-style: '.$settings->box_border_style.';'; } else { echo 'border-style: none;'; } ?>
<?php if($settings->box_border_color !== ''){ echo 'border-color: #'.$settings->box_border_color.';'; } else { echo 'border-color: #ffffff;'; }?>
}
<?php } ?>

.fl-node-<?php echo $id ?> .njba-gallery-section {
 overflow: hidden;
 <?php if( $settings->box_shadow !== '' ) { ?> -webkit-box-shadow: <?php if( isset($settings->box_shadow['horizontal'] ) ) { echo $settings->box_shadow['horizontal'].'px '; } if( isset($settings->box_shadow['vertical'] ) ) {  echo $settings->box_shadow['vertical'].'px '; } if( isset($settings->box_shadow['blur'] ) ) { echo $settings->box_shadow['blur'].'px '; } if( isset($settings->box_shadow['spread'] ) ) { echo $settings->box_shadow['spread'].'px '; } ?> rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_shadow_color )) ?>);
    -moz-box-shadow: <?php if( isset($settings->box_shadow['horizontal'] ) ) { echo $settings->box_shadow['horizontal'].'px '; } if( isset($settings->box_shadow['vertical'] ) ) {echo $settings->box_shadow['vertical'].'px '; } if( isset($settings->box_shadow['blur'] ) ) { echo $settings->box_shadow['blur'].'px '; } if( isset($settings->box_shadow['spread'] ) ) {echo $settings->box_shadow['spread'].'px '; } ?> rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_shadow_color )) ?>);
    -o-box-shadow: <?php if( isset($settings->box_shadow['horizontal'] ) ) { echo $settings->box_shadow['horizontal'].'px '; } if( isset($settings->box_shadow['vertical'] ) ) { echo $settings->box_shadow['vertical'].'px '; } if( isset($settings->box_shadow['blur'] ) ) { echo $settings->box_shadow['blur'].'px '; } if( isset($settings->box_shadow['spread'] ) ) { echo $settings->box_shadow['spread'].'px '; } ?> rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_shadow_color )) ?>);
    box-shadow: <?php if( isset($settings->box_shadow['horizontal'] ) ) { echo $settings->box_shadow['horizontal'].'px '; } if( isset($settings->box_shadow['vertical'] ) ) { echo $settings->box_shadow['vertical'].'px '; } if( isset($settings->box_shadow['blur'] ) ) { echo $settings->box_shadow['blur'].'px '; } if( isset($settings->box_shadow['spread'] ) ) { echo $settings->box_shadow['spread'].'px '; }?> rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->box_shadow_color )) ?>);
<?php } ?>
}


<?php if($global_settings->responsive_enabled) { // Global Setting If started ?>
@media ( max-width: <?php echo $global_settings->medium_breakpoint .'px'; ?> ) {
    .fl-node-<?php echo $id; ?> .njba-gallery-box {
        width: <?php echo 100/$settings->show_col['medium']; ?>%;
    }

<?php if ( $settings->show_col['desktop'] > 1 ) { ?>
    .fl-node-<?php echo $id; ?> .njba-gallery-box:nth-child(<?php echo $settings->show_col['desktop']; ?>n+1),
    .fl-node-<?php echo $id; ?> .njba-gallery-box:nth-child(<?php echo $settings->show_col['desktop']; ?>n+0) {
        clear: none;
    }

<?php } ?>

    .fl-node-<?php echo $id; ?> .njba-gallery-box:nth-child(<?php echo $settings->show_col['medium']; ?>n+1) {
        clear: left;
    }

    .fl-node-<?php echo $id; ?> .njba-gallery-box:nth-child(<?php echo $settings->show_col['medium']; ?>n+0) {
        clear: right;
    }

}

@media ( max-width: <?php echo $global_settings->responsive_breakpoint .'px'; ?> ) {


    .fl-node-<?php echo $id; ?> .njba-gallery-box {
        width: <?php echo 100/$settings->show_col['small']; ?>%;
    }

<?php if ( $settings->show_col['desktop'] > 1 ) { ?>
    .fl-node-<?php echo $id; ?> .njba-gallery-box:nth-child(<?php echo $settings->show_col['desktop']; ?>n+1),
    .fl-node-<?php echo $id; ?> .njba-gallery-box:nth-child(<?php echo $settings->show_col['desktop']; ?>n+0) <?php if ( $settings->show_col['medium'] > 1 ) { ?>,
    .fl-node-<?php echo $id; ?> .njba-gallery-box:nth-child(<?php echo $settings->show_col['medium']; ?>n+1),
    .fl-node-<?php echo $id; ?> .njba-gallery-box:nth-child(<?php echo $settings->show_col['medium']; ?>n+0) <?php } ?> {
        clear: none;
    }

<?php } ?>

    .fl-node-<?php echo $id; ?> .njba-gallery-box:nth-child(<?php echo $settings->show_col['small']; ?>n+1) {
        clear: left;
    }

    .fl-node-<?php echo $id; ?> .njba-gallery-box:nth-child(<?php echo $settings->show_col['small']; ?>n+0) {
        clear: right;
    }

}

<?php } //die();?>
