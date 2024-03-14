jQuery( document ).ready(function( $ ) {
	'use strict';

    //Add the trigger for ClipboardJS
	var copyCode = new ClipboardJS('#dm-copy-raw-code', {
        text: function(trigger) {
	        // return the content of the <code>
            var clean = $(trigger).parent('.dm-buttons-right').parent('.dm-buttons').parent('.control-language').find('#dm-code-raw').text();
            var clean_trim = $.trim(clean);
            
            return clean_trim;
	    }
	});


    //Change the text on success copy
	copyCode.on('success', function(e) {
		var copyText = $(e.trigger).find('.dm-copy-text');
		var copyConfirmed = $(e.trigger).find('.dm-copy-confirmed');

		$(copyText).hide();
		$(copyConfirmed).show();
		e.clearSelection();

		 setTimeout(function() {
			 $(copyText).show();
			 $(copyConfirmed).hide();
		 }, 2500);
	 });

    //Change the text on error
	 copyCode.on('error', function(e) {
		 var copyText = $(e.trigger).find('.dm-copy-text');
		 var copyError = $(e.trigger).find('.dm-error-message');
		 e.clearSelection();

		 $(copyText).hide();
		 $(copyError).show();
		 setTimeout(function() {
			 $(copyText).show();
			 $(copyError).hide();
		 }, 2500);
	 });


     //Set snippet height
     setTimeout(function() {
        $('.dm-code-snippet').each(function() {
            var snippetHeight = $(this).attr('snippet-height');
            $(this).find('pre[class*="language-"]').css('max-height', snippetHeight);
        });
    }, 500);

});
