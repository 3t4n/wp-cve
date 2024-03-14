jQuery(function($) {	
	jQuery('.ct-timer-style .clock-builder-output').each(function () {			
		var idvalue = jQuery(this).attr('id');
		$(this ).each(function () {
			var clocks = [];
			var Days = $('#' + idvalue).attr('data-days');
			var Hours = $('#' + idvalue).attr('data-hours');
			var Minutes = $('#' + idvalue).attr('data-minutes');
			var Seconds = $('#' + idvalue).attr('data-seconds');
			var Message = $('.ct-timer-style').attr('data-mssg');
			var URL = $('.ct-timer-style').attr('data-redirectUrl');
			var tabOption = $('.ct-timer-style').attr('data-urlNewTab');
			var ExpireOption = $('.ct-timer-style').attr('data-expireOption');
			FlipClock.Lang.Custom = { days: Days, hours: Hours, minutes: Minutes, seconds: Seconds };	
			var opts = {
				clockFace: 'DailyCounter',
				countdown: true,				
				language: 'Custom',
				callbacks: {
					stop: function () {	
						if ( ExpireOption == 'message' ) {
						  $('.ms' + idvalue).html( Message );
						}
						else {
							window.open( URL , tabOption );
						}			
					}
				}
			};	
			var endvalue = $('#' + idvalue).attr('data-enddate');
			if(endvalue != null && endvalue != '') {
				var datefrom = ((new Date(endvalue).getTime())/1000);
			}
			else {
				var endvalue = new Date(Date.parse(new Date()) + 1 * 24 * 60 * 60 * 1000);
				var datefrom = ((new Date(endvalue).getTime())/1000);
			}			
			
			var countdown = datefrom - ((new Date().getTime())/1000); 
			countdown = Math.max(1, countdown);        
			var clock = $('#' + idvalue).FlipClock(countdown,opts); 
			clocks.push(clock); 	
			$('.ms' + idvalue).html();		
		});
	});	
});

