<div class="njba-infobox-sub-main <?php if ( $settings->image_type === 'icon' ) {
	echo 'njba-infobox-icon-set';
} ?>">
	<?php
	if ( $settings->cta_type === 'complete_box' && $settings->link !== '' ) {
		?>
        <a href="<?php echo $settings->link; ?>" class="njba-link-infobox-module" target="<?php echo $settings->link_target; ?>"></a>
		<?php
	}
	?>
	<?php
	$img_src = '';
	if ( ! empty( $settings->info_photo_src ) ) {
		$img_src = $settings->info_photo_src;
	}
	$img_icon = array(
		'image_type'                 => $settings->image_type,
		'overall_alignment_img_icon' => $settings->overall_alignment,
		'photo'                      => $settings->info_photo,
		'photo_src'                  => $img_src,
		'icon'                       => $settings->icon
	);
	FLBuilder::render_module_html( 'njba-icon-img', $img_icon );
	?>

    <div class="njba-infobox-contant">
		<?php if ( $settings->heading_prefix !== '' ) {
			echo '<' . $settings->prefix_tag_selection . ' class="heading_prefix">' . $settings->heading_prefix . '</' . $settings->prefix_tag_selection . '>';
		} ?>
		<?php if ( $settings->title !== '' ) {
			echo '<' . $settings->title_tag_selection . ' class="heading">' . $settings->title . '</' . $settings->title_tag_selection . '>';
		} ?>
		<?php if ( $settings->text !== '' ) {
			echo $settings->text;
		} ?>
		<?php if ( $settings->cta_type !== 'none' ) { ?>
			<?php if ( $settings->cta_type === 'link' ) { ?>
                <a href="<?php echo $settings->link; ?>" class="njba-infobox-link"
                   target="<?php echo $settings->link_target; ?>"><?php echo $settings->button_text; ?></a>
			<?php } ?>
			<?php if ( $settings->cta_type === 'button' ) { ?>
				<?php
				$btn_settings = array(
					//Button text
					'button_text'                   => $settings->button_text,
					//Button Link
					'link'                          => $settings->link,
					'link_target'                   => $settings->link_target,
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
					'transition'                    => $settings->transition,
					'width'                         => $settings->width,
					'custom_width'                  => $settings->custom_width,
					'custom_height'                 => $settings->custom_height,
					'alignment'                     => $settings->overall_alignment,
					//Button Typography
					'button_font_family'            => $settings->button_font_family,
					'button_font_size'              => $settings->button_font_size,
				);
				?>
				<?php FLBuilder::render_module_html( 'njba-button', $btn_settings ); ?>
			<?php } ?>
		<?php } ?>
    </div>
</div>
