<div class="col-md-12 col-sm-12">
		<p class="login-info"><?php // esc_attr_e('BOOKING_FORM_LOGIN_ACCEESS_ACCOUNT'); ?></p>
	</div>
	<div id="limobooking-step3-container-area" class="row limobooking-step3-container-area clearfix">
		<div class="col-md-6 col-sm-6 col-right-border">
		<div class="col-max-width left step3-container-left">
			<div class="passenger-infoWrap clearfix">
			<h2 class="limobooking-step3-title"><?php esc_attr_e( 'BOOKING_FORM_PASSENGER_INFORMATION_HEADER', 'cab-fare-calculator' ); ?></h2>
			<div class="form-group pass-full-name clearfix">
				<label class="col-md-4 col-sm-4 control-label" for="pass-first-name"><?php esc_attr_e( 'NAME', 'cab-fare-calculator' ); ?> *</label>
				<div class="col-md-8 col-sm-8">
				<input type="text" name="name" id="pass-first-name" class="form-control required" value="" />
				</div>
			</div>
			<div class="form-group clearfix">
				<label class="col-md-4 col-sm-4 control-label" for="pass-email"><?php esc_attr_e( 'EMAIL', 'cab-fare-calculator' ); ?> *</label>
				<div class="col-md-8 col-sm-8">
				<input type="email" name="email" id="pass-email" class="form-control required email" value="" />
				</div>
			</div>
			<div class="form-group clearfix">
				<label class="col-md-4 col-sm-4 control-label" for="pass-phone-number"><?php esc_attr_e( 'PHONE', 'cab-fare-calculator' ); ?> *</label>
				<div class="col-md-8 col-sm-8 country_calling_code_wrap">
				<?php echo html_entity_decode( esc_html( SelectList::getCallingCodeOptions( 'country_calling_code', 'styler_list', $elsettings->default_country, '' ) ) ); ?>
				<input type="tel" name="phone" id="pass-phone-number" class="form-control required phone" value="" />
				<input type="hidden" name="country_id" id="country_id" value="0" />
				</div>
			</div>
			
			</div>
			
			<div class="payment-infoWrap clearfix">
			<h2 class="limobooking-step3-title"><?php esc_attr_e( 'Payment Method', 'cab-fare-calculator' ); ?></h2>
			<div class="form-group">
			<!--label for="PaymentTypes" class="col-md-4 control-label"><?php // esc_attr_e('BOOKING_FORM_STEP2_PAYMENT_TEXT'); ?></label-->
			<div class="col-md-12">
				<div id="payment_selectors"></div>
				<span class="error text-danger" style="display:none;"><?php esc_attr_e( 'Please select a payment method to continue.', 'cab-fare-calculator' ); ?></span>
			</div>
			</div>
		</div>
		</div>
	</div>
		
	<div class="col-md-6 col-sm-6 col-left-border">
		<div class="col-max-width right">
		<div class="tripsummaryWrap clearfix">
			<h2 class="limobooking-step3-title"><?php esc_attr_e( 'ONEPAGE_BOOKING_FOURTH_STEP_HEADER', 'cab-fare-calculator' ); ?></h2>
			<!-- trip-details-row -->
			<div class="row trip-details-row">
			<div class="col-md-8 col-sm-8 col-right-border">
				<!-- trip-details -->
				<dl class="trip-details address">
					<dd class="text-primary">
					<div class="editable-field">
						<span class="date-time"></span>
						<!-- overlay-icon -->
						<div class="overlay-icon">
							<a title="Click to edit" class="btn-icon back_first" href="javascript:void(0);"><i class="tb tb-pencil"></i></a>
						</div>
						<!-- /overlay-icon -->
					</div>
					</dd>
					
					<dt><?php esc_attr_e( 'TAXI_FROM', 'cab-fare-calculator' ); ?>:</dt>
					<dd>
					<div class="editable-field">
						<span data-title="Edit pick-up address" class="editable pickup"></span>
						<!-- overlay-icon -->
						<div class="overlay-icon">
							<a title="Click to edit" class="btn-icon btn-edit back_first" href="javascript:void(0);"><i class="tb tb-pencil"></i></a>
						</div>
						<!-- /overlay-icon -->
					</div>
					</dd>
					
					<dt><?php esc_attr_e( 'TAXI_TO', 'cab-fare-calculator' ); ?>:</dt>
					<dd>
					<div class="editable-field">
						<span data-title="Edit drop-off address" class="editable dropoff"></span>
						<!-- overlay-icon -->
						<div class="overlay-icon">
						<a title="Click to edit" class="btn-icon btn-edit back_first" href="javascript:void(0);"><i class="tb tb-pencil"></i></a>
						</div>
						<!-- /overlay-icon -->
					</div>
					</dd>
				</dl>
				<!-- /trip-details -->
			</div>			
			<div class="col-md-4 col-sm-4 col-left-border">
				<!-- extra-details -->
				<ul class="extra-details">
				<li>
					<div class="editable-field">
					<i class="tb tb-male"></i>&nbsp;
					<span class="pass-number"></span>
					<!-- overlay-icon -->
					<div class="overlay-icon">
						<a title="Click to edit" class="btn-icon back_first" href="javascript:void(0);"><i class="tb tb-pencil"></i></a>
					</div>
					<!-- /overlay-icon -->
					</div>
				</li>
				
				<?php if ( (int) $elsettings->show_suitcase_select == 1 ) { ?>
				<li>
					<div class="editable-field">
					<i class="tb tb-briefcase"></i>&nbsp;
					<span><?php esc_attr_e( 'SUITCASE_NO', 'cab-fare-calculator' ); ?> (<span class="suitcases">0</span>)</span>
					<!-- overlay-icon -->
					<div class="overlay-icon">
						<a title="Click to edit" class="btn-icon back_first" href="javascript:void(0);"><i class="tb tb-pencil"></i></a>
					</div>
					<!-- /overlay-icon -->
					</div>
				</li>
				<?php } ?>
				
				<li>
					<div class="editable-field">
					<i class="tb tb-child"></i>&nbsp;
					<span class="text-muted"><?php esc_attr_e( 'BOOKING_FORM_ADDITIONAL_SEATS_HEADER', 'cab-fare-calculator' ); ?> (<span class="child-seats">0</span>)</span>
					<!-- overlay-icon -->
					<div class="overlay-icon">
						<a title="Click to edit" class="btn-icon back_first" href="javascript:void(0);"><i class="tb tb-pencil"></i></a>
					</div>
					<!-- /overlay-icon -->
					</div>
				</li>
				<li>
					<div class="editable-field">
					<i class="tb tb-male"></i>&nbsp;
					<span><?php esc_attr_e( 'Total Seats', 'cab-fare-calculator' ); ?> (<span class="total-passenger">0</span>)</span>
					<!-- overlay-icon -->
					<div class="overlay-icon">
						<a title="Click to edit" class="btn-icon back_first" href="javascript:void(0);"><i class="tb tb-pencil"></i></a>
					</div>
					<!-- /overlay-icon -->
					</div>
				</li>
				</ul>
				<!-- /extra-details -->
				
				<!-- car-details -->
				<div class="car-details clearfix">
				<div class="editable-field editable-vehicle-field">
					<div class="capacity">
					<div class="title">
						<span class="vehicle-type-label"></span>
						<!-- overlay-icon -->
						<div class="overlay-icon">
						<a title="Click to edit" class="btn-icon back_second" href="javascript:void(0);"><i class="tb tb-pencil"></i></a>
						</div>
						<!-- /overlay-icon -->
					</div>
					<span class="text-muted">
						<?php esc_attr_e( 'Capacity', 'cab-fare-calculator' ); ?>: <span class="vehicle-type-pass-capacity"></span>
						<br>
						<?php esc_attr_e( 'Bags', 'cab-fare-calculator' ); ?>: <span class="vehicle-type-luggage-capacity"></span>
					</span>
					</div>
					<img class="img-responsive car-preview vehicle-type-img" src="" />
				</div>
				</div>
				<!-- /car-details -->
			</div>			

			</div> 
			<!-- /trip-details-row -->
		</div>
		
		</div>
	</div>
</div>
<a href="javascript:void(0);" class="btn btn-default btn-sm button-color back_second"><i class="tb tb-chevron-left"></i>&nbsp;<?php esc_attr_e( 'BACK_TO_PREVIOUS', 'cab-fare-calculator' ); ?></a>
