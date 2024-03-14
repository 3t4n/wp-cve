<?php
// prepare hr and min dropdown data
if ( $elsettings->date_format == 'mm-dd-yy' ) {
	$date_format = 'm-d-Y';
} else {
	$date_format = 'd-m-Y';
}

$now                  = current_time( 'timestamp' );
$minimum_time_allowed = $now + (float) $elsettings->restrict_time * 3600;
$min_pickup_hr        = gmdate( 'H', $minimum_time_allowed );
$pickup_ampm          = 'AM';

if ( $elsettings->load_current_date ) {
	$pickup_date = gmdate( $date_format, $minimum_time_allowed );
	if ( $elsettings->time_format == '24hr' ) {
		$pickup_hr  = gmdate( 'H', $minimum_time_allowed );
		$pickup_min = gmdate( 'i', $minimum_time_allowed );
	} else {
		$pickup_hr   = gmdate( 'h', $minimum_time_allowed );
		$pickup_min  = gmdate( 'i', $minimum_time_allowed );
		$pickup_ampm = gmdate( 'A', $minimum_time_allowed );
	}
	// Round minute value to the next multiple of 5, exclude the current number
	$pickup_min = round( ( $pickup_min + 5 / 2 ) / 5 ) * 5;

	// if modified pickup min is 60, then add 5 mins to minimum allowed time and recalculate times
	if ( $pickup_min == 60 ) {
		$minimum_time_allowed += 300;
		$pickup_date           = gmdate( $date_format, $minimum_time_allowed );
		if ( $elsettings->time_format == '24hr' ) {
			$pickup_hr  = gmdate( 'H', $minimum_time_allowed );
			$pickup_min = gmdate( 'i', $minimum_time_allowed );
		} else {
			$pickup_hr   = gmdate( 'h', $minimum_time_allowed );
			$pickup_min  = gmdate( 'i', $minimum_time_allowed );
			$pickup_ampm = gmdate( 'A', $minimum_time_allowed );
		}
		$pickup_min = round( ( $pickup_min + 5 / 2 ) / 5 ) * 5;
	}
	$pickup_min = str_pad( $pickup_min, 2, '0', STR_PAD_LEFT );
} else {
	$pickup_date = '';
	$pickup_hr   = '';
	$pickup_min  = '';
	$pickup_ampm = 'AM';
}

if ( $elsettings->time_format == '24hr' ) {
	$hr_start     = 0;
	$hr_end_limit = 24;
} else {
	$hr_start     = 1;
	$hr_end_limit = 13;
}

// get the maximum passengers
$max           = BookingHelper::getMaxSeatsData();
$max_passenger = isset( $max->max_passenger ) ? $max->max_passenger : 8;
$max_suitcase  = isset( $max->max_suitcase ) ? $max->max_suitcase : 9;
$max_child     = isset( $max->max_child ) ? $max->max_child : 5;

$passenger_options = array( 0 => 0 );
for ( $i = 1; $i <= $max_passenger; $i++ ) {
	$passenger_options[ $i ] = $i;
}

$suitcases_options = array( 0 => 0 );
for ( $i = 1; $i <= $max_suitcase; $i++ ) {
	$suitcases_options[ $i ] = $i;
}

$chseats_options = array( 0 => 0 );
for ( $i = 1; $i <= $max_child; $i++ ) {
	$chseats_options[ $i ] = $i;
}

$has_chseats = ( $max_child > 0 ) ? true : false;
?>

<div id="limobooking-step1-container-area" class="limobooking-step1-container-area clearfix">
	<div class="row left-right-wrap clearfix">
		<div class="limobooking-step1-left-area col-sm-6">
			<input type="hidden" id="booking_type" name="booking_type" value="address" />
			<div class="step1-inputWrap form-group form-group-lg non-shuttle non-shared-rides timeSet">
				<div class="row">
					<label class="label-lg full-width pickup-date-time-label"><?php esc_attr_e( 'BOOKING_FORM_SELECT_DATE_LABEL', 'cab-fare-calculator' ); ?></label>
					<div class="step1-sm-inputWrap pickupDate col-xs-6 col-md-6">
						<div id="pickupDateHolderStep1" class="pickupDateHolderStep1 input-group input-group-lg">
							<input type="text" name="orderdate" id="pickupDateStep1" class="datepickerbox form-control datepickerbutton required" value="<?php echo $pickup_date; ?>" />
							<span class="input-group-btn datepickerbutton">
							<button type="button" class="btn btn-default calendar">
								<span class="icon"><i class="tb tb-calendar"></i></span>
							</button>
							</span>
						</div>
					</div>
					<?php
					if ( $elsettings->time_format == '12hr' ) {
						$additional_class = ' col-xs-4 col-md-4';
					} else {
						$additional_class = ' col-xs-6 col-md-6';
					}
					?>
										
					<div class="step1-sm-inputWrap pickupTime col-xs-6 col-md-6">
						<div class="time-date-wrapper row">
							<div class="row">
								<div id="pickupTimeHolderStep1" class="pickupTimeHolderStep1 hour <?php echo $additional_class; ?>">
									<input type="text" name="selPtHr1" id="selPtHr1" readonly="readonly" class="form-control" value="<?php echo $pickup_hr; ?>" placeholder="<?php esc_attr_e( 'HRS', 'cab-fare-calculator' ); ?>" />
									<div class="timepicker-hours pickup-hours" data-action="selectHour" style="display:none;">
										<table class="table-condensed" cellpadding="0" cellspacing="0">
											<tbody>
												<tr>
												<?php
												$counter = 0;
												for ( $i = $hr_start; $i < $hr_end_limit; $i++ ) {
													$i = ( $i < 10 ) ? "0$i" : $i;
													echo '<td class="hour">' . $i . '</td>';
													$counter++;
													if ( $counter % 4 == 0 ) {
														echo '</tr><tr>';
													}
												}
												?>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								<div id="pickupTimeHolderStep2" class="pickupTimeHolderStep2 min <?php echo $additional_class; ?>">
									<input type="text" name="selPtMn1" id="selPtMn1" readonly="readonly" class="form-control" value="<?php echo $pickup_min; ?>" placeholder="<?php esc_attr_e( 'MINS', 'cab-fare-calculator' ); ?>" />
									<div class="timepicker-minutes pickup-minutes" data-action="selectMinute" style="display:none;">
										<table class="table-condensed" cellpadding="0" cellspacing="0">
											<tbody>
												<tr>
												<?php
												$counter = 0;
												for ( $i = 0; $i <= 55; $i = $i + 5 ) {
													$i = ( $i < 10 ) ? "0$i" : $i;
													echo '<td class="minute">' . $i . '</td>';
													$counter++;
													if ( $counter % 4 == 0 ) {
														echo '</tr><tr>';
													}
												}
												?>
												</tr>
											</tbody>
										</table>
									</div>
								</div>
								
								<?php if ( $elsettings->time_format == '12hr' ) { ?>
								<div id="pickupTimeHolderStep3" class="pickupTimeHolderStep3 col-xs-4 col-md-4 ampm">
									<?php
									$optionsArr = array(
										'AM' => 'AM',
										'PM' => 'PM',
									);
									echo html_entity_decode( esc_html( SelectList::getSelectListHtml( 'seltimeformat1', 'styler_list', $optionsArr, $pickup_ampm ) ) );
									?>
								</div>
								<?php } ?>
							</div>
						</div>
					</div>
					<span class="error text-danger"></span>
				</div>
			</div>
			<div id="tabs_address" class="service-type-elems-wrapper">
				<div class="step1-inputWrap form-group form-group-lg pickup_wrap pac-container-wrapper">
					<label class="label-lg" id="pickuplocation"><?php esc_attr_e( 'TAXI_FROM', 'cab-fare-calculator' ); ?></label>
					<div class="userlocationOuter">
						<div class="userlocationWrap">
							<div class="getlocationField">
								<a rel="nofollow" href="javascript:void(0);" aria-label="pickup_direction" class="pickup_direction"><i class="tb tb-crosshairs" aria-hidden="true"></i></a>
							</div>
							<input type="text" class="form-control required" name="address_from" id="address_from" autocomplete="off" value="" placeholder="<?php esc_attr_e( 'ENTER_ADDRESS', 'cab-fare-calculator' ); ?>" />
							<span class="error"></span>
							<input type="hidden" name="address_from_lat" id="address_from_lat" value="" />
							<input type="hidden" name="address_from_lng" id="address_from_lng" value="" />
						</div>
					</div>
				</div>
				<div class="step1-inputWrap form-group form-group-lg dropoff_wrap pac-container-wrapper">
					<label class="label-lg" id="dropofflocation"><?php esc_attr_e( 'TAXI_TO', 'cab-fare-calculator' ); ?></label>
					<div class="userlocationOuter">
						<div class="userlocationWrap">
							<div class="getlocationField">
								<a rel="nofollow" href="javascript:void(0);" aria-label="dropoff_direction" class="dropoff_direction"><i class="tb tb-crosshairs" aria-hidden="true"></i></a>
							</div>
							<input type="text" class="form-control required" name="address_to" id="address_to" autocomplete="off" value="" placeholder="<?php esc_attr_e( 'ENTER_ADDRESS', 'cab-fare-calculator' ); ?>" />
							<input type="hidden" name="address_to_lat" id="address_to_lat" value="" />
							<input type="hidden" name="address_to_lng" id="address_to_lng" value="" />
						</div>
					</div>
				</div>
				<div class="step1-inputWrap form-group form-group-lg viewMapBtn" style="display: none">
					<input type="button" value="<?php esc_attr_e( 'VIEW_MAP_BTN_LBL', 'cab-fare-calculator' ); ?>" class="btn button-color viewMapTrigger" />
				</div>
			</div>
			<!-- comes from bottom part start -->
			<div class="form-group form-group-lg non-shuttle no-margin-bottom non-shared-rides seats-suitcase-block">
				<div class="row">
					<?php if ( (int) $elsettings->show_passengers_select == 1 ) { ?>
					<div class="step1-inputWrap-sm form-group form-group-lg col-xs-6 col-md-6">
						<label class="label-lg" id=""><?php esc_attr_e( 'PASSENGER_SEATS', 'cab-fare-calculator' ); ?></label>
						<div id="passengers-styler">
						<?php
						echo html_entity_decode( esc_html( SelectList::getSelectListHtml( 'passengers', 'styler_list seat_options required', $passenger_options, $elsettings->default_adult_seat ) ));
						?>
						</div>						
					</div>
					<?php } else { ?>
					<input type="hidden" name="passengers" value="<?php echo ( (int) $elsettings->default_adult_seat > 0 ) ? $elsettings->default_adult_seat : 1; ?>" />
					<?php } ?>
					
					<?php if ( (int) $elsettings->show_suitcase_select == 1 ) { ?>
					<div class="step1-inputWrap-sm form-group form-group-lg col-xs-6 col-md-6">
						<label class="label-lg" id=""><?php esc_attr_e( 'SUITCASE_NO', 'cab-fare-calculator' ); ?></label>
						<div id="suitcases-styler">
						<?php
						echo html_entity_decode( esc_html( SelectList::getSelectListHtml( 'suitcases', 'styler_list seat_options', $suitcases_options ) ) );
						?>
						</div>
					</div>
					<?php } ?>
				</div>
			</div>
			<?php if ( $has_chseats ) { ?>
			<div class="buttonWrap non-shuttle non-shared-rides">
				<a href="javascript:void(0);" class="child-seat-modal-trigger" data-bs-toggle="modal" data-bs-target="#childSeatsModal">
					<i class="tb tb-child"></i> <?php esc_attr_e( 'BOOKING_FORM_RIDING_CHILD_BTN_LABEL', 'cab-fare-calculator' ); ?>
				</a>
			</div>
			<?php } ?>
			<div class="childSeatsButtons-list" style="display: none;">
				<div class="childSeatsButtons">
					<span>
						<strong id="childSeatCaption"><?php esc_attr_e( 'BOOKING_FORM_ADDITIONAL_SEATS_HEADER', 'cab-fare-calculator' ); ?>:</strong> 
						<span id="ChildSeatsLabel">
						<span id="chSeatsLabel" style="">&nbsp;<?php esc_attr_e( 'CHILD_SEATS', 'cab-fare-calculator' ); ?> (<span id="chSeatsCount"></span>)</span>
						</span>
					</span>
					<div class="overlay-icon">
						<span>
						<a href="javascript:void(0);" class="btn-icon" title="Click to edit" id="edit_child_seats_btn"><i class="tb tb-edit"></i></a>
						</span>
					</div>
				</div>
			</div>

			<!-- comes from bottom part end -->
			<div class="limobooking-step1-bottom-area desktop-device">
				<div class="form-group non-shared-rides">
					<div id="step1Error" class="alert alert-danger" style="display: none;" role="alert"></div>
					<button class="btn btn-primary btn-lg btn-block see_price button-color" type="button"><?php esc_attr_e( 'SEE_PRICE', 'cab-fare-calculator' ); ?></button>
				</div>
			</div>
		</div>
		<?php if ( $elsettings->show_map_in_popup_only == 1 ) { ?>
		<div class="limobooking-step1-right-area col-sm-6 non-shared-rides" id="mapOff" style="visibility:hidden; width: 0px; height: 0px;">
			<div id="mapOuter" style="visibility:hidden;">
				<div class="mapInner">
					<div class="mapWrap" id="map-canvas-step1" style="height: 300px;"></div>
					<div id="map-close"><i class="tb tb-close"></i></div>
					<div class="estimatedDistanceInfo" style="display: none;">
						<span id="estimatedDistance"></span>
						<span id="estimatedDuration"></span>			
					</div>
				</div>
			</div>
		</div>
		<?php } else { ?>
		<div class="limobooking-step1-right-area col-sm-6 non-shared-rides no-popup" id="mapOff">
			<div class="mapOuter-nopopup" style="">
				<div class="mapWrap-nopopup" id="map-canvas-step1" style=""></div>
			</div>
			<span id="estimatedDistance" class="nopopup"></span>
			<span id="estimatedDuration" class="nopopup"></span>
		</div>
		<?php } ?>
	</div>
	<div class="limobooking-step1-bottom-area mobile-device col-sm-6 non-shared-rides">
		<div class="form-group">
			<div id="step1Error" class="alert alert-danger" style="display: none;" role="alert"></div>
			<button class="btn btn-primary btn-lg btn-block see_price button-color" type="button"><?php esc_attr_e( 'SEE_PRICE', 'cab-fare-calculator' ); ?></button>
		</div>
	</div>
</div>
<input type="hidden" id="page" name="page" value="1" />

<?php
if ( $has_chseats ) {
	?>
<div id="childSeatsModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-sm">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"><?php esc_attr_e( 'BOOKING_FORM_ADDITIONAL_SEATS_HEADER', 'cab-fare-calculator' ); ?></h4>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" style="background-color: transparent;">&nbsp;</button>
			</div>
			<div class="modal-body">
				<div class="seat_input_wrap childseats clearfix" style="margin-bottom: 10px;">
					<label style="display: block;"><?php esc_attr_e( 'CHILD_SEATS', 'cab-fare-calculator' ); ?></label>
					<?php
					echo html_entity_decode( esc_html( SelectList::getSelectListHtml( 'chseats', 'styler_list seat_options', $chseats_options ) ) );
					?>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default button-color" id="add_child_seats_btn">Add</button>
			</div>
		</div>
	</div>
</div>
<?php } ?>


<?php 
if($elsettings->pickup_dropoff_in_area == 1 || $elsettings->pickup_in_area == 1 || $elsettings->dropoff_in_area == 1)
{
?>
<div id="areaOperationModal" class="modal fade" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="btn-close" data-bs-dismiss="modal">&nbsp;</button>
			</div>
			<div class="modal-body">

				<div id="area_operation_wrapper">
					<div id="area_operation_dialog">
					<?php
					// Both Pick up and Drop off in Area = NO
					
					// 1) When Pick up in Area: Yes
					// and Drop off in Area: Yes
					// but Both Pick up and Drop off in Area: No
					// then only one, either Pick up or Drop off has to be in the Area of operation and they can both be in the area
					if($elsettings->pickup_dropoff_in_area==0 && $elsettings->pickup_in_area==1 && $elsettings->dropoff_in_area==1)
					{
					?>
					<div id="service_unavailable_msg"><?php esc_attr_e( "Sorry, we don't operate in this area. Either Pick up or Drop off has to be in the red zone of the map.", 'tblight' );?></div>
					<?php 
					}
					else {
						?>
					<div id="service_unavailable_msg"><?php esc_attr_e("We are sorry we don't operate in this area! Please select an address within the red zone of the map.", 'tblight');?></div>
					<?php } ?>
					<div id="map-canvas-operation"></div>
					</div>
				</div>

				<style type="text/css">
				#map-canvas-operation {
					min-height: 350px; 
					height: 350px; 
					width: 100%;	
				}
				#map-canvas-operation img {
					max-width: inherit !important;
				}
				#service_unavailable_msg {
					color: red;
					font-weight: bold;
					margin-bottom: 10px;
				}
				</style>
			</div>
		</div>
	</div>
</div>
<?php } ?>