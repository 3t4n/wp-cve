<div class="njba-image-panels-wrap clearfix">
    <div class="njba-image-panels-inner">
		<?php
		$number_panels = count( $settings->image_panels );
		for ( $i = 0; $i < $number_panels; $i ++ ) {
			if ( ! is_object( $settings->image_panels[ $i ] ) ) {
				continue;
			}
			$panel = $settings->image_panels[ $i ];
			?>
			<?php if ( $panel->link_type === 'panel' ) { ?>
                <a class="njba-panel-link njba-panel-link-<?php echo $i; ?>" href="<?php echo $panel->link; ?>" target="<?php echo $panel->link_target; ?>" style="width: <?php echo 100 / ( $number_panels ); ?>%;">
			<?php } ?>
            <div class="njba-panel-item njba-panel-item-<?php echo $i; ?> clearfix"
                 style="width: <?php echo $panel->link_type !== 'panel' ? 100 / ( $number_panels ) . '%;' : '' ?>">
				<?php if ( $panel->title ) { ?>
                    <div class="njba-panel-title">
						<?php if ( $panel->link_type === 'title' ) { ?>
                        <a class="njba-panel-link" href="<?php echo $panel->link; ?>" target="<?php echo $panel->link_target; ?>">
							<?php } ?>
                            <h3 class="njba-panel-title-selector"><?php echo $panel->title; ?></h3>
							<?php if ( $panel->link_type === 'title' ) { ?>
                        </a>
					<?php } ?>
                    </div>
				<?php } ?>
            </div>
			<?php if ( $panel->link_type === 'panel' ) { ?>
                </a>
			<?php } ?>
			<?php
		}
		?>
    </div>
</div>
