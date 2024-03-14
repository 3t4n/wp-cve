jQuery(document).ready(function($) {
	$('.vca-tab-container').find('.tab-pane').first().addClass('active in');
	$('.vca-tab-container').find('.nav-tabs li:first').addClass('active');
	$('.vca-tab-container').find('.nav-stacked li:first').addClass('active');
	$('.vca-tab-container').find('.tab-content .tab-pane:first').addClass('active in');
	var tabs = $('.vca-tab-container');
	
	
	tabs.each(function(){
		var thisTabs = $(this);
		thisTabs.children('.tab-content').find('.tab-pane').each(function(index){
			index = index + 1;
			
			var that = $(this),
				link = that.attr('id'),
				// activeNav = that.closest('.tab-content').parent().find('.nav-tabs li').first().addClass('active'),
				navItem = that.closest('.tab-content').parent().find('.nav-tabs li:nth-child('+index+') a'),
				navLink = navItem.attr('href');

			link = '#'+link;
			if(link.indexOf(navLink) > -1) {
				navItem.attr('href',link);
			}
		});
	});

	
	
});