'use strict';

if ( ! window.ACADPInitForm ) {
	/**
	 * Init Form.
	 * @param {string} selector The form selector.
	 */
	var ACADPInitForm = ( selector ) => {
		// Get the form element.
		const formEl = document.querySelector( selector );

		// Update the JS enabled state.
		formEl.dataset.jsEnabled = 'true';

		// Turn off built-in form submit validation. 
		formEl.setAttribute( 'novalidate', '' );
	
		return formEl;
	}
}

if ( ! window.ACADPCheckValidity ) {
	/**
	 * Check the form validity with the Constraint Validation API.
	 * @param {HTMLFormElement} The form element that was validated.
	 * @returns {boolean} Is the form valid?
	 */
	var ACADPCheckValidity = ( formEl ) => {
		// Update the validation UI state for all inputs.
		formEl.querySelectorAll( '.acadp-form-validate' ).forEach( ACADPUpdateValidationStateForInput );

		// The isFormValid boolean respresents all inputs that can
		// be validated with the Constraint Validation API.
		let isFormValid = formEl.checkValidity();

		// Fields that cannot be validated with the Constraint Validation API need
		// to be validated manually.
		formEl.querySelectorAll( '.acadp-form-validate-checkboxes' ).forEach(( fieldsetEl ) => {
			const isValid = ACADPValidateCheckboxGroup( fieldsetEl );
			if ( ! isValid ) {
				isFormValid = isValid;
			}
		});

		return isFormValid;
	}
}

if ( ! window.ACADPUpdateValidationStateForInput ) {
	/**
	 * Update the validation UI state for a given input element.
	 * @param {HTMLInputElement} inputEl The input element to update the UI state for.
	 */
	var ACADPUpdateValidationStateForInput = ( inputEl ) => {
		const formGroupEl = inputEl.closest( '.acadp-form-group' );

		// Check if the input is valid using the Constraint Validation API.
		// Yes, one line of code handles validation. 
		// The Constraint Validation API is cool!
		const isInputValid = inputEl.checkValidity();

		// Handle optional fields that are empty
		if ( ! inputEl.required && inputEl.value === '' && isInputValid ) {
			// Clear validation states.
			formGroupEl.classList.remove( 'is-valid', 'is-invalid' );
		} else {
			// Required fields: Toggle valid/invalid state classes.
			formGroupEl.classList.toggle( 'is-valid', isInputValid );
			formGroupEl.classList.toggle( 'is-invalid', ! isInputValid );
		}

		// Update the `aria-invalid` state based on the input's validity.
		// Converts the boolean to a string.
		inputEl.setAttribute( 'aria-invalid', ( ! isInputValid ).toString() );

		try {
			// Get the error message element for the current input element.
			const errorEl = formGroupEl.querySelector( '.acadp-form-error' );

			// Use custom validation messages.
			errorEl.textContent = ACADPGetValidationMessageForInput( inputEl );

			// Show/hide the error message depending on the input's validity.
			errorEl.hidden = isInputValid;
		} catch( e ) {
			// console.log( e ); 
		}
	}
}

if ( ! window.ACADPValidateCheckboxGroup ) {
	/**
	 * Validates the checkbox group.
	 * Custom validation is required because checkbox group validation 
	 * is not supported by the browser's built-in validation features.
	 * @param {HTMLFieldSetElement} fieldsetEl The form element
	 * @return {boolean} Is the checkbox group valid?
	 */
	var ACADPValidateCheckboxGroup = ( fieldsetEl ) => {
		// Are any of the checkboxes checked? 
		// At least one is required.
		const isValid = fieldsetEl.querySelectorAll( 'input[type=checkbox]:checked' ).length > 0;

		// Need to place the validation state classes higher up to show
		// a validation state icon (one icon for the group of checkboxes).
		fieldsetEl.classList.toggle( 'is-valid', isValid );
		fieldsetEl.classList.toggle( 'is-invalid', ! isValid );

		// Also update aria-invalid on the fieldset (convert to a string)
		fieldsetEl.setAttribute( 'aria-invalid', String( ! isValid ) );

		// Get both the legend and visual error message elements.
		const legendErrorEl = fieldsetEl.querySelector( '.acadp-form-legend-error' );
		const visualErrorEl = fieldsetEl.querySelector( '.acadp-form-error' );
		
		// Update the validation error message.
		const errorMsg = isValid ? '' : acadp.i18n.required_multicheckbox;
		
		// Set the error message for both the legend and the visual error.
		legendErrorEl.textContent = errorMsg;
		visualErrorEl.textContent = errorMsg;
		
		// Show/hide the visual error message depending on validity.
		visualErrorEl.hidden = isValid;

		// Return the validation state.
		return isValid;
	}
}

if ( ! window.ACADPValidatePassword ) {
	/**
	 * Validates the password.
	 * @param {HTMLFormElement} formEl The form element that was submitted.
	 * @return {boolean} Is the passwords same?
	 */
	var ACADPValidatePassword = ( formEl ) => {
		if ( formEl.pass1.value === '' || formEl.pass2.value === '' ) {
			return true;
		}

		// Check if the passwords are same.
		const isInputValid = ( formEl.pass1.value === formEl.pass2.value );

		formEl.querySelectorAll( '.acadp-form-group-password' ).forEach(( formGroupEl ) => {
			const inputEl = formGroupEl.querySelector( '.acadp-form-validate-password' );

			// Required fields: Toggle valid/invalid state classes.
			inputEl.classList.toggle( 'is-valid', isInputValid );
			inputEl.classList.toggle( 'is-invalid', ! isInputValid );

			// Update the `aria-invalid` state based on the input's validity.
			// Converts the boolean to a string.
			inputEl.setAttribute( 'aria-invalid', ( ! isInputValid ).toString() );

			try {
				// Get the error message element for the current input element.
				const errorEl = formGroupEl.querySelector( '.acadp-form-error' );

				// Use custom validation messages.
				errorEl.textContent = acadp.i18n.invalid_password;

				// Show/hide the error message depending on the input's validity.
				errorEl.hidden = isInputValid;
			} catch( e ) {
				// console.log( e ); 
			}
		});		

		// Return the validation state.
		return isInputValid;
	}
}

if ( ! window.ACADPGetValidationMessageForInput ) {
	/**
	 * Returns a custom validation message referencing the input's ValidityState object.
	 * @param {HTMLInputElement} inputEl The input element
	 * @returns {string} A custom validation message for the given input element
	 */
	var ACADPGetValidationMessageForInput = ( inputEl ) => {
		// If the input is valid, return an empty string.
		if ( inputEl.validity.valid ) return '';

		// If all else fails, return the default built-in message.
		return inputEl.validationMessage;
	}
}

(function( $ ) {
	
	/**
	 * Called when the page has loaded.
	 */
	$(function() {

		// Set up `blur` and `input` validation for the inputs that can be 
		// validated with the Constraint Validation API.
		$( document ).on( 'input', '.acadp-form-validate', ( event ) => {
			ACADPUpdateValidationStateForInput( event.target );
		});

		$( document ).on( 'blur', '.acadp-form-validate', ( event ) => {
			ACADPUpdateValidationStateForInput( event.target );
		});

		// Updates the UI state for the checkbox group when checked/unchecked.
		$( document ).on( 'change', '.acadp-form-validate-checkboxes input[type=checkbox]', ( event ) => {
			const fieldsetEl = event.target.closest( 'fieldset' );
        	ACADPValidateCheckboxGroup( fieldsetEl );
		});

		// Set up late validation for the checkbox group.
		$( document ).on( 'blur', '.acadp-form-validate-checkboxes input[type=checkbox]', ( event ) => {
			// FocusEvent.relatedTarget is the element receiving focus.
			const activeEl = event.relatedTarget;

			// Validate only if the focus is not going to another checkbox.
			if ( activeEl?.type !== 'checkbox' ) {
				const fieldsetEl = event.target.closest( 'fieldset' );
				ACADPValidateCheckboxGroup( fieldsetEl );
			}
		});		

	});

})( jQuery );
