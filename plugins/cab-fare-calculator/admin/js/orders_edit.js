jQuery(document).ready(function($){
	jQuery('#admin-form').submit(function(){
		var errorsCount = 0;
		jQuery('#admin-form .form-required').each(function(){
			if(jQuery(this).find('input').val() == "")
			{
				errorsCount++;
				jQuery(this).addClass('form-invalid');
			}
			else if(jQuery(this).find('input').hasClass('numeric') && jQuery(this).find('input').val() == "0")
			{
				errorsCount++;
				jQuery(this).addClass('form-invalid');
			}
			else {
				jQuery(this).removeClass('form-invalid');
			}
		})
		
		if(errorsCount > 0){
			return false;
		}
		return true;
	});

    jQuery.fn.getAvailableCars = function(scrolltocars,setcarprice) {
	if( tbxhr != null ) {
		tbxhr.abort();
		tbxhr = null;
	}
	if (typeof scrolltocars == 'undefined') {
	    scrolltocars = true;
	}
	if (typeof setcarprice == 'undefined') {
	    setcarprice = true;
	}
	
	// don't scroll to cars on first load
	if(scrolltocars){
	    //jQuery('html, body').animate({ scrollTop: jQuery('div#custom_fields_wrap').offset().top-200 }, 'fast');
	}

	var passingData = jQuery('#admin-form').serialize();
	tbxhr = jQuery.ajax({
	    type: "POST"
	    , url: 'tblight.php?controller=tblight&action=getAvailableCarsAjax&ajax=1'
	    , data: passingData
	    , dataType: 'json'
	    //, async: false
	    , beforeSend: function(){
		jQuery('div#available_cars_wrap').html("");
		jQuery('#available_cars_loader').show();
		jQuery('input[name="custom_car"]').val("");
		//jQuery('input[name="price_override"]').val("");
	    }
	    , complete: function(){
		
	    }
	    , success: function(response){
		jQuery('#available_cars_loader').hide();
		jQuery('div#available_cars_wrap').show();
		if(setcarprice){
		    jQuery('input[name="price"]').val(response.selected_car_price);
		    jQuery('input[name="sub_total"]').val(response.selected_car_price);
		}
		
		if(response.error==0)
		{
		    jQuery('input#selected_car_id').val(0);
		    jQuery('div#available_cars_wrap').html(response.msg);
		}
		else {
		    jQuery('div#available_cars_wrap').html('<span id="available_cars_error" style="color:red;font-size:15px;">'+response.msg+'</span>');
		}
	    }
	})
    }	

	jQuery('.datepicker_input').datepicker({
		dateFormat: "yy-mm-dd",
		minDate: new Date()
	});

	jQuery('.btn-group-yesno label.btn').click(function () {
		if (jQuery(this).prop("checked")) {
			// checked
			return;
		}
		jQuery(this).siblings('.btn').removeClass('active');
		jQuery(this).addClass('active');
		jQuery(this).parent('.btn-group-yesno').children('input').attr('checked', false);
		jQuery(this).prev('input').attr('checked', true);
	});

    jQuery('a.get_cars').click(function(){
		jQuery.fn.getAvailableCars();
    });
    // if this is edit order, show previously selected car with list of all cars available
    if(page_mode=='edit' && car_type=='system'){
	jQuery.fn.getAvailableCars(false,false);
    }
    jQuery(document).on("click", '.assign_car', function (e) {
	var car_price = jQuery(this).closest('.car_row').data('price');
	jQuery('[name="price"]').val(car_price);
	jQuery('[name="sub_total"]').val(car_price);
	jQuery('.gratuities_btn').removeClass('active');
	jQuery('#flat_gratuity').val("");
	jQuery('#gratuity_amt').val(0);
	jQuery('#gratuity_amttype').val("");
	jQuery('#gratuity_percent_value').val(0);
	jQuery('#changed').val(1);
    })
    jQuery(document).on("change", 'select:not(#state)', function (e) {
	if(jQuery('div#available_cars_wrap').is(":visible")){
	    jQuery.fn.getAvailableCars();
	}
    })
    jQuery(document).on("change", 'input,select,textarea', function (e) {
	jQuery('#changed').val(1);
    })
    // if custom car is added, hide the system cars and reset price to zero
    jQuery('input[name="custom_car"]').change(function(){
	jQuery('input#selected_car_id').val(0);
	jQuery('div#available_cars_wrap').html("").hide();
	jQuery('[name="price"]').val(0);
	jQuery('[name="sub_total"]').val(0);
	jQuery('#changed').val(1);
    })    

	var selected_callingcode = jQuery('.country_list select option:selected').data('callingcode');
	jQuery('input[name="country_calling_code"]').val(selected_callingcode);
    jQuery('.country_list select').change(function(){
	var selected_callingcode = jQuery('.country_list select option:selected').data('callingcode');
	jQuery('input[name="country_calling_code"]').val(selected_callingcode);
    })	    			
});

function reloadAvailableCars(){
    if(jQuery('div#available_cars_wrap').is(":visible")){
	jQuery.fn.getAvailableCars();
    }
    jQuery('#changed').val(1);
}

var map;

function initialize() {
    var pickup_input = document.getElementById('pickup_address');
    var pickup_autocomplete = new google.maps.places.Autocomplete(pickup_input, options);
    google.maps.event.addListener(pickup_autocomplete, 'place_changed', function() {
	    var place = pickup_autocomplete.getPlace();
	    if (place.geometry) {
		    document.getElementById('pickup_lat').value = place.geometry.location.lat();
		    document.getElementById('pickup_lng').value = place.geometry.location.lng();
		    //jQuery.fn.getExtras('address_pickup');
		    
		    // change in value should trigger updated car list for edit order only
		    if(jQuery('div#available_cars_wrap').is(":visible")){
			jQuery.fn.getAvailableCars();
		    }
		    jQuery('#changed').val(1);
	    }
    });

    var dropoff_input = document.getElementById('dropoff_address');
    var dropoff_autocomplete = new google.maps.places.Autocomplete(dropoff_input, options);
    google.maps.event.addListener(dropoff_autocomplete, 'place_changed', function() {
	    var place = dropoff_autocomplete.getPlace();
	    if (place.geometry) {
		    document.getElementById('dropoff_lat').value = place.geometry.location.lat();
		    document.getElementById('dropoff_lng').value = place.geometry.location.lng();
		    //jQuery.fn.getExtras('address_dropoff');
		    
		    // change in value should trigger updated car list for edit order only
		    if(jQuery('div#available_cars_wrap').is(":visible")){
			jQuery.fn.getAvailableCars();
		    }
		    jQuery('#changed').val(1);
	    }
    });
}
google.maps.event.addDomListener(window, 'load', initialize);