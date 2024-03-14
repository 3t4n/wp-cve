document.addEventListener('DOMContentLoaded', function() {
	var courseID = "";
	var Calendar = FullCalendar.Calendar;
	var Draggable = FullCalendar.Draggable;
	var containerEl = document.getElementById('external-events');
	var calendarEl = document.getElementById('calendar');
	var checkbox = document.getElementById('drop-remove');

	// initialize the external events
	// -----------------------------------------------------------------

	new Draggable(containerEl, {
		itemSelector: '.fc-event',
		eventData: function(eventEl) {
			return {
				title: eventEl.innerText
			};
		}
	});

	// initialize the calendar
	// -----------------------------------------------------------------
	var prev_start_date = "";
	var prev_end_date = "";
	var cur_end_date = "";
	var datestrend = "";
	var events_src = "";
	var currDate = moment(new Date()).format("DD-MM-YYYY");
	currDate = currDate.split("-");
	var calendar = new Calendar(calendarEl, {

		headerToolbar: {
			left: 'prev,next today',
			center: 'title',
			right: 'dayGridMonth,timeGridWeek,timeGridDay'
		},
		editable: true,
		droppable: true, // this allows things to be dropped onto the calendar
		dayMaxEvents:2,
		eventDidMount: function(info) {
			console.log(info);
			var start_dt = moment(info.event._instance.range.start.toISOString()).format("YYYY-MM-DD");
			var end_dt = moment(info.event._instance.range.end.toISOString()).format("YYYY-MM-DD");
			var course_id = info.event.extendedProps.course_id;
			var event_id = info.event.id;

			var closeon_container = document.createElement('span');
			var closeon_left = document.createElement('span');
			var closeon_right = document.createElement('span');
			if (course_id == null){
				course_id = courseID;
			}
			var tooltip_content = document.createElement("div"); //tooltip main content div
			tooltip_content.className = "course-tooltip-content"; //tooltip main content div class

			var tooltip_title_el = document.createElement("div"); //tooltip title div
			tooltip_title_el.className = "course-tooltip-title"; //tooltip title div class
			var tooltip_title_content = document.createTextNode(info.event._def.title); //setting tooltip title content
			tooltip_title_el.appendChild(tooltip_title_content);

			var tooltip_start_date_el = document.createElement("div"); //course startdate div
			tooltip_start_date_el.className = "course-start-date"; //course startdate div class
			var tooltip_start_date_content = document.createTextNode(start_dt); //setting course startdate div content
			tooltip_start_date_el.appendChild(tooltip_start_date_content);

			var tooltip_end_date_el = document.createElement("div"); //course startdate div
			tooltip_end_date_el.className = "course-end-date"; //course startdate div class
			var tooltip_end_date_content = document.createTextNode(end_dt); //setting course startdate div content
			tooltip_end_date_el.appendChild(tooltip_end_date_content);

			tooltip_content.appendChild(tooltip_title_el); //adding event title element to main tooltip content div
			tooltip_content.appendChild(tooltip_start_date_el); //adding event start date element to main tooltip content div
			tooltip_content.appendChild(tooltip_end_date_el); //adding event end date element to main tooltip content div


			var tooltip = new Tooltip(info.el, {
				/*title: '<div class="course-tooltip-title">'+info.event._def.title+'</div><div class="course-start-date">Start Date: '+start_dt+'</div><div class="course-end-date">End Date: '+end_dt+'</div>',*/
				title: tooltip_content,
				placement: 'top',
				trigger: 'hover',
				container: 'body',
				html: true,
			});
			console.log(tooltip);
			closeon_container.setAttribute('data-event-id', event_id);
			closeon_container.setAttribute('data-course-dt', start_dt);
			closeon_container.setAttribute('data-course-id', course_id);

			closeon_left.setAttribute('data-course-dt', start_dt);
			closeon_left.setAttribute('data-course-id', course_id);
			closeon_left.setAttribute('data-event-id', event_id);

			closeon_right.setAttribute('data-course-dt', start_dt);
			closeon_right.setAttribute('data-course-id', course_id);
			closeon_right.setAttribute('data-event-id', event_id);


			closeon_container.setAttribute("id", "close-cont");
			closeon_container.className = "closeon closeon-container";

			closeon_left.setAttribute("id", "close-left");
			closeon_left.className = "closeon left";

			closeon_right.setAttribute("id", "close-right");
			closeon_right.className = "closeon right";


			closeon_container.appendChild(closeon_left);
			closeon_container.appendChild(closeon_right);

			insertAfter(info.el, closeon_container);


		},

		drop: function(info) {
			courseID = info.draggedEl.dataset.courseId;
			var datestr = info.dateStr;
			var notice = document.getElementsByClassName("invalid-date");
			var request = new XMLHttpRequest();
			request.open('POST', LDCSAdminVars.ajax_url, true);
			request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
			request.onload = function () {
				if (request.response =="true") {
					/* console.log("success"); */
					notice[0].style.display = "none";
					location.reload();
				} else {
					/* console.log("fail"); */
					notice[0].style.display = "block";
				}
			};
			request.onerror = function () {
				// Connection error
			};
			request.send('action=add_course_schedule&date=' + datestr + '&course_id=' + courseID);
		},
		eventDragStart: function (info) {
			console.log("Event Drag");console.log(info);

			prev_start_date = moment(info.event.start.toISOString()).format("YYYY-MM-DD");
			if(info.event.end !== null){
				prev_end_date = moment(info.event.end.toISOString()).format("YYYY-MM-DD");
			}
		},
		eventDrop: function(info) {

			console.log(info);
			var courseID = info.event.extendedProps.course_id;
			var datestr = moment(info.event.start.toISOString()).format("YYYY-MM-DD");
			if(info.event.end !== null) {
				 datestrend = moment(info.event.end.toISOString()).format("YYYY-MM-DD");
			}
			if(info.event.end !== null){
				cur_end_date = moment(info.event.end.toISOString()).format("YYYY-MM-DD");
			}
			var notice = document.getElementsByClassName("invalid-date");
			var confirmation = confirm("Are you sure about this change?");
			if ( !confirmation ) {
				// revertFunc();
				info.revert();
			}
			else {
				var request = new XMLHttpRequest();
				request.open('POST', LDCSAdminVars.ajax_url, true);
				request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
				request.onload = function () {
					if (request.response =="true") {
						/* console.log("success"); */
						notice[0].style.display = "none";
					} else {
						/* console.log("fail"); */
						notice[0].style.display = "block";
					}
				};
				request.onerror = function () {
					// Connection error
				};
				request.send('action=shift_course_schedule&newdatestart=' + datestr + '&newdateend=' + datestrend + '&prev_date=' + prev_start_date +'&prev_end_date=' + prev_end_date +'&course_id=' + courseID + '&remove_date=true');
			}
		},
		eventResize: function(info) {
			if (!confirm("is this okay?")) {
				info.revert();
			}else{
					console.log("Event Resize");
					console.log(info);
				if(courseID == ''){
					courseID = info.event.extendedProps.course_id;
				}
				var event_start_date = moment(info.event.start.toISOString()).format("YYYY-MM-DD");
				var event_end_date = moment(info.event.end.toISOString()).format("YYYY-MM-DD");

				var request = new XMLHttpRequest();
				request.open('POST', LDCSAdminVars.ajax_url, true);
				request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
				request.onload = function () {
					if (request.response =="true") {
						/* console.log("success"); */
						notice[0].style.display = "none";
					} else {
						/* console.log("fail"); */
						notice[0].style.display = "block";
					}
				};
				request.onerror = function () {
					// Connection error
				};
				request.send('action=course_reschedule&date=' + event_start_date + '&end_date=' + event_end_date +'&course_id=' + courseID);


			}
		},

		eventSources: [
			// your event source
			{
				url: LDCSAdminVars.ajax_url,
				method: 'POST',
				extraParams: {
					'action':'getEvents',
					'month':currDate[1],
					'year': currDate[2]
				},
				failure: function() {
					alert('there was an error while fetching events!');
				},
			}
			// any other sources.
		]
	});

	calendar.render();

	function insertAfter(referenceNode, newNode) {
		referenceNode.parentNode.insertBefore(newNode, referenceNode.nextSibling);
	}

	document.addEventListener('click', function(e){
		var el = e.target;
		if(el.className == 'closeon left' || el.className == 'closeon right' || el.className == 'closeon closeon-container'){

			var event_id = el.getAttribute("data-event-id");
			console.log(event_id);
			var courseDate = el.getAttribute("data-course-dt");
			var courseID = el.getAttribute("data-course-id");
			console.log(courseID);
			var confirmation = confirm("Are you sure you want to delete?");
			if ( confirmation ) {
				var request = new XMLHttpRequest();
				request.open('POST', LDCSAdminVars.ajax_url, true);
				request.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded; charset=UTF-8');
				request.onload = function () {
					if (request.response =="true") {
						var ex_course = calendar.getEventById(event_id);
						ex_course.remove();
					} else {

					}
				};
				request.onerror = function () {
					// Connection error
				};
				request.send('action=remove_course_schedule&date=' + courseDate + '&course_id=' + courseID);
			}else{

			}
		}

	});
});