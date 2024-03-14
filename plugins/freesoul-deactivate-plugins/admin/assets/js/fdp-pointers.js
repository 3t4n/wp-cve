jQuery( document ).ready(
	function($){
		fdp_open_pointer( 0,'#fdp-menu-post-types' );
		$( 'body' ).on(
			'click',
			'.fdp-pointer .button',
			function(){
				var button = $( this ),next_pointer_class = button.attr( 'data-next-pointer' );
				button.closest( '.wp-pointer' ).addClass( 'eos-hidden' );
				jQuery( '.fdp-indicated' ).removeClass( 'fdp-indicated' );
				jQuery( '.fdp-hover' ).removeClass( 'fdp-hover' );
				fdp_open_pointer( button.attr( 'data-n' ),button.attr( 'data-el_selector' ) );
			}
		);
		$( 'body' ).on(
			'click',
			'.fdp-pointer .fdp-pointer-close',
			function(){
				$( this ).closest( '.wp-pointer' ).addClass( 'eos-hidden' );
			}
		);
	}
);
function fdp_open_pointer(n,el_selector){
	var options = fdpPointer.pointers[n],setup;
	if ( ! options) {
		return;
	}
	var pointer_id = options.pointer_id;
	delete options.pointer_id;
	options = jQuery.extend(
		options,
		{
			close: function() {
				jQuery( '.fdp-hover' ).removeClass( 'fdp-hover' );
				jQuery( '.fdp-indicated' ).removeClass( 'fdp-indicated' );
				jQuery.post(
					ajaxurl,
					{
						pointer: pointer_id,
						action: 'dismiss-wp-pointer'
					}
				);
			}
		}
	);
	setup   = function() {
		var pointer_el = jQuery( el_selector ).first();
		pointer_el.pointer( options ).pointer( 'open' );
		var btn   = jQuery( '#' + pointer_id + '-button' ),
		click_el  = btn.attr( 'data-click' ),
		ofs       = btn.attr( 'data-offset' ),
		add_class = btn.attr( 'data-add_class' ),
		indicated = btn.attr( 'data-indicated' );
		btn.closest( '.wp-pointer' ).find( 'a.close' ).text( fdpWpPointer.dismiss_text );
		if ('function' === typeof(jQuery.fn.draggable)) {
			btn.closest( '.wp-pointer' ).draggable();
		}
		if (click_el && '' !== click_el) {
			jQuery( click_el ).first().trigger( 'click' );
		}
		if (ofs && '' !== ofs) {
			btn.closest( '.wp-pointer' ).css( 'transform','translateY(' + ofs + 'px)' )
		}
		if (indicated && '' !== indicated) {
			jQuery( indicated + ':visible' ).first().addClass( 'fdp-indicated' );
		}
		if (add_class && '' !== add_class) {
			jQuery( indicated ).first().addClass( 'fdp-hover' );
		}
	};
	if ( options.position && options.position.defer_loading ) {
		jQuery( window ).bind( 'load.wp-pointers', setup );
	} else {
		jQuery( document ).ready( setup );
	}
}
