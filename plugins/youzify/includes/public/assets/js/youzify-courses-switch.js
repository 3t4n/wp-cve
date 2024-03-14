( function( $ ) {

	'use strict';

	$(document).ready(function() {
		$('#toggleButton').click(function() {
		  var icon = $('#btn-switch');
		//   alert('helloe');
	  
		  if (icon.hasClass('fa-th-list')) {
			
			$('.youzify-courses-page').removeClass('youzify-courses-page').addClass('youzify-courses-page-grid');
			$('.youzify-tab-course').removeClass('youzify-tab-course').addClass('youzify-tab-course-grid');
			$('.youzify-course-thumbnail').removeClass('youzify-course-thumbnail').addClass('youzify-course-thumbnail-grid');
			$('.youzify-no-thumbnail').removeClass('youzify-no-thumbnail').addClass('youzify-no-thumbnail-grid');

			// icon.removeClass('fa-th-list').addClass('fa-th');
		  } else {
			$('.youzify-courses-page-grid').removeClass('youzify-courses-page-grid').addClass('youzify-courses-page');
			$('.youzify-tab-course-grid').removeClass('youzify-tab-course-grid').addClass('youzify-tab-course');
			$('.youzify-course-thumbnail-grid').removeClass('youzify-course-thumbnail-grid').addClass('youzify-course-thumbnail');
			$('.youzify-no-thumbnail-grid').removeClass('youzify-no-thumbnail-grid').addClass('youzify-no-thumbnail');
			// icon.removeClass('fa-th').addClass('fa-th-list');
		  }

		  icon.toggleClass('fa-th-list fa-th');
		});
	  });
	  

})( jQuery );