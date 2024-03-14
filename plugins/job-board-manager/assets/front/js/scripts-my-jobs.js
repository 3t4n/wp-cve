jQuery(document).ready(function($) {


		$(document).on('click', '.job-bm-my-jobs .job-list .delete-job', function(){

			var is_confirm = $(this).attr('confirm');

			if(is_confirm=='ok'){

				var job_id = $(this).attr('job-id');
				$(this).html('<i class="fas fa-spinner fa-spin"></i>');


				$.ajax(
					{
				type: 'POST',
				context: this,
				url:job_bm_ajax.job_bm_ajaxurl,
				data: {"action": "job_bm_ajax_delete_job_by_id", "job_id":job_id,},
				success: function(data)
						{
                            var data	= JSON.parse(data)
                            var html	= data['html'];
                            var is_deleted	= data['is_deleted'];
							//alert(data);
							$(this).html(html);

							$(this).html('<i class="far fa-trash-alt"></i>');

							if(is_deleted=='yes'){
                                $('.my-job-card-'+job_id).fadeOut(2000);
								job_bm_notice('success', 'Job deleted');
							}

						}
					});


				}
			else{
					$(this).attr('confirm','ok');
					$(this).html('Confirm');

				}



			})

	});	







