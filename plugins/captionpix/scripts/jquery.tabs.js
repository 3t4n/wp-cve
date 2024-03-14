jQuery(document).ready ( function () {
  if(jQuery('.diy-metabox').length > 0) {
		jQuery('.diy-metabox li.tab a').each(function(i) {
		var thisMetabox =  jQuery(this).closest('.diy-metabox');
		var tabs = thisMetabox.children('.metabox-tabs'); 	
		var content = thisMetabox.find('.metabox-content'); 			
		var thisTab = jQuery(this).parent().attr('class').replace(/tab /, '');
		var selectedTab = thisMetabox.find('.tabselect');
		if ( thisTab == selectedTab.val() ) {
			jQuery(this).addClass('active');
            content.children('div.'+thisTab).addClass('active');        	
		} else {
	       	content.children('div.' + thisTab).hide();
		}
        jQuery(this).click(function(ev){
			ev.preventDefault();
			content.children('div').hide();
			content.children('div.active').removeClass('active');
			tabs.find('li a.active').removeClass('active');
				selectedTab.val(thisTab);
			tabs.find('li.'+thisTab+' a').addClass('active');
			content.children('div.'+thisTab).addClass('active').show();
			if (jQuery('#poststuff').length == 0) {
				boxes = jQuery('.postbox, .termbox');
				jQuery.post(ajaxurl, {
					action: 'captionpix_tab',
					box: tabs.closest(boxes).attr('id'),
					tabselect: thisTab,
					tabnonce: jQuery('#captionpixtabnonce').val()
				});
			}
			return false;
		});
	   	tabs.show();
	});
  }
});