( function( $ ) {

	var wp_tv_path;
	var code_container = $( '<div style="display:none"></div>' );

	// function to scroll to element
	function wp_tv_scroll_to( el ) {
		if ( el.length ) {
			var targetOffset = el.offset().top;
			if ( $( '#wpadminbar' ).length ) {
				targetOffset = targetOffset - 30;
			}

			$( 'html,body' ).animate( {
				scrollTop: targetOffset
			} );
		}
	}

	// function to select content
	function wp_tv_select_text( element ) {
		var text = document.getElementById( element ),
			range, selection;
		if ( document.body.createTextRange ) { //ms
			range = document.body.createTextRange();
			range.moveToElementText( text );
			range.select();
		} else if ( window.getSelection ) { //all others
			range = document.createRange();
			range.selectNodeContents( text );
			window.getSelection().removeAllRanges();
			window.getSelection().addRange( range );
		}
	}


	$( document ).ready( function() {

		// Change .wp_tv_no_js to .wp_tv_js (unhides links when there is Javascript)
		$( '.wp_tv_no_js' ).toggleClass( 'wp_tv_no_js wp_tv_js' );

		// Make links.
		$( ".wp_tv_path" ).removeClass( "ab-item ab-empty-item" ).wrap( '<a class="ab-item wp_tv_file" href=""></a>' );
		$( ".wp_tv_toggle" ).contents().unwrap().wrap( '<a class="wp_tv_toggle" href=""></a>' );
		$( ".wp_tv_toggle" ).parent().removeClass( "ab-empty-item" );
		$( ".wp_tv_close" ).contents().unwrap().wrap( '<a class="wp_tv_close" href=""></a>' );
		$( ".wp_tv_close" ).attr( 'alt', wp_tv_ajax.wp_tv_close );
		$( ".wp_tv_close" ).attr( 'title', wp_tv_ajax.wp_tv_close );

		var file_list = $( '.wp_tv_files' );
		var toggle_admin_bar = $( "#wpadminbar" ).find( ".wp_tv_toggle" );
		var toggle_footer = file_list.find( ".wp_tv_toggle" );
		var ul = $( '#wp_tv_file_list_footer' );


		// Append empty code container.
		$( '#wp_tv_template_viewer' ).append( code_container );

		// close link
		$( ".wp_tv_close" ).on( "click", function( event ) {
			event.preventDefault();
			ul.hide();
			file_list.hide();
			toggle_admin_bar.text( wp_tv_ajax.wp_tv_show_in_footer );
			toggle_footer.text( wp_tv_ajax.wp_tv_show );
			code_container.hide();
			code_container.empty();
		} );

		// toggle links
		$( ".wp_tv_toggle" ).on( "click", function( event ) {
			event.preventDefault();

			if ( ul.is( ":visible" ) ) {
				toggle_admin_bar.text( wp_tv_ajax.wp_tv_show_in_footer );
				toggle_footer.text( wp_tv_ajax.wp_tv_show );
				ul.slideUp( "fast" );

			} else {
				toggle_admin_bar.text( wp_tv_ajax.wp_tv_hide_in_footer );
				toggle_footer.text( wp_tv_ajax.wp_tv_hide );
				file_list.show();
				ul.show();
			}

			wp_tv_scroll_to( file_list );
		} );

		// select content link
		$( '#wp_tv_template_viewer' ).on( 'click', '.wp_tv_select', function( event ) {
			event.preventDefault();
			wp_tv_scroll_to( $( "#wp_tv_code_title" ) );
			wp_tv_select_text( 'wp_tv_content' );
		} );


		// file links
		$( ".wp_tv_file" ).on( "click", function( event ) {
			event.preventDefault();

			// show title (and files) (todo: create close button)
			file_list.show();

			// Hide all the things.
			code_container.hide();
			code_container.empty();

			wp_tv_path = $( this ).children( 'span' ).data( 'wp_tv_path' );

			$.post( wp_tv_ajax.wp_tv_ajaxurl, {
					action: 'wp_tv_display_template_file',
					wp_tv_nonce: wp_tv_ajax.wp_tv_nonce,
					wp_tv_file: wp_tv_path
				},
				function( response ) {
					code_container.show();

					if ( response.success === true ) {
						code_container.html( response.file_content );
					} else {
						if ( response.file_content.length ) {
							code_container.html( response.file_content );
						}
					}

					wp_tv_scroll_to( $( '#wp_tv_code_title' ) );
				}, "json" );

		} );
	} );

} )( jQuery );