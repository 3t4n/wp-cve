
// on dom load

var Hurrytimer_Utils = {

    // a function to set cookie
    setCookie: function(cname, cvalue, exdays) {
        var d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        var expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + "; " + expires  + ";path=/";
    }
};
 
(function($){

    // Dismiss the Leave review notice if user clicked the "Already done!" link.

    $(document).on('click', '#hurryt-remind-review-notice' , function () {
        $(this).closest('.notice').remove();
        Hurrytimer_Utils.setCookie('hurryt_leave_review_remind', '1', 1);
        $.post(hurrytimer_ajax_review.url, {
            action: 'hurryt_remind_leave_review_notice',
            nonce: hurrytimer_ajax_review.nonce
        });
    });
    


    // Dismiss the Leave review notice if user clicked the "Already done!" link.
    $(document).on('click', '#hurryt-dismiss-review-notice', function () {
        $(this).closest('.notice').remove();
        Hurrytimer_Utils.setCookie('hurryt_leave_review_dismissed', '1', 180);
        $.post(hurrytimer_ajax_review.url, {
            action: 'hurryt_dismiss_leave_review_notice',
            nonce: hurrytimer_ajax_review.nonce
        });
    });
    
})(jQuery);

    
