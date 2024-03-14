// detect mobile devices that support touch screen feature
// including windows mobile devices
var isTouchSupported = 'ontouchstart' in window,
    isTouchSupportedIE10 = navigator.userAgent.match(/Touch/i) != null;

jQuery(document).ready(function(){

	var deviceWidth = jQuery(window).width();

    jQuery(function() {
	jQuery('select.styler_list').chosen({
		disable_search_threshold: 10
	});
	jQuery("#childSeatsModal label").css({
		display: "block"
	});	
	jQuery("#childSeatsModal .jq-selectbox").css({
		width: "100%"
	});
	jQuery("#stopsModal label").css({
		display: "block"
	});		
    });

	jQuery('.floatingButton').click(function() {
		jQuery(this).siblings('div.floatingPopup').addClass('active');
	});
	jQuery('.floating-close').click(function(){
		jQuery(this).parent('.floatingPopup').removeClass('active');
	});    
    
    GoogleGeoCore.Init();
    
    TBFEngine.OnReady();

    // hide map on mobile if settings show_map_on_mobile = NO
    if(!TBFSettings.showMapOnMobile && deviceWidth < 768) {
	jQuery(".limobooking-step1-right-area").hide();
    }
    if (TBFSettings.showMapOnDesktop == false && deviceWidth > 768) {
	jQuery('.limobooking-step1-left-area').addClass('div-center-aligned');
    }      
    
    // show/hide list on clicking Arrow
    jQuery('.list_trigger').click(function(){
	if (jQuery(this).prev('.list_wrapper').is(":visible") == true){
	    jQuery(this).prev('.list_wrapper').hide();
	}
	else {
	    jQuery(this).prev('.list_wrapper').show();
	}        
    })
    
    // hide all the dropdown if clicked outside of the arrow
    var mouseOverList = mouseOverPickupHr = mouseOverPickupMin = mouseOverDropoffHr = mouseOverDropoffMin = false;
    jQuery('.list_trigger').mouseenter(function(){
        mouseOverList = true; 
    }).mouseleave(function(){ 
        mouseOverList = false; 
    })
    jQuery('input#selPtHr1').mouseenter(function(){
	mouseOverPickupHr = true; 
    }).mouseleave(function(){ 
	mouseOverPickupHr = false; 
    })
    jQuery('input#selPtMn1').mouseenter(function(){
	mouseOverPickupMin = true; 
    }).mouseleave(function(){ 
	mouseOverPickupMin = false; 
    })
    jQuery('input#dropoff_selPtHr').mouseenter(function(){
	mouseOverDropoffHr = true; 
    }).mouseleave(function(){ 
	mouseOverDropoffHr = false; 
    })
    jQuery('input#dropoff_selPtMn').mouseenter(function(){
	mouseOverDropoffMin = true; 
    }).mouseleave(function(){ 
	mouseOverDropoffMin = false; 
    })
    jQuery("html").click(function(){
        if (!mouseOverList) {
            jQuery('.list_trigger').prev('.list_wrapper').hide();
        }
	if (!mouseOverPickupHr) {
            jQuery('input#selPtHr1').next('.timepicker-hours').hide();
        }
	if (!mouseOverPickupMin) {
            jQuery('input#selPtMn1').next('.timepicker-minutes').hide();
        }
	if (!mouseOverDropoffHr) {
            jQuery('input#dropoff_selPtHr').next('.timepicker-hours').hide();
        }
	if (!mouseOverDropoffMin) {
            jQuery('input#dropoff_selPtMn').next('.timepicker-minutes').hide();
        }
    });
    jQuery('#limobooking-steps-area .step-number-wrap.second').click(function(){
	if(jQuery('#limobooking-steps-area .step-number-wrap.third').hasClass('active')){
	    TBFEngine.makeSecondStepActive();
	}
    })
    jQuery('#limobooking-steps-area .step-number-wrap.first').click(function(){
	if(jQuery('#limobooking-steps-area .step-number-wrap.third').hasClass('active')
	   || jQuery('#limobooking-steps-area .step-number-wrap.second').hasClass('active')
	){
	    TBFEngine.makeFirstStepActive();
	}
    })
    var changeCount = 0;
    jQuery('.service_type').change(function(){
	var selected_type = jQuery(this).val();
	jQuery('.service-type-elems-wrapper').hide();
	jQuery('#tabs_'+selected_type).show();
	jQuery('div#step1Error').hide();
	jQuery('.pickup-date-time-label').show();
		
	var hide_return = false;
	jQuery('.non-shared-rides').show();
	
	    jQuery('.non-shuttle').show();
	    if(selected_type=="address"){
		jQuery('#stops_modal_trigger').show();
	    }
	    else {
		TBFEngine.clearStopPoints();
	    }
    })
    jQuery("#address_from").change(function(){
	jQuery('#address_from_lat,#address_from_lng').val("");
    });
    jQuery("#address_to").change(function(){
	jQuery('#address_to_lat,#address_to_lng').val("");
    });
    jQuery('a.pickup_direction').click(function(){
	GoogleGeoCore.getUserLocation('address_from');
    })
    jQuery('a.dropoff_direction').click(function(){
	GoogleGeoCore.getUserLocation('address_to');
    })

    jQuery('.viewMapTrigger').click(function(){
	GoogleGeoCore.RenderDirections();
	jQuery('#mapOff').css({'visibility':'visible', 'width':'auto', 'height':'auto', 'display':'block'});
	jQuery('#mapOuter').addClass('popped-up').css({'visibility':'visible'});
	jQuery('.estimatedDistanceInfo').show();
    })
    jQuery('#map-close').click(function(){
    jQuery('#mapOff').css({'visibility':'hidden', 'width':'0px', 'height':'0px', 'display':'none'});
	jQuery('#mapOuter').removeClass('popped-up').css({'visibility':'hidden'});
	jQuery('.estimatedDistanceInfo').hide();
	jQuery('#estimatedDistance,#estimatedDuration').html('');
    })  

    jQuery("#add_child_seats_btn").click(function(){
	var chseats = jQuery('#chseats').val();
	if(chseats>0){
	   jQuery('.childSeatsButtons-list').show();
	}
	//console.log('chseats: '+chseats);
	if(chseats>0){
	    jQuery('#ChildSeatsLabel #chSeatsLabel').show();
	    jQuery('#ChildSeatsLabel #chSeatsCount').html(chseats);
	}
	else {
	    jQuery('#ChildSeatsLabel #chSeatsCount').html(0);
	    jQuery('#ChildSeatsLabel #chSeatsLabel').hide();
	}
	jQuery('#childSeatsModal').modal("hide");
    });
    jQuery('#edit_child_seats_btn').click(function(){
	jQuery('#childSeatsModal').modal("show");
    })
    
    jQuery(".see_price").click(function(){
        var booking_type = jQuery('#booking_type').val();
        // first validate the required field
        var errorsCount = 0;
	
	if(booking_type=='address')
	{
	    if(TBFSettings.datePickerType=='jquery')
	    {
		var pickupDateStep1 = jQuery('#pickupDateStep1').val();
		if(pickupDateStep1==""){
		    errorsCount++;
		    jQuery("#pickupDateStep1").closest('div.step1-sm-inputWrap').addClass('has-error');
		    //console.log('pickup date error');
		}
		else {
		    jQuery("#pickupDateStep1").closest('div.step1-sm-inputWrap').removeClass('has-error');
		}
		var selPtHr1 = jQuery('#selPtHr1').val();
		if(selPtHr1==""){
		    errorsCount++;
		    jQuery("#selPtHr1").closest('div.step1-sm-inputWrap').addClass('has-error');
		}
		else {
		    jQuery("#selPtHr1").closest('div.step1-sm-inputWrap').removeClass('has-error');
		}
		var selPtMn1 = jQuery('#selPtMn1').val();
		if(selPtMn1==""){
		    errorsCount++;
		    jQuery("#selPtMn1").closest('div.step1-sm-inputWrap').addClass('has-error');
		}
		else {
		    jQuery("#selPtMn1").closest('div.step1-sm-inputWrap').removeClass('has-error');
		}
	    }
	    else if(TBFSettings.datePickerType=='inline')
	    {
		var selPtHr1 = jQuery('#selPtHr1').val();
		if(selPtHr1==""){
		    errorsCount++;
		    jQuery('div#selPtHr1-styler').addClass('has-error');
		}
		else {
		    jQuery('div#selPtHr1-styler').removeClass('has-error');
		}
		var selPtMn1 = jQuery('#selPtMn1').val();
		if(selPtMn1==""){
		    errorsCount++;
		    jQuery('div#selPtMn1-styler').addClass('has-error');
		}
		else {
		    jQuery('div#selPtMn1-styler').removeClass('has-error');
		}
	    }
	    // pickup time validation
	    if(selPtHr1!=""&&selPtMn1!="")
	    {
		var errorDescription = TBFEngine.ValidatePuDateTimeRestriction();
		if(TBFSettings.datePickerType=='jquery')
		{
		    if(errorDescription!=""){
			errorsCount++;
			jQuery(".pickupTime .time-date-wrapper").find('span.error').remove();
			jQuery("#selPtHr1").closest('div.step1-sm-inputWrap').addClass('has-error');
			jQuery(".pickupTime .time-date-wrapper").append('<span class="error text-danger">'+errorDescription+'</span>');
		    }
		    else {
			jQuery(".pickupTime .time-date-wrapper").find('span.error').remove();
			jQuery("#selPtHr1").closest('div.step1-sm-inputWrap').removeClass('has-error');
		    }
		}
		else if(TBFSettings.datePickerType=='inline')
		{
		    if(errorDescription!=""){
			errorsCount++;
			jQuery("#pickup_day").closest("div.pickupDate").find('span.error').remove();
			jQuery("#pickup_day").closest("div.pickupDate").addClass('has-error');
			jQuery("#pickup_day").closest("div.pickupDate").append('<span class="error text-danger">'+errorDescription+'</span>');
		    }
		    else {
			jQuery("#pickup_day").closest("div.pickupDate").find('span.error').remove();
			jQuery("#pickup_day").closest("div.pickupDate").removeClass('has-error');
		    }
		}
	    }
	}
	// address field validation if user choose address radio
	jQuery("#address_from").closest('div.step1-inputWrap').removeClass('has-error');
	jQuery("#address_to").closest('div.step1-inputWrap').removeClass('has-error');
	if(booking_type=='address')
	{
	    var address_from_lat = jQuery('#address_from_lat').val();
	    var address_from_lng = jQuery('#address_from_lng').val();
	    if(address_from_lat=="" || address_from_lng==""){
		errorsCount++;
		jQuery("#address_from").closest('div.step1-inputWrap').addClass('has-error');
		//console.log('address form error');
	    }
	    var address_to_lat = jQuery('#address_to_lat').val();
	    var address_to_lng = jQuery('#address_to_lng').val();
	    if(address_to_lat=="" || address_to_lng==""){
		errorsCount++;
		jQuery("#address_to").closest('div.step1-inputWrap').addClass('has-error');
		//console.log('address to error');
	    }
	}
	// other required field validation
	if(jQuery('#passengers').val()==0){
	    errorsCount++;
	    jQuery('#passengers').closest('div.step1-inputWrap-sm').addClass('has-error');
	    //console.log('passenger error');
	}
	else {
	    jQuery('#passengers').closest('div.step1-inputWrap-sm').removeClass('has-error');
	}
	
	if(errorsCount>0){
	    jQuery('div#step1Error').show().html(TBTranslations.BOOKING_FORM_FIRST_STEP_ERROR_MESSAGE);
	}
	else {
	    jQuery('div#step1Error').hide();
	    // reset page to 1 to get the first 8 cars
	    jQuery('#page').val(1);
            TBFEngine.getMaps(true);  // show_cars = TRUE will show 2nd step cars table, FALSE will show 3rd step
	}
    })
    jQuery(document).on("click", '#show_all_cars', function (e) {
	jQuery('#cartype').chosen('destroy').val(0).chosen();
	TBFEngine.getMaps(true);
    })
    jQuery('div.vehicles-list').on("click", '.list', function (e) {
	jQuery('.grid').removeClass('active');
	jQuery('.list').addClass('active');
	jQuery('div.list-view').show();
	jQuery('div.grid-view').hide();
	TBFEngine.removePopovers();
    })
    jQuery('div.vehicles-list').on("click", '.grid', function (e) {
	jQuery('.grid').addClass('active');
	jQuery('.list').removeClass('active');
	jQuery('div.list-view').hide();
	jQuery('div.grid-view').show();
	TBFEngine.removePopovers();
    })
    jQuery('div#vehicle_wrapper').on("click", 'a#load_more_trigger', function (e) {
	var page = jQuery('#page').val();
	jQuery('#page').val(parseInt(page)+1);
	TBFEngine.getCars();
    })
     // on clicking book now, book a car
    jQuery(document).on("click", 'a.car_booking', function (e) {
	TBFEngine.removePopovers();
	var vehicle_id = jQuery(this).data('carid');
	var booking_btn = jQuery(this);
	// if there is a previous ajax search, then we abort it and then set tbxhr to null
        if( tbxhr != null ) {
            tbxhr.abort();
            tbxhr = null;
        }
	jQuery('#selected_car').val(vehicle_id);
	jQuery('div#loadingProgressContainer').show();
        var passingData = 'vehicle_id='+vehicle_id + '&action=bookNow' + '&nonce=' + tblightAjax.nonce;
        tbxhr = jQuery.ajax({
	    type: "POST"
	    , url: tblightAjax.ajaxurl
	    , data: passingData
	    , dataType: 'json'
	    //, async: false
	    , beforeSend: function(){
	    }
	    , complete: function(){
		// show stripe credit card form if stripe is the only payment method
		setTimeout(function () {
		    TBFEngine.calculateGrandTotal();
		}, 500);
	    }
	    , success: function(response){
		
		if(response.error==1)
		{
		    if(response.company_id==0){
			window.location.reload();
		    }
		    else {
			alert(response.msg);
			return false;
		    }
		}
		else
		{
		    TBFEngine.makeThirdStepActive();
		    
		    jQuery('#country_id').val(jQuery('#country_calling_code option:selected').data('countryid'));
		    jQuery('#limobooking-step3-wrapper .date-time').html(response.msg.pickup_datetime);
		    
		    jQuery('#limobooking-step3-wrapper .car-details .vehicle-type-label').html(response.msg.car.title);
		    jQuery('#limobooking-step3-wrapper .car-details .vehicle-type-pass-capacity').html(response.msg.car.passenger_no);
		    jQuery('#limobooking-step3-wrapper .car-details .vehicle-type-luggage-capacity').html(response.msg.car.suitcase_no);
		    jQuery('#limobooking-step3-wrapper .car-details .vehicle-type-img').prop('src', response.msg.car.image);
		    
		    jQuery('#limobooking-step3-wrapper .pass-number').html(response.msg.adultseats_html);
		    
		    if(response.msg.show_additional_seats==1){
			jQuery('#limobooking-step3-wrapper .child-seats').closest('li').show();
			jQuery('#limobooking-step3-wrapper .total-passenger').closest('li').show();
			jQuery('#limobooking-step3-wrapper .child-seats').html(response.msg.childseats);
			jQuery('#limobooking-step3-wrapper .total-passenger').html(response.msg.totalpassengers);
		    }
		    else {
			jQuery('#limobooking-step3-wrapper .child-seats').closest('li').hide();
			jQuery('#limobooking-step3-wrapper .total-passenger').closest('li').hide();
		    }
		    
		    if(response.msg.suitcases>0){
			jQuery('#limobooking-step3-wrapper .suitcases').closest('li').show();
			jQuery('#limobooking-step3-wrapper .suitcases').html(response.msg.suitcases);
		    }
		    else {
			jQuery('#limobooking-step3-wrapper .suitcases').closest('li').hide();
			jQuery('#limobooking-step3-wrapper .suitcases').html(0);
		    }
		    
		    jQuery('#limobooking-step3-wrapper .trip-details.address').show();
		    
		    jQuery('#limobooking-step3-wrapper .pickup').html(response.msg.begin);
		    jQuery('#limobooking-step3-wrapper .dropoff').html(response.msg.end);
		    
		    jQuery('.label_tooltip').tooltip();
		    if(response.msg.found_payment_method > 0){
			jQuery('div#payment_selectors').html(response.msg.payment_html);
			if(jQuery('div#payment_selectors').find('input.tb_paymentmethods').length==1){ // if only payment method available, mark it
			    jQuery('div#payment_selectors').find('input.tb_paymentmethods').attr('checked', true);
			}
			jQuery('.payment_desc').tooltip();
		    }
		    else {
			jQuery('div#payment_selectors').html('');
		    }	    
		    
		    // execute javascript in response
		    // for now, js will come from Stripe payment plugin only
		    jQuery("div#payment_selectors").find("script").each(function(i) {
			eval(jQuery(this).text());
		    });
		    
		    jQuery('div#loadingProgressContainer').hide();
		}
	    }
        });
    })
    // we should not trigger total calculation on changes of Extra in first step
    jQuery(document).on("change", '#limobooking-step3-wrapper .extra', function (e) {
	TBFEngine.calculateGrandTotal();
    })
    jQuery(document).on("click", 'input.tb_paymentmethods', function (e) {
	if(jQuery('input.tb_paymentmethods:checked').hasClass('stripe')){
	    
	}
	else {
	    // if user switches between payment methods, reset tokens if selected payment is not Stripe
	}
	TBFEngine.calculateGrandTotal();
    })
    jQuery('#make_booking').click(function(){
	var errorsCount = 0;
	// If Terms and Conditions check box is not clicked and customer clicks on Make a booking button display error message
	if(jQuery('[name="cbox"]').length > 0){
	    if(jQuery('[name="cbox"]').is(':checked')){
		jQuery('#termsError').hide();
	    }
	    else {
		errorsCount++;
		jQuery('#termsError').show();
	    }
	}
	
	jQuery('#limobooking-step3-container-area .required').each(function(){
	    jQuery(this).next('span.error').remove();
            if(jQuery(this).val().length == 0)
	    {
                errorsCount++;
                jQuery(this).parent().addClass('has-error');
		jQuery(this).after('<span class="error text-danger">'+TBTranslations.ERR_MESSAGE_FIELD_REQUIRED+'</span>');
            }
	    else if(jQuery(this).val()=="/")
	    {
		errorsCount++;
		jQuery(this).parent().addClass('has-error');
		jQuery(this).after('<span class="error text-danger">'+TBTranslations.ERR_MESSAGE_FIELD_SLASH_NOT_ALLOWED+'</span>');
	    }
	    else if(jQuery(this).hasClass('email') && !TBFEngine.ValidateEmail(jQuery(this).val()))
	    {
		errorsCount++;
		jQuery(this).parent().addClass('has-error');
		jQuery(this).after('<span class="error text-danger">'+TBTranslations.ERR_MESSAGE_VALID_EMAIL+'</span>');
	    }
	    else if(jQuery(this).hasClass('phone') && !TBFEngine.ValidatePhone(jQuery(this).val()))
	    {
		errorsCount++;
		jQuery(this).parent().addClass('has-error');
		jQuery(this).after('<span class="error text-danger">'+TBTranslations.ERR_MESSAGE_VALID_PHONE+'</span>');
	    }
	    else if(jQuery(this).hasClass('numeric') && !jQuery.isNumeric(jQuery(this).val()))
	    {
		errorsCount++;
		jQuery(this).parent().addClass('has-error');
		jQuery(this).after('<span class="error text-danger">'+TBTranslations.ERR_MESSAGE_VALID_NUMERIC+'</span>');
	    }
	    else {
                jQuery(this).parent().removeClass('has-error');
		jQuery(this).next('span.error').remove();
	    }
        })
	
	// check payment selection
	if(jQuery('div#payment_selectors').find('input[type="radio"][name="tb_paymentmethod_id"]').is(':checked')){
	    jQuery('div#payment_selectors').next('span.error').hide();
	}
	else {
	    errorsCount++;
	    jQuery('div#payment_selectors').next('span.error').show();
	}
	
	if(TBFSettings.captchaEnabled){
	    if(jQuery('#booking-g-recaptcha-response').val()=="")
	    {
		errorsCount++;
		jQuery('.g-recaptcha-response-error').show();
	    }
	    else {
		jQuery('.g-recaptcha-response-error').hide();
	    }
	}
	
	if(errorsCount>0){
	    jQuery('#step3Error').show();
	}
	else {
	    jQuery('#step3Error').hide();
	    jQuery('#make_booking').attr('disabled', true);
	    jQuery('div#loadingProgressContainer').show();
	    
	    jQuery.ajax({
		type: "POST"
		, url: tblightAjax.ajaxurl
		, data: jQuery('#price_form').serialize() + '&action=submitOrder' + '&nonce=' + tblightAjax.nonce
		, dataType: 'json'
		, beforeSend: function(){
		}
		, complete: function(){
		}
		, success: function(response){
		    if(response.error==0){
			window.location = response.redirect_url;
		    }
		    else {
			jQuery('div#loadingProgressContainer').hide();
			jQuery('#make_booking').attr('disabled', false);
		    }
		}
	    })
	}
    })
    jQuery(document).on("click", '.back_first', function (e) {
	TBFEngine.makeFirstStepActive();
    })
    jQuery(document).on("click", '.back_second', function (e) {
	TBFEngine.makeSecondStepActive();
    })
    jQuery('a.reset-booking-form').click(function(){
	// if there is a previous ajax search, then we abort it and then set tbxhr to null
        if( tbxhr != null ) {
            tbxhr.abort();
            tbxhr = null;
        }
	
	jQuery('div#loadingProgressContainer').show();
	
        tbxhr = jQuery.ajax({
	    type: "POST"
	    , url: tblightAjax.ajaxurl
	    , data: 'action=resetBookingForm' + '&nonce=' + tblightAjax.nonce
	    , dataType: 'json'
	    //, async: false
	    , beforeSend: function(){
	    }
	    , complete: function(){
	    }
	    , success: function(response){
		window.location.reload();
	    }
        });
    })

    if(TBFSettings.datePickerType=='inline')
    {
	// pickup section
	jQuery("#pickup_year").change(function(){
	    TBFEngine.setPickupDate();
	    var pickup_year = parseInt(jQuery('#pickup_year').val());
	    var currentDate = new Date();
	    var day = currentDate.getDate();
	    var month = currentDate.getMonth();
	    var year = currentDate.getFullYear();
	    jQuery('#pickup_month').chosen('destroy');
	    jQuery('#pickup_day').chosen('destroy');
	    if(pickup_year > year){
		jQuery('#pickup_day').val(1);
		jQuery('#pickup_month').val(1);
	    }
	    else {
		jQuery('#pickup_day').val(day);
		jQuery('#pickup_month').val(month+1);
	    }
	    jQuery('#pickup_month').chosen();
	    jQuery('#pickup_day').chosen();
	})
	jQuery("#pickup_month").change(function(){
	    var order_date_year = parseInt(jQuery('#pickup_year').val());
	    TBFEngine.setPickupDate();
	})
	jQuery("#pickup_day").change(function(){
	    var order_date_month = parseInt(jQuery('#pickup_month').val());
	    TBFEngine.setPickupDate();
	})
	// hide all past dates
	TBFEngine.hidePastDates();
    }
})