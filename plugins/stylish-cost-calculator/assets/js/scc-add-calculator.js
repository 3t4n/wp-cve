
let svgCollection = [];
window.addEventListener( 'DOMContentLoaded', ( event ) => {
	// removing WordPress's forms.css file, for
	// document.getElementById('forms-css')?.remove();
	svgCollection = JSON.parse( document.getElementById( 'svgCollection' )?.textContent || '[]' );
	// parsing query parameters
	const urlParams = new URLSearchParams( window.location.search );
	if ( urlParams.has( 'open-wizard' ) ) {
		document.querySelector( '[data-btn-action="startQuiz"]' ).click();
	}
} );
function filterResultPageSuggestions( data ) {
	const chosenOptions = Object.values( quizAnswersStore )
		.map( ( quizStep ) => Object.entries( quizStep ) )
		.flat().filter( ( answerKV ) => answerKV[ 1 ] )
		.map( ( pickedChoice ) => pickedChoice[ 0 ] );
	const suggestionsAvailable = getFeaturesAndElementsByOptions( chosenOptions );

	const features = data.allFeatureSuggestions.filter( ( feature ) => suggestionsAvailable.features.includes( feature.key ) );
	const elements = data.allElementSuggestions.filter( ( element ) => suggestionsAvailable.elements.includes( element.key ) );

	// preparing the result modal data after the filtering

	data.choices = features;
	data.elementSuggestions = elements;
	data.elementsByChoice = suggestionsAvailable.elementsByChoice;
	data.featuresByChoice = suggestionsAvailable.featuresByChoice;

	return data;
	// return features.filter(feature => featsAvailable.includes(feature.key));
}

function sscUploadSccBackup() {
	// showLoadingChanges()
	const files = jQuery( '#restoration-file' )[ 0 ].files[ 0 ];
	if ( ! files ) {
		console.log( 'no hay archivo ' );
		return;
	}
	const reader = new FileReader();
	reader.readAsText( files, 'UTF-8' );
	reader.onload = function( ee ) {
		const json = JSON.parse( ee.target.result );
		const o = ( 'scc_form' in json );
		const fdata = new FormData();
		fdata.append( 'file', files );
		fdata.append( 'action', 'sccRestoreBackup' );
		fdata.append( 'nonce', pageAddCalculator.nonce );
		jQuery.ajax( {
			type: 'POST',
			url: ajaxurl + '?pageType=add_new',
			data: fdata,
			contentType: false,
			processData: false,
			success( data ) {
				if ( data.passed ) {
					showSweet( true, 'SCC restored successfully.' );
					window.location = '/admin.php?page=scc_edit_items' + '&id_form=' + data.newCalcId;
				} else {
					showSweet( false, data.msj );
				}
			},
		} );
	};
}

// function filterResultPageElementSuggestion(elements) {
// 	return elements;
// }
// https://dirask.com/posts/JavaScript-UUID-function-in-Vanilla-JS-1X9kgD
const UUIDv4 = new function() {
	const generateNumber = ( limit ) => {
		const value = limit * Math.random();
		return value | 0;
	};
	const generateX = () => {
		const value = generateNumber( 16 );
		return value.toString( 16 );
	};
	const generateXes = ( count ) => {
		let result = '';
		for ( let i = 0; i < count; ++i ) {
			result += generateX();
		}
		return result;
	};
	const generateVariant = () => {
		const value = generateNumber( 16 );
		const variant = ( value & 0x3 ) | 0x8;
		return variant.toString( 16 );
	};
	// UUID v4
	//
	//   varsion: M=4
	//   variant: N
	//   pattern: xxxxxxxx-xxxx-Mxxx-Nxxx-xxxxxxxxxxxx
	//
	this.generate = function() {
		const result = generateXes( 8 ) +
            '-' + generateXes( 4 ) +
            '-' + '4' + generateXes( 3 ) +
            '-' + generateVariant() + generateXes( 3 ) +
            '-' + generateXes( 12 );
		return result;
	};
};

const getFeaturesAndElementsByOptions = ( optionsChosen ) => {
	const features = [];
	const elements = [];
	const elementsByChoice = {};
	const featuresByChoice = {};
	const elementsByStep = {};
	const featuresByStep = {};
	optionsChosen.forEach( ( optKey, index ) => {
		if ( ! featuresByChoice[ optKey ] ) {
			featuresByChoice[ optKey ] = [];
		}
		if ( ! elementsByChoice[ optKey ] ) {
			elementsByChoice[ optKey ] = [];
		}
		const suggestionForChoice = choicesSuggestionMap.find( ( suggestion ) => suggestion.choiceKey == optKey );
		if ( suggestionForChoice ) {
			suggestionForChoice.feats.forEach( ( feature ) => {
				features.push( feature );
				const category = suggestionsByStep[ findChoiceCategory( optKey, choicesByStepNames ) ];
				if ( category ) {
					// push the element to category if not already present
					if ( ! category[ feature ] ) {
						category.push( feature );
					}
				}
				featuresByChoice[ optKey ].push( feature );
			} );
			suggestionForChoice.elements.forEach( ( element ) => {
				elements.push( element );
				const category = suggestionsByStep[ findChoiceCategory( optKey, choicesByStepNames ) ];
				if ( category ) {
					// push the element to category if not already present
					if ( ! category[ element ] ) {
						category.push( element );
					}
				}
				elementsByChoice[ optKey ].push( element );
			} );
		}
	} );

	// filtering out empty elements in elementsByChoice and featuresByChoice
	Object.keys( elementsByChoice ).forEach( ( choiceKey ) => {
		if ( elementsByChoice[ choiceKey ].length == 0 ) {
			delete elementsByChoice[ choiceKey ];
		}
	} );
	Object.keys( featuresByChoice ).forEach( ( choiceKey ) => {
		if ( featuresByChoice[ choiceKey ].length == 0 ) {
			delete featuresByChoice[ choiceKey ];
		}
	} );

	return { features, elements, elementsByChoice, featuresByChoice };
};

function findChoiceCategory(term, obj) {

    for ( const key in obj ) {
		if ( obj[ key ].includes( term ) ) {
			return key;
		}
	}
	return null; // Return null if the term isn't found in any category
}


const choicesData = JSON.parse( document.querySelector( '#choices-data' )?.textContent || '[]' );
const choicesBySteps = Object.keys( choicesData ).filter( ( z ) => z.startsWith( 'step' ) && z !== 'stepResult' && z !== 'step1' ).map( ( x ) => choicesData[ x ].map( ( q ) => q.key ) );
const choicesByStepNames = {};
choicesByStepNames[ 'Pricing Structure' ] = choicesBySteps[ 0 ];
choicesByStepNames[ 'Use Cases' ] = choicesBySteps[ 1 ];
choicesByStepNames[ 'Unique Needs' ] = [ ...choicesBySteps[ 2 ], ...choicesBySteps[ 3 ] ];
const suggestionsByStep = {
	'Unique Needs': [],
	'Use Cases': [],
	'Pricing Structure': [],
};
const step2results = [ {
	choiceKey: 'straight-forward',
	feats: [],
	elements: [ 'dropdown-element', 'simple-buttons-element', 'slider-element' ],
},
{
	choiceKey: 'bulk-pricing',
	feats: [ 'use-cost-per-unit' ],
	elements: [ 'slider-with-bulk-or-sliding-pricing-element' ],
},
{
	choiceKey: 'mandatory-fees',
	feats: [ 'mandatory-fees' ],
	elements: [],
},
{
	choiceKey: 'need-complex-math',
	feats: [],
	elements: [ 'variable-math-element' ],
},
{
	choiceKey: 'need-to-apply-a-percentage',
	feats: [ 'need-to-apply-a-percentage' ],
	elements: [ 'custom-math-element' ],
},
{
	choiceKey: 'need-to-trigger-a-fee-or-discount',
	feats: [],
	elements: [ 'custom-math-with-cl-trigger-element' ],
} ];
const step3results = [ {
	choiceKey: 'lead-gen-user-enters-contact-to-see-final-price',
	feats: [ 'turn-off-detailed-list', 'turn-off-total-price' ],
	elements: [],
},
{
	choiceKey: 'send-email-quotes-pdf',
	feats: [
		'email-quote-primary-cta',
		'email-quote-custom-outgoing-message',
		'use-quote-management-screen',
		'use-live-currency-conversion',
	],
	elements: [
		'comment-box-element',
		'dropdown-element',
		'text-html-element',
		'slider-element',
	],
},
{
	choiceKey: 'lead-gen-user-can-email-total',
	feats: [ 'email-quote-primary-cta', 'email-quote-custom-outgoing-message' ],
	elements: [],
},
{
	choiceKey: 'e-comm',
	feats: [ 'woocommerce', 'stripe', 'paypal' ],
	elements: [ 'image-btn-w-qtn-sel-element' ],
},
{
	choiceKey: 'internal-tool',
	feats: [ 'internal-tool' ],
	elements: [],
},
{
	choiceKey: 'prod-config',
	feats: [],
	elements: [ 'slider-element' ],
} ];
const step4results = [
	{
		choiceKey: 'conditional-logic',
		feats: [ 'conditional-logic' ],
		elements: [],
	},
	{
		choiceKey: 'lead-gen-two-way-sms',
		feats: [ 'sms-feature' ],
		elements: [],
	},
	{
		choiceKey: 'multi-step',
		feats: [ 'activate-multiple-step', 'activate-accordion' ],
		elements: [],
	},
	{
		choiceKey: 'international-customers',
		feats: [ 'use-live-currency-conversion' ],
		elements: [],
	},
	{
		choiceKey: 'automation',
		feats: [ 'use-webhooks' ],
		elements: [],
	},
	{
		choiceKey: 'competitor-comparison',
		feats: [ 'use-custom-totals' ],
		elements: [],
	},
	{
		choiceKey: 'lead-management',
		feats: [ 'quotes-n-leads-dashboard' ],
		elements: [],
	},
	{
		choiceKey: 'stylish',
		feats: [ 'stylish' ],
		elements: [],
	},
	{
		choiceKey: 'coupons',
		feats: [ 'use-coupon-code-btn' ],
		elements: [],
	},
	{
		choiceKey: 'stats-n-conversion-tracking',
		feats: [ 'lead-source-analytics', 'form-conversion-analytics' ],
		elements: [],
	},
	{
		choiceKey: 'analytical-ai',
		feats: [ 'detailed-list' ],
		elements: [ 'slider-element' ],
	},
	{
		choiceKey: 'set-minimum-total',
		feats: [ 'use-minimum-total-feature' ],
		elements: [],
	},
	{
		choiceKey: 'shipping-rates-calculator',
		feats: [],
		elements: [ 'shipping-rates-calculator', 'distance-element' ],
	},
	{
		choiceKey: 'upsells-n-cross-sales',
		feats: [],
		elements: [ 'image-btn-w-qtn-sel-element' ],
	},
	{
		choiceKey: 'add-clarity-credibility-reduce-friction',
		feats: [],
		elements: [ 'slider-element' ],
	},
	{
		choiceKey: 'file-uploads',
		feats: [],
		elements: [ 'file-upload-element' ],
	},
	{
		choiceKey: 'date-picker',
		feats: [],
		elements: [ 'date-picker-element' ],
	},
	{
		choiceKey: 'user-inputs',
		feats: [],
		elements: [ 'comment-box-element' ],
	},
	{
		choiceKey: 'conditional-messages-n-alerts',
		feats: [ 'conditional-logic' ],
		elements: [ 'html-box-w-cl-element' ],
	},
];
const choicesSuggestionMap = [ ...step2results, ...step3results, ...step4results ];

const quizAnswersStore = {};
Object.keys( choicesData ).forEach( ( step ) => {
	quizAnswersStore[ step ] = {};
	choicesData[ step ].forEach( ( stepChoices ) => {
		if ( stepChoices.key == 'others' ) {
			quizAnswersStore[ step ][ stepChoices.key ] = '';
			return;
		}
		quizAnswersStore[ step ][ stepChoices.key ] = false;
	} );
} );
const modalLeads = {
	1: 'What are you <span style="color:#314af3;">selling</span>?',
	2: 'Choose one or <u>more</u> <span style="color:#314af3;">pricing structures</span>',
	3: 'Choose one or <u>more</u> <span style="color:#314af3;">use cases</span>',
	4: 'Choose one or <u>more</u> <span style="color:#314af3;">unique needs</span>',
	5: 'Choose one or <u>more</u> <span style="color:#314af3;">unique needs</span> (part 2)',
};
/**
 * *Creates new calculator with name
 * @param name_calculator
 */
function scc_create_new_calculator() {
	const name = jQuery( '#new-calc-name' ).val();
	if ( ! name ) {
		document.querySelector( '#new-calc-creator-section .text-danger' ).classList.remove( 'd-none' );
		setTimeout( () => {
			document.querySelector( '#new-calc-creator-section .text-danger' ).classList.add( 'd-none' );
		}, 5000 );
		return;
	}
	showLoadingChanges();

	if ( name ) {
		jQuery.ajax( {
			url: wp.ajax.settings.url,
			data: {
				action: 'sccCalculatorOp',
				op: 'add',
				calculator_name: name,
				nonce: pageAddCalculator.nonce,
			},
			success( data ) {
				console.log( data );
				const response = JSON.parse( data );
				if ( response.passed == true ) {
					window.location.href = window.location.pathname + '?page=scc_edit_items&id_form=' + response.data + '&new';
				} else {
					showSweet( false, 'An error occured, please try again' );
				}
			},
		} );
	}
}
/**
 * Creates a calculator form with the data returned by the quiz,
 * the caluclator will have the features and the elements selected by the results
 * @param results
 * @param _quizAnswersStore
 */
function scc_create_new_calculator_by_quiz_results( results, _quizAnswersStore ) {
	showWizardQuizResultEmailLoadingAlert( results.resultsEmailFormData.optin );
	jQuery.ajax( {
		url: wp.ajax.settings.url + '?nonce=' + pageAddCalculator.nonce + '&action=sccCalculatorOp' + '&op=load_by_params' + '&calculator_name=' + 'New Stylish Calculator',
		// set payload type to json
		contentType: 'application/json',
		method: 'POST',
		data: JSON.stringify( { ...results, ...{ __quizAnswersStore: _quizAnswersStore }, ...suggestionsByStep } ),
		success( data ) {
			const response = JSON.parse( data );
			if ( results.resultsEmailFormData.optin ) {
				if ( response.data ) {
					window.location.href = window.location.pathname + '?page=scc_edit_items&id_form=' + response.data + '&new';
				} else {
					showSweet( false, 'An error occurred, please try again' );
				}
			} else if ( response.pdfData ) {
				downloadPDF( response.pdfData, response.data );
				window.location.href = window.location.pathname + '?page=scc_edit_items&id_form=' + response.data + '&new';
			} else {
				showSweet( false, 'An error occurred generating the PDF' );
			}
		},
	} );
}

const downloadPDF = ( data, calcId, type = 'application/pdf' ) => {
	let blob = null;
	const filename = 'your-tailored-setup-guide-' + calcId + '-sylish-cost-calculator';
	blob = b64toBlob( data, type );
	const blobURL = URL.createObjectURL( blob );
	const tempLink = document.createElement( 'a' );
	tempLink.href = blobURL;
	tempLink.download = filename;
	tempLink.click();
};
const printPreview = ( data, type = 'application/pdf' ) => {
	let blob = null;
	const filename = 'your-tailored-setup-guide-sylish-cost-calculator';
	blob = b64toBlob( data, type );
	const blobURL = URL.createObjectURL( blob );
	const theWindow = window.open( blobURL );
	const theDoc = theWindow.document;
	const theScript = document.createElement( 'script' );
	function injectThis() {
		window.print();
	}
	theScript.innerHTML = `window.onload = ${ injectThis.toString() };`;
	theDoc.body.appendChild( theScript );
};
const b64toBlob = ( content, contentType ) => {
	contentType = contentType || '';
	const sliceSize = 512;
	// method which converts base64 to binary
	const byteCharacters = window.atob( content );
	const byteArrays = [];
	for ( let offset = 0; offset < byteCharacters.length; offset += sliceSize ) {
		const slice = byteCharacters.slice( offset, offset + sliceSize );
		const byteNumbers = new Array( slice.length );
		for ( let i = 0; i < slice.length; i++ ) {
			byteNumbers[ i ] = slice.charCodeAt( i );
		}
		const byteArray = new Uint8Array( byteNumbers );
		byteArrays.push( byteArray );
	}
	const blob = new Blob( byteArrays, {
		type: contentType,
	} ); // statement which creates the blob
	return blob;
};
/**
 * *Creates calculator with template
 */

function loadExample( element ) {
	const el = jQuery( element ).val();
	if ( el == 'null' ) {
		document.querySelector( '#template-loader .text-danger' ).classList.remove( 'd-none' );
		setTimeout( () => {
			document.querySelector( '#template-loader .text-danger' ).classList.add( 'd-none' );
		}, 5000 );
		return;
	}
	showLoadingChanges();
	jQuery.ajax( {
		url: ajaxurl,
		data: {
			action: 'sscLoadExample',
			el,
			nonce: pageAddCalculator.nonce,
		},
		success( data ) {
			console.log( data );
			if ( data.passed == true ) {
				window.location.href = window.location.pathname + '?page=scc_edit_items&id_form=' + data.data + '&new';
			} else {
				showSweet( false, 'An error occured, please try again' );
			}
		},
	} );
}

function getChoicesByStep( stepNumber ) {
	return choicesData[ 'step' + stepNumber ];
}

function getTemplateTypeByStep( stepNumber ) {
	if ( [ 'Result', 1 ].includes( stepNumber ) ) {
		return 'quiz-columned-card-choices-content';
	}
	return 'quiz-choices-content';
}

function buildChoicesContent( step ) {
	let templateData = {
		step,
	};
	if ( step !== 'Result' ) {
		templateData = {
			...templateData,
			choices: getChoicesByStep( step ),
		};
	}
	if ( step == 'Result' ) {
		// templateData.choices
		templateData = {
			...templateData,
			allFeatureSuggestions: getChoicesByStep( step ),
			allElementSuggestions: choicesData.elementSuggestions,
		};
	}
	return jQuery( wp.template( getTemplateTypeByStep( step ) )( templateData ) );
}

// Function to trigger 'change' event on checkbox input using vanilla JS
function triggerCheckboxChange( checkboxElement ) {
	const event = new Event( 'change' );
	checkboxElement.checked = true;
	checkboxElement.dispatchEvent( event );
}

function showModal( modalElementSelector, modalContentData, isFirstModal = false ) {
	const { currentStep } = modalContentData;
	const modalNode = jQuery( document.getElementById( modalElementSelector ) );
	const modalContent = jQuery( wp.template( 'quiz-modal-content' )( modalContentData ) );
	const choicesWrapper = modalContent.find( '.choices-wrapper' );
	// cleaning up previous content inside the modal body, if it was used earlier
	const modalExistingContent = modalNode[ 0 ]?.children;
	if ( modalExistingContent && modalExistingContent.length > 0 ) {
		[ ...modalExistingContent ].forEach( ( fragment ) => {
			fragment.remove();
		} );
	}
	const choicesContent = buildChoicesContent( currentStep );
	// registering tooltip for the modal contents
	choicesContent.find( '[title]' ).each( ( index, element ) => {
		const tooltip = new bootstrap.Tooltip( element );
	} );
	choicesWrapper.append( choicesContent );
	modalNode.append( modalContent );
	if ( isFirstModal ) {
		const cardChoices = modalNode.find( '.card' );
		cardChoices.attr( 'data-next-step', 2 );
		cardChoices.attr( 'data-max-steps', 5 );
		cardChoices.on( 'click', handleQuizBtnClick );
	}
	const modalActionBtn = modalNode.find( '.scc-setup-wizard-button' );
	const modalInputFields = modalNode.find( 'input:not([data-element-suggestion])' );
	const modalInputElementSuggestions = modalNode.find( 'input[data-element-suggestion]' );
	modalActionBtn.on( 'click', handleQuizBtnClick );
	modalInputFields.on( 'change', ( evt ) => {
		updateQuizAnswersStore( evt, 'step' + currentStep );
	} );
	modalInputElementSuggestions.on( 'change', ( evt ) => {
		updateQuizAnswersStore( evt, 'elementSuggestions' );
	} );
	// If the 'modalInputElementSuggestions' variable has length, it is a final result modal
	// And we set all of the choices to checked state
	if ( modalInputElementSuggestions.length > 0 ) {
		modalInputElementSuggestions.each( ( index, element ) => {
			triggerCheckboxChange( element );
		} );
		modalInputFields.each( ( index, element ) => {
			triggerCheckboxChange( element );
		} );
	}
	const quizModal = bootstrap.Modal.getOrCreateInstance( modalNode.get( 0 ) );
	quizModal.show();
}

function send_setup_wizard_data_and_build( srcBtn, filteredFeaturesAndSuggestions ) {
	const _quizAnswersStore = Object.assign( {}, quizAnswersStore );
	// renaming stepResult to featureSuggestions
	_quizAnswersStore.featureSuggestions = _quizAnswersStore.stepResult;
	delete _quizAnswersStore.stepResult;
	document.querySelector( '#new-calc-name' ).value = 'New Stylish Calculator';
	scc_create_new_calculator_by_quiz_results( filteredFeaturesAndSuggestions, _quizAnswersStore );
}

function isElementInView( scrollableDiv, targetElement ) {
	// Get dimensions and position for the scrollable div
	const divTop = scrollableDiv.scrollTop;
	const divBottom = divTop + scrollableDiv.clientHeight;

	// Get dimensions and position for the target element
	const elemTop = targetElement.offsetTop;
	const elemBottom = elemTop + targetElement.clientHeight;

	// Check if the element is fully within the view of the scrollable div
	return elemTop >= divTop && elemBottom <= divBottom;
}

function handleQuizBtnClick( evt ) {
	const { currentTarget: nextBtn } = evt;
	let currentStep = Number( nextBtn.getAttribute( 'data-next-step' ) );
	const finalStep = Number( nextBtn.getAttribute( 'data-max-steps' ) );
	const modalNode = nextBtn.closest( '.modal' );
	const modalInstance = bootstrap.Modal.getInstance( modalNode );
	const isFinalStep = currentStep == finalStep;
	if ( currentStep !== 0 ) {
		modalInstance.hide();
	}
	if ( currentStep == 0 ) {
		const resultAction = nextBtn.getAttribute( 'data-result-action' );
		const formEmailFields = document.querySelector( '#wq_field_wrapper input[type="email"]' );
		const isEmailOptInEnabled = ( resultAction === 'email' ) ? true : false;
		// check if the `formEmailFields` is visible
		if ( ( ! isElementInView( document.querySelector( '.modal.show .modal-body' ), formEmailFields ) ) && isEmailOptInEnabled ) {
			emailResultsFormScrollToView( true );
			return;
		}
		// if the `wq_your_name` and the `wq_your_email` fields are empty, show the error message
		if ( isEmailOptInEnabled && ( ! document.querySelector( '#wq_field_wrapper input[type="text"]' ).value || ! document.querySelector( '#wq_field_wrapper input[type="email"]' ).value ) ) {
			emailResultsFormScrollToView( true );
			document.querySelector( '#wq_field_wrapper' ).classList.add( 'scc-wql-field-warnings' );
			return;
		}
		const buildCalculatorActionBtn = nextBtn;
		let templateData = {
			step: 'Result',
		};
		templateData = {
			...templateData,
			allFeatureSuggestions: getChoicesByStep( 'Result' ),
			allElementSuggestions: choicesData.elementSuggestions,
		};
		const filteredFeaturesAndSuggestions = filterResultPageSuggestions( templateData );
		filteredFeaturesAndSuggestions.elementSuggestions.forEach( ( element ) => {
			quizAnswersStore.elementSuggestions[ element.key ] = true;
		} );
		filteredFeaturesAndSuggestions.choices.forEach( ( feature ) => {
			quizAnswersStore.stepResult[ feature.key ] = true;
		} );
		const resultsEmailFormData = {
			optin: isEmailOptInEnabled,
			email: document.querySelector( '#wq_field_wrapper input[type="email"]' ).value,
			name: document.querySelector( '#wq_field_wrapper input[type="text"]' ).value,
		};
		send_setup_wizard_data_and_build( buildCalculatorActionBtn, { elementsByChoice: filteredFeaturesAndSuggestions.elementsByChoice, featuresByChoice: filteredFeaturesAndSuggestions.featuresByChoice, resultsEmailFormData } );
		return;
	}
	if ( isNaN( currentStep ) ) {
		// currentStep is 'Result', thus was evaluated as NaN
		currentStep = nextBtn.getAttribute( 'data-next-step' );
		showModal( 'quizResult', {
			title: 'Setup Wizard: Final Step',
			subtitle: `For a seamless setup, get your instructions via <strong>Email</strong> to gide you as you build, or <strong>Download a PDF</strong> to keep a permanent guide on hand. Both provide clear, step-by-step directions to perfect your calculator form.`,
			modalLead: '',
			currentStep,
			actionBtnTitle: 'Send My Recommendations',
			quizNextStep: 0,
			isFinalStep: true,
		} );
		return;
	}
	showModal( 'quizModal' + currentStep, {
		title: 'Setup Wizard',
		subtitle: `Step ${ currentStep } of 5`,
		modalLead: modalLeads[ currentStep ],
		currentStep,
		actionBtnTitle: isFinalStep ? 'Finish' : 'Next',
		quizNextStep: isFinalStep ? 'Result' : currentStep + 1,
		isFinalStep,
	} );
	return 0;
}

function handleBackNavigation( currentStep, backBtn ) {
	const isFinalStep = currentStep == 5;
	const isFirstModal = currentStep == 1;
	const templateId = isFirstModal ? 'quizModal' : 'quizModal' + currentStep;

	const modalNode = backBtn.closest( '.modal' );
	const modalInstance = bootstrap.Modal.getInstance( modalNode );
	modalInstance.hide();

	showModal( templateId, {
		title: 'Setup Wizard',
		subtitle: `Step ${ currentStep } of 5`,
		modalLead: modalLeads[ currentStep ],
		currentStep,
		actionBtnTitle: isFinalStep ? 'Finish' : 'Next',
		quizNextStep: isFinalStep ? 'Result' : currentStep + 1,
		isFinalStep,
	}, isFirstModal );
}

function sccGetOffset( el ) {
	const rect = el.getBoundingClientRect();
	return {
		left: rect.left + window.scrollX,
		top: rect.top + window.scrollY,
	};
}

function updateQuizAnswersStore( evt, inputOriginStep ) {
	const { currentTarget: inputField } = evt;
	if ( inputField.name == 'others' ) {
		// revealing the input field to define the others
		const defineOthersInput = document.querySelector( `[name="${ inputOriginStep }-othersInput"]` );
		const defineOthersInputWrapper = defineOthersInput.closest( '.form-check' );
		defineOthersInputWrapper.classList.toggle( 'd-none' );
		defineOthersInputWrapper.value = '';
		// scroll to the input field
		defineOthersInput.scrollIntoView( { behavior: 'smooth' } );
		// adding cursor focus to the input field
		defineOthersInput.focus();
		// adding event listener to the input field
		if ( defineOthersInput.getAttribute( 'data-event-registered' ) == 'true' ) {
			return;
		}
		defineOthersInput.addEventListener( 'change', ( evt ) => {
			quizAnswersStore[ inputOriginStep ][ inputField.name ] = evt.currentTarget.value;
		} );
		defineOthersInput.setAttribute( 'data-event-registered', 'true' );

		return;
	}
	quizAnswersStore[ inputOriginStep ][ inputField.name ] = inputField.checked;
}

document.querySelector( 'body' ).classList.remove( 'wp-core-ui' );

const welcomePageActionBtns = document.querySelectorAll( '[data-btn-action]' );
const welcomeSection = document.querySelector( '#welcome-section' );
const newCalcSection = document.querySelector( '#new-calc-creator-section' );
const restoreCalcSection = document.querySelector( '#restore-section' );
const templatePickerFragment = document.querySelector( '#template-loader-wrapper' );

const chooseATemplate = document.querySelector( '#choose-a-template' );
const chooseATemplateBtn = document.querySelector( '[data-relative-field="choose-a-template"]' );
const newCalcName = document.querySelector( '#new-calc-name' );
const newCalcNameBtn = document.querySelector( '[data-relative-field="new-calc-name"]' );
const calcPreview = document.querySelector( '#calc-preview-wrapper' );
const calcPreviewImage = document.querySelector( '#calc-preview-wrapper img' );
const pageWrapper = document.querySelector( '#add-new-page-wrapper' );
const newCalcCreateBox = document.querySelector( '#new-calc-creator-section' );

calcPreviewImage?.addEventListener( 'click', ( evt ) => {
	chooseATemplateBtn.click();
} );

welcomePageActionBtns.forEach( ( btn ) => {
	btn.addEventListener( 'click', ( evt ) => {
		const actionType = btn.getAttribute( 'data-btn-action' );

		// Instead of eval, use a condition or switch statement
		if ( typeof window[ actionType ] === 'function' ) {
			window[ actionType ]();
		}
	} );
} );

function showNewCalcNameInput() {
	welcomeSection.classList.add( 'd-none' );
	newCalcSection.classList.remove( 'd-none' );
}

function showRestorePrompt() {
	welcomeSection.classList.add( 'd-none' );
	restoreCalcSection.classList.remove( 'd-none' );
}

function handleEmailOptInForWQ( $this ) {
	const shownModalActionBtn = document.querySelector( '.modal.show .scc-setup-wizard-button' );
	if ( $this.checked ) {
		document.querySelector( '#wq_field_wrapper' ).classList.remove( 'd-none' );
		shownModalActionBtn.textContent = 'Send My Recommendations';
	} else {
		document.querySelector( '#wq_field_wrapper' ).classList.add( 'd-none' );
		shownModalActionBtn.textContent = 'Build My Calculator';
	}
	emailResultsFormScrollToView();
}

function emailResultsFormScrollToView( showBorder = false ) {
	const modalVisibleContent = document.querySelector( '.modal.show .modal-body' );
	const formEmailFields = document.querySelector( '#wq_field_wrapper input[type="email"]' );
	modalVisibleContent.scrollTo( 0, sccGetOffset( formEmailFields ).top - 100 );
	if ( showBorder ) {
		formEmailFields.classList.add( 'scc-wql-field-warnings' );
		setTimeout( () => {
			formEmailFields.classList.remove( 'scc-wql-field-warnings' );
		}, 2000 );
	}
}

function showTemplateChoices() {
	welcomeSection.classList.add( 'd-none' );
	pageWrapper.classList.add( 'd-none' );
	templatePickerFragment.classList.remove( 'd-none' );
}

function startQuiz() {
	const currentStep = 1;
	isFinalStep = false;
	showModal( 'quizModal', {
		title: 'Setup Wizard',
		subtitle: `Step ${ currentStep } of 5`,
		modalLead: modalLeads[ currentStep ],
		currentStep,
		quizNextStep: isFinalStep ? 'Result' : currentStep + 1,
		isFinalStep,
	}, true );
	const wrapper = document.querySelector( '#wpwrap' );
	if ( wrapper ) {
		wrapper.classList.add( 'scc-p-relative' );
	}
}

chooseATemplate?.addEventListener( 'change', ( evt ) => {
	const selectedValue = evt.target.value;
	if ( selectedValue !== 'null' ) {
		calcPreview.classList.remove( 'd-none' );
		pageWrapper.classList.remove( 'with-vh' );
		calcPreview.querySelector( 'img' ).setAttribute( 'src', previewImagesBaseUrl + '/' + chooseATemplate.querySelector( `[value="${ chooseATemplate.value }"]` ).getAttribute( 'data-preview-image' ) );
	} else {
		pageWrapper.classList.add( 'with-vh' );
		calcPreview.classList.add( 'd-none' );
	}
} );

newCalcName.addEventListener( 'keyup', ( evt ) => {
	const currentValue = evt.target.value;
} );

newCalcNameBtn.addEventListener( 'click', ( evt ) => {
	scc_create_new_calculator();
} );

chooseATemplateBtn?.addEventListener( 'click', ( evt ) => {
	loadExample( chooseATemplate );
} );

function showSweet( respuesta, message ) {
	if ( respuesta ) {
		Swal.fire( {
			toast: true,
			title: message,
			icon: 'success',
			showConfirmButton: false,
			timer: 3000,
			position: 'bottom-end',
		} );
	} else {
		Swal.fire( {
			toast: true,
			title: message,
			icon: 'error',
			showConfirmButton: false,
			timer: 3000,
			position: 'top-end',
			background: 'white',
		} );
	}
}

function showWizardQuizResultEmailLoadingAlert( emailOptIn = false ) {
    const modalShown = document.querySelector( '.modal.show' );
    // Hide the modal if it is shown
    if ( modalShown ) {
        const modalInstance = bootstrap.Modal.getInstance( modalShown );
        modalInstance.hide();
    }
    Swal.fire( {
        title: 'Success',
        width: 600,
        padding: '3em',
        showConfirmButton: false,
        html: `<div style="color: #716add;">${ emailOptIn ? 'We sent your email instructions.' : '' } <b>Please wait</b> while we build your calculator form based on your answers.</div>`,
        willOpen: () => {
            Swal.showLoading();
        },
        backdrop: `
          left top no-repeat rgba(0, 0, 0, .85)
          no-repeat
        `,
    } );
}