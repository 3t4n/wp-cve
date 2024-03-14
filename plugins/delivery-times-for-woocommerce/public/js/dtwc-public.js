jQuery(document).ready(function( $ ) {
	var deliveryDays = dtwcSettings.deliveryDays;
	var weekDays = [ "sunday", "monday", "tuesday", "wednesday", "thursday", "friday", "saturday" ];
	var deliveryTimes = dtwcSettings.deliveryTimes;
	var prepDays = dtwcSettings.minDate;

	function minutesWithLeadingZeros( dt ) { 
		return (dt.getMinutes() < 10 ? "0" : "") + dt.getMinutes();
	}
	function hoursWithLeadingZeros(dt) { 
		return (dt.getHours() < 10 ? "0" : "") + dt.getHours();
	}

	var d = new Date();
	var currHour = hoursWithLeadingZeros( d );
	var currMin = minutesWithLeadingZeros( d );
	var currentTime = currHour + ":" + currMin;

	if (0 == prepDays) {
		if ( deliveryTimes.some(el => el > currentTime) ) {
			var minDate = $.datepicker.formatDate("yy-mm-dd", new Date());
		} else {
			var minDate = new Date((new Date()).valueOf() + 1000*3600*24);
			var minDate = $.datepicker.formatDate("yy-mm-dd", minDate);
		}
	} else {
		var minDate = dtwcSettings.minDate;
	}

	$("#dtwc_delivery_date").datepicker( {
		minDate: minDate,
		maxDate: dtwcSettings.maxDays,
		showAnim: "fadeIn",
		dateFormat: "yy-mm-dd",
		firstDay: dtwcSettings.firstDay,
		beforeShowDay: function(date) {
			var currentWeekday = weekDays[ date.getDay() ];
			if ( currentWeekday in deliveryDays ) {
				// So enable the date here by returning an array.
				return [ true, "dtwc_date_available", "This date is available"];
			}
			return [ false, "dtwc_date_unavailable", "This date is unavailable" ];
		}
	} );
} );

jQuery(document).ready(function( $ ) {
	$("#dtwc_delivery_date").change(function() {
		var chosenDate = $(this).val();
		var today = $.datepicker.formatDate("yy-mm-dd", new Date());

		var x = 30; // minutes interval
		var times = []; // time array
		var tt = 0; // start time

		// Create times array.
		for (var i=0;tt<24*60; i++) {
			var hh = Math.floor(tt/60);
			var mm = (tt%60);
			// Time added to array.
			times[i] = ("0" + (hh % 12)).slice(-2) + ":" + ("0" + mm).slice(-2);
			// Add 30 minutes to time.
			tt = tt + x;
		}

		// Delivery date is today.
		if (today === chosenDate) {

			var deliveryTimes = dtwcSettings.deliveryTimes;
			var result = [];

			for(var t in deliveryTimes){
				result.push(deliveryTimes[t]);
			}

			// Loop through times.
			result.forEach(dateCheck);
		}

		// Chosen date is AFTER today.
		if (today < chosenDate) {

			var deliveryTimes = dtwcSettings.deliveryTimes;
			var result = [];

			for(var t in deliveryTimes) {
				result.push(deliveryTimes[t]);
			}

			// Loop through times.
			result.forEach(resetTimes);
		}

		// Prep time check.
		function dateCheck(item) {
			// Update delivery times if selected date is today.
			if (item<=dtwcSettings.prepTime) {
				// Remove specific time from available options.
				$("#dtwc_delivery_time option[value='" + item + "']").hide();
			} else {
				// Add specific times to available options.
				$("#dtwc_delivery_time option[value='" + item + "']").show();
			}
		}

		// Delivery times reset.
		function resetTimes(item) {
			$("#dtwc_delivery_time option[value='" + item + "']").show();
		}

	});
});
