jQuery(document).ready(function(){
	var course_timer, group_timer , timespent_timer;
	var migration_course_callback = function(self, span, progress) {
		jQuery.ajax({
			url: admin_url.url,
			type: 'POST',
			// async: false,
			data: {
				action: 'upgrade_course_enrollments'
			},
			success: function( response ) {
				progress.val(response.data.percentage);
				progress.text(response.data.percentage + '%');
				span.text(response.data.percentage + '%');
				/*if ( response.data.percentage  ) {
					migration_course_callback();
				} */
				if ( ! response.data.next ) {
					clearInterval(course_timer);
					self.removeAttr('disabled');
				}
			}
		});
	}

	jQuery('.start-course-migration').on('click', function(event) {
		event.preventDefault();
		var self = jQuery(this);
		var span = self.next();
		var progress = span.next();
		span.removeClass('hidden');
		progress.removeClass('hidden');
		self.attr('disabled', 'disabled');
		// var looper = true;
		course_timer = setInterval(function(){
			migration_course_callback(self, span, progress);
		}, 2000);
		// while ( looper ) {
			// jQuery.ajaxSetup({});  
			
		// }
		// 
	});

	var migration_group_callback = function(self, span, progress) {
		jQuery.post(
			admin_url.url,
			{
				action: 'upgrade_group_enrollments'
			},
			function( response ) {
				progress.val(response.data.percentage);
				progress.text(response.data.percentage + '%');
				span.text(response.data.percentage + '%'); 
				if ( ! response.data.next ) {
					clearInterval(group_timer);
					self.removeAttr('disabled');
				}
			}
		);
	};
	
	jQuery('.start-group-migration').on('click', function(event) {
		event.preventDefault();
		var self = jQuery(this);
		var span = self.next();
		var progress = span.next();
		span.removeClass('hidden');
		progress.removeClass('hidden');
		self.attr('disabled', 'disabled');
		// var looper = true;
		// while ( looper ) {
			// jQuery.ajaxSetup({async: false});
			
		// }
		group_timer = setInterval(function(){
			migration_group_callback(self, span, progress);
		}, 2000);
	});



	//time spent data migration

	var migration_timespent_callback = function(self, span, progress) {
		jQuery.post(
			admin_url.url,
			{
				action: 'upgrade_course_time_spent'
			},
			function( response ) {
				progress.val(response.data.percentage);
				console.log(response.data.percentage);
				console.log(response.data.percentage === 100);
				if(response.data.percentage === 100){
					console.log("Done");
					progress.text(response.data.percentage + '%');
					span.text(response.data.percentage + '%');
					
					jQuery('.wrld_done_class').show();
				}else{
					progress.text(response.data.percentage + '%');
					span.text(response.data.percentage + '%'); 
				}
				
				if ( ! response.data.next ) {
					clearInterval(timespent_timer);
					//self.removeAttr('disabled');
				}
			}
		);
	};


	jQuery('.start-timespent-migration').on('click', function(event) {
		event.preventDefault();
		var self = jQuery(this);
		var span = self.next();
		var progress = span.next();
		span.removeClass('hidden');
		progress.removeClass('hidden');
		self.attr('disabled', 'disabled');
		// var looper = true;
		// while ( looper ) {
			// jQuery.ajaxSetup({async: false});
			
		// }
		timespent_timer = setInterval(function(){
			migration_timespent_callback(self, span, progress);
		}, 2000);
	});
});