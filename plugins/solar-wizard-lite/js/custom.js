jQuery(document).ready(function($){
	
	jQuery('.select_wizard_form input[type="text"]').on("keypress", function (e) {            
		if (e.keyCode == 13) {
			// Cancel the default action on keypress event
			e.preventDefault(); 
			var sf = jQuery(this).closest( ".select_wizard_form" );
			sf.find('.select-wizard-btn').trigger('click');
		}
	});
	
	jQuery('.sw_form input[name="address"]').on("keypress", function (e) {            
		if (e.keyCode == 13) {
			// Cancel the default action on keypress event
			e.preventDefault(); 
			var cform = jQuery(this).closest( "fieldset" );
			cform.find('.next.action-button').trigger('click');
		}
	});
	
	jQuery('.sw_form .input-enter').on("keypress", function (e) {            
		if (e.keyCode == 13) {
			// Cancel the default action on keypress event
			e.preventDefault(); 
			var cform = jQuery(this).closest( "fieldset" );
			cform.find('.next.action-button').trigger('click');
		}
	});

	$( ".sw_form" ).each(function( index ) {
		//console.log( index + ": " + $( this ).text() );
		$(this).find('.step-count .count_end').text($(this).find('.progressbar li').length);
	});
	
	function solwzd_validateSelectWizardForm(formID){
		var vv = jQuery("#"+formID).validate({
			onkeyup: false,
			//onfocusout: false,
				rules: {
				wizard_selection: {
				  required: true
				},
				select_wizard_fname: {
					required: true
				},
				select_wizard_lname: {
					required: true
				}
			},
			errorElement: "span",
			errorClass: "help-inline-error",
			errorPlacement: function(error, element) {
				if (element.attr("name") == "wizard_selection") {
					error.insertAfter("#"+formID+" .selw-error");
				} else {
					error.insertAfter(element);
				}
			},
			messages: {
				select_wizard_lname: 'Please enter last name',
				select_wizard_fname: 'Please enter first name',
				wizard_selection: 'Please select wizard option to start the journey',
			},
			submitHandler: function(form) {
				var showWizard = $(form).find("input[name='wizard_selection']:checked").val();
				$('#' + showWizard).css('display', 'block');
				$(form).closest( '.wizard_selection_card' ).css('display', 'none');
				var selectedForm = $('#' + showWizard);
				selectedForm.find('input[name="username"]').val($(form).find('input[name="select_wizard_fname"]').val() + ' ' + $(form).find('input[name="select_wizard_lname"]').val());
				selectedForm.find('input[name="firstname"]').val($(form).find('input[name="select_wizard_fname"]').val());
				selectedForm.find('input[name="lastname"]').val($(form).find('input[name="select_wizard_lname"]').val());
				selectedForm.find('.username').text(selectedForm.find('input[name="username"]').val());
				selectedForm.find('.firstname').text(selectedForm.find('input[name="firstname"]').val());
				solwzd_submitQuote(selectedForm);
			}
		});
		return vv;
	}
	
	$.validator.addMethod('filesize', function (value, element, param) {
		var length = ( element.files.length );
		var fileSize = 0;
		var result = true;
		if (length > 0) {
		   for(var i = 0; i < length; i++) {	
				fileSize = element.files[i].size; // get file size
				result = this.optional( element ) || fileSize <= param;
				if(result == false){
					return result;
				}
		   }
		   if(result == true){
				return result;
		   } 
		}
		else {
			return result;
		}
		//return this.optional(element) || (element.files[0].size <= param)
	}, 'File size must be less than {0}');
	
	jQuery.validator.addMethod("extension", function(value, element, param) {
		param = typeof param === "string" ? param.replace(/,/g, '|') : "png|jpe?g|gif";
		var allowed_ext = param.split("|");
		var length = ( element.files.length );
		console.log(allowed_ext);
		var result = true;
		if (length > 0) {
		   for(var i = 0; i < length; i++) {	
				var ext = element.files[i].name.split('.').pop(); // get file size
				console.log(ext);
				result = this.optional( element ) || allowed_ext.includes(ext);
				if(result == false){
					return result;
				}
		   }
		   if(result == true){
				return result;
		   } 
		}
		else {
			return result;
		}	   
	}, jQuery.format("Please upload files having ({0}) a valid extension."));
	
	$(".sw_form .datepicker").datepicker({
		minDate : 'today'
	});
	
	$('.wizard_selection_card .box').click(function(){
		var radio_box = $(this).parent().parent();
		$(radio_box).find('.box').removeClass('active');
		$(this).find('input[type="radio"]').prop('checked', true).trigger('change');
		if($(this).find('input[type="radio"]').prop('checked') == true){
			$(this).addClass('active');
		} else {
			$(this).removeClass('active');
		}
		$('.select-wizard-btn').removeClass('lite-wizard').removeClass('full-wizard');
		
		var formId = $(this).find('input[type="radio"]:checked').val();
		if($("form#" + formId).hasClass("wizard_full")){
			$('.select-wizard-btn').addClass('full-wizard');
		} else {
			$('.select-wizard-btn').addClass('lite-wizard');
		}
	});
	
	$('.select-wizard-btn').click(function(e){
		var sf = $(this).closest( ".select_wizard_form" );
		var b = solwzd_validateSelectWizardForm(sf.attr('id'));
		if(b.form()){
			$(sf).trigger('submit');
		}
	});
	
	$('.sw_form .box').click(function(){
		var radio_box = $(this).parent().parent();
		$(radio_box).find('.box').removeClass('active');
		$(this).find('input[type="radio"]').prop('checked', true).trigger('change');
		if($(this).find('input[type="radio"]').prop('checked') == true){
			$(this).addClass('active');
		} else {
			$(this).removeClass('active');
		}
	});

	jQuery('.sw_form .fill_blank').change(function(){
		if($(this).val() != ''){
			$(this).closest( ".sw_form" ).find('span.blank').text($(this).val());
		} else {
			$(this).closest( ".sw_form" ).find('span.blank').html('&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
		}
	});
	
	if(jQuery('.sw_google_autocomplete_address').length && jQuery('.sw_google_autocomplete_address').length > 0){	
		jQuery('.sw_google_autocomplete_address').each(function( index ) {
			if(jQuery(this).val() != ''){
				var add_id = jQuery(this).val();
				const input = document.getElementById(add_id);
				jQuery('#'+add_id).attr('autocomplete', 'none');
				const autocomplete = new google.maps.places.Autocomplete(input);
				
				const input_confirm = document.getElementById(add_id + "_sw_confirm_address");
				if (input_confirm === null) { return; }
				jQuery("#" + add_id + "_sw_confirm_address").attr('autocomplete', 'none');
				const autocomplete_confirm = new google.maps.places.Autocomplete(input_confirm);
			}
		});
	}

	jQuery('.sw_form input[name="getting_best"]').change(function(){
		$(this).closest( ".sw_form" ).find('span.blank').text($(this).val());
	});

	jQuery('.sw_form .trigger-next').click(function(e){
		e.preventDefault();
		$(this).closest('fieldset').find(".next").trigger('click');
	});
	jQuery('.sw_form .calculate-cost').click(function(e){
		e.preventDefault();
		$(this).closest('fieldset').find(".next").trigger('click');
	});

	jQuery( ".sw_form .slider-range" ).slider({
		range: false,
		min: 25,
		max: 1500,
		values: [ 1 ],
		create: function(event, ui){
			var handle = jQuery(this).find('.ui-slider-handle');
			var bubble = jQuery('<div class="valuebox">'+ $(this).closest( ".sw_form" ).find(".sw_currency_symbol" ).val() + 25 +'</div>');
			$(this).closest( ".sw_form" ).find(".sw_monthly_bill_label").html($(this).closest( ".sw_form" ).find(".sw_currency_symbol" ).val() + 25);
			$(this).closest( ".sw_form" ).find(".sw_monthly_bill" ).val( 25 );
			handle.append(bubble);
		},
		slide: function( event, ui ) {
			$(this).closest( ".sw_form" ).find(".sw_monthly_bill" ).val( ui.value);
			$(this).closest( ".sw_form" ).find(".sw_monthly_bill_label").html($(this).closest( ".sw_form" ).find(".sw_currency_symbol" ).val()+ui.value);
			ui.handle.childNodes[0].innerHTML = $(this).closest( ".sw_form" ).find(".sw_currency_symbol" ).val() + ui.value;
		}
	});
	
	var current_fs, next_fs, previous_fs; //fieldsets
	var opacity;
	function solwzd_validateForm(formID){
	var v = jQuery("#"+formID).validate({
			onkeyup: false,
			//onfocusout: false,
				rules: {
				username: {
				  required: true
				},
				motivate_option: {
				  required: true
				},
				more_about: {
				  required: true
				},
				getting_best: {
				  required: true
				},
				battery_storage: {
				  required: true
				},
				address: {
				  required: true
				},
				monthly_bill: {
				  required: true,
				  number: true
				},
				acknowledge: {
					required: true
				},
				email: {
					required: true,
					email: true,
				},
				phone:{
					required: true
				},
				describe_you: {
					required: true
				},
				system_purchase_plan: {
					required: true
				},
				confirm_address: {
					required: true
				},
				confirm_address_check: {
					required: true
				},
				communication_method: {
					required : true
				},
				communication_details: {
					required : true
				},
				date: {
					required: true
				},
				"bill[]": {
                     required: false,
                     extension: "jpg|jpeg|png|gif|bmp|pdf",
                     filesize: 5120000 
				}
			},
			errorElement: "span",
			errorClass: "help-inline-error",
			errorPlacement: function(error, element) {
				if (element.attr("name") == "acknowledge") {
					error.insertAfter(".ack-error");
				} else if(element.attr("name") == "confirm_address"){
					error.insertAfter(".ca-error");
				} else if(element.attr("name") == "username"){
					error.insertAfter(".name-error");
				} else if(element.attr("name") == "motivate_option"){
					error.insertAfter(".motivation-error");
				} else if(element.attr("name") == "more_about"){
					error.insertAfter(".bd-error");
				} else if(element.attr("name") == "getting_best"){
					error.insertAfter(".fb-error");
				} else if(element.attr("name") == "battery_storage"){
					error.insertAfter(".bs-error");
				} else if(element.attr("name") == "describe_you"){
					error.insertAfter(".dy-error");
				} else if(element.attr("name") == "system_purchase_plan"){
					error.insertAfter(".ps-error");
				} else if(element.attr("name") == "communication_method"){
					error.insertAfter(".cm-error");
				} else {
				  error.insertAfter(element);
				}
			},
			messages: {
				yourphone: 'Please enter valid phone in 10 digit',
				username: 'Please enter your name',
				confirm_address: 'Please Confirm Address.',
				"bill[]": {
					 extension: "Invalid file type.",
                     filesize: "File size is too large." 
				}
			},
			submitHandler: function(form) {
				$(form).find('.upload-bill-btn').attr('disabled', true);
				$(form).find('fieldset.11').find(".loader").removeClass('hidden');
				$(form).find('p.msg').removeClass('d-none');
				$(form).find('p.msg').text('Please wait...');				
				solwzd_submitQuote(form, 'final', 'full');
			}
		});
	return v;
	}
	
	function solwzd_submitQuote(cform, fcount='', email_type=''){
		var formData = new FormData();
		formData.append('sw_currency_symbol',$(cform).find(".sw_currency_symbol" ).val());
		formData.append('quote_id',$(cform).find("input[name='sw_quote_id']").val());
		formData.append('username',$(cform).find("input[name='username']").val());
		formData.append('firstname',$(cform).find("input[name='firstname']").val());
		formData.append('lastname',$(cform).find("input[name='lastname']").val());
		if($(cform).find("input[name='motivate_option']").length > 0){
			formData.append('motivate_option',$(cform).find("input[name='motivate_option']:checked").val());
		}
		if($(cform).find("input[name='more_about']").length > 0){
			formData.append('more_about',$(cform).find("input[name='more_about']:checked").val());
		}
		if($(cform).find("input[name='getting_best']").length > 0){
			formData.append('getting_best',$(cform).find("input[name='getting_best']:checked").val());
		}
		formData.append('address',$(cform).find("input[name='address']").val());
		if($(cform).find("input[name='battery_storage_option']").length == 0 && $(cform).find("input[name='describe_you']:checked").val() == 'Residential'){
			formData.append('battery_storage',$(cform).find("input[name='battery_storage']:checked").val());
		}
		formData.append('monthly_bill',$(cform).find("input[name='monthly_bill']").val());
		formData.append('acknowledge',$(cform).find("input[name='acknowledge']:checked").val());
		formData.append('opt_in',$(cform).find("input[name='opt_in']:checked").val());
		formData.append('email',$(cform).find("input[name='email']").val());
		formData.append('phone',$(cform).find("input[name='phone']").val());
		if($(cform).find("input[name='military']").length > 0){
			var military = $(cform).find("input[name='military']:checked").is(':checked') ? 'Yes' : 'No' ;
			formData.append('military', military);
		} 
		if($(cform).find("input[name='nurse']").length > 0){
			var nurse = $(cform).find("input[name='nurse']:checked").is(':checked') ? 'Yes' : 'No' ;
			formData.append('nurse',nurse);
		} 	
		formData.append('describe_you',$(cform).find("input[name='describe_you']:checked").val());
		formData.append('system_purchase_plan',$(cform).find("input[name='system_purchase_plan']:checked").val());
		var learn_battery_storage = $(cform).find("input[name='learn_battery_storage']:checked").is(':checked') ? 'Yes' : 'No' ;
		formData.append('learn_battery_storage',learn_battery_storage);
		
		formData.append('panel_required',$(cform).find('input[name="panel_required"]').val());
		formData.append('system_size',$(cform).find('input[name="system_size"]').val());
		formData.append('potential_savings',$(cform).find('input[name="potential_savings"]').val());
		
		formData.append('communication_method',$(cform).find("input[name='communication_method']:checked").val());
		formData.append('communication_details',$(cform).find("input[name='communication_details']").val());
		
		formData.append('panel_required',$(cform).find('input[name="panel_required"]').val());
		formData.append('system_size',$(cform).find('input[name="system_size"]').val());
		formData.append('potential_savings',$(cform).find('input[name="potential_savings"]').val());
		
		formData.append('date',$(cform).find("input[name='date']").val());
		formData.append('action','solwzd_submit_quote');
		formData.append('form_used',$(cform).find("input[name='form_used']").val());
		if(email_type != ''){
			formData.append('email_type',email_type);
		}
		if(fcount == 'final'){
			formData.append('final_step',true);
		}
		
		var files_data = $(cform).find('.files-data'); // The <input type="file" /> field
		// Loop through each data and create an array file[] containing our files data.
		$.each($(files_data), function(i, obj) {
			$.each(obj.files,function(j,file){
				formData.append('files[' + j + ']', file);
			})
		});
		
		$.ajax({
			url: ajax_object.ajax_url,
			type: "POST",
			dataType: 'json',
			contentType: false,
			processData: false,
			data: formData,
			success: function(response) {
			  if(response.result == 'true'){
					$(cform).find(".sw_quote_id").val(response.quote_id);
					if(fcount == 'final'){
						$(cform).find(".final-step").trigger('click');
						$(cform).find('fieldset.11').find(".loader").addClass('hidden');
					}
			  } else {
				$(cform).find('.upload-bill-btn').attr('disabled', false);
				$(cform).find('p.msg').html('Something went wrong.' + response.file_repsonse);
				if(fcount == 'final'){
					$(cform).find('fieldset.11').find(".loader").addClass('hidden');
				}
			  }
		  }            
		});
	}
	
	$(".next").click(function(){
		var cform = $(this).closest( ".sw_form" );
		var v = solwzd_validateForm(cform.attr('id'));
		if(v.form()){
			current_fs = $(this).parent();
			next_fs = $(this).parent().next();
			var cform = $(this).closest( ".sw_form" );
			
			if(current_fs.hasClass('calculate-panel')){
				cform.find('input[name="confirmaddress"]').val(cform.find('input[name="address"]').val());
				$(next_fs).find(".loader").removeClass('hidden');
				if(cform.find('input[name="battery_storage"]:checked').length > 0){
					battery_storage = cform.find('input[name="battery_storage"]:checked').val();
				} else {
					battery_storage = cform.find('input[name="battery_storage"]').val();
				}
				
				var data = {
					'action': 'solwzd_calculate_panel',
					'battery_storage': battery_storage,
					'monthly_bill': cform.find('input[name="monthly_bill"]').val(),
					'describe_you': cform.find("input[name='describe_you']:checked").val()
				};
				jQuery.ajax({
					url: ajax_object.ajax_url,
					type: 'post',
					dataType: 'json',
					success: function (data) {
						cform.find('.panel-required').html('<span class="sw_pr">' + data.panel_required + '</span> WATT PANELS');
						cform.find('.system-size').html('<span class="sw_ss">' + data.system_size.toLocaleString() +'</span> kW');
						cform.find('.potential-savings').html('<span class="sw_ps">'+cform.find(".sw_currency_symbol" ).val()+data.potential_savings.toLocaleString()+'</span> Over 30 Years');
						cform.find('input[name="panel_required"]').val(data.panel_required);
						cform.find('input[name="system_size"]').val(data.system_size.toLocaleString());
						cform.find('input[name="potential_savings"]').val(cform.find(".sw_currency_symbol" ).val()+data.potential_savings.toLocaleString());
						if(battery_storage == "solar_with_storage"){
							cform.find('.b-storage').css('display', 'block');
							cform.find('.sw_battery_price').val(data.battery.price);
							cform.find('.storage-battery').html('<span class="sw_sb">' + data.battery.noofbattery + ' ' + data.battery.name + ' </span> Batteries');
							cform.find('input[name="storage_battery"]').val(data.battery.noofbattery + ' ' + data.battery.name);
							cform.find("input[name='learn_battery_storage']").prop('checked', false);
						} else {
							cform.find('.b-storage').css('display', 'none');
							cform.find('.storage-battery').html('');
							cform.find("input[name='learn_battery_storage']").prop('checked', true);
						}
						if(data.lease_option == 'yes'){
							cform.find('.lease-option').removeClass('hidden');
						} else {
							cform.find('.lease-option').addClass('hidden');
						}
						if(data.panel_image != ''){
							cform.find('.panel-image img').attr('src', data.panel_image);
							cform.find('.panel-image img').css('display', 'block');
						} else {
							cform.find('.panel-image img').css('display', 'none');
						}
						$(next_fs).find(".loader").addClass('hidden');
						$(next_fs).find(".next").trigger('click');
					},
					data: data
				});
			}
			if(next_fs.hasClass('calculate-cost-final')){
				$(next_fs).find(".loader").removeClass('hidden');
				$(next_fs).find(".result").addClass('hidden');
				$(next_fs).find(".next").addClass('hidden');
				$(next_fs).find(".group").addClass('hidden');
				cform.find('.system-result').addClass('hidden');
				$(next_fs).find(".waiting-card").removeClass('hidden');
				$(next_fs).find(".wait-complete").addClass('hidden');
				cform.find('button.schedule-consultant-btn').addClass('hidden');
				if(cform.find('input[name="battery_storage"]:checked').length > 0){
					battery_storage = cform.find('input[name="battery_storage"]:checked').val();
				} else {
					battery_storage = cform.find('input[name="battery_storage"]').val();
				}
				var data = {
					'action': 'solwzd_count_incentive_with_cost',
					'applied': cform.find("input[name='describe_you']:checked").val(), // We pass php values differently!
					'type': cform.find("input[name='system_purchase_plan']:checked").val(),
					'battery_storage': battery_storage,
					'battery_price': cform.find('.sw_battery_price').val(),
					'monthly_bill': cform.find('input[name="monthly_bill"]').val(),
					'system_purchase_plan': cform.find('input[name="system_purchase_plan"]:checked').val(),
					'describe_you': cform.find("input[name='describe_you']:checked").val()
				};
				jQuery.ajax({
					url: ajax_object.ajax_url,
					type: 'post',
					dataType: 'json',
					success: function (data) {
						cform.find('.system-result').addClass('hidden');
						if( cform.find('input[name="system_purchase_plan"]:checked').val() == 'Cash' ){
						
							cform.find('.system-cost').text(cform.find(".sw_currency_symbol" ).val() + data.system_cost_low.toLocaleString() + ' - ' + cform.find(".sw_currency_symbol" ).val() + data.system_cost_high.toLocaleString());
							cform.find('.incentive').text(cform.find(".sw_currency_symbol" ).val() + data.total_incentive_low.toLocaleString() + ' - ' + cform.find(".sw_currency_symbol" ).val() + data.total_incentive_high.toLocaleString());
							cform.find('.net-cost').text(cform.find(".sw_currency_symbol" ).val() + data.net_cost_low.toLocaleString() + ' - ' + cform.find(".sw_currency_symbol" ).val() + data.net_cost_high.toLocaleString());
							cform.find('.cash_result').removeClass('hidden');
						
						} else if( cform.find('input[name="system_purchase_plan"]:checked').val() == 'Finance' ){
							cform.find('.utility-bill-per-month').text(cform.find(".sw_currency_symbol" ).val() + data.high_per_of_saving.toFixed(0) + ' - ' + cform.find(".sw_currency_symbol" ).val() + data.low_per_of_saving.toFixed(0));
							cform.find('.system-cost').text(cform.find(".sw_currency_symbol" ).val() + data.system_cost_low.toLocaleString() + ' - ' + cform.find(".sw_currency_symbol" ).val() + data.system_cost_high.toLocaleString());
							cform.find('.incentive').text(cform.find(".sw_currency_symbol" ).val() + data.total_incentive_low.toLocaleString() + ' - ' + cform.find(".sw_currency_symbol" ).val() + data.total_incentive_high.toLocaleString());
							cform.find('.loan_term').text(data.sw_loan_term);
							cform.find('.loan_rate').text(data.sw_loan_rate);
							cform.find('.loan_credit_score').text(data.sw_loan_credit_score);
							cform.find('.net-cost').text(cform.find(".sw_currency_symbol" ).val() + data.net_cost_low.toLocaleString() + ' - ' + cform.find(".sw_currency_symbol" ).val() + data.net_cost_high.toLocaleString());
							cform.find('.financing_result').removeClass('hidden');
							
						} else if( cform.find('input[name="system_purchase_plan"]:checked').val() == 'Lease' ){
							
							cform.find('.utility-bill-per-month').text(cform.find(".sw_currency_symbol" ).val() + data.high_per_of_saving.toFixed(0) + ' - ' + cform.find(".sw_currency_symbol" ).val() + data.low_per_of_saving.toFixed(0));
							
							cform.find('.lease_term').text(data.sw_lease_term);
							cform.find('.lease_rate').text(data.sw_lease_rate);
							cform.find('.lease_credit_score').text(data.sw_lease_credit_score);
							cform.find('.lease_result').removeClass('hidden');
						}
						cform.find('input[name="system_cost"]').val(cform.find(".sw_currency_symbol" ).val() + data.system_cost_low.toLocaleString() + ' - ' + cform.find(".sw_currency_symbol" ).val() + data.system_cost_high.toLocaleString());
						cform.find('input[name="incentive"]').val(cform.find(".sw_currency_symbol" ).val() + data.total_incentive_low.toLocaleString() + ' - ' + cform.find(".sw_currency_symbol" ).val() + data.total_incentive_high.toLocaleString());
						cform.find('input[name="utility_bill_per_month"]').val(cform.find(".sw_currency_symbol" ).val() + data.high_per_of_saving.toLocaleString() + ' - ' + cform.find(".sw_currency_symbol" ).val() + data.low_per_of_saving.toLocaleString());
						$(next_fs).find(".wait-complete").removeClass('hidden');
						$(next_fs).find(".loader").addClass('hidden');
						$(next_fs).find(".waiting-card").addClass('hidden');
						$(next_fs).find(".next").removeClass('hidden');
						$(next_fs).find(".group").removeClass('hidden');
						cform.find('button.schedule-consultant-btn').removeClass('hidden');
					},
					data: data
				});
			}
			if(!current_fs.hasClass('final-step-data') && !current_fs.hasClass('waiting')){
				if(current_fs.hasClass('send-email-partial')){
					solwzd_submitQuote(cform,'', 'after_system_size');
				} else {
					solwzd_submitQuote(cform);
				}
			}
			//Add Class Active
			cform.find('.step-count .count').text(cform.find("fieldset").index(next_fs)+1);
			cform.find(".progressbar li").eq(cform.find("fieldset").index(next_fs)).addClass("active");

			//show the next fieldset
			next_fs.show();
			//hide the current fieldset with style
			current_fs.animate({opacity: 0}, {
				step: function(now) {
					// for making fielset appear animation
					opacity = 1 - now;

					current_fs.css({
					'display': 'none',
					});
					next_fs.css({'opacity': opacity});
					if(cform.find("input[name='describe_you']:checked").val() == 'Residential'){
						if(next_fs.hasClass('residential-skip')){
							$(next_fs).find(".next").trigger('click');
						}
					}
				},
				duration: 600
			});
		}
	});
	
	
	$(".previous").click(function(){
		current_fs = $(this).parent();
		previous_fs = $(this).parent().prev();
		var cform = $(this).closest( ".sw_form" );
		previous_fs.show();
		//Remove class active
		cform.find('.step-count .count').text(cform.find("fieldset").index(previous_fs)+ 1);
		cform.find(".progressbar li").eq(cform.find("fieldset").index(current_fs)).removeClass("active");

		//show the previous fieldset
		//hide the current fieldset with style
		current_fs.animate({opacity: 0}, {
			step: function(now) {
				// for making fielset appear animation
				opacity = 1 - now;

				current_fs.css({
					'display': 'none'
				});
				previous_fs.css({'opacity': opacity});
				if(previous_fs.hasClass('waiting')){
					$(previous_fs).find(".previous").trigger('click');
				}
				if(cform.find("input[name='describe_you']:checked").val() == 'Residential'){
					if(previous_fs.hasClass('residential-skip')){
						$(previous_fs).find(".previous").trigger('click');
					}
				}
			},
			duration: 600
		});
		
		
	});

	$('.radio-group .radio').click(function(){
		$(this).parent().find('.radio').removeClass('selected');
		$(this).addClass('selected');
	});

	$(".submit").click(function(){
		return false;
	})
	
});