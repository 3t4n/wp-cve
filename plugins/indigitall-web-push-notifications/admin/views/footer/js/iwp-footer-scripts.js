document.addEventListener('DOMContentLoaded', function() {
    developerModeClick();

    function developerModeClick() {
        let mode = document.getElementById('iwpDeveloperMode');
        if (mode) {
            mode.addEventListener("click", function() {
                changeDeveloperMode();
            });
        }
    }

    function changeDeveloperMode() {
        let developerModeContainer = document.getElementById('iwpDeveloperMode');
        let developerModeValue = developerModeContainer.getAttribute('data-mode');
        let newValue = (developerModeValue === '1') ? '0' : '1';

        const data = new FormData();
        data.append('action', 'iwp_toggle_developer_mode');
        data.append('developerMode', newValue);

        fetch(iwpAjaxUrl, {
            method: "POST",
            credentials: "same-origin",
            body: data
        }).then((response) => {
            if (response.ok) {
                developerModeContainer.setAttribute('data-mode', newValue);
                developerMode = parseInt(newValue);
                developerModeContainer.classList.remove('active');
                if (newValue === '1') {
                    developerModeContainer.classList.add('active');
                }
            }
            const msg = {
                'Status code:': response.status,
                'Changed DeveloperMode status to:': newValue,
                'Request Response': response.statusText
            }
            printConsoleLogOnDevelopMode(msg, false, true);
        }).catch((err) => {
            // Error genérico
            printConsoleLogOnDevelopMode(err.message, true, true);
        });
    }
});