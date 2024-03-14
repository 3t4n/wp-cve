jQuery(document).ready(function ($) {
	'use strict';

	// Prevent to input character or special char in loan amount field
	$(document).on("keypress", "#loan_amount", function(event) {
	var charCode = event.which || event.keyCode;
	var inputVal = $(this).val();
		if (charCode < 48 || charCode > 57 || inputVal.length >= 9) {
		event.preventDefault();
		}
	});


	//For Dropdown Filed (Payment Mode)
	// const inputs = document.getElementsByClassName('js-fake-input');
	// 	const selects = document.getElementsByClassName('js-select');

	// 	for (let i = 0; i < inputs.length; i++) {
	// 	  getSelected(i);
	// 	  selects[i].addEventListener("change", function() {
	// 	    getSelected(i);

	// 	  });
	// 	}

	// 	function getSelected(i){
	// 	  inputs[i].value = selects[i].options[selects[i].selectedIndex].text;
	// 	  var translated_text ="1";
		
	// }
	

    // Close form when clicked outside (Popup form)
   	/* jQuery(document).click(function(event) {
	        if (!jQuery(event.target).closest('.jotform-form, button.contact-book-btn').length) {
	            jQuery('.contact-us-popup').hide();
	      }
	  });*/



});


