

	function job_bm_notice(noticeClass, message){

		noticeDiv = document.getElementById('job-bm-notice');

		noticeDiv.innerHTML  = message;
		noticeDiv.classList.add('has-notice');
		noticeDiv.classList.add(noticeClass);

		console.log(noticeDiv);


		setTimeout(function(){
			noticeDiv.classList.remove(noticeClass);
			noticeDiv.classList.remove('has-notice');

		}, 2000);




	}

















