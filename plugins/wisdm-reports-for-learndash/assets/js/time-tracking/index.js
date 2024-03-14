let timeSpentOnPage = function () {

	let time;
	let start_idletimer;
	let end_idletimer;
	let idletimer = 0;
	let start_activetimer;
	let end_activetimer;
	let activetimer = 0;
	if(settings.status == 'on'){
		window.onload = resetTimer;
		document.onload = resetTimer;
		document.onmousemove = resetTimer;
		document.onmousedown = resetTimer; // touchscreen presses
		document.ontouchstart = resetTimer;
		document.onclick = resetTimer; // touchpad clicks
		document.onkeypress = resetTimer;
		document.addEventListener('scroll', resetTimer, true);
	}
	 
	function showPopup() {
	    if (jQuery('.is-active-popup').length < 1 && document.visibilityState == 'visible'){
	    	var string = '<div class="is-active-popup"><div><span>' + settings.message + '</span><button class="resume-timer">' + settings.btnlabel + '</button></div></div>';
	    	jQuery('body').append(string);
	    	start_idletimer = Date.now();
	    }
	}

	function calculateIdleTime() {
		if (document.visibilityState == 'hidden'){
			start_idletimer = Date.now();
		}
		else{
			end_ideltimer = Date.now();
			idletimer = idletimer + ((end_ideltimer - start_idletimer)/1000);
			console.log(idletimer);
		}
	}


	function resetTimer() {
	    clearTimeout(time);
	    time = setTimeout(showPopup, parseInt(settings.timer) * 1000);
	}

	function getCachedStorage() {
	    var archive = {}, // Notice change here
            keys = Object.keys(localStorage),
            i = keys.length;
        while ( i-- ) {
	    	if ( keys[i].includes( 'time_cached_' ) ) {
	            archive[ keys[i] ] = localStorage.getItem( keys[i] );
	    	}
        }
        return archive;
	}

	function sendFailedRequests() {
		var previous_failure = getCachedStorage();
		var data = [];
		for ( const property in previous_failure ) {
			data = JSON.parse( previous_failure[ property ] );
			jQuery.ajax({
	            type: "POST",
	            url: page_info.ajax_url,
	            dataType: "json",
	            data: {
	            	action: 'add_time_entry',
	            	security: page_info.security,
	                user_id: data.user_id,
	                course_id: data.course_id,
	                post_id: data.post_id,
	                total_time: data.total_time,
	                time: data.time
	            },
	            success: function( response ) {
	            	localStorage.removeItem( property );
	            },
	            error: function(XMLHttpRequest, textStatus, errorThrown) {
					// localStorage.setItem( current_key, JSON.stringify( data ) );
		            if (XMLHttpRequest.readyState == 4) {
	                    // HTTP error (can be checked by XMLHttpRequest.status and XMLHttpRequest.statusText)
	                }
	                else if (XMLHttpRequest.readyState == 0) {
	                    // Network error (i.e. connection refused, access denied due to CORS, etc.)
	                }
	                else {
	                    // something weird is happening
	                }
	            }
	        });
		}
	}


	function sendData(activetimer, is_not_mark_complete=true) {
		var data = {
			user_id: page_info.user_id,
			course_id: page_info.course_id,
			post_id: page_info.post_id,
			total_time: activetimer,
			time: Math.round( Date.now() / 1000 )
		};
		var current_key      = 'time_cached_' + Math.floor( Math.random() * 1000 );

		jQuery.ajax({
            type: "POST",
            url: page_info.ajax_url,
            dataType: "json",
            data: {
            	action: 'add_time_entry',
            	security: page_info.security,
                user_id: page_info.user_id,
                course_id: page_info.course_id,
                post_id: page_info.post_id,
                total_time: activetimer,
                time: Math.round( Date.now() / 1000 )
            },
            success: function( response ) {
				start_activetimer = Date.now();
				resetTimer();
				if ( ! is_not_mark_complete ) {
					jQuery( 'form.sfwd-mark-complete' )[0].submit();
				}
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
				localStorage.setItem( current_key, JSON.stringify( data ) );
	            if (XMLHttpRequest.readyState == 4) {
                    // HTTP error (can be checked by XMLHttpRequest.status and XMLHttpRequest.statusText)
                }
                else if (XMLHttpRequest.readyState == 0) {
                    // Network error (i.e. connection refused, access denied due to CORS, etc.)
                }
                else {
                    // something weird is happening
                }
            }

        });
	}
    jQuery('form.sfwd-mark-complete').one('submit', function(evnt){
    	evnt.preventDefault();
		end_activetimer = Date.now(); 
	    activetimer = ((end_activetimer - start_activetimer)/1000) - idletimer;
	    sendData( Math.round( activetimer ), false );
		start_activetimer = Date.now();
		resetTimer();
	});
	window.addEventListener("beforeunload", function(event) {
		if(jQuery('.is-active-popup').length > 0){
			jQuery('.resume-timer').trigger('click');
		}
	    end_activetimer = Date.now(); 
	    activetimer = ((end_activetimer - start_activetimer)/1000) - idletimer;
	    sendData( Math.round( activetimer ) );
	    console.log(activetimer);
	});

	jQuery(window).on('load', function(){
		setTimeout(function(){
			sendFailedRequests();
		}, 2000);

	    start_activetimer = Date.now(); 
	    window.addEventListener('visibilitychange', function (e) {
	    	if (jQuery('.is-active-popup').length < 1){
	        	calculateIdleTime();
	    	}
	    });
	});


	jQuery(document).on('click', '.resume-timer', function(){
		end_ideltimer = Date.now();
		idletimer = idletimer + ((end_ideltimer - start_idletimer)/1000);
		jQuery('.is-active-popup').remove();
		console.log(idletimer);
	});

};
timeSpentOnPage();

