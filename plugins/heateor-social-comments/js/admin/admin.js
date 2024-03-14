jQuery( function(){
	jQuery('#tabs').tabs();
	jQuery('#heateor_sc_comments_order').sortable({
		update: function(event, ui){
			var newOrder = [];
			jQuery(this).find('li').each(function(){
				newOrder.push(jQuery(this).attr('id'));
			});
			jQuery('#heateor_sc_commenting_tab_order').val(newOrder.join(','));
		}
	});
	jQuery("input[name='heateor_sc[commenting_layout]']").click(function(){
		if(jQuery(this).val() == 'tabbed'){
			jQuery('#heateor_sc_comments_order li').css({'float': 'left', 'marginLeft': '3px'});
		}else{
			jQuery('#heateor_sc_comments_order li').css({'float': 'none', 'marginLeft': '0px'});
		}
	});
	if(jQuery("input[name='heateor_sc[commenting_layout]']:checked").val() == 'tabbed'){
		jQuery('.heateor_sc_tabbed_option').css('display', 'table-row');
	}else{
		jQuery('.heateor_sc_tabbed_option').css('display', 'none');
	}
	jQuery(".heateor_help_bubble").attr("title", heateorScHelpBubbleTitle), jQuery(".heateor_help_bubble").click(function() {
        jQuery("#" + jQuery(this).attr("id") + "_cont").toggle(500)
    });
} );