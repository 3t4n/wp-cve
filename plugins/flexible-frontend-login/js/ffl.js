jQuery(document).ready(function($) {
    // $() will work as an alias for jQuery() inside of this function
    
    // Add an empty container for overlay
    $(document).ready( function($) {
		$('body').prepend('<div id="ffl-mask"></div>');
	} );

	
	$(document).ready(function() { 
	 
		 //select all the a tag with name equal to ffl-modal
		 $('a[class=ffl-modal]').click(function(e) {
		     //Cancel the link behavior
		     e.preventDefault();
		     //Get the A tag
		     var id = $(this).attr('href');
		  
		     //Get the screen height and width
		     var maskHeight = $(document).height();
		     var maskWidth = $(window).width();
		  
		     //Set height and width to mask to fill up the whole screen
		     $('#ffl-mask').css({'width':maskWidth,'height':maskHeight});
		      
		     //transition effect    
		     $('#ffl-mask').fadeIn(600);   
		     $('#ffl-mask').fadeTo("fast",0.8); 
		  
		     //Get the window height and width
		     var winH = $(window).height();
		     var winW = $(window).width();
		            
		     //Set the popup window to center
		     $(id).css('top',  winH/2-$(id).height()/2);
		     $(id).css('left', winW/2-$(id).width()/2);
		  
		     //transition effect
		     $(id).fadeIn(1000);
		  
		 });
		  
		 //if close button is clicked
		 $('.ffl-window .ffl-close').click(function (e) {
		     //Cancel the link behavior
		     e.preventDefault();
		     $('#ffl-mask, .ffl-window').hide();
		 });    
		  
		 //if mask is clicked
		 $('#ffl-mask').click(function () {
		     $(this).hide();
		     $('.ffl-window').hide();
		 });        
		  
	});
	
	// calculate mask when user resizes window
	$(document).ready(function () {
		$(window).resize(function () {
	  
		     var box = $('#ffl-boxes .ffl-window');
	  
		     //Get the screen height and width
		     var maskHeight = $(document).height();
		     var maskWidth = $(window).width();
		    
		     //Set height and width to mask to fill up the whole screen
		     $('#ffl-mask').css({'width':maskWidth,'height':maskHeight});
		             
		     //Get the window height and width
		     var winH = $(window).height();
		     var winW = $(window).width();
	  
		     //Set the popup window to center
		     box.css('top',  winH/2 - box.height()/2);
		     box.css('left', winW/2 - box.width()/2);
	  
		});
	});
	

	 $(document).ready(function() {

		// find all instances of flexible-frontend-login to append individual links
		$('a[name=ffl-popup]').each(function(){
			// get the tags from link
			var id = $(this).attr('href');
			// set linked div to hidden
			$(id).hide();					
		});

		// select a tags with name equal to ffl-popup
		$('a[name=ffl-popup]').click(function(e) {
			// cancel link behavior
			e.preventDefault();
			// get the tags from link
			var id = $(this).attr('href');
			// Set visibility
			$(id).css('display', 'block');
		});
		
		 //if close button is clicked
		 $('.ffl-close-popup-link').click(function(e) {
		     //Cancel the link behavior
		     e.preventDefault();
			 // get parent id
			 var id = $(this).parents('div:eq(0)').attr('id');
			 // and hide it
			$('#'+id).hide();
		 });    
		  
	});	

});


