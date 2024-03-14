document.addEventListener('DOMContentLoaded', function() {
	let reconnectMessage = document.getElementById('iwp-admin-login-reconnect-box');
	if (reconnectMessage) {
		setTimeout(() => {
			reconnectMessage.innerHTML = '';
		}, 10000);
	}

	/*** FUNCIONES ADICIONALES ***/

	/**
	 * CHECK IF THE LOGIN MODAL SHOULD BE DISPLAYED AT START
	 */
	const checkLoginModalStatus = () => {
		let mainBlock = document.getElementById('iwpAdminModalLogin');
		if (mainBlock && (mainBlock.getAttribute('data-show') === '1')) {
			let loginBlock = document.getElementById('iwpAdminLogin');

			showHideLoginModalBlock(mainBlock, true);
			showHideLoginModalBlock(loginBlock, true);

		}
	};

	const closeLoginAlertsOnClick = () => {
		let loginAlerts = document.querySelectorAll('.iwp-admin-default-box');
		Array.from(loginAlerts).forEach((el) => {
			el.addEventListener("click", function (e) {
				e.stopPropagation();
				e.stopImmediatePropagation();
				el.innerHTML = '';
			});
		});
	};


	/*** EVENTOS Y FUNCIONES LANZADAS AL INICIO ***/

	checkLoginModalStatus();
	closeLoginAlertsOnClick();

	// HIDE LOGIN BLOCK AND SHOW SIGN UP BLOCK
	document.getElementById('showSignUp').addEventListener("click", function () {
		switchLoginModalBlocks(
			document.getElementById('iwpAdminLogin'),
			document.getElementById('iwpAdminSignUp')
		);
	});

	// HIDE SIGN UP BLOCK AND SHOW LOGIN BLOCK
	document.getElementById('showLogin').addEventListener("click", function () {
		switchLoginModalBlocks(
			document.getElementById('iwpAdminSignUp'),
			document.getElementById('iwpAdminLogin')
		);
	});

	// HIDE LOGIN BLOCK AND SHOW RECOVER PASS BLOCK
	document.getElementById('recoverPassword').addEventListener("click", function () {
		switchLoginModalBlocks(
			document.getElementById('iwpAdminLogin'),
			document.getElementById('iwpAdminRecoverPass')
		);
	});

	// HIDE RECOVER PASS BLOCK AND SHOW LOGIN BLOCK
	document.getElementById('backToLogin').addEventListener("click", function () {
		switchLoginModalBlocks(
			document.getElementById('iwpAdminRecoverPass'),
			document.getElementById('iwpAdminLogin')
		);
	});

	// SHOW/HIDE LOGIN PASSWORD
	showHidePassword(
		document.getElementById('iwp-show-password-container'),
		document.getElementById('iwp-show-password'),
		document.getElementById('userPassword')
	);

	// SHOW/HIDE SIGN UP NEW PASSWORD
	showHidePassword(
		document.getElementById('iwp-show-new-password-container'),
		document.getElementById('iwp-show-new-password'),
		document.getElementById('userNewPassword')
	);

	// SHOW/HIDE SIGN UP RE-PASSWORD
	showHidePassword(
		document.getElementById('iwp-show-new-password-confirm-container'),
		document.getElementById('iwp-show-new-password-confirm'),
		document.getElementById('userNewPasswordConfirm')
	);

	// SHOW/HIDE LOGIN CUSTOM DOMAIN
	document.getElementById('userDomainCheckbox').addEventListener("click", (e) => {
		let divCustomDomain = document.getElementById("customDomain");
		if (e.target.checked === true) {
			divCustomDomain.classList.remove('iwp-hide');
		} else {
			divCustomDomain.classList.add('iwp-hide');
			document.getElementById("userDomain").value = '';
		}
	});

	// SHOW/HIDE RECOVER PASS CUSTOM DOMAIN
	document.getElementById('recoverPassUserDomainCheckbox').addEventListener("click", (e) => {
		let divCustomDomain = document.getElementById("recoverPassCustomDomain");
		if (e.target.checked === true) {
			divCustomDomain.classList.remove('iwp-hide');
		} else {
			divCustomDomain.classList.add('iwp-hide');
			document.getElementById("recoverPassUserDomain").value = '';
		}
	});

	//CLOSE LOGIN MODAL
	document.getElementById('iwpAdminLoginModalClose').addEventListener("click", () => {
		showHideLoginModalBlock(document.getElementById('iwpAdminLogin'), false);
		showHideLoginModalBlock(document.getElementById('iwpAdminModalLogin'), false);
	});

	//CLOSE LOGIN MODAL
	document.getElementById('iwpAdminLoginModalCloseSignUp').addEventListener("click", () => {
		showHideLoginModalBlock(document.getElementById('iwpAdminSignUp'), false);
		showHideLoginModalBlock(document.getElementById('iwpAdminModalLogin'), false);
	});

	//CLOSE LOGIN MODAL
	document.getElementById('iwpAdminLoginModalCloseRecoverPass').addEventListener("click", () => {
		showHideLoginModalBlock(document.getElementById('iwpAdminRecoverPass'), false);
		showHideLoginModalBlock(document.getElementById('iwpAdminModalLogin'), false);
	});

	// LOGIN SUBMIT
	document.getElementById('loginSubmit').addEventListener("click", function() {
		submitLoginAjax();
	});

	// SIGN UP SUBMIT
	document.getElementById('signUpSubmit').addEventListener("click", function() {
		submitSignUpAjax();
	});

	// CLICK BUTTON TO RECOVER PASSWORD
	document.getElementById('iwpRecoverPassSubmit').addEventListener("click", function() {
		recoverPassAjax();
	});

	// CLICK BUTTON TO CLOSE 2FA MODAL
	document.getElementById('iwp2FaSubmit').addEventListener("click", function() {
		submit2FaAjax();
	});

	// CLICK BUTTON TO RENEW 2FA CODE
	document.getElementById('iwp2FaRenewCode').addEventListener("click", function() {
		refresh2FaAjax();
	});

	// CLICK BUTTON TO CLOSE 2FA MODAL AND RETURN TO LOGIN
	document.getElementById('iwpAdminLoginModalClose2FA').addEventListener("click", function() {
		totalTime = 0;
		updateClock();
		switchLoginModalBlocks(
			document.getElementById('iwpAdmin2FA'),
			document.getElementById('iwpAdminLogin')
		);
	});

	// CLICK BUTTON TO CLOSE PROJECT SELECTION MODAL AND RETURN TO LOGIN
	document.getElementById('iwpAdminLoginModalCloseProjectSelection').addEventListener("click", function () {
		switchLoginModalBlocks(
			document.getElementById('iwpAdminProjectSelection'),
			document.getElementById('iwpAdminLogin')
		);
	});

	document.getElementById('selectService').addEventListener("click", async function () {
		await sendEvent(MICRO_PLUGIN_SELECCIONA_SERVICIO);
		showHideLoginModalBlock(document.getElementById('iwpAdminProjectSelection'), false);
		finishLogin();
	});
});