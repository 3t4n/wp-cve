<?php 
$location_id = isset( $_REQUEST['id'] ) ? sanitize_text_field($_REQUEST['id']) : '0';
$location = array();
$location = wc_local_pickup()->admin->get_data_byid($location_id);
?>
<section class="pickup-location-setting">
	<div class="location-setting">
		<form method="post" id="wclp_location_tab_form">
			<div class="accordion heading address-special">
				<label><?php esc_html_e( 'Name & Special Instructions', 'advanced-local-pickup-for-woocommerce' ); ?>
				<span class="wclp-btn">
					<div class="spinner workflow_spinner" style="float:none"></div>
					<button name="save" class="wclp-save button-primary woocommerce-save-button btn_location_submit" type="submit" value="Save changes"><?php esc_html_e( 'Save & close', 'advanced-local-pickup-for-woocommerce' ); ?></button>
					<span class="alp_error_msg"></span>								
					<?php wp_nonce_field( 'wclp_location_edit_form_action', 'wclp_location_edit_form_nonce_field' ); ?>
					<input type="hidden" name="action" value="wclp_location_edit_form_update">
				</span>
				<span class="dashicons dashicons-arrow-right-alt2"></span></label>
				<br><span class="heading-subtitle"><?php echo ( isset( $location->store_name ) ) ? esc_html(stripslashes($location->store_name)) : ''; ?></span>
			</div>
			<div class="panel options address-special">
				<table class="form-table">
					<tbody>
						<input type="hidden" id="location_id" name="id" value="<?php echo esc_html($location_id); ?>">
						<tr valign="top">
							<th><label for=""><?php esc_html_e( 'Location Name', 'advanced-local-pickup-for-woocommerce' ); ?><span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( 'The location name for your business location.', 'advanced-local-pickup-for-woocommerce' ); ?>"></span></label></th>
							<td class="forminp">                                            
								<fieldset>
									<input class="input-text regular-input " type="text" name="wclp_store_name" id="wclp_store_name" style="" value="<?php echo ( isset( $location->store_name ) ) ? esc_html(stripslashes($location->store_name)) : ''; ?>" placeholder="">
									<span class="alp_error_msg" style="display:none;"><?php esc_html_e( 'you must add location name and save to proceed', 'advanced-local-pickup-for-woocommerce' ); ?></span>
								</fieldset>
							</td>
						</tr>
						<tr valign="top" class="">
							<th class="text-top">
								<label for=""><?php esc_html_e( 'Special Instruction', 'advanced-local-pickup-for-woocommerce' ); ?><span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( 'The special instruction for your store.', 'advanced-local-pickup-for-woocommerce' ); ?>" ></span></label>
							</th>
							<td class="forminp">
								<fieldset>
									<textarea rows="3" cols="20" class="input-text regular-input" type="textarea" name="wclp_store_instruction" id="wclp_store_instruction" style="" placeholder="Special Pickup instruction to your customers"><?php echo ( isset( $location->store_instruction ) ) ? esc_html(stripslashes($location->store_instruction)) : ''; ?></textarea>
								</fieldset>                
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="accordion heading address">
				<label><?php esc_html_e( 'Address', 'advanced-local-pickup-for-woocommerce' ); ?>
				<span class="wclp-btn">
					<div class="spinner workflow_spinner" style="float:none"></div>
					<button name="save" class="wclp-save button-primary woocommerce-save-button btn_location_submit" type="submit" value="Save changes"><?php esc_html_e( 'Save & close', 'advanced-local-pickup-for-woocommerce' ); ?></button>
					<span class="alp_error_msg"></span>								
					<input type="hidden" name="action" value="wclp_location_edit_form_update">
				</span>
				<span class="dashicons dashicons-arrow-right-alt2"></span></label>
				<br>
				<span class="heading-subtitle">
					<?php 
					$country_setting =  isset( $location->store_country ) ? $location->store_country : get_option('woocommerce_default_country') ;
					if ( strstr( $country_setting, ':' ) ) {
						$country_setting = explode( ':', $country_setting );
						$country         = current( $country_setting );
						$state           = end( $country_setting );
					} else {
						$country = $country_setting;
						$state   = '';
					}
					if (!empty( $location->store_address )) {
						echo esc_html($location->store_address);
					} 
					if (!empty( $location->store_address_2 )) {
						echo ', '; 
						echo esc_html($location->store_address_2);
					} 
					if (!empty( $location->store_city )) {
						echo ', '; 
						echo esc_html($location->store_city);
					} 
					if ( ( class_exists('Advanced_local_pickup_PRO') && isset( $location->store_display_country ) && '1' != $location->store_display_country ) ) {
						if (!empty($state) && !empty( $location->store_name )) {
							echo ', '; 
							echo esc_html(WC()->countries->get_states( $country )[$state]);
						}
					}
					
					if (!empty( $location->store_postcode )) {
						echo ' - '; 
						echo esc_html($location->store_postcode);
					}
					if ( ( class_exists('Advanced_local_pickup_PRO') && isset( $location->store_display_country ) && '1' != $location->store_display_country ) || !isset( $location->store_display_country ) ) {
						if ($country && !empty( $location->store_name )) {
							echo ', '; 
							echo esc_html(WC()->countries->countries[$country]) . '.';
						}
					} 
					?>
				</span>
			</div>
			<div class="panel options address">
				<table class="form-table">
					<tbody>
						<tr valign="top" class="">							
							<th>
								<label for=""><?php esc_html_e( 'Address line 1', 'woocommerce' ); ?><span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( 'The street address for your business location.', 'woocommerce' ); ?>"></span></label>
							</th>
							<td class="forminp">                                              
								<fieldset>
									<input class="input-text regular-input " type="text" name="wclp_store_address" id="wclp_store_address" style="" value="<?php echo ( isset( $location->store_address ) ) ? esc_html($location->store_address) : ''; ?>" placeholder="">
								</fieldset>
							</td>
						</tr>
						<tr valign="top" class="">
							<th>
								<label for=""><?php esc_html_e( 'Address line 2', 'woocommerce' ); ?><span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( 'An additional, optional address line for your business location.', 'advanced-local-pickup-for-woocommerce' ); ?>"></span></label>
							</th>
							<td class="forminp">                                                
								<fieldset>
									<input class="input-text regular-input " type="text" name="wclp_store_address_2" id="wclp_store_address_2" style="" value="<?php echo ( isset( $location->store_address_2 ) ) ? esc_html($location->store_address_2) : ''; ?>" placeholder="">
								</fieldset>
							</td>
						</tr>
						<tr valign="top" class="">
							<th>
								<label for=""><?php esc_html_e( 'City', 'woocommerce' ); ?><span class="woocommerce-help-tip tipTip"  title="<?php esc_html_e( 'The city in which your business is located.', 'woocommerce' ); ?>"></span></label>
							</th>
							<td class="forminp">                                                
								<fieldset>
									<input class="input-text regular-input " type="text" name="wclp_store_city" id="wclp_store_city" style="" value="<?php echo ( isset( $location->store_city ) ) ? esc_html($location->store_city) : ''; ?>" placeholder="">
								</fieldset>
							</td>
						</tr>
						<tr valign="top" class="">
							<th>
								<label for=""><?php esc_html_e( 'Country / State', 'woocommerce' ); ?><span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( 'The country and state or province, if any, in which your business is located.', 'woocommerce' ); ?>"></span></label>
							</th>
							<td class="forminp">                                                
								<?php
								$country_setting =  isset( $location->store_country ) ? $location->store_country : get_option('woocommerce_default_country') ; //get_option('woocommerce_default_country'));
								if ( strstr( $country_setting, ':' ) ) {
									$country_setting = explode( ':', $country_setting );
									$country         = current( $country_setting );
									$state           = end( $country_setting );
								} else {
									$country = $country_setting;
									$state   = '*';
								}
								?>
								<fieldset>
									<select name="wclp_default_country" id="wclp_default_country" style="" data-placeholder="<?php esc_attr__( 'Choose a country / region&hellip;', 'woocommerce' ); ?>" aria-label="<?php esc_attr__( 'Country / Region', 'woocommerce' ); ?>" class="select wc-enhanced-select">
										<?php WC()->countries->country_dropdown_options( $country, $state ); ?>
									</select>
									<?php if (class_exists('Advanced_local_pickup_PRO')) { ?>
										<input type="hidden" name="wclp_display_country" value="0">
										<input type="checkbox" id="wclp_display_country" name="wclp_display_country" class="" <?php echo ( !isset( $location->store_display_country ) || ( isset( $location->store_display_country ) && '1' == $location->store_display_country ) ) ? 'checked' : ''; ?> value="1" style="margin: 5px;"><?php esc_html_e( 'Hide', 'woocommerce' ); ?>
									<?php } ?>
								</fieldset>
							</td>
						</tr>
						<tr valign="top" class="">								
							<th>
								<label for=""><?php esc_html_e( 'Postcode / ZIP', 'woocommerce' ); ?><span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( 'The postal code, if any, in which your business is located.', 'woocommerce' ); ?>"></span></label>
							</th>
							<td class="forminp">                                                
								<fieldset>
									<input class="input-text regular-input " type="text" name="wclp_store_postcode" id="wclp_store_postcode" style="" value="<?php echo ( isset( $location->store_postcode ) ) ? esc_html($location->store_postcode) : ''; ?>" placeholder="">
								</fieldset>
							</td>
						</tr>
						<tr valign="top" class="">								
							<th>
								<label for=""><?php esc_html_e( 'Phone Number', 'woocommerce' ); ?><span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( 'The phone number for your business information.', 'advanced-local-pickup-for-woocommerce' ); ?>"></span></label>
							</th>
							<td class="forminp">                                                
								<fieldset>
									<input class="input-text regular-input " type="text" name="wclp_store_phone" id="wclp_store_phone" style="" value="<?php echo ( isset( $location->store_phone ) ) ? esc_html($location->store_phone) : ''; ?>" placeholder="Ex.9900990099">
								</fieldset>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			<div class="accordion heading business-hours">
				<label><?php esc_html_e( 'Business Hours', 'advanced-local-pickup-for-woocommerce' ); ?>
				<span class="wclp-btn">
					<div class="spinner workflow_spinner" style="float:none"></div>
					<button name="save" class="wclp-save button-primary woocommerce-save-button btn_location_submit" type="submit" value="Save changes"><?php esc_html_e( 'Save & close', 'advanced-local-pickup-for-woocommerce' ); ?></button>
					<span class="alp_error_msg"></span>								
					<?php //wp_nonce_field( 'wclp_location_edit_form_action', 'wclp_location_edit_form_nonce_field' ); ?>
					<input type="hidden" name="action" value="wclp_location_edit_form_update">
				</span>
				<span class="dashicons dashicons-arrow-right-alt2"></span></label>
				<br>
				<span class="heading-subtitle">
				<?php
				$store_days = isset($location->store_days) ? unserialize($location->store_days) : get_option('wclp_store_days');
				if (!empty($store_days) && !empty( $location->store_name )) {
					$all_days = array(
					'sunday' => esc_html__( 'Sunday', 'default' ),
					'monday' => esc_html__( 'Monday', 'default'),
					'tuesday' => esc_html__( 'Tuesday', 'default' ),
					'wednesday' => esc_html__( 'Wednesday', 'default' ),
					'thursday' => esc_html__( 'Thursday', 'default' ),
					'friday' => esc_html__( 'Friday', 'default' ),
					'saturday' => esc_html__( 'Saturday', 'default' ),
					);
					$w_day = array_slice($all_days, get_option('start_of_week'));
					foreach ($all_days as $key=>$val) {
						$w_day[$key] = $val;
					}
					foreach ($store_days as $key => $val) {
						if ($w_day[$key]) {
							$w_day[$key] = $val;
						}
					}
							
					$wclp_store_time_format = isset($location->store_time_format) ? $location->store_time_format : '24';
										
					if ('12' == $wclp_store_time_format) {
						foreach ($w_day as $key=>$val) {	
							if (isset($val['wclp_store_hour'])) {
								$last_digit = explode(':', $val['wclp_store_hour']);
								if ('00' == end($last_digit)) {
									$val['wclp_store_hour'] = gmdate('ga', strtotime($val['wclp_store_hour']));
								} else {
									$val['wclp_store_hour'] = gmdate('g:ia', strtotime($val['wclp_store_hour']));
								}
							}
							if (isset($val['wclp_store_hour_end'])) {
								$last_digit = explode(':', $val['wclp_store_hour_end']);
								if ('00' == end($last_digit)) {
									$val['wclp_store_hour_end'] = gmdate('ga', strtotime($val['wclp_store_hour_end']));
								} else {
									$val['wclp_store_hour_end'] = gmdate('g:ia', strtotime($val['wclp_store_hour_end']));
								}
							}
							$w_day[$key] = $val;				
						}	
					}
					if (!empty($w_day)) { 	
						$n = 0;
						$new_array = [];
						$previousValue = [];
						
						foreach ($w_day as $day=>$value) {				
							if (isset($value['checked']) && 1 == $value['checked']) {																	
								if ($value != $previousValue) {
									$n++;
								}
								$new_array[$n][$day] = $value;					
								$previousValue = $value;
							} else {
								$n++;
								$new_array[$n][$day] = '';	
								$previousValue = '';
							}							
						}
					}
					
					foreach ($new_array as $key => $data) {
						if (1 == count($data)) {							
							if (isset(reset($data)['wclp_store_hour']) && '' != reset($data)['wclp_store_hour'] && isset(reset($data)['wclp_store_hour_end']) && '' != reset($data)['wclp_store_hour_end']) {
								reset($data);
								echo esc_html(substr(ucfirst(key($data)), 0, 3), 'default') . '<span>: ' . esc_html(reset($data)['wclp_store_hour']) . '-' . esc_html(reset($data)['wclp_store_hour_end']) . '</span> ';
							} 
						}
						if (2 == count($data)) {
							if (isset(reset($data)['wclp_store_hour']) && '' != reset($data)['wclp_store_hour'] && isset(reset($data)['wclp_store_hour_end']) && '' != reset($data)['wclp_store_hour_end']) {
								reset($data);
								$array_key_first = substr(key($data), 0, 3);
								end($data);
								$array_key_last = substr(key($data), 0, 3);
								echo esc_html(ucfirst($array_key_first), 'default') . '<span>-</span>' . esc_html(ucfirst($array_key_last), 'default') . '<span>: ' . esc_html(reset($data)['wclp_store_hour']) . '-' . esc_html(reset($data)['wclp_store_hour_end']) . '</span> ';
							} 
						}
						if (count($data) > 2) { 
							if (isset(reset($data)['wclp_store_hour']) && '' != reset($data)['wclp_store_hour'] && isset(reset($data)['wclp_store_hour_end']) && '' != reset($data)['wclp_store_hour_end']) {
								reset($data);
								$array_key_first = substr(key($data), 0, 3);
								end($data);
								$array_key_last = substr(key($data), 0, 3);
								echo esc_html(ucfirst($array_key_first), 'default') . esc_html(' to', 'advanced-local-pickup-for-woocommerce') . esc_html(ucfirst($array_key_last), 'default') . '<span>: ' . esc_html(reset($data)['wclp_store_hour']) . '-' . esc_html(reset($data)['wclp_store_hour_end']) . '</span> ';
							} 
						}	
					}
				}
				?>
				</span>
			</div>
			<div class="panel options business-hours">
				<table class="form-table">
					<tbody>
						<tr valign="top" class="">								
							<td style="padding-top:5px;">
								<div class="hours-block time-format">
									<label class="time-format" for=""><?php esc_html_e( 'Display Time Format', 'advanced-local-pickup-for-woocommerce' ); ?><span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( 'Select time format which you want to display in business hours for customers.', 'advanced-local-pickup-for-woocommerce' ); ?>"></span></label>
								</div>
								<div>
									<fieldset>
										<select class="select select2" id="wclp_default_time_format" name="wclp_default_time_format">
											<option value="24" <?php echo ( isset( $location->store_time_format ) && '24' == $location->store_time_format ) ? 'selected' : ''; ?>><?php esc_html_e( '24 hour', 'advanced-local-pickup-for-woocommerce' ); ?></option>
											<option value="12" <?php echo ( isset( $location->store_time_format ) && '12' == $location->store_time_format ) ? 'selected' : ''; ?>><?php esc_html_e( '12 hour', 'advanced-local-pickup-for-woocommerce' ); ?></option>
										</select>
									</fieldset>
								<div>
							</td>
						</tr>
						<tr valign="top" class="">								
							<td>
								<div class="hours-block work-hours">
									<label class="work-hours" for=""><?php esc_html_e( 'Work Hours', 'advanced-local-pickup-for-woocommerce' ); ?><span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( 'the select for working days of your store.', 'advanced-local-pickup-for-woocommerce' ); ?>"></span></label>
								</div>
								<div class="pickup_hours_div" style="">
								<?php
									$all_days = array(
										'sunday' => esc_html__( 'Sunday', 'default' ),
										'monday' => esc_html__( 'Monday', 'default'),
										'tuesday' => esc_html__( 'Tuesday', 'default' ),
										'wednesday' => esc_html__( 'Wednesday', 'default' ),
										'thursday' => esc_html__( 'Thursday', 'default' ),
										'friday' => esc_html__( 'Friday', 'default' ),
										'saturday' => esc_html__( 'Saturday', 'default' ),
									);
									
									$days = array_slice($all_days, get_option('start_of_week'));
									
									foreach ($all_days as $key=>$val) {
										$days[$key] = $val;
									}
								
									foreach ((array) $days as $key => $val ) {									
											
										$multi_checkbox_data = isset( $location->store_days ) ? unserialize($location->store_days) : get_option('wclp_store_days');
										$wclp_store_time_format = '24';
										
										if (isset($multi_checkbox_data[$key]['checked']) && 1 == $multi_checkbox_data[$key]['checked']) {
											$checked='checked';
											$class = 'hours-time';
										} else {
											$checked='';
											$class = '';
										}
										
										
										$send_time_array = array();										
										for ( $hour = 0; $hour < 24; $hour++ ) {
											for ( $min = 0; $min < 60; $min = $min + apply_filters('alp_work_hours_slots', $default = '30') ) {
												$this_time = gmdate( 'H:i', strtotime( "$hour:$min" ) );
												$send_time_array[ $this_time ] = $this_time;
											}	
										}
										?>
									<div class="wplp_pickup_duration" style="">
										<fieldset style=""><label class="" for="<?php echo esc_html($key); ?>" style="">
											<input type="checkbox" id="<?php echo esc_html($key); ?>" name="wclp_store_days[<?php echo esc_html($key); ?>][checked]" class="pickup_days_checkbox" <?php echo esc_html($checked); ?> value="1"/>
											<span class="pickup_days_lable" style="width: auto;"><?php esc_html_e($val, 'advanced-local-pickup-for-woocommerce'); ?></span>	
										</label></fieldset>
										<fieldset class="wclp_pickup_time_fieldset" style="">
											
											<span class="hours <?php echo esc_html($class); ?>" style="">
												<?php
												if (isset($multi_checkbox_data[$key]['wclp_store_hour'])) { 
													if ('12' == $wclp_store_time_format) {
														$last_digit = explode(':', $multi_checkbox_data[$key]['wclp_store_hour']);
														if ('00' == end($last_digit)) {
															$wclp_store_hour = gmdate('g:ia', strtotime($multi_checkbox_data[$key]['wclp_store_hour']));
														} else {
															$wclp_store_hour = gmdate('g:ia', strtotime($multi_checkbox_data[$key]['wclp_store_hour']));
														}
													} else {
														$wclp_store_hour = $multi_checkbox_data[$key]['wclp_store_hour'];
													}
													echo esc_html($wclp_store_hour); 
												}
												echo !empty($multi_checkbox_data[$key]['wclp_store_hour']) ? ' - ' : '<span class="dashicons dashicons-plus"></span>';
												if (isset($multi_checkbox_data[$key]['wclp_store_hour_end'])) { 
													if ('12' == $wclp_store_time_format) {
														$last_digit = explode(':', $multi_checkbox_data[$key]['wclp_store_hour_end']);
														if ('00' == end($last_digit)) {
															$wclp_store_hour_end = gmdate('g:ia', strtotime($multi_checkbox_data[$key]['wclp_store_hour_end']));
														} else {
															$wclp_store_hour_end = gmdate('g:ia', strtotime($multi_checkbox_data[$key]['wclp_store_hour_end']));
														}
													} else {
														$wclp_store_hour_end = $multi_checkbox_data[$key]['wclp_store_hour_end'];
													}
													echo esc_html($wclp_store_hour_end);
												}
												?>
												</span>
											<?php do_action('wclp_split_hours_hook', $key, $wclp_store_time_format, $location, $class); ?>
											<div id="" class="popupwrapper alp-hours-popup" style="display:none;">
												<div class="popuprow">
													<span class="dashicons dashicons-no-alt popup_close_icon"></span>
													<div class="alp-hours-popup">
														<div id="header-text">
															<span style=""><?php esc_html_e( 'From', 'advanced-local-pickup-for-woocommerce' ); ?></span>
															<span><?php esc_html_e( ' To', 'advanced-local-pickup-for-woocommerce' ); ?></span>
														</div>
														<span class="morning-time"><select class="select <?php echo esc_html($key); ?> wclp_pickup_time_select start" name="wclp_store_days[<?php echo esc_html($key); ?>][wclp_store_hour]"> <option value="" ><?php esc_html_e( 'Select', 'woocommerce' ); ?></option>
															<?php 
															foreach ( (array) $send_time_array as $key1 => $val1 ) {
																if ('12' == $wclp_store_time_format) {
																	$last_digit = explode(':', $val1);
																	if ('00' == end($last_digit)) {
																		$val1 = gmdate('g:ia', strtotime($val1));
																	} else {
																		$val1 = gmdate('g:ia', strtotime($val1));
																	}
																}
																?>
															<option value="<?php echo esc_html($key1); ?>" <?php echo ( isset($multi_checkbox_data[$key]['wclp_store_hour']) && $multi_checkbox_data[$key]['wclp_store_hour'] == $key1 ) ? 'selected' : ''; ?>><?php echo esc_html($val1); ?></option>
															<?php } ?>
														</select>
														<select class="select <?php echo esc_html($key); ?> wclp_pickup_time_select end" name="wclp_store_days[<?php echo esc_html($key); ?>][wclp_store_hour_end]"><option value=""><?php esc_html_e( 'Select', 'woocommerce' ); ?></option>
																<?php 
																foreach ( (array) $send_time_array as $key2 => $val2 ) {
																	if ('12' == $wclp_store_time_format) {
																		$last_digit = explode(':', $val2);
																		if ( '00' == end($last_digit)) {
																			$val2 = gmdate('g:ia', strtotime($val2));
																		} else {
																			$val2 = gmdate('g:ia', strtotime($val2));
																		}
																	}
																	?>
																<option value="<?php echo esc_html($key2); ?>" <?php echo ( isset( $multi_checkbox_data[$key]['wclp_store_hour_end'] ) && $multi_checkbox_data[$key]['wclp_store_hour_end'] == $key2 ) ? 'selected' : ''; ?> ><?php echo esc_html($val2); ?></option>
															<?php } ?>
														</select>
														<span class="dashicons dashicons-trash" ></span>
														</span>
														<?php do_action('wclp_multi_hours_hook', $key, $wclp_store_time_format, $location, $send_time_array); ?>
														<p class="add-interval" 
														<?php echo ( !class_exists('Advanced_local_pickup_PRO') || ( isset( $multi_checkbox_data[$key]['wclp_store_hour_end2'] ) && '' != $multi_checkbox_data[$key]['wclp_store_hour_end2'] ) ) ? 'style="display:none"' : ''; ?>>+ Add Interval</p>
													</div>
													<?php do_action('wclp_apply_mltiple_popup_hook', $days, $key); ?>
													<button type="button" class="wclp-apply button-primary" value="<?php echo esc_html($key); ?>"><?php esc_html_e('Apply & close', 'advanced-local-pickup-for-woocommerce'); ?></button>
													<?php do_action('wclp_apply_mltiple_on_days_hook'); ?>
												</div>
												<div class="popupclose"></div>
											</div>
											</fieldset>
										</div> 						
									<?php } ?>
									</div>
								</div>              
							</td>
						</tr>
						<?php do_action('wclp_add_business_setting_html_hook', $location); ?>
					</tbody>
				</table>
			</div>

			<div class="accordion heading premium">
				<label>
					<?php esc_html_e( 'Pickup Appointments', 'advanced-local-pickup-for-woocommerce' ); ?>
					<span class="premium-label"><?php esc_html_e( 'Premium', 'advanced-local-pickup-for-woocommerce' ); ?></span>
					<span class="dashicons dashicons-arrow-right-alt2"></span>
				</label>
			</div>

			<div class="accordion heading premium">
				<label>
					<?php esc_html_e( 'Products', 'advanced-local-pickup-for-woocommerce' ); ?>
					<span class="premium-label"><?php esc_html_e( 'Premium', 'advanced-local-pickup-for-woocommerce' ); ?></span>
					<span class="dashicons dashicons-arrow-right-alt2"></span>
				</label>
			</div>

			<div class="accordion heading premium">
				<label>
					<?php esc_html_e( 'Shipping Method', 'advanced-local-pickup-for-woocommerce' ); ?>
					<span class="premium-label"><?php esc_html_e( 'Premium', 'advanced-local-pickup-for-woocommerce' ); ?></span>
					<span class="dashicons dashicons-arrow-right-alt2"></span>
				</label>
			</div>

			<div class="accordion heading premium">
				<label>
					<?php esc_html_e( 'Payment Method', 'advanced-local-pickup-for-woocommerce' ); ?>
					<span class="premium-label"><?php esc_html_e( 'Premium', 'advanced-local-pickup-for-woocommerce' ); ?></span>
					<span class="dashicons dashicons-arrow-right-alt2"></span>
				</label>
			</div>

			<div class="accordion heading premium">
				<label>
					<?php esc_html_e( 'Price Adjustments', 'advanced-local-pickup-for-woocommerce' ); ?>
					<span class="premium-label"><?php esc_html_e( 'Premium', 'advanced-local-pickup-for-woocommerce' ); ?></span>
					<span class="dashicons dashicons-arrow-right-alt2"></span>
				</label>
			</div>

			<div class="accordion heading premium">
				<label>
					<?php esc_html_e( 'Notifications', 'advanced-local-pickup-for-woocommerce' ); ?>
					<span class="premium-label"><?php esc_html_e( 'Premium', 'advanced-local-pickup-for-woocommerce' ); ?></span>
					<span class="dashicons dashicons-arrow-right-alt2"></span>
				</label>
			</div>

			<div class="accordion heading premium">
				<label>
					<?php esc_html_e( 'Automations', 'advanced-local-pickup-for-woocommerce' ); ?>
					<span class="premium-label"><?php esc_html_e( 'Premium', 'advanced-local-pickup-for-woocommerce' ); ?></span>
					<span class="dashicons dashicons-arrow-right-alt2"></span>
				</label>
			</div>
			<?php do_action('wclp_add_setting_html_hook', $location); ?>
		</form>
	</div>
</section>
