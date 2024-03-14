<div class="njba-infolist">
	<?php
	$total_infolist = count( $settings->info_list_content );
	for ( $i = 0; $i < $total_infolist; $i ++ ) {
		$box_content = $settings->info_list_content[ $i ];
		if ( $total_infolist == 1 ) {
			$njba_column = 'njba-col-xs-1';
		}
		if ( $total_infolist == 2 ) {
			$njba_column = 'njba-col-xs-1 njba-col-sm-2';
		}
		if ( $total_infolist == 3 ) {
			$njba_column = 'njba-col-xs-1 njba-col-sm-2 njba-col-md-3';
		}
		if ( $total_infolist == 4 ) {
			$njba_column = 'njba-col-xs-1 njba-col-sm-2 njba-col-md-4';
		}
		if ( $total_infolist >= 5 ) {
			$njba_column = 'njba-col-xs-1 njba-col-sm-2 njba-col-md-4 njba-col-lg-5';
		}
		?>
        <div class="<?php echo $njba_column; ?> njba-infolist-sec <?php if ( $box_content->image_type === 'icon' ) {
			echo 'njba-infolist-icon-set ';
		} ?><?php echo 'njba-infolist-list-' . $i; ?>">
            <div class="njba-infolist-img-main <?php echo 'position_' . $settings->img_icon_position; ?>">
                <div class="njba-infolist-img">
					<?php if ( $box_content->image_type === 'photo' ) {
						if ( ! empty( $box_content->info_photo_src ) ) {
							$src = $box_content->info_photo_src;
						} else {
							$src = FL_BUILDER_URL . 'img/pixel.png';
						}
						?>
                        <img src="<?php echo $src; ?>" class="njba-img-responsive">
					<?php } ?>
					<?php if ( $box_content->image_type === 'icon' ) { ?>
                        <i class="<?php echo $box_content->icon; ?>" aria-hidden="true"></i>
					<?php } ?>
                </div>
            </div>
            <div class="njba-infolist-content">
				<?php if ( $box_content->title !== '' ) {
					echo '<' . $settings->title_tag_selection . ' class="heading">' . $box_content->title . '</' . $settings->title_tag_selection . '>';
				} ?>
				<?php if ( $box_content->text !== '' ) {
					echo $box_content->text;
				} ?>
            </div>
			<?php
			if ( $settings->show_connector === 'yes' ) {
				?>
				<?php if ( $settings->img_icon_position !== 'center' ) { ?>
                    <div class="njba-info-list-connector-top njba-info-list-<?php echo $settings->img_icon_position; ?>"></div>
				<?php } ?>
                <div class="njba-info-list-connector njba-info-list-<?php echo $settings->img_icon_position; ?>"></div>
				<?php
			}
			?>
        </div>
		<?php
	}
	?>
</div>
