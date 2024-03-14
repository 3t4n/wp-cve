/**
 * @author http://www.cosmosfarm.com/
 */

function kboard_widget_move_up(widget_item){
	var kboard_widget_tab = jQuery(widget_item).parents('.kboard-widget-tab');
	kboard_widget_tab.prev().before(kboard_widget_tab);
	kboard_widget_item_checked(widget_item);
}

function kboard_widget_move_down(widget_item){
	var kboard_widget_tab = jQuery(widget_item).parents('.kboard-widget-tab');
	kboard_widget_tab.next().after(kboard_widget_tab);
	kboard_widget_item_checked(widget_item);
}

function kboard_widget_item_checked(kboard_widget_checkbox){
	var wrap = jQuery(kboard_widget_checkbox).parents('.kboard-widget-tab-wrap');
	var checkbox_value = [];
	var checked_list = jQuery('input[type=checkbox]:checked', wrap);
	if(checked_list.length > 0){
		jQuery(checked_list).each(function(index){
			checkbox_value[index] = jQuery(this).val();
		});
		checkbox_value = checkbox_value.join(',');
	}
	else{
		checkbox_value = '';
	}
	jQuery('.kboard-used-tab', wrap).val(checkbox_value);
}

function kboard_widget_notice_check(with_notice){
	var wrap = jQuery(with_notice).parents('.kboard-with-notice-wrap');
	var checked = jQuery('input[type=checkbox]:checked', wrap);
	if(checked.length > 0){
		jQuery('.kboard-with-notice', wrap).val('with_notice');
	}
	else if(checked.length == 0){
		jQuery('.kboard-with-notice', wrap).val('');
	}
}