jQuery(document).ready( function($) {

		$('.definitive-admin-tab-contents').show();
		$('.definitive-admin-tab-nav li:first').addClass('tab-active');

	// Change tab class and display content
	$('.definitive-admin-tab-nav a').on('click', function(event){
	event.preventDefault();
	
	$('.definitive-admin-tab-nav li').removeClass('tab-active');
	$(this).parent().addClass('tab-active');
	$('.definitive-admin-tab-contents .dafe-admin-tab-bar').hide();
	$($(this).attr('href')).show();
	});
	
});