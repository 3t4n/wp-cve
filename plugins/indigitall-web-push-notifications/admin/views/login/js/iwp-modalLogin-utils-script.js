	let totalTime;
	let timeExpired;

	/**
	 * ADDS 0 IF NUMBER HAS ONLY 1 DIGIT
	 */
	const PadLeft = (value, length) => {
		return (value.toString().length < length) ? PadLeft("0" + value, length) : value;
	}

	/**
	 * FORMATS 2FA COUNTDOWN SECONDS TO HOUR FORMAT
	 */
	const formatClock = (seconds) => {
		let response = "00:";
		let minutes = Math.floor(seconds / 60);
		seconds = seconds % 60;
		response += PadLeft(minutes, 2) + ":" + PadLeft(seconds, 2);
		return response;
	}

	/**
	 * UPDATES 2FA COUNTDOWN
	 */
	const updateClock = (reload = false) => {
		if (reload) {
			totalTime = 300;
			timeExpired = 0;
		}
		document.getElementById('iwp2FaCounter').innerHTML = formatClock(totalTime);
		if(totalTime <= 0) {
			document.getElementById('iwp2FaSubmit').disabled = true;
			timeExpired = 1;
		}else{
			totalTime -= 1;
			setTimeout(updateClock,1000);
		}
	}

	/**
	 * STARTS 2FA COUNTDOWN
	 */
	const start2Fa = () => {
		switchLoginModalBlocks(
			document.getElementById('iwpAdminLogin'),
			document.getElementById('iwpAdmin2FA')
		);
		document.getElementById('iwp2FaSubmit').disabled = false;
		totalTime = 300;
		timeExpired = 0;
		updateClock();
	}

	/**
	 * TOGGLE LOGIN BLOCKS DISPLAY
	 */
	const switchLoginModalBlocks = (hideBlock, showBlock) => {
		hideBlock.classList.remove('iwp-show-modal');
		setTimeout(() => {
			hideBlock.style.display = 'none';
			showBlock.style.display = 'flex';
			setTimeout(() => {
				showBlock.classList.add('iwp-show-modal');
			}, 100);
		}, 300);
	}

	const showHideLoginModalBlock = (block, show = true) => {
		if (show) {
			block.style.display = 'flex';
			setTimeout(() => {
				block.classList.add('iwp-show-modal');
			}, 300);
		} else {
			block.classList.remove('iwp-show-modal');
			setTimeout(() => {
				block.style.display = 'none';
			}, 300);
		}
	}

	/**
	 * SHOW/HIDE PASSWORD CHARACTERS
	 */
	const showHidePassword = (container, icon, input) => {
		if (container && icon && input) {
			icon.addEventListener("click", function () {
				if (container.classList.contains('iwp-password-is-hide')) {
					container.classList.remove('iwp-password-is-hide');
					input.type = 'text';
				} else {
					container.classList.add('iwp-password-is-hide');
					input.type = 'password';
				}
			});
		}
	}

	/*** AJAX CALLS ***/
	/**
	 * AJAX CALL FOR LOGIN SUBMIT
	 */
	const submitLoginAjax = () => {
		showHideLoader(true);

		let errorBox = document.getElementById('iwp-admin-login-error-box');
		errorBox.innerHTML = '';

		const data = new FormData();
		data.append('action', 'iwp_login');
		data.append('userEmail', document.getElementById('userEmail').value);
		data.append('userPassword', document.getElementById('userPassword').value);
		data.append('userDomain', document.getElementById('userDomain').value);
		data.append('userDomainCheckbox', document.getElementById('userDomainCheckbox').checked);

		fetch(iwpAjaxUrl, {method: "POST", credentials: "same-origin", body: data})
			.then((response) => isResponseOk(response))
			.then((data) => {
				if (data.status === 1) {
					// Intentamos obtener las aplicaciones
					showHideLoginModalBlock(document.getElementById('iwpAdminLogin'), false);
					getApplicationList();
				} else if (data.status === 2) {
					// Abrir modal 2FA
					showHideLoader(false);
					start2Fa();
				} else {
					errorBox.innerHTML = data.message;
					setTimeout(function () {
						errorBox.innerHTML = '';
					}, 20000);
					showHideLoader(false);
				}
				printConsoleLogOnDevelopMode(data);
			}).catch((err) => {
			// Error genérico
			printConsoleLogOnDevelopMode(err.message, true, true);
			showHideLoader(false);
		}).finally(() => {

		});
	}

	/**
	 * AJAX CALL FOR SIGNUP SUBMIT
	 */
	function submitSignUpAjax() {
		showHideLoader(true);

		let errorBox = document.getElementById('iwp-admin-sign-up-info-box');
		errorBox.innerHTML = '';

		let errorInputs = document.querySelectorAll('.iwp-color-error');
		if (errorInputs.length) {
			Array.from(errorInputs).forEach((el) => {
				el.classList.remove('iwp-color-error');
			});
		}

		const data = new FormData();
		data.append('action', 'iwp_signup');
		data.append('userNewEmail', document.getElementById('userNewEmail').value);
		data.append('userNewPassword', document.getElementById('userNewPassword').value);
		data.append('userNewPasswordConfirm', document.getElementById('userNewPasswordConfirm').value);
		data.append('confirmTermsCheckbox', document.getElementById('confirmTermsCheckbox').checked);
		data.append('confirmNewsletters', document.getElementById('confirmNewsletters').checked);

		fetch(iwpAjaxUrl, {method: "POST", credentials: "same-origin", body: data})
			.then((response) => isResponseOk(response))
			.then((data) => {
				if (data.status === 1) {
					// SignUp y LogIn correctos
					// Intentamos obtener las aplicaciones
					getApplicationList();
				} else {
					if (data.status === 0) {
						// Si el error es de tipo 0, es que el usuario se ha creado correctamente, pero no se ha
						// podido iniciar la sesión. Y por eso cambiamos a la vista del login
						document.getElementById('userEmail').value = document.getElementById('userNewEmail').value;
						document.getElementById('userPassword').value = document.getElementById('userNewPassword').value;
						switchLoginModalBlocks(
							document.getElementById('iwpAdminSignUp'),
							document.getElementById('iwpAdminLogin')
						);
						errorBox = document.getElementById('iwp-admin-login-error-box');
					}
					if (data.hasOwnProperty('fields')) {
						for (const [key, value] of Object.entries(data.fields)) {
							if (value) {
								if (document.getElementById(key).getAttribute('type') === 'checkbox') {
									document.getElementById(key).parentElement.classList.add('iwp-color-error');
								} else {
									document.getElementById(key).classList.add('iwp-color-error');
								}
							}
						}
					}

					errorBox.innerHTML = data.message;
					setTimeout(function () {
						errorBox.innerHTML = '';
					}, 10000);
					showHideLoader(false);
				}
				printConsoleLogOnDevelopMode(data);
			}).catch((err) => {
				// Error genérico
				printConsoleLogOnDevelopMode(err.message, true, true);
				showHideLoader(false);
			}).finally(() => {

		});
	}

	const recoverPassAjax = () => {
		let emailInput = document.getElementById('recoverPassUserEmail');
		emailInput.classList.remove('iwp-color-error');
		let email = emailInput.value.trim();
		if (!validateEmail(email)) {
			emailInput.classList.add('iwp-color-error');
		} else {
			showHideLoader(true);
			let errorBox = document.getElementById('iwp-admin-recover-pass-error-box');
			errorBox.innerHTML = '';
			let infoBox = document.getElementById('iwp-admin-login-info-box');
			infoBox.innerHTML = '';

			const data = new FormData();
			data.append('action', 'iwp_recover_pass');
			data.append('recoverPassUserEmail', document.getElementById('recoverPassUserEmail').value);
			data.append('recoverPassUserDomain', document.getElementById('recoverPassUserDomain').value);
			data.append('recoverPassUserDomainCheckbox', document.getElementById('recoverPassUserDomainCheckbox').checked);


			fetch(iwpAjaxUrl, {method: "POST", credentials: "same-origin", body: data})
				.then((response) => isResponseOk(response))
				.then(async (data) => {
					if (data.status === 1) {
						// Envío correcto
						switchLoginModalBlocks(
							document.getElementById('iwpAdminRecoverPass'),
							document.getElementById('iwpAdminLogin')
						);
						infoBox.innerHTML = data.message;
						setTimeout(function () {
							infoBox.innerHTML = '';
						}, 10000);
					} else {
						// Error en el envío
						errorBox.innerHTML = data.message;
						setTimeout(function () {
							errorBox.innerHTML = '';
						}, 10000);
					}
					printConsoleLogOnDevelopMode(data);
				}).catch((err) => {
				// Error genérico
				printConsoleLogOnDevelopMode(err.message, true, true);
			}).finally(() => {
				showHideLoader(false);
			});
		}
	}

	/**
	 * AJAX CALL TO GET USERS APPS IN OPTION TAG HTML FORMATO
	 */
	const getApplicationList = () => {
		const data = new FormData();
		data.append('action', 'iwp_get_applications');

		fetch(iwpAjaxUrl, {method: "POST", credentials: "same-origin", body: data})
			.then((response) => isResponseOk(response))
			.then(async(data) => {
				if (data.status === 1) {
					// Obtenemos el listado de proyectos
					document.getElementById('iwpApplicationId').innerHTML = data.options;
					createCustomSelectById('iwpApplicationId');
					if (data.hasOwnProperty('totalOptions') && data.totalOptions === 1) {
						// Terminamos el login
						await sendEvent(MICRO_PLUGIN_SELECCIONA_SERVICIO);
						finishLogin();
					} else {
						// Mostramos la ventana para seleccionar el proyecto
						showHideLoginModalBlock(document.getElementById('iwpAdminProjectSelection'), true);
					}
				} else {
					showHideLoginModalBlock(document.getElementById('iwpAdminLogin'), true);
					document.getElementById('iwp-admin-login-error-box').innerHTML = data.message;
				}
				printConsoleLogOnDevelopMode(data);
			}).catch((err) => {
			// Error genérico
			printConsoleLogOnDevelopMode(err.message, true, true);
		}).finally(() => {
			showHideLoader(false);
		});
	}

	/**
	 * AJAX CALL FOR 2FA SUBMIT
	 */
	const submit2FaAjax = () => {
		let errorBox = document.getElementById('iwp-admin-2fa-error-box');
		errorBox.innerHTML = '';

		showHideLoader(true);

		const data = new FormData();
		data.append('action', 'iwp_submit_2fa');
		data.append('2Fa_token', document.getElementById('2FaCode').value);

		fetch(iwpAjaxUrl, {method: "POST", credentials: "same-origin", body: data})
			.then((response) => isResponseOk(response))
			.then((data) => {
				if (data.status === 1) {
					showHideLoginModalBlock(document.getElementById('iwpAdmin2FA'), false);
					getApplicationList();
				} else {
					showHideLoader(false);
					errorBox.innerText = data.message;
					setTimeout(function () {
						errorBox.innerHTML = '';
					}, 5000);
				}
				printConsoleLogOnDevelopMode(data);
			}).catch((err) => {
			// Error genérico
			printConsoleLogOnDevelopMode(err.message, true, true);
			showHideLoader(false);
		}).finally(() => {

		});
	}

	/**
	 * AJAX CALL FOR REFRESH 2FA
	 */
	const refresh2FaAjax = () => {
		let errorBox = document.getElementById('iwp-admin-2fa-error-box');
		errorBox.innerHTML = '';
		let infoBox = document.getElementById('iwp-admin-2fa-info-box');
		infoBox.innerHTML = '';

		showHideLoader(true);

		const data = new FormData();
		data.append('action', 'iwp_refresh_2fa');

		fetch(iwpAjaxUrl, {method: "POST", credentials: "same-origin", body: data})
			.then((response) => isResponseOk(response))
			.then((data) => {
				if (data.status === 1) {
					totalTime = 300;
					timeExpired = 0;
					updateClock();
					infoBox.innerText = data.message;
					infoBox.classList.remove('iwp-hide');
					setTimeout(function () {
						infoBox.classList.add('iwp-hide');
						infoBox.innerHTML = '';
					}, 5000);
					document.getElementById('iwp2FaSubmit').disabled = false;
				} else {
					errorBox.innerHTML = data.message;
					setTimeout(function () {
						errorBox.innerHTML = '';
					}, 5000);
				}
				printConsoleLogOnDevelopMode(data);
			}).catch((err) => {
			// Error genérico
			printConsoleLogOnDevelopMode(err.message, true, true);
		}).finally(() => {
			showHideLoader(false);
		});
	}

	/**
	 * AJAX CALL FOR LOGIN FINISH
	 */
	const finishLogin = () => {
		let applicationSelect = document.getElementById('iwpApplicationId');
		let application = applicationSelect.options[applicationSelect.selectedIndex];

		const data = new FormData();
		data.append('action', 'iwp_finish_onBoarding');
		data.append('applicationId', application.value);
		data.append('applicationPkey', application.getAttribute('data-pkey'));
		data.append('applicationName', application.getAttribute('data-pkname'));

		for (const [key, value] of Object.entries(getInfoObjFromExtraHiddenFieldForLogin())) {
			// Obtenemos todos los datos extra almacenados y los añadimos
			data.append(key, value.toString());
		}

		showHideLoader(true);

		fetch(iwpAjaxUrl, {method: "POST", credentials: "same-origin", body: data})
			.then((response) => isResponseOk(response))
			.then((data) => {
				if (data.status === 1) {
					// Recarga la página para que recargue toda la información
					window.location.reload();
				} else {
					showHideLoginModalBlock(document.getElementById('iwpAdminLogin'), true);
					let errorBox = document.getElementById('iwp-admin-login-error-box');
					errorBox.innerHTML = data.message;
					setTimeout(function () {
						errorBox.innerHTML = '';
					}, 5000);
				}
				printConsoleLogOnDevelopMode(data);
			}).catch((err) => {
			// Error genérico
			printConsoleLogOnDevelopMode(err.message, true, true);
		}).finally(() => {
			showHideLoader(false);
		});
	}
	/**
	 * Crea el input oculto donde almacenar los datos extra para el login y lo adjunta a la modal
	 * Si el campo ya existe, no se crea
	 * Devuelve el elemento entero
	 */
	const createIfNotExistsExtraHiddenFieldForLoginAndGetIt = () => {
		let fieldName = 'iwpLoginExtraFields';
		if (!document.getElementById(fieldName)) {
			let input = document.createElement('input');
			input.setAttribute('type', 'hidden');
			input.setAttribute('id', fieldName);
			input.setAttribute('name', fieldName);
			input.setAttribute('value', '{}');
			document.getElementById('iwpAdminModalLogin').appendChild(input);
		}
		return document.getElementById(fieldName);
	}
	/**
	 * Se almacena en el input oculto del login datos extra para el login.
	 * Si el input no existe, lo crea.
	 * Si ya existe un dato con el mismo nombre pasado, lo sobreescribirá
	 */
	const addInfoToExtraHiddenFieldForLogin = (name, value) => {
		let input = createIfNotExistsExtraHiddenFieldForLoginAndGetIt();
		let valuesObj = JSON.parse(input.value);
		valuesObj[name] = value;
		input.value = JSON.stringify(valuesObj);
	}
	/**
	 * Obtenemos el objeto con todos los datos extra almacenados en el input oculto del login.
	 * Si el input no existe o no tiene datos extra, devolverá un objeto vacío.
	 */
	const getInfoObjFromExtraHiddenFieldForLogin = () => {
		let input = createIfNotExistsExtraHiddenFieldForLoginAndGetIt();
		return JSON.parse(input.value);
	}
