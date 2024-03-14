/* global objwp2sl, obj_captcha, jQuery, grecaptcha*/
(function($) {
    "use strict";
 	$(function() {
		$.fn.center = function () {
			this.css("position","absolute");
			this.css("top", ( $(window).height() - this.height() ) / 2 + $(window).scrollTop() + "px");
			this.css("left", ( $(window).width() - this.width() ) / 2 + $(window).scrollLeft() + "px");
			return this;
		};
		$.fn.doesExist = function(){
			return $(this).length > 0;
		};
		$(document).ready(function() {

			if($(".oepl_sel_captcha").val() != undefined){
                if($(".oepl_sel_captcha").val().trim() === 'google'){
                    $(".reCAPTCHA_tr").show();
                }
            }

			$(".oepl_sel_captcha").on("change", function() {
				$(".reCAPTCHA_tr").hide();
				if($(this).val() === 'google'){
					$(".reCAPTCHA_tr").show();
				}
			});

			$(".OEPL_repload_captcha").on("click", function() {
				var dateobj = new Date();	
				$(".OEPL_captcha_img").attr('src', objwp2sl.pluginurl+'captcha.php?' + dateobj.getTime());
			});
			
			if($(".IPaddrStatus").is(':checked') === true){
				$('.OEPL_highlight_this').addClass('OEPL-red');
			}
			$(".IPaddrStatus").on("change", function(){
				if($(this).is(':checked') === true){
					$('.OEPL_highlight_this').addClass('OEPL-red');
				} else {
					$('.OEPL_highlight_this').removeClass('OEPL-red');
				}
			});
			
			if($("#OEPL_redirect_user").is(':checked') === true){
				$('.OEPL_redirect_tr').show();
			}
			$("#OEPL_redirect_user").on("change", function(){
				if($(this).is(':checked') === true){
					$('.OEPL_redirect_tr').show();
				} else {
					$('.OEPL_redirect_tr').hide();
				}
			});
			
			if($("#OEPL_is_htacess_protected").is(':checked') === true){
				$('.OEPL_htaccess_tr').show();
			}
			$("#OEPL_is_htacess_protected").on("change", function(){
				if($(this).is(':checked') === true){
					$('.OEPL_htaccess_tr').show();
				} else {
					$('.OEPL_htaccess_tr').hide();
				}
			});
			
			/********************************************
			 *Front End widget form submit function START 
			********************************************/ 
			var options = { 
				target:        	'.LeadFormMsg',
				beforeSubmit:  	showRequest,
				success:		showResponse,
				url:			objwp2sl.ajaxurl
			}; 
			
			$('#OEPL_Widget_Form').ajaxForm(options); 
			
			function showRequest(formData, jqForm, options) {
				var ReturnFlag = false;
				var Error = '';

				if (obj_captcha.wp2sl_captcha_v2) {
					if (grecaptcha.getResponse() === "") {
						Error = objwp2sl.ReqCatchaMsg;
						ReturnFlag = true;
					} 
				}
				
				$("#OEPL_Widget_Form p .LeadFormRequired").each(function(){
					$(this).removeClass('InvalidInput');
					if($(this).val() === ''){
						$(this).addClass('InvalidInput');
						if($(this).attr('id') === 'OEPL_CAPTCHA'){
							Error = objwp2sl.ReqCatchaMsg;
						} else {
							Error = objwp2sl.ReqFieldMsg;
						}
						ReturnFlag = true;
					}
				
					var type = $(this).attr("type");
					if(type === 'checkbox'){
						if($(this).is(":not(:checked)")){
							$(this).addClass('InvalidInput');
							Error = objwp2sl.ReqFieldMsg;
							ReturnFlag = true;
						}
					}
				});
				
				$('.LeadFormMsg').css('color', '#FF0000');
				if(ReturnFlag === true) {
					$('.LeadFormMsg').html(Error);
					$('html, body').animate({
						scrollTop: $('.widget_oepl_lead_widget').offset().top
					}, 400);
					return false;
				}
				
				$('.oe-loader-section').show();
				$('.LeadFormMsg').html('');
				$("#WidgetFormSubmit").val("Please Wait...");
				$("#WidgetFormSubmit").addClass('loadingBtn');
			}
			function showResponse(response, statusText, xhr, $form)  {
				var json = '';
				if(response === 0) {
					json = $.parseJSON($('.LeadFormMsg').html());
				} else {
					json = response;
				}
				
				if(json.redirectStatus === 'Y'){
					var url = json.redirectTo;
					window.location = url;
				} else {
					var dateobj = new Date();	
					$(".OEPL_captcha_img").attr('src', objwp2sl.pluginurl+'captcha.php?' + dateobj.getTime());
					
					if (json.success === 'Y'){
						$("#OEPL_Widget_Form p .nonHidden").each(function(){
							$(this).val('');
						});

						$('.LeadFormMsg').css('color', '#0A821A');
					}
					$("#OEPL_CAPTCHA").val('');

					$('.LeadFormMsg').html(json.message);
					$("#WidgetFormSubmit").val("Submit");
					$("#WidgetFormSubmit").removeClass('loadingBtn');
					$('html, body').animate({
						scrollTop: $('.widget_oepl_lead_widget').offset().top
					}, 400);
					if (obj_captcha.wp2sl_captcha_v2) {
						grecaptcha.reset();
					}
					$('.oe-loader-section').hide();
				}
				return false;
			}
			/******************************************
			 *Front End widget form submit function END 
			******************************************/
			
			if($('#EmailNotification').is(':checked') === true)
				$(".EmailToTR").show();
			$('#EmailNotification').on("change", function(){
				if($(this).is(':checked') === true)
					$(".EmailToTR").show();
				else
					$(".EmailToTR").hide();
			});
			
			$(".OEPL_hide_panel").on("click", function(){
				if($(this).attr("is_show") === "No"){
					$(this).attr("is_show","Yes");
					$(this).find("img").attr('src',objwp2sl.pluginurl+'image/minus-icon.png');
					$(".OEPL_hidden_panel").show();
				} else {
					$(this).attr("is_show","No");
					$(this).find("img").attr('src',objwp2sl.pluginurl+'image/plus-icon.png');
					$(".OEPL_hidden_panel").hide();
				}
			});
			
			$("#OEPL_css_save").on("click", function(){
				var css = $("#OEPL_custom_css").val();
				var oeplnonce 	= $('#oepl_nonce').val();
				var data = {};
				data.action = 'WP2SL_save_custom_css';
				data.css 	= css;
				data.oepl_nonce 	= oeplnonce;
				
				$('.oe-loader-section').show();
				$.post(objwp2sl.ajaxurl,data,function(response){
					$('.oe-loader-section').hide();
					if(response.status === 'Y'){
						$(".OEPL_SuccessMsg").show();
						$(".OEPL_SuccessMsg").html(response.message);
					} else {
						$(".OEPL_ErrMsg").show();
						$(".OEPL_ErrMsg").html(response.message);
					}
					$('.oe-loader-section').hide();
					$("html, body").animate({ scrollTop: 0 }, "slow");
				});
				return false;
			});
			
			$(".OEPL_Delete_Cust_Field").on("click", function(){
				var agree=confirm("Are you sure you want to delete this field ?");
				if (agree) {
					var pid = $(this).attr('pid');
					var data = {};
					data.action = 'WP2SL_Custom_Field_Delete';
					data.pid 	= pid;
					$('.oe-loader-section').show();
					$.post(objwp2sl.ajaxurl,data,function(response){
						$('.oe-loader-section').hide();
						if (response === 'Field deleted successfully'){
							window.location.reload();
						} else {
							alert(response);
						}
					});
				} else {
					return false;
				}
			});
			
			$(".OEPL_Custom_Field_Add").on("click", function(){
				var data = {};
				var FieldName = $("#OEPL_Custom_Field_Name").val().trim();	
				if(FieldName === ''){
					alert("Field Name could not be blank ! Please try again");
					return false;
				}
				data.action 	= 'WP2SL_Custom_Field_Save';
				data.Field_Name	= FieldName;
				$('.oe-loader-section').show();
				$.post(objwp2sl.ajaxurl,data,function(response){
					$('.oe-loader-section').hide();
					if (response === 'Field added successfully'){
						window.location.reload();
					} else {
						alert(response);
					}
				});
				return false;
			});
			
			$(".OEPLsaveConfig").on("click", function(){
				$(".OEPL_ErrMsg").hide();
				$(".OEPL_SuccessMsg").hide();
				var data = {};
				var SugarURL 	= $('#OEPL_SUGARCRM_URL').val().trim();
				var SugarUser 	= $('#OEPL_SUGARCRM_ADMIN_USER').val().trim();
				var SugarPass 	= $('#OEPL_SUGARCRM_ADMIN_PASS').val().trim();
				var oeplnonce 	= $('#oepl_nonce').val().trim();
				
				if(SugarURL === '' || SugarURL === null){
					alert ('Please provide SugarCRM URL');
					$('#OEPL_SUGARCRM_URL').focus();
					return false;
				} else if(SugarUser === '' || SugarUser === null){
					alert ('Please provide SugarCRM Admin User');
					$('#OEPL_SUGARCRM_ADMIN_USER').focus();
					return false;
				} else if(SugarPass === '' || SugarPass === null){
					alert ('Please provide SugarCRM Admin Password');
					$('#OEPL_SUGARCRM_ADMIN_PASS').focus();
					return false;
				}
				var isHtaccessProtected = '';
				if($('#OEPL_is_htacess_protected').is(':checked') === true)
					isHtaccessProtected = 'Y';
				else 
					isHtaccessProtected = 'N';
				
				data.action = 'WP2SL_saveConfig';
				data.SugarURL 	= SugarURL;
				data.SugarUser 	= SugarUser;
				data.SugarPass 	= SugarPass;
				data.oepl_nonce 	= oeplnonce;
				
				
				data.isHtaccessProtected = isHtaccessProtected;
				if(isHtaccessProtected === 'Y'){
					var HtaccessUser = $("#Oepl_Htaccess_Admin_User").val().trim();
					var HtaccessPass = $("#Oepl_Htaccess_Admin_Pass").val().trim();
					if (HtaccessUser === '' || HtaccessUser === null){
						alert ("Please provide .htaccess Username");
						$('#Oepl_Htaccess_Admin_User').focus();
						return false;
					} else if(HtaccessPass === '' || HtaccessPass === null) {
						alert ("Please provide .htaccess Password");
						$('#Oepl_Htaccess_Admin_Pass').focus();
						return false;
					}
					data.HtaccessUser = HtaccessUser;
					data.HtaccessPass = HtaccessPass;
				}
				
				$('.oe-loader-section').show();
				$.post(objwp2sl.ajaxurl,data,function(response){
					if(response.status === 'Y'){
						$(".OEPL_SuccessMsg").show();
						$(".OEPL_SuccessMsg").html(response.message);
						setTimeout(function(){ location.reload(); }, 1000);
					} else {
						$(".OEPL_ErrMsg").show();
						$(".OEPL_ErrMsg").html(response.message);
					}
					$("html, body").animate({ scrollTop: 0 }, "slow");
					$('.oe-loader-section').hide();
				});
				return false;
			});
			
			$(".DatePicker").datetimepicker({
				format:'m/d/Y',
				timepicker:false,
				closeOnDateSelect:true,
			});
			$(".DateTimePicker").datetimepicker({
				format:'m/d/Y H:i',
				closeOnDateSelect:true,
			});
			$('#OEPL-Leads_table #doaction').on("click", function(){
				var noOfChecked = 0;
				$("#OEPL-Leads_table .LeadTableCbx").each(function(){
					var CbxChecked = $(this).prop("checked");
					if(CbxChecked) noOfChecked++;
				});
				if(noOfChecked <= 0){
					alert ("Please select atleast record to update");
					return false;
				}
			});
			$('.LeadTableCbx').on("change", function(){
				var check = $(this).prop("checked");
				if(check){
					$(this).parent().parent().addClass('CbxChecked');
				} else {
					$(this).parent().parent().removeClass('CbxChecked');
				}
			});
			
			$('#cb-select-all-1').on("change", function(){
				var check = $(this).prop("checked");
				if(check){
					$(".wp-list-table tbody tr").addClass('CbxChecked');
				} else {
					$(".wp-list-table tbody tr").removeClass('CbxChecked');
				}
			});
			
			$('#LeadFldSync').on("click", function(){
				$(".OEPL_ErrMsg").hide();
				$(".OEPL_SuccessMsg").hide();
				var data = {};
				data.action = 'WP2SL_LeadFieldSync';
				$('.oe-loader-section').show();
				$.post(objwp2sl.ajaxurl,data,function(response){
					$('.oe-loader-section').hide();
					if(response.status === 'Y'){
						$(".OEPL_SuccessMsg").show();
						$(".OEPL_SuccessMsg").html(response.message);
					} else {
						$(".OEPL_ErrMsg").show();
						$(".OEPL_ErrMsg").html(response.message);
					}
					$("html, body").animate({ scrollTop: 0 }, "slow");
				});
				return false;
			});
			
			$("#OEPL_message_save").on("click", function(){
				$(".OEPL_ErrMsg").hide();
				$(".OEPL_SuccessMsg").hide();
				var oeplnonce 	= $('#oepl_nonce').val().trim();
				
				var data = {};
				data.SuccessMessage			= $(".SuccessMessage").val().trim();
				data.FailureMessage			= $(".FailureMessage").val().trim();
				data.ReqFieldsMessage		= $(".ReqFieldsMessage").val().trim();
				data.InvalidCaptchaMessage	= $(".InvalidCaptchaMessage").val().trim();
				data.oepl_nonce 	= oeplnonce;
				data.action = "WP2SL_GeneralMessagesSave";
				
				$('.oe-loader-section').show();
				$.post(objwp2sl.ajaxurl,data,function(response){
					if(response.status === 'Y'){
						$(".OEPL_SuccessMsg").show();
						$(".OEPL_SuccessMsg").html(response.message);
					} else {
						$(".OEPL_ErrMsg").show();
						$(".OEPL_ErrMsg").html(response.message);
					}
					$('.oe-loader-section').hide();
					$("html, body").animate({ scrollTop: 0 }, "slow");
				});
				return false;
			});
			
			$('#OEPL_save_general_settings').on("click", function(){
				$(".OEPL_ErrMsg").hide();
				$(".OEPL_SuccessMsg").hide();
				var data = {};
				var IPaddrStatus = '';
				var EmailNotification = '';
				var redirectStatus = '';
				if($('#EmailNotification').is(':checked') === true){
					EmailNotification = 'Y';
					if($("#EmailReceiver").val().trim() === ''){
						alert("Please enter Send Email to");
						$("#EmailReceiver").focus();
						return false;
					}
				} else {
					EmailNotification = 'N';
				}
				
				if($('.IPaddrStatus').is(':checked') === true)
					IPaddrStatus = 'Y';
				else 
					IPaddrStatus = 'N';
					
				if($("#OEPL_redirect_user").is(':checked') === true){
					redirectStatus = 'Y';
					if($("#OEPL_redirect_user_to").val().trim() === ''){
						alert("Please enter redirect URL.");
						$("#OEPL_redirect_user_to").focus();
						return false;
					}
				} else {
					redirectStatus = 'N';
				}

				if($(".oepl_sel_captcha").val().trim() === 'google'){
					if($("#oepl_recaptcha_site_key").val().trim() === ''){
						alert("Please enter reCAPTCHA Site key (Public key)");
						$("#oepl_recaptcha_site_key").focus();
						return false;
					}

					if($("#oepl_recaptcha_secret_key").val().trim() === ''){
						alert("Please enter reCAPTCHA Secret key (Private key)");
						$("#oepl_recaptcha_secret_key").focus();
						return false;
					}
				}

				var oeplnonce 	= $('#oepl_nonce').val().trim();
				
				data.action = "WP2SL_GeneralSettingSave";
				data.IPaddrStatus		= IPaddrStatus;
				data.EmailNotification	= EmailNotification;
				data.EmailReceiver		= $("#EmailReceiver").val().trim();
				data.redirectStatus		= redirectStatus;
				data.redirectTo			= $("#OEPL_redirect_user_to").val().trim();
				data.catpchaStatus 		= $(".captchaSettings").val().trim();
				data.selectcaptcha      = $(".oepl_sel_captcha").val().trim();
				data.oepl_recaptcha_site_key = $(".oepl_recaptcha_site_key").val().trim();
				data.oepl_recaptcha_secret_key = $(".oepl_recaptcha_secret_key").val().trim();
				data.oepl_nonce 	= oeplnonce;
				
				$('.oe-loader-section').show();
				$.post(objwp2sl.ajaxurl,data,function(response){
					$('.oe-loader-section').hide();
					if(response.status === 'Y'){
						$(".OEPL_SuccessMsg").show();
						$(".OEPL_SuccessMsg").html(response.message);
					} else {
						$(".OEPL_ErrMsg").show();
						$(".OEPL_ErrMsg").html(response.message);
					}
					
					var oeplcaptcha = $(".oepl_sel_captcha").val().trim();
					if(oeplcaptcha === 'google'){
						$("#oepl_captcha").show();
					}else{
						$("#oepl_captcha").hide();
					}
					
					$("html, body").animate({ scrollTop: 0 }, "slow");
				});
				return false;
			});
			
			$(".FileEach").on("change", function(evt){
				var formdata = '';
				if (window.FormData) {
					formdata = new FormData();
					var len = this.files.length, reader, file;
					for (var i=0; i < len; i++ ) {
						file = this.files[i];
						if (!!file.type.match(/image.*/)) {
							if ( window.FileReader ) {
								reader = new FileReader();
								reader.onloadend = function (e) { 
								};
								reader.readAsDataURL(file);
							}
							if (formdata) {
								formdata.append("images[]", file);
							}
						}
					}	
				} else {
					formdata = '';
				}
				return false;
			});
			
			$('.OEPLIntInput').on("keydown", function(e) {
			switch( e.keyCode ) {
				case 9:case 97:case 98:case 96:case 48:case 99:case 100:case 101:case 102:case 103:case 104:case 105:case 49:case 50:case 51:case 52:case 53:case 54:case 55:case 56:case 57:case 37:case 39:case 8:case 13:case 46:case 116:case 17:return;}
				e.preventDefault();
			});
			
			$("#testConn").on("click", function(){
				$(".OEPL_ErrMsg").hide();
				$(".OEPL_SuccessMsg").hide();
				var data = {};
				data.action = 'WP2SL_TestSugarConn';
				
				var isHtaccessProtected = '';
				if($('#OEPL_is_htacess_protected').is(':checked') === true)
					isHtaccessProtected = 'Y';
				else 
					isHtaccessProtected = 'N';
				
				data.isHtaccessProtected = isHtaccessProtected;
				if(isHtaccessProtected === 'Y'){
					var HtaccessUser = $("#Oepl_Htaccess_Admin_User").val().trim();
					var HtaccessPass = $("#Oepl_Htaccess_Admin_Pass").val().trim();
					if (HtaccessUser === '' || HtaccessUser === null){
						alert ("Please provide .htaccess Username");
						$('#Oepl_Htaccess_Admin_User').focus();
						return false;
					} else if(HtaccessPass === '' || HtaccessPass === null) {
						alert ("Please provide .htaccess Password");
						$('#Oepl_Htaccess_Admin_Pass').focus();
						return false;
					}
					data.HtaccessUser = HtaccessUser;
					data.HtaccessPass = HtaccessPass;
				}
				
				data.URL  = $("#OEPL_SUGARCRM_URL").val().trim();
				data.USER = $("#OEPL_SUGARCRM_ADMIN_USER").val().trim();
				data.PASS = $("#OEPL_SUGARCRM_ADMIN_PASS").val().trim();

				if(data.URL === '' || data.URL === null){
					alert ('Please provide SugarCRM URL');
					$('#OEPL_SUGARCRM_URL').focus();
					return false;
				} else if(data.USER === '' || data.USER === null){
					alert ('Please provide SugarCRM Admin User');
					$('#OEPL_SUGARCRM_ADMIN_USER').focus();
					return false;
				} else if(data.PASS === '' || data.PASS === null){
					alert ('Please provide SugarCRM Admin Password');
					$('#OEPL_SUGARCRM_ADMIN_PASS').focus();
					return false;
				}

				$('.oe-loader-section').show();
				$.post(objwp2sl.ajaxurl, data, function(response) {
					$('.oe-loader-section').hide();
					if(response.status === 'Y'){
						$(".OEPL_SuccessMsg").show();
						$(".OEPL_SuccessMsg").html(response.message);
					} else {
						$(".OEPL_ErrMsg").show();
						$(".OEPL_ErrMsg").html(response.message);
					}
					$("html, body").animate({ scrollTop: 0 }, "slow");
				});
				return false;
			});
		});
	});
})(jQuery);