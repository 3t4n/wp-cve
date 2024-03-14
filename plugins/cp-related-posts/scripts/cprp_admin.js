var codepeople_related_posts_admin = function(){
	var $ = jQuery;
	if('undefined' != typeof $.codepeople_related_posts_admin_flag) return;
	$.codepeople_related_posts_admin_flag = true;

	window[ 'cprp_get_tags' ] = function( id ){
		if( typeof cprp != 'undefined' ){
			var t = $( '[name="post_title"]' ),
				c = $( '[name="content"]' ),
				text = ( ( t.length ) ? t.val() + ' ' : '' ) + ( ( c.length ) ? c.val() : '' ) ;

			$.post(
				cprp[ 'admin_url' ]+'?cprp-action=extract-tags',
				{
					'text'          : text,
					'id'            : id
				},
				function( data ){
					var str = '';
					if( data[ 'recommended_tags' ] ){
						var recommended_tags = data[ 'recommended_tags' ];

						for( var tag in recommended_tags ){
							var checked = '';
							str += '<span style="border:1px solid #CCC;display:inline-block;padding:5px;margin:5px;"><input type="checkbox" name="cprp_tag[]" value="'+tag+'" '+checked+' value="'+tag+'" /> '+tag+' ('+recommended_tags[ tag ]+') </span>';
						}
					}

					$( '#cprp_tags' ).html( str );
				},
				'json'
			);
		}
	};

	window[ 'cprp_search_manually' ] = function( ){
		var terms = $.trim($( '#cprp_search' ).val());
		if( !/^\s*$/.test( terms ) ){
			$.getJSON(
				cprp[ 'admin_url' ],
				{
					'cprp-action'   : 'get-post',
					'terms'         : terms
				},
				function( data ){
					var str = '';
					for( var i = 0, h = data.length; i < h; i++ ){
						var t = data[ i ][ 'post_title' ];
						str += '<span style="cursor:pointer; border:1px solid #CCC;display:inline-block;padding:5px;margin:5px;" onclick="cprp_add_manually(this, '+data[ i ][ 'ID' ]+')" ><span class="cprp-hndl" >+</span><span class="cprp_found_title">'+(('thumbnail_url' in data[i]) ? '<img src="'+data[i]['thumbnail_url']+'" style="max-width:50px;max-height:50px;margin-right:5px;float:left;" />':'')+t.replace( /'/g, '\'')+'</span></span>';
					}

					$( '#cprp_found .cprp-container' ).html( str );
				}
			);
		}
	};

	window[ 'cprp_remove_manually' ] = function( e ){
		var e = $(e);
		e.parents( 'li' ).remove();
	};

	window[ 'cprp_add_manually' ] = function( e, id ){
		if( cprp_manually_added.find( '[value="'+id+'"]').length == 0 ){
			var title = $(e).find( '.cprp_found_title' ).html(),
				li = '<li><span class="cprp-hndl" onclick="cprp_remove_manually(this);">-</span><input type="hidden" name="cprp_manually[]" value="'+id+'" />'+title+'</li>';

			cprp_manually_added.append( li );
		}
		$(e).remove();
	};

	var cprp_manually_added = $( '#cprp_manually_added ul' );
	cprp_manually_added.sortable();
	cprp_manually_added.disableSelection();

	$( '#cprp_search' ).keypress( function( e ){
		var keycode = (e.keyCode ? e.keyCode : e.which);
		if( keycode == 13 ){
			$( e.target ).next( 'input' ).click();
			e.preventDefault();
			e.stopPropagation();
		}
	} );
};

jQuery(codepeople_related_posts_admin);
jQuery(window).on('load',codepeople_related_posts_admin);