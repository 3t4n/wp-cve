let elements = [
	'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'li', 'img:not(.areoi-background img, .carousel img)', 'video:not(.areoi-background video, .carousel video)',
	'.btn', '.btn-simple', '.alert', '.accordion-item', '.card', '.list-group-item', '.nav-link',
	'.areoi-content-item',
	'.nf-form-cont', '.carousel',
	'.areoi-heading-divider', '.areoi-heading-icon', '.areoi-lightspeed-item-icon'
];

let block_container = $( 'main' );

function add_transition_classes()
{
	elements.forEach( (row) => {
		block_container.find( row ).each( function() {
			if ( !$( this ).parents( '.areoi-transition-none' ).length ) {
				var position = $( this ).css( 'position' );
				var parent_parralax = $( this ).parents( '.areoi-parallax-component' );
				if ( !$( this ).hasClass( 'areoi-parallax-component' ) && !parent_parralax.length ) {
					$( this ).addClass( 'areoi-transition' );
				}

				if ( ![ 'fixed', 'relative', 'absolute', 'sticky' ].includes( position ) ) {
					$( this ).css( 'position', 'relative' );
					$( this ).addClass( 'areoi-transition-move' );
				}
			}
		});
	});

	check_transitions();
}
add_transition_classes();

function is_scrolled_into_view( elem )
{
	var docViewTop 		= $( window ).scrollTop();
	var docViewBottom 	= docViewTop + $( window ).height();
	var elemTop 		= $( elem ).offset().top;
	var elemBottom 		= elemTop + $( elem ).height();
	var elemHeight 		= $( elem ).height();
	var elemType 		= $( elem ).prop( 'nodeName' );

	var sensitivity = 1;

	var sensitivity_factor = $( window ).height() * sensitivity;

	var bounding = elem[0].getBoundingClientRect();
	
	return bounding.top <= $( window ).height() - (elemHeight * 0.2);
}

function check_transitions()
{
	let items = [];

	$( '.areoi-transition' ).each( function( key, item ) {
		var item = $( this );
		if ( is_scrolled_into_view( item ) ) {
			if ( !item.hasClass( 'areoi-transition-loading' ) ) {
				items.push( item );
				item.addClass( 'areoi-transition-loading' );
			}
		}
	});
	animate_in( 0, items )
}

function animate_in( index, items )
{
	if ( items.length && items[index] ) {
		let item = items[index];

		item.addClass( 'areoi-transition-visible' ).removeClass( 'areoi-transition-loading' );

		setTimeout( function() {
			animate_in( ( index + 1 ), items )
		}, 50);
	}
}

$( window ).on( 'scroll', function() {
	check_transitions();
});
check_transitions();