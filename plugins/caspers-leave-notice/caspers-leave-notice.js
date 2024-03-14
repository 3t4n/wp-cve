jQuery(document).ready(function($){
	var countdown;
	function cpln_close_notice(){
		$('.cpln-leavenotice').removeClass('cpln-active');
	}
	function cpln_set_displayed_url(url){
		$('.cpln-redirect-link').html('<a target="_blank" class="cpln-redirect-url" href="'+url+'">'+url+'</a>');
	}
	function cpln_open_notice(){
		$('.cpln-leavenotice').addClass('cpln-active');
	}
	function cpln_auto_redirect(el, url){
		//get start time
		var start = parseInt(el.dataset.startTime);
		var timeLeft = start;
		el.innerHTML = start;	//when countdown was previously closed... this resets the UI timer
		
		//set countdown, redirect, close window, and reset html
		countdown = setInterval(function(){
			timeLeft--;
			el.innerHTML = timeLeft;
			if(timeLeft < 1){ 
				window.open(url, '_blank'); 
				clearInterval(countdown);
				cpln_close_notice();
				el.innerHTML = start;
			}
		}, 1000);
	}
	function cpln_stop_redirect(){
		if(countdown){
			clearInterval(countdown);
		}
	}
	
	//get exclusion URLs, convert to regex, convert to array
	var cplnExclusions = [];
	$('li.cpln-exclude').each(function(){
		var cplnExclude = $(this).html();

		//convert common URL features to regex-ready strings
		cplnExclude = cplnExclude.replace(/\//g,'\\/');
		//cplnExclude = cplnExclude.replace(/\:/g,'\\:');
		cplnExclude = cplnExclude.replace(/\-/g,'\\-');		

		//tag on the exclusions to the array
		cplnExclusions.push( cplnExclude );

	});
	
	var cplnExList = '!obj.href.match(/^mailto\:/) && !obj.href.match(/^tel\:/) && !obj.href.match(/^javascript/) && (obj.hostname !== location.hostname)';
	//checks to see if any exclusions have been entered, if so, add them to the exclusion list
	if(cplnExclusions.length > 0 && cplnExclusions[0] !== '') {
		for(var i = 0; i < cplnExclusions.length; i++){
			cplnExList = cplnExList.concat(' && !obj.href.match(/' + cplnExclusions[i] + '/)');
		}
	}
	//console.log(cplnExList);
	// Creating custom :external selector
	$.expr[':'].external = function(obj){ //all links except mailto, tel, and links with hostname location
		return eval(cplnExList);
	};
	
	// Add 'external' CSS class to all external links
	$('a[href]:external').addClass('external');
	$('.no-notice').removeClass('external'); //add class 'no-popup' on any link you want to exclude from external warning
	$('.no-notice a').removeClass('external'); //allows applying class to menu navigation
	$('a.external').click(function(e){
		e.preventDefault();
		//if the element clicked on is NOT the link (i.e. a button with an icon), travel up until you find the element with the href
		var url = (e.target.hasAttribute('href')) ? e.target.getAttribute('href') : $(this).closest('[href]').attr('href'); 
		cpln_set_displayed_url(url);
		cpln_open_notice();
		if(document.querySelector('.cpln-redirect-box__time')){
			cpln_auto_redirect(document.querySelector('.cpln-redirect-box__time'), url);
		}
	});
	
	// CLOSE POP UP WHEN OPENING THE NEW PAGE
	$(document.body).on('click', '.cpln-redirect-url', function(){
		cpln_close_notice();
	});
	
	// CANCEL BUTTON AND ESC TO CLOSE POPUP
	$('.cpln-cancel').click(function(e){
		cpln_close_notice();
		e.preventDefault(); 
		cpln_stop_redirect();
	});
	$(document).keyup(function(e){
		if(e.keyCode === 27) {
			cpln_close_notice();
			cpln_stop_redirect();
		}
	});
});