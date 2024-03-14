( function( $ ) {
	function fp_tab_show( widget, tab = '' ) {
		var target;
		if ( '' === tab ) {
			tab = $( '.fp-tabs > li:first-child .fp-tab-item', widget );
		}
		target = tab.data( 'target' );

		$( '.fp-tab-item', widget ).removeClass( 'active' );
		$( '.fp-tab', widget ).removeClass( 'active' );

		tab.addClass( 'active' );
		$( '.' + target, widget ).addClass( 'active' )
	}

	function fp_image_sizes_show( widget ) {
		var layout         = $( '.fp-layout', widget ).val();
		var show_image     = $( '.fp-show-image', widget ).val();
		var fp_image_size  = $( '.fp-image-size', widget );
		var fp_image_size2 = $( '.fp-image-size2', widget );
		if ( show_image !== 'none' && ( parseInt( layout ) === 1 || parseInt( layout ) === 3 ) ) {
			fp_image_size.closest( 'p' ).show();
		} else {
			fp_image_size.closest( 'p' ).hide();
		}
		if ( show_image !== 'none' && parseInt( layout ) !== 1 ) {
			fp_image_size2.closest( 'p' ).show();
		} else {
			fp_image_size2.closest( 'p' ).hide();
		}
	}

	function fp_category_tag_show( widget ) {
		var post_type           = $( '.fp-query-post-type', widget ).val();
		var has_category_option = false;
		var has_post_tag_option = false;
		var fp_query_category   = $( '.fp-query-category', widget );
		var fp_query_tag        = $( '.fp-query-tag', widget );
		if ( typeof flex_posts_admin.taxonomies[ post_type ] !== 'undefined' ) {
			has_category_option = flex_posts_admin.taxonomies[ post_type ].indexOf( 'category' ) !== -1;
			has_post_tag_option = flex_posts_admin.taxonomies[ post_type ].indexOf( 'post_tag' ) !== -1;
		}
		if ( has_category_option ) {
			fp_query_category.closest( 'p' ).show();
		} else {
			fp_query_category.closest( 'p' ).hide();
		}
		if ( has_post_tag_option ) {
			fp_query_tag.closest( 'p' ).show();
		} else {
			fp_query_tag.closest( 'p' ).hide();
		}
	}

	function fp_excerpt_show( widget ) {
		var show_excerpt = $( '.fp-show-excerpt', widget );
		var excerpt_length = $( '.fp-excerpt-length', widget );
		if ( show_excerpt.is( ':checked' ) ) {
			excerpt_length.closest( 'p' ).show();
		} else {
			excerpt_length.closest( 'p' ).hide();
		}
	}

	function fp_readmore_show( widget ) {
		var show_readmore = $( '.fp-show-readmore', widget );
		var readmore_text = $( '.fp-readmore-text', widget );
		if ( show_readmore.is( ':checked' ) ) {
			readmore_text.closest( 'p' ).show();
		} else {
			readmore_text.closest( 'p' ).hide();
		}
	}

	function fp_widget_start( widget ) {
		fp_tab_show( widget );
		fp_image_sizes_show( widget );
		fp_category_tag_show( widget );
		fp_excerpt_show( widget );
		fp_readmore_show( widget );

		$( '.fp-tab-item', widget ).on( 'click', function( e ) {
			e.preventDefault();
			var tab = $( this );
			fp_tab_show( widget, tab );
		} );

		$( '.fp-layout, .fp-show-image', widget ).on( 'change', function() {
			fp_image_sizes_show( widget );
		} );

		$( '.fp-query-post-type', widget ).on( 'change', function() {
			fp_category_tag_show( widget );
		} );

		$( '.fp-show-excerpt', widget ).on( 'click', function() {
			fp_excerpt_show( widget );
		} );

		$( '.fp-show-readmore', widget ).on( 'click', function() {
			fp_readmore_show( widget );
		} );
	}

	$( function() {
		$( '#widgets-right .widget[id*="flex-posts"]' ).each( function() {
			var widget = $( this );
			fp_widget_start( widget );
		} );
	} );

	$( document ).on( 'widget-added widget-updated', function( event, widget ) {
		var id_base = widget.find( '.id_base' ).val();
		if ( id_base.indexOf( 'flex-posts' ) === -1 ) {
			return;
		}
		fp_widget_start( widget );
	} );

} )( jQuery );
