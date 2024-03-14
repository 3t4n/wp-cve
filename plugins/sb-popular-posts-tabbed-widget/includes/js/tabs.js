jQuery(document).ready(function($) {
// setting the tabs in the sidebar hide and show, setting the current tab
	$('div.sb_tabbed div').hide();
	$('div.t1').show();
	$('div.sb_tabbed ul.sb_tabs li.t1 a').addClass('tab-current');

// SIDEBAR TABS
$('div.sb_tabbed ul.sb_tabs li a').click(function(){
	var thisClass = this.className.slice(0,2);
	$('div.sb_tabbed div').not( thisClass ).hide();
	$('div.' + thisClass).show();
	$('div.sb_tabbed ul.sb_tabs li a').removeClass('tab-current');
	$(this).addClass('tab-current');
	});
});