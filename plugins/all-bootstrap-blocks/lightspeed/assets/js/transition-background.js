let text_elems = [];

function is_background_in_view( elem )
{
    var docViewTop      = $( window ).scrollTop();
    var docViewBottom   = docViewTop + $( window ).height();
    var elemTop         = $( elem ).offset().top;
    var elemBottom      = elemTop + $( elem ).height();
    var elemHeight      = $( elem ).height();
    var elemType        = $( elem ).prop( 'nodeName' );

    var bounding = elem[0].getBoundingClientRect();
    
    return bounding.top <= $( window ).height() - ($( window ).height() * 0.5);
}

let bg_checked = false;

function add_data_attribute()
{
    let items = [];

    $( '.areoi-background' ).each( function( key, item ) {
        var container = $( this ).parents( '.areoi-lightspeed-block' );
        var texts = container.find( '[class^="text-"],[class*=" text-"],[class^="btn-"],[class*=" btn-"]' );

        var text_color = null;
        var allowed_classes = [ 
            'text-primary', 
            'text-secondary', 
            'text-success', 
            'text-warning', 
            'text-danger', 
            'text-info', 
            'text-light', 
            'text-dark', 
            'text-body',
            'btn-primary', 
            'btn-secondary', 
            'btn-success', 
            'btn-warning', 
            'btn-danger', 
            'btn-info', 
            'btn-light', 
            'btn-dark', 
            'btn-body',
        ];
        texts.each( function() {
            var is_item_with_background = $( this ).parents( '.areoi-item-has-background' ).length;

            if ( !is_item_with_background ) {
                var elem = $( this );
                var class_list = $( this ).attr( 'class' );
                var class_arr = class_list.split(/\s+/);
                $.each( class_arr, function( index, value ) {
                    if ( allowed_classes.includes( value ) ) {

                        var color = value.replace( 'text-', '' );
                        color = color.replace( 'btn-', '' );

                        text_elems.push( { class: value, elem: elem } );
                        if ( !text_color ) {
                            text_color = color;
                        }
                    }
                });
            }
        });

        var item = $( this );

        var background_color = item.css('background-color');
        if ( item.find( '.areoi-background__color' ).length ) {
            background_color = item.find( '.areoi-background__color' ).css('background-color');
        }

        item.data( 'background', background_color );
        item.data( 'text', text_color );
    });

    bg_checked = true;
}
add_data_attribute();

function check_backgrounds()
{
    let items = [];

    $( '.areoi-lightspeed-block .areoi-background' ).each( function( key, item ) {
        
        var item = $( this );
        if ( is_background_in_view( item ) ) {

            var background_color = item.data( 'background' );
            var text_color = item.data( 'text' );

            if ( !text_color ) {
                if ( $( this ).hasClass( 'bg-body' ) || $( this ).hasClass( 'bg-light' ) ) {
                    text_color = 'dark';
                } else {
                    text_color = 'light';
                }
            }

            if ( text_elems.length ) {
                $.each( text_elems, function( index, elem ) {
                    
                    var new_class = '';
                    if ( elem.class.includes( 'btn-' ) ) {
                        new_class = 'btn-' + text_color;
                    } else {
                        new_class = 'text-' + text_color;
                    }

                    elem.elem.removeClass( elem.class ).addClass( new_class );
                    
                    text_elems[index].class = new_class;
                });
            }

            $( '.areoi-lightspeed-block .areoi-background, .areoi-background__color' ).attr('style', 'background-color: ' + background_color + ' !important;' );
        }
    });
}

$( window ).on( 'scroll', function() {
    if ( bg_checked ) {
        check_backgrounds();
    }
});
check_backgrounds();