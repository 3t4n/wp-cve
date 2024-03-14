<?php

	// Generate shift calendar
	function fd_shift_calendar_generate($type, $display_date = '', $ajax = false){
		
		$calendar = '';
		
		// Get calendar settings from options page
		$fd_shift_calendar_start = get_option('fd_calendar_shift_start_date');
		$fd_shift_calendar_shifts = get_option('fd_calendar_shifts');
		$fd_shift_calendar_schedule = get_option('fd_calendar_schedule_pattern');
		
		// Make plugin use current Wordpress timezone settings
		if($gmt_offset = get_option('gmt_offset')){	
			if(get_option('timezone_string')){
				$timezone = get_option('timezone_string');
			}else{
				$timezone = timezone_name_from_abbr('', $gmt_offset * 3600, 0);
			}
			date_default_timezone_set($timezone);
		}
	
		$shift_start = new DateTime($fd_shift_calendar_start);
		$shift_format = str_replace(' ', '', $fd_shift_calendar_schedule);
		
		$shift_colors = array();
		foreach($fd_shift_calendar_shifts as $shift){
			$id = $shift['id'];
			$shift_labels[$id] = $shift['label'];
			$shift_colors[$id] = $shift['color'];
		}
		
		
		// Only output styles if this is not an AJAX request
		if(!$ajax){
			// Output styles for calendar
			$calendar .= '<style>';
			
				// Loop through shift colors for display
				foreach($shift_colors as $shift => $color){
					$rgb = extract(fd_shift_calendar_hex2rgb($color));
					$calendar .= "
						.fd-shift-calendar .shift.$shift{
							background: $color;
						}
						
						.fd-shift-calendar .shift.$shift.current:before{
							border-color: rgba($red, $green, $blue, 0.39);
						}
					";
				}
				
				// Loop through shift colors for print
				foreach($shift_colors as $shift => $color){
					$rgb = extract(fd_shift_calendar_hex2rgb($color));
					$calendar_print_colors .= "
						.fd-shift-calendar .calendar td.shift.$shift{
							color: $color;
						}
					";
				}
				
				
				// Output print styles for calendar
				$calendar .= "
						@media print{
							$calendar_print_colors
							
							@page {
							  size: letter portrait;
							  margin: .5in;
							}

							.fd-shift-calendar .fd-shift-calendar-head .fd-shift-calendar-title{
								margin: 0px !important;
								padding: 0px !important;
							}
							
							.fd-shift-calendar .fd-shift-calendar-head .prev a,
							.fd-shift-calendar .fd-shift-calendar-head .next a{
								display: none;
							}
							
							.fd-shift-calendar .calendar td.day.current{
								border-radius: 0;
							}
							
							.fd-shift-calendar td.shift.current:before{
								border-color: transparent !important;
							}
							
							.fd-shift-calendar .row{
								margin: 0;
							}
							
							.fd-shift-calendar .calendar{
								border-collapse: collapse;
								margin: 5px 10px;
							}
							
							.fd-shift-calendar .calendar td.shift{
								background: transparent !important;
								border: 1px solid #CCC;
								border-radius: 0;
								font-weight: bold !important;
							}
						}
						
						@media print and (color){
						   .fd-shift-calendar{
							  -webkit-print-color-adjust: exact;
							  print-color-adjust: exact;
						   }
						}
						";
						
			$calendar .= '</style>';
		}

		// Append "monthly" to class name if this is monthly calendar being displayed
		$type_class = ($type == 'monthly') ? ' monthly' : ' yearly';
		
		
		// Begin calendar output
		if(!$ajax){
			$calendar .= '<div class="fd-shift-calendar' . $type_class . '" data-calendar-type="' . $type . '">';
		}
			
			// Parse shift schedule into array
			if(strpos($shift_format, ',') !== false){
				$shift_on_duty = array_values(array_filter(explode(' ',str_replace(',',' ',$shift_format))));
			}else{
				$shift_on_duty = str_split($shift_format);
			}
			
			// Calculate end of shift schedule
			$shift_length = count($shift_on_duty);
			
			// Get start date for this calendar
			$calendar_date = new DateTime($display_date);

			// Get year of this calendar
			if($type == 'yearly' && strlen($display_date) == 4){
				$year = intval($display_date);
			}else{
				$year = $calendar_date->format('Y');
			}
			
			// Determine if this is monthly or yearly calendar
			if($type == 'monthly'){
				$months = 1;
				$row = null;
			}else{
				$months = 12;
				$row = 1;
			}
			
			// If this is a yearly calendar display a title above the calendar
			if($type != 'monthly'){
				$prev_year = $year - 1;
				$next_year = $year + 1;
				
				$calendar .= "<div class=\"fd-shift-calendar-head\">" .
								"<div class=\"prev\">" .
									"<a href=\"#prev-year\" rel=\"prev\" title=\"Previous Year\" data-prev-date=\"$prev_year\"><span class=\"icon-fd-prev\"></span></a>" .
								"</div>" .
								"<h2 class=\"fd-shift-calendar-title\" style=\"text-align: center;\">" . $year . " Shift Calendar</h2>" .
								"<div class='next'>" .
									"<a href=\"#next-year\" rel=\"next\" title=\"Next Year\" data-next-date=\"$next_year\"><span class=\"icon-fd-next\"></span></a>" .
								"</div>" .
							"</div>";
			}
			
			// Create array containing abbreviations of days of week.
			$days_of_week = array('S','M','T','W','T','F','S');
			
			$current_date = date('Y-m-d');
			$current_month = $calendar_date->format('n');
			
			
			$yearStart = "$year-01-01";
			$start_of_year = new DateTime($yearStart);
			
			$row_length = 3;
			
			
			for($month = 1; $month <= $months; $month++){
				
				if($months == 1){
					$month = $current_month;
				}
				
				// What is the first day of the month in question?
				$first_day_of_month = mktime(0,0,0,$month,1,$year);

				// How many days does this month contain?
				$number_days = date('t',$first_day_of_month);

				// Retrieve some information about the first day of the month in question.
				$calendar_month = new DateTime();
				$calendar_month->setTimestamp($first_day_of_month);

				// What is the name of the month in question?
				$month_name = $calendar_month->format('F');

				// What is the index value (0-6) of the first day of the month in question.
				$day_of_week = $calendar_month->format('w');

				if(isset($row) && $row == 1){
					$calendar .= '<div class="row clearfix">';
				}
				
					// Create the table tag opener and day headers
					$calendar .= "<table class='calendar'>";
						
						$calendar .= "<thead>";
					
							$calendar .= '<tr class="month">';
								if($type == 'monthly'){
									
									if(!$display_date){
										$display_date = $month_name . ' ' .$year;
									}
									
									$current_month = new DateTime($display_date);
									$current_month = $current_month->format('F Y');
									
									$prev_month = new DateTime($month_name . ' ' .  $year);
									$prev_month = $prev_month->modify('-1 month')->format('F Y');
									
									$next_month = new DateTime($month_name . ' ' .  $year);
									$next_month = $next_month->modify('+1 month')->format('F Y');
									
									$calendar .= "<th class='prev'>" .
													"<a href='#prev-month' rel='prev' title='Previous Month' data-prev-date='$prev_month'><span class=\"icon-fd-prev\"></span></a>" .
												"</th>" .
												"<th colspan='5' class='caption' data-current-month='$current_month'>$current_month</th>" .
												"<th class='next'>" .
													"<a href='#next-month' rel='next' title='Next Month' data-next-date='$next_month'><span class=\"icon-fd-next\"></span></a>" .
												"</th>";
								}else{
									$calendar .= "<th colspan='7' class='caption'>$month_name $year</th>";
								}
							$calendar .= '</tr>';
							
							// Create the calendar headers
							$calendar .= '<tr class="days">';
								foreach($days_of_week as $day){
									$calendar .= "<th class='header'>$day</th>";
								}
							$calendar .= "</tr>";
	 
						$calendar .= "</thead>";



						// Create the rest of the calendar
						$calendar .= "<tbody>";
						
							// Initiate the day counter, starting with the 1st.
							$current_day = 1;
							
							
							$calendar .= "<tr>";

								// The variable $day_of_week is used to ensure that the calendar display consists of exactly 7 columns.
								if($day_of_week > 0){
									$calendar .= "<td colspan='$day_of_week'>&nbsp;</td>"; 
								}

								$month = str_pad($month, 2, "0", STR_PAD_LEFT);

								while($current_day <= $number_days){
									
									// Seventh column (Saturday) reached. Start a new row.
									if($day_of_week == 7){
										$day_of_week = 0;
										$calendar .= "</tr><tr>";
									}
								  
									$current_day_rel = str_pad($current_day, 2, "0", STR_PAD_LEFT);

									$date = "$year-$month-$current_day_rel";
									
									

									// Set this to the day to find which shift is working
									$day_to_test = new DateTime($date);
									
									$dDiff = $day_to_test->diff($shift_start);
									
									
									// Correct offset for dates before shift start date
									if($day_to_test < $shift_start){
										$diff_from_year_start = $start_of_year->diff($day_to_test);
										
										$start_pattern_offset = $start_of_year->diff($shift_start);
										$start_pattern_offset = $start_pattern_offset->days%$shift_length;

										$days_from_year_start = $diff_from_year_start->days;
										
										if($start_pattern_offset > 0){
											$pattern_total = (strlen($shift_format) - $start_pattern_offset);
										}else{
											$pattern_total = 0;
										}

										$currentShift = ($days_from_year_start + $pattern_total)%$shift_length;
									}else{
										$currentShift = $dDiff->days%$shift_length;
									}
									
									$classes = 'day shift ' . $shift_on_duty[$currentShift];
									
									if($date == $current_date){
										$classes .= ' current hvr-ripple-out';
									}
									
									$calendar .= "<td class='$classes' rel='$date'>$current_day</td>";

									// Increment counters
									$current_day++;
									$day_of_week++;
								}

								// Complete the row of the last week in month, if necessary
								if ($day_of_week != 7) { 
								  $remaining_days = 7 - $day_of_week;
								  $calendar .= "<td colspan='$remaining_days'>&nbsp;</td>"; 
								}

							$calendar .= "</tr>";
							
						$calendar .= "</tbody>";

					$calendar .= "</table>";
				
				if(isset($row) && $row == 3){
					$calendar .=  '</div>';
					$row = 0;
				}
				
				if(isset($row)){
					$row++;
				}
			}
			
			$calendar .= '<div class="calendar-key">';

				foreach($shift_labels as $shift => $label){
					$calendar .= '<div class="key-item"><span class="shift ' . $shift .'"></span>' . $label . '</div>';
				}

			$calendar .= '</div>';
		
		if(!$ajax){
			$calendar .= '</div>';
		}
		
		return $calendar;
	}
	
	
	// Generate shift calendar from AJAX request
	function fd_shift_calendar_ajax_generate(){

		if(wp_verify_nonce($_GET['nonce'], 'fd-shift-calendar-nonce')){
			
			// If this is an AJAX request use the supplied calendar type (if supplied)
			if(!empty($_GET['calendar_type'])){
				$type = trim(filter_var($_GET['calendar_type'], FILTER_SANITIZE_STRING));
			}
			
			// If this is an AJAX request use the supplied date instead
			if(!empty($_GET['display_date'])){
				$display_date = trim(filter_var($_GET['display_date'], FILTER_SANITIZE_STRING));
			}
			
			if($type && $display_date){
				echo fd_shift_calendar_generate($type, $display_date, true);
			}
		}
		wp_die();
		
	}
	add_action('wp_ajax_fd_shift_calendar_ajax_generate', 'fd_shift_calendar_ajax_generate');
	add_action('wp_ajax_nopriv_fd_shift_calendar_ajax_generate', 'fd_shift_calendar_ajax_generate');

?>