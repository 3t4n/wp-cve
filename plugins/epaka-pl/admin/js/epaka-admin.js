(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	var availableCouriers = [];

	window.setAvailableCouriers = function (couriers){
		$(couriers).each(function(index){
			couriers[index].courierName = decodeURI(couriers[index].courierName).replace("+"," ");
		});
		availableCouriers = couriers;
	}

	window.changeTab = function(name) {
		var i;
		var x = document.getElementsByClassName("tab");
		for (i = 0; i < x.length; i++) {
		  x[i].style.display = "none";  
		}
		document.getElementById(name).style.display = "block";  
	}

	window.getEpakaOrderLabel = function(orderId){
		$.post(epaka_admin_object.api_endpoint+"/get-epaka-order-label?token="+epaka_admin_object.admin_token,
		{
			orderId: orderId
		}
		,function(data){
			var byteCharacters = atob(data);
			var byteNumbers = new Array(byteCharacters.length);
			for (var i = 0; i < byteCharacters.length; i++) {
				byteNumbers[i] = byteCharacters.charCodeAt(i);
			}
			var byteArray = new Uint8Array(byteNumbers);
			var file = new Blob([byteArray], { type: 'application/pdf;base64' });
			var fileURL = URL.createObjectURL(file);
			window.open(fileURL);
		}).fail(function(){
			alert("Nie udało się pobrac etykiety.");
		});
	}

	window.setParcelNumberToTrack = function(label){
		$(".epaka-order-tracking").find(".epaka-tracking-loading").show();

		$.post(epaka_admin_object.api_endpoint+"/get-epaka-courier-tracking?token="+epaka_admin_object.admin_token,
		{
			label: label
		},
		function(data){
			$(".epaka-order-tracking").find(".epaka-tracking-loading").hide();
			if(Array.isArray(data.step)){
				$(data.step).each(function(){
					var time = new Date(this.time);
					var tr = document.createElement("tr");
					$(tr).append("<td>"+time.toLocaleDateString()+"</td>");
					$(tr).append("<td>"+time.toLocaleTimeString()+"</td>");
					$(tr).append("<td>"+this.status_code_desc+"</td>");
					$(tr).append("<td>"+this.location+"</td>");
					
					$(".epaka-order-tracking").find("tbody").append(tr);
				});
			}else{
				var time = new Date(data.step.time);
				var tr = document.createElement("tr");
				$(tr).append("<td>"+time.toLocaleDateString()+"</td>");
				$(tr).append("<td>"+time.toLocaleTimeString()+"</td>");
				$(tr).append("<td>"+data.step.status_code_desc+"</td>");
				$(tr).append("<td>"+data.step.location+"</td>");

				$(".epaka-order-tracking").find("tbody").append(tr);
			}
		}).fail(function(){
			$(".epaka-order-tracking").find(".epaka-tracking-loading").hide();
			$(".epaka-order-tracking").find("tbody").append("<tr><td colspan='4'><center>Brak danych...</center></td></tr>");
		});
	}

	window.unlinkEpakaOrderFromWooOrder = function(wooOrderId){
		var r = confirm("Czy na pewno chcesz złożyć nowe zamówienie?(Stare zostanie odpięte)");
		if(r == true){
			$.post(epaka_admin_object.api_endpoint+"/unlink-epaka-order-from-woo-order?token="+epaka_admin_object.admin_token,
			{
				woo_order_id: wooOrderId
			},
			function(data){
				window.location.reload();
			}).fail(function(){
				alert("Nie udało się odlinkować zamówienia Epaka.");
			});
		}
	}

	window.cancelEpakaOrder = function(epakaOrderId){
		var r = confirm("Czy na pewno chcesz anulować zamówienie?");
		if(r == true){
			$.post(epaka_admin_object.api_endpoint+"/cancel-epaka-order?token="+epaka_admin_object.admin_token,
			{
				epaka_order_id: epakaOrderId
			},
			function(data){
				window.location.reload();
			}).fail(function(){
				alert("Nie udało się odlinkować zamówienia Epaka.");
			});
		}
	}

	window.getEpakaOrderLabelZebra = function(orderId){
		$.post(epaka_admin_object.api_endpoint+"/get-epaka-order-label-zebra?token="+epaka_admin_object.admin_token,
		{
			orderId: orderId
		}
		,function(data){
			var byteCharacters = atob(data);
			var byteNumbers = new Array(byteCharacters.length);
			for (var i = 0; i < byteCharacters.length; i++) {
				byteNumbers[i] = byteCharacters.charCodeAt(i);
			}
			var byteArray = new Uint8Array(byteNumbers);
			var file = new Blob([byteArray], { type: 'application/pdf;base64' });
			var fileURL = URL.createObjectURL(file);
			window.open(fileURL);
		}).fail(function(){
			alert("Nie udało się pobrać etykiety zebra.");
		});
	}

	window.getEpakaOrderProforma = function(orderId){
		$.post(epaka_admin_object.api_endpoint+"/get-epaka-order-proforma?token="+epaka_admin_object.admin_token,
		{
			orderId: orderId
		}
		,function(data){
			var byteCharacters = atob(data);
			var byteNumbers = new Array(byteCharacters.length);
			for (var i = 0; i < byteCharacters.length; i++) {
				byteNumbers[i] = byteCharacters.charCodeAt(i);
			}
			var byteArray = new Uint8Array(byteNumbers);
			var file = new Blob([byteArray], { type: 'application/pdf;base64' });
			var fileURL = URL.createObjectURL(file);
			window.open(fileURL);
		}).fail(function(){
			alert("Nie udało się pobrać proformy.");
		});
	}

	window.getEpakaOrderProtocol = function(orderId){
		$.post(epaka_admin_object.api_endpoint+"/get-epaka-order-protocol?token="+epaka_admin_object.admin_token,
		{
			orderId: orderId
		}
		,function(data){
			var byteCharacters = atob(data);
			var byteNumbers = new Array(byteCharacters.length);
			for (var i = 0; i < byteCharacters.length; i++) {
				byteNumbers[i] = byteCharacters.charCodeAt(i);
			}
			var byteArray = new Uint8Array(byteNumbers);
			var file = new Blob([byteArray], { type: 'application/pdf;base64' });
			var fileURL = URL.createObjectURL(file);
			window.open(fileURL);
		}).fail(function(){
			alert("Nie udało się pobrać protokołu.");
		});
	}

	window.openPaymentForOrder = function(url){
		var win = window.open(url, '_blank');
  		win.focus();
	}

	window.addExistingOrder = function(elem, postID){
		var epakaID = $(elem).parent().find("input").val();
		$.post(epaka_admin_object.api_endpoint+"/link-epaka-order-to-woo-order?token="+epaka_admin_object.admin_token,
		{
			woo_order_id: postID,
			epaka_order_id: epakaID
		}
		,function(data){
			window.location.reload();
		}).fail(function(){
			alert("Nie udało się podpiąć zamówienia.");
		});
	}

	window.showInputForAddExisting = function(elem){
		if(!$(elem).parent().find(".epaka-add-existing-order").is(":visible")){
			$(elem).parent().find(".epaka-add-existing-order").show();
			$(elem).text("Ukryj");
		}else{
			$(elem).parent().find(".epaka-add-existing-order").hide();
			$(elem).text("Dodaj istniejące zamówienie");
		}
	}

	window.getEpakaOrderAuthorizationDocument = function(orderId){
		$.post(epaka_admin_object.api_endpoint+"/get-epaka-order-authorization-document?token="+epaka_admin_object.admin_token,
		{
			orderId: orderId
		}
		,function(data){
			var byteCharacters = atob(data);
			var byteNumbers = new Array(byteCharacters.length);
			for (var i = 0; i < byteCharacters.length; i++) {
				byteNumbers[i] = byteCharacters.charCodeAt(i);
			}
			var byteArray = new Uint8Array(byteNumbers);
			var file = new Blob([byteArray], { type: 'application/pdf;base64' });
			var fileURL = URL.createObjectURL(file);
			window.open(fileURL);
		}).fail(function(){
			alert("Nie udało się pobrać upoważnienia.");
		});
	}


	$( window ).load(function() {
		$(".epaka-add-existing-order").find("input").click(function(e){
			e.preventDefault();
			return false;
		});

		$("#logout").click(function(){
			// console.log(window.location.origin+window.location.pathname+"?page=epaka_admin_panel_logout");
			window.location.href = window.location.origin+window.location.pathname+"?page=epaka_admin_panel_logout";
		});

		$("#saveMethods").click(function(){
			var invokingElement = $(this);
			$(invokingElement).prop( "disabled", true );

			var methodForm = $("#panel-form-methods").serializeArray();

			$.post(epaka_admin_object.api_endpoint+"/set-shipping-courier-mapping?token="+epaka_admin_object.admin_token,methodForm,
			function(data){
				$(invokingElement).prop( "disabled", false);
				if(data.status != "OK"){
					window.addAlert(data.message,'danger');
				}else{
					window.addAlert('Zapisano mapowanie','success');
				}
			}).fail(function(){
				$(invokingElement).prop( "disabled", false);
				window.addAlert('Wystąpił błąd przy połączeniu z API.','danger');
			});
		});

		$('.method-courier-map-value').each(function(){
			var elem = $(this).find("option:selected");

			var selectedCourier = availableCouriers.filter(function(value){
				return value.courierId == $(elem).val();
			});

			if(Array.isArray(selectedCourier) && selectedCourier.length > 0 && selectedCourier[0].courierPointDelivery == "1"){
				$(this).parent().find("[type=checkbox]").attr("checked",true);
				$(this).parent().find(".map_source_id").val(selectedCourier[0].courierMapSourceId);
				$(this).parent().find(".map_source_url").val(selectedCourier[0].courierMapSourceUrl);
				$(this).parent().find(".map_source_name").val(selectedCourier[0].courierMapSourceName);
			}
		});

		$('.method-courier-map-value').change(function(event){
			var selectedCourier = availableCouriers.filter(function(value){
				return value.courierId == $(event.target).val();
			});

			if(Array.isArray(selectedCourier) && selectedCourier.length != 0){
				if(selectedCourier[0].courierPointDelivery == "1"){
					$(event.target).parent().find("input[type=checkbox]").attr('checked',true);
					$(event.target).parent().find(".map_source_id").val(selectedCourier[0].courierMapSourceId);
					$(event.target).parent().find(".map_source_url").val(selectedCourier[0].courierMapSourceUrl);
					$(event.target).parent().find(".map_source_name").val(selectedCourier[0].courierMapSourceName);
				}else{
					$(event.target).parent().find("input[type=checkbox]").attr('checked',false);
					$(event.target).parent().find(".map_source_id").val("");
					$(event.target).parent().find(".map_source_url").val("");
					$(event.target).parent().find(".map_source_name").val("");
				}
			}else{
				$(event.target).parent().find("input[type=checkbox]").attr('checked',false);
				$(event.target).parent().find(".map_source_id").val("");
				$(event.target).parent().find(".map_source_url").val("");
				$(event.target).parent().find(".map_source_name").val("");
			}
		});

		$("#saveProfile").click(function(){
			var invokingElement = $(this);
			$(invokingElement).prop( "disabled", true );

			var formsDataArrays = [];
			$(".profile-forms form").each(function(key,form){
				formsDataArrays.push($(form).serializeArray());
			})

			var formData = [];
			formsDataArrays.forEach(function(value, key){
				formData = formData.concat(value);
			});
			
			$.post(epaka_admin_object.api_endpoint+"/save-profile?token="+epaka_admin_object.admin_token,formData,
			function(data){
				$(invokingElement).prop( "disabled", false);
				if(data.status != "OK"){
					window.addAlert(data.message,'danger');
				}else{
					window.addAlert('Zapisano profil','success');
				}
				
			}).fail(function(){
				$(invokingElement).prop( "disabled", false);
				window.addAlert('Wystąpił błąd przy połączeniu z API.','danger');
			});
		});

		$(".epakainvoicecheckbox").change(function(){
			switch($(this).attr("id")){
				case "UzytkownikBezFakturyCheckbox":
				{
					$("#UzytkownikFakturaPoPlatnosciCheckbox").prop('checked',false);
					$("#UzytkownikFakturaZbiorczaCheckbox").prop('checked',false);
				break;
				}
				case "UzytkownikFakturaPoPlatnosciCheckbox":
				{
					$("#UzytkownikBezFakturyCheckbox").prop('checked',false);
					$("#UzytkownikFakturaZbiorczaCheckbox").prop('checked',false);
				break;
				}
				case "UzytkownikFakturaZbiorczaCheckbox":
				{
					$("#UzytkownikFakturaPoPlatnosciCheckbox").prop('checked',false);
					$("#UzytkownikBezFakturyCheckbox").prop('checked',false);
				break;
				}
			}

			if($(this).prop("checked") == false && $("#UzytkownikFakturaPoPlatnosciCheckbox").prop('checked') == false 
			&& $("#UzytkownikBezFakturyCheckbox").prop('checked') == false && $("#UzytkownikFakturaZbiorczaCheckbox").prop('checked') == false){
				$(this).prop("checked",true);
			}
		});
	});
})( jQuery );
