jQuery( document ).ready(
	function ($) {
		$( '.fed_template1' ).on(
			'click', '.fed_collapse_menu', function (e) {
				var click  = $( this );
				var parent = click.closest( '.fed_dashboard_wrapper' );
				parent.find( '.fed_dashboard_menus' ).toggleClass( 'fed_collapse' );
				parent.find( '.fed_dashboard_menus' ).toggleClass( 'col-md-2' ).toggleClass( 'col-md-1' );
				parent.find( '.fed_dashboard_items' ).toggleClass( 'col-md-10' ).toggleClass( 'col-md-11' );
				parent.find( '.flex' ).toggleClass( 'flex_center' );
				e.preventDefault();
			}
		);
	}
);
