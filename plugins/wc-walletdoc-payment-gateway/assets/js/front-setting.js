/* 

 * To change this license header, choose License Headers in Project Properties.

 * To change this template file, choose Tools | Templates

 * and open the template in the editor.

 */



var isCardName = true;

var isCardNumber = true;

var isCardExp = true;

var isCardCvv = true;

var countToken = 0;

jQuery(function ($) {

	'use strict';





	if (isEmpty(wc_walletdoc_params.publicKey)) {



	} else {

		try {

			var walletdoc = Walletdoc(wc_walletdoc_params.publicKey);

		} catch (error) {

			console.log(error);

			return;

		}



	}











	/**

	 * Object to handle Walletdoc admin functions.

	 */

	var wc_walletdoc_admin = {

		isTestMode: function () {

			return $('#woocommerce_walletdoc_testmode').is(':checked');

		},

		isSavedMode: function () {

			return $('#woocommerce_walletdoc_saved_cards').is(':checked');

		},



		getSecretKey: function () {

			if (wc_walletdoc_admin.isTestMode()) {

				return $('#woocommerce_walletdoc_client_secret').val();

			} else {

				return $('#woocommerce_walletdoc_production_secret').val();

			}

		},



		/**

		 * Unmounts all walletdoc elements when the checkout page is being updated.

		 */

		unmountElements: function () {





			walletdoc_card.unmount('#walletdoc-card-element');

			walletdoc_exp.unmount('#walletdoc-exp-element');

			walletdoc_cvc.unmount('#walletdoc-cvc-element');



		},



		/**

				 * Mounts all elements to their DOM nodes on initial loads and updates.

				 */

		mountElements: function () {

			if (!$('#walletdoc-card-element').length) {

				return;

			}





			walletdoc_card.mount('#walletdoc-card-element');

			walletdoc_exp.mount('#walletdoc-exp-element');

			walletdoc_cvc.mount('#walletdoc-cvc-element');

		},





		/**

	 * Creates all walletdoc elements that will be used to enter cards or IBANs.

	 */

		createElements: function () {

			countToken = 0;

			var hostedFields = walletdoc.hostedFields();



			var walletdoc_name = hostedFields.create('cardHolder');

			walletdoc_name.mount('walletdoc-name-element');



			var walletdoc_card = hostedFields.create('cardNumber');

			walletdoc_card.mount('walletdoc-card-element');



			var walletdoc_cvc = hostedFields.create('cardCvv');

			walletdoc_cvc.mount('walletdoc-cvc-element');



			var walletdoc_exp = hostedFields.create('cardExpiry');

			walletdoc_exp.mount('walletdoc-exp-element');



			var errName = $(".walletdoc-source-errors");

			var token = $(".token");

			var errorArr = [];





			walletdoc_name.on('change', function (nameEvent) {

 



				if (!isEmpty(nameEvent.error.message)) {



					errorArr[0] = nameEvent.error.message;



					isCardName = true;



				} else {



					isCardName = false;



				}

				wc_walletdoc_admin.checkError(errorArr);

			});









			walletdoc_card.on('change', function (cardEvent) {





				if (!isEmpty(cardEvent.error.message)) {



					errorArr[1] = cardEvent.error.message;

					isCardNumber = true;

				} else {





					isCardNumber = false





				}

				wc_walletdoc_admin.checkError(errorArr);

			});







			walletdoc_cvc.on('change', function (cvcEvent) {





				if (!isEmpty(cvcEvent.error.message)) {





					isCardCvv = true

				} else {





					isCardCvv = false;





				}

				wc_walletdoc_admin.checkError(errorArr);

			});



			walletdoc_exp.on('change', function (expEvent) {



				if (!isEmpty(expEvent.error.message)) {



					isCardExp = true;

				} else {





					isCardExp = false;





				}

				wc_walletdoc_admin.checkError(errorArr);



			});





		},







		checkError(errorArr) {



		





			if (!isCardExp && !isCardCvv && !isCardNumber && !isCardName) {





				document.getElementById("place_order").disabled = false;



		

					walletdoc.createToken().then(showoutcome);

				



			} else {

				document.getElementById("place_order").disabled = true;

				$("#token").val('');

			}

		},















		/**

		 * Initialize.

		 */

		init: function () {



			$( document.body ).on( 'updated_checkout', function() {

				// console.log("checkout");

				addClass(document.getElementById("cardDetailForm"), "hide");

				removeClass(document.getElementById("cardDetailForm"), "show");

				wc_walletdoc_admin.createElements();



				$("li").on("click", function (event) {





					if (event.target.id == "wc-walletdoc-payment-token-new") {

						removeClass(document.getElementById("cardDetailForm"), "hide");

						addClass(document.getElementById("cardDetailForm"), "show");

						document.getElementById("place_order").disabled = true;

					

					}else{

						removeClass(document.getElementById("cardDetailForm"), "show");

						addClass(document.getElementById("cardDetailForm"), "hide");

						document.getElementById("place_order").disabled = false;

					

					}

				});



				if(!isEmpty(wc_walletdoc_params.transaction_id)){

					// console.log("coming to call process api ",wc_walletdoc_params);

				}



			



			} );



			jQuery( function($) {     

				$("form.woocommerce-checkout")

				.on('submit', function() {

				

					setInterval(() => {

					

					}, 1000);

					 } ); 

			  } );





			$(document.body).on('change', '#woocommerce_walletdoc_testmode', function () {

				var sandbox_secret_key = $('#woocommerce_walletdoc_client_secret').parents('tr').eq(0),

					production_secret_key = $('#woocommerce_walletdoc_production_secret').parents('tr').eq(0);



				if ($(this).is(':checked')) {

					sandbox_secret_key.show();

					production_secret_key.hide();



				} else {

					sandbox_secret_key.hide();

					production_secret_key.show();

				}

			});



			$('#woocommerce_walletdoc_testmode').change();



			$(document.body).on('change', '#woocommerce_walletdoc_saved_cards', function (event) {



				if (wc_walletdoc_admin.isSavedMode() == false) {

					alert("Saved cards must be enabled for subscription payments");

				}



			});







			// add payment method page



			if ($('form#add_payment_method').length) {



				if (!isEmpty(wc_walletdoc_params.publicKey)) {

					wc_walletdoc_admin.createElements();

					addClass(document.getElementById("cardDetailForm"), "show");

					removeClass(document.getElementById("cardDetailForm"), "hide");

					this.form = $('form#add_payment_method');

				}





			}







			$("#add_payment_method").on("click", function (event) {



				addClass(document.getElementById("cardDetailForm"), "show");

				removeClass(document.getElementById("cardDetailForm"), "hide");



			});





			$(".change_payment_method").on("click", function (event) {



				if (document.getElementById("cardDetailForm")) {

					addClass(document.getElementById("cardDetailForm"), "hide");

					removeClass(document.getElementById("cardDetailForm"), "show");

				}





			});







			$(function () {

				let countCheck = 0;



				$("li").on("click", function (event) {

					if (event.target.id == "wc-walletdoc-payment-token-new" || event.target.id == "wc-walletdoc-cc-form") {

						// console.log("coming here");

						if (countCheck == 0) {

							countCheck = 1;



							if (document.getElementById("cardDetailForm").classList.contains('show') == false) {

								wc_walletdoc_admin.createElements();

								addClass(document.getElementById("cardDetailForm"), "show");

								removeClass(document.getElementById("cardDetailForm"), "hide");

								document.getElementById("place_order").disabled = true;

							}





						} else {



							removeClass(document.getElementById("cardDetailForm"), "hide");

							addClass(document.getElementById("cardDetailForm"), "show");



							document.getElementById("place_order").disabled = false;

						}



					} else if (!isEmpty(event.target.id) && event.target.id != "walletdoc-exp-element") {







						if (document.getElementById("cardDetailForm")) {

							addClass(document.getElementById("cardDetailForm"), "hide");

							removeClass(document.getElementById("cardDetailForm"), "show");

							document.getElementById("place_order").disabled = false;

						}





					}



				



				});

			});



		}

	};



	/**

		 * Performs payment-related actions when a checkout/payment form is being submitted.

		 *

		 * @return {boolean} An indicator whether the submission should proceed.

		 *                   WooCommerce's checkout.js stops only on `false`, so this needs to be explicit.

		 */

	 function onSubmit() {

	

		// console.log("check submit button ");

	}





	function showoutcome(result) {





	

		var errName = $(".walletdoc-source-errors");

		if (!isEmpty(result) && !isEmpty(result.error)) {



			errName.html(result.error.message);

			document.getElementById("place_order").disabled = true;





		} else {



			addClass(document.getElementById("cardDetailForm"), "show");

			removeClass(document.getElementById("cardDetailForm"), "hide");

			if (!isEmpty(result) && !isEmpty(result.id)) {

				errName.html('');

				$("#token").val(result.id);

				document.getElementById("place_order").disabled = false;

			}

		}



	}

	function namePasteEvent(data) {



		if (!isEmpty(nameEvent.error.message)) {



			errorArr[0] = nameEvent.error.message;



			isCardName = true;



		} else {



			isCardName = false;



		}

		wc_walletdoc_admin.checkError(errorArr);

	}



	function isEmpty(value) {



		return (!value || value == null || value == undefined || value == "" || value.length == 0);

	}





	function hasClass(el, className) {

		if (!isEmpty(el.classList))

			return el.classList.contains(className);

		return !!el.className.match(new RegExp('(\\s|^)' + className + '(\\s|$)'));

	}



	function addClass(el, className) {

		if (!isEmpty(el.classList))

			el.classList.add(className)

		else if (!hasClass(el, className))

			el.className += " " + className;

	}



	function removeClass(el, className) {

		if (!isEmpty(el.classList))

			el.classList.remove(className)

		else if (hasClass(el, className)) {

			var reg = new RegExp('(\\s|^)' + className + '(\\s|$)');

			el.className = el.className.replace(reg, ' ');

		}

	}



	





	wc_walletdoc_admin.init();



	

	 

});



