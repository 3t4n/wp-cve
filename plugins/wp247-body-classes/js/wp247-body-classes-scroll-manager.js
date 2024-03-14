/* WP247 Body Classes Scroll Manager */
jQuery(document).ready(function($) {

	$(window).scroll( function() {

		// Get current potition
		var top = $(window).scrollTop();
/*
$('body').removeClass(function(index,css){return (css.match(/\bis-scroll-debug-\S+/g) || []).join(' ');});
$('body').addClass('is-scroll-debug-'+parseInt(top)+'-px');
*/

		// Do general scrolling classes
		var gen = wp247_body_classes_scroll_options[ 'general_options' ];
		if ( 0 == top ) {
			if ( 'on' == gen[ 'scroll_top' ] ) $('body').addClass( 'is-scroll-top' );
			if ( 'on' == gen[ 'not_scroll_top' ] ) $('body').removeClass( 'is-not-scroll-top' );
			if ( 'on' == gen[ 'not_scroll' ] ) $('body').addClass( 'is-not-scroll' );
			if ( 'on' == gen[ 'scroll' ] ) $('body').removeClass( 'is-scroll' );
		}
		else if ( 0 == gen[ 'prev_offset' ] ) {
			if ( 'on' == gen[ 'scroll_top' ] ) $('body').removeClass( 'is-scroll-top' );
			if ( 'on' == gen[ 'not_scroll_top' ] ) $('body').addClass( 'is-not-scroll-top' );
			if ( 'on' == gen[ 'not_scroll' ] ) $('body').removeClass( 'is-not-scroll' );
			if ( 'on' == gen[ 'scroll' ] ) $('body').addClass( 'is-scroll' );
		}
		wp247_body_classes_scroll_options[ 'general_options' ][ 'prev_offset' ] = top;

		// Measure scroll by pixels
		wp247_body_classes_scroll_options[ 'pixel_options' ]
			= wp247_body_classes_scroll_manager( wp247_body_classes_scroll_options[ 'pixel_options' ], top, 0 );

		// Measure scroll by percent of viewport height
		wp247_body_classes_scroll_options[ 'view_options' ]
			= wp247_body_classes_scroll_manager( wp247_body_classes_scroll_options[ 'view_options' ], top, $(window).height() );

		// Measure scroll by percent of document height
		wp247_body_classes_scroll_options[ 'doc_options' ]
			= wp247_body_classes_scroll_manager( wp247_body_classes_scroll_options[ 'doc_options' ], top, $(document).height() );

		function wp247_body_classes_scroll_manager( opts, off, height ) {
		
			if ( 'on' != opts[ 'active' ] ) return opts;

			var type = opts[ 'type' ];
			var incr = opts[ 'increment' ];
			var start = opts[ 'start' ];
			if ( 0 == start ) start = incr;
			var limit = opts[ 'limit' ];
			if ( 0 == limit ) limit = 999999;
			var sfx = opts[ 'suffix' ];
			var do_top = opts[ 'do_top' ];
			var do_mid = opts[ 'do_mid' ];
			var do_n = opts[ 'do_n' ];
			var do_max = opts[ 'do_max' ];
			var poff = opts[ 'prev_offset' ];
			var pclass = opts[ 'prev_class' ];
			var noff = poff;
			var nclass = pclass;

			// make offset percent if needed
			if ( 'pct' == type ) off = off / height * 100;

			// Make offset a multiple of the increment
			off = off - ( off % incr );

			// If we're at the top
			if ( off < start )
			{
				if ( '' == pclass || 'is-scroll-top-' + sfx != pclass )
				{
					if ( 'on' == do_top ) nclass = 'is-scroll-top-' + sfx;
					else nclass = '';
					if ( 'on' == do_mid ) $('body').removeClass( 'is-scroll-mid-' + sfx );
					noff = 0;
				}
			}
			else if ( ( poff > off && poff - incr >= off )
					|| ( poff < off && poff + incr <= off ) )
			{
				if ( limit > 0 && off >= limit )
				{
					if ( 'on' == do_max ) nclass = 'is-scroll-max-' + sfx;
					else nclass = '';
					if ( 'on' == do_mid ) $('body').removeClass( 'is-scroll-mid-' + sfx );
					noff = limit;
				}
				else
				{
					if ( 'on' == do_n ) nclass = 'is-scroll-' + off + '-' + sfx;
					else nclass = '';
					if ( 'on' == do_mid ) $('body').addClass( 'is-scroll-mid-' + sfx );
					noff = off;
				}
			}

			if ( nclass != pclass ) {
				if ( '' != pclass) $('body').removeClass( pclass );
				if ( '' != nclass ) $('body').addClass( nclass );
			}
			
			opts[ 'prev_offset' ] = noff;
			opts[ 'prev_class' ] = nclass;

			return opts;
		}

	});
	
	$(window).scroll();

});