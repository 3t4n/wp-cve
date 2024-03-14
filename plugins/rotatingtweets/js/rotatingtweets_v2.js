/*
 Add some transitions
*/

(function($) {
"use strict";

$.fn.cycle2.transitions.scrollDown = {
    before: function( opts, curr, next, fwd ) {
        opts.API.stackSlides( opts, curr, next, fwd );
        var width = opts.container.css('overflow','visible').width();
        var height = opts.container.css('overflow','hidden').height();
        opts.cssBefore = { top: fwd ? -height : height, left: 0, visibility: 'visible', opacity: 1, display: 'block' ,width:width };
        opts.animIn = { top: 0 };
        opts.animOut = { top: fwd ? height : -height };
    }
};
$.fn.cycle2.transitions.scrollUp = {
    before: function( opts, curr, next, fwd ) {
        opts.API.stackSlides( opts, curr, next, fwd );
        var width = opts.container.css('overflow','visible').width();
        var height = opts.container.css('overflow','hidden').height();
        opts.cssBefore = { top: fwd ? height : -height, left: 0, visibility: 'visible', opacity: 1, display: 'block' ,width:width };
        opts.animIn = { top: 0 };
        opts.animOut = { top: fwd ? -height : height };
    }
};
$.fn.cycle2.transitions.scrollLeft = {
    before: function( opts, curr, next, fwd ) {
        opts.API.stackSlides( opts, curr, next, fwd );
        var width = opts.container.css('overflow','hidden').width();
        opts.cssBefore = { width: width, left : width+20, top: 0, visibility: 'visible', opacity: 1, display: 'block' };
        opts.animIn = { left: 0 };
        opts.animOut = { left : -width-20,width:width };
    }
};

$.fn.cycle2.transitions.scrollRight = {
    before: function( opts, curr, next, fwd ) {
        opts.API.stackSlides( opts, curr, next, fwd );
        var width = opts.container.css('overflow','hidden').width();
        opts.cssBefore = { width: width, left : -width-20, top: 0, visibility: 'visible', opacity: 1, display: 'block' };
        opts.animIn = { left: 0 };
        opts.animOut = { left : width+20 };
    }
};

$.fn.cycle2.transitions.toss = {
    before: function( opts, curr, next, fwd ) {
        opts.API.stackSlides( opts, curr, next, fwd );
        var width = opts.container.css('overflow','visible').width();
		var height = opts.container.css('overflow','visible').height();
        opts.cssBefore = { left: 0, top: 0, opacity: 1, visibility: 'visible', display: 'block', width:width };
        opts.animIn = { left: 0 };
        opts.animOut = { left : width*2, top:-height/2 , opacity:0, width:width, display:'block' };
    }
};

$.fn.cycle2.transitions.scrollLeftGap = {
    before: function( opts, curr, next, fwd ) {
        opts.API.stackSlides( opts, curr, next, fwd );
        var width = opts.container.css('overflow','hidden').width();
        opts.cssBefore = { width: width, left : width+100, top: 0, visibility: 'visible', opacity: 1, display: 'block' };
        opts.animIn = { left: 0 };
        opts.animOut = { left : -width-100,width:width };
    }
};

})(jQuery);
/* 
Function for basic functionality
*/
function rotatingtweetsInteraction( targetid ) {
	// Script to show mouseover effects when going over the Twitter intents
	var rtw_src,
		clearOutHovers = /_hover.png$/,
		srcReplacePattern = /.png$/;
	jQuery( targetid ).find('.rtw_intents a').hover(function() {
		rtw_src = jQuery(this).find('img').attr('src');
		jQuery(this).find('img').attr('src',rtw_src.replace(clearOutHovers,".png"));
		rtw_src = jQuery(this).find('img').attr('src');
		jQuery(this).find('img').attr('src',rtw_src.replace(srcReplacePattern,"_hover.png"));
	},function() {
		rtw_src = jQuery(this).find('img').attr('src');
		jQuery(this).find('img').attr('src',rtw_src.replace(clearOutHovers,".png"));
	});
	jQuery( targetid ).children().not('.cycle-carousel-wrap').has('.rtw_wide').find('.rtw_wide .rtw_intents').hide();
	jQuery( targetid ).children().not('.cycle-carousel-wrap').has('.rtw_wide').find('.rtw_expand').show();
	jQuery( targetid ).children().not('.cycle-carousel-wrap').has('.rtw_wide').hover(function() {
		jQuery(this).find('.rtw_intents').show();
	},function() {
		jQuery(this).find('.rtw_intents').hide();
	});
}

/*
 Script to cycle the rotating tweets
*/
jQuery(document).ready(function() {
	if (navigator.doNotTrack != 'yes' && navigator.doNotTrack != 1 && window.doNotTrack != 1 && navigator.msDoNotTrack != 1 && jQuery('div.follow-button').length ) {
		/* And call the Twitter script while we're at it! */
		/* Standard script to call Twitter */
		window.twttr = (function(d, s, id) {
		  var js, fjs = d.getElementsByTagName(s)[0],
			t = window.twttr || {};
		  if (d.getElementById(id)) return t;
		  js = d.createElement(s);
		  js.id = id;
		  js.src = "https://platform.twitter.com/widgets.js";
		  fjs.parentNode.insertBefore(js, fjs);
		 
		  t._e = [];
		  t.ready = function(f) {
			t._e.push(f);
		  };
		 
		  return t;
		}(document, "script", "twitter-wjs"));
	};

	rotatingtweetsInteraction('.rotatingtweets');
	
	if ( 'undefined' === typeof wp || ! wp.customize || ! wp.customize.selectiveRefresh || ! jQuery('div.follow-button').length ) {
        return;
    };
	// Re-load Twitter widgets when a partial is rendered.
    wp.customize.selectiveRefresh.bind( 'partial-content-rendered', function( placement ) {
		if ( placement.container ) {
			var newid = '#' + placement.container[0].id;
			rotatingtweetsInteraction(newid + " > .rotatingtweets" );
			jQuery( newid  + " > .rotatingtweets" ).cycle2('destroy');
			jQuery( newid  + " > .rotatingtweets" ).cycle2();
			if(navigator.doNotTrack != 'yes' && navigator.doNotTrack != 1 && window.doNotTrack != 1 && navigator.msDoNotTrack != 1) {
				twttr.widgets.load(
				  document.getElementById(newid)
				);
			};
		};
    } );
});
