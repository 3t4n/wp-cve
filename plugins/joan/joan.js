jQuery(function($){

	function get_current_show() {
	//Get the current show data
	$.post(crudScriptURL, {"action":"show-time-curd","crud-action" : "read", "read-type" : "current"}, function (currentShowJSON) {

		//var schedule = $.parseJSON(currentShowJSON);
		var schedule = currentShowJSON;
		var outputHTML = '';
		
		var currentShow = schedule['current-show'];
		if (currentShow.showName){
			var currentShowName = currentShow.showName;
			var imageURL = currentShow.imageURL;
			var linkURL = currentShow.linkURL;
			var startClock = currentShow.startClock;
			var endClock = currentShow.endClock;

			if (linkURL){
				currentShowName = '<a target="_blank" href="'+linkURL+'">'+currentShowName+'</a>';
			}



			outputHTML += '<h3 class="current-show">'+currentShowName+'</h3>';
			outputHTML += '<span>'+startClock + ' - ' + endClock + '</span>';

			if (imageURL){
				if (linkURL){
					outputHTML += '<a target="_blank" href="'+linkURL+'"><img class="joan-image-thumbnail" src="'+imageURL+'" alt="'+currentShow.showName+'" /></a>';
				} else {
					outputHTML += '<img class="joan-image-thumbnail" src="'+imageURL+'" alt="'+currentShow.showName+'" />';
				}
			}

		} else {                                                                                                                                                                                                                                                                                                                                            
			outputHTML += '<h3 class="current-show">'+currentShow+'<h3>';
		}

		var upcomingShow = schedule['upcoming-show'];

		if (upcomingShow){
			var upcomingShowName = upcomingShow.showName;
			var upcomingShowLink = upcomingShow.linkURL;
			var upcomingStartClock = upcomingShow.startClock;
			var upcomingEndClock = upcomingShow.endClock;

			if (upcomingShowLink){
				outputHTML += '<h3 class="upcoming-show"><strong>Up next:</strong> <a target="_blank" href="'+upcomingShowLink+'">'+upcomingShowName+'</a></h3>';
			} else {
				outputHTML += '<h3 class="upcoming-show"><strong>Up next:</strong> '+upcomingShowName+'</h3>';
			}

			outputHTML += '<span>'+upcomingStartClock + ' - ' + upcomingEndClock + '</span>';

		}

		$('.joan-now-playing').html(outputHTML);

		//Set a timer to update the widget every 2 minutes
		setTimeout (get_current_show, (120 * 1000));

	});

	}

	get_current_show();


});