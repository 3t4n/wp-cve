document.addEventListener('DOMContentLoaded', function() {
    loadChatPreview();
    previewChatChangeEvents();
    changeChatType();
    loadQrCode();
    resetThemeColor();

    function resetThemeColor() {
        let themeColor = document.getElementById('adminWhThemeColor');
        themeColor.addEventListener("reset-color-default", function () {
            changeChatPreview();
        });
        let qrColor = document.getElementById('adminWhQrColor');
        qrColor.addEventListener("reset-color-default", function () {
            changeChatPreview();
        });
    }

    function changeChatType() {
        Array.from(document.querySelectorAll('input[name="adminWhChatType"]')).forEach(function(radioButton) {
            radioButton.addEventListener("change", function (el) {
                let element = el.target;
                if (element.checked) { // Better safe than sorry
                    let chatHeader          = document.getElementById('iwpAdminWhatsappPreviewHeaderText');
                    let qrHeader            = document.getElementById('iwpAdminWhatsappPreviewQrHeaderText');
                    let qrForm              = document.getElementById('iwpAdminWhatsappQrForm');
                    let customizedForm      = document.getElementById('iwpAdminWhatsappCustomizedForm');
                    let previewContainer    = document.getElementById('iwpAdminWhatsappPreviewContainer');
                    let qrTitle             = document.getElementById('iwpAdminWhatsappPreviewQrTitle');
                    let qrCode              = document.getElementById('iwpAdminWhatsappPreviewQr');
                    let messageContainer    = document.getElementById('iwpAdminWhatsappPreviewMessageContainer');
                    let message             = document.getElementById('iwpAdminWhatsappPreviewMessage');
                    let icon                = document.getElementById('iwpAdminWhatsappPreviewIcon');

                    if (element.value === 'qr') {
                        messageContainer.classList.add('iwp-qr-container');
                        chatHeader.classList.add('iwp-hide');
                        customizedForm.classList.add('iwp-hide');
                        message.classList.add('iwp-hide');
                        icon.classList.add('iwp-hide');

                        qrHeader.classList.remove('iwp-hide');
                        qrForm.classList.remove('iwp-hide');
                        qrTitle.classList.remove('iwp-hide');
                        qrCode.classList.remove('iwp-hide');

                        previewContainer.classList.remove('iwp-hide');
                    } else if (element.value === 'customized') {
                        messageContainer.classList.remove('iwp-qr-container');
                        chatHeader.classList.remove('iwp-hide');
                        customizedForm.classList.remove('iwp-hide');
                        message.classList.remove('iwp-hide');
                        icon.classList.remove('iwp-hide');

                        qrHeader.classList.add('iwp-hide');
                        qrForm.classList.add('iwp-hide');
                        qrTitle.classList.add('iwp-hide');
                        qrCode.classList.add('iwp-hide');

                        previewContainer.classList.remove('iwp-hide');
                    } else {
                        messageContainer.classList.remove('iwp-qr-container');
                        chatHeader.classList.remove('iwp-hide');
                        customizedForm.classList.add('iwp-hide');
                        message.classList.add('iwp-hide');
                        icon.classList.add('iwp-hide');

                        qrHeader.classList.add('iwp-hide');
                        qrForm.classList.add('iwp-hide');
                        qrTitle.classList.add('iwp-hide');
                        qrCode.classList.add('iwp-hide');

                        previewContainer.classList.add('iwp-hide');
                    }
                    changeChatPreview();
                }
            });
        });
    }

    function loadQrCode() {
        updateQrCode();

        let prefix = document.getElementById('adminWhPhonePrefix');
        let phone = document.getElementById('adminWhPhone');
        let message = document.getElementById('adminWhChatWelcomeMessage');
        prefix.addEventListener("click-custom-select-item", function() {
            updateQrCode();
        });
        phone.addEventListener("change", function() {
            updateQrCode();
        });
        message.addEventListener("change", function() {
            updateQrCode();
        });


        function updateQrCode() {
            let prefix = document.getElementById('adminWhPhonePrefix');
            let phone = document.getElementById('adminWhPhone');
            let message = document.getElementById('adminWhChatWelcomeMessage');
            if (prefix && phone && message && (phone.value.trim() !== '')) {
                let prefixValue = prefix.value.trim();
                let phoneValue = prefixValue + phone.value.trim();
                let welcomeMessage = message.value.trim();
                let link = `https://wa.me/${phoneValue}?text=${welcomeMessage}`;
                printConsoleLogOnDevelopMode(`QR code link: ${link}`);
                generateQR(link, 'iwp-QR-code');
            } else {
                document.getElementById('iwp-QR-code').innerHTML = '';
            }
        }
    }

    function previewChatChangeEvents() {
        let qrHeader = document.getElementById('adminWhQrHeader');
        let qrText = document.getElementById('adminWhQrText');
        let qrColor = document.getElementById('adminWhQrColor');
        let header = document.getElementById('adminWhChatHeader');
        let welcome = document.getElementById('adminWhChatWelcome');
        let color = document.getElementById('adminWhThemeColor');
        let buttonText = document.getElementById('adminWhChatButtonText');
        let icons = document.querySelectorAll('input[name="adminWhChatButtonImage"]');
        let sleepChat = document.getElementById('adminWhChatSleep');

        qrHeader.addEventListener("change", function () {
            changeChatPreview();
        });
        qrText.addEventListener("change", function () {
            changeChatPreview();
        });
        qrColor.addEventListener("change", function () {
            changeChatPreview();
        });
        header.addEventListener("change", function () {
            changeChatPreview();
        });
        welcome.addEventListener("change", function () {
            changeChatPreview();
        });
        color.addEventListener("change", function () {
            changeChatPreview();
        });
        buttonText.addEventListener("change", function () {
            changeChatPreview();
        });
        Array.from(icons).forEach(function (position) {
            position.addEventListener("click", function () {
                changeChatPreview();
            });
        });
        sleepChat.addEventListener("input", function () {
            sleepChat.value = sleepChat.value.replace(/\D-/g, '');
        });
        sleepChat.addEventListener("change", function () {
            let sleepError = document.getElementById('adminWhIconSleepError');
            let newValue = sleepChat.value.replace(/\D-/g, '');
            sleepChat.value = newValue;

            sleepError.classList.add('iwp-hide');
            const pattern = /^(-)?\d+$/;

            if (!pattern.test(newValue)) {
                sleepError.classList.remove('iwp-hide');
            }
        });
    }

    function changeChatPreview() {
        let iconsContainer = document.getElementById('adminWhButtonType');

        let chatType            = document.querySelector('input[name="adminWhChatType"]:checked');

        let header              = document.getElementById('adminWhChatHeader');
        let welcome             = document.getElementById('adminWhChatWelcome');
        let color               = document.getElementById('adminWhThemeColor');
        let buttonText          = document.getElementById('adminWhChatButtonText');
        let icon                = iconsContainer.querySelector('input[name="adminWhChatButtonImage"]:checked');
        let iconBackgrounds     = iconsContainer.querySelectorAll('.iwp-admin-whatsAppChat-icon-background');
        let qrHeader            = document.getElementById('adminWhQrHeader');
        let qrText              = document.getElementById('adminWhQrText');
        let qrColor             = document.getElementById('adminWhQrColor');

        let previewHeader       = document.getElementById('iwpAdminWhatsappPreviewHeaderText');
        let previewWelcome      = document.getElementById('iwpAdminWhatsappPreviewMessage');
        let previewColorHeader  = document.getElementById('iwpAdminWhatsappPreviewHeader');
        let previewColorBody    = document.getElementById('iwpAdminWhatsappPreviewBody');
        let previewColorIcon    = document.getElementById('iwpAdminWhatsappPreviewIcon');
        let previewButtonText   = document.getElementById('iwpAdminWhatsappPreviewButtonText');
        let previewButtonIcon   = previewColorIcon.querySelector('img');
        let previewQrHeader     = document.getElementById('iwpAdminWhatsappPreviewQrHeaderText');
        let previewQrTitle     = document.getElementById('iwpAdminWhatsappPreviewQrTitle');

        if (qrText && previewQrTitle) {
            previewQrTitle.innerText = qrText.value;
        }

        if (qrHeader && previewQrHeader) {
            previewQrHeader.innerText = qrHeader.value;
        }

        if (header && previewHeader) {
            previewHeader.innerText = header.value;
        }

        if (welcome && previewWelcome) {
            previewWelcome.innerText = welcome.value;
        }

        if (chatType && color && qrColor && previewColorHeader && previewColorBody && previewColorIcon && iconBackgrounds) {
            let selectedColor = (chatType.value === 'qr') ? qrColor.value : color.value;

            let bodyColor = hexToRgb(selectedColor, '0.12');
            previewColorHeader.style.backgroundColor = selectedColor;
            previewColorBody.style.backgroundColor = bodyColor;
            previewColorIcon.style.backgroundColor = selectedColor;
            Array.from(iconBackgrounds).forEach(function (iconBackground) {
                iconBackground.style.backgroundColor = selectedColor;
            });
        }

        if (buttonText && previewButtonText) {
            previewButtonText.innerText = buttonText.value;
        }

        if (icon && previewButtonIcon) {
            previewButtonIcon.src = '';
            previewButtonIcon.classList.add('iwp-hide');
            if (icon.value !== 'none') {
                previewButtonIcon.classList.remove('iwp-hide');
                previewButtonIcon.src = icon.getAttribute('data-img');
            }
        }
    }

    function loadChatPreview() {
        changeChatPreview();
    }

});