jQuery(document).ready(function($) {


		$(document).on('click', '.reset-email-templates', function()
			{

				var is_confirm = $(this).attr('confirm');
				$(this).text('Confirm');

				if(is_confirm=='ok'){
					
					$.ajax(
						{
					type: 'POST',
					context: this,
					url:job_bm_ajax.job_bm_ajaxurl,
					data: {"action": "job_bm_ajax_reset_email_templates_data",},
					success: function(data)
							{	
								//alert(data);
								//$(this).html(data);
								//$(this).parent().parent().remove();
								//$('.see-phone-number .phone-number').html(data);
								location.reload(true);
							}
						});
					
					}
				else{
					$(this).attr('confirm','ok');
					$(this).val('Confirm');
					
					
					}

				})









	});	







