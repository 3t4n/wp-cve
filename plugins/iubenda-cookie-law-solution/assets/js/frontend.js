/**
 * Main iubenda frontend functions
 *
 * @package  Iubenda
 */

(function ($) {

	$( document ).ready(
		function () {

			var iubendaConsentSolution = new function () {
				var _this = this;

				// parse args.
				var args = iubForms;

				// console.log( args );.

				// get forms.
				this.init = function () {
					// loop though plugins.
					if ($( args ).length > 0) {
						$.each(
							args,
							function (source, forms) {
								// loop through forms.
								if ($( forms ).length > 0) {
									$.each(
										forms,
										function (id, form) {
											// console.log( form );.

											// get corresponding html form id.
											switch (source) {

												// WooCommerce Checkout Form.
												case 'woocommerce' :
													var htmlFormContainer = $( '.woocommerce-checkout' );

													// form exists, let's use it.
													if (htmlFormContainer.length > 0) {
														// setup vars.
														formArgs = {
															submitElement: null,
															form: {
																selector: document.querySelectorAll( 'form.woocommerce-checkout' )[0],
																map: form.form.map
															}
														};

														// handle ajax submit.
														$( htmlFormContainer ).on(
															'checkout_place_order',
															function (e) {
																_this.submitForm( form, formArgs );

																_iub.cons.sendData();
																// don't send before page refresh.
																// on successful submit it will be sent automatically.
																// _iub.cons.sendFromLocalStorage();.
															}
														);
													}

													break;

												// WordPress Comment Form.
												case 'wp_comment_form' :
													var htmlFormContainer = $( '#commentform' );

													// form exists, let's use it.
													if (htmlFormContainer.length > 0) {
														// adjust submit element id.
														var submitElement = document.getElementById( htmlFormContainer.attr( 'id' ) ).querySelectorAll( 'input[type=submit]' )[0];

														/* id="submit" override
														if ( typeof submitElement !== 'undefined' && submitElement.name == 'submit' ) {
														submitElement.id = 'wp-comment-submit';
														submitElement.name = 'wp-comment-submit';
														}
															*/

														// setup vars.
														formArgs = {
															// submitElement: submitElement,.
															submitElement: null,
															form: {
																selector: document.getElementById( htmlFormContainer.attr( 'id' ) ),
																map: form.form.map
															}
														};

														$( submitElement ).on(
															'click touchstart',
															function (e) {
																_this.submitForm( form, formArgs );

																_iub.cons.sendData();
																// don't send before page refresh.
																// on successful submit it will be sent automatically.
																// _iub.cons.sendFromLocalStorage();.
															}
														);
													}

													break;

												// Contact Form 7.
												case 'wpcf7' :
													// var regex = new RegExp( '^wpcf7-f([0-9]*)-' );.
													var htmlFormContainer = $( 'div[id^="wpcf7-f' + id + '-"]' );

													// form exists, let's use it.
													if (htmlFormContainer.length > 0) {
														var selector = document.getElementById( htmlFormContainer.attr( 'id' ) ).getElementsByClassName( 'wpcf7-form' )[0];

														// handle ajax submit.
														$( document ).on(
															'wpcf7mailsent',
															selector,
															function (e) {
																// setup vars.
																formArgs = {
																	// submitElement: document.getElementById( htmlFormContainer.attr( 'id' ) ).getElementsByClassName( 'wpcf7-submit' )[0],.
																	submitElement: null,
																	form: {
																		selector: selector,
																		map: form.form.map
																	}
																};

																// send only if specific form has been submitted.
																if ((selector.parentElement.id == e.target.parentElement.id) || (selector.parentElement.id == e.target.id)) {
																	_this.submitForm( form, formArgs );

																	_iub.cons.sendData();
																	_iub.cons.sendFromLocalStorage();
																}
															}
														);

													};

													break;

												// WP Forms.
												case 'wpforms' :
													var htmlFormContainer = $( 'div[id^="wpforms-' + id + '"]' );

													// form exists, let's use it.
													if (htmlFormContainer.length > 0) {
														var selector = document.getElementById( 'wpforms-form-' + id );
														var isAjax   = $( '#wpforms-form-' + id ).hasClass( 'wpforms-ajax-form' );

														// handle ajax submit.
														$( document ).on(
															'wpformsAjaxSubmitSuccess',
															selector,
															function (e) {
																// setup vars.
																formArgs = {
																	submitElement: (isAjax ? null : document.getElementById( 'wpforms-submit-' + id )),
																	form: {
																		selector: selector,
																		map: form.form.map
																	}
																};

																// send only if specific form has been submitted.
																if (selector.id == e.target.id) {
																	_this.submitForm( form, formArgs );

																	_iub.cons.sendData();
																	_iub.cons.sendFromLocalStorage();
																}
															}
														);
													};

													break;
													// Elementor forms.
												case 'elementor_forms' :
													// Iterate over each Elementor form with a parent div that has data-elementor-id="id" and attach the submit_success event listener.
													form_id = id
													if (id.indexOf( '-' ) >= 0) {
														var post_id = id.split( '-' )[0];
														var form_id = id.split( '-' )[1];
													}

													// Find input element with name="post_id" and matching value.
													var input = $( 'input[name="post_id"][value="' + post_id + '"]' );

													// Return if no input element found, indicating that this is not the target post.
													if ( ! input.length) {
														return;
													}

													$( document ).on(
														'submit_success',
														'form.elementor-form:has(input[name="form_id"][value="'+ form_id +'"])',
														function(event) {
															var formElement = event.target;
															var formArgs = {
																form: {
																	selector: formElement,
																	map: form.form.map
																}
															};

															_this.submitForm( form, formArgs );
															_iub.cons.sendData();
															_iub.cons.sendFromLocalStorage();
														}
													);

													break;
											}

											// console.log( source );.
										}
									);
								}
							}
						);
					}
				};

				// submit form.
				this.submitForm = function (form, formArgs) {
					// push consent vars.
					if (typeof form.consent !== 'undefined' && form.consent.legal_notices.length > 0) {
						formArgs.consent               = {};
						formArgs.consent.legal_notices = form.consent.legal_notices;
					}

					console.log( formArgs );

					// build form consent data.
					_iub.cons_instructions.push( ['load', formArgs] );
				};

			};

			// initialize.
			iubendaConsentSolution.init();

		}
	);

	// Integrate with mailchimp.
	// Remove the hidden input of mailchimp to avoid the duplicate while saving the cons.
	if ($( '#_mc4wp_subscribe_woocommerce' ).length) {
		$( window ).load(
			function () {
				var mc_subscribe_checkbox = $( '#_mc4wp_subscribe_woocommerce' )
				mc_subscribe_checkbox.on(
					'change',
					function (event) {
						if (event.currentTarget.checked) {
							$( 'input:hidden[name=_mc4wp_subscribe_woocommerce]' ).remove();
							return;
						}
						mc_subscribe_checkbox.before( '<input type="hidden" name="_mc4wp_subscribe_woocommerce" value="0">' );
					}
				);
			}
		);
	}

})( jQuery );
