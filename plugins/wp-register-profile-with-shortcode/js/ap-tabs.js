// version 1.1 //

(function ( $ ) {
	$.fn.aptabs = function( options ) {
		var tabs = $(this);
		var tabs_count = $(this).children().length;
		tabs_count = tabs_count - 1;
		$(this).children().removeClass('ap-active');
		$('.'+options.content_container+' > div').hide();
		if( options.active <= tabs_count ){
			$('.'+options.content_container+' > div').eq(options.active).show();
			$(this).children().eq(options.active).addClass('ap-active');
		} else{
			$('.'+options.content_container+' > div').eq(0).show();
			$(this).children().eq(0).addClass('ap-active');
		}
		$(this).children().click(function(){
			tabs.children().removeClass('ap-active');
			$('.'+options.content_container+' > div').hide();
			$('.'+options.content_container+' > div').eq($(this).index()).show();
			tabs.children().eq($(this).index()).addClass('ap-active');
			apSetCookie('ap_current_tab', $(this).index(),1);
		});
	};
}( jQuery ));
jQuery(document).ready(function() {
	var active_tab;
	if (apGetCookie('ap_current_tab') == ''){
		active_tab = 0;
	} else {
		active_tab = apGetCookie('ap_current_tab');
	}
	jQuery( ".ap-tabs" ).aptabs( { active : active_tab, content_container : 'ap-tabs-content'} );
});