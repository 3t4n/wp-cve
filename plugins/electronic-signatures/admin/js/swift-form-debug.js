jQuery(document).ready(function() {
    var plugin_prefix = 'ssing_';

    jQuery(".swift_beta_testing").rcSwitcher().on({
        'turnon.rcSwitcher': function(e, dataObj) {
            var data = {
                'action': plugin_prefix + 'beta_testing_mode_on',
                'data': jQuery("#" + plugin_prefix + "beta_testing_mode:checked").val()
            };
            jQuery.post(ajaxurl, data, function(response) {
                if (response != '') {
                    var obj = jQuery.parseJSON(response);
                    if (obj.status == 'on') {
//                        jQuery(".swift_testing_msg_wrap").before('<div id="swift-testing-error-msg" class="notice notice-error"><p>' + obj.msg + '</div>');
//                        jQuery(".swift_testing_mode_form").fadeIn();
                        var url = window.location.href;
                        if (url.indexOf('?') > -1) {
                            url += '&tab=ssign-setp-support&update=modeon';
                        }
                        window.location.href = url;
                    }
                }
            });
        },
        'turnoff.rcSwitcher': function(e, dataObj) {
            jQuery("#swift-testing-error-msg").remove();
            jQuery(".swift_testing_mode_form").fadeOut();
            var data = {
                'action': plugin_prefix + 'beta_testing_mode_off',
                'data': '0'
            };
            jQuery.post(ajaxurl, data, function(response) {
                jQuery('.swift_beta_testing').removeAttr('checked');
                jQuery("#swift-testing-error-msg").remove();
                //location.reload();
                var url = window.location.href;
                if (url.indexOf('?') > -1) {
                    url += '&tab=ssign-setp-support&update=modeoff';
                }
                window.location.href = url;
            });
        }
    });
});
function swiftOffTestingMode() {
    var plugin_prefix = 'ssing_';
    jQuery("#swift-testing-error-msg").remove();
    jQuery(".swift_testing_mode_form").fadeOut();
    var data = {
        'action': plugin_prefix + 'beta_testing_mode_off',
        'data': '0',
        'redirect': 'yes'
    };
    jQuery.post(ajaxurl, data, function(response) {
        jQuery('.swift_beta_testing').removeAttr('checked');
        jQuery("#swift-testing-error-msg").remove();
        //location.reload();
        var url = window.location.href;
        if (url.indexOf('?') > -1) {
            url += '&tab=ssign-setp-support&update=modeoff';
        }
        window.location.href = url;
    });
}