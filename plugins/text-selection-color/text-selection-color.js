jQuery(document).ready(function($){

 if( typeof jQuery.wp === 'object' && typeof jQuery.wp.wpColorPicker === 'function' ){

$('.text-color').wpColorPicker({
palettes: false,
change: function(event, ui) {
        $(".preview-text").css( 'color', ui.color.toString());
    }
});

$('.text-bg-color').wpColorPicker({
palettes: false,
change: function(event, ui) {

        $(".preview-text").css( 'backgroundColor', ui.color.toString());
    }
});
}
else {
    //$( '#colorpicker' ).farbtastic('.text-color');
    //$( '#colorpicker2' ).farbtastic('.text-bg-color');
    $('.text-color').focus(function() {
   colorPicker = $(this).next('div');
	input = $(this);
	$.farbtastic($(colorPicker), function(a) {$(input).val(a).css('background', a); $('.preview-text').val(a).css('color', a); });
    colorPicker.show();
    e.preventDefault();

}).blur(function() {
     colorPicker.hide();

});

 $('.text-bg-color').focus(function() {
   colorPicker = $(this).next('div');
	input = $(this);
	$.farbtastic($(colorPicker), function(a) { $(input).val(a).css('background', a); $('.preview-text').val(a).css('backgroundColor', a); });
    colorPicker.show();
    e.preventDefault();

}).blur(function() {
     colorPicker.hide();

});
}

});