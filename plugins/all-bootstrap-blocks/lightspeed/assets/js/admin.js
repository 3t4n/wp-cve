jQuery(document).ready(function($){
    function change_background_size()
    {
    	$( '.areoi-lightspeed-block > .areoi-background' ).each( function() {
    		var parent = $( this ).parents( '.areoi-lightspeed-block' ),
    			new_height = parent.outerHeight();

    		$( this ).css( 'height', new_height + 'px' );
    	});
    }

    addEventListener('resize, load', (event) => {
    	change_background_size();
    });

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
            });
        });
    }

    if ( $( 'body' ).hasClass( 'wp-admin' ) ) {
        if ( typeof wp.data != 'undefined' ) {
            wp.data.subscribe( () => {
                setTimeout( () => {
                    resize_drag_containers();
                }, 2000 );
            });
        }
    }
});