window.addEventListener("message", receiveMessage, false); 
function receiveMessage(event){ 
    if(typeof event.data == 'string' && event.data.substring(0,12) == 'iframe_click'){ 
        jQuery('.hf-orderform').height(1* event.data.substring(13));
    }
    else if(typeof event.data == 'string' && event.data == 'iframe_reload')
	{
		var scrollTo = Math.max(
			jQuery('html').scrollTop(),
			jQuery('body').scrollTop()
		);

		if(scrollTo > jQuery('.hf-orderform').offset().top)
		{
			jQuery('html, body').animate({scrollTop: jQuery('.hf-orderform').offset().top}, 100);
		}
	}
}