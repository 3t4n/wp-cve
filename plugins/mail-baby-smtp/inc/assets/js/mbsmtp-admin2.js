(function($){

    jQuery(document).ready(function(){
        jQuery("#mailer").on("change",function(){
            //Getting Value
            var selValue = $("#mailer").val();
            $('.baby-mail-smtp-mailer-options .baby-mail-smtp-mailer-option').removeClass('active').addClass('hidden');
            //Setting Value
            if(selValue == 'php'){
                $('.baby-mail-smtp-mailer-option-mail').removeClass('hidden').addClass('active');
            }
            if(selValue == 'smtp'){
                $('.baby-mail-smtp-mailer-option-smtpcom').removeClass('hidden').addClass('active');
            }
            if(selValue == 'sendinblue'){
                $('.baby-mail-smtp-mailer-option-sendinblue').removeClass('hidden').addClass('active');
            }
            if(selValue == 'mailgun'){
                $('.baby-mail-smtp-mailer-option-mailgun').removeClass('hidden').addClass('active');
            }
            if(selValue == 'sendgrid'){
                $('.baby-mail-smtp-mailer-option-sendgrid').removeClass('hidden').addClass('active');
            }
            if(selValue == 'amazonses'){
                $('.baby-mail-smtp-mailer-option-amazonses').removeClass('hidden').addClass('active');
            }
            if(selValue == 'gmail'){
                $('.baby-mail-smtp-mailer-option-gmail').removeClass('hidden').addClass('active');
            }
            if(selValue == 'outlook'){
                $('.baby-mail-smtp-mailer-option-outlook').removeClass('hidden').addClass('active');
            }
            if(selValue == 'zohomail'){
                $('.baby-mail-smtp-mailer-option-zoho').removeClass('hidden').addClass('active');
            }
            if(selValue == 'othersmtp'){
                $('.baby-mail-smtp-mailer-option-smtp').removeClass('hidden').addClass('active');
            }
            if(selValue == 'babymail'){
                $('.baby-mail-smtp-mailer-option-babymail').removeClass('hidden').addClass('active');
            }
        });
    });

})(jQuery);
