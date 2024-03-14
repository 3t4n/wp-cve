document.addEventListener("DOMContentLoaded", function() {
    const srcId = 'leadlovers-capture-lead-script-js'
    const data = {
        action:  'leadlovers-get-integrations',
        nonce: getParameterByName('leadlovers-get-integrations_nonce', srcId),
        page: window.location.href.split('#')[0],
    }
    const params = new URLSearchParams()
    for(let key in data)
        params.append(key, data[key])

    fetch(getParameterByName('xhr_url', srcId), {
        method: 'POST',
        body: params
    })
    .then(res => res.json())
    .catch(error => {
            console.error(error)
    })
    .then(response => {
        function reload() {
            const integrations = response.data
            const allForms = document.querySelectorAll('form')
            const forms = []
            if(allForms) {
                allForms.forEach((f) => {
                    if(f.id && 
                        integrations.some((int) =>  int.active && int.form_id === f.id) && 
                        LISTED_FORMS.every((f )=> f !== f.id)
                    ) {
                        forms.push(f)
                    }
                })
            }
            forms.forEach((form) => {
                form.addEventListener('submit', (event) => handleSubmitForm(event, integrations, srcId))
                LISTED_FORMS.push(form.id)
            })
        }
        reload()
        observe(() => {
            reload()
        })
    })
})