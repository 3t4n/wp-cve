<?php if ( $settings->image_type !== 'none' && ! empty( $settings->image_type ) ) : ?>
    <div class="njba-icon-img-main <?php echo 'position_' . $settings->overall_alignment_img_icon; ?>">
		<?php if ( ! empty( $settings->icon_image_link ) && $settings->img_icon_show_link !== 'no' ) : ?>

        <a href="<?php echo $settings->icon_image_link; ?>" target="<?php echo $settings->icon_image_link_target; ?>">

			<?php endif; ?>
            <div class="njba-icon-img">
				<?php if ( $settings->image_type === 'photo' ) {
					if ( ! empty( $settings->photo_src ) ) {
						$src = $settings->photo_src;
					} else {
						$src = FL_BUILDER_URL . 'img/pixel.png';
					}
					?>
					<?php $icon_image = wp_get_attachment_image_src( $settings->photo );
					if ( ! is_wp_error( $icon_image ) ) {
						$photo_src    = $icon_image[0];
						$photo_width  = $icon_image[1];
						$photo_height = $icon_image[2];
					} ?>
                    <img src="<?php echo $src; ?>" class="njba-img-responsive">
				<?php } ?>
				<?php if ( $settings->image_type === 'icon' ) { ?>
                    <i class="<?php echo $settings->icon; ?>" aria-hidden="true"></i>
				<?php } ?>
            </div>
			<?php if ( ! empty( $settings->icon_image_link ) && $settings->img_icon_show_link !== 'no' ) : ?>

        </a>

	<?php endif; ?>
    </div>
<?php endif; ?>
