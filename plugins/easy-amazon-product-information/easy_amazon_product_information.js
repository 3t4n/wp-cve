jQuery('.nav-tab').on('click', function(){
		jQuery('.nav-tab').removeClass('nav-tab-active');
		
		jQuery(this).addClass('nav-tab-active');
		
		jQuery('.nav-text').removeClass('nav-text-visible');
		var id = jQuery(this).attr('data-tab');
		jQuery('.nav-text[data-id='+ id +']').addClass('nav-text-visible');
	});
jQuery(document).ready(function(){
		jQuery('.my-color-field').wpColorPicker();
});
