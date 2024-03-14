function getCalcId() {
	const urlParams = new URLSearchParams(window.location.search);
	const calcId = urlParams.get('id_form');
	return calcId;
}
function sccGetOffset(el) {
	const rect = el.getBoundingClientRect();
	return {
		left: rect.left + window.scrollX,
		top: rect.top + window.scrollY
	};
}
function isInsideEditingPage() {
	const urlParams = new URLSearchParams(window.location.search);
	const pageName = urlParams.get('page');
	if (pageName == 'scc_edit_items') {
		return true;
	} else {
		return false;
	}
}

//Loading Symbol during adding an element
function showLoadingChanges() {
	let timerInterval
	Swal.fire({
		showConfirmButton: false,
		timer: 7500,
		backdrop: true,
		customClass: {
			loader: 'custom-loader scc-smiling-loader',
			popup: 'scc-transparent',
		},
		loaderHtml: `<svg role="img" aria-label="Mouth and eyes come from 9:00 and rotate clockwise into position, right eye blinks, then all parts rotate and merge into 3:00" class="smiley" viewBox="0 0 128 128" width="128px" height="128px">
	<defs>
		<clipPath id="smiley-eyes">
			<circle class="smiley__eye1" cx="64" cy="64" r="8" transform="rotate(-40,64,64) translate(0,-56)" />
			<circle class="smiley__eye2" cx="64" cy="64" r="8" transform="rotate(40,64,64) translate(0,-56)" />
		</clipPath>
		<linearGradient id="smiley-grad" x1="0" y1="0" x2="0" y2="1">
			<stop offset="0%" stop-color="#000" />
			<stop offset="100%" stop-color="#fff" />
		</linearGradient>
		<mask id="smiley-mask">
			<rect x="0" y="0" width="128" height="128" fill="url(#smiley-grad)" />
		</mask>
	</defs>
	<g stroke-linecap="round" stroke-width="12" stroke-dasharray="175.93 351.86">
		<g>
			<rect fill="hsl(193,90%,50%)" width="128" height="64" clip-path="url(#smiley-eyes)" />
			<g fill="none" stroke="hsl(193,90%,50%)">
				<circle class="smiley__mouth1" cx="64" cy="64" r="56" transform="rotate(180,64,64)" />
				<circle class="smiley__mouth2" cx="64" cy="64" r="56" transform="rotate(0,64,64)" />
			</g>
		</g>
		<g mask="url(#smiley-mask)">
			<rect fill="hsl(223,90%,50%)" width="128" height="64" clip-path="url(#smiley-eyes)" />
			<g fill="none" stroke="hsl(223,90%,50%)">
				<circle class="smiley__mouth1" cx="64" cy="64" r="56" transform="rotate(180,64,64)" />
				<circle class="smiley__mouth2" cx="64" cy="64" r="56" transform="rotate(0,64,64)" />
			</g>
		</g>
	</g>
</svg>`,
		didOpen: (modal) => {
			Swal.showLoading()
		},
		willClose: () => {
			clearInterval(timerInterval)
		}
	})
}

const navigateToSettingsAccordion = (evt, $this) => {
	const scrollTarget = document.querySelector('#calc-settings-accordion');
	window.scrollTo(0, sccGetOffset(scrollTarget).top - 80);
	if ( ! scrollTarget.parentElement.classList.contains('active') ) {
	  scrollTarget.click();
	}
  }

const sccBackendUtils = {
	simulateClick: (element) => {
	  var evt = new MouseEvent("click", {
		bubbles: true,
		cancelable: true,
		view: window,
	  });
	  element.dispatchEvent(evt);
	},
	disableSaveBtnAjax: (status, element = null) => { //Handles the state of the save button when elements of the form are saved with ajax
		const elm = JSON.stringify(element);
		let topSaveBtn = document.querySelector(".scc-top-save-btn");
		let bottomSaveBtn = document.querySelector(".scc-bottom-save-btn");
		let elementBox = '';
		if(element){
		elementBox = element.closest('.elements_added');
		}
		// disable save button while the ajax is being processed
		if(status == true){	
		if(element){
			let loaderSelector = elementBox.querySelector('.scc-saving-element-msg');
			loaderSelector.innerHTML = '<i class="scc-btn-spinner scc-save-btn-spinner"></i> Saving...'
			loaderSelector.classList.remove('scc-visibility-hidden');
		}
		
		topSaveBtn.setAttribute("disabled", "");
		topSaveBtn.style.paddingLeft = "20px";
		topSaveBtn.style.paddingRight = "20px";
		bottomSaveBtn.setAttribute("disabled", "");
		bottomSaveBtn.style.paddingLeft = "20px";
		bottomSaveBtn.style.paddingRight = "20px";
		document.querySelectorAll(".scc-save-btn-cont").forEach((el) => {
			el.setAttribute("data-bs-original-title", "<h5>Saving changes</h5>");
			el.setAttribute("data-setting-tooltip-type","disabled-save-button-tt");
		});
		document.querySelectorAll(".scc-save-btn-spinner").forEach((el) => {
			el.classList.remove("scc-d-none");
		});
		}
		// enable save button while the ajax is being processed
		if(status == false){
		if(element){
			let loaderSelector = elementBox.querySelector('.scc-saving-element-msg');
		loaderSelector.innerHTML = '<span style="color:#314af3;">Saved</span>';

			setTimeout(() => {
			loaderSelector.classList.add('scc-visibility-hidden')
			}, 500);
		}else{
			document.querySelectorAll('.scc-saving-element-msg').forEach((el) => {
			el.innerHTML = '<span style="color:#314af3;">Saved</span>';
			setTimeout(() => {
				el.classList.add('scc-visibility-hidden')
			}, 500);
			});
		}
			
		elementBox.removeChild
		topSaveBtn.removeAttribute("disabled");
		topSaveBtn.style.paddingLeft = "35px";
		topSaveBtn.style.paddingRight = "35px";
		bottomSaveBtn.removeAttribute("disabled");
		bottomSaveBtn.style.paddingLeft = "35px";
		bottomSaveBtn.style.paddingRight = "35px";
		document.querySelectorAll(".scc-save-btn-cont").forEach((el) => {
			el.setAttribute("data-bs-original-title", "");
			el.setAttribute("data-setting-tooltip-type","");
		});
		document.querySelectorAll(".scc-save-btn-spinner").forEach((el) => {
			el.classList.add("scc-d-none");
		});
		}
	},
	handleSliderSetupBox: elementSetupBox => {
	  let titleInput = elementSetupBox.querySelector('[data-element-title]');
	  let pricingStructureChoice = elementSetupBox.querySelector('[data-pricing-structure]');
	  let woocommerceProdIdChoice = elementSetupBox.querySelector('[data-woocommerce-prod]');
	  let sliderRangeSetups = elementSetupBox.querySelectorAll('[data-slider-range-setup]');
	  sliderRangeSetups.forEach(rangeSet => {
		let rangeDataInputs = rangeSet.querySelectorAll('.col input');
		rangeDataInputs.forEach(inputField => {
		  if (!inputField.hasAttribute('data-attached-eventlistener')) {
			inputField.addEventListener('change', (evt) => {
			  let src = evt.currentTarget;
			  sccBackendUtils.disableSaveBtnAjax(true, elementSetupBox);
			  sccBackendUtils.updateSliderRangeValues(src, elementSetupBox.querySelectorAll('[data-slider-range-setup]'));
			})
			inputField.setAttribute('data-attached-eventlistener', 1);
		  }
		})
	  })
	},
	setupSurveyModal: (modal) => {
		const firstStep = modal.querySelector('.step1-wrapper');
		const secondStep = modal.querySelector('.step2-wrapper');
		const thirdStep = modal.querySelector('.step3-wrapper');
		const closeBtn = modal.querySelector('[data-dismiss="modal"]');
		const emailInput = modal.querySelector('#feedback-email-input');
		const checkboxOptIn = modal.querySelector('#feedback-opt-in');
		const searchParams = new URLSearchParams(window.location.search);
		const launchTour = ( searchParams.get( 'page' ) === 'scc_edit_items' && searchParams.has('new') ) ? true : false ;

		modal.classList.remove('d-none', 'fade');
		modal.style.display = 'block';

		const responseData = {
			rating: 0,
			text: '',
			email: emailInput.value,
			optedForEmail: checkboxOptIn.checked,
		  }

		const ratingChosenText = modal.querySelector('.rating-chosen');

		const commentInput = modal.querySelector('#comments-text-input');
		const commentSubmitBtn = modal.querySelector('#comments-submit-btn');

		const ratingsPicker = modal.querySelector('.ratings-picker');
		ratingsPicker.querySelectorAll('li').forEach((li, index) => {
			li.addEventListener('click', evt => {
			firstStep.classList.add('d-none');
			secondStep.classList.remove('d-none');
			ratingChosenText.textContent = index + 1;
			responseData.rating = index + 1;
			});
		});

		commentInput.addEventListener('input', evt => {
			responseData.text = evt.target.value;
		});

		emailInput.addEventListener('input', evt => {
			responseData.email = evt.target.value;
		});
	  
		checkboxOptIn.addEventListener('change', evt => {
			responseData.optedForEmail = evt.target.checked;
			if (!evt.target.checked) {
				delete responseData.email;
			}
		});
		commentSubmitBtn.addEventListener('click', evt => {
			jQuery.ajax({
			url:
				ajaxurl +
				"?action=scc_feedback_manage" +
				"&_wpnonce=" +
				pageEditCalculator.nonce,
			type: "POST",
			contentType: "application/json; charset=utf-8",
			dataType: "json",
			data: JSON.stringify(responseData),
			beforeSend: function () {
				commentSubmitBtn.disabled = true;
				commentSubmitBtn.textContent = 'Submitting...';
			},
			complete: function (data) {
				secondStep.classList.add('d-none');
				thirdStep.classList.remove('d-none');
				closeBtn.classList.add('d-none');
				setTimeout(() => {
					document.querySelector('#user-scc-sv').classList.remove('d-block');
					document.querySelector('#user-scc-sv').classList.add('fade', 'd-none');
					if( launchTour ){
						sccBackendUtils.knowingEditingPageGuidedTour( 'scc-introjs-new-editing-page' );
					}
				}, 3000);
			}
			})
		});
	},
	getSliderRangeData: (originInputField, rangeDataRows) => {
	  return [...rangeDataRows].map(rangeSet => {
		let rangeData = rangeSet.querySelectorAll('.col input');
		let rangeId = rangeSet.querySelector('[data-range-id]').getAttribute('data-range-id');
		return {
		  rangeId,
		  from: {value: rangeData[0].value, inputControl: rangeData[0], isOriginInputField: originInputField == rangeData[0]},
		  to: {value: rangeData[1].value, inputControl: rangeData[1], isOriginInputField: originInputField == rangeData[1]},
		  ppu: {value: rangeData[2].value, inputControl: rangeData[2], isOriginInputField: originInputField == rangeData[2]}
		};
	  });
	},
	updateSliderRangeValues: (originInputField, rangeDataRows) => {
	  let ranges = sccBackendUtils.getSliderRangeData(originInputField, rangeDataRows)
	  let elementId = originInputField.closest('.elements_added')?.querySelector('.input_id_element')?.value
	  for (let index = 0; index < ranges.length; index++) {
		const rangeItem = ranges[index];
		const nextRangeItem = ranges[index + 1];
		if ((Number(rangeItem.from.value) > Number(rangeItem.to.value))) {
			rangeItem.to.value = Number(rangeItem.from.value);
			rangeItem.to.inputControl.value = Number(rangeItem.from.value);
		}
		if (typeof(nextRangeItem) !== 'undefined' && (Number(nextRangeItem.from.value) !== ( Number(rangeItem.to.value) + 1 ) )) {
		  nextRangeItem.from.value = Number(rangeItem.to.value) + 1;
		  nextRangeItem.from.inputControl.value = Number(rangeItem.to.value) + 1;
		}
	  }
	  updateSliderRangesWithDebounce( ranges, elementId );
	},
	changeNumberInputCommaFormat: (element) => {
		const elementSettingsWrapper = jQuery(element).closest(".elements_added");
			var element_id = elementSettingsWrapper.find(".input_id_element").val();
		const status = element.checked;
		jQuery.ajax({
		  url: ajaxurl,
		  cache: false,
		  data: {
			action: 'sccUpElement',
			id_element: element_id,
			value5: status ? 2 : 1,
			nonce: pageEditCalculator.nonce
		  },
		  success: function(data) {
			sccBackendUtils.disableSaveBtnAjax(false, element);
			var datajson = JSON.parse(data)
			if (datajson.passed == true) {
			  showSweet(true, "The value has changed")
			} else {
			  showSweet(false, "There was an error, please try again")
			}
		  },
		  error: function(){
			sccBackendUtils.disableSaveBtnAjax(false, element);
		  }
		});
	},
	updateSliderRanges: (rangeCollection, elementId) => {
	  // stripping off the `inputControl` property
	  let cleanRangeCollection = rangeCollection.map(r => {
		delete r.from.inputControl;
		delete r.to.inputControl;
		delete r.ppu.inputControl;
		return r;
	  })
	  console.log('debounce')
	  var data = JSON.stringify({cleanRangeCollection, elementId});
	  // sending a JSON data
	  jQuery
		.ajax({
		  url:
			ajaxurl +
			"?action=scc_update_slider_ranges" +
			"&_wpnonce=" +
			pageEditCalculator.nonce,
		  type: "POST",
		  contentType: "application/json; charset=utf-8",
		  dataType: "json",
		  data,
		  beforeSend: function () {
			//showLoadingChanges();
		  }
		})
		.complete(function() {
		  sccBackendUtils.disableSaveBtnAjax(false);
		  showSweet(true, "The changes have been saved.");
		})
		.fail(function (xhr, textStatus, e) {
		  sccBackendUtils.disableSaveBtnAjax(false);
		  showSweet(false, "There was an error.");
		  console.log(xhr.responseText);
		});
	},
	triggerChangeEvent: (element) => {
	  var event = new Event("change");
	  element.dispatchEvent(event);
	},
	handleTooltipAjaxAddedElements( element ) {
		// applying tooltips to ajax added elements
		if( element ) {
			element.querySelectorAll( '[data-element-tooltip-type]' ).forEach( function( node ) {
			  applyElementTooltip( node );
			});
			//sccRefreshAlert();
			element.querySelectorAll( '.with-tooltip:not([data-element-tooltip-type])' ).forEach( function( element ) {
			  new bootstrap.Tooltip( element, {
				delay: {
				  show: 500,
				  hide: 300
				},
				trigger: 'hover focus',
				html: true,
				placement: 'right'
			  } );
			} );
		}
	},
	handleCalculatorTourLinks: () => {
		let tourLinks = document.querySelectorAll('.scc-calculator-tour-link');
		tourLinks.forEach(link => {
		  link.addEventListener('click', evt => {
			evt.preventDefault();
			let tourType = link.getAttribute('data-tour-type');
			if (tourType === 'editing-page') {
			  sccBackendUtils.knowingEditingPageGuidedTour();
			}
			if (tourType === 'calculator-settings') {
			  sccBackendUtils.calculatorSettingsGuidedTour();
			}
			if (tourType === 'font-settings') {
			  sccBackendUtils.fontSettingsGuidedTour();
			}
			if (tourType === 'wordings') {
			  sccBackendUtils.wordingsGuidedTour();
			}
			if (tourType === 'email-quote-settings') {
			  sccBackendUtils.emailQuoteSettingsGuidedTour();
			}
			if (tourType === 'payment-options') {
			  sccBackendUtils.paymentOptionsGuidedTour();
			}
		  });
		});
	  },
	  // This function is used to configure the introjs tour
	  getTourConfig: ( steps, hints, dontShowAgainCookieName = null ) => {
			if ( ! dontShowAgainCookieName ) { 
			  dontShowAgainCookieName = 'scc-introjs-cookie';
			  dontShowAgain = false;
			} else{
			  dontShowAgainCookieName = dontShowAgainCookieName;
			  dontShowAgain = true;
			} 
			return {
			  showProgress: true,
			  showProgress: true,
			  showBullets: false,
			  showStepNumbers: true,
			  disableInteraction: true,
			  dontShowAgain: dontShowAgain,
			  dontShowAgainCookie: dontShowAgainCookieName,
			  steps: steps,
			  hints: hints,
			}
	  },
	  calculatorSettingsGuidedTour: ( dontShowAgainCookieName ) => {
	
		const calculatorSettingsMenu  = document.querySelector('.scc-menu-dropdown .scc-dropbtn');
		const calculatorSettings      = document.querySelector('.scc-menu-dropdown .scc-menu-dropdown-content .scc-calculator-settings-dropdown');
		const saveButtonModal         = document.querySelector('#settingsModal1 .settings-modal-save-btn');
	
		const frontendOptionsSection    = document.querySelector('.scc-frontend-options-section');
		const formFieldElementStyles    = document.querySelector('.scc-form-field-element-styles');
		const woocommerceSettings       = document.querySelector('.scc-woocommerce-settings');
		const ctaButtonSettings         = document.querySelector('.scc-cta-button-calc-settings');
		const totalPriceSettings        = document.querySelector('.scc-total-price-settings');
		const detailedListPdfSettings   = document.querySelector('.scc-detailed-list-pdf-settings');
		const searchBoxSettings         = document.querySelector('.scc-search-box-settings');
	
		const currencyTaxSettingsSection = document.querySelector('.scc-currency-tax-settings-section');
		const pdfSettingsSection         = document.querySelector('.scc-pdf-settings-section');
		const emailSettingsSection       = document.querySelector('.scc-email-setting-section');
		const textMessageSettingsSection = document.querySelector('.scc-text-message-settings-section');
		const webhookEventsSection       = document.querySelector('.scc-webhook-events-section');
		const calculatorSettingsSearch   = document.querySelector('.scc-calculator-settings-search');
		const calculatorSettingsFilter   = document.querySelector('.scc-calculator-settings-filters');
	
		const steps = [
		  // Calculator Settings
		  {
			element: calculatorSettingsMenu,
			title: 'Settings Menu',
			intro: `Customize calculator options: fonts, colors, styles, and email settings.`,
			position: 'right',
		  },
		  {
			element: calculatorSettings,
			title: 'Main Settings',
			intro: `Access advanced configuration.`,
			position: 'right',
		  },
		  {
			title: 'Features',
			intro: `Adjust operational features: styles, properties, PDF, TAX, webhook, and email settings.`,
			position: 'right',
		  },
		  {
			element: frontendOptionsSection,
			title: 'Frontend',
			intro: `Adjust frontend visual and functional options.`,
			position: 'right',
		  },
		  {
			element: formFieldElementStyles,
			title: 'Styles',
			intro: `Configure container options, width, and overall style.`,
			position: 'right',
		  },
		  {
			element: woocommerceSettings,
			title: 'WooCommerce',
			intro: `Configure WooCommerce interactions.`,
			position: 'right',
		  },
		  {
			element: ctaButtonSettings,
			title: 'CTA Buttons',
			intro: `Customize user action buttons.`,
			position: 'right',
		  },
		  {
			element: totalPriceSettings,
			title: 'Total Price',
			intro: `Control the display of the total price.`,
			position: 'right',
		  },
		  {
			element: detailedListPdfSettings,
			title: 'Detailed List & PDF',
			intro: `Choose elements for the PDF and detailed list.`,
			position: 'right',
		  },
		  {
			element: searchBoxSettings,
			title: 'Search Box',
			intro: `Modify the item search bar display.`,
			position: 'right',
		  }, 
		  {
			element: currencyTaxSettingsSection,
			title: 'Currency & Tax',
			intro: `Set currency type, conversion, format, and display options.`,
			position: 'right',
		  },
		  {
			element: pdfSettingsSection,
			title: 'PDF Preferences',
			intro: `Choose font and date format for PDF output.`,
			position: 'right',
		  },
		  {
			element: emailSettingsSection,
			title: 'Email Configurations',
			intro: `Adjust email template, recipient list, and display preferences.`,
			position: 'right',
		  },
/* 		  {
			element: textMessageSettingsSection,
			title: 'SMS Configurations',
			intro: `Set up SMS quote messaging.`,
			position: 'right',
		  }, */
		  {
			element: webhookEventsSection,
			title: 'Webhooks',
			intro: `Connect with apps like Mailchimp, Gmail, GDocs, Zapier, and more.`,
			position: 'right',
		  },
		  {
			element: calculatorSettingsSearch,
			title: 'Search',
			intro: `Find options with the search function.`,
			position: 'right',
		  },
/* 		  {
			element: calculatorSettingsFilter,
			title: 'Filter Options',
			intro: `Use filters for easier option navigation.`,
			position: 'right',
		  }, */
		  {
			element: saveButtonModal,
			title: 'Save',
			intro: `Commit your changes.`,
			position: 'right',
		  },
	
		]
		
		const tourConfig = sccBackendUtils.getTourConfig( steps, null, dontShowAgainCookieName );
		const tour = introJs().setOptions( tourConfig )
		.onbeforechange( function( targetElement ) {
		  const isCalculatorSettingsMenu  = targetElement === calculatorSettingsMenu;
		  const isCalculatorSettings      = targetElement === calculatorSettings;
		  const isFloatingSteps           = targetElement.classList.contains('introjsFloatingElement');
		  let settingsMenuDropdown        = document.querySelector( '.scc-menu-dropdown-content' );
		  let showCalculatorSettingsModal = document.querySelector( '#calculatorSettingsModal' )?.classList.contains('show');
	
		  // Display calculator settings menu before showing on tour
		  if( isCalculatorSettingsMenu ){  
			settingsMenuDropdown?.classList.remove('scc-hidden');
			settingsMenuDropdown?.classList.add('scc-d-block');
		  }
	
		  if( isFloatingSteps ) {
			if ( ! showCalculatorSettingsModal ) {
			  calculatorSettings?.click();
			}
		  }
		  if( isCalculatorSettings ) {
			if ( showCalculatorSettingsModal ) {
			  console.log('clicking here?')
			  calculatorSettings?.click();
			  settingsMenuDropdown?.classList.remove('scc-hidden');
			  settingsMenuDropdown?.classList.add('scc-d-block');
			}
		  }
	
		} )
		// start the tour
	
		tour.start();
	  },
	  paymentOptionsGuidedTour: ( dontShowAgainCookieName ) => {
		const paymentSettings          = document.querySelector('.scc-payment-settings');
		const paymentMethods           = paymentSettings.querySelector('.scc-payment-methods');
		const emailQuoteBeforeCheckout = paymentSettings.querySelector('.scc-email-quote-before-checkout');
		const combineAllLineItems      = paymentSettings.querySelector('.payment-options-wrapper .scc-combine-all-line-items');
		const multipleCartItems        = paymentSettings.querySelector('.payment-options-wrapper .scc-multiple-cart-items');
	
		const steps = [
		  // Calculator Settings
		  {
			element: paymentSettings,
			title: 'Payment Settings',
			intro: `Choose your preferred payment methods.`,
			position: 'top',
		  },
		  {
			element: paymentMethods,
			title: 'Payment Methods',
			intro: `Select from Paypal, Stripe, or Woocommerce.`,
			position: 'right',
		  },
		  {
			element: emailQuoteBeforeCheckout,
			title: 'Pre-Checkout Email Quote',
			intro: `Log quotes prior to customer payment.`,
			position: 'right',
		  },
		  /* {
			element: combineAllLineItems,
			title: 'Combine Line Items',
			intro: `Merge calculator selections into a single WooCommerce item. Useful for volume discounts or when using math elements.`,
			position: 'right',
		  },
		  {
			element: multipleCartItems,
			title: 'Multiple Item Instances',
			intro: `Allow the same WooCommerce item to be added to the cart multiple times.`,
			position: 'right',
		  }, */
	
		]
		
		const tourConfig = sccBackendUtils.getTourConfig( steps, null, dontShowAgainCookieName );
		const tour = introJs().setOptions( tourConfig )
		.onbeforechange( function( targetElement ) {
		  const isPaymentSettings      = targetElement === paymentSettings;
		  // Display calculator settings menu before showing on tour
		  if ( isPaymentSettings ){  
			paymentSettings.querySelector('.card-action-btns').classList.remove('d-none');
		  }
	
		} )
		// start the tour
		.oncomplete( function() {
		  paymentSettings.querySelector('.card-action-btns').classList.add('d-none');
		} )
		.onexit( function() {
		  paymentSettings.querySelector('.card-action-btns').classList.add('d-none');
		} );
		tour.start();
	  },
	  emailQuoteSettingsGuidedTour: ( dontShowAgainCookieName ) => {
		const quoteFormSettings = document.querySelector('.scc-quote-form-settings');
		const buttonContainer   = quoteFormSettings.querySelector('.btns-container');
		const addNewField       = quoteFormSettings.querySelector('.btn-plus');
		const requireAcceptance = quoteFormSettings.querySelector('.scc-form-checkbox');
		const steps = [
		  // Calculator Settings
		  {
			element: quoteFormSettings,
			title: 'Email Quote Settings',
			intro: `Tailor your email quote form here. Add fields and set acceptance requirements.`,
			position: 'top',
		  },
		  {
			element: buttonContainer,
			title: 'Form Fields',
			intro: `Adjust individual form fields for better user experience.`,
			position: 'right',
		  },
		  {
			element: addNewField,
			title: 'Add Field',
			intro: `Introduce a new field to your form with a click.`,
			position: 'right',
		  },
		  {
			element: requireAcceptance,
			title: 'Mandatory Acceptance',
			intro: `Enforce user agreement to GDPR, Terms, or other conditions before form submission.`,
			position: 'right',
		  },
	
		  // Calculator Builder panel and Calculator settings
		  
		]
		
		const tourConfig = sccBackendUtils.getTourConfig( steps, null, dontShowAgainCookieName );
		const tour = introJs().setOptions( tourConfig )
		.onbeforechange( function( targetElement ) {
		  const isQuoteFormSettings      = targetElement === quoteFormSettings;
		  // Display calculator settings menu before showing on tour
		  if ( isQuoteFormSettings ){  
			quoteFormSettings.querySelector('.card-action-btns').classList.remove('d-none');
		  }
	
		} )
		// start the tour
		.oncomplete( function() {
		  quoteFormSettings.querySelector('.card-action-btns').classList.add('d-none');
		} )
		.onexit( function() {
		  quoteFormSettings.querySelector('.card-action-btns').classList.add('d-none');
		} );
		tour.start();
	  },
	  wordingsGuidedTour: ( dontShowAgainCookieName ) => {
	
		const calculatorSettingsMenu = document.querySelector('.scc-menu-dropdown .scc-dropbtn');
		const wordingsSettings       = document.querySelector('.scc-menu-dropdown .scc-menu-dropdown-content .scc-wordings-settings-dropdown');
		const frontendWord           = document.querySelector('.translation-row div:nth-child(1)');
		const translationWord        = document.querySelector('.translation-row div:nth-child(2)');
		const deleteButton           = document.querySelector('.translation-row div:nth-child(3)');
		const addNewTranslateButton  = document.querySelector('.scc-add-new-translate-button');
		const saveButtonModal        = document.querySelector('#settingsModal2 .settings-modal-save-btn');
	
		const steps = [
		  // Calculator Wordings and Translations
		  {
			element: calculatorSettingsMenu,
			title: 'Calculator Settings Menu',
			intro: `Customize general calculator options, including wordings, fonts, colors, styles and emails.`,
			position: 'right',
		  },
		  {
			element: wordingsSettings,
			title: 'Wordings Settings',
			intro: `Access and adjust the words or translations used in your calculator.`,
			position: 'right',
		  },
		  {
			title: 'Wording Settings',
			intro: `Here you can customize the words/translations of your calculator.`,
			position: 'right',
		  },
		  {
			element: frontendWord,
			title: 'Frontend Display',
			intro: `View how the word appears on the frontend.`,
			position: 'right',
		  },
		  {
			element: translationWord,
			title: 'Edit Translation',
			intro: `Modify how the word appears on the frontend. This replaces the original text.`,
			position: 'right',
		  },
		  {
			element: deleteButton,
			title: 'Delete Translation',
			intro: `Remove a translation completely. If you need this translation later, you'll need to add it again.`,
			position: 'right',
		  },
		  {
			element: addNewTranslateButton,
			title: 'Add Translation',
			intro: `Introduce a new translation for your calculator.`,
			position: 'right',
		  },
		  {
			element: saveButtonModal,
			title: 'Commit Changes',
			intro: `Ensure to save your changes for them to be applied to your calculator.`,
			position: 'right',
		  },
		]
		
		const tourConfig = sccBackendUtils.getTourConfig( steps, null, dontShowAgainCookieName );
		const tour = introJs().setOptions( tourConfig )
		.onbeforechange( function( targetElement ) {
		  const isCalculatorSettingsMenu = targetElement === calculatorSettingsMenu;
		  const isWordingsSettings       = targetElement === wordingsSettings;
		  const isFloatingSteps          = targetElement.classList.contains('introjsFloatingElement');
		  const isDeleteButton           = targetElement === deleteButton;
		  let settingsMenuDropdown       = document.querySelector( '.scc-menu-dropdown-content' );
		  let showWordingsSettingsModal  = document.querySelector( '#wordingsModal' )?.classList.contains('show');
	
		  // Display calculator settings menu before showing on tour
		  if ( isCalculatorSettingsMenu ){  
			settingsMenuDropdown?.classList.remove('scc-hidden');
			settingsMenuDropdown?.classList.add('scc-d-block');
		  }
	
		  if ( isFloatingSteps ) {
			wordingsSettings?.click();
		  }
		  if ( isWordingsSettings ) {
			if ( showWordingsSettingsModal ) {
			  wordingsSettings?.click();
			  settingsMenuDropdown?.classList.remove('scc-hidden');
			  settingsMenuDropdown?.classList.add('scc-d-block');
			}
		  }
		  if ( isDeleteButton ) {
			targetElement.querySelector('a').classList.add('scc-d-inline-block');
		  }else{
			deleteButton.querySelector('a').classList.remove('scc-d-inline-block');
		  }
	
		} )
		// start the tour
	
		tour.start();
	  },
	  fontSettingsGuidedTour: ( dontShowAgainCookieName ) => {
	
		const calculatorSettingsMenu = document.querySelector('.scc-menu-dropdown .scc-dropbtn');
		const fontSettings           = document.querySelector('.scc-menu-dropdown .scc-menu-dropdown-content .scc-font-settings-dropdown');
		const titleFontSettings      = document.querySelector('.scc-title-total-font-settings');
		const elementFontSettings    = document.querySelector('.scc-element-font-settings');
		const objectSettings         = document.querySelector('.scc-object-settings');
		const ctaButtonSettings      = document.querySelector('.scc-cta-button-settings');
		const saveButtonModal        = document.querySelector('#settingsModal .settings-modal-save-btn');
	
		const steps = [
		  // Calculator Settings
		  {
			element: calculatorSettingsMenu,
			title: 'Settings Menu',
			intro: `Access general options, fonts, colors, styles, and email configurations here.`,
			position: 'right',
		  },
		  {
			element: fontSettings,
			title: 'Font Settings',
			intro: `Dive into typography settings.`,
			position: 'right',
		  },
		  {
			title: 'Detailed Font Adjustments',
			intro: `Tweak fonts, colors, sizes, and design elements of the form and buttons.`,
			position: 'right',
		  },
		  {
			element: titleFontSettings,
			title: 'Title Fonts',
			intro: `Personalize the typography of titles and total sections.`,
			position: 'right',
		  },
		  {
			element: elementFontSettings,
			title: 'Element Typography',
			intro: `Fine-tune the typography for various elements like Dropdowns, Sliders, Checkboxes, and more.`,
			position: 'right',
		  },
		  {
			element: objectSettings,
			title: 'Object Color Customizations',
			intro: `Adjust colors of elements, influencing component backgrounds, excluding text.`,
			position: 'right',
		  },
/* 		  {
			element: ctaButtonSettings,
			title: 'CTA Button Customizations',
			intro: `Stylize the Call-to-action buttons including Email Quote, Coupon Code, Woocommerce, and Payment options.`,
			position: 'right',
		  }, */
		  {
			element: saveButtonModal,
			title: 'Commit Changes',
			intro: `Ensure to save modifications for them to reflect on your calculator.`,
			position: 'right',
		  },
		]
		
		const tourConfig = sccBackendUtils.getTourConfig( steps, null, dontShowAgainCookieName );
		const tour = introJs().setOptions( tourConfig )
		.onbeforechange( function( targetElement ) {
		  const isCalculatorSettingsMenu = targetElement === calculatorSettingsMenu;
		  const isFontSettings           = targetElement === fontSettings;
		  const isFloatingSteps          = targetElement.classList.contains('introjsFloatingElement');
		  let settingsMenuDropdown = document.querySelector( '.scc-menu-dropdown-content' );
		  let showFontSettingsModal = document.querySelector( '#fontSettingsModal' )?.classList.contains('show');
	
		  // Display calculator settings menu before showing on tour
		  if( isCalculatorSettingsMenu ){  
			settingsMenuDropdown?.classList.remove('scc-hidden');
			settingsMenuDropdown?.classList.add('scc-d-block');
		  }
	
		  if( isFloatingSteps ) {
			fontSettings?.click();
		  }
		  if( isFontSettings ) {
			if ( showFontSettingsModal ) {
			  console.log('clicking on font settings')
			  fontSettings?.click();
			  settingsMenuDropdown?.classList.remove('scc-hidden');
			  settingsMenuDropdown?.classList.add('scc-d-block');
			}
		  }
	
		} )
		// start the tour
	
		tour.start();
	  },
	  knowingEditingPageGuidedTour: ( dontShowAgainCookieName ) => {
		const calculatorName               = document.querySelector('.scc-edit-calculator-name');
		const calculatorBuilder            = document.querySelector('.scc-calculator-builder-pane');
		const calculatorSettingsMenu       = document.querySelector('.scc-menu-dropdown .scc-dropbtn');
		const fontSettings                 = document.querySelector('.scc-menu-dropdown .scc-menu-dropdown-content .scc-font-settings-dropdown');
		const calculatorSettings           = document.querySelector('.scc-menu-dropdown .scc-menu-dropdown-content .scc-calculator-settings-dropdown');
		const wordingsSettings             = document.querySelector('.scc-menu-dropdown .scc-menu-dropdown-content .scc-wordings-settings-dropdown');
		const couponCodes                  = document.querySelector('.scc-menu-dropdown .scc-menu-dropdown-content .scc-coupon-codes-dropdown');
		const globalSettings               = document.querySelector('.scc-menu-dropdown .scc-menu-dropdown-content .scc-global-settings-dropdown');
		const firstSection                 = document.querySelector('#allinputstoadd .addedFieldsStyle:first-child');
		const firstSectionTitle            = document.querySelector('#allinputstoadd .addedFieldsStyle:first-child .title_section_no_edit_container');
		const firstSectionDescription      = document.querySelector('#allinputstoadd .addedFieldsStyle:first-child .description_section_no_edit_container');
		const firstSubsection              = document.querySelector('#allinputstoadd .addedFieldsStyle:first-child .boardOption');
		const firstElement                 = document.querySelector('#allinputstoadd .addedFieldsStyle:first-child .elements_added');
		const firstElementActions          = document.querySelector('#allinputstoadd .addedFieldsStyle:first-child .element-action-icons');
		const firstElementSettings         = document.querySelector('#allinputstoadd .addedFieldsStyle:first-child .scc-element-content');
		const firstElementAdvancedSettings = document.querySelector('#allinputstoadd .addedFieldsStyle:first-child .styled-accordion:has(.scc_accordion_advance)');
		const firstElementConditionalLogic = document.querySelector('#allinputstoadd .addedFieldsStyle:first-child .styled-accordion:has(.scc_accordion_conditional)');
		const addNewElementButton          = document.querySelector('#allinputstoadd .addedFieldsStyle:first-child .add-element-btn');
		const addNewSubsectionButton       = document.querySelector('#allinputstoadd .add-subsection-btn a');
		const addNewSectionButton          = document.querySelector('.scc_new_sec');
		const calculatorSettingsBottom     = document.querySelector('.scc-calc-settings-bottom');
		const emailQuoteSettingsBottom     = document.querySelector('.scc-quote-form-settings');
		const paymentOptions               = document.querySelector('.scc-payment-settings');
		const previewPane                  = document.querySelector('.scc-preview-pane');
		const embedButton                  = document.querySelector('.scc-embed-tips-wrapper');
		const embedShortcode               = document.querySelector('.scc-embed-tip-wrapper div');
	
	
		const steps = [
		  {
			title: 'Welcome!',
			intro: `Explore the dynamic world of the calculator editing. Ready for a deep dive?`
		  },
		  {
			element: calculatorName,
			title: 'Naming Your Calculator',
			intro: `Rename your calculator to best represent its purpose.`,
		  },
		  {
			element: calculatorBuilder,
			title: 'Craft Your Calculator',
			intro: `Adjust and organize calculator elements in this builder space.`,
			position: 'right',
		  },
		  {
			element: calculatorSettingsMenu,
			title: 'Calculator settings Menu',
			intro: `From here you can access to customize the most important settings in the calculator`,
			position: 'right',
		  },
		  {
			element: fontSettings,
			title: 'Font & Styling',
			intro: `Adjust fonts, their colors, and sizes. Also, personalize form and button colors.`,
			position: 'right',
		  },
		  {
			element: calculatorSettings,
			title: 'Enhance Capabilities',
			intro: `Toggle and customize features like Buttons, PDFs, Emails, Taxes, and more.`,
			position: 'right',
		  },
		  {
			element: wordingsSettings,
			title: 'Your Calculator’s Voice',
			intro: `Modify the frontend texts and even add multiple language translations.`,
			position: 'right',
		  },
		  {
			element: couponCodes,
			title: 'Offer discounts',
			intro: `Create and manage discount coupons for your clients.`,
			position: 'right',
		  },
		  {
			element: globalSettings,
			title: 'Global Settings',
			intro: `Manage global settings applied to all calculators.`,
			position: 'right',
		  },
			/**
		   * Calculator Section
		   * NOTE: If the order changes, please update the onbeforechange function
		   */
		  {
			element: firstSection,
			title: 'Editing a Section',
			intro: `Delve into your first section here.`,
			position: 'right',
		  },
		  {
			element: firstSectionTitle,
			title: 'Defining the Title',
			intro: `Give your section a fitting title.`,
			position: 'right',
		  },
		  {
			element: firstSectionDescription,
			title: 'Description Details',
			intro: `Describe what this section is all about.`,
			position: 'right',
		  },
		  {
			element: firstSubsection,
			title: 'Dive into Subsections',
			intro: `Subsections house the form elements. Remember, only one Slider element per subsection.`,
			position: 'right',
		  },
		  /**
		  * Element review
		  */
		  {
			element: firstElement,
			title: 'Crafting an Element',
			intro: `Customize this element's settings and values to your preference.`,
			position: 'right',
		  },
		  {
			element: firstElementActions,
			title: 'Element Manipulations',
			intro: `Duplicate, remove, or shift this element to another section.`,
			position: 'right',
		  },
		  {
			element: firstElementSettings,
			title: 'Tuning Element Properties',
			intro: `Modify its title, description, value, and add items for Dropdowns or checkboxes.`,
			position: 'right',
		  },
		  {
			element: firstElementAdvancedSettings,
			title: 'Advanced Configurations',
			intro: `Refine advanced options such as visibility, obligatory fields, and selection types.`,
			position: 'right',
		  },
		  {
			element: firstElementConditionalLogic,
			title: 'Smart Logic',
			intro: `Set conditions to reveal this element based on other criteria.`,
			position: 'right',
		  },
		  /**
		   * Options to Add Element, Subsection, and Section
		   */
		  {
			element: addNewElementButton,
			title: 'New Element',
			intro: `Click to introduce a new element within the subsection.`,
			position: 'right',
		  },
		  {
			element: addNewSubsectionButton,
			title: 'New Subsection',
			intro: `Add a subsection to the section. Note: A subsection can contain various elements.`,
			position: 'right',
		  },
		  {
			element: addNewSectionButton,
			title: 'New Section',
			intro: `Incorporate a new section. Each section can encompass multiple subsections.`,
			position: 'right',
		  },
		  /**
		   * Accessible Settings at the Bottom
		   */
		  {
			element: calculatorSettingsBottom,
			title: 'Quick Settings Access',
			intro: `Reach out to the calculator settings from here.`,
			position: 'top',
		  },
		  {
			element: emailQuoteSettingsBottom,
			title: 'Email & Form Settings',
			intro: `Adjust fields for email quotes and ensure GDPR compliance & T&C acceptance.`,
			position: 'top',
		  },
		  {
			element: paymentOptions,
			title: 'Payment Gateways',
			intro: `Enable payment methods. Options include Paypal, Stripe, and Woocommerce.`,
			position: 'top',
		  },
		  {
			element: previewPane,
			title: 'Live Preview',
			intro: `Monitor your edits in real-time here.`
		  },
		  {
			element: embedButton,
			title: 'Embed to page',
			intro: `Click on the button to display the shortcodes`,
			position: 'left',
		  },
		  {
			element: embedShortcode,
			title: 'Shortcode',
			intro: `Copy and paste this shortcode properly into a code, text, shortcode, or shortblock widget within your page builder. Do not use the visual text box.`,
			position: 'left',
		  },
		  
		]
	
		const tourConfig = sccBackendUtils.getTourConfig( steps, null, dontShowAgainCookieName );
		const tour = introJs().setOptions( tourConfig )
		.onbeforechange( function( targetElement ) {
		  const isCalculatorBuilderPanel = targetElement === calculatorBuilder;
		  const isSection = targetElement === firstSection;
		  const isSettingsMenuButton = targetElement === calculatorSettingsMenu;
		  const isWordingSettings = targetElement === wordingsSettings;
		  const isCouponCodes = targetElement === couponCodes;
		  const isGlobalSettings = targetElement === globalSettings;
		  const isElement = targetElement === firstElement;
		  const isEmbedButton = targetElement === embedButton;
	
		  let settingsMenuDropdown = document.querySelector( '.scc-menu-dropdown-content' );
		  let elementContent = targetElement.querySelectorAll( '.scc-element-content' );
	
	
		  // Hide calculator settings menu before showing on tour
		  if( isCalculatorBuilderPanel || isSection ){  
			settingsMenuDropdown?.classList.remove('scc-d-block');
			settingsMenuDropdown?.classList.add('scc-hidden');
		  }
		  // Display calculator settings menu before showing on tour
		  if( isSettingsMenuButton || isGlobalSettings){
			settingsMenuDropdown?.classList.remove('scc-hidden');
			settingsMenuDropdown?.classList.add('scc-d-block');
		  }
		  //Display Element 
		  if( isElement ){
			elementContent.forEach( content => { 
			  content.style.display = '';
			 } )
		  }
		  if( isEmbedButton ){
			document.querySelector('#df_scc_tabembed_').classList.remove('d-none');
		  }
	
		} )
		.onafterchange(function( targetElement ) {
		} )
		// start the tour
		tour.start();
	  },
  };

const updateSliderRangesWithDebounce = _.debounce(sccBackendUtils.updateSliderRanges, 3000);

/* Message for premium options tooltips (used in settingTooltips) */
const premiumMessage = '<span class="scc-premium-msg"><i class="material-icons scc-icon-tooltips pe-1">info_outline</i> You need to purchase a <b><a class="scc-text-orange px-1" href="https://stylishcostcalculator.com/" >premium license</a></b> to edit this feature.</span>'
/* Tool Tip for added Settings*/
const settingTooltips = {
	'quote-screen-tt': {
		msg: `<h5 class='text-start'>Quotes & Leads Dashboard</h4>
				  <p class='text-start mt-2'>It’s a straightforward system that lets you organize, manage, store and manage your quote entries and leads generated by your price estimation form (cost calculator).</p>
				  <div class="example-description text-start">
          
          <a href="https://designful.freshdesk.com/a/solutions/articles/48001216303" target="_blank"><div class="btn btn-primary btn-lg">Learn More</div></a>
		  <br> <br> 
		  ${premiumMessage}		  
		  </div>`,
		coverImage:
		'images/tooltip-images/quote-lead-dashboard-img.png',
	},
	'require-acceptance-tt': {
		msg: `<h5 class='text-start'>Require acceptance (GDPR/Terms & Conditions)</h4>
				  <p class='text-start mt-2'>Use this feature to add a mandatory checkbox with GDPR, Terms and Conditions etc. The user must accept it to continue submitting the form</p>
				  <div class="example-description text-start">
          <br>
          <a href="https://designful.freshdesk.com/support/solutions/articles/48001225789-acceptance-box-a-complete-guide" target="_blank"><div class="btn btn-primary btn-lg">Learn More</div></a>
		  ${premiumMessage}		  
		  </div>`,
	},
	'subsection-note-tt': {
		msg: `<h5 class='text-start'>Subsection</h5>
        <p class='text-start mt-2'>Use subsections to control which elements a slider has an effect on. </p>
        <p class='text-start mt-2'><b>Note:</b> Only one slider can be added per subsection due to the logic of a slider interacting with other elements.</p>`, 
	},
  	'slider-disabled-tt': {
		msg: `<h5 class='text-start'>Slider</h5>
        <p class='text-start mt-2 scc-slider-alert-msg'><i class="material-icons scc-icon-tooltips pe-1">info_outline</i> To add a second slider, please create a new subsection.</p>
        <p class='text-start mt-2'><b>Note:</b> Only one slider can be added per subsection due to the logic of a slider interacting with other elements.</p>`, 
	},
	'disabled-save-button-tt': {
		msg: `<h5 class='text-start'>Saving changes</h4>
				  <p class='text-start mt-2'></p>
				  <div class="example-description text-start">
					  </div>`
	},
	'title-font-weight-tt': {
		msg: `<h4 class='text-start'>Title Font Weight</h4>
				  <p class='text-start mt-2'>If you don't see some font weight, that is because the font chosen doesn't support such font weight value</p>
				  <div class="example-description text-start">
					  <br>
					  ${premiumMessage}
					  </div>`
	},
	'font-weight-tt': {
		msg: `<h4 class='text-start'>Font Weight</h4>
				  <p class='text-start mt-2'>If you don't see some font weight, that is because the font chosen doesn't support such font weight value</p>
				  <div class="example-description text-start">
					  <br>
					  ${premiumMessage}
					  </div>`
	},
	'element-style-skin-tt': {
		msg: `<h4 class='text-start'><b>Element</b> Style Skin</h4>
				  <p class='text-start mt-2'>Change the skin for the frontend form fields (elements)</p>
				  <div class="example-description text-start">
					  <h5 class="mt-3">Benefits, Features & Use Cases</h5>
					  <p>You can change your calculator form style to match your website's general styling. This will help you adapt your calculator to any kind of device.</p>
					  <br>
					  <a href="https://designful.freshdesk.com/support/solutions/articles/48001216304-frontend-styles-frontend-skins" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
				  </div>`,
		coverImage:
			'images/tooltip-images/for-settings/infographic-frontend-styles.png',

	},
	'calculator-max-with-tt': {
		msg: `<h4 class='text-start'><b> Calculator Form</b> - Container Max Width</h4>
				  <p class='text-start mt-2'>Sets a maximum width for the form container</p>`,
		coverImage:
			'images/tooltip-images/for-settings/infographic-feat-calculator-max-width.png',
	},
	'accordion-style-tt': {
		msg: `<h4 class='text-start'><b> Accordion</b> style</h4>
				  <p class='text-start mt-2'>Sets a maximum width for the form container</p>`,
		coverImage:
			'images/tooltip-images/for-settings/infographic-accordion-style-v2.png',
	},
	'add-to-cart-redirection-tt': {
		msg: `<h4 class='text-start'>Add-To-Cart Redirection</h4>
				  <p class='text-start mt-2'>Choose the action when pressing the add-to-cart button</p>
				  <div class="example-description text-start">
					  <br>
					  ${premiumMessage}
					  </div>`
	},
	'button-style-visibility-tt': {
		msg: `<h4 class='text-start'><b>Button</b> Style Visibility</h4>
				  <p class='text-start mt-2'>You can change the style of your form action buttons</p>`,
		coverImage:
			'/images/tooltip-images/button-styles.gif',
	},
	'turn-off-border-pay-buttons-tt': {
		msg: `<h4 class='text-start'>Turn off Border for Pay Buttons</h4>
				  <p class='text-start mt-2'>Remove the border from your payment buttons</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`,
		coverImage:
			'/images/tooltip-images/payment-btn-hover-effect.gif',
	},
	'show-email-quote-button-tt': {
		msg: `<h4 class='text-start'>Show Email Quote Button</h4>
				  <p class='text-start mt-2'>Use this to toggle the quote button</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-feat-email-quotes.png'
	},
	'show-view-detailed-list-button-tt': {
		msg: `<h4 class='text-start'>Show View Detailed List Button</h4>
				  <p class='text-start mt-2'>Use this to toggle the detailed list button</p>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-feat-email-quotes.png'

	},
	'show-coupon-button-info-tt': {
		msg: `<h4 class='text-start'>Show Coupon Button</h4>
				  <p class='text-start mt-2'>Use this to toggle the coupon list button</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-feat-coupon-code-button.png'
	},
	'bar-style-visibility-tt': {
		msg: `<h4 class='text-start'>Bar Style Visibility</h4>
				  <p class='text-start mt-2'>You can change the style of the total price bar according to your needs.</p>`,
		coverImage:
				  'images/tooltip-images/for-settings/infographic-feat-total-bar-style-visibility.png'
			//Alternative img '/assets/images/tooltip-images/scc-total-price-styles.jpg'
	},
	'show-floating-total-bar-tt': {
		msg: `<h4 class='text-start'>Show Floating for Total Bar</h4>
				  <p class='text-start mt-2'>Enable this to have the total price float at the bottom of the page</p>`
	},
	'remove-total-price-info-tt': {
		msg: `<h4 class='text-start'>Remove the Total Price</h4>
				  <p class='text-start mt-2'>Enable this to hide the total price at the bottom of the calculator</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`,
		coverImage:
				  'images/tooltip-images/for-settings/infographic-feat-remove-total-price.png'

	},
	'total-price-range-settings-tt' : {
		msg: `<h5 class='text-start'>Minimum Total Price</h4>
				  <p class='text-start mt-2'>Specify a range extending the total calculated value. Using this feature disables checkouts via PayPal, WooCommerce and Stripe.</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`,
    coverImage:
          'images/tooltip-images/for-settings/infographic-price-range.png'
	},
	'minimum-total-price-tt': {
		msg: `<h4 class='text-start'>Minimum Total Price</h4>
				  <p class='text-start mt-2'>Specify the minimum total price at the calculator level.</p>
				  <div class="example-description text-start">
				  <br>
				  <a class="btn btn-primary btn-lg" href="https://designful.freshdesk.com/a/solutions/articles/48001243581" target="_blank">Read more</a>
				  <span class="w-100 d-block pt-3">${premiumMessage}</span>
				  </div>`,
		coverImage:
				  'images/tooltip-images/for-settings/infographic-feat-minimum-total-price-info.png'
	},
	'show-title-info-tt': {
		msg: `<h4 class='text-start'>Show Title</h4>
				<p class='text-start mt-2'>If disabled, detailed list will not show tittle at the top of the view.</p>
				<div class="example-description text-start">
				<br>
				${premiumMessage}
				</div>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-feat-show-title-info-detailed-list.png'		

	},
	'show-unit-price-column-tt': {
		msg: `<h4 class='text-start'>Show Unit Price Column</h4>
				<p class='text-start mt-2'>If disabled, the unit price column of the detailed list view will not be shown</p>
				<div class="example-description text-start">
				<br>
					<a href="https://designful.freshdesk.com/support/solutions/articles/48001151785-unit-price-how-do-i-hide-this" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					<span class="w-100 d-block pt-3">${premiumMessage}</span>
				</div>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-feat-show-unit-price-detailed-list.png'		
	},
	'show-quantity-column-tt': {
		msg: `<h4 class='text-start'>Show Quantity Column</h4>
				<p class='text-start mt-2'>If disabled, the quantity column of the detailed list view will not be shown.</p>
				<div class="example-description text-start">
				<br>
					<a href="https://designful.freshdesk.com/support/solutions/articles/48001231845-hide-quantity-how-do-i-do-this" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					<span class="w-100 d-block pt-3">${premiumMessage}</span>
				</div>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-feat-show-quantity-column-detailed-list.png'	
	},
	'show-save-icon-tt': {
		msg: `<h4 class='text-start'>Show Save Icon</h4>
				<p class='text-start mt-2'>If disabled, the save button at the top of the detailed list view will not be shown.</p>
				<div class="example-description text-start">
				<br>
				${premiumMessage}
				</div>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-feat-show-save-icon-detailed-list.png'	
	},
	'show-tax-display-tt': {
		msg: `<h4 class='text-start'>Show Tax Display</h4>
				<p class='text-start mt-2'>If disabled, the calculated TAX amount will not show in Detailed List/PDF.</p>
				<div class="example-description text-start">
				<br>
				${premiumMessage}
				</div>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-feat-show-tax-display-detailed-list.png'			

	},
	'include-quote-submitted-data-tt': {
		msg: `<h4 class='text-start'>Include Email Form Data to User's Email</h4>
				  <p class='text-start mt-2'>When enabled, the frontend user will also receive his email quote form data. For example: name, phone, email, address.</p>
				  <div class="text-start">
				  <br>
					${premiumMessage}
				  </div>`,
    coverImage:
    'images/tooltip-images/include-quote-form-data.png'
	},
	'user-files-as-attachment-tt' : {
		msg: `<h4 class='text-start'>User Uploaded File Attachments</h4>
			<p class='text-start mt-2'>When <b>enabled</b>, user-uploaded files are attached to sent emails,
			enabling recipients to access and download them directly from the email. This feature enhances communication by ensuring relevant attachments are delivered with the email content.</p>
			  <div class="text-start">
				<br>
				${premiumMessage}
			  </div>`,
	},
	'show-invoice-number-tt': {
		msg: `<h4 class='text-start'>Show <b>Invoice Number</b></h4>
				<p class='text-start mt-2'>If enabled, invoice number will show up on the emailed quote.</p>
				<div class="example-description text-start">
				<br>
				${premiumMessage}
				</div>`		
	},
	'invoice-number-starting-tt': {
		msg: `<h4 class='text-start'><b>Invoice Number</b> Starting Number</h4>
				<p class='text-start mt-2'>Set the number from which invoice number should start from.</p>
				<div class="example-description text-start">
				<br>
				${premiumMessage}
				</div>`

	},
	'footer-notes-tt': {
		msg: `<h4 class='text-start'>Footer Notes</h4>
				<p class='text-start mt-2'>Add footer notes or a disclaimer to your PDF and Detailed List Screen.</p>
				<div class="example-description text-start">
				<br>
				${premiumMessage}
				</div>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-feat-footer-notes-detailed-list.png'		

	},
	'show-search-box-tt': {
		msg: `<h4 class='text-start'>Show <b>Search Box</b></h4>
				<p class='text-start mt-2'>If enabled, the elements will be searchable and can be navigated upon clicking the found options</p>
				<div class="example-description text-start">
				<br>
				<a href="https://designful.freshdesk.com/support/solutions/articles/48001216552-search-box-a-complete-guide" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
				<span class="w-100 d-block pt-3">${premiumMessage}</span>
				</div>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-search-box.png'

	},
	'tax-vat-info-tt': {
		msg: `<h4 class='text-start'>Tax/VAT</h4>
				  <p class='text-start mt-2'>Applies TAX on total value returned by the calculator</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-tax-2.png'

	},
	'show-tax-vat-before-total-tt': {
		msg: `<h4 class='text-start'>Show Tax/VAT before Total</h4>
				  <p class='text-start mt-2'>Show TAX on total value returned by the calculator</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-feat-show-tax-front-end.png'
	},
	'symbol-placement-style-tt': {
		msg: `<h4 class='text-start'>Symbol Placement Style</h4>
				  <p class='text-start mt-2'>Helps define the currency symbol placement. You can have the currency symbol show at the left or the right side.</p>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-feat-currency-style.png'
	},
	'show-currency-label-tt': {
		msg: `<h4 class='text-start'>Show Currency label</h4>
				  <p class='text-start mt-2'>Shows the currency symbol if enabled</p>`,
		coverImage:
			'images/tooltip-images/for-settings/infographic-feat-show-currency-label.png'
	},
	'allow-users-choose-currency-tt': {
		msg: `<h4 class='text-start'>Allow users to choose their currency</h4>
				  <p class='text-start mt-2'>Shows a dropdown above the total bar</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`,
		coverImage:
			'images/tooltip-images/for-settings/infographic-feat-choose-their-currency.png'
	},
	'currency-selector-tt': {
		msg: `<h4 class='text-start'>Currency Selector ($,€,£)</h4>
				  <p class='text-start mt-2'>Helps define the currency symbol placement. You can have the currency symbol show at the left or the right side.</p>`,
		coverImage:
			'images/tooltip-images/for-settings/infographic-feat-currency-selector.png'
	},
	'currency-selector-global-tt': {
		msg: `<h4 class='text-start'>Currency Selector ($,€,£)</h4>
				  <p class='text-start mt-2'>Choose your calculator forms currency. You can choose to have a symbol ($) or intials (USD) at the calculator settings level.</p>`,
		coverImage:
			'images/tooltip-images/for-settings/infographic-feat-currency-selector.png'
	},
	
	'auto-currency-conversion-tt': {
		msg: `<h4 class='text-start'>Auto Currency Conversion</h4>
				  <p class='text-start mt-2'>Automatically convert the currency into the users locale currency.</p>
				  <div class="example-description text-start">
				  <br>
				  	<a href="https://designful.freshdesk.com/support/solutions/articles/48001143319-live-currency-conversion-a-complete-guide" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					<span class="w-100 d-block pt-3">${premiumMessage}</span>
				  </div>`,
		coverImage:
				  'images/tooltip-images/for-settings/infographic-feat-live-conversion.png'
	},
	'currency-format-tt': {
		msg: `<h4 class='text-start'>Currency Format (dot/comma)</h4>
				  <p class='text-start mt-2'>Choose whether to use a period or comma to separate the price. Example: 14,00.00 vs 15,000,00</p>`,
		coverImage:
				  'images/tooltip-images/for-settings/infographic-feat-currency-format.png'
	},
	'currency-format-global-tt': {
		msg: `<h4 class='text-start'>Currency Format</h4>
				  <p class='text-start mt-2'>Choose whether to use a period or comma to separate the price. Example: 14,00.00 vs 15,000,00</p>`,
		coverImage:
				  'images/tooltip-images/for-settings/infographic-feat-currency-format.png',
	},
	'pdf-font-style-tt': {
		msg: `<h4 class='text-start'>PDF Font Style</h4>
				  <p class='text-start mt-2'>Define the typography style for your pdf</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`
	},
	'pdf-date-format-tt': {
		msg: `<h4 class='text-start'>PDF Date Format</h4>
				  <p class='text-start mt-2'>Select the date format to display in the pdf</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`
	},
	'pdf-format-tt': {
		msg: `<h4 class='text-start'>PDF Format</h4>
				  <p class='text-start mt-2'>Choose the font family you want to use. Depending on the language you use, choosing a different font can improve the texts on the PDF</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`
	},
	'email-subject-tt': {
		msg: `<h4 class='text-start'>Email Subject</h4>
				  <p class='text-start mt-2'>Change your email subject for outgoing quotes sent via email to the user and copy to yourself.</p>`
	},
	'email-body-tt': {
		msg: `<h4 class='text-start'>PDF Date Format</h4>
				  <p class='text-start mt-2'>Change the email body</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`
	},
	'email-quote-recipient-tt': {
		msg: `<h4 class='text-start'>Email Quote Recipient(s)</h4>
				  <p class='text-start mt-2'>You can choose to either send to the admin, send to the user or both</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`
	},
	'user-completes-email-quote-form-tt': {
		msg: `<h4 class='text-start'>User completes an Email Quote Form</h4>
				  <p class='text-start mt-2'>Enable this to set an webhook endpoint when a user completes an email quote form.</p>
				  <div class="example-description text-start">
				  <br>
				  <a href="https://designful.freshdesk.com/support/solutions/articles/48001216553-webhooks-a-complete-guide" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					<span class="w-100 d-block pt-3">${premiumMessage}</span>
				  </div>`,
				  coverImage:
							'images/tooltip-images/for-settings/infographic-feat-webhook-leads.png'
	},
	'user-clicks-detailed-list-button-tt': {
		msg: `<h4 class='text-start'>User clicks the Detailed List button</h4>
				  <p class='text-start mt-2'>Enable this to setup an webhook endpoint when a user clicks the 'detailed view' button</p>
				  <div class="example-description text-start">
				  <br>
				  <a href="https://designful.freshdesk.com/support/solutions/articles/48001216553-webhooks-a-complete-guide" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					<span class="w-100 d-block pt-3">${premiumMessage}</span>
				  </div>`,
				  coverImage:
							'images/tooltip-images/for-settings/infographic-feat-webhook-leads.png'
	},
	'user-completes-email-quote-form-js-tt': {
		msg: `<h4 class='text-start'>User completes an Email Quote Form</h4>
				  <p class='text-start mt-2'>Enable this to execute javascript when a user completes an email quote form.</p>
				  <div class="example-description text-start">
				  <br>
				  <a href="https://designful.freshdesk.com/support/solutions/articles/48001216553-webhooks-a-complete-guide" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					<span class="w-100 d-block pt-3">${premiumMessage}</span>
				  </div>`,
				  coverImage:
							'images/tooltip-images/for-settings/infographic-feat-webhook-leads.png'
	},
	'user-clicks-detailed-list-button-js-tt': {
		msg: `<h4 class='text-start'>User clicks the Detailed List button</h4>
				  <p class='text-start mt-2'>Enable this to execute javascript when a user clicks the 'detailed view' button</p>
				  <div class="example-description text-start">
				  <br>
				  <a href="https://designful.freshdesk.com/support/solutions/articles/48001216553-webhooks-a-complete-guide" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					<span class="w-100 d-block pt-3">${premiumMessage}</span>
				  </div>`,
				  coverImage:
							'images/tooltip-images/for-settings/infographic-feat-webhook-leads.png'
	},
	'subsection-tt': {
		msg: `<h4 class='text-start'>Subsection</h4>
				  <p class='text-start mt-2'>Use subsections to control which elements a slider has an effect on.</p>
				  <div class="example-description text-start">
					  <h5 class="mt-3">Note</h5>
					  <p>You can only add one slider per subsection.</p>
				  </div>`	  
	},
	'payment-option-paypal-tt': {
		msg: `<h4 class='text-start'>PayPal integration</h4>
				  <p class='text-start mt-2'>SCC can send the product/service name, quantity, and price to PayPal. Additionally, you will be able to see the user's name and email associated with their PayPal account.</p>
				  <div class="example-description text-start">
				  <br>
				  <a href="https://designful.freshdesk.com/support/solutions/articles/48000948103-paypal-integration" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
			  	 
				  <span class="w-100 d-block pt-3">${premiumMessage}</span>
				  </div>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-pay-paypal.png'
	},
	'payment-option-stripe-tt': {
		msg: `<h4 class='text-start'>Stripe Integration</h4>
				  <p class='text-start mt-2'>Adding a Stripe checkout button to your calculator is very easy. All you have to do is click the button at the bottom of your calculator instance and fill in your Stripe details, then click SAVE.</p>
				  <div class="example-description text-start">
				  <br>
				  <a href="https://designful.freshdesk.com/support/solutions/articles/48001167920-stripe-integration-everything-to-know" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
			  	 
				  <span class="w-100 d-block pt-3">${premiumMessage}</span>
				  </div>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-pay-stripe.png'
	},
	'payment-option-woocommerce-tt': {
		msg: `<h4 class='text-start'>WooCommerce integration</h4>
				  <p class='text-start mt-2'>You will be able to configure and customize your products/services, get a quote via email and process the payment easily with the WooCommerce checkout</p>
				  <div class="example-description text-start">
				  <br>
				  <a href="https://designful.freshdesk.com/support/solutions/articles/48001076926-woocommerce-integration" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
			  	 
				  <span class="w-100 d-block pt-3">${premiumMessage}</span>
				  </div>`,
		coverImage:
				'images/tooltip-images/for-settings/infographic-pay-woocommerce.png'
	},
	'force-email-form-before-checkout-tt': {
		msg: `<h4 class='text-start'>Force email form before checkout</h4>
				  <p class='text-start mt-2'>Make it mandatory for users to fillout a Email Form before they proceed to a checkout (PayPal and Stripe Only)</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`
	},
	'show-user-details-detailed-list-pdf-tt': {
		msg: `<h4 class='text-start'>Show User Details on Detailed List & PDF</h4>
				  <p class='text-start mt-2'>Shows the field filled by the user in the detail view</p>
				  <div class="example-description text-start">
				  <br>
				  ${premiumMessage}
				  </div>`
	},
}
/* Tool Tip for added Elements*/
const elementTooltips = {
	'dropdown': {
		msg: `<h4 class='text-start'><b>Dropdown</b> Element</h4>
				  <p class='text-start mt-2'>The dropdown element is used to create a drop-down list.</p>
				  <p class='text-start mb-0'>1. Use it when a user is only allowed to pick one selection. Use the checkbox element if the user is allowed to pick more than one.</p>
				  <div class="example-description text-start">
					  <h5 class="mt-3">Quantity Multiplier</h5>
					  <p>You can attach a slider element below a dropdown menu to act as a multiplier.</p>
					  <br>
					  <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001207045-element-dropdown-menu" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
				  </div>`,
		coverImage:
			'images/tooltip-images/for-elements/infographics-dropdown.png',
	},
	'slider-element': {
		msg: `<h4 class='text-start'><strong>Slider</strong> Element</h4>
				  <p class='text-start mt-2'>Sliders are linked to any elements in the same subsection.</p>
				  <p class='text-start mb-0'>1. It can act as a multiplier of other elements</p>
				  <p class='text-start mb-0'>2. It can act as a product selector. Buy X amount of item</p>
				  <div class="example-description text-start">
					  <h5 class="mt-3">Example</h5>
					  <p>If you select 10 units with the slider, it will add 10 units to any dropdown
					  or checkbox element above it. If you want to unlink them, make sure the slider sits in it's
					  own subsection.</p>
					  <br>
					  <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001207026-element-slider" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
				  </div>`,
		coverImage:
			'images/tooltip-images/for-elements/infographics-slider.png',
	},
	'checkbox-buttons': {
		msg: `<h4 class='text-start'><b>Checkbox</b> Element</h4>
				  <p class='text-start mt-2'>The checkbox is shown as a square box that is ticked (checked) when activated.
				  Checkboxes are used to let a user select one or more options of a limited number of choices.</p>
				  <div class="example-description text-start">
				  <h5 class="mt-3">Other Styles</h5>
				  <p class='text-start mb-0'>1. Circle & Square Checkboxes - more than one is allowed</p>
				  <p class='text-start mb-0'>2. Simple Buttons - more than one option is allowed</p>
				  <p class='text-start mb-0'>3. Toggle Switches -  more than one option is allowed</p>
				  <p class='text-start mb-0'>5. Radio Buttons - only one option is allowed</p>
				  <p class='text-start mb-0'>6. Image Buttons -  more than one option is allowed</p>
			  </div>
				  <div class="example-description text-start">
					  <h5 class="mt-3">Quantity Multiplier</h5>
					  <p>You can attach a slider element below a checkbox to act as a multiplier of the checkbox.</p>
					  <br>
					  <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001207024-checkbox-toggle-switches-simple-buttons-element" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
				  </div>`,
		coverImage:
			"images/tooltip-images/for-elements/infographics-checkbox-toggle-button-radio.png"
	},
	'radio-buttons': {
		msg: `<h4 class='text-start'><b>Radio Buttons</b> Element</h4>
				  <p class='text-start mt-2'>Radio buttons allow the user to select one option from a list of mutually exclusive options. Radio buttons are unique in that users cannot select or deselect any quantity of items, unlike checkboxes. </p>
				  <div class="example-description text-start">
				  <h5 class="mt-3">Other Styles</h5>
				  <p class='text-start mb-0'>1. Circle & Square Checkboxes - more than one is allowed</p>
				  <p class='text-start mb-0'>2. Simple Buttons - more than one option is allowed</p>
				  <p class='text-start mb-0'>3. Toggle Switches -  more than one option is allowed</p>
				  <p class='text-start mb-0'>5. Radio Buttons - only one option is allowed</p>
				  <p class='text-start mb-0'>6. Image Buttons -  more than one option is allowed</p>
			  </div>
				  <div class="example-description text-start">
					  <h5 class="mt-3">Quantity Multiplier</h5>
					  <p>You can attach a slider element below to act as a multiplier of the radio button.</p>
					  <br>
					  <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001207024-checkbox-toggle-switches-simple-buttons-element" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
				  </div>`,
		coverImage:
				"images/tooltip-images/for-elements/infographics-checkbox-toggle-button-radio.png"
	},
	'simple-buttons': {
		msg: `<h4 class='text-start'><b>Simple Buttons</b> Element</h4>
				  <p class='text-start mt-2'>Looks just like a regular CTA button on your website. These buttons are displayed in-line (side by side). </p>
				  <div class="example-description text-start">
				  <h5 class="mt-3">Other Styles</h5>
				  <p class='text-start mb-0'>1. Circle & Square Checkboxes - more than one is allowed</p>
				  <p class='text-start mb-0'>2. Simple Buttons - more than one option is allowed</p>
				  <p class='text-start mb-0'>3. Toggle Switches -  more than one option is allowed</p>
				  <p class='text-start mb-0'>5. Radio Buttons - only one option is allowed</p>
				  <p class='text-start mb-0'>6. Image Buttons -  more than one option is allowed</p>
			  </div>
				  <div class="example-description text-start">
					  <h5 class="mt-3">Quantity Multiplier</h5>
					  <p>You can attach a slider element below to act as a multiplier of any selected simple button.</p>
					  <br>
					  <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001207024-checkbox-toggle-switches-simple-buttons-element" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
				  </div>`,
		coverImage:
				"images/tooltip-images/for-elements/infographics-checkbox-toggle-button-radio.png"
	},
	'toggle-switches': {
		msg: `<h4 class='text-start'><b>Toggle Switches</b> Element</h4>
				  <p class='text-start mt-2'>A toggle switch allows users to choose between two opposing states, such as on or off. If there are multiple options, its best to use something else.Another benefit to toggle switches is that they work immediately. Radio buttons or checkboxes need users to hit a submit button before the choice goes into effect. </p>
				  <div class="example-description text-start">
				  <h5 class="mt-3">Other Styles</h5>
				  <p class='text-start mb-0'>1. Circle & Square Checkboxes - more than one is allowed</p>
				  <p class='text-start mb-0'>2. Simple Buttons - more than one option is allowed</p>
				  <p class='text-start mb-0'>3. Toggle Switches -  more than one option is allowed</p>
				  <p class='text-start mb-0'>5. Radio Buttons - only one option is allowed</p>
				  <p class='text-start mb-0'>6. Image Buttons -  more than one option is allowed</p>
				  </div>
				  <div class="example-description text-start">
					  <h5 class="mt-3">Quantity Multiplier</h5>
					  <p>You can attach a slider element below to act as a multiplier of any selected toggle switch.</p>
					  <br>
					  <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001207024-checkbox-toggle-switches-simple-buttons-element" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
				  </div>`,
		coverImage:
				"images/tooltip-images/for-elements/infographics-checkbox-toggle-button-radio.png"
	},
	'image-buttons-tt': {
		msg: `<h4 class='text-start'><b>Image Buttons</B> Element</h4>
				  <p class='text-start mt-2'>Create a button using an image instead of text.</p>
				  <div class="example-description text-start">
					  <h5 class="mt-3">Other Styles</h5>
					  <p class='text-start mb-0'>1. Circle & Square Checkboxes - more than one is allowed</p>
					  <p class='text-start mb-0'>2. Simple Buttons - more than one option is allowed</p>
					  <p class='text-start mb-0'>3. Toggle Switches -  more than one option is allowed</p>
					  <p class='text-start mb-0'>5. Radio Buttons - only one option is allowed</p>
					  <p class='text-start mb-0'>6. Image Buttons -  more than one option is allowed</p>
				  </div>
				  <div class="example-description text-start">
					  <h5 class="mt-3">Quantity Multiplier</h5>
					  <p>You can attach a slider element below to act as a multiplier of any clicked Image Button.</p>
					  <br>
					  <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001207056-element-image-buttons" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					  <span class="w-100 d-block pt-3">${premiumMessage}</span>
				  </div>`,
		coverImage:
			'images/tooltip-images/for-elements/infographics-image-button.png',
	},
	'date-picker-tt': {
		msg: `<h4 class='text-start'><b>Date</b> Picker</h4>
				<p class='text-start mt-2'>It's great for bookings, schedules, pickup dates, general info, etc.</p>
				<div class="example-description text-start">
					<h5 class="mt-3">Use Cases</h5>
					<p class='text-start mb-0'>1. Let your users schedule or book your services</p>
					<p class='text-start mb-0'>2. Set an appointment with your customers</p>
					<br>
					<a href="https://designful.freshdesk.com/support/solutions/articles/48001216557-element-date-picker" target="_blank"><div class="btn btn-primary btn-lg">Learn More</div></a>
					<span class="w-100 d-block pt-3">${premiumMessage}</span>
				</div>`,
		coverImage:
      'images/tooltip-images/for-elements/infographics-date-picker.png',
	},
	'distance-cost-tt': {
		msg: `<h4 class='text-start'><b>Distance-Based Cost</b></h4>
				<p class='text-start mt-2'>The Distance-Based Cost element calculates costs between two points, requiring a Google Cloud account for distance calculation.</p>
				
				<div class="example-description text-start">
					<h5 class="mt-3">Example</h5>
					<p class='text-start mb-0'>Delivery services might determine fees based on distances, and travel agencies can instantly quote fares for location-based trips.</p>
					<br>
					<a href="https://designful.freshdesk.com/a/solutions/articles/48001244300" target="_blank"><div class="btn btn-primary btn-lg">Learn More</div></a>
					<span class="w-100 d-block pt-3">${premiumMessage}</span>
				</div>`,
					coverImage:
						'images/tooltip-images/for-elements/infographics-distance-based-cost.png',
	},
	'default-checkbox': {
		msg: `<h4 class='text-start'><b>Checkbox</b> Element</h4>
				  <p class='text-start mt-2'>Sliders are linked to any elements in the same subsection.</p>
				  <p class='text-start mb-0'>1. It can act as a multiplier of other elements</p>
				  <p class='text-start mb-0'>2. It can act as a product selector. Buy X amount of item</p>
				  <div class="example-description text-start">
					  <h5 class="mt-3">Example</h5>
					  <p>If you select 10 units with the slider, it will add 10 units to any dropdown
					  or checkbox element above it. If you want to unlink them, make sure the slider sits in it's
					  own subsection.</p>
					  <br>
					  <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001207024-checkbox-toggle-switches-simple-buttons-element" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
				  </div>`
	},
	'comment-box': {
		msg: `<h4 class='text-start'><b>Comment Box</b> Element</h4>
				  <p class='text-start mt-2'>The Comment Box is basically any user input field.</p>
				  <p class='text-start mb-0'>1. Collect a date</p>
				  <p class='text-start mb-0'>2. Collect name, address, phone numbers</p>
				  <p class='text-start mb-0'>3. Ask for more information for a interested product or service</p>
				  <div class="example-description text-start">
					  <br>
					  <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001207024-checkbox-toggle-switches-simple-buttons-element" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
				  </div>`,
		coverImage:
			'images/tooltip-images/for-elements/infographics-comment-box.png',
	},
	'quantity-input-box': {
		msg: `<h4 class='text-start'><b>Number Input </b> Element</h4>
					<p class='text-start mt-2'>Add a number (quantity) input field to your calculator form. </p>
					<p class='text-start mb-0'>1. Sliders will multiply the Number Input quantity if they are in the same subsection.</p>
					<div class="example-description text-start">
						<h5 class="mt-3">Example</h5>
						<p>For example, if a user enter 10 quantity in the number input box, and the slider is set to 5, it will 5 x 10 = 50. If you don't want a slider to affect this input, just make sure no slider is in the same subsection</p>
						<br>
						<a href="https://designful.freshdesk.com/en/support/solutions/articles/48001081164-quantity-box-element-how-does-it-work-" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					</div>`,
		coverImage:
			'images/tooltip-images/for-elements/infographics-number-input.png',
	},
	'custom-math-tt': {
		msg: `<h4 class='text-start'><b>Custom Math</b></h4>
				  <p class='text-start mt-2'>This is not a user element, but math that works in the background. Use this to control the math of the subsections total price.</p>
				  <div class="example-description text-start">
					  <h5 class="mt-3">Use Cases</h5>
					  <p class='text-start mb-0'>1. Add shipping cost</p>
					  <p class='text-start mb-0'>2. Add admin fee</p>
					  <p class='text-start mb-0'>3. Give a bundle discount</p>
					  <p class='text-start mb-0'>4. Trigger a fee if another item is select</p>
					  <p class='text-start mb-0'>5. Trigger a discount under certain conditions</p>
					  <br>
					  <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001142309-custom-math-learn-more-about-this-feature-and-function" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					  <span class="w-100 d-block pt-3">${premiumMessage}</span>
				  </div>`,
		coverImage:
			'images/tooltip-images/for-elements/infographic-elmnt-custom-math.png',
	},
	'variable-math-tt': {
		msg: `<h4 class='text-start'><b>Variable Math</b> Element</h4>
				  <p class='text-start mt-2'>This element comes in the form of a Slider or Quantity Box, however, it gives you the ability to add variables to control the final price.</p>
				  <div class="example-description text-start">
				  <h5 class="mt-3">How to Use</h5>
					  <p class='text-start mb-0'>For the total calculation. You must use the exact words of Input1, Input2, Input3, etc. Do not use the name of the item</p>
					  <p class='text-start mb-0'>Example 1: Input 1 / Input 2</p>
					  <p class='text-start mb-0'>Example 2: Input 1 + Input 2 + Input3 </p>
					  <p class='text-start mb-0'>Example 3: (Input 1 * Input 2) / 2</p>
					  <p class='text-start mb-0'>Example 4: (Input 1 / Input 2) * 2</p>
				  </div>
        		  <div class="example-description text-start">
					  <h5 class="mt-3">Use Cases</h5>
					  <p class='text-start mb-0'>1. Bundle discounts</p>
					  <p class='text-start mb-0'>2. Shipping Cost</p>
					  <p class='text-start mb-0'>3. Length X Width X Height</p>
					  <br>
					  <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001202286-element-variable-math" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					  <span class="w-100 d-block pt-3">${premiumMessage}</span>
				</div>`,
		coverImage:
			'images/tooltip-images/for-elements/infographic-variable-math-modes.png',
	},
	'file-upload-tt': {
		msg: `<h4 class='text-start'><b>File Upload</b> Element</h4>
				  <p class='text-start mt-2'>Add a file upload element to your calculator forms.</p>
				  <div class="example-description text-start">
					  <h5 class="mt-3">Use Cases</h5>
					  <p class='text-start mb-0'>1. Request images</p>
					  <p class='text-start mb-0'>1. Request documents</p>
					  <br>
					  <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001166819-element-file-upload" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					  <span class="w-100 d-block pt-3">${premiumMessage}</span>
				  </div>`,
		coverImage:
			'images/tooltip-images/for-elements/infographic-element-upload.png',
	},
	'text-html-field-tt': {
		msg: `<h4 class='text-start'><b>Text/HTML</b> Field</h4>
				  <p class='text-start mt-2'>Add raw text or HTML to your calculator form.</p>
				  <div class="example-description text-start">
					  <h5 class="mt-3">Use Cases</h5>
					  <p class='text-start mb-0'>1. Add a title for checkboxes</p>
					  <p class='text-start mb-0'>2. Display a message in red writing</p>
			<p class='text-start mb-0'>3. Use conditional logic to alert people under certain conditions</p>
					  <br>
					  <a href="https://designful.freshdesk.com/en/support/solutions/articles/48001207057-element-text-html-field" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					  <span class="w-100 d-block pt-3">${premiumMessage}</span>
				  </div>`,
		coverImage:
			'images/tooltip-images/for-elements/infographics-html-plain-field.png',
	},
	'slider-type-bulk': {
		msg: `<h4 class='text-start'><b>Bulk Quantity Discounts</b></h4>
		<p class='text-start mt-2'>Set one or more price ranges and apply discount for bulk purchases</p>
		<div class="example-description text-start">
						<h5 class="mt-3">Example</h5>
						<h5 class='mt-2'>Price ranges:</h5>
						<p class='text-start mb-0'>Between 1 and 5 units = $50 (per unit price)</p>
						<p class='text-start mb-0'>Between 6 and 10 units = $45 (per unit price)</p>
						<p class='text-start mb-0'>Between 11 and 20 units = $45 (per unit price)</p>
						<h5 class='mt-2'>Prices:</h5>
						<p class='text-start mb-0'>3 Units = $150 (total price)</p>
						<p class='text-start mb-0'>8 Units = $360 (total price)</p>
						<p class='text-start mb-0'>15 Units = $675 (total price)</p>
						<br>
						<a href="https://designful.freshdesk.com/a/solutions/articles/48001079780" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					</div>`
	},
	'slider-type-default': {
		msg: `<h4 class='text-start'><b>Default</b></h4>
		<p class='text-start mt-2'>Multiply the product quantity by the slider number</p>
		<div class="example-description text-start">
			  
						<h5 class="mt-3">Example</h5>
						<h5 class='mt-2'>Product price:$100</h5>
						<p class='text-start mb-0'>3 Units = $300 total price</p>
						<p class='text-start mb-0'>4 Units = $400 total price</p>
						<p class='text-start mb-0'>5 Units = $500 total price</p>
						<br>
						<a href="https://designful.freshdesk.com/a/solutions/articles/48001079780" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					</div>`
	},
	'slider-type-quantity_mod': {
		msg: `<h4 class='text-start'><b>Element Quantity modifier</b></h4>
		<p class='text-start mt-2'>Modifies quantity value of the elements available on the subsection</p>
		<div class="example-description text-start">
			  <h5 class="mt-3">Example</h5>
			  <p class='text-start mb-0'>If you have a dropdown on the subsection, the slider will multiply the quantity by the value returned by the slider</p>
						<br>
						<a href="https://designful.freshdesk.com/a/solutions/articles/48001079780" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					</div>`
	},
	'slider-type-sliding': {
		msg: `<h4 class='text-start'><b>Sliding Quantity Discounts</b></h4>
		<p class='text-start mt-2'>Set a definite price for a set of quantity ranges</p>
		<div class="example-description text-start">
			  <p class='text-start mb-0'>Between 1 and 10 = $100 (flat price)</p>
			  <p class='text-start mb-0'>Between 11 and 20 = $200 (flat price)</p>
			  <p class='text-start mb-0'>Between 21 and 30 = $300 (flat price)</p>
	
			  <h5 class="mt-3">Example</h5>
			  <p class='text-start mb-0'>3 Units = $100 (total price)</p>
			  <p class='text-start mb-0'>15 Units =$200 (total price)</p>
			  <p class='text-start mb-0'>25 Units= $300 (total price)</p>
						<br>
						<a href="https://designful.freshdesk.com/a/solutions/articles/48001079780" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					</div>`
	},
	"enable-price-hint-bubble-tt": {
		msg: `<h5 class='text-start'><b>Price Hint Bubble</b></h4>
		<p class='text-start mt-2'>Will show a temporary pop-up bubble with the cost that was added to the total when the user selects any item in this element.</p>
		<div class="example-description text-start">
			<br>
			<a href="https://designful.freshdesk.com/support/solutions/articles/48001142314-price-hint-learn-more-about-this-feature" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
			<span class="w-100 d-block pt-3">${premiumMessage}</span>
		</div>`,
		coverImage:
		  "/images/tooltip-images/for-ad-settings/infographic-ad-hint-bubble.png",
	  },
	"qnt-input-comma-number": {
		msg: `<h5 class='text-start'><b>Input Box Comma Formatting</b></h4>
		<p class='text-start mt-2'>Toggle on to enable comma number formatting in the 'quantity' input box. This will display large numbers with commas for improved readability.</p>`,
	},
	"mandatory-elements-tt": {
		msg: `<h5 class='text-start'><b>Required</b></h4>
		<p class='text-start mt-2'>By activating this option, users are forced to select an option before being able to proceed to an email quote or view the detailed list</p>
		<div class="example-description text-start">
			<br>
			<a href="https://designful.freshdesk.com/support/solutions/articles/48001170573-required-fields-a-complete-guide" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
			</div>`,
		coverImage:
		  "/images/tooltip-images/for-ad-settings/infographic-ad-redquired-field.png",
	  },
	  "display-on-detailed-list-pdf-tt": {
		msg: `<h5 class='text-start'>Display
		on <b>Detailed List & PDF</b></h4>
		<p class='text-start mt-2'>Show this element in the Detailed List view & PDF. When this is not activated, it will only add to the calculator price and will not appear on the invoice.</p>
		<div class="example-description text-start">
			</div>`,
		coverImage:
		  "/images/tooltip-images/for-elements/infographic-display-on-detailed-list-pdf.png",
	  },
	  "append-quantity-input-box-tt": {
		msg: `<h5 class='text-start'>Append <b>Quantity Input Box</b></h4>
		<p class='text-start mt-2'>When this option is activated, it will add a Quantity Input Box to the end of the slider. Linking both elements together.</p>
		<div class="example-description text-start">
			<br>
			${premiumMessage}
			</div>`,
		coverImage:
		  "/images/tooltip-images/for-elements/infographic-append-quantity-input-box.png",
	  },
	  "convert-to-quantity-input-box-tt": {
		msg: `<h5 class='text-start'>Convert to <b>Quantity Input Box</b></h4>
		<p class='text-start mt-2'>By activating this option you will swap the Slider for a Quantity Input Box. Giving you the functionality of a slider but with the look of a Quantity Input Box</p>
		<div class="example-description text-start">
			</div>`,
		coverImage:
		  "/images/tooltip-images/for-elements/infographic-convert-to-quantity-input-box.png",
	  },
	  "show-title-on-detailed-list-tt": {
		msg: `<h5 class='text-start'>Show title on <b>Detailed list & PDF</b></h4>
		<p class='text-start mt-2'>By enabling this setting, the main Element title will appear next to Item titles in the invoice.</p>
		<div class="example-description text-start">
			</div>`,
		coverImage:
		  "/images/tooltip-images/for-ad-settings/infographic-ad-show-item-name-on-pdf.png",
	  },
	  "show-item-name-on-pdf-tt": {
		msg: `<h5 class='text-start'>Show Item Name on <b>PDF</b></h4>
		<p class='text-start mt-2'>When this setting is enabled, the custom math element title appears next to the item titles in the PDF invoice & Detailed List view.</p>
		<div class="example-description text-start">
			</div>`,
		coverImage:
		  "/images/tooltip-images/for-ad-settings/infographic-ad-show-item-name-on-pdf.png",
	  },
	  "show-result-on-frontend-tt": {
		msg: `<h5 class='text-start'>Show Result on <b>Frontend</b></h4>
		<p class='text-start mt-2'>When this setting is enabled, it will show the calculation result on the frontend.</p>
		<div class="example-description text-start">
			</div>`,
			coverImage:
			  "/images/tooltip-images/for-ad-settings/infographic-ad-show-result-on-frontend.png",
	  },
	  "default-zero-value-tt": {
		msg: `<h5 class='text-start'>Default <b>Zero (0)</b> Value</h4>
		<p class='text-start mt-2'>When this setting is enabled, it will replace blank Number Input Boxes with a 0 value. This helps in situations where users do not need to fill out all input fields for this custom math element to work.</p>
		<div class="example-description text-start">
			</div>`,
			coverImage:
			  "/images/tooltip-images/for-ad-settings/infographic-ad-default-zero.png",
	  },
	  "add-border-to-image-tt": {
		msg: `<h5 class='text-start'>Add Border to <b>Image</b></h4>
		<p class='text-start mt-2'>Adds a border to the image that helps to differentiate it from other items or elements.</p>
		<div class="example-description text-start">
			</div>`,
			coverImage:
			  "/images/tooltip-images/for-ad-settings/infographic-ad-add-border.png",
	  },
	  "allow-multiple-selections-tt": {
		msg: `<h5 class='text-start'>Allow Multiple <b>Selections</b></h4>
		<p class='text-start mt-2'>When this setting is enabled, it will allow users to select more than one item. When this setting is disabled, it will turn your Image Buttons into a radio-button style function, allowing only one selection at a time.</p>
		<div class="example-description text-start">
			</div>`,
			coverImage:
			  "/images/tooltip-images/for-ad-settings/infographic-ad-allow-multiple-selection.png",
	  },
	  "display-math-on-front-end-form-tt": {
		msg: `<h5 class='text-start'>Display on <b>Frontend Form</b></h4>
		<p class='text-start mt-2'>Show this element on the frontend, allowing the user to see the additional calculation provided by custom math.</p>
		<div class="example-description text-start">
			</div>`,
			coverImage:
			  "/images/tooltip-images/for-ad-settings/infographic-ad-display-on-frontend-form.png",
	  },
	  "display-math-on-detailed-list-tt": {
		msg: `<h5 class='text-start'>Display on Detailed <b>List & PDF</b></h4>
		<p class='text-start mt-2'>Show this element on detailed list & pdf, allowing the user to see the additional calculation provided by custom math.</p>
		<div class="example-description text-start">
			</div>`,
			coverImage:
			  "/images/tooltip-images/for-elements/infographic-display-on-detailed-list-pdf.png",
	  },
	  "show-calculation-symbol-tt": {
		msg: `<h5 class='text-start'>Show Calculation <b>Symbol</b></h4>
		<p class='text-start mt-2'>Shows the symbol used in the calculation (+ ,- ,x ,/ , %).</p>
		<div class="example-description text-start">
			</div>`,
			coverImage:
			  "/images/tooltip-images/for-ad-settings/infographic-ad-show-calculation-symbol.png",
	  },
	  "apply-math-to-calculation-total-tt": {
		msg: `<h5 class='text-start'>Apply Math to <b>Calculator Total</b></h4>
		<p class='text-start mt-2'>By default it will only apply math to the subsection total.</p>
		<div class="example-description text-start">
			</div>`,
			coverImage:
			  "/images/tooltip-images/for-ad-settings/infographic-ad-apply-math-to-calculator-total.png",
	  },
	  'use-parent-font-family-tt': {
		msg: `<h5 class='text-start'>Use Parent Font-family</h4>
				  <p class='text-start mt-2'>Use your theme or page's font-family</p>
				  <div class="example-description text-start">
				  </div>`
	  },
	  'responsive-options-tt': {
		msg: `<h5 class='text-start'>Responsive Options</h4>
				  <p class='text-start mt-2'>Adjust the size of the columns of your calculator in its desktop and mobile version</p>
				  <div class="example-description text-start">
				  ${premiumMessage}
				  </div>`,
      	coverImage:
          'images/tooltip-images/for-ad-settings/infographics-ad-mobile-responsive.png'
	   },
	   'conditional-logic-tt': {
		msg: `<h5 class='text-start'>Conditional Logic</h4>
				  <p class='text-start mt-2'>With Conditional Logic, you can customize your form's behavior based on specific actions. The form's experience can be dynamically customized according to the responses you receive.</p>
				  <div class="example-description text-start">
					<h5 class="mt-3">Use cases</h5>
					<p class='text-start mb-0'>1. Organize long forms by hiding secondary options until the user selects a parent option first</p>
					<p class='text-start mb-0'>2. Show/hide an element based on the selection of a previous element</p>
					<p class='text-start mb-0'>3. Multiple conditions can be combined with an 'and' condition.</p>
					<p class='text-start mb-0'>4. Show or hide an element based on the calculator's total price</p>
					<p class='text-start mb-0'>5. Trigger fees or discounts by applying conditional logic to a custom math element</p>
					
					<br>
				  <a href="https://designful.freshdesk.com/support/solutions/articles/48001157409-conditional-logic-a-complete-guide" target="_blank"><div class="btn btn-primary btn-lg">Learn more</div></a>
					<span class="w-100 d-block pt-3">${premiumMessage}</span>
				  </div>`,
      	coverImage:
          'images/tooltip-images/infographic-feat-conditional-logic.png'
	   },
	   'checkbox-styles-tt': {
		msg: `<h5 class='text-start'>Input Styles</h4>
				  <p class='text-start mt-2'>Pick the desired input style to display the checkbox and buttons</p>
				  <div class="example-description text-start">
				  </div>`,
		coverImage:
			'images/tooltip-images/for-elements/infographic-checkboxes-2.jpg'
		},
}

const needLicenseKeyTooltip = `You need to purchase a <a href="https://stylishcostcalculator.com/" target="_blank">premium license</a> to use this feature.`

// Upload button click
var handleDropdownLogoSetup = function ($this) {
	event.preventDefault();
	formField = jQuery($this);
	if (window.hasOwnProperty('mediaUploader')) {
		mediaUploader.open();
		return;
	}
	mediaUploader = wp.media.frames.file_frame = wp.media({
		title: 'Choose Image',
		button: {
			text: 'Choose Image'
		}, multiple: false
	});
	mediaUploader.on("select", onMediaImageSelect);
	mediaUploader.open();
}
function removeDropdownImage($this, idFromDataAttribute = false) {
	$this = jQuery($this);
	var imgPlaceholder = $this.prev('.scc-image-picker');
	imgPlaceholder.attr('src', df_scc_resources.dropdownTumbnailDefaultImage);
	var id_elementitem = $this.closest('.selopt3').find(".swichoptionitem_id").val();
	if (idFromDataAttribute) {
		id_elementitem = $this.closest(".dd-item-field-container").data('elementItemId')
	}
	jQuery.ajax({
		url: ajaxurl,
		cache: false,
		data: {
			action: 'sccUpElementItemSwichoption',
			id_elementitem: id_elementitem,
			image: '',
			nonce: pageEditCalculator.nonce
		},
		success: function (data) {
			if (data.passed == true) {
				showSweet(true, "The changes have been saved.")
			} else {
				showSweet(false, "There was an error, please try again")
			}
		}
	})
}
function resizeImage(url, callback) {
	var data = {
		action: "scc_handle_dropdown_logo",
		data: url
	};
	jQuery.post(ajaxurl, data, callback);
}
/**
 * On media image select
 */
function onMediaImageSelect() {
	var attachment = mediaUploader.state().get('selection').first().toJSON()
	var field = formField;
	field.attr('src', attachment.sizes.thumbnail.url);
	resizeImage(attachment.sizes.thumbnail.url, (data) => {
		var element = field.attr('src', data.link).data('hasImage', true);
		if (!element.next('span').length) element.after(jQuery('<span class="scc-dropdown-image-remove" onclick="removeDropdownImage(this)">x</span>'));
	});
}
// Functions to change the backend title while typing.
function changeElementTitleSlider($this) {
	var changeBackendTitleElement = jQuery($this).closest(".slider-section-container").prev().find(".scc-element-title")
	var currentBackendInputTitle = truncateElementTitle(jQuery($this).val(), 20)
	changeBackendTitleElement.text(currentBackendInputTitle)
}
function changeElementTitle($this) {
	var changeBackendTitleElement = jQuery($this).closest(".elements_added").find(".scc-element-title")
	var currentBackendInputTitle = truncateElementTitle(jQuery($this).val(), 20)
	changeBackendTitleElement.text(currentBackendInputTitle)
}
function changeElementTitleCustomMath($this) {
	var changeBackendTitleElement = jQuery($this).closest('.scc_custom_math').find('.scc-element-title')
	var currentBackendInputTitle = truncateElementTitle(jQuery($this).val(), 20)
	changeBackendTitleElement.text(currentBackendInputTitle)
}
function truncateElementTitle(str, n) {
	return (str.length > n) ? str.substr(0, n - 1) + '..' : str;
};
/** receives the source element and event from the HTML tag it
 * was referrenced from
 * @param {Object} element  - this is the source HTML tag from where the function is initiated
 * @param {Object} event - this is event caused by the click
 */
function doFormFieldsSetup(element, event, isPremium) {
	if (!isPremium) {
		return
	}
	return
}
function addEventsToQuoteFormBtns(elements) {
	elements.click(($this) => {
		if (jQuery($this).closest('.card-action-btns').hasClass('disabled')) {
			return
		}
		switch (jQuery($this.target).data('formBuilderActionType')) {
			case 'edit':
				console.log('edit action');
				doFormFieldsSetup($this.currentTarget, $this, true);
				break;
			case 'delete':
				console.log('delete action');
				let fieldKey = jQuery($this.currentTarget).data('fieldKey');
				if (fieldKey) delete formFieldsArray[_.findKey(formFieldsArray, fieldKey)];
				jQuery($this.currentTarget).remove();
				break;
			default:
				// jQuery($this.currentTarget).toggleClass('active');
				break;
		}
	})
}
// handles quote custom field setup form's delete button
function handleQuoteFieldDeletion($this) {
	$this = jQuery($this);
	let fieldKey = $this.data('fieldKey');
	const urlParams = new URLSearchParams(window.location.search);
	const calcId = urlParams.get('id_form');
	let data = {
		action: 'sccQuoteFieldDeletion',
		id_form: calcId,
		fieldKey: fieldKey
	}
	jQuery.ajax({
		url: ajaxurl,
		data: data,
		type: 'POST',
		context: $this,
		beforeSend: function () {
			this.find(".df-scc-euiButtonContent.df-scc-euiButtonEmpty__content").html(`<div>
			<svg aria-hidden="true" style="width: 1em" focusable="false" data-prefix="fas" data-icon="spinner" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="svg-inline--fa fa-spinner fa-w-16 fa-spin"><path fill="currentColor" d="M304 48c0 26.51-21.49 48-48 48s-48-21.49-48-48 21.49-48 48-48 48 21.49 48 48zm-48 368c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zm208-208c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.49-48-48-48zM96 256c0-26.51-21.49-48-48-48S0 229.49 0 256s21.49 48 48 48 48-21.49 48-48zm12.922 99.078c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.491-48-48-48zm294.156 0c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48c0-26.509-21.49-48-48-48zM108.922 60.922c-26.51 0-48 21.49-48 48s21.49 48 48 48 48-21.49 48-48-21.491-48-48-48z" class=""></path></svg>
			</div>
			<span class="trn df-scc-euiButtonEmpty__text">Deleting...</span>`);
		},
		success: function (data) {
			if (data.passed == true) {
				this.closest('[role=dialog]').modal('hide');
				let fieldKey = data.key;
				jQuery(`[data-field-key=${fieldKey}]`, '.editing-action-cards.action-quoteform').remove();
				showSweet(true, "The changes have been saved.")
			} else {
				showSweet(false, data.message);
			}
		}
	});
}
function paypalFormValidation() {
	var paypalEmail = jQuery("#paypal_email_form").val();
	var paypalShoppingCartName = jQuery("#paypal_shopping_cart_name_form").val();
	var paypalCurrency = "";
	jQuery.each(jQuery("#paypal_currency_form option:selected"), function () {
		paypalCurrency = jQuery(this).val();
	});
	var paypalSuccessURL = jQuery("#paypal_shopping_cart_success_url_form").val();
	var paypalCancelURL = jQuery("#paypal_shopping_cart_cancel_url_form").val();
	var paypalIncludeTax = Boolean(
		jQuery("#paypal_tax_inclusion_settings_form").prop("checked")
	);
	var noValidMessage = false;
	if (
		paypalEmail == null ||
		typeof paypalEmail == "undefined" ||
		paypalEmail.length < 5 ||
		paypalEmail.indexOf("@") == -1 ||
		paypalEmail.indexOf(".") == -1
	) {
		noValidMessage = "Invalid Email!";
		jQuery("#paypal_email_form")
			.closest(".df-scc-euiFormRow__fieldWrapper")
			.find("span.text-danger")
			.show();
	}
	if (
		paypalShoppingCartName == null ||
		typeof paypalShoppingCartName == "undefined" ||
		paypalShoppingCartName.length < 2
	) {
		noValidMessage === false
			? (noValidMessage = " | Invalid Shopping card Name!")
			: (noValidMessage += " | Invalid Shopping card Name!");
		jQuery("#paypal_shopping_cart_name_form")
			.closest(".df-scc-euiFormRow__fieldWrapper")
			.find("span.text-danger")
			.show();
	}
	if (
		paypalCurrency == null ||
		typeof paypalCurrency == "undefined" ||
		paypalCurrency == "0"
	) {
		noValidMessage === false
			? (noValidMessage = " | Select a Currency!")
			: (noValidMessage += " | Select a Currency!");
		jQuery("#paypal_currency_form")
			.closest(".df-scc-euiFormRow")
			.find("span.text-danger")
			.show();
	}
	// https://stackoverflow.com/questions/5717093/check-if-a-javascript-string-is-a-url
	var pattern = new RegExp(
		"^(https?:\\/\\/)?" + // protocol
		"((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|" + // domain name
		"((\\d{1,3}\\.){3}\\d{1,3}))" + // OR ip (v4) address
		"(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*" + // port and path
		"(\\?[;&a-z\\d%_.~+=-]*)?" + // query string
		"(\\#[-a-z\\d_]*)?$",
		"i"
	); // fragment locator
	if (paypalSuccessURL.length && !pattern.test(paypalSuccessURL)) {
		noValidMessage === false
			? (noValidMessage = " | Invalid URL!")
			: (noValidMessage += " | Invalid URL!");
		jQuery("#paypal_shopping_cart_success_url_form")
			.closest(".df-scc-euiFormRow__fieldWrapper")
			.find("span.text-danger")
			.show();
	}
	if (paypalCancelURL.length && !pattern.test(paypalCancelURL)) {
		noValidMessage === false
			? (noValidMessage = " | Invalid URL!")
			: (noValidMessage += " | Invalid URL!");
		jQuery("#paypal_shopping_cart_cancel_url_form")
			.closest(".df-scc-euiFormRow__fieldWrapper")
			.find("span.text-danger")
			.show();
	}
	if (!noValidMessage) {
		return true;
	} else {
		// jQuery('#paypal_modal_form_no_valid_fields_message').html(noValidMessage.replace('false', ''))
		return false;
	}
}
function doPaypalSetupModal(calcId) {
	return
}
// in progress code not being used
function registerWebhookActions(calcId) {
	let { webhookConfig } = sccData[calcId].config;
	webhookConfig.map(e => Object.keys(e)[0]).forEach(webhookCtx => {
		let { enabled, webhook } = webhookConfig.filter(ee => (Object.keys(ee)[0] == webhookCtx))[0][webhookCtx];
		let webhookCtxNode = jQuery(`#${webhookCtx}`);
		webhookCtxNode.prop('checked', enabled);
		let webhookCtxLink = jQuery(`[data-event-type=${webhookCtxNode.data('target')}`);
		webhookCtxLink.data('webhook', webhook);
	})
}
// handles on/ off actions of the webhook setup section
jQuery('#calc-settings-webhook input').on('click', (event) => {
	var $this = jQuery(event.target);
	var target = $this.data('target');
	var webhookEditBtn = jQuery(`[data-event-type=${target}`);
	if (webhookEditBtn.data('webhook')) {
		saveWebhookSettings();
	} else {
		event.preventDefault();
		// prompt for a webhook form
		webhookEditBtn.click();
	}
})
jQuery('#calc-settings-webhook i.material-icons:not(.disabled)').on('click', (event) => {
	let eventTitles = {
		"quote-fillup": "Quote Fillup Webhook",
		"detail-btn": "Detailed View Button Webhook",
		"payment-btn": "Payment Button Webhook"
	}
	let eventType = jQuery(event.target).data('eventType');
	let currentWebhookEndpoint = jQuery(event.target).data('webhook');
	let webhookSetupForm = wp.template('scc-webhook-setup')({
		title: eventTitles[eventType],
		webhookEndPoint: currentWebhookEndpoint
	});
	jQuery('#webhook-setup-placeholder').html(webhookSetupForm).modal('show');
	jQuery('#webhook-setup-placeholder').find('form').data('modalSource', event.target);
});
function handleWebHookSetup($this) {
	$this = jQuery($this);
	var modalSource = jQuery($this.data('modalSource'));
	var data = new FormData($this[0]);
	var webhook = data.get('webhook-link');
	modalSource.data('webhook', webhook);
	if (!webhook.length) {
		let relatedSwitchBtn = modalSource.data('eventType');
		jQuery(`[data-target=${relatedSwitchBtn}]`).prop('checked', false);
	}
	modalSource.trigger('webhookSetupDone');
	setTimeout(() => {
		saveWebhookSettings();
	}, 300);
	jQuery('#webhook-setup-placeholder').modal('hide');
}
function saveWebhookSettings() {
	const urlParams = new URLSearchParams(window.location.search);
	const calcId = urlParams.get('id_form');
	let newWebhookConfig = [
		{
			'scc_set_webhook_quote': {
				enabled: jQuery('#scc_set_webhook_quote').prop('checked'),
				webhook: jQuery('[data-event-type="quote-fillup"]').data('webhook')
			}
		},
		{
			'scc_set_webhook_detail_view': {
				enabled: jQuery('#scc_set_webhook_detail_view').prop('checked'),
				webhook: jQuery('[data-event-type="detail-btn"]').data('webhook')
			}
		}
	];
	jQuery.ajax({
		url: ajaxurl + '?action=sccSaveWebhookConfig' + '&id=' + calcId,
		contentType: 'json',
		type: 'POST',
		calcId,
		data: JSON.stringify(newWebhookConfig),
		success: () => {
			showSweet(true, "The changes have been saved.");
		}
	});
}
function stripeOptionsModal($this) {
	return
}
function setupStripeKey($this) {
	const urlParams = new URLSearchParams(window.location.search);
	const calcId = urlParams.get('id_form');
	var modalObject = jQuery($this).closest('.df-scc-euiModal');
	var privKey = jQuery('[name="stripe-api-priv-key"]').val();
	var pubKey = jQuery('[name="stripe-api-pub-key"]').val();
	if (privKey.length == 0) {
		jQuery('[name="stripe-api-priv-key"]').closest('.df-scc-euiFormControlLayout').next('.text-danger').show().hide(3000);
	}
	if (pubKey.length == 0) {
		jQuery('[name="stripe-api-pub-key"]').closest('.df-scc-euiFormControlLayout').next('.text-danger').show().hide(3000);
	}
	if (privKey.length == 0 || pubKey.length == 0) {
		return;
	}
	var keyInputVal = {
		privKey: privKey,
		pubKey: pubKey,
		enabled: true
	};
	jQuery.ajax({
		url: ajaxurl,
		type: 'POST',
		context: modalObject,
		calcId,
		data: {
			action: 'scc_set_stripe_key',
			data: keyInputVal
		},
		beforeSend: function (xhr) {
			this.find('.df-scc-euiButtonContent.df-scc-euiButton__content').html('<i class="fa fa-circle-o-notch fa-spin"></i><span class="trn df-scc-euiButton__text">Saving...</span>')
		},
		success: function (data) {
			this.find('.df-scc-euiModalFooter').hide();
			this.find('.df-scc-euiText.df-scc-euiText--medium').html("<p>The Stripe API key has been saved. You can change the Stripe API key from the 'Global Settings' menu</p>")
			jQuery('#stripe_checkbox').prop('checked', true);
			var sourceBtn = jQuery('.editing-action-cards.action-payment [data-btn-type="stripe"]');
			var hasStripeKeys = null;
			if (sourceBtn && privKey.length && pubKey.length) {
				jQuery(sourceBtn)
					.addClass('active')
					.attr('onclick', 'toggleStripe(this)')
					.attr('data-pub-key', pubKey)
					.attr('data-priv-key', privKey);
				hasStripeKeys = true;
			}
			hasStripeKeys && loadPreviewForm(this.calcId);
			// close the success notice
			setTimeout(() => {
				this.find('.df-scc-euiButtonIcon.df-scc-euiButtonIcon--text.df-scc-euiModal__closeIcon').click();
			}, 5000);
		},
		error: function (error) {
			console.log(error);
			this.find('.text-danger').show();
			setTimeout(() => {
				this.find('.text-danger').hide();
			}, 3000);
		}
	});
}
function toggleStripe($this) {
	const urlParams = new URLSearchParams(window.location.search);
	const calcId = urlParams.get('id_form')
	$this = jQuery($this);
	let privKey = $this.data('privKey');
	let pubKey = $this.data('pubKey');
	let newStatus = !$this.hasClass('active');
	let keyInputVal = {
		privKey: privKey,
		pubKey: pubKey,
		enabled: newStatus,
		calcId
	};
	jQuery.ajax({
		url: ajaxurl,
		type: 'POST',
		context: $this,
		calcId,
		data: {
			action: 'scc_set_stripe_key',
			data: keyInputVal
		},
		success: function (data) {
			showSweet(true, "The changes have been saved.");
			newStatus ? this.addClass('active') : this.removeClass('active');
		}
	})
}
function setForceQuoteFormStatus($this, event) {
}
function toggleFormBuilderOnDetails(element) {
	return
}
function setWoocommerceCheckoutStatus(status) {
}
function attachProductId($this, id = null, elementType = null) {
	return
}
function attachSliderProductId($this) {
	$this = jQuery($this);
	let target = $this.data('target')
	let id_elementitems = $this.closest('.' + target).find(".swichoptionitem_id").val();
	let woocomerce_product_id = $this.val();
	if (woocomerce_product_id) {
		jQuery.ajax({
			url: ajaxurl,
			data: {
				action: 'sccUpElementItemSwichoption',
				id_elementitems,
				woocomerce_product_id
			},
			success: function (datajson) {
				if (datajson.passed == true) {
					showSweet(true, "The changes have been saved.")
				} else {
					showSweet(false, datajson.msj)
				}
			}
		});
	}
}
function sccSaveRecaptchaKeys() {
	var sccPDFFont = jQuery('#pdf_font').children("option:selected").val()
	var recaptchaKeys = jQuery('#recaptcha').find('input').serializeArray();
	$fragment_refresh = {
		url: ajaxurl,
		type: 'POST',
		data: {
			action: 'sccSaveRecaptchaKeys',
			recaptchakeys: JSON.stringify(recaptchaKeys)
		},
		success: function (data) {
			showSweet(true, 'Saved successfully.')
		},
		error: function (err) {
		}
	};
	jQuery.ajax($fragment_refresh);
}
function updateStripeKey() {
	var keyInputVal = {
		privKey: jQuery('[name="stripe-api-priv-key"]').val(),
		pubKey: jQuery('[name="stripe-api-pub-key"]').val(),
		enabled: jQuery('[name="is-stripe-enabled"]').prop('checked')
	};
	jQuery.ajax({
		url: ajaxurl,
		type: 'POST',
		data: {
			action: 'scc_set_stripe_key',
			data: keyInputVal
		},
		success: function (data) {
			showSweet(true, 'Saved Successfully!')
		}
	});
}
function changeShowSectionTotalOnPdf(element) {
	var id = jQuery(element).parentsUntil(".addedFieldsStyle").parent().find(".id_section_class").val();
	var show = jQuery(element).is(":checked");
	jQuery.ajax({
		url: ajaxurl,
		cache: false,
		data: {
			action: 'sccUpSection',
			id_section: id,
			showSectionTotalOnPdf: show,
			nonce: pageEditCalculator.nonce
		},
		success: function (data) {
			var datajson = JSON.parse(data)
			if (datajson.passed == true) {
				showSweet(true, "The changes have been saved.")
			} else {
				showSweet(false, "There was an error, please try again.")
			}
		}
	})
}
function truncateElementTitle(str, n) {
	return (str.length > n) ? str.substr(0, n - 1) + '..' : str;
};
function settings_scc_() {
	let menuclass = document.querySelector('.scc-edit-nav-items-2')
	menuclass.prepend(settings_el())

}
function settings_el() {
	let u = document.querySelector('.scc-footer.logo').getAttribute('href') + '?utm_source=inside-plugin&utm_medium=wordpress&utm_content=buy-premium-cta-banner'
	let cont = document.createElement('li')
	let p = document.createElement('span')
	p.classList.add('free_version')
	p.innerHTML = ''
	let s1 = document.createElement('a')
	s1.classList.add('highlighted')
	s1.classList.add('scc-nav-with-icons')
	s1.innerHTML = '<i class="far fa-gem"></i>Buy Premium'
	s1.setAttribute('href', u)
	s1.setAttribute('target', '_blank')
	p.appendChild(s1)
	p.innerHTML += ''
	cont.appendChild(p)
	return cont
}
settings_scc_()
document.querySelectorAll('[id^=scc_calculator_]').forEach(element => {
	element.querySelectorAll('a').forEach(element => {
		let inner = element.innerHTML
		if (inner == 'Duplicate' || inner == 'Export' || inner == 'URLs') {
			element.style.boxShadow = 'none'
			element.classList.add('use-premium-tooltip')
		}
	});
})
function disableGlobalSettingsSection(arr) {
	let param = new URLSearchParams(window.location.search)
	if (param.get('page') != 'scc-global-settings') return
	// set tooltips at the right
	document.querySelectorAll('.mb-3.row[title]').forEach(e => new bootstrap.Tooltip(e, { placement: 'right', delay: { show: 600, hide: 300 }, }))
	let couponLink = document.querySelector('#coupon-page');
	couponLink.setAttribute('href', 'javascript:void(0)');
	couponLink.setAttribute('title', needLicenseKeyTooltip);
	new bootstrap.Tooltip(couponLink, { placement: 'right', delay: { show: 600, hide: 300 } })

	let style = {
		"background-color": "rgba(0,0,0,0.28)",
		"position": "absolute",
		"width": "100%",
		"height": "100%",
		"top": "0",
		"left": "0",
		"right": "0",
		"bottom": "0",
		"z-index": "100",
		"display": "flex",
		"align-items": "center",
		"justify-content": "end",
		"backdrop-filter": "blur(1px)",
		"padding-right": "30px"
	}
	let u = document.querySelector('.scc-footer.logo').getAttribute('href') + '?utm_source=inside-plugin&utm_medium=wordpress&utm_content=buy-premium-cta-banner'
	arr.forEach(e => {
		var cont = document.querySelectorAll('.accordion-body')[e]
		cont.style.position = 'relative'
		let frag = document.createDocumentFragment()
		let content = document.createElement('div')
		let div = document.createElement('div')
		Object.assign(content.style, style)
		let text = document.createElement('h5')
		text.style.color = '#000'
		text.style.textAlign = 'center'
		text.style.maxWidth = '200px'
		text.style.marginBottom = '40px'
		text.style.fontWeight = '700'
		text.innerText = 'Upgrade to unlock this setting'
		let link_cont = document.createElement('center')
		let link = document.createElement('a')
		link.classList.add('scc-a-over-blue')
		link_cont.appendChild(link)
		link.setAttribute('target', '_blank')
		link.setAttribute('href', u)
		link.innerText = 'Buy Premium'
		link.innerHTML = '<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-lock"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect><path d="M7 11V7a5 5 0 0 1 10 0v4"></path></svg> <span style="margin-left:5px">Buy Premium</span>'
		div.appendChild(text)
		div.appendChild(link_cont)
		content.appendChild(div)
		frag.appendChild(content)
		cont.appendChild(frag)
	});
}
disableGlobalSettingsSection([1, 2, 3, 4, 6, 7, 8])
document.querySelectorAll('.add-element-btn.save_button').forEach(element => {
	element.addEventListener('click', function () {
		element.closest('.boardOption').querySelectorAll('.scc_button.btn-backend').forEach(e => {
			unabled(e)
		})
	})
})

function unabled(e) {
	let premiumClass = e.classList.contains('scc-premium-element');
	let o = e.innerText.trim()
	if (o == 'File Upload' || o == 'Custom Math' || o == 'Image Button' || o == 'Text/HTML Field' || o == 'Variable Math' || o == 'Date Picker' || o == 'Distance-Based Cost') {
		let p = ''
		let tooltipImageUrl = ''
		switch (o) {
			case 'File Upload':
				p = 'file-upload'
				break
			case 'Custom Math':
				p = 'https://designful.freshdesk.com/a/solutions/articles/48001142309'
				break
			case 'Image Button':
				p = 'https://designful.freshdesk.com/a/solutions/articles/48001207056'
				break
			case 'Text/HTML Field':
				p = 'https://designful.freshdesk.com/a/solutions/articles/48001207057'
				break
			case 'Variable Math':
				p = 'https://designful.freshdesk.com/a/solutions/articles/48001202286'
				break
			case 'Date Picker':
				p = 'https://designful.freshdesk.com/a/solutions/articles/48001216557'
				break
			case 'Distance-Based Cost':
				p = 'https://designful.freshdesk.com/a/solutions/articles/48001244300'
				break
				
		}
		let inner = ['File Upload', 'Custom Math', 'Variable Math', 'Image Button', 'Text/HTML Field', 'Date Picker', 'Distance-Based Cost'].includes(o) ? 'More' : ''
		e.classList.add('premium-tooltips2', 'ed-btn-disabled');

		e.removeAttribute('onclick')
		if (premiumClass) {
			let title = `You need to purchase a premium license to use this feature. <a target="_blank" href="${p}">${inner}</a>`;
			if (tooltipImageUrl.length > 0) {
				title = `<p class="mt-3">${title}</p>` + '<img class="mx-3" src=\'' + tooltipImageUrl + '\'/>';
			}
			e.removeAttribute('onclick')
			jQuery(e).tooltip({
				placement: 'right',
				html: true,
				title,
				customClass: tooltipImageUrl.length ? 'tooltip-img-dark' : '',
				delay: { show: 0, hide: 100 }
			})
		}

	}
}


function toolPrem() {
	let ooo = function (e) {
		let p = ''
		let inner = ''
		e.classList.add('premium-tooltips2')
		e.setAttribute('title', `You need to purchase a <a href="https://stylishcostcalculator.com/">premium license</a> to use this feature. <a target="_blank" href="${p}">${inner}</a>`)
		e.removeAttribute('onclick')
		jQuery(e).tooltip({
			placement: 'right',
			html: true,
			delay: { show: 0, hide: 100 }
		})
	}
	document.querySelectorAll('.tool-premium').forEach(function (e) {
		ooo(e)
	})

}

// tooltips
jQuery('.material-icons-outlined.with-tooltip')
	.add('.btnn.material-icons')
	.add('.scc_accordion_conditional.with-tooltip')
	.add('.use-premium-tooltip, .use-tooltip')
	.each((index, element) => {
		new bootstrap.Tooltip(element, {
			delay: { show: 600, hide: 300 },
			trigger: 'hover focus',
			html: true,
			placement: 'right',
		})
	})

function handleDiagRemove($this) {
	let ignoredMsgKey = $this.getAttribute('data-diag-key');
	jQuery.ajax({
		url: ajaxurl,
		cache: false,
		data: {
			action: 'scc_get_debug_items',
			nonce: pageEditCalculator.nonce,
			method: 'set_ignore',
			value: ignoredMsgKey
		},
		success: function (data) {
			$this.closest('.alert.alert-warning').remove();
		}
	})
}

document.querySelectorAll('#close-btn').forEach(element => {
	element.addEventListener('click', function () {
		if (this.innerText != '-') return
		element.closest('.elements_added').querySelector('.first-conditional-step')?.setAttribute('disabled', 'true')
		let first = element.closest('.elements_added').querySelector('.first-conditional-step')
		let second = element.closest('.elements_added').querySelector('.second-conditional-step')
		element.closest('.elements_added').querySelector('.item_conditionals').querySelectorAll('button').forEach(e => {
			e.removeAttribute('onclick')
		})
		let cond = element.closest('.elements_added').querySelector('.item_conditionals')
		cond.style.display = 'inline'
		cond.classList.add('use-premium-tooltip')
		// cond.setAttribute('data-tooltip','You need to purchase a premium license to use this feature.')
		first.removeAttribute('onchange')
		first.removeAttribute('onfocus')
		second.removeAttribute('onchange')
	})
})
let p = document.querySelectorAll('#paybuttonhovereffect,#scc_send_quote,#turn_off_coupon,#scc_remove_total_price_frntd,#scc_remove_detailed_list_title,#scc_no_unit_col,#scc_no_qty_col,#scc_save_icon,#turn_off_tax,#scc_show_taxvat,#scc_set_webhook_quote,#scc_set_webhook_detail_view,#show_invoice_number,#scc_frontend_allow_currency_switching').forEach(element => {
	element.setAttribute('disabled', 'true')
	if (element.getAttribute('id') == 'turn_off_coupon' || element.getAttribute('id') == 'scc_detailed_list' || element.getAttribute('id') == 'scc_send_quote') element.setAttribute('checked', 'true')
	if (element.getAttribute('id') == 'scc_show_taxvat') element.removeAttribute('checked')
	if (element.nextElementSibling) element.nextElementSibling.style.backgroundColor = 'rgba(211, 211, 211, 0.4)'
	if (element.nextElementSibling) element.nextElementSibling.style.cursor = 'not-allowed'
	element.closest('.scc-switch')?.classList.add('use-premium-tooltip')
});
document.querySelectorAll('[name=scc_wc_cart_btn_action],[name=email_quote_recipients]').forEach(element => {
	element.setAttribute('disabled', 'true')
	element.parentElement.classList.add('use-premium-tooltip')
	element.style.cursor = 'not-allowed'
})
document.querySelectorAll('#scc_minimum-total,#scc_tax_amount').forEach(element => {
	if (element.getAttribute('id') == 'scc_tax_amount') element.setAttribute('value', '0')
	element.setAttribute('disabled', 'true')
	element.parentElement.classList.add('use-premium-tooltip')
})
document.querySelectorAll('#label_footern,#label_autocurr,#label_pdffont,#label_pdfdate,#label_emailbody').forEach(element => {
	element.closest('.scc-vcenter').querySelector('a').classList.add('use-premium-tooltip')
})
/**
 * 
 * @param {The button DOM object} btn 
 * @param {The onClick event} event 
 */
function handleFeedbackButtons(btn, event) {
	event.preventDefault();
	jQuery.post(ajaxurl, {
		'action': 'scc_feedback_manage',
		'btn-type': jQuery(btn).data('btnType'),
		'nonce': pageEditCalculator.nonce
	}, function (response) {
		document.querySelector('#user-scc-sv').classList.remove('d-block');
		document.querySelector('#user-scc-sv').classList.add('fade', 'd-none');
		var link = jQuery(btn).attr('href');
		if (link) {
			window.open(link, '_blank');
		}
	});
}

function sccSkipFeedbackModal() {
	jQuery.post(ajaxurl, {
		'action': 'scc_feedback_manage',
		'btn-type': 'skip',
		'nonce': pageEditCalculator.nonce
	}, function (response) {
		const searchParams = new URLSearchParams(window.location.search);
		const launchTour = ( searchParams.get( 'page' ) === 'scc_edit_items' && searchParams.has('new') ) ? true : false ;
		// launch the tour if the user is in the new item page and has skipped the satisfaction survey
		if( launchTour ){
			sccBackendUtils.knowingEditingPageGuidedTour( 'scc-introjs-new-editing-page' );
		}
		document.querySelector('#user-scc-sv').classList.remove('d-block');
		document.querySelector('#user-scc-sv').classList.add('fade', 'd-none');
	})
}

function processTooltipContent(elementType) {
	if (typeof (elementTooltips[elementType]) == 'string') {
		return elementTooltips[elementType];
	}
	if (typeof (elementTooltips[elementType]) == 'object') {
		return elementTooltips[elementType].msg;
	}
	return elementType;
}

function processSettingTooltipContent(settingType) {
	if (typeof (settingTooltips[settingType]) == 'string') {
		return settingTooltips[settingType];
	}
	if (typeof (settingTooltips[settingType]) == 'object') {
		return settingTooltips[settingType].msg;
	}
	return settingType;
}

//Deprecated function?
/* function getTooltipCoverImage(elementType) {
	if (typeof (elementTooltips[elementType]) == 'object') {
		return df_scc_resources.assetsPath + '/' + elementTooltips[elementType].coverImage;
	}
	return 'https://picsum.photos/200/100';
} */

function getTooltipCoverImage(elementType) {
	if (typeof elementTooltips[elementType]?.coverImage !== "undefined") {
	  return (
		df_scc_resources.assetsPath +
		"/" +
		elementTooltips[elementType].coverImage
	  );
	}

	return null;
  }

function getSettingTooltipCoverImage(elementType) {
	if (typeof settingTooltips[elementType]?.coverImage !== "undefined") {
		return (
		  df_scc_resources.assetsPath +
		  "/" +
		  settingTooltips[elementType].coverImage
		);
	  }
	  return null;
  }

//Element tooltip callback
function applyElementTooltip(node) {
	let elementType = node.getAttribute('data-element-tooltip-type');
	let coverImage = getTooltipCoverImage(elementType);
	let imgCard = "";
	if(coverImage){
		imgCard = `<div class="bg-dark pt-1"><img src="${coverImage}" class="card-img-top bg-dark rounded-0" alt="..."></div>`;
	}
	new bootstrap.Tooltip(node, {
		delay: { show: 600, hide: 300 },
		trigger: 'hover focus',
		template: `<div class="tooltip opacity-100 bg-dark" role="tooltip">
		<div class="tooltip-arrow"></div>
		<div class="card tooltip-element">
		${imgCard}
		<div class="card-body bg-dark tooltip-inner rounded-0 border-0">
		</div>
		</div>
	  </div>`,
		title: processTooltipContent(elementType),
		html: true,
		placement: 'bottom',
	})
}
//Setting tooltip callback
function applySettingTooltip(node) {
	let settingType = node.getAttribute('data-setting-tooltip-type');
	let coverImage = getSettingTooltipCoverImage(settingType);
	let imgCard = "";
	if(coverImage){
		imgCard = `<div class="bg-dark pt-1"><img src="${coverImage}" class="card-img-top bg-dark rounded-0" alt="..."></div>`;
	}
	new bootstrap.Tooltip(node, {
		delay: { show: 600, hide: 300 },
		trigger: 'hover focus',
		template: `<div class="tooltip opacity-100 bg-dark backed-tooltip" role="tooltip">
		<div class="tooltip-arrow"></div>
		<div class="card tooltip-element">
		${imgCard}
		<div class="card-body bg-dark tooltip-inner rounded-0 border-0">
		</div>
		</div>
	  </div>`,
		title: processSettingTooltipContent(settingType),
		html: true,
		placement: 'right',
	})
}

function preDeletionDialog(type, callbackFn, ...cbArg) {
	Swal.fire({
		title: `Do you want to remove this ${type}?`,
		showDenyButton: true,
		showCancelButton: false,
		confirmButtonText: 'Yes, remove',
		denyButtonText: `No, keep it`,
	}).then((result) => {
		if (result.isConfirmed) {
			callbackFn(cbArg)
		} else if (result.isDenied) {
		}
	})
	return 0;
}
jQuery(document).ready(function () {
	toolPrem()
	//keeps tooltip on mouseover
	var cx, cy, tip, waiting;
	var old_hide = bootstrap.Tooltip.prototype.hide

	var isOutside = function () {
		return ((cx < tip.left || cx > tip.left + tip.width) || (cy < tip.top || cy > tip.top + tip.height))
	}


	document.addEventListener('mousemove', function (e) {
		cx = e.clientX
		cy = e.clientY
		if (waiting && isOutside()) {
			waiting.f.call(waiting.context)
			waiting = null
		}
	})

	bootstrap.Tooltip.prototype.hide = function (args) {
		tip = this.getTipElement().getBoundingClientRect()
		if (isOutside()) {
			old_hide.call(this)
		} else {
			waiting = { f: old_hide, context: this }
		}
	}

	// tooltip for editing page
	jQuery('.material-icons-outlined.with-tooltip').add('.btnn.material-icons').add('.scc_accordion_conditional.with-tooltip').each((index, element) => {
		new bootstrap.Tooltip(element, {
			delay: { show: 600, hide: 300 },
			trigger: 'hover focus',
			html: true,
			placement: 'right'
		})
	})
	document.querySelectorAll('.use-premium-tooltip').forEach(node => {
		let tooltipImageUrl = node.getAttribute('data-tooltip-image');
		let tooltipStr = needLicenseKeyTooltip;
		if (tooltipImageUrl) {
			tooltipStr = `<p class="mt-3">${needLicenseKeyTooltip}</p>` + '<img class="mx-3" src=\'' + tooltipImageUrl + '\'/>';
		}
		new bootstrap.Tooltip(node, {
			delay: { show: 600, hide: 300 },
			trigger: 'hover focus',
			html: true,
			title: tooltipStr,
			placement: 'right',
			customClass: tooltipImageUrl ? 'tooltip-img-dark' : ''
		})
	})
	// tooltip for elements
	document.querySelectorAll('[data-element-tooltip-type]').forEach((node) => {
		applyElementTooltip(node)
	})

	// tooltip for settings
	document.querySelectorAll('[data-setting-tooltip-type]').forEach((node) => {
		applySettingTooltip(node)
	})

	// tooltips at the bottom
	document.querySelectorAll('.use-tooltip-child-nodes').forEach(e => {
		let tooltipImageUrl = '';
		let tooltipStr = needLicenseKeyTooltip;
		let relevantNodes = [...e.childNodes].filter(e => e.nodeName !== '#text' && e.nodeName !== 'DIV');
		if (e.getAttribute('data-tooltip-image')) {
			tooltipImageUrl = e.getAttribute('data-tooltip-image');
		}
		if (e.classList.contains('scc_accordion_conditional')) {
			tooltipImageUrl = df_scc_resources.assetsPath + '/images/tooltip-images/infographic-feat-conditional-logic.png';
		}
		relevantNodes.forEach(ee => {
			ee.removeAttribute('disabled')
			ee.removeAttribute('onclick')
			ee.classList.remove('disabled')
			if (ee.getAttribute('data-btn-type') == 'paypal') {
				tooltipImageUrl = df_scc_resources.assetsPath + '/images/tooltip-images/infographic-pay-paypal.png';
			}
			if (tooltipImageUrl.length) {
				tooltipStr = `<p class="mt-3">${needLicenseKeyTooltip}</p>` + '<img class="mx-3" src=\'' + tooltipImageUrl + '\'/>';
			}
			new bootstrap.Tooltip(ee, {
				delay: { show: 600, hide: 300 },
				trigger: 'hover focus',
				html: true,
				title: tooltipStr,
				placement: 'right',
				customClass: tooltipImageUrl.length ? 'tooltip-img-dark' : '',
			})
		})
	})

	const notificationContainer = document.getElementById('scc-notifications');
	if (notificationContainer) {
		let notficationMessagesWrapper = notificationContainer.querySelector('.notification-message-wrapper');
		let notficationMessageNodes = notficationMessagesWrapper.children;
		let nextBtn = notificationContainer.querySelector('.next');
		let prevBtn = notificationContainer.querySelector('.prev');
		let dismissBtn = notificationContainer.querySelector('.scc-dismiss');
		dismissBtn.addEventListener('click', function (event) {
			let messageNode = notificationContainer.querySelector('.scc-notifications-message.current');
			let messageId = messageNode.getAttribute('data-message-id');
			// if here are only one notification left, remove the notification box
			if (notficationMessagesWrapper.childElementCount <= 1) {
				notificationContainer.parentElement.remove();
			}
			if (notficationMessagesWrapper.childElementCount > 1) {
				// if notifications are more than 1, activate the next notification item, and remove the current message node
				let isLastMessageNode = notficationMessageNodes[notficationMessagesWrapper.childElementCount - 1] == messageNode;
				if (isLastMessageNode) {
					messageNode.previousElementSibling.classList.add('current');
				} else {
					messageNode.nextElementSibling.classList.add('current');
				}
				messageNode.remove();
			}
			// if last message, hide the previous, next navigation buttons
			if (notficationMessagesWrapper.childElementCount == 1 && document.body.contains(notficationMessagesWrapper)) {
				prevBtn.remove();
				nextBtn.remove();
			}
			var data = {
				action: 'scc_notification_dismiss',
				nonce: notificationsNonce.nonce,
				id: messageId,
			};
			jQuery.post(wp.ajax.settings.url, data, function (res) {

				if (!res.success) {
					console.log(res);
				}
			}).fail(function (xhr, textStatus, e) {
				console.log(xhr.responseText);
			});
		})
		nextBtn && nextBtn.addEventListener('click', function (event) {
			if (event.currentTarget.classList.contains('disabled')) return;
			for (let index = 0; index < notficationMessageNodes.length; index++) {
				let msgNode = notficationMessageNodes[index];
				if (msgNode.classList.contains('current')) {
					msgNode.classList.remove('current');
					msgNode.nextElementSibling.classList.add('current');
					if (!msgNode.nextElementSibling.nextElementSibling) {
						nextBtn.classList.add('disabled');
					}
					if (msgNode.nextElementSibling.previousElementSibling) {
						prevBtn.classList.remove('disabled');
					}
					return;
				}
			}
		})
		prevBtn && prevBtn.addEventListener('click', function (event) {
			if (event.currentTarget.classList.contains('disabled')) return;
			for (let index = 0; index < notficationMessageNodes.length; index++) {
				let msgNode = notficationMessageNodes[index];
				if (msgNode.classList.contains('current')) {
					msgNode.classList.remove('current');
					msgNode.previousElementSibling.classList.add('current');
					if (!msgNode.previousElementSibling.previousElementSibling) {
						prevBtn.classList.add('disabled');
					}
					if (msgNode.previousElementSibling.nextElementSibling) {
						nextBtn.classList.remove('disabled');
					}
					return;
				}
			}
		})
	}
})

function skipSGOptimWarning($this) {
	jQuery.ajax({
	  url: ajaxurl,
	  cache: false,
	  data: {
		action: "scc_get_debug_items",
		nonce: pageEditCalculator.nonce,
		method: "skip_sg_optim_warning",
	  },
	  success: function () {
		$this.closest(".alert.alert-danger").remove();
	  },
	  error: function (data) {
		console.error(data);
	  }
	});
  }

document.querySelectorAll(".editing-action-cards .mb-0").forEach((node) => {
	let targetNode = node.parentElement;
	targetNode.addEventListener("click", (evt) => {
		let cardWrapper = targetNode.closest(".editing-action-cards");
		let icon = cardWrapper.querySelector("i.material-icons");
		let actionsWrapper = cardWrapper.querySelector(".card-action-btns");
		if (icon.textContent == "keyboard_arrow_right") {
			icon.textContent = "keyboard_arrow_down";
			actionsWrapper.classList.remove("d-none");
		} else {
			icon.textContent = "keyboard_arrow_right";
			actionsWrapper.classList.add("d-none");
		}
	});
});

function sccToggleMenuDropdown( button ) {
	let dropdownContent = button.closest( '.scc-menu-dropdown' ).querySelector( '.scc-menu-dropdown-content' );
	if ( dropdownContent ) {
		if ( dropdownContent.classList.contains( "scc-hidden" ) ) {
			dropdownContent.classList.remove( "scc-hidden" );
			dropdownContent.classList.add( "scc-d-block" );
  
			let outsideClickListener = function( event ) {
			  // Check if the click was outside the dropdown
			  if ( !button.contains( event.target ) && event.target !== button ) {
				  // Hide the dropdown
				  dropdownContent.classList.add( "scc-hidden" );
				  dropdownContent.classList.remove( "scc-d-block" );
				  // Remove the event listener to avoid multiple listeners
				  document.removeEventListener( 'click', outsideClickListener );
			  }
		  };
		  // Add an event listener to the document to detect clicks outside the dropdown
		  document.addEventListener( 'click', outsideClickListener );
		} else {
			dropdownContent.classList.add( "scc-hidden" );
			dropdownContent.classList.remove( "scc-d-block" );
		}
	}
}

//Loading Symbol during adding an element
function showLoadingChanges() {
	let timerInterval
	Swal.fire({
		showConfirmButton: false,
		timer: 75000,
		backdrop: true,
		customClass: {
			loader: 'custom-loader scc-smiling-loader',
			popup: 'scc-transparent',
		},
		loaderHtml: `<svg role="img" aria-label="Mouth and eyes come from 9:00 and rotate clockwise into position, right eye blinks, then all parts rotate and merge into 3:00" class="smiley" viewBox="0 0 128 128" width="128px" height="128px">
	<defs>
		<clipPath id="smiley-eyes">
			<circle class="smiley__eye1" cx="64" cy="64" r="8" transform="rotate(-40,64,64) translate(0,-56)" />
			<circle class="smiley__eye2" cx="64" cy="64" r="8" transform="rotate(40,64,64) translate(0,-56)" />
		</clipPath>
		<linearGradient id="smiley-grad" x1="0" y1="0" x2="0" y2="1">
			<stop offset="0%" stop-color="#000" />
			<stop offset="100%" stop-color="#fff" />
		</linearGradient>
		<mask id="smiley-mask">
			<rect x="0" y="0" width="128" height="128" fill="url(#smiley-grad)" />
		</mask>
	</defs>
	<g stroke-linecap="round" stroke-width="12" stroke-dasharray="175.93 351.86">
		<g>
			<rect fill="hsl(193,90%,50%)" width="128" height="64" clip-path="url(#smiley-eyes)" />
			<g fill="none" stroke="hsl(193,90%,50%)">
				<circle class="smiley__mouth1" cx="64" cy="64" r="56" transform="rotate(180,64,64)" />
				<circle class="smiley__mouth2" cx="64" cy="64" r="56" transform="rotate(0,64,64)" />
			</g>
		</g>
		<g mask="url(#smiley-mask)">
			<rect fill="hsl(223,90%,50%)" width="128" height="64" clip-path="url(#smiley-eyes)" />
			<g fill="none" stroke="hsl(223,90%,50%)">
				<circle class="smiley__mouth1" cx="64" cy="64" r="56" transform="rotate(180,64,64)" />
				<circle class="smiley__mouth2" cx="64" cy="64" r="56" transform="rotate(0,64,64)" />
			</g>
		</g>
	</g>
</svg>`,
		didOpen: (modal) => {
			Swal.showLoading()
		},
		willClose: () => {
			clearInterval(timerInterval)
		}
	})
}
