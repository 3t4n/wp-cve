jQuery(document).ready(function() {
	jQuery("span.hidden-video-tiles-gallery-id").each(function() {
		var gallery_id = jQuery(this).html();
		var thumb_click = jQuery("#maxgallery-" + gallery_id + " span.hidden-video-tiles-thumb-click").html();
		var mg_video_skin = jQuery("#maxgallery-" + gallery_id + " span.hidden-video-tiles-lightbox-skin").html();
		var mg_video_effect = jQuery("#maxgallery-" + gallery_id + " span.hidden-video-tiles-lightbox-effect").html();
		var mg_video_kb_nav = jQuery("#maxgallery-" + gallery_id + " span.hidden-video-tiles-lightbox-kb-nav").html();
		var mg_video_img_click_close = jQuery("#maxgallery-" + gallery_id + " span.hidden-video-tiles-lightbox-img-click-close").html();
		var mg_video_ol_click_close = jQuery("#maxgallery-" + gallery_id + " span.hidden-video-tiles-lightbox-ol-click-close").html();    
		var mg_video_close_tip_text = jQuery("#maxgallery-" + gallery_id + " span.hidden-video-tiles-lightbox-close-tip-text").html();        
		var mg_video_next_tip_text = jQuery("#maxgallery-" + gallery_id + " span.hidden-video-tiles-lightbox-next-tip-text").html();        
		var mg_video_prev_tip_text = jQuery("#maxgallery-" + gallery_id + " span.hidden-video-tiles-lightbox-prev-tip-text").html();        
		var mg_video_error_tip_text = jQuery("#maxgallery-" + gallery_id + " span.hidden-video-tiles-lightbox-error-tip-text").html();        
    
    
    mg_video_kb_nav = (mg_video_kb_nav === 'true') ? true : false;
    mg_video_img_click_close = (mg_video_img_click_close === 'true') ? true : false;
    mg_video_ol_click_close = (mg_video_ol_click_close === 'true') ? true : false;
    
    mg_video_close_tip_text = (mg_video_close_tip_text == '') ? 'Close' : mg_video_close_tip_text;
    mg_video_next_tip_text = (mg_video_next_tip_text == '') ? 'Next' : mg_video_next_tip_text;
    mg_video_prev_tip_text = (mg_video_prev_tip_text == '') ? 'Previous' : mg_video_prev_tip_text;
    mg_video_error_tip_text = (mg_video_error_tip_text == '') ? 'The requested content cannot be loaded. Please try again later.' : mg_video_error_tip_text;
        
    if(mg_video_skin == 'none') {
      jQuery('.video-lightbox-' + gallery_id).topbox({
        effect: mg_video_effect,
        clickImgToClose: mg_video_img_click_close,
        clickOverlayToClose: mg_video_ol_click_close,
        keyboardNav: mg_video_kb_nav,
        closeToolTip: mg_video_close_tip_text,
        nextToolTip: mg_video_next_tip_text,
        previousToolTip: mg_video_prev_tip_text,
        errorMessage: mg_video_error_tip_text
      });
    } else {       
      jQuery('.video-lightbox-' + gallery_id).topbox({
        effect: mg_video_effect,
        skin: mg_video_skin,
        clickImgToClose: mg_video_img_click_close,
        clickOverlayToClose: mg_video_ol_click_close,
        keyboardNav: mg_video_kb_nav,
        closeToolTip: mg_video_close_tip_text,
        nextToolTip: mg_video_next_tip_text,
        previousToolTip: mg_video_prev_tip_text,
        errorMessage: mg_video_error_tip_text
      });
    }
   
	});
   
});