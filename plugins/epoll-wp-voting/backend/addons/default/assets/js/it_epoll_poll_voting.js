jQuery(document).ready(function() {

    // Get the client's fingerprint id
const it_epoll_client_fingerprint = '';
	//Epoll js default voting
	jQuery('.eg_main_content .it_epoll_survey-item').each(function(){
		var it_epoll_item = jQuery(this);
		jQuery(this).find('#it_epoll_survey-vote-button').click(function(){

			jQuery(it_epoll_item).parent().find('.it_epoll_survey-item').each(function(){
				var it_epoll_multivote = jQuery(this).find('#it_epoll_multivoting').val();
			
				if(!it_epoll_multivote){
					jQuery(this).find('#it_epoll_survey-vote-button').val('...');
					jQuery(this).find('#it_epoll_survey-vote-button').attr('disabled','yes');
				}
			});
			

			var it_epoll_btn  = jQuery(this);	
			
		

			var formD = {
				'action': 'it_epoll_vote',
				'wp_nonce':jQuery(it_epoll_item).find('#it_epoll_poll-security_check').val(),
				'option_id': jQuery(it_epoll_item).find('#it_epoll_survey-item-id').val(),
				'fingerprint':it_epoll_client_fingerprint,
				'poll_id': jQuery(it_epoll_item).find('#it_epoll_poll-id').val() // We pass php values differently!
			};
	
		// We can also pass the url value separately from ajaxurl for front end AJAX implementations
		jQuery.ajax({url:it_epoll_ajax_obj.ajax_url, 
			data:formD,
			type:'POST',
		beforeSend:function(){
			jQuery(it_epoll_item).find('.it_epoll_spinner').fadeIn();
		},
		success:function(response){
			var it_epoll_json = jQuery.parseJSON(response);
			
			var arrayOpt = [];
			if(it_epoll_json.voting_status == 'done'){
			jQuery(it_epoll_item).parent().find('.it_epoll_survey-item').each(function(){
				var it_epoll_multivote = jQuery(this).find('#it_epoll_multivoting').val();
				if(!it_epoll_multivote){
		        	jQuery(this).find('#it_epoll_survey-vote-button').addClass('it_epoll_scale_hide');
		        }
		       
			});

		
			
			arrayOpt = it_epoll_json.options;
			
				
				for (let i = 0; i < arrayOpt.length; i++) {
					var epoll_option = arrayOpt[i];
					var epoll_option_item = jQuery('#epoll_poll_option_id_'+epoll_option.option_id);
					
					jQuery(epoll_option_item).find('.it_epoll_survey-progress-fg').animate({width:epoll_option.vote_percentage});
					jQuery(epoll_option_item).find('.it_epoll_survey-progress-label').text(epoll_option.vote_percentage);
					jQuery(epoll_option_item).find('.it_epoll_survey-completes,.it_epoll_survey-complete').text(epoll_option.vote_count+'/'+it_epoll_json.total_vote);
				  }
			
			
				setTimeout(function(){
					jQuery(it_epoll_btn).addClass('it_epoll_scale_show');
					jQuery(it_epoll_btn).val("Voted");
					jQuery(it_epoll_btn).prop("disabled",true);
					jQuery(it_epoll_btn).toggleClass("it_epoll_green_gradient");
					jQuery(it_epoll_item).find('.it_epoll_spinner').toggleClass("it_epoll_spinner_stop");	
					jQuery(it_epoll_item).find('.it_epoll_spinner').toggleClass("it_epoll_drawn");
				},300);
				}else{
					jQuery(it_epoll_item).find('.it_epoll_spinner').fadeOut();
				jQuery(it_epoll_item).find('.it_epoll_survey-item-action').html('<div class="it_epoll_already_voted">'+it_epoll_json.msg+'</div>');
			}
		}
			});
			
	
		});

	});
});