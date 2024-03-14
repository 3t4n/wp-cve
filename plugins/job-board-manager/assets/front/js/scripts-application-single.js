jQuery(document).ready(function($) {


	$(document).on('click', '.application-single .application-card .comments', function(){
		var application_id = $(this).attr('application-id');



		if($(this).hasClass('active')){
			$(this).removeClass('active');
			$('.application-card-'+application_id+' .application-comments').fadeOut();
		}else{
			$(this).addClass('active');
			$('.application-card-'+application_id+' .application-comments').fadeIn();
		}


	})

		$(document).on('click', '.application-single .application-card .hire', function(){

			var application_id = $(this).attr('application-id');

			$(this).html('<i class="fas fa-spinner fa-spin"></i>');

			console.log(application_id);

			$.ajax(
				{
			type: 'POST',
			context: this,
			url:job_bm_ajax.job_bm_ajaxurl,
			data: {"action": "job_bm_ajax_application_marked_hired", "application_id":application_id,},
			success: function(data)
					{
						var data	= JSON.parse(data)
						var hired	= data['hired'];
						var message	= data['message'];

						if(hired =='no'){

							$(this).removeClass('hired');
							job_bm_notice('fail', message);
						}else{
							$(this).addClass('hired');
							job_bm_notice('success', message);

						}

						$(this).html('<i class="fas fa-medal"></i>');
					}
				});
		})


	$(document).on('click', '.application-single .application-card .trash', function(){

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
					var message	= data['message'];

					if(trash =='no'){

						$('.application-card-'+application_id).removeClass('application-trash');

						$(this).removeClass('trashed');
						//$(this).fadeIn();

						job_bm_notice('fail', message);
					}else{
						$(this).addClass('trashed');
						//$(this).parent().parent().parent().fadeOut();
						$('.application-card-'+application_id).addClass('application-trash');
						job_bm_notice('fail', message);
					}

					$(this).html('<i class="far fa-trash-alt"></i>');
				}
			});
	})







	$(document).on('mouseover','.application-single .star i',function(){

		data_count = $(this).attr('data-count');

		$(this).parent().attr('title','Click to rate');

		$(this).parent().children('i').each(function(index,element){

			if($(this).hasClass('fas')){
				$(this).removeClass('fas');
				$(this).addClass('far');

				if($(this).hasClass('fa-star-half-alt')){
					$(this).removeClass('fa-star-half-alt');
					$(this).addClass('fa-star');
				}



			}

			if( index < data_count){
				$(this).removeClass('far');
				$(this).addClass('fas');
			}


		})


	})



	$(document).on('mouseout','.application-single .star i',function(){

		current_rate = $(this).parent().attr('current-rate');
		current_rate_int = Math.floor(current_rate);
		current_rate_mod = (current_rate - current_rate_int);
		current_rate_mod = current_rate_mod.toFixed(2);

		console.log(current_rate_mod);
		console.log(current_rate_int);

		$(this).parent().children('i').each(function(index,element){
			console.log(index);


			if($(this).hasClass('fas')){
				$(this).removeClass('fas');
				$(this).addClass('far');

			}

			if(index == (current_rate_int) && current_rate_mod > 0.5){
				//$(this).addClass('fa-star-half-alt');

			}



			if( index < current_rate_int){
				$(this).removeClass('far');
				$(this).addClass('fas');
			}




		})


	})







	$(document).on('click','.application-single .star i',function(){
		data_count = $(this).attr('data-count');
		application_id = $(this).parent().attr('application_id');

		_this = this;

		$('.rating-text').html('<i class="fas fa-spin fa-spinner"></i>');

		$.ajax({
			type: 'POST',
			context: _this,
			url:job_bm_ajax.job_bm_ajaxurl,
			data: {
				"action" 		: "job_bm_ajax_application_rate",
				//"wpblockhub_ajax_nonce"	: wpblockhubadmin_ajax.ajax_nonce,
				"application_id" 		: application_id,
				"data_count" 		: data_count,
			},
			success: function( response ) {
				var data = JSON.parse( response );
				html = data['html'];
				star_rate = data['star_rate'];
				message = data['message'];

				if(star_rate >0){
					current_rate_int = Math.floor(star_rate);
					$(_this).parent().attr('current-rate',star_rate);
					job_bm_notice('success', message);

					$(this).parent().children('i').each(function(index,element){

						if($(this).hasClass('fas')){
							$(this).removeClass('fas');
							$(this).addClass('far');
						}
						if( index < current_rate_int){
							$(this).removeClass('far');
							$(this).addClass('fas');
						}

					})

				}else{
					job_bm_notice('fail', message);

				}


				//console.log(data);











			}
		});



	})

















	});	







