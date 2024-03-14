(function($){

    jQuery(document).ready(function(){
        var selectValue = $(".mailer:checked").val();
        $('.mail-baby-smtp-mailer-options .mail-baby-smtp-mailer-option').removeClass('active').addClass('hidden');
        //Setting Value
        if(selectValue == 'php'){
            $('.mail-baby-smtp-mailer-option-mail').removeClass('hidden').addClass('active');
        }
        if(selectValue == 'smtp'){
            $('.mail-baby-smtp-mailer-option-smtpcom').removeClass('hidden').addClass('active');
        }
        if(selectValue == 'sendinblue'){
            $('.mail-baby-smtp-mailer-option-sendinblue').removeClass('hidden').addClass('active');
        }
        if(selectValue == 'mailgun'){
            $('.mail-baby-smtp-mailer-option-mailgun').removeClass('hidden').addClass('active');
        }
        if(selectValue == 'sendgrid'){
            $('.mail-baby-smtp-mailer-option-sendgrid').removeClass('hidden').addClass('active');
        }
        if(selectValue == 'gmail'){
            $('.mail-baby-smtp-mailer-option-gmail').removeClass('hidden').addClass('active');
        }
        if(selectValue == 'othersmtp'){
            $('.mail-baby-smtp-mailer-option-smtp').removeClass('hidden').addClass('active');
        }
        if(selectValue == 'mailbaby'){
            $('.mail-baby-smtp-mailer-option-mailbaby').removeClass('hidden').addClass('active');
        }
        jQuery(".mailer").on("click",function(){
            //Getting Value
            var selValue = $(".mailer:checked").val();
            $('.mail-baby-smtp-mailer-options .mail-baby-smtp-mailer-option').removeClass('active').addClass('hidden');
            //Setting Value
            if(selValue == 'php'){
                $('.mail-baby-smtp-mailer-option-mail').removeClass('hidden').addClass('active');
            }
            if(selValue == 'smtp'){
                $('.mail-baby-smtp-mailer-option-smtpcom').removeClass('hidden').addClass('active');
            }
            if(selValue == 'sendinblue'){
                $('.mail-baby-smtp-mailer-option-sendinblue').removeClass('hidden').addClass('active');
            }
            if(selValue == 'mailgun'){
                $('.mail-baby-smtp-mailer-option-mailgun').removeClass('hidden').addClass('active');
            }
            if(selValue == 'sendgrid'){
                $('.mail-baby-smtp-mailer-option-sendgrid').removeClass('hidden').addClass('active');
            }
            if(selValue == 'amazonses'){
                $('.mail-baby-smtp-mailer-option-amazonses').removeClass('hidden').addClass('active');
            }
            if(selValue == 'gmail'){
                $('.mail-baby-smtp-mailer-option-gmail').removeClass('hidden').addClass('active');
            }
            if(selValue == 'outlook'){
                $('.mail-baby-smtp-mailer-option-outlook').removeClass('hidden').addClass('active');
            }
            if(selValue == 'zohomail'){
                $('.mail-baby-smtp-mailer-option-zoho').removeClass('hidden').addClass('active');
            }
            if(selValue == 'othersmtp'){
                $('.mail-baby-smtp-mailer-option-smtp').removeClass('hidden').addClass('active');
            }
            if(selValue == 'mailbaby'){
                $('.mail-baby-smtp-mailer-option-mailbaby').removeClass('hidden').addClass('active');
            }
        });
    });

})(jQuery);
