<div id="limobooking-step2-container-area" class="limobooking-step2-container-area clearfix">
	<div class="tripSummary clearfix">
	<div class="trip-summary-info">
		<h3>
			<a rel="nofollow" href="javascript:void(0);" class="back_first">
			<span class="datetimeWrap">
			<span class="chevron-up"><i class="tb tb-chevron-up"></i></span>
			<span class="datetimesummary">Booking Summary:</span>
			&nbsp;<span class="date-time" style="display: inline;"></span>
			</span>
			<span class="distance"></span><span class="duration"></span>
			</a>
			<span class="btn btn-default edit back_first"><a rel="nofollow" href="javascript:void(0);"><i class="tb tb-pencil"></i></a></span>
		</h3>
		<div class="trip_status clearfix">
			<div class="trip_form">
				<span class="location-form-marker"><i class="tb tb-map-marker"></i></span>
				<div class="location-form"></div>
			</div>
			<div class="list-address-point" style="display:none;"></div>
			<div class="trip_to">
				<span class="location-to-marker"><i class="tb tb-map-marker"></i></span>
				<div class="location-to"></div>
			</div>
		</div>
		<div class="additional_seats_wrapper editable-field" style="display: none;">
			<strong><?php esc_attr_e( 'BOOKING_FORM_ADDITIONAL_SEATS_HEADER', 'cab-fare-calculator' ); ?>: </strong><span id="additional-seats" class="text-muted"></span>
			<!-- overlay-icon -->
			<div class="overlay-icon">
				<a class="btn-icon back_first" href="javascript:void(0);"><i class="tb tb-edit"></i></a>
			</div>
			<!-- /overlay-icon -->
		</div>
	</div>
	</div>
	
	<div class="vehicles-list clearfix">
	<div class="vehicles-header clearfix">
		<div class="row">
			<div class="col-sm-6">
				<h2 class="vehicles-title"><?php esc_attr_e( 'BOOKING_FORM_SECOND_STEP_LABEL', 'cab-fare-calculator' ); ?></h2>
			</div>
			<div class="col-sm-6">
				<!-- Sorting -->
				<div class="sort-outer">
					<div class="btn-toolbar pull-right" role="toolbar">   
					<div class="vm-options btn-group btn-group-sm" role="group" aria-label="sorting">
						<button type="button" class="btn btn-default list" title="<?php esc_attr_e( 'CARS_TABLE_LIST_BTN_LABEL', 'cab-fare-calculator' ); ?>">
						<span class="icon"><i class="tb tb-th-list"></i></span>
						<span class="sr-only"><?php esc_attr_e( 'CARS_TABLE_LIST_BTN_LABEL', 'cab-fare-calculator' ); ?></span>
						</button>
						<button type="button" class="btn btn-default active grid" title="<?php esc_attr_e( 'CARS_TABLE_GRID_BTN_LABEL', 'cab-fare-calculator' ); ?>">
						<span class="icon"><i class="tb tb-th"></i></span>
						<span class="sr-only"><?php esc_attr_e( 'CARS_TABLE_GRID_BTN_LABEL', 'cab-fare-calculator' ); ?></span>
						</button>
					</div>
					</div>
				</div>
				<!-- /Sorting -->
			</div>
		</div>
	</div>
	
	<div style="display:none;" id="warning_msg" class="alert alert-danger"></div>
	<div style="display:none;" class="msg_board alert alert-warning"></div>
	<input type="hidden" name="selected_car" id="selected_car" value="" />

	<div id="vehicle_wrapper"></div>
	
	</div>
	
	<a href="javascript:void(0);" class="btn btn-default btn-sm button-color back_first"><i class="tb tb-chevron-left"></i>&nbsp;<?php esc_attr_e( 'BACK_TO_PREVIOUS', 'cab-fare-calculator' ); ?></a>
</div>
