(function($) {
    "use strict";
    $("#zurcf7_formid").change(function() {
        var zurcf7_formid = $(this).val();
        $.ajax({
            type: "POST",
            dataType: "json",
            url: ajaxurl,
            data: { action: "get_cf7_form_data", zurcf7_formid: zurcf7_formid },
            beforeSend: function() {
                $('.loader').show();
            },
            complete: function() {
                $('.loader').hide();
            },
            success: function(r) {
                if (r.response == 'success') {
                    if (r.is_exists == 'no')
                        $('.zurcf7_alltag').html(r.formtag);
                        $('#zurcf7_fb_signup_app_id').val('');
                        $('#zurcf7_fb_app_secret').val('');
                    if (r.is_exists == 'yes') {

                        $('.zurcf7_alltag').html(r.formtag);

                        $("#zurcf7_email_field option[value='" + r.zurcf7_email_field + "']").attr("selected", "selected");
                        $("#zurcf7_username_field option[value='" + r.zurcf7_username_field + "']").attr("selected", "selected");
                        $("#zurcf7_userrole_field option[value='" + r.zurcf7_userrole_field + "']").attr("selected", "selected");
                        
                        /*Start ACF Field */
                        var zurcf7_ACF_field = r.zurcf7_ACF_field;
                        if(zurcf7_ACF_field){
                            var zurcf7_ACF_field_string = zurcf7_ACF_field.toString();
                            $.each(zurcf7_ACF_field_string.split(','), function(key, value) {
                                $("#zurcf7_ACF_field_"+key).val(value); 
                            });
                        }
                        $("#zurcf7_ACF_field option[value='" + r.zurcf7_ACF_field + "']").attr("selected", "selected");
                        /*End ACF Field */
                        
                        /*Start FB Field */
                        $('#zurcf7_fb_signup_app_id').val(r.zurcf7_fb_signup_app_id);
                        $('#zurcf7_fb_app_secret').val(r.zurcf7_fb_app_secret);
                        /*End FB Field */
                    }
                } else {
                    $('.zurcf7_alltag').html(r.formtag)
                    $('#zurcf7_fb_signup_app_id').val('');
                    $('#zurcf7_fb_app_secret').val('');
                }
            }
        });
    });

    //Hover information popup
    $('#zurcf7_formid_msg').on('mouseenter click', function() {
        $('body .wp-pointer-buttons .close').trigger('click');
        $('#zurcf7_formid_msg').pointer({
            pointerClass: 'wp-pointer zurcf7-pointer',
            content: cf7forms_data.zurcf7_formid_msg,
            position: 'left center',
        }).pointer('open');
    });

    $('#zurcf7_skipcf7_email_msg').on('mouseenter click', function() {
        $('body .wp-pointer-buttons .close').trigger('click');
        $('#zurcf7_skipcf7_email_msg').pointer({
            pointerClass: 'wp-pointer zurcf7-pointer',
            content: cf7forms_data.zurcf7_skipcf7_email_msg,
            position: 'left center',
        }).pointer('open');
    });
    $('#zurcf7_enable_sent_login_url').on('mouseenter click', function() {
        $('body .wp-pointer-buttons .close').trigger('click');
        $('#zurcf7_enable_sent_login_url').pointer({
            pointerClass: 'wp-pointer zurcf7-pointer',
            content: cf7forms_data.zurcf7_enable_sent_login_url,
            position: 'left center',
        }).pointer('open');
    });

    $('#zurcf7_debug_mode_status_msg').on('mouseenter click', function() {
        $('body .wp-pointer-buttons .close').trigger('click');
        $('#zurcf7_debug_mode_status_msg').pointer({
            pointerClass: 'wp-pointer zurcf7-pointer',
            content: cf7forms_data.zurcf7_debug_mode_status_msg,
            position: 'left center',
        }).pointer('open');
    });



    $('#zurcf7_email_field_msg').on('mouseenter click', function() {
        $('body .wp-pointer-buttons .close').trigger('click');
        $('#zurcf7_email_field_msg').pointer({
            pointerClass: 'wp-pointer zurcf7-pointer',
            content: cf7forms_data.zurcf7_email_field_msg,
            position: 'left center',
        }).pointer('open');
    });

    $('#zurcf7_username_field_msg').on('mouseenter click', function() {
        $('body .wp-pointer-buttons .close').trigger('click');
        $('#zurcf7_username_field_msg').pointer({
            pointerClass: 'wp-pointer zurcf7-pointer',
            content: cf7forms_data.zurcf7_username_field_msg,
            position: 'left center',
        }).pointer('open');
    });



    $('#zurcf7_userrole_field_msg').on('mouseenter click', function() {
        $('body .wp-pointer-buttons .close').trigger('click');
        $('#zurcf7_userrole_field_msg').pointer({
            pointerClass: 'wp-pointer zurcf7-pointer',
            content: cf7forms_data.zurcf7_userrole_field_msg,
            position: 'left center',
        }).pointer('open');
    });

    $('#zurcf7_successurl_field_msg').on('mouseenter click', function() {
        $('body .wp-pointer-buttons .close').trigger('click');
        $('#zurcf7_successurl_field_msg').pointer({
            pointerClass: 'wp-pointer zurcf7-pointer',
            content: cf7forms_data.zurcf7_successurl_field_msg,
            position: 'left center',
        }).pointer('open');
    });
    $('#zurcf7_acf_field_mapping').on('mouseenter click', function() {
        $('body .wp-pointer-buttons .close').trigger('click');
        $('#zurcf7_acf_field_mapping').pointer({
            pointerClass: 'wp-pointer zurcf7-pointer',
            content: cf7forms_data.zurcf7_acf_field_mapping,
            position: 'left center',
        }).pointer('open');
    });
    $('#zurcf7_fb_signup_app_id_tool').on('mouseenter click', function() {
        $('body .wp-pointer-buttons .close').trigger('click');
        $('#zurcf7_fb_signup_app_id_tool').pointer({
            pointerClass: 'wp-pointer zurcf7-pointer',
            content: cf7forms_data.zurcf7_fb_signup_app_id_tool,
            position: 'left center',
        }).pointer('open');
    });
    $('#zurcf7_fb_app_secret_tool').on('mouseenter click', function() {
        $('body .wp-pointer-buttons .close').trigger('click');
        $('#zurcf7_fb_app_secret_tool').pointer({
            pointerClass: 'wp-pointer zurcf7-pointer',
            content: cf7forms_data.zurcf7_fb_app_secret_tool,
            position: 'left center',
        }).pointer('open');
    });

})(jQuery);

(function() {
    //Hide notification
    document.getElementById('zeal-user-reg-cf7').style.display = 'none';
    //form selection
    var $selectFormDropDown = document.getElementById("zurcf7_formid");
    $selectFormDropDown.addEventListener('change', function(event) {
        if (event.target.value != '') {
            $selectFormDropDown.classList.remove("zurcf7_error");
        } else {
            $selectFormDropDown.classList.add("zurcf7_error");
        }
    });

    var $emailField = document.getElementById("zurcf7_email_field");
    $emailField.addEventListener('change', function(event) {
        if (event.target.value != '') {
            $emailField.classList.remove("zurcf7_error");
        } else {
            $emailField.classList.add("zurcf7_error");
        }
    });

    var $usernameField = document.getElementById("zurcf7_username_field");
    $usernameField.addEventListener('change', function(event) {
        if (event.target.value != '') {
            $usernameField.classList.remove("zurcf7_error");
        } else {
            $usernameField.classList.add("zurcf7_error");
        }
    });


    var $userRole = document.getElementById("zurcf7_userrole_field");
    $userRole.addEventListener('change', function(event) {
        if (event.target.value != '') {
            $userRole.classList.remove("zurcf7_error");
        } else {
            $userRole.classList.add("zurcf7_error");
        }
    });


})();

// Get the button, and when the user clicks on it, execute myFunction
document.getElementById("setting_zurcf7_submit").onclick = function(event) {
    var $flag = true,
        $selectForm = document.getElementById("zurcf7_formid"),
        $selectFormValue = $selectForm.options[$selectForm.selectedIndex].value,
        $emailField = document.getElementById("zurcf7_email_field"),
        $emailFieldValue = $emailField.options[$emailField.selectedIndex].value,
        $usernameField = document.getElementById("zurcf7_username_field"),
        $usernameFieldValue = $usernameField.options[$usernameField.selectedIndex].value,
        $userRoleField = document.getElementById("zurcf7_userrole_field"),
        $userRoleFieldValue = $userRoleField.options[$userRoleField.selectedIndex].value;


    if ($selectFormValue == '') {
        $selectForm.classList.add("zurcf7_error");
        $flag = false;
    }
    if ($emailFieldValue == '') {
        $emailField.classList.add("zurcf7_error");
        $flag = false;
    }
    if ($usernameFieldValue == '') {
        $usernameField.classList.add("zurcf7_error");
        $flag = false;
    }

    if ($userRoleFieldValue == '') {
        $userRoleField.classList.add("zurcf7_error");
        $flag = false;
    }


    //Main condition
    if ($flag == false) {
        document.getElementById('zeal-user-reg-cf7').style.display = 'block';
        window.scrollTo({ top: 0, behavior: 'smooth', });
        event.preventDefault();
    }
};

//On click reset settings button
document.getElementById("setting_reset").onclick = function(event) {
    var result = confirm("All settings will be reset and you can not restore it.");
    if (result == true) {} else {
        event.preventDefault();
        return false;
    }
};