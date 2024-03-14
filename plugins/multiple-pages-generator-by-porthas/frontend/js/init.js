import { translate } from  "../lang/init.js";
import {mpgUpdateState} from "./helper.js";

jQuery(document).ready(function () {

    jQuery.post(ajaxurl, {
        action: 'mpg_get_permalink_structure',
        securityNonce: backendData.securityNonce
    }).then(permalink => {

        let permalinkData = JSON.parse(permalink)

        if (!permalinkData.success) {
            toastr.error(permalinkData.error, 'Checking permalink structure');
        }

        if (permalinkData.data === '') {
            toastr.warning(`${translate['Your permailnk structure is Plain. MPG needed to change permalink structure to any other, like a /postname/. Do you want to']} <a href="#" style="color:green;" class="fix-permalink-structure">${translate['fix it?']}</a>`, translate['Wrong permalink structure'], { timeOut: 10000 });
        }
    });

    // Инициализация тултипов
    tippy('[data-tippy-content]');


    // ==================     Datetime picker init ===============
    let dateObject = new Date();
    jQuery('input[name="datetime_upload_remote_file"]').datetimepicker({
        minuteStepping: 1,               //set the minute stepping
        minDate: `1/1/1900`,
        minTime: `${dateObject.getHours()}:${dateObject.getMinutes()}`,
        step: 10 // minutes
    });

    jQuery('input[name="mpg_timezone_name"]').val(Intl.DateTimeFormat().resolvedOptions().timeZone);

    mpgUpdateState('limit', 5);
});


jQuery(document).on('click', '.fix-permalink-structure', function (e) {

    e.preventDefault();

    jQuery.post(ajaxurl, {
        action: 'mpg_change_permalink_structure',
        securityNonce: backendData.securityNonce
    }).then(permalink => {

        let permalinkData = JSON.parse(permalink)

        if (!permalinkData.success) {
            toastr.error(translate['Checking permalink structure failed, due to: '] + permalinkData.error, translate['Failed']);
        } else {
            toastr.success(permalinkData.data, translate['Success']);
        }
    });
})
