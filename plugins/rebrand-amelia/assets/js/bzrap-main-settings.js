jQuery(document).ready(function() {
	
	jQuery('.ame-wl-setting-tabs').on('click', '.ame-wl-tab', function(e) {
		e.preventDefault();
		var id = jQuery(this).attr('href');
		jQuery(this).siblings().removeClass('active');
		jQuery(this).addClass('active');
		jQuery('.ame-wl-setting-tabs-content .ame-wl-setting-tab-content').removeClass('active');
		jQuery('.ame-wl-setting-tabs-content').find(id).addClass('active');
	});


});
 
