const settingEndPoint = bv_settings.api.endpoint;

export default {
    saveSettings(settings) {
        let data = {
            settings
        };

        return fetch(settingEndPoint, {
            method: 'POST',
            headers: {
                'X-WP-Nonce': bv_settings.api.nonce,
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            credentials: 'same-origin',
            body: JSON.stringify(data),
        }).then(response => {
            return response.json().then(json => {
                return response.ok ? json : Promise.reject(json);
            });
        });
    },
};
