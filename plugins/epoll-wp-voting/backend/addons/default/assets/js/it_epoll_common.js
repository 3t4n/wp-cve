jQuery(document).ready(function() {
	//Epoll js common actions
	jQuery('.it_epoll_pop_close').click(function(){
		jQuery('.it_epoll_pop_container').fadeOut();
	});

	jQuery('body .it_epoll_container_alert').each(function(){
		var item = jQuery(this);
		jQuery(this).find('.it_epoll_container_alert_close').on('click',function(){
			jQuery(item).removeClass('it_epoll_container_alert_show')
		});
	});

	//Epoll poll share popup actions & common
	jQuery('.eg_main_content #epoll_container_opinion').each(function(){
		var it_epoll_opionion_item = jQuery(this);

		jQuery(document).mouseup(function(e) 
		{
			var container = jQuery(it_epoll_opionion_item).find('#it_epoll_opinion_share_btn');

			// if the target of the click isn't the container nor a descendant of the container
			if (!container.is(e.target) && container.has(e.target).length === 0) 
			{

				jQuery(container).find('.epoll_sc_share_container').addClass('hide_epoll_sc_share_container');
			}
		});

		
		jQuery(it_epoll_opionion_item).find('#it_epoll_opinion_share_btn').click(function(){
			
			jQuery(this).find('.epoll_sc_share_container').toggleClass('hide_epoll_sc_share_container');
		});

		
		jQuery(it_epoll_opionion_item).find('#epoll_opinion_show_result_button,#epoll_opinion_hide_result_button').click(function(){
			jQuery(it_epoll_opionion_item).find('#epoll_opinion_show_result_button').toggleClass('epoll_hide_element');
			jQuery(it_epoll_opionion_item).find('#epoll_opinion_vote_button').toggleClass('epoll_hide_element');
			jQuery(it_epoll_opionion_item).find('#epoll_opinion_hide_result_button').toggleClass('epoll_hide_element');

			jQuery(it_epoll_opionion_item).find('.epoll_poll_options li').each(function(){
				jQuery(this).find('.it_epoll_otp_result_wrap').css({width:'0%',opacity:'0'});


				var progress = jQuery(this).find('.it_epoll_poll_opt_progressbar').data('count');
				jQuery(this).find('.it_epoll_poll_opt_progressbar').toggleClass('epoll_hide_element');
				jQuery(this).find('.it_epoll_otp_result_wrap').animate({width:progress,opacity:'1'});
				jQuery(this).find('.it_epoll_opt_radio ').removeClass('it_epoll_opnion_opt_error');


				if(!jQuery(this).find('.it_epoll_poll_opt_progressbar').hasClass('epoll_hide_element')){
					jQuery(this).find('.it_epoll_opt_radio_wrap [type="radio"] + label').animate({paddingLeft:'25px'});
				}else{
					jQuery(this).find('.it_epoll_opt_radio_wrap [type="radio"] + label').css({paddingLeft:'50px'});
				}
				
				jQuery(this).find('.epoll_show_radio').toggleClass('epoll_hide_radio');
			});
		});
	});

    
	//Epoll contest share popup actions
	jQuery('.eg_main_content .it_epoll_container').each(function(){
		
		var it_epoll_opionion_item = jQuery(this);
		jQuery(it_epoll_opionion_item).find('#it_epoll_opinion_share_btn').mouseenter(function(){
			jQuery(this).find('.epoll_sc_share_container').fadeIn();
		});
		jQuery(it_epoll_opionion_item).find('.it_epoll_container_alert').on('click',function(){
			//jQuery(it_epoll_opionion_item).find('.it_epoll_container_alert').removeClass('it_epoll_container_alert_show');
		});

		  
		jQuery('.it_epoll_container_alert_inner').click(function(event){
				event.stopPropagation();
		});

		jQuery(it_epoll_opionion_item).find('.it_epoll_poll_share_btn').on('click',function(){
			jQuery(it_epoll_opionion_item).find('#it_epoll_share_alert').addClass('it_epoll_container_alert_show');
		});
		
		jQuery(it_epoll_opionion_item).find('#it_epoll_opinion_share_btn').mouseleave(function(){
			jQuery(this).find('.epoll_sc_share_container').fadeOut();
		});
	});

});
(function () {
    jQuery(function () {
        return jQuery('[data-toggle]').on('click', function () {
            var toggle;
            toggle = $(this).addClass('active').attr('data-toggle');
            jQuery(this).siblings('[data-toggle]').removeClass('active');
            return jQuery('.surveys').removeClass('grid list').addClass(toggle);
        });
    });
    
    
}.call(this));