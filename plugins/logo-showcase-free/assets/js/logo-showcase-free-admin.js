jQuery(document).ready(function($){

	$(document).on('click', '.expandable .header', function(){
		if($(this).parent().hasClass('active')){
			$(this).parent().removeClass('active');
		}
		else{
			$(this).parent().addClass('active');	
		}
	});

	$(document).on('click', '.tab-nav li', function(){
		$(".active").removeClass("active");
		$(this).addClass("active");
		
		var nav = $(this).attr("nav");
		
		$(".box li.tab-box").css("display","none");
		$(".box"+nav).css("display","block");

		$("#nav_value").val(nav);
	});

	var pkslogo_types = $("#pkslogo_types").val();
	if( pkslogo_types == 1 ){
		$("#test01").show();
		$("#test02").hide();
	}
	else {
		$("#test01").hide();
		$("#test02").show();
	}

	$("#pkslogo_types").on('change', function(){
		var pkslogo_types = $("#pkslogo_types").val();
		if( pkslogo_types == 2 ){
			$("#test01").hide();
			$("#test02").show();
		}
		else{
			$("#test01").show();
			$("#test02").hide();
		}
	});

	var pkslogo_heights = $("#pkslogo_heights").val();
	if( pkslogo_heights == 1 ){
		$("#pkslghi1").show('slow');
		$("#pkslghi2").hide('slow');
	}
	else {
		$("#pkslghi1").hide('slow');
		$("#pkslghi2").show('slow');
	}

	$("#pkslogo_heights").on('change', function(){
		var pkslogo_heights = $("#pkslogo_heights").val();
		if( pkslogo_heights == 2 ){
			$("#pkslghi1").hide('slow');
			$("#pkslghi2").show('slow');
		}
		else{
			$("#pkslghi1").show('slow');
			$("#pkslghi2").hide('slow');
		}
	});
});