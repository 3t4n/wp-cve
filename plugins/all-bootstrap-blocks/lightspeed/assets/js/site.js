function change_content_height()
{
    if ( $( '.areoi-resize-container' ).length ) {
        $( '.areoi-resize-container' ).each( function() {
            var container = $( this ).parents( '.areoi-lightspeed-block' ),
                media = container.find( '.areoi-resize-media' ),
                content = container.find( '.areoi-resize-content' ),
                padding_top = parseInt( container.css( 'padding-top' ).replace( 'px', '' ) ),
                padding_bottom = parseInt( container.css( 'padding-bottom' ).replace( 'px', '' ) ),
                body_width = $( 'body' ).width(),
                percentage_width = ( media.width() / body_width ) * 100;
            
            media.css( 'height', '' );
            
            if ( media.height() < content.height() && percentage_width < 70 ) {
                media.css( 'height', ( content.height() + padding_top + padding_bottom ) + 'px' ); 
            }
        });
    }
}
$( window ).on( 'load', function() {    
    change_content_height();
});
$( window ).on( 'resize', function(){
    change_content_height();
});

function header_scroll()
{
    if ( $( '.areoi-lightspeed-header' ).length ) {
        
        var scroll = $(window).scrollTop();

        if ( scroll > 50 ) {
            $( '.areoi-lightspeed-header' ).addClass( 'scrolled' );
        } else {
            $( '.areoi-lightspeed-header' ).removeClass( 'scrolled' );
        }
    }
}

$( window ).on( 'scroll', function(){

    header_scroll();
});

header_scroll();

function delay( callback, ms ) 
{
    var timer = 0;
    return function() {
        var context = this, args = arguments;
        clearTimeout(timer);
        timer = setTimeout(function () {
            callback.apply(context, args);
        }, ms || 0);
    };
}

function resize_drag_containers()
{
    $( '.areoi-drag-container' ).each( function( i ) {
        var item_count = $( this ).find( 'ul:first-of-type > li' ).length;
        var item_width = $( this ).find( 'ul:first-of-type > li' ).css( 'flex-basis' );
        if ( item_width ) {
            item_width = item_width.replace( 'px', '' );
            var new_width = item_width * item_count;
            $( this ).find( 'ul:first-of-type' ).css( 'width', new_width + 'px' );
        }
    });
}

if ( $( '.areoi-drag-container' ).length ) {

    resize_drag_containers();

    let sliders = [];
    let next_url;

    $( document ).on( 'mousedown', '.areoi-drag-container a', function(e) {
        e.preventDefault();
        next_url = $( this ).attr( 'href' );
    } );
    $( document ).on( 'click', '.areoi-drag-container a', function(e) {
        e.preventDefault();
    } );

    $( '.areoi-drag-container' ).each( function( i ) {
        sliders[i]     = $( this );
        let is_down    = false;
        let start_x;
        let end_x;
        let scroll_left;            

        sliders[i].on('mousedown', (e) => {
            is_down = true;
            sliders[i].addClass('active');
            sliders[i].removeClass('moving');
            start_x = e.pageX - sliders[i].offset().left;
            scroll_left = sliders[i].scrollLeft();
            sliders[i].css( 'cursor', 'grabbing' );
        });
        sliders[i].on('mouseleave', () => {
            is_down = false;
            sliders[i].removeClass('active');
        });
        sliders[i].on('mouseup', (e) => {
            is_down = false;
            sliders[i].removeClass('active');
            sliders[i].css( 'cursor', 'grab' );
            end_x = e.pageX - sliders[i].offset().left;
            if ( start_x == end_x && next_url ) {
                window.location = next_url
            }
        });
        sliders[i].on('mousemove', (e) => {
            if(!is_down) return;
            e.preventDefault();
            const x = e.pageX - sliders[i].offset().left;
            const walk = (x - start_x) * 1;
            sliders[i].scrollLeft( scroll_left - walk );
            sliders[i].addClass('moving');
        });
    });
}