jQuery(document).ready(function($) {
	var wor_animate = words.animation;
	var allanimations = [];
	
	// var animationstyle = $(".demo"+words.counter+"");
	  
	var counter = 0;
	if (words.speed=='') {
		words.speed = '1200';
	}
	jQuery('.rwo-container').each(function(index) {
		console.log();
		var data_id = jQuery(this).data("id");
		$(".rotating-word"+data_id+" .rotate").textrotator({
		        animation: jQuery(this).data('animation'), 
		        speed: words.speed,
		        separator: ",",
		 });
		// allanimations.push(jQuery(this).data('animation'));
	  // var animationstyle = jQuery(this).data('animation'); 
	  	
	});
	// $.each(allanimations, function( index, value ) {
	// 	counter++;
	//   $(".rotating-word"+index+" .rotate").textrotator({
	//           animation: value, 
	//           speed: words.speed,
	//           separator: ",",
	//    });
	// });

	$(window).on("load", function() {
	   $('.rwo-container').show();
	});
	
});