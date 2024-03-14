// Note: IDE marks this as unused, but it is used by settings-edit.php ("Delete API key")
function cnb_delete_apikey() {
    const apiKeyField = jQuery(".call-now-button-plugin #cnb_api_key")
    apiKeyField.prop("type", "hidden");
    apiKeyField.prop("value", "delete_me");
    apiKeyField.removeAttr("disabled");

    // Ensure we use the exact verbiage of the Submit button
    const saveVal = apiKeyField.parents('.cnb-container').find('#submit').val();
    jQuery('.call-now-button-plugin #cnb_api_key_delete').replaceWith("<p>Click <strong>"+saveVal+"</strong> to disconnect your account.</p>")

    // Present the default behavior of this submit button (since it needs to be actioned on by the *actual* submit button
    return false;
}

const cnb_tally_deactivate_premium_form_id = 'wA7d2z'

function cnb_ask_for_feedback_disable_cloud() {
    const ele = jQuery('#cnb_cloud_enabled')
    const isChecked = ele.is(':checked')
    if (isChecked) {
        ele.on('click', () => {
            const isChecked = ele.is(':checked')
            const options = {
                width: 450,
                hideTitle: 1,
                emoji: {
                    text: '😢',
                    animation: 'heart-beat'
                },
                autoClose: 5000,
                hiddenFields: {
                    wordPressUrl: window.location.href,
                    userId: jQuery('#cnb_user_id').text()
                }
            }
            // Check if Tally actually is loaded
            if (Tally) {
                if (!isChecked) {
                    Tally.openPopup(cnb_tally_deactivate_premium_form_id, options)
                } else {
                    Tally.closePopup(cnb_tally_deactivate_premium_form_id)
                }
            }
        })
    }
}

/**
 * Disable the Cloud inputs when it is disabled (but only on the settings screen,
 * where that checkbox is actually visible)
 */
function cnb_disable_api_key_when_cloud_hosting_is_disabled() {
    const ele = jQuery('#cnb_cloud_enabled');
    if (ele.length) {
        jQuery('.when-cloud-enabled :input').prop('disabled', !ele.is(':checked'));
    }
}

function init_settings() {
    jQuery("#cnb_email_activation_alternate_form").hide()
}

/**
 * Displays a link to a page with tips to fix "Not Working" issues to prevent users from disabling cloud
 */
function cnb_show_tips_when_deactivating() {
  const ele = jQuery("#cnb_cloud_enabled")
  const isChecked = ele.is(':checked')
  if (isChecked) {
    ele.on('click', () => {
        const isChecked = ele.is(':checked')
        if (!isChecked) {
            jQuery("#cnb_not_working_tips").css('display','flex');
        } else {
            jQuery("#cnb_not_working_tips").hide();
        }
    })
  }
}

function add_onclick_cnb_user_storage_type() {
    jQuery('.cnb-switch-storage-type').on('click', cnb_user_storage_type)
}
function cnb_user_storage_type() {
    const switchingTo = jQuery(this).data('storage-type')
    const data = {
        'action': 'cnb_set_user_storage_solution',
        'storage_type': switchingTo,
        '_ajax_nonce': jQuery(this).data('wpnonce'),
    }

    jQuery(this).prop('disabled', true)
    jQuery('.cnb-switch-storage-type-result').text("Switching to " + switchingTo + ".").removeClass('hidden').removeClass('notice-success').addClass('notice-info')
    jQuery.post(ajaxurl, data)
        .done((result) => {
            const switchedTo = result.settings.fileStorageImplementation
            jQuery('.cnb-switch-storage-type-result').text("Successfully switched to " + switchedTo + '. Refresh the page to see the new data.').removeClass('notice-info').addClass('notice-success')
        })

    return false
}

jQuery(() => {
    init_settings();
    cnb_disable_api_key_when_cloud_hosting_is_disabled()
    cnb_ask_for_feedback_disable_cloud()
    cnb_show_tips_when_deactivating()
    add_onclick_cnb_user_storage_type()
})
