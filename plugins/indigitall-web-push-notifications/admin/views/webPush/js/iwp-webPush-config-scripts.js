document.addEventListener('DOMContentLoaded', function() {
    checkUncheckWebPushStatus();
    checkUncheckWebPushLocation();

    function checkUncheckWebPushStatus() {
        let webPushCheckboxContainer = document.getElementById('iwp-admin-webPush-config-webPush-notifications-container');
        let webPushSwitch = document.getElementById('iwpWebPushStatusSwitch');
        if (webPushCheckboxContainer) {
            webPushCheckboxContainer.addEventListener("click", function () {
                webPushSwitch.click();
            });
        }
    }

    function checkUncheckWebPushLocation() {
        let webPushLocationCheckboxContainer = document.getElementById('iwp-admin-webPush-config-webPush-location-container');
        let webPushLocation = document.getElementById('webPushLocation');
        if (webPushLocationCheckboxContainer && webPushLocation) {
            webPushLocationCheckboxContainer.addEventListener("click", function (e) {
                let webPushSwitch = document.getElementById('iwpWebPushStatusSwitch');
                let webPushStatus = webPushSwitch.querySelector('.iwp-admin-switch-value');

                if (webPushStatus.value === '0') {
                    e.preventDefault();
                    e.stopPropagation();
                    e.stopImmediatePropagation();
                } else {
                    webPushLocation.checked = !webPushLocation.checked;
                    sendWebPushLocationAjax();
                }
            });
        }
    }

    function sendWebPushLocationAjax() {
        let webPushLocationCheckboxContainer = document.getElementById('iwp-admin-webPush-config-webPush-location-container');
        let webPushLocation = document.getElementById('webPushLocation');

        if (webPushLocationCheckboxContainer) {
            if (!webPushLocationCheckboxContainer.classList.contains('blocked')) {
                webPushLocationCheckboxContainer.classList.add('blocked');

                const data = new FormData();
                data.append('action', 'iwp_toggle_wp_location');
                data.append('location', webPushLocation.checked);

                fetch(iwpAjaxUrl, {
                    method: "POST",
                    credentials: "same-origin",
                    body: data
                }).then((response) => {
                    const msg = {
                        'Status code:': response.status,
                        'Changed WebPush status to:': webPushLocation.checked,
                        'Request Response': response.statusText
                    }
                    printConsoleLogOnDevelopMode(msg);
                }).catch((err) => {
                    // Error genérico
                    printConsoleLogOnDevelopMode(err.message, true, true);
                }).finally(() => {
                    setTimeout(function () {
                        webPushLocationCheckboxContainer.classList.remove('blocked');
                    }, 2000);
                });
            }
        }
    }

});