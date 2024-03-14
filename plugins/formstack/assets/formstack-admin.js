// Leave in place even if not being used. This js file is used as a hook-onto for localized data elsewhere.
(function($) {
	$('#formstack_iframe').css(
		{height: function(){return screen.height * .8 + 'px';}}
	);
})(jQuery);
