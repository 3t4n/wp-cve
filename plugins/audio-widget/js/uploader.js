/*!
 * Audio Widget jQuery Uploader 0.1
 * http://sinayazdi.com/audio-widget
 * Licensed under the GPL2
 */

jQuery( function( $ ) {
    var id, idP;
    var idP;
    var frame = wp.media({
        multiple: false,
            library: {
            type: 'audio'
        }
    });
    var frameP = wp.media({
        multiple: false,
        library: {
            type: 'image'
        }
    });

    $(document).on('click', '.smy_audio_widget_add_audio', function (e) {
        frame.open();
        id = $(this).attr('id').replace('_add_button', '');
        e.preventDefault();
    });
    $(document).on('click', '.smy_audio_widget_add_poster', function (e) {
        frameP.open();
        idP= $(this).attr('id').replace('_add_button', '');
        id = idP.replace('-poster', '-src');
        e.preventDefault();
    });


    frame.on('select', function() {
        attachment = frame.state().get('selection').first().toJSON();

        var idp = id.replace('-src', '-poster');
        var idi = id.replace('-src', '');

        if ( $('#'+id+'_type').has('audio').length ) {
            $('#'+id+'_type audio').attr("src", attachment.url);
        } else{
            $('#'+id+'_type').prepend('<audio src="'+attachment.url+'" class="widefat" controls></audio>');
        };


        $('#'+idi+'-controls').prop('checked', true);
        $('#'+id+'_url').val(attachment.url);
        frame.close();
    });

    frameP.on('select', function() {
        attachment = frameP.state().get('selection').first().toJSON();

        if ( $('#'+id+'_type').has('img').length ) {
            $('#'+id+'_type img').attr("src", attachment.url);
        } else{
            $('#'+id+'_type').prepend('<img src="'+attachment.url+'" class="smy_audio_widget_image" alt="" />');
        };

        $('#'+idP+'_poster').val(attachment.url);
        frameP.close();
    });

} );