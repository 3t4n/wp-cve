jQuery(document).ready(function( $ ) {
	$('.wp_hide_email').each(function(index) {
		var obscured_email=$(this).attr('class');
		var myArray=obscured_email.split(' ');
		$(this).html('<a href=\'mailto:'+unescape(myArray[1])+'\'>'+unescape(myArray[1])+'</a>');
	});
});
