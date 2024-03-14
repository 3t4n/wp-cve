document.addEventListener('DOMContentLoaded', function () {
    /** Handle phone tel input text change */
    const phoneInputs = Array.from(document.querySelectorAll('.spoki-phone-container input[type="tel"]'));
    if (phoneInputs.length) {
        phoneInputs.forEach(c => c.addEventListener('keyup', function (e) {
            this.value = e.target.value.replace(/[^0-9]+/g, '');
        }))
    }

    /** Handle phone prefix input text change */
    const prefixInputs = Array.from(document.querySelectorAll('.spoki-phone-container .spoki-phone-prefix'));
    if (prefixInputs.length) {
        prefixInputs.forEach(c => c.addEventListener('keyup', function (e) {
            this.value = e.target.value.replace(/[^0-9\+]+/g, '');
            if (this.value.length && this.value[0] !== '+') {
                this.value = '+' + this.value;
            }
        }))
    }

    function performRequest(url, body = {}, headers = {}, method = 'POST') {
        return fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                ...headers || {}
            },
            body: method !== 'GET' ? JSON.stringify(body || {}) : undefined
        }).then(r => r.json());
    }

    function submitForm(form) {
        const submitFormFunction = Object.getPrototypeOf(form).submit;
        submitFormFunction.call(form);
    }

    function fetchPlan(plan_url, secret) {
        return performRequest(
            plan_url,
            {},
            {
                'Authorization': secret || null
            },
            'GET'
        ).then(plan => {
            document.getElementsByName('_wp_spoki_setting[account_info][response_json]')[0].value = JSON.stringify(plan);
        })
    }

    const form = document.forms['spoki-options-form'];
    if (form) {
        form.addEventListener('submit', function (e) {
            if (e.submitter.hasAttribute('enable-spoki-btn')) {
                e.preventDefault();
                e.submitter.classList.add('disabled');
                const formData = new FormData(form);
                const data = {};
                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }
                performRequest(
                    data['spoki_enable_url'],
                    {
                        phone: data['_wp_spoki_setting[onboarding][prefix]'] + data['_wp_spoki_setting[onboarding][telephone]'],
                        email: data['_wp_spoki_setting[onboarding][email]'],
                        name: data['_wp_spoki_setting[onboarding][shop_name]']
                    }
                ).then(r => {
                    const {delivery_url, secret} = r;
                    if (delivery_url && secret) {
                        document.getElementsByName('_wp_spoki_setting[onboarding][spoki_onboarding_delivery_url]')[0].value = delivery_url;
                        document.getElementsByName('_wp_spoki_setting[onboarding][spoki_onboarding_secret]')[0].value = secret;
                        fetchPlan(data['spoki_plan_url'], secret).then(() => {
                            e.submitter.removeAttribute('enable-spoki-btn');
                            submitForm(form);
                        }).catch(error => {
                            console.log(error);
                            e.submitter.classList.remove('disabled');
                            alert('Unable to enable Spoki');
                        })
                    } else {
                        e.submitter.classList.remove('disabled');
                        alert('Unable to enable Spoki');
                    }
                }).catch(error => {
                    console.log(error);
                    e.submitter.classList.remove('disabled');
                    alert('Unable to enable Spoki');
                })
            } else if (e.submitter.hasAttribute('settings-btn')) {
                e.preventDefault();
                e.submitter.classList.add('disabled');
                const formData = new FormData(form);
                const data = {};
                for (let [key, value] of formData.entries()) {
                    data[key] = value;
                }
                const authorization = data['_wp_spoki_setting[secret]'];
                if (authorization) {
                    performRequest(
                        data['spoki_account_url'],
                        {
                            phone: data['_wp_spoki_setting[prefix]'] + data['_wp_spoki_setting[telephone]'],
                            email: data['_wp_spoki_setting[email]'],
                            name: data['_wp_spoki_setting[shop_name]'],
                            language: data['_wp_spoki_setting[language]'],
                            contact_link: data['_wp_spoki_setting[contact_link]'] || '',
                            default_prefix: data['_wp_spoki_setting[default_prefix]'] || '',
                            zip_code: data['_wp_spoki_setting[billing_data][zip_code]'] || '',
                            province: data['_wp_spoki_setting[billing_data][province]'] || '',
                            country: data['_wp_spoki_setting[billing_data][country]'] || '',
                            route: data['_wp_spoki_setting[billing_data][route]'] || '',
                            city: data['_wp_spoki_setting[billing_data][city]'] || '',
                            vat_number: data['_wp_spoki_setting[billing_data][vat_number]'] || '',
                            vat_name: data['_wp_spoki_setting[billing_data][vat_name]'] || '',
                            c_f: data['_wp_spoki_setting[billing_data][c_f]'] || '',
                            pec: data['_wp_spoki_setting[billing_data][pec]'] || '',
                            sid: data['_wp_spoki_setting[billing_data][sid]'] || '',
                        },
                        {
                            'Authorization': authorization || null
                        }
                    ).then(() => {
                        fetchPlan(data['spoki_plan_url'], data['_wp_spoki_setting[secret]']).then(() => {
                            e.submitter.removeAttribute('settings-btn');
                            submitForm(form);
                        }).catch(error => {
                            e.submitter.classList.remove('disabled');
                            console.log(error);
                            alert('Unable to save settings on Spoki');
                        })
                    }).catch(error => {
                        e.submitter.classList.remove('disabled');
                        console.log(error);
                        alert('Unable to save settings on Spoki');
                    })
                } else {
                    e.submitter.removeAttribute('settings-btn');
                    submitForm(form);
                }
            }
        })
    }
})