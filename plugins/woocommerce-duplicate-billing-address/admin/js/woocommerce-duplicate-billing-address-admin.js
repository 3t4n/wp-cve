(function( $ ) {
	'use strict';

	$(function() {
		var dupBillAddress = document.getElementById('duplicate_billing_address');
		
		// shipping fields
		var sFirstName = document.getElementById('shipping_first_name');
		var sLastName = document.getElementById('shipping_last_name');
		var sCompany = document.getElementById('shipping_company');
		var sAddress1 = document.getElementById('shipping_address_1');
		var sAddress2 = document.getElementById('shipping_address_2');
		var sCity = document.getElementById('shipping_city');
		var sPostcode = document.getElementById('shipping_postcode');
		var sCountry = document.getElementById('shipping_country');
		var sState = document.getElementById('shipping_state');
		
		// billing fields
		var bFirstName = document.getElementById('billing_first_name');
		var bLastName = document.getElementById('billing_last_name');
		var bCompany = document.getElementById('billing_company');
		var bAddress1 = document.getElementById('billing_address_1');
		var bAddress2 = document.getElementById('billing_address_2');
		var bCity = document.getElementById('billing_city');
		var bPostcode = document.getElementById('billing_postcode');

		if( dupBillAddress != null ) {
			dupBillAddress.checked = false;		
			dupBillAddress.addEventListener('change', billingCheckboxChanged, false);
		}
		
		
		function billingCheckboxChanged() {
			if( this.checked ) {
				sFirstName.value = bFirstName.value;
				sLastName.value = bLastName.value;
				sCompany.value = bCompany.value;
				sAddress1.value = bAddress1.value;
				sAddress2.value = bAddress2.value;
				sCity.value = bCity.value;
				sPostcode.value = bPostcode.value;
				
				$("#shipping_country").val($("#billing_country").val());
				$('#shipping_country').change();
				$("#shipping_state").val($("#billing_state").val());
				$('#shipping_state').change();

			}
			else {
				// clear all values
				var shippingFields = [].slice.call(document.querySelectorAll('[id^=shipping_]'));
				
				for (var i=0; i < shippingFields.length; i++) {
					shippingFields[i].value = '';
				}
				
				$('#shipping_country').change();
				$('#shipping_state').change();
			}
		}
	});

})( jQuery );
