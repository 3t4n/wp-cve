<div class="pp-business-hours-content clearfix" itemscope itemtype="http://schema.org/LocalBusiness">
	<meta itemprop="name" content="<?php echo get_bloginfo('name'); ?>" />
	<?php
	// Fetch logo from theme or filter.
	$image = '';
	if ( is_callable( 'FLTheme::get_setting' ) && 'image' == FLTheme::get_setting( 'fl-logo-type' ) ) {
		$image = FLTheme::get_setting( 'fl-logo-image' );
	} elseif ( function_exists( 'has_custom_logo' ) && has_custom_logo() ) {
		$custom_logo_id = get_theme_mod( 'custom_logo' );
		$logo           = wp_get_attachment_image_src( $custom_logo_id, 'full' );
		$image          = $logo[0];
	}
	$image = apply_filters( 'pp_business_hours_publisher_image_url', $image );
	if ( $image ) {
		echo '<div itemprop="image" itemscope itemtype="https://schema.org/ImageObject">';
		echo '<meta itemprop="url" content="' . $image . '">';
		echo '</div>';
	}
	?>

	<?php
	$rows = count( $settings->business_hours_rows );

	for ( $i = 0; $i < $rows; $i++ ) :

		if ( ! is_object( $settings->business_hours_rows[ $i ] ) ) continue;

		$timing    = $settings->business_hours_rows[ $i ];
		$status    = '';
		$highlight = '';

		if ( $timing->status == 'close' ) {
			$status = ' pp-closed';
		}
		if ( $timing->highlight == 'yes' ) {
			$highlight = ' pp-highlight-row';
		}
		?>
		<div itemprop="openingHoursSpecification" itemscope="itemscope" itemtype="https://schema.org/OpeningHoursSpecification" class="pp-bh-row clearfix pp-bh-row-<?php echo $i; ?><?php echo $status; ?><?php echo $highlight; ?>">
			<div class="pp-bh-title"><?php $module->render_time_title( $timing ); ?></div>
			<div class="pp-bh-timing">
				<?php
				if ( $timing->status == 'close' ) {
					echo $timing->status_text;
				} else {
					$opening_time = $module->get_timing( $timing->start_time );;
					$closing_time = $module->get_timing( $timing->end_time );;
				
					if ( $timing->hours_type == 'day' ) {
						echo sprintf(
							'<time itemprop="opens" content="%1$s">%1$s</time> &ndash; <time itemprop="closes" content="%2$s">%2$s</time>',
							$opening_time,
							$closing_time
						);
					} else {
						$datetime 	= array();
						$start_day 	= 0;
						$end_day 	= 0;
						
						foreach ( pp_long_day_format() as $day => $label ) {
							if ( $day == $timing->start_day ) {
								$start_day = 1;
							}
							if ( ! $start_day ) {
								continue;
							}
							if ( $end_day ) {
								break;
							}
							if ( $day == $timing->end_day ) {
								$end_day = 1;
							}
							$datetime[] = substr( $day, 0, 2 );
						}

						$datetime_str = implode( ',', $datetime );
						$datetime_str .= ' ';
						$datetime_str .= $opening_time;
						$datetime_str .= '-';
						$datetime_str .= $closing_time;

						echo sprintf(
							'<time itemprop="openingHours" datetime="%s">%s &ndash; %s</time>',
							$datetime_str,
							$opening_time,
							$closing_time
						);
					}
				} ?>
			</div>
		</div>
		<?php
	endfor; ?>
</div>
