/*  
 * Ape Gallery			
 * Author:            	Wp Gallery Ape 
 * Author URI:        	https://wpape.net/
 * License:           	GPL-2.0+
 */

(function ($) {
    $(document).ready(function () {
    	var twoJgalleryHelpObj = jQuery( "<div>", 
        		{
        			id: "twoj-gallery-help-dialog-window-id", 
        			"class": "twoj-gallery-help-dialog-window-class" 
        		}
        	);
        twoJgalleryHelpObj.appendTo('body');

		twoJgalleryHelpDialog = twoJgalleryHelpObj.dialog({
			'dialogClass' : 'wp-dialog',
			'title': '2J Gallery :: Help',
			'modal' : true,
			'autoOpen' : false,
			'width': '650', 
		    'maxWidth': 750,
		    'height': 'auto', /*'auto'*/
		    'fluid': true, 
		    'resizable': false,
			'responsive': true,
			'draggable': false,
			'closeOnEscape' : true,
			position: {
		      my: "center",
		      at: "top center",
		      of: window
    		},
			'buttons' : [/*{
					'text'  : 	'Close',
					'class' : 	'button button-link',
					'click' : 	function() { jQuery(this).dialog('close'); }
			},*/
			{
					'text' : 	wpape_fields_help_i18.close,
					'class' : 	'button-primary',
					'click' : 	function(){
						jQuery(this).dialog('close');
					}
			}
			],
			open: function( event, ui ) {
				/*var contentHelp = jQuery(this).data('content');
       			twoJgalleryHelpObj.html( getCode(contentHelp) );*/
			},
			beforeClose: function( event, ui ) {

				$('#popup-youtube-player')[0].contentWindow.postMessage('{"event":"command","func":"' + 'stopVideo' + '","args":""}', '*');  
			},

		    create: function () {
		      // style fix for WordPress admin
		      $('.ui-dialog-titlebar-close').addClass('ui-button');
		    },

		});

		window['twoJgalleryHelpObj'] = twoJgalleryHelpObj;
		window['twoJgalleryHelpDialog'] = twoJgalleryHelpDialog;

		jQuery(".twoj-gallery-help-button").click(function(event) {
			event.preventDefault();
			var id = jQuery(this).data('help');
			twoJgalleryHelpObj.html( getCode(  jQuery('#'+id).html() )  );
			//twoJgalleryHelpObj.data( 'content',  jQuery('#'+id).html() );
			twoJgalleryHelpDialog.dialog('option', 'title', wpape_fields_help_i18.title );
			twoJgalleryHelpDialog.dialog('open');		
		});

		jQuery(".twoj-gallery-help-label").click(function(event) {
			event.preventDefault();
			jQuery(this).next('.twoj-gallery-help-button').click();
		});
   	});

    var getCode = function( inHtml ){
    	const regex = /{youtube@@([a-zA-Z0-9\_\-]+)(\|([0-9]+)\*([0-9]+))?}/g;
    	var  value = {
			width: 560,
			height: 315,
			videoid: ''
		};
		var replaceTag = '';
		let m;
		while ((m = regex.exec(inHtml)) !== null) {
		    if (m.index === regex.lastIndex){ regex.lastIndex++;}
		    m.forEach((match, groupIndex) => {
		    	if(groupIndex==0 && match!=undefined)  replaceTag= match;
		    	if(groupIndex==1 && match!=undefined)  value.videoid = match;
		    	if(groupIndex==3 && match!=undefined)  value.width = match;
		    	if(groupIndex==4 && match!=undefined)  value.height = match;
		        //console.log(`Found match, group ${groupIndex}: ${match}`);
		    });
		}
		var youtubeCode = '<iframe id="popup-youtube-player" width="'+value.width+'" height="'+value.height+'" src="https://www.youtube.com/embed/'+value.videoid+'?enablejsapi=1&version=3&playerapiid=ytplayer" frameborder="0" gesture="media" allow="encrypted-media" allowscriptaccess="always" allowfullscreen></iframe>';
		return inHtml.replace(replaceTag, youtubeCode);

    }

})(jQuery);


/*let onClickHelpIcon = function( elem ){
	console.log(elem);
	alert('fff');
	var contenID = '#' + elem.getAttribute('data-help');
	twoJgalleryHelpObj.html( getCode(  contenID )  );
}*/