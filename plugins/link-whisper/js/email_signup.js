jQuery(document).ready(function($){
    setTimeout(function(){
        // unhide the notice after the timeout to eliminate jumping
        $('.wpil-email-signup-offer').css('display', 'flex');

        // listen for the user dismissing the email sign up notice, and hide the notice if he does dismiss it
        $('.wpil-email-signup-offer .notice-dismiss').on('click', function(e){
            $.ajax({
                type: 'POST',
                url: wpil_ajax.ajax_url,
                data: {
                    action: 'dismiss_email_offer_notice',
                    nonce: wpil_ajax.wpil_email_dismiss_nonce,
                    current_user: wpil_ajax.current_user
                },
                success: function(response){
                    console.log(response);
                },
            });
        });
        
        var formSubmitted = null,
            responseCheck = 0;
        // listen for the email subscribe form being submitted
        $('.wpil-email-signup-offer form.email-signup-inputs').on('click', function(e){
            // when it has been submitted, check the form for a success message every quarter second
            formSubmitted = setInterval(function(){
                // find out if there's a success message
                var success = $('.wpil-email-signup-offer form.email-signup-inputs').find('.formkit-alert-success').length;
                // if there is a success message and we've been waiting less than 5 seconds
                if(success && responseCheck <= 20){
                    // make an ajax call to hide the email signup form
                    $.ajax({
                        type: 'POST',
                        url: wpil_ajax.ajax_url,
                        data: {
                            action: 'signed_up_email_offer_notice',
                            nonce: wpil_ajax.wpil_email_dismiss_nonce,
                            current_user: wpil_ajax.current_user
                        },
                        success: function(response){
                            console.log(response);
                            // stop watching since we've stored the admin's subscribe status
                            clearInterval(formSubmitted);
                        },
                    });
                    // increase the response check regardless of what happens
                    responseCheck++;
                }else if(responseCheck > 20){
                    // if we've been waiting for the success message for 5 seconds, quit watching
                    clearInterval(formSubmitted);
                }else{
                    // if we haven't been watching for 5 seconds and there isn't a success message, wait another quarter second
                    responseCheck++;
                }
            }, 250);
        });
    }, 100);
});
