'use strict';
(function(jQuery, window, document) {
	jQuery(function(){
		'use strict';
		
		function scconn_pad_with_leading_zeros(string) {
			return new Array(5 - string.length).join("0") + string;
		}

		function scconn_unicode_char_escape(charCode) {
			return "\\u" + scconn_pad_with_leading_zeros(charCode.toString(16));
		}

		function scconn_unicode_escape(string) {
			return string.split("")
						 .map(function (char) {
							 var charCode = char.charCodeAt(0);
							 return charCode > 127 ? scconn_unicode_char_escape(charCode) : char;
						 })
						 .join("");
		}
		
		jQuery(".sellingcommander-context input[type='password']").each(function(){
			jQuery("<a class='show_hide_password'>&#128065;</a>").insertAfter(jQuery(this)).parent().addClass("password-cnt");
		});
		
		jQuery(document).on("click",".show_hide_password",function(e){
			e.preventDefault();
			if(jQuery(this).prev("input").is("[type='password']")){
				jQuery(this).prev("input").attr("type","text");
				jQuery(this).addClass("show_password");
			}else{
				jQuery(this).prev("input").attr("type","password");
				jQuery(this).removeClass("show_password");
			}
		});
		
		jQuery("#cmdSCSwapAcount").hide();
		
		var scconn_connect_link = "https://sellingcommander.com/connect/";
		
		if(SellingCommander){
			
			if(SellingCommander.connectdata){
				if(SellingCommander.connectdata.scuser){
					if(/^scgeneric/.test(SellingCommander.connectdata.scuser)){
						SellingCommander.in_local_console = 1;
					}
				}
			}
			
			if(SellingCommander.just_manage_products_url){
				jQuery(".cmd-just-manage-products").attr("href",SellingCommander.just_manage_products_url);
			}else{
				jQuery(".cmd-just-manage-products").click(function(e){
					jQuery(".sc-local-site .sc-product-manager").attr("path",jQuery(".sc-local-site .sc-product-manager").attr("path") + "&scforlocalconsole=1");	
					jQuery(".sc-local-site .sc-product-manager").trigger("click");
				});
			}
			
			jQuery("#sellingcommander_connector_version").html(SellingCommander.plugin_version);
			
			if(SellingCommander.response){
				if(SellingCommander.response.follow){
					window.location.href = "//sellingcommander.com/" + SellingCommander.response.follow;
					return;
				}
			}
			
			SellingCommander.connectdata.referrer_link = window.location.href.split("&scresponse=")[0].split("?scresponse=")[0];
			if(!SellingCommander.connectdata.scaction || SellingCommander.connectdata.scaction == "siteauth-connectuser"){
				window.location.href = scconn_connect_link + "?connectdata=" + encodeURIComponent(btoa(JSON.stringify(SellingCommander.connectdata))); 
			}else{
				
				if(SellingCommander.siteuid){
					jQuery(".create-note").hide();
				}else{
					jQuery(".uconnect-note").hide();
				}
				
				if(!SellingCommander.response){
					jQuery(".owner-note, .uconnect-note").hide();
				}else{
					if(!SellingCommander.response.c_owner){
						jQuery(".owner-note").hide();
					}else{
						jQuery(".sellingcommander_owner").html(SellingCommander.response.c_owner);
					}
					
					if(SellingCommander.response.drole){
						jQuery(".sellingcommander_role").html(SellingCommander.response.drole);
					}
					
					let scuser = null;
					let siteuser = null;
					
					if(SellingCommander.response.scuser){
						jQuery(".sellingcommander-account").show();
						jQuery(".sellingcommander-account span").html(SellingCommander.response.scuser);
						jQuery(".sellingcommander_proceed_account").html(SellingCommander.response.scuser);
						scuser = SellingCommander.response.scuser;
					}else{
						jQuery(".sellingcommander-account").hide();
					}
					
					if(SellingCommander.response.scaction == "connected"){
						jQuery(".sellingcommander-local-account").show();
						if(SellingCommander.connectdata.user_email){
							jQuery(".sellingcommander-local-account span").html(SellingCommander.connectdata.user_email);
							siteuser = SellingCommander.connectdata.user_email;
						}else{
							jQuery(".sellingcommander-local-account").hide();
						}
					}
					
					
					if(scuser && siteuser){
						if(scuser == siteuser){
							jQuery(".sellingcommander-local-account").hide();
						}else{
							jQuery("#cmdSCSwapAcount").show();
						}
					}
					
					if(SellingCommander.response.confirm_alias){
						jQuery("#SCAliasedSite").html(SellingCommander.response.confirm_alias);
						jQuery(".sellingcommander-confirm-alias").show();
					}
				}
				
				jQuery(".sellingcommander-pending").hide();
				let main_action = SellingCommander.connectdata.scaction.split("-")[0];
				if(main_action == "login"){
					if(SellingCommander.connectdata.scuser){
						jQuery("#ProceedAs").html(SellingCommander.connectdata.scuser);
						jQuery(".sellingcommander-proceed").show();
					}
					
					jQuery(".sellingcommander-login").show();
					if(SellingCommander.response){
						if(SellingCommander.response.nologin){
							jQuery(".sellingcommander-login").show();
						}
					}
					jQuery(".sellingcommander-context input[name='email']").val(localStorage["sc_email"] || SellingCommander.connectdata.user_email);
				}else if(main_action == "register"){
					if(SellingCommander.connectdata.scuser){
						jQuery("#ProceedAs").html(SellingCommander.connectdata.scuser);
						jQuery(".sellingcommander-proceed").show();
					}
					jQuery(".sellingcommander-register").show();
					let suggest_email = localStorage["sc_email"] || SellingCommander.connectdata.user_email;
					
					if(suggest_email.indexOf("site.com") === -1)
						jQuery(".sellingcommander-context input[name='email']").val(suggest_email);
					
					let d = new Date();
					let tmp = suggest_email.split("@")[0];
					
					let suggest_p = "s" + tmp.slice(0,1).toLowerCase() + d.getUTCMonth() + "." + tmp.slice(-2).toUpperCase() + "C" + d.getMinutes() + "#" +  tmp.slice(-4,2) + d.getMilliseconds();
					setTimeout(function(){
						if(!scconn_validate_email(jQuery(".sellingcommander-context input[name='email']:visible").val())){
							jQuery(".sellingcommander-context input[name='email']").css("border","1px solid red");
						}
						jQuery(".sellingcommander-register input[type='password']").val(suggest_p).css("border","1px solid green");
						jQuery(".sellingcommander-register .show_hide_password").trigger("click");	
					},1000);
					
					
				}else if(main_action == "onsitedahboard"){
					jQuery(".sellingcommander-onsitedahboard").show();
				}else{
					
				}
				if(SellingCommander.error){
					jQuery(".sellingcommander-error").html(SellingCommander.error);
				}
				
				if(SellingCommander.init){
					try{
						SellingCommander.init = eval("(" + SellingCommander.init + ")")();
					}catch(ex){
						//
					}
				}
				
				if(SellingCommander.ui){
					
					let site_list = jQuery(".sellingcommander-local-dashboard");
					for(var scid in SellingCommander.ui){
						if(SellingCommander.ui.hasOwnProperty(scid)){
							let site = SellingCommander.ui[scid];
							let row = jQuery("<div><label></label><span></span></div>").attr("site_id",scid);
							row.find("label").html(site[0].replace("woocommerce","WooCommerce")
														  .replace("virtuemart","VirtueMart")
														  .replace("gmc","Google Merchant Center")
														  .replace("facebook","Facebook")
														  .replace("instagram","Instagram"));
							
							row.addClass("sc-site-type-" + String(site[0].split(":")[0]).trim());
							
							let cmds = row.find("span");
							
							if(site[1].indexOf("p") > -1){
								let btn = jQuery("<button class='sc-button sc-product-manager'></button>").html(SellingCommander.labels.SC_MANAGE_PRODUCTS).attr("site_id",scid).attr("path","p_shopid=" + scid);
								cmds.append(btn);
							}
							
							if(site[1].indexOf("o") > -1){
								let btn = jQuery("<button class='sc-button sc-order-manager'></button>").html(SellingCommander.labels.SC_MANAGE_ORDERS).attr("site_id",scid).attr("path","o_shopid=" + scid);
								cmds.append(btn);
							}
							
							if(site[1].indexOf("b") > -1){
								let btn = jQuery("<button class='sc-button sc-b2b'></button>").html(SellingCommander.labels.SC_B2B).attr("site_id",scid).attr("path","b_shopid=" + scid);
								cmds.append(btn);
							}
							
							if(site[1].indexOf("c") > -1){
								let btn = jQuery("<button class='sc-configure sc-b2b'></button>").html(SellingCommander.labels.SC_CONFIGURE).attr("site_id",scid).attr("path","c_shopid=" + scid);
								cmds.append(btn);
							}
							
							if(site[1].indexOf("l") > -1){
								jQuery("<hr/>").prependTo(site_list);
								row.addClass("sc-local-site");
								row.prependTo(site_list);
							}else{
								row.appendTo(site_list);
							}
						}
					}
					
					if(SellingCommander.show_add == 1){
						jQuery("<hr/>").appendTo(site_list);
						site_list.append(jQuery("<p></p>").html(SellingCommander.labels.SC_ADD_HINT));
						let btn = jQuery("<button class='sc-add-new-site'></button>").html(SellingCommander.labels.SC_ADD).attr("path","addsite");
						site_list.append(btn);
					}
				}
				
				jQuery.get("//sellingcommander.com/promo/?locale=" + SellingCommander.connectdata.locale,function(data){
					if (typeof data === 'string' || data instanceof String){
						data = eval("(" + data + ")");	
					}
					
					if(data.html){
						jQuery(".sellingcommander-promo-zone").html(data.html);
					}
					
					if(data.run){
						eval("(" + data.run + ")")();
					}
				});
			} 
		}
		
		let scconn_check_password = function(str){
			var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/;
			return re.test(str);
		};
		
		let scconn_validate_email = function(email) {
			var re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
			return re.test(email);
		};
		
		let scconn_get_inputed_data = function(container){
			let obj = {};
			jQuery(container).find("*[name]").each(function(ind){
				if(jQuery(this).is("input[type='checkbox'],input[type='radio']")){
					if(jQuery(this).is(":checked")){
						obj[jQuery(this).attr("name")] = jQuery(this).attr("value");
					}else if(!obj.hasOwnProperty(jQuery(this).attr("name"))){
						obj[jQuery(this).attr("name")] = null;
					}					
				}else{
					obj[jQuery(this).attr("name")] = scconn_unicode_escape(jQuery(this).val());
				}
			});
			return obj;
		};
		
		jQuery(document).on("click",".sellingcommander-local-dashboard button[path]",function(e){
			e.preventDefault();
			
			SellingCommander.connectdata.scaction = "";
			SellingCommander.connectdata.scpath  = jQuery(this).attr("path");
			
			if(SellingCommander.in_local_console){
				if(!/scforlocalconsole/.test(SellingCommander.connectdata.scpath)){
					SellingCommander.connectdata.scpath += "&scforlocalconsole=1";
				}
			}
			
			window.location.href = scconn_connect_link + "?connectdata=" + encodeURIComponent(btoa(JSON.stringify(SellingCommander.connectdata))); 
		});
		
		jQuery(document).on("click",".sellingcommander-confirm-alias button[aliasval]",function(e){
			e.preventDefault();
			SellingCommander.connectdata.confirm_alias = jQuery(this).attr("aliasval");
			window.location.href = scconn_connect_link + "?connectdata=" + encodeURIComponent(btoa(JSON.stringify(SellingCommander.connectdata))); 
		});
		
		jQuery(document).on("click","#cmdHaveAccount",function(e){
			e.preventDefault();
			jQuery(".sellingcommander-register").hide();
			jQuery(".sellingcommander-login").show();
			if(!scconn_validate_email(jQuery(".sellingcommander-context input[name='email']:visible").val())){
				jQuery(".sellingcommander-context input[name='email']").css("border","1px solid red");
			}
		});
		
		jQuery(document).on("click","#cmdCreateAccount",function(e){
			e.preventDefault();
			jQuery(".sellingcommander-login").hide();
			jQuery(".sellingcommander-register").show();
			if(!scconn_validate_email(jQuery(".sellingcommander-context input[name='email']:visible").val())){
				jQuery(".sellingcommander-context input[name='email']").css("border","1px solid red");
			}
		});
		
		jQuery(document).on("focus",".sellingcommander-context input,.sellingcommander-context textarea,.sellingcommander-context select",function(e){
			e.preventDefault();
			jQuery(this).css("border","none");
			jQuery(".sellingcommander-context .problem").hide();
		});
		
		jQuery(document).on("click","#cmdSCRegister",function(e){
			e.preventDefault();
			let inpdata = scconn_get_inputed_data('.sellingcommander-register-form');
			
			if(!SellingCommander.connectdata.soca){
				if(!scconn_validate_email(inpdata.email || "")){
					jQuery(".problem.email_problem").show();
					return;
				}
				
				if(!scconn_check_password(inpdata.password)){
					jQuery(".problem.password_problem").show();
					return;
				}
				
				if(inpdata.password != inpdata.rpassword){
					jQuery(".problem.repassword_problem").show();
					return;
				}
			}
			
			if(!inpdata.sctermsandcond){
				jQuery(".problem.register_accept").show();
				return;
			}
			
			localStorage["sc_email"] = inpdata.email;
			SellingCommander.connectdata.scaction = inpdata.scaction;
			SellingCommander.connectdata.input    = inpdata;
			delete SellingCommander.connectdata.input.scaction;
			
			if(SellingCommander.connectdata.soca){
				window.location.href = scconn_connect_link + "?connectdata=" + encodeURIComponent(btoa(JSON.stringify(SellingCommander.connectdata)));
			}else{
				jQuery(".sellingcommander-context input[name='email_confirmation']").val("");
				scconn_call_get_email_vcode();
				
				
			}
		});
		
		function scconn_call_get_email_vcode(){
			jQuery.post("//sellingcommander.com/confirmident/",{ email: SellingCommander.connectdata.input.email, locale: SellingCommander.connectdata.locale }, function(data){
				if(data.error){
					jQuery(".sellingcommander-error").html(data.error);
					return;
				}
				
				if(data.confirm == SellingCommander.connectdata.input.email){
					jQuery(".sellingcommander-register, .sellingcommander-proceed").hide();
					jQuery(".sellingcommander-confirmident").show();
				}else{
					jQuery(".problem.register_error").show();
				}
			});
		}
		
		jQuery(document).on("click","#cmdResendCode",function(e){
			e.preventDefault();
			
			scconn_call_get_email_vcode();
			
			jQuery("#cmdResendCode").prop("disabled",true).css('opacity',0.2);
			setTimeout(function(){
				jQuery("#cmdResendCode").prop("disabled",false).css('opacity',1);
			},60000);
		});
		
		jQuery(document).on("click","#cmdConfirmIdentCancel",function(e){
			e.preventDefault();
			jQuery(".sellingcommander-confirmident").hide();
			jQuery(".sellingcommander-register").show();
			if(SellingCommander.connectdata.scuser){
				jQuery("#ProceedAs").html(SellingCommander.connectdata.scuser);
				jQuery(".sellingcommander-proceed").show();
			}
		});
		
		jQuery(document).on("click","#cmdConfirmIdent",function(e){
			e.preventDefault();
			let email_confirmation = jQuery(".sellingcommander-context input[name='email_confirmation']").val() || "";
			if(!/[^\d]/.test(email_confirmation) && email_confirmation.length == 6){
				SellingCommander.connectdata.input.email_confirmation = email_confirmation;
				window.location.href = scconn_connect_link + "?connectdata=" + encodeURIComponent(btoa(JSON.stringify(SellingCommander.connectdata))); 
			}else{
				if(!inpdata.sctermsandcond){
					jQuery(".problem.email_confirmation").show();
				}
			}
		});
		
		jQuery(document).on("click","#cmdSCSwapAcount",function(e){
			e.preventDefault();
			SellingCommander.connectdata.swp = 1;
			SellingCommander.connectdata.scaction = 'siteauth-connectuser';
			window.location.href = scconn_connect_link + "?connectdata=" + encodeURIComponent(btoa(JSON.stringify(SellingCommander.connectdata))); 
		});
		
		jQuery(document).on("click","#cmdSCLogin",function(e){
			e.preventDefault();
			let inpdata = scconn_get_inputed_data('.sellingcommander-login-form');
			
			if(!SellingCommander.connectdata.soca){
				if(!scconn_validate_email(inpdata.email || "")){
					jQuery(".problem.email_problem").show();
					return;
				}
				
				if(!scconn_check_password(inpdata.password)){
					jQuery(".problem.password_problem").show();
					return;
				}
			}
			
			localStorage["sc_email"] = inpdata.email;
			SellingCommander.connectdata.scaction = inpdata.scaction;
			SellingCommander.connectdata.input    = inpdata;
			delete SellingCommander.connectdata.input.scaction;
			
			window.location.href = scconn_connect_link + "?connectdata=" + encodeURIComponent(btoa(JSON.stringify(SellingCommander.connectdata))); 
		});
		
		jQuery(document).on("click","#cmdProceedAs",function(e){
			e.preventDefault();
			let inpdata = scconn_get_inputed_data('.sellingcommander-proceed-form');
			SellingCommander.connectdata.scaction = inpdata.scaction;
			SellingCommander.connectdata.input    = inpdata;
			delete SellingCommander.connectdata.input.scaction;
			window.location.href = scconn_connect_link + "?connectdata=" + encodeURIComponent(btoa(JSON.stringify(SellingCommander.connectdata))); 
		});
		
		jQuery(document).on("click","#cmdLoginChange",function(e){
			e.preventDefault();
			jQuery(".sellingcommander-login").show();
		});
		
		
		jQuery(document).on("click","#cmdSCGoogleRegister",function(e){
			e.preventDefault();
			
			SellingCommander.connectdata.soca = "GOOGLE";
			jQuery("#cmdSCRegister").trigger("click");
			
		});
		
		jQuery(document).on("click","#cmdSCGoogleLogin",function(e){
			e.preventDefault();
			SellingCommander.connectdata.soca = "GOOGLE";	
			jQuery("#cmdSCLogin").trigger("click");
		});

		jQuery(document).on("click","#cmdSCFacebookRegister",function(e){
			e.preventDefault();
			SellingCommander.connectdata.soca = "FB";	
			jQuery("#cmdSCRegister").trigger("click");
		});
		
		jQuery(document).on("click","#cmdSCFacebookLogin",function(e){
			e.preventDefault();
			SellingCommander.connectdata.soca = "FB"		
			jQuery("#cmdSCLogin").trigger("click");
		});
		
		jQuery(document).on("click","#product_promo",function(e){
			e.preventDefault();
			if(SellingCommander.just_manage_products_url){
				window.location.href = SellingCommander.just_manage_products_url;
			}else{
				jQuery(".sc-local-site .sc-product-manager").trigger("click");
			}
		});
		
		jQuery(document).on("click","#order_promo",function(e){
			e.preventDefault();
			if(SellingCommander.just_manage_orders_url){
				window.location.href = SellingCommander.just_manage_orders_url;
			}else{
				jQuery(".sc-local-site .sc-order-manager").trigger("click");
			}
		});
		
		jQuery(document).on("click","#b2b_promo",function(e){
			e.preventDefault();
			jQuery(".sc-local-site .sc-b2b-manager").trigger("click");
		});
	});
}(window.jQuery, window, document));