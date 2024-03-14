jQuery(function() {
	/* Centralizes the position of the box with the respectives width and height (in pixels) */
	boxwidth = 380;
	boxheight = 400;
	/* Made with a bit of jQuery plugin Dimensions */
	windowwidth = self.innerWidth ||
		jQuery.boxModel && document.documentElement.clientWidth ||
		document.body.clientWidth;
	windowheight = self.innerHeight ||
		jQuery.boxModel && document.documentElement.clientHeight ||
		document.body.clientHeight;
	posx = (windowwidth - boxwidth) / 2;
	posy = (windowheight - boxheight) / 2;
	jQuery("#loginbox").css({ left: posx + "px", top: posy + "px" });
});