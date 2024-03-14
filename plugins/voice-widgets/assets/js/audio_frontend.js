jQuery( document ).ready( function( $ ) {
    // do code here.
    jQuery( document ).on( 'click', '.qc_play_audio', function( e ) {
        e.preventDefault();
        var obj = $(this);
        obj.removeClass( 'qc_play_audio' ).addClass( 'qc_stop_audio' );
        obj.find( 'i' ).toggleClass("fa-play-circle fa-pause-circle");
        var audio_elem = obj.parent().parent().find( '.qc_audio_audio audio' )[0];
        audio_elem.play();
    
        $( audio_elem ).on( 'ended', function(e) {
            $(audio_elem).unbind('ended');
            obj.removeClass( 'qc_stop_audio' ).addClass( 'qc_play_audio' );
            obj.find( 'i' ).toggleClass("fa-play-circle fa-pause-circle");
            wave_block( obj, 'close' );
        } )

        // for wave animation
        wave_block( obj, 'repeat' );

    } )
    jQuery( document ).on( 'click', '.qc_stop_audio', function( e ) {
        e.preventDefault();
        var obj = $(this);
        obj.removeClass( 'qc_stop_audio' ).addClass( 'qc_play_audio' );
        obj.find( 'i' ).toggleClass("fa-play-circle fa-pause-circle");
        var audio_elem = obj.parent().parent().find( '.qc_audio_audio audio' )[0];
        audio_elem.pause();

        // for wave animation
        wave_block( obj, 'close' );
    } )

    function wave_block(obj, handle) {
        // for wave animation
        if ( obj.parent().parent().find( '.wave-block' ).length > 0 ) {
            obj.parent().parent().find( '.wave-block' ).attr( 'qc-data-animate', handle );
        }
    }
    
} )