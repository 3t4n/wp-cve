jQuery( document ).ready(function($) {
	
	if($('#woocommerce_peach-payments_embed_payments').length){
		if($('#woocommerce_peach-payments_embed_payments:checkbox:checked').length > 0){
			$('.form-table tr:nth-child(9), .form-table tr:nth-child(10), .form-table tr:nth-child(11)').css('display', 'table-row');
		}else{
			$('.form-table tr:nth-child(9), .form-table tr:nth-child(10), .form-table tr:nth-child(11)').css('display', 'none');
		}
	}
	
	$('#woocommerce_peach-payments_embed_payments').change(function() {
		if(this.checked) {
			$('.form-table tr:nth-child(9), .form-table tr:nth-child(10), .form-table tr:nth-child(11)').css('display', 'table-row');
		}else{
			$('.form-table tr:nth-child(9), .form-table tr:nth-child(10), .form-table tr:nth-child(11)').css('display', 'none');
		}
	});
	
	$(document).on('click', '.peach-version-rollback', function (e) {
		var a = $('.peach-core-modal-overlay');
		if(!a.hasClass('active')){
			a.addClass('active');
		}
    });
	
	$(document).on('click', '.peach-core-modal-close', function (e) {
		var a = $('.peach-core-modal-overlay');
		if(a.hasClass('active')){
			a.removeClass('active');
		}
    });
	
	$(document).on('click', '.peach-card-sync', function (e) {
		e.preventDefault();
		card_sync();
    });
	
	$(document).on('click', '.peach-version-rollback-confirm', function (e) {
		e.preventDefault();
		var a = $(this);
		$('.peach-core-modal-content').css('min-height','200px');
		$('.peach-core-modal-content').html('<div class="modal-content-loading"></div>');
		rollback(a.attr('href'));
    });
	
	function card_sync() {
		
		$.ajax({
			url:peach_plugin.ajax_url,
			data:{ 
			  action: 'peach_card_sync' 
			},
			beforeSend:function(){
				$('.peach-card-sync').css('display','none');
				$('.sync-pending').html('<p><img style="vertical-align:middle;" name="Synchronization" src="' + peach_plugin.peach_plugin_url + '/assets/images/loader.gif" width="31" height="31" alt="Synchronization" />&nbsp;&nbsp;Synchronization in progress. Do not interrupt this step!</p>');
			},
			success:function(data){
				$('.sync-pending').css('display', 'none');
				$('.sync-results').addClass('green');
				$('.sync-results').html(data);
			}
		});
		
	  }
	
	function rollback(url) {
		$.ajax({
		  type: 'POST',
		  url: url,
		  success: function(response) {
				$('.peach-core-modal-content').html(response);
				setTimeout(
				function() 
				{
				location.reload();
				}, 1000);
			},
		});
	  }
		
});