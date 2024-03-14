(function ($) {


    // Run the function - I already left a review
    // Add 5 years
    $.function_related_i_have_already_by_ajax_callback = function(){
        var data = {
            'action': 'i_have_related_and_upsells',
            'name': 'i_have_already_related_and_upsells_by_ajax_callback'
        };

        $.post(ajaxurl, data, function(response) {
            $( "#ffxf_rate_related_and_upsells > .notice-dismiss" ).trigger( "click" );
        });
    };


    // Run the function - Remind me later
    // Add 1 day
    $.function_related_remind_me_later_by_ajax_callback = function(){
        var data = {
            'action': 'remind_me_later_related_and_upsells',
            'name': 'remind_me_later_related_and_upsells_by_ajax_callback'
        };

        $.post(ajaxurl, data, function(response) {
            $( "#ffxf_rate_related_and_upsells > .notice-dismiss" ).trigger( "click" );
        });
    };



    // We make the transition to the feedback page in the repository and say - thanks
    // Add 5 years
    $.function_related_leave_feedback_by_ajax_callback = function(){
        var data = {
            'action': 'leave_feedback_related_and_upsells',
            'name': 'leave_feedback_related_and_upsellsby_ajax_callback'
        };

        $.post(ajaxurl, data, function(response) {
            $( "#ffxf_rate_related_and_upsells" ).removeClass('notice-info').addClass('notice-success').empty().append('<p>'+ffxf_sp.ffxf_sp+'</p>');
        });
    };

    $('#i_have_already_related_and_upsells_by_ajax_callback').click(function (e) {
        e.preventDefault();
        $.function_related_i_have_already_by_ajax_callback();
    });

    $('#remind_me_later_related_and_upsells_by_ajax_callback').click(function (e) {
        e.preventDefault();
        $.function_related_remind_me_later_by_ajax_callback();
    });

    $('#leave_feedback_related_and_upsells_by_ajax_callback').click(function (e) {
        $.function_related_leave_feedback_by_ajax_callback();
    });

})(jQuery);