jQuery(document).ready(function($) {
	$(".wp-submenu-wrap a").click(function() {
		$(".wp-submenu-wrap li").removeClass("current");
		$(this).parent("li").addClass("current");
	});
	$(".nav-tab-wrapper a").click(function() {
		$(".wp-submenu-wrap li").removeClass("current");
		var foundin = $('*:contains('+$(this).text()+')');
		$(foundin).addClass("current");
	});
});