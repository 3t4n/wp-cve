<?php
$number_photos = count($settings->photos);
for($i=0,$iMax = count($settings->photos); $i < $iMax; $i++)
{
        if($settings->photos[$i]->cta_layout === 'stacked'){
            $coordinate = explode( ',', $settings->photos[$i]->marker );
                //var_dump($coordinate);die();
            $x_coordinate = ( isset( $coordinate[1] ) ) ? $coordinate[1] : '0';
            $y_coordinate = ( isset( $coordinate[0] ) ) ? $coordinate[0] : '0';
        }
        $cta_id = $id.' .njba-slide-id-'.$i;
       
         $cta_css_array = array(
        //Button Style/*
            'button_style'                   =>$settings->photos[$i]->button_style,
            'button_background_color'        =>$settings->photos[$i]->button_background_color,
            'button_background_hover_color'  =>$settings->photos[$i]->button_background_hover_color,
            'button_text_color'              =>$settings->photos[$i]->button_text_color,
            'button_text_hover_color'        =>$settings->photos[$i]->button_text_hover_color,
            'button_border_style'            =>$settings->photos[$i]->button_border_style,
            'button_border_width'            =>$settings->photos[$i]->button_border_width,
            'button_border_radius'           =>$settings->photos[$i]->button_border_radius,
            'button_border_color'            =>$settings->photos[$i]->button_border_color,
            'button_border_hover_color'      =>$settings->photos[$i]->button_border_hover_color,
            'button_box_shadow'              =>$settings->photos[$i]->button_box_shadow,
            'button_box_shadow_color'        =>$settings->photos[$i]->button_box_shadow_color,
            'button_padding'                 =>$settings->photos[$i]->button_padding,
            'button_margin'                  =>$settings->photos[$i]->button_margin,
            // Icon Style
            'icon_color'                    =>$settings->photos[$i]->icon_color,
            'icon_hover_color'              =>$settings->photos[$i]->icon_hover_color,
            'icon_padding'                  =>$settings->photos[$i]->icon_padding,
            'icon_margin'                   =>$settings->photos[$i]->icon_margin,
            'transition'                    =>$settings->photos[$i]->transition,
            'width'                         =>$settings->photos[$i]->width,
            'custom_width'                  =>$settings->photos[$i]->custom_width,
            'custom_height'                 =>$settings->photos[$i]->custom_height,
            'alignment'                     =>$settings->photos[$i]->alignment,
            //Button Typography
            'button_font_family'            =>$settings->photos[$i]->button_font_family,
            'button_font_size'              =>$settings->photos[$i]->button_font_size,
            //icon Typography
            'icon_font_size'                =>$settings->photos[$i]->icon_font_size,
            'separator_normal_width'        =>$settings->photos[$i]->separator_normal_width,
            'separator_icon_font_size'      =>$settings->photos[$i]->separator_icon_font_size,
            'separator_icon_font_color'     =>$settings->photos[$i]->separator_icon_font_color,
            'separator_text_font_size'      =>$settings->photos[$i]->separator_text_font_size,
            'separator_text_font_color'     =>$settings->photos[$i]->separator_text_font_color,
            'separator_margintb'            =>$settings->photos[$i]->separator_margintb,
            'separator_border_width'        =>$settings->photos[$i]->separator_border_width,
            'separator_border_style'        =>$settings->photos[$i]->separator_border_style,
            'separator_border_color'        =>$settings->photos[$i]->separator_border_color,
            'heading_title_color'           =>$settings->photos[$i]->heading_title_color,
            'heading_sub_title_color'       =>$settings->photos[$i]->heading_sub_title_color,
            'heading_title_font'            =>$settings->photos[$i]->heading_title_font,
            'heading_title_font_size'       =>$settings->photos[$i]->heading_title_font_size,
            'heading_title_line_height'     =>$settings->photos[$i]->heading_title_line_height,
            'heading_sub_title_font'        =>$settings->photos[$i]->heading_sub_title_font,
            'heading_sub_title_font_size'   =>$settings->photos[$i]->heading_sub_title_font_size,
            'heading_sub_title_line_height' =>$settings->photos[$i]->heading_sub_title_line_height,
            'icon_position'                 =>$settings->photos[$i]->icon_position,
            'heading_title_alignment'       =>$settings->photos[$i]->heading_title_alignment,
            'heading_sub_title_alignment'   =>$settings->photos[$i]->heading_sub_title_alignment,
            'heading_margin'                =>$settings->photos[$i]->heading_margin,
            'heading_subtitle_margin'       =>$settings->photos[$i]->heading_subtitle_margin
        );
        FLBuilder::render_module_css('njba-advance-cta' , $cta_id, $cta_css_array);
       
        
         switch($settings->photos[$i]->cta_column){
    
            case  '50_50':
                $njba_cta_text = '50%';
                $njba_btn_main = '50%';
                break;
            
            case  '60_40':
                $njba_cta_text = '60%';
                $njba_btn_main = '40%';
                break;
            
            case  '70_30':
                $njba_cta_text = '70%';
                $njba_btn_main = '30%';
                break;
            
            case  '80_20':
                $njba_cta_text = '80%';
                $njba_btn_main = '20%';
                break;
        }
        ?>
<?php if($settings->photos[$i]->cta_layout === 'inline'){ ?>
.fl-node-<?php echo $id; ?> .njba-slide-id-<?php echo $i; ?> .njba-cta-module-content.inline .njba-cta-text {
    width: <?php echo $njba_cta_text;?>
}

.fl-node-<?php echo $id; ?> .njba-slide-id-<?php echo $i; ?> .njba-cta-module-content.inline .njba-btn-main {
    width: <?php echo $njba_btn_main;?>
}

<?php } ?>

<?php if($settings->image_height !== ''){ ?>
.fl-node-<?php echo $id; ?> img.njba-slider-image-responsive {
    height: <?php echo $settings->image_height.'px';?>
}

<?php } ?>

<?php if($settings->photos[$i]->cta_layout === 'stacked'){?>
.fl-node-<?php echo $id; ?> .njba-slide-id-<?php echo $i; ?> .njba-cta-module-content.stacked .njba-cta-text {
    width: 70%;
}

.fl-node-<?php echo $id; ?> .njba-slide-id-<?php echo $i; ?> .njba-cta-module-content.stacked .njba-btn-main {
    width: 100%;
}

.fl-node-<?php echo $id; ?> .njba-slide-id-<?php echo $i; ?> .njba-cta-module-content.stacked {
    width: auto;
}

.fl-node-<?php echo $id; ?> .njba-slide-id-<?php echo $i; ?> .njba-cta-box-main-stacked {
    top: calc(<?php echo $x_coordinate; ?>% - 0%);
    left: calc(<?php echo $y_coordinate; ?>% - 0%);

}

<?php }
?>
.fl-node-<?php echo $id; ?> .njba-slide-id-<?php echo $i; ?> .njba-cta-box-main-stacked .njba-cta-module-content,
.fl-node-<?php echo $id; ?> .njba-slide-id-<?php echo $i; ?> .njba-cta-box-main-inline .njba-cta-module-content {
    background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->photos[$i]->cta_box_bg_color)) ?>, <?php echo $settings->photos[$i]->cta_box_bg_opc/100; ?>);

}

@media ( max-width: 767px ) {
    .fl-node-<?php echo $id; ?> .njba-slide-id-<?php echo $i; ?> .njba-cta-module-content.inline .njba-cta-text {
        width: 100%;
        text-align: center;
    }

    .fl-node-<?php echo $id; ?> .njba-slide-id-<?php echo $i; ?> .njba-btn-main {
        width: 100%;
    }
}

<?php
}
//die();
?>
.fl-node-<?php echo $id; ?> .njba-slider-main .bx-thumbnail-pager i {
    background-color: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->toggle_bg_color)) ?>, <?php echo $settings->toggle_bg_opc/100; ?>);
<?php if( $settings->toggle_color  !== null) { ?> color: <?php echo '#'.$settings->toggle_color; ?>;
<?php } ?>

}

<?php if($settings->dot == 2 ){  ?>
.fl-node-<?php echo $id; ?> .bx-pager.bx-default-pager {
    display: none;
}

<?php }?>
.fl-node-<?php echo $id; ?> .njba-slider-main a.bx-pager-link {
<?php if( $settings->dot_color ) { ?> background: <?php echo '#'.$settings->dot_color; ?><?php } ?>;
    opacity: 0.5;
}

.fl-node-<?php echo $id; ?> .njba-slider-main a.bx-pager-link.active {
<?php if( $settings->active_dot_color ) { ?> background: <?php echo '#'.$settings->active_dot_color; ?>;
<?php } ?> opacity: 1;
}

.fl-node-<?php echo $id; ?> .njba-slider-main .bx-thumbnail-pager_section a.bx-pager-link {
<?php if( $settings->dot_color ) { ?> background: <?php echo '#'.$settings->dot_color; ?><?php } ?>;
    opacity: 1;
}

.fl-node-<?php echo $id; ?> .njba-slider-main .bx-wrapper .bx-controls-direction a.bx-next,
.fl-node-<?php echo $id; ?> .njba-slider-main .bx-wrapper .bx-controls-direction a.bx-prev {
<?php if( $settings->arrow_background ) { ?> background: rgba(<?php echo implode(',', FLBuilderColor::hex_to_rgb($settings->arrow_background)) ?>, <?php echo $settings->arrow_background_opacity/100; ?>);
<?php } ?> <?php if( $settings->arrow_color ) { ?> color: <?php echo '#'.$settings->arrow_color; ?>;
<?php } ?>

}

<?php if($settings->desktop_device){?>
.fl-node-<?php echo $id; ?> .njba-cta-box-main-inline,
.fl-node-<?php echo $id; ?> .njba-cta-box-main-stacked {
    display: <?php echo $settings->desktop_device; ?>;
}

<?php } ?>
@media ( max-width: 991px ) {
<?php if($settings->medium_device){?>
    .fl-node-<?php echo $id; ?> .njba-cta-box-main-inline,
    .fl-node-<?php echo $id; ?> .njba-cta-box-main-stacked {
        display: <?php echo $settings->medium_device; ?>;
    }

<?php } ?>
<?php if($settings->thumbnail_medium_device === 'none'){?>
    .fl-node-<?php echo $id; ?> .bx-thumbnail-pager_section {
        display: none;
    }

    .fl-node-<?php echo $id; ?> .njba-cta-box-main-inline {
        margin-bottom: 0;
    }

<?php } ?>
    .njba-slider-main .njba-cta-box-main-stacked .njba-cta-box-body {
        width: 90%;
        height: 100%;
        display: table;
        text-align: center;
    }

    .njba-slider-main .njba-cta-box-main-stacked .njba-cta-box-content {
        vertical-align: middle;
        width: 100%;
        height: 100%;
        display: table-cell;
    }

    .njba-slider-main .njba-cta-box-main-stacked {
        left: 0 !important;
        right: 0;
        width: 80%;
        top: 0 !important;
    }

    .njba-slider-main .njba-cta-box-main-stacked .njba-heading-title {
        margin-top: 0;
    }

    .njba-slider-main .njba-cta-box-main-stacked .njba-heading-sub-title {
        margin-top: 10px;
    }

    .njba-slider-main .njba-cta-box-main-stacked .njba-btn-main a.njba-btn {
        margin-top: 0;
        padding: 10px 20px;
        margin-bottom: 15px;
    }
}

@media ( max-width: 767px ) {
<?php if($settings->small_device){?>
    .fl-node-<?php echo $id; ?> .njba-cta-box-main-inline,
    .fl-node-<?php echo $id; ?> .njba-cta-box-main-stacked {
        display: <?php echo $settings->small_device; ?>;
    }

<?php } ?>
<?php if($settings->thumbnail_small_device === 'none'){?>
    .fl-node-<?php echo $id; ?> .bx-thumbnail-pager_section {
        display: none;
    }

    .fl-node-<?php echo $id; ?> .njba-cta-box-main-inline {
        margin-bottom: 0;
    }

<?php } ?>
}

<!--
<?php /* $settings->button_border_radius = (array)$settings->button_border_radius; ?>
<?php $settings->button_box_shadow = (array)$settings->button_box_shadow; ?>
<?php $settings->button_padding = (array)$settings->button_padding; ?>
<?php $settings->button_margin = (array)$settings->button_margin; ?>
<?php $settings->button_font_family = (array)$settings->button_font_family; ?>
<?php $settings->button_font_size = (array)$settings->button_font_size; ?>
<?php $settings->icon_padding = (array)$settings->icon_padding; ?>
<?php $settings->icon_margin = (array)$settings->icon_margin; ?>
<?php $settings->icon_font_size = (array)$settings->icon_font_size; ?>
<?php $settings->cta_title_margin = (array)$settings->cta_title_margin; ?>
<?php $settings->cta_title_font_family = (array)$settings->cta_title_font_family; ?>
<?php $settings->cta_head_font_size = (array)$settings->cta_head_font_size; ?>
<?php $settings->cta_head_line_height = (array)$settings->cta_head_line_height; ?>
<?php $settings->cta_subhead_font_family = (array)$settings->cta_subhead_font_family; ?>
<?php $settings->cta_subhead_font_size = (array)$settings->cta_subhead_font_size; ?>
<?php $settings->cta_subhead_line_height = (array)$settings->cta_subhead_line_height; ?>
<?php $settings->cta_subhead_margin = (array)$settings->cta_subhead_margin; */?>
-->
