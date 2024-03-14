(function ($) {

    $.fn.saIntel={
        initIntellinput:function (options) {
            var default_cc = (typeof sa_country_settings !='undefined' && sa_country_settings['sa_default_countrycode'] && sa_country_settings['sa_default_countrycode']!='') ? sa_country_settings['sa_default_countrycode'] : '';
            var show_flag = (typeof sa_country_settings !=  'undefined' && sa_country_settings['show_flag']) ? sa_country_settings['show_flag'] : "on";                
            var selected_countries             = (typeof sa_intl_warning !=  'undefined' && sa_intl_warning['whitelist_countries']) ? sa_intl_warning['whitelist_countries'] : new Array();
        
            var whitelist_countries = [];
        
            for(var c=0;c<selected_countries.length;c++)
            {
                var v = getCountryByCode(selected_countries[c]);
            
                whitelist_countries.push(v[0].iso2.toUpperCase());
            }
        
            var country= $("#billing_country").val();
        
        
            var default_opt = {
                "separateDialCode": true,
                "nationalMode": true,
                "showFlags": true,
                "formatOnDisplay": false,
                "hiddenInput": "billing_phone",
                "utilsScript": "/utils.js?v=3.3.1",
                "onlyCountries": whitelist_countries
            };
			
            if(country != undefined)
			{
				default_opt['initialCountry'] = country;
			}
			if(show_flag !== 'on')
			{
				default_opt['showFlags'] = false;
			}
			if(selected_countries.length == 1)
			{
				default_opt['allowDropdown'] = false;
			}
            if(default_cc!='') {
                var object = $.extend({},default_opt, options);
            }
            else
            {
                var object = $.extend(
                    default_opt, {initialCountry: "auto",geoIpLookup: function (success, failure) {
                        $.get("https://ipapi.co/json/").always(
                            function (resp) {
                                var countryCode = (resp && resp.country) ? resp.country : "US";
                                success(countryCode);
                    
                            }
                        ).fail(
                            function () {
                                console.log("ip lookup is not working.");
                            }
                        );
                    }},options
                );
            }        
            return object;
        }
    };
    
    jQuery.fn.saIntellinput = $.fn.saIntel.initIntellinput;
}(jQuery)); 

jQuery(window).on(
    "load",function () {
        var $ = jQuery;
        var country= $("#billing_country").val();
        var invalid_no         = (typeof sa_intl_warning  !=  'undefined' && sa_intl_warning['invalid_no']) ? sa_intl_warning ['invalid_no'] : "Invalid number";
        var invalid_country = (typeof sa_intl_warning  !=  'undefined' && sa_intl_warning['invalid_country']) ? sa_intl_warning['invalid_country'] : "Invalid country code";
        var ppvn             = (typeof sa_intl_warning !=  'undefined' && sa_intl_warning['ppvn']) ? sa_intl_warning['ppvn'] : "Please provide a valid Number";
    
        var errorMap = [invalid_no, invalid_country, ppvn, ppvn, invalid_no];
        $("#billing_phone").after("<p class='error sa_phone_error' style='display:none'></p>");
        $(document).find(".phone-valid").after("<span class='error sa_phone_error' style='display:none'></span>");

        var vars = {};
        var default_cc = (typeof sa_country_settings !='undefined' && sa_country_settings['sa_default_countrycode'] && sa_country_settings['sa_default_countrycode']!='') ? sa_country_settings['sa_default_countrycode'] : '';
        var enter_here = (typeof sa_notices !=  'undefined' && sa_notices['enter_here']) ? sa_notices['enter_here'] : "Enter Number Here";
		 var reset = function (obj) {
              jQuery(obj).parents("form").find(".sa_phone_error").hide();
        
        }; 
        jQuery("#billing_phone, .phone-valid").each(
            function (i,item) {
                jQuery(item).attr('data-id','sa_intellinput_'+i)
                .attr("placeholder", enter_here)
                .intlTelInput("destroy");
                var field_name = jQuery(this).attr('name');
                var object = jQuery(this).saIntellinput({hiddenInput:false});
                vars['sa_intellinput_'+i] = jQuery(this).intlTelInput(object);
				
                var itis = vars['sa_intellinput_'+i];
        
                if(default_cc!='') {
                    var selected_cc = getCountryByCode(default_cc);
                    var show_default_cc = selected_cc[0].iso2.toUpperCase();
                    itis.intlTelInput("setCountry",show_default_cc);
                }
           
				jQuery(this).parents(".iti--separate-dial-code").append('<input type="hidden" name="'+field_name+'">');
        
                itis.on(
                    'countrychange', function (e, countryData) {
						var allow_otp_verification = sa_intl_warning['allow_otp_verification'];
						var allow_otp_countries = sa_intl_warning['allow_otp_countries'];
						if("on" == allow_otp_verification && "" !== allow_otp_countries && !sa_intl_warning['post_verify'])
						{
							var buyer_checkout_otp = sa_intl_warning['buyer_checkout_otp']; 	
							var country_code = jQuery(this).intlTelInput("getSelectedCountryData").dialCode;
							if("on" == buyer_checkout_otp && sa_intl_warning['is_checkout']){
							if(jQuery.inArray(country_code,allow_otp_countries)== -1)	{						
								removeShortcode();
								jQuery("#smsalert_otp_token_submit").addClass('sa-default-btn-hide');
							}else{							
								addShortcode();
								jQuery("#smsalert_otp_token_submit").removeClass('sa-default-btn-hide');
							}
							}
						}						
                        var fullnumber =  jQuery(this).intlTelInput("getNumber");
                        var field_name = jQuery(this).attr('name');
                        jQuery(this).parents("form").find('[name="'+field_name+'"]:hidden').val(fullnumber);
            
                        if (jQuery(this).intlTelInput('isValidNumber')) {
                            jQuery(this).intlTelInput("setNumber",fullnumber);
                            reset(this);
                            jQuery(this).parents("form").find("button, input[type=submit], input[type=button]").attr("disabled",false);
                            jQuery(this).parents("form").find(".smsalert_otp_btn_submit").css("cursor","pointer");
                            jQuery(this).parents("form").find(".smsalert_otp_btn_submit").attr("disabled",false);
                        }
                        else
                        {    
                
                            var iti = jQuery(this);
                            if(iti.val()!='') {
                                var errorCode = iti.intlTelInput('getValidationError');
                                iti.parents(".iti--separate-dial-code").next(".sa_phone_error").text(errorMap[errorCode]);
                                jQuery("#smsalert_otp_token_submit,#sc_btn").attr("disabled",true);
                                iti.parents(".iti--separate-dial-code").next(".sa_phone_error").removeAttr("style");
                                iti.parents("form").find(".smsalert_otp_btn_submit").css("cursor","not-allowed");
                                iti.parents("form").find(".smsalert_otp_btn_submit").attr("disabled",true);
                                jQuery("#sa_bis_submit").attr("disabled",true);
                            }
                        }
                    }
                );
				jQuery(this).trigger("countrychange");
            }
        );    
    
        //get all country data        
        function getCountryByCode(code)
        {
            return window.intlTelInputGlobals.getCountryData().filter(
                function (data) {
                    return (data.dialCode == code) ? data.iso2 : ''; }
            );
        }

        jQuery('#billing_country').change(
            function () {
                var iti = vars[jQuery("#billing_phone").attr('data-id')];
                iti.intlTelInput("setCountry",$(this).val());
                onChangeCheckValidno(document.querySelector("#billing_phone"));
            }
        );

        var reset = function (obj) {
              jQuery(obj).parents("form").find(".sa_phone_error").hide();
        
        };    

        function onChangeCheckValidno(obj)
        {
            reset(obj);
            var input     = obj;
            var iti     = jQuery(obj);
            if (input.value.trim()) {
                if (iti.intlTelInput('isValidNumber')) {
                     jQuery("#smsalert_otp_token_submit,#sc_btn").attr("disabled",false);
                     jQuery("#sa_bis_submit").attr("disabled",false);
                     iti.parents("form").find("button, input[type=submit], input[type=button]").attr("disabled",false);
                     iti.parents("form").find(".smsalert_otp_btn_submit").css("cursor","pointer");
                     iti.parents("form").find(".smsalert_otp_btn_submit").attr("disabled",false);

                } else{
                    var errorCode = iti.intlTelInput('getValidationError');
                    iti.parents(".iti--separate-dial-code").next(".sa_phone_error").text(errorMap[errorCode]);
                    jQuery("#smsalert_otp_token_submit,#sc_btn").attr("disabled",true);
                    iti.parents(".iti--separate-dial-code").next(".sa_phone_error").removeAttr("style");
                    iti.parents("form").find(".smsalert_otp_btn_submit").css("cursor","not-allowed");
                    iti.parents("form").find(".smsalert_otp_btn_submit").attr("disabled",true);
                    jQuery("#sa_bis_submit").attr("disabled",true);
                }
            
            }
        }

        jQuery(document).on(
            "blur","#billing_phone, .phone-valid",function () {
                onChangeCheckValidno(this);
            }
        );
        
        jQuery(".phone-valid,#billing_phone").keyup(
            function () {
                setPhoneNumber(this);
            }
        );
        jQuery(document).on(
            "keyup","#billing_phone, .phone-valid",function () {
                setPhoneNumber(this);
            }
        );
    
        function setPhoneNumber(obj)
        {
			reset(obj);
            var fullnumber =  jQuery(obj).intlTelInput("getNumber");
            //get number with std code
            if(typeof(fullnumber)!='object' && fullnumber!='') {
                var field_name = jQuery(obj).attr('name');
                jQuery(obj).intlTelInput("setNumber",fullnumber);
                jQuery(obj).parents("form").find('[name="'+field_name+'"]:hidden').val(fullnumber);
            }
        
            if (jQuery(obj).intlTelInput('isValidNumber')) {
                jQuery('#billing_phone_field .fl-wrap-input').addClass('fl-is-active');
                reset(obj);
                jQuery(obj).parents("form").find("button, input[type=submit], input[type=button]").attr("disabled",false);
                jQuery(obj).parents("form").find(".smsalert_otp_btn_submit").css("cursor","pointer");
                jQuery(obj).parents("form").find(".smsalert_otp_btn_submit").attr("disabled",false);
            }
            else{
                var iti     = jQuery(obj);
				if(iti.parents("form").find('.sa_phone_error').length == 0)
				{                
			      iti.parents("form").find(".iti--separate-dial-code").after("<span class='error sa_phone_error' style='display:none'></span>");              
			    }
                var errorCode = iti.intlTelInput('getValidationError');
                if(iti.val()!='') {
                    jQuery('#billing_phone_field .fl-wrap-input').addClass('fl-is-active');
                    iti.parents(".iti--separate-dial-code").next(".sa_phone_error").text(errorMap[errorCode]);
                    jQuery("#smsalert_otp_token_submit,#sc_btn").attr("disabled",true);
                    iti.parents(".iti--separate-dial-code").next(".sa_phone_error").removeAttr("style");
                    iti.parents("form").find(".smsalert_otp_btn_submit").attr("disabled",true);
                    jQuery("#sa_bis_submit").attr("disabled",true);    
                }
                else{
                    jQuery('#billing_phone_field .fl-wrap-input').removeClass('fl-is-active');
                }
                   iti.parents("form").find(".smsalert_otp_btn_submit").attr("disabled",true).css("cursor","not-allowed");                
            }    
        }
    
        jQuery(".phone-valid,#billing_phone").trigger('keyup');

        // on keyup / change flag: reset
        jQuery("#billing_phone").change(
            function () {
    
                var iti     = jQuery(this);
                if (iti.intlTelInput('isValidNumber')) {
                    reset(this);
                }
            }
        );
    }
);