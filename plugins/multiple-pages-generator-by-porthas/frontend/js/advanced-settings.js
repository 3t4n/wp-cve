import { translate } from "../lang/init.js";

jQuery('#mpg_update_tables_structure').on('click', async function () {

    const event = await jQuery.post(ajaxurl, {
        action: 'mpg_activation_events',
        isAjax: true,
        securityNonce: backendData.securityNonce
    });

    let eventData = JSON.parse(event);

    if (!eventData.success) {
        toastr.error(eventData.error, translate['Failed']);
    } else {
        toastr.success(translate['MPG tables structure updated successfully'], translate['Success'], { timeOut: 5000 });
    }
})

jQuery('.advanced-page .mpg-hooks-block').on('submit', async function (e) {

    e.preventDefault();

    const selectedHook = jQuery('#mpg_hook_name').val();
    const hookPriority = jQuery('#mpg_hook_priority').val();

    const event = await jQuery.post(ajaxurl, {
        action: 'mpg_set_hook_name_and_priority',
        'hook_name': selectedHook,
        'hook_priority': hookPriority,
        'securityNonce': backendData.securityNonce
    });

    let eventData = JSON.parse(event);

    if (!eventData.success) {
        toastr.error(eventData.error, translate['Failed']);
    } else {
        toastr.success(translate['Hook settings updated sucessfully'], translate['Success'], { timeOut: 5000 });
    }
});

jQuery('.advanced-page .mpg-path-block').on('submit', async function (e) {

    e.preventDefault();

    const basePath = jQuery(this).find('select').val();

    const event = await jQuery.post(ajaxurl, {
        action: 'mpg_set_basepath',
        'basepath': basePath,
        securityNonce: backendData.securityNonce
    });

    let eventData = JSON.parse(event);

    if (!eventData.success) {
        toastr.error(eventData.error, translate['Failed']);
    } else {
        toastr.success(translate['Basepath settings updated sucessfully'], translate['Success'], { timeOut: 5000 });
    }
});



jQuery('.advanced-page .mpg-cache-hooks-block').on('submit', async function (e) {

    e.preventDefault();

    const selectedHook = jQuery('#mpg_cache_hook_name').val();
    const hookPriority = jQuery('#mpg_cache_hook_priority').val();

    const event = await jQuery.post(ajaxurl, {
        action: 'mpg_set_cache_hook_name_and_priority',
        'cache_hook_name': selectedHook,
        'cache_hook_priority': hookPriority,
        securityNonce: backendData.securityNonce

    });

    let eventData = JSON.parse(event);

    if (!eventData.success) {
        toastr.error(eventData.error, translate['Failed']);
    } else {
        toastr.success(translate['Hook settings updated sucessfully'], translate['Success'], { timeOut: 5000 });
    }
});



jQuery('.advanced-page .mpg-branding-position-block').on('submit', async function (e) {

    e.preventDefault();

    const position = jQuery('#mpg_change_branding_position').val();

    const event = await jQuery.post(ajaxurl, {
        action: 'mpg_set_branding_position',
        'branding_position': position ? position : 'left',
        securityNonce: backendData.securityNonce
    });

    let eventData = JSON.parse(event);

    if (!eventData.success) {
        toastr.error(eventData.error, translate['Failed']);
    } else {
        toastr.success(translate['Hook settings updated sucessfully'], translate['Success'], { timeOut: 5000 });
    }
});





