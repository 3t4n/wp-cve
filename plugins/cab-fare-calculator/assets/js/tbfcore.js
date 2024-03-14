var TBFCoreSettings = new function ()
{
    this.DatePickerFormat = null;
    this.TimePckerFormat = null;
    this.Is12HoursTimeFormat = null;
    this.SelectedOres4ServiceType = null;
    this.CurrencySymbol = "";
    this.CalculatedDistance = 0;
    this.CalculatedReturnDistance = 0;
    this.MaxStopCount = 23;
    this.RoutingType = 
    {
        PickUp: "Pickup",
        DropOff: "Dropoff",
        Stop: "Stop",
    };

	this.DistanceUnits = {
		Miles: "Miles",
		Kilometers: "Kilometers"
	}

};

var TBFEngine = new function ()
{
    this.OnReady = function ()
    {
	//init datetime picker on Step 1 http://eonasdan.github.io/bootstrap-datetimepicker/
	this.ApplyDatePicker(jQuery('#pickupDateHolderStep1'), 'pickupDateStep1');
	
	// For Daily Hire, Drop off date and time 24 hours after Pick up 
	var min_dropoff_date = new Date();
	min_dropoff_date.setTime(min_dropoff_date.getTime() + 60 * 60 * 24 * 1000);
	this.ApplyDatePicker(jQuery('#dropoffDateHolderStep1'), 'dropoffDateStep1', min_dropoff_date);
	
	this.ApplyHrMinPicker();
	
	for (var i =0; i < selectedAreaVerticesArr.length; i++) {
	    triangleCoords.push(new google.maps.LatLng(selectedAreaVerticesArr[i][0],selectedAreaVerticesArr[i][1]));
	}

	if (TBFSettings.checkPickupAreaOperation == 1 || TBFSettings.checkDropoffAreaOperation == 1 || TBFSettings.checkPickupDropoffAreaOperation == 1 )
	{
		// area of operation map
		var areaOfOperation_mapOptions = {
			zoom: 8,
			center: new google.maps.LatLng(selectedAreaVerticesArr[0][0],selectedAreaVerticesArr[0][1]),
			mapTypeId: google.maps.MapTypeId.ROADMAP
		};

		var areaOfOperation_map = new google.maps.Map(document.getElementById('map-canvas-operation'), areaOfOperation_mapOptions);

		// Construct the polygon.
		areaOfOperation = new google.maps.Polygon({
			paths: triangleCoords,
			strokeColor: '#FF0000',
			strokeOpacity: 0.8,
			strokeWeight: 2,
			fillColor: '#FF0000',
			fillOpacity: 0.35,
			draggable: false,
			editable: false
		});

		areaOfOperation.setMap(areaOfOperation_map);

		var bounds = new google.maps.LatLngBounds();
			for (var i =0; i < selectedAreaVerticesArr.length; i++) {
			bounds.extend(new google.maps.LatLng(selectedAreaVerticesArr[i][0],selectedAreaVerticesArr[i][1]));
		}
		google.maps.event.addListenerOnce(areaOfOperation_map, 'idle', function() {
			areaOfOperation_map.fitBounds(bounds);
		});
	}
	
	setTimeout(function () {
	    
	    var address_search_enabled = true;
	    
	    if( (address_search_enabled && jQuery('#address_from').val()!="" && jQuery('#address_to').val()!="")
	       && (jQuery('#passengers').val()!=0)
	       && (jQuery('#pickupDateStep1').val()!="" && jQuery('#pickupTimeStep1').val()!="")
	    )
	    {
		var booking_type = jQuery('#booking_type').val();
		TBFEngine.getMaps(false);  // show_cars = TRUE will show 2nd step cars table, FALSE will show 3rd step
	    }
	}, 1000);
    };

    // Show view map button if Show map = YES
    this.showViewMapBtn = function() {
	if(TBFSettings.showMapInPopupOnly){
	    jQuery('.viewMapBtn').show();
	}
	else {
	    jQuery('.viewMapBtn').hide();
	}
    }
        
    this.clearStopPoints = function(){
	jQuery('#stops_modal_trigger').hide();
	jQuery('#stops_data_wrapper').html("");
	jQuery('.stops-wrapper').hide();
    }
    this.clearAjaxLoading = function() {
        jQuery('.autocomplete-loading,.autocomplete-loading-list').remove();
    }
    this.unsetPoiAddress = function() {
        jQuery('input#address_from,input#address_from_lat,input#address_from_lng').val('');
        jQuery('input#address_to,input#address_to_lat,input#address_to_lng').val('');
	
	// reset map to default city-country 
	jQuery("#estimatedDistance").text("");//clear calculated distance
	GoogleGeoCore.DirectionRenderer1.setMap(null);
	//clear pushpins
	if(TBFSettings.showMapOnDesktop){
	    GoogleGeoCore.ClearPushpins();
	}
	//console.log(TBFSettings.defaultMapLat+' '+TBFSettings.defaultMapLng);
	if(TBFSettings.defaultMapLat!="" && TBFSettings.defaultMapLng!=""){
	    GoogleGeoCore.MapStep1.setCenter({lat: TBFSettings.defaultMapLat, lng: TBFSettings.defaultMapLng});
	}
    }
    this.makeFirstStepActive = function(){
	TBFEngine.removePopovers();
	jQuery('#bottomFloatingBar').hide(); // price floating bar will not be first step
	jQuery('#limobooking-step3-wrapper').hide();
	jQuery('#limobooking-step2-wrapper').hide();
	jQuery('#limobooking-step1-wrapper').show();
	
	jQuery('#limobooking-steps-area .step-number-wrap.third').removeClass('active');
	jQuery('#limobooking-steps-area .step-number-wrap.second').removeClass('active');
	jQuery('#limobooking-steps-area .step-number-wrap.first').addClass('active');
	
	// change data source to component
	jQuery('#data_source').val('component');
	
	jQuery("body, html", window.document).animate({ 
	    scrollTop: jQuery('#limobooking-steps-area').offset().top 
	}, 'fast');
	
	if(TBFSettings.showMapOnDesktop){
	    GoogleGeoCore.RenderDirections();
	}
	
	if(TBFSettings.fixedFareBookingEnabled && TBFSettings.routeSwappingEnabled){
	    var route_swapped = jQuery('#route_swapped').val();
	    if(route_swapped==1){
		jQuery('#route_from_fld,#route_to_fld').attr('readonly', true);
		jQuery('div#tabs_offers div.list_trigger').hide();
		
		if(TBFSettings.showPOICategories==1 ) {
		    jQuery('#route_category_fld,#route_category_dropoff_fld').attr('readonly', true);
		}
		
	    }
	}
    }
    this.makeSecondStepActive = function(){
	TBFEngine.removePopovers();
	jQuery('#bottomFloatingBar').hide(); // price floating bar will not be first step
	jQuery('#limobooking-step3-wrapper').hide();
	jQuery('#limobooking-step2-wrapper').show();
	jQuery('#limobooking-step1-wrapper').hide();

	jQuery('#limobooking-step2-wrapper div.tripSummary').show();

	jQuery('#limobooking-steps-area .step-number-wrap.third').removeClass('active');
	jQuery('#limobooking-steps-area .step-number-wrap.second').addClass('active');
	jQuery('#limobooking-steps-area .step-number-wrap.first').removeClass('active');
	
	jQuery("body, html", window.document).animate({ 
	    scrollTop: jQuery('#limobooking-steps-area').offset().top 
	}, 'fast');
    }
    this.makeThirdStepActive = function(){
	TBFEngine.removePopovers();
	jQuery('#bottomFloatingBar').show(); // show price floating bar on third step
	jQuery('#limobooking-step3-wrapper').show();
	jQuery('#limobooking-step2-wrapper').hide();
	jQuery('#limobooking-step1-wrapper').hide();
	
	jQuery('#limobooking-steps-area .step-number-wrap.third').addClass('active');
	jQuery('#limobooking-steps-area .step-number-wrap.second').removeClass('active');
	jQuery('#limobooking-steps-area .step-number-wrap.first').removeClass('active');
	
	jQuery("body, html", window.document).animate({ 
	    scrollTop: jQuery('#limobooking-steps-area').offset().top 
	}, 'fast');
    }
    this.removePopovers = function(){
	if(jQuery(document).find(".popover").length>0){
	    jQuery(document).find(".popover").remove();
	}
    }
    this.showAreaOperationPopup = function(){
	TBFEngine.clearAjaxLoading();
	//jQuery('#areaOperationiFrame').attr('src', TBF_BASE_URL+'index.php?option=com_tblight&view=taxibooking&layout=areaoperation&tmpl=component');
	jQuery('#areaOperationModal').modal("show");
    }
    this.checkAreaOperation = function(target)
    {
	var pickup_lat = jQuery('#address_from_lat').val();
	var pickup_lng = jQuery('#address_from_lng').val();
	var dropoff_lat = jQuery('#address_to_lat').val();
	var dropoff_lng = jQuery('#address_to_lng').val();
	
	areaOfOperation = new google.maps.Polygon({
	    paths: triangleCoords
	});
	
	// Both Pick up and Drop off in Area = YES, both Pick up and Drop off have to be in Area of operation
	if(TBFSettings.checkPickupDropoffAreaOperation==1)
	{
	    if(target=='address_from'){
		if ( google.maps.geometry.poly.containsLocation(new google.maps.LatLng(pickup_lat,pickup_lng), areaOfOperation))
		{
		    return true;
		}
		else {
		    jQuery('#address_from').val('');
		    jQuery('#address_from_lat').val('');
		    jQuery('#address_from_lng').val('');
		    TBFEngine.showAreaOperationPopup();
		    return false;
		}    
	    }
	    else if(target=='address_to'){
		if ( google.maps.geometry.poly.containsLocation(new google.maps.LatLng(dropoff_lat,dropoff_lng), areaOfOperation))
		{
		    return true;
		}
		else {
		    jQuery('#address_to').val('');
		    jQuery('#address_to_lat').val('');
		    jQuery('#address_to_lng').val('');
		    TBFEngine.showAreaOperationPopup();
		    return false;
		}    
	    }
	}
	else { // Both Pick up and Drop off in Area = NO
	    
	    // 1) When Pick up in Area: Yes
	    // and Drop off in Area: Yes
	    // but Both Pick up and Drop off in Area: No
	    // then only one, either Pick up or Drop off has to be in the Area of operation and they can both be in the area 
	    if(TBFSettings.checkPickupAreaOperation==1 && TBFSettings.checkDropoffAreaOperation==1){
		if(pickup_lat!="" && pickup_lng!="" && dropoff_lat!="" && dropoff_lng!="")
		{
		    if ( !google.maps.geometry.poly.containsLocation(new google.maps.LatLng(pickup_lat,pickup_lng), areaOfOperation)
			&& !google.maps.geometry.poly.containsLocation(new google.maps.LatLng(dropoff_lat,dropoff_lng), areaOfOperation)
			)
		    {
			if(target=='address_from'){
			    jQuery('#address_from').val('');
			    jQuery('#address_from_lat').val('');
			    jQuery('#address_from_lng').val('');
			}
			else if(target=='address_to'){
			    jQuery('#address_to').val('');
			    jQuery('#address_to_lat').val('');
			    jQuery('#address_to_lng').val('');
			}
			TBFEngine.showAreaOperationPopup();
			return false;
		    }
		    else {
			return true;
		    }
		}
		else {
		    return true;
		}
	    }
	    else if(TBFSettings.checkPickupAreaOperation==1 && TBFSettings.checkDropoffAreaOperation==0) { // only Pick up has to be in the Area of operation
		if (target=='address_from' && !google.maps.geometry.poly.containsLocation(new google.maps.LatLng(pickup_lat,pickup_lng), areaOfOperation))
		{
		    jQuery('#address_from').val('');
		    jQuery('#address_from_lat').val('');
		    jQuery('#address_from_lng').val('');
		    TBFEngine.showAreaOperationPopup();
		    return false;
		}
		else {
		    return true;
		}
	    }
	    else if(TBFSettings.checkPickupAreaOperation==0 && TBFSettings.checkDropoffAreaOperation==1) { // only DropOff has to be in the Area of operation
		if (target=='address_to' && !google.maps.geometry.poly.containsLocation(new google.maps.LatLng(dropoff_lat,dropoff_lng), areaOfOperation))
		{
		    jQuery('#address_to').val('');
		    jQuery('#address_to_lat').val('');
		    jQuery('#address_to_lng').val('');
		    TBFEngine.showAreaOperationPopup();
		    return false;
		}
		else {
		    return true;
		}
	    }
	    else { // none of Pick up or Drop off has to be in the Area of operation
		return true;
	    }
	}
	return true;
    }
    // collect map data
    this.getMaps = function(show_cars) {  // show_cars = TRUE will show 2nd step cars table, FALSE will show 3rd step
        // if there is a previous ajax search, then we abort it and then set tbxhr to null
        if( tbxhr != null ) {
            tbxhr.abort();
            tbxhr = null;
        }
        var booking_type = jQuery('#booking_type').val();
        var passingData = jQuery('#price_form').serialize() + '&action=getPrice' + '&nonce=' + tblightAjax.nonce;
	
        tbxhr = jQuery.ajax({
                type: "POST"
                , url: tblightAjax.ajaxurl
                , data: passingData
                , dataType: 'json'
                , beforeSend: function(){
		    jQuery('div#loadingProgressContainer').show();
                }
                , complete: function(){
                }
                , success: function(response){
		    jQuery('div#loadingProgressContainer').hide();
                    if(response.error==1)
                    {
			if(response.company_id==0){
			    window.location.reload();
			}
			else {
			    jQuery('div#step1Error').show().html(response.msg);
			    return false;
			}
                    }
                    else
                    {
			jQuery('#limobooking-step2-wrapper .location-form').html(response.msg.begin);
			jQuery('#limobooking-step2-wrapper .location-to').html(response.msg.end);
			jQuery('#limobooking-step2-wrapper .distance').html(' '+response.msg.distance);
			jQuery('#limobooking-step2-wrapper .duration').html(' '+response.msg.duration);
			
			TBFEngine.getCars(show_cars);
                    }                    
                }
        })
    }
    // collect cars
    this.getCars = function(show_cars) {
        // if there is a previous ajax search, then we abort it and then set tbxhr to null
        if( tbxhr != null ) {
            tbxhr.abort();
            tbxhr = null;
        }
	jQuery('#selected_car').val(0);
	var booking_type = jQuery('#booking_type').val();
        var passingData = jQuery('#price_form').serialize() + '&action=getVehicles' + '&nonce=' + tblightAjax.nonce;
	
	TBFEngine.removePopovers();
	
        tbxhr = jQuery.ajax({
                type: "POST"
		, url: tblightAjax.ajaxurl
                , data: passingData
                , dataType: 'json'
                , beforeSend: function(){
		    jQuery('div#loadingProgressContainer').show();
		    jQuery('#limobooking-step2-wrapper #warning_msg, #limobooking-step2-wrapper div.msg_board').html('').hide();
                }
                , complete: function(){
                }
                , success: function(response){
		    jQuery('div#loadingProgressContainer').hide();
		    //alert(response.test);
		    TBFEngine.makeSecondStepActive();
		    
		    jQuery('#limobooking-step2-wrapper .date-time').html(response.pickup_datetime);
		    jQuery('#limobooking-step3-wrapper .date-time').html(response.pickup_datetime);
		    
		    if(response.show_stops==1){
		    	jQuery('#limobooking-step2-wrapper .list-address-point').show().html(response.stops_html);
		    };
		    
		    if(response.additional_seats_html==""){
			jQuery('#limobooking-step2-wrapper .additional_seats_wrapper').hide();
			jQuery('#limobooking-step2-wrapper #additional-seats').html('');
		    }
		    else{
			jQuery('#limobooking-step2-wrapper .additional_seats_wrapper').show();
			jQuery('#limobooking-step2-wrapper #additional-seats').html(response.additional_seats_html);
		    }
                    
		    if(response.error==1)
                    {
			if(response.company_id==0){
				window.location.reload();
			}
			else {
			    jQuery('div.trip_status').show();

			    if(response.call_next_available==1
			    && (booking_type=='address'||booking_type=='offers'||booking_type=='hourly'||booking_type=='tours')
			    ){
				    TBFEngine.getNextAvailableCar();
			    }
			    else {
				jQuery('#limobooking-step2-wrapper #vehicle_wrapper').show().html("");
				jQuery('#limobooking-step2-wrapper #warning_msg').show().html(response.msg);
			    }
			}
                    }
                    else
                    {
			jQuery('#limobooking-step2-wrapper #vehicle_wrapper').show().html(response.msg);
			jQuery( '.car_desc' ).tooltip();
			
			jQuery('div.trip_status').show();

			jQuery('a.price_popup').tooltip({
			    html: true,
			    title: function() {
				var target_selector = jQuery(this).data('target-selector');
				return jQuery('.'+target_selector).html();
			    }
			});
			if(jQuery('a.rate_details').length > 0){
			    jQuery('a.rate_details').popover({ 
				placement : 'bottom',
				container : 'body',
				html : true,
				title: function(){
				    return jQuery(this).data('title')+'&nbsp;<span class="close">&times;</span>';
				},
				content: function() {
				    var target_selector = jQuery(this).data('target-selector');
				    return jQuery('.'+target_selector).html();
				}
			    }).on('shown.bs.popover', function(e){
				var popover = jQuery(this);
				jQuery(document).on("click", ".popover .close" , function(){
				    popover.popover('hide');
				});
			    });
			}
			// display type from config
			if(TBFSettings.carsDefaultDisplay=='list'){
			    jQuery('div.vehicles-list .grid').removeClass('active');
			    jQuery('div.vehicles-list .list').addClass('active');
			    jQuery('div.vehicles-list div.list-view').show();
			    jQuery('div.vehicles-list div.grid-view').hide();
			}
			else {
			    jQuery('div.vehicles-list .grid').addClass('active');
			    jQuery('div.vehicles-list .list').removeClass('active');
			    jQuery('div.vehicles-list div.list-view').hide();
			    jQuery('div.vehicles-list div.grid-view').show();
			}
			if ((jQuery("#limobooking-step2-wrapper").width() <= 865) && (jQuery(window).width() >= 979)) {
			    jQuery("#limobooking-step2-wrapper .vehicles-list").addClass("small-style");
			}
			else {
			    jQuery("#limobooking-step2-wrapper .vehicles-list").removeClass("small-style");
			}
			
			// Check if car is already selected in previous attempt,
			// If yes, redirect user to 3rd step
			if(show_cars==false){
			    //TBFEngine.isCarAlreadySelected();
			}
                    }
                }
        });
    }
    //popover related code
    //jQuery(document).on("click", 'a.rate_details', function (e) {
	//e.stopPropagation();
    //});
    //jQuery(document).click(function (e) {
	//if ((jQuery('.popover').has(e.target).length == 0) || jQuery(e.target).is('.close')) {
	    //if(jQuery(document).find(".popover").length>0){
		//jQuery(document).find(".popover").remove();
	    //}
	//}
    //});
    // collect next available car
    this.getNextAvailableCar = function() {
        var passingData = jQuery('#price_form').serialize();
	var booking_type = jQuery('#booking_type').val();
        jQuery.ajax({
                type: "POST"
                , url: TBF_BASE_URL+'index.php?option=com_tblight&task=onepage.getNextAvailableCar&ajax=1'
                , data: passingData
                , dataType: 'json'
                , beforeSend: function(){
		    jQuery('div#loadingProgressContainer').show();
		    jQuery('#limobooking-step2-wrapper #vehicle_wrapper').html('');
                }
                , complete: function(){
                }
                , success: function(response){
		    jQuery('div#loadingProgressContainer').hide();
                    if(response.error==1)
                    {
			if(response.company_id==0){
			    window.location.reload();
			}
			else {
			    jQuery('#limobooking-step2-wrapper #warning_msg').show().html(response.msg);
			}
                    }
                    else
                    {
			jQuery('#limobooking-step2-wrapper .date-time').html(response.pickup_datetime);
			jQuery('#limobooking-step3-wrapper .date-time').html(response.pickup_datetime);
			jQuery('#limobooking-step2-wrapper .list-address-point').show().html(response.stops_html);
			
			if(response.additional_seats_html==""){
			    jQuery('#limobooking-step2-wrapper .additional_seats_wrapper').hide();
			    jQuery('#limobooking-step2-wrapper #additional-seats').html('');
			}
			else{
			    jQuery('#limobooking-step2-wrapper .additional_seats_wrapper').show();
			    jQuery('#limobooking-step2-wrapper #additional-seats').html(response.additional_seats_html);
			}
			
			jQuery('#limobooking-step2-wrapper #warning_msg').show().html(response.next_available_msg);
			jQuery('#limobooking-step2-wrapper #vehicle_wrapper').html(response.msg);
			
			jQuery( '.car_desc' ).tooltip();
			
			// hide the cars table
			jQuery('#limobooking-step2-wrapper #vehicle_wrapper').hide();
			jQuery('#show_next_available_yes').click(function(){
			    jQuery('#limobooking-step2-wrapper #vehicle_wrapper').show();
			})
			jQuery('#show_next_available_no').click(function(){
			    TBFEngine.makeFirstStepActive();
			})
			
			if(jQuery('a.rate_details').length > 0){
			    jQuery('a.rate_details').popover({ 
				placement : 'bottom',
				container : 'body',
				html : true,
				title: function(){
				    return jQuery(this).data('title')+'&nbsp;<span class="close">&times;</span>';
				},
				content: function() {
				    var target_selector = jQuery(this).data('target-selector');
				    return jQuery('.'+target_selector).html();
				}
			    }).on('shown.bs.popover', function(e){
				var popover = jQuery(this);
				jQuery(document).on("click", ".popover .close" , function(){
				    popover.popover('hide');
				});
			    });
			}
			
			// display type from config
			if(TBFSettings.carsDefaultDisplay=='list'){
			    jQuery('div.vehicles-list .grid').removeClass('active');
			    jQuery('div.vehicles-list .list').addClass('active');
			    jQuery('div.vehicles-list div.list-view').show();
			    jQuery('div.vehicles-list div.grid-view').hide();
			}
			else {
			    jQuery('div.vehicles-list .grid').addClass('active');
			    jQuery('div.vehicles-list .list').removeClass('active');
			    jQuery('div.vehicles-list div.list-view').hide();
			    jQuery('div.vehicles-list div.grid-view').show();
			}
                    }
                }
        });
    }
    this.isCarAlreadySelected = function() {
        var passingData = jQuery('#price_form').serialize();
	var booking_type = jQuery('#booking_type').val();
        jQuery.ajax({
                type: "POST"
                , url: TBF_BASE_URL+'index.php?option=com_tblight&task=onepage.getAlreadySelectedCar&ajax=1'
                , data: passingData
                , dataType: 'json'
                , beforeSend: function(){
                }
                , complete: function(){
                }
                , success: function(response){
		    if(response.car_is_set!=0){
			jQuery('#limobooking-step2-wrapper #vehicle_wrapper .vehicles-body.grid-view a.car_booking').each(function(){
			    var vehicle_id = jQuery(this).data('carid');
			    if(vehicle_id==response.car_is_set){
				jQuery(this).trigger('click');
			    }
			})
		    }
                }
        });
    }
    this.calculateGrandTotal = function() {
	
	var booking_type = jQuery('#booking_type').val();
        jQuery.ajax({
	    type: "POST",
	    url: tblightAjax.ajaxurl,
	    data: jQuery('#price_form').serialize() + '&action=calculateTotal' + '&nonce=' + tblightAjax.nonce,
	    dataType: 'json',
	    //async: false,
	    beforeSend: function(){
		jQuery('div#loadingProgressContainer').show();
	    },
	    complete: function(){
	    },
	    success: function(response){
		jQuery('div#loadingProgressContainer').hide();
		if(response.error==1){
		    if(response.company_id==0){
			window.location.reload();
		    }
		    else {
			alert(response.msg);
		    }
		}
		else {
		    // price display
		    if(response.msg.show_price==1){
			jQuery('.sub_total_wrap,.grand_total_wrap').show();
			jQuery('.sub_total').html(response.msg.sub_total);
			jQuery('#bottomFloatingBar .grand_total').html(response.msg.grand_total);
			if(response.msg.show_flat_cost==1){
			    jQuery('.flat_cost_wrap').show();
			    jQuery('.flat_cost').html(response.msg.flat_cost);
			}
			else {
			    jQuery('.flat_cost').html("");
			    jQuery('.flat_cost_wrap').hide();
			}
			if(response.msg.show_percentage_cost==1){
			    jQuery('.percentage_cost_wrap').show();
			    jQuery('.percentage_cost').html(response.msg.percentage_cost);
			}
			else {
			    jQuery('.percentage_cost').html("");
			    jQuery('.percentage_cost_wrap').hide();
			}
			
			if(response.msg.payment_labels!=""){
			    jQuery('#bottomFloatingBar .payment_label_wrap').remove();
			    jQuery('#bottomFloatingBar .user_group_discount_wrap').after(response.msg.payment_labels);
			}
			else {
			    jQuery('#bottomFloatingBar .payment_label_wrap').remove();
			}
		    }
		    else {
			jQuery('.sub_total,#bottomFloatingBar .grand_total,.flat_cost,.percentage_cost').html("");
			jQuery('.sub_total_wrap,.flat_cost_wrap,.percentage_cost_wrap,.grand_total_wrap').hide();
		    }
		}
	    }
        });
    }

    //Validate PickUp Date
    this.ValidatePickUpDateTime = function (step)
    {
    	var dateIsValid = true;
    	var timeIsValid = true;
	    var disallowed = false;
	    var pickUpDateValue = $("#pickupDate" + step).val();
	    var pickupTimeValue = $("#pickupTime" + step).val();
	    var puDate = parseDate(pickUpDateValue, Ores4Settings.DatePickerFormat);
	    var puTime = tryParseTime(pickupTimeValue);

	    if (pickUpDateValue == "") {
		    $("#pickupDateErrorDiv" + step).text("Date is required");
		    dateIsValid = false;
	    }
	    else if(!isValidDate(puDate))
	    {
	    	$("#pickupDateErrorDiv" + step).text("Date is incorrect");
	    	dateIsValid = false;
	    }
 
	    if (pickupTimeValue == "")
        {
        	timeIsValid = false;
        	$("#pickupTimeErrorDiv" + step).text("Time is required");
	    }
	    else if (puTime == null)
	    {
	    	timeIsValid = false;
	    	$("#pickupTimeErrorDiv" + step).text("Time is incorrect");
	    }
        
	    if (dateIsValid && timeIsValid)
	    {
	        var errorDescription = Ores4Engine.ValidatePuDateTimeRestriction(pickUpDateValue, puTime);

	        if (errorDescription.length > 0)
	        {
	            if (errorDescription === "Date in past")
	            {
	                $("#pickupDateErrorDiv" + step).text("Date in past");
	                dateIsValid = false;
	            }
	            else if (errorDescription.length > 0)
	            {
	                $("#pickupDateTimeErrorDiv" + step).text(errorDescription);
	            }
	            disallowed = true;
            }
	    }

	    if (!dateIsValid) {
        	$("#pickupDateHolder" + step).addClass("has-error");
        	$("#pickupDateErrorDiv" + step).show();
        }
        else {
        	$("#pickupDateHolder" + step).removeClass("has-error");
        	$("#pickupDateErrorDiv" + step).hide();
        }

        if (!timeIsValid)
        {
            $("#pickupTimeHolder" + step).addClass("has-error");
            $("#pickupTimeErrorDiv" + step).show();
        }
        else
        {
            $("#pickupTimeHolder" + step).removeClass("has-error");
            $("#pickupTimeErrorDiv" + step).hide();
        }

        if (disallowed) {
        	$("#pickupTimeHolder" + step).addClass("has-error");
        	$("#pickupDateHolder" + step).addClass("has-error");
        	$("#pickupDateTimeErrorDiv" + step).show();
        }
        else {
	        if (timeIsValid) {
	        	$("#pickupTimeHolder" + step).removeClass("has-error");
	        }
	        if (dateIsValid) {
				$("#pickupDateHolder" + step).removeClass("has-error");
	        }

        	$("#pickupDateTimeErrorDiv" + step).hide();
        }

	    return dateIsValid && timeIsValid && (!disallowed);
    };

	//Validate PickUp Date
	this.ValidatePickUpDate = function(step)
	{
		var dateIsValid = true;
		var pickUpDateValue = $("#pickupDate" + step).val();
		var puDate = parseDate(pickUpDateValue, Ores4Settings.DatePickerFormat);

		if (pickUpDateValue === "")
		{
			$("#pickupDateErrorDiv" + step).text("Date is required");
			dateIsValid = false;
		}
		else if (!isValidDate(puDate))
		{
			$("#pickupDateErrorDiv" + step).text("Date is incorrect");
			dateIsValid = false;
		}

		if (!dateIsValid)
		{
			$("#pickupDateHolder" + step).addClass("has-error");
			$("#pickupDateErrorDiv" + step).show();
		}
		else
		{
			$("#pickupDateHolder" + step).removeClass("has-error");
			$("#pickupDateErrorDiv" + step).hide();
		}

		return dateIsValid;
	};

	//Validate PickUp time
	this.ValidatePickUpTime = function(step)
	{
		var timeIsValid = true;
		var pickupTimeValue = $("#pickupTime" + step).val();
		var puTime = tryParseTime(pickupTimeValue);

		if (pickupTimeValue === "")
		{
			timeIsValid = false;
			$("#pickupTimeErrorDiv" + step).text("Time is required");
		}
		else if (puTime == null)
		{
			timeIsValid = false;
			$("#pickupTimeErrorDiv" + step).text("Time is incorrect");
		}

		if (!timeIsValid)
		{
			$("#pickupTimeHolder" + step).addClass("has-error");
			$("#pickupTimeErrorDiv" + step).show();
		}
		else
		{
			$("#pickupTimeHolder" + step).removeClass("has-error");
			$("#pickupTimeErrorDiv" + step).hide();
		}

		return timeIsValid;
	};

    this.ValidatePuDateTimeRestriction = function ()
    {
        var errorDescription = "";

        var hourLimit = TBFSettings.orderCreationHoursLimit == null ? 0 : TBFSettings.orderCreationHoursLimit;
	
	if(TBFSettings.datePickerType=='jquery')
	{
	    var order_date = jQuery('#pickupDateStep1').val();
	
	    if(TBFSettings.DatePickerFormat=='DD-MM-YYYY'){
		var order_date_day = order_date.substring(0, 2);
		var order_date_month = order_date.substring(3, 5);
	    }
	    else {
		var order_date_month = order_date.substring(0, 2);
		var order_date_day = order_date.substring(3, 5);
	    }
	    var order_date_year = order_date.substring(6, 10);
	    
	    var pickup_hr = jQuery('input#selPtHr1').val();
	    var pickup_min = jQuery('input#selPtMn1').val();
	    
	    if(TBFSettings.Is12HoursTimeFormat){
		var pickup_ampm = jQuery('#seltimeformat1').val();
		
		var time12hr = pickup_hr+':'+pickup_min+' '+pickup_ampm;
		var resultArray = TBFEngine.ConvertTimeformat(time12hr);
		
		pickup_hr = resultArray[0];
		pickup_min = resultArray[1];
	    }
	}
	else if(TBFSettings.datePickerType=='inline')
	{
	    var order_date_day = jQuery('#pickup_day').val();
	    var order_date_month = jQuery('#pickup_month').val();
	    var order_date_year = jQuery('#pickup_year').val();
	    
	    var pickup_hr = jQuery('#selPtHr1').val();
	    var pickup_min = jQuery('#selPtMn1').val();
	    
	    if(TBFSettings.Is12HoursTimeFormat){
		var pickup_ampm = jQuery('#seltimeformat1').val();
		
		var time12hr = pickup_hr+':'+pickup_min+' '+pickup_ampm;
		var resultArray = TBFEngine.ConvertTimeformat(time12hr);
		
		pickup_hr = resultArray[0];
		pickup_min = resultArray[1];
	    }
	}
	
	var enteredDate = new Date(order_date_year, parseInt(order_date_month)-1, order_date_day, pickup_hr, pickup_min);
        //var enteredDateWithOffset = new Date(enteredDate.getTime() + (TBFSettings.TimeZoneUtcOffset * 60 + enteredDate.getTimezoneOffset()) * 60 * 1000);

        var minDateTime = new Date();
        var minDateTimeWithOffset = new Date(minDateTime.getTime() + (TBFSettings.TimeZoneUtcOffset * 60 + minDateTime.getTimezoneOffset()) * 60 * 1000);
	//console.log(enteredDate);
	//console.log(minDateTime);
	//console.log(minDateTimeWithOffset);

        var hourDifference = Math.abs(enteredDate - minDateTimeWithOffset) / 36e5;
	//var hourDifference = Math.abs(enteredDate.getTime() - minDateTime.getTime()) / 36e5;
	//console.log('hourDifference: '+hourDifference);
	//console.log('hourLimit: '+hourLimit);

        //if (hourDifference < hourLimit || enteredDateWithOffset < minDateTimeWithOffset)
	if (hourDifference < hourLimit || enteredDate < minDateTimeWithOffset)
        {
            if (hourLimit === 0)
            {
                errorDescription = TBTranslations.ERR_MESSAGE_DATETIME_IN_PAST;
            }
            else
            {
                errorDescription = TBTranslations.ERR_MESSAGE_INSUFFICIENT_BOOKING_TIME;
            }
        }
	//console.log(errorDescription);
        return errorDescription;
    };
    this.ConvertTimeformat = function(time) {
	var hours = Number(time.match(/^(\d+)/)[1]);
	var minutes = Number(time.match(/:(\d+)/)[1]);
	var AMPM = time.match(/\s(.*)$/)[1];
	if (AMPM == "PM" && hours < 12) hours = hours + 12;
	if (AMPM == "AM" && hours == 12) hours = hours - 12;
	var sHours = hours.toString();
	var sMinutes = minutes.toString();
	if (hours < 10) sHours = "0" + sHours;
	if (minutes < 10) sMinutes = "0" + sMinutes;
	
	var resultArray = new Array(2);
        resultArray[0] = sHours;
        resultArray[1] = sMinutes;
	return resultArray;
    }

    //validate email
    this.ValidateEmail = function (email) {
    	//var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    	//return re.test(email);
	
	var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
	return regex.test(email);
    };
    this.ValidatePhone = function (value) {
	var regex=/^(NA|[0-9+-]+)$/;
	return regex.test(value);
    }

    this.ApplyDatePicker = function (holder, target, minDate) {
    	//dates for datetime pickers
    	var today = new Date();
    	var todayPlusTwoYears = new Date();
    	todayPlusTwoYears.setFullYear(today.getFullYear() + 2);

    	if (!minDate) {
		minDate = today;
	}

	holder.datetimepicker(
	{
	    //pickTime: false,
	    //format: TBFSettings.DatePickerFormat,
	    //defaultDate: today,
	    //minDate: minDate,
	    //maxDate: todayPlusTwoYears, //two year future
	    //viewMode: 'days, months',
	    //minutesToAddForDefaultDateTime: (TBFSettings.orderCreationHoursLimit*60)+5,
		defaultDate: today,
		minDate: minDate,
		maxDate: todayPlusTwoYears, //two year future
		//format: 'mm/dd/yyyy',
		format: TBFSettings.DatePickerFormat,
		viewMode: 'days',
	    icons:
	    {
		time: 'icon-clock',
		date: 'icon-calendar',
		up: 'tb tb-chevron-up',
		down: 'tb tb-chevron-down'
	    }
	}).on("dp.show", function (e) {
	    //jQuery('.datepickerbutton').each(function () {
		    //if (jQuery(this).parent().data("DateTimePicker")!=holder.data("DateTimePicker")) {
			    //jQuery(this).parent().data("DateTimePicker").hide();
		    //}
	    //});
	}).on("dp.change", function (e) {
	    if(target=="pickupDateStep1"){
		var booking_type = jQuery('#booking_type').val();
	    }
	});

	holder.find("input").focus(function(e) {
	    jQuery(this).parent().data("DateTimePicker").show(e);
	});

	holder.find("input").focusout(function() {
	    jQuery(this).parent().data("DateTimePicker").hide();
	});
    };
    
    this.ApplyHrMinPicker = function () {
	
	jQuery('input#selPtHr1').click(function(){
	    if(jQuery(this).next('.timepicker-hours').is(":visible") == true){
		jQuery(this).next('.timepicker-hours').hide();
	    }
	    else {
		jQuery(this).next('.timepicker-hours').show();
	    }
	})
	jQuery('.pickup-hours').on("click", 'td.hour', function (e) {
	    jQuery('input#selPtHr1').val(jQuery(this).html());
	    jQuery('.pickup-hours').hide();
	})
	jQuery('input#selPtMn1').click(function(){
	    if(jQuery(this).next('.timepicker-minutes').is(":visible") == true){
		jQuery(this).next('.timepicker-minutes').hide();
	    }
	    else {
		jQuery(this).next('.timepicker-minutes').show();
	    }
	})
	jQuery('.pickup-minutes').on("click", 'td.minute', function (e) {
	    jQuery('input#selPtMn1').val(jQuery(this).html());
	    jQuery('.pickup-minutes').hide();
	})
	
	// Dropoff Timepickers for Daily Hire booking
	jQuery('input#dropoff_selPtHr').click(function(){
	    if(jQuery(this).next('.timepicker-hours').is(":visible") == true){
		jQuery(this).next('.timepicker-hours').hide();
	    }
	    else {
		jQuery(this).next('.timepicker-hours').show();
	    }
	})
	jQuery('.dropoff-hours').on("click", 'td.hour', function (e) {
	    jQuery('input#dropoff_selPtHr').val(jQuery(this).html());
	    jQuery('.dropoff-hours').hide();
	})
	jQuery('input#dropoff_selPtMn').click(function(){
	    if(jQuery(this).next('.timepicker-minutes').is(":visible") == true){
		jQuery(this).next('.timepicker-minutes').hide();
	    }
	    else {
		jQuery(this).next('.timepicker-minutes').show();
	    }
	})
	jQuery('.dropoff-minutes').on("click", 'td.minute', function (e) {
	    jQuery('input#dropoff_selPtMn').val(jQuery(this).html());
	    jQuery('.dropoff-minutes').hide();
	})
    };

    this.ApplyTimePicker = function (holder) {
    	//dates for datetime pickers
    	var today = new Date();
    	var todayPlusTwoYears = new Date();
    	todayPlusTwoYears.setFullYear(today.getFullYear() + 2);

	holder.datetimepicker(
	{
	    format:TBFSettings.Is12HoursTimeFormat==true?null: TBFSettings.TimePickerFormat,
	    pickDate: false,
	    minutesToAddForDefaultDateTime: (TBFSettings.orderCreationHoursLimit*60)+5,
	    icons:
	    {
		time: 'icon-clock',
		date: 'icon-calendar',
		up: 'tb tb-chevron-up',
		down: 'tb tb-chevron-down'
	    },
	    minuteStepping: 5
	}).on("dp.show", function (e) {
	    //jQuery('.datepickerbutton').each(function () {
		    //if (jQuery(this).parent().data("DateTimePicker") != holder.data("DateTimePicker")) {
			    //jQuery(this).parent().data("DateTimePicker").hide();
		    //}
	    //});
	});
	holder.find("input").focus(function (e) {
	    jQuery(this).parent().data("DateTimePicker").show(e);
	});
	holder.find("input").focusout(function () {
	    jQuery(this).parent().data("DateTimePicker").hide();
	});
    };
    
    this.comparePickupReturnDates = function () {
	var order_date = jQuery('#pickupDateStep1').val();
	var order_return_date = jQuery('#returnTripPickupDate').val();
	
	if(TBFSettings.DatePickerFormat=='DD-MM-YYYY'){
	    var order_date_day = order_date.substring(0, 2);
	    var order_date_month = order_date.substring(3, 5);
	    
	    var order_return_date_day = order_return_date.substring(0, 2);
	    var order_return_date_month = order_return_date.substring(3, 5);
	}
	else {
	    var order_date_month = order_date.substring(0, 2);
	    var order_date_day = order_date.substring(3, 5);
	    
	    var order_return_date_month = order_return_date.substring(0, 2);
	    var order_return_date_day = order_return_date.substring(3, 5);
	}
	var order_date_year = order_date.substring(6, 10);
	var order_return_date_year = order_return_date.substring(6, 10);
	
	var pickup_d = new Date(order_date_year, parseInt(order_date_month)-1, order_date_day);
	var return_d = new Date(order_return_date_year, parseInt(order_return_date_month)-1, order_return_date_day);
	//console.log(pickup_d);
	//console.log(return_d);
        if(return_d>pickup_d) {
            return true;
        }
	else {
	    return false;
	}
    }
    this.setPickupDate = function() {
	var pickup_year = jQuery('#pickup_year').val();
        var pickup_month = jQuery('#pickup_month').val();
	var pickup_day = jQuery('#pickup_day').val();
	var currentDate = new Date();
        var day = currentDate.getDate();
        var month = currentDate.getMonth();
	var year = currentDate.getFullYear();
	
	jQuery('#pickup_month').chosen('destroy');
	jQuery('#pickup_day').chosen('destroy');
	if(pickup_year > year){
	    jQuery("#pickup_month option").each(function()
	    {
		jQuery(this).attr('disabled', false);
	    })
	    jQuery("#pickup_day option").each(function()
	    {
		jQuery(this).attr('disabled', false);
	    })
	}
	else {
	    jQuery("#pickup_month option").each(function()
	    {
		if(jQuery(this).val()<(parseInt(month)+1)){
		    jQuery(this).attr('disabled', true);
		}
		else {
		    jQuery(this).attr('disabled', false);
		}
	    })
	    if(pickup_month > (parseInt(month)+1)){
		jQuery("#pickup_day option").each(function()
		{
		    jQuery(this).attr('disabled', false);
		})
	    }
	    else {
		jQuery("#pickup_day option").each(function()
		{
		    if(jQuery(this).val()<day){
			jQuery(this).attr('disabled', true);
		    }
		    else {
			jQuery(this).attr('disabled', false);
		    }
		})
	    }
	}
	jQuery('#pickup_month').chosen();
	jQuery('#pickup_day').chosen();
    }
    this.hidePastDates = function(){
        var currentDate = new Date();
        var day = currentDate.getDate();
        var month = currentDate.getMonth();
	var pickup_day = parseInt(jQuery('#pickup_day').val());
	var pickup_month = parseInt(jQuery('#pickup_month').val());
	var pickup_year = parseInt(jQuery('#pickup_year').val());
	jQuery('#pickup_month').chosen('destroy');
	jQuery('#pickup_day').chosen('destroy');
        jQuery("#pickup_day option").each(function()
        {
            if(jQuery(this).val()<parseInt(day)){
                jQuery(this).attr('disabled', true);
            }
            else {
                jQuery(this).attr('disabled', false);
            }
        })
        jQuery("#pickup_month option").each(function()
        {
            if(jQuery(this).val()<(parseInt(month)+1)){
                jQuery(this).attr('disabled', true);
            }
            else {
                jQuery(this).attr('disabled', false);
            }
        })
	jQuery('#pickup_month').chosen();
	jQuery('#pickup_day').chosen();
    }
};