document.addEventListener( 'wpcf7submit', function( event ) {
    console.log('Contact Form 7 stop spinner');
    jQuery('head').append('<style type="text/css">.aw-no-spinner:before{display:none!important}</style>');
    jQuery('body').find('.processing').addClass('aw-no-spinner');
}, false );
jQuery('.wpcf7-submit').on('click', function( event ) {
    jQuery('body').find('.processing').removeClass('aw-no-spinner');
});