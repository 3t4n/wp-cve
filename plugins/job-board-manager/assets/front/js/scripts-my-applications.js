jQuery(document).ready(function($) {


	$(document).on('click', '.job-bm-my-applications .application-card .comments', function(){
		var application_id = $(this).attr('application-id');



		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$('.application-card-'+application_id+' .application-comments').fadeOut();
		}else{
			$(this).addClass('active');
			$('.application-card-'+application_id+' .application-comments').fadeIn();
		}


	})







	$(document).on('click', '.job-bm-my-applications .application-card .trash', function(){

		var application_id = $(this).attr('application-id');

		$(this).html('<i class="fas fa-spinner fa-spin"></i>');

		console.log(application_id);

		$.ajax(
			{
				type: 'POST',
				context: this,
				url:job_bm_ajax.job_bm_ajaxurl,
				data: {"action": "job_bm_ajax_application_marked_trash", "application_id":application_id,},
				success: function(data)
				{
					var data	= JSON.parse(data)
					var trash	= data['trash'];

					if(trash =='no'){

						$(this).removeClass('trashed');
						$(this).fadeIn();
					}else{
						$(this).addClass('trashed');
						$(this).parent().parent().parent().fadeOut();
					}

					$(this).html('<i class="far fa-trash-alt"></i>');
				}
			});
	})


});







