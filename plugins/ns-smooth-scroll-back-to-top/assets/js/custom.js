jQuery(document).ready(function($){

	// Add Color Picker to 'ly_opacity' id
	$('#ns_btta_background').wpColorPicker();
	$('#ns_btta_text_color').wpColorPicker();
	$('#ns_btta_border_color').wpColorPicker();
	$('#ns_btta_background_hover').wpColorPicker();
	$('#ns_btta_text_color_hover').wpColorPicker();
	$('#ns_btta_border_color_hover').wpColorPicker();
	


	// square for select position of back to top
	$("#ns_square_"+$("#ns_btta_position").val()).css("background-color", "#6BAA01");
	$( ".ns_square" ).click(function() {
		$("#ns_btta_position").val($(this).attr("data-square"));
		$( ".ns_square" ).css("background-color", "#cecece"); 
		$(this).css("background-color", "#6BAA01"); 
	});


});	