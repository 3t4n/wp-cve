( function( $ ) {

  jQuery( document).ready(function(){

    // Add Notification To the End of Page Body.
    $( 'body' ).append(
        '<div id="youzify-live-notifications" class="youzify-notif-icons-colorful youzify-notif-icons-radius"></div><div id="youzify-notifications-sound"></div>'
    );

    // Init Last Notification Vars.
    var last_notification = Youzify.last_notification;

    /**
     * Set Heartbeat Speed to Fast = 5 Seconds
     */
    wp.heartbeat.interval( Youzify.notifications_interval );

    /**
     * Proccess Received Notifications.
     */
    $( document ).on( 'heartbeat-tick.youzify-notification-data', function( event, data ) {

        if ( data.hasOwnProperty( 'youzify-notification-data' ) ) {

            // Get Notifications Data.
            var youzify_notification_data = data['youzify-notification-data'];

            // Update Last Notifications.
            last_notification = youzify_notification_data.last_notification;

            var notifications = youzify_notification_data.notifications;

            if ( notifications == undefined || notifications.length == 0 ) {
                return ;
            }

            $.each( notifications, function( index, notification ) {

                // Check if The Notification Is Already Added.
                if ( $( '#youzify-live-notifications #' + $(  notification ).attr( 'id' ) )[0] ) {
                    return;
                }

                // Play Notification Sound.
                $.Youzify_LN_PlaySound();

                // Append The New Notification.
                $( notification ).appendTo( '#youzify-live-notifications' ).delay( Youzify.timeout * 1000 ).queue( function() { $( this ).remove(); } );

            });

        }

    });

    /**
     * Send Last Notification ID.
     */
    $( document ).on( 'heartbeat-send', function( e, data ) {
        data['youzify-notification-data'] = { last_notification: last_notification };
    });

    /**
     * Delete Notification.
     */
    $( '#youzify-live-notifications' ).on( 'click', '.youzify-delete-notification', function( e ) {
        e.preventDefault();
        $( this ).parent().remove();
    });

    /**
     * Notification Sound Function.
     */
    $.Youzify_LN_PlaySound = function() {

        if ( $( '#youzify-notifications-sound audio' )[0] ) {
            $( '#youzify-notifications-sound audio' ).trigger( 'play' );
            return;
        }

        var mp3Source = '<source src="' + Youzify.sound_file + '.mp3" type="audio/mpeg">';
        var oggSource = '<source src="' + Youzify.sound_file + '.ogg" type="audio/ogg">';
        var embedSource = '<embed hidden="true" autostart="true" loop="false" src="' + Youzify.sound_file +'.mp3">';
        $( '#youzify-notifications-sound' ).append( '<audio autoplay="autoplay">' + mp3Source + oggSource + embedSource + '</audio>' ).trigger( 'play' );

    }

});

})( jQuery );