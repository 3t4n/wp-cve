jQuery(function () {

    jQuery('#user_forms').DataTable();
    jQuery('iframe').css('height', jQuery(window).height() - 120);

    jQuery('.mf-forms-control-block button').on('click', function () {

        const confirmDialog = confirm('Are you sure you want to remove the API key?')

        if (confirmDialog) {
            jQuery.post(
                ajaxurl, {
                action: 'upsert_user_api_key',
                userApiKey: null
            }, function (response) {
                if (JSON.parse(response).success) {
                    alert('API key was removed. Now, login again with needed credentials in Dashboard');
                    location.reload();
                } else {
                    alert('Something was wrong. Please, contact with MightyForms support');
                }
            });
        }
    });

    const mfIsSafari = /constructor/i.test(window.HTMLElement) || (function (p) {
        return p.toString() === "[object SafariRemoteNotification]";
    }
    )(!window['safari'] || (typeof safari !== 'undefined' && safari.pushNotification));

    const mfMessageBox = jQuery('.mf-main-block .mf-message-box');
    const mfHideSafariMessage = 'mf-hide-safari-message';

    if (mfIsSafari) {
        if (localStorage.getItem(mfHideSafariMessage) !== 'true') {
            mfMessageBox.css('display', 'flex');
        }
    }

    mfMessageBox.find('span').on('click', function () {
        mfMessageBox.css('display', 'none');
        localStorage.setItem(mfHideSafariMessage, 'true');
    });
});



jQuery(window).on('message', function (e) {

    let rawData = e.originalEvent.data;
    try {
        if (typeof rawData !== 'string') return;

        let post = JSON.parse(rawData);


        if ('userApiKey' === post.message) {
            jQuery.post(
                ajaxurl, {
                action: 'upsert_user_api_key',
                userApiKey: post.data
            }, function (response) {
                console.log(response)
            });
        }
    } catch (err) {
        console.error(err);
    }
});