<?php
// Require object
if (empty($ics_data)) { return false; }

global $R34ICS;

$days_of_week = $R34ICS->get_days_of_week($args['columnlabels']);
$start_of_week = get_option('start_of_week', 0);

$date_format = r34ics_date_format($args['format'], true);

$today = r34ics_date('Ymd');

$ics_calendar_classes = apply_filters('r34ics_calendar_classes', null, $args, true);

// Special handling for instances where limitdays and/or startdate are set
// Note: This was simplified to correspond with shortcode value logic changes in v. 10.10.1 and may need more testing
$fixed_dates = false;
if ($args['limitdays'] > 0 && $args['limitdays'] <= 7) {
	$fixed_dates = true;
	// Set startdate to today if not set
	if (empty($args['startdate'])) {
		$args['startdate'] = $today;
	}
	$enddate = r34ics_date('Ymd', $args['startdate'], null, '+' . intval($args['limitdays'] - 1) . ' days');
}

// Feed colors custom CSS
if (!empty($ics_data['colors'])) {
	r34ics_feed_colors_css($ics_data);
}

// Prepare event details toggle lightbox
if ($args['toggle'] === 'lightbox') {
	r34ics_lightbox_container();
}
?>

<section class="<?php echo esc_attr($ics_calendar_classes); ?>" id="<?php echo esc_attr($ics_data['guid']); ?>" style="opacity: 0;">

	<?php
	// Title and description
	if (!empty($ics_data['title'])) {
		?>
		<<?php echo esc_attr($args['htmltagtitle']); ?> class="ics-calendar-title"><?php echo wp_kses_post($ics_data['title']); ?></<?php echo esc_attr($args['htmltagtitle']); ?>>
		<?php
	}
	if (!empty($ics_data['description'])) {
		?>
		<p class="ics-calendar-description"><?php echo wp_kses_post($ics_data['description']); ?></p>
		<?php
	}

	// Actions before rendering calendar wrapper (can include additional template output)
	do_action('r34ics_display_calendar_before_wrapper', $view, $args, $ics_data);

	// Color code key
	if (empty($args['legendposition']) || $args['legendposition'] == 'above') {
		echo $R34ICS->color_key_html($args, $ics_data);
	}

	// Display calendar
	if (empty($fixed_dates)) {
		?>
		<select class="ics-calendar-select" style="display: none;" autocomplete="off">
			<option value="previous-week"><?php _e('Last week', 'r34ics'); ?></option>
			<option value="current-week" selected="selected"><?php _e('This week', 'r34ics'); ?></option>
			<option value="next-week"><?php _e('Next week', 'r34ics'); ?></option>
		</select>
		<?php
	}

	// Toggle show/hide past events on mobile
	if ($args['startdate'] != $today) {
		?>
		<p class="ics-calendar-past-events-toggle phone_only inline_block" aria-hidden="true"><a href="#" data-ics-calendar-action="show-past-events"><?php _e('Show past events','r34ics'); ?></a></p>
		<?php
	}
	?>

	<article class="ics-calendar-week-wrapper" style="display: none;">
		<table class="ics-calendar-month-grid<?php if (!empty($fixed_dates)) { echo ' fixed_dates'; } ?>">
			<thead>
				<tr>
					<?php
					if (!empty($args['weeknumbers'])) {
						?>
						<th class="week-number">&nbsp;</th>
						<?php
					}
					if (!empty($fixed_dates)) {
						$day_for_start = r34ics_date('j', $args['startdate']);
						$day_for_max = $day_for_start + ($args['limitdays'] - 1);
						for ($day = $day_for_start; $day <= $day_for_max; $day++) {
							$w = r34ics_date('w', $args['startdate'], null, '+' . ($day-$day_for_start) . ' days');
							?>
							<th data-dow="<?php echo esc_attr($w); ?>"><?php echo wp_kses_post($days_of_week[$w]); ?></th>
							<?php
						}
					}
					else {
						foreach ((array)$days_of_week as $w => $dow) {
							?>
							<th data-dow="<?php echo esc_attr($w); ?>"><?php echo wp_kses_post($dow); ?></th>
							<?php
						}
					}
					?>
				</tr>
			</thead>

			<tbody><tr>
				<?php
				if (!empty($args['weeknumbers'])) {
					$wknum = r34ics_date('W', $ics_data['earliest']);
					?>
					<th class="week-number" data-wknum="<?php echo esc_attr($wknum); ?>"><?php echo wp_kses_post($wknum); ?></th>
					<?php
				}
				foreach (array_keys((array)$ics_data['events']) as $year) {
					for ($m = 1; $m <= 12; $m++) {
						$month = $m < 10 ? '0' . $m : '' . $m;
						$ym = $year . $month;
						// Is this month in range? If not, continue
						if (!r34ics_month_in_range($ym, $ics_data)) { continue; }
						$first_dow = $R34ICS->first_dow($m.'/1/'.$year);
						if ($first_dow < $start_of_week) { $first_dow = $first_dow + 7; }
						if (!isset($start_fill)) {
							if (empty($fixed_dates)) {
								for ($off_dow = $start_of_week; $off_dow < $first_dow; $off_dow++) {
									?>
									<td class="off" data-dow="<?php echo intval($off_dow); ?>"></td>
									<?php
								}
							}
							$start_fill = true;
						}
						if (!empty($fixed_dates) && $month == substr(($args['startdate'] ?? ''),4,2)) {
							$day_for_start = r34ics_date('j', $args['startdate']);
							$day_for_max = min(array($day_for_start + ($args['limitdays'] - 1), r34ics_date('t',$m.'/1/'.$year)));
						}
						else {
							$day_for_start = 1;
							$day_for_max = r34ics_date('t',$m.'/1/'.$year);
						}
						for ($day = $day_for_start; $day <= $day_for_max; $day++) {
							$date = r34ics_date('Ymd', $m.'/1/'.$year, null, '+' . ($day-1) . ' days');
							// Exclude dates out of range
							if (!empty($fixed_dates)) {
								// Month does not fall within range of start and end dates
								if ($date < $startdate || $date > $enddate) { continue(2); }
							}
							$d = r34ics_date('d', $date);
							$dow = r34ics_date('w', $date);
							$wknum = r34ics_date('W', $date . ' +' . (1 - $start_of_week) . 'day'); // PHP week numbers start on Monday
							$day_events = isset($ics_data['events'][$year][$month][$d]) ? $ics_data['events'][$year][$month][$d] : null;
							$day_events_count = r34ics_day_events_count($day_events);
							$day_feed_keys = r34ics_day_events_feed_keys($day_events, '|');
							$day_uid = $ics_data['guid'] . '-' . $ym . $d;
							$day_classes = r34ics_day_classes(array(
								'date' => $date,
								'today' => $today,
								'count' => $day_events_count,
								'filler' => !empty($day_events['all-day'][0]['filler'])
							));
							if (empty($fixed_dates) && $dow == $start_of_week) {
								if ($day >= $day_for_start) { echo '</tr><tr>'; }
								if (!empty($args['weeknumbers'])) {
									?>
									<th class="week-number" data-wknum="<?php echo esc_attr($wknum); ?>"><?php echo wp_kses_post($wknum); ?></th>
									<?php
								}
							}
							?>
							<td data-dow="<?php echo intval($dow); ?>" data-events-count="<?php echo intval($day_events_count); ?>" data-feed-keys="<?php echo esc_attr($day_feed_keys); ?>" class="<?php echo esc_attr($day_classes); ?>">
								<div class="day">
									<span class="phone_only" id="<?php echo esc_attr($day_uid); ?>"><?php echo r34ics_date($date_format, $date); ?></span>
									<span class="no_phone" aria-hidden="true"><?php echo r34ics_date('j', $date); ?></span>
								</div>
								<?php
								if (!empty($day_events)) {
									?>
									<ul class="events" aria-labelledby="<?php echo esc_attr($day_uid); ?>">
										<?php
										foreach ((array)$day_events as $time => $events) {
											$all_day_indicator_shown = !empty($args['hidealldayindicator']);
											foreach ((array)$events as $event) {
												$has_desc = r34ics_has_desc($args, $event);
												if ($time == 'all-day') {
													?>
													<li class="<?php echo r34ics_event_css_classes($event, $time, $args); ?>" data-feed-key="<?php echo intval($event['feed_key']); ?>"<?php
														if (!empty($ics_data['colors'][$event['feed_key']]['base'])) { echo ' data-feed-color="' . esc_attr($ics_data['colors'][$event['feed_key']]['base']) . '"'; }
														if (!empty($event['categories'])) { echo ' data-categories="' . esc_attr($event['categories']) . '"'; }
													?>>
														<?php
														if (!$all_day_indicator_shown) {
															?>
															<span class="all-day-indicator"><?php _e('All Day', 'r34ics'); ?></span>
															<?php
															$all_day_indicator_shown = true;
														}

														// Event label (title)
														echo $R34ICS->event_label_html($args, $event, (!empty($has_desc) ? array('has_desc') : null));

														// Sub-label
														echo $R34ICS->event_sublabel_html($args, $event, null);

														// Description/Location/Organizer
														echo $R34ICS->event_description_html($args, $event, (empty($args['toggle']) ? array('hover_block') : null), $has_desc);
														?>
													</li>
													<?php
												}
												else {
													?>
													<li class="<?php echo r34ics_event_css_classes($event, $time, $args); ?>" data-feed-key="<?php echo intval($event['feed_key']); ?>" <?php if (!empty($ics_data['colors'][$event['feed_key']]['base'])) { echo ' data-feed-color="' . esc_attr($ics_data['colors'][$event['feed_key']]['base']) . '"'; } ?><?php
														if (!empty($event['categories'])) { echo ' data-categories="' . esc_attr($event['categories']) . '"'; }
													?>>
														<?php
														if (!empty($event['start'])) {
															?>
															<span class="time"><?php
															echo wp_kses_post($event['start']);
															if (!empty($event['end']) && $event['end'] != $event['start']) {
																if (empty($args['showendtimes'])) {
																	?>
																	<span class="end_time show_on_hover">&#8211; <?php echo wp_kses_post($event['end']); ?></span>
																	<?php
																}
																else {
																	?>
																	<span class="end_time">&#8211; <?php echo wp_kses_post($event['end']); ?></span>
																	<?php
																}
															}
															?></span>
															<?php
														}

														// Event label (title)
														echo $R34ICS->event_label_html($args, $event, (!empty($has_desc) ? array('has_desc') : null));

														if (!empty($event['sublabel'])) {
															?>
															<span class="sublabel"><?php
															if (empty($event['start']) && !empty($event['end'])) {
																?>
																<span class="carryover">&#10554;</span>
																<?php
															}
															echo str_replace('/', '/<wbr />',$event['sublabel']);
															?></span>
															<?php
														}
														// Description/Location/Organizer
														echo $R34ICS->event_description_html($args, $event, (empty($args['toggle']) ? array('hover_block') : null), $has_desc);
														?>
													</li>
													<?php
												}
											}
										}
										?>
									</ul>
									<?php
								}
								?>
							</td>
							<?php
						}
					}
				}
				?>
			</tr></tbody>
		</table>
		
		<?php
		// Message for mobile breakpoint when there are no events returned
		if (empty($ics_data['events']) || r34ics_is_empty_array($ics_data['events'])) {
			?>
			<p class="phone_only no_events">
				<?php _e('No events.', 'r34ics'); ?>
			</p>
			<?php
		}
		?>

	</article>

	<?php
	// Color code key
	if (!empty($args['legendposition']) && $args['legendposition'] == 'below') {
		echo $R34ICS->color_key_html($args, $ics_data);
	}

	// Actions after rendering calendar wrapper (can include additional template output)
	do_action('r34ics_display_calendar_after_wrapper', $view, $args, $ics_data);
	?>

</section>
