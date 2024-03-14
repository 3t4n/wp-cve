(function($) {
    'use strict';

    $(document).ready(initScript);

    function initScript() {

        //defing global ajax post url
        window.ajaxPostUrl = ajax_object.ajax_url;
        // validating login form request
        wpmpValidateAndProcessLoginForm();
        // validating registration form request
        wpmpValidateAndProcessRegisterForm();
        // validating reset password form request
        wpmpValidateAndProcessResetPasswordForm();
        //Show Reset password
        wpmpShowResetPasswordForm();
        // validating Profile form request
        wpmpValidateAndProcessProfileForm();
        //Return to login
        wpmpReturnToLoginForm();
        generateCaptcha();

    }

    // Validate login form
    function wpmpValidateAndProcessLoginForm() {
        $('#wpmpLoginForm').formValidation({
            message: 'This value is not valid',
            icon: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                wpmp_username: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The username is required.'
                        }
                    }
                },
                wpmp_password: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required.'
                        }
                    }
                }
            }
        }).on('success.form.fv', function(e) {
            $('#wpmp-login-alert').hide();
            // You can get the form instance
            var $loginForm = $(e.target);
            // and the FormValidation instance
            var fv = $loginForm.data('formValidation');
            var content = $loginForm.serialize();

            // start processing
            $('#wpmp-login-loader-info').show();
            wpmpStartLoginProcess(content);
            // Prevent form submission
            e.preventDefault();
        });
    }

    // Make ajax request with user credentials
    function wpmpStartLoginProcess(content) {

        var loginRequest = jQuery.ajax({
            type: 'POST',
            url: ajaxPostUrl,
            data: content + '&action=wpmp_user_login',
            dataType: 'json',
            success: function(data) {
                $('#wpmp-login-loader-info').hide();
                // check login status
                if (true == data.logged_in) {
                    $('#wpmp-login-alert').removeClass('alert-danger');
                    $('#wpmp-login-alert').addClass('alert-success');
                    $('#wpmp-login-alert').show();
                    $('#wpmp-login-alert').html(data.success);

                    // redirect to redirection url provided
                    window.location = data.redirection_url;

                } else {

                    $('#wpmp-login-alert').show();
                    $('#wpmp-login-alert').html(data.error);

                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    //Validate profile update
    function wpmpValidateAndProcessProfileForm() {
        $('#wpmpProfileForm').formValidation({
            message: 'This value is not valid',
            icon: {
                required: 'glyphicon glyphicon-asterisk',
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                wpmp_fname: {
                    validators: {
                        notEmpty: {
                            message: 'The first name is required'
                        },
                        stringLength: {
                            max: 30,
                            message: 'The firstname must be less than 30 characters long'
                        }
                    }
                },                
                wpmp_email: {
                    validators: {
                        notEmpty: {
                            message: 'The email is required'
                        },
                        regexp: {
                            regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                            message: 'The value is not a valid email address'
                        }
                    }
                },
            }
        }).on('success.form.fv', function(e) {
            e.preventDefault();
            $('#wpmp-profile-alert').hide();           
            $('body, html').animate({
                scrollTop: 0
            }, 'slow');
            // You can get the form instance
            $('#wpmp-profile-loader-info').show();
            var formdata = new FormData($("#wpmpProfileForm")[0]);
            formdata.append('action', 'updateProfile');
            jQuery.ajax({
              url:ajax_object.ajax_url, 
              data:formdata,
              method:"POST",
              processData: false,
              contentType: false,
              success:function(data){  
                $('#wpmp-profile-loader-info').hide();
                 if (true == data.reg_status) {
                    $('#wpmp-profile-alert').removeClass('alert-danger');
                    $('#wpmp-profile-alert').addClass('alert-success');
                    $('#wpmp-profile-alert').show();
                    $('#wpmp-profile-alert').html(data.success);

                } else {
                    $('#wpmp-profile-alert').addClass('alert-danger');
                    $('#wpmp-profile-alert').show();
                    $('#wpmp-profile-alert').html(data.error);

                }
              }
          });
        }).on('err.form.fv', function(e) {
            console.log("ddd");
        });
    }


    // Validate registration form


    function randomNumber(min, max) {
        return Math.floor(Math.random() * (max - min + 1) + min);
    }

    function generateCaptcha() {
        $('#captchaOperation').html([randomNumber(1, 100), '+', randomNumber(1, 200), '='].join(' '));
    }

    // Validate registration form
    function wpmpValidateAndProcessRegisterForm() {
        $('#wpmpRegisterForm').formValidation({
            message: 'This value is not valid',
            icon: {
                required: 'glyphicon glyphicon-asterisk',
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                wpmp_fname: {
                    validators: {
                        notEmpty: {
                            message: 'The first name is required'
                        },
                        stringLength: {
                            max: 30,
                            message: 'The firstname must be less than 30 characters long'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z]*$/,
                            message: 'Only characters are allowed.'
                        }
                    }
                },
                wpmp_username: {
                    message: 'The username is not valid',
                    validators: {
                        notEmpty: {
                            message: 'The username is required'
                        },
                        stringLength: {
                            min: 6,
                            max: 30,
                            message: 'The username must be more than 6 and less than 30 characters long'
                        },
                        regexp: {
                            regexp: /^[a-zA-Z0-9_\.]+$/,
                            message: 'The username can only consist of alphabetical, number, dot and underscore'
                        }
                    }
                },
                wpmp_email: {
                    validators: {
                        notEmpty: {
                            message: 'The email is required'
                        },
                        regexp: {
                            regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                            message: 'The value is not a valid email address'
                        }
                    }
                },
                wpmp_password: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required'
                        },
                        stringLength: {
                            min: 6,
                            message: 'The password must be more than 6 characters long'
                        }
                    }
                },
                wpmp_password2: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required'
                        },
                        identical: {
                            field: 'wpmp_password',
                            message: 'The password and its confirm are not the same'
                        },
                        stringLength: {
                            min: 6,
                            message: 'The password must be more than 6 characters long'
                        }
                    }
                },
                wpmp_captcha: {
                    validators: {
                        callback: {
                            message: 'Wrong answer',
                            callback: function(value, validator, $field) {
                                var items = $('#captchaOperation').html().split(' '),
                                        sum = parseInt(items[0]) + parseInt(items[2]);
                                return value == sum;
                            }
                        }
                    }
                }
            }
        }).on('success.form.fv', function(e) {
            $('#wpmp-register-alert').hide();
            $('#wpmp-mail-alert').hide();
            $('body, html').animate({
                scrollTop: 0
            }, 'slow');
            // You can get the form instance
            var $registerForm = $(e.target);
            // and the FormValidation instance
            var fv = $registerForm.data('formValidation');
            var content = $registerForm.serialize();

            // start processing
            $('#wpmp-reg-loader-info').show();
            wpmpStartRegistrationProcess(content);
            // Prevent form submission
            e.preventDefault();
        }).on('err.form.fv', function(e) {
            // Regenerate the captcha
            generateCaptcha();
        });
    }


    // Make ajax request with user credentials
    function wpmpStartRegistrationProcess(content) {

        var registerRequest = $.ajax({
            type: 'POST',
            url: ajaxPostUrl,
            data: content + '&action=wpmp_user_registration',
            dataType: 'json',
            success: function(data) {

                $('#wpmp-reg-loader-info').hide();
                //check mail sent status
                if (data.mail_status == false) {

                    $('#wpmp-mail-alert').show();
                    $('#wpmp-mail-alert').html('Could not able to send the email notification.');
                }
                // check login status
                if (true == data.reg_status) {
                    $('#wpmp-register-alert').removeClass('alert-danger');
                    $('#wpmp-register-alert').addClass('alert-success');
                    $('#wpmp-register-alert').show();
                    $('#wpmp-register-alert').html(data.success);

                } else {
                    $('#wpmp-register-alert').addClass('alert-danger');
                    $('#wpmp-register-alert').show();
                    $('#wpmp-register-alert').html(data.error);

                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }

    function wpmpShowResetPasswordForm() {
        $('#btnForgotPassword').click(function() {
              $('#wpmpResetPasswordSection').removeClass('hidden');
              $('#wpmpLoginForm').slideUp(500);  
               $('#wpmpResetPasswordSection').slideDown(500);
        });
    }
    
    function wpmpReturnToLoginForm() {
        $('#btnReturnToLogin').click(function() {
              $('#wpmpResetPasswordSection').slideUp(500);              
              $('#wpmpResetPasswordSection').addClass('hidden');
              $('#wpmpLoginForm').removeClass('hidden');
              $('#wpmpLoginForm').slideDown(500);               
        });
    }

    // Validate reset password form
    //Neelkanth
    function wpmpValidateAndProcessResetPasswordForm() {

        $('#wpmpResetPasswordForm').formValidation({
            message: 'This value is not valid',
            icon: {
                required: 'glyphicon glyphicon-asterisk',
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                wpmp_rp_email: {
                    validators: {
                        notEmpty: {
                            message: 'Please enter your email address which you used during registration.'
                        },
                        regexp: {
                            regexp: '^[^@\\s]+@([^@\\s]+\\.)+[^@\\s]+$',
                            message: 'The value is not a valid email address'
                        }
                    }
                },
                wpmp_newpassword: {
                    validators: {
                        notEmpty: {
                            message: 'The password is required'
                        },
                        stringLength: {
                            min: 6,
                            message: 'The password must be more than 6 characters long'
                        }
                    }
                }
            }
        }).on('success.form.fv', function(e) {
            $('#wpmp-resetpassword-alert').hide();

            $('body, html').animate({
                scrollTop: 0
            }, 'slow');
            // You can get the form instance
            var $resetPasswordForm = $(e.target);
            // and the FormValidation instance
            var fv = $resetPasswordForm.data('formValidation');
            var content = $resetPasswordForm.serialize();
            
            // start processing
            $('#wpmp-resetpassword-loader-info').show();
            wpmpStartResetPasswordProcess(content);
            // Prevent form submission
            e.preventDefault();
        });
    }

    // Make ajax request with email
    //Neelkanth
    function wpmpStartResetPasswordProcess(content) {
        
        var resetPasswordRequest = jQuery.ajax({
            type: 'POST',
            url: ajaxPostUrl,
            data: content + '&action=wpmp_resetpassword',
            dataType: 'json',
            success: function(data) {
                
                $('#wpmp-resetpassword-loader-info').hide();
                // check login status
                if (data.success) {
                    
                    $('#wpmp-resetpassword-alert').removeClass('alert-danger');
                    $('#wpmp-resetpassword-alert').addClass('alert-success');
                    $('#wpmp-resetpassword-alert').show();
                    $('#wpmp-resetpassword-alert').html(data.success);

                } else {

                    $('#wpmp-resetpassword-alert').show();
                    $('#wpmp-resetpassword-alert').html(data.error);

                }
            },
            error: function(data) {
                console.log(data);
            }
        });
    }



})(jQuery);
