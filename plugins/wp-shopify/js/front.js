// JavaScript Document
jQuery(document).ready(function($){
	
	$("body").on("keyup", '#wpsy-filter-bar', function() {
		var value = $(this).val().toLowerCase();
		$("ul.wp_shopify li").filter(function() {
			$(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
		});
	});	
	
});