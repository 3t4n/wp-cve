document.addEventListener('DOMContentLoaded', function() {
    // Variables globales
    let totalTime;
    let timeExpired;

    removeReconnectError();

    // Definimos los listeners en funciones para que el scope de las variables esté limitada a sus funciones
    createCustomSelectById('whatsAppPhonePrefix', true);
    showHideLoginCustomDomain();
    showHideLoginPassword();
    showHideSignUpNewPassword();
    showHideSignUpNewPasswordConfirm();
    showHideSignUp();
    loginSubmit();
    signUpSubmit();
    goToStep3();
    selectWhatsAppChannel();
    selectWebPushChannel();
    phoneWhatsAppInput();
    finishOnBoarding();
    close2FaModal();
    submit2Fa();
    renew2FaCode();

    /***** FUNCIONES *****/

    function removeReconnectError() {
        let errorBox = document.getElementById('iwpReconnectErrorBox');
        if (errorBox) {
            setTimeout(function() {
                errorBox.remove();
            }, 20000);
        }
    }

    /**
     * SHOW/HIDE LOGIN CUSTOM DOMAIN
     */
    function showHideLoginCustomDomain() {
        let checkCustomDomain = document.getElementById('userDomainCheckbox');
        let divCustomDomain = document.getElementById("customDomain");
        let userDomain = document.getElementById("userDomain");
        if (checkCustomDomain && divCustomDomain && userDomain) {
            checkCustomDomain.addEventListener("click", function () {
                if (checkCustomDomain.checked === true) {
                    divCustomDomain.classList.remove('iwp-hide');
                } else {
                    divCustomDomain.classList.add('iwp-hide');
                    userDomain.value = '';
                }
            });
        }
    }

    /**
     * SHOW/HIDE LOGIN PASSWORD
     */
    function showHideLoginPassword() {
        let showPasswordContainer = document.getElementById('iwp-show-password-container');
        let showPassword = document.getElementById('iwp-show-password');
        let passwordInput = document.getElementById('userPassword');
        showHidePassword(showPasswordContainer, showPassword, passwordInput);
    }

    /**
     * SHOW/HIDE SIGNUP NEW PASSWORD
     */
    function showHideSignUpNewPassword() {
        let showPasswordContainer = document.getElementById('iwp-show-new-password-container');
        let showPassword = document.getElementById('iwp-show-new-password');
        let passwordInput = document.getElementById('userNewPassword');
        showHidePassword(showPasswordContainer, showPassword, passwordInput);
    }

    /**
     * SHOW/HIDE SIGNUP NEW PASSWORD CONFIRM
     */
    function showHideSignUpNewPasswordConfirm() {
        let showPasswordContainer = document.getElementById('iwp-show-new-password-confirm-container');
        let showPassword = document.getElementById('iwp-show-new-password-confirm');
        let passwordInput = document.getElementById('userNewPasswordConfirm');
        showHidePassword(showPasswordContainer, showPassword, passwordInput);
    }


    /**
     * SHOW/HIDE SIGN-UP
     */
    function showHideSignUp() {
        let showSignUpButton = document.getElementById('showSignUp');
        let showLoginButton = document.getElementById('showLogin');
        let loginView = document.getElementById('iwp-admin-onBoarding-login-view');
        let sigUpView = document.getElementById('iwp-admin-onBoarding-signup-view');
        if (showSignUpButton && showLoginButton && loginView && sigUpView) {
            showSignUpButton.addEventListener("click", function () {
                loginView.classList.add('iwp-hide');
                sigUpView.classList.remove('iwp-hide');
            });
            showLoginButton.addEventListener("click", function () {
                sigUpView.classList.add('iwp-hide');
                loginView.classList.remove('iwp-hide');
            });
        }
    }

    /**
     * LOGIN SUBMIT
     */
    function loginSubmit() {
        let loginSubmitButton = document.getElementById('loginSubmit');
        if (loginSubmitButton) {
            loginSubmitButton.addEventListener("click", function() {
                submitLoginAjax();
            });
        }
    }

    /**
     * SIGNUP SUBMIT
     */
    function signUpSubmit() {
        let signUpSubmitButton = document.getElementById('signUpSubmit');

        if (signUpSubmitButton) {
            signUpSubmitButton.addEventListener("click", function() {
                submitSignUpAjax();
            });
        }
    }

    /**
     * GOTO STEP 3
     */
    function goToStep3() {
        let nextButton = document.getElementById('selectService');
        if (nextButton) {
            nextButton.addEventListener("click", async function () {
                await sendEvent(MICRO_PLUGIN_SELECCIONA_SERVICIO);
                changeStep('channel');
            });
        }
    }

    /**
     * CLICK WHATSAPP-CHAT CHANNEL
     */
    function selectWhatsAppChannel() {
        let whatsAppChannel = document.getElementById('iwpWhatsAppChannel');
        let whatsAppChannelActive = document.getElementById('iwpChannelWhatsAppActive');
        let whatsAppChannelData = document.getElementById('iwpWhatsAppChannelData');

        if (whatsAppChannel && whatsAppChannelActive && whatsAppChannelData) {
            whatsAppChannel.addEventListener("click", function() {
                if (whatsAppChannel.classList.contains('selected')) {
                    whatsAppChannel.classList.remove('selected');
                    whatsAppChannelActive.value = '0';
                    whatsAppChannelData.classList.add('iwp-hide');
                } else {
                    whatsAppChannel.classList.add('selected');
                    whatsAppChannelActive.value = '1';
                    whatsAppChannelData.classList.remove('iwp-hide');
                }
                showHideStartButton();
            });
        }
    }

    /**
     * CLICK WEB-PUSH CHANNEL
     */
    function selectWebPushChannel() {
        let webPushChannel = document.getElementById('iwpWebPushChannel');
        let webPushChannelActive = document.getElementById('iwpChannelWebPushActive');
        let webPushChannelData = document.getElementById('iwpWebPushChannelData');

        if (webPushChannel && webPushChannelActive) {
            webPushChannel.addEventListener("click", function() {
                if (webPushChannel.classList.contains('selected')) {
                    webPushChannel.classList.remove('selected');
                    webPushChannelActive.value = '0';
                    webPushChannelData.classList.add('iwp-hide');
                } else {
                    webPushChannel.classList.add('selected');
                    webPushChannelActive.value = '1';
                    webPushChannelData.classList.remove('iwp-hide');
                }
                showHideStartButton();
            });
        }
    }

    /**
     * CLICK BUTTON TO FINISH ONBOARDING
     */
    function finishOnBoarding()  {
        let startButton = document.getElementById('onBoardingStart');
        if (startButton) {
            startButton.addEventListener("click", function() {
                finishOnBoardingAjax();
            });
        }
    }

    /**
     * FILTERS WHATSAPP-CHAT PHONE NUMBER ON INPUT
     */
    function phoneWhatsAppInput() {
        let input = document.getElementById('whatsAppPhone');
        if (input) {
            input.addEventListener("input", function() {
                input.value = input.value.replace(/\D/g, '');
            });
        }
    }

    /**
     * CLICK BUTTON TO CLOSE 2FA MODAL
     */
    function submit2Fa() {
        let close2FaModalButton = document.getElementById('iwp2FaSubmit');
        if (close2FaModalButton) {
            close2FaModalButton.addEventListener("click", function() {
                submit2FaAjax();
            });
        }
    }

    /**
     * CLICK BUTTON TO CLOSE 2FA MODAL
     */
    function close2FaModal() {
        let close2FaModalButton = document.getElementById('iwpTimes2FaModal');
        if (close2FaModalButton) {
            close2FaModalButton.addEventListener("click", function() {
                totalTime = 0;
                updateClock();
                document.getElementById('iwp2faModal').classList.add('iwp-hide');
            });
        }
    }

    /**
     * CLICK BUTTON TO RENEW 2FA CODE
     */
    function renew2FaCode() {
        let renew2FaButton = document.getElementById('iwp2FaRenewCode');
        if (renew2FaButton) {
            renew2FaButton.addEventListener("click", function() {
                refresh2FaAjax();
            });
        }
    }

    /***** FUNCIONES PARA SIGNUP *****/
    /**
     * AJAX CALL FOR SIGNUP SUBMIT
     */
    function submitSignUpAjax() {
        showHideLoader(true);

        let errorBox = document.getElementById('iwp-admin-error-box');
        errorBox.classList.add('iwp-hide');

        let errorInputs = document.getElementById('iwp-admin-onBoarding-signup-view').getElementsByClassName('iwp-admin-error-box');
        if (errorInputs.length) {
            Array.from(errorInputs).forEach((el) => {
                el.classList.remove('iwp-admin-error-box');
            });
        }

        const data = new FormData();
        data.append('action', 'iwp_signup');
        data.append('userNewEmail', document.getElementById('userNewEmail').value);
        data.append('userNewPassword', document.getElementById('userNewPassword').value);
        data.append('userNewPasswordConfirm', document.getElementById('userNewPasswordConfirm').value);
        data.append('confirmTermsCheckbox', document.getElementById('confirmTermsCheckbox').checked);
        data.append('confirmNewsletters', document.getElementById('confirmNewsletters').checked);

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => isResponseOk(response))
            .then((data) => {
                if (data.status === 1) {
                    // SignUp y LogIn correctos
                    // Intentamos obtener las aplicaciones
                    getApplicationList();
                } else {
                    errorBox.innerHTML = data.message;
                    errorBox.classList.remove('iwp-hide');
                    setTimeout(function () {
                        errorBox.classList.add('iwp-hide');
                        errorBox.innerHTML = '';
                    }, 10000);
                    showHideLoader(false);
                }
                if (data.status === 0) {
                    // Si el error es de tipo 0, es que el usuario se ha creado correctamente, pero no se ha
                    // podido iniciar la sesión. Y por eso cambiamos a la vista del login
                    document.getElementById('userEmail').value = document.getElementById('userNewEmail').value;
                    document.getElementById('userPassword').value = document.getElementById('userNewPassword').value;
                    document.getElementById('showLogin').click();
                }
                printConsoleLogOnDevelopMode(data);
            }).catch((err) => {
            // Error genérico
            printConsoleLogOnDevelopMode(err.message, true, true);
            showHideLoader(false);
        }).finally(() => {

        });
    }

    /***** FUNCIONES PARA LOGIN *****/
    /**
     * AJAX CALL FOR LOGIN SUBMIT
     */
    function submitLoginAjax() {
        showHideLoader(true);

        let errorBox = document.getElementById('iwp-admin-error-box');
        errorBox.classList.add('iwp-hide');
        errorBox.innerHTML = '';

        const data = new FormData();
        data.append('action', 'iwp_login');
        data.append('userEmail', document.getElementById('userEmail').value);
        data.append('userPassword', document.getElementById('userPassword').value);
        data.append('userDomain', document.getElementById('userDomain').value);
        data.append('userDomainCheckbox', document.getElementById('userDomainCheckbox').checked);

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => isResponseOk(response))
            .then((data) => {
                if (data.status === 1) {
                    // Intentamos obtener las aplicaciones
                    getApplicationList();
                } else if (data.status === 2) {
                    // Abrir modal 2FA
                    showHideLoader(false);
                    start2Fa();
                } else {
                    errorBox.innerHTML = data.message;
                    errorBox.classList.remove('iwp-hide');
                    setTimeout(function () {
                        errorBox.classList.add('iwp-hide');
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

    /***** FUNCIONES PARA 2FA *****/
    /**
     * AJAX CALL FOR 2FA SUBMIT
     */
    function submit2FaAjax() {
        let errorBox = document.getElementById('iwp-admin-2fa-error-box');
        errorBox.classList.add('iwp-hide');

        showHideLoader(true);

        const data = new FormData();
        data.append('action', 'iwp_submit_2fa');
        data.append('2Fa_token', document.getElementById('2FaCode').value);

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => isResponseOk(response))
            .then((data) => {
                if (data.status === 1) {
                    document.getElementById('iwp2faModal').classList.add('iwp-hide');
                    getApplicationList();
                } else {
                    showHideLoader(false);
                    errorBox.innerText = data.message;
                    errorBox.classList.remove('iwp-hide');
                    setTimeout(function () {
                        errorBox.classList.add('iwp-hide');
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
    function refresh2FaAjax() {
        let errorBox = document.getElementById('iwp-admin-2fa-error-box');
        errorBox.classList.add('iwp-hide');
        let infoBox = document.getElementById('iwp-admin-2fa-info-box');
        infoBox.classList.add('iwp-hide');

        showHideLoader(true);

        const data = new FormData();
        data.append('action', 'iwp_refresh_2fa');

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => isResponseOk(response))
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
                    errorBox.classList.remove('iwp-hide');
                    setTimeout(function () {
                        errorBox.classList.add('iwp-hide');
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
     * STARTS 2FA COUNTDOWN
     */
    function start2Fa() {
        let doubleFactorModal = document.getElementById('iwp2faModal');
        if (doubleFactorModal) {
            doubleFactorModal.classList.remove('iwp-hide');
            document.getElementById('iwp2FaSubmit').disabled = false;
            totalTime = 300;
            timeExpired = 0;
            updateClock();
        }
    }

    /**
     * UPDATES 2FA COUNTDOWN
     */
    function updateClock() {
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
     * FORMATS 2FA COUNTDOWN SECONDS TO HOUR FORMAT
     */
    function formatClock(seconds){
        let response = "00:";
        let minutes = Math.floor(seconds / 60);
        seconds = seconds % 60;
        response += PadLeft(minutes, 2) + ":" + PadLeft(seconds, 2);
        return response;
    }

    /**
     * ADDS 0 IF NUMBER HAS ONLY 1 DIGIT
     */
    function PadLeft(value, length) {
        return (value.toString().length < length) ? PadLeft("0" + value, length) : value;
    }

    /***** FUNCIONES FINALIZAR ONBOARDING *****/
    /**
     * PREPARES DATA FOR AJAX CALL FOR ONBOARDING ENDING
     */
    function finishOnBoardingAjax() {
        let applicationId, applicationPkey, applicationName, channelWhatsAppActive, channelWebPushActive, whatsAppPrefix, whatsAppPhone;
        try {
            let applicationSelect = document.getElementById('iwpApplicationId');
            let application = applicationSelect.options[applicationSelect.selectedIndex];
            applicationId = application.value;
            applicationPkey= application.getAttribute('data-pkey');
            applicationName= application.getAttribute('data-pkname');

            channelWhatsAppActive = document.getElementById('iwpChannelWhatsAppActive').value;
            channelWebPushActive = document.getElementById('iwpChannelWebPushActive').value;

            let prefixSelect = document.getElementById('whatsAppPhonePrefix');
            let prefix = prefixSelect.options[prefixSelect.selectedIndex];
            whatsAppPrefix = prefix.value;
            whatsAppPhone = document.getElementById('whatsAppPhone').value;
        } catch (err) {
            // No es habitual llegar aquí. Si llega, es que han tocado el html manualmente
            printConsoleLogOnDevelopMode(err.message, true, true);
        }
        const data = new FormData();
        data.append('action', 'iwp_finish_onBoarding');
        data.append('applicationId', applicationId);
        data.append('applicationPkey', applicationPkey);
        data.append('applicationName', applicationName);
        data.append('channelWhatsAppActive', channelWhatsAppActive);
        data.append('channelWebPushActive', channelWebPushActive);
        data.append('whatsAppPrefix', whatsAppPrefix);
        data.append('whatsAppPhone', whatsAppPhone);
        sendOnBoardingData(data);
    }

    /**
     * AJAX CALL FOR ONBOARDING ENDING
     */
    function sendOnBoardingData(data) {
        let errorBox = document.getElementById('iwp-admin-step3-error-box');
        errorBox.classList.add('iwp-hide');
        showHideLoader(true);

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => isResponseOk(response))
            .then((data) => {
                if (data.status === 1) {
                    // Recarga la página para entrar al plugin
                    window.location.reload();
                } else {
                    errorBox.innerHTML = data.message;
                    errorBox.classList.remove('iwp-hide');
                    setTimeout(function () {
                        errorBox.classList.add('iwp-hide');
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

    /***** FUNCIONES COMPLEMENTARIAS *****/
    /**
     * SHOW/HIDE ONBOARDING ENDING BUTTON
     */
    function showHideStartButton() {
        let whatsAppChannelActive = document.getElementById('iwpChannelWhatsAppActive');
        let webPushChannelActive = document.getElementById('iwpChannelWebPushActive');
        let onBoardingStart = document.getElementById('onBoardingStartContainer');
        if (whatsAppChannelActive && webPushChannelActive && onBoardingStart) {
            if ((whatsAppChannelActive.value === '1') || (webPushChannelActive.value === '1')) {
                onBoardingStart.classList.remove('iwp-hide');
            } else {
                onBoardingStart.classList.add('iwp-hide');
            }
        }
    }

    /**
     * AJAX CALL TO GET USERS APPS IN OPTION TAG HTML FORMATO
     */
    function getApplicationList() {
        let errorBox = document.getElementById('iwp-admin-error-box');
        const data = new FormData();
        data.append('action', 'iwp_get_applications');

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => isResponseOk(response))
            .then(async (data) => {
                if (data.status === 1) {
                    // Abrir paso 2
                    document.getElementById('iwpApplicationId').innerHTML = data.options;
                    createCustomSelectById('iwpApplicationId');
                    if (data.hasOwnProperty('totalOptions') && data.totalOptions === 1) {
                        await sendEvent(MICRO_PLUGIN_SELECCIONA_SERVICIO);
                        changeStep('service');
                        changeStep('channel');
                    } else {
                        changeStep('service');
                    }
                } else {
                    errorBox.innerHTML = data.message;
                    errorBox.classList.remove('iwp-hide');
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
     * STEP CHANGE
     */
    function changeStep(newStep) {
        let boxes = document.getElementsByClassName('iwp-admin-onBoarding-box');
        Array.from(boxes).forEach((box) => {
            let step = box.dataset.step;
            if (step === newStep) {
                box.classList.add('selected');
            } else {
                if (box.classList.contains('selected')) {
                    box.classList.add('completed');
                    box.classList.remove('selected');
                }
            }
        });
    }

    /**
     * SHOW/HIDE PASSWORD CHARACTERS
     */
    function showHidePassword(container, icon, input) {
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
});