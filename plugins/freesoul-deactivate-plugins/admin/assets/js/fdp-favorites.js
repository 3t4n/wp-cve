jQuery( document ).ready(
	function($){
		var plugins_slugs = [],plugins_titles = [],plugins_icons = [];
		$( '#wpbody-content' ).on(
			'click',
			'.plugin-card',
			function(){
				var selected_plugins = parent.jQuery( '#fdp-selected-plugins' ),html_list = '',html = '',card = $( this ),slug = '',card_classes = this.classList,n = 0;
				for (n;n < card_classes.length;++n) {
					if (card_classes[n].indexOf( 'plugin-card-' ) > -1) {
						slug = card_classes[n].replace( 'plugin-card-','' );
					}
				}
				var idx = plugins_slugs.indexOf( slug );
				if ('' !== slug) {
					if (idx > -1) {
						card.removeClass( 'fdp-added-to-favorites' );
						plugins_slugs.splice( idx,1 );
						plugins_titles.splice( idx,1 );
						plugins_icons.splice( idx,1 );
					} else {
						card.addClass( 'fdp-added-to-favorites' );
						plugins_slugs.push( slug );
						plugins_titles.push( card.find( 'h3' ).text().replace( /(\r\n\t|\n|\r\t)/gm,"" ).replace( /\s+/g," " ).trim() );
						plugins_icons.push( card.find( 'img' ) );
					}
					$( '#fdp_favorites_list' ).val( plugins_slugs.join( ';' ) );
					parent.jQuery( '#fdp_favorites_list_parent' ).val( plugins_slugs.join( ';' ) );
				}
				$.each(
					plugins_slugs,
					function(idx,slug){
						var title  = plugins_titles[idx],icon = plugins_icons[idx];
						html      += '<span id="fdp-' + slug + '" style="display:inline-block;background:#F1F1F1;padding:12px;margin:2px"><span>' + title + '</span><img style="margin:-5px 5px;width:20px;margin:0 6px" src="' + icon.attr( 'src' ) + '" /></span>';
						html_list += '<p><input type="checkbox" class="fdp-list-item" value="' + slug + '" checked=""><span>' + title + '</span></p>';
					}
				);
				selected_plugins.html( html );
				parent.document.getElementById( 'fdp-plugins-list-added' ).innerHTML = html_list;
			}
		);
	}
);
