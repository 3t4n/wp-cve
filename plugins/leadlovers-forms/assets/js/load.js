document.addEventListener("DOMContentLoaded", function() {
    const data = {
        action:  'leadlovers-get-integrations',
        nonce: getParameterByName('leadlovers-get-integrations_nonce'),
        page: window.location.href.split('#')[0],
    }
    
    const params = new URLSearchParams()
    for(let key in data)
        params.append(key, data[key])

    fetch(getParameterByName('xhr_url'), {
        method: 'POST',
        body: params
    })
    .then(res => res.json())
    .catch(error => {
            console.error(error)
    })
    .then(response => {
        const integrations = response.data
        const element = document.createElement('ll-integration-form')
        element.guid = getParameterByName('api-access-key')
        element.logo = getParameterByName('plugin-url') + 'assets/img/marca.svg'
        element.short_logo = getParameterByName('plugin-url') + 'assets/img/leadlovers-simbolo-negativo.svg'
        element.integrations = JSON.stringify(integrations)
        document.body.appendChild(element)
        element.addEventListener('form-submit', function(data) {
            data = JSON.parse(data.detail)
            data.page = window.location.href.split('#')[0]
            data.action = 'leadlovers-save-integration'
            data.nonce = getParameterByName('leadlovers-save-integration_nonce')
            const params = new URLSearchParams()
            for(let key in data)
                params.append(key, data[key])
            
            fetch(getParameterByName('xhr_url'), {
                method: 'POST',
                body: params
            })
            .then(res => res.json())
            .catch(error => {
                    console.error(error)
            })
            .then(response => {
                element.dispatchEvent(
                    new CustomEvent('form-submit-callback', {
                        detail: JSON.stringify({
                            result: response.data,
                            status: response.status
                        }),
                    })
                )
                if(LISTED_FORMS.every((f )=> f !== response.data.form_id)) {
                    document.querySelector('#' + response.data.form_id).addEventListener('submit', (event) => 
                        handleSubmitForm(event, [response.data, ...integrations])) 
                    LISTED_FORMS.push(response.data.form_id)
                }
            })
        })
    })
})

