/*jshint eqnull:true */
/*!
 * jQuery Cookie Plugin v1.2
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2011, Klaus Hartl
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.opensource.org/licenses/GPL-2.0
 */
(function(a,b,c){function e(a){return a}function f(a){return decodeURIComponent(a.replace(d," "))}var d=/\+/g;a.cookie=function(d,g,h){if(g!==c&&!/Object/.test(Object.prototype.toString.call(g))){h=a.extend({},a.cookie.defaults,h);if(g===null){h.expires=-1}if(typeof h.expires==="number"){var i=h.expires,j=h.expires=new Date;j.setDate(j.getDate()+i)}g=String(g);return b.cookie=[encodeURIComponent(d),"=",h.raw?g:encodeURIComponent(g),h.expires?"; expires="+h.expires.toUTCString():"",h.path?"; path="+h.path:"",h.domain?"; domain="+h.domain:"",h.secure?"; secure":""].join("")}h=g||a.cookie.defaults||{};var k=h.raw?e:f;var l=b.cookie.split("; ");for(var m=0,n;n=l[m]&&l[m].split("=");m++){if(k(n.shift())===d){return k(n.join("="))}}return null};a.cookie.defaults={};a.removeCookie=function(b,c){if(a.cookie(b,c)!==null){a.cookie(b,null,c);return true}return false}})(jQuery,document)

jQuery(function($){

	function updateTabHeight(){
 		$('.joan-tabs').height($('.tab-container:eq(0)').height());
	}

	message_is_being_displayed = false;

	function display_message(message, state){


		var textColor = '';
		var borderColor = '';
		var backgroundColor = '';

		if (state == 'good'){
			var backgroundColor = '#49ac3c';
			var borderColor = '#227017';
			var textColor = '#fff';
		} else if (state == 'bad') {
			var backgroundColor = '#9b2121';
			var borderColor = '#400909'
			var textColor = '#fff';

		} else if (state == 'warning') {
			var borderColor = '#8b8525';
			var backgroundColor = '#c8c25b';
			var textColor = '#fff';

		}

		if ( ! message_is_being_displayed )
		{				

			message_is_being_displayed = true;
			$('.joan-message-window').css({right : '-2em', opacity: 0, display: 'block', backgroundColor: backgroundColor, color : textColor, borderColor: borderColor}).animate({ right: '2em', opacity : 1 }, 450).text(message).delay(5000).animate({right: '4em', opacity: 0}, 350, function(){
				message_is_being_displayed = false;
			});
		} else {

			$('.joan-message-window').stop(true, true).css({ backgroundColor: backgroundColor, color : textColor, borderColor: borderColor}).text(message).delay(5000).animate({right: '4em', opacity: 0}, 350, function(){
				message_is_being_displayed = false;
			});

		}

		$('.joan-message-window').click(function(){
			$('.joan-message-window').stop(true, true).animate({right: '4em', opacity: 0}, 350, function(){
				message_is_being_displayed = false;
			});
		});



	}


	function buildShowObject(show){

		var formHTML = '';

		var showName = show.showName;
		var imageURL = show.imageURL;
		var linkURL = show.linkURL;
		var showID = show.id;
		var startDay = show.dayOfTheWeek;
		var startTime = show.startTime;
		var endTime = show.endTime;

		// Get the start and end times from the UNIX timestamps
		var startTimeClock = show.startClock;
		var endTimeClock = show.endClock;

		//Build the HTML for each show entry change form
		formHTML += '<div class="joan-entry joan-id-'+showID+'" data-show-end-time="'+endTime+'">'+"\r";

		if (imageURL){
			formHTML += '<img class="joan-image hide-on-simple" src="'+imageURL+'" data-target-field-name="'+showID+'" />';
		}

		formHTML += '<p class="joan-show-name"><strong>'+startTimeClock+'</strong> to <strong>'+endTimeClock+'</strong><br/><input type="text" name="showName['+showID+']" value="'+showName+'" /> <span class="joan-delete-show"><a href="#" data-remove-show-id="'+showID+'" class="remove-show">Delete</a></span></p>'+"\r";
		formHTML += '<p class="joan-show-urls hide-on-simple"><input type="text" name="linkURL['+showID+']" value="'+linkURL+'" placeholder="External URL" class="image-url" /> <input type="hidden" name="imageURL['+showID+']" data-target-field-name="'+showID+'" value="'+imageURL+'" /></p>'+"\r";
		formHTML += '<p class="hide-on-simple"><input type="button" class="secondary-button button upload-image" data-target-field="'+showID+'" value="Set Jock Image" /> <input type="submit" value="Save Changes" class="button button-primary" /></p>';
		formHTML += '</div>';

		return formHTML;
	}

	function loadEntireSchedule(scriptURL){

		$.post(scriptURL, {action:"show-time-curd","crud-action": "read"}, function(scheduleJSON){

			//var days = $.parseJSON(scheduleJSON);
            var days = scheduleJSON;
			var formHTML = '';

			for (var day in days){

				formHTML += '<div class="'+ day.toLowerCase() +'-container day-container">';
				formHTML += '<h2>' + day + '</h2>';

				$(days[day]).each(function(key, entries){

					$(entries).each(function(){

						formHTML += buildShowObject(this);

					});

				});

				formHTML += '</div><!-- end this day of the week -->';
				
			}

			formHTML += '</form>';
			

			$('.joan-schedule').html(formHTML).removeClass('loading');
			$('.joan-entry').show();
			updateTabHeight();
		});

	}

	//Load the schedule on page load
	loadEntireSchedule($('#add-joan-entry').attr('action'));

	function insertShowIntoSchedule (show){

		var show = show[0];

		//First, find the appropriate day container
		var dayContainer = $('.'+show.dayOfTheWeek.toLowerCase()+'-container');

		var showAdded = false;

		//Find the last show to air right before this one and insert it after
		$($('.joan-entry', dayContainer).get().reverse()).each(function(){
			if ($(this).attr('data-show-end-time') < show.startTime){

				var insertHTML = buildShowObject(show);
				$(this).after(insertHTML);
				showAdded = true;

				return false;

			}

		});

		//If there are no shows, just add it
		if ($('.joan-entry', dayContainer).length == 0 || showAdded == false) {
			var insertHTML = buildShowObject(show);
			$('h2', dayContainer).after(insertHTML);
		}



 		//Slide down the newly created element
 		$('.joan-id-'+show.id).slideDown(250, function(){
	 		//Update the tab height
	 		updateTabHeight();
 		});


	}

	//Update the end day when the start day changes
	$('select.startDay').change(function(){

		$('select.endDay').val($('select.startDay').val());

	});

	$('#starttime').change(function(){
		$('#endtime').val($('#starttime').val());
	});

	//Updates to the Joan Table

	$('#add-joan-entry').submit(function(e){

		e.preventDefault();

		//Serialze the new entry and send it the CRUD script

		var data = $(this).serialize();
		var scriptURL = $(this).attr('action');

		$.post(scriptURL, data, function (results){
			if (results == 'scheduling conflict'){

				display_message('You already have a show sceduled at that time.', 'bad');

			} else if (results == 'bad linkURL'){

				display_message('Check your Link URL. It\'s no good.', 'bad');


			} else if (results == 'bad name'){

				display_message('You forgot to add a name.', 'bad');

			} else if (results == 'too soon'){

				display_message('The show can\'t start before it ends. Check your times.', 'bad');

			} else {

				display_message('Show added.', 'good');

				insertShowIntoSchedule($.parseJSON(results));

			}
		});

	});

	$('.joan-update-shows').on('submit', function(e){

		e.preventDefault();

		var data = $(this).serialize();
		var scriptURL = $(this).attr('action');

		$.post(scriptURL, data, function (results){
			if (results == 'good updates'){
				
				display_message('Changes saved.', 'good');

			}
		});

	});

	$(document).on('click','a.remove-show', function(e){
		console.log(this);
		e.preventDefault();
		//Confirm the deletion
		var x = confirm('Are you sure you want to delete this show? This cannot be undone.');
		if (x){
			var removeShowID = $(this).attr('data-remove-show-id');
			var removeNonce = $('#delete_entries_nonce_field').val();
			var referrer = $('.script-src').val();
			$.post($('#add-joan-entry').attr('action'), {action:"show-time-curd","crud-action" : "delete", "id" : removeShowID, "delete_entries_nonce_field" : removeNonce, "_wp_http_referer" : referrer}, function(result){
				if (result){
					//Remove this show from the list
					$('.joan-id-'+removeShowID).slideUp(250, function(){
						updateTabHeight();
					});
				}
			});
		}
	});

	//Create the tabs

	if ($.cookie('current-joan-tab')){
		var currentTab = $.cookie('current-joan-tab');
	} else {
		var currentTab = 0;
	}

	$('.tab-container:eq('+currentTab+')').fadeIn(250);
	$('.joan-tabs').height($('.tab-container:eq('+currentTab+')').height());

	$('.tab-navigation li').click(function(){
		//Get the index of the clicked <li>
		var index = $(this).index();

		if (index != currentTab){

			//Hide the current tab-container, then show the selected one
			$('.tab-container:eq('+currentTab+')').fadeOut(250);
			currentTab = index;
			$('.tab-container:eq('+currentTab+')').fadeIn(250);

			//Set the height
			$('.joan-tabs').height($('.tab-container:eq('+currentTab+')').height());

			//Update the cookie
			$.cookie('current-joan-tab', currentTab);

		}


	});

	//Make the view type toggleable

	viewType = 0;

	$('a.display-toggle').click(function(e){
		
		e.preventDefault();

		if ($(this).hasClass('full-display')){
			viewType = 1;
			$.cookie('set-full-display', true);
		} else {
			viewType = 0;
			$.cookie('set-full-display', '');
		}

		update_display_type();

	});


	$('.joan-entry input').on('focus', function(){

		if ( ! $.cookie('set-full-display')){
			$('.editing').removeClass('editing');
			$(this).closest('.joan-entry').addClass('editing');
			updateTabHeight();
		}


	});


	function update_display_type() {

		if (viewType == 0){
			$('.joan-entry').removeClass('editing');
		} else if (viewType ==1 ) {
			$('.joan-entry').addClass('editing');
		}

		updateTabHeight();

	}
});


	//Handle image uploading

	jQuery(document).ready(function($) {
 
	jQuery(document).on('click', '.upload-image',function() {
	 targetTextField = $(this).attr('data-target-field');
	 target = $('input[data-target-field-name="'+targetTextField+'"]');

	 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');

	 return false;
	});
	 
	window.send_to_editor = function(html) {
	 //imgurl = $('img',html).attr('src');
	 imgurl = $('<div>'+html+'</div>').find('img').attr('src');
	 $(target).val(imgurl);
	 tb_remove();

	 //Update the image
	 $('img[data-target-field-name="'+targetTextField+'"]').attr('src', imgurl).show();

	}
	 
	});
