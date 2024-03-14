function isValidEmailAddress(emailAddress) {
    var pattern = new RegExp(/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i);
    return pattern.test(emailAddress);
}
function TestaCPF(cpf) {
  var Soma = 0
  var Resto

  var strCPF = String(cpf).replace(/[^\d]/g, '')
  
  if (strCPF.length !== 11)
     return false
  
  if ([
    '00000000000',
    '11111111111',
    '22222222222',
    '33333333333',
    '44444444444',
    '55555555555',
    '66666666666',
    '77777777777',
    '88888888888',
    '99999999999',
    ].indexOf(strCPF) !== -1)
    return false

  for (i=1; i<=9; i++)
    Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);

  Resto = (Soma * 10) % 11

  if ((Resto == 10) || (Resto == 11)) 
    Resto = 0

  if (Resto != parseInt(strCPF.substring(9, 10)) )
    return false

  Soma = 0

  for (i = 1; i <= 10; i++)
    Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i)

  Resto = (Soma * 10) % 11

  if ((Resto == 10) || (Resto == 11)) 
    Resto = 0

  if (Resto != parseInt(strCPF.substring(10, 11) ) )
    return false

  return true
}
jQuery(document).ready(function($) {
	$(".cwmp_link_login").click(function(){
		$("#cwmp_form_input_password").removeClass("hide");
		$("#cwmp_login_button").removeClass("hide");
		$("#cwmp_remember_button").addClass("hide");
		$("#cwmp_register_button").addClass("hide");
		$(".cwmp_link_forget").removeClass("hide");
		$(".cwmp_link_login").addClass("hide");
		$(".cwmp_link_register").removeClass("hide");
		return false;
	});
	$(".cwmp_link_forget").click(function(){
		$("#cwmp_form_input_password").addClass("hide");
		$("#cwmp_login_button").addClass("hide");
		$("#cwmp_register_button").addClass("hide");
		$("#cwmp_remember_button").removeClass("hide");
		$(".cwmp_link_forget").addClass("hide");
		$(".cwmp_link_register").addClass("hide");
		$(".cwmp_link_login").removeClass("hide");
		return false;
	});
	$(".cwmp_link_register").click(function(){
		$("#cwmp_login_button").addClass("hide");
		$("#cwmp_remember_button").addClass("hide");
		$("#cwmp_register_button").removeClass("hide");
		$(".cwmp_link_forget").addClass("hide");
		$(".cwmp_link_register").addClass("hide");
		$(".cwmp_link_login").removeClass("hide");
		return false;
	});
	$("#cwmp_login_link").click(function(){
		$.ajax({
			type: "POST",
			url: cwmp.ajaxUrl,
			data: {
				action: "cwmp_form_access_link",
				email: $('#cwmp_form_input_email').val()
			},
			success: function(data) {
				$(".cwmp_form_login .return_login").removeClass("hide");
			}
		});
		return false;
	});
	$("#cwmp_login_button").click(function(){
		if($("#cwmp_form_input_password").hasClass("hide")){
			$.ajax({
				type: "POST",
				url: cwmp.ajaxUrl,
				data: {
					action: "cwmp_form_submit_login",
					email: $('#cwmp_form_input_email').val()
				},
				success: function(data) {
					
					if(data==false){
						$("form.checkout").removeClass("hide");
						$(".cwmp_form_login").addClass("hide");
						$("#billing_email").val($('#cwmp_form_input_email').val());
					}else{
						$("#cwmp_form_input_password").removeClass("hide");
						$("#cwmp_login_link").removeClass("hide");
					}
				}
			});
			
		}else{
			$.ajax({
				type: "POST",
				url: cwmp.ajaxUrl,
				data: {
					action: "cwmp_form_submit_login",
					email: $('#cwmp_form_input_email').val(),
					senha: $('#cwmp_form_input_password').val()
				},
				success: function(data) {
					if(data=="true"){
						location.reload();
					}else{

					}
				}
			});
			
		}
		return false;
	});
	$("input").keypress(function (e) {
		var code = null;
		code = (e.keyCode ? e.keyCode : e.which);                
		return (code == 13) ? false : true;
	});
	if(cwmp.maskedinput==1){
		$("#shipping_postcode").mask("00000-000");
		$("#billing_postcode").mask("00000-000");
		$("#billing_cpf").mask("000.000.000-00");
		$("#billing_cnpj").mask("00.000.000/0000-00");
		$("#shipping_cpf").mask("000.000.000-00");
		$("#shipping_cnpj").mask("00.000.000/0000-00");
		$("#billing_phone").mask("(00) #0000-0000");
		$("#shipping_phone").mask("(00) #0000-0000");
		$("#billing_cellphone").mask("(00) #0000-0000");
		$("#shipping_cellphone").mask("(00) #0000-0000");
		$("#billing_birthdate").mask("00/00/0000");
	}
	$(".open_resume").click(function(){
		if($(this).hasClass("open")){
			$(this).removeClass("open");
			$(".cwmp_woo_cart").removeClass("cwmp_woo_cart_white");
		}else{
			$(this).addClass("open");
			$(".cwmp_woo_cart").addClass("cwmp_woo_cart_white");
			
		}
		$( ".mobile_cart" ).slideToggle();
		return false;
	});
	$("form.checkout").on( "click", ".box_form_coupon button", function() {
		var coupon = $("#coupon_code").val();
		var data = {
		  coupon_code: $("#coupon_code").val(), 
		  security: cwmp.applyCoupon
		};
		$.post("/?wc-ajax=apply_coupon", data).done(function(data) {
			$.ajax({
				type: "POST",
				url: cwmp.ajaxUrl,
				data: {
					action: "cwmp_step_cart_ajax",
					step: 2,
				},
				success: function(data) {}
			});
			$(".return_cupom").html(data);
			$("body").trigger("update_checkout");
		});
		return false;
	});
	$(".woocommerce-cart .quantity").change(function() {
		if ($(this).val != "") {
			$("button[name='update_cart']").removeAttr("disabled");
		}
	});
	$("form.checkout").on( "click", "button.plus, button.minus", function() {
		var arvore = $(this).closest(".cwmp-quantity").find(".qty");
		var qty = arvore.val();
		if ( $( this ).is( ".plus" ) ) {
			var sum_qty = parseInt(qty)+parseInt(1);		
			$(arvore).val(sum_qty).change();
		}else{
			if(qty==0){}else{
				var sum_qty = parseInt(qty)-parseInt(1);		
				$(arvore).val(sum_qty).change();
			}
		}
		return false;
	});
	$(".woocommerce").on("change", "input.qty", function(){
		var item_hash = $( this ).attr( "name" ).replace(/cart\[([\w]+)\]\[qty\]/g, "$1");
		var item_quantity = $( this ).val();
		var currentVal = parseFloat(item_quantity);
		$.ajax({
			type: "POST",
			url: cwmp.ajaxUrl,
			data: {
				action: "cwmp_ajax_cart",
				hash: item_hash,
				quantity: currentVal
			},
			success: function(data) {
				$("body").trigger("update_checkout");
			}
		});
	});
	$(".woocommerce").on("change", "select#billing_persontype", function(){
		if($(this).val()=="1"){
			$("#billing_cpf_field").removeClass("hide");
			$("#billing_rg_field").removeClass("hide");
			$("#billing_cnpj_field").addClass("hide");
			$("#billing_company_field").addClass("hide");
			$("#billing_ie_field").addClass("hide");
		}
		if($(this).val()=="2"){
			$("#billing_cpf_field").removeClass("hide");
			$("#billing_rg_field").addClass("hide");
			$("#billing_cnpj_field").removeClass("hide");
			$("#billing_company_field").removeClass("hide");
			$("#billing_ie_field").removeClass("hide");

		}
	});
	$("#cwmp_step_1 a").click(function(){
		var field_error = "none";
		var field_error_name = "none";
		var cwmp_name = $("input#cwmp_billing_name").val().split(" ");
		$("input#billing_first_name").val(cwmp_name[0]);
		if(cwmp_name[1]){ var cwmp_last_name = cwmp_name[1]; }
		if(cwmp_name[2]){ cwmp_last_name = cwmp_last_name + " " + cwmp_name[2];	}
		if(cwmp_name[3]){ cwmp_last_name = cwmp_last_name + " " + cwmp_name[3];	}
		if(cwmp_name[4]){ cwmp_last_name = cwmp_last_name + " " + cwmp_name[4];	}
		if(cwmp_name[5]){ cwmp_last_name = cwmp_last_name + " " + cwmp_name[5]; }
		$("input#billing_last_name").val(cwmp_last_name);
		var fieldsBilling = JSON.parse(cwmp.billingFields);
		for(var k in fieldsBilling) {
			if($("#"+fieldsBilling[k]).val()==""){
				$("input#"+fieldsBilling[k]).addClass('fieldError');
				field_error = "error";
			}
		}
		if($("#billing_first_name").val()=="" || $("#billing_last_name").val()==""){
			field_error_name = "error";
		}
		if(field_error_name=="error"){
			$("input#cwmp_billing_name").addClass('fieldError');
		}
		if(field_error=="none"){
			var cwmp_user_name = $("#cwmp_billing_name").val();
			var cwmp_user_mail = $("#billing_email").val();
			var cwmp_user_phone = $("#billing_phone").val();
			$.ajax({
				type: "POST",
				url: cwmp.ajaxUrl,
				data: {
					action: "cwmp_ajax_register_cart",
					cwmp_user_name: cwmp_user_name,
					cwmp_cart_email: cwmp_user_mail,
					cwmp_cart_phone: cwmp_user_phone,
					cwmp_cart_session: cwmp.cartSession
				},
				success: function(data) {}
			});
			$.ajax({
				type: "POST",
				url: cwmp.ajaxUrl,
				data: {
					action: "cwmp_step_cart_ajax",
					step: 1,
				},
				success: function(data) {}
			});
			if(cwmp.viewActiveAddress!="S"){
				$(".woocommerce-shipping-fields").removeClass("hide");
				$(".cwmp_woo_wrapper .cwmp_woo_checkout .cwmp_woo_form_shipping").css("opacity","1");
			}else{
				$(".cwmp_woo_wrapper .cwmp_woo_form_payment").css("opacity","1");
				$(".section_payment").removeClass("hide");
				$(".cwmp_woo_wrapper .cwmp_woo_form_payment").css("opacity","1");
				$(".section_payment").removeClass("hide");
				$(".woocommerce-checkout-payment p").removeClass("hide");
				$(".cwmp_woo_wrapper .woocommerce-checkout-payment .cwmp_box_method_shipping").css("opacity","1");
			}
			$(".cwmp_retorno_billing").removeClass("hide");
			$("#cwmp_step_1").addClass("hide");
			$(".cwmp_woo_form_billing").addClass("box-success");
			$(".cwmp_woo_form_billing .cwmp-form-row").addClass("hide");
			$(".cwmp_retorno_billing div:nth-child(2) p:nth-child(1) strong").html($("input#cwmp_billing_name").val());
			$(".cwmp_retorno_billing div:nth-child(2) p:nth-child(2)").html($("input#billing_phone").val());
			$(".cwmp_retorno_billing div:nth-child(2) p:nth-child(3)").html($("input#billing_email").val());
		}
		return false;
	});
	$("#cwmp_edit_step_1 a").click(function(){
		var field_error = "none";
		var field_error_name = "none";
		var cwmp_name = $("input#cwmp_billing_name").val().split(" ");
		$("input#billing_first_name").val(cwmp_name[0]);
		if(cwmp_name[1]){ var cwmp_last_name = cwmp_name[1]; }
		if(cwmp_name[2]){ cwmp_last_name = cwmp_last_name + " " + cwmp_name[2];	}
		if(cwmp_name[3]){ cwmp_last_name = cwmp_last_name + " " + cwmp_name[3];	}
		if(cwmp_name[4]){ cwmp_last_name = cwmp_last_name + " " + cwmp_name[4];	}
		if(cwmp_name[5]){ cwmp_last_name = cwmp_last_name + " " + cwmp_name[5]; }
		$("input#billing_last_name").val(cwmp_last_name);
		var fieldsBilling = JSON.parse(cwmp.billingFields);
		for(var k in fieldsBilling) {
			if($("#"+fieldsBilling[k]).val()==""){
				$("input#"+fieldsBilling[k]).addClass('fieldError');
				field_error = "error";
			}
		}
		if($("#billing_first_name").val()=="" || $("#billing_last_name").val()==""){
			field_error_name = "error";
		}
		if(field_error_name=="error"){
			$("input#cwmp_billing_name").addClass('fieldError');
		}
		if(field_error=="none"){
			if(cwmp.viewActiveAddress!="S"){
			}else{
				$(".cwmp_woo_wrapper .cwmp_woo_form_payment").css("opacity","1");
				$(".section_payment").removeClass("hide");
			}
			$("#cwmp_edit_step_1").addClass("hide");
			$(".cwmp_woo_form_billing").addClass("box-success");
			$(".cwmp_woo_form_billing .cwmp-form-row").addClass("hide");
			$(".cwmp_retorno_billing").removeClass("hide");
			$(".cwmp_retorno_billing div:nth-child(2) p:nth-child(1) strong").html($("input#cwmp_billing_name").val());
			$(".cwmp_retorno_billing div:nth-child(2) p:nth-child(2)").html($("input#billing_phone").val());
			$(".cwmp_retorno_billing div:nth-child(2) p:nth-child(3)").html($("input#billing_email").val());
		}
		return false;
	});
	$("#cwmp_step_2 a").click(function(){
		var field_error = "none";
		var billing_postcode = $("#billing_postcode").val();
		if(billing_postcode.length==8 || billing_postcode.length==9){
			$("#billing_postcode_field span.error").addClass("hide");
		} else {
			field_error="true";
			//$("#billing_postcode_field span.error").removeClass("hide");
			$("input#billing_postcode").addClass('fieldError');
		}
		if($("#billing_address_1").val()==""){
			field_error="true";
			//$("#billing_address_1_field span.error").removeClass("hide");
			$("input#billing_address_1").addClass('fieldError');
		} else {
			$("#billing_address_1_field span.error").addClass("hide");
		}
		if($("#billing_number").val()==""){
			field_error="true";
			//$("#billing_number_field span.error").removeClass("hide");
			$("input#billing_number").addClass('fieldError');
		} else {
			$("#billing_number_field span.error").addClass("hide");
		}
		if($("#billing_neighborhood").val()==""){
			field_error="true";
			//$("#billing_neighborhood_field span.error").removeClass("hide");
			$("input#billing_neighborhood").addClass('fieldError');
		} else {
			$("#billing_neighborhood_field span.error").addClass("hide");
		}
		if($("#billing_city").val()==""){
			field_error="true";
			//$(".cwmp_errors li.billing_city").show();
			$("input#billing_city").addClass('fieldError');
		} else {
			$(".cwmp_errors li.billing_city").hide();
		}
		if($("#billing_state").val()==""){
			field_error="true";
			//$(".cwmp_errors li.billing_state").show();
			$("input#billing_state").addClass('fieldError');
		} else {
			$(".cwmp_errors li.billing_state").hide();
		}
		if(cwmp.optionalPresent=="S"){
			if($("input#ship-to-different-address-checkbox").is(":checked")){
				$("input#shipping_postcode").val($("input#billing_postcode").val());
				$("input#shipping_address_1").val($("input#billing_address_1").val());
				$("input#shipping_number").val($("input#billing_number").val());
				$("input#shipping_address_2").val($("input#billing_address_2").val());
				$("input#shipping_neighborhood").val($("input#billing_neighborhood").val());
				$("input#shipping_city").val($("input#billing_city").val());
				$("input#shipping_state").val($("input#billing_state").val());
				$("input#shipping_country").val($("input#billing_country").val());
			}
		}
		if(field_error=="none"){
			$.ajax({
				type: "POST",
				url: cwmp.ajaxUrl,
				data: {
					
					action: "cwmp_step_cart_ajax",
					step: 2,
				},
				success: function(data) {}
			});
			if(cwmp.needsShipping && cwmp.showShipping){
				$(".cwmp_form_method_shipping").removeClass("hide");
				$(".woocommerce-checkout-payment p").removeClass("hide");
				$(".section_payment").removeClass("hide");
				$(".cwmp_form_method_shipping .woocommerce-shipping-methods").removeClass("hide");
				$(".cwmp_form_method_shipping .cwmp_mobile").removeClass("hide");
				$(".cwmp_woo_wrapper .woocommerce-checkout-payment .cwmp_box_method_shipping").css("opacity","1");
			}else{
				$(".cwmp_woo_wrapper .cwmp_woo_form_payment").css("opacity","1");
				$(".section_payment").removeClass("hide");
				$(".woocommerce-checkout-payment p").removeClass("hide");
				$(".cwmp_woo_wrapper .woocommerce-checkout-payment .cwmp_box_method_shipping").css("opacity","1");
			}
			$(".cwmp_woo_form_shipping").addClass("box-success");
			$(".woocommerce-shipping-fields").addClass("hide");
			
			$(".cwmp_retorno_shipping div:nth-child(2) p:nth-child(1) strong").html($("input#billing_postcode").val());
			$(".cwmp_retorno_shipping div:nth-child(2) p:nth-child(2)").html($("input#billing_address_1").val());
			$(".cwmp_retorno_shipping div:nth-child(2) p:nth-child(2)").append(", " + $("input#billing_number").val());
			if($("input#billing_address_2").val()!=""){
			$(".cwmp_retorno_shipping div:nth-child(2) p:nth-child(2)").append(" " + $("input#billing_address_2").val());
			}
			$(".cwmp_retorno_shipping div:nth-child(2) p:nth-child(2)").append(" | " + $("input#billing_neighborhood").val());
			$(".cwmp_retorno_shipping div:nth-child(2) p:nth-child(3)").html($("input#billing_city").val());
			$(".cwmp_retorno_shipping div:nth-child(2) p:nth-child(3)").append(" | " + $("select#billing_state").val());
			
			$(".cwmp_retorno_shipping").removeClass("hide");	
			
			
			$("body").trigger("update_checkout");
		}
		return false;
	});
	$("form.checkout").on( "click", "#cwmp_step_4 a", function() {
		$.ajax({
			type: "POST",
			url: cwmp.ajaxUrl,
			data: {	
				action: "cwmp_step_cart_ajax",
				step: 3,
			},
			success: function(data) {}
		});
		$(".cwmp_woo_wrapper .cwmp_woo_form_payment").css("opacity","1");
		$('.cwmp_box_method_shipping').addClass('box-success');
		$(".section_payment").removeClass("hide");
		$(".cwmp_form_method_shipping .cwmp_method_shipping").addClass("hide");
		$(".cwmp_form_method_shipping .woocommerce-shipping-methods").addClass("hide");
		$("#cwmp_step_4").addClass("hide");
		if($("input.shipping_method").attr("type")=="radio"){
			var entrega_escolhida = $("input.shipping_method:checked", ".woocommerce-checkout").attr("id");
		}else{
			var entrega_escolhida = $("input.shipping_method", ".woocommerce-checkout").attr("id");
		}
		var html_entrega = $("label[for=\'" + entrega_escolhida + "\']").html();
		$('.cwmp_retorno_shipping_method').removeClass('hide');
		$(".cwmp_retorno_shipping_method div:nth-child(2) strong").html($(".cwmp_form_method_shipping div[selected='selected'] div:nth-child(1) h4").html());
		$(".cwmp_retorno_shipping_method div:nth-child(2) p:nth-child(2)").html($(".cwmp_form_method_shipping div[selected='selected'] div:nth-child(1) p").html());
		$(".cwmp_retorno_shipping_method div:nth-child(2) p:nth-child(3)").html($(".cwmp_form_method_shipping div[selected='selected'] div:nth-child(2) span").html());
		return false;
	});
	
	
	
	if(cwmp.AddressAutoBR=="S"){
		$("#billing_postcode").keyup(function(){
			var billing_postcode = $("#billing_postcode").val();
			if(billing_postcode.length==8 || billing_postcode.length==9){
				var cep = $(this).val();
				if (cep != "") {
						$(".cwmp_loading_address").removeClass("hide");
						$.ajax({
							type: "POST",
							url: cwmp.ajaxUrl,
							data: { "action": "cwmp_address_ajax", "cep": $(this).val() },
							dataType:"text",
							success: function(dados) {
								if(dados){
									objeto = JSON.parse(dados);
									$(".cwmp_loading_address").addClass("hide");
									$("#billing_address_1_field").removeClass("hide");
									$("#billing_number_field").removeClass("hide");
									$("#billing_address_2_field").removeClass("hide");
									$("#billing_neighborhood_field").removeClass("hide");
									$("#billing_address_return").removeClass("hide");
									$("#billing_city_field").removeClass("hide");
									$("#billing_state_field").removeClass("hide");
									if(cwmp.fieldCountry!="S"){
										$("#billing_country_field").removeClass("hide");
									}
									$("#cwmp_step_2").removeClass("hide");
									$("#billing_postcode_field span.error").addClass("hide");
									$("#billing_address_return").removeClass("hide");
									
									$("input#billing_address_1").val(objeto.logradouro);
									$("#billing_neighborhood").val(objeto.bairro);
									if(objeto.localidade){
										$("#billing_city").val(objeto.localidade);
									}
									if(objeto.cidade){
										$("#billing_city").val(objeto.cidade);
									}
									$("#billing_state").val(objeto.uf);
									$("#billing_country").val("BR");
									if(objeto.localidade){
										$("#billing_address_return .cwmp_return_city").html(objeto.localidade);
									}
									if(objeto.cidade){
										$("#billing_address_return .cwmp_return_city").html(objeto.cidade);
									}
									$("#billing_address_return .cwmp_return_state").html(objeto.uf);
									if(objeto.logradouro!==""){
										$("#billing_address_1").focus();	
									}else{
										$("#billing_number").focus();
									}
									$("body").trigger("update_checkout");
								}else{
									$(".cwmp_loading_address").addClass("hide");
									$("#billing_address_1_field").removeClass("hide");
									$("#billing_number_field").removeClass("hide");
									$("#billing_address_2_field").removeClass("hide");
									$("#billing_neighborhood_field").removeClass("hide");
									$("#billing_address_return").removeClass("hide");
									$("#billing_city_field").removeClass("hide");
									$("#billing_state_field").removeClass("hide");
									$("#billing_country_field").removeClass("hide");
									$("#cwmp_step_2").removeClass("hide");
									$("#billing_postcode_field span.error").addClass("hide");
									$("#billing_address_return").removeClass("hide");
									$("#billing_address_1").focus();	
								}
							}
						});
					
				}else{}
			}else{
				$("#billing_address_return").addClass("hide");
				$("#billing_address_1_field").addClass("hide");
				$("#billing_number_field").addClass("hide");
				$("#billing_address_2_field").addClass("hide");
				$("#billing_neighborhood_field").addClass("hide");
				$("#billing_address_return").addClass("hide");
				$("#billing_city_field").addClass("hide");
				$("#billing_state_field").addClass("hide");
				$("#billing_city_field").addClass("hide");
				$("#billing_state_field").addClass("hide");
				if(cwmp.fieldCountry!="S"){
					$("#billing_country_field").addClass("hide");
				}
				$("#cwmp_step_2").addClass("hide");
			}
		});
		
		var billing_postcode = $("#billing_postcode").val();
		if(billing_postcode.length==8 || billing_postcode.length==9){
			var cep = billing_postcode.replace("/\D/g", "\'\'");
				$.ajax({
					type: "POST",
					url: cwmp.ajaxUrl,
					data: { "action": "cwmp_address_ajax", "cep": cep },
					dataType:"text",
					success: function(dados) {
						$(".cwmp_loading_address").addClass("hide");
						if(dados){
							var objeto = JSON.parse(dados);
							$("#billing_address_1").val(objeto.logradouro);
							$("#billing_neighborhood").val(objeto.bairro);
							if(objeto.localidade){
								$("#billing_city").val(objeto.localidade);
							}
							if(objeto.cidade){
								$("#billing_city").val(objeto.cidade);
							}
							$("#billing_state").val(objeto.uf);
							$("#billing_country").val("BR");
						}
					}
				});
		}
		var billing_postcode = $("#billing_postcode").val();
		if(billing_postcode.length==8 || billing_postcode.length==9){
			$("#billing_address_1_field").removeClass("hide");
			$("#billing_number_field").removeClass("hide");
			$("#billing_address_2_field").removeClass("hide");
			$("#billing_neighborhood_field").removeClass("hide");
			$("#billing_address_return").removeClass("hide");
			$("#billing_city_field").removeClass("hide");
			$("#billing_state_field").removeClass("hide");
			if(cwmp.fieldCountry!="S"){
				$("#billing_country_field").removeClass("hide");
			}
			$("#cwmp_step_2").removeClass("hide");
			$("#billing_address_return .cwmp_return_city").html($("#billing_city").val());
			$("#billing_address_return .cwmp_return_state").html($("#billing_state").val());
		}
	}

	$("form.checkout").on('change',".shipping_method ",function(){
		
		$.ajax({
			type: "POST",
			url: cwmp.ajaxUrl,
			data: {
				action: "cwmpAddEventAddRate",
				method_shipping: $(this).val(),
				billing_first_name: $("#billing_first_name").val(),
				billing_last_name: $("#billing_last_name").val(),
				billing_email: $("#billing_email").val(),
				billing_phone: $("#billing_phone").val(),
				billing_company: $("#billing_company").val(),
				billing_address_1: $("#billing_address_1").val(),
				billing_address_2: $("#billing_address_2").val(),
				billing_city: $("#billing_city").val(),
				billing_state: $("#billing_state").val(),
				billing_country: $("#billing_country").val(),
				billing_postcode: $("#billing_postcode").val(),
			},
			success: function(data) {
				$('body').append(data);
			}
		});
		return false;
	});
	$("form.checkout").on('change','input[name="payment_method"]',function(){
		$.ajax({
			type: "POST",
			url: cwmp.ajaxUrl,
			data: {
				action: "cwmpAddEventPaymentInfo",
				payment_method: $(this).val(),
				billing_first_name: $("#billing_first_name").val(),
				billing_last_name: $("#billing_last_name").val(),
				billing_email: $("#billing_email").val(),
				billing_phone: $("#billing_phone").val(),
				billing_company: $("#billing_company").val(),
				billing_address_1: $("#billing_address_1").val(),
				billing_address_2: $("#billing_address_2").val(),
				billing_city: $("#billing_city").val(),
				billing_state: $("#billing_state").val(),
				billing_country: $("#billing_country").val(),
				billing_postcode: $("#billing_postcode").val(),
			},
			success: function(data) {
				$('body').append(data);
			}
		});
		return false;
	});

	$("form.checkout").on( "click", "a#cwmp_step_3", function() {
		$(".cwmp_form_method_shipping").removeClass("hide");
		$("#shipping_method").removeClass("hide");
		return false;
	});
	$("input#ship-to-different-address-checkbox").click(function(){
		if($(this).is(":checked")){
			$("#shipping_first_name_field").removeClass("hide");
		}else{
			$("#shipping_first_name_field").addClass("hide");
		}
	});
	$(".edit_billing").click(function(){
			$(".cwmp_retorno_billing").addClass("hide");
			$(".cwmp_woo_form_billing").removeClass("box-success");
			$("#cwmp_edit_step_1").removeClass("hide");
			$(".cwmp_woo_form_billing .cwmp-form-row").removeClass("hide");
			if($("select#billing_persontype").val()=="1"){
				$("#billing_cpf_field").removeClass("hide");
				$("#billing_rg_field").removeClass("hide");
				$("#billing_cnpj_field").addClass("hide");
				$("#billing_company_field").addClass("hide");
				$("#billing_ie_field").addClass("hide");
			}
			if($("select#billing_persontype").val()=="2"){
				$("#billing_cpf_field").removeClass("hide");
				$("#billing_rg_field").addClass("hide");
				$("#billing_cnpj_field").removeClass("hide");
				$("#billing_company_field").removeClass("hide");
				$("#billing_ie_field").removeClass("hide");

			}
		return false;
	});
	$(".edit_shipping").click(function(){
			$(".woocommerce-shipping-fields").removeClass("hide");
			$(".cwmp_woo_form_shipping").removeClass("box-success");
			$(".cwmp_woo_form_shipping .cwmp_retorno_shipping").addClass("hide");
			$(".woocommerce-shipping-methods").addClass("hide");
			$(".cwmp_form_method_shipping .cwmp_mobile").addClass("hide");
		return false;
	});
	$("form.checkout").on( "click", "a.edit_shipping_method", function() {
		$.ajax({
			type: "POST",
			url: cwmp.ajaxUrl,
			data: {	
				action: "cwmp_step_cart_ajax",
				step: 2,
			},
			success: function(data) {}
		});
		$('#cwmp_step_4').removeClass('hide');
		$('.cwmp_retorno_shipping_method').addClass('hide');
		$('.cwmp_method_shipping').removeClass('hide');
		$('.cwmp_box_method_shipping').removeClass('box-success');
		return false;
	});
	$("form.checkout").on( "click", "a.cwmp_button_order", function() {
		
		var value_input = $("input[name=payment_method]:checked").val();
		var cwmp_form_status = "";
		 if($("input#billing_email").val()==""){
			 if(isValidEmailAddress($("input#billing_email").val())){
				 $("#billing_email_field").removeClass("field_error");
				 cwmp_form_status = "";
			 }else{
				$("#billing_email_field").addClass("field_error"); 
				cwmp_form_status = "true";
			 }
		}else{
		}
		 if(cwmp_form_status == "true"){
		 }else{
			if(value_input=="pagaleve-pix"){
				$( "#pagaleve_place_order" ).trigger( "click" );
			}else{
				$( "#place_order" ).trigger( "click" );
				
			}
		 }
		return false;
	});
	$("form#order_review").on( "click", "a.cwmp_button_order", function() {
		var cwmp_form_status = "";
		 if($("#billing_email").val()==""){
			 if(isValidEmailAddress($("#billing_email").val())){
				 $("#billing_email_field").removeClass("field_error");
				 cwmp_form_status = "";
			 }else{
				$("#billing_email_field").addClass("field_error"); 
				cwmp_form_status = "true";
			 }
		}else{
		}
		 if(cwmp_form_status == "true"){
		 }else{
			 $( "#place_order" ).trigger( "click" );
		 }
		return false;
	});

	$('form.checkout').on( 'click', '.title_payment label', function() {
		$('body').trigger('update_checkout');
	});
	$('form.checkout').on( 'click', '.cwmp_method_shipping', function() {
		var id = $(this).attr("id");
		$('.cwmp_method_shipping').removeClass("active");
		$(this).addClass("active");
		$(".cwmp_form_method_shipping select").val(id).trigger("change");
	});
	$('form.checkout').on( 'click', 'a.cwmp_add_order_bump', function() {
		
		$.ajax({
			type: 'POST',
			url: cwmp.ajaxUrl,
			data: {
				action: 'cwmp_add_order_bump',
				product: $(this).attr('id')
			},
			success: function(data) {
				$('body').trigger('update_checkout');
			}
		});
		return false;
	});
	$('form.checkout').on( 'click', 'a.cwmp_not_order_bump', function() {
		$(".bump").addClass('hide');
		return false;
	});
	var selected_Id = $('input[name="payment_method"]:checked').attr('id');
	$('label[for="'+selected_Id+'"] .no-active').addClass('hide');
	$('label[for="'+selected_Id+'"] .active').removeClass('hide');
	
});