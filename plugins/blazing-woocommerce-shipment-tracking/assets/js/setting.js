jQuery(function () {

    function set_track_message_demo(){
        jQuery('#track_message_demo_1').html(
            jQuery('#track_message_1').val() + 'FedEx' +
                '<br/>'+
            jQuery('#track_message_2').val() + '1Zxxxxxxxxxx098'
        );
    }

    jQuery('#plugin').change(function () {
        jQuery('#couriers').parent().parent().show();
        jQuery('#track_message_demo_1').parent().parent().show();
    });


    if (jQuery('#track_message_demo_1')) {
        set_track_message_demo();

    }

    jQuery('#track_message_1').keyup(function () {
        set_track_message_demo();
    });

    jQuery('#track_message_2').keyup(function () {
        set_track_message_demo();
    });
});