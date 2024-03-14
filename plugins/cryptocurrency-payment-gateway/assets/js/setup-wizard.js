/** Based on https://levelup.gitconnected.com/create-a-multi-step-form-using-html-css-and-javascript-30aca5c062fc */

/**
 * Define a function to navigate between form steps.
 * It accepts one parameter. That is - step number.
 */
const navigateToFormStep = (stepNumber) => {
	/**
	 * Hide all form steps.
	 */
	document.querySelectorAll( ".form-step" ).forEach( (formStepElement) => {
		formStepElement.classList.add( "d-none" );
	});

	/**
	 * Mark all form steps as unfinished.
	 */
	document.querySelectorAll( ".form-stepper-list" ).forEach( ( formStepHeader ) => {
		formStepHeader.classList.add( "form-stepper-unfinished" );
		formStepHeader.classList.remove( "form-stepper-active", "form-stepper-completed" );
	});

	/**
	 * Show the current form step (as passed to the function).
	 */
	document.querySelector( "#step-" + stepNumber ).classList.remove( "d-none" );

	/**
	 * Select the form step circle (progress bar).
	 */
	const formStepCircle = document.querySelector( 'li[step="' + stepNumber + '"]' );

	/**
	 * Mark the current form step as active.
	 */
	formStepCircle.classList.remove( "form-stepper-unfinished", "form-stepper-completed" );
	formStepCircle.classList.add( "form-stepper-active" );

	/**
	 * Loop through each form step circles.
	 * This loop will continue up to the current step number.
	 * Example: If the current step is 3,
	 * then the loop will perform operations for step 1 and 2.
	 */
	for ( let index = 0; index < stepNumber; index++ ) {

		/**
		 * Select the form step circle (progress bar).
		 */
		const formStepCircle = document.querySelector( 'li[step="' + index + '"]' );

		/**
		 * Check if the element exist. If yes, then proceed.
		 */
		if ( formStepCircle ) {

			/**
			 * Mark the form step as completed.
			 */
			formStepCircle.classList.remove( "form-stepper-unfinished", "form-stepper-active" );
			formStepCircle.classList.add( "form-stepper-completed" );
		}
	}

    // Scroll to the top of the page.
    scroll(0,0);
};

/**
 * Define a function to perform form validation of current step prior to navigating to new step.
 * It accepts one parameter. That is - current step number.
 */
const formValidation = (stepNumber) => {
	// Maybe show an error if no cryptocurrency has been selected.
	if ( 1 === stepNumber ) {
		const checkboxes_checked = document.querySelectorAll( 'input[class="coin-selection"]:checked' );
		const checkboxes		 = document.querySelectorAll( 'input[class="coin-selection"]' );

		if ( checkboxes_checked === undefined || 0 === checkboxes_checked.length )  {
			checkboxes.forEach( (checkbox) => {
				checkbox.classList.add( 'invalid-value' );
				checkbox.addEventListener( "click", () => {
					checkboxes.forEach( (checkbox) => {
						checkbox.classList.remove( 'invalid-value' );
						disableFormValidationError( stepNumber );
					});
				});
			});
			setFormValidationError( stepNumber, 'You must select at least one cryptocurrency.' );
			return false;
		}
	}

	// Maybe show an error if no cryptocurrency addresses has been added.
	if ( 3 === stepNumber ) {
		const address_lists = document.querySelectorAll( '.mt-3:not(.d-none) textarea' );

		let address_list_validation_failed = false;

		address_lists.forEach( (address_list) => {
			if ( 0 === address_list.value.length ) {
				address_list.classList.add( 'invalid-value' );
				setFormValidationError( stepNumber, 'You must add at least one address per cryptocurrency.' );
				address_list_validation_failed = true;

				address_list.onkeyup = function() {
					disableFormValidationError( stepNumber );
					address_list.classList.remove( 'invalid-value' );
				}
			}
		});

		if ( address_list_validation_failed ) {
			return false;
		}
	}

	return true;
}

/**
 * Define a function to do set and display form validation errors.
 * It accepts two parameters. That is - step number and error message.
 */
const setFormValidationError = ( stepNumber, errorMessage ) => {
	document.querySelector( '#step-' + stepNumber + ' .form-validation-error-message p' ).innerHTML = errorMessage;
	document.querySelector( '#step-' + stepNumber + ' .form-validation-error-message' ).classList.remove( 'd-none' );
}

/**
 * Define a function to do disable/hide form validation error message.
 * It accepts two parameters. That is - step number and error message.
 */
const disableFormValidationError = ( stepNumber ) => {
	document.querySelector( '#step-' + stepNumber + ' .form-validation-error-message' ).classList.add( 'd-none' );
}

/**
 * Define a function to do modifications on specific steps.
 * It accepts one parameter. That is - step number.
 */
const maybeDoStepModifications = (stepNumber) => {
	// Maybe disable wallet recommendation according to supported cryptocurrencies.
	if ( 2 === stepNumber ) {
		const bitcoin_com_incompatible_coins = [ 'litecoin', 'dogecoin' ];

		const checkboxes = document.querySelectorAll( 'input[class="coin-selection"]:checked' );

		let coin_is_incompatible_with_bitcoin_com = false;

		checkboxes.forEach( (checkbox) => {
			if (bitcoin_com_incompatible_coins.includes( checkbox.value ) ) {
				coin_is_incompatible_with_bitcoin_com = true;
			}
		});

		if ( coin_is_incompatible_with_bitcoin_com ) {
			document.getElementById( 'wallet-recommendation-bitcoin-com' ).classList.add( 'd-none' );
		} else {
			document.getElementById( 'wallet-recommendation-bitcoin-com' ).classList.remove( 'd-none' );
		}
	}

	// Disable wallet address lists according to selected coin, and do not allow more than 20 addresses per coin.
	if ( 3 === stepNumber ) {
		const checkboxes = document.querySelectorAll('input[class="coin-selection"]');

		checkboxes.forEach((checkbox) => {
            let crypto_id = checkbox.value.replace('_', '-');

            // Disable wallet address lists according to selected cryptocurrency.
            let address_list_element = document.getElementById( crypto_id + '-addresses' );
			if (checkbox.checked) {
				address_list_element.classList.remove('d-none');
			} else {
				address_list_element.classList.add('d-none');
			}

            // Do not allow more than 20 addresses in textarea field, inspired by: https://stackoverflow.com/a/23031729.
            let textarea     = document.querySelector( 'textarea[name="' + crypto_id + '-addresses"]' );
            let limit	     = 20; // <---max no of lines you want in textarea
            textarea.onkeyup = function() {
                var lines = textarea.value.split( "\n" );

                if( lines.length>limit )
                {
                    textarea.style.color = 'red';
                    setTimeout(function(){
                        textarea.style.color = '';
                    },500);
                }
                textarea.value = lines.slice( 0, limit ).join("\n");
            };
		});
	}
}

jQuery( document ).ready( function ($) {

	/**
	 * Select all form navigation buttons, and loop through them.
	 */
	document.querySelectorAll( ".btn-navigate-form-step" ).forEach( (formNavigationBtn) => {

		/**
		 * Add a click event listener to the button.
		 */
		formNavigationBtn.addEventListener( "click", () => {

			/**
			 * Get the value of the step.
			 */
			const stepNumber = parseInt( formNavigationBtn.getAttribute( "step_number" ) );

			/**
			 * Do form validation.
			 */
			if ( formValidation( stepNumber - 1 ) ) {

				/**
				 * Call the function to navigate to the target form step.
				 */
				navigateToFormStep( stepNumber );

				/**
				 * Call the function to maybe disable wallet recommendation according to coin selection.
				 */
				maybeDoStepModifications( stepNumber );
			}
		});
	});

    /**
     * Select all form submit buttons, and loop through them.
     */
    document.querySelectorAll( ".submit-btn" ).forEach( (submitBtn) => {

        /**
         * Add a click event listener to the button.
         */
        submitBtn.addEventListener( "click", function( event ) {

            /**
             * Get the value of the step.
             */
            const stepNumber = parseInt( submitBtn.getAttribute( "step_number" ) );

            /**
             * Do form validation.
             */
            if ( ! formValidation( stepNumber ) ) {
                event.preventDefault();
            }
        });
    });
});