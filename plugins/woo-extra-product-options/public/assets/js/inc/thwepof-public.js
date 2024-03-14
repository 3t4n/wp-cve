var thwepof_public = (function($, window, document) {
	'use strict';

	function initialize_thwepof(){
		var extra_options_wrapper = $('.thwepo-extra-options');
		//if(extra_options_wrapper){
			setup_date_picker(extra_options_wrapper, 'thwepof-date-picker', thwepof_public_var);
		//}

	    var wepo_range_input = $('input[type="range"].thwepof-input-field');
	    wepo_range_input.each(function(){
			display_range_value(this);
	    });

	    wepo_range_input.on('change', function(){
			display_range_value(this);
	    });

	    var mask_fields = $(".thwepof-mask-input");
	    mask_fields.each(function(){
			apply_input_masking(this);
	    });
	}

	function setup_date_picker(form, class_selector, data){
		//form.find('.'+class_selector).each(function(){
		$('.'+class_selector).each(function(){
			var readonly = $(this).data("readonly");
			readonly = readonly === 'yes' ? true : false;
			var yearRange = $(this).data("year-range");
			yearRange = '' == yearRange ? '-100:+10' : yearRange;
			
			$(this).datepicker({
				showButtonPanel: true,
				changeMonth: true,
				changeYear: true,
				yearRange: yearRange,
			});
			$(this).prop('readonly', readonly);
		});
	}

	/***
		Work around for field validation notice on oceanwp quickview
		ajax add to cart is the issue.
										***/
	function oceanwp_qv_field_validating_notice(){
		jQuery("body").off("adding_to_cart").on("adding_to_cart", function(event, addToCartBtn, formData) {
			var $cartForm = $("#owp-qv-content").find('form.cart');
			// Find the table with class "thwepo-extra-options" inside the form
			var $table = $cartForm.find('table.thwepo-extra-options');
			var $requiredTrs = $table.find('tr:has(abbr.required)');
			var $requiredTds = $requiredTrs.find('td.value, td.abovefield');

			// Initialize an empty array to store the input names
			var inputNames = [];
			var reqFields_data = [];
			// Loop through the selected <td> elements and get the name attribute of each input field inside them
			$requiredTds.each(function() {
			  var $inputs = $(this).find('input, select, textarea');
			  $inputs.each(function() {
			    var inputName = $(this).attr('name');
			    if (inputName !== undefined) {
			    	var $labelTr = $(this).closest('tr');
                	var labelText = $labelTr.find('label.label-tag').text().trim();
                	var ftype = "other";
                	if($labelTr.has('input[type="email"]').length){
                		ftype = "email";
                	} else if (($labelTr.has('input[type="url"]').length)) {
                		ftype = "url";
                	}
					inputNames.push(inputName);
					// Push the  req field data to the array
                	reqFields_data.push({ name: inputName, label: labelText, type: ftype });
				}
			  });
			});
			// Make the array of input names unique
			var filteredNames = Array.from(new Set(inputNames));
			var req_fields = [];

			for (var i = 0; i < filteredNames.length; i++) {
				var flag = 0;

				for (var j = 0; j < formData.length; j++) {
				    if (formData[j].name == filteredNames[i]) {
				        if(formData[j].value != ""){
							flag = 1;
						}
					}
				}
				if(!flag){
					req_fields.push(filteredNames[i]);
				}
			}

			if (addToCartBtn.hasClass('notincart')) {
				addToCartBtn.removeClass('notincart');
			}
			if(req_fields.length !== 0){
				var reqLabels = [];
				for (var i = 0; i < req_fields.length; i++) {
			    	for (var j = 0; j < reqFields_data.length; j++){
			    		if(req_fields[i] == reqFields_data[j].name){
			    			reqLabels.push(reqFields_data[j].label);
			    			break;
			    		}
					}
				}
				if(reqLabels.length === 1){
					alert(reqLabels + "  is a required field");
				}else{
					alert(reqLabels.join("\n ") + "  - are required fields");
				}
				addToCartBtn.removeClass('loading');
				addToCartBtn.addClass('notincart');
			} else {
				var fields_data = [];

				$table.find('tr').each(function() {
				  $(this).find('input[type="email"], input[type="url"]').each(function() {
				    var fieldName = $(this).attr('name');
				    var fieldType = $(this).attr('type');
				    fields_data.push({ name: fieldName, type: fieldType });
				  });
				});
				if(fields_data.length !== 0){
					validate_email_url(fields_data, formData);
				}
			}
		});
	}

	function validate_email_url(fields_data, formData) {
		for(var i = 0; i < fields_data.length; i++ ){
			for (var j = 0; j < formData.length; j++) {
				if(fields_data[i].name == formData[j].name){
					if(formData[j].value != ""){
						if(fields_data[i].type == 'email'){
							if(!isEmail(formData[j].value)){
								// alert("Warning: Added email is not valid!");
								showMessage("Added email is not valid!");
								return false;
							}
						}else if(fields_data[i].type == 'url') {
							if(!isUrl(formData[j].value)){
								//alert("Warning: Added URL is not valid!");
								showMessage("Added URL is not valid!");
								return false;
							}
						}
					}
				}
			}
		}
	}

	function isEmail(value) {
		const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
		return emailRegex.test(value);
	}

	function isUrl(value) {
		const urlRegex = /^(ftp|http|https):\/\/[^ "]+$/;
		return urlRegex.test(value);
	}

	function showMessage(message) {
	  // create a new div element for the message box
	  var box = document.createElement("div");
	  // set the CSS styles for the message box
	  box.style.position = "fixed";
	  box.style.top = "50%";
	  box.style.left = "50%";
	  box.style.transform = "translate(-50%, -50%)";
	  box.style.padding = "20px";
	  box.style.backgroundColor = "#fff";
	  box.style.boxShadow = "0 0 10px rgba(0, 0, 0, 0.5)";
	  box.style.zIndex = 99999;
	  // create a new p element for the message text
	  var messageText = document.createElement("p");
	  messageText.style.margin = "0";
	  messageText.innerHTML = "<span style='color: yellow;'>&#9888;<b>Warning: </b> </span>" + message;
	  box.appendChild(messageText);
	  // add the message box to the document
	  document.body.appendChild(box);
	  // fade out the message box after 4 seconds
	  setTimeout(function() {
	    box.style.opacity = "0";
	    setTimeout(function() {
	      document.body.removeChild(box);
	    }, 2000);
	  }, 3000);
	}



	function check_oceanwp_quickview_opened() {
      var qv_modal = $('#owp-qv-wrap');
      if(qv_modal.hasClass('is-visible')){
        	initialize_thwepof();
			oceanwp_qv_field_validating_notice();
      }else {
          setTimeout(function(){
          	check_oceanwp_quickview_opened();
          }, 1000);
      }
    }

    function apply_input_masking(elm){
    	var data = $(elm).data('mask-pattern');
    	var alias_items = ['datetime','numeric','cssunit','url','IP','email','mac','vin'];

    	if($.inArray(data, alias_items) !== -1){
    		$(elm).inputmask({
	    		"alias": data,
	    	});
    	}else{
    		$(elm).inputmask({
	    		"mask": data,
	    	});
    	}
    }

    function thwepofviewpassword(elm){
    	var icon = $(elm);
    	var parent_elm = icon.closest('.thwepof-password-field');
    	var input = parent_elm.find('input');

    	if(icon.hasClass('dashicons-visibility')){
    		input.attr("type", "text");
    		icon.addClass('dashicons-hidden').removeClass('dashicons-visibility');
    	}else if(icon.hasClass('dashicons-hidden')){
    		input.attr("type", "password");
    		icon.addClass('dashicons-visibility').removeClass('dashicons-hidden');
    	}
    }

    function display_range_value(elm){
			var range_input = $(elm);
			var range_val = range_input.val();
			var width = range_input.width();
			var min_attr =  range_input.attr('min');
			var max_attr =  range_input.attr('max');

			const min = min_attr ? min_attr : 0;
			const max = max_attr ? max_attr : 100;
			const position = Number(((range_val - min) * 100) / (max - min));

			var display_div = range_input.siblings('.thwepof-range-val');
			display_div.html(range_val);
			var display_div_width = display_div.innerWidth();

			var left_position;
			var slider_position = width * position/100;

			if((width - slider_position) < display_div_width/2){
				left_position = 'calc('+ 100 +'% - '+ display_div_width +'px)';
			}else if(slider_position < display_div_width/2){
				left_position = '0px';
			}else{
				left_position = 'calc('+ position +'% - '+ display_div_width/2 +'px)';
			}

			display_div.css('left', left_position);
		}
	
	/***----- INIT -----***/
	initialize_thwepof();
	
	if(thwepof_public_var.is_quick_view == 'flatsome'){
		$(document).on('mfpOpen', function() {
			initialize_thwepof();

			$.magnificPopup.instance._onFocusIn = function(e) {
			    if( $(e.target).hasClass('ui-datepicker-month') ) {
			        return true;
			    }
			    if( $(e.target).hasClass('ui-datepicker-year') ) {
			        return true;
			    }
			    $.magnificPopup.proto._onFocusIn.call(this,e);
			};
		});
	}else if(thwepof_public_var.is_quick_view == 'yith'){
		$(document).on("qv_loader_stop", function() {
			initialize_thwepof();

		});
	}else if(thwepof_public_var.is_quick_view == 'astra'){ //Premium feature of Astra
		$(document).on("ast_quick_view_loader_stop", function() {
			initialize_thwepof();

		});
	}else if(thwepof_public_var.is_quick_view == 'oceanwp'){
		$(document).on('click', '.owp-quick-view', function(e) {
			check_oceanwp_quickview_opened();
		});
	}


	return {
		initialize_thwepof : initialize_thwepof,
		thwepofviewpassword : thwepofviewpassword,
	};

}(window.jQuery, window, document));

function thwepofViewPassword(elm){
	thwepof_public.thwepofviewpassword(elm);
}

function thwepof_init(){
	thwepof_public.initialize_thwepof();
}
