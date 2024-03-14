(function(jQuery){
	jQuery.fn.jflatTimeline = function(options){
	
		/**------------------ SETTING PARAMETERS ------------------**/
		
		var timelinedates = new Array();
		var date_sort_asc = function (date1, date2) {
			// This is a comparison function that will result in dates being sorted in
			// ASCENDING order. As you can see, JavaScript's native comparison operators
			// can be used to compare dates. This was news to me.
			if (date1 > date2) return -1;
			if (date1 < date2) return 1;
			return 0;
		};
		
		var current_year = 0;
		var current_month = 0;
		var scroll_count = 2;
		var scrolled = 0;
		var scroll_time = 500;
		
		var month=new Array();
		month[0]="January";
		month[1]="February";
		month[2]="March";
		month[3]="April";
		month[4]="May";
		month[5]="June";
		month[6]="July";
		month[7]="August";
		month[8]="September";
		month[9]="October";
		month[10]="November";
		month[11]="December";
		
		var config = {};
		if(options){
			jQuery.extend(config, options);
		}
		
		
		/**------------------ BEGIN FUNCTION BODY ------------------**/
		
		return this.each(function(){
			selector = jQuery(this);
			
			if(config.scroll)
				scroll_count = parseInt(config.scroll);
		
			if(config.width)
				selector.css('width', config.width)

			if(config.scrollingTime)
				scroll_time = config.scrollingTime;
				
		/**------------------ INSERT  YEAR MONTH BAR------------------**/
		
			//
			if(!selector.children('.timeline-wrap').children('.event.selected').length)
				selector.children('.timeline-wrap').children('.event:first-child').addClass('selected')
			//This store the selected year to 'current_year'
			
			current_year = (new Date(selector.children('.timeline-wrap').children('.event.selected').attr('data-date'))).getFullYear() 
			//This store the selected year to 'current_month'
			current_month = (new Date(selector.children('.timeline-wrap').children('.event.selected').attr('data-date'))).getMonth()
			
			//This will generate the month-year bar if it doesn't exist + put the current year and month
			if(!selector.children('.month-year-bar').length){
				selector.prepend('<div class = "month-year-bar"></div>')
				selector.children('.month-year-bar').prepend('<div class = "year"><a class = "event_calender_wp_prev"></a><span>' + String(current_year) + '</span><a class = "event_calender_wp_next"></a></div>')
				selector.children('.month-year-bar').prepend('<div class = "month"><a class = "event_calender_wp_prev"></a><span>' + String(month[current_month]) + '</span><a class = "event_calender_wp_next"></a></div>')
			}
			
		/**------------------ STORING DATES INTO ARRAY------------------**/

			var i = 0;
			// Store the dates into timelinedates[]
			selector.children('.timeline-wrap').children('.event').each(function(){
				timelinedates[i] = new Date(jQuery(this).attr('data-date'));
				i++;
			})
			//Sort the dates from small to large
			timelinedates.sort(date_sort_asc)
			
		/**------------------ INSERT DATES BAR------------------**/
			
			//This will insert the month year bar
				
				
			if(!selector.children(".dates-bar").length)
				selector.children(".month-year-bar").after('<div class = "dates-bar"><a class = "event_calender_wp_prev"></a><a class = "noevent">No event found</a><a class = "event_calender_wp_next"></a></div>')
			
			//This for loop will insert all the dates in the bar fetching from timelinedates[]
			for(i=0; i < timelinedates.length; i++){
				dateString = String((timelinedates[i].getMonth() + 1) + "/" + timelinedates[i].getDate() + "/" + timelinedates[i].getFullYear())
				if(selector.children('.dates-bar').children('a[data-date = "'+ dateString +'"]').length)
					continue;
				selector.children('.dates-bar').children('a.event_calender_wp_prev').after('<a data-date = '+ dateString + '><span class = "date">' + String(timelinedates[i].getDate()) + '</span><span class = "month">' + String(month[timelinedates[i].getMonth()]) + '</span></a>')
			}
			
			//This will convert the event data-date attribute from mm/dd/yyyy into m/d/yyyy
			for(i = 0; i < selector.children('.timeline-wrap').children('.event').length; i++){
				var a = new Date(selector.children('.timeline-wrap').children('.event:nth-child(' + String(i+1)+ ')').attr('data-date'))
				dateString = String((a.getMonth() + 1) + "/" + a.getDate() + "/" + a.getFullYear())
				selector.children('.timeline-wrap').children('.event:nth-child(' + String(i+1)+ ')').attr('data-date', dateString)
			}
			
			
			//This will hide the noevent bar
			selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').each(function(){
				if((new Date(jQuery(this).attr('data-date'))).getFullYear() != current_year)
					jQuery(this).hide();
			})
			
			//event_calender_wp_prevent from calling twice
			if(selector.hasClass('calledOnce'))
				return 0;
			selector.addClass('calledOnce')
			
			//Add 'selected' class the date
			selector.children('.dates-bar').children('a[data-date ="' + String(selector.children('.timeline-wrap').children('.event.selected').attr('data-date')) + '"]').addClass('selected')
			//Adding Class s_screen
			if(selector.width() < 500)
				selector.addClass('s_screen')
				
			jQuery(window).resize(function(){
				if(selector.width() < 500)
					selector.addClass('s_screen')
				else
					selector.removeClass('s_screen')	
			})
		/**------------------ EVENTS HANDLING------------------**/

		/**------------------ EVENTS FOR CLICKING ON THE DATES ------------------**/
		
			selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').click(function(){
				a = String(jQuery(this).attr('data-date'));

				selector.children('.timeline-wrap').children('.event.selected').removeClass('selected');

				selector.children('.timeline-wrap').children('.event[data-date="' + a + '"]').addClass('selected');
				
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').removeClass('selected');
				jQuery(this).addClass('selected')

			})
			
		/**------------------ EVENTS FOR CLICKING TO THE event_calender_wp_next DATE EVENT ------------------**/
			
			selector.children('.dates-bar').children('a.event_calender_wp_next').click(function(){
				var actual_scroll = scroll_count;
				var c = selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible()').length
				if(scrolled + scroll_count >= c)
					actual_scroll = (c - scrolled)-1
				
				if(parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'))*actual_scroll > selector.children('.dates-bar').width())
					while(parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'))*actual_scroll > selector.children('.dates-bar').width() && actual_scroll > 1)
						actual_scroll -= 1;
				
				var a = (-1)*actual_scroll*parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'));
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').animate({marginLeft: '+=' + String(a)+ 'px'}, scroll_time)
				scrolled += actual_scroll;
				
				current_month = new Date(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(' + String(scrolled) + ')').attr('data-date')).getMonth()
				
				selector.children('.month-year-bar').children('.month').children('span').text(month[current_month])
			})
			
			
		/**------------------ EVENTS FOR CLICKING TO THE event_calender_wp_prevIOUS DATE EVENT ------------------**/
			
			
			selector.children('.dates-bar').children('a.event_calender_wp_prev').click(function(){
				var actual_scroll = scroll_count;
				var c = selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible()').length
				if(scrolled <= scroll_count)
					actual_scroll = scrolled;

				if(parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'))*actual_scroll > selector.children('.dates-bar').width())
					while(parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'))*actual_scroll > selector.children('.dates-bar').width() && actual_scroll > 1)
						actual_scroll -= 1;

					
				var a = actual_scroll*parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'));
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').animate({marginLeft: '+=' + String(a)+ 'px'}, scroll_time)
				scrolled -= actual_scroll;
				
				current_month = new Date(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(' + String(scrolled) + ')').attr('data-date')).getMonth()
				
				selector.children('.month-year-bar').children('.month').children('span').text(month[current_month])
			})
			
			
		/**------------------ EVENTS FOR CLICKING TO THE event_calender_wp_next MONTH ------------------**/
			
			selector.children('.month-year-bar').children('.month').children('.event_calender_wp_next').click(function(){

				if(!(current_month == 11))
					current_month += 1;
				else
					current_month = 0;
					
				var month_found = 0;
				
				selector.children('.month-year-bar').children('.month').children('span').text(month[current_month])
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible()').each(function(){
						month_found += 1 ;
					if((new Date(jQuery(this).attr('data-date'))).getMonth() >= current_month){
						return false;
					}
				})
				
				
				var a = (month_found-scrolled-1)*parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'));
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').animate({marginLeft: '-=' + String(a)+ 'px'}, scroll_time)
				scrolled = month_found - 1;
				
			})			
			
		/**------------------ EVENTS FOR CLICKING TO THE event_calender_wp_prevIOUS MONTH ------------------**/
			
			
			selector.children('.month-year-bar').children('.month').children('.event_calender_wp_prev').click(function(){
				if(!(current_month == 0))
					current_month -= 1;
				else
					current_month = 11;
					
				var month_found = 0;
				
				selector.children('.month-year-bar').children('.month').children('span').text(month[current_month])
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible()').each(function(){
						month_found += 1 ;
					if((new Date(jQuery(this).attr('data-date'))).getMonth() >= current_month){
						return false;
					}
				})
				
				
				var a = (month_found-scrolled-1)*parseInt(selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').css('width'));
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible():eq(0)').animate({marginLeft: '-=' + String(a)+ 'px'}, scroll_time)
				scrolled = month_found - 1;
				
				
			})
			
			
		/**------------------ EVENTS FOR CLICKING TO THE event_calender_wp_next YEAR ------------------**/
			
			selector.children('.month-year-bar').children('.year').children('.event_calender_wp_next').click(function(){
				current_year += 1;
				selector.children('.month-year-bar').children('.year').children('span').text(String(current_year))
				
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').each(function(){
				
					if((new Date(jQuery(this).attr('data-date'))).getFullYear() != current_year)
						jQuery(this).hide();
					else
						jQuery(this).show()
				})
				
				if(!selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible').length){
					selector.children('.dates-bar').children('a.noevent').css('display', 'block');
				}else{
					selector.children('.dates-bar').children('a.noevent').css('display', 'none');
					selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').css('margin-left', '0');
					scrolled = 0;
					selector.children('.timeline-wrap').children('.event').removeClass('selected');
					selector.children('.timeline-wrap').children('.event').each(function(){
						a = (new Date(jQuery(this).attr('data-date')))
						if(a.getFullYear() == current_year){
							jQuery(this).addClass('selected')
							current_month = a.getMonth();
							selector.children('.month-year-bar').children('.month').children('span').text(month[current_month])
							return false;
						}
					})
				}
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').removeClass('selected');
				selector.children('.dates-bar').children('a[data-date ="' + String(selector.children('.timeline-wrap').children('.event.selected').attr('data-date')) + '"]').addClass('selected')

			})
			
			
		/**------------------ EVENTS FOR CLICKING TO THE event_calender_wp_prevIOUS YEAR ------------------**/
			
			
			selector.children('.month-year-bar').children('.year').children('.event_calender_wp_prev').click(function(){
				current_year -= 1;
				selector.children('.month-year-bar').children('.year').children('span').text(String(current_year))
				
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').each(function(){
				
					if((new Date(jQuery(this).attr('data-date'))).getFullYear() != current_year)
						jQuery(this).hide();
					else
						jQuery(this).show()
				})

				if(!selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent):visible').length){
					selector.children('.dates-bar').children('a.noevent').css('display', 'block');
				}else{
					selector.children('.dates-bar').children('a.noevent').css('display', 'none');					
					selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').css('margin-left', '0');
					scrolled = 0;
					selector.children('.timeline-wrap').children('.event').removeClass('selected');
					selector.children('.timeline-wrap').children('.event').each(function(){
						a = (new Date(jQuery(this).attr('data-date')))
						if(a.getFullYear() == current_year){
							jQuery(this).addClass('selected')
							current_month = a.getMonth();
							selector.children('.month-year-bar').children('.month').children('span').text(month[current_month])
							return false;
						}
					})
				}
			
				selector.children('.dates-bar').children('a:not(.event_calender_wp_prev, .event_calender_wp_next, .noevent)').removeClass('selected');
							selector.children('.dates-bar').children('a[data-date ="' + String(selector.children('.timeline-wrap').children('.event.selected').attr('data-date')) + '"]').addClass('selected')

			})
			
		})
	}
})(jQuery)