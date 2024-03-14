jQuery( document ).ready(function( $ ) {
	
	var loadData = function(ID, type, postData, obj, location){
		
		var dataTR = type+'_view_tr_'+ID;
		var dataTD = type+'_view_td_'+ID;
		var loadingTR = 'loading_tr_'+ID;
		
		var postViewTR = 'post_view_tr_'+ID;
		var pageViewTR = 'page_view_tr_'+ID;
		
		var postViewTD = 'post_view_td_'+ID;
		var pageViewTD = 'page_view_td_'+ID;
		
		if(location == 'load') {
			
			if(!$("#"+dataTR).is(':hidden')) {
				
				$('#'+dataTR).hide();
				obj.removeClass('active');
				return;
				
			} else {
				
				if($('#'+dataTD).html() != '') {
					if(type == 'page') {
			    		$('#'+postViewTR).hide();
			    		$('#post_view_count_'+ID).removeClass('active');
			    	} else {
			    		$('#'+pageViewTR).hide();
			    		$('#page_view_count_'+ID).removeClass('active');
			    	}
					obj.addClass('active');
					
					$('#'+dataTD).show();
					$('#'+dataTR).show();
					
					return;
					
				}
			}
		} 
		
		$.ajax({
		    type: 'POST',
		    url: pageViewData.ajaxurl,
		    data: postData,
		    beforeSend: function() {
		    	
		    	if(location == 'load') { 
		    		
		    		if(type == 'page') {
			    		$('#'+postViewTR).hide();
			    	} else {
			    		$('#'+pageViewTR).hide();
			    	}
		    		
		    	} else {
		    		
		    		$('#'+pageViewTR).hide();
		    		$('#'+postViewTR).hide();
		    		
		    	}
		    	
		    	$('#'+loadingTR).show();
		    },
		    success: function(data) {
		    	$('#'+loadingTR).hide();
		    	$('#'+dataTD).html(data);
		    	
		    	if(location == 'sort') {
		    		if(type == 'page') {
		    			$('#'+pageViewTR).show();
		    		} else {
		    			$('#'+postViewTR).show();
		    		}
		    	}
		    	
		    	$('#'+dataTR).show();
		    	
		    	
		    	if(type == 'page') {
		    		$('#post_view_count_'+ID).removeClass('active');
		    	} else {
		    		$('#page_view_count_'+ID).removeClass('active');
		    	}
		    	
		    	if(location == 'load') {
		    		obj.addClass('active');
		    	}
		    	
		    },
		    error: function(xhr) { // if error occured
		    	$('#'+loadingTR).hide();
		        alert("Error occured. Please try again");
		    },
		    complete: function() {
		    	
		    }
		});

	}
	
	
	$('.detail_data').on('click', '.sort-detail-data', function(){
		var ID = $(this).parent().parent().data('id');
		var type = $(this).parent().parent().data('type');
		
		var $this = $(this);
		
		var orderBy = 'asc';
		if($this.hasClass('asc') || $this.hasClass('ASC')) {
			orderBy = 'desc';
		}
		
		var postData = {
				'action': 'load_views_data',
				'id': ID,
				'type': type,
				'date_range': jQuery('#date_range').val(),
				'sort_field' : $this.data('field'),
				'sort_order' : orderBy
			};
		
		loadData(ID, type, postData, $this, 'sort');
		
	});
  
	$('#view-count-table').on('click', '.load_detail_data', function(){
		
		var ID = $(this).data('id');
		var type = $(this).data('type');
		
		var $this = $(this);
		
		var postData = {
				'action': 'load_views_data',
				'id': ID,
				'type': type,
				'date_range': jQuery('#date_range').val()
			};
		
		loadData(ID, type, postData, $this, 'load');
		
	});
	
	
	$('.sortdata').on('click', function(){
		
		$('#sort_field').val($(this).data('field'));
		
		var order = 'asc';
		
		if($(this).parent().hasClass('asc')) {
			order = 'desc';
		}
		
		$('#sort_order').val(order);
		
		$('#view-search-form').submit();
	});
	
	
	jQuery('.page_link').on('click', function(e){

		e.preventDefault();
		
		if(!$(this).hasClass('disabled')) {
			jQuery('#pagenum').val($(this).data('page'));
			
			$('#view-search-form').submit();
		}
		
	});
	
	jQuery('#reset_filter').on('click', function(){
		
		$('#date_range').val('');
		$('#sort_order').val('');
		$('#sort_field').val('');
		$('#pagenum').val('1');
		
		$('#view-search-form').submit();
		
	});
	
	$('#date_range').daterangepicker({
		posX: null,
		posY: null,
		appendTo: $('#date_range_td'),
		dateFormat : 'd/m/yy'
	});
	
	
});