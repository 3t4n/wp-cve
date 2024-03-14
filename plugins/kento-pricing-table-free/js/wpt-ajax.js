jQuery(document).ready(function(jQuery){

	jQuery(".wpt-bg-ul li").click(function(){
		jQuery('.wpt-bg-ul li.bg-selected').removeClass('bg-selected');
		jQuery(this).addClass('bg-selected');
		var wpt_bg_img = jQuery(this).attr('data-url');
		jQuery('#wpt_bg_img').val(wpt_bg_img);
	})

	//get gradient value
	jQuery(".wpt-corner-gradient").change(function(){ 
		var value =  50;
		jQuery('#wpt-corner-gradient-value').html(value);
	});

	// ajax form to add data field
	jQuery("#wpt-total-column, #wpt-total-row").blur(function(){
		var wpt_total_row = jQuery('#wpt-total-row').val();
		var wpt_total_column = jQuery('#wpt-total-column').val();
		var wpt_postid = jQuery('#wpt-postid').val();
		jQuery.ajax({
			type: 'POST',
			url: wpt_ajax.wpt_ajaxurl,
			data: {"action": "wpt_ajax_form", "wpt_total_row":wpt_total_row, "wpt_total_column":wpt_total_column, "wpt_postid":wpt_postid},
			success: function(data){
				jQuery("#wpt-total-data").html(data);
			}
		});
	});
});