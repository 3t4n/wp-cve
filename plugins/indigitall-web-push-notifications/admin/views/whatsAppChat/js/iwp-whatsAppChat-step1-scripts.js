document.addEventListener('DOMContentLoaded', function() {
    createCustomSelectById('adminWhPhonePrefix', true);
    changeWhatsAppChatPhone();

    function changeWhatsAppChatPhone() {
        let input = document.getElementById('adminWhPhone');

        if (input) {
            input.addEventListener("change", function () {
                input.value = input.value.replace(/\D/, '');
                checkWhatsAppChatPhoneNumber();
            });
            input.addEventListener("input", function () {
                input.value = input.value.replace(/\D/, '');
            });
        }
    }

    function checkWhatsAppChatPhoneNumber() {
        let whatsAppChatSwitch = document.getElementById('iwpWhatsAppChatStatusSwitch');
        let whatsAppChatStatus = whatsAppChatSwitch.querySelector('.iwp-admin-switch-value');
        let input = document.getElementById('adminWhPhone');

        let phoneEmpty = document.getElementById('adminWhPhoneEmpty');
        let phoneError = document.getElementById('adminWhPhoneError');

        let newPhone = input.value;
        let switchValue = whatsAppChatStatus.value;

        phoneEmpty.classList.add('iwp-hide');
        phoneError.classList.add('iwp-hide');
        const pattern = /^\d{7,}$/;

        if (!pattern.test(newPhone)) {
            if ((newPhone === '') && (switchValue === '1')) {
                phoneEmpty.classList.remove('iwp-hide');
            } else {
                phoneError.classList.remove('iwp-hide');
            }
        }
    }
});