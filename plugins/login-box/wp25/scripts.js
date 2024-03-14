jQuery(function() {
	/* works if  Login-box is showed */
	lbboxwidth = 310;
	lbboxheight = 290;
	/* Centralizes the position of the box
	with the respectives width and height (in pixels) */	
	/* Made with a bit of jQuery plugin Dimensions */
	windowwidth = self.innerWidth ||
		jQuery.boxModel && document.documentElement.clientWidth ||
		document.body.clientWidth;
	windowheight = self.innerHeight ||
		jQuery.boxModel && document.documentElement.clientHeight ||
		document.body.clientHeight;
	lbposx = (windowwidth - lbboxwidth) / 2;
	lbposy = (windowheight - lbboxheight) / 2;
	jQuery("#loginbox").css({ left: lbposx + "px", top: lbposy + "px" });
	jQuery("#loginbox").prepend("<a href='http://wordpress.org' title='Powered by WordPress' id='loginbox_wordpresslink'><span>WordPress</span></a>");
	jQuery("#loginbox").hover(function(){
		jQuery("#loginbox_close").fadeIn();
	},function(){
		jQuery("#loginbox_close").fadeOut();
	});
	jQuery("#loginbox_close input").val("");
});