function captionpix_news_ajax(url) {
	var data = { action: captionpix_news.ajaxaction, security: captionpix_news.ajaxnonce, url: url };     
	jQuery.post( captionpix_news.ajaxurl, data, function( response ) {
   	var ele = jQuery(captionpix_news.ajaxresults);
      if( response.success ) 
      	ele.append( response.data );
/*      else if ( response.data.error )
      	ele.append( response.data.error );
*/
   });
}    

jQuery(document).ready(function($) {
	if (typeof captionpix_news0 != 'undefined') captionpix_news_ajax(captionpix_news0.feedurl );
	if (typeof captionpix_news1 != 'undefined') captionpix_news_ajax(captionpix_news1.feedurl );   
	if (typeof captionpix_news2 != 'undefined') captionpix_news_ajax(captionpix_news2.feedurl );
});