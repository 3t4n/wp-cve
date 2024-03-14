(function($) {
    // $() will work as an alias for jQuery() inside of this function
	$('.ns_wpg_color').click(function(){
	alert('You Clicked me');
	});
	$('.ns_wpg_color,').ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val(hex);
			$(el).ColorPickerHide();
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		}
	})
	.bind('keyup', function(){
		$(this).ColorPickerSetColor(this.value);
	});
})(jQuery);