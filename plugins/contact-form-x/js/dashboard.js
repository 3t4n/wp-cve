/* Contact Form X */

jQuery(document).ready(function($) {
	
	$('.cfx-subj a').click(function() { var id = $(this).data('id'); $('.cfx-id-'+ id).slideToggle(100); });
	
});