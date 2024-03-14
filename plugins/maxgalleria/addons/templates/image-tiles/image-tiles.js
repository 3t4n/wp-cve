jQuery(document).ready(function() {
	jQuery("span.hidden-image-tiles-gallery-id").each(function() {
	  var gallery_id = jQuery(this).html();
		var mg_skin = jQuery("#maxgallery-" + gallery_id + " span.hidden-lightbox-skin").html();
		var mg_effect = jQuery("#maxgallery-" + gallery_id + " span.hidden-lightbox-effect").html();
		var mg_kb_nav = jQuery("#maxgallery-" + gallery_id + " span.hidden-lightbox-kb-nav").html();
		var mg_img_click_close = jQuery("#maxgallery-" + gallery_id + " span.hidden-lightbox-img-click-close").html();
		var mg_ol_click_close = jQuery("#maxgallery-" + gallery_id + " span.hidden-lightbox-ol-click-close").html();    
		var mg_close_tip_text = jQuery("#maxgallery-" + gallery_id + " span.hidden-lightbox-close-tip-text").html();        
		var mg_next_tip_text = jQuery("#maxgallery-" + gallery_id + " span.hidden-lightbox-next-tip-text").html();        
		var mg_prev_tip_text = jQuery("#maxgallery-" + gallery_id + " span.hidden-lightbox-prev-tip-text").html();        
		var mg_error_tip_text = jQuery("#maxgallery-" + gallery_id + " span.hidden-lightbox-error-tip-text").html();        
    
    
    mg_kb_nav = (mg_kb_nav === 'true') ? true : false;
    mg_img_click_close = (mg_img_click_close === 'true') ? true : false;
    mg_ol_click_close = (mg_ol_click_close === 'true') ? true : false;
    
    mg_close_tip_text = (mg_close_tip_text == '') ? 'Close' : mg_close_tip_text;
    mg_next_tip_text = (mg_next_tip_text == '') ? 'Next' : mg_next_tip_text;
    mg_prev_tip_text = (mg_prev_tip_text == '') ? 'Previous' : mg_prev_tip_text;
    mg_error_tip_text = (mg_error_tip_text == '') ? 'The requested content cannot be loaded. Please try again later.' : mg_error_tip_text;
        
    if(mg_skin == 'none') {
      jQuery('.lightbox-' + gallery_id).topbox({
        effect: mg_effect,
        clickImgToClose: mg_img_click_close,
        clickOverlayToClose: mg_ol_click_close,
        keyboardNav: mg_kb_nav,
        closeToolTip: mg_close_tip_text,
        nextToolTip: mg_next_tip_text,
        previousToolTip: mg_prev_tip_text,
        errorMessage: mg_error_tip_text
      });
    } else {       
      jQuery('.lightbox-' + gallery_id).topbox({
        effect: mg_effect,
        skin: mg_skin,
        clickImgToClose: mg_img_click_close,
        clickOverlayToClose: mg_ol_click_close,
        keyboardNav: mg_kb_nav,
        closeToolTip: mg_close_tip_text,
        nextToolTip: mg_next_tip_text,
        previousToolTip: mg_prev_tip_text,
        errorMessage: mg_error_tip_text
      });
    }
                     
	});
});