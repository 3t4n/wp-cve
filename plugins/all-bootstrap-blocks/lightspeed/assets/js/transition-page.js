const is_external_link = (url) => {
    const tmp = document.createElement('a');
    tmp.href = url;
    return tmp.host !== window.location.host;
};

const is_new_page = (url) => {
    var next_url = new URL( url, document.baseURI );
    var next_url_no_hash = next_url.href.replace( next_url.hash, '' );

    var current_url = new URL( window.location, document.baseURI );
    var current_url_no_hash = current_url.href.replace( current_url.hash, '' );

    if ( next_url_no_hash != current_url_no_hash ) {
        return true;
    }
    return false;
};

const open_url = (url) => {

    $( 'body' ).addClass( 'areoi-page-transition-unloaded' );
    $( '.areoi-transition' ).addClass( 'areoi-transition-invisible' );

    setTimeout( function() {
        window.location = url;
    }, 500);
};

$( document ).on( 'click', 'a:not(.areoi-feature-menu a)', function(e) {
    e.preventDefault();

    if ( e.ctrlKey ) {
        return;
    }
    switch ( e.which ) {
        case 1:
            break;
        default:
            return false;
    }
    var url = $( this ).attr( 'href' );
    var drag = $( this ).parents( '.areoi-drag-container' );
    
    if ( url.indexOf('#') < 0 && ( !drag.length || !drag.hasClass( 'moving' ) ) ) {
        
        if ( typeof url !== 'undefined' && !is_external_link( url ) && $( this ).attr( 'target' ) !== '_blank' && is_new_page( url ) ) {
            open_url( url );
        } else if ( $( this ).attr( 'target' ) === '_blank' ) {
            window.open( url, '_blank' ).focus();
        } else if ( is_external_link( url ) ) {
            window.location = url;
        }
    }
} );

function add_loaded_classes()
{
    $( 'body' ).addClass( 'areoi-page-transition-loaded' ).removeClass( 'areoi-page-transition-unloaded' );
    $( '.areoi-transition' ).removeClass( 'areoi-transition-invisible' );
}

$( window ).on( 'pageshow', function() {
    add_loaded_classes();
});

add_loaded_classes();