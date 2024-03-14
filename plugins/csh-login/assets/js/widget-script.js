jQuery(document).ready(function($) {
    
    if (jsPassData.generated_pass == 'on') {
        $("#allow_pass").show();
    }else {
        $("#allow_pass").hide();
    }

    $(".go_to_login_link").click(function(e) {
        if (jsPassData.type_modal != 'LinkToDefault') {
            e.stopPropagation();
            e.preventDefault();
            var offset = $( this ).offset();

            if ($("#csh-login-wrap").css("display") == "none") {
                var windowsize = $(window).width();
                if (jsPassData.type_modal == 'Dropdown') {
                    $("#csh-login-wrap").css('position', 'absolute');
                    $("#csh-login-wrap").css('top', offset.top + 30);
                    $("#csh-login-wrap").css('left', offset.left);
                }

                $("#csh-login-wrap").slideToggle(250);
                $(".login_form").show();
                $("#login_user").focus();
                $(".register_form").hide();
                $(".lost_pwd_form").hide();
                $(".back_login").hide();
                $(".alert_status").hide();
            }else {
                $("#csh-login-wrap").slideUp(200);
            }

            //clear form fields
            //$(".login_form").find("input[type=text],input[type=email],input[type=password],textarea").val("");
            $(".register_form").find("input[type=text],input[type=email],input[type=password],textarea").val("");
            $(".lost_pwd_form").find("input[type=email],textarea").val("");

            //clear validation.
            var login_validator = $(".login_form").validate();
            login_validator.resetForm();
        }
    });
    
    $(".menu_register_link").click(function(e) {
        if (jsPassData.type_modal != 'LinkToDefault') {
            e.stopPropagation();
            e.preventDefault();
            var offset = $( this ).offset();
            var windowsize = $(window).width();
            if (jsPassData.type_modal == 'Dropdown') {
                $("#csh-login-wrap").css('position', 'absolute');
                $("#csh-login-wrap").css('top', offset.top + 30);
                $("#csh-login-wrap").css('left', offset.left);
            }

            $("#csh-login-wrap").slideToggle(250);
            $(".login_form").hide();
            $(".register_form").show();
            $("#register_user").focus();
            $(".lost_pwd_form").hide();
            $(".back_login").show();
            $(".alert_status").hide();
            //clear form fields
            $(".register_form").find("input[type=text],input[type=email],input[type=password],textarea").val("");
            $(".lost_pwd_form").find("input[type=email],textarea").val("");

            //clear validation.
            var register_validator = $(".register_form").validate();
            register_validator.resetForm();

        }
    });

    $(document).keydown(function(e) {
        if (e.keyCode === 27) {
            e.stopPropagation();
            $("#csh-login-wrap").slideUp(200);
        }
    });

    var mouse_is_inside = false;
    $('#csh-login-wrap').hover(function() {
        mouse_is_inside = true;
    }, function() {
        mouse_is_inside = false;
    });

    $("body").mousedown(function() {
        if (!mouse_is_inside) {
            $('#csh-login-wrap').slideUp(200);
        }
    });

    $('.boxclose').click(function() {
        $("#csh-login-wrap").slideUp(200);
    });
    //Login form
    $(".go_to_register_link").click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(".login_form").hide();
        $(".lost_pwd_form").hide();
        $(".register_form").show();
        $("#register_user").focus();
        $(".back_login").show();
        $(".alert_status").hide();

        //clear validation.
        var register_validator = $(".register_form").validate();
        register_validator.resetForm();
    });

    $(".go_to_lostpassword_link").click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(".login_form").hide();
        $(".lost_pwd_form").show();
        $("#lost_pwd_user_email").focus();
        $(".register_form").hide();
        $(".back_login").show();
        $(".alert_status").hide();

        //clear validation.
        var lostpwd_validator = $(".lost_pwd_form").validate();
        lostpwd_validator.resetForm();
    });

    $(".back_login").click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        $(".login_form").show();
        $("#login_user").focus();
        $(".register_form").hide();
        $(".lost_pwd_form").hide();
        $(".back_login").hide();
        $(".alert_status").hide();
    });
    //validate form.
    $('.login_form').removeData('validator');
    $('.register_form').removeData('validator');
    $('.lost_pwd_form').removeData('validator');

    $('.login_form').removeData('unobtrusiveValidation');
    $('.register_form').removeData('unobtrusiveValidation');
    $('.lost_pwd_form').removeData('unobtrusiveValidation');

    var login_form = $(".login_form");
    login_form.validate({
        invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                $('.alert_status').show();  
                $('.alert_status').val(validator.errorList[0].message);
                validator.errorList[0].element.focus(); //Set Focus
            }
        },
        rules: {
            login_user: {
                required: true,
            },
            pass_user: {
                required: true,
                minlength: 6
            },
        },
        messages: {
            login_user: {required: "Enter your name"},
            pass_user: {required: "Enter your Password", minlength:"At least 6 characters"}                        
        },
        errorPlacement: function(error, element) {
            //Nothing
        }
    });

    var register_form = $(".register_form");
    register_form.validate({
        invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                $('.alert_status').show();  
                $('.alert_status').val(validator.errorList[0].message);
                validator.errorList[0].element.focus(); //Set Focus
            }
        },
        rules: {
            register_user: {
                required: true,
            },
            register_email: {
                required: true,
                email: true,
            },
            register_pass: {
                required: true,
                minlength: 6
            },
            confirm_pass: {
                required: true,
                minlength: 6,
                equalTo: "#register_pass",
            },
        },
        messages: {
            register_user: {required: "Enter your name"},
            register_email: {required: "Enter the email",email: "Invalid email address" },
            register_pass: {required: "Enter the password.",minlength:"At least 6 characters"},
            confirm_pass: {required: "Enter the password confirm.",equalTo:"Confirm password not match"}                        
        },
        errorPlacement: function(error, element) {
            //
        }
    });

    var lost_pwd_form = $(".lost_pwd_form");
    lost_pwd_form.validate({
        invalidHandler: function(form, validator) {
            var errors = validator.numberOfInvalids();
            if (errors) {
                $('.alert_status').show();  
                $('.alert_status').val(validator.errorList[0].message);
                validator.errorList[0].element.focus(); //Set Focus
            }
        },
        rules: {
            lost_pwd_user_email: {
                required: true,
            },
        },
        messages: {
            lost_pwd_user_email: {required: "Enter your E-mail"},                     
        },
        errorPlacement: function(error, element) {
            //
        }
    });
    //login form ajax.
    $(".login_submit").click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (login_form.valid()) {
            var user = $("#login_user").val();
            var password = $("#pass_user").val();
            var rememberme = $("#rememberme").val();
            var arlet_string = '';
            $(".alert_status").hide();
            $.ajax({
                type: 'POST',
                data: {
                    'action': 'login_submit',
                    'user': user,
                    'password': password,
                    'rememberme': rememberme,
                },
                url: jsPassData.ajax_url,
                success: function(data) {
                    if (data.login_status == "OK") {
                        arlet_string = 'Login Successfull! ';
                        $(".alert_status").val(arlet_string);
                        $(".alert_status").show();

                        if (jsPassData.get_login_redirect == 'Home Page') {
                            $(".login_form").attr('action', jsPassData.login_redirect);
                            $(".login_form").submit();
                        }

                        if (jsPassData.get_login_redirect == 'Current Page') {
                            $(".login_form").attr('action', "");
                            $(".login_form").submit();
                        }

                        if (jsPassData.get_login_redirect == 'Custom URL') {
                            window.location.href = jsPassData.login_redirect;
                        }
                    }else {
                        arlet_string = 'Wrong Username or Password! ';
                        $(".alert_status").val(arlet_string);
                        $(".alert_status").show();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle any errors
                }
            });
        }
    });

    //register form ajax.
    $("#register_submit").click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (register_form.valid()) {
            var register_user = $("#register_user").val();
            var register_email = $("#register_email").val();
            var password_register = $("#register_pass").val();
            var repass_register = $("#confirm_pass").val();

            //gcaptcha data
            var dataArray = $("#register_form").serializeArray(),
                len = dataArray.length,
                dataObj = {};
            for (i=0; i<len; i++) {
              dataObj[dataArray[i].name] = dataArray[i].value;
            }

            var arlet_string = '';
            $(".alert_status").hide();
            $.ajax({
                type: 'POST',
                data: {
                    'action': 'register_submit',
                    'register_user': register_user,
                    'register_email': register_email,
                    'password_register': password_register,
                    'g-recaptcha-response': dataObj['g-recaptcha-response'],
                },
                url: jsPassData.ajax_url,
                success: function(data) {
                    if (data.captcha == 'INCORRECT') {
                        arlet_string = 'Captcha is required!';
                        $(".alert_status").val(arlet_string);
                        $(".alert_status").show();
                    }else {
                        if (data.register_status == "OK") {
                            if (jsPassData.generated_pass == 'on') {
                                arlet_string = 'Register Successfull!';
                            }else{
                                arlet_string = 'Register Successfull, Check your E-mail';
                            }

                            $(".alert_status").val(arlet_string);
                            $(".alert_status").show();
                            setTimeout(function() {
                              window.location = jsPassData.register_redirect;
                            }, 2000); 
                        }else {
                            if (data.register_status == 'ERRORMAIL') {
                                arlet_string = 'Sorry! We cant sent email for you';
                            }else{
                                arlet_string = 'Email already exists';
                            }
                            $(".alert_status").val(arlet_string);
                            $(".alert_status").show();
                        }
                    }
                    
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle any errors
                }
            });
        }
    });

    //lost password form.
    $("#lost_pwd_submit").click(function(e) {
        e.preventDefault();
        e.stopPropagation();
        if (lost_pwd_form.valid()) {
            var lost_pwd_user_email = $("#lost_pwd_user_email").val();
            var arlet_string = '';
            $(".alert_status").hide();
            $.ajax({
                type: 'POST',
                data: {
                    'action': 'lost_pwd_submit',
                    'lost_pwd_user_email': lost_pwd_user_email,
                },
                url: jsPassData.ajax_url,
                success: function(data) {
                    if (data.lost_pwd_status == "OK") {
                        arlet_string = 'Check your email for a link to reset your password. ';
                        $(".alert_status").val(arlet_string);
                        $(".alert_status").show();
                    }else {
                        if (data.lost_pwd_status == 'ERRORMAIL') {
                            arlet_string = 'Cant sent Email';
                        }else{
                            arlet_string = 'Not registered User or Email';
                        }
                        $(".alert_status").val(arlet_string);
                        $(".alert_status").show();
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    // Handle any errors
                }
            });
        }
    });

    //for login facebook.
    if (window.location.hash == '#_=_'){
        // Check if the browser supports history.replaceState.
        if (history.replaceState) {
            // Keep the exact URL up to the hash.
            var cleanHref = window.location.href.split('#')[0];
            // Replace the URL in the address bar without messing with the back button.
            history.replaceState(null, null, cleanHref);

        }else {
            // Well, you're on an old browser, we can get rid of the _=_ but not the #.
            window.location.hash = '';

        }
    }

});