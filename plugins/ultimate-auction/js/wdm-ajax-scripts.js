jQuery(document).ready(function($) {
    $(".see-more").click(function(){
	var current=$(this);
	var auction_id=$(this).attr('rel');
    
	$(".wdm-bidder-list-"+auction_id).css('opacity','0.4');
	var show_rows;
	var label_text;
	if($(this).hasClass('showing-top-5')){
	    show_rows=-1;
	    $(this).removeClass('showing-top-5');
	    $(this).addClass('showing-all');
	    label_text= ' '+wdm_ua_obj_l10n1.msg1;
	}
	else{
	    show_rows=5;
	    
	    $(this).removeClass('showing-all');
	    $(this).addClass('showing-top-5');
	    label_text= ' '+wdm_ua_obj_l10n1.msg2;
	}
	
	var data = {
		action: 'wdm_ajax',
		auction_id: auction_id,
		show_rows: show_rows
	};
	
	jQuery.post(ajaxurl, data, function(response) {
		
		$(".wdm-bidder-list-"+auction_id).html(response);
		current.text(label_text);
		$(".wdm-bidder-list-"+auction_id).css('opacity','1');
	});	
	return false;
	});
    
   
	
});