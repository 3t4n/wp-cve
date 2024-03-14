jQuery(document).ready(function() {
	
    // Get the client's fingerprint id
const it_epoll_client_fingerprint = '';
	jQuery('.eg_main_content #epoll_container_opinion').each(function(){

		var it_epoll_opionion_item = jQuery(this);
		jQuery(it_epoll_opionion_item).find('#epoll_poll_opinion_form').validate({
			errorClass:'it_epoll_opnion_opt_error',
			errorPlacement: function(error, element) {  
				var shakeable = jQuery(it_epoll_opionion_item).find('.epoll_poll_options');
				jQuery(shakeable).addClass("it_epoll_shake_anim");
 				setTimeout(function(){
					jQuery(shakeable).removeClass("it_epoll_shake_anim"); 
				}, 500);
            },
			validHandler: function(error, element) {  
                jQuery(element).parent().parent().removeClass('it_epoll_poll_opt_with_error');
            },
			submitHandler:function(form){
				


				var it_epoll_btn  = jQuery(this);
				if(jQuery(it_epoll_opionion_item).find('.it_epoll_multi_vote').val() == 1){
					var formD = {
						'action': 'it_epoll_opinion_multivote',
						'fingerprint':it_epoll_client_fingerprint,
						'wp_nonce':jQuery(form).find('#it_epoll_poll-security_check').val(),
						'data': jQuery(form).serialize()// We pass php values differently!
					};
				}else{
					var formD = {
						'action': 'it_epoll_opinion_vote',
						'fingerprint':it_epoll_client_fingerprint,
						'wp_nonce':jQuery(form).find('#it_epoll_poll-security_check').val(),
						'data': jQuery(form).serialize()// We pass php values differently!
					};
				}
				
		
			// We can also pass the url value separately from ajaxurl for front end AJAX implementations
				jQuery.ajax({url:it_epoll_ajax_obj.ajax_url, 
					data:formD,
					type:'POST',
				beforeSend:function(){
					jQuery('.epoll_poll_loader').addClass('epoll_poll_loader_show');
				},
				complete:function(){
					jQuery('.epoll_poll_loader').removeClass('epoll_poll_loader_show');
				},
				success:function(response){
					jQuery('.epoll_poll_loader').removeClass('epoll_poll_loader_show');
					var it_epoll_json = jQuery.parseJSON(response);
					var arrayOpt = [];
					arrayOpt = it_epoll_json.options;
					if(it_epoll_json.voting_status == 'done'){
						
						for (let i = 0; i < arrayOpt.length; i++) {
							var epoll_option = arrayOpt[i];
							var epoll_option_item = jQuery('#epoll_poll_option_id_'+epoll_option.option_id);
							jQuery(epoll_option_item).find('.it_epoll_poll_opt_progressbar').data('count',epoll_option.vote_percentage);
							jQuery(epoll_option_item).find('.it_epoll_otp_result_right').html(epoll_option.vote_percentage+' <span>('+epoll_option.vote_count+')</span>');
							
						  }
						  jQuery(it_epoll_opionion_item).find('.it_epoll_total_vote_count').text(it_epoll_json.total_vote);
						  jQuery(it_epoll_opionion_item).find('#epoll_opinion_show_result_button').click();
						  jQuery(it_epoll_opionion_item).find('#epoll_opinion_hide_result_button').addClass('epoll_hide_element'); 
					}else{
						jQuery(it_epoll_opionion_item).find('.epoll_poll_opinion_form').prepend('<p class="epoll_opinion_voting_error it_epoll_otp_form_container_error" style="display:block;">'+it_epoll_json.msg+'</p>')
						setTimeout( function(){jQuery(it_epoll_opionion_item).find('.epoll_opinion_voting_error').remove();} , 3000);
					}
				}
			  });
			}
		});
		
	});

});