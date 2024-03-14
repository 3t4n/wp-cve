/**
 * Word Typed
 * Init Word Typed
 * by Luiz Ricardo (https://github.com/luizrw)
 *
 * This plugin this licensed as GPL.
 */
jQuery(document).ready(function($) {
    $('.lrw-word-typed').each( function() {
        var $$ = $(this),
            element = $$.find( '#lrw-typed' ),
            wrapper = $$.find( '.lrw-word-typed-wrapper' ),
            trigger = ( wrapper.data( 'trigger' ) ? !0 : !1 ),
            str = element.data( 'strings' ),
            strings = (str, str.split(",")),
            parameters = {
                strings: strings,
                typeSpeed: wrapper.data('typespeed'),
                startDelay: wrapper.data('startdelay'),
                backSpeed: wrapper.data('backspeed'),
                backDelay: ( wrapper.data('backdelay') ? wrapper.data('backdelay') : 500 ),
                loop: wrapper.data('loop'),
                showCursor: wrapper.data('showcursor'),
                cursorChar: ( wrapper.data('cursorchar') ? wrapper.data('cursorchar') : '|'),
                contentType: 'html'
            };

        if ( 0 == trigger ) {
            element.typed( parameters );

        } else {
            $(this).waypoint(function(direction) {
                element.typed( parameters );
            }, {
              offset: 'bottom-in-view',
              triggerOnce: !0
            })
        }

        setInterval(function(){
            var c = $$.find('.typed-cursor');
            if( c.css('opacity') == 0 ) c.css('opacity', 1);
            else c.css('opacity', 0);
        }, wrapper.data('cursortime') * 10 );
    });
});