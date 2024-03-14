<div class="njba-opening-times-main">
    <ul class="njba-opening-times-list  <?php echo $settings->layout; ?>">
		<?php
		$number_panels = count( $settings->day_panels );
		for ( $i = 0; $i < $number_panels; $i ++ ) {
			if ( $i < 7 ) {
				if ( ! is_object( $settings->day_panels[ $i ] ) ) {
					continue;
				}
				$panel = $settings->day_panels[ $i ];
				?>
                <li>
                    <span class="njba-opening-day"><?php echo $panel->day; ?></span>
                    <div class="njba-opening-hours">
						<?php if ( $panel->time ) { ?> <span><?php echo $panel->time; ?></span><?php } ?>
						<?php if ( $panel->time_2 !== '' ) {
							if ( $panel->time_separator !== 'none' ) { ?><span class="njba-opening-hours-separator"><?php echo $panel->time_separator; ?></span><?php } ?>
                            <span><?php echo $panel->time_2; ?></span><?php } ?>
                    </div>
                </li>
				<?php
			}
		}
		?>
    </ul>
</div>
