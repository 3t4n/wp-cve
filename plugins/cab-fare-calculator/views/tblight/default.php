<?php
$highlightscolor = ( ! empty( $elsettings->highlights_color ) ) ? $elsettings->highlights_color : '#1aa1bf';
?>
<style type="text/css">
#limobooking-header-area {
	background: <?php echo esc_attr( $highlightscolor ); ?> !important;
}
#limobooking-steps-area .step-number-wrap.active .step-number .step-number-inner {
	background: <?php echo esc_attr( $highlightscolor ); ?>;
}
#limobooking-steps-area .step-number-wrap.active .step-text {
	color: <?php echo esc_attr( $highlightscolor ); ?>;
}
#limobooking-step2-wrapper .vehicles-list .vehicles-header {
	background: <?php echo esc_attr( $highlightscolor ); ?> !important;
}
.vehicles-body.grid-view .vehicles-grid .vehicles-item-i:hover .vehicles-item-box {
	border : 3px solid <?php echo esc_attr( $highlightscolor ); ?>;
}
.datepicker table td.active, 
.datepicker table td.active:hover {
	background-color: <?php echo esc_attr( $highlightscolor ); ?>;
}
.timepicker .btn-timepicker-close {
background-color: <?php echo esc_attr( $highlightscolor ); ?> !important;
}
.timepicker span.fa-chevron-down,
.timepicker span.fa-chevron-up {
	color: <?php echo esc_attr( $highlightscolor ); ?> !important;;
}
.poi_dropdown_wrapper .poi_link:hover {
	background-color: <?php echo esc_attr( $highlightscolor ); ?>;
}
.button-color,
a.button-color,
.child-seat-modal-trigger {
background: <?php echo esc_attr( $highlightscolor ); ?> !important;
}
.button-color:hover {
background: <?php echo esc_attr( $highlightscolor ); ?> !important;
}
#bottomFloatingBar .floatingButton {
	background: <?php echo esc_attr( $highlightscolor ); ?>;
}
#bottomFloatingBar .floatingPopup-section-two span {
	color: <?php echo esc_attr( $highlightscolor ); ?>;
}
.chosen-container .chosen-results li.highlighted {
	background: <?php echo esc_attr( $highlightscolor ); ?> !important;
}
.thankyou-styles {
	background: <?php echo esc_attr( $highlightscolor ); ?> !important;
}
.timepicker-hours table.table-condensed tr td.hour:hover,
.timepicker-hours table.table-condensed tr td.hour:hover,
.timepicker-minutes table.table-condensed tr td.minute:hover,
.timepicker-minutes table.table-condensed tr td.minute:hover {  
	background: <?php echo esc_attr( $highlightscolor ); ?> !important;
	color: #fff !important;
}
input[type="text"]:focus, 
input[type="email"]:focus, 
input[type="url"]:focus, 
input[type="password"]:focus, 
input[type="number"]:focus, 
input[type="tel"]:focus, 
input[type="range"]:focus, 
input[type="date"]:focus, 
input[type="month"]:focus, 
input[type="week"]:focus, 
input[type="time"]:focus, 
input[type="datetime"]:focus, 
input[type="datetime-local"]:focus, 
input[type="color"]:focus, 
textarea:focus,
#pickupTimeHolderStep3 .chosen-container-active.chosen-with-drop .chosen-single, 
#passengers-styler .chosen-container-active.chosen-with-drop .chosen-single, 
#suitcases-styler .chosen-container-active.chosen-with-drop .chosen-single,
.childseats .chosen-container-active.chosen-with-drop .chosen-single,
.country_calling_code_wrap .chosen-container-active.chosen-with-drop .chosen-single {
	border-color: <?php echo esc_attr( $highlightscolor ); ?> !important;
}
[type="radio"]:checked + label:after,
[type="radio"].with-gap:checked + label:before,
[type="radio"].with-gap:checked + label:after {
  border: 2px solid <?php echo esc_attr( $highlightscolor ); ?> !important;
}

[type="radio"]:checked + label:after,
[type="radio"].with-gap:checked + label:after {
  background-color: <?php echo esc_attr( $highlightscolor ); ?> !important;
}
[type="checkbox"]:checked + label:before {
  top: -4px;
  left: -5px;
  width: 12px;
  height: 22px;
  border-top: 2px solid transparent;
  border-left: 2px solid transparent;
  border-right: 2px solid <?php echo esc_attr( $highlightscolor ); ?> !important;
  border-bottom: 2px solid <?php echo esc_attr( $highlightscolor ); ?> !important;
  transform: rotate(40deg);
  backface-visibility: hidden;
  transform-origin: 100% 100%;
}
</style>

<div id="tbNewStyleWrapper" style="position: relative;">
	<div id="loadingProgressContainer" style="display: none;">
		<div id="loadingProgressElement">
			<img src="<?php echo TBLIGHT_PLUGIN_DIR_URL; ?>assets/images/ajax-loader-bar.gif" alt="Loading.." />
		</div>
	</div>
	<div id="limobooking-header-area" class="limobooking-header-area clearfix">
		<a href="javascript:void(0);" class="reset-booking-form"><i class="tb tb-tags"></i> <?php esc_attr_e( 'BOOKING_FORM_NEW_ORDER_BTN_LABEL', 'cab-fare-calculator' ); ?></a>
	</div>
	<div id="limobooking-steps-area-outer">
		<div id="limobooking-steps-area" class="limobooking-steps-area clearfix">
			<div class="step-number-wrap first active">
				<div class="step-number">
					<div class="step-number-inner">1</div>
				</div>
				<div class="step-text"><?php esc_attr_e( 'BOOKING_FORM_FIRST_STEP_LABEL', 'cab-fare-calculator' ); ?></div>
			</div>
			<div class="step-number-wrap second">
				<div class="step-number">
					<div class="step-number-inner">2</div>
				</div>
				<div class="step-text"><?php esc_attr_e( 'BOOKING_FORM_SECOND_STEP_LABEL', 'cab-fare-calculator' ); ?></div>
			</div>
			<div class="step-number-wrap third">
				<div class="step-number">
					<div class="step-number-inner">3</div>
				</div>
				<div class="step-text"><?php esc_attr_e( 'BOOKING_FORM_THIRD_STEP_LABEL', 'cab-fare-calculator' ); ?></div>
			</div>
		</div>
	</div>
	<form name="price_form" id="price_form" method="POST" action="index.php" class="clearfix">
		<div id="limobooking-step1-wrapper">
			<?php require_once TBLIGHT_PLUGIN_PATH . 'views/tblight/default_step1.php'; ?>
		</div>
		<div id="limobooking-step2-wrapper" style="display:none;">
			<?php require_once TBLIGHT_PLUGIN_PATH . 'views/tblight/default_step2.php'; ?>
		</div>
		<div id="limobooking-step3-wrapper" style="display:none;">
			<?php require_once TBLIGHT_PLUGIN_PATH . 'views/tblight/default_step3.php'; ?>
		</div>
		
		<div id="bottomFloatingBar" style="display:none;">
			<div class="floatingButton">
					<?php esc_attr_e( 'Grand Total', 'cab-fare-calculator' ); ?>: <span class="grand_total"></span>
					<br/>(<?php esc_attr_e( 'Click here to Finish', 'cab-fare-calculator' ); ?>)
			</div>	
				<div class="floatingPopup">
					<div class="floating-close">
						<a href="javascript:void(0);" class="btn button-color">
							<i class="tb tb-close"></i>
						</a>
					</div>				
				<div class="floatingPopup-section-one">
					<table cellspacing="0" cellpadding="0" border="0" style="width: 100%; margin: 0px;">
					<tr class="sub_total_wrap" style="display:none;">
						<td><?php esc_attr_e( 'Sub Total', 'cab-fare-calculator' ); ?>:</td>
						<td class="sub_total"></td>
					</tr>
					
					<tr class="flat_cost_wrap" style="display:none;">
						<td><?php esc_attr_e( 'Payment processor Fee', 'cab-fare-calculator' ); ?>:</td>
						<td class="flat_cost"></td>
					</tr>
					<tr class="percentage_cost_wrap" style="display:none;">
						<td><?php esc_attr_e( 'Tax', 'cab-fare-calculator' ); ?>:</td>
						<td class="percentage_cost"></td>
					</tr>
					</table>			
				</div>
				<div class="floatingPopup-section-two grand_total_wrap">
					<?php esc_attr_e( 'Grand Total', 'cab-fare-calculator' ); ?>: <span class="grand_total"></span>
				</div>
				<div class="floatingPopup-section-three">
					<div class="tandc">
					<?php if ( (int) $elsettings->use_terms == 1 ) { ?>
					<input type="checkbox" id="cbox" name="cbox" class="checkb0" value="1" />
					<label for="cbox" class="check">
						<?php esc_attr_e( 'Please check the box to accept', 'cab-fare-calculator' ); ?>&nbsp;
						<a href="javascript:void(0);" class="" data-toggle="modal" data-target="#termsModal"><?php esc_attr_e( 'Terms & Conditions', 'cab-fare-calculator' ); ?></a>
					</label>
					<div id="termsError" class="alert alert-danger" style="display: none;" role="alert"><?php esc_attr_e( 'Please agree to our Terms and Conditions before you proceed', 'cab-fare-calculator' ); ?></div>
					<?php } ?>
					
						<?php if ( (int) $elsettings->enable_captcha == 1 && ! empty( $elsettings->recaptcha_key ) ) { ?>
						<div class="captchaContent">
							<div class="g-recaptcha" data-sitekey="<?php echo esc_attr( $elsettings->recaptcha_key ); ?>" data-callback="getCaptchaResponse" style="transform:scale(0.9);transform-origin:0;-webkit-transform:scale(0.9); transform:scale(0.9);-webkit-transform-origin:0 0;transform-origin:0 0;"></div>
							<div class="messageContent"></div>
							
							<div class="g-recaptcha-response-error alert alert-error" style="display:none;">
								<?php esc_attr_e( 'Please prove that you are not a robot!', 'cab-fare-calculator' ); ?>
							</div>
							<input type="hidden" name="booking-g-recaptcha-response" id="booking-g-recaptcha-response" value="" />
						</div>
						<?php } ?>
					</div>
					
					<div id="step3Error" class="alert alert-danger" style="display: none;" role="alert"><?php esc_attr_e( 'Oops! Please review the errors in red and try again.', 'cab-fare-calculator' ); ?></div>
					<input type="button" class="btn button-color" name="make_booking" id="make_booking" value="<?php esc_attr_e( 'Make a Booking', 'cab-fare-calculator' ); ?>" />
				</div>
				</div>	    
			</div>

			<input type="hidden" name="booking_form_url" value="<?php echo esc_url( $booking_form_url ); ?>" />
	</form>
</div>

<script type="text/javascript">
var clientIP = '<?php echo esc_attr( $_SERVER['REMOTE_ADDR'] ); ?>';
var selectedAreaVerticesStr = '<?php echo esc_attr( $elsettings->operation_area_vertices ); ?>';
var triangleCoords = new Array();
var selectedAreaVerticesArr = JSON.parse(selectedAreaVerticesStr);
var areaOfOperation, tbxhr;
var stripeAPIloaded = false;
var TBFSettings = new function ()
{
	<?php if ( (int) $elsettings->show_map_in_popup_only == 1 ) { ?>
	this.showMapInPopupOnly = true;
	<?php } else { ?>
	this.showMapInPopupOnly = false;
	<?php } ?>
	<?php if ( (int) $elsettings->show_map_on_mobile == 1 ) { ?>
	this.showMapOnMobile = true;
	<?php } else { ?>
	this.showMapOnMobile = false;
	<?php } ?>
	<?php if ( (int) $elsettings->show_map_on_desktop == 1 ) { ?>
	this.showMapOnDesktop = true;
	<?php } else { ?>
	this.showMapOnDesktop = false;
	<?php } ?>
	this.addressBookingEnabled = true;
	this.stopsEnabled = false;
	this.defaultCity = "<?php echo esc_attr( $elsettings->default_city ); ?>";
	this.defaultCountry = "<?php echo esc_attr( $default_country ); ?>";
	this.defaultMapLat = '<?php echo ( ! empty( $elsettings->company_location_lat ) ) ? esc_attr( $elsettings->company_location_lat ) : 51.507351; ?>';
	this.defaultMapLng = '<?php echo ( ! empty( $elsettings->company_location_lng ) ) ? esc_attr( $elsettings->company_location_lng ) : -0.127758; ?>';
	this.optimizeStops  = false;
	<?php if ( $elsettings->distance_unit == 'km' ) { ?>
	this.distanceUnit = 'km';
	<?php } else { ?>
	this.distanceUnit = 'mile';
	<?php } ?>
	<?php if ( $elsettings->date_format == 'dd-mm-yy' ) { ?>
	this.DatePickerFormat = "DD-MM-YYYY";
	<?php } else { ?>
	this.DatePickerFormat = "MM-DD-YYYY";
	<?php } ?>
	<?php if ( $elsettings->time_format == '24hr' ) { ?>
	this.Is12HoursTimeFormat = false;
	<?php } else { ?>
	this.Is12HoursTimeFormat = true;
	<?php } ?>
	this.TimePickerFormat = "HH:mm";
	this.checkPickupAreaOperation = <?php echo (int) $elsettings->pickup_in_area; ?>;
	this.checkDropoffAreaOperation = <?php echo (int) $elsettings->dropoff_in_area; ?>;
	this.checkPickupDropoffAreaOperation = <?php echo (int) $elsettings->pickup_dropoff_in_area; ?>;
	this.orderCreationHoursLimit = <?php echo (float) $elsettings->restrict_time; ?>;
	this.TimeZoneUtcOffset = <?php echo esc_attr( $offset ); ?>;
	<?php if ( isset( $elsettings->frontend_cars_default_display ) ) { ?>
	this.carsDefaultDisplay = "<?php echo esc_attr( $elsettings->frontend_cars_default_display ); ?>";
	<?php } else { ?>
	this.carsDefaultDisplay = "grid";
	<?php } ?>
	
	<?php
	if ( ! empty( $elsettings->gmap_api_avoids ) ) {
		if ( in_array( 'ferries', $elsettings->gmap_api_avoids ) ) {
			?>
	this.GAPIavoidFerries = true;
		<?php } else { ?>
	this.GAPIavoidFerries = false;
			<?php
		}
		if ( in_array( 'highways', $elsettings->gmap_api_avoids ) ) {
			?>
	this.GAPIavoidHighways = true;
		<?php } else { ?>
	this.GAPIavoidHighways = false;
			<?php
		}
		if ( in_array( 'tolls', $elsettings->gmap_api_avoids ) ) {
			?>
	this.GAPIavoidTolls = true;
		<?php } else { ?>
	this.GAPIavoidTolls = false;
			<?php
		}
	} else {
		?>
	this.GAPIavoidFerries = false;
	this.GAPIavoidHighways = false;
	this.GAPIavoidTolls = false;
	<?php } ?>
	this.datePickerType = "jquery";
	<?php if ( (int) $elsettings->enable_captcha == 1 ) { ?>
	this.captchaEnabled = true;
	<?php } else { ?>
	this.captchaEnabled = false;
	<?php } ?>
};

var TBTranslations = new function ()
{
	this.BOOKING_FORM_FIRST_STEP_ERROR_MESSAGE = "Oops! Please review the errors in red and try again.";
	this.ERR_MESSAGE_FIELD_REQUIRED = "This field is required";
	this.ERR_MESSAGE_VALID_EMAIL = "Please enter valid email address";
	this.ERR_MESSAGE_VALID_PHONE = "Please enter valid phone";
	this.ERR_MESSAGE_VALID_NUMERIC = "Please enter Numeric Non-zero value only";
	this.ERR_TERMS_CONDITIONS_REQUIRED = "Please agree to our Terms and Conditions before you proceed";
	this.ERR_MESSAGE_FIELD_SLASH_NOT_ALLOWED = "Only Forward slash ( / ) is not allowed in this field";
	this.BOOKING_FORM_ESTIMATED_DISTANCE_LBL = "Estimated Distance";
	this.BOOKING_FORM_DISTANCE_UNIT_MILES_LABEL = "miles";
	this.BOOKING_FORM_DISTANCE_UNIT_KM_LABEL = "kilometers";
	this.ERR_MESSAGE_ESTIMATED_DISTANCE_CALCULATE = "We could not to calculate the distance of the route.";
	this.ERR_MESSAGE_INSUFFICIENT_BOOKING_TIME = "<?php echo sprintf( 'Booking is too soon, please allow %s hours before your time of Departure.', esc_attr( $elsettings->restrict_time ) ); ?>";
	this.ERR_MESSAGE_DATETIME_IN_PAST = "Date/Time in the past!";
	this.ERR_ENTER_SECURITY_CODE = "Please enter security code.";
	this.BOOKING_FORM_ESTIMATED_DURATION_LBL = "Estimated Duration";
	this.CAR_LIST_ESTIMATED_TIME_HR = "%s hrs ";
	this.CAR_LIST_ESTIMATED_TIME_MIN = "%s mins";
};

function getCaptchaResponse(response) {
jQuery('#booking-g-recaptcha-response').val(response);
} 
</script>
