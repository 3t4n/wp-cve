jQuery(document).ready(function($) { 
	
	$('ul#menu-main-menu, ul#primary-menu, ul.menu, .superfish').superfish({
		delay:       100,								// 0.1 second delay on mouseout 
		animation:   {opacity:'show',height:'show'},	// fade-in and slide-down animation 
		dropShadows: false								// disable drop shadows 
	});
	
});