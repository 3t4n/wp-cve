<?php

	// Add plugin menu page to dashboard sidebar
	add_action('admin_menu', function(){
		$page_title = 'FD Shift Calendar Settings';
		$menu_title = 'FD Shift Calendar';
		$capability = 'manage_options';
		$menu_slug = FD_SHIFT_CAL_SLUG;
		$callback = 'fd_shift_calendar_admin';
		$icon_url = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" width="17" height="17" viewBox="0 0 17 17"><path d="M17 8.5c0-1.3-1.4-2.3-1.3-3.5 0.1-0.6 0.1-1 0-1.2 -0.1-0.3-0.3-0.5-0.6-0.6 -0.3-0.1-0.6 0-0.8 0.2C13.6 4.1 12.8 4.9 12 5.7c-0.1-0.1-0.2-0.2-0.3-0.3l0 0c0 0 0 0-0.1 0 0 0 0 0 0 0l0 0c-0.1-0.1-0.2-0.2-0.3-0.3 0.8-0.8 1.6-1.5 2.3-2.3 0.2-0.2 0.3-0.5 0.2-0.8 -0.1-0.3-0.3-0.5-0.6-0.6 -0.3-0.1-0.6-0.1-1.2 0C10.8 1.4 9.8 0 8.5 0 7.2 0 6.2 1.4 4.9 1.3 4.4 1.2 4 1.2 3.7 1.2 3.4 1.3 3.2 1.6 3.1 1.8 3.1 2.1 3.1 2.4 3.4 2.7c0.8 0.8 1.5 1.6 2.3 2.3C5.6 5.1 5.5 5.2 5.3 5.3L5.3 5.3C5.3 5.3 5.3 5.3 5.3 5.3 5.3 5.3 5.3 5.3 5.3 5.3l0 0c-0.1 0.1-0.2 0.2-0.3 0.3C4.2 4.9 3.4 4.1 2.6 3.3 2.4 3.1 2.1 3.1 1.8 3.1 1.6 3.2 1.3 3.4 1.2 3.7 1.2 4 1.2 4.4 1.3 4.9 1.4 6.2 0 7.2 0 8.5c0 1.3 1.4 2.3 1.3 3.5 -0.1 0.6-0.1 1 0 1.2 0.1 0.3 0.3 0.5 0.6 0.6 0.3 0.1 0.6 0 0.8-0.2 0.8-0.8 1.6-1.5 2.3-2.3 0.1 0.1 0.2 0.2 0.3 0.3L5.3 11.7c0.1 0.1 0.3 0.3 0.4 0.4 -0.8 0.8-1.6 1.5-2.3 2.3 -0.2 0.2-0.3 0.5-0.2 0.8 0.1 0.3 0.3 0.5 0.6 0.6 0.3 0.1 0.6 0.1 1.2 0C6.2 15.6 7.2 17 8.5 17c1.3 0 2.3-1.4 3.5-1.3 0.6 0.1 1 0.1 1.2 0 0.3-0.1 0.5-0.3 0.6-0.6 0.1-0.3 0-0.6-0.2-0.8 -0.8-0.8-1.5-1.6-2.3-2.3 0.1-0.1 0.2-0.2 0.4-0.3l0 0c0 0 0 0 0 0 0 0 0 0 0 0l0 0c0.1-0.1 0.2-0.2 0.3-0.3 0.8 0.8 1.5 1.6 2.3 2.3 0.2 0.2 0.5 0.3 0.8 0.2 0.3-0.1 0.5-0.3 0.6-0.6 0.1-0.3 0.1-0.6 0-1.2C15.6 10.8 17 9.8 17 8.5zM8.5 11.4c-1.6 0-2.9-1.3-2.9-2.9 0-1.6 1.3-2.9 2.9-2.9s2.9 1.3 2.9 2.9C11.4 10.1 10.1 11.4 8.5 11.4z" fill="none"/></svg>');
		$position = 99;
		
		add_menu_page($page_title, $menu_title, $capability, $menu_slug, $callback, $icon_url, $position);
	});

	
	// Add plugin settings to db
	add_action('admin_init', function(){
		register_setting('fd_shift_calendar_settings', 'fd_calendar_shift_start_date');
		register_setting('fd_shift_calendar_settings', 'fd_calendar_shifts');
		register_setting('fd_shift_calendar_settings', 'fd_calendar_shift_rotation');
		register_setting('fd_shift_calendar_settings', 'fd_calendar_shift_schedule');
		register_setting('fd_shift_calendar_settings', 'fd_calendar_schedule_pattern');
	});
	
	
	// Menu page display
	function fd_shift_calendar_admin(){
		?>
		<div class="wrap" id="fd-calendar-options">
			<div id="fd-shift-calendar-title">
				<h2><?php echo esc_html_e('Fire Department Shift Calendar Settings', 'fd_shift_calendar'); ?></h2>
				<a href="#fd_calendar_shortcodes" title="<?php echo esc_html_e('View Shortcodes', 'fd_shift_calendar'); ?>" data-lity><?php echo esc_html_e('View Shortcodes', 'fd_shift_calendar'); ?></a>
				<div id="fd_calendar_shortcodes" style="background:#fff" class="lity-hide">
					<h3><?php echo esc_html_e('Shortcodes', 'fd_shift_calendar'); ?></h3>
					<table>
						<tbody>
							<tr>
								<td><strong><?php echo esc_html_e('Full Year Display', 'fd_shift_calendar'); ?></strong></td>
								<td><code>[fd_shift_calendar]</code></td>
							</tr>
							<tr>
								<td><strong><?php echo esc_html_e('Current Month Display', 'fd_shift_calendar'); ?></strong></td>
								<td><code>[fd_shift_calendar type="monthly"]</code></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<form id="fd-shift-calendar-form" method="post" action="options.php">
				<?php 
				// Get settings
				settings_fields('fd_shift_calendar_settings'); 
				
				// Current shift rotation
				$shift_rotation = esc_attr(get_option('fd_calendar_shift_rotation'));
				
				// Current schedule pattern
				$schedule_pattern = esc_attr(get_option('fd_calendar_schedule_pattern'));
				
				// Shift ID,Label,Color Defaults
				$shift_defaults = array(
					array(
						'id'	=>	'A',
						'label'	=>	__('1st Platoon', 'fd_shift_calendar'),
						'color'	=>	'#ea0d00'
					),
					array(
						'id'	=>	'B',
						'label'	=>	__('2nd Platoon', 'fd_shift_calendar'),
						'color'	=>	'#1b1bf4'
					),
					array(
						'id'	=>	'C',
						'label'	=>	__('3rd Platoon', 'fd_shift_calendar'),
						'color'	=>	'#3ca93c'
					),
				);
				
				// Pre-defined Schedule Rotations
				$schedule_rotations = array(
					array(
						'label'	=> __('Custom', 'fd_shift_calendar'),
						'value'	=> 'custom'
					),
					array(
						'label'	=> '24/48',
						'value'	=> 'ABC'
					),
					array(
						'label'	=> '48/96',
						'value'	=> 'AABBCC'
					),
					array(
						'label'	=> '72/144',
						'value'	=> 'AAABBBCCC'
					),
					array(
						'label'	=> '3s and 4s (9 day)',
						'value'	=> 'AB AC ACBCB'
					),
					array(
						'label'	=> '2s and 4s (12 day)',
						'value'	=> 'AB ABC AC ABCBC'
					),
					array(
						'label'	=> '4s and 6s (24 day)',
						'value'	=> 'AB AC AC ACBCB AB AB AC ACBCBCB'
					)
				);
				
				// Check select rotation value
				function selectedRotationValue($value, $schedule_pattern = ''){
					if($value == $schedule_pattern || $value == 'custom'){
						echo 'selected';
					}
				}
				?>
				
				<table class="form-table">

					<!-- Shift IDs, Labels, & Colors -->
					<tr valign="top" class="shift-colors">
						<th scope="row" class="label">
							<label for="shift_start_date"><?php echo esc_html_e('Shifts & Colors', 'fd_shift_calendar'); ?></label>
						</th>
						<td class="input">
							<table id="fd-shifts" class="inline-table">
								<tr class="shift-labels">
									<th class="shift-label"><?php echo esc_html_e('Shift', 'fd_shift_calendar'); ?></th>
									<th class="shift-name"><?php echo esc_html_e('Label', 'fd_shift_calendar'); ?></th>
									<th class="shift-color"><?php echo esc_html_e('Color', 'fd_shift_calendar'); ?></th>
								</tr>
								<?php
									$fd_shift_calendar_shifts = get_option('fd_calendar_shifts');

									if(count($fd_shift_calendar_shifts) > 0){
										if(empty($fd_shift_calendar_shifts[1]['id'])){
											$fd_shift_calendar_shifts = null;
										}
									}
									
									if($fd_shift_calendar_shifts){
										foreach($fd_shift_calendar_shifts as $i => $shift){
											?>
											<tr class="shift-definitions">
												<td class="shift-label">
													<input type="text" placeholder="<?php echo esc_html_e('Shift Label', 'fd_shift_calendar'); ?>" name="fd_calendar_shifts[<?php echo $i; ?>][id]" value="<?php echo esc_attr($fd_shift_calendar_shifts[$i]['id']); ?>" autocomplete="off">
												</td>
												<td class="shift-name">
													<input type="text" placeholder="<?php echo esc_html_e('Shift Label', 'fd_shift_calendar'); ?>" name="fd_calendar_shifts[<?php echo $i; ?>][label]" value="<?php echo esc_attr($fd_shift_calendar_shifts[$i]['label']); ?>" autocomplete="off">
												</td>
												<td class="shift-color">
													<input type="text" class="color-picker" placeholder="<?php echo esc_html_e('Color', 'fd_shift_calendar'); ?>" name="fd_calendar_shifts[<?php echo $i; ?>][color]" value="<?php echo esc_attr($fd_shift_calendar_shifts[$i]['color']); ?>" autocomplete="off">
													<span class="repeater-actions">
														<a class="repeater-action add button" title="<?php echo esc_html_e('Add Shift', 'fd_shift_calendar'); ?>" href="#add">+</a>
														<a class="repeater-action remove button <?php echo ($i > 1) ? '' : 'disabled'; ?>" title="<?php echo esc_html_e('Remove Shift', 'fd_shift_calendar'); ?>" href="#remove">-</a>
													</span>
												</td>
											</tr>
											<?php
											$i++;
										}
									}else{
										$i = 1;
										foreach($shift_defaults as $shift_default){
											?>
											<tr class="shift-definitions">
												<td class="shift-label">
													<input type="text" placeholder="<?php echo esc_html_e('Shift Label', 'fd_shift_calendar'); ?>" name="fd_calendar_shifts[<?php echo $i; ?>][id]" value="<?php echo esc_attr($shift_default['id']); ?>" autocomplete="off">
												</td>
												<td class="shift-name">
													<input type="text" placeholder="<?php echo esc_html_e('Shift Label', 'fd_shift_calendar'); ?>" name="fd_calendar_shifts[<?php echo $i; ?>][label]" value="<?php echo esc_attr($shift_default['label']); ?>" autocomplete="off">
												</td>
												<td class="shift-color">
													<input type="text" class="color-picker" placeholder="<?php echo esc_html_e('Color', 'fd_shift_calendar'); ?>" name="fd_calendar_shifts[<?php echo $i; ?>][color]" value="<?php echo esc_attr($shift_default['color']); ?>" autocomplete="off">
													<span class="repeater-actions">
														<a class="repeater-action add button" title="<?php echo esc_html_e('Add Shift', 'fd_shift_calendar'); ?>"href="#add">+</a>
														<a class="repeater-action remove button <?php echo ($i > 1) ? '' : 'disabled'; ?>" title="<?php echo esc_html_e('Remove Shift', 'fd_shift_calendar'); ?>" href="#remove">-</a>
													</span>
												</td>
											</tr>
											<?php
											$i++;
										}
									}
								?>
							</table>
						</td>
					</tr>
				
				
					<!-- Schedule Stat Date -->
					<tr valign="top" class="start-date">
						<th scope="row" class="label">
							<label for="shift_start_date"><?php echo esc_html_e('Rotation Start', 'fd_shift_calendar'); ?></label>
						</th>
						<td class="input" id="startdate">
							<input type="text" class="date-picker" placeholder="<?php echo esc_html_e('Enter start date', 'fd_shift_calendar'); ?>" name="fd_calendar_shift_start_date" value="<?php echo esc_attr(get_option('fd_calendar_shift_start_date')); ?>" autocomplete="off">
						</td>
					</tr>
					
					
					<!-- Schedule Rotation Select -->
					<tr valign="top" class="shift-schedule-select">
						<th scope="row" class="label">
							<label for="fd_calendar_shift_rotation"><?php echo esc_html_e('Shift Rotation', 'fd_shift_calendar'); ?></label>
						</th>

						<td class="input" id="rotation">
							<select id="shift-rotation" name="fd_calendar_shift_rotation">
								<?php
								foreach($schedule_rotations as $schedule_rotation){
									?>
									<option value="<?php echo $schedule_rotation['value']; ?>" <?php selectedRotationValue($schedule_rotation['value'], $schedule_pattern); ?>><?php echo $schedule_rotation['label']; ?></option>
									<?php
								}
								?>
							</select>
							<a href="https://youtu.be/xMIWXwaI1Ug" title="<?php echo esc_html_e('View video example of adding a custom shift schedule', 'fd_shift_calendar'); ?>" data-lity><?php echo esc_html_e('Video Example', 'fd_shift_calendar'); ?></a>
						</td>
					</tr>
					
					
					<!-- Custom Schedule Pattern -->
					<tr valign="top" class="shift-schedule <?php echo ($shift_rotation == 'custom') ? '' : 'hidden'; ?>">
						<th scope="row" class="label">
							<label for="shift_start_date"><?php echo esc_html_e('Schedule', 'fd_shift_calendar'); ?></label>
						</th>
						<td class="input" id="schedule">
							<table id="fd-schedule" class="inline-table" style="max-width: 400px;">
								<tr class="schedule-labels">
									<th class="schedule-times"><?php echo esc_html_e('Hours', 'fd_shift_calendar'); ?></th>
									<th class="schedule-on"><?php echo esc_html_e('On', 'fd_shift_calendar'); ?></th>
									<th class="schedule-off"><?php echo esc_html_e('Off', 'fd_shift_calendar'); ?></th>
								</tr>
								
								<?php
									$fd_shift_calendar_schedules = get_option('fd_calendar_shift_schedule');
									if($fd_shift_calendar_schedules){
										foreach($fd_shift_calendar_schedules as $i => $options){
											?>
											<tr class="schedule-inputs">
												<td class="shift-schedule-time">
													<input class="shift-schedule-time-on" type="text" placeholder="<?php echo esc_html_e('On', 'fd_shift_calendar'); ?>" name="fd_calendar_shift_schedule[<?php echo $i; ?>][time_on]" value="<?php echo esc_attr($fd_shift_calendar_schedules[$i]['time_on']); ?>" autocomplete="off"> <?php echo esc_html_e('on', 'fd_shift_calendar'); ?>
													<input class="shift-schedule-time-off" type="text" placeholder="<?php echo esc_html_e('Off', 'fd_shift_calendar'); ?>" name="fd_calendar_shift_schedule[<?php echo $i; ?>][time_off]" value="<?php echo esc_attr($fd_shift_calendar_schedules[$i]['time_off']); ?>" autocomplete="off"> <?php echo esc_html_e('off', 'fd_shift_calendar'); ?>
												</td>
												
												<td class="shift-schedule-on">
													<input class="shift-schedule-crew-on" type="text" placeholder="<?php echo esc_html_e('Shift(s)', 'fd_shift_calendar'); ?>" name="fd_calendar_shift_schedule[<?php echo $i; ?>][crew_on]" value="<?php echo esc_attr($fd_shift_calendar_schedules[$i]['crew_on']); ?>" autocomplete="off">
												</td>
												
												<td class="shift-schedule-off">
													<input class="shift-schedule-crew-off" type="text" placeholder="<?php echo esc_html_e('Shift(s)', 'fd_shift_calendar'); ?>" name="fd_calendar_shift_schedule[<?php echo $i; ?>][crew_off]" value="<?php echo esc_attr($fd_shift_calendar_schedules[$i]['crew_off']); ?>" autocomplete="off">
													<span class="repeater-actions">
														<a class="repeater-action add button" title="<?php echo esc_html_e('Add Shift Rotation', 'fd_shift_calendar'); ?>" href="#add">+</a>
														<a class="repeater-action remove button <?php echo ($i == 1) ? 'disabled' : ''; ?>" title="<?php echo esc_html_e('Remove Shift Rotation', 'fd_shift_calendar'); ?>" href="#remove">-</a>
													</span>
												</td>
											</tr>
											<?php
											$i++;
										}
									}else{
										?>
										<tr class="schedule-inputs">
											<td class="shift-schedule-time">
												<input class="shift-schedule-time-on" type="text" placeholder="<?php echo esc_html_e('On', 'fd_shift_calendar'); ?>" name="fd_calendar_shift_schedule[1][time_on]" value="<?php echo esc_attr($fd_shift_calendar_schedules[1]['time_on']); ?>" autocomplete="off"> <?php echo esc_html_e('on', 'fd_shift_calendar'); ?>
												<input class="shift-schedule-time-off" type="text" placeholder="<?php echo esc_html_e('Off', 'fd_shift_calendar'); ?>" name="fd_calendar_shift_schedule[1][time_off]" value="<?php echo esc_attr($fd_shift_calendar_schedules[1]['time_off']); ?>" autocomplete="off"> <?php echo esc_html_e('off', 'fd_shift_calendar'); ?>
											</td>
											
											<td class="shift-schedule-on">
												<input class="shift-schedule-crew-on" type="text" placeholder="<?php echo esc_html_e('Shift(s)', 'fd_shift_calendar'); ?>" name="fd_calendar_shift_schedule[1][crew_on]" value="<?php echo esc_attr($fd_shift_calendar_schedules[1]['crew_on']); ?>" autocomplete="off">
											</td>
											
											<td class="shift-schedule-off">
												<input class="shift-schedule-crew-off" type="text" placeholder="<?php echo esc_html_e('Shift(s)', 'fd_shift_calendar'); ?>" name="fd_calendar_shift_schedule[1][crew_off]" value="<?php echo esc_attr($fd_shift_calendar_schedules[1]['crew_off']); ?>" autocomplete="off">
												<span class="repeater-actions">
													<a class="repeater-action add button" href="#add">+</a>
													<a class="repeater-action remove button disabled" href="#remove">-</a>
												</span>
											</td>
										</tr>
										<?php
									}
								?>
							</table>
						</td>
					</tr>
					
					
					<!-- Schedule Pattern Display -->
					<tr valign="top" class="schedule-pattern <?php echo ($shift_rotation == 'custom') ? 'hidden' : ''; ?>">
						<th scope="row" class="label">
							<label for="fd_calendar_schedule_pattern"><?php echo esc_html_e('Schedule Pattern', 'fd_shift_calendar'); ?></label>
						</th>
						<td class="input" id="schedule-pattern-input">
							<input type="text" id="schedule_pattern" name="fd_calendar_schedule_pattern" value="<?php echo esc_attr(get_option('fd_calendar_schedule_pattern')); ?>">
						</td>
					</tr>
					
					
					<!-- Submit -->
					<tr valign="top" class="submit">
						<th scope="row" class="label">&nbsp;</th>
						<td>
							<?php submit_button(); ?>
						</td>
					</tr>
					
				</table>
				
			</form>
			
			<!-- Monthly Calendar Preview -->
			<div id="fd-shift-calendar-display">
				<h3><?php echo esc_html_e('Preview', 'fd_shift_calendar'); ?></h3>				
				<?php echo fd_shift_calendar_generate('monthly'); ?>
			</div>
			
			<!-- Plugin Credits -->
			<div class="fd-shift-calendar-credits">
				<?php
					$allowed_html = array(
						'a' => array(
							'href' => array(),
							'title' => array(),
							'target' => array(),
						)
					);
					printf( wp_kses( __('This plugin makes use of the following jQuery plugins <a href="%s" title="A datepicker for twitter bootstrap" target="_blank">bootstrap-datepicker</a> and <a href="%s" title="Lity - Lightweight, accessible and responsive lightbox" target="_blank">Lity</a>', 'fd_shift_calendar'), $allowed_html), esc_url('https://github.com/uxsolutions/bootstrap-datepicker'),  esc_url('https://sorgalla.com/lity/'));
				?>
			</div>
		</div>

	 <?php
	}
	
?>