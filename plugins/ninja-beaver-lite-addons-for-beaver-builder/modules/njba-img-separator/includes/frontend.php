<?php
$classes = $module->njbaGetImgClass();
$src     = $module->njbaGetImgSrc();
$alt     = $module->njbaGetImgAlt();
?>
<div class="njba-module-content njba-image-seperator-main">
	<?php if ( $settings->enable_link === 'yes' ) : ?>
        <a class="imgseparator-link" href="<?php echo $settings->link; ?>" target="<?php echo $settings->link_target; ?>"></a>
	<?php endif; ?>
    <div class="njba-image-separator njba-image<?php if ( ! empty( $settings->image_style ) ) {
		echo ' njba-crop-image-' . $settings->image_style;
	} ?>" itemscope itemtype="http://schema.org/ImageObject">
		<?php $image_separator = wp_get_attachment_image_src( $settings->photo );
		if ( ! is_wp_error( $image_separator ) ) {
			$photo_src    = $image_separator[0];
			$photo_width  = $image_separator[1];
			$photo_height = $image_separator[2];
		} ?>
        <img class="<?php echo $classes; ?> <?php echo ( $settings->img_animation_repeat == '0' ) ? 'infinite' : ''; ?>" src="<?php echo $src; ?>"
             alt="<?php echo $alt; ?>" itemprop="image"/> <!--width="<?php //echo $photo_width; ?>" height="<?php //echo $photo_height; ?>"-->
    </div>
</div>
