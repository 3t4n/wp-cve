function validateEmail($email) {
    var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
    return emailReg.test( $email );
}

function callRegister(){

    // Validate the form
    jQuery.validity.start();
    jQuery('#socialboost_reg_email_user').require('Please add email');
    jQuery('#socialboost_reg_email_user').match('email','Please add valid email');
    jQuery('#socialboost_reg_firstname').require('Please add first name');
    jQuery('#socialboost_reg_lastname').require('Please add last name');
    var result = jQuery.validity.end();

    if(result.valid)
    {
        jQuery('#createButton').val('Saving Settings..');
        jQuery('#createButton').attr('disabled','disabled');

        var $modal = jQuery('.js-loading-bar');
        $modal.show();
        jQuery('.modal-backdrop').appendTo('#registerBlock');

        jQuery.post(
            ajaxurl,
            jQuery('#registerForm').serialize()+'&raffd='+jQuery('#raffd').val(), 
            function(response){

                if(response.sb_reg == 0)
                {
                    jQuery('#sb_launch_link').attr('href', response.frame_url);
                    setTimeout(function(){
                        jQuery('#settingBlock').show();
                        jQuery('#registerBlock, .sbBlkNonFrame').hide();
                        $modal.hide();
                    }, 1500);
                }
                else if(response.sb_reg == 2)
                {
                    jQuery('.error_msg').html(response.message);                    
                    jQuery('#registerBlock, #loaderBlock').hide();
                    jQuery('.alertBox').show();
                    jQuery('#loginBlock').show();
                    $modal.hide();
                }
                else
                {
                    if(typeof response.message !== "undefined") {
                        jQuery('.error_msg').html(response.message);
                        jQuery('.alertBox').show();
                    }

                    jQuery('#createButton').removeAttr('disabled');
                    jQuery('#createButton').val('Next');
                    $modal.hide();
                }
            },'json'
        );
    }
    else
    {
        //alert('Please clear errors while input.');
        return false;
    }
}

function callVerify(){

    // Validate the form
    jQuery.validity.start();
    jQuery('#admin_email').require('Please add email');
    jQuery('#admin_email').match('email','Please add proper format email');
    var result = jQuery.validity.end();

    if(result.valid)
    {		
        jQuery('#verifyButton').val('Updating Settings..');
        jQuery('#verifyButton').attr('disabled','disabled');

        var $modal = jQuery('.js-loading-bar3');
        $modal.show();
        jQuery('.modal-backdrop').appendTo('#loaderBlock');

        jQuery.post(
            ajaxurl,
            jQuery('#verifyForm').serialize()+'&raffd='+jQuery('#raffd').val(),
            function(response){

                if(response.sb_reg == 1)
                {
                    jQuery('.error_msg').html(response.msg);
                    jQuery('.alertBox').show();
                    jQuery('#verifyForm').show();
                    jQuery('#verifyButton').val('Update');
                    jQuery('#verifyButton').removeAttr('disabled');
                    $modal.hide();
                }
                else if(response.sb_reg == 0)
                {
                    jQuery('#sb_launch_link').attr('href', response.frame_url);
                    setTimeout(function(){
                        jQuery('#settingBlock').show();
                        jQuery('#loaderBlock, .sbBlkNonFrame').hide();
                        $modal.hide();
                    },500);
                }
                else
                {
                    jQuery('#registerBlock').show();
                    jQuery('#loaderBlock').hide();
                    $modal.hide();
                }
            },'json'
        );
    }
}

function callLoader(){

    var $modal = jQuery('.js-loading-bar3');
    $modal.show();

    jQuery.post(
        ajaxurl,
        {action:'check_grvlsw_settings',raffd: jQuery('#raffd').val()}, 
        function(response){

            if(response.sb_reg == 0){
                jQuery('#sb_launch_link').attr('href', response.frame_url);
                setTimeout(function(){
                    jQuery('#settingBlock').show();
                    jQuery('#loaderBlock, .sbBlkNonFrame').hide();
                    $modal.hide();
                },500);
            }
            else if(response.sb_reg == 2 || response.sb_reg == 3)
            {
                if(typeof response.message !== "undefined") {
                    jQuery('.error_msg').html(response.message);
                    jQuery('.alertBox').show();
                }
                jQuery('#loginBlock').show();
                jQuery('#loaderBlock').hide();
                $modal.hide();
            }
            else
            {
                if(typeof response.message !== "undefined") {
                    jQuery('.error_msg').html(response.message);
                    jQuery('.alertBox').show();
                }
                jQuery('#registerBlock').show();
                jQuery('#loaderBlock').hide();
                $modal.hide();
            }

        },'json'
    );
}

function callLogin() {

    // Validate login form
    jQuery.validity.start();
    jQuery('#socialboost_login_email').require('Please add email');
    jQuery('#socialboost_login_email').match('email','Please add valid email');
    jQuery('#socialboost_login_pwd').require('Please add password');
    var result = jQuery.validity.end();

    if(result.valid)
    {
        jQuery('#loginButton').val('Checking Login..');
        jQuery('#loginButton').attr('disabled','disabled');

        var $modal = jQuery('.js-loading-bar');
        $modal.show();
        jQuery('.modal-backdrop').appendTo('#loginBlock');

        jQuery.post(
            ajaxurl,
            jQuery('#loginForm').serialize()+'&raffd='+jQuery('#raffd').val(), 
            function(response){
                if(response.error == 0)
                {
                    jQuery('#sb_launch_link').attr('href', response.frame_url);
                    setTimeout(function(){
                        jQuery('#settingBlock').show();
                        jQuery('#loginBlock, .sbBlkNonFrame').hide();
                        $modal.hide();
                    },1500);
                }
                else
                {
                    if(typeof response.message !== "undefined") {
                        jQuery('.error_msg').html(response.message);
                        jQuery('.alertBox').show();
                    }

                    jQuery('#loginButton').removeAttr('disabled');
                    jQuery('#loginButton').val('Login');
                    $modal.hide();
                }
            },'json'
        );

    }else{
        //alert('Please clear errors while input.');
        return false;
    }
}

jQuery(document).ready(function(){		

    jQuery('.js-loading-bar').modal({
        backdrop: 'static',
        show: false
    });

    if(jQuery('#sbRegisterAr').val()	== 2){
        jQuery('.js-loading-bar3').modal({
            backdrop: 'static',
            show: false
        });
        callLoader();
    }

    // Added for success tick
    jQuery('.inputBox input').on('input', function(){
        var re = /\S+@\S+\.\S+/;
        if(jQuery(this).data('type') == 'email') {
            if( jQuery(this).val() != '' && re.test( jQuery(this).val() ) ) {
                jQuery(this).parent().addClass('success').removeClass('errorBox');
                if(jQuery(this).parent().find('.error').length > 0) {
                    jQuery(this).parent().find('.error').remove();
                }
            }
            else {
                jQuery(this).parent().removeClass('success').addClass('errorBox');
            }
        }
        else if( jQuery(this).val() != '') {
            jQuery(this).parent().addClass('success').removeClass('errorBox');
            if(jQuery(this).parent().find('.error').length > 0) {
                jQuery(this).parent().find('.error').remove();
            }
        }
        else {
            jQuery(this).parent().removeClass('success').addClass('errorBox');
        }
    });

    jQuery('body').tooltip({selector: '[data-toggle=tooltip]'});
});
