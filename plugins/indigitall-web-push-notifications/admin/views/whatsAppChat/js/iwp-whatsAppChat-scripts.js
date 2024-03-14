document.addEventListener('DOMContentLoaded', function() {
    let defaultColor = '#00bb2d';
    let resetColorLabel = 'Reset color';
    if (ADMIN_PARAMS.hasOwnProperty('resetColorLabel')) {
        resetColorLabel = ADMIN_PARAMS.resetColorLabel;
    }
    addResetToColorInput();
    switchWhatsAppChatStatus();
    saveWhatsAppChatConfig();
    showAdvanceSettings();

    function addResetToColorInput() {
        Array.from(document.querySelectorAll('input.iwp-admin-whatsAppChat-color')).forEach(function(colorInput) {
            let parent = document.createElement('div');
            parent.classList.add('iwp-admin-whatsAppChat-color-parent');
            colorInput.parentNode.insertBefore(parent, colorInput);
            parent.appendChild(colorInput);

            let resetColorIcon = document.createElement('img');
            resetColorIcon.classList.add('iwp-admin-whatsAppChat-color-reset');
            resetColorIcon.src = '/wp-content/plugins/indigitall-web-push-notifications/admin/images/reload-icon.svg';
            resetColorIcon.setAttribute('title', resetColorLabel);
            parent.appendChild(resetColorIcon);

            resetColorIcon.addEventListener("click", function () {
                let defaultInputColor = colorInput.getAttribute('data-default-color');
                colorInput.value = defaultInputColor ? defaultInputColor : defaultColor;
                colorInput.dispatchEvent(new CustomEvent("reset-color-default", {
                    detail: {},
                    bubbles: false,
                    composed: false
                }));
            });
        });
    }

    function showAdvanceSettings() {
        let whatsAppChatAdvanceSettingsSwitch = document.getElementById('iwpWhatsAppChatShowAdvanceSettings');
        let whatsAppChatAdvanceSettingsStatus = whatsAppChatAdvanceSettingsSwitch.querySelector('.iwp-admin-switch-value');
        if (whatsAppChatAdvanceSettingsSwitch) {
            whatsAppChatAdvanceSettingsSwitch.addEventListener("click", function (e) {
                const advSettings = document.getElementById('iwpWhatsAppChatAdvanceSettings');
                if (whatsAppChatAdvanceSettingsStatus && whatsAppChatAdvanceSettingsStatus.value === '0') {
                    slideDown(advSettings);
                } else {
                    slideUp(advSettings);
                }
                toggleSwitch(whatsAppChatAdvanceSettingsSwitch, false, 'deactivated', 'activated');
            });
        }
    }

    function switchWhatsAppChatStatus() {
        let whatsAppChatSwitch = document.getElementById('iwpWhatsAppChatStatusSwitch');
        let whatsAppChatStatus = whatsAppChatSwitch.querySelector('.iwp-admin-switch-value');
        if (whatsAppChatSwitch) {
            whatsAppChatSwitch.addEventListener("click", function (e) {
                if (whatsAppChatStatus && whatsAppChatStatus.value === '0') {
                    sendWhatsAppChatConfig(true);
                } else {
                    sendWhatsAppChatStatusAjax();
                }
            });
        }
    }

    function saveWhatsAppChatConfig() {
        let submitButton = document.getElementById('iwpAdminWhatsAppChatSave');
        submitButton.addEventListener("click", function () {
            sendWhatsAppChatConfig();
        });
    }

    function sendWhatsAppChatConfig(changeStatus = false) {
        let whatsAppChatSwitch = document.getElementById('iwpWhatsAppChatStatusSwitch');
        let whatsAppChatStatus = whatsAppChatSwitch.querySelector('.iwp-admin-switch-value');
        let switchValue = changeStatus ? !(whatsAppChatStatus.value === '1') : (whatsAppChatStatus.value === '1');

        let prefix = document.getElementById('adminWhPhonePrefix');
        let phone = document.getElementById('adminWhPhone');
        let mainWelcome = document.getElementById('adminWhChatWelcomeMessage');

        let iconOption = document.querySelector('input[name="adminWhIconOption"]:checked');
        let position = document.querySelector('input[name="adminWhPositionValue"]:checked');
        let iconColor = document.getElementById('adminWhIconColor');
        let iconsContainer = document.getElementById('adminWhIconType');
        let iconImage = iconsContainer.querySelector('input[name="adminWhIconImage"]:checked');
        let iconCustomImage = document.getElementById('adminWhIconImageCustom');
        let transparent = document.getElementById('adminWhIconTransparent');
        let bubbleShow = document.getElementById('adminWhIconBalloonShow');
        let bubbleHover = document.getElementById('adminWhIconBalloonHover');
        let iconBalloon = bubbleShow.checked ? 'show' : 'none'; // Asignamos 'none' si no queremos activar algún bocadillo
        iconBalloon = bubbleHover.checked ? 'hover' : iconBalloon;
        let bubbleText = document.getElementById('adminWhChatBalloonText');
        let sleepIcon = document.getElementById('adminWhChatIconSleep');

        let chatType = document.querySelector('input[name="adminWhChatType"]:checked');
        let buttonsContainer = document.getElementById('adminWhButtonType');
        let chatHeader = document.getElementById('adminWhChatHeader');
        let chatWelcome = document.getElementById('adminWhChatWelcome');
        let themeColor = document.getElementById('adminWhThemeColor');
        let buttonText = document.getElementById('adminWhChatButtonText');
        let buttonImage = buttonsContainer.querySelector('input[name="adminWhChatButtonImage"]:checked');
        let sleepChat = document.getElementById('adminWhChatSleep');

        let qrHeader = document.getElementById('adminWhQrHeader');
        let qrText = document.getElementById('adminWhQrText');
        let qrColor = document.getElementById('adminWhQrColor');

        // Comprobación teléfono
        if (!sendCheckPhone(phone, switchValue)) {
            return true;
        }
        // Comprobamos el campo de sleep icon
        if (!sendCheckSleep(sleepIcon, switchValue)) {
            return true;
        }
        // Comprobamos el campo de sleep chat
        if (!sendCheckSleep(sleepChat, switchValue)) {
            return true;
        }

        showHideLoader(true);

        const data = new FormData();
        data.append('action', 'iwp_wh_save');
        data.append('phone', phone.value);
        data.append('countriesPrefixOptions', prefix.value);
        data.append('welcomeMessage', mainWelcome.value);

        data.append('iconOption', iconOption.value);
        data.append('iconPosition', position.value);
        data.append('iconColor', iconColor.value);
        data.append('iconImageId', iconCustomImage.value);
        data.append('iconImage', iconImage.value);
        data.append('iconTransparent', transparent.checked);
        data.append('iconBalloon', iconBalloon);
        data.append('iconBalloonText', bubbleText.value);
        data.append('iconSleep', sleepIcon.value);

        data.append('chatType', chatType.value);
        data.append('chatHeaderValue', chatHeader.value);
        data.append('chatBodyValue', chatWelcome.value);
        data.append('themeColor', themeColor.value);
        data.append('chatButtonTextValue', buttonText.value);
        data.append('buttonIcon', buttonImage.value);
        data.append('chatSleep', sleepChat.value);

        data.append('qrHeader', qrHeader.value);
        data.append('qrText', qrText.value);
        data.append('qrColor', qrColor.value);

        let errorBox = document.getElementById('iwp-admin-error-box');
        let successAlert = document.getElementById('iwp-admin-success-box');
        errorBox.classList.add('iwp-hide');
        successAlert.classList.add('iwp-hide');

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => isResponseOk(response))
            .then((data) => {
                printConsoleLogOnDevelopMode(data);
                window.scrollTo(0, 0);

                let box = (data.status === 1) ? successAlert : errorBox;
                if ((data.status === 0) || !changeStatus) {
                    box.innerText = data.message;
                    box.classList.remove('iwp-hide');
                    setTimeout(function () {
                        box.classList.add('iwp-hide');
                        box.innerText = '';
                    }, 5000);
                }

                if ((data.status === 1) && changeStatus) {
                    sendWhatsAppChatStatusAjax();
                }
            }).catch((err) => {
                // Error genérico
                printConsoleLogOnDevelopMode(err.message, true, true);
        }).finally(() => {
            showHideLoader(false);
        });
    }

    function sendWhatsAppChatStatusAjax() {
        let whatsAppChatSwitch = document.getElementById('iwpWhatsAppChatStatusSwitch');
        let whatsAppChatStatus = whatsAppChatSwitch.querySelector('.iwp-admin-switch-value');

        if (whatsAppChatSwitch && whatsAppChatStatus) {
            toggleSwitch(whatsAppChatSwitch, true, 'disabled', 'enabled');
            if (!whatsAppChatSwitch.classList.contains('blocked')) {
                whatsAppChatSwitch.classList.add('blocked');

                const data = new FormData();
                data.append('action', 'iwp_toggle_wh_status');
                data.append('status', whatsAppChatStatus.value);

                fetch(iwpAjaxUrl, {
                    method: "POST",
                    credentials: "same-origin",
                    body: data
                }).then((response) => {
                    changeWhatsAppChatStatus(whatsAppChatStatus.value);

                    const msg = {
                        'Status code:': response.status,
                        'Changed WhatsAppChat status to:': whatsAppChatStatus.value,
                        'Request Response': response.statusText
                    }
                    printConsoleLogOnDevelopMode(msg);
                }).catch((err) => {
                    // Error genérico
                    printConsoleLogOnDevelopMode(err.message, true, true);
                }).finally(() => {
                    setTimeout(function () {
                        whatsAppChatSwitch.classList.remove('blocked');
                    }, 2000);
                });
            }
        }
    }

    function changeWhatsAppChatStatus(newValue) {
        let mainMenuItem = document.getElementById('iwp-admin-main-menu-channel-whatsAppChat');
        let menuItemStatus = mainMenuItem.querySelector('.iwp-admin-main-menu-item-status');

        if (newValue === '1') {
            menuItemStatus.classList.remove('deactivated');
            menuItemStatus.classList.add('activated');
        } else {
            menuItemStatus.classList.remove('activated');
            menuItemStatus.classList.add('deactivated');
        }
    }

    function sendCheckPhone(phone, switchValue) {
        let phoneEmpty = document.getElementById('adminWhPhoneEmpty');
        let phoneError = document.getElementById('adminWhPhoneError');

        phone.value = phone.value.replace(/\D/, '');

        phoneEmpty.classList.add('iwp-hide');
        phoneError.classList.add('iwp-hide');

        const phonePattern = /^\d{7,}$/;
        phone.classList.remove('iwp-input-error');
        if (switchValue && !phonePattern.test(phone.value)) {
            if (phone.value === '') {
                phoneEmpty.classList.remove('iwp-hide');
            } else {
                phoneError.classList.remove('iwp-hide');
            }
            phone.classList.add('iwp-input-error');
            if(document.querySelector('.iwp-admin-switch-value').value === '1') {
                phone.scrollIntoView();
            }
            return false;
        }
        return true;
    }

    function sendCheckSleep(sleep, switchValue) {
        let parent = sleep.closest('.iwp-admin-form-group');
        let sleepError = parent.querySelector('.iwp-admin-whatsAppChat-tiny-error');
        let newValue = sleep.value.replace(/-\D/g, '');
        sleep.value = newValue;

        let advancedSettingsValue = document.getElementById('iwpWhatsAppChatShowAdvanceSettings').querySelector('.iwp-admin-switch-value').value;
        let hideError = document.getElementById('adminWhHideError');
        hideError.classList.add('iwp-hide');

        sleepError.classList.add('iwp-hide');
        const pattern = /^(-)?\d+$/;
        sleep.classList.remove('iwp-input-error');
        if (switchValue && !pattern.test(newValue)) {

            if (advancedSettingsValue === '0') {
                hideError.classList.remove('iwp-hide');
            }

            sleep.classList.add('iwp-input-error');
            sleepError.classList.remove('iwp-hide');
            parent.scrollIntoView();
            return false;
        }
        return true;
    }
});