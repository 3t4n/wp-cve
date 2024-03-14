jQuery(document).ready(function($) {

    function ultimate_subscribe_panel_tab_set(tab_id) {
        $.cookie('ultimate_subscribe_active_tab', tab_id);
        var obj = $('#' + tab_id);
        var tab = obj.children('a').attr('href');
        $('.panel-nav-tabs li').removeClass('active');
        obj.addClass('active');
        $('.tab-pane').removeClass('active');
        $('.tab-pane').removeAttr('style');
        $(tab).fadeIn();
        $(tab).addClass('active');
    }

    var current_tab = $.cookie('ultimate_subscribe_active_tab');
    ultimate_subscribe_panel_tab_set(current_tab);

    $(document).on('click', '.ultimate-subscribe-panel .panel-nav-tabs li', function(event) {
        event.preventDefault();
        var tab_id = $(this).attr('id');
        ultimate_subscribe_panel_tab_set(tab_id);
    });

    $('input.color').colorPicker();


    
    $("#us-import-form").on('submit', (function(e) {
        e.preventDefault();


        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            beforeSend: function() {
                /*$('.import-progress-con').show();
                $('.import-progress').css('width', '0px');
                $('.import-progress').html('0%');*/
            },
            success: function(data) {
                /*console.log('n',data);
                $('.import-progress').css('width', data+'px');
                $('.import-progress').html(data+'%');
                if(data ==100){
                    $("us-import-form")[0].reset();
                }*/
            },
            complete: function() {
                // console.log('asdf');
                $("#us-import-form")[0].reset();
            },
            error: function(e) {
                // $("#err").html(e).fadeIn();
            }
        });
    }));


});