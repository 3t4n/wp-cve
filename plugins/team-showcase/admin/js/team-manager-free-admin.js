(function( $ ) {
	'use strict';
	$(document).on('click', '.tab-nav li', function(){
		$(".active").removeClass("active");
		$(this).addClass("active");
		var nav = $(this).attr("nav");
		$(".box li.tab-box").css("display","none");
		$(".box"+nav).css("display","block");
		$("#nav_value").val(nav);
	});

	var team_manager_free_imagesize = $("#team_manager_free_imagesize").val();
	if( team_manager_free_imagesize == 1 ){
		$("#hide1").hide('slow');
	}
	else {
		$("#hide1").show('slow');
	}

	$("#team_manager_free_imagesize").on('change', function(){
		var team_manager_free_imagesize = $("#team_manager_free_imagesize").val();
		if( team_manager_free_imagesize == 2 ){
			$("#hide1").show('slow');
		}
		else{
			$("#hide1").hide('slow');
		}
	});

})( jQuery );