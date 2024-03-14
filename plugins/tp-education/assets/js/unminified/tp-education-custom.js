jQuery(document).ready(function($){

    /*------------------------------------------------
                    TABS
    ------------------------------------------------*/
    $('ul.tabs.tp-education-search-tabs li').click(function() {
        var tab_id = $(this).attr('data-tab');

        $('ul.tabs.tp-education-search-tabs li').removeClass('active');
        $('.tab-content').removeClass('active');

        $(this).addClass('active');
        $("#"+tab_id).addClass('active');
    });

    $(function() {
        $( ".datepicker" ).datepicker();
    });

});
