function wmamc_openTab(evt, Name) {
    var i, tabcontent, tablinks;
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }
    document.getElementById(Name).style.display = "block";
    evt.currentTarget.className += " active";
}

(function($){
	
	$(document).on('click','#order_map_submit',function(e){
		e.preventDefault();
		
		$('.wmamc_loader').show();
		$('.wmamc_loader label').html('Mapping...');
		var p_data = [];
		jQuery('#mainform tbody tr').each(function(k,v){
			 p_data[k] = jQuery(this).find('input,select').serialize();
		});		
		 
		var other_data = jQuery("#order_mapping_nonce").val();
		
		$.ajax({
		url : plajax.ajax_url,
		type : 'post',
		data : {
			action : 'wmamc_orderMapping',
			data : p_data,
			order_mapping_nonce:other_data,
		},
		success : function( response ) {
			
			/*$('.wmamc_loader').hide();*/
			var arr = JSON.parse(response); 	
			
			if(jQuery.inArray(true, arr) !== -1 && jQuery.inArray(true, arr) !== -1){
				$('.msg_box').html('<center><p>Order Mapping Process Completed! Some orders are not Mapped! Please try again</p></center>');
			}
			else if(jQuery.inArray(true, arr) !== -1){
				$('.msg_box').html('<center><p>Order Mapping Process Completed! </p></center>');
			}else if(jQuery.inArray(false, arr) !== -1){
				$('.msg_box').html('<center><p>Order Mapping Process failed! Please try again</p></center>');
			}			
			
			setTimeout(function(){ location.reload(); }, 3000);
			
			
		}
		});
	});
	
	$(document).on('click','.dbbackup_button',function(ev){ 
		ev.preventDefault();
		
		$('.wmamc_loader').show();
		$('.wmamc_loader label').html('Mapping...');
		var p_data = jQuery('#dbbackupform').serialize();			
		
		$.ajax({
		url : plajax.ajax_url,
		method : 'POST',
		data : {
			action : 'wmamc_dbBackup',
			data : p_data,			
		},
		success : function( response ) {			
			$('.wmamc_loader').hide();
			
			var res = JSON.parse(response);  
			if(res.status == 0){
				$('.msg_box').html('<center><p>Database backup done. Location : /uploads/backups/woo_orderexport/'+res.filename+'</p></center>');
			}else{
				$('.msg_box').html('<center><p style="color:red;">Error in database backup process!! Please try again. </p></center>');
			}	
		
		}
		});
	});
	
	$(document).on('click','#refresh_orderlist',function(e){		
		$('.wmamc_loader').show();
		setTimeout(function(){ location.reload(); }, 1000);
		
	});
	
	$(document).on('change','#order_status,#product_cats',function(e){
		 
		var selcted_item_id = $(this).val(); 
		if(selcted_item_id != ' '){
			
			var add_li_selector = $('#'+$(this).attr('id') +'_queue'); 		
			var add_inputName = $(this).attr('id') +'_requested[]'; 		
			
			if(!add_li_selector.find("li[data-id='"+ selcted_item_id +"']").length > 0){				
			
				var selcted_item_text = $(this).find('option:selected').text();	
							
				var html = "<li data-id='"+ selcted_item_id +"'><span title='Remove'>x</span> "+ selcted_item_text +"<input type='hidden' name="+ add_inputName +" value="+ selcted_item_id +"></li>";
				
				add_li_selector.append(html);
			}else{
				console.log('yes');
				add_li_selector.find("li[data-id='"+ selcted_item_id +"']").remove();
			}
		}
				
	});
	
	$(document).on('click','#order_status_queue li span,#product_cats_queue li span',function(e){
		$(this).parent('li').remove();
	});
	
	
}
)(jQuery);