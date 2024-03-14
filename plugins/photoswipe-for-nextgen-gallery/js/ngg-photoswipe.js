//Ready for photoSwipe!
jQuery(function(){
	if (jQuery(".ngg-gallery-thumbnail a").length > 0) {
		jQuery(".ngg-gallery-thumbnail a").photoSwipe({
			captionAndToolbarAutoHideDelay: 0,
			getImageCaption: function(el){
				psTitle = jQuery(el).find("img").first().attr("alt");
				psDescription = jQuery(el).attr("title");
				psCaptionString = "<strong>" + psTitle + "</strong>";
				if (psDescription.length > 1) {
					psCaptionString = psCaptionString + '<div class="ps-long-description"><small>' + psDescription + '</small></div>';
				}
				psImgCaption = jQuery(psCaptionString);
				return psImgCaption;
			}
		});
	}
});

jQuery(document).ready(function(){
	//Remove thickbox effect:
	jQuery('a.thickbox').removeClass ("thickbox");
	//Remove lightbox effect:
	jQuery('a[rel^="lightbox"]').attr("rel","");
	//Remove highslide effect:
	jQuery('a.highslide').removeClass("highslide").attr("onclick","");
	//Remove shutter effect:
	jQuery('a[class^=shutterset]').removeClass (function (index, css) { return (css.match (/\bshutterset\S+/g) || []).join(' '); });	
});
