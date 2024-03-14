jQuery(document).ready(function($){
    setTimeout(function(){
        // unhide the notice after the timeout to eliminate jumping
        $('.wpil-review-offer').css('display', 'flex');

        // listen for the user dismissing the email sign up notice, and hide the notice if he does dismiss it
        $('.wpil-review-offer .notice-dismiss, .wpil-review-offer .notice-temp-dismiss').on('click', function(e){
            $(this).css({'opacity': 0.75, 'cursor': 'default', 'background': '#007cba !important'});
            $(this).parents('.wpil-review-offer').fadeOut(300);

            $.ajax({
                type: 'POST',
                url: wpil_ajax.ajax_url,
                data: {
                    action: 'dismiss_review_notice',
                    nonce: wpil_ajax.wpil_review_nonce,
                    current_user: wpil_ajax.current_user
                },
                success: function(response){
                    console.log(response);
                },
            });
        });
        
        // add an active class to the perm dismiss buttons
        $('.wpil-review-offer .notice-perm-dismiss').on('focus', function(e){
            e.preventDefault();
            $(this).css({'opacity': 0.75, 'cursor': 'default', 'background': '#007cba !important'});
        });

        // listen for the user deciding to review or not to review the plugin
        $('.wpil-review-offer .notice-perm-dismiss').on('click', function(e){
            var button = this;

            $.ajax({
                type: 'POST',
                url: wpil_ajax.ajax_url,
                async: false, // wait until the action is complete before moving on
                data: {
                    action: 'perm_dismiss_review_notice',
                    nonce: wpil_ajax.wpil_review_dismiss_nonce,
                    current_user: wpil_ajax.current_user,
                    leaving_review: $(button).prop('id') === 'wpil-review-plugin' ? 1: 0
                },
                success: function(response){
                    console.log(response);
                },
                complete: function(){
                    $(button).removeAttr('style');
                    $(button).parents('.wpil-review-offer').fadeOut(300);
                }
            });

            if($(button).prop('id') === 'wpil-dont-review-plugin'){
                e.preventDefault();
            }
        });

    }, 100);
});
