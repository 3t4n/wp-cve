jQuery(document).ready(function($) {
	if(slimbox2_options['mobile'] || !/android|iphone|ipod|series60|symbian|windows ce|blackberry/i.test(navigator.userAgent)){
		slimbox_CSS();
		closeKeys = slimbox2_options['closeKeys'].split(',');
		previousKeys = slimbox2_options['previousKeys'].split(',');
		nextKeys = slimbox2_options['nextKeys'].split(',');
		for ( var i in closeKeys) closeKeys[i] = parseInt(closeKeys[i]);
		for ( var i in previousKeys) previousKeys[i] = parseInt(previousKeys[i]);
		for ( var i in nextKeys) nextKeys[i] = parseInt(nextKeys[i]);
		load_slimbox();
	}
});
function slimbox_CSS() {jQuery(function($) {
	$("#lbOverlay").css("background-color",slimbox2_options['overlayColor']);
	$("#lbPrevLink").hover(
		function () {
			$(this).css("background-image","url("+slimbox2_options["prev"]+")");
		},
		function () {
			$(this).css("background-image","");
		}
	);
	$("#lbNextLink").hover(
		function () {
			$(this).css("background-image","url("+slimbox2_options["next"]+")");
		},
		function () {
			$(this).css("background-image","");
		}
	);
	$("#lbCloseLink").css("background-image","url("+slimbox2_options["close"]+")");
})};

function load_slimbox() {jQuery(function($) {
	var options = {
		loop: slimbox2_options['loop'],
		overlayOpacity: slimbox2_options['overlayOpacity'],
		overlayFadeDuration: parseInt(slimbox2_options['overlayFadeDuration']),
		resizeDuration: parseInt(slimbox2_options['resizeDuration']),
		resizeEasing: slimbox2_options['resizeEasing'],
		initialWidth: parseInt(slimbox2_options['initialWidth']),
		initialHeight: parseInt(slimbox2_options['initialHeight']),
		imageFadeDuration: parseInt(slimbox2_options['imageFadeDuration']),
		captionAnimationDuration: parseInt(slimbox2_options['captionAnimationDuration']),
		counterText: slimbox2_options['counterText'],
		closeKeys: closeKeys,
		previousKeys: previousKeys,
		nextKeys: nextKeys
	}

		if(slimbox2_options['autoload']) {
			var images = $("a[href]").not("[rel^='nolightbox']").filter(function() {return /\.(jpeg|bmp|jpg|png|gif)(\?[\d\w=&]*)?$/i.test(this.href);});
			if(slimbox2_options['picasaweb']) images = images.add($("a[href^='http://picasaweb.google.'] > img:first-child[src]").parent());
			if(slimbox2_options['flickr']) images = images.add($("a[href^='http://www.flickr.com/photos/'] > img:first-child[src]").parent());
		} else 
		var images = $("a[rel^='lightbox']");		
		images.unbind("click").slimbox(options, function(el) {
			if (el.href.match(/^http:\/\/picasaweb.google./)!=null) {
				var href = el.firstChild.src.replace(/\/s\d+(?:\-c)?\/([^\/]+)$/, "/s640/$2");
			} else if (el.href.match(/^http:\/\/www.flickr.com\/photos\//)!=null) {
				var href = el.firstChild.src.replace(/_[mts]\.(\w+)$/, ".$1");
			} else var href = el.href;
			return parseForURL(href,eval(slimbox2_options['caption']));
		}, function(el) {
			if(slimbox2_options['autoload']) return (this == el) || ($(this).closest(slimbox2_options['selector'])[0] && ($(this).closest(slimbox2_options['selector'])[0] == $(el).closest(slimbox2_options['selector'])[0]));
			else return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
		});
})};

function parseForURL(uri, caption) {
	t = caption.split('/*');
	href=uri;
	if(t.length > 1) t = t[1].split('*/');
	if (t.length > 1) {
		uri = t[0];
		caption = t[1]
		if(uri.toLowerCase().match(/^javascript:/)!=null) uri = uri.substring(11,uri.length);
	}
	return [href, (slimbox2_options['url'])?'<a href="' + uri + '">'+caption+'</a>':caption];
};