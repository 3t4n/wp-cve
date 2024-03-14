jQuery( document ).ready( function() {
	createSlider();
} );

document.addEventListener( 'gutenbergSlick', function( e ) {
	window.setTimeout( createSlider, 1500 );
}, false );

const createSlider = function() {
    jQuery(".social-proof-slider-wrap").not(".slick-initialized").slick({
        dots: false,
		arrows: false,
		prevArrow: '<button type="button" class="slick-prev"><span class="fa fa-angle-left"></span></button>',
		nextArrow: '<button type="button" class="slick-next"><span class="fa fa-angle-right"></span></button>',
        infinite: true,
        fade: true,
        autoplay: true,
        autoplaySpeed: 3000,
        slidesToShow: 1,
        adaptiveHeight: false,
        pauseOnHover: false,
    });
};
