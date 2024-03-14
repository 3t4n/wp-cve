$( document ).on( 'click', '.areoi-lightspeed-block img:not(.areoi-footer img, .areoi-header-container img, a img), .areoi-lightspeed-block video:not(.areoi-footer video, .areoi-header-container video, a video), .areoi-open-gallery', function(e) {
	e.preventDefault();

	var drag = $( this ).parents( '.areoi-drag-container' );
    
    if ( drag.length && drag.hasClass( 'moving' ) ) {
    	return false;
    }

	var gallery 	= $( '#areoi-gallery' ),
		carousel 	= $( '#areoi-gallery-carousel' ),
		container 	= $( this ).parents( '.areoi-lightspeed-block' ),
		medias 		= container.find( 'img, video' ),
		src 		= $( this ).attr( 'src' ),
		inner 		= '',
		media_count = medias.length;

	if ( !src ) {
		src = $( this ).find( 'source' ).attr( 'src' );
	}

	medias.each( function() {
		
		var item_src = $( this ).attr( 'src' );
		if ( !item_src ) {
			item_src = $( this ).find( 'source' ).attr( 'src' );
		}
		var item_index = $( this ).index( medias );
		
		inner += '<div class="carousel-item h-100 ' + ( src == item_src ? 'active' : '' ) + '">';
			inner += '<div class="h-100 d-flex align-items-center justify-content-center">';
				if ( $( this ).prop( 'nodeName' ) == 'VIDEO' ) {
					inner += '<video src="' + item_src + '" controls></video>';
				} else {
					inner += '<img src="' + item_src + '" />';
				}
			inner += '</div>';
		inner += '</div>';
	});

	carousel.find( '.carousel-inner' ).html( inner );

	if ( media_count > 1 ) {
		carousel.find( '.carousel-control-next, .carousel-control-prev' ).show();
	} else {
		carousel.find( '.carousel-control-next, .carousel-control-prev' ).hide();
	}

	gallery.modal( 'show' );
} );