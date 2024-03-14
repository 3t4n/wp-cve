jQuery(document).ready(function ($) {

    // Querystring param functions
    // Examples...
    // var allVars = $.getUrlVars();
    // var byName = $.getUrlVar('name');
    $.extend({
        getUrlVars: function () {
            var vars = [], hash;
            var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
            for (var i = 0; i < hashes.length; i++) {
                hash = hashes[i].split('=');
                vars.push(hash[0]);
                vars[hash[0]] = hash[1];
            }
            return vars;
        },
        getUrlVar: function (name) {
            return $.getUrlVars()[name];
        }
    });

    // E-mail validation
    if (!FDSUS.dlssus_validate_email.disable) {
        $('.dls-sus-signup-form #signup_email').on('blur', function (e) {
            let warnNoticeOpen = '<div role="alertdialog" class="dlsntc-notice dlsntc-warn"><p class="dlsntc-message"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><title>Warning</title><path d="M22.56 16.3L14.89 3.58a3.43 3.43 0 0 0-5.78 0L1.44 16.3a3 3 0 0 0-.05 3A3.37 3.37 0 0 0 4.33 21h15.34a3.37 3.37 0 0 0 2.94-1.66a3 3 0 0 0-.05-3.04zM12 17a1 1 0 1 1 1-1a1 1 0 0 1-1 1zm1-4a1 1 0 0 1-2 0V9a1 1 0 0 1 2 0z"/></svg> ';
            let noticeClose = '</p></div>';
            let mailcheckSuggestion = $('.dls-sus-signup-form #dls-sus-mailcheck-suggestion');

            $(this).mailcheck({
                suggested: function (element, suggestion) {
                    mailcheckSuggestion.html(warnNoticeOpen + 'Did you mean <b><i>' + suggestion.full + '</b></i>?' + noticeClose);
                },
                empty: function (element) {
                    mailcheckSuggestion.html('');

                    // Standard general format check
                    function fdsusValidateEmail(email) {
                        const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
                        return re.test(String(email).toLowerCase());
                    }

                    if (fdsusValidateEmail(document.getElementById('signup_email').value)) {
                        mailcheckSuggestion.html('');
                    } else {
                        mailcheckSuggestion.html(warnNoticeOpen + 'Please make sure your email is valid' + noticeClose);
                    }
                }
            });
        });
    }

    $('.dls-sus-signup-form input[type=date]').on('blur', function (e) {
        let warnNoticeOpen = '<div role="alertdialog" class="dlsntc-notice dlsntc-warn fdsus-date-check"><p class="dlsntc-message"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" focusable="false" width="1em" height="1em" style="-ms-transform: rotate(360deg); -webkit-transform: rotate(360deg); transform: rotate(360deg);" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24"><title>Warning</title><path d="M22.56 16.3L14.89 3.58a3.43 3.43 0 0 0-5.78 0L1.44 16.3a3 3 0 0 0-.05 3A3.37 3.37 0 0 0 4.33 21h15.34a3.37 3.37 0 0 0 2.94-1.66a3 3 0 0 0-.05-3.04zM12 17a1 1 0 1 1 1-1a1 1 0 0 1-1 1zm1-4a1 1 0 0 1-2 0V9a1 1 0 0 1 2 0z"/></svg> ';
        let noticeClose = '</p></div>';

        // Remove existing date warnings
        $('.fdsus-date-check').remove();

        // Validate date field
        if ($(this).val() && !fdsusIsValidDate($(this).val())) {
            $(this).after(warnNoticeOpen + 'Please make sure date is in <strong>YYYY-MM-DD</strong> format' + noticeClose);
        }
    });

});

document.addEventListener('DOMContentLoaded', function () {

    const fdsusSignupForm = document.getElementById('fdsus-signup-form');
    if (fdsusSignupForm !== null) {
        // Disable Submit for 3s to prevent double submissions
        let submittedOnce = false;
        fdsusSignupForm.addEventListener('submit', (event) => {
            const submitButton = fdsusSignupForm.querySelector('[type=submit]');
            if (!submittedOnce) {
                submittedOnce = true;
                submitButton.disabled = true;
                setTimeout(() => {
                    submittedOnce = false;
                    submitButton.disabled = false;
                }, 3000)
            }

            // Validate v2 Checkbox reCAPTCHA
            if (FDSUS.dls_sus_recaptcha_version === 'v2-checkbox') {
                const fdsusRecaptchaV2Response = document.querySelector('#fdsus-signup-form #g-recaptcha-response');
                if (fdsusRecaptchaV2Response !== null) {
                    if (fdsusRecaptchaV2Response.value === '') {
                        event.preventDefault();
                        alert('Please check the reCAPTCHA to submit the form.');
                    }
                }
            }
        });
    }

    // Clean query string of message-based parameters
    const currentUrl = window.location.toString();
    if (currentUrl.indexOf('?') > 0) {
        let cleanUrl = currentUrl;

        // Clear after successful removal
        if (
            (
                fdsusExistsInQueryString('action=removed', cleanUrl)
                || fdsusExistsInQueryString('action=signup', cleanUrl)
                || fdsusExistsInQueryString('action=updated', cleanUrl)
            )
            && fdsusExistsInQueryString('status=success', cleanUrl)
        ) {
            cleanUrl = fdsusRemoveUrlParam('action', cleanUrl);
            cleanUrl = fdsusRemoveUrlParam('status', cleanUrl);
        }

        // Update URL if it's been changed
        if (cleanUrl !== currentUrl) {
            window.history.replaceState({}, document.title, cleanUrl);
        }
    }

}, false);

/**
 * Check if a string exists in query string
 *
 * @param needle
 * @param url
 * @returns {boolean}
 */
function fdsusExistsInQueryString(needle, url) {
    // $param can be the param key like "action=" or key+value like "action=signup"
    let regex = new RegExp('[?&]' + needle);
    return regex.test(url);
}

/**
 * Remove query string parameter from URL
 *
 * @param key
 * @param url
 * @returns {string}
 */
function fdsusRemoveUrlParam(key, url) {
    let rtn = url.split("?")[0],
        param,
        params_arr = [],
        queryString = (url.indexOf("?") !== -1) ? url.split("?")[1] : "";
    if (queryString !== "") {
        params_arr = queryString.split("&");
        for (let i = params_arr.length - 1; i >= 0; i -= 1) {
            param = params_arr[i].split("=")[0];
            if (param === key) {
                params_arr.splice(i, 1);
            }
        }
        if (params_arr.length) rtn = rtn + "?" + params_arr.join("&");
    }
    return rtn;
}

/**
 * Check if date is valid YYYY-mm-dd format
 *
 * @param dateString
 * @returns {string}
 */
function fdsusIsValidDate(dateString) {
    let regEx = /^\d{4}-\d{2}-\d{2}$/;
    if (!dateString.match(regEx)) return false;  // Invalid format
    let d = new Date(dateString);
    let dNum = d.getTime();
    if (!dNum && dNum !== 0) return false; // NaN value, Invalid date
    return d.toISOString().slice(0, 10) === dateString;
}

/**
 * Sign-up Form Submit Callback (for reCAPTCHA v2 Invisible)
 */
function fdsusSignupFormSubmit(token) {
    document.getElementById('fdsus-signup-form').submit();
}
