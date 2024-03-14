let parallax_components = [
	'.accordion',
	'.alert',
	'.card',
	'.list-group',
	'.areoi-content-grid-item',
	'.nf-form-cont',
	'.carousel:not(.areoi-lightspeed-header .carousel)',
	'.areoi-content-item'
];

let parallax_exclude_elements_in_components = [
	'.accordion',
	'.alert',
	'.card',
	'.list-group'
];

let parallax_parents = [
	'.areoi-lightspeed-block'
];

let p_block_container = $( '.wp-site-blocks' );

let device_width = (window.innerWidth > 0) ? window.innerWidth : screen.width;

function add_parallax_classes( items, slug )
{
	items.forEach( (row) => {
		p_block_container.find( row ).each( function() {
			if ( !$( this ).parents( '.areoi-parallax-none' ).length ) {
				var position = $( this ).css( 'position' );
				$( this ).addClass( 'areoi-parallax-' + slug );

				if ( ![ 'fixed', 'relative', 'absolute', 'sticky' ].includes( position ) ) {
					$( this ).css( 'position', 'relative' );
				}
			}
		});
	});
}
add_parallax_classes( parallax_components, 'component' );

function change_parallax_components( elem ) 
{
	if ( !$(elem).parents( parallax_parents.join( ', ' ) ).length ) {
		return;
	}
	var scale 		= -0.35;
	var parent 		= $(elem).parents( parallax_parents.join( ', ' ) );
	var window_top 	= $(window).scrollTop(),
		parent_top 	= parent.offset().top,
		parent_height = parent.outerHeight(),
		elem_height = elem.height(),
		view_height = window.innerHeight * 0.5 - parent_height * 0.5,
		scrolled 	= window_top - parent_top + view_height + (elem_height/2),
		scaled 		= device_width >= 992 ? scrolled * scale : 0;
		
		elem.css({
			'transform': 'translate3d( 0, ' + scaled + 'px, 0 )'
		});
}

function change_parallax_background( elem ) 
{
	var scale 		= -0.15;
	var parent 		= $(elem).parent();
	var window_top 	= $(window).scrollTop(),
		parent_top 	= parent.offset().top,
		parent_height = parent.outerHeight(),
		elem_height = elem.height(),
		view_height = window.innerHeight * 0.5 - parent_height * 0.5,
		scrolled 	= window_top - parent_top + view_height,
		scaled 		= scrolled * scale;
		
		elem.css({
			'transform': 'translate3d( 0, ' + scaled + 'px, 0 )'
		});
}

function change_parallax_pattern( elem ) 
{
	if ( !$(elem).parents( parallax_parents.join( ', ' ) ).length ) {
		return;
	}
	var scale 		= 0.25;
	var parent 		= $(elem).parents( parallax_parents.join( ', ' ) );
	var window_top 	= $(window).scrollTop(),
		parent_top 	= parent.offset().top,
		parent_height = parent.outerHeight(),
		elem_height = elem.height(),
		view_height = window.innerHeight * 0.5 - parent_height * 0.5,
		scrolled 	= window_top - parent_top + view_height + (elem_height/2),
		scaled 		= scrolled * scale;
		
		elem.css({
			'transform': 'translate3d( 0, ' + scaled + 'px, 0 )'
		});
}

function check_parallax()
{
	device_width = (window.innerWidth > 0) ? window.innerWidth : screen.width;
	
	if ( $( 'body' ).hasClass( 'has-areoi-parallax-components' ) ) {
		$( '.areoi-parallax-component' ).each( function( key, item ) {
			var item = $( this );
			change_parallax_components( item );
		});
	}

	if ( $( 'body' ).hasClass( 'has-areoi-parallax-elements' ) ) {
		$( '.areoi-parallax-element' ).each( function( key, item ) {
			var item = $( this );
			change_parallax_elements( item );
		});
	}

	if ( $( 'body' ).hasClass( 'has-areoi-parallax-background' ) ) {
		p_block_container.find('.areoi-background:not(.areoi-parallax-none .areoi-background)' ).each(function(index, element) {
			var item = $( this );
			change_parallax_background( item );
		});
	}

	if ( $( 'body' ).hasClass( 'has-areoi-parallax-patterns' ) ) {
		p_block_container.find('.areoi-background-pattern:not(.areoi-background-pattern-media)' ).each(function(index, element) {
			var item = $( this );
			change_parallax_pattern( item );
		});
	}
}

check_parallax();


$( window ).on( 'scroll', function() {
	check_parallax();
});