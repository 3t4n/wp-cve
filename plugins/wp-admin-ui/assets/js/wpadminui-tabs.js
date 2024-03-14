jQuery(document).ready(function($) {
	if(typeof sessionStorage!='undefined') {
		var wpui_tab_session_storage = sessionStorage.getItem("wpui_tab");
		if (wpui_tab_session_storage) {
			jQuery('#wpui-tabs').find('.nav-tab.nav-tab-active').removeClass("nav-tab-active");
			jQuery('#wpui-tabs').find('.wpui-tab.active').removeClass("active");
			
	    	jQuery('#'+wpui_tab_session_storage+'-tab').addClass("nav-tab-active");
	    	jQuery('#'+wpui_tab_session_storage).addClass("active");
	    } else {
	    	//Default TAB
	    	jQuery('#tab_wpui_global_display-tab').addClass("nav-tab-active");
	    	jQuery('#tab_wpui_global_display').addClass("active");
	    }
	};
    jQuery("#wpui-tabs").find("a").click(function(e){
    	e.preventDefault();
    	var hash = jQuery(this).attr('href').split('#tab=')[1];

    	jQuery('#wpui-tabs').find('.nav-tab.nav-tab-active').removeClass("nav-tab-active");
    	jQuery('#'+hash+'-tab').addClass("nav-tab-active");
    	

		sessionStorage.setItem("wpui_tab", hash);
    	
    	jQuery('#wpui-tabs').find('.wpui-tab.active').removeClass("active");
    	jQuery('#'+hash).addClass("active");
    });
});