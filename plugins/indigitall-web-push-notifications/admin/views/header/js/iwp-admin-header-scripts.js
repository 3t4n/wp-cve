document.addEventListener('DOMContentLoaded', function() {
    openLogoutModal();
    logOutModalTimes();
    logOutModalCancel();
    confirmLogOutModal();

    /***** FUNCIONES *****/

    function openLogoutModal() {
        let logOutButton = document.getElementById('iwp-logout');
        if (logOutButton) {
            logOutButton.addEventListener('click', function() {
                document.getElementById('iwpLogoutModal').classList.remove('iwp-hide');
            });
        }
    }

    function logOutModalTimes() {
        let timesIcon = document.getElementById('iwpTimesLogoutModal');
        if (timesIcon) {
            timesIcon.addEventListener('click', function() {
                closeLogOutModal();
            });
        }
    }
    function logOutModalCancel() {
        let cancelButton = document.getElementById('iwpCancelLogoutModal');
        if (cancelButton) {
            cancelButton.addEventListener('click', function() {
                closeLogOutModal();
            });
        }
    }

    function closeLogOutModal() {
        let logOutModal = document.getElementById('iwpLogoutModal');
        if (logOutModal) {
            document.getElementById('iwpLogoutModal').classList.add('iwp-hide');
        }
    }

    function confirmLogOutModal() {
        let disconnectModal = document.getElementById('iwpLogoutModalDisconnect');
        if (disconnectModal) {
            disconnectModal.addEventListener('click', function() {
                document.getElementById('iwpLogoutModal').classList.add('iwp-hide');
                disconnect();
            });
        }
    }

    function disconnect() {
        showHideLoader(true);
        const data = new FormData();
        data.append('action', 'iwp_disconnect');

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => isResponseOk(response))
            .then((data) => {
                printConsoleLogOnDevelopMode(data);
                window.location.reload();
            }).catch((err) => {
                // Error genérico
                showHideLoader(false);
                printConsoleLogOnDevelopMode(err.message, true, true);
        }).finally(() => {});
    }
});