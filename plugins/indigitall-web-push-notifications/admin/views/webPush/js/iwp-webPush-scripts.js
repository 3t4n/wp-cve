document.addEventListener('DOMContentLoaded', function() {
    switchWebPushStatus();

    function switchWebPushStatus() {
        let webPushSwitch = document.getElementById('iwpWebPushStatusSwitch');
        if (webPushSwitch) {
            webPushSwitch.addEventListener("click", function (e) {
                if (IS_LOGGED === '1') {
                    sendWebPushStatusAjax();
                } else {
                    e.preventDefault();
                    e.stopImmediatePropagation();
                    e.stopPropagation();
                    addInfoToExtraHiddenFieldForLogin('activateWebPush', '1');
                    showHideLoginModalBlock(document.getElementById('iwpAdminModalLogin'), true);
                    showHideLoginModalBlock(document.getElementById('iwpAdminLogin'), true);
                }
            });
        }
    }

    function sendWebPushStatusAjax() {
        let webPushSwitch = document.getElementById('iwpWebPushStatusSwitch');
        let webPushStatus = webPushSwitch.querySelector('.iwp-admin-switch-value');
        let webPushCheckboxContainer = document.getElementById('iwp-admin-webPush-config-webPush-notifications-container');

        if (webPushSwitch && webPushStatus) {
            toggleSwitch(webPushSwitch, true, 'disabled', 'enabled');
            if (!webPushSwitch.classList.contains('blocked')) {
                webPushSwitch.classList.add('blocked');
                if (webPushCheckboxContainer) {
                    webPushCheckboxContainer.classList.add('blocked');
                }

                const data = new FormData();
                data.append('action', 'iwp_toggle_wp_status');
                data.append('status', webPushStatus.value);

                fetch(iwpAjaxUrl, {
                    method: "POST",
                    credentials: "same-origin",
                    body: data
                }).then((response) => {
                    changeWebPushStatus(webPushStatus.value);

                    const msg = {
                        'Status code:': response.status,
                        'Changed WebPush status to:': webPushStatus.value,
                        'Request Response': response.statusText
                    }
                    printConsoleLogOnDevelopMode(msg);
                }).catch((err) => {
                    // Error genérico
                    printConsoleLogOnDevelopMode(err.message, true, true);
                }).finally(() => {
                    setTimeout(function () {
                        webPushSwitch.classList.remove('blocked');
                        if (webPushCheckboxContainer) {
                            webPushCheckboxContainer.classList.remove('blocked');
                        }
                    }, 2000);
                });
            }
        }
    }

    function changeWebPushStatus(newValue) {
        let mainMenuItem = document.getElementById('iwp-admin-main-menu-channel-webPush');
        let menuItemStatus = mainMenuItem.querySelector('.iwp-admin-main-menu-item-status');

        let webPushConfigNotifications = document.getElementById('webPushNotifications');
        let webPushConfigLocation = document.getElementById('webPushLocation');
        let subPageIsConfig = (webPushConfigLocation && webPushConfigNotifications);

        if (newValue === '1') {
            menuItemStatus.classList.remove('deactivated');
            menuItemStatus.classList.add('activated');
            if (subPageIsConfig) {
                webPushConfigNotifications.checked = true;
                webPushConfigLocation.checked = false;
            }
        } else {
            menuItemStatus.classList.remove('activated');
            menuItemStatus.classList.add('deactivated');
            if (subPageIsConfig) {
                webPushConfigNotifications.checked = false;
                webPushConfigLocation.checked = false;
            }
        }
    }

});