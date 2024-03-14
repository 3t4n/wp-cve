<?php
/*
 * Version: 1.4.9
 * Author: Alex Polonski
 * Author URI: http://smartcalc.es
 * License: GPL2
 */
defined ( 'ABSPATH' ) or die ();

define ( 'SCD_BASE_FONT_SIZE', 12 );
abstract class SmartCountdown_Helper {
	private static $assets = array (
			'years',
			'months',
			'weeks',
			'days',
			'hours',
			'minutes',
			'seconds' 
	);
	
	// Responsive Classes actions
	private static $layout_tpls = array (
			'labels_pos' => array (
					'row' => array (
							array (
									'selector' => '.scd-label',
									'remove' => 'scd-label-col scd-label-row',
									'add' => 'scd-label-row' 
							),
							array (
									'selector' => '.scd-digits',
									'remove' => 'scd-digits-col scd-digits-row',
									'add' => 'scd-digits-row' 
							) 
					),
					'col' => array (
							array (
									'selector' => '.scd-label',
									'remove' => 'scd-label-col scd-label-row',
									'add' => 'scd-label-col' 
							),
							array (
									'selector' => '.scd-digits',
									'remove' => 'scd-digits-col scd-digits-row',
									'add' => 'scd-digits-col' 
							) 
					) 
			),
			'layout' => array (
					'vert' => array (
							'selector' => '.scd-unit',
							'remove' => 'scd-unit-vert scd-unit-horz',
							'add' => 'scd-unit-vert clearfix' 
					),
					'horz' => array (
							'selector' => '.scd-unit',
							'remove' => 'scd-unit-vert scd-unit-horz clearfix',
							'add' => 'scd-unit-horz' 
					) 
			),
			'event_text_pos' => array (
					'vert' => array (
							array (
									'selector' => '.scd-title',
									'remove' => 'scd-title-col scd-title-row clearfix',
									'add' => 'scd-title-col clearfix' 
							),
							array (
									'selector' => '.scd-counter',
									'remove' => 'scd-counter-col scd-counter-row clearfix',
									'add' => 'scd-counter-col clearfix' 
							) 
					),
					'horz' => array (
							array (
									'selector' => '.scd-title',
									'remove' => 'scd-title-col scd-title-row clearfix',
									'add' => 'scd-title-row' 
							),
							array (
									'selector' => '.scd-counter',
									'remove' => 'scd-counter-col scd-counter-row clearfix',
									'add' => 'scd-counter-row' 
							) 
					) 
			) 
	)
	;
	public static function getCounterConfig($instance) {
		if (! empty ( $instance ['layout_preset'] )) {
			$file_name = dirname ( __FILE__ ) . '/layouts/' . $instance ['layout_preset'];
			
			if (! file_exists ( $file_name )) {
				// Additional layouts can be stored in alternative folder, if requested file is not
				// present we make another attempt
				$is_alt_animation_dir = true;
				$file_name = dirname ( __FILE__ ) . '/../../smart-countdown-custom-layouts/' . $instance ['layout_preset'];
			}
			if (! file_exists ( $file_name )) {
				// fallback to default layout preset (e.g. for misprints in shortcode)
				$file_name = dirname ( __FILE__ ) . '/layouts/shortcode.xml';
			}
			if (file_exists ( $file_name )) {
				$xml = file_get_contents ( $file_name );
			} else {
				// panic
				return $instance;
			}

			// now XML document should be valid
			libxml_use_internal_errors ( true );
			
			$xml = simplexml_load_string ( $xml );
			
			foreach ( libxml_get_errors () as $error ) {
				// log errors here...
			}
			
			// counter units padding settings
			foreach ( $xml->paddings->children () as $padding ) {
				$padding = $padding->getName ();
				$instance ['paddings'] [$padding] = ( int ) $xml->paddings->$padding;
			}
			
			$instance ['layout'] = ( string ) $xml->layout;
			$instance ['event_text_pos'] = ( string ) $xml->event_text_pos;
			$instance ['labels_pos'] = ( string ) $xml->labels_pos;
			$instance ['labels_vert_align'] = ( string ) $xml->labels_vert_align;
			
			$instance ['hide_highest_zeros'] = ( string ) $xml->hide_highest_zeros;
			$instance ['allow_all_zeros'] = ( string ) $xml->allow_all_zeros;
			
			$responsive = array ();
			
			$is_responsive = $xml->responsive->attributes ();
			$is_responsive = ( int ) $is_responsive ['value'];
			
			if ($is_responsive) {
				
				// screen sizes
				foreach ( $xml->responsive->children () as $scale ) {
					
					$attrs = array ();
					foreach ( $scale->attributes () as $k => $v ) {
						$attrs [$k] = ( string ) $v;
					}
					
					$classes = array ();
					foreach ( $scale->children () as $layout ) {
						$name = $layout->getName ();
						$value = ( string ) $layout;
						
						if (isset ( self::$layout_tpls [$name] ) && isset ( self::$layout_tpls [$name] [$value] )) {
							$classes [] = self::$layout_tpls [$name] [$value];
						}
					}
					
					$responsive [] = array (
							'scale' => $attrs ['value'],
							'alt_classes' => $classes 
					);
				}
				
				// add default scale 1.0 setting
				$classes = array ();
				$classes [] = self::$layout_tpls ['layout'] [$instance ['layout']];
				$labels_pos = $instance ['labels_pos'] == 'right' || $instance ['labels_pos'] == 'left' ? 'row' : 'col';
				$classes [] = self::$layout_tpls ['labels_pos'] [$labels_pos];
				$classes [] = self::$layout_tpls ['event_text_pos'] [$instance ['event_text_pos']];
				
				$responsive [] = array (
						'scale' => 1.0,
						'alt_classes' => $classes 
				);
			}
			
			$instance ['responsive'] = $responsive;
			
			$instance ['base_font_size'] = SCD_BASE_FONT_SIZE;
		}
		return $instance;
	}
	
	/**
	 * Set widget layout properties based on the widget config
	 *
	 * @param Array $instance
	 *        	- widget config
	 * @return Array: layout config
	 */
	private static function getCounterLayout($instance) {
		$layout = array ();
		
		$title_before_size = $instance ['title_before_size'] / SCD_BASE_FONT_SIZE;
		$title_after_size = $instance ['title_after_size'] / SCD_BASE_FONT_SIZE;
		$labels_size = $instance ['labels_size'] / SCD_BASE_FONT_SIZE;
		
		$layout ['event_text_pos'] = $instance ['event_text_pos'];
		$layout ['labels_pos'] = $instance ['labels_pos'];
		
		$layout ['title_before_style'] = 'font-size:' . $title_before_size . 'em;' . $instance ['title_before_style'];
		$layout ['title_after_style'] = 'font-size:' . $title_after_size . 'em;' . $instance ['title_after_style'];
		
		$layout ['labels_style'] = 'font-size:' . $labels_size . 'em;' . $instance ['labels_style'];
		
		$layout ['title_before_style'] = empty ( $layout ['title_before_style'] ) ? '' : ' style="' . $layout ['title_before_style'] . '"';
		$layout ['title_after_style'] = empty ( $layout ['title_after_style'] ) ? '' : ' style="' . $layout ['title_after_style'] . '"';
		$layout ['digits_style'] = empty ( $instance ['digits_style'] ) ? '' : ' style="' . $instance ['digits_style'] . '"';
		$layout ['labels_style'] = empty ( $layout ['labels_style'] ) ? '' : ' style="' . $layout ['labels_style'] . '"';
		
		switch ($layout ['labels_pos']) {
			case 'left' :
			case 'right' :
				$layout ['labels_class'] = 'scd-label scd-label-row';
				$layout ['digits_class'] = 'scd-digits scd-digits-row';
				break;
			case 'top' :
			case 'bottom' :
			default :
				$layout ['labels_class'] = 'scd-label scd-label-col';
				$layout ['digits_class'] = 'scd-digits scd-digits-col';
				break;
		}
		switch ($layout ['event_text_pos']) {
			case 'horz' :
				$layout ['text_class'] = 'scd-title scd-title-row';
				$layout ['counter_class'] = 'scd-counter scd-counter-row scd-counter-' . $instance ['layout'];
				break;
			case 'vert' :
			default :
				$layout ['text_class'] = 'scd-title scd-title-col clearfix';
				$layout ['counter_class'] = 'scd-counter scd-counter-col clearfix';
		}
		
		$layout ['units_class'] = 'scd-unit scd-unit-' . $instance ['layout'];
		if ($instance ['layout'] == 'vert') {
			$layout ['units_class'] .= ' clearfix';
		}
		
		return $layout;
	}
	
	/*
	 * Updates "deadline" setting for widget or import plugin to UTC and returns it
	 * in 'c' format (ready for javascript Date() initialization)
	 */
	public static function updateDeadlineUTC($options) {
		$deadline = self::localDateToUTC ( $options ['deadline'] );
		
		$options ['deadline'] = $deadline->format ( 'c' );
		
		return $options;
	}
	public static function localDateToUTC($local_date = null) {
		$date = new DateTime ( ! empty ( $local_date ) ? $local_date : null );
		// For now we use current WP system time (aware of time zone in settings)
		$tz_string = get_option ( 'timezone_string', 'UTC' );
		if (empty ( $tz_string )) {
			// direct offset if not a TZ
			$offset = get_option ( 'gmt_offset' ) * 3600;
		} else {
			try {
				$tz = new DateTimeZone ( $tz_string );
				$offset = $tz->getOffset ( $date );
			} catch ( Exception $e ) {
				$offset = 0; // invalid timezone string
			}
		}
		
		// convert date to UTC
		$date->modify ( ($offset < 0 ? '+' : '-') . abs ( $offset ) . ' second' );
		return $date;
	}
	
	/**
	 *
	 * @param array $instance
	 *        	- original instance
	 * @param integer $now_ts
	 *        	- current UTC timestamp
	 * @return array - updated instance
	 *        
	 *         Process imported events. We expect $instance['imported'] array in the following format:
	 *         on or more import plugins add event array keyed by provider alias.
	 *         Each array must contain 0 to many events as arrays with the following elements:
	 *         'deadline' - event date and time (UTC)
	 *         'title' - event title from connected event management plugin or Service
	 *         'duration - event duration in seconds
	 *         It is not required to order events or apply strict filters in import plugins,
	 *         the only condition is that more or less relevant events should be provided by
	 *         import plugins - if an event is missing, it will be ignored (ERROR), if too many
	 *         past or far future events are provided, they will be filtered out here, but
	 *         it will affect PERFORMANCE
	 */
	public static function processImportedEvents($instance, $now_ts) {
		if (empty ( $instance ['imported'] )) {
			return $instance;
		}
		
		$is_countdown_to_end = $instance ['countdown_to_end'];
		if ($is_countdown_to_end) {
			$countup_limit = - 1;
		} else {
			$countup_limit = $instance ['countup_limit'];
		}
		
		// Plain events arrays
		$current_events = array ();
		$future_events = array ();
		
		// merge events from all providers. For now there is no difference which
		// import plugin events comes from
		foreach ( $instance ['imported'] as /*$provider =>*/ $group ) {
			foreach ( $group as $i => &$event ) {
				// convert imported deadline to timestamp
				$deadline = new DateTime ( $event ['deadline'] );
				$event ['deadline'] = $deadline->format ( 'U' );
				
				// ===== old import modules handle "countdown to end mode" creating two events with
				// duration zero, setting 'is_countdown_to_end' flag for the second event and
				// its deadline as first event deadline + first event imported duration.
				// For each group such events always go one after another in unsorted timeline,
				// so we can detect CTE simutation events pair
				if (isset ( $group [$i + 1] ) && ! empty ( $group [$i + 1] ['is_countdown_to_end'] )) {
					// if we have next event and it is CTE, modify current event setting its
					// 'is_countdown_to_end' flag and correct duration (recover it from difference
					// in deadlines)
					$event ['is_countdown_to_end'] = 1;
					
					$d_next = new DateTime ( $group [$i + 1] ['deadline'] );
					$event ['duration'] = $d_next->format ( 'U' ) - $event ['deadline'];
					// mark next event as processed - it shouldn't be added to timeline
					$group [$i + 1] ['skip_event'] = 1;
				}
				if (! empty ( $group [$i] ['skip_event'] )) {
					// this event was already processed, discard it
					continue;
				}
				// end old plugins compatibility code =====
				
				// separate and filter events
				if ($event ['deadline'] <= $now_ts) {
					// event already started
					if ($event ['duration'] >= 0) {
						$duration_filter = $countup_limit >= 0 ? min ( $countup_limit, $event ['duration'] ) : $event ['duration'];
						if ($event ['deadline'] + $duration_filter > $now_ts) {
							$current_events [] = $event;
						}
					} else {
						// we are interested in all started events which have no end date
						$current_events [] = $event;
					}
				} elseif ($event ['deadline'] > $now_ts) {
					// we are interested in all future events
					$future_events [] = $event;
				}
				// finished events are discarded
			}
		}
		
		// Structured events. Each deadline will be an array of events, keyed and sorted
		// by their end time (normal) or start time (CTE)
		
		$current_events = self::groupEvents ( $current_events, 'current', $is_countdown_to_end );
		$future_events = self::groupEvents ( $future_events, 'future', false );
		
		$max_countup_limit = 0;
		
		if ($is_countdown_to_end) {
			// CTE (countdown-to-end) mode
			if (! empty ( $current_events )) {
				// closest event(s) end time is the deadline
				$event_end_times = array_keys ( $current_events );
				$deadline_ts = $event_end_times [0];
				
				// get events group (for overlapping events)
				$events = reset ( $current_events );
				$event_start_times = array_keys ( $events );
				
				// if there are future events we need the closest event start to
				// set countdown limit - when this limit is reached we must repeat event query
				$countdown_query_limit = 0;
				if (! empty ( $future_events )) {
					// future events are always grouped by start dates
					$event_start_times = array_keys ( $future_events );
					$countdown_query_limit = $deadline_ts - $event_start_times [0];
					if ($countdown_query_limit < 0) {
						// if the closest future event starts after the current event finishes
						// we ignore the difference
						$countdown_query_limit = 0;
					}
				}
				$countdown_to_end = 1;
			} elseif (! empty ( $future_events )) {
				$event_start_times = array_keys ( $future_events );
				$deadline_ts = $event_start_times [0];
				
				$events = reset ( $future_events );
				$event_end_times = array_keys ( $events );
				
				// countup limit option cannot be used in CTE mode (special value -2
				// is used in plugin options to express CTE mode), so here we do not need
				// max_countup_limit calculation
				/*
				if (isset ( $event_start_times [1] )) {
					// limit countup to next event start and event duration
					$max_countup_limit = min ( $event_start_times [1], $event_end_times [0] ) - $deadline_ts;
				} else {
					// no more events - limit countup to event duration only
					$max_countup_limit = $event_end_times [0] - $deadline_ts;
				}
				*/
				
				// we have only future events. In CTE mode we must repeat event query once
				// the deadline is reached
				$countdown_query_limit = 0;
				$countdown_to_end = 0;
			}
		} else {
			$current_event_start_times = array_keys ( $current_events );
			$future_event_start_times = array_keys ( $future_events );
			
			// normal mode
			if (! empty ( $current_events )) {
				// most recently started event(s) start time is the deadline
				$deadline_ts = $current_event_start_times [0];
				
				// get events group (for overlapping events)
				$events = reset ( $current_events );
				$event_end_times = array_keys ( $events );
				
				if (! empty ( $future_events )) {
					// limit countup to next event start and event duration
					$max_countup_limit = min ( $future_event_start_times [0], $event_end_times [0] ) - $deadline_ts;
				} else {
					// no more events - limit countup to event duration only
					$max_countup_limit = $event_end_times [0] - $deadline_ts;
				}
			} elseif (! empty ( $future_events )) {
				// we have only future events
				// the closest future event(s) start time is the deadline
				$deadline_ts = $future_event_start_times [0];
				
				$events = reset ( $future_events );
				$event_end_times = array_keys ( $events );
				
				if (isset ( $future_event_start_times [1] )) {
					// limit countup to next event start and event duration
					$max_countup_limit = min ( $future_event_start_times [1], $event_end_times [0] ) - $deadline_ts;
				} else {
					// no more events - limit countup to event duration only
					$max_countup_limit = $event_end_times [0] - $deadline_ts;
				}
			}
			
			// adjust countup_limit - it can never be greater than the interval
			// to next event start or event duration
			if ($countup_limit >= 0) {
				$countup_limit = min ( $countup_limit, $max_countup_limit );
			} else {
				$countup_limit = $max_countup_limit;
			}
			// no CTE in normal mode
			$countdown_to_end = 0;
			$countdown_query_limit = - 1;
		}
		
		if (empty ( $events )) {
			// no events found, cannot proceed
			$instance ['deadline'] = '';
			return $instance;
		}
		
		$redirect_url = null;
		$concat_title = array ();
		
		foreach ( $events as &$event ) {
			if (empty ( $redirect_url ) && ! empty ( $event ['redirect_url'] )) {
				$redirect_url = $event ['redirect_url'];
			}
			self::concatTitles ( $concat_title, $event );
		}
		
		// join titles to a string (may be empty string if no titles found)
		$concat_title = implode ( ', ', $concat_title );
		$instance ['imported_title'] = $concat_title;
		
		$deadline = new DateTime ();
		$deadline->setTimestamp ( $deadline_ts );
		$instance ['deadline'] = $deadline->format ( 'c' );
		$instance ['is_countdown_to_end'] = $countdown_to_end;
		if (! empty ( $redirect_url )) {
			$instance ['redirect_url'] = $redirect_url;
		} else {
			$instance ['redirect_url'] = '';
		}
		$instance ['countup_limit'] = $countup_limit;
		$instance ['countdown_query_limit'] = $countdown_query_limit;
		
		unset ( $instance ['imported'] );
		
		return $instance;
	}
	
	private static function groupEvents($unsorted, $events_type, $is_countdown_to_end = false) {
		$timeline = array ();
		foreach ( $unsorted as $event ) {
			if ($is_countdown_to_end && $event ['duration'] == - 1) {
				// no countdown-to-end for events with no end date
				continue;
			}
			
			$event_start_ts = $event ['deadline'];
			if ($event ['duration'] >= 0) {
				$event_end_ts = $event ['deadline'] + $event ['duration'];
			} else {
				$event_end_ts = PHP_INT_MAX;
			}
			
			if ($is_countdown_to_end) {
				// for countdown-to-end mode group events by end date
				if (! isset ( $timeline [$event_end_ts] )) {
					$timeline [$event_end_ts] = array ();
				}
				// make sure we have unique $event_start_ts key: otherwise if there are fully overlapping
				// events the last event data will overwrite the previous one(s) which will be lost
				while ( isset ( $timeline [$event_end_ts] [$event_start_ts] ) ) {
					$event_start_ts = '0' . $event_start_ts;
				}
				// add event to timeline
				$timeline [$event_end_ts] [$event_start_ts] = $event;
			} else {
				// for normal mode group events by start date
				if (! isset ( $timeline [$event_start_ts] )) {
					$timeline [$event_start_ts] = array ();
				}
				// make sure we have unique $event_end_ts key: otherwise if there are fully overlapping
				// events the last event data will overwrite the previous one(s) which will be lost
				while ( isset ( $timeline [$event_start_ts] [$event_end_ts] ) ) {
					$event_end_ts = '0' . $event_end_ts;
				}
				// add event to timeline
				$timeline [$event_start_ts] [$event_end_ts] = $event;
			}
		}
		
		if ($is_countdown_to_end) {
			$events = self::sortEvents ( $timeline, 'asc' );
		} else {
			$events = self::sortEvents ( $timeline, $events_type == 'future' ? 'asc' : 'desc' );
		}
		return $events;
	}
	private static function sortEvents($timeline, $sort_external = 'asc') {
		// Sort each group
		
		// user-defined sort function: for numerically distinct values
		// we compare numerically, for zero-padded trick values we compare string length -
		// very easy but effective - the only difference is the number of zeros prepended
		// to the value, so this simple will do the trick - the shortest (i.e. added first)
		// will come first in the "asc"-sorted array.
		
		// *** define a standalone function for compatibility with PHP < 5.3
		if (! function_exists ( 'scd_padded_numeric_sort' )) {
			function scd_padded_numeric_sort($a, $b) {
				if (intval ( $a ) == intval ( $b )) {
					// numerically equal: compare number of padded zeros
					return strlen ( $a ) > strlen ( $b ) ? 1 : (strlen ( $a ) < strlen ( $b ) ? - 1 : 0);
				} else {
					// normal: compare as integers
					return intval ( $a ) > intval ( $b ) ? 1 : (intval ( $a ) < intval ( $b ) ? - 1 : 0);
				}
			}
		}
		
		foreach ( $timeline as &$group ) {
			uksort ( $group, 'scd_padded_numeric_sort' );
		}
		
		// Sort groups
		ksort ( $timeline, SORT_NUMERIC );
		if ($sort_external == 'desc') {
			// revert order for 'desc' sort
			$timeline = array_reverse ( $timeline, true );
		}
		
		return $timeline;
	}
	private static function concatTitles(&$concat_title, $event) {
		// implicitly reduce full duplicates
		if (isset ( $event ['title'] ) && trim ( $event ['title'] ) != '') {
			$concat_title [$event ['title']] = $event ['title'];
		}
	}
	public static function getCounterHtml($instance) {
		$layout = SmartCountdown_Helper::getCounterLayout ( $instance );
		if ($layout === false) {
			// log error here!!!
			// echo '<h3>Layout preset invalid!</h3>';
			return;
		}
		ob_start ();
		?>
<style>
.spinner {
	background:
		url('<?php echo get_site_url();?>/wp-admin/images/wpspin_light.gif')
		no-repeat;
}
</style>
<div id="<?php echo $instance['id']; ?>-loading" class="spinner"></div>
<div class="scd-all-wrapper">
	<div class="<?php echo $layout['text_class']; ?>"
		id="<?php echo $instance['id']; ?>-title-before"
		<?php echo $layout['title_before_style']; ?>></div>
	<div class="<?php echo ($layout['counter_class']); ?>">
		<?php foreach(self::$assets as $asset) : ?>
			<div id="<?php echo $instance['id']; ?>-<?php echo $asset; ?>"
			class="<?php echo $layout['units_class']; ?>"
			<?php echo ($instance['units'][$asset] ? '' : ' style="display:none;"'); ?>>
			<?php if($instance['labels_pos'] == 'left' || $instance['labels_pos'] == 'top') : ?>
				<div class="<?php echo $layout['labels_class']; ?>"
				id="<?php echo $instance['id']; ?>-<?php echo $asset; ?>-label"
				<?php echo $layout['labels_style']; ?>></div>
			<div class="<?php echo $layout['digits_class']; ?>"
				id="<?php echo $instance['id']; ?>-<?php echo $asset; ?>-digits"
				<?php echo $layout['digits_style']; ?>></div>
			<?php else : ?>
				<div class="<?php echo $layout['digits_class']; ?>"
				id="<?php echo $instance['id']; ?>-<?php echo $asset; ?>-digits"
				<?php echo $layout['digits_style']; ?>></div>
			<div class="<?php echo $layout['labels_class']; ?>"
				id="<?php echo $instance['id']; ?>-<?php echo $asset; ?>-label"
				<?php echo $layout['labels_style']; ?>></div>
			<?php endif; ?>
			</div>
		<?php endforeach; ?>
	</div>
	<div class="<?php echo $layout['text_class']; ?>"
		id="<?php echo $instance['id']; ?>-title-after"
		<?php echo $layout['title_after_style']; ?>></div>
</div>
<?php
		
		$html = ob_get_clean ();
		return $html;
	}
	public static function getAnimations($instance) {
		$file_name = dirname ( __FILE__ ) . '/animations/' . $instance ['fx_preset'];
		
		if (! file_exists ( $file_name )) {
			// Additional profiles can be stored in alternative folder, if requested file is not
			// present we make another attempt
			$is_alt_animation_dir = true;
			$file_name = dirname ( __FILE__ ) . '/../../smart-countdown-animations/' . $instance ['fx_preset'];
		}
		if (! file_exists ( $file_name )) {
			// fallback to default animation profile (e.g. for misprints in shortcode)
			$file_name = dirname ( __FILE__ ) . '/animations/No_FX_animation.xml';
		}
		if (file_exists ( $file_name )) {
			$xml = file_get_contents ( $file_name );
		} else {
			// panic
			return false;
		}
		
		libxml_use_internal_errors ( true );
		
		$xml = simplexml_load_string ( $xml );
		
		foreach ( libxml_get_errors () as $error ) {
			// log errors here...
		}
		if (empty ( $xml )) {
			return false;
		}
		
		$digitsConfig = array ();
		
		// global settings
		$digitsConfig ['name'] = $xml ['name'] ? ( string ) $xml ['name'] : 'Custom';
		$digitsConfig ['description'] = $xml ['description'] ? ( string ) $xml ['description'] : '';
		
		if (empty ( $is_alt_animation_dir )) {
			$digitsConfig ['images_folder'] = plugins_url () . '/' . dirname ( plugin_basename ( __FILE__ ) ) . '/animations/' . ($xml ['images_folder'] ? ( string ) $xml ['images_folder'] : '');
		} else {
			$digitsConfig ['images_folder'] = plugins_url () . '/smart-countdown-animations/' . ($xml ['images_folder'] ? ( string ) $xml ['images_folder'] : '');
		}
		$digitsConfig ['uses_margin_values'] = false;
		
		// *** TEST ONLY - for debugging to see previous values for all digits on init
		// $digitsConfig['uses_margin_values'] = true;
		
		// get all digit scopes configurations
		foreach ( $xml->digit as $digit ) {
			
			// scope attribute may contain more than one value (comma-separated list)
			$scopes = explode ( ',', ( string ) $digit ['scope'] );
			
			foreach ( $scopes as $scope ) {
				// init config for all scopes in list
				$digitsConfig ['digits'] [$scope] = array ();
			}
			
			// Calculate digits scale. We look for height and font-size scalable styles and calculate the
			// effective scaling (basing on SCD_BASE_FONT_SIZE)
			$scale = 1; // prepare for a fallback if no scalable relevant style is found
			$digits_size = empty ( $instance ['digits_size'] ) ? 24 : $instance ['digits_size'];
			
			foreach ( $digit->styles->style as $value ) {
				$attrs = array ();
				foreach ( $value->attributes () as $k => $v ) {
					$attrs [$k] = ( string ) $v;
				}
				/*
				 * *** CHECK LOGIC with text based animations - EM setting and line-height:1em; (causes extra margin!)
				 */
				if (($attrs ['name'] == 'height' || $attrs ['name'] == 'font-size') && ! empty ( $attrs ['scalable'] )) {
					if ($attrs ['unit'] == 'px') {
						$scale = $digits_size / $attrs ['value'];
					} elseif ($attrs ['unit'] == 'em') {
						$scale = ($digits_size / SCD_BASE_FONT_SIZE) / $attrs ['value'];
					}
				}
			}
			
			// construct digit style
			$styles = array ();
			
			foreach ( $digit->styles->style as $value ) {
				$attrs = array ();
				foreach ( $value->attributes () as $k => $v ) {
					$attrs [$k] = ( string ) $v;
				}
				
				// If attribute unit is "px" we translate it to "em" using global base font size
				// setting
				if ($attrs ['unit'] == 'px') {
					$attrs ['unit'] = 'em';
					$attrs ['value'] = $attrs ['value'] / SCD_BASE_FONT_SIZE;
				}
				// Scale the value if it has 'scalable' attribute set
				$result = (! empty ( $attrs ['scalable'] ) ? $scale * $attrs ['value'] : $attrs ['value']) . (! empty ( $attrs ['unit'] ) ? $attrs ['unit'] : '');
				
				$result = preg_replace ( '#url\((\S+)\)#', 'url(' . $digitsConfig ['images_folder'] . '$1)', $result );
				
				// We save styles as array, must be joined by ";" before applying directly to style attribute!
				$styles [$attrs ['name']] = $result;
			}
			
			// *** old version: styles as a string
			// for digit style - if background set, prepend images_folder
			// $styles = preg_replace('#url\((\S+)\)#', 'url('.$digitsConfig['images_folder'].'$1)', $styles);
			
			foreach ( $scopes as $scope ) {
				// set styles for all scopes in list
				$digitsConfig ['digits'] [$scope] ['style'] = $styles;
			}
			
			// get modes (down and up)
			foreach ( $digit->modes->mode as $groups ) {
				
				$attrs = $groups->attributes ();
				$mode = ( string ) $attrs ['name'];
				
				foreach ( $groups as $group ) {
					
					$grConfig = array ();
					
					$grAttrs = $group->attributes ();
					foreach ( $grAttrs as $k => $v ) {
						$grConfig [$k] = ( string ) $v;
						if ($k == 'transition') {
							$grConfig [$k] = self::translateTransitions ( $grConfig [$k] );
						}
					}
					
					$grConfig ['elements'] = array ();
					
					// get all elements for the group
					foreach ( $group as $element ) {
						// default values to use if attribute is missing
						$elConfig = array (
								'filename_base' => '',
								'filename_ext' => '',
								'value_type' => '' 
						);
						
						$elAttrs = $element->attributes ();
						foreach ( $elAttrs as $k => $v ) {
							$elConfig [$k] = ( string ) $v;
						}
						
						if ($elConfig ['value_type'] == 'pre-prev' || $elConfig ['value_type'] == 'post-next') {
							// working with pre-prev and post-next requires significant
							// calculation in client script, so for performance sake we set the
							// flag here, so that this calculation is performed only if needed
							$digitsConfig ['uses_margin_values'] = true;
						}
						
						$elConfig ['styles'] = self::getElementStyles ( $element->styles, $digitsConfig ['images_folder'] );
						$elConfig ['tweens'] = self::getElementTweens ( $element->tweens, empty ( $grConfig ['unit'] ) ? '%' : $grConfig ['unit'] );
						
						// if a style is missing in tweens['from'] we must add it here
						$elConfig ['tweens'] ['from'] = array_merge ( $elConfig ['styles'], $elConfig ['tweens'] ['from'] );
						
						// if a tweens rule (CSS property) is missing in element's styles, existing animations profiles
						// get broken. At the moment we implement this workaround - explicitly add a style if a "tween.from"
						// property is missing. Later we can check if this can be done in client script and/or if there are
						// clear guidelines for correcting existing animation profiles
						foreach ( $elConfig ['tweens'] ['from'] as $style => $value ) {
							if (! isset ( $elConfig ['styles'] [$style] )) {
								$elConfig ['styles'] [$style] = $value;
							}
						}
						$grConfig ['elements'] [] = $elConfig;
					}
					
					foreach ( $scopes as $scope ) {
						// set fx configuration for all scopes in list
						$digitsConfig ['digits'] [$scope] [$mode] [] = $grConfig;
					}
				}
			}
		}
		
		return $digitsConfig;
	}
	
	/**
	 * Translate old mootools easing directives to jQuery UI easing standards.
	 * When using native jQuery
	 * easing or unknown, returns $transition param without changes
	 *
	 * @param string $transition
	 *        	- sourse easing directive
	 * @return string - jQuery UI standard easing directive
	 */
	private static function translateTransitions($transition) {
		$parts = explode ( ':', $transition );
		if (count ( $parts ) == 2) {
			return 'ease' . ucfirst ( $parts [1] ) . ucfirst ( $parts [0] );
		} else {
			return $transition;
		}
	}
	private static function getElementStyles($styles, $images_folder) {
		$result = array ();
		
		if (empty ( $styles )) {
			return $result;
		}
		
		$styles = $styles->children ();
		for($i = 0; $count = count ( $styles ), $i < $count; $i ++) {
			$result [$styles [$i]->getName ()] = trim ( preg_replace ( '#url\((\S+)\)#', 'url(' . $images_folder . '$1)', ( string ) $styles [$i] ) );
		}
		
		return $result;
	}
	
	/*
	 * Split tweens to "from" and "to" CSS rules. Must-have for jQuery animation
	 */
	private static function getElementTweens($tweens, $unit) {
		$result = array (
				'from' => array (),
				'to' => array () 
		);
		if (empty ( $tweens )) {
			return $result;
		}
		
		$tweens = $tweens->children ();
		
		for($i = 0; $count = count ( $tweens ), $i < $count; $i ++) {
			$name = $tweens [$i]->getName ();
			if (! in_array ( $name, array (
					'top',
					'bottom',
					'left',
					'right',
					'height',
					'width',
					'font-size' 
			) )) {
				// discard unit for css rules that do not accept units
				$unit = '';
			}
			$values = explode ( ',', ( string ) $tweens [$i] );
			$result ['from'] [$name] = trim ( $values [0] . $unit );
			$result ['to'] [$name] = trim ( $values [1] . $unit );
		}
		
		return $result;
	}
	public static function selectInput($id, $name, $selected = '', $config = array()) {
		$config = array_merge ( array (
				'type' => 'integer',
				'start' => 10,
				'end' => 50,
				'step' => 2,
				'default' => 30,
				'unit' => 'px' 
		), $config );
		
		$html = array ();
		
		if ($config ['type'] == 'integer') {
			$html [] = '<select id="' . $id . '" name="' . $name . '">';
			
			for($v = $config ['start']; $v <= $config ['end']; $v += $config ['step']) {
				$html [] = '<option value="' . $v . '"' . ($selected == $v ? ' selected' : '') . '>' . $v . $config ['unit'] . '</option>';
			}
		} elseif ($config ['type'] == 'filelist') {
			$html [] = '<select class="widefat" id="' . $id . '" name="' . $name . '">';
			
			// for filelist we support an array of folders, so that we can merge all
			// files found into dropdown control
			$dirs = ( array ) $config ['folder'];
			
			foreach ( $dirs as $dir ) {
				if (! file_exists ( $dir )) {
					continue;
				}
				$files = scandir ( $dir );
				$filter_ext = empty ( $config ['extension'] ) ? '' : $config ['extension'];
				
				foreach ( $files as $filename ) {
					$parts = explode ( '.', $filename );
					$ext = array_pop ( $parts );
					$name = str_replace ( array (
							'.',
							'_' 
					), ' ', implode ( '.', $parts ) );
					
					if ($filter_ext && $ext != $filter_ext) {
						continue;
					}
					$html [] = '<option value="' . $filename . '"' . ($selected == $filename ? ' selected' : '') . '>' . ucwords ( esc_html ( $name ) ) . '</option>';
				}
			}
		} elseif ($config ['type'] == 'optgroups') {
			// plain lists and option groups supported
			$html [] = '<select class="widefat" id="' . $id . '" name="' . $name . '">';
			
			foreach ( $config ['options'] as $value => $option ) {
				if (is_array ( $option )) {
					// this is an option group
					$html [] = '<optgroup label="' . esc_html ( $value ) . '">';
					foreach ( $option as $v => $text ) {
						$html [] = '<option value="' . $v . '"' . ($v == $selected ? ' selected' : '') . '>';
						$html [] = esc_html ( $text );
						$html [] = '</option>';
					}
					$html [] = '</optgroup>';
				} else {
					// this is a plain select option
					$html [] = '<option value="' . $value . '"' . ($value == $selected ? ' selected' : '') . '>';
					$html [] = esc_html ( $option );
					$html [] = '</option>';
				}
			}
		}
		
		$html [] = '</select>';
		
		return implode ( "\n", $html );
	}
	public static function checkboxesInput($widget, $values, $config = array()) {
		$lang_key_units = array (
				'years' => __ ( 'years', 'smart-countdown' ),
				'months' => __ ( 'months', 'smart-countdown' ),
				'weeks' => __ ( 'weeks', 'smart-countdown' ),
				'days' => __ ( 'days', 'smart-countdown' ),
				'hours' => __ ( 'hours', 'smart-countdown' ),
				'minutes' => __ ( 'minutes', 'smart-countdown' ),
				'seconds' => __ ( 'seconds', 'smart-countdown' ) 
		);
		$html = array ();
		if (! empty ( $config ['legend'] )) {
			$html [] = '<fieldset><legend>' . $config ['legend'] . '</legend>';
		}
		foreach ( $values as $unit => $value ) {
			$field_id = $widget->get_field_id ( 'units_' . $unit );
			$field_name = $widget->get_field_name ( 'units_' . $unit );
			$html [] = '<p><input type="checkbox" class="checkbox" id="' . $field_id . '" name="' . $field_name . '"' . ($value ? ' checked' : '') . ' />';
			$html [] = '<label for="' . $field_id . '">' . $lang_key_units [$unit];
			$html [] = '</label></p>';
		}
		if (! empty ( $config ['legend'] )) {
			$html [] = '</fieldset>';
		}
		return implode ( "\n", $html );
	}
	public static function enabledImportConfigs($id, $name, $selected = '') {
		$configs = array ();
		$configs = apply_filters ( 'smartcountdownfx_get_import_configs', $configs );
		if (empty ( $configs )) {
			return '';
		}
		
		$html = array ();
		$html [] = '<p>';
		$html [] = '<label for="' . $id . '">' . __ ( 'Import events from:', 'smart-countdown' ) . '</label>';
		$html [] = '<select class="widefat" id="' . $id . '" name="' . $name . '">';
		
		$html [] = '<option value=""' . ('' == $selected ? ' selected' : '') . '>';
		$html [] = esc_html__ ( 'Disabled. Use event date and time from settings', 'smart-countdown' );
		$html [] = '</option>';
		
		foreach ( $configs as $provider => $presets ) {
			if (empty ( $presets )) {
				continue;
			}
			$html [] = '<optgroup label="' . esc_html ( $provider ) . '">';
			foreach ( $presets as $v => $text ) {
				$html [] = '<option value="' . $v . '"' . ($v == $selected ? ' selected' : '') . '>';
				$html [] = $text != '' ? esc_html ( $text ) : esc_html__ ( 'Invalid configuration', 'smart-countdown' );
				$html [] = '</option>';
			}
			$html [] = '</optgroup>';
		}
		
		$html [] = '</select>';
		$html [] = '</p>';
		$html [] = '<p class="help">';
		$html [] = __ ( 'Widget event date and time will be ignored if an event import configuration is selected', 'smart-countdown' );
		$html [] = '</p>';
		
		return implode ( "\n", $html );
	}
	public static function importPluginsEnabled() {
		$configs = array ();
		$configs = apply_filters ( 'smartcountdownfx_get_import_configs', $configs );
		if (empty ( $configs )) {
			return false;
		}
		return true;
	}
}