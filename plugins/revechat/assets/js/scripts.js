jQuery.noConflict();
(function($) {
$(document).ready(function() {
	var baseUrl = 'https://app.revechat.com/';
	// var baseUrl = 'https://staging-dev.revechat.com:5443/dashboard/';
	var ajaxMessage = $('.ajax_message');
	
	var newAccountInput = $('#newAccountInput');
	var newAccountForm = $("#newAccountForm");
	var newAccountBtn = $("#new-account-submit");
	var newAccountID = newAccountForm.find('input[name=revechat_aid]');
	
	var fullName = newAccountForm.find('#fullName');
	var emailAddress = newAccountForm.find('#emailAddress');
	var password = newAccountForm.find('#password');
	var phoneTaker = newAccountForm.find('#phoneTaker');
	var phoneNo = newAccountForm.find('#phoneNo');
	var companyWebsite = newAccountForm.find('#companyWebsite');
	var selectedCountryData = null;

	var existingAccountInput = $('#existingAccountInput');
	var existingAccountForm = $("#existingAccountForm");
	var existingAccountBtn = $("#existingAccountBtn");
	var existingAccountID = existingAccountForm.find('input[name=revechat_aid]');
	var existingAccountEmail = existingAccountForm.find('#existingAccountEmail');


	var toggleForms = function ()
	{
		if ( newAccountInput.is(':checked') )
		{
			existingAccountForm.hide();
			newAccountForm.show();
		}
		else if (existingAccountInput.is(':checked'))
		{
			newAccountForm.hide();
			existingAccountForm.show();
		}
	};
	toggleForms();
	$('#revechat_chooser input').click(toggleForms);

	var signInValidationRules = {

		rules: {
			existingAccountEmail: {
				required: true,
				isValidEmail: true
			}
		},

		messages: {
			existingAccountEmail: {
				required: "Please provide your business email address",
				isValidEmail: "Please provide your real email address"
			}
		},

		submitHandler: function(form) {
			console.log(`newAccountID.val()`,newAccountID.val());
			console.log(`form`,form);

			// if (parseInt((newAccountID.val()) > 0))		
				form.submit();
		}
	}

	var validationRules = {

		rules: {
			password: {
				required: true,
				minlength: 6,
				maxlength: 12
			},
			fullName: {
				required: true
			},
			emailAddress: {
				required: true
			},
			phoneTaker: {
				required: true,
				phoneMinLength: 6,
				maxlength: 12,
				validatePhone: true
			},
			companyWebsite: {
				required: true,
				// excludeCommonSites: true,
				validateURL: true,
			}
		},

		messages: {

			password: {
				required: "The password length must be between 6 to 12 characters",
				minlength: "The password length must be between 6 to 12 characters",
				maxlength: "The password length must be between 6 to 12 characters"
			},
			fullName: {
				required: "Please provide your name"
			},
			emailAddress: {
				required: "Please provide your business email address"
			},
			phoneTaker: {
				required: "The phone number length must be between 6 to 12 digits",
				phoneMinLength: "The phone number length must be between 6 to 12 digits",
				maxlength: "The phone number length must be between 6 to 12 digits",
				validatePhone: "The phone number length must be between 6 to 12 digits"
			},
			companyWebsite: {
				required: "Please enter the website, where REVE Chat will be integrated",
				validateURL: "This is not a valid entry. Please enter the website, where REVE Chat will be integrated",
				// excludeCommonSites: "This is not a valid entry. Please enter the website, where REVE Chat will be integrated",
			}

		},

		submitHandler: function(form) {
			console.log(`newAccountID.val()`,newAccountID.val());
			console.log(`form`,form);

			// if (parseInt((newAccountID.val()) > 0))		
				form.submit();
		}
	}

	$("#eye_close").on("click", function() {

		password.attr('type', 'text');
		$("#eye_close").hide();
		$("#eye_open").show();
	});

	$("#eye_open").on("click", function() {

		password.attr('type', 'password');
		$("#eye_open").hide();
		$("#eye_close").show();
	});
	
	

	window.ExistingAccoutVerify = function (response) {
		console.log(`response`,response);
		if (response.error)
		{
			ajaxMessage.removeClass('wait').addClass('message alert').html('Incorrect REVE Chat login.');
			setTimeout(function () { ajaxMessage.slideUp().removeClass('wait message alert').html(''); }, 3000);
			existingAccountEmail.focus();
		}
		else
		{
			if( response.data.account_id ) {

				existingAccountID.val(response.data.account_id);
				existingAccountForm.submit();

			} else {
				console.log(`ExistingAccoutVerify Response Error: `,response);
			}
		}
	}

	function existingAccountFormFunc()
	{

		var isValid = existingAccountForm.valid(signInValidationRules);
		console.log(`isValid`,isValid);
		// if (0) {
		if (isValid) {
			ajaxMessage.removeClass('message').addClass('wait').html('Please wait&hellip;').slideDown();
			var signInUrl = baseUrl +'license/adminId/'+existingAccountEmail.val()+'/?callback=window.ExistingAccoutVerify';

			$.ajax({
			    type: 'GET',
			    dataType: "text",
			    // dataType: "jsonp",
			    url: signInUrl, 
			    // jsonpCallback: "window.ExistingAccoutVerify",
			    success: function(response) {
			        eval(response);
			    },
			    error: function(XMLHttpRequest, textStatus, errorThrown) {
			        ajaxMessage.removeClass('wait').addClass('message alert').html('Unable to Login. Please check internet connection.');
			        setTimeout(function () { ajaxMessage.slideUp().removeClass('wait message alert').html(''); }, 3000);
			    }

			}); // end of ajax saving.
		}
	}
	existingAccountBtn.click(existingAccountFormFunc);


	function newAccountFormFunc()
	{
		var isValid = newAccountForm.valid(validationRules);
		// if (0) {
		if (isValid) {
			ajaxMessage.removeClass('message').addClass('wait').html('Creating new account&hellip;').slideDown();
			var signUpUrl = baseUrl + 'revechat/cms/api/signup.do';

			$.ajax({
				data: { 
					'firstname': fullName.val(), 
					'lastname':' ', 
					'mailAddr': emailAddress.val(), 
					'password': password.val(),
					'phoneNo': phoneNo.val(),
					'companyWebsite': companyWebsite.val(),
			    	'utm_source':'cms', 'utm_content':'wordpress', 'referrer':'https://wordpress.org/'
				},
				type:'POST',
				url:signUpUrl,
				dataType: 'json',
				cache:false,
			    beforeSend: function() { },
				success: function(response) {

					if(response.status == 'success')
					{
						if(response.account_id) {
							newAccountID.val(response.account_id);
							newAccountForm.submit();
						}
						else if( response.accountId) {
							newAccountID.val(response.accountId);
							newAccountForm.submit();
						}
						else {
							response.message = 'Account Id missing Please contact with Revechat Adminstrator.';
						}
						ajaxMessage.removeClass('wait').addClass('message').html(response.message);
						setTimeout(function () { ajaxMessage.slideUp().removeClass('wait message alert').html(''); }, 3000);
					} 
					else {
						ajaxMessage.removeClass('wait').addClass('message alert').html(response.message);
						setTimeout(function () { ajaxMessage.slideUp().removeClass('wait message alert').html(''); }, 3000);
					}
				}, 
				error: function (message) {
					console.log(`message`,message);
					ajaxMessage.removeClass('wait').addClass('message alert').html('Unable to Signup. Please check internet connection.');
					setTimeout(function () { ajaxMessage.slideUp().removeClass('wait message alert').html(''); }, 3000);
				}
			});
		}
	}
	newAccountBtn.click(newAccountFormFunc);

	function setCookie(cname, cvalue, exdays) {
		var d = new Date();
		d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
		var expires = "expires=" + d.toUTCString();
		document.cookie = cname + "=" + cvalue + "; " + expires + "; path=/";
	}


	phoneTaker.intlTelInput({

		initialCountry: "auto",
		separateDialCode: true,
		nationalMode: false,
		autoPlaceholder: true,

		customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
			return "For example: " + selectedCountryPlaceholder;
		},

		geoIpLookup: function(callback) {
			window.geoInfoCallback = function(data) {

				var countryCode = data['result']['country_code'];

				if (countryCode) {
					countryCode = countryCode == "-" ? "us" : countryCode.toLowerCase();
				} else {
					countryCode = "us";
				}
				callback(countryCode);
				setCookie("countryCode", countryCode, 30);
			}
			$.ajax({
				url: "https://location.revechat.com:9090/",
				type: "get",
				async: true,
				success: function(response) {
					eval(response);
				}
			});
		},
		utilsScript: "https://www.revechat.com/wp-content/themes/revechat/js/vendors/utils.js" // just for formatting/placeholders etc
	});

	phoneTaker.on("countrychange", function (e, countryData) {
		selectedCountryData = countryData;
		newAccountForm.validate(validationRules);
		// console.log(`selectedCountryData`,selectedCountryData);
	});

	phoneTaker.on("change", function(e) {

		var me= $(this);
		var currentValue = me.val();
		var regex = new RegExp("^[\+0-9\-\)\( ]+$");
		var phone_number =  phoneTaker.intlTelInput("getNumber");
		var isOK = regex.test(phone_number);
		// console.log(`isOK`,isOK);
		// console.log(`phone_number`,phone_number);
		// console.log(`currentValue`,currentValue);

		if(isOK) {
			phoneNo.val(phone_number);
		} else if ( selectedCountryData == null ) {
			phoneNo.val( currentValue );
		} else {

		}

	});

	phoneTaker.on("keypress", function(e) {

		var theEvent = e || window.event;
		var key = theEvent.keyCode || theEvent.which;

		if ((
			// < "0"  || > 9
			(key < 48 || key > 57) 
			&& !(
				key == 8 	// BACK_SPACE
				|| key == 32  //' '
				|| key == 45  //'-'
				|| key == 41  //')'
				|| key == 40  //'('
				// || key == 9  //TAB
				// || key == 13  // ''
				// ||  key == 37 // "%"
				// || key == 39 // "'"
				// || key == 46 "."
				)
			)) {
		    theEvent.returnValue = false;
		    if (theEvent.preventDefault) theEvent.preventDefault();
		}
	});	


	newAccountForm.validate(validationRules);
	existingAccountForm.validate(signInValidationRules);



	$.validator.addMethod('validatePhone', function() {
		// this is the inteligent validation check provided by the core initTelInput script
		// if ( $REVEChatSignup.$_phoneNoInputFieldSelector.intlTelInput("isValidNumber")){
		// 	return true;
		// }
		// else{
		// 	return false;
		// }

		var phone_number  = phoneTaker.val();
		var regex = new RegExp("^[0-9\-\)\( ]+$");
		var isNumber = regex.test(phone_number);
		// console.log(`isNumber`,isNumber);
		return isNumber;

	}, "Please Enter a valid phone number.");

	$.validator.addMethod('phoneMinLength', function(inputValue, selectorDom, settingValue) {
		// console.log(`inputValue`,inputValue);
		// console.log(`selectorDom`,selectorDom);
		// console.log(`settingValue`,settingValue);

		var realVal = inputValue.replace(/[ \-+\(\)]/g, '');
		// console.log(`realVal.length`,realVal.length);

		if (realVal.length >= settingValue ){
			return true;
		}
		else{
			return false;
		}

	}, "Please enter at least 6 digits without country code");

	$.validator.addMethod('validateURL', function(inputValue, selectorDom, settingValue) {

		var urlregex = new RegExp(/^(?![\.\-\_\+])(((https?1?2?\:\/\/)?www\.)|((https?1?2?\:\/\/)?(www\.))|((https?1?2?\:\/\/)))?[0-9A-Za-z-]+\..+$/, 'i');
		// var urlregex = new RegExp("^(((https?\:\/\/)?www\.)|((https?\:\/\/)?(www\.)))([0-9A-Za-z]+\..+)");
		var ret = urlregex.test(inputValue);
		var arr = inputValue.split('.');

		if( arr[ arr.length-1 ] == ''){
			return false
		}

		if((arr[0].substr(arr[0].length - 3)).toLowerCase() == 'www' && arr.length<3)
			return false		
		
		if(arr.length>4) 
			return false;

		if(arr[ arr.length-1 ].length > 6)
			return false;
		
		return ret;

	}, "Please enter a valid URL.");

	$.validator.addMethod('excludeCommonSites', function(inputValue, selectorDom, settingValue) {

		var urlregex = new RegExp(/^.*?((hotmail)|(facebook)|(yahoo)|(google)|(revechat)|(fb\.com)|(twitter)|(gmail)).*?$/, 'i');
		// var urlregex = new RegExp("^(((https?\:\/\/)?www\.)|((https?\:\/\/)?(www\.)))([0-9A-Za-z]+\..+)");
		var ret = urlregex.test(inputValue);

		return !ret;

	}, "Please enter a valid URL.");

	$.validator.addMethod('isValidEmail', function(inputValue, selectorDom, settingValue) {

		var urlregex = new RegExp(/^([a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+(\.[a-z\d!#$%&'*+\-\/=?^_`{|}~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]+)*|"((([ \t]*\r\n)?[ \t]+)?([\x01-\x08\x0b\x0c\x0e-\x1f\x7f\x21\x23-\x5b\x5d-\x7e\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|\\[\x01-\x09\x0b\x0c\x0d-\x7f\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))*(([ \t]*\r\n)?[ \t]+)?")@(([a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\d\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.)+([a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]|[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][a-z\d\-._~\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]*[a-z\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])\.?$/, 'i');
		// var urlregex = new RegExp("^(((https?\:\/\/)?www\.)|((https?\:\/\/)?(www\.)))([0-9A-Za-z]+\..+)");
		var ret = urlregex.test(inputValue);

		return ret;

	}, "Please enter a real email.");

	function ShowToast(msg,msg_type,snackbar_wrapper, delay, calback_func, calback_params){
		msg_type = msg_type || 'spf-success';
		delay = delay || 3000;
		var auto_snackbar = false;
		if( $("#snackbar").length > 0 ) 
			$("#snackbar").remove();
		if( !snackbar_wrapper ) {
			$("body").append(`<div id="snackbar"><div class="overlay"></div><span class="data"></span></div>`);
			snackbar_wrapper = $("#snackbar");
			auto_snackbar = true;
		}

		if (snackbar_wrapper.data("active")) { return; }
		snackbar_wrapper.slideDown().addClass(msg_type).data("active", true).find('.data').html(msg);

		setTimeout(function() {
			snackbar_wrapper.slideUp().removeClass(msg_type).data("active", false).find('.data').html('');
			if( auto_snackbar ) {
				snackbar_wrapper.remove();
				auto_snackbar = false;
			}

			if(Array.isArray(calback_func)) {
				if( typeof calback_func[1] == "function" ) {
					// callable_func     obj_for_bind     params_for_callable_func
					calback_func[1].call(calback_func[0], calback_params);
				}
			} else if(typeof calback_func == "function") {
				calback_func(calback_params);
			}
		}, delay);           
	}


});
})(jQuery);