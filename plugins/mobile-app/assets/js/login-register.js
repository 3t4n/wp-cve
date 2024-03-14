window.addEventListener(
	"load",
	function () {
		removeOtherSpinnerOS();
		var loginFormElement             = document.getElementById( "canvas-loginform" );
		var registerFormElement          = document.getElementById( "canvas-register-form" );
		var forgotPasswordFormElement    = document.getElementById( "canvas-fp-form" );
		var forgotPasswordMessageElement = document.getElementsByClassName( "canvas-form-forgot-password-message" )[0];
		var registerLink                 = document.getElementById( "register-link" );
		var forgotPasswordLink           = document.getElementById( "forgot-password-link" );
		var loginLink                    = document.getElementById( "login-link" );

		var FormSubmitBtn   = document.getElementById( "wp-submit" );
		var LinkClickBtn    = document.getElementById( "wp-link-submit" );
		var isFormSubmitted = false;

		if (loginFormElement) {
			loginFormElement.addEventListener(
				"submit",
				function (e) {
					e.preventDefault();
					if ( ! isFormSubmitted) {
						preSubmitForm( loginFormElement, FormSubmitBtn );
						return false;
					}
				}
			)
		}
		if (registerFormElement) {
			registerFormElement.addEventListener(
				"submit",
				function (e) {
					e.preventDefault();
					if ( ! isFormSubmitted) {
						preSubmitForm( registerFormElement, FormSubmitBtn );
						return false;
					}
				}
			)
		}
		if (forgotPasswordFormElement) {
			forgotPasswordFormElement.addEventListener(
				"submit",
				function (e) {
					e.preventDefault();
					if ( ! isFormSubmitted) {
						preSubmitForm( forgotPasswordFormElement, FormSubmitBtn );
						return false;
					}
				}
			)
		}
		if (LinkClickBtn) {
			LinkClickBtn.addEventListener(
				"click",
				function (e) {
					forgotPasswordMessageElement.classList.add( "loading" );
					LinkClickBtn.classList.add( "loading" );
					document.getElementsByClassName( "spinner-loading" )[0].classList.remove( "hide" );
				}
			)
		}
		if (registerLink) {
			registerLink.addEventListener(
				"click",
				function (e) {
					document.getElementById( "loading-full-page" ).classList.remove( "hide" );
				}
			)
		}
		if (forgotPasswordLink) {
			forgotPasswordLink.addEventListener(
				"click",
				function (e) {
					document.getElementById( "loading-full-page" ).classList.remove( "hide" );
				}
			)
		}
		if (loginLink) {
			loginLink.addEventListener(
				"click",
				function (e) {
					document.getElementById( "loading-full-page" ).classList.remove( "hide" );
				}
			)
		}
		if ( document.getElementById( "label_canvas_agree_term" ) ) {
			document.getElementById( "label_canvas_agree_term" ).addEventListener(
				"click",
				function (e) {
					var checkbox     = document.getElementById( "canvas_agree_term" );
					checkbox.checked = ! checkbox.checked;
				}
			)
		}
	}
)

/*  Trigger the modal window */

let triggerLink = document.getElementById( "label_canvas_agree_term" );
let body        = document.getElementsByTagName( "body" )[0];
if (triggerLink) {
	triggerLink = triggerLink.getElementsByTagName( "a" );
	if (triggerLink) {
		triggerLink = triggerLink[0];

		let termModal = document.getElementById( "term-content" );
		let closeBtn  = document.getElementById( "close-term-modal" );

		body.insertAdjacentElement( 'beforeEnd', termModal );
		triggerLink.onclick = function () {
			termModal.style.display = "block";
		}
		closeBtn.onclick    = function () {
			termModal.style.display = "none";
		}
		window.onclick      = function (event) {
			if (event.target === termModal) {
				termModal.style.display = "none";
			}
		}
	}
}

/**
 * Showing the loading before submitting form
 *
 * @param formElement
 * @param FormSubmitBtn
 */
function preSubmitForm(formElement, FormSubmitBtn)
{
	FormSubmitBtn.disabled = true;
	isFormSubmitted        = true;
	FormSubmitBtn.value    = "";
	FormSubmitBtn.classList.add( "loading" );
	document.getElementsByClassName( "spinner-loading" )[0].classList.remove( "hide" );
	setTimeout(
		function () {
			formElement.submit();
		},
		2000
	);
}

/**
 * Hiding the spinner which not match with the current OS
 */
function removeOtherSpinnerOS()
{
	let spinnerEle = {};
	if (ons.platform.isAndroid()) {
		spinnerEle = document.getElementsByClassName( "spinner-ios" );
	} else {
		spinnerEle = document.getElementsByClassName( "spinner-android" );
	}
	while (spinnerEle[0]) {
		spinnerEle[0].parentNode.removeChild( spinnerEle[0] );
	}

}
