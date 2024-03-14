<?php switch($settings->cta_column){
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
<?php if($settings->cta_layout === 'inline'){?>
.fl-node-<?php echo $id; ?> .njba-cta-text {
    width: <?php echo $njba_cta_text;?>
}

.fl-node-<?php echo $id; ?> .njba-btn-main {
    width: <?php echo $njba_btn_main;?>
}

<?php } ?>
<?php if($settings->cta_layout === 'stacked'){?>
.fl-node-<?php echo $id; ?> .njba-cta-text {
    width: 100%;
}

.fl-node-<?php echo $id; ?> .njba-btn-main {
    width: 100%;
}

<?php } ?>
.fl-node-<?php echo $id; ?> .njba-cta-module-content {
    width: 100%;
}

<?php
        $btn_css_array = array(
            //Button Style
            'button_style'                  => $settings->button_style,
            'button_background_color'       => $settings->button_background_color,
            'button_background_hover_color' => $settings->button_background_hover_color,
            'button_text_color'             => $settings->button_text_color,
            'button_text_hover_color'       => $settings->button_text_hover_color,
            'button_border_style'           => $settings->button_border_style,
            'button_border_width'           => $settings->button_border_width,
            'button_border_radius'          => $settings->button_border_radius,
            'button_border_color'           => $settings->button_border_color,
            'button_border_hover_color'     => $settings->button_border_hover_color,
            'button_box_shadow'             => $settings->button_box_shadow,
            'button_box_shadow_color'       => $settings->button_box_shadow_color,
            'button_padding'                => $settings->button_padding,
            'button_margin'                 => $settings->button_margin,
            // Icon Style
            'icon_color'                    => $settings->icon_color,
            'icon_hover_color'              => $settings->icon_hover_color,
            'icon_padding'                  => $settings->icon_padding,
            'icon_margin'                   => $settings->icon_margin,
            'transition'                    => $settings->transition,
            'width'                         => $settings->width,
            'custom_width'                  => $settings->custom_width,
            'custom_height'                 => $settings->custom_height,
            'alignment'                     => $settings->alignment,
            //Button Typography
            'button_font_family'            => $settings->button_font_family,
            'button_font_size'              => $settings->button_font_size,
            //icon Typography
            'icon_font_size'                => $settings->icon_font_size,
        );
        FLBuilder::render_module_css('njba-button' , $id, $btn_css_array);
        $heading_css      = array(
            'separator_type'                => $settings->separator_type,
            'separator_normal_width'        => $settings->separator_normal_width,
            'separator_icon_font_size'      => $settings->separator_icon_font_size,
            'separator_icon_font_color'     => $settings->separator_icon_font_color,
            'separator_text_font_size'      => $settings->separator_text_font_size,
            'separator_text_line_height'    => $settings->separator_text_line_height,
            'separator_text_font_color'     => $settings->separator_text_font_color,
            'separator_margintb'            => $settings->separator_margintb,
            'separator_border_width'        => $settings->separator_border_width,
            'separator_border_style'        => $settings->separator_border_style,
            'separator_border_color'        => $settings->separator_border_color,
            'heading_title_color'           => $settings->heading_title_color,
            'heading_sub_title_color'       => $settings->heading_sub_title_color,
            'heading_title_font'            => $settings->heading_title_font,
            'heading_title_font_size'       => $settings->heading_title_font_size,
            'heading_title_line_height'     => $settings->heading_title_line_height,
            'heading_sub_title_font'        => $settings->heading_sub_title_font,
            'heading_sub_title_font_size'   => $settings->heading_sub_title_font_size,
            'heading_sub_title_line_height' => $settings->heading_sub_title_line_height,
            'icon_position'                 => $settings->icon_position,
            'heading_title_alignment'       => $settings->heading_title_alignment,
            'heading_sub_title_alignment'   => $settings->heading_sub_title_alignment,
            'heading_margin'                => $settings->heading_margin,
            'heading_subtitle_margin'       => $settings->heading_subtitle_margin
        );
        FLBuilder::render_module_css('njba-heading' , $id, $heading_css);
?>
@media ( max-width: 767px ) {
    .fl-node-<?php echo $id; ?> .njba-cta-text {
        width: 100%;
    }

    .fl-node-<?php echo $id; ?> .njba-heading-title,
    .fl-node-<?php echo $id; ?> .njba-heading-sub-title {
        text-align: center;
    }

    .fl-node-<?php echo $id; ?> .njba-btn-main {
        width: 100%;
        text-align: center;
    }
}
