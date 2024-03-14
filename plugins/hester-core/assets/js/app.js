// Active / Hover
window.addEventListener( 'load', () => {
	if ( document.body.classList.contains( 'is-active-focus' ) ) {
		let activeClassRows = document.querySelectorAll( '.active__focus-row' );
		activeClassRows.forEach( function( activeClassRow ) {
			var activeclass = activeClassRow.querySelectorAll( '.active__focus-item' );
			for ( var i = 0; i < activeclass.length; i++ ) {
				activeclass[i].addEventListener( 'mouseenter', activateClass );
			}
			function activateClass( event ) {
				for ( var i = 0; i < activeclass.length; i++ ) {
					activeclass[i].classList.remove( 'active' );
				}
				event.target.classList.add( 'active' );
			}
		});
	}
});

// .starter__swiper
let swiperCarousels = document.querySelectorAll( '.starter__swiper' );
for ( var i = 0; i < swiperCarousels.length; i++ ) {
	let swiperOptions = JSON.parse( swiperCarousels[i].dataset.swiperOptions );
	let carouselSwiperSlider = new Swiper( swiperCarousels[i], swiperOptions );
}

// Starter PopUp

if ( document.querySelector( '.hesterpop__galleryDesc' ) ||
	document.querySelector( '.gallery-poup .gallery-icon a' ) ||
	document.querySelector( '.hesterpop__video' ) ||
	document.querySelector( '.hesterpop__inlineIframe' ) ) {

	var lightbox = GLightbox();
	lightbox.on( 'open', ( target ) => {
		console.log( 'lightbox opened' );
	});

	var lightboxDescription = GLightbox({
		selector: '.hesterpop__galleryDesc, .gallery-poup .gallery-icon a'
	});

	var lightboxVideo = GLightbox({
		selector: '.hesterpop__video'
	});

	var lightboxInlineIframe = GLightbox({
		selector: '.hesterpop__inlineIframe'
	});

	lightboxVideo.on( 'slide_changed', ({ prev, current }) => {
		console.log( 'Prev slide', prev );
		console.log( 'Current slide', current );

		const { slideIndex, slideNode, slideConfig, player } = current;

		if ( player ) {
			if ( ! player.ready ) {

				// If player is not ready
				player.on( 'ready', ( event ) => {

					// Do something when video is ready
				});
			}

			player.on( 'play', ( event ) => {
				console.log( 'Started play' );
			});

			player.on( 'volumechange', ( event ) => {
				console.log( 'Volume change' );
			});

			player.on( 'ended', ( event ) => {
				console.log( 'Video ended' );
			});
		}
	});

}