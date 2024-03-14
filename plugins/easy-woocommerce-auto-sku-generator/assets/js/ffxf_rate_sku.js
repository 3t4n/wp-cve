(function ($) {


    // Run the function - I already left a review
    // Add 5 years
    $.function_i_have_already_by_ajax_callback = function(){
        var data = {
            'action': 'i_have',
            'name'  : 'i_have_already_by_ajax_callback'
        };

        $.post(ajaxurl, data, function(response) {
            $( "#ffxf_rate_sku .notice-dismiss" ).trigger( "click" );
        });
    };


    // Run the function - Remind me later
    // Add 1 day
    $.function_remind_me_later_by_ajax_callback = function(){
        var data = {
            'action': 'remind_me_later',
            'name'  : 'remind_me_later_by_ajax_callback'
        };

        $.post(ajaxurl, data, function(response) {
            $( "#ffxf_rate_sku .notice-dismiss" ).trigger( "click" );
        });
    };



    // We make the transition to the feedback page in the repository and say - thanks
    // Add 5 years
    $.function_leave_feedback_by_ajax_callback = function(){
        var data = {
            'action': 'leave_feedback',
            'name'  : 'leave_feedback_by_ajax_callback'
        };

        $.post(ajaxurl, data, function(response) {
            $( "#ffxf_rate_sku" ).removeClass('notice-info').addClass('notice-success').empty().append('<p>'+ffxf_sp.ffxf_sp+'</p>');
        });
    };

    $('#i_have_already_by_ajax_callback').click(function (e) {
        e.preventDefault();
        $.function_i_have_already_by_ajax_callback();
    });

    $('#remind_me_later_by_ajax_callback').click(function (e) {
        e.preventDefault();
        $.function_remind_me_later_by_ajax_callback();
    });

    $('#leave_feedback').click(function (e) {
        $.function_leave_feedback_by_ajax_callback();
    });

})(jQuery);